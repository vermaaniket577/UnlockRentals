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

        // Auto-assign to available sales admin or owner
        $assignedStaff = User::whereIn('role', ['admin', 'owner', 'sales_manager', 'sales_executive'])->first();

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

    /**
     * Dedicated intake endpoint for Admission and General Enquiry leads from external websites (e.g. Anushram).
     * Accepts JSON, query params, or form data, normalizes phone/student/course, saves directly into CRM leads table.
     * POST or PUT /api/general-enquiry/create
     * POST or PUT /v1/api/general-enquiry/create
     * POST or PUT /api/leads/admission
     */
    public function storeAdmissionLead(Request $request): JsonResponse
    {
        // 1. Extract contact/phone
        $rawPhone = $request->input('phone') 
            ?: $request->input('mobile') 
            ?: $request->input('contact') 
            ?: $request->input('student_mobile') 
            ?: $request->input('phone_number')
            ?: '';

        $cleanPhone = preg_replace('/[^0-9]/', '', (string)$rawPhone);
        if (strlen($cleanPhone) >= 10) {
            $cleanPhone = substr($cleanPhone, -10);
        }

        if (empty($cleanPhone) || strlen($cleanPhone) < 10) {
            return response()->json([
                'success' => false,
                'message' => 'A valid 10-digit mobile number is required.',
            ], 422);
        }

        // 2. Extract name
        $name = $request->input('name') 
            ?: $request->input('full_name') 
            ?: $request->input('student_name') 
            ?: $request->input('candidate_name') 
            ?: 'Admission Enquirer';

        // 3. Extract email
        $email = $request->input('email') 
            ?: $request->input('student_email') 
            ?: null;

        // 4. Extract course & academic info
        $courseParts = array_filter([
            $request->input('stream'),
            $request->input('course'),
            $request->input('subject'),
            $request->input('program'),
            $request->input('degree'),
            $request->input('branch'),
            $request->input('specialization'),
            $request->input('college'),
            $request->input('university'),
            $request->input('admission_year'),
        ]);
        $academicSummary = implode(' • ', $courseParts);

        // 5. Build rich message
        $userMsg = $request->input('message') 
            ?: $request->input('enquiry') 
            ?: $request->input('query') 
            ?: $request->input('notes') 
            ?: '';

        $fullMessage = $academicSummary 
            ? ($userMsg ? "Course / Program: {$academicSummary}\nEnquiry: {$userMsg}" : "Course / Program: {$academicSummary}") 
            : ($userMsg ?: 'Admission Inquiry submitted via external portal');

        $city = $request->input('city') 
            ?: $request->input('preferred_city') 
            ?: $request->input('state') 
            ?: null;

        $source = $request->input('source') 
            ?: $request->input('lead_source') 
            ?: 'admission_portal';

        // 6. Deduplicate or update within 48h
        $existing = Lead::where('mobile', 'LIKE', '%' . $cleanPhone)
            ->where('created_at', '>=', now()->subHours(48))
            ->first();

        if ($existing) {
            $existing->update([
                'lead_source' => $source,
                'message' => $existing->message . "\n[Update " . now()->format('d M H:i') . "]: " . $fullMessage,
                'engagement_score' => $existing->engagement_score + 15,
                'lead_status' => 'new',
            ]);
            $lead = $existing;
        } else {
            $assignedStaff = User::whereIn('role', ['admin', 'owner', 'sales_manager', 'sales_executive'])->first();
            $lead = Lead::create([
                'name' => $name,
                'mobile' => $cleanPhone,
                'email' => $email,
                'lead_source' => $source,
                'lead_status' => 'new',
                'lead_stage' => 'enquiry',
                'property_type' => 'admission',
                'purpose' => 'rent', // Satisfies MySQL ENUM
                'preferred_city' => $city,
                'preferred_locality' => $request->input('locality') ?: null,
                'message' => $fullMessage,
                'notes' => $academicSummary ?: 'Admission Lead',
                'assigned_to' => $assignedStaff?->id,
                'engagement_score' => 30,
                'whatsapp_opt_in' => $request->boolean('whatsapp_opt_in', true),
                'next_follow_up_at' => now()->addHours(2),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Admission lead successfully recorded in UnlockRentals CRM.',
            'lead_id' => $lead->id,
            'lead' => [
                'id' => $lead->id,
                'name' => $lead->name,
                'mobile' => $lead->mobile,
                'stream' => $lead->stream,
                'course' => $lead->course,
                'source' => $lead->lead_source,
                'status' => $lead->lead_status,
                'created_at' => $lead->created_at->toIso8601String(),
            ]
        ], 201);
    }
}
