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
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-3 sm:mb-4">
            <div>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 text-[11px] font-black uppercase tracking-wider mb-1">
                    <i class="ph-bold ph-shield-check text-xs"></i>
                    <span>Zero Brokerage Guaranteed</span>
                </div>
                <h3 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                    Direct Owner Contact Passes
                </h3>
            </div>

            <div class="flex items-center justify-between sm:justify-end gap-2.5 w-full sm:w-auto">
                {{-- Filter Tabs: All Plans / Tenant Pass (Rent) / Buyer Pass (Buy) --}}
                @if($hasRentPlans && $hasBuyPlans)
                <div class="inline-flex items-center p-1 bg-slate-100 dark:bg-slate-800/80 rounded-xl border border-slate-200 dark:border-slate-700/60" id="urDirectPassFilter">
                    <button type="button" onclick="filterDirectPassCards('all', this)" class="ur-dp-tab {{ $defaultPurpose === 'all' ? 'active bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white' }} px-3 sm:px-3.5 py-1.5 text-xs font-bold rounded-lg transition-all cursor-pointer whitespace-nowrap">
                        All Plans
                    </button>
                    <button type="button" onclick="filterDirectPassCards('rent', this)" class="ur-dp-tab {{ $defaultPurpose === 'rent' ? 'active bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white' }} px-3 sm:px-3.5 py-1.5 text-xs font-bold rounded-lg transition-all cursor-pointer whitespace-nowrap">
                        Tenant Pass
                    </button>
                    <button type="button" onclick="filterDirectPassCards('buy', this)" class="ur-dp-tab {{ $defaultPurpose === 'buy' ? 'active bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-xs' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white' }} px-3 sm:px-3.5 py-1.5 text-xs font-bold rounded-lg transition-all cursor-pointer whitespace-nowrap">
                        Buyer Pass
                    </button>
                </div>
                @endif

                {{-- Interactive Slider Navigation Chevrons --}}
                <div class="flex items-center gap-1.5" id="urDirectSliderArrows">
                    <button type="button" onclick="slideDirectPasses(-1)" class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 flex items-center justify-center transition-all active:scale-90 shadow-xs cursor-pointer" title="Previous Pass" aria-label="Previous Pass">
                        <i class="ph-bold ph-caret-left text-sm"></i>
                    </button>
                    <button type="button" onclick="slideDirectPasses(1)" class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 flex items-center justify-center transition-all active:scale-90 shadow-xs cursor-pointer" title="Next Pass" aria-label="Next Pass">
                        <i class="ph-bold ph-caret-right text-sm"></i>
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
    border-radius: 20px;
    padding: 1.35rem 1.25rem;
    box-shadow: 0 16px 36px -8px rgba(0, 0, 0, 0.55), 0 0 0 1px rgba(255, 255, 255, 0.05);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    gap: 1.15rem;
    color: #ffffff;
    font-family: 'Outfit', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s ease;
    min-height: 195px;
}

.ur-direct-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 20px 42px -8px rgba(0, 0, 0, 0.7), 0 0 0 1px rgba(255, 255, 255, 0.1);
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
    gap: 0.5rem;
    margin-bottom: 0.75rem;
    flex-wrap: wrap;
}

.ur-dc-mint-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.25rem 0.65rem;
    border-radius: 9999px;
    border: 1.5px solid #10b981;
    background: #ecfdf5;
    color: #047857;
    font-size: 0.68rem;
    font-weight: 800;
    letter-spacing: 0.03em;
    text-transform: uppercase;
    line-height: 1.2;
    white-space: nowrap;
    flex-shrink: 0;
}

.ur-dc-mint-pill i {
    font-size: 0.82rem;
    color: #10b981;
}

.ur-dc-gold-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    color: #fbbf24;
    font-size: 0.74rem;
    font-weight: 800;
    letter-spacing: 0.03em;
    text-transform: uppercase;
    line-height: 1.2;
    white-space: nowrap;
    flex-shrink: 0;
}

.ur-dc-gold-badge i {
    font-size: 0.92rem;
    color: #fbbf24;
}

.ur-dc-duration-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.24rem 0.65rem;
    border-radius: 9999px;
    background: rgba(255, 255, 255, 0.07);
    border: 1px solid rgba(255, 255, 255, 0.12);
    color: #cbd5e1;
    font-size: 0.68rem;
    font-weight: 700;
    letter-spacing: 0.02em;
    white-space: nowrap;
    flex-shrink: 0;
}

.ur-dc-title {
    font-size: 1.35rem;
    font-weight: 800;
    color: #ffffff;
    line-height: 1.25;
    letter-spacing: -0.02em;
    margin: 0 0 0.35rem 0;
    font-family: 'Outfit', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}

.ur-dc-sub {
    font-size: 0.82rem;
    font-weight: 400;
    color: #94a3b8;
    line-height: 1.45;
    margin: 0;
}

.ur-dc-features-row {
    display: none;
    flex-wrap: wrap;
    align-items: center;
    gap: 0.5rem;
    margin-top: 0.85rem;
}

.ur-dc-feat-tag {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.25rem 0.65rem;
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.09);
    color: #e2e8f0;
    font-size: 0.74rem;
    font-weight: 600;
}

.ur-dc-footer {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 0.75rem;
    margin-top: auto;
    padding-top: 0.25rem;
}

.ur-dc-price-group {
    display: flex;
    flex-direction: column;
    min-width: 0;
}

.ur-dc-price-row {
    display: flex;
    align-items: baseline;
    gap: 0.4rem;
    line-height: 1;
}

.ur-dc-val {
    font-size: 1.85rem;
    font-weight: 900;
    color: #ffffff;
    letter-spacing: -0.03em;
    font-family: 'Outfit', 'Inter', sans-serif;
}

.ur-dc-orig {
    font-size: 0.95rem;
    font-weight: 500;
    color: #64748b;
    text-decoration: line-through;
}

.ur-dc-save {
    font-size: 0.75rem;
    font-weight: 700;
    color: #10b981;
    margin-top: 0.3rem;
    letter-spacing: 0.01em;
    white-space: nowrap;
}

.ur-dc-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.45rem;
    background: #10b981;
    color: #ffffff !important;
    font-size: 0.9rem;
    font-weight: 700;
    padding: 0.68rem 1.35rem;
    border-radius: 14px;
    text-decoration: none;
    box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35);
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
    box-shadow: 0 6px 18px rgba(16, 185, 129, 0.45);
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
        padding: 1.75rem 2.25rem;
        gap: 2.25rem;
        min-height: 185px;
        border-radius: 24px;
    }

    .ur-dc-title {
        font-size: 1.75rem;
        margin-bottom: 0.4rem;
    }

    .ur-dc-sub {
        font-size: 0.92rem;
        max-width: 700px;
    }

    .ur-dc-features-row {
        display: flex;
    }

    .ur-dc-footer {
        flex-direction: column;
        align-items: flex-end;
        justify-content: center;
        flex-shrink: 0;
        gap: 0.95rem;
        padding-left: 2.25rem;
        border-left: 1px solid rgba(255, 255, 255, 0.12);
        margin-top: 0;
        padding-top: 0;
        min-width: 220px;
    }

    .ur-dc-val {
        font-size: 2.35rem;
    }

    .ur-dc-orig {
        font-size: 1.05rem;
    }

    .ur-dc-save {
        font-size: 0.8rem;
    }

    .ur-dc-btn {
        padding: 0.78rem 1.65rem;
        font-size: 0.95rem;
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

@media (max-width: 640px) {
    .ur-dc-title {
        font-size: 1.25rem;
    }
    .ur-dc-val {
        font-size: 1.65rem;
    }
    .ur-dc-btn {
        padding: 0.6rem 1.1rem;
        font-size: 0.84rem;
    }
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
