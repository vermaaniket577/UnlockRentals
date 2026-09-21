{{-- ============================================================
     UNLOCK RENTALS — SMART LOCATION & NEAR ME MANAGER
     Handles device GPS permission, reverse geocoding, and auto-fetching
     properties nearby in both the mobile app and mobile browsers.
     ============================================================ --}}

{{-- Location Onboarding / Selection Modal Sheet --}}
<div id="ur-location-modal" class="hidden fixed inset-0 z-[9999] overflow-y-auto" role="dialog" aria-modal="true" aria-labelledby="location-modal-title">
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm transition-opacity" onclick="window.closeLocationModal && window.closeLocationModal()"></div>

    {{-- Modal Container --}}
    <div class="flex min-h-full items-end sm:items-center justify-center p-0 sm:p-4 text-center">
        <div class="relative w-full max-w-lg transform overflow-hidden rounded-t-3xl sm:rounded-3xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-left shadow-2xl transition-all p-6 sm:p-8 animate-fade-in">
            
            {{-- Close Button --}}
            <button type="button" onclick="window.closeLocationModal && window.closeLocationModal()" class="absolute top-5 right-5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1.5 rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" aria-label="Close location dialog">
                <i class="ph-bold ph-x text-lg"></i>
            </button>

            {{-- Header Icon & Title --}}
            <div class="text-center mb-6">
                <div class="w-16 h-16 rounded-2xl bg-blue-50 dark:bg-blue-950/60 border border-blue-200/80 dark:border-blue-800/80 text-blue-600 dark:text-blue-400 flex items-center justify-center mx-auto mb-4 shadow-sm shadow-blue-500/10">
                    <i class="ph-fill ph-navigation-arrow text-3xl animate-bounce"></i>
                </div>
                <h3 id="location-modal-title" class="text-xl sm:text-2xl font-black font-display text-slate-900 dark:text-white tracking-tight">
                    Find Rentals Near You
                </h3>
                <p id="location-modal-desc" class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-2 leading-relaxed">
                    Allow location access to discover verified flats, rooms, and PGs right around your current location with zero brokerage.
                </p>
            </div>

            {{-- Location Notice / Error Banner (if permission denied) --}}
            <div id="location-notice-banner" class="hidden mb-5 p-3.5 bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/60 rounded-xl text-amber-800 dark:text-amber-300 text-xs font-semibold flex items-center gap-2.5">
                <i class="ph-bold ph-warning-circle text-base flex-shrink-0"></i>
                <span id="location-notice-text">Location access was denied. Please select your city below:</span>
            </div>

            {{-- Primary Action: Use GPS Location --}}
            <div class="space-y-3 mb-6">
                <button type="button" 
                        id="btn-use-gps-location" 
                        onclick="window.fetchPropertiesNearMe && window.fetchPropertiesNearMe({ autoRedirect: true })"
                        class="w-full flex items-center justify-center gap-2.5 py-3.5 px-6 rounded-2xl bg-blue-600 hover:bg-blue-700 active:scale-[0.98] text-white font-bold text-sm shadow-lg shadow-blue-500/25 transition-all cursor-pointer group">
                    <i class="ph-fill ph-navigation-arrow text-lg group-hover:scale-110 transition-transform"></i>
                    <span id="gps-button-label">Use My Current Location</span>
                </button>
            </div>

            {{-- Divider --}}
            <div class="relative flex py-2 items-center mb-5">
                <div class="flex-grow border-t border-slate-200 dark:border-slate-800"></div>
                <span class="flex-shrink mx-4 text-[11px] font-bold uppercase tracking-wider text-slate-400">Or Select City</span>
                <div class="flex-grow border-t border-slate-200 dark:border-slate-800"></div>
            </div>

            {{-- Quick City Grid --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 mb-4">
                @php
                    $popularCities = [
                        ['name' => 'Mumbai', 'icon' => '🏢'],
                        ['name' => 'Delhi NCR', 'icon' => '🏛️'],
                        ['name' => 'Bengaluru', 'icon' => '💻'],
                        ['name' => 'Pune', 'icon' => '🎓'],
                        ['name' => 'Hyderabad', 'icon' => '🕌'],
                        ['name' => 'Chennai', 'icon' => '🌊'],
                        ['name' => 'Kolkata', 'icon' => '🌉'],
                        ['name' => 'Ahmedabad', 'icon' => '🏭'],
                    ];
                @endphp
                @foreach($popularCities as $city)
                    <button type="button" 
                            onclick="window.setUserCityManually && window.setUserCityManually('{{ $city['name'] }}')"
                            class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200/80 dark:border-slate-800 hover:border-blue-500 hover:bg-blue-50/50 dark:hover:bg-blue-950/30 text-left transition-all group">
                        <span class="text-base">{{ $city['icon'] }}</span>
                        <span class="text-xs font-bold text-slate-800 dark:text-slate-200 group-hover:text-blue-600 dark:group-hover:text-blue-400 truncate">{{ $city['name'] }}</span>
                    </button>
                @endforeach
            </div>

            {{-- Footer Skip --}}
            <div class="text-center pt-2">
                <button type="button" onclick="window.closeLocationModal && window.closeLocationModal()" class="text-xs font-semibold text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                    Skip for now & browse all properties
                </button>
            </div>

        </div>
    </div>
</div>

{{-- Global Location Manager Script --}}
<script>
(function() {
    window.openLocationModal = function(noticeMessage) {
        var modal = document.getElementById('ur-location-modal');
        var noticeBanner = document.getElementById('location-notice-banner');
        var noticeText = document.getElementById('location-notice-text');

        if (noticeBanner && noticeText) {
            if (noticeMessage) {
                noticeText.textContent = noticeMessage;
                noticeBanner.classList.remove('hidden');
            } else {
                noticeBanner.classList.add('hidden');
            }
        }

        if (modal) {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
    };

    window.closeLocationModal = function() {
        var modal = document.getElementById('ur-location-modal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
        localStorage.setItem('ur_location_prompt_seen', '1');
    };

    // Set city manually and reload
    window.setUserCityManually = function(cityName) {
        if (!cityName) return;
        localStorage.setItem('ur_user_city', cityName);
        localStorage.setItem('ur_user_location_set', '1');
        localStorage.setItem('ur_location_prompt_seen', '1');
        window.closeLocationModal();

        // Redirect to properties index filtered by district/city
        window.location.href = "{{ route('properties.index') }}?district=" + encodeURIComponent(cityName) + "&near_me=1";
    };

    // Primary Near Me function using Device GPS Geolocation
    window.fetchPropertiesNearMe = function(opts) {
        opts = opts || {};
        var btn = document.getElementById('btn-use-gps-location');
        var originalBtnText = btn ? btn.innerHTML : '';

        if (!navigator.geolocation) {
            window.openLocationModal('GPS Geolocation is not supported on this device. Please select your city:');
            return;
        }

        if (btn) {
            btn.innerHTML = '<i class="ph ph-circle-notch ph-spin text-lg"></i><span>Detecting GPS Location...</span>';
            btn.disabled = true;
        }

        // Request native location
        navigator.geolocation.getCurrentPosition(
            function(position) {
                var lat = position.coords.latitude;
                var lng = position.coords.longitude;

                localStorage.setItem('ur_user_lat', lat);
                localStorage.setItem('ur_user_lng', lng);
                localStorage.setItem('ur_user_location_set', '1');
                localStorage.setItem('ur_location_prompt_seen', '1');

                if (btn) {
                    btn.innerHTML = '<i class="ph-bold ph-check text-lg"></i><span>Location Found! Fetching nearby homes...</span>';
                }

                // Reverse geocode to find city/neighborhood name (with timeout fallback)
                var nominatimUrl = 'https://nominatim.openstreetmap.org/reverse?format=json&lat=' + lat + '&lon=' + lng + '&zoom=14';
                
                var geocodeTimeout = setTimeout(function() {
                    // Fallback to coordinate-based search if reverse geocode is slow
                    window.location.href = "{{ route('properties.index') }}?lat=" + lat + "&lng=" + lng + "&near_me=1";
                }, 3000);

                fetch(nominatimUrl)
                    .then(function(res) { return res.json(); })
                    .then(function(data) {
                        clearTimeout(geocodeTimeout);
                        var address = data.address || {};
                        var detectedCity = address.city || address.town || address.state_district || address.suburb || address.county || '';
                        
                        if (detectedCity) {
                            localStorage.setItem('ur_user_city', detectedCity);
                            window.location.href = "{{ route('properties.index') }}?district=" + encodeURIComponent(detectedCity) + "&lat=" + lat + "&lng=" + lng + "&near_me=1";
                        } else {
                            window.location.href = "{{ route('properties.index') }}?lat=" + lat + "&lng=" + lng + "&near_me=1";
                        }
                    })
                    .catch(function() {
                        clearTimeout(geocodeTimeout);
                        window.location.href = "{{ route('properties.index') }}?lat=" + lat + "&lng=" + lng + "&near_me=1";
                    });
            },
            function(error) {
                if (btn) {
                    btn.innerHTML = originalBtnText;
                    btn.disabled = false;
                }
                var errMsg = 'Unable to access your GPS location. Please choose your city below:';
                if (error.code === error.PERMISSION_DENIED) {
                    errMsg = 'Location permission was denied. Please allow location in your device settings or select your city below:';
                } else if (error.code === error.POSITION_UNAVAILABLE) {
                    errMsg = 'GPS location is currently unavailable. Please select your city below:';
                }
                window.openLocationModal(errMsg);
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 60000
            }
        );
    };

    // Auto-prompt location on first visit inside Mobile App or on mobile browser
    document.addEventListener('DOMContentLoaded', function() {
        var isMobileApp = /UnlockRentals|wv|Version\/[0-9.]+/i.test(navigator.userAgent) 
            || window.isNativeApp === true 
            || document.documentElement.classList.contains('is-mobile-app')
            || new URLSearchParams(window.location.search).get('app') === '1';

        var hasSeenPrompt = localStorage.getItem('ur_location_prompt_seen') === '1';
        var hasLocationSet = localStorage.getItem('ur_user_location_set') === '1';

        // Auto-prompt for mobile app users on homepage if location is not set yet
        if (isMobileApp && !hasSeenPrompt && !hasLocationSet) {
            // Show prompt after brief delay for smooth app launch
            setTimeout(function() {
                window.openLocationModal();
            }, 1200);
        }
    });
})();
</script>
