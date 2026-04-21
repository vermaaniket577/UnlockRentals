<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta name="description" content="@yield('meta_description', 'UnlockRentals - Find your perfect house or shop for rent. Browse thousands of rental properties with advanced search filters.')">
    <title>@yield('title', 'UnlockRentals - Property Rental Marketplace')</title>

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

    @php
        $faviconVersion = max(
            file_exists(public_path('favicon.ico')) ? filemtime(public_path('favicon.ico')) : 0,
            file_exists(public_path('favicon.png')) ? filemtime(public_path('favicon.png')) : 0,
        );
    @endphp
    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v={{ $faviconVersion }}">
    <link rel="icon" type="image/png" sizes="256x256" href="{{ asset('favicon.png') }}?v={{ $faviconVersion }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}?v={{ $faviconVersion }}">


    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400..800;1,400..800&display=swap" rel="stylesheet">

    {{-- Phosphor Icons --}}
    <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.0.3/src/regular/style.css">
    <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.0.3/src/fill/style.css">
    <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.0.3/src/light/style.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-white text-zinc-800 font-sans antialiased font-light selection:bg-[#2563EB]/30 selection:text-zinc-900">

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

    {{-- Footer --}}
    @include('components.footer')

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
    @stack('scripts')
</body>
</html>
