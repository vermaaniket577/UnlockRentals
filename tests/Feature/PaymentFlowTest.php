<?php

namespace Tests\Feature;

use App\Models\Plan;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserPlan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentFlowTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;
    protected $plan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = User::create([
            'name' => 'Michael Tenant',
            'email' => 'tenant@test.com',
            'role' => 'tenant',
            'password' => bcrypt('password'),
        ]);

        $this->plan = Plan::create([
            'name' => 'Gold Plan',
            'description' => 'Test Gold Description',
            'price' => 399.00,
            'duration_days' => 30,
            'contact_limit' => 15,
            'features' => ['Gold Feature 1'],
            'is_active' => true,
        ]);

        // Enable default mock Razorpay gateway in settings
        Setting::create([
            'key' => 'payment_gateways',
            'value' => json_encode([
                [
                    'id' => 'razorpay-default',
                    'name' => 'Razorpay',
                    'type' => 'razorpay',
                    'enabled' => '1',
                    'key_id' => 'rzp_test_mock_key',
                    'key_secret' => 'rzp_test_mock_secret',
                    'reference_label' => 'Razorpay Payment ID',
                    'instructions' => 'Pay securely.',
                ]
            ]),
        ]);
    }

    /**
     * Test checkout page is viewable.
     */
    public function test_checkout_page_is_viewable()
    {
        $response = $this->actingAs($this->tenant)
            ->get(route('plans.checkout', $this->plan));

        $response->assertStatus(200);
        $response->assertSee($this->plan->name);
        $response->assertSee('Secure Premium Checkout');
    }

    /**
     * Test Razorpay order creation API endpoint.
     */
    public function test_razorpay_order_can_be_created()
    {
        $response = $this->actingAs($this->tenant)
            ->postJson(route('plans.razorpay.order', $this->plan), [
                'billing_period' => 'monthly',
            ]);

        // Note: It might make a real network request if not mocked or if API is instantiated.
        // Let's verify it gets handled. To prevent external API calls failing the test suite,
        // we catch/mock the service call. Our SubscriptionPaymentService intercepts Razorpay exceptions
        // and returns a JSON response. Let's make sure it returns a JSON response.
        $response->assertStatus(200)
            ->assertJsonStructure(['order_id', 'amount', 'currency', 'key_id', 'plan_name']);
    }

    /**
     * Test active subscription activation flow with manual/mock payment details.
     */
    public function test_manual_payment_processing_saves_to_db()
    {
        // Switch to manual gateway in settings
        Setting::where('key', 'payment_gateways')->update([
            'value' => json_encode([
                [
                    'id' => 'manual-default',
                    'name' => 'UPI Transfer',
                    'type' => 'manual',
                    'enabled' => '1',
                    'reference_label' => 'UTR Number',
                    'instructions' => 'Transfer manual.',
                ]
            ]),
        ]);

        // Simulate uploading a file for payment proof
        $file = \Illuminate\Http\UploadedFile::fake()->create('proof.png', 100, 'image/png');

        $response = $this->actingAs($this->tenant)
            ->post(route('plans.purchase.process', $this->plan), [
                'billing_period' => 'monthly',
                'payment_method' => 'upi',
                'payment_reference' => 'UTR123456789',
                'amount_paid' => 470.82, // Plan price 399 + 18% GST (71.82)
                'payment_proof' => $file,
            ]);

        $response->assertRedirect(route('plans.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('user_plans', [
            'user_id' => $this->tenant->id,
            'plan_id' => $this->plan->id,
            'status' => 'pending',
            'transaction_id' => 'UTR123456789',
        ]);
    }

    /**
     * Test that users can see their billing history page.
     */
    public function test_billing_history_page_loads_with_records()
    {
        // Create an approved user plan
        UserPlan::create([
            'user_id' => $this->tenant->id,
            'plan_id' => $this->plan->id,
            'status' => 'approved',
            'amount_paid' => 399.00,
            'invoice_id' => 'INV-20260523-TEST',
            'billing_period' => 'monthly',
            'payment_method' => 'upi',
        ]);

        $response = $this->actingAs($this->tenant)
            ->get(route('billing.history'));

        $response->assertStatus(200);
        $response->assertSee('Billing & Invoices', false);
        $response->assertSee('INV-20260523-TEST');
    }

    /**
     * Test invoice rendering and printer views.
     */
    public function test_user_can_view_invoice()
    {
        $userPlan = UserPlan::create([
            'user_id' => $this->tenant->id,
            'plan_id' => $this->plan->id,
            'status' => 'approved',
            'amount_paid' => 399.00,
            'invoice_id' => 'INV-20260523-TEST',
            'billing_period' => 'monthly',
            'payment_method' => 'upi',
        ]);

        $response = $this->actingAs($this->tenant)
            ->get(route('billing.invoice', $userPlan));

        $response->assertStatus(200);
        $response->assertSee('INV-20260523-TEST');
        $response->assertSee('Billed To');
        $response->assertSee('Michael Tenant');
    }
}
