<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" itemscope itemtype="https://schema.org/WebPage">
<head>
    <!-- Google tag (gtag.js) Non-Blocking -->
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      window.gtag = gtag;
      gtag('js', new Date());
      gtag('config', 'G-RJ2TX883V4');

      (function() {
          var gtagLoaded = false;
          function loadGtag() {
              if (gtagLoaded) return;
              gtagLoaded = true;
              var s = document.createElement('script');
              s.async = true;
              s.src = 'https://www.googletagmanager.com/gtag/js?id=G-RJ2TX883V4';
              document.head.appendChild(s);
          }
          if ('requestIdleCallback' in window) {
              requestIdleCallback(function() { setTimeout(loadGtag, 1500); });
          } else {
              setTimeout(loadGtag, 2000);
          }
          ['scroll', 'mousemove', 'touchstart', 'click', 'keydown'].forEach(function(e) {
              window.addEventListener(e, loadGtag, { once: true, passive: true });
          });
      })();
    </script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="canonical" href="{{ route('home') }}">
    <meta name="robots" content="index, follow">
    <title>Room Near My Location | Search House & Flat For Rent Near Me - UnlockRentals</title>
    <meta name="description" content="Search room near my location with zero brokerage. Find 100% verified single rooms, 1RK, 1BHK flats, PGs & houses for rent near you directly from owners across India.">
    <meta name="keywords" content="room near my location, room for rent near me, rooms near me, single room for rent near me, rent room near me, room near my current location, pg near my location, pg near me, flat for rent near me, flats near me, house for rent near me, search house near me, search house near me for rent, search house near me by owner, unlockrentals">
    <meta name="author" content="UnlockRentals">
    <meta name="publisher" content="UnlockRentals">

    {{-- Legacy & Universal Image Source --}}
    <link rel="image_src" href="{{ asset('images/logo.png') }}">

    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="Room Near My Location | Search House & Flat For Rent Near Me - UnlockRentals">
    <meta property="og:description" content="Search room near my location with zero brokerage. Find 100% verified single rooms, 1RK, 1BHK flats, PGs & houses for rent near you directly from owners across India.">
    <meta property="og:image" content="{{ asset('images/logo.png') }}">

    {{-- Twitter --}}
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="Room Near My Location | Search House & Flat For Rent Near Me - UnlockRentals">
    <meta property="twitter:description" content="Search room near my location with zero brokerage. Find 100% verified single rooms, 1RK, 1BHK flats, PGs & houses for rent near you directly from owners across India.">
    <meta property="twitter:image" content="{{ asset('images/logo.png') }}">

    {{-- High-Performance Deferred Google AdSense (Zero Blocking on Initial Page Load) --}}
    <script>
        (function() {
            var adsLoaded = false;
            function loadAdSense() {
                if (adsLoaded) return;
                adsLoaded = true;
                var script = document.createElement('script');
                script.async = true;
                script.crossOrigin = 'anonymous';
                script.src = 'https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2772066538696984';
                document.head.appendChild(script);
            }
            if ('requestIdleCallback' in window) {
                requestIdleCallback(function() { setTimeout(loadAdSense, 2000); });
            } else {
                setTimeout(loadAdSense, 2500);
            }
            ['scroll', 'mousemove', 'touchstart', 'click', 'keydown'].forEach(function(evt) {
                window.addEventListener(evt, loadAdSense, { once: true, passive: true });
            });
        })();
    </script>

    {{-- Favicon & Google Search SERP Icons (Google Guidelines Compliant) --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=20260831">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=20260831">
    <link rel="icon" type="image/png" sizes="48x48" href="{{ asset('favicon-48x48.png') }}?v=20260831">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon-96x96.png') }}?v=20260831">
    <link rel="icon" type="image/png" sizes="144x144" href="{{ asset('favicon-144x144.png') }}?v=20260831">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicon-192x192.png') }}?v=20260831">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('favicon-512x512.png') }}?v=20260831">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}?v=20260831">
    <link rel="apple-touch-icon-precomposed" sizes="180x180" href="{{ asset('apple-touch-icon-precomposed.png') }}?v=20260831">

    {{-- Comprehensive JSON-LD Schema for Google Search & Organization Knowledge Graph --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@graph": [
            {
                "@@type": "WebSite",
                "@@id": "{{ route('home') }}#website",
                "name": "UnlockRentals",
                "url": "{{ route('home') }}",
                "description": "Find verified houses, flats, PGs, and rental properties near you with zero brokerage.",
                "potentialAction": {
                    "@@type": "SearchAction",
                    "target": {
                        "@@type": "EntryPoint",
                        "urlTemplate": "{{ route('properties.index') }}?search={search_term_string}"
                    },
                    "query-input": "required name=search_term_string"
                }
            },
            {
                "@@type": "Organization",
                "@@id": "{{ route('home') }}#organization",
                "name": "UnlockRentals",
                "url": "{{ route('home') }}",
                "logo": "{{ asset('images/logo.png') }}",
                "image": "{{ asset('images/logo.png') }}",
                "priceRange": "₹5,000 - ₹5,00,000",
                "address": {
                    "@@type": "PostalAddress",
                    "addressCountry": "IN"
                }
            },
            {
                "@@type": "RealEstateAgent",
                "@@id": "{{ route('home') }}#realestateagent",
                "name": "UnlockRentals",
                "url": "{{ route('home') }}",
                "logo": "{{ asset('images/logo.png') }}",
                "image": "{{ asset('images/logo.png') }}",
                "priceRange": "₹5,000 - ₹5,00,000",
                "areaServed": [
                    { "@@type": "City", "name": "Delhi" },
                    { "@@type": "City", "name": "Gurugram" },
                    { "@@type": "City", "name": "Gurgaon" },
                    { "@@type": "City", "name": "Noida" },
                    { "@@type": "City", "name": "Bengaluru" },
                    { "@@type": "City", "name": "Mumbai" }
                ],
                "address": {
                    "@@type": "PostalAddress",
                    "addressCountry": "IN"
                }
            },
            {
                "@@type": "FAQPage",
                "mainEntity": [
                    {
                        "@@type": "Question",
                        "name": "How do I search room near my location for rent with zero brokerage?",
                        "acceptedAnswer": {
                            "@@type": "Answer",
                            "text": "On UnlockRentals, you can search single rooms, 1RK, 1BHK flats, PGs, and houses near your current location directly by owner. Filter by your location, price, and room type to connect with verified property owners without paying brokerage fees."
                        }
                    },
                    {
                        "@@type": "Question",
                        "name": "What is the average rent for a single room near my location?",
                        "acceptedAnswer": {
                            "@@type": "Answer",
                            "text": "Single rooms and 1RK units near your location typically start around ₹3,000 to ₹8,000 per month for budget rooms and ₹10,000 to ₹20,000 for fully furnished studio rooms in gated societies."
                        }
                    },
                    {
                        "@@type": "Question",
                        "name": "Can I search houses and rooms for rent in Gurgaon, Delhi NCR under 5000 / 10000?",
                        "acceptedAnswer": {
                            "@@type": "Answer",
                            "text": "Yes, UnlockRentals provides price filter options allowing you to find affordable rental homes, single rooms, 1RK, 1BHK, and PG accommodations in Gurgaon, Delhi NCR, Mumbai, and Bangalore under ₹5,000 and ₹10,000."
                        }
                    },
                    {
                        "@@type": "Question",
                        "name": "How to find rooms and flats near me directly by owner?",
                        "acceptedAnswer": {
                            "@@type": "Answer",
                            "text": "Use the UnlockRentals direct owner filter to view 100% owner-posted property listings with direct WhatsApp, phone contact, and instant visit booking."
                        }
                    }
                ]
            }
        ]
    }
    </script>

    <!-- Optimized Non-Blocking Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&family=Outfit:wght@400;700;800&family=Playfair+Display:wght@700;900&display=swap">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&family=Outfit:wght@400;700;800&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&family=Outfit:wght@400;700;800&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">
    </noscript>

    <!-- Precompiled High-Performance Tailwind CSS (Zero Runtime JS Overhead) -->
    @if(file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css'])
    @else
        <link rel="stylesheet" href="{{ asset('css/tailwind-build.css') }}?v={{ file_exists(public_path('css/tailwind-build.css')) ? filemtime(public_path('css/tailwind-build.css')) : time() }}">
    @endif

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/unlock-rental.css') }}?v={{ file_exists(public_path('css/unlock-rental.css')) ? filemtime(public_path('css/unlock-rental.css')) : time() }}&cb=20260920v1">

    <!-- Non-Blocking Phosphor Icons (Regular, Bold, Fill) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/regular/style.css" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/bold/style.css" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/fill/style.css" media="print" onload="this.media='all'">
    <noscript>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/regular/style.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/bold/style.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/fill/style.css">
    </noscript>

    {{-- Preload Largest Contentful Paint (LCP) Image --}}
    <link rel="preload" as="image" href="{{ asset('images/hero-bg.webp') }}" type="image/webp" fetchpriority="high">

    <!-- PWA Configuration -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="UnlockRentals">
    <link class="pwa-apple-touch-icon" rel="apple-touch-icon" href="{{ asset('images/icons/icon-192x192.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#2563EB">

    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "WebSite",
        "name": "UnlockRentals",
        "url": "{{ route('home') }}",
        "potentialAction": {
            "@@type": "SearchAction",
            "target": "{{ route('properties.index') }}?search={search_term_string}",
            "query-input": "required name=search_term_string"
        }
    }
    </script>
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Organization",
        "name": "UnlockRentals",
        "url": "{{ route('home') }}",
        "logo": "{{ asset('images/logo.png') }}",
        "sameAs": []
    }
    </script>


    <!-- Custom Styles for Homepage Banner Slider -->
    <style>
        .promo-slider-container {
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px 40px 10px;
            overflow: hidden;
        }
        
        .promo-slider {
            position: relative;
            width: 100%;
            background: #1a3fbd; /* fallback blue matching image */
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            height: 330px;
        }

        .promo-slides-wrapper {
            display: flex;
            width: 100%;
            height: 100%;
            transition: transform 0.6s cubic-bezier(0.25, 1, 0.5, 1);
        }

        .promo-slide {
            flex: 0 0 100%;
            width: 100%;
            height: 100%;
            display: flex;
            position: relative;
            overflow: hidden;
        }

        .promo-slide-content {
            width: 55%;
            height: 100%;
            padding: 40px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            z-index: 5;
            color: #ffffff;
        }

        .promo-badge {
            background: #ffd814;
            color: #0f1111;
            font-size: 12px;
            font-weight: 800;
            padding: 6px 14px;
            border-radius: 100px;
            letter-spacing: 0.5px;
            margin-bottom: 16px;
            box-shadow: 0 4px 10px rgba(255, 216, 20, 0.2);
            display: inline-block;
        }

        .promo-title {
            font-family: 'Outfit', sans-serif;
            font-size: clamp(1.8rem, 3.2vw, 2.8rem);
            font-weight: 900;
            line-height: 1.15;
            margin-bottom: 12px;
            letter-spacing: -0.5px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .promo-desc {
            font-family: 'Inter', sans-serif;
            font-size: clamp(0.85rem, 1.2vw, 1.05rem);
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.6;
            margin-bottom: 24px;
            max-width: 520px;
        }

        .promo-btn {
            background: #ffd814;
            color: #0f1111;
            font-weight: 800;
            font-size: 14px;
            padding: 12px 28px;
            border-radius: 10px;
            text-transform: none;
            letter-spacing: 0.2px;
            transition: all 0.25s ease;
            box-shadow: 0 6px 20px rgba(255, 216, 20, 0.25);
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .promo-btn:hover {
            background: #ffd814;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 216, 20, 0.4);
        }

        .promo-image-side {
            width: 45%;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .promo-image-side img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Gradient mask to blend the image into the blue background */
        .promo-gradient-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right, #1a3fbd 0%, rgba(26, 63, 189, 0.9) 15%, rgba(26, 63, 189, 0.4) 50%, transparent 100%);
            z-index: 2;
        }

        /* Specific backgrounds for each slide to match their gradients */
        .slide-zero-brokerage {
            background: #1a3fbd;
        }
        .slide-zero-brokerage .promo-gradient-overlay {
            background: linear-gradient(to right, #1a3fbd 0%, rgba(26, 63, 189, 0.9) 15%, rgba(26, 63, 189, 0.4) 50%, transparent 100%);
        }

        .slide-pg-stays {
            background: #0f172a;
        }
        .slide-pg-stays .promo-gradient-overlay {
            background: linear-gradient(to right, #0f172a 0%, rgba(15, 23, 42, 0.9) 15%, rgba(15, 23, 42, 0.4) 50%, transparent 100%);
        }

        .slide-commercial {
            background: #1e1b4b;
        }
        .slide-commercial .promo-gradient-overlay {
            background: linear-gradient(to right, #1e1b4b 0%, rgba(30, 27, 75, 0.9) 15%, rgba(30, 27, 75, 0.4) 50%, transparent 100%);
        }

        /* Slider Controls - Standard Circular Buttons (Fix Issue 18) */
        .promo-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            transition: all 0.25s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.25);
        }

        .promo-arrow:hover {
            background: #2563eb;
            border-color: #2563eb;
            transform: translateY(-50%) scale(1.08);
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.4);
        }

        .promo-arrow-prev {
            left: 16px;
        }

        .promo-arrow-next {
            right: 16px;
        }

        .promo-arrow svg {
            width: 20px;
            height: 20px;
            transition: transform 0.2s ease;
        }
        
        .promo-arrow:hover svg {
            transform: scale(1.1);
        }

        .promo-dots {
            position: absolute;
            bottom: 16px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 8px;
            z-index: 10;
        }

        .promo-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.4);
            border: none;
            cursor: pointer;
            padding: 0;
            transition: all 0.3s ease;
        }

        .promo-dot.active {
            background: #ffffff;
            transform: scale(1.2);
        }

        /* Responsive styling for promo slider */
        @media (max-width: 1024px) {
            .promo-slider-container {
                padding: 20px 24px 10px;
            }
            .promo-slide-content {
                width: 65%;
                padding: 30px 40px;
            }
            .promo-image-side {
                width: 35%;
            }
        }

        @media (max-width: 768px) {
            .promo-slider {
                height: 280px;
            }
            .promo-slide-content {
                width: 100%;
                padding: 24px 36px;
                text-align: center;
                align-items: center;
            }
            .promo-image-side {
                display: none;
            }
            .promo-arrow {
                width: 36px;
            }
        }

        @media (max-width: 480px) {
            .promo-slider-container {
                padding: 14px 12px 6px !important;
            }
            .promo-slider {
                height: 240px !important;
                border-radius: 16px !important;
            }
            .promo-slide-content {
                padding: 16px 18px !important;
            }
            .promo-badge {
                font-size: 10px !important;
                padding: 4px 10px !important;
                margin-bottom: 8px !important;
            }
            .promo-title {
                font-size: 1.35rem !important;
                margin-bottom: 6px !important;
            }
            .promo-desc {
                font-size: 0.8rem !important;
                line-height: 1.4 !important;
                margin-bottom: 14px !important;
            }
            .promo-btn {
                font-size: 12px !important;
                padding: 8px 18px !important;
                border-radius: 8px !important;
            }
            .promo-arrow {
                width: 30px !important;
                height: 30px !important;
            }
            .promo-arrow svg {
                width: 14px !important;
                height: 14px !important;
            }
        }
        
        @media (max-width: 1023px) {
            .main-header {
                background: #ffffff !important;
                border-bottom: 1px solid #e2e8f0 !important;
                box-shadow: 0 1px 4px rgba(15, 23, 42, 0.05) !important;
            }
            .main-header .main-nav,
            .main-nav {
                display: none !important;
            }
            .main-header .hamburger {
                display: inline-flex !important;
                background: #f1f5f9 !important;
                border: 1px solid #e2e8f0 !important;
                color: #1e293b !important;
            }
            .main-header .hamburger svg {
                stroke: #1e293b !important;
            }
            .logo-text {
                color: #0f172a !important;
            }
            .logo-text .logo-accent {
                color: #2563eb !important;
            }
            .btn-cta-premium-header {
                display: none !important;
            }
        }
        @media (min-width: 1024px) {
            .main-header .main-nav {
                display: flex !important;
            }
            .main-header .hamburger {
                display: none !important;
            }
            #welcome-mobile-top-login {
                display: none !important;
            }
            .logo-text {
                color: #ffffff !important;
            }
            .logo-text .logo-accent {
                color: #60a5fa !important;
            }
        }
        @media (max-width: 768px) {
            .main-header {
                padding: calc(8px + env(safe-area-inset-top, 0px)) 12px 8px 12px !important;
                gap: 8px !important;
                min-height: 52px !important;
                height: auto !important;
                display: flex !important;
                align-items: center !important;
                justify-content: space-between !important;
                box-sizing: border-box !important;
                width: 100% !important;
                left: 0 !important;
                right: 0 !important;
            }
            .main-header .main-nav,
            .main-nav {
                display: none !important;
            }
            .logo-wrapper {
                flex-shrink: 0 !important;
            }
            .logo-text {
                font-size: 16px !important;
            }
            .auth-nav {
                margin-left: auto !important;
                flex-shrink: 0 !important;
                display: flex !important;
                align-items: center !important;
                gap: 6px !important;
            }
            #welcome-mobile-top-login {
                display: inline-flex !important;
                padding: 6px 12px !important;
                font-size: 11.5px !important;
                color: #ffffff !important;
            }
            #welcome-mobile-top-login *,
            #welcome-mobile-top-login i,
            #welcome-mobile-top-login span {
                color: #ffffff !important;
                fill: #ffffff !important;
            }
            .btn-cta-premium-header {
                display: none !important;
            }
            .hero-container {
                padding-top: calc(56px + env(safe-area-inset-top, 0px)) !important;
                padding-bottom: 16px !important;
                padding-left: 14px !important;
                padding-right: 14px !important;
            }
            .hero-badge {
                margin-top: 4px !important;
                margin-bottom: 8px !important;
                font-size: 10px !important;
                padding: 3.5px 11px !important;
                letter-spacing: 0.8px !important;
            }
            .hero-title {
                font-size: clamp(20px, 5.5vw, 25px) !important;
                line-height: 1.22 !important;
                letter-spacing: -0.015em !important;
                margin-bottom: 6px !important;
            }
            .hero-subtitle {
                font-size: 11.5px !important;
                line-height: 1.4 !important;
                margin-bottom: 12px !important;
                max-width: 95% !important;
                margin-left: auto !important;
                margin-right: auto !important;
            }
        }

        @media (max-width: 480px) {
            .hero-container {
                padding-top: calc(56px + env(safe-area-inset-top, 0px)) !important;
                padding-bottom: 12px !important;
                padding-left: 10px !important;
                padding-right: 10px !important;
            }
            .hero-badge {
                margin-top: 2px !important;
                margin-bottom: 6px !important;
                font-size: 9.5px !important;
                padding: 3px 9px !important;
                letter-spacing: 0.6px !important;
            }
        }

        /* ===================================================
           FLIPKART STYLE MOBILE APP HERO SECTION (Light Theme)
           =================================================== */
        .mobile-app-hero-slider-section {
            position: relative;
            z-index: 20;
            width: 100%;
            margin: 0 auto;
            padding-top: calc(56px + env(safe-area-inset-top, 0px));
            padding-bottom: 20px;
            background: #f8fafc;
            background: linear-gradient(180deg, #eff6ff 0%, #f8fafc 40%, #f1f5f9 100%);
            box-sizing: border-box;
            overflow: hidden;
            border-bottom: 1px solid #e2e8f0;
        }

        /* Top Location Bar */
        .flipkart-location-chip {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 9999px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            color: #1e293b;
            font-size: 11px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            transition: all 0.2s ease;
        }
        .flipkart-location-chip:active {
            background: #eff6ff;
            border-color: #3b82f6;
        }

        /* Search Bar */
        .mobile-app-search-bar {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 6px 8px;
            box-shadow: 0 4px 16px -2px rgba(15, 23, 42, 0.08), 0 1px 3px rgba(15, 23, 42, 0.04);
        }

        /* Horizontal Category Circles Rail */
        .flipkart-cat-rail {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            overflow-x: auto;
            padding: 4px 2px 8px 2px;
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
        }
        .flipkart-cat-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex-shrink: 0;
            width: 58px;
            text-decoration: none;
            transition: transform 0.15s ease;
            -webkit-tap-highlight-color: transparent;
        }
        .flipkart-cat-item:active {
            transform: scale(0.92);
        }
        .flipkart-cat-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12), 0 1px 3px rgba(0, 0, 0, 0.08);
            border: 2px solid #ffffff;
            margin-bottom: 5px;
            position: relative;
        }
        .flipkart-cat-label {
            font-size: 11px;
            font-weight: 800;
            color: #0f172a;
            text-align: center;
            line-height: 1.15;
            white-space: nowrap;
        }

        /* Hero Banner Slider */
        .mobile-hero-slider-container {
            position: relative;
            width: 100%;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.15);
            margin-bottom: 4px;
        }

        .mobile-hero-slider-track {
            display: flex;
            width: 100%;
            transition: transform 0.42s cubic-bezier(0.25, 1, 0.5, 1);
            touch-action: pan-y;
            will-change: transform;
        }

        .mobile-hero-banner-slide {
            flex: 0 0 100%;
            width: 100%;
            padding: 18px 16px 20px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 185px;
            position: relative;
            overflow: hidden;
            user-select: none;
        }

        .mobile-hero-banner-slide::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background: radial-gradient(circle at 90% 20%, rgba(255, 255, 255, 0.18) 0%, transparent 60%);
            pointer-events: none;
        }

        .mobile-slider-indicators {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-top: 8px;
            margin-bottom: 12px;
        }

        .mobile-slider-dot {
            width: 6px;
            height: 6px;
            border-radius: 9999px;
            background: #cbd5e1;
            transition: all 0.3s ease;
            border: none;
            padding: 0;
            cursor: pointer;
        }

        .mobile-slider-dot.active {
            width: 22px;
            background: #2563eb;
            border-radius: 9999px;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.4);
        }

        /* Quick Filter Chips */
        .flipkart-quick-filters {
            display: flex;
            align-items: center;
            gap: 6px;
            overflow-x: auto;
            padding: 2px 2px 8px 2px;
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
        }
        .flipkart-filter-chip {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 6px 12px;
            border-radius: 9999px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            color: #0f172a;
            font-size: 11px;
            font-weight: 700;
            white-space: nowrap;
            flex-shrink: 0;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }
        .flipkart-filter-chip:active, .flipkart-filter-chip.active {
            background: #2563eb;
            border-color: #2563eb;
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.3);
        }

        .mobile-filter-btn {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 7px 11px;
            border-radius: 12px;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            color: #334155;
            font-size: 11px;
            font-weight: 700;
            white-space: nowrap;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
            user-select: none;
            -webkit-tap-highlight-color: transparent;
        }
        .mobile-filter-btn:active, .mobile-filter-btn.active {
            background: #2563eb;
            border-color: #2563eb;
            color: #ffffff;
            box-shadow: 0 3px 10px rgba(37, 99, 235, 0.35);
        }

        .mobile-filter-dropdown-panel {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 16px 14px;
            box-shadow: 0 20px 45px -10px rgba(15, 23, 42, 0.15), 0 1px 3px rgba(15, 23, 42, 0.06);
            margin-top: 8px;
            animation: mobileDropdownSlide 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes mobileDropdownSlide {
            from {
                opacity: 0;
                transform: translateY(-8px) scale(0.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        .mobile-purpose-tab {
            cursor: pointer;
            user-select: none;
            -webkit-tap-highlight-color: transparent;
        }
        .mobile-category-card {
            background: rgba(15, 23, 42, 0.75);
            border: 1px solid rgba(255, 255, 255, 0.09);
            border-radius: 14px;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        .mobile-category-card:active {
            transform: scale(0.95);
            border-color: rgba(59, 130, 246, 0.5);
        }
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* ===================================================
           STRICT VISIBILITY: MOBILE APP SLIDER VS DESKTOP HERO
           =================================================== */
        @media (max-width: 1023px) {
            /* COMPLETELY HIDE DESKTOP WEBSITE HERO ON MOBILE SCREEN */
            .hero-section,
            section.hero-section,
            .hero-bg,
            .hero-container,
            .promo-slider-container {
                display: none !important;
                visibility: hidden !important;
                height: 0 !important;
                min-height: 0 !important;
                max-height: 0 !important;
                margin: 0 !important;
                padding: 0 !important;
                overflow: hidden !important;
                pointer-events: none !important;
            }

            /* DISPLAY ONLY MOBILE APP HERO SLIDER ON MOBILE */
            .mobile-app-hero-slider-section {
                display: block !important;
                visibility: visible !important;
            }
        }

        @media (min-width: 1024px) {
            /* HIDE MOBILE APP HERO SLIDER ON DESKTOP */
            .mobile-app-hero-slider-section {
                display: none !important;
                visibility: hidden !important;
                height: 0 !important;
                overflow: hidden !important;
            }

            /* DISPLAY DESKTOP WEBSITE HERO ON DESKTOP */
            .hero-section {
                display: flex !important;
            }
            .promo-slider-container {
                display: block !important;
            }
        }
    </style>
</head>
<body>

    {{-- Premium Page Loader --}}
    @include('components.page-loader')

    <header class="main-header" style="z-index: 9999; box-sizing: border-box; display: flex; align-items: center; justify-content: space-between; width: 100%;">
        <div class="logo-wrapper" style="flex-shrink: 0;">
            <a href="{{ route('home') }}" class="logo" style="display: flex !important; align-items: center !important; gap: 8px !important; flex-direction: row !important; white-space: nowrap !important;" title="UnlockRentals">
                <div style="width: 34px; height: 34px; border-radius: 9px; background: #ffffff; display: flex; align-items: center; justify-content: center; padding: 3px; box-shadow: 0 4px 14px rgba(0,0,0,0.3), 0 0 0 1px rgba(255,255,255,0.25); flex-shrink: 0;">
                    <img src="{{ asset('images/logo-icon.png') }}" alt="Unlock Rentals" title="Unlock Rentals" class="logo-img" width="34" height="34" style="width: 100% !important; height: 100% !important; flex-shrink: 0 !important; object-fit: contain !important;" fetchpriority="high" decoding="async" loading="eager" onerror="this.src='https://ui-avatars.com/api/?name=UR&background=2563EB&color=fff'">
                </div>
                <span class="logo-text" style="font-size: 17px !important; font-weight: 800 !important; letter-spacing: -0.4px !important; white-space: nowrap !important;">Unlock<span class="logo-accent">Rentals</span></span>
            </a>
        </div>
        <nav class="main-nav hidden lg:flex">
            <a href="{{ route('properties.index') }}" class="nav-link" title="Discover">
                <i class="ph-bold ph-compass"></i>
                Discover
            </a>
            <a href="{{ route('properties.index', ['purpose' => 'buy']) }}" class="nav-link" title="Buy">
                <i class="ph-bold ph-shopping-bag"></i>
                Buy
            </a>
            <a href="{{ route('properties.index', ['purpose' => 'rent']) }}" class="nav-link" title="Rent">
                <i class="ph-bold ph-key"></i>
                Rent
            </a>
            <a href="{{ route('properties.index', ['type' => 'commercial']) }}" class="nav-link" title="Commercial">
                <i class="ph-bold ph-buildings"></i>
                Commercial
            </a>
            <a href="{{ route('properties.index', ['type' => 'plot']) }}" class="nav-link" title="Plots / Land">
                <i class="ph-bold ph-map-trifold"></i>
                Plots / Land
            </a>
            <a href="{{ url('/how-it-works') }}" class="nav-link" title="Process">
                <i class="ph-bold ph-git-merge"></i>
                Process
            </a>
            <a href="{{ url('/blog') }}" class="nav-link" title="Blog">
                <i class="ph-bold ph-newspaper"></i>
                Blog
            </a>
        </nav>
        <div class="auth-nav" style="display: flex; align-items: center; gap: 8px; flex-shrink: 0; margin-left: auto;">
            @if (Route::has('login'))
                @auth
                    {{-- Desktop Only: Post Ad CTA (hidden on mobile to prevent overlap) --}}
                    <a href="{{ route('properties.create') }}" class="btn-primary-sm btn-cta-premium btn-cta-premium-header hidden lg:inline-flex" style="white-space:nowrap; padding: 0 18px; height: 42px; align-items: center; gap: 6px; color: #ffffff !important;" title="Post Free Advertise">
                        <i class="ph-bold ph-plus-circle" style="font-size: 17px; color: #ffffff !important;"></i>
                        <span style="color: #ffffff !important;">Post Free Advertise</span>
                    </a>

                    {{-- Compact User Account Pill --}}
                    <div class="relative" style="position:relative; display:inline-block;">
                        <script>
                            window.toggleUserDropdown = function(e) {
                                if (e) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                                if (window.innerWidth < 768) {
                                    window.openWelcomeAccountModal();
                                    return;
                                }
                                var dd = document.getElementById('userDropdown');
                                var bd = document.getElementById('userDropdownBackdrop');
                                if (!dd) return;
                                if (dd.classList.contains('hidden')) {
                                    dd.classList.remove('hidden');
                                    if (bd) bd.classList.remove('hidden');
                                } else {
                                    dd.classList.add('hidden');
                                    if (bd) bd.classList.add('hidden');
                                }
                            };
                            window.closeUserDropdown = function() {
                                var dd = document.getElementById('userDropdown');
                                var bd = document.getElementById('userDropdownBackdrop');
                                if (dd) dd.classList.add('hidden');
                                if (bd) bd.classList.add('hidden');
                            };
                            window.openWelcomeAccountModal = function() {
                                var modal = document.getElementById('welcome-account-modal');
                                if (modal) {
                                    modal.classList.remove('hidden');
                                    document.body.style.overflow = 'hidden';
                                }
                            };
                            window.closeWelcomeAccountModal = function() {
                                var modal = document.getElementById('welcome-account-modal');
                                if (modal) {
                                    modal.classList.add('hidden');
                                    document.body.style.overflow = '';
                                }
                            };
                            window.performUniversalLogout = function(e) {
                                if (e) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                                var btn = e ? (e.currentTarget || e.target) : null;
                                if (btn) {
                                    btn.disabled = true;
                                    btn.style.opacity = '0.5';
                                }
                                var tokenMeta = document.querySelector('meta[name="csrf-token"]');
                                var token = tokenMeta ? tokenMeta.getAttribute('content') : '{{ csrf_token() }}';

                                fetch('/logout', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': token,
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({})
                                })
                                .then(function(res) {
                                    return res.json().catch(function() { return { redirect: '/' }; });
                                })
                                .then(function(data) {
                                    var target = (data && data.redirect) ? data.redirect : '/';
                                    if (window.location.pathname.indexOf('dashboard') !== -1 || window.location.pathname.indexOf('admin') !== -1) {
                                        window.location.replace('/');
                                    } else {
                                        window.location.reload();
                                    }
                                })
                                .catch(function() {
                                    if (window.location.pathname.indexOf('dashboard') !== -1 || window.location.pathname.indexOf('admin') !== -1) {
                                        window.location.replace('/');
                                    } else {
                                        window.location.reload();
                                    }
                                });
                            };
                        </script>

                        {{-- Desktop Dropdown Backdrop --}}
                        <div id="userDropdownBackdrop" class="fixed inset-0 z-[9998] hidden bg-black/20" onclick="window.closeUserDropdown()"></div>

                        <button type="button" onclick="window.toggleUserDropdown(event)" class="flex items-center gap-1.5 py-1 px-2 rounded-full hover:bg-white/10 transition-all border border-white/15 bg-white/5 relative z-[9999] cursor-pointer" id="userDropdownBtn" aria-label="User Account">
                            <div style="width:30px; height:30px; border-radius:50%; background:linear-gradient(135deg, #2563eb, #6366f1); color:#fff; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:12px; box-shadow:0 2px 8px rgba(37,99,235,0.4); flex-shrink:0; pointer-events:none;">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="opacity:0.7; flex-shrink:0; pointer-events:none;"><polyline points="6 9 12 15 18 9"></polyline></svg>
                        </button>
                        
                        <div id="userDropdown" class="hidden" style="position:absolute; top:calc(100% + 10px); right:0; width:240px; background:rgba(15,15,18,0.96); backdrop-filter:blur(20px); border:1px solid rgba(255,255,255,0.1); border-radius:14px; box-shadow:0 20px 40px rgba(0,0,0,0.5); overflow:hidden; z-index:9999;">
                            <div style="padding:14px; border-bottom:1px solid rgba(255,255,255,0.08);">
                                <p style="color:rgba(255,255,255,0.5); font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:1px; margin-bottom:2px;">Signed in as</p>
                                <p style="color:#fff; font-weight:700; font-size:14px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ auth()->user()->name }}</p>
                                <p style="color:rgba(255,255,255,0.6); font-size:12px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ auth()->user()->email }}</p>
                            </div>
                            <div style="padding:6px 0;">
                                <a href="{{ route('dashboard') }}" class="dropdown-item" style="display:flex; align-items:center; gap:10px; padding:10px 15px; color:rgba(255,255,255,0.8); font-size:13px; text-decoration:none; transition:all 0.2s;" title="Dashboard">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                                    Dashboard
                                </a>
                                <a href="{{ route('plans.index') }}" class="dropdown-item" style="display:flex; align-items:center; gap:10px; padding:10px 15px; color:rgba(255,255,255,0.8); font-size:13px; text-decoration:none; transition:all 0.2s;" title="Membership Plans">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
                                    Membership Plans
                                </a>
                            </div>
                            <div style="padding:6px 0; border-top:1px solid rgba(255,255,255,0.08);">
                                <form method="POST" action="{{ route('logout') }}" onsubmit="window.performUniversalLogout(event)">
                                    @csrf
                                    <button type="button" onclick="window.performUniversalLogout(event)" class="dropdown-item" style="width:100%; display:flex; align-items:center; gap:10px; padding:10px 15px; color:#f87171; font-size:13px; text-decoration:none; transition:all 0.2s; background:transparent; border:none; cursor:pointer; text-align:left;">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <style>
                        .dropdown-item:hover { background: rgba(255,255,255,0.08); color: #fff !important; }
                    </style>
                @else
                    {{-- Mobile Top Login Button (Visible on Mobile & Tablet Screens < 1024px) --}}
                    <a href="{{ route('login') }}" onclick="event.preventDefault(); window.openAuthModal('login');" class="inline-flex lg:hidden items-center gap-1.5 px-3 py-1.5 rounded-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold shadow-md shadow-blue-500/25 active:scale-95 transition-all whitespace-nowrap cursor-pointer" style="text-decoration:none; color: #ffffff !important;" id="welcome-mobile-top-login" title="Login">
                        <i class="ph-bold ph-sign-in" style="font-size: 14px; color: #ffffff !important;"></i>
                        <span style="color: #ffffff !important;">Login</span>
                    </a>

                    {{-- Desktop Sign In & Post Free Advertise --}}
                    <a href="{{ route('login') }}" onclick="event.preventDefault(); window.openAuthModal('login');" class="nav-link hidden lg:inline-flex" style="margin-right: 8px; cursor: pointer;" title="Log in">
                        <i class="ph ph-user-circle"></i>
                        Log in
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('properties.create') }}" class="btn-primary-sm btn-cta-premium btn-cta-premium-header hidden lg:inline-flex" style="white-space:nowrap; padding: 0 18px; height: 42px; align-items: center; gap: 6px; color: #ffffff !important;" title="Post Free Advertise">
                            <i class="ph-bold ph-plus-circle" style="font-size: 17px; color: #ffffff !important;"></i>
                            <span style="color: #ffffff !important;">Post Free Advertise</span>
                        </a>
                    @endif
                @endauth
            @endif
            <button class="hamburger inline-flex lg:hidden" onclick="toggleMobileNav()" aria-label="Open menu" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 10px; cursor: pointer; flex-shrink: 0;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>
        </div>
    </header>

    <!-- Mobile Navigation Drawer -->
    <style>
        #mobileNav,
        .mobile-nav {
            background: #ffffff !important;
            background-color: #ffffff !important;
            color: #0f172a !important;
            border-left: 1px solid #e2e8f0 !important;
            box-shadow: -12px 0 40px rgba(15, 23, 42, 0.15) !important;
        }
        #mobileNav .mobile-nav-link,
        .mobile-nav .mobile-nav-link {
            color: #0f172a !important;
            background: transparent !important;
        }
        #mobileNav .mobile-nav-link:hover,
        .mobile-nav .mobile-nav-link:hover {
            color: #2563EB !important;
            background: #eff6ff !important;
        }
        #mobileNav .btn-ghost-sm,
        .mobile-nav .btn-ghost-sm {
            background: #f8fafc !important;
            background-color: #f8fafc !important;
            color: #0f172a !important;
            border: 1.5px solid #cbd5e1 !important;
        }
        #mobileNav .mobile-close,
        .mobile-nav .mobile-close {
            background: #f1f5f9 !important;
            background-color: #f1f5f9 !important;
            color: #475569 !important;
        }
        #mobileOverlay,
        .mobile-nav-overlay {
            background: rgba(15, 23, 42, 0.45) !important;
        }
    </style>
    <div class="mobile-nav-overlay" id="mobileOverlay" onclick="toggleMobileNav()" style="background: rgba(15, 23, 42, 0.45) !important;"></div>
    <nav class="mobile-nav" id="mobileNav" style="background: #ffffff !important; background-color: #ffffff !important; color: #0f172a !important; border-left: 1px solid #e2e8f0 !important; box-shadow: -12px 0 40px rgba(15, 23, 42, 0.15) !important;">
        {{-- Drawer Header with Brand and Close Button --}}
        <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-100" style="border-bottom: 1px solid #f1f5f9 !important;">
            <x-brand-logo
                href="{{ url('/') }}"
                class="group flex-shrink-0"
                imageClass="h-7 w-auto"
                textClass="text-base font-bold tracking-tight text-slate-900"
                accentClass="text-[#2563EB]"
            />
            <button type="button" class="mobile-close" onclick="toggleMobileNav()" aria-label="Close menu" style="position: static !important; width: 34px !important; height: 34px !important; border-radius: 50% !important; background: #f1f5f9 !important; color: #475569 !important; border: none !important; display: flex !important; align-items: center !important; justify-content: center !important; cursor: pointer !important;">
                <i class="ph-bold ph-x text-sm" style="color: #475569 !important;"></i>
            </button>
        </div>

        <div style="display: flex; flex-direction: column; gap: 4px;">
            <a href="{{ route('properties.index') }}" class="mobile-nav-link" style="display: flex !important; align-items: center !important; gap: 12px !important; padding: 10px 12px !important; border-radius: 12px !important; background: transparent !important; color: #0f172a !important; text-decoration: none !important; border: none !important;" title="Discover">
                <span style="width: 34px; height: 34px; border-radius: 10px; background: #eff6ff; color: #2563EB; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="ph-bold ph-compass text-base" style="color: #2563EB;"></i>
                </span>
                <span style="font-size: 15px; font-weight: 700; color: #0f172a !important;">Discover</span>
            </a>
            <a href="{{ route('properties.index', ['purpose' => 'buy']) }}" class="mobile-nav-link" style="display: flex !important; align-items: center !important; gap: 12px !important; padding: 10px 12px !important; border-radius: 12px !important; background: transparent !important; color: #0f172a !important; text-decoration: none !important; border: none !important;" title="Buy">
                <span style="width: 34px; height: 34px; border-radius: 10px; background: #eef2ff; color: #4f46e5; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="ph-bold ph-shopping-bag text-base" style="color: #4f46e5;"></i>
                </span>
                <span style="font-size: 15px; font-weight: 700; color: #0f172a !important;">Buy</span>
            </a>
            <a href="{{ route('properties.index', ['purpose' => 'rent']) }}" class="mobile-nav-link" style="display: flex !important; align-items: center !important; gap: 12px !important; padding: 10px 12px !important; border-radius: 12px !important; background: transparent !important; color: #0f172a !important; text-decoration: none !important; border: none !important;" title="Rent">
                <span style="width: 34px; height: 34px; border-radius: 10px; background: #fffbeb; color: #d97706; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="ph-bold ph-key text-base" style="color: #d97706;"></i>
                </span>
                <span style="font-size: 15px; font-weight: 700; color: #0f172a !important;">Rent</span>
            </a>
            <a href="{{ route('properties.index', ['type' => 'commercial']) }}" class="mobile-nav-link" style="display: flex !important; align-items: center !important; gap: 12px !important; padding: 10px 12px !important; border-radius: 12px !important; background: transparent !important; color: #0f172a !important; text-decoration: none !important; border: none !important;" title="Commercial">
                <span style="width: 34px; height: 34px; border-radius: 10px; background: #ecfdf5; color: #059669; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="ph-bold ph-buildings text-base" style="color: #059669;"></i>
                </span>
                <span style="font-size: 15px; font-weight: 700; color: #0f172a !important;">Commercial</span>
            </a>
            <a href="{{ route('properties.index', ['type' => 'plot']) }}" class="mobile-nav-link" style="display: flex !important; align-items: center !important; gap: 12px !important; padding: 10px 12px !important; border-radius: 12px !important; background: transparent !important; color: #0f172a !important; text-decoration: none !important; border: none !important;" title="Plots / Land">
                <span style="width: 34px; height: 34px; border-radius: 10px; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="ph-bold ph-map-trifold text-base" style="color: #d97706;"></i>
                </span>
                <span style="font-size: 15px; font-weight: 700; color: #0f172a !important;">Plots / Land</span>
            </a>
            <a href="{{ url('/how-it-works') }}" class="mobile-nav-link" style="display: flex !important; align-items: center !important; gap: 12px !important; padding: 10px 12px !important; border-radius: 12px !important; background: transparent !important; color: #0f172a !important; text-decoration: none !important; border: none !important;" title="Process">
                <span style="width: 34px; height: 34px; border-radius: 10px; background: #faf5ff; color: #7c3aed; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="ph-bold ph-git-merge text-base" style="color: #7c3aed;"></i>
                </span>
                <span style="font-size: 15px; font-weight: 700; color: #0f172a !important;">Process</span>
            </a>
            <a href="{{ url('/blog') }}" class="mobile-nav-link" style="display: flex !important; align-items: center !important; gap: 12px !important; padding: 10px 12px !important; border-radius: 12px !important; background: transparent !important; color: #0f172a !important; text-decoration: none !important; border: none !important;" title="Blog">
                <span style="width: 34px; height: 34px; border-radius: 10px; background: #fff1f2; color: #e11d48; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="ph-bold ph-newspaper text-base" style="color: #e11d48;"></i>
                </span>
                <span style="font-size: 15px; font-weight: 700; color: #0f172a !important;">Blog</span>
            </a>
        </div>

        <div class="mobile-auth" style="margin-top: 18px !important; padding-top: 16px !important; border-top: 1px solid #f1f5f9 !important;">
            @if (Route::has('login'))
                @auth
                    <div style="padding: 12px; margin-bottom: 10px; border-radius: 14px; background: #f8fafc; border: 1px solid #e2e8f0;">
                        <p style="color: #64748b; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 2px;">Signed in as</p>
                        <p style="color: #0f172a; font-weight: 700; font-size: 14px; line-height: 1.3;">{{ auth()->user()->name }}</p>
                        <p style="color: #64748b; font-size: 12px;">{{ auth()->user()->email }}</p>
                    </div>
                    <a href="{{ route('properties.create') }}" class="btn-primary-sm btn-cta-premium" style="text-align: center !important; display: flex !important; align-items: center !important; justify-content: center !important; gap: 8px !important; width: 100% !important; border-radius: 12px !important; height: 48px !important; margin-bottom: 8px !important; background: linear-gradient(135deg, #2563EB, #1d4ed8) !important; color: #ffffff !important; box-shadow: 0 8px 20px rgba(37,99,235,0.25) !important;" title="Post Free Advertise">
                        <i class="ph-bold ph-plus-circle" style="font-size: 19px; color: #ffffff !important;"></i>
                        <span style="color: #ffffff !important; font-weight: 700;">Post Free Advertise</span>
                    </a>
                    <a href="{{ url('/dashboard') }}" class="btn-ghost-sm" style="text-align: center !important; width: 100% !important; margin-bottom: 8px !important; display: flex !important; align-items: center !important; justify-content: center !important; gap: 8px !important; height: 46px !important; background: #f8fafc !important; color: #0f172a !important; border: 1.5px solid #cbd5e1 !important; border-radius: 12px !important; font-weight: 700 !important;" title="Dashboard">
                        <i class="ph-bold ph-squares-four text-blue-600"></i>
                        <span style="color: #0f172a !important;">Dashboard</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" onsubmit="window.performUniversalLogout(event)">
                        @csrf
                        <button type="button" onclick="window.performUniversalLogout(event)" class="w-full flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 font-bold text-sm transition-all cursor-pointer">
                            <i class="ph-bold ph-sign-out text-base"></i>
                            <span>Sign Out</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" onclick="event.preventDefault(); toggleMobileNav(); window.openAuthModal('login');" class="btn-ghost-sm" style="text-align: center !important; display: flex !important; align-items: center !important; justify-content: center !important; gap: 8px !important; height: 46px !important; background: #f8fafc !important; color: #0f172a !important; border: 1.5px solid #cbd5e1 !important; border-radius: 12px !important; font-weight: 700 !important;" title="Log in">
                        <i class="ph-bold ph-sign-in text-blue-600 text-base"></i>
                        <span style="color: #0f172a !important;">Log in</span>
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('properties.create') }}" class="btn-primary-sm btn-cta-premium" style="text-align: center !important; display: flex !important; align-items: center !important; justify-content: center !important; gap: 8px !important; width: 100% !important; border-radius: 12px !important; height: 48px !important; background: linear-gradient(135deg, #2563EB, #1d4ed8) !important; color: #ffffff !important; box-shadow: 0 8px 20px rgba(37,99,235,0.25) !important;" title="Post Free Advertise">
                            <i class="ph-bold ph-plus-circle" style="font-size: 19px; color: #ffffff !important;"></i>
                            <span style="color: #ffffff !important; font-weight: 700;">Post Free Advertise</span>
                        </a>
                    @endif
                @endauth
            @endif
        </div>
    </nav>

    {{-- Dedicated Mobile Account Bottom Sheet Modal for Welcome Page --}}
    @auth
    <div id="welcome-account-modal" class="fixed inset-0 z-[10000] hidden flex items-end sm:items-center justify-center p-0 sm:p-4 bg-slate-950/40 backdrop-blur-sm" onclick="if(event.target===this) window.closeWelcomeAccountModal()">
        <div class="w-full sm:max-w-md bg-white border border-slate-200 rounded-t-3xl sm:rounded-3xl p-6 shadow-2xl animate-[slideUp_0.2s_ease-out]">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-600 text-white font-black text-xl flex items-center justify-center shadow-lg shadow-blue-500/25">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <h3 class="text-base font-black text-slate-900 truncate">{{ auth()->user()->name }}</h3>
                        <p class="text-xs text-slate-500 capitalize truncate">{{ ucfirst(auth()->user()->role) }} · {{ auth()->user()->email }}</p>
                    </div>
                </div>
                <button type="button" onclick="window.closeWelcomeAccountModal()" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 hover:text-slate-900 flex items-center justify-center transition-colors">
                    <i class="ph-bold ph-x text-base"></i>
                </button>
            </div>
            
            <div class="py-3 space-y-1">
                <a href="{{ route('dashboard') }}" onclick="window.closeWelcomeAccountModal()" class="flex items-center gap-3 px-3.5 py-3 rounded-2xl text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors">
                    <i class="ph-bold ph-squares-four text-xl text-blue-600"></i>
                    <span>My Dashboard</span>
                </a>
                <a href="{{ route('plans.index') }}" onclick="window.closeWelcomeAccountModal()" class="flex items-center gap-3 px-3.5 py-3 rounded-2xl text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors">
                    <i class="ph-bold ph-crown text-xl text-blue-600"></i>
                    <span>Membership Plans</span>
                </a>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" onclick="window.closeWelcomeAccountModal()" class="flex items-center gap-3 px-3.5 py-3 rounded-2xl text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors">
                        <i class="ph-bold ph-shield-check text-xl text-blue-600"></i>
                        <span>Admin Panel</span>
                    </a>
                @endif
            </div>

            <div class="pt-3 border-t border-slate-100">
                <form method="POST" action="{{ route('logout') }}" onsubmit="window.performUniversalLogout(event)">
                    @csrf
                    <button type="button" onclick="window.performUniversalLogout(event)" class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 font-bold text-sm transition-all cursor-pointer active:scale-[0.98]">
                        <i class="ph-bold ph-sign-out text-base"></i>
                        <span>Sign Out</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endauth

    <section class="hero-section hidden lg:block">
        <div class="hero-bg">
            <div class="glow-orb orb-1"></div>
            <div class="glow-orb orb-2"></div>
            <picture>
                <source srcset="{{ asset('images/hero-bg.webp') }}" type="image/webp">
                <img src="{{ asset('images/hero-bg.png') }}" 
                     alt="Premium Indian City Skyline Real Estate" 
                     title="Premium Indian City Skyline Real Estate" 
                     width="1024" 
                     height="1024" 
                     loading="eager" 
                     fetchpriority="high" 
                     decoding="async">
            </picture>
            <div class="overlay-gradient"></div>
        </div>

        <div class="hero-container">
            <div class="hero-badge badge-animate">
                <span class="badge-dot"></span>
                Verified Premium Listings
            </div>

            <h1 class="hero-title title-animate" style="max-width: 920px; margin-left: auto; margin-right: auto;">
                Find Your <span style="background: linear-gradient(135deg, #93c5fd 0%, #2563EB 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Perfect Home</span> <br class="hidden sm:inline">
                Across <span style="background: linear-gradient(90deg, #dbeafe 0%, #60a5fa 40%, #2563EB 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">India's Top Cities</span>
            </h1>

            <p class="hero-subtitle subtitle-animate">
                Verified rooms, flats, PG stays & houses for rent near your location directly from owners.
            </p>

            {{-- Quick SEO Shortcuts / Near Me Badges --}}
            <div class="flex items-center justify-center flex-wrap gap-2 mb-4 px-2">
                <a href="{{ url('/room-near-my-location') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-blue-600/40 hover:bg-blue-600 text-blue-100 hover:text-white text-xs font-bold border border-blue-400/40 transition shadow-sm" title="Rooms Near My Location">
                    <i class="ph-bold ph-map-pin text-sm text-blue-300"></i>
                    <span>📍 Rooms Near My Location</span>
                </a>
                <a href="{{ url('/flat-for-rent-near-me') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-white/10 hover:bg-white/20 text-slate-200 hover:text-white text-xs font-semibold border border-white/15 transition shadow-sm" title="Flats Near Me">
                    <i class="ph-bold ph-buildings text-sm text-sky-300"></i>
                    <span>Flats Near Me</span>
                </a>
                <a href="{{ url('/pg-near-me') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-white/10 hover:bg-white/20 text-slate-200 hover:text-white text-xs font-semibold border border-white/15 transition shadow-sm" title="PG Near Me">
                    <i class="ph-bold ph-bed text-sm text-amber-300"></i>
                    <span>PG Near Me</span>
                </a>
                <a href="{{ url('/house-for-rent-near-me') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-white/10 hover:bg-white/20 text-slate-200 hover:text-white text-xs font-semibold border border-white/15 transition shadow-sm" title="House For Rent Near Me">
                    <i class="ph-bold ph-house text-sm text-emerald-300"></i>
                    <span>House Near Me</span>
                </a>
            </div>

            <form class="search-glass-panel panel-animate" action="{{ route('properties.index') }}" method="GET" data-ur-loader-msg="Searching premium properties&#8230;" onsubmit="handleHeroSearchSubmit(this)">

                <!-- ─ Row 1: Primary Filters ─ -->
                <div class="search-filters-row">

                    <div class="filter-group">
                        <div class="filter-icon">
                            <i class="ph-bold ph-map-pin" style="font-size: 20px; color: var(--primary);"></i>
                        </div>
                        <div class="filter-input-wrap">
                            <label for="state-select" class="filter-label">Region / State</label>
                            <select class="filter-input" id="state-select" name="state" onchange="if(window.handleLocationStateChange) window.handleLocationStateChange(this, 'city-select', 'locality-select');">
                                <option value="">&nbsp;&nbsp;Select State</option>
                                @php $statesList = $globalAllStates ?? $allStates ?? []; @endphp
                                @if(!empty($statesList) && count($statesList) > 0)
                                    @foreach($statesList as $code => $name)
                                        <option value="{{ $code }}" {{ request('state') == $code ? 'selected' : '' }}>&nbsp;&nbsp;{{ $name }}</option>
                                    @endforeach
                                @else
                                    <option value="AP">&nbsp;&nbsp;Andhra Pradesh</option>
                                    <option value="AR">&nbsp;&nbsp;Arunachal Pradesh</option>
                                    <option value="AS">&nbsp;&nbsp;Assam</option>
                                    <option value="BR">&nbsp;&nbsp;Bihar</option>
                                    <option value="CT">&nbsp;&nbsp;Chhattisgarh</option>
                                    <option value="GA">&nbsp;&nbsp;Goa</option>
                                    <option value="GJ">&nbsp;&nbsp;Gujarat</option>
                                    <option value="HR">&nbsp;&nbsp;Haryana</option>
                                    <option value="HP">&nbsp;&nbsp;Himachal Pradesh</option>
                                    <option value="JH">&nbsp;&nbsp;Jharkhand</option>
                                    <option value="KA">&nbsp;&nbsp;Karnataka</option>
                                    <option value="KL">&nbsp;&nbsp;Kerala</option>
                                    <option value="MP">&nbsp;&nbsp;Madhya Pradesh</option>
                                    <option value="MH">&nbsp;&nbsp;Maharashtra</option>
                                    <option value="MN">&nbsp;&nbsp;Manipur</option>
                                    <option value="ML">&nbsp;&nbsp;Meghalaya</option>
                                    <option value="MZ">&nbsp;&nbsp;Mizoram</option>
                                    <option value="NL">&nbsp;&nbsp;Nagaland</option>
                                    <option value="OR">&nbsp;&nbsp;Odisha</option>
                                    <option value="PB">&nbsp;&nbsp;Punjab</option>
                                    <option value="RJ">&nbsp;&nbsp;Rajasthan</option>
                                    <option value="SK">&nbsp;&nbsp;Sikkim</option>
                                    <option value="TN">&nbsp;&nbsp;Tamil Nadu</option>
                                    <option value="TS">&nbsp;&nbsp;Telangana</option>
                                    <option value="TR">&nbsp;&nbsp;Tripura</option>
                                    <option value="UP">&nbsp;&nbsp;Uttar Pradesh</option>
                                    <option value="UK">&nbsp;&nbsp;Uttarakhand</option>
                                    <option value="WB">&nbsp;&nbsp;West Bengal</option>
                                    <option value="DL">&nbsp;&nbsp;Delhi</option>
                                    <option value="CH">&nbsp;&nbsp;Chandigarh</option>
                                @endif
                            </select>
                        </div>
                        <div class="dropdown-chevron">
                            <i class="ph ph-caret-down"></i>
                        </div>
                    </div>

                    <div class="filter-group">
                        <div class="filter-icon">
                            <i class="ph-bold ph-city" style="font-size: 20px; color: var(--primary);"></i>
                        </div>
                        <div class="filter-input-wrap">
                            <label for="city-select" class="filter-label">City / District</label>
                            <select class="filter-input" id="city-select" name="district" onchange="if(window.handleLocationCityChange) window.handleLocationCityChange(this, 'locality-select', 'state-select');">
                                <option value="">&nbsp;&nbsp;All Cities / Districts</option>
                                <option value="Gurugram" {{ request('district') == 'Gurugram' ? 'selected' : '' }}>&nbsp;&nbsp;Gurugram (HR)</option>
                                <option value="New Delhi" {{ request('district') == 'New Delhi' ? 'selected' : '' }}>&nbsp;&nbsp;New Delhi (DL)</option>
                                <option value="South Delhi" {{ request('district') == 'South Delhi' ? 'selected' : '' }}>&nbsp;&nbsp;South Delhi (DL)</option>
                                <option value="Noida" {{ request('district') == 'Noida' ? 'selected' : '' }}>&nbsp;&nbsp;Noida (UP)</option>
                                <option value="Ghaziabad" {{ request('district') == 'Ghaziabad' ? 'selected' : '' }}>&nbsp;&nbsp;Ghaziabad (UP)</option>
                                <option value="Faridabad" {{ request('district') == 'Faridabad' ? 'selected' : '' }}>&nbsp;&nbsp;Faridabad (HR)</option>
                                <option value="Bengaluru" {{ request('district') == 'Bengaluru' ? 'selected' : '' }}>&nbsp;&nbsp;Bengaluru (KA)</option>
                                <option value="Mumbai" {{ request('district') == 'Mumbai' ? 'selected' : '' }}>&nbsp;&nbsp;Mumbai (MH)</option>
                                <option value="Pune" {{ request('district') == 'Pune' ? 'selected' : '' }}>&nbsp;&nbsp;Pune (MH)</option>
                                <option value="Hyderabad" {{ request('district') == 'Hyderabad' ? 'selected' : '' }}>&nbsp;&nbsp;Hyderabad (TS)</option>
                                <option value="Jaipur" {{ request('district') == 'Jaipur' ? 'selected' : '' }}>&nbsp;&nbsp;Jaipur (RJ)</option>
                                <option value="Chandigarh" {{ request('district') == 'Chandigarh' ? 'selected' : '' }}>&nbsp;&nbsp;Chandigarh (CH)</option>
                                <option value="Kolkata" {{ request('district') == 'Kolkata' ? 'selected' : '' }}>&nbsp;&nbsp;Kolkata (WB)</option>
                                <option value="Chennai" {{ request('district') == 'Chennai' ? 'selected' : '' }}>&nbsp;&nbsp;Chennai (TN)</option>
                                <option value="Ahmedabad" {{ request('district') == 'Ahmedabad' ? 'selected' : '' }}>&nbsp;&nbsp;Ahmedabad (GJ)</option>
                            </select>
                        </div>
                        <div class="dropdown-chevron">
                            <i class="ph ph-caret-down"></i>
                        </div>
                    </div>

                    <div class="filter-group">
                        <div class="filter-icon">
                            <i class="ph-bold ph-map-trifold" style="font-size: 20px; color: var(--primary);"></i>
                        </div>
                        <div class="filter-input-wrap">
                            <label for="locality-select" class="filter-label">Locality / Area</label>
                            <select class="filter-input" id="locality-select" name="locality">
                                <option value="">&nbsp;&nbsp;Select Locality</option>
                            </select>
                        </div>
                        <div class="dropdown-chevron">
                            <i class="ph ph-caret-down"></i>
                        </div>
                    </div>

                </div><!-- end .search-filters-row -->

                <!-- ─ Row 2: Configs & Actions ─ -->
                <div class="search-actions-row">
                    <div class="search-quick-configs">
                        
                        <div class="config-item">
                            <label>Property Type</label>
                            <select name="type">
                                <option value="all">Any Type</option>
                                <option value="house">House / Villa</option>
                                <option value="shop">Shop / Commercial</option>
                                <option value="pg-hostel">PG / Hostel</option>
                                <option value="hotel">Hotel</option>
                            </select>
                        </div>

                        <div class="config-divider"></div>

                        <div class="config-item">
                            <label>Budget</label>
                            <select name="price">
                                <option value="any">Any Price</option>
                                <option value="0-20000">Up to ₹20,000</option>
                                <option value="20000-50000">₹20K – ₹50K</option>
                                <option value="50000-plus">₹50,000+</option>
                            </select>
                        </div>
                        
                        <div class="config-item">
                            <label>Layout</label>
                            <div class="pill-group">
                                <input type="hidden" name="rooms" id="rooms-input" value="{{ request('rooms', 'any') }}">
                                <button type="button" class="pill-btn {{ request('rooms', 'any') == 'any' ? 'active' : '' }}" data-value="any" onclick="window.setPill(this, 'any', 'rooms-input')">Any</button>
                                <button type="button" class="pill-btn {{ request('rooms') == '1bhk' ? 'active' : '' }}" data-value="1bhk" onclick="window.setPill(this, '1bhk', 'rooms-input')">1 BHK</button>
                                <button type="button" class="pill-btn {{ request('rooms') == '2bhk' ? 'active' : '' }}" data-value="2bhk" onclick="window.setPill(this, '2bhk', 'rooms-input')">2 BHK</button>
                                <button type="button" class="pill-btn {{ in_array(request('rooms'), ['3bhk-plus', '3bhk', '3plus']) ? 'active' : '' }}" data-value="3bhk-plus" onclick="window.setPill(this, '3bhk-plus', 'rooms-input')">3+</button>
                            </div>
                        </div>

                        <div class="config-divider"></div>

                        <div class="config-item">
                            <label>Intent</label>
                            <div class="pill-group">
                                <input type="hidden" name="purpose" id="purpose-input" value="{{ request('purpose', 'rent') }}">
                                <button type="button" class="pill-btn {{ request('purpose', 'rent') == 'rent' ? 'active' : '' }}" data-value="rent" onclick="window.setPill(this, 'rent', 'purpose-input')">Rent</button>
                                <button type="button" class="pill-btn {{ request('purpose') == 'buy' ? 'active' : '' }}" data-value="buy" onclick="window.setPill(this, 'buy', 'purpose-input')">Buy</button>
                            </div>
                        </div>

                    </div>

                    <button type="submit" class="btn-search-premium">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        <span>Find Now</span>
                    </button>
                </div>

            </form>

            <div class="actions-wrapper actions-animate">
                <button type="button" id="btnNearMe" onclick="searchNearMe()" class="btn-outline-blue" style="background: rgba(37,99,235,0.25); border-color: #3b82f6; display: inline-flex; align-items: center; gap: 8px; cursor: pointer;">
                    <i class="ph-fill ph-navigation-arrow" style="font-size: 16px; color: #60a5fa;"></i>
                    <span>Search House Near Me</span>
                </button>
                <span class="action-text">Own a property?</span>
                <a href="{{ route('properties.create') }}" class="btn-outline-blue" title="Post Free Advertise">Post Free Advertise</a>
                <a href="{{ route('properties.index') }}" class="btn-text-link" title="View Showreel">
                    <div class="play-icon">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                    </div>
                    View Showreel
                </a>
            </div>

            <div class="trust-indicators indicators-animate">
                <div class="indicator-box">
                    <div class="indicator-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path></svg>
                    </div>
                    <div class="indicator-text">
                        <span class="val">10,000+</span>
                        <span class="lbl">Luxury Properties</span>
                    </div>
                </div>
                <div class="indicator-box">
                    <div class="indicator-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    </div>
                    <div class="indicator-text">
                        <span class="val">100%</span>
                        <span class="lbl">Verified Listings</span>
                    </div>
                </div>
                <div class="indicator-box">
                    <div class="indicator-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    </div>
                    <div class="indicator-text">
                        <span class="val">24/7</span>
                        <span class="lbl">Concierge Support</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ====================================================
         MOBILE APP HERO SLIDER SECTION (Clean, Standard Format)
         Visible ONLY in Mobile Application / Screens < 1024px
         ==================================================== --}}
    {{-- ====================================================
         FLIPKART STYLE MOBILE APP HERO SECTION
         Clean, standard, app-native format like Flipkart App
         Visible ONLY in Mobile Application / Screens < 1024px
         ==================================================== --}}
    <section class="mobile-app-hero-slider-section block lg:hidden">
        
        {{-- 1. Flipkart Top Location & Brand Status Bar --}}
        <div class="flex items-center justify-between gap-2 mb-2 px-0.5">
            <button type="button" onclick="openMobileCityModal()" class="flipkart-location-chip" title="Click to select city">
                <i class="ph-fill ph-map-pin text-blue-600 text-xs"></i>
                <span id="mobileTopLocationText">{{ request('district') ?: 'Gurugram / NCR' }}</span>
                <i class="ph ph-caret-down text-[10px] text-slate-500"></i>
            </button>
            <div class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-blue-50 border border-blue-200 shadow-xs">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-[9.5px] font-extrabold tracking-wider text-blue-700 uppercase">100% Direct Owner</span>
            </div>
        </div>

        @php
            $mobileActiveFiltersCount = 0;
            if(request()->filled('district')) $mobileActiveFiltersCount++;
            if(request()->filled('price') && request('price') !== 'any') $mobileActiveFiltersCount++;
            if(request()->filled('rooms') && request('rooms') !== 'any') $mobileActiveFiltersCount++;
            if(request()->filled('purpose') && request('purpose') !== 'rent') $mobileActiveFiltersCount++;
        @endphp

        {{-- 2. Flipkart Prominent Search Bar with Filter Button --}}
        <div class="mb-2.5">
            <form id="mobileHeroSearchForm" action="{{ route('properties.index') }}" method="GET">
                <input type="hidden" name="purpose" id="mobile_purpose_input" value="{{ request('purpose', 'rent') }}">
                <input type="hidden" name="type" id="mobile_type_input" value="{{ request('type', 'all') }}">
                <input type="hidden" name="rooms" id="mobile_rooms_input" value="{{ request('rooms', 'any') }}">

                {{-- Unified Search Row --}}
                <div class="mobile-app-search-bar flex items-center gap-1.5">
                    <div class="relative flex-1 flex items-center min-w-0">
                        <i class="ph-bold ph-magnifying-glass text-blue-600 text-sm absolute left-2.5 pointer-events-none"></i>
                        <input type="text" 
                               name="search" 
                               id="mobile_search_input"
                               placeholder="Search 'Rooms in Gurugram'..." 
                               value="{{ request('search') }}"
                               class="w-full pl-8 pr-14 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-blue-500">
                        
                        {{-- Quick Near GPS inside input --}}
                        <button type="button" 
                                id="mobileBtnNearMe" 
                                onclick="searchNearMe()" 
                                class="absolute right-1 px-2 py-1 rounded-lg bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white border border-blue-200 text-[10px] font-bold flex items-center gap-0.5 transition-all active:scale-95"
                                title="Locate Near Me">
                            <i class="ph-fill ph-navigation-arrow text-[11px] text-blue-600"></i>
                            <span>Near</span>
                        </button>
                    </div>

                    {{-- Dedicated Filter Dropdown Toggle Button --}}
                    <button type="button" 
                            id="mobileFilterToggleBtn" 
                            onclick="toggleMobileFiltersDropdown()" 
                            class="mobile-filter-btn {{ $mobileActiveFiltersCount > 0 ? 'active' : '' }}"
                            aria-expanded="false"
                            aria-controls="mobileFiltersDropdownPanel">
                        <i class="ph-bold ph-sliders-horizontal text-xs text-blue-600"></i>
                        <span>Filter</span>
                        <i class="ph-bold ph-caret-down text-[10px] transition-transform duration-300" id="mobileFilterCaret"></i>

                        @if($mobileActiveFiltersCount > 0)
                            <span id="mobileFilterBadge" class="absolute -top-1.5 -right-1.5 w-4 h-4 rounded-full bg-blue-600 text-white text-[9px] font-extrabold flex items-center justify-center ring-2 ring-white shadow-md">
                                {{ $mobileActiveFiltersCount }}
                            </span>
                        @endif
                    </button>

                    {{-- Search Submit Action --}}
                    <button type="submit" 
                            class="w-8 h-8 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white flex items-center justify-center flex-shrink-0 shadow-md shadow-blue-500/25 active:scale-95 transition-all"
                            title="Search">
                        <i class="ph-bold ph-arrow-right text-xs"></i>
                    </button>
                </div>

                {{-- Collapsible Dropdown Filter Panel (Reveals on clicking Filter button) --}}
                <div id="mobileFiltersDropdownPanel" class="mobile-filter-dropdown-panel hidden">
                    
                    {{-- Dropdown Header --}}
                    <div class="flex items-center justify-between pb-2.5 mb-3 border-b border-slate-200">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs">
                                <i class="ph-bold ph-faders-horizontal"></i>
                            </div>
                            <span class="text-xs font-black text-slate-900 tracking-wide">Filter Properties</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <button type="button" 
                                    onclick="resetMobileFilters()" 
                                    class="text-[11px] font-bold text-slate-500 hover:text-red-500 active:scale-95 transition-colors">
                                Reset
                            </button>
                            <button type="button" 
                                    onclick="toggleMobileFiltersDropdown()" 
                                    class="text-slate-400 hover:text-slate-700 p-1 rounded-md active:scale-95 transition-colors"
                                    aria-label="Close Filter">
                                <i class="ph-bold ph-x text-sm"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Purpose Tabs (Rent / Buy / PG) inside Dropdown --}}
                    <div class="mb-3">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block mb-1.5">Listing Purpose</span>
                        <div class="grid grid-cols-4 gap-1 p-1 bg-slate-100 rounded-xl border border-slate-200">
                            <button type="button" 
                                    id="mobileTabRent"
                                    onclick="switchMobileTab('rent')"
                                    class="mobile-purpose-tab {{ request('purpose', 'rent') === 'rent' && request('type') !== 'pg-hostel' && request('type') !== 'plot' ? 'active bg-blue-600 text-white shadow-md shadow-blue-500/25' : 'text-slate-600 hover:text-slate-900' }} py-1.5 text-xs font-bold rounded-lg text-center transition-all">
                                Rent
                            </button>
                            <button type="button" 
                                    id="mobileTabBuy"
                                    onclick="switchMobileTab('buy')"
                                    class="mobile-purpose-tab {{ request('purpose') === 'buy' && request('type') !== 'plot' ? 'active bg-blue-600 text-white shadow-md shadow-blue-500/25' : 'text-slate-600 hover:text-slate-900' }} py-1.5 text-xs font-bold rounded-lg text-center transition-all">
                                Buy
                            </button>
                            <button type="button" 
                                    id="mobileTabPg"
                                    onclick="switchMobileTab('pg')"
                                    class="mobile-purpose-tab {{ request('type') === 'pg-hostel' ? 'active bg-blue-600 text-white shadow-md shadow-blue-500/25' : 'text-slate-600 hover:text-slate-900' }} py-1.5 text-xs font-bold rounded-lg text-center transition-all">
                                PG
                            </button>
                            <button type="button" 
                                    id="mobileTabPlot"
                                    onclick="switchMobileTab('plot')"
                                    class="mobile-purpose-tab {{ request('type') === 'plot' ? 'active bg-blue-600 text-white shadow-md shadow-blue-500/25' : 'text-slate-600 hover:text-slate-900' }} py-1.5 text-xs font-bold rounded-lg text-center transition-all">
                                Plot
                            </button>
                        </div>
                    </div>

                    {{-- City & Budget Grid inside Dropdown --}}
                    <div class="grid grid-cols-2 gap-2 mb-3">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block mb-1">City</span>
                            <div class="relative">
                                <select name="district" 
                                        id="mobile_city_select"
                                        class="w-full appearance-none pl-3 pr-7 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:bg-white focus:outline-none focus:border-blue-500">
                                    <option value="">All Top Cities</option>
                                    <option value="Gurugram" {{ request('district') == 'Gurugram' ? 'selected' : '' }}>Gurugram</option>
                                    <option value="New Delhi" {{ request('district') == 'New Delhi' ? 'selected' : '' }}>Delhi NCR</option>
                                    <option value="Noida" {{ request('district') == 'Noida' ? 'selected' : '' }}>Noida</option>
                                    <option value="Faridabad" {{ request('district') == 'Faridabad' ? 'selected' : '' }}>Faridabad</option>
                                    <option value="Ghaziabad" {{ request('district') == 'Ghaziabad' ? 'selected' : '' }}>Ghaziabad</option>
                                    <option value="Bengaluru" {{ request('district') == 'Bengaluru' ? 'selected' : '' }}>Bengaluru</option>
                                    <option value="Mumbai" {{ request('district') == 'Mumbai' ? 'selected' : '' }}>Mumbai</option>
                                    <option value="Pune" {{ request('district') == 'Pune' ? 'selected' : '' }}>Pune</option>
                                    <option value="Hyderabad" {{ request('district') == 'Hyderabad' ? 'selected' : '' }}>Hyderabad</option>
                                    <option value="Jaipur" {{ request('district') == 'Jaipur' ? 'selected' : '' }}>Jaipur</option>
                                    <option value="Chandigarh" {{ request('district') == 'Chandigarh' ? 'selected' : '' }}>Chandigarh</option>
                                </select>
                                <i class="ph ph-caret-down absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs"></i>
                            </div>
                        </div>

                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block mb-1">Budget</span>
                            <div class="relative">
                                <select name="price" 
                                        id="mobile_price_select"
                                        class="w-full appearance-none pl-3 pr-7 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:bg-white focus:outline-none focus:border-blue-500">
                                    <option value="any">Any Budget</option>
                                    <option value="0-20000" {{ request('price') == '0-20000' ? 'selected' : '' }}>Under ₹20,000</option>
                                    <option value="20000-50000" {{ request('price') == '20000-50000' ? 'selected' : '' }}>₹20K – ₹50K</option>
                                    <option value="50000-plus" {{ request('price') == '50000-plus' ? 'selected' : '' }}>₹50,000+</option>
                                </select>
                                <i class="ph ph-caret-down absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Layout BHK Selection Pills --}}
                    <div class="mb-3.5">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block mb-1.5">BHK / Layout</span>
                        <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar py-0.5">
                            <button type="button" onclick="setMobileRoom('any', this)" class="mobile-room-pill flex-shrink-0 px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ request('rooms', 'any') == 'any' ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">Any</button>
                            <button type="button" onclick="setMobileRoom('1rk', this)" class="mobile-room-pill flex-shrink-0 px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ request('rooms') == '1rk' ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">1 RK</button>
                            <button type="button" onclick="setMobileRoom('1bhk', this)" class="mobile-room-pill flex-shrink-0 px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ request('rooms') == '1bhk' ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">1 BHK</button>
                            <button type="button" onclick="setMobileRoom('2bhk', this)" class="mobile-room-pill flex-shrink-0 px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ request('rooms') == '2bhk' ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">2 BHK</button>
                            <button type="button" onclick="setMobileRoom('3bhk-plus', this)" class="mobile-room-pill flex-shrink-0 px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ in_array(request('rooms'), ['3bhk-plus', '3bhk', '3plus']) ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">3+ BHK</button>
                        </div>
                    </div>

                    {{-- Apply Filters Button --}}
                    <div class="flex items-center gap-2">
                        <button type="submit" 
                                class="flex-1 py-2.5 px-4 bg-gradient-to-r from-blue-600 via-blue-500 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl text-xs font-black shadow-lg shadow-blue-500/25 flex items-center justify-center gap-2 active:scale-[0.98] transition-all">
                            <i class="ph-bold ph-check text-sm"></i>
                            <span>Apply Filters</span>
                        </button>
                        <button type="button" 
                                onclick="toggleMobileFiltersDropdown()" 
                                class="py-2.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold active:scale-[0.98] transition-all">
                            Close
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- 3. Flipkart Signature Horizontal Circular Category Rail --}}
        <div class="mb-3">
            <div class="flipkart-cat-rail no-scrollbar">
                
                {{-- Category 1: Single Rooms --}}
                <a href="{{ url('/room-near-my-location') }}" class="flipkart-cat-item">
                    <div class="flipkart-cat-icon" style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);">
                        <i class="ph-bold ph-door text-white" style="font-size: 22px; color: #ffffff !important;"></i>
                    </div>
                    <span class="flipkart-cat-label">Rooms</span>
                </a>

                {{-- Category 2: Flats --}}
                <a href="{{ url('/flat-for-rent-near-me') }}" class="flipkart-cat-item">
                    <div class="flipkart-cat-icon" style="background: linear-gradient(135deg, #059669 0%, #047857 100%);">
                        <i class="ph-bold ph-buildings text-white" style="font-size: 22px; color: #ffffff !important;"></i>
                    </div>
                    <span class="flipkart-cat-label">Flats</span>
                </a>

                {{-- Category 3: PG Stays --}}
                <a href="{{ url('/pg-near-me') }}" class="flipkart-cat-item">
                    <div class="flipkart-cat-icon" style="background: linear-gradient(135deg, #d97706 0%, #b45309 100%);">
                        <i class="ph-bold ph-bed text-white" style="font-size: 22px; color: #ffffff !important;"></i>
                    </div>
                    <span class="flipkart-cat-label">PG Stays</span>
                </a>

                {{-- Category 4: Independent Houses --}}
                <a href="{{ url('/house-for-rent-near-me') }}" class="flipkart-cat-item">
                    <div class="flipkart-cat-icon" style="background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);">
                        <i class="ph-bold ph-house-line text-white" style="font-size: 22px; color: #ffffff !important;"></i>
                    </div>
                    <span class="flipkart-cat-label">Houses</span>
                </a>

                {{-- Category 5: Commercial --}}
                <a href="{{ route('properties.index', ['type' => 'commercial']) }}" class="flipkart-cat-item">
                    <div class="flipkart-cat-icon" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);">
                        <i class="ph-bold ph-storefront text-white" style="font-size: 22px; color: #ffffff !important;"></i>
                    </div>
                    <span class="flipkart-cat-label">Commercial</span>
                </a>

                {{-- Category 5b: Plots & Land --}}
                <a href="{{ route('properties.index', ['type' => 'plot']) }}" class="flipkart-cat-item">
                    <div class="flipkart-cat-icon" style="background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%);">
                        <i class="ph-bold ph-map-trifold text-white" style="font-size: 22px; color: #ffffff !important;"></i>
                    </div>
                    <span class="flipkart-cat-label">Plot/Land</span>
                </a>

                {{-- Category 6: Near Me GPS --}}
                <div onclick="openMobileCityModal()" class="flipkart-cat-item cursor-pointer">
                    <div class="flipkart-cat-icon" style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);">
                        <i class="ph-fill ph-navigation-arrow text-white" style="font-size: 22px; color: #ffffff !important;"></i>
                    </div>
                    <span class="flipkart-cat-label">Near Me</span>
                </div>

                {{-- Category 7: 0 Brokerage --}}
                <a href="{{ route('properties.index', ['purpose' => 'rent']) }}" class="flipkart-cat-item">
                    <div class="flipkart-cat-icon" style="background: linear-gradient(135deg, #e11d48 0%, #be123c 100%);">
                        <i class="ph-bold ph-tag text-white" style="font-size: 22px; color: #ffffff !important;"></i>
                    </div>
                    <span class="flipkart-cat-label">0 Broker</span>
                </a>

                {{-- Category 8: Post Free Property --}}
                <a href="{{ route('properties.create') }}" class="flipkart-cat-item">
                    <div class="flipkart-cat-icon" style="background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);">
                        <i class="ph-bold ph-plus-circle text-white" style="font-size: 22px; color: #ffffff !important;"></i>
                    </div>
                    <span class="flipkart-cat-label">Post Free</span>
                </a>

            </div>
        </div>

        {{-- 4. Flipkart Hero Promotional Banner Slider (Touch Swipe & Auto Sliding) --}}
        <div class="mobile-hero-slider-container" id="mobileHeroSliderContainer">
            <div class="mobile-hero-slider-track" id="mobileHeroSliderTrack">
                
                {{-- Slide 1: Rooms & 1RK Super Saver --}}
                <div class="mobile-hero-banner-slide" style="background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 55%, #312e81 100%);">
                    <div class="flex items-center justify-between">
                        <span class="px-2.5 py-0.5 rounded-full bg-white/20 backdrop-blur-md text-[9.5px] font-extrabold tracking-wide text-white uppercase inline-flex items-center gap-1 shadow-xs">
                            <i class="ph-fill ph-lightning text-amber-300"></i> BIG SAVINGS • 0% BROKERAGE
                        </span>
                        <span class="text-[10px] font-extrabold text-white bg-black/30 px-2.5 py-0.5 rounded-full backdrop-blur-xs">100% Direct Owner</span>
                    </div>
                    <div class="my-2">
                        <h2 class="text-lg sm:text-xl font-black text-white leading-tight tracking-tight mb-1">
                            Single Rooms & 1 RK Rentals <br>
                            <span class="text-amber-300 font-extrabold">Starting at ₹4,999/mo</span>
                        </h2>
                        <p class="text-[11px] text-white/90 line-clamp-1 leading-relaxed">
                            Verified independent rooms & studio units with direct owner contact.
                        </p>
                    </div>
                    <div class="flex items-center justify-between">
                        <a href="{{ url('/room-near-my-location') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-white text-blue-950 font-black text-[11px] shadow-lg active:scale-95 transition-all">
                            <span>Explore Homes</span>
                            <i class="ph-bold ph-arrow-right text-blue-600 text-xs"></i>
                        </a>
                        <span class="text-[10px] font-extrabold text-white bg-black/30 px-2.5 py-1 rounded-lg">Zero Deposit Options</span>
                    </div>
                </div>

                {{-- Slide 2: Flats & Gated Societies --}}
                <div class="mobile-hero-banner-slide" style="background: linear-gradient(135deg, #047857 0%, #065f46 55%, #0f172a 100%);">
                    <div class="flex items-center justify-between">
                        <span class="px-2.5 py-0.5 rounded-full bg-white/20 backdrop-blur-md text-[9.5px] font-extrabold tracking-wide text-white uppercase inline-flex items-center gap-1 shadow-xs">
                            <i class="ph-fill ph-shield-check text-emerald-300"></i> GATED SOCIETIES
                        </span>
                        <span class="text-[10px] font-extrabold text-white bg-black/30 px-2.5 py-0.5 rounded-full backdrop-blur-xs">Gurugram & NCR</span>
                    </div>
                    <div class="my-2">
                        <h2 class="text-lg sm:text-xl font-black text-white leading-tight tracking-tight mb-1">
                            1, 2 & 3 BHK Luxury Flats <br>
                            <span class="text-amber-300 font-extrabold">Starting at ₹14,999/mo</span>
                        </h2>
                        <p class="text-[11px] text-white/90 line-clamp-1 leading-relaxed">
                            High-rise societies with gym, parking, swimming pool & 24/7 power backup.
                        </p>
                    </div>
                    <div class="flex items-center justify-between">
                        <a href="{{ url('/flat-for-rent-near-me') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-white text-emerald-950 font-black text-[11px] shadow-lg active:scale-95 transition-all">
                            <span>Browse Flats</span>
                            <i class="ph-bold ph-arrow-right text-emerald-600 text-xs"></i>
                        </a>
                        <span class="text-[10px] font-extrabold text-white bg-black/30 px-2.5 py-1 rounded-lg">No Broker Fee</span>
                    </div>
                </div>

                {{-- Slide 3: PG & Co-Living --}}
                <div class="mobile-hero-banner-slide" style="background: linear-gradient(135deg, #b45309 0%, #c2410c 55%, #4c1d95 100%);">
                    <div class="flex items-center justify-between">
                        <span class="px-2.5 py-0.5 rounded-full bg-white/20 backdrop-blur-md text-[9.5px] font-extrabold tracking-wide text-white uppercase inline-flex items-center gap-1 shadow-xs">
                            <i class="ph-fill ph-bed text-amber-200"></i> ALL-INCLUSIVE LIVING
                        </span>
                        <span class="text-[10px] font-extrabold text-white bg-black/30 px-2.5 py-0.5 rounded-full backdrop-blur-xs">Food & Wi-Fi Included</span>
                    </div>
                    <div class="my-2">
                        <h2 class="text-lg sm:text-xl font-black text-white leading-tight tracking-tight mb-1">
                            Premium PG & Hostels <br>
                            <span class="text-amber-300 font-extrabold">Starting at ₹5,999/mo</span>
                        </h2>
                        <p class="text-[11px] text-white/90 line-clamp-1 leading-relaxed">
                            Air-conditioned rooms, 3-time home meals, Wi-Fi & daily cleaning.
                        </p>
                    </div>
                    <div class="flex items-center justify-between">
                        <a href="{{ url('/pg-near-me') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-white text-amber-950 font-black text-[11px] shadow-lg active:scale-95 transition-all">
                            <span>Explore Stays</span>
                            <i class="ph-bold ph-arrow-right text-amber-600 text-xs"></i>
                        </a>
                        <span class="text-[10px] font-extrabold text-white bg-black/30 px-2.5 py-1 rounded-lg">Boys & Girls PG</span>
                    </div>
                </div>

                {{-- Slide 4: Free Property Listing for Owners --}}
                <div class="mobile-hero-banner-slide" style="background: linear-gradient(135deg, #4338ca 0%, #6d28d9 55%, #0f172a 100%);">
                    <div class="flex items-center justify-between">
                        <span class="px-2.5 py-0.5 rounded-full bg-white/20 backdrop-blur-md text-[9.5px] font-extrabold tracking-wide text-white uppercase inline-flex items-center gap-1 shadow-xs">
                            <i class="ph-fill ph-sparkle text-indigo-200"></i> 100% FREE ADVERTISEMENT
                        </span>
                        <span class="text-[10px] font-extrabold text-white bg-black/30 px-2.5 py-0.5 rounded-full backdrop-blur-xs">Direct Inquiries</span>
                    </div>
                    <div class="my-2">
                        <h2 class="text-lg sm:text-xl font-black text-white leading-tight tracking-tight mb-1">
                            List Your Property Free <br>
                            <span class="text-amber-300 font-extrabold">Rent 10x Faster Online</span>
                        </h2>
                        <p class="text-[11px] text-white/90 line-clamp-1 leading-relaxed">
                            Connect directly with 10,000+ verified tenants with zero middleman commission.
                        </p>
                    </div>
                    <div class="flex items-center justify-between">
                        <a href="{{ route('properties.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-white text-indigo-950 font-black text-[11px] shadow-lg active:scale-95 transition-all">
                            <span>Post Property Now</span>
                            <i class="ph-bold ph-arrow-right text-indigo-600 text-xs"></i>
                        </a>
                        <span class="text-[10px] font-extrabold text-white bg-black/30 px-2.5 py-1 rounded-lg">Zero Brokerage</span>
                    </div>
                </div>

            </div>
        </div>

        {{-- Slider Dot Indicators --}}
        <div class="mobile-slider-indicators" id="mobileSliderDots">
            <button type="button" class="mobile-slider-dot active" onclick="goMobileSlide(0)" aria-label="Slide 1"></button>
            <button type="button" class="mobile-slider-dot" onclick="goMobileSlide(1)" aria-label="Slide 2"></button>
            <button type="button" class="mobile-slider-dot" onclick="goMobileSlide(2)" aria-label="Slide 3"></button>
            <button type="button" class="mobile-slider-dot" onclick="goMobileSlide(3)" aria-label="Slide 4"></button>
        </div>

        {{-- 5. Flipkart Style Quick Filter Chips --}}
        <div class="mb-3">
            <div class="flipkart-quick-filters no-scrollbar">
                <a href="{{ route('properties.index') }}" class="flipkart-filter-chip active">
                    <i class="ph-bold ph-squares-four text-xs text-blue-400"></i> All
                </a>
                <a href="{{ route('properties.index', ['purpose' => 'rent']) }}" class="flipkart-filter-chip">
                    <i class="ph-bold ph-key text-xs text-emerald-400"></i> For Rent
                </a>
                <a href="{{ route('properties.index', ['purpose' => 'buy']) }}" class="flipkart-filter-chip">
                    <i class="ph-bold ph-house text-xs text-purple-400"></i> For Buy
                </a>
                <a href="{{ route('properties.index', ['type' => 'pg-hostel']) }}" class="flipkart-filter-chip">
                    <i class="ph-bold ph-bed text-xs text-amber-400"></i> PG / Stays
                </a>
                <a href="{{ route('properties.index', ['price' => '0-20000']) }}" class="flipkart-filter-chip">
                    <i class="ph-bold ph-currency-inr text-xs text-sky-400"></i> Under ₹20k
                </a>
                <button type="button" onclick="searchNearMe()" class="flipkart-filter-chip">
                    <i class="ph-fill ph-navigation-arrow text-xs text-cyan-400"></i> Near Me
                </button>
                <button type="button" onclick="toggleMobileFiltersDropdown()" class="flipkart-filter-chip bg-blue-50 border-blue-200 text-blue-700 font-bold">
                    <i class="ph-bold ph-faders text-xs"></i> More Filters
                </button>
            </div>
        </div>

        {{-- 6. Flipkart Style Trust & Assurance Ribbon --}}
        <div class="py-2.5 px-3 rounded-2xl bg-white border border-slate-200 shadow-xs" style="display: flex; align-items: center; justify-content: space-around; width: 100%; box-sizing: border-box;">
            <div style="display: flex; align-items: center; justify-content: center; gap: 5px;">
                <i class="ph-fill ph-check-circle text-emerald-600 text-sm"></i>
                <span class="text-[11px] font-bold text-slate-800" style="white-space: nowrap;">Zero Brokerage</span>
            </div>
            <div style="width: 1px; height: 16px; background: #e2e8f0; flex-shrink: 0;"></div>
            <div style="display: flex; align-items: center; justify-content: center; gap: 5px;">
                <i class="ph-fill ph-shield-check text-blue-600 text-sm"></i>
                <span class="text-[11px] font-bold text-slate-800" style="white-space: nowrap;">100% Verified</span>
            </div>
            <div style="width: 1px; height: 16px; background: #e2e8f0; flex-shrink: 0;"></div>
            <div style="display: flex; align-items: center; justify-content: center; gap: 5px;">
                <i class="ph-fill ph-whatsapp-logo text-emerald-600 text-sm"></i>
                <span class="text-[11px] font-bold text-slate-800" style="white-space: nowrap;">Direct Contact</span>
            </div>
        </div>

    </section>

    {{-- Flipkart Style Mobile City / Location Selection Modal --}}
    <div id="mobileCityModal" class="fixed inset-0 z-[99999] hidden items-end sm:items-center justify-center bg-slate-950/40 backdrop-blur-sm transition-opacity duration-300 p-0 sm:p-4" onclick="if(event.target === this) closeMobileCityModal()">
        <div class="w-full sm:max-w-md bg-white rounded-t-3xl sm:rounded-2xl p-5 shadow-2xl border border-slate-200 max-h-[88vh] overflow-y-auto" onclick="event.stopPropagation()">
            
            {{-- Modal Header --}}
            <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm shadow-xs">
                        <i class="ph-fill ph-map-pin"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-900 leading-tight">Select Location</h3>
                        <p class="text-[11px] text-slate-500 font-medium">Browse direct owner rental homes</p>
                    </div>
                </div>
                <button type="button" onclick="closeMobileCityModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center active:scale-95 transition-all" aria-label="Close">
                    <i class="ph-bold ph-x text-sm"></i>
                </button>
            </div>

            {{-- Optional Notice Banner (e.g. when GPS is denied) --}}
            <div id="modalGpsNotice" class="hidden mb-3 p-2.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-xs font-semibold flex items-center gap-2">
                <i class="ph-fill ph-warning-circle text-amber-600 text-sm flex-shrink-0"></i>
                <span id="modalGpsNoticeText" class="flex-1">Location permission is turned off. Please pick your city below:</span>
            </div>

            {{-- 1-Click GPS Detect Location Row --}}
            <button type="button" 
                    id="modalGpsDetectBtn"
                    onclick="searchNearMe()" 
                    class="w-full mb-3.5 p-3 rounded-2xl bg-blue-50/80 hover:bg-blue-100 border border-blue-200 text-left flex items-center justify-between group active:scale-[0.99] transition-all">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center text-base shadow-sm">
                        <i class="ph-fill ph-navigation-arrow"></i>
                    </div>
                    <div>
                        <span class="block text-xs font-black text-blue-900">Use Current Location</span>
                        <span class="block text-[10px] text-blue-700 font-medium">Auto-detect area via device GPS</span>
                    </div>
                </div>
                <i class="ph-bold ph-caret-right text-blue-500 text-xs group-hover:translate-x-0.5 transition-transform"></i>
            </button>

            {{-- Popular Cities Section Header --}}
            <div class="flex items-center justify-between mb-2 px-0.5">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Popular Cities</span>
                <span class="text-[10px] font-bold text-slate-400">Zero Brokerage</span>
            </div>

            {{-- Popular Cities Grid --}}
            <div class="grid grid-cols-2 gap-2 mb-3.5">
                @php
                    $mobileCityList = [
                        ['name' => 'Gurugram', 'label' => 'Gurugram (Gurgaon)', 'tag' => 'Popular'],
                        ['name' => 'New Delhi', 'label' => 'Delhi NCR', 'tag' => 'Top Search'],
                        ['name' => 'Noida', 'label' => 'Noida', 'tag' => null],
                        ['name' => 'Faridabad', 'label' => 'Faridabad', 'tag' => null],
                        ['name' => 'Ghaziabad', 'label' => 'Ghaziabad', 'tag' => null],
                        ['name' => 'Bengaluru', 'label' => 'Bengaluru', 'tag' => 'Tech Hub'],
                        ['name' => 'Mumbai', 'label' => 'Mumbai', 'tag' => null],
                        ['name' => 'Pune', 'label' => 'Pune', 'tag' => null],
                        ['name' => 'Hyderabad', 'label' => 'Hyderabad', 'tag' => null],
                        ['name' => 'Jaipur', 'label' => 'Jaipur', 'tag' => null],
                        ['name' => 'Chandigarh', 'label' => 'Chandigarh', 'tag' => null],
                    ];
                    $selectedDistrict = request('district');
                @endphp

                @foreach($mobileCityList as $c)
                    <button type="button" 
                            onclick="selectMobileCity('{{ $c['name'] }}')" 
                            class="p-2.5 rounded-xl border text-left flex items-center justify-between transition-all active:scale-[0.98] {{ $selectedDistrict === $c['name'] ? 'bg-blue-50/90 border-blue-500 ring-1 ring-blue-500 text-blue-900 shadow-xs' : 'bg-slate-50/80 hover:bg-slate-100 border-slate-200 text-slate-800' }}">
                        <div class="min-w-0 pr-1">
                            <span class="block text-xs font-bold truncate">{{ $c['label'] }}</span>
                            @if($c['tag'])
                                <span class="text-[9px] font-extrabold text-blue-600 uppercase">{{ $c['tag'] }}</span>
                            @endif
                        </div>
                        @if($selectedDistrict === $c['name'])
                            <i class="ph-fill ph-check-circle text-blue-600 text-sm flex-shrink-0"></i>
                        @else
                            <i class="ph ph-map-pin text-slate-400 text-xs flex-shrink-0"></i>
                        @endif
                    </button>
                @endforeach
            </div>

            {{-- All Cities Option --}}
            <button type="button" 
                    onclick="selectMobileCity('')" 
                    class="w-full py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold text-center active:scale-[0.98] transition-all">
                All Cities (Clear Location Filter)
            </button>
        </div>
    </div>

    <!-- Premium Promo Slider Section (Desktop only to prevent duplicate sliders on mobile) -->
    <div class="promo-slider-container hidden lg:block">
        <div class="promo-slider" id="promo-slider">
            <!-- Slides Wrapper -->
            <div class="promo-slides-wrapper" id="promo-slides-wrapper">
                
                <!-- Slide 1: Zero Brokerage -->
                <div class="promo-slide slide-zero-brokerage">
                    <div class="promo-slide-content">
                        <span class="promo-badge">Special Offer</span>
                        <h2 class="promo-title">Zero Brokerage Home Rentals</h2>
                        <p class="promo-desc">Browse thousands of 100% verified flats and independent villas across India. Contact owners directly.</p>
                        <a href="{{ route('properties.index', ['purpose' => 'rent']) }}" class="promo-btn" title="Explore Rentals">Explore Rentals</a>
                    </div>
                    <div class="promo-image-side">
                        <div class="promo-gradient-overlay"></div>
                        <img src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=800&q=80" alt="Zero Brokerage Rentals" title="Zero Brokerage Rentals" width="600" height="330" loading="lazy" decoding="async">
                    </div>
                </div>

                <!-- Slide 2: Premium PG -->
                <div class="promo-slide slide-pg-stays">
                    <div class="promo-slide-content">
                        <span class="promo-badge">Popular Category</span>
                        <h2 class="promo-title">Premium PG & Co-Living Stays</h2>
                        <p class="promo-desc">Fully furnished, high-speed Wi-Fi, daily housekeeping, and home-style food. Perfect for students and professionals.</p>
                        <a href="{{ route('properties.index', ['type' => 'pg-hostel']) }}" class="promo-btn" title="Explore PG Stays">Explore PG Stays</a>
                    </div>
                    <div class="promo-image-side">
                        <div class="promo-gradient-overlay"></div>
                        <img src="https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?auto=format&fit=crop&w=800&q=80" alt="Premium PG Stays" title="Premium PG Stays" width="600" height="330" loading="lazy" decoding="async">
                    </div>
                </div>

                <!-- Slide 3: Commercial Spaces -->
                <div class="promo-slide slide-commercial">
                    <div class="promo-slide-content">
                        <span class="promo-badge">Business Class</span>
                        <h2 class="promo-title">Modern Commercial Spaces</h2>
                        <p class="promo-desc">Find high-footfall retail shops, showrooms, and fully managed offices. Zero brokerage, maximum growth.</p>
                        <a href="{{ route('properties.index', ['type' => 'commercial']) }}" class="promo-btn" title="View Commercial">View Commercial</a>
                    </div>
                    <div class="promo-image-side">
                        <div class="promo-gradient-overlay"></div>
                        <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=800&q=80" alt="Commercial Properties" title="Commercial Properties" width="600" height="330" loading="lazy" decoding="async">
                    </div>
                </div>

            </div>

            <!-- Navigation Controls -->
            <button class="promo-arrow promo-arrow-prev" id="promo-prev" aria-label="Previous Offer">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
            </button>
            <button class="promo-arrow promo-arrow-next" id="promo-next" aria-label="Next Offer">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </button>

            <!-- Dot Indicators -->
            <div class="promo-dots">
                <button class="promo-dot active" data-slide-index="0" aria-label="Slide 1"></button>
                <button class="promo-dot" data-slide-index="1" aria-label="Slide 2"></button>
                <button class="promo-dot" data-slide-index="2" aria-label="Slide 3"></button>
            </div>
        </div>
    </div>

    <!-- Featured Rentals -->
    <section class="premium-section featured-rentals" id="featured-rentals-section">
        <div class="section-container">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
                <div>
                    <h2 class="section-title text-left mb-1">
                        @if(request()->anyFilled(['state', 'district', 'locality', 'type', 'price', 'rooms', 'purpose']))
                            Search <span class="text-gradient">Results</span>
                            <a href="{{ route('home') }}" style="font-size: 14px; color: var(--primary); font-weight: 600; text-decoration: none; margin-left: 15px; border-bottom: 1px solid var(--primary);" title="Clear Filters">Clear Filters</a>
                        @else
                            Discover <span class="text-gradient">Premium</span> Rentals
                        @endif
                    </h2>
                    <p class="section-subtitle text-left m-0">
                        @if(request()->anyFilled(['state', 'district', 'locality', 'type', 'price', 'rooms', 'purpose']))
                            Showing <strong>{{ $featuredRentals->count() }}</strong> {{ Str::plural('property', $featuredRentals->count()) }} matching your criteria.
                        @else
                            Handpicked luxury spaces from our top-rated customers and property owners.
                        @endif
                    </p>
                </div>

                <!-- Clean Standard Custom Dropdown -->
                <div class="flex items-center gap-2.5 flex-shrink-0 self-start md:self-auto">
                    <div class="relative" id="featuredCustomDropdown">
                        <button type="button" 
                                id="featuredDropdownBtn" 
                                onclick="toggleFeaturedDropdown(event)"
                                class="flex items-center justify-between gap-3 px-3.5 py-2.5 bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 rounded-xl text-xs font-bold text-slate-800 dark:text-slate-100 shadow-xs hover:border-slate-300 dark:hover:border-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-600/20 transition-all min-w-[220px]">
                            <span class="flex items-center gap-2 truncate" id="featuredSelectedDisplay">
                                <i class="ph-bold ph-sparkle text-blue-600 text-sm" id="featuredSelectedIcon"></i>
                                <span id="featuredSelectedLabel">Default (Featured)</span>
                            </span>
                            <i class="ph-bold ph-caret-down text-slate-400 text-xs transition-transform duration-200 flex-shrink-0" id="featuredDropdownChevron"></i>
                        </button>

                        <!-- Floating Clean Dropdown Menu -->
                        <div id="featuredDropdownMenu" 
                             class="hidden absolute right-0 top-full mt-1.5 w-60 bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 rounded-2xl shadow-xl z-50 py-1.5 backdrop-blur-xl overflow-hidden">
                            
                            <!-- Sort Group -->
                            <div class="px-3.5 py-1 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                Sort Order
                            </div>
                            <button type="button" class="featured-menu-item active" data-value="featured" data-icon="ph-bold ph-sparkle text-blue-600" data-label="Default (Featured)" onclick="selectFeaturedOption('featured')">
                                <span class="flex items-center gap-2.5">
                                    <i class="ph-bold ph-sparkle text-blue-600 text-sm"></i>
                                    <span>Default (Featured)</span>
                                </span>
                                <i class="ph-bold ph-check text-blue-600 checkmark"></i>
                            </button>
                            <button type="button" class="featured-menu-item" data-value="new_to_old" data-icon="ph-bold ph-clock text-slate-600" data-label="New to Old" onclick="selectFeaturedOption('new_to_old')">
                                <span class="flex items-center gap-2.5">
                                    <i class="ph-bold ph-clock text-slate-600 dark:text-slate-300 text-sm"></i>
                                    <span>New to Old</span>
                                </span>
                                <i class="ph-bold ph-check text-blue-600 checkmark hidden"></i>
                            </button>
                            <button type="button" class="featured-menu-item" data-value="old_to_new" data-icon="ph-bold ph-clock-counter-clockwise text-slate-600" data-label="Old to New" onclick="selectFeaturedOption('old_to_new')">
                                <span class="flex items-center gap-2.5">
                                    <i class="ph-bold ph-clock-counter-clockwise text-slate-600 dark:text-slate-300 text-sm"></i>
                                    <span>Old to New</span>
                                </span>
                                <i class="ph-bold ph-check text-blue-600 checkmark hidden"></i>
                            </button>
                            <button type="button" class="featured-menu-item" data-value="price_low" data-icon="ph-bold ph-arrow-up-right text-emerald-600" data-label="Price: Low to High" onclick="selectFeaturedOption('price_low')">
                                <span class="flex items-center gap-2.5">
                                    <i class="ph-bold ph-arrow-up-right text-emerald-600 text-sm"></i>
                                    <span>Price: Low to High</span>
                                </span>
                                <i class="ph-bold ph-check text-blue-600 checkmark hidden"></i>
                            </button>
                            <button type="button" class="featured-menu-item" data-value="price_high" data-icon="ph-bold ph-arrow-down-right text-amber-600" data-label="Price: High to Low" onclick="selectFeaturedOption('price_high')">
                                <span class="flex items-center gap-2.5">
                                    <i class="ph-bold ph-arrow-down-right text-amber-600 text-sm"></i>
                                    <span>Price: High to Low</span>
                                </span>
                                <i class="ph-bold ph-check text-blue-600 checkmark hidden"></i>
                            </button>

                            <!-- Filter Group -->
                            <div class="px-3.5 py-1 mt-1 pt-1.5 border-t border-slate-100 dark:border-slate-800 text-[10px] font-extrabold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                Filter by
                            </div>
                            <button type="button" class="featured-menu-item" data-value="unbooked" data-icon="ph-bold ph-lock-key-open text-emerald-500" data-label="Unbooked Only" onclick="selectFeaturedOption('unbooked')">
                                <span class="flex items-center gap-2.5">
                                    <i class="ph-bold ph-lock-key-open text-emerald-500 text-sm"></i>
                                    <span>Unbooked Only</span>
                                </span>
                                <i class="ph-bold ph-check text-blue-600 checkmark hidden"></i>
                            </button>
                            <button type="button" class="featured-menu-item" data-value="video" data-icon="ph-bold ph-video-camera text-purple-500" data-label="With Video Tour" onclick="selectFeaturedOption('video')">
                                <span class="flex items-center gap-2.5">
                                    <i class="ph-bold ph-video-camera text-purple-500 text-sm"></i>
                                    <span>With Video Tour</span>
                                </span>
                                <i class="ph-bold ph-check text-blue-600 checkmark hidden"></i>
                            </button>
                            <button type="button" class="featured-menu-item" data-value="images" data-icon="ph-bold ph-image text-blue-500" data-label="With Photos" onclick="selectFeaturedOption('images')">
                                <span class="flex items-center gap-2.5">
                                    <i class="ph-bold ph-image text-blue-500 text-sm"></i>
                                    <span>With Photos</span>
                                </span>
                                <i class="ph-bold ph-check text-blue-600 checkmark hidden"></i>
                            </button>
                            <button type="button" class="featured-menu-item" data-value="unbooked_first" data-icon="ph-bold ph-check-circle text-teal-500" data-label="Unbooked First" onclick="selectFeaturedOption('unbooked_first')">
                                <span class="flex items-center gap-2.5">
                                    <i class="ph-bold ph-check-circle text-teal-500 text-sm"></i>
                                    <span>Unbooked First</span>
                                </span>
                                <i class="ph-bold ph-check text-blue-600 checkmark hidden"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 md:gap-6" id="featuredPropertiesGrid">
                @forelse($featuredRentals as $property)
                    <x-property-card :property="$property" />
                @empty
                    <div class="col-span-full py-16 flex flex-col items-center justify-center text-center">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="1.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        <h3 class="text-xl font-bold mt-4 text-zinc-900 dark:text-white">No properties found</h3>
                        <p class="text-zinc-500 mt-2">Try adjusting your filters or <a href="{{ route('home') }}" style="color:var(--primary);font-weight:600;" title="clear all filters">clear all filters</a></p>
                    </div>
                @endforelse

                {{-- Client-side Filter Empty State --}}
                <div id="featuredEmptyFilterState" class="hidden col-span-full py-14 flex-col items-center justify-center text-center bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-dashed border-slate-200 dark:border-slate-800 p-6">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 flex items-center justify-center mb-3">
                        <i class="ph-bold ph-magnifying-glass text-2xl"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-800 dark:text-slate-200 mb-1">No matching properties with current selection</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mb-4 max-w-sm">Try choosing another option from the menu to view available listings.</p>
                    <button type="button" onclick="selectFeaturedOption('featured')" class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-xs transition-all">
                        <i class="ph-bold ph-arrow-counter-clockwise"></i> Show All Properties
                    </button>
                </div>
            </div>
            
            <div style="text-align: center; margin-top: 50px;">
                <a href="{{ route('properties.index') }}" class="btn-explore-premium" title="Explore All Rentals">
                    <span>Explore All Rentals</span>
                    <i class="ph ph-bold ph-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Custom Dropdown Styling & Handler Script -->
    <style>
        .featured-menu-item {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 14px;
            font-size: 12px;
            font-weight: 600;
            color: #334155;
            background: transparent;
            border: none;
            cursor: pointer;
            transition: all 0.15s ease;
            text-align: left;
        }
        .dark .featured-menu-item {
            color: #cbd5e1;
        }
        .featured-menu-item:hover {
            background: #f1f5f9;
            color: #0f172a;
        }
        .dark .featured-menu-item:hover {
            background: rgba(51, 65, 85, 0.6);
            color: #f8fafc;
        }
        .featured-menu-item.active {
            background: #eff6ff;
            color: #2563eb;
            font-weight: 700;
        }
        .dark .featured-menu-item.active {
            background: rgba(37, 99, 235, 0.15);
            color: #60a5fa;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const grid = document.getElementById('featuredPropertiesGrid');
            if (!grid) return;

            const dropdownBtn = document.getElementById('featuredDropdownBtn');
            const dropdownMenu = document.getElementById('featuredDropdownMenu');
            const dropdownChevron = document.getElementById('featuredDropdownChevron');
            const selectedIcon = document.getElementById('featuredSelectedIcon');
            const selectedLabel = document.getElementById('featuredSelectedLabel');
            const cards = Array.from(grid.querySelectorAll('[data-property-card="true"]'));
            const emptyState = document.getElementById('featuredEmptyFilterState');

            window.toggleFeaturedDropdown = function(event) {
                if (event) event.stopPropagation();
                if (!dropdownMenu) return;
                const isOpen = !dropdownMenu.classList.contains('hidden');
                if (isOpen) {
                    closeFeaturedDropdown();
                } else {
                    dropdownMenu.classList.remove('hidden');
                    if (dropdownChevron) dropdownChevron.style.transform = 'rotate(180deg)';
                }
            };

            function closeFeaturedDropdown() {
                if (!dropdownMenu) return;
                dropdownMenu.classList.add('hidden');
                if (dropdownChevron) dropdownChevron.style.transform = 'rotate(0deg)';
            }

            // Close on outside click
            document.addEventListener('click', function(e) {
                const container = document.getElementById('featuredCustomDropdown');
                if (container && !container.contains(e.target)) {
                    closeFeaturedDropdown();
                }
            });

            // Close on ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeFeaturedDropdown();
                }
            });

            window.selectFeaturedOption = function(val) {
                closeFeaturedDropdown();

                const items = document.querySelectorAll('.featured-menu-item');
                items.forEach(item => {
                    const isSelected = (item.getAttribute('data-value') === val);
                    item.classList.toggle('active', isSelected);
                    const check = item.querySelector('.checkmark');
                    if (check) {
                        check.classList.toggle('hidden', !isSelected);
                    }
                    if (isSelected) {
                        const iconClass = item.getAttribute('data-icon') || 'ph-bold ph-sparkle text-blue-600';
                        const labelText = item.getAttribute('data-label') || 'Default (Featured)';
                        if (selectedIcon) selectedIcon.className = iconClass + ' text-sm';
                        if (selectedLabel) selectedLabel.textContent = labelText;
                    }
                });

                applyFeaturedSortAndFilter(val);
            };

            function applyFeaturedSortAndFilter(selectedVal) {
                let filterType = 'all';
                let sortType = 'featured';

                switch (selectedVal) {
                    case 'new_to_old':
                    case 'newest':
                        filterType = 'all';
                        sortType = 'new_to_old';
                        break;
                    case 'old_to_new':
                    case 'oldest':
                        filterType = 'all';
                        sortType = 'old_to_new';
                        break;
                    case 'unbooked':
                        filterType = 'unbooked';
                        sortType = 'new_to_old';
                        break;
                    case 'price_low':
                        filterType = 'all';
                        sortType = 'price_low';
                        break;
                    case 'price_high':
                        filterType = 'all';
                        sortType = 'price_high';
                        break;
                    case 'video':
                        filterType = 'video';
                        sortType = 'featured';
                        break;
                    case 'images':
                        filterType = 'images';
                        sortType = 'featured';
                        break;
                    case 'unbooked_first':
                        filterType = 'all';
                        sortType = 'unbooked_first';
                        break;
                    case 'featured':
                    default:
                        filterType = 'all';
                        sortType = 'featured';
                        break;
                }

                // 1. Filter cards
                let visibleCards = [];
                cards.forEach(card => {
                    const isBooked = card.getAttribute('data-is-booked') === '1';
                    const hasImage = card.getAttribute('data-has-image') === '1';
                    const hasVideo = card.getAttribute('data-has-video') === '1';

                    let matches = true;
                    if (filterType === 'unbooked' && isBooked) matches = false;
                    if (filterType === 'images' && !hasImage) matches = false;
                    if (filterType === 'video' && !hasVideo) matches = false;

                    if (matches) {
                        card.style.display = '';
                        visibleCards.push(card);
                    } else {
                        card.style.display = 'none';
                    }
                });

                // 2. Sort cards
                visibleCards.sort((a, b) => {
                    const priceA = parseFloat(a.getAttribute('data-price')) || 0;
                    const priceB = parseFloat(b.getAttribute('data-price')) || 0;
                    const bookedA = a.getAttribute('data-is-booked') === '1' ? 1 : 0;
                    const bookedB = b.getAttribute('data-is-booked') === '1' ? 1 : 0;
                    const featuredA = a.getAttribute('data-is-featured') === '1' ? 1 : 0;
                    const featuredB = b.getAttribute('data-is-featured') === '1' ? 1 : 0;
                    const dateA = parseInt(a.getAttribute('data-created-at')) || 0;
                    const dateB = parseInt(b.getAttribute('data-created-at')) || 0;

                    switch (sortType) {
                        case 'price_low':
                            return priceA - priceB;
                        case 'price_high':
                            return priceB - priceA;
                        case 'unbooked_first':
                            if (bookedA !== bookedB) return bookedA - bookedB;
                            return dateB - dateA;
                        case 'new_to_old':
                        case 'newest':
                            return dateB - dateA;
                        case 'old_to_new':
                        case 'oldest':
                            return dateA - dateB;
                        case 'featured':
                        default:
                            if (featuredA !== featuredB) return featuredB - featuredA;
                            return dateB - dateA;
                    }
                });

                // 3. Re-append in sorted order
                visibleCards.forEach(card => grid.appendChild(card));

                // 4. Empty state toggle
                if (emptyState) {
                    if (visibleCards.length === 0 && cards.length > 0) {
                        emptyState.classList.remove('hidden');
                        emptyState.style.display = 'flex';
                    } else {
                        emptyState.classList.add('hidden');
                        emptyState.style.display = 'none';
                    }
                }
            }

            // Initialize from URL params if present
            const urlParams = new URLSearchParams(window.location.search);
            let initialVal = 'featured';
            if (urlParams.get('sort') === 'price_low') initialVal = 'price_low';
            else if (urlParams.get('sort') === 'price_high') initialVal = 'price_high';
            else if (urlParams.get('sort') === 'oldest' || urlParams.get('sort') === 'old_to_new') initialVal = 'old_to_new';
            else if (urlParams.get('sort') === 'newest' || urlParams.get('sort') === 'new_to_old') initialVal = 'new_to_old';
            else if (urlParams.get('unbooked') === '1' || urlParams.get('availability') === 'unbooked') initialVal = 'unbooked';
            else if (urlParams.get('media') === 'video') initialVal = 'video';
            else if (urlParams.get('media') === 'images' || urlParams.get('media') === 'image') initialVal = 'images';

            if (initialVal !== 'featured') {
                selectFeaturedOption(initialVal);
            }
        });
    </script>

    {{-- Why Choose Us Section --}}
    @include('components.why-choose-us')

    {{-- How It Works Section --}}
    @include('components.how-it-works')

    {{-- Pricing Plans Section --}}
    @include('components.pricing-plans')

    {{-- App Download Section --}}
    @include('components.app-download')

    <!-- Testimonials / Success Stories -->
    <section class="premium-section success-stories">
        <div class="section-container">
            <h2 class="section-title">Trusted by <span class="text-gradient">Thousands</span></h2>
            <p class="section-subtitle">Real experiences from customers who found their perfect rental spaces.</p>
            
            @php
                $displayTestimonials = [];
                
                // 1. Add approved database feedbacks
                if (isset($feedbacks) && $feedbacks->count() > 0) {
                    foreach ($feedbacks as $fb) {
                        $displayTestimonials[] = [
                            'stars' => $fb->rating,
                            'quote' => '"' . ($fb->comment ?: 'No comment provided.') . '"',
                            'author' => $fb->user->name ?? 'Guest User',
                            'role' => $fb->user ? ucfirst($fb->user->role) : 'Verified Customer',
                            'image' => $fb->user && $fb->user->role === 'landlord' 
                                ? 'https://randomuser.me/api/portraits/women/68.jpg' 
                                : 'https://randomuser.me/api/portraits/men/' . (($fb->id % 50) + 1) . '.jpg',
                        ];
                    }
                }
                
                // 2. Add fallback testimonials from settings if we need more to reach 3
                if (count($displayTestimonials) < 3) {
                    $fallbacks = [
                        [
                            'stars' => $site_settings['testimonial_1_stars'] ?? 5,
                            'quote' => '"' . ($site_settings['testimonial_1_quote'] ?? "UnlockRentals made finding our company's new office space in Cyber City incredibly seamless. The verified listings and sleek UI saved us weeks of searching.") . '"',
                            'author' => $site_settings['testimonial_1_author'] ?? 'Rahul S.',
                            'role' => $site_settings['testimonial_1_role'] ?? 'CEO, TechFlow India',
                            'image' => $site_settings['testimonial_1_image'] ?? 'https://randomuser.me/api/portraits/men/43.jpg',
                        ],
                        [
                            'stars' => $site_settings['testimonial_2_stars'] ?? 5,
                            'quote' => '"' . ($site_settings['testimonial_2_quote'] ?? "I listed my luxury villa in Assagao and within 48 hours I had a verified, high-quality tenant. The platform's concierge support is world-class.") . '"',
                            'author' => $site_settings['testimonial_2_author'] ?? 'Priya D.',
                            'role' => $site_settings['testimonial_2_role'] ?? 'Property Owner',
                            'image' => $site_settings['testimonial_2_image'] ?? 'https://randomuser.me/api/portraits/women/68.jpg',
                        ],
                        [
                            'stars' => $site_settings['testimonial_3_stars'] ?? 5,
                            'quote' => '"' . ($site_settings['testimonial_3_quote'] ?? "The filtering is incredibly smart. We found a beautiful apartment that checked off all our boxes in South Mumbai without dealing with broker spam.") . '"',
                            'author' => $site_settings['testimonial_3_author'] ?? 'Aditya P.',
                            'role' => $site_settings['testimonial_3_role'] ?? 'Renter',
                            'image' => $site_settings['testimonial_3_image'] ?? 'https://randomuser.me/api/portraits/men/57.jpg',
                        ]
                    ];
                    
                    $needed = 3 - count($displayTestimonials);
                    for ($i = 0; $i < $needed; $i++) {
                        $fallbackIndex = 3 - $needed + $i;
                        if (isset($fallbacks[$fallbackIndex])) {
                            $displayTestimonials[] = $fallbacks[$fallbackIndex];
                        }
                    }
                }
            @endphp

            @php
                $marqueeCards = array_merge($displayTestimonials, $displayTestimonials);
            @endphp

            <style>
                .ur-testimonials-marquee-container {
                    display: flex;
                    overflow: hidden;
                    user-select: none;
                    gap: 1rem;
                    position: relative;
                    width: 100%;
                    padding: 0.5rem 0 1rem;
                    -webkit-mask-image: linear-gradient(to right, transparent, black 3%, black 97%, transparent);
                    mask-image: linear-gradient(to right, transparent, black 3%, black 97%, transparent);
                }

                .ur-testimonials-marquee-content {
                    flex-shrink: 0;
                    display: flex;
                    align-items: stretch;
                    gap: 1rem;
                    min-width: 100%;
                    animation: urMarqueeScroll 28s linear infinite;
                    will-change: transform;
                }

                @keyframes urMarqueeScroll {
                    0% {
                        transform: translateX(0);
                    }
                    100% {
                        transform: translateX(calc(-100% - 1rem));
                    }
                }

                .ur-testimonials-marquee-container:hover .ur-testimonials-marquee-content,
                .ur-testimonials-marquee-container:active .ur-testimonials-marquee-content,
                .ur-testimonials-marquee-container.is-paused .ur-testimonials-marquee-content {
                    animation-play-state: paused;
                }

                @media (max-width: 640px) {
                    .ur-testimonials-marquee-container {
                        gap: 0.75rem;
                    }
                    .ur-testimonials-marquee-content {
                        gap: 0.75rem;
                        animation-duration: 20s;
                    }
                    @keyframes urMarqueeScroll {
                        0% {
                            transform: translateX(0);
                        }
                        100% {
                            transform: translateX(calc(-100% - 0.75rem));
                        }
                    }
                }
            </style>

            {{-- Testimonial Marquee (Infinite Auto-Scroll Right to Left with Pause on Touch) --}}
            <div class="ur-testimonials-marquee-container" id="testimonialsMarquee" aria-label="Customer Reviews">
                {{-- Track 1 --}}
                <div class="ur-testimonials-marquee-content">
                    @foreach($marqueeCards as $t)
                        <div class="w-[270px] sm:w-[310px] shrink-0 p-4 sm:p-5 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl sm:rounded-3xl shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                            <div>
                                <div class="text-amber-400 text-sm sm:text-base mb-2 tracking-wide flex items-center">
                                    @for($i = 0; $i < $t['stars']; $i++)★@endfor
                                </div>
                                <p class="text-xs sm:text-[13px] text-slate-600 dark:text-slate-300 italic line-clamp-3 mb-4 leading-relaxed">
                                    {{ $t['quote'] }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2.5 pt-3 border-t border-slate-100 dark:border-slate-800 mt-auto">
                                <img src="{{ $t['image'] }}" alt="{{ $t['author'] }}" title="{{ $t['author'] }}"
                                     width="36" height="36" loading="lazy" decoding="async"
                                     onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($t['author']) }}&background=2563EB&color=fff&rounded=true&bold=true';"
                                     class="w-9 h-9 rounded-full object-cover shrink-0 ring-2 ring-blue-500/20 shadow-xs">
                                <div class="min-w-0">
                                    <h3 class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white truncate">{{ $t['author'] }}</h3>
                                    <span class="text-[11px] text-blue-600 dark:text-blue-400 font-semibold block truncate">{{ $t['role'] }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Track 2 (Duplicate for Seamless Infinite Loop) --}}
                <div class="ur-testimonials-marquee-content" aria-hidden="true">
                    @foreach($marqueeCards as $t)
                        <div class="w-[270px] sm:w-[310px] shrink-0 p-4 sm:p-5 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl sm:rounded-3xl shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                            <div>
                                <div class="text-amber-400 text-sm sm:text-base mb-2 tracking-wide flex items-center">
                                    @for($i = 0; $i < $t['stars']; $i++)★@endfor
                                </div>
                                <p class="text-xs sm:text-[13px] text-slate-600 dark:text-slate-300 italic line-clamp-3 mb-4 leading-relaxed">
                                    {{ $t['quote'] }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2.5 pt-3 border-t border-slate-100 dark:border-slate-800 mt-auto">
                                <img src="{{ $t['image'] }}" alt="{{ $t['author'] }}" title="{{ $t['author'] }}"
                                     width="36" height="36" loading="lazy" decoding="async"
                                     onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($t['author']) }}&background=2563EB&color=fff&rounded=true&bold=true';"
                                     class="w-9 h-9 rounded-full object-cover shrink-0 ring-2 ring-blue-500/20 shadow-xs">
                                <div class="min-w-0">
                                    <h3 class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white truncate">{{ $t['author'] }}</h3>
                                    <span class="text-[11px] text-blue-600 dark:text-blue-400 font-semibold block truncate">{{ $t['role'] }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <script>
                (function() {
                    const marquee = document.getElementById('testimonialsMarquee');
                    if (marquee) {
                        marquee.addEventListener('touchstart', function() {
                            marquee.classList.add('is-paused');
                        }, { passive: true });
                        marquee.addEventListener('touchend', function() {
                            setTimeout(function() {
                                marquee.classList.remove('is-paused');
                            }, 1200);
                        }, { passive: true });
                    }
                })();
            </script>
        </div>
    </section>

    {{-- Resource Directory --}}
    @include('components.resource-directory')

    {{-- Blog & Guides Section --}}
    @include('components.home-blog')

    {{-- Property Links Bar --}}
    @include('components.property-links-bar')

    <div class="hidden md:block">
        <x-footer />
    </div>


    {{-- Floating AI Support Chatbot --}}
    @include('components.chatbot')
    
    <script>
        // Clean empty / default parameters on hero search submit so the URL stays clean
        window.handleHeroSearchSubmit = function(form) {
            if (!form) return true;
            const elements = form.querySelectorAll('input, select');
            elements.forEach(el => {
                const val = (el.value || '').trim();
                if (!val || val === 'all' || val === 'any') {
                    el.disabled = true;
                }
            });
            setTimeout(() => {
                elements.forEach(el => el.disabled = false);
            }, 1000);
            return true;
        };

        // Global Pill Toggle Handler for Layout & Intent
        window.setPill = function(btn, value, inputId) {
            if (!btn) return;
            const input = document.getElementById(inputId);
            if (input) {
                input.value = value;
                input.dispatchEvent(new Event('change', { bubbles: true }));
            }
            const group = btn.closest('.pill-group');
            if (group) {
                group.querySelectorAll('.pill-btn').forEach(b => b.classList.remove('active'));
            }
            btn.classList.add('active');
        };

        // Global Event Delegation for Pill Buttons
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.pill-btn');
            if (!btn) return;
            const group = btn.closest('.pill-group');
            if (!group) return;
            const hiddenInput = group.querySelector('input[type="hidden"]');
            const val = btn.getAttribute('data-value') || btn.textContent.trim().toLowerCase();
            
            group.querySelectorAll('.pill-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            
            if (hiddenInput) {
                hiddenInput.value = btn.dataset.value || val;
                hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            // Cascading Dropdowns for Home Search (Runs First & Protected)
            try {
                if (typeof window.initLocationCascading === 'function') {
                    window.initLocationCascading({
                        stateId: 'state-select',
                        cityId: 'city-select',
                        localityId: 'locality-select',
                        selectedState: "{{ request('state') }}",
                        selectedCity: "{{ request('district') }}",
                        selectedLocality: "{{ request('locality') }}"
                    });
                }
            } catch(e) {
                console.error('Cascading init error:', e);
            }

            // Hover effect on the glass panel
            const panel = document.querySelector('.search-glass-panel');
            if (panel) {
                panel.addEventListener('mousemove', (e) => {
                    const rect = panel.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;
                    
                    panel.style.setProperty('--mouse-x', `${x}px`);
                    panel.style.setProperty('--mouse-y', `${y}px`);
                });
            }

            // Promo Slider Implementation
            const promoSliderWrapper = document.getElementById('promo-slides-wrapper');
            const promoSlides = document.querySelectorAll('.promo-slide');
            const promoDots = document.querySelectorAll('.promo-dot');
            const promoPrevBtn = document.getElementById('promo-prev');
            const promoNextBtn = document.getElementById('promo-next');
            
            let promoCurrentSlide = 0;
            const promoTotalSlides = promoSlides.length;
            let promoSlideInterval;

            function updatePromoSlider() {
                if (promoSliderWrapper) {
                    promoSliderWrapper.style.transform = `translateX(-${promoCurrentSlide * 100}%)`;
                }
                promoDots.forEach((dot, idx) => {
                    dot.classList.toggle('active', idx === promoCurrentSlide);
                });
            }

            function nextPromoSlide() {
                promoCurrentSlide = (promoCurrentSlide + 1) % promoTotalSlides;
                updatePromoSlider();
            }

            function prevPromoSlide() {
                promoCurrentSlide = (promoCurrentSlide - 1 + promoTotalSlides) % promoTotalSlides;
                updatePromoSlider();
            }

            if (promoNextBtn) {
                promoNextBtn.addEventListener('click', () => {
                    nextPromoSlide();
                    resetPromoInterval();
                });
            }

            if (promoPrevBtn) {
                promoPrevBtn.addEventListener('click', () => {
                    prevPromoSlide();
                    resetPromoInterval();
                });
            }

            promoDots.forEach(dot => {
                dot.addEventListener('click', (e) => {
                    promoCurrentSlide = parseInt(e.target.dataset.slideIndex);
                    updatePromoSlider();
                    resetPromoInterval();
                });
            });

            function startPromoInterval() {
                promoSlideInterval = setInterval(nextPromoSlide, 5000);
            }

            function resetPromoInterval() {
                clearInterval(promoSlideInterval);
                startPromoInterval();
            }

            if (promoTotalSlides > 0) {
                startPromoInterval();
            }

        });

            // Mobile navigation toggle
            function toggleMobileNav() {
                const nav = document.getElementById('mobileNav');
                const overlay = document.getElementById('mobileOverlay');
                const isOpen = nav.classList.contains('active');
                if (isOpen) {
                    nav.classList.remove('active');
                    overlay.classList.remove('active');
                    document.body.style.overflow = '';
                } else {
                    nav.classList.add('active');
                    overlay.classList.add('active');
                    document.body.style.overflow = 'hidden';
                }
            }

            // Toggle Mobile Filters Dropdown feature
            window.toggleMobileFiltersDropdown = function() {
                const panel = document.getElementById('mobileFiltersDropdownPanel');
                const caret = document.getElementById('mobileFilterCaret');
                const btn = document.getElementById('mobileFilterToggleBtn');
                if (!panel) return;

                const isHidden = panel.classList.contains('hidden');
                if (isHidden) {
                    panel.classList.remove('hidden');
                    if (caret) caret.style.transform = 'rotate(180deg)';
                    if (btn) btn.classList.add('active');
                } else {
                    panel.classList.add('hidden');
                    if (caret) caret.style.transform = 'rotate(0deg)';
                    if (btn && !document.getElementById('mobileFilterBadge')) {
                        btn.classList.remove('active');
                    }
                }
            };

            // Reset Mobile Filters
            window.resetMobileFilters = function() {
                const citySelect = document.getElementById('mobile_city_select');
                const priceSelect = document.getElementById('mobile_price_select');
                const searchInput = document.getElementById('mobile_search_input');
                const roomsInput = document.getElementById('mobile_rooms_input');
                const badge = document.getElementById('mobileFilterBadge');
                const btn = document.getElementById('mobileFilterToggleBtn');

                if (citySelect) citySelect.value = '';
                if (priceSelect) priceSelect.value = 'any';
                if (searchInput) searchInput.value = '';
                if (roomsInput) roomsInput.value = 'any';
                if (badge) badge.remove();
                if (btn) btn.classList.remove('active');

                window.switchMobileTab('rent');
                const anyPill = document.querySelector('.mobile-room-pill');
                if (anyPill) window.setMobileRoom('any', anyPill);
            };

            // Mobile App Hero Tab Switcher (Rent / Buy / PG)
            window.switchMobileTab = function(tab) {
                const purposeInput = document.getElementById('mobile_purpose_input');
                const typeInput = document.getElementById('mobile_type_input');
                const rentTab = document.getElementById('mobileTabRent');
                const buyTab = document.getElementById('mobileTabBuy');
                const pgTab = document.getElementById('mobileTabPg');
                const plotTab = document.getElementById('mobileTabPlot');

                [rentTab, buyTab, pgTab, plotTab].forEach(t => {
                    if (t) {
                        t.classList.remove('bg-blue-600', 'text-white', 'shadow-md', 'shadow-blue-600/30', 'shadow-blue-500/25');
                        t.classList.add('text-slate-600');
                    }
                });

                if (tab === 'rent') {
                    if (purposeInput) purposeInput.value = 'rent';
                    if (typeInput) typeInput.value = 'all';
                    if (rentTab) {
                        rentTab.classList.add('bg-blue-600', 'text-white', 'shadow-md', 'shadow-blue-500/25');
                        rentTab.classList.remove('text-slate-600');
                    }
                } else if (tab === 'buy') {
                    if (purposeInput) purposeInput.value = 'buy';
                    if (typeInput) typeInput.value = 'all';
                    if (buyTab) {
                        buyTab.classList.add('bg-blue-600', 'text-white', 'shadow-md', 'shadow-blue-500/25');
                        buyTab.classList.remove('text-slate-600');
                    }
                } else if (tab === 'pg') {
                    if (purposeInput) purposeInput.value = 'rent';
                    if (typeInput) typeInput.value = 'pg-hostel';
                    if (pgTab) {
                        pgTab.classList.add('bg-blue-600', 'text-white', 'shadow-md', 'shadow-blue-500/25');
                        pgTab.classList.remove('text-slate-600');
                    }
                } else if (tab === 'plot') {
                    if (purposeInput) purposeInput.value = 'buy';
                    if (typeInput) typeInput.value = 'plot';
                    if (plotTab) {
                        plotTab.classList.add('bg-blue-600', 'text-white', 'shadow-md', 'shadow-blue-500/25');
                        plotTab.classList.remove('text-slate-600');
                    }
                }
            };

            // Mobile App Hero Room Selection
            window.setMobileRoom = function(room, btn) {
                const input = document.getElementById('mobile_rooms_input');
                if (input) input.value = room;
                document.querySelectorAll('.mobile-room-pill').forEach(p => {
                    p.classList.remove('bg-blue-600', 'text-white', 'shadow-xs');
                    p.classList.add('bg-slate-100', 'text-slate-700', 'border', 'border-slate-200');
                });
                if (btn) {
                    btn.classList.remove('bg-slate-100', 'text-slate-700', 'border', 'border-slate-200');
                    btn.classList.add('bg-blue-600', 'text-white', 'shadow-xs');
                }
            };

            // Mobile App Hero Banner Slider Controller
            let mobileHeroCurrentSlide = 0;
            const mobileHeroTotalSlides = 4;
            let mobileHeroTimer = null;

            window.updateMobileHeroSlider = function() {
                const track = document.getElementById('mobileHeroSliderTrack');
                const dots = document.querySelectorAll('.mobile-slider-dot');
                if (track) {
                    track.style.transform = `translateX(-${mobileHeroCurrentSlide * 100}%)`;
                }
                dots.forEach((dot, idx) => {
                    dot.classList.toggle('active', idx === mobileHeroCurrentSlide);
                });
            };

            window.goMobileSlide = function(idx) {
                mobileHeroCurrentSlide = idx;
                window.updateMobileHeroSlider();
                window.resetMobileHeroTimer();
            };

            window.nextMobileHeroSlide = function() {
                mobileHeroCurrentSlide = (mobileHeroCurrentSlide + 1) % mobileHeroTotalSlides;
                window.updateMobileHeroSlider();
            };

            window.prevMobileHeroSlide = function() {
                mobileHeroCurrentSlide = (mobileHeroCurrentSlide - 1 + mobileHeroTotalSlides) % mobileHeroTotalSlides;
                window.updateMobileHeroSlider();
            };

            window.startMobileHeroTimer = function() {
                mobileHeroTimer = setInterval(window.nextMobileHeroSlide, 4500);
            };

            window.resetMobileHeroTimer = function() {
                if (mobileHeroTimer) clearInterval(mobileHeroTimer);
                window.startMobileHeroTimer();
            };

            document.addEventListener('DOMContentLoaded', () => {
                const container = document.getElementById('mobileHeroSliderContainer');
                if (!container) return;

                window.startMobileHeroTimer();

                let touchStartX = 0;
                let touchEndX = 0;

                container.addEventListener('touchstart', (e) => {
                    touchStartX = e.touches[0].clientX;
                    if (mobileHeroTimer) clearInterval(mobileHeroTimer);
                }, { passive: true });

                container.addEventListener('touchend', (e) => {
                    touchEndX = e.changedTouches[0].clientX;
                    const diff = touchStartX - touchEndX;
                    if (Math.abs(diff) > 40) {
                        if (diff > 0) {
                            window.nextMobileHeroSlide();
                        } else {
                            window.prevMobileHeroSlide();
                        }
                    }
                    window.resetMobileHeroTimer();
                }, { passive: true });
            });

            // Flipkart Style Animated Rotating Search Placeholder
            document.addEventListener('DOMContentLoaded', () => {
                const searchPlaceholders = [
                    'Search "Rooms in Gurugram"...',
                    'Search "1 & 2 BHK Flats"...',
                    'Search "PG with Food & Wi-Fi"...',
                    'Search "Flats in Delhi NCR"...',
                    'Search "Independent Houses"...',
                    'Search "Zero Brokerage Rentals"...'
                ];
                let searchPlaceholderIndex = 0;
                const mobileSearchInput = document.getElementById('mobile_search_input');
                if (mobileSearchInput) {
                    setInterval(() => {
                        if (document.activeElement !== mobileSearchInput && !mobileSearchInput.value) {
                            searchPlaceholderIndex = (searchPlaceholderIndex + 1) % searchPlaceholders.length;
                            mobileSearchInput.setAttribute('placeholder', searchPlaceholders[searchPlaceholderIndex]);
                        }
                    }, 2800);
                }
            });

            // Mobile City Location Selector Modal Controller
            window.openMobileCityModal = function(noticeMsg = null) {
                const modal = document.getElementById('mobileCityModal');
                const notice = document.getElementById('modalGpsNotice');
                const noticeText = document.getElementById('modalGpsNoticeText');
                if (notice && noticeText) {
                    if (noticeMsg) {
                        noticeText.textContent = noticeMsg;
                        notice.classList.remove('hidden');
                    } else {
                        notice.classList.add('hidden');
                    }
                }
                if (modal) {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    document.body.style.overflow = 'hidden';
                }
            };

            window.closeMobileCityModal = function() {
                const modal = document.getElementById('mobileCityModal');
                if (modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    document.body.style.overflow = '';
                }
            };

            window.selectMobileCity = function(city) {
                window.closeMobileCityModal();
                if (city) {
                    window.location.href = "{{ route('properties.index') }}?district=" + encodeURIComponent(city);
                } else {
                    window.location.href = "{{ route('properties.index') }}";
                }
            };

            // Geolocation 'Search House Near Me' function
            window.searchNearMe = function() {
                const btn = document.getElementById('btnNearMe');
                const mBtn = document.getElementById('mobileBtnNearMe');
                const modalGpsBtn = document.getElementById('modalGpsDetectBtn');

                if (!navigator.geolocation) {
                    window.openMobileCityModal('Device geolocation is not supported. Please select your city below:');
                    return;
                }

                if (btn) btn.innerHTML = '<i class="ph ph-circle-notch ph-spin" style="font-size:16px;"></i> Locating...';
                if (mBtn) mBtn.innerHTML = '<i class="ph ph-circle-notch ph-spin text-xs"></i> <span>Locating...</span>';
                if (modalGpsBtn) {
                    modalGpsBtn.innerHTML = `
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center text-base shadow-sm">
                                <i class="ph ph-circle-notch ph-spin"></i>
                            </div>
                            <div>
                                <span class="block text-xs font-black text-blue-900">Detecting Location...</span>
                                <span class="block text-[10px] text-blue-700 font-medium">Fetching GPS coordinates</span>
                            </div>
                        </div>
                    `;
                }

                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const lat = position.coords.latitude;
                        const lon = position.coords.longitude;
                        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`)
                            .then(res => res.json())
                            .then(data => {
                                const city = (data.address && (data.address.city || data.address.town || data.address.state_district || data.address.suburb)) || 'Delhi';
                                window.location.href = "{{ route('properties.index') }}?district=" + encodeURIComponent(city);
                            })
                            .catch(() => {
                                window.location.href = "{{ route('properties.index') }}";
                            });
                    },
                    (error) => {
                        if (btn) btn.innerHTML = '<i class="ph-fill ph-navigation-arrow" style="font-size:16px; color:#60a5fa;"></i> Search House Near Me';
                        if (mBtn) mBtn.innerHTML = '<i class="ph-fill ph-navigation-arrow text-xs text-blue-600"></i> <span>Near</span>';
                        if (modalGpsBtn) {
                            modalGpsBtn.innerHTML = `
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center text-base shadow-sm">
                                        <i class="ph-fill ph-navigation-arrow"></i>
                                    </div>
                                    <div>
                                        <span class="block text-xs font-black text-blue-900">Use Current Location</span>
                                        <span class="block text-[10px] text-blue-700 font-medium">Auto-detect area via device GPS</span>
                                    </div>
                                </div>
                                <i class="ph-bold ph-caret-right text-blue-500 text-xs"></i>
                            `;
                        }
                        // Open the city picker modal smoothly with an in-modal notice banner instead of browser alert!
                        window.openMobileCityModal('Location access is denied or disabled. Please select your city below:');
                    },
                    { timeout: 8000 }
                );
            };

            // Feedback Logic
            const stars = document.querySelectorAll('.rating-star');
            let currentRating = 0;

            stars.forEach(star => {
                star.addEventListener('click', () => {
                    currentRating = star.dataset.rating;
                    stars.forEach(s => {
                        s.classList.toggle('active', s.dataset.rating <= currentRating);
                    });
                });
            });

            // Feedback Modal Logic
            function openFeedbackModal() {
                document.getElementById('feedbackModal').classList.add('active');
                document.body.style.overflow = 'hidden';
            }

            function closeFeedbackModal() {
                document.getElementById('feedbackModal').classList.remove('active');
                document.body.style.overflow = '';
            }

            let modalRating = 0;
            document.addEventListener('DOMContentLoaded', () => {
                const modalStars = document.querySelectorAll('.modal-star');
                modalStars.forEach(star => {
                    star.addEventListener('click', () => {
                        modalRating = parseInt(star.dataset.rating) || 0;
                        modalStars.forEach(s => {
                            s.classList.toggle('active', (parseInt(s.dataset.rating) || 0) <= modalRating);
                        });
                    });
                });
            });

            function submitModalFeedback(btn) {
                if(modalRating === 0) {
                    alert('Please select a star rating first!');
                    return;
                }
                
                const comment = document.getElementById('modalComment').value;
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="ph ph-circle-notch ph-spin"></i> Processing...';
                btn.disabled = true;

                fetch("{{ route('feedback.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        rating: modalRating,
                        comment: comment
                    })
                })
                .then(response => response.json())
                .then(data => {
                    btn.innerHTML = '<i class="ph ph-check-circle"></i> Sent Successfully!';
                    btn.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
                    setTimeout(() => {
                        closeFeedbackModal();
                        window.location.reload();
                    }, 2000);
                })
                .catch(error => {
                    console.error('Error:', error);
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    alert('Something went wrong. Please try again.');
                });
            }
    </script>

    @if(($site_settings['feedback_enabled'] ?? '1') == '1')
    <!-- Feedback Modal Trigger (Hidden on mobile to avoid overlapping navigation) -->
    <button class="feedback-modal-trigger hidden md:flex" onclick="openFeedbackModal()" style="position: fixed; left: 30px; bottom: 30px; width: 60px; height: 60px; background: rgba(25, 25, 30, 0.8); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1); border-radius: 50%; color: var(--primary); font-size: 24px; cursor: pointer; z-index: 9998; align-items: center; justify-content: center; box-shadow: 0 10px 30px rgba(0,0,0,0.3); transition: all 0.3s;">
        <img src="{{ asset('images/icons/feedback.png') }}" alt="Feedback" title="Send Platform Feedback" width="32" height="32" loading="lazy" decoding="async" style="width: 32px; height: 32px; object-fit: contain; filter: invert(1) grayscale(1) brightness(200%); mix-blend-mode: screen;">
    </button>

    <!-- Feedback Modal Overlay -->
    <div class="feedback-overlay" id="feedbackModal">
        <div class="feedback-modal">
            <div class="modal-close" onclick="closeFeedbackModal()" style="display: flex; align-items: center; justify-content: center;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #94a3b8;"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </div>
            
            <div class="modal-header">
                <h2 class="modal-title">How are we doing?</h2>
                <p class="modal-subtitle">Your feedback helps us refine the luxury experience at UnlockRentals. Share your thoughts with us.</p>
            </div>

            <div class="modal-stars" id="modalStars">
                <span class="modal-star" data-rating="1" style="font-style: normal; display: inline-block;">★</span>
                <span class="modal-star" data-rating="2" style="font-style: normal; display: inline-block;">★</span>
                <span class="modal-star" data-rating="3" style="font-style: normal; display: inline-block;">★</span>
                <span class="modal-star" data-rating="4" style="font-style: normal; display: inline-block;">★</span>
                <span class="modal-star" data-rating="5" style="font-style: normal; display: inline-block;">★</span>
            </div>

            <div class="modal-form">
                <textarea class="modal-textarea" id="modalComment" placeholder="Share your thoughts or report an issue..."></textarea>
                <div style="display: flex; gap: 12px; width: 100%;">
                    <button class="modal-cancel" onclick="closeFeedbackModal()" style="flex: 1; padding: 12px 24px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.1); background: rgba(255,255,255,0.05); color: #fff; font-weight: 600; cursor: pointer; transition: all 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='rgba(255,255,255,0.05)'">
                        Cancel
                    </button>
                    <button class="modal-submit" onclick="submitModalFeedback(this)" style="flex: 2;">
                        Send Feedback
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline-block; margin-left: 8px;"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Mobile Bottom Navigation Bar --}}
    @include('components.mobile-nav')

    {{-- PWA Install Prompt Banner --}}
    @include('components.pwa-install-prompt')

    <!-- PWA & Network Service Worker Logic -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('Service Worker registered successfully:', reg.scope))
                    .catch(err => console.error('Service Worker registration failed:', err));
            });
        }

        // Online/Offline Detection Toast Notifier
        function showNetworkToast(isOnline) {
            const existing = document.getElementById('network-status-toast');
            if (existing) existing.remove();

            const toast = document.createElement('div');
            toast.id = 'network-status-toast';
            toast.className = `fixed top-20 left-1/2 -translate-x-1/2 z-[9999] px-6 py-3 rounded-full shadow-2xl font-bold text-xs flex items-center gap-2 transition-all duration-300 transform -translate-y-10 opacity-0`;
            
            if (isOnline) {
                toast.classList.add('bg-emerald-600', 'text-white');
                toast.innerHTML = `<i class="ph-bold ph-wifi-high text-sm"></i> Connection Restored. Back online!`;
            } else {
                toast.classList.add('bg-red-600', 'text-white');
                toast.innerHTML = `<i class="ph-bold ph-wifi-slash text-sm"></i> Connection Lost. Working offline.`;
            }

            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.classList.remove('-translate-y-10', 'opacity-0');
                toast.classList.add('translate-y-0', 'opacity-100');
            }, 50);

            setTimeout(() => {
                toast.classList.remove('translate-y-0', 'opacity-100');
                toast.classList.add('-translate-y-10', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        }

        window.addEventListener('online', () => showNetworkToast(true));
        window.addEventListener('offline', () => showNetworkToast(false));
    </script>

    @guest
        <x-auth-modal />
    @endguest

    @include('components.idle-logout')

    <!-- Deferred Non-Critical Scripts -->
    <script src="{{ asset('js/otp-verification.js') }}?v=20260916"></script>
    @include('components.location-script')
</body>
</html>
