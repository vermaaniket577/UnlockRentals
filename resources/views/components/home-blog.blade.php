{{-- ============================================================
     UNLOCK RENTALS — HOMEPAGE BLOG & GUIDES SLIDER (RIGHT TO LEFT)
     Configurable from Admin Panel (Settings -> Homepage Slider)
     ============================================================ --}}
@php
    $sliderEnabled = \App\Models\Setting::get('home_blog_slider_enabled', '1') !== '0';
    $sliderDirection = \App\Models\Setting::get('home_blog_slider_direction', 'rtl'); // 'rtl' (default) or 'ltr'
    $sliderSpeed = \App\Models\Setting::get('home_blog_slider_speed', 'normal'); // 'slow', 'normal', 'fast'
    $savedSliderIds = json_decode(\App\Models\Setting::get('home_blog_slider_ids', '[]'), true) ?: [];

    $speedMs = match($sliderSpeed) {
        'slow' => 5000,
        'fast' => 2400,
        default => 3500,
    };

    $articlesList = collect();

    // 1. Try loading from database if blogs table exists
    if (\Illuminate\Support\Facades\Schema::hasTable('blogs')) {
        try {
            if (!empty($savedSliderIds)) {
                $articlesList = \App\Models\Blog::published()
                    ->whereIn('id', $savedSliderIds)
                    ->get();
            }

            // Fallback to slider toggle column or featured articles
            if ($articlesList->isEmpty()) {
                $articlesList = \App\Models\Blog::published()
                    ->where(function ($q) {
                        if (\Illuminate\Support\Facades\Schema::hasColumn('blogs', 'show_in_slider')) {
                            $q->where('show_in_slider', true);
                        }
                        $q->orWhere('is_featured', true);
                    })
                    ->latest('published_at')
                    ->take(10)
                    ->get();
            }

            // Fallback to latest published
            if ($articlesList->isEmpty()) {
                $articlesList = \App\Models\Blog::published()
                    ->latest('published_at')
                    ->take(10)
                    ->get();
            }
        } catch (\Throwable $e) {
            $articlesList = collect();
        }
    }

    // 2. Default high-quality curated articles fallback (guarantees stunning display)
    $curatedFallback = [
        [
            'slug' => 'top-tips-for-first-time-renters',
            'title' => 'Top 10 Essential Tips for First-Time Renters in 2026',
            'excerpt' => 'Learn everything about rental agreements, security deposits, inspections, and hidden costs to avoid.',
            'category' => 'Tenant Guide',
            'read_time' => '5 min read',
            'image' => 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=800&q=80',
            'author' => 'Priya Sharma',
            'author_avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=120&q=80',
        ],
        [
            'slug' => 'commercial-real-estate-trends-2026',
            'title' => 'Commercial Real Estate Trends: Finding High-Footfall Retail Spaces',
            'excerpt' => 'Discover key metrics and strategies to choose the most profitable retail shop or office space for your business.',
            'category' => 'Commercial Hub',
            'read_time' => '6 min read',
            'image' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=800&q=80',
            'author' => 'Rahul Verma',
            'author_avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=120&q=80',
        ],
        [
            'slug' => 'landlord-guide-maximizing-rental-yield',
            'title' => 'How Property Owners Can Maximize Rental Yield by 25%',
            'excerpt' => 'Smart upgrades, professional photography, digital listing optimization, and tenant screening techniques.',
            'category' => 'Owner Insights',
            'read_time' => '4 min read',
            'image' => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=800&q=80',
            'author' => 'Ananya Roy',
            'author_avatar' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=120&q=80',
        ],
        [
            'slug' => 'legal-checklist-rental-agreements',
            'title' => 'Complete Legal Checklist: Stamp Duty & Registration in India',
            'excerpt' => 'Avoid legal disputes with our verified guide on lock-in periods, eviction laws, and digital rent agreement registration.',
            'category' => 'Legal & Finance',
            'read_time' => '5 min read',
            'image' => 'https://images.unsplash.com/photo-1450133064473-71024230f91b?auto=format&fit=crop&w=800&q=80',
            'author' => 'Vikram Seth',
            'author_avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=120&q=80',
        ],
        [
            'slug' => 'smart-home-tech-for-rentals',
            'title' => 'Top Smart Home Upgrades That Attract High-Paying Renters',
            'excerpt' => 'From keyless digital locks to smart thermostats, discover which affordable tech upgrades boost rental revenue.',
            'category' => 'Lifestyle & Tech',
            'read_time' => '4 min read',
            'image' => 'https://images.unsplash.com/photo-1558002038-1055907df827?auto=format&fit=crop&w=800&q=80',
            'author' => 'Sneha Kapoor',
            'author_avatar' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=120&q=80',
        ],
        [
            'slug' => 'city-rental-market-outlook',
            'title' => 'India Rental Market Outlook 2026: Prime Localities & ROI',
            'excerpt' => 'In-depth analysis of rental appreciation rates across Bangalore, Mumbai, Pune, Delhi NCR, and Hyderabad.',
            'category' => 'Market Trends',
            'read_time' => '7 min read',
            'image' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=800&q=80',
            'author' => 'Arjun Patel',
            'author_avatar' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=120&q=80',
        ],
    ];

    $sliderCards = [];
    if ($articlesList->isNotEmpty()) {
        foreach ($articlesList as $art) {
            $sliderCards[] = [
                'slug' => $art->slug,
                'title' => $art->title,
                'excerpt' => $art->excerpt,
                'category' => $art->category,
                'read_time' => $art->estimated_read_time,
                'image' => $art->cover_image_url,
                'author' => $art->author_display_name,
                'author_avatar' => $art->author_avatar_url,
            ];
        }
    }

    // Merge fallback items if fewer than 4 to make the carousel full & continuous
    if (count($sliderCards) < 4) {
        $existingSlugs = collect($sliderCards)->pluck('slug')->all();
        foreach ($curatedFallback as $fb) {
            if (!in_array($fb['slug'], $existingSlugs)) {
                $sliderCards[] = $fb;
            }
            if (count($sliderCards) >= 6) break;
        }
    }
@endphp

@if($sliderEnabled)
<section class="py-16 sm:py-20 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-200/60 dark:border-slate-800 relative overflow-hidden" id="rental-guides">
    {{-- Decorative Background Glow --}}
    <div class="absolute -top-24 right-0 w-96 h-96 bg-blue-500/5 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 left-0 w-96 h-96 bg-indigo-500/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        {{-- Section Header with Title & Navigation Controls --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 sm:mb-12 gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 border border-blue-200/60 dark:border-blue-800/60 mb-3 shadow-2xs">
                    <i class="ph-bold ph-newspaper text-sm"></i>
                    <span>Knowledge Hub</span>
                </div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white font-['Playfair_Display',serif]">
                    Latest Rental Guides & Insights
                </h2>
                <p class="text-slate-600 dark:text-slate-400 text-sm sm:text-base mt-2 max-w-xl">
                    Smart advice on tenant rights, stamp duty, property valuation, and commercial space leasing.
                </p>
            </div>

            <div class="flex items-center gap-3 self-start md:self-auto">
                {{-- Manual Slide Controls --}}
                <div class="flex items-center gap-2">
                    <button type="button" id="blog-slider-prev"
                            aria-label="Previous articles"
                            class="w-10 h-10 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:text-blue-600 hover:border-blue-600 dark:hover:border-blue-500 flex items-center justify-center transition-all shadow-sm active:scale-95 cursor-pointer">
                        <i class="ph-bold ph-caret-left text-base"></i>
                    </button>
                    <button type="button" id="blog-slider-next"
                            aria-label="Next articles"
                            class="w-10 h-10 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:text-blue-600 hover:border-blue-600 dark:hover:border-blue-500 flex items-center justify-center transition-all shadow-sm active:scale-95 cursor-pointer">
                        <i class="ph-bold ph-caret-right text-base"></i>
                    </button>
                </div>

                <a href="{{ url('/blog') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-white text-sm font-bold shadow-sm hover:border-blue-600 hover:text-blue-600 transition-all" title="View All Articles">
                    <span>View All Articles</span>
                    <i class="ph-bold ph-arrow-right text-xs"></i>
                </a>
            </div>
        </div>

        {{-- Custom Styles to guarantee exact 3-card desktop display without relying on arbitrary utility classes --}}
        <style>
            #rental-guides .blog-slider-card {
                flex: 0 0 calc((100% - 48px) / 3);
                width: calc((100% - 48px) / 3);
                max-width: calc((100% - 48px) / 3);
                box-sizing: border-box;
            }
            @media (max-width: 1024px) {
                #rental-guides .blog-slider-card {
                    flex: 0 0 calc((100% - 24px) / 2);
                    width: calc((100% - 24px) / 2);
                    max-width: calc((100% - 24px) / 2);
                }
            }
            @media (max-width: 640px) {
                #rental-guides .blog-slider-card {
                    flex: 0 0 calc(100% - 32px);
                    width: calc(100% - 32px);
                    max-width: calc(100% - 32px);
                }
            }
            #rental-guides .blog-card-img-wrap {
                height: 13rem;
                width: 100%;
                overflow: hidden;
                position: relative;
            }
            @media (max-width: 640px) {
                #rental-guides .blog-card-img-wrap {
                    height: 10rem;
                }
            }
        </style>

        {{-- Right-to-Left (RTL) Slider Viewport --}}
        <div id="blog-slider-viewport"
             data-direction="{{ $sliderDirection }}"
             data-interval="{{ $speedMs }}"
             class="relative overflow-hidden cursor-grab active:cursor-grabbing select-none py-2 -my-2">
            
            {{-- Sliding Track --}}
            <div id="blog-slider-track"
                 class="flex gap-4 sm:gap-6 will-change-transform transition-transform ease-out"
                 style="transform: translateX(0px);">
                
                @foreach($sliderCards as $art)
                <article class="blog-slider-card shrink-0 bg-white dark:bg-slate-900 rounded-2xl sm:rounded-3xl overflow-hidden border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col group">
                    <a href="{{ url('/blog/' . $art['slug']) }}" class="blog-card-img-wrap block" title="{{ $art['title'] }}">
                        <img src="{{ $art['image'] }}" alt="{{ $art['title'] }}" title="{{ $art['title'] }}"
                             loading="lazy"
                             onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=800&q=80';"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <span class="absolute top-3 left-3 sm:top-3.5 sm:left-3.5 px-2.5 py-1 rounded-lg text-[10px] sm:text-xs font-extrabold bg-slate-950/80 backdrop-blur-md text-white shadow-sm border border-white/10">
                            {{ $art['category'] }}
                        </span>
                    </a>
                    
                    <div class="p-4 sm:p-5 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-1.5 text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 mb-2">
                                <i class="ph-bold ph-clock text-[11px] sm:text-xs text-blue-500"></i>
                                <span>{{ $art['read_time'] }}</span>
                            </div>
                            <h3 class="text-sm sm:text-base font-extrabold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors mb-2 line-clamp-2 leading-snug sm:leading-snug">
                                <a href="{{ url('/blog/' . $art['slug']) }}" title="{{ $art['title'] }}">
                                    {{ $art['title'] }}
                                </a>
                            </h3>
                            <p class="text-slate-600 dark:text-slate-300 text-xs sm:text-sm leading-relaxed line-clamp-2 mb-4">
                                {{ $art['excerpt'] }}
                            </p>
                        </div>

                        <div class="flex items-center justify-between pt-3 sm:pt-4 border-t border-slate-100 dark:border-slate-800 mt-2">
                            <div class="flex items-center gap-2 sm:gap-2.5 min-w-0">
                                <img src="{{ $art['author_avatar'] }}" alt="{{ $art['author'] }}" title="{{ $art['author'] }}"
                                     loading="lazy"
                                     onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($art['author']) }}&background=2563EB&color=fff&rounded=true&bold=true';"
                                     class="w-6 h-6 sm:w-7 sm:h-7 rounded-full object-cover shrink-0 border border-slate-200 dark:border-slate-700">
                                <span class="text-xs font-bold text-slate-800 dark:text-slate-200 truncate">{{ $art['author'] }}</span>
                            </div>
                            <a href="{{ url('/blog/' . $art['slug']) }}" class="text-xs font-bold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 hover:underline flex items-center gap-1 shrink-0 ml-1" title="Read Guide">
                                <span>Read</span> <i class="ph-bold ph-arrow-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                </article>
                @endforeach

            </div>
        </div>

    </div>
</section>

{{-- Slider JavaScript (Smooth Right-to-Left Auto Sliding, Touch Swipe, Prev/Next Navigation) --}}
<script>
(function() {
    function initBlogSlider() {
        const viewport = document.getElementById('blog-slider-viewport');
        const track = document.getElementById('blog-slider-track');
        const prevBtn = document.getElementById('blog-slider-prev');
        const nextBtn = document.getElementById('blog-slider-next');

        if (!viewport || !track) return;

        const direction = viewport.getAttribute('data-direction') || 'rtl';
        const intervalMs = parseInt(viewport.getAttribute('data-interval'), 10) || 3500;

        let currentIndex = 0;
        let isHovered = false;
        let autoPlayTimer = null;
        let isDragging = false;
        let startX = 0;
        let currentTranslate = 0;
        let prevTranslate = 0;
        let animationID = 0;

        // Clone cards to enable smooth endless wrap
        const originalCards = Array.from(track.children);
        if (originalCards.length === 0) return;

        // Clone items once to allow seamless loop
        originalCards.forEach(card => {
            const clone = card.cloneNode(true);
            clone.setAttribute('aria-hidden', 'true');
            track.appendChild(clone);
        });

        function getCardStep() {
            const firstCard = track.children[0];
            if (!firstCard) return 380;
            const style = window.getComputedStyle(track);
            const gap = parseFloat(style.columnGap || style.gap || 24);
            return firstCard.offsetWidth + gap;
        }

        function maxIndex() {
            return originalCards.length;
        }

        function updateSliderPosition(animate = true) {
            const step = getCardStep();
            track.style.transition = animate ? 'transform 0.55s cubic-bezier(0.25, 1, 0.5, 1)' : 'none';
            const offset = -currentIndex * step;
            track.style.transform = `translateX(${offset}px)`;
            currentTranslate = offset;
            prevTranslate = offset;
        }

        function slideRightToLeft() {
            currentIndex++;
            updateSliderPosition(true);

            // Infinite loop seamless reset
            if (currentIndex >= maxIndex()) {
                setTimeout(() => {
                    currentIndex = 0;
                    updateSliderPosition(false);
                }, 560);
            }
        }

        function slideLeftToRight() {
            if (currentIndex <= 0) {
                currentIndex = maxIndex();
                updateSliderPosition(false);
                setTimeout(() => {
                    currentIndex--;
                    updateSliderPosition(true);
                }, 20);
            } else {
                currentIndex--;
                updateSliderPosition(true);
            }
        }

        function nextSlide() {
            if (direction === 'rtl') {
                slideRightToLeft();
            } else {
                slideLeftToRight();
            }
        }

        function prevSlide() {
            if (direction === 'rtl') {
                slideLeftToRight();
            } else {
                slideRightToLeft();
            }
        }

        function startAutoPlay() {
            stopAutoPlay();
            autoPlayTimer = setInterval(() => {
                if (!isHovered && !isDragging) {
                    nextSlide();
                }
            }, intervalMs);
        }

        function stopAutoPlay() {
            if (autoPlayTimer) {
                clearInterval(autoPlayTimer);
                autoPlayTimer = null;
            }
        }

        // Button Listeners
        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                stopAutoPlay();
                nextSlide();
                startAutoPlay();
            });
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                stopAutoPlay();
                prevSlide();
                startAutoPlay();
            });
        }

        // Hover to Pause
        viewport.addEventListener('mouseenter', () => {
            isHovered = true;
        });
        viewport.addEventListener('mouseleave', () => {
            isHovered = false;
        });

        // Touch & Drag Support
        function getPositionX(e) {
            return e.type.includes('mouse') ? e.pageX : e.touches[0].clientX;
        }

        function touchStart(e) {
            isDragging = true;
            stopAutoPlay();
            startX = getPositionX(e);
            track.style.transition = 'none';
        }

        function touchMove(e) {
            if (!isDragging) return;
            const currentX = getPositionX(e);
            const diff = currentX - startX;
            track.style.transform = `translateX(${prevTranslate + diff}px)`;
        }

        function touchEnd(e) {
            if (!isDragging) return;
            isDragging = false;
            const endX = (e.type.includes('mouse') ? e.pageX : (e.changedTouches ? e.changedTouches[0].clientX : startX));
            const diff = endX - startX;
            const threshold = 60;

            if (diff < -threshold) {
                // Dragged Left -> Advance slider (RTL forward)
                slideRightToLeft();
            } else if (diff > threshold) {
                // Dragged Right -> Reverse slider
                slideLeftToRight();
            } else {
                updateSliderPosition(true);
            }

            startAutoPlay();
        }

        // Touch Events
        viewport.addEventListener('touchstart', touchStart, { passive: true });
        viewport.addEventListener('touchmove', touchMove, { passive: true });
        viewport.addEventListener('touchend', touchEnd);

        // Mouse Drag Events
        viewport.addEventListener('mousedown', touchStart);
        window.addEventListener('mousemove', touchMove);
        window.addEventListener('mouseup', touchEnd);

        // Window resize reposition
        window.addEventListener('resize', () => {
            updateSliderPosition(false);
        });

        // Start initial auto-play
        startAutoPlay();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initBlogSlider);
    } else {
        initBlogSlider();
    }
})();
</script>
@endif
