@extends('layouts.app')

@section('title', $professional->business_name . ' - ' . $professional->category->name . ' in ' . ($professional->locality ? $professional->locality . ', ' : '') . $professional->city . ' | UnlockRentals')
@section('meta_description', 'Hire ' . $professional->business_name . ', verified ' . $professional->category->name . ' in ' . $professional->city . '. ' . $professional->years_experience . ' years experience. Direct call and WhatsApp on UnlockRentals.')

@section('content')
{{-- Structured Data for SEO --}}
@if(isset($schemaData))
<script type="application/ld+json">
{!! json_encode($schemaData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endif

<div class="min-h-screen bg-slate-50 dark:bg-slate-900 pb-20 pt-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Breadcrumbs --}}
        <nav class="flex items-center text-xs text-slate-500 dark:text-slate-400 mb-6 gap-2 flex-wrap" aria-label="Breadcrumb">
            <a href="{{ url('/') }}" class="hover:text-blue-600 transition-colors">Home</a>
            <i class="ph ph-caret-right text-[10px]"></i>
            <a href="{{ route('services.index') }}" class="hover:text-blue-600 transition-colors">Local Professionals</a>
            <i class="ph ph-caret-right text-[10px]"></i>
            <a href="{{ route('services.category', $professional->category->slug) }}" class="hover:text-blue-600 transition-colors">{{ $professional->category->name }}</a>
            <i class="ph ph-caret-right text-[10px]"></i>
            <a href="{{ route('services.city', [$professional->category->slug, Str::slug($professional->city ?: 'india')]) }}" class="hover:text-blue-600 transition-colors">{{ $professional->city }}</a>
            <i class="ph ph-caret-right text-[10px]"></i>
            <span class="text-slate-900 dark:text-white font-medium truncate max-w-xs">{{ $professional->business_name }}</span>
        </nav>

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-sm font-semibold flex items-center gap-2">
                <i class="ph-fill ph-check-circle text-lg text-emerald-600"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 text-sm font-semibold flex items-center gap-2">
                <i class="ph-fill ph-warning-circle text-lg text-rose-600"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- Top Profile Card --}}
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 border border-slate-200/80 dark:border-slate-700/80 shadow-sm mb-8">
            <div class="flex flex-col lg:flex-row items-start justify-between gap-6">

                {{-- Left: Profile Image & Basic Info --}}
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5 text-center sm:text-left">
                    <div class="relative w-28 h-28 sm:w-32 sm:h-32 rounded-3xl overflow-hidden bg-slate-100 dark:bg-slate-700 border-2 border-slate-200 dark:border-slate-600 shadow-md flex-shrink-0">
                        <img src="{{ $professional->profile_photo_url }}" alt="{{ $professional->business_name }}" class="w-full h-full object-cover">
                        @if($professional->isVerified())
                            <span class="absolute bottom-2 right-2 w-7 h-7 rounded-full bg-blue-600 text-white flex items-center justify-center shadow-md ring-2 ring-white dark:ring-slate-800" title="Verified Professional">
                                <i class="ph-bold ph-check text-sm"></i>
                            </span>
                        @endif
                    </div>

                    <div class="space-y-2">
                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">
                                {{ $professional->business_name }}
                            </h1>

                            @if($professional->isVerified())
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-xs font-bold border border-blue-200 dark:border-blue-800">
                                    <i class="ph-fill ph-seal-check text-blue-600"></i>
                                    Verified Professional
                                </span>
                            @endif

                            @if($professional->featured)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 text-xs font-bold border border-amber-200 dark:border-amber-800">
                                    <i class="ph-fill ph-star text-amber-500"></i>
                                    Featured
                                </span>
                            @endif
                        </div>

                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 sm:gap-3 text-xs sm:text-sm text-slate-500 dark:text-slate-400">
                            <span class="font-bold text-blue-600 dark:text-blue-400">{{ $professional->category->name }}</span>
                            <span>•</span>
                            <div class="flex items-center gap-1">
                                <i class="ph-bold ph-map-pin text-slate-400"></i>
                                <span class="text-slate-700 dark:text-slate-300 font-medium">
                                    {{ $professional->locality ? $professional->locality . ', ' : '' }}{{ $professional->city }}
                                </span>
                            </div>
                            @if($professional->years_experience > 0)
                                <span>•</span>
                                <span class="text-slate-700 dark:text-slate-300 font-semibold">
                                    {{ $professional->years_experience }} Years Experience
                                </span>
                            @endif
                        </div>

                        {{-- Rating Line --}}
                        <div class="flex items-center justify-center sm:justify-start gap-2 pt-1">
                            <div class="flex items-center gap-1 text-amber-500 text-sm">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($professional->average_rating))
                                        <i class="ph-fill ph-star"></i>
                                    @elseif($i - $professional->average_rating < 1 && $i - $professional->average_rating > 0)
                                        <i class="ph-fill ph-star-half"></i>
                                    @else
                                        <i class="ph ph-star text-slate-300 dark:text-slate-600"></i>
                                    @endif
                                @endfor
                            </div>
                            <span class="text-sm font-black text-slate-900 dark:text-white">{{ number_format($professional->average_rating, 1) }}</span>
                            <span class="text-xs text-slate-400">({{ $professional->review_count }} {{ Str::plural('Customer Review', $professional->review_count) }})</span>
                        </div>

                        {{-- Badges (Home Visit, Available Today, Emergency) --}}
                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 pt-1">
                            @if($professional->home_visit)
                                <span class="px-2.5 py-1 rounded-lg bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 text-xs font-semibold flex items-center gap-1">
                                    <i class="ph-bold ph-check text-emerald-600"></i> Home Visit
                                </span>
                            @endif
                            @if($professional->available_today)
                                <span class="px-2.5 py-1 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-xs font-semibold flex items-center gap-1">
                                    <i class="ph-bold ph-lightning text-blue-600"></i> Available Today
                                </span>
                            @endif
                            @if($professional->emergency_service)
                                <span class="px-2.5 py-1 rounded-lg bg-rose-50 dark:bg-rose-900/30 text-rose-700 dark:text-rose-300 text-xs font-semibold flex items-center gap-1">
                                    <i class="ph-bold ph-shield-warning text-rose-600"></i> 24x7 Emergency Service
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Right: Direct Contact Actions & Pricing --}}
                <div class="w-full lg:w-72 flex-shrink-0 bg-slate-50 dark:bg-slate-700/50 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-700 flex flex-col justify-between gap-4">
                    <div>
                        <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Starting Price</span>
                        <div class="text-2xl font-black text-slate-900 dark:text-white">
                            {{ $professional->formatted_price }}
                        </div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Final quote after inspecting service details</p>
                    </div>

                    <div class="space-y-2.5">
                        {{-- Call Button --}}
                        <a href="{{ route('professionals.click', [$professional->id, 'call']) }}" class="w-full inline-flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-md shadow-emerald-600/25 transition-all active:scale-95">
                            <i class="ph-bold ph-phone-call text-base"></i>
                            <span>Call Now ({{ $professional->phone }})</span>
                        </a>

                        {{-- WhatsApp Button --}}
                        <a href="{{ route('professionals.click', [$professional->id, 'whatsapp']) }}" target="_blank" rel="noopener" class="w-full inline-flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-[#25D366] hover:bg-[#20ba59] text-white font-bold text-sm shadow-md shadow-[#25D366]/25 transition-all active:scale-95">
                            <i class="ph-bold ph-whatsapp-logo text-base"></i>
                            <span>Chat on WhatsApp</span>
                        </a>

                        {{-- Request Service CTA Modal Trigger --}}
                        <button type="button" onclick="openServiceRequestModal()" class="w-full inline-flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-sm transition-all active:scale-95">
                            <i class="ph-bold ph-paper-plane-tilt text-sm"></i>
                            <span>Request Service / Quote</span>
                        </button>

                        {{-- Save / Bookmark & Report --}}
                        <div class="flex items-center justify-between pt-2 border-t border-slate-200 dark:border-slate-600">
                            <button type="button" id="btn-favorite" onclick="toggleFavorite({{ $professional->id }})" class="inline-flex items-center gap-1.5 text-xs font-semibold {{ $isFavorited ? 'text-rose-600 font-bold' : 'text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white' }} transition-colors">
                                <i class="ph{{ $isFavorited ? '-fill' : '' }} ph-heart text-base text-rose-500"></i>
                                <span id="fav-label">{{ $isFavorited ? 'Saved' : 'Save' }}</span>
                            </button>

                            <button type="button" onclick="openReportModal()" class="inline-flex items-center gap-1 text-[11px] text-slate-400 hover:text-rose-500 transition-colors">
                                <i class="ph ph-flag"></i>
                                <span>Report Listing</span>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Main Content 2-Column Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Left Column: Details, Services, Photos, Reviews --}}
            <div class="lg:col-span-2 space-y-8">

                {{-- About Section --}}
                <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 border border-slate-200/80 dark:border-slate-700/80 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                        <i class="ph-bold ph-info text-blue-600"></i>
                        About {{ $professional->business_name }}
                    </h2>
                    <div class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed space-y-3 whitespace-pre-line">
                        {{ $professional->description }}
                    </div>
                </div>

                {{-- Services Offered --}}
                <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 border border-slate-200/80 dark:border-slate-700/80 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                        <i class="ph-bold ph-wrench text-blue-600"></i>
                        Services Offered ({{ $professional->services->count() }})
                    </h2>

                    @if($professional->services->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($professional->services as $serv)
                                <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-700/50 border border-slate-200/60 dark:border-slate-700 flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-300 flex items-center justify-center flex-shrink-0">
                                        <i class="ph-bold ph-check text-sm"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-bold text-slate-900 dark:text-white">{{ $serv->name }}</h4>
                                        @if($serv->description)
                                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 line-clamp-2">{{ $serv->description }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs text-slate-500">General {{ $professional->category->name }} services.</p>
                    @endif
                </div>

                {{-- Work Photos Gallery --}}
                @if($professional->photos->count() > 0)
                    <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 border border-slate-200/80 dark:border-slate-700/80 shadow-sm">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                            <i class="ph-bold ph-images text-blue-600"></i>
                            Work Photos ({{ $professional->photos->count() }})
                        </h2>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @foreach($professional->photos as $photo)
                                <div class="group relative rounded-2xl overflow-hidden bg-slate-100 dark:bg-slate-700 aspect-square border border-slate-200 dark:border-slate-600">
                                    <img src="{{ $photo->image_url }}" alt="{{ $photo->caption ?: 'Work photo' }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                                    @if($photo->caption)
                                        <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-slate-950/80 to-transparent p-2 text-white text-[11px] font-medium truncate">
                                            {{ $photo->caption }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Customer Reviews Section --}}
                <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 border border-slate-200/80 dark:border-slate-700/80 shadow-sm" id="reviews-section">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100 dark:border-slate-700">
                        <div>
                            <h2 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                <i class="ph-bold ph-star text-amber-500"></i>
                                Customer Reviews & Ratings
                            </h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Real verified customer feedback</p>
                        </div>

                        <button type="button" onclick="document.getElementById('write-review-form').classList.toggle('hidden')" class="px-4 py-2 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-300 hover:bg-blue-100 text-xs font-bold transition-all">
                            Write a Review
                        </button>
                    </div>

                    {{-- Review Submission Form --}}
                    <div id="write-review-form" class="hidden mb-8 p-5 rounded-2xl bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-700">
                        <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-3">Leave a Review for {{ $professional->business_name }}</h4>
                        
                        @auth
                            <form action="{{ route('professionals.review', $professional->id) }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Your Rating (1 to 5 Stars) *</label>
                                    <select name="rating" required class="w-full sm:w-48 text-xs font-semibold rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-800 dark:text-white">
                                        <option value="5">⭐⭐⭐⭐⭐ (5 - Excellent)</option>
                                        <option value="4">⭐⭐⭐⭐ (4 - Very Good)</option>
                                        <option value="3">⭐⭐⭐ (3 - Good)</option>
                                        <option value="2">⭐⭐ (2 - Average)</option>
                                        <option value="1">⭐ (1 - Poor)</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Review Headline *</label>
                                    <input type="text" name="title" required placeholder="e.g. Excellent service, arrived on time!" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-800 dark:text-white">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Detailed Feedback *</label>
                                    <textarea name="review" rows="3" required placeholder="Describe your experience with this professional's quality, pricing, and punctuality..." class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-800 dark:text-white"></textarea>
                                </div>

                                <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-sm transition-all">
                                    Submit Review
                                </button>
                            </form>
                        @else
                            <div class="text-center py-4">
                                <p class="text-xs text-slate-600 dark:text-slate-300 mb-3">Please sign in to your UnlockRentals account to share your feedback.</p>
                                <a href="{{ route('login') }}" onclick="event.preventDefault(); window.openAuthModal('login');" class="inline-flex px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition-all">
                                    Sign In to Review
                                </a>
                            </div>
                        @endauth
                    </div>

                    {{-- List of Reviews --}}
                    @if($professional->approvedReviews->count() > 0)
                        <div class="space-y-4">
                            @foreach($professional->approvedReviews as $review)
                                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-700/40 border border-slate-100 dark:border-slate-700/60">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-2">
                                            <div class="w-7 h-7 rounded-full bg-blue-100 dark:bg-slate-600 text-blue-600 dark:text-blue-300 flex items-center justify-center font-bold text-xs">
                                                {{ substr($review->user?->name ?? 'User', 0, 1) }}
                                            </div>
                                            <div>
                                                <span class="text-xs font-bold text-slate-900 dark:text-white">{{ $review->user?->name ?? 'Verified Customer' }}</span>
                                                <span class="text-[10px] text-slate-400 block">{{ $review->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>

                                        <div class="flex items-center text-amber-500 text-xs">
                                            @for($i=1; $i<=$review->rating; $i++)
                                                <i class="ph-fill ph-star"></i>
                                            @endfor
                                        </div>
                                    </div>

                                    @if($review->title)
                                        <h5 class="text-xs font-bold text-slate-800 dark:text-slate-200 mb-1">{{ $review->title }}</h5>
                                    @endif
                                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">{{ $review->review }}</p>

                                    @if($review->admin_response)
                                        <div class="mt-3 p-2.5 rounded-xl bg-blue-50/50 dark:bg-blue-900/20 text-xs border-l-2 border-blue-500">
                                            <span class="font-bold text-blue-600 block text-[11px]">Professional Response:</span>
                                            <p class="text-slate-600 dark:text-slate-300 text-[11px] mt-0.5">{{ $review->admin_response }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-slate-400">
                            <i class="ph ph-chat-circle-dots text-3xl mb-2 block"></i>
                            <p class="text-xs">No reviews submitted yet. Be the first to review {{ $professional->business_name }}!</p>
                        </div>
                    @endif
                </div>

            </div>

            {{-- Right Column: Areas, Availability, Trust, Related --}}
            <div class="space-y-6">

                {{-- Service Areas & Coverage --}}
                <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200/80 dark:border-slate-700/80 shadow-sm">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-3 flex items-center gap-2">
                        <i class="ph-bold ph-navigation-arrow text-blue-600"></i>
                        Service Coverage
                    </h3>

                    <div class="space-y-2 text-xs">
                        <div class="flex items-center justify-between py-1.5 border-b border-slate-100 dark:border-slate-700">
                            <span class="text-slate-500">Primary City:</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200">{{ $professional->city }}</span>
                        </div>
                        @if($professional->locality)
                            <div class="flex items-center justify-between py-1.5 border-b border-slate-100 dark:border-slate-700">
                                <span class="text-slate-500">Primary Locality:</span>
                                <span class="font-bold text-slate-800 dark:text-slate-200">{{ $professional->locality }}</span>
                            </div>
                        @endif
                        <div class="flex items-center justify-between py-1.5 border-b border-slate-100 dark:border-slate-700">
                            <span class="text-slate-500">Service Radius:</span>
                            <span class="font-bold text-blue-600">Up to {{ $professional->service_radius_km ?? 20 }} km</span>
                        </div>
                    </div>

                    @if($professional->locations->count() > 0)
                        <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-700">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-2">Covered Localities</span>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($professional->locations as $loc)
                                    <span class="px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-700 text-[11px] font-medium text-slate-700 dark:text-slate-300">
                                        {{ $loc->locality ?: $loc->city }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Working Hours Schedule --}}
                <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200/80 dark:border-slate-700/80 shadow-sm">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-3 flex items-center gap-2">
                        <i class="ph-bold ph-clock text-blue-600"></i>
                        Working Hours
                    </h3>

                    @if($professional->availability->count() > 0)
                        <div class="space-y-1.5 text-xs">
                            @foreach($professional->availability as $sched)
                                <div class="flex items-center justify-between py-1">
                                    <span class="text-slate-600 dark:text-slate-400 font-medium">{{ $sched->day_of_week }}</span>
                                    @if($sched->is_available)
                                        <span class="font-bold text-slate-800 dark:text-slate-200">{{ substr($sched->start_time, 0, 5) }} - {{ substr($sched->end_time, 0, 5) }}</span>
                                    @else
                                        <span class="text-rose-500 font-bold">Closed</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-xs text-slate-500 space-y-1">
                            <p>Monday - Saturday: 09:00 AM - 08:00 PM</p>
                            <p>Sunday: On Call / Emergency</p>
                        </div>
                    @endif
                </div>

                {{-- Trust & Safety Checklist --}}
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-slate-800 dark:to-slate-800/90 rounded-3xl p-6 border border-blue-100 dark:border-slate-700 shadow-sm">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-3 flex items-center gap-2">
                        <i class="ph-bold ph-shield-check text-blue-600"></i>
                        Trust & Verification
                    </h3>
                    <ul class="space-y-2 text-xs text-slate-700 dark:text-slate-300">
                        <li class="flex items-center gap-2">
                            <i class="ph-bold ph-check text-blue-600"></i>
                            <span>Phone Verified on Platform</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="ph-bold ph-check text-blue-600"></i>
                            <span>Zero Brokerage / Direct Interaction</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="ph-bold ph-check text-blue-600"></i>
                            <span>Public Reviews & Ratings</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="ph-bold ph-check text-blue-600"></i>
                            <span>Customer Protection & Reporting</span>
                        </li>
                    </ul>
                </div>

                {{-- Related Professionals Nearby --}}
                @if(isset($relatedProfessionals) && $relatedProfessionals->count() > 0)
                    <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200/80 dark:border-slate-700/80 shadow-sm">
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-3 flex items-center gap-2">
                            <i class="ph-bold ph-users text-blue-600"></i>
                            Similar Professionals Nearby
                        </h3>
                        <div class="space-y-3">
                            @foreach($relatedProfessionals as $rel)
                                <a href="{{ route('services.show', [$rel->category->slug, Str::slug($rel->city ?: 'india'), $rel->slug]) }}" class="flex items-center gap-3 p-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/60 transition-colors group">
                                    <img src="{{ $rel->profile_photo_url }}" alt="{{ $rel->business_name }}" class="w-10 h-10 rounded-xl object-cover border border-slate-200 dark:border-slate-600 flex-shrink-0">
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-xs font-bold text-slate-900 dark:text-white truncate group-hover:text-blue-600 transition-colors">{{ $rel->business_name }}</h4>
                                        <div class="flex items-center gap-1.5 text-[11px] text-slate-400">
                                            <span>⭐ {{ number_format($rel->average_rating, 1) }}</span>
                                            <span>•</span>
                                            <span>{{ $rel->city }}</span>
                                        </div>
                                    </div>
                                    <i class="ph ph-caret-right text-xs text-slate-400 group-hover:text-blue-600"></i>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>

{{-- Service Request Modal --}}
<div id="service-request-modal" class="fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white dark:bg-slate-800 rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-slate-200 dark:border-slate-700 relative max-h-[90vh] overflow-y-auto">
        <button type="button" onclick="closeServiceRequestModal()" class="absolute top-5 right-5 p-2 rounded-full text-slate-400 hover:text-slate-600 dark:hover:text-white">
            <i class="ph-bold ph-x text-base"></i>
        </button>

        <div class="mb-5">
            <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">Book Direct Service</span>
            <h3 class="text-xl font-black text-slate-900 dark:text-white">Request Service from {{ $professional->business_name }}</h3>
            <p class="text-xs text-slate-500 mt-1">Submit your requirement. The professional will review your request and get back to you directly.</p>
        </div>

        <form action="{{ route('services.request.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <input type="hidden" name="target_professional_id" value="{{ $professional->id }}">
            <input type="hidden" name="category_id" value="{{ $professional->category_id }}">

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Your Full Name *</label>
                <input type="text" name="name" value="{{ auth()->user()?->name }}" required placeholder="e.g. Rahul Sharma" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Mobile Number *</label>
                    <input type="tel" name="phone" value="{{ auth()->user()?->phone }}" required placeholder="e.g. 9876543210" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Select Specific Service</label>
                    <select name="service_id" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
                        <option value="">General Service Inquiry</option>
                        @foreach($professional->services as $serv)
                            <option value="{{ $serv->id }}">{{ $serv->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Problem Description / Service Details *</label>
                <textarea name="description" rows="3" required placeholder="Describe the issue or required work in detail..." class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white"></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">City / Locality *</label>
                    <input type="text" name="city" value="{{ $professional->city }}" required class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Preferred Date</label>
                    <input type="date" name="preferred_date" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Attach Photos (Optional)</label>
                <input type="file" name="attachments[]" multiple accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full py-3 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-600/25 transition-all">
                    Submit Service Request
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Report Listing Modal --}}
<div id="report-modal" class="fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white dark:bg-slate-800 rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-200 dark:border-slate-700 relative">
        <button type="button" onclick="closeReportModal()" class="absolute top-5 right-5 p-2 rounded-full text-slate-400 hover:text-slate-600 dark:hover:text-white">
            <i class="ph-bold ph-x text-base"></i>
        </button>

        <h3 class="text-lg font-black text-slate-900 dark:text-white mb-2">Report This Listing</h3>
        <p class="text-xs text-slate-500 mb-4">Please let us know why you are reporting this professional profile.</p>

        <form action="{{ route('professionals.report', $professional->id) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Reason for Report *</label>
                <select name="reason" required class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
                    <option value="Fake Profile">Fake Profile / Identity</option>
                    <option value="Wrong Information">Wrong Information / Phone</option>
                    <option value="Fraud">Fraud / Scammer</option>
                    <option value="Abusive Behaviour">Abusive Behaviour</option>
                    <option value="Spam">Spam Listing</option>
                    <option value="Other">Other Reason</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Description / Details *</label>
                <textarea name="description" rows="3" required placeholder="Please provide details of the issue..." class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white"></textarea>
            </div>

            <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-sm transition-all">
                Submit Report
            </button>
        </form>
    </div>
</div>

{{-- Scripts for Modals and AJAX Favorites --}}
<script>
    function openServiceRequestModal() {
        document.getElementById('service-request-modal').classList.remove('hidden');
    }
    function closeServiceRequestModal() {
        document.getElementById('service-request-modal').classList.add('hidden');
    }

    function openReportModal() {
        document.getElementById('report-modal').classList.remove('hidden');
    }
    function closeReportModal() {
        document.getElementById('report-modal').classList.add('hidden');
    }

    function toggleFavorite(profId) {
        fetch(`/professionals/${profId}/favorite`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        })
        .then(response => {
            if (response.status === 401) {
                window.location.href = '{{ route("login") }}';
                return;
            }
            return response.json();
        })
        .then(data => {
            if (data && data.success) {
                const btn = document.getElementById('btn-favorite');
                const label = document.getElementById('fav-label');
                const icon = btn.querySelector('i');

                if (data.favorited) {
                    icon.classList.remove('ph-heart');
                    icon.classList.add('ph-fill', 'ph-heart');
                    label.textContent = 'Saved';
                    btn.classList.add('text-rose-600', 'font-bold');
                } else {
                    icon.classList.remove('ph-fill');
                    icon.classList.add('ph-heart');
                    label.textContent = 'Save';
                    btn.classList.remove('text-rose-600', 'font-bold');
                }
            }
        })
        .catch(err => console.error(err));
    }
</script>
@endsection
