<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <script>
        (function () {
            if (localStorage.getItem('ur-theme') === 'dark') {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta name="description" content="@yield('meta_description', 'UnlockRentals - Find your perfect house or shop for rent. Browse thousands of rental properties with advanced search filters.')">
    <meta name="robots" content="@yield('robots', 'index, follow')">
    <title>@yield('title', 'UnlockRentals - Property Rental Marketplace')</title>

    {{-- Performance: DNS prefetch for external resources --}}
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="dns-prefetch" href="//cdn.jsdelivr.net">


    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'UnlockRentals - Property Rental Marketplace')">
    <meta property="og:description" content="@yield('meta_description', 'UnlockRentals - Find your perfect house or shop for rent. Browse thousands of rental properties with advanced search filters.')">
    <meta property="og:image" content="@yield('og_image', asset('images/logo.png'))">

    {{-- Twitter --}}
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('title', 'UnlockRentals - Property Rental Marketplace')">
    <meta property="twitter:description" content="@yield('meta_description', 'UnlockRentals - Find your perfect house or shop for rent. Browse thousands of rental properties with advanced search filters.')">
    <meta property="twitter:image" content="@yield('og_image', asset('images/logo.png'))">

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=1">
    <link rel="icon" type="image/png" sizes="256x256" href="{{ asset('favicon.png') }}?v=1">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}?v=1">


    {{-- Premium Fonts (Optimized) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">

    {{-- Icon Systems (SRI Protected) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.0/src/bold/style.css" integrity="sha384-nblAP2mo2pVPyMQZDw9Xy9Cwgs9lowushAYep4w5+Q9kF4Ibf3n0B/gCMVdR+Vqy" crossorigin="anonymous">
    {{-- Full Phosphor Icons (regular, fill, etc.) for mobile nav & components --}}
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/style.css">

    {{-- Tailwind CSS for local/dev reliability without requiring Vite --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#2563EB',
                        'zinc-850': '#121214',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                    }
                }
            }
        }
    </script>

    {{-- Premium UnlockRentals Styles --}}
    <link rel="stylesheet" href="{{ asset('css/unlock-rental.css') }}?v=20260611-header-fix">
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
    </style>

    <!-- PWA Configuration -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="UnlockRentals">
    <link rel="apple-touch-icon" href="{{ asset('images/icons/icon-192x192.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#2563EB">

    @stack('head')
</head>
<body class="min-h-screen bg-white text-zinc-800 font-sans antialiased font-light selection:bg-[#2563EB]/30 selection:text-zinc-900 dark:bg-slate-950 dark:text-slate-200">

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

    {{-- Footer (Hidden on mobile to save screen space) --}}
    <div class="hidden md:block">
        @include('components.footer')
    </div>

    {{-- Mobile Bottom Navigation Bar --}}
    @include('components.mobile-nav')

    {{-- PWA Install Prompt Banner --}}
    @include('components.pwa-install-prompt')

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
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('Service Worker registered successfully:', reg.scope))
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
    </script>

    @stack('scripts')
</body>
</html>
