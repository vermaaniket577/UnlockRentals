@extends('layouts.admin')

@section('title', 'Manage Properties - Admin - UnlockRentals')

@section('content')

<section class="py-8 lg:py-12" id="admin-properties">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex items-center justify-between mb-8 flex-wrap gap-4">
            <div>
                <h1 class="text-3xl font-medium text-zinc-900 mb-1">All Properties</h1>
                <p class="text-zinc-500">Manage and review property listings</p>
            </div>
            <div class="flex items-center gap-3">
                {{-- Post Advertise Button --}}
                <button type="button" 
                        onclick="openPostAdvertiseModal()"
                        class="inline-flex items-center gap-2 text-white font-bold text-sm shadow-md transition-all cursor-pointer hover:brightness-110 active:scale-95" 
                        style="background: linear-gradient(180deg, #ea580c 0%, #c2410c 100%) !important; color: #ffffff !important; border: 1px solid #9a3412 !important; padding: 0.55rem 1.05rem; border-radius: 9999px; box-shadow: 0 4px 12px rgba(234, 88, 12, 0.25);"
                        title="List a property advertisement on behalf of a user or client">
                    <i class="ph-bold ph-megaphone text-base" style="color: #ffffff !important;"></i>
                    <span style="color: #ffffff !important; font-weight: 700; font-size: 0.875rem;">+ Post Advertise</span>
                </button>

                <a href="{{ route('admin.dashboard') }}" class="text-sm text-[#2563EB] hover:text-blue-700 font-medium transition-colors flex items-center gap-1" title="Dashboard">
                    <i class="ph ph-arrow-left"></i> Dashboard
                </a>
            </div>
        </div>

        {{-- KPI Top Summary Cards: Total, Booked, Selling, Rentals, Pending --}}
        <div id="properties-kpi-container" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3.5 sm:gap-4 mb-6">
            {{-- Total Properties --}}
            <a href="{{ route('admin.properties') }}" 
               class="p-4 rounded-xl border transition-all duration-200 flex items-center justify-between group cursor-pointer {{ !request()->hasAny(['is_booked', 'purpose', 'status']) ? 'bg-white border-[#2563EB]/40 shadow-sm ring-2 ring-[#2563EB]/20' : 'bg-white border-stone-200 hover:border-stone-300 shadow-xs hover:shadow-sm' }}"
               title="View all properties">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-zinc-400">Total</p>
                    <h3 class="text-2xl font-black text-zinc-900 mt-1">{{ number_format($stats['total'] ?? 0) }}</h3>
                    <span class="text-[11px] font-medium text-zinc-500 mt-0.5 block">All listed units</span>
                </div>
                <div class="w-11 h-11 rounded-xl bg-blue-50 text-[#2563EB] border border-blue-200/60 flex items-center justify-center text-xl shadow-xs group-hover:scale-105 transition-transform shrink-0">
                    <i class="ph-bold ph-buildings"></i>
                </div>
            </a>

            {{-- Booked Properties (Specifically Requested) --}}
            <a href="{{ route('admin.properties', array_merge(request()->except(['page']), ['is_booked' => request('is_booked') === '1' ? null : '1'])) }}" 
               class="p-4 rounded-xl border transition-all duration-200 flex items-center justify-between group cursor-pointer {{ request('is_booked') === '1' ? 'bg-rose-50/70 border-rose-400/80 shadow-sm ring-2 ring-rose-400/20' : 'bg-white border-stone-200 hover:border-rose-200 shadow-xs hover:shadow-sm' }}"
               title="Filter Booked Properties">
                <div>
                    <div class="flex items-center gap-1.5">
                        <p class="text-[11px] font-bold uppercase tracking-wider {{ request('is_booked') === '1' ? 'text-rose-700' : 'text-zinc-400' }}">Booked</p>
                        <span class="px-1.5 py-0.2 rounded-full text-[9px] font-bold {{ request('is_booked') === '1' ? 'bg-rose-200 text-rose-800' : 'bg-rose-50 text-rose-600 border border-rose-200/60' }}">Closed</span>
                    </div>
                    <h3 class="text-2xl font-black {{ request('is_booked') === '1' ? 'text-rose-700' : 'text-zinc-900' }} mt-1">{{ number_format($stats['booked'] ?? 0) }}</h3>
                    <span class="text-[11px] font-medium text-zinc-500 mt-0.5 block">Occupied / Closed</span>
                </div>
                <div class="w-11 h-11 rounded-xl bg-rose-50 text-rose-600 border border-rose-200/60 flex items-center justify-center text-xl shadow-xs group-hover:scale-105 transition-transform shrink-0">
                    <i class="ph-bold ph-handshake"></i>
                </div>
            </a>

            {{-- Selling Properties (Specifically Requested) --}}
            <a href="{{ route('admin.properties', array_merge(request()->except(['page']), ['purpose' => request('purpose') === 'buy' ? null : 'buy'])) }}" 
               class="p-4 rounded-xl border transition-all duration-200 flex items-center justify-between group cursor-pointer {{ (request('purpose') === 'buy' || request('purpose') === 'sell') ? 'bg-emerald-50/70 border-emerald-400/80 shadow-sm ring-2 ring-emerald-400/20' : 'bg-white border-stone-200 hover:border-emerald-200 shadow-xs hover:shadow-sm' }}"
               title="Filter Selling / Sale Properties">
                <div>
                    <div class="flex items-center gap-1.5">
                        <p class="text-[11px] font-bold uppercase tracking-wider {{ (request('purpose') === 'buy' || request('purpose') === 'sell') ? 'text-emerald-700' : 'text-zinc-400' }}">Selling</p>
                        <span class="px-1.5 py-0.2 rounded-full text-[9px] font-bold {{ (request('purpose') === 'buy' || request('purpose') === 'sell') ? 'bg-emerald-200 text-emerald-800' : 'bg-emerald-50 text-emerald-600 border border-emerald-200/60' }}">For Sale</span>
                    </div>
                    <h3 class="text-2xl font-black {{ (request('purpose') === 'buy' || request('purpose') === 'sell') ? 'text-emerald-700' : 'text-zinc-900' }} mt-1">{{ number_format($stats['selling'] ?? 0) }}</h3>
                    <span class="text-[11px] font-medium text-zinc-500 mt-0.5 block">Buy & Sell units</span>
                </div>
                <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-200/60 flex items-center justify-center text-xl shadow-xs group-hover:scale-105 transition-transform shrink-0">
                    <i class="ph-bold ph-tag"></i>
                </div>
            </a>

            {{-- Rental Properties --}}
            <a href="{{ route('admin.properties', array_merge(request()->except(['page']), ['purpose' => request('purpose') === 'rent' ? null : 'rent'])) }}" 
               class="p-4 rounded-xl border transition-all duration-200 flex items-center justify-between group cursor-pointer {{ request('purpose') === 'rent' ? 'bg-indigo-50/70 border-indigo-400/80 shadow-sm ring-2 ring-indigo-400/20' : 'bg-white border-stone-200 hover:border-indigo-200 shadow-xs hover:shadow-sm' }}"
               title="Filter Rental Properties">
                <div>
                    <div class="flex items-center gap-1.5">
                        <p class="text-[11px] font-bold uppercase tracking-wider {{ request('purpose') === 'rent' ? 'text-indigo-700' : 'text-zinc-400' }}">Rentals</p>
                        <span class="px-1.5 py-0.2 rounded-full text-[9px] font-bold {{ request('purpose') === 'rent' ? 'bg-indigo-200 text-indigo-800' : 'bg-indigo-50 text-indigo-600 border border-indigo-200/60' }}">For Rent</span>
                    </div>
                    <h3 class="text-2xl font-black {{ request('purpose') === 'rent' ? 'text-indigo-700' : 'text-zinc-900' }} mt-1">{{ number_format($stats['rent'] ?? 0) }}</h3>
                    <span class="text-[11px] font-medium text-zinc-500 mt-0.5 block">Monthly rental units</span>
                </div>
                <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 border border-indigo-200/60 flex items-center justify-center text-xl shadow-xs group-hover:scale-105 transition-transform shrink-0">
                    <i class="ph-bold ph-key"></i>
                </div>
            </a>

            {{-- Pending Approval --}}
            <a href="{{ route('admin.properties', array_merge(request()->except(['page']), ['status' => request('status') === 'pending' ? null : 'pending'])) }}" 
               class="p-4 rounded-xl border transition-all duration-200 flex items-center justify-between group cursor-pointer {{ request('status') === 'pending' ? 'bg-amber-50/70 border-amber-400/80 shadow-sm ring-2 ring-amber-400/20' : 'bg-white border-stone-200 hover:border-amber-200 shadow-xs hover:shadow-sm' }}"
               title="Filter Pending Review Properties">
                <div>
                    <div class="flex items-center gap-1.5">
                        <p class="text-[11px] font-bold uppercase tracking-wider {{ request('status') === 'pending' ? 'text-amber-700' : 'text-zinc-400' }}">Pending</p>
                        @if(($stats['pending'] ?? 0) > 0)
                            <span class="px-1.5 py-0.2 rounded-full text-[9px] font-bold bg-amber-500 text-white animate-pulse">Action</span>
                        @endif
                    </div>
                    <h3 class="text-2xl font-black {{ request('status') === 'pending' ? 'text-amber-700' : 'text-zinc-900' }} mt-1">{{ number_format($stats['pending'] ?? 0) }}</h3>
                    <span class="text-[11px] font-medium text-zinc-500 mt-0.5 block">Requires review</span>
                </div>
                <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 border border-amber-200/60 flex items-center justify-center text-xl shadow-xs group-hover:scale-105 transition-transform shrink-0">
                    <i class="ph-bold ph-clock-countdown"></i>
                </div>
            </a>
        </div>

        {{-- Status Filters & Bypass Approval Toggle --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div class="flex gap-2 flex-wrap">
                <a href="{{ route('admin.properties') }}" class="px-4 py-2 text-sm font-medium rounded-sm transition-all {{ !request('status') ? 'bg-[#2563EB]/10 text-[#2563EB] border border-[#2563EB]/50' : 'bg-stone-50 text-zinc-500 border border-stone-200/50 hover:bg-stone-100' }}" title="All">
                    All
                </a>
                <a href="{{ route('admin.properties', ['status' => 'pending']) }}" class="px-4 py-2 text-sm font-medium rounded-sm transition-all {{ request('status') === 'pending' ? 'bg-amber-500/20 text-amber-400 border border-amber-500/30' : 'bg-stone-50 text-zinc-500 border border-stone-200/50 hover:bg-stone-100' }}" title="Pending">
                    Pending
                </a>
                <a href="{{ route('admin.properties', ['status' => 'approved']) }}" class="px-4 py-2 text-sm font-medium rounded-sm transition-all {{ request('status') === 'approved' ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-stone-50 text-zinc-500 border border-stone-200/50 hover:bg-stone-100' }}" title="Approved">
                    Approved
                </a>
                <a href="{{ route('admin.properties', ['status' => 'rejected']) }}" class="px-4 py-2 text-sm font-medium rounded-sm transition-all {{ request('status') === 'rejected' ? 'bg-red-500/20 text-red-400 border border-red-500/30' : 'bg-stone-50 text-zinc-500 border border-stone-200/50 hover:bg-stone-100' }}" title="Rejected">
                    Rejected
                </a>
            </div>
            
            <div class="flex items-center gap-3 flex-wrap">
                {{-- Live 1-Minute Auto-Sync Controls --}}
                <div class="flex items-center gap-1.5 bg-white px-2.5 py-1.5 border border-stone-200/60 rounded-sm shadow-sm">
                    <button type="button" 
                            id="liveSyncToggleBtn" 
                            onclick="toggleLiveAutoSync()" 
                            class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 px-2 py-1 rounded transition-colors cursor-pointer"
                            title="Auto-syncs every 60s. Click to pause or resume">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse" id="syncPulseDot"></span>
                        <span id="syncCountdownText">Sync: 60s</span>
                    </button>

                    <button type="button" 
                            id="manualSyncBtn" 
                            onclick="triggerManualSync()" 
                            class="inline-flex items-center gap-1 text-xs font-bold text-white bg-[#2563EB] hover:bg-blue-700 px-2.5 py-1 rounded transition-all shadow-xs disabled:opacity-50"
                            title="Trigger 99acres scraper sync immediately">
                        <i class="ph-bold ph-arrows-clockwise text-xs" id="manualSyncIcon"></i>
                        <span id="manualSyncText">Sync Now</span>
                    </button>
                </div>

                {{-- Direct Auto-Approve Toggle --}}
                <div class="flex items-center gap-3 bg-white px-4 py-2 border border-stone-200/50 rounded-sm shadow-sm">
                    <div class="flex flex-col">
                        <span class="text-xs font-bold text-zinc-800 uppercase tracking-wider">Bypass Approval</span>
                        <span class="text-[10px] text-zinc-400">Post directly to website</span>
                    </div>
                    <form id="bypass-toggle-form" action="{{ route('admin.properties.toggle-bypass') }}" method="POST" class="m-0 p-0 flex items-center">
                        @csrf
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="bypass-approval-checkbox" name="bypass_property_approval" value="1" {{ \App\Models\Setting::get('bypass_property_approval', '0') == '1' ? 'checked' : '' }} class="sr-only peer" onchange="document.getElementById('bypass-toggle-form').submit()">
                            <div class="w-11 h-6 bg-stone-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#2563EB]"></div>
                        </label>
                    </form>
                </div>
            </div>
        </div>

        {{-- Compact Filter Toolbar (UnlockRentals Theme) --}}
        <div class="bg-white border border-stone-200/80 rounded-lg p-2.5 mb-5 shadow-sm">
            <form method="GET" action="{{ route('admin.properties') }}" class="flex flex-wrap items-center gap-2">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                @if(request('purpose'))
                    <input type="hidden" name="purpose" value="{{ request('purpose') }}">
                @endif
                @if(request()->has('is_booked') && request('is_booked') !== '')
                    <input type="hidden" name="is_booked" value="{{ request('is_booked') }}">
                @endif

                {{-- Compact Search Input --}}
                <div class="relative flex-1 min-w-[200px]">
                    <i class="ph ph-magnifying-glass absolute left-2.5 top-1/2 -translate-y-1/2 text-zinc-400 text-xs"></i>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search title, locality, ID..." 
                           class="w-full h-8 pl-8 pr-2.5 text-xs bg-stone-50 hover:bg-stone-50/80 focus:bg-white border border-stone-200 rounded-md text-zinc-800 placeholder-zinc-400 focus:outline-none focus:ring-1 focus:ring-[#2563EB] focus:border-[#2563EB] transition-all">
                </div>

                {{-- Date of Posting Dropdown --}}
                <div class="relative min-w-[130px]">
                    <div class="absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none text-zinc-400 text-xs">
                        <i class="ph ph-calendar"></i>
                    </div>
                    <select name="date_filter" 
                            class="w-full h-8 pl-7 pr-6 text-xs bg-stone-50 hover:bg-stone-50/80 focus:bg-white border border-stone-200 rounded-md text-zinc-700 font-medium focus:outline-none focus:ring-1 focus:ring-[#2563EB] focus:border-[#2563EB] appearance-none cursor-pointer transition-all" 
                            onchange="this.form.submit()">
                        <option value="">All Dates</option>
                        <option value="today" {{ request('date_filter') === 'today' ? 'selected' : '' }}>Today</option>
                        <option value="yesterday" {{ request('date_filter') === 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                        <option value="this_week" {{ request('date_filter') === 'this_week' ? 'selected' : '' }}>This Week</option>
                        <option value="this_month" {{ request('date_filter') === 'this_month' ? 'selected' : '' }}>This Month</option>
                    </select>
                    <i class="ph ph-caret-down absolute right-2 top-1/2 -translate-y-1/2 text-zinc-400 pointer-events-none text-[10px]"></i>
                </div>

                {{-- City Dropdown --}}
                <div class="relative min-w-[130px]">
                    <div class="absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none text-zinc-400 text-xs">
                        <i class="ph ph-map-pin"></i>
                    </div>
                    <select name="location" 
                            class="w-full h-8 pl-7 pr-6 text-xs bg-stone-50 hover:bg-stone-50/80 focus:bg-white border border-stone-200 rounded-md text-zinc-700 font-medium focus:outline-none focus:ring-1 focus:ring-[#2563EB] focus:border-[#2563EB] appearance-none cursor-pointer transition-all" 
                            onchange="this.form.submit()">
                        <option value="">All Cities</option>
                        @foreach($availableLocations ?? [] as $loc)
                            <option value="{{ $loc }}" {{ request('location') === $loc ? 'selected' : '' }}>{{ $loc }}</option>
                        @endforeach
                    </select>
                    <i class="ph ph-caret-down absolute right-2 top-1/2 -translate-y-1/2 text-zinc-400 pointer-events-none text-[10px]"></i>
                </div>

                {{-- Type Dropdown --}}
                <div class="relative min-w-[110px]">
                    <div class="absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none text-zinc-400 text-xs">
                        <i class="ph ph-house"></i>
                    </div>
                    <select name="type" 
                            class="w-full h-8 pl-7 pr-6 text-xs bg-stone-50 hover:bg-stone-50/80 focus:bg-white border border-stone-200 rounded-md text-zinc-700 font-medium focus:outline-none focus:ring-1 focus:ring-[#2563EB] focus:border-[#2563EB] appearance-none cursor-pointer transition-all" 
                            onchange="this.form.submit()">
                        <option value="">All Types</option>
                        <option value="house" {{ request('type') === 'house' ? 'selected' : '' }}>House / Flat</option>
                        <option value="shop" {{ request('type') === 'shop' ? 'selected' : '' }}>Shop</option>
                    </select>
                    <i class="ph ph-caret-down absolute right-2 top-1/2 -translate-y-1/2 text-zinc-400 pointer-events-none text-[10px]"></i>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center gap-1.5 ml-auto">
                    <button type="submit" 
                            class="h-8 px-3.5 bg-[#2563EB] hover:bg-blue-700 text-white text-xs font-semibold rounded-md shadow-xs transition-colors flex items-center gap-1">
                        <i class="ph ph-funnel text-xs"></i>
                        <span>Filter</span>
                    </button>
                    @if(request()->hasAny(['search', 'date_filter', 'location', 'type', 'sort', 'purpose', 'is_booked']))
                    <a href="{{ route('admin.properties') }}" 
                       class="h-8 px-2.5 bg-stone-100 hover:bg-stone-200 text-zinc-600 text-xs font-medium rounded-md transition-colors flex items-center gap-1" 
                       title="Reset Filters">
                        <i class="ph ph-arrow-counter-clockwise text-xs"></i>
                        <span class="hidden sm:inline">Reset</span>
                    </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Properties Table --}}
        <div id="properties-table-container" class="bg-white border border-stone-200 rounded-xl shadow-sm overflow-hidden">
            @if($properties->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-stone-50 border-b border-stone-200">
                        <tr>
                            <th class="text-left text-xs font-bold text-zinc-500 uppercase tracking-wider px-6 py-4">Property</th>
                            <th class="text-left text-xs font-bold text-zinc-500 uppercase tracking-wider px-6 py-4">Owner</th>
                            <th class="text-left text-xs font-bold text-zinc-500 uppercase tracking-wider px-6 py-4">Type</th>
                            <th class="text-left text-xs font-bold text-zinc-500 uppercase tracking-wider px-6 py-4">Price</th>
                            <th class="text-left text-xs font-bold text-zinc-500 uppercase tracking-wider px-6 py-4">Posted</th>
                            <th class="text-left text-xs font-bold text-zinc-500 uppercase tracking-wider px-6 py-4">Booked</th>
                            <th class="text-left text-xs font-bold text-zinc-500 uppercase tracking-wider px-6 py-4">Status</th>
                            <th class="text-center text-xs font-bold text-zinc-500 uppercase tracking-wider px-6 py-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-200/80">
                        @foreach($properties as $property)
                        <tr class="hover:bg-stone-50/50 transition-colors">
                            <td class="px-6 py-5">
                                <div class="flex items-start gap-3">
                                    <div class="flex flex-col items-center shrink-0">
                                        @if($property->primaryImage)
                                            <img src="{{ $property->primaryImage->imageUrl() }}" class="w-11 h-11 rounded-md object-cover border border-stone-200/80 shadow-xs" alt="">
                                        @else
                                            <div class="w-11 h-11 rounded-md bg-stone-100 flex items-center justify-center border border-stone-200/80">
                                                <i class="ph ph-image text-zinc-400 text-sm"></i>
                                            </div>
                                        @endif

                                        {{-- Image Count Attached --}}
                                        @php
                                            $imgCount = $property->images_count ?? $property->images()->count();
                                        @endphp
                                        <span class="inline-flex items-center gap-1 mt-1 px-1.5 py-0.5 rounded-full text-[9px] font-bold {{ $imgCount > 0 ? 'bg-blue-50 text-[#2563EB] border border-blue-200/70' : 'bg-stone-100 text-zinc-400 border border-stone-200/60' }}" title="{{ $imgCount }} image(s) attached">
                                            <i class="ph-bold ph-camera text-[9.5px]"></i>
                                            <span>{{ $imgCount }} {{ Str::plural('img', $imgCount) }}</span>
                                        </span>
                                    </div>
                                    <div class="min-w-0">
                                        <a href="{{ route('properties.show', $property) }}" class="text-sm font-semibold text-zinc-900 hover:text-[#2563EB] transition-colors" title="{{ $property->title }}">{{ Str::limit($property->title, 34) }}</a>
                                        <p class="text-xs text-zinc-500 mt-0.5 flex items-center gap-1">
                                            <i class="ph ph-map-pin text-zinc-400"></i>
                                            <span>{{ $property->locality ? $property->locality . ', ' : '' }}{{ $property->location }}</span>
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-sm font-medium text-zinc-600">{{ $property->owner->name ?? 'N/A' }}</td>
                            <td class="px-6 py-5">
                                <span class="px-3 py-1.5 bg-[#2563EB]/10 text-[#2563EB] border border-[#2563EB]/20 text-xs font-bold uppercase tracking-wider rounded-md">
                                    {{ ucfirst($property->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-sm text-zinc-900 font-bold tracking-tight">{{ $property->formatted_price }}</td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <span class="text-xs font-medium text-zinc-800">
                                        {{ $property->created_at ? $property->created_at->format('d M Y') : '—' }}
                                    </span>
                                    <span class="text-[10px] text-zinc-400">
                                        {{ $property->created_at ? $property->created_at->format('h:i A') . ' (' . $property->created_at->diffForHumans() . ')' : '' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <span class="booked-status-badge text-xs font-bold px-2.5 py-1 rounded-md border {{ $property->is_booked ? 'bg-rose-50 text-rose-700 border-rose-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200' }}">
                                    {{ $property->is_booked ? 'Booked' : 'Available' }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                @php
                                    $statusColors = ['pending' => 'amber', 'approved' => 'emerald', 'rejected' => 'red'];
                                    $color = $statusColors[$property->status] ?? 'gray';
                                @endphp
                                <span class="px-3 py-1.5 bg-{{ $color }}-100 text-{{ $color }}-700 border border-{{ $color }}-200 text-xs font-bold uppercase tracking-wider rounded-md">
                                    {{ ucfirst($property->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center justify-center gap-2">
                                    {{-- Toggle Booked Switch (AJAX) in Actions --}}
                                    <label class="booked-toggle-label" title="{{ $property->is_booked ? 'Booked (Click to mark Available)' : 'Available (Click to mark Booked)' }}">
                                        <input type="checkbox" 
                                               class="booked-toggle-input" 
                                               data-property-id="{{ $property->id }}"
                                               data-toggle-url="{{ route('properties.toggle-booked', $property) }}"
                                               onchange="handleBookedToggle(this)"
                                               {{ $property->is_booked ? 'checked' : '' }}>
                                        <div class="booked-toggle-track">
                                            <div class="booked-toggle-knob"></div>
                                        </div>
                                    </label>

                                    {{-- Update / Edit Property Button in Actions --}}
                                    <a href="{{ route('properties.edit', $property) }}" class="w-8 h-8 flex items-center justify-center bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-md transition-all shadow-sm border border-blue-200/50" title="Update / Edit Property">
                                        <i class="ph-bold ph-pencil-simple text-sm"></i>
                                    </a>

                                    @if($property->status !== 'approved')
                                    <form method="POST" action="{{ route('admin.properties.approve', $property) }}">
                                        @csrf
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center bg-emerald-50 text-emerald-600 hover:bg-emerald-500 hover:text-white rounded-md transition-all shadow-sm border border-emerald-200/50" title="Approve">
                                            <i class="ph-bold ph-check text-sm"></i>
                                        </button>
                                    </form>
                                    @endif

                                    @if($property->status !== 'rejected')
                                    <form method="POST" action="{{ route('admin.properties.reject', $property) }}">
                                        @csrf
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center bg-red-50 text-red-600 hover:bg-red-500 hover:text-white rounded-md transition-all shadow-sm border border-red-200/50" title="Reject">
                                            <i class="ph-bold ph-x text-sm"></i>
                                        </button>
                                    </form>
                                    @endif

                                    <a href="{{ route('properties.show', $property) }}" class="w-8 h-8 flex items-center justify-center bg-zinc-50 text-zinc-500 hover:bg-zinc-800 hover:text-white rounded-md transition-all shadow-sm border border-zinc-200" title="View Property">
                                        <i class="ph-bold ph-eye text-sm"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-stone-200/50">
                {{ $properties->links() }}
            </div>
            @else
            <div class="text-center py-16">
                <i class="ph ph-buildings text-5xl text-gray-700 mb-4"></i>
                <h3 class="text-xl font-semibold text-zinc-500">No properties found</h3>
            </div>
            @endif
        </div>
    </div>

    {{-- ========================================================================= --}}
    {{-- POST ADVERTISE / PROPERTY ON BEHALF OF USER MODAL POPUP                   --}}
    {{-- ========================================================================= --}}
    <div id="postAdvertiseModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm hidden transition-opacity duration-200 opacity-0" role="dialog" aria-modal="true">
        <div id="postAdvertiseModalCard" class="bg-white rounded-3xl border border-slate-200/90 shadow-2xl w-full max-w-lg p-6 sm:p-8 transform scale-95 transition-all duration-200 relative max-h-[92vh] overflow-y-auto">
            
            {{-- Close Button --}}
            <button type="button" onclick="closePostAdvertiseModal()" class="absolute top-5 right-5 text-slate-400 hover:text-slate-600 p-1.5 rounded-full hover:bg-slate-100 transition-colors cursor-pointer" aria-label="Close modal">
                <i class="ph-bold ph-x text-lg"></i>
            </button>

            {{-- Header Icon & Title --}}
            <div class="flex items-center gap-3.5 mb-5">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-orange-500 to-amber-500 text-white flex items-center justify-center text-2xl shadow-md shadow-orange-500/25 ring-4 ring-orange-50 shrink-0">
                    <i class="ph-bold ph-megaphone"></i>
                </div>
                <div>
                    <h3 class="text-xl font-black text-slate-900 tracking-tight">Post Advertise on Behalf of User</h3>
                    <p class="text-xs text-slate-500 mt-0.5">List a property for a client. If they are new, their owner profile will be automatically created!</p>
                </div>
            </div>

            {{-- Segmented Mode Toggle: New Client vs Existing Registered User --}}
            <div class="grid grid-cols-2 p-1 bg-slate-100 rounded-xl mb-5">
                <button type="button" id="tab-ad-new" onclick="switchAdModalTab('new')"
                        class="py-2 text-xs font-bold rounded-lg transition-all text-slate-900 bg-white shadow-xs cursor-pointer">
                    ✨ New Client (Auto-Create)
                </button>
                <button type="button" id="tab-ad-existing" onclick="switchAdModalTab('existing')"
                        class="py-2 text-xs font-semibold rounded-lg transition-all text-slate-500 hover:text-slate-800 cursor-pointer">
                    👤 Existing User
                </button>
            </div>

            {{-- Form that redirects to properties.create with GET params --}}
            <form method="GET" action="{{ route('properties.create') }}" id="postAdvertiseForm" class="space-y-4">
                <input type="hidden" name="behalf_mode" id="modal_ad_behalf_mode" value="new">

                {{-- SECTION: New Client Fields --}}
                <div id="ad-section-new" class="space-y-4">
                    {{-- Client Name --}}
                    <div>
                        <label for="ad_client_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Client / Owner Name <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <i class="ph-bold ph-user absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-base pointer-events-none"></i>
                            <input type="text" name="behalf_name" id="ad_client_name" required placeholder="e.g. Ramesh Sharma"
                                   class="w-full pl-10 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 placeholder:text-slate-400 focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition-all">
                        </div>
                    </div>

                    {{-- Client Mobile Phone --}}
                    <div>
                        <label for="ad_client_phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Mobile Phone Number <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <i class="ph-bold ph-phone absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-base pointer-events-none"></i>
                            <input type="tel" name="behalf_phone" id="ad_client_phone" required placeholder="e.g. 9876543210"
                                   class="w-full pl-10 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 placeholder:text-slate-400 focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition-all">
                        </div>
                    </div>

                    {{-- Client Email (Optional) --}}
                    <div>
                        <label for="ad_client_email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Email Address <span class="text-slate-400 font-normal lowercase text-[11px]">(Optional)</span>
                        </label>
                        <div class="relative">
                            <i class="ph-bold ph-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-base pointer-events-none"></i>
                            <input type="email" name="behalf_email" id="ad_client_email" placeholder="e.g. ramesh@example.com (Optional)"
                                   class="w-full pl-10 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 placeholder:text-slate-400 focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition-all">
                        </div>
                    </div>

                    {{-- Automation Info Badge --}}
                    <div class="p-3 bg-amber-50/70 border border-amber-200/80 rounded-xl text-xs text-amber-900 flex items-start gap-2.5">
                        <i class="ph-bold ph-sparkle text-amber-600 text-base shrink-0 mt-0.5"></i>
                        <div>
                            <strong class="font-bold">Automatic Profile Creation:</strong>
                            When you submit the property, an active Owner account will be created automatically in the User Directory with their phone verified!
                        </div>
                    </div>
                </div>

                {{-- SECTION: Existing User Fields --}}
                <div id="ad-section-existing" class="space-y-4 hidden">
                    <div>
                        <label for="ad_existing_user" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Select Existing User Account <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <select name="behalf_user_id" id="ad_existing_user"
                                    class="w-full pl-3.5 pr-8 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 focus:bg-white focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition-all cursor-pointer">
                                <option value="">-- Choose User from Directory --</option>
                                @php
                                    $dropdownUsers = $allUsersList ?? \App\Models\User::orderBy('name')->select('id', 'name', 'phone', 'email', 'role')->get();
                                @endphp
                                @foreach($dropdownUsers as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->phone ?: $u->email }}) — {{ ucfirst($u->role) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">If the selected user is currently a tenant, their account will be automatically upgraded to Owner upon posting.</p>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" onclick="closePostAdvertiseModal()"
                            class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs transition-colors cursor-pointer"
                            style="background-color: #ffffff !important; color: #334155 !important; border: 1px solid #cbd5e1 !important;">
                        Cancel
                    </button>
                    <button type="submit"
                            class="ur-admin-btn ur-admin-btn-orange px-5 py-2.5 rounded-xl font-bold text-xs shadow-md transition-all flex items-center gap-1.5 cursor-pointer"
                            style="background: linear-gradient(180deg, #ea580c 0%, #c2410c 100%) !important; color: #ffffff !important; border: 1px solid #9a3412 !important; box-shadow: 0 2px 6px rgba(234, 88, 12, 0.35) !important;">
                        <span style="color: #ffffff !important; -webkit-text-fill-color: #ffffff !important; font-weight: 700;">Proceed to Property Form</span>
                        <i class="ph-bold ph-arrow-right text-sm" style="color: #ffffff !important;"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

@push('scripts')
<script>
async function handleBookedToggle(input) {
    const label = input.closest('.booked-toggle-label');
    const url = input.dataset.toggleUrl;
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';
    const newState = input.checked;

    if (label) label.classList.add('is-loading');

    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({})
        });

        const data = await response.json();

        if (response.ok && data.success) {
            input.checked = Boolean(data.is_booked);
            if (label) {
                label.title = data.is_booked 
                    ? 'Booked (Click to mark Available)' 
                    : 'Available (Click to mark Booked)';
            }
            const row = input.closest('tr');
            const badge = row?.querySelector('.booked-status-badge');
            if (badge) {
                if (data.is_booked) {
                    badge.className = 'booked-status-badge text-xs font-bold px-2.5 py-1 rounded-md border bg-rose-50 text-rose-700 border-rose-200';
                    badge.textContent = 'Booked';
                } else {
                    badge.className = 'booked-status-badge text-xs font-bold px-2.5 py-1 rounded-md border bg-emerald-50 text-emerald-700 border-emerald-200';
                    badge.textContent = 'Available';
                }
            }
            showAdminToast(data.message || (data.is_booked ? 'Property marked as Booked' : 'Property marked as Available'), 'success');
        } else {
            throw new Error(data.message || 'Failed to update property status');
        }
    } catch (err) {
        console.error('Toggle error:', err);
        input.checked = !newState;
        showAdminToast(err.message || 'Failed to update status. Please try again.', 'error');
    } finally {
        if (label) label.classList.remove('is-loading');
    }
}

function showAdminToast(message, type = 'success') {
    let container = document.getElementById('admin-ajax-toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'admin-ajax-toast-container';
        container.className = 'fixed bottom-6 right-6 z-[9999] flex flex-col gap-2 pointer-events-none';
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    toast.className = `pointer-events-auto px-4 py-3 rounded-xl shadow-xl border text-sm font-bold flex items-center gap-2.5 transition-all duration-300 transform translate-y-3 opacity-0 ${
        type === 'success' 
            ? 'bg-slate-900 text-white border-slate-700 shadow-slate-900/30' 
            : 'bg-rose-600 text-white border-rose-500 shadow-rose-600/30'
    }`;
    toast.innerHTML = `
        <i class="ph-bold ${type === 'success' ? 'ph-check-circle text-emerald-400' : 'ph-warning-circle text-white'} text-lg"></i>
        <span>${message}</span>
    `;

    container.appendChild(toast);

    requestAnimationFrame(() => {
        toast.classList.remove('translate-y-3', 'opacity-0');
    });

    setTimeout(() => {
        toast.classList.add('opacity-0', 'translate-y-2');
        setTimeout(() => toast.remove(), 300);
    }, 2800);
}

// ==========================================
// Live 1-Minute Property Sync & Update System
// ==========================================
let syncCountdown = 60;
let syncInterval = null;
let syncEnabled = true;
let currentLatestId = {{ $properties->first()->id ?? 0 }};
let currentTotal = {{ $properties->total() ?? $properties->count() }};
let currentLatestUpdated = {{ \App\Models\Property::latest('updated_at')->first()?->updated_at?->timestamp ?? time() }};

function startLiveSync() {
    if (syncInterval) clearInterval(syncInterval);
    syncCountdown = 60;
    
    syncInterval = setInterval(() => {
        if (!syncEnabled) return;
        syncCountdown--;
        
        const countdownEl = document.getElementById('syncCountdownText');
        if (countdownEl) {
            countdownEl.textContent = `Sync: ${syncCountdown}s`;
        }

        if (syncCountdown <= 0) {
            syncCountdown = 60;
            checkPropertyUpdatesSilently();
        }
    }, 1000);
}

function toggleLiveAutoSync() {
    syncEnabled = !syncEnabled;
    const dot = document.getElementById('syncPulseDot');
    const text = document.getElementById('syncCountdownText');
    const btn = document.getElementById('liveSyncToggleBtn');

    if (syncEnabled) {
        syncCountdown = 60;
        if (dot) dot.className = 'w-2 h-2 rounded-full bg-emerald-500 animate-pulse';
        if (text) text.textContent = 'Sync: 60s';
        if (btn) btn.className = 'inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 px-2 py-1 rounded transition-colors cursor-pointer';
        showAdminToast('Live Auto-Sync active (checking every 60s)', 'success');
    } else {
        if (dot) dot.className = 'w-2 h-2 rounded-full bg-zinc-400';
        if (text) text.textContent = 'Sync: Paused';
        if (btn) btn.className = 'inline-flex items-center gap-1.5 text-xs font-semibold text-zinc-600 bg-stone-100 hover:bg-stone-200 border border-stone-200 px-2 py-1 rounded transition-colors cursor-pointer';
        showAdminToast('Live Auto-Sync paused', 'info');
    }
}

async function checkPropertyUpdatesSilently() {
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput && document.activeElement === searchInput) {
        return;
    }

    try {
        const url = `{{ route('admin.properties.sync-check') }}?last_id=${currentLatestId}&total=${currentTotal}&last_updated=${currentLatestUpdated}`;
        const res = await fetch(url);
        const data = await res.json();

        if (data.has_updates) {
            currentLatestId = data.latest_id;
            currentTotal = data.total;
            currentLatestUpdated = data.latest_updated;
            await refreshPropertiesTableSilently();
        }
    } catch (e) {
        console.warn('Sync check warning:', e);
    }
}

async function refreshPropertiesTableSilently() {
    try {
        const res = await fetch(window.location.href, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        const html = await res.text();
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');

        const newContainer = doc.getElementById('properties-table-container');
        const currentContainer = document.getElementById('properties-table-container');

        const newKpi = doc.getElementById('properties-kpi-container');
        const currentKpi = document.getElementById('properties-kpi-container');
        if (newKpi && currentKpi) {
            currentKpi.innerHTML = newKpi.innerHTML;
        }

        if (newContainer && currentContainer) {
            currentContainer.innerHTML = newContainer.innerHTML;

            // Flash highlight top row to show new updated data
            const topRow = currentContainer.querySelector('tbody tr');
            if (topRow) {
                topRow.classList.add('bg-blue-50/80', 'transition-colors', 'duration-1000');
                setTimeout(() => {
                    topRow.classList.remove('bg-blue-50/80');
                }, 2500);
            }

            showAdminToast('⚡ Data updated! Fresh listings displayed.', 'success');
        } else {
            window.location.reload();
        }
    } catch (e) {
        window.location.reload();
    }
}

async function triggerManualSync() {
    const btn = document.getElementById('manualSyncBtn');
    const icon = document.getElementById('manualSyncIcon');
    const text = document.getElementById('manualSyncText');

    if (btn) btn.disabled = true;
    if (icon) icon.classList.add('animate-spin');
    if (text) text.textContent = 'Syncing...';

    showAdminToast('Fetching latest listings from 99acres...', 'info');

    try {
        const res = await fetch('{{ route('admin.properties.sync-now') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ city: 'Gurgaon', count: 3 })
        });

        const data = await res.json();
        if (data.success) {
            showAdminToast(`✅ ${data.message}`, 'success');
            currentLatestId = data.latest_id || currentLatestId;
            currentTotal = data.total || currentTotal;
            await refreshPropertiesTableSilently();
        } else {
            showAdminToast(data.message || 'Sync failed. Check scraper logs.', 'error');
        }
    } catch (err) {
        showAdminToast('Could not reach sync endpoint. Please try again.', 'error');
    } finally {
        if (btn) btn.disabled = false;
        if (icon) icon.classList.remove('animate-spin');
        if (text) text.textContent = 'Sync Now';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    startLiveSync();
});

// ====================================================
// Post Advertise / On Behalf of User Modal Functions
// ====================================================
function openPostAdvertiseModal() {
    const modal = document.getElementById('postAdvertiseModal');
    const card = document.getElementById('postAdvertiseModalCard');
    if (!modal || !card) return;
    modal.classList.remove('hidden');
    requestAnimationFrame(() => {
        modal.classList.remove('opacity-0');
        modal.classList.add('opacity-100');
        card.classList.remove('scale-95');
        card.classList.add('scale-100');
    });
    const nameInput = document.getElementById('ad_client_name');
    if (nameInput) setTimeout(() => nameInput.focus(), 100);
}

function closePostAdvertiseModal() {
    const modal = document.getElementById('postAdvertiseModal');
    const card = document.getElementById('postAdvertiseModalCard');
    if (!modal || !card) return;
    modal.classList.remove('opacity-100');
    modal.classList.add('opacity-0');
    card.classList.remove('scale-100');
    card.classList.add('scale-95');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 200);
}

function switchAdModalTab(mode) {
    const tabNew = document.getElementById('tab-ad-new');
    const tabExisting = document.getElementById('tab-ad-existing');
    const secNew = document.getElementById('ad-section-new');
    const secExisting = document.getElementById('ad-section-existing');
    const modeInput = document.getElementById('modal_ad_behalf_mode');
    const nameInput = document.getElementById('ad_client_name');
    const phoneInput = document.getElementById('ad_client_phone');
    const userInput = document.getElementById('ad_existing_user');

    if (mode === 'new') {
        if (modeInput) modeInput.value = 'new';
        if (tabNew) tabNew.className = 'py-2 text-xs font-bold rounded-lg transition-all text-slate-900 bg-white shadow-xs cursor-pointer';
        if (tabExisting) tabExisting.className = 'py-2 text-xs font-semibold rounded-lg transition-all text-slate-500 hover:text-slate-800 cursor-pointer';
        if (secNew) secNew.classList.remove('hidden');
        if (secExisting) secExisting.classList.add('hidden');
        if (nameInput) nameInput.required = true;
        if (phoneInput) phoneInput.required = true;
        if (userInput) userInput.required = false;
    } else {
        if (modeInput) modeInput.value = 'existing';
        if (tabNew) tabNew.className = 'py-2 text-xs font-semibold rounded-lg transition-all text-slate-500 hover:text-slate-800 cursor-pointer';
        if (tabExisting) tabExisting.className = 'py-2 text-xs font-bold rounded-lg transition-all text-slate-900 bg-white shadow-xs cursor-pointer';
        if (secNew) secNew.classList.add('hidden');
        if (secExisting) secExisting.classList.remove('hidden');
        if (nameInput) nameInput.required = false;
        if (phoneInput) phoneInput.required = false;
        if (userInput) userInput.required = true;
    }
}

document.addEventListener('click', (e) => {
    const modal = document.getElementById('postAdvertiseModal');
    if (modal && !modal.classList.contains('hidden') && e.target === modal) {
        closePostAdvertiseModal();
    }
});

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closePostAdvertiseModal();
    }
});
</script>
@endpush

@endsection
