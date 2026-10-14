{{-- ============================================================
     UNLOCK RENTALS — ULTRA-FAST REACT-STYLE SPA PAGE LOADER & ROUTER
     Provides instantaneous client-side navigation (0ms perceived latency),
     in-memory predictive caching, native View Transitions, smooth top
     progress bar (Next.js/YouTube style), and instant Back/Forward history.
     ============================================================ --}}

<style>
/* ── Top Slim Accent Progress Bar (Next.js / YouTube / GitHub Grade) ── */
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
    pointer-events: none;
    transition: opacity 0.15s ease, width 0.22s cubic-bezier(0.16, 1, 0.3, 1);
    animation: ur-bar-shimmer 1.2s infinite linear;
    box-shadow: 0 0 10px rgba(37, 99, 235, 0.8), 0 0 5px rgba(56, 189, 248, 0.6);
}

#ur-progress-bar-top.ur-active {
    opacity: 1;
}

/* Glowing Leading Tip (NProgress / Next.js Style) */
#ur-progress-bar-top::after {
    content: '';
    position: absolute;
    right: 0;
    top: 0;
    width: 100px;
    height: 100%;
    box-shadow: 0 0 14px #38bdf8, 0 0 8px #2563eb;
    opacity: 1;
    transform: rotate(3deg) translateY(-2px);
    pointer-events: none;
}

@keyframes ur-bar-shimmer {
    0%   { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

/* ── Floating Compact Glass Loader Overlay (For Heavy Operations & Forms) ── */
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
    background: rgba(15, 23, 42, 0.94);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.12);
    box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.5), 0 0 25px rgba(37, 99, 235, 0.22);
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    position: relative;
    overflow: hidden;
}

@media (max-width: 768px) {
    /* Mobile GPU Optimization: Disable heavy blur kernels to preserve 60/120fps touch responsiveness */
    #ur-animated-loader {
        backdrop-filter: none !important;
        -webkit-backdrop-filter: none !important;
        background: rgba(15, 23, 42, 0.55) !important;
    }
    .ur-loader-card {
        backdrop-filter: none !important;
        -webkit-backdrop-filter: none !important;
        background: rgba(15, 23, 42, 0.97) !important;
    }
}
</style>

{{-- Top Accent Progress Bar --}}
<div id="ur-progress-bar-top"></div>

{{-- Fullscreen Overlay with Compact Floating Glass Centerpiece (Appears only on slow requests >650ms or form submits) --}}
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

    let timer            = null;
    let overlayTimer     = null;
    let autoDismissTimer = null;
    let fakeWidth        = 0;
    let started          = false;

    // Start loading: Instantly activates slim top bar (0ms feedback), delays heavy center modal
    function start(showCenterModalImmediate = false) {
        if (started) return;
        started   = true;
        fakeWidth = 20;
        
        clearInterval(timer);
        clearTimeout(overlayTimer);
        clearTimeout(autoDismissTimer);

        if (bar) {
            bar.style.width = '20%';
            bar.classList.add('ur-active');
        }
        if (line) line.style.width = '20%';
        if (textLabel) textLabel.textContent = "Loading...";

        // Only show center modal if navigation takes longer than 650ms (or on heavy form submits)
        if (showCenterModalImmediate) {
            if (overlay) overlay.classList.add('ur-loading-active');
        } else {
            overlayTimer = setTimeout(() => {
                if (started && overlay) {
                    overlay.classList.add('ur-loading-active');
                }
            }, 650);
        }

        // Fluid incremental progress simulation
        timer = setInterval(() => {
            if (fakeWidth < 90) {
                fakeWidth += (90 - fakeWidth) * 0.15 + 0.8;
                if (bar) bar.style.width = fakeWidth + '%';
                if (line) line.style.width = fakeWidth + '%';
            }
        }, 50);

        // Safety auto-dismiss
        autoDismissTimer = setTimeout(done, 5000);
    }

    function done() {
        clearInterval(timer);
        clearTimeout(overlayTimer);
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
            }, 180);
        }, 160);
    }

    window.URLoader = { show: () => start(true), start: () => start(false), hide: done };

    if (overlay) {
        overlay.addEventListener('click', done);
    }

    // ============================================================
    // REACT-STYLE IN-MEMORY SPA CLIENT ROUTER & INSTANT PRELOADER
    // ============================================================
    const urPageCache = new Map();
    const CACHE_MAX_ENTRIES = 35;
    const CACHE_TTL_MS = 5 * 60 * 1000; // 5-minute in-memory cache
    const inflightFetches = new Map();
    let currentNavController = null;

    function normalizeUrlKey(url) {
        try {
            const u = new URL(url, window.location.origin);
            return u.pathname + u.search;
        } catch (_) {
            return url;
        }
    }

    function setCache(key, data) {
        if (urPageCache.size >= CACHE_MAX_ENTRIES) {
            const firstKey = urPageCache.keys().next().value;
            urPageCache.delete(firstKey);
        }
        urPageCache.set(key, { ...data, timestamp: Date.now() });
    }

    function getCache(key) {
        const item = urPageCache.get(key);
        if (!item) return null;
        if (Date.now() - item.timestamp > CACHE_TTL_MS) {
            urPageCache.delete(key);
            return null;
        }
        return item;
    }

    // Check if URL is safe and eligible for instant SPA navigation
    function isSpaEligible(url) {
        if (!url || url.origin !== window.location.origin) return false;
        const p = url.pathname.toLowerCase();

        // Standalone root homepage (welcome.blade.php has custom standalone layout without <main>)
        if (p === '/' || p === '') return false;

        // Heavy multi-step forms & wizards: must load natively with 100% fresh DOM, scripts & uploaders
        if (p.includes('/create') ||
            p.includes('/edit') ||
            p.includes('/register') ||
            p.includes('/checkout') ||
            p.includes('/payment')) {
            return false;
        }

        // Exclude admin panels, authentication endpoints, downloads
        if (p.startsWith('/admin') ||
            p.startsWith('/backend') ||
            p.startsWith('/api') ||
            p.startsWith('/sanctum') ||
            p.includes('/login') ||
            p.includes('/logout') ||
            p.includes('/password') ||
            p.endsWith('.pdf') ||
            p.endsWith('.apk') ||
            p.endsWith('.zip') ||
            p.endsWith('.jpg') ||
            p.endsWith('.jpeg') ||
            p.endsWith('.png') ||
            p.endsWith('.webp') ||
            p.includes('/download/')) {
            return false;
        }
        return true;
    }

    // Parse incoming HTML text into structured SPA data
    function parsePageData(htmlText, url) {
        try {
            const parser = new DOMParser();
            const doc = parser.parseFromString(htmlText, 'text/html');

            const main = doc.getElementById('ur-main-container') || doc.querySelector('main');
            if (!main) return null; // Not a standard app page, fallback

            const title = doc.title || '';
            const metaDesc = doc.querySelector('meta[name="description"]')?.getAttribute('content') || '';
            const canonical = doc.querySelector('link[rel="canonical"]')?.getAttribute('href') || url;
            const mobileNav = doc.getElementById('mobile-bottom-nav')?.innerHTML || null;

            // Scripts inside <main>
            const mainScripts = Array.from(main.querySelectorAll('script')).map(s => ({
                src: s.getAttribute('src'),
                content: s.textContent,
                type: s.getAttribute('type') || 'text/javascript'
            }));

            // Page-specific scripts from body (e.g. from @push('scripts'))
            const bodyScripts = [];
            doc.querySelectorAll('body script').forEach(s => {
                if (main.contains(s)) return;
                const src = s.getAttribute('src');
                const content = s.textContent.trim();
                if (src) {
                    if (!src.includes('googletagmanager') && !src.includes('adsbygoogle') && !src.includes('otp-verification') && !src.includes('visitor-tracker')) {
                        bodyScripts.push({ src, type: s.getAttribute('type') || 'text/javascript' });
                    }
                } else if (content.length > 0) {
                    if (!content.includes('sendSubscriptionToServer') && !content.includes('showNetworkToast') && !content.includes('btn-submitting')) {
                        bodyScripts.push({ content, type: s.getAttribute('type') || 'text/javascript' });
                    }
                }
            });

            return {
                title,
                metaDesc,
                canonical,
                mainHtml: main.innerHTML,
                mobileNav,
                mainScripts,
                bodyScripts
            };
        } catch (_) {
            return null;
        }
    }

    // Predictive background prefetcher (populates cache before click release!)
    function prefetchUrl(targetHref) {
        try {
            const targetUrl = new URL(targetHref, window.location.origin);
            if (!isSpaEligible(targetUrl)) return;
            const key = normalizeUrlKey(targetUrl);
            const currentKey = normalizeUrlKey(window.location.href);
            if (key === currentKey || getCache(key) || inflightFetches.has(key)) return;

            const fetchPromise = fetch(targetUrl.href, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-UR-SPA': '1' },
                credentials: 'same-origin',
                priority: 'low'
            })
            .then(res => {
                if (res.ok && res.headers.get('content-type')?.includes('text/html')) {
                    return res.text();
                }
                return null;
            })
            .then(html => {
                inflightFetches.delete(key);
                if (html) {
                    const data = parsePageData(html, targetUrl.href);
                    if (data) setCache(key, data);
                }
            })
            .catch(() => {
                inflightFetches.delete(key);
            });

            inflightFetches.set(key, fetchPromise);
        } catch (_) {}
    }

    // Execute scripts extracted from newly mounted SPA view
    function runPageScripts(mainScripts, bodyScripts) {
        const allScripts = [...(mainScripts || []), ...(bodyScripts || [])];
        allScripts.forEach(scriptObj => {
            if (scriptObj.type && scriptObj.type !== 'text/javascript' && scriptObj.type !== 'application/javascript') return;
            try {
                const s = document.createElement('script');
                if (scriptObj.src) {
                    if (!document.querySelector(`script[src="${scriptObj.src}"]`)) {
                        s.src = scriptObj.src;
                        document.body.appendChild(s);
                    }
                } else if (scriptObj.content) {
                    s.textContent = scriptObj.content;
                    document.body.appendChild(s);
                    s.remove();
                }
            } catch (e) {
                console.warn('[UR-SPA] Script execution notice:', e);
            }
        });
    }

    // Update active highlight classes on desktop navbar links
    function updateNavbarActiveLinks(path, search) {
        const navLinks = document.querySelectorAll('#main-nav .ur-nav-link');
        navLinks.forEach(link => {
            try {
                const linkUrl = new URL(link.getAttribute('href'), window.location.origin);
                const linkPath = linkUrl.pathname;
                const linkSearch = linkUrl.search;
                
                let isActive = false;
                if (linkSearch) {
                    isActive = (linkPath === path && linkSearch === search);
                } else if (linkPath === '/') {
                    isActive = (path === '/' && !search);
                } else {
                    isActive = (path === linkPath && !search);
                }

                if (isActive) {
                    link.classList.add('active-nav-link', 'bg-blue-50/50', 'dark:bg-blue-900/20');
                    link.classList.remove('hover:bg-stone-50', 'dark:hover:bg-slate-800');
                } else {
                    link.classList.remove('active-nav-link', 'bg-blue-50/50', 'dark:bg-blue-900/20');
                    link.classList.add('hover:bg-stone-50', 'dark:hover:bg-slate-800');
                }
            } catch (_) {}
        });
    }

    // Core SPA Page Navigation Engine
    async function urNavigateTo(targetHref, isPop = false, pushState = true) {
        const targetUrl = new URL(targetHref, window.location.origin);
        const key = normalizeUrlKey(targetUrl);
        const currentKey = normalizeUrlKey(window.location.href);

        // Handle same-page anchor jumps
        if (key === currentKey) {
            if (targetUrl.hash) {
                const el = document.getElementById(targetUrl.hash.slice(1));
                if (el) el.scrollIntoView({ behavior: 'smooth' });
            } else {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
            return;
        }

        // Save current scroll position before leaving
        if (!isPop) {
            try {
                history.replaceState({ scrollY: window.scrollY }, '', window.location.href);
            } catch (_) {}
        }

        // Abort previous navigation fetch if still running
        if (currentNavController) {
            currentNavController.abort();
        }
        currentNavController = new AbortController();

        // 0ms instant feedback: start glowing top loader
        start(false);

        let pageData = getCache(key);

        // If not in cache, fetch immediately with top priority
        if (!pageData) {
            try {
                let fetchPromise = inflightFetches.get(key);
                if (!fetchPromise) {
                    const res = await fetch(targetUrl.href, {
                        signal: currentNavController.signal,
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-UR-SPA': '1' },
                        credentials: 'same-origin'
                    });

                    if (!res.ok || !res.headers.get('content-type')?.includes('text/html')) {
                        window.location.href = targetUrl.href;
                        return;
                    }

                    // Check if server redirected to auth or admin
                    const finalUrl = new URL(res.url);
                    if (finalUrl.pathname.startsWith('/admin') || finalUrl.pathname.startsWith('/login')) {
                        window.location.href = res.url;
                        return;
                    }

                    const html = await res.text();
                    pageData = parsePageData(html, res.url);
                } else {
                    await fetchPromise;
                    pageData = getCache(key);
                }

                if (!pageData) {
                    window.location.href = targetUrl.href;
                    return;
                }

                setCache(key, pageData);
            } catch (err) {
                if (err.name === 'AbortError') return;
                window.location.href = targetUrl.href;
                return;
            }
        }

        // DOM Swap with Native View Transition API or CSS crossfade fallback
        const applySwap = () => {
            // Verify main container exists BEFORE modifying history or DOM
            const mainEl = document.getElementById('ur-main-container') || document.querySelector('main');
            if (!mainEl || !pageData || !pageData.mainHtml) {
                window.location.href = targetUrl.href;
                return;
            }

            // 1. Update Title & Meta
            if (pageData.title) document.title = pageData.title;
            if (pageData.metaDesc) {
                const metaDesc = document.querySelector('meta[name="description"]');
                if (metaDesc) metaDesc.setAttribute('content', pageData.metaDesc);
            }
            if (pageData.canonical) {
                const canon = document.querySelector('link[rel="canonical"]');
                if (canon) canon.setAttribute('href', pageData.canonical);
            }

            // 2. Push/Replace History ONLY when swap is guaranteed
            if (pushState && !isPop) {
                history.pushState({ scrollY: 0 }, '', targetUrl.href);
            }

            // 3. Swap <main> container content
            mainEl.innerHTML = pageData.mainHtml;
            mainEl.classList.remove('ur-spa-enter');
            void mainEl.offsetWidth; // Force reflow
            mainEl.classList.add('ur-spa-enter');

            // 4. Update Mobile Bottom Nav active state
            if (pageData.mobileNav) {
                const mobileNavEl = document.getElementById('mobile-bottom-nav');
                if (mobileNavEl) mobileNavEl.innerHTML = pageData.mobileNav;
            }

            // 5. Update Desktop Main Nav active state
            updateNavbarActiveLinks(targetUrl.pathname, targetUrl.search);

            // 6. Execute Scripts
            runPageScripts(pageData.mainScripts, pageData.bodyScripts);

            // 7. Scroll Management (Instant scroll restore on Back/Forward, smooth on click)
            if (targetUrl.hash) {
                const el = document.getElementById(targetUrl.hash.slice(1));
                if (el) el.scrollIntoView({ behavior: 'instant' });
                else window.scrollTo(0, 0);
            } else if (isPop && history.state && typeof history.state.scrollY === 'number') {
                window.scrollTo(0, history.state.scrollY);
            } else {
                window.scrollTo(0, 0);
            }

            // 8. Google Analytics Pageview Tracking
            if (typeof window.gtag === 'function') {
                window.gtag('event', 'page_view', {
                    page_path: targetUrl.pathname + targetUrl.search,
                    page_title: document.title
                });
            }

            // 9. Dispatch custom lifecycle events for page-level components
            window.dispatchEvent(new Event('DOMContentLoaded'));
            document.dispatchEvent(new Event('DOMContentLoaded'));
            window.dispatchEvent(new CustomEvent('ur:page-loaded', { detail: { url: targetUrl.href } }));
            window.dispatchEvent(new CustomEvent('turbolinks:load'));

            // 10. Complete Top Progress Bar
            done();
        };

        if (document.startViewTransition) {
            document.startViewTransition(applySwap);
        } else {
            applySwap();
        }
    }

    // Expose SPA controls globally
    window.URNavigate = urNavigateTo;
    window.URCache = urPageCache;

    // Cache initial boot page for instant 0ms Back-navigation
    const initialKey = normalizeUrlKey(window.location.href);
    const initialMain = document.getElementById('ur-main-container') || document.querySelector('main');
    if (initialMain) {
        setCache(initialKey, {
            title: document.title,
            metaDesc: document.querySelector('meta[name="description"]')?.getAttribute('content') || '',
            canonical: document.querySelector('link[rel="canonical"]')?.getAttribute('href') || window.location.href,
            mainHtml: initialMain.innerHTML,
            mobileNav: document.getElementById('mobile-bottom-nav')?.innerHTML || null,
            mainScripts: [],
            bodyScripts: []
        });
    }

    // Intercept Link Clicks for Instant SPA Navigation
    document.addEventListener('click', function (e) {
        if (e.defaultPrevented) return;
        if (e.button !== 0) return; // Only left clicks
        if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return; // Allow new tab/window

        const link = e.target.closest('a[href]');
        if (!link) return;

        // Skip downloads, modals, explicit opt-outs
        if (link.hasAttribute('download') || 
            link.getAttribute('download') !== null ||
            link.href.includes('/download/') ||
            link.href.endsWith('.apk') ||
            link.href.endsWith('.pdf') ||
            link.href.endsWith('.zip') ||
            link.dataset.noPjax === 'true' ||
            link.dataset.noLoader === 'true' || 
            link.dataset.urLoaderSkip === 'true' || 
            link.getAttribute('data-no-loader') === 'true' ||
            link.getAttribute('data-no-pjax') !== null) {
            return;
        }

        const onclick = link.getAttribute('onclick') || '';
        if (onclick.includes('openAuthModal') || 
            onclick.includes('openUserAccountModal') || 
            onclick.includes('toggleUserDropdown') ||
            onclick.includes('toggleSupportChat') ||
            onclick.includes('handleMobileSupportClick')) {
            return;
        }

        const href = link.getAttribute('href');
        if (!href || href.startsWith('#') || href.startsWith('javascript') || 
            href.startsWith('tel:') || href.startsWith('mailto:') || 
            href.startsWith('whatsapp:') || (link.target && link.target !== '_self')) {
            return;
        }

        try {
            const url = new URL(href, window.location.origin);
            const currentHasMain = !!(document.getElementById('ur-main-container') || document.querySelector('main'));

            // If current page is a standalone view without <main> (like welcome.blade.php)
            // OR if target route is not SPA-eligible (forms, /create, /edit, homepage, etc.),
            // do NOT intercept! Allow natural browser navigation with top progress bar!
            if (!currentHasMain || !isSpaEligible(url)) {
                start(false);
                return;
            }

            // Stop full browser reload and run instant SPA navigation!
            e.preventDefault();
            urNavigateTo(url.href, false, true);
        } catch (_) {
            return;
        }
    }, false);

    // Browser Back and Forward Button Support with 0ms Instant Cache Swap
    window.addEventListener('popstate', function () {
        try {
            const currentHasMain = !!(document.getElementById('ur-main-container') || document.querySelector('main'));
            const url = new URL(window.location.href);
            if (currentHasMain && isSpaEligible(url)) {
                urNavigateTo(window.location.href, true, false);
            }
        } catch (_) {}
    });

    // Predictive Preloading On Touch & Hover
    function onInteractionPrefetch(e) {
        const currentHasMain = !!(document.getElementById('ur-main-container') || document.querySelector('main'));
        if (!currentHasMain) return;

        const link = e.target.closest('a[href]');
        if (!link) return;
        const href = link.getAttribute('href');
        if (!href || href.startsWith('#') || href.startsWith('javascript') || href.startsWith('tel:') || href.startsWith('mailto:') || href.startsWith('whatsapp:') || (link.target && link.target !== '_self')) return;
        if (link.dataset.noPjax === 'true' || link.dataset.urLoaderSkip === 'true') return;

        prefetchUrl(href);
    }

    document.addEventListener('pointerdown', onInteractionPrefetch, { passive: true });
    document.addEventListener('touchstart', onInteractionPrefetch, { passive: true });

    let hoverTimer = null;
    document.addEventListener('mouseover', function (e) {
        clearTimeout(hoverTimer);
        hoverTimer = setTimeout(() => onInteractionPrefetch(e), 40);
    }, { passive: true });

    // Handle Form Submissions: Show loading indicator & invalidate cache so new data shows
    document.addEventListener('submit', function (e) {
        if (e.defaultPrevented || e.target.dataset.urLoaderSkip === 'true' || e.target.id === 'ur-modal-login-form' || e.target.id === 'ur-modal-register-form') return;
        start(true);
        urPageCache.clear();
    }, false);

    // Dismiss on native page events
    window.addEventListener('pageshow', done);
    window.addEventListener('focus', done);
    if (document.readyState === 'complete') { done(); }
    else { window.addEventListener('load', done); }
})();
</script>
