/**
 * UnlockRentals premium subscription checkout helpers.
 * 
 * THREE-LAYER PAYMENT COMPLETION SYSTEM:
 * 1. Standard Razorpay handler callback (ideal path)
 * 2. Server-side order polling every 3s (catches UPI QR / mobile payments)
 * 3. Manual Payment ID entry (last resort fallback)
 */
window.UnlockSubscriptionCheckout = (config) => {
    const {
        form,
        methodInput,
        phoneInput,
        phoneError,
        phoneValidIcon,
        phoneSyncBadge,
        payButton,
        summaryPayButton,
        overlay,
        progressBar,
        processingStatusText,
        isRazorpay,
        razorpayOrderUrl,
        checkOrderStatusUrl,
        csrfToken,
        plansUrl,
        billingPeriod,
        planName,
        brandLogo,
        userPrefill = {},
        manualPaymentLink,
    } = config;

    let pollingInterval = null;
    let paymentCompleted = false;
    let hasDismissedModal = false;
    let isOpeningRazorpay = false;
    let currentOrderId = null;

    function saveActivePendingOrder(orderId) {
        currentOrderId = orderId;
        try {
            sessionStorage.setItem('ur_pending_order_id', orderId);
            sessionStorage.setItem('ur_pending_order_time', Date.now().toString());
            sessionStorage.setItem('ur_pending_plan_id', config.planId ? String(config.planId) : '');
            localStorage.setItem('ur_pending_order_id', orderId);
            localStorage.setItem('ur_pending_order_time', Date.now().toString());
        } catch (_) {}
    }

    function clearActivePendingOrder() {
        try {
            sessionStorage.removeItem('ur_pending_order_id');
            sessionStorage.removeItem('ur_pending_order_time');
            sessionStorage.removeItem('ur_pending_plan_id');
            localStorage.removeItem('ur_pending_order_id');
            localStorage.removeItem('ur_pending_order_time');
        } catch (_) {}
    }

    // Helper: Clean non-digits and extract 10-digit number
    function extract10Digits(val) {
        if (!val) return '';
        const digits = String(val).replace(/\D/g, '');
        if (digits.length >= 10) {
            return digits.slice(-10);
        }
        return digits;
    }

    // Validate 10-digit Indian mobile number (starts with 6, 7, 8, or 9)
    function isValidIndianMobile(digits) {
        return /^[6-9]\d{9}$/.test(digits);
    }

    // Live phone input validation & formatting
    if (phoneInput) {
        let cachedStoragePhone = '';
        try {
            cachedStoragePhone = localStorage.getItem('ur_user_phone') || '';
        } catch (_) {}

        const initialDigits = extract10Digits(phoneInput.value || userPrefill.contact || cachedStoragePhone || '');
        if (initialDigits) {
            phoneInput.value = initialDigits;
            if (isValidIndianMobile(initialDigits)) {
                phoneValidIcon?.classList.remove('opacity-0');
                phoneValidIcon?.classList.add('opacity-100');
                try {
                    localStorage.setItem('ur_user_phone', initialDigits);
                } catch (_) {}
            }
        }

        phoneInput.addEventListener('input', (e) => {
            const raw = e.target.value;
            const cleaned = extract10Digits(raw);
            phoneInput.value = cleaned;

            if (isValidIndianMobile(cleaned)) {
                try {
                    localStorage.setItem('ur_user_phone', cleaned);
                } catch (_) {}

                phoneError?.classList.add('hidden');
                phoneValidIcon?.classList.remove('opacity-0');
                phoneValidIcon?.classList.add('opacity-100');
                phoneInput.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500/20');
                phoneInput.classList.add('border-emerald-500', 'focus:border-emerald-500');
                if (phoneSyncBadge) {
                    phoneSyncBadge.innerHTML = '<i class="ph-bold ph-check-circle"></i> Phone number verified';
                    phoneSyncBadge.className = 'inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600 dark:text-emerald-400';
                }

                // If user just typed 10 digits and hasn't opened Razorpay yet, launch it automatically
                if (isRazorpay && !hasDismissedModal && !isOpeningRazorpay) {
                    setTimeout(() => {
                        payButton?.click();
                    }, 350);
                }
            } else {
                phoneValidIcon?.classList.remove('opacity-100');
                phoneValidIcon?.classList.add('opacity-0');
                phoneInput.classList.remove('border-emerald-500', 'focus:border-emerald-500');
            }
        });

        phoneInput.addEventListener('blur', () => {
            const cleaned = extract10Digits(phoneInput.value);
            if (cleaned.length > 0 && !isValidIndianMobile(cleaned)) {
                phoneError?.classList.remove('hidden');
                phoneInput.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500/20');
            } else {
                phoneError?.classList.add('hidden');
                phoneInput.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500/20');
            }
        });
    }

    function resolveContactNumber() {
        let raw = '';
        if (phoneInput && phoneInput.value) {
            raw = phoneInput.value;
        } else if (userPrefill && userPrefill.contact) {
            raw = userPrefill.contact;
        } else {
            try {
                raw = localStorage.getItem('ur_user_phone') || '';
            } catch (_) {}
        }

        const cleaned = extract10Digits(raw);
        if (isValidIndianMobile(cleaned)) {
            if (phoneInput && !phoneInput.value) {
                phoneInput.value = cleaned;
                phoneValidIcon?.classList.remove('opacity-0');
                phoneValidIcon?.classList.add('opacity-100');
            }
            try {
                localStorage.setItem('ur_user_phone', cleaned);
            } catch (_) {}
            return cleaned;
        }

        return cleaned || '';
    }

    summaryPayButton?.addEventListener('click', () => payButton?.click());

    document.querySelectorAll('.method-card').forEach(card => {
        card.addEventListener('click', () => {
            document.querySelectorAll('.method-card').forEach(item => item.classList.remove('selected'));
            card.classList.add('selected');
            if (methodInput) methodInput.value = card.dataset.method;
        });
    });

    function showLoading(statusText) {
        if (overlay) {
            overlay.classList.remove('hidden');
            overlay.classList.add('flex');
            overlay.setAttribute('aria-hidden', 'false');
        }
        if (progressBar) {
            progressBar.style.width = '0%';
            requestAnimationFrame(() => {
                progressBar.style.width = '88%';
            });
        }
        if (processingStatusText && statusText) {
            processingStatusText.textContent = statusText;
        }
        if (!payButton) return;
        payButton.disabled = true;
        payButton.querySelector('.btn-text')?.classList.add('hidden');
        const loader = payButton.querySelector('.btn-loader');
        loader?.classList.remove('hidden');
        loader?.classList.add('flex');
    }

    function hideLoading() {
        overlay?.classList.add('hidden');
        overlay?.classList.remove('flex');
        overlay?.setAttribute('aria-hidden', 'true');
        if (payButton) {
            payButton.disabled = false;
            payButton.querySelector('.btn-text')?.classList.remove('hidden');
            const loader = payButton.querySelector('.btn-loader');
            loader?.classList.add('hidden');
            loader?.classList.remove('flex');
        }
    }

    function stopPolling() {
        if (pollingInterval) {
            clearInterval(pollingInterval);
            pollingInterval = null;
        }
    }

    function redirectToPlansWithFailure(reason) {
        stopPolling();
        clearActivePendingOrder();
        const url = new URL(plansUrl, window.location.origin);
        url.searchParams.set('payment_failed', '1');
        url.searchParams.set('reason', reason || 'Payment failed. Please try again or choose another payment method.');
        window.location.href = url.toString();
    }

    /**
     * Submit the payment form with the given payment details (fallback path).
     */
    function submitPaymentForm(paymentId, orderId, signature) {
        if (paymentCompleted) return;
        paymentCompleted = true;
        stopPolling();
        clearActivePendingOrder();

        showLoading('Payment verified! Activating your premium plan...');
        if (progressBar) {
            progressBar.style.transition = 'width 1s ease';
            progressBar.style.width = '100%';
        }

        const payIdEl = document.getElementById('razorpay_payment_id');
        const orderIdEl = document.getElementById('razorpay_order_id');
        const sigEl = document.getElementById('razorpay_signature');

        if (payIdEl) payIdEl.value = paymentId;
        if (orderIdEl) orderIdEl.value = orderId || '';
        if (sigEl) sigEl.value = signature || '';

        setTimeout(() => form.submit(), 400);
    }

    /**
     * Single check of order status with automatic server activation.
     */
    async function checkOrderStatusOnce(orderId) {
        if (!checkOrderStatusUrl || paymentCompleted || !orderId) return;

        try {
            const resp = await fetch(checkOrderStatusUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({
                    order_id: orderId,
                    billing_period: billingPeriod,
                }),
            });

            if (!resp.ok) return;
            const data = await resp.json();

            if (data.status === 'paid' && data.payment_id) {
                paymentCompleted = true;
                stopPolling();
                clearActivePendingOrder();

                showLoading('Payment verified! Plan activated! Redirecting to dashboard...');
                if (progressBar) {
                    progressBar.style.transition = 'width 0.8s ease';
                    progressBar.style.width = '100%';
                }

                setTimeout(() => {
                    window.location.href = data.redirect_url || config.dashboardUrl || '/dashboard';
                }, 400);
            }
        } catch (_) {
            // Silently retry on next tick
        }
    }

    /**
     * Start continuous order polling (active for 10 minutes to allow mobile UPI completion).
     */
    function startOrderPolling(orderId) {
        if (!checkOrderStatusUrl) return;
        saveActivePendingOrder(orderId);

        if (pollingInterval) return;

        let pollCount = 0;
        const maxPolls = 240; // 240 * 2.5s = 10 minutes

        pollingInterval = setInterval(async () => {
            if (paymentCompleted) {
                stopPolling();
                return;
            }

            pollCount++;
            if (pollCount > maxPolls) {
                stopPolling();
                return;
            }

            await checkOrderStatusOnce(orderId);
        }, 2500);
    }

    // Mobile App & Browser Resume Handler:
    // When returning from PhonePe, GPay, Paytm, or an external bank app, immediately check order status!
    function handlePageResume() {
        if (currentOrderId && !paymentCompleted) {
            checkOrderStatusOnce(currentOrderId);
            if (!pollingInterval) {
                startOrderPolling(currentOrderId);
            }
        }
    }

    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'visible') {
            handlePageResume();
        }
    });
    window.addEventListener('focus', handlePageResume);
    window.addEventListener('pageshow', handlePageResume);
    window.addEventListener('app_resumed', handlePageResume);
    window.checkPendingPaymentOnResume = handlePageResume;

    // Check if user reloaded with a pending order from last 15 minutes
    try {
        const savedOrderId = sessionStorage.getItem('ur_pending_order_id') || localStorage.getItem('ur_pending_order_id');
        const savedTime = parseInt(sessionStorage.getItem('ur_pending_order_time') || localStorage.getItem('ur_pending_order_time') || '0', 10);
        if (savedOrderId && (Date.now() - savedTime) < 15 * 60 * 1000) {
            currentOrderId = savedOrderId;
            const fallback = document.getElementById('manual-verify-section');
            if (fallback) fallback.classList.remove('hidden');
            checkOrderStatusOnce(savedOrderId);
            startOrderPolling(savedOrderId);
        }
    } catch (_) {}

    function razorpayMethodConfig(selectedMethod) {
        return { netbanking: true, card: true, upi: true, wallet: true };
    }

    if (isRazorpay && typeof Razorpay !== 'undefined') {
        payButton?.addEventListener('click', async () => {
            if (isOpeningRazorpay) return;

            if (!config.razorpayKeyConfigured) {
                alert('Razorpay is selected but API credentials are not configured by admin.');
                return;
            }

            // Get clean 10-digit mobile number for Razorpay auto-fill
            const contactNumber = resolveContactNumber();

            isOpeningRazorpay = true;
            paymentCompleted = false;
            showLoading('Connecting securely to Razorpay...');

            let order;
            try {
                const orderPayload = {
                    billing_period: billingPeriod,
                };
                if (contactNumber) {
                    orderPayload.phone = contactNumber;
                }

                const orderResponse = await fetch(razorpayOrderUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify(orderPayload),
                });
                order = await orderResponse.json();
                if (!orderResponse.ok) {
                    throw new Error(order.message || 'Unable to create Razorpay order.');
                }
            } catch (error) {
                isOpeningRazorpay = false;
                redirectToPlansWithFailure(error.message || 'Network issue detected. Check your connection and try again.');
                return;
            }

            saveActivePendingOrder(order.order_id);

            // Start polling immediately
            startOrderPolling(order.order_id);

            const selectedMethod = methodInput?.value || 'razorpay';
            const logoUrl = brandLogo || (window.location.origin + '/images/logo-icon.png');

            const prefillData = {
                name: userPrefill.name || order.user_name || '',
                email: userPrefill.email || order.user_email || '',
                method: (selectedMethod !== 'razorpay') ? selectedMethod : undefined,
            };

            const bestPhone = order.user_phone || (userPrefill && userPrefill.contact) || contactNumber;
            if (bestPhone) {
                const digits = String(bestPhone).replace(/\D/g, '').slice(-10);
                if (digits.length === 10) {
                    prefillData.contact = digits;
                    if (phoneInput && !phoneInput.value) {
                        phoneInput.value = digits;
                        phoneValidIcon?.classList.remove('opacity-0');
                        phoneValidIcon?.classList.add('opacity-100');
                    }
                    try { localStorage.setItem('ur_user_phone', digits); } catch (_) {}
                }
            }

            const razorpayOptions = {
                key: order.key_id,
                amount: order.amount,
                currency: order.currency,
                name: 'UnlockRentals',
                description: `${planName} (${billingPeriod === 'yearly' ? 'Annual' : 'Monthly'} Membership)`,
                image: logoUrl,
                order_id: order.order_id,
                method: razorpayMethodConfig(selectedMethod),
                callback_url: config.callbackUrl || undefined,
                handler: function (response) {
                    isOpeningRazorpay = false;
                    submitPaymentForm(
                        response.razorpay_payment_id,
                        response.razorpay_order_id,
                        response.razorpay_signature
                    );
                },
                prefill: prefillData,
                notes: {
                    user_id: userPrefill.id || '',
                    plan_name: planName,
                    billing_period: billingPeriod,
                },
                theme: {
                    color: '#2563EB',
                    backdrop_color: 'rgba(15, 23, 42, 0.75)',
                },
                send_sms_hash: true,
                retry: {
                    enabled: true,
                    max_count: 3,
                },
                modal: {
                    confirm_close: true,
                    animation: true,
                    backdropclose: false,
                    escape: true,
                    ondismiss: function () {
                        isOpeningRazorpay = false;
                        hasDismissedModal = true;
                        hideLoading();
                        
                        const fallback = document.getElementById('manual-verify-section');
                        if (fallback) {
                            fallback.classList.remove('hidden');
                            fallback.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }

                        // On mobile, opening PhonePe or Google Pay triggers ondismiss when switching apps.
                        // We DO NOT stop polling here! We keep checking order status so that when payment succeeds,
                        // it activates automatically.
                        if (currentOrderId && !paymentCompleted) {
                            checkOrderStatusOnce(currentOrderId);
                            if (!pollingInterval) {
                                startOrderPolling(currentOrderId);
                            }
                        }
                    },
                },
            };

            const razorpay = new Razorpay(razorpayOptions);

            razorpay.on('payment.failed', function (response) {
                isOpeningRazorpay = false;
                stopPolling();
                clearActivePendingOrder();
                const error = response.error || {};
                const reason = error.description || error.reason || error.code || 'Payment was declined by your bank or payment provider.';
                redirectToPlansWithFailure(reason);
            });

            hideLoading();
            razorpay.open();
        });

        // Instant Direct Razorpay Launch: Auto-launch if valid phone number is available
        setTimeout(() => {
            if (!hasDismissedModal && !paymentCompleted && !isOpeningRazorpay && !currentOrderId) {
                const existingPhone = resolveContactNumber();
                if (isValidIndianMobile(existingPhone)) {
                    payButton?.click();
                } else if (phoneInput) {
                    phoneInput.focus();
                    phoneInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        }, 150);

    } else {
        payButton?.addEventListener('click', (event) => {
            if (manualPaymentLink && form && !form.checkValidity()) {
                event.preventDefault();
                window.location.href = manualPaymentLink;
            }
        });
        form?.addEventListener('submit', () => showLoading('Submitting payment proof for secure verification...'));
    }

    // ── Manual / Immediate Verification Button Handler ──
    const manualVerifyBtn = document.getElementById('manual-verify-btn');
    const manualVerifyCustomBtn = document.getElementById('manual-verify-custom-btn');
    const manualPaymentInput = document.getElementById('manual_razorpay_payment_id');
    const errorEl = document.getElementById('manual-verify-error');

    // 1. One-click "I Have Completed Payment — Verify & Activate Now" button
    manualVerifyBtn?.addEventListener('click', async () => {
        if (!currentOrderId) {
            payButton?.click();
            return;
        }

        const originalHtml = manualVerifyBtn.innerHTML;
        manualVerifyBtn.disabled = true;
        manualVerifyBtn.innerHTML = '<i class="ph-bold ph-circle-notch animate-spin"></i> Checking with Razorpay...';
        showLoading('Verifying payment confirmation from your bank/UPI app...');

        await checkOrderStatusOnce(currentOrderId);

        setTimeout(() => {
            if (!paymentCompleted) {
                hideLoading();
                manualVerifyBtn.disabled = false;
                manualVerifyBtn.innerHTML = '<i class="ph-bold ph-arrows-clockwise"></i> Check Again';
                if (errorEl) {
                    errorEl.textContent = 'Payment confirmation is still pending from the bank. Please wait a moment and tap again.';
                    errorEl.classList.remove('hidden');
                }
            }
        }, 2000);
    });

    // 2. Custom ID input submission
    manualVerifyCustomBtn?.addEventListener('click', () => {
        const idVal = manualPaymentInput?.value?.trim();
        if (!idVal) {
            manualPaymentInput?.focus();
            if (errorEl) {
                errorEl.textContent = 'Please enter your payment reference, transaction ID or Razorpay Payment ID.';
                errorEl.classList.remove('hidden');
            }
            return;
        }

        errorEl?.classList.add('hidden');
        if (idVal.startsWith('order_')) {
            startOrderPolling(idVal);
            checkOrderStatusOnce(idVal);
        } else {
            submitPaymentForm(idVal, currentOrderId || '', '');
        }
    });
};
