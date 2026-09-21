{{-- ============================================================
     UNLOCK RENTALS — SMART MOBILE APP AUTO-LAUNCH & HANDOFF BANNER
     Automatically opens the mobile app if installed when browsing on mobile,
     with a 1-tap "Open in App" banner for guaranteed user handoff.
     ============================================================ --}}

<div id="ur-smart-app-banner" class="hidden fixed top-0 left-0 right-0 z-[9999] bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-b border-slate-200/80 dark:border-slate-800 shadow-md transform transition-all duration-300">
    <div class="max-w-7xl mx-auto px-3 py-2 sm:px-4 sm:py-2.5 flex items-center justify-between gap-2.5">
        
        {{-- Close / Dismiss Button --}}
        <button type="button" id="ur-dismiss-app-banner" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1 rounded-full transition-colors flex-shrink-0" aria-label="Dismiss app banner">
            <i class="ph-bold ph-x text-sm"></i>
        </button>

        {{-- App Icon & Info --}}
        <div class="flex items-center gap-2.5 min-w-0 flex-1 cursor-pointer" onclick="window.__openInMobileApp && window.__openInMobileApp(event)">
            <div class="w-9 h-9 rounded-xl bg-blue-600 p-1 flex-shrink-0 shadow-sm flex items-center justify-center overflow-hidden">
                <img src="{{ asset('images/logo-icon.png') }}" 
                     alt="UnlockRentals App Icon" 
                     class="w-full h-full object-contain"
                     onerror="this.src='{{ asset('images/icons/icon-192x192.png') }}'">
            </div>
            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-1.5">
                    <span class="text-xs font-black text-slate-900 dark:text-white truncate font-display">UnlockRentals App</span>
                    <span class="inline-flex items-center px-1.5 py-0.2 text-[9px] font-extrabold uppercase rounded bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300">Free</span>
                </div>
                <p class="text-[11px] text-slate-500 dark:text-slate-400 truncate font-medium">
                    Zero Brokerage • Fast Owner WhatsApp Chat
                </p>
            </div>
        </div>

        {{-- Primary "Open in App" Button --}}
        <button type="button" 
                id="ur-open-in-app-btn" 
                class="inline-flex items-center justify-center gap-1 px-3.5 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 active:scale-95 text-white text-xs font-bold shadow-md shadow-blue-500/20 transition-all flex-shrink-0"
                onclick="window.__openInMobileApp && window.__openInMobileApp(event)">
            <span>Open App</span>
            <i class="ph-bold ph-arrow-up-right text-xs"></i>
        </button>

    </div>
</div>

<script>
(function() {
    // 1. Guard check: Never run if already inside the native mobile app
    var ua = navigator.userAgent || navigator.vendor || window.opera || '';
    var isInsideNativeApp = /UnlockRentals|wv|Version\/[0-9.]+/i.test(ua) 
        || window.isNativeApp === true 
        || document.documentElement.classList.contains('is-mobile-app')
        || new URLSearchParams(window.location.search).get('app') === '1';

    if (isInsideNativeApp) {
        return;
    }

    // 2. Device detection: only activate on mobile phones
    var isAndroid = /Android/i.test(ua);
    var isIOS = /iPhone|iPad|iPod/i.test(ua) && !window.MSStream;

    if (!isAndroid && !isIOS) {
        return; // Desktop users continue standard web view
    }

    var banner = document.getElementById('ur-smart-app-banner');
    var dismissBtn = document.getElementById('ur-dismiss-app-banner');
    var isDismissed = sessionStorage.getItem('ur_smart_banner_dismissed') === '1';

    // 3. Build Universal Deep Links
    var currentUrl = window.location.href;
    var host = window.location.host;
    var pathWithQuery = window.location.pathname + window.location.search + window.location.hash;

    // Android Intent format:
    // If com.unlockrentals.app is installed, Android OS immediately routes to it.
    // If not, it falls back to the web URL cleanly.
    var androidIntentUrl = 'intent://' + host + pathWithQuery 
        + '#Intent;' 
        + 'scheme=https;' 
        + 'package=com.unlockrentals.app;' 
        + 'S.browser_fallback_url=' + encodeURIComponent(currentUrl) + ';' 
        + 'end';

    // Universal custom scheme format
    var customSchemeUrl = 'unlockrentals://open?url=' + encodeURIComponent(currentUrl);

    // Global handoff function for 1-tap clicks
    window.__openInMobileApp = function(e) {
        if (e && e.preventDefault) e.preventDefault();
        
        if (isAndroid) {
            // Navigate to Android intent URL
            window.location.href = androidIntentUrl;
        } else if (isIOS) {
            // Try iOS custom scheme with store fallback
            var start = Date.now();
            window.location.href = customSchemeUrl;
            setTimeout(function() {
                // If user is still in browser after 1.5 seconds, app isn't installed
                if (Date.now() - start < 2000 && !document.hidden) {
                    var appStoreUrl = '{{ (!empty($site_settings["app_store_url"]) && $site_settings["app_store_url"] !== "#") ? $site_settings["app_store_url"] : route("app.download") }}';
                    window.location.href = appStoreUrl;
                }
            }, 1200);
        }
        return false;
    };

    // 4. Auto-launch Attempt: When user searches or enters any URL in mobile browser
    // Run once per browsing session so we don't trap users if they choose to remain in browser
    var hasAutoAttempted = sessionStorage.getItem('ur_auto_app_handoff_attempted') === '1';
    
    // Don't auto-redirect on auth callbacks or explicit download page
    var isExcludedPath = window.location.pathname.startsWith('/auth') 
        || window.location.pathname === '/app' 
        || window.location.pathname.startsWith('/download');

    if (!hasAutoAttempted && !isExcludedPath) {
        sessionStorage.setItem('ur_auto_app_handoff_attempted', '1');

        if (isAndroid) {
            // Trigger intent launch via invisible iframe or delayed navigation
            // This lets Android intercept the URL if the app is installed
            try {
                var iframe = document.createElement('iframe');
                iframe.style.cssText = 'display:none;width:0;height:0;border:0;';
                iframe.src = androidIntentUrl;
                document.documentElement.appendChild(iframe);
                setTimeout(function() {
                    try { iframe.parentNode && iframe.parentNode.removeChild(iframe); } catch(err) {}
                }, 1000);
            } catch(e) {}
        }
    }

    // 5. Display Smart Floating App Header Bar
    if (banner && !isDismissed && !isExcludedPath) {
        banner.classList.remove('hidden');
        document.body.classList.add('has-smart-app-banner');
        
        // Add subtle top padding so sticky navbar doesn't get covered
        var bannerHeight = banner.offsetHeight || 52;
        var existingNav = document.querySelector('nav');
        if (existingNav) {
            existingNav.style.marginTop = bannerHeight + 'px';
        }

        if (dismissBtn) {
            dismissBtn.addEventListener('click', function(ev) {
                ev.stopPropagation();
                banner.classList.add('hidden');
                document.body.classList.remove('has-smart-app-banner');
                if (existingNav) {
                    existingNav.style.marginTop = '0px';
                }
                sessionStorage.setItem('ur_smart_banner_dismissed', '1');
            });
        }
    }
})();
</script>
