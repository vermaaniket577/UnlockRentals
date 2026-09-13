<script>
    window._dbLocationData = window._dbLocationData || {};
</script>
<script defer src="{{ asset('js/location-data.js') }}?v={{ file_exists(public_path('js/location-data.js')) ? filemtime(public_path('js/location-data.js')) : time() }}"></script>
