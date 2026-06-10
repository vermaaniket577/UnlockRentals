{{-- ============================================================
     UNLOCK RENTALS — RESOURCE DIRECTORY (VANILLA CSS & DYNAMIC)
     ============================================================ --}}

<style>
.ur-directory {
    padding-top: 5rem;
    padding-bottom: 5rem;
    background-color: #fafaf9;
    border-top: 1px solid #e7e5e4;
    font-family: 'Inter', sans-serif;
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
    gap: 4rem;
}

@media (min-width: 1024px) {
    .ur-directory__grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 5rem;
    }
}

    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e7e5e4;
}

.ur-directory__title i {
    font-size: 1.25rem;
    color: #2563eb;
}

.ur-directory__title span {
    font-size: 0.75rem;
    font-weight: 900;
    color: #18181b;
    text-transform: uppercase;
    letter-spacing: 0.2em;
}

.ur-directory__tags {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.ur-directory__tag {
    display: inline-block;
    padding: 0.375rem 0.75rem;
    background-color: #ffffff;
    border: 1px solid #e7e5e4;
    border-radius: 0.5rem;
    font-size: 0.6875rem;
    font-weight: 700;
    color: #71717a;
    text-decoration: none;
    transition: all 0.3s;
}

.ur-directory__tag:hover {
    color: #2563eb;
    border-color: #2563eb;
    background-color: #eff6ff;
    transform: translateY(-1px);
}
</style>

<section class="ur-directory" id="resource-directory">
    <div class="ur-directory__container">
        
        <div class="ur-directory__grid">
            
            {{-- Column: Buy Resources --}}
            <div>
                <h3 class="ur-directory__title">
                    <i class="ph-bold ph-shopping-bag"></i>
                    <span>Buy Services & Guides</span>
                </h3>
                <div class="ur-directory__tags">
                    @php
                        $buyTagsRaw = $site_settings['directory_buy_tags'] ?? 'Property Legal Services, Interiors, Sale Agreement, NoBroker For NRIs, New Builder Project, Home Loan EMI Calculator, Home Loan Balance Transfer, Home Loan Eligibility Calculator, Apply Home Loan, Compare Home Loan Interest, Property Buyers Forum, Property Buyers Guide, Property Seller Guide, Home Loan Guide, Home Loan Queries, Home Renovation Guide, Home Renovation Queries, Interior Design Tips, Interior Design Queries, NRI RealEstate Guide, NRI RealEstate Queries, Realestate Vastu Guide, Personal Loan Guide, Personal Loan Queries, Bill Payment Guide, Realestate Legal Guide, Realestate Legal Queries, e-AASTHI BBMP, Due Diligence Service';
                        $buyTags = array_filter(array_map('trim', explode(',', $buyTagsRaw)));
                    @endphp
                    @foreach($buyTags as $tag)
                    <a href="#" class="ur-directory__tag">{{ $tag }}</a>
                    @endforeach
                </div>
            </div>

            {{-- Column: Rent Resources --}}
            <div>
                <h3 class="ur-directory__title">
                    <i class="ph-bold ph-key"></i>
                    <span>Rent Services & Guides</span>
                </h3>
                <div class="ur-directory__tags">
                    @php
                        $rentTagsRaw = $site_settings['directory_rent_tags'] ?? 'Rental Agreement, Pay Tuition Fee, Refer and Earn, Packers and Movers, Property Management in India, Home Services Questions, Rent Services Questions, Rent Calculator, Property Rental Guide, Landlord Guide, Tenant Guide, Packers and Movers Guide, Packers and Movers queries, Home Services, Home Services Queries, Painting Services, Home Painting Guide, Home Painting Queries, Cleaning Services, Kitchen Cleaning Services, Sofa Cleaning Services, Bathroom Cleaning Services, Full House Cleaning Services, Home Cleaning Guide, Home Cleaning Queries, AC Services, Carpentry Services, Carpentry Services Queries, Electrician Services, Electrician Services Queries, Plumbing Services, Plumbing Services Queries, Lease Agreement, Notary, Notary Advocate, Notary Affidavit';
                        $rentTags = array_filter(array_map('trim', explode(',', $rentTagsRaw)));
                    @endphp
                    @foreach($rentTags as $tag)
                    <a href="#" class="ur-directory__tag">{{ $tag }}</a>
                    @endforeach
                </div>
            </div>

        </div>

    </div>
</section>
