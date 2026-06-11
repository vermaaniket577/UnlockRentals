{{-- Navigation Bar Component --}}
<nav id="main-nav" class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-xl border-b border-stone-200/50 transition-all duration-300 dark:bg-slate-950/85 dark:border-slate-800/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 lg:h-18">

            {{-- Logo --}}
            <x-brand-logo
                href="{{ route('home') }}"
                id="nav-logo"
                class="group"
                imageClass="h-10 w-auto transition-transform duration-300 group-hover:scale-[1.02]"
                textClass="text-xl font-bold tracking-tight text-zinc-900"
                accentClass="text-[#2563EB]"
            />

            {{-- Desktop Navigation --}}
            <div class="hidden md:flex items-center gap-1">
                <a href="{{ route('home') }}" class="nav-link px-4 py-2 rounded-sm text-sm font-medium text-zinc-500 hover:text-zinc-900 hover:bg-stone-50 transition-all {{ request()->routeIs('home') ? 'text-zinc-900 bg-stone-50' : '' }}" id="nav-home">
                    Home
                </a>
                <a href="{{ route('properties.index') }}" class="nav-link px-4 py-2 rounded-sm text-sm font-medium text-zinc-500 hover:text-zinc-900 hover:bg-stone-50 transition-all {{ request()->routeIs('properties.*') ? 'text-zinc-900 bg-stone-50' : '' }}" id="nav-properties">
                    Properties
                </a>
                <a href="{{ route('properties.index', ['type' => 'house']) }}" class="nav-link px-4 py-2 rounded-sm text-sm font-medium text-zinc-500 hover:text-zinc-900 hover:bg-stone-50 transition-all" id="nav-houses">
                    Houses
                </a>
                <a href="{{ route('properties.index', ['type' => 'shop']) }}" class="nav-link px-4 py-2 rounded-sm text-sm font-medium text-zinc-500 hover:text-zinc-900 hover:bg-stone-50 transition-all" id="nav-shops">
                    Shops
                </a>
            </div>

            {{-- Auth Actions --}}
            <div class="flex items-center gap-3">
                <button type="button" id="theme-toggle" class="hidden md:inline-flex h-10 w-10 items-center justify-center rounded-full border border-stone-200/80 bg-white/80 text-zinc-500 transition hover:text-zinc-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300" title="Toggle dark mode" aria-label="Toggle dark mode">
                    <i class="ph-bold ph-moon" id="theme-toggle-icon"></i>
                </button>
                @guest
                    <a href="{{ route('login') }}" class="hidden md:inline-flex px-4 py-2 text-sm font-medium text-zinc-500 hover:text-zinc-900 transition-colors" id="nav-login">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}" class="px-5 py-2.5 bg-[#2563EB] hover:bg-[#1D4ED8] text-white text-sm font-semibold rounded-sm shadow-sm shadow-[#2563EB]/20 hover:shadow-[#2563EB]/20 transition-all" id="nav-register">
                        Get Started
                    </a>
                @else
                    @php
                        $navActivePlan = auth()->user()->activePlan();
                        $navPlanName = strtolower($navActivePlan?->plan?->name ?? '');
                        $navBadgeClass = str_contains($navPlanName, 'enterprise') ? 'from-slate-900 to-teal-500' : (str_contains($navPlanName, 'platinum') ? 'from-blue-600 to-violet-600' : (str_contains($navPlanName, 'gold') ? 'from-amber-500 to-yellow-300' : 'from-slate-400 to-sky-300'));
                    @endphp
                    @if(auth()->user()->isOwner() || auth()->user()->isAdmin())
                    <a href="{{ route('properties.create') }}" class="hidden md:inline-flex items-center gap-2 px-4 py-2.5 bg-[#2563EB] hover:bg-[#1D4ED8] text-white text-sm font-semibold rounded-sm shadow-sm shadow-[#2563EB]/20 hover:shadow-[#2563EB]/20 transition-all" id="nav-add-property">
                        <i class="ph ph-plus-circle"></i>
                        List Property
                    </a>
                    @endif

                    {{-- User Menu --}}
                    <div class="relative" x-data="{ open: false }">
                        <button onclick="this.nextElementSibling.classList.toggle('hidden')" class="flex items-center gap-2 px-3 py-2 rounded-sm hover:bg-stone-50 transition-all relative" id="nav-user-menu">
                            <div class="w-8 h-8 {{ $navActivePlan ? 'bg-gradient-to-br ' . $navBadgeClass . ' ring-2 ring-white shadow-lg shadow-blue-500/20' : 'bg-[#2563EB]' }} rounded-full flex items-center justify-center text-white text-sm font-medium relative overflow-hidden">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                @if($navActivePlan)
                                    <span class="absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-transparent -translate-x-full animate-[premiumShine_2.6s_ease-in-out_infinite]"></span>
                                @endif
                            </div>
                            <span class="hidden md:inline text-sm font-medium text-zinc-700">{{ auth()->user()->name }}</span>
                            @if($navActivePlan)
                                <span class="hidden md:inline-flex items-center gap-1 rounded-full bg-gradient-to-r {{ $navBadgeClass }} px-2.5 py-1 text-[10px] font-black uppercase tracking-wider text-white shadow-sm">
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
                                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-zinc-500 hover:text-zinc-900 hover:bg-stone-50 transition-all" id="nav-dashboard">
                                    <i class="ph ph-squares-four"></i> Dashboard
                                </a>
                                @if(auth()->user()->isOwner())
                                <a href="{{ route('inquiries.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-zinc-500 hover:text-zinc-900 hover:bg-stone-50 transition-all" id="nav-inquiries">
                                    <i class="ph ph-chat-dots"></i> Inquiries
                                </a>
                                @endif
                                @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-zinc-500 hover:text-zinc-900 hover:bg-stone-50 transition-all" id="nav-admin">
                                    <i class="ph ph-shield-check"></i> Admin Panel
                                </a>
                                <a href="{{ route('admin.settings') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-zinc-500 hover:text-zinc-900 hover:bg-stone-50 transition-all" id="nav-admin-settings">
                                    <i class="ph ph-gear"></i> Content & Settings
                                </a>
                                <a href="{{ route('admin.feedback') }}" class="flex items-center justify-between px-4 py-2.5 text-sm text-zinc-500 hover:text-zinc-900 hover:bg-stone-50 transition-all" id="nav-admin-feedback">
                                    <div class="flex items-center gap-3">
                                        <i class="ph ph-chat-centered-text"></i> Customer Feedback
                                    </div>
                                    @if(isset($adminNotifications) && $adminNotifications['new_feedbacks'] > 0)
                                        <span class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $adminNotifications['new_feedbacks'] }}</span>
                                    @endif
                                </a>
                                <a href="{{ route('admin.chats') }}" class="flex items-center justify-between px-4 py-2.5 text-sm text-zinc-500 hover:text-zinc-900 hover:bg-stone-50 transition-all" id="nav-admin-chats">
                                    <div class="flex items-center gap-3">
                                        <i class="ph ph-chat-circle-dots"></i> Chat History
                                    </div>
                                    @if(isset($adminNotifications) && $adminNotifications['unread_chats'] > 0)
                                        <span class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $adminNotifications['unread_chats'] }}</span>
                                    @endif
                                </a>
                                <a href="{{ route('admin.callbacks') }}" class="flex items-center justify-between px-4 py-2.5 text-sm text-zinc-500 hover:text-zinc-900 hover:bg-stone-50 transition-all" id="nav-admin-callbacks">
                                    <div class="flex items-center gap-3">
                                        <i class="ph ph-phone-call"></i> Callback Leads
                                    </div>
                                    @if(isset($adminNotifications) && $adminNotifications['new_callbacks'] > 0)
                                        <span class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $adminNotifications['new_callbacks'] }}</span>
                                    @endif
                                </a>
                                <a href="{{ route('admin.plans') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-zinc-500 hover:text-zinc-900 hover:bg-stone-50 transition-all" id="nav-admin-plans">
                                    <i class="ph ph-crown"></i> Manage Plans
                                </a>
                                <a href="{{ route('admin.subscriptions') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-zinc-500 hover:text-zinc-900 hover:bg-stone-50 transition-all" id="nav-admin-subscriptions">
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

                {{-- Mobile Menu Button --}}
                <button onclick="document.getElementById('mobile-menu').classList.toggle('hidden')" class="md:hidden p-2 text-zinc-500 hover:text-zinc-900" id="nav-mobile-toggle">
                    <i class="ph ph-list text-xl"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div id="mobile-menu" class="hidden md:hidden border-t border-stone-200/50 bg-white/95 backdrop-blur-xl">
        <div class="px-4 py-3 space-y-1">
            <a href="{{ route('home') }}" class="block px-4 py-2.5 rounded-sm text-sm text-zinc-500 hover:text-zinc-900 hover:bg-stone-50">Home</a>
            <a href="{{ route('properties.index') }}" class="block px-4 py-2.5 rounded-sm text-sm text-zinc-500 hover:text-zinc-900 hover:bg-stone-50">Properties</a>
            <a href="{{ route('properties.index', ['type' => 'house']) }}" class="block px-4 py-2.5 rounded-sm text-sm text-zinc-500 hover:text-zinc-900 hover:bg-stone-50">Houses</a>
            <a href="{{ route('properties.index', ['type' => 'shop']) }}" class="block px-4 py-2.5 rounded-sm text-sm text-zinc-500 hover:text-zinc-900 hover:bg-stone-50">Shops</a>
            @guest
                <a href="{{ route('login') }}" class="block px-4 py-2.5 rounded-sm text-sm text-zinc-500 hover:text-zinc-900 hover:bg-stone-50">Sign In</a>
            @endguest
        </div>
    </div>
</nav>

{{-- Spacer for fixed nav --}}
<div class="h-16 lg:h-18"></div>

{{-- Theme toggle --}}
<script>
(() => {
    const btn = document.getElementById('theme-toggle');
    const icon = document.getElementById('theme-toggle-icon');
    if (!btn) return;

    const syncIcon = () => {
        const dark = document.documentElement.classList.contains('dark');
        icon.className = dark ? 'ph-bold ph-sun' : 'ph-bold ph-moon';
    };

    syncIcon();
    btn.addEventListener('click', () => {
        const dark = document.documentElement.classList.toggle('dark');
        localStorage.setItem('ur-theme', dark ? 'dark' : 'light');
        syncIcon();
    });
})();
</script>

{{-- Close dropdown on outside click --}}
<script>
document.addEventListener('click', function(e) {
    document.querySelectorAll('#nav-user-menu').forEach(btn => {
        const dropdown = btn.nextElementSibling;
        if (dropdown && !btn.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });
});
</script>
