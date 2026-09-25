@extends('layouts.app')

@section('title', 'List Your Professional Service — FREE | UnlockRentals')
@section('meta_description', 'Register your professional home service business on UnlockRentals for FREE. Get direct customer leads via Call and WhatsApp. Plumbers, electricians, carpenters and home technicians.')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-900 pb-20 pt-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Breadcrumbs --}}
        <nav class="flex items-center text-xs text-slate-500 dark:text-slate-400 mb-6 gap-2" aria-label="Breadcrumb">
            <a href="{{ url('/') }}" class="hover:text-blue-600 transition-colors">Home</a>
            <i class="ph ph-caret-right text-[10px]"></i>
            <a href="{{ route('services.index') }}" class="hover:text-blue-600 transition-colors">Local Professionals</a>
            <i class="ph ph-caret-right text-[10px]"></i>
            <span class="text-slate-900 dark:text-white font-medium">Free Registration</span>
        </nav>

        {{-- Top Hero Header --}}
        <div class="bg-gradient-to-br from-blue-700 via-indigo-700 to-slate-900 text-white rounded-3xl p-6 sm:p-10 mb-8 shadow-xl shadow-blue-900/10 relative overflow-hidden">
            <div class="max-w-2xl relative z-10">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 text-xs font-black uppercase tracking-wider mb-3">
                    <i class="ph-bold ph-seal-check"></i>
                    100% FREE Registration • No Commissions
                </span>
                <h1 class="text-2xl sm:text-4xl font-black tracking-tight leading-tight text-white mb-2">
                    List Your Professional Service — FREE
                </h1>
                <p class="text-xs sm:text-sm text-blue-100/90 leading-relaxed">
                    Get discovered by thousands of customers looking for local professionals in your area. Receive direct calls and WhatsApp leads without middleman cuts.
                </p>
            </div>
            <div class="absolute -right-10 -bottom-10 opacity-10 pointer-events-none">
                <i class="ph-duotone ph-briefcase text-[160px]"></i>
            </div>
        </div>

        {{-- Error Alerts --}}
        @if($errors->any())
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 text-xs font-semibold">
                <div class="flex items-center gap-2 mb-1 text-sm font-bold">
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

        {{-- Registration Form Container --}}
        <form action="{{ route('services.register.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            {{-- Step 1: Personal & Contact Information --}}
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 border border-slate-200/80 dark:border-slate-700/80 shadow-sm space-y-5">
                <div class="flex items-center gap-3 pb-4 border-b border-slate-100 dark:border-slate-700">
                    <div class="w-8 h-8 rounded-xl bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-300 flex items-center justify-center font-black text-sm">
                        1
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">Personal & Contact Information</h3>
                        <p class="text-xs text-slate-500">Your identity and direct contact information</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Full Name *</label>
                        <input type="text" name="full_name" value="{{ old('full_name', auth()->user()?->name) }}" required placeholder="e.g. Raj Kumar Sharma" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Email Address *</label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()?->email) }}" required placeholder="e.g. raj.electricals@gmail.com" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Mobile Calling Number *</label>
                        <input type="tel" name="phone" value="{{ old('phone', auth()->user()?->phone) }}" required placeholder="e.g. 9876543210" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">WhatsApp Number</label>
                        <input type="tel" name="whatsapp_number" value="{{ old('whatsapp_number', auth()->user()?->phone) }}" placeholder="Leave blank if same as mobile" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
                    </div>
                </div>

                @guest
                    <div class="pt-2">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Create Account Password *</label>
                        <input type="password" name="password" required placeholder="Minimum 6 characters for logging into your dashboard" class="w-full sm:w-1/2 text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
                        <p class="text-[11px] text-slate-400 mt-1">An account will be created automatically so you can manage leads and profile.</p>
                    </div>
                @endguest

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Profile / Work Avatar Photo</label>
                    <input type="file" name="profile_photo" accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="text-[11px] text-slate-400 mt-0.5">Clear picture of yourself or your business logo (Max 3MB, JPG/PNG/WebP)</p>
                </div>
            </div>

            {{-- Step 2: Professional & Service Details --}}
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 border border-slate-200/80 dark:border-slate-700/80 shadow-sm space-y-5">
                <div class="flex items-center gap-3 pb-4 border-b border-slate-100 dark:border-slate-700">
                    <div class="w-8 h-8 rounded-xl bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-300 flex items-center justify-center font-black text-sm">
                        2
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">Professional & Service Information</h3>
                        <p class="text-xs text-slate-500">What services you provide and your background</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Business / Brand Name *</label>
                        <input type="text" name="business_name" value="{{ old('business_name') }}" required placeholder="e.g. Raj Electrical & AC Works" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Primary Category *</label>
                        <select name="category_id" id="category_select" required class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Specific Services Offered (Dynamic via Category) --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">Specific Services You Offer * (Select all that apply)</label>
                    <div id="services_container" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2.5 p-4 rounded-2xl bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-700 max-h-60 overflow-y-auto">
                        <p class="text-xs text-slate-400 col-span-3">Please select a primary category above to view available services.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Years of Experience *</label>
                        <input type="number" name="years_experience" value="{{ old('years_experience', 5) }}" min="0" max="50" required class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Starting Price (₹)</label>
                            <input type="number" name="starting_price" value="{{ old('starting_price') }}" min="0" placeholder="e.g. 299" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Price Type</label>
                            <select name="price_type" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
                                <option value="per_visit">Per Visit</option>
                                <option value="hourly">Hourly</option>
                                <option value="per_service">Per Service</option>
                                <option value="negotiable">Negotiable</option>
                                <option value="contact">Contact for Price</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Professional Profile Description *</label>
                    <textarea name="description" rows="4" required placeholder="Describe your experience, types of jobs handled, quality guarantees, and reasons why customers should choose you..." class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">{{ old('description') }}</textarea>
                </div>

                {{-- Preferences --}}
                <div class="pt-2 flex flex-wrap gap-6">
                    <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 dark:text-slate-300 cursor-pointer">
                        <input type="checkbox" name="home_visit" value="1" {{ old('home_visit', '1') ? 'checked' : '' }} class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        <span>🏠 Home Visit Available</span>
                    </label>

                    <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 dark:text-slate-300 cursor-pointer">
                        <input type="checkbox" name="emergency_service" value="1" {{ old('emergency_service') ? 'checked' : '' }} class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        <span>🚨 24x7 Emergency Service</span>
                    </label>

                    <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 dark:text-slate-300 cursor-pointer">
                        <input type="checkbox" name="available_today" value="1" {{ old('available_today', '1') ? 'checked' : '' }} class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        <span>⚡ Available Today</span>
                    </label>
                </div>
            </div>

            {{-- Step 3: Location & Service Coverage --}}
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 border border-slate-200/80 dark:border-slate-700/80 shadow-sm space-y-5">
                <div class="flex items-center gap-3 pb-4 border-b border-slate-100 dark:border-slate-700">
                    <div class="w-8 h-8 rounded-xl bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-300 flex items-center justify-center font-black text-sm">
                        3
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">Location & Service Areas</h3>
                        <p class="text-xs text-slate-500">Where you are located and which areas you can travel to</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">State</label>
                        <input type="text" name="state" value="{{ old('state', 'Haryana') }}" placeholder="e.g. Haryana" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Primary City *</label>
                        <input type="text" name="city" value="{{ old('city', 'Gurgaon') }}" required placeholder="e.g. Gurgaon" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Locality / Sector</label>
                        <input type="text" name="locality" value="{{ old('locality', 'Sector 14') }}" placeholder="e.g. Sector 14" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Pincode</label>
                        <input type="text" name="pincode" value="{{ old('pincode', '122001') }}" placeholder="e.g. 122001" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Service Radius (Travel Distance) *</label>
                        <select name="service_radius_km" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
                            <option value="5">5 km radius</option>
                            <option value="10">10 km radius</option>
                            <option value="20" selected>20 km radius</option>
                            <option value="50">50 km radius (Entire City)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Full Shop / Base Address (Optional)</label>
                    <input type="text" name="address" value="{{ old('address') }}" placeholder="e.g. Shop No. 12, Main Market, Sector 14" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
                    <p class="text-[11px] text-slate-400 mt-0.5">Note: We only display your locality & city publicly to protect your privacy.</p>
                </div>
            </div>

            {{-- Step 4: Work Photos & KYC Verification --}}
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 border border-slate-200/80 dark:border-slate-700/80 shadow-sm space-y-5">
                <div class="flex items-center gap-3 pb-4 border-b border-slate-100 dark:border-slate-700">
                    <div class="w-8 h-8 rounded-xl bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-300 flex items-center justify-center font-black text-sm">
                        4
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">Work Photos & Private Verification Documents</h3>
                        <p class="text-xs text-slate-500">Showcase your past work and get the verified badge</p>
                    </div>
                </div>

                {{-- Work Photos --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Work Photos (Upload past job pictures)</label>
                    <input type="file" name="photos[]" multiple accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="text-[11px] text-slate-400 mt-0.5">Upload up to 5 photos of your work, equipment or service examples (Max 4MB each)</p>
                </div>

                {{-- Confidential KYC Box --}}
                <div class="p-4 rounded-2xl bg-amber-50/50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-800/60 space-y-3">
                    <div class="flex items-center gap-2 text-xs font-bold text-amber-800 dark:text-amber-300">
                        <i class="ph-bold ph-lock-key text-base"></i>
                        <span>Confidential Verification Document (Aadhaar / Driving License / Certificate)</span>
                    </div>
                    <p class="text-[11px] text-amber-700 dark:text-amber-400 leading-relaxed">
                        Security Notice: These documents are stored privately in encrypted server storage. They are strictly accessible only to UnlockRentals admins to verify your identity and give you the <strong>✓ Verified Professional</strong> badge. They will <strong>NEVER</strong> be displayed publicly.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1">Document Type</label>
                            <select name="document_type" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white">
                                <option value="ID Proof">Aadhaar Card / Voter ID / Passport</option>
                                <option value="Address Proof">Electricity Bill / Ration Card</option>
                                <option value="Certificate">Trade Certificate / ITI / Diploma</option>
                                <option value="Business Proof">GST / Shop License / MSME</option>
                                <option value="Other">Other Official Document</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1">Document Number (Optional)</label>
                            <input type="text" name="document_number" placeholder="e.g. XXXX-XXXX-1234" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1">Upload Document File (PDF, JPG, PNG)</label>
                        <input type="file" name="document_file" accept=".pdf,image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-white file:text-slate-700 hover:file:bg-slate-100">
                    </div>
                </div>
            </div>

            {{-- Submit Button & Terms --}}
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 border border-slate-200/80 dark:border-slate-700/80 shadow-sm text-center space-y-4">
                <div class="flex items-center justify-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                    <i class="ph-bold ph-shield-check text-base text-emerald-600"></i>
                    <span>By registering, you agree to UnlockRentals Professional Service Terms & Privacy Policy.</span>
                </div>

                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-10 py-4 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-black text-sm sm:text-base shadow-xl shadow-blue-600/30 transition-all hover:scale-[1.02] active:scale-95">
                    <i class="ph-bold ph-rocket-launch text-lg"></i>
                    <span>Submit & List My Service — FREE</span>
                </button>
            </div>
        </form>

    </div>
</div>

{{-- Dynamic Category Services Loader Script --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const catSelect = document.getElementById('category_select');
        const servicesBox = document.getElementById('services_container');

        const initialCategory = catSelect.value;
        if (initialCategory) {
            loadServices(initialCategory);
        }

        catSelect.addEventListener('change', function () {
            loadServices(this.value);
        });

        function loadServices(categoryId) {
            if (!categoryId) {
                servicesBox.innerHTML = '<p class="text-xs text-slate-400 col-span-3">Please select a primary category above.</p>';
                return;
            }

            servicesBox.innerHTML = '<p class="text-xs text-blue-600 col-span-3 flex items-center gap-1.5"><i class="ph ph-spinner animate-spin"></i> Loading services...</p>';

            fetch(`/services/categories/${categoryId}/services`)
                .then(res => res.json())
                .then(services => {
                    if (!services || services.length === 0) {
                        servicesBox.innerHTML = '<p class="text-xs text-slate-400 col-span-3">General services for this category.</p>';
                        return;
                    }

                    let html = '';
                    services.forEach(serv => {
                        html += `
                            <label class="flex items-center gap-2 text-xs text-slate-700 dark:text-slate-300 cursor-pointer p-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200/60 dark:border-slate-600 hover:border-blue-400 transition-colors">
                                <input type="checkbox" name="services[]" value="${serv.id}" checked class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                <span class="font-medium">${serv.name}</span>
                            </label>
                        `;
                    });

                    servicesBox.innerHTML = html;
                })
                .catch(err => {
                    servicesBox.innerHTML = '<p class="text-xs text-rose-500 col-span-3">Unable to load services.</p>';
                });
        }
    });
</script>
@endsection
