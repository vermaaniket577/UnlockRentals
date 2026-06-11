<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="canonical" href="{{ route('home') }}">
    <meta name="robots" content="index, follow">
    <title>UnlockRentals - Find Houses, Flats, PGs & Rental Properties in India</title>
    <meta name="description" content="Find verified houses, flats, PGs, shops, and rental properties across India. Search by location, budget, rooms, and property type on UnlockRentals.">

    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="UnlockRentals - Find Houses, Flats, PGs & Rental Properties in India">
    <meta property="og:description" content="Find verified houses, flats, PGs, shops, and rental properties across India.">
    <meta property="og:image" content="{{ asset('images/logo.png') }}">

    {{-- Twitter --}}
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="UnlockRentals - Find Houses, Flats, PGs & Rental Properties in India">
    <meta property="twitter:description" content="Find verified houses, flats, PGs, shops, and rental properties across India.">
    <meta property="twitter:image" content="{{ asset('images/logo.png') }}">

    <!-- Optimized Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&family=Outfit:wght@400;700;800&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2563EB',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                        outfit: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/unlock-rental.css') }}?v=20260611-header-fix">

    <!-- Phosphor Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.0/src/bold/style.css" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/style.css">

    <!-- PWA Configuration -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="UnlockRentals">
    <link class="pwa-apple-touch-icon" rel="apple-touch-icon" href="{{ asset('images/icons/icon-192x192.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#2563EB">

    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "WebSite",
        "name": "UnlockRentals",
        "url": "{{ route('home') }}",
        "potentialAction": {
            "@@type": "SearchAction",
            "target": "{{ route('properties.index') }}?search={search_term_string}",
            "query-input": "required name=search_term_string"
        }
    }
    </script>
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Organization",
        "name": "UnlockRentals",
        "url": "{{ route('home') }}",
        "logo": "{{ asset('images/logo.png') }}",
        "sameAs": []
    }
    </script>
    <!-- GSAP for animations -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>

    <!-- Location Data Script -->
    @include('components.location-script')

    <!-- Custom Styles for Homepage Banner Slider -->
    <style>
        .promo-slider-container {
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px 40px 10px;
        }
        
        .promo-slider {
            position: relative;
            width: 100%;
            background: #1a3fbd; /* fallback blue matching image */
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            height: 330px;
        }

        .promo-slides-wrapper {
            display: flex;
            width: 100%;
            height: 100%;
            transition: transform 0.6s cubic-bezier(0.25, 1, 0.5, 1);
        }

        .promo-slide {
            flex: 0 0 100%;
            width: 100%;
            height: 100%;
            display: flex;
            position: relative;
        }

        .promo-slide-content {
            width: 55%;
            height: 100%;
            padding: 40px 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            z-index: 5;
            color: #ffffff;
        }

        .promo-badge {
            background: #ffd814;
            color: #0f1111;
            font-size: 11px;
            font-weight: 800;
            padding: 6px 14px;
            border-radius: 100px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 16px;
            box-shadow: 0 4px 10px rgba(255, 216, 20, 0.2);
            display: inline-block;
        }

        .promo-title {
            font-family: 'Outfit', sans-serif;
            font-size: clamp(1.8rem, 3.2vw, 2.8rem);
            font-weight: 900;
            line-height: 1.15;
            margin-bottom: 12px;
            letter-spacing: -0.5px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .promo-desc {
            font-family: 'Inter', sans-serif;
            font-size: clamp(0.85rem, 1.2vw, 1.05rem);
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.6;
            margin-bottom: 24px;
            max-width: 520px;
        }

        .promo-btn {
            background: #ffd814;
            color: #0f1111;
            font-weight: 800;
            font-size: 13px;
            padding: 14px 32px;
            border-radius: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.25s ease;
            box-shadow: 0 6px 20px rgba(255, 216, 20, 0.25);
            border: none;
            cursor: pointer;
        }

        .promo-btn:hover {
            background: #ffd814;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 216, 20, 0.4);
        }

        .promo-image-side {
            width: 45%;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .promo-image-side img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Gradient mask to blend the image into the blue background */
        .promo-gradient-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right, #1a3fbd 0%, rgba(26, 63, 189, 0.9) 15%, rgba(26, 63, 189, 0.4) 50%, transparent 100%);
            z-index: 2;
        }

        /* Specific backgrounds for each slide to match their gradients */
        .slide-zero-brokerage {
            background: #1a3fbd;
        }
        .slide-zero-brokerage .promo-gradient-overlay {
            background: linear-gradient(to right, #1a3fbd 0%, rgba(26, 63, 189, 0.9) 15%, rgba(26, 63, 189, 0.4) 50%, transparent 100%);
        }

        .slide-pg-stays {
            background: #0f172a;
        }
        .slide-pg-stays .promo-gradient-overlay {
            background: linear-gradient(to right, #0f172a 0%, rgba(15, 23, 42, 0.9) 15%, rgba(15, 23, 42, 0.4) 50%, transparent 100%);
        }

        .slide-commercial {
            background: #1e1b4b;
        }
        .slide-commercial .promo-gradient-overlay {
            background: linear-gradient(to right, #1e1b4b 0%, rgba(30, 27, 75, 0.9) 15%, rgba(30, 27, 75, 0.4) 50%, transparent 100%);
        }

        /* Slider Controls */
        .promo-arrow {
            position: absolute;
            top: 0;
            bottom: 0;
            width: 48px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            transition: all 0.3s ease;
        }

        .promo-arrow:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.4);
        }

        .promo-arrow-prev {
            left: 0;
            border-top-right-radius: 6px;
            border-bottom-right-radius: 6px;
            border-left: none;
        }

        .promo-arrow-next {
            right: 0;
            border-top-left-radius: 6px;
            border-bottom-left-radius: 6px;
            border-right: none;
        }

        .promo-arrow svg {
            width: 24px;
            height: 24px;
            transition: transform 0.2s ease;
        }
        
        .promo-arrow:hover svg {
            transform: scale(1.1);
        }

        .promo-dots {
            position: absolute;
            bottom: 16px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 8px;
            z-index: 10;
        }

        .promo-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.4);
            border: none;
            cursor: pointer;
            padding: 0;
            transition: all 0.3s ease;
        }

        .promo-dot.active {
            background: #ffffff;
            transform: scale(1.2);
        }

        /* Responsive styling for promo slider */
        @media (max-width: 1024px) {
            .promo-slider-container {
                padding: 20px 24px 10px;
            }
            .promo-slide-content {
                width: 65%;
                padding: 30px 40px;
            }
            .promo-image-side {
                width: 35%;
            }
        }

        @media (max-width: 768px) {
            .promo-slider {
                height: 280px;
            }
            .promo-slide-content {
                width: 100%;
                padding: 24px 36px;
                text-align: center;
                align-items: center;
            }
            .promo-image-side {
                display: none;
            }
            .promo-arrow {
                width: 36px;
            }
        }
        
        @media (max-width: 768px) {
            .main-header {
                padding-top: 42px !important;
                padding-bottom: 12px !important;
                padding-left: 14px !important;
                padding-right: 14px !important;
                gap: 8px !important;
            }
        }
    </style>
</head>
<body>

    {{-- Premium Page Loader --}}
    @include('components.page-loader')

    <header class="main-header" style="z-index: 9999;">
        <div class="logo-wrapper">
            <a href="{{ route('home') }}" class="logo" style="display: flex !important; align-items: center !important; gap: 8px !important; flex-direction: row !important; white-space: nowrap !important;">
                <img src="{{ asset('images/logo.png') }}" alt="Unlock Rentals" class="logo-img" style="width: 28px !important; height: 28px !important; flex-shrink: 0 !important; object-fit: contain !important;" onerror="this.src='https://ui-avatars.com/api/?name=UR&background=2563EB&color=fff'">
                <span class="logo-text" style="font-size: 18px !important; white-space: nowrap !important;">Unlock<span>Rentals</span></span>
            </a>
        </div>
        <nav class="main-nav">
            <a href="{{ route('properties.index') }}" class="nav-link">
                <i class="ph ph-magnifying-glass"></i>
                Discover
            </a>
            <a href="{{ route('properties.index', ['purpose' => 'buy']) }}" class="nav-link">
                <i class="ph ph-house"></i>
                Buy
            </a>
            <a href="{{ route('properties.index', ['purpose' => 'rent']) }}" class="nav-link">
                <i class="ph ph-key"></i>
                Rent
            </a>
            <a href="{{ route('properties.index', ['type' => 'commercial']) }}" class="nav-link">
                <i class="ph ph-buildings"></i>
                Commercial
            </a>
        </nav>
        <div class="auth-nav" style="display: flex; align-items: center; gap: 10px;">
            @if (Route::has('login'))
                @auth
                    <a href="{{ route('properties.create') }}" class="btn-primary-sm btn-cta-premium" style="white-space:nowrap; padding: 0 20px; height: 44px; margin-right: 5px; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="ph-bold ph-plus-circle" style="font-size: 18px;"></i>
                        <span class="hidden md:inline">{{ $site_settings['cta_button_text'] ?? 'Post Your Property Advertise' }}</span>
                        <span class="inline md:hidden">Post Ad</span>
                    </a>
                    <div class="relative" style="position:relative; display:inline-block;">
                        <button onclick="toggleUserDropdown(event)" class="flex items-center gap-2 px-3 py-1.5 rounded-lg hover:bg-white/10 transition-all" style="background:transparent; border:none; color:#fff; cursor:pointer;" id="userDropdownBtn">
                            <span style="color:rgba(255,255,255,0.9); font-size:14px; font-weight:600;">{{ auth()->user()->name }}</span>
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="opacity:0.6;"><polyline points="6 9 12 15 18 9"></polyline></svg>
                        </button>
                        
                        <div id="userDropdown" class="hidden" style="position:absolute; top:calc(100% + 10px); right:0; width:220px; background:rgba(15,15,18,0.95); backdrop-filter:blur(20px); border:1px solid rgba(255,255,255,0.1); border-radius:12px; box-shadow:0 20px 40px rgba(0,0,0,0.4); overflow:hidden; z-index:1000;">
                            <div style="padding:15px; border-bottom:1px solid rgba(255,255,255,0.05);">
                                <p style="color:rgba(255,255,255,0.5); font-size:11px; text-transform:uppercase; letter-spacing:1px; margin-bottom:2px;">Account</p>
                                <p style="color:#fff; font-weight:600; font-size:14px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ auth()->user()->email }}</p>
                            </div>
                            <div style="padding:6px 0;">
                                <a href="javascript:void(0)" class="dropdown-item" style="display:flex; align-items:center; gap:10px; padding:10px 15px; color:rgba(255,255,255,0.7); font-size:13px; text-decoration:none; transition:all 0.2s;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                    View Profile
                                </a>
                                <a href="{{ route('dashboard') }}" class="dropdown-item" style="display:flex; align-items:center; gap:10px; padding:10px 15px; color:rgba(255,255,255,0.7); font-size:13px; text-decoration:none; transition:all 0.2s;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                                    Dashboard
                                </a>
                                <a href="{{ route('plans.index') }}" class="dropdown-item" style="display:flex; align-items:center; gap:10px; padding:10px 15px; color:rgba(255,255,255,0.7); font-size:13px; text-decoration:none; transition:all 0.2s;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
                                    View Plans
                                </a>
                            </div>
                            <div style="padding:6px 0; border-top:1px solid rgba(255,255,255,0.05);">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item" style="width:100%; display:flex; align-items:center; gap:10px; padding:10px 15px; color:#ff4d4d; font-size:13px; text-decoration:none; transition:all 0.2s; background:transparent; border:none; cursor:pointer; text-align:left;">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <style>
                        .dropdown-item:hover { background: rgba(255,255,255,0.08); color: #fff !important; }
                    </style>
                    <script>
                        function toggleUserDropdown(e) {
                            e.stopPropagation();
                            const dropdown = document.getElementById('userDropdown');
                            dropdown.classList.toggle('hidden');
                        }
                        window.onclick = function(event) {
                            if (!event.target.closest('#userDropdownBtn')) {
                                const dropdown = document.getElementById('userDropdown');
                                if (dropdown && !dropdown.classList.contains('hidden')) {
                                    dropdown.classList.add('hidden');
                                }
                            }
                        }
                    </script>
                @else
                    <a href="{{ route('login') }}" class="nav-link" style="margin-right: 15px;">
                        <i class="ph ph-user-circle"></i>
                        Log in
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('properties.create') }}" class="btn-primary-sm btn-cta-premium" style="white-space:nowrap; padding: 0 20px; height: 44px; display: inline-flex; align-items: center; gap: 6px;">
                            <i class="ph-bold ph-plus-circle" style="font-size: 18px;"></i>
                            <span class="hidden md:inline">{{ $site_settings['cta_button_text'] ?? 'Post Your Property Advertise' }}</span>
                            <span class="inline md:hidden">Post Ad</span>
                        </a>
                    @endif
                @endauth
            @endif
            <button class="hamburger" onclick="toggleMobileNav()" aria-label="Open menu">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>
        </div>
    </header>

    <!-- Mobile Navigation Drawer -->
    <div class="mobile-nav-overlay" id="mobileOverlay" onclick="toggleMobileNav()"></div>
    <nav class="mobile-nav" id="mobileNav">
        <button class="mobile-close" onclick="toggleMobileNav()" aria-label="Close menu">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
        <a href="{{ route('properties.index') }}" class="mobile-nav-link">Discover</a>
        <a href="{{ route('properties.index', ['purpose' => 'buy']) }}" class="mobile-nav-link">Buy</a>
        <a href="{{ route('properties.index', ['purpose' => 'rent']) }}" class="mobile-nav-link">Rent</a>
        <a href="{{ route('properties.index', ['type' => 'commercial']) }}" class="mobile-nav-link">Commercial</a>
        <div class="mobile-auth">
            @if (Route::has('login'))
                @auth
                    <div style="padding:10px 16px; margin-bottom:10px; border-bottom:1px solid rgba(255,255,255,0.05);">
                        <p style="color:rgba(255,255,255,0.5); font-size:11px; text-transform:uppercase; letter-spacing:1px; margin-bottom:4px;">Signed in as</p>
                        <p style="color:#fff; font-weight:600; font-size:15px;">{{ auth()->user()->name }}</p>
                    </div>
                    <a href="{{ route('properties.create') }}" class="btn-primary-sm btn-cta-premium" style="text-align:center; display:flex; justify-content:center; width:100%; border-radius: 12px; height: 50px; margin-bottom: 10px;">
                        <i class="ph-fill ph-megaphone-simple" style="font-size: 20px;"></i>
                        {{ $site_settings['cta_button_text'] ?? 'Post Your Property Advertise' }}
                    </a>
                    <a href="{{ url('/dashboard') }}" class="btn-primary-sm" style="text-align:center; width:100%;">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn-ghost-sm" style="text-align:center; display:block;">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('properties.create') }}" class="btn-primary-sm btn-cta-premium" style="text-align:center; display:flex; justify-content:center; width:100%; border-radius: 12px; height: 50px;">
                            <i class="ph-fill ph-megaphone-simple" style="font-size: 20px;"></i>
                            {{ $site_settings['cta_button_text'] ?? 'Post Your Property Advertise' }}
                        </a>
                    @endif
                @endauth
            @endif
        </div>
    </nav>

    <section class="hero-section">
        <div class="hero-bg">
            <div class="glow-orb orb-1"></div>
            <div class="glow-orb orb-2"></div>
            <img src="{{ asset('images/hero-bg.png') }}" alt="Premium Indian City Skyline Real Estate" loading="eager" fetchpriority="high">
            <div class="overlay-gradient"></div>
        </div>

        <div class="hero-container">
            <div class="hero-badge badge-animate">
                <span class="badge-dot"></span>
                Verified Premium Listings
            </div>

            <h1 class="hero-title title-animate" style="max-width: 920px; margin-left: auto; margin-right: auto;">
                Find Your <span style="background: linear-gradient(135deg, #93c5fd 0%, #2563EB 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Perfect Home</span> <br>
                Across <span style="background: linear-gradient(90deg, #dbeafe 0%, #60a5fa 40%, #2563EB 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">India's Top Cities</span>
            </h1>

            <p class="hero-subtitle subtitle-animate">
                Browse verified premium rentals, PG stays, shops & hotels. Connect directly with owners and move in seamlessly.
            </p>

            <form class="search-glass-panel panel-animate" action="{{ route('home') }}" method="GET" data-ur-loader-msg="Searching premium properties&#8230;">

                <!-- ─ Row 1: Primary Filters ─ -->
                <div class="search-filters-row">

                    <div class="filter-group">
                        <div class="filter-icon">
                            <i class="ph-bold ph-map-pin" style="font-size: 20px; color: var(--primary);"></i>
                        </div>
                        <div class="filter-input-wrap">
                            <label class="filter-label">Region / State</label>
                            <select class="filter-input" id="state-select" name="state">
                                <option value="">Select State</option>
                                <option value="AP">Andhra Pradesh</option>
                                <option value="AR">Arunachal Pradesh</option>
                                <option value="AS">Assam</option>
                                <option value="BR">Bihar</option>
                                <option value="CG">Chhattisgarh</option>
                                <option value="GA">Goa</option>
                                <option value="GJ">Gujarat</option>
                                <option value="HR">Haryana</option>
                                <option value="HP">Himachal Pradesh</option>
                                <option value="JH">Jharkhand</option>
                                <option value="KA">Karnataka</option>
                                <option value="KL">Kerala</option>
                                <option value="MP">Madhya Pradesh</option>
                                <option value="MH">Maharashtra</option>
                                <option value="MN">Manipur</option>
                                <option value="ML">Meghalaya</option>
                                <option value="MZ">Mizoram</option>
                                <option value="NL">Nagaland</option>
                                <option value="OR">Odisha</option>
                                <option value="PB">Punjab</option>
                                <option value="RJ">Rajasthan</option>
                                <option value="SK">Sikkim</option>
                                <option value="TN">Tamil Nadu</option>
                                <option value="TS">Telangana</option>
                                <option value="TR">Tripura</option>
                                <option value="UP">Uttar Pradesh</option>
                                <option value="UK">Uttarakhand</option>
                                <option value="WB">West Bengal</option>
                                <option value="DL">Delhi</option>
                            </select>
                        </div>
                        <div class="dropdown-chevron">
                            <i class="ph ph-caret-down"></i>
                        </div>
                    </div>

                    <div class="filter-group">
                        <div class="filter-icon">
                            <i class="ph-bold ph-city" style="font-size: 20px; color: var(--primary);"></i>
                        </div>
                        <div class="filter-input-wrap">
                            <label class="filter-label">City / District</label>
                            <select class="filter-input" id="city-select" name="district">
                                <option value="">Select District</option>
                            </select>
                        </div>
                        <div class="dropdown-chevron">
                            <i class="ph ph-caret-down"></i>
                        </div>
                    </div>

                    <div class="filter-group">
                        <div class="filter-icon">
                            <i class="ph-bold ph-map-trifold" style="font-size: 20px; color: var(--primary);"></i>
                        </div>
                        <div class="filter-input-wrap">
                            <label class="filter-label">Locality / Area</label>
                            <select class="filter-input" id="locality-select" name="locality">
                                <option value="">Select Locality</option>
                            </select>
                        </div>
                        <div class="dropdown-chevron">
                            <i class="ph ph-caret-down"></i>
                        </div>
                    </div>

                </div><!-- end .search-filters-row -->

                <!-- ─ Row 2: Configs & Actions ─ -->
                <div class="search-actions-row">
                    <div class="search-quick-configs">
                        
                        <div class="config-item">
                            <label>Property Type</label>
                            <select name="type">
                                <option value="all">Any Type</option>
                                <option value="house">House / Villa</option>
                                <option value="shop">Shop / Commercial</option>
                                <option value="pg-hostel">PG / Hostel</option>
                                <option value="hotel">Hotel</option>
                            </select>
                        </div>

                        <div class="config-divider"></div>

                        <div class="config-item">
                            <label>Budget</label>
                            <select name="price">
                                <option value="any">Any Price</option>
                                <option value="0-20000">Up to ₹20,000</option>
                                <option value="20000-50000">₹20K – ₹50K</option>
                                <option value="50000-plus">₹50,000+</option>
                            </select>
                        </div>
                        
                        <div class="config-divider"></div>

                        <div class="config-item">
                            <label>Layout</label>
                            <div class="pill-group">
                                <input type="hidden" name="rooms" id="rooms-input" value="{{ request('rooms', 'any') }}">
                                <button type="button" class="pill-btn {{ request('rooms', 'any') == 'any' ? 'active' : '' }}" onclick="setPill(this, 'any', 'rooms-input')">Any</button>
                                <button type="button" class="pill-btn {{ request('rooms') == '1bhk' ? 'active' : '' }}" onclick="setPill(this, '1bhk', 'rooms-input')">1 BHK</button>
                                <button type="button" class="pill-btn {{ request('rooms') == '2bhk' ? 'active' : '' }}" onclick="setPill(this, '2bhk', 'rooms-input')">2 BHK</button>
                                <button type="button" class="pill-btn {{ request('rooms') == '3bhk-plus' ? 'active' : '' }}" onclick="setPill(this, '3bhk-plus', 'rooms-input')">3+</button>
                            </div>
                        </div>

                        <div class="config-divider"></div>

                        <div class="config-item">
                            <label>Intent</label>
                            <div class="pill-group">
                                <input type="hidden" name="purpose" id="purpose-input" value="{{ request('purpose', 'rent') }}">
                                <button type="button" class="pill-btn {{ request('purpose', 'rent') == 'rent' ? 'active' : '' }}" onclick="setPill(this, 'rent', 'purpose-input')">Rent</button>
                                <button type="button" class="pill-btn {{ request('purpose') == 'buy' ? 'active' : '' }}" onclick="setPill(this, 'buy', 'purpose-input')">Buy</button>
                            </div>
                        </div>

                    </div>

                    <button type="submit" class="btn-search-premium">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        <span>Find Now</span>
                    </button>
                </div>

            </form>

            <div class="actions-wrapper actions-animate">
                <span class="action-text">Own a property?</span>
                <a href="{{ route('properties.create') }}" class="btn-outline-blue">Post Property</a>
                <a href="{{ route('properties.index') }}" class="btn-text-link">
                    <div class="play-icon">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                    </div>
                    View Showreel
                </a>
            </div>

            <div class="trust-indicators indicators-animate">
                <div class="indicator-box">
                    <div class="indicator-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path></svg>
                    </div>
                    <div class="indicator-text">
                        <span class="val">10,000+</span>
                        <span class="lbl">Luxury Properties</span>
                    </div>
                </div>
                <div class="indicator-box">
                    <div class="indicator-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    </div>
                    <div class="indicator-text">
                        <span class="val">100%</span>
                        <span class="lbl">Verified Listings</span>
                    </div>
                </div>
                <div class="indicator-box">
                    <div class="indicator-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    </div>
                    <div class="indicator-text">
                        <span class="val">24/7</span>
                        <span class="lbl">Concierge Support</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Premium Promo Slider Section -->
    <div class="promo-slider-container">
        <div class="promo-slider" id="promo-slider">
            <!-- Slides Wrapper -->
            <div class="promo-slides-wrapper" id="promo-slides-wrapper">
                
                <!-- Slide 1: Zero Brokerage -->
                <div class="promo-slide slide-zero-brokerage">
                    <div class="promo-slide-content">
                        <span class="promo-badge">SPECIAL OFFER</span>
                        <h2 class="promo-title">Zero Brokerage Home Rentals</h2>
                        <p class="promo-desc">Browse thousands of 100% verified flats and independent villas across India. Contact owners directly.</p>
                        <a href="{{ route('properties.index', ['purpose' => 'rent']) }}" class="promo-btn">SHOP NOW</a>
                    </div>
                    <div class="promo-image-side">
                        <div class="promo-gradient-overlay"></div>
                        <img src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=800&q=80" alt="Zero Brokerage Rentals">
                    </div>
                </div>

                <!-- Slide 2: Premium PG -->
                <div class="promo-slide slide-pg-stays">
                    <div class="promo-slide-content">
                        <span class="promo-badge">POPULAR CATEGORY</span>
                        <h2 class="promo-title">Premium PG & Co-Living Stays</h2>
                        <p class="promo-desc">Fully furnished, high-speed Wi-Fi, daily housekeeping, and home-style food. Perfect for students and professionals.</p>
                        <a href="{{ route('properties.index', ['type' => 'pg-hostel']) }}" class="promo-btn">EXPLORE PG</a>
                    </div>
                    <div class="promo-image-side">
                        <div class="promo-gradient-overlay"></div>
                        <img src="https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?auto=format&fit=crop&w=800&q=80" alt="Premium PG Stays">
                    </div>
                </div>

                <!-- Slide 3: Commercial Spaces -->
                <div class="promo-slide slide-commercial">
                    <div class="promo-slide-content">
                        <span class="promo-badge">BUSINESS CLASS</span>
                        <h2 class="promo-title">Modern Commercial Spaces</h2>
                        <p class="promo-desc">Find high-footfall retail shops, showrooms, and fully managed offices. Zero brokerage, maximum growth.</p>
                        <a href="{{ route('properties.index', ['type' => 'commercial']) }}" class="promo-btn">VIEW SHOPS</a>
                    </div>
                    <div class="promo-image-side">
                        <div class="promo-gradient-overlay"></div>
                        <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=800&q=80" alt="Commercial Properties">
                    </div>
                </div>

            </div>

            <!-- Navigation Controls -->
            <button class="promo-arrow promo-arrow-prev" id="promo-prev" aria-label="Previous Offer">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
            </button>
            <button class="promo-arrow promo-arrow-next" id="promo-next" aria-label="Next Offer">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </button>

            <!-- Dot Indicators -->
            <div class="promo-dots">
                <button class="promo-dot active" data-slide-index="0" aria-label="Slide 1"></button>
                <button class="promo-dot" data-slide-index="1" aria-label="Slide 2"></button>
                <button class="promo-dot" data-slide-index="2" aria-label="Slide 3"></button>
            </div>
        </div>
    </div>

    <!-- Featured Rentals -->
    <section class="premium-section featured-rentals">
        <div class="section-container">
            <h2 class="section-title">
                @if(request()->anyFilled(['state', 'district', 'locality', 'type', 'price', 'rooms', 'purpose']))
                    Search <span class="text-gradient">Results</span>
                    <a href="{{ route('home') }}" style="font-size: 14px; color: var(--primary); font-weight: 600; text-decoration: none; margin-left: 15px; border-bottom: 1px solid var(--primary);">Clear Filters</a>
                @else
                    Discover <span class="text-gradient">Premium</span> Rentals
                @endif
            </h2>
            <p class="section-subtitle">
                @if(request()->anyFilled(['state', 'district', 'locality', 'type', 'price', 'rooms', 'purpose']))
                    Showing <strong>{{ $featuredRentals->count() }}</strong> {{ Str::plural('property', $featuredRentals->count()) }} matching your criteria.
                @else
                    Handpicked luxury spaces from our top-rated customers and property owners.
                @endif
            </p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($featuredRentals as $property)
                    <x-property-card :property="$property" />
                @empty
                    <div class="col-span-full py-16 flex flex-col items-center justify-center text-center">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="1.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        <h3 class="text-xl font-bold mt-4 text-zinc-900 dark:text-white">No properties found</h3>
                        <p class="text-zinc-500 mt-2">Try adjusting your filters or <a href="{{ route('home') }}" style="color:var(--primary);font-weight:600;">clear all filters</a></p>
                    </div>
                @endforelse
            </div>
            
            <div style="text-align: center; margin-top: 50px;">
                <a href="{{ route('properties.index') }}" class="btn-explore-premium">
                    <span>Explore All Rentals</span>
                    <i class="ph ph-bold ph-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    {{-- Why Choose Us Section --}}
    @include('components.why-choose-us')

    {{-- How It Works Section --}}
    @include('components.how-it-works')

    {{-- Pricing Plans Section --}}
    @include('components.pricing-plans')

    {{-- App Download Section --}}
    @include('components.app-download')

    <!-- Testimonials / Success Stories -->
    <section class="premium-section success-stories">
        <div class="section-container">
            <h2 class="section-title">Trusted by <span class="text-gradient">Thousands</span></h2>
            <p class="section-subtitle">Real experiences from customers who found their perfect rental spaces.</p>
            
            <div class="testimonial-grid">
                <div class="testimonial-card glass-card">
                    <div class="stars">@for($i = 0; $i < ($site_settings['testimonial_1_stars'] ?? 5); $i++)★@endfor</div>
                    <p class="quote">"{{ $site_settings['testimonial_1_quote'] ?? "UnlockRentals made finding our company's new office space in Cyber City incredibly seamless. The verified listings and sleek UI saved us weeks of searching." }}"</p>
                    <div class="author">
                        <div class="author-img" style="background-image: url('{{ $site_settings['testimonial_1_image'] ?? 'https://randomuser.me/api/portraits/men/43.jpg' }}')" loading="lazy"></div>
                        <div class="author-details">
                            <h4>{{ $site_settings['testimonial_1_author'] ?? 'Rahul S.' }}</h4>
                            <span>{{ $site_settings['testimonial_1_role'] ?? 'CEO, TechFlow India' }}</span>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card glass-card">
                    <div class="stars">@for($i = 0; $i < ($site_settings['testimonial_2_stars'] ?? 5); $i++)★@endfor</div>
                    <p class="quote">"{{ $site_settings['testimonial_2_quote'] ?? "I listed my luxury villa in Assagao and within 48 hours I had a verified, high-quality tenant. The platform's concierge support is world-class." }}"</p>
                    <div class="author">
                        <div class="author-img" style="background-image: url('{{ $site_settings['testimonial_2_image'] ?? 'https://randomuser.me/api/portraits/women/68.jpg' }}')"></div>
                        <div class="author-details">
                            <h4>{{ $site_settings['testimonial_2_author'] ?? 'Priya D.' }}</h4>
                            <span>{{ $site_settings['testimonial_2_role'] ?? 'Property Owner' }}</span>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card glass-card">
                    <div class="stars">@for($i = 0; $i < ($site_settings['testimonial_3_stars'] ?? 5); $i++)★@endfor</div>
                    <p class="quote">"{{ $site_settings['testimonial_3_quote'] ?? "The filtering is incredibly smart. We found a beautiful apartment that checked off all our boxes in South Mumbai without dealing with broker spam." }}"</p>
                    <div class="author">
                        <div class="author-img" style="background-image: url('{{ $site_settings['testimonial_3_image'] ?? 'https://randomuser.me/api/portraits/men/57.jpg' }}')"></div>
                        <div class="author-details">
                            <h4>{{ $site_settings['testimonial_3_author'] ?? 'Aditya P.' }}</h4>
                            <span>{{ $site_settings['testimonial_3_role'] ?? 'Renter' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Resource Directory --}}
    @include('components.resource-directory')

    {{-- Property Links Bar --}}
    @include('components.property-links-bar')

    <div class="hidden md:block">
        <x-footer />
    </div>

    <!-- Floating WhatsApp Button -->
    <a href="https://wa.me/917974164274" target="_blank" class="whatsapp-trigger" style="position: fixed; bottom: 30px; left: 30px; width: 60px; height: 60px; background: #25D366; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 32px; box-shadow: 0 10px 30px rgba(37,211,102,0.4); z-index: 10000; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);">
        <i class="ph-fill ph-whatsapp-logo"></i>
    </a>

    @if(($site_settings['chatbot_enabled'] ?? '1') == '1')
    <!-- Chatbot Overlay -->
    <div class="chatbot-trigger" id="chatTrigger" style="overflow: hidden; padding: 0; background: none; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
        <video src="{{ asset('videos/chatbot.mp4') }}" autoplay loop muted playsinline style="width: 100%; height: 100%; object-fit: cover; pointer-events: none; border-radius: 50%;"></video>
    </div>

    <div class="chat-window" id="chatWindow">
        <div class="chat-header">
            <div class="chat-header-content">
                <div class="chat-avatar">
                    <img src="{{ asset('images/icons/chatbot.png') }}" alt="Bot" style="width: 24px; height: 24px; object-fit: contain; filter: invert(1) grayscale(1) brightness(200%); mix-blend-mode: screen;">
                </div>
                <div class="chat-header-info">
                    <h4>Unlock Support</h4>
                    <p>Always Online</p>
                </div>
            </div>
            <i class="ph ph-x chat-close" id="chatClose"></i>
        </div>
        <div class="chat-messages" id="chatMessages">
            <div class="msg bot">
                {{ $site_settings['bot_welcome_message'] ?? 'Hi there! 👋 Welcome to UnlockRentals. How can I assist you with your property search today?' }}
            </div>
        </div>
        <div class="chat-input-area">
            <input type="text" class="chat-input" id="chatInput" placeholder="Write a message...">
            <button class="chat-send-btn" id="chatSend">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: #ffffff;"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
            </button>
        </div>
    </div>
    @endif
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Robust entrance animations using .from()
            // This ensures content is visible by default (opacity 1 in CSS)
            // and only animates if GSAP loads successfully.
            gsap.from('.badge-animate', { opacity: 0, y: 30, duration: 1, delay: 0.2, ease: 'power3.out' });
            gsap.from('.title-animate', { opacity: 0, y: 40, duration: 1.2, delay: 0.4, ease: 'power3.out' });
            gsap.from('.subtitle-animate', { opacity: 0, y: 30, duration: 1, delay: 0.6, ease: 'power3.out' });
            gsap.from('.panel-animate', { opacity: 0, y: 50, scale: 0.95, duration: 1.5, delay: 0.8, ease: 'power4.out' });
            gsap.from('.actions-animate', { opacity: 0, y: 20, duration: 1, delay: 1.2, ease: 'power3.out' });
            gsap.from('.indicators-animate', { opacity: 0, y: 20, duration: 1, delay: 1.4, ease: 'power3.out' });
              
            // Hover effect on the glass panel
            const panel = document.querySelector('.search-glass-panel');
            panel.addEventListener('mousemove', (e) => {
                const rect = panel.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                panel.style.setProperty('--mouse-x', `${x}px`);
                panel.style.setProperty('--mouse-y', `${y}px`);
            });

            // Cascading Dropdowns for Home Search
            if (typeof window.initLocationCascading === 'function') {
                window.initLocationCascading({
                    stateId: 'state-select',
                    cityId: 'city-select',
                    localityId: 'locality-select',
                    selectedState: "{{ request('state') }}",
                    selectedCity: "{{ request('district') }}",
                    selectedLocality: "{{ request('locality') }}"
                });
            } else {
                console.warn('initLocationCascading not found. Cascading filters may not work.');
            }

            // Promo Slider Implementation
            const promoSliderWrapper = document.getElementById('promo-slides-wrapper');
            const promoSlides = document.querySelectorAll('.promo-slide');
            const promoDots = document.querySelectorAll('.promo-dot');
            const promoPrevBtn = document.getElementById('promo-prev');
            const promoNextBtn = document.getElementById('promo-next');
            
            let promoCurrentSlide = 0;
            const promoTotalSlides = promoSlides.length;
            let promoSlideInterval;

            function updatePromoSlider() {
                if (promoSliderWrapper) {
                    promoSliderWrapper.style.transform = `translateX(-${promoCurrentSlide * 100}%)`;
                }
                promoDots.forEach((dot, idx) => {
                    dot.classList.toggle('active', idx === promoCurrentSlide);
                });
            }

            function nextPromoSlide() {
                promoCurrentSlide = (promoCurrentSlide + 1) % promoTotalSlides;
                updatePromoSlider();
            }

            function prevPromoSlide() {
                promoCurrentSlide = (promoCurrentSlide - 1 + promoTotalSlides) % promoTotalSlides;
                updatePromoSlider();
            }

            if (promoNextBtn) {
                promoNextBtn.addEventListener('click', () => {
                    nextPromoSlide();
                    resetPromoInterval();
                });
            }

            if (promoPrevBtn) {
                promoPrevBtn.addEventListener('click', () => {
                    prevPromoSlide();
                    resetPromoInterval();
                });
            }

            promoDots.forEach(dot => {
                dot.addEventListener('click', (e) => {
                    promoCurrentSlide = parseInt(e.target.dataset.slideIndex);
                    updatePromoSlider();
                    resetPromoInterval();
                });
            });

            function startPromoInterval() {
                promoSlideInterval = setInterval(nextPromoSlide, 5000);
            }

            function resetPromoInterval() {
                clearInterval(promoSlideInterval);
                startPromoInterval();
            }

            if (promoTotalSlides > 0) {
                startPromoInterval();
            }

        });

            function setPill(btn, value, inputId) {
                const input = document.getElementById(inputId);
                input.value = value;
                btn.closest('.pill-group')
                   .querySelectorAll('.pill-btn')
                   .forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
            }



            // Mobile navigation toggle
            function toggleMobileNav() {
                const nav = document.getElementById('mobileNav');
                const overlay = document.getElementById('mobileOverlay');
                const isOpen = nav.classList.contains('active');
                if (isOpen) {
                    nav.classList.remove('active');
                    overlay.classList.remove('active');
                    document.body.style.overflow = '';
                } else {
                    nav.classList.add('active');
                    overlay.classList.add('active');
                    document.body.style.overflow = 'hidden';
                }
            }

            // Chatbot Logic
            const chatTrigger = document.getElementById('chatTrigger');
            const chatWindow = document.getElementById('chatWindow');
            const chatClose = document.getElementById('chatClose');
            const chatSend = document.getElementById('chatSend');
            const chatInput = document.getElementById('chatInput');
            const chatMessages = document.getElementById('chatMessages');
            
            // Chat session ID
            let chatSessionId = localStorage.getItem('ur_chat_session');
            if (!chatSessionId) {
                chatSessionId = 'session_' + Math.random().toString(36).substr(2, 9) + '_' + Date.now();
                localStorage.setItem('ur_chat_session', chatSessionId);
            }

            // Load Chat History
            fetch(`/chatbot/history/${chatSessionId}`)
                .then(res => res.json())
                .then(data => {
                    if (data.messages && data.messages.length > 0) {
                        chatMessages.innerHTML = ''; 
                        data.messages.forEach(msg => {
                            const m = document.createElement('div');
                            m.className = `msg ${msg.sender}`;
                            m.textContent = msg.message;
                            chatMessages.appendChild(m);
                        });
                        chatMessages.scrollTop = chatMessages.scrollHeight;
                    } else {
                        // Save the initial welcome message to the DB for history
                        const welcomeText = "{{ $site_settings['bot_welcome_message'] ?? 'Hi there! 👋 Welcome to UnlockRentals. How can I assist you with your property search today?' }}";
                        fetch("{{ route('chatbot.save') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                message: welcomeText,
                                sender: 'bot',
                                session_id: chatSessionId
                            })
                        }).catch(err => console.error('Chat save error:', err));
                    }
                })
                .catch(err => console.error('Chat history error:', err));

            chatTrigger.addEventListener('click', () => chatWindow.classList.toggle('active'));
            chatClose.addEventListener('click', (e) => {
                e.stopPropagation();
                chatWindow.classList.remove('active');
            });

            function addMessage(text, side) {
                const msg = document.createElement('div');
                msg.className = `msg ${side}`;
                msg.textContent = text;
                chatMessages.appendChild(msg);
                chatMessages.scrollTop = chatMessages.scrollHeight;

                // Save message to database
                fetch("{{ route('chatbot.save') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        message: text,
                        sender: side,
                        session_id: chatSessionId
                    })
                }).catch(err => console.error('Chat save error:', err));
            }

            let botState = 'idle';
            let leadData = { name: '', phone: '' };

            chatSend.addEventListener('click', () => {
                const text = chatInput.value.trim();
                if(!text) return;
                addMessage(text, 'user');
                chatInput.value = '';
                
                setTimeout(() => {
                    if (botState === 'collecting_name') {
                        leadData.name = text;
                        botState = 'collecting_phone';
                        addMessage("Thank you! And what's your contact number so an agent can call you?", 'bot');
                        return;
                    }

                    if (botState === 'collecting_phone') {
                        leadData.phone = text;
                        botState = 'idle';
                        
                        // Submit lead
                        fetch("{{ route('chatbot.callback') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                name: leadData.name,
                                phone: leadData.phone,
                                session_id: chatSessionId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            addMessage("Perfect! I've shared your details with our elite concierge team. Expect a call shortly from one of our agents. 📞", 'bot');
                        })
                        .catch(err => {
                            addMessage("I'm sorry, I couldn't save your details. Please try again or call us at {{ $site_settings['site_phone'] ?? '+91 7974164274' }}.", 'bot');
                        });
                        return;
                    }

                    // Check for callback keywords
                    const lowerText = text.toLowerCase();
                    if (lowerText.includes('call') || lowerText.includes('callback') || lowerText.includes('agent') || lowerText.includes('contact')) {
                        botState = 'collecting_name';
                        addMessage("I'll be happy to arrange an elite concierge callback for you. Could you please share your full name?", 'bot');
                        return;
                    }

                    const dbResponses = {!! json_encode(array_values(array_filter(array_map('trim', explode("\n", $site_settings['bot_auto_responses'] ?? "That's a great question! Let me check our premium listings for you.\nI can certainly help you with that. Would you like to see properties in a specific city?\nOne of our agents will be happy to assist you further. Shall I book a callback for you?\nUnlockRentals offers the best verified properties in India. You're in good hands!"))))) !!};
                    
                    const fallbackResponses = [
                        "That's a great question! Let me check our premium listings for you.",
                        "I can certainly help you with that. Would you like to see properties in a specific city?",
                        "One of our agents will be happy to assist you further. Shall I book a callback for you?",
                        "UnlockRentals offers the best verified properties in India. You're in good hands!"
                    ];
                    
                    const responses = dbResponses.length > 0 ? dbResponses : fallbackResponses;
                    addMessage(responses[Math.floor(Math.random() * responses.length)], 'bot');
                }, 1000);
            });

            chatInput.addEventListener('keypress', (e) => {
                if(e.key === 'Enter') chatSend.click();
            });

            // Feedback Logic
            const stars = document.querySelectorAll('.rating-star');
            let currentRating = 0;

            stars.forEach(star => {
                star.addEventListener('click', () => {
                    currentRating = star.dataset.rating;
                    stars.forEach(s => {
                        s.classList.toggle('active', s.dataset.rating <= currentRating);
                    });
                });
            });

            // Feedback Modal Logic
            function openFeedbackModal() {
                document.getElementById('feedbackModal').classList.add('active');
                document.body.style.overflow = 'hidden';
            }

            function closeFeedbackModal() {
                document.getElementById('feedbackModal').classList.remove('active');
                document.body.style.overflow = '';
            }

            const modalStars = document.querySelectorAll('.modal-star');
            let modalRating = 0;

            modalStars.forEach(star => {
                star.addEventListener('click', () => {
                    modalRating = star.dataset.rating;
                    modalStars.forEach(s => {
                        s.classList.toggle('active', s.dataset.rating <= modalRating);
                    });
                });
            });

            function submitModalFeedback(btn) {
                if(modalRating === 0) {
                    alert('Please select a star rating first!');
                    return;
                }
                
                const comment = document.getElementById('modalComment').value;
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="ph ph-circle-notch ph-spin"></i> Processing...';
                btn.disabled = true;

                fetch("{{ route('feedback.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        rating: modalRating,
                        comment: comment
                    })
                })
                .then(response => response.json())
                .then(data => {
                    btn.innerHTML = '<i class="ph ph-check-circle"></i> Sent Successfully!';
                    btn.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
                    setTimeout(() => {
                        closeFeedbackModal();
                        window.location.reload();
                    }, 2000);
                })
                .catch(error => {
                    console.error('Error:', error);
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    alert('Something went wrong. Please try again.');
                });
            }
    </script>

    @if(($site_settings['feedback_enabled'] ?? '1') == '1')
    <!-- Feedback Modal Trigger (Hidden on mobile to avoid overlapping navigation) -->
    <button class="feedback-modal-trigger hidden md:flex" onclick="openFeedbackModal()" style="position: fixed; left: 30px; bottom: 30px; width: 60px; height: 60px; background: rgba(25, 25, 30, 0.8); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1); border-radius: 50%; color: var(--primary); font-size: 24px; cursor: pointer; z-index: 9998; align-items: center; justify-content: center; box-shadow: 0 10px 30px rgba(0,0,0,0.3); transition: all 0.3s;">
        <img src="{{ asset('images/icons/feedback.png') }}" alt="Feedback" style="width: 32px; height: 32px; object-fit: contain; filter: invert(1) grayscale(1) brightness(200%); mix-blend-mode: screen;">
    </button>

    <!-- Feedback Modal Overlay -->
    <div class="feedback-overlay" id="feedbackModal">
        <div class="feedback-modal">
            <div class="modal-close" onclick="closeFeedbackModal()" style="display: flex; align-items: center; justify-content: center;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #94a3b8;"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </div>
            
            <div class="modal-header">
                <h2 class="modal-title">How are we doing?</h2>
                <p class="modal-subtitle">Your feedback helps us refine the luxury experience at UnlockRentals. Share your thoughts with us.</p>
            </div>

            <div class="modal-stars" id="modalStars">
                <i class="ph-fill ph-star modal-star" data-rating="1"></i>
                <i class="ph-fill ph-star modal-star" data-rating="2"></i>
                <i class="ph-fill ph-star modal-star" data-rating="3"></i>
                <i class="ph-fill ph-star modal-star" data-rating="4"></i>
                <i class="ph-fill ph-star modal-star" data-rating="5"></i>
            </div>

            <div class="modal-form">
                <textarea class="modal-textarea" id="modalComment" placeholder="Share your thoughts or report an issue..."></textarea>
                <div style="display: flex; gap: 12px; width: 100%;">
                    <button class="modal-cancel" onclick="closeFeedbackModal()" style="flex: 1; padding: 12px 24px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.1); background: rgba(255,255,255,0.05); color: #fff; font-weight: 600; cursor: pointer; transition: all 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='rgba(255,255,255,0.05)'">
                        Cancel
                    </button>
                    <button class="modal-submit" onclick="submitModalFeedback(this)" style="flex: 2;">
                        Send Feedback
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline-block; margin-left: 8px;"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Mobile Bottom Navigation Bar --}}
    @include('components.mobile-nav')

    {{-- PWA Install Prompt Banner --}}
    @include('components.pwa-install-prompt')

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

        // Automatically open chatbot if open-chat is passed
        window.addEventListener('load', () => {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('open-chat') === '1') {
                const chatTrigger = document.getElementById('chatTrigger');
                if (chatTrigger) {
                    setTimeout(() => {
                        chatTrigger.click();
                    }, 500);
                }
            }
        });
    </script>
</body>
</html>
