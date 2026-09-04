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
<div class="min-h-screen pt-24 pb-28 bg-[#fcfcfd] dark:bg-slate-950 relative overflow-hidden">
    {{-- Ambient Background Gradients --}}
    <div class="absolute top-0 left-0 w-full h-[600px] bg-gradient-to-b from-blue-600/5 via-indigo-500/[0.02] to-transparent pointer-events-none"></div>
    <div class="absolute -top-40 -left-40 w-[500px] h-[500px] bg-blue-600/10 rounded-full blur-[120px] pointer-events-none dark:bg-blue-600/5"></div>
    <div class="absolute top-1/3 right-0 w-[400px] h-[400px] bg-indigo-500/10 rounded-full blur-[100px] pointer-events-none dark:bg-indigo-500/5"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        {{-- Breadcrumb Navigation --}}
        <nav class="flex items-center gap-2.5 text-[10px] font-bold text-zinc-400 dark:text-slate-500 uppercase tracking-widest mb-6">
            <a href="{{ url('/') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors" title="Home">Home</a>
            <i class="ph-bold ph-caret-right text-[8px]"></i>
            <a href="{{ route('properties.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors" title="Rentals">Rentals</a>
            <i class="ph-bold ph-caret-right text-[8px]"></i>
            <span class="text-zinc-900 dark:text-slate-200 font-extrabold">{{ $keywordItem['keyword'] ?? 'List Property' }}</span>
        </nav>

        {{-- Hero Header Section --}}
        <div class="max-w-4xl mx-auto text-center mb-16">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 text-xs font-black uppercase tracking-wider mb-5 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>100% Free Owner Ad · Zero Brokerage · Instant Tenant Leads</span>
            </div>

            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black tracking-tight text-zinc-900 dark:text-slate-100 mb-6 leading-[1.15]">
                {!! str_replace(' | UnlockRentals', '', $meta_title) !!}
            </h1>

            <p class="text-zinc-600 dark:text-slate-300 text-base sm:text-xl font-normal leading-relaxed max-w-2xl mx-auto mb-8">
                {{ $meta_description }}
            </p>

            {{-- Primary Action Buttons --}}
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('properties.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-8 py-4 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-base font-bold shadow-xl shadow-blue-500/20 hover:shadow-2xl hover:shadow-blue-500/30 hover:-translate-y-0.5 transition-all">
                    <i class="ph-bold ph-plus-circle text-xl"></i>
                    <span>Post Free Property Listing</span>
                </a>
                <a href="{{ route('properties.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-4 rounded-2xl bg-white dark:bg-slate-900 border border-zinc-200 dark:border-slate-800 text-zinc-800 dark:text-slate-200 text-base font-semibold hover:bg-zinc-50 dark:hover:bg-slate-800/80 transition-all">
                    <i class="ph-bold ph-magnifying-glass text-lg text-blue-600 dark:text-blue-400"></i>
                    <span>Explore Current Listings</span>
                </a>
            </div>

            {{-- Quick Trust Badges --}}
            <div class="flex flex-wrap items-center justify-center gap-6 mt-10 text-xs font-semibold text-zinc-500 dark:text-slate-400">
                <span class="flex items-center gap-1.5"><i class="ph-fill ph-check-circle text-emerald-500 text-base"></i> No Middlemen Fees</span>
                <span class="flex items-center gap-1.5"><i class="ph-fill ph-check-circle text-emerald-500 text-base"></i> Direct Tenant Contact via Call & WhatsApp</span>
                <span class="flex items-center gap-1.5"><i class="ph-fill ph-check-circle text-emerald-500 text-base"></i> Privacy-Protected Inquiries</span>
            </div>
        </div>

        {{-- 3 Step Process Card Grid --}}
        <div class="mb-20">
            <div class="text-center max-w-2xl mx-auto mb-10">
                <h2 class="text-2xl sm:text-3xl font-black text-zinc-900 dark:text-slate-100">
                    How It Works in 3 Simple Steps
                </h2>
                <p class="text-zinc-500 dark:text-slate-400 text-sm sm:text-base mt-2">
                    Rent out your home, flat, room, or commercial space faster than traditional brokers.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
                <div class="p-8 rounded-3xl bg-white dark:bg-slate-900 border border-zinc-200/80 dark:border-slate-800 shadow-sm relative group hover:shadow-md transition-all">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center text-2xl font-black mb-6">
                        1
                    </div>
                    <h3 class="text-xl font-bold text-zinc-900 dark:text-slate-100 mb-3">Add Property Details</h3>
                    <p class="text-zinc-500 dark:text-slate-400 text-sm leading-relaxed">
                        Enter your rental price, location, bedrooms, amenities, and upload photos of your flat, house, or room.
                    </p>
                </div>

                <div class="p-8 rounded-3xl bg-white dark:bg-slate-900 border border-zinc-200/80 dark:border-slate-800 shadow-sm relative group hover:shadow-md transition-all">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-2xl font-black mb-6">
                        2
                    </div>
                    <h3 class="text-xl font-bold text-zinc-900 dark:text-slate-100 mb-3">Get Instant Verification</h3>
                    <p class="text-zinc-500 dark:text-slate-400 text-sm leading-relaxed">
                        Our quality team reviews your listing to add a "Verified Owner" badge, boosting your search visibility to thousands of active seekers.
                    </p>
                </div>

                <div class="p-8 rounded-3xl bg-white dark:bg-slate-900 border border-zinc-200/80 dark:border-slate-800 shadow-sm relative group hover:shadow-md transition-all">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-2xl font-black mb-6">
                        3
                    </div>
                    <h3 class="text-xl font-bold text-zinc-900 dark:text-slate-100 mb-3">Close Rental Deal</h3>
                    <p class="text-zinc-500 dark:text-slate-400 text-sm leading-relaxed">
                        Receive direct tenant inquiries on your phone or WhatsApp, schedule property walkthroughs, and finalize rent with zero brokerage.
                    </p>
                </div>
            </div>
        </div>

        {{-- Owner Advantages Section --}}
        <div class="mb-20 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-3xl p-8 sm:p-14 text-white shadow-2xl relative overflow-hidden">
            <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="max-w-3xl relative z-10">
                <span class="inline-block px-3 py-1 rounded-full bg-white/20 backdrop-blur-sm text-xs font-black uppercase tracking-wider mb-4">
                    Why Landlords Choose UnlockRentals
                </span>
                <h2 class="text-2xl sm:text-4xl font-black tracking-tight mb-6">
                    Say Goodbye to Heavy Brokerages & Idle Properties
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm text-blue-50">
                    <div class="flex items-start gap-3">
                        <i class="ph-fill ph-check-circle text-emerald-300 text-xl mt-0.5 shrink-0"></i>
                        <span><strong>Zero Commission:</strong> Keep 100% of your rental income without paying any brokerage month after month.</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <i class="ph-fill ph-check-circle text-emerald-300 text-xl mt-0.5 shrink-0"></i>
                        <span><strong>Verified Tenants:</strong> Screened working professionals and families searching for rental homes across India.</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <i class="ph-fill ph-check-circle text-emerald-300 text-xl mt-0.5 shrink-0"></i>
                        <span><strong>Full Listing Control:</strong> Edit rent, toggle availability, update photos, or pause listings anytime from your dashboard.</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <i class="ph-fill ph-check-circle text-emerald-300 text-xl mt-0.5 shrink-0"></i>
                        <span><strong>Fast Occupancy:</strong> Over 85% of listings on UnlockRentals receive tenant inquiries within the first 48 hours.</span>
                    </div>
                </div>

                <div class="mt-8 pt-8 border-t border-white/20 flex flex-wrap items-center gap-4">
                    <a href="{{ route('properties.create') }}" class="inline-flex items-center gap-2 px-7 py-3.5 rounded-2xl bg-white text-blue-700 text-sm font-black shadow-lg hover:bg-blue-50 transition">
                        <span>List Your Property Free</span>
                        <i class="ph-bold ph-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Frequently Asked Questions for Owners --}}
        <div class="max-w-4xl mx-auto mb-20">
            <h2 class="text-2xl sm:text-3xl font-extrabold text-zinc-900 dark:text-slate-100 mb-2 text-center">
                Frequently Asked Questions for Owners
            </h2>
            <p class="text-zinc-500 dark:text-slate-400 text-sm sm:text-base font-light text-center mb-10">
                Everything you need to know about listing your rental property on UnlockRentals.
            </p>

            <div class="space-y-4">
                <details class="group border border-stone-200/80 dark:border-slate-800/80 rounded-2xl p-6 bg-white dark:bg-slate-900/60 transition-all duration-300 [&_summary::-webkit-details-marker]:hidden" open>
                    <summary class="flex items-center justify-between font-bold text-zinc-900 dark:text-slate-100 cursor-pointer list-none">
                        <span class="text-base md:text-lg">Is it really free to post a property listing?</span>
                        <span class="transition-transform duration-300 group-open:rotate-180 flex items-center justify-center w-8 h-8 rounded-full bg-stone-50 dark:bg-slate-800 text-zinc-500">
                            <i class="ph ph-caret-down font-bold"></i>
                        </span>
                    </summary>
                    <div class="mt-4 text-zinc-500 dark:text-slate-400 text-sm md:text-base leading-relaxed font-light">
                        Yes, posting your rental property ad on UnlockRentals is 100% free. You can add property details, upload photos, and connect with prospective tenants directly with zero brokerage fee.
                    </div>
                </details>

                <details class="group border border-stone-200/80 dark:border-slate-800/80 rounded-2xl p-6 bg-white dark:bg-slate-900/60 transition-all duration-300 [&_summary::-webkit-details-marker]:hidden">
                    <summary class="flex items-center justify-between font-bold text-zinc-900 dark:text-slate-100 cursor-pointer list-none">
                        <span class="text-base md:text-lg">How quickly will I start receiving inquiries?</span>
                        <span class="transition-transform duration-300 group-open:rotate-180 flex items-center justify-center w-8 h-8 rounded-full bg-stone-50 dark:bg-slate-800 text-zinc-500">
                            <i class="ph ph-caret-down font-bold"></i>
                        </span>
                    </summary>
                    <div class="mt-4 text-zinc-500 dark:text-slate-400 text-sm md:text-base leading-relaxed font-light">
                        Most properties in prime rental areas receive genuine tenant inquiries within 24 to 48 hours of being listed and verified.
                    </div>
                </details>

                <details class="group border border-stone-200/80 dark:border-slate-800/80 rounded-2xl p-6 bg-white dark:bg-slate-900/60 transition-all duration-300 [&_summary::-webkit-details-marker]:hidden">
                    <summary class="flex items-center justify-between font-bold text-zinc-900 dark:text-slate-100 cursor-pointer list-none">
                        <span class="text-base md:text-lg">What property types can I list on UnlockRentals?</span>
                        <span class="transition-transform duration-300 group-open:rotate-180 flex items-center justify-center w-8 h-8 rounded-full bg-stone-50 dark:bg-slate-800 text-zinc-500">
                            <i class="ph ph-caret-down font-bold"></i>
                        </span>
                    </summary>
                    <div class="mt-4 text-zinc-500 dark:text-slate-400 text-sm md:text-base leading-relaxed font-light">
                        You can list apartments, independent houses, builder floors, 1RK/1BHK/2BHK flats, rooms, PG / co-living accommodations, villas, and commercial shops or office spaces.
                    </div>
                </details>
            </div>
        </div>

        {{-- Footer Internal Links --}}
        @include('partials.seo-links', ['city' => null, 'type' => 'house', 'typeDisplay' => 'Rental'])

    </div>
</div>
@endsection
