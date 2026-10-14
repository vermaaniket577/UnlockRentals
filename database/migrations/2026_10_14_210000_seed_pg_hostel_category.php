<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('categories')) {
            $cat = Category::where('name', 'PG & Hostel')
                ->orWhere('name', 'PG / Hostel')
                ->orWhere('slug', 'pg-hostel')
                ->first();

            if (!$cat) {
                Category::create([
                    'name' => 'PG & Hostel',
                    'slug' => 'pg-hostel',
                    'icon' => 'buildings',
                    'description' => 'Paying guest accommodations, student hostels, and co-living spaces with food and amenities.',
                ]);
            }

            Cache::forget('all_categories');
            Cache::forget('active_categories');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Keep category safe
    }
};
