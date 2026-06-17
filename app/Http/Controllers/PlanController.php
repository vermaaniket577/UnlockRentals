<?php

namespace App\Http\Controllers;

use App\Mail\SubscriptionActivated;
use App\Models\ActivityLog;
use App\Models\PaymentLog;
use App\Models\Plan;
use App\Models\Property;
use App\Models\Setting;
use App\Models\UserPlan;
use App\Services\SubscriptionPaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Razorpay\Api\Api;

class PlanController extends Controller
{
    private $payments;

    public function __construct(SubscriptionPaymentService $payments)
    {
        $this->payments = $payments;
    }

    public function index()
    {
        $plans = Plan::public()->get();
        $activePlan = auth()->check() ? auth()->user()->activePlan() : null;
        $pendingPlan = auth()->check()
            ? auth()->user()->userPlans()->pending()->with('plan')->latest()->first()
            : null;

        $userOffers = collect();
        if (auth()->check()) {
            $userOffers = \App\Models\PrivateUserOffer::where('user_id', auth()->id())
                ->where('status', 'active')
                ->where(function ($q) {
                    $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
                })
                ->get()
                ->keyBy('plan_id');
        }

        $activeGateway = Setting::activePaymentGateway();
        [$razorpayKeyId] = $this->razorpayCredentials($activeGateway);
        $razorpayKeyId = ($activeGateway['type'] ?? null) === 'razorpay'
            ? $razorpayKeyId
            : null;

        return view('plans.index', compact('plans', 'activePlan', 'pendingPlan', 'userOffers', 'activeGateway', 'razorpayKeyId'));
    }

    public function purchase(Plan $plan)
    {
        $user = auth()->user();

        if ($user->hasActivePlan()) {
            $activePlan = $user->activePlan();
            if ($activePlan && $activePlan->remaining_contacts > 0 && $activePlan->plan && (float) $plan->price <= (float) $activePlan->plan->price) {
                return redirect()->back()->with('error', 'You already have an active plan of this tier or higher with remaining contact views. You can only upgrade to a higher plan.');
            }
        }

        $billing = request('billing_period', 'monthly') === 'yearly' ? 'yearly' : 'monthly';

        return redirect()->route('plans.checkout', ['plan' => $plan, 'billing' => $billing]);
    }

    public function checkout(Plan $plan)
    {
        $user = auth()->user();
        if ($user->hasActivePlan()) {
            $activePlan = $user->activePlan();
            if ($activePlan && $activePlan->remaining_contacts > 0 && $activePlan->plan && (float) $plan->price <= (float) $activePlan->plan->price) {
                return redirect()->route('plans.index')->with('error', 'You already have an active plan of this tier or higher with remaining contact views. You can only upgrade to a higher plan.');
            }
        }

        $billingPeriod = request('billing', 'monthly') === 'yearly' ? 'yearly' : 'monthly';
        [$effectivePrice] = $this->effectivePlanPrice($plan, $user);
        $billing = $this->payments->billingBreakdown($plan, (float) $effectivePrice, $billingPeriod);

        $activeGateway = Setting::activePaymentGateway();
        [$razorpayKeyId] = $this->razorpayCredentials($activeGateway);
        $razorpayKeyId = ($activeGateway['type'] ?? null) === 'razorpay'
            ? $razorpayKeyId
            : null;

        return view('plans.checkout', compact('plan', 'activeGateway', 'razorpayKeyId', 'effectivePrice', 'billing', 'billingPeriod'));
    }

    public function createRazorpayOrder(Request $request, Plan $plan): JsonResponse
    {
        $user = auth()->user();

        if ($user->hasActivePlan()) {
            $activePlan = $user->activePlan();
            if ($activePlan && $activePlan->remaining_contacts > 0 && $activePlan->plan && (float) $plan->price <= (float) $activePlan->plan->price) {
                return response()->json(['message' => 'You already have an active plan of this tier or higher with remaining contact views. You can only upgrade to a higher plan.'], 409);
            }
        }

        $activeGateway = Setting::activePaymentGateway();

        if (($activeGateway['type'] ?? null) !== 'razorpay') {
            return response()->json(['message' => 'Razorpay is not the active payment gateway.'], 422);
        }

        [$razorpayKeyId, $razorpayKeySecret] = $this->razorpayCredentials($activeGateway);

        if (!$razorpayKeyId || !$razorpayKeySecret) {
            return response()->json(['message' => 'Razorpay credentials are not configured.'], 401);
        }

        $data = $request->validate([
            'billing_period' => ['nullable', 'in:monthly,yearly'],
        ]);

        [$effectivePrice] = $this->effectivePlanPrice($plan, $user);
        $billingPeriod = ($data['billing_period'] ?? 'monthly') === 'yearly' ? 'yearly' : 'monthly';
        $billing = $this->payments->billingBreakdown($plan, (float) $effectivePrice, $billingPeriod);
        $receipt = 'UR' . $user->id . 'P' . $plan->id . now()->format('His');

        $amountPaise = max(100, (int) round($billing['final'] * 100));
        \Illuminate\Support\Facades\Log::info('Razorpay order creation', [
            'plan_id' => $plan->id,
            'plan_price' => $plan->price,
            'effective_price' => $effectivePrice,
            'billing_period' => $billingPeriod,
            'billing_final' => $billing['final'],
            'amount_paise' => $amountPaise,
        ]);

        try {
            if (app()->environment('testing')) {
                $order = [
                    'id' => 'order_test_123',
                    'amount' => max(100, (int) round($billing['final'] * 100)),
                    'currency' => 'INR',
                ];
            } else {
                // Fix SSL connection timeout on Windows/XAMPP:
                // Set the CA cert path for the rmccue/requests library
                $caBundle = ini_get('curl.cainfo');
                if ($caBundle && file_exists($caBundle)) {
                    \WpOrg\Requests\Requests::set_certificate_path($caBundle);
                }

                $api = new Api($razorpayKeyId, $razorpayKeySecret);
                $order = $this->createRazorpayOrderWithCurlFix($api, [
                    'amount' => max(100, (int) round($billing['final'] * 100)),
                    'currency' => 'INR',
                    'receipt' => Str::limit($receipt, 40, ''),
                    'notes' => [
                        'user_id' => (string) $user->id,
                        'plan_id' => (string) $plan->id,
                        'billing_period' => $billingPeriod,
                    ],
                ]);
            }

            return response()->json([
                'order_id' => $order['id'],
                'amount' => $order['amount'],
                'currency' => $order['currency'],
                'key_id' => $razorpayKeyId,
                'plan_name' => $plan->name,
                'billing_period' => $billingPeriod,
            ]);
        } catch (\Razorpay\Api\Errors\BadRequestError $e) {
            report($e);

            return response()->json([
                'message' => $this->payments->categorizeFailure($e->getMessage()),
            ], 400);
        } catch (\Throwable $e) {
            report($e);
            $isAuthError = str_contains(strtolower($e->getMessage()), 'authentication');

            return response()->json([
                'message' => $this->payments->categorizeFailure(
                    $isAuthError
                        ? 'Payment gateway authentication failed. Please contact support.'
                        : 'Network issue while creating payment order.'
                ),
            ], $isAuthError ? 401 : 500);
        }
    }

    public function processPayment(Request $request, Plan $plan)
    {
        $user = auth()->user();
        $activeGateway = Setting::activePaymentGateway();

        if (!$activeGateway) {
            return $this->paymentFailureRedirect($plan, 'monthly', 'Payment gateway is currently not configured.');
        }

        $request->validate([
            'billing_period' => ['nullable', 'in:monthly,yearly'],
            'payment_method' => ['nullable', 'in:upi,phonepe,paytm,razorpay,card,netbanking,wallet,qr'],
            'auto_renew' => ['nullable', 'boolean'],
        ]);

        [$effectivePrice] = $this->effectivePlanPrice($plan, $user);
        $billingPeriod = $request->input('billing_period', 'monthly') === 'yearly' ? 'yearly' : 'monthly';
        $billing = $this->payments->billingBreakdown($plan, (float) $effectivePrice, $billingPeriod);
        $paymentMethod = $request->input('payment_method', ($activeGateway['type'] ?? 'manual') === 'razorpay' ? 'razorpay' : 'upi');
        $invoiceId = $this->payments->generateInvoiceId();

        if (($activeGateway['type'] ?? 'manual') !== 'razorpay') {
            return $this->processManualPayment($request, $plan, $user, $activeGateway, $billing, $billingPeriod, $paymentMethod, $invoiceId);
        }

        [$razorpayKeyId, $razorpayKeySecret] = $this->razorpayCredentials($activeGateway);

        if (!$request->has('razorpay_payment_id') || !$razorpayKeyId || !$razorpayKeySecret) {
            return $this->paymentFailureRedirect(
                $plan,
                $billingPeriod,
                'Payment was not processed correctly. If payment was deducted, contact support with your transaction ID.'
            );
        }

        $reference = $request->razorpay_payment_id;

        // Check for duplicate activation
        $existingSubscription = $this->payments->findApprovedByTransaction($reference);
        if ($existingSubscription) {
            return redirect()->route('dashboard')
                ->with('success', 'This payment was already verified and your plan is active.')
                ->with('subscription_success', $this->payments->successPayload(
                    $existingSubscription,
                    $existingSubscription->invoice_id ?? $invoiceId
                ));
        }

        // ── FLOW A: Standard Razorpay handler (signature present) ──
        $hasSignature = $request->filled('razorpay_signature') && $request->filled('razorpay_order_id');

        if ($hasSignature) {
            try {
                $this->payments->verifyRazorpaySignature(
                    $request->razorpay_order_id,
                    $reference,
                    $request->razorpay_signature,
                    $razorpayKeyId,
                    $razorpayKeySecret
                );
            } catch (\Exception $e) {
                $this->payments->rollbackFailedAttempt($user, $plan, $reference);
                $this->payments->logPayment($user, $billing['final'], $paymentMethod, 'failed', $reference, [
                    'invoice_id' => $invoiceId,
                    'error' => $e->getMessage(),
                    'reason' => $this->payments->categorizeFailure($e->getMessage()),
                ]);

                return $this->paymentFailureRedirect(
                    $plan,
                    $billingPeriod,
                    $this->payments->categorizeFailure($e->getMessage())
                );
            }
        } else {
            // ── FLOW B: Direct API verification (no signature — fallback/polling) ──
            try {
                $paymentData = $this->fetchRazorpayPaymentDirect($reference, $razorpayKeyId, $razorpayKeySecret);

                // Auto-capture if payment is authorized but not yet captured
                if (($paymentData['status'] ?? '') === 'authorized') {
                    $expectedAmountPaise = max(100, (int) round($billing['final'] * 100));
                    $paymentData = $this->captureRazorpayPaymentDirect(
                        $reference, $expectedAmountPaise, 'INR', $razorpayKeyId, $razorpayKeySecret
                    );
                }

                $paymentStatus = $paymentData['status'] ?? 'unknown';
                if ($paymentStatus !== 'captured') {
                    $this->payments->logPayment($user, $billing['final'], $paymentMethod, 'failed', $reference, [
                        'invoice_id' => $invoiceId,
                        'error' => "Payment status is '{$paymentStatus}', not captured.",
                    ]);
                    return $this->paymentFailureRedirect(
                        $plan,
                        $billingPeriod,
                        "Payment is not yet completed (status: {$paymentStatus}). Please wait a moment and try again."
                    );
                }

                // Validate amount matches
                $paidAmountPaise = (int) ($paymentData['amount'] ?? 0);
                $expectedAmountPaise = max(100, (int) round($billing['final'] * 100));
                if (abs($paidAmountPaise - $expectedAmountPaise) > 100) {
                    $this->payments->logPayment($user, $billing['final'], $paymentMethod, 'failed', $reference, [
                        'invoice_id' => $invoiceId,
                        'error' => "Amount mismatch. Paid: {$paidAmountPaise}, Expected: {$expectedAmountPaise}",
                    ]);
                    return $this->paymentFailureRedirect(
                        $plan,
                        $billingPeriod,
                        'Payment amount does not match the expected plan amount. Please contact support.'
                    );
                }

                // Validate user ownership if notes contain user_id
                $noteUserId = $paymentData['notes']['user_id'] ?? null;
                if ($noteUserId && (string) $noteUserId !== (string) $user->id) {
                    $this->payments->logPayment($user, $billing['final'], $paymentMethod, 'failed', $reference, [
                        'invoice_id' => $invoiceId,
                        'error' => "User mismatch. Payment user: {$noteUserId}, Current user: {$user->id}",
                    ]);
                    return $this->paymentFailureRedirect(
                        $plan,
                        $billingPeriod,
                        'This payment belongs to a different account. Please contact support.'
                    );
                }
            } catch (\Throwable $e) {
                report($e);
                $this->payments->logPayment($user, $billing['final'], $paymentMethod, 'failed', $reference, [
                    'invoice_id' => $invoiceId,
                    'error' => 'Direct API verification failed: ' . $e->getMessage(),
                ]);
                return $this->paymentFailureRedirect(
                    $plan,
                    $billingPeriod,
                    'Could not verify payment with Razorpay. Please ensure the Payment ID is correct or contact support.'
                );
            }
        }

        // ── Activate the subscription ──
        try {
            $userPlan = $this->payments->activateSubscription(
                $user,
                $plan,
                $billing,
                $billingPeriod,
                $paymentMethod,
                $reference,
                $invoiceId,
                $request,
                $request->boolean('auto_renew')
            );
        } catch (\Throwable $e) {
            report($e);
            $this->payments->rollbackFailedAttempt($user, $plan, $reference);
            $this->payments->logPayment($user, $billing['final'], $paymentMethod, 'failed', $reference, [
                'invoice_id' => $invoiceId,
                'error' => 'Database transaction failed: ' . $e->getMessage(),
            ]);

            return $this->paymentFailureRedirect(
                $plan,
                $billingPeriod,
                'Activation failed after payment verification: ' . $e->getMessage()
            );
        }

        try {
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new SubscriptionActivated($userPlan));
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect()->route('dashboard')
            ->with('success', "Payment successful. Your \"{$plan->name}\" plan has been activated automatically.")
            ->with('subscription_success', $this->payments->successPayload($userPlan, $invoiceId));
    }

    /**
     * AJAX endpoint: Poll order payment status from Razorpay.
     * Called by the client-side JS every few seconds after the modal opens.
     */
    public function checkOrderStatus(Request $request, Plan $plan): JsonResponse
    {
        $request->validate([
            'order_id' => ['required', 'string', 'starts_with:order_'],
        ]);

        $activeGateway = Setting::activePaymentGateway();
        [$razorpayKeyId, $razorpayKeySecret] = $this->razorpayCredentials($activeGateway);

        if (!$razorpayKeyId || !$razorpayKeySecret) {
            return response()->json(['status' => 'error', 'message' => 'Credentials missing'], 500);
        }

        try {
            $payments = $this->fetchOrderPaymentsDirect(
                $request->order_id, $razorpayKeyId, $razorpayKeySecret
            );

            // Find any captured or authorized payment
            foreach (($payments['items'] ?? []) as $payment) {
                if (in_array($payment['status'] ?? '', ['captured', 'authorized'])) {
                    return response()->json([
                        'status' => 'paid',
                        'payment_id' => $payment['id'],
                        'order_id' => $request->order_id,
                        'amount' => $payment['amount'],
                    ]);
                }
            }

            return response()->json(['status' => 'pending']);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Order status check failed', ['error' => $e->getMessage()]);
            return response()->json(['status' => 'pending']);
        }
    }

    public function unlockContact(Property $property)
    {
        $user = auth()->user();

        if (!$user->canViewContact($property)) {
            return redirect()->route('plans.index')
                ->with('error', 'You need an active plan to view owner contacts. Please purchase a plan.');
        }

        $user->viewContact($property);

        return redirect()->route('properties.show', $property)
            ->with('success', 'Contact details unlocked!');
    }

    private function processManualPayment(
        Request $request,
        Plan $plan,
        $user,
        array $activeGateway,
        array $billing,
        string $billingPeriod,
        string $paymentMethod,
        string $invoiceId
    ) {
        $data = $request->validate([
            'payment_reference' => ['required', 'string', 'max:255'],
            'amount_paid' => ['required', 'numeric', 'min:0'],
            'payment_proof' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:5120'],
        ]);

        $proofPath = $request->hasFile('payment_proof')
            ? $request->file('payment_proof')->store('payment_proofs', 'public')
            : null;

        UserPlan::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'status' => 'pending',
            'payment_reference' => '[' . ($activeGateway['name'] ?? 'Gateway') . '] ' . $data['payment_reference'],
            'amount_paid' => $data['amount_paid'],
            'transaction_id' => $data['payment_reference'],
            'payment_proof' => $proofPath,
            'invoice_id' => $invoiceId,
            'billing_period' => $billingPeriod,
            'subtotal_amount' => $billing['subtotal'],
            'discount_amount' => $billing['discount'],
            'gst_amount' => $billing['gst'],
            'final_amount' => $billing['final'],
            'payment_method' => $paymentMethod,
            'auto_renew' => $request->boolean('auto_renew'),
        ]);

        PaymentLog::create([
            'user_id' => $user->id,
            'amount' => $data['amount_paid'],
            'method' => $paymentMethod,
            'status' => 'pending',
            'transaction_id' => $data['payment_reference'],
            'details' => [
                'invoice_id' => $invoiceId,
                'plan_id' => $plan->id,
                'billing_period' => $billingPeriod,
                'gateway' => $activeGateway['name'] ?? 'Manual gateway',
            ],
        ]);

        ActivityLog::create([
            'admin_id' => $user->id,
            'action' => 'subscription_payment_submitted',
            'description' => "{$user->name} submitted payment for {$plan->name} ({$invoiceId}).",
            'ip_address' => $request->ip(),
        ]);

        if ($plan->is_private) {
            \App\Models\PrivateUserOffer::where('user_id', $user->id)
                ->where('plan_id', $plan->id)
                ->where('status', 'active')
                ->update(['status' => 'claimed']);
        }

        return redirect()->route('plans.index')
            ->with('success', 'Payment submitted successfully. Admin will verify the proof and activate your plan.');
    }

    private function paymentFailureRedirect(Plan $plan, string $billingPeriod, string $reason)
    {
        session([
            'payment_failed_reason' => $this->payments->categorizeFailure($reason),
            'payment_retry_checkout' => route('plans.checkout', ['plan' => $plan, 'billing' => $billingPeriod]),
        ]);

        return redirect()->route('plans.index')
            ->with('error', session('payment_failed_reason'))
            ->with('payment_failed_reason', session('payment_failed_reason'));
    }

    private function effectivePlanPrice(Plan $plan, $user): array
    {
        $effectivePrice = $plan->price;
        $privateOffer = \App\Models\PrivateUserOffer::where('user_id', $user->id)
            ->where('plan_id', $plan->id)
            ->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })->first();

        if ($plan->is_private && !$privateOffer) {
            abort(403, 'You are not authorized to purchase this exclusive offer.');
        }

        if ($privateOffer) {
            $effectivePrice = $privateOffer->effective_price;
        }

        return [(float) $effectivePrice, $privateOffer];
    }

    private function razorpayCredentials(?array $gateway): array
    {
        return [
            !empty($gateway['key_id']) ? $gateway['key_id'] : config('services.razorpay.key_id'),
            !empty($gateway['key_secret']) ? $gateway['key_secret'] : config('services.razorpay.key_secret'),
        ];
    }

    /**
     * Create a Razorpay order with curl options patched to fix
     * SSL connection timeouts on Windows/XAMPP environments.
     *
     * Root causes addressed:
     * - IPv6 resolution hangs (CURLOPT_IPRESOLVE → IPv4 only)
     * - SDK forces TLS 1.1 which some endpoints reject (→ TLS 1.2)
     * - Explicit CA bundle path for rmccue/requests transport
     */
    private function createRazorpayOrderWithCurlFix(Api $api, array $orderData): array
    {
        \Razorpay\Api\Request::addHeader('Connection', 'close');

        // On Windows or Local/Testing environment, try direct curl first to avoid the SDK's 60-second connection timeout hangs.
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' || app()->environment('local', 'testing')) {
            try {
                \Illuminate\Support\Facades\Log::info('Local/Windows environment detected: trying direct curl first for Razorpay order.');
                return $this->createRazorpayOrderDirectCurl($api, $orderData);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Direct curl failed, falling back to SDK', ['error' => $e->getMessage()]);
            }
        }

        // Try with the SDK first (benefits from set_certificate_path).
        // If it fails with SSL/timeout, fall back to direct curl which
        // forces IPv4 + TLS 1.2 to bypass the SDK's problematic defaults.
        try {
            $order = $api->order->create($orderData);
            return $order->toArray();
        } catch (\WpOrg\Requests\Exception $e) {
            if (str_contains($e->getMessage(), 'cURL error 28') ||
                str_contains($e->getMessage(), 'SSL')) {
                \Illuminate\Support\Facades\Log::warning('Razorpay SDK failed with SSL/timeout, retrying with direct curl', [
                    'error' => $e->getMessage(),
                ]);
                return $this->createRazorpayOrderDirectCurl($api, $orderData);
            }
            throw $e;
        }
    }

    /**
     * Fallback: Create Razorpay order using direct curl instead of the SDK's
     * rmccue/requests transport. This bypasses all SDK curl quirks.
     */
    private function createRazorpayOrderDirectCurl(Api $api, array $orderData): array
    {
        $ch = curl_init('https://api.razorpay.com/v1/orders');
        
        $keyId = Api::getKey();
        $keySecret = Api::getSecret();

        $options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($orderData),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
            ],
            CURLOPT_USERPWD => $keyId . ':' . $keySecret,
            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
            CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => 10,
        ];

        // Disable SSL verification on local/testing environment to prevent certificate verification hangs
        if (app()->environment('local', 'testing')) {
            $options[CURLOPT_SSL_VERIFYPEER] = false;
            $options[CURLOPT_SSL_VERIFYHOST] = false;
        }

        curl_setopt_array($ch, $options);

        $caBundle = ini_get('curl.cainfo');
        if ($caBundle && file_exists($caBundle) && !app()->environment('local', 'testing')) {
            curl_setopt($ch, CURLOPT_CAINFO, $caBundle);
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new \RuntimeException("Razorpay API curl error: $error");
        }

        $body = json_decode($response, true);

        if ($httpCode >= 400) {
            $msg = $body['error']['description'] ?? 'Unknown Razorpay error';
            throw new \Razorpay\Api\Errors\BadRequestError($msg, $body['error']['code'] ?? 'BAD_REQUEST_ERROR', $httpCode);
        }

        return $body;
    }

    /**
     * Fetch a single Razorpay payment by ID using direct curl.
     */
    private function fetchRazorpayPaymentDirect(string $paymentId, string $keyId, string $keySecret): array
    {
        $url = 'https://api.razorpay.com/v1/payments/' . urlencode($paymentId);
        return $this->razorpayCurlGet($url, $keyId, $keySecret);
    }

    /**
     * Capture an authorized Razorpay payment using direct curl.
     */
    private function captureRazorpayPaymentDirect(
        string $paymentId, int $amountPaise, string $currency, string $keyId, string $keySecret
    ): array {
        $url = 'https://api.razorpay.com/v1/payments/' . urlencode($paymentId) . '/capture';
        return $this->razorpayCurlPost($url, ['amount' => $amountPaise, 'currency' => $currency], $keyId, $keySecret);
    }

    /**
     * Fetch all payments for a Razorpay order using direct curl.
     */
    private function fetchOrderPaymentsDirect(string $orderId, string $keyId, string $keySecret): array
    {
        $url = 'https://api.razorpay.com/v1/orders/' . urlencode($orderId) . '/payments';
        return $this->razorpayCurlGet($url, $keyId, $keySecret);
    }

    /**
     * Shared helper: curl GET to Razorpay API with basic auth.
     */
    private function razorpayCurlGet(string $url, string $keyId, string $keySecret): array
    {
        $ch = curl_init($url);
        $options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPGET => true,
            CURLOPT_USERPWD => $keyId . ':' . $keySecret,
            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
            CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_HTTPHEADER => ['Accept: application/json'],
        ];
        if (app()->environment('local', 'testing')) {
            $options[CURLOPT_SSL_VERIFYPEER] = false;
            $options[CURLOPT_SSL_VERIFYHOST] = false;
        }
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new \RuntimeException("Razorpay API curl error: $error");
        }
        $body = json_decode($response, true) ?? [];
        if ($httpCode >= 400) {
            throw new \RuntimeException($body['error']['description'] ?? "Razorpay API error (HTTP {$httpCode})");
        }
        return $body;
    }

    /**
     * Shared helper: curl POST to Razorpay API with basic auth.
     */
    private function razorpayCurlPost(string $url, array $data, string $keyId, string $keySecret): array
    {
        $ch = curl_init($url);
        $options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_USERPWD => $keyId . ':' . $keySecret,
            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
            CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'Accept: application/json'],
        ];
        if (app()->environment('local', 'testing')) {
            $options[CURLOPT_SSL_VERIFYPEER] = false;
            $options[CURLOPT_SSL_VERIFYHOST] = false;
        }
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new \RuntimeException("Razorpay API curl error: $error");
        }
        $body = json_decode($response, true) ?? [];
        if ($httpCode >= 400) {
            throw new \RuntimeException($body['error']['description'] ?? "Razorpay API error (HTTP {$httpCode})");
        }
        return $body;
    }
}
