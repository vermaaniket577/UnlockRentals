{{-- ============================================================
     UNLOCK RENTALS — RESOURCE DIRECTORY (CLEAN & PROFESSIONAL)
     ============================================================ --}}

<style>
.ur-directory {
    padding-top: 3.5rem;
    padding-bottom: 3.5rem;
    background-color: #f8fafc;
    border-top: 1px solid #e2e8f0;
    font-family: 'Inter', sans-serif;
    transition: background-color 0.3s ease;
}
:is(.dark .ur-directory) {
    background-color: #0b0f19;
    border-top-color: #1e293b;
}

.ur-directory__container {
    max-width: 80rem;
    margin-left: auto;
    margin-right: auto;
    padding-left: 1rem;
    padding-right: 1rem;
}

.ur-directory__header {
    text-align: center;
    max-width: 38rem;
    margin: 0 auto 2.25rem;
}

.ur-directory__badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.3rem 0.75rem;
    background: #eff6ff;
    border: 1px solid rgba(37, 99, 235, 0.15);
    border-radius: 9999px;
    color: #2563eb;
    font-size: 0.6875rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.12em;
    margin-bottom: 0.6rem;
}
:is(.dark .ur-directory__badge) {
    background: rgba(37, 99, 235, 0.15);
    border-color: rgba(37, 99, 235, 0.3);
    color: #60a5fa;
}

.ur-directory__main-title {
    font-size: 1.75rem;
    font-weight: 900;
    color: #0f172a;
    letter-spacing: -0.03em;
    line-height: 1.2;
    font-family: 'Outfit', sans-serif;
}
:is(.dark .ur-directory__main-title) {
    color: #f8fafc;
}

@media (min-width: 768px) {
    .ur-directory__main-title { font-size: 2.25rem; }
}

/* Category Tabs */
.ur-directory__tabs {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    margin-bottom: 2rem;
}

.ur-directory__tab-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.55rem 1.15rem;
    border-radius: 9999px;
    font-size: 0.8125rem;
    font-weight: 700;
    cursor: pointer;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    color: #64748b;
    transition: all 0.2s ease;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
}
:is(.dark .ur-directory__tab-btn) {
    background: #1e293b;
    border-color: #334155;
    color: #94a3b8;
}

.ur-directory__tab-btn.active {
    background: #2563eb;
    border-color: #2563eb;
    color: #ffffff;
    box-shadow: 0 4px 14px rgba(37, 99, 235, 0.28);
}
:is(.dark .ur-directory__tab-btn.active) {
    background: #2563eb;
    border-color: #2563eb;
    color: #ffffff;
}

/* Card Grid */
.ur-directory__card-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.625rem;
}

@media (min-width: 640px) {
    .ur-directory__card-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 0.85rem;
    }
}
@media (min-width: 1024px) {
    .ur-directory__card-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }
}
@media (min-width: 1280px) {
    .ur-directory__card-grid {
        grid-template-columns: repeat(4, 1fr);
    }
}

/* Directory Tile Item */
.ur-directory__item {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    padding: 0.75rem 0.85rem;
    background-color: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 0.875rem;
    text-decoration: none;
    transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    min-height: 3.5rem;
}
:is(.dark .ur-directory__item) {
    background-color: #131b2e;
    border-color: #1e293b;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.25);
}

.ur-directory__item:hover {
    transform: translateY(-2px);
    border-color: #93c5fd;
    background-color: #ffffff;
    box-shadow: 0 8px 20px -4px rgba(37, 99, 235, 0.12);
}
:is(.dark .ur-directory__item:hover) {
    border-color: #3b82f6;
    background-color: #1e293b;
    box-shadow: 0 8px 20px -4px rgba(0, 0, 0, 0.5);
}

.ur-directory__icon {
    width: 2rem;
    height: 2rem;
    border-radius: 0.5rem;
    background: #eff6ff;
    color: #2563eb;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.95rem;
    flex-shrink: 0;
    transition: all 0.2s ease;
}
:is(.dark .ur-directory__icon) {
    background: rgba(37, 99, 235, 0.15);
    color: #60a5fa;
}

.ur-directory__item:hover .ur-directory__icon {
    background: #2563eb;
    color: #ffffff;
    transform: scale(1.05);
}

.ur-directory__text {
    flex: 1;
    font-size: 0.8125rem;
    font-weight: 600;
    color: #1e293b;
    line-height: 1.35;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    transition: color 0.2s ease;
}
:is(.dark .ur-directory__text) {
    color: #e2e8f0;
}

.ur-directory__item:hover .ur-directory__text {
    color: #2563eb;
}
:is(.dark .ur-directory__item:hover .ur-directory__text) {
    color: #60a5fa;
}

.ur-directory__arrow {
    font-size: 0.75rem;
    color: #cbd5e1;
    flex-shrink: 0;
    transition: all 0.2s ease;
}
:is(.dark .ur-directory__arrow) {
    color: #475569;
}

.ur-directory__item:hover .ur-directory__arrow {
    color: #2563eb;
    transform: translateX(2px);
}

@media (max-width: 640px) {
    .ur-directory {
        padding-top: 2.25rem;
        padding-bottom: 2.25rem;
    }
    .ur-directory__header {
        margin-bottom: 1.5rem;
    }
    .ur-directory__tabs {
        margin-bottom: 1.25rem;
        gap: 0.35rem;
    }
    .ur-directory__tab-btn {
        padding: 0.45rem 0.9rem;
        font-size: 0.75rem;
    }
    .ur-directory__item {
        padding: 0.6rem 0.65rem;
        gap: 0.5rem;
        border-radius: 0.75rem;
        min-height: 3.25rem;
    }
    .ur-directory__icon {
        width: 1.75rem;
        height: 1.75rem;
        font-size: 0.85rem;
        border-radius: 0.45rem;
    }
    .ur-directory__text {
        font-size: 0.735rem;
        line-height: 1.25;
    }
    .ur-directory__arrow {
        display: none;
    }
}
</style>

@php
    function getDirIconClass($tag) {
        $l = strtolower($tag);
        if (str_contains($l, 'loan') || str_contains($l, 'emi') || str_contains($l, 'calculator') || str_contains($l, 'interest') || str_contains($l, 'rent calculator')) return 'ph-bold ph-calculator';
        if (str_contains($l, 'legal') || str_contains($l, 'agreement') || str_contains($l, 'notary') || str_contains($l, 'affidavit') || str_contains($l, 'lease') || str_contains($l, 'stamp')) return 'ph-bold ph-file-text';
        if (str_contains($l, 'packers') || str_contains($l, 'movers')) return 'ph-bold ph-truck';
        if (str_contains($l, 'clean') || str_contains($l, 'house cleaning')) return 'ph-bold ph-sparkle';
        if (str_contains($l, 'interior') || str_contains($l, 'renovation') || str_contains($l, 'paint')) return 'ph-bold ph-paint-roller';
        if (str_contains($l, 'electric') || str_contains($l, 'ac')) return 'ph-bold ph-lightning';
        if (str_contains($l, 'plumb')) return 'ph-bold ph-wrench';
        if (str_contains($l, 'forum') || str_contains($l, 'queries')) return 'ph-bold ph-chat-circle-dots';
        if (str_contains($l, 'guide') || str_contains($l, 'tips') || str_contains($l, 'nri') || str_contains($l, 'vastu')) return 'ph-bold ph-book-bookmark';
        if (str_contains($l, 'tenant') || str_contains($l, 'landlord')) return 'ph-bold ph-user-check';
        if (str_contains($l, 'management') || str_contains($l, 'diligence')) return 'ph-bold ph-shield-check';
        if (str_contains($l, 'builder') || str_contains($l, 'project') || str_contains($l, 'real estate')) return 'ph-bold ph-buildings';
        return 'ph-bold ph-arrow-up-right';
    }

    $buyTagsRaw = $site_settings['directory_buy_tags'] ?? 'Property Legal Services, Sale Agreement, Home Loan EMI Calculator, Home Loan Balance Transfer, Home Loan Eligibility, Compare Interest Rates, Property Buyers Forum, Property Buyers Guide, Property Seller Guide, Home Loan Queries, Home Renovation Guide, Interior Design Tips, NRI Real Estate Guide, Real Estate Vastu Guide, Due Diligence Service';
    $buyTags = array_filter(array_map('trim', explode(',', $buyTagsRaw)));

    $rentTagsRaw = $site_settings['directory_rent_tags'] ?? 'Rental Agreement Online, Packers and Movers, Property Management, Rent Calculator, Tenant Guide, Landlord Guide, Home Painting Services, Full House Cleaning, AC Services, Electrician Services, Plumbing Services, E-Stamped Lease Agreement, Notary Affidavit';
    $rentTags = array_filter(array_map('trim', explode(',', $rentTagsRaw)));
@endphp

<section class="ur-directory" id="resource-directory">
    <div class="ur-directory__container">
        
        {{-- Section Header --}}
        <div class="ur-directory__header">
            <span class="ur-directory__badge">
                <i class="ph-bold ph-squares-four text-xs"></i>
                Verified Services & Guides
            </span>
            <h2 class="ur-directory__main-title">
                Property Services & Solutions
            </h2>
        </div>

        {{-- Mobile & Desktop Category Switcher Tabs --}}
        <div class="ur-directory__tabs" role="tablist">
            <button type="button" class="ur-directory__tab-btn active" id="tab-buy-btn" onclick="switchDirectoryTab('buy')" role="tab" aria-selected="true" aria-controls="directory-buy-pane">
                <i class="ph-bold ph-shopping-bag"></i>
                <span>Buy Services & Guides</span>
            </button>
            <button type="button" class="ur-directory__tab-btn" id="tab-rent-btn" onclick="switchDirectoryTab('rent')" role="tab" aria-selected="false" aria-controls="directory-rent-pane">
                <i class="ph-bold ph-key"></i>
                <span>Rent & Tenant Solutions</span>
            </button>
        </div>

        {{-- Buy Pane --}}
        <div id="directory-buy-pane" class="ur-directory__pane" role="tabpanel">
            <div class="ur-directory__card-grid">
                @foreach($buyTags as $tag)
                    <a href="{{ route('properties.index', ['purpose' => 'buy']) }}" class="ur-directory__item group" title="{{ $tag }}">
                        <div class="ur-directory__icon">
                            <i class="{{ getDirIconClass($tag) }}"></i>
                        </div>
                        <span class="ur-directory__text">{{ $tag }}</span>
                        <i class="ph-bold ph-caret-right ur-directory__arrow"></i>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Rent Pane --}}
        <div id="directory-rent-pane" class="ur-directory__pane" style="display: none;" role="tabpanel">
            <div class="ur-directory__card-grid">
                @foreach($rentTags as $tag)
                    <a href="{{ route('properties.index', ['purpose' => 'rent']) }}" class="ur-directory__item group" title="{{ $tag }}">
                        <div class="ur-directory__icon">
                            <i class="{{ getDirIconClass($tag) }}"></i>
                        </div>
                        <span class="ur-directory__text">{{ $tag }}</span>
                        <i class="ph-bold ph-caret-right ur-directory__arrow"></i>
                    </a>
                @endforeach
            </div>
        </div>

    </div>
</section>

<script>
    function switchDirectoryTab(tab) {
        const buyPane = document.getElementById('directory-buy-pane');
        const rentPane = document.getElementById('directory-rent-pane');
        const buyBtn = document.getElementById('tab-buy-btn');
        const rentBtn = document.getElementById('tab-rent-btn');

        if (tab === 'buy') {
            if (buyPane) buyPane.style.display = 'block';
            if (rentPane) rentPane.style.display = 'none';
            if (buyBtn) {
                buyBtn.classList.add('active');
                buyBtn.setAttribute('aria-selected', 'true');
            }
            if (rentBtn) {
                rentBtn.classList.remove('active');
                rentBtn.setAttribute('aria-selected', 'false');
            }
        } else {
            if (buyPane) buyPane.style.display = 'none';
            if (rentPane) rentPane.style.display = 'block';
            if (buyBtn) {
                buyBtn.classList.remove('active');
                buyBtn.setAttribute('aria-selected', 'false');
            }
            if (rentBtn) {
                rentBtn.classList.add('active');
                rentBtn.setAttribute('aria-selected', 'true');
            }
        }
    }
</script>

