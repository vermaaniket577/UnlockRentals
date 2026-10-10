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
     * Detect visitor's physical geographical location (City, State, Country, IP).
     */
    public function detectLocation(Request $request): array
    {
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
            ?? 'India';

        $ip = $request->ip();

        // If city not in proxy headers and IP is public, look up via fast GeoIP service
        if (empty($city) && $ip && filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            $cacheKey = 'ur_geoip_' . md5($ip);
            $geo = \Illuminate\Support\Facades\Cache::remember($cacheKey, 86400 * 30, function () use ($ip) {
                try {
                    $ctx = stream_context_create([
                        'http' => [
                            'timeout' => 1.5,
                            'header'  => "User-Agent: UnlockRentals-GeoIP/1.0\r\n"
                        ]
                    ]);
                    $json = @file_get_contents("http://ip-api.com/json/{$ip}?fields=status,country,regionName,city", false, $ctx);
                    if ($json) {
                        $res = json_decode($json, true);
                        if (($res['status'] ?? '') === 'success') {
                            return [
                                'city' => $res['city'] ?? null,
                                'state' => $res['regionName'] ?? null,
                                'country' => $res['country'] ?? 'India',
                            ];
                        }
                    }
                } catch (\Throwable $e) {}
                return null;
            });

            if ($geo) {
                $city = $geo['city'] ?? $city;
                $state = $geo['state'] ?? $state;
                $country = $geo['country'] ?? $country;
            }
        }

        // Client explicit override if available
        if (empty($city) && $request->filled('detected_city')) {
            $city = trim($request->input('detected_city'));
            $state = trim($request->input('detected_state', ''));
        }

        return [
            'city' => !empty($city) ? ucwords(strtolower(trim($city))) : null,
            'state' => !empty($state) ? ucwords(strtolower(trim($state))) : null,
            'country' => !empty($country) ? trim($country) : 'India',
            'ip' => $ip,
        ];
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

        if (\Illuminate\Support\Facades\Schema::hasColumn('visitors', 'ip_address')) {
            $initialValues['ip_address'] = $loc['ip'];
        }

        $visitor = Visitor::firstOrCreate(
            ['visitor_uuid' => $uuid],
            $initialValues
        );

        // Update location on returning visitors if previously unknown
        $updates = [];
        if (empty($visitor->city) && !empty($loc['city'])) {
            $updates['city'] = $loc['city'];
        }
        if (empty($visitor->state) && !empty($loc['state'])) {
            $updates['state'] = $loc['state'];
        }
        if (empty($visitor->country) && !empty($loc['country'])) {
            $updates['country'] = $loc['country'];
        }
        if (\Illuminate\Support\Facades\Schema::hasColumn('visitors', 'ip_address') && empty($visitor->ip_address) && !empty($loc['ip'])) {
            $updates['ip_address'] = $loc['ip'];
        }
        $updates['last_seen_at'] = now();
        $updates['last_url'] = Str::limit($request->fullUrl(), 500, '');

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
            $activeSession->update([
                'last_activity_at' => now(),
                'page_views' => $activeSession->page_views + 1,
            ]);
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

        if (\Illuminate\Support\Facades\Schema::hasColumn('visitor_sessions', 'ip_address')) {
            $sessionData['ip_address'] = $request->ip();
        }
        if (\Illuminate\Support\Facades\Schema::hasColumn('visitor_sessions', 'city')) {
            $sessionData['city'] = $visitor->city;
        }
        if (\Illuminate\Support\Facades\Schema::hasColumn('visitor_sessions', 'state')) {
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

        $searchQuery = trim((string) ($customCriteria['search'] ?? $request->get('search', '')));
        $district = trim((string) ($customCriteria['district'] ?? $request->get('district', '')));
        $locality = trim((string) ($customCriteria['locality'] ?? $request->get('locality', '')));
        $state = trim((string) ($customCriteria['state'] ?? $request->get('state', '')));
        $type = trim((string) ($customCriteria['type'] ?? $request->get('type', '')));
        $rooms = trim((string) ($customCriteria['rooms'] ?? $request->get('rooms', $request->get('bedrooms', ''))));
        $price = trim((string) ($customCriteria['price'] ?? $request->get('price', '')));
        $minPrice = $customCriteria['min_price'] ?? $request->get('min_price');
        $maxPrice = $customCriteria['max_price'] ?? $request->get('max_price');
        $nearMe = !empty($customCriteria['near_me']) || $request->boolean('near_me');

        // Only record if at least one meaningful search parameter is provided
        if (empty($searchQuery) && empty($district) && empty($locality) && empty($state) && empty($type) && empty($rooms) && empty($price) && empty($minPrice) && empty($maxPrice) && !$nearMe) {
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

        $metadata = [
            'search' => $searchQuery ?: null,
            'district' => $district ?: null,
            'locality' => $locality ?: null,
            'state' => $state ?: null,
            'type' => $type ?: null,
            'rooms' => $rooms ?: null,
            'price' => $priceDisplay ?: null,
            'near_me' => $nearMe,
            'results_count' => $resultsCount,
            // Origin Location where the user is physically searching from:
            'visitor_city' => $visitor->city ?: 'Location Detected',
            'visitor_state' => $visitor->state ?: 'India',
            'visitor_country' => $visitor->country ?: 'India',
            'visitor_ip' => $request->ip(),
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
