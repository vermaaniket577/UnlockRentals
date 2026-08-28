{{-- ============================================================
     UNLOCK RENTALS — PRICING PLANS (HOMEPAGE PREVIEW)
     ============================================================ --}}

<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js" defer></script>

<style>
.ur-plans {
    background: linear-gradient(180deg, #f8fafc 0%, #ffffff 50%, #f8fafc 100%);
    padding: 6rem 0;
    position: relative;
    overflow: hidden;
    font-family: 'Outfit', 'Inter', sans-serif;
}

.ur-plans__accent {
    position: absolute;
    width: 36rem;
    height: 36rem;
    background: radial-gradient(circle, rgba(37, 99, 235, 0.04) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
    z-index: 0;
}
.ur-plans__accent--1 { top: -8%; right: 0; transform: translateX(10%); }
.ur-plans__accent--2 { bottom: -8%; left: 0; transform: translateX(-10%); }

.ur-plans__container {
    max-width: 80rem;
    margin: 0 auto;
    padding: 0 1.5rem;
    position: relative;
    z-index: 10;
}

.ur-plans__header {
    text-align: center;
    max-width: 42rem;
    margin: 0 auto 4rem;
}

.ur-plans__eyebrow {
    font-size: 0.7rem;
    font-weight: 800;
    color: #2563eb;
    text-transform: uppercase;
    letter-spacing: 0.3em;
    margin-bottom: 1rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.ur-plans__eyebrow i {
    font-size: 0.85rem;
}

.ur-plans__title {
    font-size: 2.25rem;
    font-weight: 900;
    color: #0f172a;
    letter-spacing: -0.04em;
    line-height: 1.15;
    margin-bottom: 1rem;
}

@media (min-width: 768px) {
    .ur-plans__title { font-size: 3rem; }
}

.ur-plans__title span {
    background: linear-gradient(135deg, #2563eb, #6366f1);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.ur-plans__subtitle {
    font-size: 1rem;
    color: #64748b;
    line-height: 1.6;
    font-weight: 400;
}

/* ─── BILLING TOGGLE SWITCH ────────────────── */
.ur-plans__toggle-wrap {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1.25rem;
    margin: -1.5rem auto 3rem;
    position: relative;
    z-index: 10;
}

.ur-plans__toggle-label {
    font-size: 0.95rem;
    font-weight: 700;
    color: #64748b;
    transition: color 0.3s;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
}

.ur-plans__toggle-label.active {
    color: #0f172a;
    font-weight: 800;
}

.ur-plans__toggle-switch {
    width: 3.75rem;
    height: 2.1rem;
    border-radius: 9999px;
    background: #cbd5e1;
    border: none;
    position: relative;
    cursor: pointer;
    transition: background 0.3s;
    padding: 0;
}

.ur-plans__toggle-switch.active {
    background: #2563eb;
}

.ur-plans__toggle-handle {
    width: 1.65rem;
    height: 1.65rem;
    border-radius: 50%;
    background: #ffffff;
    position: absolute;
    top: 0.22rem;
    left: 0.22rem;
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 2px 6px rgba(15, 23, 42, 0.15);
}

.ur-plans__toggle-switch.active .ur-plans__toggle-handle {
    transform: translateX(1.65rem);
}

.ur-plans__discount-badge {
    background: #10b981;
    color: #ffffff;
    font-size: 0.75rem;
    font-weight: 800;
    padding: 0.25rem 0.6rem;
    border-radius: 9999px;
    letter-spacing: 0.05em;
    box-shadow: 0 4px 10px rgba(16, 185, 129, 0.25);
}

/* ─── SLIDER WRAPPER & CONTAINER ────────────────── */
.ur-slider-wrapper {
    position: relative;
    width: 100%;
}

.ur-plans__slider-container {
    overflow: hidden;
    width: 100%;
    padding: 1.5rem 0.5rem;
    margin: -1.5rem -0.5rem;
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
    border-radius: 1.5rem;
    padding: 2.25rem;
    display: flex;
    flex-direction: column;
    transition: all 0.3s ease;
    overflow: hidden;
    flex: 0 0 100%;
    width: 100%;
    box-sizing: border-box;
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
    gap: 1.5rem;
    margin-top: 2.5rem;
    position: relative;
    z-index: 15;
}

.ur-slider-btn {
    width: 2.75rem;
    height: 2.75rem;
    border-radius: 50%;
    background: #ffffff;
    border: 1px solid rgba(15, 23, 42, 0.08);
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05);
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
    box-shadow: 0 8px 20px rgba(37, 99, 235, 0.2);
}

.ur-slider-dots {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.ur-slider-dot {
    width: 0.5rem;
    height: 0.5rem;
    border-radius: 50%;
    background: #cbd5e1;
    cursor: pointer;
    transition: all 0.3s ease;
}

.ur-slider-dot.active {
    width: 1.25rem;
    border-radius: 9999px;
    background: #2563eb;
}

/* Base Card Scale Hover */
.ur-plan-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
}

/* ─── SILVER CARD THEME ────────────────── */
.ur-plan-card--silver {
    background: #ffffff;
    border: 1px solid rgba(15, 23, 42, 0.08);
}

/* ─── GOLD CARD THEME (SINGLE CLEAR FOCAL POINT) ────────────────── */
.ur-plan-card--gold {
    background: #ffffff;
    border: 2.5px solid #2563eb;
    box-shadow: 0 16px 40px rgba(37, 99, 235, 0.12);
}

.ur-plan-card--gold .ur-plan-card__badge {
    background: #2563eb;
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
}

.ur-plan-card--gold .ur-plan-card__cta--primary {
    background: #2563eb;
    border: none;
    color: #ffffff;
}

.ur-plan-card--gold .ur-plan-card__cta--primary:hover {
    background: #1d4ed8;
    box-shadow: 0 8px 24px rgba(37, 99, 235, 0.35);
}

/* ─── PLATINUM CARD THEME (HARMONIOUS PREMIUM WHITE) ────────────────── */
.ur-plan-card--platinum {
    background: #ffffff;
    border: 1px solid rgba(15, 23, 42, 0.08);
    box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
}

.ur-plan-card--platinum .ur-plan-card__badge {
    background: #475569;
    color: #ffffff;
}

.ur-plan-card--platinum .ur-plan-card__name {
    color: #0f172a;
}

.ur-plan-card--platinum .ur-plan-card__desc {
    color: #64748b;
}

.ur-plan-card--platinum .ur-plan-card__amount {
    color: #0f172a;
}

.ur-plan-card--platinum .ur-plan-card__currency {
    color: #2563eb;
}

.ur-plan-card--platinum .ur-plan-card__period {
    color: #64748b;
}

.ur-plan-card--platinum .ur-plan-card__price-note {
    color: #64748b;
}

.ur-plan-card--platinum .ur-plan-card__price-note i {
    color: #2563eb;
}

.ur-plan-card--platinum .ur-plan-card__divider {
    background: #e2e8f0;
}

.ur-plan-card--platinum .ur-plan-card__features li {
    color: #334155;
}

.ur-plan-card--platinum .ur-plan-card__f-icon {
    background: rgba(37, 99, 235, 0.08);
    color: #2563eb;
}

.ur-plan-card--platinum .ur-plan-card__cta--outline {
    background: #ffffff;
    color: #0f172a;
    border: 1.5px solid #cbd5e1;
}

.ur-plan-card--platinum .ur-plan-card__cta--outline:hover {
    background: #f8fafc;
    border-color: #2563eb;
    color: #2563eb;
}

.ur-plan-card--platinum .ur-plan-card__icon {
    border-color: rgba(56, 189, 248, 0.2);
}

.ur-plan-card--platinum:hover .ur-plan-card__icon {
    background: #38bdf8;
    color: #0d1527;
    box-shadow: 0 0 25px rgba(56, 189, 248, 0.5);
}

/* ─── CARD COMPONENTS ──────────────────── */
.ur-plan-card__badge {
    position: absolute;
    top: 1.25rem;
    right: 1.25rem;
    font-size: 0.6rem;
    font-weight: 800;
    color: #ffffff;
    background: #0f172a;
    padding: 0.35rem 0.75rem;
    border-radius: 9999px;
    text-transform: uppercase;
    letter-spacing: 0.12em;
    display: inline-flex;
    align-items: center;
    z-index: 10;
}

.ur-plan-card__icon {
    width: 4rem;
    height: 4rem;
    border-radius: 1.25rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
    margin-bottom: 1.5rem;
    background: var(--plan-bg);
    color: var(--plan-accent);
    box-shadow: 0 12px 28px var(--plan-glow);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    border: 2px solid color-mix(in srgb, var(--plan-accent) 20%, transparent);
}

.ur-plan-card:hover .ur-plan-card__icon {
    transform: scale(1.12) rotate(8deg);
    background: var(--plan-accent);
    color: #ffffff;
    box-shadow: 0 18px 40px var(--plan-glow);
}

.ur-plan-card__name {
    font-size: 1.4rem;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 0.35rem;
    letter-spacing: -0.02em;
}

.ur-plan-card__desc {
    font-size: 0.875rem;
    color: #64748b;
    line-height: 1.55;
    margin-bottom: 1.25rem;
    min-height: 2.75rem;
}

.ur-plan-card__price {
    display: flex;
    align-items: baseline;
    gap: 0.35rem;
    margin-bottom: 0.35rem;
}

.ur-plan-card__currency {
    font-size: 1.25rem;
    font-weight: 800;
    color: var(--plan-accent);
}

.ur-plan-card__amount {
    font-size: 2.75rem;
    font-weight: 900;
    color: #0f172a;
    letter-spacing: -0.04em;
    line-height: 1;
}

.ur-plan-card__period {
    font-size: 0.8125rem;
    font-weight: 600;
    color: #64748b;
    text-transform: lowercase;
    letter-spacing: 0;
    padding-bottom: 0.25rem;
}

.ur-plan-card__price-note {
    font-size: 0.75rem;
    font-weight: 500;
    color: #64748b;
    margin-bottom: 1.25rem;
    display: flex;
    align-items: center;
    gap: 0.4rem;
}

.ur-plan-card__price-note i {
    font-size: 0.9rem;
    color: #10b981;
}

/* ─── DIVIDER ──────────────────────────── */
.ur-plan-card__divider {
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(15, 23, 42, 0.08), transparent);
    margin-bottom: 1.25rem;
}

/* ─── FEATURES LIST ────────────────────── */
.ur-plan-card__features {
    list-style: none;
    padding: 0;
    margin: 0 0 2rem;
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 0.8rem;
}

.ur-plan-card__features li {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 0.8125rem;
    color: #334155;
    line-height: 1.45;
    transition: transform 0.25s ease;
}

.ur-plan-card__features li strong {
    color: #0f172a;
    font-weight: 700;
}

.ur-plan-card--platinum .ur-plan-card__features li strong {
    color: #ffffff;
}

.ur-plan-card:hover .ur-plan-card__features li {
    transform: translateX(2px);
}

.ur-plan-card__f-icon {
    width: 1.5rem;
    height: 1.5rem;
    min-width: 1.5rem;
    background: var(--plan-check-bg, #eff6ff);
    color: var(--plan-accent, #2563eb);
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    margin-top: 0.05rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.ur-plan-card:hover .ur-plan-card__f-icon {
    transform: scale(1.1) rotate(-5deg);
}

/* ─── CTA BUTTONS ──────────────────────── */
.ur-plan-card__cta {
    margin-top: auto;
    display: flex;
    width: 100%;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    text-align: center;
    padding: 0.875rem 1.5rem;
    border-radius: 1rem;
    font-size: 0.8125rem;
    font-weight: 800;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: none;
    cursor: pointer;
}

.ur-plan-card__cta i {
    font-size: 1rem;
    transition: transform 0.3s ease;
}

.ur-plan-card__cta:hover i {
    transform: translateX(4px);
}

.ur-plan-card__cta--primary {
    background: #0f172a;
    color: #ffffff;
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.15);
}

.ur-plan-card__cta--primary:hover {
    background: #2563eb;
    transform: translateY(-2px);
    box-shadow: 0 16px 40px rgba(37, 99, 235, 0.25);
}

.ur-plan-card__cta--outline {
    background: transparent;
    color: #0f172a;
    border: 1.5px solid rgba(15, 23, 42, 0.12);
}

.ur-plan-card__cta--outline:hover {
    border-color: #2563eb;
    color: #2563eb;
    background: #eff6ff;
}

/* ─── VIEW ALL PLANS LINK ──────────────── */
.ur-plans__cta-wrap {
    text-align: center;
    margin-top: 3rem;
}

.ur-plans__cta-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    font-weight: 700;
    color: #2563eb;
    text-decoration: none;
    padding: 0.75rem 2rem;
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

        {{-- Billing Toggle Switch --}}
        <div class="ur-plans__toggle-wrap">
            <span class="ur-plans__toggle-label active" id="billing-monthly">Rental Pass (Monthly)</span>
            <button type="button" class="ur-plans__toggle-switch" id="billing-toggle-btn" aria-label="Toggle billing period">
                <span class="ur-plans__toggle-handle"></span>
            </button>
            <span class="ur-plans__toggle-label" id="billing-yearly">
                Buyer Pass (Annual)
                <span class="ur-plans__discount-badge">Save 20%</span>
            </span>
        </div>

        {{-- Plans Slider Wrapper --}}
        <div class="ur-slider-wrapper">
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
                             data-has-monthly-offer="{{ $monthlyOffer ? 'true' : 'false' }}"
                             data-has-yearly-offer="{{ $yearlyOffer ? 'true' : 'false' }}">

                            @if($isGold)
                                <span class="ur-plan-card__badge"><i class="ph-bold ph-fire" style="margin-right:3px"></i> Most Popular</span>
                            @elseif($isPlatinum)
                                <span class="ur-plan-card__badge"><i class="ph-bold ph-lightning" style="margin-right:3px"></i> VIP Choice</span>
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

                            <a href="{{ Route::has('plans.checkout') ? route('plans.checkout', ['plan' => $plan, 'billing' => 'monthly']) : url('/plans') }}" class="ur-plan-card__cta {{ $isGold ? 'ur-plan-card__cta--primary' : 'ur-plan-card__cta--outline' }} plan-checkout-link" title="UnlockRentals">
                                <i class="ph-bold {{ $isGold ? 'ph-crown' : ($isPlatinum ? 'ph-lightning' : 'ph-arrow-right') }}"></i>
                                <span>{{ $isGold ? 'Get Started with Gold' : ($isPlatinum ? 'Unlock Platinum VIP' : 'Choose Silver Plan') }}</span>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Slider Navigation Controls --}}
            <div class="ur-slider-controls">
                <button type="button" class="ur-slider-btn ur-slider-btn--prev" aria-label="Previous slide">
                    <i class="ph-bold ph-caret-left"></i>
                </button>
                <div class="ur-slider-dots">
                    @foreach($homePlans as $index => $plan)
                        <span class="ur-slider-dot @if($index === 0) active @endif" data-index="{{ $index }}"></span>
                    @endforeach
                </div>
                <button type="button" class="ur-slider-btn ur-slider-btn--next" aria-label="Next slide">
                    <i class="ph-bold ph-caret-right"></i>
                </button>
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
        const prevBtn = document.querySelector('.ur-slider-btn--prev');
        const nextBtn = document.querySelector('.ur-slider-btn--next');
        const dots = document.querySelectorAll('.ur-slider-dot');
        const cards = document.querySelectorAll('.ur-plan-card');

        if (!grid || !cards.length) return;

        // Billing Toggle Logic
        const toggleBtn = document.getElementById('billing-toggle-btn');
        const monthlyLabel = document.getElementById('billing-monthly');
        const yearlyLabel = document.getElementById('billing-yearly');
        const priceAmounts = document.querySelectorAll('.ur-plan-card__amount');
        const priceNotes = document.querySelectorAll('.price-note-text');
        const billingInputs = document.querySelectorAll('.billing-period-input');
        
        let isYearly = false;
        
        function updateBillingPeriod(yearly) {
            isYearly = yearly;
            
            if (toggleBtn) toggleBtn.classList.toggle('active', isYearly);
            if (monthlyLabel) monthlyLabel.classList.toggle('active', !isYearly);
            if (yearlyLabel) yearlyLabel.classList.toggle('active', isYearly);
            
            billingInputs.forEach(input => {
                input.value = isYearly ? 'yearly' : 'monthly';
            });
            
            cards.forEach(card => {
                const amountEl = card.querySelector('.ur-plan-card__amount');
                const badgeEl = card.querySelector('.special-offer-badge');
                
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
                const url = new URL(link.href, window.location.origin);
                url.searchParams.set('billing', isYearly ? 'yearly' : 'monthly');
                link.href = url.pathname + url.search;
            });
        }
        
        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                updateBillingPeriod(!isYearly);
            });
        }
        if (monthlyLabel) {
            monthlyLabel.addEventListener('click', () => {
                if (isYearly) updateBillingPeriod(false);
            });
        }
        if (yearlyLabel) {
            yearlyLabel.addEventListener('click', () => {
                if (!isYearly) updateBillingPeriod(true);
            });
        }

        let currentIndex = 0;

        function getItemsPerView() {
            if (window.innerWidth >= 1024) return 3;
            if (window.innerWidth >= 768) return 2;
            return 1;
        }

        function getMaxIndex() {
            return Math.max(0, cards.length - getItemsPerView());
        }

        function updateSlider() {
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

            grid.style.transform = `translateX(-${offset}px)`;

            // Enable/disable buttons
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

            // Show controls only if there are items to slide
            const showControls = cards.length > itemsPerView;
            const controls = document.querySelector('.ur-slider-controls');
            if (controls) {
                controls.style.display = showControls ? 'flex' : 'none';
            }
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                if (currentIndex > 0) {
                    currentIndex--;
                    updateSlider();
                }
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                if (currentIndex < getMaxIndex()) {
                    currentIndex++;
                    updateSlider();
                }
            });
        }

        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                currentIndex = index;
                updateSlider();
            });
        });

        // Touch swipe support
        let startX = 0;
        let currentX = 0;
        let isSwiping = false;

        container.addEventListener('touchstart', (e) => {
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
            if (Math.abs(diffX) > 50) {
                if (diffX > 0) {
                    if (currentIndex < getMaxIndex()) {
                        currentIndex++;
                        updateSlider();
                    }
                } else {
                    if (currentIndex > 0) {
                        currentIndex--;
                        updateSlider();
                    }
                }
            }
        });

        let resizeTimeout;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(updateSlider, 100);
        });

        document.querySelectorAll('.plan-checkout-link').forEach(link => {
            link.addEventListener('click', () => {
                link.style.pointerEvents = 'none';
                link.style.opacity = '0.7';
                link.innerHTML = '<i class="ph-bold ph-circle-notch animate-spin" style="margin-right:6px"></i> Opening secure checkout...';
            });
        });

        // Initial update
        updateSlider();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPricingSlider);
    } else {
        initPricingSlider();
    }
})();
</script>
@endif
