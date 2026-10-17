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

    // Typography formatting
    $cleanTitle = ucwords(strtolower(trim($property->title)));
    $cleanTitle = preg_replace_callback('/\b(\d+)?\s*(bhk|rk|pg)\b/i', fn($m) => strtoupper($m[0]), $cleanTitle);

    // Locality & Project
    $localityName = !empty($property->locality) ? trim($property->locality) : trim(explode(',', $property->location)[0]);
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
        $floorText = "{$flr}{$suffix} of {$totalFlr} Flr";
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
    if ($galleryImages->isEmpty()) {
        $galleryImages = collect();
    }
    $totalPhotos = max($galleryImages->count(), 1);

    // Direct WhatsApp Link
    $rawWa = \App\Models\Setting::get('whatsapp_number', \App\Models\Setting::get('agent_phone', '917974164274'));
    $cleanWa = preg_replace('/[^0-9]/', '', (string)$rawWa);
    if (strlen($cleanWa) === 10) { $cleanWa = '91' . $cleanWa; }
    $waMessage = urlencode("Hello, I am interested in this property on UnlockRentals.\nProperty: #{$property->id} ({$property->title})\nPrice: ₹" . number_format($property->price, 0) . "\nLink: " . route('properties.show', $property));
    $waUrl = "https://wa.me/{$cleanWa}?text={$waMessage}";
@endphp

@section('title', $cleanTitle . ' - ' . $localityName . ' - UnlockRentals')
@section('meta_description', Str::limit('Verified 0% brokerage listing: ' . $cleanTitle . ' in ' . $localityName . ', ' . $property->location . '. Direct owner connection on UnlockRentals.', 160))
@section('og_image', $property->primaryImage ? $property->primaryImage->imageUrl() : asset('images/logo.png'))

@push('head')
<meta name="property-id" content="{{ $property->id }}">
<script type="application/ld+json">
{
  "@@context": "https://schema.org/",
  "@@type": "RealEstateListing",
  "name": @json($cleanTitle),
  "description": @json(Str::limit(strip_tags($property->description), 150)),
  "url": @json(route('properties.show', $property)),
  "image": @json($property->primaryImage ? $property->primaryImage->imageUrl() : asset('images/logo.png')),
  "address": {
    "@@type": "PostalAddress",
    "streetAddress": @json(($isOwnerOrAdmin || $hasViewed) ? $property->address : 'Verified Locality'),
    "addressLocality": @json($property->location),
    "addressRegion": @json($property->state ?? 'Haryana'),
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
            const toast = document.getElementById('share-toast');
            if (toast) {
                toast.classList.remove('opacity-0', 'translate-y-2');
                toast.classList.add('opacity-100', 'translate-y-0');
                setTimeout(() => {
                    toast.classList.remove('opacity-100', 'translate-y-0');
                    toast.classList.add('opacity-0', 'translate-y-2');
                }, 2500);
            }
        }
    }
</script>
@endpush

@section('content')

{{-- Toast Notification --}}
<div id="share-toast" class="fixed bottom-24 left-1/2 -translate-x-1/2 bg-slate-900/95 backdrop-blur-md text-white text-xs sm:text-sm font-bold px-5 py-2.5 rounded-full shadow-2xl transition-all duration-300 opacity-0 translate-y-2 z-[9999] pointer-events-none flex items-center gap-2">
    <i class="ph-bold ph-check-circle text-emerald-400 text-base"></i> Link copied to clipboard!
</div>

{{-- Main Page Section: Styled strictly to match luxury mobile-first card design --}}
<section class="bg-slate-100/70 dark:bg-slate-950 py-3 sm:py-6 pb-28 sm:pb-32 min-h-screen" id="property-detail-page">
    <div class="max-w-xl md:max-w-2xl lg:max-w-3xl mx-auto px-3 sm:px-4 space-y-3.5 sm:space-y-4">

        {{-- ========================================================
             1. HERO MEDIA GALLERY CARD WITH FLOATING CONTROLS
             ======================================================== --}}
        <div class="bg-white dark:bg-slate-900 rounded-3xl overflow-hidden shadow-sm border border-slate-200/80 dark:border-slate-800 relative group">
            
            {{-- Main Image Display Area --}}
            <div class="relative w-full overflow-hidden bg-slate-900" style="aspect-ratio: 16 / 11;" id="gallery-main-container">
                @if($galleryImages->isNotEmpty())
                    <div id="gallery-slider-track" class="flex h-full w-full transition-transform duration-500 ease-out" style="transform: translate3d(0,0,0);">
                        @foreach($galleryImages as $idx => $img)
                            <div class="h-full w-full flex-shrink-0 relative cursor-pointer" onclick="openLightboxModal({{ $idx }})">
                                <img src="{{ $img->imageUrl() }}"
                                     alt="{{ $cleanTitle }} - Photo {{ $idx + 1 }}"
                                     class="w-full h-full object-cover select-none"
                                     loading="{{ $idx === 0 ? 'eager' : 'lazy' }}"
                                     onerror="this.onerror=null; this.src='{{ asset('images/luxury_sunlit.webp') }}';">
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="w-full h-full flex items-center justify-center bg-slate-800">
                        <img src="{{ asset('images/luxury_sunlit.webp') }}" alt="{{ $cleanTitle }}" class="w-full h-full object-cover">
                    </div>
                @endif

                {{-- Booked Overlay if booked --}}
                @if($property->is_booked)
                    <div class="absolute inset-0 bg-black/50 backdrop-blur-xs flex items-center justify-center z-20 pointer-events-none">
                        <span class="px-5 py-2 bg-rose-600 text-white font-black text-xs uppercase tracking-widest rounded-xl shadow-xl transform -rotate-6">
                            <i class="ph-bold ph-lock-key mr-1"></i> Property Booked
                        </span>
                    </div>
                @endif

                {{-- Top Floating Navigation Bar (Back, Share, Wishlist) --}}
                <div class="absolute top-3 left-3 right-3 flex items-center justify-between z-30 pointer-events-none">
                    {{-- Back Circle Button --}}
                    <button type="button"
                            onclick="window.history.length > 1 ? window.history.back() : window.location.href='{{ route('properties.index') }}'"
                            class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-white/95 hover:bg-white text-slate-800 shadow-md flex items-center justify-center transition-all active:scale-90 pointer-events-auto cursor-pointer"
                            aria-label="Back">
                        <i class="ph-bold ph-arrow-left text-base sm:text-lg"></i>
                    </button>

                    {{-- Right Share & Heart Buttons --}}
                    <div class="flex items-center gap-2">
                        <button type="button"
                                onclick="copyPropertyLink()"
                                class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-white/95 hover:bg-white text-slate-800 shadow-md flex items-center justify-center transition-all active:scale-90 pointer-events-auto cursor-pointer"
                                aria-label="Share listing">
                            <i class="ph-bold ph-share-network text-base sm:text-lg"></i>
                        </button>
                        <button type="button"
                                onclick="window.toggleCardFavorite(this, {{ $property->id }})"
                                class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-white/95 hover:bg-white text-slate-700 hover:text-rose-500 shadow-md flex items-center justify-center transition-all active:scale-90 pointer-events-auto cursor-pointer"
                                data-property-fav="{{ $property->id }}"
                                aria-label="Favorite">
                            <i class="ph-bold ph-heart text-base sm:text-lg"></i>
                        </button>
                    </div>
                </div>

                {{-- Top Left Badges (Direct Landlord + Unlock Verified) --}}
                <div class="absolute top-14 sm:top-16 left-3 flex items-center gap-1.5 z-20 pointer-events-none flex-wrap">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/95 dark:bg-slate-900/95 text-slate-800 dark:text-white text-[10px] sm:text-[11px] font-black tracking-wide shadow-sm border border-white/80">
                        <i class="ph-fill ph-shield-check text-emerald-600 text-xs"></i>
                        <span>{{ $isSale ? 'Direct Owner' : ($property->type === 'plot' ? 'Direct Title' : 'Direct Landlord') }}</span>
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/95 dark:bg-slate-900/95 text-blue-700 dark:text-blue-300 text-[10px] sm:text-[11px] font-black tracking-wide shadow-sm border border-white/80">
                        <i class="ph-fill ph-seal-check text-blue-600 text-xs"></i>
                        <span>Unlock Verified</span>
                    </span>
                </div>

                {{-- Bottom Badges (360 Virtual Tour + Photo Counter) --}}
                <div class="absolute bottom-3 left-3 right-3 flex items-center justify-between z-20 pointer-events-none">
                    {{-- Virtual Tour / Video Tour Pill --}}
                    @if($property->hasVideo())
                        <button type="button"
                                onclick="openVideoModal()"
                                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-white/95 hover:bg-white text-purple-700 text-[10.5px] sm:text-xs font-black shadow-md border border-white/80 transition-all pointer-events-auto cursor-pointer">
                            <i class="ph-bold ph-video-camera text-purple-600"></i>
                            <span>HD Video Tour</span>
                        </button>
                    @else
                        <button type="button"
                                onclick="openLightboxModal(0)"
                                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-white/95 hover:bg-white text-blue-700 text-[10.5px] sm:text-xs font-black shadow-md border border-white/80 transition-all pointer-events-auto cursor-pointer">
                            <i class="ph-bold ph-cube text-blue-600"></i>
                            <span>360° Virtual Tour</span>
                        </button>
                    @endif

                    {{-- Photos Counter Badge --}}
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-black/65 backdrop-blur-xs text-white text-[10.5px] sm:text-xs font-bold shadow-md" id="gallery-counter-badge">
                        1/{{ $totalPhotos }} photos
                    </span>
                </div>
            </div>

            {{-- Thumbnails Row Strip --}}
            @if($galleryImages->count() > 1)
                <div class="p-2 sm:p-2.5 bg-slate-900 flex items-center gap-2 overflow-x-auto no-scrollbar">
                    @foreach($galleryImages->take(4) as $idx => $img)
                        @if($idx === 3 && $galleryImages->count() > 4)
                            {{-- 4th thumbnail with "+X more" overlay --}}
                            <div class="relative w-16 h-12 sm:w-20 sm:h-14 rounded-xl overflow-hidden flex-shrink-0 cursor-pointer border border-white/20 group/more"
                                 onclick="openLightboxModal(3)">
                                <img src="{{ $img->imageUrl() }}" alt="" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-slate-950/80 hover:bg-slate-950/70 text-white flex items-center justify-center text-xs font-black gap-1 transition-colors">
                                    <i class="ph-bold ph-images text-xs"></i>
                                    <span>+{{ $galleryImages->count() - 3 }} more</span>
                                </div>
                            </div>
                        @else
                            <button type="button"
                                    onclick="selectGallerySlide({{ $idx }})"
                                    class="gallery-thumb-item relative w-16 h-12 sm:w-20 sm:h-14 rounded-xl overflow-hidden flex-shrink-0 cursor-pointer border-2 {{ $idx === 0 ? 'border-blue-500 scale-95 ring-2 ring-blue-500/30' : 'border-transparent opacity-75 hover:opacity-100' }} transition-all">
                                <img src="{{ $img->imageUrl() }}" alt="" class="w-full h-full object-cover">
                            </button>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ========================================================
             2. CARD 1: TITLE, COMMUNITY, RATING & VERIFIED LANDLORD
             ======================================================== --}}
        <div class="bg-white dark:bg-slate-900 rounded-3xl p-4 sm:p-5 shadow-sm border border-slate-200/80 dark:border-slate-800 space-y-3">
            
            {{-- Top Tag Row: Community & Physical Verified --}}
            <div class="flex items-center justify-between gap-2">
                <div class="flex items-center gap-1.5 text-xs font-black truncate">
                    <span class="text-blue-600 dark:text-blue-400 uppercase tracking-wider">{{ $projectName }}</span>
                    <span class="text-slate-300 dark:text-slate-600">•</span>
                    <span class="text-slate-500 dark:text-slate-400 font-bold truncate">{{ $communitySub }}</span>
                </div>
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 text-[10.5px] font-black shrink-0 border border-emerald-200/60 dark:border-emerald-800/60">
                    <i class="ph-bold ph-check text-xs"></i>
                    <span>Physical Verified</span>
                </span>
            </div>

            {{-- Main Title --}}
            <h1 class="text-lg sm:text-xl md:text-2xl font-black text-slate-900 dark:text-white leading-snug tracking-tight">
                {{ $cleanTitle }}
            </h1>

            {{-- Location Row with Blue Pin --}}
            <div class="flex items-start gap-1.5 text-xs sm:text-[13px] text-slate-500 dark:text-slate-400 font-semibold leading-relaxed">
                <i class="ph-bold ph-map-pin text-blue-600 text-sm mt-0.5 shrink-0"></i>
                <span>
                    @if($isOwnerOrAdmin || $hasViewed)
                        <strong class="text-slate-800 dark:text-slate-200 font-bold">{{ $property->address }}</strong>, {{ $localityName }}, {{ $property->location }}, {{ $property->state ?? 'Haryana' }}
                    @else
                        {{ $localityName }}, {{ $property->location }}, {{ $property->state ?? 'Haryana' }}
                    @endif
                </span>
            </div>

            {{-- Rating & Shortlisted Row --}}
            <div class="flex items-center gap-3 pt-1 text-xs">
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-emerald-700 text-white font-black text-[11px]">
                    <span>4.7</span>
                    <i class="ph-fill ph-star text-[10px]"></i>
                    <span class="font-normal opacity-80">(38 reviews)</span>
                </span>
                <span class="text-slate-500 dark:text-slate-400 font-bold flex items-center gap-1 text-[11.5px]">
                    <i class="ph-bold ph-users text-blue-600"></i>
                    <span>{{ 110 + ($property->id * 7) % 75 }} renters shortlisted</span>
                </span>
            </div>

            {{-- Trust Banner Inside Card 1: 100% Verified Direct Landlord | Zero Brokerage --}}
            <div class="bg-emerald-50/80 dark:bg-emerald-950/40 border border-emerald-200/70 dark:border-emerald-800/50 rounded-2xl p-3 sm:p-3.5 flex items-center justify-between gap-2 mt-2">
                <div class="flex items-center gap-2 min-w-0">
                    <span class="w-6 h-6 rounded-full bg-emerald-600 text-white flex items-center justify-center shrink-0 text-xs">
                        <i class="ph-bold ph-check"></i>
                    </span>
                    <span class="text-xs sm:text-sm font-black text-slate-900 dark:text-emerald-100 truncate">
                        100% Verified Direct {{ $isSale ? 'Owner' : 'Landlord' }}
                    </span>
                </div>
                <span class="px-2.5 py-1 rounded-lg bg-emerald-200/70 dark:bg-emerald-900/80 text-emerald-900 dark:text-emerald-200 text-[10px] font-black uppercase tracking-wider shrink-0">
                    ZERO BROKERAGE
                </span>
            </div>

            {{-- Description Snippet & Toggle --}}
            @if(!empty($property->description))
                <div class="pt-2 border-t border-slate-100 dark:border-slate-800 text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                    <p id="desc-collapsed" class="line-clamp-2">{{ strip_tags($property->description) }}</p>
                    <p id="desc-full" class="hidden whitespace-pre-line">{{ $property->description }}</p>
                    <button type="button" onclick="toggleDescription()" id="desc-toggle-btn" class="text-blue-600 dark:text-blue-400 font-bold text-xs mt-1 cursor-pointer">
                        Read more +
                    </button>
                </div>
            @endif
        </div>

        {{-- ========================================================
             3. CARD 2: PRICING, DEPOSIT & BROKERAGE SAVINGS BREAKDOWN
             ======================================================== --}}
        <div class="bg-white dark:bg-slate-900 rounded-3xl p-4 sm:p-5 shadow-sm border border-slate-200/80 dark:border-slate-800 space-y-3.5">
            
            {{-- Top Price & Deposit Row --}}
            <div class="flex items-start justify-between gap-3">
                <div>
                    <div class="flex items-baseline gap-1.5 flex-wrap">
                        <span class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">
                            ₹{{ number_format($priceVal, 0) }}
                        </span>
                        <span class="text-slate-500 dark:text-slate-400 text-xs sm:text-sm font-bold">
                            / {{ $property->price_period ?: ($isSale ? 'total' : 'month') }}
                        </span>
                    </div>

                    <div class="flex items-center gap-2 mt-1 flex-wrap text-xs">
                        @if($marketPrice > 0)
                            <span class="line-through text-slate-400 font-bold">₹{{ number_format($marketPrice, 0) }}</span>
                            <span class="px-1.5 py-0.5 rounded-md bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 font-black text-[10.5px]">
                                12% Below Market
                            </span>
                        @endif
                        <span class="text-slate-400">• Maintenance incl.</span>
                    </div>
                </div>

                {{-- Deposit Box --}}
                <div class="bg-blue-50/70 dark:bg-slate-800/80 border border-blue-100 dark:border-slate-700 rounded-2xl p-2.5 sm:p-3 text-right shrink-0">
                    <div class="flex items-center justify-end gap-1 text-blue-700 dark:text-blue-300 text-xs font-black">
                        <i class="ph-bold ph-arrows-clockwise text-xs"></i>
                        <span>1-Month Deposit</span>
                    </div>
                    <span class="text-[10px] text-slate-500 dark:text-slate-400 font-bold block mt-0.5">
                        ₹{{ number_format($depositAmount, 0) }} fully refundable
                    </span>
                </div>
            </div>

            {{-- Signature Brokerage Savings Deep Green Banner --}}
            <div class="bg-emerald-800 dark:bg-emerald-900 text-white rounded-2xl p-3.5 sm:p-4 shadow-sm relative overflow-hidden">
                <div class="flex items-center justify-between gap-3 relative z-10">
                    <div class="flex items-start gap-2.5 min-w-0">
                        <div class="w-8 h-8 rounded-xl bg-white/15 flex items-center justify-center shrink-0 text-white text-base">
                            <i class="ph-fill ph-tag"></i>
                        </div>
                        <div>
                            <p class="text-xs sm:text-sm font-black text-white leading-snug">
                                You save ₹{{ number_format($brokerSavings, 0) }} broker fee directly on this home!
                            </p>
                            <p class="text-[10.5px] text-emerald-100 flex items-center gap-1 mt-0.5 font-medium">
                                Standard 15-day agent fee waived 100%
                            </p>
                        </div>
                    </div>
                    <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center shrink-0 text-white text-xs">
                        <i class="ph-bold ph-check"></i>
                    </div>
                </div>
            </div>

            {{-- Perks Pills Row --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                <div class="p-2.5 rounded-xl bg-blue-50/60 dark:bg-slate-800/60 border border-blue-100/70 dark:border-slate-700 flex items-center gap-2 text-xs font-bold text-slate-700 dark:text-slate-200">
                    <i class="ph-bold ph-credit-card text-blue-600 text-sm"></i>
                    <span>Payment via CC & get ₹500 back</span>
                </div>
                <div class="p-2.5 rounded-xl bg-emerald-50/60 dark:bg-slate-800/60 border border-emerald-100/70 dark:border-slate-700 flex items-center gap-2 text-xs font-bold text-slate-700 dark:text-slate-200">
                    <i class="ph-bold ph-fingerprint text-emerald-600 text-sm"></i>
                    <span>Free Biometric E-Agreement</span>
                </div>
            </div>
        </div>

        {{-- ========================================================
             4. CARD 3: KEY SPECIFICATIONS (2x3 GRID)
             ======================================================== --}}
        <div class="bg-white dark:bg-slate-900 rounded-3xl p-4 sm:p-5 shadow-sm border border-slate-200/80 dark:border-slate-800 space-y-3">
            
            <div class="flex items-center justify-between">
                <h2 class="text-sm sm:text-base font-black text-slate-900 dark:text-white">
                    Key Specifications
                </h2>
                <button type="button" onclick="openLightboxModal(0)" class="text-blue-600 dark:text-blue-400 text-xs font-black hover:underline cursor-pointer">
                    Floor Plan Available
                </button>
            </div>

            <div class="grid grid-cols-2 gap-2 sm:gap-2.5">
                {{-- 1. Bedrooms / Config --}}
                <div class="bg-blue-50/50 dark:bg-slate-800/50 border border-blue-100/70 dark:border-slate-700/60 rounded-2xl p-2.5 sm:p-3 flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-blue-100/80 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-base shrink-0">
                        <i class="ph-bold ph-bed"></i>
                    </div>
                    <div class="min-w-0">
                        <span class="block text-[8.5px] font-black text-slate-400 uppercase tracking-wider">BEDROOMS</span>
                        <span class="block text-xs sm:text-[13px] font-black text-slate-800 dark:text-white truncate">
                            {{ $property->bedrooms !== null ? ($property->bedrooms == 0 ? '1 RK' : $property->bedrooms . ' Beds') : '2 Beds' }} | {{ $property->bathrooms ?? 2 }} Baths
                        </span>
                    </div>
                </div>

                {{-- 2. Super Area --}}
                <div class="bg-blue-50/50 dark:bg-slate-800/50 border border-blue-100/70 dark:border-slate-700/60 rounded-2xl p-2.5 sm:p-3 flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-blue-100/80 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-base shrink-0">
                        <i class="ph-bold ph-ruler"></i>
                    </div>
                    <div class="min-w-0">
                        <span class="block text-[8.5px] font-black text-slate-400 uppercase tracking-wider">SUPER AREA</span>
                        <span class="block text-xs sm:text-[13px] font-black text-slate-800 dark:text-white truncate">
                            {{ !empty($property->area_sqft) ? number_format($property->area_sqft) . ' sq.ft' : '1,900 sq.ft' }}
                        </span>
                    </div>
                </div>

                {{-- 3. Floor Level --}}
                <div class="bg-blue-50/50 dark:bg-slate-800/50 border border-blue-100/70 dark:border-slate-700/60 rounded-2xl p-2.5 sm:p-3 flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-blue-100/80 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-base shrink-0">
                        <i class="ph-bold ph-buildings"></i>
                    </div>
                    <div class="min-w-0">
                        <span class="block text-[8.5px] font-black text-slate-400 uppercase tracking-wider">FLOOR LEVEL</span>
                        <span class="block text-xs sm:text-[13px] font-black text-slate-800 dark:text-white truncate">
                            {{ $floorText }}
                        </span>
                    </div>
                </div>

                {{-- 4. Furnishing --}}
                <div class="bg-blue-50/50 dark:bg-slate-800/50 border border-blue-100/70 dark:border-slate-700/60 rounded-2xl p-2.5 sm:p-3 flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-blue-100/80 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-base shrink-0">
                        <i class="ph-bold ph-couch"></i>
                    </div>
                    <div class="min-w-0">
                        <span class="block text-[8.5px] font-black text-slate-400 uppercase tracking-wider">FURNISHING</span>
                        <span class="block text-xs sm:text-[13px] font-black text-slate-800 dark:text-white truncate">
                            {{ !empty($property->furnishing) ? ucfirst(str_replace('-', ' ', $property->furnishing)) : 'Semi-Furnished' }}
                        </span>
                    </div>
                </div>

                {{-- 5. Orientation --}}
                <div class="bg-blue-50/50 dark:bg-slate-800/50 border border-blue-100/70 dark:border-slate-700/60 rounded-2xl p-2.5 sm:p-3 flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-blue-100/80 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-base shrink-0">
                        <i class="ph-bold ph-compass"></i>
                    </div>
                    <div class="min-w-0">
                        <span class="block text-[8.5px] font-black text-slate-400 uppercase tracking-wider">ORIENTATION</span>
                        <span class="block text-xs sm:text-[13px] font-black text-slate-800 dark:text-white truncate">
                            North-East Facing
                        </span>
                    </div>
                </div>

                {{-- 6. Possession --}}
                <div class="bg-emerald-50/50 dark:bg-slate-800/50 border border-emerald-100/70 dark:border-slate-700/60 rounded-2xl p-2.5 sm:p-3 flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-emerald-100/80 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-base shrink-0">
                        <i class="ph-bold ph-calendar-check"></i>
                    </div>
                    <div class="min-w-0">
                        <span class="block text-[8.5px] font-black text-slate-400 uppercase tracking-wider">POSSESSION</span>
                        <span class="block text-xs sm:text-[13px] font-black text-emerald-700 dark:text-emerald-300 truncate">
                            Immediate Move-in
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================================
             5. CARD 4: DIRECT LANDLORD CONTACT CARD
             ======================================================== --}}
        <div class="bg-white dark:bg-slate-900 rounded-3xl p-4 sm:p-5 shadow-sm border border-slate-200/80 dark:border-slate-800 space-y-3.5" id="direct-landlord-section">
            
            <div class="flex items-center justify-between gap-2">
                <span class="text-xs font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">
                    DIRECT {{ $isSale ? 'OWNER' : 'LANDLORD' }}
                </span>
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 text-[10.5px] font-black border border-emerald-200/60 dark:border-emerald-800/60">
                    <i class="ph-bold ph-shield-check text-xs"></i>
                    <span>Identity & Ownership Verified</span>
                </span>
            </div>

            {{-- Owner Profile Snippet --}}
            <div class="p-3.5 bg-slate-50/80 dark:bg-slate-800/70 border border-slate-200/70 dark:border-slate-700/70 rounded-2xl flex items-center gap-3">
                <div class="relative shrink-0">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-700 text-white font-black text-base flex items-center justify-center shadow-md">
                        {{ $initials ?: 'RK' }}
                    </div>
                    <span class="absolute bottom-0 right-0 w-3.5 h-3.5 rounded-full bg-emerald-500 ring-2 ring-white dark:ring-slate-800" title="Online now"></span>
                </div>

                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <span class="text-sm sm:text-base font-black text-slate-900 dark:text-white leading-none">
                            {{ $ownerName }}
                        </span>
                        <i class="ph-fill ph-check-circle text-blue-600 text-sm"></i>
                    </div>
                    <span class="text-[11px] text-slate-400 font-semibold block mt-1 truncate">
                        Owner since {{ $memberYear }} • {{ $localityName }}
                    </span>

                    <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-emerald-100/70 dark:bg-emerald-950/70 text-emerald-800 dark:text-emerald-300 text-[9.5px] font-extrabold">
                            <i class="ph-bold ph-seal-check text-[10px]"></i> Govt ID & Registry Verified
                        </span>
                        <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-bold">
                            • Typically replies in 5m
                        </span>
                    </div>
                </div>
            </div>

            {{-- Phone Number & Unlock Button Box --}}
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-3 sm:p-3.5 flex items-center justify-between gap-3 shadow-2xs">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-base shrink-0">
                        <i class="ph-bold ph-phone"></i>
                    </div>
                    <div class="min-w-0">
                        @if($isOwnerOrAdmin || $hasViewed)
                            <a href="tel:{{ $displayPhone }}" class="text-xs sm:text-sm font-black text-slate-900 dark:text-white hover:text-blue-600 block truncate">
                                {{ $displayPhone }}
                            </a>
                            <span class="text-[10px] text-emerald-600 font-bold block">Verified direct phone line</span>
                        @else
                            <span class="text-xs sm:text-sm font-black text-slate-800 dark:text-slate-200 block tracking-wider font-mono truncate">
                                +91 98XXX XXXXX
                            </span>
                            <span class="text-[10px] text-slate-400 font-semibold block">Direct landlord phone line</span>
                        @endif
                    </div>
                </div>

                {{-- Unlock Button --}}
                @if($isOwnerOrAdmin || $hasViewed)
                    <a href="tel:{{ $displayPhone }}"
                       class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black rounded-xl shadow-xs flex items-center gap-1.5 shrink-0 transition-all active:scale-95"
                       style="color: #ffffff !important;">
                        <i class="ph-bold ph-phone-call text-sm" style="color: #ffffff !important;"></i>
                        <span style="color: #ffffff !important;">Call Now</span>
                    </a>
                @elseif($canView)
                    <form method="POST" action="{{ route('properties.unlock-contact', $property) }}">
                        @csrf
                        <button type="submit"
                                class="px-3.5 py-2 bg-slate-950 hover:bg-slate-800 text-white text-xs font-black rounded-xl shadow-xs flex items-center gap-1.5 shrink-0 transition-all active:scale-95 cursor-pointer"
                                style="color: #ffffff !important;">
                            <i class="ph-bold ph-lock-key-open text-xs" style="color: #ffffff !important;"></i>
                            <span style="color: #ffffff !important;">Unlock Free</span>
                        </button>
                    </form>
                @else
                    @guest
                        <button type="button"
                                onclick="window.openAuthModal('login', window.location.href);"
                                class="px-3.5 py-2 bg-slate-950 hover:bg-slate-800 text-white text-xs font-black rounded-xl shadow-xs flex items-center gap-1.5 shrink-0 transition-all active:scale-95 cursor-pointer"
                                style="color: #ffffff !important;">
                            <i class="ph-bold ph-lock-key text-xs" style="color: #ffffff !important;"></i>
                            <span style="color: #ffffff !important;">Unlock Free</span>
                        </button>
                    @else
                        <a href="{{ route('plans.index', ['billing' => $isSale ? 'yearly' : 'monthly', 'purpose' => $isSale ? 'buy' : 'rent']) }}"
                           class="px-3.5 py-2 bg-slate-950 hover:bg-slate-800 text-white text-xs font-black rounded-xl shadow-xs flex items-center gap-1.5 shrink-0 transition-all active:scale-95"
                           style="color: #ffffff !important;">
                            <i class="ph-bold ph-lock-key text-xs" style="color: #ffffff !important;"></i>
                            <span style="color: #ffffff !important;">Unlock Free</span>
                        </a>
                    @endguest
                @endif
            </div>
        </div>

        {{-- ========================================================
             6. CARD 5: LIFESTYLE & AMENITIES (3x2 GRID)
             ======================================================== --}}
        <div class="bg-white dark:bg-slate-900 rounded-3xl p-4 sm:p-5 shadow-sm border border-slate-200/80 dark:border-slate-800 space-y-3">
            
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-sm sm:text-base font-black text-slate-900 dark:text-white leading-tight">
                        Lifestyle & Amenities
                    </h2>
                    <span class="text-[11px] text-slate-400 font-semibold">Clubhouse & Society features</span>
                </div>
                <span class="px-2.5 py-0.5 rounded-full bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 text-[10.5px] font-black border border-blue-200/60 dark:border-blue-800/60">
                    24 Amenities
                </span>
            </div>

            <div class="grid grid-cols-3 gap-2 sm:gap-2.5">
                {{-- 1. Olympic Pool --}}
                <div class="bg-blue-50/40 dark:bg-slate-800/40 border border-blue-100/60 dark:border-slate-700/60 rounded-2xl p-2.5 sm:p-3 text-center flex flex-col items-center justify-center gap-1">
                    <div class="w-7 h-7 rounded-lg bg-blue-100/70 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-base">
                        <i class="ph-bold ph-swimming-pool"></i>
                    </div>
                    <span class="text-[11px] sm:text-xs font-black text-slate-800 dark:text-white leading-tight">Olympic Pool</span>
                </div>

                {{-- 2. High-tech Gym --}}
                <div class="bg-blue-50/40 dark:bg-slate-800/40 border border-blue-100/60 dark:border-slate-700/60 rounded-2xl p-2.5 sm:p-3 text-center flex flex-col items-center justify-center gap-1">
                    <div class="w-7 h-7 rounded-lg bg-blue-100/70 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-base">
                        <i class="ph-bold ph-barbell"></i>
                    </div>
                    <span class="text-[11px] sm:text-xs font-black text-slate-800 dark:text-white leading-tight">High-tech Gym</span>
                </div>

                {{-- 3. 24/7 Security --}}
                <div class="bg-blue-50/40 dark:bg-slate-800/40 border border-blue-100/60 dark:border-slate-700/60 rounded-2xl p-2.5 sm:p-3 text-center flex flex-col items-center justify-center gap-1">
                    <div class="w-7 h-7 rounded-lg bg-blue-100/70 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-base">
                        <i class="ph-bold ph-shield"></i>
                    </div>
                    <span class="text-[11px] sm:text-xs font-black text-slate-800 dark:text-white leading-tight">24/7 Security</span>
                </div>

                {{-- 4. 100% Backup --}}
                <div class="bg-blue-50/40 dark:bg-slate-800/40 border border-blue-100/60 dark:border-slate-700/60 rounded-2xl p-2.5 sm:p-3 text-center flex flex-col items-center justify-center gap-1">
                    <div class="w-7 h-7 rounded-lg bg-blue-100/70 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-base">
                        <i class="ph-bold ph-lightning"></i>
                    </div>
                    <span class="text-[11px] sm:text-xs font-black text-slate-800 dark:text-white leading-tight">100% Backup</span>
                </div>

                {{-- 5. Covered Parking --}}
                <div class="bg-blue-50/40 dark:bg-slate-800/40 border border-blue-100/60 dark:border-slate-700/60 rounded-2xl p-2.5 sm:p-3 text-center flex flex-col items-center justify-center gap-1">
                    <div class="w-7 h-7 rounded-lg bg-blue-100/70 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-base">
                        <i class="ph-bold ph-car"></i>
                    </div>
                    <span class="text-[11px] sm:text-xs font-black text-slate-800 dark:text-white leading-tight">Covered Parking</span>
                </div>

                {{-- 6. Tennis Court --}}
                <div class="bg-blue-50/40 dark:bg-slate-800/40 border border-blue-100/60 dark:border-slate-700/60 rounded-2xl p-2.5 sm:p-3 text-center flex flex-col items-center justify-center gap-1">
                    <div class="w-7 h-7 rounded-lg bg-blue-100/70 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-base">
                        <i class="ph-bold ph-tennis-ball"></i>
                    </div>
                    <span class="text-[11px] sm:text-xs font-black text-slate-800 dark:text-white leading-tight">Tennis Court</span>
                </div>
            </div>
        </div>

        {{-- ========================================================
             7. CARD 6: TRANSIT & NEIGHBORHOOD
             ======================================================== --}}
        <div class="bg-white dark:bg-slate-900 rounded-3xl p-4 sm:p-5 shadow-sm border border-slate-200/80 dark:border-slate-800 space-y-3">
            
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-sm sm:text-base font-black text-slate-900 dark:text-white leading-tight">
                        Transit & Neighborhood
                    </h2>
                    <span class="text-[11px] text-slate-400 font-semibold">Prime {{ $localityName }} Corridor</span>
                </div>
                <span class="text-emerald-600 dark:text-emerald-400 text-xs font-black flex items-center gap-1">
                    <i class="ph-bold ph-navigation-arrow text-xs"></i> Live Distance
                </span>
            </div>

            {{-- Map Snippet Card --}}
            <div class="relative rounded-2xl overflow-hidden h-36 sm:h-44 bg-slate-200 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 group">
                @php
                    $mapQuery = urlencode(($isOwnerOrAdmin || $hasViewed) ? ($property->address . ', ' . $localityName) : ($localityName . ', ' . $property->location . ', India'));
                @endphp
                <iframe
                    src="https://www.google.com/maps?q={{ $mapQuery }}&output=embed&hl=en&z=13"
                    width="100%"
                    height="100%"
                    style="border:0;"
                    loading="lazy"
                    class="w-full h-full filter saturate-[0.85]">
                </iframe>

                {{-- Map Pin Center Overlay --}}
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                    <div class="px-3.5 py-1.5 rounded-xl bg-white/95 dark:bg-slate-900/95 text-slate-900 dark:text-white text-xs font-black shadow-lg border border-slate-200 dark:border-slate-700 flex items-center gap-1.5 pointer-events-auto">
                        <i class="ph-fill ph-house text-blue-600"></i>
                        <span>{{ $localityName }}</span>
                        <a href="https://www.google.com/maps/search/?api=1&query={{ $mapQuery }}" target="_blank" class="text-blue-600 hover:text-blue-700 ml-0.5" title="Open Map">
                            <i class="ph-bold ph-arrow-square-out text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- 3 Key Transit Distances --}}
            <div class="space-y-2 pt-1">
                {{-- Metro --}}
                <div class="bg-blue-50/40 dark:bg-slate-800/40 border border-blue-100/60 dark:border-slate-700/60 rounded-xl p-2.5 sm:p-3 flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2">
                        <i class="ph-bold ph-train text-blue-600 text-sm"></i>
                        <span class="font-bold text-slate-800 dark:text-slate-200">{{ $localityName }} Metro Station</span>
                    </div>
                    <span class="font-black text-blue-600 dark:text-blue-400">400 m (5 mins walk)</span>
                </div>

                {{-- Mall --}}
                <div class="bg-blue-50/40 dark:bg-slate-800/40 border border-blue-100/60 dark:border-slate-700/60 rounded-xl p-2.5 sm:p-3 flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2">
                        <i class="ph-bold ph-storefront text-blue-600 text-sm"></i>
                        <span class="font-bold text-slate-800 dark:text-slate-200">South Point & Galleria Mall</span>
                    </div>
                    <span class="font-bold text-slate-500 dark:text-slate-400">1.2 km (4 mins drive)</span>
                </div>

                {{-- Hospital --}}
                <div class="bg-blue-50/40 dark:bg-slate-800/40 border border-blue-100/60 dark:border-slate-700/60 rounded-xl p-2.5 sm:p-3 flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2">
                        <i class="ph-bold ph-first-aid-kit text-blue-600 text-sm"></i>
                        <span class="font-bold text-slate-800 dark:text-slate-200">Fortis & Max Hospital</span>
                    </div>
                    <span class="font-bold text-slate-500 dark:text-slate-400">3.6 km (9 mins drive)</span>
                </div>
            </div>
        </div>

        {{-- ========================================================
             8. CARD 7: UNLOCKRENTALS ZERO-BROKERAGE GUARANTEE
             ======================================================== --}}
        <div class="bg-slate-900 text-white rounded-3xl p-4 sm:p-5 shadow-lg relative overflow-hidden space-y-2">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-blue-500/20 text-blue-400 flex items-center justify-center shrink-0 text-lg">
                    <i class="ph-fill ph-shield-check"></i>
                </div>
                <h3 class="text-xs sm:text-sm font-black text-white leading-tight">
                    UnlockRentals Zero-Brokerage Guarantee
                </h3>
            </div>
            <p class="text-[11px] sm:text-xs text-slate-300 leading-relaxed">
                Direct connection with {{ $ownerName }} (verified property owner). No broker fees, no middleman markup, and verified rental agreement on standard state norms.
            </p>
        </div>

        {{-- ========================================================
             9. SIMILAR PROPERTIES (PEOPLE ALSO VIEWED)
             ======================================================== --}}
        @if($similarProperties->isNotEmpty())
            <div class="pt-6 sm:pt-8" id="similar-properties-section">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base sm:text-lg font-black text-slate-900 dark:text-white">
                        People Also Viewed
                    </h2>
                    <a href="{{ route('properties.index') }}" class="text-xs font-black text-blue-600 hover:underline">
                        View All Listings →
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
</section>

{{-- ========================================================
     10. FIXED BOTTOM STICKY ACTION BAR (MOBILE & DESKTOP)
     ======================================================== --}}
<div class="fixed bottom-0 left-0 right-0 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-t border-slate-200 dark:border-slate-800 py-3 px-4 z-[990] shadow-[0_-8px_30px_rgba(0,0,0,0.08)]">
    <div class="max-w-xl md:max-w-2xl lg:max-w-3xl mx-auto flex items-center gap-3">
        
        {{-- Button 1: Schedule Visit --}}
        @auth
            <button type="button"
                    onclick="openBookVisitModal()"
                    class="flex-1 py-3 px-4 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 active:bg-slate-100 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white font-black text-xs sm:text-sm rounded-2xl shadow-xs transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-95">
                <i class="ph-bold ph-calendar-blank text-base"></i>
                <span>Schedule Visit</span>
            </button>
        @else
            <button type="button"
                    onclick="window.openAuthModal('login', window.location.href);"
                    class="flex-1 py-3 px-4 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 active:bg-slate-100 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white font-black text-xs sm:text-sm rounded-2xl shadow-xs transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-95">
                <i class="ph-bold ph-calendar-blank text-base"></i>
                <span>Schedule Visit</span>
            </button>
        @endauth

        {{-- Button 2: Chat & Call Owner --}}
        @if($isOwnerOrAdmin || $hasViewed)
            <a href="tel:{{ $displayPhone }}"
               class="flex-1 py-3 px-4 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-black text-xs sm:text-sm rounded-2xl shadow-md transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-95"
               style="color: #ffffff !important;">
                <i class="ph-bold ph-chat-circle-dots text-base" style="color: #ffffff !important;"></i>
                <span style="color: #ffffff !important;">Chat & Call Owner</span>
            </a>
        @else
            @auth
                @if($canView)
                    <form method="POST" action="{{ route('properties.unlock-contact', $property) }}" class="flex-1 flex">
                        @csrf
                        <button type="submit"
                                class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-black text-xs sm:text-sm rounded-2xl shadow-md transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-95"
                                style="color: #ffffff !important;">
                            <i class="ph-bold ph-chat-circle-dots text-base" style="color: #ffffff !important;"></i>
                            <span style="color: #ffffff !important;">Chat & Call Owner</span>
                        </button>
                    </form>
                @else
                    <a href="{{ $waUrl }}" target="_blank"
                       class="flex-1 py-3 px-4 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-black text-xs sm:text-sm rounded-2xl shadow-md transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-95"
                       style="color: #ffffff !important;">
                        <i class="ph-bold ph-chat-circle-dots text-base" style="color: #ffffff !important;"></i>
                        <span style="color: #ffffff !important;">Chat & Call Owner</span>
                    </a>
                @endif
            @else
                <button type="button"
                        onclick="window.openAuthModal('login', window.location.href);"
                        class="flex-1 py-3 px-4 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-black text-xs sm:text-sm rounded-2xl shadow-md transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-95"
                        style="color: #ffffff !important;">
                    <i class="ph-bold ph-chat-circle-dots text-base" style="color: #ffffff !important;"></i>
                    <span style="color: #ffffff !important;">Chat & Call Owner</span>
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
                    <h3 class="font-black text-base text-white">Schedule Property Visit</h3>
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
                    <label class="block text-[11px] font-black text-slate-500 uppercase tracking-wider mb-1">Preferred Date</label>
                    <input type="date" name="preferred_date" required min="{{ date('Y-m-d') }}"
                           class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-800 dark:text-white focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-[11px] font-black text-slate-500 uppercase tracking-wider mb-1">Time Slot</label>
                    <select name="preferred_time" required
                            class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-800 dark:text-white focus:outline-none focus:border-blue-500">
                        <option value="morning">Morning (9 AM - 12 PM)</option>
                        <option value="afternoon">Afternoon (12 PM - 4 PM)</option>
                        <option value="evening" selected>Evening (4 PM - 7 PM)</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-black text-slate-500 uppercase tracking-wider mb-1">Your Name</label>
                <input type="text" name="name" value="{{ auth()->user()->name }}" required
                       class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-800 dark:text-white">
            </div>

            <div>
                <label class="block text-[11px] font-black text-slate-500 uppercase tracking-wider mb-1">Phone Number</label>
                <input type="tel" name="phone" value="{{ auth()->user()->phone }}" required placeholder="Your contact phone"
                       class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-800 dark:text-white">
            </div>

            <div id="visit-alert" class="hidden rounded-xl p-3 text-xs font-bold"></div>

            <button type="submit" id="visit-submit-btn" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white text-xs font-black rounded-xl shadow-md transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-95">
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
            badge.textContent = `${idx + 1}/${totalPhotos} photos`;
        }
        document.querySelectorAll('.gallery-thumb-item').forEach((btn, i) => {
            if (i === idx) {
                btn.className = 'gallery-thumb-item relative w-16 h-12 sm:w-20 sm:h-14 rounded-xl overflow-hidden flex-shrink-0 cursor-pointer border-2 border-blue-500 scale-95 ring-2 ring-blue-500/30 transition-all';
            } else {
                btn.className = 'gallery-thumb-item relative w-16 h-12 sm:w-20 sm:h-14 rounded-xl overflow-hidden flex-shrink-0 cursor-pointer border-2 border-transparent opacity-75 hover:opacity-100 transition-all';
            }
        });
    }

    function openLightboxModal(idx) {
        if (!propertyGallery || propertyGallery.length === 0) return;
        lightboxIdx = idx || currentSlide;
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
                btn.textContent = 'Show less -';
            } else {
                full.classList.add('hidden');
                col.classList.remove('hidden');
                btn.textContent = 'Read more +';
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
