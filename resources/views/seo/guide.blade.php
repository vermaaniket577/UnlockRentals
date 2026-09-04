@extends('layouts.app')

@section('title', $meta_title)
@section('meta_description', $meta_description)

@push('head')
<script type="application/ld+json">
{!! $schemas['breadcrumbs'] !!}
</script>
<script type="application/ld+json">
{!! $schemas['faqs'] !!}
</script>
<script type="application/ld+json">
{!! $schemas['article'] !!}
</script>
@endpush

@section('content')
<div class="min-h-screen pt-24 pb-28 bg-[#fcfcfd] dark:bg-slate-950 relative overflow-hidden">
    {{-- Ambient Background Gradients --}}
    <div class="absolute top-0 left-0 w-full h-[500px] bg-gradient-to-b from-blue-600/5 via-indigo-500/[0.02] to-transparent pointer-events-none"></div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        {{-- Breadcrumb Navigation --}}
        <nav class="flex items-center gap-2.5 text-[10px] font-bold text-zinc-400 dark:text-slate-500 uppercase tracking-widest mb-6">
            <a href="{{ url('/') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors" title="Home">Home</a>
            <i class="ph-bold ph-caret-right text-[8px]"></i>
            <a href="{{ url('/blog') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors" title="Guides">Rental Guides</a>
            <i class="ph-bold ph-caret-right text-[8px]"></i>
            <span class="text-zinc-900 dark:text-slate-200 font-extrabold truncate max-w-[200px] sm:max-w-none">{{ $keywordItem['keyword'] ?? 'Guide' }}</span>
        </nav>

        {{-- Guide Article Header --}}
        <div class="mb-12">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-50 dark:bg-blue-950/60 border border-blue-200 dark:border-blue-800 text-blue-700 dark:text-blue-300 text-xs font-black uppercase tracking-wider mb-5">
                <i class="ph-bold ph-book-open text-sm"></i>
                <span>Rental Guide & Expert Tips</span>
            </div>

            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-zinc-900 dark:text-slate-100 mb-6 leading-[1.2]">
                {!! str_replace(' | UnlockRentals', '', $meta_title) !!}
            </h1>

            <p class="text-zinc-600 dark:text-slate-300 text-base sm:text-lg leading-relaxed mb-6 font-normal">
                {{ $meta_description }}
            </p>

            <div class="flex items-center gap-4 text-xs text-zinc-400 dark:text-slate-500 border-y border-zinc-200/80 dark:border-slate-800 py-3.5">
                <span class="flex items-center gap-1.5"><i class="ph ph-calendar-blank"></i> Updated {{ now()->format('M Y') }}</span>
                <span>•</span>
                <span class="flex items-center gap-1.5"><i class="ph ph-clock"></i> 4 min read</span>
                <span>•</span>
                <span class="flex items-center gap-1.5 text-emerald-600 dark:text-emerald-400 font-bold"><i class="ph-fill ph-shield-check"></i> Verified Advice</span>
            </div>
        </div>

        {{-- Main Article Content Body --}}
        <div class="prose prose-slate dark:prose-invert max-w-none mb-16 text-zinc-700 dark:text-slate-300 text-base leading-relaxed space-y-8">
            
            {{-- Section 1: Overview --}}
            <div class="bg-white dark:bg-slate-900 p-6 sm:p-8 rounded-3xl border border-zinc-200/80 dark:border-slate-800 shadow-sm">
                <h2 class="text-xl sm:text-2xl font-bold text-zinc-900 dark:text-slate-100 mb-4 flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 flex items-center justify-center text-sm font-bold">1</span>
                    <span>Essential Checklist Before You Rent</span>
                </h2>
                <p class="mb-4">
                    Finding the right rental property involves more than just looking at the price tag. Taking a systematic approach ensures you avoid hidden costs, disputes with owners, and unexpected maintenance issues.
                </p>
                <ul class="space-y-2 list-disc list-inside">
                    <li><strong>Inspect the Location:</strong> Check proximity to public transport, daily convenience stores, hospitals, and water supply consistency.</li>
                    <li><strong>Verify Property Ownership:</strong> Ensure you are dealing directly with the legal owner or authorized representative to avoid sub-letting scams.</li>
                    <li><strong>Clarify Monthly Maintenance:</strong> Verify whether monthly electricity, water, generator backup, and society maintenance charges are included in the rent.</li>
                    <li><strong>Review the Security Deposit:</strong> Make sure the security deposit refund conditions and lock-in period are explicitly recorded in writing.</li>
                </ul>
            </div>

            {{-- Section 2: Steps to follow --}}
            <div class="bg-white dark:bg-slate-900 p-6 sm:p-8 rounded-3xl border border-zinc-200/80 dark:border-slate-800 shadow-sm">
                <h2 class="text-xl sm:text-2xl font-bold text-zinc-900 dark:text-slate-100 mb-4 flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 flex items-center justify-center text-sm font-bold">2</span>
                    <span>Rent Agreement & Legal Verification</span>
                </h2>
                <p class="mb-4">
                    Always execute a standard 11-month or multi-year registered rental agreement before paying any advance. Key clauses to verify include:
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm mt-4">
                    <div class="p-4 rounded-2xl bg-zinc-50 dark:bg-slate-800/60 border border-zinc-200/60 dark:border-slate-700/60">
                        <strong class="text-zinc-900 dark:text-slate-100 block mb-1">Notice Period & Exit:</strong>
                        Standard notice period is typically 30 days. Both parties should agree on the exit notice requirements.
                    </div>
                    <div class="p-4 rounded-2xl bg-zinc-50 dark:bg-slate-800/60 border border-zinc-200/60 dark:border-slate-700/60">
                        <strong class="text-zinc-900 dark:text-slate-100 block mb-1">Inventory Checklist:</strong>
                        Document existing furniture, electrical appliances, AC condition, and wall paint before moving in.
                    </div>
                </div>
            </div>

            {{-- Section 3: Why Zero Brokerage is Better --}}
            <div class="p-6 sm:p-8 rounded-3xl bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-950/30 dark:to-indigo-950/30 border border-blue-200/80 dark:border-blue-900/50">
                <h2 class="text-xl sm:text-2xl font-bold text-zinc-900 dark:text-slate-100 mb-3">
                    Skip Brokerage Fees on UnlockRentals
                </h2>
                <p class="text-sm sm:text-base text-zinc-600 dark:text-slate-300 leading-relaxed mb-6">
                    Traditional rental brokers charge 15 to 30 days worth of rent simply for introducing you to a landlord. On UnlockRentals, you interact directly with verified owners, schedule property visits online, and save your hard-earned money.
                </p>
                <div class="flex flex-wrap items-center gap-4">
                    <a href="{{ route('properties.index') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold shadow-md transition">
                        <span>Browse Verified Rentals Near You</span>
                        <i class="ph-bold ph-arrow-right"></i>
                    </a>
                </div>
            </div>

        </div>

        {{-- Recommended Properties Section --}}
        @if(isset($recommendations) && $recommendations->isNotEmpty())
        <div class="mb-16">
            <h3 class="text-xl sm:text-2xl font-black text-zinc-900 dark:text-slate-100 mb-6">
                Featured Rental Properties
            </h3>
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 md:gap-6">
                @foreach($recommendations as $recProperty)
                    <x-property-card :property="$recProperty" />
                @endforeach
            </div>
        </div>
        @endif

        {{-- Frequently Asked Questions for Guide --}}
        <div class="mb-20">
            <h2 class="text-2xl sm:text-3xl font-extrabold text-zinc-900 dark:text-slate-100 mb-2 text-center">
                Frequently Asked Questions
            </h2>
            <p class="text-zinc-500 dark:text-slate-400 text-sm font-light text-center mb-8">
                Common questions about rental agreements, owner contacts, and zero brokerage.
            </p>

            <div class="space-y-4">
                <details class="group border border-stone-200/80 dark:border-slate-800/80 rounded-2xl p-6 bg-white dark:bg-slate-900/60 transition-all duration-300 [&_summary::-webkit-details-marker]:hidden" open>
                    <summary class="flex items-center justify-between font-bold text-zinc-900 dark:text-slate-100 cursor-pointer list-none">
                        <span class="text-base md:text-lg">How can I find a rental property without a broker?</span>
                        <span class="transition-transform duration-300 group-open:rotate-180 flex items-center justify-center w-8 h-8 rounded-full bg-stone-50 dark:bg-slate-800 text-zinc-500">
                            <i class="ph ph-caret-down font-bold"></i>
                        </span>
                    </summary>
                    <div class="mt-4 text-zinc-500 dark:text-slate-400 text-sm md:text-base leading-relaxed font-light">
                        UnlockRentals provides 100% verified, direct-owner rental listings across houses, flats, PGs, and commercial spaces. You can filter by budget, location, and amenities, then contact the owner directly via phone or WhatsApp.
                    </div>
                </details>

                <details class="group border border-stone-200/80 dark:border-slate-800/80 rounded-2xl p-6 bg-white dark:bg-slate-900/60 transition-all duration-300 [&_summary::-webkit-details-marker]:hidden">
                    <summary class="flex items-center justify-between font-bold text-zinc-900 dark:text-slate-100 cursor-pointer list-none">
                        <span class="text-base md:text-lg">Is police verification mandatory for tenants in India?</span>
                        <span class="transition-transform duration-300 group-open:rotate-180 flex items-center justify-center w-8 h-8 rounded-full bg-stone-50 dark:bg-slate-800 text-zinc-500">
                            <i class="ph ph-caret-down font-bold"></i>
                        </span>
                    </summary>
                    <div class="mt-4 text-zinc-500 dark:text-slate-400 text-sm md:text-base leading-relaxed font-light">
                        Yes, most state police departments require landlords to submit tenant verification forms (online or at the local police station) for security and documentation.
                    </div>
                </details>
            </div>
        </div>

        {{-- Footer Internal Links --}}
        @include('partials.seo-links', ['city' => null, 'type' => 'house', 'typeDisplay' => 'Rental'])

    </div>
</div>
@endsection
