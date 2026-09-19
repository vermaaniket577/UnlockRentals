<?php

namespace Database\Seeders;

use App\Models\CommunicationLog;
use App\Models\ConsentRecord;
use App\Models\Lead;
use App\Models\LeadFollowUp;
use App\Models\Property;
use App\Models\User;
use App\Models\Visitor;
use App\Models\VisitorDailyStatistic;
use App\Models\VisitorEvent;
use App\Models\VisitorSession;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VisitorCrmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $sampleProperty = Property::approved()->first();
        $sampleOwner = $sampleProperty ? $sampleProperty->user : $admin;

        // 1. Create Sample Visitors across Cities & Devices
        $visitorProfiles = [
            [
                'city' => 'Gurgaon',
                'state' => 'Haryana',
                'source' => 'google',
                'medium' => 'cpc',
                'campaign' => 'gurgaon-rentals-2bhk',
                'device' => 'mobile',
                'browser' => 'Chrome Mobile',
                'os' => 'Android',
                'score' => 45,
                'tier' => 'high',
                'lead_name' => 'Rahul Sharma',
                'lead_phone' => '9876543210',
                'lead_email' => 'rahul.sharma@example.com',
                'purpose' => 'rent',
                'budget_max' => 35000,
                'status' => 'interested',
                'stage' => 'qualified',
            ],
            [
                'city' => 'Noida',
                'state' => 'Uttar Pradesh',
                'source' => 'facebook',
                'medium' => 'social',
                'campaign' => 'noida-flats',
                'device' => 'mobile',
                'browser' => 'Safari Mobile',
                'os' => 'iOS',
                'score' => 60,
                'tier' => 'high',
                'lead_name' => 'Priya Patel',
                'lead_phone' => '9812345678',
                'lead_email' => 'priya.p@example.com',
                'purpose' => 'buy',
                'budget_max' => 8500000,
                'status' => 'visit_scheduled',
                'stage' => 'visit',
            ],
            [
                'city' => 'Delhi',
                'state' => 'Delhi',
                'source' => 'organic',
                'medium' => 'search',
                'campaign' => null,
                'device' => 'desktop',
                'browser' => 'Chrome',
                'os' => 'Windows',
                'score' => 15,
                'tier' => 'medium',
                'lead_name' => 'Amit Verma',
                'lead_phone' => '9988776655',
                'lead_email' => 'amit.verma@example.com',
                'purpose' => 'rent',
                'budget_max' => 22000,
                'status' => 'new',
                'stage' => 'enquiry',
            ],
            [
                'city' => 'Bangalore',
                'state' => 'Karnataka',
                'source' => 'direct',
                'medium' => 'direct',
                'campaign' => null,
                'device' => 'desktop',
                'browser' => 'Edge',
                'os' => 'Windows',
                'score' => 70,
                'tier' => 'high',
                'lead_name' => 'Kavita Reddy',
                'lead_phone' => '9765432109',
                'lead_email' => 'kavita.reddy@example.com',
                'purpose' => 'rent',
                'budget_max' => 45000,
                'status' => 'converted',
                'stage' => 'converted',
            ],
        ];

        foreach ($visitorProfiles as $idx => $p) {
            $visitorUuid = (string) Str::uuid();
            $firstSeen = now()->subDays(rand(2, 10))->subHours(rand(1, 12));

            $visitor = Visitor::create([
                'visitor_uuid' => $visitorUuid,
                'first_seen_at' => $firstSeen,
                'last_seen_at' => now()->subHours(rand(1, 6)),
                'first_landing_url' => 'https://www.unlockrentals.com/properties?locality=' . urlencode($p['city']),
                'last_url' => 'https://www.unlockrentals.com/properties/' . ($sampleProperty ? $sampleProperty->slug : 'sample'),
                'referrer' => $p['source'] === 'direct' ? null : 'https://www.' . $p['source'] . '.com',
                'utm_source' => $p['source'],
                'utm_medium' => $p['medium'],
                'utm_campaign' => $p['campaign'],
                'device_type' => $p['device'],
                'browser' => $p['browser'],
                'operating_system' => $p['os'],
                'country' => 'India',
                'state' => $p['state'],
                'city' => $p['city'],
                'total_sessions' => rand(2, 5),
                'total_page_views' => rand(6, 18),
                'total_property_views' => rand(3, 8),
                'first_property_id' => $sampleProperty ? $sampleProperty->id : null,
                'last_property_id' => $sampleProperty ? $sampleProperty->id : null,
                'engagement_score' => $p['score'],
                'engagement_tier' => $p['tier'],
                'has_converted_lead' => true,
            ]);

            // Create Session
            $session = VisitorSession::create([
                'visitor_id' => $visitor->id,
                'session_uuid' => (string) Str::uuid(),
                'landing_page' => $visitor->first_landing_url,
                'referrer' => $visitor->referrer,
                'started_at' => $firstSeen,
                'ended_at' => (clone $firstSeen)->addMinutes(18),
                'last_activity_at' => (clone $firstSeen)->addMinutes(18),
                'page_views' => 4,
                'property_views' => 2,
                'device_type' => $p['device'],
                'browser' => $p['browser'],
                'operating_system' => $p['os'],
                'utm_source' => $p['source'],
                'utm_medium' => $p['medium'],
                'utm_campaign' => $p['campaign'],
            ]);

            // Create Visitor Events Journey
            VisitorEvent::create([
                'visitor_id' => $visitor->id,
                'session_id' => $session->id,
                'event_name' => 'page_view',
                'page_url' => $visitor->first_landing_url,
                'created_at' => (clone $firstSeen),
            ]);

            VisitorEvent::create([
                'visitor_id' => $visitor->id,
                'session_id' => $session->id,
                'event_name' => 'search',
                'page_url' => $visitor->first_landing_url,
                'metadata' => [
                    'city' => $p['city'],
                    'purpose' => $p['purpose'],
                    'budget' => $p['budget_max'],
                ],
                'created_at' => (clone $firstSeen)->addMinutes(3),
            ]);

            if ($sampleProperty) {
                VisitorEvent::create([
                    'visitor_id' => $visitor->id,
                    'session_id' => $session->id,
                    'event_name' => 'property_view',
                    'property_id' => $sampleProperty->id,
                    'page_url' => route('properties.show', $sampleProperty),
                    'created_at' => (clone $firstSeen)->addMinutes(6),
                ]);

                VisitorEvent::create([
                    'visitor_id' => $visitor->id,
                    'session_id' => $session->id,
                    'event_name' => 'whatsapp_clicked',
                    'property_id' => $sampleProperty->id,
                    'page_url' => route('properties.show', $sampleProperty),
                    'created_at' => (clone $firstSeen)->addMinutes(10),
                ]);
            }

            // Create Converted Lead
            $consentText = 'I agree to be contacted by UnlockRentals regarding my property enquiry and selected services through phone, SMS, email or WhatsApp.';
            $lead = Lead::create([
                'visitor_id' => $visitor->id,
                'property_id' => $sampleProperty ? $sampleProperty->id : null,
                'owner_id' => $sampleOwner ? $sampleOwner->id : null,
                'assigned_to' => $admin ? $admin->id : null,
                'lead_source' => $p['source'] === 'google' ? 'similar_properties_form' : 'contact_owner_form',
                'lead_status' => $p['status'],
                'lead_stage' => $p['stage'],
                'name' => $p['lead_name'],
                'mobile' => $p['lead_phone'],
                'email' => $p['lead_email'],
                'preferred_city' => $p['city'],
                'preferred_locality' => $p['city'] . ' Sector ' . rand(10, 65),
                'property_type' => '2bhk',
                'purpose' => $p['purpose'],
                'budget_min' => $p['budget_max'] * 0.7,
                'budget_max' => $p['budget_max'],
                'bedrooms' => '2',
                'furnished_status' => 'Semi-Furnished',
                'move_in_date' => now()->addDays(rand(10, 45))->toDateString(),
                'message' => 'Looking for verified ' . $p['purpose'] . ' options in ' . $p['city'] . ' with parking and power backup.',
                'whatsapp_opt_in' => true,
                'marketing_opt_in' => true,
                'consent_text' => $consentText,
                'consent_at' => (clone $firstSeen)->addMinutes(12),
                'engagement_score' => $p['score'],
                'next_follow_up_at' => now()->addDays(rand(1, 4)),
                'notes' => 'Client verified phone and is looking to finalize quickly.',
                'created_at' => (clone $firstSeen)->addMinutes(12),
            ]);

            // Create Consent Record
            ConsentRecord::create([
                'visitor_id' => $visitor->id,
                'lead_id' => $lead->id,
                'consent_type' => 'service_enquiry',
                'is_granted' => true,
                'consent_text' => $consentText,
                'form_source' => $lead->lead_source,
                'ip_address' => '127.0.0.1',
                'granted_at' => $lead->consent_at,
            ]);

            // Create Scheduled Follow-Up
            if ($admin) {
                LeadFollowUp::create([
                    'lead_id' => $lead->id,
                    'assigned_to' => $admin->id,
                    'scheduled_at' => now()->addDays(rand(1, 3))->setTime(11, 0),
                    'status' => $p['status'] === 'converted' ? 'completed' : 'pending',
                    'note' => 'Call client to review curated listings in ' . $p['city'],
                    'completed_at' => $p['status'] === 'converted' ? now() : null,
                ]);

                // Create Communication Logs
                CommunicationLog::create([
                    'lead_id' => $lead->id,
                    'user_id' => $admin->id,
                    'type' => 'outgoing',
                    'channel' => 'whatsapp',
                    'message' => 'Hello ' . $lead->name . '! Thank you for choosing UnlockRentals. Here are matching verified properties for ' . $p['city'] . ' in your budget.',
                    'status' => 'delivered',
                    'provider_message_id' => 'wamid.' . Str::random(24),
                    'sent_at' => (clone $firstSeen)->addMinutes(15),
                    'delivered_at' => (clone $firstSeen)->addMinutes(16),
                    'read_at' => (clone $firstSeen)->addMinutes(18),
                ]);
            }
        }

        // 2. Create Daily Aggregated Statistics for past 7 days
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            foreach (['Gurgaon', 'Delhi', 'Noida', 'Bangalore'] as $city) {
                foreach (['google', 'organic', 'direct'] as $src) {
                    VisitorDailyStatistic::updateOrCreate([
                        'date' => $date,
                        'city' => $city,
                        'source' => $src,
                    ], [
                        'visitors_count' => rand(15, 60),
                        'sessions_count' => rand(20, 80),
                        'page_views_count' => rand(40, 190),
                        'property_views_count' => rand(15, 75),
                        'leads_count' => rand(1, 6),
                        'whatsapp_clicks_count' => rand(2, 10),
                        'enquiries_count' => rand(1, 5),
                        'scheduled_visits_count' => rand(0, 3),
                        'conversions_count' => rand(0, 2),
                    ]);
                }
            }
        }
    }
}
