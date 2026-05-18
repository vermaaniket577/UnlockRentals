@extends('layouts.app')

@section('title', 'Browse Properties - UnlockRentals')
@section('meta_description', 'Browse houses and shops for rent. Filter by type, price, location, and more.')

@section('content')

<section class="min-h-screen pt-28 pb-32 bg-[#fcfcfd] relative overflow-hidden" id="properties-browse">
    {{-- Ambient Background Elements --}}
    <div class="absolute top-0 left-0 w-full h-[500px] bg-gradient-to-b from-stone-100/40 to-transparent pointer-events-none"></div>
    <div class="absolute -top-24 -left-24 w-96 h-96 bg-[#2563EB]/5 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute top-1/2 right-0 w-64 h-64 bg-indigo-500/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        {{-- Page Header --}}
        <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-8">
            <div class="max-w-3xl">
                <nav class="flex items-center gap-2 text-[10px] font-bold text-zinc-400 uppercase tracking-widest mb-4">
                    <a href="{{ route('home') }}" class="hover:text-[#2563EB] transition-colors">Home</a>
                    <i class="ph-bold ph-caret-right text-[8px]"></i>
                    <span class="text-zinc-900">Properties</span>
                </nav>
                <h1 class="text-3xl md:text-5xl font-extrabold tracking-tight text-zinc-900 mb-6 leading-tight">
                    @if(request('type'))
                        <span class="text-[#2563EB] capitalize">{{ request('type') }}s</span> for Rent
                    @elseif(request('search'))
                        Results for <span class="text-[#2563EB]">"{{ request('search') }}"</span>
                    @else
                        Explore <span class="text-[#2563EB]">Premium</span> Properties
                    @endif
                </h1>
                <p class="text-zinc-500 text-base md:text-lg font-normal leading-relaxed max-w-2xl">
                    Discover handpicked luxury rentals across India's most prestigious neighborhoods.
                </p>
            </div>
            <div class="hidden md:flex items-center gap-4 px-6 py-4 bg-white border border-stone-200/60 rounded-2xl shadow-sm">
                <div class="flex flex-col items-end">
                    <span class="text-xs font-bold text-zinc-400 uppercase tracking-wider mb-1">Database Status</span>
                    <span class="text-xs font-medium text-green-500 flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Live & Verified</span>
                </div>
                <div class="w-px h-8 bg-stone-100"></div>
                <div class="flex flex-col">
                    <span class="text-2xl font-black text-zinc-900 leading-none">{{ $properties->total() }}</span>
                    <span class="text-[10px] font-bold text-zinc-400 uppercase mt-1">Found</span>
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-10 xl:gap-16">
            {{-- Sidebar Filters --}}
            <div class="lg:w-80 flex-shrink-0">
                @include('components.search-filters', ['categories' => $categories, 'locations' => $locations])
            </div>

            {{-- Property Grid --}}
            <div class="flex-1">
                @if($properties->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-2 2xl:grid-cols-3 gap-8">
                        @foreach($properties as $property)
                            <x-property-card :property="$property" />
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-16">
                        {{ $properties->links() }}
                    </div>
                @else
                    <div class="text-center py-32 bg-white/50 backdrop-blur-sm border border-stone-200/60 rounded-3xl shadow-sm">
                        <div class="w-20 h-20 bg-stone-50 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="ph ph-magnifying-glass text-4xl text-zinc-300"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-zinc-900 mb-2">No properties found</h3>
                        <p class="text-zinc-500 mb-8 max-w-sm mx-auto">We couldn't find any matches for your current filters. Try broadening your search criteria.</p>
                        <a href="{{ route('properties.index') }}" class="inline-flex items-center gap-3 px-8 py-4 bg-[#2563EB] hover:bg-[#1D4ED8] text-white text-[11px] font-bold tracking-widest uppercase rounded-xl shadow-lg shadow-[#2563EB]/25 transition-all">
                            <i class="ph ph-arrow-counter-clockwise text-sm"></i> Clear All Filters
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection
