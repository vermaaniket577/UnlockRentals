{{-- ============================================================
     UNLOCK RENTALS — ELEGANT COMPACT GLASS LOADER
     A sleek, standard-sized glassmorphic loader with official
     brand mark, smooth ambient pulse, and zero screen obstruction.
     ============================================================ --}}

<style>
/* ── Top Slim Accent Progress Bar ────────────────────────────── */
#ur-progress-bar-top {
    position: fixed;
    top: 0;
    left: 0;
    width: 0%;
    height: 3px;
    background: linear-gradient(90deg, #2563eb, #60a5fa, #38bdf8, #2563eb);
    background-size: 200% 100%;
    z-index: 9999999;
    opacity: 0;
    transition: opacity 0.15s ease, width 0.3s ease;
    animation: ur-bar-shimmer 1.2s infinite linear;
    box-shadow: 0 0 8px rgba(37, 99, 235, 0.6);
}

#ur-progress-bar-top.ur-active {
    opacity: 1;
}

@keyframes ur-bar-shimmer {
    0%   { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

/* ── Floating Compact Glass Loader Overlay ───────────────────── */
#ur-animated-loader {
    pointer-events: none;
    transition: opacity 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}

#ur-animated-loader.ur-loading-active {
    pointer-events: auto;
    opacity: 1;
}

/* Ambient glow & orbit keyframes */
@keyframes ur-ambient-glow {
    0%, 100% { transform: scale(1); opacity: 0.2; }
    50% { transform: scale(1.18); opacity: 0.45; }
}

.ur-ambient-glow-circle {
    animation: ur-ambient-glow 3s ease-in-out infinite;
}

/* Standard Compact Card */
.ur-loader-card {
    width: 142px;
    padding: 1.25rem 1rem 1.1rem;
    border-radius: 1.5rem;
    background: rgba(15, 23, 42, 0.88);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.12);
    box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.5), 0 0 25px rgba(37, 99, 235, 0.22);
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    position: relative;
    overflow: hidden;
}
</style>

{{-- Top Accent Progress Bar --}}
<div id="ur-progress-bar-top"></div>

{{-- Fullscreen Overlay with Compact Floating Glass Centerpiece --}}
<div id="ur-animated-loader" class="fixed inset-0 bg-slate-950/45 backdrop-blur-[3px] z-[9999998] flex items-center justify-center opacity-0">
    <div class="ur-loader-card">
        
        {{-- Subtle radial shimmer --}}
        <div class="absolute inset-0 bg-gradient-to-tr from-blue-500/10 via-transparent to-indigo-500/10 pointer-events-none"></div>
        
        {{-- Animated Brand Icon Container --}}
        <div class="relative z-10 w-16 h-16 mb-2.5 flex items-center justify-center">
            {{-- Glowing ambient back ring --}}
            <div class="absolute inset-0 bg-blue-500/30 rounded-full filter blur-md ur-ambient-glow-circle"></div>
            
            {{-- Orbit spinner --}}
            <div class="absolute w-14 h-14 border border-dashed border-blue-400/40 rounded-full animate-[spin_6s_linear_infinite]"></div>
            
            {{-- Official App Icon Card --}}
            <div class="relative w-11 h-11 bg-white rounded-xl p-1.5 flex items-center justify-center shadow-lg shadow-blue-600/30 ring-1 ring-white/40">
                <img src="{{ asset('images/logo-icon.png') }}" alt="UnlockRentals" title="UnlockRentals" class="w-full h-full object-contain" onerror="this.src='{{ asset('images/icons/icon-192x192.png') }}'">
            </div>
        </div>

        {{-- Progress Line --}}
        <div class="relative z-10 w-16 bg-slate-800 rounded-full h-1 mb-2 overflow-hidden">
            <div id="ur-loader-progress-line" class="bg-gradient-to-r from-blue-500 to-sky-400 h-full w-0 transition-all duration-200 ease-out"></div>
        </div>

        {{-- Clean Brand & Status Labels --}}
        <span class="relative z-10 text-white font-extrabold text-[10.5px] tracking-[0.14em] uppercase font-sans">UnlockRentals</span>
        <span id="ur-loader-status-text" class="relative z-10 text-[9.5px] text-slate-400 font-medium tracking-tight mt-0.5">Loading...</span>
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
    let autoDismissTimer = null;
    let fakeWidth     = 0;
    let started       = false;

    function start() {
        if (started) return;
        started   = true;
        fakeWidth = 0;
        
        clearInterval(timer);
        clearTimeout(autoDismissTimer);

        if (bar) {
            bar.style.width = '0%';
            bar.classList.add('ur-active');
        }
        if (line) line.style.width = '0%';
        if (overlay) overlay.classList.add('ur-loading-active');
        if (textLabel) textLabel.textContent = "Loading...";

        // Incremental progress
        timer = setInterval(() => {
            if (fakeWidth < 90) {
                fakeWidth += (90 - fakeWidth) * 0.1 + 0.8;
                if (bar) bar.style.width = fakeWidth + '%';
                if (line) line.style.width = fakeWidth + '%';
            }
        }, 80);

        // Safety auto-dismiss: never keep the loader stuck if page navigation doesn't unload (e.g. file downloads)
        autoDismissTimer = setTimeout(done, 3000);
    }

    function done() {
        clearInterval(timer);
        clearTimeout(autoDismissTimer);

        if (bar) bar.style.width = '100%';
        if (line) line.style.width = '100%';

        setTimeout(() => {
            if (bar) bar.classList.remove('ur-active');
            if (overlay) overlay.classList.remove('ur-loading-active');
            
            setTimeout(() => {
                if (bar) bar.style.width = '0%';
                if (line) line.style.width = '0%';
                started = false;
            }, 200);
        }, 220);
    }

    window.URLoader = { show: start, hide: done };

    // Dismiss overlay on click in case of edge cases
    if (overlay) {
        overlay.addEventListener('click', done);
    }

    // Trigger on internal navigation link clicks
    document.addEventListener('click', function (e) {
        if (e.defaultPrevented) return;

        const link = e.target.closest('a[href]');
        if (!link) return;

        // Skip loader on download links, files, modals, and special triggers
        if (link.hasAttribute('download') || 
            link.getAttribute('download') !== null ||
            link.href.includes('/download/') ||
            link.href.endsWith('.apk') ||
            link.href.endsWith('.pdf') ||
            link.href.endsWith('.zip') ||
            link.dataset.noLoader === 'true' || 
            link.dataset.urLoaderSkip === 'true' || 
            link.getAttribute('data-no-loader') === 'true' ||
            (link.getAttribute('onclick') && link.getAttribute('onclick').includes('openAuthModal'))) {
            return;
        }

        const href = link.getAttribute('href');
        if (!href || href.startsWith('#') || href.startsWith('javascript') || href.startsWith('tel:') || href.startsWith('mailto:') || link.target === '_blank') return;

        try {
            const url = new URL(href, window.location.origin);
            if (url.origin !== window.location.origin) return;
            if (url.pathname === window.location.pathname && url.search === window.location.search) return;
        } catch (_) { return; }

        start();
    }, false);

    // Trigger on form submits
    document.addEventListener('submit', function (e) {
        if (e.defaultPrevented || e.target.dataset.urLoaderSkip === 'true' || e.target.id === 'ur-modal-login-form' || e.target.id === 'ur-modal-register-form') return;
        start();
    }, false);

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

    // Hide on page load & back/forward navigation
    window.addEventListener('pageshow', done);
    window.addEventListener('focus', done);
    if (document.readyState === 'complete') { done(); }
    else { window.addEventListener('load', done); }
})();
</script>
