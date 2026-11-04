@extends('layouts.app')

@section('title', 'Membership Plans & Pricing - UnlockRentals')
@section('meta_description', 'Choose an UnlockRentals subscription plan for instant direct owner contact unlocks, zero brokerage, verified phone numbers, and instant visit booking.')

@push('head')
<link rel="stylesheet" href="{{ asset('css/plans-page.css') }}?v={{ file_exists(public_path('css/plans-page.css')) ? filemtime(public_path('css/plans-page.css')) : time() }}">
@endpush

@section('content')
@php
    $paymentFailedReason = session('payment_failed_reason') ?: (request()->boolean('payment_failed') ? request('reason', 'Payment failed. Please try again or choose another payment method.') : null);
    $displayPlans = $plans->values();
    $hasEnterprise = $displayPlans->contains(fn($item) => str_contains(strtolower($item->name), 'enterprise'));

    if ($displayPlans->isEmpty()) {
        $displayPlans = \App\Models\Plan::public()->get();
    }
    $rentPlans = $displayPlans->filter(fn($p) => in_array($p->purpose, ['rent', 'both', null]))
        ->sortBy(fn($p) => sprintf('%04d_%08.2f', $p->sort_order ?? 0, (float)($p->price ?? 0)))
        ->values();
    $buyPlans = $displayPlans->filter(fn($p) => in_array($p->purpose, ['buy', 'sale', 'both']))
        ->sortBy(fn($p) => sprintf('%04d_%08.2f', $p->sort_order ?? 0, (float)($p->price ?? 0)))
        ->values();
    if ($buyPlans->isEmpty()) {
        $buyPlans = $rentPlans;
    }

    $requestedBilling = request('billing');
    $requestedPurpose = request('purpose');
    $initialBilling = ($requestedBilling === 'yearly' || in_array($requestedPurpose, ['buy', 'sale'])) ? 'yearly' : 'monthly';
    $isYearly = ($initialBilling === 'yearly');

    // Build combined plan list for tabs
    $allPlansForTabs = $isYearly ? $buyPlans : $rentPlans;
    $firstPlanId = $allPlansForTabs->first()?->id;
    $cleanTel = preg_replace('/[^0-9+]/', '', (string)($site_settings['site_phone'] ?? '+919425455499'));
@endphp

<div class="plans-page pb-24">

    {{-- ============================================
         HORIZONTAL TAB BAR (Sticky)
    ============================================ --}}
    <nav class="plans-tab-bar" id="plans-tab-bar" aria-label="Membership Plans">
        {{-- Rental Plan Tabs --}}
        <div id="rental-tabs" class="{{ $isYearly ? 'hidden' : '' }}" style="display: {{ $isYearly ? 'none' : 'flex' }}; align-items: center; gap: 0;">
            @foreach($rentPlans as $index => $plan)
                @php
                    $nameLower = strtolower($plan->name);
                    $isGold = str_contains($nameLower, 'gold') || str_contains($nameLower, 'pro') || str_contains($nameLower, 'popular');
                    $isPlatinum = str_contains($nameLower, 'plat') || str_contains($nameLower, 'diamond') || str_contains($nameLower, 'vip');
                    $tabBadge = $isGold ? 'MOST POPULAR' : ($isPlatinum ? 'VIP CHOICE' : '');
                    $tabBadgeClass = $isGold ? 'popular' : ($isPlatinum ? 'vip' : 'budget');
                @endphp
                <button type="button"
                    class="plan-tab {{ $index === 0 ? 'active' : '' }}"
                    data-plan-tab="rent_{{ $plan->id }}"
                    data-category="rental">
                    @if(str_contains($nameLower, 'silver') || str_contains($nameLower, 'start') || str_contains($nameLower, 'basic'))
                        <i class="ph-bold ph-shield-check tab-icon"></i>
                    @elseif($isGold)
                        <i class="ph-bold ph-crown tab-icon"></i>
                    @elseif($isPlatinum)
                        <i class="ph-bold ph-diamond tab-icon"></i>
                    @else
                        <i class="ph-bold ph-star tab-icon"></i>
                    @endif
                    <span>{{ strtoupper($plan->name) }}</span>
                    @if($tabBadge)
                        <span class="tab-badge {{ $tabBadgeClass }}">{{ $tabBadge }}</span>
                    @endif
                </button>
            @endforeach
            @unless($hasEnterprise)
                <button type="button" class="plan-tab" data-plan-tab="rent_enterprise" data-category="rental">
                    <i class="ph-bold ph-buildings tab-icon"></i>
                    <span>ENTERPRISE</span>
                </button>
            @endunless
        </div>

        {{-- Buyer Plan Tabs --}}
        <div id="buyer-tabs" class="{{ $isYearly ? '' : 'hidden' }}" style="display: {{ $isYearly ? 'flex' : 'none' }}; align-items: center; gap: 0;">
            @foreach($buyPlans as $index => $plan)
                @php
                    $nameLower = strtolower($plan->name);
                    $isGold = str_contains($nameLower, 'gold') || str_contains($nameLower, 'pro') || str_contains($nameLower, 'popular');
                    $isPlatinum = str_contains($nameLower, 'plat') || str_contains($nameLower, 'diamond') || str_contains($nameLower, 'vip');
                    $tabBadge = $isGold ? 'MOST POPULAR' : ($isPlatinum ? 'VIP CHOICE' : '');
                    $tabBadgeClass = $isGold ? 'popular' : ($isPlatinum ? 'vip' : 'budget');
                @endphp
                <button type="button"
                    class="plan-tab {{ $index === 0 ? 'active' : '' }}"
                    data-plan-tab="buy_{{ $plan->id }}"
                    data-category="buyer">
                    @if(str_contains($nameLower, 'silver') || str_contains($nameLower, 'start') || str_contains($nameLower, 'basic'))
                        <i class="ph-bold ph-shield-check tab-icon"></i>
                    @elseif($isGold)
                        <i class="ph-bold ph-crown tab-icon"></i>
                    @elseif($isPlatinum)
                        <i class="ph-bold ph-diamond tab-icon"></i>
                    @else
                        <i class="ph-bold ph-star tab-icon"></i>
                    @endif
                    <span>{{ strtoupper($plan->name) }}</span>
                    @if($tabBadge)
                        <span class="tab-badge {{ $tabBadgeClass }}">{{ $tabBadge }}</span>
                    @endif
                </button>
            @endforeach
            @unless($hasEnterprise)
                <button type="button" class="plan-tab" data-plan-tab="buy_enterprise" data-category="buyer">
                    <i class="ph-bold ph-buildings tab-icon"></i>
                    <span>INSTITUTIONAL BUYER</span>
                </button>
            @endunless

            {{-- Buyer Pass badge on right edge --}}
            <div style="margin-left: auto; padding: 0 16px;">
                <span class="tab-badge buyer" style="font-size: 10px; padding: 4px 10px;">HOME BUYERS</span>
            </div>
        </div>
    </nav>

    {{-- ============================================
         MAIN CONTENT AREA
    ============================================ --}}
    <div class="plan-detail-section">

        {{-- Category Toggle --}}
        <div class="category-toggle" id="category-toggle" role="group" aria-label="Plan category selector">
            <button type="button" class="category-toggle-btn {{ $isYearly ? '' : 'active' }}" data-billing-choice="monthly">
                <i class="ph-bold ph-house-line"></i>
                <span>Rental Pass</span>
            </button>
            <button type="button" class="category-toggle-btn {{ $isYearly ? 'active' : '' }}" data-billing-choice="yearly">
                <i class="ph-bold ph-buildings"></i>
                <span>Buyer Pass</span>
                <span class="save-tag">SAVE 20%</span>
            </button>
        </div>

        {{-- Flash Alerts --}}
        @if(session('success'))
            <div class="plans-flash-alert success">{{ session('success') }}</div>
        @endif
        @if(session('error') || $errors->has('payment_reference'))
            <div class="plans-flash-alert error">{{ session('error') ?? $errors->first('payment_reference') }}</div>
        @endif

        {{-- Active/Pending Subscriptions banner --}}
        @auth
            @php
                $userActivePlans = collect([$activeRentPlan ?? null, $activeBuyPlan ?? null])->filter()->unique('id');
            @endphp
            @if($userActivePlans->isNotEmpty())
                @foreach($userActivePlans as $curPlan)
                    <div class="active-plan-banner">
                        <div class="banner-icon">
                            <i class="ph-bold ph-check-circle"></i>
                        </div>
                        <div class="banner-text">
                            <h3>Active Plan: {{ $curPlan->plan->name ?? 'Premium' }} ({{ $curPlan->plan && $curPlan->plan->isBuyPlan() ? 'Buyer Pass' : 'Rental Plan' }})</h3>
                            <p>{{ $curPlan->remaining_contacts }} contact unlocks remaining · Valid until {{ $curPlan->expires_at->format('M d, Y') }}</p>
                        </div>
                        <span class="banner-badge">Active</span>
                    </div>
                @endforeach
            @elseif($pendingPlan)
                <div class="active-plan-banner pending">
                    <div class="banner-icon">
                        <i class="ph-bold ph-clock"></i>
                    </div>
                    <div class="banner-text">
                        <h3>Payment Review Pending: {{ $pendingPlan->plan->name ?? 'Plan' }}</h3>
                        <p>Admin verification is in progress. Your plan will activate automatically upon confirmation.</p>
                    </div>
                </div>
            @endif
        @endauth


        {{-- ========== RENTAL PLAN PANELS ========== --}}
        @foreach($rentPlans as $index => $plan)
            @php
                $nameLower = strtolower($plan->name);
                $isGold = str_contains($nameLower, 'gold') || str_contains($nameLower, 'pro') || str_contains($nameLower, 'popular');
                $isPlatinum = str_contains($nameLower, 'plat') || str_contains($nameLower, 'diamond') || str_contains($nameLower, 'vip');
                $monthlyOffer = isset($userOffers) ? $userOffers->where('plan_id', $plan->id)->where('billing_period', 'monthly')->first() : null;
                $price = ($monthlyOffer && $monthlyOffer->discounted_price !== null) ? (float) $monthlyOffer->discounted_price : (float) $plan->price;
                $originalPrice = ($monthlyOffer && $monthlyOffer->discounted_price !== null) ? (float) $plan->price : null;
                $savePercent = $originalPrice ? round((($originalPrice - $price) / $originalPrice) * 100) : null;
                $perDay = $plan->duration_days > 0 ? round($price / $plan->duration_days, 1) : 0;
            @endphp
            <div class="plan-content-panel rental-panel {{ $index === 0 && !$isYearly ? 'active' : '' }}"
                 id="panel-rent_{{ $plan->id }}" data-category="rental">
                <div class="plan-split-layout">
                    {{-- LEFT SIDE --}}
                    <div class="plan-info-left">
                        <div class="plan-badge-row">
                            <span class="plan-type-badge">
                                <i class="ph-bold ph-shield-check"></i>
                                ZERO BROKERAGE RENTAL PASS · DIRECT OWNER DEALS
                            </span>
                        </div>

                        <h1>
                            Find Your Dream Rental With
                            <span class="plan-name-highlight">{{ $plan->name }} Pass</span>
                        </h1>
                        <p class="plan-subtitle">
                            {{ $plan->description ?? 'Looking for a flat, PG, or room? Skip the 1-month broker commission and negotiate directly with verified landlords.' }}
                        </p>

                        {{-- Feature Grid --}}
                        <div class="feature-grid">
                            <div class="feature-box">
                                <div class="feat-icon">
                                    <i class="ph-bold ph-user-circle-check"></i>
                                </div>
                                <div class="feat-text">
                                    <h4>{{ $plan->contact_limit }} Verified Direct Owners</h4>
                                    <p>Direct owners & builder representatives</p>
                                </div>
                            </div>
                            <div class="feature-box">
                                <div class="feat-icon green">
                                    <i class="ph-bold ph-currency-inr"></i>
                                </div>
                                <div class="feat-text">
                                    <h4>Save ₹1 Lakh to ₹5 Lakhs</h4>
                                    <p>Zero broker commission on rent transactions</p>
                                </div>
                            </div>
                            <div class="feature-box">
                                <div class="feat-icon">
                                    <i class="ph-bold ph-calendar-check"></i>
                                </div>
                                <div class="feat-text">
                                    <h4>{{ $plan->duration_days }} Days Validity</h4>
                                    <p>Extended window to evaluate deals</p>
                                </div>
                            </div>
                            <div class="feature-box">
                                <div class="feat-icon green">
                                    <i class="ph-bold ph-handshake"></i>
                                </div>
                                <div class="feat-text">
                                    <h4>Direct Negotiation</h4>
                                    <p>Direct owner negotiation access</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT SIDE - Pricing Card --}}
                    <div>
                        <div class="pricing-card {{ $isGold ? 'popular-card' : '' }}">
                            <div class="pricing-card-header">
                                <span class="card-tab active">RENTAL PASS</span>
                                <span class="card-tab" style="cursor: default; opacity: 0.4;">DIRECT PASS</span>
                                <div class="card-icon">
                                    <i class="ph-bold ph-house-line"></i>
                                </div>
                            </div>

                            <div class="pricing-card-body">
                                {{-- Price Row --}}
                                @if($originalPrice)
                                    <div class="price-row">
                                        <span class="price-old">₹{{ number_format($originalPrice, 0) }}</span>
                                        <span class="price-save">SAVE {{ $savePercent }}%</span>
                                    </div>
                                @endif
                                <div class="price-main">
                                    <span class="currency">₹</span>
                                    <span class="amount">{{ number_format($price, 0) }}</span>
                                    <span class="period">/ rental pass</span>
                                </div>
                                <div class="price-subtitle">
                                    <i class="ph-bold ph-seal-check"></i>
                                    Only ₹{{ number_format($perDay, 1) }}/day · {{ $plan->duration_days }} Full Days Validity
                                </div>

                                {{-- Pricing Card Trust Area (Under Price) --}}
                                <div class="plan-trust-features-list">
                                    <div class="pt-feat-item"><i class="ph-bold ph-check-circle"></i> No hidden charges</div>
                                    <div class="pt-feat-item"><i class="ph-bold ph-check-circle"></i> {{ $plan->duration_days }} Days Validity</div>
                                    <div class="pt-feat-item"><i class="ph-bold ph-check-circle"></i> Secure payment</div>
                                    <div class="pt-feat-item"><i class="ph-bold ph-check-circle"></i> Instant activation</div>
                                </div>

                                {{-- Stats --}}
                                <div class="plan-stats">
                                    <div class="plan-stat-row">
                                        <span class="stat-label"><i class="ph-bold ph-phone"></i> Direct Owner Contacts</span>
                                        <span class="stat-value highlight">{{ $plan->contact_limit }} Direct Unlocks</span>
                                    </div>
                                    <div class="plan-stat-row">
                                        <span class="stat-label"><i class="ph-bold ph-calendar"></i> Access Duration</span>
                                        <span class="stat-value">{{ $plan->duration_days }} Full Days</span>
                                    </div>
                                    <div class="plan-stat-row">
                                        <span class="stat-label"><i class="ph-bold ph-hand-coins"></i> Brokerage Fee</span>
                                        <span class="stat-value green">₹0 (Zero Commission)</span>
                                    </div>
                                </div>

                                {{-- Trust Strip Directly Above Pricing Buttons --}}
                                <div class="ur-pricing-trust-strip">
                                    <span class="ur-strip-item" title="Encrypted Payment"><i class="ph-bold ph-lock-key text-blue-600"></i> Secure Checkout</span>
                                    <span class="ur-strip-item" title="Transparent Pricing"><i class="ph-bold ph-check text-emerald-600"></i> Transparent Pricing</span>
                                    <a href="{{ route('privacy') }}" class="ur-strip-item ur-strip-link" title="Privacy Protected"><i class="ph-bold ph-shield-check text-indigo-600"></i> Privacy Protected</a>
                                    <a href="tel:{{ $cleanTel }}" class="ur-strip-item ur-strip-link" title="Customer Support"><i class="ph-bold ph-phone text-blue-600"></i> Support</a>
                                    <a href="#refund-policy-summary" onclick="if(window.urScrollTo){ window.urScrollTo('refund-policy-summary'); } else { document.getElementById('refund-policy-summary')?.scrollIntoView({behavior:'smooth'}); } return false;" class="ur-strip-item ur-strip-link" title="Clear Cancellation/Refund"><i class="ph-bold ph-arrow-counter-clockwise text-amber-600"></i> Refund Policy</a>
                                </div>

                                {{-- CTA --}}
                                @if(auth()->check() && $activeRentPlan && $activeRentPlan->remaining_contacts > 0 && $activeRentPlan->plan_id === $plan->id)
                                    <button class="plan-cta-btn active-plan" disabled>
                                        <i class="ph-bold ph-check-circle"></i>
                                        Current Active Plan
                                    </button>
                                @elseif(auth()->check() && $activeRentPlan && $activeRentPlan->remaining_contacts > 0 && $activeRentPlan->plan && (float) $plan->price > (float) $activeRentPlan->plan->price)
                                    <a href="{{ route('plans.checkout', ['plan' => $plan, 'billing' => 'monthly', 'direct' => 1]) }}" class="plan-cta-btn blue">
                                        <i class="ph-bold ph-lightning"></i>
                                        Upgrade Plan · ₹{{ number_format($price, 0) }}
                                    </a>
                                @elseif(auth()->check() && $activeRentPlan && $activeRentPlan->remaining_contacts > 0)
                                    <button class="plan-cta-btn disabled" disabled>
                                        Already Subscribed
                                    </button>
                                @elseif(auth()->check() && $pendingPlan && $pendingPlan->plan && $pendingPlan->plan->isRentPlan())
                                    <button class="plan-cta-btn pending-plan" disabled>
                                        <i class="ph-bold ph-clock"></i>
                                        Verification Pending
                                    </button>
                                @else
                                    @guest
                                        <a href="{{ route('login', ['redirect' => route('plans.checkout', ['plan' => $plan, 'billing' => 'monthly', 'direct' => 1])]) }}"
                                           onclick="event.preventDefault(); event.stopPropagation(); window.openAuthModal('login', '{{ route('plans.checkout', ['plan' => $plan, 'billing' => 'monthly', 'direct' => 1]) }}');"
                                           class="plan-cta-btn primary">
                                            <i class="ph-bold ph-lock-key-open"></i>
                                            Buy Plan · ₹{{ number_format($price, 0) }}
                                        </a>
                                    @else
                                        <a href="{{ route('plans.checkout', ['plan' => $plan, 'billing' => 'monthly', 'direct' => 1]) }}"
                                           class="plan-cta-btn primary">
                                            <i class="ph-bold ph-lock-key-open"></i>
                                            Buy Plan · ₹{{ number_format($price, 0) }}
                                        </a>
                                    @endguest
                                @endif

                                <a href="#comparison-section" class="plan-compare-link">
                                    View Full Plan Details &amp; Compare <i class="ph-bold ph-caret-down"></i>
                                </a>
                            </div>
                        </div>

                        {{-- Info Card --}}
                        <div class="plan-info-card">
                            <div class="plan-info-card-body">
                                <div class="card-badges">
                                    <span class="badge-green">DIRECT OWNER DEALS</span>
                                    <span class="badge-blue">100% Genuine</span>
                                </div>
                                <h3>Direct Property Rental</h3>
                                <p class="card-desc">Connect directly with genuine property owners and save lakhs in brokerage fees.</p>
                                <ul class="card-checks">
                                    <li><i class="ph-bold ph-check-circle"></i> Verified Direct Owner Contacts</li>
                                    <li><i class="ph-bold ph-check-circle"></i> Save 1 Month Brokerage Fee</li>
                                    <li><i class="ph-bold ph-check-circle"></i> Rental Agreement Checklist</li>
                                </ul>
                            </div>
                            <div class="plan-info-card-footer">
                                <i class="ph-bold ph-sparkle"></i>
                                Direct Owner Negotiation · ₹0 Middleman
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        {{-- Enterprise Panel (Rental) --}}
        @unless($hasEnterprise)
            <div class="plan-content-panel rental-panel" id="panel-rent_enterprise" data-category="rental">
                <div class="plan-split-layout">
                    <div class="plan-info-left">
                        <div class="plan-badge-row">
                            <span class="plan-type-badge" style="background: #ccfbf1; color: #134e4a; border-color: #99f6e4;">
                                <i class="ph-bold ph-buildings"></i>
                                ENTERPRISE · CORPORATE SOLUTIONS
                            </span>
                        </div>
                        <h1>
                            Scale Your Operations With
                            <span class="plan-name-highlight">Enterprise Plan</span>
                        </h1>
                        <p class="plan-subtitle">For agencies, relocation teams, and portfolio operations. Get unlimited access, dedicated account management, and seamless API integration.</p>

                        <div class="feature-grid">
                            <div class="feature-box">
                                <div class="feat-icon" style="background: #ccfbf1; color: #0d9488; border-color: #99f6e4;">
                                    <i class="ph-bold ph-infinity"></i>
                                </div>
                                <div class="feat-text">
                                    <h4>Unlimited Owner Unlocks</h4>
                                    <p>No cap on contact discoveries</p>
                                </div>
                            </div>
                            <div class="feature-box">
                                <div class="feat-icon" style="background: #ccfbf1; color: #0d9488; border-color: #99f6e4;">
                                    <i class="ph-bold ph-user-circle-gear"></i>
                                </div>
                                <div class="feat-text">
                                    <h4>Dedicated Account Manager</h4>
                                    <p>Personal support for your team</p>
                                </div>
                            </div>
                            <div class="feature-box">
                                <div class="feat-icon" style="background: #ccfbf1; color: #0d9488; border-color: #99f6e4;">
                                    <i class="ph-bold ph-receipt"></i>
                                </div>
                                <div class="feat-text">
                                    <h4>GST Invoicing & API</h4>
                                    <p>Seamless financial integration</p>
                                </div>
                            </div>
                            <div class="feature-box">
                                <div class="feat-icon" style="background: #ccfbf1; color: #0d9488; border-color: #99f6e4;">
                                    <i class="ph-bold ph-users-three"></i>
                                </div>
                                <div class="feat-text">
                                    <h4>Multi-User License</h4>
                                    <p>Team-wide access and controls</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="pricing-card">
                            <div class="pricing-card-header">
                                <span class="card-tab active" style="background: #0d9488; color: #fff; box-shadow: 0 2px 8px rgba(13,148,136,0.25);">ENTERPRISE</span>
                                <div class="card-icon" style="background: #ccfbf1; color: #0d9488;">
                                    <i class="ph-bold ph-buildings"></i>
                                </div>
                            </div>
                            <div class="pricing-card-body">
                                <div class="price-main">
                                    <span class="amount" style="font-size: 36px;">Custom</span>
                                </div>
                                <div class="price-subtitle" style="color: #0d9488;">
                                    <i class="ph-bold ph-users-three"></i>
                                    Multi-User License · Tailored Pricing
                                </div>

                                <div class="plan-stats">
                                    <div class="plan-stat-row">
                                        <span class="stat-label"><i class="ph-bold ph-phone"></i> Owner Contacts</span>
                                        <span class="stat-value highlight">Unlimited</span>
                                    </div>
                                    <div class="plan-stat-row">
                                        <span class="stat-label"><i class="ph-bold ph-calendar"></i> Access Duration</span>
                                        <span class="stat-value">Custom</span>
                                    </div>
                                    <div class="plan-stat-row">
                                        <span class="stat-label"><i class="ph-bold ph-headset"></i> Support Level</span>
                                        <span class="stat-value green">24/7 SLA Manager</span>
                                    </div>
                                </div>

                                <a href="mailto:support@unlockrentals.com?subject=Enterprise%20Plan%20Inquiry"
                                   class="plan-cta-btn" style="background: linear-gradient(135deg, #0d9488 0%, #14b8a6 100%); color: #fff; box-shadow: 0 4px 16px rgba(13,148,136,0.3);">
                                    <i class="ph-bold ph-envelope"></i>
                                    Contact Sales
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endunless


        {{-- ========== BUYER PLAN PANELS ========== --}}
        @foreach($buyPlans as $index => $plan)
            @php
                $nameLower = strtolower($plan->name);
                $isGold = str_contains($nameLower, 'gold') || str_contains($nameLower, 'pro') || str_contains($nameLower, 'popular');
                $isPlatinum = str_contains($nameLower, 'plat') || str_contains($nameLower, 'diamond') || str_contains($nameLower, 'vip');
                $price = (float) $plan->price;
                $perDay = $plan->duration_days > 0 ? round($price / $plan->duration_days, 1) : 0;

                $buyerDisplayName = $plan->name;
                $buyerDisplayDesc = $plan->description ?: 'Direct seller contact access for verified property purchase.';
            @endphp
            <div class="plan-content-panel buyer-panel {{ $index === 0 && $isYearly ? 'active' : '' }}"
                 id="panel-buy_{{ $plan->id }}" data-category="buyer">
                <div class="plan-split-layout">
                    {{-- LEFT SIDE --}}
                    <div class="plan-info-left">
                        <div class="plan-badge-row">
                            <span class="plan-type-badge buyer">
                                <i class="ph-bold ph-key"></i>
                                ZERO BROKERAGE BUYER PASS · DIRECT SELLER DEALS
                            </span>
                        </div>

                        <h1>
                            Purchase Your Dream Property With
                            <span class="plan-name-highlight">{{ $buyerDisplayName }}</span>
                        </h1>
                        <p class="plan-subtitle">{{ $buyerDisplayDesc }}</p>

                        <div class="feature-grid">
                            <div class="feature-box">
                                <div class="feat-icon">
                                    <i class="ph-bold ph-user-circle-check"></i>
                                </div>
                                <div class="feat-text">
                                    <h4>{{ $plan->contact_limit }} Verified Direct Sellers</h4>
                                    <p>Direct owners & builder representatives</p>
                                </div>
                            </div>
                            <div class="feature-box">
                                <div class="feat-icon green">
                                    <i class="ph-bold ph-currency-inr"></i>
                                </div>
                                <div class="feat-text">
                                    <h4>Save ₹1 Lakh to ₹5 Lakhs</h4>
                                    <p>Zero broker commission on buy transactions</p>
                                </div>
                            </div>
                            <div class="feature-box">
                                <div class="feat-icon">
                                    <i class="ph-bold ph-calendar-check"></i>
                                </div>
                                <div class="feat-text">
                                    <h4>{{ $plan->duration_days }} Days Buyer Validity</h4>
                                    <p>Extended window to evaluate deals</p>
                                </div>
                            </div>
                            <div class="feature-box">
                                <div class="feat-icon green">
                                    <i class="ph-bold ph-file-text"></i>
                                </div>
                                <div class="feat-text">
                                    <h4>Title & Visit Assistance</h4>
                                    <p>Direct owner negotiation pass</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT SIDE - Pricing Card --}}
                    <div>
                        <div class="pricing-card {{ $isGold ? 'popular-card' : '' }}">
                            <div class="pricing-card-header">
                                <span class="card-tab active" style="background: #d97706; color: #fff; box-shadow: 0 2px 8px rgba(217,119,6,0.25);">BUYER PASS</span>
                                <span class="card-tab" style="cursor: default; opacity: 0.4;">DIRECT PASS</span>
                                <div class="card-icon" style="background: #fef3c7; color: #d97706;">
                                    <i class="ph-bold ph-buildings"></i>
                                </div>
                            </div>

                            <div class="pricing-card-body">
                                <div class="price-main">
                                    <span class="currency">₹</span>
                                    <span class="amount">{{ number_format($price, 0) }}</span>
                                    <span class="period">/ annual pass</span>
                                </div>
                                <div class="price-subtitle">
                                    <i class="ph-bold ph-seal-check"></i>
                                    Only ₹{{ number_format($perDay, 1) }}/day · {{ $plan->duration_days }} Full Days Validity
                                </div>

                                {{-- Pricing Card Trust Area (Under Price) --}}
                                <div class="plan-trust-features-list">
                                    <div class="pt-feat-item"><i class="ph-bold ph-check-circle"></i> No hidden charges</div>
                                    <div class="pt-feat-item"><i class="ph-bold ph-check-circle"></i> {{ $plan->duration_days }} Days Priority Access</div>
                                    <div class="pt-feat-item"><i class="ph-bold ph-check-circle"></i> Secure payment</div>
                                    <div class="pt-feat-item"><i class="ph-bold ph-check-circle"></i> Instant activation</div>
                                </div>

                                <div class="plan-stats">
                                    <div class="plan-stat-row">
                                        <span class="stat-label"><i class="ph-bold ph-phone"></i> Direct Seller Contacts</span>
                                        <span class="stat-value highlight">{{ $plan->contact_limit }} Direct Unlocks</span>
                                    </div>
                                    <div class="plan-stat-row">
                                        <span class="stat-label"><i class="ph-bold ph-calendar"></i> Access Duration</span>
                                        <span class="stat-value">{{ $plan->duration_days }} Full Days</span>
                                    </div>
                                    <div class="plan-stat-row">
                                        <span class="stat-label"><i class="ph-bold ph-hand-coins"></i> Brokerage Fee</span>
                                        <span class="stat-value green">₹0 (Zero Commission)</span>
                                    </div>
                                </div>

                                {{-- Trust Strip Directly Above Pricing Buttons --}}
                                <div class="ur-pricing-trust-strip">
                                    <span class="ur-strip-item" title="Encrypted Payment"><i class="ph-bold ph-lock-key text-blue-600"></i> Secure Checkout</span>
                                    <span class="ur-strip-item" title="Transparent Pricing"><i class="ph-bold ph-check text-emerald-600"></i> Transparent Pricing</span>
                                    <a href="{{ route('privacy') }}" class="ur-strip-item ur-strip-link" title="Privacy Protected"><i class="ph-bold ph-shield-check text-indigo-600"></i> Privacy Protected</a>
                                    <a href="tel:{{ $cleanTel }}" class="ur-strip-item ur-strip-link" title="Customer Support"><i class="ph-bold ph-phone text-blue-600"></i> Support</a>
                                    <a href="#refund-policy-summary" onclick="if(window.urScrollTo){ window.urScrollTo('refund-policy-summary'); } else { document.getElementById('refund-policy-summary')?.scrollIntoView({behavior:'smooth'}); } return false;" class="ur-strip-item ur-strip-link" title="Clear Cancellation/Refund"><i class="ph-bold ph-arrow-counter-clockwise text-amber-600"></i> Refund Policy</a>
                                </div>

                                {{-- CTA --}}
                                @if(auth()->check() && $activeBuyPlan && $activeBuyPlan->remaining_contacts > 0 && $activeBuyPlan->plan_id === $plan->id)
                                    <button class="plan-cta-btn active-plan" disabled>
                                        <i class="ph-bold ph-check-circle"></i>
                                        Current Active Plan
                                    </button>
                                @elseif(auth()->check() && $activeBuyPlan && $activeBuyPlan->remaining_contacts > 0 && $activeBuyPlan->plan && (float) $plan->price > (float) $activeBuyPlan->plan->price)
                                    <a href="{{ route('plans.checkout', ['plan' => $plan, 'billing' => 'yearly', 'direct' => 1]) }}" class="plan-cta-btn blue">
                                        <i class="ph-bold ph-lightning"></i>
                                        Upgrade Plan · ₹{{ number_format($price, 0) }}
                                    </a>
                                @elseif(auth()->check() && $activeBuyPlan && $activeBuyPlan->remaining_contacts > 0)
                                    <button class="plan-cta-btn disabled" disabled>
                                        Already Subscribed
                                    </button>
                                @elseif(auth()->check() && $pendingPlan && $pendingPlan->plan && $pendingPlan->plan->isBuyPlan())
                                    <button class="plan-cta-btn pending-plan" disabled>
                                        <i class="ph-bold ph-clock"></i>
                                        Verification Pending
                                    </button>
                                @else
                                    @guest
                                        <a href="{{ route('login', ['redirect' => route('plans.checkout', ['plan' => $plan, 'billing' => 'yearly', 'direct' => 1])]) }}"
                                           onclick="event.preventDefault(); event.stopPropagation(); window.openAuthModal('login', '{{ route('plans.checkout', ['plan' => $plan, 'billing' => 'yearly', 'direct' => 1]) }}');"
                                           class="plan-cta-btn primary">
                                            <i class="ph-bold ph-lock-key-open"></i>
                                            Buy Plan · ₹{{ number_format($price, 0) }}
                                        </a>
                                    @else
                                        <a href="{{ route('plans.checkout', ['plan' => $plan, 'billing' => 'yearly', 'direct' => 1]) }}"
                                           class="plan-cta-btn primary">
                                            <i class="ph-bold ph-lock-key-open"></i>
                                            Buy Plan · ₹{{ number_format($price, 0) }}
                                        </a>
                                    @endguest
                                @endif

                                <a href="#comparison-section" class="plan-compare-link">
                                    View Full Plan Details &amp; Compare <i class="ph-bold ph-caret-down"></i>
                                </a>
                            </div>
                        </div>

                        {{-- Info Card --}}
                        <div class="plan-info-card">
                            <div class="plan-info-card-body">
                                <div class="card-badges">
                                    <span class="badge-green">DIRECT SELLER DEALS</span>
                                    <span class="badge-blue">100% Genuine</span>
                                </div>
                                <h3>Direct Property Purchase</h3>
                                <p class="card-desc">Connect directly with genuine property sellers and save lakhs in brokerage fees.</p>
                                <ul class="card-checks">
                                    <li><i class="ph-bold ph-check-circle"></i> Verified Direct Seller Contacts</li>
                                    <li><i class="ph-bold ph-check-circle"></i> Save 1% to 2% Brokerage Fee</li>
                                    <li><i class="ph-bold ph-check-circle"></i> Title Document Checklist</li>
                                </ul>
                            </div>
                            <div class="plan-info-card-footer">
                                <i class="ph-bold ph-sparkle"></i>
                                Direct Seller Negotiation · ₹0 Middleman
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        {{-- Enterprise Panel (Buyer) --}}
        @unless($hasEnterprise)
            <div class="plan-content-panel buyer-panel" id="panel-buy_enterprise" data-category="buyer">
                <div class="plan-split-layout">
                    <div class="plan-info-left">
                        <div class="plan-badge-row">
                            <span class="plan-type-badge" style="background: #ccfbf1; color: #134e4a; border-color: #99f6e4;">
                                <i class="ph-bold ph-buildings"></i>
                                INVESTOR DESK · INSTITUTIONAL SOLUTIONS
                            </span>
                        </div>
                        <h1>
                            Scale Your Investments With
                            <span class="plan-name-highlight">Institutional Buyer</span>
                        </h1>
                        <p class="plan-subtitle">For property funds, builders, and large commercial investors. Get unlimited seller access, dedicated investment concierge, and API integration.</p>

                        <div class="feature-grid">
                            <div class="feature-box">
                                <div class="feat-icon" style="background: #ccfbf1; color: #0d9488; border-color: #99f6e4;">
                                    <i class="ph-bold ph-infinity"></i>
                                </div>
                                <div class="feat-text">
                                    <h4>Unlimited Seller Unlocks</h4>
                                    <p>No cap on contact discoveries</p>
                                </div>
                            </div>
                            <div class="feature-box">
                                <div class="feat-icon" style="background: #ccfbf1; color: #0d9488; border-color: #99f6e4;">
                                    <i class="ph-bold ph-user-circle-gear"></i>
                                </div>
                                <div class="feat-text">
                                    <h4>Dedicated Investment Concierge</h4>
                                    <p>Personal support for your portfolio</p>
                                </div>
                            </div>
                            <div class="feature-box">
                                <div class="feat-icon" style="background: #ccfbf1; color: #0d9488; border-color: #99f6e4;">
                                    <i class="ph-bold ph-code"></i>
                                </div>
                                <div class="feat-text">
                                    <h4>Direct API & Bulk Export</h4>
                                    <p>Programmatic access to data</p>
                                </div>
                            </div>
                            <div class="feature-box">
                                <div class="feat-icon" style="background: #ccfbf1; color: #0d9488; border-color: #99f6e4;">
                                    <i class="ph-bold ph-chart-line-up"></i>
                                </div>
                                <div class="feat-text">
                                    <h4>Portfolio Access</h4>
                                    <p>Multi-property deal pipeline</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="pricing-card">
                            <div class="pricing-card-header">
                                <span class="card-tab active" style="background: #0d9488; color: #fff; box-shadow: 0 2px 8px rgba(13,148,136,0.25);">INSTITUTIONAL</span>
                                <div class="card-icon" style="background: #ccfbf1; color: #0d9488;">
                                    <i class="ph-bold ph-buildings"></i>
                                </div>
                            </div>
                            <div class="pricing-card-body">
                                <div class="price-main">
                                    <span class="amount" style="font-size: 36px;">Custom</span>
                                </div>
                                <div class="price-subtitle" style="color: #0d9488;">
                                    <i class="ph-bold ph-users-three"></i>
                                    Portfolio Access · Tailored Pricing
                                </div>

                                <div class="plan-stats">
                                    <div class="plan-stat-row">
                                        <span class="stat-label"><i class="ph-bold ph-phone"></i> Seller Contacts</span>
                                        <span class="stat-value highlight">Unlimited</span>
                                    </div>
                                    <div class="plan-stat-row">
                                        <span class="stat-label"><i class="ph-bold ph-calendar"></i> Access Duration</span>
                                        <span class="stat-value">Custom</span>
                                    </div>
                                    <div class="plan-stat-row">
                                        <span class="stat-label"><i class="ph-bold ph-headset"></i> Support Level</span>
                                        <span class="stat-value green">24/7 SLA Manager</span>
                                    </div>
                                </div>

                                <a href="mailto:support@unlockrentals.com?subject=Institutional%20Buyer%20Inquiry"
                                   class="plan-cta-btn" style="background: linear-gradient(135deg, #0d9488 0%, #14b8a6 100%); color: #fff; box-shadow: 0 4px 16px rgba(13,148,136,0.3);">
                                    <i class="ph-bold ph-envelope"></i>
                                    Contact Sales
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endunless

    </div>{{-- End plan-detail-section --}}


    {{-- ============================================
         FEATURE COMPARISON TABLE
    ============================================ --}}
    <div class="comparison-section" id="comparison-section">
        <div class="comparison-card">
            <div class="comparison-card-header">
                <div class="comp-title">
                    <span>Detailed Comparison</span>
                    <h2>What's included in every plan</h2>
                </div>
                <div class="comp-secure">
                    <i class="ph-bold ph-lock-key"></i>
                    256-Bit SSL Encrypted · Instant Activation
                </div>
            </div>

            <div style="overflow-x: auto;">
                <table class="comparison-table">
                    <thead>
                        <tr>
                            <th>Features & Benefits</th>
                            <th>Basic</th>
                            <th>Gold (Popular)</th>
                            <th>Platinum</th>
                            <th>Enterprise</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Direct Landlord Contact Unlocks</td>
                            <td>Starter Pack</td>
                            <td class="highlight-cell">Expanded Pack</td>
                            <td>High Volume</td>
                            <td>Unlimited</td>
                        </tr>
                        <tr>
                            <td>Direct WhatsApp & Call Connect</td>
                            <td class="check-cell">✓ Included</td>
                            <td class="check-cell">✓ Included</td>
                            <td class="check-cell">✓ Included</td>
                            <td class="check-cell">✓ Included</td>
                        </tr>
                        <tr>
                            <td>Zero Brokerage Guarantee</td>
                            <td class="check-cell" style="font-weight: 800;">100% Free</td>
                            <td class="check-cell" style="font-weight: 800;">100% Free</td>
                            <td class="check-cell" style="font-weight: 800;">100% Free</td>
                            <td class="check-cell" style="font-weight: 800;">100% Free</td>
                        </tr>
                        <tr>
                            <td>Visit Booking Access</td>
                            <td>Standard</td>
                            <td>Priority</td>
                            <td>Instant Slot Lock</td>
                            <td>Concierge</td>
                        </tr>
                        <tr>
                            <td>Customer Support</td>
                            <td>Email Support</td>
                            <td class="highlight-cell">Priority Chat & Phone</td>
                            <td>Dedicated Support</td>
                            <td>24/7 SLA Manager</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    {{-- ============================================
         HIGH-TRUST PLAN PURCHASE SECTION
         Transparent Pricing · 6 Trust Cards · 4 Steps · Verified Reviews · Refund Policy · 9 FAQs · Help Desk
    ============================================ --}}
    <x-plan-trust-section :feedbacks="$feedbacks ?? null" />

</div>

<x-subscription.payment-failed-modal :payment-failed-reason="$paymentFailedReason" />
@endsection

@push('scripts')
<script>
(() => {
    // ---- Category Toggle (Rental / Buyer) ----
    const categoryBtns = document.querySelectorAll('[data-billing-choice]');
    const rentalTabs = document.getElementById('rental-tabs');
    const buyerTabs = document.getElementById('buyer-tabs');

    function setCategory(period, updateUrl = false) {
        categoryBtns.forEach(btn => {
            btn.classList.toggle('active', btn.dataset.billingChoice === period);
        });

        if (period === 'yearly') {
            rentalTabs.style.display = 'none';
            buyerTabs.style.display = 'flex';
            // Hide all rental panels, show first buyer panel
            document.querySelectorAll('.rental-panel').forEach(p => p.classList.remove('active'));
            document.querySelectorAll('.buyer-panel').forEach(p => p.classList.remove('active'));
            const firstBuyer = document.querySelector('.buyer-panel');
            if (firstBuyer) firstBuyer.classList.add('active');
            // Activate first buyer tab
            document.querySelectorAll('#buyer-tabs .plan-tab').forEach((t, i) => {
                t.classList.toggle('active', i === 0);
            });
        } else {
            rentalTabs.style.display = 'flex';
            buyerTabs.style.display = 'none';
            // Hide all buyer panels, show first rental panel
            document.querySelectorAll('.buyer-panel').forEach(p => p.classList.remove('active'));
            document.querySelectorAll('.rental-panel').forEach(p => p.classList.remove('active'));
            const firstRental = document.querySelector('.rental-panel');
            if (firstRental) firstRental.classList.add('active');
            // Activate first rental tab
            document.querySelectorAll('#rental-tabs .plan-tab').forEach((t, i) => {
                t.classList.toggle('active', i === 0);
            });
        }

        if (updateUrl && window.history.replaceState) {
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('billing', period);
            currentUrl.searchParams.set('purpose', period === 'yearly' ? 'buy' : 'rent');
            window.history.replaceState({}, '', currentUrl.toString());
        }
    }

    categoryBtns.forEach(btn => {
        btn.addEventListener('click', () => setCategory(btn.dataset.billingChoice, true));
    });

    // ---- Plan Tab Switching ----
    document.querySelectorAll('.plan-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            const planId = tab.dataset.planTab;
            const category = tab.dataset.category;
            if (!planId) return;

            // Deactivate sibling tabs
            const parentContainer = tab.closest('#rental-tabs, #buyer-tabs');
            if (parentContainer) {
                parentContainer.querySelectorAll('.plan-tab').forEach(t => t.classList.remove('active'));
            }
            tab.classList.add('active');

            // Show matching panel
            const panels = category === 'rental'
                ? document.querySelectorAll('.rental-panel')
                : document.querySelectorAll('.buyer-panel');
            panels.forEach(p => p.classList.remove('active'));
            const target = document.getElementById('panel-' + planId);
            if (target) target.classList.add('active');
        });
    });

    // ---- Init from URL ----
    const urlParams = new URLSearchParams(window.location.search);
    const billingParam = urlParams.get('billing');
    const purposeParam = urlParams.get('purpose');
    const hash = window.location.hash.toLowerCase();

    let targetBilling = '{{ $initialBilling }}';
    if (billingParam === 'yearly' || billingParam === 'monthly') {
        targetBilling = billingParam;
    } else if (purposeParam === 'buy' || purposeParam === 'sale' || hash.includes('buyer') || hash.includes('yearly')) {
        targetBilling = 'yearly';
    } else if (purposeParam === 'rent' || hash.includes('rental') || hash.includes('monthly')) {
        targetBilling = 'monthly';
    }
    setCategory(targetBilling, false);

    // Smooth scroll if param requested
    if (billingParam || purposeParam || hash.includes('buyer') || hash.includes('rental') || hash.includes('billing')) {
        setTimeout(() => {
            const scrollTarget = document.getElementById('category-toggle') || document.getElementById('plans-tab-bar');
            if (scrollTarget) {
                scrollTarget.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }, 150);
    }
})();

// FAQ Toggle
function toggleFaq(el) {
    const item = el.closest('.faq-item');
    if (item) {
        item.classList.toggle('open');
    }
}
</script>
@endpush
