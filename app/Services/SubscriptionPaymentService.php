<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\PaymentLog;
use App\Models\Plan;
use App\Models\User;
use App\Models\UserPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Razorpay\Api\Api;

class SubscriptionPaymentService
{
    /**
     * Calculate billing details with subtotal, discount, GST, and final totals.
     */
    public function billingBreakdown(Plan $plan, float $effectivePrice, string $billingPeriod): array
    {
        $months = $billingPeriod === 'yearly' ? 12 : 1;
        $durationDays = $billingPeriod === 'yearly' ? 365 : (int) $plan->duration_days;
        $subtotal = (float) $plan->price * $months;
        $offerSubtotal = $effectivePrice * $months;
        $yearlyDiscount = $billingPeriod === 'yearly' ? round($offerSubtotal * 0.20, 2) : 0;
        $discount = max(0, $subtotal - $offerSubtotal) + $yearlyDiscount;
        $taxable = max(0, $offerSubtotal - $yearlyDiscount);
        $gstRate = (float) \App\Models\Setting::get('gst_rate', '18');
        $gst = round($taxable * ($gstRate / 100), 2);

        // Razorpay requires minimum ₹1 (100 paise). Enforce this at billing level
        // so the displayed amount always matches the Razorpay charge.
        $final = max(1.00, round($taxable + $gst, 2));

        return [
            'period' => $billingPeriod,
            'duration_days' => $durationDays,
            'subtotal' => round($subtotal, 2),
            'discount' => round($discount, 2),
            'gst' => $gst,
            'gst_rate' => $gstRate,
            'final' => $final,
            'yearly_savings' => $yearlyDiscount,
        ];
    }

    /**
     * Map complex error logs to consumer friendly status messages.
     */
    public function categorizeFailure(string $reason): string
    {
        $reasonLower = strtolower($reason);
        if (str_contains($reasonLower, 'signature')) {
            return 'Payment verification failed. The payment signature was invalid.';
        }
        if (str_contains($reasonLower, 'cancelled') || str_contains($reasonLower, 'dismiss')) {
            return 'Payment was cancelled before completion.';
        }
        if (str_contains($reasonLower, 'network') || str_contains($reasonLower, 'timeout')) {
            return 'A network issue occurred. If money was deducted, it will be refunded automatically.';
        }
        return $reason;
    }

    /**
     * Generate a unique invoice sequence.
     */
    public function generateInvoiceId(): string
    {
        return 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(6));
    }

    /**
     * Check if a subscription exists for the transaction ID.
     */
    public function findApprovedByTransaction(string $transactionId)
    {
        return UserPlan::where('status', 'approved')
            ->where(function ($q) use ($transactionId) {
                $q->where('transaction_id', $transactionId)
                  ->orWhere('payment_reference', $transactionId);
            })
            ->first();
    }

    /**
     * Build the session success parameters for UI response modal display.
     */
    public function successPayload(UserPlan $userPlan, string $invoiceId): array
    {
        return [
            'plan' => $userPlan->plan?->name ?? 'Premium',
            'expires_at' => $userPlan->expires_at ? $userPlan->expires_at->format('M d, Y') : '—',
            'invoice_id' => $invoiceId,
        ];
    }

    /**
     * Verify payment signature from Razorpay payload.
     */
    public function verifyRazorpaySignature(
        string $orderId,
        string $paymentId,
        string $signature,
        string $keyId,
        string $keySecret
    ): void {
        $api = new Api($keyId, $keySecret);
        $api->utility->verifyPaymentSignature([
            'razorpay_order_id' => $orderId,
            'razorpay_payment_id' => $paymentId,
            'razorpay_signature' => $signature,
        ]);
    }

    /**
     * Handle cleanup logs if signature processing fails.
     */
    public function rollbackFailedAttempt(User $user, Plan $plan, string $reference): void
    {
        Log::warning("Subscription checkout failed signature validation. User: {$user->id}, Plan: {$plan->id}, Transaction ID: {$reference}");
    }

    /**
     * Create database entries mapping payment status.
     */
    public function logPayment(
        User $user,
        float $amount,
        string $method,
        string $status,
        string $reference,
        array $details
    ): PaymentLog {
        return PaymentLog::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'method' => $method,
            'status' => $status,
            'transaction_id' => $reference,
            'details' => $details,
        ]);
    }

    /**
     * Activate the user plan and update transaction logs.
     */
    public function activateSubscription(
        User $user,
        Plan $plan,
        array $billing,
        string $billingPeriod,
        string $paymentMethod,
        string $reference,
        string $invoiceId,
        Request $request,
        bool $autoRenew
    ): UserPlan {
        // Expire all previous plans of the user first
        UserPlan::where('user_id', $user->id)
            ->where('status', 'approved')
            ->update([
                'status' => 'expired',
                'expires_at' => now(),
            ]);

        $durationDays = (int) $billing['duration_days'];
        $expiresAt = now()->addDays($durationDays);

        $userPlan = UserPlan::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'status' => 'approved',
            'contacts_used' => 0,
            'approved_at' => now(),
            'expires_at' => $expiresAt,
            'payment_reference' => $reference,
            'amount_paid' => $billing['final'],
            'transaction_id' => $reference,
            'invoice_id' => $invoiceId,
            'billing_period' => $billingPeriod,
            'subtotal_amount' => $billing['subtotal'],
            'discount_amount' => $billing['discount'],
            'gst_amount' => $billing['gst'],
            'final_amount' => $billing['final'],
            'payment_method' => $paymentMethod,
            'auto_renew' => $autoRenew,
        ]);

        $this->logPayment($user, $billing['final'], $paymentMethod, 'success', $reference, [
            'invoice_id' => $invoiceId,
            'plan_id' => $plan->id,
            'billing_period' => $billingPeriod,
            'auto_renew' => $autoRenew,
        ]);

        ActivityLog::create([
            'admin_id' => $user->id,
            'action' => 'subscription_activated',
            'description' => "{$user->name} purchased and activated {$plan->name} ({$invoiceId}).",
            'ip_address' => $request->ip(),
        ]);

        // Mark any matching private offers as claimed (works for both private and public plans
        // since admins can assign discounted offers for any plan)
        \App\Models\PrivateUserOffer::where('user_id', $user->id)
            ->where('plan_id', $plan->id)
            ->where('status', 'active')
            ->update(['status' => 'claimed']);

        return $userPlan;
    }
}
