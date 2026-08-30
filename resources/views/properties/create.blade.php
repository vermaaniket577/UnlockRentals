@extends('layouts.app')

@section('title', 'List Your Property - Zero Brokerage | UnlockRentals')
@section('meta_description', 'List your rental house, flat, shop, PG or commercial property on UnlockRentals. Connect with thousands of verified direct tenants with zero brokerage.')

@section('content')

<section class="min-h-screen pt-28 sm:pt-32 pb-24 bg-[#f8fafc] dark:bg-slate-950 relative overflow-hidden" id="create-property">
    {{-- Ambient Background Gradients --}}
    <div class="absolute top-0 left-0 w-full h-[500px] bg-gradient-to-b from-blue-500/[0.04] via-indigo-500/[0.02] to-transparent pointer-events-none"></div>
    <div class="absolute -top-32 -left-32 w-96 h-96 bg-blue-500/10 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute top-1/3 right-0 w-80 h-80 bg-indigo-500/10 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        {{-- Page Header --}}
        <div class="mb-10 text-center">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1 bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 text-xs font-extrabold uppercase tracking-widest rounded-full mb-3 border border-blue-100 dark:border-blue-900/50">
                <i class="ph-bold ph-sparkle text-xs"></i> 100% Free Owner Listing
            </span>
            <h1 class="text-3xl sm:text-5xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-tight">
                List Your Property
            </h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm sm:text-base font-normal max-w-xl mx-auto mt-2.5 leading-relaxed">
                Reach thousands of verified tenants with zero brokerage and instant digital inquiries.
            </p>
        </div>

        {{-- Step Navigation Indicator --}}
        <div class="mb-10 p-4 sm:p-5 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs">
            <div class="grid grid-cols-4 gap-2 sm:gap-4 text-center">
                <div class="flex flex-col items-center gap-1.5">
                    <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-blue-600 text-white font-extrabold text-xs sm:text-sm flex items-center justify-center shadow-xs">1</div>
                    <span class="text-[11px] sm:text-xs font-bold text-slate-900 dark:text-white">Basic Info</span>
                </div>
                <div class="flex flex-col items-center gap-1.5">
                    <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 font-extrabold text-xs sm:text-sm flex items-center justify-center">2</div>
                    <span class="text-[11px] sm:text-xs font-bold text-slate-500 dark:text-slate-400">Pricing</span>
                </div>
                <div class="flex flex-col items-center gap-1.5">
                    <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 font-extrabold text-xs sm:text-sm flex items-center justify-center">3</div>
                    <span class="text-[11px] sm:text-xs font-bold text-slate-500 dark:text-slate-400">Location</span>
                </div>
                <div class="flex flex-col items-center gap-1.5">
                    <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 font-extrabold text-xs sm:text-sm flex items-center justify-center">4</div>
                    <span class="text-[11px] sm:text-xs font-bold text-slate-500 dark:text-slate-400">Photos</span>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('properties.store') }}" enctype="multipart/form-data" class="space-y-8" id="create-property-form" data-ur-loader-skip="true" data-no-smooth="true">
            @csrf

            {{-- 1. Basic Info Section --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 sm:p-8 shadow-xs" id="create-basic-info">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 flex items-center justify-center">
                        <i class="ph-bold ph-info text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">Basic Information</h2>
                        <p class="text-xs text-slate-400">General property headline and listing classification</p>
                    </div>
                </div>

                <div class="space-y-6">
                    {{-- Title --}}
                    <div>
                        <label for="create-title" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Property Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="create-title" value="{{ old('title') }}" required
                               class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white placeholder-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all"
                               placeholder="e.g. Spacious 3 BHK Semi-Furnished Flat in Sector 57">
                        @error('title') <p class="text-red-500 text-xs mt-1.5 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="create-description" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Description <span class="text-red-500">*</span></label>
                        <textarea name="description" id="create-description" rows="4" required
                                  class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all resize-none"
                                  placeholder="Describe the key features, nearby amenities, sunlight, balcony view, and tenant preferences...">{{ old('description') }}</textarea>
                        @error('description') <p class="text-red-500 text-xs mt-1.5 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    {{-- Listing Purpose (Rent vs Sell) --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2.5">Listing Purpose (Intent) <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-w-xl">
                            @php
                                $curPurpose = old('purpose', 'rent');
                            @endphp
                            <label class="relative flex items-center gap-3 p-3.5 rounded-xl border cursor-pointer transition-all {{ ($curPurpose === 'rent') ? 'border-blue-600 bg-blue-50/70 dark:bg-blue-950/40 text-blue-700 dark:text-blue-400 font-bold ring-1 ring-blue-600 shadow-sm' : 'border-slate-200 dark:border-slate-700/80 bg-slate-50/50 dark:bg-slate-800/40 hover:border-slate-300 text-slate-600 dark:text-slate-400 font-medium' }}" for="create-purpose-rent">
                                <input type="radio" name="purpose" value="rent" id="create-purpose-rent" {{ $curPurpose === 'rent' ? 'checked' : '' }} class="sr-only" onchange="this.closest('.grid').querySelectorAll('label').forEach(l => l.classList.remove('border-blue-600','bg-blue-50/70','dark:bg-blue-950/40','text-blue-700','dark:text-blue-400','font-bold','ring-1','ring-blue-600','shadow-sm')); this.closest('label').classList.add('border-blue-600','bg-blue-50/70','dark:bg-blue-950/40','text-blue-700','dark:text-blue-400','font-bold','ring-1','ring-blue-600','shadow-sm'); if(window.onPurposeChange) window.onPurposeChange('rent');">
                                <div class="w-8 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center shrink-0">
                                    <i class="ph-bold ph-key text-base"></i>
                                </div>
                                <div>
                                    <div class="text-xs sm:text-sm font-bold leading-tight">For Rent</div>
                                    <div class="text-[11px] text-slate-500 dark:text-slate-400 font-normal">Monthly Lease / Rental Income</div>
                                </div>
                            </label>

                            <label class="relative flex items-center gap-3 p-3.5 rounded-xl border cursor-pointer transition-all {{ in_array($curPurpose, ['buy', 'sell']) ? 'border-blue-600 bg-blue-50/70 dark:bg-blue-950/40 text-blue-700 dark:text-blue-400 font-bold ring-1 ring-blue-600 shadow-sm' : 'border-slate-200 dark:border-slate-700/80 bg-slate-50/50 dark:bg-slate-800/40 hover:border-slate-300 text-slate-600 dark:text-slate-400 font-medium' }}" for="create-purpose-buy">
                                <input type="radio" name="purpose" value="buy" id="create-purpose-buy" {{ in_array($curPurpose, ['buy', 'sell']) ? 'checked' : '' }} class="sr-only" onchange="this.closest('.grid').querySelectorAll('label').forEach(l => l.classList.remove('border-blue-600','bg-blue-50/70','dark:bg-blue-950/40','text-blue-700','dark:text-blue-400','font-bold','ring-1','ring-blue-600','shadow-sm')); this.closest('label').classList.add('border-blue-600','bg-blue-50/70','dark:bg-blue-950/40','text-blue-700','dark:text-blue-400','font-bold','ring-1','ring-blue-600','shadow-sm'); if(window.onPurposeChange) window.onPurposeChange('buy');">
                                <div class="w-8 h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center shrink-0">
                                    <i class="ph-bold ph-tag text-base"></i>
                                </div>
                                <div>
                                    <div class="text-xs sm:text-sm font-bold leading-tight">For Sell / Sale</div>
                                    <div class="text-[11px] text-slate-500 dark:text-slate-400 font-normal">Outright Property Sale</div>
                                </div>
                            </label>
                        </div>
                        @error('purpose') <p class="text-red-500 text-xs mt-1.5 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    {{-- Property Type Selector --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2.5">Property Type <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                            @php
                                $formTypes = [
                                    'house' => ['label' => 'House / Flat', 'icon' => 'ph-bold ph-house-line'],
                                    'shop' => ['label' => 'Commercial Shop', 'icon' => 'ph-bold ph-storefront'],
                                    'pg-hostel' => ['label' => 'PG / Hostel', 'icon' => 'ph-bold ph-buildings'],
                                    'hotel' => ['label' => 'Hotel Room', 'icon' => 'ph-bold ph-bed']
                                ];
                            @endphp
                            @foreach($formTypes as $val => $info)
                            <label class="relative flex flex-col items-center justify-center p-3 rounded-xl border cursor-pointer transition-all {{ (old('type', 'house') === $val) ? 'border-blue-600 bg-blue-50/70 dark:bg-blue-950/40 text-blue-700 dark:text-blue-400 font-bold ring-1 ring-blue-600' : 'border-slate-200 dark:border-slate-700/80 bg-slate-50/50 dark:bg-slate-800/40 hover:border-slate-300 text-slate-600 dark:text-slate-400 font-medium' }}" for="create-type-{{ $val }}">
                                <input type="radio" name="type" value="{{ $val }}" id="create-type-{{ $val }}" {{ old('type', 'house') === $val ? 'checked' : '' }} class="sr-only" onchange="this.closest('.grid').querySelectorAll('label').forEach(l => l.classList.remove('border-blue-600','bg-blue-50/70','dark:bg-blue-950/40','text-blue-700','dark:text-blue-400','font-bold','ring-1','ring-blue-600')); this.closest('label').classList.add('border-blue-600','bg-blue-50/70','dark:bg-blue-950/40','text-blue-700','dark:text-blue-400','font-bold','ring-1','ring-blue-600');">
                                <i class="{{ $info['icon'] }} text-xl mb-1 text-blue-600"></i>
                                <span class="text-xs text-center leading-tight">{{ $info['label'] }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Category & Phone Row --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="create-category" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Category <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select name="category_id" id="create-category" required
                                        class="w-full pl-4 pr-9 py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all appearance-none cursor-pointer">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <i class="ph-bold ph-caret-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                            </div>
                        </div>

                        <div>
                            <label for="create-phone" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Contact Phone Number <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"><i class="ph-bold ph-phone"></i></span>
                                <input type="tel" name="contact_phone" id="create-phone" value="{{ old('contact_phone', auth()->user()->phone ?? '') }}" required
                                       class="w-full pl-10 pr-4 py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white placeholder-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all"
                                       placeholder="+91 94254 55499">
                            </div>
                            @error('contact_phone') <p class="text-red-500 text-xs mt-1.5 font-semibold">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. Pricing Section --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 sm:p-8 shadow-xs" id="create-pricing">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 flex items-center justify-center">
                        <i class="ph-bold ph-currency-inr text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white" id="create-pricing-title">Pricing & Billing</h2>
                        <p class="text-xs text-slate-400" id="create-pricing-desc">Set your expected rent or sale price</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5" id="pricing-grid">
                    <div>
                        <label for="create-price" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2" id="create-price-label">Expected Rent (₹) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 font-extrabold text-sm">₹</span>
                            <input type="number" name="price" id="create-price" value="{{ old('price') }}" required min="0" step="0.01"
                                   class="w-full pl-9 pr-4 py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white placeholder-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all"
                                   placeholder="25,000">
                        </div>
                        @error('price') <p class="text-red-500 text-xs mt-1.5 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    {{-- Rent Billing Cycle (Per Month / Per Year) --}}
                    <div id="period-input-col">
                        <label for="create-period" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2" id="create-period-label">Billing Cycle <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select name="price_period" id="create-period" required
                                    class="w-full pl-4 pr-9 py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all appearance-none cursor-pointer">
                                <option value="month" {{ old('price_period') === 'month' ? 'selected' : '' }}>Per Month</option>
                                <option value="year" {{ old('price_period') === 'year' ? 'selected' : '' }}>Per Year</option>
                            </select>
                            <i class="ph-bold ph-caret-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                        </div>
                    </div>

                    {{-- Sale Pricing Mode Badge (Shown when Selling) --}}
                    <div id="sale-badge-col" class="hidden">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Payment Mode</label>
                        <div class="h-[46px] px-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/50 rounded-xl text-xs font-bold text-emerald-800 dark:text-emerald-300 flex items-center gap-2 shadow-2xs">
                            <i class="ph-bold ph-tag text-emerald-600 text-base shrink-0"></i>
                            <span>One-Time Total Purchase Price (No recurring rent)</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. Location Details --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 sm:p-8 shadow-xs" id="create-location">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 flex items-center justify-center">
                        <i class="ph-bold ph-map-pin text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">Location Details</h2>
                        <p class="text-xs text-slate-400">Accurate location helps verified tenants discover your listing</p>
                    </div>
                </div>

                <div class="space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="create-state" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">State <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select name="state" id="create-state" required onchange="if(window.handleLocationStateChange) window.handleLocationStateChange(this, 'create-city', 'create-locality-select');"
                                        class="w-full pl-4 pr-9 py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all appearance-none cursor-pointer">
                                    <option value="">Select State</option>
                                    @php $statesList = $globalAllStates ?? $allStates ?? []; @endphp
                                    @if(!empty($statesList))
                                        @foreach($statesList as $code => $name)
                                            <option value="{{ $code }}" {{ old('state') == $code ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <i class="ph-bold ph-caret-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                            </div>
                        </div>

                        <div>
                            <label for="create-city" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">City / District <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select name="location" id="create-city" required onchange="if(window.handleLocationCityChange) window.handleLocationCityChange(this, 'create-locality-select', 'create-state');"
                                        class="w-full pl-4 pr-9 py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all appearance-none cursor-pointer">
                                    <option value="">Select District</option>
                                    @php $districtsList = $globalAllDistricts ?? $allDistricts ?? []; @endphp
                                    @if(!empty($districtsList))
                                        @foreach($districtsList as $d)
                                            @php
                                                $dSlug = $d['slug'] ?? strtolower(str_replace(' ', '-', $d['name']));
                                                $isSelected = (old('location') === $dSlug || old('location') === $d['name']);
                                            @endphp
                                            <option value="{{ $d['name'] }}" {{ $isSelected ? 'selected' : '' }}>
                                                {{ $d['name'] }}{{ !empty($d['state_code']) ? ' (' . $d['state_code'] . ')' : '' }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <i class="ph-bold ph-caret-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label for="create-locality-select" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Locality / Sector / Area <span class="text-red-500">*</span></label>
                        <div id="locality-select-wrap">
                            <div class="relative">
                                <select name="locality" id="create-locality-select"
                                        class="w-full pl-4 pr-9 py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all appearance-none cursor-pointer">
                                    <option value="">Select City First</option>
                                </select>
                                <i class="ph-bold ph-caret-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                            </div>
                        </div>
                        <div id="locality-text-wrap" style="display: none;">
                            <div class="relative">
                                <i class="ph-bold ph-map-pin absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                                <input type="text" name="locality" id="create-locality-text" value="{{ old('locality') }}" 
                                       class="w-full pl-10 pr-4 py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white placeholder-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all"
                                       placeholder="e.g. Sector 57, Sushant Lok">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="create-address" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Complete Address <span class="text-red-500">*</span></label>
                        <input type="text" name="address" id="create-address" value="{{ old('address') }}" required
                               class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white placeholder-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all"
                               placeholder="Flat/House No, Building Name, Street / Landmark">
                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        if (typeof window.initLocationCascading === 'function') {
                            window.initLocationCascading({
                                stateId: 'create-state',
                                cityId: 'create-city',
                                localityId: 'create-locality-select',
                                localityTextWrapId: 'locality-text-wrap',
                                localitySelectWrapId: 'locality-select-wrap',
                                selectedState: "{{ old('state') }}",
                                selectedCity: "{{ old('location') }}",
                                selectedLocality: "{{ old('locality') }}"
                            });
                        }
                    });
                </script>
            </div>

            {{-- 4. Key Specifications --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 sm:p-8 shadow-xs" id="create-details">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 flex items-center justify-center">
                        <i class="ph-bold ph-house-line text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">Key Specifications</h2>
                        <p class="text-xs text-slate-400">Bedrooms, bathrooms, area, and furnishing level</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div>
                        <label for="create-bedrooms" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Bedrooms (BHK)</label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"><i class="ph-bold ph-bed"></i></span>
                            <input type="number" name="bedrooms" id="create-bedrooms" value="{{ old('bedrooms') }}" min="0" max="20"
                                   class="w-full pl-10 pr-4 py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white placeholder-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all"
                                   placeholder="3">
                        </div>
                    </div>
                    <div>
                        <label for="create-bathrooms" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Bathrooms</label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"><i class="ph-bold ph-drop"></i></span>
                            <input type="number" name="bathrooms" id="create-bathrooms" value="{{ old('bathrooms') }}" min="0" max="20"
                                   class="w-full pl-10 pr-4 py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white placeholder-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all"
                                   placeholder="2">
                        </div>
                    </div>
                    <div>
                        <label for="create-area" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Area (sq.ft)</label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"><i class="ph-bold ph-square-half"></i></span>
                            <input type="number" name="area_sqft" id="create-area" value="{{ old('area_sqft') }}" min="0"
                                   class="w-full pl-10 pr-4 py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white placeholder-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all"
                                   placeholder="1250">
                        </div>
                    </div>
                </div>

                <div class="mt-5">
                    <label for="create-furnishing" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Furnishing Status <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="furnishing" id="create-furnishing" required
                                class="w-full pl-4 pr-9 py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all appearance-none cursor-pointer">
                            <option value="unfurnished" {{ old('furnishing') === 'unfurnished' ? 'selected' : '' }}>Unfurnished</option>
                            <option value="semi-furnished" {{ old('furnishing') === 'semi-furnished' ? 'selected' : '' }}>Semi-Furnished</option>
                            <option value="fully-furnished" {{ old('furnishing') === 'fully-furnished' ? 'selected' : '' }}>Fully Furnished</option>
                        </select>
                        <i class="ph-bold ph-caret-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                    </div>
                </div>
            </div>

            {{-- 5. Media & Photo Gallery --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 sm:p-8 shadow-xs" id="create-images">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 flex items-center justify-center">
                            <i class="ph-bold ph-images-square text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-slate-900 dark:text-white">Property Photos</h2>
                            <p class="text-xs text-slate-400">High-quality photos increase inquiries by up to 5x</p>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-950/60 px-3 py-1 rounded-lg self-start sm:self-auto border border-blue-200/60 dark:border-blue-800">Min 1 Photo or Video Tour *</span>
                </div>

                {{-- Upload Dropzone --}}
                <div class="border-2 border-dashed border-slate-200 dark:border-slate-700 hover:border-blue-500 rounded-2xl p-8 text-center transition-all group relative bg-slate-50/50 dark:bg-slate-800/30">
                    <div class="w-14 h-14 rounded-2xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                        <i class="ph-bold ph-cloud-arrow-up text-2xl"></i>
                    </div>
                    <p class="text-sm font-bold text-slate-800 dark:text-white mb-1">Click to upload or drag & drop photos here</p>
                    <p class="text-xs text-slate-400">Supports JPG, PNG, WebP · <span class="text-emerald-600 font-semibold">⚡ Auto-compressed for ultra-fast upload</span></p>
                    
                    <input type="file" name="images[]" multiple accept="image/*"
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                           id="create-images-input" onchange="handleImageSelection(event)">
                </div>

                {{-- Image Compression Status Indicator --}}
                <div id="compression-status" class="mt-3 p-3 bg-blue-50 dark:bg-blue-950/50 border border-blue-200 dark:border-blue-800 rounded-xl text-xs font-semibold text-blue-700 dark:text-blue-300 flex items-center justify-between hidden">
                    <div class="flex items-center gap-2">
                        <i class="ph-bold ph-lightning text-amber-500 text-base"></i>
                        <span id="compression-status-text">Optimizing photos for instant upload...</span>
                    </div>
                    <span id="compression-saved-text" class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-950/60 px-2 py-0.5 rounded-md"></span>
                </div>
                
                {{-- Hidden input for primary image --}}
                <input type="hidden" name="primary_image" id="primary-image-input" value="0">

                {{-- Preview Gallery Container --}}
                <div id="image-preview-container" class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 gap-3.5 mt-6 hidden">
                    {{-- Previews dynamically rendered here --}}
                </div>
                
                @error('images') <p class="text-red-500 text-xs mt-3 font-semibold">{{ $message }}</p> @enderror
                @error('images.*') <p class="text-red-500 text-xs mt-3 font-semibold">{{ $message }}</p> @enderror

                {{-- Video Tour Section --}}
                <div class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-800">
                    <div class="flex items-center justify-between gap-3 mb-4">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl bg-purple-50 dark:bg-purple-950/60 text-purple-600 flex items-center justify-center">
                                <i class="ph-bold ph-video-camera text-base"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                    Property Video Tours & Clips
                                    <span class="px-2 py-0.5 bg-purple-100 dark:bg-purple-900/60 text-purple-700 dark:text-purple-300 text-[10px] font-extrabold uppercase rounded-full">Multi-Clip & Trimmer</span>
                                </h3>
                                <p class="text-xs text-slate-400">Upload multiple clips, trim large videos into short clips, or add YouTube/Vimeo links</p>
                            </div>
                        </div>
                        <span class="text-[11px] font-bold text-purple-700 dark:text-purple-300 bg-purple-50 dark:bg-purple-950/60 px-2.5 py-0.5 rounded-md border border-purple-200/60 dark:border-purple-800">3x Inquiries</span>
                    </div>

                    {{-- Multi-Video Upload Box --}}
                    <div id="video-upload-box" class="border-2 border-dashed border-slate-200 dark:border-slate-700 hover:border-purple-500 rounded-2xl p-6 text-center transition-all group relative bg-slate-50/40 dark:bg-slate-800/20 cursor-pointer" onclick="document.getElementById('create-video-input').click()">
                        <div class="w-12 h-12 rounded-2xl bg-purple-50 dark:bg-purple-950/50 text-purple-600 flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                            <i class="ph-bold ph-film-slate text-xl"></i>
                        </div>
                        <p class="text-xs sm:text-sm font-bold text-slate-800 dark:text-white mb-0.5">Click to upload property videos / clips (Up to 10 videos)</p>
                        <p class="text-xs text-slate-400">MP4, WebM, MOV, M4V · Auto-optimized for fast upload · Optional trim for long videos · Max 10 clips</p>
                        
                        <input type="file" id="create-video-input" multiple accept="video/mp4,video/webm,video/ogg,video/quicktime,video/x-m4v" class="hidden" onchange="handleMultipleVideoSelection(event)">
                    </div>

                    {{-- External Video Tour Link Section --}}
                    <div class="mt-4 p-4 rounded-xl bg-slate-50/60 dark:bg-slate-800/40 border border-slate-200/80 dark:border-slate-700/80">
                        <div class="flex items-center justify-between gap-2 mb-2">
                            <label class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider flex items-center gap-1.5">
                                <i class="ph-bold ph-link text-purple-600"></i> Or Add Video Tour Link (YouTube / Vimeo / Cloud)
                            </label>
                        </div>
                        <div class="flex gap-2">
                            <input type="url" id="video-url-input" placeholder="https://www.youtube.com/watch?v=... or cloud video link"
                                   class="flex-1 px-3.5 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-purple-600">
                            <button type="button" onclick="addVideoUrlLink()" class="px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-xl shadow-sm transition-all flex items-center gap-1.5 cursor-pointer">
                                <i class="ph-bold ph-plus"></i> Add Link
                            </button>
                        </div>
                    </div>

                    {{-- Compression / Trimming Global Status --}}
                    <div id="video-compress-status" class="mt-4 p-3.5 bg-purple-50 dark:bg-purple-950/40 border border-purple-200 dark:border-purple-800/60 rounded-xl hidden">
                        <div class="flex items-center justify-between text-xs font-bold text-purple-900 dark:text-purple-300 mb-1.5">
                            <span class="flex items-center gap-1.5"><i class="ph-bold ph-lightning text-amber-500 animate-pulse text-sm"></i> <span id="compress-status-text">Optimizing video for fast posting...</span></span>
                            <span id="compress-status-pct">0%</span>
                        </div>
                        <div class="w-full bg-purple-200/60 dark:bg-purple-900/60 h-2 rounded-full overflow-hidden">
                            <div id="compress-progress-bar" class="bg-gradient-to-r from-purple-600 to-blue-600 h-full w-0 transition-all duration-150"></div>
                        </div>
                    </div>

                    {{-- Multi-Video Preview List Container --}}
                    <div id="video-previews-container" class="mt-4 space-y-3"></div>

                    @error('video') <p class="text-red-500 text-xs mt-2 font-semibold">{{ $message }}</p> @enderror
                    @error('videos') <p class="text-red-500 text-xs mt-2 font-semibold">{{ $message }}</p> @enderror
                    @error('videos.*') <p class="text-red-500 text-xs mt-2 font-semibold">{{ $message }}</p> @enderror
                </div>
                      {{-- Interactive Video Trimmer Modal --}}
            <div id="video-trimmer-modal" class="fixed inset-0 z-[99999] flex items-center justify-center p-3 sm:p-4 bg-black/85 backdrop-blur-md hidden" style="z-index: 99999;">
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl max-w-2xl w-full max-h-[92vh] overflow-y-auto shadow-2xl p-5 sm:p-6 transition-all relative z-10" onclick="event.stopPropagation()">
                    
                    {{-- Modal Header --}}
                    <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100 dark:border-slate-800">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center text-lg">
                                <i class="ph-bold ph-scissors"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                    Trim Video Clip
                                    <span class="px-2 py-0.5 bg-purple-100 dark:bg-purple-900/60 text-purple-700 dark:text-purple-300 text-[10px] font-extrabold uppercase rounded-full">Fast Web Clip</span>
                                </h3>
                                <p class="text-xs text-slate-400">Cut a short highlight clip (15s–60s) for instant uploading & fast playback</p>
                            </div>
                        </div>
                        <button type="button" onclick="closeTrimmerModal()" class="w-8 h-8 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400 hover:text-slate-700 dark:hover:text-white flex items-center justify-center transition-all cursor-pointer">
                            <i class="ph-bold ph-x text-lg"></i>
                        </button>
                    </div>

                    {{-- Video Player Container --}}
                    <div class="relative bg-black rounded-xl overflow-hidden aspect-video max-h-[280px] sm:max-h-[320px] mb-4 flex items-center justify-center shadow-inner">
                        <video id="trimmer-preview-player" playsinline preload="metadata" class="w-full h-full object-contain"></video>
                        
                        {{-- Time Overlay --}}
                        <div class="absolute bottom-2 left-2 px-2.5 py-1 bg-black/75 backdrop-blur-xs text-white text-[11px] font-mono font-bold rounded-lg pointer-events-none">
                            <span id="trimmer-current-time">00:00</span> / <span id="trimmer-total-duration">00:00</span>
                        </div>
                    </div>

                    {{-- Clip Label Input --}}
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                            Clip Name / Room (Optional)
                        </label>
                        <input type="text" id="trimmer-clip-title" placeholder="e.g. Living Room Walkthrough, Master Bedroom, Balcony View"
                               class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-purple-600">
                    </div>

                    {{-- Trim Range Controls --}}
                    <div class="p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-200/80 dark:border-slate-700/80 mb-4 space-y-3.5">
                        
                        {{-- Sliders & Time Inputs --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            {{-- Start Time --}}
                            <div class="space-y-1.5">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-bold text-slate-700 dark:text-slate-300">Start Time:</span>
                                    <div class="flex items-center gap-1.5">
                                        <span id="trimmer-start-display" class="font-mono font-bold text-purple-600 dark:text-purple-400">0.0s</span>
                                        <button type="button" onclick="setTrimStartToCurrent()" class="px-2 py-0.5 bg-purple-100 dark:bg-purple-950/60 hover:bg-purple-200 text-purple-700 dark:text-purple-300 text-[10px] font-bold rounded cursor-pointer">
                                            Set Current
                                        </button>
                                    </div>
                                </div>
                                <input type="range" id="trimmer-start-slider" min="0" max="100" step="0.1" value="0"
                                       class="w-full accent-purple-600 cursor-pointer" oninput="onTrimStartSliderChange(this.value)">
                            </div>

                            {{-- End Time --}}
                            <div class="space-y-1.5">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-bold text-slate-700 dark:text-slate-300">End Time:</span>
                                    <div class="flex items-center gap-1.5">
                                        <span id="trimmer-end-display" class="font-mono font-bold text-purple-600 dark:text-purple-400">30.0s</span>
                                        <button type="button" onclick="setTrimEndToCurrent()" class="px-2 py-0.5 bg-purple-100 dark:bg-purple-950/60 hover:bg-purple-200 text-purple-700 dark:text-purple-300 text-[10px] font-bold rounded cursor-pointer">
                                            Set Current
                                        </button>
                                    </div>
                                </div>
                                <input type="range" id="trimmer-end-slider" min="0" max="100" step="0.1" value="30"
                                       class="w-full accent-purple-600 cursor-pointer" oninput="onTrimEndSliderChange(this.value)">
                            </div>
                        </div>

                        {{-- Quick Presets --}}
                        <div class="flex flex-wrap items-center gap-1.5 pt-2 border-t border-slate-200/60 dark:border-slate-700/60">
                            <span class="text-[11px] font-bold text-slate-400 mr-1">Fast Presets:</span>
                            <button type="button" onclick="applyTrimPreset(15)" id="trim-preset-15" class="trim-preset-btn px-2.5 py-1 bg-white dark:bg-slate-700 hover:bg-purple-50 text-slate-700 dark:text-slate-200 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-600 cursor-pointer transition-all">15s Clip</button>
                            <button type="button" onclick="applyTrimPreset(30)" id="trim-preset-30" class="trim-preset-btn px-2.5 py-1 bg-white dark:bg-slate-700 hover:bg-purple-50 text-slate-700 dark:text-slate-200 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-600 cursor-pointer transition-all">30s Clip</button>
                            <button type="button" onclick="applyTrimPreset(45)" id="trim-preset-45" class="trim-preset-btn px-2.5 py-1 bg-white dark:bg-slate-700 hover:bg-purple-50 text-slate-700 dark:text-slate-200 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-600 cursor-pointer transition-all">45s Clip</button>
                            <button type="button" onclick="applyTrimPreset(60)" id="trim-preset-60" class="trim-preset-btn px-2.5 py-1 bg-white dark:bg-slate-700 hover:bg-purple-50 text-slate-700 dark:text-slate-200 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-600 cursor-pointer transition-all">60s Clip</button>
                            <button type="button" onclick="previewTrimRange()" class="ml-auto px-3 py-1 bg-slate-900 hover:bg-black text-white text-xs font-bold rounded-lg flex items-center gap-1 shadow-xs cursor-pointer">
                                <i class="ph-bold ph-play"></i> Preview Clip
                            </button>
                        </div>

                        {{-- Summary Badge --}}
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1.5 text-xs font-semibold text-slate-600 dark:text-slate-300 pt-1">
                            <span>Clip Duration: <strong id="trimmer-duration-badge" class="text-purple-600 dark:text-purple-400">30.0s</strong> <span class="text-[10px] text-slate-400 font-normal">(Max 60s per clip)</span></span>
                            <div id="trimmer-speed-badge">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 text-[11px] font-bold rounded-full border border-emerald-200/50">⚡ ~4.8 MB · Uploads in ~2 sec</span>
                            </div>
                        </div>
                    </div>

                    {{-- Trimming Progress Bar (during processing) --}}
                    <div id="trimmer-progress-wrapper" class="mb-4 p-3 bg-purple-50 dark:bg-purple-950/40 rounded-xl border border-purple-200 dark:border-purple-800/60 hidden">
                        <div class="flex items-center justify-between text-xs font-bold text-purple-900 dark:text-purple-300 mb-1.5">
                            <span class="flex items-center gap-1.5"><i class="ph-bold ph-lightning text-amber-500 animate-pulse"></i> Extracting & optimizing clip...</span>
                            <span id="trimmer-progress-pct">0%</span>
                        </div>
                        <div class="w-full bg-purple-200 dark:bg-purple-900 h-2 rounded-full overflow-hidden">
                            <div id="trimmer-progress-bar" class="bg-gradient-to-r from-purple-600 to-indigo-600 h-full w-0 transition-all duration-100"></div>
                        </div>
                    </div>

                    {{-- Mode Selector: Add as new clip vs replace --}}
                    <div class="flex items-center gap-4 mb-5 text-xs">
                        <label class="inline-flex items-center gap-2 cursor-pointer text-slate-700 dark:text-slate-300 font-semibold">
                            <input type="radio" name="trimmer_mode" value="add" id="trimmer-mode-add" checked class="text-purple-600 focus:ring-purple-500">
                            <span>Add as New Clip <span class="text-[10px] text-slate-400">(Cut multiple clips for different rooms)</span></span>
                        </label>
                        <label class="inline-flex items-center gap-2 cursor-pointer text-slate-700 dark:text-slate-300 font-semibold">
                            <input type="radio" name="trimmer_mode" value="replace" id="trimmer-mode-replace" class="text-purple-600 focus:ring-purple-500">
                            <span>Replace Selected Video</span>
                        </label>
                    </div>

                    {{-- Bottom Modal Buttons --}}
                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" onclick="closeTrimmerModal()" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all cursor-pointer">
                            Cancel
                        </button>
                        <button type="button" id="trimmer-submit-btn" onclick="executeVideoTrimming()" class="px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white text-xs font-bold rounded-xl shadow-md transition-all flex items-center gap-1.5 cursor-pointer">
                            <i class="ph-bold ph-scissors text-sm"></i>
                            <span id="trimmer-submit-text">✂️ Cut & Add Clip to Uploads</span>
                        </button>
                    </div>
                </div>
            </div>        </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3 pt-6 border-t border-slate-200/70 dark:border-slate-800 mt-6">
                <a href="{{ route('dashboard') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold text-sm text-center rounded-xl transition-all" id="create-cancel" title="Cancel">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold text-sm rounded-xl shadow-sm shadow-blue-500/20 hover:shadow-md transition-all active:scale-[0.98] flex items-center justify-center gap-2 cursor-pointer disabled:opacity-75 disabled:cursor-not-allowed" id="create-submit">
                    <i class="ph-bold ph-paper-plane-tilt text-base" id="submit-icon"></i> 
                    <span id="submit-label">Publish Property Listing</span>
                </button>
            </div>
        </form>
    </div>
</section>

<script>
let selectedFiles = [];
let primaryImageIndex = 0;

// Maximum duration allowed for a single clip (guarantees fast upload and instant processing)
const MAX_CLIP_SECONDS = 60;

// High-speed client-side image compression
async function compressImage(file, maxDimension = 1600, quality = 0.85) {
    if (file.type === 'image/svg+xml' || file.size < 150 * 1024) {
        return file;
    }

    return new Promise((resolve) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = (e) => {
            const img = new Image();
            img.src = e.target.result;
            img.onload = () => {
                let width = img.width;
                let height = img.height;

                if (width > maxDimension || height > maxDimension) {
                    if (width > height) {
                        height = Math.round((height * maxDimension) / width);
                        width = maxDimension;
                    } else {
                        width = Math.round((width * maxDimension) / height);
                        height = maxDimension;
                    }
                }

                const canvas = document.createElement('canvas');
                canvas.width = width;
                canvas.height = height;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);

                const mimeType = 'image/jpeg';
                canvas.toBlob((blob) => {
                    if (blob && blob.size < file.size) {
                        const newName = file.name.replace(/\.[^/.]+$/, "") + ".jpg";
                        const compressedFile = new File([blob], newName, {
                            type: mimeType,
                            lastModified: Date.now()
                        });
                        resolve({ file: compressedFile, originalSize: file.size, compressedSize: compressedFile.size });
                    } else {
                        resolve({ file: file, originalSize: file.size, compressedSize: file.size });
                    }
                }, mimeType, quality);
            };
            img.onerror = () => resolve({ file: file, originalSize: file.size, compressedSize: file.size });
        };
        reader.onerror = () => resolve({ file: file, originalSize: file.size, compressedSize: file.size });
    });
}

async function handleImageSelection(event) {
    const input = event.target;
    const newRawFiles = Array.from(input.files);
    
    if (selectedFiles.length + newRawFiles.length > 10) {
        alert('You can upload a maximum of 10 photos per property.');
        syncInputFiles();
        return;
    }

    const statusBox = document.getElementById('compression-status');
    const statusText = document.getElementById('compression-status-text');
    const savedText = document.getElementById('compression-saved-text');
    
    if (statusBox) {
        statusBox.classList.remove('hidden');
        statusText.textContent = `Optimizing ${newRawFiles.length} photo(s) for fast upload...`;
        savedText.textContent = 'Processing...';
    }

    let totalOriginal = 0;
    let totalCompressed = 0;

    const compressedResults = await Promise.all(newRawFiles.map(f => compressImage(f)));

    compressedResults.forEach(res => {
        selectedFiles.push(res.file);
        totalOriginal += res.originalSize;
        totalCompressed += res.compressedSize;
    });

    syncInputFiles();
    renderPreviews();

    if (statusBox && totalOriginal > 0) {
        const origMB = (totalOriginal / (1024 * 1024)).toFixed(1);
        const compMB = (totalCompressed / (1024 * 1024)).toFixed(1);
        const savings = Math.max(0, Math.round(((totalOriginal - totalCompressed) / totalOriginal) * 100));

        statusText.textContent = `⚡ Optimized ${selectedFiles.length} photo(s) (${origMB}MB → ${compMB}MB)`;
        savedText.textContent = `${savings}% Faster Upload`;
    }
}

function syncInputFiles() {
    const input = document.getElementById('create-images-input');
    const dataTransfer = new DataTransfer();
    selectedFiles.forEach(file => dataTransfer.items.add(file));
    input.files = dataTransfer.files;

    const primaryInput = document.getElementById('primary-image-input');
    if (primaryInput) {
        primaryInput.value = primaryImageIndex;
    }
}

function setPrimaryImage(index) {
    primaryImageIndex = index;
    const primaryInput = document.getElementById('primary-image-input');
    if (primaryInput) primaryInput.value = index;
    renderPreviews();
}

function renderPreviews() {
    const container = document.getElementById('image-preview-container');
    container.innerHTML = ''; 
    
    if (selectedFiles.length > 0) {
        container.classList.remove('hidden');
    } else {
        container.classList.add('hidden');
        const statusBox = document.getElementById('compression-status');
        if (statusBox) statusBox.classList.add('hidden');
    }

    selectedFiles.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = (e) => {
            const isPrimary = (index === primaryImageIndex);
            const sizeKB = Math.round(file.size / 1024);
            const imgBox = document.createElement('div');
            imgBox.className = `relative aspect-square rounded-2xl overflow-hidden border ${isPrimary ? 'border-blue-600 ring-2 ring-blue-500 shadow-md' : 'border-slate-200 dark:border-slate-700 shadow-xs'} group/item`;
            
            imgBox.innerHTML = `
                <img src="${e.target.result}" class="w-full h-full object-cover transition-transform group-hover/item:scale-105">
                
                ${isPrimary ? '<span class="absolute top-2 left-2 px-2 py-0.5 bg-blue-600 text-white text-[10px] font-extrabold uppercase rounded-md shadow-sm flex items-center gap-1"><i class="ph-fill ph-star"></i> Cover Photo</span>' : `<button type="button" onclick="setPrimaryImage(${index})" class="absolute top-2 left-2 px-2 py-0.5 bg-black/60 hover:bg-blue-600 text-white text-[10px] font-bold rounded-md opacity-0 group-hover/item:opacity-100 transition-opacity">Set as Cover</button>`}

                <div class="absolute inset-x-0 bottom-0 p-2 bg-gradient-to-t from-black/85 via-black/40 to-transparent flex justify-between items-center">
                    <span class="text-white text-[10px] font-bold">${sizeKB} KB</span>
                    <button type="button" onclick="removeImage(${index})" class="text-white bg-red-600/90 hover:bg-red-600 p-1.5 rounded-full transition-all shadow-sm" title="Remove Photo">
                        <i class="ph-bold ph-trash text-xs"></i>
                    </button>
                </div>
            `;
            container.appendChild(imgBox);
        };
        reader.readAsDataURL(file);
    });
}

function removeImage(index) {
    selectedFiles.splice(index, 1);
    if (primaryImageIndex >= selectedFiles.length) {
        primaryImageIndex = 0;
    }
    syncInputFiles();
    renderPreviews();
}

// --- MULTI-VIDEO & VIDEO TRIMMER / COMPRESSION ENGINE ---
let uploadedVideoList = []; // Array of { id, file, name, size, originalSize, isCompressed, isTrimmed, previewUrl, isUrl, url }
let activeTrimmerItem = null;
let trimmerPlayer = null;

function handleMultipleVideoSelection(event) {
    const files = Array.from(event.target.files || []);
    if (files.length === 0) return;

    const maxVideos = 10;
    const remainingSlots = maxVideos - uploadedVideoList.length;

    if (remainingSlots <= 0) {
        alert('You have reached the maximum limit of 10 videos/clips per property. Please remove a video before uploading more.');
        event.target.value = '';
        return;
    }

    let filesToProcess = files;
    if (files.length > remainingSlots) {
        alert(`You can only add ${remainingSlots} more video(s). Maximum 10 videos allowed. Only the first ${remainingSlots} will be attached.`);
        filesToProcess = files.slice(0, remainingSlots);
    }

    let firstLargeItemToTrim = null;

    filesToProcess.forEach(file => {
        // Allow up to 2GB local file selection so users can trim 4K/HD phone videos!
        if (file.size > 2048 * 1024 * 1024) {
            alert(`"${file.name}" is unusually large (> 2GB). Please select a video file under 2GB.`);
            return;
        }

        const videoId = 'vid_' + Date.now() + '_' + Math.random().toString(36).substr(2, 6);
        const item = {
            id: videoId,
            file: file,
            name: file.name,
            originalSize: file.size,
            size: file.size,
            isCompressed: false,
            isTrimmed: false,
            previewUrl: URL.createObjectURL(file),
            isUrl: false
        };
        uploadedVideoList.push(item);

        // Track large files for info banner
        if (file.size > 50 * 1024 * 1024 && !firstLargeItemToTrim) {
            firstLargeItemToTrim = item;
        }
    });

    event.target.value = '';
    renderVideoPreviews();
    syncVideoFormInputs();

    // Show a non-intrusive info banner for large files (no popup)
    if (firstLargeItemToTrim) {
        const sizeMB = (firstLargeItemToTrim.size / (1024 * 1024)).toFixed(0);
        const statusWrapper = document.getElementById('video-compress-status');
        const statusText = document.getElementById('compress-status-text');
        if (statusWrapper && statusText) {
            statusText.innerHTML = `📹 <strong>"${firstLargeItemToTrim.name}"</strong> (${sizeMB} MB) added. It will be <strong>auto-compressed on submit</strong> for fast upload. You can also use "Trim Clip" to select a highlight.`;
            statusWrapper.classList.remove('hidden');
            setTimeout(() => statusWrapper.classList.add('hidden'), 6000);
        }
    }
}

function addVideoUrlLink() {
    if (uploadedVideoList.length >= 10) {
        alert('You have reached the maximum limit of 10 videos/clips per property. Please remove a video before adding a new link.');
        return;
    }

    const input = document.getElementById('video-url-input');
    const url = (input.value || '').trim();
    if (!url) return;

    if (!url.startsWith('http://') && !url.startsWith('https://')) {
        alert('Please enter a valid video link starting with http:// or https://');
        return;
    }

    const videoId = 'url_' + Date.now() + '_' + Math.random().toString(36).substr(2, 6);
    uploadedVideoList.push({
        id: videoId,
        file: null,
        name: 'Video Link: ' + url.replace(/^https?:\/\/(www\.)?/, '').substring(0, 30) + '...',
        url: url,
        isUrl: true,
        isTrimmed: false,
        previewUrl: url
    });

    input.value = '';
    renderVideoPreviews();
    syncVideoFormInputs();
}

function removeVideoItem(id) {
    const idx = uploadedVideoList.findIndex(v => v.id === id);
    if (idx !== -1) {
        if (uploadedVideoList[idx].previewUrl && !uploadedVideoList[idx].isUrl) {
            URL.revokeObjectURL(uploadedVideoList[idx].previewUrl);
        }
        uploadedVideoList.splice(idx, 1);
        renderVideoPreviews();
        syncVideoFormInputs();
    }
}

// Format seconds into MM:SS
function formatTime(seconds) {
    const mins = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);
    return `${mins < 10 ? '0' : ''}${mins}:${secs < 10 ? '0' : ''}${secs}`;
}

// --- VIDEO TRIMMER MODAL LOGIC ---
function openTrimmerModal(videoId) {
    const item = uploadedVideoList.find(v => v.id === videoId);
    if (!item || item.isUrl || !item.file) return;

    activeTrimmerItem = item;
    const modal = document.getElementById('video-trimmer-modal');
    trimmerPlayer = document.getElementById('trimmer-preview-player');
    const clipTitleInput = document.getElementById('trimmer-clip-title');

    if (!modal || !trimmerPlayer) return;

    // Teleport modal to body so it floats on top of everything without footer bleed
    if (modal.parentElement !== document.body) {
        document.body.appendChild(modal);
    }

    if (clipTitleInput) clipTitleInput.value = '';
    trimmerPlayer.src = item.previewUrl;
    modal.classList.remove('hidden');

    trimmerPlayer.onloadedmetadata = () => {
        const duration = trimmerPlayer.duration || 30;
        const totalDurationEl = document.getElementById('trimmer-total-duration');
        if (totalDurationEl) totalDurationEl.textContent = formatTime(duration);

        const startSlider = document.getElementById('trimmer-start-slider');
        const endSlider = document.getElementById('trimmer-end-slider');

        if (startSlider && endSlider) {
            startSlider.min = 0;
            startSlider.max = duration.toFixed(1);
            startSlider.value = 0;

            endSlider.min = 0;
            endSlider.max = duration.toFixed(1);
            // Default to 30s clip for instant processing
            endSlider.value = Math.min(30, duration).toFixed(1);
        }

        updateTrimDisplay();
    };

    trimmerPlayer.ontimeupdate = () => {
        const currentEl = document.getElementById('trimmer-current-time');
        if (currentEl && trimmerPlayer) {
            currentEl.textContent = formatTime(trimmerPlayer.currentTime);
        }
    };
}

function closeTrimmerModal() {
    const modal = document.getElementById('video-trimmer-modal');
    if (trimmerPlayer) {
        trimmerPlayer.pause();
        trimmerPlayer.removeAttribute('src');
        trimmerPlayer.load();
    }
    const progressWrapper = document.getElementById('trimmer-progress-wrapper');
    if (progressWrapper) progressWrapper.classList.add('hidden');
    if (modal) modal.classList.add('hidden');
    activeTrimmerItem = null;
}

let selectedTrimWindow = 30; // default 30s preset duration

function highlightActivePresetBtn(sec) {
    document.querySelectorAll('.trim-preset-btn').forEach(btn => {
        btn.classList.remove('border-purple-600', 'bg-purple-50', 'dark:bg-purple-950/60', 'text-purple-700', 'dark:text-purple-300', 'font-bold', 'ring-2', 'ring-purple-600/30');
    });
    const activeBtn = document.getElementById(`trim-preset-${sec}`);
    if (activeBtn) {
        activeBtn.classList.add('border-purple-600', 'bg-purple-50', 'dark:bg-purple-950/60', 'text-purple-700', 'dark:text-purple-300', 'font-bold', 'ring-2', 'ring-purple-600/30');
    }
}

function onTrimStartSliderChange(val) {
    let startVal = parseFloat(val);
    const endSlider = document.getElementById('trimmer-end-slider');
    const maxDuration = trimmerPlayer?.duration || parseFloat(endSlider?.max || 60);

    // Automatically shift End Time with Start Time to keep selected clip duration (e.g. 60s)
    let endVal = Math.min(maxDuration, startVal + selectedTrimWindow);
    if (endSlider) {
        endSlider.value = endVal.toFixed(1);
    }

    if (trimmerPlayer) trimmerPlayer.currentTime = startVal;
    updateTrimDisplay();
}

function onTrimEndSliderChange(val) {
    let endVal = parseFloat(val);
    const startSlider = document.getElementById('trimmer-start-slider');
    const startVal = parseFloat(startSlider?.value || 0);

    if (startSlider && endVal <= startVal) {
        endVal = Math.min(parseFloat(document.getElementById('trimmer-end-slider').max), startVal + 1);
        document.getElementById('trimmer-end-slider').value = endVal.toFixed(1);
    }

    // Auto-clamp to 60s max per clip
    if (endVal - startVal > MAX_CLIP_SECONDS) {
        endVal = startVal + MAX_CLIP_SECONDS;
        document.getElementById('trimmer-end-slider').value = endVal.toFixed(1);
    }

    // Remember user's custom clip duration
    selectedTrimWindow = Math.max(1, endVal - startVal);
    highlightActivePresetBtn(Math.round(selectedTrimWindow));

    if (trimmerPlayer) trimmerPlayer.currentTime = endVal;
    updateTrimDisplay();
}

function setTrimStartToCurrent() {
    if (!trimmerPlayer) return;
    const current = trimmerPlayer.currentTime;
    const startSlider = document.getElementById('trimmer-start-slider');
    if (startSlider) {
        startSlider.value = current.toFixed(1);
        onTrimStartSliderChange(current);
    }
}

function setTrimEndToCurrent() {
    if (!trimmerPlayer) return;
    const current = trimmerPlayer.currentTime;
    const endSlider = document.getElementById('trimmer-end-slider');
    if (endSlider) {
        endSlider.value = current.toFixed(1);
        onTrimEndSliderChange(current);
    }
}

function applyTrimPreset(seconds) {
    if (!trimmerPlayer) return;
    const duration = trimmerPlayer.duration || 30;
    selectedTrimWindow = seconds;
    highlightActivePresetBtn(seconds);

    const startSlider = document.getElementById('trimmer-start-slider');
    const endSlider = document.getElementById('trimmer-end-slider');

    if (startSlider && endSlider) {
        const curStart = parseFloat(startSlider.value) || 0;
        let startVal = curStart;
        if (startVal + selectedTrimWindow > duration) {
            startVal = Math.max(0, duration - selectedTrimWindow);
            startSlider.value = startVal.toFixed(1);
        }
        const endVal = Math.min(duration, startVal + selectedTrimWindow);
        endSlider.value = endVal.toFixed(1);
        updateTrimDisplay();
        if (trimmerPlayer) trimmerPlayer.currentTime = startVal;
    }
}

function updateTrimDisplay() {
    const startSlider = document.getElementById('trimmer-start-slider');
    const endSlider = document.getElementById('trimmer-end-slider');
    const startDisplay = document.getElementById('trimmer-start-display');
    const endDisplay = document.getElementById('trimmer-end-display');
    const durationBadge = document.getElementById('trimmer-duration-badge');
    const speedBadge = document.getElementById('trimmer-speed-badge');

    if (!startSlider || !endSlider) return;

    const start = parseFloat(startSlider.value) || 0;
    const end = parseFloat(endSlider.value) || 0;
    const diff = Math.max(0, end - start);

    if (startDisplay) startDisplay.textContent = `${start.toFixed(1)}s (${formatTime(start)})`;
    if (endDisplay) endDisplay.textContent = `${end.toFixed(1)}s (${formatTime(end)})`;
    if (durationBadge) {
        durationBadge.textContent = `${diff.toFixed(1)}s`;
    }

    const estMb = Math.max(0.4, (diff * 0.16)).toFixed(1);
    if (speedBadge) {
        if (diff <= 15) {
            speedBadge.innerHTML = `<span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 text-[11px] font-bold rounded-full border border-emerald-200/50">⚡ ~${estMb} MB · Instant Upload (~1s)</span>`;
        } else if (diff <= 30) {
            speedBadge.innerHTML = `<span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 text-[11px] font-bold rounded-full border border-emerald-200/50">⚡ ~${estMb} MB · Fast Upload (~2s)</span>`;
        } else if (diff <= 60) {
            speedBadge.innerHTML = `<span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-indigo-100 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 text-[11px] font-bold rounded-full border border-indigo-200/50">🚀 ~${estMb} MB · Tour Clip (~4s)</span>`;
        } else {
            speedBadge.innerHTML = `<span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 text-[11px] font-bold rounded-full border border-amber-200/50">⚠️ ~${estMb} MB · Max 60s per clip</span>`;
        }
    }
}

function previewTrimRange() {
    if (!trimmerPlayer) return;
    const startSlider = document.getElementById('trimmer-start-slider');
    const endSlider = document.getElementById('trimmer-end-slider');

    const start = parseFloat(startSlider?.value || 0);
    const end = parseFloat(endSlider?.value || 30);

    trimmerPlayer.currentTime = start;
    trimmerPlayer.play();

    const checkTime = () => {
        if (trimmerPlayer.currentTime >= end || trimmerPlayer.paused) {
            trimmerPlayer.pause();
            trimmerPlayer.removeEventListener('timeupdate', checkTime);
        }
    };
    trimmerPlayer.addEventListener('timeupdate', checkTime);
}

// Background high-speed server uploader (takes ~0.5s for 1.5MB clip)
async function uploadCreateVideoToServer(file, onProgress) {
    return new Promise((resolve, reject) => {
        const formData = new FormData();
        formData.append('video', file);
        formData.append('_token', '{{ csrf_token() }}');

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '{{ route("property.video.upload") }}', true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.setRequestHeader('Accept', 'application/json');

        xhr.upload.onprogress = (e) => {
            if (e.lengthComputable && onProgress) {
                const pct = Math.min(99, Math.round((e.loaded / e.total) * 100));
                onProgress(pct);
            }
        };

        xhr.onload = () => {
            if (xhr.status >= 200 && xhr.status < 400) {
                try {
                    const res = JSON.parse(xhr.responseText);
                    if (res.success && res.path) {
                        resolve(res.path);
                    } else {
                        resolve(null);
                    }
                } catch(err) {
                    resolve(null);
                }
            } else {
                resolve(null);
            }
        };

        xhr.onerror = () => resolve(null);
        xhr.send(formData);
    });
}

// --- EXECUTE TRIM & EXTRACT VIDEO CLIP ---
async function executeVideoTrimming() {
    if (!activeTrimmerItem || !activeTrimmerItem.file) return;

    const startSlider = document.getElementById('trimmer-start-slider');
    const endSlider = document.getElementById('trimmer-end-slider');
    const clipTitleInput = document.getElementById('trimmer-clip-title');
    const progressWrapper = document.getElementById('trimmer-progress-wrapper');
    const progressPct = document.getElementById('trimmer-progress-pct');
    const progressBar = document.getElementById('trimmer-progress-bar');
    const submitBtn = document.getElementById('trimmer-submit-btn');
    const submitText = document.getElementById('trimmer-submit-text');
    const isReplace = document.getElementById('trimmer-mode-replace')?.checked;

    const startTime = parseFloat(startSlider?.value || 0);
    const endTime = parseFloat(endSlider?.value || 30);
    const customTitle = (clipTitleInput?.value || '').trim();

    if (progressWrapper) progressWrapper.classList.remove('hidden');
    if (submitBtn) submitBtn.disabled = true;
    if (submitText) submitText.textContent = '⚡ Extracting Clip...';

    try {
        // Step 1: Slice video at 8x speed (~4-6s)
        const trimmedBlob = await runVideoSliceAndCompress(
            activeTrimmerItem.file,
            startTime,
            endTime,
            (pct) => {
                if (progressPct) progressPct.textContent = `Processing: ${Math.round(pct)}%`;
                if (progressBar) progressBar.style.width = `${pct * 0.7}%`;
            }
        );

        const safeTitle = customTitle || activeTrimmerItem.name.replace(/\.[^/.]+$/, "") + `_clip_${Math.round(startTime)}-${Math.round(endTime)}s`;
        const clipFileName = `${safeTitle.replace(/[^a-zA-Z0-9_-]/g, '_')}.webm`;
        const clipFile = new File([trimmedBlob], clipFileName, { type: 'video/webm' });

        // Step 2: Instant Background Upload to server (~0.5s for 1.5MB)
        if (progressPct) progressPct.textContent = 'Uploading to server...';
        if (progressBar) progressBar.style.width = '85%';

        let serverPath = null;
        try {
            serverPath = await uploadCreateVideoToServer(clipFile, (upPct) => {
                if (progressPct) progressPct.textContent = `Uploading: ${upPct}%`;
                if (progressBar) progressBar.style.width = `${70 + (upPct * 0.3)}%`;
            });
        } catch(upErr) {
            console.warn('Direct upload fallback:', upErr);
        }

        // If user chose replace, replace the active item
        if (isReplace) {
            if (activeTrimmerItem.previewUrl) URL.revokeObjectURL(activeTrimmerItem.previewUrl);
            activeTrimmerItem.file = clipFile;
            activeTrimmerItem.name = customTitle ? `${customTitle} (Trimmed Clip)` : clipFileName;
            activeTrimmerItem.size = trimmedBlob.size;
            activeTrimmerItem.originalSize = trimmedBlob.size;
            activeTrimmerItem.isTrimmed = true;
            activeTrimmerItem.isCompressed = true;
            activeTrimmerItem.serverPath = serverPath;
            activeTrimmerItem.previewUrl = URL.createObjectURL(trimmedBlob);
        } else {
            if (uploadedVideoList.length >= 10) {
                alert('You have already reached the maximum limit of 10 videos/clips.');
                if (progressWrapper) progressWrapper.classList.add('hidden');
                if (submitBtn) submitBtn.disabled = false;
                if (submitText) submitText.textContent = '✂️ Cut & Add Clip to Uploads';
                return;
            }

            const newId = 'vid_clip_' + Date.now() + '_' + Math.random().toString(36).substr(2, 6);
            uploadedVideoList.push({
                id: newId,
                file: clipFile,
                name: customTitle ? `${customTitle} (Trimmed Clip)` : `Clip: ${activeTrimmerItem.name} [${startTime.toFixed(0)}s-${endTime.toFixed(0)}s]`,
                originalSize: trimmedBlob.size,
                size: trimmedBlob.size,
                isCompressed: true,
                isTrimmed: true,
                serverPath: serverPath,
                previewUrl: URL.createObjectURL(trimmedBlob),
                isUrl: false
            });

            // If the original master file was oversized (>60MB) and not yet trimmed, remove the raw master so it doesn't block submission
            const rawIdx = uploadedVideoList.findIndex(v => v.id === activeTrimmerItem.id && !v.isTrimmed && v.originalSize > 60 * 1024 * 1024);
            if (rawIdx !== -1) {
                uploadedVideoList.splice(rawIdx, 1);
            }
        }

        renderVideoPreviews();
        syncVideoFormInputs();
        closeTrimmerModal();

        // Flash message in status
        const statusWrapper = document.getElementById('video-compress-status');
        const statusText = document.getElementById('compress-status-text');
        if (statusWrapper && statusText) {
            statusText.innerHTML = `🎉 Clip successfully trimmed & added! (${uploadedVideoList.length}/10 Clips Attached · ${(trimmedBlob.size/(1024*1024)).toFixed(1)} MB).`;
            statusWrapper.classList.remove('hidden');
            setTimeout(() => statusWrapper.classList.add('hidden'), 4000);
        }

    } catch (err) {
        console.error('Trimming error:', err);
        alert('Could not trim this video format. Using full file with compression.');
    } finally {
        if (progressWrapper) progressWrapper.classList.add('hidden');
        if (submitBtn) submitBtn.disabled = false;
        if (submitText) submitText.textContent = '✂️ Cut & Add Clip to Uploads';
    }
}

// Slice video range [startTime -> endTime] and compress using Canvas & MediaRecorder
function runVideoSliceAndCompress(file, startTime, endTime, onProgress) {
    return new Promise((resolve, reject) => {
        const video = document.createElement('video');
        video.src = URL.createObjectURL(file);
        video.muted = true;
        video.playsInline = true;
        video.crossOrigin = 'anonymous';

        video.onloadedmetadata = () => {
            const duration = Math.max(0.5, Math.min(endTime, video.duration) - Math.max(0, startTime));
            let width = video.videoWidth || 960;
            let height = video.videoHeight || 540;

            const maxDimension = 960;
            if (width > maxDimension || height > maxDimension) {
                if (width > height) {
                    height = Math.round((height * maxDimension) / width);
                    width = maxDimension;
                } else {
                    width = Math.round((width * maxDimension) / height);
                    height = maxDimension;
                }
            }

            const canvas = document.createElement('canvas');
            canvas.width = (width % 2 === 0) ? width : width - 1;
            canvas.height = (height % 2 === 0) ? height : height - 1;
            const ctx = canvas.getContext('2d', { alpha: false });

            if (!canvas.captureStream || typeof MediaRecorder === 'undefined') {
                return resolve(file);
            }

            const stream = canvas.captureStream(25);

            let mimeType = 'video/webm;codecs=vp8';
            if (!MediaRecorder.isTypeSupported(mimeType)) {
                mimeType = MediaRecorder.isTypeSupported('video/webm') ? 'video/webm' : 'video/mp4';
            }

            let mediaRecorder;
            try {
                mediaRecorder = new MediaRecorder(stream, {
                    mimeType: MediaRecorder.isTypeSupported(mimeType) ? mimeType : undefined,
                    videoBitsPerSecond: 900000 // 900 kbps ultra-fast & lightweight
                });
            } catch(e) {
                mediaRecorder = new MediaRecorder(stream);
            }

            const chunks = [];
            mediaRecorder.ondataavailable = e => { if (e.data && e.data.size > 0) chunks.push(e.data); };

            let isCompleted = false;
            const finishRecording = () => {
                if (isCompleted) return;
                isCompleted = true;
                if (onProgress) onProgress(100);
                try {
                    if (mediaRecorder.state === 'recording') {
                        mediaRecorder.stop();
                    }
                } catch(e) {
                    console.warn(e);
                }
            };

            mediaRecorder.onstop = () => {
                const blob = new Blob(chunks, { type: 'video/webm' });
                URL.revokeObjectURL(video.src);
                resolve(blob);
            };

            // Set normal 1.0x playback rate for full duration & natural smooth motion
            video.playbackRate = 1.0;
            video.currentTime = startTime;
            let isRecording = false;

            video.onseeked = () => {
                if (isRecording) return;
                isRecording = true;
                try {
                    mediaRecorder.start(100);
                } catch(e) {
                    return resolve(file);
                }
                video.play().catch(e => {
                    return resolve(file);
                });

                const drawFrame = () => {
                    if (isCompleted) return;
                    if (!video.paused && !video.ended) {
                        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                        const progress = Math.min(99, Math.max(1, ((video.currentTime - startTime) / (endTime - startTime)) * 100));
                        if (onProgress) onProgress(progress);
                    }
                    if (video.currentTime >= endTime || video.ended) {
                        finishRecording();
                        return;
                    }
                    requestAnimationFrame(drawFrame);
                };
                requestAnimationFrame(drawFrame);

                const timeoutMs = ((endTime - startTime) + 3) * 1000;
                setTimeout(() => finishRecording(), timeoutMs);
            };
        };

        video.onerror = () => resolve(file);
    });
}

// Client-Side Full Video Compressor
async function compressVideoItem(id) {
    const item = uploadedVideoList.find(v => v.id === id);
    if (!item || item.isUrl || !item.file) return;

    const statusWrapper = document.getElementById('video-compress-status');
    const statusText = document.getElementById('compress-status-text');
    const statusPct = document.getElementById('compress-status-pct');
    const progressBar = document.getElementById('compress-progress-bar');

    if (statusWrapper) statusWrapper.classList.remove('hidden');
    if (statusText) statusText.textContent = `Optimizing "${item.name}" for fast posting...`;

    try {
        const compressedBlob = await runVideoCompression(item.file, (pct) => {
            if (statusPct) statusPct.textContent = `${Math.round(pct)}%`;
            if (progressBar) progressBar.style.width = `${pct}%`;
        });

        if (compressedBlob && compressedBlob.size < item.file.size) {
            const compressedFile = new File([compressedBlob], item.name.replace(/\.[^/.]+$/, "") + "_compressed.webm", {
                type: 'video/webm'
            });
            item.file = compressedFile;
            item.size = compressedBlob.size;
            item.isCompressed = true;
            if (item.previewUrl) URL.revokeObjectURL(item.previewUrl);
            item.previewUrl = URL.createObjectURL(compressedBlob);
            
            if (statusText) statusText.textContent = `Done! Reduced from ${(item.originalSize / (1024*1024)).toFixed(1)}MB to ${(item.size / (1024*1024)).toFixed(1)}MB 🎉`;
            setTimeout(() => { if (statusWrapper) statusWrapper.classList.add('hidden'); }, 3500);
        } else {
            if (statusText) statusText.textContent = 'Video is already at optimal size!';
            setTimeout(() => { if (statusWrapper) statusWrapper.classList.add('hidden'); }, 2500);
        }
    } catch (e) {
        console.warn('Compression skipped, using original video file:', e);
        if (statusWrapper) statusWrapper.classList.add('hidden');
    }

    renderVideoPreviews();
    syncVideoFormInputs();
}

function runVideoCompression(file, onProgress) {
    return new Promise((resolve) => {
        const video = document.createElement('video');
        video.src = URL.createObjectURL(file);
        video.muted = true;
        video.playsInline = true;
        
        video.onloadedmetadata = () => {
            let width = video.videoWidth || 1280;
            let height = video.videoHeight || 720;
            
            const maxDimension = 1280;
            if (width > maxDimension || height > maxDimension) {
                if (width > height) {
                    height = Math.round((height * maxDimension) / width);
                    width = maxDimension;
                } else {
                    width = Math.round((width * maxDimension) / height);
                    height = maxDimension;
                }
            }

            const canvas = document.createElement('canvas');
            canvas.width = width;
            canvas.height = height;
            const ctx = canvas.getContext('2d');

            if (!canvas.captureStream || typeof MediaRecorder === 'undefined') {
                return resolve(file);
            }

            const stream = canvas.captureStream(25);
            let mediaRecorder;
            try {
                mediaRecorder = new MediaRecorder(stream, {
                    mimeType: MediaRecorder.isTypeSupported('video/webm;codecs=vp8') ? 'video/webm;codecs=vp8' : 'video/webm',
                    videoBitsPerSecond: 1800000
                });
            } catch(e) {
                return resolve(file);
            }

            const chunks = [];
            mediaRecorder.ondataavailable = e => { if (e.data && e.data.size > 0) chunks.push(e.data); };
            mediaRecorder.onstop = () => {
                const blob = new Blob(chunks, { type: 'video/webm' });
                URL.revokeObjectURL(video.src);
                resolve(blob);
            };

            // 🚀 8x FAST PROCESSING for full-video compression too!
            video.playbackRate = 8;
            mediaRecorder.start(100);
            video.currentTime = 0;
            video.play();

            const drawFrame = () => {
                if (video.paused || video.ended) {
                    if (video.ended && mediaRecorder.state === 'recording') {
                        mediaRecorder.stop();
                    }
                    return;
                }
                ctx.drawImage(video, 0, 0, width, height);
                if (video.duration) {
                    const progress = (video.currentTime / video.duration) * 100;
                    onProgress(Math.min(progress, 99));
                }
                requestAnimationFrame(drawFrame);
            };

            video.onplay = () => requestAnimationFrame(drawFrame);
            video.onended = () => {
                if (mediaRecorder.state === 'recording') mediaRecorder.stop();
            };

            // Safety timeout: even at 8x, cap at 120s max processing
            setTimeout(() => {
                if (mediaRecorder.state === 'recording') {
                    mediaRecorder.stop();
                }
            }, 120000);
        };

        video.onerror = () => resolve(file);
    });
}

function renderVideoPreviews() {
    const container = document.getElementById('video-previews-container');
    if (!container) return;

    if (uploadedVideoList.length === 0) {
        container.innerHTML = '';
        return;
    }

    const isMax = uploadedVideoList.length >= 10;

    let html = `<div class="p-3 bg-purple-50/60 dark:bg-purple-950/30 rounded-xl border border-purple-100 dark:border-purple-900/40 mb-2 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
        <div class="flex items-center gap-2">
            <span class="text-xs font-bold text-purple-900 dark:text-purple-300 flex items-center gap-1.5">
                <i class="ph-bold ph-video text-purple-600"></i> ${uploadedVideoList.length} / 10 Video Clips Attached
            </span>
            ${isMax ? `<span class="px-2 py-0.5 bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 text-[10px] font-extrabold rounded-md">Max 10 Reached</span>` : ''}
        </div>
        ${!isMax ? `
            <button type="button" onclick="document.getElementById('create-video-input').click()" class="text-[11px] font-extrabold text-purple-700 dark:text-purple-300 hover:underline flex items-center gap-1 cursor-pointer">
                <i class="ph-bold ph-plus-circle"></i> Add More Videos / Clips (${10 - uploadedVideoList.length} slots left)
            </button>
        ` : `
            <span class="text-[11px] text-slate-400 font-semibold">Maximum 10 clips limit reached</span>
        `}
    </div>`;

    uploadedVideoList.forEach((v, index) => {
        const sizeMb = v.size ? (v.size / (1024 * 1024)).toFixed(1) + ' MB' : '';

        html += `
        <div class="relative rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800/90 shadow-sm p-3.5 flex flex-col sm:flex-row items-center gap-3">
            ${v.isUrl ? `
                <div class="w-full sm:w-36 h-20 rounded-lg bg-slate-900 flex flex-col items-center justify-center text-purple-400 shrink-0">
                    <i class="ph-bold ph-link text-2xl mb-0.5"></i>
                    <span class="text-[10px] text-slate-400 font-bold uppercase">External Tour</span>
                </div>
            ` : `
                <div class="w-full sm:w-36 h-20 rounded-lg bg-black overflow-hidden shrink-0 relative group">
                    <video src="${v.previewUrl}" class="w-full h-full object-cover" preload="metadata"></video>
                    <span class="absolute bottom-1 right-1 px-1.5 py-0.5 bg-black/80 text-white text-[9px] font-bold rounded">Clip ${index + 1}</span>
                </div>
            `}

            <div class="flex-1 min-w-0 w-full">
                <div class="flex flex-wrap items-center gap-2 mb-1">
                    <span class="text-xs font-bold text-slate-900 dark:text-white truncate max-w-xs">${v.name}</span>
                    ${v.isTrimmed ? `<span class="px-2 py-0.5 bg-purple-100 dark:bg-purple-950/60 text-purple-700 dark:text-purple-300 font-extrabold text-[10px] rounded-md shrink-0 flex items-center gap-1"><i class="ph-bold ph-scissors"></i> Trimmed Clip</span>` : ''}
                    ${v.isCompressed ? `<span class="px-2 py-0.5 bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 font-extrabold text-[10px] rounded-md shrink-0 flex items-center gap-1"><i class="ph-bold ph-lightning"></i> Fast Web Clip</span>` : ''}
                </div>
                
                <div class="flex flex-wrap items-center gap-2.5 text-xs text-slate-500 dark:text-slate-400">
                    ${v.isUrl ? `
                        <a href="${v.url}" target="_blank" class="text-purple-600 dark:text-purple-400 hover:underline truncate max-w-xs text-[11px]">${v.url}</a>
                    ` : `
                        <span>Size: <strong class="text-slate-700 dark:text-slate-200">${sizeMb}</strong></span>
                        <div class="flex flex-wrap items-center gap-1.5">
                            <button type="button" onclick="openTrimmerModal('${v.id}')" class="px-2.5 py-1 bg-purple-600 hover:bg-purple-700 text-white font-bold text-[11px] rounded-lg shadow-xs flex items-center gap-1 cursor-pointer">
                                <i class="ph-bold ph-scissors"></i> Trim Clip
                            </button>
                            ${(!v.isCompressed && v.size > 10 * 1024 * 1024) ? `
                                <button type="button" onclick="compressVideoItem('${v.id}')" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] rounded-lg shadow-xs flex items-center gap-1 cursor-pointer">
                                    <i class="ph-bold ph-lightning"></i> Fast Compress
                                </button>
                            ` : ''}
                        </div>
                    `}
                </div>
            </div>

            <button type="button" onclick="removeVideoItem('${v.id}')" class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-950/40 rounded-lg transition-all shrink-0 cursor-pointer" title="Remove Video Clip">
                <i class="ph-bold ph-trash text-base"></i>
            </button>
        </div>`;
    });

    container.innerHTML = html;
}

function syncVideoFormInputs() {
    const form = document.getElementById('create-property-form');
    if (!form) return;

    // Remove existing hidden video file & url inputs
    form.querySelectorAll('.sync-video-input').forEach(el => el.remove());

    // Sync file uploads using DataTransfer
    const fileItems = uploadedVideoList.filter(v => !v.isUrl && v.file);
    if (fileItems.length > 0) {
        const dt = new DataTransfer();
        fileItems.forEach(item => dt.items.add(item.file));

        const hiddenFileInput = document.createElement('input');
        hiddenFileInput.type = 'file';
        hiddenFileInput.name = 'videos[]';
        hiddenFileInput.multiple = true;
        hiddenFileInput.className = 'hidden sync-video-input';
        hiddenFileInput.files = dt.files;
        form.appendChild(hiddenFileInput);
    }

    // Sync pre-uploaded server paths
    const preUploadedItems = uploadedVideoList.filter(v => v.serverPath);
    preUploadedItems.forEach(item => {
        const hiddenPathInput = document.createElement('input');
        hiddenPathInput.type = 'hidden';
        hiddenPathInput.name = 'uploaded_video_paths[]';
        hiddenPathInput.value = item.serverPath;
        hiddenPathInput.className = 'sync-video-input';
        form.appendChild(hiddenPathInput);
    });

    // Sync URL links
    const urlItems = uploadedVideoList.filter(v => v.isUrl && v.url);
    urlItems.forEach(item => {
        const hiddenUrlInput = document.createElement('input');
        hiddenUrlInput.type = 'hidden';
        hiddenUrlInput.name = 'video_urls[]';
        hiddenUrlInput.value = item.url;
        hiddenUrlInput.className = 'sync-video-input';
        form.appendChild(hiddenUrlInput);
    });
}

// Dynamic Purpose UI Label Updater
window.onPurposeChange = function(purpose) {
    const priceLabel = document.getElementById('create-price-label');
    const priceInput = document.getElementById('create-price');
    const periodCol = document.getElementById('period-input-col');
    const saleCol = document.getElementById('sale-badge-col');
    const periodSelect = document.getElementById('create-period');

    if (purpose === 'buy' || purpose === 'sell') {
        if (priceLabel) priceLabel.innerHTML = 'Total Sale Price (₹) <span class="text-red-500">*</span>';
        if (priceInput) priceInput.placeholder = '45,00,000';
        if (periodCol) periodCol.classList.add('hidden');
        if (saleCol) saleCol.classList.remove('hidden');
        if (periodSelect) periodSelect.value = 'month'; // Default backend safe fallback
    } else {
        if (priceLabel) priceLabel.innerHTML = 'Expected Rent (₹) <span class="text-red-500">*</span>';
        if (priceInput) priceInput.placeholder = '25,000';
        if (periodCol) periodCol.classList.remove('hidden');
        if (saleCol) saleCol.classList.add('hidden');
    }
};

// Initialize on load
document.addEventListener('DOMContentLoaded', () => {
    const checkedPurpose = document.querySelector('input[name="purpose"]:checked');
    if (checkedPurpose) {
        window.onPurposeChange(checkedPurpose.value);
    }
});

// Quick auto-optimize medium videos (25–60MB) by extracting first 30s clip at 8x speed (~4 seconds!)
async function autoCompressOversizedVideos() {
    const MAX_SAFE = 60 * 1024 * 1024; // 60MB
    const MIN_COMPRESS = 25 * 1024 * 1024; // 25MB
    const toCompress = uploadedVideoList.filter(v => !v.isUrl && v.file && !v.isCompressed && v.file.size > MIN_COMPRESS && v.file.size <= MAX_SAFE);
    if (toCompress.length === 0) return true;

    const statusWrapper = document.getElementById('video-compress-status');
    const statusText = document.getElementById('compress-status-text');
    const statusPct = document.getElementById('compress-status-pct');
    const progressBar = document.getElementById('compress-progress-bar');

    for (let i = 0; i < toCompress.length; i++) {
        const item = toCompress[i];
        const label = `(${i+1}/${toCompress.length}) "${item.name}"`;

        if (statusWrapper) statusWrapper.classList.remove('hidden');
        if (statusText) statusText.innerHTML = `⚡ Quick-optimizing ${label}...`;
        if (statusPct) statusPct.textContent = '0%';
        if (progressBar) progressBar.style.width = '0%';

        try {
            // Use fast slice (first 30s) instead of processing entire video
            const compressedBlob = await runVideoSliceAndCompress(item.file, 0, 30, (pct) => {
                if (statusPct) statusPct.textContent = `${Math.round(pct)}%`;
                if (progressBar) progressBar.style.width = `${pct}%`;
            });

            if (compressedBlob && compressedBlob.size < item.file.size) {
                const compressedFile = new File([compressedBlob], item.name.replace(/\.[^/.]+$/, '') + '_optimized.webm', {
                    type: 'video/webm'
                });
                if (item.previewUrl) URL.revokeObjectURL(item.previewUrl);
                item.file = compressedFile;
                item.size = compressedBlob.size;
                item.isCompressed = true;
                item.isTrimmed = true;
                item.previewUrl = URL.createObjectURL(compressedBlob);
            }
        } catch (err) {
            console.warn('Auto-compress skipped for', item.name, err);
        }
    }

    syncVideoFormInputs();
    if (statusWrapper) statusWrapper.classList.add('hidden');
    return true;
}

// Submission validation and instant loader
document.getElementById('create-property-form')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const form = this;

    // 1. Validate that at least one photo or video is attached
    const hasPhotos = (typeof selectedFiles !== 'undefined' && selectedFiles.length > 0) || 
                      (document.getElementById('create-images-input')?.files?.length > 0);
    const hasVideos = (typeof uploadedVideoList !== 'undefined' && uploadedVideoList.length > 0);

    if (!hasPhotos && !hasVideos) {
        alert('Please upload at least one photo or video tour for your property listing.');
        document.getElementById('create-images')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return;
    }

    // 2. Automatically clean up any raw uncompressed files > 60MB if trimmed clips exist
    const MAX_SAFE_SIZE = 60 * 1024 * 1024;
    const hasTrimmedClips = uploadedVideoList.some(v => v.isTrimmed || v.isCompressed);
    if (hasTrimmedClips) {
        uploadedVideoList = uploadedVideoList.filter(v => v.isUrl || !v.file || v.file.size <= MAX_SAFE_SIZE || v.isTrimmed || v.isCompressed);
        syncVideoFormInputs();
        renderVideoPreviews();
    }

    // 3. Check if any untrimmed raw file > 60MB still remains
    const tooBigFiles = (typeof uploadedVideoList !== 'undefined') ? uploadedVideoList.filter(v => !v.isUrl && v.file && v.file.size > MAX_SAFE_SIZE && !v.isTrimmed && !v.isCompressed) : [];
    if (tooBigFiles.length > 0) {
        const item = tooBigFiles[0];
        const sizeMb = (item.file.size / (1024 * 1024)).toFixed(0);
        alert(`"${item.name}" is ${sizeMb}MB — too large to upload directly.\n\nPlease use "Trim Clip" to cut a short highlight (15s–30s). It takes seconds and uploads instantly!`);
        openTrimmerModal(item.id);
        return;
    }

    const submitBtn = document.getElementById('create-submit');
    const submitIcon = document.getElementById('submit-icon');
    const submitLabel = document.getElementById('submit-label');

    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.classList.remove('btn-submitting');
        if (submitIcon) submitIcon.className = 'ph-bold ph-spinner animate-spin text-lg';
        if (submitLabel) submitLabel.textContent = '🚀 Publishing...';
    }

    // 4. Sync inputs & construct FormData for high-speed AJAX upload with live progress
    syncVideoFormInputs();
    const formData = new FormData(form);

    const xhr = new XMLHttpRequest();
    xhr.open('POST', form.action, true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.setRequestHeader('Accept', 'application/json');
    xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

    xhr.upload.onprogress = function(evt) {
        if (evt.lengthComputable && submitLabel) {
            const pct = Math.min(99, Math.round((evt.loaded / evt.total) * 100));
            submitLabel.textContent = `🚀 Uploading ${pct}%...`;
        }
    };

    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 400) {
            if (submitLabel) submitLabel.textContent = '✅ Published! Redirecting...';
            try {
                const res = JSON.parse(xhr.responseText);
                window.location.href = res.redirect_url || '{{ route("dashboard") }}';
            } catch (e) {
                window.location.href = '{{ route("dashboard") }}';
            }
        } else {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.classList.remove('btn-submitting');
            }
            if (submitIcon) submitIcon.className = 'ph-bold ph-paper-plane-tilt text-base';
            if (submitLabel) submitLabel.textContent = 'Publish Property Listing';
            try {
                const res = JSON.parse(xhr.responseText);
                const errors = res.errors ? Object.values(res.errors).flat().join('\n') : (res.message || 'An error occurred.');
                alert('Error publishing property:\n' + errors);
            } catch (e) {
                alert('An error occurred. Please try submitting again.');
            }
        }
    };

    xhr.onerror = function() {
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('btn-submitting');
        }
        if (submitIcon) submitIcon.className = 'ph-bold ph-paper-plane-tilt text-base';
        if (submitLabel) submitLabel.textContent = 'Publish Property Listing';
        alert('Network error. Please check your internet connection.');
    };

    xhr.send(formData);
});
</script>

@endsection
