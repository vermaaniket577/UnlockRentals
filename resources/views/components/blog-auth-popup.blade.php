@guest
{{-- Blog Reading Scroll-Triggered Auth / Sign Up & Sign In Pop-up --}}
<div id="blog-reader-auth-popup" 
     class="fixed inset-0 z-[99998] flex items-center justify-center p-3 sm:p-4 bg-slate-950/75 backdrop-blur-md transition-all duration-300 opacity-0 pointer-events-none pb-[env(safe-area-inset-bottom,0px)]" 
     style="display: none;" 
     role="dialog" 
     aria-modal="true" 
     aria-labelledby="blog-auth-popup-title">

    {{-- Modal Card Container --}}
    <div class="relative w-full max-w-[440px] bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/90 dark:border-slate-800 shadow-2xl overflow-hidden transform scale-95 transition-all duration-300 my-auto text-center"
         id="blog-reader-auth-card">
         
        {{-- Top Accent Gradient Bar --}}
        <div class="h-2 w-full bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600"></div>

        {{-- Close Button --}}
        <button type="button" 
                onclick="window.closeBlogReaderPopup()" 
                class="absolute top-4 right-4 z-20 w-8 h-8 rounded-full bg-slate-100/90 dark:bg-slate-800 text-slate-500 hover:text-slate-900 dark:hover:text-white flex items-center justify-center transition-all hover:scale-105 active:scale-95 cursor-pointer shadow-xs" 
                title="Close" 
                aria-label="Close modal">
            <i class="ph-bold ph-x text-sm"></i>
        </button>

        <div class="p-6 sm:p-8">
            {{-- Brand / Icon Badge --}}
            <div class="w-14 h-14 mx-auto rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center shadow-lg shadow-blue-500/30 mb-4 ring-4 ring-blue-500/10">
                <i class="ph-bold ph-sparkle text-2xl"></i>
            </div>

            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-[11px] font-extrabold uppercase tracking-wider mb-2.5 border border-blue-200/50 dark:border-blue-800/40">
                <i class="ph-bold ph-users-three"></i> UnlockRentals Community
            </div>

            <h3 id="blog-auth-popup-title" class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight leading-snug mb-2 font-['Playfair_Display',serif]">
                Enjoying this Guide?
            </h3>
            
            <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 leading-relaxed max-w-sm mx-auto mb-6">
                Join UnlockRentals to connect directly with verified property owners and explore 10,000+ listings with <strong class="text-blue-600 dark:text-blue-400 font-extrabold">Zero Brokerage</strong>.
            </p>

            {{-- Value Proposition Bullets --}}
            <div class="bg-slate-50 dark:bg-slate-800/50 rounded-2xl p-3.5 mb-6 text-left border border-slate-100 dark:border-slate-800/80">
                <div class="space-y-2.5 text-xs font-semibold text-slate-700 dark:text-slate-300">
                    <div class="flex items-center gap-2.5">
                        <span class="w-5 h-5 rounded-full bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center flex-shrink-0 text-[11px]">
                            <i class="ph-bold ph-check"></i>
                        </span>
                        <span>100% Direct Owner Contacts</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <span class="w-5 h-5 rounded-full bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center flex-shrink-0 text-[11px]">
                            <i class="ph-bold ph-check"></i>
                        </span>
                        <span>Zero Brokerage on Verified Flats & PGs</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <span class="w-5 h-5 rounded-full bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center flex-shrink-0 text-[11px]">
                            <i class="ph-bold ph-check"></i>
                        </span>
                        <span>Instant Alerts for New Nearby Rentals</span>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="space-y-2.5">
                {{-- Sign Up / Create Account --}}
                <button type="button" 
                        onclick="window.openAuthFromBlog('register')" 
                        class="w-full py-3.5 px-6 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold text-sm shadow-lg shadow-blue-500/25 active:scale-[0.98] transition-all flex items-center justify-center gap-2 cursor-pointer">
                    <i class="ph-bold ph-user-plus text-base"></i>
                    <span>Create Free Account (Sign Up)</span>
                </button>

                {{-- Sign In --}}
                <button type="button" 
                        onclick="window.openAuthFromBlog('login')" 
                        class="w-full py-3 px-6 rounded-xl border border-slate-200 dark:border-slate-700 hover:bg-slate-100/70 dark:hover:bg-slate-800 text-slate-800 dark:text-slate-200 font-bold text-xs active:scale-[0.98] transition-all flex items-center justify-center gap-2 cursor-pointer bg-white dark:bg-slate-900 shadow-xs">
                    <i class="ph-bold ph-sign-in text-base text-blue-600 dark:text-blue-400"></i>
                    <span>Already have an account? Sign In</span>
                </button>
            </div>

            {{-- Continue Reading Link --}}
            <button type="button" 
                    onclick="window.closeBlogReaderPopup()" 
                    class="mt-4 text-xs font-semibold text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors cursor-pointer inline-flex items-center gap-1">
                <span>Continue reading article</span>
                <i class="ph-bold ph-arrow-right text-[10px]"></i>
            </button>
        </div>
    </div>
</div>

<script>
(function() {
    const STORAGE_KEY = 'ur_blog_auth_popup_dismissed';
    let popupTriggered = false;

    window.openAuthFromBlog = function(tab) {
        window.closeBlogReaderPopup();
        if (typeof window.openAuthModal === 'function') {
            window.openAuthModal(tab);
        } else {
            window.location.href = tab === 'register' ? '{{ route('register') }}' : '{{ route('login') }}';
        }
    };

    window.openBlogReaderPopup = function() {
        if (popupTriggered) return;
        popupTriggered = true;
        sessionStorage.setItem(STORAGE_KEY, '1');

        const modal = document.getElementById('blog-reader-auth-popup');
        const card = document.getElementById('blog-reader-auth-card');
        if (!modal || !card) return;

        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';

        requestAnimationFrame(() => {
            modal.classList.remove('opacity-0', 'pointer-events-none');
            modal.classList.add('opacity-100');
            card.classList.remove('scale-95');
            card.classList.add('scale-100');
        });
    };

    window.closeBlogReaderPopup = function() {
        sessionStorage.setItem(STORAGE_KEY, '1');
        const modal = document.getElementById('blog-reader-auth-popup');
        const card = document.getElementById('blog-reader-auth-card');
        if (!modal || !card) return;

        modal.classList.remove('opacity-100');
        modal.classList.add('opacity-0', 'pointer-events-none');
        card.classList.remove('scale-100');
        card.classList.add('scale-95');

        setTimeout(() => {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }, 250);
    };

    document.addEventListener('DOMContentLoaded', function() {
        // If already dismissed or triggered in this session, don't re-attach scroll trigger
        if (sessionStorage.getItem(STORAGE_KEY)) {
            return;
        }

        const modal = document.getElementById('blog-reader-auth-popup');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    window.closeBlogReaderPopup();
                }
            });
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('blog-reader-auth-popup');
                if (modal && modal.style.display === 'flex') {
                    window.closeBlogReaderPopup();
                }
            }
        });

        let ticking = false;
        function checkScroll() {
            if (popupTriggered || sessionStorage.getItem(STORAGE_KEY)) {
                window.removeEventListener('scroll', onScroll);
                return;
            }

            const scrollY = window.scrollY || window.pageYOffset;
            const docHeight = document.documentElement.scrollHeight - window.innerHeight;
            const scrollPercent = docHeight > 0 ? (scrollY / docHeight) : 0;

            // Trigger when scrolled down past 28% of page OR at least 500px down
            if (scrollY >= 500 && scrollPercent >= 0.28) {
                window.removeEventListener('scroll', onScroll);
                window.openBlogReaderPopup();
            }
        }

        function onScroll() {
            if (!ticking) {
                window.requestAnimationFrame(function() {
                    checkScroll();
                    ticking = false;
                });
                ticking = true;
            }
        }

        window.addEventListener('scroll', onScroll, { passive: true });
    });
})();
</script>
@endguest
