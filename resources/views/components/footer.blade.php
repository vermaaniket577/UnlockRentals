{{-- ============================================================
     UNLOCK RENTAL — LUXURY REGISTRY FOOTER (REMASTERED - NO TAILWIND)
     ============================================================ --}}

<style>
.ur-footer {
    background-color: #020617;
    border-top: 1px solid rgba(255, 255, 255, 0.05);
    padding-top: 6rem;
    padding-bottom: 3rem;
    overflow: hidden;
    position: relative;
    font-family: 'Inter', sans-serif;
}

.ur-footer__glow-1 {
    position: absolute;
    top: -12rem;
    left: -12rem;
    width: 37.5rem;
    height: 37.5rem;
    background-color: rgba(37, 99, 235, 0.1);
    border-radius: 9999px;
    filter: blur(120px);
    pointer-events: none;
}

.ur-footer__glow-2 {
    position: absolute;
    top: 50%;
    right: -6rem;
    width: 24rem;
    height: 24rem;
    background-color: rgba(99, 102, 241, 0.1);
    border-radius: 9999px;
    filter: blur(100px);
    pointer-events: none;
}

.ur-footer__container {
    max-width: 80rem;
    margin-left: auto;
    margin-right: auto;
    padding-left: 1rem;
    padding-right: 1rem;
    position: relative;
    z-index: 10;
}

/* ─── CTA CARD ─────────────────────────── */
.ur-footer__cta {
    margin-bottom: 6rem;
    padding: 3rem;
    border-radius: 2.5rem;
    background: linear-gradient(to right, rgba(255, 255, 255, 0.05), rgba(255, 255, 255, 0.02));
    border: 1px solid rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(40px);
    -webkit-backdrop-filter: blur(40px);
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-between;
    gap: 2.5rem;
}

@media (min-width: 768px) {
    .ur-footer__cta {
        flex-direction: row;
        text-align: left;
    }
}

.ur-footer__cta-title {
    font-size: 1.875rem;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 1rem;
    letter-spacing: -0.025em;
}

@media (min-width: 768px) {
    .ur-footer__cta-title { font-size: 2.25rem; }
}

.ur-footer__cta-accent { color: #3b82f6; }

.ur-footer__cta-desc {
    color: #a1a1aa;
    font-size: 1.125rem;
    font-weight: 300;
    line-height: 1.625;
}

.ur-footer__cta-actions {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    width: 100%;
}

@media (min-width: 640px) {
    .ur-footer__cta-actions {
        flex-direction: row;
        width: auto;
    }
}

.ur-footer__btn {
    padding: 1rem 2rem;
    border-radius: 1rem;
    font-weight: 700;
    text-align: center;
    transition: all 0.3s;
    text-decoration: none;
}

.ur-footer__btn--primary {
    background-color: #2563eb;
    color: #ffffff;
    box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.2);
}

.ur-footer__btn--primary:hover { background-color: #3b82f6; }

.ur-footer__btn--secondary {
    background-color: rgba(255, 255, 255, 0.05);
    color: #ffffff;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.ur-footer__btn--secondary:hover { background-color: rgba(255, 255, 255, 0.1); }

/* ─── GRID ─────────────────────────────── */
.ur-footer__grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 4rem;
    margin-bottom: 6rem;
}

@media (min-width: 768px) {
    .ur-footer__grid { grid-template-columns: repeat(2, 1fr); }
}

@media (min-width: 1024px) {
    .ur-footer__grid { grid-template-columns: repeat(4, 1fr); gap: 3rem; }
}

.ur-footer__col {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

@media (min-width: 768px) {
    .ur-footer__col { align-items: flex-start; text-align: left; }
}

.ur-footer__brand-desc {
    color: #a1a1aa;
    font-size: 1rem;
    font-weight: 300;
    line-height: 1.625;
    margin-bottom: 2.5rem;
    max-width: 20rem;
}

.ur-footer__socials {
    display: flex;
    gap: 1.25rem;
}

.ur-footer__social-link {
    width: 3rem;
    height: 3rem;
    background-color: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.05);
    border-radius: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #71717a;
    transition: all 0.3s;
    text-decoration: none;
}

.ur-footer__social-link:hover {
    background-color: rgba(255, 255, 255, 0.08);
    border-color: rgba(255, 255, 255, 0.2);
    transform: translateY(-0.25rem);
    color: #ffffff;
}

.ur-footer__heading {
    font-size: 0.75rem;
    font-weight: 900;
    color: #ffffff;
    text-transform: uppercase;
    letter-spacing: 0.3em;
    margin-bottom: 2.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid rgba(37, 99, 235, 0.5);
    display: inline-block;
}

.ur-footer__nav {
    list-style: none;
    padding: 0;
    margin: 0;
    width: 100%;
}

.ur-footer__nav-item { margin-bottom: 1.25rem; }

.ur-footer__nav-link {
    color: #a1a1aa;
    text-decoration: none;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.875rem;
}

.ur-footer__nav-link i {
    font-size: 1.125rem;
    color: #2563eb;
    opacity: 0.8;
}

@media (min-width: 768px) {
    .ur-footer__nav-link { justify-content: flex-start; }
}

.ur-footer__nav-link:hover { color: #ffffff; }

.ur-footer__dot {
    width: 0.375rem;
    height: 0.375rem;
    background-color: #2563eb;
    border-radius: 9999px;
    transform: scale(0);
    transition: transform 0.3s;
}

.ur-footer__nav-link:hover .ur-footer__dot { transform: scale(1); }

.ur-footer__contact-group { margin-bottom: 2rem; }

.ur-footer__contact-label {
    font-size: 0.625rem;
    font-weight: 900;
    color: #71717a;
    text-transform: uppercase;
    letter-spacing: 0.2em;
    margin-bottom: 0.5rem;
}

.ur-footer__contact-value {
    color: #ffffff;
    font-weight: 700;
    font-size: 1.125rem;
    text-decoration: none;
    transition: all 0.3s;
}

.ur-footer__contact-value:hover {
    text-decoration: underline;
    text-underline-offset: 8px;
    text-decoration-color: rgba(37, 99, 235, 0.4);
}

/* ─── BOTTOM BAR ───────────────────────── */
.ur-footer__bottom {
    padding-top: 3rem;
    border-top: 1px solid rgba(255, 255, 255, 0.05);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2rem;
}

.ur-footer__legal {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 3rem;
}

.ur-footer__legal-link {
    font-size: 0.75rem;
    font-weight: 900;
    color: #71717a;
    text-transform: uppercase;
    letter-spacing: 0.2em;
    text-decoration: none;
    transition: color 0.3s;
}

.ur-footer__legal-link:hover { color: #ffffff; }

.ur-footer__copyright {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.75rem;
}

.ur-footer__copy-text {
    font-size: 0.625rem;
    font-weight: 900;
    color: #52525b;
    text-transform: uppercase;
    letter-spacing: 0.4em;
}

.ur-footer__excellence {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.ur-footer__line {
    width: 2rem;
    height: 1px;
    background-color: rgba(255, 255, 255, 0.1);
}

.ur-footer__excellence-text {
    font-size: 0.625rem;
    font-weight: 700;
    color: #3f3f46;
    font-style: italic;
    letter-spacing: 0.1em;
}
</style>

<footer class="ur-footer" id="main-footer">
    <div class="ur-footer__glow-1"></div>
    <div class="ur-footer__glow-2"></div>

    <div class="ur-footer__container">
        
        {{-- ─── PRE-FOOTER CTA CARD ────────────────────────── --}}
        <div class="ur-footer__cta">
            <div class="ur-footer__cta-content">
                <h2 class="ur-footer__cta-title">Ready to list your <span class="ur-footer__cta-accent">premium property?</span></h2>
                <p class="ur-footer__cta-desc">Join India's most exclusive network of luxury rental owners and verified tenants today.</p>
            </div>
            <div class="ur-footer__cta-actions">
                <a href="{{ route('properties.create') }}" class="ur-footer__btn ur-footer__btn--primary">
                    Get Started Now
                </a>
                <a href="{{ route('login') }}" class="ur-footer__btn ur-footer__btn--secondary">
                    Owner Login
                </a>
            </div>
        </div>

        {{-- ─── MAIN FOOTER GRID ─────────────────────────────────── --}}
        <div class="ur-footer__grid">
            
            {{-- Column 1: Brand & Identity --}}
            <div class="ur-footer__col">
                <x-brand-logo
                    href="{{ route('home') }}"
                    class="mb-8"
                    imageClass="h-12 w-auto"
                    textClass="text-2xl font-black tracking-tighter text-white"
                    accentClass="text-blue-500"
                    style="margin-bottom: 2rem;"
                />
                <p class="ur-footer__brand-desc">
                    Defining the standard of excellence in the Indian rental market. Discover handpicked luxury for discerning clients.
                </p>
                <div class="ur-footer__socials" style="margin-bottom: 2rem;">
                    @php
                        $socials = [
                            ['svg' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4l11.733 16h4.267l-11.733 -16z"></path><path d="M4 20l6.768 -6.768m2.46 -2.46l6.772 -6.772"></path></svg>', 'url' => $site_settings['social_twitter'] ?? '#'],
                            ['svg' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>', 'url' => $site_settings['social_facebook'] ?? '#'],
                            ['svg' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>', 'url' => $site_settings['social_instagram'] ?? '#'],
                            ['svg' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>', 'url' => $site_settings['social_linkedin'] ?? '#']
                        ];
                    @endphp
                    @foreach($socials as $social)
                    <a href="{{ $social['url'] }}" target="_blank" class="ur-footer__social-link">
                        {!! $social['svg'] !!}
                    </a>
                    @endforeach
                </div>

                <div class="ur-footer__apps" style="display: flex; flex-direction: column; gap: 10px;">
                    <a href="{{ $site_settings['app_google_play_url'] ?? '#' }}" target="_blank" class="ur-store-badge" style="min-width: 150px; padding: 6px 14px; border-radius: 8px;">
                        <div class="ur-store-badge__icon" style="width: 20px; height: 20px;">
                            <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                                <path d="M32.5 17.5C29.6 20.3 28 24.4 28 29.5v453c0 5.1 1.6 9.2 4.5 12l1.5 1.5L257 273v-6l-223-251-1.5 1.5z" fill="#00a3ff"/>
                                <path d="M353.5 173.5l-96.5 96.5v6l96.5 96.5 1.5-1 113.5-64.5c32.5-18.5 32.5-48.5 0-67L355 174.5l-1.5-1z" fill="#ffc107"/>
                                <path d="M257 276.5l-223 223c4.5 4.5 11.5 5 20 0.5l301-171-98-52.5z" fill="#ff3d00"/>
                                <path d="M257 269.5l98-52.5-301-171-8.5-4.5-15.5-4-20 0.5l223 223z" fill="#4caf50"/>
                            </svg>
                        </div>
                        <div class="ur-store-badge__content">
                            <span class="ur-store-badge__label" style="font-size: 8px;">Get it on</span>
                            <span class="ur-store-badge__title" style="font-size: 14px;">Google Play</span>
                        </div>
                    </a>
                    <a href="{{ $site_settings['app_store_url'] ?? '#' }}" target="_blank" class="ur-store-badge" style="min-width: 150px; padding: 6px 14px; border-radius: 8px;">
                        <div class="ur-store-badge__icon" style="width: 20px; height: 20px;">
                            <svg viewBox="0 0 384 512" xmlns="http://www.w3.org/2000/svg">
                                <path fill="currentColor" d="M318.7 268.7c-.2-36.7 16.4-64.4 50-84.8-18.8-26.9-47.2-41.7-84.7-44.6-35.5-2.8-74.3 20.7-88.5 20.7-15 0-49.4-19.7-76.4-19.7C63.3 141.2 4 184.8 4 273.5q0 39.3 14.4 81.2c12.8 36.7 59 126.7 107.2 125.2 25.2-.6 43-17.9 75.8-17.9 31.8 0 48.3 17.9 76.4 17.9 48.6-.7 90.4-82.5 102.6-119.3-65.2-31.4-57.3-114.3-1.7-114.3-1.4 0-1.4 0 0 0zm-7.9-167.2c31.7-36.7 22.1-85 21.6-86.3-4.2.3-51.4 14.4-83.6 51.8-29.8 35.8-22.6 78.4-20.1 82.6 4.3.4 50.4-11.4 82.1-48.1z"/>
                            </svg>
                        </div>
                        <div class="ur-store-badge__content">
                            <span class="ur-store-badge__label" style="font-size: 8px;">Download on the</span>
                            <span class="ur-store-badge__title" style="font-size: 14px;">App Store</span>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Column 2: Navigation --}}
            <div class="ur-footer__col">
                <h4 class="ur-footer__heading">Directory</h4>
                <ul class="ur-footer__nav">
                    <li class="ur-footer__nav-item">
                        <a href="{{ route('properties.index') }}" class="ur-footer__nav-link">
                            <i class="ph ph-buildings"></i>
                            All Properties
                        </a>
                    </li>
                    <li class="ur-footer__nav-item">
                        <a href="{{ route('properties.index', ['type' => 'house']) }}" class="ur-footer__nav-link">
                            <i class="ph ph-house"></i>
                            Luxury Houses
                        </a>
                    </li>
                    <li class="ur-footer__nav-item">
                        <a href="{{ route('properties.index', ['type' => 'shop']) }}" class="ur-footer__nav-link">
                            <i class="ph ph-storefront"></i>
                            Premium Shops
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Column 3: Services --}}
            <div class="ur-footer__col">
                <h4 class="ur-footer__heading">Experience</h4>
                <ul class="ur-footer__nav">
                    <li class="ur-footer__nav-item">
                        <a href="#" class="ur-footer__nav-link">
                            <i class="ph ph-sparkle"></i>
                            Concierge Service
                        </a>
                    </li>
                    <li class="ur-footer__nav-item">
                        <a href="{{ route('register') }}" class="ur-footer__nav-link">
                            <i class="ph ph-handshake"></i>
                            Partner with Us
                        </a>
                    </li>
                    <li class="ur-footer__nav-item">
                        <a href="#" class="ur-footer__nav-link">
                            <i class="ph ph-crown"></i>
                            Elite Membership
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Column 4: Contact --}}
            <div class="ur-footer__col">
                <h4 class="ur-footer__heading">Contact</h4>
                <div class="ur-footer__contact-group">
                    <p class="ur-footer__contact-label">Direct Inquiry</p>
                    <a href="mailto:{{ $site_settings['site_email'] ?? 'support@unlockrentals.com' }}" class="ur-footer__contact-value" style="display: flex; align-items: center; gap: 0.75rem;">
                        <i class="ph ph-envelope-simple" style="color: #2563eb;"></i>
                        {{ $site_settings['site_email'] ?? 'support@unlockrentals.com' }}
                    </a>
                </div>
                <div class="ur-footer__contact-group">
                    <p class="ur-footer__contact-label">Customer Support</p>
                    <a href="tel:{{ $site_settings['site_phone'] ?? '+91 7974164274' }}" class="ur-footer__contact-value" style="display: flex; align-items: center; gap: 0.75rem;">
                        <i class="ph ph-phone" style="color: #2563eb;"></i>
                        {{ $site_settings['site_phone'] ?? '+91 7974164274' }}
                    </a>
                </div>
            </div>
        </div>

        {{-- ─── BOTTOM BAR ───────────────────────── --}}
        <div class="ur-footer__bottom">
            <div class="ur-footer__legal">
                <a href="#" class="ur-footer__legal-link">Privacy</a>
                <a href="#" class="ur-footer__legal-link">Terms</a>
                <a href="#" class="ur-footer__legal-link">Cookie Policy</a>
                <a href="#" class="ur-footer__legal-link">Security</a>
            </div>
            
            <div class="ur-footer__copyright">
                <p class="ur-footer__copy-text">&copy; {{ date('Y') }} UNLOCKRENTALS GLOBAL</p>
                <div class="ur-footer__excellence">
                    <span class="ur-footer__line"></span>
                    <p class="ur-footer__excellence-text">Excellence in Real Estate</p>
                    <span class="ur-footer__line"></span>
                </div>
            </div>
        </div>

    </div>
</footer>
