{{-- Search Filters Sidebar Component --}}
{{-- Usage: @include('components.search-filters', ['categories' => $categories, 'locations' => $locations]) --}}

<aside class="bg-white border border-stone-100 rounded-sm p-8 sticky top-28 shadow-sm shadow-stone-100/50" id="search-filters">
    <h3 class="text-xl font-serif font-light text-zinc-900 mb-8 flex items-center gap-3">
        <i class="ph ph-funnel text-[#2563EB]"></i>
        Refine Search
    </h3>

    <form method="GET" action="{{ route('properties.index') }}" id="filter-form" data-ur-loader-msg="Filtering property database...">
        {{-- Search --}}
        <div class="mb-6">
            <label for=" filter-search" class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-4 ml-1">Search</label>
            <div class="relative">
                <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-zinc-500"></i>
                <input type="text" name="search" id="filter-search" value="{{ request('search') }}"
                       placeholder="Search properties..."
                       class="w-full pl-10 pr-4 py-2.5 bg-stone-50 border border-stone-200/50 rounded-sm text-sm text-zinc-900 placeholder-gray-500 focus:outline-none focus:border-[#2563EB]/50 focus:ring-1 focus:ring-[#2563EB]/30 transition-all">
            </div>
        </div>

        {{-- Property Type --}}
        <div class="mb-6">
            <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-4 ml-1">Property Type</label>
            <div class="space-y-2">
                <label class="flex items-center gap-3 px-3 py-2 rounded-sm hover:bg-stone-50 cursor-pointer transition-all" for="filter-type-all">
                    <input type="radio" name="type" value="" id="filter-type-all" {{ !request('type') ? 'checked' : '' }}
                           class="w-4 h-4 text-[#2563EB] bg-stone-50 border-stone-200 focus:ring-[#2563EB]/30">
                    <span class="text-sm text-zinc-700">All Types</span>
                </label>
                <label class="flex items-center gap-3 px-3 py-2 rounded-sm hover:bg-stone-50 cursor-pointer transition-all" for="filter-type-house">
                    <input type="radio" name="type" value="house" id="filter-type-house" {{ request('type') === 'house' ? 'checked' : '' }}
                           class="w-4 h-4 text-[#2563EB] bg-stone-50 border-stone-200 focus:ring-[#2563EB]/30">
                    <i class="ph ph-house text-[#2563EB]"></i>
                    <span class="text-sm text-zinc-700">House</span>
                </label>
                <label class="flex items-center gap-3 px-3 py-2 rounded-sm hover:bg-stone-50 cursor-pointer transition-all" for="filter-type-shop">
                    <input type="radio" name="type" value="shop" id="filter-type-shop" {{ request('type') === 'shop' ? 'checked' : '' }}
                           class="w-4 h-4 text-[#2563EB] bg-stone-50 border-stone-200 focus:ring-[#2563EB]/30">
                    <i class="ph ph-storefront text-indigo-500"></i>
                    <span class="text-sm text-zinc-700">Shop</span>
                </label>
                <label class="flex items-center gap-3 px-3 py-2 rounded-sm hover:bg-stone-50 cursor-pointer transition-all" for="filter-type-pg">
                    <input type="radio" name="type" value="pg-hostel" id="filter-type-pg" {{ request('type') === 'pg-hostel' ? 'checked' : '' }}
                           class="w-4 h-4 text-[#2563EB] bg-stone-50 border-stone-200 focus:ring-[#2563EB]/30">
                    <i class="ph ph-buildings text-amber-500"></i>
                    <span class="text-sm text-zinc-700">PG / Hostel</span>
                </label>
                <label class="flex items-center gap-3 px-3 py-2 rounded-sm hover:bg-stone-50 cursor-pointer transition-all" for="filter-type-hotel">
                    <input type="radio" name="type" value="hotel" id="filter-type-hotel" {{ request('type') === 'hotel' ? 'checked' : '' }}
                           class="w-4 h-4 text-[#2563EB] bg-stone-50 border-stone-200 focus:ring-[#2563EB]/30">
                    <i class="ph ph-bed text-rose-500"></i>
                    <span class="text-sm text-zinc-700">Hotel</span>
                </label>
            </div>
        </div>

        {{-- Category --}}
        <div class="mb-6">
            <label for="filter-category" class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-4 ml-1">Category</label>
            <select name="category" id="filter-category"
                    class="w-full px-4 py-2.5 bg-stone-50 border border-stone-200/50 rounded-sm text-sm text-zinc-900 focus:outline-none focus:border-[#2563EB]/50 focus:ring-1 focus:ring-[#2563EB]/30 transition-all">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Price Range --}}
        <div class="mb-6">
            <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-4 ml-1">Price Range</label>
            <div class="flex gap-2">
                <input type="number" name="min_price" value="{{ request('min_price') }}"
                       placeholder="Min"
                       class="w-1/2 px-3 py-2.5 bg-stone-50 border border-stone-200/50 rounded-sm text-sm text-zinc-900 placeholder-gray-500 focus:outline-none focus:border-[#2563EB]/50 transition-all"
                       id="filter-min-price">
                <input type="number" name="max_price" value="{{ request('max_price') }}"
                       placeholder="Max"
                       class="w-1/2 px-3 py-2.5 bg-stone-50 border border-stone-200/50 rounded-sm text-sm text-zinc-900 placeholder-gray-500 focus:outline-none focus:border-[#2563EB]/50 transition-all"
                       id="filter-max-price">
            </div>
        </div>

        {{-- State --}}
        <div class="mb-6">
            <label for="filter-state" class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-4 ml-1">State</label>
            <select name="state" id="filter-state"
                    class="w-full px-4 py-2.5 bg-stone-50 border border-stone-200/50 rounded-sm text-sm text-zinc-900 focus:outline-none focus:border-[#2563EB]/50 focus:ring-1 focus:ring-[#2563EB]/30 transition-all">
                <option value="">All States</option>
            </select>
        </div>

        {{-- City / District --}}
        <div class="mb-6">
            <label for="filter-city" class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-4 ml-1">City / District</label>
            <select name="district" id="filter-city"
                    class="w-full px-4 py-2.5 bg-stone-50 border border-stone-200/50 rounded-sm text-sm text-zinc-900 focus:outline-none focus:border-[#2563EB]/50 focus:ring-1 focus:ring-[#2563EB]/30 transition-all">
                <option value="">All Cities</option>
            </select>
        </div>

        {{-- Locality --}}
        <div class="mb-6">
            <label for="filter-locality" class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-4 ml-1">Locality</label>
            <div id="locality-select-wrap">
                <select name="locality" id="filter-locality-select"
                        class="w-full px-4 py-2.5 bg-stone-50 border border-stone-200/50 rounded-sm text-sm text-zinc-900 focus:outline-none focus:border-[#2563EB]/50 focus:ring-1 focus:ring-[#2563EB]/30 transition-all">
                    <option value="">All Localities</option>
                </select>
            </div>
            <div id="locality-text-wrap" style="display: none;">
                <div class="relative">
                    <i class="ph ph-map-pin absolute left-3 top-1/2 -translate-y-1/2 text-zinc-500"></i>
                    <input type="text" name="locality" id="filter-locality-text" value="{{ request('locality') }}"
                           placeholder="e.g. Andheri West"
                           class="w-full pl-10 pr-4 py-2.5 bg-stone-50 border border-stone-200/50 rounded-sm text-sm text-zinc-900 placeholder-gray-500 focus:outline-none focus:border-[#2563EB]/50 focus:ring-1 focus:ring-[#2563EB]/30 transition-all">
                </div>
            </div>
        </div>

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

        {{-- Bedrooms --}}
        <div class="mb-6">
            <label for="filter-bedrooms" class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-4 ml-1">Min Bedrooms</label>
            <select name="bedrooms" id="filter-bedrooms"
                    class="w-full px-4 py-2.5 bg-stone-50 border border-stone-200/50 rounded-sm text-sm text-zinc-900 focus:outline-none focus:border-[#2563EB]/50 focus:ring-1 focus:ring-[#2563EB]/30 transition-all">
                <option value="">Any</option>
                @for($i = 1; $i <= 6; $i++)
                    <option value="{{ $i }}" {{ request('bedrooms') == $i ? 'selected' : '' }}>{{ $i }}+</option>
                @endfor
            </select>
        </div>

        {{-- Furnishing --}}
        <div class="mb-6">
            <label for="filter-furnishing" class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-4 ml-1">Furnishing</label>
            <select name="furnishing" id="filter-furnishing"
                    class="w-full px-4 py-2.5 bg-stone-50 border border-stone-200/50 rounded-sm text-sm text-zinc-900 focus:outline-none focus:border-[#2563EB]/50 focus:ring-1 focus:ring-[#2563EB]/30 transition-all">
                <option value="">Any</option>
                <option value="unfurnished" {{ request('furnishing') === 'unfurnished' ? 'selected' : '' }}>Unfurnished</option>
                <option value="semi-furnished" {{ request('furnishing') === 'semi-furnished' ? 'selected' : '' }}>Semi-Furnished</option>
                <option value="fully-furnished" {{ request('furnishing') === 'fully-furnished' ? 'selected' : '' }}>Fully Furnished</option>
            </select>
        </div>

        {{-- Sort --}}
        <div class="mb-6">
            <label for="filter-sort" class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-4 ml-1">Sort By</label>
            <select name="sort" id="filter-sort"
                    class="w-full px-4 py-2.5 bg-stone-50 border border-stone-200/50 rounded-sm text-sm text-zinc-900 focus:outline-none focus:border-[#2563EB]/50 focus:ring-1 focus:ring-[#2563EB]/30 transition-all">
                <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Newest First</option>
                <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest First</option>
            </select>
        </div>

        {{-- Buttons --}}
        <div class="flex flex-col gap-3 mt-8">
            <button type="submit" class="w-full px-6 py-4 bg-[#2563EB] hover:bg-[#1D4ED8] text-white text-xs font-bold tracking-[0.15em] uppercase rounded-sm shadow-sm transition-all" id="filter-apply">
                Apply Filters
            </button>
            <a href="{{ route('properties.index') }}" class="w-full text-center px-6 py-4 bg-transparent border border-stone-200 text-zinc-500 text-xs font-bold tracking-[0.15em] uppercase rounded-sm hover:border-[#2563EB] hover:text-[#2563EB] transition-all" id="filter-reset">
                Reset
            </a>
        </div>
    </form>
</aside>
