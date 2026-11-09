<?php

namespace App\Services;

use App\Models\ConsentRecord;
use App\Models\Property;
use App\Models\User;
use App\Models\Visitor;
use App\Models\VisitorEvent;
use App\Models\VisitorSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class VisitorTrackingService
{
    public const COOKIE_NAME = 'ur_visitor_uuid';
    public const COOKIE_LIFETIME = 525600; // 365 days in minutes

    public const SCORING_RULES = [
        'page_view' => 1,
        'search' => 3,
        'property_view' => 3,
        'favorite_property' => 5,
        'share_property' => 3,
        'contact_owner_clicked' => 8,
        'whatsapp_clicked' => 10,
        'enquiry_started' => 5,
        'enquiry_submitted' => 20,
        'lead_submitted' => 20,
        'visit_scheduled' => 30,
        'phone_revealed' => 15,
        'exit_intent_submitted' => 20,
        'auth_page_viewed' => 2,
        'auth_abandoned' => 6,
        'auth_exit_intent_shown' => 3,
        'auth_exit_lead_submitted' => 25,
        'auth_exit_whatsapp_clicked' => 15,
    ];

    /**
     * Resolve the client's public IP address considering proxies/Cloudflare.
     */
    public static function getClientIp(Request $request): string
    {
        $headers = [
            'CF-Connecting-IP',
            'True-Client-IP',
            'X-Real-IP',
            'X-Forwarded-For',
        ];
        foreach ($headers as $h) {
            $val = $request->header($h);
            if ($val) {
                $ips = array_map('trim', explode(',', $val));
                foreach ($ips as $ip) {
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                        return $ip;
                    }
                }
            }
        }
        return $request->ip() ?: '127.0.0.1';
    }

    /**
     * Fast, resilient IP geolocation resolver with caching.
     */
    public static function resolveIpLocation(?string $ip): ?array
    {
        if (empty($ip)) {
            return null;
        }

        // Handle private / reserved / loopback IPs (local dev fallback)
        if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return [
                'city' => 'Gurugram',
                'state' => 'Haryana',
                'country' => 'India',
                'lat' => 28.4595,
                'lng' => 77.0266,
            ];
        }

        $cacheKey = 'ur_geoip_' . md5($ip);

        return \Illuminate\Support\Facades\Cache::remember($cacheKey, 86400 * 30, function () use ($ip) {
            $defaultHeaders = [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 3,
                CURLOPT_CONNECTTIMEOUT => 2,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            ];

            // 1. Primary: ipapi.co (HTTPS, highly accurate for Indian ISPs like Jio, Airtel, ACT)
            try {
                $ch = curl_init("https://ipapi.co/{$ip}/json/");
                curl_setopt_array($ch, $defaultHeaders);
                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($httpCode === 200 && $response) {
                    $data = json_decode($response, true);
                    if (!empty($data['city']) && empty($data['error'])) {
                        return [
                            'city' => ucwords(strtolower(trim($data['city']))),
                            'state' => !empty($data['region']) ? ucwords(strtolower(trim($data['region']))) : null,
                            'country' => !empty($data['country_name']) ? trim($data['country_name']) : 'India',
                            'lat' => $data['latitude'] ?? null,
                            'lng' => $data['longitude'] ?? null,
                        ];
                    }
                }
            } catch (\Throwable $e) {}

            // 2. Secondary: ipwho.is (HTTPS, fast global fallback)
            try {
                $ch = curl_init("https://ipwho.is/{$ip}");
                curl_setopt_array($ch, $defaultHeaders);
                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($httpCode === 200 && $response) {
                    $data = json_decode($response, true);
                    if (!empty($data['success']) && !empty($data['city'])) {
                        return [
                            'city' => ucwords(strtolower(trim($data['city']))),
                            'state' => !empty($data['region']) ? ucwords(strtolower(trim($data['region']))) : null,
                            'country' => !empty($data['country']) ? trim($data['country']) : 'India',
                            'lat' => $data['latitude'] ?? null,
                            'lng' => $data['longitude'] ?? null,
                        ];
                    }
                }
            } catch (\Throwable $e) {}

            // 3. Tertiary: ip-api.com (HTTP)
            try {
                $ch = curl_init("http://ip-api.com/json/{$ip}?fields=status,message,country,regionName,city,lat,lon");
                curl_setopt_array($ch, $defaultHeaders);
                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($httpCode === 200 && $response) {
                    $data = json_decode($response, true);
                    if (($data['status'] ?? '') === 'success' && !empty($data['city'])) {
                        return [
                            'city' => ucwords(strtolower(trim($data['city']))),
                            'state' => !empty($data['regionName']) ? ucwords(strtolower(trim($data['regionName']))) : null,
                            'country' => !empty($data['country']) ? trim($data['country']) : 'India',
                            'lat' => $data['lat'] ?? null,
                            'lng' => $data['lon'] ?? null,
                        ];
                    }
                }
            } catch (\Throwable $e) {}

            return null;
        });
    }

    /**
     * Smart Indian City & Locality Extractor from search query string.
     */
    public static function parseIndianLocationFromQuery(?string $query): ?array
    {
        if (empty($query)) return null;

        $q = strtolower(trim($query));

        // Mapping of popular Indian cities & localities
        static $indianLocations = [
            'gurugram' => ['city' => 'Gurugram', 'state' => 'Haryana'],
            'gurgaon' => ['city' => 'Gurugram', 'state' => 'Haryana'],
            'cyber city' => ['city' => 'Gurugram', 'locality' => 'Cyber City', 'state' => 'Haryana'],
            'golf course road' => ['city' => 'Gurugram', 'locality' => 'Golf Course Road', 'state' => 'Haryana'],
            'golf course ext' => ['city' => 'Gurugram', 'locality' => 'Golf Course Extension', 'state' => 'Haryana'],
            'sohna road' => ['city' => 'Gurugram', 'locality' => 'Sohna Road', 'state' => 'Haryana'],
            'noida' => ['city' => 'Noida', 'state' => 'Uttar Pradesh'],
            'greater noida' => ['city' => 'Greater Noida', 'state' => 'Uttar Pradesh'],
            'delhi' => ['city' => 'Delhi', 'state' => 'Delhi'],
            'new delhi' => ['city' => 'New Delhi', 'state' => 'Delhi'],
            'south delhi' => ['city' => 'South Delhi', 'state' => 'Delhi'],
            'dwarka' => ['city' => 'Delhi', 'locality' => 'Dwarka', 'state' => 'Delhi'],
            'saket' => ['city' => 'Delhi', 'locality' => 'Saket', 'state' => 'Delhi'],
            'rohini' => ['city' => 'Delhi', 'locality' => 'Rohini', 'state' => 'Delhi'],
            'bengaluru' => ['city' => 'Bengaluru', 'state' => 'Karnataka'],
            'bangalore' => ['city' => 'Bengaluru', 'state' => 'Karnataka'],
            'whitefield' => ['city' => 'Bengaluru', 'locality' => 'Whitefield', 'state' => 'Karnataka'],
            'koramangala' => ['city' => 'Bengaluru', 'locality' => 'Koramangala', 'state' => 'Karnataka'],
            'indiranagar' => ['city' => 'Bengaluru', 'locality' => 'Indiranagar', 'state' => 'Karnataka'],
            'bellandur' => ['city' => 'Bengaluru', 'locality' => 'Bellandur', 'state' => 'Karnataka'],
            'mumbai' => ['city' => 'Mumbai', 'state' => 'Maharashtra'],
            'navi mumbai' => ['city' => 'Navi Mumbai', 'state' => 'Maharashtra'],
            'thane' => ['city' => 'Thane', 'state' => 'Maharashtra'],
            'andheri' => ['city' => 'Mumbai', 'locality' => 'Andheri', 'state' => 'Maharashtra'],
            'bandra' => ['city' => 'Mumbai', 'locality' => 'Bandra', 'state' => 'Maharashtra'],
            'powai' => ['city' => 'Mumbai', 'locality' => 'Powai', 'state' => 'Maharashtra'],
            'pune' => ['city' => 'Pune', 'state' => 'Maharashtra'],
            'hinjewadi' => ['city' => 'Pune', 'locality' => 'Hinjewadi', 'state' => 'Maharashtra'],
            'wakad' => ['city' => 'Pune', 'locality' => 'Wakad', 'state' => 'Maharashtra'],
            'baner' => ['city' => 'Pune', 'locality' => 'Baner', 'state' => 'Maharashtra'],
            'hyderabad' => ['city' => 'Hyderabad', 'state' => 'Telangana'],
            'gachibowli' => ['city' => 'Hyderabad', 'locality' => 'Gachibowli', 'state' => 'Telangana'],
            'madhapur' => ['city' => 'Hyderabad', 'locality' => 'Madhapur', 'state' => 'Telangana'],
            'hitec city' => ['city' => 'Hyderabad', 'locality' => 'HITEC City', 'state' => 'Telangana'],
            'chennai' => ['city' => 'Chennai', 'state' => 'Tamil Nadu'],
            'omr' => ['city' => 'Chennai', 'locality' => 'OMR', 'state' => 'Tamil Nadu'],
            'kolkata' => ['city' => 'Kolkata', 'state' => 'West Bengal'],
            'salt lake' => ['city' => 'Kolkata', 'locality' => 'Salt Lake', 'state' => 'West Bengal'],
            'faridabad' => ['city' => 'Faridabad', 'state' => 'Haryana'],
            'ghaziabad' => ['city' => 'Ghaziabad', 'state' => 'Uttar Pradesh'],
            'indirapuram' => ['city' => 'Ghaziabad', 'locality' => 'Indirapuram', 'state' => 'Uttar Pradesh'],
            'vaishali' => ['city' => 'Ghaziabad', 'locality' => 'Vaishali', 'state' => 'Uttar Pradesh'],
            'jaipur' => ['city' => 'Jaipur', 'state' => 'Rajasthan'],
            'chandigarh' => ['city' => 'Chandigarh', 'state' => 'Chandigarh'],
            'mohali' => ['city' => 'Mohali', 'state' => 'Punjab'],
            'panchkula' => ['city' => 'Panchkula', 'state' => 'Haryana'],
            'lucknow' => ['city' => 'Lucknow', 'state' => 'Uttar Pradesh'],
            'kanpur' => ['city' => 'Kanpur', 'state' => 'Uttar Pradesh'],
            'ahmedabad' => ['city' => 'Ahmedabad', 'state' => 'Gujarat'],
            'surat' => ['city' => 'Surat', 'state' => 'Gujarat'],
            'indore' => ['city' => 'Indore', 'state' => 'Madhya Pradesh'],
            'bhopal' => ['city' => 'Bhopal', 'state' => 'Madhya Pradesh'],
            'patna' => ['city' => 'Patna', 'state' => 'Bihar'],
            'dehradun' => ['city' => 'Dehradun', 'state' => 'Uttarakhand'],
            'nagpur' => ['city' => 'Nagpur', 'state' => 'Maharashtra'],
            'coimbatore' => ['city' => 'Coimbatore', 'state' => 'Tamil Nadu'],
            'kochi' => ['city' => 'Kochi', 'state' => 'Kerala'],
            'goa' => ['city' => 'Goa', 'state' => 'Goa'],
        ];

        $matchedLocality = null;
        if (preg_match('/\b(sector|sec)\s*([0-9]+[a-z]?)\b/i', $query, $m)) {
            $matchedLocality = 'Sector ' . strtoupper($m[2]);
        }

        foreach ($indianLocations as $keyword => $info) {
            if (str_contains($q, $keyword)) {
                return [
                    'city' => $info['city'],
                    'state' => $info['state'],
                    'locality' => $matchedLocality ?: ($info['locality'] ?? null),
                ];
            }
        }

        if ($matchedLocality) {
            return [
                'city' => null,
                'locality' => $matchedLocality,
                'state' => null,
            ];
        }

        return null;
    }

    /**
     * Detect visitor's physical geographical location (City, State, Country, IP).
     */
    public function detectLocation(Request $request): array
    {
        $ip = static::getClientIp($request);

        $city = $request->header('CF-IPCity') 
            ?? $request->header('X-Geo-City') 
            ?? $request->header('X-AppEngine-City') 
            ?? null;

        $state = $request->header('CF-IPRegion') 
            ?? $request->header('CF-Region') 
            ?? $request->header('CF-Region-Code') 
            ?? $request->header('X-Geo-Region') 
            ?? null;

        $country = $request->header('CF-IPCountry') 
            ?? $request->header('X-Geo-Country') 
            ?? null;

        // 1. Client explicit input override if available
        if (empty($city) && $request->filled('detected_city')) {
            $city = trim($request->input('detected_city'));
            $state = trim($request->input('detected_state', ''));
        }

        // 2. Check cookies for user's explicit / GPS selected city
        if (empty($city)) {
            $cookieCity = $request->cookie('ur_user_city');
            $cookieLocality = $request->cookie('ur_user_locality');
            $cookieExact = $request->cookie('ur_user_exact_location');
            if ($cookieCity) {
                $city = $cookieCity;
                $state = $cookieLocality ?: $state;
            } elseif ($cookieExact) {
                $exactParts = array_map('trim', explode(',', $cookieExact));
                $city = end($exactParts);
                if (count($exactParts) > 1) {
                    $state = $exactParts[0];
                }
            }
        }

        // 3. Cloudflare Indian Region Code to City/State mapping (instant 0ms resolution)
        $cfRegion = strtoupper(trim((string) (
            $request->header('CF-IPRegion') 
            ?? $request->header('CF-Region') 
            ?? $request->header('CF-Region-Code') 
            ?? ''
        )));

        static $indiaRegionMap = [
            'DL' => ['city' => 'New Delhi', 'state' => 'Delhi'],
            'HR' => ['city' => 'Gurugram', 'state' => 'Haryana'],
            'UP' => ['city' => 'Noida', 'state' => 'Uttar Pradesh'],
            'KA' => ['city' => 'Bengaluru', 'state' => 'Karnataka'],
            'MH' => ['city' => 'Mumbai', 'state' => 'Maharashtra'],
            'TS' => ['city' => 'Hyderabad', 'state' => 'Telangana'],
            'TG' => ['city' => 'Hyderabad', 'state' => 'Telangana'],
            'TN' => ['city' => 'Chennai', 'state' => 'Tamil Nadu'],
            'WB' => ['city' => 'Kolkata', 'state' => 'West Bengal'],
            'RJ' => ['city' => 'Jaipur', 'state' => 'Rajasthan'],
            'GJ' => ['city' => 'Ahmedabad', 'state' => 'Gujarat'],
            'CH' => ['city' => 'Chandigarh', 'state' => 'Chandigarh'],
            'PB' => ['city' => 'Chandigarh', 'state' => 'Punjab'],
            'MP' => ['city' => 'Indore', 'state' => 'Madhya Pradesh'],
            'BR' => ['city' => 'Patna', 'state' => 'Bihar'],
            'UK' => ['city' => 'Dehradun', 'state' => 'Uttarakhand'],
            'UA' => ['city' => 'Dehradun', 'state' => 'Uttarakhand'],
            'AS' => ['city' => 'Guwahati', 'state' => 'Assam'],
            'KL' => ['city' => 'Kochi', 'state' => 'Kerala'],
            'AP' => ['city' => 'Visakhapatnam', 'state' => 'Andhra Pradesh'],
            'JH' => ['city' => 'Ranchi', 'state' => 'Jharkhand'],
            'OR' => ['city' => 'Bhubaneswar', 'state' => 'Odisha'],
            'OD' => ['city' => 'Bhubaneswar', 'state' => 'Odisha'],
            'GA' => ['city' => 'Panaji', 'state' => 'Goa'],
        ];

        if (empty($city) && !empty($cfRegion) && isset($indiaRegionMap[$cfRegion])) {
            $city = $indiaRegionMap[$cfRegion]['city'];
            $state = $state ?: $indiaRegionMap[$cfRegion]['state'];
            $country = 'India';
        }

        // 4. Fast GeoIP resolver if city or country is missing
        if ((empty($city) || empty($country)) && $ip) {
            $geo = static::resolveIpLocation($ip);
            if (!empty($geo)) {
                $city = $city ?: ($geo['city'] ?? null);
                $state = $state ?: ($geo['state'] ?? null);
                $country = $country ?: ($geo['country'] ?? 'India');
            }
        }

        if (empty($country)) {
            $country = 'India';
        }

        return [
            'city' => !empty($city) ? ucwords(strtolower(trim($city))) : null,
            'state' => !empty($state) ? ucwords(strtolower(trim($state))) : null,
            'country' => !empty($country) ? trim($country) : 'India',
            'ip' => $ip,
        ];
    }

    /**
     * Statically cache schema column lookups to avoid repeated database metadata queries.
     */
    protected static function hasColumn(string $table, string $column): bool
    {
        static $columnCache = [];
        $key = "{$table}.{$column}";
        if (array_key_exists($key, $columnCache)) {
            return $columnCache[$key];
        }

        return $columnCache[$key] = \Illuminate\Support\Facades\Cache::rememberForever("ur_col_exists_{$key}", function () use ($table, $column) {
            try {
                return \Illuminate\Support\Facades\Schema::hasColumn($table, $column);
            } catch (\Throwable) {
                return false;
            }
        });
    }

    /**
     * Resolve or create the current Visitor from the request.
     */
    public function resolveVisitor(Request $request): Visitor
    {
        $uuid = $request->input('visitor_uuid') ?: ($request->header('X-Visitor-UUID') ?: $request->cookie(self::COOKIE_NAME));

        if (!$uuid || !Str::isUuid($uuid)) {
            $uuid = (string) Str::uuid();
            Cookie::queue(self::COOKIE_NAME, $uuid, self::COOKIE_LIFETIME, '/', null, false, false);
        }

        $loc = $this->detectLocation($request);

        $initialValues = [
            'user_id' => auth()->id(),
            'first_seen_at' => now(),
            'last_seen_at' => now(),
            'first_landing_url' => Str::limit($request->fullUrl(), 500, ''),
            'last_url' => Str::limit($request->fullUrl(), 500, ''),
            'referrer' => Str::limit($request->header('referer'), 500, ''),
            'utm_source' => $request->input('utm_source'),
            'utm_medium' => $request->input('utm_medium'),
            'utm_campaign' => $request->input('utm_campaign'),
            'utm_term' => $request->input('utm_term'),
            'utm_content' => $request->input('utm_content'),
            'device_type' => $this->detectDevice($request),
            'browser' => $this->detectBrowser($request),
            'operating_system' => $this->detectOs($request),
            'country' => $loc['country'],
            'state' => $loc['state'],
            'city' => $loc['city'],
        ];

        if (static::hasColumn('visitors', 'ip_address')) {
            $initialValues['ip_address'] = $loc['ip'];
        }

        $visitor = Visitor::firstOrCreate(
            ['visitor_uuid' => $uuid],
            $initialValues
        );

        // Update location on returning visitors if previously unknown or placeholder
        $updates = [];
        $isPlaceholderCity = in_array($visitor->city, ['Location Detected', 'Detected Location', null, ''], true);
        if ($isPlaceholderCity && !empty($loc['city'])) {
            $updates['city'] = $loc['city'];
        }
        if ((empty($visitor->state) || $visitor->state === 'India') && !empty($loc['state'])) {
            $updates['state'] = $loc['state'];
        }
        if (empty($visitor->country) && !empty($loc['country'])) {
            $updates['country'] = $loc['country'];
        }
        if (static::hasColumn('visitors', 'ip_address') && (empty($visitor->ip_address) || $visitor->ip_address === '127.0.0.1') && !empty($loc['ip'])) {
            $updates['ip_address'] = $loc['ip'];
        }

        // Throttle last_seen_at updates to once every 60s to prevent locking on rapid page clicks
        if ($visitor->last_seen_at === null || $visitor->last_seen_at->diffInSeconds(now()) >= 60) {
            $updates['last_seen_at'] = now();
            $updates['last_url'] = Str::limit($request->fullUrl(), 500, '');
        }

        if (auth()->check() && !$visitor->user_id) {
            $updates['user_id'] = auth()->id();
        }

        if (!empty($updates)) {
            $visitor->update($updates);
        }

        return $visitor;
    }

    /**
     * Resolve or start a visitor session (30-minute inactivity threshold).
     */
    public function resolveSession(Visitor $visitor, Request $request): VisitorSession
    {
        $activeSession = $visitor->sessions()
            ->where('last_activity_at', '>=', now()->subMinutes(30))
            ->first();

        if ($activeSession) {
            // Throttle session update to prevent unnecessary database writes
            if ($activeSession->last_activity_at === null || $activeSession->last_activity_at->diffInSeconds(now()) >= 30) {
                $activeSession->update([
                    'last_activity_at' => now(),
                    'page_views' => $activeSession->page_views + 1,
                ]);
            }
            return $activeSession;
        }

        $sessionData = [
            'visitor_id' => $visitor->id,
            'session_uuid' => (string) Str::uuid(),
            'landing_page' => Str::limit($request->fullUrl(), 500, ''),
            'referrer' => Str::limit($request->header('referer'), 500, ''),
            'started_at' => now(),
            'last_activity_at' => now(),
            'page_views' => 1,
            'property_views' => 0,
            'device_type' => $this->detectDevice($request),
            'browser' => $this->detectBrowser($request),
            'operating_system' => $this->detectOs($request),
            'utm_source' => $request->input('utm_source'),
            'utm_medium' => $request->input('utm_medium'),
            'utm_campaign' => $request->input('utm_campaign'),
        ];

        if (static::hasColumn('visitor_sessions', 'ip_address')) {
            $sessionData['ip_address'] = $request->ip();
        }
        if (static::hasColumn('visitor_sessions', 'city')) {
            $sessionData['city'] = $visitor->city;
        }
        if (static::hasColumn('visitor_sessions', 'state')) {
            $sessionData['state'] = $visitor->state;
        }

        // Start new session
        $session = VisitorSession::create($sessionData);

        $visitor->increment('total_sessions');

        return $session;
    }

    /**
     * Record a search event with comprehensive visitor origin location & search criteria.
     */
    public function recordSearchEvent(Request $request, int $resultsCount = 0, array $customCriteria = []): ?VisitorEvent
    {
        $visitor = $request->attributes->get('visitor');
        if (!$visitor) {
            $visitor = $this->resolveVisitor($request);
        }

        $session = $request->attributes->get('visitor_session');
        if (!$session && $visitor) {
            $session = $this->resolveSession($visitor, $request);
        }

        $searchQuery = trim((string) ($customCriteria['search'] ?? $request->get('search', $request->get('q', $request->get('query', '')))));
        $district = trim((string) ($customCriteria['district'] ?? $request->get('district', $request->get('city', $request->get('location', '')))));
        $locality = trim((string) ($customCriteria['locality'] ?? $request->get('locality', '')));
        $state = trim((string) ($customCriteria['state'] ?? $request->get('state', '')));
        $type = trim((string) ($customCriteria['type'] ?? $request->get('type', '')));
        $subType = trim((string) ($customCriteria['sub_type'] ?? $request->get('sub_type', '')));
        $purpose = trim((string) ($customCriteria['purpose'] ?? $request->get('purpose', '')));
        $rooms = trim((string) ($customCriteria['rooms'] ?? $request->get('rooms', $request->get('bedrooms', ''))));
        $price = trim((string) ($customCriteria['price'] ?? $request->get('price', '')));
        $minPrice = $customCriteria['min_price'] ?? $request->get('min_price');
        $maxPrice = $customCriteria['max_price'] ?? $request->get('max_price');
        $nearMe = !empty($customCriteria['near_me']) || $request->boolean('near_me');
        $ownerOnly = !empty($customCriteria['owner']) || $request->boolean('owner');
        $sort = trim((string) ($customCriteria['sort'] ?? $request->get('sort', '')));
        $page = (int) ($request->get('page', 1));

        // Only record if at least one meaningful search parameter or filter is provided
        if (empty($searchQuery) && empty($district) && empty($locality) && empty($state) && empty($type) && empty($rooms) && empty($price) && empty($minPrice) && empty($maxPrice) && !$nearMe && empty($subType) && empty($purpose) && !$ownerOnly) {
            return null;
        }

        // Avoid logging duplicate search events within 15 seconds for the same visitor
        $recentDuplicate = VisitorEvent::where('visitor_id', $visitor->id)
            ->where('event_name', 'search')
            ->where('created_at', '>=', now()->subSeconds(15))
            ->latest()
            ->first();

        if ($recentDuplicate) {
            return $recentDuplicate;
        }

        $priceDisplay = $price;
        if (empty($priceDisplay) && ($minPrice || $maxPrice)) {
            $priceDisplay = '₹' . ($minPrice ?: '0') . ' - ₹' . ($maxPrice ?: 'Any');
        }

        $lat = $customCriteria['lat'] ?? $request->get('lat', $request->cookie('ur_user_lat'));
        $lng = $customCriteria['lng'] ?? $request->get('lng', $request->cookie('ur_user_lng'));
        $exactLocation = $customCriteria['exact_location'] 
            ?? $request->get('exact_location', $request->cookie('ur_user_exact_location'));

        $isNearMeSearch = $nearMe 
            || str_contains(strtolower($searchQuery), 'near me') 
            || str_contains(strtolower($searchQuery), 'near my location') 
            || str_contains(strtolower($searchQuery), 'near my');

        if ($isNearMeSearch) {
            $nearMe = true;
            if (empty($exactLocation)) {
                $locParts = array_filter([
                    $locality,
                    $district ?: ($visitor->city ?: null),
                    $state ?: ($visitor->state ?: null),
                ]);
                if (!empty($locParts)) {
                    $exactLocation = implode(', ', $locParts);
                }
            }
        }

        // Auto-detect Indian city/locality from search query if district is empty
        if (empty($district) && !empty($searchQuery)) {
            $parsed = static::parseIndianLocationFromQuery($searchQuery);
            if ($parsed) {
                $district = $parsed['city'] ?? $district;
                $locality = $locality ?: ($parsed['locality'] ?? null);
                $state = $state ?: ($parsed['state'] ?? null);
            }
        }

        // User explicit / GPS saved location from cookies if no explicit district in URL
        $userSelectedCity = $request->cookie('ur_user_city');
        $userSelectedLocality = $request->cookie('ur_user_locality');
        $userExactLocation = $request->cookie('ur_user_exact_location');

        $targetScope = 'all_india';
        if (!empty($district) || !empty($locality)) {
            $targetScope = 'filtered';
        } elseif ($nearMe) {
            $targetScope = 'near_me';
        } elseif (!empty($userSelectedCity)) {
            $targetScope = 'user_selected';
            $district = $userSelectedCity;
            $locality = $userSelectedLocality ?: $locality;
        }

        // Detect real origin location (Where user is physically searching from)
        $loc = $this->detectLocation($request);
        if ((empty($visitor->city) || in_array($visitor->city, ['Location Detected', 'Detected Location'], true)) && !empty($loc['city'])) {
            $visitor->updateQuietly([
                'city' => $loc['city'],
                'state' => $loc['state'],
                'country' => $loc['country'],
            ]);
        }

        $metadata = [
            'search' => $searchQuery ?: null,
            'district' => $district ?: null,
            'locality' => $locality ?: null,
            'state' => $state ?: null,
            'type' => $type ?: null,
            'sub_type' => $subType ?: null,
            'purpose' => $purpose ?: null,
            'rooms' => $rooms ?: null,
            'price' => $priceDisplay ?: null,
            'owner_only' => $ownerOnly ? true : null,
            'sort' => $sort ?: null,
            'page' => $page > 1 ? $page : null,
            'near_me' => $nearMe,
            'exact_location' => $exactLocation ?: ($userExactLocation ?? null),
            'target_scope' => $targetScope,
            'lat' => $lat ?: null,
            'lng' => $lng ?: null,
            'results_count' => $resultsCount,
            // Origin Location where the user is physically searching from:
            'visitor_city' => $loc['city'] ?: ($visitor->city ?: 'Detected Location'),
            'visitor_state' => $loc['state'] ?: ($visitor->state ?: 'India'),
            'visitor_country' => $loc['country'] ?: ($visitor->country ?: 'India'),
            'visitor_ip' => $loc['ip'] ?? static::getClientIp($request),
            'device_type' => $visitor->device_type ?? $this->detectDevice($request),
            'browser' => $visitor->browser ?? $this->detectBrowser($request),
        ];

        // Filter out null values
        $metadata = array_filter($metadata, fn($v) => $v !== null && $v !== '');

        return $this->recordEvent(
            $visitor,
            'search',
            null,
            $request->fullUrl(),
            $metadata,
            $session
        );
    }

    /**
     * Record an event for the current visitor.
     */
    public function recordEvent(
        Visitor $visitor,
        string $eventName,
        ?int $propertyId = null,
        ?string $pageUrl = null,
        ?array $metadata = null,
        ?VisitorSession $session = null
    ): VisitorEvent {
        $pageUrl = $pageUrl ?: request()->fullUrl();

        $event = VisitorEvent::create([
            'visitor_id' => $visitor->id,
            'session_id' => $session ? $session->id : null,
            'user_id' => auth()->id() ?? $visitor->user_id,
            'event_name' => $eventName,
            'property_id' => $propertyId,
            'page_url' => Str::limit($pageUrl, 500, ''),
            'metadata' => $metadata,
            'created_at' => now(),
        ]);

        // Engagement scoring
        $points = self::SCORING_RULES[$eventName] ?? 1;
        $visitor->addEngagementScore($points);
        $visitor->increment('total_page_views');

        // Property view specifics
        if ($eventName === 'property_view' && $propertyId) {
            $visitor->increment('total_property_views');
            if ($session) {
                $session->increment('property_views');
            }
            if (!$visitor->first_property_id) {
                $visitor->update(['first_property_id' => $propertyId]);
            }
            $visitor->update(['last_property_id' => $propertyId]);
        }

        return $event;
    }

    /**
     * Merge anonymous visitor history into authenticated user profile.
     */
    public function associateUser(User $user, ?string $visitorUuid): void
    {
        if (empty($visitorUuid)) return;

        $visitor = Visitor::where('visitor_uuid', $visitorUuid)->first();
        if ($visitor) {
            $visitor->update(['user_id' => $user->id]);
            VisitorEvent::where('visitor_id', $visitor->id)->whereNull('user_id')->update(['user_id' => $user->id]);
        }
    }

    /**
     * Detect device type.
     */
    public function detectDevice(Request $request): string
    {
        $ua = strtolower($request->header('User-Agent', ''));
        if (str_contains($ua, 'tablet') || str_contains($ua, 'ipad')) {
            return 'tablet';
        }
        if (str_contains($ua, 'mobile') || str_contains($ua, 'android') || str_contains($ua, 'iphone')) {
            return 'mobile';
        }
        return 'desktop';
    }

    /**
     * Detect browser.
     */
    public function detectBrowser(Request $request): string
    {
        $ua = $request->header('User-Agent', '');
        return match (true) {
            str_contains($ua, 'Edg/') => 'Edge',
            str_contains($ua, 'Chrome/') && !str_contains($ua, 'Edg/') => 'Chrome',
            str_contains($ua, 'Safari/') && !str_contains($ua, 'Chrome/') => 'Safari',
            str_contains($ua, 'Firefox/') => 'Firefox',
            str_contains($ua, 'MSIE') || str_contains($ua, 'Trident/') => 'Internet Explorer',
            default => 'Other Browser',
        };
    }

    /**
     * Detect operating system.
     */
    public function detectOs(Request $request): string
    {
        $ua = $request->header('User-Agent', '');
        return match (true) {
            str_contains($ua, 'Windows') => 'Windows',
            str_contains($ua, 'Android') => 'Android',
            str_contains($ua, 'iPhone') || str_contains($ua, 'iPad') => 'iOS',
            str_contains($ua, 'Macintosh') || str_contains($ua, 'Mac OS') => 'macOS',
            str_contains($ua, 'Linux') => 'Linux',
            default => 'Other OS',
        };
    }
}
