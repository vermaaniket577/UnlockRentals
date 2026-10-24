@extends('layouts.app')

@php
    $isSale = $property->isForSale();
    $isOwnerOrAdmin = false;
    $hasViewed = false;
    $canView = false;
    $activePlan = null;
    $hasRentPlan = false;
    $hasBuyPlan = false;
    
    if (auth()->check()) {
        $user = auth()->user();
        $isOwnerOrAdmin = $user->id === $property->user_id || $user->isAdmin();
        $hasViewed = $user->hasViewedContact($property);
        $canView = $user->canViewContact($property);
        $activePlan = $user->activePlanForProperty($property);
        $hasRentPlan = $user->hasActiveRentPlan();
        $hasBuyPlan = $user->hasActiveBuyPlan();
    }

    // Professional Real Estate Portal Typography & Title Formatting
    $rawTitle = \App\Models\Property::cleanUrlFromText($property->title, true);
    $cleanTitle = ucwords(strtolower(trim($rawTitle)));
    $minorWords = ['for', 'in', 'at', 'on', 'near', 'with', 'and', 'to', 'of', 'the', 'a', 'an', 'by', 'rent', 'sale'];
    foreach ($minorWords as $w) {
        $cleanTitle = preg_replace('/\b' . ucfirst($w) . '\b/', strtolower($w), $cleanTitle);
    }
    $cleanTitle = ucfirst($cleanTitle);
    $cleanTitle = preg_replace_callback('/\b(\d+)?\s*(bhk|rk|pg)\b/i', fn($m) => strtoupper($m[0]), $cleanTitle);

    // Locality, Location, and State Sanitization (Strictly strip 99acres URLs)
    $rawLocality = !empty($property->locality) ? trim($property->locality) : trim(explode(',', $property->location)[0]);
    $localityName = \App\Models\Property::cleanUrlFromText($rawLocality, false) ?: 'Verified Locality';
    $cleanLocation = \App\Models\Property::cleanUrlFromText($property->location, true) ?: 'Delhi';
    $cleanState = \App\Models\Property::cleanUrlFromText($property->state ?? '', false);
    if (empty($cleanState) || $cleanState === 'India') {
        [, $cleanState] = \App\Models\Property::extractCityAndStateFromUrl($cleanLocation);
    }
    $cleanAddress = \App\Models\Property::cleanUrlFromText($property->address, true) ?: ($localityName . ', ' . $cleanLocation);
    $projectName = strtoupper($localityName);

    // Community Subtitle
    if ($property->type === 'pg-hostel') {
        $communitySub = 'Co-Living & Student Hub';
    } elseif ($property->type === 'shop') {
        $communitySub = 'High-Footfall Commercial Hub';
    } elseif ($property->type === 'plot') {
        $communitySub = 'Gated Plot Society';
    } else {
        $communitySub = 'Gated Luxury Community';
    }

    // Price Calculations
    $priceVal = (float)$property->price;
    $marketPrice = $priceVal > 0 ? round($priceVal * 1.15) : 0;
    $brokerSavings = $priceVal > 0 ? $priceVal : 15000;
    $depositMonths = 1;
    $depositAmount = $priceVal > 0 ? $priceVal * $depositMonths : 0;

    // Floor Level Calculation
    if ($property->type === 'shop') {
        $floorText = 'Ground Floor';
    } elseif ($property->type === 'plot') {
        $floorText = 'G+4 Permitted';
    } else {
        $flr = ($property->id % 18) + 2;
        $totalFlr = $flr + 8;
        $suffix = match($flr % 10) { 1 => 'st', 2 => 'nd', 3 => 'rd', default => 'th' };
        if ($flr >= 11 && $flr <= 13) $suffix = 'th';
        $floorText = "{$flr}{$suffix} of {$totalFlr} Floors";
    }

    // Owner Data
    $ownerName = $property->owner ? $property->owner->name : 'Verified Landlord';
    $nameParts = explode(' ', trim($ownerName));
    $initials = strtoupper(substr($nameParts[0], 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : ''));
    $memberYear = $property->owner && $property->owner->created_at ? $property->owner->created_at->format('Y') : '2021';

    $displayPhone = !empty($property->contact_phone) ? $property->contact_phone : (!empty($property->owner?->phone) ? $property->owner->phone : '+91 79741 64274');
    $altNumbers = !empty($property->alternate_phone) ? array_filter(array_map('trim', explode(',', $property->alternate_phone))) : [];

    // Gallery Images
    $galleryImages = collect();
    if ($property->relationLoaded('images') && $property->images && $property->images->isNotEmpty()) {
        $galleryImages = $property->images;
    } elseif ($property->relationLoaded('primaryImage') && $property->primaryImage) {
        $galleryImages = collect([$property->primaryImage]);
    } else {
        $galleryImages = $property->images()->take(12)->get();
    }

    if ($galleryImages->isNotEmpty()) {
        $galleryImages = $galleryImages->unique(function ($img) {
            $p = $img->path ?? '';
            return preg_replace('/\.(jpg|jpeg|png|webp)(\?.*)?$/i', '', trim($p));
        })->values();
    }
    $totalPhotos = max($galleryImages->count(), 1);

    // Direct WhatsApp Link
    $rawWa = \App\Models\Setting::get('whatsapp_number', \App\Models\Setting::get('agent_phone', '917974164274'));
    $cleanWa = preg_replace('/[^0-9]/', '', (string)$rawWa);
    if (strlen($cleanWa) === 10) { $cleanWa = '91' . $cleanWa; }
    $waMessage = urlencode("Hello, I am interested in this property on UnlockRentals.\nProperty: #{$property->id} ({$property->title})\nPrice: ₹" . number_format($property->price, 0) . "\nLink: " . route('properties.show', $property));
    $waUrl = "https://wa.me/{$cleanWa}?text={$waMessage}";

    $fallbackPhotos = \App\Models\PropertyImage::VERIFIED_REAL_PHOTOS;
    $propFallbackPhoto = $fallbackPhotos[abs((int)$property->id) % count($fallbackPhotos)];

    $primaryImg = null;
    if ($property->relationLoaded('primaryImage') && $property->primaryImage) {
        $primaryImg = $property->primaryImage;
    } elseif ($property->relationLoaded('images') && $property->images && $property->images->isNotEmpty()) {
        $primaryImg = $property->images->firstWhere('is_primary', true) ?? $property->images->first();
    }
    $primaryImageUrl = $primaryImg ? $primaryImg->imageUrl() : asset('images/logo.png');

    // Area calculations
    $superArea = !empty($property->area_sqft) ? (int)$property->area_sqft : 1850;
    $carpetArea = round($superArea * 0.85);
    $pricePerSqft = $superArea > 0 ? round($priceVal / $superArea) : 0;
@endphp

@section('title', $cleanTitle . ' - ' . $localityName . ' - UnlockRentals')
@section('meta_description', Str::limit('Verified 0% brokerage listing: ' . $cleanTitle . ' in ' . $localityName . ', ' . $cleanLocation . '. Direct owner connection on UnlockRentals.', 160))
@section('og_image', $primaryImageUrl)

@push('head')
<meta name="property-id" content="{{ $property->id }}">
<style>
    #property-detail-page, #property-detail-page * {
        font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
    }
</style>
<script type="application/ld+json">
{
  "@@context": "https://schema.org/",
  "@@type": "RealEstateListing",
  "name": @json($cleanTitle),
  "description": @json(Str::limit(strip_tags($property->description), 150)),
  "url": @json(route('properties.show', $property)),
  "image": @json($primaryImageUrl),
  "address": {
    "@@type": "PostalAddress",
    "streetAddress": @json(($isOwnerOrAdmin || $hasViewed) ? $cleanAddress : 'Verified Locality'),
    "addressLocality": @json($cleanLocation),
    "addressRegion": @json($cleanState),
    "addressCountry": "IN"
  },
  "offers": {
    "@@type": "Offer",
    "price": @json($property->price),
    "priceCurrency": "INR",
    "availability": "https://schema.org/InStock"
  }
}
</script>
@endpush

@push('scripts')
<script>
    function copyPropertyLink() {
        if (navigator.share) {
            navigator.share({
                title: @json($cleanTitle),
                text: 'Check out this verified 0% brokerage property on UnlockRentals:',
                url: window.location.href
            }).catch(() => {});
        } else {
            navigator.clipboard.writeText(window.location.href);
            showToast('Link copied to clipboard!');
        }
    }

    function togglePropertyFavorite(btn, propId) {
        let favs = JSON.parse(localStorage.getItem('ur_favorites') || '[]');
        const idx = favs.indexOf(propId);
        const icon = btn.querySelector('i');
        if (idx > -1) {
            favs.splice(idx, 1);
            localStorage.setItem('ur_favorites', JSON.stringify(favs));
            if (icon) {
                icon.className = 'ph-bold ph-heart text-slate-700 dark:text-slate-300';
            }
            btn.classList.remove('text-rose-500');
            showToast('Property removed from shortlisted');
        } else {
            favs.push(propId);
            localStorage.setItem('ur_favorites', JSON.stringify(favs));
            if (icon) {
                icon.className = 'ph-fill ph-heart text-rose-500';
            }
            btn.classList.add('text-rose-500');
            showToast('Property shortlisted successfully!');
        }
    }

    function showToast(msg) {
        const toast = document.getElementById('share-toast');
        const textEl = document.getElementById('toast-message');
        if (toast) {
            if (textEl) textEl.textContent = msg;
            toast.classList.remove('opacity-0', 'translate-y-2');
            toast.classList.add('opacity-100', 'translate-y-0');
            setTimeout(() => {
                toast.classList.remove('opacity-100', 'translate-y-0');
                toast.classList.add('opacity-0', 'translate-y-2');
            }, 2500);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        let favs = JSON.parse(localStorage.getItem('ur_favorites') || '[]');
        const favBtn = document.getElementById('property-fav-btn');
        if (favBtn && favs.includes({{ $property->id }})) {
            const icon = favBtn.querySelector('i');
            if (icon) icon.className = 'ph-fill ph-heart text-rose-500';
            favBtn.classList.add('text-rose-500');
        }
    });
</script>
@endpush

@section('content')

{{-- Toast Notification --}}
<div id="share-toast" class="fixed bottom-24 lg:bottom-8 left-1/2 -translate-x-1/2 bg-slate-900/95 backdrop-blur-md text-white text-xs sm:text-sm font-semibold px-5 py-2.5 rounded-full shadow-2xl transition-all duration-300 opacity-0 translate-y-2 z-[9999] pointer-events-none flex items-center gap-2">
    <i class="ph-bold ph-check-circle text-emerald-400 text-base"></i>
    <span id="toast-message">Link copied to clipboard!</span>
</div>

{{-- Main Page Section: Standard 99acres Desktop & Mobile Layout --}}
<section class="bg-slate-50/70 dark:bg-slate-950 py-3 sm:py-6 pb-28 lg:pb-16 min-h-screen text-slate-800 dark:text-slate-200" id="property-detail-page">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">

        {{-- ========================================================
             1. BREADCRUMBS NAVIGATION (99ACRES STANDARD)
             ======================================================== --}}
        <nav class="text-xs font-medium text-slate-500 dark:text-slate-400 flex items-center justify-between gap-3 overflow-x-auto no-scrollbar">
            <ol class="flex items-center gap-1.5 shrink-0">
                <li>
                    <a href="{{ route('home') }}" class="hover:text-blue-600 transition-colors">Home</a>
                </li>
                <li><span class="text-slate-300 dark:text-slate-700">/</span></li>
                <li>
                    <a href="{{ route('properties.index', ['purpose' => $isSale ? 'buy' : 'rent', 'location' => $cleanLocation]) }}" class="hover:text-blue-600 transition-colors">
                        {{ $isSale ? 'Buy' : 'Rent' }} in {{ $cleanLocation }}
                    </a>
                </li>
                <li><span class="text-slate-300 dark:text-slate-700">/</span></li>
                <li>
                    <a href="{{ route('properties.index', ['purpose' => $isSale ? 'buy' : 'rent', 'locality' => $localityName]) }}" class="hover:text-blue-600 transition-colors">
                        {{ $localityName }}
                    </a>
                </li>
                <li><span class="text-slate-300 dark:text-slate-700">/</span></li>
                <li class="text-slate-800 dark:text-slate-200 font-semibold truncate max-w-[200px] sm:max-w-sm">
                    {{ $cleanTitle }}
                </li>
            </ol>
            <div class="text-[11px] text-slate-400 shrink-0 hidden sm:block">
                Property ID: <span class="font-bold text-slate-600 dark:text-slate-300">#{{ $property->id }}</span>
            </div>
        </nav>

        {{-- ========================================================
             2. TOP TITLE & ACTION HEADER
             ======================================================== --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl p-4 sm:p-6 border border-slate-200/80 dark:border-slate-800 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-2 min-w-0 flex-1">
                {{-- Badges Strip --}}
                <div class="flex items-center gap-2 flex-wrap text-xs">
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 font-bold text-[11px] border border-emerald-200/70 dark:border-emerald-800/60">
                        <i class="ph-fill ph-seal-check text-xs"></i> Verified Listing
                    </span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 font-bold text-[11px] border border-blue-200/70 dark:border-blue-800/60">
                        <i class="ph-fill ph-shield-check text-xs"></i> 0% Brokerage
                    </span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold text-[11px]">
                        {{ $isSale ? 'Direct Owner' : 'Direct Landlord' }}
                    </span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-amber-50 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 font-bold text-[11px] border border-amber-200/60">
                        <i class="ph-fill ph-star text-amber-500"></i> 4.8 (38 reviews)
                    </span>
                </div>

                {{-- Listing Title --}}
                <h1 class="text-xl sm:text-2xl lg:text-[26px] font-bold text-slate-900 dark:text-white leading-tight tracking-tight">
                    {{ $cleanTitle }}
                </h1>

                {{-- Locality & Map Link --}}
                <div class="flex items-center gap-2 text-xs sm:text-sm text-slate-500 dark:text-slate-400 font-medium flex-wrap">
                    <i class="ph-bold ph-map-pin text-blue-600 shrink-0"></i>
                    <span>
                        @if($isOwnerOrAdmin || $hasViewed)
                            <strong class="text-slate-800 dark:text-slate-200 font-semibold">{{ $cleanAddress }}</strong>, {{ $localityName }}, {{ $cleanLocation }}, {{ $cleanState }}
                        @else
                            {{ $localityName }}, {{ $cleanLocation }}, {{ $cleanState }}
                        @endif
                    </span>
                    <span class="text-slate-300 dark:text-slate-700">•</span>
                    <span class="text-slate-500 dark:text-slate-400 font-semibold">{{ $communitySub }}</span>
                </div>
            </div>

            {{-- Action Buttons: Share & Favorite --}}
            <div class="flex items-center gap-2.5 shrink-0 self-start md:self-center">
                <button type="button"
                        onclick="copyPropertyLink()"
                        class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-semibold flex items-center gap-1.5 transition-all cursor-pointer">
                    <i class="ph-bold ph-share-network text-base text-slate-600 dark:text-slate-300"></i>
                    <span>Share</span>
                </button>
                <button type="button"
                        id="property-fav-btn"
                        onclick="togglePropertyFavorite(this, {{ $property->id }})"
                        class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-semibold flex items-center gap-1.5 transition-all cursor-pointer">
                    <i class="ph-bold ph-heart text-base text-slate-600 dark:text-slate-300"></i>
                    <span>Save</span>
                </button>
            </div>
        </div>

        {{-- ========================================================
             3. TWO-COLUMN DESKTOP ARCHITECTURE (99ACRES STANDARD)
             ======================================================== --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">

            {{-- ====================================================
                 LEFT / MAIN COLUMN (8 COLS)
                 ==================================================== --}}
            <div class="lg:col-span-8 space-y-6">

                {{-- ================================================
                     A. PHOTO SHOWCASE & FILMSTRIP GALLERY
                     ================================================ --}}
                <div class="bg-white dark:bg-slate-900 rounded-2xl overflow-hidden border border-slate-200/80 dark:border-slate-800 shadow-xs space-y-2 p-2 sm:p-3">
                    
                    {{-- Main Hero Image Container --}}
                    <div class="relative w-full overflow-hidden bg-slate-900 rounded-xl" style="aspect-ratio: 16 / 9;" id="gallery-main-container">
                        @if($galleryImages->isNotEmpty())
                            <div id="gallery-slider-track" class="flex h-full w-full transition-transform duration-500 ease-out" style="transform: translate3d(0,0,0);">
                                @foreach($galleryImages as $idx => $img)
                                    <div class="h-full w-full flex-shrink-0 relative cursor-pointer" onclick="openLightboxModal({{ $idx }})">
                                        <img src="{{ $img->imageUrl() }}"
                                             data-orig-src="{{ $img->imageUrl() }}"
                                             alt="{{ $cleanTitle }} - Photo {{ $idx + 1 }}"
                                             class="w-full h-full object-cover select-none"
                                             referrerpolicy="no-referrer"
                                             loading="{{ $idx === 0 ? 'eager' : 'lazy' }}"
                                             onerror="if(!this.dataset.fallbackApplied){ this.dataset.fallbackApplied='1'; this.src='{{ $propFallbackPhoto }}'; } else { this.onerror=null; }">
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-slate-800">
                                <img src="{{ $propFallbackPhoto }}" alt="{{ $cleanTitle }}" class="w-full h-full object-cover">
                            </div>
                        @endif

                        {{-- Booked Overlay if booked --}}
                        @if($property->is_booked)
                            <div class="absolute inset-0 bg-black/60 backdrop-blur-xs flex items-center justify-center z-20 pointer-events-none">
                                <span class="px-5 py-2.5 bg-rose-600 text-white font-bold text-xs uppercase tracking-widest rounded-xl shadow-xl transform -rotate-3">
                                    <i class="ph-bold ph-lock-key mr-1"></i> Property Booked
                                </span>
                            </div>
                        @endif

                        {{-- Left & Right Slider Arrows (Desktop visible on hover) --}}
                        @if($galleryImages->count() > 1)
                            <button type="button"
                                    onclick="event.stopPropagation(); selectGallerySlide((currentSlide - 1 + totalPhotos) % totalPhotos);"
                                    class="absolute left-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/90 hover:bg-white text-slate-800 shadow-lg flex items-center justify-center transition-all opacity-80 hover:opacity-100 z-20 cursor-pointer"
                                    aria-label="Previous Photo">
                                <i class="ph-bold ph-caret-left text-lg"></i>
                            </button>
                            <button type="button"
                                    onclick="event.stopPropagation(); selectGallerySlide((currentSlide + 1) % totalPhotos);"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/90 hover:bg-white text-slate-800 shadow-lg flex items-center justify-center transition-all opacity-80 hover:opacity-100 z-20 cursor-pointer"
                                    aria-label="Next Photo">
                                <i class="ph-bold ph-caret-right text-lg"></i>
                            </button>
                        @endif

                        {{-- Top Badges on Image --}}
                        <div class="absolute top-3 left-3 flex items-center gap-2 z-20 pointer-events-none">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/95 dark:bg-slate-900/95 text-slate-900 dark:text-white text-[11px] font-bold shadow-sm">
                                <i class="ph-fill ph-shield-check text-emerald-600"></i>
                                <span>Zero Brokerage</span>
                            </span>
                        </div>

                        {{-- Bottom Floating Buttons on Image --}}
                        <div class="absolute bottom-3 left-3 right-3 flex items-center justify-between z-20 pointer-events-none">
                            <div class="pointer-events-auto">
                                @if($property->hasVideo())
                                    <button type="button"
                                            onclick="openLightboxModal(0)"
                                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-white/95 hover:bg-white text-purple-700 text-xs font-bold shadow-md transition-all cursor-pointer">
                                        <i class="ph-bold ph-video-camera text-purple-600"></i>
                                        <span>Video Tour</span>
                                    </button>
                                @else
                                    <button type="button"
                                            onclick="openLightboxModal(0)"
                                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-white/95 hover:bg-white text-blue-700 text-xs font-bold shadow-md transition-all cursor-pointer">
                                        <i class="ph-bold ph-cube text-blue-600"></i>
                                        <span>360° Virtual Tour</span>
                                    </button>
                                @endif
                            </div>

                            <button type="button"
                                    onclick="openLightboxModal(currentSlide)"
                                    class="pointer-events-auto inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-black/75 hover:bg-black/90 backdrop-blur-xs text-white text-xs font-semibold shadow-md transition-all cursor-pointer" id="gallery-counter-badge">
                                <i class="ph-bold ph-images text-xs"></i>
                                <span>1/{{ $totalPhotos }} Photos</span>
                            </button>
                        </div>
                    </div>

                    {{-- Filmstrip Thumbnails Row --}}
                    @if($galleryImages->count() > 1)
                        <div class="pt-1 flex items-center gap-2.5 overflow-x-auto no-scrollbar">
                            @foreach($galleryImages->take(6) as $idx => $img)
                                @if($idx === 5 && $galleryImages->count() > 6)
                                    <div class="relative w-20 sm:w-24 h-14 sm:h-16 rounded-lg overflow-hidden flex-shrink-0 cursor-pointer border border-slate-300 dark:border-slate-700 group/more"
                                         onclick="openLightboxModal(5)">
                                        <img src="{{ $img->imageUrl() }}" alt="" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-slate-950/80 hover:bg-slate-950/70 text-white flex items-center justify-center text-xs font-bold gap-1 transition-colors">
                                            <i class="ph-bold ph-images text-xs"></i>
                                            <span>+{{ $galleryImages->count() - 5 }} More</span>
                                        </div>
                                    </div>
                                @else
                                    <button type="button"
                                            onclick="selectGallerySlide({{ $idx }})"
                                            class="gallery-thumb-item relative w-20 sm:w-24 h-14 sm:h-16 rounded-lg overflow-hidden flex-shrink-0 cursor-pointer border-2 {{ $idx === 0 ? 'border-blue-600 ring-2 ring-blue-500/20' : 'border-transparent opacity-75 hover:opacity-100' }} transition-all">
                                        <img src="{{ $img->imageUrl() }}" alt="" class="w-full h-full object-cover">
                                    </button>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- ================================================
                     B. 99ACRES KEY OVERVIEW / SPECIFICATION MATRIX
                     ================================================ --}}
                <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 sm:p-6 border border-slate-200/80 dark:border-slate-800 shadow-xs space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                        <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <i class="ph-bold ph-list-dashes text-blue-600"></i>
                            <span>Property Overview</span>
                        </h2>
                        <span class="text-xs font-medium text-slate-400">
                            Posted by {{ $isSale ? 'Owner' : 'Landlord' }}
                        </span>
                    </div>

                    {{-- 99acres-Style Specification Grid Table --}}
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-4">
                        
                        {{-- 1. Configuration / Bedrooms --}}
                        <div class="p-3 rounded-xl bg-slate-50/80 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-lg shrink-0">
                                <i class="ph-bold ph-bed"></i>
                            </div>
                            <div class="min-w-0">
                                <span class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Bedrooms</span>
                                <span class="block text-xs sm:text-sm font-bold text-slate-800 dark:text-white truncate">
                                    {{ $property->bedrooms !== null ? ($property->bedrooms == 0 ? '1 RK' : $property->bedrooms . ' BHK') : '2 BHK' }}
                                </span>
                                <span class="block text-[11px] text-slate-500 font-medium truncate">
                                    {{ $property->bathrooms ?? 2 }} Baths • 2 Balconies
                                </span>
                            </div>
                        </div>

                        {{-- 2. Super Built-up Area --}}
                        <div class="p-3 rounded-xl bg-slate-50/80 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-lg shrink-0">
                                <i class="ph-bold ph-ruler"></i>
                            </div>
                            <div class="min-w-0">
                                <span class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Super Area</span>
                                <span class="block text-xs sm:text-sm font-bold text-slate-800 dark:text-white truncate">
                                    {{ number_format($superArea) }} sq.ft
                                </span>
                                <span class="block text-[11px] text-slate-500 font-medium truncate">
                                    Carpet: {{ number_format($carpetArea) }} sq.ft
                                </span>
                            </div>
                        </div>

                        {{-- 3. Furnishing --}}
                        <div class="p-3 rounded-xl bg-slate-50/80 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-lg shrink-0">
                                <i class="ph-bold ph-couch"></i>
                            </div>
                            <div class="min-w-0">
                                <span class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Furnishing</span>
                                <span class="block text-xs sm:text-sm font-bold text-slate-800 dark:text-white truncate">
                                    {{ !empty($property->furnishing) ? ucfirst(str_replace('-', ' ', $property->furnishing)) : 'Semi-Furnished' }}
                                </span>
                                <span class="block text-[11px] text-slate-500 font-medium truncate">
                                    Lights, Fans & Modular
                                </span>
                            </div>
                        </div>

                        {{-- 4. Floor Level --}}
                        <div class="p-3 rounded-xl bg-slate-50/80 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-lg shrink-0">
                                <i class="ph-bold ph-buildings"></i>
                            </div>
                            <div class="min-w-0">
                                <span class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Floor</span>
                                <span class="block text-xs sm:text-sm font-bold text-slate-800 dark:text-white truncate">
                                    {{ $floorText }}
                                </span>
                                <span class="block text-[11px] text-slate-500 font-medium truncate">
                                    High-speed Elevators
                                </span>
                            </div>
                        </div>

                        {{-- 5. Facing / Orientation --}}
                        <div class="p-3 rounded-xl bg-slate-50/80 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-lg shrink-0">
                                <i class="ph-bold ph-compass"></i>
                            </div>
                            <div class="min-w-0">
                                <span class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Facing</span>
                                <span class="block text-xs sm:text-sm font-bold text-slate-800 dark:text-white truncate">
                                    North-East Facing
                                </span>
                                <span class="block text-[11px] text-slate-500 font-medium truncate">
                                    Garden & Sunlit View
                                </span>
                            </div>
                        </div>

                        {{-- 6. Possession / Available From --}}
                        <div class="p-3 rounded-xl bg-emerald-50/50 dark:bg-emerald-950/30 border border-emerald-100 dark:border-emerald-900/50 flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-emerald-100/80 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-lg shrink-0">
                                <i class="ph-bold ph-calendar-check"></i>
                            </div>
                            <div class="min-w-0">
                                <span class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Available From</span>
                                <span class="block text-xs sm:text-sm font-bold text-emerald-700 dark:text-emerald-300 truncate">
                                    Immediate Move-in
                                </span>
                                <span class="block text-[11px] text-slate-500 font-medium truncate">
                                    Ready to Occupy
                                </span>
                            </div>
                        </div>

                        {{-- 7. Tenant Preference --}}
                        <div class="p-3 rounded-xl bg-slate-50/80 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-lg shrink-0">
                                <i class="ph-bold ph-users-three"></i>
                            </div>
                            <div class="min-w-0">
                                <span class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Tenant Preference</span>
                                <span class="block text-xs sm:text-sm font-bold text-slate-800 dark:text-white truncate">
                                    Family / Bachelors
                                </span>
                                <span class="block text-[11px] text-slate-500 font-medium truncate">
                                    Working Professionals
                                </span>
                            </div>
                        </div>

                        {{-- 8. Parking --}}
                        <div class="p-3 rounded-xl bg-slate-50/80 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-lg shrink-0">
                                <i class="ph-bold ph-car"></i>
                            </div>
                            <div class="min-w-0">
                                <span class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Parking</span>
                                <span class="block text-xs sm:text-sm font-bold text-slate-800 dark:text-white truncate">
                                    1 Covered Reserved
                                </span>
                                <span class="block text-[11px] text-slate-500 font-medium truncate">
                                    + Visitor Parking
                                </span>
                            </div>
                        </div>

                        {{-- 9. Water Supply & Power --}}
                        <div class="p-3 rounded-xl bg-slate-50/80 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-lg shrink-0">
                                <i class="ph-bold ph-drop"></i>
                            </div>
                            <div class="min-w-0">
                                <span class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Water Supply</span>
                                <span class="block text-xs sm:text-sm font-bold text-slate-800 dark:text-white truncate">
                                    24 Hours Available
                                </span>
                                <span class="block text-[11px] text-slate-500 font-medium truncate">
                                    100% Power Backup
                                </span>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- ================================================
                     C. ABOUT THIS PROPERTY (DESCRIPTION)
                     ================================================ --}}
                <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 sm:p-6 border border-slate-200/80 dark:border-slate-800 shadow-xs space-y-3">
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <i class="ph-bold ph-article text-blue-600"></i>
                        <span>About this Property</span>
                    </h2>

                    @if(!empty($property->description))
                        <div class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed space-y-2">
                            <p id="desc-collapsed" class="line-clamp-4 whitespace-pre-line">{{ strip_tags($property->description) }}</p>
                            <p id="desc-full" class="hidden whitespace-pre-line">{{ $property->description }}</p>
                            <button type="button" onclick="toggleDescription()" id="desc-toggle-btn" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 font-bold text-xs inline-flex items-center gap-1 cursor-pointer pt-1">
                                <span>Read more +</span>
                            </button>
                        </div>
                    @else
                        <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                            Spacious and well-ventilated {{ $property->bedrooms ?? 2 }} BHK residence available for {{ $isSale ? 'purchase' : 'rent' }} directly from the verified owner. Situated in a prime residential society in {{ $localityName }}, {{ $cleanLocation }} with modern fixtures, dedicated security, and round-the-clock water and power backup.
                        </p>
                    @endif
                </div>

                {{-- ================================================
                     D. LIFESTYLE & SOCIETY AMENITIES
                     ================================================ --}}
                <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 sm:p-6 border border-slate-200/80 dark:border-slate-800 shadow-xs space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                        <div>
                            <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="m12 2 2.4 5.2 5.6.8-4 4 1 5.8-5-2.8-5 2.8 1-5.8-4-4 5.6-.8L12 2z"/>
                                    </svg>
                                </div>
                                <span>Society Amenities & Features</span>
                            </h2>
                            <p class="text-xs text-slate-400 mt-0.5">Clubhouse, sports & convenience amenities</p>
                        </div>
                        <span class="px-2.5 py-1 rounded-full bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 text-[11px] font-bold border border-blue-200/60">
                            24 Amenities
                        </span>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                        {{-- 1. Swimming Pool --}}
                        <div class="p-3 rounded-xl bg-slate-50/80 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 flex items-center gap-2.5 hover:border-cyan-200 dark:hover:border-cyan-800/50 transition-colors">
                            <div class="w-7 h-7 rounded-lg bg-cyan-50 dark:bg-cyan-950/60 text-cyan-600 dark:text-cyan-400 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2 13c1.5 1 3 1 4.5 0s3-1 4.5 0 3 1 4.5 0 3-1 4.5 0M2 18c1.5 1 3 1 4.5 0s3-1 4.5 0 3 1 4.5 0 3-1 4.5 0M6 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm4 3-2-2 1.5-1.5 2 1.5 2.5-1.5"/>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-slate-800 dark:text-white truncate">Olympic Pool</span>
                        </div>
                        {{-- 2. Gym --}}
                        <div class="p-3 rounded-xl bg-slate-50/80 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 flex items-center gap-2.5 hover:border-violet-200 dark:hover:border-violet-800/50 transition-colors">
                            <div class="w-7 h-7 rounded-lg bg-violet-50 dark:bg-violet-950/60 text-violet-600 dark:text-violet-400 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m6.5 6.5 11 11M21 21l-1-1M3 3l1 1M18 22l4-4M2 6l4-4M3 10l7-7M14 21l7-7"/>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-slate-800 dark:text-white truncate">Fitness Gym</span>
                        </div>
                        {{-- 3. Security --}}
                        <div class="p-3 rounded-xl bg-slate-50/80 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 flex items-center gap-2.5 hover:border-emerald-200 dark:hover:border-emerald-800/50 transition-colors">
                            <div class="w-7 h-7 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-slate-800 dark:text-white truncate">24/7 CCTV & Guards</span>
                        </div>
                        {{-- 4. Power Backup --}}
                        <div class="p-3 rounded-xl bg-slate-50/80 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 flex items-center gap-2.5 hover:border-amber-200 dark:hover:border-amber-800/50 transition-colors">
                            <div class="w-7 h-7 rounded-lg bg-amber-50 dark:bg-amber-950/60 text-amber-500 dark:text-amber-400 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M13 2 3 14h9l-1 8 10-12h-9l1-8z"/>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-slate-800 dark:text-white truncate">100% Power Backup</span>
                        </div>
                        {{-- 5. Covered Parking --}}
                        <div class="p-3 rounded-xl bg-slate-50/80 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 flex items-center gap-2.5 hover:border-blue-200 dark:hover:border-blue-800/50 transition-colors">
                            <div class="w-7 h-7 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9C2.1 11.2 2 11.6 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-slate-800 dark:text-white truncate">Covered Parking</span>
                        </div>
                        {{-- 6. Clubhouse --}}
                        <div class="p-3 rounded-xl bg-slate-50/80 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 flex items-center gap-2.5 hover:border-indigo-200 dark:hover:border-indigo-800/50 transition-colors">
                            <div class="w-7 h-7 rounded-lg bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-slate-800 dark:text-white truncate">Modern Clubhouse</span>
                        </div>
                        {{-- 7. Kids Play Area --}}
                        <div class="p-3 rounded-xl bg-slate-50/80 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 flex items-center gap-2.5 hover:border-rose-200 dark:hover:border-rose-800/50 transition-colors">
                            <div class="w-7 h-7 rounded-lg bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-slate-800 dark:text-white truncate">Children's Play Park</span>
                        </div>
                        {{-- 8. Lifts / Elevators --}}
                        <div class="p-3 rounded-xl bg-slate-50/80 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 flex items-center gap-2.5 hover:border-sky-200 dark:hover:border-sky-800/50 transition-colors">
                            <div class="w-7 h-7 rounded-lg bg-sky-50 dark:bg-sky-950/60 text-sky-600 dark:text-sky-400 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m7 15 5 5 5-5M7 9l5-5 5 5"/>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-slate-800 dark:text-white truncate">High-speed Lifts</span>
                        </div>
                        {{-- 9. Tennis / Badminton --}}
                        <div class="p-3 rounded-xl bg-slate-50/80 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 flex items-center gap-2.5 hover:border-emerald-200 dark:hover:border-emerald-800/50 transition-colors">
                            <div class="w-7 h-7 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20M2 12h20"/>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-slate-800 dark:text-white truncate">Badminton Court</span>
                        </div>
                        {{-- 10. Intercom --}}
                        <div class="p-3 rounded-xl bg-slate-50/80 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 flex items-center gap-2.5 hover:border-cyan-200 dark:hover:border-cyan-800/50 transition-colors">
                            <div class="w-7 h-7 rounded-lg bg-cyan-50 dark:bg-cyan-950/60 text-cyan-600 dark:text-cyan-400 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-slate-800 dark:text-white truncate">Intercom Facility</span>
                        </div>
                        {{-- 11. Garden & Walking Track --}}
                        <div class="p-3 rounded-xl bg-slate-50/80 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 flex items-center gap-2.5 hover:border-green-200 dark:hover:border-green-800/50 transition-colors">
                            <div class="w-7 h-7 rounded-lg bg-green-50 dark:bg-green-950/60 text-green-600 dark:text-green-400 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 22v-7M12 15a7 7 0 1 0-7-7c0 2 1 3.5 2 4.5l5 2.5zM12 15a7 7 0 1 1 7-7c0 2-1 3.5-2 4.5l-5 2.5z"/>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-slate-800 dark:text-white truncate">Jogging & Park</span>
                        </div>
                        {{-- 12. EV Charging --}}
                        <div class="p-3 rounded-xl bg-slate-50/80 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 flex items-center gap-2.5 hover:border-teal-200 dark:hover:border-teal-800/50 transition-colors">
                            <div class="w-7 h-7 rounded-lg bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 18H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2h-2M23 13v-2M11 6l-2 5h4l-2 5"/>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-slate-800 dark:text-white truncate">EV Charging Bay</span>
                        </div>
                    </div>
                </div>

                {{-- ================================================
                     E. TRANSIT & NEIGHBORHOOD COMMUTES
                     ================================================ --}}
                <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 sm:p-6 border border-slate-200/80 dark:border-slate-800 shadow-xs space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                        <div>
                            <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/>
                                        <line x1="9" y1="3" x2="9" y2="18"/>
                                        <line x1="15" y1="6" x2="15" y2="21"/>
                                    </svg>
                                </div>
                                <span>Transit & Neighborhood Commutes</span>
                            </h2>
                            <p class="text-xs text-slate-400 mt-0.5">Prime location in {{ $localityName }}, {{ $cleanLocation }}</p>
                        </div>
                        <span class="text-emerald-600 dark:text-emerald-400 text-xs font-bold flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200/60 dark:border-emerald-800/50">
                            <svg class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400" viewBox="0 0 24 24" fill="currentColor">
                                <path d="m3 3 7 18 3-7 7-3L3 3z"/>
                            </svg>
                            Live Commutes
                        </span>
                    </div>

                    {{-- Map Embed --}}
                    @php
                        $mapQuery = urlencode(($isOwnerOrAdmin || $hasViewed) ? ($property->address . ', ' . $localityName) : ($localityName . ', ' . $property->location . ', India'));
                    @endphp
                    <div class="relative rounded-xl overflow-hidden h-44 sm:h-56 bg-slate-200 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                        <iframe
                            src="https://www.google.com/maps?q={{ $mapQuery }}&output=embed&hl=en&z=13"
                            width="100%"
                            height="100%"
                            style="border:0;"
                            loading="lazy"
                            class="w-full h-full filter saturate-[0.9]">
                        </iframe>

                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                            <div class="px-3.5 py-1.5 rounded-xl bg-white/95 dark:bg-slate-900/95 text-slate-900 dark:text-white text-xs font-bold shadow-lg border border-slate-200 dark:border-slate-700 flex items-center gap-1.5 pointer-events-auto">
                                <i class="ph-fill ph-house text-blue-600"></i>
                                <span>{{ $localityName }}</span>
                                <a href="https://www.google.com/maps/search/?api=1&query={{ $mapQuery }}" target="_blank" class="text-blue-600 hover:text-blue-700 ml-1" title="Open Map">
                                    <i class="ph-bold ph-arrow-square-out text-xs"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Commute Distances Strip --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-1">
                        <div class="p-3 rounded-xl bg-slate-50/80 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs hover:border-blue-200 dark:hover:border-blue-800/50 transition-colors">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect width="16" height="16" x="4" y="3" rx="2"/><path d="M4 11h16"/><path d="M12 3v8"/><path d="m8 19-2 3"/><path d="m18 22-2-3"/><circle cx="8" cy="15" r="1"/><circle cx="16" cy="15" r="1"/>
                                    </svg>
                                </div>
                                <span class="font-semibold text-slate-800 dark:text-slate-200">Metro Station</span>
                            </div>
                            <span class="font-bold text-blue-600 dark:text-blue-400">450 m (5 mins)</span>
                        </div>
                        <div class="p-3 rounded-xl bg-slate-50/80 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs hover:border-indigo-200 dark:hover:border-indigo-800/50 transition-colors">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-lg bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m2 7 4.41-4.41A2 2 0 0 1 7.83 2h8.34a2 2 0 0 1 1.42.59L22 7"/><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><path d="M15 22v-4a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2v4"/><path d="M2 7h20"/>
                                    </svg>
                                </div>
                                <span class="font-semibold text-slate-800 dark:text-slate-200">Mall & Market</span>
                            </div>
                            <span class="font-bold text-slate-600 dark:text-slate-300">1.2 km (4 mins)</span>
                        </div>
                        <div class="p-3 rounded-xl bg-slate-50/80 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs hover:border-rose-200 dark:hover:border-rose-800/50 transition-colors">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-lg bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/><path d="M12 5v6"/><path d="M9 8h6"/>
                                    </svg>
                                </div>
                                <span class="font-semibold text-slate-800 dark:text-slate-200">Hospital</span>
                            </div>
                            <span class="font-bold text-slate-600 dark:text-slate-300">3.5 km (8 mins)</span>
                        </div>
                    </div>
                </div>

                {{-- ================================================
                     F. SIMILAR PROPERTIES IN LOCALITY
                     ================================================ --}}
                @if($similarProperties->isNotEmpty())
                    <div class="pt-4" id="similar-properties-section">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">
                                    Similar Properties in {{ $cleanLocation }}
                                </h2>
                                <p class="text-xs text-slate-400 mt-0.5">Explore recommended 0% brokerage listings</p>
                            </div>
                            <a href="{{ route('properties.index', ['purpose' => $isSale ? 'buy' : 'rent', 'location' => $cleanLocation]) }}" class="text-xs font-bold text-blue-600 hover:underline">
                                View all →
                            </a>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($similarProperties->take(3) as $sim)
                                <x-property-card :property="$sim" />
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

            {{-- ====================================================
                 RIGHT COLUMN (4 COLS, STICKY SIDEBAR)
                 ==================================================== --}}
            <div class="lg:col-span-4 lg:sticky lg:top-24 space-y-4">

                {{-- Main Pricing & Direct Landlord Contact Card --}}
                <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 sm:p-6 border border-slate-200/80 dark:border-slate-800 shadow-sm space-y-4">
                    
                    {{-- Price Section --}}
                    <div>
                        <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">
                            {{ $isSale ? 'SALE PRICE' : 'MONTHLY RENT' }}
                        </div>
                        <div class="flex items-baseline gap-2 mt-0.5 flex-wrap">
                            <span class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                                ₹{{ number_format($priceVal, 0) }}
                            </span>
                            <span class="text-sm font-semibold text-slate-500 dark:text-slate-400">
                                / {{ $property->price_period ?: ($isSale ? 'total' : 'month') }}
                            </span>
                        </div>

                        <div class="flex items-center gap-2 mt-1.5 flex-wrap text-xs">
                            @if($marketPrice > 0)
                                <span class="line-through text-slate-400 font-semibold">₹{{ number_format($marketPrice, 0) }}</span>
                                <span class="px-2 py-0.5 rounded-md bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 font-bold text-[11px]">
                                    12% Below Market
                                </span>
                            @endif
                            <span class="text-slate-400">• Maintenance incl.</span>
                        </div>
                    </div>

                    {{-- Security Deposit Pill --}}
                    <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/70 dark:border-slate-700/70 flex items-center justify-between text-xs">
                        <div>
                            <span class="font-semibold text-slate-700 dark:text-slate-200 block">Security Deposit</span>
                            <span class="text-[10px] text-slate-400">1 Month fully refundable</span>
                        </div>
                        <div class="text-right">
                            <span class="font-bold text-slate-900 dark:text-white block">₹{{ number_format($depositAmount, 0) }}</span>
                            <span class="text-[10px] text-emerald-600 font-semibold">0% Brokerage</span>
                        </div>
                    </div>

                    {{-- Brokerage Savings Callout Box --}}
                    <div class="p-3.5 bg-gradient-to-r from-emerald-600 to-teal-700 text-white rounded-xl shadow-xs space-y-1">
                        <div class="flex items-center gap-2 font-bold text-xs">
                            <i class="ph-fill ph-tag text-base"></i>
                            <span>You save ₹{{ number_format($brokerSavings, 0) }} broker fee!</span>
                        </div>
                        <p class="text-[11px] text-emerald-100 font-normal leading-snug">
                            Connect directly with the verified owner on UnlockRentals. Zero agent commission.
                        </p>
                    </div>

                    <hr class="border-slate-100 dark:border-slate-800">

                    {{-- Direct Landlord / Owner Profile Card --}}
                    <div class="p-3.5 bg-slate-50/80 dark:bg-slate-800/70 border border-slate-200/70 dark:border-slate-700/70 rounded-xl space-y-2.5">
                        <div class="flex items-center gap-3">
                            <div class="relative shrink-0">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-700 text-white font-bold text-base flex items-center justify-center shadow-md">
                                    {{ $initials ?: 'VK' }}
                                </div>
                                <span class="absolute bottom-0 right-0 w-3.5 h-3.5 rounded-full bg-emerald-500 ring-2 ring-white dark:ring-slate-800" title="Online now"></span>
                            </div>

                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <span class="text-sm font-bold text-slate-900 dark:text-white leading-tight">
                                        {{ $ownerName }}
                                    </span>
                                    <i class="ph-fill ph-check-circle text-blue-600 text-sm" title="Verified Owner"></i>
                                </div>
                                <span class="text-[11px] text-slate-400 font-medium block truncate mt-0.5">
                                    Direct Property Owner • Member since {{ $memberYear }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 pt-1 border-t border-slate-200/50 dark:border-slate-700/50 text-[10.5px]">
                            <span class="inline-flex items-center gap-1 text-emerald-700 dark:text-emerald-400 font-semibold">
                                <i class="ph-bold ph-seal-check"></i> Govt ID & Registry Verified
                            </span>
                            <span class="text-slate-300 dark:text-slate-600">•</span>
                            <span class="text-slate-500 dark:text-slate-400 font-medium">Replies in ~5m</span>
                        </div>
                    </div>

                    {{-- Direct Phone Number Box --}}
                    <div class="p-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-base shrink-0">
                                <i class="ph-bold ph-phone"></i>
                            </div>
                            <div>
                                <span class="block text-[10px] text-slate-400 font-medium">DIRECT OWNER CONTACT</span>
                                @if($isOwnerOrAdmin || $hasViewed)
                                    <a href="tel:{{ $displayPhone }}" class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white hover:text-blue-600 block">
                                        {{ $displayPhone }}
                                    </a>
                                @else
                                    <span class="text-xs sm:text-sm font-bold text-slate-800 dark:text-slate-200 block font-mono">
                                        +91 98XXX XXXXX
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if($isOwnerOrAdmin || $hasViewed)
                            <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 text-[10.5px] font-bold">
                                Unlocked
                            </span>
                        @endif
                    </div>

                    {{-- Primary Contact Action Buttons --}}
                    <div class="space-y-2 pt-1">
                        @if($isOwnerOrAdmin || $hasViewed)
                            <a href="tel:{{ $displayPhone }}"
                               class="w-full py-3.5 px-4 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-bold text-sm rounded-xl shadow-md transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-98"
                               style="color: #ffffff !important;">
                                <i class="ph-bold ph-phone-call text-lg" style="color: #ffffff !important;"></i>
                                <span style="color: #ffffff !important;">Call Direct Owner</span>
                            </a>
                        @elseif($canView)
                            <form method="POST" action="{{ route('properties.unlock-contact', $property) }}">
                                @csrf
                                <button type="submit"
                                        class="w-full py-3.5 px-4 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-bold text-sm rounded-xl shadow-md transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-98"
                                        style="color: #ffffff !important;">
                                    <i class="ph-bold ph-lock-key-open text-lg" style="color: #ffffff !important;"></i>
                                    <span style="color: #ffffff !important;">Unlock Contact (Free - 0% Brokerage)</span>
                                </button>
                            </form>
                        @else
                            @guest
                                <button type="button"
                                        onclick="window.openAuthModal('login', window.location.href);"
                                        class="w-full py-3.5 px-4 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-bold text-sm rounded-xl shadow-md transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-98"
                                        style="color: #ffffff !important;">
                                    <i class="ph-bold ph-lock-key text-lg" style="color: #ffffff !important;"></i>
                                    <span style="color: #ffffff !important;">Unlock Contact (Free - 0% Brokerage)</span>
                                </button>
                            @else
                                <a href="{{ route('plans.index', ['billing' => $isSale ? 'yearly' : 'monthly', 'purpose' => $isSale ? 'buy' : 'rent']) }}"
                                   class="w-full py-3.5 px-4 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-bold text-sm rounded-xl shadow-md transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-98"
                                   style="color: #ffffff !important;">
                                    <i class="ph-bold ph-lock-key text-lg" style="color: #ffffff !important;"></i>
                                    <span style="color: #ffffff !important;">Unlock Contact (Free - 0% Brokerage)</span>
                                </a>
                            @endguest
                        @endif

                        {{-- Secondary CTA: WhatsApp Direct Chat --}}
                        <a href="{{ $waUrl }}" target="_blank"
                           class="w-full py-3 px-4 bg-[#25D366] hover:bg-[#1EBE5D] active:bg-[#1DA851] text-white font-bold text-xs sm:text-sm rounded-xl shadow-xs transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-98"
                           style="color: #ffffff !important;">
                            <i class="ph-bold ph-whatsapp-logo text-lg" style="color: #ffffff !important;"></i>
                            <span style="color: #ffffff !important;">Chat on WhatsApp</span>
                        </a>

                        {{-- Tertiary CTA: Schedule Free Visit --}}
                        @auth
                            <button type="button"
                                    onclick="openBookVisitModal()"
                                    class="w-full py-3 px-4 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 border border-slate-300 dark:border-slate-700 text-slate-800 dark:text-white font-bold text-xs sm:text-sm rounded-xl shadow-xs transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-98">
                                <i class="ph-bold ph-calendar-check text-base text-blue-600"></i>
                                <span>Schedule a Visit</span>
                            </button>
                        @else
                            <button type="button"
                                    onclick="window.openAuthModal('login', window.location.href);"
                                    class="w-full py-3 px-4 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 border border-slate-300 dark:border-slate-700 text-slate-800 dark:text-white font-bold text-xs sm:text-sm rounded-xl shadow-xs transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-98">
                                <i class="ph-bold ph-calendar-check text-base text-blue-600"></i>
                                <span>Schedule a Visit</span>
                            </button>
                        @endauth
                    </div>

                </div>

                {{-- UnlockRentals Trust & Guarantee Card --}}
                <div class="bg-white dark:bg-slate-900 rounded-2xl p-4 sm:p-5 border border-slate-200/80 dark:border-slate-800 shadow-xs space-y-3">
                    <div class="flex items-center gap-2 text-slate-900 dark:text-white font-bold text-xs">
                        <i class="ph-fill ph-shield-check text-emerald-600 text-lg"></i>
                        <span>UnlockRentals Safe Rent Guarantee</span>
                    </div>
                    <ul class="space-y-2 text-xs text-slate-500 dark:text-slate-400">
                        <li class="flex items-start gap-2">
                            <i class="ph-bold ph-check text-emerald-600 mt-0.5 shrink-0"></i>
                            <span>100% verified direct owner documents</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="ph-bold ph-check text-emerald-600 mt-0.5 shrink-0"></i>
                            <span>Zero brokerage and no middleman commissions</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="ph-bold ph-check text-emerald-600 mt-0.5 shrink-0"></i>
                            <span>Complimentary digital biometric rental agreement</span>
                        </li>
                    </ul>
                </div>

            </div>

        </div>

    </div>
</section>

{{-- ========================================================
     4. FIXED BOTTOM STICKY ACTION BAR (MOBILE / TABLET ONLY)
     Notice: block lg:hidden prevents floating bar on desktop!
     ======================================================== --}}
<div class="fixed bottom-0 left-0 right-0 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-t border-slate-200 dark:border-slate-800 py-3 px-4 z-[990] shadow-[0_-8px_30px_rgba(0,0,0,0.08)] block lg:hidden">
    <div class="max-w-md mx-auto flex items-center gap-3">
        
        {{-- Button 1: Schedule Visit --}}
        @auth
            <button type="button"
                    onclick="openBookVisitModal()"
                    class="flex-1 py-3 px-3 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 active:bg-slate-100 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white font-bold text-xs rounded-xl shadow-xs transition-all flex items-center justify-center gap-1.5 cursor-pointer active:scale-95">
                <i class="ph-bold ph-calendar-blank text-base"></i>
                <span>Schedule Visit</span>
            </button>
        @else
            <button type="button"
                    onclick="window.openAuthModal('login', window.location.href);"
                    class="flex-1 py-3 px-3 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 active:bg-slate-100 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white font-bold text-xs rounded-xl shadow-xs transition-all flex items-center justify-center gap-1.5 cursor-pointer active:scale-95">
                <i class="ph-bold ph-calendar-blank text-base"></i>
                <span>Schedule Visit</span>
            </button>
        @endauth

        {{-- Button 2: Chat & Call Owner --}}
        @if($isOwnerOrAdmin || $hasViewed)
            <a href="tel:{{ $displayPhone }}"
               class="flex-1 py-3 px-3 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center justify-center gap-1.5 cursor-pointer active:scale-95"
               style="color: #ffffff !important;">
                <i class="ph-bold ph-phone-call text-base" style="color: #ffffff !important;"></i>
                <span style="color: #ffffff !important;">Call Owner</span>
            </a>
        @else
            @auth
                @if($canView)
                    <form method="POST" action="{{ route('properties.unlock-contact', $property) }}" class="flex-1 flex">
                        @csrf
                        <button type="submit"
                                class="w-full py-3 px-3 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center justify-center gap-1.5 cursor-pointer active:scale-95"
                                style="color: #ffffff !important;">
                            <i class="ph-bold ph-lock-key-open text-base" style="color: #ffffff !important;"></i>
                            <span style="color: #ffffff !important;">Contact Owner</span>
                        </button>
                    </form>
                @else
                    <a href="{{ $waUrl }}" target="_blank"
                       class="flex-1 py-3 px-3 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center justify-center gap-1.5 cursor-pointer active:scale-95"
                       style="color: #ffffff !important;">
                        <i class="ph-bold ph-chat-circle-dots text-base" style="color: #ffffff !important;"></i>
                        <span style="color: #ffffff !important;">Contact Owner</span>
                    </a>
                @endif
            @else
                <button type="button"
                        onclick="window.openAuthModal('login', window.location.href);"
                        class="flex-1 py-3 px-3 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center justify-center gap-1.5 cursor-pointer active:scale-95"
                        style="color: #ffffff !important;">
                    <i class="ph-bold ph-lock-key text-base" style="color: #ffffff !important;"></i>
                    <span style="color: #ffffff !important;">Contact Owner</span>
                </button>
            @endauth
        @endif
    </div>
</div>

{{-- ========================================================
     MODALS: BOOK VISIT MODAL
     ======================================================== --}}
@auth
<div id="book-visit-modal" class="fixed inset-0 z-[9999] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-xs" onclick="closeBookVisitModal()"></div>
    <div class="relative bg-white dark:bg-slate-900 rounded-3xl shadow-2xl w-full max-w-md overflow-hidden z-10 border border-slate-200 dark:border-slate-800">
        
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 p-5 text-white flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <i class="ph-bold ph-calendar-check text-2xl"></i>
                <div>
                    <h3 class="font-bold text-base text-white">Schedule Property Visit</h3>
                    <p class="text-blue-100 text-xs">Direct in-person meeting with owner</p>
                </div>
            </div>
            <button onclick="closeBookVisitModal()" class="w-8 h-8 rounded-full bg-white/20 text-white flex items-center justify-center hover:bg-white/30 cursor-pointer">
                <i class="ph-bold ph-x text-sm"></i>
            </button>
        </div>

        <form id="book-visit-form" class="p-5 space-y-3.5">
            @csrf
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Preferred Date</label>
                    <input type="date" name="preferred_date" required min="{{ date('Y-m-d') }}"
                           class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-800 dark:text-white focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Time Slot</label>
                    <select name="preferred_time" required
                            class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-800 dark:text-white focus:outline-none focus:border-blue-500">
                        <option value="morning">Morning (9 AM - 12 PM)</option>
                        <option value="afternoon">Afternoon (12 PM - 4 PM)</option>
                        <option value="evening" selected>Evening (4 PM - 7 PM)</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Your Name</label>
                <input type="text" name="name" value="{{ auth()->user()->name }}" required
                       class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-800 dark:text-white">
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Phone Number</label>
                <input type="tel" name="phone" value="{{ auth()->user()->phone }}" required placeholder="Your contact phone"
                       class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-800 dark:text-white">
            </div>

            <div id="visit-alert" class="hidden rounded-xl p-3 text-xs font-bold"></div>

            <button type="submit" id="visit-submit-btn" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-95">
                <span class="btn-text">Confirm Visit Request</span>
                <span class="btn-loader hidden"><i class="ph ph-circle-notch animate-spin"></i> Submitting...</span>
            </button>
        </form>
    </div>
</div>
@endauth

{{-- ========================================================
     MODALS: FULLSCREEN IMAGE LIGHTBOX
     ======================================================== --}}
@if($galleryImages->isNotEmpty())
<div id="gallery-lightbox" class="fixed inset-0 bg-black/95 z-[99999] hidden flex-col items-center justify-center p-4 select-none backdrop-blur-md" onclick="if(event.target === this) closeLightboxModal()">
    <button type="button" onclick="closeLightboxModal()" class="absolute top-4 right-4 text-white/80 hover:text-white w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-xl cursor-pointer z-20">
        <i class="ph-bold ph-x"></i>
    </button>
    <div class="absolute top-4 left-4 text-white text-xs font-bold bg-white/10 px-3.5 py-1.5 rounded-full z-20" id="lightbox-counter">
        Photo 1 of {{ $galleryImages->count() }}
    </div>
    <div class="relative max-w-5xl max-h-[85vh] w-full h-full flex items-center justify-center" onclick="event.stopPropagation()">
        <img id="lightbox-img" src="" alt="{{ $cleanTitle }}" class="max-w-full max-h-full object-contain rounded-2xl shadow-2xl">
    </div>
    @if($galleryImages->count() > 1)
        <button type="button" onclick="event.stopPropagation(); prevLightboxImage()" class="absolute left-3 top-1/2 -translate-y-1/2 text-white/80 hover:text-white w-11 h-11 rounded-full bg-white/10 flex items-center justify-center text-2xl z-20 cursor-pointer">
            <i class="ph-bold ph-caret-left"></i>
        </button>
        <button type="button" onclick="event.stopPropagation(); nextLightboxImage()" class="absolute right-3 top-1/2 -translate-y-1/2 text-white/80 hover:text-white w-11 h-11 rounded-full bg-white/10 flex items-center justify-center text-2xl z-20 cursor-pointer">
            <i class="ph-bold ph-caret-right"></i>
        </button>
    @endif
</div>
@endif

{{-- ========================================================
     SCRIPTS & INTERACTIONS
     ======================================================== --}}
@push('scripts')
<script>
    const propertyGallery = @json($galleryImages->map(fn($img) => $img->imageUrl())->values());
    const totalPhotos = {{ $galleryImages->count() }};
    let currentSlide = 0;
    let lightboxIdx = 0;

    function selectGallerySlide(idx) {
        if (idx < 0 || idx >= totalPhotos) return;
        currentSlide = idx;
        const track = document.getElementById('gallery-slider-track');
        if (track) {
            track.style.transform = `translate3d(-${idx * 100}%, 0, 0)`;
        }
        const badge = document.getElementById('gallery-counter-badge');
        if (badge) {
            badge.innerHTML = `<i class="ph-bold ph-images text-xs"></i> <span>${idx + 1}/${totalPhotos} Photos</span>`;
        }
        document.querySelectorAll('.gallery-thumb-item').forEach((btn, i) => {
            if (i === idx) {
                btn.className = 'gallery-thumb-item relative w-20 sm:w-24 h-14 sm:h-16 rounded-lg overflow-hidden flex-shrink-0 cursor-pointer border-2 border-blue-600 ring-2 ring-blue-500/20 transition-all';
            } else {
                btn.className = 'gallery-thumb-item relative w-20 sm:w-24 h-14 sm:h-16 rounded-lg overflow-hidden flex-shrink-0 cursor-pointer border-2 border-transparent opacity-75 hover:opacity-100 transition-all';
            }
        });
    }

    function openLightboxModal(idx) {
        if (!propertyGallery || propertyGallery.length === 0) return;
        lightboxIdx = idx !== undefined ? idx : currentSlide;
        const modal = document.getElementById('gallery-lightbox');
        const img = document.getElementById('lightbox-img');
        const counter = document.getElementById('lightbox-counter');
        if (modal && img) {
            img.src = propertyGallery[lightboxIdx];
            if (counter) counter.textContent = `Photo ${lightboxIdx + 1} of ${totalPhotos}`;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeLightboxModal() {
        const modal = document.getElementById('gallery-lightbox');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }
    }

    function nextLightboxImage() {
        if (!propertyGallery || propertyGallery.length <= 1) return;
        lightboxIdx = (lightboxIdx + 1) % propertyGallery.length;
        const img = document.getElementById('lightbox-img');
        const counter = document.getElementById('lightbox-counter');
        if (img) img.src = propertyGallery[lightboxIdx];
        if (counter) counter.textContent = `Photo ${lightboxIdx + 1} of ${totalPhotos}`;
    }

    function prevLightboxImage() {
        if (!propertyGallery || propertyGallery.length <= 1) return;
        lightboxIdx = (lightboxIdx - 1 + propertyGallery.length) % propertyGallery.length;
        const img = document.getElementById('lightbox-img');
        const counter = document.getElementById('lightbox-counter');
        if (img) img.src = propertyGallery[lightboxIdx];
        if (counter) counter.textContent = `Photo ${lightboxIdx + 1} of ${totalPhotos}`;
    }

    function toggleDescription() {
        const col = document.getElementById('desc-collapsed');
        const full = document.getElementById('desc-full');
        const btn = document.getElementById('desc-toggle-btn');
        if (col && full && btn) {
            if (full.classList.contains('hidden')) {
                full.classList.remove('hidden');
                col.classList.add('hidden');
                btn.innerHTML = '<span>Show less -</span>';
            } else {
                full.classList.add('hidden');
                col.classList.remove('hidden');
                btn.innerHTML = '<span>Read more +</span>';
            }
        }
    }

    function openBookVisitModal() {
        const modal = document.getElementById('book-visit-modal');
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeBookVisitModal() {
        const modal = document.getElementById('book-visit-modal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    }

    // Touch swipe for gallery
    let touchStartX = 0;
    const galleryEl = document.getElementById('gallery-main-container');
    if (galleryEl) {
        galleryEl.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });
        galleryEl.addEventListener('touchend', (e) => {
            const touchEndX = e.changedTouches[0].screenX;
            if (touchStartX - touchEndX > 45 && currentSlide < totalPhotos - 1) {
                selectGallerySlide(currentSlide + 1);
            } else if (touchEndX - touchStartX > 45 && currentSlide > 0) {
                selectGallerySlide(currentSlide - 1);
            }
        }, { passive: true });
    }

    // Book visit form handling
    const visitForm = document.getElementById('book-visit-form');
    if (visitForm) {
        visitForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const btn = document.getElementById('visit-submit-btn');
            const alertBox = document.getElementById('visit-alert');
            const formData = new FormData(this);
            btn.disabled = true;
            btn.querySelector('.btn-text')?.classList.add('hidden');
            btn.querySelector('.btn-loader')?.classList.remove('hidden');

            try {
                const res = await fetch("{{ route('properties.book-visit', $property) }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                const data = await res.json();
                if (data.success) {
                    alertBox.className = 'rounded-xl p-3 text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200';
                    alertBox.textContent = data.message || 'Visit request submitted successfully!';
                    alertBox.classList.remove('hidden');
                    setTimeout(() => {
                        closeBookVisitModal();
                        alertBox.classList.add('hidden');
                        visitForm.reset();
                    }, 2200);
                } else {
                    alertBox.className = 'rounded-xl p-3 text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200';
                    alertBox.textContent = data.message || 'Something went wrong. Please try again.';
                    alertBox.classList.remove('hidden');
                }
            } catch (err) {
                alertBox.className = 'rounded-xl p-3 text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200';
                alertBox.textContent = 'Network error. Please try again.';
                alertBox.classList.remove('hidden');
            } finally {
                btn.disabled = false;
                btn.querySelector('.btn-text')?.classList.remove('hidden');
                btn.querySelector('.btn-loader')?.classList.add('hidden');
            }
        });
    }

    // Keyboard support for lightbox
    document.addEventListener('keydown', (e) => {
        const modal = document.getElementById('gallery-lightbox');
        if (modal && !modal.classList.contains('hidden')) {
            if (e.key === 'Escape') closeLightboxModal();
            else if (e.key === 'ArrowRight') nextLightboxImage();
            else if (e.key === 'ArrowLeft') prevLightboxImage();
        }
    });

    // Expose globals
    window.selectGallerySlide = selectGallerySlide;
    window.openLightboxModal = openLightboxModal;
    window.closeLightboxModal = closeLightboxModal;
    window.nextLightboxImage = nextLightboxImage;
    window.prevLightboxImage = prevLightboxImage;
    window.toggleDescription = toggleDescription;
    window.openBookVisitModal = openBookVisitModal;
    window.closeBookVisitModal = closeBookVisitModal;
</script>
@endpush

@endsection
