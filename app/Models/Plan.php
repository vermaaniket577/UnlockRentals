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
            'duration_days' => 'integer',
            'contact_limit' => 'integer',
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

    /**
     * Ensure dedicated Buyer Pass plans exist in the database.
     */
    public static function ensureBuyerPlansExist(): void
    {
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('plans')) {
                return;
            }

            if (!\Illuminate\Support\Facades\Schema::hasColumn('plans', 'purpose')) {
                \Illuminate\Support\Facades\Schema::table('plans', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->string('purpose', 20)->default('rent')->after('description');
                });
            }

            $count = static::whereIn('purpose', ['buy', 'sale'])->count();
            if ($count === 0) {
                static::create([
                    'name'          => 'Silver Buyer Pass',
                    'purpose'       => 'buy',
                    'description'   => 'Essential direct seller contacts and priority access for property buyers.',
                    'price'         => 399.00,
                    'duration_days' => 60,
                    'contact_limit' => 30,
                    'features'      => [
                        '30 Verified Seller Direct Contacts',
                        'Direct Phone & WhatsApp Unlock',
                        'Zero Brokerage Guaranteed',
                        '60 Days Priority Buyer Access',
                    ],
                    'is_active'     => true,
                    'sort_order'    => 10,
                ]);

                static::create([
                    'name'          => 'Gold Buyer Pass',
                    'purpose'       => 'buy',
                    'description'   => 'Most popular annual pass for active home buyers and property investors.',
                    'price'         => 999.00,
                    'duration_days' => 150,
                    'contact_limit' => 75,
                    'features'      => [
                        '75 Verified Seller Direct Contacts',
                        'Direct Phone & WhatsApp Unlock',
                        'Zero Brokerage Guaranteed',
                        '150 Days Priority Buyer Access',
                        'Priority Support Response',
                    ],
                    'is_active'     => true,
                    'sort_order'    => 11,
                ]);

                static::create([
                    'name'          => 'Platinum Buyer Pass',
                    'purpose'       => 'buy',
                    'description'   => 'Ultimate annual pass with maximum verified seller unlocks for serious property buyers.',
                    'price'         => 1999.00,
                    'duration_days' => 365,
                    'contact_limit' => 180,
                    'features'      => [
                        '180 Verified Seller Direct Contacts',
                        'Direct Phone & WhatsApp Unlock',
                        'Zero Brokerage Guaranteed',
                        '365 Days Priority Buyer Access',
                        'Dedicated Relationship Manager',
                    ],
                    'is_active'     => true,
                    'sort_order'    => 12,
                ]);
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Auto-create buyer plans warning: ' . $e->getMessage());
        }
    }
}
