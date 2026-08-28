{{-- ============================================================
     UNLOCK RENTALS — PEOPLE ALSO SEARCH FOR (SEO & DISCOVERY)
     High-intent search query pills matching Google's search widget
     ============================================================ --}}

@props([
    'title' => 'People also search for',
    'location' => null,
    'currentCity' => null,
    'currentType' => null,
])

@php
    $defaultQueries = [
        [
            'display' => 'search house near <strong>delhi</strong>',
            'url' => route('properties.index', ['search' => 'Delhi']),
        ],
        [
            'display' => '<strong>Free</strong> search house near me',
            'url' => route('properties.index', ['purpose' => 'rent']),
        ],
        [
            'display' => 'search house near <strong>gurugram, haryana</strong>',
            'url' => route('properties.index', ['search' => 'Gurugram']),
        ],
        [
            'display' => '<strong>Urgent</strong> House <strong>for sale in Gurgaon</strong>',
            'url' => route('properties.index', ['search' => 'Gurgaon', 'purpose' => 'buy']),
        ],
        [
            'display' => 'Search house near me <strong>for rent</strong>',
            'url' => route('properties.index', ['purpose' => 'rent']),
        ],
        [
            'display' => 'House <strong>for rent in Gurgaon under 5000</strong>',
            'url' => route('properties.index', ['search' => 'Gurgaon', 'price' => '0-20000']),
        ],
        [
            'display' => 'Search house near me <strong>by owner</strong>',
            'url' => route('properties.index', ['search' => 'owner']),
        ],
        [
            'display' => 'House <strong>in Gurgaon for rent</strong>',
            'url' => route('properties.index', ['search' => 'Gurgaon', 'purpose' => 'rent']),
        ],
    ];
@endphp

<section class="ur-pasf-section py-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto" aria-labelledby="people-also-search-heading">
    <div class="mb-6">
        <h3 id="people-also-search-heading" class="text-xl sm:text-2xl font-bold text-zinc-900 dark:text-white flex items-center gap-2.5">
            <span>{{ $title }}</span>
        </h3>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5 sm:gap-4">
        @foreach($defaultQueries as $query)
            <a href="{{ $query['url'] }}"
               class="ur-pasf-card group flex items-center justify-between p-4 sm:py-4 sm:px-5 bg-[#f1f3f4] dark:bg-slate-800/80 hover:bg-[#e8eaed] dark:hover:bg-slate-700/80 border border-transparent hover:border-zinc-300 dark:hover:border-slate-600 rounded-xl transition-all duration-200 shadow-sm hover:shadow text-zinc-800 dark:text-zinc-200">
                <span class="text-sm sm:text-[15px] font-normal text-zinc-700 dark:text-zinc-200 group-hover:text-zinc-900 dark:group-hover:text-white transition-colors leading-snug">
                    {!! $query['display'] !!}
                </span>
                <span class="flex items-center justify-center w-8 h-8 rounded-full text-zinc-500 dark:text-zinc-400 group-hover:text-[#2563EB] dark:group-hover:text-blue-400 transition-colors flex-shrink-0 ml-3">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </span>
            </a>
        @endforeach
    </div>
</section>

<style>
.ur-pasf-card {
    cursor: pointer;
    text-decoration: none;
}
.ur-pasf-card strong {
    font-weight: 700;
    color: #0f172a;
}
.dark .ur-pasf-card strong {
    color: #ffffff;
}
</style>
