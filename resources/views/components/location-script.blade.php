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
            // Ensure Sector 13 is explicitly present in Gurugram and Gurgaon
            if (!isset($map['gurugram'])) $map['gurugram'] = [];
            if (!in_array('Sector 13', $map['gurugram'])) {
                $map['gurugram'][] = 'Sector 13';
            }
            natcasesort($map['gurugram']);
            $map['gurugram'] = array_values($map['gurugram']);
            $map['gurgaon'] = $map['gurugram'];
            $map['Gurugram'] = $map['gurugram'];
            $map['Gurgaon'] = $map['gurugram'];

            return $map;
        } catch (\Throwable $e) {
            return [
                'gurugram' => ['Sector 13'],
                'gurgaon' => ['Sector 13']
            ];
        }
    });

    if (empty($dbLocalitiesByDistrict['gurugram']) || !in_array('Sector 13', $dbLocalitiesByDistrict['gurugram'])) {
        $dbLocalitiesByDistrict['gurugram'][] = 'Sector 13';
        natcasesort($dbLocalitiesByDistrict['gurugram']);
        $dbLocalitiesByDistrict['gurugram'] = array_values($dbLocalitiesByDistrict['gurugram']);
        $dbLocalitiesByDistrict['gurgaon'] = $dbLocalitiesByDistrict['gurugram'];
        $dbLocalitiesByDistrict['Gurugram'] = $dbLocalitiesByDistrict['gurugram'];
        $dbLocalitiesByDistrict['Gurgaon'] = $dbLocalitiesByDistrict['gurugram'];
    }
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
<script defer src="{{ asset('js/location-data.js') }}?v={{ file_exists(public_path('js/location-data.js')) ? filemtime(public_path('js/location-data.js')) : time() }}_s13v{{ time() }}"></script>
<script>
(function() {
    function patchLocalities() {
        if (!window.IndianLocationData) return;
        window.IndianLocationData.localities = window.IndianLocationData.localities || {};

        if (window._dbLocationData && window._dbLocationData.localities) {
            for (var city in window._dbLocationData.localities) {
                var cLower = city.toLowerCase();
                var cSlug = cLower.replace(/\s+/g, '-');
                var dbList = window._dbLocationData.localities[city] || [];
                var existing = window.IndianLocationData.localities[cLower] || window.IndianLocationData.localities[city] || [];
                
                var set = new Set();
                existing.concat(dbList).forEach(function(item) {
                    if (typeof item === 'string' && item.trim()) {
                        set.add(item.trim());
                    }
                });
                var sorted = Array.from(set).sort(function(a, b) {
                    return a.localeCompare(b, undefined, { numeric: true, sensitivity: 'base' });
                });
                window.IndianLocationData.localities[city] = sorted;
                window.IndianLocationData.localities[cLower] = sorted;
                window.IndianLocationData.localities[cSlug] = sorted;
            }
        }

        // Guarantee Sector 13 in Gurugram / Gurgaon
        ['gurugram', 'gurgaon', 'Gurugram', 'Gurgaon'].forEach(function(g) {
            var list = window.IndianLocationData.localities[g] || [];
            if (!list.includes('Sector 13')) {
                list.push('Sector 13');
                list.sort(function(a, b) {
                    return a.localeCompare(b, undefined, { numeric: true, sensitivity: 'base' });
                });
                window.IndianLocationData.localities[g] = list;
                window.IndianLocationData.localities[g.toLowerCase()] = list;
            }
        });
    }

    patchLocalities();
    window.addEventListener('load', patchLocalities);
    document.addEventListener('DOMContentLoaded', patchLocalities);
    var pInt = setInterval(patchLocalities, 150);
    setTimeout(function() { clearInterval(pInt); }, 3000);

    function syncDropdown(cityVal, locSelect) {
        if (!cityVal || !locSelect) return;
        var isGurg = /gur/i.test(cityVal);

        // Immediate injection for Gurugram if missing
        if (isGurg) {
            var hasS13 = Array.from(locSelect.options).some(function(o) { return o.value.trim().toLowerCase() === 'sector 13'; });
            if (!hasS13 && locSelect.options.length > 1) {
                var opt = new Option('\u00A0\u00A0Sector 13', 'Sector 13');
                locSelect.add(opt);
                var placeholder = locSelect.options[0];
                var rest = Array.from(locSelect.options).slice(1);
                rest.sort(function(a, b) {
                    return a.text.trim().localeCompare(b.text.trim(), undefined, { numeric: true, sensitivity: 'base' });
                });
                locSelect.innerHTML = '';
                locSelect.add(placeholder);
                rest.forEach(function(o) { locSelect.add(o); });
            }
        }

        // Live API fetch to sync all admin-added localities
        var cleanCity = cityVal.replace(/\s*\([A-Za-z]+\)$/, '').trim();
        fetch('/api/locations/localities?district=' + encodeURIComponent(cleanCity))
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (Array.isArray(data) && data.length > 0) {
                    var existingVals = new Set(Array.from(locSelect.options).map(function(o) { return o.value.trim().toLowerCase(); }));
                    var added = false;
                    data.forEach(function(item) {
                        var name = (item.name || '').trim();
                        if (name && !existingVals.has(name.toLowerCase())) {
                            locSelect.add(new Option('\u00A0\u00A0' + name, name));
                            existingVals.add(name.toLowerCase());
                            added = true;
                        }
                    });
                    if (added && locSelect.options.length > 2) {
                        var placeholder = locSelect.options[0];
                        var rest = Array.from(locSelect.options).slice(1);
                        rest.sort(function(a, b) {
                            return a.text.trim().localeCompare(b.text.trim(), undefined, { numeric: true, sensitivity: 'base' });
                        });
                        locSelect.innerHTML = '';
                        locSelect.add(placeholder);
                        rest.forEach(function(o) { locSelect.add(o); });
                    }
                }
            })
            .catch(function() {});
    }

    function attachLiveSync() {
        var citySelects = document.querySelectorAll('select[name="location"], select[name="district"], #create-city, #edit-city, #city-select');
        citySelects.forEach(function(cityEl) {
            if (cityEl.dataset.hasLiveSectorSync) return;
            cityEl.dataset.hasLiveSectorSync = 'true';

            var form = cityEl.form || document;
            var locSelect = form.querySelector('select[name="locality"]') || document.getElementById('create-locality-select') || document.getElementById('edit-locality-select') || document.getElementById('locality-select');

            cityEl.addEventListener('change', function() {
                var cVal = (cityEl.value || '').trim();
                setTimeout(function() {
                    syncDropdown(cVal, locSelect);
                }, 50);
            });

            // If city is already pre-selected on page load (e.g., validation error or edit mode)
            if (cityEl.value) {
                setTimeout(function() {
                    syncDropdown(cityEl.value, locSelect);
                }, 200);
            }
        });
    }

    attachLiveSync();
    document.addEventListener('DOMContentLoaded', attachLiveSync);
    window.addEventListener('load', attachLiveSync);
})();
</script>
