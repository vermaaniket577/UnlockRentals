<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use App\Models\Property;
use App\Models\Plan;
use App\Models\UserPlan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VisitBookingTest extends TestCase
{
    use RefreshDatabase;

    protected $owner;
    protected $tenant;
    protected $property;
    protected $plan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = User::create([
            'name' => 'John Owner',
            'email' => 'owner@test.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
            'phone' => '+91 9999999999',
        ]);

        $this->tenant = User::create([
            'name' => 'Michael Tenant',
            'email' => 'tenant@test.com',
            'password' => bcrypt('password'),
            'role' => 'tenant',
            'phone' => '+91 8888888888',
        ]);

        $category = Category::create([
            'name' => 'Residential House',
            'slug' => 'residential-house',
            'icon' => 'house',
            'description' => 'Test house',
        ]);

        $this->property = Property::create([
            'user_id' => $this->owner->id,
            'category_id' => $category->id,
            'title' => 'Test Property',
            'description' => 'Test description',
            'type' => 'house',
            'price' => 50000,
            'price_period' => 'month',
            'location' => 'Mumbai',
            'address' => 'Bandra',
            'bedrooms' => 2,
            'bathrooms' => 2,
            'area_sqft' => 1000,
            'furnishing' => 'semi-furnished',
            'status' => 'approved',
        ]);

        $this->plan = Plan::create([
            'name' => 'Silver Plan',
            'description' => 'Test plan description',
            'price' => 199.00,
            'duration_days' => 30,
            'contact_limit' => 5,
            'features' => ['Feature 1'],
            'is_active' => true,
        ]);
    }

    /**
     * Test that guests are redirected to login when trying to book a visit.
     */
    public function test_guest_cannot_book_visit()
    {
        $response = $this->post(route('properties.book-visit', $this->property), [
            'preferred_date' => now()->addDays(2)->format('Y-m-d'),
            'preferred_time' => 'morning',
            'name' => 'Test User',
            'phone' => '1234567890',
        ]);

        $response->assertRedirect(route('login'));
    }

    /**
     * Test that authenticated users without an active subscription plan are blocked and redirected to the plans page.
     */
    public function test_user_without_active_plan_cannot_book_visit()
    {
        $response = $this->actingAs($this->tenant)
            ->post(route('properties.book-visit', $this->property), [
                'preferred_date' => now()->addDays(2)->format('Y-m-d'),
                'preferred_time' => 'morning',
                'name' => 'Test User',
                'phone' => '1234567890',
            ]);

        $response->assertRedirect(route('plans.index'));
        $response->assertSessionHas('error', 'Please purchase a plan to book a visit.');
    }

    /**
     * Test that authenticated users without an active subscription plan get blocked and receive redirect JSON via AJAX.
     */
    public function test_user_without_active_plan_cannot_book_visit_via_ajax()
    {
        $response = $this->actingAs($this->tenant)
            ->postJson(route('properties.book-visit', $this->property), [
                'preferred_date' => now()->addDays(2)->format('Y-m-d'),
                'preferred_time' => 'morning',
                'name' => 'Test User',
                'phone' => '1234567890',
            ]);

        $response->assertStatus(403);
        $response->assertJson([
            'success' => false,
            'redirect' => route('plans.index'),
            'message' => 'Please purchase a plan to book a visit.',
        ]);
    }

    /**
     * Test that authenticated users with an active subscription plan can book a visit successfully.
     */
    public function test_user_with_active_plan_can_book_visit()
    {
        // Give tenant an active plan
        UserPlan::create([
            'user_id' => $this->tenant->id,
            'plan_id' => $this->plan->id,
            'status' => 'approved',
            'approved_at' => now(),
            'expires_at' => now()->addDays(30),
        ]);

        $response = $this->actingAs($this->tenant)
            ->post(route('properties.book-visit', $this->property), [
                'preferred_date' => now()->addDays(2)->format('Y-m-d'),
                'preferred_time' => 'morning',
                'name' => 'Test User',
                'phone' => '1234567890',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('visit_bookings', [
            'user_id' => $this->tenant->id,
            'property_id' => $this->property->id,
            'preferred_time' => 'morning',
        ]);
    }
}
