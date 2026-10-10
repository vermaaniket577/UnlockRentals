@php
    $dbDistrictsByState = \Illuminate\Support\Facades\Cache::remember('db_districts_by_state_v3', 1800, function() {
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

    $dbLocalitiesByDistrict = \Illuminate\Support\Facades\Cache::remember('db_localities_by_district_v2', 1800, function() {
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('localities') || !\Illuminate\Support\Facades\Schema::hasTable('districts')) {
                return [];
            }
            $records = \Illuminate\Support\Facades\DB::table('localities')
                ->join('districts', 'localities.district_id', '=', 'districts.id')
                ->select('districts.name as district_name', 'districts.id as district_id', 'localities.name as locality_name')
                ->orderBy('localities.name')
                ->get();

            $cityAliases = [
                'gurugram' => ['gurugram', 'gurgaon'],
                'gurgaon' => ['gurugram', 'gurgaon'],
                'bengaluru' => ['bengaluru', 'bangalore'],
                'bangalore' => ['bengaluru', 'bangalore'],
                'prayagraj' => ['prayagraj', 'allahabad'],
                'allahabad' => ['prayagraj', 'allahabad'],
                'varanasi' => ['varanasi', 'banaras', 'benares'],
                'banaras' => ['varanasi', 'banaras', 'benares'],
                'benares' => ['varanasi', 'banaras', 'benares'],
                'puducherry' => ['puducherry', 'pondicherry'],
                'pondicherry' => ['puducherry', 'pondicherry'],
                'mysuru' => ['mysuru', 'mysore'],
                'mysore' => ['mysuru', 'mysore']
            ];

            $map = [];
            foreach ($records as $r) {
                $dName = trim($r->district_name);
                $dSlug = str_replace(' ', '-', strtolower($dName));
                $dLower = strtolower($dName);
                $dId = (string)$r->district_id;
                $lName = trim($r->locality_name);
                if ($lName === '') continue;

                $targetKeys = [$dSlug, $dLower, $dName, $dId];
                if (isset($cityAliases[$dLower])) {
                    foreach ($cityAliases[$dLower] as $alias) {
                        $targetKeys[] = $alias;
                        $targetKeys[] = str_replace(' ', '-', $alias);
                        $targetKeys[] = ucwords($alias);
                    }
                }

                foreach (array_unique($targetKeys) as $k) {
                    if (!isset($map[$k])) $map[$k] = [];
                    if (!in_array($lName, $map[$k])) {
                        $map[$k][] = $lName;
                    }
                }
            }

            foreach ($map as $k => $list) {
                natcasesort($map[$k]);
                $map[$k] = array_values($map[$k]);
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
    @if(!empty($dbLocalitiesByDistrict))
    window._dbLocationData.localities = Object.assign(window._dbLocationData.localities || {}, @json($dbLocalitiesByDistrict));
    @endif
</script>
<script defer src="{{ asset('js/location-data.js') }}?v={{ file_exists(public_path('js/location-data.js')) ? filemtime(public_path('js/location-data.js')) : time() }}"></script>
