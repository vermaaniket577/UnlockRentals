{{-- ============================================================
     UNLOCK RENTALS — PRICING PLANS (HOMEPAGE PREVIEW)
     ============================================================ --}}

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
    transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
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
        flex: 0 0 85%;
    }
}

@media (min-width: 1024px) {
    .ur-plans__slider-container {
        overflow: visible;
        cursor: default;
    }
    .ur-plans__grid {
        display: grid !important;
        grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
        gap: 1.5rem !important;
        transform: none !important;
        transition: none !important;
        width: 100% !important;
    }
    .ur-plan-card {
        flex: none !important;
        width: 100% !important;
    }
    .ur-slider-controls,
    .ur-slider-progress-wrap,
    .ur-plan-tabs {
        display: none !important;
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
    $rentPlans = \App\Models\Plan::active()
        ->where('is_private', false)
        ->whereIn('purpose', ['rent', 'both', null])
        ->orderBy('sort_order')
        ->take(3)
        ->get();

    $buyPlans = \App\Models\Plan::active()
        ->where('is_private', false)
        ->whereIn('purpose', ['buy', 'sale'])
        ->orderBy('sort_order')
        ->take(3)
        ->get();

    if ($buyPlans->isEmpty()) {
        $buyPlans = $rentPlans;
    }
@endphp

@if($rentPlans->count())
<section class="ur-plans" id="pricing-plans">
    <div class="ur-plans__accent ur-plans__accent--1"></div>
    <div class="ur-plans__accent ur-plans__accent--2"></div>

    <div class="ur-plans__container">
        {{-- Header --}}
        <div class="ur-plans__header">
            <span class="ur-plans__eyebrow">
                <i class="ph-bold ph-shield-check"></i>
                Zero Brokerage Plans
            </span>
            <h2 class="ur-plans__title">Unlock <span>Direct Owner Contacts</span></h2>
            <p class="ur-plans__subtitle">Choose a plan to unlock verified owner contacts, WhatsApp chat, and private visit scheduling.</p>
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

        {{-- 1. Rental Plans Slider Wrapper --}}
        <div class="ur-slider-wrapper" id="ur-rental-slider-wrapper">
            <div class="ur-plans__slider-container">
                <div class="ur-plans__grid">
                    @foreach($rentPlans as $index => $plan)
                        @php
                            $isGold = str_contains(strtolower($plan->name), 'gold') || str_contains(strtolower($plan->name), 'pro') || str_contains(strtolower($plan->name), 'popular');
                            $isPlatinum = str_contains(strtolower($plan->name), 'plat') || str_contains(strtolower($plan->name), 'diamond');
                            $isSilver = !$isGold && !$isPlatinum;
                            
                            $cardThemeClass = $isGold ? 'ur-plan-card--gold' : ($isPlatinum ? 'ur-plan-card--platinum' : 'ur-plan-card--silver');

                            $monthlyOffer = isset($userOffers) ? $userOffers->where('plan_id', $plan->id)->where('billing_period', 'monthly')->first() : null;
                            $originalPrice = (float) $plan->price;
                            $price = ($monthlyOffer && $monthlyOffer->discounted_price !== null) ? (float) $monthlyOffer->discounted_price : $originalPrice;
                            $planUid = 'home_rent_' . $plan->id;
                        @endphp
                        <div class="ur-plan-card {{ $cardThemeClass }}" data-plan-index="{{ $index }}">

                            @if($isGold)
                                <span class="ur-plan-card__badge"><i class="ph-bold ph-fire"></i> Most Popular</span>
                            @elseif($isPlatinum)
                                <span class="ur-plan-card__badge"><i class="ph-bold ph-lightning"></i> VIP Choice</span>
                            @endif

                            {{-- High-Definition Vector SVG Icon --}}
                            <div class="ur-plan-card__icon" style="position: relative; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                @if($plan->image_path)
                                    <img src="{{ asset('storage/' . $plan->image_path) }}" alt="{{ $plan->name }}" style="width: 2.25rem; height: 2.25rem; object-fit: contain;">
                                @elseif($isGold)
                                    {{-- Luxury 3D Imperial Gold Crown --}}
                                    <svg style="width: 2.25rem; height: 2.25rem;" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <defs>
                                            <linearGradient id="goldG_{{ $planUid }}" x1="4" y1="8" x2="44" y2="40" gradientUnits="userSpaceOnUse">
                                                <stop offset="0%" stop-color="#FDE047"/>
                                                <stop offset="45%" stop-color="#F59E0B"/>
                                                <stop offset="100%" stop-color="#D97706"/>
                                            </linearGradient>
                                            <linearGradient id="goldB_{{ $planUid }}" x1="8" y1="34" x2="40" y2="38" gradientUnits="userSpaceOnUse">
                                                <stop offset="0%" stop-color="#FFFBEB"/>
                                                <stop offset="100%" stop-color="#FDE68A"/>
                                            </linearGradient>
                                        </defs>
                                        <path d="M6 34L10 14L19 23L24 8L29 23L38 14L42 34H6Z" fill="url(#goldG_{{ $planUid }})"/>
                                        <rect x="6" y="34" width="36" height="6" rx="3" fill="#B45309"/>
                                        <rect x="8" y="35" width="32" height="4" rx="2" fill="url(#goldB_{{ $planUid }})"/>
                                        <circle cx="24" cy="8" r="3.5" fill="#EF4444" stroke="#FFF" stroke-width="1.5"/>
                                        <circle cx="10" cy="14" r="3" fill="#3B82F6" stroke="#FFF" stroke-width="1.5"/>
                                        <circle cx="38" cy="14" r="3" fill="#3B82F6" stroke="#FFF" stroke-width="1.5"/>
                                        <circle cx="16" cy="37" r="1.5" fill="#EF4444"/>
                                        <circle cx="24" cy="37" r="2" fill="#10B981"/>
                                        <circle cx="32" cy="37" r="1.5" fill="#EF4444"/>
                                    </svg>
                                @elseif($isPlatinum)
                                    {{-- Brilliant Cut Royal Sapphire Diamond --}}
                                    <svg style="width: 2.25rem; height: 2.25rem;" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <defs>
                                            <linearGradient id="platG1_{{ $planUid }}" x1="6" y1="10" x2="42" y2="42" gradientUnits="userSpaceOnUse">
                                                <stop offset="0%" stop-color="#60A5FA"/>
                                                <stop offset="50%" stop-color="#3B82F6"/>
                                                <stop offset="100%" stop-color="#1D4ED8"/>
                                            </linearGradient>
                                            <linearGradient id="platFacet_{{ $planUid }}" x1="14" y1="10" x2="34" y2="20" gradientUnits="userSpaceOnUse">
                                                <stop offset="0%" stop-color="#EFF6FF"/>
                                                <stop offset="100%" stop-color="#BFDBFE"/>
                                            </linearGradient>
                                        </defs>
                                        <polygon points="14,10 34,10 42,20 6,20" fill="url(#platG1_{{ $planUid }})"/>
                                        <polygon points="18,10 30,10 33,20 15,20" fill="url(#platFacet_{{ $planUid }})"/>
                                        <polygon points="6,20 42,20 24,42" fill="url(#platG1_{{ $planUid }})"/>
                                        <polygon points="15,20 33,20 24,42" fill="#93C5FD" fill-opacity="0.9"/>
                                        <path d="M37 7L38.5 11.5L43 13L38.5 14.5L37 19L35.5 14.5L31 13L35.5 11.5L37 7Z" fill="#FFFFFF"/>
                                    </svg>
                                @else
                                    {{-- High-End Metallic Silver Shield --}}
                                    <svg style="width: 2.25rem; height: 2.25rem;" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <defs>
                                            <linearGradient id="silvG_{{ $planUid }}" x1="8" y1="4" x2="40" y2="44" gradientUnits="userSpaceOnUse">
                                                <stop offset="0%" stop-color="#CBD5E1"/>
                                                <stop offset="40%" stop-color="#64748B"/>
                                                <stop offset="100%" stop-color="#334155"/>
                                            </linearGradient>
                                            <linearGradient id="silvShine_{{ $planUid }}" x1="12" y1="8" x2="36" y2="36" gradientUnits="userSpaceOnUse">
                                                <stop offset="0%" stop-color="#F8FAFC" stop-opacity="0.9"/>
                                                <stop offset="100%" stop-color="#94A3B8" stop-opacity="0.3"/>
                                            </linearGradient>
                                        </defs>
                                        <path d="M24 4L8 10V22C8 32.5 14.8 42.2 24 44C33.2 42.2 40 32.5 40 22V10L24 4Z" fill="url(#silvG_{{ $planUid }})"/>
                                        <path d="M24 7L11 12V21.5C11 30.2 16.5 38.3 24 40C31.5 38.3 37 30.2 37 21.5V12L24 7Z" fill="url(#silvShine_{{ $planUid }})"/>
                                        <path d="M24 16L26.3 21.2L32 21.8L27.8 25.6L29 31.2L24 28.3L19 31.2L20.2 25.6L16 21.8L21.7 21.2L24 16Z" fill="#FFFFFF"/>
                                    </svg>
                                @endif
                            </div>

                            <h3 class="ur-plan-card__name">{{ $plan->name }}</h3>
                            <p class="ur-plan-card__desc">{{ $plan->description ?? 'Direct owner contact access for verified rental listings.' }}</p>

                            <div class="ur-plan-card__price" style="flex-wrap: wrap; align-items: baseline;">
                                <span class="ur-plan-card__currency">₹</span>
                                <span class="ur-plan-card__amount">{{ number_format($price, 0) }}</span>
                                <span class="ur-plan-card__period">/ rent pass</span>
                            </div>
                            <div class="ur-plan-card__price-note">
                                <i class="ph-bold ph-calendar-check"></i>
                                <span class="price-note-text">{{ $plan->duration_days }} Days Validity · Zero Brokerage</span>
                            </div>

                            <div class="ur-plan-card__divider"></div>

                            <ul class="ur-plan-card__features">
                                <li>
                                    <span class="ur-plan-card__f-icon"><i class="ph-bold ph-lock-key-open"></i></span>
                                    <span><strong>{{ $plan->contact_limit }} Verified Owner</strong> Direct Contacts</span>
                                </li>
                                <li>
                                    <span class="ur-plan-card__f-icon"><i class="ph-bold ph-phone-call"></i></span>
                                    <span>Direct Phone & WhatsApp Unlock</span>
                                </li>
                                <li>
                                    <span class="ur-plan-card__f-icon"><i class="ph-bold ph-shield-check"></i></span>
                                    <span>Zero Brokerage Guaranteed</span>
                                </li>
                                @if($plan->features && is_array($plan->features))
                                    @foreach(array_slice($plan->features, 0, 3) as $feature)
                                        @if(!empty(trim($feature)))
                                            <li>
                                                <span class="ur-plan-card__f-icon"><i class="ph-bold ph-check-circle"></i></span>
                                                <span>{{ $feature }}</span>
                                            </li>
                                        @endif
                                    @endforeach
                                @endif
                            </ul>

                            {{-- High-Impact Pay Now Button --}}
                            <div class="ur-plan-card__pay-cta">
                                @php
                                    $checkoutUrl = route('plans.checkout', ['plan' => $plan, 'billing' => 'monthly', 'direct' => 1]);
                                @endphp
                                @guest
                                    <a href="{{ route('login', ['redirect' => $checkoutUrl]) }}" 
                                       onclick="event.preventDefault(); event.stopPropagation(); window.openAuthModal('login', '{{ $checkoutUrl }}');"
                                       class="ur-plan-card__cta-btn {{ ($isGold || $isPlatinum) ? 'ur-plan-card__cta-btn--primary' : 'ur-plan-card__cta-btn--secondary' }} plan-checkout-link" 
                                       title="Pay Now &amp; Unlock Verified Contacts">
                                        <i class="ph-bold ph-lightning-fill pay-now-icon"></i>
                                        <span>Unlock Contacts</span>
                                        <span class="pay-amount-pill">₹{{ number_format($price, 0) }}</span>
                                    </a>
                                @else
                                    <a href="{{ $checkoutUrl }}" 
                                       class="ur-plan-card__cta-btn {{ ($isGold || $isPlatinum) ? 'ur-plan-card__cta-btn--primary' : 'ur-plan-card__cta-btn--secondary' }} plan-checkout-link" 
                                       title="Pay Now &amp; Unlock Verified Contacts">
                                        <i class="ph-bold ph-lightning-fill pay-now-icon"></i>
                                        <span>Unlock Contacts</span>
                                        <span class="pay-amount-pill">₹{{ number_format($price, 0) }}</span>
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
        </div>

        {{-- 2. Buyer Plans Slider Wrapper (Shown when Buyer Pass selected) --}}
        <div class="ur-slider-wrapper" id="ur-buyer-slider-wrapper" style="display: none;">
            <div class="ur-plans__slider-container">
                <div class="ur-plans__grid">
                    @foreach($buyPlans as $index => $plan)
                        @php
                            $isGold = str_contains(strtolower($plan->name), 'gold') || str_contains(strtolower($plan->name), 'pro') || str_contains(strtolower($plan->name), 'popular');
                            $isPlatinum = str_contains(strtolower($plan->name), 'plat') || str_contains(strtolower($plan->name), 'diamond');
                            $isSilver = !$isGold && !$isPlatinum;
                            
                            $cardThemeClass = $isGold ? 'ur-plan-card--gold' : ($isPlatinum ? 'ur-plan-card--platinum' : 'ur-plan-card--silver');

                            $price = (float) $plan->price;
                            $planUid = 'home_buy_' . $plan->id;
                        @endphp
                        <div class="ur-plan-card {{ $cardThemeClass }}" data-plan-index="{{ $index }}">

                            @if($isGold)
                                <span class="ur-plan-card__badge"><i class="ph-bold ph-fire"></i> Most Popular</span>
                            @elseif($isPlatinum)
                                <span class="ur-plan-card__badge"><i class="ph-bold ph-lightning"></i> VIP Choice</span>
                            @endif

                            {{-- High-Definition Vector SVG Icon --}}
                            <div class="ur-plan-card__icon" style="position: relative; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                @if($plan->image_path)
                                    <img src="{{ asset('storage/' . $plan->image_path) }}" alt="{{ $plan->name }}" style="width: 2.25rem; height: 2.25rem; object-fit: contain;">
                                @elseif($isGold)
                                    {{-- Luxury 3D Imperial Gold Crown --}}
                                    <svg style="width: 2.25rem; height: 2.25rem;" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <defs>
                                            <linearGradient id="goldG_{{ $planUid }}" x1="4" y1="8" x2="44" y2="40" gradientUnits="userSpaceOnUse">
                                                <stop offset="0%" stop-color="#FDE047"/>
                                                <stop offset="45%" stop-color="#F59E0B"/>
                                                <stop offset="100%" stop-color="#D97706"/>
                                            </linearGradient>
                                            <linearGradient id="goldB_{{ $planUid }}" x1="8" y1="34" x2="40" y2="38" gradientUnits="userSpaceOnUse">
                                                <stop offset="0%" stop-color="#FFFBEB"/>
                                                <stop offset="100%" stop-color="#FDE68A"/>
                                            </linearGradient>
                                        </defs>
                                        <path d="M6 34L10 14L19 23L24 8L29 23L38 14L42 34H6Z" fill="url(#goldG_{{ $planUid }})"/>
                                        <rect x="6" y="34" width="36" height="6" rx="3" fill="#B45309"/>
                                        <rect x="8" y="35" width="32" height="4" rx="2" fill="url(#goldB_{{ $planUid }})"/>
                                        <circle cx="24" cy="8" r="3.5" fill="#EF4444" stroke="#FFF" stroke-width="1.5"/>
                                        <circle cx="10" cy="14" r="3" fill="#3B82F6" stroke="#FFF" stroke-width="1.5"/>
                                        <circle cx="38" cy="14" r="3" fill="#3B82F6" stroke="#FFF" stroke-width="1.5"/>
                                        <circle cx="16" cy="37" r="1.5" fill="#EF4444"/>
                                        <circle cx="24" cy="37" r="2" fill="#10B981"/>
                                        <circle cx="32" cy="37" r="1.5" fill="#EF4444"/>
                                    </svg>
                                @elseif($isPlatinum)
                                    {{-- Brilliant Cut Royal Sapphire Diamond --}}
                                    <svg style="width: 2.25rem; height: 2.25rem;" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <defs>
                                            <linearGradient id="platG1_{{ $planUid }}" x1="6" y1="10" x2="42" y2="42" gradientUnits="userSpaceOnUse">
                                                <stop offset="0%" stop-color="#60A5FA"/>
                                                <stop offset="50%" stop-color="#3B82F6"/>
                                                <stop offset="100%" stop-color="#1D4ED8"/>
                                            </linearGradient>
                                            <linearGradient id="platFacet_{{ $planUid }}" x1="14" y1="10" x2="34" y2="20" gradientUnits="userSpaceOnUse">
                                                <stop offset="0%" stop-color="#EFF6FF"/>
                                                <stop offset="100%" stop-color="#BFDBFE"/>
                                            </linearGradient>
                                        </defs>
                                        <polygon points="14,10 34,10 42,20 6,20" fill="url(#platG1_{{ $planUid }})"/>
                                        <polygon points="18,10 30,10 33,20 15,20" fill="url(#platFacet_{{ $planUid }})"/>
                                        <polygon points="6,20 42,20 24,42" fill="url(#platG1_{{ $planUid }})"/>
                                        <polygon points="15,20 33,20 24,42" fill="#93C5FD" fill-opacity="0.9"/>
                                        <path d="M37 7L38.5 11.5L43 13L38.5 14.5L37 19L35.5 14.5L31 13L35.5 11.5L37 7Z" fill="#FFFFFF"/>
                                    </svg>
                                @else
                                    {{-- High-End Metallic Silver Shield --}}
                                    <svg style="width: 2.25rem; height: 2.25rem;" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <defs>
                                            <linearGradient id="silvG_{{ $planUid }}" x1="8" y1="4" x2="40" y2="44" gradientUnits="userSpaceOnUse">
                                                <stop offset="0%" stop-color="#CBD5E1"/>
                                                <stop offset="40%" stop-color="#64748B"/>
                                                <stop offset="100%" stop-color="#334155"/>
                                            </linearGradient>
                                            <linearGradient id="silvShine_{{ $planUid }}" x1="12" y1="8" x2="36" y2="36" gradientUnits="userSpaceOnUse">
                                                <stop offset="0%" stop-color="#F8FAFC" stop-opacity="0.9"/>
                                                <stop offset="100%" stop-color="#94A3B8" stop-opacity="0.3"/>
                                            </linearGradient>
                                        </defs>
                                        <path d="M24 4L8 10V22C8 32.5 14.8 42.2 24 44C33.2 42.2 40 32.5 40 22V10L24 4Z" fill="url(#silvG_{{ $planUid }})"/>
                                        <path d="M24 7L11 12V21.5C11 30.2 16.5 38.3 24 40C31.5 38.3 37 30.2 37 21.5V12L24 7Z" fill="url(#silvShine_{{ $planUid }})"/>
                                        <path d="M24 16L26.3 21.2L32 21.8L27.8 25.6L29 31.2L24 28.3L19 31.2L20.2 25.6L16 21.8L21.7 21.2L24 16Z" fill="#FFFFFF"/>
                                    </svg>
                                @endif
                            </div>

                            <h3 class="ur-plan-card__name">{{ $plan->name }}</h3>
                            <p class="ur-plan-card__desc">{{ $plan->description ?? 'Direct owner contact access for verified property purchases.' }}</p>

                            <div class="ur-plan-card__price" style="flex-wrap: wrap; align-items: baseline;">
                                <span class="ur-plan-card__currency">₹</span>
                                <span class="ur-plan-card__amount">{{ number_format($price, 0) }}</span>
                                <span class="ur-plan-card__period">/ annual pass</span>
                            </div>
                            <div class="ur-plan-card__price-note">
                                <i class="ph-bold ph-shield-check"></i>
                                <span class="price-note-text">{{ $plan->duration_days }} Days Priority Buyer Access</span>
                            </div>

                            <div class="ur-plan-card__divider"></div>

                            <ul class="ur-plan-card__features">
                                <li>
                                    <span class="ur-plan-card__f-icon"><i class="ph-bold ph-lock-key-open"></i></span>
                                    <span><strong>{{ $plan->contact_limit }} Verified Seller</strong> Direct Contacts</span>
                                </li>
                                <li>
                                    <span class="ur-plan-card__f-icon"><i class="ph-bold ph-phone-call"></i></span>
                                    <span>Direct Phone & WhatsApp Unlock</span>
                                </li>
                                <li>
                                    <span class="ur-plan-card__f-icon"><i class="ph-bold ph-shield-check"></i></span>
                                    <span>Zero Brokerage Guaranteed</span>
                                </li>
                                @if($plan->features && is_array($plan->features))
                                    @foreach(array_slice($plan->features, 0, 3) as $feature)
                                        @if(!empty(trim($feature)))
                                            <li>
                                                <span class="ur-plan-card__f-icon"><i class="ph-bold ph-check-circle"></i></span>
                                                <span>{{ $feature }}</span>
                                            </li>
                                        @endif
                                    @endforeach
                                @endif
                            </ul>

                            {{-- High-Impact Pay Now Button --}}
                            <div class="ur-plan-card__pay-cta">
                                @php
                                    $checkoutUrl = route('plans.checkout', ['plan' => $plan, 'billing' => 'yearly', 'direct' => 1]);
                                @endphp
                                @guest
                                    <a href="{{ route('login', ['redirect' => $checkoutUrl]) }}" 
                                       onclick="event.preventDefault(); event.stopPropagation(); window.openAuthModal('login', '{{ $checkoutUrl }}');"
                                       class="ur-plan-card__cta-btn {{ ($isGold || $isPlatinum) ? 'ur-plan-card__cta-btn--primary' : 'ur-plan-card__cta-btn--secondary' }} plan-checkout-link" 
                                       title="Pay Now &amp; Unlock Verified Contacts">
                                        <i class="ph-bold ph-lightning-fill pay-now-icon"></i>
                                        <span>Unlock Buyer Pass</span>
                                        <span class="pay-amount-pill">₹{{ number_format($price, 0) }}</span>
                                    </a>
                                @else
                                    <a href="{{ $checkoutUrl }}" 
                                       class="ur-plan-card__cta-btn {{ ($isGold || $isPlatinum) ? 'ur-plan-card__cta-btn--primary' : 'ur-plan-card__cta-btn--secondary' }} plan-checkout-link" 
                                       title="Pay Now &amp; Unlock Verified Contacts">
                                        <i class="ph-bold ph-lightning-fill pay-now-icon"></i>
                                        <span>Unlock Buyer Pass</span>
                                        <span class="pay-amount-pill">₹{{ number_format($price, 0) }}</span>
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
        const rentalWrapper = document.getElementById('ur-rental-slider-wrapper');
        const buyerWrapper = document.getElementById('ur-buyer-slider-wrapper');
        
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
            
            if (rentalWrapper && buyerWrapper) {
                rentalWrapper.style.display = isYearly ? 'none' : 'block';
                buyerWrapper.style.display = isYearly ? 'block' : 'none';
            }
        }
        
        monthlyBtn?.addEventListener('click', () => {
            if (isYearly) updateBillingPeriod(false);
        });
        yearlyBtn?.addEventListener('click', () => {
            if (!isYearly) updateBillingPeriod(true);
        });

        // Setup Mobile-Only Swipe & Slider
        function setupMobileSlider(wrapper) {
            if (!wrapper) return;
            const currentGrid = wrapper.querySelector('.ur-plans__grid');
            const currentContainer = wrapper.querySelector('.ur-plans__slider-container');
            const currentCards = wrapper.querySelectorAll('.ur-plan-card');
            
            if (!currentGrid || !currentContainer || !currentCards.length) return;

            let currentIndex = 0;

            function isDesktop() {
                return window.innerWidth >= 1024;
            }

            function updateSlider(smooth = true) {
                if (isDesktop()) {
                    currentGrid.style.transform = 'none';
                    currentGrid.style.transition = 'none';
                    return;
                }

                const maxIndex = currentCards.length - 1;
                if (currentIndex > maxIndex) currentIndex = maxIndex;
                if (currentIndex < 0) currentIndex = 0;

                const cardWidth = currentCards[0].offsetWidth;
                const gap = parseFloat(window.getComputedStyle(currentGrid).gap) || 20;
                const offset = currentIndex * (cardWidth + gap);

                currentGrid.style.transition = smooth ? 'transform 0.4s cubic-bezier(0.16, 1, 0.3, 1)' : 'none';
                currentGrid.style.transform = `translateX(-${offset}px)`;
            }

            // Mobile Touch Swipe Handling
            let startX = 0;
            let currentX = 0;
            let isSwiping = false;

            currentContainer.addEventListener('touchstart', (e) => {
                if (isDesktop()) return;
                startX = e.touches[0].clientX;
                isSwiping = true;
            }, { passive: true });

            currentContainer.addEventListener('touchmove', (e) => {
                if (!isSwiping || isDesktop()) return;
                currentX = e.touches[0].clientX;
            }, { passive: true });

            currentContainer.addEventListener('touchend', () => {
                if (!isSwiping || isDesktop()) return;
                isSwiping = false;
                const diffX = startX - currentX;
                if (Math.abs(diffX) > 40) {
                    if (diffX > 0 && currentIndex < currentCards.length - 1) {
                        currentIndex++;
                    } else if (diffX < 0 && currentIndex > 0) {
                        currentIndex--;
                    }
                    updateSlider(true);
                }
            });

            window.addEventListener('resize', () => {
                updateSlider(false);
            });

            updateSlider(false);
        }

        setupMobileSlider(rentalWrapper);
        setupMobileSlider(buyerWrapper);

        document.querySelectorAll('.plan-checkout-link').forEach(link => {
            link.addEventListener('click', () => {
                link.style.pointerEvents = 'none';
                link.style.opacity = '0.85';
                link.innerHTML = '<i class="ph-bold ph-circle-notch animate-spin" style="margin-right:6px"></i> Opening Secure Checkout...';
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPricingSlider);
    } else {
        initPricingSlider();
    }
})();
</script>
@endif
