@extends('layouts.app')

@section('title', 'Local Professionals & Home Services | UnlockRentals')
@section('meta_description', 'Find trusted local professionals for home, office & rental property maintenance near you. Plumbers, electricians, carpenters, painters, and more on UnlockRentals.')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-900 pb-20 pt-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Breadcrumbs --}}
        <nav class="flex items-center text-xs text-slate-500 dark:text-slate-400 mb-6 gap-2" aria-label="Breadcrumb">
            <a href="{{ url('/') }}" class="hover:text-blue-600 transition-colors">Home</a>
            <i class="ph ph-caret-right text-[10px]"></i>
            <span class="text-slate-900 dark:text-white font-medium">Local Professionals</span>
            @if(request('category'))
                <i class="ph ph-caret-right text-[10px]"></i>
                <span class="text-blue-600 capitalize font-semibold">{{ str_replace('-', ' ', request('category')) }}</span>
            @endif
            @if(request('location'))
                <i class="ph ph-caret-right text-[10px]"></i>
                <span class="text-slate-700 dark:text-slate-300 font-semibold">{{ request('location') }}</span>
            @endif
        </nav>

        {{-- Hero Header & Search Banner --}}
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-blue-700 via-indigo-700 to-slate-900 text-white p-6 sm:p-10 lg:p-12 mb-10 shadow-xl shadow-blue-900/10">
            <div class="relative z-10 max-w-3xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-xs font-semibold uppercase tracking-wider text-blue-100 mb-4">
                    <i class="ph-bold ph-seal-check text-blue-300"></i>
                    <span>Verified Home & Office Services</span>
                </div>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight leading-tight text-white mb-4">
                    Find Local Professionals <br class="hidden sm:inline">Near You
                </h1>
                <p class="text-sm sm:text-base text-blue-100/90 leading-relaxed mb-8 max-w-2xl">
                    Connect directly with trusted electricians, plumbers, carpenters, painters and skilled home experts. Zero brokerage, verified profiles, direct Call and WhatsApp.
                </p>

                {{-- Interactive Search Bar --}}
                <form action="{{ route('services.index') }}" method="GET" class="bg-white dark:bg-slate-800 rounded-2xl p-2 sm:p-2.5 shadow-2xl flex flex-col md:flex-row gap-2 border border-slate-200/50 dark:border-slate-700">
                    {{-- Service Selector --}}
                    <div class="flex-1 flex items-center gap-2.5 px-3 py-2 border-b md:border-b-0 md:border-r border-slate-200 dark:border-slate-700">
                        <i class="ph-bold ph-wrench text-xl text-blue-600"></i>
                        <div class="flex-1">
                            <label for="search-category" class="block text-[10px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Service Category</label>
                            <select name="category" id="search-category" class="w-full bg-transparent text-sm font-semibold text-slate-900 dark:text-white border-0 p-0 focus:ring-0 cursor-pointer">
                                <option value="" class="dark:bg-slate-800">All Professional Services</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }} class="dark:bg-slate-800">
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Location Input --}}
                    <div class="flex-1 flex items-center gap-2.5 px-3 py-2 border-b md:border-b-0 md:border-r border-slate-200 dark:border-slate-700">
                        <i class="ph-bold ph-map-pin text-xl text-blue-600"></i>
                        <div class="flex-1">
                            <label for="search-location" class="block text-[10px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">City / Locality / Pincode</label>
                            <input type="text" name="location" id="search-location" value="{{ request('location') }}" placeholder="e.g. Gurgaon, Sector 14, 122001" class="w-full bg-transparent text-sm font-semibold text-slate-900 dark:text-white border-0 p-0 focus:ring-0 placeholder:text-slate-400">
                        </div>
                    </div>

                    {{-- Geolocation Hidden Inputs & Action --}}
                    <input type="hidden" name="lat" id="geo-lat" value="{{ request('lat') }}">
                    <input type="hidden" name="lng" id="geo-lng" value="{{ request('lng') }}">

                    <div class="flex items-center gap-2 p-1">
                        <button type="button" id="btn-use-geo" class="inline-flex items-center gap-1.5 px-3 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors whitespace-nowrap" title="Detect GPS Location">
                            <i class="ph-bold ph-crosshair text-blue-600"></i>
                            <span class="hidden sm:inline">Near Me</span>
                        </button>

                        <button type="submit" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-md shadow-blue-600/30 transition-all hover:scale-[1.02] active:scale-95 whitespace-nowrap">
                            <i class="ph-bold ph-magnifying-glass text-base"></i>
                            <span>Search</span>
                        </button>
                    </div>
                </form>

                {{-- Status message for Geolocation --}}
                <div id="geo-status-msg" class="hidden text-xs text-amber-300 mt-2 flex items-center gap-1.5">
                    <i class="ph ph-info"></i>
                    <span id="geo-status-text"></span>
                </div>
            </div>

            {{-- Decorative pattern --}}
            <div class="absolute -right-16 -bottom-16 w-80 h-80 rounded-full bg-white/5 blur-2xl pointer-events-none"></div>
            <div class="absolute right-10 top-10 hidden lg:block opacity-20 pointer-events-none">
                <i class="ph-duotone ph-toolbox text-[180px]"></i>
            </div>
        </div>

        {{-- Popular Category Cards Bar --}}
        <div class="mb-10">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="ph-bold ph-sparkle text-blue-600"></i>
                    Popular Categories
                </h2>
                <a href="{{ route('services.register') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1">
                    <span>Are you a service provider? List FREE</span>
                    <i class="ph ph-arrow-right text-xs"></i>
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
                @foreach($categories as $category)
                    <a href="{{ route('services.category', $category->slug) }}" class="group bg-white dark:bg-slate-800 rounded-2xl p-3.5 border border-slate-200/80 dark:border-slate-700/80 hover:border-blue-500 dark:hover:border-blue-500 hover:shadow-lg hover:shadow-blue-500/5 transition-all text-center flex flex-col items-center justify-center gap-2 {{ request('category') === $category->slug ? 'ring-2 ring-blue-600 bg-blue-50/30 dark:bg-blue-900/20' : '' }}">
                        <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-slate-700 text-blue-600 dark:text-blue-400 flex items-center justify-center group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all shadow-xs">
                            <i class="{{ $category->icon ?: 'ph-bold ph-wrench' }} text-2xl"></i>
                        </div>
                        <span class="text-xs font-bold text-slate-800 dark:text-slate-200 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors line-clamp-1">
                            {{ $category->name }}
                        </span>
                        @if($category->professionals_count > 0)
                            <span class="text-[10px] font-semibold text-slate-400 dark:text-slate-400">
                                {{ $category->professionals_count }} {{ Str::plural('Expert', $category->professionals_count) }}
                            </span>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Main Layout: Filters Sidebar + Results Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

            {{-- Filters Sidebar (Desktop) --}}
            <div class="hidden lg:block lg:col-span-1 space-y-6">
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200/80 dark:border-slate-700/80 shadow-sm sticky top-24">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-700">
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <i class="ph-bold ph-sliders text-blue-600"></i>
                            Filters
                        </h3>
                        <a href="{{ route('services.index') }}" class="text-[11px] font-semibold text-slate-500 hover:text-blue-600">Reset All</a>
                    </div>

                    <form action="{{ route('services.index') }}" method="GET" id="sidebar-filter-form" class="space-y-5 pt-4">
                        @if(request('location'))
                            <input type="hidden" name="location" value="{{ request('location') }}">
                        @endif
                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        @if(request('lat'))
                            <input type="hidden" name="lat" value="{{ request('lat') }}">
                        @endif
                        @if(request('lng'))
                            <input type="hidden" name="lng" value="{{ request('lng') }}">
                        @endif

                        {{-- Sort Selector --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Sort Results</label>
                            <select name="sort" onchange="this.form.submit()" class="w-full text-xs font-medium rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-800 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                                <option value="recommended" {{ request('sort') == 'recommended' ? 'selected' : '' }}>Recommended</option>
                                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Highest Rated (⭐)</option>
                                <option value="experience" {{ request('sort') == 'experience' ? 'selected' : '' }}>Most Experienced</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Starting Price: Low to High</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Starting Price: High to Low</option>
                                <option value="nearest" {{ request('sort') == 'nearest' ? 'selected' : '' }}>Nearest to me</option>
                            </select>
                        </div>

                        {{-- Service Verification & Availability Toggles --}}
                        <div class="space-y-2.5 pt-2 border-t border-slate-100 dark:border-slate-700">
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Preferences</label>

                            <label class="flex items-center gap-2.5 text-xs font-medium text-slate-700 dark:text-slate-300 cursor-pointer">
                                <input type="checkbox" name="verified" value="1" {{ request('verified') ? 'checked' : '' }} onchange="this.form.submit()" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                <span class="flex items-center gap-1">
                                    <i class="ph-fill ph-seal-check text-blue-600"></i>
                                    Verified Only
                                </span>
                            </label>

                            <label class="flex items-center gap-2.5 text-xs font-medium text-slate-700 dark:text-slate-300 cursor-pointer">
                                <input type="checkbox" name="available_today" value="1" {{ request('available_today') ? 'checked' : '' }} onchange="this.form.submit()" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                <span>⚡ Available Today</span>
                            </label>

                            <label class="flex items-center gap-2.5 text-xs font-medium text-slate-700 dark:text-slate-300 cursor-pointer">
                                <input type="checkbox" name="emergency" value="1" {{ request('emergency') ? 'checked' : '' }} onchange="this.form.submit()" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                <span>🚨 24x7 Emergency Service</span>
                            </label>

                            <label class="flex items-center gap-2.5 text-xs font-medium text-slate-700 dark:text-slate-300 cursor-pointer">
                                <input type="checkbox" name="home_visit" value="1" {{ request('home_visit') ? 'checked' : '' }} onchange="this.form.submit()" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                <span>🏠 Home Visit Available</span>
                            </label>
                        </div>

                        {{-- Minimum Rating Filter --}}
                        <div class="pt-3 border-t border-slate-100 dark:border-slate-700">
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Customer Rating</label>
                            <div class="space-y-1.5">
                                @foreach([4 => '4.0 & above', 3 => '3.0 & above', 2 => '2.0 & above'] as $val => $label)
                                    <label class="flex items-center gap-2 text-xs text-slate-700 dark:text-slate-300 cursor-pointer">
                                        <input type="radio" name="min_rating" value="{{ $val }}" {{ request('min_rating') == $val ? 'checked' : '' }} onchange="this.form.submit()" class="text-blue-600 focus:ring-blue-500">
                                        <span class="flex items-center gap-1 text-amber-500 font-bold">
                                            @for($i=1; $i<=$val; $i++) <i class="ph-fill ph-star text-xs"></i> @endfor
                                        </span>
                                        <span class="text-slate-500 text-[11px]">({{ $label }})</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Minimum Experience --}}
                        <div class="pt-3 border-t border-slate-100 dark:border-slate-700">
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Experience</label>
                            <select name="min_exp" onchange="this.form.submit()" class="w-full text-xs font-medium rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-800 dark:text-white">
                                <option value="">Any Experience</option>
                                <option value="2" {{ request('min_exp') == '2' ? 'selected' : '' }}>2+ Years</option>
                                <option value="5" {{ request('min_exp') == '5' ? 'selected' : '' }}>5+ Years</option>
                                <option value="10" {{ request('min_exp') == '10' ? 'selected' : '' }}>10+ Years</option>
                            </select>
                        </div>
                    </form>

                    {{-- CTA Box in Sidebar --}}
                    <div class="mt-6 p-4 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 text-white text-center">
                        <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center mx-auto mb-2 text-white">
                            <i class="ph-bold ph-identification-card text-xl"></i>
                        </div>
                        <h4 class="text-xs font-black mb-1">Are you a Professional?</h4>
                        <p class="text-[11px] text-blue-100 mb-3">Join UnlockRentals today. Build your profile and get direct customer inquiries for FREE.</p>
                        <a href="{{ route('services.register') }}" class="block w-full py-2.5 px-3 bg-white text-blue-600 hover:bg-blue-50 text-xs font-black rounded-lg shadow-sm transition-all" style="color: #2563eb !important; -webkit-text-fill-color: #2563eb !important; background-color: #ffffff !important;">
                            List Your Service FREE
                        </a>
                    </div>
                </div>
            </div>

            {{-- Results Column --}}
            <div class="lg:col-span-3">

                {{-- Header Result Count & Quick Active Filter Pills --}}
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between pb-4 mb-6 border-b border-slate-200 dark:border-slate-800 gap-3">
                    <div>
                        <h2 class="text-xl font-black text-slate-900 dark:text-white">
                            @if(request('category'))
                                {{ ucwords(str_replace('-', ' ', request('category'))) }} Services
                            @else
                                Local Professionals & Services
                            @endif
                            @if(request('location'))
                                <span class="text-blue-600">in {{ request('location') }}</span>
                            @endif
                        </h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            Showing {{ $professionals->firstItem() ?? 0 }}-{{ $professionals->lastItem() ?? 0 }} of {{ $professionals->total() }} verified service providers
                        </p>
                    </div>

                    {{-- Mobile Filter Button --}}
                    <div class="lg:hidden w-full sm:w-auto flex items-center gap-2">
                        <button type="button" onclick="document.getElementById('mobile-filter-modal').classList.remove('hidden')" class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-800 dark:text-white shadow-xs">
                            <i class="ph-bold ph-sliders"></i>
                            <span>Filter & Sort</span>
                        </button>
                    </div>
                </div>

                {{-- Professionals Cards Listing --}}
                @if($professionals->count() > 0)
                    <div class="space-y-4">
                        @foreach($professionals as $prof)
                            <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 sm:p-5 border border-slate-200/80 dark:border-slate-700/80 hover:shadow-md hover:border-blue-400 dark:hover:border-blue-500 transition-all flex flex-col sm:flex-row gap-5 relative group">
                                
                                {{-- Left Column: Avatar & Quick Badges --}}
                                <div class="flex-shrink-0 flex sm:flex-col items-center sm:items-start gap-4 sm:gap-2">
                                    <div class="relative w-20 h-20 sm:w-24 sm:h-24 rounded-2xl overflow-hidden bg-slate-100 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 flex-shrink-0">
                                        <img src="{{ $prof->profile_photo_url }}" alt="{{ $prof->business_name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                                        @if($prof->isVerified())
                                            <span class="absolute bottom-1 right-1 w-5 h-5 rounded-full bg-blue-600 text-white flex items-center justify-center shadow-xs" title="Verified Professional">
                                                <i class="ph-bold ph-check text-xs"></i>
                                            </span>
                                        @endif
                                    </div>

                                    @if($prof->years_experience > 0)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-700 text-[11px] font-bold text-slate-700 dark:text-slate-300">
                                            <i class="ph-bold ph-briefcase text-blue-600"></i>
                                            {{ $prof->years_experience }} yrs exp
                                        </span>
                                    @endif
                                </div>

                                {{-- Middle Column: Details & Services Offered --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-wrap items-center gap-2 mb-1">
                                        <a href="{{ route('services.show', [$prof->category->slug, Str::slug($prof->city ?: 'india'), $prof->slug]) }}" class="text-base sm:text-lg font-black text-slate-900 dark:text-white hover:text-blue-600 transition-colors line-clamp-1">
                                            {{ $prof->business_name }}
                                        </a>

                                        @if($prof->isVerified())
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-[11px] font-bold">
                                                <i class="ph-fill ph-seal-check text-blue-600"></i>
                                                Verified
                                            </span>
                                        @endif

                                        @if($prof->featured)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 text-[11px] font-bold">
                                                <i class="ph-fill ph-star text-amber-500"></i>
                                                Featured
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Category & Rating Line --}}
                                    <div class="flex flex-wrap items-center gap-3 text-xs text-slate-500 dark:text-slate-400 mb-2">
                                        <span class="font-bold text-blue-600 dark:text-blue-400">{{ $prof->category->name }}</span>
                                        <span>•</span>
                                        
                                        {{-- Rating Display --}}
                                        <div class="flex items-center gap-1">
                                            <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 font-black text-xs">
                                                <i class="ph-fill ph-star text-amber-500"></i>
                                                {{ number_format($prof->average_rating, 1) }}
                                            </span>
                                            <span class="text-[11px] text-slate-400">({{ $prof->review_count }} {{ Str::plural('review', $prof->review_count) }})</span>
                                        </div>

                                        {{-- Location Pin --}}
                                        <span>•</span>
                                        <div class="flex items-center gap-1 text-slate-600 dark:text-slate-300">
                                            <i class="ph-bold ph-map-pin text-slate-400"></i>
                                            <span>{{ $prof->locality ? $prof->locality . ', ' : '' }}{{ $prof->city }}</span>
                                        </div>

                                        @if(isset($prof->distance))
                                            <span>•</span>
                                            <span class="text-blue-600 font-bold">{{ round($prof->distance, 1) }} km away</span>
                                        @endif
                                    </div>

                                    {{-- Description snippet --}}
                                    <p class="text-xs text-slate-600 dark:text-slate-300 line-clamp-2 mb-3 leading-relaxed">
                                        {{ $prof->description }}
                                    </p>

                                    {{-- Sub-services Tags --}}
                                    @if($prof->services->count() > 0)
                                        <div class="flex flex-wrap gap-1.5 mb-3">
                                            @foreach($prof->services->take(4) as $serv)
                                                <span class="px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-700/80 text-[11px] font-medium text-slate-700 dark:text-slate-300">
                                                    {{ $serv->name }}
                                                </span>
                                            @endforeach
                                            @if($prof->services->count() > 4)
                                                <span class="px-2 py-0.5 rounded-md bg-slate-50 dark:bg-slate-800 text-[10px] text-slate-400 font-semibold">
                                                    +{{ $prof->services->count() - 4 }} more
                                                </span>
                                            @endif
                                        </div>
                                    @endif

                                    {{-- Feature Highlights (Home visit, available today, emergency) --}}
                                    <div class="flex flex-wrap items-center gap-3 text-[11px] font-semibold text-slate-500 dark:text-slate-400">
                                        @if($prof->home_visit)
                                            <span class="flex items-center gap-1 text-emerald-600 dark:text-emerald-400">
                                                <i class="ph-bold ph-check text-xs"></i> Home Visit
                                            </span>
                                        @endif
                                        @if($prof->available_today)
                                            <span class="flex items-center gap-1 text-blue-600 dark:text-blue-400">
                                                <i class="ph-bold ph-lightning text-xs"></i> Available Today
                                            </span>
                                        @endif
                                        @if($prof->emergency_service)
                                            <span class="flex items-center gap-1 text-rose-600 dark:text-rose-400">
                                                <i class="ph-bold ph-shield-warning text-xs"></i> 24x7 Emergency
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Right Column: Pricing & Contact Action Buttons --}}
                                <div class="flex-shrink-0 flex sm:flex-col items-center sm:items-end justify-between sm:justify-center border-t sm:border-t-0 sm:border-l border-slate-100 dark:border-slate-700 pt-3 sm:pt-0 sm:pl-5 gap-3">
                                    <div class="text-left sm:text-right">
                                        <span class="block text-[10px] uppercase font-bold text-slate-400">Pricing</span>
                                        <div class="text-sm sm:text-base font-black text-slate-900 dark:text-white">
                                            {{ $prof->formatted_price }}
                                        </div>
                                    </div>

                                    <div class="flex items-center sm:flex-col gap-2 w-full sm:w-36">
                                        {{-- Call Now Button --}}
                                        <a href="{{ route('professionals.click', [$prof->id, 'call']) }}" class="flex-1 sm:w-full inline-flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-sm transition-all active:scale-95 whitespace-nowrap">
                                            <i class="ph-bold ph-phone-call text-sm"></i>
                                            <span>Call Now</span>
                                        </a>

                                        {{-- WhatsApp Button --}}
                                        <a href="{{ route('professionals.click', [$prof->id, 'whatsapp']) }}" target="_blank" rel="noopener" class="flex-1 sm:w-full inline-flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl bg-[#25D366] hover:bg-[#20ba59] text-white text-xs font-bold shadow-sm transition-all active:scale-95 whitespace-nowrap">
                                            <i class="ph-bold ph-whatsapp-logo text-sm"></i>
                                            <span>WhatsApp</span>
                                        </a>

                                        {{-- View Profile Button --}}
                                        <a href="{{ route('services.show', [$prof->category->slug, Str::slug($prof->city ?: 'india'), $prof->slug]) }}" class="hidden sm:inline-flex w-full items-center justify-center gap-1 py-1.5 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 text-xs font-bold transition-all">
                                            <span>View Profile</span>
                                            <i class="ph ph-arrow-right text-xs"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-8">
                        {{ $professionals->links() }}
                    </div>

                @else
                    {{-- Empty State (No Professionals Found) --}}
                    <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 sm:p-12 text-center border border-slate-200 dark:border-slate-700">
                        <div class="w-16 h-16 rounded-full bg-blue-50 dark:bg-slate-700 text-blue-600 flex items-center justify-center mx-auto mb-4">
                            <i class="ph-bold ph-magnifying-glass text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">No professionals found matching your search</h3>
                        <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 max-w-md mx-auto mb-6 leading-relaxed">
                            Try expanding your search radius, selecting a different locality or searching with a broader category.
                        </p>

                        <div class="inline-flex flex-wrap items-center justify-center gap-3">
                            <a href="{{ route('services.index') }}" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition-all">
                                Browse All Categories
                            </a>
                            <a href="{{ route('services.register') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 text-xs font-bold transition-all">
                                Are you a local professional? Register Free
                            </a>
                        </div>
                    </div>
                @endif

                {{-- Popular Cities Directory Footer Bar --}}
                @if(isset($popularCities) && $popularCities->count() > 0)
                    <div class="mt-12 p-6 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700/80">
                        <h4 class="text-xs font-black text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                            <i class="ph-bold ph-map-pin text-blue-600"></i>
                            Find Home Services By City
                        </h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($popularCities as $pCity)
                                <a href="{{ route('services.index', ['location' => $pCity->city]) }}" class="px-3 py-1.5 rounded-lg bg-slate-50 hover:bg-blue-50 dark:bg-slate-700 dark:hover:bg-blue-900/30 text-xs font-semibold text-slate-700 dark:text-slate-300 hover:text-blue-600 transition-colors">
                                    {{ $pCity->city }} ({{ $pCity->count }})
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>

{{-- Geolocation JS Script --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const geoBtn = document.getElementById('btn-use-geo');
        const latInput = document.getElementById('geo-lat');
        const lngInput = document.getElementById('geo-lng');
        const statusBox = document.getElementById('geo-status-msg');
        const statusText = document.getElementById('geo-status-text');

        if (geoBtn) {
            geoBtn.addEventListener('click', function () {
                if (!navigator.geolocation) {
                    showGeoStatus('Geolocation is not supported by your browser. Please enter your location manually.');
                    return;
                }

                geoBtn.disabled = true;
                geoBtn.innerHTML = '<i class="ph ph-spinner animate-spin text-blue-600"></i> Detecting...';

                navigator.geolocation.getCurrentPosition(
                    function (position) {
                        latInput.value = position.coords.latitude;
                        lngInput.value = position.coords.longitude;
                        geoBtn.innerHTML = '<i class="ph-bold ph-check text-emerald-600"></i> Located!';
                        
                        // Automatically submit form with coords
                        geoBtn.closest('form').submit();
                    },
                    function (error) {
                        geoBtn.disabled = false;
                        geoBtn.innerHTML = '<i class="ph-bold ph-crosshair text-blue-600"></i> Near Me';
                        
                        let msg = "We couldn't access your location. Please enter your city, locality or pincode manually.";
                        if (error.code === error.PERMISSION_DENIED) {
                            msg = "Location permission was denied. Please enter your city or locality manually.";
                        }
                        showGeoStatus(msg);
                    },
                    { timeout: 10000, maximumAge: 60000 }
                );
            });
        }

        function showGeoStatus(message) {
            if (statusBox && statusText) {
                statusText.textContent = message;
                statusBox.classList.remove('hidden');
            }
        }
    });
</script>
@endsection
