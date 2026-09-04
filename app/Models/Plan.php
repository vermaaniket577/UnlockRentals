<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'description',
        'purpose',
        'price',
        'duration_days',
        'contact_limit',
        'features',
        'is_active',
        'is_private',
        'image_path',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'features' => 'array',
            'price' => 'decimal:2',
            'is_active' => 'boolean',
            'is_private' => 'boolean',
        ];
    }

    /**
     * Get all subscriptions for this plan.
     */
    public function userPlans(): HasMany
    {
        return $this->hasMany(UserPlan::class);
    }

    /**
     * Scope to get only active plans.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    /**
     * Scope to get only public plans.
     */
    public function scopePublic($query)
    {
        return $query->active()->where('is_private', false);
    }

    /**
     * Scope to get rental plans.
     */
    public function scopeRent($query)
    {
        return $query->whereIn('purpose', ['rent', 'both', null]);
    }

    /**
     * Scope to get buyer plans.
     */
    public function scopeBuy($query)
    {
        return $query->whereIn('purpose', ['buy', 'sale', 'both']);
    }

    /**
     * Check if plan applies to rental properties.
     */
    public function isRentPlan(): bool
    {
        return in_array($this->purpose, ['rent', 'both', null]);
    }

    /**
     * Check if plan applies to properties for sale/buy.
     */
    public function isBuyPlan(): bool
    {
        return in_array($this->purpose, ['buy', 'sale', 'both']);
    }

    /**
     * Check if this plan can unlock the specified property.
     */
    public function canUnlock(Property $property): bool
    {
        if ($property->isForSale()) {
            return $this->isBuyPlan();
        }

        return $this->isRentPlan();
    }

    /**
     * Get formatted price.
     */
    public function getFormattedPriceAttribute(): string
    {
        return '₹' . number_format($this->price, 0);
    }
}
