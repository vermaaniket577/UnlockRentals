@php
    try {
        $allDirectPlans = \App\Models\Plan::query()
            ->where(function ($q) {
                $q->where('is_active', 1)->orWhere('is_active', true)->orWhereNull('is_active');
            })
            ->where(function ($q) {
                $q->where('is_private', 0)->orWhere('is_private', false)->orWhereNull('is_private');
            })
            ->orderBy('sort_order')
            ->orderBy('price')
            ->get();

        $allDirectPlans = $allDirectPlans->sortBy(function ($p) {
            $pRank = $p->isRentPlan() ? 1 : ($p->purpose === 'both' ? 2 : 3);
            return sprintf('%d_%04d_%08.2f', $pRank, $p->sort_order ?? 0, (float) ($p->price ?? 0));
        })->values();
    } catch (\Throwable $e) {
        $allDirectPlans = collect();
    }

    $hasRentPlans = $allDirectPlans->contains(fn($p) => $p->isRentPlan());
    $hasBuyPlans = $allDirectPlans->contains(fn($p) => $p->isBuyPlan());

    $defaultPurpose = 'all';
    if (in_array(request('purpose'), ['buy', 'sale']) && $hasBuyPlans) {
        $defaultPurpose = 'buy';
    } elseif (request('purpose') === 'rent' && $hasRentPlans) {
        $defaultPurpose = 'rent';
    }
@endphp

@if($allDirectPlans->isNotEmpty())
<section class="ur-direct-passes-section py-3 sm:py-5" id="hero-direct-passes">
    <div class="max-w-7xl mx-auto px-3.5 sm:px-6 lg:px-8">
        
        {{-- Section Header with Purpose Filter Tabs & Slider Controls --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 mb-2.5 sm:mb-3">
            <div>
                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 text-[10px] font-bold uppercase tracking-wider mb-1">
                    <i class="ph-bold ph-shield-check text-xs"></i>
                    <span>Zero Brokerage Guaranteed</span>
                </div>
                <h3 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white tracking-tight">
                    Direct Owner Contact Passes
                </h3>
            </div>

            <div class="flex items-center justify-between sm:justify-end gap-2 w-full sm:w-auto">
                {{-- Filter Tabs: All Plans / Tenant Pass (Rent) / Buyer Pass (Buy) --}}
                @if($hasRentPlans && $hasBuyPlans)
                <div class="inline-flex items-center p-0.5 bg-slate-100 dark:bg-slate-800/80 rounded-lg border border-slate-200 dark:border-slate-700/60" id="urDirectPassFilter">
                    <button type="button" onclick="filterDirectPassCards('all', this)" class="ur-dp-tab {{ $defaultPurpose === 'all' ? 'active bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white' }} px-2.5 sm:px-3 py-1 text-[11px] sm:text-xs font-semibold rounded-md transition-all cursor-pointer whitespace-nowrap">
                        All Plans
                    </button>
                    <button type="button" onclick="filterDirectPassCards('rent', this)" class="ur-dp-tab {{ $defaultPurpose === 'rent' ? 'active bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white' }} px-2.5 sm:px-3 py-1 text-[11px] sm:text-xs font-semibold rounded-md transition-all cursor-pointer whitespace-nowrap">
                        Tenant Pass
                    </button>
                    <button type="button" onclick="filterDirectPassCards('buy', this)" class="ur-dp-tab {{ $defaultPurpose === 'buy' ? 'active bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white' }} px-2.5 sm:px-3 py-1 text-[11px] sm:text-xs font-semibold rounded-md transition-all cursor-pointer whitespace-nowrap">
                        Buyer Pass
                    </button>
                </div>
                @endif

                {{-- Interactive Slider Navigation Chevrons --}}
                <div class="flex items-center gap-1" id="urDirectSliderArrows">
                    <button type="button" onclick="slideDirectPasses(-1)" class="w-7 h-7 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 flex items-center justify-center transition-all active:scale-90 shadow-xs cursor-pointer" title="Previous Pass" aria-label="Previous Pass">
                        <i class="ph-bold ph-caret-left text-xs"></i>
                    </button>
                    <button type="button" onclick="slideDirectPasses(1)" class="w-7 h-7 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 flex items-center justify-center transition-all active:scale-90 shadow-xs cursor-pointer" title="Next Pass" aria-label="Next Pass">
                        <i class="ph-bold ph-caret-right text-xs"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- Cards Container: Responsive Horizontal Single Card Slider on Mobile & Website --}}
        <div class="ur-direct-slider-viewport relative w-full" id="urDirectSliderViewport">
            <div class="ur-direct-cards-wrapper flex overflow-x-auto snap-x snap-mandatory no-scrollbar pb-2 sm:pb-3 scroll-smooth" id="urDirectCardsGrid">
                @foreach($allDirectPlans as $idx => $plan)
                    @php
                        $isBuy = $plan->isBuyPlan();
                        $isRent = $plan->isRentPlan();
                        $rawPurpose = $plan->purpose ?? ($isBuy ? 'buy' : 'rent');
                        $dataPurpose = ($rawPurpose === 'both') ? 'rent buy' : $rawPurpose;
                        $matchesDefault = ($defaultPurpose === 'all') 
                            || ($defaultPurpose === 'rent' && $isRent) 
                            || ($defaultPurpose === 'buy' && $isBuy);
                        
                        // Plan Name & Tier Badge from Admin Panel
                        $badgeText = strtoupper($plan->name);

                        // Mint badge text indicating Buyer Pass or Tenant Pass
                        if ($rawPurpose === 'buy') {
                            $mintText = 'BUYER PASS';
                        } elseif ($rawPurpose === 'rent') {
                            $mintText = 'TENANT PASS';
                        } else {
                            $mintText = 'BUYER & TENANT PASS';
                        }

                        // Estimated brokerage savings text
                        if ($isBuy) {
                            $savingsText = $plan->price >= 1500 ? 'Save ₹2,50,000+ Average' : ($plan->price >= 700 ? 'Save ₹1,50,000+ Average' : 'Save ₹75,000+ Average');
                        } elseif ($plan->price >= 900) {
                            $savingsText = 'Save ₹1,00,000+ Average';
                        } elseif ($plan->price >= 400) {
                            $savingsText = 'Save ₹60,000+ Average';
                        } else {
                            $savingsText = 'Save ₹35,000+ Average';
                        }

                        $checkoutUrl = Route::has('plans.checkout') 
                            ? route('plans.checkout', ['plan' => $plan->id, 'direct' => 1]) 
                            : url('/plans');
                    @endphp

                    <div class="ur-direct-card-item flex-shrink-0 snap-center" 
                         data-purpose="{{ $dataPurpose }}" 
                         data-card-index="{{ $idx }}"
                         @if(!$matchesDefault) style="display: none;" @endif>
                        <div class="ur-direct-card">
                            
                            {{-- Left / Main Info Block --}}
                            <div class="ur-dc-main">
                                {{-- Top Badges Row --}}
                                <div class="ur-dc-badges">
                                    <span class="ur-dc-mint-pill">
                                        <i class="ph-bold ph-shield-check"></i>
                                        <span>{{ $mintText }}</span>
                                    </span>
                                    <span class="ur-dc-gold-badge">
                                        <i class="ph-fill ph-seal-check"></i>
                                        <span>{{ $badgeText }}</span>
                                    </span>
                                    @if(!empty($plan->duration_days))
                                        <span class="ur-dc-duration-pill">
                                            <i class="ph-bold ph-calendar"></i>
                                            <span>{{ $plan->duration_days }} Days Validity</span>
                                        </span>
                                    @endif
                                </div>

                                {{-- Main Title & Subtitle directly displaying plan details from Admin Panel --}}
                                @php
                                    $contactsTarget = $isBuy ? 'Sellers' : 'Landlords';
                                    $displayTitle = str_contains(strtolower($plan->name), 'unlock') 
                                        ? $plan->name 
                                        : ($plan->name . ' &ndash; Unlock ' . $plan->contact_limit . '+ Direct ' . $contactsTarget);
                                @endphp
                                <h4 class="ur-dc-title">
                                    {!! $displayTitle !!}
                                </h4>
                                <p class="ur-dc-sub">
                                    {{ $plan->description ?: ($isBuy ? 'Skip brokerage charges completely. Instant phone & WhatsApp contact numbers directly from verified property sellers.' : 'Skip brokerage charges completely. Instant phone & WhatsApp contact numbers directly from verified property owners.') }}
                                </p>

                                {{-- Feature Highlights Row (visible on desktop / tablet) --}}
                                @php
                                    $adminFeatures = !empty($plan->features) && is_array($plan->features) 
                                        ? array_values(array_filter(array_map('trim', $plan->features))) 
                                        : [];
                                    
                                    if (!empty($adminFeatures)) {
                                        $displayFeatures = $adminFeatures;
                                    } else {
                                        $displayFeatures = [
                                            $plan->contact_limit . '+ Verified ' . ($isBuy ? 'Seller' : 'Owner') . ' Direct Contacts',
                                            'Direct Phone & WhatsApp Unlock',
                                            'Zero Brokerage Guaranteed',
                                            $plan->duration_days . ' Days Priority ' . ($isBuy ? 'Buyer' : 'Tenant') . ' Access',
                                        ];
                                    }
                                @endphp
                                <div class="ur-dc-features-row">
                                    @foreach(array_slice($displayFeatures, 0, 4) as $fIdx => $feat)
                                        <span class="ur-dc-feat-tag">
                                            @if($fIdx === 0)
                                                <i class="ph-bold ph-phone-call text-emerald-400"></i>
                                            @elseif($fIdx === 1)
                                                <i class="ph-bold ph-shield-check text-emerald-400"></i>
                                            @elseif($fIdx === 2)
                                                <i class="ph-bold ph-seal-check text-amber-400"></i>
                                            @else
                                                <i class="ph-bold ph-calendar text-sky-400"></i>
                                            @endif
                                            <span>{{ $feat }}</span>
                                        </span>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Right Side / Footer Dock: Price & Unlock CTA --}}
                            <div class="ur-dc-footer">
                                <div class="ur-dc-price-group">
                                    <div class="ur-dc-price-row">
                                        <span class="ur-dc-val">₹{{ number_format($plan->price, 0) }}</span>
                                        @if($plan->original_price && (float) $plan->original_price > (float) $plan->price)
                                            <span class="ur-dc-orig">₹{{ number_format($plan->original_price, 0) }}</span>
                                        @endif
                                    </div>
                                    <span class="ur-dc-save">{{ $savingsText }}</span>
                                </div>

                                <div class="ur-dc-cta-group w-full md:w-auto">
                                    @guest
                                        <a href="{{ route('login', ['redirect' => $checkoutUrl]) }}" 
                                           onclick="event.preventDefault(); event.stopPropagation(); if(window.openAuthModal) { window.openAuthModal('login', '{{ $checkoutUrl }}'); } else { window.location.href='{{ route('login', ['redirect' => $checkoutUrl]) }}'; }"
                                           class="ur-dc-btn" 
                                           title="Unlock Direct Contacts">
                                            <span>Unlock Now</span>
                                            <i class="ph-bold ph-arrow-right"></i>
                                        </a>
                                    @else
                                        <a href="{{ $checkoutUrl }}" 
                                           class="ur-dc-btn" 
                                           title="Unlock Direct Contacts">
                                            <span>Unlock Now</span>
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

        {{-- Slider Dot Indicators --}}
        @php
            $initialMatchingPlans = $allDirectPlans->filter(function($p) use ($defaultPurpose) {
                if ($defaultPurpose === 'all') return true;
                if ($defaultPurpose === 'buy') return $p->isBuyPlan();
                return $p->isRentPlan();
            })->values();
        @endphp
        <div class="flex items-center justify-center gap-1.5 mt-2 sm:mt-3" id="urDirectSliderDots" style="{{ $initialMatchingPlans->count() <= 1 ? 'display: none;' : '' }}">
            @foreach($initialMatchingPlans as $idx => $p)
                <button type="button" onclick="scrollDirectPassToIndex({{ $idx }}); resetDirectPassAutoTimer();" class="ur-ds-dot {{ $idx === 0 ? 'active' : '' }}" aria-label="Go to pass {{ $idx + 1 }}"></button>
            @endforeach
        </div>

    </div>
</section>

<style>
/* ─── DIRECT OWNER PASS CARDS (HORIZONTAL SINGLE CARD SLIDER) ─── */
.ur-direct-slider-viewport {
    width: 100%;
    position: relative;
    box-sizing: border-box;
}

.ur-direct-cards-wrapper {
    position: relative;
    display: flex;
    overflow-x: auto;
    scroll-snap-type: x mandatory;
    -webkit-overflow-scrolling: touch;
    touch-action: pan-x pan-y;
    scroll-behavior: smooth;
    padding-bottom: 0.35rem;
    width: 100%;
    box-sizing: border-box;
    gap: 0;
}

.ur-direct-card-item {
    flex: 0 0 100%;
    width: 100%;
    max-width: 100%;
    scroll-snap-align: start;
    box-sizing: border-box;
    padding: 0 2px;
}

.ur-direct-card {
    position: relative;
    width: 100%;
    box-sizing: border-box;
    background: radial-gradient(130% 120% at 85% 20%, #0d1e3d 0%, #071328 65%, #050c1a 100%);
    border: 1px solid rgba(255, 255, 255, 0.14);
    border-radius: 16px;
    padding: 1rem 1.15rem;
    box-shadow: 0 12px 28px -6px rgba(0, 0, 0, 0.45), 0 0 0 1px rgba(255, 255, 255, 0.05);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    gap: 0.85rem;
    color: #ffffff;
    font-family: 'Outfit', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s ease;
    min-height: auto;
}

.ur-direct-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 18px 36px -8px rgba(0, 0, 0, 0.65), 0 0 0 1px rgba(255, 255, 255, 0.1);
}

.ur-dc-main {
    display: flex;
    flex-direction: column;
    min-width: 0;
    flex: 1 1 auto;
}

.ur-dc-badges {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    margin-bottom: 0.45rem;
    flex-wrap: wrap;
}

.ur-dc-mint-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.22rem;
    padding: 0.16rem 0.48rem;
    border-radius: 9999px;
    border: 1.5px solid #10b981;
    background: #ecfdf5;
    color: #047857;
    font-size: 0.6rem;
    font-weight: 750;
    letter-spacing: 0.025em;
    text-transform: uppercase;
    line-height: 1.2;
    white-space: nowrap;
    flex-shrink: 0;
}

.ur-dc-mint-pill i {
    font-size: 0.7rem;
    color: #10b981;
}

.ur-dc-gold-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.22rem;
    color: #fbbf24;
    font-size: 0.64rem;
    font-weight: 750;
    letter-spacing: 0.025em;
    text-transform: uppercase;
    line-height: 1.2;
    white-space: nowrap;
    flex-shrink: 0;
}

.ur-dc-gold-badge i {
    font-size: 0.78rem;
    color: #fbbf24;
}

.ur-dc-duration-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.22rem;
    padding: 0.16rem 0.48rem;
    border-radius: 9999px;
    background: rgba(255, 255, 255, 0.07);
    border: 1px solid rgba(255, 255, 255, 0.12);
    color: #cbd5e1;
    font-size: 0.6rem;
    font-weight: 650;
    letter-spacing: 0.02em;
    white-space: nowrap;
    flex-shrink: 0;
}

.ur-dc-title {
    font-size: 1.05rem;
    font-weight: 700;
    color: #ffffff;
    line-height: 1.3;
    letter-spacing: -0.015em;
    margin: 0 0 0.22rem 0;
    font-family: 'Outfit', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}

.ur-dc-sub {
    font-size: 0.76rem;
    font-weight: 400;
    color: #94a3b8;
    line-height: 1.4;
    margin: 0;
}

.ur-dc-features-row {
    display: none;
    flex-wrap: wrap;
    align-items: center;
    gap: 0.35rem;
    margin-top: 0.55rem;
}

.ur-dc-feat-tag {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.16rem 0.48rem;
    border-radius: 6px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.09);
    color: #e2e8f0;
    font-size: 0.66rem;
    font-weight: 600;
}

.ur-dc-footer {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 0.6rem;
    margin-top: auto;
    padding-top: 0.2rem;
}

.ur-dc-price-group {
    display: flex;
    flex-direction: column;
    min-width: 0;
}

.ur-dc-price-row {
    display: flex;
    align-items: baseline;
    gap: 0.35rem;
    line-height: 1;
}

.ur-dc-val {
    font-size: 1.4rem;
    font-weight: 800;
    color: #ffffff;
    letter-spacing: -0.02em;
    font-family: 'Outfit', 'Inter', sans-serif;
}

.ur-dc-orig {
    font-size: 0.8rem;
    font-weight: 500;
    color: #64748b;
    text-decoration: line-through;
}

.ur-dc-save {
    font-size: 0.66rem;
    font-weight: 700;
    color: #10b981;
    margin-top: 0.15rem;
    letter-spacing: 0.01em;
    white-space: nowrap;
}

.ur-dc-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.35rem;
    background: #10b981;
    color: #ffffff !important;
    font-size: 0.76rem;
    font-weight: 700;
    padding: 0.48rem 0.95rem;
    border-radius: 10px;
    text-decoration: none;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    white-space: nowrap;
    cursor: pointer;
    user-select: none;
    flex-shrink: 0;
    -webkit-tap-highlight-color: transparent;
}

.ur-dc-btn:hover {
    background: #059669;
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
}

.ur-dc-btn:active {
    transform: scale(0.97);
}

/* ─── DESKTOP HORIZONTAL SINGLE CARD STYLES ─── */
@media (min-width: 768px) {
    .ur-direct-card {
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        padding: 1.15rem 1.65rem;
        gap: 1.5rem;
        min-height: 135px;
        border-radius: 18px;
    }

    .ur-dc-title {
        font-size: 1.18rem;
        font-weight: 750;
        margin-bottom: 0.25rem;
        line-height: 1.3;
    }

    .ur-dc-sub {
        font-size: 0.8rem;
        line-height: 1.45;
        max-width: 650px;
    }

    .ur-dc-features-row {
        display: flex;
        gap: 0.4rem;
        margin-top: 0.6rem;
    }

    .ur-dc-feat-tag {
        padding: 0.18rem 0.5rem;
        font-size: 0.68rem;
    }

    .ur-dc-footer {
        flex-direction: column;
        align-items: flex-end;
        justify-content: center;
        flex-shrink: 0;
        gap: 0.6rem;
        padding-left: 1.65rem;
        border-left: 1px solid rgba(255, 255, 255, 0.12);
        margin-top: 0;
        padding-top: 0;
        min-width: 175px;
    }

    .ur-dc-val {
        font-size: 1.65rem;
        font-weight: 800;
    }

    .ur-dc-orig {
        font-size: 0.88rem;
    }

    .ur-dc-save {
        font-size: 0.7rem;
    }

    .ur-dc-btn {
        padding: 0.52rem 1.15rem;
        font-size: 0.82rem;
        border-radius: 10px;
        width: 100%;
        justify-content: center;
    }
}

/* Slider Dots */
.ur-ds-dot {
    width: 6px;
    height: 6px;
    border-radius: 9999px;
    background: #cbd5e1;
    border: none;
    padding: 0;
    cursor: pointer;
    transition: all 0.25s cubic-bezier(0.25, 1, 0.5, 1);
}

.ur-ds-dot.active {
    width: 20px;
    background: #10b981;
}

/* ─── COMPACT MOBILE & MOBILE APP VIEWPORT (REDUCED HEIGHT & TEXT SIZES) ─── */
@media (max-width: 767px) {
    .ur-direct-passes-section {
        padding-top: 0.5rem !important;
        padding-bottom: 0.5rem !important;
    }

    .ur-direct-passes-section .max-w-7xl > .flex {
        gap: 0.5rem !important;
        margin-bottom: 0.5rem !important;
    }

    .ur-direct-passes-section h3 {
        font-size: 1.1rem !important;
        line-height: 1.3 !important;
    }

    .ur-dp-tab {
        padding: 0.25rem 0.55rem !important;
        font-size: 0.7rem !important;
    }

    #urDirectSliderArrows button {
        width: 1.75rem !important;
        height: 1.75rem !important;
    }

    .ur-direct-card {
        padding: 0.75rem 0.95rem !important;
        min-height: auto !important;
        gap: 0.5rem !important;
        border-radius: 16px !important;
        box-shadow: 0 10px 24px -6px rgba(0, 0, 0, 0.45) !important;
    }

    .ur-dc-badges {
        gap: 0.35rem !important;
        margin-bottom: 0.35rem !important;
    }

    .ur-dc-mint-pill {
        padding: 0.16rem 0.45rem !important;
        font-size: 0.58rem !important;
        letter-spacing: 0.02em !important;
        gap: 0.22rem !important;
    }

    .ur-dc-mint-pill i {
        font-size: 0.68rem !important;
    }

    .ur-dc-gold-badge {
        font-size: 0.62rem !important;
        gap: 0.22rem !important;
    }

    .ur-dc-gold-badge i {
        font-size: 0.75rem !important;
    }

    .ur-dc-duration-pill {
        padding: 0.15rem 0.42rem !important;
        font-size: 0.58rem !important;
        gap: 0.22rem !important;
    }

    .ur-dc-duration-pill i {
        font-size: 0.65rem !important;
    }

    .ur-dc-title {
        font-size: 0.95rem !important;
        font-weight: 800 !important;
        line-height: 1.25 !important;
        margin: 0 0 0.2rem 0 !important;
        letter-spacing: -0.01em !important;
    }

    .ur-dc-sub {
        font-size: 0.72rem !important;
        line-height: 1.35 !important;
        color: #94a3b8 !important;
        margin: 0 !important;
        display: -webkit-box !important;
        -webkit-line-clamp: 2 !important;
        -webkit-box-orient: vertical !important;
        overflow: hidden !important;
    }

    .ur-dc-footer {
        gap: 0.5rem !important;
        margin-top: auto !important;
        padding-top: 0 !important;
    }

    .ur-dc-price-group {
        min-width: 0 !important;
    }

    .ur-dc-price-row {
        gap: 0.3rem !important;
        align-items: baseline !important;
    }

    .ur-dc-val {
        font-size: 1.25rem !important;
        line-height: 1 !important;
    }

    .ur-dc-orig {
        font-size: 0.75rem !important;
    }

    .ur-dc-save {
        font-size: 0.62rem !important;
        margin-top: 0.15rem !important;
    }

    .ur-dc-cta-group {
        width: auto !important;
    }

    .ur-dc-btn {
        padding: 0.42rem 0.85rem !important;
        font-size: 0.76rem !important;
        border-radius: 10px !important;
        gap: 0.35rem !important;
        box-shadow: 0 3px 10px rgba(16, 185, 129, 0.3) !important;
    }

    .ur-dc-btn i {
        font-size: 0.75rem !important;
    }

    .ur-ds-dot {
        width: 5px !important;
        height: 5px !important;
    }

    .ur-ds-dot.active {
        width: 14px !important;
    }
}

@media (max-width: 400px) {
    .ur-direct-card {
        padding: 0.68rem 0.8rem !important;
    }

    .ur-dc-title {
        font-size: 0.88rem !important;
    }

    .ur-dc-val {
        font-size: 1.18rem !important;
    }

    .ur-dc-btn {
        padding: 0.38rem 0.75rem !important;
        font-size: 0.72rem !important;
    }
}

/* ─── DEDICATED IN-APP WEBVIEW STYLING (.is-mobile-app) ─── */
.is-mobile-app .ur-direct-passes-section {
    padding-top: 0.4rem !important;
    padding-bottom: 0.4rem !important;
}

.is-mobile-app .ur-direct-card {
    padding: 0.72rem 0.9rem !important;
    min-height: auto !important;
    gap: 0.45rem !important;
    border-radius: 14px !important;
}

.is-mobile-app .ur-dc-badges {
    margin-bottom: 0.3rem !important;
    gap: 0.3rem !important;
}

.is-mobile-app .ur-dc-mint-pill {
    padding: 0.15rem 0.42rem !important;
    font-size: 0.56rem !important;
}

.is-mobile-app .ur-dc-gold-badge {
    font-size: 0.6rem !important;
}

.is-mobile-app .ur-dc-duration-pill {
    padding: 0.14rem 0.4rem !important;
    font-size: 0.56rem !important;
}

.is-mobile-app .ur-dc-title {
    font-size: 0.92rem !important;
    line-height: 1.25 !important;
    margin: 0 0 0.2rem 0 !important;
}

.is-mobile-app .ur-dc-sub {
    font-size: 0.7rem !important;
    line-height: 1.35 !important;
    display: -webkit-box !important;
    -webkit-line-clamp: 2 !important;
    -webkit-box-orient: vertical !important;
    overflow: hidden !important;
}

.is-mobile-app .ur-dc-footer {
    gap: 0.45rem !important;
    padding-top: 0 !important;
}

.is-mobile-app .ur-dc-val {
    font-size: 1.22rem !important;
}

.is-mobile-app .ur-dc-orig {
    font-size: 0.72rem !important;
}

.is-mobile-app .ur-dc-save {
    font-size: 0.6rem !important;
    margin-top: 0.12rem !important;
}

.is-mobile-app .ur-dc-cta-group {
    width: auto !important;
}

.is-mobile-app .ur-dc-btn {
    padding: 0.4rem 0.82rem !important;
    font-size: 0.74rem !important;
    border-radius: 9px !important;
}
</style>

<script>
let directPassCurrentIndex = 0;
let directPassAutoTimer = null;
const DIRECT_PASS_INTERVAL = 3800; // Auto-slides every 3.8 seconds

function getDirectPassVisibleCards() {
    const grid = document.getElementById('urDirectCardsGrid');
    if (!grid) return [];
    return Array.from(grid.querySelectorAll('.ur-direct-card-item')).filter(c => c.style.display !== 'none');
}

function slideDirectPasses(direction, isAuto = false) {
    const grid = document.getElementById('urDirectCardsGrid');
    const cards = getDirectPassVisibleCards();
    if (!grid || cards.length <= 1) return;

    const isAtEnd = (grid.scrollLeft + grid.clientWidth >= grid.scrollWidth - 20);
    const isAtStart = (grid.scrollLeft <= 20);

    if (direction > 0) {
        if (directPassCurrentIndex >= cards.length - 1 || isAtEnd) {
            directPassCurrentIndex = 0;
        } else {
            directPassCurrentIndex += 1;
        }
    } else {
        if (directPassCurrentIndex <= 0 || isAtStart) {
            directPassCurrentIndex = cards.length - 1;
        } else {
            directPassCurrentIndex -= 1;
        }
    }

    scrollDirectPassToIndex(directPassCurrentIndex);

    if (!isAuto) {
        resetDirectPassAutoTimer();
    }
}

function scrollDirectPassToIndex(index) {
    const grid = document.getElementById('urDirectCardsGrid');
    const cards = getDirectPassVisibleCards();
    if (!grid || !cards.length) return;

    if (index >= 0 && index < cards.length) {
        directPassCurrentIndex = index;
        const targetCard = cards[index];
        const targetScrollLeft = targetCard.offsetLeft - grid.offsetLeft;

        grid.scrollTo({
            left: Math.max(0, targetScrollLeft),
            behavior: 'smooth'
        });
        updateDirectPassDots();
    }
}

function updateDirectPassDots() {
    const grid = document.getElementById('urDirectCardsGrid');
    const cards = getDirectPassVisibleCards();
    const dots = document.querySelectorAll('#urDirectSliderDots .ur-ds-dot');
    if (!grid || !cards.length || !dots.length) return;

    const scrollLeft = grid.scrollLeft;
    let closestIndex = 0;
    let minDistance = Infinity;

    cards.forEach((c, idx) => {
        const cardPos = c.offsetLeft - grid.offsetLeft;
        const distance = Math.abs(cardPos - scrollLeft);
        if (distance < minDistance) {
            minDistance = distance;
            closestIndex = idx;
        }
    });

    directPassCurrentIndex = closestIndex;
    dots.forEach((d, idx) => {
        d.classList.toggle('active', idx === closestIndex);
    });
}

function updateDirectPassControls() {
    const visibleCards = getDirectPassVisibleCards();
    const arrows = document.getElementById('urDirectSliderArrows');
    if (arrows) {
        arrows.style.display = visibleCards.length > 1 ? 'flex' : 'none';
    }
}

function buildDirectPassDots() {
    const dotsContainer = document.getElementById('urDirectSliderDots');
    const visibleCards = getDirectPassVisibleCards();
    updateDirectPassControls();
    if (!dotsContainer) return;

    dotsContainer.innerHTML = '';
    if (visibleCards.length <= 1) {
        dotsContainer.style.display = 'none';
        return;
    }
    dotsContainer.style.display = 'flex';

    visibleCards.forEach((c, idx) => {
        const dot = document.createElement('button');
        dot.type = 'button';
        dot.className = 'ur-ds-dot' + (idx === 0 ? ' active' : '');
        dot.setAttribute('aria-label', 'Go to pass ' + (idx + 1));
        dot.onclick = () => {
            scrollDirectPassToIndex(idx);
            resetDirectPassAutoTimer();
        };
        dotsContainer.appendChild(dot);
    });
}

function startDirectPassAutoTimer() {
    stopDirectPassAutoTimer();
    const cards = getDirectPassVisibleCards();
    if (cards.length <= 1) return;

    directPassAutoTimer = setInterval(() => {
        slideDirectPasses(1, true);
    }, DIRECT_PASS_INTERVAL);
}

function stopDirectPassAutoTimer() {
    if (directPassAutoTimer) {
        clearInterval(directPassAutoTimer);
        directPassAutoTimer = null;
    }
}

function resetDirectPassAutoTimer() {
    stopDirectPassAutoTimer();
    startDirectPassAutoTimer();
}

function filterDirectPassCards(purpose, btn) {
    // Update active tab style
    const tabs = document.querySelectorAll('#urDirectPassFilter .ur-dp-tab');
    tabs.forEach(t => {
        t.classList.remove('active', 'bg-white', 'dark:bg-slate-700', 'text-slate-900', 'dark:text-white', 'shadow-xs');
        t.classList.add('text-slate-600', 'dark:text-slate-300');
    });
    if (btn) {
        btn.classList.add('active', 'bg-white', 'dark:bg-slate-700', 'text-slate-900', 'dark:text-white', 'shadow-xs');
        btn.classList.remove('text-slate-600', 'dark:text-slate-300');
    }

    // Filter cards
    const grid = document.getElementById('urDirectCardsGrid');
    if (!grid) return;

    const cards = grid.querySelectorAll('.ur-direct-card-item');
    cards.forEach(card => {
        const cardPurpose = card.getAttribute('data-purpose') || '';
        if (purpose === 'all' || cardPurpose.includes(purpose)) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });

    directPassCurrentIndex = 0;
    grid.scrollLeft = 0;
    grid.scrollTo({ left: 0, behavior: 'instant' });

    buildDirectPassDots();
    updateDirectPassDots();
    resetDirectPassAutoTimer();
}

document.addEventListener('DOMContentLoaded', () => {
    const grid = document.getElementById('urDirectCardsGrid');
    const viewport = document.getElementById('urDirectSliderViewport');
    if (!grid) return;

    grid.addEventListener('scroll', () => {
        requestAnimationFrame(updateDirectPassDots);
    }, { passive: true });

    // Pause on hover
    if (viewport) {
        viewport.addEventListener('mouseenter', stopDirectPassAutoTimer);
        viewport.addEventListener('mouseleave', startDirectPassAutoTimer);
    }

    // Touch swipe gesture listeners with pause and auto-resume
    let touchStartX = 0;
    let touchStartY = 0;
    let isSwiping = false;

    grid.addEventListener('touchstart', (e) => {
        touchStartX = e.touches[0].clientX;
        touchStartY = e.touches[0].clientY;
        isSwiping = true;
        stopDirectPassAutoTimer();
    }, { passive: true });

    grid.addEventListener('touchend', (e) => {
        if (!isSwiping) return;
        const touchEndX = e.changedTouches[0].clientX;
        const touchEndY = e.changedTouches[0].clientY;
        const diffX = touchStartX - touchEndX;
        const diffY = touchStartY - touchEndY;

        if (Math.abs(diffX) > 35 && Math.abs(diffX) > Math.abs(diffY)) {
            if (diffX > 0) {
                slideDirectPasses(1);
            } else {
                slideDirectPasses(-1);
            }
        }
        isSwiping = false;
        setTimeout(startDirectPassAutoTimer, 2500);
    }, { passive: true });

    // Handle tab visibility (pause when switching browser tabs)
    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            stopDirectPassAutoTimer();
        } else {
            startDirectPassAutoTimer();
        }
    });

    buildDirectPassDots();
    updateDirectPassDots();
    startDirectPassAutoTimer();
});
</script>
@endif
