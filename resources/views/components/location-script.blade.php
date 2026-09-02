<script>
    window._dbLocationData = {!! json_encode($locationData ?? [
        'states' => (object)[],
        'districts' => (object)[],
        'allDistricts' => [],
        'districtToState' => (object)[],
        'localities' => (object)[],
        'localitiesByState' => (object)[]
    ]) !!};
</script>
<script src="{{ asset('js/location-data.js') }}?v={{ file_exists(public_path('js/location-data.js')) ? filemtime(public_path('js/location-data.js')) : time() }}"></script>
