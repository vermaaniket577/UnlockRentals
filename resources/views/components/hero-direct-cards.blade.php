@php
    try {
        \App\Models\Plan::ensureRentalPlansExist();
        \App\Models\Plan::ensureBuyerPlansExist();

        $allDirectPlans = \App\Models\Plan::active()
            ->where('is_private', false)
            ->orderBy('sort_order')
            ->get();
    } catch (\Throwable $e) {
        $allDirectPlans = collect();
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
                {{-- Filter Pills: All / Rent / Buy --}}
                <div class="inline-flex items-center p-1 bg-slate-100 dark:bg-slate-800/80 rounded-xl border border-slate-200 dark:border-slate-700/60" id="urDirectPassFilter">
                    <button type="button" onclick="filterDirectPassCards('all', this)" class="ur-dp-tab active px-3 py-1.5 text-xs font-bold rounded-lg transition-all bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-xs">
                        All Passes
                    </button>
                    <button type="button" onclick="filterDirectPassCards('rent', this)" class="ur-dp-tab px-3 py-1.5 text-xs font-bold rounded-lg transition-all text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white">
                        Tenant Pass
                    </button>
                    <button type="button" onclick="filterDirectPassCards('buy', this)" class="ur-dp-tab px-3 py-1.5 text-xs font-bold rounded-lg transition-all text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white">
                        Buyer Pass
                    </button>
                </div>

                {{-- Interactive Slider Navigation Chevrons --}}
                <div class="flex items-center gap-1.5">
                    <button type="button" onclick="slideDirectPasses(-1)" class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 flex items-center justify-center transition-all active:scale-90 shadow-xs cursor-pointer" title="Previous Pass" aria-label="Previous Pass">
                        <i class="ph-bold ph-caret-left text-sm"></i>
                    </button>
                    <button type="button" onclick="slideDirectPasses(1)" class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 flex items-center justify-center transition-all active:scale-90 shadow-xs cursor-pointer" title="Next Pass" aria-label="Next Pass">
                        <i class="ph-bold ph-caret-right text-sm"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- Cards Container: Responsive Interactive Slider on Mobile & Website --}}
        <div class="ur-direct-slider-viewport relative w-full" id="urDirectSliderViewport">
            <div class="ur-direct-cards-wrapper flex gap-3.5 sm:gap-5 overflow-x-auto snap-x snap-mandatory no-scrollbar pb-2 sm:pb-3 scroll-smooth" id="urDirectCardsGrid">
                @foreach($allDirectPlans as $idx => $plan)
                    @php
                        $isBuy = $plan->isBuyPlan();
                        $planPurpose = $isBuy ? 'buy' : 'rent';
                        $planNameLower = strtolower($plan->name);
                        
                        // Determine tier badge & icon
                        if (str_contains($planNameLower, 'platinum') || str_contains($planNameLower, 'vip')) {
                            $tierText = 'PLATINUM VIP ACCESS';
                        } elseif (str_contains($planNameLower, 'gold')) {
                            $tierText = $isBuy ? 'GOLD BUYER ACCESS' : 'GOLD ACCESS';
                        } elseif (str_contains($planNameLower, 'silver')) {
                            $tierText = $isBuy ? 'SILVER BUYER ACCESS' : 'SILVER ACCESS';
                        } else {
                            $tierText = strtoupper($plan->name) . ' ACCESS';
                        }

                        // Mint badge text
                        $mintText = 'BUYER & TENANT PASS';

                        // Estimated brokerage savings text
                        if ($isBuy) {
                            $savingsText = 'Save ₹1,50,000+ Average';
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

                    <div class="ur-direct-card-item flex-shrink-0 snap-center" data-purpose="{{ $planPurpose }}" data-card-index="{{ $idx }}">
                        <div class="ur-direct-card">
                            
                            {{-- Top Badges Row --}}
                            <div class="ur-dc-badges">
                                <span class="ur-dc-mint-pill">
                                    <i class="ph-bold ph-shield-check"></i>
                                    <span>{{ $mintText }}</span>
                                </span>
                                <span class="ur-dc-gold-badge">
                                    <i class="ph-fill ph-seal-check"></i>
                                    <span>{{ $tierText }}</span>
                                </span>
                            </div>

                            {{-- Main Title & Subtitle --}}
                            <h4 class="ur-dc-title">
                                Unlock {{ $plan->contact_limit }}+ Direct {{ $isBuy ? 'Sellers' : 'Landlords' }}
                            </h4>
                            <p class="ur-dc-sub">
                                Skip brokerage charges completely. Instant contact numbers.
                            </p>

                            {{-- Footer Dock: Price & Unlock CTA --}}
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
                @endforeach
            </div>
        </div>

        {{-- Slider Dot Indicators --}}
        <div class="flex items-center justify-center gap-1.5 mt-2 sm:mt-3" id="urDirectSliderDots">
            @foreach($allDirectPlans as $idx => $p)
                <button type="button" onclick="scrollDirectPassToIndex({{ $idx }})" class="ur-ds-dot {{ $idx === 0 ? 'active' : '' }}" aria-label="Go to pass {{ $idx + 1 }}"></button>
            @endforeach
        </div>

    </div>
</section>

<style>
/* ─── DIRECT OWNER PASS CARDS (DARK LUXURY DESIGN) ─── */
.ur-direct-slider-viewport {
    width: 100%;
    position: relative;
    box-sizing: border-box;
}

.ur-direct-cards-wrapper {
    display: flex;
    gap: 0.85rem;
    overflow-x: auto;
    scroll-snap-type: x mandatory;
    -webkit-overflow-scrolling: touch;
    touch-action: pan-x pan-y;
    scroll-behavior: smooth;
    padding-bottom: 0.35rem;
    width: 100%;
    box-sizing: border-box;
}

.ur-direct-card-item {
    flex: 0 0 calc(100vw - 42px);
    max-width: 350px;
    scroll-snap-align: center;
    box-sizing: border-box;
}

@media (min-width: 640px) {
    .ur-direct-card-item {
        flex: 0 0 340px;
        max-width: 340px;
        scroll-snap-align: start;
    }
}

@media (min-width: 1024px) {
    .ur-direct-card-item {
        flex: 0 0 calc((100% - 2rem) / 3);
        max-width: none;
        scroll-snap-align: start;
    }
}

.ur-direct-card {
    position: relative;
    width: 100%;
    box-sizing: border-box;
    background: radial-gradient(130% 120% at 75% 0%, #0d1e3d 0%, #071328 65%, #050c1a 100%);
    border: 1px solid rgba(255, 255, 255, 0.14);
    border-radius: 20px;
    padding: 1.25rem 1.15rem 1.15rem;
    box-shadow: 0 16px 36px -8px rgba(0, 0, 0, 0.55), 0 0 0 1px rgba(255, 255, 255, 0.05);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    color: #ffffff;
    font-family: 'Outfit', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s ease;
    min-height: 195px;
}

.ur-direct-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 20px 42px -8px rgba(0, 0, 0, 0.7), 0 0 0 1px rgba(255, 255, 255, 0.1);
}

.ur-dc-badges {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.35rem;
    margin-bottom: 0.85rem;
    flex-wrap: nowrap;
    overflow: hidden;
}

.ur-dc-mint-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.24rem 0.62rem;
    border-radius: 9999px;
    border: 1.5px solid #10b981;
    background: #ecfdf5;
    color: #047857;
    font-size: 0.66rem;
    font-weight: 800;
    letter-spacing: 0.03em;
    text-transform: uppercase;
    line-height: 1.2;
    white-space: nowrap;
    flex-shrink: 0;
}

.ur-dc-mint-pill i {
    font-size: 0.8rem;
    color: #10b981;
}

.ur-dc-gold-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    color: #fbbf24;
    font-size: 0.72rem;
    font-weight: 800;
    letter-spacing: 0.03em;
    text-transform: uppercase;
    line-height: 1.2;
    white-space: nowrap;
    flex-shrink: 0;
}

.ur-dc-gold-badge i {
    font-size: 0.9rem;
    color: #fbbf24;
}

.ur-dc-title {
    font-size: 1.35rem;
    font-weight: 800;
    color: #ffffff;
    line-height: 1.25;
    letter-spacing: -0.02em;
    margin: 0 0 0.3rem 0;
    font-family: 'Outfit', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}

.ur-dc-sub {
    font-size: 0.8rem;
    font-weight: 400;
    color: #94a3b8;
    line-height: 1.4;
    margin: 0 0 1rem 0;
}

.ur-dc-footer {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 0.65rem;
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
    gap: 0.35rem;
    line-height: 1;
}

.ur-dc-val {
    font-size: 1.8rem;
    font-weight: 900;
    color: #ffffff;
    letter-spacing: -0.03em;
    font-family: 'Outfit', 'Inter', sans-serif;
}

.ur-dc-orig {
    font-size: 0.92rem;
    font-weight: 500;
    color: #64748b;
    text-decoration: line-through;
}

.ur-dc-save {
    font-size: 0.74rem;
    font-weight: 700;
    color: #10b981;
    margin-top: 0.25rem;
    letter-spacing: 0.01em;
    white-space: nowrap;
}

.ur-dc-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
    background: #10b981;
    color: #ffffff !important;
    font-size: 0.88rem;
    font-weight: 700;
    padding: 0.65rem 1.25rem;
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

function getDirectPassVisibleCards() {
    const grid = document.getElementById('urDirectCardsGrid');
    if (!grid) return [];
    return Array.from(grid.querySelectorAll('.ur-direct-card-item')).filter(c => c.style.display !== 'none');
}

function slideDirectPasses(direction) {
    const grid = document.getElementById('urDirectCardsGrid');
    const cards = getDirectPassVisibleCards();
    if (!grid || !cards.length) return;

    directPassCurrentIndex = Math.max(0, Math.min(cards.length - 1, directPassCurrentIndex + direction));
    scrollDirectPassToIndex(directPassCurrentIndex);
}

function scrollDirectPassToIndex(index) {
    const grid = document.getElementById('urDirectCardsGrid');
    const cards = getDirectPassVisibleCards();
    if (!grid || !cards.length) return;

    if (index >= 0 && index < cards.length) {
        directPassCurrentIndex = index;
        const targetCard = cards[index];
        const targetScrollLeft = targetCard.offsetLeft - grid.offsetLeft - (grid.clientWidth - targetCard.clientWidth) / 2;
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
        const cardCenter = c.offsetLeft - grid.offsetLeft + (c.clientWidth / 2);
        const viewCenter = scrollLeft + (grid.clientWidth / 2);
        const distance = Math.abs(cardCenter - viewCenter);
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

function filterDirectPassCards(purpose, btn) {
    // Update active tab style
    const tabs = document.querySelectorAll('#urDirectPassFilter .ur-dp-tab');
    tabs.forEach(t => {
        t.classList.remove('active', 'bg-white', 'dark:bg-slate-700', 'text-slate-900', 'dark:text-white', 'shadow-xs');
        t.classList.add('text-slate-600', 'dark:text-slate-300');
    });
    btn.classList.add('active', 'bg-white', 'dark:bg-slate-700', 'text-slate-900', 'dark:text-white', 'shadow-xs');
    btn.classList.remove('text-slate-600', 'dark:text-slate-300');

    // Filter cards
    const grid = document.getElementById('urDirectCardsGrid');
    if (!grid) return;

    const cards = grid.querySelectorAll('.ur-direct-card-item');
    cards.forEach(card => {
        const cardPurpose = card.getAttribute('data-purpose');
        if (purpose === 'all' || cardPurpose === purpose) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });

    directPassCurrentIndex = 0;
    grid.scrollTo({ left: 0, behavior: 'smooth' });

    // Update dots count
    const visibleCards = getDirectPassVisibleCards();
    const dotsContainer = document.getElementById('urDirectSliderDots');
    if (dotsContainer) {
        dotsContainer.innerHTML = '';
        visibleCards.forEach((c, idx) => {
            const dot = document.createElement('button');
            dot.type = 'button';
            dot.className = 'ur-ds-dot' + (idx === 0 ? ' active' : '');
            dot.setAttribute('aria-label', 'Go to pass ' + (idx + 1));
            dot.onclick = () => scrollDirectPassToIndex(idx);
            dotsContainer.appendChild(dot);
        });
    }

    updateDirectPassDots();
}

document.addEventListener('DOMContentLoaded', () => {
    const grid = document.getElementById('urDirectCardsGrid');
    if (!grid) return;

    grid.addEventListener('scroll', () => {
        requestAnimationFrame(updateDirectPassDots);
    }, { passive: true });

    // Touch swipe gesture listeners
    let touchStartX = 0;
    let isSwiping = false;

    grid.addEventListener('touchstart', (e) => {
        touchStartX = e.touches[0].clientX;
        isSwiping = true;
    }, { passive: true });

    grid.addEventListener('touchend', (e) => {
        if (!isSwiping) return;
        const touchEndX = e.changedTouches[0].clientX;
        const diffX = touchStartX - touchEndX;

        if (Math.abs(diffX) > 35) {
            if (diffX > 0) {
                slideDirectPasses(1);
            } else {
                slideDirectPasses(-1);
            }
        }
        isSwiping = false;
    }, { passive: true });

    updateDirectPassDots();
});
</script>
@endif
