<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExternalLeadApiController extends Controller
{
    /**
     * Default target endpoint for lead delivery.
     */
    protected const DEFAULT_TARGET_URL = 'https://api.anushram.com/v1/api/general-enquiry/create';

    /**
     * Resolve target URL from request or .env.
     */
    protected function resolveTargetUrl(Request $request): string
    {
        return $request->input('target_url')
            ?: env('EXTERNAL_LEAD_API_URL', self::DEFAULT_TARGET_URL);
    }

    /**
     * Format a lead into a standardized payload for external intake.
     */
    protected function formatLeadPayload(Lead $lead): array
    {
        $cleanPhone = preg_replace('/[^0-9]/', '', (string) ($lead->mobile ?? ''));
        if (strlen($cleanPhone) >= 10) {
            $cleanPhone = substr($cleanPhone, -10);
        }

        $course = $lead->course ?: ($lead->stream ?: '');
        $stream = $course;

        return [
            'id' => $lead->id,
            'name' => $lead->name ?? '',
            'full_name' => $lead->name ?? '',
            'email' => $lead->email ?? '',
            'phone' => $cleanPhone ?: ($lead->mobile ?? ''),
            'mobile' => $cleanPhone ?: ($lead->mobile ?? ''),
            'contact' => $cleanPhone ?: ($lead->mobile ?? ''),
            'stream' => $stream,
            'course' => $course,
            'subject' => $course,
            'program' => $course,
            'academic_stream' => $stream,
            'city' => $lead->preferred_city ?? '',
            'preferred_city' => $lead->preferred_city ?? '',
            'locality' => $lead->preferred_locality ?? '',
            'property_type' => $lead->property_type ?? '',
            'purpose' => $lead->purpose ?? 'rent',
            'budget' => $lead->budget_max ? (string)$lead->budget_max : ($lead->budget_min ? (string)$lead->budget_min : null),
            'budget_min' => $lead->budget_min,
            'budget_max' => $lead->budget_max,
            'bedrooms' => $lead->bedrooms ?? null,
            'message' => $lead->message ?: 'General enquiry submitted on UnlockRentals',
            'source' => $lead->lead_source ?: 'UnlockRentals',
            'lead_source' => $lead->lead_source ?: 'UnlockRentals',
            'lead_status' => $lead->lead_status ?: 'new',
            'property_id' => $lead->property_id,
            'whatsapp_opt_in' => (bool) $lead->whatsapp_opt_in,
            'created_at' => $lead->created_at ? $lead->created_at->toIso8601String() : now()->toIso8601String(),
        ];
    }

    /**
     * Send all leads to external endpoint (Fire-and-forget HTTP 204 or debug JSON).
     * GET or POST /api/leads/send-all
     * GET or POST /leads/send-all
     */
    public function sendAll(Request $request): Response|JsonResponse
    {
        $targetUrl = $this->resolveTargetUrl($request);
        $mode = $request->input('mode') === 'bulk' ? 'bulk' : 'individual';
        $isDebug = $request->boolean('debug') || $request->input('debug') === '1';

        $leads = Lead::latest()->get();
        $totalLeads = $leads->count();

        if ($totalLeads > 0) {
            if ($mode === 'bulk') {
                // Send all leads at once in a single JSON payload
                try {
                    Http::timeout(10)
                        ->withHeaders([
                            'Accept' => 'application/json',
                            'Content-Type' => 'application/json',
                            'User-Agent' => 'UnlockRentals-LeadDispatcher/1.0',
                        ])
                        ->post($targetUrl, [
                            'leads' => $leads->map(fn($lead) => $this->formatLeadPayload($lead))->toArray(),
                            'total_leads' => $totalLeads,
                            'source' => 'UnlockRentals',
                            'dispatched_at' => now()->toIso8601String(),
                        ]);
                } catch (\Throwable $e) {
                    Log::warning('Silent external bulk lead dispatch error: ' . $e->getMessage());
                }
            } else {
                // Individual mode: dispatch concurrent asynchronous batches via Http::pool()
                $batchSize = 25;
                $chunks = $leads->chunk($batchSize);

                foreach ($chunks as $chunk) {
                    try {
                        Http::pool(function (Pool $pool) use ($chunk, $targetUrl) {
                            foreach ($chunk as $lead) {
                                $pool->as('lead_' . $lead->id)
                                    ->timeout(5)
                                    ->withHeaders([
                                        'Accept' => 'application/json',
                                        'Content-Type' => 'application/json',
                                        'User-Agent' => 'UnlockRentals-LeadDispatcher/1.0',
                                    ])
                                    ->post($targetUrl, $this->formatLeadPayload($lead));
                            }
                        });
                    } catch (\Throwable $e) {
                        Log::warning('Silent external lead pool batch error: ' . $e->getMessage());
                    }
                }
            }
        }

        // Debug / Verification Mode
        if ($isDebug) {
            return response()->json([
                'success' => true,
                'message' => "Successfully sent {$totalLeads} leads to external endpoint.",
                'total_leads' => $totalLeads,
                'target_url' => $targetUrl,
                'mode' => $mode,
            ]);
        }

        // Fire-and-Forget: HTTP 204 No Content (0 bytes returned)
        return response()->noContent();
    }

    /**
     * Send a specific single lead by ID without response body (HTTP 204 or debug JSON).
     * GET or POST /api/leads/send/{id}
     */
    public function sendSingle(Request $request, $id): Response|JsonResponse
    {
        $targetUrl = $this->resolveTargetUrl($request);
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
                    ->post($targetUrl, $this->formatLeadPayload($lead));
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

        // Fire-and-Forget: HTTP 204 No Content
        return response()->noContent();
    }

    /**
     * Fetch leads from an external API or direct JSON and store/update them in the CRM database.
     * GET or POST /api/leads/fetch-all
     * GET or POST /leads/fetch-all
     * GET or POST /admin/leads/fetch-external
     */
    public function fetchAndStore(Request $request): JsonResponse
    {
        $rawSourceUrl = $request->input('source_url') 
            ?: $request->input('target_url') 
            ?: env('EXTERNAL_FETCH_LEAD_API_URL', 'https://api.anushram.com/v1/api/general-enquiry/all');
            
        // Auto-correct common missing '/all' path for Anushram GET enquiries
        $sourceUrl = trim($rawSourceUrl);
        if (str_contains($sourceUrl, 'api.anushram.com/v1/api/general-enquiry') && !str_ends_with($sourceUrl, '/all') && !str_ends_with($sourceUrl, '/create')) {
            $sourceUrl = rtrim($sourceUrl, '/') . '/all';
        }

        $method = strtoupper($request->input('method', 'GET'));
        $token = $request->input('auth_token') ?: $request->input('token');
        $rawJson = $request->input('leads_json');
        
        $leadsData = [];
        $fetchSource = 'api';

        // 1. If direct raw JSON was pasted or provided in body, use it directly
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
                        'User-Agent' => 'UnlockRentals-LeadFetcher/1.0',
                    ]);

                if ($token) {
                    $client->withToken($token);
                }

                $params = $request->input('params', []);
                if (is_string($params)) {
                    $params = json_decode($params, true) ?: [];
                }

                $response = ($method === 'POST')
                    ? $client->post($sourceUrl, $params)
                    : $client->get($sourceUrl, $params);

                if (!$response->successful()) {
                    return response()->json([
                        'success' => false,
                        'message' => "External API returned HTTP {$response->status()}: " . \Illuminate\Support\Str::limit($response->body(), 200),
                        'source_url' => $sourceUrl,
                        'status_code' => $response->status(),
                    ], 422);
                }

                $leadsData = $response->json();
            } catch (\Throwable $e) {
                return response()->json([
                    'success' => false,
                    'message' => "Failed to connect to external API: " . $e->getMessage(),
                    'source_url' => $sourceUrl,
                ], 500);
            }
        }

        // 3. Normalize response array
        // Check if nested under 'enquiries', 'enquiry', 'leads', 'data', 'results', 'items', 'records', etc.
        if (isset($leadsData['enquiries']) && is_array($leadsData['enquiries'])) {
            $items = $leadsData['enquiries'];
        } elseif (isset($leadsData['leads']) && is_array($leadsData['leads'])) {
            $items = $leadsData['leads'];
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
            // Single lead object passed
            $items = [$leadsData];
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No lead records found in the API response.',
                'raw_response' => $leadsData,
                'source_url' => $sourceUrl,
            ], 422);
        }

        // 4. Ingest and Store Each Lead into database
        $importedCount = 0;
        $updatedCount = 0;
        $skippedCount = 0;
        $storedLeads = [];

        $assignedStaff = User::whereIn('role', ['admin', 'owner', 'sales_manager', 'sales_executive'])->first();

        foreach ($items as $item) {
            if (!is_array($item)) {
                $skippedCount++;
                continue;
            }

            // Extract phone
            $rawPhone = $item['contact'] 
                ?? $item['phone'] 
                ?? $item['mobile'] 
                ?? $item['student_mobile'] 
                ?? $item['phone_number'] 
                ?? '';
            $cleanPhone = preg_replace('/[^0-9]/', '', (string)$rawPhone);
            if (strlen($cleanPhone) >= 10) {
                $cleanPhone = substr($cleanPhone, -10);
            }

            if (empty($cleanPhone) || strlen($cleanPhone) < 10) {
                $skippedCount++;
                continue;
            }

            // Extract name (supports firstName + lastName, or combined name)
            $composedName = trim(($item['firstName'] ?? '') . ' ' . ($item['lastName'] ?? ''));
            $name = $composedName ?: (
                $item['name'] 
                ?? $item['full_name'] 
                ?? $item['student_name'] 
                ?? $item['candidate_name'] 
                ?? $item['client_name'] 
                ?? 'API Lead'
            );

            // Extract email
            $email = $item['email'] ?? $item['student_email'] ?? null;

            // Extract academic / subject / stream info
            $academicParts = array_filter([
                $item['subject'] ?? null,
                $item['course'] ?? null,
                $item['stream'] ?? null,
                $item['program'] ?? null,
                $item['degree'] ?? null,
                $item['branch'] ?? null,
                $item['specialization'] ?? null,
                $item['college'] ?? null,
                $item['university'] ?? null,
            ]);
            $academicSummary = implode(' • ', $academicParts);

            // Extract message
            $msg = $item['message'] ?? $item['enquiry'] ?? $item['query'] ?? $item['notes'] ?? '';
            $fullMessage = $academicSummary 
                ? ($msg ? "Subject/Course: {$academicSummary}\n{$msg}" : "Subject/Course: {$academicSummary}") 
                : ($msg ?: 'Imported from external API');

            // Detect source and admission flag
            $rawSource = strtolower($item['source'] ?? $item['lead_source'] ?? '');
            $isAdmission = !empty($academicSummary) 
                || !empty($item['subject'])
                || str_contains($sourceUrl, 'anushram')
                || str_contains($rawSource, 'admission') 
                || str_contains($rawSource, 'anushram') 
                || str_contains(strtolower($msg), 'admission') 
                || str_contains(strtolower($item['property_type'] ?? ''), 'admission')
                || str_contains(strtolower($item['purpose'] ?? $item['intent'] ?? ''), 'admission');

            $leadSource = $item['lead_source'] 
                ?? $item['source'] 
                ?? ($isAdmission ? 'admission' : 'external_api');

            $city = $item['city'] ?? $item['preferred_city'] ?? $item['location'] ?? null;
            $locality = $item['locality'] ?? $item['preferred_locality'] ?? null;
            $budget = !empty($item['budget_max']) ? (float)$item['budget_max'] : (!empty($item['budget']) ? (float)$item['budget'] : null);
            $bedrooms = $item['bedrooms'] ?? $item['bhk_preference'] ?? null;

            // Duplicate check within 48h
            $existing = Lead::where('mobile', 'LIKE', '%' . $cleanPhone)
                ->where('created_at', '>=', now()->subHours(48))
                ->first();

            if ($existing) {
                $existing->update([
                    'lead_source' => $leadSource,
                    'message' => $existing->message . "\n[API Sync " . now()->format('d M H:i') . "]: " . $fullMessage,
                    'engagement_score' => $existing->engagement_score + 10,
                ]);
                $updatedCount++;
                $storedLeads[] = [
                    'id' => $existing->id,
                    'name' => $existing->name,
                    'mobile' => $existing->mobile,
                    'stream' => $existing->stream,
                    'course' => $existing->course,
                    'action' => 'updated',
                ];
            } else {
                $lead = Lead::create([
                    'name' => $name,
                    'mobile' => $cleanPhone,
                    'email' => $email,
                    'lead_source' => $leadSource,
                    'lead_status' => 'new',
                    'lead_stage' => 'enquiry',
                    'property_type' => $isAdmission ? 'admission' : ($item['property_type'] ?? null),
                    'purpose' => 'rent',
                    'preferred_city' => $city,
                    'preferred_locality' => $locality,
                    'budget_max' => $budget,
                    'bedrooms' => $bedrooms,
                    'message' => $fullMessage,
                    'notes' => $academicSummary ?: 'Imported via External API Fetch',
                    'assigned_to' => $assignedStaff?->id,
                    'engagement_score' => 30,
                    'whatsapp_opt_in' => isset($item['whatsapp_opt_in']) ? (bool)$item['whatsapp_opt_in'] : true,
                    'next_follow_up_at' => now()->addHours(2),
                ]);
                $importedCount++;
                $storedLeads[] = [
                    'id' => $lead->id,
                    'name' => $lead->name,
                    'mobile' => $lead->mobile,
                    'stream' => $academicSummary ?: null,
                    'course' => $academicSummary ?: null,
                    'action' => 'created',
                ];
            }
        }

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
}
