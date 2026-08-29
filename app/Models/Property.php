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
                $property->slug = Str::slug($property->title) . '-' . Str::random(6);
            }
        });
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
        if ($this->purpose === 'buy' || $this->purpose === 'sell') {
            return '₹' . number_format($this->price, 0);
        }
        return '₹' . number_format($this->price, 0) . '/' . $this->price_period;
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
                $urls[] = route('property.video.file', ['path' => $item]);
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

    /**
     * Get the primary image URL or fallback to first image.
     */
    public function primaryImageUrl(): ?string
    {
        if ($this->primaryImage) {
            return $this->primaryImage->imageUrl();
        }
        $firstImage = $this->images ? $this->images->first() : null;
        if ($firstImage) {
            return $firstImage->imageUrl();
        }
        return null;
    }
}
