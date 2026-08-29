@extends('layouts.app')

@section('title', 'Secure Premium Checkout - UnlockRentals')
@section('robots', 'noindex, follow')

@push('head')
<style>
    .checkout-stage {
        background:
            radial-gradient(circle at 10% 6%, rgba(37, 99, 235, .08), transparent 35%),
            radial-gradient(circle at 85% 15%, rgba(20, 184, 166, .08), transparent 30%),
            linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
    }
    .dark .checkout-stage {
        background:
            radial-gradient(circle at 10% 6%, rgba(59, 130, 246, .15), transparent 35%),
            radial-gradient(circle at 85% 15%, rgba(20, 184, 166, .12), transparent 30%),
            linear-gradient(180deg, #020617 0%, #0f172a 100%);
    }
    .method-card {
        border: 1.5px solid rgba(226, 232, 240, .9);
        background: rgba(255, 255, 255, .95);
        transition: all .2s ease;
    }
    .dark .method-card {
        border-color: rgba(51, 65, 85, .8);
        background: rgba(15, 23, 42, .85);
    }
    .method-card:hover {
        border-color: #2563EB;
        transform: translateY(-2px);
    }
    .method-card.selected {
        border-color: #2563EB;
        background: linear-gradient(180deg, rgba(239, 246, 255, .98), rgba(255, 255, 255, .98));
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .15);
    }
    .dark .method-card.selected {
        border-color: #3b82f6;
        background: linear-gradient(180deg, rgba(30, 58, 138, .3), rgba(15, 23, 42, .95));
        box-shadow: 0 0 0 3px rgba(59, 130, 246, .25);
    }
    /* Prevent mobile browser unwanted auto-zoom by ensuring minimum 16px font size on inputs */
    @media (max-width: 640px) {
        input, select, textarea {
            font-size: 16px !important;
        }
    }
</style>
@endpush

@section('content')
@php
    $billingPeriod = $billingPeriod ?? request('billing', 'monthly');
    $billingPeriod = $billingPeriod === 'yearly' ? 'yearly' : 'monthly';

    $effectivePrice = isset($effectivePrice) ? (float) $effectivePrice : (float) $plan->price;

    if (!isset($billing)) {
        $months = $billingPeriod === 'yearly' ? 12 : 1;
        $durationDays = $billingPeriod === 'yearly' ? 365 : $plan->duration_days;
        $subtotalPaise = (int) round((float) $plan->price * $months * 100);
        $offerSubtotalPaise = (int) round($effectivePrice * $months * 100);
        $yearlyDiscountPaise = $billingPeriod === 'yearly' ? (int) round($offerSubtotalPaise * 0.20) : 0;
        $discountPaise = max(0, $subtotalPaise - $offerSubtotalPaise) + $yearlyDiscountPaise;
        $taxablePaise = max(0, $offerSubtotalPaise - $yearlyDiscountPaise);
        $gstRate = (float) ($site_settings['gst_rate'] ?? 18);
        $gstPaise = (int) round($taxablePaise * ($gstRate / 100));
        $finalPaise = max(100, $taxablePaise + $gstPaise);

        $billing = [
            'period' => $billingPeriod,
            'duration_days' => $durationDays,
            'subtotal' => $subtotalPaise / 100,
            'discount' => $discountPaise / 100,
            'gst' => $gstPaise / 100,
            'gst_rate' => $gstRate,
            'final' => $finalPaise / 100,
            'final_paise' => $finalPaise,
            'yearly_savings' => $yearlyDiscountPaise / 100,
        ];
    }

    $methods = [
        ['id' => 'upi', 'name' => 'UPI', 'icon' => 'ph-qr-code', 'copy' => 'PhonePe, GPay, Paytm UPI'],
        ['id' => 'phonepe', 'name' => 'PhonePe', 'icon' => 'ph-device-mobile', 'copy' => 'Pay through PhonePe UPI'],
        ['id' => 'paytm', 'name' => 'Paytm', 'icon' => 'ph-wallet', 'copy' => 'Paytm UPI or wallet'],
        ['id' => 'razorpay', 'name' => 'Razorpay', 'icon' => 'ph-lightning', 'copy' => 'Fast checkout with verification'],
        ['id' => 'card', 'name' => 'Credit/Debit Card', 'icon' => 'ph-credit-card', 'copy' => 'Visa, Mastercard, RuPay'],
        ['id' => 'netbanking', 'name' => 'Net Banking', 'icon' => 'ph-bank', 'copy' => 'Major Indian banks'],
        ['id' => 'wallet', 'name' => 'Wallets', 'icon' => 'ph-wallet', 'copy' => 'Popular mobile wallets'],
        ['id' => 'qr', 'name' => 'QR Code Payment', 'icon' => 'ph-scan', 'copy' => 'Scan and pay securely'],
    ];
    $isRazorpay = $activeGateway && ($activeGateway['type'] ?? 'manual') === 'razorpay';
@endphp

<section class="checkout-stage min-h-screen pt-20 pb-16 lg:pt-24 lg:pb-20" id="checkout-page">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
        
        {{-- Top Navigation & Header --}}
        <div class="mb-5">
            <a href="{{ route('plans.index') }}" class="inline-flex items-center gap-1.5 text-sm font-bold text-slate-500 transition hover:text-blue-600 dark:text-slate-400 dark:hover:text-blue-400" title="Back to plans">
                <i class="ph-bold ph-arrow-left text-base"></i>
                <span>Back to Plans</span>
            </a>
            
            <div class="mt-2.5 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <span class="inline-flex items-center gap-1 text-xs font-black uppercase tracking-wider text-blue-600 dark:text-blue-400">
                        <i class="ph-bold ph-shield-check"></i> Secure Checkout
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-slate-900 dark:text-white">
                        Review & Complete Payment
                    </h1>
                </div>
                
                {{-- Trust Badges --}}
                <div class="flex items-center gap-2 text-xs font-bold text-slate-600 dark:text-slate-400">
                    <span class="inline-flex items-center gap-1 rounded-lg bg-emerald-50 px-2.5 py-1 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300">
                        <i class="ph-bold ph-lock-key"></i> 256-Bit SSL
                    </span>
                    <span class="inline-flex items-center gap-1 rounded-lg bg-blue-50 px-2.5 py-1 text-blue-700 dark:bg-blue-950/50 dark:text-blue-300">
                        <i class="ph-bold ph-lightning"></i> Instant Access
                    </span>
                </div>
            </div>
        </div>

        {{-- Main Content Grid --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
            
            {{-- Left Column (7 cols): Plan info, contact details, payment trigger --}}
            <div class="space-y-5 lg:col-span-7">
                
                {{-- 1. Selected Plan Banner --}}
                <div class="rounded-2xl border border-slate-200/90 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-center gap-3.5">
                            <span class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 text-white shadow-md shadow-blue-500/20">
                                <i class="ph-bold ph-crown text-2xl"></i>
                            </span>
                            <div>
                                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Selected Membership</span>
                                <h2 class="text-xl font-black text-slate-900 dark:text-white">{{ $plan->name }}</h2>
                                <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-0.5">
                                    {{ $billingPeriod === 'yearly' ? 'Annual (Buy)' : 'Monthly (Rent)' }} · {{ $billing['duration_days'] }} Days Access
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-bold text-slate-400 block">Total</span>
                            <span class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white">
                                ₹{{ number_format($billing['final'], 2) }}
                            </span>
                        </div>
                    </div>

                    {{-- Plan Highlights --}}
                    <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800 grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                        <div class="flex items-center gap-2 text-slate-700 dark:text-slate-300">
                            <i class="ph-bold ph-check-circle text-emerald-600 dark:text-emerald-400 text-base shrink-0"></i>
                            <span><strong>{{ $plan->contact_limit }}</strong> Direct Owner Contact Unlocks</span>
                        </div>
                        <div class="flex items-center gap-2 text-slate-700 dark:text-slate-300">
                            <i class="ph-bold ph-check-circle text-emerald-600 dark:text-emerald-400 text-base shrink-0"></i>
                            <span>Zero Brokerage Forever</span>
                        </div>
                        @foreach(($plan->features ?? []) as $feature)
                            <div class="flex items-center gap-2 text-slate-700 dark:text-slate-300">
                                <i class="ph-bold ph-check-circle text-emerald-600 dark:text-emerald-400 text-base shrink-0"></i>
                                <span>{{ $feature }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- 2. Account & Contact Details Card --}}
                <div class="rounded-2xl border border-slate-200/90 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-black text-slate-900 dark:text-white flex items-center gap-2">
                            <i class="ph-bold ph-user-circle text-blue-600 dark:text-blue-400 text-lg"></i>
                            <span>Account Details</span>
                        </h3>
                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-bold text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-400">
                            <i class="ph-bold ph-check-circle"></i> Logged In
                        </span>
                    </div>

                    <div class="space-y-4">
                        {{-- Name & Email --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">Full Name</label>
                                <div class="rounded-xl border border-slate-200 bg-slate-50/90 px-3.5 py-2.5 text-sm font-bold text-slate-800 dark:border-slate-700 dark:bg-slate-800/80 dark:text-slate-200">
                                    {{ auth()->user()->name }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1">Email Address</label>
                                <div class="rounded-xl border border-slate-200 bg-slate-50/90 px-3.5 py-2.5 text-sm font-bold text-slate-800 truncate dark:border-slate-700 dark:bg-slate-800/80 dark:text-slate-200" title="{{ auth()->user()->email }}">
                                    {{ auth()->user()->email }}
                                </div>
                            </div>
                        </div>

                        {{-- Mobile Number Input --}}
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="checkout_user_phone" class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                    Mobile Number <span class="text-red-500">*</span>
                                </label>
                                @if(!empty($userCleanPhone))
                                    <span id="phone_sync_badge" class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600 dark:text-emerald-400">
                                        <i class="ph-bold ph-check-circle"></i> Synced from profile
                                    </span>
                                @else
                                    <span id="phone_sync_badge" class="inline-flex items-center gap-1 text-xs font-bold text-blue-600 dark:text-blue-400">
                                        <i class="ph-bold ph-sparkle"></i> Auto-saves to your profile
                                    </span>
                                @endif
                            </div>
                            
                            <div class="relative flex items-center">
                                <div class="absolute left-3.5 flex items-center gap-1 text-sm font-bold text-slate-700 dark:text-slate-300 select-none">
                                    <span>🇮🇳 +91</span>
                                    <span class="h-4 w-px bg-slate-300 dark:bg-slate-600 ml-1.5"></span>
                                </div>
                                <input
                                    type="tel"
                                    id="checkout_user_phone"
                                    name="phone"
                                    value="{{ $userCleanPhone ?? '' }}"
                                    maxlength="10"
                                    placeholder="Enter 10-digit mobile number"
                                    class="w-full rounded-xl border border-slate-300 bg-white pl-24 pr-11 py-3 text-base font-bold text-slate-900 placeholder-slate-400 outline-none transition focus:border-blue-600 focus:ring-4 focus:ring-blue-500/10 dark:border-slate-700 dark:bg-slate-800 dark:text-white dark:focus:border-blue-400"
                                    autocomplete="tel-national"
                                    inputmode="numeric"
                                >
                                <span id="phone_valid_icon" class="absolute right-3.5 text-emerald-500 text-xl transition-opacity duration-200 {{ !empty($userCleanPhone) ? 'opacity-100' : 'opacity-0' }}">
                                    <i class="ph-bold ph-check-circle"></i>
                                </span>
                            </div>
                            <p id="phone_error_text" class="mt-1.5 hidden text-xs font-bold text-red-600">
                                <i class="ph-bold ph-warning-circle"></i> Please enter a valid 10-digit Indian mobile number.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- 3. Gateway / Payment Trigger --}}
                @if($isRazorpay)
                    <div class="rounded-2xl border border-blue-200 bg-gradient-to-br from-blue-50/80 via-white to-sky-50/60 p-5 shadow-sm dark:border-blue-900/60 dark:from-blue-950/30 dark:via-slate-900 dark:to-slate-900">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2.5">
                                <span class="grid h-9 w-9 place-items-center rounded-xl bg-blue-600 text-white shadow-sm shadow-blue-500/20">
                                    <i class="ph-bold ph-lightning-fill text-amber-300 text-lg"></i>
                                </span>
                                <div>
                                    <h3 class="text-sm font-black text-slate-900 dark:text-white">Direct Razorpay Gateway</h3>
                                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400">UPI (GPay, PhonePe, Paytm), Cards & NetBanking</p>
                                </div>
                            </div>
                            <span class="rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-bold text-blue-700 dark:bg-blue-900/60 dark:text-blue-300">
                                RBI Verified
                            </span>
                        </div>

                        {{-- Payment Method Icons --}}
                        <div class="grid grid-cols-4 gap-2 pt-2 text-center text-xs">
                            <div class="rounded-xl border border-slate-200/80 bg-white p-2 dark:border-slate-800 dark:bg-slate-800/90">
                                <i class="ph-bold ph-qr-code text-lg text-blue-600 dark:text-blue-400"></i>
                                <span class="block font-bold text-slate-800 dark:text-slate-200 mt-0.5">UPI / QR</span>
                            </div>
                            <div class="rounded-xl border border-slate-200/80 bg-white p-2 dark:border-slate-800 dark:bg-slate-800/90">
                                <i class="ph-bold ph-credit-card text-lg text-blue-600 dark:text-blue-400"></i>
                                <span class="block font-bold text-slate-800 dark:text-slate-200 mt-0.5">Cards</span>
                            </div>
                            <div class="rounded-xl border border-slate-200/80 bg-white p-2 dark:border-slate-800 dark:bg-slate-800/90">
                                <i class="ph-bold ph-bank text-lg text-blue-600 dark:text-blue-400"></i>
                                <span class="block font-bold text-slate-800 dark:text-slate-200 mt-0.5">NetBanking</span>
                            </div>
                            <div class="rounded-xl border border-slate-200/80 bg-white p-2 dark:border-slate-800 dark:bg-slate-800/90">
                                <i class="ph-bold ph-wallet text-lg text-blue-600 dark:text-blue-400"></i>
                                <span class="block font-bold text-slate-800 dark:text-slate-200 mt-0.5">Wallets</span>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Payment Form --}}
                <form action="{{ route('plans.purchase.process', $plan) }}" method="POST" id="payment-form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="billing_period" value="{{ $billingPeriod }}">
                    <input type="hidden" name="payment_method" id="payment_method" value="{{ $isRazorpay ? 'razorpay' : 'upi' }}">
                    <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
                    <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
                    <input type="hidden" name="razorpay_signature" id="razorpay_signature">

                    {{-- Pay Button --}}
                    <button
                        type="{{ $isRazorpay ? 'button' : 'submit' }}"
                        id="pay-button"
                        class="w-full flex items-center justify-center gap-2.5 rounded-2xl bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-600 py-4 px-6 text-base font-black uppercase tracking-wider text-white shadow-xl shadow-blue-500/25 transition active:scale-[0.99] hover:shadow-blue-500/40 disabled:cursor-not-allowed disabled:opacity-60"
                        {{ !$activeGateway ? 'disabled' : '' }}
                    >
                        <span class="btn-text flex items-center gap-2">
                            <i class="ph-bold ph-lightning-fill text-amber-300 text-lg"></i>
                            <span>Pay ₹{{ number_format($billing['final'], 2) }} · Activate Instantly</span>
                        </span>
                        <span class="btn-loader hidden items-center gap-2">
                            <i class="ph-bold ph-circle-notch animate-spin text-lg"></i>
                            Connecting to Gateway...
                        </span>
                    </button>
                </form>

                {{-- Fallback manual verification section --}}
                @if($isRazorpay)
                <div id="manual-verify-section" class="hidden rounded-2xl border border-amber-200 bg-amber-50/70 p-4 dark:border-amber-900/60 dark:bg-slate-900">
                    <div class="flex items-start gap-3">
                        <i class="ph-bold ph-info text-amber-600 text-xl shrink-0 mt-0.5"></i>
                        <div class="text-xs text-slate-700 dark:text-slate-300">
                            <p class="font-bold text-slate-900 dark:text-white">Already completed payment?</p>
                            <p class="mt-0.5">Enter your Razorpay Payment ID (starts with <strong>pay_</strong>) to activate instantly:</p>
                            <div class="mt-2.5 flex gap-2">
                                <input type="text" id="manual_razorpay_payment_id" placeholder="pay_xxxxxxxxxxxxx" class="flex-1 rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs font-mono font-bold text-slate-900 outline-none focus:border-amber-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                                <button type="button" id="manual-verify-btn" class="rounded-xl bg-amber-600 px-4 py-2 text-xs font-bold text-white shadow hover:bg-amber-700">
                                    Verify
                                </button>
                            </div>
                            <p id="manual-verify-error" class="mt-1.5 hidden text-xs font-bold text-red-600"></p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            {{-- Right Column (5 cols): Order Summary --}}
            <div class="lg:col-span-5">
                <aside class="sticky top-24 rounded-2xl border border-slate-200/90 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <h3 class="text-base font-black text-slate-900 dark:text-white pb-3 border-b border-slate-100 dark:border-slate-800">
                        Order Summary
                    </h3>

                    <div class="mt-4 space-y-3 text-sm">
                        <div class="flex justify-between text-slate-600 dark:text-slate-400">
                            <span>Plan ({{ $billingPeriod === 'yearly' ? 'Buy / 365 Days' : 'Rent / ' . $billing['duration_days'] . ' Days' }})</span>
                            <span class="font-bold text-slate-900 dark:text-white">₹{{ number_format($billing['subtotal'], 2) }}</span>
                        </div>

                        @if($billing['discount'] > 0)
                            <div class="flex justify-between text-emerald-600 dark:text-emerald-400">
                                <span>Discount</span>
                                <span class="font-bold">- ₹{{ number_format($billing['discount'], 2) }}</span>
                            </div>
                        @endif

                        @if($billing['gst'] > 0)
                            <div class="flex justify-between text-slate-600 dark:text-slate-400">
                                <span>GST ({{ $billing['gst_rate'] }}%)</span>
                                <span class="font-bold text-slate-900 dark:text-white">₹{{ number_format($billing['gst'], 2) }}</span>
                            </div>
                        @endif

                        <div class="pt-3 border-t border-slate-200 dark:border-slate-800">
                            <div class="flex items-baseline justify-between">
                                <span class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-wider">Total Payable</span>
                                <span class="text-2xl sm:text-3xl font-black text-blue-600 dark:text-blue-400">
                                    ₹{{ number_format($billing['final'], 2) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Secondary Action --}}
                    <button
                        type="button"
                        id="summary-pay-button"
                        class="mt-5 w-full flex items-center justify-center gap-2 rounded-xl bg-slate-900 py-3.5 px-4 text-sm font-black text-white shadow-md transition hover:bg-slate-800 dark:bg-blue-600 dark:hover:bg-blue-700"
                        {{ !$activeGateway ? 'disabled' : '' }}
                    >
                        <i class="ph-bold ph-lock-key text-base"></i>
                        <span>Proceed to Pay</span>
                    </button>

                    <div class="mt-4 rounded-xl bg-emerald-50/80 p-3 text-xs font-bold text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-300">
                        <i class="ph-bold ph-lightning"></i> Instant activation immediately after payment.
                    </div>

                    {{-- Guarantees --}}
                    <div class="mt-4 grid grid-cols-3 gap-2 text-center text-[11px] font-bold text-slate-500 dark:text-slate-400">
                        <div class="rounded-lg bg-slate-50 p-2 dark:bg-slate-800/60">
                            <i class="ph-bold ph-shield-check text-blue-600 dark:text-blue-400 block text-base mb-0.5"></i>
                            <span>Secure</span>
                        </div>
                        <div class="rounded-lg bg-slate-50 p-2 dark:bg-slate-800/60">
                            <i class="ph-bold ph-receipt text-blue-600 dark:text-blue-400 block text-base mb-0.5"></i>
                            <span>GST Invoice</span>
                        </div>
                        <div class="rounded-lg bg-slate-50 p-2 dark:bg-slate-800/60">
                            <i class="ph-bold ph-sparkle text-blue-600 dark:text-blue-400 block text-base mb-0.5"></i>
                            <span>Verified</span>
                        </div>
                    </div>
                </aside>
            </div>

        </div>
    </div>
</section>

<x-subscription.payment-processing />

@endsection

@push('scripts')
@if($isRazorpay)
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
@endif
<script src="{{ asset('js/subscription-checkout.js') }}?v={{ time() }}"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    if (typeof UnlockSubscriptionCheckout !== 'function') return;
    UnlockSubscriptionCheckout({
        form: document.getElementById('payment-form'),
        methodInput: document.getElementById('payment_method'),
        phoneInput: document.getElementById('checkout_user_phone'),
        phoneError: document.getElementById('phone_error_text'),
        phoneValidIcon: document.getElementById('phone_valid_icon'),
        phoneSyncBadge: document.getElementById('phone_sync_badge'),
        payButton: document.getElementById('pay-button'),
        summaryPayButton: document.getElementById('summary-pay-button'),
        overlay: document.getElementById('processing-overlay'),
        progressBar: document.getElementById('processing-progress-bar'),
        processingStatusText: document.getElementById('processing-status-text'),
        isRazorpay: @json($isRazorpay),
        razorpayKeyConfigured: @json((bool) $razorpayKeyId),
        razorpayOrderUrl: @json(route('plans.razorpay.order', $plan)),
        checkOrderStatusUrl: @json(route('plans.check-order-status', $plan)),
        csrfToken: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
        plansUrl: @json(route('plans.index')),
        billingPeriod: @json($billingPeriod),
        planName: @json($plan->name),
        brandLogo: @json(asset('images/logo-icon.png')),
        userPrefill: {
            name: @json(auth()->user()->name),
            email: @json(auth()->user()->email),
            contact: @json($userCleanPhone ?? ''),
        },
        manualPaymentLink: @json($activeGateway['payment_link'] ?? null),
    });
});
</script>
@endpush


