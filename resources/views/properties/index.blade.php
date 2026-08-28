@extends('layouts.app')

@section('title', 'Rental Properties in India - Houses, Flats & PGs | UnlockRentals')
@section('meta_description', 'Browse rental houses, flats, PGs, shops, and commercial spaces in India. Filter verified rental properties by location, budget, rooms, and property type.')

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

<section class="min-h-screen pt-28 sm:pt-32 pb-24 bg-[#f8fafc] dark:bg-slate-950 relative overflow-hidden" id="properties-browse">
    {{-- Ambient Background Gradients --}}
    <div class="absolute top-0 left-0 w-full h-[500px] bg-gradient-to-b from-blue-500/[0.04] via-indigo-500/[0.02] to-transparent pointer-events-none"></div>
    <div class="absolute -top-32 -left-32 w-96 h-96 bg-blue-500/10 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute top-1/3 right-0 w-80 h-80 bg-indigo-500/10 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        {{-- Page Header & Breadcrumb --}}
        <div class="mb-8 sm:mb-10 pb-6 border-b border-slate-200/80 dark:border-slate-800">
            <div class="max-w-3xl">
                <nav class="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">
                    <a href="{{ route('home') }}" class="hover:text-blue-600 transition-colors" title="Home">Home</a>
                    <i class="ph-bold ph-caret-right text-[10px]"></i>
                    <span class="text-slate-900 dark:text-slate-100 font-extrabold">Properties</span>
                    @if(request('type'))
                        <i class="ph-bold ph-caret-right text-[10px]"></i>
                        <span class="text-blue-600 capitalize font-extrabold">{{ request('type') }}</span>
                    @endif
                </nav>
                <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white leading-tight">
                    @if(request('type'))
                        <span class="text-blue-600 capitalize">{{ request('type') }}s</span> for Rent
                    @elseif(request('search'))
                        Results for <span class="text-blue-600">"{{ request('search') }}"</span>
                    @else
                        Explore <span class="text-blue-600">Verified Rentals</span> in India
                    @endif
                </h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm sm:text-base font-normal mt-2 leading-relaxed">
                    Browse 100% direct-owner verified houses, flats, PGs, and commercial spaces with zero brokerage.
                </p>
            </div>
        </div>

        {{-- Layout: Sidebar + Grid --}}
        <div class="flex flex-col lg:flex-row gap-8 xl:gap-10 items-start">
            
            {{-- Left Sidebar Filters --}}
            <div class="w-full lg:w-80 flex-shrink-0">
                @include('components.search-filters', ['categories' => $categories, 'locations' => $locations])
            </div>

            {{-- Right Property Grid --}}
            <div class="flex-1 w-full min-w-0">
                @if($properties->count() > 0)
                    {{-- Grid Container --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-2 2xl:grid-cols-3 gap-6">
                        @foreach($properties as $property)
                            <x-property-card :property="$property" />
                        @endforeach
                    </div>

                    {{-- Pagination Container --}}
                    <div class="mt-12 pt-6 border-t border-slate-200/80 dark:border-slate-800">
                        {{ $properties->links() }}
                    </div>
                @else
                    {{-- Empty State --}}
                    <div class="text-center py-24 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-3xl shadow-xs p-8">
                        <div class="w-16 h-16 bg-blue-50 dark:bg-blue-950/40 rounded-2xl flex items-center justify-center mx-auto mb-4 text-blue-600">
                            <i class="ph-bold ph-magnifying-glass text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">No matching properties found</h3>
                        <p class="text-slate-500 dark:text-slate-400 text-sm mb-6 max-w-md mx-auto">We couldn't find any rentals matching your exact filters. Try clearing some filters or changing location.</p>
                        <a href="{{ route('properties.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold tracking-wider uppercase rounded-xl shadow-sm transition-all" title="Reset All Filters">
                            <i class="ph-bold ph-arrow-counter-clockwise"></i> Reset All Filters
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- People Also Search For Section --}}
        <div class="mt-20 border-t border-slate-200/80 dark:border-slate-800 pt-10">
            <x-people-also-search />
        </div>

    </div>
</section>

@endsection
