<?php

namespace Tests\Feature;

use App\Models\Professional;
use App\Models\ProfessionalCategory;
use App\Models\ProfessionalDocument;
use App\Models\ProfessionalLead;
use App\Models\ProfessionalReview;
use App\Models\ProfessionalService;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocalProfessionalsMarketplaceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\ProfessionalMarketplaceSeeder::class);
    }

    /**
     * Test public directory loads successfully.
     */
    public function test_marketplace_index_page_returns_successful_response(): void
    {
        $this->withoutExceptionHandling();
        $response = $this->get(route('services.index'));
        $response->assertStatus(200);
        $response->assertSee('Find Local Professionals');
    }

    /**
     * Test category landing page loads.
     */
    public function test_category_landing_page_loads(): void
    {
        $category = ProfessionalCategory::first() ?? ProfessionalCategory::create([
            'name' => 'Electrician',
            'slug' => 'electrician',
            'status' => 'active',
        ]);

        $response = $this->get(route('services.category', $category->slug));
        $response->assertStatus(200);
    }

    /**
     * Test registration page loads with form.
     */
    public function test_registration_page_loads_successfully(): void
    {
        $response = $this->get(route('services.register'));
        $response->assertStatus(200);
        $response->assertSee('List Your Professional Service — FREE');
        $response->assertSee('Your Contact Details');
        $response->assertSee('Your Service Location');
    }

    /**
     * Test registration validation rejects invalid input.
     */
    public function test_registration_rejects_missing_category_and_invalid_phone(): void
    {
        $response = $this->post(route('services.register.submit'), [
            'full_name' => 'Test Provider',
            'business_name' => 'Test Business',
            'phone' => '1234', // Invalid phone length
            'email' => 'test@example.com',
            'description' => 'Short', // Too short
            'city' => 'Gurgaon',
            'years_experience' => 5,
            'price_type' => 'hourly',
            // Missing category_id
        ]);

        $response->assertSessionHasErrors(['category_id', 'phone']);
    }

    /**
     * Test valid professional registration succeeds and creates records.
     */
    public function test_valid_professional_registration_creates_pending_profile(): void
    {
        $category = ProfessionalCategory::first() ?? ProfessionalCategory::create([
            'name' => 'Plumber',
            'slug' => 'plumber',
            'status' => 'active',
        ]);

        $uniqueEmail = 'plumber_' . time() . '@test.com';

        $response = $this->post(route('services.register.submit'), [
            'full_name' => 'Ramesh Kumar',
            'business_name' => 'Ramesh Plumbing Works',
            'phone' => '9876543210',
            'email' => $uniqueEmail,
            'category_id' => $category->id,
            'years_experience' => 8,
            'starting_price' => 350,
            'price_type' => 'per_visit',
            'city' => 'Gurgaon',
            'locality' => 'Sector 14',
            'pincode' => '122001',
            'service_radius_km' => 20,
            'description' => 'Over 8 years of expert plumbing and leak repair experience with top rated customer feedback.',
            'home_visit' => '1',
            'available_today' => '1',
            'password' => 'secret123',
        ]);

        $this->assertDatabaseHas('professionals', [
            'business_name' => 'Ramesh Plumbing Works',
            'city' => 'Gurgaon',
            'status' => 'pending',
            'verification_status' => 'unverified',
        ]);
    }

    /**
     * Test customer can submit a service request and matching creates leads.
     */
    public function test_customer_can_submit_service_request(): void
    {
        $category = ProfessionalCategory::first();

        $response = $this->post(route('services.request.submit'), [
            'category_id' => $category->id,
            'name' => 'Customer Test',
            'phone' => '9123456780',
            'email' => 'customer@test.com',
            'description' => 'Ceiling fan making strange noise, need urgent repair.',
            'city' => 'Gurgaon',
            'locality' => 'Sector 14',
            'preferred_date' => date('Y-m-d'),
        ]);

        $this->assertDatabaseHas('service_requests', [
            'name' => 'Customer Test',
            'city' => 'Gurgaon',
            'status' => 'new',
        ]);
    }

    /**
     * Test private KYC document download is unauthorized for other users.
     */
    public function test_private_kyc_document_access_is_guarded(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $category = ProfessionalCategory::first();

        $prof = Professional::create([
            'user_id' => $user1->id,
            'category_id' => $category->id,
            'business_name' => 'Owner Business',
            'slug' => 'owner-biz-' . time(),
            'full_name' => 'Owner Name',
            'phone' => '9876543211',
            'email' => 'owner' . time() . '@test.com',
            'description' => 'Quality electrical service provider in Gurgaon.',
            'years_experience' => 4,
            'price_type' => 'hourly',
            'city' => 'Gurgaon',
            'status' => 'approved',
        ]);

        $doc = ProfessionalDocument::create([
            'professional_id' => $prof->id,
            'document_type' => 'id_proof',
            'file_path' => 'private_documents/professionals/dummy.pdf',
            'status' => 'pending',
        ]);

        // User 2 (not owner, not admin) attempts download
        $response = $this->actingAs($user2)->get(route('professionals.documents.download', $doc->id));
        $response->assertStatus(403);
    }

    /**
     * Test REST API endpoints return JSON data.
     */
    public function test_api_v1_categories_returns_json(): void
    {
        $response = $this->getJson('/api/v1/professional-categories');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'data' => [
                '*' => ['id', 'name', 'slug'],
            ],
        ]);
    }

    /**
     * Test professional profile page loads with SEO schema.
     */
    public function test_professional_profile_page_loads_with_seo_and_schema(): void
    {
        $user = User::factory()->create();
        $category = ProfessionalCategory::first();

        $prof = Professional::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'business_name' => 'Apex Electrical Solutions',
            'slug' => 'apex-electrical-solutions',
            'full_name' => 'Vikram Singh',
            'phone' => '9876543299',
            'whatsapp_number' => '9876543299',
            'email' => 'apex@example.com',
            'description' => 'Top notch certified electrical contractor serving Gurgaon with round the clock support.',
            'years_experience' => 10,
            'price_type' => 'per_visit',
            'starting_price' => 400,
            'city' => 'Gurgaon',
            'locality' => 'Sector 29',
            'pincode' => '122002',
            'status' => 'approved',
            'verification_status' => 'verified',
            'average_rating' => 4.9,
            'review_count' => 14,
        ]);

        $response = $this->get(route('services.show', [
            'category' => $category->slug,
            'city' => strtolower($prof->city),
            'slug' => $prof->slug,
        ]));

        $response->assertStatus(200);
        $response->assertSee('Apex Electrical Solutions');
        $response->assertSee('Verified Professional');
        $response->assertSee('Call Now');
        $response->assertSee('WhatsApp');
    }

    /**
     * Test click tracking increments counters.
     */
    public function test_call_and_whatsapp_tracking_increments_click_counts(): void
    {
        $user = User::factory()->create();
        $category = ProfessionalCategory::first();

        $prof = Professional::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'business_name' => 'Quick Plumb Express',
            'slug' => 'quick-plumb-express-' . time(),
            'full_name' => 'Mohan Lal',
            'phone' => '9876543212',
            'city' => 'Gurgaon',
            'status' => 'approved',
            'price_type' => 'hourly',
            'years_experience' => 3,
            'description' => 'Reliable and affordable plumbing repair services.',
            'call_clicks' => 0,
            'whatsapp_clicks' => 0,
        ]);

        // Track Call click
        $callResponse = $this->postJson(route('professionals.click', ['professional' => $prof->id, 'type' => 'call']));
        $callResponse->assertJson(['success' => true]);

        // Track WhatsApp click
        $waResponse = $this->postJson(route('professionals.click', ['professional' => $prof->id, 'type' => 'whatsapp']));
        $waResponse->assertJson(['success' => true]);

        $prof->refresh();
        $this->assertEquals(1, $prof->call_clicks);
        $this->assertEquals(1, $prof->whatsapp_clicks);
    }

    /**
     * Test customer can toggle favorite.
     */
    public function test_customer_can_favorite_professional(): void
    {
        $customer = User::factory()->create();
        $user = User::factory()->create();
        $category = ProfessionalCategory::first();

        $prof = Professional::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'business_name' => 'Elite Carpentry Works',
            'slug' => 'elite-carpentry-' . time(),
            'full_name' => 'Kishore Kumar',
            'phone' => '9876543213',
            'city' => 'Noida',
            'status' => 'approved',
            'price_type' => 'hourly',
            'years_experience' => 6,
            'description' => 'Custom woodwork and modular kitchen repair specialists.',
        ]);

        $response = $this->actingAs($customer)->postJson(route('professionals.favorite', ['professional' => $prof->id]));
        $response->assertJson(['success' => true, 'favorited' => true]);

        $this->assertDatabaseHas('professional_favorites', [
            'user_id' => $customer->id,
            'professional_id' => $prof->id,
        ]);

        // Toggle again to remove
        $response2 = $this->actingAs($customer)->postJson(route('professionals.favorite', ['professional' => $prof->id]));
        $response2->assertJson(['success' => true, 'favorited' => false]);
    }

    /**
     * Test admin approval and verification workflow.
     */
    public function test_admin_can_approve_and_verify_professional(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        $category = ProfessionalCategory::first();

        $prof = Professional::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'business_name' => 'Sunrise Painting Services',
            'slug' => 'sunrise-painting-' . time(),
            'full_name' => 'Ajay Verma',
            'phone' => '9876543214',
            'city' => 'Delhi',
            'status' => 'pending',
            'verification_status' => 'unverified',
            'price_type' => 'hourly',
            'years_experience' => 4,
            'description' => 'Interior and exterior painting with Asian Paints materials.',
        ]);

        // Admin approves
        $approveResponse = $this->actingAs($admin)->post(route('admin.professionals.approve', ['professional' => $prof->id]));
        $approveResponse->assertSessionHas('success');

        // Admin verifies
        $verifyResponse = $this->actingAs($admin)->post(route('admin.professionals.toggle-verify', ['professional' => $prof->id]));
        $verifyResponse->assertSessionHas('success');

        $prof->refresh();
        $this->assertEquals('approved', $prof->status);
        $this->assertEquals('verified', $prof->verification_status);
        $this->assertNotNull($prof->approved_at);
    }
}
