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
<section class="ur-direct-passes-section py-4 sm:py-6" id="hero-direct-passes">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Section Header with Purpose Filter Tabs --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 sm:mb-5">
            <div>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 text-[11px] font-black uppercase tracking-wider mb-1">
                    <i class="ph-bold ph-shield-check text-xs"></i>
                    <span>Zero Brokerage Guaranteed</span>
                </div>
                <h3 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                    Direct Owner Contact Passes
                </h3>
            </div>

            {{-- Filter Pills: All / Rent / Buy --}}
            <div class="inline-flex items-center p-1 bg-slate-100 dark:bg-slate-800/80 rounded-xl border border-slate-200 dark:border-slate-700/60 self-start sm:self-auto" id="urDirectPassFilter">
                <button type="button" onclick="filterDirectPassCards('all', this)" class="ur-dp-tab active px-3.5 py-1.5 text-xs font-bold rounded-lg transition-all bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-xs">
                    All Passes
                </button>
                <button type="button" onclick="filterDirectPassCards('rent', this)" class="ur-dp-tab px-3.5 py-1.5 text-xs font-bold rounded-lg transition-all text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white">
                    Tenant Pass
                </button>
                <button type="button" onclick="filterDirectPassCards('buy', this)" class="ur-dp-tab px-3.5 py-1.5 text-xs font-bold rounded-lg transition-all text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white">
                    Buyer Pass
                </button>
            </div>
        </div>

        {{-- Cards Container: Responsive Grid on Desktop, Swipeable Reel on Mobile --}}
        <div class="ur-direct-cards-wrapper flex md:grid md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5 overflow-x-auto md:overflow-visible snap-x snap-mandatory no-scrollbar pb-3 md:pb-0 -mx-4 px-4 sm:mx-0 sm:px-0" id="urDirectCardsGrid">
            @foreach($allDirectPlans as $plan)
                @php
                    $isBuy = $plan->isBuyPlan();
                    $planPurpose = $isBuy ? 'buy' : 'rent';
                    $planNameLower = strtolower($plan->name);
                    
                    // Determine tier badge & icon
                    if (str_contains($planNameLower, 'platinum') || str_contains($planNameLower, 'vip')) {
                        $tierText = 'PLATINUM VIP ACCESS';
                        $tierIcon = 'ph-fill ph-seal-check';
                    } elseif (str_contains($planNameLower, 'gold')) {
                        $tierText = $isBuy ? 'GOLD BUYER ACCESS' : 'GOLD ACCESS';
                        $tierIcon = 'ph-fill ph-seal-check';
                    } elseif (str_contains($planNameLower, 'silver')) {
                        $tierText = $isBuy ? 'SILVER BUYER ACCESS' : 'SILVER ACCESS';
                        $tierIcon = 'ph-fill ph-seal-check';
                    } else {
                        $tierText = strtoupper($plan->name) . ' ACCESS';
                        $tierIcon = 'ph-fill ph-seal-check';
                    }

                    // Mint badge text
                    $mintText = $isBuy ? 'BUYER & TENANT PASS' : 'BUYER & TENANT PASS';

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

                <div class="ur-direct-card-item flex-shrink-0 w-[86vw] max-w-[340px] md:w-auto md:max-w-none snap-center" data-purpose="{{ $planPurpose }}">
                    <div class="ur-direct-card">
                        
                        {{-- Top Badges Row --}}
                        <div class="ur-dc-badges">
                            <span class="ur-dc-mint-pill">
                                <i class="ph-bold ph-shield-check"></i>
                                <span>{{ $mintText }}</span>
                            </span>
                            <span class="ur-dc-gold-badge">
                                <i class="ph-bold {{ $tierIcon }}"></i>
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
</section>

<style>
/* ─── DIRECT OWNER PASS CARDS (DARK LUXURY DESIGN) ─── */
.ur-direct-card {
    position: relative;
    width: 100%;
    box-sizing: border-box;
    background: radial-gradient(130% 120% at 75% 0%, #0d1e3d 0%, #071328 65%, #050c1a 100%);
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 20px;
    padding: 1.35rem 1.25rem 1.2rem;
    box-shadow: 0 16px 36px -8px rgba(0, 0, 0, 0.55), 0 0 0 1px rgba(255, 255, 255, 0.05);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    color: #ffffff;
    font-family: 'Outfit', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s ease;
    min-height: 200px;
}

.ur-direct-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 20px 42px -8px rgba(0, 0, 0, 0.7), 0 0 0 1px rgba(255, 255, 255, 0.1);
}

.ur-dc-badges {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.5rem;
    margin-bottom: 0.95rem;
}

.ur-dc-mint-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.28rem 0.72rem;
    border-radius: 9999px;
    border: 1.5px solid #10b981;
    background: #ecfdf5;
    color: #047857;
    font-size: 0.7rem;
    font-weight: 800;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    line-height: 1.3;
}

.ur-dc-mint-pill i {
    font-size: 0.85rem;
    color: #10b981;
}

.ur-dc-gold-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    color: #fbbf24;
    font-size: 0.75rem;
    font-weight: 800;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    line-height: 1.3;
}

.ur-dc-gold-badge i {
    font-size: 0.95rem;
    color: #fbbf24;
}

.ur-dc-title {
    font-size: 1.38rem;
    font-weight: 800;
    color: #ffffff;
    line-height: 1.25;
    letter-spacing: -0.025em;
    margin: 0 0 0.35rem 0;
    font-family: 'Outfit', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}

.ur-dc-sub {
    font-size: 0.82rem;
    font-weight: 400;
    color: #94a3b8;
    line-height: 1.45;
    margin: 0 0 1.15rem 0;
}

.ur-dc-footer {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 0.75rem;
    margin-top: auto;
    padding-top: 0.35rem;
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
}

.ur-dc-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.45rem;
    background: #10b981;
    color: #ffffff !important;
    font-size: 0.92rem;
    font-weight: 700;
    padding: 0.68rem 1.35rem;
    border-radius: 14px;
    text-decoration: none;
    box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35);
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    white-space: nowrap;
    cursor: pointer;
    user-select: none;
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

@media (max-width: 640px) {
    .ur-dc-title {
        font-size: 1.25rem;
    }
    .ur-dc-val {
        font-size: 1.65rem;
    }
    .ur-dc-btn {
        padding: 0.62rem 1.15rem;
        font-size: 0.86rem;
    }
}
</style>

<script>
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
    const cards = document.querySelectorAll('#urDirectCardsGrid .ur-direct-card-item');
    cards.forEach(card => {
        const cardPurpose = card.getAttribute('data-purpose');
        if (purpose === 'all' || cardPurpose === purpose) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
}
</script>
@endif
