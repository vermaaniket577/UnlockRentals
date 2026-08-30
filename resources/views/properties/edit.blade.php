@extends('layouts.app')

@section('title', 'Edit: ' . $property->title . ' - UnlockRentals')
@section('meta_description', 'Update property details, photos, video walkthroughs, and pricing on UnlockRentals.')

@section('content')

<section class="min-h-screen pt-28 sm:pt-32 pb-24 bg-[#f8fafc] dark:bg-slate-950 relative overflow-hidden" id="edit-property">
    {{-- Ambient Background Gradients --}}
    <div class="absolute top-0 left-0 w-full h-[500px] bg-gradient-to-b from-blue-500/[0.04] via-indigo-500/[0.02] to-transparent pointer-events-none"></div>
    <div class="absolute -top-32 -left-32 w-96 h-96 bg-blue-500/10 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute top-1/3 right-0 w-80 h-80 bg-indigo-500/10 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        {{-- Page Header --}}
        <div class="mb-10 text-center">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1 bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 text-xs font-extrabold uppercase tracking-widest rounded-full mb-3 border border-blue-100 dark:border-blue-900/50">
                <i class="ph-bold ph-pencil-simple text-xs"></i> Edit Property Listing
            </span>
            <h1 class="text-3xl sm:text-5xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-tight">
                Edit Property
            </h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm sm:text-base font-normal max-w-xl mx-auto mt-2.5 leading-relaxed">
                Update your listing details, photos, video walkthroughs, and pricing.
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
                    <span class="text-[11px] sm:text-xs font-bold text-slate-500 dark:text-slate-400">Media</span>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('properties.update', $property) }}" enctype="multipart/form-data" class="space-y-8" id="edit-property-form" data-ur-loader-skip="true" data-no-smooth="true">
            @csrf
            @method('PUT')

            {{-- 1. Basic Info Section --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 sm:p-8 shadow-xs" id="edit-basic-info">
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
                        <label for="edit-title" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Property Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="edit-title" value="{{ old('title', $property->title) }}" required
                               class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white placeholder-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all"
                               placeholder="e.g. Spacious 3 BHK Semi-Furnished Flat in Sector 57">
                        @error('title') <p class="text-red-500 text-xs mt-1.5 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="edit-description" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Description <span class="text-red-500">*</span></label>
                        <textarea name="description" id="edit-description" rows="4" required
                                  class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all resize-none"
                                  placeholder="Describe the key features, nearby amenities, sunlight, balcony view, and tenant preferences...">{{ old('description', $property->description) }}</textarea>
                        @error('description') <p class="text-red-500 text-xs mt-1.5 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    {{-- Listing Purpose (Rent vs Sell) --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2.5">Listing Purpose (Intent) <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-w-xl">
                            @php
                                $editPurpose = old('purpose', $property->purpose ?? 'rent');
                            @endphp
                            <label class="relative flex items-center gap-3 p-3.5 rounded-xl border cursor-pointer transition-all {{ ($editPurpose === 'rent') ? 'border-blue-600 bg-blue-50/70 dark:bg-blue-950/40 text-blue-700 dark:text-blue-400 font-bold ring-1 ring-blue-600 shadow-sm' : 'border-slate-200 dark:border-slate-700/80 bg-slate-50/50 dark:bg-slate-800/40 hover:border-slate-300 text-slate-600 dark:text-slate-400 font-medium' }}" for="edit-purpose-rent">
                                <input type="radio" name="purpose" value="rent" id="edit-purpose-rent" {{ $editPurpose === 'rent' ? 'checked' : '' }} class="sr-only" onchange="this.closest('.grid').querySelectorAll('label').forEach(l => l.classList.remove('border-blue-600','bg-blue-50/70','dark:bg-blue-950/40','text-blue-700','dark:text-blue-400','font-bold','ring-1','ring-blue-600','shadow-sm')); this.closest('label').classList.add('border-blue-600','bg-blue-50/70','dark:bg-blue-950/40','text-blue-700','dark:text-blue-400','font-bold','ring-1','ring-blue-600','shadow-sm'); if(window.onEditPurposeChange) window.onEditPurposeChange('rent');">
                                <div class="w-8 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center shrink-0">
                                    <i class="ph-bold ph-key text-base"></i>
                                </div>
                                <div>
                                    <div class="text-xs sm:text-sm font-bold leading-tight">For Rent</div>
                                    <div class="text-[11px] text-slate-500 dark:text-slate-400 font-normal">Monthly Lease / Rental Income</div>
                                </div>
                            </label>

                            <label class="relative flex items-center gap-3 p-3.5 rounded-xl border cursor-pointer transition-all {{ in_array($editPurpose, ['buy', 'sell']) ? 'border-blue-600 bg-blue-50/70 dark:bg-blue-950/40 text-blue-700 dark:text-blue-400 font-bold ring-1 ring-blue-600 shadow-sm' : 'border-slate-200 dark:border-slate-700/80 bg-slate-50/50 dark:bg-slate-800/40 hover:border-slate-300 text-slate-600 dark:text-slate-400 font-medium' }}" for="edit-purpose-buy">
                                <input type="radio" name="purpose" value="buy" id="edit-purpose-buy" {{ in_array($editPurpose, ['buy', 'sell']) ? 'checked' : '' }} class="sr-only" onchange="this.closest('.grid').querySelectorAll('label').forEach(l => l.classList.remove('border-blue-600','bg-blue-50/70','dark:bg-blue-950/40','text-blue-700','dark:text-blue-400','font-bold','ring-1','ring-blue-600','shadow-sm')); this.closest('label').classList.add('border-blue-600','bg-blue-50/70','dark:bg-blue-950/40','text-blue-700','dark:text-blue-400','font-bold','ring-1','ring-blue-600','shadow-sm'); if(window.onEditPurposeChange) window.onEditPurposeChange('buy');">
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
                                $curType = old('type', $property->type ?? 'house');
                            @endphp
                            @foreach($formTypes as $val => $info)
                            <label class="relative flex flex-col items-center justify-center p-3 rounded-xl border cursor-pointer transition-all {{ ($curType === $val) ? 'border-blue-600 bg-blue-50/70 dark:bg-blue-950/40 text-blue-700 dark:text-blue-400 font-bold ring-1 ring-blue-600' : 'border-slate-200 dark:border-slate-700/80 bg-slate-50/50 dark:bg-slate-800/40 hover:border-slate-300 text-slate-600 dark:text-slate-400 font-medium' }}" for="edit-type-{{ $val }}">
                                <input type="radio" name="type" value="{{ $val }}" id="edit-type-{{ $val }}" {{ $curType === $val ? 'checked' : '' }} class="sr-only" onchange="this.closest('.grid').querySelectorAll('label').forEach(l => l.classList.remove('border-blue-600','bg-blue-50/70','dark:bg-blue-950/40','text-blue-700','dark:text-blue-400','font-bold','ring-1','ring-blue-600')); this.closest('label').classList.add('border-blue-600','bg-blue-50/70','dark:bg-blue-950/40','text-blue-700','dark:text-blue-400','font-bold','ring-1','ring-blue-600');">
                                <i class="{{ $info['icon'] }} text-xl mb-1 text-blue-600"></i>
                                <span class="text-xs text-center leading-tight">{{ $info['label'] }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Category & Phone Row --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="edit-category" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Category <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select name="category_id" id="edit-category" required
                                        class="w-full pl-4 pr-9 py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all appearance-none cursor-pointer">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $property->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <i class="ph-bold ph-caret-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                            </div>
                        </div>

                        <div>
                            <label for="edit-phone" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Contact Phone Number <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"><i class="ph-bold ph-phone"></i></span>
                                <input type="tel" name="contact_phone" id="edit-phone" value="{{ old('contact_phone', $property->contact_phone ?? $property->owner->phone ?? auth()->user()->phone) }}" required
                                       class="w-full pl-10 pr-4 py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white placeholder-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all"
                                       placeholder="+91 94254 55499">
                            </div>
                            @error('contact_phone') <p class="text-red-500 text-xs mt-1.5 font-semibold">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. Pricing Section --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 sm:p-8 shadow-xs" id="edit-pricing">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 flex items-center justify-center">
                        <i class="ph-bold ph-currency-inr text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white" id="edit-pricing-title">Pricing & Financials</h2>
                        <p class="text-xs text-slate-400" id="edit-pricing-desc">Update your expected rent or sale price</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5" id="pricing-grid">
                    <div>
                        <label for="edit-price" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2" id="edit-price-label">
                            {{ ($property->purpose === 'buy' || $property->purpose === 'sell') ? 'Total Sale Price (₹) *' : 'Expected Rent (₹) *' }}
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 font-extrabold text-sm">₹</span>
                            <input type="number" name="price" id="edit-price" value="{{ old('price', $property->price) }}" required min="0" step="0.01"
                                   class="w-full pl-9 pr-4 py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white placeholder-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all"
                                   placeholder="25,000">
                        </div>
                        @error('price') <p class="text-red-500 text-xs mt-1.5 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    {{-- Rent Billing Cycle (Per Month / Per Year) --}}
                    <div id="edit-period-col" class="{{ ($property->purpose === 'buy' || $property->purpose === 'sell') ? 'hidden' : '' }}">
                        <label for="edit-period" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2" id="edit-period-label">Billing Cycle <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select name="price_period" id="edit-period" required
                                    class="w-full pl-4 pr-9 py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all appearance-none cursor-pointer">
                                <option value="month" {{ old('price_period', $property->price_period) === 'month' ? 'selected' : '' }}>Per Month</option>
                                <option value="year" {{ old('price_period', $property->price_period) === 'year' ? 'selected' : '' }}>Per Year</option>
                            </select>
                            <i class="ph-bold ph-caret-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                        </div>
                    </div>

                    {{-- Sale Pricing Mode Badge (Shown when Selling) --}}
                    <div id="edit-sale-badge-col" class="{{ ($property->purpose === 'buy' || $property->purpose === 'sell') ? '' : 'hidden' }}">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Payment Mode</label>
                        <div class="h-[46px] px-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/50 rounded-xl text-xs font-bold text-emerald-800 dark:text-emerald-300 flex items-center gap-2 shadow-2xs">
                            <i class="ph-bold ph-tag text-emerald-600 text-base shrink-0"></i>
                            <span>One-Time Total Purchase Price (No recurring rent)</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. Location Details --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 sm:p-8 shadow-xs" id="edit-location">
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
                            <label for="edit-state" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">State <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select name="state" id="edit-state" required onchange="if(window.handleLocationStateChange) window.handleLocationStateChange(this, 'edit-city', 'edit-locality-select');"
                                        class="w-full pl-4 pr-9 py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all appearance-none cursor-pointer">
                                    <option value="">Select State</option>
                                    @php $statesList = $globalAllStates ?? $allStates ?? []; @endphp
                                    @if(!empty($statesList))
                                        @foreach($statesList as $code => $name)
                                            <option value="{{ $code }}" {{ old('state', $property->state) == $code ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <i class="ph-bold ph-caret-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                            </div>
                            @error('state') <p class="text-red-500 text-xs mt-1.5 font-semibold">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="edit-city" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">City / District <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select name="location" id="edit-city" required onchange="if(window.handleLocationCityChange) window.handleLocationCityChange(this, 'edit-locality-select', 'edit-state');"
                                        class="w-full pl-4 pr-9 py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all appearance-none cursor-pointer">
                                    <option value="">Select District</option>
                                    @php $districtsList = $globalAllDistricts ?? $allDistricts ?? []; @endphp
                                    @if(!empty($districtsList))
                                        @foreach($districtsList as $d)
                                            @php
                                                $dSlug = $d['slug'] ?? strtolower(str_replace(' ', '-', $d['name']));
                                                $isSelected = (old('location', $property->location) === $dSlug || old('location', $property->location) === $d['name']);
                                            @endphp
                                            <option value="{{ $d['name'] }}" {{ $isSelected ? 'selected' : '' }}>
                                                {{ $d['name'] }}{{ !empty($d['state_code']) ? ' (' . $d['state_code'] . ')' : '' }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <i class="ph-bold ph-caret-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                            </div>
                            @error('location') <p class="text-red-500 text-xs mt-1.5 font-semibold">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    
                    <div>
                        <label for="edit-locality-select" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Locality / Sector / Area <span class="text-red-500">*</span></label>
                        <div id="locality-select-wrap">
                            <div class="relative">
                                <select name="locality" id="edit-locality-select"
                                        class="w-full pl-4 pr-9 py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all appearance-none cursor-pointer">
                                    <option value="">Select City First</option>
                                </select>
                                <i class="ph-bold ph-caret-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                            </div>
                        </div>
                        <div id="locality-text-wrap" style="display: none;">
                            <div class="relative">
                                <i class="ph-bold ph-map-pin absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                                <input type="text" name="locality" id="edit-locality-text" value="{{ old('locality', $property->locality) }}" 
                                       class="w-full pl-10 pr-4 py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white placeholder-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all"
                                       placeholder="e.g. Sector 57, Sushant Lok">
                            </div>
                        </div>
                        @error('locality') <p class="text-red-500 text-xs mt-1.5 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="edit-address" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Complete Address <span class="text-red-500">*</span></label>
                        <input type="text" name="address" id="edit-address" value="{{ old('address', $property->address) }}" required
                               class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white placeholder-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all"
                               placeholder="Flat/House No, Building Name, Street / Landmark">
                        @error('address') <p class="text-red-500 text-xs mt-1.5 font-semibold">{{ $message }}</p> @enderror
                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        if (typeof window.initLocationCascading === 'function') {
                            window.initLocationCascading({
                                stateId: 'edit-state',
                                cityId: 'edit-city',
                                localityId: 'edit-locality-select',
                                localityTextWrapId: 'locality-text-wrap',
                                localitySelectWrapId: 'locality-select-wrap',
                                selectedState: "{{ old('state', $property->state) }}",
                                selectedCity: "{{ old('location', $property->location) }}",
                                selectedLocality: "{{ old('locality', $property->locality) }}"
                            });
                        }
                    });
                </script>
            </div>

            {{-- 4. Key Specifications --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 sm:p-8 shadow-xs" id="edit-details">
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
                        <label for="edit-bedrooms" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Bedrooms (BHK)</label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"><i class="ph-bold ph-bed"></i></span>
                            <input type="number" name="bedrooms" id="edit-bedrooms" value="{{ old('bedrooms', $property->bedrooms) }}" min="0" max="20"
                                   class="w-full pl-10 pr-4 py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white placeholder-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all"
                                   placeholder="3">
                        </div>
                    </div>
                    <div>
                        <label for="edit-bathrooms" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Bathrooms</label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"><i class="ph-bold ph-drop"></i></span>
                            <input type="number" name="bathrooms" id="edit-bathrooms" value="{{ old('bathrooms', $property->bathrooms) }}" min="0" max="20"
                                   class="w-full pl-10 pr-4 py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white placeholder-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all"
                                   placeholder="2">
                        </div>
                    </div>
                    <div>
                        <label for="edit-area" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Area (sq.ft)</label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"><i class="ph-bold ph-square-half"></i></span>
                            <input type="number" name="area_sqft" id="edit-area" value="{{ old('area_sqft', $property->area_sqft) }}" min="0"
                                   class="w-full pl-10 pr-4 py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white placeholder-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all"
                                   placeholder="1250">
                        </div>
                    </div>
                </div>

                <div class="mt-5">
                    <label for="edit-furnishing" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Furnishing Status <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="furnishing" id="edit-furnishing" required
                                class="w-full pl-4 pr-9 py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all appearance-none cursor-pointer">
                            <option value="unfurnished" {{ old('furnishing', $property->furnishing) === 'unfurnished' ? 'selected' : '' }}>Unfurnished</option>
                            <option value="semi-furnished" {{ old('furnishing', $property->furnishing) === 'semi-furnished' ? 'selected' : '' }}>Semi-Furnished</option>
                            <option value="fully-furnished" {{ old('furnishing', $property->furnishing) === 'fully-furnished' ? 'selected' : '' }}>Fully Furnished</option>
                        </select>
                        <i class="ph-bold ph-caret-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                    </div>
                </div>
            </div>

            {{-- 5. Media & Photo Gallery --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 sm:p-8 shadow-xs" id="edit-images">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 flex items-center justify-center">
                            <i class="ph-bold ph-images-square text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-slate-900 dark:text-white">Property Photos & Media</h2>
                            <p class="text-xs text-slate-400">Manage existing images and add new high-resolution photos</p>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-950/60 px-3 py-1 rounded-lg self-start sm:self-auto border border-blue-200/60 dark:border-blue-800">Min 1 Photo or Video Tour *</span>
                </div>

                {{-- Existing Images --}}
                @if($property->images->count() > 0)
                <div class="mb-6 p-4 sm:p-5 bg-slate-50/70 dark:bg-slate-800/40 rounded-2xl border border-slate-200/70 dark:border-slate-700/60">
                    <p class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-3 flex items-center gap-2">
                        <i class="ph-bold ph-images text-blue-600"></i> Current Images ({{ $property->images->count() }})
                        <span class="text-[11px] font-normal text-slate-400 normal-case ml-auto">Check box to delete on save</span>
                    </p>
                    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 gap-3.5">
                        @foreach($property->images as $image)
                        <div class="relative group rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700 bg-black aspect-square shadow-2xs">
                            <img src="{{ $image->imageUrl() }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            
                            <label class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 peer-checked:opacity-100 flex items-center justify-center cursor-pointer transition-opacity backdrop-blur-[2px]">
                                <input type="checkbox" name="remove_images[]" value="{{ $image->id }}" class="sr-only peer">
                                <div class="px-2.5 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg flex items-center gap-1 shadow-md scale-95 group-hover:scale-100 transition-all">
                                    <i class="ph-bold ph-trash"></i> Remove
                                </div>
                            </label>
                            
                            @if($image->is_primary)
                            <span class="absolute top-2 left-2 px-2 py-0.5 bg-blue-600 text-white text-[10px] font-extrabold uppercase tracking-wide rounded-md shadow-sm pointer-events-none">Primary</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Add More Images Dropzone --}}
                <div class="border-2 border-dashed border-slate-200 dark:border-slate-700 hover:border-blue-500 rounded-2xl p-7 text-center transition-all group relative bg-slate-50/50 dark:bg-slate-800/30">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 flex items-center justify-center mx-auto mb-2.5 group-hover:scale-110 transition-transform">
                        <i class="ph-bold ph-cloud-arrow-up text-2xl"></i>
                    </div>
                    <p class="text-xs sm:text-sm font-bold text-slate-800 dark:text-white mb-0.5">Click to upload additional photos</p>
                    <p class="text-xs text-slate-400">JPG, PNG, WebP · <span class="text-emerald-600 font-semibold">⚡ Auto-compressed for fast upload</span></p>
                    
                    <input type="file" name="images[]" multiple accept="image/*"
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                           id="edit-images-input" onchange="handleEditImageSelection(event)">
                </div>

                {{-- Image Compression Status Indicator --}}
                <div id="edit-compression-status" class="mt-3 p-3 bg-blue-50 dark:bg-blue-950/50 border border-blue-200 dark:border-blue-800 rounded-xl text-xs font-semibold text-blue-700 dark:text-blue-300 flex items-center justify-between hidden">
                    <div class="flex items-center gap-2">
                        <i class="ph-bold ph-lightning text-amber-500 text-base"></i>
                        <span id="edit-compression-status-text">Optimizing photos for instant upload...</span>
                    </div>
                    <span id="edit-compression-saved-text" class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-950/60 px-2 py-0.5 rounded-md"></span>
                </div>

                <script>
                async function compressEditImage(file, maxDimension = 1600, quality = 0.85) {
                    if (file.type === 'image/svg+xml' || file.size < 150 * 1024) return file;
                    return new Promise((resolve) => {
                        const reader = new FileReader();
                        reader.readAsDataURL(file);
                        reader.onload = (e) => {
                            const img = new Image();
                            img.src = e.target.result;
                            img.onload = () => {
                                let width = img.width, height = img.height;
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
                                canvas.width = width; canvas.height = height;
                                canvas.getContext('2d').drawImage(img, 0, 0, width, height);
                                canvas.toBlob((blob) => {
                                    if (blob && blob.size < file.size) {
                                        resolve(new File([blob], file.name.replace(/\.[^/.]+$/, "") + ".jpg", { type: 'image/jpeg', lastModified: Date.now() }));
                                    } else resolve(file);
                                }, 'image/jpeg', quality);
                            };
                            img.onerror = () => resolve(file);
                        };
                        reader.onerror = () => resolve(file);
                    });
                }

                async function handleEditImageSelection(event) {
                    const input = event.target;
                    const rawFiles = Array.from(input.files);
                    if (rawFiles.length > 10) {
                        alert('You can only upload a maximum of 10 images at a time.');
                        input.value = '';
                        return;
                    }
                    const status = document.getElementById('edit-compression-status');
                    const statusText = document.getElementById('edit-compression-status-text');
                    if (status) { status.classList.remove('hidden'); if(statusText) statusText.textContent = '⚡ Optimizing photos for fast upload...'; }
                    const compressed = await Promise.all(rawFiles.map(f => compressEditImage(f)));
                    const dataTransfer = new DataTransfer();
                    compressed.forEach(f => dataTransfer.items.add(f));
                    input.files = dataTransfer.files;
                    if (statusText) statusText.textContent = `⚡ Ready! ${compressed.length} photo(s) optimized.`;
                }
                </script>

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

                    @php
                        $existingVideos = $property->allVideoUrls();
                    @endphp

                    @if(!empty($existingVideos))
                    <div class="mb-5 p-4 sm:p-5 bg-purple-50/40 dark:bg-purple-950/20 border border-purple-200/70 dark:border-purple-900/50 rounded-2xl">
                        <p class="text-xs font-bold text-purple-900 dark:text-purple-300 uppercase tracking-wider mb-3.5 flex items-center gap-2">
                            <i class="ph-bold ph-film-slate text-purple-600"></i> Existing Video Tours ({{ count($existingVideos) }})
                            <span class="text-[11px] font-normal text-slate-400 normal-case ml-auto">Check box to delete</span>
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-3">
                            @foreach($existingVideos as $vIndex => $vUrl)
                                <div class="p-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-2xs">
                                    <div class="relative bg-black rounded-lg overflow-hidden mb-2.5 h-36 flex items-center justify-center">
                                        @php
                                            $isYt = preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i', $vUrl, $ytM);
                                            $isVm = preg_match('/vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)/i', $vUrl, $vmM);
                                        @endphp
                                        @if($isYt)
                                            <iframe class="w-full h-full" src="https://www.youtube.com/embed/{{ $ytM[1] }}" frameborder="0"></iframe>
                                        @elseif($isVm)
                                            <iframe class="w-full h-full" src="https://player.vimeo.com/video/{{ $vmM[3] }}" frameborder="0"></iframe>
                                        @else
                                            <video src="{{ $vUrl }}" controls playsinline preload="metadata" class="w-full h-full object-contain"></video>
                                        @endif
                                    </div>
                                    <label class="inline-flex items-center gap-2 text-xs font-bold text-red-600 hover:text-red-700 cursor-pointer">
                                        <input type="checkbox" name="remove_video_indexes[]" value="{{ $vIndex }}" class="rounded text-red-600 focus:ring-red-500">
                                        <span>Delete Video {{ $vIndex + 1 }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <label class="inline-flex items-center gap-2 text-xs font-bold text-red-700 dark:text-red-400 cursor-pointer pt-2 border-t border-purple-200/60 dark:border-purple-900/60">
                            <input type="checkbox" name="remove_video" value="1" class="rounded text-red-600 focus:ring-red-500">
                            <span>Remove all existing videos</span>
                        </label>
                    </div>
                    @endif

                    {{-- Multi-Video Upload Box --}}
                    <div id="edit-video-upload-box" class="border-2 border-dashed border-slate-200 dark:border-slate-700 hover:border-purple-500 rounded-2xl p-6 text-center transition-all group relative bg-slate-50/40 dark:bg-slate-800/20 cursor-pointer" onclick="document.getElementById('edit-video-input').click()">
                        <div class="w-12 h-12 rounded-2xl bg-purple-50 dark:bg-purple-950/50 text-purple-600 flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                            <i class="ph-bold ph-film-slate text-xl"></i>
                        </div>
                        <p class="text-xs sm:text-sm font-bold text-slate-800 dark:text-white mb-0.5">Click to upload property videos / clips (Up to 10 videos)</p>
                        <p class="text-xs text-slate-400">MP4, WebM, MOV, M4V · Auto-optimized for fast upload · Optional trim for long videos · Max 10 clips</p>
                        
                        <input type="file" id="edit-video-input" multiple accept="video/mp4,video/webm,video/ogg,video/quicktime,video/x-m4v" class="hidden" onchange="handleEditMultipleVideoSelection(event)">
                    </div>

                    {{-- Video URL Input --}}
                    <div class="mt-4 p-4 bg-slate-50/60 dark:bg-slate-800/40 rounded-xl border border-slate-200/70 dark:border-slate-700/60">
                        <label for="edit-video-url-input" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                            <i class="ph-bold ph-link text-purple-600 mr-1"></i> Or Add Video Tour Link (YouTube / Vimeo / Cloud)
                        </label>
                        <div class="flex flex-col sm:flex-row gap-2.5">
                            <div class="relative flex-1">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"><i class="ph-bold ph-video"></i></span>
                                <input type="url" id="edit-video-url-input" placeholder="https://www.youtube.com/watch?v=... or Vimeo"
                                       class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-purple-600 focus:ring-2 focus:ring-purple-600/20 transition-all">
                            </div>
                            <button type="button" onclick="addEditVideoUrlLink()" class="px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-xl shadow-xs transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                                <i class="ph-bold ph-plus"></i> Add Link
                            </button>
                        </div>
                    </div>

                    {{-- Compression Status Indicator --}}
                    <div id="edit-video-compress-status" class="mt-3 p-3 bg-purple-50 dark:bg-purple-950/50 border border-purple-200 dark:border-purple-800 rounded-xl hidden">
                        <div class="flex items-center justify-between text-xs font-bold text-purple-900 dark:text-purple-300 mb-1.5">
                            <span class="flex items-center gap-1.5"><i class="ph-bold ph-lightning text-amber-500 animate-pulse"></i> <span id="edit-compress-status-text">Optimizing video...</span></span>
                            <span id="edit-compress-status-pct">0%</span>
                        </div>
                        <div class="w-full bg-purple-200 dark:bg-purple-900 h-2 rounded-full overflow-hidden">
                            <div id="edit-compress-progress-bar" class="bg-gradient-to-r from-purple-600 to-indigo-600 h-full w-0 transition-all duration-150"></div>
                        </div>
                    </div>

                    {{-- Video Previews Container --}}
                    <div id="edit-video-previews-container" class="mt-4 space-y-2.5"></div>
                </div>

                {{-- Interactive Video Trimmer Modal for Edit Page --}}
                <div id="edit-video-trimmer-modal" class="fixed inset-0 z-[99999] flex items-center justify-center p-3 sm:p-4 bg-black/85 backdrop-blur-md hidden" style="z-index: 99999;">
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl max-w-2xl w-full max-h-[92vh] overflow-y-auto shadow-2xl p-5 sm:p-6 transition-all relative z-10" onclick="event.stopPropagation()">
                        
                        {{-- Modal Header --}}
                        <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100 dark:border-slate-800">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-xl bg-purple-50 dark:bg-purple-950/60 text-purple-600 flex items-center justify-center">
                                    <i class="ph-bold ph-scissors text-base"></i>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Video Highlight Trimmer</h3>
                                    <p class="text-xs text-slate-400">Select a short clip (15s–60s) for ultra-fast instant upload</p>
                                </div>
                            </div>
                            <button type="button" onclick="closeEditTrimmerModal()" class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-500 flex items-center justify-center transition-all cursor-pointer">
                                <i class="ph-bold ph-x text-sm"></i>
                            </button>
                        </div>

                        {{-- Video Preview Player --}}
                        <div class="relative bg-black rounded-xl overflow-hidden mb-4 aspect-video flex items-center justify-center shadow-inner">
                            <video id="edit-trimmer-preview-player" class="w-full h-full object-contain" playsinline preload="auto" controls></video>
                            <div class="absolute top-2.5 right-2.5 px-2 py-1 bg-black/80 text-white font-mono text-[11px] rounded-md backdrop-blur-sm pointer-events-none">
                                <span id="edit-trimmer-current-time">00:00</span> / <span id="edit-trimmer-total-duration">00:00</span>
                            </div>
                        </div>

                        {{-- Clip Title Input --}}
                        <div class="mb-4">
                            <label for="edit-trimmer-clip-title" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Clip Name / Room (Optional)</label>
                            <input type="text" id="edit-trimmer-clip-title" placeholder="e.g. Living Room, Balcony View, Kitchen"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-purple-600">
                        </div>

                        {{-- Slider Trimming Controls Box --}}
                        <div class="p-4 bg-slate-50 dark:bg-slate-800/60 rounded-xl border border-slate-200/80 dark:border-slate-700/80 mb-4 space-y-3.5">
                            
                            {{-- Start & End Dual Controls --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                {{-- Start Slider --}}
                                <div>
                                    <div class="flex items-center justify-between text-xs font-bold mb-1">
                                        <span class="text-slate-700 dark:text-slate-300">Start Time:</span>
                                        <div class="flex items-center gap-1.5">
                                            <span id="edit-trimmer-start-display" class="text-purple-600 dark:text-purple-400 font-mono">0.0s (00:00)</span>
                                            <button type="button" onclick="setEditTrimStartToCurrent()" class="px-1.5 py-0.5 bg-purple-100 hover:bg-purple-200 text-purple-700 text-[10px] font-bold rounded cursor-pointer">Set Current</button>
                                        </div>
                                    </div>
                                    <input type="range" id="edit-trimmer-start-slider" min="0" max="60" step="0.1" value="0"
                                           class="w-full accent-purple-600 cursor-pointer" oninput="onEditTrimStartSliderChange(this.value)">
                                </div>

                                {{-- End Slider --}}
                                <div>
                                    <div class="flex items-center justify-between text-xs font-bold mb-1">
                                        <span class="text-slate-700 dark:text-slate-300">End Time:</span>
                                        <div class="flex items-center gap-1.5">
                                            <span id="edit-trimmer-end-display" class="text-purple-600 dark:text-purple-400 font-mono">30.0s (00:30)</span>
                                            <button type="button" onclick="setEditTrimEndToCurrent()" class="px-1.5 py-0.5 bg-purple-100 hover:bg-purple-200 text-purple-700 text-[10px] font-bold rounded cursor-pointer">Set Current</button>
                                        </div>
                                    </div>
                                    <input type="range" id="edit-trimmer-end-slider" min="0" max="60" step="0.1" value="30"
                                           class="w-full accent-purple-600 cursor-pointer" oninput="onEditTrimEndSliderChange(this.value)">
                                </div>
                            </div>

                            {{-- Quick Presets --}}
                            <div class="flex flex-wrap items-center gap-1.5 pt-2 border-t border-slate-200/60 dark:border-slate-700/60">
                                <span class="text-[11px] font-bold text-slate-400 mr-1">Fast Presets:</span>
                                <button type="button" onclick="applyEditTrimPreset(15)" id="edit-trim-preset-15" class="edit-trim-preset-btn px-2.5 py-1 bg-white dark:bg-slate-700 hover:bg-purple-50 text-slate-700 dark:text-slate-200 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-600 cursor-pointer transition-all">15s Clip</button>
                                <button type="button" onclick="applyEditTrimPreset(30)" id="edit-trim-preset-30" class="edit-trim-preset-btn px-2.5 py-1 bg-white dark:bg-slate-700 hover:bg-purple-50 text-slate-700 dark:text-slate-200 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-600 cursor-pointer transition-all">30s Clip</button>
                                <button type="button" onclick="applyEditTrimPreset(45)" id="edit-trim-preset-45" class="edit-trim-preset-btn px-2.5 py-1 bg-white dark:bg-slate-700 hover:bg-purple-50 text-slate-700 dark:text-slate-200 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-600 cursor-pointer transition-all">45s Clip</button>
                                <button type="button" onclick="applyEditTrimPreset(60)" id="edit-trim-preset-60" class="edit-trim-preset-btn px-2.5 py-1 bg-white dark:bg-slate-700 hover:bg-purple-50 text-slate-700 dark:text-slate-200 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-600 cursor-pointer transition-all">60s Clip</button>
                                <button type="button" onclick="previewEditTrimRange()" class="ml-auto px-3 py-1 bg-slate-900 hover:bg-black text-white text-xs font-bold rounded-lg flex items-center gap-1 shadow-xs cursor-pointer">
                                    <i class="ph-bold ph-play"></i> Preview Clip
                                </button>
                            </div>

                            {{-- Summary Badge --}}
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1.5 text-xs font-semibold text-slate-600 dark:text-slate-300 pt-1">
                                <span>Clip Duration: <strong id="edit-trimmer-duration-badge" class="text-purple-600 dark:text-purple-400">30.0s</strong> <span class="text-[10px] text-slate-400 font-normal">(Max 60s per clip)</span></span>
                                <div id="edit-trimmer-speed-badge">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 text-[11px] font-bold rounded-full border border-emerald-200/50">⚡ ~4.8 MB · Uploads in ~2 sec</span>
                                </div>
                            </div>
                        </div>

                        {{-- Trimming Progress Bar (during processing) --}}
                        <div id="edit-trimmer-progress-wrapper" class="mb-4 p-3 bg-purple-50 dark:bg-purple-950/40 rounded-xl border border-purple-200 dark:border-purple-800/60 hidden">
                            <div class="flex items-center justify-between text-xs font-bold text-purple-900 dark:text-purple-300 mb-1.5">
                                <span class="flex items-center gap-1.5"><i class="ph-bold ph-lightning text-amber-500 animate-pulse"></i> Extracting & optimizing clip...</span>
                                <span id="edit-trimmer-progress-pct">0%</span>
                            </div>
                            <div class="w-full bg-purple-200 dark:bg-purple-900 h-2 rounded-full overflow-hidden">
                                <div id="edit-trimmer-progress-bar" class="bg-gradient-to-r from-purple-600 to-indigo-600 h-full w-0 transition-all duration-100"></div>
                            </div>
                        </div>

                        {{-- Mode Selector: Add as new clip vs replace --}}
                        <div class="flex items-center gap-4 mb-5 text-xs">
                            <label class="inline-flex items-center gap-2 cursor-pointer text-slate-700 dark:text-slate-300 font-semibold">
                                <input type="radio" name="edit_trimmer_mode" value="add" id="edit-trimmer-mode-add" checked class="text-purple-600 focus:ring-purple-500">
                                <span>Add as New Clip <span class="text-[10px] text-slate-400">(Cut multiple clips for different rooms)</span></span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer text-slate-700 dark:text-slate-300 font-semibold">
                                <input type="radio" name="edit_trimmer_mode" value="replace" id="edit-trimmer-mode-replace" class="text-purple-600 focus:ring-purple-500">
                                <span>Replace Selected Video</span>
                            </label>
                        </div>

                        {{-- Bottom Modal Buttons --}}
                        <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100 dark:border-slate-800">
                            <button type="button" onclick="closeEditTrimmerModal()" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all cursor-pointer">
                                Cancel
                            </button>
                            <button type="button" id="edit-trimmer-submit-btn" onclick="executeEditVideoTrimming()" class="px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white text-xs font-bold rounded-xl shadow-md transition-all flex items-center gap-1.5 cursor-pointer">
                                <i class="ph-bold ph-scissors text-sm"></i>
                                <span id="edit-trimmer-submit-text">✂️ Cut & Add Clip to Uploads</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3 pt-6 border-t border-slate-200/70 dark:border-slate-800 mt-6">
                <a href="{{ route('dashboard') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold text-sm text-center rounded-xl transition-all" id="edit-cancel" title="Cancel">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold text-sm rounded-xl shadow-sm shadow-blue-500/20 hover:shadow-md transition-all active:scale-[0.98] flex items-center justify-center gap-2 cursor-pointer disabled:opacity-75 disabled:cursor-not-allowed" id="edit-submit">
                    <i class="ph-bold ph-floppy-disk text-base" id="edit-submit-icon"></i> 
                    <span id="edit-submit-label">Update Property</span>
                </button>
            </div>
        </form>
    </div>
</section>

<script>
let editUploadedVideoList = [];
let activeEditTrimmerItem = null;
let editTrimmerPlayer = null;

// Maximum duration allowed for a single clip (guarantees fast upload and instant processing)
const MAX_EDIT_CLIP_SECONDS = 60;

function formatEditTime(seconds) {
    const mins = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);
    return `${mins < 10 ? '0' : ''}${mins}:${secs < 10 ? '0' : ''}${secs}`;
}

function getActiveExistingVideoCount() {
    const removeAllChecked = document.querySelector('input[name="remove_video"]')?.checked;
    if (removeAllChecked) return 0;
    const initialCount = {{ count($existingVideos) }};
    const checkedRemovals = document.querySelectorAll('input[name="remove_video_indexes[]"]:checked').length;
    return Math.max(0, initialCount - checkedRemovals);
}

function getTotalVideoCount() {
    return getActiveExistingVideoCount() + editUploadedVideoList.length;
}

function handleEditMultipleVideoSelection(event) {
    const files = Array.from(event.target.files || []);
    if (files.length === 0) return;

    const maxAllowed = 10;
    const currentTotal = getTotalVideoCount();
    const remainingSlots = maxAllowed - currentTotal;

    if (remainingSlots <= 0) {
        alert('You have reached the maximum limit of 10 videos/clips for this property. Please remove an existing video before uploading more.');
        event.target.value = '';
        return;
    }

    let filesToProcess = files;
    if (files.length > remainingSlots) {
        alert(`You can only add ${remainingSlots} more video(s). Maximum 10 videos allowed in total. Only the first ${remainingSlots} will be attached.`);
        filesToProcess = files.slice(0, remainingSlots);
    }

    let firstLargeItemToTrim = null;

    filesToProcess.forEach(file => {
        if (file.size > 2048 * 1024 * 1024) {
            alert(`"${file.name}" is unusually large (> 2GB). Please select a file under 2GB.`);
            return;
        }
        const videoId = 'edit_vid_' + Date.now() + '_' + Math.random().toString(36).substr(2, 6);
        const item = {
            id: videoId,
            file: file,
            name: file.name,
            originalSize: file.size,
            size: file.size,
            isCompressed: false,
            isTrimmed: false,
            serverPath: null,
            previewUrl: URL.createObjectURL(file),
            isUrl: false
        };
        editUploadedVideoList.push(item);

        // Upload in background immediately
        uploadEditVideoToServer(file).then(serverPath => {
            if (serverPath) {
                item.serverPath = serverPath;
                renderEditVideoPreviews();
                syncEditVideoFormInputs();
            }
        });

        if (file.size > 25 * 1024 * 1024 && !firstLargeItemToTrim) {
            firstLargeItemToTrim = item;
        }
    });

    event.target.value = '';
    renderEditVideoPreviews();
    syncEditVideoFormInputs();

    if (firstLargeItemToTrim) {
        const sizeMB = (firstLargeItemToTrim.size / (1024 * 1024)).toFixed(0);
        const statusWrapper = document.getElementById('edit-video-compress-status');
        const statusText = document.getElementById('edit-compress-status-text');
        if (statusWrapper && statusText) {
            statusText.innerHTML = `📹 <strong>"${firstLargeItemToTrim.name}"</strong> (${sizeMB} MB) uploading in background. You can also click "Trim Clip" to select a highlight.`;
            statusWrapper.classList.remove('hidden');
            setTimeout(() => statusWrapper.classList.add('hidden'), 6000);
        }
    }
}

function addEditVideoUrlLink() {
    if (getTotalVideoCount() >= 10) {
        alert('You have reached the maximum limit of 10 videos/clips for this property. Please remove an existing video before adding a new link.');
        return;
    }

    const input = document.getElementById('edit-video-url-input');
    const url = (input.value || '').trim();
    if (!url) return;
    if (!url.startsWith('http://') && !url.startsWith('https://')) {
        alert('Please enter a valid URL starting with http:// or https://');
        return;
    }
    const videoId = 'edit_url_' + Date.now() + '_' + Math.random().toString(36).substr(2, 6);
    editUploadedVideoList.push({
        id: videoId,
        file: null,
        name: 'Video Link: ' + url.replace(/^https?:\/\/(www\.)?/, '').substring(0, 30) + '...',
        url: url,
        isUrl: true,
        isTrimmed: false,
        previewUrl: url
    });
    input.value = '';
    renderEditVideoPreviews();
    syncEditVideoFormInputs();
}

function removeEditVideoItem(id) {
    const idx = editUploadedVideoList.findIndex(v => v.id === id);
    if (idx !== -1) {
        if (editUploadedVideoList[idx].previewUrl && !editUploadedVideoList[idx].isUrl) {
            URL.revokeObjectURL(editUploadedVideoList[idx].previewUrl);
        }
        editUploadedVideoList.splice(idx, 1);
        renderEditVideoPreviews();
        syncEditVideoFormInputs();
    }
}

function openEditTrimmerModal(videoId) {
    const item = editUploadedVideoList.find(v => v.id === videoId);
    if (!item || item.isUrl || !item.file) return;

    activeEditTrimmerItem = item;
    const modal = document.getElementById('edit-video-trimmer-modal');
    editTrimmerPlayer = document.getElementById('edit-trimmer-preview-player');
    const titleInput = document.getElementById('edit-trimmer-clip-title');

    if (!modal || !editTrimmerPlayer) return;

    // Teleport modal to body so it floats on top of everything without footer bleed
    if (modal.parentElement !== document.body) {
        document.body.appendChild(modal);
    }

    if (titleInput) titleInput.value = '';
    editTrimmerPlayer.src = item.previewUrl;
    modal.classList.remove('hidden');

    editTrimmerPlayer.onloadedmetadata = () => {
        const duration = editTrimmerPlayer.duration || 30;
        document.getElementById('edit-trimmer-total-duration').textContent = formatEditTime(duration);
        const startSlider = document.getElementById('edit-trimmer-start-slider');
        const endSlider = document.getElementById('edit-trimmer-end-slider');
        if (startSlider && endSlider) {
            startSlider.min = 0; startSlider.max = duration.toFixed(1); startSlider.value = 0;
            endSlider.min = 0; endSlider.max = duration.toFixed(1); endSlider.value = Math.min(30, duration).toFixed(1);
        }
        updateEditTrimDisplay();
    };

    editTrimmerPlayer.ontimeupdate = () => {
        const currentEl = document.getElementById('edit-trimmer-current-time');
        if (currentEl && editTrimmerPlayer) currentEl.textContent = formatEditTime(editTrimmerPlayer.currentTime);
    };
}

function closeEditTrimmerModal() {
    const modal = document.getElementById('edit-video-trimmer-modal');
    if (editTrimmerPlayer) { editTrimmerPlayer.pause(); editTrimmerPlayer.removeAttribute('src'); editTrimmerPlayer.load(); }
    if (modal) modal.classList.add('hidden');
    activeEditTrimmerItem = null;
}

let selectedEditTrimWindow = 30; // default 30s preset duration

function highlightActiveEditPresetBtn(sec) {
    document.querySelectorAll('.edit-trim-preset-btn').forEach(btn => {
        btn.classList.remove('border-purple-600', 'bg-purple-50', 'dark:bg-purple-950/60', 'text-purple-700', 'dark:text-purple-300', 'font-bold', 'ring-2', 'ring-purple-600/30');
    });
    const activeBtn = document.getElementById(`edit-trim-preset-${sec}`);
    if (activeBtn) {
        activeBtn.classList.add('border-purple-600', 'bg-purple-50', 'dark:bg-purple-950/60', 'text-purple-700', 'dark:text-purple-300', 'font-bold', 'ring-2', 'ring-purple-600/30');
    }
}

function onEditTrimStartSliderChange(val) {
    let startVal = parseFloat(val);
    const endSlider = document.getElementById('edit-trimmer-end-slider');
    const maxDuration = editTrimmerPlayer?.duration || parseFloat(endSlider?.max || 60);

    // Automatically shift End Time with Start Time to keep selected clip duration (e.g. 60s)
    let endVal = Math.min(maxDuration, startVal + selectedEditTrimWindow);
    if (endSlider) {
        endSlider.value = endVal.toFixed(1);
    }

    if (editTrimmerPlayer) editTrimmerPlayer.currentTime = startVal;
    updateEditTrimDisplay();
}

function onEditTrimEndSliderChange(val) {
    let endVal = parseFloat(val);
    const startSlider = document.getElementById('edit-trimmer-start-slider');
    const startVal = parseFloat(startSlider?.value || 0);

    if (startSlider && endVal <= startVal) {
        endVal = Math.min(parseFloat(document.getElementById('edit-trimmer-end-slider').max), startVal + 1);
        document.getElementById('edit-trimmer-end-slider').value = endVal.toFixed(1);
    }

    // Clamp to 60s max per clip
    if (endVal - startVal > MAX_EDIT_CLIP_SECONDS) {
        endVal = startVal + MAX_EDIT_CLIP_SECONDS;
        document.getElementById('edit-trimmer-end-slider').value = endVal.toFixed(1);
    }

    // Remember user's custom clip duration
    selectedEditTrimWindow = Math.max(1, endVal - startVal);
    highlightActiveEditPresetBtn(Math.round(selectedEditTrimWindow));

    if (editTrimmerPlayer) editTrimmerPlayer.currentTime = endVal;
    updateEditTrimDisplay();
}

function setEditTrimStartToCurrent() {
    if (!editTrimmerPlayer) return;
    const current = editTrimmerPlayer.currentTime;
    const s = document.getElementById('edit-trimmer-start-slider');
    if (s) { s.value = current.toFixed(1); onEditTrimStartSliderChange(current); }
}

function setEditTrimEndToCurrent() {
    if (!editTrimmerPlayer) return;
    const current = editTrimmerPlayer.currentTime;
    const e = document.getElementById('edit-trimmer-end-slider');
    if (e) { e.value = current.toFixed(1); onEditTrimEndSliderChange(current); }
}

function applyEditTrimPreset(sec) {
    if (!editTrimmerPlayer) return;
    const duration = editTrimmerPlayer.duration || 30;
    selectedEditTrimWindow = sec;
    highlightActiveEditPresetBtn(sec);

    const startSlider = document.getElementById('edit-trimmer-start-slider');
    const endSlider = document.getElementById('edit-trimmer-end-slider');

    if (startSlider && endSlider) {
        const curStart = parseFloat(startSlider.value) || 0;
        let startVal = curStart;
        if (startVal + selectedEditTrimWindow > duration) {
            startVal = Math.max(0, duration - selectedEditTrimWindow);
            startSlider.value = startVal.toFixed(1);
        }
        const endVal = Math.min(duration, startVal + selectedEditTrimWindow);
        endSlider.value = endVal.toFixed(1);
        updateEditTrimDisplay();
        if (editTrimmerPlayer) editTrimmerPlayer.currentTime = startVal;
    }
}

function updateEditTrimDisplay() {
    const start = parseFloat(document.getElementById('edit-trimmer-start-slider')?.value || 0);
    const end = parseFloat(document.getElementById('edit-trimmer-end-slider')?.value || 0);
    const diff = Math.max(0, end - start);
    document.getElementById('edit-trimmer-start-display').textContent = `${start.toFixed(1)}s (${formatEditTime(start)})`;
    document.getElementById('edit-trimmer-end-display').textContent = `${end.toFixed(1)}s (${formatEditTime(end)})`;
    document.getElementById('edit-trimmer-duration-badge').textContent = `${diff.toFixed(1)}s`;

    const estMb = Math.max(0.4, (diff * 0.16)).toFixed(1);
    const speedBadge = document.getElementById('edit-trimmer-speed-badge');
    if (speedBadge) {
        if (diff <= 15) {
            speedBadge.innerHTML = `<span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 text-[11px] font-bold rounded-full border border-emerald-200/50">⚡ ~${estMb} MB · Instant Upload (~1s)</span>`;
        } else if (diff <= 30) {
            speedBadge.innerHTML = `<span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 text-[11px] font-bold rounded-full border border-emerald-200/50">⚡ ~${estMb} MB · Fast Upload (~2s)</span>`;
        } else if (diff <= 60) {
            speedBadge.innerHTML = `<span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 text-[11px] font-bold rounded-full border border-indigo-200/50">🚀 ~${estMb} MB · Tour Clip (~4s)</span>`;
        } else {
            speedBadge.innerHTML = `<span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 text-[11px] font-bold rounded-full border border-amber-200/50">⚠️ ~${estMb} MB · Max 60s per clip</span>`;
        }
    }
}

function previewEditTrimRange() {
    if (!editTrimmerPlayer) return;
    const start = parseFloat(document.getElementById('edit-trimmer-start-slider')?.value || 0);
    const end = parseFloat(document.getElementById('edit-trimmer-end-slider')?.value || 30);
    editTrimmerPlayer.currentTime = start;
    editTrimmerPlayer.play();
    const checkTime = () => {
        if (editTrimmerPlayer.currentTime >= end || editTrimmerPlayer.paused) {
            editTrimmerPlayer.pause();
            editTrimmerPlayer.removeEventListener('timeupdate', checkTime);
        }
    };
    editTrimmerPlayer.addEventListener('timeupdate', checkTime);
}

// 3.0x fast video trimmer & recorder
async function runVideoSliceAndCompress(file, startTime, endTime, onProgress) {
    return new Promise((resolve, reject) => {
        const video = document.createElement('video');
        video.preload = 'auto';
        video.muted = true;
        video.playsInline = true;
        video.crossOrigin = 'anonymous';

        const sourceUrl = URL.createObjectURL(file);
        video.src = sourceUrl;

        let mediaRecorder = null;
        const chunks = [];
        let canvas = null;
        let ctx = null;
        let animFrameId = null;
        let isFinished = false;

        const cleanup = () => {
            if (animFrameId) cancelAnimationFrame(animFrameId);
            video.pause();
            URL.revokeObjectURL(sourceUrl);
            video.removeAttribute('src');
            video.load();
        };

        const finishRecording = () => {
            if (isFinished) return;
            isFinished = true;
            if (mediaRecorder && mediaRecorder.state !== 'inactive') {
                try { mediaRecorder.stop(); } catch (e) {}
            }
        };

        video.onloadedmetadata = () => {
            const targetDuration = Math.max(0.5, endTime - startTime);
            canvas = document.createElement('canvas');
            
            const maxDim = 960;
            let w = video.videoWidth || 960;
            let h = video.videoHeight || 540;
            if (w > maxDim || h > maxDim) {
                if (w > h) {
                    h = Math.round((h * maxDim) / w);
                    w = maxDim;
                } else {
                    w = Math.round((w * maxDim) / h);
                    h = maxDim;
                }
            }
            canvas.width = (w % 2 === 0) ? w : w - 1;
            canvas.height = (h % 2 === 0) ? h : h - 1;
            ctx = canvas.getContext('2d', { alpha: false });

            const stream = canvas.captureStream(25);

            const mimeTypes = [
                'video/webm;codecs=vp8',
                'video/webm;codecs=vp9',
                'video/webm',
                'video/mp4'
            ];
            let selectedMime = mimeTypes.find(m => MediaRecorder.isTypeSupported(m)) || 'video/webm';

            try {
                mediaRecorder = new MediaRecorder(stream, {
                    mimeType: selectedMime,
                    videoBitsPerSecond: 900000 // 900 kbps ultra-fast & lightweight
                });
            } catch (err) {
                mediaRecorder = new MediaRecorder(stream);
            }

            mediaRecorder.ondataavailable = (e) => {
                if (e.data && e.data.size > 0) chunks.push(e.data);
            };

            mediaRecorder.onstop = () => {
                cleanup();
                const blob = new Blob(chunks, { type: selectedMime });
                resolve(blob);
            };

            mediaRecorder.onerror = (e) => {
                cleanup();
                reject(e);
            };

            video.currentTime = startTime;
        };

        video.onseeked = () => {
            if (mediaRecorder && mediaRecorder.state === 'inactive') {
                mediaRecorder.start(100);
                video.playbackRate = 1.0; // 1.0x Normal playback speed (full duration & smooth playback)
                video.play().catch(e => {
                    cleanup();
                    reject(e);
                });

                const drawFrame = () => {
                    if (isFinished) return;
                    if (!video.paused && !video.ended) {
                        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                        const progress = Math.min(99, Math.max(1, ((video.currentTime - startTime) / (endTime - startTime)) * 100));
                        if (onProgress) onProgress(progress);
                    }
                    if (video.currentTime >= endTime || video.ended) {
                        finishRecording();
                        return;
                    }
                    animFrameId = requestAnimationFrame(drawFrame);
                };
                animFrameId = requestAnimationFrame(drawFrame);

                const timeoutMs = ((endTime - startTime) + 3) * 1000;
                setTimeout(() => finishRecording(), timeoutMs);
            }
        };

        video.onerror = (e) => {
            cleanup();
            reject(e);
        };
    });
}

// Background high-speed server uploader (takes ~0.5s for 1.5MB clip)
async function uploadEditVideoToServer(file, onProgress) {
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

async function executeEditVideoTrimming() {
    if (!activeEditTrimmerItem) return;
    const start = parseFloat(document.getElementById('edit-trimmer-start-slider')?.value || 0);
    const end = parseFloat(document.getElementById('edit-trimmer-end-slider')?.value || 30);
    const customTitle = (document.getElementById('edit-trimmer-clip-title')?.value || '').trim();
    const isReplace = document.getElementById('edit-trimmer-mode-replace')?.checked;

    const progressWrapper = document.getElementById('edit-trimmer-progress-wrapper');
    const progressBar = document.getElementById('edit-trimmer-progress-bar');
    const progressPct = document.getElementById('edit-trimmer-progress-pct');
    const btn = document.getElementById('edit-trimmer-submit-btn');

    if (progressWrapper) progressWrapper.classList.remove('hidden');
    if (btn) btn.disabled = true;

    try {
        // Step 1: Slicing video at 8x speed (~4-6s)
        const blob = await runVideoSliceAndCompress(activeEditTrimmerItem.file, start, end, (pct) => {
            if (progressPct) progressPct.textContent = `Processing: ${Math.round(pct)}%`;
            if (progressBar) progressBar.style.width = `${pct * 0.7}%`;
        });

        const safeTitle = customTitle || activeEditTrimmerItem.name.replace(/\.[^/.]+$/, "") + `_clip_${Math.round(start)}-${Math.round(end)}s`;
        const clipFile = new File([blob], `${safeTitle.replace(/[^a-zA-Z0-9_-]/g, '_')}.webm`, { type: 'video/webm' });

        // Step 2: Instant Background Upload (~0.5s for 1.5MB)
        if (progressPct) progressPct.textContent = 'Uploading to server...';
        if (progressBar) progressBar.style.width = '85%';

        let serverPath = null;
        try {
            serverPath = await uploadEditVideoToServer(clipFile, (upPct) => {
                if (progressPct) progressPct.textContent = `Uploading: ${upPct}%`;
                if (progressBar) progressBar.style.width = `${70 + (upPct * 0.3)}%`;
            });
        } catch(upErr) {
            console.warn('Direct upload fallback:', upErr);
        }

        if (isReplace) {
            if (activeEditTrimmerItem.previewUrl) URL.revokeObjectURL(activeEditTrimmerItem.previewUrl);
            activeEditTrimmerItem.file = clipFile;
            activeEditTrimmerItem.name = customTitle ? `${customTitle} (Trimmed Clip)` : `${safeTitle}.webm`;
            activeEditTrimmerItem.size = blob.size;
            activeEditTrimmerItem.originalSize = blob.size;
            activeEditTrimmerItem.isTrimmed = true;
            activeEditTrimmerItem.isCompressed = true;
            activeEditTrimmerItem.serverPath = serverPath;
            activeEditTrimmerItem.previewUrl = URL.createObjectURL(blob);
        } else {
            const newId = 'edit_clip_' + Date.now() + '_' + Math.random().toString(36).substr(2, 6);
            editUploadedVideoList.push({
                id: newId,
                file: clipFile,
                name: customTitle ? `${customTitle} (Trimmed Clip)` : `Clip: ${activeEditTrimmerItem.name} [${start.toFixed(0)}s-${end.toFixed(0)}s]`,
                originalSize: blob.size,
                size: blob.size,
                isCompressed: true,
                isTrimmed: true,
                serverPath: serverPath,
                previewUrl: URL.createObjectURL(blob),
                isUrl: false
            });
        }

        renderEditVideoPreviews();
        syncEditVideoFormInputs();
        closeEditTrimmerModal();
    } catch (err) {
        console.error('Trimming error:', err);
        alert('Could not trim this video format. You can upload the original directly.');
    } finally {
        if (progressWrapper) progressWrapper.classList.add('hidden');
        if (btn) btn.disabled = false;
    }
}

function renderEditVideoPreviews() {
    const container = document.getElementById('edit-video-previews-list');
    const countBadge = document.getElementById('edit-video-count-badge');
    if (!container) return;

    const totalCount = (typeof getTotalVideoCount === 'function') ? getTotalVideoCount() : editUploadedVideoList.length;
    if (countBadge) countBadge.textContent = `${totalCount} / 10 Videos`;

    if (editUploadedVideoList.length === 0) {
        container.innerHTML = '';
        return;
    }

    let html = '';
    editUploadedVideoList.forEach((item, index) => {
        const sizeStr = item.isUrl ? 'Web Link' : (item.size / (1024 * 1024)).toFixed(1) + ' MB';
        const isTrimmed = item.isTrimmed || item.isCompressed;
        const isOverLimit = !item.isUrl && item.size > (60 * 1024 * 1024);

        html += `
        <div class="p-3.5 bg-white dark:bg-slate-800/90 rounded-xl border ${isOverLimit ? 'border-amber-400 bg-amber-50/20' : 'border-slate-200 dark:border-slate-700'} shadow-2xs flex items-center justify-between gap-3">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-16 h-12 rounded-lg bg-black overflow-hidden flex-shrink-0 flex items-center justify-center relative">
                    ${item.isUrl ? 
                        `<i class="ph-bold ph-link text-purple-400 text-xl"></i>` : 
                        `<video src="${item.previewUrl}" class="w-full h-full object-cover" muted></video>`
                    }
                    <span class="absolute bottom-0.5 right-0.5 px-1 py-0.2 bg-black/80 text-[9px] font-bold text-white rounded">Clip ${index + 1}</span>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-bold text-slate-800 dark:text-slate-200 truncate" title="${item.name}">${item.name}</p>
                    <div class="flex items-center gap-2 mt-0.5 flex-wrap">
                        <span class="text-[11px] font-semibold text-slate-400">Size: <strong class="text-slate-600 dark:text-slate-300">${sizeStr}</strong></span>
                        ${isTrimmed ? `<span class="px-1.5 py-0.2 bg-purple-100 dark:bg-purple-950/60 text-purple-700 dark:text-purple-300 text-[10px] font-bold rounded">✂️ Trimmed</span>` : ''}
                        ${item.serverPath ? `<span class="px-1.5 py-0.2 bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 text-[10px] font-bold rounded">✓ Ready</span>` : ''}
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-1.5 flex-shrink-0">
                ${!item.isUrl ? `
                    <button type="button" onclick="openEditTrimmerModal('${item.id}')" class="px-2.5 py-1 bg-purple-50 dark:bg-purple-950/60 hover:bg-purple-100 text-purple-700 dark:text-purple-300 text-xs font-bold rounded-lg border border-purple-200/60 flex items-center gap-1 cursor-pointer">
                        <i class="ph-bold ph-scissors"></i> Trim Clip
                    </button>
                ` : ''}
                <button type="button" onclick="removeEditVideoItem('${item.id}')" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg cursor-pointer transition-colors" title="Remove video">
                    <i class="ph-bold ph-trash text-sm"></i>
                </button>
            </div>
        </div>`;
    });

    container.innerHTML = html;
}

function syncEditVideoFormInputs() {
    const form = document.getElementById('edit-property-form');
    if (!form) return;
    form.querySelectorAll('.edit-sync-video-input').forEach(el => el.remove());

    const fileItems = editUploadedVideoList.filter(v => !v.isUrl && v.file);
    if (fileItems.length > 0) {
        const dt = new DataTransfer();
        fileItems.forEach(item => dt.items.add(item.file));
        const hiddenFileInput = document.createElement('input');
        hiddenFileInput.type = 'file';
        hiddenFileInput.name = 'videos[]';
        hiddenFileInput.multiple = true;
        hiddenFileInput.className = 'hidden edit-sync-video-input';
        hiddenFileInput.files = dt.files;
        form.appendChild(hiddenFileInput);
    }

    // Sync pre-uploaded server paths
    const preUploadedItems = editUploadedVideoList.filter(v => v.serverPath);
    preUploadedItems.forEach(item => {
        const hiddenPathInput = document.createElement('input');
        hiddenPathInput.type = 'hidden';
        hiddenPathInput.name = 'uploaded_video_paths[]';
        hiddenPathInput.value = item.serverPath;
        hiddenPathInput.className = 'edit-sync-video-input';
        form.appendChild(hiddenPathInput);
    });

    const urlItems = editUploadedVideoList.filter(v => v.isUrl && v.url);
    urlItems.forEach(item => {
        const hiddenUrlInput = document.createElement('input');
        hiddenUrlInput.type = 'hidden';
        hiddenUrlInput.name = 'video_urls[]';
        hiddenUrlInput.value = item.url;
        hiddenUrlInput.className = 'edit-sync-video-input';
        form.appendChild(hiddenUrlInput);
    });
}

window.onEditPurposeChange = function(purpose) {
    const priceLabel = document.getElementById('edit-price-label');
    const periodCol = document.getElementById('edit-period-col');
    const saleCol = document.getElementById('edit-sale-badge-col');
    const periodSelect = document.getElementById('edit-period');

    if (purpose === 'buy' || purpose === 'sell') {
        if (priceLabel) priceLabel.textContent = 'Total Sale Price (₹) *';
        if (periodCol) periodCol.classList.add('hidden');
        if (saleCol) saleCol.classList.remove('hidden');
        if (periodSelect) periodSelect.value = 'month';
    } else {
        if (priceLabel) priceLabel.textContent = 'Expected Rent (₹) *';
        if (periodCol) periodCol.classList.remove('hidden');
        if (saleCol) saleCol.classList.add('hidden');
    }
};

async function autoCompressEditVideos() {
    const MAX_SAFE = 60 * 1024 * 1024;
    const MIN_COMPRESS = 25 * 1024 * 1024;
    const toCompress = editUploadedVideoList.filter(v => !v.isUrl && v.file && !v.isCompressed && v.file.size > MIN_COMPRESS && v.file.size <= MAX_SAFE);
    if (toCompress.length === 0) return true;

    for (let i = 0; i < toCompress.length; i++) {
        const item = toCompress[i];
        try {
            const blob = await runVideoSliceAndCompress(item.file, 0, 30, () => {});
            if (blob && blob.size < item.file.size) {
                const compressedFile = new File([blob], item.name.replace(/\.[^/.]+$/, '') + '_optimized.webm', { type: 'video/webm' });
                if (item.previewUrl) URL.revokeObjectURL(item.previewUrl);
                item.file = compressedFile;
                item.size = blob.size;
                item.isCompressed = true;
                item.isTrimmed = true;
                item.previewUrl = URL.createObjectURL(blob);
            }
        } catch (err) {
            console.warn('Auto-compress skipped for', item.name, err);
        }
    }
    syncEditVideoFormInputs();
    return true;
}

document.getElementById('edit-property-form')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const form = this;

    const initialImagesCount = {{ $property->images->count() }};
    const removedImagesCount = document.querySelectorAll('input[name="remove_images[]"]:checked').length;
    const newImagesCount = document.getElementById('edit-images-input')?.files?.length || 0;
    const totalPhotos = Math.max(0, initialImagesCount - removedImagesCount) + newImagesCount;
    const totalVideos = (typeof getTotalVideoCount === 'function') ? getTotalVideoCount() : editUploadedVideoList.length;

    if (totalPhotos === 0 && totalVideos === 0) {
        alert('Please ensure your property listing has at least one photo or video tour attached.');
        return;
    }

    const MAX_SAFE_SIZE = 60 * 1024 * 1024;
    const hasTrimmedClips = editUploadedVideoList.some(v => v.isTrimmed || v.isCompressed);
    if (hasTrimmedClips) {
        editUploadedVideoList = editUploadedVideoList.filter(v => v.isUrl || !v.file || v.file.size <= MAX_SAFE_SIZE || v.isTrimmed || v.isCompressed);
        syncEditVideoFormInputs();
        renderEditVideoPreviews();
    }

    const tooBigFiles = editUploadedVideoList.filter(v => !v.isUrl && v.file && v.file.size > MAX_SAFE_SIZE && !v.isTrimmed && !v.isCompressed);
    if (tooBigFiles.length > 0) {
        const item = tooBigFiles[0];
        alert(`"${item.name}" is too large. Please use "Trim Clip" to cut a highlight.`);
        openEditTrimmerModal(item.id);
        return;
    }

    const submitBtn = document.getElementById('edit-submit');
    const submitIcon = document.getElementById('edit-submit-icon');
    const submitLabel = document.getElementById('edit-submit-label');

    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.classList.remove('btn-submitting');
        if (submitIcon) submitIcon.className = 'ph-bold ph-spinner animate-spin text-base';
        if (submitLabel) submitLabel.textContent = '🚀 Saving...';
    }

    syncEditVideoFormInputs();
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
            if (submitLabel) submitLabel.textContent = '✅ Saved! Redirecting...';
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
            if (submitIcon) submitIcon.className = 'ph-bold ph-floppy-disk text-base';
            if (submitLabel) submitLabel.textContent = 'Update Property';
            try {
                const res = JSON.parse(xhr.responseText);
                const errors = res.errors ? Object.values(res.errors).flat().join('\n') : (res.message || 'An error occurred.');
                alert('Error:\n' + errors);
            } catch (e) {
                alert('An error occurred.');
            }
        }
    };

    xhr.onerror = function() {
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('btn-submitting');
        }
        if (submitIcon) submitIcon.className = 'ph-bold ph-floppy-disk text-base';
        if (submitLabel) submitLabel.textContent = 'Update Property';
        alert('Network error. Please check your internet connection.');
    };

    xhr.send(formData);
});
</script>

@endsection
