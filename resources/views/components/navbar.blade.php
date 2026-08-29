{{-- Navigation Bar Component --}}
<nav id="main-nav" class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-xl border-b border-stone-200/50 transition-all duration-300 dark:bg-slate-950/85 dark:border-slate-800/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 lg:h-18 gap-3 xl:gap-6">

            {{-- Left Side: Brand Logo --}}
            <div class="flex-shrink-0">
                <x-brand-logo
                    href="{{ url('/') }}"
                    id="nav-logo"
                    class="group flex-shrink-0"
                    imageClass="h-9 w-auto transition-transform duration-300 group-hover:scale-[1.02]"
                    textClass="text-lg xl:text-xl font-bold tracking-tight text-zinc-900"
                    accentClass="text-[#2563EB]"
                />
            </div>

            {{-- Center: Desktop Navigation Links (Responsive, Non-overlapping) --}}
            <nav class="hidden lg:flex items-center gap-0.5 xl:gap-1 2xl:gap-1.5 flex-shrink">
                <a href="{{ url('/') }}" class="nav-link px-2.5 xl:px-3 py-1.5 rounded-lg text-xs xl:text-[13.5px] font-semibold text-zinc-600 hover:text-blue-600 hover:bg-stone-50 transition-all flex items-center gap-1.5 whitespace-nowrap {{ request()->is('/') ? 'text-blue-600 bg-blue-50/50 dark:bg-blue-900/20 dark:text-blue-400' : 'dark:text-slate-300 dark:hover:text-white dark:hover:bg-slate-800' }}" id="nav-home" title="Home">
                    <i class="ph-bold ph-house text-sm xl:text-base text-blue-600"></i>
                    <span>Home</span>
                </a>
                <a href="{{ url('/properties') }}" class="nav-link px-2.5 xl:px-3 py-1.5 rounded-lg text-xs xl:text-[13.5px] font-semibold text-zinc-600 hover:text-blue-600 hover:bg-stone-50 transition-all flex items-center gap-1.5 whitespace-nowrap {{ request()->is('properties') && !request('purpose') && !request('type') ? 'text-blue-600 bg-blue-50/50 dark:bg-blue-900/20 dark:text-blue-400' : 'dark:text-slate-300 dark:hover:text-white dark:hover:bg-slate-800' }}" id="nav-discover" title="Discover">
                    <i class="ph-bold ph-compass text-sm xl:text-base text-blue-600"></i>
                    <span>Discover</span>
                </a>
                <a href="{{ url('/properties?purpose=buy') }}" class="nav-link px-2.5 xl:px-3 py-1.5 rounded-lg text-xs xl:text-[13.5px] font-semibold text-zinc-600 hover:text-blue-600 hover:bg-stone-50 transition-all flex items-center gap-1.5 whitespace-nowrap {{ request('purpose') == 'buy' ? 'text-blue-600 bg-blue-50/50 dark:bg-blue-900/20 dark:text-blue-400' : 'dark:text-slate-300 dark:hover:text-white dark:hover:bg-slate-800' }}" id="nav-buy" title="Buy">
                    <i class="ph-bold ph-shopping-bag text-sm xl:text-base text-blue-600"></i>
                    <span>Buy</span>
                </a>
                <a href="{{ url('/properties?purpose=rent') }}" class="nav-link px-2.5 xl:px-3 py-1.5 rounded-lg text-xs xl:text-[13.5px] font-semibold text-zinc-600 hover:text-blue-600 hover:bg-stone-50 transition-all flex items-center gap-1.5 whitespace-nowrap {{ request('purpose') == 'rent' ? 'text-blue-600 bg-blue-50/50 dark:bg-blue-900/20 dark:text-blue-400' : 'dark:text-slate-300 dark:hover:text-white dark:hover:bg-slate-800' }}" id="nav-rent" title="Rent">
                    <i class="ph-bold ph-key text-sm xl:text-base text-blue-600"></i>
                    <span>Rent</span>
                </a>
                <a href="{{ url('/properties?type=commercial') }}" class="nav-link px-2.5 xl:px-3 py-1.5 rounded-lg text-xs xl:text-[13.5px] font-semibold text-zinc-600 hover:text-blue-600 hover:bg-stone-50 transition-all flex items-center gap-1.5 whitespace-nowrap {{ request('type') == 'commercial' || request('type') == 'shop' ? 'text-blue-600 bg-blue-50/50 dark:bg-blue-900/20 dark:text-blue-400' : 'dark:text-slate-300 dark:hover:text-white dark:hover:bg-slate-800' }}" id="nav-commercial" title="Commercial">
                    <i class="ph-bold ph-buildings text-sm xl:text-base text-blue-600"></i>
                    <span>Commercial</span>
                </a>
                <a href="{{ url('/how-it-works') }}" class="nav-link px-2.5 xl:px-3 py-1.5 rounded-lg text-xs xl:text-[13.5px] font-semibold text-zinc-600 hover:text-blue-600 hover:bg-stone-50 transition-all flex items-center gap-1.5 whitespace-nowrap {{ request()->is('how-it-works') || request()->is('process') ? 'text-blue-600 bg-blue-50/50 dark:bg-blue-900/20 dark:text-blue-400' : 'dark:text-slate-300 dark:hover:text-white dark:hover:bg-slate-800' }}" id="nav-process" title="Process">
                    <i class="ph-bold ph-git-merge text-sm xl:text-base text-blue-600"></i>
                    <span>Process</span>
                </a>
                <a href="{{ url('/blog') }}" class="nav-link px-2.5 xl:px-3 py-1.5 rounded-lg text-xs xl:text-[13.5px] font-semibold text-zinc-600 hover:text-blue-600 hover:bg-stone-50 transition-all flex items-center gap-1.5 whitespace-nowrap {{ request()->is('blog*') ? 'text-blue-600 bg-blue-50/50 dark:bg-blue-900/20 dark:text-blue-400' : 'dark:text-slate-300 dark:hover:text-white dark:hover:bg-slate-800' }}" id="nav-blog" title="Blog">
                    <i class="ph-bold ph-newspaper text-sm xl:text-base text-blue-600"></i>
                    <span>Blog</span>
                </a>
            </nav>

            {{-- Right Side: Auth Actions & Top-Right Theme Toggle --}}
            <div class="flex items-center gap-2 xl:gap-3 flex-shrink-0">
                @guest
                    <a href="{{ route('login') }}" class="hidden md:inline-flex px-3 py-1.5 text-xs xl:text-sm font-semibold text-zinc-600 hover:text-zinc-900 transition-colors whitespace-nowrap" id="nav-login" title="Sign In">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}" class="px-3.5 xl:px-4.5 py-1.5 xl:py-2 bg-[#2563EB] hover:bg-[#1D4ED8] text-white text-xs xl:text-sm font-semibold rounded-lg shadow-sm shadow-[#2563EB]/20 transition-all whitespace-nowrap" id="nav-register" title="Get Started">
                        Get Started
                    </a>
                @else
                    @php
                        $navActivePlan = auth()->user()->activePlan();
                        $navPlanName = strtolower($navActivePlan?->plan?->name ?? '');
                        $navBadgeClass = str_contains($navPlanName, 'enterprise') ? 'from-slate-900 to-teal-500' : (str_contains($navPlanName, 'platinum') ? 'from-blue-600 to-violet-600' : (str_contains($navPlanName, 'gold') ? 'from-amber-500 to-yellow-300' : 'from-slate-400 to-sky-300'));
                    @endphp
                    @if(auth()->user()->isOwner() || auth()->user()->isAdmin())
                    <a href="{{ route('properties.create') }}" class="hidden md:inline-flex items-center gap-1.5 px-3 xl:px-4 py-1.5 xl:py-2 bg-[#2563EB] hover:bg-[#1D4ED8] text-white text-xs xl:text-sm font-bold rounded-lg shadow-sm shadow-[#2563EB]/20 transition-all whitespace-nowrap" id="nav-add-property" title="List Property">
                        <i class="ph-bold ph-plus-circle text-sm"></i>
                        <span>List Property</span>
                    </a>
                    @endif

                    {{-- User Menu --}}
                    <div class="relative" x-data="{ open: false }">
                        <button onclick="this.nextElementSibling.classList.toggle('hidden')" class="flex items-center gap-1.5 p-1 sm:px-2.5 sm:py-1.5 rounded-full sm:rounded-xl hover:bg-stone-100 dark:hover:bg-slate-800 transition-all relative" id="nav-user-menu" aria-label="User Account">
                            <div class="w-8 h-8 {{ $navActivePlan ? 'bg-gradient-to-br ' . $navBadgeClass . ' ring-2 ring-white shadow-lg shadow-blue-500/20' : 'bg-[#2563EB]' }} rounded-full flex items-center justify-center text-white text-sm font-medium relative overflow-hidden">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                @if($navActivePlan)
                                    <span class="absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-transparent -translate-x-full animate-[premiumShine_2.6s_ease-in-out_infinite]"></span>
                                @endif
                            </div>
                            <span class="hidden xl:inline text-xs xl:text-sm font-semibold text-zinc-700 whitespace-nowrap">{{ auth()->user()->name }}</span>
                            @if($navActivePlan)
                                <span class="hidden 2xl:inline-flex items-center gap-1 rounded-full bg-gradient-to-r {{ $navBadgeClass }} px-2 py-0.5 text-[9px] font-black uppercase tracking-wider text-white shadow-sm whitespace-nowrap">
                                    <i class="ph-bold ph-crown"></i> Pro
                                </span>
                            @endif
                            <i class="ph ph-caret-down text-xs text-zinc-500"></i>
                            @if(isset($adminNotifications) && $adminNotifications['total_unread'] > 0)
                                <span class="absolute top-1 right-2 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></span>
                            @endif
                        </button>

                        {{-- Dropdown --}}
                        <div class="hidden absolute right-0 mt-2 w-56 bg-stone-50 border border-stone-200/50 rounded-sm shadow-2xl overflow-hidden">
                            <div class="px-4 py-3 border-b border-stone-200/50">
                                <p class="text-sm font-medium text-zinc-900 flex items-center gap-2">
                                    {{ auth()->user()->name }}
                                    @if($navActivePlan)
                                        <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-gradient-to-r {{ $navBadgeClass }} text-white"><i class="ph-bold ph-check text-xs"></i></span>
                                    @endif
                                </p>
                                <p class="text-xs text-zinc-500">{{ ucfirst(auth()->user()->role) }}</p>
                                @if($navActivePlan)
                                        <div class="mt-3 rounded-xl border border-slate-200 bg-white p-3 dark:border-slate-700 dark:bg-slate-900">
                                        <div class="flex items-center gap-2">
                                            <span class="grid h-8 w-8 place-items-center rounded-xl bg-gradient-to-r {{ $navBadgeClass }} text-white"><i class="ph-bold ph-crown"></i></span>
                                            <div>
                                                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Pro Member</p>
                                                <p class="text-xs font-black text-slate-900 dark:text-white">{{ $navActivePlan->plan->name ?? 'Pro Plan' }}</p>
                                                @if($navActivePlan->expires_at)
                                                    <p class="mt-0.5 text-[10px] font-semibold text-slate-500">Expires {{ $navActivePlan->expires_at->format('M d, Y') }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="py-1">
                                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-zinc-500 hover:text-zinc-900 hover:bg-stone-50 transition-all" id="nav-dashboard" title="Dashboard">
                                    <i class="ph ph-squares-four"></i> Dashboard
                                </a>
                                <a href="#" onclick="event.preventDefault(); window.openProfileModal();" class="flex items-center gap-3 px-4 py-2.5 text-sm text-zinc-500 hover:text-zinc-900 hover:bg-stone-50 transition-all" id="nav-profile-settings" title="Profile Settings">
                                    <i class="ph ph-user-gear"></i> Profile Settings
                                </a>
                                @if(auth()->user()->isOwner())
                                <a href="{{ route('inquiries.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-zinc-500 hover:text-zinc-900 hover:bg-stone-50 transition-all" id="nav-inquiries" title="Inquiries">
                                    <i class="ph ph-chat-dots"></i> Inquiries
                                </a>
                                @endif
                                @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-zinc-500 hover:text-zinc-900 hover:bg-stone-50 transition-all" id="nav-admin" title="Admin Panel">
                                    <i class="ph ph-shield-check"></i> Admin Panel
                                </a>
                                <a href="{{ route('admin.settings') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-zinc-500 hover:text-zinc-900 hover:bg-stone-50 transition-all" id="nav-admin-settings" title="Content &amp; Settings">
                                    <i class="ph ph-gear"></i> Content & Settings
                                </a>
                                <a href="{{ route('admin.feedback') }}" class="flex items-center justify-between px-4 py-2.5 text-sm text-zinc-500 hover:text-zinc-900 hover:bg-stone-50 transition-all" id="nav-admin-feedback" title="UnlockRentals">
                                    <div class="flex items-center gap-3">
                                        <i class="ph ph-chat-centered-text"></i> Customer Feedback
                                    </div>
                                    @if(isset($adminNotifications) && $adminNotifications['new_feedbacks'] > 0)
                                        <span class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $adminNotifications['new_feedbacks'] }}</span>
                                    @endif
                                </a>
                                <a href="{{ route('admin.chats') }}" class="flex items-center justify-between px-4 py-2.5 text-sm text-zinc-500 hover:text-zinc-900 hover:bg-stone-50 transition-all" id="nav-admin-chats" title="UnlockRentals">
                                    <div class="flex items-center gap-3">
                                        <i class="ph ph-chat-circle-dots"></i> Chat History
                                    </div>
                                    @if(isset($adminNotifications) && $adminNotifications['unread_chats'] > 0)
                                        <span class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $adminNotifications['unread_chats'] }}</span>
                                    @endif
                                </a>
                                <a href="{{ route('admin.callbacks') }}" class="flex items-center justify-between px-4 py-2.5 text-sm text-zinc-500 hover:text-zinc-900 hover:bg-stone-50 transition-all" id="nav-admin-callbacks" title="UnlockRentals">
                                    <div class="flex items-center gap-3">
                                        <i class="ph ph-phone-call"></i> Callback Leads
                                    </div>
                                    @if(isset($adminNotifications) && $adminNotifications['new_callbacks'] > 0)
                                        <span class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $adminNotifications['new_callbacks'] }}</span>
                                    @endif
                                </a>
                                <a href="{{ route('admin.plans') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-zinc-500 hover:text-zinc-900 hover:bg-stone-50 transition-all" id="nav-admin-plans" title="Manage Plans">
                                    <i class="ph ph-crown"></i> Manage Plans
                                </a>
                                <a href="{{ route('admin.subscriptions') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-zinc-500 hover:text-zinc-900 hover:bg-stone-50 transition-all" id="nav-admin-subscriptions" title="User Subscriptions">
                                    <i class="ph ph-receipt"></i> User Subscriptions
                                </a>
                                @endif
                            </div>
                            <div class="border-t border-stone-200/50 py-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-400 hover:text-red-300 hover:bg-red-500/5 transition-all" id="nav-logout">
                                        <i class="ph ph-sign-out"></i> Sign Out
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
                <button type="button" onclick="toggleMobileDrawer(true)" class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800/80 text-slate-700 dark:text-slate-200 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors active:scale-95 cursor-pointer" id="nav-mobile-toggle" aria-label="Open Mobile Menu">
                    <i class="ph-bold ph-list text-xl"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- Modern Mobile Drawer Overlay & Sheet --}}
    <div id="mobile-drawer-overlay" onclick="toggleMobileDrawer(false)" class="fixed inset-0 z-[100] bg-slate-950/60 backdrop-blur-sm transition-opacity duration-300 opacity-0 pointer-events-none md:hidden"></div>

    <aside id="mobile-drawer-sheet" class="fixed top-0 right-0 bottom-0 z-[101] w-80 max-w-[85vw] bg-white dark:bg-slate-950 border-l border-slate-200/80 dark:border-slate-800/80 shadow-2xl flex flex-col transition-transform duration-300 translate-x-full md:hidden">
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
                <div class="p-3.5 rounded-2xl bg-gradient-to-br from-blue-50 to-indigo-50/50 dark:from-slate-900 dark:to-slate-900/80 border border-blue-100/80 dark:border-slate-800 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-600 text-white font-black flex items-center justify-center text-sm shadow-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-bold text-slate-900 dark:text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[11px] text-slate-500 capitalize">{{ auth()->user()->role }}</p>
                    </div>
                </div>
            @endauth

            {{-- Main Navigation Links --}}
            <div>
                <p class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-2 px-2">Discover</p>
                <div class="space-y-1">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('home') ? 'bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-900' }}">
                        <i class="ph-bold ph-house text-lg text-blue-600"></i>
                        <span>Home</span>
                    </a>
                    <a href="{{ route('properties.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('properties.index') && !request()->has('type') && !request()->has('purpose') ? 'bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-900' }}">
                        <i class="ph-bold ph-compass text-lg text-blue-600"></i>
                        <span>All Properties</span>
                    </a>
                    <a href="{{ route('properties.index', ['purpose' => 'rent']) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request('purpose') === 'rent' ? 'bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-900' }}">
                        <i class="ph-bold ph-key text-lg text-blue-600"></i>
                        <span>Properties for Rent</span>
                    </a>
                    <a href="{{ route('properties.index', ['purpose' => 'buy']) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request('purpose') === 'buy' ? 'bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-900' }}">
                        <i class="ph-bold ph-shopping-bag text-lg text-blue-600"></i>
                        <span>Properties for Sale</span>
                    </a>
                    <a href="{{ route('properties.index', ['type' => 'commercial']) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request('type') === 'commercial' ? 'bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-900' }}">
                        <i class="ph-bold ph-buildings text-lg text-blue-600"></i>
                        <span>Commercial & Shops</span>
                    </a>
                    <a href="{{ url('/how-it-works') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-900">
                        <i class="ph-bold ph-git-merge text-lg text-blue-600"></i>
                        <span>How It Works</span>
                    </a>
                    <a href="{{ url('/blog') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-900">
                        <i class="ph-bold ph-newspaper text-lg text-blue-600"></i>
                        <span>Blog & Insights</span>
                    </a>
                </div>
            </div>

            {{-- Quick Post Ad Banner --}}
            <div class="pt-2">
                @auth
                    <a href="{{ route('properties.create') }}" class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold text-xs uppercase tracking-wider shadow-md shadow-blue-500/20 active:scale-[0.98] transition-all">
                        <i class="ph-bold ph-plus-circle text-base"></i>
                        <span>Post Property Ad</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" onclick="event.preventDefault(); toggleMobileDrawer(false); window.openAuthModal('login', '{{ route('properties.create') }}');" class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold text-xs uppercase tracking-wider shadow-md shadow-blue-500/20 active:scale-[0.98] transition-all">
                        <i class="ph-bold ph-plus-circle text-base"></i>
                        <span>Post Property Ad</span>
                    </a>
                @endauth
            </div>

            {{-- Auth / Settings --}}
            <div class="pt-3 border-t border-slate-100 dark:border-slate-800 space-y-1">
                @guest
                    <a href="{{ route('login') }}" onclick="event.preventDefault(); toggleMobileDrawer(false); window.openAuthModal('login');" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-900">
                        <i class="ph-bold ph-sign-in text-lg text-blue-600"></i>
                        <span>Sign In</span>
                    </a>
                    <a href="{{ route('register') }}" onclick="event.preventDefault(); toggleMobileDrawer(false); window.openAuthModal('register');" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-blue-600 dark:text-blue-400 hover:bg-blue-50/50 dark:hover:bg-slate-900">
                        <i class="ph-bold ph-user-plus text-lg"></i>
                        <span>Create Account</span>
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-900">
                        <i class="ph-bold ph-squares-four text-lg text-blue-600"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="#" onclick="event.preventDefault(); toggleMobileDrawer(false); window.openProfileModal();" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-900">
                        <i class="ph-bold ph-user-gear text-lg text-blue-600"></i>
                        <span>Profile Settings</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="pt-2">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-red-500 hover:bg-red-50 dark:hover:bg-red-950/30 transition-all cursor-pointer">
                            <i class="ph-bold ph-sign-out text-lg"></i>
                            <span>Sign Out</span>
                        </button>
                    </form>
                @endguest
            </div>
        </div>
    </aside>
</nav>

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
            overlay.classList.remove('opacity-0', 'pointer-events-none');
            overlay.classList.add('opacity-100', 'pointer-events-auto');
            sheet.classList.remove('translate-x-full');
            sheet.classList.add('translate-x-0');
            document.body.style.overflow = 'hidden';
        } else {
            overlay.classList.remove('opacity-100', 'pointer-events-auto');
            overlay.classList.add('opacity-0', 'pointer-events-none');
            sheet.classList.remove('translate-x-0');
            sheet.classList.add('translate-x-full');
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

    // Close user dropdown on outside click
    document.addEventListener('click', function(e) {
        document.querySelectorAll('#nav-user-menu').forEach(btn => {
            const dropdown = btn.nextElementSibling;
            if (dropdown && !btn.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    });
})();
</script>
