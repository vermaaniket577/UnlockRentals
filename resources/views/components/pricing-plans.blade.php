{{-- ============================================================
     UNLOCK RENTALS — PRICING PLANS (HOMEPAGE PREVIEW)
     ============================================================ --}}

<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js" defer></script>

<style>
.ur-plans {
    background: linear-gradient(180deg, #f8fafc 0%, #ffffff 50%, #f8fafc 100%);
    padding: 4.5rem 0 5rem;
    position: relative;
    overflow: hidden;
    font-family: 'Outfit', 'Inter', sans-serif;
}

.ur-plans__accent {
    position: absolute;
    width: 36rem;
    height: 36rem;
    background: radial-gradient(circle, rgba(37, 99, 235, 0.05) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
    z-index: 0;
}
.ur-plans__accent--1 { top: -8%; right: 0; transform: translateX(10%); }
.ur-plans__accent--2 { bottom: -8%; left: 0; transform: translateX(-10%); }

.ur-plans__container {
    max-width: 80rem;
    margin: 0 auto;
    padding: 0 1.25rem;
    position: relative;
    z-index: 10;
}

.ur-plans__header {
    text-align: center;
    max-width: 42rem;
    margin: 0 auto 2.5rem;
}

.ur-plans__eyebrow {
    font-size: 0.72rem;
    font-weight: 800;
    color: #2563eb;
    text-transform: uppercase;
    letter-spacing: 0.25em;
    margin-bottom: 0.75rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(37, 99, 235, 0.08);
    padding: 0.35rem 0.9rem;
    border-radius: 9999px;
}

.ur-plans__title {
    font-size: 2rem;
    font-weight: 900;
    color: #0f172a;
    letter-spacing: -0.03em;
    line-height: 1.2;
    margin-bottom: 0.75rem;
}

@media (min-width: 768px) {
    .ur-plans__title { font-size: 2.75rem; }
}

.ur-plans__title span {
    background: linear-gradient(135deg, #2563eb, #6366f1);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.ur-plans__subtitle {
    font-size: 0.95rem;
    color: #64748b;
    line-height: 1.55;
    font-weight: 400;
}

/* ─── STANDARD SEGMENTED BILLING TOGGLE ────────────────── */
.ur-billing-switch-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.75rem;
    position: relative;
    z-index: 10;
    width: 100%;
    max-width: 440px;
    padding: 0 0.75rem;
}

.ur-billing-segmented-switch {
    display: flex;
    align-items: center;
    background: #f1f5f9;
    border: 1px solid rgba(15, 23, 42, 0.08);
    border-radius: 9999px;
    padding: 0.35rem;
    width: 100%;
    box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
}

.ur-billing-seg-btn {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
    padding: 0.65rem 0.85rem;
    border-radius: 9999px;
    background: transparent;
    border: none;
    cursor: pointer;
    font-family: inherit;
    font-size: 0.84rem;
    font-weight: 700;
    color: #64748b;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    white-space: nowrap;
    text-align: center;
}

.ur-billing-seg-btn.active {
    background: #ffffff;
    color: #0f172a;
    font-weight: 800;
    box-shadow: 0 4px 14px rgba(15, 23, 42, 0.1);
}

.ur-billing-seg-btn i {
    font-size: 1rem;
    color: #2563eb;
}

.ur-billing-seg-btn.active i {
    color: #2563eb;
}

.ur-billing-seg-btn .ur-seg-sub {
    font-size: 0.74rem;
    font-weight: 600;
    color: #94a3b8;
    margin-left: 2px;
}

.ur-billing-seg-btn.active .ur-seg-sub {
    color: #64748b;
}

.ur-billing-seg-btn .ur-discount-chip {
    background: #10b981;
    color: #ffffff;
    font-size: 0.68rem;
    font-weight: 800;
    padding: 0.15rem 0.45rem;
    border-radius: 9999px;
    margin-left: 0.25rem;
    letter-spacing: 0.02em;
    box-shadow: 0 2px 6px rgba(16, 185, 129, 0.25);
}

@media (max-width: 480px) {
    .ur-billing-seg-btn {
        padding: 0.55rem 0.5rem;
        font-size: 0.78rem;
        gap: 0.25rem;
    }
    .ur-billing-seg-btn i {
        font-size: 0.9rem;
    }
    .ur-billing-seg-btn .ur-seg-sub {
        display: none;
    }
    .ur-billing-seg-btn .ur-discount-chip {
        font-size: 0.62rem;
        padding: 0.1rem 0.35rem;
    }
}

/* ─── INTERACTIVE PLAN TABS (QUICK SWITCHER) ────────────────── */
.ur-plan-tabs {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    margin: 0 auto 1.75rem;
    max-width: 36rem;
    background: #f1f5f9;
    padding: 0.35rem;
    border-radius: 9999px;
    border: 1px solid rgba(15, 23, 42, 0.06);
    position: relative;
    z-index: 10;
}

.ur-plan-tab-btn {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
    padding: 0.55rem 0.85rem;
    border-radius: 9999px;
    font-size: 0.82rem;
    font-weight: 700;
    color: #64748b;
    background: transparent;
    border: none;
    cursor: pointer;
    transition: all 0.25s ease;
    white-space: nowrap;
}

.ur-plan-tab-btn.active {
    background: #ffffff;
    color: #0f172a;
    font-weight: 800;
    box-shadow: 0 4px 14px rgba(15, 23, 42, 0.08);
}

.ur-plan-tab-btn .tab-badge {
    font-size: 0.65rem;
    font-weight: 800;
    padding: 0.15rem 0.45rem;
    border-radius: 9999px;
    background: #2563eb;
    color: #ffffff;
    margin-left: 2px;
}

/* ─── SLIDER WRAPPER & HORIZONTAL CAROUSEL ────────────────── */
.ur-slider-wrapper {
    position: relative;
    width: 100%;
    touch-action: pan-y;
}

.ur-plans__slider-container {
    overflow: hidden;
    width: 100%;
    padding: 1rem 0;
    cursor: grab;
}

.ur-plans__slider-container:active {
    cursor: grabbing;
}

.ur-plans__grid {
    display: flex;
    flex-wrap: nowrap;
    gap: 1.5rem;
    transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    align-items: stretch;
    width: 100%;
}

.ur-plan-card {
    position: relative;
    background: #ffffff;
    border: 1px solid rgba(15, 23, 42, 0.08);
    border-radius: 1.75rem;
    padding: 1.75rem 1.5rem;
    display: flex;
    flex-direction: column;
    transition: all 0.3s ease;
    overflow: hidden;
    flex: 0 0 100%;
    width: 100%;
    box-sizing: border-box;
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);
}

@media (min-width: 640px) {
    .ur-plan-card {
        padding: 2rem 1.75rem;
    }
}

@media (min-width: 768px) {
    .ur-plan-card {
        flex: 0 0 calc(50% - 0.75rem);
    }
}

@media (min-width: 1024px) {
    .ur-plan-card {
        flex: 0 0 calc(33.333% - 1rem);
    }
}

/* ─── SLIDER CONTROLS ──────────────────── */
.ur-slider-controls {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1.25rem;
    margin-top: 2rem;
    position: relative;
    z-index: 15;
}

.ur-slider-btn {
    width: 2.6rem;
    height: 2.6rem;
    border-radius: 50%;
    background: #ffffff;
    border: 1.5px solid rgba(15, 23, 42, 0.1);
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
    color: #0f172a;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    padding: 0;
}

.ur-slider-btn:hover {
    background: #2563eb;
    color: #ffffff;
    border-color: #2563eb;
    box-shadow: 0 6px 16px rgba(37, 99, 235, 0.25);
    transform: scale(1.06);
}

.ur-slider-dots {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.ur-slider-dot {
    width: 0.55rem;
    height: 0.55rem;
    border-radius: 50%;
    background: #cbd5e1;
    cursor: pointer;
    transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.ur-slider-dot.active {
    width: 1.5rem;
    border-radius: 9999px;
    background: #2563eb;
    box-shadow: 0 2px 8px rgba(37, 99, 235, 0.35);
}

.ur-slider-progress-wrap {
    width: 100%;
    max-width: 12rem;
    height: 3px;
    background: rgba(15, 23, 42, 0.08);
    border-radius: 9999px;
    overflow: hidden;
    margin: 0.75rem auto 0;
}

.ur-slider-progress-bar {
    height: 100%;
    width: 0%;
    background: linear-gradient(90deg, #2563eb, #60a5fa);
    border-radius: 9999px;
    transition: width 0.1s linear;
}

.ur-plan-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 45px rgba(15, 23, 42, 0.09);
}

.ur-plan-card--silver {
    background: #ffffff;
    border: 1.5px solid rgba(15, 23, 42, 0.09);
}

.ur-plan-card--gold {
    background: #ffffff;
    border: 2.5px solid #2563eb;
    box-shadow: 0 16px 40px rgba(37, 99, 235, 0.14);
}

.ur-plan-card--gold .ur-plan-card__badge {
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35);
}

.ur-plan-card--platinum {
    background: #ffffff;
    border: 1.5px solid rgba(15, 23, 42, 0.09);
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
}

.ur-plan-card--platinum .ur-plan-card__badge {
    background: linear-gradient(135deg, #0f172a, #334155);
    color: #ffffff;
}

.ur-plan-card__badge {
    position: absolute;
    top: 1.25rem;
    right: 1.25rem;
    font-size: 0.62rem;
    font-weight: 800;
    color: #ffffff;
    background: #0f172a;
    padding: 0.35rem 0.75rem;
    border-radius: 9999px;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    z-index: 10;
}

.ur-plan-card__icon {
    width: 3.75rem;
    height: 3.75rem;
    border-radius: 1.15rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
    margin-bottom: 1.25rem;
    background: var(--plan-bg);
    color: var(--plan-accent);
    box-shadow: 0 10px 24px var(--plan-glow);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    border: 1.5px solid color-mix(in srgb, var(--plan-accent) 20%, transparent);
}

.ur-plan-card:hover .ur-plan-card__icon {
    transform: scale(1.08) rotate(6deg);
}

.ur-plan-card__name {
    font-size: 1.35rem;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 0.35rem;
    letter-spacing: -0.02em;
}

.ur-plan-card__desc {
    font-size: 0.85rem;
    color: #64748b;
    line-height: 1.5;
    margin-bottom: 1.1rem;
    min-height: 2.6rem;
}

.ur-plan-card__price {
    display: flex;
    align-items: baseline;
    gap: 0.35rem;
    margin-bottom: 0.35rem;
}

.ur-plan-card__currency {
    font-size: 1.1rem;
    font-weight: 800;
    color: var(--plan-accent);
}

.ur-plan-card__amount {
    font-size: 2.6rem;
    font-weight: 900;
    color: #0f172a;
    letter-spacing: -0.04em;
    line-height: 1;
}

.ur-plan-card__period {
    font-size: 0.8rem;
    font-weight: 600;
    color: #64748b;
}

.ur-plan-card__price-note {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.75rem;
    font-weight: 600;
    color: #64748b;
    margin-bottom: 1.25rem;
}

.ur-plan-card__price-note i {
    color: #10b981;
    font-size: 0.95rem;
}

.ur-plan-card__divider {
    height: 1px;
    background: #e2e8f0;
    margin-bottom: 1.25rem;
}

.ur-plan-card__features {
    list-style: none;
    padding: 0;
    margin: 0 0 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 0.7rem;
    flex-grow: 1;
}

.ur-plan-card__features li {
    display: flex;
    align-items: flex-start;
    gap: 0.65rem;
    font-size: 0.85rem;
    color: #334155;
    line-height: 1.45;
}

.ur-plan-card__f-icon {
    width: 1.35rem;
    height: 1.35rem;
    border-radius: 50%;
    background: rgba(37, 99, 235, 0.08);
    color: #2563eb;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    flex-shrink: 0;
    margin-top: 0.1rem;
}

/* ─── PROMINENT PAY NOW CTA BUTTON ──────────────────── */
.ur-plan-card__pay-cta {
    display: flex;
    flex-direction: column;
    gap: 0.45rem;
    margin-top: auto;
}

.ur-plan-card__cta-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    width: 100%;
    padding: 0.9rem 1.25rem;
    border-radius: 1rem;
    font-size: 0.95rem;
    font-weight: 800;
    letter-spacing: -0.01em;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    box-sizing: border-box;
    position: relative;
    overflow: hidden;
}

.ur-plan-card__cta-btn--primary {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    color: #ffffff !important;
    border: none;
    box-shadow: 0 8px 24px rgba(37, 99, 235, 0.32);
}

.ur-plan-card__cta-btn--primary:hover {
    background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
    box-shadow: 0 12px 30px rgba(37, 99, 235, 0.42);
    transform: translateY(-2px);
}

.ur-plan-card__cta-btn--secondary {
    background: #0f172a;
    color: #ffffff !important;
    border: none;
    box-shadow: 0 6px 20px rgba(15, 23, 42, 0.2);
}

.ur-plan-card__cta-btn--secondary:hover {
    background: #1e293b;
    box-shadow: 0 10px 26px rgba(15, 23, 42, 0.3);
    transform: translateY(-2px);
}

.ur-plan-card__cta-btn .pay-now-icon {
    font-size: 1.15rem;
}

.ur-plan-card__cta-btn .pay-amount-pill {
    background: rgba(255, 255, 255, 0.22);
    padding: 0.2rem 0.5rem;
    border-radius: 9999px;
    font-size: 0.8rem;
    font-weight: 800;
    margin-left: auto;
}

.ur-plan-card__trust-note {
    font-size: 0.72rem;
    color: #64748b;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.35rem;
}

.ur-plan-card__trust-note i {
    color: #10b981;
    font-size: 0.82rem;
}

/* ─── VIEW ALL PLANS LINK ──────────────── */
.ur-plans__cta-wrap {
    text-align: center;
    margin-top: 2.5rem;
}

.ur-plans__cta-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    font-weight: 700;
    color: #2563eb;
    text-decoration: none;
    padding: 0.7rem 1.75rem;
    border: 1.5px solid rgba(37, 99, 235, 0.2);
    border-radius: 9999px;
    transition: all 0.3s;
    background: rgba(37, 99, 235, 0.04);
}

.ur-plans__cta-link:hover {
    background: #2563eb;
    color: #ffffff;
    border-color: #2563eb;
    box-shadow: 0 12px 30px rgba(37, 99, 235, 0.25);
    transform: translateY(-2px);
}

.ur-plans__cta-link i {
    font-size: 1rem;
    transition: transform 0.3s;
}

.ur-plans__cta-link:hover i {
    transform: translateX(4px);
}
</style>

@php
    $homePlans = \Illuminate\Support\Facades\Cache::remember('home_plans_preview', 300, function () {
        return \App\Models\Plan::active()
            ->where('is_private', false)
            ->orderBy('sort_order')
            ->take(3)
            ->get();
    });

    $planMeta = [
        0 => [
            'icon'   => 'ph-sparkle',
            'accent' => '#475569', 'bg' => '#f1f5f9',
            'glow'   => 'rgba(71,85,105,.12)', 'border' => 'rgba(71,85,105,.2)', 'check' => '#f1f5f9',
        ],
        1 => [
            'icon'   => 'ph-crown',
            'accent' => '#d97706', 'bg' => '#fffbeb',
            'glow'   => 'rgba(217,119,6,.15)', 'border' => 'rgba(217,119,6,.3)', 'check' => '#fffbeb',
        ],
        2 => [
            'icon'   => 'ph-lightning',
            'accent' => '#2563eb', 'bg' => '#eff6ff',
            'glow'   => 'rgba(37,99,235,.15)', 'border' => 'rgba(37,99,235,.3)', 'check' => '#eff6ff',
        ],
    ];

    // Map feature keywords to Phosphor icons
    $featureIcons = [
        'unlock'    => 'ph-lock-key-open',
        'contact'   => 'ph-address-book',
        'support'   => 'ph-headset',
        'priority'  => 'ph-rocket-launch',
        'email'     => 'ph-envelope-simple',
        'search'    => 'ph-magnifying-glass',
        'filter'    => 'ph-funnel',
        'badge'     => 'ph-medal',
        'verified'  => 'ph-seal-check',
        'whatsapp'  => 'ph-whatsapp-logo',
        'alert'     => 'ph-bell-ringing',
        'analytics' => 'ph-chart-line-up',
        'premium'   => 'ph-star',
        'manager'   => 'ph-user-circle-gear',
        'dedicated' => 'ph-user-focus',
        'advanced'  => 'ph-sliders-horizontal',
        'validity'  => 'ph-calendar-check',
        'days'      => 'ph-calendar-check',
        'period'    => 'ph-calendar-check',
        'instant'   => 'ph-lightning',
    ];

    if (!function_exists('getFeatureIcon')) {
        function getFeatureIcon($feature, $featureIcons) {
            $lower = strtolower($feature);
            foreach ($featureIcons as $keyword => $icon) {
                if (str_contains($lower, $keyword)) return $icon;
            }
            return 'ph-check-circle';
        }
    }
@endphp

@if($homePlans->count())
<section class="ur-plans" id="pricing-plans">
    <div class="ur-plans__accent ur-plans__accent--1"></div>
    <div class="ur-plans__accent ur-plans__accent--2"></div>

    <div class="ur-plans__container">
        {{-- Header --}}
        <div class="ur-plans__header">
            <span class="ur-plans__eyebrow">
                <i class="ph-bold ph-shield-check"></i>
                Premium Plans
            </span>
            <h2 class="ur-plans__title">Unlock <span>Premium Access</span></h2>
            <p class="ur-plans__subtitle">Choose a plan to unlock verified owner contacts, priority support, and premium rental intelligence.</p>
        </div>

        {{-- Standard Segmented Billing Switch --}}
        <div class="ur-billing-switch-container">
            <div class="ur-billing-segmented-switch" id="ur-billing-switch" role="tablist" aria-label="Billing period toggle">
                <button type="button" class="ur-billing-seg-btn active" id="billing-monthly" data-period="monthly" role="tab" aria-selected="true">
                    <i class="ph-bold ph-house-line"></i>
                    <span>Rental Pass</span>
                    <span class="ur-seg-sub">(Monthly)</span>
                </button>
                <button type="button" class="ur-billing-seg-btn" id="billing-yearly" data-period="yearly" role="tab" aria-selected="false">
                    <i class="ph-bold ph-buildings"></i>
                    <span>Buyer Pass</span>
                    <span class="ur-seg-sub">(Annual)</span>
                    <span class="ur-discount-chip">Save 20%</span>
                </button>
            </div>
        </div>

        {{-- Interactive Plan Switcher Tabs --}}
        <div class="ur-plan-tabs" id="ur-plan-tabs">
            @foreach($homePlans as $index => $plan)
                @php
                    $isGold = $index === 1 || str_contains(strtolower($plan->name), 'gold');
                    $isPlatinum = $index === 2 || str_contains(strtolower($plan->name), 'platinum');
                    $tabLabel = $isGold ? 'Gold Pass' : ($isPlatinum ? 'Platinum VIP' : 'Silver Pass');
                    $tabIcon = $isGold ? 'ph-crown' : ($isPlatinum ? 'ph-lightning' : 'ph-sparkle');
                @endphp
                <button type="button" class="ur-plan-tab-btn @if($index === 0) active @endif" data-plan-index="{{ $index }}" aria-label="Switch to {{ $tabLabel }}">
                    <i class="ph-bold {{ $tabIcon }}"></i>
                    <span>{{ $tabLabel }}</span>
                    @if($isGold)
                        <span class="tab-badge">Popular</span>
                    @endif
                </button>
            @endforeach
        </div>

        {{-- Plans Slider Wrapper --}}
        <div class="ur-slider-wrapper" id="ur-slider-wrapper">
            <div class="ur-plans__slider-container">
                <div class="ur-plans__grid">
                    @foreach($homePlans as $index => $plan)
                        @php
                            $meta = $planMeta[$index] ?? $planMeta[0];
                            $isGold = $index === 1 || str_contains(strtolower($plan->name), 'gold');
                            $isPlatinum = $index === 2 || str_contains(strtolower($plan->name), 'platinum');
                            $isSilver = !$isGold && !$isPlatinum;
                            
                            $cardThemeClass = 'ur-plan-card--silver';
                            if ($isGold) $cardThemeClass = 'ur-plan-card--gold';
                            if ($isPlatinum) $cardThemeClass = 'ur-plan-card--platinum';

                            $monthlyOffer = isset($userOffers) ? $userOffers->where('plan_id', $plan->id)->where('billing_period', 'monthly')->first() : null;
                            $yearlyOffer = isset($userOffers) ? $userOffers->where('plan_id', $plan->id)->where('billing_period', 'yearly')->first() : null;
                            $originalPrice = (float) $plan->price;
                            $monthlyPrice = ($monthlyOffer && $monthlyOffer->discounted_price !== null) ? (float) $monthlyOffer->discounted_price : $originalPrice;
                            $yearlyPrice = ($yearlyOffer && $yearlyOffer->discounted_price !== null) ? (float) $yearlyOffer->discounted_price : round($originalPrice * 12 * 0.8);
                            $hasOffer = ($monthlyOffer || $yearlyOffer);
                        @endphp
                        <div class="ur-plan-card {{ $cardThemeClass }}"
                             style="--plan-accent: {{ $meta['accent'] }}; --plan-bg: {{ $meta['bg'] }}; --plan-glow: {{ $meta['glow'] }}; --plan-border: {{ $meta['border'] }}; --plan-check-bg: {{ $meta['check'] }};"
                             data-plan-index="{{ $index }}"
                             data-has-monthly-offer="{{ $monthlyOffer ? 'true' : 'false' }}"
                             data-has-yearly-offer="{{ $yearlyOffer ? 'true' : 'false' }}">

                            @if($isGold)
                                <span class="ur-plan-card__badge"><i class="ph-bold ph-fire"></i> Most Popular</span>
                            @elseif($isPlatinum)
                                <span class="ur-plan-card__badge"><i class="ph-bold ph-lightning"></i> VIP Choice</span>
                            @endif

                            <div class="ur-plan-card__icon" style="position: relative; display: flex; align-items: center; justify-content: center; overflow: hidden; background: var(--plan-bg);">
                                @if($isSilver)
                                    <lottie-player src="{{ asset('lottie/sparkles.json') }}" background="transparent" speed="1.2" style="width: 2.75rem; height: 2.75rem;" loop autoplay></lottie-player>
                                @elseif($isGold)
                                    <lottie-player src="{{ asset('lottie/crown.json') }}" background="transparent" speed="1.0" style="width: 2.75rem; height: 2.75rem;" loop autoplay></lottie-player>
                                @elseif($isPlatinum)
                                    <lottie-player src="{{ asset('lottie/lightning.json') }}" background="transparent" speed="1.0" style="width: 2.75rem; height: 2.75rem;" loop autoplay></lottie-player>
                                @else
                                    <i class="ph-bold {{ $meta['icon'] }}"></i>
                                @endif
                            </div>

                            @php
                                $cleanDesc = 'Ideal for tenants looking to quickly connect with verified property owners.';
                                if ($isGold) {
                                    $cleanDesc = 'Most popular choice for active seekers wanting fast-track verified contacts.';
                                } elseif ($isPlatinum) {
                                    $cleanDesc = 'VIP comprehensive pass with dedicated relationship support & priority assistance.';
                                }
                            @endphp

                            <h3 class="ur-plan-card__name">{{ $plan->name }}</h3>
                            <p class="ur-plan-card__desc">{{ $cleanDesc }}</p>

                            <div class="ur-plan-card__price" style="flex-wrap: wrap; align-items: baseline;">
                                <div class="special-offer-badge" style="width: 100%; display: {{ $hasOffer ? 'flex' : 'none' }}; align-items: center; gap: 8px; margin-bottom: 4px;" data-original-monthly="{{ number_format($originalPrice, 0) }}" data-original-yearly="{{ number_format(round($originalPrice * 12 * 0.8), 0) }}">
                                    <span style="font-size: 0.65rem; font-weight: 800; color: #10b981; background: rgba(16, 185, 129, 0.1); padding: 2px 8px; border-radius: 9999px; text-transform: uppercase; letter-spacing: 0.05em;">Special Offer</span>
                                    <span class="special-offer-original-price" style="font-size: 0.875rem; text-decoration: line-through; color: #94a3b8; font-weight: 700;">₹{{ number_format($originalPrice, 0) }}</span>
                                </div>
                                <span class="ur-plan-card__currency">₹</span>
                                <span class="ur-plan-card__amount"
                                      data-monthly="{{ number_format($monthlyPrice, 0) }}"
                                      data-yearly="{{ number_format($yearlyPrice, 0) }}">{{ number_format($monthlyPrice, 0) }}</span>
                                <span class="ur-plan-card__period">/ pass</span>
                            </div>
                            <div class="ur-plan-card__price-note">
                                <i class="ph-bold ph-seal-check"></i>
                                <span class="price-note-text">Zero Brokerage · GST Included · Instant Access</span>
                            </div>

                            <div class="ur-plan-card__divider"></div>

                            @php
                                $cleanFeatures = [];
                                
                                // 1. Direct Owner Unlocks
                                $cleanFeatures[] = [
                                    'icon' => 'ph-lock-key-open',
                                    'html' => '<strong>' . $plan->contact_limit . ' Verified Owner</strong> Direct Contacts'
                                ];
                                
                                // 2. Search Pass Validity
                                $cleanFeatures[] = [
                                    'icon' => 'ph-calendar-check',
                                    'html' => '<strong>' . $plan->duration_days . ' Days</strong> Access Validity'
                                ];
                                
                                // 3. Instant Contact method
                                $cleanFeatures[] = [
                                    'icon' => 'ph-phone-call',
                                    'html' => 'Direct Phone & WhatsApp Access'
                                ];

                                // 4. Tiered perks
                                if ($isSilver) {
                                    $cleanFeatures[] = [
                                        'icon' => 'ph-envelope-simple',
                                        'html' => 'Standard Email Support (24h turnaround)'
                                    ];
                                    $cleanFeatures[] = [
                                        'icon' => 'ph-shield-check',
                                        'html' => 'Zero Brokerage Guarantee'
                                    ];
                                } elseif ($isGold) {
                                    $cleanFeatures[] = [
                                        'icon' => 'ph-sliders-horizontal',
                                        'html' => 'Advanced Neighborhood & Amenity Filters'
                                    ];
                                    $cleanFeatures[] = [
                                        'icon' => 'ph-headset',
                                        'html' => 'Priority Support & Fast-Track Assistance'
                                    ];
                                    $cleanFeatures[] = [
                                        'icon' => 'ph-bell-ringing',
                                        'html' => 'Real-Time New Listing Alerts'
                                    ];
                                } elseif ($isPlatinum) {
                                    $cleanFeatures[] = [
                                        'icon' => 'ph-user-focus',
                                        'html' => 'Dedicated Relationship Concierge'
                                    ];
                                    $cleanFeatures[] = [
                                        'icon' => 'ph-whatsapp-logo',
                                        'html' => 'Instant WhatsApp Direct Property Alerts'
                                    ];
                                    $cleanFeatures[] = [
                                        'icon' => 'ph-seal-check',
                                        'html' => 'Premium Verified Seeker Profile Badge'
                                    ];
                                    $cleanFeatures[] = [
                                        'icon' => 'ph-file-text',
                                        'html' => 'Digital Lease Agreement Assistance'
                                    ];
                                }
                            @endphp

                            <ul class="ur-plan-card__features">
                                @foreach($cleanFeatures as $f)
                                    <li>
                                        <span class="ur-plan-card__f-icon"><i class="ph-bold {{ $f['icon'] }}"></i></span>
                                        <span>{!! $f['html'] !!}</span>
                                    </li>
                                @endforeach
                            </ul>

                            {{-- High-Impact Pay Now Button --}}
                            <div class="ur-plan-card__pay-cta">
                                @php
                                    $checkoutUrl = Route::has('plans.checkout') ? route('plans.checkout', ['plan' => $plan, 'billing' => 'monthly', 'direct' => 1]) : url('/plans');
                                @endphp
                                @guest
                                    <a href="{{ route('login', ['redirect' => $checkoutUrl]) }}" 
                                       onclick="event.preventDefault(); event.stopPropagation(); window.openAuthModal('login', this.getAttribute('data-checkout-url') || '{{ $checkoutUrl }}');"
                                       data-checkout-url="{{ $checkoutUrl }}"
                                       data-no-loader="true"
                                       data-ur-loader-skip="true"
                                       class="ur-plan-card__cta-btn {{ ($isGold || $isPlatinum) ? 'ur-plan-card__cta-btn--primary' : 'ur-plan-card__cta-btn--secondary' }} plan-checkout-link" 
                                       title="Pay Now &amp; Unlock Verified Contacts">
                                        <i class="ph-bold ph-lightning-fill pay-now-icon"></i>
                                        <span>Pay Now · Instant Access</span>
                                        <span class="pay-amount-pill cta-price-display" data-monthly="₹{{ number_format($monthlyPrice, 0) }}" data-yearly="₹{{ number_format($yearlyPrice, 0) }}">₹{{ number_format($monthlyPrice, 0) }}</span>
                                    </a>
                                @else
                                    <a href="{{ $checkoutUrl }}" 
                                       data-checkout-url="{{ $checkoutUrl }}"
                                       class="ur-plan-card__cta-btn {{ ($isGold || $isPlatinum) ? 'ur-plan-card__cta-btn--primary' : 'ur-plan-card__cta-btn--secondary' }} plan-checkout-link" 
                                       title="Pay Now &amp; Unlock Verified Contacts">
                                        <i class="ph-bold ph-lightning-fill pay-now-icon"></i>
                                        <span>Pay Now · Instant Access</span>
                                        <span class="pay-amount-pill cta-price-display" data-monthly="₹{{ number_format($monthlyPrice, 0) }}" data-yearly="₹{{ number_format($yearlyPrice, 0) }}">₹{{ number_format($monthlyPrice, 0) }}</span>
                                    </a>
                                @endguest
                                <div class="ur-plan-card__trust-note">
                                    <i class="ph-bold ph-shield-check"></i>
                                    <span>100% Secure Checkout · Instant Activation</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Slider Navigation Controls --}}
            <div class="ur-slider-controls">
                <button type="button" class="ur-slider-btn ur-slider-btn--prev" aria-label="Previous plan">
                    <i class="ph-bold ph-caret-left"></i>
                </button>
                <div class="ur-slider-dots">
                    @foreach($homePlans as $index => $plan)
                        <span class="ur-slider-dot @if($index === 0) active @endif" data-index="{{ $index }}" aria-label="Go to slide {{ $index + 1 }}"></span>
                    @endforeach
                </div>
                <button type="button" class="ur-slider-btn ur-slider-btn--next" aria-label="Next plan">
                    <i class="ph-bold ph-caret-right"></i>
                </button>
            </div>

            {{-- Visual Auto-Slide Progress Bar --}}
            <div class="ur-slider-progress-wrap" title="Auto-slide active">
                <div class="ur-slider-progress-bar" id="ur-slider-progress"></div>
            </div>
        </div>

        {{-- View All Plans CTA --}}
        <div class="ur-plans__cta-wrap">
            <a href="{{ route('plans.index') }}" class="ur-plans__cta-link" title="View All Plans &amp; Compare">
                <i class="ph-bold ph-squares-four"></i>
                <span>View All Plans & Compare</span>
                <i class="ph-bold ph-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<script>
(function() {
    function initPricingSlider() {
        const grid = document.querySelector('.ur-plans__grid');
        const container = document.querySelector('.ur-plans__slider-container');
        const sliderWrapper = document.getElementById('ur-slider-wrapper');
        const prevBtn = document.querySelector('.ur-slider-btn--prev');
        const nextBtn = document.querySelector('.ur-slider-btn--next');
        const dots = document.querySelectorAll('.ur-slider-dot');
        const tabBtns = document.querySelectorAll('.ur-plan-tab-btn');
        const cards = document.querySelectorAll('.ur-plan-card');
        const progressBar = document.getElementById('ur-slider-progress');

        if (!grid || !cards.length) return;

        // Billing Toggle Logic (Segmented Switch)
        const monthlyBtn = document.getElementById('billing-monthly');
        const yearlyBtn = document.getElementById('billing-yearly');
        const priceNotes = document.querySelectorAll('.price-note-text');
        const billingInputs = document.querySelectorAll('.billing-period-input');
        
        let isYearly = false;
        
        function updateBillingPeriod(yearly) {
            isYearly = yearly;
            
            if (monthlyBtn) {
                monthlyBtn.classList.toggle('active', !isYearly);
                monthlyBtn.setAttribute('aria-selected', !isYearly ? 'true' : 'false');
            }
            if (yearlyBtn) {
                yearlyBtn.classList.toggle('active', isYearly);
                yearlyBtn.setAttribute('aria-selected', isYearly ? 'true' : 'false');
            }
            
            billingInputs.forEach(input => {
                input.value = isYearly ? 'yearly' : 'monthly';
            });
            
            cards.forEach(card => {
                const amountEl = card.querySelector('.ur-plan-card__amount');
                const badgeEl = card.querySelector('.special-offer-badge');
                const ctaPriceEl = card.querySelector('.cta-price-display');
                
                if (amountEl) {
                    const monthlyPrice = amountEl.getAttribute('data-monthly');
                    const yearlyPrice = amountEl.getAttribute('data-yearly');
                    const activePrice = isYearly ? yearlyPrice : monthlyPrice;
                    
                    amountEl.style.transition = 'transform 0.15s, opacity 0.15s';
                    amountEl.style.transform = 'scale(0.9)';
                    amountEl.style.opacity = '0';
                    
                    setTimeout(() => {
                        amountEl.textContent = activePrice;
                        amountEl.style.transform = 'scale(1)';
                        amountEl.style.opacity = '1';
                    }, 150);
                }

                if (ctaPriceEl) {
                    const monthlyText = ctaPriceEl.getAttribute('data-monthly');
                    const yearlyText = ctaPriceEl.getAttribute('data-yearly');
                    ctaPriceEl.textContent = isYearly ? yearlyText : monthlyText;
                }
                
                if (badgeEl) {
                    const hasMonthlyOffer = card.getAttribute('data-has-monthly-offer') === 'true';
                    const hasYearlyOffer = card.getAttribute('data-has-yearly-offer') === 'true';
                    const originalPriceEl = badgeEl.querySelector('.special-offer-original-price');
                    
                    if (isYearly && hasYearlyOffer) {
                        badgeEl.style.display = 'flex';
                        if (originalPriceEl) originalPriceEl.textContent = '₹' + badgeEl.getAttribute('data-original-yearly');
                    } else if (!isYearly && hasMonthlyOffer) {
                        badgeEl.style.display = 'flex';
                        if (originalPriceEl) originalPriceEl.textContent = '₹' + badgeEl.getAttribute('data-original-monthly');
                    } else {
                        badgeEl.style.display = 'none';
                    }
                }
            });

            const periodLabels = document.querySelectorAll('.ur-plan-card__period');
            periodLabels.forEach(periodEl => {
                periodEl.style.transition = 'opacity 0.15s';
                periodEl.style.opacity = '0';
                setTimeout(() => {
                    periodEl.textContent = isYearly ? '/ annual pass' : '/ pass';
                    periodEl.style.opacity = '1';
                }, 150);
            });
            
            priceNotes.forEach(noteEl => {
                noteEl.style.transition = 'opacity 0.15s';
                noteEl.style.opacity = '0';
                setTimeout(() => {
                    noteEl.innerHTML = isYearly 
                        ? 'Save 20% · Priority Buyer Contact Pass' 
                        : 'Zero Brokerage · GST Included · Instant Access';
                    noteEl.style.opacity = '1';
                }, 150);
            });

            const checkoutLinks = document.querySelectorAll('.plan-checkout-link');
            checkoutLinks.forEach(link => {
                const rawUrl = link.getAttribute('data-checkout-url') || link.href;
                const url = new URL(rawUrl, window.location.origin);
                url.searchParams.set('billing', isYearly ? 'yearly' : 'monthly');
                url.searchParams.set('direct', '1');
                const finalHref = url.pathname + url.search;
                
                link.setAttribute('data-checkout-url', finalHref);
                if (link.hasAttribute('onclick')) {
                    link.href = '/login?redirect=' + encodeURIComponent(finalHref);
                } else {
                    link.href = finalHref;
                }
            });
        }
        
        monthlyBtn?.addEventListener('click', () => {
            if (isYearly) updateBillingPeriod(false);
        });
        yearlyBtn?.addEventListener('click', () => {
            if (!isYearly) updateBillingPeriod(true);
        });

        let currentIndex = 0;
        const AUTO_INTERVAL_MS = 4200;
        let autoSlideTimer = null;
        let progressTimer = null;
        let progressStart = Date.now();
        let isUserInteracting = false;
        let resumeTimeout = null;

        function getItemsPerView() {
            if (window.innerWidth >= 1024) return 3;
            if (window.innerWidth >= 768) return 2;
            return 1;
        }

        function getMaxIndex() {
            return Math.max(0, cards.length - getItemsPerView());
        }

        function updateSlider(smooth = true) {
            const itemsPerView = getItemsPerView();
            const maxIndex = getMaxIndex();

            if (currentIndex > maxIndex) {
                currentIndex = maxIndex;
            }
            if (currentIndex < 0) {
                currentIndex = 0;
            }

            const cardWidth = cards[0].offsetWidth;
            const gap = parseFloat(window.getComputedStyle(grid).gap) || 24;
            const offset = currentIndex * (cardWidth + gap);

            grid.style.transition = smooth ? 'transform 0.5s cubic-bezier(0.16, 1, 0.3, 1)' : 'none';
            grid.style.transform = `translateX(-${offset}px)`;

            // Update arrow buttons
            if (prevBtn) {
                prevBtn.disabled = currentIndex === 0;
                prevBtn.style.opacity = currentIndex === 0 ? '0.3' : '1';
                prevBtn.style.pointerEvents = currentIndex === 0 ? 'none' : 'auto';
            }
            if (nextBtn) {
                nextBtn.disabled = currentIndex === maxIndex;
                nextBtn.style.opacity = currentIndex === maxIndex ? '0.3' : '1';
                nextBtn.style.pointerEvents = currentIndex === maxIndex ? 'none' : 'auto';
            }

            // Update dots
            dots.forEach((dot, index) => {
                const isActive = index === currentIndex;
                dot.classList.toggle('active', isActive);
                dot.style.display = index <= maxIndex ? 'inline-block' : 'none';
            });

            // Update plan quick tabs
            tabBtns.forEach((tab, index) => {
                tab.classList.toggle('active', index === currentIndex);
            });

            // Show controls only if there are items to slide
            const showControls = cards.length > itemsPerView;
            const controls = document.querySelector('.ur-slider-controls');
            if (controls) {
                controls.style.display = showControls ? 'flex' : 'none';
            }
        }

        function nextSlide() {
            const maxIndex = getMaxIndex();
            if (currentIndex >= maxIndex) {
                currentIndex = 0;
            } else {
                currentIndex++;
            }
            updateSlider();
            resetProgressBar();
        }

        function prevSlide() {
            if (currentIndex > 0) {
                currentIndex--;
            } else {
                currentIndex = getMaxIndex();
            }
            updateSlider();
            resetProgressBar();
        }

        function resetProgressBar() {
            progressStart = Date.now();
            if (progressBar) progressBar.style.width = '0%';
        }

        function startAutoSlide() {
            stopAutoSlide();
            resetProgressBar();
            
            progressTimer = setInterval(() => {
                if (isUserInteracting) return;
                const elapsed = Date.now() - progressStart;
                const pct = Math.min(100, (elapsed / AUTO_INTERVAL_MS) * 100);
                if (progressBar) progressBar.style.width = `${pct}%`;
            }, 60);

            autoSlideTimer = setInterval(() => {
                if (!isUserInteracting) {
                    nextSlide();
                }
            }, AUTO_INTERVAL_MS);
        }

        function stopAutoSlide() {
            if (autoSlideTimer) clearInterval(autoSlideTimer);
            if (progressTimer) clearInterval(progressTimer);
            if (progressBar) progressBar.style.width = '0%';
        }

        function pauseAutoSlideTemporarily() {
            isUserInteracting = true;
            if (progressBar) progressBar.style.width = '0%';
            clearTimeout(resumeTimeout);
            resumeTimeout = setTimeout(() => {
                isUserInteracting = false;
                resetProgressBar();
            }, 5500);
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                pauseAutoSlideTemporarily();
                prevSlide();
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                pauseAutoSlideTemporarily();
                nextSlide();
            });
        }

        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                pauseAutoSlideTemporarily();
                currentIndex = index;
                updateSlider();
                resetProgressBar();
            });
        });

        tabBtns.forEach((tab, index) => {
            tab.addEventListener('click', () => {
                pauseAutoSlideTemporarily();
                currentIndex = index;
                updateSlider();
                resetProgressBar();
            });
        });

        // Hover pause on desktop
        if (sliderWrapper) {
            sliderWrapper.addEventListener('mouseenter', () => {
                isUserInteracting = true;
            });
            sliderWrapper.addEventListener('mouseleave', () => {
                isUserInteracting = false;
                resetProgressBar();
            });
        }

        // Touch swipe support with rubberband & inertia
        let startX = 0;
        let currentX = 0;
        let isSwiping = false;

        container.addEventListener('touchstart', (e) => {
            pauseAutoSlideTemporarily();
            startX = e.touches[0].clientX;
            isSwiping = true;
        }, { passive: true });

        container.addEventListener('touchmove', (e) => {
            if (!isSwiping) return;
            currentX = e.touches[0].clientX;
        }, { passive: true });

        container.addEventListener('touchend', () => {
            if (!isSwiping) return;
            isSwiping = false;
            const diffX = startX - currentX;
            if (Math.abs(diffX) > 40) {
                if (diffX > 0) {
                    nextSlide();
                } else {
                    prevSlide();
                }
            }
        });

        let resizeTimeout;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                updateSlider(false);
            }, 100);
        });

        document.querySelectorAll('.plan-checkout-link').forEach(link => {
            link.addEventListener('click', () => {
                link.style.pointerEvents = 'none';
                link.style.opacity = '0.85';
                link.innerHTML = '<i class="ph-bold ph-circle-notch animate-spin" style="margin-right:6px"></i> Opening Secure Checkout...';
            });
        });

        // Initial setup & start auto slider
        updateSlider(false);
        startAutoSlide();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPricingSlider);
    } else {
        initPricingSlider();
    }
})();
</script>
@endif
