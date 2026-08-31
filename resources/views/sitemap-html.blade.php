@extends('layouts.app')

@section('title', 'Site Map - UnlockRentals Property Directory & Navigation')
@section('meta_description', 'Explore the full UnlockRentals site map. Browse rental houses, apartments, PGs, shops, city landing pages, blog guides, and platform resources.')
@section('meta_keywords', 'unlockrentals sitemap, property directory, rental houses sitemap, flats for rent, rental properties india sitemap')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-950 py-10 sm:py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        
        {{-- Header Section --}}
        <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-50 dark:bg-blue-950/60 border border-blue-200 dark:border-blue-800 text-blue-700 dark:text-blue-300 text-xs font-black uppercase tracking-wider mb-4">
                <i class="ph-bold ph-tree-structure text-sm"></i>
                <span>Complete Website Index</span>
            </div>
            <h1 class="text-3xl sm:text-5xl font-black text-slate-900 dark:text-white tracking-tight leading-tight">
                UnlockRentals <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-400 dark:to-indigo-400">Site Map</span>
            </h1>
            <p class="mt-4 text-base sm:text-lg text-slate-600 dark:text-slate-400">
                Explore our full registry of rental properties, city guides, subscription plans, and resources. Fast, zero-brokerage access across India.
            </p>
            <div class="mt-4 inline-flex items-center gap-2 text-xs font-semibold text-slate-500 dark:text-slate-400">
                <a href="{{ url('/sitemap.xml') }}" target="_blank" class="inline-flex items-center gap-1.5 text-blue-600 dark:text-blue-400 hover:underline font-bold">
                    <i class="ph-bold ph-code text-sm"></i> View Raw XML Sitemap for Search Engines
                </a>
            </div>
        </div>

        {{-- Grid of Sitemap Sections --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            {{-- Section 1: Main Platform Pages --}}
            <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-8 border border-slate-200 dark:border-slate-800 shadow-lg hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 flex items-center justify-center text-2xl mb-6 shadow-inner">
                        <i class="ph-bold ph-compass"></i>
                    </div>
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-4 flex items-center justify-between">
                        <span>Core Platform Pages</span>
                        <span class="text-xs px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 font-semibold">Primary</span>
                    </h2>
                    <ul class="space-y-3 text-sm text-slate-600 dark:text-slate-300">
                        <li>
                            <a href="{{ route('home') }}" class="flex items-center gap-2 hover:text-blue-600 dark:hover:text-blue-400 transition-colors font-medium">
                                <i class="ph ph-house text-blue-500"></i> Homepage
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('properties.index') }}" class="flex items-center gap-2 hover:text-blue-600 dark:hover:text-blue-400 transition-colors font-medium">
                                <i class="ph ph-buildings text-blue-500"></i> All Rental Properties
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('properties.create') }}" class="flex items-center gap-2 hover:text-blue-600 dark:hover:text-blue-400 transition-colors font-medium">
                                <i class="ph ph-plus-circle text-blue-500"></i> Post Property Listing (Free)
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('plans.index') }}" class="flex items-center gap-2 hover:text-blue-600 dark:hover:text-blue-400 transition-colors font-medium">
                                <i class="ph ph-crown text-blue-500"></i> Membership Plans & Pricing
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/how-it-works') }}" class="flex items-center gap-2 hover:text-blue-600 dark:hover:text-blue-400 transition-colors font-medium">
                                <i class="ph ph-git-merge text-blue-500"></i> How It Works / Process
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/blog') }}" class="flex items-center gap-2 hover:text-blue-600 dark:hover:text-blue-400 transition-colors font-medium">
                                <i class="ph ph-newspaper text-blue-500"></i> Blog & Rental Guides
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/app') }}" class="flex items-center gap-2 hover:text-blue-600 dark:hover:text-blue-400 transition-colors font-medium">
                                <i class="ph ph-device-mobile text-blue-500"></i> Download Android / iOS App
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Section 2: Property Types & Categories --}}
            <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-8 border border-slate-200 dark:border-slate-800 shadow-lg hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-2xl mb-6 shadow-inner">
                        <i class="ph-bold ph-squares-four"></i>
                    </div>
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-4 flex items-center justify-between">
                        <span>Property Categories</span>
                        <span class="text-xs px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 font-semibold">Types</span>
                    </h2>
                    <ul class="space-y-3 text-sm text-slate-600 dark:text-slate-300">
                        <li>
                            <a href="{{ route('properties.index', ['type' => 'house']) }}" class="flex items-center gap-2 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors font-medium">
                                <i class="ph ph-house-line text-indigo-500"></i> Independent Houses & Villas
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('properties.index', ['type' => 'apartment']) }}" class="flex items-center gap-2 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors font-medium">
                                <i class="ph ph-buildings text-indigo-500"></i> Apartments & Multi-Story Flats
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('properties.index', ['type' => 'pg-hostel']) }}" class="flex items-center gap-2 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors font-medium">
                                <i class="ph ph-users text-indigo-500"></i> PG & Co-Living Accommodations
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('properties.index', ['type' => 'shop']) }}" class="flex items-center gap-2 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors font-medium">
                                <i class="ph ph-storefront text-indigo-500"></i> Commercial Shops & Retail Spaces
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('properties.index', ['availability' => 'unbooked']) }}" class="flex items-center gap-2 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors font-medium">
                                <i class="ph ph-check-circle text-emerald-500"></i> Immediately Available / Unbooked
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('properties.index', ['media' => 'video']) }}" class="flex items-center gap-2 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors font-medium">
                                <i class="ph ph-video-camera text-indigo-500"></i> Properties with Video Walkthroughs
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Section 3: Configurations & BHKs --}}
            <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-8 border border-slate-200 dark:border-slate-800 shadow-lg hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-amber-100 dark:bg-amber-900/50 text-amber-600 dark:text-amber-400 flex items-center justify-center text-2xl mb-6 shadow-inner">
                        <i class="ph-bold ph-bed"></i>
                    </div>
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-4 flex items-center justify-between">
                        <span>Room & BHK Setups</span>
                        <span class="text-xs px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 font-semibold">Bedrooms</span>
                    </h2>
                    <ul class="space-y-3 text-sm text-slate-600 dark:text-slate-300">
                        <li>
                            <a href="{{ route('properties.index', ['rooms' => '1rk']) }}" class="flex items-center gap-2 hover:text-amber-600 dark:hover:text-amber-400 transition-colors font-medium">
                                <i class="ph ph-door text-amber-500"></i> 1 RK Studio Rooms
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('properties.index', ['rooms' => '1bhk']) }}" class="flex items-center gap-2 hover:text-amber-600 dark:hover:text-amber-400 transition-colors font-medium">
                                <i class="ph ph-door text-amber-500"></i> 1 BHK Houses & Flats
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('properties.index', ['rooms' => '2bhk']) }}" class="flex items-center gap-2 hover:text-amber-600 dark:hover:text-amber-400 transition-colors font-medium">
                                <i class="ph ph-door text-amber-500"></i> 2 BHK Family Apartments
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('properties.index', ['rooms' => '3bhk']) }}" class="flex items-center gap-2 hover:text-amber-600 dark:hover:text-amber-400 transition-colors font-medium">
                                <i class="ph ph-door text-amber-500"></i> 3 BHK Spacious Homes
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('properties.index', ['rooms' => '4bhk-plus']) }}" class="flex items-center gap-2 hover:text-amber-600 dark:hover:text-amber-400 transition-colors font-medium">
                                <i class="ph ph-door text-amber-500"></i> 4+ BHK Luxury Suites & Villas
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('properties.index', ['price' => '0-20000']) }}" class="flex items-center gap-2 hover:text-amber-600 dark:hover:text-amber-400 transition-colors font-medium">
                                <i class="ph ph-currency-inr text-emerald-500"></i> Budget Homes (Under ₹20,000)
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Section 4: Top City Hubs & Programmatic Landing Pages --}}
            <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-8 border border-slate-200 dark:border-slate-800 shadow-lg hover:shadow-xl transition-all duration-300 md:col-span-2 lg:col-span-2 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-2xl mb-6 shadow-inner">
                        <i class="ph-bold ph-map-pin-line"></i>
                    </div>
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-4 flex items-center justify-between">
                        <span>Top Rental Cities & Popular SEO Pages</span>
                        <span class="text-xs px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 font-semibold">Location Hubs</span>
                    </h2>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 text-sm text-slate-600 dark:text-slate-300">
                        <a href="{{ url('/flat-for-rent-in-gurgaon') }}" class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 hover:text-emerald-600 dark:hover:text-emerald-400 transition-all font-medium flex items-center gap-2">
                            <i class="ph ph-buildings text-emerald-500"></i> Flats in Gurgaon
                        </a>
                        <a href="{{ url('/house-for-rent-in-gurgaon') }}" class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 hover:text-emerald-600 dark:hover:text-emerald-400 transition-all font-medium flex items-center gap-2">
                            <i class="ph ph-house text-emerald-500"></i> Houses in Gurgaon
                        </a>
                        <a href="{{ url('/room-for-rent-in-gurgaon') }}" class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 hover:text-emerald-600 dark:hover:text-emerald-400 transition-all font-medium flex items-center gap-2">
                            <i class="ph ph-door text-emerald-500"></i> Rooms in Gurgaon
                        </a>
                        <a href="{{ url('/pg-for-boys-in-gurgaon') }}" class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 hover:text-emerald-600 dark:hover:text-emerald-400 transition-all font-medium flex items-center gap-2">
                            <i class="ph ph-users text-emerald-500"></i> PG Boys in Gurgaon
                        </a>
                        <a href="{{ url('/pg-for-girls-in-gurgaon') }}" class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 hover:text-emerald-600 dark:hover:text-emerald-400 transition-all font-medium flex items-center gap-2">
                            <i class="ph ph-users text-emerald-500"></i> PG Girls in Gurgaon
                        </a>
                        <a href="{{ url('/flat-for-rent-in-noida') }}" class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 hover:text-emerald-600 dark:hover:text-emerald-400 transition-all font-medium flex items-center gap-2">
                            <i class="ph ph-buildings text-emerald-500"></i> Flats in Noida
                        </a>
                        <a href="{{ url('/house-for-rent-in-delhi') }}" class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 hover:text-emerald-600 dark:hover:text-emerald-400 transition-all font-medium flex items-center gap-2">
                            <i class="ph ph-house text-emerald-500"></i> Houses in Delhi
                        </a>
                        <a href="{{ url('/flat-for-rent-in-delhi') }}" class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 hover:text-emerald-600 dark:hover:text-emerald-400 transition-all font-medium flex items-center gap-2">
                            <i class="ph ph-buildings text-emerald-500"></i> Flats in Delhi
                        </a>
                        <a href="{{ url('/flat-for-rent-in-bangalore') }}" class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 hover:text-emerald-600 dark:hover:text-emerald-400 transition-all font-medium flex items-center gap-2">
                            <i class="ph ph-buildings text-emerald-500"></i> Flats in Bangalore
                        </a>
                        <a href="{{ url('/flat-for-rent-in-mumbai') }}" class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 hover:text-emerald-600 dark:hover:text-emerald-400 transition-all font-medium flex items-center gap-2">
                            <i class="ph ph-buildings text-emerald-500"></i> Flats in Mumbai
                        </a>
                        <a href="{{ url('/flat-for-rent-in-gurgaon-under-20000') }}" class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 hover:text-emerald-600 dark:hover:text-emerald-400 transition-all font-medium flex items-center gap-2">
                            <i class="ph ph-tag text-emerald-500"></i> Flats in Gurgaon Under 20k
                        </a>
                        <a href="{{ url('/room-for-rent-in-gurgaon-under-10000') }}" class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 hover:text-emerald-600 dark:hover:text-emerald-400 transition-all font-medium flex items-center gap-2">
                            <i class="ph ph-tag text-emerald-500"></i> Rooms in Gurgaon Under 10k
                        </a>
                    </div>
                </div>
            </div>

            {{-- Section 5: Legal & Support --}}
            <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-8 border border-slate-200 dark:border-slate-800 shadow-lg hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-rose-100 dark:bg-rose-900/50 text-rose-600 dark:text-rose-400 flex items-center justify-center text-2xl mb-6 shadow-inner">
                        <i class="ph-bold ph-shield-check"></i>
                    </div>
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-4 flex items-center justify-between">
                        <span>Legal & Support</span>
                        <span class="text-xs px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 font-semibold">Trust</span>
                    </h2>
                    <ul class="space-y-3 text-sm text-slate-600 dark:text-slate-300">
                        <li>
                            <a href="{{ route('privacy') }}" class="flex items-center gap-2 hover:text-rose-600 dark:hover:text-rose-400 transition-colors font-medium">
                                <i class="ph ph-lock text-rose-500"></i> Privacy Policy
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('terms') }}" class="flex items-center gap-2 hover:text-rose-600 dark:hover:text-rose-400 transition-colors font-medium">
                                <i class="ph ph-file-text text-rose-500"></i> Terms & Conditions
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('login') }}" class="flex items-center gap-2 hover:text-rose-600 dark:hover:text-rose-400 transition-colors font-medium">
                                <i class="ph ph-sign-in text-rose-500"></i> User & Landlord Login
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('register') }}" class="flex items-center gap-2 hover:text-rose-600 dark:hover:text-rose-400 transition-colors font-medium">
                                <i class="ph ph-user-plus text-rose-500"></i> Create Free Account
                            </a>
                        </li>
                        <li>
                            <a href="mailto:support@unlockrentals.com" class="flex items-center gap-2 hover:text-rose-600 dark:hover:text-rose-400 transition-colors font-medium">
                                <i class="ph ph-envelope text-rose-500"></i> Email Support
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

        </div>

        {{-- Recent Property Listings Grid --}}
        @if(isset($properties) && $properties->count() > 0)
        <div class="mt-16 bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-10 border border-slate-200 dark:border-slate-800 shadow-xl">
            <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-100 dark:border-slate-800">
                <div>
                    <h2 class="text-2xl font-black text-slate-900 dark:text-white">Active Verified Property Directory</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Direct links to current rental listings across India</p>
                </div>
                <a href="{{ route('properties.index') }}" class="hidden sm:inline-flex items-center gap-1 text-sm font-bold text-blue-600 dark:text-blue-400 hover:underline">
                    View All {{ $properties->count() }}+ Properties <i class="ph-bold ph-arrow-right"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($properties->take(32) as $property)
                <a href="{{ route('properties.show', $property) }}" class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-800/50 hover:bg-blue-50 dark:hover:bg-blue-950/40 border border-slate-200/60 dark:border-slate-700/60 hover:border-blue-300 dark:hover:border-blue-700 transition-all group block">
                    <div class="font-bold text-slate-900 dark:text-white text-sm truncate group-hover:text-blue-600 dark:group-hover:text-blue-400">
                        {{ $property->title }}
                    </div>
                    <div class="flex items-center justify-between mt-2 text-xs text-slate-500 dark:text-slate-400">
                        <span class="truncate max-w-[120px]"><i class="ph ph-map-pin text-blue-500"></i> {{ $property->locality ?? $property->location }}</span>
                        <span class="font-black text-blue-600 dark:text-blue-400">₹{{ number_format($property->price) }}/mo</span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Real Estate Guides & Articles --}}
        @if(isset($blogs) && $blogs->count() > 0)
        <div class="mt-12 bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-10 border border-slate-200 dark:border-slate-800 shadow-xl">
            <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-100 dark:border-slate-800">
                <div>
                    <h2 class="text-2xl font-black text-slate-900 dark:text-white">Rental Guides & Insights</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Tenant tips, owner checklists, and real estate market updates</p>
                </div>
                <a href="{{ url('/blog') }}" class="hidden sm:inline-flex items-center gap-1 text-sm font-bold text-blue-600 dark:text-blue-400 hover:underline">
                    View All Articles <i class="ph-bold ph-arrow-right"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                @foreach($blogs as $blog)
                <a href="{{ url('/blog/' . $blog->slug) }}" class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 hover:bg-blue-50 dark:hover:bg-blue-950/40 border border-slate-200/60 dark:border-slate-700/60 hover:border-blue-300 dark:hover:border-blue-700 transition-all group block">
                    <div class="font-bold text-slate-900 dark:text-white text-sm line-clamp-1 group-hover:text-blue-600 dark:group-hover:text-blue-400">
                        {{ $blog->title }}
                    </div>
                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-1 line-clamp-2">
                        {{ $blog->meta_description ?? Str::limit(strip_tags($blog->content ?? ''), 90) }}
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>
@endsection
