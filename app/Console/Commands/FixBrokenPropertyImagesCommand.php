<?php

namespace App\Console\Commands;

use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class FixBrokenPropertyImagesCommand extends Command
{
    protected $signature = 'properties:fix-images';
    protected $description = 'Replace broken 404 image URLs and AI stock placeholders with real, authentic 99acres apartment photos';

    protected array $verifiedPhotos = [];

    public function __construct()
    {
        parent::__construct();
        $this->verifiedPhotos = PropertyImage::VERIFIED_REAL_PHOTOS;
    }

    public function handle(): int
    {
        $this->info("Scanning and replacing broken/AI stock images with verified real estate photos...");

        // 1. Target known broken or AI stock URLs
        $badUrls = [
            'https://imagecdn.99acres.com/media1/42940/15/858815159M-1791672462214.webp',
            'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1000&q=80',
            'images/luxury_sunlit.webp',
            'images/luxury_sunlit.png',
        ];

        $fixedCount = 0;
        $images = PropertyImage::withoutGlobalScope('withoutBlob')->get();

        foreach ($images as $img) {
            $isBad = false;
            foreach ($badUrls as $bad) {
                if ($img->path === $bad || str_contains($img->path ?? '', '858815159M') || str_contains($img->path ?? '', 'photo-1512917774080-9991f1c4c750') || str_contains($img->path ?? '', 'luxury_sunlit')) {
                    $isBad = true;
                    break;
                }
            }

            if ($isBad) {
                $replacement = $this->verifiedPhotos[$img->property_id % count($this->verifiedPhotos)];
                $img->path = $replacement;
                $img->save();
                $fixedCount++;
            }
        }

        // 2. Attach images to any property that currently has no images
        $emptyProps = Property::doesntHave('images')->get();
        foreach ($emptyProps as $prop) {
            $photo = $this->verifiedPhotos[$prop->id % count($this->verifiedPhotos)];
            PropertyImage::create([
                'property_id' => $prop->id,
                'path' => $photo,
                'is_primary' => true,
                'sort_order' => 0,
            ]);
            $fixedCount++;
        }

        Cache::forget('home_featured_rentals');
        Cache::forget('home_featured_properties');
        Cache::forget('admin_dashboard_stats');

        $this->info("Successfully fixed {$fixedCount} image records across properties!");
        return 0;
    }
}
