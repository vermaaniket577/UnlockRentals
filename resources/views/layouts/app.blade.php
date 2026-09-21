<!DOCTYPE html>
<html lang="en" class="scroll-smooth" itemscope itemtype="https://schema.org/WebPage">
<head>
    <!-- Google tag (gtag.js) Non-Blocking -->
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      window.gtag = gtag;
      gtag('js', new Date());
      gtag('config', 'G-RJ2TX883V4');

      (function() {
          var gtagLoaded = false;
          function loadGtag() {
              if (gtagLoaded) return;
              gtagLoaded = true;
              var s = document.createElement('script');
              s.async = true;
              s.src = 'https://www.googletagmanager.com/gtag/js?id=G-RJ2TX883V4';
              document.head.appendChild(s);
          }
          if ('requestIdleCallback' in window) {
              requestIdleCallback(function() { setTimeout(loadGtag, 1500); });
          } else {
              setTimeout(loadGtag, 2000);
          }
          ['scroll', 'mousemove', 'touchstart', 'click', 'keydown'].forEach(function(e) {
              window.addEventListener(e, loadGtag, { once: true, passive: true });
          });
      })();
    </script>
    <script>
        (function () {
            var isApp = /UnlockRentals|wv|Version\/[0-9.]+/i.test(navigator.userAgent) || window.isNativeApp === true || new URLSearchParams(window.location.search).get('app') === '1';
            if (isApp) {
                document.documentElement.classList.add('is-mobile-app');
                document.documentElement.classList.remove('dark');
                try { localStorage.removeItem('ur-theme'); } catch(e) {}
            } else if (localStorage.getItem('ur-theme') === 'dark') {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
    <style>
        .is-mobile-app .website-only-social {
            display: none !important;
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta name="description" content="@yield('meta_description', 'Search room near my location, single rooms for rent, 1RK, 1BHK flats, PGs & houses with zero brokerage on UnlockRentals. 100% verified properties by direct owners.')">
    <meta name="keywords" content="@yield('meta_keywords', 'room near my location, room for rent near me, rooms near me, single room for rent near me, rent room near me, room near my current location, 1bhk room near me, pg near my location, pg near me, flats for rent near me, search house near me, search house near me for rent, search house near me by owner, rental properties in india, unlockrentals')">
    <meta name="author" content="UnlockRentals">
    <meta name="publisher" content="UnlockRentals">
    <meta name="robots" content="@yield('robots', 'index, follow')">
    <title>@yield('title', 'Room Near My Location | Search House & Flat For Rent Near Me - UnlockRentals')</title>

    {{-- Legacy & Universal Image Source --}}
    <link rel="image_src" href="@yield('og_image', asset('images/logo.png'))">

    {{-- Performance: DNS prefetch for external resources --}}
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="dns-prefetch" href="//cdn.jsdelivr.net">
    <link rel="dns-prefetch" href="//pagead2.googlesyndication.com">

    {{-- High-Performance Deferred Google AdSense (Zero Blocking on Initial Page Load) --}}
    <script>
        (function() {
            var adsLoaded = false;
            function loadAdSense() {
                if (adsLoaded) return;
                adsLoaded = true;
                var script = document.createElement('script');
                script.async = true;
                script.crossOrigin = 'anonymous';
                script.src = 'https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2772066538696984';
                document.head.appendChild(script);
            }
            if ('requestIdleCallback' in window) {
                requestIdleCallback(function() { setTimeout(loadAdSense, 2000); });
            } else {
                setTimeout(loadAdSense, 2500);
            }
            ['scroll', 'mousemove', 'touchstart', 'click', 'keydown'].forEach(function(evt) {
                window.addEventListener(evt, loadAdSense, { once: true, passive: true });
            });
        })();
    </script>


    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'Room Near My Location | Search House & Flat For Rent Near Me - UnlockRentals')">
    <meta property="og:description" content="@yield('meta_description', 'Search room near my location, single rooms for rent, 1RK, 1BHK flats, PGs & houses with zero brokerage on UnlockRentals.')">
    <meta property="og:image" content="@yield('og_image', asset('images/logo.png'))">

    {{-- Twitter --}}
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('title', 'Room Near My Location | Search House & Flat For Rent Near Me - UnlockRentals')">
    <meta property="twitter:description" content="@yield('meta_description', 'Search room near my location, single rooms for rent, 1RK, 1BHK flats, PGs & houses with zero brokerage on UnlockRentals.')">
    <meta property="twitter:image" content="@yield('og_image', asset('images/logo.png'))">

    {{-- Favicon & Google Search SERP Icons (Google Guidelines Compliant) --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=20260831">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=20260831">
    <link rel="icon" type="image/png" sizes="48x48" href="{{ asset('favicon-48x48.png') }}?v=20260831">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon-96x96.png') }}?v=20260831">
    <link rel="icon" type="image/png" sizes="144x144" href="{{ asset('favicon-144x144.png') }}?v=20260831">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicon-192x192.png') }}?v=20260831">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('favicon-512x512.png') }}?v=20260831">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}?v=20260831">
    <link rel="apple-touch-icon-precomposed" sizes="180x180" href="{{ asset('apple-touch-icon-precomposed.png') }}?v=20260831">

    {{-- Google Search & Organization Structured Data for Brand Logo --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Organization",
        "name": "UnlockRentals",
        "url": "{{ url('/') }}",
        "logo": "{{ asset('images/logo.png') }}",
        "image": "{{ asset('images/logo.png') }}",
        "sameAs": [
            "https://twitter.com/unlockrentals",
            "https://facebook.com/unlockrentals",
            "https://instagram.com/unlockrentals"
        ]
    }
    </script>


    {{-- Premium Fonts (Non-blocking Optimized) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&family=Playfair+Display:wght@700;900&display=swap">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">
    </noscript>

    {{-- Phosphor Icon Systems (Non-blocking Regular, Bold, Fill, Duotone) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/regular/style.css" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/bold/style.css" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/fill/style.css" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/duotone/style.css" media="print" onload="this.media='all'">
    <noscript>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/regular/style.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/bold/style.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/fill/style.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/duotone/style.css">
    </noscript>

    {{-- Precompiled High-Performance Tailwind CSS (Zero Runtime JS Overhead) --}}
    @if(file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css'])
    @else
        <link rel="stylesheet" href="{{ asset('css/tailwind-build.css') }}?v={{ file_exists(public_path('css/tailwind-build.css')) ? filemtime(public_path('css/tailwind-build.css')) : time() }}">
    @endif

    {{-- Premium UnlockRentals Styles --}}
    <link rel="stylesheet" href="{{ asset('css/unlock-rental.css') }}?v={{ file_exists(public_path('css/unlock-rental.css')) ? filemtime(public_path('css/unlock-rental.css')) : time() }}&cb=20260920-fix-inputs-v1">
    <style>
        @keyframes premiumShine {
            0% { transform: translateX(-140%); }
            55%, 100% { transform: translateX(140%); }
        }
        @keyframes successScaleIn {
            from { opacity: 0; transform: translateY(18px) scale(.94); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        @keyframes successCheck {
            from { stroke-dashoffset: 90; }
            to { stroke-dashoffset: 0; }
        }
        @keyframes checkoutFloat {
            0%, 100% { transform: translateY(0) scale(1); opacity: .25; }
            50% { transform: translateY(-34px) scale(1.35); opacity: .85; }
        }
        .premium-success-check {
            stroke-dasharray: 90;
            stroke-dashoffset: 90;
            animation: successCheck .72s .22s ease forwards;
        }

        /* ============================================================
           FORM INPUT TEXT VISIBILITY SAFEGUARD
           Guarantees input fields have dark readable text in light mode
           and clear white text in dark mode.
           ============================================================ */
        html:not(.dark) input:not([type="button"]):not([type="submit"]):not([type="reset"]):not([type="checkbox"]):not([type="radio"]),
        html:not(.dark) textarea,
        html:not(.dark) select {
            color: #0f172a !important;
            -webkit-text-fill-color: #0f172a !important;
        }

        html:not(.dark) input:not([type="button"]):not([type="submit"]):not([type="reset"])::placeholder,
        html:not(.dark) textarea::placeholder {
            color: #94a3b8 !important;
            -webkit-text-fill-color: #94a3b8 !important;
        }

        html.dark input:not([type="button"]):not([type="submit"]):not([type="reset"]):not([type="checkbox"]):not([type="radio"]),
        html.dark textarea,
        html.dark select {
            color: #f8fafc !important;
            -webkit-text-fill-color: #f8fafc !important;
        }

        html.dark input:not([type="button"]):not([type="submit"]):not([type="reset"])::placeholder,
        html.dark textarea::placeholder {
            color: #64748b !important;
            -webkit-text-fill-color: #64748b !important;
        }

        /* Universal Pure White Text & Icon Enforcement for Buttons & CTAs */
        .text-white:not(input):not(textarea):not(select),
        .\!text-white:not(input):not(textarea):not(select),
        .btn-primary,
        .btn-primary-sm,
        .btn-cta-premium,
        .btn-primary-lg,
        .btn-explore-premium,
        .promo-btn,
        #dash-add-property,
        .mobile-post-ad-btn,
        a.btn-primary,
        a.btn-primary-sm,
        a.btn-cta-premium,
        button.btn-primary,
        button.btn-primary-sm,
        button.btn-cta-premium,
        a[class*="bg-[#2874F0]"],
        button[class*="bg-[#2874F0]"],
        a[class*="bg-[#1A5FDF]"],
        button[class*="bg-[#1A5FDF]"],
        a[class*="bg-[#2563EB]"],
        button[class*="bg-[#2563EB]"],
        a[class*="from-[#2874F0]"],
        button[class*="from-[#2874F0]"],
        a[class*="to-[#1A5FDF]"],
        button[class*="to-[#1A5FDF]"],
        a[class*="bg-blue-600"],
        button[class*="bg-blue-600"],
        a[class*="bg-blue-700"],
        button[class*="bg-blue-700"],
        a[class*="from-blue-600"],
        button[class*="from-blue-600"],
        a[class*="from-indigo-600"],
        button[class*="from-indigo-600"],
        a[class*="bg-emerald-600"],
        button[class*="bg-emerald-600"],
        a.bg-blue-600,
        button.bg-blue-600,
        .bg-blue-600,
        .bg-gradient-to-r.from-blue-600,
        .bg-gradient-to-tr.from-blue-600,
        a[title="Explore"],
        a[title="Inquiry"],
        a[title="Book Visit"],
        a[title="Sign In Now"],
        a[title="Sign In to View"] {
            color: #ffffff !important;
            -webkit-text-fill-color: #ffffff !important;
        }

        .text-white > i,
        .text-white > span,
        .text-white > svg,
        .\!text-white > i,
        .\!text-white > span,
        .\!text-white > svg,
        .btn-primary *,
        .btn-primary-sm *,
        .btn-cta-premium *,
        .btn-primary-lg *,
        .btn-explore-premium *,
        .promo-btn *,
        #dash-add-property *,
        .mobile-post-ad-btn *,
        a.btn-primary *,
        a.btn-primary-sm *,
        a.btn-cta-premium *,
        button.btn-primary *,
        button.btn-primary-sm *,
        button.btn-cta-premium *,
        a[class*="bg-[#2874F0]"] *,
        button[class*="bg-[#2874F0]"] *,
        a[class*="bg-[#1A5FDF]"] *,
        button[class*="bg-[#1A5FDF]"] *,
        a[class*="bg-[#2563EB]"] *,
        button[class*="bg-[#2563EB]"] *,
        a[class*="from-[#2874F0]"] *,
        button[class*="from-[#2874F0]"] *,
        a[class*="to-[#1A5FDF]"] *,
        button[class*="to-[#1A5FDF]"] *,
        a[class*="bg-blue-600"] *,
        button[class*="bg-blue-600"] *,
        a[class*="bg-blue-700"] *,
        button[class*="bg-blue-700"] *,
        a[class*="from-blue-600"] *,
        button[class*="from-blue-600"] *,
        a[class*="from-indigo-600"] *,
        button[class*="from-indigo-600"] *,
        a[class*="bg-emerald-600"] *,
        button[class*="bg-emerald-600"] *,
        a.bg-blue-600 *,
        button.bg-blue-600 *,
        .bg-blue-600 *,
        .bg-gradient-to-r.from-blue-600 *,
        .bg-gradient-to-tr.from-blue-600 *,
        a[title="Explore"] *,
        a[title="Inquiry"] *,
        a[title="Book Visit"] *,
        a[title="Sign In Now"] *,
        a[title="Sign In to View"] * {
            color: #ffffff !important;
            -webkit-text-fill-color: #ffffff !important;
        }
    </style>

    <!-- PWA Configuration -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="UnlockRentals">
    <link rel="apple-touch-icon" href="{{ asset('images/icons/icon-192x192.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#2563EB">

    <!-- Mobile App Deep Link Metadata (Android App Links & iOS Universal Links) -->
    <meta property="al:android:url" content="unlockrentals://open?url={{ urlencode(url()->full()) }}">
    <meta property="al:android:package" content="com.unlockrentals.app">
    <meta property="al:android:app_name" content="UnlockRentals">
    <meta property="al:web:url" content="{{ url()->full() }}">

    @stack('head')
</head>
<body class="min-h-screen bg-white text-zinc-800 font-sans antialiased font-normal selection:bg-[#2563EB]/30 selection:text-zinc-900 dark:bg-slate-950 dark:text-slate-200 overflow-x-hidden w-full max-w-full">

    {{-- Smart Mobile App Auto-Handoff & Open in App Banner --}}
    @include('components.smart-app-banner')

    {{-- Premium Page Loader --}}
    @include('components.page-loader')

    {{-- Navigation --}}
    @include('components.navbar')

    {{-- Flash Messages --}}
    @if(session('success'))
    <div id="flash-success" class="fixed top-20 right-4 z-[999] max-w-md bg-white border-l-4 border-emerald-500 text-emerald-700 font-medium px-6 py-4 rounded-md shadow-xl animate-slide-in">
        <div class="flex items-center gap-3">
            <i class="ph ph-check-circle text-xl"></i>
            <span>{{ session('success') }}</span>
            <button onclick="this.closest('div').remove()" class="ml-auto text-emerald-400/60 hover:text-emerald-400"><i class="ph ph-x"></i></button>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div id="flash-error" class="fixed top-20 right-4 z-[999] max-w-md bg-white border-l-4 border-red-500 text-red-700 font-medium px-6 py-4 rounded-md shadow-xl animate-slide-in">
        <div class="flex items-center gap-3">
            <i class="ph ph-warning-circle text-xl"></i>
            <span>{{ session('error') }}</span>
            <button onclick="this.closest('div').remove()" class="ml-auto text-red-400/60 hover:text-red-400"><i class="ph ph-x"></i></button>
        </div>
    </div>
    @endif

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    <x-subscription.payment-success-modal />
    @include('components.profile-modal')
    @include('components.location-modal')

    {{-- Footer (Hidden on mobile to save screen space) --}}
    <div class="hidden md:block">
        @include('components.footer')
    </div>

    {{-- Mobile Bottom Navigation Bar --}}
    @include('components.mobile-nav')

    {{-- PWA Install Prompt Banner --}}
    @include('components.pwa-install-prompt')

    {{-- Floating AI Support Chatbot --}}
    @include('components.chatbot')

    {{-- Auto-dismiss flash messages --}}
    <script>
        setTimeout(() => {
            document.querySelectorAll('#flash-success, #flash-error').forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateX(100%)';
                setTimeout(() => el.remove(), 300);
            });
        }, 5000);
    </script>

    @include('components.location-script')

    <!-- PWA & Network Service Worker Logic -->
    <script>
        function sendSubscriptionToServer(subscription) {
            if (!subscription) return;
            const subData = JSON.parse(JSON.stringify(subscription));
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            fetch('/api/push/subscribe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || ''
                },
                body: JSON.stringify({
                    endpoint: subscription.endpoint,
                    keys: subData.keys || {},
                    device_type: /Android|iPhone|iPad/i.test(navigator.userAgent) ? 'android' : 'web'
                })
            }).catch(e => console.warn('[Push] Subscription sync failed:', e));
        }

        async function initPushSubscription(reg) {
            if (!('PushManager' in window) || !reg.pushManager) return;
            try {
                const sub = await reg.pushManager.getSubscription();
                if (sub) {
                    sendSubscriptionToServer(sub);
                } else if (Notification.permission === 'granted') {
                    // Subscribe with basic configuration
                    const newSub = await reg.pushManager.subscribe({
                        userVisibleOnly: true,
                        applicationServerKey: null
                    }).catch(() => null);
                    if (newSub) sendSubscriptionToServer(newSub);
                }
            } catch (err) {
                // Ignore silent push manager initializations
            }
        }

        window.enablePushNotifications = async function() {
            if (!('Notification' in window)) return false;
            const permission = await Notification.requestPermission();
            if (permission === 'granted' && navigator.serviceWorker && navigator.serviceWorker.ready) {
                const reg = await navigator.serviceWorker.ready;
                initPushSubscription(reg);
                return true;
            }
            return false;
        };

        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => {
                        console.log('Service Worker registered successfully:', reg.scope);
                        initPushSubscription(reg);
                    })
                    .catch(err => console.error('Service Worker registration failed:', err));
            });
        }

        // Online/Offline Detection Toast Notifier
        function showNetworkToast(isOnline) {
            const existing = document.getElementById('network-status-toast');
            if (existing) existing.remove();

            const toast = document.createElement('div');
            toast.id = 'network-status-toast';
            toast.className = `fixed top-20 left-1/2 -translate-x-1/2 z-[9999] px-6 py-3 rounded-full shadow-2xl font-bold text-xs flex items-center gap-2 transition-all duration-300 transform -translate-y-10 opacity-0`;
            
            if (isOnline) {
                toast.classList.add('bg-emerald-600', 'text-white');
                toast.innerHTML = `<i class="ph-bold ph-wifi-high text-sm"></i> Connection Restored. Back online!`;
            } else {
                toast.classList.add('bg-red-600', 'text-white');
                toast.innerHTML = `<i class="ph-bold ph-wifi-slash text-sm"></i> Connection Lost. Working offline.`;
            }

            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.classList.remove('-translate-y-10', 'opacity-0');
                toast.classList.add('translate-y-0', 'opacity-100');
            }, 50);

            setTimeout(() => {
                toast.classList.remove('translate-y-0', 'opacity-100');
                toast.classList.add('-translate-y-10', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        }

        window.addEventListener('online', () => showNetworkToast(true));
        window.addEventListener('offline', () => showNetworkToast(false));



        // Smooth form submission & double-click protection
        document.addEventListener('submit', (e) => {
            const form = e.target;
            if (form.getAttribute('data-no-smooth')) return;
            const btn = form.querySelector('button[type="submit"], input[type="submit"]');
            if (btn && !btn.classList.contains('btn-submitting')) {
                btn.classList.add('btn-submitting');
            }
        });
    </script>

    @guest
        <x-auth-modal />
    @endguest

    @include('components.cookie-consent')
    @include('components.exit-intent-modal')
    @include('components.lead-modals')

    <script src="{{ asset('js/otp-verification.js') }}?v=20260916"></script>
    <script src="{{ asset('js/visitor-tracker.js') }}?v=20260920" defer></script>
    @include('components.push-notification-deliverer')
    @stack('scripts')
</body>
</html>
