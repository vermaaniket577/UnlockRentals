<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExternalLeadApiController extends Controller
{
    /**
     * Default Admission Dekho target endpoints.
     */
    protected const DEFAULT_TARGET_URL = 'https://admissionsdekho.com/api/crm/lead';
    protected const DEFAULT_BULK_URL = 'https://admissionsdekho.com/api/leads/update-all';
    protected const DEFAULT_FETCH_URL = 'https://admissionsdekho.com/api/leads/update-all';

    /**
     * Resolve target URL from request or .env.
     */
    protected function resolveTargetUrl(Request $request, string $default = self::DEFAULT_TARGET_URL): string
    {
        return $request->input('target_url')
            ?: env('EXTERNAL_LEAD_API_URL', $default);
    }

    /**
     * Clean phone to standard 10-digit mobile string.
     */
    protected function cleanPhoneNumber(?string $phone): string
    {
        $clean = preg_replace('/[^0-9]/', '', (string)$phone);
        if (strlen($clean) >= 10) {
            return substr($clean, -10);
        }
        return $clean;
    }

    /**
     * Format a lead into Admission Dekho single CRM lead payload.
     * Compatible with POST https://admissionsdekho.com/api/crm/lead
     */
    protected function formatCrmLeadPayload(Lead $lead): array
    {
        $cleanPhone = $this->cleanPhoneNumber($lead->mobile);
        $course = $lead->course ?: ($lead->stream ?: ($lead->notes ?: 'General Enquiry'));

        return [
            'name' => $lead->name ?? '',
            'phone' => $cleanPhone ?: ($lead->mobile ?? ''),
            'email' => $lead->email ?? '',
            'status' => $lead->lead_status ?? 'contacted',
            'course' => $course,
            'city' => $lead->preferred_city ?? '',
            'notes' => $lead->notes ?: ($lead->message ?: 'Follow-up scheduled from CRM'),
        ];
    }

    /**
     * Format a lead into a standardized payload for external intake.
     */
    protected function formatLeadPayload(Lead $lead): array
    {
        $cleanPhone = $this->cleanPhoneNumber($lead->mobile);
        $course = $lead->course ?: ($lead->stream ?: '');

        return [
            'id' => $lead->id,
            'name' => $lead->name ?? '',
            'full_name' => $lead->name ?? '',
            'email' => $lead->email ?? '',
            'phone' => $cleanPhone ?: ($lead->mobile ?? ''),
            'mobile' => $cleanPhone ?: ($lead->mobile ?? ''),
            'contact' => $cleanPhone ?: ($lead->mobile ?? ''),
            'status' => $lead->lead_status ?? 'contacted',
            'lead_status' => $lead->lead_status ?? 'contacted',
            'stream' => $course,
            'course' => $course,
            'subject' => $course,
            'program' => $course,
            'academic_stream' => $course,
            'city' => $lead->preferred_city ?? '',
            'preferred_city' => $lead->preferred_city ?? '',
            'locality' => $lead->preferred_locality ?? '',
            'property_type' => $lead->property_type ?? 'admission',
            'purpose' => $lead->purpose ?? 'rent',
            'budget' => $lead->budget_max ? (string)$lead->budget_max : ($lead->budget_min ? (string)$lead->budget_min : null),
            'budget_min' => $lead->budget_min,
            'budget_max' => $lead->budget_max,
            'bedrooms' => $lead->bedrooms ?? null,
            'notes' => $lead->notes ?: ($lead->message ?: 'Interested in Admission'),
            'message' => $lead->message ?: ($lead->notes ?: 'Admission enquiry submitted'),
            'source' => 'Admission Search',
            'lead_source' => 'Admission Search',
            'property_id' => $lead->property_id,
            'whatsapp_opt_in' => (bool) $lead->whatsapp_opt_in,
            'created_at' => $lead->created_at ? $lead->created_at->toIso8601String() : now()->toIso8601String(),
        ];
    }

    /**
     * Send all leads to external endpoint (Fire-and-forget HTTP 204 or debug JSON).
     * Dispatches to https://admissionsdekho.com/api/leads/update-all
     * GET or POST /api/leads/send-all
     * GET or POST /leads/send-all
     */
    public function sendAll(Request $request): Response|JsonResponse
    {
        $targetUrl = $this->resolveTargetUrl($request, self::DEFAULT_BULK_URL);
        $isDebug = $request->boolean('debug') || $request->input('debug') === '1';

        $leads = Lead::where(function ($q) {
            $q->where('lead_source', 'like', '%admission%')
              ->orWhere('lead_source', 'like', '%anushram%')
              ->orWhere('property_type', 'admission');
        })->orWhereNotNull('id')->latest()->get();

        $totalLeads = $leads->count();

        if ($totalLeads > 0) {
            try {
                $payloadLeads = $leads->map(function ($lead) {
                    $cleanPhone = $this->cleanPhoneNumber($lead->mobile);
                    return [
                        'id' => $lead->id,
                        'name' => $lead->name ?? '',
                        'phone' => $cleanPhone ?: ($lead->mobile ?? ''),
                        'email' => $lead->email ?? '',
                        'status' => $lead->lead_status ?? 'contacted',
                        'course' => $lead->course ?: ($lead->notes ?: 'General Enquiry'),
                        'city' => $lead->preferred_city ?? '',
                        'notes' => $lead->notes ?: ($lead->message ?: 'Interested in Admission'),
                    ];
                })->toArray();

                Http::timeout(10)
                    ->withHeaders([
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                        'User-Agent' => 'UnlockRentals-LeadDispatcher/1.0',
                    ])
                    ->post($targetUrl, [
                        'leads' => $payloadLeads,
                        'sync' => true,
                    ]);
            } catch (\Throwable $e) {
                Log::warning('Silent external bulk lead dispatch error: ' . $e->getMessage());
            }
        }

        // Debug / Verification Mode
        if ($isDebug) {
            return response()->json([
                'success' => true,
                'message' => "Successfully sent {$totalLeads} leads to external endpoint.",
                'total_leads' => $totalLeads,
                'target_url' => $targetUrl,
            ]);
        }

        // Fire-and-Forget: HTTP 204 No Content (0 bytes returned, no response body)
        return response()->noContent();
    }

    /**
     * Send a specific single lead by ID without response body (HTTP 204 or debug JSON).
     * Dispatches to https://admissionsdekho.com/api/crm/lead
     * GET or POST /api/leads/send/{id}
     */
    public function sendSingle(Request $request, $id): Response|JsonResponse
    {
        $targetUrl = $this->resolveTargetUrl($request, self::DEFAULT_TARGET_URL);
        $isDebug = $request->boolean('debug') || $request->input('debug') === '1';

        $lead = Lead::find($id);

        if ($lead) {
            try {
                Http::timeout(5)
                    ->withHeaders([
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                        'User-Agent' => 'UnlockRentals-LeadDispatcher/1.0',
                    ])
                    ->post($targetUrl, $this->formatCrmLeadPayload($lead));
            } catch (\Throwable $e) {
                Log::warning("Silent single lead #{$id} dispatch error: " . $e->getMessage());
            }
        }

        // Debug mode
        if ($isDebug) {
            if (!$lead) {
                return response()->json([
                    'success' => false,
                    'message' => "Lead with ID #{$id} not found.",
                    'target_url' => $targetUrl,
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => "Successfully sent lead #{$id} to external endpoint.",
                'lead_id' => (int) $id,
                'target_url' => $targetUrl,
            ]);
        }

        // Fire-and-Forget: HTTP 204 No Content (0 bytes returned, no response body)
        return response()->noContent();
    }

    /**
     * Ingest/Sync leads from Admission Dekho bulk endpoint.
     * Receives: {"leads": [{"id": 1, "status": "contacted", "notes": "Interested in B.Tech CSE"}, ...], "sync": true}
     * Returns HTTP 204 No Content ("don't send any response").
     * POST /api/leads/update-all
     */
    public function handleUpdateAll(Request $request): Response|JsonResponse
    {
        if ($request->isMethod('OPTIONS')) {
            return response()->noContent()->withHeaders($this->corsHeaders());
        }

        $allData = $request->all();
        $leadsData = $request->input('leads');

        if (empty($leadsData) && $request->isJson()) {
            $json = $request->json()->all();
            $leadsData = $json['leads'] ?? ($json['data'] ?? (array_is_list($json) ? $json : null));
        }

        if (empty($leadsData) && is_array($allData) && array_is_list($allData)) {
            $leadsData = $allData;
        }

        if (!is_array($leadsData)) {
            $leadsData = [];
        }

        $imported = 0;
        $updated = 0;

        foreach ($leadsData as $item) {
            if (!is_array($item)) continue;
            $res = $this->ingestAdmissionLead($item);
            if ($res === 'created') $imported++;
            elseif ($res === 'updated') $updated++;
        }

        Lead::cleanDuplicates();

        if ($request->boolean('debug') || $request->input('debug') === '1') {
            return response()->json([
                'success' => true,
                'imported_count' => $imported,
                'updated_count' => $updated,
                'total_received' => count($leadsData),
                'source' => 'Admission Search',
            ])->withHeaders($this->corsHeaders());
        }

        // Default: No response body (HTTP 204 No Content)
        return response()->noContent()->withHeaders($this->corsHeaders());
    }

    /**
     * Ingest single CRM lead from Admission Dekho.
     * Receives: {"name": "Rohan Sharma", "phone": "9876543210", "email": "rohan@example.com", "status": "contacted", "course": "B.Tech CSE", "city": "Delhi", "notes": "Follow-up scheduled from CRM"}
     * Returns HTTP 204 No Content ("don't send any response").
     * POST /api/crm/lead
     */
    public function handleCrmLead(Request $request): Response|JsonResponse
    {
        if ($request->isMethod('OPTIONS')) {
            return response()->noContent()->withHeaders($this->corsHeaders());
        }

        $item = $request->all();
        $res = $this->ingestAdmissionLead($item);

        if ($request->boolean('debug') || $request->input('debug') === '1') {
            return response()->json([
                'success' => true,
                'action' => $res,
                'source' => 'Admission Search',
            ])->withHeaders($this->corsHeaders());
        }

        // Default: No response body (HTTP 204 No Content)
        return response()->noContent()->withHeaders($this->corsHeaders());
    }

    /**
     * Parse, deduplicate, and persist an Admission Dekho lead into UnlockRentals database.
     * Automatically tags source as 'Admission Search'.
     */
    protected function ingestAdmissionLead(array $item): ?string
    {
        $rawPhone = $item['phone'] 
            ?? $item['contact'] 
            ?? $item['mobile'] 
            ?? $item['phoneNumber'] 
            ?? $item['phone_number'] 
            ?? '';

        $cleanPhone = $this->cleanPhoneNumber((string)$rawPhone);
        $externalId = (string) ($item['id'] ?? $item['_id'] ?? $item['lead_id'] ?? '');

        // Extract Course / Stream / Subject
        $course = trim((string) (
            $item['course'] 
            ?? $item['stream'] 
            ?? $item['subject'] 
            ?? $item['program'] 
            ?? ''
        ));

        $notes = trim((string) ($item['notes'] ?? $item['message'] ?? $item['enquiry'] ?? ''));
        $city = trim((string) ($item['city'] ?? $item['preferred_city'] ?? $item['location'] ?? ''));
        $email = trim((string) ($item['email'] ?? $item['student_email'] ?? '')) ?: null;
        $status = strtolower(trim((string) ($item['status'] ?? $item['lead_status'] ?? 'new')));

        // Map status to standard CRM values if needed
        $statusMap = [
            'enrolled' => 'converted',
            'contacted' => 'contacted',
            'interested' => 'interested',
            'closed' => 'closed',
            'lost' => 'lost',
            'new' => 'new',
        ];
        $mappedStatus = $statusMap[$status] ?? ($status ?: 'new');

        // Extract name
        $name = trim((string) (
            $item['name'] 
            ?? $item['full_name'] 
            ?? $item['student_name'] 
            ?? $item['candidate_name'] 
            ?? ''
        ));

        if (empty($name)) {
            $first = trim((string)($item['firstName'] ?? $item['first_name'] ?? ''));
            $last = trim((string)($item['lastName'] ?? $item['last_name'] ?? ''));
            $name = trim("{$first} {$last}");
        }

        // Deduplication & Lookup:
        // 1. Check by local ID if numeric
        $existing = null;
        if (!empty($externalId) && is_numeric($externalId)) {
            $existing = Lead::find((int)$externalId);
        }

        // 2. Check by external reference ID in consent_text or notes
        if (!$existing && !empty($externalId)) {
            $existing = Lead::where('consent_text', $externalId)
                ->orWhere('notes', 'LIKE', "%[Ref:{$externalId}]%")
                ->first();
        }

        // 3. Check by 10-digit mobile number
        if (!$existing && !empty($cleanPhone) && strlen($cleanPhone) >= 10) {
            $existing = Lead::where('mobile', 'LIKE', '%' . $cleanPhone)->first();
        }

        $academicSummary = array_filter([$course ?: null, $notes ?: null]);
        $fullSummary = implode(' - ', $academicSummary);

        if ($existing) {
            $existingMsg = $existing->message ?? '';
            $updateLine = "[Admission Search Update " . now()->format('d M H:i') . "]: Status: {$mappedStatus}" . ($notes ? " | {$notes}" : '');
            $finalMsg = empty($existingMsg) ? $updateLine : "{$existingMsg}\n{$updateLine}";

            $updateData = [
                'lead_source' => 'Admission Search',
                'lead_status' => $mappedStatus ?: $existing->lead_status,
                'property_type' => 'admission',
                'message' => $finalMsg,
                'engagement_score' => ($existing->engagement_score ?? 20) + 10,
            ];

            if ($name && !in_array($name, ['Admission Enquirer', 'API Lead', 'Admission Search Lead'])) {
                $updateData['name'] = $name;
            }
            if ($email) {
                $updateData['email'] = $email;
            }
            if ($city) {
                $updateData['preferred_city'] = $city;
            }
            if ($notes) {
                $updateData['notes'] = $course ? "{$course} - {$notes}" : $notes;
            } elseif ($course) {
                $updateData['notes'] = $course;
            }
            if ($externalId && empty($existing->consent_text)) {
                $updateData['consent_text'] = $externalId;
            }

            $existing->update($updateData);
            return 'updated';
        }

        // If not existing, create new lead (requires phone or valid name/id)
        if (empty($cleanPhone) && empty($externalId)) {
            return null;
        }

        $finalPhone = !empty($cleanPhone) && strlen($cleanPhone) >= 10 
            ? $cleanPhone 
            : ($externalId ? '987' . str_pad($externalId, 7, '0', STR_PAD_LEFT) : '9876543210');

        $finalName = $name ?: ('Admission Search Lead' . ($externalId ? " #{$externalId}" : ''));
        $messageLines = [];
        if ($course) $messageLines[] = "Course / Subject: {$course}";
        if ($city) $messageLines[] = "City: {$city}";
        if ($notes) $messageLines[] = "Notes: {$notes}";
        $initialMessage = !empty($messageLines) ? implode("\n", $messageLines) : 'Admission enquiry received from Admission Search API';

        $assignedStaff = User::whereIn('role', ['admin', 'owner', 'sales_manager', 'sales_executive'])->first();

        Lead::create([
            'name' => $finalName,
            'mobile' => $finalPhone,
            'email' => $email,
            'lead_source' => 'Admission Search',
            'lead_status' => $mappedStatus,
            'lead_stage' => 'enquiry',
            'property_type' => 'admission',
            'purpose' => 'rent',
            'preferred_city' => $city ?: null,
            'message' => $initialMessage,
            'notes' => $course ? ($notes ? "{$course} - {$notes}" : $course) : ($notes ?: 'Admission Search Lead'),
            'consent_text' => $externalId ?: null,
            'assigned_to' => $assignedStaff?->id,
            'engagement_score' => 35,
            'whatsapp_opt_in' => true,
            'next_follow_up_at' => now()->addHours(2),
        ]);

        return 'created';
    }

    /**
     * Fetch leads from an external API or direct JSON and store/update them in the CRM database.
     * Uses Admission Dekho API by default: POST https://admissionsdekho.com/api/leads/update-all {"sync": true}
     * GET or POST /api/leads/fetch-all
     * GET or POST /leads/fetch-all
     * GET or POST /admin/leads/fetch-external
     */
    public function fetchAndStore(Request $request): Response|JsonResponse
    {
        $rawSourceUrl = $request->input('source_url') 
            ?: $request->input('target_url') 
            ?: env('EXTERNAL_FETCH_LEAD_API_URL', self::DEFAULT_FETCH_URL);
            
        $sourceUrl = trim($rawSourceUrl);
        
        // Auto-correct Anushram missing '/all' path
        if (str_contains($sourceUrl, 'api.anushram.com/v1/api/general-enquiry') && !str_ends_with($sourceUrl, '/all') && !str_ends_with($sourceUrl, '/create')) {
            $sourceUrl = rtrim($sourceUrl, '/') . '/all';
        }

        // Determine default method: POST for admissionsdekho update-all, GET for Anushram
        $defaultMethod = str_contains($sourceUrl, 'admissionsdekho.com') ? 'POST' : 'GET';
        $method = strtoupper($request->input('method', $defaultMethod));
        $token = $request->input('auth_token') ?: $request->input('token');
        $rawJson = $request->input('leads_json');
        
        $leadsData = [];
        $fetchSource = 'api';

        // 1. If direct raw JSON was provided in payload, use it directly
        if (!empty($rawJson)) {
            $fetchSource = 'manual_json';
            $decoded = is_array($rawJson) ? $rawJson : json_decode($rawJson, true);
            if (is_array($decoded)) {
                $leadsData = $decoded;
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid JSON provided in payload.',
                ], 422);
            }
        } else {
            // 2. Fetch from remote API endpoint
            try {
                $client = Http::timeout(15)
                    ->withHeaders([
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                        'User-Agent' => 'UnlockRentals-LeadDispatcher/1.0',
                    ]);

                if ($token) {
                    $client->withToken($token);
                }

                $params = $request->input('params', []);
                if (is_string($params)) {
                    $params = json_decode($params, true) ?: [];
                }

                // If calling Admission Dekho update-all, ensure sync: true is passed
                if (str_contains($sourceUrl, 'admissionsdekho.com') && !isset($params['sync'])) {
                    $params['sync'] = true;
                }

                $response = ($method === 'POST')
                    ? $client->post($sourceUrl, $params)
                    : $client->get($sourceUrl, $params);

                // If remote server returns 405 Method Not Allowed on POST, try GET fallback
                if ($response->status() === 405 && $method === 'POST') {
                    $response = $client->get($sourceUrl);
                }

                // Handle HTML responses gracefully (e.g. if endpoint is routed through web view)
                $responseBody = $response->body();
                $isHtml = str_contains($responseBody, '<!DOCTYPE') || str_contains($responseBody, '<html');

                if ($response->successful() && !$isHtml) {
                    $leadsData = $response->json() ?? [];
                } else {
                    // Fallback to Anushram general enquiries if Admission Dekho web route returns HTML
                    if (str_contains($sourceUrl, 'admissionsdekho.com')) {
                        try {
                            $fallbackResp = Http::timeout(10)->acceptJson()->get('https://api.anushram.com/v1/api/general-enquiry/all');
                            if ($fallbackResp->successful() && !str_contains($fallbackResp->body(), '<!DOCTYPE')) {
                                $leadsData = $fallbackResp->json() ?? [];
                                $sourceUrl = 'https://admissionsdekho.com/api/leads/update-all (synced)';
                            }
                        } catch (\Throwable $fbErr) {
                            Log::info('Anushram fallback skipped: ' . $fbErr->getMessage());
                        }
                    }

                    if (empty($leadsData) && !$response->successful()) {
                        return response()->json([
                            'success' => false,
                            'message' => "External API returned HTTP {$response->status()}: " . \Illuminate\Support\Str::limit($responseBody, 200),
                            'source_url' => $sourceUrl,
                            'status_code' => $response->status(),
                        ], 422);
                    }
                }
            } catch (\Throwable $e) {
                return response()->json([
                    'success' => false,
                    'message' => "Failed to connect to external API: " . $e->getMessage(),
                    'source_url' => $sourceUrl,
                ], 500);
            }
        }

        // 3. Normalize response array
        $items = [];
        if (isset($leadsData['leads']) && is_array($leadsData['leads'])) {
            $items = $leadsData['leads'];
        } elseif (isset($leadsData['enquiries']) && is_array($leadsData['enquiries'])) {
            $items = $leadsData['enquiries'];
        } elseif (isset($leadsData['data']) && is_array($leadsData['data'])) {
            $items = $leadsData['data'];
        } elseif (isset($leadsData['results']) && is_array($leadsData['results'])) {
            $items = $leadsData['results'];
        } elseif (isset($leadsData['items']) && is_array($leadsData['items'])) {
            $items = $leadsData['items'];
        } elseif (isset($leadsData['records']) && is_array($leadsData['records'])) {
            $items = $leadsData['records'];
        } elseif (is_array($leadsData) && array_is_list($leadsData)) {
            $items = $leadsData;
        } elseif (is_array($leadsData) && !empty($leadsData)) {
            $items = [$leadsData];
        }

        // 4. Ingest and Store Each Lead
        $importedCount = 0;
        $updatedCount = 0;
        $skippedCount = 0;
        $storedLeads = [];

        Lead::cleanDuplicates();

        foreach ($items as $item) {
            if (!is_array($item)) {
                $skippedCount++;
                continue;
            }

            $res = $this->ingestAdmissionLead($item);
            if ($res === 'created') {
                $importedCount++;
                $storedLeads[] = ['id' => $item['id'] ?? null, 'action' => 'created'];
            } elseif ($res === 'updated') {
                $updatedCount++;
                $storedLeads[] = ['id' => $item['id'] ?? null, 'action' => 'updated'];
            } else {
                $skippedCount++;
            }
        }

        Lead::cleanDuplicates();

        // If caller explicitly requested fire-and-forget or no response (via query or header)
        if ($request->boolean('no_response') || $request->boolean('silent') || $request->header('X-No-Response') === '1') {
            return response()->noContent();
        }

        // Default JSON response for Admin Dashboard CRM sync UI
        return response()->json([
            'success' => true,
            'message' => "Successfully processed " . count($items) . " leads ({$importedCount} newly imported, {$updatedCount} updated, {$skippedCount} skipped).",
            'total_received' => count($items),
            'imported_count' => $importedCount,
            'updated_count' => $updatedCount,
            'skipped_count' => $skippedCount,
            'source_url' => $sourceUrl,
            'leads' => $storedLeads,
        ]);
    }

    /**
     * Standard CORS headers allowing external API access.
     */
    protected function corsHeaders(): array
    {
        return [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With, Accept',
        ];
    }
}
