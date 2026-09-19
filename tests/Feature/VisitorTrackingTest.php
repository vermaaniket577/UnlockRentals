<?php

namespace Tests\Feature;

use App\Models\Visitor;
use App\Models\VisitorEvent;
use App\Models\VisitorSession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VisitorTrackingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test visitor tracking cookie attachment on public pages.
     */
    public function test_visitor_cookie_is_attached_on_home_page(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertCookie('ur_visitor_uuid');
    }

    /**
     * Test client event tracking API.
     */
    public function test_can_record_visitor_event_via_api(): void
    {
        $visitor = Visitor::create([
            'visitor_uuid' => (string) \Illuminate\Support\Str::uuid(),
            'first_seen_at' => now(),
            'last_seen_at' => now(),
            'city' => 'Pune',
            'state' => 'Maharashtra',
        ]);

        $session = VisitorSession::create([
            'visitor_id' => $visitor->id,
            'session_uuid' => (string) \Illuminate\Support\Str::uuid(),
            'landing_page' => '/',
            'started_at' => now(),
            'last_activity_at' => now(),
        ]);

        $payload = [
            'visitor_uuid' => $visitor->visitor_uuid,
            'event_name' => 'whatsapp_click',
            'page_url' => 'http://localhost/properties/1',
            'metadata' => [
                'target' => 'owner_contact',
            ],
        ];

        $response = $this->withCookie('ur_visitor_uuid', $visitor->visitor_uuid)
                         ->postJson('/api/visitor/event', $payload);

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseHas('visitor_events', [
            'visitor_id' => $visitor->id,
            'event_name' => 'whatsapp_click',
        ]);
    }

    /**
     * Test updating cookie consent record.
     */
    public function test_can_update_consent_record(): void
    {
        $visitor = Visitor::create([
            'visitor_uuid' => (string) \Illuminate\Support\Str::uuid(),
            'first_seen_at' => now(),
            'last_seen_at' => now(),
        ]);

        $payload = [
            'visitor_uuid' => $visitor->visitor_uuid,
            'consent_type' => 'analytics',
            'is_granted' => true,
        ];

        $response = $this->postJson('/api/consent/update', $payload);

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseHas('consent_records', [
            'visitor_id' => $visitor->id,
            'consent_type' => 'analytics',
            'is_granted' => true,
        ]);
    }
}
