{{-- ============================================================
     UNLOCK RENTALS — PREMIUM APP DOWNLOAD (LUXURY REGISTRY)
     ============================================================ --}}

<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800;900&display=swap');

.ur-app {
    padding: 8rem 0;
    background: radial-gradient(circle at 10% 20%, rgba(37, 99, 235, 0.03) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(99, 102, 241, 0.03) 0%, transparent 40%),
                #ffffff;
    overflow: hidden;
    font-family: 'Outfit', 'Inter', sans-serif;
    position: relative;
}

/* Subtle background pattern */
.ur-app::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image: radial-gradient(#e5e7eb 0.5px, transparent 0.5px);
    background-size: 40px 40px;
    opacity: 0.3;
    pointer-events: none;
}

.ur-app__container {
    max-width: 80rem;
    margin: 0 auto;
    padding: 0 1.5rem;
    position: relative;
    z-index: 10;
}

.ur-app__flex {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6rem;
}

@media (min-width: 1024px) {
    .ur-app__flex {
        flex-direction: row;
        align-items: center;
    }
}

/* ─── LEFT SIDE: MOCKUP & CARDS ──────────────── */
.ur-app__visuals {
    flex: 1.2;
    position: relative;
    width: 100%;
    display: flex;
    justify-content: center;
}

.ur-app__mockup-wrap {
    position: relative;
    width: 100%;
    max-width: 32rem;
    animation: ur-float 6s ease-in-out infinite;
}

.ur-app__img {
    width: 100%;
    height: auto;
    filter: drop-shadow(0 40px 80px rgba(0, 0, 0, 0.12));
    position: relative;
    z-index: 5;
}

/* Floating Glassmorphic Cards */
.ur-app__floating-card {
    position: absolute;
    padding: 1rem 1.5rem;
    background: rgba(255, 255, 255, 0.7);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.5);
    border-radius: 1.25rem;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.06);
    z-index: 10;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.ur-app__card--1 {
    top: 10%;
    left: -5%;
    animation: ur-float-alt 7s ease-in-out infinite;
}

.ur-app__card--2 {
    bottom: 20%;
    right: -10%;
    animation: ur-float 5s ease-in-out infinite;
}

.ur-app__card-icon {
    width: 2.5rem;
    height: 2.5rem;
    background-color: #2563eb;
    color: white;
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.ur-app__card-text {
    display: flex;
    flex-direction: column;
}

.ur-app__card-title {
    font-size: 0.75rem;
    font-weight: 800;
    color: #111827;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.ur-app__card-status {
    font-size: 0.875rem;
    color: #6b7280;
    font-weight: 400;
}

.ur-app__glow {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 120%;
    height: 120%;
    background: radial-gradient(circle, rgba(37, 99, 235, 0.08) 0%, transparent 70%);
    pointer-events: none;
    z-index: 0;
}

/* ─── RIGHT SIDE: CONTENT ─────────────────── */
.ur-app__content {
    flex: 1;
    text-align: center;
}

@media (min-width: 1024px) {
    .ur-app__content { text-align: left; }
}

.ur-app__badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background-color: #eff6ff;
    color: #2563eb;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.2em;
    margin-bottom: 2rem;
}

.ur-app__title {
    font-size: 3rem;
    font-weight: 900;
    color: #111827;
    line-height: 1.1;
    letter-spacing: -0.04em;
    margin-bottom: 1.5rem;
}

@media (min-width: 768px) {
    .ur-app__title { font-size: 4rem; }
}

.ur-app__title span {
    background: linear-gradient(135deg, #2563eb, #6366f1);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.ur-app__desc {
    font-size: 1.25rem;
    color: #4b5563;
    line-height: 1.6;
    font-weight: 300;
    margin-bottom: 3rem;
    max-width: 32rem;
}

@media (min-width: 1024px) {
    .ur-app__desc { margin-left: 0; }
}

/* Store Buttons - Polished & Clean */
.ur-app__stores {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 1rem;
}

@media (min-width: 1024px) {
    .ur-app__stores { justify-content: flex-start; }
}

/* Store Buttons - Premium Glassmorphic */
.ur-app__btn {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    padding: 1rem 2.25rem;
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 1.25rem;
    color: white;
    text-decoration: none;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    position: relative;
    box-shadow: 
        0 10px 20px -5px rgba(0, 0, 0, 0.3),
        inset 0 1px 0 rgba(255, 255, 255, 0.1);
}

.ur-app__btn i {
    font-size: 2.25rem;
    color: #ffffff;
    filter: drop-shadow(0 0 8px rgba(255, 255, 255, 0.3));
}

.ur-app__btn:hover {
    transform: translateY(-5px) scale(1.02);
    background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
    border-color: rgba(37, 99, 235, 0.5);
    box-shadow: 
        0 20px 40px -10px rgba(0, 0, 0, 0.4),
        0 0 20px rgba(37, 99, 235, 0.2);
}

.ur-app__btn-text {
    display: flex;
    flex-direction: column;
    text-align: left;
}

.ur-app__btn-label {
    font-size: 0.6875rem;
    text-transform: uppercase;
    font-weight: 700;
    color: rgba(255, 255, 255, 0.5);
    letter-spacing: 0.15em;
    margin-bottom: 2px;
}

.ur-app__btn-name {
    font-size: 1.25rem;
    font-weight: 800;
    letter-spacing: -0.02em;
    color: #ffffff;
}

/* ─── ANIMATIONS ───────────────────────── */
@keyframes ur-float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-20px); }
}

@keyframes ur-float-alt {
    0%, 100% { transform: translateY(0) translateX(0); }
    50% { transform: translateY(-15px) translateX(10px); }
}

.ur-app__bg-circles {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 1;
}

.ur-app__circle {
    position: absolute;
    border-radius: 50%;
    filter: blur(80px);
}

.ur-app__circle--1 {
    width: 400px;
    height: 400px;
    background-color: rgba(37, 99, 235, 0.05);
    top: -100px;
    left: -100px;
}
</style>

<section class="ur-app" id="app-download">
    <div class="ur-app__bg-circles">
        <div class="ur-app__circle ur-app__circle--1"></div>
    </div>

    <div class="ur-app__container">
        <div class="ur-app__flex">
            
            {{-- Visual Column --}}
            <div class="ur-app__visuals">
                <div class="ur-app__glow"></div>
                <div class="ur-app__mockup-wrap">
                    {{-- Generated Mockup --}}
                    <img src="{{ asset('unlockrental_premium_mockup_1778934329998.png') }}" alt="UnlockRentals Luxury App" class="ur-app__img">
                    
                    {{-- Floating Glass Cards --}}
                    <div class="ur-app__floating-card ur-app__card--1">
                        <div class="ur-app__card-icon">
                            <i class="ph-bold ph-house-line"></i>
                        </div>
                        <div class="ur-app__card-text">
                            <span class="ur-app__card-title">New Listing</span>
                            <span class="ur-app__card-status">Sea View Villa</span>
                        </div>
                    </div>

                    <div class="ur-app__floating-card ur-app__card--2">
                        <div class="ur-app__card-icon" style="background-color: #10b981;">
                            <i class="ph-bold ph-shield-check"></i>
                        </div>
                        <div class="ur-app__card-text">
                            <span class="ur-app__card-title">Verified</span>
                            <span class="ur-app__card-status">Premium Owner</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Content Column --}}
            <div class="ur-app__content">
                <div class="ur-app__badge">
                    <i class="ph-fill ph-sparkle"></i>
                    Experience Anywhere
                </div>
                
                <h2 class="ur-app__title">
                    Find Your <br><span>Perfect Home</span> <br>Anywhere
                </h2>
                
                <p class="ur-app__desc">
                    Unlock India's most exclusive rental registry. Experience intelligent property search, virtual concierge support, and seamless digital viewings in one sophisticated app.
                </p>

                <div class="ur-app__stores">
                    <a href="{{ $site_settings['app_google_play_url'] ?? '#' }}" target="_blank" class="ur-store-badge">
                        <div class="ur-store-badge__icon">
                            <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                                <path d="M32.5 17.5C29.6 20.3 28 24.4 28 29.5v453c0 5.1 1.6 9.2 4.5 12l1.5 1.5L257 273v-6l-223-251-1.5 1.5z" fill="#00a3ff"/>
                                <path d="M353.5 173.5l-96.5 96.5v6l96.5 96.5 1.5-1 113.5-64.5c32.5-18.5 32.5-48.5 0-67L355 174.5l-1.5-1z" fill="#ffc107"/>
                                <path d="M257 276.5l-223 223c4.5 4.5 11.5 5 20 0.5l301-171-98-52.5z" fill="#ff3d00"/>
                                <path d="M257 269.5l98-52.5-301-171-8.5-4.5-15.5-4-20 0.5l223 223z" fill="#4caf50"/>
                            </svg>
                        </div>
                        <div class="ur-store-badge__content">
                            <span class="ur-store-badge__label">Get it on</span>
                            <span class="ur-store-badge__title">Google Play</span>
                        </div>
                    </a>
                    
                    <a href="{{ $site_settings['app_store_url'] ?? '#' }}" target="_blank" class="ur-store-badge">
                        <div class="ur-store-badge__icon">
                            <svg viewBox="0 0 384 512" xmlns="http://www.w3.org/2000/svg">
                                <path fill="currentColor" d="M318.7 268.7c-.2-36.7 16.4-64.4 50-84.8-18.8-26.9-47.2-41.7-84.7-44.6-35.5-2.8-74.3 20.7-88.5 20.7-15 0-49.4-19.7-76.4-19.7C63.3 141.2 4 184.8 4 273.5q0 39.3 14.4 81.2c12.8 36.7 59 126.7 107.2 125.2 25.2-.6 43-17.9 75.8-17.9 31.8 0 48.3 17.9 76.4 17.9 48.6-.7 90.4-82.5 102.6-119.3-65.2-31.4-57.3-114.3-1.7-114.3-1.4 0-1.4 0 0 0zm-7.9-167.2c31.7-36.7 22.1-85 21.6-86.3-4.2.3-51.4 14.4-83.6 51.8-29.8 35.8-22.6 78.4-20.1 82.6 4.3.4 50.4-11.4 82.1-48.1z"/>
                            </svg>
                        </div>
                        <div class="ur-store-badge__content">
                            <span class="ur-store-badge__label">Download on the</span>
                            <span class="ur-store-badge__title">App Store</span>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>
