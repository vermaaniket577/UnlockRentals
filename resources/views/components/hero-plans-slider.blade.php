{{-- ============================================================
     UNLOCK RENTALS — HERO PLANS SLIDER (TOP NEW DESIGN)
     Displayed directly below the Hero Section in Hero-scale slider format.
     Does NOT remove or replace the bottom pricing plans section.
     ============================================================ --}}

@php
    try {
        \App\Models\Plan::ensureBuyerPlansExist();

        $allRentPlans = \Illuminate\Support\Facades\Cache::remember('active_rent_plans_v1', 3600, function () {
            return \App\Models\Plan::active()
                ->where('is_private', false)
                ->whereIn('purpose', ['rent', 'both', null])
                ->orderBy('sort_order')
                ->get();
        });

        $allBuyPlans = \Illuminate\Support\Facades\Cache::remember('active_buy_plans_v1', 3600, function () {
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

    // High quality fallback plans if database table is empty or offline
    if ($allRentPlans->isEmpty()) {
        $allRentPlans = collect([
            (object)[
                'id' => 2,
                'name' => 'Gold Rental Pass',
                'description' => 'Most popular pass for active rental seekers with extended validity and priority owner unlocks.',
                'price' => 499.00,
                'duration_days' => 90,
                'contact_limit' => 60,
                'features' => [
                    '60 Verified Owner Direct Contacts',
                    'Instant WhatsApp & Phone Call Unlock',
                    'Zero Brokerage Guaranteed (Save ₹25,000+)',
                    '90 Days Extended Validity',
                    'Direct Visit Scheduling Pass',
                    'Priority WhatsApp Customer Assistance'
                ],
                'image_path' => null,
            ],
            (object)[
                'id' => 3,
                'name' => 'Platinum VIP Pass',
                'description' => 'Ultimate VIP pass with maximum direct owner unlocks, concierge support, and lease assistance.',
                'price' => 999.00,
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
                'price' => 199.00,
                'duration_days' => 30,
                'contact_limit' => 25,
                'features' => [
                    '25 Verified Owner Direct Contacts',
                    'Instant WhatsApp & Phone Call Unlock',
                    'Zero Brokerage Guaranteed',
                    '30 Days Validity',
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
                'name' => 'Gold Buyer Pass',
                'description' => 'Most popular pass for active property buyers looking for direct seller deals without paying 1-2% brokerage.',
                'price' => 999.00,
                'duration_days' => 150,
                'contact_limit' => 75,
                'features' => [
                    '75 Verified Seller Direct Contacts',
                    'Direct WhatsApp & Phone Unlock',
                    'Zero Brokerage Guaranteed (Save ₹1,00,000+)',
                    '150 Days Priority Buyer Access',
                    'Direct Property Document Assistance'
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
            'original_price' => round((float) ($goldRent->price ?? 499) * 2.8),
            'price' => (float) ($goldRent->price ?? 499),
            'savings_pct' => 65,
            'per_day' => round(((float) ($goldRent->price ?? 499)) / max(1, (int) ($goldRent->duration_days ?? 90)), 1),
            'highlights' => [
                ['icon' => 'ph-phone-call', 'title' => ($goldRent->contact_limit ?? 60) . ' Verified Owner Contacts', 'desc' => 'Direct phone & WhatsApp numbers'],
                ['icon' => 'ph-shield-check', 'title' => 'Zero Brokerage Guaranteed', 'desc' => 'Save ₹15,000 to ₹35,000 in fees'],
                ['icon' => 'ph-calendar-check', 'title' => ($goldRent->duration_days ?? 90) . ' Days Active Validity', 'desc' => 'Full 3 months of unlimited unlocks'],
                ['icon' => 'ph-chat-circle-dots', 'title' => 'Instant WhatsApp Connect', 'desc' => 'Direct landlord chat & visit pass'],
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
            'original_price' => round((float) ($platRent->price ?? 999) * 3),
            'price' => (float) ($platRent->price ?? 999),
            'savings_pct' => 67,
            'per_day' => round(((float) ($platRent->price ?? 999)) / max(1, (int) ($platRent->duration_days ?? 180)), 1),
            'highlights' => [
                ['icon' => 'ph-lightning', 'title' => ($platRent->contact_limit ?? 150) . ' Verified Owner Contacts', 'desc' => 'Maximum direct contact credits'],
                ['icon' => 'ph-shield-check', 'title' => 'Zero Brokerage Guaranteed', 'desc' => 'Save ₹30,000 to ₹60,000 in fees'],
                ['icon' => 'ph-calendar-check', 'title' => ($platRent->duration_days ?? 180) . ' Days Active Validity', 'desc' => 'Full 6 months priority access'],
                ['icon' => 'ph-user-check', 'title' => 'Dedicated Concierge', 'desc' => 'Personal relationship support on WhatsApp'],
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
            'original_price' => round((float) ($silverRent->price ?? 199) * 2.5),
            'price' => (float) ($silverRent->price ?? 199),
            'savings_pct' => 60,
            'per_day' => round(((float) ($silverRent->price ?? 199)) / max(1, (int) ($silverRent->duration_days ?? 30)), 1),
            'highlights' => [
                ['icon' => 'ph-phone-call', 'title' => ($silverRent->contact_limit ?? 25) . ' Verified Owner Contacts', 'desc' => 'Direct phone numbers & WhatsApp'],
                ['icon' => 'ph-shield-check', 'title' => 'Zero Brokerage Guaranteed', 'desc' => 'No commission on lease closing'],
                ['icon' => 'ph-calendar-check', 'title' => ($silverRent->duration_days ?? 30) . ' Days Active Validity', 'desc' => 'Standard monthly house hunt'],
                ['icon' => 'ph-headset', 'title' => 'Standard Support', 'desc' => 'WhatsApp & email query assistance'],
            ],
        ],
    ]);

    if ($buyerPlan) {
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
            'original_price' => round((float) ($buyerPlan->price ?? 999) * 3),
            'price' => (float) ($buyerPlan->price ?? 999),
            'savings_pct' => 67,
            'per_day' => round(((float) ($buyerPlan->price ?? 999)) / max(1, (int) ($buyerPlan->duration_days ?? 150)), 1),
            'highlights' => [
                ['icon' => 'ph-house-line', 'title' => ($buyerPlan->contact_limit ?? 75) . ' Verified Direct Sellers', 'desc' => 'Direct owners & builder representatives'],
                ['icon' => 'ph-money', 'title' => 'Save ₹1 Lakh to ₹5 Lakhs', 'desc' => 'Zero broker commission on buy transactions'],
                ['icon' => 'ph-calendar-check', 'title' => ($buyerPlan->duration_days ?? 150) . ' Days Buyer Validity', 'desc' => 'Extended window to evaluate deals'],
                ['icon' => 'ph-file-text', 'title' => 'Title & Visit Assistance', 'desc' => 'Direct owner negotiation pass'],
            ],
        ]);
    }
@endphp

<style>
/* ============================================================
   HERO PLANS SLIDER (TOP NEW DESIGN)
   Full-width hero scale presence directly below the Hero Section
   ============================================================ */
.ur-hero-plans-slider-section {
    position: relative;
    width: 100%;
    background: #030712;
    background: radial-gradient(ellipse at 50% 0%, #0c1b3d 0%, #030712 65%, #02040a 100%);
    color: #ffffff;
    padding: 3.5rem 0 4rem;
    overflow: hidden;
    font-family: 'Outfit', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    box-shadow: inset 0 20px 40px rgba(0, 0, 0, 0.5), 0 20px 40px rgba(0, 0, 0, 0.3);
}

/* Ambient glow orbs in background */
.ur-hps-ambient-orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(120px);
    pointer-events: none;
    z-index: 1;
    opacity: 0.28;
    transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
}
.ur-hps-ambient-orb--1 {
    width: 550px;
    height: 550px;
    background: radial-gradient(circle, #2563eb 0%, #1e40af 50%, transparent 80%);
    top: -150px;
    left: -100px;
}
.ur-hps-ambient-orb--2 {
    width: 480px;
    height: 480px;
    background: radial-gradient(circle, #f59e0b 0%, #b45309 50%, transparent 80%);
    bottom: -150px;
    right: -80px;
    opacity: 0.22;
}
.ur-hps-ambient-orb--3 {
    width: 380px;
    height: 380px;
    background: radial-gradient(circle, #7c3aed 0%, #4338ca 60%, transparent 80%);
    top: 30%;
    left: 45%;
    opacity: 0.14;
}

/* Subtle architectural grid pattern */
.ur-hps-grid-pattern {
    position: absolute;
    inset: 0;
    background-image: 
        linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
    background-size: 50px 50px;
    background-position: center center;
    pointer-events: none;
    z-index: 1;
    mask-image: radial-gradient(ellipse at center, rgba(0, 0, 0, 0.8) 0%, transparent 85%);
    -webkit-mask-image: radial-gradient(ellipse at center, rgba(0, 0, 0, 0.8) 0%, transparent 85%);
}

.ur-hps-container {
    position: relative;
    z-index: 10;
    width: 100%;
    max-width: 1360px;
    margin: 0 auto;
    padding: 0 1.5rem;
    box-sizing: border-box;
}

/* Section Header */
.ur-hps-header {
    text-align: center;
    max-width: 820px;
    margin: 0 auto 2.25rem;
}

.ur-hps-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.76rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.18em;
    color: #93c5fd;
    background: rgba(37, 99, 235, 0.16);
    border: 1px solid rgba(147, 197, 253, 0.25);
    padding: 0.4rem 1.1rem;
    border-radius: 9999px;
    margin-bottom: 0.85rem;
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    box-shadow: 0 4px 16px rgba(37, 99, 235, 0.2);
}

.ur-hps-eyebrow i {
    color: #fbbf24;
    font-size: 0.95rem;
}

.ur-hps-title {
    font-size: clamp(1.75rem, 3.4vw, 2.75rem);
    font-weight: 900;
    color: #ffffff;
    letter-spacing: -0.03em;
    line-height: 1.18;
    margin: 0 0 0.75rem;
}

.ur-hps-title span.grad-gold {
    background: linear-gradient(135deg, #fef08a 0%, #f59e0b 50%, #f97316 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.ur-hps-title span.grad-blue {
    background: linear-gradient(135deg, #93c5fd 0%, #60a5fa 50%, #3b82f6 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.ur-hps-subtitle {
    font-size: clamp(0.88rem, 1.3vw, 1.05rem);
    color: #94a3b8;
    line-height: 1.55;
    margin: 0 auto;
    max-width: 680px;
}

/* Quick Jump Plan Tabs Nav Bar */
.ur-hps-tabs-bar {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.65rem;
    flex-wrap: wrap;
    margin: 0 auto 2.25rem;
    padding: 0.4rem;
    background: rgba(15, 23, 42, 0.7);
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 9999px;
    max-width: max-content;
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
}

.ur-hps-tab-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    padding: 0.6rem 1.15rem;
    border-radius: 9999px;
    border: 1px solid transparent;
    background: transparent;
    color: #94a3b8;
    font-family: inherit;
    font-size: 0.85rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    white-space: nowrap;
}

.ur-hps-tab-btn:hover {
    color: #ffffff;
    background: rgba(255, 255, 255, 0.06);
}

.ur-hps-tab-btn.active {
    background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
    color: #ffffff;
    border-color: rgba(147, 197, 253, 0.4);
    box-shadow: 0 4px 18px rgba(37, 99, 235, 0.45);
}

.ur-hps-tab-btn.active.tab--gold {
    background: linear-gradient(135deg, #b45309 0%, #d97706 50%, #f59e0b 100%);
    border-color: rgba(254, 240, 138, 0.5);
    box-shadow: 0 4px 18px rgba(245, 158, 11, 0.4);
    color: #ffffff;
}

.ur-hps-tab-btn.active.tab--platinum {
    background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 50%, #0284c7 100%);
    border-color: rgba(186, 230, 253, 0.5);
    box-shadow: 0 4px 18px rgba(37, 99, 235, 0.45);
}

.ur-hps-tab-btn.active.tab--buyer {
    background: linear-gradient(135deg, #065f46 0%, #059669 50%, #10b981 100%);
    border-color: rgba(167, 243, 208, 0.5);
    box-shadow: 0 4px 18px rgba(16, 185, 129, 0.4);
}

.ur-hps-tab-pill {
    font-size: 0.65rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 0.15rem 0.5rem;
    border-radius: 9999px;
    background: rgba(255, 255, 255, 0.16);
    color: #ffffff;
}

/* ─── SLIDER STAGE WRAPPER ───────────────────────────────── */
.ur-hps-slider-stage {
    position: relative;
    width: 100%;
    min-height: 520px;
    background: rgba(15, 23, 42, 0.65);
    border: 1.5px solid rgba(255, 255, 255, 0.12);
    border-radius: 2rem;
    box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.6), inset 0 1px 0 rgba(255, 255, 255, 0.15);
    overflow: hidden;
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
}

/* Slide Track */
.ur-hps-track {
    display: flex;
    width: 100%;
    transition: transform 0.65s cubic-bezier(0.2, 0.9, 0.3, 1);
    will-change: transform;
}

/* Individual Slide (Hero Size) */
.ur-hps-slide {
    flex: 0 0 100%;
    width: 100%;
    min-height: 520px;
    padding: 3rem 3.5rem;
    box-sizing: border-box;
    display: grid;
    grid-template-columns: 1.35fr 1fr;
    gap: 3rem;
    align-items: center;
    position: relative;
    overflow: hidden;
}

/* Slide dynamic backdrops */
.ur-hps-slide--gold {
    background: radial-gradient(ellipse at 85% 50%, rgba(245, 158, 11, 0.15) 0%, transparent 65%),
                linear-gradient(135deg, rgba(30, 27, 75, 0.4) 0%, rgba(15, 23, 42, 0.6) 100%);
}
.ur-hps-slide--platinum {
    background: radial-gradient(ellipse at 85% 50%, rgba(59, 130, 246, 0.18) 0%, transparent 65%),
                linear-gradient(135deg, rgba(15, 23, 42, 0.6) 0%, rgba(30, 58, 138, 0.3) 100%);
}
.ur-hps-slide--silver {
    background: radial-gradient(ellipse at 85% 50%, rgba(148, 163, 184, 0.12) 0%, transparent 65%),
                linear-gradient(135deg, rgba(15, 23, 42, 0.6) 0%, rgba(30, 41, 59, 0.4) 100%);
}
.ur-hps-slide--buyer {
    background: radial-gradient(ellipse at 85% 50%, rgba(16, 185, 129, 0.16) 0%, transparent 65%),
                linear-gradient(135deg, rgba(6, 78, 59, 0.3) 0%, rgba(15, 23, 42, 0.6) 100%);
}

/* ─── LEFT COLUMN: VALUE PROPOSITION ────────────────────── */
.ur-hps-slide-content {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    z-index: 5;
}

.ur-hps-plan-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    font-size: 0.72rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.12em;
    padding: 0.35rem 0.9rem;
    border-radius: 9999px;
    margin-bottom: 1.15rem;
}

.ur-hps-plan-badge--gold {
    background: rgba(245, 158, 11, 0.15);
    color: #fbbf24;
    border: 1px solid rgba(251, 191, 36, 0.35);
    box-shadow: 0 4px 14px rgba(245, 158, 11, 0.2);
}
.ur-hps-plan-badge--platinum {
    background: rgba(59, 130, 246, 0.15);
    color: #93c5fd;
    border: 1px solid rgba(147, 197, 253, 0.35);
    box-shadow: 0 4px 14px rgba(37, 99, 235, 0.2);
}
.ur-hps-plan-badge--silver {
    background: rgba(148, 163, 184, 0.15);
    color: #cbd5e1;
    border: 1px solid rgba(203, 213, 225, 0.3);
}
.ur-hps-plan-badge--buyer {
    background: rgba(16, 185, 129, 0.15);
    color: #6ee7b7;
    border: 1px solid rgba(110, 231, 183, 0.35);
    box-shadow: 0 4px 14px rgba(16, 185, 129, 0.2);
}

.ur-hps-slide-title {
    font-size: clamp(1.85rem, 2.8vw, 2.6rem);
    font-weight: 900;
    color: #ffffff;
    line-height: 1.18;
    letter-spacing: -0.025em;
    margin: 0 0 0.85rem;
}

.ur-hps-slide-title .highlight-gold {
    background: linear-gradient(135deg, #fffbeb 0%, #fde047 35%, #f59e0b 80%, #d97706 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    display: inline-block;
}

.ur-hps-slide-title .highlight-platinum {
    background: linear-gradient(135deg, #f0fdfa 0%, #67e8f9 35%, #38bdf8 70%, #2563eb 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    display: inline-block;
}

.ur-hps-slide-title .highlight-silver {
    background: linear-gradient(135deg, #ffffff 0%, #e2e8f0 40%, #94a3b8 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    display: inline-block;
}

.ur-hps-slide-title .highlight-buyer {
    background: linear-gradient(135deg, #ecfdf5 0%, #6ee7b7 40%, #10b981 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    display: inline-block;
}

.ur-hps-slide-desc {
    font-size: 0.98rem;
    color: #94a3b8;
    line-height: 1.6;
    margin: 0 0 1.75rem;
    max-width: 580px;
}

/* Feature 2x2 Highlights Grid */
.ur-hps-features-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.85rem;
    width: 100%;
    margin-bottom: 2rem;
}

.ur-hps-feature-item {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 0.85rem 1rem;
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 1rem;
    transition: all 0.25s ease;
}

.ur-hps-feature-item:hover {
    background: rgba(255, 255, 255, 0.07);
    border-color: rgba(255, 255, 255, 0.16);
    transform: translateY(-2px);
}

.ur-hps-f-icon-box {
    width: 2.25rem;
    height: 2.25rem;
    border-radius: 0.75rem;
    background: rgba(37, 99, 235, 0.15);
    color: #60a5fa;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.15rem;
    flex-shrink: 0;
}

.ur-hps-feature-item--gold .ur-hps-f-icon-box {
    background: rgba(245, 158, 11, 0.16);
    color: #fbbf24;
}

.ur-hps-feature-item--platinum .ur-hps-f-icon-box {
    background: rgba(59, 130, 246, 0.18);
    color: #93c5fd;
}

.ur-hps-feature-item--buyer .ur-hps-f-icon-box {
    background: rgba(16, 185, 129, 0.18);
    color: #34d399;
}

.ur-hps-f-text h4 {
    font-size: 0.88rem;
    font-weight: 800;
    color: #f8fafc;
    margin: 0 0 0.15rem;
    letter-spacing: -0.01em;
}

.ur-hps-f-text p {
    font-size: 0.76rem;
    color: #94a3b8;
    margin: 0;
    line-height: 1.35;
}

/* Trust Badges Row */
.ur-hps-trust-row {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    flex-wrap: wrap;
    font-size: 0.8rem;
    font-weight: 700;
    color: #cbd5e1;
}

.ur-hps-trust-item {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
}

.ur-hps-trust-item i {
    color: #10b981;
    font-size: 1rem;
}

/* ─── RIGHT COLUMN: FLOATING 3D MEMBERSHIP PASS ─────────── */
.ur-hps-card-side {
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 5;
}

.ur-hps-pass-card {
    position: relative;
    width: 100%;
    max-width: 420px;
    background: linear-gradient(145deg, rgba(255, 255, 255, 0.12) 0%, rgba(255, 255, 255, 0.03) 100%);
    border: 1.5px solid rgba(255, 255, 255, 0.2);
    border-radius: 1.85rem;
    padding: 2.25rem 2rem;
    box-sizing: border-box;
    backdrop-filter: blur(25px);
    -webkit-backdrop-filter: blur(25px);
    box-shadow: 0 30px 60px rgba(0, 0, 0, 0.5), inset 0 1px 0 rgba(255, 255, 255, 0.3);
    transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.4s ease;
    overflow: hidden;
}

.ur-hps-pass-card:hover {
    transform: translateY(-6px) scale(1.02);
    box-shadow: 0 35px 70px rgba(0, 0, 0, 0.6), 0 0 40px rgba(37, 99, 235, 0.25);
}

.ur-hps-pass-card--gold {
    border-color: rgba(245, 158, 11, 0.45);
    box-shadow: 0 30px 60px rgba(0, 0, 0, 0.5), 0 0 30px rgba(245, 158, 11, 0.15);
}
.ur-hps-pass-card--gold:hover {
    box-shadow: 0 35px 70px rgba(0, 0, 0, 0.6), 0 0 45px rgba(245, 158, 11, 0.3);
}

.ur-hps-pass-card--platinum {
    border-color: rgba(59, 130, 246, 0.45);
    box-shadow: 0 30px 60px rgba(0, 0, 0, 0.5), 0 0 30px rgba(37, 99, 235, 0.2);
}
.ur-hps-pass-card--platinum:hover {
    box-shadow: 0 35px 70px rgba(0, 0, 0, 0.6), 0 0 45px rgba(37, 99, 235, 0.35);
}

.ur-hps-pass-card--buyer {
    border-color: rgba(16, 185, 129, 0.45);
    box-shadow: 0 30px 60px rgba(0, 0, 0, 0.5), 0 0 30px rgba(16, 185, 129, 0.18);
}
.ur-hps-pass-card--buyer:hover {
    box-shadow: 0 35px 70px rgba(0, 0, 0, 0.6), 0 0 45px rgba(16, 185, 129, 0.32);
}

/* Card Holographic Reflection */
.ur-hps-pass-card::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.08) 0%, transparent 60%);
    pointer-events: none;
    transform: rotate(35deg);
}

/* Card Header with Emblem */
.ur-hps-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.5rem;
}

.ur-hps-brand-tag {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.76rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: #cbd5e1;
}

.ur-hps-brand-tag span.ur-accent {
    color: #60a5fa;
}

.ur-hps-card-emblem {
    width: 3.5rem;
    height: 3.5rem;
    border-radius: 1.15rem;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.15);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
    position: relative;
    overflow: hidden;
}

/* Price Box */
.ur-hps-price-box {
    margin-bottom: 1.5rem;
    padding-bottom: 1.25rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.ur-hps-price-top {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    margin-bottom: 0.35rem;
}

.ur-hps-price-original {
    font-size: 1.1rem;
    font-weight: 700;
    color: #94a3b8;
    text-decoration: line-through;
}

.ur-hps-save-chip {
    font-size: 0.72rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 0.2rem 0.6rem;
    border-radius: 9999px;
    background: #10b981;
    color: #ffffff;
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
}

.ur-hps-price-main {
    display: flex;
    align-items: baseline;
    gap: 0.35rem;
    margin-bottom: 0.35rem;
}

.ur-hps-currency {
    font-size: 1.6rem;
    font-weight: 900;
    color: #ffffff;
}

.ur-hps-amount {
    font-size: 3.2rem;
    font-weight: 900;
    color: #ffffff;
    line-height: 1;
    letter-spacing: -0.04em;
}

.ur-hps-period {
    font-size: 0.88rem;
    font-weight: 600;
    color: #94a3b8;
}

.ur-hps-price-subtext {
    font-size: 0.78rem;
    color: #cbd5e1;
    display: flex;
    align-items: center;
    gap: 0.4rem;
}

.ur-hps-price-subtext i {
    color: #10b981;
}

/* Card Meta Pill List */
.ur-hps-card-meta {
    display: flex;
    flex-direction: column;
    gap: 0.6rem;
    margin-bottom: 1.75rem;
}

.ur-hps-meta-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 0.82rem;
    color: #e2e8f0;
}

.ur-hps-meta-row span.meta-label {
    color: #94a3b8;
    display: flex;
    align-items: center;
    gap: 0.35rem;
}

.ur-hps-meta-row span.meta-value {
    font-weight: 800;
    color: #ffffff;
}

/* Primary CTA Button */
.ur-hps-cta-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.65rem;
    width: 100%;
    padding: 1.05rem 1.5rem;
    border-radius: 1.15rem;
    font-family: inherit;
    font-size: 1rem;
    font-weight: 800;
    letter-spacing: -0.01em;
    text-decoration: none;
    cursor: pointer;
    box-sizing: border-box;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
    overflow: hidden;
    margin-bottom: 0.75rem;
}

.ur-hps-cta-btn--gold {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 50%, #b45309 100%);
    color: #0f172a !important;
    font-weight: 900;
    box-shadow: 0 10px 30px rgba(245, 158, 11, 0.4);
    border: none;
}
.ur-hps-cta-btn--gold:hover {
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 50%, #d97706 100%);
    box-shadow: 0 14px 40px rgba(245, 158, 11, 0.55);
    transform: translateY(-2px);
    color: #000000 !important;
}

.ur-hps-cta-btn--platinum {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 50%, #1e40af 100%);
    color: #ffffff !important;
    box-shadow: 0 10px 30px rgba(37, 99, 235, 0.45);
    border: none;
}
.ur-hps-cta-btn--platinum:hover {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 50%, #1d4ed8 100%);
    box-shadow: 0 14px 40px rgba(37, 99, 235, 0.6);
    transform: translateY(-2px);
}

.ur-hps-cta-btn--silver {
    background: linear-gradient(135deg, #334155 0%, #1e293b 100%);
    color: #ffffff !important;
    box-shadow: 0 8px 25px rgba(15, 23, 42, 0.4);
    border: 1px solid rgba(255, 255, 255, 0.2);
}
.ur-hps-cta-btn--silver:hover {
    background: linear-gradient(135deg, #475569 0%, #334155 100%);
    transform: translateY(-2px);
}

.ur-hps-cta-btn--buyer {
    background: linear-gradient(135deg, #10b981 0%, #059669 50%, #047857 100%);
    color: #ffffff !important;
    box-shadow: 0 10px 30px rgba(16, 185, 129, 0.45);
    border: none;
}
.ur-hps-cta-btn--buyer:hover {
    background: linear-gradient(135deg, #34d399 0%, #10b981 50%, #059669 100%);
    box-shadow: 0 14px 40px rgba(16, 185, 129, 0.6);
    transform: translateY(-2px);
}

/* Secondary Link to Compare All Plans at Bottom */
.ur-hps-compare-link {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.35rem;
    font-size: 0.78rem;
    font-weight: 700;
    color: #94a3b8;
    text-decoration: none;
    transition: color 0.2s ease;
    text-align: center;
}

.ur-hps-compare-link:hover {
    color: #ffffff;
}

.ur-hps-compare-link i {
    transition: transform 0.25s ease;
}

.ur-hps-compare-link:hover i {
    transform: translateY(3px);
}

/* ─── SLIDER CONTROLS (ARROWS & PROGRESS) ───────────────── */
.ur-hps-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 3.2rem;
    height: 3.2rem;
    border-radius: 50%;
    background: rgba(15, 23, 42, 0.85);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
    cursor: pointer;
    z-index: 20;
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    transition: all 0.25s ease;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
}

.ur-hps-arrow:hover {
    background: #2563eb;
    border-color: #3b82f6;
    transform: translateY(-50%) scale(1.08);
    box-shadow: 0 10px 30px rgba(37, 99, 235, 0.5);
}

.ur-hps-arrow--prev { left: 1rem; }
.ur-hps-arrow--next { right: 1rem; }

/* Bottom Nav Indicator Bar */
.ur-hps-bottom-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    margin-top: 1.5rem;
    padding: 0 0.5rem;
}

.ur-hps-dots {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.ur-hps-dot {
    width: 0.6rem;
    height: 0.6rem;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.25);
    border: none;
    cursor: pointer;
    padding: 0;
    transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
}

.ur-hps-dot.active {
    width: 2.2rem;
    border-radius: 9999px;
    background: #2563eb;
    box-shadow: 0 2px 10px rgba(37, 99, 235, 0.5);
}

.ur-hps-swipe-hint {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.74rem;
    font-weight: 700;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.06em;
}

.ur-hps-swipe-hint i {
    color: #60a5fa;
    font-size: 0.95rem;
}

/* Desktop View Wrapper */
.ur-hps-desktop-view {
    display: grid;
    grid-template-columns: 1.35fr 1fr;
    gap: 3rem;
    align-items: center;
    width: 100%;
}

/* Mobile 16:9 Card (hidden on desktop) */
.ur-hps-mobile-card {
    display: none;
}

/* ─── RESPONSIVE BEHAVIOR (MOBILE 16:9 SHORT CARD FORMAT) ──────────── */
@media (max-width: 1023px) {
    .ur-hero-plans-slider-section {
        padding: 0.65rem 0 0.85rem;
    }
    .ur-hps-container {
        padding: 0 0.75rem;
    }
    .ur-hps-header {
        margin: 0 auto 0.35rem;
    }
    .ur-hps-eyebrow {
        display: none;
    }
    .ur-hps-title {
        font-size: 1.05rem;
        margin: 0 0 0.15rem;
        line-height: 1.2;
    }
    .ur-hps-subtitle {
        display: none;
    }
    .ur-hps-tabs-bar {
        width: 100%;
        margin-bottom: 0.4rem;
        padding: 0.2rem;
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
        padding: 0.25rem 0.6rem;
        font-size: 0.7rem;
        white-space: nowrap;
        gap: 0.3rem;
    }
    .ur-hps-tab-btn .ur-hps-tab-pill {
        display: none;
    }

    .ur-hps-slider-stage {
        border-radius: 1.25rem;
        background: transparent;
        border: none;
        box-shadow: none;
        min-height: auto !important;
    }
    .ur-hps-arrow {
        display: none;
    }

    .ur-hps-slide {
        display: flex !important;
        align-items: center;
        justify-content: center;
        padding: 0 !important;
        min-height: auto !important;
        background: transparent !important;
        border: none !important;
    }

    /* Hide heavy desktop column content on mobile */
    .ur-hps-desktop-view {
        display: none !important;
    }

    /* Show 16:9 Short Mobile Card (Jain Shaadi Milan App Format) */
    .ur-hps-mobile-card {
        display: flex !important;
        flex-direction: column;
        justify-content: space-between;
        width: 100%;
        max-width: 480px;
        margin: 0 auto;
        aspect-ratio: 16 / 8.5;
        box-sizing: border-box;
        padding: 0.75rem 0.95rem;
        border-radius: 1.15rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 12px 30px -6px rgba(0, 0, 0, 0.75), 0 0 0 1px rgba(255, 255, 255, 0.08);
    }

    /* Card Themes */
    .ur-hps-mobile-card--gold {
        background: radial-gradient(ellipse at top right, rgba(245, 158, 11, 0.3) 0%, transparent 60%),
                    linear-gradient(145deg, #131722 0%, #1c1813 50%, #120f0a 100%);
        border: 1px solid rgba(245, 158, 11, 0.45);
    }
    .ur-hps-mobile-card--platinum {
        background: radial-gradient(ellipse at top right, rgba(59, 130, 246, 0.32) 0%, transparent 60%),
                    linear-gradient(145deg, #0d1527 0%, #101c36 50%, #080d1a 100%);
        border: 1px solid rgba(59, 130, 246, 0.5);
    }
    .ur-hps-mobile-card--silver {
        background: radial-gradient(ellipse at top right, rgba(148, 163, 184, 0.25) 0%, transparent 60%),
                    linear-gradient(145deg, #121620 0%, #1a2230 50%, #0d1117 100%);
        border: 1px solid rgba(148, 163, 184, 0.35);
    }
    .ur-hps-mobile-card--buyer {
        background: radial-gradient(ellipse at top right, rgba(16, 185, 129, 0.3) 0%, transparent 60%),
                    linear-gradient(145deg, #091a18 0%, #0d2621 50%, #061513 100%);
        border: 1px solid rgba(16, 185, 129, 0.45);
    }

    .ur-hps-mob-watermark {
        position: absolute;
        right: -6px;
        top: -6px;
        font-size: 4.75rem;
        opacity: 0.06;
        pointer-events: none;
        line-height: 1;
        z-index: 1;
    }

    /* Top Bar */
    .ur-hps-mob-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
        z-index: 2;
        gap: 0.4rem;
        margin-bottom: 0.1rem;
    }
    .ur-hps-mob-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        font-size: 0.62rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        padding: 0.15rem 0.5rem;
        border-radius: 9999px;
    }
    .ur-hps-mob-badge--gold {
        background: rgba(245, 158, 11, 0.2);
        color: #fde047;
        border: 1px solid rgba(253, 224, 71, 0.35);
    }
    .ur-hps-mob-badge--platinum {
        background: rgba(59, 130, 246, 0.2);
        color: #93c5fd;
        border: 1px solid rgba(147, 197, 253, 0.35);
    }
    .ur-hps-mob-badge--silver {
        background: rgba(148, 163, 184, 0.2);
        color: #e2e8f0;
        border: 1px solid rgba(226, 232, 240, 0.3);
    }
    .ur-hps-mob-badge--buyer {
        background: rgba(16, 185, 129, 0.2);
        color: #a7f3d0;
        border: 1px solid rgba(167, 243, 208, 0.35);
    }

    .ur-hps-mob-save {
        font-size: 0.62rem;
        font-weight: 800;
        color: #34d399;
        background: rgba(16, 185, 129, 0.15);
        border: 1px solid rgba(52, 211, 153, 0.3);
        padding: 0.12rem 0.45rem;
        border-radius: 9999px;
        letter-spacing: 0.03em;
    }

    /* Middle Row */
    .ur-hps-mob-body {
        position: relative;
        z-index: 2;
        margin: 0.1rem 0;
    }
    .ur-hps-mob-title {
        font-size: 1.05rem;
        font-weight: 900;
        line-height: 1.15;
        letter-spacing: -0.02em;
        margin: 0 0 0.15rem;
    }
    .ur-hps-mob-tagline {
        display: none;
    }
    .ur-hps-mob-chips {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        flex-wrap: nowrap;
        overflow: hidden;
    }
    .ur-hps-mob-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.2rem;
        font-size: 0.62rem;
        font-weight: 700;
        color: #cbd5e1;
        background: rgba(255, 255, 255, 0.07);
        border: 1px solid rgba(255, 255, 255, 0.1);
        padding: 0.15rem 0.38rem;
        border-radius: 0.35rem;
        white-space: nowrap;
    }
    .ur-hps-mob-chip i {
        color: #fbbf24;
        font-size: 0.65rem;
    }

    /* Bottom Row: Price & Pay Now Button */
    .ur-hps-mob-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
        z-index: 2;
        gap: 0.5rem;
        padding-top: 0.35rem;
        margin-top: 0.15rem;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
    }
    .ur-hps-mob-price {
        display: flex;
        flex-direction: column;
    }
    .ur-hps-mob-price-row {
        display: flex;
        align-items: baseline;
        gap: 0.25rem;
        line-height: 1;
    }
    .ur-hps-mob-curr {
        font-size: 0.8rem;
        font-weight: 800;
        color: #94a3b8;
    }
    .ur-hps-mob-amount {
        font-size: 1.28rem;
        font-weight: 900;
        color: #ffffff;
        letter-spacing: -0.02em;
    }
    .ur-hps-mob-orig {
        font-size: 0.72rem;
        color: #64748b;
        text-decoration: line-through;
        font-weight: 600;
    }
    .ur-hps-mob-rate {
        font-size: 0.58rem;
        color: #94a3b8;
        font-weight: 600;
        margin-top: 0.1rem;
    }

    /* High-impact Pay Now CTA */
    .ur-hps-mob-pay-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        padding: 0.42rem 1rem;
        border-radius: 9999px;
        font-size: 0.78rem;
        font-weight: 800;
        letter-spacing: 0.02em;
        text-decoration: none;
        cursor: pointer;
        white-space: nowrap;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.4);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .ur-hps-mob-pay-btn:active {
        transform: scale(0.96);
    }
    .ur-hps-mob-pay-btn--gold {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: #000000;
        border: 1px solid #fde047;
        box-shadow: 0 4px 14px rgba(245, 158, 11, 0.45);
    }
    .ur-hps-mob-pay-btn--platinum {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: #ffffff;
        border: 1px solid #93c5fd;
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.45);
    }
    .ur-hps-mob-pay-btn--silver {
        background: linear-gradient(135deg, #475569 0%, #334155 100%);
        color: #ffffff;
        border: 1px solid #cbd5e1;
        box-shadow: 0 4px 12px rgba(71, 85, 105, 0.4);
    }
    .ur-hps-mob-pay-btn--buyer {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #ffffff;
        border: 1px solid #6ee7b7;
        box-shadow: 0 4px 14px rgba(16, 185, 129, 0.45);
    }

    /* Compact Bottom Bar on Mobile */
    .ur-hps-bottom-bar {
        margin-top: 0.4rem;
        justify-content: center;
        padding: 0;
    }
    .ur-hps-dots {
        gap: 0.35rem;
    }
    .ur-hps-dot {
        width: 0.45rem;
        height: 0.45rem;
    }
    .ur-hps-dot.active {
        width: 1.4rem;
    }
    .ur-hps-swipe-hint {
        display: none !important;
    }
}

@media (max-width: 380px) {
    .ur-hps-mobile-card {
        padding: 0.65rem 0.8rem;
        aspect-ratio: 16 / 8.8;
    }
    .ur-hps-mob-title {
        font-size: 0.98rem;
    }
    .ur-hps-mob-amount {
        font-size: 1.18rem;
    }
    .ur-hps-mob-pay-btn {
        padding: 0.38rem 0.85rem;
        font-size: 0.74rem;
    }
}
</style>

<section class="ur-hero-plans-slider-section" id="hero-plans-slider-section">
    {{-- Ambient Light Orbs --}}
    <div class="ur-hps-ambient-orb ur-hps-ambient-orb--1" id="hpsOrb1"></div>
    <div class="ur-hps-ambient-orb ur-hps-ambient-orb--2" id="hpsOrb2"></div>
    <div class="ur-hps-ambient-orb ur-hps-ambient-orb--3"></div>
    <div class="ur-hps-grid-pattern"></div>

    <div class="ur-hps-container">
        {{-- Section Header --}}
        <div class="ur-hps-header">
            <span class="ur-hps-eyebrow">
                <i class="ph-fill ph-shield-check"></i>
                Zero Brokerage Direct Pass
            </span>
            <h2 class="ur-hps-title">
                Unlock Direct Owner Contacts & <span class="grad-gold">Save Brokerage</span>
            </h2>
            <p class="ur-hps-subtitle">
                Connect directly with 100% verified property owners on phone & WhatsApp. Move in faster with complete peace of mind.
            </p>
        </div>

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

        {{-- Grand Hero-Sized Slider Stage --}}
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
                        {{-- Desktop Expanded Layout (Visible only on Desktop/Tablet landscape) --}}
                        <div class="ur-hps-desktop-view">
                            {{-- Left Column: Value Proposition & Details --}}
                            <div class="ur-hps-slide-content">
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

                                {{-- Trust Badges Row --}}
                                <div class="ur-hps-trust-row">
                                    <div class="ur-hps-trust-item">
                                        <i class="ph-fill ph-check-circle"></i>
                                        <span>Instant Activation (30 Sec)</span>
                                    </div>
                                    <div class="ur-hps-trust-item">
                                        <i class="ph-fill ph-shield-check"></i>
                                        <span>100% Verified Owners Only</span>
                                    </div>
                                    <div class="ur-hps-trust-item">
                                        <i class="ph-fill ph-lock-key"></i>
                                        <span>Safe UPI / Card Checkout</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Right Column: Floating 3D Pass Card --}}
                            <div class="ur-hps-card-side">
                                <div class="ur-hps-pass-card ur-hps-pass-card--{{ $s['theme'] }}">
                                    {{-- Card Header & Emblem --}}
                                    <div class="ur-hps-card-header">
                                        <div class="ur-hps-brand-tag">
                                            <i class="ph-bold ph-key"></i>
                                            <span>Unlock<span class="ur-accent">Rentals</span> Pass</span>
                                        </div>

                                        <div class="ur-hps-card-emblem">
                                            @if($s['theme'] === 'gold')
                                                {{-- 3D Gold Imperial Crown --}}
                                                <svg style="width: 2.25rem; height: 2.25rem;" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
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
                                                {{-- Royal Sapphire Diamond --}}
                                                <svg style="width: 2.25rem; height: 2.25rem;" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
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
                                                {{-- Luxury Villa / Penthouse Emblem --}}
                                                <svg style="width: 2.25rem; height: 2.25rem;" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
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
                                                {{-- Metallic Titanium Shield --}}
                                                <svg style="width: 2.25rem; height: 2.25rem;" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
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

                                    {{-- Price Box --}}
                                    <div class="ur-hps-price-box">
                                        <div class="ur-hps-price-top">
                                            <span class="ur-hps-price-original">₹{{ number_format($s['original_price'], 0) }}</span>
                                            <span class="ur-hps-save-chip">Save {{ $s['savings_pct'] }}%</span>
                                        </div>

                                        <div class="ur-hps-price-main">
                                            <span class="ur-hps-currency">₹</span>
                                            <span class="ur-hps-amount">{{ number_format($s['price'], 0) }}</span>
                                            <span class="ur-hps-period">/ {{ $s['type'] === 'buy' ? 'annual pass' : 'pass' }}</span>
                                        </div>

                                        <div class="ur-hps-price-subtext">
                                            <i class="ph-bold ph-seal-check"></i>
                                            <span>Only ₹{{ $s['per_day'] }}/day · {{ $plan->duration_days ?? 90 }} Days Validity</span>
                                        </div>
                                    </div>

                                    {{-- Card Meta Rows --}}
                                    <div class="ur-hps-card-meta">
                                        <div class="ur-hps-meta-row">
                                            <span class="meta-label"><i class="ph-bold ph-lock-key-open"></i> Contact Credits:</span>
                                            <span class="meta-value">{{ $plan->contact_limit ?? 60 }} Direct Unlocks</span>
                                        </div>
                                        <div class="ur-hps-meta-row">
                                            <span class="meta-label"><i class="ph-bold ph-clock"></i> Access Duration:</span>
                                            <span class="meta-value">{{ $plan->duration_days ?? 90 }} Full Days</span>
                                        </div>
                                        <div class="ur-hps-meta-row">
                                            <span class="meta-label"><i class="ph-bold ph-shield-star"></i> Brokerage:</span>
                                            <span class="meta-value" style="color: #34d399;">₹0 (Zero Commission)</span>
                                        </div>
                                    </div>

                                    {{-- Primary Checkout CTA Button --}}
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

                                    {{-- Compare All Plans Link (Smooth scrolls to bottom plan cards) --}}
                                    <a href="#pricing-plans" class="ur-hps-compare-link" onclick="if(document.getElementById('pricing-plans')){ document.getElementById('pricing-plans').scrollIntoView({behavior:'smooth'}); return false; }">
                                        <span>Compare All Plan Details</span>
                                        <i class="ph-bold ph-arrow-down"></i>
                                    </a>
                                </div>
                            </div>
                        </div>{{-- End .ur-hps-desktop-view --}}

                        {{-- =======================================================
                             MOBILE 16:9 SHORT CARD (JAIN SHAADI MILAN APP FORMAT)
                             Displays Plan Name, Key Highlights, Price, and Pay Now Button
                             Strict 16:9 Aspect Ratio with Zero Vertical Scrolling
                             ======================================================= --}}
                        <div class="ur-hps-mobile-card ur-hps-mobile-card--{{ $s['theme'] }}">
                            {{-- Watermark Background Icon --}}
                            <div class="ur-hps-mob-watermark">
                                <i class="ph-fill {{ $s['tab_icon'] }}"></i>
                            </div>

                            {{-- 1. Top Bar: Badge + Save Tag --}}
                            <div class="ur-hps-mob-top">
                                <span class="ur-hps-mob-badge ur-hps-mob-badge--{{ $s['theme'] }}">
                                    <i class="ph-fill {{ $s['tab_icon'] }}"></i>
                                    <span>{{ $s['tab_badge'] ?? 'Featured Pass' }}</span>
                                </span>
                                <span class="ur-hps-mob-save">
                                    Save {{ $s['savings_pct'] }}%
                                </span>
                            </div>

                            {{-- 2. Middle: Plan Name & Short Feature Chips --}}
                            <div class="ur-hps-mob-body">
                                <h3 class="ur-hps-mob-title highlight-{{ $s['theme'] }}">
                                    {{ $s['title_highlight'] }}
                                </h3>
                                <p class="ur-hps-mob-tagline">
                                    {{ Str::limit($s['tagline'], 65) }}
                                </p>
                                <div class="ur-hps-mob-chips">
                                    <span class="ur-hps-mob-chip">
                                        <i class="ph-bold ph-phone-call"></i> {{ $plan->contact_limit ?? 60 }} Contacts
                                    </span>
                                    <span class="ur-hps-mob-chip">
                                        <i class="ph-bold ph-calendar"></i> {{ $plan->duration_days ?? 90 }} Days
                                    </span>
                                    <span class="ur-hps-mob-chip">
                                        <i class="ph-bold ph-shield-check"></i> ₹0 Brokerage
                                    </span>
                                </div>
                            </div>

                            {{-- 3. Bottom: Price (Left) + Pay Now Button (Right) --}}
                            <div class="ur-hps-mob-footer">
                                <div class="ur-hps-mob-price">
                                    <div class="ur-hps-mob-price-row">
                                        <span class="ur-hps-mob-curr">₹</span>
                                        <span class="ur-hps-mob-amount">{{ number_format($s['price'], 0) }}</span>
                                        <span class="ur-hps-mob-orig">₹{{ number_format($s['original_price'], 0) }}</span>
                                    </div>
                                    <div class="ur-hps-mob-rate">
                                        ₹{{ $s['per_day'] }}/day · Direct Unlocks
                                    </div>
                                </div>

                                <div class="ur-hps-mob-action">
                                    @guest
                                        <a href="{{ route('login', ['redirect' => $checkoutUrl]) }}" 
                                           onclick="event.preventDefault(); event.stopPropagation(); if(window.openAuthModal) { window.openAuthModal('login', '{{ $checkoutUrl }}'); } else { window.location.href='{{ route('login', ['redirect' => $checkoutUrl]) }}'; }"
                                           class="ur-hps-mob-pay-btn ur-hps-mob-pay-btn--{{ $s['theme'] }}"
                                           title="Pay Now & Unlock Contacts">
                                            <i class="ph-fill ph-lightning"></i>
                                            <span>Pay Now</span>
                                            <i class="ph-bold ph-arrow-right"></i>
                                        </a>
                                    @else
                                        <a href="{{ $checkoutUrl }}" 
                                           class="ur-hps-mob-pay-btn ur-hps-mob-pay-btn--{{ $s['theme'] }}"
                                           title="Pay Now & Unlock Contacts">
                                            <i class="ph-fill ph-lightning"></i>
                                            <span>Pay Now</span>
                                            <i class="ph-bold ph-arrow-right"></i>
                                        </a>
                                    @endguest
                                </div>
                            </div>
                        </div>
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
                <span>Swipe left / right or click tabs to explore passes</span>
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

        // Theme colors for background glow orbs
        const themeGlows = {
            gold: { orb1: 'radial-gradient(circle, #f59e0b 0%, #b45309 50%, transparent 80%)', orb2: 'radial-gradient(circle, #d97706 0%, #78350f 50%, transparent 80%)' },
            platinum: { orb1: 'radial-gradient(circle, #2563eb 0%, #1e40af 50%, transparent 80%)', orb2: 'radial-gradient(circle, #0284c7 0%, #0369a1 50%, transparent 80%)' },
            silver: { orb1: 'radial-gradient(circle, #64748b 0%, #334155 50%, transparent 80%)', orb2: 'radial-gradient(circle, #94a3b8 0%, #475569 50%, transparent 80%)' },
            buyer: { orb1: 'radial-gradient(circle, #10b981 0%, #047857 50%, transparent 80%)', orb2: 'radial-gradient(circle, #059669 0%, #064e3b 50%, transparent 80%)' }
        };

        function goToSlide(index) {
            if (index < 0) index = totalSlides - 1;
            if (index >= totalSlides) index = 0;
            currentIndex = index;

            // Move track
            track.style.transform = `translateX(-${currentIndex * 100}%)`;

            // Update dots
            dots.forEach((dot, i) => {
                if (i === currentIndex) {
                    dot.classList.add('active');
                    dot.setAttribute('aria-current', 'true');
                } else {
                    dot.classList.remove('active');
                    dot.removeAttribute('aria-current');
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
            }, 4800);
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
            }, 6000);
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
            // Only trigger if horizontal swipe is significantly stronger than vertical scroll
            if (Math.abs(diffX) > 45 && Math.abs(diffX) > Math.abs(diffY)) {
                if (diffX < 0) {
                    nextSlide(); // Swiped left -> next
                } else {
                    prevSlide(); // Swiped right -> prev
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

        // Initialize state
        goToSlide(0);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initHeroPlanSlider);
    } else {
        initHeroPlanSlider();
    }
})();
</script>
