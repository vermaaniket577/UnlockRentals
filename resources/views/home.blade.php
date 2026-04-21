@extends('layouts.app')

@section('title', 'UnlockRentals - Find Your Perfect Rental Property')
@section('meta_description', 'Discover houses and shops for rent. Browse verified listings from trusted property owners.')

@section('content')

{{-- Hero Section --}}
{{-- Hero Section --}}
<section class="relative overflow-hidden pt-32 pb-24 lg:pt-48 lg:pb-36" id="hero-section">
    {{-- Background Effects --}}
    <div class="absolute inset-0">
        <div class="absolute top-0 right-0 w-[800px] h-full "></div>
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-[#2563EB]/10 rounded-full blur-[100px] animate-pulse"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row items-center gap-12 xl:gap-20">
            
            {{-- Left Content --}}
            <div class="flex-[1.2] w-full lg:max-w-[45rem] xl:max-w-[50rem] z-10">
                <h1 class="text-4xl sm:text-5xl lg:text-[4.5rem] font-extrabold tracking-tight text-zinc-900 leading-[1.1] mb-6">
                    {{ $site_settings['hero_title_1'] ?? 'Find Your' }} 
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#2563EB] to-cyan-500">{{ $site_settings['hero_title_2'] ?? 'Perfect Nest' }}</span>
                </h1>
                
                <p class="text-lg text-zinc-500 mb-10 leading-relaxed pr-8">
                    {{ $site_settings['hero_description'] ?? 'Discover thousands of premium houses, cozy apartments, and practical shop spaces. Connect directly with owners and settle in effortlessly.' }}
                </p>

                {{-- The Glassmorphism Search Bar --}}
                <form action="{{ route('properties.index') }}" method="GET" class="relative">
                    <div class="bg-white/80 backdrop-blur-xl border border-stone-200/60 rounded-sm p-4 sm:p-5 shadow-2xl shadow-stone-200/50">
                        <div class="flex flex-col sm:flex-row items-end gap-5">
                            
                            {{-- Target Location --}}
                            <div class="flex-1 w-full relative">
                                <label class="block text-xs font-bold text-zinc-500 uppercase tracking-wider mb-2 ml-1">Location</label>
                                <div class="relative">
                                    <i class="ph ph-map-pin absolute left-4 top-1/2 -translate-y-1/2 text-[#2563EB] text-lg"></i>
                                    <input type="text" name="search" placeholder="City or area..." 
                                           class="w-full bg-white border border-stone-200 rounded-sm py-3.5 pl-12 pr-4 text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]/60 focus:ring-1 focus:ring-[#2563EB]/30 transition-all placeholder:text-zinc-400 shadow-sm shadow-stone-100/50">
                                </div>
                            </div>

                            {{-- Property Type --}}
                            <div class="flex-1 w-full relative">
                                <label class="block text-xs font-bold text-zinc-500 uppercase tracking-wider mb-2 ml-1">Type</label>
                                <div class="relative">
                                    <i class="ph ph-house absolute left-4 top-1/2 -translate-y-1/2 text-[#2563EB] text-lg"></i>
                                    <select name="type" class="w-full bg-white border border-stone-200 rounded-sm py-3.5 pl-12 pr-10 text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]/60 focus:ring-1 focus:ring-[#2563EB]/30 appearance-none cursor-pointer transition-all shadow-sm shadow-stone-100/50">
                                        <option value="" class="bg-white">All Types</option>
                                        <option value="house" class="bg-white">House</option>
                                        <option value="shop" class="bg-white">Shop Space</option>
                                    </select>
                                    <i class="ph ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-[#2563EB] pointer-events-none"></i>
                                </div>
                            </div>

                            {{-- Budget --}}
                            <div class="flex-1 w-full relative">
                                <label class="block text-xs font-bold text-zinc-500 uppercase tracking-wider mb-2 ml-1">Max Budget</label>
                                <div class="relative">
                                    <i class="ph ph-currency-inr absolute left-4 top-1/2 -translate-y-1/2 text-[#2563EB] text-lg"></i>
                                    <input type="number" name="max_price" placeholder="Amount..." 
                                           class="w-full bg-white border border-stone-200 rounded-sm py-3.5 pl-12 pr-4 text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]/60 focus:ring-1 focus:ring-[#2563EB]/30 transition-all placeholder:text-zinc-400 shadow-sm shadow-stone-100/50">
                                </div>
                            </div>

                            {{-- Search Button CTA --}}
                            <div class="w-full sm:w-auto mt-4 sm:mt-0">
                                <button type="submit" class="w-full sm:w-auto px-10 py-3.5 bg-[#2563EB] hover:bg-[#1D4ED8] text-white font-medium text-sm tracking-wide rounded-sm shadow-lg shadow-[#2563EB]/20 hover:shadow-[#2563EB]/40 transition-all flex items-center justify-center gap-2">
                                    <i class="ph ph-magnifying-glass text-lg"></i>
                                    <span>Search</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                {{-- Social Proof --}}
                <div class="mt-10 flex flex-col sm:flex-row items-start sm:items-center gap-4 animate-fade-in delay-200">
                    <div class="flex -space-x-3">
                        <img class="w-10 h-10 rounded-full border-2 border-white object-cover" src="https://i.pravatar.cc/100?img=1" alt="User Avatar">
                        <img class="w-10 h-10 rounded-full border-2 border-white object-cover" src="https://i.pravatar.cc/100?img=2" alt="User Avatar">
                        <img class="w-10 h-10 rounded-full border-2 border-white object-cover" src="https://i.pravatar.cc/100?img=5" alt="User Avatar">
                        <img class="w-10 h-10 rounded-full border-2 border-white object-cover" src="https://i.pravatar.cc/100?img=8" alt="User Avatar">
                        <div class="w-10 h-10 rounded-full border-2 border-white bg-stone-100 flex items-center justify-center text-xs font-medium text-zinc-900 backdrop-blur-sm">
                            +2k
                        </div>
                    </div>
                    <div class="text-sm text-zinc-500">
                        <span class="text-zinc-900 font-semibold">Trusted by 10,000+</span> renters & owners daily.
                    </div>
                </div>
            </div>

            {{-- Right Image --}}
            <div class="flex-[0.8] w-full relative group perspective-1000 hidden lg:block">
                <div class="absolute inset-0 bg-[#2563EB]/10 rounded-sm blur-[40px] opacity-40 group-hover:opacity-60 transition-opacity duration-700"></div>
                <div class="relative h-[450px] lg:h-[600px] w-full rounded-sm overflow-hidden shadow-2xl border border-stone-200/50 transform transition-transform duration-700 group-hover:scale-[1.02]">
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-50 via-gray-50/20 to-transparent z-10"></div>
                    <img src="{{ asset('images/luxury_sunlit.png') }}" alt="Modern Apartment Interior" class="w-full h-full object-cover">
                    
                    {{-- Floating Image Cards --}}
                    <div class="absolute bottom-8 left-8 right-8 z-20 flex gap-4 overflow-hidden" style="mask-image: linear-gradient(to right, black 80%, transparent);">
                        <div class="bg-white/80 backdrop-blur-md px-4 py-3 rounded-sm border border-stone-200/50 flex items-center gap-3">
                            <i class="ph-fill ph-check-circle text-emerald-400 text-xl"></i>
                            <div>
                                <p class="text-zinc-900 text-sm font-medium">Verified Listings</p>
                            </div>
                        </div>
                        <div class="bg-white/80 backdrop-blur-md px-4 py-3 rounded-sm border border-stone-200/50 flex items-center gap-3">
                            <i class="ph-fill ph-star text-amber-400 text-xl"></i>
                            <div>
                                <p class="text-zinc-900 text-sm font-medium">Top Quality</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</section>

{{-- Categories Section --}}
<section class="py-16 lg:py-20" id="categories-section">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl lg:text-4xl font-medium text-zinc-900 mb-4">Browse by Category</h2>
            <p class="text-zinc-500 max-w-xl mx-auto">Explore our curated categories to find the perfect space that matches your needs.</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            @forelse($categories as $category)
            <a href="{{ route('properties.index', ['category' => $category->id]) }}"
               class="group p-6 bg-stone-50 border border-stone-200/50 rounded-sm hover:border-[#2563EB]/50 hover:bg-[#2563EB]/10 transition-all duration-300 text-center"
               id="category-{{ $category->slug }}">
                <div class="w-14 h-14 bg-gradient-to-br from-[#2563EB]/10 to-[#2563EB]/10 rounded-sm flex items-center justify-center mx-auto mb-4 group-hover:from-blue-600/30 group-hover:to-indigo-600/30 transition-all">
                    <i class="ph ph-{{ $category->icon ?: 'buildings' }} text-2xl text-[#2563EB]"></i>
                </div>
                <h3 class="text-sm font-semibold text-zinc-900 mb-1 group-hover:text-[#2563EB] transition-colors">{{ $category->name }}</h3>
                <p class="text-xs text-zinc-500">{{ $category->properties_count }} {{ Str::plural('listing', $category->properties_count) }}</p>
            </a>
            @empty
            <p class="col-span-full text-center text-zinc-500 py-8">Categories coming soon.</p>
            @endforelse
        </div>
    </div>
</section>

{{-- Featured Properties --}}
@if($featuredProperties->count() > 0)
<section class="py-16 lg:py-20" id="featured-section">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-12">
            <div>
                <div class="flex items-center gap-2 text-amber-400 text-sm font-medium mb-2">
                    <i class="ph ph-star"></i>
                    <span>Hand-picked for you</span>
                </div>
                <h2 class="text-3xl lg:text-4xl font-medium text-zinc-900">Featured Properties</h2>
            </div>
            <a href="{{ route('properties.index') }}" class="hidden md:flex items-center gap-2 text-sm text-[#2563EB] hover:text-[#2563EB] font-medium transition-colors">
                View All <i class="ph ph-arrow-right"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($featuredProperties as $property)
                @include('components.property-card', ['property' => $property])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Latest Properties --}}
<section class="py-16 lg:py-20" id="latest-section">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-12">
            <div>
                <div class="flex items-center gap-2 text-emerald-400 text-sm font-medium mb-2">
                    <i class="ph ph-clock"></i>
                    <span>Just added</span>
                </div>
                <h2 class="text-3xl lg:text-4xl font-medium text-zinc-900">Latest Listings</h2>
            </div>
            <a href="{{ route('properties.index') }}" class="hidden md:flex items-center gap-2 text-sm text-[#2563EB] hover:text-[#2563EB] font-medium transition-colors">
                Browse All <i class="ph ph-arrow-right"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($latestProperties as $property)
                @include('components.property-card', ['property' => $property])
            @empty
                <div class="col-span-full text-center py-16">
                    <i class="ph ph-house text-5xl text-gray-700 mb-4"></i>
                    <h3 class="text-xl font-semibold text-zinc-500 mb-2">No Properties Yet</h3>
                    <p class="text-zinc-500 mb-6">Be the first to list your property on UnlockRentals.</p>
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-[#2563EB] text-white text-sm font-medium tracking-wide rounded-sm shadow-sm transition-all hover:bg-[#1D4ED8] hover:shadow-[#2563EB]/30">
                        <i class="ph ph-plus-circle text-lg"></i> List a Property
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- CTA Section --}}
<section class="py-16 lg:py-24" id="cta-section">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative overflow-hidden bg-gradient-to-br from-blue-100/50 via-white to-indigo-100/50 border border-stone-200/50 rounded-sm p-12 lg:p-20 text-center">
            {{-- Glow --}}
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-96 h-48 bg-[#2563EB]/10 blur-3xl rounded-full"></div>

            <div class="relative">
                <h2 class="text-3xl lg:text-5xl font-serif font-light tracking-wide text-zinc-900 mb-6">
                    Ready to Find Your
                    <span class="text-[#2563EB] font-serif font-light italic">Dream Space?</span>
                </h2>
                <p class="text-lg text-zinc-500 max-w-xl mx-auto mb-10">
                    Whether you're looking for a cozy house or a prime shop location, we've got you covered.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('properties.index') }}" class="px-8 py-4 bg-[#2563EB] hover:bg-[#1D4ED8] text-white font-medium tracking-wide rounded-sm shadow-sm shadow-[#2563EB]/20 hover:shadow-[#2563EB]/40 transition-all" id="cta-browse">
                        Browse Properties
                    </a>
                    <a href="{{ route('register') }}" class="px-8 py-4 border border-[#2563EB] text-[#2563EB] hover:bg-[#2563EB]/5 font-medium tracking-wide rounded-sm transition-all" id="cta-register">
                        List Your Property
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Footer / Social Media --}}
<footer class="bg-white border-t border-stone-100 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-6">
        <x-brand-logo
            href="{{ route('home') }}"
            imageClass="h-9 w-auto"
            textClass="text-lg font-bold tracking-tight text-zinc-900"
            accentClass="text-[#2563EB]"
        />
        
        <p class="text-sm text-zinc-500">{{ $site_settings['footer_text'] ?? '© 2026 UnlockRentals. All rights reserved.' }}</p>

        {{-- Social Media Cards --}}
        <div class="flex gap-4">
            @if(!empty($site_settings['social_facebook']))
            <a href="{{ $site_settings['social_facebook'] }}" target="_blank" class="w-10 h-10 rounded-full border border-stone-200 flex items-center justify-center text-zinc-400 hover:text-[#2563EB] hover:border-[#2563EB] transition-all">
                <i class="ph ph-facebook-logo text-xl"></i>
            </a>
            @endif
            @if(!empty($site_settings['social_instagram']))
            <a href="{{ $site_settings['social_instagram'] }}" target="_blank" class="w-10 h-10 rounded-full border border-stone-200 flex items-center justify-center text-zinc-400 hover:text-[#2563EB] hover:border-[#2563EB] transition-all">
                <i class="ph ph-instagram-logo text-xl"></i>
            </a>
            @endif
            @if(!empty($site_settings['social_twitter']))
            <a href="{{ $site_settings['social_twitter'] }}" target="_blank" class="w-10 h-10 rounded-full border border-stone-200 flex items-center justify-center text-zinc-400 hover:text-[#2563EB] hover:border-[#2563EB] transition-all">
                <i class="ph ph-twitter-logo text-xl"></i>
            </a>
            @endif
            @if(!empty($site_settings['social_linkedin']))
            <a href="{{ $site_settings['social_linkedin'] }}" target="_blank" class="w-10 h-10 rounded-full border border-stone-200 flex items-center justify-center text-zinc-400 hover:text-[#2563EB] hover:border-[#2563EB] transition-all">
                <i class="ph ph-linkedin-logo text-xl"></i>
            </a>
            @endif
        </div>
    </div>
</footer>

@endsection
