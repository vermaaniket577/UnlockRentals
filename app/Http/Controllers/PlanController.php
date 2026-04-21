<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Setting;
use App\Models\UserPlan;
use App\Models\Property;
use Razorpay\Api\Api;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    /**
     * Show available plans for users to purchase.
     */
    public function index()
    {
        $plans = Plan::active()->get();
        $activePlan = auth()->check() ? auth()->user()->activePlan() : null;
        $pendingPlan = auth()->check()
            ? auth()->user()->userPlans()->pending()->with('plan')->latest()->first()
            : null;

        return view('plans.index', compact('plans', 'activePlan', 'pendingPlan'));
    }

    /**
     * Purchase (subscribe to) a plan — sends to admin for approval.
     */
    public function purchase(Plan $plan)
    {
        $user = auth()->user();

        // Check if already has an active plan
        if ($user->hasActivePlan()) {
            return redirect()->back()->with('error', 'You already have an active plan.');
        }

        return redirect()->route('plans.checkout', $plan);
    }

    /**
     * Show the checkout page for a specific plan.
     */
    public function checkout(Plan $plan)
    {
        $user = auth()->user();
        if ($user->hasActivePlan()) {
            return redirect()->route('plans.index')->with('error', 'You already have an active plan.');
        }

        $settings = Setting::pluck('value', 'key')->toArray();
        $razorpayKeyId = $settings['razorpay_key_id'] ?? null;
        $razorpayKeySecret = $settings['razorpay_key_secret'] ?? null;

        $razorpayOrder = null;
        if ($razorpayKeyId && $razorpayKeySecret) {
            try {
                $api = new Api($razorpayKeyId, $razorpayKeySecret);
                $razorpayOrder = $api->order->create([
                    'receipt' => 'plan_' . $plan->id . '_' . time(),
                    'amount' => $plan->price * 100, // in paise
                    'currency' => 'INR',
                ]);
            } catch (\Exception $e) {
                \Log::error('Razorpay Order Error: ' . $e->getMessage());
            }
        }

        return view('plans.checkout', compact('plan', 'razorpayOrder', 'razorpayKeyId'));
    }

    /**
     * Handle the Razorpay payment verification and auto-activate the plan.
     */
    public function processPayment(Request $request, Plan $plan)
    {
        $user = auth()->user();
        $settings = Setting::pluck('value', 'key')->toArray();
        $razorpayKeyId = $settings['razorpay_key_id'] ?? null;
        $razorpayKeySecret = $settings['razorpay_key_secret'] ?? null;

        // Verify Razorpay Payment Signature
        if ($request->has('razorpay_payment_id') && $razorpayKeyId && $razorpayKeySecret) {
            try {
                $api = new Api($razorpayKeyId, $razorpayKeySecret);
                $attributes = [
                    'razorpay_order_id' => $request->razorpay_order_id,
                    'razorpay_payment_id' => $request->razorpay_payment_id,
                    'razorpay_signature' => $request->razorpay_signature
                ];
                $api->utility->verifyPaymentSignature($attributes);
                
                $reference = $request->razorpay_payment_id;
            } catch (\Exception $e) {
                return redirect()->route('plans.index')->with('error', 'Payment verification failed: ' . $e->getMessage());
            }
        } else {
            return redirect()->route('plans.index')->with('error', 'Payment was not processed correctly. If payment was deducted, please contact support with your Transaction ID.');
        }

        // Create and Approve the plan
        UserPlan::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'status' => 'approved',
            'payment_reference' => $reference,
            'approved_at' => now(),
            'expires_at' => now()->addDays($plan->duration_days),
        ]);

        return redirect()->route('dashboard')
            ->with('success', "Payment Successful! Your \"{$plan->name}\" plan has been activated automatically. 🚀");
    }

    /**
     * Unlock a property owner's contact details.
     */
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
}
