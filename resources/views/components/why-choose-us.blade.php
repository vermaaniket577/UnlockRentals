{{-- ============================================================
     UNLOCK RENTALS — WHY CHOOSE US & ESSENTIAL SERVICES
     ============================================================ --}}

<style>
.ur-why {
    background-color: #ffffff;
    padding: 3.5rem 0 5rem;
    position: relative;
    overflow: hidden;
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
}

/* Cinematic background accents */
.ur-why__accent {
    position: absolute;
    width: 38rem;
    height: 38rem;
    background: radial-gradient(circle, rgba(37, 99, 235, 0.035) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
    z-index: 0;
}
.ur-why__accent--1 { top: -8%; left: 0; transform: translateX(-10%); }
.ur-why__accent--2 { bottom: -8%; right: 0; transform: translateX(10%); }

.ur-why__container {
    max-width: 82rem;
    margin: 0 auto;
    padding: 0 1.5rem;
    position: relative;
    z-index: 10;
}

/* ─── SERVICES INTRO HEADER ──────────────── */
.ur-why__services-intro {
    text-align: center;
    max-width: 42rem;
    margin: 0 auto 2.5rem;
}

.ur-why__services-eyebrow {
    font-size: 0.75rem;
    font-weight: 700;
    color: #2563eb;
    text-transform: uppercase;
    letter-spacing: 0.15em;
    margin-bottom: 0.625rem;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
}

.ur-why__services-title {
    font-size: 2rem;
    font-weight: 800;
    color: #0f172a;
    letter-spacing: -0.03em;
    line-height: 1.2;
    margin-bottom: 0.5rem;
    font-family: 'Outfit', sans-serif;
}

.ur-why__services-desc {
    font-size: 0.925rem;
    color: #64748b;
    line-height: 1.55;
}

/* ─── SERVICES GRID (6 CARDS) ─────────────── */
.ur-why__services {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.25rem;
    margin-bottom: 5rem;
}

@media (min-width: 640px) {
    .ur-why__services { grid-template-columns: repeat(3, 1fr); }
}

@media (min-width: 1024px) {
    .ur-why__services { grid-template-columns: repeat(6, 1fr); gap: 1rem; }
}

.ur-why__service-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 1.25rem;
    padding: 1.75rem 1rem 1.35rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(15, 23, 42, 0.02);
}

.ur-why__service-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 16px 32px rgba(37, 99, 235, 0.08);
    border-color: #93c5fd;
}

.ur-why__s-badge {
    position: absolute;
    top: 0.75rem;
    right: 0.75rem;
    font-size: 0.75rem;
    font-weight: 700;
    color: #2563eb;
    background: #eff6ff;
    border: 1px solid #dbeafe;
    padding: 0.2rem 0.6rem;
    border-radius: 9999px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.ur-why__s-icon {
    width: 3.25rem;
    height: 3.25rem;
    background: #f8fafc;
    border: 1px solid #f1f5f9;
    border-radius: 0.875rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #2563eb;
    font-size: 1.5rem;
    margin-bottom: 0.875rem;
    transition: all 0.3s ease;
}

.ur-why__service-card:hover .ur-why__s-icon {
    background: #2563eb;
    color: #ffffff;
    border-color: #2563eb;
    transform: scale(1.08);
}

.ur-why__service-card:hover .ur-why__s-icon svg {
    stroke: #ffffff;
}

.ur-why__s-label {
    font-size: 0.95rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 0.25rem;
    letter-spacing: -0.01em;
    font-family: 'Outfit', sans-serif;
}

.ur-why__s-desc {
    font-size: 0.8125rem;
    color: #64748b;
    line-height: 1.45;
    font-weight: 400;
}

/* ─── SECTION HEADER (WHY CHOOSE US) ──────── */
.ur-why__header {
    text-align: center;
    max-width: 44rem;
    margin: 0 auto 3.5rem;
}

.ur-why__subtitle {
    font-size: 0.75rem;
    font-weight: 700;
    color: #2563eb;
    text-transform: uppercase;
    letter-spacing: 0.2em;
    margin-bottom: 0.75rem;
    display: block;
}

.ur-why__title {
    font-size: 2.25rem;
    font-weight: 900;
    color: #0f172a;
    letter-spacing: -0.03em;
    line-height: 1.15;
    font-family: 'Outfit', sans-serif;
}

@media (min-width: 768px) {
    .ur-why__title { font-size: 2.75rem; }
}

.ur-why__title span {
    background: linear-gradient(135deg, #2563eb, #6366f1);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

/* ─── FEATURES GRID ────────────────────── */
.ur-why__features {
    display: grid;
    grid-template-columns: 1fr;
    gap: 0.875rem;
}

@media (min-width: 640px) {
    .ur-why__features { grid-template-columns: repeat(2, 1fr); gap: 1rem; }
}

@media (min-width: 1024px) {
    .ur-why__features { grid-template-columns: repeat(4, 1fr); gap: 1.25rem; }
}

.ur-why__f-card {
    background: #ffffff;
    padding: 1rem 1.15rem;
    border-radius: 1.25rem;
    border: 1px solid #e2e8f0;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    flex-direction: row;
    align-items: center;
    gap: 1rem;
    text-align: left;
    box-shadow: 0 2px 6px rgba(15, 23, 42, 0.02);
}

@media (min-width: 1024px) {
    .ur-why__f-card {
        flex-direction: column;
        align-items: flex-start;
        padding: 1.75rem 1.35rem;
        gap: 0;
    }
}

.ur-why__f-card:hover {
    background: #ffffff;
    border-color: #93c5fd;
    box-shadow: 0 12px 28px rgba(37, 99, 235, 0.08);
    transform: translateY(-2px);
}

.ur-why__f-icon {
    width: 2.75rem;
    height: 2.75rem;
    min-width: 2.75rem;
    background: #eff6ff;
    border: 1px solid #dbeafe;
    color: #2563eb;
    border-radius: 0.875rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    margin-bottom: 0;
    flex-shrink: 0;
    transition: all 0.4s ease;
}

@media (min-width: 1024px) {
    .ur-why__f-icon {
        width: 3.5rem;
        height: 3.5rem;
        min-width: 3.5rem;
        border-radius: 1rem;
        margin-bottom: 1.25rem;
    }
}

.ur-why__f-card:hover .ur-why__f-icon {
    transform: scale(1.06);
    background: #2563eb;
    color: #ffffff;
    border-color: #2563eb;
    box-shadow: 0 8px 20px rgba(37, 99, 235, 0.25);
}

.ur-why__f-card:hover .ur-why__f-icon svg path,
.ur-why__f-card:hover .ur-why__f-icon svg circle,
.ur-why__f-card:hover .ur-why__f-icon svg polygon,
.ur-why__f-card:hover .ur-why__f-icon svg line {
    fill: #ffffff !important;
}

.ur-why__f-body {
    flex: 1;
    min-width: 0;
}

.ur-why__f-title {
    font-size: 1rem;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 0.25rem;
    letter-spacing: -0.015em;
    font-family: 'Outfit', sans-serif;
    line-height: 1.25;
}

@media (min-width: 1024px) {
    .ur-why__f-title {
        font-size: 1.15rem;
        margin-bottom: 0.5rem;
    }
}

.ur-why__f-desc {
    font-size: 0.8125rem;
    color: #64748b;
    line-height: 1.45;
    font-weight: 400;
}
</style>

<section class="ur-why" id="why-choose-us">
    <div class="ur-why__accent ur-why__accent--1"></div>
    <div class="ur-why__accent ur-why__accent--2"></div>

    <div class="ur-why__container">
        
        {{-- Section Intro for Services --}}
        <div class="ur-why__services-intro">
            <span class="ur-why__services-eyebrow">
                <i class="ph-bold ph-sparkle"></i> Value-Added Services
            </span>
            <h2 class="ur-why__services-title">Everything for Your Move & Living</h2>
            <p class="ur-why__services-desc">Verified partner solutions to handle relocation, agreements, rent payments, and asset protection.</p>
        </div>

        {{-- Services Grid (6 Cards) --}}
        <div class="ur-why__services">
            @php
                $services = [
                    [
                        'svg' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>', 
                        'label' => 'Packers & Movers',
                        'desc' => 'Doorstep shifting & packaging',
                        'badge' => 'Premium'
                    ],
                    [
                        'svg' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>', 
                        'label' => 'Rental Agreement',
                        'desc' => 'Instant e-stamped legal lease',
                        'badge' => 'Legal'
                    ],
                    [
                        'svg' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19l7-7 3 3-7 7-3-3z"></path><path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"></path><path d="M2 2l7.586 7.586"></path><circle cx="11" cy="11" r="2"></circle></svg>', 
                        'label' => 'Painting & Cleaning',
                        'desc' => 'Deep sanitization & refresh',
                        'badge' => 'Expert'
                    ],
                    [
                        'svg' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="20" height="12" rx="2"></rect><circle cx="12" cy="12" r="2"></circle><path d="M6 12h.01M18 12h.01"></path></svg>', 
                        'label' => 'Pay Rent Online',
                        'desc' => 'Credit card cashback & rewards',
                        'badge' => 'Secure'
                    ],
                    [
                        'svg' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path><path d="M9 12l2 2 4-4"></path></svg>', 
                        'label' => 'Home Insurance',
                        'desc' => 'Comprehensive damage cover',
                        'badge' => 'Safe'
                    ],
                    [
                        'svg' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>', 
                        'label' => 'NRI Concierge',
                        'desc' => 'End-to-end remote tenant care',
                        'badge' => 'Global'
                    ]
                ];
            @endphp
            @foreach($services as $service)
            <div class="ur-why__service-card">
                <span class="ur-why__s-badge">{{ $service['badge'] }}</span>
                <div class="ur-why__s-icon">
                    {!! $service['svg'] !!}
                </div>
                <h3 class="ur-why__s-label">{{ $service['label'] }}</h3>
                <p class="ur-why__s-desc">{{ $service['desc'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- Header --}}
        <div class="ur-why__header">
            <span class="ur-why__subtitle">The Luxury Registry Difference</span>
            <h2 class="ur-why__title">Why Choose <span>UnlockRentals?</span></h2>
        </div>

        {{-- Features Grid (4 Cards) --}}
        <div class="ur-why__features">
            @php
                $features = [
                    [
                        'svg' => '<svg width="40" height="40" viewBox="0 0 24 24" fill="#2563eb" xmlns="http://www.w3.org/2000/svg"><path d="M12 2a5 5 0 1 0 5 5 5 5 0 0 0-5-5zm0 8a3 3 0 1 1 3-3 3 3 0 0 1-3 3zm9 11v-1a7 7 0 0 0-7-7h-4a7 7 0 0 0-7 7v1h2v-1a5 5 0 0 1 5-5h4a5 5 0 0 1 5 5v1z"/></svg>', 
                        'title' => 'Zero Brokerage', 
                        'desc' => 'Connect directly with verified property owners and save tens of thousands on traditional broker commissions.'
                    ],
                    [
                        'svg' => '<svg width="40" height="40" viewBox="0 0 24 24" fill="#2563eb" xmlns="http://www.w3.org/2000/svg"><path d="M11.47 3.84a.75.75 0 011.06 0l8.69 8.68a.75.75 0 101.06-1.06l-8.689-8.69a2.25 2.25 0 00-3.182 0l-8.69 8.69a.75.75 0 001.061 1.06l8.69-8.68zM12 2.25a.75.75 0 01.75.75v18a.75.75 0 01-1.5 0V3a.75.75 0 01.75-.75z"/></svg>', 
                        'title' => 'Free Owner Listing', 
                        'desc' => 'List your rental or commercial space for free in under 2 minutes with high-resolution photo galleries.'
                    ],
                    [
                        'svg' => '<svg width="40" height="40" viewBox="0 0 24 24" fill="#2563eb" xmlns="http://www.w3.org/2000/svg"><path d="M12 15a3 3 0 100-6 3 3 0 000 6z"/><path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 010-1.113zM17.25 12a5.25 5.25 0 11-10.5 0 5.25 5.25 0 0110.5 0z" clip-rule="evenodd"/></svg>', 
                        'title' => 'Verified Viewings', 
                        'desc' => 'Shortlist authentic homes with complete location intel and book private in-person walkthroughs.'
                    ],
                    [
                        'svg' => '<svg width="40" height="40" viewBox="0 0 24 24" fill="#2563eb" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0016.5 9h-1.875a1.875 1.875 0 01-1.875-1.875V5.25A3.75 3.75 0 009 1.5H5.625zM7.5 15a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5A.75.75 0 017.5 15zm.75 2.25a.75.75 0 000 1.5H12a.75.75 0 000-1.5H8.25z" clip-rule="evenodd"/><path d="M12.971 1.816A5.23 5.23 0 0114.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 013.434 1.279 9.768 9.768 0 00-6.963-6.963z"/></svg>', 
                        'title' => 'Digital Legal Leases', 
                        'desc' => 'Legally binding, e-stamped digital rental agreements drafted and signed from your smartphone.'
                    ]
                ];
            @endphp
            @foreach($features as $feature)
            <div class="ur-why__f-card">
                <div class="ur-why__f-icon">
                    {!! $feature['svg'] !!}
                </div>
                <div class="ur-why__f-body">
                    <h3 class="ur-why__f-title">{{ $feature['title'] }}</h3>
                    <p class="ur-why__f-desc">{{ $feature['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>
