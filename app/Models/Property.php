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

        foreach ($map as $key => $pair) {
            if (str_contains($urlLower, $key)) {
                return $pair;
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
}
