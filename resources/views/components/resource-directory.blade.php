{{-- ============================================================
     UNLOCK RENTALS — RESOURCE DIRECTORY (VANILLA CSS & DYNAMIC)
     ============================================================ --}}

<style>
.ur-directory {
    padding-top: 4.5rem;
    padding-bottom: 4.5rem;
    background-color: #fafaf9;
    border-top: 1px solid #e7e5e4;
    font-family: 'Inter', sans-serif;
    overflow: hidden;
}

.ur-directory__container {
    max-width: 80rem;
    margin-left: auto;
    margin-right: auto;
    padding-left: 1.5rem;
    padding-right: 1.5rem;
}

.ur-directory__grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 3.5rem;
}

@media (min-width: 1024px) {
    .ur-directory__grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 4.5rem;
    }
}

.ur-directory__title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #e2e8f0;
}

.ur-directory__title i {
    font-size: 1.35rem;
    color: #2563eb;
}

.ur-directory__title span {
    font-size: 1rem;
    font-weight: 800;
    color: #0f172a;
    letter-spacing: -0.01em;
    font-family: 'Outfit', sans-serif;
}

.ur-directory__tags {
    display: flex;
    flex-wrap: wrap;
    gap: 0.625rem;
    line-height: 1.6;
}

.ur-directory__tag {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 0.875rem;
    background-color: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    font-size: 0.8125rem;
    font-weight: 600;
    color: #475569;
    text-decoration: none;
    transition: all 0.2s ease;
    box-shadow: 0 1px 2px rgba(0,0,0,0.03);
}

.ur-directory__tag:hover {
    color: #2563eb;
    border-color: #93c5fd;
    background-color: #eff6ff;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(37, 99, 235, 0.08);
}
</style>

<section class="ur-directory" id="resource-directory">
    <div class="ur-directory__container">
        
        <div class="ur-directory__grid">
            
            {{-- Column: Buy Resources --}}
            <div>
                <h3 class="ur-directory__title">
                    <i class="ph-bold ph-shopping-bag"></i>
                    <span>Buy Services & Property Guides</span>
                </h3>
                <div class="ur-directory__tags">
                    @php
                        $buyTagsRaw = $site_settings['directory_buy_tags'] ?? 'Property Legal Services, Sale Agreement, Home Loan EMI Calculator, Home Loan Balance Transfer, Home Loan Eligibility, Compare Interest Rates, Property Buyers Forum, Property Buyers Guide, Property Seller Guide, Home Loan Queries, Home Renovation Guide, Interior Design Tips, NRI Real Estate Guide, Real Estate Vastu Guide, Due Diligence Service';
                        $buyTags = array_filter(array_map('trim', explode(',', $buyTagsRaw)));
                    @endphp
                    @foreach($buyTags as $tag)
                    <a href="{{ route('properties.index', ['purpose' => 'buy']) }}" class="ur-directory__tag">{{ $tag }}</a>
                    @endforeach
                </div>
            </div>

            {{-- Column: Rent Resources --}}
            <div>
                <h3 class="ur-directory__title">
                    <i class="ph-bold ph-key"></i>
                    <span>Rent Services & Tenant Solutions</span>
                </h3>
                <div class="ur-directory__tags">
                    @php
                        $rentTagsRaw = $site_settings['directory_rent_tags'] ?? 'Rental Agreement Online, Packers and Movers, Property Management, Rent Calculator, Tenant Guide, Landlord Guide, Home Painting Services, Full House Cleaning, AC Services, Electrician Services, Plumbing Services, E-Stamped Lease Agreement, Notary Affidavit';
                        $rentTags = array_filter(array_map('trim', explode(',', $rentTagsRaw)));
                    @endphp
                    @foreach($rentTags as $tag)
                    <a href="{{ route('properties.index', ['purpose' => 'rent']) }}" class="ur-directory__tag">{{ $tag }}</a>
                    @endforeach
                </div>
            </div>

        </div>

    </div>
</section>
