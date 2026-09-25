@extends('layouts.admin')

@section('title', 'Review ' . $professional->business_name . ' | UnlockRentals CRM')

@section('content')
<div class="p-6 space-y-6">

    {{-- Breadcrumb & Back --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center text-xs text-slate-500 gap-2">
            <a href="{{ route('admin.professionals.index') }}" class="hover:text-blue-600">Professionals Directory</a>
            <i class="ph ph-caret-right text-[10px]"></i>
            <span class="text-slate-900 dark:text-white font-medium">{{ $professional->business_name }}</span>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('services.show', [$professional->category->slug, Str::slug($professional->city ?: 'india'), $professional->slug]) }}" target="_blank" class="px-3 py-1.5 rounded-xl border border-slate-300 dark:border-slate-600 text-xs font-bold text-slate-700 dark:text-slate-200 flex items-center gap-1">
                <span>View Public Page</span>
                <i class="ph ph-arrow-square-out text-xs"></i>
            </a>
            <a href="{{ route('admin.professionals.index') }}" class="px-3 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-700 text-xs font-bold text-slate-700 dark:text-slate-200">
                Back to List
            </a>
        </div>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 text-emerald-800 text-xs font-semibold flex items-center gap-2">
            <i class="ph-fill ph-check-circle text-base text-emerald-600"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Top Action & Status Card --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200/80 dark:border-slate-700 shadow-xs flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <img src="{{ $professional->profile_photo_url }}" class="w-16 h-16 rounded-2xl object-cover border border-slate-200 dark:border-slate-700 flex-shrink-0">
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl font-black text-slate-900 dark:text-white">{{ $professional->business_name }}</h1>
                    <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase {{ $professional->status === 'approved' ? 'bg-emerald-100 text-emerald-800' : ($professional->status === 'pending' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
                        {{ $professional->status }}
                    </span>
                    @if($professional->isVerified())
                        <span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-800 text-[10px] font-bold flex items-center gap-1">
                            <i class="ph-bold ph-check"></i> Verified
                        </span>
                    @endif
                </div>
                <p class="text-xs text-slate-500 mt-1">
                    Contact: <strong>{{ $professional->full_name }}</strong> • Phone: <a href="tel:{{ $professional->phone }}" class="text-blue-600 font-bold">{{ $professional->phone }}</a> • Email: {{ $professional->email }}
                </p>
            </div>
        </div>

        {{-- Admin Action Buttons --}}
        <div class="flex flex-wrap items-center gap-2">
            @if($professional->status !== 'approved')
                <form action="{{ route('admin.professionals.approve', $professional->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm">
                        ✓ Approve Listing
                    </button>
                </form>
            @endif

            <form action="{{ route('admin.professionals.toggle-verify', $professional->id) }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-sm">
                    {{ $professional->isVerified() ? 'Remove Verified Badge' : '✓ Grant Verified Badge' }}
                </button>
            </form>

            <form action="{{ route('admin.professionals.toggle-featured', $professional->id) }}" method="POST">
                @csrf
                <button type="submit" class="px-3.5 py-2 rounded-xl border border-amber-300 bg-amber-50 text-amber-800 text-xs font-bold">
                    {{ $professional->featured ? 'Remove Featured' : '⭐ Mark Featured' }}
                </button>
            </form>

            @if($professional->status !== 'suspended')
                <form action="{{ route('admin.professionals.suspend', $professional->id) }}" method="POST" onsubmit="return confirm('Suspend this professional listing?')">
                    @csrf
                    <button type="submit" class="px-3.5 py-2 rounded-xl border border-rose-300 bg-rose-50 text-rose-800 text-xs font-bold">
                        Suspend
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- 2-Column: Details & Verification Documents --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left 2 Columns: Overview, Description, Photos, Services --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Profile Details --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200/80 dark:border-slate-700 shadow-xs space-y-4">
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">Profile Details</h3>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-xs">
                    <div>
                        <span class="text-slate-400 block">Category</span>
                        <strong class="text-slate-900 dark:text-white">{{ $professional->category->name }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block">Experience</span>
                        <strong class="text-slate-900 dark:text-white">{{ $professional->years_experience }} Years</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block">Pricing</span>
                        <strong class="text-slate-900 dark:text-white">{{ $professional->formatted_price }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block">City & State</span>
                        <strong class="text-slate-900 dark:text-white">{{ $professional->city }}, {{ $professional->state }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block">Locality & Pincode</span>
                        <strong class="text-slate-900 dark:text-white">{{ $professional->locality }} ({{ $professional->pincode }})</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block">Service Radius</span>
                        <strong class="text-slate-900 dark:text-white">{{ $professional->service_radius_km ?? 20 }} km</strong>
                    </div>
                </div>

                <div class="pt-2 border-t border-slate-100 dark:border-slate-700">
                    <span class="text-slate-400 text-xs block mb-1">Description</span>
                    <p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed whitespace-pre-line">{{ $professional->description }}</p>
                </div>
            </div>

            {{-- Services Offered --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200/80 dark:border-slate-700 shadow-xs">
                <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-3">Services Offered ({{ $professional->services->count() }})</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($professional->services as $serv)
                        <span class="px-3 py-1 rounded-xl bg-slate-100 dark:bg-slate-700 text-xs font-semibold text-slate-800 dark:text-slate-200">
                            {{ $serv->name }}
                        </span>
                    @endforeach
                </div>
            </div>

            {{-- Work Photos --}}
            @if($professional->photos->count() > 0)
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200/80 dark:border-slate-700 shadow-xs">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-3">Work Photos Gallery</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        @foreach($professional->photos as $photo)
                            <div class="rounded-xl overflow-hidden aspect-square border border-slate-200 dark:border-slate-600 bg-slate-100">
                                <img src="{{ $photo->image_url }}" class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

        {{-- Right Column: Confidential KYC Documents --}}
        <div class="space-y-6">

            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200/80 dark:border-slate-700 shadow-xs">
                <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-3 flex items-center gap-2">
                    <i class="ph-bold ph-shield-check text-blue-600"></i>
                    Confidential KYC Documents
                </h3>

                @if($professional->documents->count() > 0)
                    <div class="space-y-4">
                        @foreach($professional->documents as $doc)
                            <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-200/80 dark:border-slate-600 space-y-3">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-xs font-bold text-slate-900 dark:text-white block">{{ $doc->document_type }}</span>
                                        <span class="text-[11px] text-slate-400">{{ $doc->document_number ?: 'No ID number recorded' }}</span>
                                    </div>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $doc->status === 'verified' ? 'bg-emerald-100 text-emerald-800' : ($doc->status === 'rejected' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800') }}">
                                        {{ $doc->status }}
                                    </span>
                                </div>

                                <div class="flex items-center gap-2">
                                    {{-- Secure Download Link --}}
                                    <a href="{{ route('admin.professionals.documents.download', $doc->id) }}" class="flex-1 inline-flex items-center justify-center gap-1.5 py-1.5 px-3 rounded-lg bg-blue-600 text-white text-xs font-bold hover:bg-blue-700">
                                        <i class="ph-bold ph-download-simple"></i> Download File
                                    </a>
                                </div>

                                {{-- Document Verify / Reject Actions --}}
                                <div class="pt-2 border-t border-slate-200 dark:border-slate-600 flex items-center gap-2">
                                    <form action="{{ route('admin.professionals.documents.verify', $doc->id) }}" method="POST" class="flex-1">
                                        @csrf
                                        <input type="hidden" name="status" value="verified">
                                        <button type="submit" class="w-full py-1 rounded-lg bg-emerald-600 text-white text-[11px] font-bold">
                                            ✓ Verify
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.professionals.documents.verify', $doc->id) }}" method="POST" class="flex-1">
                                        @csrf
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="w-full py-1 rounded-lg bg-rose-600 text-white text-[11px] font-bold">
                                            ✗ Reject
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-slate-400">No verification documents submitted by this professional.</p>
                @endif
            </div>

            {{-- Audit Stats --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200/80 dark:border-slate-700 shadow-xs space-y-2 text-xs">
                <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-2">Listing Statistics</h3>
                <div class="flex justify-between py-1 border-b border-slate-100 dark:border-slate-700">
                    <span class="text-slate-500">Profile Views:</span>
                    <strong class="text-slate-800 dark:text-slate-200">{{ $professional->views_count }}</strong>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-100 dark:border-slate-700">
                    <span class="text-slate-500">Call Button Clicks:</span>
                    <strong class="text-slate-800 dark:text-slate-200">{{ $professional->call_clicks }}</strong>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-100 dark:border-slate-700">
                    <span class="text-slate-500">WhatsApp Clicks:</span>
                    <strong class="text-slate-800 dark:text-slate-200">{{ $professional->whatsapp_clicks }}</strong>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-100 dark:border-slate-700">
                    <span class="text-slate-500">Total Leads Received:</span>
                    <strong class="text-slate-800 dark:text-slate-200">{{ $professional->lead_count }}</strong>
                </div>
                <div class="flex justify-between py-1">
                    <span class="text-slate-500">Average Rating:</span>
                    <strong class="text-amber-500 font-bold">⭐ {{ number_format($professional->average_rating, 1) }} ({{ $professional->review_count }})</strong>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
