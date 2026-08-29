{{-- Mobile Bottom Navigation Bar (Standard Native App Style) --}}
<nav class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-white/95 dark:bg-slate-950/95 backdrop-blur-2xl border-t border-slate-200/90 dark:border-slate-800/90 shadow-[0_-4px_25px_rgba(0,0,0,0.08)] transition-all duration-300 pb-[env(safe-area-inset-bottom,0px)]" id="mobile-bottom-nav" aria-label="Mobile Navigation">
    <div class="grid grid-cols-5 items-center h-16 max-w-lg mx-auto px-2">
        
        {{-- Home Tab --}}
        <a href="{{ route('home') }}" class="group flex flex-col items-center justify-center py-1 rounded-xl transition-all duration-150 active:scale-95 {{ request()->routeIs('home') ? 'text-blue-600 dark:text-blue-400 font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-medium' }}" title="Home" aria-label="Home">
            <div class="relative flex items-center justify-center">
                <i class="{{ request()->routeIs('home') ? 'ph-fill ph-house' : 'ph-bold ph-house' }} text-[22px] transition-transform duration-200 group-hover:scale-110"></i>
                @if(request()->routeIs('home'))
                    <span class="absolute -bottom-1.5 w-1.5 h-1.5 bg-blue-600 dark:bg-blue-400 rounded-full"></span>
                @endif
            </div>
            <span class="text-[10px] tracking-tight mt-1">Home</span>
        </a>

        {{-- Explore Tab --}}
        <a href="{{ route('properties.index') }}" class="group flex flex-col items-center justify-center py-1 rounded-xl transition-all duration-150 active:scale-95 {{ request()->routeIs('properties.*') ? 'text-blue-600 dark:text-blue-400 font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-medium' }}" title="Explore Properties" aria-label="Explore">
            <div class="relative flex items-center justify-center">
                <i class="{{ request()->routeIs('properties.*') ? 'ph-fill ph-compass' : 'ph-bold ph-compass' }} text-[22px] transition-transform duration-200 group-hover:scale-110"></i>
                @if(request()->routeIs('properties.*'))
                    <span class="absolute -bottom-1.5 w-1.5 h-1.5 bg-blue-600 dark:bg-blue-400 rounded-full"></span>
                @endif
            </div>
            <span class="text-[10px] tracking-tight mt-1">Explore</span>
        </a>

        {{-- Center Elevated Action: Post Ad --}}
        <div class="flex flex-col items-center justify-center -mt-6">
            @auth
                <a href="{{ route('properties.create') }}" class="group relative flex items-center justify-center w-13 h-13 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-500/35 border-2 border-white dark:border-slate-900 active:scale-90 transition-all duration-200" title="Post Property" aria-label="Post Property">
                    <i class="ph-bold ph-plus text-xl transition-transform group-hover:rotate-90 duration-300"></i>
                    <span class="sr-only">Post Property</span>
                </a>
            @else
                <a href="{{ route('login') }}" onclick="event.preventDefault(); window.openAuthModal('login', '{{ route('properties.create') }}');" class="group relative flex items-center justify-center w-13 h-13 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-500/35 border-2 border-white dark:border-slate-900 active:scale-90 transition-all duration-200" title="Post Property" aria-label="Post Property">
                    <i class="ph-bold ph-plus text-xl transition-transform group-hover:rotate-90 duration-300"></i>
                    <span class="sr-only">Post Property</span>
                </a>
            @endauth
            <span class="text-[10px] font-bold text-slate-600 dark:text-slate-400 mt-1 tracking-tight">Post Ad</span>
        </div>

        {{-- Dashboard / Account Tab --}}
        @auth
            <a href="{{ route('dashboard') }}" class="group flex flex-col items-center justify-center py-1 rounded-xl transition-all duration-150 active:scale-95 {{ request()->routeIs('dashboard*') ? 'text-blue-600 dark:text-blue-400 font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-medium' }}" title="Dashboard" aria-label="Dashboard">
                <div class="relative flex items-center justify-center">
                    <i class="{{ request()->routeIs('dashboard*') ? 'ph-fill ph-squares-four' : 'ph-bold ph-squares-four' }} text-[22px] transition-transform duration-200 group-hover:scale-110"></i>
                    @if(request()->routeIs('dashboard*'))
                        <span class="absolute -bottom-1.5 w-1.5 h-1.5 bg-blue-600 dark:bg-blue-400 rounded-full"></span>
                    @endif
                </div>
                <span class="text-[10px] tracking-tight mt-1">Dashboard</span>
            </a>
        @else
            <a href="{{ route('login') }}" onclick="event.preventDefault(); window.openAuthModal('login');" class="group flex flex-col items-center justify-center py-1 rounded-xl transition-all duration-150 active:scale-95 text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-medium" title="Sign In" aria-label="Account">
                <div class="relative flex items-center justify-center">
                    <i class="ph-bold ph-user-circle text-[22px] transition-transform duration-200 group-hover:scale-110"></i>
                </div>
                <span class="text-[10px] tracking-tight mt-1">Account</span>
            </a>
        @endauth

        {{-- Support Tab (Triggers Chatbot) --}}
        <button type="button" onclick="handleMobileSupportClick()" class="group flex flex-col items-center justify-center py-1 rounded-xl transition-all duration-150 active:scale-95 text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-medium cursor-pointer" id="mobile-support-nav-btn" title="Support" aria-label="Help and Support">
            <div class="relative flex items-center justify-center">
                <i class="ph-bold ph-chats-circle text-[22px] transition-transform duration-200 group-hover:scale-110"></i>
                <span class="absolute -top-0.5 -right-1 w-2 h-2 bg-emerald-500 rounded-full ring-2 ring-white dark:ring-slate-950"></span>
            </div>
            <span class="text-[10px] tracking-tight mt-1">Support</span>
        </button>

    </div>
</nav>

{{-- Safe spacing offset for mobile screens --}}
<style>
    body {
        padding-bottom: calc(4.25rem + env(safe-area-inset-bottom, 0px)) !important;
    }
    @media (min-width: 768px) {
        body {
            padding-bottom: 0 !important;
        }
    }
</style>

<script>
    function handleMobileSupportClick() {
        const chatTrigger = document.getElementById('chatTrigger');
        const chatWindow = document.getElementById('chatWindow');
        
        if (chatWindow) {
            chatWindow.classList.toggle('active');
            if (chatWindow.classList.contains('active')) {
                const input = document.getElementById('chatInput');
                if (input) setTimeout(() => input.focus(), 150);
            }
        } else {
            window.location.href = "{{ route('home') }}?open-chat=1";
        }
    }
</script>
