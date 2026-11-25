<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class PropertyScraperImportService
{
    /**
     * Ingest scraped property items into the database with deduplication,
     * owner account linking/creation, category/PG resolution, and gallery images.
     */
    public function importItems(array $items, array $options = []): array
    {
        $defaultCity = (string) ($options['city'] ?? 'Delhi');
        $defaultState = (string) ($options['state'] ?? 'India');
        $defaultPurpose = (string) ($options['purpose'] ?? 'rent');
        $propertyType = $options['property_type'] ?? 'all';
        $engine = (string) ($options['engine'] ?? 'playwright');
        $createAccounts = (bool) ($options['create_accounts'] ?? true);
        $autoApprove = (bool) ($options['auto_approve'] ?? true);
        $featureFirstN = (int) ($options['feature_first_n'] ?? 0);

        $adminUser = User::where('role', 'admin')->first() ?? User::first();
        $defaultUserId = $adminUser ? $adminUser->id : 1;

        $createdAccounts = [];
        $createdProperties = [];
        $duplicatesSkippedCount = 0;
        $insertedCount = 0;

        foreach ($items as $item) {
            if (!is_array($item) || empty($item['title'])) {
                continue;
            }

            $propId = trim((string) ($item['prop_id'] ?? $item['external_id'] ?? ''));
            $candidateCheck = [
                'external_id'   => $propId,
                'prop_id'       => $propId,
                'title'         => $item['title'],
                'city'          => $item['city'] ?? $item['location'] ?? $defaultCity,
                'location'      => $item['city'] ?? $item['location'] ?? $defaultCity,
                'locality'      => $item['locality'] ?? '',
                'price'         => $item['price'] ?? 25000,
                'purpose'       => $item['purpose'] ?? $defaultPurpose,
                'bedrooms'      => $item['bedrooms'] ?? 2,
                'images'        => $item['images'] ?? [],
                'contact_phone' => $item['contact_phone'] ?? null,
            ];

            $existing = Property::findDuplicate($candidateCheck);
            if ($existing) {
                $duplicatesSkippedCount++;
                if ($existing->images()->count() === 0 && !empty($item['images']) && is_array($item['images'])) {
                    foreach ($item['images'] as $idx => $imgUrl) {
                        PropertyImage::create([
                            'property_id' => $existing->id,
                            'path'        => $imgUrl,
                            'is_primary'  => ($idx === 0),
                            'sort_order'  => $idx,
                        ]);
                    }
                }
                continue;
            }

            $propertyUserId = $defaultUserId;
            $ownerName = 'Admin';
            $sellerName = trim((string) ($item['seller_name'] ?? ''));

            if ($createAccounts && $sellerName !== '' && strtolower($sellerName) !== 'property owner') {
                $contactPhone = $item['contact_phone'] ?? null;
                $company = $item['seller_company'] ?? null;
                $photo = $item['seller_photo'] ?? null;
                $scrapedEmail = trim((string) ($item['seller_email'] ?? $item['contact_email'] ?? $item['email'] ?? ''));

                $phoneDigits = preg_replace('/\D/', '', (string) $contactPhone);
                $suffix = $phoneDigits !== '' ? substr($phoneDigits, -4) : (string) rand(1000, 9999);
                $cleanSlug = Str::slug($sellerName) ?: 'owner';

                if ($scrapedEmail !== '' && filter_var($scrapedEmail, FILTER_VALIDATE_EMAIL) && !str_ends_with(strtolower($scrapedEmail), '@unlockrentals.com')) {
                    $email = strtolower($scrapedEmail);
                } else {
                    $email = "{$cleanSlug}.{$suffix}@gmail.com";
                }

                $sellerUser = User::where('email', $email)->first();
                if (!$sellerUser && !empty($contactPhone)) {
                    $sellerUser = User::where('phone', $contactPhone)->first();
                }
                if (!$sellerUser) {
                    $fullName = $sellerName . (!empty($company) && $company !== $sellerName ? " ({$company})" : '');
                    $sellerUser = User::create([
                        'name'              => $fullName,
                        'email'             => $email,
                        'phone'             => $contactPhone,
                        'password'          => \Illuminate\Support\Facades\Hash::make('Password@123'),
                        'role'              => 'owner',
                        'avatar'            => $photo ?: null,
                        'email_verified_at' => now(),
                        'phone_verified_at' => now(),
                    ]);
                    $createdAccounts[] = [
                        'id'    => $sellerUser->id,
                        'name'  => $sellerUser->name,
                        'email' => $sellerUser->email,
                        'phone' => $sellerUser->phone,
                        'role'  => $sellerUser->role,
                    ];
                }
                $propertyUserId = $sellerUser->id;
                $ownerName = $sellerUser->name;
            }

            $catName = $item['category_name'] ?? 'Flat / Apartment';
            $category = Category::where('name', $catName)
                ->orWhere('name', 'like', "%{$catName}%")
                ->first();
            if (!$category) {
                $category = Category::where('slug', Str::slug($catName))->first();
            }
            if (!$category) {
                $category = Category::where('name', 'like', '%Flat%')
                    ->orWhere('name', 'like', '%Apartment%')
                    ->first() ?? Category::first();
            }
            $itemCategoryId = $category ? $category->id : 1;

            $itemType = in_array($item['type'] ?? '', ['house', 'shop', 'pg-hostel', 'hotel', 'plot'], true)
                ? $item['type']
                : 'house';
            $itemPurpose = in_array($item['purpose'] ?? '', ['rent', 'buy'], true)
                ? $item['purpose']
                : (in_array($defaultPurpose, ['rent', 'buy'], true) ? $defaultPurpose : 'rent');

            $isPg = in_array(strtolower($itemType), ['pg', 'pg-hostel'], true)
                || in_array(strtolower((string) ($propertyType ?? '')), ['pg', 'pg-hostel'], true)
                || str_contains(strtolower((string) ($item['title'] ?? '')), 'pg')
                || str_contains(strtolower((string) ($item['title'] ?? '')), 'paying guest')
                || str_contains(strtolower((string) ($item['category_name'] ?? '')), 'pg');

            if ($isPg) {
                $itemType = 'pg-hostel';
                $itemPurpose = 'rent';
                $pgCategory = Category::where('slug', 'pg-hostel')
                    ->orWhere('name', 'like', '%PG%')
                    ->first();
                if ($pgCategory) {
                    $itemCategoryId = $pgCategory->id;
                    $category = $pgCategory;
                }
            }

            $itemCity = Property::cleanUrlFromText($item['city'] ?? $item['location'] ?? $defaultCity, true) ?: ($defaultCity ?: 'Delhi');
            $itemState = Property::cleanUrlFromText($item['state'] ?? $defaultState, false) ?: ($defaultState ?: 'India');
            $itemLocality = Property::cleanUrlFromText($item['locality'] ?? $itemCity, false) ?: $itemCity;
            $itemTitle = Property::cleanUrlFromText($item['title'] ?? '', true) ?: (($item['bedrooms'] ?? 2) . " BHK Property in {$itemLocality}");
            $itemDesc = Property::cleanUrlFromText($item['description'] ?? ("{$itemTitle} in {$itemLocality}, {$itemCity}"), false);
            $itemAddress = Property::cleanUrlFromText($item['address'] ?? "{$itemLocality}, {$itemCity}", true) ?: "{$itemLocality}, {$itemCity}";

            $furnishingRaw = strtolower(trim((string) ($item['furnishing'] ?? '')));
            $furnishing = $itemType === 'plot'
                ? 'unfurnished'
                : (in_array($furnishingRaw, ['furnished', 'fully', 'fully-furnished', 'full'], true)
                    ? 'fully-furnished'
                    : (str_contains($furnishingRaw, 'semi') ? 'semi-furnished' : 'unfurnished'));

            $propPayload = [
                'user_id'       => $propertyUserId,
                'category_id'   => $itemCategoryId,
                'title'         => $itemTitle,
                'slug'          => Str::slug($itemTitle) . '-' . rand(10000, 99999),
                'description'   => $itemDesc,
                'type'          => $itemType,
                'purpose'       => $itemPurpose,
                'price'         => $item['price'] ?? 25000,
                'price_period'  => 'month',
                'state'         => $itemState,
                'location'      => $itemCity,
                'locality'      => $itemLocality,
                'address'       => $itemAddress,
                'contact_phone' => $item['contact_phone'] ?? '+91 98765 43210',
                'bedrooms'      => $itemType === 'plot' ? 0 : ($item['bedrooms'] ?? 2),
                'bathrooms'     => $itemType === 'plot' ? 0 : ($item['bathrooms'] ?? 1),
                'area_sqft'     => $item['area_sqft'] ?? 800,
                'furnishing'    => $furnishing,
                'status'        => $autoApprove ? 'approved' : 'pending',
                'approved_at'   => $autoApprove ? now() : null,
                'is_featured'   => ($featureFirstN > 0 && $insertedCount < $featureFirstN),
                'is_booked'     => false,
            ];
            if (Property::ensureExternalIdColumnExists() && $propId !== '') {
                $propPayload['external_id'] = $propId;
            }

            $prop = Property::create($propPayload);
            $insertedCount++;

            if (!empty($item['images']) && is_array($item['images'])) {
                foreach ($item['images'] as $idx => $imgUrl) {
                    PropertyImage::create([
                        'property_id' => $prop->id,
                        'path'        => $imgUrl,
                        'is_primary'  => ($idx === 0),
                        'sort_order'  => $idx,
                    ]);
                }
            }

            $createdProperties[] = [
                'id'            => $prop->id,
                'title'         => $prop->title,
                'price'         => $prop->price,
                'purpose'       => $prop->purpose,
                'type'          => $prop->type,
                'category'      => $category?->name ?? 'Property',
                'locality'      => $prop->locality,
                'city'          => $prop->location,
                'state'         => $prop->state,
                'owner'         => $ownerName,
                'contact_phone' => $prop->contact_phone,
                'thumbnail'     => !empty($item['images']) ? $item['images'][0] : null,
                'images_count'  => count($item['images'] ?? []),
                'bedrooms'      => $prop->bedrooms,
                'bathrooms'     => $prop->bathrooms,
                'furnishing'    => $prop->furnishing,
                'area_sqft'     => $prop->area_sqft,
                'engine'        => $engine,
                'is_genuine'    => !empty($item['is_genuine']),
                'view_url'      => route('properties.show', $prop->slug),
            ];
        }

        try {
            \Illuminate\Support\Facades\Artisan::call('properties:enrich-images');
        } catch (\Throwable $e) {
        }

        Cache::forget('home_featured_rentals');
        Cache::forget('home_featured_properties');
        Cache::forget('admin_dashboard_stats');

        return [
            'imported_count'       => $insertedCount,
            'duplicates_prevented' => $duplicatesSkippedCount,
            'accounts_created'     => count($createdAccounts),
            'accounts'             => $createdAccounts,
            'properties'           => $createdProperties,
        ];
    }

    public function resolveLiveBaseUrl(?string $override = null): string
    {
        $url = trim((string) ($override ?: env('LIVE_SYNC_URL', 'https://www.unlockrentals.com')));
        if ($url === '') {
            $url = 'https://www.unlockrentals.com';
        }
        return rtrim($url, '/');
    }

    public function isSelfLiveHost(string $targetBaseUrl, ?string $currentHost = null): bool
    {
        $targetHost = strtolower(preg_replace('/^www\./', '', (string) parse_url($targetBaseUrl, PHP_URL_HOST)));
        $host = strtolower(preg_replace('/^www\./', '', (string) ($currentHost ?: parse_url((string) config('app.url'), PHP_URL_HOST))));
        return $targetHost !== '' && $host !== '' && $targetHost === $host;
    }

    /**
     * Push raw scraped property items directly to the live server's sync-crawler API.
     */
    public function pushItemsToLive(array $items, array $options = []): array
    {
        if (empty($items)) {
            return [
                'success' => false,
                'message' => 'No scraped listings to push to live server.',
            ];
        }

        $liveBaseUrl = $this->resolveLiveBaseUrl($options['live_url'] ?? null);
        if ($this->isSelfLiveHost($liveBaseUrl, $options['current_host'] ?? null)) {
            return [
                'success' => false,
                'skipped' => true,
                'message' => 'Already running on live server host.',
            ];
        }

        $endpoint = $liveBaseUrl . '/api/properties/sync-crawler';

        try {
            $response = Http::timeout(60)->acceptJson()->post($endpoint, [
                'properties'      => array_values($items),
                'city'            => $options['city'] ?? 'Delhi',
                'purpose'         => $options['purpose'] ?? 'rent',
                'property_type'   => $options['property_type'] ?? 'all',
                'create_accounts' => $options['create_accounts'] ?? true,
                'auto_approve'    => $options['auto_approve'] ?? true,
                'page'            => $options['page'] ?? 1,
            ]);

            $body = $response->json();
            if ($response->successful() && is_array($body) && (!empty($body['success']) || ($body['status'] ?? '') === 'success')) {
                return [
                    'success'              => true,
                    'live_url'             => $liveBaseUrl,
                    'imported_count'       => (int) ($body['new_imported'] ?? $body['imported_count'] ?? 0),
                    'duplicates_prevented' => (int) ($body['duplicates_prevented'] ?? 0),
                    'accounts_created'     => (int) ($body['accounts_created'] ?? 0),
                    'message'              => $body['message'] ?? "Synced listings to {$liveBaseUrl}.",
                ];
            }

            return [
                'success'  => false,
                'live_url' => $liveBaseUrl,
                'message'  => is_array($body) && !empty($body['message'])
                    ? "Live sync error ({$response->status()}): {$body['message']}"
                    : "Live sync failed with HTTP {$response->status()}.",
            ];
        } catch (\Throwable $e) {
            return [
                'success'  => false,
                'live_url' => $liveBaseUrl,
                'message'  => 'Could not reach live server: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Push recently saved local properties to the live server.
     */
    public function pushLocalPropertiesToLive(int $limit = 20, ?string $liveUrl = null, ?string $currentHost = null): array
    {
        if (!Schema::hasTable('properties')) {
            return [
                'success' => false,
                'message' => 'Local properties database table is not set up yet.',
            ];
        }

        $hasExternalId = Property::ensureExternalIdColumnExists();
        $localProps = Property::with(['images', 'user', 'category'])
            ->latest('id')
            ->take(max(1, min(60, $limit)))
            ->get();

        if ($localProps->isEmpty()) {
            return [
                'success' => false,
                'message' => 'No local properties found to sync to live.',
            ];
        }

        $items = [];
        foreach ($localProps as $p) {
            $images = $p->images->pluck('path')->filter()->values()->all();
            $items[] = [
                'prop_id'       => $hasExternalId ? ($p->external_id ?? '') : '',
                'external_id'   => $hasExternalId ? ($p->external_id ?? '') : '',
                'title'         => $p->title,
                'description'   => $p->description,
                'type'          => $p->type,
                'purpose'       => $p->purpose,
                'category_name' => $p->category?->name ?? 'Flat / Apartment',
                'price'         => $p->price,
                'state'         => $p->state,
                'city'          => $p->location,
                'location'      => $p->location,
                'locality'      => $p->locality,
                'address'       => $p->address,
                'contact_phone' => $p->contact_phone,
                'bedrooms'      => $p->bedrooms,
                'bathrooms'     => $p->bathrooms,
                'area_sqft'     => $p->area_sqft,
                'furnishing'    => $p->furnishing,
                'seller_name'   => $p->user?->name ?? 'Property Owner',
                'seller_email'  => $p->user?->email ?? '',
                'images'        => $images,
            ];
        }

        return $this->pushItemsToLive($items, [
            'live_url'     => $liveUrl,
            'current_host' => $currentHost,
            'city'         => $localProps->first()->location ?? 'Delhi',
            'purpose'      => $localProps->first()->purpose ?? 'rent',
        ]);
    }
}
