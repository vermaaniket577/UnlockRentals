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
            // Ensure PG & Hostel exists
            $pg = Category::where('name', 'PG & Hostel')
                ->orWhere('name', 'PG / Hostel')
                ->orWhere('slug', 'pg-hostel')
                ->first();

            if (!$pg) {
                Category::create([
                    'name' => 'PG & Hostel',
                    'slug' => 'pg-hostel',
                    'icon' => 'buildings',
                    'description' => 'Paying guest accommodations, student hostels, and co-living spaces with food and amenities.',
                ]);
            }

            // Ensure Hostel exists as distinct selectable category
            $hostel = Category::where('name', 'Hostel')
                ->orWhere('slug', 'hostel')
                ->first();

            if (!$hostel) {
                Category::create([
                    'name' => 'Hostel',
                    'slug' => 'hostel',
                    'icon' => 'bed',
                    'description' => 'Student hostels and working professionals hostels with boarding and lodging facilities.',
                ]);
            }

            Cache::forget('all_categories');
            Cache::forget('active_categories');
            Cache::forget('home_categories');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Keep categories safe
    }
};
