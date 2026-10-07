@extends('layouts.app')

@section('title', 'Rent Flats, Rooms & Houses — Zero Brokerage Rental Portal | UnlockRentals')
@section('meta_description', 'Unlock rent savings with UnlockRentals. Explore verified rental flats, rooms, PGs, houses, and commercial properties for rent directly from owners with zero brokerage across India.')
@section('meta_keywords', 'unlock, unlock rent, unlock rental, rental, rent, unlock rentals, rent flats, rent rooms, rooms for rent, flats for rent, zero brokerage rental, direct owner rent, unlockrentals')


@push('head')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "BreadcrumbList",
    "itemListElement": [
        {
            "@@type": "ListItem",
            "position": 1,
            "name": "Home",
            "item": "{{ route('home') }}"
        },
        {
            "@@type": "ListItem",
            "position": 2,
            "name": "Rental Properties",
            "item": "{{ route('properties.index') }}"
        }
    ]
}
</script>
@endpush

@section('content')

<section class="min-h-screen pt-20 sm:pt-24 pb-32 sm:pb-24 bg-[#f8fafc] dark:bg-slate-950 relative overflow-hidden" id="properties-browse">
    {{-- Ambient Background Gradients --}}
    <div class="absolute top-0 left-0 w-full h-[500px] bg-gradient-to-b from-blue-500/[0.04] via-indigo-500/[0.02] to-transparent pointer-events-none"></div>
    <div class="absolute -top-32 -left-32 w-96 h-96 bg-blue-500/10 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute top-1/3 right-0 w-80 h-80 bg-indigo-500/10 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        {{-- Centered Top Search Bar --}}
        <div class="mb-6 sm:mb-8 flex justify-center w-full">
            <div class="w-full max-w-2xl">
                <form action="{{ route('properties.index') }}" method="GET" class="relative flex items-center p-1.5 bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 rounded-2xl sm:rounded-full shadow-md hover:shadow-lg transition-all">
                    @if(request('type'))
                        <input type="hidden" name="type" value="{{ request('type') }}">
                    @endif
                    @if(request('purpose'))
                        <input type="hidden" name="purpose" value="{{ request('purpose') }}">
                    @endif
                    
                    {{-- Search Input --}}
                    <div class="relative flex-1 flex items-center">
                        <i class="ph-bold ph-magnifying-glass absolute left-4 text-blue-600 text-lg pointer-events-none"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Search by city, locality, society, BHK, or keyword..."
                               class="w-full pl-11 pr-3 py-2.5 sm:py-3 bg-transparent text-sm sm:text-base font-semibold text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none border-0">
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex items-center gap-1.5 flex-shrink-0 pr-1">
                        @if(request('search'))
                            <a href="{{ route('properties.index', request()->except('search')) }}" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-sm font-bold transition-colors" title="Clear Search">
                                <i class="ph-bold ph-x-circle text-base"></i>
                            </a>
                        @endif
                        <button type="submit"
                                class="px-6 py-2.5 sm:py-3 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-bold text-xs sm:text-sm rounded-xl sm:rounded-full shadow-md shadow-blue-500/25 transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                            <i class="ph-bold ph-magnifying-glass text-sm hidden sm:inline"></i>
                            <span>Search</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Page Header & Breadcrumb --}}
        <div class="mb-5 sm:mb-8 pb-4 sm:pb-6 border-b border-slate-200/80 dark:border-slate-800">
            <div class="max-w-3xl">
                <nav class="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 sm:mb-3">
                    <a href="{{ route('home') }}" class="hover:text-blue-600 transition-colors" title="Home">Home</a>
                    <i class="ph-bold ph-caret-right text-[10px]"></i>
                    <span class="text-slate-900 dark:text-slate-100 font-extrabold">Properties</span>
                    @if(request('type'))
                        <i class="ph-bold ph-caret-right text-[10px]"></i>
                        <span class="text-blue-600 capitalize font-extrabold">{{ request('type') }}</span>
                    @endif
                </nav>
                <h1 class="text-xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white leading-tight">
                    @if(request('type') === 'plot')
                        <span class="text-blue-600">Plots & Land</span> for Sale & Investment
                    @elseif(request('type'))
                        <span class="text-blue-600 capitalize">{{ request('type') }}s</span> for Rent & Buy
                    @elseif(request('search'))
                        Results for <span class="text-blue-600">"{{ request('search') }}"</span>
                    @else
                        Explore <span class="text-blue-600">Rental Properties & Homes</span> in India
                    @endif
                </h1>
                <p class="text-slate-500 dark:text-slate-400 text-xs sm:text-sm font-normal mt-1.5 leading-relaxed">
                    Direct-owner verified rental flats, rooms, PGs, houses, and commercial spaces with zero brokerage. Unlock direct owner contact numbers instantly.
                </p>
            </div>
        </div>

            {{-- Quick Category Pills (Horizontal Scroll on Mobile) --}}
            <div class="mt-4 -mx-4 px-4 sm:mx-0 sm:px-0 overflow-x-auto scrollbar-none flex items-center gap-2 py-1">
                {{-- Quick GPS Near Me Action Pill --}}
                <button type="button" 
                        onclick="window.fetchPropertiesNearMe ? window.fetchPropertiesNearMe() : (window.openLocationModal && window.openLocationModal())" 
                        class="whitespace-nowrap px-3.5 py-1.5 rounded-full text-xs font-bold transition-all flex items-center gap-1.5 flex-shrink-0 cursor-pointer {{ request('near_me') || request('lat') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-500/30 ring-2 ring-emerald-400' : 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-800 hover:bg-emerald-100 dark:hover:bg-emerald-900/40' }}"
                        title="Search properties near my current location">
                    <i class="ph-fill ph-navigation-arrow text-sm {{ request('near_me') ? 'animate-pulse' : '' }}"></i>
                    <span>{{ request('near_me') || request('lat') ? '📍 Near You Active' : '📍 Near Me' }}</span>
                </button>

                @php
                    $pills = [
                        ['label' => 'All Properties', 'url' => route('properties.index'), 'active' => !request('type') && !request('purpose') && !request('near_me')],
                        ['label' => 'Houses & Flats', 'url' => route('properties.index', ['type' => 'house']), 'active' => request('type') === 'house'],
                        ['label' => 'Plots & Land', 'url' => route('properties.index', ['type' => 'plot']), 'active' => request('type') === 'plot'],
                        ['label' => 'Shops & Offices', 'url' => route('properties.index', ['type' => 'shop']), 'active' => request('type') === 'shop'],
                        ['label' => 'PG & Hostels', 'url' => route('properties.index', ['type' => 'pg-hostel']), 'active' => request('type') === 'pg-hostel'],
                        ['label' => 'For Rent', 'url' => route('properties.index', ['purpose' => 'rent']), 'active' => request('purpose') === 'rent'],
                        ['label' => 'For Sale', 'url' => route('properties.index', ['purpose' => 'buy']), 'active' => request('purpose') === 'buy'],
                    ];
                @endphp
                @foreach($pills as $pill)
                    <a href="{{ $pill['url'] }}" class="whitespace-nowrap px-3.5 py-1.5 rounded-full text-xs font-bold transition-all flex-shrink-0 {{ $pill['active'] ? 'bg-blue-600 text-white shadow-sm shadow-blue-500/25' : 'bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:border-blue-500/50' }}">
                        {{ $pill['label'] }}
                    </a>
                @endforeach
            </div>

        {{-- Mobile Filter Bar Toggle (Visible on screens < lg) --}}
        @php
            $activeFilterCount = count(array_filter(request()->only(['search', 'type', 'state', 'district', 'locality', 'min_price', 'max_price', 'bedrooms', 'sort', 'media', 'availability', 'near_me'])));
        @endphp
        <div class="lg:hidden mb-5 flex items-center justify-between gap-3 bg-white dark:bg-slate-900 p-3 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs">
            <button type="button" onclick="toggleMobileFilterSheet(true)" class="flex-1 flex items-center justify-center gap-2 py-2 px-3 bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 font-bold text-xs rounded-xl active:scale-95 transition-all cursor-pointer">
                <i class="ph-bold ph-faders-horizontal text-sm"></i>
                <span>Filters & Search</span>
                @if($activeFilterCount > 0)
                    <span class="w-5 h-5 rounded-full bg-blue-600 text-white text-[10px] flex items-center justify-center font-black">{{ $activeFilterCount }}</span>
                @endif
            </button>
            <div class="text-xs text-slate-500 dark:text-slate-400 font-semibold px-2">
                {{ $properties->total() ?? $properties->count() }} Properties
            </div>
        </div>

        {{-- Mobile Filter Slide-up Bottom Sheet Modal --}}
        <div id="mobile-filter-modal-overlay" onclick="toggleMobileFilterSheet(false)" class="fixed inset-0 z-[9998] bg-slate-950/70 backdrop-blur-sm transition-opacity duration-300 pointer-events-none lg:hidden"></div>
        <div id="mobile-filter-modal-sheet" class="fixed inset-x-0 bottom-0 z-[9999] max-h-[88vh] bg-white dark:bg-slate-900 rounded-t-3xl border-t border-slate-200/80 dark:border-slate-800 shadow-2xl flex flex-col transition-transform duration-300 lg:hidden">
            <div class="flex items-center justify-between p-4 border-b border-slate-100 dark:border-slate-800">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-blue-50 dark:bg-blue-950/50 text-blue-600 flex items-center justify-center">
                        <i class="ph-bold ph-faders-horizontal text-sm"></i>
                    </div>
                    <h3 class="text-sm font-extrabold text-slate-900 dark:text-white">Filter & Refine</h3>
                </div>
                <button type="button" onclick="toggleMobileFilterSheet(false)" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 hover:text-slate-900 dark:hover:text-white flex items-center justify-center" aria-label="Close filters">
                    <i class="ph-bold ph-x text-sm"></i>
                </button>
            </div>
            <div class="flex-1 overflow-y-auto p-4">
                @include('components.search-filters', ['categories' => $categories, 'locations' => $locations, 'idPrefix' => 'mobile-'])
            </div>
        </div>

        {{-- Layout: Desktop Sidebar + Grid --}}
        <div class="flex flex-col lg:flex-row gap-8 xl:gap-10 items-start">
            
            {{-- Left Sidebar Filters (Desktop Only) --}}
            <div class="hidden lg:block w-80 flex-shrink-0">
                @include('components.search-filters', ['categories' => $categories, 'locations' => $locations, 'idPrefix' => 'desktop-'])
            </div>

            {{-- Right Property Grid --}}
            <div class="flex-1 w-full min-w-0">
                {{-- Active Near Me Location Banner --}}
                @if(request('near_me') || request('lat') || request('district'))
                    <div class="mb-5 p-3.5 sm:p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/60 flex flex-wrap items-center justify-between gap-3 shadow-xs">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center text-sm shadow-sm flex-shrink-0">
                                <i class="ph-fill ph-map-pin"></i>
                            </div>
                            <div>
                                <span class="block text-xs font-bold text-emerald-900 dark:text-emerald-200">
                                    Showing rentals near {{ request('district') ? request('district') : 'your current location' }}
                                </span>
                                <span class="block text-[11px] text-emerald-700 dark:text-emerald-400 font-medium">
                                    Zero Brokerage • Sorted by nearest available
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="window.openLocationModal && window.openLocationModal()" class="px-3 py-1 bg-white dark:bg-slate-900 border border-emerald-300 dark:border-emerald-700 text-emerald-800 dark:text-emerald-300 rounded-lg text-xs font-bold hover:bg-emerald-100 transition-colors cursor-pointer">
                                Change
                            </button>
                            <a href="{{ route('properties.index') }}" class="text-xs text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 font-medium" title="Clear Location Filter">
                                View All
                            </a>
                        </div>
                    </div>
                @endif

                {{-- Prominent Results Header & Sorting Toolbar --}}
                <div class="mb-5 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-3.5 sm:p-4 shadow-sm">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        {{-- Left: Count & Status --}}
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center text-base shadow-xs flex-shrink-0">
                                <i class="ph-bold ph-squares-four"></i>
                            </div>
                            <div>
                                <h2 class="text-xs sm:text-sm font-extrabold text-slate-900 dark:text-white leading-tight">
                                    <span class="text-blue-600">{{ $properties->total() ?? $properties->count() }}</span>
                                    <span>{{ ($properties->total() ?? $properties->count()) == 1 ? 'Property Available' : 'Properties Available' }}</span>
                                </h2>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">
                                    Zero Brokerage • Direct Owner Listings
                                </p>
                            </div>
                        </div>

                        {{-- Right: Prominent Sort Dropdown --}}
                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <label for="property-grid-sort" class="text-xs font-bold text-slate-600 dark:text-slate-400 whitespace-nowrap flex items-center gap-1.5 flex-shrink-0">
                                <i class="ph-bold ph-arrows-down-up text-blue-600"></i>
                                <span>Sort By:</span>
                            </label>
                            <div class="relative flex-1 sm:w-56">
                                <select id="property-grid-sort"
                                        onchange="updatePropertySort(this.value)"
                                        class="w-full pl-3 pr-8 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-800 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all appearance-none cursor-pointer">
                                    <option value="latest" {{ request('sort') === 'latest' || request('sort') === 'new_to_old' || !request('sort') ? 'selected' : '' }}>⏱️ Newest First</option>
                                    <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>💰 Price: Low to High</option>
                                    <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>💎 Price: High to Low</option>
                                    <option value="unbooked" {{ request('sort') === 'unbooked' ? 'selected' : '' }}>🟢 Available / Unbooked</option>
                                    <option value="old_to_new" {{ request('sort') === 'old_to_new' || request('sort') === 'oldest' ? 'selected' : '' }}>⏳ Oldest First</option>
                                    @if(request('near_me') || request('lat'))
                                        <option value="nearest" {{ request('sort') === 'nearest' || request('sort') === 'distance' ? 'selected' : '' }}>📍 Nearest to Me</option>
                                    @endif
                                </select>
                                <i class="ph-bold ph-caret-down absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Quick 1-Click Sort Pills (Horizontal Scrolling) --}}
                    <div class="mt-3 pt-3 border-t border-slate-100 dark:border-slate-800/80 flex items-center gap-1.5 overflow-x-auto scrollbar-none -mx-1 px-1">
                        <span class="text-[11px] font-bold text-slate-400 dark:text-slate-500 whitespace-nowrap mr-1 flex-shrink-0">
                            Quick Sort:
                        </span>
                        
                        @php
                            $currentSort = request('sort', 'latest');
                            if ($currentSort === 'new_to_old') $currentSort = 'latest';
                            if ($currentSort === 'oldest') $currentSort = 'old_to_new';
                            if ($currentSort === 'distance') $currentSort = 'nearest';

                            $sortPills = [
                                ['id' => 'latest', 'label' => 'Newest', 'icon' => 'ph-clock'],
                                ['id' => 'price_low', 'label' => 'Price: Low to High', 'icon' => 'ph-trend-up'],
                                ['id' => 'price_high', 'label' => 'Price: High to Low', 'icon' => 'ph-trend-down'],
                                ['id' => 'unbooked', 'label' => 'Available First', 'icon' => 'ph-check-circle'],
                            ];
                            if (request('near_me') || request('lat')) {
                                array_unshift($sortPills, ['id' => 'nearest', 'label' => 'Nearest to Me', 'icon' => 'ph-navigation-arrow']);
                            }
                        @endphp

                        @foreach($sortPills as $sp)
                            @php
                                $isActive = ($currentSort === $sp['id']);
                                $pillParams = array_merge(request()->query(), ['sort' => $sp['id']]);
                                unset($pillParams['page']);
                            @endphp
                            <a href="{{ route('properties.index', $pillParams) }}"
                               class="whitespace-nowrap px-3 py-1 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 flex-shrink-0 {{ $isActive ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                                <i class="ph-bold {{ $sp['icon'] }} text-xs"></i>
                                <span>{{ $sp['label'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                @if($properties->count() > 0)
                    {{-- Grid Container --}}
                    <div class="grid grid-cols-2 sm:grid-cols-2 xl:grid-cols-2 2xl:grid-cols-3 gap-3 sm:gap-4 md:gap-6">
                        @foreach($properties as $property)
                            <x-property-card :property="$property" />
                        @endforeach
                    </div>

                    {{-- Pagination Container --}}
                    <div class="mt-10 sm:mt-12 pt-6 border-t border-slate-200/80 dark:border-slate-800">
                        {{ $properties->links() }}
                    </div>
                @else
                    {{-- Empty State --}}
                    <div class="text-center py-16 sm:py-24 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-3xl shadow-xs p-6 sm:p-8">
                        <div class="w-14 h-14 bg-blue-50 dark:bg-blue-950/40 rounded-2xl flex items-center justify-center mx-auto mb-4 text-blue-600">
                            <i class="ph-bold ph-magnifying-glass text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">No matching properties found</h3>
                        <p class="text-slate-500 dark:text-slate-400 text-xs sm:text-sm mb-6 max-w-md mx-auto">We couldn't find any rentals matching your exact filters. Try clearing some filters or changing location.</p>
                        <a href="{{ route('properties.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold tracking-wider uppercase rounded-xl shadow-sm transition-all" title="Reset All Filters">
                            <i class="ph-bold ph-arrow-counter-clockwise"></i> Reset All Filters
                        </a>
                    </div>
                @endif
            </div>
        </div>

    </div>
</section>

<script>
    function toggleMobileFilterSheet(open) {
        const overlay = document.getElementById('mobile-filter-modal-overlay');
        const sheet = document.getElementById('mobile-filter-modal-sheet');
        if (!overlay || !sheet) return;

        if (open) {
            overlay.classList.add('active');
            sheet.classList.add('active');
            document.body.style.overflow = 'hidden';
        } else {
            overlay.classList.remove('active');
            sheet.classList.remove('active');
            document.body.style.overflow = '';
        }
    }

    function updatePropertySort(sortValue) {
        const url = new URL(window.location.href);
        if (sortValue && sortValue !== 'latest' && sortValue !== 'new_to_old') {
            url.searchParams.set('sort', sortValue);
        } else {
            url.searchParams.delete('sort');
        }
        url.searchParams.delete('page');
        window.location.href = url.toString();
    }
</script>

<style>
#mobile-filter-modal-overlay {
    display: none !important;
    position: fixed !important;
    inset: 0 !important;
    z-index: 9998 !important;
    background: rgba(15, 23, 42, 0.7) !important;
    backdrop-filter: blur(4px);
    opacity: 0;
    transition: opacity 0.3s ease;
    pointer-events: none !important;
}
#mobile-filter-modal-overlay.active {
    display: block !important;
    opacity: 1 !important;
    pointer-events: auto !important;
}
#mobile-filter-modal-sheet {
    display: none !important;
    position: fixed !important;
    left: 0 !important;
    right: 0 !important;
    bottom: 0 !important;
    max-height: 88vh !important;
    z-index: 9999 !important;
    background: #ffffff !important;
    border-top: 1px solid #e2e8f0 !important;
    border-radius: 1.5rem 1.5rem 0 0 !important;
    box-shadow: 0 -15px 35px rgba(0, 0, 0, 0.25) !important;
    flex-direction: column !important;
    transform: translateY(100%) !important;
    transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
}
html.dark #mobile-filter-modal-sheet {
    background: #0f172a !important;
    border-top: 1px solid #1e293b !important;
    box-shadow: 0 -15px 35px rgba(0, 0, 0, 0.6) !important;
}
#mobile-filter-modal-sheet.active {
    display: flex !important;
    transform: translateY(0) !important;
}
</style>

@endsection
