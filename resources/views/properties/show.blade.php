@extends('layouts.app')

@php
    $isOwnerOrAdmin = false;
    $hasViewed = false;
    $canView = false;
    $activePlan = null;
    
    if (auth()->check()) {
        $user = auth()->user();
        $isOwnerOrAdmin = $user->id === $property->user_id || $user->isAdmin();
        $hasViewed = $user->hasViewedContact($property);
        $canView = $user->canViewContact($property);
        $activePlan = $user->activePlanForProperty($property);
    }
@endphp

@section('title', $property->title . ' for Rent in ' . $property->location . ' - UnlockRentals')
@section('meta_description', Str::limit('View rent, photos, location, and contact details for ' . $property->title . ' in ' . $property->location . ', ' . $property->state . '. Find verified rental properties on UnlockRentals.', 160))
@section('og_image', $property->primaryImage ? $property->primaryImage->imageUrl() : asset('images/logo.png'))

@push('head')
<script type="application/ld+json">
{
  "@@context": "https://schema.org/",
  "@@type": "RealEstateListing",
  "name": @json($property->title),
  "description": @json(Str::limit(strip_tags($property->description), 150)),
  "url": @json(route('properties.show', $property)),
  "image": @json($property->primaryImage ? $property->primaryImage->imageUrl() : asset('images/logo.png')),
  "address": {
    "@@type": "PostalAddress",
    "streetAddress": @json(($isOwnerOrAdmin || $hasViewed) ? $property->address : 'Exact address locked'),
    "addressLocality": @json($property->location),
    "addressRegion": @json($property->state),
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
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "BreadcrumbList",
    "itemListElement": [
        {
            "@@type": "ListItem",
            "position": 1,
            "name": "Home",
            "item": "{{ route('home') }}"
        },
        {
            "@@type": "ListItem",
            "position": 2,
            "name": "Rental Properties",
            "item": "{{ route('properties.index') }}"
        },
        {
            "@@type": "ListItem",
            "position": 3,
            "name": @json($property->title),
            "item": "{{ route('properties.show', $property) }}"
        }
    ]
}
</script>
@endpush

@push('scripts')
<script>
    function copyPropertyLink() {
        navigator.clipboard.writeText(window.location.href);
        const toast = document.getElementById('share-toast');
        toast.classList.remove('opacity-0', 'translate-y-2');
        toast.classList.add('opacity-100', 'translate-y-0');
        setTimeout(() => {
            toast.classList.remove('opacity-100', 'translate-y-0');
            toast.classList.add('opacity-0', 'translate-y-2');
        }, 2500);
    }
</script>
@endpush

@section('content')

{{-- Toast Alert --}}
<div id="share-toast" class="fixed bottom-24 left-1/2 -translate-x-1/2 bg-zinc-900/95 backdrop-blur-md text-white text-sm font-semibold px-5 py-3 rounded-full shadow-2xl transition-all duration-300 opacity-0 translate-y-2 z-[999] pointer-events-none flex items-center gap-2">
    <i class="ph-bold ph-check-circle text-emerald-400 text-base"></i> Link copied to clipboard!
</div>

<section class="py-6 lg:py-10 bg-[#f8fafc]" id="property-detail">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Top Breadcrumb & Share Actions Row --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-xs sm:text-sm text-zinc-500" id="property-breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-[#2874F0] font-medium transition-colors flex items-center gap-1" title="Home">
                    <i class="ph-bold ph-house-line"></i> Home
                </a>
                <i class="ph-bold ph-caret-right text-[10px] text-zinc-400"></i>
                <a href="{{ route('properties.index') }}" class="hover:text-[#2874F0] font-medium transition-colors" title="Properties">Properties</a>
                <i class="ph-bold ph-caret-right text-[10px] text-zinc-400"></i>
                <span class="text-zinc-700 font-semibold truncate max-w-[200px] sm:max-w-xs">{{ $property->title }}</span>
            </nav>

            {{-- Action Tools --}}
            <div class="flex items-center gap-2.5">
                <button onclick="copyPropertyLink()" class="flex items-center gap-2 px-3.5 py-1.5 bg-white hover:bg-zinc-50 text-zinc-700 hover:text-[#2874F0] text-xs sm:text-sm font-semibold rounded-md border border-zinc-200 shadow-sm transition-all cursor-pointer">
                    <i class="ph-bold ph-share-network"></i> Share
                </button>
                <button class="flex items-center gap-2 px-3.5 py-1.5 bg-white hover:bg-zinc-50 text-zinc-700 hover:text-red-500 text-xs sm:text-sm font-semibold rounded-md border border-zinc-200 shadow-sm transition-all group cursor-pointer">
                    <i class="ph-bold ph-heart group-hover:scale-110 transition-transform"></i> Save
                </button>
                <a href="https://wa.me/?text=Hi! I am interested in this property on UnlockRentals: {{ urlencode(route('properties.show', $property)) }}" 
                   target="_blank" 
                   class="flex items-center gap-2 px-3.5 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 hover:text-emerald-800 text-xs sm:text-sm font-semibold rounded-md border border-emerald-200/60 shadow-sm transition-all cursor-pointer" title="WhatsApp Inquiry">
                    <i class="ph-bold ph-whatsapp text-emerald-500 text-base"></i> WhatsApp Inquiry
                </a>
            </div>
        </div>

        {{-- Main Layout Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            {{-- Left Side: Core Info & Image Gallery (8 Columns) --}}
            <div class="lg:col-span-8 space-y-6">

                {{-- Property Title, Badges & Header Card --}}
                <div class="bg-white rounded-2xl p-6 border border-zinc-200 shadow-sm">
                    <div class="flex flex-wrap items-center gap-2 mb-3">
                        @if(($property->purpose ?? 'rent') === 'buy' || ($property->purpose ?? 'rent') === 'sell')
                            <span class="px-3 py-1 bg-emerald-600 text-white text-xs font-extrabold rounded-md uppercase tracking-wider flex items-center gap-1 shadow-sm">
                                <i class="ph-bold ph-tag"></i> For Sale
                            </span>
                        @else
                            <span class="px-3 py-1 bg-blue-600 text-white text-xs font-extrabold rounded-md uppercase tracking-wider flex items-center gap-1 shadow-sm">
                                <i class="ph-bold ph-key"></i> For Rent
                            </span>
                        @endif
                        <span class="px-3 py-1 bg-[#2874F0]/10 text-[#2874F0] text-xs font-bold rounded-md uppercase tracking-wider">
                            {{ ucfirst($property->type) }}
                        </span>
                        <span class="px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-md flex items-center gap-1 shadow-sm">
                            <i class="ph-bold ph-shield-check"></i> Verified Owner
                        </span>
                        @if($property->is_featured)
                        <span class="px-3 py-1 bg-amber-100 text-amber-900 text-xs font-bold rounded-md flex items-center gap-1 shadow-sm">
                            <i class="ph-bold ph-star text-amber-500"></i> Featured
                        </span>
                        @endif
                        @if($property->created_at && $property->created_at->diffInDays() < 7)
                        <span class="px-3 py-1 bg-orange-100 text-orange-850 text-xs font-bold rounded-md shadow-sm">
                            New Listing
                        </span>
                        @endif
                    </div>

                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-zinc-900 mb-3 tracking-tight leading-tight">
                        {{ $property->title }}
                    </h1>
                    
                    <div class="flex flex-wrap items-center gap-x-5 gap-y-2 text-zinc-650 text-xs sm:text-sm border-t border-zinc-100 pt-4 mt-3">
                        <div class="flex items-center gap-1.5 font-medium">
                            <i class="ph-bold ph-map-pin text-[#2874F0] text-base"></i>
                            <span>
                                @if($isOwnerOrAdmin || $hasViewed)
                                    {{ $property->address }}, {{ $property->location }}
                                @else
                                    <span class="inline-flex items-center gap-1 text-amber-600 bg-amber-50 px-2 py-0.5 rounded text-xs border border-amber-100 font-bold"><i class="ph-bold ph-lock"></i> Exact address locked</span> · {{ $property->location }}
                                @endif
                            </span>
                        </div>
                        @if($property->category)
                        <div class="flex items-center gap-1.5 font-medium border-l border-zinc-200 pl-5">
                            <i class="ph-bold ph-tag text-[#2874F0] text-base"></i>
                            <span>{{ $property->category->name }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- E-commerce style Product Gallery --}}
                {{-- E-commerce style Product Gallery with Auto-Slider & Interactive Cursor Zoom --}}
                <div class="bg-white rounded-2xl p-4 sm:p-5 border border-zinc-200 shadow-sm" id="property-gallery">
                    @if($property->images->count() > 0)
                        {{-- Large Interactive Main Image Slider with Cursor Zoom --}}
                        <div class="relative h-[250px] sm:h-[400px] lg:h-[480px] rounded-xl overflow-hidden mb-4 bg-zinc-50 border border-zinc-100 group shadow-inner" 
                             id="gallery-main" 
                             onmouseenter="pauseGalleryAutoSlide()" 
                             onmouseleave="startGalleryAutoSlide()">
                            
                            {{-- Sliding Carousel Track (Slides Left, New Image from Right) --}}
                            <div id="gallery-slider-track" class="flex h-full w-full" style="display: flex; height: 100%; width: 100%; transition: transform 0.55s cubic-bezier(0.25, 1, 0.5, 1); will-change: transform;">
                                @foreach($property->images as $index => $image)
                                    <div class="h-full w-full flex-shrink-0 flex items-center justify-center bg-zinc-50 overflow-hidden gallery-slide-item" 
                                         style="flex: 0 0 100%; width: 100%; height: 100%; overflow: hidden; position: relative; cursor: zoom-in;"
                                         onmousemove="handleGalleryZoom(event, this)" 
                                         onmouseleave="resetGalleryZoom(this)"
                                         onclick="openLightboxModal({{ $index }})">
                                        <img src="{{ $image->imageUrl() }}"
                                             alt="{{ $property->title }} - Photo {{ $index + 1 }}"
                                             class="gallery-zoom-target w-full h-full object-cover sm:object-contain bg-zinc-50"
                                             style="transition: transform 0.12s ease-out; transform-origin: center center; will-change: transform;"
                                             loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
                                    </div>
                                @endforeach
                            </div>

                            @if($property->is_booked)
                            <div class="absolute inset-0 bg-black/45 backdrop-blur-[1.5px] flex items-center justify-center z-10 pointer-events-none" style="position: absolute; inset: 0; background: rgba(0,0,0,0.45); backdrop-filter: blur(1.5px); display: flex; align-items: center; justify-content: center; z-index: 10;">
                                <div class="px-6 py-2.5 bg-red-600/90 text-white font-extrabold text-sm uppercase tracking-widest rounded shadow-lg border border-red-500/30 transform -rotate-12 flex items-center gap-2" style="background: rgba(220, 38, 38, 0.9); color: #fff; padding: 10px 20px; border-radius: 4px; font-weight: 850; font-size: 13px; text-transform: uppercase; letter-spacing: 2px; transform: rotate(-12deg); box-shadow: 0 10px 25px -5px rgba(220, 38, 38, 0.4); border: 1px solid rgba(239, 68, 68, 0.3); display: flex; align-items: center; gap: 8px;">
                                    <i class="ph-bold ph-lock-key"></i> Booked
                                </div>
                            </div>
                            @endif

                            {{-- Auto-slide progress bar indicator --}}
                            @if($property->images->count() > 1)
                            <div class="absolute bottom-0 left-0 right-0 h-1 bg-black/10 z-10">
                                <div id="gallery-progress-bar" class="h-full bg-[#2874F0] w-0 transition-all duration-100 ease-linear"></div>
                            </div>
                            @endif

                            {{-- Hover Zoom / Lightbox Hint Badge --}}
                            <div class="absolute top-3.5 right-3.5 bg-zinc-900/75 backdrop-blur-md text-white text-[11px] font-semibold px-2.5 py-1 rounded-md shadow-md opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none flex items-center gap-1.5 z-10">
                                <i class="ph-bold ph-magnifying-glass-plus text-xs"></i> Move mouse to zoom · Click for fullscreen
                            </div>

                            {{-- Image Overlay Counter Badge --}}
                            <div class="absolute bottom-4 right-4 bg-zinc-900/80 backdrop-blur-md text-white text-xs font-bold px-3.5 py-1.5 rounded-lg shadow-md flex items-center gap-1 z-10">
                                <i class="ph-bold ph-image text-sm"></i>
                                <span id="gallery-counter">1 of {{ $property->images->count() }} Photos</span>
                            </div>
                        </div>

                        {{-- Thumbnails Row --}}
                        @if($property->images->count() > 1)
                        <div class="grid grid-cols-4 sm:grid-cols-6 lg:grid-cols-8 gap-2.5" id="gallery-thumbs">
                            @foreach($property->images as $index => $image)
                            <button type="button"
                                    onclick="selectGalleryImage({{ $index }})"
                                    data-thumb-index="{{ $index }}"
                                    class="gallery-thumb aspect-square rounded-lg overflow-hidden border-2 {{ $index === 0 ? 'border-[#2874F0] ring-2 ring-[#2874F0]/20' : 'border-zinc-200' }} hover:border-[#2874F0]/60 transition-all focus:outline-none cursor-pointer group shadow-sm">
                                <img src="{{ $image->imageUrl() }}" alt="" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </button>
                            @endforeach
                        </div>
                        @endif
                    @else
                        <div class="h-[250px] sm:h-[380px] bg-gradient-to-br from-[#2874F0]/5 to-[#2874F0]/10 rounded-xl flex flex-col items-center justify-center border border-zinc-200 shadow-inner p-8 text-center">
                            <div class="w-16 h-16 rounded-full bg-white flex items-center justify-center shadow-md mb-3 text-[#2874F0]">
                                <i class="ph-bold ph-image text-3xl"></i>
                            </div>
                            <h3 class="text-zinc-800 font-bold text-base">No property images available</h3>
                            <p class="text-zinc-500 text-xs mt-1">Owner has not uploaded media for this listing yet</p>
                        </div>
                    @endif
                </div>

                {{-- Video Tour Section (if property has video) --}}
                @if($property->hasVideo())
                @php
                    $allVideos = $property->allVideoUrls();
                @endphp
                <div class="bg-white rounded-2xl p-5 sm:p-6 border border-zinc-200 shadow-sm" id="property-video-tour">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 pb-3 border-b border-zinc-100">
                        <div class="flex items-center gap-2.5">
                            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl shadow-xs">
                                <i class="ph-bold ph-video-camera"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-zinc-900 flex items-center gap-2">
                                    Virtual Video Tours
                                    <span class="px-2 py-0.5 bg-purple-100 text-purple-800 text-[10px] font-extrabold uppercase rounded-full">
                                        {{ count($allVideos) > 1 ? count($allVideos) . ' HD Clips' : 'HD Walkthrough' }}
                                    </span>
                                </h3>
                                <p class="text-xs text-zinc-500">Real video walkthrough recorded for this property</p>
                            </div>
                        </div>

                        {{-- Multi-Video Selector Tabs --}}
                        @if(count($allVideos) > 1)
                        <div class="flex flex-wrap gap-2">
                            @foreach($allVideos as $idx => $vUrl)
                                <button type="button" onclick="switchPropertyVideo('{{ $vUrl }}', {{ $idx }}, this)"
                                        class="property-video-tab px-3 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer {{ $idx === 0 ? 'bg-purple-600 text-white shadow-xs' : 'bg-stone-100 text-stone-700 hover:bg-stone-200' }}">
                                    <i class="ph-bold ph-play-circle mr-1"></i> Tour Clip {{ $idx + 1 }}
                                </button>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    <div class="relative rounded-xl overflow-hidden bg-black aspect-video max-h-[460px] shadow-inner" id="video-display-container">
                        @php
                            $firstVideo = $allVideos[0] ?? '';
                            $isYoutube = preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i', $firstVideo, $ytMatches);
                            $isVimeo = preg_match('/vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)/i', $firstVideo, $vmMatches);
                        @endphp

                        @if($isYoutube)
                            <iframe id="main-video-iframe" class="w-full h-full" src="https://www.youtube.com/embed/{{ $ytMatches[1] }}" allowfullscreen frameborder="0"></iframe>
                            <video id="main-video-player" controls playsinline class="w-full h-full object-contain mx-auto hidden"></video>
                        @elseif($isVimeo)
                            <iframe id="main-video-iframe" class="w-full h-full" src="https://player.vimeo.com/video/{{ $vmMatches[3] }}" allowfullscreen frameborder="0"></iframe>
                            <video id="main-video-player" controls playsinline class="w-full h-full object-contain mx-auto hidden"></video>
                        @else
                            <iframe id="main-video-iframe" class="w-full h-full hidden" allowfullscreen frameborder="0"></iframe>
                            <video id="main-video-player" controls playsinline preload="auto" class="w-full h-full object-contain mx-auto" src="{{ $firstVideo }}">
                                <source src="{{ $firstVideo }}" type="video/webm">
                                <source src="{{ $firstVideo }}" type="video/mp4">
                                Your browser does not support HTML5 video playback.
                            </video>
                        @endif
                    </div>

                    {{-- All Clips Playlist Grid --}}
                    @if(count($allVideos) > 1)
                    <div class="mt-4 pt-3.5 border-t border-zinc-100">
                        <p class="text-xs font-bold text-zinc-700 uppercase tracking-wider mb-2.5 flex items-center gap-1.5">
                            <i class="ph-bold ph-film-strip text-purple-600"></i> All Tour Clips ({{ count($allVideos) }})
                        </p>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2.5">
                            @foreach($allVideos as $idx => $vUrl)
                                <button type="button" onclick="switchPropertyVideo('{{ $vUrl }}', {{ $idx }}, this)"
                                        class="property-video-tab group text-left p-2.5 rounded-xl border transition-all cursor-pointer flex items-center gap-2.5 {{ $idx === 0 ? 'bg-purple-50 border-purple-500 text-purple-900 ring-2 ring-purple-500/20' : 'bg-zinc-50 hover:bg-purple-50/50 border-zinc-200 hover:border-purple-300 text-zinc-700' }}">
                                    <div class="w-8 h-8 rounded-lg bg-purple-600 text-white flex items-center justify-center flex-shrink-0 text-sm shadow-xs group-hover:scale-105 transition-transform">
                                        <i class="ph-bold ph-play"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="text-xs font-bold block truncate">Clip {{ $idx + 1 }}</span>
                                        <span class="text-[10px] text-zinc-400 font-medium block truncate">Click to play</span>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <script>
                function switchPropertyVideo(url, index, btn) {
                    document.querySelectorAll('.property-video-tab').forEach(b => {
                        b.classList.remove('bg-purple-50', 'border-purple-500', 'text-purple-900', 'ring-2', 'ring-purple-500/20', 'bg-purple-600', 'text-white');
                        b.classList.add('bg-zinc-50', 'border-zinc-200', 'text-zinc-700');
                    });
                    if (btn) {
                        btn.classList.remove('bg-zinc-50', 'border-zinc-200', 'text-zinc-700');
                        btn.classList.add('bg-purple-50', 'border-purple-500', 'text-purple-900', 'ring-2', 'ring-purple-500/20');
                    }

                    const player = document.getElementById('main-video-player');
                    const iframe = document.getElementById('main-video-iframe');

                    const ytMatch = url.match(/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i);
                    const vmMatch = url.match(/vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)/i);

                    if (ytMatch && iframe && player) {
                        player.pause();
                        player.classList.add('hidden');
                        iframe.src = 'https://www.youtube.com/embed/' + ytMatch[1] + '?autoplay=1';
                        iframe.classList.remove('hidden');
                    } else if (vmMatch && iframe && player) {
                        player.pause();
                        player.classList.add('hidden');
                        iframe.src = 'https://player.vimeo.com/video/' + vmMatch[3] + '?autoplay=1';
                        iframe.classList.remove('hidden');
                    } else if (player && iframe) {
                        iframe.src = '';
                        iframe.classList.add('hidden');
                        player.src = url;
                        player.load();
                        player.classList.remove('hidden');
                        player.play().catch(e => console.warn('Autoplay prevented:', e));
                    }
                }
                </script>
                @endif

                {{-- Premium Quick Info Cards Grid --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3.5" id="property-features">
                    @if($property->bedrooms)
                    <div class="p-4 bg-white border border-zinc-200 rounded-xl shadow-sm hover:border-[#2874F0]/50 hover:shadow-md transition-all duration-300 text-center flex flex-col items-center group">
                        <div class="w-11 h-11 rounded-full bg-[#2874F0]/8 text-[#2874F0] flex items-center justify-center mb-2.5 group-hover:bg-[#2874F0] group-hover:text-white transition-colors duration-300">
                            <i class="ph-bold ph-bed text-xl"></i>
                        </div>
                        <p class="text-base font-extrabold text-zinc-900 leading-none mb-1">{{ $property->bedrooms }} BHK</p>
                        <p class="text-[11px] font-semibold text-zinc-550 uppercase tracking-wider">Bedrooms</p>
                    </div>
                    @endif
                    
                    @if($property->bathrooms)
                    <div class="p-4 bg-white border border-zinc-200 rounded-xl shadow-sm hover:border-[#2874F0]/50 hover:shadow-md transition-all duration-300 text-center flex flex-col items-center group">
                        <div class="w-11 h-11 rounded-full bg-[#2874F0]/8 text-[#2874F0] flex items-center justify-center mb-2.5 group-hover:bg-[#2874F0] group-hover:text-white transition-colors duration-300">
                            <i class="ph-bold ph-drop text-xl"></i>
                        </div>
                        <p class="text-base font-extrabold text-zinc-900 leading-none mb-1">{{ $property->bathrooms }} Baths</p>
                        <p class="text-[11px] font-semibold text-zinc-550 uppercase tracking-wider">Bathrooms</p>
                    </div>
                    @endif

                    @if($property->area_sqft)
                    <div class="p-4 bg-white border border-zinc-200 rounded-xl shadow-sm hover:border-[#2874F0]/50 hover:shadow-md transition-all duration-300 text-center flex flex-col items-center group">
                        <div class="w-11 h-11 rounded-full bg-[#2874F0]/8 text-[#2874F0] flex items-center justify-center mb-2.5 group-hover:bg-[#2874F0] group-hover:text-white transition-colors duration-300">
                            <i class="ph-bold ph-ruler-square text-xl"></i>
                        </div>
                        <p class="text-base font-extrabold text-zinc-900 leading-none mb-1">{{ number_format($property->area_sqft) }}</p>
                        <p class="text-[11px] font-semibold text-zinc-550 uppercase tracking-wider">Sq. Ft. Area</p>
                    </div>
                    @endif

                    <div class="p-4 bg-white border border-zinc-200 rounded-xl shadow-sm hover:border-[#2874F0]/50 hover:shadow-md transition-all duration-300 text-center flex flex-col items-center group">
                        <div class="w-11 h-11 rounded-full bg-[#2874F0]/8 text-[#2874F0] flex items-center justify-center mb-2.5 group-hover:bg-[#2874F0] group-hover:text-white transition-colors duration-300">
                            <i class="ph-bold ph-couch text-xl"></i>
                        </div>
                        <p class="text-base font-extrabold text-zinc-900 leading-none truncate max-w-full mb-1">
                            {{ ucfirst(str_replace('-', ' ', $property->furnishing)) }}
                        </p>
                        <p class="text-[11px] font-semibold text-zinc-550 uppercase tracking-wider">Furnishing</p>
                    </div>

                    {{-- Dynamic Floor Display --}}
                    <div class="p-4 bg-white border border-zinc-200 rounded-xl shadow-sm hover:border-[#2874F0]/50 hover:shadow-md transition-all duration-300 text-center flex flex-col items-center group">
                        <div class="w-11 h-11 rounded-full bg-[#2874F0]/8 text-[#2874F0] flex items-center justify-center mb-2.5 group-hover:bg-[#2874F0] group-hover:text-white transition-colors duration-300">
                            <i class="ph-bold ph-stairs text-xl"></i>
                        </div>
                        <p class="text-base font-extrabold text-zinc-900 leading-none mb-1">
                            @if($property->type === 'shop')
                                Ground Floor
                            @else
                                {{ ($property->id % 4) + 1 }}nd Floor
                            @endif
                        </p>
                        <p class="text-[11px] font-semibold text-zinc-550 uppercase tracking-wider">Floor Level</p>
                    </div>
                </div>

                {{-- Highlights Section --}}
                <div class="bg-white rounded-2xl p-6 border border-zinc-200 shadow-sm">
                    <h3 class="text-lg font-extrabold text-zinc-900 mb-4 flex items-center gap-2 border-b border-zinc-150 pb-3">
                        <i class="ph-bold ph-sparkles text-[#2874F0]"></i> Property Highlights
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3.5">
                        <div class="flex items-center gap-2.5 text-zinc-700 text-sm font-medium">
                            <i class="ph-bold ph-check-circle text-emerald-500 text-base"></i> Gated Security Community
                        </div>
                        <div class="flex items-center gap-2.5 text-zinc-700 text-sm font-medium">
                            <i class="ph-bold ph-check-circle text-emerald-500 text-base"></i> Dedicated Car Parking
                        </div>
                        <div class="flex items-center gap-2.5 text-zinc-700 text-sm font-medium">
                            <i class="ph-bold ph-check-circle text-emerald-500 text-base"></i> Power Backup Available
                        </div>
                        <div class="flex items-center gap-2.5 text-zinc-700 text-sm font-medium">
                            <i class="ph-bold ph-check-circle text-emerald-500 text-base"></i> 24x7 Clean Water Supply
                        </div>
                        <div class="flex items-center gap-2.5 text-zinc-700 text-sm font-medium">
                            <i class="ph-bold ph-check-circle text-emerald-500 text-base"></i> Elevator / Lift Access
                        </div>
                        <div class="flex items-center gap-2.5 text-zinc-700 text-sm font-medium">
                            <i class="ph-bold ph-check-circle text-emerald-500 text-base"></i> Close to Metro Station
                        </div>
                    </div>
                </div>

                {{-- Professional About Property --}}
                <div class="bg-white rounded-2xl p-6 border border-zinc-200 shadow-sm" id="property-description">
                    <h2 class="text-lg font-extrabold text-zinc-900 mb-4 flex items-center gap-2 border-b border-zinc-150 pb-3">
                        <i class="ph-bold ph-article text-[#2874F0]"></i> About this Property
                    </h2>
                    <div class="text-zinc-650 leading-relaxed font-normal text-sm sm:text-base space-y-4 whitespace-pre-line">
                        {!! nl2br(e($property->description)) !!}
                    </div>
                </div>

                {{-- Nearby Places --}}
                <div class="bg-white rounded-2xl p-6 border border-zinc-200 shadow-sm">
                    <h3 class="text-lg font-extrabold text-zinc-900 mb-4 flex items-center gap-2 border-b border-zinc-150 pb-3">
                        <i class="ph-bold ph-map-trifold text-[#2874F0]"></i> Connectivity & Locality
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="p-3 bg-zinc-50 border border-zinc-200/70 rounded-xl flex items-start gap-3">
                            <i class="ph-bold ph-train text-[#2874F0] text-xl mt-0.5"></i>
                            <div>
                                <h4 class="text-xs font-bold text-zinc-900">Metro Station</h4>
                                <p class="text-[11px] text-zinc-500 mt-0.5">0.6 km · 5 mins walk</p>
                            </div>
                        </div>
                        <div class="p-3 bg-zinc-50 border border-zinc-200/70 rounded-xl flex items-start gap-3">
                            <i class="ph-bold ph-first-aid-kit text-[#2874F0] text-xl mt-0.5"></i>
                            <div>
                                <h4 class="text-xs font-bold text-zinc-900">General Hospital</h4>
                                <p class="text-[11px] text-zinc-500 mt-0.5">1.2 km · 7 mins drive</p>
                            </div>
                        </div>
                        <div class="p-3 bg-zinc-50 border border-zinc-200/70 rounded-xl flex items-start gap-3">
                            <i class="ph-bold ph-graduation-cap text-[#2874F0] text-xl mt-0.5"></i>
                            <div>
                                <h4 class="text-xs font-bold text-zinc-900">Elite Prep School</h4>
                                <p class="text-[11px] text-zinc-500 mt-0.5">1.8 km · 10 mins drive</p>
                            </div>
                        </div>
                        <div class="p-3 bg-zinc-50 border border-zinc-200/70 rounded-xl flex items-start gap-3">
                            <i class="ph-bold ph-storefront text-[#2874F0] text-xl mt-0.5"></i>
                            <div>
                                <h4 class="text-xs font-bold text-zinc-900">Shopping Mall</h4>
                                <p class="text-[11px] text-zinc-500 mt-0.5">0.9 km · 8 mins walk</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Google Map Section --}}
                <div class="bg-white rounded-2xl p-6 border border-zinc-200 shadow-sm">
                    <h3 class="text-lg font-extrabold text-zinc-900 mb-4 flex items-center gap-2 border-b border-zinc-150 pb-3">
                        <i class="ph-bold ph-compass text-[#2874F0]"></i> Map Location
                    </h3>
                    <div class="relative rounded-xl overflow-hidden h-[240px] sm:h-[300px] bg-zinc-100 border border-zinc-200 shadow-inner group">
                        @if($isOwnerOrAdmin || $hasViewed)
                            {{-- UNLOCKED: Show real Google Maps embed with exact address --}}
                            @php
                                $mapQuery = urlencode($property->address . ', ' . $property->location . ', ' . ($property->state ?? '') . ', India');
                            @endphp
                            <iframe
                                src="https://www.google.com/maps?q={{ $mapQuery }}&output=embed&hl=en"
                                width="100%"
                                height="100%"
                                style="border:0; min-height:100%;"
                                allowfullscreen=""
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                                class="w-full h-full">
                            </iframe>
                            {{-- Unlocked Address Tooltip --}}
                            <div class="absolute bottom-4 left-4 right-4 z-10">
                                <div class="bg-white/95 backdrop-blur-md border border-zinc-200/80 rounded-xl p-3 shadow-xl text-xs font-semibold max-w-sm">
                                    <p class="font-bold text-[#2874F0] mb-0.5 truncate">{{ $property->title }}</p>
                                    <p class="text-zinc-500 font-medium leading-tight text-[11px]">{{ $property->address }}, {{ $property->location }}</p>
                                </div>
                            </div>
                            <div class="absolute top-4 right-4 z-10">
                                <span class="bg-emerald-500/90 text-white font-bold text-[10px] uppercase tracking-wider px-3 py-1 rounded-full shadow-md flex items-center gap-1"><i class="ph-bold ph-map-pin"></i> Exact Location</span>
                            </div>
                        @else
                            {{-- LOCKED: Show blurred city-level map with lock overlay --}}
                            @php
                                $lockedMapQuery = urlencode($property->location . ', ' . ($property->state ?? '') . ', India');
                            @endphp
                            <iframe
                                src="https://www.google.com/maps?q={{ $lockedMapQuery }}&output=embed&hl=en&z=11"
                                width="100%"
                                height="100%"
                                style="border:0; min-height:100%; filter: blur(6px); pointer-events: none;"
                                allowfullscreen=""
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                                class="w-full h-full">
                            </iframe>
                            {{-- Lock Overlay --}}
                            <div class="absolute inset-0 z-10 flex items-center justify-center" style="background: rgba(255,255,255,0.55); backdrop-filter: blur(2px);">
                                <div class="text-center px-6 py-5 max-w-xs">
                                    <div class="w-14 h-14 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center mx-auto mb-3 shadow-lg border-2 border-amber-200">
                                        <i class="ph-bold ph-lock-key text-2xl"></i>
                                    </div>
                                    <p class="text-sm font-extrabold text-zinc-800 mb-1">Exact Location Locked</p>
                                    <p class="text-[11px] text-zinc-500 mb-4 leading-normal font-medium">Subscribe to a plan to see the exact property address on the map.</p>
                                    <a href="{{ route('plans.index', ['billing' => $isSale ? 'yearly' : 'monthly', 'purpose' => $isSale ? 'buy' : 'rent']) }}#billing-toggle" class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-gradient-to-r from-amber-500 to-[#c9a050] hover:brightness-105 text-white text-xs font-extrabold rounded-lg shadow-md transition-all" title="Unlock Location">
                                        <i class="ph-bold ph-crown"></i> Unlock Location
                                    </a>
                                </div>
                            </div>
                            {{-- Locked Badge --}}
                            <div class="absolute top-4 right-4 z-20">
                                <span class="bg-amber-500/90 text-white font-bold text-[10px] uppercase tracking-wider px-3 py-1 rounded-full shadow-md flex items-center gap-1"><i class="ph-bold ph-lock"></i> Location Locked</span>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            {{-- Right Side: Sticky Pricing, Contact Card, Inquiry (4 Columns) --}}
            <div class="lg:col-span-4">
                <div class="lg:sticky lg:top-24 space-y-6">

                    {{-- Premium Pricing & Core Financials --}}
                    <div class="bg-white border border-zinc-200 rounded-2xl p-6 shadow-sm flex flex-col relative overflow-hidden group">
                        {{-- Flipkart-style blue subtle stripe --}}
                        <div class="absolute top-0 left-0 w-full h-[5px] bg-[#2874F0]"></div>

                        <div class="mb-4">
                            <p class="text-zinc-500 text-xs font-bold uppercase tracking-wider mb-1">Rental Pricing</p>
                            <div class="flex items-baseline gap-1">
                                <span class="text-3xl sm:text-4xl font-black text-zinc-900 tracking-tight">
                                    ₹{{ number_format($property->price, 0) }}
                                </span>
                                <span class="text-zinc-550 text-sm font-bold">/ {{ $property->price_period }}</span>
                            </div>
                        </div>

                        {{-- Additional Financial breakdowns --}}
                        <div class="border-y border-zinc-100 py-3.5 mb-5 space-y-2.5 text-xs text-zinc-650 font-medium">
                            <div class="flex items-center justify-between">
                                <span>Security Deposit:</span>
                                <span class="font-extrabold text-zinc-800">₹{{ number_format($property->price * 2, 0) }} (Refundable)</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Brokerage Fee:</span>
                                <span class="font-extrabold text-emerald-600 flex items-center gap-0.5"><i class="ph-bold ph-check"></i> ₹0 (Zero Brokerage)</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>EMI Starting:</span>
                                <span class="font-semibold text-zinc-700">₹{{ number_format($property->price * 0.15, 0) }}/mo</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            @auth
                            <button onclick="openCallAgentModal()" class="px-4 py-3 bg-[#2874F0]/10 hover:bg-[#2874F0]/20 text-[#2874F0] text-sm font-bold rounded-xl transition-all flex items-center justify-center gap-1.5 cursor-pointer shadow-sm" id="call-agent-btn">
                                <i class="ph-bold ph-phone-call"></i> Call Agent
                            </button>
                            @if(auth()->user()->hasActivePlanForProperty($property))
                            <button onclick="openBookVisitModal()" class="px-4 py-3 bg-[#2874F0] hover:bg-[#1A5FDF] text-white text-sm font-bold rounded-xl shadow-md shadow-[#2874F0]/20 transition-all flex items-center justify-center gap-1.5 cursor-pointer" id="book-visit-btn">
                                <i class="ph-bold ph-calendar-blank"></i> Book Visit
                            </button>
                            @else
                            <a href="{{ route('plans.index', ['billing' => $property->isForSale() ? 'yearly' : 'monthly', 'purpose' => $property->isForSale() ? 'buy' : 'rent']) }}#billing-toggle" class="px-4 py-3 bg-[#2874F0] hover:bg-[#1A5FDF] text-white text-sm font-bold rounded-xl shadow-md shadow-[#2874F0]/20 transition-all flex items-center justify-center gap-1.5 cursor-pointer shadow-sm" id="book-visit-btn" title="Book Visit">
                                <i class="ph-bold ph-calendar-blank"></i> Book Visit
                            </a>
                            @endif
                            @else
                            <a href="tel:{{ \App\Models\Setting::get('agent_phone', '+91 7974164274') }}" class="px-4 py-3 bg-[#2874F0]/10 hover:bg-[#2874F0]/20 text-[#2874F0] text-sm font-bold rounded-xl transition-all flex items-center justify-center gap-1.5 cursor-pointer shadow-sm" title="Call Agent">
                                <i class="ph-bold ph-phone-call"></i> Call Agent
                            </a>
                            <a href="{{ route('login') }}" class="px-4 py-3 bg-[#2874F0] hover:bg-[#1A5FDF] text-white text-sm font-bold rounded-xl shadow-md shadow-[#2874F0]/20 transition-all flex items-center justify-center gap-1.5 cursor-pointer" title="Book Visit">
                                <i class="ph-bold ph-calendar-blank"></i> Book Visit
                            </a>
                            @endauth
                        </div>
                    </div>

                    {{-- Sticky Contact Card (Gated by Plan) --}}
                    <div class="bg-white border border-zinc-200 rounded-2xl p-6 shadow-sm relative overflow-hidden" id="property-price-card">
                        <div class="absolute top-0 left-0 w-full h-[5px] bg-amber-500"></div>
                        <h3 class="text-zinc-900 font-extrabold text-base mb-4 flex items-center gap-2">
                            <i class="ph-bold ph-user-circle text-amber-500 text-lg"></i> Owner Contact Info
                        </h3>

                        @if($property->owner)
                        <div class="p-4 bg-zinc-50 border border-zinc-200/80 rounded-xl mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-11 h-11 bg-gradient-to-tr from-[#2874F0] to-[#1e40af] rounded-full flex items-center justify-center text-white text-base font-extrabold shadow-md">
                                    {{ strtoupper(substr($property->owner->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-extrabold text-zinc-900 truncate leading-snug">{{ $property->owner->name }}</p>
                                    <p class="text-xs text-zinc-500 font-semibold uppercase tracking-wider mt-0.5">Property Owner</p>
                                </div>
                            </div>

                            @if(auth()->check())
                                @php
                                    $canView = auth()->user()->canViewContact($property);
                                    $hasViewed = auth()->user()->hasViewedContact($property);
                                    $isOwnerOrAdmin = auth()->id() === $property->user_id || auth()->user()->isAdmin();
                                    $activePlan = auth()->user()->activePlanForProperty($property);
                                    $isSale = $property->isForSale();
                                    $hasRentPlan = auth()->user()->hasActiveRentPlan();
                                    $hasBuyPlan = auth()->user()->hasActiveBuyPlan();
                                @endphp

                                @if($isOwnerOrAdmin || $hasViewed)
                                    {{-- Show full contact details --}}
                                    <div class="space-y-3 pt-4 border-t border-zinc-200 mt-4">
                                        <div class="flex items-center gap-2.5 text-sm font-semibold text-zinc-700 bg-white p-2.5 rounded-lg border border-zinc-150 shadow-inner">
                                            @php
                                                $displayPhone = !empty($property->contact_phone) ? $property->contact_phone : (!empty($property->owner->phone) ? $property->owner->phone : 'Not provided');
                                            @endphp
                                            <i class="ph-bold ph-phone text-[#2874F0] text-base"></i>
                                            <a href="tel:{{ $displayPhone !== 'Not provided' ? $displayPhone : '' }}" class="text-zinc-800 hover:text-[#2874F0] truncate flex-1" title="Call Support">
                                                {{ $displayPhone }}
                                                @if(auth()->check() && (auth()->user()->isAdmin() || auth()->id() === $property->user_id))
                                                    <span class="text-[10px] text-zinc-400 font-normal ml-1">(Property Phone: "{{ $property->contact_phone ?? 'NULL' }}", Owner Phone: "{{ $property->owner->phone ?? 'NULL' }}")</span>
                                                @endif
                                            </a>
                                        </div>
                                        <div class="flex items-center gap-2.5 text-sm font-semibold text-zinc-700 bg-white p-2.5 rounded-lg border border-zinc-150 shadow-inner">
                                            <i class="ph-bold ph-envelope text-[#2874F0] text-base"></i>
                                            <a href="mailto:{{ $property->owner->email }}" class="text-zinc-800 hover:text-[#2874F0] truncate flex-1" title="Email Support">
                                                {{ $property->owner->email }}
                                            </a>
                                        </div>
                                        @if($hasViewed && !$isOwnerOrAdmin)
                                        <div class="text-[11px] text-emerald-600 font-bold mt-2.5 flex items-center justify-center gap-1 bg-emerald-50 py-1.5 px-3 rounded-md border border-emerald-200">
                                            <i class="ph-bold ph-check-circle text-base"></i> Contact unlocked
                                            @if($activePlan)
                                                · {{ $activePlan->remaining_contacts }} views remaining
                                            @endif
                                        </div>
                                        @endif
                                    </div>
                                @elseif($canView)
                                    {{-- Has matching plan with remaining contacts — show unlock button --}}
                                    <div class="pt-4 border-t border-zinc-200 mt-4">
                                        <div class="text-center mb-3 bg-amber-50/50 p-3 rounded-lg border border-amber-100">
                                            <p class="text-xs font-bold text-zinc-700 mb-0.5">Contact details are locked</p>
                                            <p class="text-[11px] text-zinc-500 font-medium">{{ $activePlan->remaining_contacts }} contact views remaining in your active {{ $activePlan->plan->name ?? ($isSale ? 'Buyer Pass' : 'Rental Plan') }}</p>
                                        </div>
                                        <form method="POST" action="{{ route('properties.unlock-contact', $property) }}">
                                            @csrf
                                            <button type="submit" class="w-full px-4 py-3 bg-[#c9a050] hover:bg-[#b08d42] text-white text-sm font-bold rounded-xl transition-all shadow-md shadow-[#c9a050]/20 flex items-center justify-center gap-1.5 cursor-pointer">
                                                <i class="ph-bold ph-lock-key-open"></i> Unlock {{ $isSale ? 'Seller' : 'Owner' }} Contact
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    {{-- Cannot view: determine precise reason --}}
                                    <div class="pt-4 border-t border-zinc-200 mt-4">
                                        <div class="text-center p-4 bg-amber-50/50 border border-amber-200 rounded-xl">
                                            @if($isSale && $hasRentPlan && !$hasBuyPlan)
                                                {{-- User has Rent plan, but this is a Seller (Sale) post --}}
                                                <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center mx-auto mb-2 shadow-inner">
                                                    <i class="ph-bold ph-buildings text-xl"></i>
                                                </div>
                                                <p class="text-sm font-extrabold text-zinc-800 mb-0.5">Buyer Pass Required</p>
                                                <p class="text-[11px] text-zinc-500 mb-4 leading-normal font-medium">You currently hold an active Rental Plan. Rental plans only unlock rent posts. To view verified seller contact details for this property, please purchase a Buyer Pass.</p>
                                                <a href="{{ route('plans.index', ['billing' => 'yearly', 'purpose' => 'buy']) }}#billing-toggle" class="inline-flex items-center gap-1.5 px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:brightness-105 text-white text-xs font-extrabold rounded-lg shadow-md transition-all" title="View Buyer Passes">
                                                    <i class="ph-bold ph-buildings"></i> View Buyer Passes
                                                </a>
                                            @elseif(!$isSale && $hasBuyPlan && !$hasRentPlan)
                                                {{-- User has Buyer pass, but this is a Rent post --}}
                                                <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center mx-auto mb-2 shadow-inner">
                                                    <i class="ph-bold ph-house text-xl"></i>
                                                </div>
                                                <p class="text-sm font-extrabold text-zinc-800 mb-0.5">Rental Plan Required</p>
                                                <p class="text-[11px] text-zinc-500 mb-4 leading-normal font-medium">You currently hold an active Buyer Pass. Buyer passes only unlock sale posts. To view verified owner contact details for this rental listing, please purchase a Rental Plan.</p>
                                                <a href="{{ route('plans.index', ['billing' => 'monthly', 'purpose' => 'rent']) }}#billing-toggle" class="inline-flex items-center gap-1.5 px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:brightness-105 text-white text-xs font-extrabold rounded-lg shadow-md transition-all" title="View Rental Plans">
                                                    <i class="ph-bold ph-house"></i> View Rental Plans
                                                </a>
                                            @elseif($activePlan && $activePlan->remaining_contacts <= 0)
                                                {{-- Contact views exhausted on matching plan --}}
                                                <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center mx-auto mb-2 shadow-inner">
                                                    <i class="ph-bold ph-lock-key text-xl"></i>
                                                </div>
                                                <p class="text-sm font-extrabold text-zinc-800 mb-0.5">Contact limit reached</p>
                                                <p class="text-[11px] text-zinc-500 mb-4 leading-normal font-medium">You have used all contact views in your active {{ $activePlan->plan->name ?? ($isSale ? 'Buyer Pass' : 'Rental Plan') }}. Please upgrade your plan to unlock more contact details.</p>
                                                <a href="{{ route('plans.index', ['billing' => $isSale ? 'yearly' : 'monthly', 'purpose' => $isSale ? 'buy' : 'rent']) }}#billing-toggle" class="inline-flex items-center gap-1.5 px-6 py-2.5 bg-gradient-to-r from-amber-500 to-[#c9a050] hover:brightness-105 text-white text-xs font-extrabold rounded-lg shadow-md transition-all" title="Upgrade Plan">
                                                    <i class="ph-bold ph-crown"></i> Upgrade Plan
                                                </a>
                                            @else
                                                {{-- No active plan --}}
                                                <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center mx-auto mb-2 shadow-inner">
                                                    <i class="ph-bold ph-lock-key text-xl"></i>
                                                </div>
                                                <p class="text-sm font-extrabold text-zinc-800 mb-0.5">Premium details locked</p>
                                                <p class="text-[11px] text-zinc-500 mb-4 leading-normal font-medium">Get a {{ $isSale ? 'Buyer Pass' : 'Rental Plan' }} to access the verified {{ $isSale ? 'seller\'s' : 'owner\'s' }} phone & email details.</p>
                                                <a href="{{ route('plans.index', ['billing' => $isSale ? 'yearly' : 'monthly', 'purpose' => $isSale ? 'buy' : 'rent']) }}#billing-toggle" class="inline-flex items-center gap-1.5 px-6 py-2.5 bg-gradient-to-r from-amber-500 to-[#c9a050] hover:brightness-105 text-white text-xs font-extrabold rounded-lg shadow-md transition-all" title="View Unlock Plans">
                                                    <i class="ph-bold ph-crown"></i> View {{ $isSale ? 'Buyer Passes' : 'Rental Plans' }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @else
                                {{-- Guest — prompt to login --}}
                                <div class="pt-4 border-t border-zinc-200 mt-4">
                                    <div class="text-center p-4 bg-zinc-50 rounded-xl border border-zinc-200/60">
                                        <div class="w-10 h-10 rounded-full bg-zinc-200 text-zinc-500 flex items-center justify-center mx-auto mb-2 shadow-inner">
                                            <i class="ph-bold ph-user-lock text-xl"></i>
                                        </div>
                                        <p class="text-xs font-bold text-zinc-650 mb-3 leading-normal">Sign in with your verified profile to view full owner contact details.</p>
                                        <a href="{{ route('login') }}" class="inline-flex items-center gap-1 px-6 py-2.5 bg-[#2874F0] hover:bg-[#1A5FDF] text-white text-xs font-extrabold rounded-lg shadow-md transition-all" title="Sign In to View">
                                            Sign In to View
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                        @endif

                        {{-- Edit/Delete for owner/admin --}}
                        @if(auth()->check())
                            @if(auth()->id() === $property->user_id || auth()->user()->isAdmin())
                            <div class="flex gap-2.5 pt-3 border-t border-zinc-150 mt-3">
                                <a href="{{ route('properties.edit', $property) }}" class="flex-1 text-center px-4 py-2.5 bg-zinc-50 hover:bg-zinc-100 border border-zinc-200 text-zinc-700 text-xs font-bold rounded-xl transition-all shadow-sm" title="Edit Page">
                                    <i class="ph-bold ph-pencil-simple"></i> Edit Page
                                </a>
                                <form method="POST" action="{{ route('properties.destroy', $property) }}" class="flex-1" onsubmit="return confirm('Are you sure you want to delete this property?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full px-4 py-2.5 bg-red-50 hover:bg-red-100 border border-red-200 text-red-700 text-xs font-bold rounded-xl transition-all shadow-sm cursor-pointer">
                                        <i class="ph-bold ph-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                            @endif
                        @endif
                    </div>

                    {{-- Elegant Inquiry Form Card --}}
                    <div class="bg-white border border-zinc-200 rounded-2xl p-6 shadow-sm relative overflow-hidden" id="inquiry-form-card">
                        <div class="absolute top-0 left-0 w-full h-[5px] bg-[#2874F0]"></div>
                        <h3 class="text-zinc-900 font-extrabold text-base mb-4 flex items-center gap-2">
                            <i class="ph-bold ph-envelope-simple-open text-[#2874F0] text-lg"></i>
                            Send Inquiry
                        </h3>

                        @if(auth()->check())
                        <form method="POST" action="{{ route('inquiries.store') }}" id="inquiry-form">
                            @csrf
                            <input type="hidden" name="property_id" value="{{ $property->id }}">

                            <div class="space-y-3.5">
                                <div class="relative">
                                    <i class="ph-bold ph-user absolute left-4 top-1/2 -translate-y-1/2 text-zinc-400 text-base"></i>
                                    <input type="text" name="name" value="{{ auth()->user()->name }}"
                                           class="w-full pl-11 pr-4 py-3 bg-zinc-50 border border-zinc-200 rounded-xl text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-[#2874F0]/15 focus:border-[#2874F0] transition-all font-medium"
                                           placeholder="Your Full Name" required id="inquiry-name">
                                </div>
                                <div class="relative">
                                    <i class="ph-bold ph-envelope absolute left-4 top-1/2 -translate-y-1/2 text-zinc-400 text-base"></i>
                                    <input type="email" name="email" value="{{ auth()->user()->email }}"
                                           class="w-full pl-11 pr-4 py-3 bg-zinc-50 border border-zinc-200 rounded-xl text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-[#2874F0]/15 focus:border-[#2874F0] transition-all font-medium"
                                           placeholder="Your Email Address" required id="inquiry-email">
                                </div>
                                <div class="relative">
                                    <i class="ph-bold ph-phone absolute left-4 top-1/2 -translate-y-1/2 text-zinc-400 text-base"></i>
                                    <input type="tel" name="phone" value="{{ auth()->user()->phone }}"
                                           class="w-full pl-11 pr-4 py-3 bg-zinc-50 border border-zinc-200 rounded-xl text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-[#2874F0]/15 focus:border-[#2874F0] transition-all font-medium"
                                           placeholder="Phone Number (optional)" id="inquiry-phone">
                                </div>
                                <div class="relative">
                                    <textarea name="message" rows="4"
                                              class="w-full px-4 py-3 bg-zinc-50 border border-zinc-200 rounded-xl text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-[#2874F0]/15 focus:border-[#2874F0] transition-all resize-none font-medium"
                                              placeholder="I'm interested in this property..." required id="inquiry-message">I'm interested in "{{ $property->title }}". Please share more details.</textarea>
                                </div>
                                <button type="submit" class="w-full px-6 py-3.5 bg-gradient-to-r from-[#2874F0] to-[#1A5FDF] hover:brightness-105 text-white text-sm font-extrabold rounded-xl shadow-lg shadow-[#2874F0]/20 hover:shadow-[#2874F0]/30 transition-all flex items-center justify-center gap-2 cursor-pointer" id="inquiry-submit">
                                    <i class="ph-bold ph-paper-plane-tilt"></i> Send Inquiry Details
                                </button>
                            </div>

                            @if($errors->any())
                            <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-xl">
                                @foreach($errors->all() as $error)
                                    <p class="text-xs font-bold text-red-650">{{ $error }}</p>
                                @endforeach
                            </div>
                            @endif
                        </form>
                        @else
                        <div class="text-center py-6">
                            <p class="text-xs font-bold text-zinc-550 mb-4 leading-normal">Please sign in with your UnlockRentals account to submit inquiries directly to the owner.</p>
                            <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 px-6 py-2.5 bg-[#2874F0] hover:bg-[#1A5FDF] text-white text-xs font-extrabold rounded-lg shadow-md transition-all" title="Sign In Now">
                                Sign In Now
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Similar Properties (Flipkart Product Cards Style Redesign) --}}
        @if($similarProperties->count() > 0)
        <div class="mt-16" id="similar-properties">
            <div class="flex items-center justify-between border-b border-zinc-200 pb-4 mb-8">
                <h2 class="text-xl sm:text-2xl font-black text-zinc-900 tracking-tight">People Also Viewed</h2>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-zinc-450 uppercase tracking-widest sm:block hidden">Swipe for more</span>
                    <div class="flex gap-1.5">
                        <button class="w-8 h-8 rounded-lg bg-white border border-zinc-200 flex items-center justify-center text-zinc-500 hover:text-[#2874F0] hover:border-[#2874F0] transition-all cursor-pointer"><i class="ph-bold ph-caret-left"></i></button>
                        <button class="w-8 h-8 rounded-lg bg-white border border-zinc-200 flex items-center justify-center text-zinc-500 hover:text-[#2874F0] hover:border-[#2874F0] transition-all cursor-pointer"><i class="ph-bold ph-caret-right"></i></button>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 md:gap-6">
                @foreach($similarProperties as $similar)
                <div class="bg-white rounded-2xl border border-zinc-200 overflow-hidden hover:border-[#2874F0]/40 hover:shadow-xl transition-all duration-300 flex flex-col group h-full">
                    {{-- Card Image --}}
                    <div class="relative aspect-[4/3] w-full overflow-hidden bg-zinc-100 shadow-inner">
                        @if($similar->primaryImage)
                            <img src="{{ $similar->primaryImage->imageUrl() }}"
                                 alt="{{ $similar->title }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                 loading="lazy">
                        @else
                            <img src="{{ asset('images/luxury_sunlit.png') }}"
                                 alt="Placeholder"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                 loading="lazy">
                        @endif

                        {{-- Category/Type Badges --}}
                        <div class="absolute top-3 left-3 flex gap-1.5 z-10">
                            <span class="px-2.5 py-0.5 bg-white/95 backdrop-blur-sm text-zinc-800 text-[10px] font-bold uppercase rounded-md shadow-sm">
                                {{ ucfirst($similar->type) }}
                            </span>
                            @if($similar->is_featured)
                            <span class="px-2 py-0.5 bg-amber-500 text-white text-[10px] font-bold uppercase rounded-md shadow-sm flex items-center gap-0.5">
                                <i class="ph-bold ph-star text-[9px]"></i> Premium
                            </span>
                            @endif
                        </div>

                        {{-- Price Overlaid Badge --}}
                        <div class="absolute bottom-3 left-3 bg-zinc-900/85 backdrop-blur-sm text-white px-3 py-1.5 rounded-lg text-xs font-extrabold shadow-md flex items-center gap-0.5">
                            ₹{{ number_format($similar->price, 0) }}<span class="text-[10px] font-bold text-zinc-300">/{{ $similar->price_period }}</span>
                        </div>

                        {{-- Rating Mock Badge (Flipkart/Airbnb Style) --}}
                        <div class="absolute top-3 right-3 bg-emerald-500 text-white text-[10px] font-black px-2 py-0.5 rounded-md shadow-md flex items-center gap-0.5">
                            4.8 <i class="ph-bold ph-star text-[9px]"></i>
                        </div>
                    </div>

                    {{-- Card Details --}}
                    <div class="p-4 flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="font-extrabold text-zinc-900 text-sm line-clamp-1 group-hover:text-[#2874F0] transition-colors leading-tight mb-1" title="{{ $similar->title }}">
                                <a href="{{ route('properties.show', $similar) }}" title="UnlockRentals">
                                    {{ $similar->title }}
                                </a>
                            </h3>
                            
                            <p class="text-xs text-zinc-550 flex items-center gap-1 mb-3">
                                <i class="ph-bold ph-map-pin text-[#2874F0]"></i>
                                <span class="truncate">{{ $similar->location }}</span>
                            </p>

                            <div class="flex items-center gap-2 border-t border-zinc-100 pt-3 mb-4 text-[11px] text-zinc-650 font-bold">
                                @if($similar->bedrooms)
                                    <span class="flex items-center gap-0.5 bg-zinc-100 py-1 px-2 rounded-md"><i class="ph-bold ph-bed text-[#2874F0] text-xs"></i> {{ $similar->bedrooms }} Bed</span>
                                @endif
                                @if($similar->bathrooms)
                                    <span class="flex items-center gap-0.5 bg-zinc-100 py-1 px-2 rounded-md"><i class="ph-bold ph-drop text-[#2874F0] text-xs"></i> {{ $similar->bathrooms }} Bath</span>
                                @endif
                                @if($similar->area_sqft)
                                    <span class="flex items-center gap-0.5 bg-zinc-100 py-1 px-2 rounded-md"><i class="ph-bold ph-ruler-square text-[#2874F0] text-xs"></i> {{ number_format($similar->area_sqft) }} ft²</span>
                                @endif
                            </div>
                        </div>

                        {{-- Footer with Owner Info & Button --}}
                        <div class="flex items-center justify-between border-t border-zinc-100 pt-3 mt-auto">
                            @if($similar->owner)
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-[#2874F0]/10 text-[#2874F0] font-black text-xs flex items-center justify-center border border-zinc-200">
                                    {{ strtoupper(substr($similar->owner->name, 0, 1)) }}
                                </div>
                                <div class="leading-none">
                                    <p class="text-[10px] text-zinc-500 font-bold uppercase tracking-wider">Owner</p>
                                    <p class="text-xs font-black text-zinc-800 mt-0.5 max-w-[80px] truncate">{{ $similar->owner->name }}</p>
                                </div>
                            </div>
                            @endif

                            <a href="{{ route('properties.show', $similar) }}" class="px-3.5 py-1.5 bg-[#2874F0] hover:bg-[#1A5FDF] text-white text-[11px] font-extrabold rounded-lg shadow-md shadow-[#2874F0]/15 transition-all flex items-center gap-0.5 cursor-pointer" title="Explore">
                                Explore <i class="ph-bold ph-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>

{{-- Mobile Sticky Bottom Bar (UX Improvement) --}}
<div class="lg:hidden fixed bottom-0 left-0 w-full bg-white/95 backdrop-blur-md border-t border-zinc-250 py-3.5 px-4 z-[990] flex items-center justify-between gap-3 shadow-[0_-8px_30px_rgba(0,0,0,0.08)]">
    <div>
        <p class="text-[10px] text-zinc-500 font-bold uppercase tracking-wider">Price per {{ $property->price_period }}</p>
        <p class="text-lg font-black text-zinc-900 leading-none mt-0.5">₹{{ number_format($property->price, 0) }}</p>
    </div>
    <div class="flex items-center gap-2 flex-1 max-w-[240px]">
        <a href="https://wa.me/?text=Hi! I am interested in your property on UnlockRentals: {{ urlencode(route('properties.show', $property)) }}"
           target="_blank"
           class="flex-1 px-3.5 py-2.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-extrabold text-xs rounded-xl border border-emerald-200/60 shadow-sm transition-all flex items-center justify-center gap-1.5 cursor-pointer" title="WA">
            <i class="ph-bold ph-whatsapp text-emerald-500 text-base"></i> WA
        </a>
        <a href="#inquiry-form-card"
           class="flex-1 px-3.5 py-2.5 bg-[#2874F0] hover:bg-[#1A5FDF] text-white font-extrabold text-xs rounded-xl shadow-md shadow-[#2874F0]/15 transition-all flex items-center justify-center gap-1.5 cursor-pointer" title="Inquiry">
            <i class="ph-bold ph-envelope"></i> Inquiry
        </a>
    </div>
</div>

{{-- ==================== CALL AGENT MODAL ==================== --}}
@auth
<div id="call-agent-modal" class="fixed inset-0 z-[9999] hidden">
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-zinc-900/60 backdrop-blur-sm" onclick="closeCallAgentModal()"></div>
    {{-- Modal Content --}}
    <div class="relative flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden animate-[modalSlideIn_0.3s_ease-out]">
            {{-- Header --}}
            <div class="bg-gradient-to-r from-[#2874F0] to-[#1A5FDF] px-6 py-5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="ph-bold ph-phone-call text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-white font-extrabold text-base">Request a Callback</h3>
                            <p class="text-blue-100 text-xs font-medium">Our agent will call you shortly</p>
                        </div>
                    </div>
                    <button onclick="closeCallAgentModal()" class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors cursor-pointer">
                        <i class="ph-bold ph-x text-sm"></i>
                    </button>
                </div>
            </div>

            {{-- Property Info Mini Card --}}
            <div class="px-6 pt-5">
                <div class="flex items-center gap-3 p-3 bg-zinc-50 rounded-xl border border-zinc-100">
                    @if($property->primaryImage)
                    <img src="{{ $property->primaryImage->imageUrl() }}" class="w-12 h-12 rounded-lg object-cover border border-zinc-200" alt="">
                    @else
                    <div class="w-12 h-12 rounded-lg bg-zinc-200 flex items-center justify-center text-zinc-400">
                        <i class="ph-bold ph-image text-lg"></i>
                    </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-zinc-900 truncate">{{ $property->title }}</p>
                        <p class="text-xs text-zinc-500 truncate">
                            @if($isOwnerOrAdmin || $hasViewed)
                                {{ $property->address }}, {{ $property->location }}
                            @else
                                {{ $property->location }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            {{-- Direct Call Section --}}
            <div class="px-6 pt-4 text-center">
                <div class="p-4 bg-blue-50/50 border border-blue-100 rounded-xl">
                    <p class="text-zinc-500 text-xs font-bold uppercase tracking-wider mb-1.5">Direct Agent Number</p>
                    <a href="tel:{{ \App\Models\Setting::get('agent_phone', '+91 7974164274') }}" class="text-xl font-black text-[#2874F0] hover:underline flex items-center justify-center gap-2" title="Call Support">
                        <i class="ph-bold ph-phone"></i> {{ \App\Models\Setting::get('agent_phone', '+91 7974164274') }}
                    </a>
                    <p class="text-zinc-400 text-[10px] mt-1 font-semibold">Click to dial directly, or request a callback below:</p>
                </div>
            </div>

            {{-- Form --}}
            <form id="call-agent-form" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-1.5">Your Name</label>
                    <div class="relative">
                        <i class="ph-bold ph-user absolute left-3.5 top-1/2 -translate-y-1/2 text-zinc-400"></i>
                        <input type="text" name="name" value="{{ auth()->user()->name }}" required
                               class="w-full pl-10 pr-4 py-3 bg-zinc-50 border border-zinc-200 rounded-xl text-sm text-zinc-900 focus:outline-none focus:border-[#2874F0] focus:ring-2 focus:ring-[#2874F0]/10 transition-all font-medium" id="callback-name">
                    </div>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-1.5">Phone Number</label>
                    <div class="relative">
                        <i class="ph-bold ph-phone absolute left-3.5 top-1/2 -translate-y-1/2 text-zinc-400"></i>
                        <input type="tel" name="phone" value="{{ auth()->user()->phone }}" required placeholder="Enter your phone number"
                               class="w-full pl-10 pr-4 py-3 bg-zinc-50 border border-zinc-200 rounded-xl text-sm text-zinc-900 focus:outline-none focus:border-[#2874F0] focus:ring-2 focus:ring-[#2874F0]/10 transition-all font-medium" id="callback-phone">
                    </div>
                </div>

                {{-- Error / Success Messages --}}
                <div id="callback-alert" class="hidden rounded-xl p-3 text-sm font-bold"></div>

                <button type="submit" id="callback-submit-btn" class="w-full py-3.5 bg-gradient-to-r from-[#2874F0] to-[#1A5FDF] hover:brightness-105 text-white text-sm font-extrabold rounded-xl shadow-lg shadow-[#2874F0]/20 transition-all flex items-center justify-center gap-2 cursor-pointer">
                    <span class="btn-text flex items-center gap-2">
                        <i class="ph-bold ph-phone-call"></i> Request Callback
                    </span>
                    <span class="btn-loader hidden flex items-center gap-2">
                        <i class="ph ph-circle-notch animate-spin"></i> Submitting...
                    </span>
                </button>
            </form>
        </div>
    </div>
</div>

{{-- ==================== BOOK VISIT MODAL ==================== --}}
<div id="book-visit-modal" class="fixed inset-0 z-[9999] hidden">
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-zinc-900/60 backdrop-blur-sm" onclick="closeBookVisitModal()"></div>
    {{-- Modal Content --}}
    <div class="relative flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden animate-[modalSlideIn_0.3s_ease-out]">
            {{-- Header --}}
            <div class="bg-gradient-to-r from-emerald-500 to-teal-600 px-6 py-5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="ph-bold ph-calendar-check text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-white font-extrabold text-base">Book a Property Visit</h3>
                            <p class="text-emerald-100 text-xs font-medium">Schedule an in-person visit</p>
                        </div>
                    </div>
                    <button onclick="closeBookVisitModal()" class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors cursor-pointer">
                        <i class="ph-bold ph-x text-sm"></i>
                    </button>
                </div>
            </div>

            {{-- Property Info Mini Card --}}
            <div class="px-6 pt-5">
                <div class="flex items-center gap-3 p-3 bg-zinc-50 rounded-xl border border-zinc-100">
                    @if($property->primaryImage)
                    <img src="{{ $property->primaryImage->imageUrl() }}" class="w-12 h-12 rounded-lg object-cover border border-zinc-200" alt="">
                    @else
                    <div class="w-12 h-12 rounded-lg bg-zinc-200 flex items-center justify-center text-zinc-400">
                        <i class="ph-bold ph-image text-lg"></i>
                    </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-zinc-900 truncate">{{ $property->title }}</p>
                        <p class="text-xs text-zinc-500 truncate">
                            @if($isOwnerOrAdmin || $hasViewed)
                                {{ $property->address }}, {{ $property->location }}
                            @else
                                {{ $property->location }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <form id="book-visit-form" class="p-6 space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-1.5">Preferred Date</label>
                        <div class="relative">
                            <i class="ph-bold ph-calendar absolute left-3.5 top-1/2 -translate-y-1/2 text-zinc-400"></i>
                            <input type="date" name="preferred_date" required min="{{ date('Y-m-d') }}"
                                   class="w-full pl-10 pr-3 py-3 bg-zinc-50 border border-zinc-200 rounded-xl text-sm text-zinc-900 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all font-medium" id="visit-date">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-1.5">Time Slot</label>
                        <div class="relative">
                            <i class="ph-bold ph-clock absolute left-3.5 top-1/2 -translate-y-1/2 text-zinc-400"></i>
                            <select name="preferred_time" required
                                    class="w-full pl-10 pr-3 py-3 bg-zinc-50 border border-zinc-200 rounded-xl text-sm text-zinc-900 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all font-medium appearance-none" id="visit-time">
                                <option value="morning">Morning (9-12)</option>
                                <option value="afternoon">Afternoon (12-4)</option>
                                <option value="evening" selected>Evening (4-7)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-1.5">Your Name</label>
                    <div class="relative">
                        <i class="ph-bold ph-user absolute left-3.5 top-1/2 -translate-y-1/2 text-zinc-400"></i>
                        <input type="text" name="name" value="{{ auth()->user()->name }}" required
                               class="w-full pl-10 pr-4 py-3 bg-zinc-50 border border-zinc-200 rounded-xl text-sm text-zinc-900 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all font-medium" id="visit-name">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-1.5">Phone Number</label>
                        <div class="relative">
                            <i class="ph-bold ph-phone absolute left-3.5 top-1/2 -translate-y-1/2 text-zinc-400"></i>
                            <input type="tel" name="phone" value="{{ auth()->user()->phone }}" required placeholder="Your phone"
                                   class="w-full pl-10 pr-3 py-3 bg-zinc-50 border border-zinc-200 rounded-xl text-sm text-zinc-900 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all font-medium" id="visit-phone">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-1.5">Email <span class="text-zinc-300">(optional)</span></label>
                        <div class="relative">
                            <i class="ph-bold ph-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-zinc-400"></i>
                            <input type="email" name="email" value="{{ auth()->user()->email }}" placeholder="Your email"
                                   class="w-full pl-10 pr-3 py-3 bg-zinc-50 border border-zinc-200 rounded-xl text-sm text-zinc-900 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all font-medium" id="visit-email">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-1.5">Message <span class="text-zinc-300">(optional)</span></label>
                    <textarea name="message" rows="2" placeholder="Any specific requirements or questions..."
                              class="w-full px-4 py-3 bg-zinc-50 border border-zinc-200 rounded-xl text-sm text-zinc-900 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all resize-none font-medium" id="visit-message"></textarea>
                </div>

                {{-- Error / Success Messages --}}
                <div id="visit-alert" class="hidden rounded-xl p-3 text-sm font-bold"></div>

                <button type="submit" id="visit-submit-btn" class="w-full py-3.5 bg-gradient-to-r from-emerald-500 to-teal-600 hover:brightness-105 text-white text-sm font-extrabold rounded-xl shadow-lg shadow-emerald-500/20 transition-all flex items-center justify-center gap-2 cursor-pointer">
                    <span class="btn-text flex items-center gap-2">
                        <i class="ph-bold ph-calendar-check"></i> Confirm Visit Booking
                    </span>
                    <span class="btn-loader hidden flex items-center gap-2">
                        <i class="ph ph-circle-notch animate-spin"></i> Booking...
                    </span>
                </button>
            </form>
        </div>
    </div>
</div>
@endauth

{{-- Fullscreen Photo Lightbox Modal --}}
@if($property->images->count() > 0)
<div id="gallery-lightbox" class="fixed inset-0 bg-black/95 z-[99999] hidden flex-col items-center justify-center p-4 backdrop-blur-md select-none transition-all duration-300" onclick="if(event.target === this) closeLightboxModal()">
    {{-- Close Button --}}
    <button type="button" onclick="closeLightboxModal()" class="absolute top-5 right-5 text-white/80 hover:text-white w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-2xl transition cursor-pointer z-20" aria-label="Close Lightbox">
        <i class="ph-bold ph-x"></i>
    </button>
    {{-- Counter Badge --}}
    <div class="absolute top-5 left-5 text-white text-xs sm:text-sm font-bold bg-white/10 px-4 py-2 rounded-full backdrop-blur-sm z-20 flex items-center gap-1.5" id="lightbox-counter">
        <i class="ph-bold ph-image"></i> Photo 1 of {{ $property->images->count() }}
    </div>
    {{-- Lightbox Image Box --}}
    <div class="relative max-w-6xl max-h-[85vh] w-full h-full flex items-center justify-center overflow-hidden" onclick="event.stopPropagation()">
        <img id="lightbox-img" src="" alt="{{ $property->title }}" class="max-w-full max-h-full object-contain rounded-xl shadow-2xl transition-all duration-300">
    </div>
    {{-- Lightbox Prev / Next Buttons --}}
    @if($property->images->count() > 1)
    <button type="button" onclick="event.stopPropagation(); prevLightboxImage()" class="absolute left-4 top-1/2 -translate-y-1/2 text-white/80 hover:text-white w-12 h-12 rounded-full bg-white/15 hover:bg-white/30 flex items-center justify-center text-2xl transition cursor-pointer z-20" aria-label="Previous">
        <i class="ph-bold ph-caret-left"></i>
    </button>
    <button type="button" onclick="event.stopPropagation(); nextLightboxImage()" class="absolute right-4 top-1/2 -translate-y-1/2 text-white/80 hover:text-white w-12 h-12 rounded-full bg-white/15 hover:bg-white/30 flex items-center justify-center text-2xl transition cursor-pointer z-20" aria-label="Next">
        <i class="ph-bold ph-caret-right"></i>
    </button>
    @endif
</div>
@endif

@push('scripts')
<style>
    @keyframes modalSlideIn {
        from { opacity: 0; transform: translateY(20px) scale(0.97); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
</style>
<script>
    // ── Modal Open / Close ────────────────────
    function openCallAgentModal() {
        const modal = document.getElementById('call-agent-modal');
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            resetModalAlert('callback-alert');
        }
    }
    function closeCallAgentModal() {
        const modal = document.getElementById('call-agent-modal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    }
    function openBookVisitModal() {
        const modal = document.getElementById('book-visit-modal');
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            resetModalAlert('visit-alert');
        }
    }
    function closeBookVisitModal() {
        const modal = document.getElementById('book-visit-modal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    }

    // Close modals on Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeCallAgentModal();
            closeBookVisitModal();
            closeLightboxModal();
        }
    });

    // ── Alert Helpers ─────────────────────────
    function showModalAlert(alertId, message, isSuccess) {
        const alert = document.getElementById(alertId);
        if (!alert) return;
        alert.classList.remove('hidden', 'bg-red-50', 'text-red-700', 'border-red-200', 'bg-emerald-50', 'text-emerald-700', 'border-emerald-200');
        if (isSuccess) {
            alert.classList.add('bg-emerald-50', 'text-emerald-700', 'border', 'border-emerald-200');
            alert.innerHTML = '<div class="flex items-center gap-2"><i class="ph-bold ph-check-circle text-base"></i> ' + message + '</div>';
        } else {
            alert.classList.add('bg-red-50', 'text-red-700', 'border', 'border-red-200');
            alert.innerHTML = '<div class="flex items-center gap-2"><i class="ph-bold ph-warning text-base"></i> ' + message + '</div>';
        }
    }
    function resetModalAlert(alertId) {
        const alert = document.getElementById(alertId);
        if (!alert) return;
        alert.classList.add('hidden');
        alert.innerHTML = '';
    }

    function setButtonLoading(btn, loading) {
        if (!btn) return;
        const text = btn.querySelector('.btn-text');
        const loader = btn.querySelector('.btn-loader');
        if (loading) {
            btn.disabled = true;
            if (text) text.classList.add('hidden');
            if (loader) loader.classList.remove('hidden');
        } else {
            btn.disabled = false;
            if (text) text.classList.remove('hidden');
            if (loader) loader.classList.add('hidden');
        }
    }

    // ── Call Agent Form Submit (Safe Event Listener) ────────────────
    const callAgentFormEl = document.getElementById('call-agent-form');
    if (callAgentFormEl) {
        callAgentFormEl.addEventListener('submit', async function(e) {
            e.preventDefault();
            const btn = document.getElementById('callback-submit-btn');
            const form = this;
            const alertId = 'callback-alert';

            // Client-side validation
            const phoneInput = document.getElementById('callback-phone');
            const phone = phoneInput ? phoneInput.value.trim() : '';
            if (!phone) {
                showModalAlert(alertId, 'Please enter your phone number.', false);
                return;
            }

            setButtonLoading(btn, true);
            resetModalAlert(alertId);

            try {
                const formData = new FormData(form);
                const response = await fetch("{{ route('properties.request-callback', $property) }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    showModalAlert(alertId, data.message, true);
                    if (btn) {
                        btn.disabled = true;
                        const btnText = btn.querySelector('.btn-text');
                        const btnLoader = btn.querySelector('.btn-loader');
                        if (btnText) {
                            btnText.innerHTML = '<i class="ph-bold ph-check-circle"></i> Callback Requested';
                            btnText.classList.remove('hidden');
                        }
                        if (btnLoader) btnLoader.classList.add('hidden');
                    }
                    setTimeout(() => {
                        closeCallAgentModal();
                        if (btn) {
                            btn.disabled = false;
                            const btnText = btn.querySelector('.btn-text');
                            if (btnText) btnText.innerHTML = '<i class="ph-bold ph-phone-call"></i> Request Callback';
                        }
                    }, 3000);
                } else {
                    showModalAlert(alertId, data.message || 'Something went wrong. Please try again.', false);
                    setButtonLoading(btn, false);
                }
            } catch (error) {
                showModalAlert(alertId, 'Network error. Please check your connection and try again.', false);
                setButtonLoading(btn, false);
            }
        });
    }

    // ── Book Visit Form Submit (Safe Event Listener) ────────────────
    const bookVisitFormEl = document.getElementById('book-visit-form');
    if (bookVisitFormEl) {
        bookVisitFormEl.addEventListener('submit', async function(e) {
            e.preventDefault();
            const btn = document.getElementById('visit-submit-btn');
            const form = this;
            const alertId = 'visit-alert';

            // Client-side validation
            const dateInput = document.getElementById('visit-date');
            const phoneInput = document.getElementById('visit-phone');
            const date = dateInput ? dateInput.value : '';
            const phone = phoneInput ? phoneInput.value.trim() : '';
            if (!date) {
                showModalAlert(alertId, 'Please select a preferred date for your visit.', false);
                return;
            }
            if (!phone) {
                showModalAlert(alertId, 'Please enter your phone number.', false);
                return;
            }

            setButtonLoading(btn, true);
            resetModalAlert(alertId);

            try {
                const formData = new FormData(form);
                const response = await fetch("{{ route('properties.book-visit', $property) }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    showModalAlert(alertId, data.message, true);
                    if (btn) {
                        btn.disabled = true;
                        const btnText = btn.querySelector('.btn-text');
                        const btnLoader = btn.querySelector('.btn-loader');
                        if (btnText) {
                            btnText.innerHTML = '<i class="ph-bold ph-check-circle"></i> Visit Booked!';
                            btnText.classList.remove('hidden');
                        }
                        if (btnLoader) btnLoader.classList.add('hidden');
                    }
                    setTimeout(() => {
                        closeBookVisitModal();
                        if (btn) {
                            btn.disabled = false;
                            const btnText = btn.querySelector('.btn-text');
                            if (btnText) btnText.innerHTML = '<i class="ph-bold ph-calendar-check"></i> Confirm Visit Booking';
                        }
                        form.reset();
                    }, 3000);
                } else {
                    if (data.redirect) { window.location.href = data.redirect; return; }
                    showModalAlert(alertId, data.message || 'Something went wrong. Please try again.', false);
                    setButtonLoading(btn, false);
                }
            } catch (error) {
                showModalAlert(alertId, 'Network error. Please check your connection and try again.', false);
                setButtonLoading(btn, false);
            }
        });
    }

    // ── Horizontal Slide-Left Carousel with Loop Repeat ─────────────────
    const propertyGalleryImages = @json($property->images->map(fn($img) => $img->imageUrl())->values());
    const totalGalleryImages = {{ $property->images->count() }};
    let currentGalleryIndex = 0;
    let galleryAutoSlideTimer = null;
    const gallerySlideDuration = 3500; // 3.5 seconds

    // ── Interactive Cursor Follow Loupe Zoom ─────────────────
    function handleGalleryZoom(e, container) {
        if (!container) return;
        const img = container.querySelector('.gallery-zoom-target');
        if (!img) return;
        
        const rect = container.getBoundingClientRect();
        if (rect.width === 0 || rect.height === 0) return;
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        
        const xPercent = Math.max(0, Math.min(100, (x / rect.width) * 100));
        const yPercent = Math.max(0, Math.min(100, (y / rect.height) * 100));
        
        img.style.transformOrigin = `${xPercent}% ${yPercent}%`;
        img.style.transform = 'scale(2.2)';
    }

    function resetGalleryZoom(container) {
        if (!container) return;
        const img = container.querySelector('.gallery-zoom-target');
        if (!img) return;
        
        img.style.transform = 'scale(1)';
        img.style.transformOrigin = 'center center';
    }

    function resetAllGalleryZooms() {
        document.querySelectorAll('.gallery-zoom-target').forEach(img => {
            img.style.transform = 'scale(1)';
            img.style.transformOrigin = 'center center';
        });
    }

    // ── Slide Track Animation ─────────────────
    function updateGallerySlide(index) {
        if (totalGalleryImages <= 1) return;
        
        // Loop repeat when complete
        if (index >= totalGalleryImages) {
            index = 0;
        } else if (index < 0) {
            index = totalGalleryImages - 1;
        }
        
        currentGalleryIndex = index;
        resetAllGalleryZooms();

        const track = document.getElementById('gallery-slider-track');
        const counter = document.getElementById('gallery-counter');
        
        // Move track left (new image enters from the right)
        if (track) {
            track.style.transform = `translateX(-${currentGalleryIndex * 100}%)`;
        }

        if (counter) {
            counter.textContent = `${currentGalleryIndex + 1} of ${totalGalleryImages} Photos`;
        }

        // Highlight active thumbnail & scroll it into view
        const thumbs = document.querySelectorAll('.gallery-thumb');
        thumbs.forEach((thumb, idx) => {
            if (idx === currentGalleryIndex) {
                thumb.classList.add('border-[#2874F0]', 'ring-2', 'ring-[#2874F0]/20');
                thumb.classList.remove('border-zinc-200');
                thumb.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
            } else {
                thumb.classList.remove('border-[#2874F0]', 'ring-2', 'ring-[#2874F0]/20');
                thumb.classList.add('border-zinc-200');
            }
        });
        
        resetGalleryProgressBar();
    }

    function nextGalleryImage() {
        updateGallerySlide(currentGalleryIndex + 1);
        restartGalleryAutoSlide();
    }

    function prevGalleryImage() {
        updateGallerySlide(currentGalleryIndex - 1);
        restartGalleryAutoSlide();
    }

    function selectGalleryImage(index) {
        updateGallerySlide(index);
        restartGalleryAutoSlide();
    }

    function resetGalleryProgressBar() {
        const bar = document.getElementById('gallery-progress-bar');
        if (bar) {
            bar.style.transition = 'none';
            bar.style.width = '0%';
            setTimeout(() => {
                if (bar) {
                    bar.style.transition = `width ${gallerySlideDuration}ms linear`;
                    bar.style.width = '100%';
                }
            }, 40);
        }
    }

    function startGalleryAutoSlide() {
        if (totalGalleryImages <= 1) return;
        clearInterval(galleryAutoSlideTimer);
        resetGalleryProgressBar();
        galleryAutoSlideTimer = setInterval(() => {
            updateGallerySlide(currentGalleryIndex + 1);
        }, gallerySlideDuration);
    }

    function pauseGalleryAutoSlide() {
        clearInterval(galleryAutoSlideTimer);
        const bar = document.getElementById('gallery-progress-bar');
        if (bar) {
            bar.style.width = '0%';
            bar.style.transition = 'none';
        }
    }

    function restartGalleryAutoSlide() {
        pauseGalleryAutoSlide();
        startGalleryAutoSlide();
    }

    // ── Fullscreen Lightbox Modal ─────────────────
    let lightboxIndex = 0;
    function openLightboxModal(index) {
        if (!propertyGalleryImages || propertyGalleryImages.length === 0) return;
        pauseGalleryAutoSlide();
        lightboxIndex = (typeof index === 'number') ? index : currentGalleryIndex;
        const modal = document.getElementById('gallery-lightbox');
        const img = document.getElementById('lightbox-img');
        const counter = document.getElementById('lightbox-counter');
        
        if (modal && img) {
            img.src = propertyGalleryImages[lightboxIndex] || '';
            if (counter) counter.innerHTML = `<i class="ph-bold ph-image"></i> Photo ${lightboxIndex + 1} of ${totalGalleryImages}`;
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
            if (totalGalleryImages > 1) {
                updateGallerySlide(lightboxIndex);
                startGalleryAutoSlide();
            }
        }
    }

    function nextLightboxImage() {
        if (!propertyGalleryImages || propertyGalleryImages.length <= 1) return;
        lightboxIndex = (lightboxIndex + 1) % propertyGalleryImages.length;
        const img = document.getElementById('lightbox-img');
        const counter = document.getElementById('lightbox-counter');
        if (img) {
            img.style.opacity = '0.3';
            setTimeout(() => {
                img.src = propertyGalleryImages[lightboxIndex];
                img.style.opacity = '1';
            }, 120);
        }
        if (counter) counter.innerHTML = `<i class="ph-bold ph-image"></i> Photo ${lightboxIndex + 1} of ${totalGalleryImages}`;
    }

    function prevLightboxImage() {
        if (!propertyGalleryImages || propertyGalleryImages.length <= 1) return;
        lightboxIndex = (lightboxIndex - 1 + propertyGalleryImages.length) % propertyGalleryImages.length;
        const img = document.getElementById('lightbox-img');
        const counter = document.getElementById('lightbox-counter');
        if (img) {
            img.style.opacity = '0.3';
            setTimeout(() => {
                img.src = propertyGalleryImages[lightboxIndex];
                img.style.opacity = '1';
            }, 120);
        }
        if (counter) counter.innerHTML = `<i class="ph-bold ph-image"></i> Photo ${lightboxIndex + 1} of ${totalGalleryImages}`;
    }

    // Keyboard support (Escape to close, Left/Right arrow keys)
    document.addEventListener('keydown', (e) => {
        const modal = document.getElementById('gallery-lightbox');
        if (modal && !modal.classList.contains('hidden')) {
            if (e.key === 'Escape') closeLightboxModal();
            if (e.key === 'ArrowRight') nextLightboxImage();
            if (e.key === 'ArrowLeft') prevLightboxImage();
        }
    });

    // ── Touch Swipe Support for Mobile & Tablets ─────────────────
    let touchStartX = 0;
    let touchEndX = 0;
    const galleryMainEl = document.getElementById('gallery-main');
    if (galleryMainEl) {
        galleryMainEl.addEventListener('touchstart', (e) => {
            if (e.changedTouches && e.changedTouches[0]) {
                touchStartX = e.changedTouches[0].screenX;
            }
            pauseGalleryAutoSlide();
        }, { passive: true });

        galleryMainEl.addEventListener('touchend', (e) => {
            if (e.changedTouches && e.changedTouches[0]) {
                touchEndX = e.changedTouches[0].screenX;
                const diffX = touchStartX - touchEndX;
                if (Math.abs(diffX) > 40) {
                    if (diffX > 0) {
                        nextGalleryImage();
                    } else {
                        prevGalleryImage();
                    }
                } else {
                    startGalleryAutoSlide();
                }
            }
        }, { passive: true });
    }

    // Expose functions globally to window object for reliable onclick handler execution
    window.openCallAgentModal = openCallAgentModal;
    window.closeCallAgentModal = closeCallAgentModal;
    window.openBookVisitModal = openBookVisitModal;
    window.closeBookVisitModal = closeBookVisitModal;
    window.handleGalleryZoom = handleGalleryZoom;
    window.resetGalleryZoom = resetGalleryZoom;
    window.updateGallerySlide = updateGallerySlide;
    window.nextGalleryImage = nextGalleryImage;
    window.prevGalleryImage = prevGalleryImage;
    window.selectGalleryImage = selectGalleryImage;
    window.pauseGalleryAutoSlide = pauseGalleryAutoSlide;
    window.startGalleryAutoSlide = startGalleryAutoSlide;
    window.restartGalleryAutoSlide = restartGalleryAutoSlide;
    window.openLightboxModal = openLightboxModal;
    window.closeLightboxModal = closeLightboxModal;
    window.nextLightboxImage = nextLightboxImage;
    window.prevLightboxImage = prevLightboxImage;

    // Initialize auto slider on page load
    function initGallerySlider() {
        if (totalGalleryImages > 1) {
            updateGallerySlide(0);
            startGalleryAutoSlide();
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initGallerySlider);
    } else {
        initGallerySlider();
    }
</script>
@endpush

@endsection
