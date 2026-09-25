{{-- Home Services & Local Professionals Showcase Section --}}
<section class="py-16 sm:py-20 bg-slate-50/70 dark:bg-slate-900/60 border-y border-slate-200/60 dark:border-slate-800/80" id="local-professionals-section">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Section Header --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
            <div>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-xs font-bold uppercase tracking-wider mb-2.5 border border-blue-200/50 dark:border-blue-800/50">
                    <i class="ph-bold ph-toolbox text-blue-600"></i>
                    <span>Home & Office Services</span>
                </div>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 dark:text-white tracking-tight">
                    Find Local Professionals Near You
                </h2>
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1 max-w-2xl">
                    Find trusted local professionals for your home, office and property needs. Direct Call and WhatsApp, zero middleman brokerage.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('services.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md shadow-blue-600/20 transition-all active:scale-95 whitespace-nowrap">
                    <span>Find a Professional</span>
                    <i class="ph ph-arrow-right text-xs"></i>
                </a>
            </div>
        </div>

        {{-- Categories Grid (12 Core Categories) --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3.5 mb-12">
            @php
                $homepageCategories = [
                    ['name' => 'Electrician', 'slug' => 'electrician', 'icon' => 'ph-lightning', 'color' => 'from-amber-500 to-yellow-400', 'desc' => 'Wiring, fans, inverter & repairs'],
                    ['name' => 'Plumber', 'slug' => 'plumber', 'icon' => 'ph-wrench', 'color' => 'from-blue-500 to-cyan-400', 'desc' => 'Taps, pipes, leakage & fittings'],
                    ['name' => 'Carpenter', 'slug' => 'carpenter', 'icon' => 'ph-ruler', 'color' => 'from-orange-500 to-amber-400', 'desc' => 'Furniture, doors, locks & wood'],
                    ['name' => 'Painter', 'slug' => 'painter', 'icon' => 'ph-paint-brush', 'color' => 'from-rose-500 to-pink-400', 'desc' => 'Interior, exterior, texture & putty'],
                    ['name' => 'CCTV Professional', 'slug' => 'cctv-professional', 'icon' => 'ph-video-camera', 'color' => 'from-indigo-500 to-purple-400', 'desc' => 'Camera installation & DVR setup'],
                    ['name' => 'IT Professional', 'slug' => 'it-professional', 'icon' => 'ph-laptop', 'color' => 'from-emerald-500 to-teal-400', 'desc' => 'WiFi, PC repair & networking'],
                    ['name' => 'Labour', 'slug' => 'labour', 'icon' => 'ph-hammer', 'color' => 'from-stone-600 to-zinc-500', 'desc' => 'Daily wage, shifting & helper'],
                    ['name' => 'Mason', 'slug' => 'mason', 'icon' => 'ph-wall', 'color' => 'from-amber-700 to-yellow-600', 'desc' => 'Tile, brickwork & plastering'],
                    ['name' => 'Mechanic', 'slug' => 'mechanic', 'icon' => 'ph-gear', 'color' => 'from-blue-600 to-indigo-500', 'desc' => 'Two-wheeler & home machine repair'],
                    ['name' => 'Driver', 'slug' => 'driver', 'icon' => 'ph-steering-wheel', 'color' => 'from-sky-500 to-blue-400', 'desc' => 'Personal & commercial drivers'],
                    ['name' => 'Security Guard', 'slug' => 'security-guard', 'icon' => 'ph-shield-check', 'color' => 'from-slate-700 to-zinc-600', 'desc' => 'Residential & event security'],
                    ['name' => 'Laundry', 'slug' => 'laundry', 'icon' => 'ph-t-shirt', 'color' => 'from-violet-500 to-purple-400', 'desc' => 'Dry clean, iron & wash services'],
                ];
            @endphp

            @foreach($homepageCategories as $hCat)
                <a href="{{ route('services.category', $hCat['slug']) }}" class="group bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200/80 dark:border-slate-700 hover:border-blue-500 dark:hover:border-blue-500 hover:shadow-lg hover:shadow-blue-500/5 transition-all text-center flex flex-col items-center justify-between min-h-[145px]">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-tr {{ $hCat['color'] }} text-white flex items-center justify-center text-2xl shadow-sm group-hover:scale-110 transition-transform duration-200">
                        <i class="ph-bold {{ $hCat['icon'] }}"></i>
                    </div>

                    <div class="mt-2">
                        <h4 class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors line-clamp-1">
                            {{ $hCat['name'] }}
                        </h4>
                        <p class="text-[10px] text-slate-400 line-clamp-1 mt-0.5">
                            {{ $hCat['desc'] }}
                        </p>
                    </div>

                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-blue-600 dark:text-blue-400 opacity-0 group-hover:opacity-100 transition-opacity mt-1">
                        <span>Explore</span>
                        <i class="ph ph-arrow-right text-[10px]"></i>
                    </span>
                </a>
            @endforeach
        </div>

        {{-- Professional CTA Banner --}}
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-blue-700 via-indigo-700 to-slate-900 text-white p-6 sm:p-10 shadow-xl shadow-blue-900/10">
            <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 max-w-5xl mx-auto">
                <div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 text-[11px] font-black uppercase tracking-wider mb-2">
                        <i class="ph-bold ph-seal-check"></i> 100% Free Listing
                    </span>
                    <h3 class="text-xl sm:text-2xl font-black text-white">
                        Are You a Professional or Service Provider?
                    </h3>
                    <p class="text-xs sm:text-sm text-blue-100/90 mt-1 max-w-xl">
                        List your services and connect with local property owners, tenants and businesses looking for verified experts in your city.
                    </p>
                </div>

                <div class="flex-shrink-0">
                    <a href="{{ route('services.register') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-2xl bg-white text-blue-700 hover:bg-blue-50 font-black text-xs sm:text-sm shadow-lg shadow-black/10 transition-all hover:scale-105 active:scale-95 whitespace-nowrap" style="color: #1d4ed8 !important; -webkit-text-fill-color: #1d4ed8 !important; background-color: #ffffff !important;">
                        <i class="ph-bold ph-identification-card text-base" style="color: #1d4ed8 !important; -webkit-text-fill-color: #1d4ed8 !important;"></i>
                        <span style="color: #1d4ed8 !important; -webkit-text-fill-color: #1d4ed8 !important;">List Your Service FREE</span>
                    </a>
                </div>
            </div>

            <div class="absolute -right-12 -bottom-12 w-64 h-64 rounded-full bg-white/5 blur-xl pointer-events-none"></div>
        </div>

    </div>
</section>
