<?php

namespace Tests\Feature;

use App\Models\Lead;
use App\Models\LeadFollowUp;
use App\Models\Visitor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadCaptureCrmTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test successful lead capture via API.
     */
    public function test_can_capture_lead_via_api(): void
    {
        $visitor = Visitor::create([
            'visitor_uuid' => (string) \Illuminate\Support\Str::uuid(),
            'first_seen_at' => now(),
            'last_seen_at' => now(),
        ]);

        $payload = [
            'name' => 'Aarav Patel',
            'phone' => '9876543210',
            'email' => 'aarav@example.com',
            'intent' => 'rent',
            'budget_max' => 25000,
            'bhk_preference' => '2 BHK',
            'preferred_city' => 'Pune',
            'source' => 'property_modal',
            'consent' => 1,
            'whatsapp_opt_in' => 1,
            'visitor_uuid' => $visitor->visitor_uuid,
        ];

        $response = $this->withCookie('ur_visitor_uuid', $visitor->visitor_uuid)
                         ->postJson('/api/leads', $payload);

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseHas('leads', [
            'mobile' => '9876543210',
            'name' => 'Aarav Patel',
            'purpose' => 'rent',
        ]);

        // Visitor converted flag should be set
        $this->assertDatabaseHas('visitors', [
            'id' => $visitor->id,
            'has_converted_lead' => true,
        ]);
    }

    /**
     * Test spam honeypot blocks automated bot submissions.
     */
    public function test_honeypot_silently_absorbs_bot_spam(): void
    {
        $payload = [
            'name' => 'Bot Spammer',
            'phone' => '9876543210',
            'intent' => 'rent',
            'consent' => 1,
            'website_hp' => 'http://spam-link.com', // Filled honeypot
        ];

        $response = $this->postJson('/api/leads', $payload);

        // Honeypot should fail validation
        $response->assertStatus(422);
    }

    /**
     * Test follow-up completion updates status.
     */
    public function test_can_create_and_complete_follow_up(): void
    {
        $user = \App\Models\User::create([
            'name' => 'Agent User',
            'email' => 'agent' . uniqid() . '@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $lead = Lead::create([
            'name' => 'Kavita Singh',
            'mobile' => '9123456780',
            'purpose' => 'buy',
            'lead_source' => 'manual',
            'lead_status' => 'new',
            'assigned_to' => $user->id,
        ]);

        $followUp = LeadFollowUp::create([
            'lead_id' => $lead->id,
            'assigned_to' => $user->id,
            'scheduled_at' => now()->addDay(),
            'note' => 'Discuss villa requirements',
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('lead_follow_ups', [
            'id' => $followUp->id,
            'status' => 'pending',
        ]);

        $followUp->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        $this->assertDatabaseHas('lead_follow_ups', [
            'id' => $followUp->id,
            'status' => 'completed',
        ]);
    }
}
