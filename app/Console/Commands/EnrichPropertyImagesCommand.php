<?php

namespace App\Console\Commands;

use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class EnrichPropertyImagesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'properties:enrich-images {--all : Force re-enrich even if property already has images}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and attach full high-resolution image galleries (living room, bedrooms, kitchen, bathrooms, balcony) to properties';

    /**
     * Curated authentic 99acres real estate photo galleries.
     */
    protected array $galleries = [
        'pyramid_infinity' => [
            'https://imagecdn.99acres.com/media1/42017/5/840345375M-1789551687244.jpg',
            'https://imagecdn.99acres.com/media1/42017/9/840349907M-1789551825075.jpg',
            'https://imagecdn.99acres.com/media1/42017/5/840345401M-1789551661860.jpg',
            'https://imagecdn.99acres.com/media1/42017/5/840345399M-1789551664861.jpg',
            'https://imagecdn.99acres.com/media1/42017/9/840349911M-1789551801701.jpg',
            'https://imagecdn.99acres.com/media1/42017/9/840349909M-1789551845266.jpg',
            'https://imagecdn.99acres.com/media1/42017/5/840345379M-1789551706129.jpg',
        ],
        'signature_global' => [
            'https://imagecdn.99acres.com/media1/42871/0/857420409M-1791478098385.jpg',
            'https://imagecdn.99acres.com/media1/42871/0/857420395M-1791478098446.jpg',
            'https://imagecdn.99acres.com/media1/42871/0/857420393M-1791478098484.jpg',
            'https://imagecdn.99acres.com/media1/42871/0/857420397M-1791478098558.jpg',
            'https://imagecdn.99acres.com/media1/42871/0/857420401M-1791478098600.jpg',
            'https://imagecdn.99acres.com/media1/42871/0/857420405M-1791478098650.jpg',
        ],
        'emaar_marbella' => [
            'https://imagecdn.99acres.com/media1/43068/0/861360145M-1791968331875.jpg',
            'https://imagecdn.99acres.com/media1/35898/8/717968519M-1772008451141.jpg',
            'https://imagecdn.99acres.com/media1/35898/8/717968565M-1772008453429.jpg',
            'https://imagecdn.99acres.com/media1/43068/8/861368551M-1791969086996.jpg',
            'https://imagecdn.99acres.com/media1/43068/8/861368559M-1791969090000.jpg',
            'https://imagecdn.99acres.com/media1/35898/8/717968571M-1772008455000.jpg',
        ],
        'dlf_cyber_city' => [
            'https://imagecdn.99acres.com/media1/42940/15/858815159M-1791672462214.webp',
            'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1000&q=80',
            'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1000&q=80',
            'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1000&q=80',
            'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=1000&q=80',
            'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=1000&q=80',
        ],
        'luxury_apartments' => [
            'https://imagecdn.99acres.com/media1/42852/17/857057345M-1791448609652.webp',
            'https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=1000&q=80',
            'https://images.unsplash.com/photo-1600566753376-12c8ab7fb75b?auto=format&fit=crop&w=1000&q=80',
            'https://images.unsplash.com/photo-1600573472592-401b489a3cdc?auto=format&fit=crop&w=1000&q=80',
            'https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea?auto=format&fit=crop&w=1000&q=80',
            'https://images.unsplash.com/photo-1600585152220-90363fe7e115?auto=format&fit=crop&w=1000&q=80',
        ]
    ];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $forceAll = $this->option('all');
        $query = Property::query();

        if (!$forceAll) {
            $query->has('images', '<', 4);
        }

        $properties = $query->get();

        if ($properties->isEmpty()) {
            $this->info("All properties already have rich image galleries (3+ images each).");
            return 0;
        }

        $this->info("Enriching " . $properties->count() . " properties with complete image galleries...");

        $galleryKeys = array_keys($this->galleries);
        $enrichedCount = 0;

        foreach ($properties as $i => $property) {
            $existingPaths = $property->images()->pluck('path')->toArray();
            $galleryKey = $galleryKeys[$i % count($galleryKeys)];
            $photos = $this->galleries[$galleryKey];

            // If Sector 70, use pyramid infinity gallery
            if (stripos($property->title, 'Sector 70') !== false || stripos($property->locality, 'Sector 70') !== false) {
                $photos = $this->galleries['pyramid_infinity'];
            } elseif (stripos($property->title, 'Sector 102') !== false) {
                $photos = $this->galleries['signature_global'];
            } elseif (stripos($property->title, 'Sector 65') !== false) {
                $photos = $this->galleries['emaar_marbella'];
            }

            $currentMaxOrder = $property->images()->max('sort_order') ?? -1;
            $hasPrimary = $property->images()->where('is_primary', true)->exists();

            $added = 0;
            foreach ($photos as $idx => $photoUrl) {
                if (in_array($photoUrl, $existingPaths)) {
                    continue;
                }

                PropertyImage::create([
                    'property_id' => $property->id,
                    'path'        => $photoUrl,
                    'is_primary'  => (!$hasPrimary && $idx === 0),
                    'sort_order'  => ++$currentMaxOrder,
                ]);

                $existingPaths[] = $photoUrl;
                $hasPrimary = true;
                $added++;
            }

            $enrichedCount++;
            $totalNow = $property->images()->count();
            $this->line("  [✓] Property #{$property->id} ('{$property->title}'): added {$added} images (Total: {$totalNow} photos)");
        }

        Cache::forget('home_featured_rentals');
        Cache::forget('home_featured_properties');
        Cache::forget('admin_dashboard_stats');

        $this->info("\nSuccessfully enriched {$enrichedCount} properties with full image galleries!");
        return 0;
    }
}
