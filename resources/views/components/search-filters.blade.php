<aside class="bg-white/60 backdrop-blur-3xl border border-white/40 rounded-[2rem] p-8 sticky top-28 shadow-[0_32px_64px_-16px_rgba(0,0,0,0.08)] ring-1 ring-black/5" id="search-filters">
    <div class="flex items-center justify-between mb-8">
        <h3 class="text-xl font-bold tracking-tight text-zinc-900 flex items-center gap-3">
            <i class="ph-fill ph-funnel text-[#2563EB]"></i>
            Refine Search
        </h3>
        <span class="text-[10px] font-bold text-[#2563EB] bg-[#2563EB]/10 px-2 py-1 rounded-full uppercase tracking-wider">Filtered</span>
    </div>

    <form method="GET" action="{{ route('properties.index') }}" id="filter-form" class="space-y-8">
        {{-- Search --}}
        <div>
            <label for="filter-search" class="block text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-3 ml-1">Keyword Search</label>
            <div class="relative group">
                <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-zinc-400 group-focus-within:text-[#2563EB] transition-colors"></i>
                <input type="text" name="search" id="filter-search" value="{{ request('search') }}"
                       placeholder="e.g. Luxury Villa, Shop..."
                       class="w-full pl-11 pr-4 py-3 bg-stone-50 border border-stone-200/60 rounded-xl text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none focus:border-[#2563EB] focus:ring-4 focus:ring-[#2563EB]/10 transition-all">
            </div>
        </div>

        {{-- Property Type --}}
        <div>
            <label class="block text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-4 ml-1">Intent & Type</label>
            <div class="grid grid-cols-2 gap-2">
                @php
                    $types = [
                        '' => ['label' => 'All', 'icon' => 'ph-circles-four'],
                        'house' => ['label' => 'House', 'icon' => 'ph-house-line'],
                        'shop' => ['label' => 'Shop', 'icon' => 'ph-storefront'],
                        'pg-hostel' => ['label' => 'PG/Hostel', 'icon' => 'ph-buildings'],
                        'hotel' => ['label' => 'Hotel', 'icon' => 'ph-bed']
                    ];
                @endphp
                @foreach($types as $val => $info)
                <label class="relative flex items-center justify-center p-3 rounded-xl border border-stone-200/60 bg-stone-50 cursor-pointer transition-all hover:border-[#2563EB]/30 group {{ request('type') == $val ? 'border-[#2563EB] bg-[#2563EB]/5 ring-1 ring-[#2563EB]' : '' }}" for="filter-type-{{ $val ?: 'all' }}">
                    <input type="radio" name="type" value="{{ $val }}" id="filter-type-{{ $val ?: 'all' }}" {{ request('type') == $val ? 'checked' : '' }} class="sr-only">
                    <div class="flex flex-col items-center gap-1.5">
                        <i class="ph {{ $info['icon'] }} text-lg {{ request('type') == $val ? 'text-[#2563EB]' : 'text-zinc-400 group-hover:text-zinc-600' }}"></i>
                        <span class="text-[11px] font-bold {{ request('type') == $val ? 'text-[#2563EB]' : 'text-zinc-500' }}">{{ $info['label'] }}</span>
                    </div>
                </label>
                @endforeach
            </div>
        </div>

        {{-- Location Cascading --}}
        <div class="space-y-6">
            <div>
                <label for="filter-state" class="block text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-3 ml-1">Region</label>
                <div class="relative">
                    <select name="state" id="filter-state"
                            class="w-full px-4 py-3 bg-stone-50 border border-stone-200/60 rounded-xl text-sm text-zinc-900 focus:outline-none focus:border-[#2563EB] focus:ring-4 focus:ring-[#2563EB]/10 transition-all appearance-none">
                        <option value="">All States</option>
                    </select>
                    <i class="ph ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-zinc-400 pointer-events-none"></i>
                </div>
            </div>

            <div>
                <label for="filter-city" class="block text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-3 ml-1">City / District</label>
                <div class="relative">
                    <select name="district" id="filter-city"
                            class="w-full px-4 py-3 bg-stone-50 border border-stone-200/60 rounded-xl text-sm text-zinc-900 focus:outline-none focus:border-[#2563EB] focus:ring-4 focus:ring-[#2563EB]/10 transition-all appearance-none">
                        <option value="">All Cities</option>
                    </select>
                    <i class="ph ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-zinc-400 pointer-events-none"></i>
                </div>
            </div>

            <div>
                <label for="filter-locality" class="block text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-3 ml-1">Locality</label>
                <div id="locality-select-wrap">
                    <div class="relative">
                        <select name="locality" id="filter-locality-select"
                                class="w-full px-4 py-3 bg-stone-50 border border-stone-200/60 rounded-xl text-sm text-zinc-900 focus:outline-none focus:border-[#2563EB] focus:ring-4 focus:ring-[#2563EB]/10 transition-all appearance-none">
                            <option value="">All Localities</option>
                        </select>
                        <i class="ph ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-zinc-400 pointer-events-none"></i>
                    </div>
                </div>
                <div id="locality-text-wrap" style="display: none;">
                    <div class="relative group">
                        <i class="ph ph-map-pin absolute left-4 top-1/2 -translate-y-1/2 text-zinc-400 group-focus-within:text-[#2563EB] transition-colors"></i>
                        <input type="text" name="locality" id="filter-locality-text" value="{{ request('locality') }}"
                               placeholder="e.g. Andheri West"
                               class="w-full pl-11 pr-4 py-3 bg-stone-50 border border-stone-200/60 rounded-xl text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none focus:border-[#2563EB] focus:ring-4 focus:ring-[#2563EB]/10 transition-all">
                    </div>
                </div>
            </div>
        </div>

        {{-- Price Range --}}
        <div>
            <label class="block text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-4 ml-1">Budget Range</label>
            <div class="flex items-center gap-2">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-zinc-400 text-xs font-bold">₹</span>
                    </div>
                    <input type="number" name="min_price" value="{{ request('min_price') }}"
                           placeholder="Min"
                           class="w-full pl-7 pr-3 py-3 bg-stone-50 border border-stone-200/60 rounded-xl text-sm text-zinc-900 focus:outline-none focus:border-[#2563EB] focus:ring-4 focus:ring-[#2563EB]/5 transition-all"
                           id="filter-min-price">
                </div>
                <div class="w-2 h-px bg-stone-300"></div>
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-zinc-400 text-xs font-bold">₹</span>
                    </div>
                    <input type="number" name="max_price" value="{{ request('max_price') }}"
                           placeholder="Max"
                           class="w-full pl-7 pr-3 py-3 bg-stone-50 border border-stone-200/60 rounded-xl text-sm text-zinc-900 focus:outline-none focus:border-[#2563EB] focus:ring-4 focus:ring-[#2563EB]/5 transition-all"
                           id="filter-max-price">
                </div>
            </div>
        </div>

        {{-- Additional Filters Row --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="filter-bedrooms" class="block text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-3 ml-1">Beds</label>
                <div class="relative">
                    <select name="bedrooms" id="filter-bedrooms"
                            class="w-full px-4 py-3 bg-stone-50 border border-stone-200/60 rounded-xl text-sm text-zinc-900 focus:outline-none focus:border-[#2563EB] transition-all appearance-none">
                        <option value="">Any</option>
                        @for($i = 1; $i <= 6; $i++)
                            <option value="{{ $i }}" {{ request('bedrooms') == $i ? 'selected' : '' }}>{{ $i }}+</option>
                        @endfor
                    </select>
                    <i class="ph ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-zinc-400 pointer-events-none"></i>
                </div>
            </div>
            <div>
                <label for="filter-sort" class="block text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-3 ml-1">Sort</label>
                <div class="relative">
                    <select name="sort" id="filter-sort"
                            class="w-full px-4 py-3 bg-stone-50 border border-stone-200/60 rounded-xl text-sm text-zinc-900 focus:outline-none focus:border-[#2563EB] transition-all appearance-none">
                        <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Newest</option>
                        <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Price ↑</option>
                        <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Price ↓</option>
                    </select>
                    <i class="ph ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-zinc-400 pointer-events-none"></i>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="pt-4 flex flex-col gap-3">
            <button type="submit" class="w-full py-4 bg-[#2563EB] hover:bg-[#1D4ED8] text-white text-[11px] font-bold tracking-widest uppercase rounded-xl shadow-lg shadow-[#2563EB]/25 transition-all active:scale-[0.98]" id="filter-apply">
                Apply Filters
            </button>
            <a href="{{ route('properties.index') }}" class="w-full text-center py-4 bg-transparent border border-stone-200 text-zinc-500 text-[11px] font-bold tracking-widest uppercase rounded-xl hover:bg-stone-50 transition-all" id="filter-reset">
                Reset All
            </a>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
        });
    </script>
</aside>
