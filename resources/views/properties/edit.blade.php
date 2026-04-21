@extends('layouts.app')

@section('title', 'Edit: ' . $property->title . ' - UnlockRentals')

@section('content')

<section class="py-8 lg:py-12" id="edit-property">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-medium text-zinc-900 mb-2">Edit Property</h1>
            <p class="text-zinc-500">Update the details of your listing below.</p>
        </div>

        <form method="POST" action="{{ route('properties.update', $property) }}" enctype="multipart/form-data" class="space-y-6" id="edit-property-form" data-ur-loader-msg="Saving property changes...">
            @csrf
            @method('PUT')

            {{-- Basic Info --}}
            <div class="bg-stone-50 border border-stone-200/50 rounded-sm p-6">
                <h2 class="text-lg font-semibold text-zinc-900 mb-5 flex items-center gap-2">
                    <i class="ph ph-info text-[#2563EB]"></i> Basic Information
                </h2>

                <div class="space-y-4">
                    <div>
                        <label for="edit-title" class="block text-sm font-medium text-zinc-500 mb-1.5">Property Title *</label>
                        <input type="text" name="title" id="edit-title" value="{{ old('title', $property->title) }}" required
                               class="w-full px-4 py-3 bg-stone-50 border border-stone-200/50 rounded-sm text-sm text-zinc-900 placeholder-gray-500 focus:outline-none focus:border-[#2563EB]/50 transition-all">
                        @error('title') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="edit-description" class="block text-sm font-medium text-zinc-500 mb-1.5">Description *</label>
                        <textarea name="description" id="edit-description" rows="5" required
                                  class="w-full px-4 py-3 bg-stone-50 border border-stone-200/50 rounded-sm text-sm text-zinc-900 placeholder-gray-500 focus:outline-none focus:border-[#2563EB]/50 transition-all resize-none">{{ old('description', $property->description) }}</textarea>
                        @error('description') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="edit-type" class="block text-sm font-medium text-zinc-500 mb-1.5">Property Type *</label>
                            <select name="type" id="edit-type" required
                                    class="w-full px-4 py-3 bg-stone-50 border border-stone-200/50 rounded-sm text-sm text-zinc-900 focus:outline-none focus:border-[#2563EB]/50 transition-all">
                                <option value="house" {{ old('type', $property->type) === 'house' ? 'selected' : '' }}>House</option>
                                <option value="shop" {{ old('type', $property->type) === 'shop' ? 'selected' : '' }}>Shop</option>
                                <option value="pg-hostel" {{ old('type', $property->type) === 'pg-hostel' ? 'selected' : '' }}>PG / Hostel</option>
                                <option value="hotel" {{ old('type', $property->type) === 'hotel' ? 'selected' : '' }}>Hotel</option>
                            </select>
                        </div>
                        <div>
                            <label for="edit-category" class="block text-sm font-medium text-zinc-500 mb-1.5">Category *</label>
                            <select name="category_id" id="edit-category" required
                                    class="w-full px-4 py-3 bg-stone-50 border border-stone-200/50 rounded-sm text-sm text-zinc-900 focus:outline-none focus:border-[#2563EB]/50 transition-all">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $property->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pricing --}}
            <div class="bg-stone-50 border border-stone-200/50 rounded-sm p-6">
                <h2 class="text-lg font-semibold text-zinc-900 mb-5 flex items-center gap-2">
                    <i class="ph ph-currency-inr text-[#2563EB]"></i> Pricing
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="edit-price" class="block text-sm font-medium text-zinc-500 mb-1.5">Rent Amount (₹) *</label>
                        <input type="number" name="price" id="edit-price" value="{{ old('price', $property->price) }}" required min="0" step="0.01"
                               class="w-full px-4 py-3 bg-stone-50 border border-stone-200/50 rounded-sm text-sm text-zinc-900 focus:outline-none focus:border-[#2563EB]/50 transition-all">
                    </div>
                    <div>
                        <label for="edit-period" class="block text-sm font-medium text-zinc-500 mb-1.5">Billing Period *</label>
                        <select name="price_period" id="edit-period" required
                                class="w-full px-4 py-3 bg-stone-50 border border-stone-200/50 rounded-sm text-sm text-zinc-900 focus:outline-none focus:border-[#2563EB]/50 transition-all">
                            <option value="month" {{ old('price_period', $property->price_period) === 'month' ? 'selected' : '' }}>Per Month</option>
                            <option value="year" {{ old('price_period', $property->price_period) === 'year' ? 'selected' : '' }}>Per Year</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Location --}}
            <div class="bg-stone-50 border border-stone-200/50 rounded-sm p-6">
                <h2 class="text-lg font-semibold text-zinc-900 mb-5 flex items-center gap-2">
                    <i class="ph ph-map-pin text-[#2563EB]"></i> Location
                </h2>

                <div class="space-y-4">
                    <div>
                        <label for="edit-state" class="block text-sm font-medium text-zinc-500 mb-1.5">State *</label>
                        <select name="state" id="edit-state" required
                                class="w-full px-4 py-3 bg-stone-50 border border-stone-200/50 rounded-sm text-sm text-zinc-900 focus:outline-none focus:border-[#2563EB]/50 transition-all">
                            <option value="">Select State</option>
                        </select>
                        @error('state') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="edit-city" class="block text-sm font-medium text-zinc-500 mb-1.5">City / District *</label>
                        <select name="location" id="edit-city" required
                                class="w-full px-4 py-3 bg-stone-50 border border-stone-200/50 rounded-sm text-sm text-zinc-900 focus:outline-none focus:border-[#2563EB]/50 transition-all">
                            <option value="">Select State First</option>
                        </select>
                        @error('location') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="edit-locality-select" class="block text-sm font-medium text-zinc-500 mb-1.5">Locality</label>
                        <div id="locality-select-wrap">
                            <select name="locality" id="edit-locality-select"
                                    class="w-full px-4 py-3 bg-stone-50 border border-stone-200/50 rounded-sm text-sm text-zinc-900 focus:outline-none focus:border-[#2563EB]/50 transition-all">
                                <option value="">Select City First</option>
                            </select>
                        </div>
                        <div id="locality-text-wrap" style="display: none;">
                            <input type="text" name="locality" id="edit-locality-text" value="{{ old('locality', $property->locality) }}" 
                                   class="w-full px-4 py-3 bg-stone-50 border border-stone-200/50 rounded-sm text-sm text-zinc-900 focus:outline-none focus:border-[#2563EB]/50 transition-all"
                                   placeholder="e.g., Carter Road">
                        </div>
                        @error('locality') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="edit-address" class="block text-sm font-medium text-zinc-500 mb-1.5">Full Address *</label>
                        <input type="text" name="address" id="edit-address" value="{{ old('address', $property->address) }}" required
                               class="w-full px-4 py-3 bg-stone-50 border border-stone-200/50 rounded-sm text-sm text-zinc-900 focus:outline-none focus:border-[#2563EB]/50 transition-all">
                        @error('address') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
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
                    });
                </script>
            </div>

            {{-- Property Details --}}
            <div class="bg-stone-50 border border-stone-200/50 rounded-sm p-6">
                <h2 class="text-lg font-semibold text-zinc-900 mb-5 flex items-center gap-2">
                    <i class="ph ph-house-line text-[#2563EB]"></i> Property Details
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="edit-bedrooms" class="block text-sm font-medium text-zinc-500 mb-1.5">Bedrooms</label>
                        <input type="number" name="bedrooms" id="edit-bedrooms" value="{{ old('bedrooms', $property->bedrooms) }}" min="0"
                               class="w-full px-4 py-3 bg-stone-50 border border-stone-200/50 rounded-sm text-sm text-zinc-900 focus:outline-none focus:border-[#2563EB]/50 transition-all">
                    </div>
                    <div>
                        <label for="edit-bathrooms" class="block text-sm font-medium text-zinc-500 mb-1.5">Bathrooms</label>
                        <input type="number" name="bathrooms" id="edit-bathrooms" value="{{ old('bathrooms', $property->bathrooms) }}" min="0"
                               class="w-full px-4 py-3 bg-stone-50 border border-stone-200/50 rounded-sm text-sm text-zinc-900 focus:outline-none focus:border-[#2563EB]/50 transition-all">
                    </div>
                    <div>
                        <label for="edit-area" class="block text-sm font-medium text-zinc-500 mb-1.5">Area (sq ft)</label>
                        <input type="number" name="area_sqft" id="edit-area" value="{{ old('area_sqft', $property->area_sqft) }}" min="0"
                               class="w-full px-4 py-3 bg-stone-50 border border-stone-200/50 rounded-sm text-sm text-zinc-900 focus:outline-none focus:border-[#2563EB]/50 transition-all">
                    </div>
                </div>
                <div class="mt-4">
                    <label for="edit-furnishing" class="block text-sm font-medium text-zinc-500 mb-1.5">Furnishing *</label>
                    <select name="furnishing" id="edit-furnishing" required
                            class="w-full px-4 py-3 bg-stone-50 border border-stone-200/50 rounded-sm text-sm text-zinc-900 focus:outline-none focus:border-[#2563EB]/50 transition-all">
                        <option value="unfurnished" {{ old('furnishing', $property->furnishing) === 'unfurnished' ? 'selected' : '' }}>Unfurnished</option>
                        <option value="semi-furnished" {{ old('furnishing', $property->furnishing) === 'semi-furnished' ? 'selected' : '' }}>Semi-Furnished</option>
                        <option value="fully-furnished" {{ old('furnishing', $property->furnishing) === 'fully-furnished' ? 'selected' : '' }}>Fully Furnished</option>
                    </select>
                </div>
            </div>

            {{-- Existing Images --}}
            @if($property->images->count() > 0)
            <div class="bg-stone-50 border border-stone-200/50 rounded-sm p-6">
                <h2 class="text-lg font-semibold text-zinc-900 mb-5 flex items-center gap-2">
                    <i class="ph ph-images text-[#2563EB]"></i> Current Images
                </h2>
                <div class="grid grid-cols-3 sm:grid-cols-4 gap-3">
                    @foreach($property->images as $image)
                    <div class="relative group">
                        <img src="{{ asset('storage/' . $image->path) }}" class="w-full h-24 object-cover rounded-sm border border-stone-200/50">
                        <label class="absolute inset-0 bg-red-500/0 group-hover:bg-red-500/40 rounded-sm flex items-center justify-center cursor-pointer transition-all">
                            <input type="checkbox" name="remove_images[]" value="{{ $image->id }}" class="sr-only peer">
                            <span class="hidden group-hover:flex peer-checked:flex items-center gap-1 text-xs text-zinc-900 font-medium bg-red-500 px-2 py-1 rounded-sm">
                                <i class="ph ph-trash"></i> Remove
                            </span>
                        </label>
                        @if($image->is_primary)
                        <span class="absolute top-1 left-1 px-1.5 py-0.5 bg-[#2563EB] text-white text-[10px] font-semibold rounded">Primary</span>
                        @endif
                    </div>
                    @endforeach
                </div>
                <p class="text-xs text-zinc-500 mt-2">Hover and click images to mark them for removal.</p>
            </div>
            @endif

            {{-- New Images --}}
            <div class="bg-stone-50 border border-stone-200/50 rounded-sm p-6">
                <h2 class="text-lg font-semibold text-zinc-900 mb-5 flex items-center gap-2">
                    <i class="ph ph-cloud-arrow-up text-[#2563EB]"></i> Add More Images
                </h2>
                <input type="file" name="images[]" multiple accept="image/*"
                       class="block w-full text-sm text-zinc-500 file:mr-4 file:py-2 file:px-4 file:rounded-sm file:border-0 file:text-sm file:font-medium file:bg-[#2563EB]/10 file:text-[#2563EB] hover:file:bg-[#2563EB]/10 cursor-pointer"
                       id="edit-images-input" onchange="checkMaxFiles(event)">
                <p class="text-xs text-zinc-500 mt-2">JPG, PNG, WebP — Max 2MB each. Up to 10 images total.</p>
            </div>

            <script>
            function checkMaxFiles(event) {
                if (event.target.files.length > 10) {
                    alert('You can only upload a maximum of 10 images at a time.');
                    event.target.value = '';
                }
            }
            </script>

            {{-- Submit --}}
            <div class="flex gap-4">
                <button type="submit" class="flex-1 px-8 py-4 bg-[#2563EB] hover:bg-[#1D4ED8] text-white text-sm font-semibold rounded-sm shadow-sm transition-all">
                    <i class="ph ph-floppy-disk"></i> Update Property
                </button>
                <a href="{{ route('dashboard') }}" class="px-6 py-4 bg-stone-50 border border-stone-200/50 text-zinc-500 text-sm font-medium rounded-sm hover:bg-stone-100 transition-all">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</section>

@endsection
