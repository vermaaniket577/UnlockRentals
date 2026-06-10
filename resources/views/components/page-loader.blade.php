{{-- ============================================================
     UNLOCK RENTALS — INTERACTIVE ANIMATED ILLUSTRATIVE LOADER
     A high-end, glassmorphic full-screen transition overlay
     coupled with an interactive, animated illustrative SVG and
     dynamic cycling text lines for ultimate perceived premium quality.
     ============================================================ --}}

<style>
/* ── Progress Bar ────────────────────────────── */
#ur-progress-bar-top {
    position: fixed;
    top: 0;
    left: 0;
    width: 0%;
    height: 3.5px;
    background: linear-gradient(90deg, #2563EB, #a855f7, #2563EB);
    background-size: 200% 100%;
    z-index: 9999999;
    opacity: 0;
    transition: opacity 0.15s ease, width 0.3s ease;
    animation: ur-shimmer 1.2s infinite linear;
    box-shadow: 0 0 10px rgba(37, 99, 235, 0.6), 0 0 4px rgba(168, 85, 247, 0.4);
}

#ur-progress-bar-top.ur-active {
    opacity: 1;
}

@keyframes ur-shimmer {
    0%   { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

/* Glassmorphic Loader Overlay */
#ur-animated-loader {
    pointer-events: none;
    transition: opacity 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

#ur-animated-loader.ur-loading-active {
    pointer-events: auto;
    opacity: 1;
}

/* Pulsing particles */
@keyframes ur-pulse-glow {
    0%, 100% { transform: scale(1); opacity: 0.15; }
    50% { transform: scale(1.15); opacity: 0.35; }
}
.ur-glow-back {
    animation: ur-pulse-glow 4s ease-in-out infinite;
}
</style>

{{-- Top Slim Progress Bar --}}
<div id="ur-progress-bar-top"></div>

{{-- Fullscreen Luxury Overlay --}}
<div id="ur-animated-loader" class="fixed inset-0 bg-zinc-950/80 backdrop-blur-md z-[9999998] flex flex-col items-center justify-center opacity-0">
    <div class="relative bg-zinc-900/90 border border-zinc-800 p-8 rounded-2xl shadow-2xl flex flex-col items-center max-w-sm w-full text-center mx-4 overflow-hidden">
        
        {{-- Shimmer Background layer --}}
        <div class="absolute inset-0 bg-gradient-to-tr from-blue-500/5 via-transparent to-purple-500/5 pointer-events-none"></div>
        
        {{-- Interactive Animated Illustration --}}
        <div class="relative z-10 w-28 h-28 mb-6 flex items-center justify-center">
            {{-- Outer glow ring --}}
            <div class="absolute inset-0 bg-blue-500/20 rounded-full filter blur-xl ur-glow-back"></div>
            
            {{-- Dotted spinning orbits --}}
            <div class="absolute w-24 h-24 border border-dashed border-zinc-700 rounded-full animate-[spin_12s_linear_infinite]"></div>
            <div class="absolute w-20 h-20 border border-dashed border-blue-500/30 rounded-full animate-[spin_8s_linear_infinite_reverse]"></div>
            <div class="absolute w-16 h-16 border-2 border-dashed border-purple-500/20 rounded-full animate-[spin_4s_linear_infinite]"></div>
            
            {{-- Core illustrative SVG lock / key / luxury house --}}
            <div class="relative w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/20 transform transition-transform duration-300 hover:scale-110">
                <svg class="w-6 h-6 text-white animate-bounce" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
        </div>

        {{-- Progress Line --}}
        <div class="relative z-10 w-full bg-zinc-800 rounded-full h-1 mb-4 overflow-hidden">
            <div id="ur-loader-progress-line" class="bg-gradient-to-r from-blue-500 to-purple-500 h-full w-0 transition-all duration-300 ease-out"></div>
        </div>

        {{-- Interactive illustrative labels --}}
        <h3 class="relative z-10 text-white font-semibold text-xs tracking-[0.2em] mb-1 font-sans uppercase">UnlockRentals</h3>
        <p id="ur-loader-status-text" class="relative z-10 text-[10px] text-zinc-400 font-mono tracking-tight h-4">Preparing secure dashboard...</p>
    </div>
</div>

<script>
(function () {
    'use strict';

    const bar       = document.getElementById('ur-progress-bar-top');
    const overlay   = document.getElementById('ur-animated-loader');
    const line      = document.getElementById('ur-loader-progress-line');
    const textLabel = document.getElementById('ur-loader-status-text');

    let timer         = null;
    let textTimer     = null;
    let fakeWidth     = 0;
    let started       = false;

    const phrases = [
        "Connecting to secure gateway...",
        "Syncing real-estate directory...",
        "Fetching luxury listings...",
        "Securing active database nodes...",
        "Generating dynamic UI layout...",
        "Authenticating credentials...",
        "Optimizing interface caching..."
    ];

    function cycleStatusText() {
        let index = 0;
        textLabel.textContent = phrases[0];
        textTimer = setInterval(() => {
            index = (index + 1) % phrases.length;
            textLabel.textContent = phrases[index];
        }, 1200);
    }

    function start() {
        if (started) return;
        started   = true;
        fakeWidth = 0;
        
        clearInterval(timer);
        clearInterval(textTimer);

        // Reset elements
        bar.style.width  = '0%';
        line.style.width = '0%';
        bar.classList.add('ur-active');
        overlay.classList.add('ur-loading-active');

        cycleStatusText();

        // Fake incremental progress
        timer = setInterval(() => {
            if (fakeWidth < 88) {
                fakeWidth += (88 - fakeWidth) * 0.08 + 0.6;
                bar.style.width  = fakeWidth + '%';
                line.style.width = fakeWidth + '%';
            }
        }, 90);
    }

    function done() {
        clearInterval(timer);
        clearInterval(textTimer);

        bar.style.width  = '100%';
        line.style.width = '100%';
        textLabel.textContent = "Interface synchronized successfully!";

        setTimeout(() => {
            bar.classList.remove('ur-active');
            overlay.classList.remove('ur-loading-active');
            
            setTimeout(() => {
                bar.style.width  = '0%';
                line.style.width = '0%';
                started = false;
            }, 300);
        }, 400);
    }

    window.URLoader = { show: start, hide: done };

    // Trigger on all internal link clicks
    document.addEventListener('click', function (e) {
        const link = e.target.closest('a[href]');
        if (!link) return;
        const href = link.getAttribute('href');
        if (!href || href.startsWith('#') || href.startsWith('javascript') || link.target === '_blank') return;

        try {
            const url = new URL(href, window.location.origin);
            if (url.origin !== window.location.origin) return;
            if (url.pathname === window.location.pathname && url.search === window.location.search) return;
        } catch (_) { return; }

        start();
    }, true);

    // Trigger on form submits
    document.addEventListener('submit', function (e) {
        if (e.target.dataset.urLoaderSkip === 'true') return;
        start();
    }, true);

    // Prefetch pages on hover for instant navigation
    const prefetched = new Set();
    document.addEventListener('mouseover', function (e) {
        const link = e.target.closest('a[href]');
        if (!link) return;
        const href = link.getAttribute('href');
        if (!href || href.startsWith('#') || href.startsWith('javascript') || link.target === '_blank') return;

        try {
            const url = new URL(href, window.location.origin);
            if (url.origin !== window.location.origin) return;
            if (prefetched.has(url.pathname)) return;

            prefetched.add(url.pathname);
            const prefetchLink = document.createElement('link');
            prefetchLink.rel  = 'prefetch';
            prefetchLink.href = url.href;
            document.head.appendChild(prefetchLink);
        } catch (_) {}
    }, { passive: true });

    // Hide on page load
    window.addEventListener('pageshow', done);
    if (document.readyState === 'complete') { done(); }
    else { window.addEventListener('load', done); }
})();
</script>
