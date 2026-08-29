@extends('layouts.admin')

@section('title', 'Manage Locations - Admin - UnlockRentals')

@section('content')
<section class="py-8 lg:py-10" id="admin-locations">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Top Header Bar --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-zinc-900 tracking-tight">Location Management</h1>
                <p class="text-xs sm:text-sm text-zinc-500 mt-0.5">Add, explore, and manage states, cities/districts, and neighborhood localities</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <button type="button" onclick="openModal('add-state-modal')" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-zinc-900 hover:bg-black text-white text-xs font-semibold rounded-lg shadow-sm transition-all">
                    <i class="ph-bold ph-plus"></i>
                    <span>Add State</span>
                </button>
                <button type="button" onclick="openModal('add-district-modal')" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg shadow-sm transition-all">
                    <i class="ph-bold ph-plus"></i>
                    <span>Add City / District</span>
                </button>
                <button type="button" onclick="openModal('add-locality-modal')" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg shadow-sm transition-all">
                    <i class="ph-bold ph-plus"></i>
                    <span>Add Locality</span>
                </button>
            </div>
        </div>

        {{-- Overview Stats Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            
            <div class="bg-white border border-stone-200/80 rounded-2xl p-5 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-zinc-500 uppercase tracking-wider">States</span>
                    <p class="text-2xl font-extrabold text-zinc-900 mt-0.5">{{ $totalStates }}</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                    <i class="ph-bold ph-map-trifold"></i>
                </div>
            </div>

            <div class="bg-white border border-stone-200/80 rounded-2xl p-5 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-zinc-500 uppercase tracking-wider">Cities / Districts</span>
                    <p class="text-2xl font-extrabold text-zinc-900 mt-0.5">{{ $totalDistricts }}</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl">
                    <i class="ph-bold ph-buildings"></i>
                </div>
            </div>

            <div class="bg-white border border-stone-200/80 rounded-2xl p-5 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-zinc-500 uppercase tracking-wider">Localities / Areas</span>
                    <p class="text-2xl font-extrabold text-zinc-900 mt-0.5">{{ $totalLocalities }}</p>
                </div>
                <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                    <i class="ph-bold ph-map-pin"></i>
                </div>
            </div>

        </div>

        {{-- Drilldown & Filter Section --}}
        <div class="bg-white border border-stone-200/80 rounded-2xl p-6 shadow-xs">
            <div class="flex items-center justify-between gap-4 mb-4">
                <div class="flex items-center gap-2">
                    <i class="ph-bold ph-funnel text-blue-600 text-base"></i>
                    <h2 class="text-sm font-bold text-zinc-900 uppercase tracking-wider">Filter Localities by Region</h2>
                </div>
                @if($selectedStateId || $selectedDistrictId)
                    <a href="{{ route('admin.locations') }}" class="text-xs text-blue-600 hover:underline font-medium">Reset Filters</a>
                @endif
            </div>

            <form method="GET" action="{{ route('admin.locations') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4" id="location-filter-form">
                
                {{-- State Select --}}
                <div class="space-y-1.5">
                    <label for="state_id" class="text-xs font-bold text-zinc-600 uppercase tracking-wider flex items-center gap-1.5">
                        <i class="ph-bold ph-map-pin text-blue-600"></i> Select State
                    </label>
                    <select name="state_id" id="state_id" onchange="this.form.submit()" 
                            class="w-full px-3.5 py-2.5 bg-stone-50 border border-stone-200 rounded-xl text-xs sm:text-sm text-zinc-900 font-semibold focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition-all">
                        <option value="">-- All States ({{ $states->count() }}) --</option>
                        @foreach($states as $state)
                            <option value="{{ $state->id }}" {{ $selectedStateId == $state->id ? 'selected' : '' }}>
                                {{ $state->name }} ({{ $state->code }}) — {{ $state->districts_count }} cities
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- District Select --}}
                <div class="space-y-1.5">
                    <label for="district_id" class="text-xs font-bold text-zinc-600 uppercase tracking-wider flex items-center gap-1.5">
                        <i class="ph-bold ph-compass text-blue-600"></i> Select City / District
                    </label>
                    <select name="district_id" id="district_id" onchange="this.form.submit()" {{ $districts->isEmpty() ? 'disabled' : '' }}
                            class="w-full px-3.5 py-2.5 bg-stone-50 border border-stone-200 rounded-xl text-xs sm:text-sm text-zinc-900 font-semibold focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 transition-all disabled:opacity-50">
                        <option value="">-- {{ $selectedStateId ? 'Choose City / District' : 'Select a State First' }} --</option>
                        @foreach($districts as $district)
                            <option value="{{ $district->id }}" {{ $selectedDistrictId == $district->id ? 'selected' : '' }}>
                                {{ $district->name }} ({{ $district->localities_count }} localities)
                            </option>
                        @endforeach
                    </select>
                </div>

            </form>
        </div>

        {{-- Display Localities List for Selected District --}}
        @if($selectedDistrictId)
            <div class="bg-white border border-stone-200/80 rounded-2xl overflow-hidden shadow-xs">
                <div class="px-6 py-4 border-b border-stone-150 flex flex-wrap items-center justify-between gap-3 bg-stone-50/50">
                    <div class="flex items-center gap-2">
                        <i class="ph-bold ph-map-pin text-emerald-600 text-lg"></i>
                        <h3 class="font-bold text-zinc-900 text-sm">
                            Localities in Selected City
                        </h3>
                        <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold px-2.5 py-0.5 rounded-full">
                            Total: {{ $localities->total() }}
                        </span>
                    </div>

                    <button type="button" onclick="openLocalityModalWithDistrict({{ $selectedDistrictId }})" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg transition-all">
                        <i class="ph-bold ph-plus"></i>
                        <span>Add Locality to this City</span>
                    </button>
                </div>

                @if($localities->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-stone-50 text-zinc-500 text-xs font-bold uppercase tracking-wider border-b border-stone-200/80">
                                    <th class="px-6 py-3.5">ID</th>
                                    <th class="px-6 py-3.5">Locality Name</th>
                                    <th class="px-6 py-3.5">City / District</th>
                                    <th class="px-6 py-3.5">State</th>
                                    <th class="px-6 py-3.5 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-100 text-sm text-zinc-700">
                                @foreach($localities as $locality)
                                    <tr class="hover:bg-stone-50/60 transition-colors">
                                        <td class="px-6 py-3.5 font-mono text-xs text-zinc-400 font-bold">#{{ $locality->id }}</td>
                                        <td class="px-6 py-3.5 font-bold text-zinc-900">{{ $locality->name }}</td>
                                        <td class="px-6 py-3.5 font-medium text-zinc-700">{{ $locality->district->name }}</td>
                                        <td class="px-6 py-3.5">
                                            <span class="px-2.5 py-1 bg-stone-100 text-zinc-700 text-xs font-semibold rounded-md border border-stone-200">
                                                {{ $locality->district->state->name }} ({{ $locality->district->state->code }})
                                            </span>
                                        </td>
                                        <td class="px-6 py-3.5 text-right">
                                            <form method="POST" action="{{ route('admin.locations.localities.destroy', $locality) }}" onsubmit="return confirm('Delete locality \'{{ $locality->name }}\'?')" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 text-zinc-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Delete Locality">
                                                    <i class="ph-bold ph-trash text-sm"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="px-6 py-4 border-t border-stone-150 bg-stone-50/30">
                        {{ $localities->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-14 px-4">
                        <div class="w-12 h-12 rounded-2xl bg-stone-100 flex items-center justify-center mx-auto mb-3 text-zinc-400">
                            <i class="ph-bold ph-magnifying-glass text-xl"></i>
                        </div>
                        <h4 class="font-bold text-zinc-800 text-sm">No localities in this district yet</h4>
                        <p class="text-zinc-500 text-xs mt-1 max-w-sm mx-auto">Click the button below to add your first locality/sector for this city.</p>
                        <button type="button" onclick="openLocalityModalWithDistrict({{ $selectedDistrictId }})" class="mt-4 inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 text-white text-xs font-bold rounded-lg shadow-sm">
                            <i class="ph-bold ph-plus"></i> Add Locality Now
                        </button>
                    </div>
                @endif
            </div>
        @elseif($selectedStateId && $districts->count() > 0)
            {{-- Display Cities in Selected State --}}
            <div class="bg-white border border-stone-200/80 rounded-2xl overflow-hidden shadow-xs">
                <div class="px-6 py-4 border-b border-stone-150 flex items-center justify-between bg-stone-50/50">
                    <h3 class="font-bold text-zinc-900 text-sm flex items-center gap-2">
                        <i class="ph-bold ph-buildings text-indigo-600"></i>
                        Cities / Districts in Selected State ({{ $districts->count() }})
                    </h3>
                    <button type="button" onclick="openDistrictModalWithState({{ $selectedStateId }})" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg transition-all">
                        <i class="ph-bold ph-plus"></i>
                        <span>Add City to this State</span>
                    </button>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 p-6">
                    @foreach($districts as $district)
                        <div class="p-3.5 bg-stone-50 border border-stone-200/80 rounded-xl flex items-center justify-between gap-2 hover:bg-blue-50/40 hover:border-blue-200 transition-all">
                            <a href="{{ route('admin.locations', ['state_id' => $selectedStateId, 'district_id' => $district->id]) }}" class="min-w-0 flex-1">
                                <p class="text-sm font-bold text-zinc-900 truncate hover:text-blue-600">{{ $district->name }}</p>
                                <p class="text-xs text-zinc-500 mt-0.5">{{ $district->localities_count }} localities →</p>
                            </a>
                            <form method="POST" action="{{ route('admin.locations.districts.destroy', $district) }}" onsubmit="return confirm('Delete city \'{{ $district->name }}\' and all its localities?')" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1 text-zinc-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Delete City">
                                    <i class="ph-bold ph-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            {{-- Default States Explorer --}}
            <div class="bg-white border border-stone-200/80 rounded-2xl p-6 shadow-xs">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-zinc-900 text-sm flex items-center gap-2">
                        <i class="ph-bold ph-map-trifold text-blue-600"></i>
                        Browse All States ({{ $states->count() }})
                    </h3>
                    <button type="button" onclick="openModal('add-state-modal')" class="text-xs font-bold text-blue-600 hover:underline flex items-center gap-1">
                        <i class="ph-bold ph-plus"></i> Add New State
                    </button>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
                    @foreach($states as $st)
                        <div class="p-3 bg-stone-50 border border-stone-200/80 rounded-xl hover:border-blue-300 hover:bg-blue-50/30 transition-all flex flex-col justify-between">
                            <a href="{{ route('admin.locations', ['state_id' => $st->id]) }}" class="block">
                                <span class="text-[10px] font-extrabold px-1.5 py-0.5 bg-blue-100 text-blue-800 rounded">{{ $st->code }}</span>
                                <p class="text-xs font-bold text-zinc-900 mt-1.5 truncate" title="{{ $st->name }}">{{ $st->name }}</p>
                                <p class="text-[11px] text-zinc-500 mt-0.5">{{ $st->districts_count }} cities</p>
                            </a>
                            <div class="mt-2 pt-2 border-t border-stone-200/60 flex justify-end">
                                <form method="POST" action="{{ route('admin.locations.states.destroy', $st) }}" onsubmit="return confirm('Delete state \'{{ $st->name }}\' and all its cities/localities?')" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-zinc-400 hover:text-red-600 p-0.5" title="Delete State">
                                        <i class="ph-bold ph-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</section>

{{-- =========================================================================
     MODAL 1: ADD STATE
     ========================================================================= --}}
<div id="add-state-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-xs p-4 hidden">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-stone-200 relative animate-in fade-in zoom-in-95 duration-150">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-stone-150">
            <h3 class="text-base font-bold text-zinc-900 flex items-center gap-2">
                <i class="ph-bold ph-map-trifold text-zinc-900"></i> Add New State
            </h3>
            <button onclick="closeModal('add-state-modal')" class="text-zinc-400 hover:text-zinc-700"><i class="ph-bold ph-x text-lg"></i></button>
        </div>
        <form method="POST" action="{{ route('admin.locations.states.store') }}" class="space-y-4">
            @csrf
            <div>
                <label for="state_name" class="block text-xs font-bold uppercase tracking-wider text-zinc-600 mb-1">State Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="state_name" required placeholder="e.g. Haryana, Delhi, Karnataka" 
                       class="w-full px-3.5 py-2.5 border border-stone-200 rounded-xl text-sm font-semibold text-zinc-900 focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 outline-none">
            </div>
            <div>
                <label for="state_code" class="block text-xs font-bold uppercase tracking-wider text-zinc-600 mb-1">State Code (2-4 Letters) <span class="text-red-500">*</span></label>
                <input type="text" name="code" id="state_code" required maxlength="5" placeholder="e.g. HR, DL, KA, MH" 
                       class="w-full px-3.5 py-2.5 border border-stone-200 rounded-xl text-sm font-semibold text-zinc-900 uppercase focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 outline-none">
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeModal('add-state-modal')" class="px-4 py-2 bg-stone-100 hover:bg-stone-200 text-zinc-700 text-xs font-bold rounded-xl">Cancel</button>
                <button type="submit" class="px-5 py-2 bg-zinc-900 hover:bg-black text-white text-xs font-bold rounded-xl shadow-sm">Save State</button>
            </div>
        </form>
    </div>
</div>

{{-- =========================================================================
     MODAL 2: ADD CITY / DISTRICT
     ========================================================================= --}}
<div id="add-district-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-xs p-4 hidden">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-stone-200 relative animate-in fade-in zoom-in-95 duration-150">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-stone-150">
            <h3 class="text-base font-bold text-zinc-900 flex items-center gap-2">
                <i class="ph-bold ph-buildings text-blue-600"></i> Add New City / District
            </h3>
            <button onclick="closeModal('add-district-modal')" class="text-zinc-400 hover:text-zinc-700"><i class="ph-bold ph-x text-lg"></i></button>
        </div>
        <form method="POST" action="{{ route('admin.locations.districts.store') }}" class="space-y-4">
            @csrf
            <div>
                <label for="district_state_id" class="block text-xs font-bold uppercase tracking-wider text-zinc-600 mb-1">State <span class="text-red-500">*</span></label>
                <select name="state_id" id="district_state_id" required 
                        class="w-full px-3.5 py-2.5 border border-stone-200 rounded-xl text-sm font-semibold text-zinc-900 focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 outline-none">
                    <option value="">-- Select State --</option>
                    @foreach($states as $st)
                        <option value="{{ $st->id }}" {{ $selectedStateId == $st->id ? 'selected' : '' }}>{{ $st->name }} ({{ $st->code }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="district_name" class="block text-xs font-bold uppercase tracking-wider text-zinc-600 mb-1">City / District Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="district_name" required placeholder="e.g. Gurugram, Faridabad, South Delhi" 
                       class="w-full px-3.5 py-2.5 border border-stone-200 rounded-xl text-sm font-semibold text-zinc-900 focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 outline-none">
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeModal('add-district-modal')" class="px-4 py-2 bg-stone-100 hover:bg-stone-200 text-zinc-700 text-xs font-bold rounded-xl">Cancel</button>
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-sm">Save City / District</button>
            </div>
        </form>
    </div>
</div>

{{-- =========================================================================
     MODAL 3: ADD LOCALITY
     ========================================================================= --}}
<div id="add-locality-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-xs p-4 hidden">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-stone-200 relative animate-in fade-in zoom-in-95 duration-150">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-stone-150">
            <h3 class="text-base font-bold text-zinc-900 flex items-center gap-2">
                <i class="ph-bold ph-map-pin text-emerald-600"></i> Add New Locality / Sector
            </h3>
            <button onclick="closeModal('add-locality-modal')" class="text-zinc-400 hover:text-zinc-700"><i class="ph-bold ph-x text-lg"></i></button>
        </div>
        <form method="POST" action="{{ route('admin.locations.localities.store') }}" class="space-y-4">
            @csrf
            <div>
                <label for="locality_district_id" class="block text-xs font-bold uppercase tracking-wider text-zinc-600 mb-1">City / District <span class="text-red-500">*</span></label>
                <select name="district_id" id="locality_district_id" required 
                        class="w-full px-3.5 py-2.5 border border-stone-200 rounded-xl text-sm font-semibold text-zinc-900 focus:ring-2 focus:ring-emerald-600/20 focus:border-emerald-600 outline-none">
                    <option value="">-- Select City / District --</option>
                    @foreach($allDistricts as $dist)
                        <option value="{{ $dist->id }}" {{ $selectedDistrictId == $dist->id ? 'selected' : '' }}>
                            {{ $dist->name }} ({{ optional($dist->state)->code ?? '' }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="locality_name" class="block text-xs font-bold uppercase tracking-wider text-zinc-600 mb-1">Locality / Sector Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="locality_name" required placeholder="e.g. Sector 57, DLF Phase 5, Connaught Place" 
                       class="w-full px-3.5 py-2.5 border border-stone-200 rounded-xl text-sm font-semibold text-zinc-900 focus:ring-2 focus:ring-emerald-600/20 focus:border-emerald-600 outline-none">
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeModal('add-locality-modal')" class="px-4 py-2 bg-stone-100 hover:bg-stone-200 text-zinc-700 text-xs font-bold rounded-xl">Cancel</button>
                <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm">Save Locality</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id) {
    const modal = document.getElementById(id);
    if (modal) modal.classList.remove('hidden');
}

function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) modal.classList.add('hidden');
}

function openDistrictModalWithState(stateId) {
    const select = document.getElementById('district_state_id');
    if (select) select.value = stateId;
    openModal('add-district-modal');
}

function openLocalityModalWithDistrict(districtId) {
    const select = document.getElementById('locality_district_id');
    if (select) select.value = districtId;
    openModal('add-locality-modal');
}
</script>
@endsection
