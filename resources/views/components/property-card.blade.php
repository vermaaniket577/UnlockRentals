{{-- ============================================================
     UnlockRentals.com — Premium Real Estate Property Card
     ============================================================ --}}

@php
    $propertyUrl = Route::has('properties.show') ? route('properties.show', $property) : url('/properties/' . $property->id);

    // Standard title casing with uppercase for acronyms (e.g. "1 BHK Big Floor", "2 Room Set")
    $cleanTitle = ucwords(strtolower(trim($property->title)));
    $cleanTitle = preg_replace_callback('/\b(\d+)?\s*(bhk|rk|pg)\b/i', function($m) {
        return strtoupper($m[0]);
    }, $cleanTitle);

    // Collect available specs cleanly to prevent empty spaces or dangling dots
    $specs = [];
    if ($property->bedrooms !== null && $property->bedrooms !== '') {
        $beds = (int)$property->bedrooms;
        $specs[] = [
            'icon' => 'ph-bed',
            'label' => $beds === 0 ? '1 RK' : ($beds === 1 ? '1 Bed' : $beds . ' Beds')
        ];
    }
    if (!empty($property->bathrooms)) {
        $baths = (int)$property->bathrooms;
        $specs[] = [
            'icon' => 'ph-drop',
            'label' => $baths === 1 ? '1 Bath' : $baths . ' Baths'
        ];
    }
    if (!empty($property->area_sqft)) {
        $specs[] = [
            'icon' => 'ph-ruler',
            'label' => number_format($property->area_sqft) . ' sq.ft'
        ];
    }
    if (!empty($property->furnishing)) {
        $specs[] = [
            'icon' => 'ph-armchair',
            'label' => ucfirst($property->furnishing)
        ];
    }

    $isSale = ($property->purpose ?? 'rent') === 'buy' || ($property->purpose ?? 'rent') === 'sell';
    $hasFixedPrice = (float)$property->price > 0;
@endphp

<article class="property-rental-card group relative flex flex-col h-full bg-white dark:bg-slate-900 rounded-xl sm:rounded-2xl border border-slate-200/90 dark:border-slate-800 shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden"
    id="property-card-{{ $property->id }}"
    data-property-card="true"
    data-property-id="{{ $property->id }}"
    data-price="{{ (float)$property->price }}"
    data-is-booked="{{ $property->is_booked ? '1' : '0' }}"
    data-has-image="{{ $property->primaryImageUrl() ? '1' : '0' }}"
    data-has-video="{{ $property->hasVideo() ? '1' : '0' }}"
    data-created-at="{{ $property->created_at ? $property->created_at->timestamp : 0 }}"
    data-is-featured="{{ $property->is_featured ? '1' : '0' }}">

    {{-- Accessible Full-Card Overlay Link (Hidden visually to guarantee zero text bleed) --}}
    @guest
        <a href="{{ route('login') }}?redirect={{ urlencode($propertyUrl) }}"
           onclick="event.preventDefault(); event.stopPropagation(); window.openAuthModal('login', '{{ $propertyUrl }}');"
           data-no-loader="true"
           data-ur-loader-skip="true"
           class="absolute inset-0 z-10"
           style="opacity: 0; font-size: 0; line-height: 0; text-indent: -9999px; overflow: hidden;"
           aria-label="View {{ $cleanTitle }}"
           title="Sign in to view {{ $cleanTitle }}">
            <span class="sr-only">View {{ $cleanTitle }}</span>
        </a>
    @else
        <a href="{{ $propertyUrl }}"
           class="absolute inset-0 z-10"
           style="opacity: 0; font-size: 0; line-height: 0; text-indent: -9999px; overflow: hidden;"
           aria-label="View {{ $cleanTitle }}"
           title="View {{ $cleanTitle }}">
            <span class="sr-only">View {{ $cleanTitle }}</span>
        </a>
    @endguest

    {{-- A. Property Image Section (Responsive Aspect Ratio: 16:10 on mobile, 16:9 on sm+) --}}
    <div class="relative w-full aspect-[16/10] sm:aspect-[16/9] overflow-hidden bg-slate-100 dark:bg-slate-800 flex-shrink-0">
        @if($property->primaryImageUrl())
            <img src="{{ $property->primaryImageUrl() }}"
                 alt="{{ $cleanTitle }}"
                 title="{{ $cleanTitle }}"
                 width="480"
                 height="270"
                 onerror="this.onerror=null; this.src='{{ asset('images/luxury_sunlit.webp') }}';"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out"
                 loading="lazy"
                 decoding="async">
        @elseif($property->hasVideo())
            <div class="relative w-full h-full">
                <img src="{{ asset('images/luxury_sunlit.webp') }}"
                     alt="{{ $cleanTitle }}"
                     title="{{ $cleanTitle }}"
                     width="480"
                     height="270"
                     class="w-full h-full object-cover filter brightness-[0.55] group-hover:scale-105 transition-transform duration-500 ease-out"
                     loading="lazy"
                     decoding="async">
                <div class="absolute inset-0 flex flex-col items-center justify-center text-white pointer-events-none">
                    <div class="w-8 h-8 sm:w-11 sm:h-11 rounded-full bg-white/25 backdrop-blur-md border border-white/40 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform mb-1">
                        <i class="ph-fill ph-play text-sm sm:text-lg ml-0.5 text-white"></i>
                    </div>
                    <span class="text-[9px] sm:text-[11px] font-extrabold uppercase tracking-wider text-white drop-shadow">Video Tour</span>
                </div>
            </div>
        @else
            <img src="{{ asset('images/luxury_sunlit.webp') }}"
                 alt="Premium Property - {{ $cleanTitle }}"
                 title="Premium Property - {{ $cleanTitle }}"
                 width="480"
                 height="270"
                 loading="lazy"
                 decoding="async"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out">
        @endif

        {{-- Subtle Vignette Gradient for Badge Contrast --}}
        <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-transparent to-transparent pointer-events-none"></div>

        {{-- Diagonal BOOKED Corner Ribbon (Responsive Size) --}}
        @if($property->is_booked)
            <div class="absolute top-0 right-0 w-20 h-20 sm:w-28 sm:h-28 overflow-hidden pointer-events-none z-30">
                <div class="absolute top-[14px] -right-[26px] w-[110px] sm:top-[22px] sm:-right-[32px] sm:w-[145px] transform rotate-45 bg-gradient-to-r from-rose-600 to-red-600 text-white text-[8px] sm:text-[10px] font-black uppercase tracking-wider py-0.5 sm:py-1.5 shadow-md text-center flex items-center justify-center gap-1 border-y border-white/25 select-none"
                     style="background: linear-gradient(135deg, #e11d48 0%, #dc2626 100%); color: #ffffff; font-weight: 900; letter-spacing: 0.12em; text-transform: uppercase; text-align: center; box-shadow: 0 4px 12px rgba(0,0,0,0.3); border-top: 1px solid rgba(255,255,255,0.3); border-bottom: 1px solid rgba(0,0,0,0.15);">
                    <i class="ph-bold ph-lock-key text-[9px] sm:text-xs"></i>
                    <span>Booked</span>
                </div>
            </div>
        @endif

        {{-- Top Badges Row --}}
        <div class="absolute top-1.5 left-1.5 right-1.5 sm:top-2.5 sm:left-2.5 sm:right-2.5 flex items-center justify-between gap-1 z-20 pointer-events-none">
            {{-- Top Left: RENT / SALE & Property Type & FEATURED (when booked) --}}
            <div class="flex items-center gap-1 flex-wrap">
                @if($isSale)
                    <span class="inline-flex items-center gap-0.5 sm:gap-1 px-1.5 py-0.5 sm:px-2.5 sm:py-1 rounded-md sm:rounded-lg text-[8px] sm:text-[10px] font-extrabold uppercase tracking-wide sm:tracking-wider bg-emerald-600/95 text-white shadow-xs sm:shadow-sm backdrop-blur-md">
                        <i class="ph-bold ph-tag text-[8px] sm:text-[10px]"></i> <span class="hidden xs:inline sm:inline">For </span>Sale
                    </span>
                @else
                    <span class="inline-flex items-center gap-0.5 sm:gap-1 px-1.5 py-0.5 sm:px-2.5 sm:py-1 rounded-md sm:rounded-lg text-[8px] sm:text-[10px] font-extrabold uppercase tracking-wide sm:tracking-wider bg-blue-600/95 text-white shadow-xs sm:shadow-sm backdrop-blur-md">
                        <i class="ph-bold ph-key text-[8px] sm:text-[10px]"></i> Rent
                    </span>
                @endif
                <span class="inline-flex items-center px-1.5 py-0.5 sm:px-2 sm:py-1 rounded-md sm:rounded-lg text-[8px] sm:text-[10px] font-bold capitalize bg-slate-950/70 text-white/95 border border-white/15 shadow-xs sm:shadow-sm backdrop-blur-md truncate max-w-[80px] sm:max-w-none">
                    {{ ucfirst($property->type) }}
                </span>
                @if($property->is_booked && $property->is_featured)
                    <span class="inline-flex items-center gap-0.5 sm:gap-1 px-1.5 py-0.5 sm:px-2 sm:py-1 rounded-md sm:rounded-lg text-[8px] sm:text-[10px] font-extrabold uppercase tracking-wide sm:tracking-wider bg-amber-500 text-slate-950 shadow-xs sm:shadow-sm backdrop-blur-md">
                        <i class="ph-fill ph-star text-[8px] sm:text-[10px]"></i> <span class="hidden sm:inline">Featured</span>
                    </span>
                @endif
            </div>

            {{-- Top Right: FEATURED (when unbooked) --}}
            <div class="flex items-center gap-1">
                @if(!$property->is_booked && $property->is_featured)
                    <span class="inline-flex items-center gap-0.5 sm:gap-1 px-1.5 py-0.5 sm:px-2.5 sm:py-1 rounded-md sm:rounded-lg text-[8px] sm:text-[10px] font-extrabold uppercase tracking-wide sm:tracking-wider bg-amber-500 text-slate-950 shadow-xs sm:shadow-sm backdrop-blur-md">
                        <i class="ph-fill ph-star text-[8px] sm:text-[10px]"></i> <span class="hidden sm:inline">Featured</span>
                    </span>
                @endif
            </div>
        </div>
    </div>

    {{-- B & C. Information & Action Body (Uniform Padding & Spacing) --}}
    <div class="p-2.5 sm:p-4 md:p-5 flex flex-col flex-1 justify-between gap-1.5 sm:gap-3 bg-white dark:bg-slate-900">
        <div class="flex flex-col min-w-0">
            {{-- Property Title (Allows up to 2 lines with fixed min-height for uniform alignment) --}}
            <h3 class="text-xs sm:text-[15px] md:text-base font-bold text-slate-900 dark:text-white line-clamp-2 min-h-[2rem] sm:min-h-[2.6rem] md:min-h-[2.85rem] leading-snug group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors" title="{{ $cleanTitle }}">
                {{ $cleanTitle }}
            </h3>

            {{-- Location --}}
            <div class="flex items-center gap-1 text-[10px] sm:text-xs text-slate-500 dark:text-slate-400 font-medium mt-0.5 sm:mt-1">
                <i class="ph-bold ph-map-pin text-blue-600 text-[10px] sm:text-xs shrink-0"></i>
                <span class="truncate capitalize">{{ $property->location }}{{ $property->state ? ', ' . $property->state : '' }}</span>
            </div>

            {{-- Property Details / Specs Row (Consistent Height & Clean Separators) --}}
            <div class="flex items-center gap-1 sm:gap-2 text-[10px] sm:text-xs text-slate-600 dark:text-slate-300 mt-1.5 sm:mt-3 pt-1.5 sm:pt-3 border-t border-slate-100 dark:border-slate-800 min-h-[1.4rem] sm:min-h-[2.25rem] flex-wrap overflow-hidden">
                @if(count($specs) > 0)
                    @foreach($specs as $index => $spec)
                        @if($index > 0)
                            <span class="text-slate-300 dark:text-slate-700 select-none text-[10px]">·</span>
                        @endif
                        <span class="inline-flex items-center gap-0.5 sm:gap-1 whitespace-nowrap">
                            <i class="ph-bold {{ $spec['icon'] }} text-slate-400 text-[10px] sm:text-xs"></i>
                            <span class="font-medium text-slate-600 dark:text-slate-300 text-[9px] sm:text-[11px] md:text-xs">{{ $spec['label'] }}</span>
                        </span>
                    @endforeach
                @else
                    <span class="text-[9px] sm:text-[11px] md:text-xs text-slate-400 italic">Ready to Move</span>
                @endif
            </div>
        </div>

        {{-- C. Price and Action Section (Consistently Aligned Across All Cards) --}}
        <div class="flex items-center justify-between pt-2 sm:pt-3 mt-auto border-t border-slate-100 dark:border-slate-800 gap-1">
            {{-- Prominent Price Display --}}
            <div class="flex flex-col min-w-0 flex-1">
                @if($hasFixedPrice)
                    <span class="text-[8px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-0.5 sm:mb-1">
                        {{ $isSale ? 'Price' : 'Rent' }}
                    </span>
                    <span class="text-xs sm:text-base md:text-lg font-black text-slate-900 dark:text-white tracking-tight leading-none truncate">
                        {{ $property->formatted_price }}
                    </span>
                @else
                    <span class="text-[8px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-0.5 sm:mb-1">Pricing</span>
                    <span class="text-[10px] sm:text-xs md:text-sm font-bold text-blue-600 dark:text-blue-400 tracking-tight leading-none truncate">
                        On Request
                    </span>
                @endif
            </div>

            {{-- Professional Brand Blue Action Button --}}
            @guest
                <button type="button"
                        onclick="event.preventDefault(); event.stopPropagation(); window.openAuthModal('login', '{{ $propertyUrl }}');"
                        data-no-loader="true"
                        data-ur-loader-skip="true"
                        class="inline-flex items-center justify-center gap-1 px-2.5 py-1.5 sm:px-3.5 sm:py-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-[10px] sm:text-xs font-bold rounded-lg sm:rounded-xl shadow-xs hover:shadow transition-all relative z-20 shrink-0 cursor-pointer active:scale-95"
                        title="Sign in to view details">
                    <span class="hidden sm:inline">View Details</span>
                    <span class="sm:hidden">View</span>
                    <i class="ph-bold ph-lock text-[9px] sm:text-xs"></i>
                </button>
            @else
                <span class="inline-flex items-center justify-center gap-1 px-2.5 py-1.5 sm:px-3.5 sm:py-2 bg-blue-600 group-hover:bg-blue-700 active:bg-blue-800 text-white text-[10px] sm:text-xs font-bold rounded-lg sm:rounded-xl shadow-xs hover:shadow transition-all shrink-0">
                    <span class="hidden sm:inline">View Details</span>
                    <span class="sm:hidden">View</span>
                    <i class="ph-bold ph-arrow-right text-[9px] sm:text-xs group-hover:translate-x-0.5 transition-transform"></i>
                </span>
            @endguest
        </div>
    </div>
</article>
