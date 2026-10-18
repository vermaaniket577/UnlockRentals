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
        'original_price',
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
            'original_price' => 'decimal:2',
            'duration_days' => 'integer',
            'contact_limit' => 'integer',
            'is_active' => 'boolean',
            'is_private' => 'boolean',
        ];
    }

    /**
     * The booted method of the model.
     */
    protected static function booted(): void
    {
        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget('active_rent_plans_v1');
            \Illuminate\Support\Facades\Cache::forget('active_buy_plans_v1');
            \Illuminate\Support\Facades\Cache::forget('active_rent_plans_v2');
            \Illuminate\Support\Facades\Cache::forget('active_buy_plans_v2');
            \Illuminate\Support\Facades\Cache::forget('rental_plans_ensured_v1');
            \Illuminate\Support\Facades\Cache::forget('buyer_plans_ensured_v1');
        });

        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forget('active_rent_plans_v1');
            \Illuminate\Support\Facades\Cache::forget('active_buy_plans_v1');
            \Illuminate\Support\Facades\Cache::forget('active_rent_plans_v2');
            \Illuminate\Support\Facades\Cache::forget('active_buy_plans_v2');
            \Illuminate\Support\Facades\Cache::forget('rental_plans_ensured_v1');
            \Illuminate\Support\Facades\Cache::forget('buyer_plans_ensured_v1');
        });
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
     * Get formatted original price.
     */
    public function getFormattedOriginalPriceAttribute(): ?string
    {
        return $this->original_price !== null ? ('₹' . number_format($this->original_price, 0)) : null;
    }

    /**
     * Get array of all default direct contact pass plans.
     */
    public static function getDefaultPlansData(): array
    {
        return [
            [
                'name'          => 'Silver Starter Pass',
                'purpose'       => 'rent',
                'description'   => 'Perfect for tenants looking for a basic start. Skip brokerage charges completely.',
                'price'         => 399.00,
                'original_price'=> 799.00,
                'duration_days' => 30,
                'contact_limit' => 5,
                'features'      => [
                    '5 Verified Landlords Direct Contacts',
                    'Direct Phone & WhatsApp Unlocks',
                    'Zero Brokerage Guaranteed',
                    '30 Days Validity'
                ],
                'is_active'     => true,
                'is_private'    => false,
                'sort_order'    => 1,
            ],
            [
                'name'          => 'Silver Buyer Pass',
                'purpose'       => 'buy',
                'description'   => 'Skip brokerage charges completely. Instant contact numbers directly from verified property sellers.',
                'price'         => 399.00,
                'original_price'=> 998.00,
                'duration_days' => 60,
                'contact_limit' => 30,
                'features'      => [
                    '30 Verified Seller Direct Contacts',
                    'Direct Phone & WhatsApp Unlocks',
                    'Zero Brokerage Guaranteed',
                    '60 Days Priority Buyer Access'
                ],
                'is_active'     => true,
                'is_private'    => false,
                'sort_order'    => 2,
            ],
            [
                'name'          => 'Gold Direct Pass',
                'purpose'       => 'rent',
                'description'   => 'Skip brokerage charges completely. Instant contact numbers for active rental seekers.',
                'price'         => 499.00,
                'original_price'=> 1249.00,
                'duration_days' => 90,
                'contact_limit' => 15,
                'features'      => [
                    '15 Verified Landlords Direct Contacts',
                    'Direct Phone & WhatsApp Unlocks',
                    'Zero Brokerage Guaranteed',
                    '90 Days Active Validity'
                ],
                'is_active'     => true,
                'is_private'    => false,
                'sort_order'    => 3,
            ],
            [
                'name'          => 'Gold Buyer Pass',
                'purpose'       => 'buy',
                'description'   => 'Most popular pass for home buyers and investors. Skip brokerage completely.',
                'price'         => 999.00,
                'original_price'=> 2499.00,
                'duration_days' => 150,
                'contact_limit' => 75,
                'features'      => [
                    '75 Verified Seller Direct Contacts',
                    'Direct Phone & WhatsApp Unlocks',
                    'Zero Brokerage Guaranteed',
                    '150 Days Priority Buyer Access'
                ],
                'is_active'     => true,
                'is_private'    => false,
                'sort_order'    => 4,
            ],
            [
                'name'          => 'Platinum VIP Pass',
                'purpose'       => 'rent',
                'description'   => 'Skip brokerage charges completely. Instant contact numbers with maximum VIP access.',
                'price'         => 999.00,
                'original_price'=> 2499.00,
                'duration_days' => 180,
                'contact_limit' => 50,
                'features'      => [
                    '50 Verified Landlords Direct Contacts',
                    'Direct Phone & WhatsApp Unlocks',
                    'Zero Brokerage Guaranteed',
                    '180 Days VIP Access'
                ],
                'is_active'     => true,
                'is_private'    => false,
                'sort_order'    => 5,
            ],
            [
                'name'          => 'Platinum Buyer Pass',
                'purpose'       => 'buy',
                'description'   => 'Ultimate annual pass with maximum verified seller unlocks for serious property buyers.',
                'price'         => 1999.00,
                'original_price'=> 4999.00,
                'duration_days' => 365,
                'contact_limit' => 180,
                'features'      => [
                    '180 Verified Seller Direct Contacts',
                    'Direct Phone & WhatsApp Unlocks',
                    'Zero Brokerage Guaranteed',
                    '365 Days Priority Buyer Access'
                ],
                'is_active'     => true,
                'is_private'    => false,
                'sort_order'    => 6,
            ],
        ];
    }

    /**
     * Ensure all standard default plans (Rent & Buy) exist or reset them.
     */
    public static function seedDefaultPlans(bool $force = false): void
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

            $defaults = static::getDefaultPlansData();

            foreach ($defaults as $planData) {
                $query = static::where('name', $planData['name'])->where('purpose', $planData['purpose']);
                $existing = $query->first();

                if (!$existing) {
                    // Also check if existing by purpose & price
                    $existingByPrice = static::where('purpose', $planData['purpose'])
                        ->where('price', $planData['price'])
                        ->first();

                    if ($existingByPrice && $force) {
                        $existingByPrice->update($planData);
                    } else {
                        static::create($planData);
                    }
                } elseif ($force) {
                    $existing->update($planData);
                }
            }

            \Illuminate\Support\Facades\Cache::forget('active_rent_plans_v1');
            \Illuminate\Support\Facades\Cache::forget('active_buy_plans_v1');
            \Illuminate\Support\Facades\Cache::forget('active_rent_plans_v2');
            \Illuminate\Support\Facades\Cache::forget('active_buy_plans_v2');
            \Illuminate\Support\Facades\Cache::forever('rental_plans_ensured_v1', true);
            \Illuminate\Support\Facades\Cache::forever('buyer_plans_ensured_v1', true);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Seed default plans warning: ' . $e->getMessage());
        }
    }

    /**
     * Ensure dedicated Buyer Pass plans exist in the database.
     */
    public static function ensureBuyerPlansExist(): void
    {
        if (\Illuminate\Support\Facades\Cache::has('buyer_plans_ensured_v1')) {
            return;
        }

        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('plans')) {
                return;
            }

            $count = static::whereIn('purpose', ['buy', 'sale'])->count();
            if ($count === 0) {
                static::seedDefaultPlans(false);
            }

            \Illuminate\Support\Facades\Cache::forever('buyer_plans_ensured_v1', true);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Auto-create buyer plans warning: ' . $e->getMessage());
        }
    }

    /**
     * Ensure standard Rental Pass plans exist in the database.
     */
    public static function ensureRentalPlansExist(): void
    {
        if (\Illuminate\Support\Facades\Cache::has('rental_plans_ensured_v1')) {
            return;
        }

        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('plans')) {
                return;
            }

            $count = static::whereIn('purpose', ['rent', 'both'])->orWhereNull('purpose')->count();
            if ($count === 0) {
                static::seedDefaultPlans(false);
            }

            \Illuminate\Support\Facades\Cache::forever('rental_plans_ensured_v1', true);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Auto-create rental plans warning: ' . $e->getMessage());
        }
    }
}
