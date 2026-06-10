<script>
    window.IndianLocationData = @json($locationData ?? ['states' => [], 'districts' => [], 'localities' => []]);

    /**
     * Helper to init cascading dropdowns
     */
    window.initLocationCascading = function(config) {
        const stateEl = document.getElementById(config.stateId);
        const cityEl = document.getElementById(config.cityId);
        const localityEl = document.getElementById(config.localityId);
        const localityTextWrap = document.getElementById(config.localityTextWrapId);
        const localitySelectWrap = document.getElementById(config.localitySelectWrapId);

        if (!stateEl || !cityEl) return;

        // Populate States
        if (stateEl.options.length <= 1) {
            Object.entries(window.IndianLocationData.states).forEach(([code, name]) => {
                const opt = new Option(name, code);
                if (config.selectedState === code) opt.selected = true;
                stateEl.add(opt);
            });
        }

        const updateCities = (selectedCity = null) => {
            const stateCode = stateEl.value;
            cityEl.innerHTML = '<option value="">Select District / City</option>';
            cityEl.disabled = !stateCode;
            
            if (stateCode && window.IndianLocationData.districts[stateCode]) {
                window.IndianLocationData.districts[stateCode].forEach(city => {
                    const val = city.toLowerCase().replace(/\s+/g, '-');
                    const opt = new Option(city, val);
                    if (selectedCity === val || selectedCity === city) opt.selected = true;
                    cityEl.add(opt);
                });
            }
            updateLocalities();
        };

        const updateLocalities = (selectedLocality = null) => {
            if (!localityEl) return;
            const cityVal = cityEl.value;
            localityEl.innerHTML = '<option value="">Select Locality</option>';
            
            const list = window.IndianLocationData.localities[cityVal];
            
            if (list && list.length > 0) {
                list.forEach(loc => {
                    const opt = new Option(loc, loc);
                    if (selectedLocality === loc) opt.selected = true;
                    localityEl.add(opt);
                });
                localityEl.disabled = false;
            } else if (cityVal) {
                // Generic Fallback for cities without specific data
                ['Main Town','Market Area','Station Road','Civil Lines','Residential Area','Sector 1','Sector 2'].forEach(loc => {
                    localityEl.add(new Option(loc, loc));
                });
                localityEl.disabled = false;
            } else {
                localityEl.disabled = true;
            }
        };

        stateEl.addEventListener('change', () => updateCities());
        cityEl.addEventListener('change', () => updateLocalities());

        // Initial trigger if values exist
        if (stateEl.value) updateCities(config.selectedCity);
        if (cityEl.value) updateLocalities(config.selectedLocality);
    };
</script>
