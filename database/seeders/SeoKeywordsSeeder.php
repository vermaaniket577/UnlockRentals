<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SeoKeyword;
use Illuminate\Support\Facades\File;

class SeoKeywordsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = database_path('data/seo_keywords.json');
        
        if (!File::exists($jsonPath)) {
            $jsonPath = storage_path('app/seo_keywords.json');
        }
        
        if (!File::exists($jsonPath)) {
            $jsonPath = base_path('scratch/seo_keywords.json');
        }

        if (!File::exists($jsonPath)) {
            $this->command->error("seo_keywords.json not found!");
            return;
        }

        $raw = File::get($jsonPath);
        if (str_starts_with($raw, "\xEF\xBB\xBF")) {
            $raw = substr($raw, 3);
        }
        $items = json_decode($raw, true);

        if (empty($items)) {
            $this->command->error("No items found in JSON!");
            return;
        }

        $this->command->info("Seeding " . count($items) . " SEO keywords...");

        $chunks = array_chunk($items, 200);

        foreach ($chunks as $chunk) {
            $records = [];
            $now = now();

            foreach ($chunk as $item) {
                $records[] = [
                    'keyword' => $item['keyword'] ?? '',
                    'search_intent' => $item['search_intent'] ?? null,
                    'category' => $item['category'] ?? null,
                    'priority' => $item['priority'] ?? 'Medium',
                    'city' => !empty($item['city']) ? $item['city'] : null,
                    'locality' => !empty($item['locality']) ? $item['locality'] : null,
                    'recommended_page' => $item['recommended_page'] ?? 'Category / Landing Page',
                    'seo_title' => $item['seo_title'] ?? '',
                    'meta_description' => $item['meta_description'] ?? '',
                    'url_slug' => '/' . ltrim($item['url_slug'] ?? '', '/'),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            SeoKeyword::upsert(
                $records,
                ['url_slug'],
                ['keyword', 'search_intent', 'category', 'priority', 'city', 'locality', 'recommended_page', 'seo_title', 'meta_description', 'updated_at']
            );
        }

        $this->command->info("Successfully seeded " . SeoKeyword::count() . " SEO keywords!");
    }
}
