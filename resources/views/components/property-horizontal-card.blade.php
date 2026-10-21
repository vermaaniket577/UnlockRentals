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

    // Owner / Dealer Information
    $owner = $property->owner ?? $property->user;
    $ownerName = $owner?->name ?? 'Verified Partner';
    $ownerAvatar = $owner?->avatar;
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

    // Description teaser
    $desc = strip_tags($property->description ?? "Carefully laid out in the prime location of {$locality}, this {$beds} BHK {$propTypeLabel} features modern fixtures, natural lighting, and superior connectivity.");
    $desc = \App\Models\Property::cleanUrlFromText($desc, false);
    $descTeaser = \Illuminate\Support\Str::limit($desc, 110);

    // Nearby Transit tags
    $cleanLocTag = \App\Models\Property::cleanUrlFromText($locality, false);
    $nearbyTags = [
        "{$cleanLocTag} Metro Station",
        "Market Center",
        "Prime Park"
    ];
@endphp

<article class="property-horizontal-card group relative bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/90 dark:border-slate-800 shadow-xs hover:shadow-lg hover:border-blue-400/60 dark:hover:border-blue-500/50 transition-all duration-300 overflow-hidden mb-5 flex flex-col md:flex-row items-stretch"
    id="property-hcard-{{ $property->id }}"
    data-property-card="true"
    data-property-id="{{ $property->id }}">

    {{-- ============================================================
         LEFT COLUMN: Large Property Image & Interactive Badges
         ============================================================ --}}
    <div class="hcard-image-col relative w-full md:w-[340px] lg:w-[360px] xl:w-[380px] min-h-[220px] md:min-h-full bg-slate-100 dark:bg-slate-800 shrink-0 overflow-hidden">
        
        {{-- Clickable Image Link --}}
        <a href="{{ $propertyUrl }}" class="hcard-image-link block w-full h-full relative group/img overflow-hidden" title="{{ $cleanTitle }}">
            <img src="{{ $primaryUrl }}"
                 data-orig-src="{{ $primaryUrl }}"
                 data-fallback-url="{{ $cardFallbackPhoto }}"
                 alt="{{ $cleanTitle }}"
                 loading="lazy"
                 referrerpolicy="no-referrer"
                 class="hcard-img w-full h-full object-cover group-hover/img:scale-105 transition-transform duration-500 ease-out select-none"
                 onerror="if(!this.dataset.fallbackApplied){ this.dataset.fallbackApplied='1'; this.src='{{ $cardFallbackPhoto }}'; } else { this.onerror=null; }">

            {{-- Subtle Vignette Gradients --}}
            <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-black/30 pointer-events-none"></div>
        </a>

        {{-- Top-Left Badge: FEATURED --}}
        <div class="absolute top-3.5 left-3.5 z-20 pointer-events-none">
            <span class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider text-white shadow-md"
                  style="background: #1e293b; letter-spacing: 0.05em;">
                FEATURED
            </span>
        </div>

        {{-- Top-Right Action Buttons: Favorite Heart & Audio/Video --}}
        <div class="absolute top-3.5 right-3.5 z-20 flex items-center gap-1.5 pointer-events-auto">
            @auth
                <button type="button"
                        onclick="event.preventDefault(); event.stopPropagation(); window.togglePropertyFavorite ? window.togglePropertyFavorite({{ $property->id }}, this) : null;"
                        class="w-8 h-8 rounded-full bg-black/45 hover:bg-black/70 backdrop-blur-md text-white flex items-center justify-center transition-all cursor-pointer shadow-sm hover:scale-110 active:scale-95"
                        title="Shortlist / Save Property"
                        aria-label="Save Property">
                    <i class="ph-bold ph-heart text-sm"></i>
                </button>
            @else
                <button type="button"
                        onclick="event.preventDefault(); event.stopPropagation(); window.openAuthModal ? window.openAuthModal('login', '{{ $propertyUrl }}') : null;"
                        class="w-8 h-8 rounded-full bg-black/45 hover:bg-black/70 backdrop-blur-md text-white flex items-center justify-center transition-all cursor-pointer shadow-sm hover:scale-110 active:scale-95"
                        title="Save Property"
                        aria-label="Save Property">
                    <i class="ph-bold ph-heart text-sm"></i>
                </button>
            @endauth

            @if($property->hasVideo())
                <div class="w-8 h-8 rounded-full bg-black/45 backdrop-blur-md text-white flex items-center justify-center shadow-sm" title="Video Tour Available">
                    <i class="ph-fill ph-video-camera text-sm text-white"></i>
                </div>
            @else
                <div class="w-8 h-8 rounded-full bg-black/45 backdrop-blur-md text-white/90 flex items-center justify-center shadow-sm" title="Verified Photos">
                    <i class="ph-bold ph-speaker-slash text-xs"></i>
                </div>
            @endif
        </div>

        {{-- Bottom-Right Photo Counter Badge (e.g. 📷 6) --}}
        <div class="absolute bottom-3 right-3 z-20 pointer-events-none">
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-black/65 backdrop-blur-md text-white text-[11px] font-extrabold shadow-md border border-white/10">
                <i class="ph-bold ph-camera text-xs text-white"></i>
                <span>{{ $imagesCount }}</span>
            </span>
        </div>

        {{-- Diagonal BOOKED Corner Ribbon --}}
        @if($property->is_booked)
            <div class="absolute top-0 right-0 z-30 pointer-events-none overflow-hidden w-28 h-28">
                <div class="absolute transform rotate-45 bg-rose-600 text-white font-black text-[9px] uppercase tracking-wider py-1 right-[-35px] top-[18px] w-[120px] text-center shadow-md">
                    BOOKED
                </div>
            </div>
        @endif
    </div>

    {{-- ============================================================
         RIGHT COLUMN: Content, Key Metrics, Landmarks & Actions
         ============================================================ --}}
    <div class="hcard-content-col flex-1 p-5 sm:p-6 flex flex-col justify-between min-w-0">

        <div>
            {{-- Header: Locality & Property Type Title --}}
            <div class="mb-3.5">
                <div class="flex items-center justify-between gap-2 flex-wrap">
                    <a href="{{ $propertyUrl }}" class="group-hover:text-blue-600 transition-colors">
                        <h2 class="text-base sm:text-lg font-black text-slate-900 dark:text-white tracking-tight leading-snug">
                            {{ $headingLocation }}
                        </h2>
                    </a>
                    @if($property->is_featured)
                        <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-amber-500/15 text-amber-600 border border-amber-500/30">
                            ★ Verified
                        </span>
                    @endif
                </div>
                <a href="{{ $propertyUrl }}" class="block mt-0.5 text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-300 hover:text-blue-600 transition-colors">
                    {{ $subheading }}
                </a>
            </div>

            {{-- 3-Column Key Highlights Row (Price, Area, Configuration) --}}
            <div class="hcard-specs-row grid grid-cols-3 gap-2 sm:gap-4 py-3 sm:py-3.5 border-y border-slate-100 dark:border-slate-800/80 mb-3.5">
                {{-- Column 1: Price --}}
                <div class="hcard-spec-col min-w-0 pr-1">
                    <div class="flex items-baseline gap-1 flex-wrap">
                        <span class="text-base sm:text-xl font-black text-slate-900 dark:text-white tracking-tight">
                            {{ $formattedPrice }}
                        </span>
                        @if($isRent)
                            <span class="text-[11px] sm:text-xs font-semibold text-slate-500">/month</span>
                        @endif
                    </div>
                    <span class="block text-[10.5px] sm:text-[11px] font-medium text-slate-500 dark:text-slate-400 mt-0.5 truncate">
                        + Deposit {{ $depositText }}
                    </span>
                </div>

                {{-- Column 2: Area in sqft & sqm --}}
                <div class="hcard-spec-col min-w-0 px-1 border-l border-slate-100 dark:border-slate-800/80">
                    <div class="flex items-center gap-1">
                        <span class="text-base sm:text-lg font-black text-slate-900 dark:text-white tracking-tight">
                            {{ number_format($sqft) }} sqft
                        </span>
                        <span class="text-[10px] sm:text-[11px] text-slate-400 font-semibold hidden sm:inline">({{ $sqm }} sqm) ▾</span>
                    </div>
                    <span class="block text-[10.5px] sm:text-[11px] font-medium text-slate-500 dark:text-slate-400 mt-0.5 truncate">
                        {{ $property->type === 'plot' ? 'Plot Area' : 'Carpet Area' }}
                    </span>
                </div>

                {{-- Column 3: Configuration & Baths --}}
                <div class="hcard-spec-col min-w-0 pl-1 border-l border-slate-100 dark:border-slate-800/80">
                    <span class="text-base sm:text-lg font-black text-slate-900 dark:text-white tracking-tight block">
                        {{ $beds > 0 ? "{$beds} BHK" : '1 RK' }}
                    </span>
                    <span class="block text-[10.5px] sm:text-[11px] font-medium text-slate-500 dark:text-slate-400 mt-0.5 truncate">
                        {{ $baths }} Baths
                    </span>
                </div>
            </div>

            {{-- Nearby Landmarks & Transit Pills --}}
            <div class="flex items-center gap-1.5 flex-wrap mb-3 text-xs">
                <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400">Nearby :</span>
                @foreach($nearbyTags as $idx => $tag)
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10.5px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700">
                        {{ $tag }}
                    </span>
                @endforeach
                <span class="px-1.5 py-0.5 rounded-md text-[10.5px] font-bold bg-slate-50 dark:bg-slate-800 text-slate-500 border border-slate-200/50">
                    +3
                </span>
            </div>

            {{-- Short Description Teaser --}}
            <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-1 mb-4 leading-relaxed">
                {{ $descTeaser }}
                <a href="{{ $propertyUrl }}" class="text-slate-400 hover:text-blue-600 ml-1 inline-flex items-center" title="Read more">
                    <i class="ph-bold ph-caret-down text-xs"></i>
                </a>
            </p>
        </div>

        {{-- ============================================================
             BOTTOM FOOTER BAR: Dealer Profile + "View Number" & "Contact"
             ============================================================ --}}
        <div class="hcard-footer-row pt-3 border-t border-slate-100 dark:border-slate-800/80 flex items-center justify-between gap-3 flex-wrap">
            
            {{-- Left: Dealer / Owner Info --}}
            <div class="hcard-dealer-info flex items-center gap-2.5 min-w-0">
                <div class="relative shrink-0">
                    @if($ownerAvatar)
                        <img src="{{ $ownerAvatar }}" alt="{{ $ownerName }}" class="w-9 h-9 rounded-full object-cover border border-slate-200 shadow-2xs">
                    @else
                        <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-amber-500 to-orange-600 text-white font-black text-xs flex items-center justify-center shadow-xs">
                            {{ strtoupper(substr($ownerName, 0, 1)) }}
                        </div>
                    @endif
                    <span class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 rounded-full bg-amber-500 text-white flex items-center justify-center text-[8px] ring-2 ring-white shadow-2xs" title="Featured Dealer">
                        <i class="ph-bold ph-check"></i>
                    </span>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-1.5 flex-wrap leading-none">
                        <span class="text-[10px] text-slate-400 font-semibold">{{ $postedTime }}</span>
                        <span class="px-1.5 py-0.2 rounded text-[8.5px] font-black uppercase tracking-wider bg-orange-100 text-orange-800 border border-orange-200/80">
                            FEATURED DEALER
                        </span>
                    </div>
                    <p class="text-xs font-bold text-slate-900 dark:text-white truncate mt-1 max-w-[140px] sm:max-w-[180px]" title="{{ $ownerName }}">
                        {{ $ownerName }}
                    </p>
                </div>
            </div>

            {{-- Right: Action Buttons (View Number & Contact) --}}
            <div class="hcard-actions flex items-center gap-2 shrink-0 w-full sm:w-auto justify-end">
                
                {{-- Button 1: "View Number" --}}
                <button type="button"
                        onclick="revealCardPhoneNumber({{ $property->id }}, '{{ $ownerPhone }}', this)"
                        class="view-number-btn px-4 py-2 sm:py-2.5 rounded-xl border border-[#0066cc] text-[#0066cc] hover:bg-blue-50/80 dark:hover:bg-blue-950/40 text-xs sm:text-sm font-bold transition-all active:scale-95 cursor-pointer flex items-center gap-1.5 shadow-2xs"
                        data-phone="{{ $ownerPhone }}">
                    <i class="ph-bold ph-phone text-xs sm:text-sm"></i>
                    <span class="btn-text">View Number</span>
                </button>

                {{-- Button 2: "📞 Contact" --}}
                <a href="https://wa.me/{{ $cleanPhone }}?text={{ urlencode('Hello, I am interested in your property: ' . $cleanTitle . ' on UnlockRentals (' . $propertyUrl . ')') }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="px-5 py-2 sm:py-2.5 rounded-xl bg-[#0066cc] hover:bg-[#0052a3] text-white text-xs sm:text-sm font-bold transition-all active:scale-95 shadow-xs flex items-center gap-1.5 cursor-pointer"
                   style="color: #ffffff !important;">
                    <i class="ph-bold ph-chat-circle-dots text-sm" style="color: #ffffff !important;"></i>
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
   Ensures side-by-side (left photo, right details) on tablet and desktop.
   ========================================================================= */
.property-horizontal-card {
    display: flex !important;
    flex-direction: column !important;
    background: #ffffff !important;
    border-radius: 1.25rem !important;
    border: 1px solid rgba(226, 232, 240, 0.9) !important;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05) !important;
    overflow: hidden !important;
    margin-bottom: 1.25rem !important;
    position: relative !important;
    width: 100% !important;
    box-sizing: border-box !important;
    transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease !important;
}

html.dark .property-horizontal-card,
.dark .property-horizontal-card {
    background: #0f172a !important;
    border-color: #1e293b !important;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.3) !important;
}

.property-horizontal-card:hover {
    border-color: #3b82f6 !important;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.06) !important;
}

/* Mobile defaults (< 768px) */
.property-horizontal-card .hcard-image-col {
    position: relative !important;
    width: 100% !important;
    height: 220px !important;
    min-height: 220px !important;
    max-height: 220px !important;
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
    transition: transform 0.5s cubic-bezier(0, 0, 0.2, 1) !important;
}

.property-horizontal-card:hover .hcard-img {
    transform: scale(1.04) !important;
}

.property-horizontal-card .hcard-content-col {
    flex: 1 1 0% !important;
    min-width: 0 !important;
    display: flex !important;
    flex-direction: column !important;
    justify-content: space-between !important;
    padding: 1.25rem !important;
    box-sizing: border-box !important;
}

/* Tablet & Desktop (>= 768px): Strictly Side-by-Side Horizontal Layout */
@media (min-width: 768px) {
    .property-horizontal-card {
        flex-direction: row !important;
        align-items: stretch !important;
        min-height: 245px !important;
        height: auto !important;
    }

    .property-horizontal-card .hcard-image-col {
        width: 320px !important;
        min-width: 320px !important;
        max-width: 320px !important;
        height: auto !important;
        min-height: 100% !important;
        max-height: none !important;
        align-self: stretch !important;
        flex: 0 0 320px !important;
    }

    .property-horizontal-card .hcard-image-link {
        position: absolute !important;
        inset: 0 !important;
        width: 100% !important;
        height: 100% !important;
    }

    .property-horizontal-card .hcard-content-col {
        padding: 1.25rem 1.5rem !important;
    }
}

@media (min-width: 1024px) {
    .property-horizontal-card .hcard-image-col {
        width: 340px !important;
        min-width: 340px !important;
        max-width: 340px !important;
        flex: 0 0 340px !important;
    }
}

@media (min-width: 1280px) {
    .property-horizontal-card .hcard-image-col {
        width: 360px !important;
        min-width: 360px !important;
        max-width: 360px !important;
        flex: 0 0 360px !important;
    }
}

/* 3-Column Key Highlights Row */
.property-horizontal-card .hcard-specs-row {
    display: grid !important;
    grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
    gap: 0.75rem !important;
    padding: 0.75rem 0 !important;
    border-top: 1px solid #f1f5f9 !important;
    border-bottom: 1px solid #f1f5f9 !important;
    margin-bottom: 0.75rem !important;
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
    padding-left: 0.75rem !important;
}

html.dark .property-horizontal-card .hcard-spec-col + .hcard-spec-col,
.dark .property-horizontal-card .hcard-spec-col + .hcard-spec-col {
    border-color: #1e293b !important;
}

/* Footer Row */
.property-horizontal-card .hcard-footer-row {
    padding-top: 0.75rem !important;
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
</style>

<script>
    if (typeof window.revealCardPhoneNumber === 'undefined') {
        window.revealCardPhoneNumber = function(propId, phone, btn) {
            if (!btn) return;
            const cleanNumber = phone.replace(/[^\d+]/g, '');
            btn.innerHTML = `<i class="ph-bold ph-phone-call text-emerald-600 text-sm"></i> <a href="tel:${cleanNumber}" class="text-emerald-700 dark:text-emerald-400 font-black">${phone}</a>`;
            btn.className = 'px-3.5 py-2 sm:py-2.5 rounded-xl border border-emerald-500 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-800 dark:text-emerald-300 text-xs sm:text-sm font-bold flex items-center gap-1.5 shadow-2xs transition-all';
            btn.onclick = null;
        };
    }
</script>
@endonce
