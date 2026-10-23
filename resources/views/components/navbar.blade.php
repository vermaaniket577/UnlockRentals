{{-- Navigation Bar Component --}}
<nav id="main-nav" class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-xl border-b border-stone-200/50 transition-all duration-300 dark:bg-slate-950/85 dark:border-slate-800/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-12 lg:h-13 gap-2.5 xl:gap-4">

            {{-- Left Side: Brand Logo --}}
            <div class="flex-shrink-0 pr-4 xl:pr-6">
                <x-brand-logo
                    href="{{ url('/') }}"
                    id="nav-logo"
                    class="group flex-shrink-0"
                    imageClass="h-7 w-auto transition-transform duration-300 group-hover:scale-[1.02]"
                    textClass="text-base xl:text-lg font-bold tracking-tight text-zinc-900 dark:text-white"
                    accentClass="text-[#2563EB]"
                />
            </div>

            {{-- Right: Desktop Navigation Links (Matching Reference Screenshot) --}}
            <nav class="main-nav hidden lg:flex items-center gap-1 xl:gap-2 flex-shrink ml-auto">
                <a href="{{ url('/') }}" class="ur-nav-link px-2.5 py-1 text-[13.5px] font-semibold transition-all whitespace-nowrap {{ request()->is('/') ? 'text-blue-700 font-bold border-b-2 border-blue-700' : 'text-slate-700 hover:text-blue-700' }}" id="nav-home" title="Home">
                    <span>Home</span>
                </a>
                <a href="{{ url('/properties') }}" class="ur-nav-link px-2.5 py-1 text-[13.5px] font-semibold transition-all whitespace-nowrap {{ request()->is('properties') && !request('purpose') && !request('type') ? 'text-blue-700 font-bold border-b-2 border-blue-700' : 'text-slate-700 hover:text-blue-700' }}" id="nav-properties" title="Properties">
                    <span>Properties</span>
                </a>

                {{-- Rent Dropdown --}}
                <div class="relative group">
                    <button type="button" class="ur-nav-link px-2.5 py-1 text-[13.5px] font-semibold transition-all whitespace-nowrap flex items-center gap-1 {{ request('purpose') == 'rent' ? 'text-blue-700 font-bold border-b-2 border-blue-700' : 'text-slate-700 hover:text-blue-700' }}" id="nav-rent" title="Rent">
                        <span>Rent</span>
                        <i class="ph ph-caret-down text-[11px] text-slate-500 transition-transform group-hover:rotate-180"></i>
                    </button>
                    <div class="absolute top-full left-0 mt-1 hidden group-hover:block bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-xl shadow-xl py-2 min-w-[200px] z-50">
                        <a href="{{ url('/properties?purpose=rent&type=apartment') }}" class="block px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-blue-50 dark:hover:bg-slate-800 hover:text-blue-600">Flats &amp; Apartments</a>
                        <a href="{{ url('/properties?purpose=rent&type=house') }}" class="block px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-blue-50 dark:hover:bg-slate-800 hover:text-blue-600">Independent Houses</a>
                        <a href="{{ url('/properties?purpose=rent&type=pg-hostel') }}" class="block px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-blue-50 dark:hover:bg-slate-800 hover:text-blue-600">PG &amp; Hostels</a>
                        <a href="{{ url('/properties?purpose=rent&type=commercial') }}" class="block px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-blue-50 dark:hover:bg-slate-800 hover:text-blue-600">Commercial Spaces</a>
                    </div>
                </div>

                {{-- Buy Dropdown --}}
                <div class="relative group">
                    <button type="button" class="ur-nav-link px-2.5 py-1 text-[13.5px] font-semibold transition-all whitespace-nowrap flex items-center gap-1 {{ request('purpose') == 'buy' ? 'text-blue-700 font-bold border-b-2 border-blue-700' : 'text-slate-700 hover:text-blue-700' }}" id="nav-buy" title="Buy">
                        <span>Buy</span>
                        <i class="ph ph-caret-down text-[11px] text-slate-500 transition-transform group-hover:rotate-180"></i>
                    </button>
                    <div class="absolute top-full left-0 mt-1 hidden group-hover:block bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-xl shadow-xl py-2 min-w-[200px] z-50">
                        <a href="{{ url('/properties?purpose=buy&type=apartment') }}" class="block px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-blue-50 dark:hover:bg-slate-800 hover:text-blue-600">Ready to Move Flats</a>
                        <a href="{{ url('/properties?purpose=buy&type=house') }}" class="block px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-blue-50 dark:hover:bg-slate-800 hover:text-blue-600">Independent Villas</a>
                        <a href="{{ url('/properties?purpose=buy&type=plot') }}" class="block px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-blue-50 dark:hover:bg-slate-800 hover:text-blue-600">Plots &amp; Land</a>
                        <a href="{{ url('/properties?purpose=buy&type=commercial') }}" class="block px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-blue-50 dark:hover:bg-slate-800 hover:text-blue-600">Commercial Shops</a>
                    </div>
                </div>

                {{-- Sell Dropdown --}}
                <div class="relative group">
                    <button type="button" class="ur-nav-link px-2.5 py-1 text-[13.5px] font-semibold transition-all whitespace-nowrap flex items-center gap-1 text-slate-700 hover:text-blue-700" id="nav-sell" title="Sell">
                        <span>Sell</span>
                        <i class="ph ph-caret-down text-[11px] text-slate-500 transition-transform group-hover:rotate-180"></i>
                    </button>
                    <div class="absolute top-full left-0 mt-1 hidden group-hover:block bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-xl shadow-xl py-2 min-w-[200px] z-50">
                        <a href="{{ route('properties.create') }}" class="block px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-blue-50 dark:hover:bg-slate-800 hover:text-blue-600">Post Property Free</a>
                        <a href="{{ url('/how-it-works') }}" class="block px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-blue-50 dark:hover:bg-slate-800 hover:text-blue-600">Seller Guide &amp; Process</a>
                        <a href="{{ url('/properties?purpose=buy') }}" class="block px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-blue-50 dark:hover:bg-slate-800 hover:text-blue-600">View Resale Listings</a>
                    </div>
                </div>

                <a href="{{ route('properties.create') }}" class="ur-nav-link px-2.5 py-1 text-[13.5px] font-semibold transition-all whitespace-nowrap text-slate-700 hover:text-blue-700" id="nav-list-property" title="List Your Property">
                    <span>List Your Property</span>
                </a>
            </nav>

            {{-- Right Side: Auth Actions & Post Property Button --}}
            <div class="flex items-center gap-2 xl:gap-3 flex-shrink-0">
                @guest
                    {{-- Mobile Top Login Button --}}
                    <a href="{{ route('login') }}" onclick="event.preventDefault(); window.openAuthModal('login');" class="lg:hidden inline-flex items-center justify-center gap-1.5 px-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-sm shadow-blue-500/20 active:scale-95 transition-all whitespace-nowrap cursor-pointer" style="height: 32px; text-decoration: none; color: #ffffff !important;" id="nav-mobile-top-login" title="Login">
                        <i class="ph-bold ph-sign-in text-sm" style="color: #ffffff !important;"></i>
                        <span style="color: #ffffff !important;">Login</span>
                    </a>

                    {{-- Desktop Login / Sign Up --}}
                    <a href="{{ route('login') }}" onclick="event.preventDefault(); window.openAuthModal('login');" class="hidden lg:inline-flex items-center gap-1.5 px-3 py-1.5 text-[13.5px] font-semibold text-slate-700 hover:text-blue-700 dark:text-slate-300 dark:hover:text-white transition-colors whitespace-nowrap cursor-pointer" id="nav-login" title="Login / Sign Up">
                        <i class="ph ph-user text-base text-slate-600 dark:text-slate-400"></i>
                        <span>Login / Sign Up</span>
                    </a>
                    
                    {{-- Desktop Post Property Free Navy Button --}}
                    <a href="{{ route('properties.create') }}" onclick="event.preventDefault(); window.openAuthModal('login');" class="hidden lg:inline-flex items-center gap-1.5 px-4 py-2 bg-[#0c365a] hover:bg-[#082640] text-white text-xs xl:text-[13px] font-bold rounded-full shadow-sm shadow-[#0c365a]/25 transition-all whitespace-nowrap cursor-pointer active:scale-95" id="nav-post-free" title="Post Property Free">
                        <i class="ph-bold ph-plus text-xs text-white"></i>
                        <span class="text-white">Post Property Free</span>
                    </a>
                @else
                    @php
                        $navActivePlan = auth()->user()->activePlan();
                        $navPlanName = strtolower($navActivePlan?->plan?->name ?? '');
                        $navBadgeClass = str_contains($navPlanName, 'enterprise') ? 'from-slate-900 to-teal-500' : (str_contains($navPlanName, 'platinum') ? 'from-blue-600 to-violet-600' : (str_contains($navPlanName, 'gold') ? 'from-amber-500 to-yellow-300' : 'from-slate-400 to-sky-300'));
                    @endphp
                    <a href="{{ route('properties.create') }}" class="hidden lg:inline-flex items-center gap-1.5 px-4 py-2 bg-[#0c365a] hover:bg-[#082640] text-white text-xs xl:text-[13px] font-bold rounded-full shadow-sm shadow-[#0c365a]/25 transition-all whitespace-nowrap cursor-pointer active:scale-95" id="nav-add-property" title="Post Property Free">
                        <i class="ph-bold ph-plus text-xs text-white"></i>
                        <span class="text-white">Post Property Free</span>
                    </a>

                    {{-- User Menu --}}
                    <div class="relative">
                        <script>
                            window.toggleUserDropdown = function(e) {
                                if (e) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                                if (window.innerWidth < 768) {
                                    window.openUserAccountModal();
                                    return;
                                }
                                var dd = document.getElementById('nav-user-dropdown');
                                var bd = document.getElementById('nav-user-backdrop');
                                if (!dd) return;
                                if (dd.classList.contains('hidden')) {
                                    dd.classList.remove('hidden');
                                    if (bd) bd.classList.remove('hidden');
                                } else {
                                    dd.classList.add('hidden');
                                    if (bd) bd.classList.add('hidden');
                                }
                            };
                            window.closeUserDropdown = function() {
                                var dd = document.getElementById('nav-user-dropdown');
                                var bd = document.getElementById('nav-user-backdrop');
                                if (dd) dd.classList.add('hidden');
                                if (bd) bd.classList.add('hidden');
                            };
                            window.openUserAccountModal = function() {
                                var modal = document.getElementById('mobile-account-modal');
                                if (modal) {
                                    modal.classList.remove('hidden');
                                    document.body.style.overflow = 'hidden';
                                }
                            };
                            window.closeUserAccountModal = function() {
                                var modal = document.getElementById('mobile-account-modal');
                                if (modal) {
                                    modal.classList.add('hidden');
                                    document.body.style.overflow = '';
                                }
                            };
                            window.performUniversalLogout = function(e) {
                                if (e) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                                var btn = e ? (e.currentTarget || e.target) : null;
                                if (btn) {
                                    btn.disabled = true;
                                    btn.style.opacity = '0.5';
                                }
                                var tokenMeta = document.querySelector('meta[name="csrf-token"]');
                                var token = tokenMeta ? tokenMeta.getAttribute('content') : '{{ csrf_token() }}';

                                fetch('/logout', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': token,
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({})
                                })
                                .then(function(res) {
                                    return res.json().catch(function() { return { redirect: '/' }; });
                                })
                                .then(function(data) {
                                    var target = (data && data.redirect) ? data.redirect : '/';
                                    if (window.location.pathname.indexOf('dashboard') !== -1 || window.location.pathname.indexOf('admin') !== -1) {
                                        window.location.replace('/');
                                    } else {
                                        window.location.reload();
                                    }
                                })
                                .catch(function() {
                                    if (window.location.pathname.indexOf('dashboard') !== -1 || window.location.pathname.indexOf('admin') !== -1) {
                                        window.location.replace('/');
                                    } else {
                                        window.location.reload();
                                    }
                                });
                            };
                        </script>

                        {{-- Dropdown Backdrop for Desktop --}}
                        <div id="nav-user-backdrop" class="fixed inset-0 z-[99999] hidden bg-black/20 dark:bg-black/60" onclick="window.closeUserDropdown()"></div>

                        <button type="button" onclick="window.toggleUserDropdown(event)" class="group flex items-center gap-2 pl-1.5 pr-2.5 sm:pr-3 py-1 rounded-full bg-slate-50/90 hover:bg-slate-100/90 dark:bg-slate-800/60 dark:hover:bg-slate-800 border border-slate-200/90 hover:border-slate-300 dark:border-slate-700/80 dark:hover:border-slate-600 shadow-2xs hover:shadow-xs transition-all duration-200 relative cursor-pointer z-[100000] focus:outline-none focus:ring-2 focus:ring-blue-500/25 active:scale-[0.98]" id="nav-user-menu" aria-label="User Account">
                            <div class="relative pointer-events-none flex-shrink-0">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center text-white relative overflow-hidden {{ $navActivePlan ? 'bg-gradient-to-tr from-blue-600 via-indigo-600 to-amber-500 ring-2 ring-amber-400/95 shadow-sm shadow-amber-500/25' : 'bg-gradient-to-br from-blue-600 to-indigo-600 ring-1.5 ring-blue-500/20 shadow-xs' }}">
                                    <span class="text-xs sm:text-[13px] font-black tracking-tight text-white select-none drop-shadow-[0_1px_1.5px_rgba(0,0,0,0.35)]">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </span>
                                    @if($navActivePlan)
                                        <span class="absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-transparent -translate-x-full animate-[premiumShine_2.6s_ease-in-out_infinite]"></span>
                                    @endif
                                </div>
                                @if($navActivePlan)
                                    <span class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full bg-gradient-to-tr from-amber-500 via-amber-400 to-yellow-300 flex items-center justify-center text-white ring-2 ring-white dark:ring-slate-900 shadow-[0_2px_6px_rgba(245,158,11,0.45)]" title="{{ $navActivePlan->plan->name ?? 'Paid Member' }}">
                                        <i class="ph-fill ph-crown text-[8.5px] drop-shadow-[0_1px_1px_rgba(0,0,0,0.4)]"></i>
                                    </span>
                                @endif
                            </div>
                            <span class="hidden md:inline-block text-xs sm:text-[13px] font-bold text-slate-800 dark:text-slate-100 max-w-[110px] lg:max-w-[140px] truncate tracking-tight pointer-events-none">{{ auth()->user()->name }}</span>
                            @if($navActivePlan)
                                <x-premium-badge :plan="$navActivePlan" size="xs" :showPlan="false" class="hidden sm:inline-flex pointer-events-none" />
                            @endif
                            <i class="ph-bold ph-caret-down text-[10px] text-slate-400 dark:text-slate-500 transition-transform duration-200 group-hover:translate-y-0.5 pointer-events-none"></i>
                            @if(isset($adminNotifications) && $adminNotifications['total_unread'] > 0)
                                <span class="absolute top-0.5 right-1.5 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white dark:border-slate-900 pointer-events-none"></span>
                            @endif
                        </button>

                        {{-- Dropdown Menu --}}
                        <div id="nav-user-dropdown" class="hidden absolute right-0 top-full mt-2 w-80 max-w-[calc(100vw-1.5rem)] bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 rounded-2xl shadow-[0_20px_50px_rgba(15,23,42,0.18)] dark:shadow-[0_25px_60px_rgba(0,0,0,0.6)] overflow-hidden z-[100000]">
                            <div class="p-4 border-b border-slate-100 dark:border-slate-800/80 bg-slate-50/70 dark:bg-slate-800/40">
                                <div class="flex items-center gap-3">
                                    <div class="relative flex-shrink-0">
                                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white text-base font-black shadow-sm {{ $navActivePlan ? 'bg-gradient-to-br from-blue-600 via-indigo-600 to-amber-500 ring-2 ring-amber-400/90 shadow-amber-500/20' : 'bg-gradient-to-br from-blue-600 to-indigo-600 ring-1 ring-blue-500/20' }}">
                                            <span class="drop-shadow-[0_1px_1.5px_rgba(0,0,0,0.35)]">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                                        </div>
                                        @if($navActivePlan)
                                            <span class="absolute -bottom-1 -right-1 w-4.5 h-4.5 rounded-full bg-gradient-to-tr from-amber-500 via-amber-400 to-yellow-300 flex items-center justify-center text-white text-[9.5px] ring-2 ring-white dark:ring-slate-900 shadow-sm" title="{{ $navActivePlan->plan->name ?? 'Paid Member' }}">
                                                <i class="ph-fill ph-crown drop-shadow-[0_1px_1px_rgba(0,0,0,0.35)]"></i>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center justify-between gap-1.5">
                                            <p class="text-sm font-bold text-slate-900 dark:text-white truncate">
                                                {{ auth()->user()->name }}
                                            </p>
                                            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 border border-blue-200/60 dark:border-blue-800/60 flex-shrink-0">
                                                {{ ucfirst(auth()->user()->role) }}
                                            </span>
                                        </div>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 truncate mt-0.5">
                                            {{ auth()->user()->email }}
                                        </p>
                                    </div>
                                </div>
                                @if($navActivePlan)
                                    <div class="mt-3">
                                        <x-premium-badge :plan="$navActivePlan" type="card" theme="light" />
                                    </div>
                                @endif
                            </div>

                            <div class="p-2 space-y-0.5 max-h-[calc(100vh-280px)] overflow-y-auto">
                                <a href="{{ route('dashboard') }}" onclick="window.closeUserDropdown()" class="flex items-center gap-3 px-3 py-2 rounded-xl text-slate-700 dark:text-slate-200 hover:text-blue-600 dark:hover:text-white hover:bg-slate-100/80 dark:hover:bg-slate-800/70 transition-all group" id="nav-dashboard" title="Dashboard">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center text-base group-hover:scale-105 transition-transform flex-shrink-0">
                                        <i class="ph-bold ph-squares-four"></i>
                                    </div>
                                    <span class="text-sm font-semibold">Dashboard</span>
                                </a>

                                <a href="#" onclick="event.preventDefault(); window.closeUserDropdown(); window.openProfileModal();" class="flex items-center gap-3 px-3 py-2 rounded-xl text-slate-700 dark:text-slate-200 hover:text-blue-600 dark:hover:text-white hover:bg-slate-100/80 dark:hover:bg-slate-800/70 transition-all group" id="nav-profile-settings" title="Profile Settings">
                                    <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-base group-hover:scale-105 transition-transform flex-shrink-0">
                                        <i class="ph-bold ph-user-gear"></i>
                                    </div>
                                    <span class="text-sm font-semibold">Profile Settings</span>
                                </a>

                                @if(auth()->user()->isProfessional())
                                <a href="{{ route('professional.dashboard') }}" onclick="window.closeUserDropdown()" class="flex items-center gap-3 px-3 py-2 rounded-xl text-slate-700 dark:text-slate-200 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-slate-100/80 dark:hover:bg-slate-800/70 transition-all group" id="nav-prof-portal" title="Professional Dashboard">
                                    <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center text-base group-hover:scale-105 transition-transform flex-shrink-0">
                                        <i class="ph-bold ph-toolbox"></i>
                                    </div>
                                    <span class="text-sm font-semibold">Professional Portal</span>
                                </a>
                                @else
                                <a href="{{ route('services.register') }}" onclick="window.closeUserDropdown()" class="flex items-center justify-between px-3 py-2 rounded-xl text-slate-700 dark:text-slate-200 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-slate-100/80 dark:hover:bg-slate-800/70 transition-all group" id="nav-prof-reg" title="List Your Service FREE">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-base group-hover:scale-105 transition-transform flex-shrink-0">
                                            <i class="ph-bold ph-identification-card"></i>
                                        </div>
                                        <span class="text-sm font-semibold">List Your Service</span>
                                    </div>
                                    <span class="text-[10px] font-extrabold uppercase px-1.5 py-0.5 rounded bg-emerald-500/15 text-emerald-600 dark:text-emerald-400">FREE</span>
                                </a>
                                @endif

                                <a href="{{ route('services.my-requests') }}" onclick="window.closeUserDropdown()" class="flex items-center gap-3 px-3 py-2 rounded-xl text-slate-700 dark:text-slate-200 hover:text-sky-600 dark:hover:text-sky-400 hover:bg-slate-100/80 dark:hover:bg-slate-800/70 transition-all group" id="nav-cust-requests" title="My Service Requests">
                                    <div class="w-8 h-8 rounded-lg bg-sky-50 dark:bg-sky-950/60 text-sky-600 dark:text-sky-400 flex items-center justify-center text-base group-hover:scale-105 transition-transform flex-shrink-0">
                                        <i class="ph-bold ph-clipboard-text"></i>
                                    </div>
                                    <span class="text-sm font-semibold">My Service Requests</span>
                                </a>

                                @if(auth()->user()->isOwner())
                                <a href="{{ route('inquiries.index') }}" onclick="window.closeUserDropdown()" class="flex items-center gap-3 px-3 py-2 rounded-xl text-slate-700 dark:text-slate-200 hover:text-violet-600 dark:hover:text-violet-400 hover:bg-slate-100/80 dark:hover:bg-slate-800/70 transition-all group" id="nav-inquiries" title="Inquiries">
                                    <div class="w-8 h-8 rounded-lg bg-violet-50 dark:bg-violet-950/60 text-violet-600 dark:text-violet-400 flex items-center justify-center text-base group-hover:scale-105 transition-transform flex-shrink-0">
                                        <i class="ph-bold ph-chat-dots"></i>
                                    </div>
                                    <span class="text-sm font-semibold">Inquiries</span>
                                </a>
                                @endif

                                @if(auth()->user()->isAdmin())
                                <div class="pt-2 mt-2 border-t border-slate-100 dark:border-slate-800/80">
                                    <div class="px-3 pb-1 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                        Administration
                                    </div>
                                    <a href="{{ route('admin.dashboard') }}" onclick="window.closeUserDropdown()" class="flex items-center gap-3 px-3 py-2 rounded-xl text-slate-700 dark:text-slate-200 hover:text-blue-600 dark:hover:text-white hover:bg-slate-100/80 dark:hover:bg-slate-800/70 transition-all group" id="nav-admin" title="Admin Panel">
                                        <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center text-base group-hover:scale-105 transition-transform flex-shrink-0">
                                            <i class="ph-bold ph-shield-check"></i>
                                        </div>
                                        <span class="text-sm font-semibold">Admin Panel</span>
                                    </a>
                                    <a href="{{ route('admin.settings') }}" onclick="window.closeUserDropdown()" class="flex items-center gap-3 px-3 py-2 rounded-xl text-slate-700 dark:text-slate-200 hover:text-blue-600 dark:hover:text-white hover:bg-slate-100/80 dark:hover:bg-slate-800/70 transition-all group" id="nav-admin-settings" title="Content &amp; Settings">
                                        <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 flex items-center justify-center text-base group-hover:scale-105 transition-transform flex-shrink-0">
                                            <i class="ph-bold ph-gear"></i>
                                        </div>
                                        <span class="text-sm font-semibold">Content &amp; Settings</span>
                                    </a>
                                    <a href="{{ route('admin.feedback') }}" onclick="window.closeUserDropdown()" class="flex items-center justify-between px-3 py-2 rounded-xl text-slate-700 dark:text-slate-200 hover:text-blue-600 dark:hover:text-white hover:bg-slate-100/80 dark:hover:bg-slate-800/70 transition-all group" id="nav-admin-feedback" title="Customer Feedback">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 flex items-center justify-center text-base group-hover:scale-105 transition-transform flex-shrink-0">
                                                <i class="ph-bold ph-chat-centered-text"></i>
                                            </div>
                                            <span class="text-sm font-semibold">Customer Feedback</span>
                                        </div>
                                        @if(isset($adminNotifications) && $adminNotifications['new_feedbacks'] > 0)
                                            <span class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $adminNotifications['new_feedbacks'] }}</span>
                                        @endif
                                    </a>
                                    <a href="{{ route('admin.chats') }}" onclick="window.closeUserDropdown()" class="flex items-center justify-between px-3 py-2 rounded-xl text-slate-700 dark:text-slate-200 hover:text-blue-600 dark:hover:text-white hover:bg-slate-100/80 dark:hover:bg-slate-800/70 transition-all group" id="nav-admin-chats" title="Chat History">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-cyan-50 dark:bg-cyan-950/60 text-cyan-600 dark:text-cyan-400 flex items-center justify-center text-base group-hover:scale-105 transition-transform flex-shrink-0">
                                                <i class="ph-bold ph-chat-circle-dots"></i>
                                            </div>
                                            <span class="text-sm font-semibold">Chat History</span>
                                        </div>
                                        @if(isset($adminNotifications) && $adminNotifications['unread_chats'] > 0)
                                            <span class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $adminNotifications['unread_chats'] }}</span>
                                        @endif
                                    </a>
                                    <a href="{{ route('admin.callbacks') }}" onclick="window.closeUserDropdown()" class="flex items-center justify-between px-3 py-2 rounded-xl text-slate-700 dark:text-slate-200 hover:text-blue-600 dark:hover:text-white hover:bg-slate-100/80 dark:hover:bg-slate-800/70 transition-all group" id="nav-admin-callbacks" title="Callback Leads">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-base group-hover:scale-105 transition-transform flex-shrink-0">
                                                <i class="ph-bold ph-phone-call"></i>
                                            </div>
                                            <span class="text-sm font-semibold">Callback Leads</span>
                                        </div>
                                        @if(isset($adminNotifications) && $adminNotifications['new_callbacks'] > 0)
                                            <span class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $adminNotifications['new_callbacks'] }}</span>
                                        @endif
                                    </a>
                                    <a href="{{ route('admin.plans') }}" onclick="window.closeUserDropdown()" class="flex items-center gap-3 px-3 py-2 rounded-xl text-slate-700 dark:text-slate-200 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-slate-100/80 dark:hover:bg-slate-800/70 transition-all group" id="nav-admin-plans" title="Manage Plans">
                                        <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center text-base group-hover:scale-105 transition-transform flex-shrink-0">
                                            <i class="ph-bold ph-crown"></i>
                                        </div>
                                        <span class="text-sm font-semibold">Manage Plans</span>
                                    </a>
                                    <a href="{{ route('admin.subscriptions') }}" onclick="window.closeUserDropdown()" class="flex items-center gap-3 px-3 py-2 rounded-xl text-slate-700 dark:text-slate-200 hover:text-blue-600 dark:hover:text-white hover:bg-slate-100/80 dark:hover:bg-slate-800/70 transition-all group" id="nav-admin-subscriptions" title="User Subscriptions">
                                        <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center text-base group-hover:scale-105 transition-transform flex-shrink-0">
                                            <i class="ph-bold ph-receipt"></i>
                                        </div>
                                        <span class="text-sm font-semibold">User Subscriptions</span>
                                    </a>
                                </div>
                                @endif
                            </div>

                            <div class="border-t border-slate-100 dark:border-slate-800/80 p-2 bg-slate-50/60 dark:bg-slate-800/30">
                                <form method="POST" action="{{ route('logout') }}" onsubmit="window.performUniversalLogout(event)">
                                    @csrf
                                    <button type="button" onclick="window.performUniversalLogout(event)" class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 text-xs font-bold transition-all cursor-pointer group" id="nav-logout">
                                        <div class="w-7 h-7 rounded-lg bg-rose-100/70 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center text-sm group-hover:scale-105 transition-transform flex-shrink-0">
                                            <i class="ph-bold ph-sign-out"></i>
                                        </div>
                                        <span>Sign Out</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endguest

                {{-- Theme Color Changer (Top-Right Corner) --}}
                <div class="border-l border-stone-200/80 pl-2.5 ml-1 dark:border-slate-800 flex items-center">
                    <button type="button" id="theme-toggle" class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-stone-200/80 bg-white text-zinc-500 transition hover:bg-stone-100 hover:text-zinc-900 shadow-sm dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800" title="Toggle dark/light mode" aria-label="Toggle dark/light mode">
                        <i class="ph-bold ph-moon text-base" id="theme-toggle-icon"></i>
                    </button>
                </div>

                {{-- Mobile Menu Button --}}
                <button type="button" onclick="toggleMobileDrawer(true)" class="lg:hidden inline-flex items-center justify-center w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800/80 text-slate-700 dark:text-slate-200 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors active:scale-95 cursor-pointer" id="nav-mobile-toggle" aria-label="Open Mobile Menu">
                    <i class="ph-bold ph-list text-xl"></i>
                </button>
            </div>
        </div>
    </div>
</nav>

    {{-- Modern Mobile Drawer Overlay & Sheet --}}
    <div id="mobile-drawer-overlay" onclick="toggleMobileDrawer(false)" class="fixed inset-0 z-[9998] bg-slate-950/70 backdrop-blur-sm transition-opacity duration-300 pointer-events-none lg:hidden"></div>

    <aside id="mobile-drawer-sheet" class="fixed top-0 right-0 bottom-0 z-[9999] w-80 max-w-[85vw] bg-white dark:bg-slate-950 border-l border-slate-200/90 dark:border-slate-800 shadow-2xl flex flex-col transition-transform duration-300 lg:hidden">
        {{-- Drawer Header --}}
        <div class="flex items-center justify-between p-4 border-b border-slate-100 dark:border-slate-800">
            <x-brand-logo
                href="{{ url('/') }}"
                class="group flex-shrink-0"
                imageClass="h-8 w-auto"
                textClass="text-base font-bold tracking-tight text-zinc-900"
                accentClass="text-[#2563EB]"
            />
            <button type="button" onclick="toggleMobileDrawer(false)" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 hover:text-slate-900 dark:hover:text-white flex items-center justify-center transition-colors active:scale-90" aria-label="Close Mobile Menu">
                <i class="ph-bold ph-x text-sm"></i>
            </button>
        </div>

        {{-- Drawer Scrollable Content --}}
        <div class="flex-1 overflow-y-auto px-4 py-4 space-y-5">
            {{-- User info if logged in --}}
            @auth
                @php
                    $drawerActivePlan = auth()->user()->activePlan();
                @endphp
                <div class="p-3.5 rounded-2xl bg-gradient-to-br from-blue-50 to-indigo-50/50 dark:from-slate-900 dark:to-slate-900/80 border border-blue-100/80 dark:border-slate-800 flex items-center gap-3">
                    <div class="relative flex-shrink-0">
                        <div class="w-10 h-10 rounded-xl {{ $drawerActivePlan ? 'bg-gradient-to-br from-blue-600 via-indigo-600 to-amber-500 ring-2 ring-amber-400 ring-offset-2 ring-offset-white dark:ring-offset-slate-900 shadow-md shadow-amber-500/25' : 'bg-gradient-to-br from-blue-600 to-indigo-600 shadow-blue-500/20' }} text-white font-black flex items-center justify-center text-sm shadow-sm">
                            <span class="drop-shadow-[0_1px_1.5px_rgba(0,0,0,0.35)]">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                        </div>
                        @if($drawerActivePlan)
                            <span class="absolute -bottom-1 -right-1 w-4.5 h-4.5 rounded-full bg-gradient-to-tr from-amber-500 via-amber-400 to-yellow-300 flex items-center justify-center text-white text-[9.5px] ring-2 ring-white dark:ring-slate-900 shadow-xs" title="Paid Member">
                                <i class="ph-fill ph-crown drop-shadow-[0_1px_1px_rgba(0,0,0,0.35)]"></i>
                            </span>
                        @endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <p class="text-xs font-bold text-slate-900 dark:text-white truncate">{{ auth()->user()->name }}</p>
                            @if($drawerActivePlan)
                                <x-premium-badge :plan="$drawerActivePlan" size="xs" :showPlan="false" />
                            @endif
                        </div>
                        <p class="text-[11px] text-slate-500 capitalize">{{ $drawerActivePlan ? ($drawerActivePlan->plan->name ?? 'Pro Plan') : ucfirst(auth()->user()->role) }}</p>
                    </div>
                </div>
            @endauth

            {{-- Main Navigation Links --}}
            <div>
                <p class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 mb-2 px-2">Discover</p>
                <div class="space-y-1">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('home') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                        <span class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                            <i class="ph-bold ph-house text-base"></i>
                        </span>
                        <span>Home</span>
                    </a>
                    <a href="{{ route('properties.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('properties.index') && !request()->has('type') && !request()->has('purpose') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                        <span class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                            <i class="ph-bold ph-compass text-base"></i>
                        </span>
                        <span>All Properties</span>
                    </a>
                    <a href="{{ route('properties.index', ['purpose' => 'rent']) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request('purpose') === 'rent' ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                        <span class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                            <i class="ph-bold ph-key text-base"></i>
                        </span>
                        <span>Properties for Rent</span>
                    </a>
                    <a href="{{ route('properties.index', ['purpose' => 'buy']) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request('purpose') === 'buy' ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                        <span class="w-8 h-8 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center shrink-0">
                            <i class="ph-bold ph-shopping-bag text-base"></i>
                        </span>
                        <span>Properties for Sale</span>
                    </a>
                    <a href="{{ route('properties.index', ['type' => 'commercial']) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request('type') === 'commercial' ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                        <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                            <i class="ph-bold ph-buildings text-base"></i>
                        </span>
                        <span>Commercial & Shops</span>
                    </a>
                    <a href="{{ route('services.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->is('services*') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                        <span class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                            <i class="ph-bold ph-toolbox text-base"></i>
                        </span>
                        <span>Local Professionals</span>
                    </a>
                    <a href="{{ url('/how-it-works') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all text-slate-700 hover:bg-slate-50">
                        <span class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                            <i class="ph-bold ph-git-merge text-base"></i>
                        </span>
                        <span>How It Works</span>
                    </a>
                    <a href="{{ url('/blog') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all text-slate-700 hover:bg-slate-50">
                        <span class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                            <i class="ph-bold ph-newspaper text-base"></i>
                        </span>
                        <span>Blog & Insights</span>
                    </a>
                </div>
            </div>

            {{-- Quick Post Ad Banner --}}
            <div class="pt-2">
                @auth
                    <a href="{{ route('properties.create') }}" class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold text-xs uppercase tracking-wider shadow-md shadow-blue-500/20 active:scale-[0.98] transition-all" style="color: #ffffff !important;" title="Post Free Advertise">
                        <i class="ph-bold ph-plus-circle text-base" style="color: #ffffff !important;"></i>
                        <span style="color: #ffffff !important;">Post Free Advertise</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" onclick="event.preventDefault(); toggleMobileDrawer(false); window.openAuthModal('login', '{{ route('properties.create') }}');" class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold text-xs uppercase tracking-wider shadow-md shadow-blue-500/20 active:scale-[0.98] transition-all" style="color: #ffffff !important;" title="Post Free Advertise">
                        <i class="ph-bold ph-plus-circle text-base" style="color: #ffffff !important;"></i>
                        <span style="color: #ffffff !important;">Post Free Advertise</span>
                    </a>
                @endauth
            </div>

            {{-- Auth / Settings --}}
            <div class="pt-3 border-t border-slate-100 space-y-1">
                @guest
                    <a href="{{ route('login') }}" onclick="event.preventDefault(); toggleMobileDrawer(false); window.openAuthModal('login');" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        <span class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                            <i class="ph-bold ph-sign-in text-base"></i>
                        </span>
                        <span>Sign In</span>
                    </a>
                    <a href="{{ route('register') }}" onclick="event.preventDefault(); toggleMobileDrawer(false); window.openAuthModal('register');" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-blue-600 hover:bg-blue-50/50">
                        <span class="w-8 h-8 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center shrink-0">
                            <i class="ph-bold ph-user-plus text-base"></i>
                        </span>
                        <span>Create Account</span>
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        <span class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                            <i class="ph-bold ph-squares-four text-base"></i>
                        </span>
                        <span>Dashboard</span>
                    </a>
                    <a href="#" onclick="event.preventDefault(); toggleMobileDrawer(false); window.openProfileModal();" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        <span class="w-8 h-8 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center shrink-0">
                            <i class="ph-bold ph-user-gear text-base"></i>
                        </span>
                        <span>Profile Settings</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" onsubmit="window.performUniversalLogout(event)" class="pt-2">
                        @csrf
                        <button type="button" onclick="window.performUniversalLogout(event)" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-red-500 hover:bg-red-50 transition-all cursor-pointer">
                            <span class="w-8 h-8 rounded-xl bg-red-50 text-red-600 flex items-center justify-center shrink-0">
                                <i class="ph-bold ph-sign-out text-base"></i>
                            </span>
                            <span>Sign Out</span>
                        </button>
                    </form>
                @endguest
            </div>
        </div>
    </aside>

    {{-- Dedicated Mobile Account Modal / Bottom Sheet --}}
    @auth
    @php
        $modalActivePlan = auth()->user()->activePlan();
    @endphp
    <div id="mobile-account-modal" class="fixed inset-0 z-[100000] hidden flex items-end sm:items-center justify-center p-0 sm:p-4 bg-slate-950/40 backdrop-blur-sm" onclick="if(event.target===this) window.closeUserAccountModal()">
        <div class="w-full sm:max-w-md bg-white rounded-t-3xl sm:rounded-3xl p-6 shadow-2xl border border-slate-200 animate-[slideUp_0.2s_ease-out]">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="relative flex-shrink-0">
                        <div class="w-12 h-12 rounded-2xl {{ $modalActivePlan ? 'bg-gradient-to-br from-blue-600 via-indigo-600 to-amber-500 ring-2 ring-amber-400 ring-offset-2 ring-offset-white shadow-lg shadow-amber-500/25' : 'bg-gradient-to-br from-blue-600 to-indigo-600 shadow-blue-500/25' }} text-white font-black text-xl flex items-center justify-center shadow-lg">
                            <span class="drop-shadow-[0_1px_2px_rgba(0,0,0,0.35)]">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                        </div>
                        @if($modalActivePlan)
                            <span class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-gradient-to-tr from-amber-500 via-amber-400 to-yellow-300 flex items-center justify-center text-white text-[10px] ring-2 ring-white shadow-xs" title="Paid Member">
                                <i class="ph-fill ph-crown drop-shadow-[0_1px_1px_rgba(0,0,0,0.35)]"></i>
                            </span>
                        @endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <h3 class="text-base font-black text-slate-900 truncate">{{ auth()->user()->name }}</h3>
                            @if($modalActivePlan)
                                <x-premium-badge :plan="$modalActivePlan" size="xs" :showPlan="false" />
                            @endif
                        </div>
                        <p class="text-xs text-slate-500 capitalize truncate">{{ ucfirst(auth()->user()->role) }} · {{ auth()->user()->email }}</p>
                    </div>
                </div>
                <button type="button" onclick="window.closeUserAccountModal()" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 hover:text-slate-900 flex items-center justify-center transition-colors">
                    <i class="ph-bold ph-x text-base"></i>
                </button>
            </div>
            
            @if($modalActivePlan)
            <div class="mt-3">
                <x-premium-badge :plan="$modalActivePlan" type="card" theme="light" />
            </div>
            @endif
            
            <div class="py-3 space-y-1">
                <a href="{{ route('dashboard') }}" onclick="window.closeUserAccountModal()" class="flex items-center gap-3 px-3.5 py-3 rounded-2xl text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors">
                    <i class="ph-bold ph-squares-four text-xl text-blue-600"></i>
                    <span>My Dashboard</span>
                </a>
                <a href="#" onclick="event.preventDefault(); window.closeUserAccountModal(); window.openProfileModal();" class="flex items-center gap-3 px-3.5 py-3 rounded-2xl text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors">
                    <i class="ph-bold ph-user-gear text-xl text-blue-600"></i>
                    <span>Profile Settings</span>
                </a>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" onclick="window.closeUserAccountModal()" class="flex items-center gap-3 px-3.5 py-3 rounded-2xl text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors">
                        <i class="ph-bold ph-shield-check text-xl text-blue-600"></i>
                        <span>Admin Panel</span>
                    </a>
                @endif
            </div>

            <div class="pt-3 border-t border-slate-100">
                <form method="POST" action="{{ route('logout') }}" onsubmit="window.performUniversalLogout(event)">
                    @csrf
                    <button type="button" onclick="window.performUniversalLogout(event)" class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 font-bold text-sm transition-all cursor-pointer active:scale-[0.98]">
                        <i class="ph-bold ph-sign-out text-base"></i>
                        <span>Sign Out</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endauth

{{-- Spacer for fixed nav --}}
<div class="h-16 lg:h-18"></div>

{{-- Mobile Drawer Controller & Theme Toggle --}}
<script>
(() => {
    // Drawer open/close handler
    window.toggleMobileDrawer = function(open) {
        const overlay = document.getElementById('mobile-drawer-overlay');
        const sheet = document.getElementById('mobile-drawer-sheet');
        if (!overlay || !sheet) return;

        if (open) {
            overlay.classList.add('active');
            sheet.classList.add('active');
            document.body.style.overflow = 'hidden';
        } else {
            overlay.classList.remove('active');
            sheet.classList.remove('active');
            document.body.style.overflow = '';
        }
    };

    // Theme toggle
    const btn = document.getElementById('theme-toggle');
    const icon = document.getElementById('theme-toggle-icon');
    if (btn) {
        const syncIcon = () => {
            const dark = document.documentElement.classList.contains('dark');
            if (icon) icon.className = dark ? 'ph-bold ph-sun text-base' : 'ph-bold ph-moon text-base';
        };

        syncIcon();
        btn.addEventListener('click', () => {
            const dark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('ur-theme', dark ? 'dark' : 'light');
            syncIcon();
        });
    }

    // ESC key closes dropdowns and drawers
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (typeof window.closeUserDropdown === 'function') {
                window.closeUserDropdown();
            }
            if (typeof window.toggleMobileDrawer === 'function') {
                window.toggleMobileDrawer(false);
            }
        }
    });
})();
</script>

{{-- Scoped Styles to Guarantee Navbar Link Text Visibility Across All Pages --}}
<style>
#main-nav .ur-nav-link {
    color: #334155 !important;
}
#main-nav .ur-nav-link:hover {
    color: #2563EB !important;
}
#main-nav .ur-nav-link.active-nav-link {
    color: #2563EB !important;
}
html.dark #main-nav .ur-nav-link {
    color: #cbd5e1 !important;
}
html.dark #main-nav .ur-nav-link:hover {
    color: #60a5fa !important;
}
html.dark #main-nav .ur-nav-link.active-nav-link {
    color: #60a5fa !important;
}
#main-nav #nav-add-property,
#main-nav #nav-add-property *,
#main-nav #nav-register,
#main-nav #nav-register *,
#nav-mobile-top-login,
#nav-mobile-top-login *,
#nav-mobile-top-login i,
#nav-mobile-top-login span {
    color: #ffffff !important;
    fill: #ffffff !important;
}

@media (max-width: 1023px) {
    #main-nav .main-nav,
    #main-nav nav.hidden {
        display: none !important;
        visibility: hidden !important;
        pointer-events: none !important;
        width: 0 !important;
        height: 0 !important;
        position: absolute !important;
    }
    #nav-mobile-top-login {
        display: inline-flex !important;
        visibility: visible !important;
    }
}

.is-mobile-app #main-nav .main-nav {
    display: none !important;
    visibility: hidden !important;
    pointer-events: none !important;
    width: 0 !important;
    height: 0 !important;
    position: absolute !important;
}
.is-mobile-app #nav-mobile-top-login {
    display: inline-flex !important;
    visibility: visible !important;
}

/* Mobile Drawer Overlay & Sheet */
#mobile-drawer-overlay {
    display: none !important;
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    bottom: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    height: 100dvh !important;
    z-index: 99998 !important;
    background: rgba(15, 23, 42, 0.65) !important;
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    opacity: 0;
    transition: opacity 0.3s ease;
    pointer-events: none !important;
}
#mobile-drawer-overlay.active {
    display: block !important;
    opacity: 1 !important;
    pointer-events: auto !important;
}
#mobile-drawer-sheet {
    display: none !important;
    position: fixed !important;
    top: 0 !important;
    right: 0 !important;
    bottom: 0 !important;
    width: 320px !important;
    max-width: 85vw !important;
    height: 100vh !important;
    height: 100dvh !important;
    z-index: 99999 !important;
    background: #ffffff !important;
    border-left: 1px solid rgba(226, 232, 240, 0.8) !important;
    box-shadow: -10px 0 35px rgba(0, 0, 0, 0.25) !important;
    flex-direction: column !important;
    transform: translateX(100%) !important;
    transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
    overflow: hidden !important;
}
#mobile-drawer-sheet.active {
    display: flex !important;
    transform: translateX(0) !important;
}
#mobile-drawer-sheet .overflow-y-auto {
    -webkit-overflow-scrolling: touch;
    overscroll-behavior: contain;
}
html.dark #mobile-drawer-sheet {
    background: #0f172a !important;
    border-left: 1px solid rgba(51, 65, 85, 0.8) !important;
}
</style>
