{{-- Auth Exit-Intent Modal: Captures Abandoning Sign-In & Register Visitors --}}
@php
    $sitePhone = \App\Models\Setting::get('whatsapp_number', \App\Models\Setting::get('site_phone', '919425455499'));
    $cleanPhone = preg_replace('/[^0-9]/', '', $sitePhone);
    if (!str_starts_with($cleanPhone, '91') && strlen($cleanPhone) === 10) {
        $cleanPhone = '91' . $cleanPhone;
    }
    $waText = urlencode("Hi UnlockRentals! I was on your sign-in page and need quick help accessing rental property listings.");
    $waUrl = "https://wa.me/{$cleanPhone}?text={$waText}";
@endphp

<div id="auth-exit-modal" class="fixed inset-0 z-[99999] hidden items-center justify-center bg-slate-950/75 backdrop-blur-md p-4 transition-all duration-300" role="dialog" aria-modal="true" aria-labelledby="auth-exit-title">
    <div class="relative w-full max-w-md bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200/80 dark:border-slate-800 overflow-hidden transform transition-all duration-300 scale-95 opacity-0" id="auth-exit-card">
        
        {{-- Close button --}}
        <button type="button" onclick="window.closeAuthExitModal()" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white flex items-center justify-center transition-all z-10 cursor-pointer" aria-label="Close modal">
            <i class="ph-bold ph-x text-base"></i>
        </button>

        {{-- Top Header Section --}}
        <div class="p-6 sm:p-7 pb-4 text-center">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-amber-400 to-orange-400 text-amber-950 flex items-center justify-center mx-auto mb-3.5 shadow-lg shadow-amber-400/25 ring-4 ring-amber-50 dark:ring-amber-950/40">
                <i class="ph-fill ph-house-line text-2xl"></i>
            </div>
            
            <div class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-400 border border-amber-200/80 dark:border-amber-800/80 text-[11px] font-extrabold uppercase tracking-wide mb-2">
                <i class="ph-bold ph-lightning"></i> Skip the form
            </div>
            
            <h3 id="auth-exit-title" class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight leading-snug">
                Having Trouble Signing In?
            </h3>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1.5 leading-relaxed">
                Skip passwords & forms! Connect directly with our rental advisor on WhatsApp or get handpicked 0-brokerage rentals sent to your phone.
            </p>
        </div>

        {{-- Action Body --}}
        <div class="px-6 sm:px-7 pb-7 pt-1 space-y-4">
            
            {{-- Option 1: 1-Click WhatsApp Assistance (Instant Conversion) --}}
            <div>
                <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" onclick="window.trackAuthWhatsAppExit()"
                   class="w-full py-3.5 px-5 rounded-2xl bg-[#25D366] hover:bg-[#20ba59] active:scale-[0.99] text-white font-extrabold text-sm flex items-center justify-center gap-2.5 shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/35 transition-all group cursor-pointer">
                    <i class="ph-bold ph-whatsapp-logo text-xl group-hover:scale-110 transition-transform"></i>
                    <span>Connect on WhatsApp (Instant Help)</span>
                </a>
            </div>

            {{-- Divider --}}
            <div class="relative flex items-center justify-center">
                <div class="border-t border-slate-200/80 dark:border-slate-800 w-full"></div>
                <span class="bg-white dark:bg-slate-900 px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider absolute">or quick send to phone</span>
            </div>

            {{-- Option 2: Quick Mobile Lead Capture Form --}}
            <div id="auth-exit-form-wrap">
                <form id="auth-exit-quick-form" onsubmit="window.submitAuthExitLead(event)" class="space-y-2.5">
                    <input type="text" name="website_hp" style="display:none !important;" tabindex="-1" autocomplete="off">
                    <input type="hidden" name="lead_source" value="auth_exit_intent">

                    <div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="ph-bold ph-phone text-base"></i>
                            </div>
                            <input type="tel" name="mobile" id="auth-exit-mobile" required
                                   pattern="[0-9]{10}" maxlength="10"
                                   placeholder="Enter 10-digit mobile number"
                                   class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 dark:text-white placeholder:text-slate-400 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                        </div>
                    </div>

                    <div id="auth-exit-error" class="hidden p-2.5 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 text-[11px] font-bold text-rose-600 rounded-xl"></div>

                    <button type="submit" id="auth-exit-submit-btn"
                            class="w-full py-2.5 px-4 bg-slate-900 hover:bg-slate-800 active:scale-[0.99] text-white text-xs font-bold rounded-xl transition-all shadow-xs flex items-center justify-center gap-2 cursor-pointer">
                        <i class="ph-bold ph-paper-plane-tilt"></i>
                        <span>Send Matching Rentals to My WhatsApp</span>
                    </button>
                </form>
            </div>

            {{-- Success State --}}
            <div id="auth-exit-success-wrap" class="hidden text-center py-3 space-y-2">
                <div class="w-12 h-12 rounded-full bg-emerald-100 dark:bg-emerald-950 text-emerald-600 flex items-center justify-center mx-auto text-2xl">
                    <i class="ph-bold ph-check-circle"></i>
                </div>
                <h4 class="text-sm font-extrabold text-slate-900 dark:text-white">We've Received Your Request!</h4>
                <p class="text-xs text-slate-500 dark:text-slate-400">A rental advisor will WhatsApp you top matching verified properties in Gurugram shortly.</p>
            </div>

            {{-- Dismiss / Continue Sign In --}}
            <div class="text-center pt-1">
                <button type="button" onclick="window.closeAuthExitModal()" class="text-[11px] font-semibold text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                    No thanks, continue to sign in
                </button>
            </div>

        </div>
    </div>
</div>

<script>
(function() {
    'use strict';

    const MODAL = document.getElementById('auth-exit-modal');
    const CARD = document.getElementById('auth-exit-card');
    const STORAGE_KEY = 'ur_auth_exit_modal_shown';
    const startTime = Date.now();
    let authCompletedOrSubmitting = false;
    let modalAlreadyShown = false;

    // Track when user starts submitting login / register so we don't trigger abandonment
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            authCompletedOrSubmitting = true;
        });
    });

    // 1. Initial Page View Tracking for Auth
    if (window.URTracker && typeof window.URTracker.track === 'function') {
        window.URTracker.track('auth_page_viewed', null, {
            auth_type: window.location.pathname.includes('register') ? 'register' : 'login',
            referrer: document.referrer || 'direct'
        });
    }

    // 2. Open Modal Handler
    window.openAuthExitModal = function() {
        if (!MODAL || modalAlreadyShown || sessionStorage.getItem(STORAGE_KEY) === '1' || authCompletedOrSubmitting) {
            return;
        }

        modalAlreadyShown = true;
        sessionStorage.setItem(STORAGE_KEY, '1');

        MODAL.classList.remove('hidden');
        MODAL.classList.add('flex');
        setTimeout(() => {
            CARD.classList.remove('scale-95', 'opacity-0');
            CARD.classList.add('scale-100', 'opacity-100');
        }, 10);

        // Record event
        if (window.URTracker && typeof window.URTracker.track === 'function') {
            window.URTracker.track('auth_exit_intent_shown', null, {
                time_on_page: Math.round((Date.now() - startTime) / 1000),
                referrer: document.referrer || 'direct'
            });
        }
    };

    // 3. Close Modal Handler
    window.closeAuthExitModal = function() {
        if (!MODAL) return;
        CARD.classList.remove('scale-100', 'opacity-100');
        CARD.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            MODAL.classList.remove('flex');
            MODAL.classList.add('hidden');
        }, 200);
    };

    // 4. WhatsApp Click Tracker
    window.trackAuthWhatsAppExit = function() {
        if (window.URTracker && typeof window.URTracker.track === 'function') {
            window.URTracker.track('auth_exit_whatsapp_clicked', null, {
                channel: 'whatsapp_concierge',
                referrer: document.referrer || 'direct'
            });
        }
    };

    // 5. Quick Lead Submission via AJAX
    window.submitAuthExitLead = function(e) {
        e.preventDefault();
        const phoneInput = document.getElementById('auth-exit-mobile');
        const phone = phoneInput ? phoneInput.value.replace(/[^0-9]/g, '') : '';
        const errorBox = document.getElementById('auth-exit-error');
        const submitBtn = document.getElementById('auth-exit-submit-btn');

        if (phone.length < 10) {
            errorBox.textContent = 'Please enter a valid 10-digit mobile number.';
            errorBox.classList.remove('hidden');
            return;
        }

        errorBox.classList.add('hidden');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="ph-bold ph-spinner animate-spin"></i> Submitting...';

        const payload = {
            name: 'Sign-In Visitor',
            mobile: phone,
            lead_source: 'auth_exit_intent',
            message: 'User was on sign-in page and requested WhatsApp matching assistance.',
            whatsapp_opt_in: true,
            consent: true,
            website_hp: ''
        };

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        fetch('/api/leads', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById('auth-exit-form-wrap').classList.add('hidden');
            document.getElementById('auth-exit-success-wrap').classList.remove('hidden');

            if (window.URTracker && typeof window.URTracker.track === 'function') {
                window.URTracker.track('auth_exit_lead_submitted', null, {
                    phone: phone.slice(-4), // masked for privacy
                    lead_source: 'auth_exit_intent'
                });
            }

            setTimeout(() => {
                window.closeAuthExitModal();
            }, 3000);
        })
        .catch(err => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="ph-bold ph-paper-plane-tilt"></i> Send Matching Rentals to My WhatsApp';
            errorBox.textContent = 'Could not submit. Please try connecting via WhatsApp directly.';
            errorBox.classList.remove('hidden');
        });
    };

    // 6. Desktop Exit-Intent Trigger: mouse leaving through top
    document.addEventListener('mouseleave', function(e) {
        if (e.clientY <= 15) {
            window.openAuthExitModal();
        }
    });

    // 7. Inactivity Trigger: 16 seconds on page without interaction
    let inactivityTimer = setTimeout(() => {
        window.openAuthExitModal();
    }, 16000);

    // Cancel timer if user is actively filling out the login form
    document.querySelectorAll('input').forEach(input => {
        input.addEventListener('focus', () => clearTimeout(inactivityTimer));
        input.addEventListener('input', () => clearTimeout(inactivityTimer));
    });

    // 8. Sign-In Abandonment Event Tracking on Exit (Beacon)
    window.addEventListener('beforeunload', function() {
        if (!authCompletedOrSubmitting && (Date.now() - startTime) >= 4000) {
            const timeSpent = Math.round((Date.now() - startTime) / 1000);
            if (navigator.sendBeacon) {
                const payload = JSON.stringify({
                    event_name: 'auth_abandoned',
                    property_id: null,
                    page_url: window.location.href,
                    metadata: {
                        auth_type: window.location.pathname.includes('register') ? 'register' : 'login',
                        time_spent_seconds: timeSpent,
                        referrer: document.referrer || 'direct'
                    }
                });
                const blob = new Blob([payload], { type: 'application/json' });
                navigator.sendBeacon('/api/visitor/event', blob);
            }
        }
    });

})();
</script>
