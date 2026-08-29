@extends('layouts.app')

@section('title', 'Owner Dashboard - UnlockRentals')

@section('content')

<section class="py-8 lg:py-12 bg-slate-50/50 dark:bg-slate-950 min-h-[calc(100vh-4rem)]" id="owner-dashboard">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        {{-- Top Welcome & Header Bar --}}
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 p-6 sm:p-8 shadow-[0_10px_30px_rgba(15,23,42,0.03)] dark:shadow-[0_10px_30px_rgba(0,0,0,0.2)]">
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
                
                {{-- User Greeting & Badge --}}
                <div class="flex items-center gap-4 sm:gap-5">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center text-2xl sm:text-3xl font-extrabold shadow-lg shadow-blue-600/20 ring-4 ring-blue-50 dark:ring-blue-900/30 flex-shrink-0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white tracking-tight">Owner Dashboard</h1>
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 text-xs font-semibold rounded-full border border-blue-200/80 dark:border-blue-800">
                                <i class="ph-fill ph-shield-check text-blue-600 dark:text-blue-400"></i> Verified Owner
                            </span>
                        </div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            Welcome back, <span class="font-semibold text-slate-800 dark:text-slate-200">{{ auth()->user()->name }}</span>! Here is an overview of your rental properties and inquiries.
                        </p>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-wrap items-center gap-2.5 w-full lg:w-auto">
                    <a href="#" onclick="event.preventDefault(); window.openProfileModal();" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-50 hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-750 dark:text-slate-200 text-sm font-semibold rounded-xl border border-slate-200/90 dark:border-slate-700 shadow-xs transition-all duration-200 cursor-pointer" id="dash-profile-settings" title="Profile Settings">
                        <i class="ph-bold ph-user-gear text-base text-slate-500 dark:text-slate-400"></i>
                        <span>Profile Settings</span>
                    </a>
                    
                    <a href="{{ route('billing.history') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-50 hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-750 dark:text-slate-200 text-sm font-semibold rounded-xl border border-slate-200/90 dark:border-slate-700 shadow-xs transition-all duration-200" id="dash-billing-history" title="Billing History">
                        <i class="ph-bold ph-receipt text-base text-slate-500 dark:text-slate-400"></i>
                        <span>Billing History</span>
                    </a>
                    
                    <a href="{{ route('properties.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-bold rounded-xl shadow-md shadow-blue-600/20 hover:shadow-lg hover:shadow-blue-600/30 transition-all duration-200" id="dash-add-property" title="List New Property">
                        <i class="ph-bold ph-plus-circle text-lg"></i>
                        <span>List New Property</span>
                    </a>
                </div>

            </div>
        </div>

        {{-- Exclusive Custom Offers Banner (If assigned by Admin) --}}
        @if(isset($privateOffers) && $privateOffers->count() > 0)
        <div class="relative overflow-hidden bg-gradient-to-r from-indigo-900 via-blue-900 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl">
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="relative z-10">
                <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur-md flex items-center justify-center text-amber-300 text-xl border border-white/20">
                            <i class="ph-fill ph-sparkle"></i>
                        </div>
                        <div>
                            <span class="inline-block px-2.5 py-0.5 bg-amber-400/20 text-amber-300 text-[11px] font-extrabold uppercase tracking-wider rounded-md border border-amber-400/30 mb-0.5">Exclusive Offer</span>
                            <h2 class="text-xl font-bold tracking-tight text-white">Custom Subscription Plan for You</h2>
                        </div>
                    </div>
                    <p class="text-xs text-blue-200">Specially assigned by UnlockRentals admin</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($privateOffers as $offer)
                        <div class="bg-white/10 backdrop-blur-md p-5 rounded-2xl border border-white/15 flex items-center justify-between gap-4 hover:bg-white/15 transition-all">
                            <div class="flex items-center gap-4 min-w-0">
                                @if($offer->plan->image_path)
                                    <img src="{{ asset('storage/' . $offer->plan->image_path) }}" class="w-14 h-14 object-cover rounded-xl border border-white/20 flex-shrink-0" alt="{{ $offer->plan->name }}">
                                @else
                                    <div class="w-14 h-14 bg-gradient-to-tr from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center text-white text-2xl flex-shrink-0 shadow-inner">
                                        <i class="ph-bold ph-crown"></i>
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <p class="font-bold text-white text-base truncate">{{ $offer->plan->name }}</p>
                                    <div class="flex flex-wrap items-center gap-2 mt-1">
                                        @if($offer->discounted_price)
                                            <span class="line-through text-slate-400 text-xs">{{ $offer->plan->formatted_price }}</span>
                                        @endif
                                        <span class="font-extrabold text-amber-300 text-sm">{{ $offer->formatted_effective_price }}</span>
                                        <span class="text-xs text-blue-200">· {{ $offer->plan->duration_days }} Days · {{ $offer->plan->contact_limit }} Unlocks</span>
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('plans.checkout', $offer->plan) }}" class="px-4 py-2.5 bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-500 hover:to-amber-600 text-slate-950 text-xs font-extrabold rounded-xl shadow-md transition-all whitespace-nowrap flex-shrink-0 flex items-center gap-1.5" title="Claim Offer">
                                <span>Claim Offer</span>
                                <i class="ph-bold ph-arrow-right"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- Key Metrics / Stats Cards Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            
            {{-- Stat 1: My Properties --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-6 shadow-[0_10px_30px_rgba(15,23,42,0.03)] dark:shadow-[0_10px_30px_rgba(0,0,0,0.2)] hover:border-blue-300 dark:hover:border-blue-800 transition-all duration-200 group" id="dash-stat-properties">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform duration-200">
                        <i class="ph-bold ph-buildings"></i>
                    </div>
                    <span class="text-xs font-semibold px-2.5 py-1 bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 rounded-lg">
                        Listings
                    </span>
                </div>
                <p class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">{{ $properties->total() }}</p>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mt-1">Total Listed Properties</p>
            </div>

            {{-- Stat 2: Total Inquiries --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-6 shadow-[0_10px_30px_rgba(15,23,42,0.03)] dark:shadow-[0_10px_30px_rgba(0,0,0,0.2)] hover:border-indigo-300 dark:hover:border-indigo-800 transition-all duration-200 group" id="dash-stat-inquiries">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform duration-200">
                        <i class="ph-bold ph-chat-centered-dots"></i>
                    </div>
                    <a href="{{ route('inquiries.index') }}" class="text-xs font-semibold px-2.5 py-1 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-950/60 dark:hover:bg-indigo-900 text-indigo-700 dark:text-indigo-300 rounded-lg transition-colors flex items-center gap-1">
                        <span>View Inquiries</span>
                        <i class="ph-bold ph-arrow-right"></i>
                    </a>
                </div>
                <p class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">{{ $totalInquiries }}</p>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mt-1">Total Inquiries Received</p>
            </div>

            {{-- Stat 3: Unread Inquiries --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-6 shadow-[0_10px_30px_rgba(15,23,42,0.03)] dark:shadow-[0_10px_30px_rgba(0,0,0,0.2)] hover:border-amber-300 dark:hover:border-amber-800 transition-all duration-200 group" id="dash-stat-unread">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform duration-200">
                        <i class="ph-bold ph-bell-ringing"></i>
                    </div>
                    @if($unreadInquiries > 0)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300 text-xs font-bold rounded-lg border border-amber-200 dark:border-amber-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-ping"></span>
                            Action Required
                        </span>
                    @else
                        <span class="text-xs font-semibold px-2.5 py-1 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 rounded-lg flex items-center gap-1">
                            <i class="ph-bold ph-check"></i> All Caught Up
                        </span>
                    @endif
                </div>
                <p class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">{{ $unreadInquiries }}</p>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mt-1">Unread Inquiries</p>
            </div>

        </div>

        {{-- Properties Management Card --}}
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-[0_10px_30px_rgba(15,23,42,0.03)] dark:shadow-[0_10px_30px_rgba(0,0,0,0.2)] overflow-hidden" id="dash-properties-table">
            
            {{-- Card Header & Filter Bar --}}
            <div class="p-6 sm:px-8 border-b border-slate-100 dark:border-slate-800 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-700 dark:text-slate-300 text-lg">
                        <i class="ph-bold ph-house-line"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">My Properties</h2>
                            <span class="px-2.5 py-0.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-xs font-bold rounded-full">
                                {{ $properties->total() }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Manage availability, edit details, or review status</p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                    {{-- Quick Search Filter --}}
                    <div class="relative flex-1 sm:w-64">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i class="ph-bold ph-magnifying-glass text-sm"></i>
                        </div>
                        <input type="text" 
                               id="property-search-input" 
                               onkeyup="filterPropertiesTable()" 
                               placeholder="Search properties..." 
                               class="w-full pl-9 pr-3.5 py-2 text-xs font-medium bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                    </div>

                    <a href="{{ route('inquiries.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-bold rounded-xl transition-colors" title="View Inquiries">
                        <i class="ph-bold ph-chat-teardrop-dots text-sm"></i>
                        <span>Inquiries</span>
                        @if($unreadInquiries > 0)
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        @endif
                    </a>

                    <a href="{{ route('properties.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-sm hover:shadow transition-all" title="List Property">
                        <i class="ph-bold ph-plus text-sm"></i>
                        <span>Add Property</span>
                    </a>
                </div>
            </div>

            @if($properties->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse" id="properties-main-table">
                    <thead>
                        <tr class="bg-slate-50/80 dark:bg-slate-850/60 border-b border-slate-200/80 dark:border-slate-800">
                            <th class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider px-6 py-4">Property</th>
                            <th class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider px-6 py-4">Category</th>
                            <th class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider px-6 py-4">Price</th>
                            <th class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider px-6 py-4">Status</th>
                            <th class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider px-6 py-4">Availability</th>
                            <th class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                        @foreach($properties as $property)
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors property-row">
                            
                            {{-- Property Column (Image + Title + Location) --}}
                            <td class="px-6 py-4.5">
                                <div class="flex items-center gap-3.5">
                                    <a href="{{ route('properties.show', $property) }}" class="relative group block flex-shrink-0">
                                        @if($property->primaryImageUrl())
                                            <img src="{{ $property->primaryImageUrl() }}" class="w-14 h-14 rounded-xl object-cover border border-slate-200/80 dark:border-slate-700 shadow-xs group-hover:scale-105 transition-transform duration-200" alt="{{ $property->title }}">
                                        @elseif($property->hasVideo())
                                            <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-purple-900 to-indigo-950 border border-purple-700/80 flex flex-col items-center justify-center text-purple-200 shadow-xs group-hover:scale-105 transition-transform duration-200">
                                                <i class="ph-bold ph-video-camera text-xl text-purple-300"></i>
                                                <span class="text-[8px] font-extrabold uppercase mt-0.5 tracking-tight text-purple-200">Video</span>
                                            </div>
                                        @else
                                            <div class="w-14 h-14 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 flex items-center justify-center text-slate-400">
                                                <i class="ph-bold ph-image text-xl"></i>
                                            </div>
                                        @endif
                                    </a>
                                    <div class="min-w-0">
                                        <a href="{{ route('properties.show', $property) }}" class="text-sm font-bold text-slate-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors line-clamp-1 property-title-cell" title="{{ $property->title }}">
                                            {{ $property->title }}
                                        </a>
                                        <div class="flex items-center gap-1 text-xs text-slate-500 dark:text-slate-400 mt-0.5 property-location-cell">
                                            <i class="ph-bold ph-map-pin text-slate-400 dark:text-slate-500 text-xs flex-shrink-0"></i>
                                            <span class="truncate">{{ $property->location ?: ($property->locality ?: 'India') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Type / Category Column --}}
                            <td class="px-6 py-4.5 whitespace-nowrap">
                                @php
                                    $typeKey = strtolower($property->type ?? '');
                                    $typeBadgeStyles = [
                                        'house' => 'bg-violet-50 text-violet-700 border-violet-200/80 dark:bg-violet-950/40 dark:text-violet-300 dark:border-violet-800/60',
                                        'flat' => 'bg-sky-50 text-sky-700 border-sky-200/80 dark:bg-sky-950/40 dark:text-sky-300 dark:border-sky-800/60',
                                        'apartment' => 'bg-sky-50 text-sky-700 border-sky-200/80 dark:bg-sky-950/40 dark:text-sky-300 dark:border-sky-800/60',
                                        'pg-hostel' => 'bg-fuchsia-50 text-fuchsia-700 border-fuchsia-200/80 dark:bg-fuchsia-950/40 dark:text-fuchsia-300 dark:border-fuchsia-800/60',
                                        'pg' => 'bg-fuchsia-50 text-fuchsia-700 border-fuchsia-200/80 dark:bg-fuchsia-950/40 dark:text-fuchsia-300 dark:border-fuchsia-800/60',
                                        'hostel' => 'bg-fuchsia-50 text-fuchsia-700 border-fuchsia-200/80 dark:bg-fuchsia-950/40 dark:text-fuchsia-300 dark:border-fuchsia-800/60',
                                        'commercial' => 'bg-emerald-50 text-emerald-700 border-emerald-200/80 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800/60',
                                        'shop' => 'bg-emerald-50 text-emerald-700 border-emerald-200/80 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800/60',
                                        'office' => 'bg-emerald-50 text-emerald-700 border-emerald-200/80 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800/60',
                                    ];
                                    $style = $typeBadgeStyles[$typeKey] ?? 'bg-slate-100 text-slate-700 border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-lg border {{ $style }}">
                                    {{ ucfirst($property->type) }}
                                </span>
                            </td>

                            {{-- Price Column --}}
                            <td class="px-6 py-4.5 whitespace-nowrap">
                                <div class="text-sm font-extrabold text-slate-900 dark:text-white">
                                    {{ $property->formatted_price }}
                                </div>
                            </td>

                            {{-- Status Column --}}
                            <td class="px-6 py-4.5 whitespace-nowrap">
                                @if($property->status === 'approved')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200/80 dark:border-emerald-800/60 text-xs font-semibold rounded-lg">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Approved
                                    </span>
                                @elseif($property->status === 'pending')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200/80 dark:border-amber-800/60 text-xs font-semibold rounded-lg">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Pending Review
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border border-rose-200/80 dark:border-rose-800/60 text-xs font-semibold rounded-lg">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Rejected
                                    </span>
                                @endif
                            </td>

                            {{-- Booked / Availability Toggle Column --}}
                            <td class="px-6 py-4.5 whitespace-nowrap">
                                <form method="POST" action="{{ route('properties.toggle-booked', $property) }}" class="inline-flex items-center gap-2.5">
                                    @csrf
                                    <label class="relative inline-flex items-center cursor-pointer group" title="{{ $property->is_booked ? 'Click to mark as Available' : 'Click to mark as Booked' }}">
                                        <input type="checkbox" name="is_booked" class="sr-only peer" onchange="this.form.submit()" {{ $property->is_booked ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-slate-200 dark:bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 dark:after:border-slate-600 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600 shadow-inner"></div>
                                    </label>
                                    <span class="text-xs font-bold px-2 py-0.5 rounded-md border {{ $property->is_booked ? 'bg-rose-50 dark:bg-rose-950/50 text-rose-700 dark:text-rose-300 border-rose-200/80 dark:border-rose-800/60' : 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border-emerald-200/80 dark:border-emerald-800/60' }}">
                                        {{ $property->is_booked ? 'Booked' : 'Available' }}
                                    </span>
                                </form>
                            </td>

                            {{-- Actions Column --}}
                            <td class="px-6 py-4.5 text-right whitespace-nowrap">
                                <div class="inline-flex items-center justify-end gap-1.5">
                                    
                                    {{-- View on Public Site --}}
                                    <a href="{{ route('properties.show', $property) }}" class="p-2 text-slate-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-950/50 rounded-xl transition-all" title="View Property Details">
                                        <i class="ph-bold ph-eye text-base"></i>
                                    </a>

                                    {{-- Edit Property --}}
                                    <a href="{{ route('properties.edit', $property) }}" class="p-2 text-slate-500 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-950/50 rounded-xl transition-all" title="Edit Property">
                                        <i class="ph-bold ph-pencil-simple text-base"></i>
                                    </a>

                                    {{-- Delete Property --}}
                                    <form method="POST" action="{{ route('properties.destroy', $property) }}" onsubmit="return confirm('Are you sure you want to delete this property? This action cannot be undone.')" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-500 hover:text-rose-600 dark:text-slate-400 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/50 rounded-xl transition-all cursor-pointer" title="Delete Property">
                                            <i class="ph-bold ph-trash text-base"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination & Footer --}}
            <div class="px-6 sm:px-8 py-5 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                    Showing <span class="font-bold text-slate-800 dark:text-slate-200">{{ $properties->firstItem() ?? 0 }}</span> to <span class="font-bold text-slate-800 dark:text-slate-200">{{ $properties->lastItem() ?? 0 }}</span> of <span class="font-bold text-slate-800 dark:text-slate-200">{{ $properties->total() }}</span> properties
                </p>
                <div class="owner-pagination">
                    {{ $properties->links() }}
                </div>
            </div>

            @else
            {{-- Clean Empty State --}}
            <div class="text-center py-16 sm:py-20 px-4">
                <div class="w-20 h-20 mx-auto rounded-3xl bg-blue-50 dark:bg-blue-950/50 border border-blue-100 dark:border-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-4xl shadow-sm mb-5">
                    <i class="ph-bold ph-house-line"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight mb-1.5">No properties listed yet</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 max-w-md mx-auto mb-6">
                    UnlockRentals connects you directly with thousands of verified tenants with zero brokerage. List your first property in under 2 minutes.
                </p>
                <a href="{{ route('properties.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-bold rounded-xl shadow-md shadow-blue-600/20 hover:shadow-lg hover:shadow-blue-600/30 transition-all duration-200" title="List a Property">
                    <i class="ph-bold ph-plus-circle text-lg"></i>
                    <span>List Your First Property</span>
                </a>
            </div>
            @endif

        </div>

    </div>
</section>

{{-- Interactive Client-side Search Filter Script --}}
<script>
function filterPropertiesTable() {
    const input = document.getElementById('property-search-input');
    const filter = input ? input.value.toLowerCase() : '';
    const rows = document.querySelectorAll('.property-row');

    rows.forEach(row => {
        const titleEl = row.querySelector('.property-title-cell');
        const locationEl = row.querySelector('.property-location-cell');
        const titleText = titleEl ? titleEl.textContent.toLowerCase() : '';
        const locationText = locationEl ? locationEl.textContent.toLowerCase() : '';

        if (titleText.includes(filter) || locationText.includes(filter)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>

@endsection
