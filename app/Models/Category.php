<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'icon',
        'description',
    ];

    /**
     * The booted method of the model.
     */
    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('active_categories');
            Cache::forget('all_categories');
            Cache::forget('home_categories');
        });

        static::deleted(function () {
            Cache::forget('active_categories');
            Cache::forget('all_categories');
            Cache::forget('home_categories');
        });
    }

    /**
     * Ensure core categories exist in database
     */
    public static function ensureCoreCategoriesExist(): void
    {
        try {
            $core = [
                ['name' => 'Residential House', 'slug' => 'residential-house', 'icon' => 'house', 'description' => 'Great places to live with your family.'],
                ['name' => 'Luxury Villa', 'slug' => 'luxury-villa', 'icon' => 'castle-turret', 'description' => 'Premium living experience.'],
                ['name' => 'Studio Apartment', 'slug' => 'studio-apartment', 'icon' => 'buildings', 'description' => 'Compact and modern apartments.'],
                ['name' => 'Retail Shop', 'slug' => 'retail-shop', 'icon' => 'storefront', 'description' => 'Perfect locations for your business.'],
                ['name' => 'Office Space', 'slug' => 'office-space', 'icon' => 'briefcase', 'description' => 'Professional working environments.'],
                ['name' => 'Plots & Land', 'slug' => 'plots-and-land', 'icon' => 'map-trifold', 'description' => 'Commercial, residential, and agricultural plots and land for sale.'],
                ['name' => 'PG & Hostel', 'slug' => 'pg-hostel', 'icon' => 'buildings', 'description' => 'Paying guest accommodations, student hostels, and co-living spaces with food and amenities.'],
                ['name' => 'Hostel', 'slug' => 'hostel', 'icon' => 'bed', 'description' => 'Student hostels and working professionals hostels with boarding and lodging facilities.'],
            ];

            $createdAny = false;
            foreach ($core as $cat) {
                $exists = static::where('slug', $cat['slug'])
                    ->orWhere('name', $cat['name'])
                    ->first();

                if (!$exists) {
                    static::create($cat);
                    $createdAny = true;
                }
            }

            if ($createdAny) {
                Cache::forget('active_categories');
                Cache::forget('all_categories');
                Cache::forget('home_categories');
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Category::ensureCoreCategoriesExist failed: ' . $e->getMessage());
        }
    }

    /**
     * Get all active categories with cache and auto-healing
     */
    public static function getActiveCategories()
    {
        static::ensureCoreCategoriesExist();
        return Cache::remember('active_categories', 3600, function () {
            $priority = [
                'residential-house' => 1,
                'luxury-villa' => 2,
                'studio-apartment' => 3,
                'pg-hostel' => 4,
                'hostel' => 5,
                'retail-shop' => 6,
                'office-space' => 7,
                'plots-and-land' => 8,
            ];

            return static::all()->sortBy(function ($cat) use ($priority) {
                return $priority[$cat->slug] ?? 99;
            })->values();
        });
    }

    /**
     * Get the properties belonging to this category.
     */
    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }
}

