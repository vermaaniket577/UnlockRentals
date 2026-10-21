<?php

namespace App\Console\Commands;

use App\Models\Property;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SanitizePropertyUrlsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'properties:sanitize-urls';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan all properties and sanitize/remove all 99acres URLs from title, location, locality, address, and description';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info("Scanning properties for URLs and 99acres links to sanitize...");

        $properties = DB::table('properties')->get();
        $cleanedCount = 0;

        foreach ($properties as $prop) {
            $updates = [];

            // 1. Clean location
            $locRaw = (string)($prop->location ?? '');
            if (preg_match('/(https?:\/\/|www\.|\b99acres)/i', $locRaw)) {
                $cleanLoc = Property::cleanUrlFromText($locRaw, true);
                if (empty($cleanLoc) || str_starts_with(strtolower($cleanLoc), 'http')) {
                    [$city] = Property::extractCityAndStateFromUrl($locRaw);
                    $cleanLoc = $city ?: 'Delhi';
                }
                $updates['location'] = $cleanLoc;
            } else {
                $cleanLoc = $locRaw ?: 'Delhi';
            }

            // 2. Clean locality
            $localityRaw = (string)($prop->locality ?? '');
            if (preg_match('/(https?:\/\/|www\.|\b99acres)/i', $localityRaw)) {
                $cleanLocality = Property::cleanUrlFromText($localityRaw, false);
                $updates['locality'] = !empty($cleanLocality) ? $cleanLocality : $cleanLoc;
            }

            // 3. Clean address
            $addressRaw = (string)($prop->address ?? '');
            if (preg_match('/(https?:\/\/|www\.|\b99acres)/i', $addressRaw)) {
                $cleanAddress = Property::cleanUrlFromText($addressRaw, true);
                $updates['address'] = !empty($cleanAddress) ? $cleanAddress : (($updates['locality'] ?? $prop->locality ?? 'Sector') . ', ' . $cleanLoc);
            }

            // 4. Clean title
            $titleRaw = (string)($prop->title ?? '');
            if (preg_match('/(https?:\/\/|www\.|\b99acres)/i', $titleRaw)) {
                $cleanTitle = Property::cleanUrlFromText($titleRaw, true);
                $updates['title'] = !empty($cleanTitle) ? $cleanTitle : (($prop->bedrooms ?? 2) . ' BHK Flat in ' . ($updates['locality'] ?? $cleanLoc));
            }

            // 5. Clean description
            $descRaw = (string)($prop->description ?? '');
            if (preg_match('/(https?:\/\/|www\.|\b99acres)/i', $descRaw)) {
                $cleanDesc = Property::cleanUrlFromText($descRaw, false);
                $updates['description'] = $cleanDesc;
            }

            // 6. Clean state
            $stateRaw = (string)($prop->state ?? '');
            if (preg_match('/(https?:\/\/|www\.|\b99acres)/i', $stateRaw)) {
                [, $resolvedState] = Property::extractCityAndStateFromUrl($locRaw);
                $updates['state'] = $resolvedState ?: 'Haryana';
            }

            if (!empty($updates)) {
                $updates['updated_at'] = now();
                DB::table('properties')
                    ->where('id', $prop->id)
                    ->update($updates);

                $this->line(" [✓] Sanitized Property #{$prop->id}: " . ($updates['title'] ?? $prop->title));
                $cleanedCount++;
            }
        }

        Cache::forget('home_featured_rentals');
        Cache::forget('home_featured_properties');
        Cache::forget('admin_dashboard_stats');

        $this->info("\nDone! Sanitized {$cleanedCount} properties. All 99acres URLs have been successfully removed.");
        return 0;
    }
}
