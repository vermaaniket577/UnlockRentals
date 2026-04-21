<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Unlock Rental | Premium Real Estate</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Outfit:wght@400;500;700;800&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/unlock-rental.css') }}">
    
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <!-- GSAP for animations -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>

    <!-- Location Data Script -->
    @include('components.location-script')
</head>
<body>

    {{-- Premium Page Loader --}}
    @include('components.page-loader')

    <header class="main-header" style="z-index: 9999;">
        <div class="logo-wrapper">
            <a href="{{ route('home') }}" class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="Unlock Rental" class="logo-img" onerror="this.src='https://ui-avatars.com/api/?name=UR&background=2563EB&color=fff'">
                <span class="logo-text">Unlock<span>Rental</span></span>
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
        <div class="auth-nav">
            @if (Route::has('login'))
                @auth
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
                        <a href="{{ route('properties.create') }}" class="btn-primary-sm btn-cta-premium" style="white-space:nowrap; padding: 0 28px; height: 44px;">
                            <i class="ph-fill ph-megaphone-simple" style="font-size: 18px;"></i>
                            {{ $site_settings['cta_button_text'] ?? 'Post Free Advertisement to Rent or Sell your Property' }}
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
                    <a href="{{ url('/dashboard') }}" class="btn-primary-sm" style="text-align:center;">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn-ghost-sm" style="text-align:center; display:block;">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('properties.create') }}" class="btn-primary-sm btn-cta-premium" style="text-align:center; display:flex; justify-content:center; width:100%; border-radius: 12px; height: 50px;">
                            <i class="ph-fill ph-megaphone-simple" style="font-size: 20px;"></i>
                            {{ $site_settings['cta_button_text'] ?? 'Post Free Advertisement to Rent or Sell your Property' }}
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
            <img src="{{ asset('images/hero-bg.png') }}" alt="Premium Indian City Skyline Real Estate">
            <div class="overlay-gradient"></div>
        </div>

        <div class="hero-container">
            <div class="hero-badge badge-animate">
                <span class="badge-dot"></span>
                Verified Premium Listings
            </div>

            <h1 class="hero-title title-animate" style="max-width: 920px; margin-left: auto; margin-right: auto;">
                {!! $site_settings['hero_title_1'] ?? 'Find Your <span style="background: linear-gradient(135deg, #93c5fd 0%, #2563EB 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Perfect Home</span>' !!} <br>
                {!! $site_settings['hero_title_2'] ?? 'Across <span style="background: linear-gradient(90deg, #dbeafe 0%, #60a5fa 40%, #2563EB 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">India\'s Top Cities</span>' !!}
            </h1>

            <p class="hero-subtitle subtitle-animate">
                {{ $site_settings['hero_description'] ?? 'Browse verified premium rentals, PG stays, shops & hotels. Connect directly with owners and move in seamlessly.' }}
            </p>

            <form class="search-glass-panel panel-animate" action="{{ route('home') }}" method="GET" style="max-width: 980px; margin: 0 auto; position: relative; z-index: 20;" data-ur-loader-msg="Searching premium properties&#8230;">

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
                <div class="search-actions-row" style="padding: 12px 18px;">
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
            
            <div class="scroll-wrapper">
                <button class="scroll-arrow scroll-left" onclick="scrollCards(-1)" aria-label="Scroll left">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"></polyline></svg>
                </button>

                <div class="property-scroll" id="propertyScroll">
                    @forelse($featuredRentals as $property)
                    <div class="scroll-item">
                        <x-property-card :property="$property" />
                    </div>
                    @empty
                    <div class="no-results-card">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="1.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        <h3>No properties found</h3>
                        <p>Try adjusting your filters or <a href="{{ route('home') }}" style="color:var(--primary);font-weight:600;">clear all filters</a></p>
                    </div>
                    @endforelse
                </div>

                <button class="scroll-arrow scroll-right" onclick="scrollCards(1)" aria-label="Scroll right">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </button>
            </div>
            
            <div style="text-align: center; margin-top: 40px;">
                <a href="{{ route('properties.index') }}" class="btn-ghost" style="padding: 12px 32px; display: inline-block; text-decoration: none;">Explore All Rentals</a>
            </div>
        </div>
    </section>

    <!-- Testimonials / Success Stories -->
    <section class="premium-section success-stories">
        <div class="section-container">
            <h2 class="section-title">Trusted by <span class="text-gradient">Thousands</span></h2>
            <p class="section-subtitle">Real experiences from customers who found their perfect rental spaces.</p>
            
            <div class="testimonial-grid">
                <div class="testimonial-card glass-card">
                    <div class="stars">★★★★★</div>
                    <p class="quote">"Unlock Rental made finding our company's new office space in Cyber City incredibly seamless. The verified listings and sleek UI saved us weeks of searching."</p>
                    <div class="author">
                        <div class="author-img" style="background-image: url('https://randomuser.me/api/portraits/men/43.jpg')"></div>
                        <div class="author-details">
                            <h4>Rahul S.</h4>
                            <span>CEO, TechFlow India</span>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card glass-card">
                    <div class="stars">★★★★★</div>
                    <p class="quote">"I listed my luxury villa in Assagao and within 48 hours I had a verified, high-quality tenant. The platform's concierge support is world-class."</p>
                    <div class="author">
                        <div class="author-img" style="background-image: url('https://randomuser.me/api/portraits/women/68.jpg')"></div>
                        <div class="author-details">
                            <h4>Priya D.</h4>
                            <span>Property Owner</span>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card glass-card">
                    <div class="stars">★★★★★</div>
                    <p class="quote">"The filtering is incredibly smart. We found a beautiful apartment that checked off all our boxes in South Mumbai without dealing with broker spam."</p>
                    <div class="author">
                        <div class="author-img" style="background-image: url('https://randomuser.me/api/portraits/men/57.jpg')"></div>
                        <div class="author-details">
                            <h4>Aditya P.</h4>
                            <span>Renter</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <footer class="main-footer">
        <div class="footer-glow"></div>
        <div class="section-container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <x-brand-logo
                        href="{{ route('home') }}"
                        class="logo"
                        style="margin-bottom: 20px;"
                        imageClass="logo-asset"
                        textClass="logo-text"
                        accentClass=""
                    />
                    <p class="brand-desc">Elevating the standard of modern living and premium commercial spaces worldwide. Experience luxury redefined.</p>
                </div>
                <div class="footer-links">
                    <h4>Explore</h4>
                    <a href="{{ route('properties.index', ['type' => 'house']) }}">Luxury Homes</a>
                    <a href="{{ route('properties.index', ['type' => 'commercial']) }}">Commercial Spaces</a>
                    <a href="{{ route('properties.index', ['type' => 'villa']) }}">Private Villas</a>
                    <a href="{{ route('properties.index', ['type' => 'apartment']) }}">Apartments</a>
                </div>
                <div class="footer-links">
                    <h4>Company</h4>
                    <a href="javascript:void(0)">About Us</a>
                    <a href="javascript:void(0)">Concierge Services</a>
                    <a href="javascript:void(0)">Trust & Safety</a>
                    <a href="javascript:void(0)">Careers</a>
                </div>
                <div class="footer-contact">
                    <h4>Contact Support</h4>
                    <p><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg> {{ $site_settings['site_phone'] ?? '+91 7974164274' }}</p>
                    <p><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg> {{ $site_settings['site_email'] ?? 'vip@unlockrental.com' }}</p>
                    <div class="social-icons">
                        <a href="{{ $site_settings['social_twitter'] ?? 'javascript:void(0)' }}" target="_blank"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.84 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg></a>
                        <a href="{{ $site_settings['social_facebook'] ?? 'javascript:void(0)' }}" target="_blank"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a>
                        <a href="{{ $site_settings['social_instagram'] ?? 'javascript:void(0)' }}" target="_blank"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.203 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg></a>
                        <a href="{{ $site_settings['social_linkedin'] ?? 'javascript:void(0)' }}" target="_blank"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} Unlock Rental. All rights reserved.</p>
                <div class="legal-links">
                    <a href="javascript:void(0)">Privacy Policy</a>
                    <a href="javascript:void(0)">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    @if(($site_settings['chatbot_enabled'] ?? '1') == '1')
    <!-- Chatbot Overlay -->
    <div class="chatbot-trigger" id="chatTrigger">
        <i class="ph-fill ph-chat-centered-text"></i>
    </div>

    <div class="chat-window" id="chatWindow">
        <div class="chat-header">
            <div class="chat-header-content">
                <div class="chat-avatar">
                    <i class="ph-fill ph-robot"></i>
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
                {{ $site_settings['bot_welcome_message'] ?? 'Hi there! 👋 Welcome to Unlock Rental. How can I assist you with your property search today?' }}
            </div>
        </div>
        <div class="chat-input-area">
            <input type="text" class="chat-input" id="chatInput" placeholder="Write a message...">
            <button class="chat-send-btn" id="chatSend">
                <i class="ph-fill ph-paper-plane-right"></i>
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

        });

            function setPill(btn, value, inputId) {
                const input = document.getElementById(inputId);
                input.value = value;
                btn.closest('.pill-group')
                   .querySelectorAll('.pill-btn')
                   .forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
            }

            // Scroll property cards horizontally
            function scrollCards(direction) {
                const container = document.getElementById('propertyScroll');
                if (!container) return;
                const scrollAmount = container.clientWidth * 0.8; 
                container.scrollBy({ left: direction * scrollAmount, behavior: 'smooth' });
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
            const chatSessionId = 'session_' + Math.random().toString(36).substr(2, 9) + '_' + Date.now();

            chatTrigger.addEventListener('click', () => chatWindow.classList.add('active'));
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

                    const responses = [
                        "That's a great question! Let me check our premium listings for you.",
                        "I can certainly help you with that. Would you like to see properties in a specific city?",
                        "One of our agents will be happy to assist you further. Shall I book a callback for you?",
                        "Unlock Rental offers the best verified properties in India. You're in good hands!"
                    ];
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
    <!-- Feedback Modal Trigger -->
    <button class="feedback-modal-trigger" onclick="openFeedbackModal()" style="position: fixed; left: 30px; bottom: 30px; width: 60px; height: 60px; background: rgba(25, 25, 30, 0.8); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1); border-radius: 50%; color: var(--primary); font-size: 24px; cursor: pointer; z-index: 9998; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 30px rgba(0,0,0,0.3); transition: all 0.3s;">
        <i class="ph-fill ph-chat-teardrop-dots"></i>
    </button>

    <!-- Feedback Modal Overlay -->
    <div class="feedback-overlay" id="feedbackModal">
        <div class="feedback-modal">
            <div class="modal-close" onclick="closeFeedbackModal()">
                <i class="ph ph-x"></i>
            </div>
            
            <div class="modal-header">
                <h2 class="modal-title">How are we doing?</h2>
                <p class="modal-subtitle">Your feedback helps us refine the luxury experience at Unlock Rental. Share your thoughts with us.</p>
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
                <button class="modal-submit" onclick="submitModalFeedback(this)">
                    Send Feedback
                    <i class="ph ph-paper-plane-tilt"></i>
                </button>
            </div>
        </div>
    </div>
    @endif
</body>
</html>
