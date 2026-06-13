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
        userPrefill,
        manualPaymentLink,
    } = config;

    let pollingInterval = null;
    let paymentCompleted = false;

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
     * Works for both standard handler and polling-detected payments.
     */
    function submitPaymentForm(paymentId, orderId, signature) {
        if (paymentCompleted) return;
        paymentCompleted = true;
        stopPolling();

        showLoading('Payment detected! Activating your premium plan...');
        if (progressBar) {
            progressBar.style.transition = 'width 1.5s ease';
            progressBar.style.width = '100%';
        }

        document.getElementById('razorpay_payment_id').value = paymentId;
        document.getElementById('razorpay_order_id').value = orderId || '';
        document.getElementById('razorpay_signature').value = signature || '';

        // Small delay so user sees the "Activating" message
        setTimeout(() => form.submit(), 600);
    }

    /**
     * Start polling the server to check if the Razorpay order has been paid.
     * This catches payments made via UPI QR on phone that the modal never detects.
     */
    function startOrderPolling(orderId) {
        if (!checkOrderStatusUrl || pollingInterval) return;

        let pollCount = 0;
        const maxPolls = 120; // Poll for up to ~6 minutes (120 * 3s)

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
                    // Payment detected server-side! Auto-submit.
                    submitPaymentForm(data.payment_id, orderId, '');
                }
            } catch (_) {
                // Silently ignore poll errors — will retry next interval
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
            if (!config.razorpayKeyConfigured) {
                alert('Razorpay is selected but API credentials are not configured by admin.');
                return;
            }

            paymentCompleted = false;
            showLoading('Creating secure payment order...');

            let order;
            try {
                const orderResponse = await fetch(razorpayOrderUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ billing_period: billingPeriod }),
                });
                order = await orderResponse.json();
                if (!orderResponse.ok) {
                    throw new Error(order.message || 'Unable to create Razorpay order.');
                }
            } catch (error) {
                redirectToPlansWithFailure(error.message || 'Network issue detected. Check your connection and try again.');
                return;
            }

            // Start polling immediately — this is the key fix.
            // Even if the Razorpay modal handler fails to fire, polling will detect the payment.
            startOrderPolling(order.order_id);

            const selectedMethod = methodInput?.value || 'razorpay';
            const razorpay = new Razorpay({
                key: order.key_id,
                amount: order.amount,
                currency: order.currency,
                name: 'UnlockRentals',
                description: `${planName} ${billingPeriod} subscription`,
                order_id: order.order_id,
                method: razorpayMethodConfig(selectedMethod),
                handler: function (response) {
                    // Standard path — handler fires with signature
                    submitPaymentForm(
                        response.razorpay_payment_id,
                        response.razorpay_order_id,
                        response.razorpay_signature
                    );
                },
                prefill: userPrefill,
                theme: { color: '#2563EB' },
                modal: {
                    ondismiss: function () {
                        // Don't redirect immediately — the polling might still detect the payment
                        hideLoading();
                        // Show the manual fallback section if it exists
                        const fallback = document.getElementById('manual-verify-section');
                        if (fallback) {
                            fallback.classList.remove('hidden');
                            fallback.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                        // Keep polling for another 30 seconds after dismiss
                        setTimeout(() => {
                            if (!paymentCompleted) {
                                stopPolling();
                            }
                        }, 30000);
                    },
                },
            });

            razorpay.on('payment.failed', function (response) {
                stopPolling();
                const error = response.error || {};
                const reason = error.description || error.reason || error.code || 'Payment was declined by your bank or payment provider.';
                redirectToPlansWithFailure(reason);
            });

            hideLoading();
            razorpay.open();
        });

        // Automatically trigger the Razorpay modal on page load
        if (payButton) {
            setTimeout(() => {
                payButton.click();
            }, 300);
        }
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

        // Submit via the main form without signature (triggers FLOW B on server)
        submitPaymentForm(paymentId, '', '');
    });
};
