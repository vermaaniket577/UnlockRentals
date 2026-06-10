@php
    $currentCity = $city ?? 'Gurgaon';
    $currentCitySlug = Str::slug($currentCity);
    
    $otherCities = ['mumbai', 'delhi', 'noida', 'gurgaon', 'bangalore', 'pune', 'thane', 'navi-mumbai'];
    // Remove current city from the other cities list
    $otherCities = array_filter($otherCities, function($c) use ($currentCitySlug) {
        return $c !== $currentCitySlug;
    });
    
    $propertyTypes = [
        'flat' => 'Flats for Rent',
        'room' => 'Rooms for Rent',
        'pg' => 'PG & Co-Living',
        'house' => 'Houses for Rent'
    ];
@endphp

<div class="mt-20 border-t border-stone-200/80 dark:border-slate-800/80 pt-16" id="seo-internal-linking">
    <h3 class="text-xl font-bold text-zinc-900 dark:text-slate-100 mb-8 flex items-center gap-2">
        <i class="ph ph-trend-up text-[#2563EB]"></i>
        Trending Rental Searches
    </h3>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        {{-- Section 1: Current City Popular Searches --}}
        <div>
            <h4 class="text-xs font-bold text-zinc-400 dark:text-slate-500 uppercase tracking-widest mb-4">
                Popular in {{ $currentCity }}
            </h4>
            <ul class="space-y-3">
                @foreach($propertyTypes as $typeKey => $typeLabel)
                    <li>
                        <a href="{{ url("/{$typeKey}-for-rent-in-{$currentCitySlug}") }}" 
                           class="text-sm text-zinc-600 dark:text-slate-400 hover:text-[#2563EB] dark:hover:text-[#2563EB] font-light transition-colors flex items-center gap-2">
                            <i class="ph ph-caret-right text-[10px] text-zinc-300"></i>
                            {{ $typeLabel }} in {{ $currentCity }}
                        </a>
                    </li>
                @endforeach
                <li>
                    <a href="{{ url("/flat-for-rent-in-{$currentCitySlug}-under-20000") }}" 
                       class="text-sm text-zinc-600 dark:text-slate-400 hover:text-[#2563EB] dark:hover:text-[#2563EB] font-light transition-colors flex items-center gap-2">
                        <i class="ph ph-caret-right text-[10px] text-zinc-300"></i>
                        Budget Flats in {{ $currentCity }} under 20K
                    </a>
                </li>
                <li>
                    <a href="{{ url("/room-for-rent-in-{$currentCitySlug}-under-10000") }}" 
                       class="text-sm text-zinc-600 dark:text-slate-400 hover:text-[#2563EB] dark:hover:text-[#2563EB] font-light transition-colors flex items-center gap-2">
                        <i class="ph ph-caret-right text-[10px] text-zinc-300"></i>
                        Cheap Rooms in {{ $currentCity }} under 10K
                    </a>
                </li>
            </ul>
        </div>

        {{-- Section 2: Gender Co-living preferences --}}
        <div>
            <h4 class="text-xs font-bold text-zinc-400 dark:text-slate-500 uppercase tracking-widest mb-4">
                PGs & Co-living for Rent
            </h4>
            <ul class="space-y-3">
                <li>
                    <a href="{{ url("/pg-for-boys-in-{$currentCitySlug}") }}" 
                       class="text-sm text-zinc-600 dark:text-slate-400 hover:text-[#2563EB] dark:hover:text-[#2563EB] font-light transition-colors flex items-center gap-2">
                        <i class="ph ph-gender-male text-zinc-400"></i>
                        Boys PG in {{ $currentCity }}
                    </a>
                </li>
                <li>
                    <a href="{{ url("/pg-for-girls-in-{$currentCitySlug}") }}" 
                       class="text-sm text-zinc-600 dark:text-slate-400 hover:text-[#2563EB] dark:hover:text-[#2563EB] font-light transition-colors flex items-center gap-2">
                        <i class="ph ph-gender-female text-zinc-400"></i>
                        Girls PG in {{ $currentCity }}
                    </a>
                </li>
                <li>
                    <a href="{{ url("/pg-for-students-in-{$currentCitySlug}") }}" 
                       class="text-sm text-zinc-600 dark:text-slate-400 hover:text-[#2563EB] dark:hover:text-[#2563EB] font-light transition-colors flex items-center gap-2">
                        <i class="ph ph-student text-zinc-400"></i>
                        Student Hostels in {{ $currentCity }}
                    </a>
                </li>
                <li>
                    <a href="{{ url("/pg-for-professionals-in-{$currentCitySlug}") }}" 
                       class="text-sm text-zinc-600 dark:text-slate-400 hover:text-[#2563EB] dark:hover:text-[#2563EB] font-light transition-colors flex items-center gap-2">
                        <i class="ph ph-briefcase-metal text-zinc-400"></i>
                        Co-living for Professionals in {{ $currentCity }}
                    </a>
                </li>
            </ul>
        </div>

        {{-- Section 3: Explore Other Cities --}}
        <div>
            <h4 class="text-xs font-bold text-zinc-400 dark:text-slate-500 uppercase tracking-widest mb-4">
                Explore Other Cities
            </h4>
            <div class="flex flex-wrap gap-2">
                @foreach($otherCities as $otherCitySlug)
                    @php
                        $cityName = ucwords(str_replace('-', ' ', $otherCitySlug));
                    @endphp
                    <a href="{{ url("/flat-for-rent-in-{$otherCitySlug}") }}" 
                       class="text-xs px-3.5 py-2 bg-stone-50 dark:bg-slate-900 border border-stone-200/60 dark:border-slate-800 text-zinc-600 dark:text-slate-400 hover:bg-[#2563EB] hover:text-white hover:border-[#2563EB] dark:hover:bg-[#2563EB] dark:hover:border-[#2563EB] dark:hover:text-white rounded-full transition-all">
                        {{ $cityName }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
