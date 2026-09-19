{{-- UnlockRentals Privacy & Cookie Consent Banner --}}
<div id="ur-cookie-banner" class="fixed bottom-4 left-4 right-4 sm:left-6 sm:right-auto sm:max-w-md bg-slate-950/95 backdrop-blur-lg border border-slate-800 text-white p-5 rounded-2xl shadow-2xl z-[9999] transition-all duration-300 transform translate-y-24 opacity-0 pointer-events-none" role="dialog" aria-label="Privacy and Cookie Notice">
    <div class="flex items-start gap-3.5 mb-3">
        <div class="w-10 h-10 rounded-xl bg-blue-600/20 text-blue-400 border border-blue-500/30 flex items-center justify-center shrink-0">
            <i class="ph-bold ph-shield-check text-xl"></i>
        </div>
        <div>
            <h4 class="text-sm font-bold text-white leading-snug">Your Privacy Matters</h4>
            <p class="text-xs text-slate-300 mt-1 leading-relaxed">
                We use first-party cookies to improve your property search experience and measure website performance. No aggressive tracking or third-party fingerprinting.
            </p>
        </div>
    </div>

    <div class="flex items-center gap-2 pt-2 border-t border-slate-800/80">
        <button type="button" onclick="window.urAcceptAllCookies()" class="flex-1 py-2 px-3 bg-blue-600 hover:bg-blue-500 active:scale-[0.98] text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-blue-600/20 cursor-pointer">
            Accept All
        </button>
        <button type="button" onclick="window.urAcceptEssentialOnly()" class="py-2 px-3 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white text-xs font-semibold rounded-xl transition-all cursor-pointer">
            Essential Only
        </button>
        <a href="{{ route('privacy') }}" class="text-[11px] text-blue-400 hover:underline px-1 whitespace-nowrap" title="Privacy Policy">
            Details
        </a>
    </div>
</div>

<script>
(function() {
    const banner = document.getElementById('ur-cookie-banner');
    if (!banner) return;

    const choice = localStorage.getItem('ur_cookie_consent_choice');
    if (!choice) {
        setTimeout(() => {
            banner.classList.remove('translate-y-24', 'opacity-0', 'pointer-events-none');
        }, 1500);
    }

    window.urAcceptAllCookies = function() {
        localStorage.setItem('ur_cookie_consent_choice', 'all');
        banner.classList.add('translate-y-24', 'opacity-0', 'pointer-events-none');
        sendConsentUpdate('all', true);
    };

    window.urAcceptEssentialOnly = function() {
        localStorage.setItem('ur_cookie_consent_choice', 'essential');
        banner.classList.add('translate-y-24', 'opacity-0', 'pointer-events-none');
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
        }).catch(() => {});
    }
})();
</script>
