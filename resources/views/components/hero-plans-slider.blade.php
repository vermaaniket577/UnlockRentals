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

    // Ensure active user offers are available for this user
    if (!isset($userOffers)) {
        $userOffers = collect();
        if (auth()->check()) {
            try {
                $userOffers = \App\Models\PrivateUserOffer::whereHas('plan')
                    ->where('user_id', auth()->id())
                    ->where('status', 'active')
                    ->where(function ($q) {
                        $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
                    })
                    ->get();
            } catch (\Throwable $e) {
                $userOffers = collect();
            }
        }
    }

    // If an offer is assigned on a private plan, include it in the available plans
    if (isset($userOffers) && $userOffers->isNotEmpty()) {
        foreach ($userOffers as $uo) {
            try {
                if ($uo->plan && in_array($uo->plan->purpose, ['rent', 'both', null]) && !$allRentPlans->contains('id', $uo->plan->id)) {
                    $allRentPlans->push($uo->plan);
                } elseif ($uo->plan && in_array($uo->plan->purpose, ['buy', 'sale']) && !$allBuyPlans->contains('id', $uo->plan->id)) {
                    $allBuyPlans->push($uo->plan);
                }
            } catch (\Throwable $e) {}
        }
    }

    // Helper to find any active user offer assigned to a plan
    $resolvePlanOffer = function ($plan, $billingPeriod = 'monthly') use ($userOffers) {
        if (!$plan || !isset($userOffers) || $userOffers->isEmpty()) {
            return null;
        }

        return $userOffers->first(function ($o) use ($plan, $billingPeriod) {
            $periodMatch = empty($o->billing_period) || $o->billing_period === $billingPeriod;
            if (!$periodMatch) return false;

            if (isset($plan->id) && (string)$o->plan_id === (string)$plan->id) return true;
            if (isset($o->plan) && isset($plan->name) && strtolower($o->plan->name) === strtolower($plan->name)) return true;
            return false;
        }) ?? $userOffers->first(function ($o) use ($plan) {
            if (isset($plan->id) && (string)$o->plan_id === (string)$plan->id) return true;
            if (isset($o->plan) && isset($plan->name) && strtolower($o->plan->name) === strtolower($plan->name)) return true;
            return false;
        });
    };

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

    // Smart image URL resolver that supports local subfolder paths, asset(), and reliable CDN fallback
    $resolveHeroPassImg = function($localPath, $cdnFallback) {
        $base = request()->getBasePath();
        $primary = $base ? ($base . '/' . ltrim($localPath, '/')) : asset($localPath);
        return [
            'primary' => $primary,
            'fallback' => $cdnFallback,
        ];
    };

    $goldImg = $resolveHeroPassImg(
        'images/buyer_pass/gold_apartment_card.webp',
        'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=800&q=80'
    );
    $platImg = $resolveHeroPassImg(
        'images/buyer_pass/platinum_luxury_card.webp',
        'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=800&q=80'
    );
    $silverImg = $resolveHeroPassImg(
        'images/buyer_pass/silver_studio_card.webp',
        'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=800&q=80'
    );
    $buyerImg = $resolveHeroPassImg(
        'images/buyer_pass/buyer_villa_card.webp',
        'https://images.unsplash.com/photo-1613490493576-7fde63acd811?auto=format&fit=crop&w=800&q=80'
    );

    // Pricing & offer calculations for Gold
    $goldOffer = $resolvePlanOffer($goldRent, 'monthly');
    $goldBasePrice = (float) ($goldRent && $goldRent->price > 0 ? $goldRent->price : 999);
    $hasGoldOffer = ($goldOffer && $goldOffer->discounted_price !== null) || ($goldBasePrice <= 100 && $goldBasePrice > 0);
    $goldPrice = ($goldOffer && $goldOffer->discounted_price !== null) 
        ? (float) $goldOffer->discounted_price 
        : (($goldBasePrice <= 100 && $goldBasePrice > 0) ? $goldBasePrice : ($goldRent && $goldRent->price > 499 ? (float)$goldRent->price : 999));
    $goldRegularPrice = ($goldBasePrice <= 100) ? 999.0 : $goldBasePrice;
    $goldOrigPrice = ($hasGoldOffer && $goldRegularPrice > $goldPrice * 1.25) ? $goldRegularPrice : 2797;
    if ($goldOrigPrice <= $goldPrice) {
        $goldOrigPrice = round(max(2499, $goldPrice * 2.5));
    }
    $goldSavingsPct = max(50, min(85, (int) round((1 - ($goldPrice / max(1, $goldOrigPrice))) * 100)));
    $goldDays = max(1, (int) ($goldRent->duration_days ?? 150));
    $goldPerDay = $goldPrice < 5 ? round($goldPrice / $goldDays, 2) : round($goldPrice / $goldDays, 1);

    // Pricing & offer calculations for Platinum
    $platOffer = $resolvePlanOffer($platRent, 'monthly');
    $platBasePrice = (float) ($platRent->price ?? 1999);
    $hasPlatOffer = ($platOffer && $platOffer->discounted_price !== null) || ($platBasePrice <= 100 && $platBasePrice > 0);
    $platPrice = ($platOffer && $platOffer->discounted_price !== null) 
        ? (float) $platOffer->discounted_price 
        : (($platBasePrice <= 100 && $platBasePrice > 0) ? $platBasePrice : $platBasePrice);
    $platRegularPrice = ($platBasePrice <= 100) ? 1999.0 : $platBasePrice;
    $platOrigPrice = ($hasPlatOffer && $platRegularPrice > $platPrice * 1.25) ? $platRegularPrice : round($platRegularPrice * 2.8);
    if ($platOrigPrice <= $platPrice) {
        $platOrigPrice = round(max(4999, $platPrice * 2.5));
    }
    $platSavingsPct = max(50, min(85, (int) round((1 - ($platPrice / max(1, $platOrigPrice))) * 100)));
    $platDays = max(1, (int) ($platRent->duration_days ?? 180));
    $platPerDay = $platPrice < 5 ? round($platPrice / $platDays, 2) : round($platPrice / $platDays, 1);

    // Pricing & offer calculations for Silver
    $silverOffer = $resolvePlanOffer($silverRent, 'monthly');
    $silverBasePrice = (float) ($silverRent->price ?? 399);
    $hasSilverOffer = ($silverOffer && $silverOffer->discounted_price !== null) || ($silverBasePrice <= 500 && $silverBasePrice > 0);
    $silverPrice = ($silverOffer && $silverOffer->discounted_price !== null) 
        ? (float) $silverOffer->discounted_price 
        : $silverBasePrice;
    $silverRegularPrice = ($silverBasePrice <= 10) ? 399.0 : $silverBasePrice;
    $silverOrigPrice = ($hasSilverOffer && $silverRegularPrice > $silverPrice * 1.25) ? $silverRegularPrice : round(max(999.00, $silverRegularPrice * 2.5));
    if ($silverOrigPrice <= $silverPrice) {
        $silverOrigPrice = round(max(999, $silverPrice * 2.5));
    }
    $silverSavingsPct = max(50, min(85, (int) round((1 - ($silverPrice / max(1, $silverOrigPrice))) * 100)));
    $silverDays = max(1, (int) ($silverRent->duration_days ?? 60));
    $silverPerDay = $silverPrice < 5 ? round($silverPrice / $silverDays, 2) : round($silverPrice / $silverDays, 1);

    $heroSlides = collect([
        [
            'plan' => $goldRent,
            'type' => 'rent',
            'theme' => 'gold',
            'tab_label' => 'Gold Pass',
            'tab_icon' => 'ph-crown',
            'tab_badge' => $hasGoldOffer ? ('₹' . number_format($goldPrice, 0) . ' OFFER') : 'POPULAR',
            'has_offer' => $hasGoldOffer,
            'offer' => $goldOffer,
            'regular_price' => $goldRegularPrice,
            'badge' => $hasGoldOffer 
                ? '🎉 SPECIAL OFFER UNLOCKED · ₹' . number_format($goldPrice, 0) . ' EXCLUSIVE PASS'
                : '👑 MOST POPULAR · 84% OF TENANTS CHOOSE THIS',
            'title_prefix' => 'Unlock Verified Owners With',
            'title_highlight' => 'Gold Direct Pass',
            'title_suffix' => '',
            'tagline' => 'Best value for 1BHK, 2BHK & 3BHK home hunters. Directly call & WhatsApp owners with 0% middleman brokerage.',
            'original_price' => $goldOrigPrice,
            'price' => $goldPrice,
            'savings_pct' => $goldSavingsPct,
            'per_day' => $goldPerDay,
            'highlights' => [
                ['icon' => 'ph-phone-call', 'title' => ($goldRent->contact_limit ?? 75) . ' Verified Owner Contacts', 'desc' => 'Direct phone & WhatsApp numbers'],
                ['icon' => 'ph-shield-check', 'title' => 'Zero Brokerage Guaranteed', 'desc' => 'Save ₹15,000 to ₹35,000 in fees'],
                ['icon' => 'ph-calendar-check', 'title' => ($goldRent->duration_days ?? 150) . ' Days Active Validity', 'desc' => 'Full 5 months of unlimited unlocks'],
                ['icon' => 'ph-chat-circle-dots', 'title' => 'Instant WhatsApp Connect', 'desc' => 'Direct landlord chat & visit pass'],
            ],
            'specs' => [
                ['label' => 'Direct Owner Contacts', 'value' => ($goldRent->contact_limit ?? 75) . ' Direct Unlocks'],
                ['label' => 'Access Duration', 'value' => ($goldRent->duration_days ?? 150) . ' Full Days'],
                ['label' => 'Brokerage Fee', 'value' => '₹0 (Zero Commission)', 'accent' => true],
            ],
            'visual' => [
                'tag' => $hasGoldOffer ? 'EXCLUSIVE OFFER' : '100% OWNER DIRECT',
                'title' => 'Direct Landlords Only',
                'sub' => 'Connect directly with genuine property owners with 0% middleman commission.',
                'perks' => [
                    'Verified Landlord Phone & WA',
                    'Direct Landlord Visit Scheduling',
                    'Save ₹15,000 to ₹35,000 Fees',
                ],
                'trust_note' => 'Direct Landlords Only · ₹0 Commission',
                'image' => $goldImg['primary'],
                'fallback' => $goldImg['fallback'],
            ],
        ],
        [
            'plan' => $platRent,
            'type' => 'rent',
            'theme' => 'platinum',
            'tab_label' => 'Platinum VIP',
            'tab_icon' => 'ph-sparkle',
            'tab_badge' => $hasPlatOffer ? ('₹' . number_format($platPrice, 0) . ' OFFER') : 'VIP CHOICE',
            'has_offer' => $hasPlatOffer,
            'offer' => $platOffer,
            'regular_price' => $platRegularPrice,
            'badge' => $hasPlatOffer 
                ? '🎉 SPECIAL OFFER UNLOCKED · ₹' . number_format($platPrice, 0) . ' VIP PASS'
                : '💎 VIP PRIVILEGE · MAXIMUM OWNER UNLOCKS',
            'title_prefix' => 'Experience Premium Access With',
            'title_highlight' => 'Platinum VIP Pass',
            'title_suffix' => '',
            'tagline' => 'Ultimate unrestricted pass for families, executives, and luxury home seekers. Maximum direct contacts + concierge.',
            'original_price' => $platOrigPrice,
            'price' => $platPrice,
            'savings_pct' => $platSavingsPct,
            'per_day' => $platPerDay,
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
                'tag' => $hasPlatOffer ? 'EXCLUSIVE OFFER' : 'VIP CONCIERGE ACCESS',
                'title' => 'Exclusive Luxury Rentals',
                'sub' => 'Direct access to high-end gated societies, penthouses, and luxury villas.',
                'perks' => [
                    'Priority Concierge Support',
                    'Unlimited Direct Landlord Calls',
                    'Free Rental Agreement Draft',
                ],
                'trust_note' => 'Dedicated Concierge · ₹0 Brokerage',
                'image' => $platImg['primary'],
                'fallback' => $platImg['fallback'],
            ],
        ],
        [
            'plan' => $silverRent,
            'type' => 'rent',
            'theme' => 'silver',
            'tab_label' => 'Silver Starter',
            'tab_icon' => 'ph-shield',
            'tab_badge' => $hasSilverOffer ? ('₹' . number_format($silverPrice, 0) . ' OFFER') : 'BUDGET',
            'has_offer' => $hasSilverOffer,
            'offer' => $silverOffer,
            'regular_price' => $silverRegularPrice,
            'badge' => $hasSilverOffer 
                ? '🎉 SPECIAL OFFER UNLOCKED · ₹' . number_format($silverPrice, 0) . ' DIRECT PASS'
                : '🛡️ ESSENTIAL STARTER · QUICK HOUSE HUNT',
            'title_prefix' => 'Find Your First Home With',
            'title_highlight' => 'Silver Starter Pass',
            'title_suffix' => '',
            'tagline' => $silverRent->description ?? 'Pocket-friendly direct owner access for individual rooms, 1RK, PG stays, and quick single-neighborhood rentals.',
            'original_price' => $silverOrigPrice,
            'price' => $silverPrice,
            'savings_pct' => $silverSavingsPct,
            'per_day' => $silverPerDay,
            'highlights' => [
                ['icon' => 'ph-phone-call', 'title' => ($silverRent->contact_limit ?? 30) . ' Verified Owner Contacts', 'desc' => 'Direct phone numbers & WhatsApp'],
                ['icon' => 'ph-shield-check', 'title' => 'Zero Brokerage Guaranteed', 'desc' => 'No commission on lease closing'],
                ['icon' => 'ph-calendar-check', 'title' => ($silverRent->duration_days ?? 60) . ' Days Active Validity', 'desc' => 'Standard monthly house hunt'],
                ['icon' => 'ph-headset', 'title' => 'Standard Support', 'desc' => 'WhatsApp & email query assistance'],
            ],
            'specs' => [
                ['label' => 'Direct Owner Contacts', 'value' => ($silverRent->contact_limit ?? 30) . ' Direct Unlocks'],
                ['label' => 'Access Duration', 'value' => ($silverRent->duration_days ?? 60) . ' Full Days'],
                ['label' => 'Brokerage Fee', 'value' => '₹0 (Zero Commission)', 'accent' => true],
            ],
            'visual' => [
                'tag' => $hasSilverOffer ? 'EXCLUSIVE OFFER' : 'QUICK MOVE-IN',
                'title' => 'Verified Starter Listings',
                'sub' => 'Best for singles, students, and professionals searching for budget 1RK & PGs.',
                'perks' => [
                    'Direct Phone & WhatsApp Unlock',
                    'Zero Broker Commission',
                    'Standard Online Support',
                ],
                'trust_note' => 'Verified Direct Listings · Fast Connect',
                'image' => $silverImg['primary'],
                'fallback' => $silverImg['fallback'],
            ],
        ],
    ]);

    if ($allBuyPlans->isNotEmpty()) {
        foreach ($allBuyPlans as $bPlan) {
            $buyerBasePrice = (float) (($bPlan->price && $bPlan->price > 0) ? $bPlan->price : 999.00);
            $buyerOffer = $resolvePlanOffer($bPlan, 'yearly');
            $hasBuyerOffer = ($buyerOffer && $buyerOffer->discounted_price !== null) || ((float)($bPlan->price ?? 0) > 0 && (float)($bPlan->price ?? 0) < 500);
            $buyerPrice = ($buyerOffer && $buyerOffer->discounted_price !== null) 
                ? (float) $buyerOffer->discounted_price 
                : $buyerBasePrice;
            $buyerDays = (int) ($bPlan->duration_days ?: 60);
            $buyerContacts = (int) ($bPlan->contact_limit ?: 30);
            $buyerAnchorPrice = ($buyerBasePrice > $buyerPrice * 1.25) ? $buyerBasePrice : round(max(999.00, $buyerPrice * 2.5));
            $buyerOrigPrice = $buyerAnchorPrice;
            if ($buyerOrigPrice <= $buyerPrice) {
                $buyerOrigPrice = round(max(999.00, $buyerPrice * 2.5));
            }
            $buyerSavingsPct = max(50, min(85, (int) round((1 - ($buyerPrice / max(1, $buyerOrigPrice))) * 100)));
            $buyerPerDay = $buyerPrice < 5 ? round($buyerPrice / max(1, $buyerDays), 2) : round($buyerPrice / max(1, $buyerDays), 1);

            $isGoldBuyer = str_contains(strtolower($bPlan->name), 'gold');
            $isPlatBuyer = str_contains(strtolower($bPlan->name), 'plat');
            $tabBadge = $hasBuyerOffer 
                ? ('₹' . number_format($buyerPrice, 0) . ' OFFER') 
                : ($isGoldBuyer ? 'POPULAR' : ($isPlatBuyer ? 'VIP BUYER' : 'BUYER'));

            $heroSlides->push([
                'plan' => $bPlan,
                'type' => 'buy',
                'theme' => 'buyer',
                'tab_label' => str_replace([' Pass', ' pass'], '', $bPlan->name),
                'tab_icon' => 'ph-buildings',
                'tab_badge' => $tabBadge,
                'has_offer' => $hasBuyerOffer,
                'offer' => $buyerOffer,
                'regular_price' => $buyerBasePrice,
                'badge' => $hasBuyerOffer 
                    ? '🎉 SPECIAL OFFER UNLOCKED · ₹' . number_format($buyerPrice, 0) . ' ' . strtoupper($bPlan->name) 
                    : '🏡 ZERO BROKERAGE BUYER PASS · DIRECT SELLER DEALS',
                'title_prefix' => 'Purchase Your Dream Property With',
                'title_highlight' => $bPlan->name,
                'title_suffix' => '',
                'tagline' => $bPlan->description ?: 'Buying a flat, villa, or commercial space? Skip the 1% to 2% property broker commission and negotiate directly with verified sellers.',
                'original_price' => $buyerOrigPrice,
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
                    'tag' => $hasBuyerOffer ? 'EXCLUSIVE OFFER' : 'DIRECT SELLER DEALS',
                    'title' => 'Direct Property Purchase',
                    'sub' => 'Connect directly with genuine property sellers and save lakhs in brokerage fees.',
                    'perks' => [
                        'Verified Direct Seller Contacts',
                        'Save 1% to 2% Brokerage Fee',
                        'Title Document Checklist',
                    ],
                    'trust_note' => 'Direct Seller Negotiation · ₹0 Middleman',
                    'image' => $buyerImg['primary'],
                    'fallback' => $buyerImg['fallback'],
                ],
            ]);
        }
    }
@endphp

<style>
/* ============================================================
   UNLOCK RENTALS — HERO PLANS SLIDER (COMPACT LIGHT LUXURY)
   Height reduced by ~40%, tight balanced 3-part desktop layout,
   clean 2x2 benefits, prominent ₹999 price, single horizontal
   trust row, and naturally stacked responsive mobile view.
/* Special User Offer Badges & Visual Enhancements */
.ur-hps-tab-btn--has-offer {
    border-color: rgba(16, 185, 129, 0.45) !important;
}
.ur-hps-tab-pill--offer {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
    color: #ffffff !important;
    border: 1px solid #34d399 !important;
    font-weight: 800 !important;
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.4) !important;
    animation: urHpsOfferPulse 2s infinite ease-in-out;
}
@keyframes urHpsOfferPulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.06); }
}
.ur-hps-save-chip--offer {
    background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
    color: #047857;
    border: 1px solid #34d399;
    font-weight: 800;
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.2);
}
.ur-hps-plan-badge--offer {
    box-shadow: 0 2px 10px rgba(15, 23, 42, 0.08);
}
.ur-mc-badge--offer {
    background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
    color: #047857;
    border: 1px solid #a7f3d0;
    font-weight: 800;
}
.ur-mc-save-chip--offer {
    background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
    color: #047857;
    border: 1px solid #34d399;
    font-weight: 800;
}
.ur-mc-direct-tag--offer {
    background: rgba(236, 253, 245, 0.95);
    color: #047857;
    border: 1px solid #86efac;
    font-weight: 800;
}
.ur-hps-cta-btn--offer {
    position: relative;
    overflow: hidden;
}
.ur-mc-cta-btn--offer {
    position: relative;
    overflow: hidden;
}

.ur-hero-plans-slider-section {
    position: relative;
    width: 100%;
    max-width: 100%;
    margin-left: auto;
    margin-right: auto;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: linear-gradient(180deg, #f8fafc 0%, #ffffff 50%, #f8fafc 100%);
    color: #0f172a;
    padding: 1.25rem 0 1.25rem;
    overflow: hidden;
    overflow-x: clip;
    box-sizing: border-box;
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
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 2rem;
    box-sizing: border-box;
}

/* Compact Section Top Bar: Eyebrow + Plan Switcher Tabs + Nav Controls */
.ur-hps-top-nav {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
    flex-wrap: nowrap;
}

.ur-hps-top-left {
    display: flex;
    align-items: center;
    flex-shrink: 0;
}

.ur-hps-top-heading {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    font-size: 0.78rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #0f172a;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    padding: 0.4rem 0.95rem;
    border-radius: 9999px;
    box-shadow: 0 1px 4px rgba(15, 23, 42, 0.04);
    white-space: nowrap;
}

.ur-hps-top-heading i {
    color: #059669;
    font-size: 0.88rem;
}

.ur-hps-top-right {
    display: flex;
    align-items: center;
    gap: 0.55rem;
    min-width: 0;
    flex: 1;
    justify-content: flex-end;
}

/* Quick Jump Plan Tabs Nav Bar — Single-row Sleek Segmented Pill */
.ur-hps-tabs-bar-wrap {
    display: inline-flex;
    align-items: center;
    position: relative;
    max-width: 100%;
}

.ur-hps-tabs-bar {
    display: inline-flex;
    align-items: center;
    gap: 0.22rem;
    flex-wrap: nowrap;
    overflow-x: auto;
    scrollbar-width: none;
    -ms-overflow-style: none;
    -webkit-overflow-scrolling: touch;
    padding: 0.22rem 0.35rem;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 9999px;
    box-shadow: 0 1px 4px rgba(15, 23, 42, 0.04);
    max-width: 100%;
}

.ur-hps-tabs-bar::-webkit-scrollbar {
    display: none;
}

.ur-hps-tab-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.32rem;
    padding: 0.36rem 0.75rem;
    border-radius: 9999px;
    border: 1px solid transparent;
    background: transparent;
    color: #64748b;
    font-family: inherit;
    font-size: 0.76rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    white-space: nowrap;
    flex-shrink: 0;
    touch-action: manipulation;
    -webkit-tap-highlight-color: transparent;
    user-select: none;
    -webkit-touch-callout: none;
}

.ur-hps-tab-btn:active {
    transform: scale(0.96);
}

.ur-hps-tab-btn:hover {
    color: #0f172a;
    background: rgba(255, 255, 255, 0.85);
}

.ur-hps-tab-btn.active.tab--gold {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    border-color: #f59e0b;
    box-shadow: 0 3px 12px rgba(217, 119, 6, 0.35);
    color: #ffffff;
}

.ur-hps-tab-btn.active.tab--platinum {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    border-color: #2563eb;
    box-shadow: 0 3px 12px rgba(37, 99, 235, 0.35);
    color: #ffffff;
}

.ur-hps-tab-btn.active.tab--silver {
    background: linear-gradient(135deg, #334155 0%, #1e293b 100%);
    border-color: #1e293b;
    box-shadow: 0 3px 12px rgba(30, 41, 59, 0.3);
    color: #ffffff;
}

.ur-hps-tab-btn.active.tab--buyer {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    border-color: #059669;
    box-shadow: 0 3px 12px rgba(5, 150, 105, 0.35);
    color: #ffffff;
}

.ur-hps-tab-pill {
    font-size: 0.58rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    padding: 0.1rem 0.42rem;
    border-radius: 9999px;
    background: #e2e8f0;
    color: #475569;
    transition: all 0.2s ease;
    line-height: 1.15;
}

.ur-hps-tab-pill--offer {
    background: #dcfce7;
    color: #047857;
}

.ur-hps-tab-btn.active .ur-hps-tab-pill {
    background: rgba(255, 255, 255, 0.28) !important;
    color: #ffffff !important;
}

/* Integrated Top Navigation Controls Pill */
.ur-hps-nav-arrows {
    display: inline-flex;
    align-items: center;
    gap: 0.28rem;
    padding: 0.22rem;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 9999px;
    box-shadow: 0 1px 4px rgba(15, 23, 42, 0.04);
    flex-shrink: 0;
}

.ur-hps-arrow-btn {
    width: 2.1rem;
    height: 2.1rem;
    border-radius: 50%;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    color: #475569;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
}

.ur-hps-arrow-btn:hover {
    background: #0f172a;
    border-color: #0f172a;
    color: #ffffff;
    box-shadow: 0 3px 8px rgba(15, 23, 42, 0.15);
    transform: scale(1.05);
}

.ur-hps-arrow-btn:active {
    transform: scale(0.94);
}

/* ─── SLIDER STAGE WRAPPER ───────────────────────────────── */
.ur-hps-slider-stage {
    position: relative;
    width: 100%;
    max-width: 100%;
    margin: 0 auto;
    box-sizing: border-box;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 1.75rem;
    box-shadow: 0 24px 64px -12px rgba(15, 23, 42, 0.08), 0 6px 18px rgba(15, 23, 42, 0.03);
    overflow: hidden;
}

/* Slide Track */
.ur-hps-track {
    display: flex;
    flex-wrap: nowrap;
    width: 100%;
    transition: transform 0.45s cubic-bezier(0.2, 0.9, 0.3, 1);
    will-change: transform;
    touch-action: pan-y pinch-zoom;
}

/* Individual Slide */
.ur-hps-slide {
    flex: 0 0 100%;
    width: 100%;
    min-width: 100%;
    max-width: 100%;
    padding: 1.35rem 1.75rem 1.15rem;
    box-sizing: border-box;
    position: relative;
    overflow: hidden;
}

.ur-hps-slide--gold {
    background: radial-gradient(circle at 85% 15%, rgba(245, 158, 11, 0.08) 0%, transparent 55%), 
                radial-gradient(circle at 10% 85%, rgba(251, 191, 36, 0.05) 0%, transparent 45%), 
                linear-gradient(180deg, #ffffff 0%, #fffcf5 100%);
}
.ur-hps-slide--platinum {
    background: radial-gradient(circle at 85% 15%, rgba(37, 99, 235, 0.08) 0%, transparent 55%), 
                radial-gradient(circle at 10% 85%, rgba(96, 165, 250, 0.05) 0%, transparent 45%), 
                linear-gradient(180deg, #ffffff 0%, #f8faff 100%);
}
.ur-hps-slide--silver {
    background: radial-gradient(circle at 85% 15%, rgba(71, 85, 105, 0.06) 0%, transparent 55%), 
                linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
}
/* ─── BUYER PASS SLIDE: 5-LAYER REAL ESTATE COMPOSITION ─────── */
.ur-hps-slide--buyer {
    position: relative;
    background: radial-gradient(circle at 85% 15%, rgba(5, 150, 105, 0.08) 0%, transparent 55%), 
                linear-gradient(180deg, #ffffff 0%, #f6fbf8 100%);
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

/* ─── STANDARD COMPACT 2-COLUMN DESKTOP GRID ─────────────── */
.ur-hps-desktop-grid {
    display: grid;
    grid-template-columns: 1.35fr 1fr;
    gap: 2rem;
    align-items: center;
    width: 100%;
    position: relative;
    z-index: 5;
}

/* Subtle Architectural Photographic Backdrop */
.ur-hps-desktop-backdrop {
    position: absolute;
    top: 0;
    right: 0;
    width: 46%;
    height: 100%;
    pointer-events: none;
    z-index: 1;
    overflow: hidden;
    mask-image: linear-gradient(to left, rgba(0, 0, 0, 0.16) 0%, rgba(0, 0, 0, 0.05) 55%, transparent 100%);
    -webkit-mask-image: linear-gradient(to left, rgba(0, 0, 0, 0.16) 0%, rgba(0, 0, 0, 0.05) 55%, transparent 100%);
}

.ur-hps-desktop-backdrop img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center 30%;
    filter: saturate(1.1);
}

/* ─── 1. LEFT COLUMN: VALUE PROPOSITION & COMPACT BENEFIT PILLS ──── */
.ur-hps-col-left {
    display: flex;
    flex-direction: column;
    justify-content: center;
    z-index: 5;
    padding-right: 0.5rem;
}

.ur-hps-plan-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.68rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 0.24rem 0.75rem;
    border-radius: 9999px;
    margin-bottom: 0.45rem;
    width: fit-content;
}

.ur-hps-plan-badge--gold {
    background: #fef3c7;
    color: #b45309;
    border: 1px solid #fde68a;
    box-shadow: 0 2px 8px rgba(245, 158, 11, 0.12);
}
.ur-hps-plan-badge--platinum {
    background: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
    box-shadow: 0 2px 8px rgba(37, 99, 235, 0.12);
}
.ur-hps-plan-badge--silver {
    background: #f1f5f9;
    color: #334155;
    border: 1px solid #cbd5e1;
    box-shadow: 0 2px 8px rgba(71, 85, 105, 0.1);
}
.ur-hps-plan-badge--buyer {
    background: #ecfdf5;
    color: #047857;
    border: 1px solid #a7f3d0;
    box-shadow: 0 2px 8px rgba(5, 150, 105, 0.12);
}

.ur-hps-slide-title {
    font-size: clamp(1.3rem, 1.8vw, 1.62rem);
    font-weight: 900;
    color: #0f172a;
    line-height: 1.22;
    letter-spacing: -0.025em;
    margin: 0 0 0.35rem;
}

.ur-hps-slide-title .highlight-gold {
    background: linear-gradient(135deg, #d97706 0%, #ea580c 50%, #b45309 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    display: inline-block;
}
.ur-hps-slide-title .highlight-platinum {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 50%, #1e40af 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    display: inline-block;
}
.ur-hps-slide-title .highlight-silver {
    background: linear-gradient(135deg, #1e293b 0%, #334155 50%, #475569 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    display: inline-block;
}
.ur-hps-slide-title .highlight-buyer {
    background: linear-gradient(135deg, #059669 0%, #047857 50%, #064e3b 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    display: inline-block;
}

.ur-hps-slide-desc {
    font-size: 0.82rem;
    color: #475569;
    line-height: 1.45;
    margin: 0 0 0.75rem;
    max-width: 520px;
}

/* Feature 2x2 Highlights — Compact Modern Pills */
.ur-hps-features-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.45rem;
    width: 100%;
}

.ur-hps-feature-item {
    display: flex;
    align-items: center;
    gap: 0.55rem;
    padding: 0.42rem 0.68rem;
    background: rgba(255, 255, 255, 0.92);
    border: 1px solid #e2e8f0;
    border-radius: 0.75rem;
    box-shadow: 0 1px 4px rgba(15, 23, 42, 0.03);
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}

.ur-hps-feature-item:hover {
    border-color: #cbd5e1;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
}

.ur-hps-f-icon-box {
    width: 1.95rem;
    height: 1.95rem;
    border-radius: 0.55rem;
    background: #f1f5f9;
    color: #475569;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.98rem;
    flex-shrink: 0;
    transition: transform 0.2s ease;
}

.ur-hps-feature-item:hover .ur-hps-f-icon-box {
    transform: scale(1.05);
}

.ur-hps-feature-item--gold .ur-hps-f-icon-box {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    color: #b45309;
    border: 1px solid #fcd34d;
}
.ur-hps-feature-item--platinum .ur-hps-f-icon-box {
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    color: #2563eb;
    border: 1px solid #bfdbfe;
}
.ur-hps-feature-item--silver .ur-hps-f-icon-box {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    color: #334155;
    border: 1px solid #cbd5e1;
}
.ur-hps-feature-item--buyer .ur-hps-f-icon-box {
    background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
    color: #059669;
    border: 1px solid #a7f3d0;
}

.ur-hps-f-text {
    min-width: 0;
}

.ur-hps-f-text h4 {
    font-size: 0.76rem;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
    letter-spacing: -0.01em;
    line-height: 1.22;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.ur-hps-f-text p {
    font-size: 0.66rem;
    color: #64748b;
    margin: 0;
    line-height: 1.2;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Micro Guarantee Row */
.ur-hps-guarantee-strip {
    display: flex;
    align-items: center;
    gap: 1.15rem;
    margin-top: 0.65rem;
    font-size: 0.72rem;
    font-weight: 700;
    color: #047857;
}

.ur-hps-guarantee-strip span {
    display: inline-flex;
    align-items: center;
    gap: 0.32rem;
}

.ur-hps-guarantee-strip span i {
    font-size: 0.85rem;
    color: #059669;
}

/* ─── 2. RIGHT COLUMN: COMPACT HIGH-CONVERSION PASS CARD ─── */
.ur-hps-col-right {
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
    padding: 0.95rem 1.15rem 0.75rem;
    box-sizing: border-box;
    box-shadow: 0 16px 36px -8px rgba(15, 23, 42, 0.08), 0 3px 10px rgba(0, 0, 0, 0.03);
    transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.28s ease, border-color 0.28s ease;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.ur-hps-pass-card:hover {
    transform: translateY(-2px);
}

/* Top accent color bar */
.ur-hps-pass-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3.5px;
    border-radius: 1.15rem 1.15rem 0 0;
}

.ur-hps-pass-card--gold {
    border-color: #f59e0b;
    background: linear-gradient(180deg, #ffffff 0%, #fffdf9 100%);
    box-shadow: 0 16px 40px -8px rgba(245, 158, 11, 0.2), 0 3px 10px rgba(15, 23, 42, 0.04);
}
.ur-hps-pass-card--gold::before {
    background: linear-gradient(90deg, #f59e0b, #fbbf24, #ea580c);
}

.ur-hps-pass-card--platinum {
    border-color: #3b82f6;
    background: linear-gradient(180deg, #ffffff 0%, #f8faff 100%);
    box-shadow: 0 16px 40px -8px rgba(37, 99, 235, 0.2), 0 3px 10px rgba(15, 23, 42, 0.04);
}
.ur-hps-pass-card--platinum::before {
    background: linear-gradient(90deg, #2563eb, #60a5fa, #1d4ed8);
}

.ur-hps-pass-card--silver {
    border-color: #64748b;
    background: linear-gradient(180deg, #ffffff 0%, #fafbfc 100%);
    box-shadow: 0 16px 40px -8px rgba(71, 85, 105, 0.16), 0 3px 10px rgba(15, 23, 42, 0.04);
}
.ur-hps-pass-card--silver::before {
    background: linear-gradient(90deg, #334155, #64748b, #1e293b);
}

.ur-hps-pass-card--buyer {
    border-color: #10b981;
    background: linear-gradient(180deg, #ffffff 0%, #f6fbf8 100%);
    box-shadow: 0 16px 40px -8px rgba(5, 150, 105, 0.22), 0 3px 10px rgba(15, 23, 42, 0.04);
}
.ur-hps-pass-card--buyer::before {
    background: linear-gradient(90deg, #059669, #34d399, #047857);
}

/* Card Header with Plan Identity Pill */
.ur-hps-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 0.42rem;
}

.ur-hps-card-title-group {
    display: flex;
    align-items: center;
    gap: 0.4rem;
}

.ur-hps-card-tier-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.28rem;
    font-size: 0.68rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    padding: 0.18rem 0.55rem;
    border-radius: 9999px;
}

.ur-hps-card-tier-pill--gold {
    background: #fef3c7;
    color: #b45309;
    border: 1px solid #fde68a;
}
.ur-hps-card-tier-pill--platinum {
    background: #dbeafe;
    color: #1e40af;
    border: 1px solid #bfdbfe;
}
.ur-hps-card-tier-pill--silver {
    background: #f1f5f9;
    color: #334155;
    border: 1px solid #cbd5e1;
}
.ur-hps-card-tier-pill--buyer {
    background: #ecfdf5;
    color: #065f46;
    border: 1px solid #a7f3d0;
}

.ur-hps-card-brand-sub {
    font-size: 0.62rem;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.04em;
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
    box-shadow: 0 1px 4px rgba(15, 23, 42, 0.04);
}

.ur-emblem-svg {
    width: 1.25rem;
    height: 1.25rem;
}

/* Price Box */
.ur-hps-price-box {
    margin-bottom: 0.45rem;
    padding-bottom: 0.45rem;
    border-bottom: 1px solid #f1f5f9;
}

.ur-hps-price-top {
    display: flex;
    align-items: center;
    gap: 0.45rem;
    margin-bottom: 0.1rem;
}

.ur-hps-price-original {
    font-size: 0.8rem;
    font-weight: 700;
    color: #94a3b8;
    text-decoration: line-through;
}

.ur-hps-save-chip {
    font-size: 0.62rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.03em;
    padding: 0.1rem 0.45rem;
    border-radius: 9999px;
    background: #ecfdf5;
    color: #047857;
    border: 1px solid #6ee7b7;
}

.ur-hps-price-main {
    display: flex;
    align-items: baseline;
    gap: 0.15rem;
    margin-bottom: 0.05rem;
}

.ur-hps-currency {
    font-size: 1.15rem;
    font-weight: 900;
    color: #0f172a;
}

.ur-hps-amount {
    font-size: 2.2rem;
    font-weight: 900;
    color: #0f172a;
    line-height: 1;
    letter-spacing: -0.04em;
}

.ur-hps-period {
    font-size: 0.72rem;
    font-weight: 600;
    color: #64748b;
    margin-left: 0.15rem;
}

.ur-hps-price-subtext {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    font-size: 0.66rem;
    font-weight: 700;
    color: #047857;
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    padding: 0.14rem 0.5rem;
    border-radius: 9999px;
    margin-top: 0.25rem;
}

/* Card Specs: Horizontal 3-Column Strip */
.ur-hps-card-meta {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.35rem;
    margin-bottom: 0.55rem;
    background: #f8fafc;
    padding: 0.4rem 0.55rem;
    border-radius: 0.7rem;
    border: 1px solid #e2e8f0;
}

.ur-hps-meta-row {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.ur-hps-meta-row span.meta-label {
    color: #64748b;
    display: inline-flex;
    align-items: center;
    gap: 0.2rem;
    font-weight: 700;
    font-size: 0.62rem;
    line-height: 1.2;
    margin-bottom: 0.08rem;
    white-space: nowrap;
}

.ur-hps-meta-row span.meta-label i {
    color: #059669;
    font-size: 0.72rem;
}

.ur-hps-meta-row span.meta-value {
    font-weight: 900;
    color: #0f172a;
    font-size: 0.76rem;
    line-height: 1.2;
    white-space: nowrap;
}

.ur-hps-meta-row span.meta-value.meta-accent {
    color: #059669;
}

/* Primary High-Impact CTA Button */
.ur-hps-cta-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.45rem;
    width: 100%;
    padding: 0.7rem 1.15rem;
    border-radius: 0.8rem;
    font-family: inherit;
    font-size: 0.88rem;
    font-weight: 800;
    letter-spacing: -0.01em;
    text-decoration: none;
    cursor: pointer;
    box-sizing: border-box;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
    overflow: hidden;
    margin-bottom: 0.25rem;
    border: none;
}

.ur-hps-cta-btn::after {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(60deg, transparent, rgba(255, 255, 255, 0.28), transparent);
    transform: rotate(25deg) translateX(-100%);
    transition: transform 0.8s ease;
}

.ur-hps-cta-btn:hover::after {
    transform: rotate(25deg) translateX(100%);
}

.ur-hps-cta-btn--gold {
    background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%) !important;
    color: #ffffff !important;
    box-shadow: 0 6px 18px rgba(234, 88, 12, 0.35) !important;
}
.ur-hps-cta-btn--gold:hover {
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 50%, #d97706 100%) !important;
    box-shadow: 0 10px 24px rgba(234, 88, 12, 0.44) !important;
    transform: translateY(-1px);
    color: #ffffff !important;
}

.ur-hps-cta-btn--platinum {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%) !important;
    color: #ffffff !important;
    box-shadow: 0 6px 18px rgba(37, 99, 235, 0.35) !important;
}
.ur-hps-cta-btn--platinum:hover {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 50%, #1d4ed8 100%) !important;
    box-shadow: 0 10px 24px rgba(37, 99, 235, 0.44) !important;
    transform: translateY(-1px);
    color: #ffffff !important;
}

.ur-hps-cta-btn--silver {
    background: linear-gradient(135deg, #334155 0%, #1e293b 100%) !important;
    color: #ffffff !important;
    box-shadow: 0 6px 18px rgba(15, 23, 42, 0.25) !important;
}
.ur-hps-cta-btn--silver:hover {
    background: linear-gradient(135deg, #475569 0%, #334155 50%, #1e293b 100%) !important;
    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.35) !important;
    transform: translateY(-1px);
    color: #ffffff !important;
}

.ur-hps-cta-btn--buyer {
    background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
    color: #ffffff !important;
    box-shadow: 0 6px 18px rgba(5, 150, 105, 0.35) !important;
    border: 1px solid rgba(255, 255, 255, 0.25);
}
.ur-hps-cta-btn--buyer:hover {
    background: linear-gradient(135deg, #10b981 0%, #059669 50%, #047857 100%) !important;
    box-shadow: 0 10px 24px rgba(5, 150, 105, 0.44) !important;
    transform: translateY(-1px);
    color: #ffffff !important;
}

/* Compact Scroll Down Link */
.ur-hps-compare-link {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.3rem;
    font-size: 0.7rem;
    font-weight: 700;
    color: #64748b;
    text-decoration: none;
    transition: color 0.2s ease;
    text-align: center;
    padding-top: 0.18rem;
}

.ur-hps-compare-link:hover {
    color: #0f172a;
}

/* ─── 3. BOTTOM HORIZONTAL TRUST ROW (COMPACT HIGH-CONTRAST BAR) ── */
.ur-hps-trust-bar {
    grid-column: 1 / -1;
    position: relative;
    z-index: 10;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1.6rem;
    flex-wrap: wrap;
    background: #ffffff !important;
    border: 1px solid #e2e8f0;
    border-radius: 9999px;
    padding: 0.42rem 1.6rem;
    margin-top: 0.75rem;
    box-shadow: 0 3px 12px rgba(15, 23, 42, 0.04);
    font-size: 0.74rem;
    font-weight: 700;
    color: #0f172a !important;
    transition: all 0.25s ease;
}

.ur-hps-trust-bar:hover {
    box-shadow: 0 4px 16px rgba(15, 23, 42, 0.07);
    border-color: #cbd5e1;
}

.ur-hps-tb-item {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    color: #0f172a !important;
    font-weight: 700;
}

.ur-hps-tb-item i {
    color: #059669;
    font-size: 0.84rem;
}

.ur-hps-tb-divider {
    width: 3.5px;
    height: 3.5px;
    border-radius: 50%;
    background: #cbd5e1;
}

/* ─── BOTTOM CONTROLS (BALANCED INDICATOR DOTS & HINT) ───── */
.ur-hps-bottom-bar {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1.5rem;
    margin-top: 1.15rem;
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
    font-size: 0.7rem;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.ur-hps-swipe-hint i {
    color: #f59e0b;
    font-size: 0.85rem;
}

/* ─── DESKTOP VS MOBILE DISPLAY TOGGLES ───────────────────── */
.ur-hps-mobile-card {
    display: none; /* Hidden by default on desktop */
}

.ur-mc-bottom-trust {
    display: none; /* Hidden by default on desktop */
}

/* ─── RESPONSIVE BEHAVIOR (MOBILE & TABLET: 16:9 SLIDER CARDS) ─── */
@media (max-width: 1023px) {
    /* Hide the complex tall desktop layout completely on mobile */
    .ur-hps-desktop-grid {
        display: none !important;
    }

    /* Outer Section & Container: Sleek compact vertical padding */
    .ur-hero-plans-slider-section {
        padding: 0.45rem 0 0.65rem !important;
    }
    .is-mobile-app .ur-hero-plans-slider-section {
        padding: 0.35rem 0 0.5rem !important;
    }

    .ur-hps-container {
        padding: 0 0.75rem;
        max-width: 100%;
    }

    /* Top Controls Header: Centered & Uncluttered */
    .ur-hps-top-nav {
        margin-bottom: 0.65rem;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        width: 100%;
    }

    .ur-hps-top-left {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .ur-hps-top-heading {
        font-size: 0.72rem;
        font-weight: 800;
        color: #0f172a;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.28rem 0.85rem;
        border-radius: 9999px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
        letter-spacing: 0.03em;
    }

    .ur-hps-top-heading i {
        color: #059669;
        font-size: 0.82rem;
    }

    .ur-hps-top-right {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Redundant cramped arrows hidden on mobile: gives full width to tabs */
    .ur-hps-nav-arrows {
        display: none !important;
    }

    /* Swipeable segmented control with soft edge fade masks */
    .ur-hps-tabs-bar-wrap {
        position: relative;
        width: 100%;
        overflow: hidden;
        mask-image: linear-gradient(to right, transparent 0%, black 16px, black calc(100% - 16px), transparent 100%);
        -webkit-mask-image: linear-gradient(to right, transparent 0%, black 16px, black calc(100% - 16px), transparent 100%);
        padding: 0.1rem 0;
    }

    .ur-hps-tabs-bar {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        background: #f1f5f9;
        padding: 0.25rem 0.65rem;
        border-radius: 9999px;
        border: 1px solid #e2e8f0;
        width: 100%;
        box-sizing: border-box;
        justify-content: flex-start;
        scroll-snap-type: x proximity;
    }

    .ur-hps-tabs-bar::-webkit-scrollbar {
        display: none;
    }

    .ur-hps-tab-btn {
        flex: 0 0 auto;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.38rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.72rem;
        font-weight: 700;
        color: #64748b;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        scroll-snap-align: center;
        cursor: pointer;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
    }

    .ur-hps-tab-btn:not(.active) i {
        color: #64748b;
    }

    /* Active Tab: Sleek emerald-green fill with subtle shadow */
    .ur-hps-tab-btn.active {
        background: #059669 !important;
        color: #ffffff !important;
        border-color: #047857 !important;
        box-shadow: 0 4px 14px rgba(5, 150, 105, 0.35) !important;
    }

    .ur-hps-tab-btn.active i {
        color: #ffffff !important;
    }

    .ur-hps-tab-pill {
        font-size: 0.58rem;
        font-weight: 800;
        padding: 0.12rem 0.45rem;
        border-radius: 9999px;
        letter-spacing: 0.02em;
        text-transform: uppercase;
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
    }

    .ur-hps-tab-btn.active .ur-hps-tab-pill {
        background: rgba(255, 255, 255, 0.22) !important;
        color: #ffffff !important;
        border: 1px solid rgba(255, 255, 255, 0.35) !important;
    }

    /* Slider Stage: Clean track wrapper without clipping shadows */
    .ur-hps-slider-stage {
        background: transparent !important;
        border: none !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        overflow: hidden;
        padding: 0.2rem 0.05rem 0.35rem;
    }

    /* Slide container */
    .ur-hps-slide {
        flex: 0 0 100%;
        width: 100%;
        min-width: 100%;
        max-width: 100%;
        box-sizing: border-box;
        padding: 0;
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        position: relative;
    }

    /* ─── MODERN HIGH-CONVERTING FINTECH MOBILE CARD ──────────────── */
    .ur-hps-mobile-card {
        display: flex !important;
        flex-direction: column;
        position: relative;
        width: 100%;
        aspect-ratio: auto !important;
        min-height: auto !important;
        max-height: none !important;
        border-radius: 1.25rem !important;
        overflow: hidden;
        padding: 1.25rem 1.15rem 1.15rem !important;
        box-sizing: border-box;
        background: rgba(255, 255, 255, 0.98) !important;
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1.5px solid rgba(16, 185, 129, 0.22) !important;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08), 0 8px 10px -6px rgba(15, 23, 42, 0.04), 0 0 0 1px rgba(16, 185, 129, 0.08) !important;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .ur-hps-mobile-card--gold {
        border-color: rgba(245, 158, 11, 0.3) !important;
    }
    .ur-hps-mobile-card--platinum {
        border-color: rgba(37, 99, 235, 0.3) !important;
    }
    .ur-hps-mobile-card--silver {
        border-color: rgba(100, 116, 139, 0.25) !important;
    }
    .ur-hps-mobile-card--buyer {
        border-color: rgba(16, 185, 129, 0.3) !important;
    }

    /* Subtle Real Estate Photo Backdrop in Card */
    .ur-mc-backdrop {
        position: absolute;
        inset: 0;
        pointer-events: none;
        z-index: 1;
        overflow: hidden;
    }

    .ur-mc-backdrop-img {
        position: absolute;
        top: 0;
        right: 0;
        width: 48%;
        height: 100%;
        object-fit: cover;
        object-position: center 30%;
        opacity: 0.12;
        filter: saturate(1.1);
        mask-image: linear-gradient(to right, transparent 0%, rgba(0,0,0,0.4) 40%, rgba(0,0,0,1) 100%);
        -webkit-mask-image: linear-gradient(to right, transparent 0%, rgba(0,0,0,0.4) 40%, rgba(0,0,0,1) 100%);
    }

    .ur-mc-scrim {
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.98) 0%, rgba(255, 255, 255, 0.92) 100%);
    }

    /* 1. Consolidated Header Row: Warm Discount Highlight on Left, Clean Status on Right */
    .ur-mc-header-row {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
        margin-bottom: 0.85rem;
    }

    .ur-mc-discount-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
        font-size: 0.68rem;
        font-weight: 800;
        padding: 0.22rem 0.6rem;
        border-radius: 9999px;
        letter-spacing: 0.02em;
    }

    .ur-mc-spark-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #f59e0b;
        box-shadow: 0 0 6px #f59e0b;
        flex-shrink: 0;
        animation: urSparkPulse 2s infinite ease-in-out;
    }

    @keyframes urSparkPulse {
        0%, 100% { transform: scale(1); opacity: 0.9; }
        50% { transform: scale(1.3); opacity: 1; }
    }

    .ur-mc-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        font-size: 0.68rem;
        font-weight: 700;
        color: #059669;
        background: transparent;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .ur-mc-status-pill i {
        font-size: 0.85rem;
    }

    /* 2. Main Plan Title & Subtitle Hierarchy */
    .ur-mc-title-section {
        position: relative;
        z-index: 2;
        margin-bottom: 0.85rem;
    }

    .ur-mc-title {
        font-size: 1.45rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
        letter-spacing: -0.025em;
        margin: 0 0 0.25rem;
    }

    .ur-mc-subtitle {
        font-size: 0.76rem;
        font-weight: 500;
        color: #64748b;
        line-height: 1.35;
        margin: 0;
    }

    /* 3. Redesigned Feature Highlights: Clean 2-column grid with soft green icon containers */
    .ur-mc-features-grid {
        position: relative;
        z-index: 2;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.65rem;
        margin-bottom: 0.95rem;
    }

    .ur-mc-feature-card {
        display: flex;
        align-items: center;
        gap: 0.55rem;
        background: rgba(248, 250, 252, 0.95);
        border: 1px solid #f1f5f9;
        border-radius: 0.75rem;
        padding: 0.5rem 0.65rem;
    }

    .ur-mc-f-icon {
        width: 1.85rem;
        height: 1.85rem;
        border-radius: 0.5rem;
        background: #ecfdf5;
        color: #059669;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        flex-shrink: 0;
    }

    .ur-mc-f-info {
        display: flex;
        flex-direction: column;
        min-width: 0;
    }

    .ur-mc-f-title {
        font-size: 0.72rem;
        font-weight: 700;
        color: #1e293b;
        line-height: 1.25;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .ur-mc-f-desc {
        font-size: 0.62rem;
        font-weight: 500;
        color: #64748b;
        line-height: 1.2;
        margin-top: 0.1rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* 4. Pricing & CTA Dock: Subtly Contrasted Bottom Container */
    .ur-mc-pricing-dock {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 0.95rem;
        padding: 0.65rem 0.85rem;
    }

    .ur-mc-price-group {
        display: flex;
        flex-direction: column;
        min-width: 0;
    }

    .ur-mc-price-row {
        display: flex;
        align-items: baseline;
        gap: 0.18rem;
        line-height: 1;
    }

    .ur-mc-currency {
        font-size: 0.95rem;
        font-weight: 800;
        color: #0f172a;
    }

    .ur-mc-price-val {
        font-size: 1.55rem;
        font-weight: 900;
        color: #0f172a;
        letter-spacing: -0.03em;
    }

    .ur-mc-price-orig {
        font-size: 0.8rem;
        font-weight: 600;
        color: #94a3b8;
        text-decoration: line-through;
        margin-left: 0.35rem;
    }

    .ur-mc-price-caption {
        font-size: 0.64rem;
        font-weight: 600;
        color: #059669;
        display: inline-flex;
        align-items: center;
        gap: 0.2rem;
        margin-top: 0.18rem;
    }

    .ur-mc-price-caption i {
        font-size: 0.7rem;
    }

    /* High-contrast, Tactile Emerald CTA Button */
    .ur-mc-cta-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        padding: 0.62rem 1.1rem;
        border-radius: 9999px;
        font-size: 0.82rem;
        font-weight: 800;
        text-decoration: none;
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        color: #ffffff !important;
        border: none;
        box-shadow: 0 4px 14px rgba(5, 150, 105, 0.32);
        transition: transform 0.15s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.15s ease;
        cursor: pointer;
        white-space: nowrap;
        user-select: none;
        -webkit-tap-highlight-color: transparent;
    }

    .ur-mc-cta-action:hover {
        box-shadow: 0 6px 18px rgba(5, 150, 105, 0.42);
        transform: translateY(-1px);
    }

    .ur-mc-cta-action:active {
        transform: scale(0.96);
        box-shadow: 0 2px 6px rgba(5, 150, 105, 0.25);
    }

    /* Compact Bottom Indicator Controls */
    .ur-hps-bottom-bar {
        margin-top: 0.65rem;
        justify-content: center;
        display: flex;
    }

    .ur-hps-dots {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
    }

    .ur-hps-dot {
        width: 0.35rem;
        height: 0.35rem;
        border-radius: 9999px;
        background: #cbd5e1;
        border: none;
        padding: 0;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        cursor: pointer;
    }

    .ur-hps-dot.active {
        width: 1.25rem;
        height: 0.35rem;
        background: #059669 !important;
        box-shadow: 0 1px 4px rgba(5, 150, 105, 0.35);
    }

    .ur-hps-swipe-hint {
        display: none;
    }

    /* Single-line Compact Mobile Trust Strip under Slider */
    .ur-mc-bottom-trust {
        display: flex !important;
        align-items: center;
        justify-content: center;
        gap: 0.65rem;
        margin-top: 0.4rem;
        padding: 0.2rem 0.5rem;
        font-size: 0.68rem;
        font-weight: 600;
        color: #64748b;
    }

    .ur-mc-bt-item {
        display: inline-flex;
        align-items: center;
        gap: 0.22rem;
    }

    .ur-mc-bt-item i {
        color: #059669;
        font-size: 0.78rem;
    }

    .ur-mc-bt-dot {
        color: #cbd5e1;
        font-size: 0.6rem;
    }
}

@media (max-width: 370px) {
    .ur-hps-mobile-card {
        padding: 1rem 0.85rem 0.95rem !important;
    }
    .ur-mc-title {
        font-size: 1.25rem;
    }
    .ur-mc-price-val {
        font-size: 1.35rem;
    }
    .ur-mc-cta-action {
        padding: 0.55rem 0.85rem;
        font-size: 0.78rem;
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
            <div class="ur-hps-top-left">
                <span class="ur-hps-top-heading">
                    <i class="ph-fill ph-shield-check"></i>
                    Zero Brokerage Direct Pass
                </span>
            </div>

            <div class="ur-hps-top-right">
                {{-- Quick Plan Switcher Tabs --}}
                <div class="ur-hps-tabs-bar-wrap">
                    <div class="ur-hps-tabs-bar" role="tablist" aria-label="Plans quick navigation">
                        @foreach($heroSlides as $idx => $s)
                            <button type="button" 
                                    class="ur-hps-tab-btn {{ $idx === 0 ? 'active tab--' . $s['theme'] : '' }} {{ !empty($s['has_offer']) ? 'ur-hps-tab-btn--has-offer' : '' }}" 
                                    data-target-index="{{ $idx }}"
                                    data-theme="{{ $s['theme'] }}"
                                    data-has-offer="{{ !empty($s['has_offer']) ? 'true' : 'false' }}"
                                    role="tab"
                                    aria-selected="{{ $idx === 0 ? 'true' : 'false' }}"
                                    title="{{ $s['tab_label'] }}">
                                <i class="ph-bold {{ $s['tab_icon'] }}"></i>
                                <span>{{ $s['tab_label'] }}</span>
                                @if(!empty($s['tab_badge']))
                                    <span class="ur-hps-tab-pill {{ !empty($s['has_offer']) ? 'ur-hps-tab-pill--offer' : '' }}">{{ $s['tab_badge'] }}</span>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Clean Top Slider Navigation Controls --}}
                <div class="ur-hps-nav-arrows">
                    <button type="button" class="ur-hps-arrow-btn" id="heroPlanPrevBtn" onclick="if(window.urPrevHeroSlide){ window.urPrevHeroSlide(); }" aria-label="Previous Plan" title="Previous Plan">
                        <i class="ph-bold ph-caret-left"></i>
                    </button>
                    <button type="button" class="ur-hps-arrow-btn" id="heroPlanNextBtn" onclick="if(window.urNextHeroSlide){ window.urNextHeroSlide(); }" aria-label="Next Plan" title="Next Plan">
                        <i class="ph-bold ph-caret-right"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- Slider Stage --}}
        <div class="ur-hps-slider-stage" id="heroPlanSliderStage">
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

                    <div class="ur-hps-slide ur-hps-slide--{{ $s['theme'] }} {{ !empty($s['has_offer']) ? 'ur-hps-slide--has-offer' : '' }}" data-slide-index="{{ $idx }}" data-theme="{{ $s['theme'] }}" data-has-offer="{{ !empty($s['has_offer']) ? 'true' : 'false' }}">
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

                        {{-- Photographic Real Estate Backdrop (Desktop) --}}
                        <div class="ur-hps-desktop-backdrop" aria-hidden="true">
                            <img src="{{ $s['visual']['image'] }}" 
                                 alt="" 
                                 loading="lazy" 
                                 decoding="async"
                                 onerror="if(!this.dataset.triedFallback){ this.dataset.triedFallback='1'; this.src='{{ $s['visual']['fallback'] ?? '' }}'; }">
                        </div>

                        <div class="ur-hps-desktop-grid">
                            
                            {{-- PART 1: LEFT COLUMN (Value Proposition & Compact Benefit Pills) --}}
                            <div class="ur-hps-col-left">
                                <span class="ur-hps-plan-badge ur-hps-plan-badge--{{ $s['theme'] }} {{ !empty($s['has_offer']) ? 'ur-hps-plan-badge--offer' : '' }}">
                                    @if(!empty($s['has_offer']))
                                        <i class="ph-fill ph-lightning"></i> Special Offer Unlocked · ₹{{ number_format($s['price'], 0) }} Pass
                                    @else
                                        {!! $s['badge'] !!}
                                    @endif
                                </span>

                                <h3 class="ur-hps-slide-title">
                                    {{ $s['title_prefix'] }} <br>
                                    <span class="highlight-{{ $s['theme'] }}">{{ $s['title_highlight'] }}</span>
                                </h3>

                                <p class="ur-hps-slide-desc">
                                    {{ $s['tagline'] }}
                                </p>

                                {{-- Compact 2x2 Feature Pills --}}
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

                                {{-- Direct Owner Guarantee Strip --}}
                                <div class="ur-hps-guarantee-strip">
                                    <span><i class="ph-bold ph-shield-check"></i> 100% Verified Direct Owners</span>
                                    <span><i class="ph-bold ph-seal-check"></i> ₹0 Brokerage Guaranteed</span>
                                </div>
                            </div>

                            {{-- PART 2: RIGHT COLUMN (Dedicated High-Conversion Pass Card) --}}
                            <div class="ur-hps-col-right">
                                <div class="ur-hps-pass-card ur-hps-pass-card--{{ $s['theme'] }}">
                                    {{-- Card Header with Plan Identity --}}
                                    <div class="ur-hps-card-header">
                                        <div class="ur-hps-card-title-group">
                                            <span class="ur-hps-card-tier-pill ur-hps-card-tier-pill--{{ $s['theme'] }}">
                                                <i class="ph-fill {{ $s['theme'] === 'gold' ? 'ph-crown' : ($s['theme'] === 'platinum' ? 'ph-sparkle' : ($s['theme'] === 'buyer' ? 'ph-buildings' : 'ph-shield-check')) }}"></i>
                                                {{ $s['tab_label'] }}
                                            </span>
                                            <span class="ur-hps-card-brand-sub">Direct Pass</span>
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
                                            <span class="ur-hps-save-chip {{ !empty($s['has_offer']) ? 'ur-hps-save-chip--offer' : '' }}">
                                                <i class="ph-bold ph-seal-percent"></i> SAVE {{ $s['savings_pct'] }}%
                                            </span>
                                        </div>

                                        <div class="ur-hps-price-main">
                                            <span class="ur-hps-currency">₹</span>
                                            <span class="ur-hps-amount">{{ number_format($s['price'], 0) }}</span>
                                            <span class="ur-hps-period">/ {{ $s['type'] === 'buy' ? 'annual pass' : 'pass' }}</span>
                                        </div>

                                        <div class="ur-hps-price-subtext">
                                            @if(!empty($s['has_offer']))
                                                <i class="ph-bold ph-lightning" style="color: #10b981;"></i>
                                                <span>Exclusive Price ₹{{ number_format($s['price'], 0) }} · {{ $s['specs'][1]['value'] ?? ($plan->duration_days ?? 60) . ' Days' }} Validity</span>
                                            @else
                                                <i class="ph-bold ph-seal-check"></i>
                                                <span>Only ₹{{ $s['per_day'] }}/day · {{ $s['specs'][1]['value'] ?? ($plan->duration_days ?? 150) . ' Days' }} Validity</span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Key Specs: Horizontal 3-Column Strip --}}
                                    <div class="ur-hps-card-meta">
                                        @foreach($s['specs'] as $spec)
                                            <div class="ur-hps-meta-row">
                                                <span class="meta-label">
                                                    <i class="ph-bold {{ str_contains(strtolower($spec['label']), 'contact') || str_contains(strtolower($spec['label']), 'seller') ? 'ph-phone-call' : (str_contains(strtolower($spec['label']), 'duration') || str_contains(strtolower($spec['label']), 'validity') ? 'ph-calendar-check' : 'ph-shield-check') }}"></i> {{ $spec['label'] }}
                                                </span>
                                                <span class="meta-value {{ !empty($spec['accent']) ? 'meta-accent' : '' }}">
                                                    {{ $spec['value'] }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>

                                    {{-- High-Impact Checkout CTA Button --}}
                                    @guest
                                        <a href="{{ route('login', ['redirect' => $checkoutUrl]) }}" 
                                           onclick="event.preventDefault(); event.stopPropagation(); if(window.openAuthModal) { window.openAuthModal('login', '{{ $checkoutUrl }}'); } else { window.location.href='{{ route('login', ['redirect' => $checkoutUrl]) }}'; }"
                                           class="ur-hps-cta-btn ur-hps-cta-btn--{{ $s['theme'] }} {{ !empty($s['has_offer']) ? 'ur-hps-cta-btn--offer' : '' }}" 
                                           title="Unlock Verified Contacts">
                                            <i class="ph-fill ph-lightning"></i>
                                            <span>Unlock Contacts Now · ₹{{ number_format($s['price'], 0) }}</span>
                                        </a>
                                    @else
                                        <a href="{{ $checkoutUrl }}" 
                                           class="ur-hps-cta-btn ur-hps-cta-btn--{{ $s['theme'] }} {{ !empty($s['has_offer']) ? 'ur-hps-cta-btn--offer' : '' }}" 
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

                            {{-- PART 3: HORIZONTAL TRUST ROW (Spans full width underneath) --}}
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

                        {{-- ============================================================
                             SEPARATE 16:9 ULTRA-COMPACT MOBILE CARD (VISIBLE ON MOBILE ONLY)
                             Reduced height in 16:9 slider format, sleek real-estate backdrop,
                             bold price, key perks, and thumb-friendly checkout CTA button.
                             ============================================================ --}}
                        <div class="ur-hps-mobile-card ur-hps-mobile-card--{{ $s['theme'] }} {{ !empty($s['has_offer']) ? 'ur-hps-mobile-card--offer' : '' }}">
                            {{-- Subtle Photographic Real Estate Backdrop with High-Fidelity Glassmorphism --}}
                            <div class="ur-mc-backdrop" aria-hidden="true">
                                <img src="{{ $s['visual']['image'] }}" 
                                     alt="{{ $s['visual']['title'] }}" 
                                     class="ur-mc-backdrop-img" 
                                     loading="lazy" 
                                     decoding="async"
                                     onerror="if(!this.dataset.triedFallback){ this.dataset.triedFallback='1'; this.src='{{ $s['visual']['fallback'] ?? '' }}'; }">
                                <div class="ur-mc-scrim"></div>
                            </div>

                            {{-- 1. CONSOLIDATED HEADER ROW: Warm Discount Highlight on Left, Clean Status on Right --}}
                            <div class="ur-mc-header-row">
                                <div class="ur-mc-discount-pill">
                                    <span class="ur-mc-spark-dot"></span>
                                    @if(!empty($s['has_offer']))
                                        <span class="ur-mc-discount-text">Special Offer · Save {{ $s['savings_pct'] }}%</span>
                                    @else
                                        <span class="ur-mc-discount-text">{{ $s['tab_badge'] ?? 'Verified Pass' }} · Save {{ $s['savings_pct'] }}%</span>
                                    @endif
                                </div>
                                <div class="ur-mc-status-pill">
                                    <i class="ph-bold ph-shield-check"></i>
                                    <span>{{ !empty($s['has_offer']) ? 'Unlocked For You' : ($s['visual']['tag'] ?? '100% Direct') }}</span>
                                </div>
                            </div>

                            {{-- 2. MAIN PLAN TITLE & SUBTITLE HIERARCHY --}}
                            <div class="ur-mc-title-section">
                                <h4 class="ur-mc-title">
                                    {{ $s['title_highlight'] }}
                                </h4>
                                <p class="ur-mc-subtitle">
                                    {{ $s['specs'][0]['value'] }} · {{ $s['specs'][1]['value'] }} · ₹0 Commission
                                </p>
                            </div>

                            {{-- 3. REDESIGNED FEATURE HIGHLIGHTS: Clean 2-column grid with soft green icon containers (NOT buttons) --}}
                            <div class="ur-mc-features-grid">
                                @foreach(array_slice($s['highlights'], 0, 2) as $f)
                                    <div class="ur-mc-feature-card">
                                        <div class="ur-mc-f-icon">
                                            <i class="ph-bold {{ $f['icon'] }}"></i>
                                        </div>
                                        <div class="ur-mc-f-info">
                                            <span class="ur-mc-f-title">{{ $f['title'] }}</span>
                                            @if(!empty($f['desc']))
                                                <span class="ur-mc-f-desc">{{ $f['desc'] }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- 4. PRICING & CTA DOCK: Subtly Contrasted Bottom Container --}}
                            @php
                                $curPrice = (float) $s['price'];
                                $origPrice = (float) ($s['original_price'] ?? 0);
                                if ($origPrice <= $curPrice) {
                                    $origPrice = round(max($curPrice * 2.2, $curPrice + 500));
                                }
                                $showOrig = ($origPrice > $curPrice);
                                $ctaText = !empty($s['has_offer']) 
                                    ? ('Unlock for ₹' . number_format($curPrice, 0) . ' →') 
                                    : ('Unlock Pass →');
                            @endphp

                            <div class="ur-mc-pricing-dock">
                                <div class="ur-mc-price-group">
                                    <div class="ur-mc-price-row">
                                        <span class="ur-mc-currency">₹</span>
                                        <span class="ur-mc-price-val">{{ number_format($curPrice, 0) }}</span>
                                        @if($showOrig)
                                            <span class="ur-mc-price-orig">₹{{ number_format($origPrice, 0) }}</span>
                                        @endif
                                    </div>
                                    <div class="ur-mc-price-caption">
                                        <i class="ph-bold ph-lightning"></i>
                                        @if(!empty($s['has_offer']))
                                            <span>Special ₹{{ number_format($curPrice, 0) }} · {{ $s['specs'][1]['value'] }}</span>
                                        @else
                                            <span>Only ₹{{ $s['per_day'] }}/day · Direct</span>
                                        @endif
                                    </div>
                                </div>

                                @guest
                                    <a href="{{ route('login', ['redirect' => $checkoutUrl]) }}" 
                                       onclick="event.preventDefault(); event.stopPropagation(); if(window.openAuthModal) { window.openAuthModal('login', '{{ $checkoutUrl }}'); } else { window.location.href='{{ route('login', ['redirect' => $checkoutUrl]) }}'; }"
                                       class="ur-mc-cta-action" 
                                       title="Unlock Verified Contacts">
                                        <span>{{ $ctaText }}</span>
                                    </a>
                                @else
                                    <a href="{{ $checkoutUrl }}" 
                                       class="ur-mc-cta-action" 
                                       title="Unlock Verified Contacts">
                                        <span>{{ $ctaText }}</span>
                                    </a>
                                @endguest
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>

        {{-- Bottom Indicator Dots --}}
        <div class="ur-hps-bottom-bar">
            <div class="ur-hps-dots" id="heroPlanDots">
                @foreach($heroSlides as $idx => $s)
                    <button type="button" 
                            class="ur-hps-dot {{ $idx === 0 ? 'active' : '' }}" 
                            data-index="{{ $idx }}" 
                            onclick="if(window.urGoToHeroSlide){ window.urGoToHeroSlide({{ $idx }}); }"
                            aria-label="Slide {{ $idx + 1 }}" 
                            title="{{ $s['tab_label'] }}"></button>
                @endforeach
            </div>
        </div>

        {{-- Mobile Single-Line Refined Trust Strip with Muted Typography & Subtle Emerald Icons --}}
        <div class="ur-mc-bottom-trust">
            <div class="ur-mc-bt-item"><i class="ph-fill ph-lightning"></i> <span>30s Activation</span></div>
            <span class="ur-mc-bt-dot">•</span>
            <div class="ur-mc-bt-item"><i class="ph-fill ph-shield-check"></i> <span>Verified Owners</span></div>
            <span class="ur-mc-bt-dot">•</span>
            <div class="ur-mc-bt-item"><i class="ph-fill ph-lock-key"></i> <span>Safe UPI</span></div>
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

        if (window.scrollX > 0) {
            window.scrollTo({ left: 0 });
        }
        if (document.documentElement && document.documentElement.scrollLeft > 0) {
            document.documentElement.scrollLeft = 0;
        }

        const slides = track.querySelectorAll('.ur-hps-slide');
        const totalSlides = slides.length;
        if (totalSlides <= 1) return;

        let currentIndex = 0;
        let autoSlideTimer = null;
        let resumeTimer = null;

        // Theme colors for background glow orbs
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

        function goToSlide(index, instant) {
            if (index < 0) index = totalSlides - 1;
            if (index >= totalSlides) index = 0;
            currentIndex = index;

            if (instant) {
                track.style.transition = 'none';
            } else {
                track.style.transition = 'transform 0.55s cubic-bezier(0.22, 1, 0.36, 1)';
            }

            // Reliable 100% relative offset translation
            track.style.transform = `translateX(-${currentIndex * 100}%)`;

            if (instant) {
                void track.offsetWidth; // Force reflow
                track.style.transition = 'transform 0.55s cubic-bezier(0.22, 1, 0.36, 1)';
            }

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

            // If tabs bar is horizontally scrollable (only on mobile/tablet view), scroll ONLY the tabs bar container
            try {
                const tabsBar = document.querySelector('.ur-hps-tabs-bar');
                const activeTab = tabs[currentIndex];
                if (tabsBar && activeTab && tabsBar.scrollWidth > tabsBar.clientWidth) {
                    const scrollLeftTarget = activeTab.offsetLeft - (tabsBar.clientWidth / 2) + (activeTab.offsetWidth / 2);
                    tabsBar.scrollTo({
                        left: Math.max(0, scrollLeftTarget),
                        behavior: 'smooth'
                    });
                }
            } catch (e) {}

            // Guard: ensure the document window horizontal scroll is never offset to the right
            if (window.scrollX > 0 || (document.documentElement && document.documentElement.scrollLeft > 0) || (document.body && document.body.scrollLeft > 0)) {
                window.scrollTo({ left: 0 });
                if (document.documentElement) document.documentElement.scrollLeft = 0;
                if (document.body) document.body.scrollLeft = 0;
            }

            // Adjust ambient glow orbs to match current slide's theme
            if (curSlide) {
                const theme = curSlide.getAttribute('data-theme') || 'gold';
                if (themeGlows[theme]) {
                    if (orb1) orb1.style.background = themeGlows[theme].orb1;
                    if (orb2) orb2.style.background = themeGlows[theme].orb2;
                }
            }
        }

        function nextSlide() {
            goToSlide((currentIndex + 1) % totalSlides);
        }

        function prevSlide() {
            goToSlide((currentIndex - 1 + totalSlides) % totalSlides);
        }

        function startAutoPlay() {
            stopAutoPlay();
            autoSlideTimer = setInterval(() => {
                if (document.visibilityState !== 'hidden') {
                    nextSlide();
                }
            }, 3500); // Smooth continuous 3.5s auto slide
        }

        function stopAutoPlay() {
            if (autoSlideTimer) {
                clearInterval(autoSlideTimer);
                autoSlideTimer = null;
            }
        }

        function restartAutoPlay() {
            stopAutoPlay();
            if (resumeTimer) clearTimeout(resumeTimer);
            resumeTimer = setTimeout(() => {
                startAutoPlay();
            }, 3500);
        }

        // Export global controller functions for instant inline onclick support
        window.urGoToHeroSlide = function(idx) {
            goToSlide(idx);
            restartAutoPlay();
        };
        window.urNextHeroSlide = function() {
            nextSlide();
            restartAutoPlay();
        };
        window.urPrevHeroSlide = function() {
            prevSlide();
            restartAutoPlay();
        };

        function userInteracted() {
            stopAutoPlay();
            if (resumeTimer) clearTimeout(resumeTimer);
            resumeTimer = setTimeout(() => {
                startAutoPlay();
            }, 4000);
        }

        // Arrow Listeners
        prevBtn?.addEventListener('click', (e) => {
            e.preventDefault();
            prevSlide();
            userInteracted();
        });

        nextBtn?.addEventListener('click', (e) => {
            e.preventDefault();
            nextSlide();
            userInteracted();
        });

        // Tab Listeners - Instant Single-Touch Response
        tabs.forEach((tab) => {
            tab.addEventListener('click', (e) => {
                e.preventDefault();
                const targetIdx = parseInt(tab.getAttribute('data-target-index') || '0', 10);
                goToSlide(targetIdx);
                userInteracted();
            });
            tab.addEventListener('pointerdown', () => {
                tab.style.transform = 'scale(0.96)';
            }, { passive: true });
            tab.addEventListener('pointerup', () => {
                tab.style.transform = '';
            }, { passive: true });
            tab.addEventListener('pointercancel', () => {
                tab.style.transform = '';
            }, { passive: true });
        });

        // Dot Listeners
        dots.forEach((dot) => {
            dot.addEventListener('click', (e) => {
                e.preventDefault();
                const targetIdx = parseInt(dot.getAttribute('data-index') || '0', 10);
                goToSlide(targetIdx);
                userInteracted();
            });
        });

        // Pause on Desktop Hover and resume immediately on leave
        stage.addEventListener('mouseenter', () => stopAutoPlay());
        stage.addEventListener('mouseleave', () => startAutoPlay());

        // Recalculate position on window resize
        window.addEventListener('resize', () => {
            goToSlide(currentIndex, true);
        }, { passive: true });

        // Pause on tab visibility change
        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'hidden') {
                stopAutoPlay();
            } else {
                startAutoPlay();
            }
        });

        // Touch & Swipe Support for Mobile & Tablets - Zero Latency & Scroll Safe
        let touchStartX = 0;
        let touchEndX = 0;
        let touchStartY = 0;
        let touchEndY = 0;

        stage.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
            touchStartY = e.changedTouches[0].screenY;
            userInteracted();
        }, { passive: true });

        stage.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            touchEndY = e.changedTouches[0].screenY;
            const diffX = touchEndX - touchStartX;
            const diffY = touchEndY - touchStartY;
            // Only trigger slide change if swipe is decisively horizontal (not a vertical scroll gesture)
            if (Math.abs(diffX) > 40 && Math.abs(diffX) > Math.abs(diffY) * 1.5) {
                if (diffX < 0) {
                    nextSlide();
                } else {
                    prevSlide();
                }
            }
        }, { passive: true });

        // Initial Slide Selection
        let initialIndex = 0;
        try {
            // Priority 1: Check if any slide has an active user offer!
            const offerIdx = Array.from(slides).findIndex(s => s.getAttribute('data-has-offer') === 'true');
            if (offerIdx >= 0) {
                initialIndex = offerIdx;
            } else {
                const urlParams = new URLSearchParams(window.location.search);
                const hash = window.location.hash.toLowerCase();
                if (urlParams.get('tab') === 'buyer' || urlParams.get('plan') === 'buyer' || urlParams.get('type') === 'buy' || hash === '#buyer-pass' || hash === '#buyer' || hash === '#direct-buyer-pass') {
                    const buyerIdx = Array.from(slides).findIndex(s => s.getAttribute('data-theme') === 'buyer');
                    if (buyerIdx >= 0) initialIndex = buyerIdx;
                }
            }
        } catch (e) {}

        goToSlide(initialIndex, true);

        if ('IntersectionObserver' in window) {
            const hpsObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        startAutoPlay();
                    } else {
                        stopAutoPlay();
                    }
                });
            }, { threshold: 0.2 });
            hpsObserver.observe(stage);
        } else {
            startAutoPlay();
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initHeroPlanSlider);
    } else {
        initHeroPlanSlider();
    }
})();
</script>
