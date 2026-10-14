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
    if ($property->type === 'plot') {
        if (!empty($property->area_sqft)) {
            $sqft = (int)$property->area_sqft;
            $specs[] = [
                'icon' => 'ph-map-trifold',
                'label' => 'Plot: ' . number_format($sqft) . ' sq.ft'
            ];
            if ($sqft >= 450) {
                $sqYards = round($sqft / 9);
                $specs[] = [
                    'icon' => 'ph-corners-out',
                    'label' => number_format($sqYards) . ' sq.yd'
                ];
            }
        }
        $specs[] = [
            'icon' => 'ph-certificate',
            'label' => 'Clear Title'
        ];
    } else {
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
    }

    $isSale = ($property->purpose ?? 'rent') === 'buy' || ($property->purpose ?? 'rent') === 'sell';
    $hasFixedPrice = (float)$property->price > 0;

    // Multi-Image Gallery Collection for Card Auto-Slider
    $cardImages = collect();
    if ($property->relationLoaded('images') && $property->images && $property->images->isNotEmpty()) {
        $cardImages = $property->images;
    } elseif ($property->relationLoaded('primaryImage') && $property->primaryImage) {
        $cardImages = collect([$property->primaryImage]);
    } else {
        $cardImages = $property->images()->take(6)->get();
    }

    if ($cardImages->isNotEmpty()) {
        $primaryImg = $cardImages->firstWhere('is_primary', true);
        if ($primaryImg) {
            $cardImages = collect([$primaryImg])->merge($cardImages->reject(fn($img) => $img->id === $primaryImg->id))->values();
        }
    }
    // Limit to 6 slides max per card for optimal DOM and memory performance
    $cardImages = $cardImages->take(6);
    $hasMultipleImages = $cardImages->count() > 1;
@endphp

<article class="property-rental-card group relative flex flex-col h-auto sm:h-full bg-white dark:bg-slate-900 rounded-xl sm:rounded-2xl border border-slate-200/90 dark:border-slate-800 shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden"
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

    @php
        $isEager = !empty($eager) || (isset($loop) && $loop->index < 4);
        $loadingAttr = $isEager ? 'eager' : 'lazy';
        $priorityAttr = $isEager ? 'high' : 'low';
    @endphp

    {{-- A. Property Image Section (Responsive Aspect Ratio: 4:3 on mobile 2-col, 16:9 on sm+) --}}
    <div class="relative w-full aspect-[4/3] sm:aspect-[16/9] overflow-hidden bg-slate-100 dark:bg-slate-800 img-skeleton-shimmer flex-shrink-0">
        @if($hasMultipleImages)
            {{-- Multi-Image Auto-Slider (Slides Right to Left) --}}
            <div class="card-image-slider relative w-full h-full overflow-hidden select-none"
                 data-card-slider="{{ $property->id }}"
                 data-total-slides="{{ $cardImages->count() }}">
                
                {{-- Slider Track (Translates horizontally for right-to-left slide) --}}
                <div class="card-slider-track flex h-full w-full"
                     style="transform: translate3d(0, 0, 0);">
                    @foreach($cardImages as $idx => $img)
                        <div class="card-slide flex-shrink-0 w-full h-full relative">
                            <img src="{{ $img->imageUrl() }}"
                                 alt="{{ $cleanTitle }} - Photo {{ $idx + 1 }}"
                                 title="{{ $cleanTitle }} - Photo {{ $idx + 1 }}"
                                 width="480"
                                 height="360"
                                 onerror="this.onerror=null; this.src='{{ asset('images/luxury_sunlit.webp') }}';"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out pointer-events-none"
                                 loading="{{ $idx === 0 ? $loadingAttr : 'lazy' }}"
                                 fetchpriority="{{ $idx === 0 ? $priorityAttr : 'low' }}"
                                 decoding="async">
                        </div>
                    @endforeach
                </div>

                {{-- Left & Right Navigation Chevrons (z-20 so clicks don't trigger the card link) --}}
                <button type="button"
                        onclick="event.preventDefault(); event.stopPropagation(); window.cardSliderPrev({{ $property->id }});"
                        class="card-slider-btn card-slider-prev absolute left-1.5 top-1/2 -translate-y-1/2 z-20 w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-black/60 hover:bg-black/90 text-white flex items-center justify-center opacity-75 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity backdrop-blur-xs cursor-pointer shadow-md border border-white/25 active:scale-90"
                        aria-label="Previous photo">
                    <i class="ph-bold ph-caret-left text-xs sm:text-sm"></i>
                </button>
                <button type="button"
                        onclick="event.preventDefault(); event.stopPropagation(); window.cardSliderNext({{ $property->id }});"
                        class="card-slider-btn card-slider-next absolute right-1.5 top-1/2 -translate-y-1/2 z-20 w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-black/60 hover:bg-black/90 text-white flex items-center justify-center opacity-75 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity backdrop-blur-xs cursor-pointer shadow-md border border-white/25 active:scale-90"
                        aria-label="Next photo">
                    <i class="ph-bold ph-caret-right text-xs sm:text-sm"></i>
                </button>

                {{-- Micro Pagination Dots (Centered at bottom, z-20) --}}
                <div class="card-slider-dots absolute bottom-2 left-1/2 -translate-x-1/2 z-20 flex items-center gap-1 px-1.5 py-0.5 rounded-full bg-black/50 backdrop-blur-xs pointer-events-auto"
                     onclick="event.stopPropagation();">
                    @foreach($cardImages as $idx => $img)
                        <button type="button"
                                onclick="event.preventDefault(); event.stopPropagation(); window.cardSliderGoTo({{ $property->id }}, {{ $idx }});"
                                class="card-slider-dot h-1.5 rounded-full transition-all duration-300 {{ $idx === 0 ? 'w-3.5 bg-white shadow-xs' : 'w-1.5 bg-white/50 hover:bg-white/80' }}"
                                data-dot-index="{{ $idx }}"
                                aria-label="Go to photo {{ $idx + 1 }}">
                        </button>
                    @endforeach
                </div>

                {{-- Photo Count Pill (Bottom right, z-20) --}}
                <div class="absolute bottom-2 right-2 z-20 pointer-events-none px-1.5 py-0.5 rounded-md bg-black/60 backdrop-blur-xs text-[9px] font-extrabold text-white flex items-center gap-1 shadow-xs border border-white/10">
                    <i class="ph-bold ph-images text-[10px] text-blue-300"></i>
                    <span class="card-slide-counter">1/{{ $cardImages->count() }}</span>
                </div>
            </div>
        @elseif($property->primaryImageUrl())
            <img src="{{ $property->primaryImageUrl() }}"
                 alt="{{ $cleanTitle }}"
                 title="{{ $cleanTitle }}"
                 width="480"
                 height="360"
                 onerror="this.onerror=null; this.src='{{ asset('images/luxury_sunlit.webp') }}';"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out"
                 loading="{{ $loadingAttr }}"
                 fetchpriority="{{ $priorityAttr }}"
                 decoding="async">
        @elseif($property->hasVideo())
            <div class="relative w-full h-full">
                <img src="{{ asset('images/luxury_sunlit.webp') }}"
                     alt="{{ $cleanTitle }}"
                     title="{{ $cleanTitle }}"
                     width="480"
                     height="360"
                     class="w-full h-full object-cover filter brightness-[0.55] group-hover:scale-105 transition-transform duration-500 ease-out"
                     loading="{{ $loadingAttr }}"
                     fetchpriority="{{ $priorityAttr }}"
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
                 height="360"
                 loading="{{ $loadingAttr }}"
                 fetchpriority="{{ $priorityAttr }}"
                 decoding="async"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out">
        @endif

        {{-- Subtle Vignette Gradient for Badge Contrast --}}
        <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-transparent to-transparent pointer-events-none"></div>

        {{-- Diagonal BOOKED Corner Ribbon (45° Angle Crossing Image Corner) --}}
        @if($property->is_booked)
            <div class="ur-booked-ribbon-wrap absolute top-0 right-0 pointer-events-none z-30 overflow-hidden"
                 style="position: absolute; top: 0; right: 0; width: 92px; height: 92px; overflow: hidden; pointer-events: none; z-index: 30;">
                <div class="ur-booked-ribbon"
                     style="position: absolute; top: 16px; right: -28px; width: 122px; transform: rotate(45deg); -webkit-transform: rotate(45deg); transform-origin: center center; -webkit-transform-origin: center center; background: linear-gradient(135deg, #e11d48 0%, #dc2626 100%); color: #ffffff; font-size: 9px; font-weight: 900; letter-spacing: 0.12em; text-transform: uppercase; text-align: center; padding: 3.5px 0; line-height: 1; box-shadow: 0 4px 12px rgba(0,0,0,0.35); border-top: 1px solid rgba(255,255,255,0.4); border-bottom: 1px solid rgba(0,0,0,0.2); display: flex; align-items: center; justify-content: center; gap: 3px; user-select: none; white-space: nowrap;">
                    <i class="ph-bold ph-lock-key" style="font-size: 10px; color: #ffffff; line-height: 1;"></i>
                    <span style="color: #ffffff; font-weight: 900; letter-spacing: 0.12em; line-height: 1;">Booked</span>
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
                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 sm:px-2 sm:py-1 rounded-md sm:rounded-lg text-[8px] sm:text-[10px] font-bold capitalize bg-slate-950/70 text-white/95 border border-white/15 shadow-xs sm:shadow-sm backdrop-blur-md truncate max-w-[90px] sm:max-w-none">
                    @if($property->type === 'plot')
                        <i class="ph-bold ph-map-trifold text-amber-300 text-[9px] sm:text-[11px]"></i> Plot / Land
                    @else
                        {{ ucfirst($property->type) }}
                    @endif
                </span>
                @if($property->is_booked && $property->is_featured)
                    <span class="inline-flex items-center gap-0.5 sm:gap-1 px-1.5 py-0.5 sm:px-2 sm:py-1 rounded-md sm:rounded-lg text-[8px] sm:text-[10px] font-extrabold uppercase tracking-wide sm:tracking-wider bg-amber-500 text-slate-950 shadow-xs sm:shadow-sm backdrop-blur-md">
                        <i class="ph-fill ph-star text-[8px] sm:text-[10px]"></i> <span class="hidden sm:inline">Featured</span>
                    </span>
                @endif
            </div>

            {{-- Top Right: DISTANCE or FEATURED --}}
            <div class="flex items-center gap-1">
                @if(isset($property->distance_km) && $property->distance_km !== null)
                    <span class="inline-flex items-center gap-0.5 sm:gap-1 px-1.5 py-0.5 sm:px-2 sm:py-1 rounded-md sm:rounded-lg text-[8px] sm:text-[10px] font-extrabold uppercase bg-emerald-600/95 text-white shadow-xs backdrop-blur-md">
                        <i class="ph-fill ph-navigation-arrow text-[8px] sm:text-[10px]"></i>
                        <span>{{ round($property->distance_km, 1) }} km</span>
                    </span>
                @endif
                @if(!$property->is_booked && $property->is_featured)
                    <span class="inline-flex items-center gap-0.5 sm:gap-1 px-1.5 py-0.5 sm:px-2.5 sm:py-1 rounded-md sm:rounded-lg text-[8px] sm:text-[10px] font-extrabold uppercase tracking-wide sm:tracking-wider bg-amber-500 text-slate-950 shadow-xs sm:shadow-sm backdrop-blur-md">
                        <i class="ph-fill ph-star text-[8px] sm:text-[10px]"></i> <span class="hidden sm:inline">Featured</span>
                    </span>
                @endif
            </div>
        </div>

        {{-- Bottom Left: Unique Post ID Badge on Image --}}
        <div class="absolute bottom-1.5 left-1.5 sm:bottom-2 sm:left-2 z-20 pointer-events-none">
            <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded bg-white/95 text-slate-800 text-[8.5px] sm:text-[9.5px] font-mono font-black shadow-xs border border-white/80 tracking-tight">
                <span class="text-blue-600 font-bold">#</span>{{ $property->id }}
            </span>
        </div>
    </div>

    {{-- B & C. Information & Action Body (Snug, Balanced Spacing Without Giant Gaps) --}}
    <div class="p-2 sm:p-4 flex flex-col flex-1 gap-1 sm:gap-2.5 bg-white dark:bg-slate-900">
        <div class="flex flex-col min-w-0">
            {{-- Property Title (Line-clamp-1 on mobile 2-col, line-clamp-2 on sm+ for clean alignment) --}}
            <h3 class="text-xs sm:text-[15px] md:text-base font-bold text-slate-900 dark:text-white line-clamp-1 sm:line-clamp-2 leading-snug group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors" title="{{ $cleanTitle }}">
                {{ $cleanTitle }}
            </h3>

            {{-- Location & Unique Post ID --}}
            <div class="flex items-center justify-between gap-1 text-[10px] sm:text-xs text-slate-500 dark:text-slate-400 font-medium mt-0.5">
                <div class="flex items-center gap-1 min-w-0">
                    <i class="ph-bold ph-map-pin text-blue-600 text-[10px] sm:text-xs shrink-0"></i>
                    <span class="truncate capitalize">
                        {{ $property->location }}{{ $property->state ? ', ' . $property->state : '' }}
                        @if(isset($property->distance_km) && $property->distance_km !== null)
                            <span class="text-emerald-600 font-bold text-[9.5px] sm:text-[10.5px]">({{ round($property->distance_km, 1) }} km away)</span>
                        @endif
                    </span>
                </div>
                <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded bg-blue-50 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 font-mono text-[8.5px] sm:text-[9.5px] font-bold shrink-0 border border-blue-100 dark:border-blue-900/60" title="Post ID: #{{ $property->id }}">
                    ID: #{{ $property->id }}
                </span>
            </div>

            {{-- Property Details / Specs Row (Single Clean Line on Mobile, Full on sm+) --}}
            <div class="flex items-center gap-1 sm:gap-2 text-[10px] sm:text-xs text-slate-600 dark:text-slate-300 mt-1 sm:mt-2.5 pt-1 sm:pt-2.5 border-t border-slate-100 dark:border-slate-800 flex-wrap overflow-hidden">
                @if(count($specs) > 0)
                    @foreach($specs as $index => $spec)
                        @if($index > 0)
                            <span class="text-slate-300 dark:text-slate-700 select-none text-[10px] {{ $index >= 2 ? 'hidden sm:inline' : '' }}">·</span>
                        @endif
                        <span class="inline-flex items-center gap-0.5 sm:gap-1 whitespace-nowrap {{ $index >= 2 ? 'hidden sm:inline-flex' : '' }}">
                            <i class="ph-bold {{ $spec['icon'] }} text-slate-400 text-[10px] sm:text-xs"></i>
                            <span class="font-medium text-slate-600 dark:text-slate-300 text-[9px] sm:text-[11px] md:text-xs">{{ $spec['label'] }}</span>
                        </span>
                    @endforeach
                @else
                    <span class="text-[9px] sm:text-[11px] md:text-xs text-slate-400 italic">Ready to Move</span>
                @endif
            </div>
        </div>

        {{-- C. Price and Action Section (Neatly Anchored at Base) --}}
        <div class="flex items-center justify-between pt-1.5 sm:pt-3 mt-auto border-t border-slate-100 dark:border-slate-800 gap-1">
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
                        style="color: #ffffff !important;"
                        title="Sign in to view details">
                    <span class="hidden sm:inline" style="color: #ffffff !important;">View Details</span>
                    <span class="sm:hidden" style="color: #ffffff !important;">View</span>
                    <i class="ph-bold ph-lock text-[9px] sm:text-xs" style="color: #ffffff !important;"></i>
                </button>
            @else
                <span class="inline-flex items-center justify-center gap-1 px-2.5 py-1.5 sm:px-3.5 sm:py-2 bg-blue-600 group-hover:bg-blue-700 active:bg-blue-800 text-white text-[10px] sm:text-xs font-bold rounded-lg sm:rounded-xl shadow-xs hover:shadow transition-all shrink-0"
                      style="color: #ffffff !important;">
                    <span class="hidden sm:inline" style="color: #ffffff !important;">View Details</span>
                    <span class="sm:hidden" style="color: #ffffff !important;">View</span>
                    <i class="ph-bold ph-arrow-right text-[9px] sm:text-xs group-hover:translate-x-0.5 transition-transform" style="color: #ffffff !important;"></i>
                </span>
            @endguest
        </div>
    </div>
</article>

@once
<style>
/* ─── BOOKED CORNER RIBBON (DIAGONAL 45° SASH CROSSING CORNER) ─── */
.ur-booked-ribbon-wrap {
    position: absolute !important;
    top: 0 !important;
    right: 0 !important;
    width: 92px !important;
    height: 92px !important;
    overflow: hidden !important;
    pointer-events: none !important;
    z-index: 30 !important;
}

.ur-booked-ribbon {
    position: absolute !important;
    top: 16px !important;
    right: -28px !important;
    width: 122px !important;
    background: linear-gradient(135deg, #e11d48 0%, #dc2626 100%) !important;
    color: #ffffff !important;
    font-size: 9px !important;
    font-weight: 900 !important;
    letter-spacing: 0.12em !important;
    text-transform: uppercase !important;
    text-align: center !important;
    padding: 3.5px 0 !important;
    line-height: 1 !important;
    transform: rotate(45deg) !important;
    -webkit-transform: rotate(45deg) !important;
    transform-origin: center center !important;
    -webkit-transform-origin: center center !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.35) !important;
    border-top: 1px solid rgba(255, 255, 255, 0.4) !important;
    border-bottom: 1px solid rgba(0, 0, 0, 0.2) !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 3px !important;
    user-select: none !important;
    white-space: nowrap !important;
}

.ur-booked-ribbon i {
    font-size: 10px !important;
    color: #ffffff !important;
    line-height: 1 !important;
}

.ur-booked-ribbon span {
    color: #ffffff !important;
    font-weight: 900 !important;
    line-height: 1 !important;
    letter-spacing: 0.12em !important;
}

@media (min-width: 640px) {
    .ur-booked-ribbon-wrap {
        width: 115px !important;
        height: 115px !important;
    }
    .ur-booked-ribbon {
        top: 22px !important;
        right: -32px !important;
        width: 150px !important;
        font-size: 10.5px !important;
        padding: 5px 0 !important;
        gap: 4px !important;
    }
    .ur-booked-ribbon i {
        font-size: 12px !important;
    }
}

/* ─── CARD MULTI-IMAGE AUTO-SLIDER (RIGHT-TO-LEFT) ─── */
.card-image-slider {
    position: relative;
    width: 100%;
    height: 100%;
    overflow: hidden;
    touch-action: pan-y pinch-zoom;
}
.card-slider-track {
    display: flex;
    width: 100%;
    height: 100%;
    transition: transform 0.55s cubic-bezier(0.25, 1, 0.5, 1);
    will-change: transform;
}
.card-slide {
    flex: 0 0 100%;
    width: 100%;
    height: 100%;
    position: relative;
}
</style>

<script>
(function() {
    if (window._urCardSliderLoaded) {
        if (window._urCardSliderInit) window._urCardSliderInit();
        return;
    }
    window._urCardSliderLoaded = true;

    const sliderStates = new Map();
    let cardObserver = null;

    function initCardSliders() {
        const sliders = document.querySelectorAll('[data-card-slider]');
        sliders.forEach(slider => {
            const id = slider.dataset.cardSlider;
            const total = parseInt(slider.dataset.totalSlides, 10);
            if (!id || total <= 1 || sliderStates.has(id)) return;

            const state = {
                id: id,
                current: 0,
                total: total,
                element: slider,
                track: slider.querySelector('.card-slider-track'),
                counter: slider.querySelector('.card-slide-counter'),
                dots: slider.querySelectorAll('.card-slider-dot'),
                timer: null,
                isHovered: false,
                isVisible: false
            };
            sliderStates.set(id, state);

            // Desktop hover pause/resume
            slider.addEventListener('mouseenter', () => {
                state.isHovered = true;
                pauseSlider(state);
            });
            slider.addEventListener('mouseleave', () => {
                state.isHovered = false;
                if (state.isVisible) startSlider(state);
            });

            // Mobile touch swipe support
            let startX = 0;
            let startY = 0;
            let isSwiping = false;

            slider.addEventListener('touchstart', (e) => {
                if (e.touches.length === 1) {
                    startX = e.touches[0].clientX;
                    startY = e.touches[0].clientY;
                    isSwiping = true;
                    pauseSlider(state);
                }
            }, { passive: true });

            slider.addEventListener('touchend', (e) => {
                if (!isSwiping || !e.changedTouches || e.changedTouches.length === 0) return;
                isSwiping = false;
                const endX = e.changedTouches[0].clientX;
                const endY = e.changedTouches[0].clientY;
                const diffX = endX - startX;
                const diffY = endY - startY;

                // Horizontal swipe detection (swipe left = next slide from right to left)
                if (Math.abs(diffX) > 28 && Math.abs(diffX) > Math.abs(diffY)) {
                    if (diffX < 0) {
                        nextSlide(state);
                    } else {
                        prevSlide(state);
                    }
                }
                if (state.isVisible && !state.isHovered) {
                    startSlider(state);
                }
            }, { passive: true });

            // IntersectionObserver: auto-slide only when card is visible in viewport
            if (!cardObserver && 'IntersectionObserver' in window) {
                cardObserver = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        const sid = entry.target.dataset.cardSlider;
                        const sstate = sliderStates.get(sid);
                        if (!sstate) return;

                        if (entry.isIntersecting) {
                            sstate.isVisible = true;
                            if (!sstate.isHovered) startSlider(sstate);
                        } else {
                            sstate.isVisible = false;
                            pauseSlider(sstate);
                        }
                    });
                }, { threshold: 0.25 });
            }

            if (cardObserver) {
                cardObserver.observe(slider);
            } else {
                state.isVisible = true;
                startSlider(state);
            }
        });
    }

    function goToSlide(state, index) {
        state.current = (index + state.total) % state.total;
        if (state.track) {
            state.track.style.transform = 'translate3d(-' + (state.current * 100) + '%, 0, 0)';
        }
        if (state.counter) {
            state.counter.textContent = (state.current + 1) + '/' + state.total;
        }
        if (state.dots && state.dots.length > 0) {
            state.dots.forEach((dot, idx) => {
                if (idx === state.current) {
                    dot.className = 'card-slider-dot h-1.5 rounded-full transition-all duration-300 w-3.5 bg-white shadow-xs';
                } else {
                    dot.className = 'card-slider-dot h-1.5 rounded-full transition-all duration-300 w-1.5 bg-white/50 hover:bg-white/80';
                }
            });
        }
    }

    function nextSlide(state) {
        goToSlide(state, state.current + 1);
    }

    function prevSlide(state) {
        goToSlide(state, state.current - 1);
    }

    function startSlider(state) {
        pauseSlider(state);
        // Stagger initial slide timing based on property ID to create natural organic motion
        const cardIdNum = parseInt(state.id, 10) || 0;
        const staggerDelay = (cardIdNum % 5) * 400;

        setTimeout(() => {
            if (!state.isVisible || state.isHovered || state.timer) return;
            nextSlide(state);
            state.timer = setInterval(() => {
                if (state.isVisible && !state.isHovered) {
                    nextSlide(state);
                }
            }, 3600);
        }, staggerDelay + 1800);
    }

    function pauseSlider(state) {
        if (state.timer) {
            clearInterval(state.timer);
            state.timer = null;
        }
    }

    window.cardSliderNext = function(id) {
        const state = sliderStates.get(String(id));
        if (state) {
            nextSlide(state);
            startSlider(state);
        }
    };

    window.cardSliderPrev = function(id) {
        const state = sliderStates.get(String(id));
        if (state) {
            prevSlide(state);
            startSlider(state);
        }
    };

    window.cardSliderGoTo = function(id, index) {
        const state = sliderStates.get(String(id));
        if (state) {
            goToSlide(state, index);
            startSlider(state);
        }
    };

    window._urCardSliderInit = initCardSliders;

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCardSliders);
    } else {
        initCardSliders();
    }

    document.addEventListener('ur:page-loaded', initCardSliders);
    window.addEventListener('popstate', () => setTimeout(initCardSliders, 150));
})();
</script>
@endonce
