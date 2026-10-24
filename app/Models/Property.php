<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'external_id',
        'description',
        'type',
        'purpose',
        'price',
        'price_period',
        'state',
        'location',
        'locality',
        'address',
        'contact_phone',
        'alternate_phone',
        'latitude',
        'longitude',
        'bedrooms',
        'bathrooms',
        'area_sqft',
        'furnishing',
        'video_path',
        'status',
        'is_featured',
        'approved_at',
        'is_booked',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'is_featured' => 'boolean',
            'approved_at' => 'datetime',
            'is_booked' => 'boolean',
        ];
    }

    /**
     * Boot the model and auto-generate slugs.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($property) {
            if (empty($property->slug)) {
                $cleanTitle = self::cleanUrlFromText($property->title, true) ?: 'Property';
                $property->slug = Str::slug($cleanTitle) . '-' . Str::random(6);
            }
        });
    }

    /**
     * Map of Indian cities and states to resolve city and state from any URL or string.
     */
    public static function extractCityAndStateFromUrl(?string $url): array
    {
        if (empty($url)) {
            return ['Delhi', 'Delhi'];
        }

        $urlLower = strtolower($url);

        $map = [
            'greater noida'  => ['Greater Noida', 'Uttar Pradesh'],
            'noida'          => ['Noida', 'Uttar Pradesh'],
            'gurgaon'        => ['Gurgaon', 'Haryana'],
            'gurugram'       => ['Gurgaon', 'Haryana'],
            'faridabad'      => ['Faridabad', 'Haryana'],
            'ghaziabad'      => ['Ghaziabad', 'Uttar Pradesh'],
            'new delhi'      => ['New Delhi', 'Delhi'],
            'delhi'          => ['Delhi', 'Delhi'],
            'navi mumbai'    => ['Navi Mumbai', 'Maharashtra'],
            'mumbai'         => ['Mumbai', 'Maharashtra'],
            'thane'          => ['Thane', 'Maharashtra'],
            'pune'           => ['Pune', 'Maharashtra'],
            'bangalore'      => ['Bangalore', 'Karnataka'],
            'bengaluru'      => ['Bangalore', 'Karnataka'],
            'hyderabad'      => ['Hyderabad', 'Telangana'],
            'secunderabad'   => ['Secunderabad', 'Telangana'],
            'chennai'        => ['Chennai', 'Tamil Nadu'],
            'kolkata'        => ['Kolkata', 'West Bengal'],
            'ahmedabad'      => ['Ahmedabad', 'Gujarat'],
            'surat'          => ['Surat', 'Gujarat'],
            'vadodara'       => ['Vadodara', 'Gujarat'],
            'jaipur'         => ['Jaipur', 'Rajasthan'],
            'udaipur'        => ['Udaipur', 'Rajasthan'],
            'jodhpur'        => ['Jodhpur', 'Rajasthan'],
            'indore'         => ['Indore', 'Madhya Pradesh'],
            'bhopal'         => ['Bhopal', 'Madhya Pradesh'],
            'gwalior'        => ['Gwalior', 'Madhya Pradesh'],
            'jabalpur'       => ['Jabalpur', 'Madhya Pradesh'],
            'lucknow'        => ['Lucknow', 'Uttar Pradesh'],
            'kanpur'         => ['Kanpur', 'Uttar Pradesh'],
            'varanasi'       => ['Varanasi', 'Uttar Pradesh'],
            'agra'           => ['Agra', 'Uttar Pradesh'],
            'chandigarh'     => ['Chandigarh', 'Punjab'],
            'mohali'         => ['Mohali', 'Punjab'],
            'ludhiana'       => ['Ludhiana', 'Punjab'],
            'patna'          => ['Patna', 'Bihar'],
            'bhubaneswar'    => ['Bhubaneswar', 'Odisha'],
            'cuttack'        => ['Cuttack', 'Odisha'],
            'kochi'          => ['Kochi', 'Kerala'],
            'trivandrum'     => ['Trivandrum', 'Kerala'],
            'dehradun'       => ['Dehradun', 'Uttarakhand'],
            'goa'            => ['Goa', 'Goa'],
            'panipat'        => ['Panipat', 'Haryana'],
            'nagpur'         => ['Nagpur', 'Maharashtra'],
            'nashik'         => ['Nashik', 'Maharashtra'],
            'mysore'         => ['Mysore', 'Karnataka'],
            'coimbatore'     => ['Coimbatore', 'Tamil Nadu'],
        ];

        // Check 99acres city ID parameters (e.g. city=269, city=38)
        $idMap = [
            '269' => ['Hyderabad', 'Telangana'],
            '38'  => ['Hyderabad', 'Telangana'],
            '142' => ['Indore', 'Madhya Pradesh'],
            '20'  => ['Bangalore', 'Karnataka'],
            '1'   => ['Delhi', 'Delhi'],
            '2'   => ['Mumbai', 'Maharashtra'],
            '18'  => ['Gurgaon', 'Haryana'],
            '19'  => ['Noida', 'Uttar Pradesh'],
            '21'  => ['Pune', 'Maharashtra'],
            '32'  => ['Chennai', 'Tamil Nadu'],
            '25'  => ['Kolkata', 'West Bengal'],
            '176' => ['Jaipur', 'Rajasthan'],
            '45'  => ['Ahmedabad', 'Gujarat'],
        ];

        if (preg_match('/(?:city=|city_id=)(\d+)/i', $url, $m) && isset($idMap[$m[1]])) {
            return $idMap[$m[1]];
        }
        if (ctype_digit(trim($url)) && isset($idMap[trim($url)])) {
            return $idMap[trim($url)];
        }

        foreach ($map as $key => $pair) {
            if (str_contains($urlLower, $key)) {
                return $pair;
            }
        }

        // Check keyword parameter in URL (e.g. keyword=hyderabad)
        if (preg_match('/(?:keyword=)([a-z0-9_-]+)/i', $url, $m)) {
            $kw = strtolower($m[1]);
            foreach ($map as $key => $pair) {
                if (str_contains($kw, $key)) {
                    return $pair;
                }
            }
        }

        // Try extracting city from regex patterns like "-in-noida-ffid"
        if (preg_match('/-in-([a-z0-9-]+)(?:-ffid|\?|\/|$)/i', $url, $m)) {
            $candidate = ucwords(str_replace('-', ' ', $m[1]));
            return [$candidate, 'India'];
        }

        return ['Delhi', 'Delhi'];
    }

    /**
     * Intelligently extract purpose (buy or rent) from a property listing URL.
     */
    public static function extractPurposeFromUrl(?string $url): string
    {
        if (empty($url)) {
            return 'rent';
        }

        $urlLower = strtolower($url);

        // Check for buy/sale markers
        if (
            str_contains($urlLower, '/buy/') ||
            str_contains($urlLower, 'for-sale') ||
            str_contains($urlLower, 'for-buy') ||
            str_contains($urlLower, 'preference=s') ||
            str_contains($urlLower, 'preference=buy') ||
            str_contains($urlLower, 'property/buy')
        ) {
            return 'buy';
        }

        // Check for PG / Hostel
        if (
            str_contains($urlLower, '/pg') ||
            str_contains($urlLower, 'pg-in-') ||
            str_contains($urlLower, 'paying-guest') ||
            str_contains($urlLower, 'hostel')
        ) {
            return 'rent';
        }

        return 'rent';
    }

    /**
     * Intelligently extract property type from a property listing URL.
     */
    public static function extractPropertyTypeFromUrl(?string $url): string
    {
        if (empty($url)) {
            return 'all';
        }

        $urlLower = strtolower($url);

        if (str_contains($urlLower, '1-rk') || str_contains($urlLower, '1rk') || str_contains($urlLower, 'studio')) {
            return '1rk';
        }
        if (str_contains($urlLower, '1-bhk') || str_contains($urlLower, '1bhk')) {
            return '1bhk';
        }
        if (str_contains($urlLower, '2-bhk') || str_contains($urlLower, '2bhk')) {
            return '2bhk';
        }
        if (str_contains($urlLower, '3-bhk') || str_contains($urlLower, '3bhk')) {
            return '3bhk';
        }
        if (str_contains($urlLower, 'commercial-shops') || str_contains($urlLower, 'shop') || str_contains($urlLower, 'retail')) {
            return 'commercial-shops';
        }
        if (str_contains($urlLower, 'plot') || str_contains($urlLower, 'land') || str_contains($urlLower, 'residential-land')) {
            return 'plot';
        }
        if (str_contains($urlLower, 'pg') || str_contains($urlLower, 'paying-guest') || str_contains($urlLower, 'hostel')) {
            return 'pg-hostel';
        }
        if (str_contains($urlLower, 'builder-floor')) {
            return 'builder-floor';
        }
        if (str_contains($urlLower, 'flat') || str_contains($urlLower, 'apartment')) {
            return 'flat';
        }
        if (str_contains($urlLower, 'villa') || str_contains($urlLower, 'independent-house') || str_contains($urlLower, 'house')) {
            return 'house';
        }
        if (str_contains($urlLower, 'commercial') || str_contains($urlLower, 'office')) {
            return 'commercial';
        }

        return 'all';
    }

    /**
     * Apply or update pagination page number on any 99acres or search URL.
     */
    public static function applyPageToUrl(?string $url, int $page = 1): string
    {
        if (empty($url)) {
            return '';
        }

        if ($page <= 1) {
            $cleaned = preg_replace('/-page-\d+/', '', $url);
            return rtrim(preg_replace('/([?&])page=\d+&?/', '$1', $cleaned), '?&');
        }

        if (str_contains($url, '-ffid')) {
            if (str_contains($url, '-page-')) {
                return preg_replace('/-page-\d+/', "-page-{$page}", $url);
            }
            return str_replace('-ffid', "-ffid-page-{$page}", $url);
        }

        if (str_contains($url, 'page=')) {
            return preg_replace('/([?&]page=)\d+/', "\${1}{$page}", $url);
        }

        $sep = str_contains($url, '?') ? '&' : '?';
        return "{$url}{$sep}page={$page}";
    }

    /**
     * Bulletproof sanitization to remove all 99acres URLs, domains, and artifacts from any text.
     */
    public static function cleanUrlFromText(?string $text, bool $extractCity = false): string
    {
        if (empty($text)) {
            return '';
        }

        $trimmed = trim($text);

        // If the entire string is basically a URL
        if (preg_match('/^(https?:\/\/|www\.)/i', $trimmed) || str_starts_with($trimmed, 'http')) {
            if ($extractCity) {
                [$city] = self::extractCityAndStateFromUrl($trimmed);
                return $city;
            }
            return '';
        }

        // Strip out 99acres URLs or generic URLs and optionally replace with resolved city
        $cleaned = preg_replace_callback('/(https?:\/\/[^\s,"\'<>]+|www\.[^\s,"\'<>]+|[\w-]*99acres[\w\.-]*)/i', function($m) use ($text, $extractCity) {
            $url = rtrim($m[0], '.,;:!?');
            $trailingPunct = substr($m[0], strlen($url));
            if ($extractCity) {
                [$city] = self::extractCityAndStateFromUrl($url);
                if (!empty($city) && !str_contains(strtolower($text), strtolower($city))) {
                    return $city . $trailingPunct;
                }
            }
            return '' . $trailingPunct;
        }, $text);

        // Clean punctuation artifacts e.g. ", .", ", ,", duplicate spaces, trailing commas
        $cleaned = preg_replace('/\s*,\s*\./', '.', $cleaned);
        $cleaned = preg_replace('/\s*,\s*,+/', ', ', $cleaned);
        $cleaned = preg_replace('/\s{2,}/', ' ', $cleaned);
        $cleaned = preg_replace('/,\s*$/', '', $cleaned);
        $cleaned = trim($cleaned, " \t\n\r\0\x0B,-/|");

        return $cleaned;
    }

    /**
     * Accessor for location: never allow a URL or 99acres reference.
     */
    public function getLocationAttribute($value): string
    {
        $cleaned = self::cleanUrlFromText($value, true);
        if (empty($cleaned) || str_starts_with(strtolower($cleaned), 'http') || str_contains(strtolower($cleaned), '99acres')) {
            [$city] = self::extractCityAndStateFromUrl($value);
            return $city ?: 'Delhi';
        }
        return $cleaned;
    }

    /**
     * Mutator for location.
     */
    public function setLocationAttribute($value): void
    {
        $this->attributes['location'] = self::cleanUrlFromText($value, true) ?: 'Delhi';
    }

    /**
     * Accessor for locality: clean and remove URLs.
     */
    public function getLocalityAttribute($value): string
    {
        $cleaned = self::cleanUrlFromText($value, false);
        return !empty($cleaned) ? $cleaned : ($this->attributes['location'] ?? 'Delhi');
    }

    /**
     * Mutator for locality.
     */
    public function setLocalityAttribute($value): void
    {
        $this->attributes['locality'] = self::cleanUrlFromText($value, false);
    }

    /**
     * Accessor for address: clean and remove URLs.
     */
    public function getAddressAttribute($value): string
    {
        $cleaned = self::cleanUrlFromText($value, true);
        return !empty($cleaned) ? $cleaned : (($this->attributes['locality'] ?? '') . ', ' . ($this->attributes['location'] ?? 'Delhi'));
    }

    /**
     * Mutator for address.
     */
    public function setAddressAttribute($value): void
    {
        $this->attributes['address'] = self::cleanUrlFromText($value, true);
    }

    /**
     * Accessor for title: clean and remove URLs.
     */
    public function getTitleAttribute($value): string
    {
        $cleaned = self::cleanUrlFromText($value, true);
        return !empty($cleaned) ? $cleaned : (($this->bedrooms ?? 2) . ' BHK Property in ' . ($this->attributes['location'] ?? 'Delhi'));
    }

    /**
     * Mutator for title.
     */
    public function setTitleAttribute($value): void
    {
        $this->attributes['title'] = self::cleanUrlFromText($value, true);
    }

    /**
     * Accessor for description: clean and remove URLs and 99acres.
     */
    public function getDescriptionAttribute($value): ?string
    {
        if (empty($value)) return $value;
        return self::cleanUrlFromText($value, false);
    }

    /**
     * Mutator for description.
     */
    public function setDescriptionAttribute($value): void
    {
        $this->attributes['description'] = !empty($value) ? self::cleanUrlFromText($value, false) : $value;
    }

    /**
     * Accessor for state: never allow a URL.
     */
    public function getStateAttribute($value): string
    {
        $cleaned = self::cleanUrlFromText($value, false);
        if (empty($cleaned) || str_starts_with(strtolower($cleaned), 'http') || str_contains(strtolower($cleaned), '99acres')) {
            [, $state] = self::extractCityAndStateFromUrl($this->attributes['location'] ?? '');
            return $state ?: 'India';
        }
        return $cleaned;
    }

    /**
     * Mutator for state.
     */
    public function setStateAttribute($value): void
    {
        $this->attributes['state'] = self::cleanUrlFromText($value, false) ?: 'India';
    }

    /**
     * Virtual city attribute.
     */
    public function getCityAttribute(): string
    {
        return $this->location;
    }

    /**
     * Get the owner of this property.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Alias for the owner relationship.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the category of this property.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get all images for this property.
     */
    public function images(): HasMany
    {
        return $this->hasMany(PropertyImage::class)->orderBy('sort_order');
    }

    /**
     * Get the primary image for this property.
     */
    public function primaryImage()
    {
        return $this->hasOne(PropertyImage::class)->where('is_primary', true);
    }

    /**
     * Get all inquiries for this property.
     */
    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class);
    }

    /**
     * Get all CRM leads generated for this property.
     */
    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }

    /**
     * Get all visitor events for this property.
     */
    public function visitorEvents(): HasMany
    {
        return $this->hasMany(VisitorEvent::class);
    }

    /**
     * Scope: only approved properties.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope: only pending properties.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: only featured properties.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope: filter by type.
     */
    public function scopeOfType($query, $type)
    {
        if ($type === 'pg' || $type === 'pg-hostel') {
            return $query->where(function ($q) {
                $q->where('type', 'pg-hostel')
                  ->orWhere('title', 'like', '%pg%')
                  ->orWhere('title', 'like', '%paying guest%')
                  ->orWhere('title', 'like', '%hostel%')
                  ->orWhere('category_id', 7);
            });
        }
        return $query->where('type', $type);
    }

    /**
     * Scope: filter by price range.
     */
    public function scopePriceBetween($query, $min, $max)
    {
        if ($min) {
            $query->where('price', '>=', $min);
        }
        if ($max) {
            $query->where('price', '<=', $max);
        }
        return $query;
    }

    /**
     * Scope: filter by location.
     */
    public function scopeInLocation($query, $location)
    {
        return $query->where('location', 'like', '%' . $location . '%');
    }

    /**
     * Check if this property is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Get formatted price string.
     */
    public function getFormattedPriceAttribute(): string
    {
        if (empty($this->price) || (float)$this->price <= 0) {
            return 'Price on Request';
        }
        if ($this->purpose === 'buy' || $this->purpose === 'sell') {
            if ($this->price >= 10000000) {
                return '₹' . rtrim(rtrim(number_format($this->price / 10000000, 2), '0'), '.') . ' Cr';
            } elseif ($this->price >= 100000) {
                return '₹' . rtrim(rtrim(number_format($this->price / 100000, 2), '0'), '.') . ' Lac';
            }
            return '₹' . number_format($this->price, 0);
        }
        $period = $this->price_period ?: 'mo';
        if ($period === 'month') $period = 'mo';
        return '₹' . number_format($this->price, 0) . '/' . $period;
    }

    /**
     * Check if property is listed for rent.
     */
    public function isForRent(): bool
    {
        return empty($this->purpose) || $this->purpose === 'rent';
    }

    /**
     * Check if property is listed for sale.
     */
    public function isForSale(): bool
    {
        return $this->purpose === 'buy' || $this->purpose === 'sell';
    }

    /**
     * Check if property has one or more uploaded videos or external video tours.
     */
    public function hasVideo(): bool
    {
        return !empty($this->video_path) && count($this->allVideoUrls()) > 0;
    }

    /**
     * Get all video URLs as an array.
     */
    public function allVideoUrls(): array
    {
        if (empty($this->video_path)) {
            return [];
        }

        $raw = $this->video_path;
        $items = [];

        if (is_array($raw)) {
            $items = $raw;
        } else {
            $decoded = json_decode($raw, true);
            if (is_string($decoded)) {
                $secondDecoded = json_decode($decoded, true);
                if (is_array($secondDecoded)) {
                    $decoded = $secondDecoded;
                }
            }
            if (is_array($decoded)) {
                $items = $decoded;
            } elseif (str_contains($raw, ',')) {
                $items = explode(',', $raw);
            } else {
                $items = [$raw];
            }
        }

        $urls = [];
        foreach ($items as $item) {
            $item = trim((string)$item, " \t\n\r\0\x0B\"'[]");
            if (empty($item)) continue;
            if (filter_var($item, FILTER_VALIDATE_URL)) {
                $urls[] = $item;
            } else {
                $urls[] = route('property.video.file', ['path' => ltrim($item, '/')], false);
            }
        }
        return array_values(array_unique($urls));
    }

    /**
     * Get the primary URL for property video tour.
     */
    public function videoUrl(): ?string
    {
        $all = $this->allVideoUrls();
        return !empty($all) ? $all[0] : null;
    }

    protected ?string $memoizedPrimaryImageUrl = null;
    protected bool $hasCheckedPrimaryImageUrl = false;

    /**
     * Get the primary image URL or fallback to first image.
     */
    public function primaryImageUrl(): ?string
    {
        if ($this->hasCheckedPrimaryImageUrl) {
            return $this->memoizedPrimaryImageUrl;
        }
        $this->hasCheckedPrimaryImageUrl = true;

        $url = null;
        if ($this->relationLoaded('primaryImage') && $this->primaryImage) {
            $url = $this->primaryImage->imageUrl();
        } elseif ($this->relationLoaded('images') && $this->images && $this->images->isNotEmpty()) {
            $prim = $this->images->firstWhere('is_primary', true);
            $url = $prim ? $prim->imageUrl() : $this->images->first()->imageUrl();
        } elseif ($this->primaryImage) {
            $url = $this->primaryImage->imageUrl();
        } elseif ($prim = $this->images()->where('is_primary', true)->first()) {
            $url = $prim->imageUrl();
        } elseif ($first = $this->images()->first()) {
            $url = $first->imageUrl();
        }

        if (!$url || str_contains($url, 'property_placeholder') || str_contains($url, 'luxury_sunlit') || str_contains($url, '858815159M') || str_contains($url, 'photo-1512917774080-9991f1c4c750')) {
            $photos = \App\Models\PropertyImage::VERIFIED_REAL_PHOTOS;
            $url = $photos[abs((int)$this->id) % count($photos)];
        }

        return $this->memoizedPrimaryImageUrl = $url;
    }

    /**
     * Mutator to guarantee furnishing always matches MySQL enum: ['unfurnished', 'semi-furnished', 'fully-furnished']
     */
    public function setFurnishingAttribute($value): void
    {
        $v = strtolower(trim((string) $value));
        if ($v === 'furnished' || $v === 'fully-furnished' || $v === 'full' || $v === 'fully' || str_contains($v, 'full') || str_contains($v, 'furnish')) {
            $this->attributes['furnishing'] = ($v === 'unfurnished' || str_starts_with($v, 'un')) ? 'unfurnished' : (str_contains($v, 'semi') ? 'semi-furnished' : 'fully-furnished');
        } elseif ($v === 'semi-furnished' || $v === 'semi' || str_contains($v, 'semi')) {
            $this->attributes['furnishing'] = 'semi-furnished';
        } else {
            $this->attributes['furnishing'] = 'unfurnished';
        }
    }

    /**
     * Ensure external_id column exists in the database table.
     * Auto-migrates the column on-the-fly if missing so production MySQL environments
     * never crash with 1054 Unknown column 'external_id'.
     */
    public static function ensureExternalIdColumnExists(): bool
    {
        static $hasColumn = null;
        if ($hasColumn !== null) {
            return $hasColumn;
        }

        try {
            if (!\Illuminate\Support\Facades\Schema::hasColumn('properties', 'external_id')) {
                \Illuminate\Support\Facades\Schema::table('properties', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->string('external_id', 150)->nullable()->index()->after('slug');
                });
            }
            $hasColumn = true;
        } catch (\Throwable $e) {
            try {
                $hasColumn = \Illuminate\Support\Facades\Schema::hasColumn('properties', 'external_id');
            } catch (\Throwable $ex) {
                $hasColumn = false;
            }
        }

        return (bool) $hasColumn;
    }

    /**
     * Check if a property matching the incoming item already exists in the database.
     * Uses a multi-tier precision algorithm:
     * 1. Primary image URL / filename match (99acres photo CDN URLs are globally unique to a listing)
     * 2. Exact Title + City + Price + Purpose match
     * 3. Normalized Title + City + Locality match
     * 4. City + Locality + Price + Bedrooms match
     * 5. High title similarity (>=85%) + City + Price match
     */
    public static function findDuplicate(array $data): ?self
    {
        // Check 0: 99acres Unique ID Number (prop_id / external_id) - 100% definitive check
        $externalId = trim((string) ($data['external_id'] ?? $data['prop_id'] ?? ''));
        if (!empty($externalId)) {
            if (self::ensureExternalIdColumnExists()) {
                try {
                    $found = self::where('external_id', $externalId)->first();
                    if ($found) {
                        return $found;
                    }
                    // If 99acres unique ID is supplied and not found in DB, this is definitively a new listing!
                    // Bypass loose title/image heuristics so fresh 99acres listings are never falsely blocked.
                    return null;
                } catch (\Throwable $e) {
                    // If DB fails to query column, fall through to title/heuristic checks
                }
            }
        }

        $cleanTitle = self::cleanUrlFromText($data['title'] ?? '', true);
        if (empty($cleanTitle)) {
            return null;
        }

        $city = self::cleanUrlFromText($data['city'] ?? $data['location'] ?? '', true);
        $price = (float) ($data['price'] ?? 0);
        $purpose = strtolower(trim((string) ($data['purpose'] ?? 'rent')));
        $locality = self::cleanUrlFromText($data['locality'] ?? '', false);
        $bedrooms = isset($data['bedrooms']) ? (int) $data['bedrooms'] : null;

        // Check 1: Image Match (99acres photo filenames e.g. 498255979M are unique to the listing)
        if (!empty($data['images']) && is_array($data['images'])) {
            $firstImg = trim((string) reset($data['images']));
            if (!empty($firstImg) && !str_contains($firstImg, 'property_placeholder') && !str_contains($firstImg, 'unsplash')) {
                $baseImg = basename(preg_replace('/(\?.*)$/', '', $firstImg));
                if (!empty($baseImg) && strlen($baseImg) > 6) {
                    $matchedImg = \App\Models\PropertyImage::where('path', $firstImg)
                        ->orWhere('path', 'like', "%{$baseImg}%")
                        ->first();
                    if ($matchedImg && $matchedImg->property_id) {
                        $prop = self::find($matchedImg->property_id);
                        if ($prop) {
                            // Ensure the matched image actually belongs to the same city and has similar title or price
                            $propCity = strtolower((string) ($prop->location ?? ''));
                            $searchCity = strtolower((string) ($city ?? ''));
                            $sameCity = empty($searchCity) || empty($propCity) || str_contains($propCity, $searchCity) || str_contains($searchCity, $propCity);
                            if ($sameCity) {
                                similar_text(strtolower($prop->title), strtolower($cleanTitle), $titleSim);
                                $similarPrice = ($price > 0 && abs($prop->price - $price) / max(1, $price) <= 0.25);
                                if ($titleSim >= 65 || $similarPrice) {
                                    return $prop;
                                }
                            }
                        }
                    }
                }
            }
        }

        // Check 2: Exact Title + City + Purpose match
        $query = self::query();
        if (!empty($city)) {
            $query->where(function ($q) use ($city) {
                $q->where('location', 'like', "%{$city}%")
                  ->orWhere('address', 'like', "%{$city}%");
            });
        }
        if (!empty($purpose)) {
            $query->where('purpose', $purpose);
        }

        $exactMatch = (clone $query)->where('title', $cleanTitle);
        if ($price > 0) {
            $exactMatch->whereBetween('price', [$price * 0.95, $price * 1.05]);
        }
        $found = $exactMatch->first();
        if ($found) {
            return $found;
        }

        // Check 3: Normalized Title (alphanumeric only) + City
        $normalizedSearchTitle = preg_replace('/[^a-z0-9]/', '', strtolower($cleanTitle));
        if (strlen($normalizedSearchTitle) >= 8) {
            $candidates = (clone $query);
            if ($price > 0) {
                $candidates->whereBetween('price', [$price * 0.90, $price * 1.10]);
            }
            $candidatesList = $candidates->limit(30)->get();

            foreach ($candidatesList as $cand) {
                $candNorm = preg_replace('/[^a-z0-9]/', '', strtolower($cand->title));
                if ($candNorm === $normalizedSearchTitle) {
                    return $cand;
                }

                // Check 4: Same locality + same bedrooms + same price
                if (!empty($locality) && $bedrooms !== null && $cand->bedrooms == $bedrooms) {
                    $candLocNorm = preg_replace('/[^a-z0-9]/', '', strtolower($cand->locality ?? ''));
                    $searchLocNorm = preg_replace('/[^a-z0-9]/', '', strtolower($locality));
                    if (!empty($candLocNorm) && !empty($searchLocNorm) && (str_contains($candLocNorm, $searchLocNorm) || str_contains($searchLocNorm, $candLocNorm))) {
                        if ($price > 0 && abs($cand->price - $price) / $price <= 0.05) {
                            return $cand;
                        }
                    }
                }

                // Check 5: Title similarity percentage
                similar_text($candNorm, $normalizedSearchTitle, $percent);
                if ($percent >= 85) {
                    return $cand;
                }
            }
        }

        return null;
    }

    /**
     * Merge and clean duplicate property listings in the database.
     * Retains the primary (best) listing, migrates all inquiries/leads/images,
     * and deletes redundant copies.
     */
    public static function cleanDuplicates(): int
    {
        $deletedCount = 0;

        $properties = self::with(['images', 'inquiries'])->orderBy('id', 'asc')->get();
        $grouped = [];

        $hasExtCol = self::ensureExternalIdColumnExists();
        foreach ($properties as $prop) {
            if ($hasExtCol && !empty($prop->external_id)) {
                $key = "ext_" . trim($prop->external_id);
            } else {
                $cleanTitle = self::cleanUrlFromText($prop->title, true);
                $normTitle = preg_replace('/[^a-z0-9]/', '', strtolower($cleanTitle));
                $normCity = strtolower(trim((string) $prop->location));
                $normPrice = round((float) $prop->price, -2);
                $normPurpose = strtolower(trim((string) $prop->purpose));
                $key = "{$normCity}_{$normPurpose}_{$normPrice}_{$normTitle}";
            }
            $grouped[$key][] = $prop;
        }

        foreach ($grouped as $key => $props) {
            if (count($props) <= 1) {
                continue;
            }

            // Sort: best record first (most images, approved, oldest ID)
            usort($props, function ($a, $b) {
                $imgDiff = ($b->images?->count() ?? 0) - ($a->images?->count() ?? 0);
                if ($imgDiff !== 0) return $imgDiff;

                if ($a->status === 'approved' && $b->status !== 'approved') return -1;
                if ($b->status === 'approved' && $a->status !== 'approved') return 1;

                return $a->id - $b->id;
            });

            $primary = array_shift($props);

            foreach ($props as $dupe) {
                // Re-link inquiries and leads to primary
                \App\Models\Inquiry::where('property_id', $dupe->id)->update(['property_id' => $primary->id]);
                \App\Models\Lead::where('property_id', $dupe->id)->update(['property_id' => $primary->id]);

                // Migrate unique images if primary has fewer than 10
                if (($primary->images?->count() ?? 0) < 10 && $dupe->images) {
                    foreach ($dupe->images as $dupeImg) {
                        $exists = \App\Models\PropertyImage::where('property_id', $primary->id)
                            ->where('path', $dupeImg->path)
                            ->exists();
                        if (!$exists) {
                            $dupeImg->update(['property_id' => $primary->id]);
                        }
                    }
                }

                \App\Models\PropertyImage::where('property_id', $dupe->id)->delete();
                $dupe->delete();
                $deletedCount++;
            }
        }

        if ($deletedCount > 0) {
            \Illuminate\Support\Facades\Cache::forget('home_featured_rentals');
            \Illuminate\Support\Facades\Cache::forget('home_featured_properties');
            \Illuminate\Support\Facades\Cache::forget('admin_dashboard_stats');
        }

        return $deletedCount;
    }
}
