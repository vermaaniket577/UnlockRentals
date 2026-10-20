<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ScrapedPropertiesSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('role', 'admin')->first() ?? User::first();
        $userId = $user ? $user->id : 1;

        $cat = Category::where('name', 'like', '%Flat%')->orWhere('name', 'like', '%Apartment%')->first() ?? Category::first();
        $catId = $cat ? $cat->id : 1;

        $properties = [
            [
                'title' => '3 BHK Flat for rent in Sector 70, Gurgaon',
                'description' => 'Beautiful 3 BHK apartment available for rent in Sector 70, Gurgaon. Spacious carpet area of 645 sq.ft. with 2 bathrooms. Comes semi-furnished. Situated close to public transit, markets, and key hubs.',
                'price' => 26000.0,
                'state' => 'Haryana',
                'location' => 'Gurgaon',
                'locality' => 'Sector 70',
                'address' => 'Sector 70, Gurgaon, Haryana',
                'contact_phone' => '+91 77662 50645',
                'bedrooms' => 3,
                'bathrooms' => 2,
                'area_sqft' => 645,
                'furnishing' => 'semi-furnished',
                'images' => ['https://imagecdn.99acres.com/media1/42017/5/840345375M-1789551687244.webp'],
            ],
            [
                'title' => '3 BHK Flat for rent in Sector 102, Gurgaon',
                'description' => 'Beautiful 3 BHK apartment available for rent in Sector 102, Gurgaon. Spacious carpet area of 1650 sq.ft. with 2 bathrooms. Comes semi-furnished. Situated close to public transit, markets, and key hubs.',
                'price' => 36000.0,
                'state' => 'Haryana',
                'location' => 'Gurgaon',
                'locality' => 'Sector 102',
                'address' => 'Sector 102, Gurgaon, Haryana',
                'contact_phone' => '+91 90200 82120',
                'bedrooms' => 3,
                'bathrooms' => 2,
                'area_sqft' => 1650,
                'furnishing' => 'semi-furnished',
                'images' => ['https://imagecdn.99acres.com/media1/42852/17/857057345M-1791448609652.webp'],
            ],
            [
                'title' => '3 BHK Flat for rent in Sector 65, Gurgaon',
                'description' => 'Beautiful 3 BHK apartment available for rent in Sector 65, Gurgaon. Spacious carpet area of 3525 sq.ft. with 2 bathrooms. Comes semi-furnished. Situated close to public transit, markets, and key hubs.',
                'price' => 280000.0,
                'state' => 'Haryana',
                'location' => 'Gurgaon',
                'locality' => 'Sector 65',
                'address' => 'Sector 65, Gurgaon, Haryana',
                'contact_phone' => '+91 81669 97980',
                'bedrooms' => 3,
                'bathrooms' => 2,
                'area_sqft' => 3525,
                'furnishing' => 'semi-furnished',
                'images' => ['https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1000&q=80'],
            ],
            [
                'title' => '4 BHK Flat for rent in Sector 65, Gurgaon',
                'description' => 'Beautiful 4 BHK apartment available for rent in Sector 65, Gurgaon. Spacious carpet area of 4550 sq.ft. with 3 bathrooms. Comes semi-furnished. Situated close to public transit, markets, and key hubs.',
                'price' => 370000.0,
                'state' => 'Haryana',
                'location' => 'Gurgaon',
                'locality' => 'Sector 65',
                'address' => 'Sector 65, Gurgaon, Haryana',
                'contact_phone' => '+91 71775 18773',
                'bedrooms' => 4,
                'bathrooms' => 3,
                'area_sqft' => 4550,
                'furnishing' => 'semi-furnished',
                'images' => ['https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1000&q=80'],
            ],
            [
                'title' => '4 BHK Flat for rent in Sector 65, Gurgaon',
                'description' => 'Beautiful 4 BHK apartment available for rent in Sector 65, Gurgaon. Spacious carpet area of 4980 sq.ft. with 3 bathrooms. Comes semi-furnished. Situated close to public transit, markets, and key hubs.',
                'price' => 270000.0,
                'state' => 'Haryana',
                'location' => 'Gurgaon',
                'locality' => 'Sector 65',
                'address' => 'Sector 65, Gurgaon, Haryana',
                'contact_phone' => '+91 79367 26376',
                'bedrooms' => 4,
                'bathrooms' => 3,
                'area_sqft' => 4980,
                'furnishing' => 'semi-furnished',
                'images' => ['https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1000&q=80'],
            ],
            [
                'title' => '4 BHK Flat for rent in Sector 46, Noida',
                'description' => 'Beautiful 4 BHK apartment available for rent in Sector 46, Noida. Spacious carpet area of 2950 sq.ft. with 3 bathrooms. Comes semi-furnished. Situated close to public transit, markets, and key hubs.',
                'price' => 55000.0,
                'state' => 'Uttar Pradesh',
                'location' => 'Noida',
                'locality' => 'Sector 46',
                'address' => 'Sector 46, Noida, Uttar Pradesh',
                'contact_phone' => '+91 79344 89680',
                'bedrooms' => 4,
                'bathrooms' => 3,
                'area_sqft' => 2950,
                'furnishing' => 'semi-furnished',
                'images' => ['https://imagecdn.99acres.com/media1/40072/13/801453641M-1784879061894.webp'],
            ],
            [
                'title' => '1 BHK Flat for rent in Sector 137, Noida',
                'description' => 'Beautiful 1 BHK apartment available for rent in Sector 137, Noida. Spacious carpet area of 450 sq.ft. with 1 bathrooms. Comes semi-furnished. Situated close to public transit, markets, and key hubs.',
                'price' => 19000.0,
                'state' => 'Uttar Pradesh',
                'location' => 'Noida',
                'locality' => 'Sector 137',
                'address' => 'Sector 137, Noida, Uttar Pradesh',
                'contact_phone' => '+91 99632 13945',
                'bedrooms' => 1,
                'bathrooms' => 1,
                'area_sqft' => 450,
                'furnishing' => 'semi-furnished',
                'images' => ['https://imagecdn.99acres.com/media1/40464/15/809295935M-1785838619926.webp'],
            ],
            [
                'title' => '3 BHK Flat for rent in Sector 29, Noida',
                'description' => 'Beautiful 3 BHK apartment available for rent in Sector 29, Noida. Spacious carpet area of 1700 sq.ft. with 2 bathrooms. Comes semi-furnished. Situated close to public transit, markets, and key hubs.',
                'price' => 56000.0,
                'state' => 'Uttar Pradesh',
                'location' => 'Noida',
                'locality' => 'Sector 29',
                'address' => 'Sector 29, Noida, Uttar Pradesh',
                'contact_phone' => '+91 89590 28436',
                'bedrooms' => 3,
                'bathrooms' => 2,
                'area_sqft' => 1700,
                'furnishing' => 'semi-furnished',
                'images' => ['https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1000&q=80'],
            ],
            [
                'title' => '3 BHK Flat for rent in Sector 70, Gurgaon',
                'description' => 'Spacious 3 BHK apartment available for rent in Sector 70, Gurgaon. Carpet area of 645 sq.ft. with 2 bathrooms. Comes semi-furnished. Situated close to public transit, markets, and key hubs.',
                'price' => 26000.0,
                'state' => 'Haryana',
                'location' => 'Gurgaon',
                'locality' => 'Sector 70',
                'address' => 'Sector 70, Gurgaon',
                'contact_phone' => '+91 79665 70927',
                'bedrooms' => 3,
                'bathrooms' => 2,
                'area_sqft' => 645,
                'furnishing' => 'semi-furnished',
                'images' => ['https://imagecdn.99acres.com/media1/42017/5/840345375M-1789551687244.webp'],
            ],
            [
                'title' => '2 BHK Flat for rent in Sector 88A, Gurgaon',
                'description' => 'Spacious 2 BHK apartment available for rent in Sector 88A, Gurgaon. Carpet area of 84 sq.ft. with 1 bathrooms. Comes unfurnished. Situated close to public transit, markets, and key hubs.',
                'price' => 14999.0,
                'state' => 'Haryana',
                'location' => 'Gurgaon',
                'locality' => 'Sector 88A',
                'address' => 'Sector 88A, Gurgaon',
                'contact_phone' => '+91 81000 70377',
                'bedrooms' => 2,
                'bathrooms' => 1,
                'area_sqft' => 84,
                'furnishing' => 'unfurnished',
                'images' => ['https://imagecdn.99acres.com/media1/37479/10/749590664M-1777186903281.webp'],
            ],
        ];

        foreach ($properties as $idx => $p) {
            if (Property::where('title', $p['title'])->where('location', $p['location'])->exists()) {
                continue;
            }

            $slug = Str::slug($p['title']) . '-' . rand(10000, 99999);

            $created = Property::create([
                'user_id' => $userId,
                'category_id' => $catId,
                'title' => $p['title'],
                'slug' => $slug,
                'description' => $p['description'],
                'type' => 'house',
                'purpose' => 'rent',
                'price' => $p['price'],
                'price_period' => 'month',
                'state' => $p['state'],
                'location' => $p['location'],
                'locality' => $p['locality'],
                'address' => $p['address'],
                'contact_phone' => $p['contact_phone'],
                'bedrooms' => $p['bedrooms'],
                'bathrooms' => $p['bathrooms'],
                'area_sqft' => $p['area_sqft'],
                'furnishing' => $p['furnishing'],
                'status' => 'approved',
                'is_featured' => ($idx < 3),
                'is_booked' => false,
                'approved_at' => now(),
            ]);

            foreach ($p['images'] as $imgIdx => $imgUrl) {
                PropertyImage::create([
                    'property_id' => $created->id,
                    'path' => $imgUrl,
                    'is_primary' => ($imgIdx === 0),
                    'sort_order' => $imgIdx,
                ]);
            }
        }

        Cache::forget('home_featured_rentals');
    }
}
