<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Professional extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'professionals';

    protected $fillable = [
        'user_id',
        'category_id',
        'business_name',
        'slug',
        'full_name',
        'profile_photo',
        'phone',
        'whatsapp_number',
        'email',
        'description',
        'years_experience',
        'starting_price',
        'price_type',
        'home_visit',
        'emergency_service',
        'available_today',
        'address',
        'state',
        'district',
        'city',
        'locality',
        'pincode',
        'latitude',
        'longitude',
        'service_radius_km',
        'status',
        'verification_status',
        'rejection_reason',
        'average_rating',
        'review_count',
        'views_count',
        'whatsapp_clicks',
        'call_clicks',
        'lead_count',
        'featured',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'home_visit' => 'boolean',
        'emergency_service' => 'boolean',
        'available_today' => 'boolean',
        'featured' => 'boolean',
        'years_experience' => 'integer',
        'service_radius_km' => 'integer',
        'starting_price' => 'decimal:2',
        'average_rating' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'approved_at' => 'datetime',
    ];

    /* ── Relationships ─────────────────────────────────────── */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProfessionalCategory::class, 'category_id');
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(
            ProfessionalService::class,
            'professional_professional_service',
            'professional_id',
            'professional_service_id'
        )->withTimestamps();
    }

    public function locations(): HasMany
    {
        return $this->hasMany(ProfessionalLocation::class, 'professional_id');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(ProfessionalPhoto::class, 'professional_id')->orderBy('sort_order', 'asc');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(ProfessionalDocument::class, 'professional_id');
    }

    public function availability(): HasMany
    {
        return $this->hasMany(ProfessionalAvailability::class, 'professional_id');
    }

    public function leads(): HasMany
    {
        return $this->hasMany(ProfessionalLead::class, 'professional_id')->latest();
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProfessionalReview::class, 'professional_id')->latest();
    }

    public function approvedReviews(): HasMany
    {
        return $this->reviews()->where('status', 'approved');
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(ProfessionalFavorite::class, 'professional_id');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(ProfessionalReport::class, 'professional_id');
    }

    public function clickLogs(): HasMany
    {
        return $this->hasMany(ProfessionalClickLog::class, 'professional_id');
    }

    /* ── Scopes ────────────────────────────────────────────── */

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeVerified($query)
    {
        return $query->where('verification_status', 'verified');
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    /**
     * Geospatial Haversine calculation to find professionals within radius.
     */
    public function scopeNearby($query, float $latitude, float $longitude, float $radiusKm = 25)
    {
        $haversine = "(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude))))";

        return $query->select('*')
            ->selectRaw("{$haversine} AS distance", [$latitude, $longitude, $latitude])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->having('distance', '<=', $radiusKm)
            ->orderBy('distance', 'asc');
    }

    /* ── Accessors & Helpers ───────────────────────────────── */

    public function getProfilePhotoUrlAttribute(): string
    {
        if (!empty($this->profile_photo)) {
            if (str_starts_with($this->profile_photo, 'http://') || str_starts_with($this->profile_photo, 'https://')) {
                return $this->profile_photo;
            }
            return asset('storage/' . $this->profile_photo);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->full_name ?: $this->business_name) . '&background=2563EB&color=fff&size=200&bold=true';
    }

    public function getPrimaryLocationDisplayAttribute(): string
    {
        $parts = array_filter([$this->locality, $this->city, $this->state]);
        return implode(', ', $parts) ?: 'India';
    }

    public function getFormattedPriceAttribute(): string
    {
        if ($this->price_type === 'contact') {
            return 'Contact for Price';
        }
        if ($this->price_type === 'negotiable') {
            return 'Negotiable';
        }

        if ($this->starting_price > 0) {
            $typeLabel = match($this->price_type) {
                'hourly' => '/ hr',
                'per_visit' => '/ visit',
                'per_service' => '/ service',
                default => '',
            };
            return '₹' . number_format($this->starting_price) . ' ' . $typeLabel;
        }

        return 'Competitive Pricing';
    }

    /**
     * Calculate Profile Completion Percentage.
     */
    public function getProfileCompletionPercentageAttribute(): int
    {
        $score = 0;
        if (!empty($this->profile_photo)) $score += 15;
        if (!empty($this->business_name)) $score += 10;
        if (!empty($this->description) && strlen($this->description) > 30) $score += 15;
        if (!empty($this->phone)) $score += 10;
        if (!empty($this->whatsapp_number)) $score += 10;
        if (!empty($this->city) && !empty($this->locality)) $score += 10;
        if ($this->services()->exists()) $score += 10;
        if ($this->photos()->exists()) $score += 10;
        if ($this->documents()->exists()) $score += 10;

        return min(100, $score);
    }

    /**
     * Check if professional is admin verified.
     */
    public function isVerified(): bool
    {
        return $this->verification_status === 'verified';
    }

    /**
     * Check if professional is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if professional is pending review.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Recalculate average rating & review count and persist.
     */
    public function recalculateRating(): void
    {
        $stats = $this->approvedReviews()->selectRaw('COUNT(*) as total_reviews, AVG(rating) as avg_rating')->first();

        $this->update([
            'review_count' => $stats->total_reviews ?? 0,
            'average_rating' => round($stats->avg_rating ?? 0, 2),
        ]);
    }
}
