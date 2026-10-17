{{-- ============================================================
     UnlockRentals.com — Luxury Real Estate Property Card
     Pixel-accurate implementation matching luxury direct listings design
     ============================================================ --}}

@php
    $propertyUrl = Route::has('properties.show') ? route('properties.show', $property) : url('/properties/' . $property->id);

    // Standard title casing with uppercase for acronyms (e.g. "1 BHK Big Floor", "PG", "RK")
    $cleanTitle = ucwords(strtolower(trim($property->title)));
    $cleanTitle = preg_replace_callback('/\b(\d+)?\s*(bhk|rk|pg)\b/i', function($m) {
        return strtoupper($m[0]);
    }, $cleanTitle);

    $isSale = ($property->purpose ?? 'rent') === 'buy' || ($property->purpose ?? 'rent') === 'sell';
    $hasFixedPrice = (float)$property->price > 0;
    $propType = $property->type ?? 'house';

    // 1. Direct Verification Badge (Top-left pill 1)
    if ($propType === 'plot') {
        $directBadge = '100% DIRECT TITLE';
    } elseif ($isSale) {
        $directBadge = '100% DIRECT OWNER';
    } else {
        $directBadge = '100% DIRECT';
    }

    // 2. Feature Incentive Badge (Top-left pill 2)
    if ($propType === 'pg-hostel') {
        $featureBadge = 'FOOD & WI-FI INCLUDED';
        $featureBadgeClass = 'bg-blue-50/95 text-blue-700 dark:bg-blue-950/90 dark:text-blue-300 border border-blue-200/70';
    } elseif ($propType === 'shop') {
        $featureBadge = '0% BROKER FEE';
        $featureBadgeClass = 'bg-emerald-50/95 text-emerald-700 dark:bg-emerald-950/90 dark:text-emerald-300 border border-emerald-200/70';
    } elseif ($propType === 'plot') {
        $featureBadge = 'FREEHOLD PLOT';
        $featureBadgeClass = 'bg-amber-50/95 text-amber-800 dark:bg-amber-950/90 dark:text-amber-300 border border-amber-200/70';
    } elseif ($isSale) {
        $featureBadge = '0% BROKERAGE';
        $featureBadgeClass = 'bg-emerald-50/95 text-emerald-700 dark:bg-emerald-950/90 dark:text-emerald-300 border border-emerald-200/70';
    } else {
        $featureBadge = '0% BROKER FEE';
        $featureBadgeClass = 'bg-emerald-50/95 text-emerald-700 dark:bg-emerald-950/90 dark:text-emerald-300 border border-emerald-200/70';
    }

    // 3. Short Locality Tag (Bottom-left over image)
    $shortLocality = '';
    if (!empty($property->locality)) {
        $shortLocality = $property->locality;
        if (!empty($property->location)) {
            $locCity = trim(explode(',', $property->location)[0]);
            if (!str_contains(strtolower($shortLocality), strtolower($locCity))) {
                $shortLocality .= ', ' . $locCity;
            }
        }
    } elseif (!empty($property->location)) {
        $shortLocality = $property->location;
    } else {
        $shortLocality = 'Prime Location';
    }

    // 4. Sub-category Header & Move-In Status Tag
    if ($propType === 'pg-hostel') {
        $subCatTag = 'PG / CO-LIVING · BOYS & GIRLS';
        $statusTag = 'Immediate Move-In';
        $statusTagClass = 'text-emerald-600 dark:text-emerald-400';
    } elseif ($propType === 'shop') {
        $subCatTag = 'COMMERCIAL · HIGH FOOTFALL';
        $statusTag = 'Ground Floor';
        $statusTagClass = 'text-blue-600 dark:text-blue-400';
    } elseif ($propType === 'plot') {
        $subCatTag = 'RESIDENTIAL PLOT · GATED SOCIETY';
        $statusTag = 'Clear Deeded';
        $statusTagClass = 'text-emerald-600 dark:text-emerald-400';
    } elseif ($isSale) {
        $catName = ($property->relationLoaded('category') && $property->category) ? strtoupper($property->category->name) : 'RESIDENTIAL';
        $subCatTag = $catName . ' · FOR SALE';
        $statusTag = 'Ready Possession';
        $statusTagClass = 'text-emerald-600 dark:text-emerald-400';
    } else {
        $catName = ($property->relationLoaded('category') && $property->category) ? strtoupper($property->category->name) : 'RESIDENTIAL APARTMENT';
        $furnish = !empty($property->furnishing) ? strtoupper($property->furnishing) : 'FAMILY & BACHELOR';
        $subCatTag = $catName . ' · ' . $furnish;
        $statusTag = 'Immediate Move-In';
        $statusTagClass = 'text-emerald-600 dark:text-emerald-400';
    }

    if ($property->is_booked) {
        $statusTag = 'Booked';
        $statusTagClass = 'text-rose-600 dark:text-rose-400';
    }

    // 5. Full Display Location
    $displayLocation = '';
    if (!empty($property->address)) {
        $displayLocation = $property->address;
    } elseif (!empty($property->locality)) {
        $displayLocation = 'Near ' . $property->locality . ($property->location ? ', ' . $property->location : '');
    } else {
        $displayLocation = $property->location . ($property->state ? ', ' . $property->state : '');
    }

    // 6. 3-Column Specifications Box Data
    if ($propType === 'pg-hostel') {
        $spec1Label = 'MEALS';
        $spec1Val   = '3 Times';
        $spec2Label = 'AMENITIES';
        $spec2Val   = 'AC + Gym';
        $spec3Label = 'CLEANING';
        $spec3Val   = 'Daily';
    } elseif ($propType === 'shop') {
        $spec1Label = 'SUPER AREA';
        $spec1Val   = !empty($property->area_sqft) ? number_format($property->area_sqft) . ' sq.ft' : '650 sq.ft';
        $spec2Label = 'FRONTAGE';
        $spec2Val   = '18 Feet';
        $spec3Label = 'POWER';
        $spec3Val   = '100% Backup';
    } elseif ($propType === 'plot') {
        $sqft = !empty($property->area_sqft) ? (int)$property->area_sqft : 1800;
        $spec1Label = 'PLOT AREA';
        $spec1Val   = number_format($sqft) . ' sq.ft';
        $spec2Label = 'FACING';
        $spec2Val   = 'North-East';
        $spec3Label = 'PERMITTED';
        $spec3Val   = 'Stilt + 4 Flr';
    } else {
        // House / Flat / Apartment
        $spec1Label = 'CONFIG';
        $spec1Val   = ($property->bedrooms !== null && $property->bedrooms !== '') ? ($property->bedrooms == 0 ? '1 RK' : $property->bedrooms . ' BHK') : '2 BHK';
        $spec2Label = 'SUPER AREA';
        $spec2Val   = !empty($property->area_sqft) ? number_format($property->area_sqft) . ' sq.ft' : ($property->bathrooms ? $property->bathrooms . ' Baths' : '1,200 sq.ft');
        $spec3Label = 'FURNISHING';
        $spec3Val   = !empty($property->furnishing) ? ucfirst($property->furnishing) : 'Semi-Furnished';
    }

    // 7. Footer Pricing Caption & Button Action
    if ($propType === 'pg-hostel') {
        $priceCaption = 'Starts From';
        $buttonText   = 'Contact Direct';
        $buttonIcon   = 'ph-bold ph-chat-circle-dots';
    } elseif ($propType === 'shop') {
        $priceCaption = 'Monthly Lease';
        $buttonText   = 'Contact Owner';
        $buttonIcon   = 'ph-bold ph-identification-card';
    } elseif ($isSale || $propType === 'plot') {
        $priceCaption = 'Direct Buy Price';
        $buttonText   = 'Contact Seller';
        $buttonIcon   = 'ph-bold ph-phone-call';
    } else {
        $priceCaption = 'Monthly Rent';
        $buttonText   = 'Contact Owner';
        $buttonIcon   = 'ph-bold ph-identification-card';
    }

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
    $cardImages = $cardImages->take(6);
    $hasMultipleImages = $cardImages->count() > 1;

    $isEager = !empty($eager) || (isset($loop) && $loop->index < 4);
    $loadingAttr = $isEager ? 'eager' : 'lazy';
    $priorityAttr = $isEager ? 'high' : 'low';
@endphp

<article class="property-rental-card group relative flex flex-col h-full bg-white dark:bg-slate-900 rounded-2xl sm:rounded-3xl border border-slate-200/90 dark:border-slate-800 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden"
    id="property-card-{{ $property->id }}"
    data-property-card="true"
    data-property-id="{{ $property->id }}"
    data-price="{{ (float)$property->price }}"
    data-is-booked="{{ $property->is_booked ? '1' : '0' }}"
    data-has-image="{{ $property->primaryImageUrl() ? '1' : '0' }}"
    data-has-video="{{ $property->hasVideo() ? '1' : '0' }}"
    data-created-at="{{ $property->created_at ? $property->created_at->timestamp : 0 }}"
    data-is-featured="{{ $property->is_featured ? '1' : '0' }}">

    {{-- Accessible Full-Card Overlay Link --}}
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

    {{-- 1. Property Image Section (Aspect 16:10 with Luxury Badges & Heart Action) --}}
    <div class="card-image-wrap relative w-full overflow-hidden bg-slate-100 dark:bg-slate-800 flex-shrink-0" style="aspect-ratio: 16 / 10;">
        @if($hasMultipleImages)
            {{-- Multi-Image Auto-Slider --}}
            <div class="card-image-slider relative w-full h-full overflow-hidden select-none"
                 data-card-slider="{{ $property->id }}"
                 data-total-slides="{{ $cardImages->count() }}">
                
                <div class="card-slider-track flex h-full w-full"
                     style="transform: translate3d(0, 0, 0);">
                    @foreach($cardImages as $idx => $img)
                        <div class="card-slide flex-shrink-0 w-full h-full relative">
                            <img src="{{ $img->imageUrl() }}"
                                 alt="{{ $cleanTitle }} - Photo {{ $idx + 1 }}"
                                 title="{{ $cleanTitle }} - Photo {{ $idx + 1 }}"
                                 width="540"
                                 height="340"
                                 onerror="this.onerror=null; this.src='{{ asset('images/luxury_sunlit.webp') }}';"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out pointer-events-none"
                                 loading="{{ $idx === 0 ? $loadingAttr : 'lazy' }}"
                                 fetchpriority="{{ $idx === 0 ? $priorityAttr : 'low' }}"
                                 decoding="async">
                        </div>
                    @endforeach
                </div>

                {{-- Micro Pagination Dots --}}
                <div class="card-slider-dots absolute bottom-2.5 left-1/2 -translate-x-1/2 z-20 flex items-center gap-1 px-2 py-0.5 rounded-full bg-black/50 backdrop-blur-xs pointer-events-auto"
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
            </div>
        @elseif($property->primaryImageUrl())
            <img src="{{ $property->primaryImageUrl() }}"
                 alt="{{ $cleanTitle }}"
                 title="{{ $cleanTitle }}"
                 width="540"
                 height="340"
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
                     width="540"
                     height="340"
                     class="w-full h-full object-cover filter brightness-[0.55] group-hover:scale-105 transition-transform duration-500 ease-out"
                     loading="{{ $loadingAttr }}"
                     fetchpriority="{{ $priorityAttr }}"
                     decoding="async">
                <div class="absolute inset-0 flex flex-col items-center justify-center text-white pointer-events-none">
                    <div class="w-9 h-9 rounded-full bg-white/25 backdrop-blur-md border border-white/40 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform mb-1">
                        <i class="ph-fill ph-play text-sm ml-0.5 text-white"></i>
                    </div>
                    <span class="text-[9.5px] font-extrabold uppercase tracking-wider text-white drop-shadow">Video Tour</span>
                </div>
            </div>
        @else
            <img src="{{ asset('images/luxury_sunlit.webp') }}"
                 alt="Premium Property - {{ $cleanTitle }}"
                 title="Premium Property - {{ $cleanTitle }}"
                 width="540"
                 height="340"
                 loading="{{ $loadingAttr }}"
                 fetchpriority="{{ $priorityAttr }}"
                 decoding="async"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out">
        @endif

        {{-- Top Gradient Overlay for Badge Contrast --}}
        <div class="absolute inset-0 bg-gradient-to-b from-black/45 via-transparent to-transparent pointer-events-none"></div>

        {{-- Top Left Badges & Top Right Favorite Heart --}}
        <div class="absolute top-2.5 left-2.5 right-2.5 flex items-center justify-between gap-2 z-20 pointer-events-none">
            {{-- Top Left Pill Badges --}}
            <div class="flex items-center gap-1.5 flex-wrap">
                {{-- 1. Direct Verification Badge --}}
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-white/95 dark:bg-slate-900/95 text-[9.5px] sm:text-[10px] font-black uppercase tracking-wide text-slate-800 dark:text-slate-100 shadow-sm border border-white/70">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span>{{ $directBadge }}</span>
                </span>

                {{-- 2. Feature / Incentive Badge --}}
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[9.5px] sm:text-[10px] font-black uppercase tracking-wide shadow-sm {{ $featureBadgeClass }}">
                    <span>{{ $featureBadge }}</span>
                </span>
            </div>

            {{-- Top Right: Favorite Wishlist Heart Button --}}
            <button type="button"
                    onclick="event.preventDefault(); event.stopPropagation(); window.toggleCardFavorite(this, {{ $property->id }});"
                    class="card-fav-btn w-8 h-8 rounded-full bg-white/95 dark:bg-slate-900/95 hover:bg-white text-slate-500 hover:text-rose-500 flex items-center justify-center shadow-md border border-white/70 transition-all hover:scale-110 active:scale-90 pointer-events-auto cursor-pointer"
                    title="Save to favorites"
                    data-property-fav="{{ $property->id }}">
                <i class="ph-bold ph-heart text-sm sm:text-base text-slate-600 transition-colors"></i>
            </button>
        </div>

        {{-- Bottom Left: Dark Locality Tag Pill (e.g., "Sector 62, Noida") --}}
        <div class="absolute bottom-2.5 left-2.5 z-20 pointer-events-none">
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-slate-950/80 backdrop-blur-xs text-white text-[10px] sm:text-[11px] font-black tracking-tight shadow-md border border-white/10">
                {{ $shortLocality }}
            </span>
        </div>

        {{-- Booked Corner Ribbon (if booked) --}}
        @if($property->is_booked)
            <div class="ur-booked-ribbon-wrap absolute top-0 right-0 pointer-events-none z-30 overflow-hidden"
                 style="position: absolute; top: 0; right: 0; width: 84px; height: 84px; overflow: hidden; pointer-events: none; z-index: 30;">
                <div class="ur-booked-ribbon"
                     style="position: absolute; top: 14px; right: -26px; width: 115px; transform: rotate(45deg); -webkit-transform: rotate(45deg); transform-origin: center center; -webkit-transform-origin: center center; background: linear-gradient(135deg, #e11d48 0%, #dc2626 100%); color: #ffffff; font-size: 8.5px; font-weight: 900; letter-spacing: 0.12em; text-transform: uppercase; text-align: center; padding: 3px 0; line-height: 1; box-shadow: 0 4px 12px rgba(0,0,0,0.35); border-top: 1px solid rgba(255,255,255,0.4); border-bottom: 1px solid rgba(0,0,0,0.2); display: flex; align-items: center; justify-content: center; gap: 3px; user-select: none; white-space: nowrap;">
                    <i class="ph-bold ph-lock-key" style="font-size: 9px; color: #ffffff; line-height: 1;"></i>
                    <span style="color: #ffffff; font-weight: 900; letter-spacing: 0.12em; line-height: 1;">Booked</span>
                </div>
            </div>
        @endif
    </div>

    {{-- 2. Card Content Area --}}
    <div class="p-3.5 sm:p-4.5 flex flex-col flex-1 gap-2 sm:gap-2.5 bg-white dark:bg-slate-900">
        
        {{-- Row A: Category Uppercase Subtitle & Status Tag --}}
        <div class="flex items-center justify-between gap-2">
            <span class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 truncate">
                {{ $subCatTag }}
            </span>
            <span class="text-[10.5px] sm:text-[11px] font-black tracking-wide shrink-0 {{ $statusTagClass }}">
                {{ $statusTag }}
            </span>
        </div>

        {{-- Row B: Property Title --}}
        <h3 class="text-sm sm:text-[15.5px] font-black text-slate-900 dark:text-white line-clamp-1 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors leading-snug" title="{{ $cleanTitle }}">
            {{ $cleanTitle }}
        </h3>

        {{-- Row C: Location with Map Pin --}}
        <div class="flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400 font-semibold min-w-0">
            <i class="ph-bold ph-map-pin text-blue-600 text-xs shrink-0"></i>
            <span class="truncate capitalize">
                {{ $displayLocation }}
            </span>
        </div>

        {{-- Row D: Dedicated 3-Column Specifications Box --}}
        <div class="bg-blue-50/50 dark:bg-slate-800/60 rounded-xl p-2 sm:p-2.5 grid grid-cols-3 gap-1.5 border border-blue-100/70 dark:border-slate-700/60 text-center my-0.5">
            <div>
                <span class="block text-[8.5px] sm:text-[9.5px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-0.5">{{ $spec1Label }}</span>
                <span class="block text-[11.5px] sm:text-xs font-black text-slate-900 dark:text-white truncate">{{ $spec1Val }}</span>
            </div>
            <div class="border-x border-slate-200/70 dark:border-slate-700/70 px-1">
                <span class="block text-[8.5px] sm:text-[9.5px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-0.5">{{ $spec2Label }}</span>
                <span class="block text-[11.5px] sm:text-xs font-black text-slate-900 dark:text-white truncate">{{ $spec2Val }}</span>
            </div>
            <div>
                <span class="block text-[8.5px] sm:text-[9.5px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-0.5">{{ $spec3Label }}</span>
                <span class="block text-[11.5px] sm:text-xs font-black text-slate-900 dark:text-white truncate">{{ $spec3Val }}</span>
            </div>
        </div>

        {{-- Row E: Price & Action Row --}}
        <div class="flex items-end justify-between pt-1.5 sm:pt-2 mt-auto gap-2 border-t border-slate-100 dark:border-slate-800">
            {{-- Left: Price Caption + Price --}}
            <div class="flex flex-col min-w-0">
                <span class="text-[10px] sm:text-[10.5px] font-bold text-slate-500 dark:text-slate-400 leading-none mb-1">
                    {{ $priceCaption }}
                </span>
                <span class="text-base sm:text-lg font-black text-slate-900 dark:text-white tracking-tight leading-none truncate">
                    {{ $property->formatted_price }}
                </span>
            </div>

            {{-- Right: Direct Blue Action Button --}}
            @guest
                <button type="button"
                        onclick="event.preventDefault(); event.stopPropagation(); window.openAuthModal('login', '{{ $propertyUrl }}');"
                        data-no-loader="true"
                        data-ur-loader-skip="true"
                        class="px-3.5 py-2 sm:px-4 sm:py-2.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-xs sm:text-xs font-black rounded-xl shadow-xs hover:shadow-md transition-all flex items-center gap-1.5 shrink-0 relative z-20 cursor-pointer active:scale-95"
                        style="color: #ffffff !important;">
                    <i class="{{ $buttonIcon }} text-sm" style="color: #ffffff !important;"></i>
                    <span style="color: #ffffff !important;">{{ $buttonText }}</span>
                </button>
            @else
                <a href="{{ $propertyUrl }}"
                   class="px-3.5 py-2 sm:px-4 sm:py-2.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-xs sm:text-xs font-black rounded-xl shadow-xs hover:shadow-md transition-all flex items-center gap-1.5 shrink-0 relative z-20 cursor-pointer active:scale-95"
                   style="color: #ffffff !important;">
                    <i class="{{ $buttonIcon }} text-sm" style="color: #ffffff !important;"></i>
                    <span style="color: #ffffff !important;">{{ $buttonText }}</span>
                </a>
            @endguest
        </div>
    </div>
</article>

@once
<script>
// Wishlist / Favorite Heart Toggle Handler
window.toggleCardFavorite = function(btn, propId) {
    let saved = JSON.parse(localStorage.getItem('ur_favorite_properties') || '[]');
    const idx = saved.indexOf(propId);
    const icon = btn.querySelector('i');
    if (idx > -1) {
        saved.splice(idx, 1);
        if (icon) {
            icon.className = 'ph-bold ph-heart text-sm sm:text-base text-slate-600 transition-colors';
        }
    } else {
        saved.push(propId);
        if (icon) {
            icon.className = 'ph-fill ph-heart text-sm sm:text-base text-rose-500 transition-colors';
        }
    }
    localStorage.setItem('ur_favorite_properties', JSON.stringify(saved));
};

document.addEventListener('DOMContentLoaded', () => {
    try {
        const saved = JSON.parse(localStorage.getItem('ur_favorite_properties') || '[]');
        if (Array.isArray(saved) && saved.length > 0) {
            document.querySelectorAll('[data-property-fav]').forEach(btn => {
                const id = parseInt(btn.getAttribute('data-property-fav'));
                if (saved.includes(id)) {
                    const icon = btn.querySelector('i');
                    if (icon) icon.className = 'ph-fill ph-heart text-sm sm:text-base text-rose-500';
                }
            });
        }
    } catch(e) {}
});

// Card Multi-Image Auto-Slider Handler
(function() {
    window.cardSliderGoTo = function(propId, slideIndex) {
        const card = document.getElementById('property-card-' + propId);
        if (!card) return;
        const slider = card.querySelector('.card-image-slider');
        if (!slider) return;
        const track = slider.querySelector('.card-slider-track');
        if (!track) return;
        track.style.transform = `translate3d(-${slideIndex * 100}%, 0, 0)`;

        slider.querySelectorAll('.card-slider-dot').forEach((dot, idx) => {
            if (idx === slideIndex) {
                dot.className = 'card-slider-dot h-1.5 rounded-full transition-all duration-300 w-3.5 bg-white shadow-xs';
            } else {
                dot.className = 'card-slider-dot h-1.5 rounded-full transition-all duration-300 w-1.5 bg-white/50 hover:bg-white/80';
            }
        });
    };
})();
</script>
@endonce
