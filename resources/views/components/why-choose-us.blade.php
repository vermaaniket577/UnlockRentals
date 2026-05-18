{{-- ============================================================
     UNLOCK RENTAL — WHY CHOOSE US (PREMIUM MODERN VERSION)
     ============================================================ --}}

<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800;900&display=swap');

.ur-why {
    background-color: #ffffff;
    padding: 8rem 0;
    position: relative;
    overflow: hidden;
    font-family: 'Outfit', 'Inter', sans-serif;
}

/* Cinematic background accents */
.ur-why__accent {
    position: absolute;
    width: 40rem;
    height: 40rem;
    background: radial-gradient(circle, rgba(37, 99, 235, 0.03) 0%, transparent 70%);
    border-radius: 50%;
    pointer-events: none;
    z-index: 0;
}
.ur-why__accent--1 { top: -10%; left: -10%; }
.ur-why__accent--2 { bottom: -10%; right: -10%; }

.ur-why__container {
    max-width: 85rem;
    margin: 0 auto;
    padding: 0 1.5rem;
    position: relative;
    z-index: 10;
}

/* ─── SERVICES BAR (TOP) ────────────────── */
.ur-why__services {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
    margin-bottom: 8rem;
}

@media (min-width: 768px) {
    .ur-why__services { grid-template-columns: repeat(3, 1fr); }
}

@media (min-width: 1024px) {
    .ur-why__services { grid-template-columns: repeat(6, 1fr); }
}

.ur-why__service-card {
    background: #ffffff;
    border: 1px solid rgba(0, 0, 0, 0.04);
    border-radius: 1.5rem;
    padding: 2rem 1rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.ur-why__service-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05);
    border-color: rgba(37, 99, 235, 0.1);
}

.ur-why__s-badge {
    position: absolute;
    top: 0.75rem;
    right: 0.75rem;
    font-size: 0.625rem;
    font-weight: 800;
    color: #2563eb;
    background: #eff6ff;
    padding: 0.25rem 0.625rem;
    border-radius: 9999px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.ur-why__s-icon {
    width: 3.5rem;
    height: 3.5rem;
    background: #f8fafc;
    border-radius: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #64748b;
    font-size: 1.75rem;
    margin-bottom: 1.25rem;
    transition: all 0.4s;
}

.ur-why__service-card:hover .ur-why__s-icon {
    background: #2563eb;
    color: #ffffff;
    transform: scale(1.1) rotate(5deg);
}

.ur-why__s-label {
    font-size: 0.8125rem;
    font-weight: 700;
    color: #1e293b;
    text-align: center;
    text-transform: uppercase;
    letter-spacing: 0.02em;
}

/* ─── SECTION HEADER ───────────────────── */
.ur-why__header {
    text-align: center;
    max-width: 48rem;
    margin: 0 auto 5rem;
}

.ur-why__subtitle {
    font-size: 0.75rem;
    font-weight: 800;
    color: #2563eb;
    text-transform: uppercase;
    letter-spacing: 0.3em;
    margin-bottom: 1.25rem;
    display: block;
}

.ur-why__title {
    font-size: 2.5rem;
    font-weight: 900;
    color: #0f172a;
    letter-spacing: -0.04em;
    line-height: 1.1;
}

@media (min-width: 768px) {
    .ur-why__title { font-size: 3.5rem; }
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
    gap: 2.5rem;
}

@media (min-width: 768px) {
    .ur-why__features { grid-template-columns: repeat(2, 1fr); }
}

@media (min-width: 1024px) {
    .ur-why__features { grid-template-columns: repeat(4, 1fr); }
}

.ur-why__f-card {
    background: #ffffff;
    padding: 2.5rem;
    border-radius: 2rem;
    border: 1px solid rgba(0, 0, 0, 0.03);
    transition: all 0.4s;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

@media (min-width: 1024px) {
    .ur-why__f-card { align-items: flex-start; text-align: left; }
}

.ur-why__f-card:hover {
    background: #fcfcfd;
    border-color: rgba(37, 99, 235, 0.1);
    transform: translateY(-5px);
}

.ur-why__f-icon {
    width: 5.5rem;
    height: 5.5rem;
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    border: 2px solid #bfdbfe;
    color: #2563eb;
    border-radius: 1.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    margin-bottom: 2.5rem;
    transition: all 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
    box-shadow: 0 15px 35px rgba(37, 99, 235, 0.08);
    position: relative;
}

.ur-why__f-card:hover .ur-why__f-icon {
    transform: scale(1.1) rotate(12deg);
    background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
    border-color: #2563eb;
    box-shadow: 0 25px 50px rgba(37, 99, 235, 0.25);
}

.ur-why__f-card:hover .ur-why__f-icon svg {
    filter: brightness(0) invert(1);
}

.ur-why__f-title {
    font-size: 1.5rem;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 1rem;
    letter-spacing: -0.02em;
}

.ur-why__f-desc {
    font-size: 1rem;
    color: #64748b;
    line-height: 1.6;
    font-weight: 400;
}
</style>

<section class="ur-why" id="why-choose-us">
    <div class="ur-why__accent ur-why__accent--1"></div>
    <div class="ur-why__accent ur-why__accent--2"></div>

    <div class="ur-why__container">
        
        {{-- Services Grid --}}
        <div class="ur-why__services">
            @php
                $services = [
                    [
                        'svg' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>', 
                        'label' => 'Packers & Movers', 'badge' => 'Premium'
                    ],
                    [
                        'svg' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>', 
                        'label' => 'Rental Agreement', 'badge' => 'Legal'
                    ],
                    [
                        'svg' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19l7-7 3 3-7 7-3-3z"></path><path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"></path><path d="M2 2l7.586 7.586"></path><circle cx="11" cy="11" r="2"></circle></svg>', 
                        'label' => 'Painting & Cleaning', 'badge' => 'Expert'
                    ],
                    [
                        'svg' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="20" height="12" rx="2"></rect><circle cx="12" cy="12" r="2"></circle><path d="M6 12h.01M18 12h.01"></path></svg>', 
                        'label' => 'Pay Rent', 'badge' => 'Secure'
                    ],
                    [
                        'svg' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path><path d="M9 12l2 2 4-4"></path></svg>', 
                        'label' => 'Home Insurance', 'badge' => 'Safe'
                    ],
                    [
                        'svg' => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>', 
                        'label' => 'NRI Services', 'badge' => 'Global'
                    ]
                ];
            @endphp
            @foreach($services as $service)
            <div class="ur-why__service-card">
                <span class="ur-why__s-badge">{{ $service['badge'] }}</span>
                <div class="ur-why__s-icon">
                    {!! $service['svg'] !!}
                </div>
                <h4 class="ur-why__s-label">{{ $service['label'] }}</h4>
            </div>
            @endforeach
        </div>

        {{-- Header --}}
        <div class="ur-why__header">
            <span class="ur-why__subtitle">The Luxury Registry Difference</span>
            <h2 class="ur-why__title">Why Use <span>UnlockRental?</span></h2>
        </div>

        {{-- Features Grid --}}
        <div class="ur-why__features">
            @php
                $features = [
                    [
                        'svg' => '<svg width="48" height="48" viewBox="0 0 24 24" fill="#2563eb" xmlns="http://www.w3.org/2000/svg"><path d="M12 2a5 5 0 1 0 5 5 5 5 0 0 0-5-5zm0 8a3 3 0 1 1 3-3 3 3 0 0 1-3 3zm9 11v-1a7 7 0 0 0-7-7h-4a7 7 0 0 0-7 7v1h2v-1a5 5 0 0 1 5-5h4a5 5 0 0 1 5 5v1z"/></svg>', 
                        'title' => 'Zero Brokerage', 
                        'desc' => 'Connect directly with verified owners and save thousands on brokerage fees.'
                    ],
                    [
                        'svg' => '<svg width="48" height="48" viewBox="0 0 24 24" fill="#2563eb" xmlns="http://www.w3.org/2000/svg"><path d="M11.47 3.84a.75.75 0 011.06 0l8.69 8.68a.75.75 0 101.06-1.06l-8.689-8.69a2.25 2.25 0 00-3.182 0l-8.69 8.69a.75.75 0 001.061 1.06l8.69-8.68zM12 2.25a.75.75 0 01.75.75v18a.75.75 0 01-1.5 0V3a.75.75 0 01.75-.75z"/></svg>', 
                        'title' => 'Free Listing', 
                        'desc' => 'List your premium property for free in under 5 minutes with our smart onboarding.'
                    ],
                    [
                        'svg' => '<svg width="48" height="48" viewBox="0 0 24 24" fill="#2563eb" xmlns="http://www.w3.org/2000/svg"><path d="M12 15a3 3 0 100-6 3 3 0 000 6z"/><path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 010-1.113zM17.25 12a5.25 5.25 0 11-10.5 0 5.25 5.25 0 0110.5 0z" clip-rule="evenodd"/></svg>', 
                        'title' => 'Digital Viewings', 
                        'desc' => 'Explore immersive virtual tours and shortlist properties without leaving your home.'
                    ],
                    [
                        'svg' => '<svg width="48" height="48" viewBox="0 0 24 24" fill="#2563eb" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0016.5 9h-1.875a1.875 1.875 0 01-1.875-1.875V5.25A3.75 3.75 0 009 1.5H5.625zM7.5 15a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5A.75.75 0 017.5 15zm.75 2.25a.75.75 0 000 1.5H12a.75.75 0 000-1.5H8.25z" clip-rule="evenodd"/><path d="M12.971 1.816A5.23 5.23 0 0114.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 013.434 1.279 9.768 9.768 0 00-6.963-6.963z"/></svg>', 
                        'title' => 'Legal Assistance', 
                        'desc' => 'End-to-end support for digital rental agreements and professional paperwork.'
                    ]
                ];
            @endphp
            @foreach($features as $feature)
            <div class="ur-why__f-card">
                <div class="ur-why__f-icon">
                    {!! $feature['svg'] !!}
                </div>
                <h3 class="ur-why__f-title">{{ $feature['title'] }}</h3>
                <p class="ur-why__f-desc">{{ $feature['desc'] }}</p>
            </div>
            @endforeach
        </div>

    </div>
</section>
