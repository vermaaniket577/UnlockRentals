{{-- ============================================================
     UNLOCK RENTALS — HERO PLANS SLIDER (COMPACT LIGHT LUXURY EDITION)
     Redesigned & optimized: compact height (35-45% reduction),
     balanced 3-part horizontal layout (Value -> Pricing -> Visual),
     compact 2x2 benefits, prominent ₹999 price & orange CTA,
     single horizontal trust row, and responsive mobile stack.
     ============================================================ --}}

@php
    try {
        \App\Models\Plan::ensureBuyerPlansExist();

        $allRentPlans = \Illuminate\Support\Facades\Cache::remember('active_rent_plans_v2', 3600, function () {
            return \App\Models\Plan::active()
                ->where('is_private', false)
                ->whereIn('purpose', ['rent', 'both', null])
                ->orderBy('sort_order')
                ->get();
        });

        $allBuyPlans = \Illuminate\Support\Facades\Cache::remember('active_buy_plans_v2', 3600, function () {
            return \App\Models\Plan::active()
                ->where('is_private', false)
                ->whereIn('purpose', ['buy', 'sale'])
                ->orderBy('sort_order')
                ->get();
        });
    } catch (\Throwable $e) {
        $allRentPlans = collect();
        $allBuyPlans = collect();
    }

    // High quality fallback plans matching prompt requirements
    if ($allRentPlans->isEmpty()) {
        $allRentPlans = collect([
            (object)[
                'id' => 2,
                'name' => 'Gold Direct Pass',
                'description' => 'Best value for 1BHK, 2BHK & 3BHK home hunters. Directly call & WhatsApp owners with 0% middleman brokerage.',
                'price' => 999.00,
                'duration_days' => 150,
                'contact_limit' => 75,
                'features' => [
                    '75 Verified Owner Contacts',
                    'Zero Brokerage Guaranteed',
                    '150 Days Active Validity',
                    'Instant WhatsApp Connect'
                ],
                'image_path' => null,
            ],
            (object)[
                'id' => 3,
                'name' => 'Platinum VIP Pass',
                'description' => 'Ultimate VIP pass with maximum direct owner unlocks, concierge support, and lease assistance.',
                'price' => 1999.00,
                'duration_days' => 180,
                'contact_limit' => 150,
                'features' => [
                    '150 Verified Owner Direct Contacts',
                    'Instant WhatsApp & Phone Call Unlock',
                    'Zero Brokerage Guaranteed (Save ₹50,000+)',
                    '180 Days VIP Access (6 Months)',
                    'Dedicated Relationship Manager',
                    'Free Rental Agreement Draft Template'
                ],
                'image_path' => null,
            ],
            (object)[
                'id' => 1,
                'name' => 'Silver Starter Pass',
                'description' => 'Essential direct owner contact access for verified rental listings in top localities.',
                'price' => 399.00,
                'duration_days' => 45,
                'contact_limit' => 30,
                'features' => [
                    '30 Verified Owner Direct Contacts',
                    'Instant WhatsApp & Phone Call Unlock',
                    'Zero Brokerage Guaranteed',
                    '45 Days Validity',
                    'Standard Online Support'
                ],
                'image_path' => null,
            ],
        ]);
    }

    if ($allBuyPlans->isEmpty()) {
        $allBuyPlans = collect([
            (object)[
                'id' => 4,
                'name' => 'Direct Buyer Pass',
                'description' => 'Buying a flat, villa, or commercial space? Skip the 1% to 2% property broker commission and negotiate directly with verified sellers.',
                'price' => 50000.00,
                'duration_days' => 60,
                'contact_limit' => 30,
                'features' => [
                    '30 Verified Direct Sellers',
                    'Save ₹1 Lakh to ₹5 Lakhs Brokerage',
                    '60 Days Buyer Validity',
                    'Title & Visit Assistance'
                ],
                'image_path' => null,
            ],
        ]);
    }

    // Build curated slides list with Gold as premier flagship first
    $goldRent = $allRentPlans->first(fn($p) => str_contains(strtolower($p->name), 'gold') || str_contains(strtolower($p->name), 'pro'));
    $platRent = $allRentPlans->first(fn($p) => str_contains(strtolower($p->name), 'plat') || str_contains(strtolower($p->name), 'diamond'));
    $silverRent = $allRentPlans->first(fn($p) => str_contains(strtolower($p->name), 'silver') || str_contains(strtolower($p->name), 'basic') || str_contains(strtolower($p->name), 'starter'));
    $buyerPlan = $allBuyPlans->first();

    // Fallbacks if named plans aren't matched exactly
    $goldRent = $goldRent ?? $allRentPlans->skip(1)->first() ?? $allRentPlans->first();
    $platRent = $platRent ?? $allRentPlans->skip(2)->first() ?? $allRentPlans->first();
    $silverRent = $silverRent ?? $allRentPlans->first();

    $heroSlides = collect([
        [
            'plan' => $goldRent,
            'type' => 'rent',
            'theme' => 'gold',
            'tab_label' => 'Gold Pass',
            'tab_icon' => 'ph-crown',
            'tab_badge' => 'Most Popular',
            'badge' => '👑 MOST POPULAR · 84% OF TENANTS CHOOSE THIS',
            'title_prefix' => 'Unlock Verified Owners With',
            'title_highlight' => 'Gold Direct Pass',
            'title_suffix' => '',
            'tagline' => 'Best value for 1BHK, 2BHK & 3BHK home hunters. Directly call & WhatsApp owners with 0% middleman brokerage.',
            'original_price' => 2797,
            'price' => (float) ($goldRent && $goldRent->price > 499 ? $goldRent->price : 999),
            'savings_pct' => 65,
            'per_day' => 6.7,
            'highlights' => [
                ['icon' => 'ph-phone-call', 'title' => '75 Verified Owner Contacts', 'desc' => 'Direct phone & WhatsApp numbers'],
                ['icon' => 'ph-shield-check', 'title' => 'Zero Brokerage Guaranteed', 'desc' => 'Save ₹15,000 to ₹35,000 in fees'],
                ['icon' => 'ph-calendar-check', 'title' => '150 Days Active Validity', 'desc' => 'Full 5 months of unlimited unlocks'],
                ['icon' => 'ph-chat-circle-dots', 'title' => 'Instant WhatsApp Connect', 'desc' => 'Direct landlord chat & visit pass'],
            ],
            'specs' => [
                ['label' => 'Direct Owner Contacts', 'value' => '75 Direct Unlocks'],
                ['label' => 'Access Duration', 'value' => '150 Full Days'],
                ['label' => 'Brokerage Fee', 'value' => '₹0 (Zero Commission)', 'accent' => true],
            ],
            'visual' => [
                'tag' => '100% OWNER DIRECT',
                'title' => 'Direct Landlords Only',
                'sub' => 'Connect directly with genuine property owners with 0% middleman commission.',
                'perks' => [
                    'Verified Landlord Phone & WA',
                    'Direct Landlord Visit Scheduling',
                    'Save ₹15,000 to ₹35,000 Fees',
                ],
            ],
        ],
        [
            'plan' => $platRent,
            'type' => 'rent',
            'theme' => 'platinum',
            'tab_label' => 'Platinum VIP',
            'tab_icon' => 'ph-sparkle',
            'tab_badge' => 'VIP Choice',
            'badge' => '💎 VIP PRIVILEGE · MAXIMUM OWNER UNLOCKS',
            'title_prefix' => 'Experience Premium Access With',
            'title_highlight' => 'Platinum VIP Pass',
            'title_suffix' => '',
            'tagline' => 'Ultimate unrestricted pass for families, executives, and luxury home seekers. Maximum direct contacts + concierge.',
            'original_price' => round((float) ($platRent->price ?? 1999) * 2.8),
            'price' => (float) ($platRent->price ?? 1999),
            'savings_pct' => 65,
            'per_day' => round(((float) ($platRent->price ?? 1999)) / max(1, (int) ($platRent->duration_days ?? 180)), 1),
            'highlights' => [
                ['icon' => 'ph-lightning', 'title' => ($platRent->contact_limit ?? 150) . ' Verified Owner Contacts', 'desc' => 'Maximum direct contact credits'],
                ['icon' => 'ph-shield-check', 'title' => 'Zero Brokerage Guaranteed', 'desc' => 'Save ₹30,000 to ₹60,000 in fees'],
                ['icon' => 'ph-calendar-check', 'title' => ($platRent->duration_days ?? 180) . ' Days Active Validity', 'desc' => 'Full 6 months priority access'],
                ['icon' => 'ph-user-check', 'title' => 'Dedicated Concierge', 'desc' => 'Personal relationship support on WhatsApp'],
            ],
            'specs' => [
                ['label' => 'Direct Owner Contacts', 'value' => ($platRent->contact_limit ?? 150) . ' Direct Unlocks'],
                ['label' => 'Access Duration', 'value' => ($platRent->duration_days ?? 180) . ' Full Days'],
                ['label' => 'Brokerage Fee', 'value' => '₹0 (Zero Commission)', 'accent' => true],
            ],
            'visual' => [
                'tag' => 'VIP CONCIERGE ACCESS',
                'title' => 'Exclusive Luxury Rentals',
                'sub' => 'Direct access to high-end gated societies, penthouses, and luxury villas.',
                'perks' => [
                    'Priority Concierge Support',
                    'Unlimited Direct Landlord Calls',
                    'Free Rental Agreement Draft',
                ],
            ],
        ],
        [
            'plan' => $silverRent,
            'type' => 'rent',
            'theme' => 'silver',
            'tab_label' => 'Silver Starter',
            'tab_icon' => 'ph-shield',
            'tab_badge' => 'Budget',
            'badge' => '🛡️ ESSENTIAL STARTER · QUICK HOUSE HUNT',
            'title_prefix' => 'Find Your First Home With',
            'title_highlight' => 'Silver Starter Pass',
            'title_suffix' => '',
            'tagline' => 'Pocket-friendly direct owner access for individual rooms, 1RK, PG stays, and quick single-neighborhood rentals.',
            'original_price' => round((float) ($silverRent->price ?? 399) * 2.5),
            'price' => (float) ($silverRent->price ?? 399),
            'savings_pct' => 60,
            'per_day' => round(((float) ($silverRent->price ?? 399)) / max(1, (int) ($silverRent->duration_days ?? 45)), 1),
            'highlights' => [
                ['icon' => 'ph-phone-call', 'title' => ($silverRent->contact_limit ?? 30) . ' Verified Owner Contacts', 'desc' => 'Direct phone numbers & WhatsApp'],
                ['icon' => 'ph-shield-check', 'title' => 'Zero Brokerage Guaranteed', 'desc' => 'No commission on lease closing'],
                ['icon' => 'ph-calendar-check', 'title' => ($silverRent->duration_days ?? 45) . ' Days Active Validity', 'desc' => 'Standard monthly house hunt'],
                ['icon' => 'ph-headset', 'title' => 'Standard Support', 'desc' => 'WhatsApp & email query assistance'],
            ],
            'specs' => [
                ['label' => 'Direct Owner Contacts', 'value' => ($silverRent->contact_limit ?? 30) . ' Direct Unlocks'],
                ['label' => 'Access Duration', 'value' => ($silverRent->duration_days ?? 45) . ' Full Days'],
                ['label' => 'Brokerage Fee', 'value' => '₹0 (Zero Commission)', 'accent' => true],
            ],
            'visual' => [
                'tag' => 'QUICK MOVE-IN',
                'title' => 'Verified Starter Listings',
                'sub' => 'Best for singles, students, and professionals searching for budget 1RK & PGs.',
                'perks' => [
                    'Direct Phone & WhatsApp Unlock',
                    'Zero Broker Commission',
                    'Standard Online Support',
                ],
            ],
        ],
    ]);

    if ($buyerPlan) {
        $buyerPrice = (float) (($buyerPlan->price && $buyerPlan->price >= 5000) ? $buyerPlan->price : 50000.00);
        $buyerDays = (int) (($buyerPlan->duration_days && $buyerPlan->duration_days <= 90) ? $buyerPlan->duration_days : 60);
        $buyerContacts = (int) (($buyerPlan->contact_limit && $buyerPlan->contact_limit <= 50) ? $buyerPlan->contact_limit : 30);
        $buyerOriginalPrice = round($buyerPrice * 3);
        $buyerSavingsPct = 67;
        $buyerPerDay = round($buyerPrice / max(1, $buyerDays), 1);

        $heroSlides->push([
            'plan' => $buyerPlan,
            'type' => 'buy',
            'theme' => 'buyer',
            'tab_label' => 'Buyer Pass',
            'tab_icon' => 'ph-buildings',
            'tab_badge' => 'Home Buyers',
            'badge' => '🏡 ZERO BROKERAGE BUYER PASS · DIRECT SELLER DEALS',
            'title_prefix' => 'Purchase Your Dream Property With',
            'title_highlight' => 'Direct Buyer Pass',
            'title_suffix' => '',
            'tagline' => 'Buying a flat, villa, or commercial space? Skip the 1% to 2% property broker commission and negotiate directly with verified sellers.',
            'original_price' => $buyerOriginalPrice,
            'price' => $buyerPrice,
            'savings_pct' => $buyerSavingsPct,
            'per_day' => $buyerPerDay,
            'highlights' => [
                ['icon' => 'ph-house-line', 'title' => $buyerContacts . ' Verified Direct Sellers', 'desc' => 'Direct owners & builder representatives'],
                ['icon' => 'ph-money', 'title' => 'Save ₹1 Lakh to ₹5 Lakhs', 'desc' => 'Zero broker commission on buy transactions'],
                ['icon' => 'ph-calendar-check', 'title' => $buyerDays . ' Days Buyer Validity', 'desc' => 'Extended window to evaluate deals'],
                ['icon' => 'ph-file-text', 'title' => 'Title & Visit Assistance', 'desc' => 'Direct owner negotiation pass'],
            ],
            'specs' => [
                ['label' => 'Direct Seller Contacts', 'value' => $buyerContacts . ' Direct Unlocks'],
                ['label' => 'Access Duration', 'value' => $buyerDays . ' Full Days'],
                ['label' => 'Brokerage Fee', 'value' => '₹0 (Zero Commission)', 'accent' => true],
            ],
            'visual' => [
                'tag' => 'DIRECT SELLER DEALS',
                'title' => 'Direct Property Purchase',
                'sub' => 'Connect directly with genuine property sellers and save lakhs in brokerage fees.',
                'perks' => [
                    'Verified Direct Seller Contacts',
                    'Save 1% to 2% Brokerage Fee',
                    'Title Document Checklist',
                ],
                'image' => asset('images/buyer_pass/buyer_villa_card.webp'),
            ],
        ]);
    }
@endphp

<style>
/* ============================================================
   UNLOCK RENTALS — HERO PLANS SLIDER (COMPACT LIGHT LUXURY)
   Height reduced by ~40%, tight balanced 3-part desktop layout,
   clean 2x2 benefits, prominent ₹999 price, single horizontal
   trust row, and naturally stacked responsive mobile view.
   ============================================================ */

.ur-hero-plans-slider-section {
    position: relative;
    width: 100%;
    background: linear-gradient(180deg, #f8fafc 0%, #ffffff 50%, #f8fafc 100%);
    color: #0f172a;
    padding: 0.95rem 0 0.75rem; /* Ultra-compact height: fits effortlessly in viewport */
    overflow: hidden;
    font-family: 'Outfit', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    border-top: 1px solid #e2e8f0;
    border-bottom: 1px solid #e2e8f0;
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.9), 0 4px 20px rgba(15, 23, 42, 0.02);
}

/* Ambient luminous glow orbs */
.ur-hps-ambient-orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(100px);
    pointer-events: none;
    z-index: 1;
    opacity: 0.65;
    transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
}
.ur-hps-ambient-orb--1 {
    width: 480px;
    height: 480px;
    background: radial-gradient(circle, rgba(245, 158, 11, 0.12) 0%, rgba(251, 191, 36, 0.03) 50%, transparent 80%);
    top: -120px;
    left: -80px;
}
.ur-hps-ambient-orb--2 {
    width: 450px;
    height: 450px;
    background: radial-gradient(circle, rgba(37, 99, 235, 0.09) 0%, rgba(147, 197, 253, 0.02) 50%, transparent 80%);
    bottom: -120px;
    right: -60px;
}

/* Subtle architectural grid pattern */
.ur-hps-grid-pattern {
    position: absolute;
    inset: 0;
    background-image: 
        linear-gradient(rgba(15, 23, 42, 0.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(15, 23, 42, 0.025) 1px, transparent 1px);
    background-size: 44px 44px;
    background-position: center center;
    pointer-events: none;
    z-index: 1;
    mask-image: radial-gradient(ellipse at center, rgba(0, 0, 0, 0.6) 0%, transparent 80%);
    -webkit-mask-image: radial-gradient(ellipse at center, rgba(0, 0, 0, 0.6) 0%, transparent 80%);
}

.ur-hps-container {
    position: relative;
    z-index: 10;
    width: 100%;
    max-width: 1220px; /* Bounded width eliminates large right-hand white void */
    margin: 0 auto;
    padding: 0 1.25rem;
    box-sizing: border-box;
}

/* Compact Section Top Bar: Eyebrow + Plan Switcher Tabs */
.ur-hps-top-nav {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    margin-bottom: 0.65rem;
    flex-wrap: wrap;
}

.ur-hps-top-heading {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    font-size: 0.75rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: #1e40af;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    padding: 0.25rem 0.8rem;
    border-radius: 9999px;
}

.ur-hps-top-heading i {
    color: #d97706;
    font-size: 0.9rem;
}

/* Quick Jump Plan Tabs Nav Bar */
.ur-hps-tabs-bar {
    display: flex;
    align-items: center;
    gap: 0.3rem;
    flex-wrap: wrap;
    padding: 0.2rem;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    border-radius: 9999px;
    box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
}

.ur-hps-tab-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.32rem 0.8rem;
    border-radius: 9999px;
    border: 1px solid transparent;
    background: transparent;
    color: #64748b;
    font-family: inherit;
    font-size: 0.78rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    white-space: nowrap;
}

.ur-hps-tab-btn:hover {
    color: #0f172a;
    background: rgba(255, 255, 255, 0.85);
}

.ur-hps-tab-btn.active.tab--gold {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    border-color: #f59e0b;
    box-shadow: 0 4px 14px rgba(217, 119, 6, 0.28);
    color: #ffffff;
}

.ur-hps-tab-btn.active.tab--platinum {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    border-color: #2563eb;
    box-shadow: 0 4px 14px rgba(37, 99, 235, 0.28);
    color: #ffffff;
}

.ur-hps-tab-btn.active.tab--silver {
    background: linear-gradient(135deg, #475569 0%, #334155 100%);
    border-color: #334155;
    box-shadow: 0 4px 14px rgba(51, 65, 85, 0.25);
    color: #ffffff;
}

.ur-hps-tab-btn.active.tab--buyer {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    border-color: #059669;
    box-shadow: 0 4px 14px rgba(5, 150, 105, 0.28);
    color: #ffffff;
}

.ur-hps-tab-pill {
    font-size: 0.62rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    padding: 0.12rem 0.45rem;
    border-radius: 9999px;
    background: #e2e8f0;
    color: #475569;
    transition: all 0.2s ease;
}

.ur-hps-tab-btn.active .ur-hps-tab-pill {
    background: rgba(255, 255, 255, 0.25);
    color: #ffffff;
}

/* ─── SLIDER STAGE WRAPPER ───────────────────────────────── */
.ur-hps-slider-stage {
    position: relative;
    width: 100%;
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    border-radius: 1.5rem;
    box-shadow: 0 16px 40px -10px rgba(15, 23, 42, 0.07), 0 2px 6px rgba(15, 23, 42, 0.03);
    overflow: hidden;
}

/* Slide Track */
.ur-hps-track {
    display: flex;
    width: 100%;
    transition: transform 0.6s cubic-bezier(0.2, 0.9, 0.3, 1);
    will-change: transform;
}

/* Individual Slide */
.ur-hps-slide {
    flex: 0 0 100%;
    width: 100%;
    padding: 0.8rem 1.25rem 0.6rem; /* Tight compact slide padding */
    box-sizing: border-box;
    position: relative;
    overflow: hidden;
}

.ur-hps-slide--gold {
    background: radial-gradient(ellipse at 85% 30%, rgba(245, 158, 11, 0.06) 0%, transparent 60%),
                linear-gradient(135deg, #ffffff 0%, #fffdf8 50%, #fffbeb 100%);
}
.ur-hps-slide--platinum {
    background: radial-gradient(ellipse at 85% 30%, rgba(59, 130, 246, 0.06) 0%, transparent 60%),
                linear-gradient(135deg, #ffffff 0%, #f8faff 50%, #eff6ff 100%);
}
.ur-hps-slide--silver {
    background: radial-gradient(ellipse at 85% 30%, rgba(148, 163, 184, 0.06) 0%, transparent 60%),
                linear-gradient(135deg, #ffffff 0%, #fbfcfd 50%, #f1f5f9 100%);
}
/* ─── BUYER PASS SLIDE: 5-LAYER REAL ESTATE COMPOSITION ─────── */
.ur-hps-slide--buyer {
    position: relative;
    background: linear-gradient(135deg, #f8fcf9 0%, #ffffff 40%, #ecfdf5 100%);
    overflow: hidden;
}

/* Background Layers Wrap (Layer 1-4) */
.ur-buyer-bg-wrap {
    position: absolute;
    inset: 0;
    pointer-events: none;
    z-index: 1;
    overflow: hidden;
}

/* Layer 2: Cityscape & Residential Apartment Towers */
.ur-buyer-bg-skyline {
    position: absolute;
    top: -5%;
    left: -5%;
    width: 110%;
    height: 110%;
    background-image: url('/images/buyer_pass/buyer_skyline_bg.webp');
    background-size: cover;
    background-position: left center;
    opacity: 0.13; /* Subtle 8-18% range */
    filter: blur(2px);
    transform-origin: center center;
    animation: urKenBurnsSkyline 22s ease-in-out infinite alternate;
    will-change: transform;
}

/* Layer 3: Subtle Luxury Villa Image */
.ur-buyer-bg-villa {
    position: absolute;
    top: -5%;
    right: -5%;
    width: 65%;
    height: 110%;
    background-image: url('/images/buyer_pass/buyer_villa_bg.webp');
    background-size: cover;
    background-position: right center;
    opacity: 0.12; /* Subtle 8-18% range */
    filter: blur(1.5px);
    mask-image: radial-gradient(ellipse at 75% 50%, rgba(0, 0, 0, 1) 15%, rgba(0, 0, 0, 0) 80%);
    -webkit-mask-image: radial-gradient(ellipse at 75% 50%, rgba(0, 0, 0, 1) 15%, rgba(0, 0, 0, 0) 80%);
    transform-origin: 75% center;
    animation: urKenBurnsVilla 18s ease-in-out infinite alternate;
    will-change: transform;
}

/* Layer 4: Translucent White & Emerald Gradient Overlay for 100% Readability */
.ur-buyer-bg-overlay {
    position: absolute;
    inset: 0;
    background: 
        radial-gradient(ellipse at 25% 45%, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.88) 60%, rgba(240, 253, 244, 0.8) 100%),
        linear-gradient(90deg, rgba(255, 255, 255, 0.96) 0%, rgba(255, 255, 255, 0.88) 45%, rgba(236, 253, 245, 0.82) 100%);
}

/* Architectural CAD Line Grid */
.ur-buyer-bg-blueprint {
    position: absolute;
    inset: 0;
    background-image: 
        linear-gradient(rgba(5, 150, 105, 0.035) 1px, transparent 1px),
        linear-gradient(90deg, rgba(5, 150, 105, 0.035) 1px, transparent 1px);
    background-size: 34px 34px;
    mask-image: radial-gradient(ellipse at center, rgba(0, 0, 0, 0.7) 0%, transparent 80%);
    -webkit-mask-image: radial-gradient(ellipse at center, rgba(0, 0, 0, 0.7) 0%, transparent 80%);
}

/* Floating subtle micro-icons */
.ur-buyer-floating-accent {
    position: absolute;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #059669;
    pointer-events: none;
    opacity: 0.16;
}
.ur-buyer-floating-accent--1 {
    top: 7%;
    right: 28%;
    font-size: 1.5rem;
    animation: urFloatSlow 7s ease-in-out infinite alternate;
}
.ur-buyer-floating-accent--2 {
    bottom: 12%;
    left: 3%;
    font-size: 1.35rem;
    animation: urFloatSlow 9s ease-in-out 1.2s infinite alternate-reverse;
}

/* Ken Burns Subtle Background Movement */
@keyframes urKenBurnsSkyline {
    0% {
        transform: scale(1) translate(0, 0);
    }
    100% {
        transform: scale(1.05) translate(-1.2%, -0.8%);
    }
}

@keyframes urKenBurnsVilla {
    0% {
        transform: scale(1) translate(0, 0);
    }
    100% {
        transform: scale(1.06) translate(1%, 0.6%);
    }
}

@keyframes urFloatSlow {
    0% {
        transform: translateY(0) rotate(0deg);
    }
    100% {
        transform: translateY(-8px) rotate(4deg);
    }
}

@media (prefers-reduced-motion: reduce) {
    .ur-buyer-bg-skyline,
    .ur-buyer-bg-villa,
    .ur-buyer-floating-accent {
        animation: none !important;
        transform: none !important;
    }
}

/* ─── 3-PART HORIZONTAL DESKTOP GRID ─────────────────────── */
.ur-hps-desktop-grid {
    display: grid;
    grid-template-columns: 1.15fr 0.98fr 0.75fr; /* Left (Value) · Center (Pricing) · Right (Visual) */
    gap: 1rem;
    align-items: stretch;
    width: 100%;
}

/* ─── 1. LEFT COLUMN: VALUE PROPOSITION & 2x2 BENEFITS ──── */
.ur-hps-col-left {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    z-index: 5;
    padding-right: 0.35rem;
}

.ur-hps-plan-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.65rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    padding: 0.22rem 0.7rem;
    border-radius: 9999px;
    margin-bottom: 0.35rem;
    width: fit-content;
}

.ur-hps-plan-badge--gold {
    background: #fef3c7;
    color: #b45309;
    border: 1px solid #fde68a;
    box-shadow: 0 2px 6px rgba(245, 158, 11, 0.1);
}
.ur-hps-plan-badge--platinum {
    background: #dbeafe;
    color: #1e40af;
    border: 1px solid #bfdbfe;
    box-shadow: 0 2px 6px rgba(37, 99, 235, 0.1);
}
.ur-hps-plan-badge--silver {
    background: #f1f5f9;
    color: #334155;
    border: 1px solid #cbd5e1;
}
.ur-hps-plan-badge--buyer {
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #a7f3d0;
    box-shadow: 0 2px 6px rgba(16, 185, 129, 0.1);
}

.ur-hps-slide-title {
    font-size: clamp(1.35rem, 1.7vw, 1.7rem); /* Compact bold heading ~28-32px */
    font-weight: 900;
    color: #0f172a;
    line-height: 1.14;
    letter-spacing: -0.025em;
    margin: 0 0 0.3rem;
}

.ur-hps-slide-title .highlight-gold {
    background: linear-gradient(135deg, #d97706 0%, #b45309 60%, #ea580c 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    display: inline-block;
}
.ur-hps-slide-title .highlight-platinum {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 60%, #1e40af 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    display: inline-block;
}
.ur-hps-slide-title .highlight-silver {
    background: linear-gradient(135deg, #334155 0%, #1e293b 60%, #0f172a 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    display: inline-block;
}
.ur-hps-slide-title .highlight-buyer {
    background: linear-gradient(135deg, #059669 0%, #047857 60%, #064e3b 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    display: inline-block;
}

.ur-hps-slide-desc {
    font-size: 0.78rem;
    color: #475569;
    line-height: 1.35;
    margin: 0 0 0.5rem;
    max-width: 480px;
}

/* Feature 2x2 Highlights Grid — Tight, compact, elegant */
.ur-hps-features-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.4rem;
    width: 100%;
}

.ur-hps-feature-item {
    display: flex;
    align-items: flex-start;
    gap: 0.5rem;
    padding: 0.42rem 0.6rem;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 0.75rem;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.02);
    transition: all 0.22s ease;
}

.ur-hps-feature-item:hover {
    border-color: #cbd5e1;
    transform: translateY(-1.5px);
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
}

.ur-hps-f-icon-box {
    width: 1.75rem;
    height: 1.75rem;
    border-radius: 0.5rem;
    background: #eff6ff;
    color: #2563eb;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.95rem;
    flex-shrink: 0;
}

.ur-hps-feature-item--gold .ur-hps-f-icon-box {
    background: #fef3c7;
    color: #d97706;
    border: 1px solid #fde68a;
}
.ur-hps-feature-item--platinum .ur-hps-f-icon-box {
    background: #eff6ff;
    color: #2563eb;
    border: 1px solid #dbeafe;
}
.ur-hps-feature-item--silver .ur-hps-f-icon-box {
    background: #f8fafc;
    color: #475569;
    border: 1px solid #e2e8f0;
}
.ur-hps-feature-item--buyer {
    background: rgba(255, 255, 255, 0.92);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    border: 1px solid rgba(167, 243, 208, 0.85);
    border-radius: 16px; /* 16px compact radius */
    box-shadow: 0 2px 6px rgba(5, 150, 105, 0.04);
    transition: all 0.28s cubic-bezier(0.16, 1, 0.3, 1);
}
.ur-hps-feature-item--buyer:hover {
    border-color: #10b981;
    transform: translateY(-3px);
    box-shadow: 0 8px 22px rgba(5, 150, 105, 0.13);
}
.ur-hps-feature-item--buyer .ur-hps-f-icon-box {
    background: #ecfdf5;
    color: #059669;
    border: 1px solid #d1fae5;
    border-radius: 0.5rem;
}

.ur-hps-f-text h4 {
    font-size: 0.75rem;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 0.08rem;
    letter-spacing: -0.01em;
    line-height: 1.2;
}

.ur-hps-f-text p {
    font-size: 0.66rem;
    color: #64748b;
    margin: 0;
    line-height: 1.2;
}

/* ─── 2. CENTER COLUMN: COMPACT PRICING CARD ─────────────── */
.ur-hps-col-center {
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 6;
}

.ur-hps-pass-card {
    position: relative;
    width: 100%;
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    border-radius: 1.15rem;
    padding: 0.82rem 1rem; /* Compact padding eliminates vertical stretch */
    box-sizing: border-box;
    box-shadow: 0 10px 28px -6px rgba(15, 23, 42, 0.07), 0 2px 5px rgba(0, 0, 0, 0.02);
    transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s ease;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.ur-hps-pass-card:hover {
    transform: translateY(-3px);
}

.ur-hps-pass-card--gold {
    border-color: #fde047;
    box-shadow: 0 12px 32px -6px rgba(245, 158, 11, 0.18), 0 2px 6px rgba(0, 0, 0, 0.03);
}
.ur-hps-pass-card--platinum {
    border-color: #93c5fd;
    box-shadow: 0 12px 32px -6px rgba(37, 99, 235, 0.18), 0 2px 6px rgba(0, 0, 0, 0.03);
}
.ur-hps-pass-card--silver {
    border-color: #cbd5e1;
    box-shadow: 0 12px 32px -6px rgba(71, 85, 105, 0.14), 0 2px 6px rgba(0, 0, 0, 0.03);
}
.ur-hps-pass-card--buyer {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(18px);
    -webkit-backdrop-filter: blur(18px);
    border: 1.5px solid #10b981;
    border-radius: 22px;
    box-shadow: 
        0 20px 45px -10px rgba(5, 150, 105, 0.22),
        0 0 0 1px rgba(245, 158, 11, 0.16),
        0 4px 14px rgba(15, 23, 42, 0.04);
    position: relative;
    z-index: 10;
}
.ur-hps-pass-card--buyer:hover {
    transform: translateY(-4px);
    box-shadow: 
        0 26px 52px -10px rgba(5, 150, 105, 0.28),
        0 0 0 1.5px rgba(16, 185, 129, 0.45),
        0 8px 22px rgba(15, 23, 42, 0.06);
}

/* Card Header with Emblem */
.ur-hps-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 0.42rem;
}

.ur-hps-brand-tag {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.68rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: #334155;
}

.ur-hps-brand-tag span.ur-accent {
    color: #2563eb;
}

.ur-hps-card-emblem {
    width: 1.85rem;
    height: 1.85rem;
    border-radius: 0.55rem;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 6px rgba(15, 23, 42, 0.04);
}

.ur-emblem-svg {
    width: 1.25rem;
    height: 1.25rem;
}

/* Price Box */
.ur-hps-price-box {
    margin-bottom: 0.42rem;
    padding-bottom: 0.42rem;
    border-bottom: 1px solid #f1f5f9;
}

.ur-hps-price-top {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    margin-bottom: 0.12rem;
}

.ur-hps-price-original {
    font-size: 0.85rem;
    font-weight: 700;
    color: #94a3b8;
    text-decoration: line-through;
}

.ur-hps-save-chip {
    font-size: 0.62rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    padding: 0.1rem 0.42rem;
    border-radius: 9999px;
    background: #ecfdf5;
    color: #047857;
    border: 1px solid #a7f3d0;
}

.ur-hps-price-main {
    display: flex;
    align-items: baseline;
    gap: 0.2rem;
    margin-bottom: 0.12rem;
}

.ur-hps-currency {
    font-size: 1.2rem;
    font-weight: 900;
    color: #0f172a;
}

.ur-hps-amount {
    font-size: 2.05rem; /* Compact dominant price */
    font-weight: 900;
    color: #0f172a;
    line-height: 1;
    letter-spacing: -0.035em;
}

.ur-hps-period {
    font-size: 0.74rem;
    font-weight: 600;
    color: #64748b;
}

.ur-hps-price-subtext {
    font-size: 0.68rem;
    font-weight: 700;
    color: #059669;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

/* Card Specs Pill List */
.ur-hps-card-meta {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    margin-bottom: 0.42rem;
    background: #f8fafc;
    padding: 0.38rem 0.65rem;
    border-radius: 0.65rem;
    border: 1px solid #f1f5f9;
}

.ur-hps-meta-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 0.72rem;
    color: #0f172a;
}

.ur-hps-meta-row span.meta-label {
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 0.25rem;
    font-weight: 600;
}

.ur-hps-meta-row span.meta-label i {
    color: #059669;
    font-size: 0.78rem;
}

.ur-hps-meta-row span.meta-value {
    font-weight: 800;
    color: #0f172a;
}

.ur-hps-meta-row span.meta-value.meta-accent {
    color: #059669;
}

/* Primary High-Impact Orange CTA Button */
.ur-hps-cta-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.45rem;
    width: 100%;
    padding: 0.62rem 0.9rem;
    border-radius: 0.75rem;
    font-family: inherit;
    font-size: 0.86rem;
    font-weight: 900;
    letter-spacing: -0.01em;
    text-decoration: none;
    cursor: pointer;
    box-sizing: border-box;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
    overflow: hidden;
    margin-bottom: 0.22rem;
    border: none;
}

.ur-hps-cta-btn--gold {
    background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%);
    color: #ffffff !important;
    box-shadow: 0 8px 22px rgba(234, 88, 12, 0.35);
}
.ur-hps-cta-btn--gold:hover {
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    box-shadow: 0 12px 28px rgba(234, 88, 12, 0.45);
    transform: translateY(-2px);
    color: #ffffff !important;
}

.ur-hps-cta-btn--platinum {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    color: #ffffff !important;
    box-shadow: 0 8px 22px rgba(37, 99, 235, 0.35);
}
.ur-hps-cta-btn--platinum:hover {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    transform: translateY(-2px);
}

.ur-hps-cta-btn--silver {
    background: linear-gradient(135deg, #334155 0%, #1e293b 100%);
    color: #ffffff !important;
    box-shadow: 0 8px 20px rgba(15, 23, 42, 0.22);
}
.ur-hps-cta-btn--silver:hover {
    background: linear-gradient(135deg, #475569 0%, #334155 100%);
    transform: translateY(-2px);
}

.ur-hps-cta-btn--buyer {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    color: #ffffff !important;
    box-shadow: 0 8px 24px rgba(5, 150, 105, 0.38), 0 2px 4px rgba(4, 120, 87, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.25);
    position: relative;
    overflow: hidden;
}
.ur-hps-cta-btn--buyer::after {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(
        60deg,
        transparent,
        rgba(255, 255, 255, 0.3),
        transparent
    );
    transform: rotate(30deg) translateX(-100%);
    transition: transform 0.75s ease;
}
.ur-hps-cta-btn--buyer:hover::after {
    transform: rotate(30deg) translateX(100%);
}
.ur-hps-cta-btn--buyer:hover {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    box-shadow: 0 12px 30px rgba(5, 150, 105, 0.48), 0 4px 8px rgba(4, 120, 87, 0.25);
    transform: translateY(-2px);
    color: #ffffff !important;
}

/* Compact Scroll Down Link */
.ur-hps-compare-link {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.3rem;
    font-size: 0.72rem;
    font-weight: 700;
    color: #64748b;
    text-decoration: none;
    transition: color 0.2s ease;
    text-align: center;
    padding-top: 0.15rem;
}

.ur-hps-compare-link:hover {
    color: #0f172a;
}

/* ─── 3. RIGHT COLUMN: SUBTLE PREMIUM VISUAL ELEMENT ─────── */
/* Eliminates the large blank empty area on desktop */
.ur-hps-col-right {
    display: flex;
    align-items: stretch;
    justify-content: center;
    z-index: 5;
}

.ur-hps-visual-card {
    position: relative;
    width: 100%;
    background: linear-gradient(160deg, #ffffff 0%, #fffdf7 60%, #fffbeb 100%);
    border: 1px solid #fde68a;
    border-radius: 1.15rem;
    padding: 0.75rem 0.85rem;
    box-sizing: border-box;
    box-shadow: 0 6px 20px rgba(245, 158, 11, 0.08);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    overflow: hidden;
}

.ur-hps-visual-card--platinum {
    background: linear-gradient(160deg, #ffffff 0%, #f8faff 60%, #eff6ff 100%);
    border-color: #bfdbfe;
    box-shadow: 0 6px 20px rgba(37, 99, 235, 0.08);
}
.ur-hps-visual-card--silver {
    background: linear-gradient(160deg, #ffffff 0%, #fbfcfd 60%, #f1f5f9 100%);
    border-color: #cbd5e1;
    box-shadow: 0 6px 20px rgba(71, 85, 105, 0.06);
}
.ur-hps-visual-card--buyer {
    background: linear-gradient(160deg, #ffffff 0%, #f7fef9 60%, #ecfdf5 100%);
    border-color: #a7f3d0;
    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.08);
}

.ur-hps-vc-illustration {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 0.35rem;
}

.ur-hps-house-svg {
    width: 100%;
    max-width: 105px;
    height: auto;
    display: block;
    margin: 0 auto;
}

.ur-hps-vc-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    font-size: 0.62rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    padding: 0.18rem 0.5rem;
    border-radius: 9999px;
    background: #fef3c7;
    color: #b45309;
    border: 1px solid #fde68a;
    margin-bottom: 0.3rem;
    width: fit-content;
}

.ur-hps-vc-title {
    font-size: 0.84rem;
    font-weight: 900;
    color: #0f172a;
    margin: 0 0 0.12rem;
    letter-spacing: -0.015em;
}

.ur-hps-vc-sub {
    font-size: 0.68rem;
    color: #64748b;
    line-height: 1.25;
    margin: 0 0 0.35rem;
}

.ur-hps-vc-list {
    display: flex;
    flex-direction: column;
    gap: 0.22rem;
}

.ur-hps-vc-row {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.68rem;
    font-weight: 700;
    color: #1e293b;
}

.ur-hps-vc-row i {
    color: #059669;
    font-size: 0.8rem;
    flex-shrink: 0;
}

/* ─── PREMIUM PHOTOREALISTIC BUYER VISUAL CARD ──────────────── */
.ur-buyer-visual-card {
    position: relative;
    width: 100%;
    background: #ffffff;
    border: 1.5px solid rgba(167, 243, 208, 0.85);
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 10px 28px -8px rgba(5, 150, 105, 0.14), 0 2px 5px rgba(15, 23, 42, 0.03);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.28s ease;
}

.ur-buyer-visual-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 16px 36px -8px rgba(5, 150, 105, 0.22);
}

.ur-buyer-vc-photo-wrapper {
    position: relative;
    width: 100%;
    height: 96px; /* Tight compact photo height */
    overflow: hidden;
    background: #0f172a;
}

.ur-buyer-vc-photo {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center 32%;
    transition: transform 0.75s cubic-bezier(0.16, 1, 0.3, 1);
    display: block;
}

.ur-buyer-visual-card:hover .ur-buyer-vc-photo {
    transform: scale(1.06);
}

.ur-buyer-vc-scrim {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(15, 23, 42, 0.1) 0%, rgba(15, 23, 42, 0.68) 100%);
}

.ur-buyer-vc-tag-overlay {
    position: absolute;
    bottom: 0.45rem;
    left: 0.65rem;
    right: 0.65rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    z-index: 2;
}

.ur-buyer-vc-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    font-size: 0.6rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    padding: 0.18rem 0.5rem;
    border-radius: 9999px;
    background: rgba(254, 243, 199, 0.96);
    color: #b45309;
    border: 1px solid #fde68a;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.16);
    backdrop-filter: blur(4px);
}

.ur-buyer-vc-subtag {
    font-size: 0.58rem;
    font-weight: 700;
    color: #ffffff;
    background: rgba(5, 150, 105, 0.92);
    padding: 0.15rem 0.45rem;
    border-radius: 9999px;
    backdrop-filter: blur(4px);
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    box-shadow: 0 2px 6px rgba(5, 150, 105, 0.25);
}

.ur-buyer-vc-body {
    padding: 0.55rem 0.8rem 0.6rem; /* Tight compact body */
    display: flex;
    flex-direction: column;
    background: linear-gradient(180deg, #ffffff 0%, #f7fdf9 100%);
}

.ur-buyer-vc-title {
    font-size: 0.82rem;
    font-weight: 900;
    color: #0f172a;
    margin: 0 0 0.12rem;
    letter-spacing: -0.015em;
}

.ur-buyer-vc-sub {
    font-size: 0.68rem;
    color: #64748b;
    line-height: 1.25;
    margin: 0 0 0.35rem;
}

.ur-buyer-vc-list {
    display: flex;
    flex-direction: column;
    gap: 0.22rem;
}

.ur-buyer-vc-row {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.68rem;
    font-weight: 700;
    color: #1e293b;
}

.ur-buyer-vc-row i {
    color: #059669;
    font-size: 0.8rem;
    flex-shrink: 0;
}

.ur-buyer-vc-trust-note {
    margin-top: 0.35rem;
    padding-top: 0.35rem;
    border-top: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    gap: 0.3rem;
    font-size: 0.65rem;
    font-weight: 700;
    color: #047857;
}

.ur-buyer-vc-trust-note i {
    color: #f59e0b;
    font-size: 0.72rem;
}

/* ─── 4. BOTTOM HORIZONTAL TRUST ROW (TRANSLUCENT FLOATING BAR) ── */
.ur-hps-trust-bar {
    grid-column: 1 / -1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1.25rem;
    flex-wrap: wrap;
    background: rgba(255, 255, 255, 0.86);
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
    border: 1px solid rgba(167, 243, 208, 0.7);
    border-radius: 9999px;
    padding: 0.35rem 1.25rem;
    margin-top: 0.45rem;
    box-shadow: 0 3px 12px rgba(5, 150, 105, 0.05), 0 1px 3px rgba(0, 0, 0, 0.02);
    font-size: 0.71rem;
    font-weight: 700;
    color: #334155;
    transition: all 0.25s ease;
}

.ur-hps-trust-bar:hover {
    box-shadow: 0 5px 18px rgba(5, 150, 105, 0.1);
    border-color: rgba(16, 185, 129, 0.85);
}

.ur-hps-tb-item {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
}

.ur-hps-tb-item i {
    color: #059669;
    font-size: 0.88rem;
}

.ur-hps-tb-divider {
    width: 3.5px;
    height: 3.5px;
    border-radius: 50%;
    background: #cbd5e1;
}

/* ─── SLIDER CONTROLS (COMPACT ARROWS & BOTTOM DOTS) ─────── */
.ur-hps-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 2.15rem;
    height: 2.15rem;
    border-radius: 50%;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    color: #0f172a;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.95rem;
    cursor: pointer;
    z-index: 20;
    transition: all 0.25s ease;
    box-shadow: 0 4px 14px rgba(15, 23, 42, 0.08);
}

.ur-hps-arrow:hover {
    background: #f59e0b;
    border-color: #f59e0b;
    color: #ffffff;
    transform: translateY(-50%) scale(1.08);
    box-shadow: 0 6px 18px rgba(245, 158, 11, 0.35);
}

.ur-hps-arrow--prev { left: 0.65rem; }
.ur-hps-arrow--next { right: 0.65rem; }

/* Bottom Nav Indicator Dots */
.ur-hps-bottom-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    margin-top: 0.45rem;
    padding: 0 0.5rem;
}

.ur-hps-dots {
    display: flex;
    align-items: center;
    gap: 0.4rem;
}

.ur-hps-dot {
    width: 0.5rem;
    height: 0.5rem;
    border-radius: 50%;
    background: #cbd5e1;
    border: none;
    cursor: pointer;
    padding: 0;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}

.ur-hps-dot.active {
    width: 1.6rem;
    border-radius: 9999px;
    background: #f59e0b;
    box-shadow: 0 2px 8px rgba(245, 158, 11, 0.35);
}

.ur-hps-swipe-hint {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.72rem;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.ur-hps-swipe-hint i {
    color: #f59e0b;
    font-size: 0.9rem;
}

/* ─── RESPONSIVE BEHAVIOR (MOBILE & TABLET) ──────────────── */
@media (max-width: 1023px) {
    .ur-hero-plans-slider-section {
        padding: 1.25rem 0 1.5rem;
    }
    .ur-hps-container {
        padding: 0 0.85rem;
    }
    .ur-hps-top-nav {
        margin-bottom: 0.75rem;
        flex-direction: column;
        align-items: stretch;
        gap: 0.5rem;
    }
    .ur-hps-top-heading {
        align-self: flex-start;
        font-size: 0.72rem;
    }
    .ur-hps-tabs-bar {
        width: 100%;
        overflow-x: auto;
        flex-wrap: nowrap;
        justify-content: flex-start;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }
    .ur-hps-tabs-bar::-webkit-scrollbar {
        display: none;
    }
    .ur-hps-tab-btn {
        flex: 0 0 auto;
        padding: 0.35rem 0.75rem;
        font-size: 0.76rem;
    }

    /* Hide arrows on touch screens */
    .ur-hps-arrow {
        display: none;
    }

    .ur-hps-slide {
        padding: 1.25rem 1rem;
    }

    /* Naturally Stacked Layout on Mobile as requested */
    .ur-hps-desktop-grid {
        display: flex;
        flex-direction: column;
        gap: 1.15rem;
    }

    .ur-hps-col-left {
        padding-right: 0;
    }

    .ur-hps-slide-title {
        font-size: 1.45rem; /* ~30-34px on tablet/mobile */
        line-height: 1.2;
    }

    .ur-hps-slide-desc {
        font-size: 0.82rem;
        margin-bottom: 0.75rem;
    }

    /* Benefits: 2-columns on standard mobile, 1-col on tiny */
    .ur-hps-features-grid {
        gap: 0.5rem;
    }

    .ur-hps-feature-item {
        padding: 0.55rem 0.65rem;
    }

    .ur-hps-f-icon-box {
        width: 1.85rem;
        height: 1.85rem;
        font-size: 0.95rem;
    }

    .ur-hps-f-text h4 {
        font-size: 0.78rem;
    }

    .ur-hps-f-text p {
        font-size: 0.68rem;
    }

    /* Pricing card full width on mobile */
    .ur-hps-pass-card {
        padding: 1rem;
    }

    .ur-hps-amount {
        font-size: 2.15rem;
    }

    .ur-hps-cta-btn {
        padding: 0.85rem 1rem;
        font-size: 0.95rem;
    }

    /* Hide right visual on mobile to keep page short and fast */
    .ur-hps-col-right {
        display: none;
    }

    /* Mobile background performance & subtle opacity */
    .ur-buyer-bg-villa {
        display: none;
    }
    .ur-buyer-bg-skyline {
        opacity: 0.08;
        filter: blur(1.5px);
        animation: none;
    }
    .ur-buyer-bg-blueprint,
    .ur-buyer-floating-accent {
        display: none;
    }

    .ur-hps-trust-bar {
        border-radius: 14px;
        padding: 0.65rem 0.85rem;
        gap: 0.5rem 0.85rem;
        margin-top: 0.35rem;
        font-size: 0.72rem;
    }

    .ur-hps-bottom-bar {
        margin-top: 0.65rem;
        justify-content: center;
    }

    .ur-hps-swipe-hint {
        display: none;
    }
}

@media (max-width: 420px) {
    .ur-hps-features-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<section class="ur-hero-plans-slider-section" id="hero-plans-slider-section">
    {{-- Ambient Light Orbs --}}
    <div class="ur-hps-ambient-orb ur-hps-ambient-orb--1" id="hpsOrb1"></div>
    <div class="ur-hps-ambient-orb ur-hps-ambient-orb--2" id="hpsOrb2"></div>
    <div class="ur-hps-grid-pattern"></div>

    <div class="ur-hps-container">
        {{-- Section Top Nav Bar --}}
        <div class="ur-hps-top-nav">
            <span class="ur-hps-top-heading">
                <i class="ph-fill ph-shield-check"></i>
                Zero Brokerage Direct Pass
            </span>

            {{-- Quick Plan Switcher Tabs --}}
            <div class="ur-hps-tabs-bar" role="tablist" aria-label="Plans quick navigation">
                @foreach($heroSlides as $idx => $s)
                    <button type="button" 
                            class="ur-hps-tab-btn {{ $idx === 0 ? 'active tab--' . $s['theme'] : '' }}" 
                            data-target-index="{{ $idx }}"
                            data-theme="{{ $s['theme'] }}"
                            role="tab"
                            aria-selected="{{ $idx === 0 ? 'true' : 'false' }}"
                            title="{{ $s['tab_label'] }}">
                        <i class="ph-bold {{ $s['tab_icon'] }}"></i>
                        <span>{{ $s['tab_label'] }}</span>
                        @if(!empty($s['tab_badge']))
                            <span class="ur-hps-tab-pill">{{ $s['tab_badge'] }}</span>
                        @endif
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Slider Stage --}}
        <div class="ur-hps-slider-stage" id="heroPlanSliderStage">
            {{-- Prev & Next Navigation Arrows --}}
            <button type="button" class="ur-hps-arrow ur-hps-arrow--prev" id="heroPlanPrevBtn" aria-label="Previous Plan" title="Previous Plan">
                <i class="ph-bold ph-caret-left"></i>
            </button>
            <button type="button" class="ur-hps-arrow ur-hps-arrow--next" id="heroPlanNextBtn" aria-label="Next Plan" title="Next Plan">
                <i class="ph-bold ph-caret-right"></i>
            </button>

            {{-- Slider Track --}}
            <div class="ur-hps-track" id="heroPlanTrack">
                @foreach($heroSlides as $idx => $s)
                    @php
                        $plan = $s['plan'];
                        $planId = $plan->id ?? 1;
                        $hasCheckout = Route::has('plans.checkout');
                        $checkoutUrl = $hasCheckout 
                            ? route('plans.checkout', ['plan' => $planId, 'billing' => ($s['type'] === 'buy' ? 'yearly' : 'monthly'), 'direct' => 1])
                            : url('/plans');
                        $planUid = 'hero_slider_plan_' . $planId . '_' . $idx;
                    @endphp

                    <div class="ur-hps-slide ur-hps-slide--{{ $s['theme'] }}" data-slide-index="{{ $idx }}" data-theme="{{ $s['theme'] }}">
                        @if($s['theme'] === 'buyer')
                            {{-- Layered Photorealistic Background Elements --}}
                            <div class="ur-buyer-bg-wrap" aria-hidden="true">
                                <div class="ur-buyer-bg-skyline"></div>
                                <div class="ur-buyer-bg-villa"></div>
                                <div class="ur-buyer-bg-overlay"></div>
                                <div class="ur-buyer-bg-blueprint"></div>
                                <div class="ur-buyer-floating-accent ur-buyer-floating-accent--1">
                                    <i class="ph-bold ph-key"></i>
                                </div>
                                <div class="ur-buyer-floating-accent ur-buyer-floating-accent--2">
                                    <i class="ph-bold ph-house-line"></i>
                                </div>
                            </div>
                        @endif

                        <div class="ur-hps-desktop-grid">
                            
                            {{-- PART 1: LEFT COLUMN (Value Proposition & Benefits 2x2) --}}
                            <div class="ur-hps-col-left">
                                <span class="ur-hps-plan-badge ur-hps-plan-badge--{{ $s['theme'] }}">
                                    {!! $s['badge'] !!}
                                </span>

                                <h3 class="ur-hps-slide-title">
                                    {{ $s['title_prefix'] }} <br>
                                    <span class="highlight-{{ $s['theme'] }}">{{ $s['title_highlight'] }}</span>
                                </h3>

                                <p class="ur-hps-slide-desc">
                                    {{ $s['tagline'] }}
                                </p>

                                {{-- 2x2 Highlights Grid --}}
                                <div class="ur-hps-features-grid">
                                    @foreach($s['highlights'] as $f)
                                        <div class="ur-hps-feature-item ur-hps-feature-item--{{ $s['theme'] }}">
                                            <div class="ur-hps-f-icon-box">
                                                <i class="ph-bold {{ $f['icon'] }}"></i>
                                            </div>
                                            <div class="ur-hps-f-text">
                                                <h4>{{ $f['title'] }}</h4>
                                                <p>{{ $f['desc'] }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- PART 2: CENTER COLUMN (Compact High-Conversion Pricing Card) --}}
                            <div class="ur-hps-col-center">
                                <div class="ur-hps-pass-card ur-hps-pass-card--{{ $s['theme'] }}">
                                    {{-- Card Header --}}
                                    <div class="ur-hps-card-header">
                                        <div class="ur-hps-brand-tag">
                                            <i class="ph-bold ph-key"></i>
                                            <span>Unlock<span class="ur-accent">Rentals</span> Pass</span>
                                        </div>
                                        <div class="ur-hps-card-emblem">
                                            @if($s['theme'] === 'gold')
                                                <svg class="ur-emblem-svg" viewBox="0 0 48 48" fill="none">
                                                    <defs>
                                                        <linearGradient id="goldHero_{{ $planUid }}" x1="4" y1="8" x2="44" y2="40" gradientUnits="userSpaceOnUse">
                                                            <stop offset="0%" stop-color="#FDE047"/>
                                                            <stop offset="45%" stop-color="#F59E0B"/>
                                                            <stop offset="100%" stop-color="#D97706"/>
                                                        </linearGradient>
                                                    </defs>
                                                    <path d="M6 34L10 14L19 23L24 8L29 23L38 14L42 34H6Z" fill="url(#goldHero_{{ $planUid }})"/>
                                                    <rect x="6" y="34" width="36" height="6" rx="3" fill="#B45309"/>
                                                    <circle cx="24" cy="8" r="3.5" fill="#EF4444" stroke="#FFF" stroke-width="1.5"/>
                                                    <circle cx="10" cy="14" r="3" fill="#3B82F6" stroke="#FFF" stroke-width="1.5"/>
                                                    <circle cx="38" cy="14" r="3" fill="#3B82F6" stroke="#FFF" stroke-width="1.5"/>
                                                    <circle cx="24" cy="37" r="2" fill="#10B981"/>
                                                </svg>
                                            @elseif($s['theme'] === 'platinum')
                                                <svg class="ur-emblem-svg" viewBox="0 0 48 48" fill="none">
                                                    <defs>
                                                        <linearGradient id="platHero_{{ $planUid }}" x1="6" y1="10" x2="42" y2="42" gradientUnits="userSpaceOnUse">
                                                            <stop offset="0%" stop-color="#93C5FD"/>
                                                            <stop offset="50%" stop-color="#3B82F6"/>
                                                            <stop offset="100%" stop-color="#1D4ED8"/>
                                                        </linearGradient>
                                                    </defs>
                                                    <polygon points="14,10 34,10 42,20 6,20" fill="url(#platHero_{{ $planUid }})"/>
                                                    <polygon points="6,20 42,20 24,42" fill="url(#platHero_{{ $planUid }})"/>
                                                    <polygon points="15,20 33,20 24,42" fill="#BAE6FD" fill-opacity="0.8"/>
                                                </svg>
                                            @elseif($s['theme'] === 'buyer')
                                                <svg class="ur-emblem-svg" viewBox="0 0 48 48" fill="none">
                                                    <defs>
                                                        <linearGradient id="buyHero_{{ $planUid }}" x1="6" y1="8" x2="42" y2="42" gradientUnits="userSpaceOnUse">
                                                            <stop offset="0%" stop-color="#A7F3D0"/>
                                                            <stop offset="50%" stop-color="#10B981"/>
                                                            <stop offset="100%" stop-color="#047857"/>
                                                        </linearGradient>
                                                    </defs>
                                                    <path d="M24 6L6 20V40C6 41.1 6.9 42 8 42H40C41.1 42 42 41.1 42 40V20L24 6Z" fill="url(#buyHero_{{ $planUid }})"/>
                                                    <rect x="20" y="26" width="8" height="16" rx="2" fill="#064E3B"/>
                                                    <rect x="12" y="24" width="6" height="6" rx="1.5" fill="#ECFDF5"/>
                                                    <rect x="30" y="24" width="6" height="6" rx="1.5" fill="#ECFDF5"/>
                                                </svg>
                                            @else
                                                <svg class="ur-emblem-svg" viewBox="0 0 48 48" fill="none">
                                                    <defs>
                                                        <linearGradient id="silvHero_{{ $planUid }}" x1="8" y1="4" x2="40" y2="44" gradientUnits="userSpaceOnUse">
                                                            <stop offset="0%" stop-color="#E2E8F0"/>
                                                            <stop offset="50%" stop-color="#94A3B8"/>
                                                            <stop offset="100%" stop-color="#475569"/>
                                                        </linearGradient>
                                                    </defs>
                                                    <path d="M24 4L8 10V22C8 32.5 14.8 42.2 24 44C33.2 42.2 40 32.5 40 22V10L24 4Z" fill="url(#silvHero_{{ $planUid }})"/>
                                                    <path d="M24 16L26.3 21.2L32 21.8L27.8 25.6L29 31.2L24 28.3L19 31.2L20.2 25.6L16 21.8L21.7 21.2L24 16Z" fill="#FFFFFF"/>
                                                </svg>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Price Block --}}
                                    <div class="ur-hps-price-box">
                                        <div class="ur-hps-price-top">
                                            <span class="ur-hps-price-original">₹{{ number_format($s['original_price'], 0) }}</span>
                                            <span class="ur-hps-save-chip">SAVE {{ $s['savings_pct'] }}%</span>
                                        </div>

                                        <div class="ur-hps-price-main">
                                            <span class="ur-hps-currency">₹</span>
                                            <span class="ur-hps-amount">{{ number_format($s['price'], 0) }}</span>
                                            <span class="ur-hps-period">/ {{ $s['type'] === 'buy' ? 'annual pass' : 'pass' }}</span>
                                        </div>

                                        <div class="ur-hps-price-subtext">
                                            <i class="ph-bold ph-seal-check"></i>
                                            <span>Only ₹{{ $s['per_day'] }}/day · {{ $s['specs'][1]['value'] ?? ($plan->duration_days ?? 150) . ' Days' }} Validity</span>
                                        </div>
                                    </div>

                                    {{-- Key Specs Pill List --}}
                                    <div class="ur-hps-card-meta">
                                        @foreach($s['specs'] as $spec)
                                            <div class="ur-hps-meta-row">
                                                <span class="meta-label">
                                                    <i class="ph-bold ph-check-circle"></i> {{ $spec['label'] }}:
                                                </span>
                                                <span class="meta-value {{ !empty($spec['accent']) ? 'meta-accent' : '' }}">
                                                    {{ $spec['value'] }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>

                                    {{-- High-Impact Orange CTA Button --}}
                                    @guest
                                        <a href="{{ route('login', ['redirect' => $checkoutUrl]) }}" 
                                           onclick="event.preventDefault(); event.stopPropagation(); if(window.openAuthModal) { window.openAuthModal('login', '{{ $checkoutUrl }}'); } else { window.location.href='{{ route('login', ['redirect' => $checkoutUrl]) }}'; }"
                                           class="ur-hps-cta-btn ur-hps-cta-btn--{{ $s['theme'] }}" 
                                           title="Unlock Verified Contacts">
                                            <i class="ph-fill ph-lightning"></i>
                                            <span>Unlock Contacts Now · ₹{{ number_format($s['price'], 0) }}</span>
                                        </a>
                                    @else
                                        <a href="{{ $checkoutUrl }}" 
                                           class="ur-hps-cta-btn ur-hps-cta-btn--{{ $s['theme'] }}" 
                                           title="Unlock Verified Contacts">
                                            <i class="ph-fill ph-lightning"></i>
                                            <span>Unlock Contacts Now · ₹{{ number_format($s['price'], 0) }}</span>
                                        </a>
                                    @endguest

                                    {{-- Compact Scroll Down Link --}}
                                    <a href="#pricing-plans" class="ur-hps-compare-link" onclick="if(document.getElementById('pricing-plans')){ document.getElementById('pricing-plans').scrollIntoView({behavior:'smooth'}); return false; }">
                                        <span>Compare All Plan Details</span>
                                        <i class="ph-bold ph-arrow-down"></i>
                                    </a>
                                </div>
                            </div>

                            {{-- PART 3: RIGHT COLUMN (Subtle Premium Verified-Owner Visual Element) --}}
                            <div class="ur-hps-col-right">
                                @if($s['theme'] === 'buyer')
                                    {{-- High-End Photorealistic Real Estate Visual Panel for Direct Buyer Pass --}}
                                    <div class="ur-buyer-visual-card">
                                        <div class="ur-buyer-vc-photo-wrapper">
                                            <img src="{{ asset('images/buyer_pass/buyer_villa_card.webp') }}" 
                                                 alt="Luxury Modern Real Estate Indian Villa" 
                                                 class="ur-buyer-vc-photo" 
                                                 loading="lazy" 
                                                 decoding="async"
                                                 width="400" 
                                                 height="250">
                                            <div class="ur-buyer-vc-scrim"></div>
                                            <div class="ur-buyer-vc-tag-overlay">
                                                <span class="ur-buyer-vc-badge">
                                                    <i class="ph-fill ph-seal-check"></i>
                                                    <span>{{ $s['visual']['tag'] }}</span>
                                                </span>
                                                <span class="ur-buyer-vc-subtag">
                                                    <i class="ph-bold ph-shield-check"></i> 100% Genuine
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ur-buyer-vc-body">
                                            <h5 class="ur-buyer-vc-title">{{ $s['visual']['title'] }}</h5>
                                            <p class="ur-buyer-vc-sub">{{ $s['visual']['sub'] }}</p>
                                            <div class="ur-buyer-vc-list">
                                                @foreach($s['visual']['perks'] as $perk)
                                                    <div class="ur-buyer-vc-row">
                                                        <i class="ph-bold ph-check"></i>
                                                        <span>{{ $perk }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="ur-buyer-vc-trust-note">
                                                <i class="ph-fill ph-sparkle"></i>
                                                <span>Direct Seller Negotiation · ₹0 Middleman</span>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="ur-hps-visual-card ur-hps-visual-card--{{ $s['theme'] }}">
                                        <div class="ur-hps-vc-glow"></div>
                                        
                                        {{-- Architectural SVG House / Shield Illustration --}}
                                        <div class="ur-hps-vc-illustration">
                                            <svg viewBox="0 0 160 110" fill="none" class="ur-hps-house-svg">
                                                <defs>
                                                    <linearGradient id="roofGrad_{{ $planUid }}" x1="20" y1="20" x2="140" y2="70" gradientUnits="userSpaceOnUse">
                                                        @if($s['theme'] === 'gold')
                                                            <stop offset="0%" stop-color="#F59E0B"/>
                                                            <stop offset="100%" stop-color="#D97706"/>
                                                        @elseif($s['theme'] === 'platinum')
                                                            <stop offset="0%" stop-color="#60A5FA"/>
                                                            <stop offset="100%" stop-color="#2563EB"/>
                                                        @else
                                                            <stop offset="0%" stop-color="#94A3B8"/>
                                                            <stop offset="100%" stop-color="#475569"/>
                                                        @endif
                                                    </linearGradient>
                                                    <linearGradient id="wallGrad_{{ $planUid }}" x1="30" y1="50" x2="130" y2="105" gradientUnits="userSpaceOnUse">
                                                        <stop offset="0%" stop-color="#FFFFFF"/>
                                                        <stop offset="100%" stop-color="#F8FAFC"/>
                                                    </linearGradient>
                                                </defs>
                                                {{-- Ground Shadow --}}
                                                <ellipse cx="80" cy="100" rx="65" ry="6" fill="#E2E8F0" fill-opacity="0.6"/>
                                                {{-- House Body --}}
                                                <path d="M32 52L80 18L128 52V98C128 100 126.5 101.5 124.5 101.5H35.5C33.5 101.5 32 100 32 98V52Z" fill="url(#wallGrad_{{ $planUid }})" stroke="#CBD5E1" stroke-width="1.4"/>
                                                {{-- Roof Eaves --}}
                                                <path d="M24 55L80 15L136 55" stroke="url(#roofGrad_{{ $planUid }})" stroke-width="4.2" stroke-linecap="round" stroke-linejoin="round"/>
                                                {{-- Modern Large Glass Window with Reflection --}}
                                                <rect x="44" y="58" width="28" height="22" rx="2.5" fill="#EFF6FF" stroke="#93C5FD" stroke-width="1.2"/>
                                                <line x1="58" y1="58" x2="58" y2="80" stroke="#BFDBFE" stroke-width="1.2"/>
                                                <line x1="44" y1="69" x2="72" y2="69" stroke="#BFDBFE" stroke-width="1.2"/>
                                                {{-- Front Door --}}
                                                <rect x="86" y="56" width="26" height="45" rx="2" fill="#1E293B"/>
                                                <circle cx="106" cy="80" r="1.8" fill="#F59E0B"/>
                                                {{-- Floating Verified Badge --}}
                                                <g transform="translate(104, 10)">
                                                    <circle cx="18" cy="18" r="16" fill="#FFFFFF" filter="drop-shadow(0 2px 5px rgba(15,23,42,0.12))"/>
                                                    <circle cx="18" cy="18" r="13.5" fill="#10B981"/>
                                                    <path d="M13.5 18L16.5 21L23 14.5" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </g>
                                            </svg>
                                        </div>

                                        {{-- Secondary Trust Content --}}
                                        <div class="ur-hps-vc-content">
                                            <div class="ur-hps-vc-badge">
                                                <i class="ph-fill ph-seal-check"></i>
                                                <span>{{ $s['visual']['tag'] }}</span>
                                            </div>

                                            <h5 class="ur-hps-vc-title">{{ $s['visual']['title'] }}</h5>
                                            <p class="ur-hps-vc-sub">{{ $s['visual']['sub'] }}</p>

                                            <div class="ur-hps-vc-list">
                                                @foreach($s['visual']['perks'] as $perk)
                                                    <div class="ur-hps-vc-row">
                                                        <i class="ph-bold ph-check"></i>
                                                        <span>{{ $perk }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{-- PART 4: HORIZONTAL TRUST ROW (Spans full width underneath) --}}
                            <div class="ur-hps-trust-bar">
                                <div class="ur-hps-tb-item">
                                    <i class="ph-fill ph-lightning"></i>
                                    <span>Instant Activation (30 Sec)</span>
                                </div>
                                <div class="ur-hps-tb-divider"></div>
                                <div class="ur-hps-tb-item">
                                    <i class="ph-fill ph-shield-check"></i>
                                    <span>100% Verified Owners Only</span>
                                </div>
                                <div class="ur-hps-tb-divider"></div>
                                <div class="ur-hps-tb-item">
                                    <i class="ph-fill ph-lock-key"></i>
                                    <span>Safe UPI / Card Checkout</span>
                                </div>
                                <div class="ur-hps-tb-divider"></div>
                                <div class="ur-hps-tb-item">
                                    <i class="ph-fill ph-seal-check"></i>
                                    <span>₹0 Commission Guarantee</span>
                                </div>
                            </div>

                        </div>{{-- End .ur-hps-desktop-grid --}}
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Bottom Indicator Dots and Hint --}}
        <div class="ur-hps-bottom-bar">
            <div class="ur-hps-dots" id="heroPlanDots">
                @foreach($heroSlides as $idx => $s)
                    <button type="button" 
                            class="ur-hps-dot {{ $idx === 0 ? 'active' : '' }}" 
                            data-index="{{ $idx }}" 
                            aria-label="Slide {{ $idx + 1 }}" 
                            title="{{ $s['tab_label'] }}"></button>
                @endforeach
            </div>

            <div class="ur-hps-swipe-hint">
                <i class="ph-bold ph-arrows-left-right"></i>
                <span>Swipe or click tabs to explore passes</span>
            </div>
        </div>
    </div>
</section>

<script>
(function() {
    function initHeroPlanSlider() {
        const stage = document.getElementById('heroPlanSliderStage');
        const track = document.getElementById('heroPlanTrack');
        const prevBtn = document.getElementById('heroPlanPrevBtn');
        const nextBtn = document.getElementById('heroPlanNextBtn');
        const dots = document.querySelectorAll('.ur-hps-dot');
        const tabs = document.querySelectorAll('.ur-hps-tab-btn');
        const orb1 = document.getElementById('hpsOrb1');
        const orb2 = document.getElementById('hpsOrb2');

        if (!stage || !track) return;

        const slides = track.querySelectorAll('.ur-hps-slide');
        const totalSlides = slides.length;
        if (totalSlides <= 1) return;

        let currentIndex = 0;
        let autoSlideTimer = null;
        let isUserInteracting = false;
        let resumeTimer = null;

        // Theme colors for background glow orbs (Soft Light Pastels)
        const themeGlows = {
            gold: { 
                orb1: 'radial-gradient(circle, rgba(245, 158, 11, 0.12) 0%, rgba(251, 191, 36, 0.03) 50%, transparent 80%)', 
                orb2: 'radial-gradient(circle, rgba(251, 191, 36, 0.08) 0%, rgba(245, 158, 11, 0.02) 50%, transparent 80%)' 
            },
            platinum: { 
                orb1: 'radial-gradient(circle, rgba(37, 99, 235, 0.12) 0%, rgba(147, 197, 253, 0.03) 50%, transparent 80%)', 
                orb2: 'radial-gradient(circle, rgba(59, 130, 246, 0.08) 0%, rgba(37, 99, 235, 0.02) 50%, transparent 80%)' 
            },
            silver: { 
                orb1: 'radial-gradient(circle, rgba(148, 163, 184, 0.12) 0%, rgba(203, 213, 225, 0.03) 50%, transparent 80%)', 
                orb2: 'radial-gradient(circle, rgba(100, 116, 139, 0.08) 0%, transparent 80%)' 
            },
            buyer: { 
                orb1: 'radial-gradient(circle, rgba(16, 185, 129, 0.12) 0%, rgba(110, 231, 183, 0.03) 50%, transparent 80%)', 
                orb2: 'radial-gradient(circle, rgba(5, 150, 105, 0.08) 0%, transparent 80%)' 
            }
        };

        function goToSlide(index) {
            if (index < 0) index = totalSlides - 1;
            if (index >= totalSlides) index = 0;
            currentIndex = index;

            // Move track
            track.style.transform = `translateX(-${currentIndex * 100}%)`;

            // Update dots
            const curSlide = slides[currentIndex];
            const activeTheme = curSlide ? (curSlide.getAttribute('data-theme') || 'gold') : 'gold';
            dots.forEach((dot, i) => {
                if (i === currentIndex) {
                    dot.classList.add('active');
                    dot.setAttribute('aria-current', 'true');
                    if (activeTheme === 'buyer') {
                        dot.style.background = '#059669';
                        dot.style.boxShadow = '0 2px 8px rgba(5, 150, 105, 0.4)';
                    } else if (activeTheme === 'platinum') {
                        dot.style.background = '#2563eb';
                        dot.style.boxShadow = '0 2px 8px rgba(37, 99, 235, 0.4)';
                    } else if (activeTheme === 'silver') {
                        dot.style.background = '#475569';
                        dot.style.boxShadow = '0 2px 8px rgba(71, 85, 105, 0.35)';
                    } else {
                        dot.style.background = '#f59e0b';
                        dot.style.boxShadow = '0 2px 8px rgba(245, 158, 11, 0.4)';
                    }
                } else {
                    dot.classList.remove('active');
                    dot.removeAttribute('aria-current');
                    dot.style.background = '#cbd5e1';
                    dot.style.boxShadow = 'none';
                }
            });

            // Update top tabs
            tabs.forEach((tab, i) => {
                const theme = tab.getAttribute('data-theme') || 'gold';
                if (i === currentIndex) {
                    tab.classList.add('active', 'tab--' + theme);
                    tab.setAttribute('aria-selected', 'true');
                } else {
                    tab.classList.remove('active', 'tab--' + theme);
                    tab.setAttribute('aria-selected', 'false');
                }
            });

            // Adjust ambient glow orbs to match current slide's theme
            const curSlide = slides[currentIndex];
            if (curSlide) {
                const theme = curSlide.getAttribute('data-theme') || 'gold';
                if (themeGlows[theme]) {
                    if (orb1) orb1.style.background = themeGlows[theme].orb1;
                    if (orb2) orb2.style.background = themeGlows[theme].orb2;
                }
            }
        }

        function nextSlide() {
            goToSlide(currentIndex + 1);
        }

        function prevSlide() {
            goToSlide(currentIndex - 1);
        }

        function startAutoPlay() {
            stopAutoPlay();
            autoSlideTimer = setInterval(() => {
                if (!isUserInteracting) {
                    nextSlide();
                }
            }, 5500);
        }

        function stopAutoPlay() {
            if (autoSlideTimer) {
                clearInterval(autoSlideTimer);
                autoSlideTimer = null;
            }
        }

        function pauseAndResume() {
            isUserInteracting = true;
            stopAutoPlay();
            if (resumeTimer) clearTimeout(resumeTimer);
            resumeTimer = setTimeout(() => {
                isUserInteracting = false;
                startAutoPlay();
            }, 7000);
        }

        // Arrow Listeners
        prevBtn?.addEventListener('click', (e) => {
            e.preventDefault();
            pauseAndResume();
            prevSlide();
        });

        nextBtn?.addEventListener('click', (e) => {
            e.preventDefault();
            pauseAndResume();
            nextSlide();
        });

        // Tab Listeners
        tabs.forEach((tab) => {
            tab.addEventListener('click', (e) => {
                e.preventDefault();
                pauseAndResume();
                const targetIdx = parseInt(tab.getAttribute('data-target-index') || '0', 10);
                goToSlide(targetIdx);
            });
        });

        // Dot Listeners
        dots.forEach((dot) => {
            dot.addEventListener('click', (e) => {
                e.preventDefault();
                pauseAndResume();
                const targetIdx = parseInt(dot.getAttribute('data-index') || '0', 10);
                goToSlide(targetIdx);
            });
        });

        // Pause on Hover
        stage.addEventListener('mouseenter', () => {
            stopAutoPlay();
        });

        stage.addEventListener('mouseleave', () => {
            if (!isUserInteracting) {
                startAutoPlay();
            }
        });

        // Touch & Swipe Support for Mobile & Tablets
        let touchStartX = 0;
        let touchEndX = 0;
        let touchStartY = 0;
        let touchEndY = 0;

        stage.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
            touchStartY = e.changedTouches[0].screenY;
            pauseAndResume();
        }, { passive: true });

        stage.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            touchEndY = e.changedTouches[0].screenY;
            handleSwipe();
            pauseAndResume();
        }, { passive: true });

        function handleSwipe() {
            const diffX = touchEndX - touchStartX;
            const diffY = touchEndY - touchStartY;
            if (Math.abs(diffX) > 40 && Math.abs(diffX) > Math.abs(diffY)) {
                if (diffX < 0) {
                    nextSlide();
                } else {
                    prevSlide();
                }
            }
        }

        // Intersection observer: only run autoplay when in viewport
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        startAutoPlay();
                    } else {
                        stopAutoPlay();
                    }
                });
            }, { threshold: 0.15 });
            observer.observe(stage);
        } else {
            startAutoPlay();
        }

        // Initialize state (supports ?tab=buyer, ?type=buy, or #buyer-pass to show buyer slide directly)
        let initialIndex = 0;
        try {
            const urlParams = new URLSearchParams(window.location.search);
            const hash = window.location.hash.toLowerCase();
            if (urlParams.get('tab') === 'buyer' || urlParams.get('plan') === 'buyer' || urlParams.get('type') === 'buy' || hash === '#buyer-pass' || hash === '#buyer' || hash === '#direct-buyer-pass') {
                const buyerIdx = Array.from(slides).findIndex(s => s.getAttribute('data-theme') === 'buyer');
                if (buyerIdx >= 0) {
                    initialIndex = buyerIdx;
                }
            }
        } catch (e) {}

        goToSlide(initialIndex);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initHeroPlanSlider);
    } else {
        initHeroPlanSlider();
    }
})();
</script>
