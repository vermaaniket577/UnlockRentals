@extends('layouts.app')

@section('title', $property->title . ' - UnlockRentals')
@section('meta_description', Str::limit($property->description, 160))
@section('og_image', $property->primaryImage ? $property->primaryImage->imageUrl() : asset('images/logo.png'))

@push('scripts')
<script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "RealEstateListing",
  "name": "{{ $property->title }}",
  "description": "{{ Str::limit(strip_tags($property->description), 150) }}",
  "url": "{{ route('properties.show', $property) }}",
  "image": "{{ $property->primaryImage ? $property->primaryImage->imageUrl() : asset('images/logo.png') }}",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "{{ $property->address }}",
    "addressLocality": "{{ $property->location }}",
    "addressRegion": "{{ $property->state }}",
    "addressCountry": "IN"
  },
  "offers": {
    "@type": "Offer",
    "price": "{{ $property->price }}",
    "priceCurrency": "INR",
    "availability": "https://schema.org/InStock"
  }
}
</script>
@endpush

@section('content')

<section class="py-8 lg:py-12" id="property-detail">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-sm text-zinc-500 mb-6" id="property-breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-[#2563EB] transition-colors">Home</a>
            <i class="ph ph-caret-right text-xs"></i>
            <a href="{{ route('properties.index') }}" class="hover:text-[#2563EB] transition-colors">Properties</a>
            <i class="ph ph-caret-right text-xs"></i>
            <span class="text-zinc-500">{{ Str::limit($property->title, 40) }}</span>
        </nav>

        <div class="flex flex-col lg:flex-row gap-8">
            {{-- Main Content --}}
            <div class="flex-1">
                {{-- Image Gallery --}}
                <div class="mb-8" id="property-gallery">
                    @if($property->images->count() > 0)
                        {{-- Main Image --}}
                        <div class="relative h-72 sm:h-96 lg:h-[500px] rounded-sm overflow-hidden mb-3" id="gallery-main">
                            <img src="{{ ($property->images->where('is_primary', true)->first() ?? $property->images->first())->imageUrl() }}"
                                 alt="{{ $property->title }}"
                                 class="w-full h-full object-cover"
                                 id="gallery-main-img">

                            {{-- Badges --}}
                            <div class="absolute top-4 left-4 flex gap-2">
                                <span class="px-4 py-1.5 bg-{{ $property->type === 'house' ? 'violet' : 'fuchsia' }}-500/90 text-zinc-900 text-sm font-semibold rounded-sm backdrop-blur-sm">
                                    {{ ucfirst($property->type) }}
                                </span>
                                @if($property->is_featured)
                                <span class="px-4 py-1.5 bg-amber-500/90 text-zinc-900 text-sm font-semibold rounded-sm backdrop-blur-sm">
                                    <i class="ph ph-star"></i> Featured
                                </span>
                                @endif
                                @if(!$property->isApproved())
                                <span class="px-4 py-1.5 bg-orange-500/90 text-zinc-900 text-sm font-semibold rounded-sm backdrop-blur-sm">
                                    {{ ucfirst($property->status) }}
                                </span>
                                @endif
                            </div>
                        </div>

                        {{-- Thumbnails --}}
                        @if($property->images->count() > 1)
                        <div class="grid grid-cols-4 sm:grid-cols-5 lg:grid-cols-6 gap-2" id="gallery-thumbs">
                            @foreach($property->images as $image)
                            <button onclick="document.getElementById('gallery-main-img').src='{{ $image->imageUrl() }}'"
                                    class="h-20 rounded-sm overflow-hidden border-2 border-transparent hover:border-[#2563EB]/50 transition-all focus:border-[#2563EB]/50 focus:outline-none">
                                <img src="{{ $image->imageUrl() }}" alt="" class="w-full h-full object-cover">
                            </button>
                            @endforeach
                        </div>
                        @endif
                    @else
                        <div class="h-72 sm:h-96 bg-gradient-to-br from-[#2563EB]/10 to-[#2563EB]/10 rounded-sm flex items-center justify-center border border-stone-200/50">
                            <i class="ph ph-image text-6xl text-gray-700"></i>
                        </div>
                    @endif
                </div>

                {{-- Title & Location --}}
                <div class="mb-8">
                    <h1 class="text-2xl lg:text-3xl font-medium text-zinc-900 mb-3" id="property-title">{{ $property->title }}</h1>
                    <div class="flex flex-wrap items-center gap-4 text-zinc-500">
                        <div class="flex items-center gap-1.5">
                            <i class="ph ph-map-pin text-[#2563EB]"></i>
                            <span>{{ $property->address }}, {{ $property->location }}</span>
                        </div>
                        @if($property->category)
                        <div class="flex items-center gap-1.5">
                            <i class="ph ph-tag text-[#2563EB]"></i>
                            <span>{{ $property->category->name }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Key Features --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8" id="property-features">
                    @if($property->bedrooms)
                    <div class="p-4 bg-stone-50 border border-stone-200/50 rounded-sm text-center">
                        <i class="ph ph-bed text-2xl text-[#2563EB] mb-2"></i>
                        <p class="text-lg font-medium text-zinc-900">{{ $property->bedrooms }}</p>
                        <p class="text-xs text-zinc-500">Bedrooms</p>
                    </div>
                    @endif
                    @if($property->bathrooms)
                    <div class="p-4 bg-stone-50 border border-stone-200/50 rounded-sm text-center">
                        <i class="ph ph-bathtub text-2xl text-[#2563EB] mb-2"></i>
                        <p class="text-lg font-medium text-zinc-900">{{ $property->bathrooms }}</p>
                        <p class="text-xs text-zinc-500">Bathrooms</p>
                    </div>
                    @endif
                    @if($property->area_sqft)
                    <div class="p-4 bg-stone-50 border border-stone-200/50 rounded-sm text-center">
                        <i class="ph ph-ruler text-2xl text-[#2563EB] mb-2"></i>
                        <p class="text-lg font-medium text-zinc-900">{{ number_format($property->area_sqft) }}</p>
                        <p class="text-xs text-zinc-500">Sq. Ft.</p>
                    </div>
                    @endif
                    <div class="p-4 bg-stone-50 border border-stone-200/50 rounded-sm text-center">
                        <i class="ph ph-couch text-2xl text-[#2563EB] mb-2"></i>
                        <p class="text-lg font-medium text-zinc-900">{{ ucfirst(str_replace('-', ' ', $property->furnishing)) }}</p>
                        <p class="text-xs text-zinc-500">Furnishing</p>
                    </div>
                </div>

                {{-- Description --}}
                <div class="mb-8" id="property-description">
                    <h2 class="text-xl font-semibold text-zinc-900 mb-4">About this Property</h2>
                    <div class="prose prose-invert max-w-none text-zinc-500 leading-relaxed">
                        {!! nl2br(e($property->description)) !!}
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="lg:w-96 flex-shrink-0">
                <div class="sticky top-24 space-y-6">
                    {{-- Price Card --}}
                    <div class="bg-stone-50 border border-stone-200/50 rounded-sm p-6" id="property-price-card">
                        <div class="mb-4">
                            <span class="text-3xl font-serif font-light tracking-wide text-[#2563EB] font-serif font-light italic">
                                ₹{{ number_format($property->price, 0) }}
                            </span>
                            <span class="text-zinc-500">/{{ $property->price_period }}</span>
                        </div>

                        {{-- Owner Info (Contact gated by plan) --}}
                        @if($property->owner)
                        <div class="p-4 bg-stone-50 rounded-sm mb-6">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-12 h-12 bg-[#2563EB] rounded-full flex items-center justify-center text-zinc-900 text-lg font-medium">
                                    {{ strtoupper(substr($property->owner->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-zinc-900">{{ $property->owner->name }}</p>
                                    <p class="text-xs text-zinc-500">Property Owner</p>
                                </div>
                            </div>

                            @auth
                                @php
                                    $canView = auth()->user()->canViewContact($property);
                                    $hasViewed = auth()->user()->hasViewedContact($property);
                                    $isOwnerOrAdmin = auth()->id() === $property->user_id || auth()->user()->isAdmin();
                                    $activePlan = auth()->user()->activePlan();
                                @endphp

                                @if($isOwnerOrAdmin || $hasViewed)
                                    {{-- Show full contact details --}}
                                    <div class="space-y-2 pt-3 border-t border-stone-200/50">
                                        <div class="flex items-center gap-2 text-sm">
                                            <i class="ph ph-phone text-[#2563EB]"></i>
                                            <a href="tel:{{ $property->owner->phone }}" class="text-zinc-700 hover:text-[#2563EB]">
                                                {{ $property->owner->phone ?? 'Not provided' }}
                                            </a>
                                        </div>
                                        <div class="flex items-center gap-2 text-sm">
                                            <i class="ph ph-envelope text-[#2563EB]"></i>
                                            <a href="mailto:{{ $property->owner->email }}" class="text-zinc-700 hover:text-[#2563EB]">
                                                {{ $property->owner->email }}
                                            </a>
                                        </div>
                                        @if($hasViewed && !$isOwnerOrAdmin)
                                        <p class="text-xs text-green-600 mt-2 flex items-center gap-1">
                                            <i class="ph ph-check-circle"></i> Contact unlocked
                                            @if($activePlan)
                                                · {{ $activePlan->remaining_contacts }} views remaining
                                            @endif
                                        </p>
                                        @endif
                                    </div>
                                @elseif($canView)
                                    {{-- Has plan with remaining contacts — show unlock button --}}
                                    <div class="pt-3 border-t border-stone-200/50">
                                        <div class="text-center mb-3">
                                            <p class="text-sm text-zinc-500 mb-1">Contact details are hidden</p>
                                            <p class="text-xs text-zinc-400">{{ $activePlan->remaining_contacts }} contact views remaining in your plan</p>
                                        </div>
                                        <form method="POST" action="{{ route('properties.unlock-contact', $property) }}">
                                            @csrf
                                            <button type="submit" class="w-full px-4 py-2.5 bg-[#c9a050] hover:bg-[#b08d42] text-white text-sm font-semibold rounded-sm transition-all flex items-center justify-center gap-2">
                                                <i class="ph ph-lock-key-open"></i> Unlock Owner Contact
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    {{-- No active plan — prompt to buy --}}
                                    <div class="pt-3 border-t border-stone-200/50">
                                        <div class="text-center p-4 bg-amber-50 border border-amber-200 rounded-sm">
                                            <i class="ph ph-lock text-2xl text-amber-600 mb-2"></i>
                                            <p class="text-sm font-semibold text-zinc-800 mb-1">Contact details locked</p>
                                            <p class="text-xs text-zinc-500 mb-3">Purchase a plan to view owner's phone & email</p>
                                            <a href="{{ route('plans.index') }}" class="inline-flex items-center gap-2 px-5 py-2 bg-[#c9a050] hover:bg-[#b08d42] text-white text-sm font-semibold rounded-sm transition-all">
                                                <i class="ph ph-crown"></i> View Plans
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            @else
                                {{-- Guest — prompt to login --}}
                                <div class="pt-3 border-t border-stone-200/50">
                                    <div class="text-center p-4 bg-stone-100 rounded-sm">
                                        <i class="ph ph-lock text-2xl text-zinc-400 mb-2"></i>
                                        <p class="text-sm text-zinc-500 mb-3">Sign in to view owner contact details</p>
                                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-5 py-2 bg-[#2563EB] text-white text-sm font-semibold rounded-sm transition-all">
                                            Sign In
                                        </a>
                                    </div>
                                </div>
                            @endauth
                        </div>
                        @endif

                        {{-- Edit/Delete for owner --}}
                        @auth
                            @if(auth()->id() === $property->user_id || auth()->user()->isAdmin())
                            <div class="flex gap-2 mb-4">
                                <a href="{{ route('properties.edit', $property) }}" class="flex-1 text-center px-4 py-2.5 bg-stone-50 border border-stone-200/50 text-gray-300 text-sm font-medium rounded-sm hover:bg-stone-100 transition-all">
                                    <i class="ph ph-pencil-simple"></i> Edit
                                </a>
                                <form method="POST" action="{{ route('properties.destroy', $property) }}" class="flex-1" onsubmit="return confirm('Are you sure you want to delete this property?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full px-4 py-2.5 bg-red-500/10 border border-red-500/20 text-red-400 text-sm font-medium rounded-sm hover:bg-red-500/20 transition-all">
                                        <i class="ph ph-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                            @endif
                        @endauth
                    </div>

                    {{-- Inquiry Form --}}
                    <div class="bg-stone-50 border border-stone-200/50 rounded-sm p-6" id="inquiry-form-card">
                        <h3 class="text-lg font-semibold text-zinc-900 mb-4 flex items-center gap-2">
                            <i class="ph ph-chat-dots text-[#2563EB]"></i>
                            Send Inquiry
                        </h3>

                        @auth
                        <form method="POST" action="{{ route('inquiries.store') }}" id="inquiry-form">
                            @csrf
                            <input type="hidden" name="property_id" value="{{ $property->id }}">

                            <div class="space-y-4">
                                <div>
                                    <input type="text" name="name" value="{{ auth()->user()->name }}"
                                           class="w-full px-4 py-3 bg-stone-50 border border-stone-200/50 rounded-sm text-sm text-zinc-900 placeholder-gray-500 focus:outline-none focus:border-[#2563EB]/50 transition-all"
                                           placeholder="Your Name" required id="inquiry-name">
                                </div>
                                <div>
                                    <input type="email" name="email" value="{{ auth()->user()->email }}"
                                           class="w-full px-4 py-3 bg-stone-50 border border-stone-200/50 rounded-sm text-sm text-zinc-900 placeholder-gray-500 focus:outline-none focus:border-[#2563EB]/50 transition-all"
                                           placeholder="Your Email" required id="inquiry-email">
                                </div>
                                <div>
                                    <input type="tel" name="phone" value="{{ auth()->user()->phone }}"
                                           class="w-full px-4 py-3 bg-stone-50 border border-stone-200/50 rounded-sm text-sm text-zinc-900 placeholder-gray-500 focus:outline-none focus:border-[#2563EB]/50 transition-all"
                                           placeholder="Phone (optional)" id="inquiry-phone">
                                </div>
                                <div>
                                    <textarea name="message" rows="4"
                                              class="w-full px-4 py-3 bg-stone-50 border border-stone-200/50 rounded-sm text-sm text-zinc-900 placeholder-gray-500 focus:outline-none focus:border-[#2563EB]/50 transition-all resize-none"
                                              placeholder="I'm interested in this property..." required id="inquiry-message">I'm interested in "{{ $property->title }}". Please share more details.</textarea>
                                </div>
                                <button type="submit" class="w-full px-6 py-3.5 bg-[#2563EB] hover:bg-[#1D4ED8] text-white text-sm font-semibold rounded-sm shadow-sm shadow-[#2563EB]/20 hover:shadow-[#2563EB]/20 transition-all" id="inquiry-submit">
                                    <i class="ph ph-paper-plane-tilt"></i> Send Inquiry
                                </button>
                            </div>

                            @if($errors->any())
                            <div class="mt-4 p-3 bg-red-500/10 border border-red-500/20 rounded-sm">
                                @foreach($errors->all() as $error)
                                    <p class="text-sm text-red-400">{{ $error }}</p>
                                @endforeach
                            </div>
                            @endif
                        </form>
                        @else
                        <div class="text-center py-6">
                            <p class="text-sm text-zinc-500 mb-4">Please sign in to send an inquiry.</p>
                            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#2563EB] text-white text-sm font-semibold rounded-sm transition-all">
                                Sign In
                            </a>
                        </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        {{-- Similar Properties --}}
        @if($similarProperties->count() > 0)
        <div class="mt-16" id="similar-properties">
            <h2 class="text-2xl font-medium text-zinc-900 mb-8">Similar Properties</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($similarProperties as $similar)
                    @include('components.property-card', ['property' => $similar])
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>

@endsection
