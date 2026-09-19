<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PlotPropertyTest extends TestCase
{
    use RefreshDatabase;
    public function test_user_can_create_plot_property()
    {
        Storage::fake('public');
        $user = User::factory()->create(['role' => 'owner']);
        $category = Category::firstOrCreate(
            ['slug' => 'plots-and-land'],
            ['name' => 'Plots & Land', 'icon' => 'ph-map-trifold', 'sort_order' => 5, 'is_active' => true]
        );

        $response = $this->actingAs($user)->post(route('properties.store'), [
            'title' => 'Prime Corner Residential Plot 1500 sqft',
            'description' => 'Excellent corner residential plot with 30ft road facing and clear registry papers ready for immediate sale.',
            'type' => 'plot',
            'purpose' => 'buy',
            'category_id' => $category->id,
            'price' => 4500000,
            'price_period' => 'month',
            'state' => 'Haryana',
            'location' => 'Gurugram',
            'locality' => 'Sector 57',
            'address' => 'Plot No. 124, Block C, Sector 57',
            'contact_phone' => '9876543210',
            'area_sqft' => 1500,
            'images' => [
                UploadedFile::fake()->create('plot1.jpg', 100, 'image/jpeg'),
            ],
        ]);

        $this->assertDatabaseHas('properties', [
            'title' => 'Prime Corner Residential Plot 1500 sqft',
            'type' => 'plot',
            'purpose' => 'buy',
            'area_sqft' => 1500,
            'bedrooms' => null,
            'bathrooms' => null,
        ]);
    }

    public function test_plot_property_detail_view_renders_plot_attributes()
    {
        $user = User::factory()->create(['role' => 'owner']);
        $category = Category::firstOrCreate(
            ['slug' => 'plots-and-land'],
            ['name' => 'Plots & Land', 'icon' => 'ph-map-trifold', 'sort_order' => 5, 'is_active' => true]
        );

        $plot = Property::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Affordable Freehold Land Plot',
            'description' => 'Beautiful freehold land parcel with wide approach road and clear boundary wall demarcated.',
            'type' => 'plot',
            'purpose' => 'buy',
            'price' => 3200000,
            'price_period' => 'month',
            'state' => 'Haryana',
            'location' => 'Gurugram',
            'locality' => 'Sohna Road',
            'address' => 'Khasra 45/2, Sohna Road',
            'contact_phone' => '9876543210',
            'area_sqft' => 1800,
            'status' => 'approved',
        ]);

        $response = $this->actingAs($user)->get(route('properties.show', $plot));
        $response->assertStatus(200);
        $response->assertSee('Plot / Land');
        $response->assertSee('Freehold');
        $response->assertSee('Outright Sale');
        $response->assertSee('1,800');
    }

    public function test_explore_page_filters_by_plot_type()
    {
        $user = User::factory()->create(['role' => 'owner']);
        $category = Category::firstOrCreate(
            ['slug' => 'plots-and-land'],
            ['name' => 'Plots & Land', 'icon' => 'ph-map-trifold', 'sort_order' => 5, 'is_active' => true]
        );

        $plot = Property::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Commercial Land for High Street Shops',
            'description' => 'Great commercial plot with immediate possession and all utilities available.',
            'type' => 'plot',
            'purpose' => 'buy',
            'price' => 9500000,
            'price_period' => 'month',
            'state' => 'Haryana',
            'location' => 'Gurugram',
            'locality' => 'Golf Course Ext Road',
            'address' => 'Plot 99, Golf Course Ext Road',
            'contact_phone' => '9876543210',
            'area_sqft' => 2700,
            'status' => 'approved',
        ]);

        $response = $this->get(route('properties.index', ['type' => 'plot']));
        $response->assertStatus(200);
        $response->assertSee('Plots & Land');
        $response->assertSee('Commercial Land For High Street Shops');
    }
}
