{{-- =========================================================================
     UnlockRentals — 99acres-Style Wide Horizontal Property Card Component
     Matches exact design: Photo on left, 3-column metrics, nearby tags, 
     dealer profile, "View Number", and "Contact" buttons.
     ========================================================================= --}}

@props(['property'])

@php
    $propertyUrl = Route::has('properties.show') ? route('properties.show', $property) : url('/properties/' . $property->id);

    // Formatted Clean Title
    $rawTitle = \App\Models\Property::cleanUrlFromText($property->title, true);
    $cleanTitle = ucwords(strtolower(trim($rawTitle)));
    $cleanTitle = preg_replace_callback('/\b(\d+)?\s*(bhk|rk|pg)\b/i', fn($m) => strtoupper($m[0]), $cleanTitle);

    // Purpose & Type
    $isRent = ($property->purpose ?? 'rent') === 'rent';
    $purposeText = $isRent ? 'for rent' : 'for sale';
    $beds = (int)($property->bedrooms ?? 2);
    $baths = (int)($property->bathrooms ?? max(1, $beds - 1));
    $sqft = (int)($property->area_sqft ?: 1200);
    $sqm = round($sqft / 10.764);

    // Formatted Price in Indian Lac / Cr or thousands
    $priceNum = (float)($property->price ?? 0);
    if ($priceNum >= 10000000) {
        $formattedPrice = '₹' . rtrim(rtrim(number_format($priceNum / 10000000, 2), '0'), '.') . ' Cr';
    } elseif ($priceNum >= 100000) {
        $formattedPrice = '₹' . rtrim(rtrim(number_format($priceNum / 100000, 2), '0'), '.') . ' Lac';
    } else {
        $formattedPrice = '₹' . number_format($priceNum);
    }

    // Security deposit calculation (standard 1 or 2 months rent or 0)
    $depositVal = (float)($property->deposit ?? 0);
    if ($depositVal <= 0 && $isRent && $priceNum > 0) {
        $depositVal = ($priceNum > 100000) ? ($priceNum * 2) : $priceNum;
    }
    if ($depositVal >= 10000000) {
        $depositText = '₹' . rtrim(rtrim(number_format($depositVal / 10000000, 2), '0'), '.') . ' Cr';
    } elseif ($depositVal >= 100000) {
        $depositText = '₹' . rtrim(rtrim(number_format($depositVal / 100000, 2), '0'), '.') . ' Lac';
    } else {
        $depositText = '₹' . number_format($depositVal);
    }

    // Collect all images for the card
    $cardImages = collect();
    if ($property->relationLoaded('images') && $property->images && $property->images->isNotEmpty()) {
        $cardImages = $property->images;
    } elseif ($property->relationLoaded('primaryImage') && $property->primaryImage) {
        $cardImages = collect([$property->primaryImage]);
    } else {
        $cardImages = $property->images()->take(10)->get();
    }

    if ($cardImages->isNotEmpty()) {
        $cardImages = $cardImages->unique(function ($img) {
            $p = $img->path ?? '';
            return preg_replace('/\.(jpg|jpeg|png|webp)(\?.*)?$/i', '', trim($p));
        })->values();

        $primaryImg = $cardImages->firstWhere('is_primary', true);
        if ($primaryImg) {
            $cardImages = collect([$primaryImg])->merge($cardImages->reject(fn($img) => $img->id === $primaryImg->id))->values();
        }
    }
    $fallbackPhotos = \App\Models\PropertyImage::VERIFIED_REAL_PHOTOS;
    $cardFallbackPhoto = $fallbackPhotos[abs((int)$property->id) % count($fallbackPhotos)];

    $primaryUrl = $cardImages->first()?->imageUrl() ?? $property->primaryImageUrl();
    if (!$primaryUrl || str_contains($primaryUrl, 'property_placeholder') || str_contains($primaryUrl, '858815159M') || str_contains($primaryUrl, 'luxury_sunlit')) {
        $primaryUrl = $cardFallbackPhoto;
    }
    $imagesCount = $cardImages->count() > 0 ? $cardImages->count() : ($property->images_count ?? 1);

    // Owner / Dealer Information - Strict filtering to eliminate "Admin User"
    $owner = $property->owner ?? $property->user;
    $rawOwnerName = trim($owner?->name ?? '');

    $isAdmin = empty($rawOwnerName)
        || preg_match('/\b(admin|administrator|root|system)\b/i', $rawOwnerName)
        || ($owner && $owner->role === 'admin');

    if ($isAdmin) {
        $ownerName = 'Owner';
        $isDealer = false;
    } else {
        $ownerName = $rawOwnerName;
        $isDealer = str_contains(strtolower($rawOwnerName), 'realt') 
            || str_contains(strtolower($rawOwnerName), 'properties') 
            || str_contains(strtolower($rawOwnerName), 'associates') 
            || str_contains(strtolower($rawOwnerName), 'service')
            || str_contains(strtolower($rawOwnerName), 'estate');
    }

    $ownerPhone = $property->contact_phone ?? $owner?->phone ?? '+91 98765 43210';
    $cleanPhone = preg_replace('/\D/', '', $ownerPhone);
    $postedTime = $property->created_at ? $property->created_at->diffForHumans(null, true) . ' ago' : 'Recently';

    // Locality and Subtitle: Strictly sanitize and eliminate any URLs or 99acres references
    $rawLocality = $property->locality ?: ($property->location ?: 'Delhi NCR');
    $rawCity = $property->location ?: 'Delhi';
    $locality = \App\Models\Property::cleanUrlFromText($rawLocality, false) ?: 'Delhi NCR';
    $city = \App\Models\Property::cleanUrlFromText($rawCity, true) ?: 'Delhi';

    // Prevent duplicate city names in heading (e.g. "Sector 48, Noida, Noida")
    if (strcasecmp($locality, $city) === 0 || str_contains(strtolower($locality), strtolower($city))) {
        $headingLocation = $locality;
    } else {
        $headingLocation = "{$locality}, {$city}";
    }
    $headingLocation = \App\Models\Property::cleanUrlFromText($headingLocation, false);

    $propTypeLabel = $property->type === 'house' ? 'Builder Floor' : ($property->type === 'plot' ? 'Plot' : ($property->type === 'shop' ? 'Commercial' : ($property->category?->name ?? 'Flat')));
    $subheading = "{$beds} BHK {$propTypeLabel} {$purposeText} in {$locality}";
    $subheading = \App\Models\Property::cleanUrlFromText($subheading, false);

    // PG / Paying Guest Detection & Parsing
    $isPg = ($property->type === 'pg-hostel')
        || str_contains(strtolower($property->title), 'pg')
        || str_contains(strtolower($property->title), 'paying guest')
        || str_contains(strtolower($property->title), 'hostel')
        || str_contains(strtolower($property->category?->name ?? ''), 'pg')
        || str_contains(strtolower($property->category?->name ?? ''), 'hostel')
        || ($property->category_id == 7);

    $pgHeadingName = $cleanTitle;
    $pgSubheading = null;
    $sharingType = 'Shared Room';
    $sharingSubtitle = 'shared by 2';

    if ($isPg) {
        if (str_contains($cleanTitle, ' - ')) {
            $parts = explode(' - ', $cleanTitle, 2);
            $pgHeadingName = trim($parts[0]);
            $pgSubheading = trim($parts[1]);
        } elseif (str_contains($cleanTitle, ' — ')) {
            $parts = explode(' — ', $cleanTitle, 2);
            $pgHeadingName = trim($parts[0]);
            $pgSubheading = trim($parts[1]);
        }
        if (!$pgSubheading) {
            $pgSubheading = "Girls & Boys PG/Paying Guest in {$headingLocation}";
        }

        // Sharing type detection from description or title
        $descLower = strtolower($property->description ?? '');
        if (str_contains($descLower, 'private room') || str_contains($descLower, 'single room') || str_contains($descLower, 'single sharing')) {
            $sharingType = 'Private Room';
            $sharingSubtitle = 'Single occupancy';
        } elseif (str_contains($descLower, 'triple') || str_contains($descLower, 'shared by 3') || str_contains($descLower, '3 sharing')) {
            $sharingType = 'Shared Room';
            $sharingSubtitle = 'shared by 3';
        } elseif (str_contains($descLower, 'four') || str_contains($descLower, 'shared by 4')) {
            $sharingType = 'Shared Room';
            $sharingSubtitle = 'shared by 4';
        } else {
            $sharingType = 'Shared Room';
            $sharingSubtitle = 'shared by 2';
        }
    }

    // Description teaser
    $desc = strip_tags($property->description ?? "Carefully laid out in the prime location of {$locality}, this {$beds} BHK {$propTypeLabel} features modern fixtures, natural lighting, and superior connectivity.");
    $desc = \App\Models\Property::cleanUrlFromText($desc, false);
    $descTeaser = \Illuminate\Support\Str::limit($desc, 110);

    // Nearby Transit tags
    $cleanLocTag = trim(explode(',', $locality)[0]) ?: 'Local';
    $nearbyTags = [
        "{$cleanLocTag} Market",
        "Transit Hub",
        "Prime Park"
    ];
@endphp

<article class="property-horizontal-card group relative bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/90 dark:border-slate-800 shadow-xs hover:shadow-md hover:border-blue-400/60 dark:hover:border-blue-500/50 transition-all duration-200 overflow-hidden mb-4 flex flex-col md:flex-row items-stretch"
    id="property-hcard-{{ $property->id }}"
    data-property-card="true"
    data-property-id="{{ $property->id }}">

    {{-- ============================================================
         LEFT COLUMN: Large Property Image & Interactive Badges
         ============================================================ --}}
    <div class="hcard-image-col relative w-full md:w-[260px] lg:w-[275px] min-h-[180px] md:min-h-full bg-slate-100 dark:bg-slate-800 shrink-0 overflow-hidden">
        
        @php
            $hasMultipleImages = $cardImages->count() > 1;
        @endphp

        @if($hasMultipleImages)
            {{-- Multi-Image Auto-Slider (Slides Right to Left smoothly) --}}
            <div class="card-image-slider hcard-image-slider relative w-full h-full overflow-hidden select-none"
                 data-card-slider="hcard-{{ $property->id }}"
                 data-total-slides="{{ $cardImages->count() }}">
                
                {{-- Slider Track (Translates horizontally for right-to-left slide) --}}
                <div class="card-slider-track flex h-full w-full"
                     style="transform: translate3d(0, 0, 0);">
                    @foreach($cardImages as $idx => $img)
                        <div class="card-slide flex-shrink-0 w-full h-full relative">
                            <a href="{{ $propertyUrl }}" class="block w-full h-full relative" title="{{ $cleanTitle }} - Photo {{ $idx + 1 }}">
                                <img src="{{ $img->imageUrl() }}"
                                     data-orig-src="{{ $img->imageUrl() }}"
                                     data-fallback-url="{{ $cardFallbackPhoto }}"
                                     alt="{{ $cleanTitle }} - Photo {{ $idx + 1 }}"
                                     loading="{{ $idx === 0 ? 'lazy' : 'lazy' }}"
                                     referrerpolicy="no-referrer"
                                     class="hcard-img w-full h-full object-cover group-hover/img:scale-105 transition-transform duration-400 ease-out select-none pointer-events-none"
                                     onerror="if(!this.dataset.fallbackApplied){ this.dataset.fallbackApplied='1'; this.src='{{ $cardFallbackPhoto }}'; } else { this.onerror=null; }">
                            </a>
                        </div>
                    @endforeach
                </div>

                {{-- Subtle Vignette Gradients --}}
                <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-black/25 pointer-events-none z-10"></div>

                {{-- Micro Pagination Dots (Centered at bottom, z-20) --}}
                <div class="card-slider-dots absolute bottom-2 left-1/2 -translate-x-1/2 z-20 flex items-center gap-1 px-1.5 py-0.5 rounded-full bg-black/50 backdrop-blur-xs pointer-events-auto"
                     onclick="event.stopPropagation();">
                    @foreach($cardImages as $idx => $img)
                        <button type="button"
                                onclick="event.preventDefault(); event.stopPropagation(); window.cardSliderGoTo('hcard-{{ $property->id }}', {{ $idx }});"
                                class="card-slider-dot h-1.5 rounded-full transition-all duration-300 {{ $idx === 0 ? 'w-3 bg-white shadow-xs' : 'w-1.5 bg-white/50 hover:bg-white/80' }}"
                                data-dot-index="{{ $idx }}"
                                aria-label="Go to photo {{ $idx + 1 }}">
                        </button>
                    @endforeach
                </div>

                {{-- Bottom-Right Photo Counter Badge (e.g. 📷 1/5) --}}
                <div class="absolute bottom-2.5 right-2.5 z-20 pointer-events-none">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-black/65 backdrop-blur-md text-white text-[10px] font-extrabold shadow-sm border border-white/10">
                        <i class="ph-bold ph-camera text-[11px] text-white"></i>
                        <span class="card-slide-counter">1/{{ $cardImages->count() }}</span>
                    </span>
                </div>
            </div>
        @else
            {{-- Clickable Image Link --}}
            <a href="{{ $propertyUrl }}" class="hcard-image-link block w-full h-full relative group/img overflow-hidden" title="{{ $cleanTitle }}">
                <img src="{{ $primaryUrl }}"
                     data-orig-src="{{ $primaryUrl }}"
                     data-fallback-url="{{ $cardFallbackPhoto }}"
                     alt="{{ $cleanTitle }}"
                     loading="lazy"
                     referrerpolicy="no-referrer"
                     class="hcard-img w-full h-full object-cover group-hover/img:scale-105 transition-transform duration-400 ease-out select-none"
                     onerror="if(!this.dataset.fallbackApplied){ this.dataset.fallbackApplied='1'; this.src='{{ $cardFallbackPhoto }}'; } else { this.onerror=null; }">

                {{-- Subtle Vignette Gradients --}}
                <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-black/25 pointer-events-none"></div>
            </a>

            {{-- Bottom-Right Photo Counter Badge (e.g. 📷 6) --}}
            <div class="absolute bottom-2.5 right-2.5 z-20 pointer-events-none">
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-black/65 backdrop-blur-md text-white text-[10px] font-extrabold shadow-sm border border-white/10">
                    <i class="ph-bold ph-camera text-[11px] text-white"></i>
                    <span>{{ $imagesCount }}</span>
                </span>
            </div>
        @endif

        {{-- Diagonal BOOKED Corner Ribbon --}}
        @if($property->is_booked)
            <div class="absolute top-0 right-0 z-30 pointer-events-none overflow-hidden w-24 h-24">
                <div class="absolute transform rotate-45 bg-rose-600 text-white font-black text-[8.5px] uppercase tracking-wider py-0.5 right-[-32px] top-[16px] w-[110px] text-center shadow-md">
                    BOOKED
                </div>
            </div>
        @endif
    </div>

    {{-- ============================================================
         RIGHT COLUMN: Content, Key Metrics, Landmarks & Actions
         ============================================================ --}}
    <div class="hcard-content-col flex-1 p-3.5 sm:p-4 flex flex-col justify-between min-w-0">

        <div>
            {{-- Header: Locality & Property Type Title --}}
            <div class="mb-2">
                <div class="flex items-start justify-between gap-2 flex-wrap">
                    <a href="{{ $propertyUrl }}" class="group-hover:text-blue-600 transition-colors">
                        <h2 class="text-base sm:text-[17px] font-bold text-slate-900 dark:text-white tracking-tight leading-snug">
                            {{ $isPg ? $pgHeadingName : $headingLocation }}
                        </h2>
                    </a>
                    <div class="flex items-center gap-1.5 shrink-0">
                        @if($isPg || in_array($property->furnishing ?? '', ['furnished', 'fully-furnished']))
                            <span class="px-2 py-0.5 rounded text-[9.5px] font-semibold uppercase tracking-wider bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 shadow-2xs">
                                FURNISHED
                            </span>
                        @elseif(($property->furnishing ?? '') === 'semi-furnished')
                            <span class="px-2 py-0.5 rounded text-[9.5px] font-semibold uppercase tracking-wider bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 shadow-2xs">
                                SEMI-FURNISHED
                            </span>
                        @endif
                        @if($property->is_featured)
                            <span class="px-2 py-0.5 rounded-full text-[9.5px] font-semibold uppercase tracking-wider bg-amber-500/15 text-amber-600 border border-amber-500/30">
                                ★ Verified
                            </span>
                        @endif
                    </div>
                </div>
                <a href="{{ $propertyUrl }}" class="block text-xs sm:text-[13px] font-normal text-slate-500 dark:text-slate-400 hover:text-blue-600 transition-colors truncate mt-0.5">
                    {{ $isPg ? $pgSubheading : $subheading }}
                </a>
            </div>

            {{-- 3-Column Key Highlights Row (Price, Area, Configuration) --}}
            <div class="hcard-specs-row grid grid-cols-3 gap-2 sm:gap-3 py-2 border-y border-slate-100 dark:border-slate-800/80 mb-2">
                {{-- Column 1: Price --}}
                <div class="hcard-spec-col min-w-0 pr-1">
                    <div class="flex items-baseline gap-1 flex-nowrap truncate">
                        <span class="text-base sm:text-lg font-bold text-slate-900 dark:text-white tracking-tight">
                            {{ $formattedPrice }}
                        </span>
                        @if($isPg)
                            <span class="text-[11.5px] font-medium text-slate-500 shrink-0">/Bed</span>
                        @elseif($isRent)
                            <span class="text-[11.5px] font-medium text-slate-500 shrink-0">/month</span>
                        @endif
                    </div>
                    <span class="block text-[11px] font-normal text-slate-500 dark:text-slate-400 truncate">
                        @if($isPg && (empty($depositVal) || $depositVal == $priceNum))
                            + Deposit 1 months rent
                        @else
                            + Deposit {{ $depositText }}
                        @endif
                    </span>
                </div>

                {{-- Column 2: Area / Sharing --}}
                <div class="hcard-spec-col min-w-0 px-2 border-l border-slate-100 dark:border-slate-800/80">
                    @if($isPg)
                        <div class="flex items-center gap-1">
                            <span class="text-base sm:text-lg font-bold text-slate-900 dark:text-white tracking-tight truncate">
                                {{ $sharingType }}
                            </span>
                        </div>
                        <span class="block text-[11px] font-normal text-slate-500 dark:text-slate-400 truncate">
                            {{ $sharingSubtitle }}
                        </span>
                    @else
                        <div class="flex items-center gap-1">
                            <span class="text-base sm:text-lg font-bold text-slate-900 dark:text-white tracking-tight whitespace-nowrap">
                                {{ number_format($sqft) }} sqft
                            </span>
                        </div>
                        <span class="block text-[11px] font-normal text-slate-500 dark:text-slate-400 truncate" title="{{ $property->type === 'plot' ? 'Plot Area' : 'Carpet Area' }} ({{ $sqm }} sqm)">
                            {{ $property->type === 'plot' ? 'Plot Area' : 'Carpet Area' }} ({{ $sqm }} sqm)
                        </span>
                    @endif
                </div>

                {{-- Column 3: Configuration & Baths --}}
                <div class="hcard-spec-col min-w-0 pl-2 border-l border-slate-100 dark:border-slate-800/80">
                    @if($isPg)
                        <span class="text-base sm:text-lg font-bold text-slate-900 dark:text-white tracking-tight block truncate">
                            Attached Bath
                        </span>
                        <span class="block text-[11px] font-normal text-slate-500 dark:text-slate-400 truncate">
                            Furnished Room
                        </span>
                    @else
                        <span class="text-base sm:text-lg font-bold text-slate-900 dark:text-white tracking-tight block whitespace-nowrap">
                            {{ $beds > 0 ? "{$beds} BHK" : '1 RK' }}
                        </span>
                        <span class="block text-[11px] font-normal text-slate-500 dark:text-slate-400 truncate">
                            {{ $baths }} {{ $baths === 1 ? 'Bath' : 'Baths' }}
                        </span>
                    @endif
                </div>
            </div>

            {{-- Nearby Landmarks & Transit Pills --}}
            <div class="flex items-center gap-1.5 flex-wrap mb-2 text-xs">
                <span class="text-[11px] font-semibold text-slate-500 dark:text-slate-400">Nearby :</span>
                @foreach($nearbyTags as $idx => $tag)
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10.5px] font-medium bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700">
                        {{ $tag }}
                    </span>
                @endforeach
                <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-slate-50 dark:bg-slate-800 text-slate-500 border border-slate-200/50">
                    +3
                </span>
            </div>

            {{-- Short Description Teaser --}}
            <p class="text-[12px] text-slate-500 dark:text-slate-400 line-clamp-1 mb-2 leading-relaxed">
                {{ $descTeaser }}
                <a href="{{ $propertyUrl }}" class="text-blue-600 hover:underline ml-1 inline-flex items-center font-medium" title="Read more">
                    more &rarr;
                </a>
            </p>
        </div>

        {{-- ============================================================
             BOTTOM FOOTER BAR: Dealer Profile + "View Number" & "Contact"
             ============================================================ --}}
        <div class="hcard-footer-row pt-2.5 mt-auto border-t border-slate-100 dark:border-slate-800/80 flex items-center justify-between gap-3">
            
            {{-- Left: Dealer / Owner Info (Clean, No Admin User) --}}
            <div class="hcard-dealer-info flex items-center gap-2 min-w-0">
                <div class="min-w-0">
                    <span class="text-[10.5px] text-slate-400 font-normal block leading-none">
                        {{ $postedTime }}
                    </span>
                    <div class="flex items-center gap-1.5 mt-1">
                        <span class="text-xs font-semibold text-slate-800 dark:text-slate-200 truncate max-w-[150px] sm:max-w-[200px]" title="{{ $ownerName }}">
                            {{ $ownerName }}
                        </span>
                        @if($isDealer)
                            <span class="px-1.5 py-0.5 rounded text-[8.5px] font-bold uppercase tracking-wider bg-orange-50 text-orange-700 border border-orange-200/80">
                                DEALER
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Right: Action Buttons (View Number & Contact) --}}
            <div class="hcard-actions flex items-center gap-2 shrink-0">
                
                {{-- Button 1: "View Number" --}}
                <button type="button"
                        onclick="revealCardPhoneNumber({{ $property->id }}, '{{ $ownerPhone }}', this)"
                        class="view-number-btn h-9 px-3.5 sm:px-4 rounded-xl border border-blue-600 text-blue-600 hover:bg-blue-50/80 dark:hover:bg-blue-950/40 text-[12px] font-semibold transition-all active:scale-95 cursor-pointer flex items-center gap-1.5 shadow-2xs whitespace-nowrap"
                        data-phone="{{ $ownerPhone }}">
                    <i class="ph-bold ph-phone text-xs"></i>
                    <span class="btn-text">View Number</span>
                </button>

                {{-- Button 2: "📞 Contact" --}}
                <a href="https://wa.me/{{ $cleanPhone }}?text={{ urlencode('Hello, I am interested in your property: ' . $cleanTitle . ' on UnlockRentals (' . $propertyUrl . ')') }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="h-9 px-3.5 sm:px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-[12px] font-semibold transition-all active:scale-95 shadow-xs flex items-center gap-1.5 cursor-pointer whitespace-nowrap"
                   style="color: #ffffff !important;">
                    <i class="ph-bold ph-chat-circle-dots text-xs" style="color: #ffffff !important;"></i>
                    <span style="color: #ffffff !important;">Contact</span>
                </a>
            </div>

        </div>

    </div>

</article>

@once
<style>
/* =========================================================================
   UNLOCK RENTALS — 99ACRES HORIZONTAL CARD BULLETPROOF STYLES
   Standard responsive side-by-side card with Poppins typography.
   ========================================================================= */
.property-horizontal-card,
.property-horizontal-card * {
    font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
    box-sizing: border-box !important;
}

.property-horizontal-card {
    display: flex !important;
    flex-direction: column !important;
    background: #ffffff !important;
    border-radius: 1rem !important;
    border: 1px solid rgba(226, 232, 240, 0.9) !important;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05) !important;
    overflow: hidden !important;
    margin-bottom: 1.25rem !important;
    position: relative !important;
    width: 100% !important;
    height: auto !important;
    min-height: auto !important;
    transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease !important;
}

html.dark .property-horizontal-card,
.dark .property-horizontal-card {
    background: #0f172a !important;
    border-color: #1e293b !important;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.3) !important;
}

.property-horizontal-card:hover {
    border-color: #3b82f6 !important;
    box-shadow: 0 8px 20px -4px rgba(0, 0, 0, 0.07) !important;
}

/* Mobile defaults (< 768px) */
.property-horizontal-card .hcard-image-col {
    position: relative !important;
    width: 100% !important;
    height: 200px !important;
    min-height: 200px !important;
    max-height: 200px !important;
    background: #f1f5f9 !important;
    overflow: hidden !important;
    flex-shrink: 0 !important;
}

html.dark .property-horizontal-card .hcard-image-col,
.dark .property-horizontal-card .hcard-image-col {
    background: #1e293b !important;
}

.property-horizontal-card .hcard-image-link {
    display: block !important;
    width: 100% !important;
    height: 100% !important;
    position: relative !important;
    overflow: hidden !important;
}

.property-horizontal-card .hcard-img {
    width: 100% !important;
    height: 100% !important;
    object-fit: cover !important;
    display: block !important;
    transition: transform 0.4s ease !important;
}

.property-horizontal-card:hover .hcard-img {
    transform: scale(1.03) !important;
}

.property-horizontal-card .hcard-content-col {
    flex: 1 1 0% !important;
    min-width: 0 !important;
    display: flex !important;
    flex-direction: column !important;
    justify-content: space-between !important;
    padding: 1rem !important;
    box-sizing: border-box !important;
}

/* Tablet & Desktop (>= 768px): Standard 99acres Compact Side-by-Side Size */
@media (min-width: 768px) {
    .property-horizontal-card {
        flex-direction: row !important;
        align-items: stretch !important;
        min-height: 235px !important;
        height: auto !important;
        max-height: none !important;
    }

    .property-horizontal-card .hcard-image-col {
        width: 275px !important;
        min-width: 275px !important;
        max-width: 275px !important;
        height: auto !important;
        min-height: 100% !important;
        max-height: none !important;
        align-self: stretch !important;
        flex: 0 0 275px !important;
    }

    .property-horizontal-card .hcard-image-link {
        position: absolute !important;
        inset: 0 !important;
        width: 100% !important;
        height: 100% !important;
    }

    .property-horizontal-card .hcard-content-col {
        padding: 1rem 1.25rem !important;
        min-height: 100% !important;
    }
}

@media (min-width: 1024px) {
    .property-horizontal-card {
        min-height: 240px !important;
        height: auto !important;
        max-height: none !important;
    }

    .property-horizontal-card .hcard-image-col {
        width: 290px !important;
        min-width: 290px !important;
        max-width: 290px !important;
        flex: 0 0 290px !important;
    }
}

@media (min-width: 1280px) {
    .property-horizontal-card .hcard-image-col {
        width: 300px !important;
        min-width: 300px !important;
        max-width: 300px !important;
        flex: 0 0 300px !important;
    }
}

/* 3-Column Key Highlights Row */
.property-horizontal-card .hcard-specs-row {
    display: grid !important;
    grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
    gap: 0.5rem !important;
    padding: 0.45rem 0 !important;
    border-top: 1px solid #f1f5f9 !important;
    border-bottom: 1px solid #f1f5f9 !important;
    margin-bottom: 0.45rem !important;
    margin-top: 0.25rem !important;
}

html.dark .property-horizontal-card .hcard-specs-row,
.dark .property-horizontal-card .hcard-specs-row {
    border-color: #1e293b !important;
}

.property-horizontal-card .hcard-spec-col {
    min-width: 0 !important;
}

.property-horizontal-card .hcard-spec-col + .hcard-spec-col {
    border-left: 1px solid #f1f5f9 !important;
    padding-left: 0.65rem !important;
}

html.dark .property-horizontal-card .hcard-spec-col + .hcard-spec-col,
.dark .property-horizontal-card .hcard-spec-col + .hcard-spec-col {
    border-color: #1e293b !important;
}

/* Footer Row */
.property-horizontal-card .hcard-footer-row {
    padding-top: 0.6rem !important;
    margin-top: auto !important;
    border-top: 1px solid #f1f5f9 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    gap: 0.75rem !important;
    flex-wrap: wrap !important;
}

html.dark .property-horizontal-card .hcard-footer-row,
.dark .property-horizontal-card .hcard-footer-row {
    border-color: #1e293b !important;
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
    if (typeof window.revealCardPhoneNumber === 'undefined') {
        window.revealCardPhoneNumber = function(propId, phone, btn) {
            if (!btn) return;
            const cleanNumber = phone.replace(/[^\d+]/g, '');
            btn.innerHTML = `<i class="ph-bold ph-phone-call text-emerald-600 text-xs"></i> <a href="tel:${cleanNumber}" class="text-emerald-700 dark:text-emerald-400 font-semibold">${phone}</a>`;
            btn.className = 'view-number-btn h-9 px-3.5 sm:px-4 rounded-xl border border-emerald-500 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-800 dark:text-emerald-300 text-[12px] font-semibold flex items-center gap-1.5 shadow-2xs transition-all';
            btn.onclick = null;
        };
    }

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
            const cardIdNum = parseInt(state.id.replace(/\D/g, ''), 10) || 0;
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
