{{-- UnlockRentals Privacy & Cookie Consent Banner --}}
<div id="ur-cookie-banner" class="ur-cookie-banner" role="dialog" aria-label="Privacy and Cookie Notice">
    <div class="ur-cookie-inner">
        {{-- Banner Header --}}
        <div class="flex items-center justify-between gap-3 mb-2.5">
            <div class="flex items-center gap-2.5 min-w-0">
                <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 border border-blue-200 flex items-center justify-center shrink-0">
                    <i class="ph-bold ph-shield-check text-lg"></i>
                </div>
                <div class="min-w-0">
                    <h4 class="text-xs sm:text-sm font-bold text-slate-900 leading-snug truncate">Your Privacy Matters</h4>
                    <span class="text-[10px] text-emerald-600 font-semibold flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span> Verified Safe Browsing
                    </span>
                </div>
            </div>
            {{-- Quick Close (X) Button --}}
            <button type="button" onclick="window.urDismissCookieBanner()" class="w-7 h-7 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition-colors cursor-pointer shrink-0" aria-label="Dismiss cookie notice">
                <i class="ph-bold ph-x text-xs"></i>
            </button>
        </div>

        {{-- Notice Description --}}
        <p class="text-xs text-slate-600 leading-relaxed mb-3 font-normal">
            We use essential cookies to provide secure browsing and optimize your property search with zero aggressive tracking.
        </p>

        {{-- Action Buttons --}}
        <div class="flex items-center gap-2 pt-2.5 border-t border-slate-100">
            <button type="button" onclick="window.urAcceptAllCookies()" class="flex-1 py-2 px-3 bg-blue-600 hover:bg-blue-700 active:scale-[0.98] text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-blue-600/20 cursor-pointer">
                Accept All
            </button>
            <button type="button" onclick="window.urAcceptEssentialOnly()" class="py-2 px-3 bg-slate-50 hover:bg-slate-100 active:scale-[0.98] text-slate-700 hover:text-slate-900 border border-slate-200 text-xs font-semibold rounded-xl transition-all cursor-pointer">
                Essential Only
            </button>
            <a href="{{ route('privacy') }}" class="text-[11px] text-blue-600 hover:underline px-1.5 whitespace-nowrap font-medium" title="Privacy Policy">
                Policy
            </a>
        </div>
    </div>
</div>

<style>
/* Cookie Banner Standard Process & Clean Light Styling */
.ur-cookie-banner {
    display: none;
    position: fixed;
    z-index: 9997;
    background: #ffffff !important; /* Pure clean white */
    border: 1px solid #e2e8f0 !important;
    border-radius: 1.25rem;
    box-shadow: 0 16px 36px -6px rgba(15, 23, 42, 0.16), 0 0 0 1px rgba(0, 0, 0, 0.04) !important;
    padding: 1.1rem;
    opacity: 0;
    transform: translateY(24px);
    transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.3s ease;
    pointer-events: none;
    box-sizing: border-box;
}

/* Mobile: Positioned ABOVE the bottom navigation bar with safe clearance */
@media (max-width: 767px) {
    .ur-cookie-banner {
        left: 12px !important;
        right: 12px !important;
        bottom: calc(76px + env(safe-area-inset-bottom, 0px)) !important;
        max-width: none !important;
    }
}

/* Desktop / Tablet: Bottom-left floating card */
@media (min-width: 768px) {
    .ur-cookie-banner {
        left: 24px !important;
        right: auto !important;
        bottom: 24px !important;
        max-width: 380px !important;
        width: 380px !important;
    }
}

.ur-cookie-banner.ur-cookie-visible {
    display: block !important;
    opacity: 1 !important;
    transform: translateY(0) !important;
    pointer-events: auto !important;
}

/* Inside native mobile app or standalone PWA wrapper: Suppress web cookie banner completely */
.is-mobile-app .ur-cookie-banner {
    display: none !important;
}
</style>

<script>
(function() {
    const banner = document.getElementById('ur-cookie-banner');
    if (!banner) return;

    // Check if running inside mobile application / WebView / PWA
    function isMobileApp() {
        if (document.documentElement.classList.contains('is-mobile-app')) return true;
        if (window.isNativeApp === true) return true;
        if (new URLSearchParams(window.location.search).get('app') === '1') return true;
        if (window.matchMedia && window.matchMedia('(display-mode: standalone)').matches) return true;
        if (/UnlockRentals|wv|WebView|Version\/[0-9.]+/i.test(navigator.userAgent)) return true;
        return false;
    }

    if (isMobileApp()) {
        banner.remove();
        return;
    }

    const choice = localStorage.getItem('ur_cookie_consent_choice');
    if (!choice) {
        setTimeout(function() {
            if (!isMobileApp()) {
                banner.classList.add('ur-cookie-visible');
            }
        }, 1500);
    }

    window.urDismissCookieBanner = function() {
        localStorage.setItem('ur_cookie_consent_choice', 'dismissed');
        banner.classList.remove('ur-cookie-visible');
        setTimeout(function() {
            if (banner.parentNode) banner.parentNode.removeChild(banner);
        }, 350);
    };

    window.urAcceptAllCookies = function() {
        localStorage.setItem('ur_cookie_consent_choice', 'all');
        banner.classList.remove('ur-cookie-visible');
        setTimeout(function() {
            if (banner.parentNode) banner.parentNode.removeChild(banner);
        }, 350);
        sendConsentUpdate('all', true);
    };

    window.urAcceptEssentialOnly = function() {
        localStorage.setItem('ur_cookie_consent_choice', 'essential');
        banner.classList.remove('ur-cookie-visible');
        setTimeout(function() {
            if (banner.parentNode) banner.parentNode.removeChild(banner);
        }, 350);
        sendConsentUpdate('essential', false);
    };

    function sendConsentUpdate(type, isGranted) {
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        fetch('/api/consent/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token || '',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                consent_type: 'cookie_' + type,
                is_granted: isGranted,
                form_source: 'cookie_banner',
            }),
        }).catch(function() {});
    }
})();
</script>
