@extends('layouts.app')

@section('title', 'Professional Dashboard | UnlockRentals')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-900 pb-20 pt-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Top Greeting & Status Pill --}}
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 border border-slate-200/80 dark:border-slate-700/80 shadow-sm mb-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <img src="{{ $professional->profile_photo_url }}" alt="{{ $professional->business_name }}" class="w-16 h-16 rounded-2xl object-cover border border-slate-200 dark:border-slate-700 flex-shrink-0">
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white">
                                Welcome, {{ $professional->business_name }}
                            </h1>
                            @if($professional->isVerified())
                                <span class="px-2 py-0.5 rounded-full bg-blue-50 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 text-[11px] font-bold flex items-center gap-1">
                                    <i class="ph-fill ph-seal-check text-blue-600"></i> Verified
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Category: <span class="font-bold text-blue-600">{{ $professional->category->name }}</span> • Location: {{ $professional->city }}
                        </p>
                    </div>
                </div>

                {{-- Listing Approval Status --}}
                <div class="flex items-center gap-3">
                    @if($professional->isApproved())
                        <span class="px-3 py-1 rounded-full bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 text-xs font-bold border border-emerald-200 dark:border-emerald-800 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            Live & Active on UnlockRentals
                        </span>
                    @elseif($professional->status === 'pending')
                        <span class="px-3 py-1 rounded-full bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 text-xs font-bold border border-amber-200 dark:border-amber-800 flex items-center gap-1.5">
                            <i class="ph-bold ph-hourglass-medium"></i>
                            Pending Admin Review
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full bg-rose-50 text-rose-700 text-xs font-bold capitalize">
                            Status: {{ $professional->status }}
                        </span>
                    @endif

                    <a href="{{ route('services.show', [$professional->category->slug, Str::slug($professional->city ?: 'india'), $professional->slug]) }}" target="_blank" class="px-3 py-1.5 rounded-xl border border-slate-300 dark:border-slate-600 text-xs font-bold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors flex items-center gap-1">
                        <span>View Public Listing</span>
                        <i class="ph ph-arrow-square-out text-xs"></i>
                    </a>
                </div>
            </div>

            {{-- Profile Completion Meter --}}
            <div class="mt-6 pt-5 border-t border-slate-100 dark:border-slate-700">
                <div class="flex items-center justify-between text-xs font-bold mb-2">
                    <span class="text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
                        <i class="ph-bold ph-chart-line-up text-blue-600"></i>
                        Profile Strength: {{ $completion }}% Completed
                    </span>
                    <span class="text-slate-400 font-normal">Complete your profile to attract more customers</span>
                </div>
                <div class="w-full h-2.5 rounded-full bg-slate-100 dark:bg-slate-700 overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full transition-all duration-500" style="width: {{ $completion }}%"></div>
                </div>
            </div>
        </div>

        {{-- Navigation Menu Tabs --}}
        <div class="flex items-center gap-2 overflow-x-auto pb-4 mb-6 text-xs font-bold">
            <a href="{{ route('professional.dashboard') }}" class="px-4 py-2 rounded-xl bg-blue-600 text-white shadow-sm flex items-center gap-1.5 whitespace-nowrap">
                <i class="ph-bold ph-squares-four text-sm"></i> Overview
            </a>
            <a href="{{ route('professional.leads') }}" class="px-4 py-2 rounded-xl bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 flex items-center gap-1.5 whitespace-nowrap">
                <i class="ph-bold ph-funnel text-sm text-blue-600"></i> Leads & Jobs
                @if($newLeads > 0)
                    <span class="w-5 h-5 rounded-full bg-rose-500 text-white text-[10px] flex items-center justify-center font-black">{{ $newLeads }}</span>
                @endif
            </a>
            <a href="{{ route('professional.profile') }}" class="px-4 py-2 rounded-xl bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 flex items-center gap-1.5 whitespace-nowrap">
                <i class="ph-bold ph-user-circle text-sm text-blue-600"></i> Edit Profile
            </a>
            <a href="{{ route('professional.services') }}" class="px-4 py-2 rounded-xl bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 flex items-center gap-1.5 whitespace-nowrap">
                <i class="ph-bold ph-wrench text-sm text-blue-600"></i> My Services
            </a>
            <a href="{{ route('professional.locations') }}" class="px-4 py-2 rounded-xl bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 flex items-center gap-1.5 whitespace-nowrap">
                <i class="ph-bold ph-map-pin text-sm text-blue-600"></i> Service Areas
            </a>
            <a href="{{ route('professional.photos') }}" class="px-4 py-2 rounded-xl bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 flex items-center gap-1.5 whitespace-nowrap">
                <i class="ph-bold ph-images text-sm text-blue-600"></i> Work Photos
            </a>
            <a href="{{ route('professional.documents') }}" class="px-4 py-2 rounded-xl bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 flex items-center gap-1.5 whitespace-nowrap">
                <i class="ph-bold ph-shield-check text-sm text-blue-600"></i> KYC Documents
            </a>
            <a href="{{ route('professional.availability') }}" class="px-4 py-2 rounded-xl bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 flex items-center gap-1.5 whitespace-nowrap">
                <i class="ph-bold ph-clock text-sm text-blue-600"></i> Availability
            </a>
        </div>

        {{-- 4 Stat Counters --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 shadow-xs">
                <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Profile Views</span>
                <div class="text-2xl font-black text-slate-900 dark:text-white mt-1">{{ number_format($professional->views_count) }}</div>
                <span class="text-[10px] text-blue-600 font-semibold flex items-center gap-1 mt-1">
                    <i class="ph ph-eye"></i> Real customer visits
                </span>
            </div>

            <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 shadow-xs">
                <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Leads Received</span>
                <div class="text-2xl font-black text-slate-900 dark:text-white mt-1">{{ $totalLeads }}</div>
                <span class="text-[10px] text-emerald-600 font-semibold flex items-center gap-1 mt-1">
                    <i class="ph ph-bell"></i> {{ $newLeads }} new pending
                </span>
            </div>

            <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 shadow-xs">
                <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Contact Inquiries</span>
                <div class="text-2xl font-black text-slate-900 dark:text-white mt-1">{{ $professional->call_clicks + $professional->whatsapp_clicks }}</div>
                <span class="text-[10px] text-slate-500 font-semibold flex items-center gap-2 mt-1">
                    <span>📞 {{ $professional->call_clicks }} Calls</span>
                    <span>💬 {{ $professional->whatsapp_clicks }} WhatsApp</span>
                </span>
            </div>

            <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 shadow-xs">
                <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Customer Rating</span>
                <div class="text-2xl font-black text-slate-900 dark:text-white mt-1 flex items-center gap-1">
                    <span>{{ number_format($professional->average_rating, 1) }}</span>
                    <i class="ph-fill ph-star text-amber-500 text-lg"></i>
                </div>
                <span class="text-[10px] text-slate-500 font-semibold flex items-center gap-1 mt-1">
                    Based on {{ $professional->review_count }} verified reviews
                </span>
            </div>
        </div>

        {{-- 2-Column: Recent Leads + Recent Reviews --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Recent Leads Table --}}
            <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200/80 dark:border-slate-700/80 shadow-sm">
                <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100 dark:border-slate-700">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <i class="ph-bold ph-funnel text-blue-600"></i>
                        Recent Leads & Service Requests
                    </h3>
                    <a href="{{ route('professional.leads') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700">
                        View All Leads ({{ $totalLeads }})
                    </a>
                </div>

                @if($recentLeads->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentLeads as $lead)
                            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-700/50 border border-slate-200/60 dark:border-slate-700 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-slate-900 dark:text-white">
                                            {{ $lead->serviceRequest?->service?->name ?? ($lead->serviceRequest?->category?->name ?? 'Service Request') }}
                                        </span>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $lead->status === 'new' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ $lead->status }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-600 dark:text-slate-300 line-clamp-1 mt-1">{{ $lead->serviceRequest?->description }}</p>
                                    <div class="flex items-center gap-3 text-[11px] text-slate-400 mt-1">
                                        <span>📍 {{ $lead->serviceRequest?->city }}</span>
                                        <span>⏰ {{ $lead->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 w-full sm:w-auto">
                                    <a href="{{ route('professional.leads') }}" class="px-3 py-1.5 rounded-lg bg-blue-600 text-white text-xs font-bold whitespace-nowrap">
                                        Manage Lead
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-10 text-slate-400">
                        <i class="ph ph-bell-ringing text-3xl mb-2 block"></i>
                        <p class="text-xs">No leads received yet. Make sure your profile is complete and verified.</p>
                    </div>
                @endif
            </div>

            {{-- Recent Reviews --}}
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200/80 dark:border-slate-700/80 shadow-sm">
                <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100 dark:border-slate-700">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <i class="ph-bold ph-star text-amber-500"></i>
                        Recent Reviews
                    </h3>
                </div>

                @if($recentReviews->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentReviews as $rev)
                            <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-700/40 text-xs">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="font-bold text-slate-800 dark:text-slate-200">{{ $rev->user?->name ?? 'Customer' }}</span>
                                    <div class="flex text-amber-500 text-[10px]">
                                        @for($i=1; $i<=$rev->rating; $i++) <i class="ph-fill ph-star"></i> @endfor
                                    </div>
                                </div>
                                <p class="text-slate-600 dark:text-slate-300 text-[11px] line-clamp-2">{{ $rev->review }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-10 text-slate-400">
                        <i class="ph ph-chats text-3xl mb-2 block"></i>
                        <p class="text-xs">No reviews received yet.</p>
                    </div>
                @endif
            </div>

        </div>

    </div>
</div>
@endsection
