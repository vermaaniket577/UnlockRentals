@extends('layouts.app')

@section('title', $meta_title)
@section('meta_description', $meta_description)

@push('head')
<script type="application/ld+json">
{!! $schemas['breadcrumbs'] !!}
</script>
<script type="application/ld+json">
{!! $schemas['localBusiness'] !!}
</script>
<script type="application/ld+json">
{!! $schemas['faqs'] !!}
</script>
@endpush

@section('content')
<section class="min-h-screen pt-28 pb-32 bg-[#fcfcfd] dark:bg-slate-950 relative overflow-hidden">
    {{-- Ambient Background Gradients --}}
    <div class="absolute top-0 left-0 w-full h-[600px] bg-gradient-to-b from-[#2563EB]/5 via-indigo-500/[0.02] to-transparent pointer-events-none"></div>
    <div class="absolute -top-40 -left-40 w-[500px] h-[500px] bg-[#2563EB]/10 rounded-full blur-[120px] pointer-events-none dark:bg-[#2563EB]/5"></div>
    <div class="absolute top-1/3 right-0 w-[400px] h-[400px] bg-indigo-500/10 rounded-full blur-[100px] pointer-events-none dark:bg-indigo-500/5"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        {{-- Breadcrumb Navigation --}}
        <nav class="flex items-center gap-2.5 text-[10px] font-bold text-zinc-400 dark:text-slate-500 uppercase tracking-widest mb-6">
            <a href="{{ url('/') }}" class="hover:text-[#2563EB] dark:hover:text-[#2563EB] transition-colors">Home</a>
            <i class="ph-bold ph-caret-right text-[8px]"></i>
            @if($city)
                <a href="{{ url(Str::slug($typeDisplay . '-for-rent-in-' . $city)) }}" class="hover:text-[#2563EB] dark:hover:text-[#2563EB] transition-colors">{{ $typeDisplay }} in {{ $city }}</a>
                @if($locality)
                    <i class="ph-bold ph-caret-right text-[8px]"></i>
                    <span class="text-zinc-900 dark:text-slate-200 font-extrabold">{{ $locality }}</span>
                @endif
            @else
                <span class="text-zinc-900 dark:text-slate-200 font-extrabold">{{ $typeDisplay }}</span>
            @endif
        </nav>

        {{-- Hero Header Area --}}
        <div class="mb-12 flex flex-col lg:flex-row lg:items-end justify-between gap-8 pb-8 border-b border-stone-200/60 dark:border-slate-800/60">
            <div class="max-w-4xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-[#2563EB]/10 text-[#2563EB] text-xs font-bold rounded-full mb-4">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#2563EB] animate-pulse"></span>
                    Verified Rental Listings
                </div>
                <h1 class="text-3xl md:text-5xl lg:text-6xl font-extrabold tracking-tight text-zinc-900 dark:text-slate-100 mb-4 leading-[1.15]">
                    {!! str_replace($typeDisplay, '<span class="text-[#2563EB]">' . $typeDisplay . '</span>', str_replace(' | UnlockRentals', '', $meta_title)) !!}
                </h1>
                <p class="text-zinc-500 dark:text-slate-400 text-base md:text-lg font-light leading-relaxed max-w-2xl">
                    Find and book commission-free {{ strtolower($typeDisplay) }} options. Fully verified properties with direct contact details.
                </p>
            </div>
            
            {{-- Quick stats block --}}
            <div class="flex items-center gap-4 px-6 py-4 bg-white dark:bg-slate-900 border border-stone-200/60 dark:border-slate-800 rounded-2xl shadow-sm self-start lg:self-end">
                <div class="flex flex-col items-end">
                    <span class="text-[10px] font-bold text-zinc-400 dark:text-slate-500 uppercase tracking-wider mb-1">Status</span>
                    <span class="text-xs font-semibold text-green-500 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> 
                        Updated Daily
                    </span>
                </div>
                <div class="w-px h-8 bg-stone-100 dark:bg-slate-800"></div>
                <div class="flex flex-col">
                    <span class="text-2xl font-black text-zinc-900 dark:text-slate-100 leading-none">
                        {{ $properties->total() }}
                    </span>
                    <span class="text-[10px] font-bold text-zinc-400 dark:text-slate-500 uppercase mt-1">Available</span>
                </div>
            </div>
        </div>

        {{-- Main Listings Grid & Recommended fallback --}}
        <div class="mb-24">
            @if($properties->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($properties as $property)
                        <x-property-card :property="$property" />
                    @endforeach
                </div>
                
                {{-- Pagination --}}
                <div class="mt-16">
                    {{ $properties->links() }}
                </div>
            @else
                {{-- Zero Result State with recommendations --}}
                <div class="mb-16">
                    <div class="text-center py-20 bg-white/50 dark:bg-slate-900/50 backdrop-blur-sm border border-stone-200/60 dark:border-slate-800 rounded-3xl shadow-sm mb-16">
                        <div class="w-16 h-16 bg-stone-50 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="ph ph-magnifying-glass text-3xl text-zinc-300 dark:text-slate-600"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-zinc-900 dark:text-slate-100 mb-2">No listings in this specific filter</h3>
                        <p class="text-zinc-500 dark:text-slate-400 mb-6 max-w-md mx-auto font-light">
                            We don't have active properties matching your exact criteria right now. Check out these highly recommended properties in the region below.
                        </p>
                    </div>

                    <div class="border-t border-stone-200/60 dark:border-slate-800/60 pt-12">
                        <h3 class="text-xl md:text-2xl font-extrabold text-zinc-900 dark:text-slate-100 mb-8">
                            Recommended properties for you
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            @foreach($recommendations as $recProperty)
                                <x-property-card :property="$recProperty" />
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- FAQ Section (Dynamic Matching JSON-LD FAQ schema) --}}
        <div class="max-w-4xl mx-auto mb-24">
            <h2 class="text-2xl md:text-3xl font-extrabold text-zinc-900 dark:text-slate-100 mb-2 text-center">
                Frequently Asked Questions
            </h2>
            <p class="text-zinc-500 dark:text-slate-400 text-sm md:text-base font-light text-center mb-10">
                Get answers to common queries regarding properties, pricing, and amenities.
            </p>

            <div class="space-y-4">
                @php
                    $minPrice = $properties->isNotEmpty() ? $properties->min('price') : 3000;
                    $maxPrice = $properties->isNotEmpty() ? $properties->max('price') : 25000;
                    $avgPrice = $properties->isNotEmpty() ? round($properties->avg('price')) : 8000;
                    $locationName = $locality ?? $city ?? $landmark ?? 'this location';
                @endphp

                <details class="group border border-stone-200/80 dark:border-slate-800/80 rounded-2xl p-6 bg-white dark:bg-slate-900/60 transition-all duration-300 [&_summary::-webkit-details-marker]:hidden" open>
                    <summary class="flex items-center justify-between font-bold text-zinc-900 dark:text-slate-100 cursor-pointer list-none">
                        <span class="text-base md:text-lg">What is the average rent for {{ strtolower($typeDisplay) }} in {{ $locationName }}?</span>
                        <span class="transition-transform duration-300 group-open:rotate-180 flex items-center justify-center w-8 h-8 rounded-full bg-stone-50 dark:bg-slate-800 text-zinc-500">
                            <i class="ph ph-caret-down font-bold"></i>
                        </span>
                    </summary>
                    <div class="mt-4 text-zinc-500 dark:text-slate-400 text-sm md:text-base leading-relaxed font-light">
                        The average rent of verified {{ strtolower(Str::plural($typeDisplay)) }} in {{ $locationName }} is approximately ₹{{ number_format($avgPrice) }} per month, with options starting as low as ₹{{ number_format($minPrice) }} and luxury units up to ₹{{ number_format($maxPrice) }}.
                    </div>
                </details>

                <details class="group border border-stone-200/80 dark:border-slate-800/80 rounded-2xl p-6 bg-white dark:bg-slate-900/60 transition-all duration-300 [&_summary::-webkit-details-marker]:hidden">
                    <summary class="flex items-center justify-between font-bold text-zinc-900 dark:text-slate-100 cursor-pointer list-none">
                        <span class="text-base md:text-lg">Are there budget-friendly rental options available in {{ $locationName }}?</span>
                        <span class="transition-transform duration-300 group-open:rotate-180 flex items-center justify-center w-8 h-8 rounded-full bg-stone-50 dark:bg-slate-800 text-zinc-500">
                            <i class="ph ph-caret-down font-bold"></i>
                        </span>
                    </summary>
                    <div class="mt-4 text-zinc-500 dark:text-slate-400 text-sm md:text-base leading-relaxed font-light">
                        Yes, there are several budget-friendly options available. You can filter by price limit or check listings under ₹{{ number_format($budget ?? 10000) }} on our platform.
                    </div>
                </details>

                <details class="group border border-stone-200/80 dark:border-slate-800/80 rounded-2xl p-6 bg-white dark:bg-slate-900/60 transition-all duration-300 [&_summary::-webkit-details-marker]:hidden">
                    <summary class="flex items-center justify-between font-bold text-zinc-900 dark:text-slate-100 cursor-pointer list-none">
                        <span class="text-base md:text-lg">What security amenities are provided in PGs and flats listed in {{ $locationName }}?</span>
                        <span class="transition-transform duration-300 group-open:rotate-180 flex items-center justify-center w-8 h-8 rounded-full bg-stone-50 dark:bg-slate-800 text-zinc-500">
                            <i class="ph ph-caret-down font-bold"></i>
                        </span>
                    </summary>
                    <div class="mt-4 text-zinc-500 dark:text-slate-400 text-sm md:text-base leading-relaxed font-light">
                        Most rental properties listed on UnlockRentals in {{ $locationName }} come with security amenities such as 24/7 CCTV surveillance, security guards at gate, gated community access, power backup, and intercom facilities.
                    </div>
                </details>
            </div>
        </div>

        {{-- Programmatic SEO Internal Link Cloud Footer --}}
        @include('partials.seo-links', ['city' => $city, 'type' => $type, 'typeDisplay' => $typeDisplay])

    </div>
</section>
@endsection
