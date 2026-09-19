<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeadRequest;
use App\Jobs\SendWhatsAppMessageJob;
use App\Models\ConsentRecord;
use App\Models\Lead;
use App\Models\Property;
use App\Models\User;
use App\Services\VisitorTrackingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeadController extends Controller
{
    public function __construct(
        protected VisitorTrackingService $tracker
    ) {}

    /**
     * Submit a lead from any public form (Similar Properties, Contact Owner, WhatsApp Alerts, Exit Intent, Schedule Visit).
     * POST /api/leads
     */
    public function store(StoreLeadRequest $request): JsonResponse
    {
        $data = $request->validated();

        // Honeypot spam protection
        if (!empty($data['website_hp'])) {
            return response()->json(['success' => true, 'message' => 'Thank you!']);
        }

        $visitor = $this->tracker->resolveVisitor($request);
        $cleanPhone = preg_replace('/[^0-9]/', '', $data['mobile']);
        if (strlen($cleanPhone) >= 10) {
            $cleanPhone = substr($cleanPhone, -10);
        }

        $property = !empty($data['property_id']) ? Property::find($data['property_id']) : null;
        $city = $data['preferred_city'] ?? ($property?->location ?? $visitor->city ?? 'Gurgaon');
        $purpose = $data['purpose'] ?? ($property?->purpose ?? 'rent');
        $ownerId = $property?->user_id;

        // Auto-assign to available sales admin
        $assignedStaff = User::whereIn('role', ['admin', 'sales_manager', 'sales_executive'])->first();

        // Duplicate Check: same mobile + property within 48 hours
        $existingLead = Lead::where('mobile', 'LIKE', '%' . $cleanPhone)
            ->when($property, fn($q) => $q->where('property_id', $property->id))
            ->where('created_at', '>=', now()->subHours(48))
            ->first();

        $consentText = 'I agree to be contacted by UnlockRentals regarding my property enquiry and selected property services through phone, SMS, email or WhatsApp.';

        if ($existingLead) {
            $existingLead->update([
                'visitor_id' => $visitor->id,
                'user_id' => auth()->id() ?? $existingLead->user_id,
                'message' => $data['message'] ? $existingLead->message . "\n[Update " . now()->format('d M H:i') . ']: ' . $data['message'] : $existingLead->message,
                'engagement_score' => $existingLead->engagement_score + 10,
                'whatsapp_opt_in' => $request->boolean('whatsapp_opt_in') ?: $existingLead->whatsapp_opt_in,
            ]);

            $lead = $existingLead;
        } else {
            $lead = DB::transaction(function () use ($data, $visitor, $property, $ownerId, $assignedStaff, $cleanPhone, $city, $purpose, $consentText, $request) {
                return Lead::create([
                    'visitor_id' => $visitor->id,
                    'user_id' => auth()->id() ?? $visitor->user_id,
                    'property_id' => $property?->id,
                    'owner_id' => $ownerId,
                    'assigned_to' => $assignedStaff?->id,
                    'lead_source' => $data['lead_source'] ?? 'website_lead_form',
                    'lead_status' => 'new',
                    'lead_stage' => 'enquiry',
                    'name' => $data['name'],
                    'mobile' => $cleanPhone,
                    'email' => $data['email'] ?? null,
                    'preferred_city' => $city,
                    'preferred_locality' => $data['preferred_locality'] ?? ($property?->locality ?? null),
                    'property_type' => $data['property_type'] ?? ($property?->type ?? '2bhk'),
                    'purpose' => $purpose,
                    'budget_min' => $data['budget_min'] ?? null,
                    'budget_max' => $data['budget_max'] ?? ($property?->price ?? null),
                    'bedrooms' => $data['bedrooms'] ?? ($property?->bedrooms ? (string)$property->bedrooms : null),
                    'furnished_status' => $data['furnished_status'] ?? ($property?->furnishing ?? null),
                    'move_in_date' => $data['move_in_date'] ?? null,
                    'message' => $data['message'] ?? null,
                    'whatsapp_opt_in' => $request->boolean('whatsapp_opt_in'),
                    'marketing_opt_in' => $request->boolean('marketing_opt_in'),
                    'consent_text' => $consentText,
                    'consent_at' => now(),
                    'engagement_score' => 25,
                    'next_follow_up_at' => now()->addHours(4),
                ]);
            });

            // Record Consent
            ConsentRecord::create([
                'visitor_id' => $visitor->id,
                'user_id' => auth()->id() ?? $visitor->user_id,
                'lead_id' => $lead->id,
                'consent_type' => 'service_enquiry',
                'is_granted' => true,
                'consent_text' => $consentText,
                'form_source' => $lead->lead_source,
                'ip_address' => $request->ip(),
                'granted_at' => now(),
            ]);

            if ($request->boolean('whatsapp_opt_in')) {
                ConsentRecord::create([
                    'visitor_id' => $visitor->id,
                    'user_id' => auth()->id() ?? $visitor->user_id,
                    'lead_id' => $lead->id,
                    'consent_type' => 'whatsapp_updates',
                    'is_granted' => true,
                    'consent_text' => 'User opted in to receive property alerts and updates on WhatsApp.',
                    'form_source' => $lead->lead_source,
                    'ip_address' => $request->ip(),
                    'granted_at' => now(),
                ]);
            }
        }

        // Update Visitor status & record event
        $visitor->update(['has_converted_lead' => true]);
        $this->tracker->recordEvent($visitor, 'lead_submitted', $property?->id, $request->fullUrl(), [
            'lead_id' => $lead->id,
            'source' => $lead->lead_source,
        ]);

        // Send WhatsApp confirmation if opted in
        if ($lead->whatsapp_opt_in) {
            SendWhatsAppMessageJob::dispatch(
                $lead,
                "Hello {$lead->name}! Thank you for your inquiry on UnlockRentals. Our verified property advisor has received your request and will connect with you shortly. 🏡"
            );
        }

        // Fetch Matching Properties
        $matchingProperties = Property::approved()
            ->with(['primaryImage'])
            ->when($city, fn($q) => $q->where('location', 'like', '%' . $city . '%'))
            ->when($purpose, fn($q) => $q->where('purpose', $purpose))
            ->when($property, fn($q) => $q->where('id', '!=', $property->id))
            ->latest()
            ->take(4)
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'title' => $p->title,
                    'price' => number_format($p->price, 0),
                    'location' => $p->location,
                    'locality' => $p->locality,
                    'bedrooms' => $p->bedrooms,
                    'url' => route('properties.show', $p),
                    'image' => $p->primaryImage ? $p->primaryImage->imageUrl() : asset('images/logo.png'),
                ];
            });

        return response()->json([
            'success' => true,
            'message' => 'Thank you! Your inquiry has been submitted successfully.',
            'lead_id' => $lead->id,
            'matching_properties' => $matchingProperties,
        ]);
    }
}
