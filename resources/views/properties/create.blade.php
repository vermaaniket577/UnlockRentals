@extends('layouts.app')

@section('title', 'List a Property - UnlockRentals')

@section('content')

<section class="py-12 lg:py-20 bg-stone-50/30" id="create-property">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-12 text-center">
            <span class="inline-block px-4 py-1.5 bg-[#2563EB]/10 text-[#2563EB] text-[10px] font-bold uppercase tracking-widest rounded-full mb-4">Marketplace</span>
            <h1 class="text-4xl md:text-5xl font-bold text-zinc-900 mb-4 tracking-tight">List Your Property</h1>
            <p class="text-zinc-500 text-lg max-w-2xl mx-auto">Showcase your property to our exclusive community of high-intent tenants and buyers.</p>
        </div>

        <form method="POST" action="{{ route('properties.store') }}" enctype="multipart/form-data" class="space-y-10" id="create-property-form" data-ur-loader-msg="Uploading images & processing listing...">
            @csrf

            {{-- Sequential Section Guide --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                
                {{-- Left Side: Side Info/Progress (Visual Only) --}}
                <div class="hidden lg:block lg:col-span-3 space-y-8 sticky top-24 h-fit">
                    <div class="flex items-center gap-4 group">
                        <div class="w-10 h-10 rounded-full bg-[#2563EB] text-white flex items-center justify-center font-bold shadow-lg shadow-[#2563EB]/20">1</div>
                        <span class="text-sm font-semibold text-zinc-900">Basic Info</span>
                    </div>
                    <div class="w-0.5 h-12 bg-stone-200 ml-5"></div>
                    <div class="flex items-center gap-4 opacity-40">
                        <div class="w-10 h-10 rounded-full bg-zinc-400 text-white flex items-center justify-center font-bold">2</div>
                        <span class="text-sm font-semibold text-zinc-500">Pricing</span>
                    </div>
                    <div class="w-0.5 h-12 bg-stone-200 ml-5"></div>
                    <div class="flex items-center gap-4 opacity-40">
                        <div class="w-10 h-10 rounded-full bg-zinc-400 text-white flex items-center justify-center font-bold">3</div>
                        <span class="text-sm font-semibold text-zinc-500">Location</span>
                    </div>
                </div>

                {{-- Right Side: Form Content --}}
                <div class="lg:col-span-9 space-y-8">
                    
                    {{-- Basic Info --}}
                    <div class="bg-white border border-stone-200/60 rounded-2xl p-8 shadow-sm hover:shadow-md transition-shadow" id="create-basic-info">
                        <h2 class="text-xl font-bold text-zinc-900 mb-8 flex items-center gap-3">
                            <span class="p-2 bg-[#2563EB]/5 text-[#2563EB] rounded-lg">
                                <i class="ph-fill ph-info"></i>
                            </span> 
                            Basic Information
                        </h2>

                        <div class="space-y-6">
                            <div>
                                <label for="create-title" class="block text-xs font-bold text-zinc-400 uppercase tracking-wider mb-2">Property Title *</label>
                                <input type="text" name="title" id="create-title" value="{{ old('title') }}" required
                                       class="w-full px-5 py-4 bg-zinc-50/50 border border-zinc-200 rounded-xl text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-4 focus:ring-[#2563EB]/5 focus:border-[#2563EB] transition-all"
                                       placeholder="e.g., Ultra Modern 3BHK Penthouse in Bandra West">
                                @error('title') <p class="text-red-500 text-xs mt-2 font-medium">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="create-description" class="block text-xs font-bold text-zinc-400 uppercase tracking-wider mb-2">Description *</label>
                                <textarea name="description" id="create-description" rows="5" required
                                          class="w-full px-5 py-4 bg-zinc-50/50 border border-zinc-200 rounded-xl text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-4 focus:ring-[#2563EB]/5 focus:border-[#2563EB] transition-all resize-none"
                                          placeholder="Share the story of your property, unique features, and premium amenities...">{{ old('description') }}</textarea>
                                @error('description') <p class="text-red-500 text-xs mt-2 font-medium">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="create-type" class="block text-xs font-bold text-zinc-400 uppercase tracking-wider mb-2">Property Type *</label>
                                    <select name="type" id="create-type" required
                                            class="w-full px-5 py-4 bg-zinc-50/50 border border-zinc-200 rounded-xl text-sm text-zinc-900 focus:outline-none focus:ring-4 focus:ring-[#2563EB]/5 focus:border-[#2563EB] transition-all appearance-none cursor-pointer">
                                        <option value="house" {{ old('type') === 'house' ? 'selected' : '' }}>House / Apartment</option>
                                        <option value="shop" {{ old('type') === 'shop' ? 'selected' : '' }}>Commercial Shop</option>
                                        <option value="pg-hostel" {{ old('type') === 'pg-hostel' ? 'selected' : '' }}>PG / Hostel</option>
                                        <option value="hotel" {{ old('type') === 'hotel' ? 'selected' : '' }}>Hotel Room</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="create-category" class="block text-xs font-bold text-zinc-400 uppercase tracking-wider mb-2">Category *</label>
                                    <select name="category_id" id="create-category" required
                                            class="w-full px-5 py-4 bg-zinc-50/50 border border-zinc-200 rounded-xl text-sm text-zinc-900 focus:outline-none focus:ring-4 focus:ring-[#2563EB]/5 focus:border-[#2563EB] transition-all appearance-none cursor-pointer">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Pricing --}}
                    <div class="bg-white border border-stone-200/60 rounded-2xl p-8 shadow-sm hover:shadow-md transition-shadow" id="create-pricing">
                        <h2 class="text-xl font-bold text-zinc-900 mb-8 flex items-center gap-3">
                            <span class="p-2 bg-[#2563EB]/5 text-[#2563EB] rounded-lg">
                                <i class="ph-fill ph-currency-inr"></i>
                            </span> 
                            Pricing & Billing
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="create-price" class="block text-xs font-bold text-zinc-400 uppercase tracking-wider mb-2">Expected Rent (₹) *</label>
                                <div class="relative">
                                    <span class="absolute left-5 top-1/2 -translate-y-1/2 text-zinc-400 font-semibold">₹</span>
                                    <input type="number" name="price" id="create-price" value="{{ old('price') }}" required min="0" step="0.01"
                                           class="w-full pl-10 pr-5 py-4 bg-zinc-50/50 border border-zinc-200 rounded-xl text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-4 focus:ring-[#2563EB]/5 focus:border-[#2563EB] transition-all"
                                           placeholder="25,000">
                                </div>
                                @error('price') <p class="text-red-500 text-xs mt-2 font-medium">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="create-period" class="block text-xs font-bold text-zinc-400 uppercase tracking-wider mb-2">Billing Cycle *</label>
                                <select name="price_period" id="create-period" required
                                        class="w-full px-5 py-4 bg-zinc-50/50 border border-zinc-200 rounded-xl text-sm text-zinc-900 focus:outline-none focus:ring-4 focus:ring-[#2563EB]/5 focus:border-[#2563EB] transition-all">
                                    <option value="month" {{ old('price_period') === 'month' ? 'selected' : '' }}>Per Month</option>
                                    <option value="year" {{ old('price_period') === 'year' ? 'selected' : '' }}>Per Year</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Location --}}
                    <div class="bg-white border border-stone-200/60 rounded-2xl p-8 shadow-sm hover:shadow-md transition-shadow" id="create-location">
                        <h2 class="text-xl font-bold text-zinc-900 mb-8 flex items-center gap-3">
                            <span class="p-2 bg-[#2563EB]/5 text-[#2563EB] rounded-lg">
                                <i class="ph-fill ph-map-pin"></i>
                            </span> 
                            Location Details
                        </h2>

                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="create-state" class="block text-xs font-bold text-zinc-400 uppercase tracking-wider mb-2">State *</label>
                                    <select name="state" id="create-state" required
                                            class="w-full px-5 py-4 bg-zinc-50/50 border border-zinc-200 rounded-xl text-sm text-zinc-900 focus:outline-none focus:ring-4 focus:ring-[#2563EB]/5 focus:border-[#2563EB] transition-all">
                                        <option value="">Select State</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="create-city" class="block text-xs font-bold text-zinc-400 uppercase tracking-wider mb-2">City / District *</label>
                                    <select name="location" id="create-city" required
                                            class="w-full px-5 py-4 bg-zinc-50/50 border border-zinc-200 rounded-xl text-sm text-zinc-900 focus:outline-none focus:ring-4 focus:ring-[#2563EB]/5 focus:border-[#2563EB] transition-all">
                                        <option value="">Select State First</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div>
                                <label for="create-locality-select" class="block text-xs font-bold text-zinc-400 uppercase tracking-wider mb-2">Locality / Area *</label>
                                <div id="locality-select-wrap">
                                    <select name="locality" id="create-locality-select"
                                            class="w-full px-5 py-4 bg-zinc-50/50 border border-zinc-200 rounded-xl text-sm text-zinc-900 focus:outline-none focus:ring-4 focus:ring-[#2563EB]/5 focus:border-[#2563EB] transition-all">
                                        <option value="">Select City First</option>
                                    </select>
                                </div>
                                <div id="locality-text-wrap" style="display: none;">
                                    <input type="text" name="locality" id="create-locality-text" value="{{ old('locality') }}" 
                                           class="w-full px-5 py-4 bg-zinc-50/50 border border-zinc-200 rounded-xl text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-4 focus:ring-[#2563EB]/5 focus:border-[#2563EB] transition-all"
                                           placeholder="e.g., Carter Road">
                                </div>
                            </div>

                            <div>
                                <label for="create-address" class="block text-xs font-bold text-zinc-400 uppercase tracking-wider mb-2">Full Address *</label>
                                <input type="text" name="address" id="create-address" value="{{ old('address') }}" required
                                       class="w-full px-5 py-4 bg-zinc-50/50 border border-zinc-200 rounded-xl text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-4 focus:ring-[#2563EB]/5 focus:border-[#2563EB] transition-all"
                                       placeholder="House No, Building Name, Street, Famous Landmark">
                            </div>
                        </div>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
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
                            });
                        </script>
                    </div>

                    {{-- Property Specs --}}
                    <div class="bg-white border border-stone-200/60 rounded-2xl p-8 shadow-sm hover:shadow-md transition-shadow" id="create-details">
                        <h2 class="text-xl font-bold text-zinc-900 mb-8 flex items-center gap-3">
                            <span class="p-2 bg-[#2563EB]/5 text-[#2563EB] rounded-lg">
                                <i class="ph-fill ph-house-line"></i>
                            </span> 
                            Key Specifications
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                            <div>
                                <label for="create-bedrooms" class="block text-xs font-bold text-zinc-400 uppercase tracking-wider mb-2">Bedrooms</label>
                                <input type="number" name="bedrooms" id="create-bedrooms" value="{{ old('bedrooms') }}" min="0" max="20"
                                       class="w-full px-5 py-4 bg-zinc-50/50 border border-zinc-200 rounded-xl text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-4 focus:ring-[#2563EB]/5 focus:border-[#2563EB] transition-all"
                                       placeholder="3">
                            </div>
                            <div>
                                <label for="create-bathrooms" class="block text-xs font-bold text-zinc-400 uppercase tracking-wider mb-2">Bathrooms</label>
                                <input type="number" name="bathrooms" id="create-bathrooms" value="{{ old('bathrooms') }}" min="0" max="20"
                                       class="w-full px-5 py-4 bg-zinc-50/50 border border-zinc-200 rounded-xl text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-4 focus:ring-[#2563EB]/5 focus:border-[#2563EB] transition-all"
                                       placeholder="2">
                            </div>
                            <div>
                                <label for="create-area" class="block text-xs font-bold text-zinc-400 uppercase tracking-wider mb-2">Area (sq ft)</label>
                                <input type="number" name="area_sqft" id="create-area" value="{{ old('area_sqft') }}" min="0"
                                       class="w-full px-5 py-4 bg-zinc-50/50 border border-zinc-200 rounded-xl text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-4 focus:ring-[#2563EB]/5 focus:border-[#2563EB] transition-all"
                                       placeholder="1200">
                            </div>
                        </div>

                        <div class="mt-8">
                            <label for="create-furnishing" class="block text-xs font-bold text-zinc-400 uppercase tracking-wider mb-2">Furnishing Status *</label>
                            <select name="furnishing" id="create-furnishing" required
                                    class="w-full px-5 py-4 bg-zinc-50/50 border border-zinc-200 rounded-xl text-sm text-zinc-900 focus:outline-none focus:ring-4 focus:ring-[#2563EB]/5 focus:border-[#2563EB] transition-all">
                                <option value="unfurnished" {{ old('furnishing') === 'unfurnished' ? 'selected' : '' }}>Unfurnished</option>
                                <option value="semi-furnished" {{ old('furnishing') === 'semi-furnished' ? 'selected' : '' }}>Semi-Furnished</option>
                                <option value="fully-furnished" {{ old('furnishing') === 'fully-furnished' ? 'selected' : '' }}>Fully Furnished</option>
                            </select>
                        </div>
                    </div>

                    {{-- Images --}}
                    <div class="bg-white border border-stone-200/60 rounded-2xl p-8 shadow-sm hover:shadow-md transition-shadow" id="create-images">
                        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                            <h2 class="text-xl font-bold text-zinc-900 flex items-center gap-3">
                                <span class="p-2 bg-[#2563EB]/5 text-[#2563EB] rounded-lg">
                                    <i class="ph-fill ph-images-square"></i>
                                </span> 
                                Media & Gallery
                            </h2>
                            <span class="text-xs font-bold text-zinc-400 uppercase tracking-widest px-3 py-1 bg-zinc-50 rounded-lg border border-zinc-200">Max 10 Images</span>
                        </div>

                        <div class="border-2 border-dashed border-zinc-200 rounded-2xl p-10 text-center hover:border-[#2563EB] hover:bg-stone-50/50 transition-all group relative">
                            <div class="mb-5 inline-flex items-center justify-center w-16 h-16 bg-[#2563EB]/5 text-[#2563EB] rounded-full group-hover:scale-110 transition-transform">
                                <i class="ph ph-cloud-arrow-up text-3xl"></i>
                            </div>
                            <p class="text-zinc-600 font-semibold mb-1">Click or drag images here</p>
                            <p class="text-xs text-zinc-400">Suports JPG, PNG, WebP (Max 2MB per image)</p>
                            
                            <input type="file" name="images[]" multiple accept="image/*" required
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                   id="create-images-input" onchange="previewImages(event)">
                        </div>
                        
                        {{-- Preview Gallery Container --}}
                        <div id="image-preview-container" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4 mt-8 hidden">
                            {{-- JS injected previews go here --}}
                        </div>
                        
                        @error('images') <p class="text-red-500 text-xs mt-4 font-medium">{{ $message }}</p> @enderror
                        @error('images.*') <p class="text-red-500 text-xs mt-4 font-medium">{{ $message }}</p> @enderror
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex flex-col sm:flex-row gap-4 pt-6">
                        <button type="submit" class="flex-[2] px-8 py-5 bg-[#2563EB] hover:bg-[#1D4ED8] text-white font-bold rounded-xl shadow-xl shadow-[#2563EB]/25 hover:shadow-2xl hover:shadow-[#2563EB]/35 transition-all flex items-center justify-center gap-3" id="create-submit">
                            <i class="ph ph-paper-plane-tilt text-xl"></i> Submit for Approval
                        </button>
                        <a href="{{ route('dashboard') }}" class="flex-1 px-8 py-5 bg-white border border-stone-200 text-zinc-500 font-bold rounded-xl hover:bg-stone-50 hover:text-zinc-900 text-center transition-all" id="create-cancel">
                            Cancel
                        </a>
                    </div>
                </div>
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
        alert('You can only upload a maximum of 10 images per property.');
        // Sync input back to our internal state
        syncInputFiles();
        return;
    }

    // Add new files to our collection
    selectedFiles = [...selectedFiles, ...newFiles];
    
    // Sync the actual input element so form submits all files
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
            imgBox.className = 'relative aspect-square rounded-xl overflow-hidden border border-stone-200 shadow-sm group/item';
            
            imgBox.innerHTML = `
                <img src="${e.target.result}" class="w-full h-full object-cover transition-transform group-hover/item:scale-110">
                <div class="absolute inset-x-0 bottom-0 p-3 bg-gradient-to-t from-black/80 via-black/40 to-transparent flex justify-between items-center translate-y-2 group-hover/item:translate-y-0 transition-transform">
                    <span class="text-white text-[10px] font-bold uppercase tracking-wider">Image ${index + 1}</span>
                    <button type="button" onclick="removeImage(${index})" class="text-white bg-red-500/80 hover:bg-red-500 p-1.5 rounded-full transition-all shadow-lg">
                        <i class="ph ph-trash text-xs"></i>
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
