<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Property;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@unlockrentals.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create Owners
        $owner1 = User::create([
            'name' => 'John Owner',
            'email' => 'owner1@unlockrentals.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'phone' => '+91 98765 11111',
            'email_verified_at' => now(),
        ]);

        $owner2 = User::create([
            'name' => 'Sarah Landlord',
            'email' => 'owner2@unlockrentals.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'phone' => '+91 98765 22222',
            'email_verified_at' => now(),
        ]);

        // Create Tenants
        User::create([
            'name' => 'Michael Tenant',
            'email' => 'tenant@unlockrentals.com',
            'password' => Hash::make('password'),
            'role' => 'tenant',
            'phone' => '+91 98765 33333',
            'email_verified_at' => now(),
        ]);

        // Create Categories
        $categories = [
            ['name' => 'Residential House', 'icon' => 'house', 'description' => 'Great places to live with your family.'],
            ['name' => 'Luxury Villa', 'icon' => 'castle-turret', 'description' => 'Premium living experience.'],
            ['name' => 'Studio Apartment', 'icon' => 'buildings', 'description' => 'Compact and modern apartments.'],
            ['name' => 'Retail Shop', 'icon' => 'storefront', 'description' => 'Perfect locations for your business.'],
            ['name' => 'Office Space', 'icon' => 'briefcase', 'description' => 'Professional working environments.'],
        ];

        $createdCategories = [];
        foreach ($categories as $cat) {
            $createdCategories[] = Category::create([
                'name' => $cat['name'],
                'slug' => Str::slug($cat['name']),
                'icon' => $cat['icon'],
                'description' => $cat['description'],
            ]);
        }

        // Create Properties
        $properties = [
            [
                'user_id' => $owner1->id,
                'category_id' => $createdCategories[0]->id, // Residential House
                'title' => 'Beautiful 3BHK Family Home in Bandra',
                'description' => "Welcome to this spacious 3 bedroom, 2 bathroom home in the heart of Bandra. This property features a large living room, fully equipped kitchen, and a beautiful backyard perfect for family gatherings. Located near top schools, parks, and shopping centers.\n\nAmenities include:\n- 24/7 Security\n- Covered Parking\n- Power Backup\n- Garden View",
                'type' => 'house',
                'price' => 85000,
                'price_period' => 'month',
                'location' => 'Bandra, Mumbai',
                'address' => '123 Carter Road, Near Joggers Park',
                'bedrooms' => 3,
                'bathrooms' => 2,
                'area_sqft' => 1500,
                'furnishing' => 'semi-furnished',
                'status' => 'approved',
                'is_featured' => true,
                'approved_at' => now(),
            ],
            [
                'user_id' => $owner2->id,
                'category_id' => $createdCategories[3]->id, // Retail Shop
                'title' => 'Prime Corner Retail Space in Andheri',
                'description' => "Exceptional corner retail shop available in a high foot-traffic area. Perfect for a boutique, cafe, or electronics store. The shop features full glass frontage, high ceilings, and pre-installed air conditioning.\n\nHighlights:\n- Premium frontage\n- Dense residential catchment\n- Near Metro Station",
                'type' => 'shop',
                'price' => 120000,
                'price_period' => 'month',
                'location' => 'Andheri West, Mumbai',
                'address' => 'Shop No. 5, Link Road Appts',
                'bedrooms' => null,
                'bathrooms' => 1,
                'area_sqft' => 800,
                'furnishing' => 'unfurnished',
                'status' => 'approved',
                'is_featured' => true,
                'approved_at' => now(),
            ],
            [
                'user_id' => $owner1->id,
                'category_id' => $createdCategories[2]->id, // Studio Apartment
                'title' => 'Cozy Studio near Tech Park',
                'description' => "Fully furnished studio apartment ideal for working professionals. Located just 5 minutes walk from major IT parks. Rent includes Wi-Fi and weekly housekeeping.\n\nIncludes:\n- Queen size bed\n- Wardrobe\n- TV and Fridge\n- Induction cooktop",
                'type' => 'house',
                'price' => 25000,
                'price_period' => 'month',
                'location' => 'Powai, Mumbai',
                'address' => 'Hiranandani Gardens, Powai',
                'bedrooms' => 1,
                'bathrooms' => 1,
                'area_sqft' => 450,
                'furnishing' => 'fully-furnished',
                'status' => 'approved',
                'is_featured' => false,
                'approved_at' => now(),
            ],
            [
                'user_id' => $owner2->id,
                'category_id' => $createdCategories[1]->id, // Luxury Villa
                'title' => 'Stunning Sea-View Villa in Alibaug',
                'description' => "Escape the city to this stunning 4-bedroom villa with private pool and panoramic sea views. Perfect for a vacation home or long-term lavish living. Features modern architecture, expansive decks, and direct beach access.",
                'type' => 'house',
                'price' => 200000,
                'price_period' => 'month',
                'location' => 'Alibaug',
                'address' => 'Mandwa Beach Road, Alibaug',
                'bedrooms' => 4,
                'bathrooms' => 5,
                'area_sqft' => 4000,
                'furnishing' => 'fully-furnished',
                'status' => 'pending', // Pending approval
                'is_featured' => false,
                'approved_at' => null,
            ]
        ];

        foreach ($properties as $propData) {
            Property::create($propData);
        }
    }
}
