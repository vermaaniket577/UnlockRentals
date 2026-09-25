@extends('layouts.app')

@section('title', 'List Your Professional Service — FREE | UnlockRentals')
@section('meta_description', 'Register your professional home service business on UnlockRentals for FREE. Get direct customer leads via Call and WhatsApp. Plumbers, electricians, carpenters and home technicians.')

@section('content')
<div class="min-h-screen bg-slate-50/70 dark:bg-slate-950 pb-20 pt-6">
    <div class="max-w-3xl mx-auto px-4 sm:px-6">

        {{-- Breadcrumbs --}}
        <nav class="flex items-center text-xs text-slate-500 dark:text-slate-400 mb-6 gap-2" aria-label="Breadcrumb">
            <a href="{{ url('/') }}" class="hover:text-blue-600 transition-colors">Home</a>
            <i class="ph ph-caret-right text-[10px]"></i>
            <a href="{{ route('services.index') }}" class="hover:text-blue-600 transition-colors">Local Professionals</a>
            <i class="ph ph-caret-right text-[10px]"></i>
            <span class="text-slate-900 dark:text-white font-medium">Free Registration</span>
        </nav>

        {{-- Header Card --}}
        <div class="bg-gradient-to-r from-blue-700 via-indigo-700 to-slate-900 text-white rounded-3xl p-6 sm:p-8 mb-6 shadow-xl shadow-blue-900/10 text-center relative overflow-hidden">
            <div class="relative z-10 max-w-xl mx-auto">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 text-xs font-bold uppercase tracking-wider mb-3">
                    <i class="ph-bold ph-seal-check"></i>
                    100% Free Listing • Zero Commission
                </span>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white mb-2">
                    List Your Professional Service — FREE
                </h1>
                <p class="text-xs sm:text-sm text-blue-100/90 leading-relaxed">
                    Get discovered by local customers looking for experts in your area. Receive direct calls and WhatsApp leads with zero brokerage.
                </p>
            </div>
            <div class="absolute -right-8 -bottom-8 opacity-10 pointer-events-none">
                <i class="ph-bold ph-toolbox text-[140px]"></i>
            </div>
        </div>

        {{-- Validation Error Alerts --}}
        @if($errors->any())
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 text-xs font-semibold">
                <div class="flex items-center gap-2 mb-1.5 text-sm font-bold">
                    <i class="ph-fill ph-warning-circle text-base text-rose-600"></i>
                    <span>Please correct the errors below:</span>
                </div>
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Main Streamlined Registration Form --}}
        <form action="{{ route('services.register.submit') }}" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-10 border border-slate-200/80 dark:border-slate-800 shadow-xl shadow-slate-200/40 dark:shadow-none space-y-8">
            @csrf

            {{-- 1. Contact & Identity Section --}}
            <div class="space-y-4">
                <div class="flex items-center gap-2.5 pb-2.5 border-b border-slate-100 dark:border-slate-800">
                    <div class="w-7 h-7 rounded-lg bg-blue-600 text-white flex items-center justify-center font-black text-xs">
                        1
                    </div>
                    <div>
                        <h2 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white">Your Contact Details</h2>
                        <p class="text-[11px] text-slate-500">Customers will contact you directly on these numbers</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Full Name *</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="ph ph-user text-base"></i>
                            </span>
                            <input type="text" name="full_name" value="{{ old('full_name', auth()->user()?->name) }}" required placeholder="e.g. Raj Kumar Sharma"
                                   class="w-full h-11 pl-10 pr-3.5 text-sm font-semibold rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder:text-slate-400 placeholder:font-normal focus:border-blue-600 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Email Address *</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="ph ph-envelope-simple text-base"></i>
                            </span>
                            <input type="email" name="email" value="{{ old('email', auth()->user()?->email) }}" required placeholder="e.g. raj@gmail.com"
                                   class="w-full h-11 pl-10 pr-3.5 text-sm font-semibold rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder:text-slate-400 placeholder:font-normal focus:border-blue-600 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Calling Mobile Number *</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="ph ph-phone-call text-base"></i>
                            </span>
                            <input type="tel" name="phone" id="phone_input" value="{{ old('phone', auth()->user()?->phone) }}" required placeholder="e.g. 9876543210"
                                   class="w-full h-11 pl-10 pr-3.5 text-sm font-semibold rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder:text-slate-400 placeholder:font-normal focus:border-blue-600 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">WhatsApp Number</label>
                            <label class="inline-flex items-center gap-1 text-[11px] font-semibold text-blue-600 cursor-pointer">
                                <input type="checkbox" id="sync_whatsapp" checked class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 w-3.5 h-3.5">
                                <span>Same as Calling</span>
                            </label>
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-emerald-600">
                                <i class="ph-bold ph-whatsapp-logo text-base"></i>
                            </span>
                            <input type="tel" name="whatsapp_number" id="whatsapp_input" value="{{ old('whatsapp_number', auth()->user()?->phone) }}" placeholder="e.g. 9876543210"
                                   class="w-full h-11 pl-10 pr-3.5 text-sm font-semibold rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder:text-slate-400 placeholder:font-normal focus:border-blue-600 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                        </div>
                    </div>
                </div>

                @guest
                    <div class="pt-1">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Create Password (For logging into your dashboard) *</label>
                        <div class="relative sm:w-1/2">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="ph ph-lock-key text-base"></i>
                            </span>
                            <input type="password" name="password" required placeholder="Minimum 6 characters"
                                   class="w-full h-11 pl-10 pr-3.5 text-sm font-semibold rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder:text-slate-400 placeholder:font-normal focus:border-blue-600 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                        </div>
                    </div>
                @endguest
            </div>

            {{-- 2. Business & Category Section --}}
            <div class="space-y-4 pt-2">
                <div class="flex items-center gap-2.5 pb-2.5 border-b border-slate-100 dark:border-slate-800">
                    <div class="w-7 h-7 rounded-lg bg-blue-600 text-white flex items-center justify-center font-black text-xs">
                        2
                    </div>
                    <div>
                        <h2 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white">Business & Services</h2>
                        <p class="text-[11px] text-slate-500">Tell customers about your trade and expertise</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Business / Service Name *</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="ph ph-storefront text-base"></i>
                            </span>
                            <input type="text" name="business_name" value="{{ old('business_name') }}" required placeholder="e.g. Raj Electrical & AC Works"
                                   class="w-full h-11 pl-10 pr-3.5 text-sm font-semibold rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder:text-slate-400 placeholder:font-normal focus:border-blue-600 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Primary Category *</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="ph ph-toolbox text-base"></i>
                            </span>
                            <select name="category_id" id="category_select" required
                                    class="w-full h-11 pl-10 pr-8 text-sm font-semibold rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:border-blue-600 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none cursor-pointer">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Specific Services Offered (Instant dynamic pills) --}}
                <div class="pt-1">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Specific Services Offered (Select your specialties)</label>
                    <div id="services_container" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2 p-3 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-800 min-h-[50px] max-h-48 overflow-y-auto">
                        <p class="text-xs text-slate-400 p-2 col-span-3">Please select a primary category above to view available services.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-1">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Years of Experience *</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="ph ph-calendar-check text-base"></i>
                            </span>
                            <input type="number" name="years_experience" value="{{ old('years_experience', 5) }}" min="0" max="50" required
                                   class="w-full h-11 pl-10 pr-10 text-sm font-semibold rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:border-blue-600 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                            <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-xs font-bold text-slate-400 pointer-events-none">Yrs</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Starting Price</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="ph ph-currency-inr text-base"></i>
                            </span>
                            <input type="number" name="starting_price" value="{{ old('starting_price', 299) }}" min="0" placeholder="299"
                                   class="w-full h-11 pl-10 pr-3.5 text-sm font-semibold rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:border-blue-600 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Price Model</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="ph ph-tag text-base"></i>
                            </span>
                            <select name="price_type"
                                    class="w-full h-11 pl-10 pr-8 text-sm font-semibold rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:border-blue-600 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none cursor-pointer">
                                <option value="per_visit" {{ old('price_type') == 'per_visit' ? 'selected' : '' }}>Per Visit</option>
                                <option value="hourly" {{ old('price_type') == 'hourly' ? 'selected' : '' }}>Hourly</option>
                                <option value="per_service" {{ old('price_type') == 'per_service' ? 'selected' : '' }}>Per Service</option>
                                <option value="negotiable" {{ old('price_type') == 'negotiable' ? 'selected' : '' }}>Negotiable</option>
                                <option value="contact" {{ old('price_type') == 'contact' ? 'selected' : '' }}>Contact for Price</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Key Availability Badges --}}
                <div class="flex flex-wrap items-center gap-4 pt-1">
                    <label class="inline-flex items-center gap-2 text-xs font-bold text-slate-700 dark:text-slate-300 cursor-pointer">
                        <input type="checkbox" name="home_visit" value="1" {{ old('home_visit', '1') ? 'checked' : '' }} class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 w-4 h-4">
                        <span>🏠 Home Visit Available</span>
                    </label>

                    <label class="inline-flex items-center gap-2 text-xs font-bold text-slate-700 dark:text-slate-300 cursor-pointer">
                        <input type="checkbox" name="available_today" value="1" {{ old('available_today', '1') ? 'checked' : '' }} class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 w-4 h-4">
                        <span>⚡ Available Today</span>
                    </label>

                    <label class="inline-flex items-center gap-2 text-xs font-bold text-slate-700 dark:text-slate-300 cursor-pointer">
                        <input type="checkbox" name="emergency_service" value="1" {{ old('emergency_service') ? 'checked' : '' }} class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 w-4 h-4">
                        <span>🚨 24x7 Emergency Service</span>
                    </label>
                </div>
            </div>

            {{-- 3. Location & Area Section --}}
            <div class="space-y-4 pt-2">
                <div class="flex items-center gap-2.5 pb-2.5 border-b border-slate-100 dark:border-slate-800">
                    <div class="w-7 h-7 rounded-lg bg-blue-600 text-white flex items-center justify-center font-black text-xs">
                        3
                    </div>
                    <div>
                        <h2 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white">Your Service Location</h2>
                        <p class="text-[11px] text-slate-500">Where you are based and how far you can travel</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Primary City *</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="ph ph-map-pin text-base"></i>
                            </span>
                            <input type="text" name="city" value="{{ old('city', 'Gurgaon') }}" required placeholder="e.g. Gurgaon"
                                   class="w-full h-11 pl-10 pr-3.5 text-sm font-semibold rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder:text-slate-400 placeholder:font-normal focus:border-blue-600 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Locality / Sector</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="ph ph-buildings text-base"></i>
                            </span>
                            <input type="text" name="locality" value="{{ old('locality', 'Sector 14') }}" placeholder="e.g. Sector 14"
                                   class="w-full h-11 pl-10 pr-3.5 text-sm font-semibold rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder:text-slate-400 placeholder:font-normal focus:border-blue-600 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Service Travel Radius</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="ph ph-arrows-out-cardinal text-base"></i>
                            </span>
                            <select name="service_radius_km"
                                    class="w-full h-11 pl-10 pr-8 text-sm font-semibold rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:border-blue-600 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none cursor-pointer">
                                <option value="5" {{ old('service_radius_km') == '5' ? 'selected' : '' }}>5 km</option>
                                <option value="10" {{ old('service_radius_km') == '10' ? 'selected' : '' }}>10 km</option>
                                <option value="20" {{ old('service_radius_km', 20) == '20' ? 'selected' : '' }}>20 km (Recommended)</option>
                                <option value="50" {{ old('service_radius_km') == '50' ? 'selected' : '' }}>50 km (Entire City)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">State</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="ph ph-compass text-base"></i>
                            </span>
                            <input type="text" name="state" value="{{ old('state', 'Haryana') }}" placeholder="e.g. Haryana"
                                   class="w-full h-11 pl-10 pr-3.5 text-sm font-semibold rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder:text-slate-400 placeholder:font-normal focus:border-blue-600 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Pincode</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="ph ph-hash text-base"></i>
                            </span>
                            <input type="text" name="pincode" value="{{ old('pincode', '122001') }}" placeholder="e.g. 122001"
                                   class="w-full h-11 pl-10 pr-3.5 text-sm font-semibold rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder:text-slate-400 placeholder:font-normal focus:border-blue-600 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Optional Accordion: Photos & KYC Verification --}}
            <div class="border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden bg-slate-50/50 dark:bg-slate-800/30">
                <button type="button" id="toggle_optional_btn" class="w-full p-4 flex items-center justify-between text-left text-xs sm:text-sm font-bold text-slate-800 dark:text-slate-200 hover:text-blue-600 transition-colors">
                    <div class="flex items-center gap-2">
                        <i class="ph-bold ph-shield-check text-base text-blue-600"></i>
                        <span>Add Work Photos & Verification Documents (Optional — can also be added later)</span>
                    </div>
                    <i id="accordion_icon" class="ph-bold ph-caret-down text-sm transition-transform duration-200"></i>
                </button>

                <div id="optional_section" class="hidden p-4 sm:p-6 border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Profile Photo / Logo</label>
                        <input type="file" name="profile_photo" accept="image/*"
                               class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Custom Description</label>
                        <textarea name="description" rows="2" placeholder="Tell customers about your skills, quality guarantee, and specialties..."
                                  class="w-full p-3 text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder:text-slate-400 focus:border-blue-600 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none">{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Work Samples / Past Work Photos</label>
                        <input type="file" name="photos[]" multiple accept="image/*"
                               class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                    </div>

                    <div class="p-3.5 rounded-xl bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-800/50 space-y-2">
                        <div class="flex items-center gap-1.5 text-xs font-bold text-amber-800 dark:text-amber-300">
                            <i class="ph-bold ph-lock-key"></i>
                            <span>Private ID / Business Document (Optional for Verified Badge)</span>
                        </div>
                        <p class="text-[11px] text-amber-700 dark:text-amber-400">
                            Security Guarantee: Documents are strictly confidential and only inspected by UnlockRentals admins to grant your <strong>✓ Verified Professional</strong> badge. Never shown to customers.
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                            <select name="document_type" class="w-full h-10 px-3 text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white">
                                <option value="id_proof">Aadhaar Card / Voter ID / Passport</option>
                                <option value="address_proof">Address Proof / Electricity Bill</option>
                                <option value="certificate">Trade / ITI / Skill Certificate</option>
                                <option value="business_registration">GST / Trade License</option>
                                <option value="other">Other Official Document</option>
                            </select>
                            <input type="file" name="document_file" accept=".pdf,image/*"
                                   class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-white file:text-slate-700 hover:file:bg-slate-100 cursor-pointer">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Submit Action --}}
            <div class="pt-2 text-center space-y-3">
                <button type="submit" class="w-full py-3.5 px-8 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-black text-base shadow-xl shadow-blue-600/25 transition-all hover:scale-[1.01] active:scale-95 flex items-center justify-center gap-2 cursor-pointer">
                    <i class="ph-bold ph-rocket-launch text-lg"></i>
                    <span>Submit & List My Service — FREE</span>
                </button>

                <div class="flex flex-wrap items-center justify-center gap-4 text-xs font-semibold text-slate-400 dark:text-slate-500">
                    <span class="flex items-center gap-1"><i class="ph-bold ph-check text-emerald-500"></i> No Credit Card Required</span>
                    <span class="flex items-center gap-1"><i class="ph-bold ph-check text-emerald-500"></i> Direct Call & WhatsApp</span>
                    <span class="flex items-center gap-1"><i class="ph-bold ph-check text-emerald-500"></i> 100% Free Forever</span>
                </div>
            </div>
        </form>

    </div>
</div>

{{-- Dynamic Category Services & WhatsApp Sync Script --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const catSelect = document.getElementById('category_select');
        const servicesBox = document.getElementById('services_container');
        const phoneInput = document.getElementById('phone_input');
        const whatsappInput = document.getElementById('whatsapp_input');
        const syncCheckbox = document.getElementById('sync_whatsapp');
        const toggleOptionalBtn = document.getElementById('toggle_optional_btn');
        const optionalSection = document.getElementById('optional_section');
        const accordionIcon = document.getElementById('accordion_icon');

        // Sync WhatsApp number with calling phone
        if (phoneInput && whatsappInput && syncCheckbox) {
            phoneInput.addEventListener('input', function() {
                if (syncCheckbox.checked) {
                    whatsappInput.value = this.value;
                }
            });
            syncCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    whatsappInput.value = phoneInput.value;
                }
            });
        }

        // Optional Section Accordion Toggle
        if (toggleOptionalBtn && optionalSection) {
            toggleOptionalBtn.addEventListener('click', function() {
                optionalSection.classList.toggle('hidden');
                if (accordionIcon) {
                    accordionIcon.classList.toggle('rotate-180');
                }
            });
        }

        // Load sub-services on category change
        if (catSelect && servicesBox) {
            if (catSelect.value) {
                loadServices(catSelect.value);
            }

            catSelect.addEventListener('change', function () {
                loadServices(this.value);
            });
        }

        function loadServices(categoryId) {
            if (!categoryId) {
                servicesBox.innerHTML = '<p class="text-xs text-slate-400 p-2 col-span-3">Please select a primary category above.</p>';
                return;
            }

            servicesBox.innerHTML = '<p class="text-xs text-blue-600 p-2 col-span-3 flex items-center gap-1.5"><i class="ph ph-spinner animate-spin"></i> Loading services...</p>';

            fetch(`/services/categories/${categoryId}/services`)
                .then(res => res.json())
                .then(services => {
                    if (!services || services.length === 0) {
                        servicesBox.innerHTML = '<p class="text-xs text-slate-400 p-2 col-span-3">General services for this category.</p>';
                        return;
                    }

                    let html = '';
                    services.forEach(serv => {
                        html += `
                            <label class="flex items-center gap-2 text-xs font-semibold text-slate-800 dark:text-slate-200 cursor-pointer p-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-blue-500 hover:bg-blue-50/50 dark:hover:bg-blue-900/20 transition-all">
                                <input type="checkbox" name="services[]" value="${serv.id}" checked class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 w-3.5 h-3.5">
                                <span class="truncate">${serv.name}</span>
                            </label>
                        `;
                    });

                    servicesBox.innerHTML = html;
                })
                .catch(err => {
                    servicesBox.innerHTML = '<p class="text-xs text-rose-500 p-2 col-span-3">Unable to load services.</p>';
                });
        }
    });
</script>
@endsection
