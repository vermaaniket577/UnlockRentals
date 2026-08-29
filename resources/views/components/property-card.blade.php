{{-- Property Card Component --}}
{{-- Usage: <x-property-card :property="$property" /> --}}

@php
    $propertyUrl = Route::has('properties.show') ? route('properties.show', $property) : url('/properties/' . $property->id);
@endphp

<article class="property-rental-card bg-white dark:bg-slate-900 rounded-xl sm:rounded-2xl border border-slate-200/90 dark:border-slate-800 shadow-xs hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 overflow-hidden flex flex-col group relative active:scale-[0.99]"
    id="property-card-{{ $property->id }}"
    data-property-card="true"
    data-property-id="{{ $property->id }}"
    data-price="{{ (float)$property->price }}"
    data-is-booked="{{ $property->is_booked ? '1' : '0' }}"
    data-has-image="{{ $property->primaryImageUrl() ? '1' : '0' }}"
    data-has-video="{{ $property->hasVideo() ? '1' : '0' }}"
    data-created-at="{{ $property->created_at ? $property->created_at->timestamp : 0 }}"
    data-is-featured="{{ $property->is_featured ? '1' : '0' }}">
    
    {{-- Accessible Full Card Click Overlay Link --}}
    @guest
        <a href="{{ route('login') }}?redirect={{ urlencode($propertyUrl) }}"
           onclick="event.preventDefault(); event.stopPropagation(); window.openAuthModal('login', '{{ $propertyUrl }}');"
           data-no-loader="true"
           data-ur-loader-skip="true"
           class="absolute inset-0 z-10 text-transparent"
           aria-label="View {{ $property->title }}"
           title="Sign in to view {{ $property->title }}">
            View {{ $property->title }}
        </a>
    @else
        <a href="{{ $propertyUrl }}"
           class="absolute inset-0 z-10 text-transparent"
           aria-label="View {{ $property->title }}"
           title="View {{ $property->title }}">
            View {{ $property->title }}
        </a>
    @endguest

    {{-- Image Container --}}
    <div class="aspect-[4/3] sm:aspect-[16/10] w-full relative overflow-hidden bg-slate-100 dark:bg-slate-800">
        @if($property->primaryImageUrl())
            <img src="{{ $property->primaryImageUrl() }}"
                 alt="{{ $property->title }}"
                 title="{{ $property->title }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                 loading="lazy">
        @elseif($property->hasVideo())
            <div class="w-full h-full bg-gradient-to-br from-slate-900 via-purple-950 to-slate-900 flex flex-col items-center justify-center text-white relative group-hover:scale-105 transition-transform duration-500 p-2 sm:p-4 text-center">
                <div class="w-8 h-8 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-purple-600/40 backdrop-blur-md border border-purple-500/50 flex items-center justify-center text-purple-200 mb-1 sm:mb-2 shadow-md group-hover:scale-110 transition-transform">
                    <i class="ph-bold ph-play-circle text-lg sm:text-2xl"></i>
                </div>
                <span class="text-[9px] sm:text-xs font-extrabold uppercase tracking-wider text-purple-200">Video Tour</span>
            </div>
        @else
            <img src="{{ asset('images/luxury_sunlit.png') }}"
                 alt="Premium Property - {{ $property->title }}"
                 title="Premium Property - {{ $property->title }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
        @endif

        {{-- Booked Status Overlay --}}
        @if($property->is_booked)
            <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-[2px] flex items-center justify-center z-20 pointer-events-none">
                <span class="px-2 py-0.5 sm:px-3 sm:py-1 bg-red-600 text-white font-black text-[9px] sm:text-xs uppercase tracking-wider rounded-md sm:rounded-lg shadow-md border border-red-400/40 transform -rotate-3 flex items-center gap-1">
                    <i class="ph-bold ph-lock-key"></i> Booked
                </span>
            </div>
        @endif

        {{-- Top Badges --}}
        <div class="absolute top-1.5 left-1.5 sm:top-2.5 sm:left-2.5 flex items-center flex-wrap gap-1 sm:gap-1.5 z-20 pointer-events-none max-w-[90%]">
            @if(($property->purpose ?? 'rent') === 'buy' || ($property->purpose ?? 'rent') === 'sell')
                <span class="bg-emerald-600 text-white backdrop-blur-md px-1.5 py-0.5 sm:px-2.5 sm:py-0.5 rounded-md sm:rounded-lg text-[8px] sm:text-[10px] font-black uppercase tracking-wider shadow-xs flex items-center gap-0.5">
                    <i class="ph-bold ph-tag"></i> Sale
                </span>
            @else
                <span class="bg-blue-600 text-white backdrop-blur-md px-1.5 py-0.5 sm:px-2.5 sm:py-0.5 rounded-md sm:rounded-lg text-[8px] sm:text-[10px] font-black uppercase tracking-wider shadow-xs flex items-center gap-0.5">
                    <i class="ph-bold ph-key"></i> Rent
                </span>
            @endif
            <span class="bg-white/95 dark:bg-slate-950/95 text-slate-800 dark:text-slate-200 backdrop-blur-md px-1.5 py-0.5 sm:px-2.5 sm:py-0.5 rounded-md sm:rounded-lg text-[8px] sm:text-[10px] font-black uppercase tracking-wider shadow-xs border border-white/40 dark:border-slate-800 truncate max-w-[65px] sm:max-w-none">
                {{ ucfirst($property->type) }}
            </span>
            @if($property->hasVideo())
                <span class="bg-purple-600 text-white backdrop-blur-md px-1.5 py-0.5 sm:px-2 sm:py-0.5 rounded-md sm:rounded-lg text-[8px] sm:text-[10px] font-black uppercase tracking-wider shadow-xs hidden sm:flex items-center gap-1">
                    <i class="ph-bold ph-video-camera"></i> Tour
                </span>
            @endif
            @if($property->is_featured)
                <span class="bg-gradient-to-r from-amber-500 to-orange-500 text-white px-1.5 py-0.5 sm:px-2 sm:py-0.5 rounded-md sm:rounded-lg text-[8px] sm:text-[10px] font-black uppercase tracking-wider shadow-xs flex items-center gap-0.5">
                    <i class="ph-fill ph-star"></i> Featured
                </span>
            @endif
        </div>

        {{-- Price Tag Floating Bottom Right --}}
        <div class="absolute bottom-1.5 right-1.5 sm:bottom-2.5 sm:right-2.5 z-20 pointer-events-none">
            <span class="inline-flex items-baseline bg-slate-950/90 backdrop-blur-md text-white px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-lg sm:rounded-xl shadow-xs border border-white/15">
                <span class="text-xs sm:text-sm font-black tracking-tight">{{ $property->formatted_price }}</span>
            </span>
        </div>
    </div>

    {{-- Content Details --}}
    <div class="p-2.5 sm:p-4 flex flex-col flex-1 justify-between gap-1.5 sm:gap-2">
        <div>
            {{-- Title --}}
            <h3 class="text-xs sm:text-[15px] font-bold text-slate-900 dark:text-white line-clamp-1 leading-snug group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors" title="{{ $property->title }}">
                {{ $property->title }}
            </h3>

            {{-- Location --}}
            <div class="flex items-center gap-1 text-[10px] sm:text-xs text-slate-500 dark:text-slate-400 font-medium mt-0.5">
                <i class="ph-fill ph-map-pin text-blue-500 text-xs sm:text-sm flex-shrink-0"></i>
                <span class="truncate capitalize">{{ $property->location }}{{ $property->state ? ', ' . $property->state : '' }}</span>
            </div>

            {{-- Features Chip Row --}}
            <div class="flex items-center gap-1 sm:gap-1.5 mt-2 pt-2 border-t border-slate-100 dark:border-slate-800/80 flex-wrap text-[10px] sm:text-xs text-slate-600 dark:text-slate-400">
                @if($property->bedrooms)
                    <span class="inline-flex items-center gap-0.5 sm:gap-1 bg-slate-50 dark:bg-slate-800/70 border border-slate-200/80 dark:border-slate-700/60 px-1.5 py-0.5 rounded-md sm:rounded-lg font-semibold text-[9px] sm:text-[11px]" title="Bedrooms">
                        <i class="ph-fill ph-bed text-blue-500"></i>
                        {{ $property->bedrooms }} Bed
                    </span>
                @endif
                @if($property->bathrooms)
                    <span class="inline-flex items-center gap-0.5 sm:gap-1 bg-slate-50 dark:bg-slate-800/70 border border-slate-200/80 dark:border-slate-700/60 px-1.5 py-0.5 rounded-md sm:rounded-lg font-semibold text-[9px] sm:text-[11px]" title="Bathrooms">
                        <i class="ph-fill ph-drop text-blue-500"></i>
                        {{ $property->bathrooms }} Bath
                    </span>
                @endif
                @if($property->area_sqft)
                    <span class="inline-flex items-center gap-0.5 sm:gap-1 bg-slate-50 dark:bg-slate-800/70 border border-slate-200/80 dark:border-slate-700/60 px-1.5 py-0.5 rounded-md sm:rounded-lg font-semibold text-[9px] sm:text-[11px] hidden sm:inline-flex" title="Area">
                        <i class="ph-fill ph-square-half text-blue-500"></i>
                        {{ number_format($property->area_sqft) }} sq.ft
                    </span>
                @endif
            </div>
        </div>

        {{-- Footer Row with Owner and View CTA --}}
        <div class="flex items-center justify-between pt-1.5 sm:pt-2.5 mt-auto gap-1 sm:gap-2">
            <div class="flex items-center gap-1 sm:gap-1.5 min-w-0">
                @if($property->owner)
                    <div class="w-5 h-5 sm:w-6 sm:h-6 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 text-white font-black text-[9px] sm:text-[10px] flex items-center justify-center flex-shrink-0 shadow-xs">
                        {{ strtoupper(substr($property->owner->name, 0, 1)) }}
                    </div>
                    <div class="flex flex-col min-w-0">
                        <span class="text-[8px] sm:text-[9px] font-bold text-slate-400 uppercase tracking-tight leading-none">Owner</span>
                        <span class="text-[10px] sm:text-xs font-bold text-slate-800 dark:text-slate-200 truncate max-w-[52px] sm:max-w-[100px] leading-tight">{{ $property->owner->name }}</span>
                    </div>
                @else
                    <div class="flex items-center gap-1 text-[10px] sm:text-xs text-slate-400">
                        <i class="ph-bold ph-shield-check text-emerald-500"></i> <span class="hidden sm:inline">Verified</span>
                    </div>
                @endif
            </div>

            @guest
                <button type="button"
                        onclick="event.preventDefault(); event.stopPropagation(); window.openAuthModal('login', '{{ $propertyUrl }}');"
                        data-no-loader="true"
                        data-ur-loader-skip="true"
                        class="inline-flex items-center gap-0.5 sm:gap-1 px-2 py-1 sm:px-2.5 sm:py-1.5 bg-blue-50 dark:bg-blue-950/60 hover:bg-blue-600 hover:text-white text-blue-700 dark:text-blue-400 text-[10px] sm:text-xs font-bold rounded-lg sm:rounded-xl transition-all shadow-xs relative z-20 active:scale-95 flex-shrink-0"
                        title="Sign in to view property details">
                    <span>View</span> <i class="ph-bold ph-lock text-[9px] sm:text-[11px]"></i>
                </button>
            @else
                <span class="inline-flex items-center gap-0.5 sm:gap-1 px-2 py-1 sm:px-2.5 sm:py-1.5 bg-blue-50 dark:bg-blue-950/60 group-hover:bg-blue-600 group-hover:text-white text-blue-700 dark:text-blue-400 text-[10px] sm:text-xs font-bold rounded-lg sm:rounded-xl transition-all shadow-xs flex-shrink-0">
                    <span>View</span> <i class="ph-bold ph-arrow-right text-[9px] sm:text-[11px] group-hover:translate-x-0.5 transition-transform"></i>
                </span>
            @endguest
        </div>
    </div>
</article>
