{{-- Navigation Bar Component --}}
<nav id="main-nav" class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-xl border-b border-stone-200/50 transition-all duration-300">
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
                @guest
                    <a href="{{ route('login') }}" class="hidden md:inline-flex px-4 py-2 text-sm font-medium text-zinc-500 hover:text-zinc-900 transition-colors" id="nav-login">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}" class="px-5 py-2.5 bg-[#2563EB] hover:bg-[#1D4ED8] text-white text-sm font-semibold rounded-sm shadow-sm shadow-[#2563EB]/20 hover:shadow-[#2563EB]/20 transition-all" id="nav-register">
                        Get Started
                    </a>
                @else
                    @if(auth()->user()->isOwner() || auth()->user()->isAdmin())
                    <a href="{{ route('properties.create') }}" class="hidden md:inline-flex items-center gap-2 px-4 py-2.5 bg-[#2563EB] hover:bg-[#1D4ED8] text-white text-sm font-semibold rounded-sm shadow-sm shadow-[#2563EB]/20 hover:shadow-[#2563EB]/20 transition-all" id="nav-add-property">
                        <i class="ph ph-plus-circle"></i>
                        List Property
                    </a>
                    @endif

                    {{-- User Menu --}}
                    <div class="relative" x-data="{ open: false }">
                        <button onclick="this.nextElementSibling.classList.toggle('hidden')" class="flex items-center gap-2 px-3 py-2 rounded-sm hover:bg-stone-50 transition-all" id="nav-user-menu">
                            <div class="w-8 h-8 bg-[#2563EB] rounded-full flex items-center justify-center text-white text-sm font-medium">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span class="hidden md:inline text-sm font-medium text-zinc-700">{{ auth()->user()->name }}</span>
                            <i class="ph ph-caret-down text-xs text-zinc-500"></i>
                        </button>

                        {{-- Dropdown --}}
                        <div class="hidden absolute right-0 mt-2 w-56 bg-stone-50 border border-stone-200/50 rounded-sm shadow-2xl overflow-hidden">
                            <div class="px-4 py-3 border-b border-stone-200/50">
                                <p class="text-sm font-medium text-zinc-900">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-zinc-500">{{ ucfirst(auth()->user()->role) }}</p>
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
                                <a href="{{ route('admin.feedback') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-zinc-500 hover:text-zinc-900 hover:bg-stone-50 transition-all" id="nav-admin-feedback">
                                    <i class="ph ph-chat-centered-text"></i> Customer Feedback
                                </a>
                                <a href="{{ route('admin.chats') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-zinc-500 hover:text-zinc-900 hover:bg-stone-50 transition-all" id="nav-admin-chats">
                                    <i class="ph ph-chat-circle-dots"></i> Chat History
                                </a>
                                <a href="{{ route('admin.callbacks') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-zinc-500 hover:text-zinc-900 hover:bg-stone-50 transition-all" id="nav-admin-callbacks">
                                    <i class="ph ph-phone-call"></i> Callback Leads
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
