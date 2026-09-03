@extends('layouts.app')

@section('title', 'Secure Premium Checkout - ' . $plan->name . ' - UnlockRentals')
@section('robots', 'noindex, follow')

@push('head')
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no">
<style>
    html, body {
        width: 100% !important;
        max-width: 100% !important;
        overflow-x: hidden !important;
    }
    .checkout-stage {
        background:
            radial-gradient(circle at 15% 10%, rgba(37, 99, 235, .07), transparent 40%),
            radial-gradient(circle at 85% 20%, rgba(99, 102, 241, .06), transparent 35%),
            linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
    }
    .dark .checkout-stage {
        background:
            radial-gradient(circle at 15% 10%, rgba(59, 130, 246, .12), transparent 40%),
            radial-gradient(circle at 85% 20%, rgba(99, 102, 241, .10), transparent 35%),
            linear-gradient(180deg, #020617 0%, #0f172a 100%);
    }
    @media (max-width: 640px) {
        input, select, textarea {
            font-size: 16px !important;
        }
    }
    /* Enforce full-width Razorpay modal rendering on mobile devices */
    @media (max-width: 768px) {
        .razorpay-container,
        .razorpay-backdrop,
        iframe[name^="razorpay"],
        iframe[src*="razorpay"] {
            width: 100% !important;
            max-width: 100vw !important;
            min-width: 100% !important;
            left: 0 !important;
            right: 0 !important;
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

    $isRazorpay = $activeGateway && ($activeGateway['type'] ?? 'manual') === 'razorpay';
@endphp

<section class="checkout-stage min-h-screen py-6 sm:py-10 w-full" id="checkout-page">
    <div class="mx-auto w-full max-w-5xl px-3 sm:px-6 lg:px-8">
        
        {{-- Back Navigation --}}
        <div class="mb-4 px-1 flex items-center justify-between">
            <a href="{{ route('plans.index') }}" class="inline-flex items-center gap-2 text-xs sm:text-sm font-extrabold text-slate-500 transition hover:text-blue-600 dark:text-slate-400 dark:hover:text-blue-400" title="Back to plans">
                <i class="ph-bold ph-arrow-left text-base"></i>
                <span>Back to Plans</span>
            </a>
            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-400 dark:text-slate-500">
                <i class="ph-bold ph-lock-key text-emerald-500"></i>
                <span>256-Bit Encrypted Checkout</span>
            </span>
        </div>

        {{-- Unified Horizontal Modern Checkout Layout --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 lg:gap-6 items-stretch">
            
            {{-- LEFT COLUMN: Plan Summary Card --}}
            <div class="lg:col-span-5 bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 rounded-3xl p-5 sm:p-6 text-white flex flex-col justify-between relative overflow-hidden shadow-xl shadow-blue-600/15 border border-blue-500/30">
                <div class="absolute -right-8 -bottom-8 w-40 h-40 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
                
                <div class="relative z-10">
                    {{-- Header with icon & badge --}}
                    <div class="flex items-center justify-between gap-3 mb-4">
                        <div class="flex items-center gap-3">
                            <span class="grid h-11 w-11 shrink-0 place-items-center rounded-2xl bg-white/20 backdrop-blur-md text-white shadow-inner">
                                <i class="ph-bold ph-crown text-2xl"></i>
                            </span>
                            <div>
                                <span class="text-[10px] font-black uppercase tracking-wider text-blue-200 block">Selected Plan</span>
                                <h1 class="text-xl sm:text-2xl font-black tracking-tight text-white leading-tight">{{ $plan->name }}</h1>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 bg-white/15 backdrop-blur-sm text-[11px] font-bold rounded-xl text-blue-100 border border-white/20 shrink-0">
                            {{ $billing['duration_days'] }} Days
                        </span>
                    </div>

                    {{-- Main Price Display --}}
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/15 mb-5 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-blue-200 block">Total Payable</span>
                            <span class="text-2xl sm:text-3xl font-black text-white leading-none">
                                ₹{{ number_format($billing['final'], 2) }}
                            </span>
                        </div>
                        <span class="px-2.5 py-1 bg-emerald-500/20 text-emerald-300 text-[11px] font-bold rounded-lg border border-emerald-400/30">
                            {{ $plan->purpose === 'buy' || $billingPeriod === 'yearly' ? 'Annual Pass' : 'Rental Pass' }}
                        </span>
                    </div>

                    {{-- Compact Plan Highlights list --}}
                    <div class="space-y-2.5 text-xs font-semibold text-blue-50">
                        <div class="flex items-center gap-2.5">
                            <i class="ph-bold ph-check-circle text-emerald-300 text-base shrink-0"></i>
                            <span><strong>{{ $plan->contact_limit }}</strong> Direct Owner Contact Unlocks</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <i class="ph-bold ph-check-circle text-emerald-300 text-base shrink-0"></i>
                            <span>Zero Brokerage & Verified Listings</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <i class="ph-bold ph-check-circle text-emerald-300 text-base shrink-0"></i>
                            <span>Direct WhatsApp & Call Connect</span>
                        </div>
                        @foreach(array_slice($plan->features ?? [], 0, 1) as $feature)
                            <div class="flex items-center gap-2.5">
                                <i class="ph-bold ph-check-circle text-emerald-300 text-base shrink-0"></i>
                                <span>{{ $feature }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Safe Guarantee Pill at bottom of left card --}}
                <div class="relative z-10 mt-6 pt-4 border-t border-white/15 flex items-center justify-between text-[11px] text-blue-200 font-medium">
                    <span class="flex items-center gap-1.5"><i class="ph-bold ph-shield-check text-emerald-300"></i> RBI Verified</span>
                    <span class="flex items-center gap-1.5"><i class="ph-bold ph-lightning text-amber-300"></i> Instant Activation</span>
                </div>
            </div>

            {{-- RIGHT COLUMN: Compact Checkout Form & Payment Actions --}}
            <div class="lg:col-span-7 bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 rounded-3xl p-5 sm:p-6 shadow-sm flex flex-col justify-between space-y-4">
                
                {{-- 1. Account Details & Phone Input (Clean Horizontal Row) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    {{-- User Profile Pill --}}
                    <div class="rounded-2xl border border-slate-200 bg-slate-50/80 p-3 dark:border-slate-800 dark:bg-slate-850 flex items-center justify-between gap-2.5">
                        <div class="min-w-0">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Account (Logged In)</span>
                            <p class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white truncate">{{ auth()->user()->name }}</p>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <span class="h-7 w-7 rounded-xl bg-blue-100 dark:bg-blue-950 text-blue-600 dark:text-blue-400 font-black text-xs flex items-center justify-center shrink-0">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>
                    </div>

                    {{-- Mobile Number Input --}}
                    <div class="rounded-2xl border border-slate-200 bg-slate-50/80 p-3 dark:border-slate-800 dark:bg-slate-850 flex flex-col justify-between">
                        <div class="flex items-center justify-between mb-1">
                            <label for="checkout_user_phone" class="text-[10px] font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                                Mobile No <span class="text-red-500">*</span>
                            </label>
                            @if(!empty($userCleanPhone))
                                <span id="phone_sync_badge" class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-600 dark:text-emerald-400">
                                    <i class="ph-bold ph-check-circle"></i> Synced
                                </span>
                            @else
                                <span id="phone_sync_badge" class="inline-flex items-center gap-1 text-[10px] font-bold text-blue-600 dark:text-blue-400">
                                    Auto-saves
                                </span>
                            @endif
                        </div>
                        
                        <div class="relative flex items-center">
                            <span class="absolute left-2.5 text-xs font-bold text-slate-700 dark:text-slate-300 select-none">
                                🇮🇳 +91
                            </span>
                            <input
                                type="tel"
                                id="checkout_user_phone"
                                name="phone"
                                value="{{ $userCleanPhone ?? '' }}"
                                maxlength="10"
                                placeholder="10-digit number"
                                class="w-full rounded-xl border border-slate-300 bg-white pl-16 pr-7 py-1.5 text-xs sm:text-sm font-bold text-slate-950 placeholder-slate-400 outline-none transition focus:border-blue-600 focus:ring-2 focus:ring-blue-500/15 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                                autocomplete="tel-national"
                                inputmode="numeric"
                            >
                            <span id="phone_valid_icon" class="absolute right-2 text-emerald-500 text-sm transition-opacity duration-200 {{ !empty($userCleanPhone) ? 'opacity-100' : 'opacity-0' }}">
                                <i class="ph-bold ph-check-circle"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <p id="phone_error_text" class="hidden text-xs font-bold text-red-600 -mt-2">
                    <i class="ph-bold ph-warning-circle"></i> Please enter a valid 10-digit Indian mobile number.
                </p>

                {{-- 2. Supported Payment Methods (Compact Horizontal Chips) --}}
                <div class="rounded-2xl border border-slate-200/80 bg-slate-50/60 p-3 dark:border-slate-800 dark:bg-slate-850 flex items-center justify-between flex-wrap gap-2">
                    <span class="text-[11px] font-bold text-slate-600 dark:text-slate-400 flex items-center gap-1.5">
                        <i class="ph-bold ph-shield-check text-blue-600 text-sm"></i> Payment Modes:
                    </span>
                    <div class="flex items-center gap-2 text-[11px] font-bold text-slate-700 dark:text-slate-300 flex-wrap">
                        <span class="px-2 py-0.5 bg-white dark:bg-slate-800 rounded-lg border border-slate-200/80 dark:border-slate-700 inline-flex items-center gap-1">
                            <i class="ph-bold ph-qr-code text-blue-600"></i> UPI / QR
                        </span>
                        <span class="px-2 py-0.5 bg-white dark:bg-slate-800 rounded-lg border border-slate-200/80 dark:border-slate-700 inline-flex items-center gap-1">
                            <i class="ph-bold ph-credit-card text-blue-600"></i> Cards
                        </span>
                        <span class="px-2 py-0.5 bg-white dark:bg-slate-800 rounded-lg border border-slate-200/80 dark:border-slate-700 inline-flex items-center gap-1">
                            <i class="ph-bold ph-bank text-blue-600"></i> NetBanking
                        </span>
                    </div>
                </div>

                {{-- 3. Price Breakdown (Compact Clean Text) --}}
                <div class="rounded-2xl border border-slate-100 bg-slate-50/50 p-3.5 space-y-1.5 text-xs dark:border-slate-800 dark:bg-slate-850">
                    <div class="flex justify-between text-slate-600 dark:text-slate-400">
                        <span>Plan Price ({{ $billing['duration_days'] }} Days)</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200">₹{{ number_format($billing['subtotal'], 2) }}</span>
                    </div>

                    @if($billing['discount'] > 0)
                        <div class="flex justify-between text-emerald-600 dark:text-emerald-400 font-bold">
                            <span>Special Discount</span>
                            <span>- ₹{{ number_format($billing['discount'], 2) }}</span>
                        </div>
                    @endif

                    @if($billing['gst'] > 0)
                        <div class="flex justify-between text-slate-600 dark:text-slate-400">
                            <span>GST ({{ $billing['gst_rate'] }}%)</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200">₹{{ number_format($billing['gst'], 2) }}</span>
                        </div>
                    @endif

                    <div class="pt-2 border-t border-slate-200 dark:border-slate-700 flex items-center justify-between">
                        <span class="text-xs sm:text-sm font-black text-slate-900 dark:text-white uppercase tracking-wider">Total Amount</span>
                        <span class="text-xl sm:text-2xl font-black text-blue-600 dark:text-blue-400">
                            ₹{{ number_format($billing['final'], 2) }}
                        </span>
                    </div>
                </div>

                {{-- 4. Single Master Action Button --}}
                <form action="{{ route('plans.purchase.process', $plan) }}" method="POST" id="payment-form">
                    @csrf
                    <input type="hidden" name="billing_period" value="{{ $billingPeriod }}">
                    <input type="hidden" name="payment_method" id="payment_method" value="{{ $isRazorpay ? 'razorpay' : 'upi' }}">
                    <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
                    <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
                    <input type="hidden" name="razorpay_signature" id="razorpay_signature">

                    <button
                        type="{{ $isRazorpay ? 'button' : 'submit' }}"
                        id="pay-button"
                        class="w-full flex items-center justify-center gap-2.5 rounded-2xl bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-700 py-3.5 sm:py-4 px-5 text-base sm:text-lg font-black uppercase tracking-wider text-white shadow-lg shadow-blue-500/25 transition active:scale-[0.98] hover:shadow-blue-500/40 hover:from-blue-500 hover:to-indigo-600 disabled:cursor-not-allowed disabled:opacity-60 cursor-pointer"
                        {{ !$activeGateway ? 'disabled' : '' }}
                    >
                        <span class="btn-text flex items-center gap-2">
                            <i class="ph-bold ph-lightning-fill text-amber-300 text-xl"></i>
                            <span>Pay ₹{{ number_format($billing['final'], 2) }} · Activate Now</span>
                        </span>
                        <span class="btn-loader hidden items-center gap-2">
                            <i class="ph-bold ph-circle-notch animate-spin text-xl"></i>
                            <span>Connecting Gateway...</span>
                        </span>
                    </button>
                </form>

                {{-- 5. Trust Badges in Single Horizontal Line --}}
                <div class="flex items-center justify-between text-[11px] font-bold text-slate-400 dark:text-slate-500 pt-1 px-1">
                    <span class="flex items-center gap-1"><i class="ph-bold ph-shield-check text-blue-600"></i> 256-Bit SSL</span>
                    <span class="flex items-center gap-1"><i class="ph-bold ph-lightning text-amber-500"></i> Instant Activation</span>
                    <span class="flex items-center gap-1"><i class="ph-bold ph-receipt text-emerald-600"></i> Tax Invoice</span>
                </div>

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
        summaryPayButton: null,
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
