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
        Plan::ensureBuyerPlansExist();
        $plans = Plan::public()->get();
        $user = auth()->user();
        $activePlan = $user ? $user->activePlan() : null;
        $activeRentPlan = $user ? $user->activeRentPlan() : null;
        $activeBuyPlan = $user ? $user->activeBuyPlan() : null;
        $pendingPlan = $user
            ? $user->userPlans()->pending()->with('plan')->latest()->first()
            : null;

        $userOffers = collect();
        if ($user) {
            $userOffers = \App\Models\PrivateUserOffer::where('user_id', $user->id)
                ->where('status', 'active')
                ->where(function ($q) {
                    $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
                })
                ->get();
        }

        $activeGateway = Setting::activePaymentGateway();
        [$razorpayKeyId] = $this->razorpayCredentials($activeGateway);
        $razorpayKeyId = ($activeGateway['type'] ?? null) === 'razorpay'
            ? $razorpayKeyId
            : null;

        return view('plans.index', compact('plans', 'activePlan', 'activeRentPlan', 'activeBuyPlan', 'pendingPlan', 'userOffers', 'activeGateway', 'razorpayKeyId'));
    }

    public function purchase(Plan $plan)
    {
        $user = auth()->user();
        $targetCategory = $plan->isBuyPlan() ? 'buy' : 'rent';
        $categoryName = $plan->isBuyPlan() ? 'Buyer Pass' : 'Rental Plan';

        $activePlan = $user->activePlan($targetCategory);
        if ($activePlan && $activePlan->remaining_contacts > 0 && $activePlan->plan && (float) $plan->price <= (float) $activePlan->plan->price) {
            return redirect()->back()->with('error', "You already have an active {$categoryName} of this tier or higher with remaining contact views. You can only upgrade to a higher plan.");
        }

        $billing = request('billing_period', 'monthly') === 'yearly' ? 'yearly' : 'monthly';

        return redirect()->route('plans.checkout', ['plan' => $plan, 'billing' => $billing]);
    }

    public function checkout(Plan $plan)
    {
        $user = auth()->user();
        $targetCategory = $plan->isBuyPlan() ? 'buy' : 'rent';
        $categoryName = $plan->isBuyPlan() ? 'Buyer Pass' : 'Rental Plan';

        $activePlan = $user->activePlan($targetCategory);
        if ($activePlan && $activePlan->remaining_contacts > 0 && $activePlan->plan && (float) $plan->price <= (float) $activePlan->plan->price) {
            return redirect()->route('plans.index', ['billing' => $plan->isBuyPlan() ? 'yearly' : 'monthly'])
                ->with('error', "You already have an active {$categoryName} of this tier or higher with remaining contact views. You can only upgrade to a higher plan.");
        }

        if (empty($user->phone)) {
            $fallbackPhone = request('phone') 
                ?? session('user_phone') 
                ?? $user->inquiries()->whereNotNull('phone')->where('phone', '!=', '')->latest()->value('phone');
            if (!empty($fallbackPhone)) {
                $user->phone = \App\Models\User::sanitizePhone($fallbackPhone);
                $user->save();
            }
        }

        $billingPeriod = request('billing', 'monthly') === 'yearly' ? 'yearly' : 'monthly';
        [$effectivePrice, $privateOffer] = $this->effectivePlanPrice($plan, $user, $billingPeriod);
        $billing = $this->payments->billingBreakdown($plan, (float) $effectivePrice, $billingPeriod, $privateOffer);

        $activeGateway = Setting::activePaymentGateway();
        [$razorpayKeyId] = $this->razorpayCredentials($activeGateway);
        $razorpayKeyId = ($activeGateway['type'] ?? null) === 'razorpay'
            ? $razorpayKeyId
            : null;

        $userCleanPhone = $user->clean_phone;
        $userFormattedPhone = $user->formatted_phone;

        return view('plans.checkout', compact('plan', 'activeGateway', 'razorpayKeyId', 'effectivePrice', 'billing', 'billingPeriod', 'userCleanPhone', 'userFormattedPhone'));
    }

    public function createRazorpayOrder(Request $request, Plan $plan): JsonResponse
    {
        $user = auth()->user();
        $targetCategory = $plan->isBuyPlan() ? 'buy' : 'rent';
        $categoryName = $plan->isBuyPlan() ? 'Buyer Pass' : 'Rental Plan';

        $activePlan = $user->activePlan($targetCategory);
        if ($activePlan && $activePlan->remaining_contacts > 0 && $activePlan->plan && (float) $plan->price <= (float) $activePlan->plan->price) {
            return response()->json(['message' => "You already have an active {$categoryName} of this tier or higher with remaining contact views. You can only upgrade to a higher plan."], 409);
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
            'phone' => ['nullable', 'string', 'max:25'],
        ]);

        // Auto-sync / save phone number to profile if provided and user doesn't have it or updated it
        if (!empty($data['phone'])) {
            $digits = preg_replace('/[^0-9]/', '', $data['phone']);
            if (strlen($digits) >= 10) {
                $sanitized = \App\Models\User::sanitizePhone($data['phone']);
                if ($user->phone !== $sanitized) {
                    $user->phone = $sanitized;
                    $user->save();
                }
            }
        } elseif (empty($user->phone)) {
            $fallbackPhone = session('user_phone') 
                ?? $user->inquiries()->whereNotNull('phone')->where('phone', '!=', '')->latest()->value('phone');
            if (!empty($fallbackPhone)) {
                $user->phone = \App\Models\User::sanitizePhone($fallbackPhone);
                $user->save();
            }
        }

        $billingPeriod = ($data['billing_period'] ?? 'monthly') === 'yearly' ? 'yearly' : 'monthly';
        [$effectivePrice, $privateOffer] = $this->effectivePlanPrice($plan, $user, $billingPeriod);
        $billing = $this->payments->billingBreakdown($plan, (float) $effectivePrice, $billingPeriod, $privateOffer);
        $receipt = 'UR' . $user->id . 'P' . $plan->id . now()->format('His');

        // Use integer paise from billing to avoid floating point precision loss
        $amountPaise = $billing['final_paise'] ?? max(100, (int) round($billing['final'] * 100));
        \Illuminate\Support\Facades\Log::info('Razorpay order creation', [
            'plan_id' => $plan->id,
            'plan_price' => $plan->price,
            'effective_price' => $effectivePrice,
            'billing_period' => $billingPeriod,
            'billing_final' => $billing['final'],
            'billing_final_paise' => $billing['final_paise'] ?? 'N/A',
            'amount_paise' => $amountPaise,
        ]);

        try {
            if (app()->environment('testing')) {
                $order = [
                    'id' => 'order_test_123',
                    'amount' => $amountPaise,
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
                    'amount' => $amountPaise,
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
                'user_name' => $user->name,
                'user_email' => $user->email,
                'user_phone' => $user->clean_phone ?? '',
                'user_phone_formatted' => $user->formatted_phone ?? '',
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

        $billingPeriod = $request->input('billing_period', 'monthly') === 'yearly' ? 'yearly' : 'monthly';
        [$effectivePrice, $privateOffer] = $this->effectivePlanPrice($plan, $user, $billingPeriod);
        $billing = $this->payments->billingBreakdown($plan, (float) $effectivePrice, $billingPeriod, $privateOffer);
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
                    $expectedAmountPaise = $billing['final_paise'] ?? max(100, (int) round($billing['final'] * 100));
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
                $expectedAmountPaise = $billing['final_paise'] ?? max(100, (int) round($billing['final'] * 100));
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
     * Automatically activates the plan on the server as soon as payment is confirmed,
     * guaranteeing instant activation even if mobile app or browser backgrounded.
     */
    public function checkOrderStatus(Request $request, Plan $plan): JsonResponse
    {
        $request->validate([
            'order_id' => ['required', 'string', 'starts_with:order_'],
            'billing_period' => ['nullable', 'in:monthly,yearly'],
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
                $status = $payment['status'] ?? '';
                if (in_array($status, ['captured', 'authorized'])) {
                    $paymentId = $payment['id'];

                    // Auto-capture if authorized
                    if ($status === 'authorized') {
                        try {
                            $payment = $this->captureRazorpayPaymentDirect(
                                $paymentId, (int) $payment['amount'], 'INR', $razorpayKeyId, $razorpayKeySecret
                            );
                        } catch (\Throwable $captureEx) {
                            \Illuminate\Support\Facades\Log::warning('Auto-capture in checkOrderStatus failed', ['error' => $captureEx->getMessage()]);
                        }
                    }

                    // Check if already approved/activated
                    $existingSubscription = $this->payments->findApprovedByTransaction($paymentId);
                    if ($existingSubscription) {
                        $successPayload = $this->payments->successPayload($existingSubscription, $existingSubscription->invoice_id ?? 'INV-APPROVED');
                        session([
                            'subscription_success' => $successPayload,
                            'success' => "Payment verified. Your \"{$plan->name}\" plan is active.",
                        ]);

                        return response()->json([
                            'status' => 'paid',
                            'activated' => true,
                            'payment_id' => $paymentId,
                            'order_id' => $request->order_id,
                            'amount' => $payment['amount'],
                            'redirect_url' => route('dashboard'),
                            'subscription_success' => $successPayload,
                        ]);
                    }

                    // Activate immediately on server
                    $user = auth()->user();
                    if (!$user && isset($payment['notes']['user_id'])) {
                        $user = \App\Models\User::find($payment['notes']['user_id']);
                        if ($user) {
                            auth()->login($user);
                        }
                    }

                    if ($user) {
                        $billingPeriod = ($payment['notes']['billing_period'] ?? $request->input('billing_period', 'monthly')) === 'yearly' ? 'yearly' : 'monthly';
                        [$effectivePrice, $privateOffer] = $this->effectivePlanPrice($plan, $user, $billingPeriod);
                        $billing = $this->payments->billingBreakdown($plan, (float) $effectivePrice, $billingPeriod, $privateOffer);
                        $invoiceId = $this->payments->generateInvoiceId();

                        $userPlan = $this->payments->activateSubscription(
                            $user,
                            $plan,
                            $billing,
                            $billingPeriod,
                            'razorpay',
                            $paymentId,
                            $invoiceId,
                            $request,
                            false
                        );

                        try {
                            \Illuminate\Support\Facades\Mail::to($user->email)->send(new SubscriptionActivated($userPlan));
                        } catch (\Throwable $mailEx) {
                            report($mailEx);
                        }

                        $successPayload = $this->payments->successPayload($userPlan, $invoiceId);
                        session([
                            'subscription_success' => $successPayload,
                            'success' => "Payment successful. Your \"{$plan->name}\" plan has been activated automatically.",
                        ]);

                        return response()->json([
                            'status' => 'paid',
                            'activated' => true,
                            'payment_id' => $paymentId,
                            'order_id' => $request->order_id,
                            'amount' => $payment['amount'],
                            'redirect_url' => route('dashboard'),
                            'subscription_success' => $successPayload,
                            'message' => 'Payment verified and plan activated successfully!',
                        ]);
                    }

                    return response()->json([
                        'status' => 'paid',
                        'payment_id' => $paymentId,
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

    /**
     * Direct Razorpay Callback: Handles mobile 3DS redirects, UPI callbacks, and browser redirects.
     */
    public function razorpayCallback(Request $request, Plan $plan)
    {
        $activeGateway = Setting::activePaymentGateway();
        [$razorpayKeyId, $razorpayKeySecret] = $this->razorpayCredentials($activeGateway);

        $paymentId = $request->input('razorpay_payment_id');
        $orderId = $request->input('razorpay_order_id');
        $signature = $request->input('razorpay_signature');

        if (!$paymentId) {
            return redirect()->route('plans.index')->with('error', 'Payment was cancelled or not completed.');
        }

        $user = auth()->user();

        // If session was lost on cross-site redirect, resolve user from Razorpay order notes
        if (!$user && $orderId && $razorpayKeyId && $razorpayKeySecret) {
            try {
                $orderData = $this->fetchRazorpayOrderDirect($orderId, $razorpayKeyId, $razorpayKeySecret);
                $userId = $orderData['notes']['user_id'] ?? null;
                if ($userId) {
                    $user = \App\Models\User::find($userId);
                    if ($user) {
                        auth()->login($user);
                    }
                }
            } catch (\Throwable $e) {
                report($e);
            }
        }

        if (!$user) {
            return redirect()->route('login')->with('error', 'Please sign in to complete your plan activation.');
        }

        // Check if already activated
        $existing = $this->payments->findApprovedByTransaction($paymentId);
        if ($existing) {
            return redirect()->route('dashboard')
                ->with('success', 'Your plan is active.')
                ->with('subscription_success', $this->payments->successPayload($existing, $existing->invoice_id ?? 'INV-APPROVED'));
        }

        $verified = false;
        if ($signature && $orderId) {
            try {
                $this->payments->verifyRazorpaySignature($orderId, $paymentId, $signature, $razorpayKeyId, $razorpayKeySecret);
                $verified = true;
            } catch (\Exception $e) {
                report($e);
            }
        }

        if (!$verified && $razorpayKeyId && $razorpayKeySecret) {
            try {
                $paymentData = $this->fetchRazorpayPaymentDirect($paymentId, $razorpayKeyId, $razorpayKeySecret);
                if (($paymentData['status'] ?? '') === 'authorized') {
                    $paymentData = $this->captureRazorpayPaymentDirect($paymentId, (int) $paymentData['amount'], 'INR', $razorpayKeyId, $razorpayKeySecret);
                }
                if (($paymentData['status'] ?? '') === 'captured') {
                    $verified = true;
                }
            } catch (\Throwable $e) {
                report($e);
            }
        }

        if (!$verified) {
            return $this->paymentFailureRedirect($plan, 'monthly', 'Payment verification failed. Please contact support with payment ID: ' . $paymentId);
        }

        $billingPeriod = $request->input('billing_period', 'monthly') === 'yearly' ? 'yearly' : 'monthly';
        [$effectivePrice, $privateOffer] = $this->effectivePlanPrice($plan, $user, $billingPeriod);
        $billing = $this->payments->billingBreakdown($plan, (float) $effectivePrice, $billingPeriod, $privateOffer);
        $invoiceId = $this->payments->generateInvoiceId();

        try {
            $userPlan = $this->payments->activateSubscription(
                $user,
                $plan,
                $billing,
                $billingPeriod,
                'razorpay',
                $paymentId,
                $invoiceId,
                $request,
                false
            );

            try {
                \Illuminate\Support\Facades\Mail::to($user->email)->send(new SubscriptionActivated($userPlan));
            } catch (\Throwable $mailEx) {
                report($mailEx);
            }

            return redirect()->route('dashboard')
                ->with('success', "Payment successful. Your \"{$plan->name}\" plan has been activated automatically.")
                ->with('subscription_success', $this->payments->successPayload($userPlan, $invoiceId));
        } catch (\Throwable $e) {
            report($e);
            return $this->paymentFailureRedirect($plan, $billingPeriod, 'Plan activation error: ' . $e->getMessage());
        }
    }

    public function unlockContact(Property $property)
    {
        $user = auth()->user();

        if (!$user->canViewContact($property)) {
            $isSale = $property->isForSale();
            $planType = $isSale ? 'Buyer Pass' : 'Rental Plan';
            $billing = $isSale ? 'yearly' : 'monthly';

            return redirect()->route('plans.index', ['billing' => $billing])
                ->with('error', "You need an active {$planType} to unlock contact details for this " . ($isSale ? 'seller' : 'rental') . " post. Please choose a suitable plan.");
        }

        $user->viewContact($property);

        return redirect()->to(route('properties.show', $property) . '#property-price-card')
            ->with('success', 'Contact details unlocked successfully!');
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

    private function effectivePlanPrice(Plan $plan, $user, string $billingPeriod): array
    {
        $effectivePrice = $plan->price;

        $privateOffer = \App\Models\PrivateUserOffer::where('user_id', $user->id)
            ->where('plan_id', $plan->id)
            ->where('billing_period', $billingPeriod)
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
     * Fetch a single Razorpay order by ID using direct curl.
     */
    private function fetchRazorpayOrderDirect(string $orderId, string $keyId, string $keySecret): array
    {
        $url = 'https://api.razorpay.com/v1/orders/' . urlencode($orderId);
        return $this->razorpayCurlGet($url, $keyId, $keySecret);
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
