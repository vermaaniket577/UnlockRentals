<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisitorEvent extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'visitor_id',
        'session_id',
        'user_id',
        'event_name',
        'property_id',
        'city_id',
        'locality_id',
        'page_url',
        'metadata',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'created_at' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            if (empty($event->created_at)) {
                $event->created_at = now();
            }
        });
    }

    public function visitor(): BelongsTo
    {
        return $this->belongsTo(Visitor::class);
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(VisitorSession::class, 'session_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get human-friendly event label.
     */
    public function getLabelAttribute(): string
    {
        return match ($this->event_name) {
            'page_view' => 'Viewed Page',
            'property_view' => 'Viewed Property',
            'search' => 'Searched Properties',
            'filter_applied' => 'Filtered Properties',
            'whatsapp_clicked' => 'Clicked WhatsApp Chat',
            'contact_owner_clicked' => 'Clicked Contact Owner',
            'enquiry_started' => 'Started Enquiry',
            'enquiry_submitted' => 'Submitted Enquiry',
            'phone_revealed' => 'Unlocked Owner Phone',
            'visit_scheduled' => 'Booked Property Visit',
            'favorite_property' => 'Saved Property to Favorites',
            'share_property' => 'Shared Property Link',
            'exit_intent_shown' => 'Exit Intent Modal Shown',
            'exit_intent_submitted' => 'Submitted Exit Intent Form',
            'login' => 'Signed In',
            'registration' => 'Registered Account',
            'auth_page_viewed' => 'Visited Sign In Page',
            'auth_abandoned' => 'Abandoned Sign In (High Intent)',
            'auth_exit_intent_shown' => 'Sign In Exit Offer Shown',
            'auth_exit_lead_submitted' => 'Recovered Sign In Lead',
            'auth_exit_whatsapp_clicked' => 'Clicked WhatsApp on Sign In Exit',
            default => ucwords(str_replace('_', ' ', $this->event_name)),
        };
    }

    /**
     * Get the visitor physical origin city (where user is searching from).
     */
    public function getOriginCity(): string
    {
        $city = $this->metadata['visitor_city'] ?? null;
        if (empty($city) || in_array($city, ['Location Detected', 'Detected Location'], true)) {
            $city = $this->visitor?->city;
        }

        // On-the-fly GeoIP fallback for legacy or unpopulated records
        if (empty($city) || in_array($city, ['Location Detected', 'Detected Location'], true)) {
            $ip = $this->getOriginIp();
            if ($ip) {
                $geo = \App\Services\VisitorTrackingService::resolveIpLocation($ip);
                if (!empty($geo['city'])) {
                    $city = $geo['city'];
                    if ($this->visitor && in_array($this->visitor->city, ['Location Detected', 'Detected Location', null, ''], true)) {
                        try {
                            $this->visitor->updateQuietly([
                                'city' => $geo['city'],
                                'state' => $geo['state'] ?? null,
                                'country' => $geo['country'] ?? 'India',
                            ]);
                        } catch (\Throwable $e) {}
                    }
                }
            }
        }

        return !empty($city) ? ucwords(strtolower(trim($city))) : 'Detected Location';
    }

    /**
     * Get the visitor physical origin state.
     */
    public function getOriginState(): string
    {
        $state = $this->metadata['visitor_state'] ?? null;
        if (empty($state) || $state === 'India') {
            $state = $this->visitor?->state;
        }

        // On-the-fly GeoIP fallback for legacy or unpopulated records
        if (empty($state) || $state === 'India') {
            $ip = $this->getOriginIp();
            if ($ip) {
                $geo = \App\Services\VisitorTrackingService::resolveIpLocation($ip);
                if (!empty($geo['state'])) {
                    $state = $geo['state'];
                } elseif (!empty($geo['country']) && $geo['country'] !== 'India') {
                    $state = $geo['country'];
                }
            }
        }

        return !empty($state) ? ucwords(strtolower(trim($state))) : 'India';
    }

    /**
     * Get the visitor physical origin country.
     */
    public function getOriginCountry(): string
    {
        $country = $this->metadata['visitor_country'] ?? $this->visitor?->country ?? null;
        if (empty($country) || $country === 'India') {
            $ip = $this->getOriginIp();
            if ($ip) {
                $geo = \App\Services\VisitorTrackingService::resolveIpLocation($ip);
                if (!empty($geo['country'])) {
                    $country = $geo['country'];
                }
            }
        }
        return $country ?: 'India';
    }

    /**
     * Format property type key into a clean readable label.
     */
    public function formatTypeLabel(?string $type): string
    {
        if (empty($type)) return 'All Types';
        return match (strtolower(trim($type))) {
            'house' => 'Houses & Villas',
            'apartment', 'flat' => 'Flats & Apartments',
            'pg', 'pg-hostel' => 'PGs & Hostels',
            'commercial', 'shop' => 'Shops & Commercial',
            'plot' => 'Plots & Land',
            'builder-floor', 'builder_floor' => 'Builder Floors',
            'villa' => 'Luxury Villas',
            default => ucfirst(str_replace('-', ' ', $type)),
        };
    }

    /**
     * Get the visitor physical origin IP address.
     */
    public function getOriginIp(): ?string
    {
        return $this->metadata['visitor_ip'] ?? $this->visitor?->ip_address ?? null;
    }

    /**
     * Get the visitor device platform.
     */
    public function getOriginDevice(): string
    {
        return $this->metadata['device_type'] ?? $this->visitor?->device_type ?? 'desktop';
    }

    /**
     * Get the visitor browser.
     */
    public function getOriginBrowser(): string
    {
        return $this->metadata['browser'] ?? $this->visitor?->browser ?? 'Browser';
    }

    /**
     * Check if this search is a "Near Me" / GPS search.
     */
    public function isNearMeSearch(): bool
    {
        $meta = $this->metadata ?? [];
        if (!empty($meta['near_me'])) {
            return true;
        }
        $search = strtolower($meta['search'] ?? '');
        if (str_contains($search, 'near me') || str_contains($search, 'near my location') || str_contains($search, 'near my')) {
            return true;
        }
        $url = strtolower($this->page_url ?? '');
        if (str_contains($url, '-near-me') || str_contains($url, '-near-my-location') || str_contains($url, 'near_me=1') || str_contains($url, 'near_me=true')) {
            return true;
        }
        return !empty($meta['lat']) && !empty($meta['lng']);
    }

    /**
     * Get the exact physical / GPS location the user was searching property for.
     */
    public function getNearMeExactLocation(): string
    {
        $meta = $this->metadata ?? [];

        // 1. Explicit exact location recorded in metadata
        if (!empty($meta['exact_location'])) {
            return trim((string) $meta['exact_location']);
        }

        // 2. Specific locality + city/district
        $locality = !empty($meta['locality']) ? ucwords(str_replace('-', ' ', $meta['locality'])) : null;
        $district = !empty($meta['district']) ? ucwords(str_replace('-', ' ', $meta['district'])) : null;
        $state = !empty($meta['state']) ? ucwords(str_replace('-', ' ', $meta['state'])) : null;

        $parts = array_filter([$locality, $district]);
        if (!empty($parts)) {
            if ($state && !in_array($state, $parts)) {
                $parts[] = $state;
            }
            return implode(', ', $parts);
        }

        // 3. Coordinates formatted if present
        $lat = $meta['lat'] ?? null;
        $lng = $meta['lng'] ?? null;
        $coordsStr = ($lat && $lng) ? 'GPS: ' . round((float)$lat, 4) . ', ' . round((float)$lng, 4) : null;

        // 4. Physical visitor origin location detected via IP / GeoIP
        $originCity = $this->getOriginCity();
        $originState = $this->getOriginState();

        $locDesc = [];
        if (!empty($originCity) && !in_array($originCity, ['Detected Location', 'Location Detected'], true)) {
            $locDesc[] = $originCity;
        }
        if (!empty($originState) && $originState !== 'India') {
            $locDesc[] = $originState;
        }

        if (!empty($locDesc)) {
            $base = implode(', ', $locDesc);
            return $coordsStr ? "{$base} ({$coordsStr})" : $base;
        }

        return $coordsStr ?: 'Detected Device Location';
    }

    /**
     * Get human-readable target location & criteria searched for.
     */
    public function getTargetLocationDisplay(): string
    {
        $meta = $this->metadata ?? [];
        $parts = [];

        $distVal = $meta['district'] ?? null;
        $locVal = $meta['locality'] ?? null;
        $stateVal = $meta['state'] ?? null;

        // If missing from metadata, inspect page_url query parameters
        if (empty($distVal) && empty($locVal) && !empty($this->page_url)) {
            $queryStr = parse_url($this->page_url, PHP_URL_QUERY);
            if ($queryStr) {
                parse_str($queryStr, $urlParams);
                $distVal = $urlParams['district'] ?? $urlParams['city'] ?? $urlParams['location'] ?? null;
                $locVal = $urlParams['locality'] ?? null;
            }
        }

        if (!empty($locVal)) {
            $parts[] = ucwords(str_replace('-', ' ', $locVal));
        }
        if (!empty($distVal)) {
            $cleanDist = ucwords(str_replace('-', ' ', $distVal));
            if (!in_array($cleanDist, $parts)) {
                $parts[] = $cleanDist;
            }
        } elseif (!empty($stateVal)) {
            $parts[] = ucwords(str_replace('-', ' ', $stateVal));
        }

        // If still empty, check if free text query has an Indian city or locality
        if (empty($parts)) {
            $searchQueryText = $meta['search'] ?? null;
            if (empty($searchQueryText) && !empty($this->page_url)) {
                $queryStr = parse_url($this->page_url, PHP_URL_QUERY);
                if ($queryStr) {
                    parse_str($queryStr, $urlParams);
                    $searchQueryText = $urlParams['search'] ?? $urlParams['q'] ?? null;
                }
            }
            if (!empty($searchQueryText)) {
                $parsed = \App\Services\VisitorTrackingService::parseIndianLocationFromQuery($searchQueryText);
                if ($parsed) {
                    if (!empty($parsed['locality'])) {
                        $parts[] = $parsed['locality'];
                    }
                    if (!empty($parsed['city']) && !in_array($parsed['city'], $parts)) {
                        $parts[] = $parsed['city'];
                    }
                }
            }
        }

        // If explicit locality or district target is found
        if (!empty($parts)) {
            $locStr = implode(', ', $parts);
            $typeVal = $meta['type'] ?? null;
            if (!empty($typeVal) && $typeVal !== 'all') {
                return "{$locStr} · " . $this->formatTypeLabel($typeVal);
            }
            return $locStr;
        }

        // Near Me GPS search
        if ($this->isNearMeSearch()) {
            $exactLoc = $this->getNearMeExactLocation();
            if (!empty($meta['search'])) {
                $cleaned = trim((string) $meta['search'], " \t\n\r\0\x0B\"'");
                return "{$cleaned} · {$exactLoc}";
            }
            return "Near Me ({$exactLoc})";
        }

        // Free text search query
        if (!empty($meta['search'])) {
            return trim((string) $meta['search'], " \t\n\r\0\x0B\"'");
        }

        // Type / Category based search without city filter (Scope: Pan-India)
        $typeVal = $meta['type'] ?? null;
        if (empty($typeVal) && !empty($this->page_url)) {
            $queryStr = parse_url($this->page_url, PHP_URL_QUERY);
            if ($queryStr) {
                parse_str($queryStr, $urlParams);
                $typeVal = $urlParams['type'] ?? null;
            }
        }

        if (!empty($typeVal) && $typeVal !== 'all') {
            $typeLabel = $this->formatTypeLabel($typeVal);
            $purpose = ($meta['purpose'] ?? '') === 'buy' ? 'For Sale' : 'For Rent';
            return "{$typeLabel} {$purpose} (All Cities · Pan-India)";
        }

        if (!empty($meta['near_me'])) {
            return 'Near Me (' . $this->getNearMeExactLocation() . ')';
        }

        return 'All Rental Listings (All Cities · Pan-India)';
    }

    /**
     * Get structured list of applied search filters & target location badges.
     */
    public function getSearchCriteriaList(): array
    {
        $criteria = [];
        $meta = $this->metadata ?? [];
        $urlParams = [];

        if (!empty($this->page_url)) {
            $queryStr = parse_url($this->page_url, PHP_URL_QUERY);
            if ($queryStr) {
                parse_str($queryStr, $urlParams);
            }
        }

        $district = $meta['district'] ?? $urlParams['district'] ?? $urlParams['city'] ?? $urlParams['location'] ?? null;
        $locality = $meta['locality'] ?? $urlParams['locality'] ?? null;
        $searchQueryVal = $meta['search'] ?? $urlParams['search'] ?? $urlParams['q'] ?? null;

        // Auto-detect Indian city if district not explicitly present
        if (empty($district) && !empty($searchQueryVal)) {
            $parsed = \App\Services\VisitorTrackingService::parseIndianLocationFromQuery($searchQueryVal);
            if ($parsed) {
                if (!empty($parsed['city'])) $district = $parsed['city'];
                if (empty($locality) && !empty($parsed['locality'])) $locality = $parsed['locality'];
            }
        }

        $type = $meta['type'] ?? $urlParams['type'] ?? null;
        $rooms = $meta['rooms'] ?? $urlParams['rooms'] ?? $urlParams['bedrooms'] ?? null;
        $sort = $meta['sort'] ?? $urlParams['sort'] ?? null;
        $isOwner = !empty($meta['owner_only']) || !empty($urlParams['owner']);

        if (!empty($district)) {
            $criteria[] = ['label' => 'Target City', 'value' => ucwords(str_replace('-', ' ', $district)), 'badge' => 'bg-blue-50 text-blue-700 border-blue-200'];
        } else {
            $criteria[] = ['label' => 'Target Scope', 'value' => 'All Cities (Pan-India)', 'badge' => 'bg-slate-100 text-slate-700 border-slate-200'];
        }

        if (!empty($locality)) {
            $criteria[] = ['label' => 'Locality', 'value' => ucwords(str_replace('-', ' ', $locality)), 'badge' => 'bg-indigo-50 text-indigo-700 border-indigo-200'];
        }
        if (!empty($meta['search'])) {
            $criteria[] = ['label' => 'Query', 'value' => $meta['search'], 'badge' => 'bg-amber-50 text-amber-700 border-amber-200'];
        }
        if (!empty($type) && $type !== 'all') {
            $criteria[] = ['label' => 'Property Type', 'value' => $this->formatTypeLabel($type), 'badge' => 'bg-purple-50 text-purple-700 border-purple-200'];
        }
        if (!empty($rooms) && $rooms !== 'any') {
            $criteria[] = ['label' => 'BHK', 'value' => strtoupper($rooms), 'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200'];
        }
        if (!empty($meta['price']) && $meta['price'] !== 'any') {
            $criteria[] = ['label' => 'Budget', 'value' => $meta['price'], 'badge' => 'bg-rose-50 text-rose-700 border-rose-200'];
        }
        if ($isOwner) {
            $criteria[] = ['label' => 'Filter', 'value' => 'Direct Owner Verified', 'badge' => 'bg-cyan-50 text-cyan-800 border-cyan-200 font-bold'];
        }
        if ($sort) {
            $sortLabel = match ($sort) {
                'price_low' => 'Lowest Price First',
                'price_high' => 'Highest Price First',
                'distance' => 'Nearest to Me',
                'latest' => 'Latest First',
                default => ucwords(str_replace('_', ' ', $sort)),
            };
            $criteria[] = ['label' => 'Sort', 'value' => $sortLabel, 'badge' => 'bg-slate-50 text-slate-600 border-slate-200'];
        }
        if ($this->isNearMeSearch()) {
            $exactLoc = $this->getNearMeExactLocation();
            $criteria[] = ['label' => 'Searching Near (GPS)', 'value' => $exactLoc, 'badge' => 'bg-emerald-50 text-emerald-800 border-emerald-300 font-extrabold shadow-2xs'];
        }

        return $criteria;
    }

    /**
     * Results found count.
     */
    public function getResultsCount(): int
    {
        return (int) ($this->metadata['results_count'] ?? 0);
    }
}
