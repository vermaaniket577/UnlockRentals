@extends('layouts.app')

@section('title', 'Service Areas | Professional Dashboard | UnlockRentals')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-900 pb-20 pt-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <nav class="flex items-center text-xs text-slate-500 mb-6 gap-2">
            <a href="{{ route('professional.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
            <i class="ph ph-caret-right text-[10px]"></i>
            <span class="text-slate-900 dark:text-white font-medium">Service Areas</span>
        </nav>

        <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-200 dark:border-slate-800">
            <div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white">Service Areas & Localities</h1>
                <p class="text-xs text-slate-500 mt-0.5">Specify which cities, localities, and sectors you travel to for service</p>
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

        {{-- Add New Area Form --}}
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 border border-slate-200/80 dark:border-slate-700/80 shadow-sm mb-8">
            <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                <i class="ph-bold ph-plus-circle text-blue-600"></i>
                Add New Service Area
            </h3>

            <form action="{{ route('professional.locations.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-4 gap-3 items-end">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">City *</label>
                    <input type="text" name="city" value="{{ $professional->city }}" required class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Locality / Sector</label>
                    <input type="text" name="locality" placeholder="e.g. Sector 29" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Pincode</label>
                    <input type="text" name="pincode" placeholder="e.g. 122002" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
                </div>
                <div class="flex items-center gap-2">
                    <div class="flex-1">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Radius</label>
                        <select name="service_radius_km" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
                            <option value="5">5 km</option>
                            <option value="10">10 km</option>
                            <option value="20" selected>20 km</option>
                            <option value="50">50 km</option>
                        </select>
                    </div>
                    <button type="submit" class="py-2.5 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs whitespace-nowrap">
                        Add Area
                    </button>
                </div>
            </form>
        </div>

        {{-- Existing Service Areas List --}}
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 border border-slate-200/80 dark:border-slate-700/80 shadow-sm">
            <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-4">Current Covered Areas ({{ $locations->count() }})</h3>

            <div class="space-y-3">
                @foreach($locations as $loc)
                    <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-700/40 border border-slate-200/60 dark:border-slate-700 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-blue-100 dark:bg-blue-900/40 text-blue-600 flex items-center justify-center">
                                <i class="ph-bold ph-map-pin"></i>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-slate-900 dark:text-white">
                                    {{ $loc->locality ? $loc->locality . ', ' : '' }}{{ $loc->city }}
                                </span>
                                <span class="text-[11px] text-slate-500 block">Radius: {{ $loc->service_radius_km ?? 20 }} km {{ $loc->pincode ? '• Pincode: ' . $loc->pincode : '' }}</span>
                            </div>
                        </div>

                        @if($locations->count() > 1)
                            <form action="{{ route('professional.locations.destroy', $loc->id) }}" method="POST" onsubmit="return confirm('Remove this service area?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 rounded-lg text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-colors">
                                    <i class="ph-bold ph-trash"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
@endsection
