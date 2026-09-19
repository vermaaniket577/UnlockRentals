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
    ];

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

        $visitor = Visitor::firstOrCreate(
            ['visitor_uuid' => $uuid],
            [
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
            ]
        );

        // Associate user if logged in and not yet associated
        if (auth()->check() && !$visitor->user_id) {
            $visitor->update(['user_id' => auth()->id()]);
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

        // Start new session
        $session = VisitorSession::create([
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
        ]);

        $visitor->increment('total_sessions');

        return $session;
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
