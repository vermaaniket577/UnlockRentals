<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\Property;
use App\Models\PropertyImage;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $photos = PropertyImage::VERIFIED_REAL_PHOTOS;
        $totalPhotos = count($photos);

        // 1. Replace broken or AI stock URLs in property_images table
        $images = DB::table('property_images')->get();
        foreach ($images as $img) {
            $p = (string) $img->path;
            $isBad = empty($p)
                || str_contains($p, 'luxury_sunlit')
                || str_contains($p, '858815159M')
                || str_contains($p, 'property_placeholder')
                || str_contains($p, 'photo-1512917774080-9991f1c4c750');

            if ($isBad) {
                $idx = abs((int)($img->property_id ?: $img->id)) % $totalPhotos;
                DB::table('property_images')
                    ->where('id', $img->id)
                    ->update([
                        'path' => $photos[$idx],
                        'updated_at' => now(),
                    ]);
            }
        }

        // 2. Attach a verified photo to any property that has no image
        $props = DB::table('properties')->get();
        foreach ($props as $prop) {
            $hasImg = DB::table('property_images')->where('property_id', $prop->id)->exists();
            if (!$hasImg) {
                $idx = abs((int)$prop->id) % $totalPhotos;
                DB::table('property_images')->insert([
                    'property_id' => $prop->id,
                    'path'        => $photos[$idx],
                    'is_primary'  => 1,
                    'sort_order'  => 0,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
        }

        try {
            Cache::forget('home_featured_rentals');
            Cache::forget('home_featured_properties');
            Cache::forget('admin_dashboard_stats');
        } catch (\Throwable $e) {}
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reversal needed for verified property photos
    }
};
