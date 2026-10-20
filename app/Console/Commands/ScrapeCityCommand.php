<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ScrapeCityCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:city {city=Gurgaon : Name of city (Gurgaon, Noida, Delhi, Mumbai, Pune, Bangalore, Hyderabad, or all)} {--count=10 : Number of properties to scrape}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawl real estate listings from 99acres and import directly into the database';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $city = $this->argument('city');
        $count = (int) $this->option('count');

        $this->info("Starting scraper for: {$city} (Target: {$count} listings)...");

        $scriptPath = base_path('scrapers/crawl_and_import.py');
        if (!file_exists($scriptPath)) {
            $this->error("Scraper script not found at {$scriptPath}");
            return 1;
        }

        // Try python3, then python
        $pythonBinary = 'python3';
        exec("python3 --version 2>&1", $out, $code);
        if ($code !== 0) {
            $pythonBinary = 'python';
        }

        $command = escapeshellcmd("{$pythonBinary} {$scriptPath} {$city} {$count} --json");
        $this->line("Running: {$command}");

        exec($command, $output, $returnCode);

        if ($returnCode !== 0) {
            $this->error("Python scraper failed with code {$returnCode}:");
            $this->line(implode("\n", $output));
            return 1;
        }

        $rawOutput = implode("\n", $output);
        // Find JSON block between ===JSON_START=== and ===JSON_END===
        if (!preg_match('/===JSON_START===\s*(.*?)\s*===JSON_END===/s', $rawOutput, $matches)) {
            $this->error("Could not find structured JSON output from scraper.");
            $this->line($rawOutput);
            return 1;
        }

        $items = json_decode($matches[1], true);
        if (!is_array($items)) {
            $this->error("Invalid JSON decoded from scraper.");
            return 1;
        }

        $adminUser = User::where('role', 'admin')->first() ?? User::first();
        $userId = $adminUser ? $adminUser->id : 1;

        $category = Category::where('name', 'like', '%Flat%')
            ->orWhere('name', 'like', '%Apartment%')
            ->first() ?? Category::first();
        $categoryId = $category ? $category->id : 1;

        $insertedCount = 0;

        foreach ($items as $item) {
            $slug = Str::slug($item['title']) . '-' . rand(10000, 99999);

            $property = Property::create([
                'user_id' => $userId,
                'category_id' => $categoryId,
                'title' => $item['title'],
                'slug' => $slug,
                'description' => $item['description'] ?? "Rental flat in {$item['locality']}, {$item['city']}.",
                'type' => 'house',
                'purpose' => 'rent',
                'price' => $item['price'] ?? 25000,
                'price_period' => 'month',
                'state' => $item['state'] ?? 'India',
                'location' => $item['city'],
                'locality' => $item['locality'],
                'address' => "{$item['locality']}, {$item['city']}",
                'contact_phone' => $item['contact_phone'] ?? '+91 98765 43210',
                'bedrooms' => $item['bedrooms'] ?? 2,
                'bathrooms' => $item['bathrooms'] ?? 1,
                'area_sqft' => $item['area_sqft'] ?? 800,
                'furnishing' => $item['furnishing'] ?? 'semi-furnished',
                'status' => 'approved',
                'is_featured' => ($insertedCount < 3),
                'is_booked' => false,
                'approved_at' => now(),
            ]);

            if (!empty($item['images']) && is_array($item['images'])) {
                foreach ($item['images'] as $idx => $imgUrl) {
                    PropertyImage::create([
                        'property_id' => $property->id,
                        'path' => $imgUrl,
                        'is_primary' => ($idx === 0),
                        'sort_order' => $idx,
                    ]);
                }
            }

            $insertedCount++;
            $this->line("  [✓] Imported ID #{$property->id}: {$property->title} | ₹" . number_format($property->price) . "/mo");
        }

        // Invalidate homepage cache
        Cache::forget('home_featured_rentals');

        $this->info("\nSuccessfully imported {$insertedCount} properties into the database!");
        return 0;
    }
}
