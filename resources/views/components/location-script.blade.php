@php
    $dbDistrictsByState = \Illuminate\Support\Facades\Cache::remember('db_districts_by_state_v3', 86400, function() {
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('districts') || !\Illuminate\Support\Facades\Schema::hasTable('states')) {
                return [];
            }
            $states = \Illuminate\Support\Facades\DB::table('states')->pluck('name', 'id')->all();
            $stateCodes = \Illuminate\Support\Facades\DB::table('states')->pluck('code', 'id')->all();
            $districts = \Illuminate\Support\Facades\DB::table('districts')->select('state_id', 'name')->get();
            $map = [];
            foreach ($districts as $d) {
                $sName = $states[$d->state_id] ?? null;
                $sCode = $stateCodes[$d->state_id] ?? null;
                if ($sCode) {
                    $map[$sCode][] = $d->name;
                }
                if ($sName && $sName !== $sCode) {
                    $map[$sName][] = $d->name;
                }
            }
            return $map;
        } catch (\Throwable $e) {
            return [];
        }
    });
@endphp
<script>
    window._dbLocationData = window._dbLocationData || {};
    @if(!empty($dbDistrictsByState))
    window._dbLocationData.districts = Object.assign(window._dbLocationData.districts || {}, @json($dbDistrictsByState));
    @endif
</script>
<script defer src="{{ asset('js/location-data.js') }}?v={{ file_exists(public_path('js/location-data.js')) ? filemtime(public_path('js/location-data.js')) : time() }}"></script>

