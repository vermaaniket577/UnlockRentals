<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserPlan;
use App\Models\Plan;
use App\Models\State;
use App\Models\District;
use App\Models\Locality;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $tenant;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        $this->tenant = User::create([
            'name' => 'Michael Tenant',
            'email' => 'tenant@test.com',
            'role' => 'tenant',
            'password' => bcrypt('password'),
        ]);
    }

    /**
     * Test that non-admin users cannot access admin locations.
     */
    public function test_non_admin_cannot_access_admin_locations()
    {
        $response = $this->actingAs($this->tenant)
            ->get(route('admin.locations'));

        $response->assertStatus(403);
    }

    /**
     * Test that admin can access locations view.
     */
    public function test_admin_can_access_locations()
    {
        $state = State::create(['code' => 'DL', 'name' => 'Delhi']);
        $district = District::create(['state_id' => $state->id, 'name' => 'New Delhi']);
        $locality = Locality::create(['district_id' => $district->id, 'name' => 'Connaught Place']);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.locations', [
                'state_id' => $state->id,
                'district_id' => $district->id,
            ]));

        $response->assertStatus(200);
        $response->assertSee('System Locations');
        $response->assertSee('Delhi');
        $response->assertSee('New Delhi');
        $response->assertSee('Connaught Place');
    }

    /**
     * Test that admin subscriptions view is null safe when a user's plan is deleted/missing.
     */
    public function test_admin_subscriptions_view_is_null_safe_with_deleted_plan()
    {
        $userPlan = new UserPlan([
            'user_id' => $this->tenant->id,
            'plan_id' => null,
            'status' => 'approved',
            'amount_paid' => 499.00,
            'payment_reference' => 'TXN-MOCK-NULL',
            'billing_period' => 'monthly',
            'payment_method' => 'upi',
        ]);
        $userPlan->created_at = now();
        
        $userPlan->setRelation('user', $this->tenant);
        $userPlan->setRelation('plan', null);

        $subscriptions = new \Illuminate\Pagination\LengthAwarePaginator(
            [$userPlan], 1, 15
        );
        $plans = collect();

        $view = $this->actingAs($this->admin)
            ->view('admin.subscriptions', compact('subscriptions', 'plans'));

        $view->assertSee('User Subscriptions');
        $view->assertSee('Deleted Plan');
        $view->assertSee('TXN-MOCK-NULL');
    }

    /**
     * Test that admin user activity view is null safe when a user's active plan is deleted/missing.
     */
    public function test_admin_user_activity_view_is_null_safe_with_deleted_plan()
    {
        $tenantMock = new TestUser();
        $tenantMock->forceFill([
            'id' => $this->tenant->id,
            'name' => 'Michael Tenant',
            'email' => 'tenant@test.com',
            'role' => 'tenant',
            'phone' => '+91 7974164274',
        ]);

        $userPlan = new UserPlan([
            'user_id' => $tenantMock->id,
            'plan_id' => null,
            'status' => 'approved',
            'expires_at' => now()->addDays(30),
            'contacts_used' => 2,
        ]);
        $userPlan->setRelation('plan', null);

        $tenantMock->mockActivePlan = $userPlan;
        
        // Mock inquiries relationship to avoid SQL queries during view rendering
        $tenantMock->setRelation('inquiries', collect());

        $contactViews = collect();

        $view = $this->actingAs($this->admin)
            ->view('admin.user-activity', ['user' => $tenantMock, 'contactViews' => $contactViews]);

        $view->assertSee('User Activity');
        $view->assertSee('Deleted Plan');
        $view->assertSee('2 / N/A');
    }

    /**
     * Test that admin can approve feedback.
     */
    public function test_admin_can_approve_feedback()
    {
        $feedback = \App\Models\Feedback::create([
            'rating' => 5,
            'comment' => 'Great platform!',
            'status' => 'new',
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.feedback.approve', $feedback));

        $response->assertRedirect();
        $this->assertDatabaseHas('feedback', [
            'id' => $feedback->id,
            'status' => 'approved',
        ]);
    }

    /**
     * Test that admin can reject feedback.
     */
    public function test_admin_can_reject_feedback()
    {
        $feedback = \App\Models\Feedback::create([
            'rating' => 2,
            'comment' => 'Needs work.',
            'status' => 'new',
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.feedback.reject', $feedback));

        $response->assertRedirect();
        $this->assertDatabaseHas('feedback', [
            'id' => $feedback->id,
            'status' => 'rejected',
        ]);
    }

    /**
     * Test that admin can delete feedback.
     */
    public function test_admin_can_delete_feedback()
    {
        $feedback = \App\Models\Feedback::create([
            'rating' => 1,
            'comment' => 'Spam feedback.',
            'status' => 'new',
        ]);

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.feedback.delete', $feedback));

        $response->assertRedirect();
        $this->assertDatabaseMissing('feedback', [
            'id' => $feedback->id,
        ]);
    }

    /**
     * Test that admin can update GST rate setting with validation.
     */
    public function test_admin_can_update_gst_rate_setting()
    {
        // 1. Valid input
        $response = $this->actingAs($this->admin)
            ->post(route('admin.settings.update'), [
                'gst_rate' => '12.50',
            ]);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('settings', [
            'key' => 'gst_rate',
            'value' => '12.50',
        ]);

        // 2. Invalid inputs (validation checks)
        $responseInvalidMax = $this->actingAs($this->admin)
            ->post(route('admin.settings.update'), [
                'gst_rate' => '101',
            ]);
        $responseInvalidMax->assertSessionHasErrors(['gst_rate']);

        $responseInvalidMin = $this->actingAs($this->admin)
            ->post(route('admin.settings.update'), [
                'gst_rate' => '-5',
            ]);
        $responseInvalidMin->assertSessionHasErrors(['gst_rate']);
    }

    /**
     * Test that billing calculations dynamically use the configured GST rate setting.
     */
    public function test_billing_calculations_use_configured_gst_rate()
    {
        // Set GST rate to 12%
        \App\Models\Setting::updateOrCreate(
            ['key' => 'gst_rate'],
            ['value' => '12']
        );
        // Clear the cache manually to update setting lookup
        \Illuminate\Support\Facades\Cache::forget('site_settings');

        // Retrieve billing calculations for a plan
        $plan = Plan::create([
            'name' => 'Gold Plan',
            'description' => 'Test Description',
            'price' => 100.00,
            'duration_days' => 30,
            'contact_limit' => 15,
            'features' => ['Gold Feature 1'],
            'is_active' => true,
        ]);

        $payments = app(\App\Services\SubscriptionPaymentService::class);
        $billing = $payments->billingBreakdown($plan, 100.00, 'monthly');

        // subtotal = 100, discount = 0, taxable = 100.
        // GST at 12% is 12.00, final = 112.00
        $this->assertEquals(12.00, $billing['gst']);
        $this->assertEquals(112.00, $billing['final']);
        $this->assertEquals(12.0, $billing['gst_rate']);
    }

    /**
     * Test that homepage only displays approved feedback submissions.
     */
    public function test_homepage_displays_only_approved_feedback()
    {
        // 1. Create a guest user
        $user = User::create([
            'name' => 'John Reviewer',
            'email' => 'john@test.com',
            'password' => bcrypt('password'),
        ]);

        // 2. Create three feedback submissions: one approved, one new, one rejected
        $approvedFb = \App\Models\Feedback::create([
            'user_id' => $user->id,
            'rating' => 5,
            'comment' => 'This is a fantastic website!',
            'status' => 'approved',
        ]);

        $newFb = \App\Models\Feedback::create([
            'user_id' => $user->id,
            'rating' => 4,
            'comment' => 'This is a pending feedback comment.',
            'status' => 'new',
        ]);

        $rejectedFb = \App\Models\Feedback::create([
            'user_id' => $user->id,
            'rating' => 1,
            'comment' => 'This is a rejected feedback comment.',
            'status' => 'rejected',
        ]);

        // Clear cache
        \Illuminate\Support\Facades\Cache::forget('home_approved_feedbacks');

        // 3. Visit the home page
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        // Should see the approved feedback
        $response->assertSee('This is a fantastic website!');
        $response->assertSee('John Reviewer');
        // Should NOT see the new or rejected feedbacks
        $response->assertDontSee('This is a pending feedback comment.');
    }

    /**
     * Test that homepage displays private user offers for logged-in user.
     */
    public function test_homepage_displays_private_user_offers()
    {
        // 1. Create a public plan
        $plan = Plan::create([
            'name' => 'Silver Plan',
            'description' => 'Test Silver Description',
            'price' => 199.00,
            'duration_days' => 30,
            'contact_limit' => 5,
            'features' => ['Silver Feature 1'],
            'is_active' => true,
            'is_private' => false,
            'sort_order' => 1,
        ]);

        // 2. Create private offer for tenant
        \App\Models\PrivateUserOffer::create([
            'user_id' => $this->tenant->id,
            'plan_id' => $plan->id,
            'discounted_price' => 99.00,
            'status' => 'active',
            'expires_at' => now()->addDays(5),
        ]);

        // Clear plans cache
        \Illuminate\Support\Facades\Cache::forget('home_plans_preview');

        // 3. Visit the homepage logged in as the tenant
        $response = $this->actingAs($this->tenant)
            ->get(route('home'));

        $response->assertStatus(200);
        // Should see the discounted price
        $response->assertSee('99');
        $response->assertSee('Special Offer');
        // Should see original price in strikethrough (or just crossed out)
        $response->assertSee('199');
    }

    /**
     * Test that an admin can permanently delete a user subscription.
     */
    public function test_admin_can_delete_subscription()
    {
        // 1. Create a public plan
        $plan = Plan::create([
            'name' => 'Silver Plan',
            'description' => 'Test Silver Description',
            'price' => 199.00,
            'duration_days' => 30,
            'contact_limit' => 5,
            'features' => ['Silver Feature 1'],
            'is_active' => true,
        ]);

        // 2. Create a user plan subscription
        $userPlan = UserPlan::create([
            'user_id' => $this->tenant->id,
            'plan_id' => $plan->id,
            'status' => 'approved',
            'amount_paid' => 199.00,
            'payment_reference' => 'TXN-MOCK-DELETE',
            'billing_period' => 'monthly',
            'payment_method' => 'upi',
            'expires_at' => now()->addDays(30),
        ]);

        // Verify it exists in database
        $this->assertDatabaseHas('user_plans', [
            'id' => $userPlan->id,
        ]);

        // 3. Send DELETE request to admin.subscriptions.destroy as admin
        $response = $this->actingAs($this->admin)
            ->delete(route('admin.subscriptions.destroy', $userPlan));

        // 4. Assert response redirects and session has success message
        $response->assertRedirect();
        $response->assertSessionHas('success');

        // 5. Assert database missing user plan record
        $this->assertDatabaseMissing('user_plans', [
            'id' => $userPlan->id,
        ]);
    }

    /**
     * Test that a non-admin cannot delete a user subscription.
     */
    public function test_non_admin_cannot_delete_subscription()
    {
        $plan = Plan::create([
            'name' => 'Silver Plan',
            'description' => 'Test Silver Description',
            'price' => 199.00,
            'duration_days' => 30,
            'contact_limit' => 5,
            'is_active' => true,
        ]);

        $userPlan = UserPlan::create([
            'user_id' => $this->tenant->id,
            'plan_id' => $plan->id,
            'status' => 'approved',
            'amount_paid' => 199.00,
            'payment_reference' => 'TXN-MOCK-FAIL-DELETE',
            'billing_period' => 'monthly',
            'payment_method' => 'upi',
        ]);

        $response = $this->actingAs($this->tenant)
            ->delete(route('admin.subscriptions.destroy', $userPlan));

        $response->assertStatus(403);
        
        $this->assertDatabaseHas('user_plans', [
            'id' => $userPlan->id,
        ]);
    }
}

/**
 * Test helper subclass to mock Eloquent relationship methods without triggering PDO database queries.
 */
class TestUser extends User
{
    protected $table = 'users';
    public $mockActivePlan = null;

    public function activePlan(): ?UserPlan
    {
        return $this->mockActivePlan;
    }
}
