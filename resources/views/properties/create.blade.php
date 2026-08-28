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

        <form method="POST" action="{{ route('properties.store') }}" enctype="multipart/form-data" class="space-y-8" id="create-property-form" data-ur-loader-msg="Uploading images & processing listing...">
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
                                       placeholder="+91 98765 43210">
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
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">Pricing & Billing</h2>
                        <p class="text-xs text-slate-400">Set your expected rent and payment schedule</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="create-price" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Expected Rent (₹) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 font-extrabold text-sm">₹</span>
                            <input type="number" name="price" id="create-price" value="{{ old('price') }}" required min="0" step="0.01"
                                   class="w-full pl-9 pr-4 py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white placeholder-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all"
                                   placeholder="25,000">
                        </div>
                        @error('price') <p class="text-red-500 text-xs mt-1.5 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="create-period" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Billing Cycle <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select name="price_period" id="create-period" required
                                    class="w-full pl-4 pr-9 py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all appearance-none cursor-pointer">
                                <option value="month" {{ old('price_period') === 'month' ? 'selected' : '' }}>Per Month</option>
                                <option value="year" {{ old('price_period') === 'year' ? 'selected' : '' }}>Per Year</option>
                            </select>
                            <i class="ph-bold ph-caret-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
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
                                <select name="state" id="create-state" required
                                        class="w-full pl-4 pr-9 py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all appearance-none cursor-pointer">
                                    <option value="">Select State</option>
                                </select>
                                <i class="ph-bold ph-caret-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                            </div>
                        </div>

                        <div>
                            <label for="create-city" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">City / District <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select name="location" id="create-city" required
                                        class="w-full pl-4 pr-9 py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all appearance-none cursor-pointer">
                                    <option value="">Select State First</option>
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
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 px-3 py-1 rounded-lg self-start sm:self-auto">Max 10 Photos</span>
                </div>

                {{-- Upload Dropzone --}}
                <div class="border-2 border-dashed border-slate-200 dark:border-slate-700 hover:border-blue-500 rounded-2xl p-8 text-center transition-all group relative bg-slate-50/50 dark:bg-slate-800/30">
                    <div class="w-14 h-14 rounded-2xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                        <i class="ph-bold ph-cloud-arrow-up text-2xl"></i>
                    </div>
                    <p class="text-sm font-bold text-slate-800 dark:text-white mb-1">Click to upload or drag & drop photos here</p>
                    <p class="text-xs text-slate-400">Supports JPG, PNG, WebP (Max 5MB per photo)</p>
                    
                    <input type="file" name="images[]" multiple accept="image/*" required
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                           id="create-images-input" onchange="previewImages(event)">
                </div>
                
                {{-- Preview Gallery Container --}}
                <div id="image-preview-container" class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 gap-3.5 mt-6 hidden">
                    {{-- Previews dynamically rendered here --}}
                </div>
                
                @error('images') <p class="text-red-500 text-xs mt-3 font-semibold">{{ $message }}</p> @enderror
                @error('images.*') <p class="text-red-500 text-xs mt-3 font-semibold">{{ $message }}</p> @enderror
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row gap-3.5 pt-2">
                <button type="submit" class="flex-[2] py-4 bg-[#2563EB] hover:bg-blue-700 text-white font-extrabold text-sm uppercase tracking-wider rounded-xl shadow-sm shadow-blue-500/25 hover:shadow-md transition-all active:scale-[0.98] flex items-center justify-center gap-2" id="create-submit">
                    <i class="ph-bold ph-paper-plane-tilt text-base"></i> Publish Property Listing
                </button>
                <a href="{{ route('dashboard') }}" class="flex-1 py-4 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-sm text-center rounded-xl transition-all" id="create-cancel" title="Cancel">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</section>

<script>
let selectedFiles = [];

function previewImages(event) {
    const input = event.target;
    const newFiles = Array.from(input.files);
    
    if (selectedFiles.length + newFiles.length > 10) {
        alert('You can upload a maximum of 10 photos per property.');
        syncInputFiles();
        return;
    }

    const overSizeFiles = newFiles.filter(f => f.size > 5 * 1024 * 1024);
    if (overSizeFiles.length > 0) {
        alert('Each photo must be under 5MB.');
        syncInputFiles();
        return;
    }

    selectedFiles = [...selectedFiles, ...newFiles];
    syncInputFiles();
    renderPreviews();
}

function syncInputFiles() {
    const input = document.getElementById('create-images-input');
    const dataTransfer = new DataTransfer();
    selectedFiles.forEach(file => dataTransfer.items.add(file));
    input.files = dataTransfer.files;
}

function renderPreviews() {
    const container = document.getElementById('image-preview-container');
    container.innerHTML = ''; 
    
    if (selectedFiles.length > 0) {
        container.classList.remove('hidden');
    } else {
        container.classList.add('hidden');
    }

    selectedFiles.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = (e) => {
            const imgBox = document.createElement('div');
            imgBox.className = 'relative aspect-square rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700 shadow-xs group/item';
            
            imgBox.innerHTML = `
                <img src="${e.target.result}" class="w-full h-full object-cover transition-transform group-hover/item:scale-105">
                <div class="absolute inset-x-0 bottom-0 p-2 bg-gradient-to-t from-black/80 to-transparent flex justify-between items-center">
                    <span class="text-white text-[10px] font-extrabold uppercase">Photo ${index + 1}</span>
                    <button type="button" onclick="removeImage(${index})" class="text-white bg-red-600 hover:bg-red-700 p-1 rounded-full transition-all shadow-sm">
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
    syncInputFiles();
    renderPreviews();
}
</script>

@endsection
