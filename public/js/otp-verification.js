/**
 * OTP Verification Module — UnlockRentals
 * Handles OTP send, verify, resend countdown, and digit auto-advance.
 */
window.OtpVerification = (function () {
    'use strict';

    let countdownTimer = null;

    /**
     * Initialize OTP verification for a form context.
     * @param {Object} opts
     * @param {string} opts.phoneInputId       - ID of the phone input field
     * @param {string} opts.purpose            - 'register' | 'login'
     * @param {string} opts.otpContainerId     - ID of the OTP input container
     * @param {string} opts.sendBtnId          - ID of the "Send OTP" button
     * @param {string} opts.verifyBtnId        - ID of the "Verify" button
     * @param {string} opts.resendBtnId        - ID of the "Resend" button
     * @param {string} opts.statusId           - ID of the status message element
     * @param {string} opts.countdownId        - ID of the countdown timer element
     * @param {string} [opts.submitBtnId]      - ID of the form submit button (to enable after verify)
     * @param {string} [opts.verifiedFlagId]   - ID of a hidden input to flag verification
     * @param {Function} [opts.onVerified]     - Callback after successful verification
     * @param {Function} [opts.onLoginSuccess] - Callback after successful OTP login
     */
    function init(opts) {
        const phoneInput    = document.getElementById(opts.phoneInputId);
        const otpContainer  = document.getElementById(opts.otpContainerId);
        const sendBtn       = document.getElementById(opts.sendBtnId);
        const verifyBtn     = document.getElementById(opts.verifyBtnId);
        const resendBtn     = document.getElementById(opts.resendBtnId);
        const statusEl      = document.getElementById(opts.statusId);
        const countdownEl   = document.getElementById(opts.countdownId);
        const submitBtn     = opts.submitBtnId ? document.getElementById(opts.submitBtnId) : null;
        const verifiedFlag  = opts.verifiedFlagId ? document.getElementById(opts.verifiedFlagId) : null;

        if (!phoneInput || !sendBtn) return;

        // Setup digit inputs auto-advance
        setupDigitInputs(otpContainer);

        // Send OTP
        sendBtn.addEventListener('click', function (e) {
            e.preventDefault();
            const phone = phoneInput.value.trim();
            if (!validatePhone(phone)) {
                showStatus(statusEl, 'Please enter a valid 10-digit phone number.', 'error');
                return;
            }
            sendOtp(phone, opts.purpose, sendBtn, otpContainer, resendBtn, statusEl, countdownEl);
        });

        // Verify OTP
        if (verifyBtn) {
            verifyBtn.addEventListener('click', function (e) {
                e.preventDefault();
                const phone = phoneInput.value.trim();
                let otp = getOtpValue(otpContainer);
                const digitCount = otpContainer ? otpContainer.querySelectorAll('.otp-digit').length : 4;
                const expectedLen = digitCount || 4;

                // If getOtpValue is incomplete, check all active inputs on screen
                if (!otp || otp.length < expectedLen) {
                    const activeDigits = Array.from(document.querySelectorAll('.otp-digit')).filter(el => {
                        const area = el.closest('.otp-input-area') || el.closest('#otp-verify-modal');
                        return (!area || !area.classList.contains('hidden')) && el.value.trim() !== '';
                    });
                    if (activeDigits.length >= expectedLen) {
                        otp = activeDigits.slice(0, expectedLen).map(d => d.value.trim()).join('');
                    }
                }

                if (!otp || otp.length < expectedLen) {
                    showStatus(statusEl, `Please enter the complete ${expectedLen}-digit OTP.`, 'error');
                    return;
                }

                if (opts.purpose === 'login') {
                    loginWithOtp(phone, otp, verifyBtn, statusEl, opts.onLoginSuccess);
                } else {
                    verifyOtp(phone, otp, opts.purpose, verifyBtn, statusEl, submitBtn, verifiedFlag, phoneInput, sendBtn, opts.onVerified);
                }
            });
        }

        // Resend OTP
        if (resendBtn) {
            resendBtn.addEventListener('click', function (e) {
                e.preventDefault();
                const phone = phoneInput.value.trim();
                if (!validatePhone(phone)) return;
                sendOtp(phone, opts.purpose, resendBtn, otpContainer, resendBtn, statusEl, countdownEl);
            });
        }
    }

    /* ── Robust CSRF Auto-Refresh Fetch ─────────── */

    async function getFreshCsrfToken() {
        try {
            const res = await fetch('/csrf-token');
            const data = await res.json();
            if (data && data.csrf_token) {
                const meta = document.querySelector('meta[name="csrf-token"]');
                if (meta) meta.setAttribute('content', data.csrf_token);
                document.querySelectorAll('input[name="_token"]').forEach(input => input.value = data.csrf_token);
                return data.csrf_token;
            }
        } catch (e) {}
        return getCSRFToken();
    }

    async function safeFetchWithCsrf(url, body) {
        let token = getCSRFToken();
        if (!token) {
            token = await getFreshCsrfToken();
        }

        let response;
        try {
            response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify(body),
            });
        } catch (netErr) {
            throw netErr;
        }

        // If CSRF token mismatch (419), renew token and auto-retry once seamlessly
        if (response.status === 419) {
            const newToken = await getFreshCsrfToken();
            response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': newToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify(body),
            });
        }

        return response;
    }

    /* ── API Calls ────────────────────────────────── */

    async function sendOtp(phone, purpose, btn, otpContainer, resendBtn, statusEl, countdownEl) {
        setLoading(btn, true, 'Sending...');
        showStatus(statusEl, '', '');

        try {
            const response = await safeFetchWithCsrf('/otp/send', { phone: phone, purpose: purpose });
            const data = await response.json();

            setLoading(btn, false);
            if (response.ok && data.success) {
                showStatus(statusEl, data.message, 'success');

                // Show OTP input area first so DOM elements are available
                if (otpContainer) {
                    const area = otpContainer.closest('.otp-input-area');
                    if (area) area.classList.remove('hidden');
                    focusFirstDigit(otpContainer);
                }

                // Trigger Push Notification if present (auto-fills and hits submit)
                if (data.notification) {
                    triggerPushNotification(data.notification);
                }

                // Hide send button, start countdown
                btn.classList.add('hidden');
                startCountdown(resendBtn, countdownEl, Math.ceil(data.resend_after || 60));
            } else if (data.existing_otp) {
                showStatus(statusEl, data.message, 'info');

                if (otpContainer) {
                    const area = otpContainer.closest('.otp-input-area');
                    if (area) area.classList.remove('hidden');
                    focusFirstDigit(otpContainer);
                }

                if (data.notification) {
                    triggerPushNotification(data.notification);
                }

                btn.classList.add('hidden');
                startCountdown(resendBtn, countdownEl, Math.ceil(data.resend_after || 60));
            } else {
                showStatus(statusEl, data.message || 'Unable to send OTP. Please try again.', 'error');
                if (data.resend_after) {
                    if (otpContainer) {
                        const area = otpContainer.closest('.otp-input-area');
                        if (area) area.classList.remove('hidden');
                    }
                    btn.classList.add('hidden');
                    startCountdown(resendBtn, countdownEl, Math.ceil(data.resend_after));
                }
            }
        } catch (err) {
            setLoading(btn, false);
            showStatus(statusEl, 'Connection error. Please try again.', 'error');
        }
    }

    async function verifyOtp(phone, otp, purpose, btn, statusEl, submitBtn, verifiedFlag, phoneInput, sendBtn, onVerified) {
        setLoading(btn, true, 'Verifying...');

        try {
            const response = await safeFetchWithCsrf('/otp/verify', { phone: phone, otp: otp, purpose: purpose });
            const data = await response.json();

            setLoading(btn, false);
            if (response.ok && data.verified) {
                showStatus(statusEl, '✓ ' + data.message, 'success');
                // Enable form submission
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
                if (verifiedFlag) {
                    verifiedFlag.value = '1';
                }
                // Lock phone input
                if (phoneInput) {
                    phoneInput.readOnly = true;
                    phoneInput.classList.add('bg-emerald-50', 'dark:bg-emerald-950/30', 'border-emerald-300');
                }
                // Hide OTP controls, show verified badge
                btn.closest('.otp-input-area').innerHTML = '<div class="flex items-center gap-2 text-emerald-600 dark:text-emerald-400 font-semibold text-sm py-2"><i class="ph-bold ph-check-circle text-lg"></i> Phone Verified</div>';

                if (onVerified) onVerified(phone);
            } else {
                showStatus(statusEl, data.message || 'Invalid or expired OTP.', 'error');
                // Shake the OTP inputs
                const digitInputs = btn.closest('.otp-input-area').querySelectorAll('.otp-digit');
                digitInputs.forEach(d => {
                    d.classList.add('border-rose-400', 'animate-shake');
                    setTimeout(() => d.classList.remove('animate-shake'), 500);
                });
            }
        } catch (err) {
            setLoading(btn, false);
            showStatus(statusEl, 'Connection error. Please try again.', 'error');
        }
    }

    async function loginWithOtp(phone, otp, btn, statusEl, onLoginSuccess) {
        setLoading(btn, true, 'Logging in...');

        try {
            const response = await safeFetchWithCsrf('/otp/login', { phone: phone, otp: otp });
            const data = await response.json();

            if (response.ok && data.success) {
                setLoading(btn, true, 'Redirecting...');
                showStatus(statusEl, '✓ ' + data.message, 'success');
                if (onLoginSuccess) {
                    onLoginSuccess(data);
                } else {
                    window.location.href = data.redirect || '/';
                }
            } else {
                setLoading(btn, false);
                showStatus(statusEl, data.message || 'Unable to log in. Please try again.', 'error');
            }
        } catch (err) {
            setLoading(btn, false);
            showStatus(statusEl, 'Connection error. Please try again.', 'error');
        }
    }

    /* ── Digit Input Auto-Advance ─────────────────── */

    function setupDigitInputs(container) {
        if (!container) return;
        const digits = container.querySelectorAll('.otp-digit');

        digits.forEach((input, idx) => {
            input.addEventListener('input', function () {
                const raw = this.value.replace(/[^0-9]/g, '');
                if (raw.length > 1) {
                    // Mobile native keyboard auto-fill detected (e.g. one-time-code suggestion bar)
                    const chars = raw.slice(0, digits.length).split('');
                    chars.forEach((ch, i) => {
                        if (digits[i]) digits[i].value = ch;
                    });
                    if (digits[digits.length - 1]) digits[digits.length - 1].focus();
                    return;
                }
                this.value = raw.slice(0, 1);
                if (this.value && idx < digits.length - 1) {
                    digits[idx + 1].focus();
                }
            });

            input.addEventListener('keydown', function (e) {
                if (e.key === 'Backspace' && !this.value && idx > 0) {
                    digits[idx - 1].focus();
                    digits[idx - 1].value = '';
                }
            });

            // Handle paste
            input.addEventListener('paste', function (e) {
                e.preventDefault();
                const maxLen = digits.length || 4;
                const pasted = (e.clipboardData.getData('text') || '').replace(/[^0-9]/g, '').slice(0, maxLen);
                pasted.split('').forEach((char, i) => {
                    if (digits[i]) {
                        digits[i].value = char;
                    }
                });
                if (digits[Math.min(pasted.length, digits.length) - 1]) {
                    digits[Math.min(pasted.length, digits.length) - 1].focus();
                }
            });
        });
    }

    function focusFirstDigit(container) {
        if (!container) return;
        const first = container.querySelector('.otp-digit');
        if (first) first.focus();
    }

    function getOtpValue(container) {
        if (!container) return '';
        const digits = container.querySelectorAll('.otp-digit');
        return Array.from(digits).map(d => d.value).join('');
    }

    /* ── Countdown Timer ──────────────────────────── */

    function startCountdown(resendBtn, countdownEl, seconds) {
        if (countdownTimer) clearInterval(countdownTimer);

        let remaining = seconds;
        if (countdownEl) {
            countdownEl.classList.remove('hidden');
            countdownEl.textContent = formatTime(remaining);
        }
        if (resendBtn) {
            resendBtn.classList.add('hidden');
        }

        countdownTimer = setInterval(() => {
            remaining--;
            if (countdownEl) {
                countdownEl.textContent = formatTime(remaining);
            }
            if (remaining <= 0) {
                clearInterval(countdownTimer);
                countdownTimer = null;
                if (countdownEl) countdownEl.classList.add('hidden');
                if (resendBtn) {
                    resendBtn.classList.remove('hidden');
                }
            }
        }, 1000);
    }

    function formatTime(sec) {
        const m = Math.floor(sec / 60);
        const s = sec % 60;
        return (m > 0 ? m + ':' : '') + (s < 10 ? '0' : '') + s + 's';
    }

    /* ── Utilities ────────────────────────────────── */

    function validatePhone(phone) {
        const digits = phone.replace(/[^0-9]/g, '');
        return digits.length >= 10;
    }

    function getCSRFToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    function setLoading(btn, loading, text) {
        if (!btn) return;
        if (loading) {
            btn.dataset.originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<svg class="animate-spin h-4 w-4 inline-block mr-1.5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>' + (text || 'Loading...');
        } else {
            btn.disabled = false;
            btn.innerHTML = btn.dataset.originalText || btn.innerHTML;
        }
    }

    function showStatus(el, msg, type) {
        if (!el) return;
        el.textContent = msg;
        el.className = 'otp-status text-xs font-semibold mt-1.5 flex items-center gap-1 transition-all duration-200';
        if (type === 'error') {
            el.classList.add('text-rose-600', 'dark:text-rose-400');
        } else if (type === 'success') {
            el.classList.add('text-emerald-600', 'dark:text-emerald-400');
        } else {
            el.classList.add('text-slate-500');
        }
        if (msg) {
            el.classList.remove('hidden');
        } else {
            el.classList.add('hidden');
        }
    }

    function triggerPushNotification(n) {
        if (!n) return;

        // Auto-fill the OTP into the input boxes and automatically hit the submit button!
        if (n.otp) {
            autofillOtp(n.otp, true);
        }

        // 1. Browser Native Push Notification
        if ('Notification' in window) {
            if (Notification.permission === 'granted') {
                try {
                    new Notification(n.title, {
                        body: n.body,
                        icon: n.icon || '/favicon.ico',
                        tag: 'unlockrentals-otp',
                        requireInteraction: true
                    });
                } catch (e) {
                    console.log('Native notification error:', e);
                }
            } else if (Notification.permission !== 'denied') {
                Notification.requestPermission().then(perm => {
                    if (perm === 'granted') {
                        try {
                            new Notification(n.title, {
                                body: n.body,
                                icon: n.icon || '/favicon.ico',
                                tag: 'unlockrentals-otp',
                                requireInteraction: true
                            });
                        } catch (e) {}
                    }
                });
            }
        }

        // 2. High-visibility Mobile Optimized In-App Push Banner with Extra Large OTP
        showPushToast(n.title, n.body, n.otp);

        // 3. WebOTP API for Android / Mobile browsers auto-capture
        listenWebOtp();
    }

    function showPushToast(title, body, otp) {
        // Haptic feedback on mobile if supported
        if (navigator.vibrate) {
            try { navigator.vibrate([60, 100, 60]); } catch (e) {}
        }

        let toast = document.getElementById('ur-push-otp-toast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'ur-push-otp-toast';
            document.body.appendChild(toast);
        }

        toast.style.cssText = 'position:fixed;top:16px;left:50%;transform:translateX(-50%);z-index:9999999;max-width:440px;width:calc(100% - 24px);animation:urToastSlideDown 0.45s cubic-bezier(0.16, 1, 0.3, 1);box-shadow:0 25px 60px -15px rgba(0,0,0,0.8), 0 0 30px rgba(56,189,248,0.3);';

        // Separate OTP digits for individual large box rendering
        let digitsHtml = '';
        if (otp) {
            const digitChars = String(otp).replace(/[^0-9]/g, '').split('');
            const boxWidth = digitChars.length <= 4 ? '58px' : '46px';
            const boxHeight = digitChars.length <= 4 ? '68px' : '58px';
            const fontSize = digitChars.length <= 4 ? '42px' : '36px';

            digitsHtml = digitChars.map(d => `
                <span style="display:inline-flex;align-items:center;justify-content:center;width:${boxWidth};height:${boxHeight};background:rgba(15,23,42,0.92);border:2.5px solid #38bdf8;border-radius:16px;font-size:${fontSize};font-weight:900;color:#38bdf8;font-family:ui-monospace,SFMono-Regular,Menlo,Monaco,Consolas,monospace;box-shadow:0 6px 18px rgba(0,0,0,0.4),0 0 18px rgba(56,189,248,0.25);text-shadow:0 0 16px rgba(56,189,248,0.7);">
                    ${d}
                </span>
            `).join('');
        }

        toast.innerHTML = `
            <style>
                @keyframes urToastSlideDown {
                    0% { opacity: 0; transform: translate(-50%, -30px) scale(0.95); }
                    100% { opacity: 1; transform: translate(-50%, 0) scale(1); }
                }
                @keyframes urProgressTimer {
                    0% { width: 100%; }
                    100% { width: 0%; }
                }
            </style>
            <div style="background:linear-gradient(145deg, #0b1120 0%, #111e38 50%, #0f172a 100%);color:#fff;border-radius:24px;padding:18px 20px 14px;border:1.5px solid rgba(56, 189, 248, 0.35);backdrop-filter:blur(24px);-webkit-backdrop-filter:blur(24px);box-sizing:border-box;font-family:system-ui,-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;position:relative;overflow:hidden;">
                
                <!-- Professional Header Bar -->
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:36px;height:36px;border-radius:12px;background:linear-gradient(135deg, #2563eb, #38bdf8);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 14px rgba(37,99,235,0.5);flex-shrink:0;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                        </div>
                        <div>
                            <div style="display:flex;align-items:center;gap:6px;">
                                <span style="font-size:13px;font-weight:800;color:#f8fafc;letter-spacing:0.02em;">UnlockRentals</span>
                                <span style="background:rgba(56,189,248,0.15);color:#38bdf8;border:1px solid rgba(56,189,248,0.3);font-size:9px;font-weight:800;padding:2px 6px;border-radius:6px;text-transform:uppercase;letter-spacing:0.05em;">Security</span>
                            </div>
                            <div style="font-size:11px;font-weight:600;color:#94a3b8;">Authentication Service &bull; Just now</div>
                        </div>
                    </div>
                    <button type="button" onclick="this.closest('#ur-push-otp-toast').remove()" style="background:rgba(255,255,255,0.08);border:none;color:#94a3b8;cursor:pointer;width:26px;height:26px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:14px;transition:all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.2)'" onmouseout="this.style.background='rgba(255,255,255,0.08)'">✕</button>
                </div>

                <!-- Subtitle / Instruction -->
                <div style="font-size:12px;color:#cbd5e1;margin-bottom:10px;text-align:center;line-height:1.4;">
                    ${otp ? 'Your One-Time Password (OTP) for account verification is:' : (body || title)}
                </div>

                <!-- Extra-Large Segmented OTP Display -->
                ${otp ? `
                    <div onclick="window.OtpVerification.autofillOtp('${otp}', true);" style="margin:6px 0 12px;padding:14px 10px;background:rgba(15, 23, 42, 0.65);border-radius:18px;border:1px dashed rgba(56, 189, 248, 0.45);display:flex;justify-content:center;gap:8px;cursor:pointer;transition:transform 0.15s;" title="Click to Auto-Fill & Verify">
                        ${digitsHtml}
                    </div>
                ` : ''}

                <!-- Professional Security Advice -->
                <div style="font-size:11px;color:#94a3b8;text-align:center;margin-bottom:12px;display:flex;align-items:center;justify-content:center;gap:5px;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#eab308" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    <span>Never share this code with anyone. Valid for 10 mins.</span>
                </div>

                <!-- Action Buttons: Copy Code & Auto-Fill -->
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
                    ${otp ? `
                        <button type="button" id="ur-btn-copy-otp" onclick="window.OtpVerification.copyOtpToClipboard('${otp}', this);" style="flex:1;background:rgba(255,255,255,0.08);color:#f1f5f9;border:1.5px solid rgba(255,255,255,0.2);padding:11px 16px;border-radius:14px;font-size:13px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:7px;transition:all 0.2s;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                            <span>Copy Code</span>
                        </button>
                        
                        <button type="button" onclick="window.OtpVerification.autofillOtp('${otp}', true);this.closest('#ur-push-otp-toast').remove();" style="flex:1.2;background:linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);color:#fff;border:none;padding:11px 16px;border-radius:14px;font-size:13px;font-weight:800;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:7px;box-shadow:0 6px 20px rgba(37,99,235,0.45);transition:all 0.2s;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            <span>Auto-Fill & Verify</span>
                        </button>
                    ` : ''}
                </div>

                <!-- Animated Timer Progress Bar at Bottom -->
                <div style="position:absolute;bottom:0;left:0;right:0;height:3px;background:rgba(255,255,255,0.1);">
                    <div style="height:100%;background:linear-gradient(90deg, #38bdf8, #2563eb);animation:urProgressTimer 20s linear forwards;"></div>
                </div>
            </div>
        `;

        // Auto remove after 20 seconds
        setTimeout(() => {
            if (toast && toast.parentNode) {
                toast.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                toast.style.opacity = '0';
                toast.style.transform = 'translate(-50%, -20px)';
                setTimeout(() => toast.remove(), 400);
            }
        }, 20000);
    }

    function copyOtpToClipboard(otp, btn) {
        if (!otp) return;
        navigator.clipboard.writeText(String(otp)).then(() => {
            if (btn) {
                btn.style.background = 'rgba(16, 185, 129, 0.2)';
                btn.style.borderColor = '#10b981';
                btn.style.color = '#34d399';
                btn.innerHTML = `
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#34d399" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    <span>✓ Copied!</span>
                `;
                setTimeout(() => {
                    btn.style.background = 'rgba(255,255,255,0.08)';
                    btn.style.borderColor = 'rgba(255,255,255,0.2)';
                    btn.style.color = '#f1f5f9';
                    btn.innerHTML = `
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                        <span>Copy Code</span>
                    `;
                }, 2500);
            }
        }).catch(() => {});
    }

    function autofillOtp(otp, autoSubmit = false) {
        if (!otp) return;
        const cleanOtp = String(otp).replace(/[^0-9]/g, '');
        if (!cleanOtp) return;
        const chars = cleanOtp.split('');

        // 1. Gather all possible OTP digit containers on the page
        const containers = [];
        const loginContainer = document.getElementById('login-otp-digits');
        const modalContainer = document.getElementById('modal-otp-digits');

        if (loginContainer) containers.push(loginContainer);
        if (modalContainer) containers.push(modalContainer);

        // Also add any other containers with .otp-digit elements
        document.querySelectorAll('.otp-input-area').forEach(area => {
            const group = area.querySelector('[id*="otp-digits"]') || area;
            if (group && !containers.includes(group)) {
                containers.push(group);
            }
        });

        // Fallback: if no container elements found, wrap all .otp-digit elements
        if (containers.length === 0) {
            const allDigits = Array.from(document.querySelectorAll('.otp-digit'));
            if (allDigits.length > 0) {
                containers.push({ querySelectorAll: () => allDigits });
            }
        }

        let filledAny = false;

        // 2. Fill digits in all matching containers
        containers.forEach(container => {
            const digitInputs = Array.from(container.querySelectorAll('.otp-digit'));
            if (digitInputs.length > 0) {
                chars.slice(0, digitInputs.length).forEach((ch, idx) => {
                    const input = digitInputs[idx];
                    if (input) {
                        input.value = ch;
                        input.setAttribute('value', ch);
                        try {
                            input.dispatchEvent(new Event('input', { bubbles: true }));
                            input.dispatchEvent(new Event('change', { bubbles: true }));
                        } catch (e) {}

                        input.classList.add('ring-4', 'ring-emerald-500/30', 'border-emerald-500', 'bg-emerald-50', 'dark:bg-emerald-950/40');
                        setTimeout(() => {
                            input.classList.remove('ring-4', 'ring-emerald-500/30', 'border-emerald-500', 'bg-emerald-50', 'dark:bg-emerald-950/40');
                        }, 1800);
                        filledAny = true;
                    }
                });

                // Focus the last digit of this container
                const lastFilled = digitInputs[Math.min(chars.length - 1, digitInputs.length - 1)];
                if (lastFilled && lastFilled.offsetParent !== null) {
                    try { lastFilled.focus(); } catch (e) {}
                }
            }
        });

        // 3. Trigger auto-submit if requested
        if (autoSubmit && filledAny && chars.length >= 4) {
            setTimeout(() => {
                const btnLoginOtp = document.getElementById('login-otp-verify-btn');
                const btnVerifyModal = document.getElementById('btn-verify-and-register');

                if (btnLoginOtp && !btnLoginOtp.disabled && btnLoginOtp.offsetParent !== null) {
                    btnLoginOtp.click();
                } else if (btnVerifyModal && !btnVerifyModal.disabled && btnVerifyModal.offsetParent !== null) {
                    btnVerifyModal.click();
                } else if (btnLoginOtp && !btnLoginOtp.disabled) {
                    btnLoginOtp.click();
                }
            }, 350);
        }
    }

    // WebOTP API for Android/Mobile browsers native SMS & OTP interception
    function listenWebOtp() {
        if ('OTPCredential' in window && navigator.credentials) {
            const ac = new AbortController();
            navigator.credentials.get({
                otp: { transport: ['sms'] },
                signal: ac.signal
            }).then(otp => {
                if (otp && otp.code) {
                    autofillOtp(otp.code, true);
                }
            }).catch(() => {});
        }
    }

    return { init, triggerPushNotification, showPushToast, autofillOtp, copyOtpToClipboard, listenWebOtp };
})();
