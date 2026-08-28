{{-- Property Card Component --}}
{{-- Usage: <x-property-card :property="$property" /> --}}

@php
    $propertyUrl = Route::has('properties.show') ? route('properties.show', $property) : url('/properties/' . $property->id);
@endphp

<article class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 overflow-hidden flex flex-col group relative" id="property-card-{{ $property->id }}">
    
    {{-- Accessible Full Card Click Overlay Link --}}
    @guest
        <a href="{{ route('login') }}?redirect={{ urlencode($propertyUrl) }}"
           onclick="event.preventDefault(); window.openAuthModal('login', '{{ $propertyUrl }}');"
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
    <div class="aspect-[16/10] w-full relative overflow-hidden bg-slate-100 dark:bg-slate-800">
        @if($property->primaryImage)
            <img src="{{ $property->primaryImage->imageUrl() }}"
                 alt="{{ $property->title }}"
                 title="{{ $property->title }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                 loading="lazy">
        @else
            <img src="{{ asset('images/luxury_sunlit.png') }}"
                 alt="Premium Property - {{ $property->title }}"
                 title="Premium Property - {{ $property->title }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
        @endif

        {{-- Booked Status Overlay --}}
        @if($property->is_booked)
            <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-[2px] flex items-center justify-center z-20 pointer-events-none">
                <span class="px-3.5 py-1.5 bg-red-600 text-white font-extrabold text-xs uppercase tracking-widest rounded-lg shadow-lg border border-red-400/40 transform -rotate-3 flex items-center gap-1.5">
                    <i class="ph-bold ph-lock-key"></i> Booked
                </span>
            </div>
        @endif

        {{-- Top Badges --}}
        <div class="absolute top-3 left-3 flex items-center gap-1.5 z-20 pointer-events-none">
            <span class="bg-white/95 dark:bg-slate-950/95 text-blue-600 dark:text-blue-400 backdrop-blur-md px-2.5 py-1 rounded-lg text-[10px] font-extrabold uppercase tracking-wider shadow-xs border border-white/40 dark:border-slate-800">
                {{ ucfirst($property->type) }}
            </span>
            @if($property->is_featured)
                <span class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-2.5 py-1 rounded-lg text-[10px] font-extrabold uppercase tracking-wider shadow-xs flex items-center gap-1">
                    <i class="ph-fill ph-star"></i> Featured
                </span>
            @endif
        </div>

        {{-- Price Tag Floating Bottom Right --}}
        <div class="absolute bottom-3 right-3 z-20 pointer-events-none">
            <span class="inline-flex items-baseline bg-slate-950/85 backdrop-blur-md text-white px-3 py-1.5 rounded-xl shadow-md border border-white/10">
                <span class="text-sm font-extrabold tracking-tight">{{ $property->formatted_price }}</span>
            </span>
        </div>
    </div>

    {{-- Content Details --}}
    <div class="p-5 flex flex-col flex-1 justify-between">
        <div>
            {{-- Title --}}
            <h3 class="text-base font-bold text-slate-900 dark:text-white line-clamp-1 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors" title="{{ $property->title }}">
                {{ $property->title }}
            </h3>

            {{-- Location --}}
            <div class="flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400 font-medium mt-1.5">
                <i class="ph-fill ph-map-pin text-blue-500 text-sm flex-shrink-0"></i>
                <span class="truncate capitalize">{{ $property->location }}{{ $property->state ? ', ' . $property->state : '' }}</span>
            </div>

            {{-- Features Chip Row --}}
            <div class="flex items-center gap-2 mt-4 pb-4 border-b border-slate-100 dark:border-slate-800 flex-wrap text-xs text-slate-600 dark:text-slate-400">
                @if($property->bedrooms)
                    <span class="inline-flex items-center gap-1 bg-slate-50 dark:bg-slate-800/70 border border-slate-100 dark:border-slate-700/60 px-2 py-1 rounded-md font-semibold" title="Bedrooms">
                        <i class="ph-fill ph-bed text-blue-500"></i>
                        {{ $property->bedrooms }} Bed
                    </span>
                @endif
                @if($property->bathrooms)
                    <span class="inline-flex items-center gap-1 bg-slate-50 dark:bg-slate-800/70 border border-slate-100 dark:border-slate-700/60 px-2 py-1 rounded-md font-semibold" title="Bathrooms">
                        <i class="ph-fill ph-drop text-blue-500"></i>
                        {{ $property->bathrooms }} Bath
                    </span>
                @endif
                @if($property->area_sqft)
                    <span class="inline-flex items-center gap-1 bg-slate-50 dark:bg-slate-800/70 border border-slate-100 dark:border-slate-700/60 px-2 py-1 rounded-md font-semibold" title="Area">
                        <i class="ph-fill ph-square-half text-blue-500"></i>
                        {{ number_format($property->area_sqft) }} sq.ft
                    </span>
                @endif
            </div>
        </div>

        {{-- Footer Row with Owner and View CTA --}}
        <div class="flex items-center justify-between pt-3 mt-auto">
            <div class="flex items-center gap-2.5">
                @if($property->owner)
                    <div class="w-7 h-7 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 text-white font-extrabold text-[11px] flex items-center justify-center flex-shrink-0 shadow-xs">
                        {{ strtoupper(substr($property->owner->name, 0, 1)) }}
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Owner</span>
                        <span class="text-xs font-bold text-slate-800 dark:text-slate-200 truncate max-w-[110px]">{{ $property->owner->name }}</span>
                    </div>
                @else
                    <div class="flex items-center gap-1.5 text-xs text-slate-400">
                        <i class="ph-bold ph-shield-check text-emerald-500"></i> Verified Listing
                    </div>
                @endif
            </div>

            @guest
                <button type="button"
                        onclick="event.stopPropagation(); window.openAuthModal('login', '{{ $propertyUrl }}');"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-400 text-xs font-bold rounded-lg hover:bg-blue-600 hover:text-white transition-all shadow-xs relative z-20"
                        title="Sign in to view property details">
                    View <i class="ph-bold ph-lock text-[11px]"></i>
                </button>
            @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-400 text-xs font-bold rounded-lg group-hover:bg-blue-600 group-hover:text-white transition-all shadow-xs">
                    View <i class="ph-bold ph-arrow-right text-[11px] group-hover:translate-x-0.5 transition-transform"></i>
                </span>
            @endguest
        </div>
    </div>
</article>
