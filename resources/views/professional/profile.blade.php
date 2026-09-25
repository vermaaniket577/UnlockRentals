@extends('layouts.app')

@section('title', 'Edit Profile | Professional Dashboard | UnlockRentals')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-900 pb-20 pt-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <nav class="flex items-center text-xs text-slate-500 mb-6 gap-2">
            <a href="{{ route('professional.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
            <i class="ph ph-caret-right text-[10px]"></i>
            <span class="text-slate-900 dark:text-white font-medium">Edit Profile</span>
        </nav>

        <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-200 dark:border-slate-800">
            <div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white">Edit Professional Profile</h1>
                <p class="text-xs text-slate-500 mt-0.5">Keep your business details, contact information, and pricing updated</p>
            </div>
            <a href="{{ route('professional.dashboard') }}" class="px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-600 text-xs font-bold text-slate-700 dark:text-slate-200">
                Back to Dashboard
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 text-emerald-800 text-xs font-semibold flex items-center gap-2">
                <i class="ph-fill ph-check-circle text-base text-emerald-600"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <form action="{{ route('professional.profile.update') }}" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 border border-slate-200/80 dark:border-slate-700/80 shadow-sm space-y-6">
            @csrf

            <div class="flex items-center gap-5">
                <img src="{{ $professional->profile_photo_url }}" class="w-20 h-20 rounded-2xl object-cover border border-slate-200 dark:border-slate-700">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Update Profile Photo</label>
                    <input type="file" name="profile_photo" accept="image/*" class="text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Business Name *</label>
                    <input type="text" name="business_name" value="{{ old('business_name', $professional->business_name) }}" required class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Full Contact Name *</label>
                    <input type="text" name="full_name" value="{{ old('full_name', $professional->full_name) }}" required class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Calling Phone *</label>
                    <input type="text" name="phone" value="{{ old('phone', $professional->phone) }}" required class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">WhatsApp Number</label>
                    <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $professional->whatsapp_number) }}" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Email *</label>
                    <input type="email" name="email" value="{{ old('email', $professional->email) }}" required class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Years of Experience *</label>
                    <input type="number" name="years_experience" value="{{ old('years_experience', $professional->years_experience) }}" min="0" max="60" required class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Starting Price (₹)</label>
                    <input type="number" name="starting_price" value="{{ old('starting_price', $professional->starting_price) }}" min="0" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Price Model</label>
                    <select name="price_type" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
                        @foreach(['per_visit' => 'Per Visit', 'hourly' => 'Hourly', 'per_service' => 'Per Service', 'negotiable' => 'Negotiable', 'contact' => 'Contact for Price'] as $pk => $plabel)
                            <option value="{{ $pk }}" {{ $professional->price_type == $pk ? 'selected' : '' }}>{{ $plabel }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Professional Profile Description *</label>
                <textarea name="description" rows="4" required class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">{{ old('description', $professional->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">City *</label>
                    <input type="text" name="city" value="{{ old('city', $professional->city) }}" required class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Locality</label>
                    <input type="text" name="locality" value="{{ old('locality', $professional->locality) }}" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Pincode</label>
                    <input type="text" name="pincode" value="{{ old('pincode', $professional->pincode) }}" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Shop / Business Address</label>
                <input type="text" name="address" value="{{ old('address', $professional->address) }}" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
            </div>

            <div class="flex flex-wrap gap-6 pt-2">
                <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 dark:text-slate-300 cursor-pointer">
                    <input type="checkbox" name="home_visit" value="1" {{ $professional->home_visit ? 'checked' : '' }} class="rounded border-slate-300 text-blue-600">
                    <span>🏠 Home Visit Available</span>
                </label>
                <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 dark:text-slate-300 cursor-pointer">
                    <input type="checkbox" name="emergency_service" value="1" {{ $professional->emergency_service ? 'checked' : '' }} class="rounded border-slate-300 text-blue-600">
                    <span>🚨 24x7 Emergency Service</span>
                </label>
                <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 dark:text-slate-300 cursor-pointer">
                    <input type="checkbox" name="available_today" value="1" {{ $professional->available_today ? 'checked' : '' }} class="rounded border-slate-300 text-blue-600">
                    <span>⚡ Available Today</span>
                </label>
            </div>

            <button type="submit" class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-600/25 transition-all">
                Save Profile Changes
            </button>
        </form>

    </div>
</div>
@endsection
