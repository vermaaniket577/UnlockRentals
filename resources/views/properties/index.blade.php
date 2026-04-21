@extends('layouts.app')

@section('title', 'Browse Properties - UnlockRentals')
@section('meta_description', 'Browse houses and shops for rent. Filter by type, price, location, and more.')

@section('content')

<section class="pt-24 pb-24 lg:pt-32 lg:pb-32" id="properties-browse">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Page Header --}}
        <div class="mb-12">
            <h1 class="text-4xl lg:text-5xl font-serif font-light tracking-wide text-zinc-900 mb-4">
                @if(request('type'))
                    {{ ucfirst(request('type')) }}s for Rent
                @elseif(request('search'))
                    Results for "{{ request('search') }}"
                @else
                    All Properties
                @endif
            </h1>
            <p class="text-zinc-500">
                {{ $properties->total() }} {{ Str::plural('property', $properties->total()) }} found
            </p>
        </div>

        <div class="flex flex-col lg:flex-row gap-12 lg:gap-16">
            {{-- Sidebar Filters --}}
            <div class="lg:w-80 flex-shrink-0">
                @include('components.search-filters', ['categories' => $categories, 'locations' => $locations])
            </div>

            {{-- Property Grid --}}
            <div class="flex-1">
                @if($properties->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach($properties as $property)
                            @include('components.property-card', ['property' => $property])
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-10">
                        {{ $properties->links() }}
                    </div>
                @else
                    <div class="text-center py-20 bg-stone-50/30 border border-stone-200/50 rounded-sm">
                        <i class="ph ph-magnifying-glass text-5xl text-gray-700 mb-4"></i>
                        <h3 class="text-xl font-semibold text-zinc-500 mb-2">No properties found</h3>
                        <p class="text-zinc-500 mb-6">Try adjusting your filters or search terms.</p>
                        <a href="{{ route('properties.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-[#2563EB] hover:bg-[#2563EB] text-white text-sm font-semibold rounded-sm transition-all">
                            <i class="ph ph-arrow-counter-clockwise"></i> Clear Filters
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection
