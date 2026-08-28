<aside class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-sm sticky top-28" id="search-filters">
    
    {{-- Header --}}
    <div class="flex items-center justify-between pb-4 mb-6 border-b border-slate-100 dark:border-slate-800">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-950/50 text-blue-600 flex items-center justify-center">
                <i class="ph-bold ph-faders-horizontal text-base"></i>
            </div>
            <h3 class="text-base font-bold text-slate-900 dark:text-white">Refine Search</h3>
        </div>
        @if(request()->anyFilled(['search', 'type', 'state', 'district', 'locality', 'min_price', 'max_price', 'bedrooms', 'sort']))
            <a href="{{ route('properties.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 hover:underline" title="Clear Filters">
                Clear all
            </a>
        @else
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Filters</span>
        @endif
    </div>

    <form method="GET" action="{{ route('properties.index') }}" id="filter-form" class="space-y-6">
        
        {{-- Keyword Search --}}
        <div>
            <label for="filter-search" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Keyword Search</label>
            <div class="relative">
                <i class="ph-bold ph-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text" name="search" id="filter-search" value="{{ request('search') }}"
                       placeholder="e.g. 2 BHK, Villa, Studio..."
                       class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-medium text-slate-900 dark:text-white placeholder-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
            </div>
        </div>

        {{-- Property Type Selector --}}
        <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2.5">Property Type</label>
            <div class="grid grid-cols-2 gap-2">
                @php
                    $types = [
                        '' => ['label' => 'All Types', 'icon' => 'ph-bold ph-squares-four'],
                        'house' => ['label' => 'House/Flat', 'icon' => 'ph-bold ph-house-line'],
                        'shop' => ['label' => 'Shop/Office', 'icon' => 'ph-bold ph-storefront'],
                        'pg-hostel' => ['label' => 'PG / Hostel', 'icon' => 'ph-bold ph-buildings'],
                    ];
                @endphp
                @foreach($types as $val => $info)
                <label class="relative flex items-center gap-2 px-3 py-2.5 rounded-xl border cursor-pointer transition-all {{ request('type') == $val ? 'border-blue-600 bg-blue-50/70 dark:bg-blue-950/40 text-blue-700 dark:text-blue-400 font-bold ring-1 ring-blue-600' : 'border-slate-200 dark:border-slate-700/80 bg-slate-50/50 dark:bg-slate-800/40 hover:border-slate-300 text-slate-600 dark:text-slate-400 font-medium' }}" for="filter-type-{{ $val ?: 'all' }}">
                    <input type="radio" name="type" value="{{ $val }}" id="filter-type-{{ $val ?: 'all' }}" {{ request('type') == $val ? 'checked' : '' }} class="sr-only">
                    <i class="{{ $info['icon'] }} text-base {{ request('type') == $val ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span class="text-xs">{{ $info['label'] }}</span>
                </label>
                @endforeach
            </div>
        </div>

        {{-- Location Cascading Selects --}}
        <div class="space-y-3.5 pt-1 border-t border-slate-100 dark:border-slate-800">
            <div>
                <label for="filter-state" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">State</label>
                <div class="relative">
                    <select name="state" id="filter-state"
                            class="w-full pl-3.5 pr-8 py-2.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-medium text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all appearance-none cursor-pointer">
                        <option value="">All States</option>
                    </select>
                    <i class="ph-bold ph-caret-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                </div>
            </div>

            <div>
                <label for="filter-city" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">City / District</label>
                <div class="relative">
                    <select name="district" id="filter-city"
                            class="w-full pl-3.5 pr-8 py-2.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-medium text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all appearance-none cursor-pointer">
                        <option value="">All Cities</option>
                    </select>
                    <i class="ph-bold ph-caret-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                </div>
            </div>

            <div>
                <label for="filter-locality-select" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Locality</label>
                <div id="locality-select-wrap">
                    <div class="relative">
                        <select name="locality" id="filter-locality-select"
                                class="w-full pl-3.5 pr-8 py-2.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-medium text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all appearance-none cursor-pointer">
                            <option value="">All Localities</option>
                        </select>
                        <i class="ph-bold ph-caret-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                    </div>
                </div>
                <div id="locality-text-wrap" style="display: none;">
                    <div class="relative">
                        <i class="ph-bold ph-map-pin absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" name="locality" id="filter-locality-text" value="{{ request('locality') }}"
                               placeholder="e.g. Sector 57, Indiranagar"
                               class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-medium text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                    </div>
                </div>
            </div>
        </div>

        {{-- Price / Budget Range --}}
        <div class="pt-1 border-t border-slate-100 dark:border-slate-800">
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Monthly Budget (₹)</label>
            <div class="flex items-center gap-2">
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 font-bold text-xs">₹</span>
                    <input type="number" name="min_price" value="{{ request('min_price') }}"
                           placeholder="Min"
                           class="w-full pl-7 pr-2.5 py-2.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all"
                           id="filter-min-price">
                </div>
                <span class="text-slate-300 font-bold text-xs">-</span>
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 font-bold text-xs">₹</span>
                    <input type="number" name="max_price" value="{{ request('max_price') }}"
                           placeholder="Max"
                           class="w-full pl-7 pr-2.5 py-2.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all"
                           id="filter-max-price">
                </div>
            </div>
        </div>

        {{-- Bedrooms & Sort Row --}}
        <div class="grid grid-cols-2 gap-3 pt-1 border-t border-slate-100 dark:border-slate-800">
            <div>
                <label for="filter-bedrooms" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Beds</label>
                <div class="relative">
                    <select name="bedrooms" id="filter-bedrooms"
                            class="w-full pl-3 pr-7 py-2.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 transition-all appearance-none cursor-pointer">
                        <option value="">Any BHK</option>
                        @for($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" {{ request('bedrooms') == $i ? 'selected' : '' }}>{{ $i }} BHK</option>
                        @endfor
                    </select>
                    <i class="ph-bold ph-caret-down absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                </div>
            </div>
            <div>
                <label for="filter-sort" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Sort By</label>
                <div class="relative">
                    <select name="sort" id="filter-sort"
                            class="w-full pl-3 pr-7 py-2.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 transition-all appearance-none cursor-pointer">
                        <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Newest</option>
                        <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Price: Low</option>
                        <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Price: High</option>
                    </select>
                    <i class="ph-bold ph-caret-down absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="pt-2 flex flex-col gap-2.5">
            <button type="submit" class="w-full py-3 bg-[#2563EB] hover:bg-blue-700 text-white text-xs font-extrabold uppercase tracking-wider rounded-xl shadow-sm shadow-blue-500/25 hover:shadow-md transition-all active:scale-[0.98] flex items-center justify-center gap-2" id="filter-apply">
                <i class="ph-bold ph-magnifying-glass"></i> Apply Filters
            </button>
            <a href="{{ route('properties.index') }}" class="w-full text-center py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold rounded-xl transition-all" id="filter-reset" title="Reset Filters">
                Reset
            </a>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof window.initLocationCascading === 'function') {
                window.initLocationCascading({
                    stateId: 'filter-state',
                    cityId: 'filter-city',
                    localityId: 'filter-locality-select',
                    localityTextWrapId: 'locality-text-wrap',
                    localitySelectWrapId: 'locality-select-wrap',
                    selectedState: "{{ request('state') }}",
                    selectedCity: "{{ request('district') }}",
                    selectedLocality: "{{ request('locality') }}"
                });
            }
        });
    </script>
</aside>
