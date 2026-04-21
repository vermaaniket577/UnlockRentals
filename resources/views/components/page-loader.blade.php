{{-- ============================================================
     UNLOCK RENTAL — PREMIUM PAGE LOADER
     Triggered on: form submit, nav clicks, page transitions
     ============================================================ --}}

{{-- Loader HTML --}}
<div id="ur-loader" aria-label="Loading" role="status" aria-live="polite">
    {{-- Backdrop --}}
    <div class="ur-loader__backdrop"></div>

    {{-- Core card --}}
    <div class="ur-loader__card">

        {{-- Animated building icon --}}
        <div class="ur-loader__icon-wrap">
            <svg class="ur-loader__building" viewBox="0 0 64 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                {{-- Building body --}}
                <rect class="ur-b-fill" x="8"  y="28" width="48" height="44" rx="3"/>
                {{-- Roof / triangle --}}
                <polygon class="ur-b-roof" points="4,30 32,6 60,30"/>
                {{-- Door --}}
                <rect class="ur-b-door" x="26" y="54" width="12" height="18" rx="2"/>
                {{-- Windows row 1 --}}
                <rect class="ur-b-win ur-win-delay-0" x="13" y="36" width="9" height="9" rx="1.5"/>
                <rect class="ur-b-win ur-win-delay-1" x="27" y="36" width="9" height="9" rx="1.5"/>
                <rect class="ur-b-win ur-win-delay-2" x="41" y="36" width="9" height="9" rx="1.5"/>
                {{-- Windows row 2 --}}
                <rect class="ur-b-win ur-win-delay-3" x="13" y="50" width="9" height="9" rx="1.5"/>
                <rect class="ur-b-win ur-win-delay-4" x="41" y="50" width="9" height="9" rx="1.5"/>
            </svg>

            {{-- Spinning ring --}}
            <svg class="ur-loader__ring" viewBox="0 0 100 100" fill="none">
                <circle class="ur-ring-track" cx="50" cy="50" r="44" stroke-width="4"/>
                <circle class="ur-ring-arc"   cx="50" cy="50" r="44" stroke-width="4"
                        stroke-linecap="round"
                        stroke-dasharray="60 216"
                        stroke-dashoffset="0"/>
            </svg>
        </div>

        {{-- Brand name --}}
        <div class="ur-loader__brand">
            <span class="ur-brand-unlock">Unlock</span><span class="ur-brand-rental">Rental</span>
        </div>

        {{-- Dynamic status line --}}
        <p class="ur-loader__status" id="ur-status-text">Finding premium spaces&hellip;</p>

        {{-- Progress bar --}}
        <div class="ur-loader__progress-track">
            <div class="ur-loader__progress-fill" id="ur-progress-bar"></div>
        </div>

        {{-- Floating golden dots --}}
        <div class="ur-loader__dots">
            <span class="ur-dot"></span>
            <span class="ur-dot"></span>
            <span class="ur-dot"></span>
        </div>
    </div>
</div>

{{-- ============================================================
     STYLES
     ============================================================ --}}
<style>
/* ---------- Variables ---------- */
:root {
    --ldr-gold:        #2563EB;
    --ldr-gold-light:  rgba(37,99,235,.15);
    --ldr-gold-glow:   rgba(37,99,235,.4);
    --ldr-dark:        #09090b;
    --ldr-card-bg:     rgba(10,10,12,.92);
    --ldr-shine:       rgba(255,255,255,.06);
}

/* ---------- Overlay ---------- */
#ur-loader {
    position: fixed;
    inset: 0;
    z-index: 99999;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    pointer-events: none;
    transition: opacity .35s ease;
}
#ur-loader.ur-loader--visible {
    opacity: 1;
    pointer-events: all;
}

.ur-loader__backdrop {
    position: absolute;
    inset: 0;
    background: rgba(5,5,7,.78);
    backdrop-filter: blur(14px) saturate(1.6);
    -webkit-backdrop-filter: blur(14px) saturate(1.6);
}

/* ---------- Card ---------- */
.ur-loader__card {
    position: relative;
    z-index: 1;
    width: 260px;
    padding: 42px 32px 36px;
    background: var(--ldr-card-bg);
    border: 1px solid rgba(201,160,80,.22);
    border-radius: 24px;
    box-shadow:
        0 40px 120px rgba(0,0,0,.7),
        0 0 0 1px rgba(255,255,255,.045) inset,
        0 0 60px var(--ldr-gold-glow);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0;
    transform: translateY(22px) scale(.96);
    transition: transform .4s cubic-bezier(.34,1.56,.64,1);
    overflow: hidden;
}
.ur-loader__card::before {                 /* subtle shine stripe */
    content: '';
    position: absolute;
    top: 0; left: -60%;
    width: 55%; height: 100%;
    background: linear-gradient(105deg, transparent, var(--ldr-shine), transparent);
    animation: ur-card-shine 3.2s ease infinite;
}
#ur-loader.ur-loader--visible .ur-loader__card {
    transform: translateY(0) scale(1);
}

/* ---------- Icon wrap ---------- */
.ur-loader__icon-wrap {
    position: relative;
    width: 108px;
    height: 108px;
    margin-bottom: 22px;
    flex-shrink: 0;
}

/* Building SVG */
.ur-loader__building {
    position: absolute;
    inset: 14px;
    width: calc(100% - 28px);
    height: calc(100% - 28px);
    filter: drop-shadow(0 0 8px var(--ldr-gold-glow));
}
.ur-b-fill  { fill: rgba(201,160,80,.12); stroke: var(--ldr-gold); stroke-width: 1.5; }
.ur-b-roof  { fill: none; stroke: var(--ldr-gold); stroke-width: 1.5; stroke-linejoin: round; }
.ur-b-door  { fill: rgba(201,160,80,.18); stroke: var(--ldr-gold); stroke-width: 1.2; }
.ur-b-win   {
    fill: var(--ldr-gold-light);
    stroke: var(--ldr-gold);
    stroke-width: 1;
    animation: ur-win-blink 2.4s ease-in-out infinite;
}
.ur-win-delay-0 { animation-delay: 0s; }
.ur-win-delay-1 { animation-delay: .25s; }
.ur-win-delay-2 { animation-delay: .5s; }
.ur-win-delay-3 { animation-delay: .15s; }
.ur-win-delay-4 { animation-delay: .65s; }

@keyframes ur-win-blink {
    0%, 100% { fill: var(--ldr-gold-light); }
    50%       { fill: rgba(201,160,80,.55);  }
}

/* Spinning ring SVG */
.ur-loader__ring {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    animation: ur-ring-spin 1.6s linear infinite;
}
.ur-ring-track { stroke: rgba(201,160,80,.12); }
.ur-ring-arc   {
    stroke: var(--ldr-gold);
    stroke-dasharray: 72 216;
    animation: ur-arc-chase 1.6s ease-in-out infinite;
    filter: drop-shadow(0 0 4px var(--ldr-gold));
}
@keyframes ur-ring-spin {
    to { transform: rotate(360deg); transform-origin: 50px 50px; }
}
@keyframes ur-arc-chase {
    0%   { stroke-dashoffset: 0;    stroke-dasharray: 20 260; }
    40%  { stroke-dashoffset: -60;  stroke-dasharray: 90 190; }
    80%  { stroke-dashoffset: -200; stroke-dasharray: 60 220; }
    100% { stroke-dashoffset: -271; stroke-dasharray: 20 260; }
}

/* ---------- Brand ---------- */
.ur-loader__brand {
    font-family: 'Outfit', 'Inter', sans-serif;
    font-size: 20px;
    font-weight: 800;
    letter-spacing: -.4px;
    margin-bottom: 10px;
}
.ur-brand-unlock  { color: #fff; }
.ur-brand-rental  { color: var(--ldr-gold); }

/* ---------- Status text ---------- */
.ur-loader__status {
    font-family: 'Inter', sans-serif;
    font-size: 12.5px;
    color: rgba(255,255,255,.48);
    text-align: center;
    letter-spacing: .2px;
    margin-bottom: 20px;
    min-height: 18px;
    transition: opacity .3s;
}

/* ---------- Progress bar ---------- */
.ur-loader__progress-track {
    width: 100%;
    height: 3px;
    background: rgba(255,255,255,.07);
    border-radius: 99px;
    overflow: hidden;
    margin-bottom: 22px;
}
.ur-loader__progress-fill {
    height: 100%;
    width: 0%;
    background: linear-gradient(90deg, #60a5fa, var(--ldr-gold));
    border-radius: 99px;
    box-shadow: 0 0 8px var(--ldr-gold-glow);
    transition: width .4s ease;
}

/* ---------- Bouncing dots ---------- */
.ur-loader__dots {
    display: flex;
    gap: 7px;
    align-items: center;
}
.ur-dot {
    display: block;
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: var(--ldr-gold);
    animation: ur-dot-bounce .9s ease-in-out infinite;
    box-shadow: 0 0 6px var(--ldr-gold-glow);
}
.ur-dot:nth-child(1) { animation-delay: 0s; }
.ur-dot:nth-child(2) { animation-delay: .15s; }
.ur-dot:nth-child(3) { animation-delay: .3s; }
@keyframes ur-dot-bounce {
    0%,80%,100% { transform: translateY(0);   opacity: .5; }
    40%          { transform: translateY(-7px); opacity: 1;  }
}

/* ---------- Shine keyframe ---------- */
@keyframes ur-card-shine {
    0%   { left: -60%; }
    60%  { left: 120%; }
    100% { left: 120%; }
}
</style>

{{-- ============================================================
     SCRIPT — loader controller
     ============================================================ --}}
<script>
(function () {
    'use strict';

    /* ── State ── */
    const MESSAGES = [
        'Finding premium spaces\u2026',
        'Curating luxury listings\u2026',
        'Verifying properties\u2026',
        'Almost ready\u2026',
        'Loading your results\u2026',
    ];

    let _progressTimer  = null;
    let _progress       = 0;
    let _messageTimer   = null;
    let _msgIdx         = 0;

    const loaderEl   = document.getElementById('ur-loader');
    const progressEl = document.getElementById('ur-progress-bar');
    const statusEl   = document.getElementById('ur-status-text');

    /* ── Show ── */
    function showLoader(message) {
        if (!loaderEl) return;
        _progress = 0;
        _msgIdx   = 0;

        if (progressEl) progressEl.style.width = '0%';
        if (statusEl)   statusEl.textContent    = message || MESSAGES[0];

        loaderEl.classList.add('ur-loader--visible');

        // Animate progress bar
        _progressTimer = setInterval(() => {
            if (_progress < 85) {
                _progress += Math.random() * 8 + 2;
                if (_progress > 85) _progress = 85;
                if (progressEl) progressEl.style.width = _progress + '%';
            }
        }, 320);

        // Rotate status messages
        _messageTimer = setInterval(() => {
            _msgIdx = (_msgIdx + 1) % MESSAGES.length;
            if (statusEl) statusEl.textContent = MESSAGES[_msgIdx];
        }, 2200);
    }

    /* ── Hide ── */
    function hideLoader() {
        clearInterval(_progressTimer);
        clearInterval(_messageTimer);
        if (progressEl) progressEl.style.width = '100%';
        setTimeout(() => {
            if (loaderEl) loaderEl.classList.remove('ur-loader--visible');
            if (progressEl) progressEl.style.width = '0%';
        }, 380);
    }

    /* ── Expose globally ── */
    window.URLoader = { show: showLoader, hide: hideLoader };

    /* ════════════════════════════════════════
       AUTO-BIND: Form submits → show loader
       ════════════════════════════════════════ */
    document.addEventListener('submit', function (e) {
        const form = e.target;

        // Skip forms that handle their own loading state (e.g. chatbot)
        if (form.dataset.urLoaderSkip === 'true') return;
        // Skip forms that use AJAX (no hard navigation)
        if (form.dataset.urLoaderAjax === 'true') return;

        const method = (form.method || 'GET').toUpperCase();
        const customMsg = form.dataset.urLoaderMsg;

        let msg = customMsg;
        if (!msg) {
            if (method === 'GET')  msg = 'Searching properties\u2026';
            else                   msg = 'Submitting your request\u2026';
        }
        showLoader(msg);
    }, true);

    /* ════════════════════════════════════════
       AUTO-BIND: Navigation link clicks
       ════════════════════════════════════════ */
    document.addEventListener('click', function (e) {
        const link = e.target.closest('a[href]');
        if (!link) return;

        const href = link.getAttribute('href');

        // Skip: anchors, javascript:, external, data attributes
        if (!href
            || href.startsWith('#')
            || href.startsWith('javascript')
            || href.startsWith('mailto')
            || href.startsWith('tel')
            || link.target === '_blank'
            || link.dataset.urLoaderSkip === 'true'
        ) return;

        // Skip external URLs
        try {
            const url = new URL(href, window.location.origin);
            if (url.origin !== window.location.origin) return;
        } catch (_) { return; }

        const customMsg = link.dataset.urLoaderMsg;
        showLoader(customMsg || 'Loading\u2026');
    }, true);

    /* ════════════════════════════════════════
       Hide loader on page fully loaded / shown
       ════════════════════════════════════════ */
    window.addEventListener('pageshow', function (e) {
        // pageshow fires on bfcache restore too
        hideLoader();
    });

    // Also hide if the page loaded normally
    if (document.readyState === 'complete') {
        hideLoader();
    } else {
        window.addEventListener('load', hideLoader);
    }

})();
</script>
