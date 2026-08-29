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

<section class="checkout-stage min-h-screen pt-16 pb-16 sm:pt-24 sm:pb-24 w-full" id="checkout-page">
    <div class="mx-auto w-full max-w-2xl px-2 sm:px-6">
        
        {{-- Back Navigation --}}
        <div class="mb-4 sm:mb-5 px-1">
            <a href="{{ route('plans.index') }}" class="inline-flex items-center gap-2 text-sm sm:text-base font-extrabold text-slate-500 transition hover:text-blue-600 dark:text-slate-400 dark:hover:text-blue-400" title="Back to plans">
                <i class="ph-bold ph-arrow-left text-base sm:text-lg"></i>
                <span>Back to Plans</span>
            </a>
        </div>

        {{-- Unified Professional Checkout Card (Full Screen Mobile Standard) --}}
        <div class="w-full rounded-2xl sm:rounded-3xl border border-slate-200/90 bg-white shadow-xl shadow-slate-200/50 dark:border-slate-800 dark:bg-slate-900 dark:shadow-none overflow-hidden">
            
            {{-- 1. Card Header: Plan & Price Banner --}}
            <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-700 p-5 sm:p-7 text-white relative overflow-hidden">
                <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
                <div class="flex items-start justify-between gap-4 relative z-10">
                    <div class="flex items-center gap-3.5">
                        <span class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-white/20 backdrop-blur-md text-white shadow-inner">
                            <i class="ph-bold ph-crown text-2xl"></i>
                        </span>
                        <div>
                            <span class="text-[11px] font-black uppercase tracking-wider text-blue-200">Selected Plan</span>
                            <h1 class="text-2xl font-black tracking-tight text-white leading-tight">{{ $plan->name }}</h1>
                            <p class="text-xs font-bold text-blue-100 mt-0.5">
                                {{ $billingPeriod === 'yearly' ? 'Annual (Buy)' : 'Monthly (Rent)' }} · {{ $billing['duration_days'] }} Days Access
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-[11px] font-bold text-blue-200 block uppercase tracking-wider">Amount</span>
                        <span class="text-2xl sm:text-3xl font-black text-white leading-tight">
                            ₹{{ number_format($billing['final'], 2) }}
                        </span>
                    </div>
                </div>

                {{-- Plan Benefits list --}}
                <div class="mt-4 pt-4 border-t border-white/20 grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs font-semibold text-blue-50">
                    <div class="flex items-center gap-2">
                        <i class="ph-bold ph-check-circle text-emerald-300 text-base shrink-0"></i>
                        <span><strong>{{ $plan->contact_limit }}</strong> Direct Owner Contact Unlocks</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="ph-bold ph-check-circle text-emerald-300 text-base shrink-0"></i>
                        <span>Zero Brokerage Forever</span>
                    </div>
                    @foreach(array_slice($plan->features ?? [], 0, 2) as $feature)
                        <div class="flex items-center gap-2">
                            <i class="ph-bold ph-check-circle text-emerald-300 text-base shrink-0"></i>
                            <span>{{ $feature }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- 2. Form Body --}}
            <div class="p-6 sm:p-7 space-y-6">
                
                {{-- User Profile Pill --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                            Account Details
                        </label>
                        <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600 dark:text-emerald-400">
                            <i class="ph-bold ph-check-circle"></i> Logged In
                        </span>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50/80 p-3.5 flex items-center justify-between gap-3 dark:border-slate-800 dark:bg-slate-850">
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-slate-900 dark:text-white truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <span class="h-8 w-8 rounded-xl bg-blue-100 dark:bg-blue-950 text-blue-600 dark:text-blue-400 font-black text-xs flex items-center justify-center shrink-0">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>
                    </div>
                </div>

                {{-- Mobile Number Input --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="checkout_user_phone" class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                            Mobile Number <span class="text-red-500">*</span>
                        </label>
                        @if(!empty($userCleanPhone))
                            <span id="phone_sync_badge" class="inline-flex items-center gap-1 text-xs font-extrabold text-emerald-600 dark:text-emerald-400">
                                <i class="ph-bold ph-check-circle"></i> Synced from profile
                            </span>
                        @else
                            <span id="phone_sync_badge" class="inline-flex items-center gap-1 text-xs font-extrabold text-blue-600 dark:text-blue-400">
                                <i class="ph-bold ph-sparkle"></i> Auto-saves to profile
                            </span>
                        @endif
                    </div>
                    
                    <div class="relative flex items-center">
                        <div class="absolute left-4 flex items-center gap-1.5 text-base font-black text-slate-800 dark:text-slate-200 select-none">
                            <span>🇮🇳 +91</span>
                            <span class="h-6 w-px bg-slate-300 dark:bg-slate-700 ml-2"></span>
                        </div>
                        <input
                            type="tel"
                            id="checkout_user_phone"
                            name="phone"
                            value="{{ $userCleanPhone ?? '' }}"
                            maxlength="10"
                            placeholder="Enter 10-digit mobile number"
                            class="w-full rounded-2xl border-2 border-slate-300 bg-white pl-28 pr-12 py-3.5 sm:py-4 text-base sm:text-lg font-black tracking-wider text-slate-950 placeholder-slate-400 outline-none transition focus:border-blue-600 focus:ring-4 focus:ring-blue-500/15 dark:border-slate-700 dark:bg-slate-800 dark:text-white dark:focus:border-blue-400 shadow-xs"
                            autocomplete="tel-national"
                            inputmode="numeric"
                        >
                        <span id="phone_valid_icon" class="absolute right-4 text-emerald-500 text-2xl transition-opacity duration-200 {{ !empty($userCleanPhone) ? 'opacity-100' : 'opacity-0' }}">
                            <i class="ph-bold ph-check-circle"></i>
                        </span>
                    </div>
                    <p id="phone_error_text" class="mt-2 hidden text-xs sm:text-sm font-extrabold text-red-600">
                        <i class="ph-bold ph-warning-circle"></i> Please enter a valid 10-digit Indian mobile number.
                    </p>
                </div>

                {{-- Payment Methods Summary (Clean Trust Strip) --}}
                <div class="rounded-2xl border border-slate-200/90 bg-slate-50/70 p-4 dark:border-slate-800 dark:bg-slate-850">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-extrabold uppercase tracking-wider text-slate-600 dark:text-slate-300 flex items-center gap-1.5">
                            <i class="ph-bold ph-shield-check text-blue-600 text-base"></i> Supported Payment Methods
                        </span>
                        <span class="text-[10px] font-black uppercase tracking-wider bg-blue-100 dark:bg-blue-950 text-blue-700 dark:text-blue-300 px-2.5 py-0.5 rounded-full">
                            RBI Verified
                        </span>
                    </div>
                    <div class="grid grid-cols-4 gap-2 text-center text-[11px] font-bold text-slate-700 dark:text-slate-300">
                        <div class="bg-white dark:bg-slate-800 p-2 rounded-xl border border-slate-200/80 dark:border-slate-700">
                            <i class="ph-bold ph-qr-code text-blue-600 text-lg block mb-0.5"></i>
                            <span>UPI / QR</span>
                        </div>
                        <div class="bg-white dark:bg-slate-800 p-2 rounded-xl border border-slate-200/80 dark:border-slate-700">
                            <i class="ph-bold ph-credit-card text-blue-600 text-lg block mb-0.5"></i>
                            <span>Cards</span>
                        </div>
                        <div class="bg-white dark:bg-slate-800 p-2 rounded-xl border border-slate-200/80 dark:border-slate-700">
                            <i class="ph-bold ph-bank text-blue-600 text-lg block mb-0.5"></i>
                            <span>NetBanking</span>
                        </div>
                        <div class="bg-white dark:bg-slate-800 p-2 rounded-xl border border-slate-200/80 dark:border-slate-700">
                            <i class="ph-bold ph-wallet text-blue-600 text-lg block mb-0.5"></i>
                            <span>Wallets</span>
                        </div>
                    </div>
                </div>

                {{-- Price Breakdown & Total --}}
                <div class="space-y-2 pt-2 text-sm">
                    <div class="flex justify-between text-slate-500 dark:text-slate-400">
                        <span>Plan Price ({{ $billingPeriod === 'yearly' ? '365 Days' : $billing['duration_days'] . ' Days' }})</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200">₹{{ number_format($billing['subtotal'], 2) }}</span>
                    </div>

                    @if($billing['discount'] > 0)
                        <div class="flex justify-between text-emerald-600 dark:text-emerald-400 font-bold">
                            <span>Special Discount</span>
                            <span>- ₹{{ number_format($billing['discount'], 2) }}</span>
                        </div>
                    @endif

                    @if($billing['gst'] > 0)
                        <div class="flex justify-between text-slate-500 dark:text-slate-400">
                            <span>GST ({{ $billing['gst_rate'] }}%)</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200">₹{{ number_format($billing['gst'], 2) }}</span>
                        </div>
                    @endif

                    <div class="pt-3 border-t border-slate-200 dark:border-slate-800 flex items-baseline justify-between">
                        <span class="text-base font-black text-slate-900 dark:text-white uppercase tracking-wider">Total Amount</span>
                        <span class="text-3xl font-black text-blue-600 dark:text-blue-400">
                            ₹{{ number_format($billing['final'], 2) }}
                        </span>
                    </div>
                </div>

                {{-- The SINGLE Master Action Button --}}
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
                        class="w-full flex items-center justify-center gap-3 rounded-2xl bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-700 py-4 sm:py-5 px-6 text-lg sm:text-xl font-black uppercase tracking-wider text-white shadow-xl shadow-blue-500/30 transition active:scale-[0.98] hover:shadow-blue-500/45 hover:from-blue-500 hover:to-indigo-600 disabled:cursor-not-allowed disabled:opacity-60 cursor-pointer mt-3"
                        {{ !$activeGateway ? 'disabled' : '' }}
                    >
                        <span class="btn-text flex items-center gap-2.5">
                            <i class="ph-bold ph-lightning-fill text-amber-300 text-2xl"></i>
                            <span>Pay ₹{{ number_format($billing['final'], 2) }} · Activate Now</span>
                        </span>
                        <span class="btn-loader hidden items-center gap-2.5">
                            <i class="ph-bold ph-circle-notch animate-spin text-2xl"></i>
                            <span>Connecting to Gateway...</span>
                        </span>
                    </button>
                </form>

                {{-- Trust Footers --}}
                <div class="grid grid-cols-3 gap-2 pt-2 text-center text-[11px] font-extrabold text-slate-500 dark:text-slate-400">
                    <div class="flex items-center justify-center gap-1.5">
                        <i class="ph-bold ph-shield-check text-blue-600 text-base"></i>
                        <span>256-Bit SSL</span>
                    </div>
                    <div class="flex items-center justify-center gap-1.5">
                        <i class="ph-bold ph-lightning text-amber-500 text-base"></i>
                        <span>Instant Unlocks</span>
                    </div>
                    <div class="flex items-center justify-center gap-1.5">
                        <i class="ph-bold ph-receipt text-emerald-600 text-base"></i>
                        <span>GST Invoice</span>
                    </div>
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
