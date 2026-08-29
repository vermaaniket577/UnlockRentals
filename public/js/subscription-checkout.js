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
                    }, 250);
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
        const url = new URL(plansUrl, window.location.origin);
        url.searchParams.set('payment_failed', '1');
        url.searchParams.set('reason', reason || 'Payment failed. Please try again or choose another payment method.');
        window.location.href = url.toString();
    }

    /**
     * Submit the payment form with the given payment details.
     */
    function submitPaymentForm(paymentId, orderId, signature) {
        if (paymentCompleted) return;
        paymentCompleted = true;
        stopPolling();

        showLoading('Payment verified! Activating your premium plan...');
        if (progressBar) {
            progressBar.style.transition = 'width 1.5s ease';
            progressBar.style.width = '100%';
        }

        const payIdEl = document.getElementById('razorpay_payment_id');
        const orderIdEl = document.getElementById('razorpay_order_id');
        const sigEl = document.getElementById('razorpay_signature');

        if (payIdEl) payIdEl.value = paymentId;
        if (orderIdEl) orderIdEl.value = orderId || '';
        if (sigEl) sigEl.value = signature || '';

        setTimeout(() => form.submit(), 600);
    }

    /**
     * Start polling the server to check if the Razorpay order has been paid.
     */
    function startOrderPolling(orderId) {
        if (!checkOrderStatusUrl || pollingInterval) return;

        let pollCount = 0;
        const maxPolls = 120;

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

            try {
                const resp = await fetch(checkOrderStatusUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ order_id: orderId }),
                });

                if (!resp.ok) return;
                const data = await resp.json();

                if (data.status === 'paid' && data.payment_id) {
                    submitPaymentForm(data.payment_id, orderId, '');
                }
            } catch (_) {
                // Silently retry
            }
        }, 3000);
    }

    function razorpayMethodConfig(selectedMethod) {
        const all = { netbanking: true, card: true, upi: true, wallet: true };
        const map = {
            upi: { upi: true },
            phonepe: { upi: true },
            paytm: { upi: true, wallet: true },
            card: { card: true },
            netbanking: { netbanking: true },
            wallet: { wallet: true },
            qr: { upi: true },
            razorpay: all,
        };
        return map[selectedMethod] || all;
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

            // Start polling immediately
            startOrderPolling(order.order_id);

            const selectedMethod = methodInput?.value || 'razorpay';
            const logoUrl = brandLogo || (window.location.origin + '/images/logo-icon.png');

            const prefillData = {
                name: userPrefill.name || '',
                email: userPrefill.email || '',
                method: (selectedMethod !== 'razorpay') ? selectedMethod : undefined,
            };
            if (contactNumber) {
                prefillData.contact = contactNumber;
            }

            const razorpay = new Razorpay({
                key: order.key_id,
                amount: order.amount,
                currency: order.currency,
                name: 'UnlockRentals',
                description: `${planName} (${billingPeriod === 'yearly' ? 'Annual' : 'Monthly'} Membership)`,
                image: logoUrl,
                order_id: order.order_id,
                method: razorpayMethodConfig(selectedMethod),
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
                        setTimeout(() => {
                            if (!paymentCompleted) {
                                stopPolling();
                            }
                        }, 30000);
                    },
                },
            });

            razorpay.on('payment.failed', function (response) {
                isOpeningRazorpay = false;
                stopPolling();
                const error = response.error || {};
                const reason = error.description || error.reason || error.code || 'Payment was declined by your bank or payment provider.';
                redirectToPlansWithFailure(reason);
            });

            hideLoading();
            razorpay.open();
        });

        // Instant Direct Razorpay Launch: Trigger payment modal immediately upon page load
        setTimeout(() => {
            if (!hasDismissedModal && !paymentCompleted && !isOpeningRazorpay) {
                payButton?.click();
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

    // ── Manual Verification Fallback Handler ──
    const manualVerifyBtn = document.getElementById('manual-verify-btn');
    const manualPaymentInput = document.getElementById('manual_razorpay_payment_id');

    manualVerifyBtn?.addEventListener('click', () => {
        const paymentId = manualPaymentInput?.value?.trim();
        if (!paymentId) {
            manualPaymentInput?.focus();
            const errorEl = document.getElementById('manual-verify-error');
            if (errorEl) {
                errorEl.textContent = 'Please enter your Razorpay Payment ID (starts with pay_)';
                errorEl.classList.remove('hidden');
            }
            return;
        }
        if (!paymentId.startsWith('pay_')) {
            const errorEl = document.getElementById('manual-verify-error');
            if (errorEl) {
                errorEl.textContent = 'Invalid format. Payment ID should start with "pay_"';
                errorEl.classList.remove('hidden');
            }
            return;
        }

        submitPaymentForm(paymentId, '', '');
    });
};
