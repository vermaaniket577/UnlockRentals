@extends('layouts.admin')

@section('title', 'Manage Locations - Admin - UnlockRentals')

@section('content')
<section class="py-8 lg:py-12" id="admin-locations">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Page Header --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-medium text-zinc-900 mb-1">System Locations</h1>
                <p class="text-zinc-500">Filter and view states, districts, and localities stored in the database</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium transition-colors flex items-center gap-1">
                <i class="ph ph-arrow-left"></i> Dashboard
            </a>
        </div>

        {{-- Filters Section --}}
        <div class="bg-white border border-stone-200/50 rounded-xl p-6 shadow-sm mb-8">
            <form method="GET" action="{{ route('admin.locations') }}" class="grid grid-cols-1 md:grid-cols-2 gap-6 items-end" id="location-filter-form">
                
                {{-- State Select --}}
                <div class="space-y-2">
                    <label for="state_id" class="text-xs font-bold uppercase tracking-wider text-zinc-500 flex items-center gap-1.5">
                        <i class="ph ph-map-pin text-sm text-blue-600"></i> Select State
                    </label>
                    <select name="state_id" id="state_id" onchange="this.form.submit()" 
                            class="w-full px-4 py-3 bg-stone-50 border border-stone-200 rounded-lg text-sm text-zinc-900 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-600/15 focus:border-blue-600 transition-all font-medium">
                        <option value="">-- Choose State --</option>
                        @foreach($states as $state)
                            <option value="{{ $state->id }}" {{ $selectedStateId == $state->id ? 'selected' : '' }}>
                                {{ $state->name }} ({{ $state->code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- District Select --}}
                <div class="space-y-2">
                    <label for="district_id" class="text-xs font-bold uppercase tracking-wider text-zinc-500 flex items-center gap-1.5">
                        <i class="ph ph-compass text-sm text-blue-600"></i> Select District
                    </label>
                    <select name="district_id" id="district_id" onchange="this.form.submit()" {{ $districts->isEmpty() ? 'disabled' : '' }}
                            class="w-full px-4 py-3 bg-stone-50 border border-stone-200 rounded-lg text-sm text-zinc-900 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-600/15 focus:border-blue-600 transition-all font-medium disabled:opacity-50">
                        <option value="">-- Choose District --</option>
                        @foreach($districts as $district)
                            <option value="{{ $district->id }}" {{ $selectedDistrictId == $district->id ? 'selected' : '' }}>
                                {{ $district->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </form>
        </div>

        {{-- Display Localities List --}}
        @if($selectedDistrictId)
            <div class="bg-white border border-stone-200/50 rounded-xl overflow-hidden shadow-sm">
                <div class="px-6 py-4 border-b border-stone-100 flex items-center justify-between">
                    <h3 class="font-bold text-zinc-800 flex items-center gap-2">
                        <i class="ph ph-map-trifold text-lg text-blue-600"></i> Localities in selected District
                    </h3>
                    <span class="bg-blue-50 text-blue-700 text-xs font-bold px-2.5 py-1 rounded-md">
                        Total: {{ $localities->total() }}
                    </span>
                </div>

                @if($localities->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-stone-50 text-zinc-500 text-xs font-bold uppercase tracking-wider border-b border-stone-200/50">
                                    <th class="text-left px-6 py-3.5">ID</th>
                                    <th class="text-left px-6 py-3.5">Locality Name</th>
                                    <th class="text-left px-6 py-3.5">District</th>
                                    <th class="text-left px-6 py-3.5">State</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-100 text-sm text-zinc-700">
                                @foreach($localities as $locality)
                                    <tr class="hover:bg-stone-50/50 transition-colors">
                                        <td class="px-6 py-4 font-mono text-xs text-zinc-400 font-bold">#{{ $locality->id }}</td>
                                        <td class="px-6 py-4 font-semibold text-zinc-900">{{ $locality->name }}</td>
                                        <td class="px-6 py-4">{{ $locality->district->name }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-0.5 bg-stone-100 text-zinc-600 text-xs font-semibold rounded">
                                                {{ $locality->district->state->name }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="px-6 py-4 border-t border-stone-150">
                        {{ $localities->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-16">
                        <div class="w-12 h-12 rounded-full bg-stone-100 flex items-center justify-center mx-auto mb-3 text-zinc-400">
                            <i class="ph ph-magnifying-glass text-2xl"></i>
                        </div>
                        <h4 class="font-bold text-zinc-800">No localities found</h4>
                        <p class="text-zinc-500 text-xs mt-1">Try selecting another district or seeding location data.</p>
                    </div>
                @endif
            </div>
        @else
            <div class="text-center py-20 bg-white border border-stone-200/50 rounded-xl shadow-sm">
                <div class="w-16 h-16 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-4 shadow-sm">
                    <i class="ph ph-compass text-3xl"></i>
                </div>
                <h3 class="text-lg font-bold text-zinc-900">Choose state & district to view locations</h3>
                <p class="text-zinc-500 text-sm mt-1 max-w-sm mx-auto">Please select a state and district from the filters above to retrieve the database locality entries.</p>
            </div>
        @endif

    </div>
</section>
@endsection
