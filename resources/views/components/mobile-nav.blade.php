{{-- Mobile Bottom Navigation Bar --}}
<div class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-white/80 dark:bg-slate-950/80 backdrop-blur-xl border-t border-stone-200/50 dark:border-slate-800/80 shadow-[0_-8px_30px_rgb(0,0,0,0.06)] transition-all duration-300 pb-safe-bottom" id="mobile-bottom-nav">
    <div class="flex items-center justify-around h-16 px-2">
        
        {{-- Home Tab --}}
        <a href="{{ route('home') }}" class="flex flex-col items-center justify-center w-16 h-12 rounded-xl transition-all duration-200 {{ request()->routeIs('home') ? 'text-[#2563EB] dark:text-blue-500 scale-105' : 'text-zinc-400 dark:text-slate-500 hover:text-zinc-700 dark:hover:text-slate-300' }}">
            <i class="ph ph-house text-xl mb-0.5"></i>
            <span class="text-[10px] font-bold tracking-tight">Home</span>
            @if(request()->routeIs('home'))
                <span class="w-1 h-1 bg-[#2563EB] dark:bg-blue-500 rounded-full mt-0.5"></span>
            @endif
        </a>

        {{-- Explore Tab --}}
        <a href="{{ route('properties.index') }}" class="flex flex-col items-center justify-center w-16 h-12 rounded-xl transition-all duration-200 {{ request()->routeIs('properties.index') ? 'text-[#2563EB] dark:text-blue-500 scale-105' : 'text-zinc-400 dark:text-slate-500 hover:text-zinc-700 dark:hover:text-slate-300' }}">
            <i class="ph ph-compass text-xl mb-0.5"></i>
            <span class="text-[10px] font-bold tracking-tight">Explore</span>
            @if(request()->routeIs('properties.index') && !request()->has('type'))
                <span class="w-1 h-1 bg-[#2563EB] dark:bg-blue-500 rounded-full mt-0.5"></span>
            @endif
        </a>

        {{-- List Ad / Plus Tab (Conditional / Interactive) --}}
        @auth
            @if(auth()->user()->isOwner() || auth()->user()->isAdmin() || auth()->user()->role === 'tenant')
            <a href="{{ route('properties.create') }}" class="flex flex-col items-center justify-center -mt-5">
                <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-tr from-blue-600 to-indigo-600 rounded-full text-white shadow-lg shadow-blue-500/30 transform transition active:scale-95 hover:rotate-90 duration-300">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                </div>
                <span class="text-[10px] font-bold tracking-tight mt-1 text-zinc-400 dark:text-slate-500">Post Ad</span>
            </a>
            @endif
        @else
            <a href="{{ route('login') }}" class="flex flex-col items-center justify-center -mt-5">
                <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-tr from-blue-600 to-indigo-600 rounded-full text-white shadow-lg shadow-blue-500/30 transform transition active:scale-95 hover:rotate-90 duration-300">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                </div>
                <span class="text-[10px] font-bold tracking-tight mt-1 text-zinc-400 dark:text-slate-500">Post Ad</span>
            </a>
        @endauth

        {{-- Dashboard Tab --}}
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center w-16 h-12 rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'text-[#2563EB] dark:text-blue-500 scale-105' : 'text-zinc-400 dark:text-slate-500 hover:text-zinc-700 dark:hover:text-slate-300' }}">
            <i class="ph ph-squares-four text-xl mb-0.5"></i>
            <span class="text-[10px] font-bold tracking-tight">Dashboard</span>
            @if(request()->routeIs('dashboard'))
                <span class="w-1 h-1 bg-[#2563EB] dark:bg-blue-500 rounded-full mt-0.5"></span>
            @endif
        </a>

        {{-- Support Tab (Interactive Chat Toggle) --}}
        <button onclick="handleMobileSupportClick()" class="flex flex-col items-center justify-center w-16 h-12 rounded-xl transition-all duration-200 text-zinc-400 dark:text-slate-500 hover:text-zinc-700 dark:hover:text-slate-300" id="mobile-support-nav-btn">
            <i class="ph ph-chat-circle-dots text-xl mb-0.5"></i>
            <span class="text-[10px] font-bold tracking-tight">Support</span>
        </button>

    </div>
</div>

{{-- Dynamic padding offset for mobile screens --}}
<style>
    @supports(padding: env(safe-area-inset-bottom)) {
        .pb-safe-bottom {
            padding-bottom: env(safe-area-inset-bottom);
        }
    }
    body {
        padding-bottom: 4rem; /* offset for mobile bottom nav */
    }
    @media (min-width: 768px) {
        body {
            padding-bottom: 0;
        }
    }
</style>

<script>
    function handleMobileSupportClick() {
        const chatTrigger = document.getElementById('chatTrigger');
        const chatWindow = document.getElementById('chatWindow');
        
        if (chatTrigger && chatWindow) {
            // We are on the homepage where chatbot is loaded
            if (chatWindow.classList.contains('hidden') || chatWindow.style.display === 'none') {
                chatTrigger.click();
            } else {
                const closeBtn = document.getElementById('chatClose');
                if (closeBtn) closeBtn.click();
            }
        } else {
            // Redirect to home page with open-chat query param
            window.location.href = "{{ route('home') }}?open-chat=1";
        }
    }
</script>
