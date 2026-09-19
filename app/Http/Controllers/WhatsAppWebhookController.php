<?php

namespace App\Http\Controllers;

use App\Models\CommunicationLog;
use App\Models\ConsentRecord;
use App\Models\Lead;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class WhatsAppWebhookController extends Controller
{
    /**
     * Verify Meta WhatsApp webhook challenge.
     * GET /api/whatsapp/webhook
     */
    public function verify(Request $request): Response|JsonResponse
    {
        $mode = $request->query('hub_mode');
        $token = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

        $expectedToken = Setting::get('whatsapp_webhook_verify_token', env('WHATSAPP_WEBHOOK_VERIFY_TOKEN', 'UnlockRentalsWhatsAppWebhook2026'));

        if ($mode === 'subscribe' && $token === $expectedToken) {
            return response($challenge, 200)->header('Content-Type', 'text/plain');
        }

        return response()->json(['error' => 'Verification failed'], 403);
    }

    /**
     * Process WhatsApp status updates & inbound messages.
     * POST /api/whatsapp/webhook
     */
    public function handle(Request $request): JsonResponse
    {
        $payload = $request->all();

        // 1. Process Message Status Updates (delivered, read, failed)
        if (isset($payload['entry'][0]['changes'][0]['value']['statuses'])) {
            foreach ($payload['entry'][0]['changes'][0]['value']['statuses'] as $statusUpdate) {
                $messageId = $statusUpdate['id'] ?? null;
                $status = $statusUpdate['status'] ?? null; // delivered, read, failed

                if ($messageId && $status) {
                    $log = CommunicationLog::where('provider_message_id', $messageId)->first();
                    if ($log) {
                        $updateData = ['status' => $status];
                        if ($status === 'delivered' && empty($log->delivered_at)) {
                            $updateData['delivered_at'] = now();
                        } elseif ($status === 'read' && empty($log->read_at)) {
                            $updateData['read_at'] = now();
                        }
                        $log->update($updateData);
                    }
                }
            }
        }

        // 2. Process Inbound Messages (Opt-out handling)
        if (isset($payload['entry'][0]['changes'][0]['value']['messages'])) {
            foreach ($payload['entry'][0]['changes'][0]['value']['messages'] as $inbound) {
                $from = $inbound['from'] ?? null;
                $text = trim($inbound['text']['body'] ?? '');

                if ($from && !empty($text)) {
                    $cleanFrom = substr(preg_replace('/[^0-9]/', '', $from), -10);
                    $lead = Lead::where('mobile', 'LIKE', '%' . $cleanFrom)->latest()->first();

                    if ($lead) {
                        // Check for Opt-Out Keywords
                        if (in_array(strtoupper($text), ['STOP', 'UNSUBSCRIBE', 'OPT OUT', 'CANCEL'])) {
                            $lead->update([
                                'whatsapp_opt_in' => false,
                                'marketing_opt_in' => false,
                            ]);

                            ConsentRecord::create([
                                'lead_id' => $lead->id,
                                'visitor_id' => $lead->visitor_id,
                                'user_id' => $lead->user_id,
                                'consent_type' => 'whatsapp_updates',
                                'is_granted' => false,
                                'consent_text' => "User sent opt-out keyword: {$text}",
                                'form_source' => 'whatsapp_inbound',
                                'withdrawn_at' => now(),
                            ]);

                            Log::info("[WhatsApp CRM] Lead #{$lead->id} opted out of WhatsApp updates.");
                        }

                        // Record Inbound Message in Communication Log
                        CommunicationLog::create([
                            'lead_id' => $lead->id,
                            'type' => 'incoming',
                            'channel' => 'whatsapp',
                            'message' => $text,
                            'status' => 'read',
                            'provider_message_id' => $inbound['id'] ?? null,
                            'sent_at' => now(),
                            'read_at' => now(),
                        ]);
                    }
                }
            }
        }

        return response()->json(['status' => 'received']);
    }
}
