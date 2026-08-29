{{-- ============================================================
     UNLOCK RENTALS — HOMEPAGE BLOG & GUIDES SECTION
     ============================================================ --}}
<section class="py-20 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-200/60 dark:border-slate-800" id="rental-guides">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Section Header --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 border border-blue-200/60 dark:border-blue-800/60 mb-3">
                    <i class="ph-bold ph-newspaper text-sm"></i>
                    Knowledge Hub
                </div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white font-['Playfair_Display',serif]">
                    Latest Rental Guides & Insights
                </h2>
                <p class="text-slate-600 dark:text-slate-400 text-sm sm:text-base mt-2 max-w-xl">
                    Smart advice on tenant rights, stamp duty, property valuation, and commercial space leasing.
                </p>
            </div>
            <div>
                <a href="{{ url('/blog') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-white text-sm font-bold shadow-sm hover:border-blue-600 hover:text-blue-600 transition-all" title="View All Articles">
                    <span>View All Articles</span>
                    <i class="ph-bold ph-arrow-right text-xs"></i>
                </a>
            </div>
        </div>

        @php
            $featuredArticles = [
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
            ];
        @endphp

        {{-- Cards Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($featuredArticles as $art)
            <article class="bg-white dark:bg-slate-900 rounded-3xl overflow-hidden border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col group">
                <a href="{{ url('/blog/' . $art['slug']) }}" class="block relative h-52 overflow-hidden" title="UnlockRentals">
                    <img src="{{ $art['image'] }}" alt="{{ $art['title'] }}" title="{{ $art['title'] }}"
                         onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=800&q=80';"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <span class="absolute top-3.5 left-3.5 px-3 py-1 rounded-lg text-xs font-bold bg-slate-950/80 backdrop-blur-md text-white shadow-sm">
                        {{ $art['category'] }}
                    </span>
                </a>
                <div class="p-6 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mb-2.5">
                            <i class="ph-bold ph-clock"></i>
                            <span>{{ $art['read_time'] }}</span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors mb-2.5 line-clamp-2">
                            <a href="{{ url('/blog/' . $art['slug']) }}" title="UnlockRentals">
                                {{ $art['title'] }}
                            </a>
                        </h3>
                        <p class="text-slate-600 dark:text-slate-300 text-sm leading-relaxed line-clamp-3 mb-6">
                            {{ $art['excerpt'] }}
                        </p>
                    </div>
                    <div class="flex items-center justify-between pt-4 border-t border-slate-100 dark:border-slate-800">
                        <div class="flex items-center gap-2.5">
                            <img src="{{ $art['author_avatar'] }}" alt="{{ $art['author'] }}" title="{{ $art['author'] }}"
                                 onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($art['author']) }}&background=2563EB&color=fff&rounded=true&bold=true';"
                                 class="w-8 h-8 rounded-full object-cover">
                            <span class="text-xs font-bold text-slate-900 dark:text-white">{{ $art['author'] }}</span>
                        </div>
                        <a href="{{ url('/blog/' . $art['slug']) }}" class="text-xs font-bold text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-1" title="Read Guide">
                            Read Guide <i class="ph-bold ph-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>
            </article>
            @endforeach
        </div>

    </div>
</section>
