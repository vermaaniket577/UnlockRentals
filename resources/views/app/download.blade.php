<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Download UnlockRentals App - Android & iOS</title>
    <meta name="description" content="Download the UnlockRentals app for Android and iOS. Find verified rental properties, PG stays, and shops across India on your mobile device.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&family=Outfit:wght@400;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/style.css">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: #020617;
            color: #ffffff;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ─── Background Effects ─── */
        .app-dl-bg {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
        }

        .app-dl-bg__orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(120px);
        }

        .app-dl-bg__orb--1 {
            width: 500px;
            height: 500px;
            background: rgba(37, 99, 235, 0.12);
            top: -150px;
            left: -100px;
            animation: orb-float 8s ease-in-out infinite;
        }

        .app-dl-bg__orb--2 {
            width: 400px;
            height: 400px;
            background: rgba(99, 102, 241, 0.08);
            bottom: -100px;
            right: -100px;
            animation: orb-float 10s ease-in-out infinite reverse;
        }

        .app-dl-bg__orb--3 {
            width: 300px;
            height: 300px;
            background: rgba(37, 99, 235, 0.06);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        @keyframes orb-float {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(30px, -20px); }
        }

        /* ─── Grid pattern ─── */
        .app-dl-bg::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(37, 99, 235, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(37, 99, 235, 0.03) 1px, transparent 1px);
            background-size: 60px 60px;
        }

        /* ─── Header ─── */
        .app-dl-header {
            position: relative;
            z-index: 10;
            padding: 24px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .app-dl-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .app-dl-logo img {
            height: 40px;
            width: auto;
        }

        .app-dl-logo__text {
            font-family: 'Outfit', sans-serif;
            font-size: 22px;
            font-weight: 900;
            color: #ffffff;
            letter-spacing: -0.5px;
        }

        .app-dl-logo__text span { color: #60a5fa; }

        .app-dl-header__link {
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: color 0.3s;
        }

        .app-dl-header__link:hover { color: #ffffff; }

        /* ─── Hero Section ─── */
        .app-dl-hero {
            position: relative;
            z-index: 10;
            max-width: 1200px;
            margin: 0 auto;
            padding: 60px 40px 80px;
            display: flex;
            align-items: center;
            gap: 80px;
        }

        .app-dl-hero__content {
            flex: 1;
        }

        .app-dl-hero__badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: rgba(37, 99, 235, 0.1);
            border: 1px solid rgba(37, 99, 235, 0.2);
            border-radius: 100px;
            color: #60a5fa;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            margin-bottom: 32px;
        }

        .app-dl-hero__badge i { font-size: 14px; }

        .app-dl-hero__title {
            font-family: 'Outfit', sans-serif;
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 900;
            line-height: 1.1;
            letter-spacing: -0.04em;
            margin-bottom: 24px;
        }

        .app-dl-hero__title span {
            background: linear-gradient(135deg, #60a5fa, #2563eb);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .app-dl-hero__desc {
            font-size: 18px;
            color: rgba(255, 255, 255, 0.6);
            line-height: 1.7;
            max-width: 500px;
            margin-bottom: 48px;
            font-weight: 300;
        }

        /* ─── Store Buttons ─── */
        .app-dl-stores {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 32px;
        }

        .app-dl-store-btn {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px 28px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.06), rgba(255, 255, 255, 0.02));
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            color: #ffffff;
            text-decoration: none;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            backdrop-filter: blur(10px);
        }

        .app-dl-store-btn:hover {
            transform: translateY(-4px);
            border-color: rgba(37, 99, 235, 0.5);
            box-shadow: 0 20px 40px -10px rgba(37, 99, 235, 0.25);
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.04));
        }

        .app-dl-store-btn__icon {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .app-dl-store-btn__icon svg { width: 100%; height: 100%; }

        .app-dl-store-btn__text {
            display: flex;
            flex-direction: column;
        }

        .app-dl-store-btn__label {
            font-size: 11px;
            text-transform: uppercase;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.5);
            letter-spacing: 0.15em;
        }

        .app-dl-store-btn__name {
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        /* Direct APK Download */
        .app-dl-apk-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 24px;
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .app-dl-apk-btn:hover {
            border-color: rgba(255, 255, 255, 0.3);
            color: #ffffff;
            background: rgba(255, 255, 255, 0.05);
        }

        .app-dl-apk-btn i { font-size: 18px; }

        /* ─── Mockup Side ─── */
        .app-dl-hero__mockup {
            flex: 0.8;
            display: flex;
            justify-content: center;
            position: relative;
        }

        .app-dl-mockup-img {
            width: 100%;
            max-width: 400px;
            filter: drop-shadow(0 40px 80px rgba(0, 0, 0, 0.3));
            animation: phone-float 6s ease-in-out infinite;
        }

        @keyframes phone-float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        /* ─── Features Strip ─── */
        .app-dl-features {
            position: relative;
            z-index: 10;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 40px 80px;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
        }

        .app-dl-feature {
            padding: 32px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 20px;
            transition: all 0.3s;
        }

        .app-dl-feature:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(37, 99, 235, 0.3);
            transform: translateY(-4px);
        }

        .app-dl-feature__icon {
            width: 48px;
            height: 48px;
            background: rgba(37, 99, 235, 0.1);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: #60a5fa;
        }

        .app-dl-feature__title {
            font-size: 16px;
            font-weight: 800;
            margin-bottom: 8px;
            letter-spacing: -0.02em;
        }

        .app-dl-feature__desc {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.5);
            line-height: 1.6;
        }

        /* ─── QR Section ─── */
        .app-dl-qr {
            position: relative;
            z-index: 10;
            max-width: 600px;
            margin: 0 auto;
            padding: 0 40px 100px;
            text-align: center;
        }

        .app-dl-qr__title {
            font-family: 'Outfit', sans-serif;
            font-size: 24px;
            font-weight: 800;
            margin-bottom: 12px;
        }

        .app-dl-qr__subtitle {
            color: rgba(255, 255, 255, 0.5);
            font-size: 14px;
            margin-bottom: 32px;
        }

        .app-dl-qr__box {
            display: inline-block;
            padding: 24px;
            background: #ffffff;
            border-radius: 20px;
        }

        .app-dl-qr__box img {
            width: 200px;
            height: 200px;
        }

        /* ─── Footer ─── */
        .app-dl-footer {
            position: relative;
            z-index: 10;
            text-align: center;
            padding: 32px 40px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            color: rgba(255, 255, 255, 0.3);
            font-size: 13px;
        }

        /* ─── Platform Detection Banner ─── */
        .app-dl-platform-banner {
            display: none;
            position: relative;
            z-index: 10;
            max-width: 800px;
            margin: 0 auto 40px;
            padding: 20px 32px;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.15), rgba(99, 102, 241, 0.1));
            border: 1px solid rgba(37, 99, 235, 0.3);
            border-radius: 16px;
            text-align: center;
        }

        .app-dl-platform-banner.show { display: block; }

        .app-dl-platform-banner__text {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .app-dl-platform-banner__btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 28px;
            background: #2563eb;
            color: #ffffff;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s;
        }

        .app-dl-platform-banner__btn:hover {
            background: #3b82f6;
            transform: translateY(-2px);
        }

        /* ─── Responsive ─── */
        @media (max-width: 1024px) {
            .app-dl-features { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 768px) {
            .app-dl-hero {
                flex-direction: column-reverse;
                text-align: center;
                padding: 40px 24px 60px;
                gap: 40px;
            }
            .app-dl-hero__desc { margin-left: auto; margin-right: auto; }
            .app-dl-stores { justify-content: center; }
            .app-dl-features { grid-template-columns: 1fr; padding: 0 24px 60px; }
            .app-dl-header { padding: 16px 24px; }
            .app-dl-hero__mockup { max-width: 280px; }
        }
    </style>
</head>
<body>

    <!-- Background -->
    <div class="app-dl-bg">
        <div class="app-dl-bg__orb app-dl-bg__orb--1"></div>
        <div class="app-dl-bg__orb app-dl-bg__orb--2"></div>
        <div class="app-dl-bg__orb app-dl-bg__orb--3"></div>
    </div>

    <!-- Header -->
    <header class="app-dl-header">
        <a href="{{ route('home') }}" class="app-dl-logo">
            <img src="{{ asset('images/logo.png') }}" alt="UnlockRentals" onerror="this.src='https://ui-avatars.com/api/?name=UR&background=2563EB&color=fff'">
            <span class="app-dl-logo__text">Unlock<span>Rentals</span></span>
        </a>
        <a href="{{ route('home') }}" class="app-dl-header__link">← Back to Website</a>
    </header>

    <!-- Platform Detection Banner -->
    <div class="app-dl-platform-banner" id="platformBanner">
        <p class="app-dl-platform-banner__text" id="platformText"></p>
        <a href="#" class="app-dl-platform-banner__btn" id="platformBtn">
            <i class="ph-bold ph-download-simple"></i>
            <span id="platformBtnText">Download Now</span>
        </a>
    </div>

    <!-- Hero -->
    <section class="app-dl-hero">
        <div class="app-dl-hero__content">
            <div class="app-dl-hero__badge">
                <i class="ph-fill ph-device-mobile"></i>
                Available on All Platforms
            </div>

            <h1 class="app-dl-hero__title">
                Your Home Search,<br>
                <span>In Your Pocket</span>
            </h1>

            <p class="app-dl-hero__desc">
                Browse 10,000+ verified rental properties, connect directly with owners, and find your perfect home — all from the UnlockRentals app.
            </p>

            <div class="app-dl-stores">
                <!-- Google Play -->
                <a href="{{ $site_settings['app_google_play_url'] ?? '#' }}" target="_blank" class="app-dl-store-btn" id="googlePlayBtn">
                    <div class="app-dl-store-btn__icon">
                        <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                            <path d="M32.5 17.5C29.6 20.3 28 24.4 28 29.5v453c0 5.1 1.6 9.2 4.5 12l1.5 1.5L257 273v-6l-223-251-1.5 1.5z" fill="#00a3ff"/>
                            <path d="M353.5 173.5l-96.5 96.5v6l96.5 96.5 1.5-1 113.5-64.5c32.5-18.5 32.5-48.5 0-67L355 174.5l-1.5-1z" fill="#ffc107"/>
                            <path d="M257 276.5l-223 223c4.5 4.5 11.5 5 20 0.5l301-171-98-52.5z" fill="#ff3d00"/>
                            <path d="M257 269.5l98-52.5-301-171-8.5-4.5-15.5-4-20 0.5l223 223z" fill="#4caf50"/>
                        </svg>
                    </div>
                    <div class="app-dl-store-btn__text">
                        <span class="app-dl-store-btn__label">Get it on</span>
                        <span class="app-dl-store-btn__name">Google Play</span>
                    </div>
                </a>

                <!-- App Store -->
                <a href="{{ $site_settings['app_store_url'] ?? '#' }}" target="_blank" class="app-dl-store-btn" id="appStoreBtn">
                    <div class="app-dl-store-btn__icon">
                        <svg viewBox="0 0 384 512" xmlns="http://www.w3.org/2000/svg">
                            <path fill="currentColor" d="M318.7 268.7c-.2-36.7 16.4-64.4 50-84.8-18.8-26.9-47.2-41.7-84.7-44.6-35.5-2.8-74.3 20.7-88.5 20.7-15 0-49.4-19.7-76.4-19.7C63.3 141.2 4 184.8 4 273.5q0 39.3 14.4 81.2c12.8 36.7 59 126.7 107.2 125.2 25.2-.6 43-17.9 75.8-17.9 31.8 0 48.3 17.9 76.4 17.9 48.6-.7 90.4-82.5 102.6-119.3-65.2-31.4-57.3-114.3-1.7-114.3-1.4 0-1.4 0 0 0zm-7.9-167.2c31.7-36.7 22.1-85 21.6-86.3-4.2.3-51.4 14.4-83.6 51.8-29.8 35.8-22.6 78.4-20.1 82.6 4.3.4 50.4-11.4 82.1-48.1z"/>
                        </svg>
                    </div>
                    <div class="app-dl-store-btn__text">
                        <span class="app-dl-store-btn__label">Download on the</span>
                        <span class="app-dl-store-btn__name">App Store</span>
                    </div>
                </a>
            </div>

            <!-- Direct APK Download -->
            @if(($site_settings['app_apk_download_url'] ?? '') !== '' && ($site_settings['app_apk_download_url'] ?? '') !== '#')
            <a href="{{ $site_settings['app_apk_download_url'] }}" class="app-dl-apk-btn" download>
                <i class="ph-bold ph-android-logo"></i>
                Download APK Directly
                <i class="ph ph-download-simple"></i>
            </a>
            @endif
        </div>

        <div class="app-dl-hero__mockup">
            <img src="{{ asset('unlockrental_premium_mockup_1778934329998.png') }}" alt="UnlockRentals App" class="app-dl-mockup-img">
        </div>
    </section>

    <!-- Features -->
    <section class="app-dl-features">
        <div class="app-dl-feature">
            <div class="app-dl-feature__icon">
                <i class="ph-bold ph-shield-check"></i>
            </div>
            <h3 class="app-dl-feature__title">100% Verified</h3>
            <p class="app-dl-feature__desc">Every listing is verified by our team. No spam, no fake properties.</p>
        </div>
        <div class="app-dl-feature">
            <div class="app-dl-feature__icon">
                <i class="ph-bold ph-currency-inr"></i>
            </div>
            <h3 class="app-dl-feature__title">Zero Brokerage</h3>
            <p class="app-dl-feature__desc">Connect directly with property owners. Save thousands on brokerage fees.</p>
        </div>
        <div class="app-dl-feature">
            <div class="app-dl-feature__icon">
                <i class="ph-bold ph-map-pin-line"></i>
            </div>
            <h3 class="app-dl-feature__title">Pan-India Coverage</h3>
            <p class="app-dl-feature__desc">Properties across all major cities — Delhi, Mumbai, Bangalore, and more.</p>
        </div>
        <div class="app-dl-feature">
            <div class="app-dl-feature__icon">
                <i class="ph-bold ph-headset"></i>
            </div>
            <h3 class="app-dl-feature__title">24/7 Support</h3>
            <p class="app-dl-feature__desc">Our concierge team is always available to help you find the perfect home.</p>
        </div>
    </section>

    <!-- Footer -->
    <footer class="app-dl-footer">
        &copy; {{ date('Y') }} UnlockRentals. All rights reserved. 
        <a href="{{ route('home') }}" style="color: #60a5fa; text-decoration: none; margin-left: 8px;">Visit Website</a>
    </footer>

    <!-- Platform Detection Script -->
    <script>
        (function() {
            const ua = navigator.userAgent || navigator.vendor || window.opera;
            const banner = document.getElementById('platformBanner');
            const text = document.getElementById('platformText');
            const btn = document.getElementById('platformBtn');
            const btnText = document.getElementById('platformBtnText');

            if (/android/i.test(ua)) {
                const gpUrl = document.getElementById('googlePlayBtn')?.href;
                const apkUrl = '{{ $site_settings["app_apk_download_url"] ?? "" }}';

                text.textContent = '📱 We detected you\'re on Android!';
                btnText.textContent = 'Download for Android';

                if (gpUrl && gpUrl !== '#' && gpUrl !== '') {
                    btn.href = gpUrl;
                } else if (apkUrl && apkUrl !== '#' && apkUrl !== '') {
                    btn.href = apkUrl;
                    btn.setAttribute('download', '');
                }

                banner.classList.add('show');
            } else if (/iPad|iPhone|iPod/.test(ua) && !window.MSStream) {
                const asUrl = document.getElementById('appStoreBtn')?.href;

                text.textContent = '📱 We detected you\'re on iOS!';
                btnText.textContent = 'Download for iPhone';

                if (asUrl && asUrl !== '#' && asUrl !== '') {
                    btn.href = asUrl;
                }

                banner.classList.add('show');
            }
        })();
    </script>

</body>
</html>
