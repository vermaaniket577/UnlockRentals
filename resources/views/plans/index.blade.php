@extends('layouts.app')

@section('title', 'Premium Subscription Plans - UnlockRentals')
@section('meta_description', 'Choose a premium UnlockRentals subscription plan with instant owner contact unlocks, advanced rental analytics, priority support, and secure payments.')

@push('head')
<style>
    .pricing-shell {
        background:
            radial-gradient(circle at 12% 8%, rgba(37, 99, 235, 0.14), transparent 30%),
            radial-gradient(circle at 88% 16%, rgba(14, 165, 233, 0.11), transparent 28%),
            linear-gradient(180deg, #ffffff 0%, #f8fafc 58%, #ffffff 100%);
    }
    .billing-toggle {
        position: relative;
        display: inline-grid;
        grid-template-columns: 1fr 1fr;
        padding: 5px;
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.84);
        box-shadow: 0 20px 60px rgba(15, 23, 42, 0.08);
    }
    .billing-toggle::before {
        content: "";
        position: absolute;
        inset: 5px auto 5px 5px;
        width: calc(50% - 5px);
        border-radius: 999px;
        background: #0f172a;
        transition: transform .28s ease;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.24);
    }
    .billing-toggle.yearly::before { transform: translateX(100%); }
    .billing-option {
        position: relative;
        z-index: 1;
        min-width: 132px;
        padding: 11px 18px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 800;
        color: #64748b;
        transition: color .25s ease;
    }
    .billing-option.active { color: #ffffff; }
    .premium-plan {
        position: relative;
        overflow: hidden;
        border-radius: 24px;
        background: linear-gradient(#ffffff, #ffffff) padding-box, var(--plan-ring) border-box;
        border: 1px solid transparent;
        box-shadow: 0 22px 70px rgba(15, 23, 42, 0.08);
        transition: transform .28s ease, box-shadow .28s ease;
    }
    .premium-plan::before {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(120deg, transparent 24%, rgba(255,255,255,.72) 42%, transparent 58%);
        transform: translateX(-120%);
        transition: transform .8s ease;
        pointer-events: none;
    }
    .premium-plan:hover {
        transform: translateY(-8px);
        box-shadow: 0 30px 90px rgba(15, 23, 42, 0.14), 0 0 42px var(--plan-glow);
    }
    .premium-plan:hover::before { transform: translateX(120%); }
    .premium-plan.recommended {
        transform: translateY(-10px);
        box-shadow: 0 32px 100px rgba(37, 99, 235, 0.18), 0 0 48px rgba(37, 99, 235, .18);
    }
    .plan-icon {
        background: var(--plan-soft);
        color: var(--plan-accent);
        box-shadow: inset 0 0 0 1px rgba(255,255,255,.7), 0 14px 30px var(--plan-glow);
    }
    .feature-check {
        background: color-mix(in srgb, var(--plan-accent) 12%, white);
        color: var(--plan-accent);
    }
    .comparison-card {
        background: rgba(255,255,255,.78);
        border: 1px solid rgba(15, 23, 42, .08);
        box-shadow: 0 24px 70px rgba(15, 23, 42, .08);
        backdrop-filter: blur(18px);
    }
    .dark .pricing-shell {
        background:
            radial-gradient(circle at 12% 8%, rgba(59, 130, 246, 0.18), transparent 32%),
            radial-gradient(circle at 88% 16%, rgba(20, 184, 166, 0.14), transparent 30%),
            linear-gradient(180deg, #050816 0%, #0f172a 62%, #050816 100%);
    }
</style>
@endpush

@section('content')
@php
    $paymentFailedReason = session('payment_failed_reason') ?: (request()->boolean('payment_failed') ? request('reason', 'Payment failed. Please try again or choose another payment method.') : null);
    $planStyles = [
        'silver' => ['icon' => 'ph-sparkle', 'ring' => 'linear-gradient(135deg,#cbd5e1,#60a5fa,#e2e8f0)', 'accent' => '#475569', 'soft' => '#f1f5f9', 'glow' => 'rgba(71,85,105,.18)'],
        'gold' => ['icon' => 'ph-crown', 'ring' => 'linear-gradient(135deg,#f59e0b,#fde68a,#2563eb)', 'accent' => '#d97706', 'soft' => '#fffbeb', 'glow' => 'rgba(217,119,6,.24)'],
        'platinum' => ['icon' => 'ph-lightning', 'ring' => 'linear-gradient(135deg,#2563eb,#7c3aed,#06b6d4)', 'accent' => '#2563eb', 'soft' => '#eff6ff', 'glow' => 'rgba(37,99,235,.24)'],
        'enterprise' => ['icon' => 'ph-buildings', 'ring' => 'linear-gradient(135deg,#0f172a,#14b8a6,#64748b)', 'accent' => '#0f766e', 'soft' => '#ecfeff', 'glow' => 'rgba(15,118,110,.22)'],
    ];
    $displayPlans = $plans->values();
    $hasEnterprise = $displayPlans->contains(fn($item) => str_contains(strtolower($item->name), 'enterprise'));
@endphp

<section class="pricing-shell relative overflow-hidden pt-28 pb-20 lg:pt-36" id="plans-page">
    <div class="absolute inset-x-0 top-20 mx-auto h-px max-w-5xl bg-gradient-to-r from-transparent via-blue-400/40 to-transparent"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-3xl text-center">
            <div class="inline-flex items-center gap-2 rounded-full border border-blue-200/80 bg-white/80 px-4 py-2 text-xs font-extrabold uppercase tracking-[0.18em] text-blue-700 shadow-sm">
                <i class="ph-bold ph-shield-check"></i>
                Secure premium billing
            </div>
            <h1 class="mt-6 text-4xl font-black tracking-tight text-slate-950 sm:text-5xl lg:text-6xl">
                UnlockRentals Premium
                <span class="block bg-gradient-to-r from-blue-600 via-sky-500 to-teal-500 bg-clip-text text-transparent">built for faster deal flow.</span>
            </h1>
            <p class="mx-auto mt-5 max-w-2xl text-base leading-7 text-slate-600">
                Choose a plan, pay securely, and activate premium owner-contact unlocks, rental intelligence, priority support, and profile identity upgrades instantly.
            </p>

            <div class="mt-8 flex flex-col items-center gap-3">
                <div class="billing-toggle" id="billing-toggle" role="group" aria-label="Billing period">
                    <button type="button" class="billing-option active flex items-center justify-center gap-1.5" data-billing-choice="monthly">
                        <i class="ph-bold ph-house-line"></i>
                        <span>Rental Pass</span>
                    </button>
                    <button type="button" class="billing-option flex items-center justify-center gap-1.5" data-billing-choice="yearly">
                        <i class="ph-bold ph-buildings"></i>
                        <span>Buyer Pass</span>
                    </button>
                </div>
                <p class="text-xs font-semibold text-emerald-600">Save 20% with Buyer Pass. GST included at checkout.</p>
            </div>
        </div>

        @if(session('success') || session('error') || $errors->has('payment_reference'))
            <div class="mx-auto mt-10 max-w-3xl rounded-2xl border {{ session('error') || $errors->has('payment_reference') ? 'border-red-200 bg-red-50 text-red-700' : 'border-emerald-200 bg-emerald-50 text-emerald-700' }} px-5 py-4 text-sm font-semibold shadow-sm">
                {{ session('error') ?? session('success') ?? $errors->first('payment_reference') }}
            </div>
        @endif

        @auth
            @if($activePlan)
                <div class="mx-auto mt-10 max-w-4xl rounded-3xl border border-emerald-200 bg-white/82 p-5 shadow-xl shadow-emerald-500/10 backdrop-blur-xl">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-center gap-4">
                            <div class="grid h-12 w-12 place-items-center rounded-2xl bg-emerald-50 text-emerald-600">
                                <i class="ph-bold ph-check-circle text-2xl"></i>
                            </div>
                            <div>
                                <p class="text-sm font-black text-slate-950">Active premium plan: {{ $activePlan->plan->name ?? 'Premium Plan' }}</p>
                                <p class="text-xs text-slate-500">{{ $activePlan->remaining_contacts }} unlocks left. Valid until {{ $activePlan->expires_at->format('M d, Y') }}.</p>
                            </div>
                        </div>
                        <span class="rounded-full bg-emerald-600 px-4 py-2 text-[11px] font-black uppercase tracking-widest text-white">Activated</span>
                    </div>
                </div>
            @elseif($pendingPlan)
                <div class="mx-auto mt-10 max-w-4xl rounded-3xl border border-amber-200 bg-white/82 p-5 shadow-xl shadow-amber-500/10 backdrop-blur-xl">
                    <div class="flex items-center gap-4">
                        <div class="grid h-12 w-12 place-items-center rounded-2xl bg-amber-50 text-amber-600">
                            <i class="ph-bold ph-clock text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-black text-slate-950">Payment review pending: {{ $pendingPlan->plan->name ?? 'Premium Plan' }}</p>
                            <p class="text-xs text-slate-500">Admin verification is in progress. Your plan activates once approved.</p>
                        </div>
                    </div>
                </div>
            @endif
        @endauth

        <div class="mt-14 grid grid-cols-1 gap-6 lg:grid-cols-4">
            @foreach($displayPlans as $plan)
                @php
                    $key = str_contains(strtolower($plan->name), 'gold') ? 'gold' : (str_contains(strtolower($plan->name), 'platinum') ? 'platinum' : 'silver');
                    $style = $planStyles[$key];
                    $monthlyOffer = isset($userOffers) ? $userOffers->where('plan_id', $plan->id)->where('billing_period', 'monthly')->first() : null;
                    $yearlyOffer = isset($userOffers) ? $userOffers->where('plan_id', $plan->id)->where('billing_period', 'yearly')->first() : null;
                    $monthly = ($monthlyOffer && $monthlyOffer->discounted_price !== null) ? (float) $monthlyOffer->discounted_price : (float) $plan->price;
                    $yearly = ($yearlyOffer && $yearlyOffer->discounted_price !== null) ? (float) $yearlyOffer->discounted_price : round((float) $plan->price * 12 * 0.8);
                    $isRecommended = $key === 'gold';
                @endphp
                <article class="premium-plan flex flex-col p-6 {{ $isRecommended ? 'recommended' : '' }}"
                    style="--plan-ring: {{ $style['ring'] }}; --plan-accent: {{ $style['accent'] }}; --plan-soft: {{ $style['soft'] }}; --plan-glow: {{ $style['glow'] }};"
                    data-monthly="{{ $monthly }}"
                    data-yearly="{{ $yearly }}"
                    data-duration="{{ $plan->duration_days }}">
                    <div class="flex items-center justify-between gap-3">
                        <div class="plan-icon grid h-12 w-12 place-items-center rounded-2xl">
                            <i class="ph-bold {{ $style['icon'] }} text-2xl"></i>
                        </div>
                        @if($isRecommended)
                            <span class="rounded-full bg-amber-500 px-3 py-1 text-[11px] font-black uppercase tracking-widest text-slate-950 shadow-md">Most Popular</span>
                        @endif
                    </div>
                    <h2 class="mt-5 text-xl font-black text-slate-950">{{ $plan->name }}</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-500">{{ $plan->description }}</p>
                    <div class="mt-6 flex items-baseline gap-2">
                        <span class="text-xs font-black uppercase text-slate-400">Rs.</span>
                        <strong class="plan-price text-4xl font-black text-slate-950">{{ number_format($monthly, 0) }}</strong>
                        <span class="plan-period text-sm font-bold text-slate-500">/rent</span>
                    </div>
                    <p class="yearly-note mt-1 hidden text-xs font-extrabold text-emerald-600">Save 20% · 365 days access</p>
                    <div class="mt-4 flex items-center gap-2 text-xs font-extrabold text-slate-500">
                        <i class="ph-bold ph-clock text-blue-600"></i>
                        <span class="duration-label">{{ $plan->duration_days }} days validity</span>
                    </div>
                    <ul class="mt-6 flex flex-1 flex-col gap-3 text-sm font-semibold text-slate-600">
                        <li class="flex items-center gap-3">
                            <span class="feature-check grid h-6 w-6 shrink-0 place-items-center rounded-full"><i class="ph-bold ph-check text-xs"></i></span>
                            <span>{{ $plan->contact_limit }} owner-contact unlocks</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="feature-check grid h-6 w-6 shrink-0 place-items-center rounded-full"><i class="ph-bold ph-check text-xs"></i></span>
                            <span>Direct landlord WhatsApp / Call</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="feature-check grid h-6 w-6 shrink-0 place-items-center rounded-full"><i class="ph-bold ph-check text-xs"></i></span>
                            <span>Priority customer support</span>
                        </li>
                    </ul>
                    <div class="mt-8">
                        @if(auth()->check() && $activePlan && $activePlan->remaining_contacts > 0 && $activePlan->plan_id === $plan->id)
                            <button disabled class="w-full rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-3 text-sm font-black text-emerald-700">Current Plan</button>
                        @elseif(auth()->check() && $activePlan && $activePlan->remaining_contacts > 0 && $activePlan->plan && (float) $plan->price > (float) $activePlan->plan->price)
                            <a href="{{ route('plans.checkout', ['plan' => $plan, 'billing' => 'monthly', 'direct' => 1]) }}" data-checkout-url="{{ route('plans.checkout', ['plan' => $plan, 'billing' => 'monthly', 'direct' => 1]) }}" class="choose-plan-btn plan-checkout-link flex items-center justify-center gap-2 w-full rounded-2xl bg-blue-600 px-5 py-3.5 text-center text-sm font-black text-white shadow-xl shadow-blue-500/25 transition hover:-translate-y-0.5 hover:bg-blue-700" title="Upgrade Plan">
                                <i class="ph-bold ph-lightning text-base"></i>
                                <span>Pay Now · Upgrade Plan</span>
                            </a>
                        @elseif(auth()->check() && $activePlan && $activePlan->remaining_contacts > 0)
                            <button disabled class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-3 text-sm font-black text-slate-400">Already Subscribed</button>
                        @elseif(auth()->check() && $pendingPlan)
                            <button disabled class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-3 text-sm font-black text-slate-400">Request Pending</button>
                        @else
                            @guest
                                <a href="{{ route('login', ['redirect' => route('plans.checkout', ['plan' => $plan, 'billing' => 'monthly', 'direct' => 1])]) }}"
                                   onclick="event.preventDefault(); event.stopPropagation(); window.openAuthModal('login', this.getAttribute('data-checkout-url') || '{{ route('plans.checkout', ['plan' => $plan, 'billing' => 'monthly', 'direct' => 1]) }}');"
                                   data-checkout-url="{{ route('plans.checkout', ['plan' => $plan, 'billing' => 'monthly', 'direct' => 1]) }}"
                                   data-no-loader="true"
                                   data-ur-loader-skip="true"
                                   class="choose-plan-btn plan-checkout-link flex items-center justify-center gap-2 w-full rounded-2xl {{ $isRecommended ? 'bg-blue-600 hover:bg-blue-700 shadow-blue-500/30' : 'bg-slate-950 hover:bg-slate-900 shadow-slate-950/20' }} px-5 py-3.5 text-center text-sm font-black text-white shadow-xl transition hover:-translate-y-0.5" title="Pay Now &amp; Activate Plan">
                                    <i class="ph-bold ph-lightning-fill text-base text-amber-300"></i>
                                    <span>Pay Now · Instant Access</span>
                                </a>
                            @else
                                <a href="{{ route('plans.checkout', ['plan' => $plan, 'billing' => 'monthly', 'direct' => 1]) }}"
                                   data-checkout-url="{{ route('plans.checkout', ['plan' => $plan, 'billing' => 'monthly', 'direct' => 1]) }}"
                                   class="choose-plan-btn plan-checkout-link flex items-center justify-center gap-2 w-full rounded-2xl {{ $isRecommended ? 'bg-blue-600 hover:bg-blue-700 shadow-blue-500/30' : 'bg-slate-950 hover:bg-slate-900 shadow-slate-950/20' }} px-5 py-3.5 text-center text-sm font-black text-white shadow-xl transition hover:-translate-y-0.5" title="Pay Now &amp; Activate Plan">
                                    <i class="ph-bold ph-lightning-fill text-base text-amber-300"></i>
                                    <span>Pay Now · Instant Access</span>
                                </a>
                            @endguest
                        @endif
                    </div>
                </article>
            @endforeach

            @unless($hasEnterprise)
                @php $style = $planStyles['enterprise']; @endphp
                <article class="premium-plan flex flex-col p-6"
                    style="--plan-ring: {{ $style['ring'] }}; --plan-accent: {{ $style['accent'] }}; --plan-soft: {{ $style['soft'] }}; --plan-glow: {{ $style['glow'] }};"
                    data-monthly="Custom" data-yearly="Custom">
                    <div class="plan-icon grid h-12 w-12 place-items-center rounded-2xl">
                        <i class="ph-bold ph-buildings text-2xl"></i>
                    </div>
                    <h2 class="mt-6 text-xl font-black text-slate-950">Enterprise Plan</h2>
                    <p class="mt-2 min-h-[42px] text-sm leading-6 text-slate-500">For agencies, relocation teams, and high-volume property operations.</p>
                    <div class="mt-7">
                        <div class="flex items-end gap-1">
                            <span class="plan-price text-4xl font-black tracking-tight text-slate-950">Custom</span>
                        </div>
                    </div>
                    <ul class="mt-7 flex-1 space-y-3">
                        <li class="flex items-center gap-3 text-sm text-slate-600">
                            <span class="feature-check grid h-5 w-5 shrink-0 place-items-center rounded-full"><i class="ph-bold ph-check text-xs"></i></span>
                            <span>Unlimited Contact Unlocks</span>
                        </li>
                        <li class="flex items-center gap-3 text-sm text-slate-600">
                            <span class="feature-check grid h-5 w-5 shrink-0 place-items-center rounded-full"><i class="ph-bold ph-check text-xs"></i></span>
                            <span>Multi-user Team Access</span>
                        </li>
                        <li class="flex items-center gap-3 text-sm text-slate-600">
                            <span class="feature-check grid h-5 w-5 shrink-0 place-items-center rounded-full"><i class="ph-bold ph-check text-xs"></i></span>
                            <span>Custom Invoicing & API Integration</span>
                        </li>
                    </ul>
                    <a href="mailto:sales@unlockrentals.com?subject=UnlockRentals%20Enterprise%20Plan" class="mt-8 block w-full rounded-2xl bg-slate-950 px-5 py-3 text-center text-sm font-black text-white shadow-xl shadow-slate-950/15 transition hover:-translate-y-0.5 hover:bg-teal-700" title="Contact Sales">Contact Sales</a>
                </article>
            @endunless
        </div>

        <div class="comparison-card mt-16 rounded-3xl p-6 lg:p-8">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.18em] text-blue-600">Plan comparison</p>
                    <h2 class="mt-2 text-2xl font-black text-slate-950">Everything needed for premium rental decisions</h2>
                </div>
                <div class="flex items-center gap-2 text-xs font-bold text-slate-500">
                    <i class="ph-bold ph-lock-key text-emerald-600"></i>
                    Secure checkout, instant activation, invoice history
                </div>
            </div>
            <div class="mt-7 overflow-x-auto">
                <table class="w-full min-w-[760px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 text-xs uppercase tracking-widest text-slate-400">
                            <th class="py-4">Capability</th>
                            <th class="py-4">Silver</th>
                            <th class="py-4">Gold</th>
                            <th class="py-4">Platinum</th>
                            <th class="py-4">Enterprise</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-600">
                        <tr><td class="py-4 font-bold text-slate-950">Owner contact unlocks</td><td>Starter</td><td>Expanded</td><td>High volume</td><td>Custom</td></tr>
                        <tr><td class="py-4 font-bold text-slate-950">Activation</td><td>Instant</td><td>Instant</td><td>Instant</td><td>Managed</td></tr>
                        <tr><td class="py-4 font-bold text-slate-950">Support</td><td>Email</td><td>Priority</td><td>Dedicated</td><td>SLA</td></tr>
                        <tr><td class="py-4 font-bold text-slate-950">Premium identity badge</td><td>Silver</td><td>Gold</td><td>Platinum</td><td>Enterprise</td></tr>
                        <tr><td class="py-4 font-bold text-slate-950">Analytics widgets</td><td>Basic</td><td>Advanced</td><td>Premium</td><td>Custom dashboards</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<x-subscription.payment-failed-modal :payment-failed-reason="$paymentFailedReason" />
@endsection

@push('scripts')
<script>
(() => {
    const toggle = document.getElementById('billing-toggle');
    const buttons = document.querySelectorAll('[data-billing-choice]');
    const cards = document.querySelectorAll('.premium-plan');

    function setBilling(period) {
        if (toggle) toggle.classList.toggle('yearly', period === 'yearly');
        buttons.forEach(button => button.classList.toggle('active', button.dataset.billingChoice === period));

        cards.forEach(card => {
            const price = period === 'yearly' ? card.dataset.yearly : card.dataset.monthly;
            const priceEl = card.querySelector('.plan-price');
            const periodEl = card.querySelector('.plan-period');
            const note = card.querySelector('.yearly-note');
            const duration = card.querySelector('.duration-label');
            const checkoutLink = card.querySelector('.plan-checkout-link');

            if (priceEl && price !== 'Custom') priceEl.textContent = Number(price).toLocaleString('en-IN');
            if (periodEl) periodEl.textContent = period === 'yearly' ? '/buy' : '/rent';
            if (note) note.classList.toggle('hidden', period !== 'yearly');
            if (duration) duration.textContent = period === 'yearly' ? '365 days' : `${card.dataset.duration || 30} days`;

            if (checkoutLink) {
                const rawUrl = checkoutLink.getAttribute('data-checkout-url') || checkoutLink.href;
                const url = new URL(rawUrl, window.location.origin);
                url.searchParams.set('billing', period);
                url.searchParams.set('direct', '1');
                const finalHref = url.pathname + url.search;
                
                checkoutLink.setAttribute('data-checkout-url', finalHref);
                if (checkoutLink.hasAttribute('onclick')) {
                    checkoutLink.href = '/login?redirect=' + encodeURIComponent(finalHref);
                } else {
                    checkoutLink.href = finalHref;
                }
            }
        });
    }

    buttons.forEach(button => button.addEventListener('click', () => setBilling(button.dataset.billingChoice)));

    document.querySelectorAll('.plan-checkout-link').forEach(link => {
        link.addEventListener('click', () => {
            if (!link.hasAttribute('onclick')) {
                link.style.pointerEvents = 'none';
                link.style.opacity = '0.7';
                link.innerHTML = '<i class="ph-bold ph-circle-notch animate-spin" style="margin-right:6px"></i> Opening secure checkout...';
            }
        });
    });
})();
</script>
@endpush
