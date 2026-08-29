<script>
    // Server-side loaded dynamic database locations
    const _dbLocationData = {!! json_encode($locationData ?? [
        'states' => (object)[],
        'districts' => (object)[],
        'allDistricts' => [],
        'districtToState' => (object)[],
        'localities' => (object)[],
        'localitiesByState' => (object)[]
    ]) !!};

    // Complete Standard Indian Location Dictionary (Guarantees instant, zero-latency cascading)
    const _standardDistrictsByState = {
        "HR": ["Gurugram", "Faridabad", "Ambala", "Bhiwani", "Charkhi Dadri", "Fatehabad", "Hisar", "Jhajjar", "Jind", "Kaithal", "Karnal", "Kurukshetra", "Mahendragarh", "Nuh", "Palwal", "Panchkula", "Panipat", "Rewari", "Rohtak", "Sirsa", "Sonipat", "Yamunanagar"],
        "DL": ["New Delhi", "Central Delhi", "East Delhi", "North Delhi", "North East Delhi", "North West Delhi", "Shahdara", "South Delhi", "South East Delhi", "South West Delhi", "West Delhi"],
        "UP": ["Noida", "Greater Noida", "Ghaziabad", "Agra", "Aligarh", "Ayodhya", "Bareilly", "Gorakhpur", "Jhansi", "Kanpur", "Lucknow", "Mathura", "Meerut", "Moradabad", "Prayagraj", "Varanasi", "Saharanpur", "Firozabad", "Muzaffarnagar"],
        "MH": ["Mumbai", "Mumbai Suburban", "Pune", "Thane", "Navi Mumbai", "Nagpur", "Nashik", "Aurangabad", "Solapur", "Kolhapur", "Amravati", "Nanded", "Alibaug"],
        "KA": ["Bengaluru", "Bengaluru Rural", "Mysuru", "Mangaluru", "Hubballi-Dharwad", "Belagavi", "Kalaburagi", "Davanagere", "Ballari", "Vijayapura", "Shivamogga", "Tumakuru"],
        "PB": ["Chandigarh", "Amritsar", "Ludhiana", "Jalandhar", "Patiala", "Bathinda", "Mohali", "Hoshiarpur", "Pathankot", "Moga"],
        "RJ": ["Jaipur", "Jodhpur", "Kota", "Bikaner", "Ajmer", "Udaipur", "Bhilwara", "Alwar", "Bharatpur", "Sikar"],
        "GJ": ["Ahmedabad", "Surat", "Vadodara", "Rajkot", "Bhavnagar", "Jamnagar", "Gandhinagar", "Junagadh", "Anand", "Navsari"],
        "WB": ["Kolkata", "Howrah", "North 24 Parganas", "South 24 Parganas", "Hooghly", "Durgapur", "Asansol", "Siliguri", "Darjeeling"],
        "TS": ["Hyderabad", "Secunderabad", "Warangal", "Nizamabad", "Khammam", "Karimnagar", "Ramagundam", "Mahbubnagar"],
        "TN": ["Chennai", "Coimbatore", "Madurai", "Tiruchirappalli", "Salem", "Tirunelveli", "Tiruppur", "Vellore", "Erode"],
        "AP": ["Visakhapatnam", "Vijayawada", "Guntur", "Nellore", "Kurnool", "Rajahmundry", "Tirupati", "Kakinada", "Kadapa"],
        "MP": ["Bhopal", "Indore", "Gwalior", "Jabalpur", "Ujjain", "Sagar", "Dewas", "Satna", "Ratlam", "Rewa"],
        "BR": ["Patna", "Gaya", "Bhagalpur", "Muzaffarpur", "Purnia", "Darbhanga", "Bihar Sharif", "Arrah", "Begusarai"],
        "UK": ["Dehradun", "Haridwar", "Rishikesh", "Nainital", "Haldwani", "Roorkee", "Rudrapur", "Kashipur"],
        "HP": ["Shimla", "Manali", "Dharamshala", "Solan", "Mandi", "Kullu", "Bilaspur", "Hamirpur", "Una", "Kangra"],
        "GA": ["North Goa", "South Goa", "Panaji", "Margao", "Vasco da Gama", "Mapusa", "Ponda"],
        "JH": ["Ranchi", "Jamshedpur", "Dhanbad", "Bokaro", "Deoghar", "Hazaribagh"],
        "CT": ["Raipur", "Bhilai", "Bilaspur", "Korba", "Durg", "Rajnandgaon"],
        "OR": ["Bhubaneswar", "Cuttack", "Rourkela", "Berhampur", "Sambalpur", "Puri"],
        "AS": ["Guwahati", "Silchar", "Dibrugarh", "Jorhat", "Nagaon", "Tinsukia"],
        "JK": ["Srinagar", "Jammu", "Anantnag", "Baramulla", "Kathua", "Udhampur"],
        "CH": ["Chandigarh", "Mohali", "Panchkula", "Zirakpur"]
    };

    // Add uppercase state name aliases (e.g. "HARYANA" -> "HR")
    const _stateNameToCode = {
        "HARYANA": "HR", "DELHI": "DL", "UTTAR PRADESH": "UP", "MAHARASHTRA": "MH",
        "KARNATAKA": "KA", "PUNJAB": "PB", "RAJASTHAN": "RJ", "GUJARAT": "GJ",
        "WEST BENGAL": "WB", "TELANGANA": "TS", "TAMIL NADU": "TN", "ANDHRA PRADESH": "AP",
        "MADHYA PRADESH": "MP", "BIHAR": "BR", "UTTARAKHAND": "UK", "HIMACHAL PRADESH": "HP",
        "GOA": "GA", "JHARKHAND": "JH", "CHHATTISGARH": "CT", "ODISHA": "OR", "ASSAM": "AS",
        "JAMMU AND KASHMIR": "JK", "CHANDIGARH": "CH"
    };

    // Standard popular localities dictionary
    const _standardLocalities = {
        "gurugram": ["Cyber City", "Golf Course Road", "Golf Course Extension", "DLF Phase 1", "DLF Phase 2", "DLF Phase 3", "DLF Phase 4", "DLF Phase 5", "Sushant Lok 1", "Sushant Lok 2", "Sector 14", "Sector 15", "Sector 29", "Sector 48", "Sector 49", "Sector 54", "Sector 56", "Sector 57", "Sohna Road", "MG Road", "Nirvana Country", "Palam Vihar"],
        "faridabad": ["Sector 14", "Sector 15", "Sector 16", "Sector 21C", "Sector 28", "Sector 37", "Greenfield Colony", "Neharpar", "NIT Faridabad", "Surajkund Road"],
        "new delhi": ["Connaught Place", "Chanakyapuri", "Barakhamba", "Khan Market", "Lutyens Delhi", "Panchsheel Park", "Golf Links", "Jor Bagh"],
        "south delhi": ["Hauz Khas", "Saket", "Greater Kailash 1", "Greater Kailash 2", "Green Park", "Defence Colony", "Lajpat Nagar", "Malviya Nagar", "Vasant Kunj", "Vasant Vihar", "South Extension", "Gulmohar Park", "Alaknanda", "Kalkaji", "Okhla"],
        "noida": ["Sector 15", "Sector 18", "Sector 50", "Sector 62", "Sector 75", "Sector 76", "Sector 78", "Sector 93A", "Sector 128", "Sector 137", "Sector 150", "Noida Expressway"],
        "greater noida": ["Knowledge Park", "Pari Chowk", "Alpha 1", "Alpha 2", "Beta 1", "Beta 2", "Gamma 1", "Delta 1", "Omnicron", "Zeta", "Greater Noida West", "Techzone 4"],
        "bengaluru": ["Koramangala", "Indiranagar", "HSR Layout", "Whitefield", "Electronic City", "Marathahalli", "BTM Layout", "Jayanagar", "JP Nagar", "Bellandur", "Sarjapur Road", "Hebbal", "Yelahanka", "Banashankari", "Rajajinagar", "Malleshwaram"],
        "mumbai": ["Bandra West", "Bandra East", "Andheri West", "Andheri East", "Juhu", "Powai", "Worli", "Lower Parel", "Colaba", "Dadar", "Malad West", "Goregaon West", "Kandivali", "Borivali", "Santacruz", "Khar West"],
        "pune": ["Koregaon Park", "Kalyani Nagar", "Viman Nagar", "Baner", "Aundh", "Wakad", "Hinjewadi", "Magarpatta", "Kharadi", "Hadapsar", "Kothrud", "Bavdhan", "Pimple Saudagar"],
        "hyderabad": ["Hitec City", "Gachibowli", "Madhapur", "Jubilee Hills", "Banjara Hills", "Kondapur", "Kukatpally", "Manikonda", "Financial District", "Begumpet", "Ameerpet", "Secunderabad"],
        "jaipur": ["Malviya Nagar", "Vaishali Nagar", "Mansarovar", "C Scheme", "Civil Lines", "Raja Park", "Jagatpura", "Tonk Road", "Ajmer Road", "Bani Park"],
        "chandigarh": ["Sector 8", "Sector 9", "Sector 10", "Sector 11", "Sector 17", "Sector 22", "Sector 35", "Sector 43", "Industrial Area Phase 1", "Manimajra", "IT Park"]
    };

    // Build unified global location database
    window.IndianLocationData = {
        states: Object.assign({}, {
            "AP": "Andhra Pradesh", "AR": "Arunachal Pradesh", "AS": "Assam", "BR": "Bihar", "CT": "Chhattisgarh",
            "GA": "Goa", "GJ": "Gujarat", "HR": "Haryana", "HP": "Himachal Pradesh", "JH": "Jharkhand",
            "KA": "Karnataka", "KL": "Kerala", "MP": "Madhya Pradesh", "MH": "Maharashtra", "MN": "Manipur",
            "ML": "Meghalaya", "MZ": "Mizoram", "NL": "Nagaland", "OR": "Odisha", "PB": "Punjab",
            "RJ": "Rajasthan", "SK": "Sikkim", "TN": "Tamil Nadu", "TS": "Telangana", "TR": "Tripura",
            "UP": "Uttar Pradesh", "UK": "Uttarakhand", "WB": "West Bengal", "DL": "Delhi", "CH": "Chandigarh"
        }, _dbLocationData.states || {}),

        districts: Object.assign({}, _standardDistrictsByState, _dbLocationData.districts || {}),
        allDistricts: _dbLocationData.allDistricts || [],
        localities: Object.assign({}, _standardLocalities, _dbLocationData.localities || {}),
        localitiesByState: Object.assign({}, _dbLocationData.localitiesByState || {})
    };

    // Helper: Normalize state code from input
    window.resolveStateCode = function(val) {
        if (!val) return '';
        const clean = val.toString().trim().toUpperCase();
        if (window.IndianLocationData.districts[clean]) return clean;
        if (_stateNameToCode[clean]) return _stateNameToCode[clean];
        // Check standard states
        for (const [code, name] of Object.entries(window.IndianLocationData.states)) {
            if (code.toUpperCase() === clean || name.toUpperCase() === clean) {
                return code;
            }
        }
        return clean;
    };

    // Direct Handler: When State Dropdown Changes
    window.handleLocationStateChange = function(stateSelectEl, citySelectId = 'city-select', localitySelectId = 'locality-select') {
        if (!stateSelectEl) return;
        const form = stateSelectEl.form || document;
        const cityEl = document.getElementById(citySelectId) || form.querySelector(`[name="district"]`) || form.querySelector(`[name="location"]`);
        const localityEl = document.getElementById(localitySelectId) || form.querySelector(`[name="locality"]`);

        if (!cityEl) return;

        const rawState = (stateSelectEl.value || '').trim();
        const stateCode = window.resolveStateCode(rawState);
        const stateName = (window.IndianLocationData.states && window.IndianLocationData.states[stateCode]) || rawState;

        // Clear and prepare city options
        cityEl.innerHTML = '';
        
        let districtList = null;
        if (stateCode) {
            districtList = window.IndianLocationData.districts[stateCode] 
                || window.IndianLocationData.districts[stateCode.toUpperCase()]
                || window.IndianLocationData.districts[stateName]
                || window.IndianLocationData.districts[stateName.toUpperCase()];
        }

        if (stateCode && districtList && districtList.length > 0) {
            const firstOpt = new Option(`\u00A0\u00A0All Cities in ${stateName}`, '');
            cityEl.add(firstOpt);

            districtList.forEach(distName => {
                const opt = new Option(`\u00A0\u00A0${distName}`, distName);
                cityEl.add(opt);
            });
        } else if (stateCode) {
            // Fallback AJAX if not in pre-loaded dictionary
            const firstOpt = new Option(`\u00A0\u00A0Loading ${stateName} Cities...`, '');
            cityEl.add(firstOpt);

            fetch(`/api/locations/districts?state=${encodeURIComponent(stateCode)}`)
                .then(r => r.json())
                .then(data => {
                    cityEl.innerHTML = '';
                    cityEl.add(new Option(`\u00A0\u00A0All Cities in ${stateName}`, ''));
                    if (Array.isArray(data) && data.length > 0) {
                        data.forEach(item => {
                            cityEl.add(new Option(`\u00A0\u00A0${item.name}`, item.name));
                        });
                    } else {
                        cityEl.add(new Option(`\u00A0\u00A0Select City / District`, ''));
                    }
                })
                .catch(() => {
                    cityEl.innerHTML = `<option value="">\u00A0\u00A0Select District</option>`;
                });
        } else {
            // No state chosen: show general options
            cityEl.add(new Option('\u00A0\u00A0All Cities / Districts', ''));
            const all = window.IndianLocationData.allDistricts || [];
            if (all.length > 0) {
                all.forEach(item => {
                    const label = item.state_code ? `${item.name} (${item.state_code})` : item.name;
                    cityEl.add(new Option(`\u00A0\u00A0${label}`, item.name));
                });
            } else {
                ['Gurugram (HR)', 'New Delhi (DL)', 'South Delhi (DL)', 'Noida (UP)', 'Faridabad (HR)', 'Bengaluru (KA)', 'Mumbai (MH)', 'Pune (MH)', 'Hyderabad (TS)', 'Jaipur (RJ)', 'Chandigarh (CH)'].forEach(d => {
                    cityEl.add(new Option(`\u00A0\u00A0${d}`, d.split(' ')[0]));
                });
            }
        }

        // Trigger locality update
        window.handleLocationCityChange(cityEl, localitySelectId, stateSelectEl.id);
    };

    // Direct Handler: When City Dropdown Changes
    window.handleLocationCityChange = function(citySelectEl, localitySelectId = 'locality-select', stateSelectId = 'state-select') {
        if (!citySelectEl) return;
        const form = citySelectEl.form || document;
        const localityEl = document.getElementById(localitySelectId) || form.querySelector(`[name="locality"]`);
        const stateEl = document.getElementById(stateSelectId) || form.querySelector(`[name="state"]`);

        if (!localityEl) return;

        const cityVal = (citySelectEl.value || '').trim();
        const cityKey = cityVal.toLowerCase().replace(/ \([a-z]+\)$/i, '').trim();
        const rawState = stateEl ? (stateEl.value || '').trim() : '';
        const stateCode = window.resolveStateCode(rawState);

        localityEl.innerHTML = '';

        let locList = null;
        if (cityKey) {
            locList = window.IndianLocationData.localities[cityKey]
                || window.IndianLocationData.localities[cityKey.replace(/\s+/g, '-')]
                || window.IndianLocationData.localities[cityKey.replace(/-/g, ' ')];
        } else if (stateCode) {
            locList = window.IndianLocationData.localitiesByState[stateCode]
                || window.IndianLocationData.localitiesByState[stateCode.toUpperCase()];
        }

        const placeholder = cityVal ? `\u00A0\u00A0All Localities in ${cityVal}` : (stateCode ? `\u00A0\u00A0All Localities in ${rawState}` : '\u00A0\u00A0All Localities / Areas');
        localityEl.add(new Option(placeholder, ''));

        if (locList && locList.length > 0) {
            locList.forEach(loc => {
                localityEl.add(new Option(`\u00A0\u00A0${loc}`, loc));
            });
        } else if (cityVal) {
            // AJAX fallback
            fetch(`/api/locations/localities?district=${encodeURIComponent(cityVal)}`)
                .then(r => r.json())
                .then(data => {
                    if (Array.isArray(data) && data.length > 0) {
                        data.forEach(item => {
                            localityEl.add(new Option(`\u00A0\u00A0${item.name}`, item.name));
                        });
                    }
                })
                .catch(() => {});
        }
    };

    /**
     * Helper to init cascading dropdowns on DOMContentLoaded
     */
    window.initLocationCascading = function(config) {
        const stateEl = document.getElementById(config.stateId);
        const cityEl = document.getElementById(config.cityId);
        const localityEl = document.getElementById(config.localityId);

        if (!stateEl || !cityEl) return;

        // Attach direct change listeners
        stateEl.addEventListener('change', function() {
            window.handleLocationStateChange(this, config.cityId, config.localityId);
        });

        cityEl.addEventListener('change', function() {
            window.handleLocationCityChange(this, config.localityId, config.stateId);
        });

        // Click-through focus
        [stateEl, cityEl, localityEl].forEach(el => {
            if (!el) return;
            const group = el.closest('.filter-group') || el.closest('.filter-input-wrap');
            if (group && !group.dataset.hasClickListener) {
                group.dataset.hasClickListener = 'true';
                group.addEventListener('click', (e) => {
                    if (e.target !== el) el.focus();
                });
            }
        });

        // If a state was pre-selected from query params, filter immediately
        if (config.selectedState || stateEl.value) {
            if (config.selectedState && stateEl.value !== config.selectedState) {
                stateEl.value = config.selectedState;
            }
            window.handleLocationStateChange(stateEl, config.cityId, config.localityId);
            if (config.selectedCity) {
                cityEl.value = config.selectedCity;
                window.handleLocationCityChange(cityEl, config.localityId, config.stateId);
            }
            if (config.selectedLocality && localityEl) {
                localityEl.value = config.selectedLocality;
            }
        }
    };

    /**
     * Global Layout & Intent Pill Selector
     */
    window.setPill = function(btn, value, inputId) {
        if (!btn) return;
        const group = btn.closest('.pill-group');
        if (group) {
            const input = inputId ? document.getElementById(inputId) : group.querySelector('input[type="hidden"]');
            if (input) {
                input.value = value;
                input.dispatchEvent(new Event('change', { bubbles: true }));
            }
            const buttons = group.querySelectorAll('.pill-btn');
            buttons.forEach(function(b) {
                b.classList.remove('active');
            });
        }
        btn.classList.add('active');
    };

    // Automatic Document Click Delegation for All Pill Buttons
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.pill-btn');
        if (!btn) return;
        const group = btn.closest('.pill-group');
        if (!group) return;
        const hiddenInput = group.querySelector('input[type="hidden"]');
        const val = btn.getAttribute('data-value') || btn.textContent.trim().toLowerCase();

        group.querySelectorAll('.pill-btn').forEach(function(b) {
            b.classList.remove('active');
        });
        btn.classList.add('active');

        if (hiddenInput) {
            hiddenInput.value = btn.dataset.value || val;
            hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
        }
    });
</script>
