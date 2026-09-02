@php
    $idPrefix = $idPrefix ?? '';
@endphp
<aside class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-sm sticky top-28" id="{{ $idPrefix }}search-filters">
    
    {{-- Header --}}
    <div class="flex items-center justify-between pb-4 mb-6 border-b border-slate-100 dark:border-slate-800">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-950/50 text-blue-600 flex items-center justify-center">
                <i class="ph-bold ph-faders-horizontal text-base"></i>
            </div>
            <h3 class="text-base font-bold text-slate-900 dark:text-white">Refine Search</h3>
        </div>
        @if(request()->anyFilled(['search', 'type', 'state', 'district', 'locality', 'min_price', 'max_price', 'bedrooms', 'sort']))
            <a href="{{ route('properties.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 hover:underline" title="Clear Filters">
                Clear all
            </a>
        @else
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Filters</span>
        @endif
    </div>

    <form method="GET" action="{{ route('properties.index') }}" id="{{ $idPrefix }}filter-form" class="space-y-6">
        
        {{-- Keyword Search --}}
        <div>
            <label for="{{ $idPrefix }}filter-search" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Keyword Search</label>
            <div class="relative">
                <i class="ph-bold ph-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text" name="search" id="{{ $idPrefix }}filter-search" value="{{ request('search') }}"
                       placeholder="e.g. 2 BHK, Villa, Studio..."
                       class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-medium text-slate-900 dark:text-white placeholder-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
            </div>
        </div>

        {{-- Property Type Selector --}}
        <div>
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2.5">Property Type</label>
            <div class="grid grid-cols-2 gap-2">
                @php
                    $types = [
                        '' => ['label' => 'All Types', 'icon' => 'ph-bold ph-squares-four'],
                        'house' => ['label' => 'House/Flat', 'icon' => 'ph-bold ph-house-line'],
                        'shop' => ['label' => 'Shop/Office', 'icon' => 'ph-bold ph-storefront'],
                        'pg-hostel' => ['label' => 'PG / Hostel', 'icon' => 'ph-bold ph-buildings'],
                    ];
                @endphp
                @foreach($types as $val => $info)
                <label class="relative flex items-center gap-2 px-3 py-2.5 rounded-xl border cursor-pointer transition-all {{ request('type') == $val ? 'border-blue-600 bg-blue-50/70 dark:bg-blue-950/40 text-blue-700 dark:text-blue-400 font-bold ring-1 ring-blue-600' : 'border-slate-200 dark:border-slate-700/80 bg-slate-50/50 dark:bg-slate-800/40 hover:border-slate-300 text-slate-600 dark:text-slate-400 font-medium' }}" for="{{ $idPrefix }}filter-type-{{ $val ?: 'all' }}">
                    <input type="radio" name="type" value="{{ $val }}" id="{{ $idPrefix }}filter-type-{{ $val ?: 'all' }}" {{ request('type') == $val ? 'checked' : '' }} class="sr-only">
                    <i class="{{ $info['icon'] }} text-base {{ request('type') == $val ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span class="text-xs">{{ $info['label'] }}</span>
                </label>
                @endforeach
            </div>
        </div>

        {{-- Location Cascading Selects --}}
        <div class="space-y-3.5 pt-1 border-t border-slate-100 dark:border-slate-800">
            <div>
                <label for="{{ $idPrefix }}filter-state" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">State</label>
                <div class="relative">
                    <select name="state" id="{{ $idPrefix }}filter-state"
                            onchange="if(window.handleLocationStateChange) window.handleLocationStateChange(this, '{{ $idPrefix }}filter-city', '{{ $idPrefix }}filter-locality-select');"
                            class="w-full pl-3.5 pr-8 py-2.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-medium text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all appearance-none cursor-pointer">
                        <option value="">All States</option>
                        @php $statesList = $globalAllStates ?? $allStates ?? []; @endphp
                        @if(!empty($statesList) && count($statesList) > 0)
                            @foreach($statesList as $code => $name)
                                <option value="{{ $code }}" {{ request('state') == $code ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        @else
                            <option value="AP" {{ request('state') == 'AP' ? 'selected' : '' }}>Andhra Pradesh</option>
                            <option value="AR" {{ request('state') == 'AR' ? 'selected' : '' }}>Arunachal Pradesh</option>
                            <option value="AS" {{ request('state') == 'AS' ? 'selected' : '' }}>Assam</option>
                            <option value="BR" {{ request('state') == 'BR' ? 'selected' : '' }}>Bihar</option>
                            <option value="CT" {{ request('state') == 'CT' ? 'selected' : '' }}>Chhattisgarh</option>
                            <option value="GA" {{ request('state') == 'GA' ? 'selected' : '' }}>Goa</option>
                            <option value="GJ" {{ request('state') == 'GJ' ? 'selected' : '' }}>Gujarat</option>
                            <option value="HR" {{ request('state') == 'HR' ? 'selected' : '' }}>Haryana</option>
                            <option value="HP" {{ request('state') == 'HP' ? 'selected' : '' }}>Himachal Pradesh</option>
                            <option value="JH" {{ request('state') == 'JH' ? 'selected' : '' }}>Jharkhand</option>
                            <option value="KA" {{ request('state') == 'KA' ? 'selected' : '' }}>Karnataka</option>
                            <option value="KL" {{ request('state') == 'KL' ? 'selected' : '' }}>Kerala</option>
                            <option value="MP" {{ request('state') == 'MP' ? 'selected' : '' }}>Madhya Pradesh</option>
                            <option value="MH" {{ request('state') == 'MH' ? 'selected' : '' }}>Maharashtra</option>
                            <option value="MN" {{ request('state') == 'MN' ? 'selected' : '' }}>Manipur</option>
                            <option value="ML" {{ request('state') == 'ML' ? 'selected' : '' }}>Meghalaya</option>
                            <option value="MZ" {{ request('state') == 'MZ' ? 'selected' : '' }}>Mizoram</option>
                            <option value="NL" {{ request('state') == 'NL' ? 'selected' : '' }}>Nagaland</option>
                            <option value="OR" {{ request('state') == 'OR' ? 'selected' : '' }}>Odisha</option>
                            <option value="PB" {{ request('state') == 'PB' ? 'selected' : '' }}>Punjab</option>
                            <option value="RJ" {{ request('state') == 'RJ' ? 'selected' : '' }}>Rajasthan</option>
                            <option value="SK" {{ request('state') == 'SK' ? 'selected' : '' }}>Sikkim</option>
                            <option value="TN" {{ request('state') == 'TN' ? 'selected' : '' }}>Tamil Nadu</option>
                            <option value="TS" {{ request('state') == 'TS' ? 'selected' : '' }}>Telangana</option>
                            <option value="TR" {{ request('state') == 'TR' ? 'selected' : '' }}>Tripura</option>
                            <option value="UP" {{ request('state') == 'UP' ? 'selected' : '' }}>Uttar Pradesh</option>
                            <option value="UK" {{ request('state') == 'UK' ? 'selected' : '' }}>Uttarakhand</option>
                            <option value="WB" {{ request('state') == 'WB' ? 'selected' : '' }}>West Bengal</option>
                            <option value="DL" {{ request('state') == 'DL' ? 'selected' : '' }}>Delhi</option>
                            <option value="CH" {{ request('state') == 'CH' ? 'selected' : '' }}>Chandigarh</option>
                        @endif
                    </select>
                    <i class="ph-bold ph-caret-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                </div>
            </div>

            <div>
                <label for="{{ $idPrefix }}filter-city" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">City / District</label>
                <div class="relative">
                    <select name="district" id="{{ $idPrefix }}filter-city"
                            onchange="if(window.handleLocationCityChange) window.handleLocationCityChange(this, '{{ $idPrefix }}filter-locality-select', '{{ $idPrefix }}filter-state');"
                            class="w-full pl-3.5 pr-8 py-2.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-medium text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all appearance-none cursor-pointer">
                        <option value="">All Cities / Districts</option>
                        @php $districtsList = $globalAllDistricts ?? $allDistricts ?? []; @endphp
                        @if(!empty($districtsList) && count($districtsList) > 0)
                            @foreach($districtsList as $d)
                                @php
                                    $dSlug = $d['slug'] ?? strtolower(str_replace(' ', '-', $d['name']));
                                    $isSelected = (request('district') === $dSlug || request('district') === $d['name']);
                                @endphp
                                <option value="{{ $d['name'] }}" {{ $isSelected ? 'selected' : '' }}>
                                    {{ $d['name'] }}{{ !empty($d['state_code']) ? ' (' . $d['state_code'] . ')' : '' }}
                                </option>
                            @endforeach
                        @else
                            <option value="Gurugram" {{ request('district') == 'Gurugram' ? 'selected' : '' }}>Gurugram (HR)</option>
                            <option value="New Delhi" {{ request('district') == 'New Delhi' ? 'selected' : '' }}>New Delhi (DL)</option>
                            <option value="South Delhi" {{ request('district') == 'South Delhi' ? 'selected' : '' }}>South Delhi (DL)</option>
                            <option value="Noida" {{ request('district') == 'Noida' ? 'selected' : '' }}>Noida (UP)</option>
                            <option value="Ghaziabad" {{ request('district') == 'Ghaziabad' ? 'selected' : '' }}>Ghaziabad (UP)</option>
                            <option value="Faridabad" {{ request('district') == 'Faridabad' ? 'selected' : '' }}>Faridabad (HR)</option>
                            <option value="Bengaluru" {{ request('district') == 'Bengaluru' ? 'selected' : '' }}>Bengaluru (KA)</option>
                            <option value="Mumbai" {{ request('district') == 'Mumbai' ? 'selected' : '' }}>Mumbai (MH)</option>
                            <option value="Pune" {{ request('district') == 'Pune' ? 'selected' : '' }}>Pune (MH)</option>
                            <option value="Hyderabad" {{ request('district') == 'Hyderabad' ? 'selected' : '' }}>Hyderabad (TS)</option>
                            <option value="Jaipur" {{ request('district') == 'Jaipur' ? 'selected' : '' }}>Jaipur (RJ)</option>
                            <option value="Chandigarh" {{ request('district') == 'Chandigarh' ? 'selected' : '' }}>Chandigarh (CH)</option>
                        @endif
                    </select>
                    <i class="ph-bold ph-caret-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                </div>
            </div>

            <div>
                <label for="{{ $idPrefix }}filter-locality-select" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Locality</label>
                <div id="{{ $idPrefix }}locality-select-wrap">
                    <div class="relative">
                        <select name="locality" id="{{ $idPrefix }}filter-locality-select"
                                class="w-full pl-3.5 pr-8 py-2.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-medium text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all appearance-none cursor-pointer">
                            <option value="">{{ request('locality') ? request('locality') : 'All Localities' }}</option>
                            @if(request('locality'))
                                <option value="{{ request('locality') }}" selected>{{ request('locality') }}</option>
                            @endif
                        </select>
                        <i class="ph-bold ph-caret-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                    </div>
                </div>
                <div id="{{ $idPrefix }}locality-text-wrap" style="display: none;">
                    <div class="relative">
                        <i class="ph-bold ph-map-pin absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" name="locality" id="{{ $idPrefix }}filter-locality-text" value="{{ request('locality') }}"
                               placeholder="e.g. Sector 57, Indiranagar"
                               class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-medium text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                    </div>
                </div>
            </div>
        </div>

        {{-- Price / Budget Range --}}
        <div class="pt-1 border-t border-slate-100 dark:border-slate-800">
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Monthly Budget (₹)</label>
            <div class="flex items-center gap-2">
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 font-bold text-xs">₹</span>
                    <input type="number" name="min_price" value="{{ request('min_price') }}"
                           placeholder="Min"
                           class="w-full pl-7 pr-2.5 py-2.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all"
                           id="{{ $idPrefix }}filter-min-price">
                </div>
                <span class="text-slate-300 font-bold text-xs">-</span>
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 font-bold text-xs">₹</span>
                    <input type="number" name="max_price" value="{{ request('max_price') }}"
                           placeholder="Max"
                           class="w-full pl-7 pr-2.5 py-2.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all"
                           id="{{ $idPrefix }}filter-max-price">
                </div>
            </div>
        </div>

        {{-- Bedrooms & Sort Row --}}
        <div class="grid grid-cols-2 gap-3 pt-1 border-t border-slate-100 dark:border-slate-800">
            <div>
                <label for="{{ $idPrefix }}filter-bedrooms" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Beds</label>
                <div class="relative">
                    <select name="bedrooms" id="{{ $idPrefix }}filter-bedrooms"
                            class="w-full pl-3 pr-7 py-2.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 transition-all appearance-none cursor-pointer">
                        <option value="">Any BHK</option>
                        @for($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" {{ request('bedrooms') == $i ? 'selected' : '' }}>{{ $i }} BHK</option>
                        @endfor
                    </select>
                    <i class="ph-bold ph-caret-down absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                </div>
            </div>
            <div>
                <label for="{{ $idPrefix }}filter-sort" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Sort By</label>
                <div class="relative">
                    <select name="sort" id="{{ $idPrefix }}filter-sort"
                            class="w-full pl-3 pr-7 py-2.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 transition-all appearance-none cursor-pointer">
                        <option value="new_to_old" {{ request('sort') === 'new_to_old' || request('sort') === 'latest' || !request('sort') ? 'selected' : '' }}>New to Old</option>
                        <option value="old_to_new" {{ request('sort') === 'old_to_new' || request('sort') === 'oldest' ? 'selected' : '' }}>Old to New</option>
                        <option value="unbooked" {{ request('sort') === 'unbooked' ? 'selected' : '' }}>Unbooked First</option>
                        <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                    </select>
                    <i class="ph-bold ph-caret-down absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                </div>
            </div>
        </div>

        {{-- Availability & Media Filters --}}
        <div class="space-y-3 pt-1 border-t border-slate-100 dark:border-slate-800">
            <div>
                <label for="{{ $idPrefix }}filter-media" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Media</label>
                <div class="relative">
                    <select name="media" id="{{ $idPrefix }}filter-media"
                            class="w-full pl-3 pr-7 py-2.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 transition-all appearance-none cursor-pointer">
                        <option value="" {{ !request('media') ? 'selected' : '' }}>All Listings</option>
                        <option value="images" {{ request('media') === 'images' ? 'selected' : '' }}>With Photos</option>
                        <option value="video" {{ request('media') === 'video' ? 'selected' : '' }}>With Video Tour</option>
                    </select>
                    <i class="ph-bold ph-caret-down absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                </div>
            </div>

            <label class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/40 hover:bg-slate-50 cursor-pointer transition-all text-xs font-semibold text-slate-700 dark:text-slate-300">
                <input type="checkbox" name="availability" value="unbooked" {{ request('availability') === 'unbooked' || request('unbooked') ? 'checked' : '' }} class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                <span class="flex items-center gap-1.5">
                    <i class="ph-bold ph-lock-key-open text-emerald-500 text-sm"></i>
                    Unbooked Only
                </span>
            </label>
        </div>

        {{-- Action Buttons --}}
        <div class="pt-2 flex flex-col gap-2.5">
            <button type="submit" class="w-full py-3 bg-[#2563EB] hover:bg-blue-700 text-white text-xs font-extrabold uppercase tracking-wider rounded-xl shadow-sm shadow-blue-500/25 hover:shadow-md transition-all active:scale-[0.98] flex items-center justify-center gap-2" id="{{ $idPrefix }}filter-apply">
                <i class="ph-bold ph-magnifying-glass"></i> Apply Filters
            </button>
            <a href="{{ route('properties.index') }}" class="w-full text-center py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold rounded-xl transition-all" id="{{ $idPrefix }}filter-reset" title="Reset Filters">
                Reset
            </a>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof window.initLocationCascading === 'function') {
                window.initLocationCascading({
                    stateId: '{{ $idPrefix }}filter-state',
                    cityId: '{{ $idPrefix }}filter-city',
                    localityId: '{{ $idPrefix }}filter-locality-select',
                    localityTextWrapId: '{{ $idPrefix }}locality-text-wrap',
                    localitySelectWrapId: '{{ $idPrefix }}locality-select-wrap',
                    selectedState: "{{ request('state') }}",
                    selectedCity: "{{ request('district') }}",
                    selectedLocality: "{{ request('locality') }}"
                });
            }
        });
    </script>
</aside>
