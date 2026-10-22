<?php

namespace Database\Seeders;

use App\Models\PaymentLog;
use App\Models\Plan;
use App\Models\User;
use App\Models\UserPlan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', '!=', 'admin')->get();
        if ($users->isEmpty()) {
            $users = User::all();
        }
        $plans = Plan::all();

        if ($users->isEmpty() || $plans->isEmpty()) {
            return;
        }

        $sampleData = [
            [
                'status' => 'approved',
                'method' => 'razorpay',
                'period' => 'monthly',
                'ref_prefix' => 'pay_Rzp',
                'note' => 'Instant Razorpay checkout payment.',
                'days_ago' => 1,
            ],
            [
                'status' => 'approved',
                'method' => 'upi',
                'period' => 'monthly',
                'ref_prefix' => 'UPI/PhonePe/',
                'note' => 'PhonePe UPI QR payment confirmed.',
                'days_ago' => 2,
            ],
            [
                'status' => 'pending',
                'method' => 'bank_transfer',
                'period' => 'yearly',
                'ref_prefix' => 'NEFT-HDFC-',
                'note' => 'Awaiting admin verification of bank transfer UTR.',
                'days_ago' => 0,
            ],
            [
                'status' => 'approved',
                'method' => 'upi',
                'period' => 'monthly',
                'ref_prefix' => 'UPI/GPay/',
                'note' => 'Google Pay payment auto-approved.',
                'days_ago' => 3,
            ],
            [
                'status' => 'pending',
                'method' => 'upi',
                'period' => 'monthly',
                'ref_prefix' => 'PAYTM-QR-',
                'note' => 'Paytm QR payment proof uploaded.',
                'days_ago' => 0,
            ],
            [
                'status' => 'rejected',
                'method' => 'upi',
                'period' => 'monthly',
                'ref_prefix' => 'UPI-FAIL-',
                'note' => 'Rejected: UTR not found on merchant bank statement.',
                'days_ago' => 4,
            ],
            [
                'status' => 'approved',
                'method' => 'card',
                'period' => 'yearly',
                'ref_prefix' => 'CC-AXIS-',
                'note' => 'Axis Bank Credit Card via Razorpay.',
                'days_ago' => 6,
            ],
            [
                'status' => 'expired',
                'method' => 'razorpay',
                'period' => 'monthly',
                'ref_prefix' => 'pay_Exp',
                'note' => 'Plan validity expired after 30 days.',
                'days_ago' => 35,
            ],
            [
                'status' => 'approved',
                'method' => 'razorpay',
                'period' => 'monthly',
                'ref_prefix' => 'pay_Direct',
                'note' => 'Direct contact pass unlocked.',
                'days_ago' => 7,
            ],
            [
                'status' => 'pending',
                'method' => 'bank_transfer',
                'period' => 'monthly',
                'ref_prefix' => 'IMPS-ICICI-',
                'note' => 'IMPS transfer receipt submitted.',
                'days_ago' => 1,
            ],
        ];

        foreach ($sampleData as $idx => $sample) {
            $user = $users[$idx % $users->count()];
            $plan = $plans[$idx % $plans->count()];

            $durationDays = $sample['period'] === 'yearly' ? 365 : (int) ($plan->duration_days ?: 30);
            $basePrice = (float) ($plan->price ?: 499);
            $subtotal = $sample['period'] === 'yearly' ? ($basePrice * 10) : $basePrice;
            $discount = ($sample['period'] === 'yearly') ? round($subtotal * 0.15) : 0;
            $taxable = $subtotal - $discount;
            $gst = round($taxable * 0.18, 2);
            $finalAmount = round($taxable + $gst, 2);

            $createdAt = now()->subDays($sample['days_ago'])->subMinutes(rand(10, 480));
            $invoiceId = 'INV-' . $createdAt->format('Ymd') . '-' . strtoupper(Str::random(6));
            $txnId = $sample['ref_prefix'] . strtoupper(Str::random(8));

            $approvedAt = null;
            $expiresAt = null;
            if ($sample['status'] === 'approved') {
                $approvedAt = $createdAt;
                $expiresAt = (clone $createdAt)->addDays($durationDays);
            } elseif ($sample['status'] === 'expired') {
                $approvedAt = (clone $createdAt)->subDays(35);
                $expiresAt = (clone $approvedAt)->addDays($durationDays);
            }

            $userPlan = UserPlan::create([
                'user_id'           => $user->id,
                'plan_id'           => $plan->id,
                'status'            => $sample['status'],
                'contacts_used'     => rand(0, min(5, (int) ($plan->contact_limit ?: 10))),
                'approved_at'       => $approvedAt,
                'expires_at'        => $expiresAt,
                'admin_note'        => $sample['note'],
                'payment_reference' => $txnId,
                'amount_paid'       => $finalAmount,
                'transaction_id'    => $txnId,
                'invoice_id'        => $invoiceId,
                'billing_period'    => $sample['period'],
                'subtotal_amount'   => $subtotal,
                'discount_amount'   => $discount,
                'gst_amount'        => $gst,
                'final_amount'      => $finalAmount,
                'payment_method'    => $sample['method'],
                'auto_renew'        => false,
                'created_at'        => $createdAt,
                'updated_at'        => $createdAt,
            ]);

            // Add corresponding Gateway Log entry
            PaymentLog::create([
                'user_id'        => $user->id,
                'amount'         => $finalAmount,
                'method'         => $sample['method'],
                'status'         => $sample['status'] === 'approved' ? 'success' : ($sample['status'] === 'pending' ? 'pending' : 'failed'),
                'transaction_id' => $txnId,
                'details'        => [
                    'invoice_id'     => $invoiceId,
                    'plan_id'        => $plan->id,
                    'plan_name'      => $plan->name,
                    'billing_period' => $sample['period'],
                    'note'           => $sample['note'],
                ],
                'created_at'     => $createdAt,
                'updated_at'     => $createdAt,
            ]);
        }
    }
}
