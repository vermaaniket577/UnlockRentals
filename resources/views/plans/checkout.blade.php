@extends('layouts.app')

@section('title', 'Secure Premium Checkout - UnlockRentals')
@section('robots', 'noindex, follow')

@push('head')
<style>
    .checkout-stage {
        background:
            radial-gradient(circle at 10% 6%, rgba(37, 99, 235, .14), transparent 30%),
            radial-gradient(circle at 78% 18%, rgba(20, 184, 166, .13), transparent 28%),
            linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
    }
    .dark .checkout-stage {
        background:
            radial-gradient(circle at 10% 6%, rgba(59, 130, 246, .2), transparent 30%),
            radial-gradient(circle at 78% 18%, rgba(20, 184, 166, .16), transparent 28%),
            linear-gradient(180deg, #020617 0%, #0f172a 100%);
    }
    .method-card {
        border: 1px solid rgba(15, 23, 42, .08);
        background: rgba(255,255,255,.86);
        box-shadow: 0 18px 50px rgba(15, 23, 42, .06);
        transition: transform .24s ease, box-shadow .24s ease, border-color .24s ease;
    }
    .method-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 24px 70px rgba(37, 99, 235, .13);
    }
    .method-card.selected {
        border-color: rgba(37, 99, 235, .72);
        box-shadow: 0 24px 80px rgba(37, 99, 235, .18), 0 0 0 4px rgba(37, 99, 235, .08);
        background: linear-gradient(180deg, rgba(239,246,255,.96), rgba(255,255,255,.94));
    }
    .summary-panel {
        background: rgba(255,255,255,.82);
        border: 1px solid rgba(15, 23, 42, .08);
        box-shadow: 0 28px 90px rgba(15, 23, 42, .1);
        backdrop-filter: blur(20px);
    }
    .processing-particle {
        position: absolute;
        width: 8px;
        height: 8px;
        border-radius: 999px;
        background: linear-gradient(135deg, #2563eb, #14b8a6);
        opacity: .55;
        animation: checkoutFloat 3.4s ease-in-out infinite;
    }
    @keyframes checkoutFloat {
        0%, 100% { transform: translateY(0) scale(1); opacity: .25; }
        50% { transform: translateY(-34px) scale(1.4); opacity: .9; }
    }
    .secure-ring {
        background: conic-gradient(from 180deg, #2563eb, #14b8a6, #7c3aed, #2563eb);
        animation: spin 1.2s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    @media (min-width: 1024px) {
        .checkout-stage {
            min-height: calc(100vh - 72px);
            padding-top: 5.25rem;
            padding-bottom: 1.5rem;
        }
        .checkout-hero {
            margin-bottom: 1rem;
        }
        .checkout-hero-copy,
        .checkout-trust-row {
            display: none;
        }
        .checkout-main-grid {
            gap: 1rem;
            grid-template-columns: minmax(0, 1fr) 360px;
        }
        .checkout-left {
            gap: .875rem;
        }
        .checkout-card {
            border-radius: 1.25rem;
            padding: 1rem;
        }
        .checkout-plan-card {
            padding: .9rem 1rem;
        }
        .checkout-method-grid {
            gap: .75rem;
        }
        .method-card {
            min-height: 74px;
            border-radius: 1rem;
            padding: .85rem;
        }
        .method-card:hover {
            transform: translateY(-2px);
        }
        .method-card .method-icon {
            width: 2.4rem;
            height: 2.4rem;
            border-radius: .85rem;
        }
        .method-card .method-copy {
            font-size: .76rem;
            line-height: 1.2rem;
        }
        .summary-panel {
            top: 5.25rem;
            border-radius: 1.25rem;
            padding: 1.1rem;
        }
        .summary-rows {
            margin-top: 1rem;
            gap: .72rem;
        }
        .summary-badges,
        .summary-note {
            margin-top: .9rem;
        }
        .summary-note {
            padding: .85rem;
            font-size: .82rem;
            line-height: 1.35rem;
        }
        .checkout-submit-card {
            padding: 1rem;
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
        $subtotal = (float) $plan->price * $months;
        $offerSubtotal = $effectivePrice * $months;
        $yearlyDiscount = $billingPeriod === 'yearly' ? round($offerSubtotal * 0.20, 2) : 0;
        $discount = max(0, $subtotal - $offerSubtotal) + $yearlyDiscount;
        $taxable = max(0, $offerSubtotal - $yearlyDiscount);
        $gstRate = (float) ($site_settings['gst_rate'] ?? 18);
        $gst = round($taxable * ($gstRate / 100), 2);

        $billing = [
            'period' => $billingPeriod,
            'duration_days' => $durationDays,
            'subtotal' => round($subtotal, 2),
            'discount' => round($discount, 2),
            'gst' => $gst,
            'gst_rate' => $gstRate,
            'final' => max(1.00, round($taxable + $gst, 2)),
            'yearly_savings' => $yearlyDiscount,
        ];
    }
@endphp
@php
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

<section class="checkout-stage relative min-h-screen overflow-hidden pt-24 pb-10 lg:pt-24" id="checkout-page">
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="checkout-hero mb-6 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-3xl">
                <a href="{{ route('plans.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 transition hover:text-blue-600">
                    <i class="ph-bold ph-arrow-left"></i>
                    Back to plans
                </a>
                <p class="mt-4 text-xs font-black uppercase tracking-[0.2em] text-blue-600">Premium checkout</p>
                <h1 class="mt-2 text-3xl font-black tracking-tight text-slate-950 lg:text-4xl">Choose how you want to pay</h1>
                <p class="checkout-hero-copy mt-3 max-w-2xl text-base leading-7 text-slate-600">Your subscription activates instantly after verified Razorpay payment. Manual methods are stored securely for admin review.</p>
            </div>
            <div class="checkout-trust-row flex flex-wrap items-center gap-3 text-xs font-bold text-slate-500">
                <span class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-4 py-2 text-emerald-700"><i class="ph-bold ph-shield-check"></i> SSL secured</span>
                <span class="inline-flex items-center gap-2 rounded-full border border-blue-200 bg-blue-50 px-4 py-2 text-blue-700"><i class="ph-bold ph-receipt"></i> Invoice generated</span>
                <span class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2"><i class="ph-bold ph-lock-key"></i> No card storage</span>
            </div>
        </div>

        <div class="checkout-main-grid grid grid-cols-1 gap-6 lg:grid-cols-[1fr_390px]">
            <div class="checkout-left flex flex-col gap-5">
                <div class="checkout-card checkout-plan-card rounded-3xl border border-slate-200/70 bg-white/82 p-5 shadow-xl shadow-slate-950/5 backdrop-blur-xl dark:border-slate-700/70 dark:bg-slate-900/82">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-400">Selected plan</p>
                            <h2 class="mt-1 text-xl font-black text-slate-950 lg:text-2xl dark:text-white">{{ $plan->name }}</h2>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $billingPeriod === 'yearly' ? 'Buy' : 'Rent' }} plan · {{ $billing['duration_days'] }} days of premium access</p>
                        </div>
                        <div class="rounded-2xl bg-slate-950 px-4 py-2.5 text-right text-white dark:bg-gradient-to-r dark:from-blue-600 dark:to-teal-500">
                            <p class="text-[10px] font-black uppercase tracking-widest text-white/60">Final amount</p>
                            <p class="text-2xl font-black">Rs. {{ number_format($billing['final'], 2) }}</p>
                        </div>
                    </div>
                    <ul class="mt-5 grid gap-2 sm:grid-cols-2">
                        <li class="flex items-center gap-2 rounded-xl bg-slate-50 px-3 py-2 text-sm text-slate-600 dark:bg-slate-800/80 dark:text-slate-300">
                            <i class="ph-bold ph-check-circle text-emerald-500"></i>
                            <span><strong class="text-slate-950 dark:text-white">{{ $plan->contact_limit }}</strong> owner-contact unlocks</span>
                        </li>
                        @foreach(($plan->features ?? []) as $feature)
                            <li class="flex items-center gap-2 rounded-xl bg-slate-50 px-3 py-2 text-sm text-slate-600 dark:bg-slate-800/80 dark:text-slate-300">
                                <i class="ph-bold ph-check-circle text-emerald-500"></i>
                                <span>{{ $feature }}</span>
                            </li>
                        @endforeach
                        <li class="flex items-center gap-2 rounded-xl bg-slate-50 px-3 py-2 text-sm text-slate-600 dark:bg-slate-800/80 dark:text-slate-300">
                            <i class="ph-bold ph-lightning text-blue-500"></i>
                            <span>Instant activation after verified payment</span>
                        </li>
                    </ul>
                </div>

                <div id="payment-methods" class="checkout-card rounded-3xl border border-slate-200/70 bg-white/70 p-5 shadow-xl shadow-slate-950/5 backdrop-blur-xl dark:border-slate-700/70 dark:bg-slate-900/70">
                    <div class="mb-4 flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs font-black uppercase tracking-[0.18em] text-blue-600">Payment methods</p>
                            <h2 class="mt-1 text-xl font-black text-slate-950">Select a secure payment option</h2>
                        </div>
                        <span class="hidden rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-500 sm:inline-flex">Powered by {{ $activeGateway['name'] ?? 'UnlockRentals Billing' }}</span>
                    </div>

                    <div class="checkout-method-grid grid grid-cols-1 gap-4 md:grid-cols-2">
                        @foreach($methods as $method)
                            <button type="button" class="method-card rounded-2xl p-5 text-left {{ $loop->first ? 'selected' : '' }}" data-method="{{ $method['id'] }}">
                                <div class="flex items-start gap-4">
                                    <span class="method-icon grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-blue-50 text-blue-600">
                                        <i class="ph-bold {{ $method['icon'] }} text-2xl"></i>
                                    </span>
                                    <span class="min-w-0">
                                        <span class="block text-base font-black text-slate-950">{{ $method['name'] }}</span>
                                        <span class="method-copy mt-1 block text-sm leading-5 text-slate-500">{{ $method['copy'] }}</span>
                                    </span>
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>

                @if(!$isRazorpay && $activeGateway)
                    <div class="rounded-3xl border border-amber-200 bg-amber-50/70 p-5">
                        <div class="flex gap-4">
                            <span class="grid h-11 w-11 shrink-0 place-items-center rounded-2xl bg-white text-amber-600"><i class="ph-bold ph-info text-xl"></i></span>
                            <div class="text-sm leading-6 text-amber-900">
                                <strong>{{ $activeGateway['name'] ?? 'Manual payment' }}</strong> is active. Pay using the configured gateway details, then submit your reference and screenshot below for verification.
                            </div>
                        </div>
                        <div class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-2">
                            @if(!empty($activeGateway['identifier']))
                                <div class="rounded-2xl bg-white/80 p-4">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Payment identifier</p>
                                    <p class="mt-1 text-sm font-black text-slate-950">{{ $activeGateway['identifier'] }}</p>
                                </div>
                            @endif
                            @if(!empty($activeGateway['qr_url']))
                                <div class="rounded-2xl bg-white/80 p-4 text-center">
                                    <img src="{{ $activeGateway['qr_url'] }}" alt="Payment QR" class="mx-auto max-h-44 rounded-xl border border-slate-200 bg-white p-2">
                                </div>
                            @endif
                        </div>
                    </div>
                @elseif(!$activeGateway)
                    <div class="rounded-3xl border border-red-200 bg-red-50 p-5 text-sm font-semibold text-red-700">
                        No active payment gateway is configured. Please contact support.
                    </div>
                @endif

                <form action="{{ route('plans.purchase.process', $plan) }}" method="POST" id="payment-form" enctype="multipart/form-data" class="checkout-submit-card rounded-3xl border border-slate-200/70 bg-white/78 p-5 shadow-xl shadow-slate-950/5 backdrop-blur-xl">
                    @csrf
                    <input type="hidden" name="billing_period" value="{{ $billingPeriod }}">
                    <input type="hidden" name="payment_method" id="payment_method" value="{{ $isRazorpay ? 'razorpay' : 'upi' }}">
                    <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
                    <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
                    <input type="hidden" name="razorpay_signature" id="razorpay_signature">

                    <div class="mb-5 flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-400">Authorization</p>
                            <h2 class="mt-1 text-lg font-black text-slate-950">{{ $isRazorpay ? 'Ready for instant activation' : 'Submit payment proof' }}</h2>
                        </div>
                        <label class="inline-flex cursor-pointer items-center gap-3 rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-xs font-bold text-slate-600">
                            <input type="checkbox" name="auto_renew" value="1" class="h-4 w-4 rounded border-slate-300 text-blue-600">
                            Auto renew
                        </label>
                    </div>

                    @if($activeGateway && !$isRazorpay)
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-[11px] font-black uppercase tracking-[0.16em] text-slate-400">{{ $activeGateway['reference_label'] ?? 'Transaction ID / UTR Number' }}</label>
                                <input type="text" name="payment_reference" value="{{ old('payment_reference') }}" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-950 outline-none transition focus:border-blue-500 focus:bg-white" placeholder="Enter payment reference">
                                @error('payment_reference')<p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="mb-2 block text-[11px] font-black uppercase tracking-[0.16em] text-slate-400">Amount Paid</label>
                                <input type="number" name="amount_paid" value="{{ old('amount_paid', $billing['final']) }}" required step="0.01" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-950 outline-none transition focus:border-blue-500 focus:bg-white">
                                @error('amount_paid')<p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="mb-2 block text-[11px] font-black uppercase tracking-[0.16em] text-slate-400">Payment Screenshot</label>
                                <input type="file" name="payment_proof" accept="image/*" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700 file:mr-4 file:rounded-full file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-xs file:font-black file:text-blue-700">
                                @error('payment_proof')<p class="mt-2 text-xs font-bold text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    @endif

                    <button type="{{ $isRazorpay ? 'button' : 'submit' }}" id="pay-button" class="mt-4 flex w-full items-center justify-center gap-3 rounded-2xl bg-gradient-to-r from-blue-600 to-sky-500 px-6 py-3.5 text-sm font-black uppercase tracking-wider text-white shadow-2xl shadow-blue-500/20 transition hover:-translate-y-0.5 hover:shadow-blue-500/30 disabled:cursor-not-allowed disabled:opacity-60" {{ !$activeGateway ? 'disabled' : '' }}>
                        <span class="btn-text flex items-center gap-2">
                            <i class="ph-bold ph-lock-key"></i>
                            Pay Now
                        </span>
                        <span class="btn-loader hidden items-center gap-2">
                            <i class="ph-bold ph-circle-notch animate-spin"></i>
                            Securely connecting
                        </span>
                    </button>
                </form>

                @if($isRazorpay)
                <div id="manual-verify-section" class="hidden checkout-card rounded-3xl border border-amber-200/80 bg-gradient-to-b from-amber-50/80 to-white/90 p-5 shadow-xl shadow-amber-500/5 backdrop-blur-xl dark:border-amber-700/40 dark:from-amber-950/40 dark:to-slate-900/80">
                    <div class="flex items-start gap-4">
                        <span class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-amber-100 text-amber-600 dark:bg-amber-900/50 dark:text-amber-400">
                            <i class="ph-bold ph-identification-badge text-2xl"></i>
                        </span>
                        <div>
                            <p class="text-xs font-black uppercase tracking-[0.18em] text-amber-600 dark:text-amber-400">Payment Verification</p>
                            <h3 class="mt-1 text-lg font-black text-slate-950 dark:text-white">Already paid? Verify manually</h3>
                            <p class="mt-1 text-sm leading-6 text-slate-500 dark:text-slate-400">If you completed UPI payment on your phone but this screen didn't update, enter your <strong>Razorpay Payment ID</strong> below to activate your plan instantly.</p>
                        </div>
                    </div>
                    <div class="mt-5">
                        <label class="mb-2 block text-[11px] font-black uppercase tracking-[0.16em] text-slate-400">Razorpay Payment ID</label>
                        <div class="flex gap-3">
                            <input type="text" id="manual_razorpay_payment_id" placeholder="pay_xxxxxxxxxxxxx" class="flex-1 rounded-2xl border border-slate-200 bg-white px-4 py-3 font-mono text-sm font-semibold text-slate-950 outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 dark:border-slate-700 dark:bg-slate-800 dark:text-white dark:focus:border-amber-400">
                            <button type="button" id="manual-verify-btn" class="flex items-center gap-2 rounded-2xl bg-gradient-to-r from-amber-500 to-orange-500 px-5 py-3 text-sm font-black text-white shadow-lg shadow-amber-500/20 transition hover:-translate-y-0.5 hover:shadow-amber-500/30">
                                <i class="ph-bold ph-shield-check"></i>
                                Verify & Activate
                            </button>
                        </div>
                        <p id="manual-verify-error" class="mt-2 hidden text-xs font-bold text-red-600"></p>
                        <div class="mt-4 flex items-start gap-2 rounded-xl bg-blue-50 p-3 text-xs leading-5 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                            <i class="ph-bold ph-info mt-0.5 shrink-0"></i>
                            <span>You can find your Payment ID in the payment confirmation SMS/email from Razorpay, or in your UPI app's transaction history. It starts with <strong>pay_</strong></span>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <aside class="summary-panel sticky top-24 h-max rounded-3xl p-6">
                <div class="flex items-center gap-3">
                    <span class="grid h-12 w-12 place-items-center rounded-2xl bg-slate-950 text-white"><i class="ph-bold ph-crown text-2xl"></i></span>
                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.18em] text-slate-400">Live summary</p>
                        <h2 class="text-lg font-black text-slate-950">{{ $plan->name }}</h2>
                    </div>
                </div>
                <div class="summary-rows mt-6 flex flex-col gap-4 text-sm">
                    <div class="flex justify-between gap-4"><span class="text-slate-500">Plan Option</span><strong class="text-slate-950">{{ $billingPeriod === 'yearly' ? 'Buy' : 'Rent' }} / {{ $billing['duration_days'] }} days</strong></div>
                    <div class="flex justify-between gap-4"><span class="text-slate-500">Subtotal</span><strong class="text-slate-950">Rs. {{ number_format($billing['subtotal'], 2) }}</strong></div>
                    <div class="flex justify-between gap-4"><span class="text-slate-500">Discount</span><strong class="text-emerald-600">- Rs. {{ number_format($billing['discount'], 2) }}</strong></div>
                    <div class="flex justify-between gap-4"><span class="text-slate-500">GST ({{ $billing['gst_rate'] ?? (float) ($site_settings['gst_rate'] ?? 18) }}%)</span><strong class="text-slate-950">Rs. {{ number_format($billing['gst'], 2) }}</strong></div>
                    <div class="border-t border-slate-200 pt-4">
                        <div class="flex items-end justify-between gap-4">
                            <span class="text-xs font-black uppercase tracking-widest text-slate-400">Final amount</span>
                            <strong class="text-3xl font-black text-slate-950">Rs. {{ number_format($billing['final'], 2) }}</strong>
                        </div>
                    </div>
                </div>
                <button type="button" id="summary-pay-button" class="mt-5 flex w-full items-center justify-center gap-3 rounded-2xl bg-gradient-to-r from-blue-600 to-sky-500 px-6 py-4 text-sm font-black uppercase tracking-wider text-white shadow-2xl shadow-blue-500/20 transition hover:-translate-y-0.5 hover:shadow-blue-500/30 disabled:cursor-not-allowed disabled:opacity-60" {{ !$activeGateway ? 'disabled' : '' }}>
                    <i class="ph-bold ph-lock-key"></i>
                    {{ $isRazorpay ? 'Pay Now' : (!empty($activeGateway['payment_link']) ? 'Open Payment Page' : 'Submit Payment') }}
                </button>

                <div class="summary-note mt-6 rounded-2xl bg-emerald-50 p-4 text-sm font-semibold leading-6 text-emerald-800">
                    <i class="ph-bold ph-lightning"></i>
                    Instant activation after successful verified payment. Premium features unlock immediately.
                </div>
                <div class="summary-badges mt-5 grid grid-cols-3 gap-2 text-center text-[11px] font-black uppercase tracking-wider text-slate-500">
                    <div class="rounded-2xl bg-slate-50 p-3"><i class="ph-bold ph-shield-check block text-lg text-blue-600"></i>Secure</div>
                    <div class="rounded-2xl bg-slate-50 p-3"><i class="ph-bold ph-receipt block text-lg text-blue-600"></i>Invoice</div>
                    <div class="rounded-2xl bg-slate-50 p-3"><i class="ph-bold ph-sparkle block text-lg text-blue-600"></i>Premium</div>
                </div>
            </aside>
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
        userPrefill: {
            name: @json(auth()->user()->name),
            email: @json(auth()->user()->email),
            contact: @json(!empty(trim(auth()->user()->phone ?? '')) ? auth()->user()->phone : '9876543210'),
        },
        manualPaymentLink: @json($activeGateway['payment_link'] ?? null),
    });
});
</script>
@endpush
