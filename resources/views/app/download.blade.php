@extends('layouts.app')

@section('title', 'Download UnlockRentals Mobile App - Zero Brokerage Rentals for Android & iOS')
@section('meta_description', 'Download the official UnlockRentals app for Android and iOS. Search 10,000+ verified zero-brokerage rental rooms, flats, PGs, and houses with direct owner contacts across India.')

@section('content')
<div class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 min-h-screen selection:bg-blue-600 selection:text-white font-sans antialiased relative overflow-x-hidden">

    {{-- Ambient Background Lighting --}}
    <div class="absolute inset-0 pointer-events-none overflow-hidden z-0">
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-blue-500/10 dark:bg-blue-600/15 rounded-full blur-3xl"></div>
        <div class="absolute top-1/3 -right-40 w-[30rem] h-[30rem] bg-indigo-500/10 dark:bg-indigo-600/15 rounded-full blur-3xl"></div>
        <div class="absolute bottom-10 left-1/4 w-80 h-80 bg-blue-400/10 dark:bg-blue-500/10 rounded-full blur-2xl"></div>
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#e2e8f015_1px,transparent_1px),linear-gradient(to_bottom,#e2e8f015_1px,transparent_1px)] dark:bg-[linear-gradient(to_right,#1e293b25_1px,transparent_1px),linear-gradient(to_bottom,#1e293b25_1px,transparent_1px)] bg-[size:4rem_4rem]"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16">

        {{-- Dynamic Platform Detection Notice --}}
        <div id="platform-notice" class="hidden mb-8 p-4 bg-gradient-to-r from-blue-600/10 via-indigo-600/10 to-blue-600/10 border border-blue-200 dark:border-blue-800/60 rounded-2xl text-center backdrop-blur-sm animate-fade-in shadow-sm">
            <div class="flex flex-wrap items-center justify-center gap-3">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 text-white text-sm font-bold shadow-md shadow-blue-500/30">
                    <i id="platform-icon" class="ph-bold ph-device-mobile"></i>
                </span>
                <p id="platform-msg" class="text-sm font-semibold text-slate-800 dark:text-slate-200">
                    Looking for the best experience? Download the app tailored for your device.
                </p>
                <a id="platform-quick-btn" href="#store-download-options" class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg transition-all shadow-sm">
                    <span>Download App</span>
                    <i class="ph-bold ph-arrow-down"></i>
                </a>
            </div>
        </div>

        {{-- Hero Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center pt-2 pb-16">
            
            {{-- Left Content Column --}}
            <div class="lg:col-span-7 text-center lg:text-left">
                
                {{-- Verified Badge --}}
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700/60 text-blue-700 dark:text-blue-300 text-xs font-bold uppercase tracking-wider mb-6 shadow-sm">
                    <i class="ph-fill ph-shield-check text-sm text-blue-600 dark:text-blue-400"></i>
                    <span>Official Mobile Application • Zero Brokerage</span>
                </div>

                {{-- Clean Standard Headline --}}
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black font-display tracking-tight text-slate-900 dark:text-white leading-[1.12] mb-6">
                    Your Rental Search, <br class="hidden sm:inline">
                    <span class="bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-700 bg-clip-text text-transparent">
                        Simplified In Your Pocket.
                    </span>
                </h1>

                {{-- Clean Body Text --}}
                <p class="text-base sm:text-lg lg:text-xl text-slate-600 dark:text-slate-300 font-normal leading-relaxed max-w-2xl mx-auto lg:mx-0 mb-8">
                    Discover 10,000+ verified 1RK, 1BHK, 2BHK flats, shared PGs, and independent houses across India. Connect directly with genuine property owners on WhatsApp with <strong class="font-bold text-slate-900 dark:text-white">₹0 brokerage fees</strong>.
                </p>

                {{-- Social Proof Badges --}}
                <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4 sm:gap-6 text-xs sm:text-sm font-semibold text-slate-600 dark:text-slate-400 mb-10">
                    <div class="flex items-center gap-1.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 px-3 py-1.5 rounded-lg shadow-sm">
                        <i class="ph-fill ph-star text-amber-400 text-base"></i>
                        <span class="text-slate-900 dark:text-white font-bold">4.8 / 5.0</span>
                        <span class="text-slate-400">Rating</span>
                    </div>
                    <div class="flex items-center gap-1.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 px-3 py-1.5 rounded-lg shadow-sm">
                        <i class="ph-bold ph-download-simple text-blue-600 dark:text-blue-400 text-base"></i>
                        <span class="text-slate-900 dark:text-white font-bold">50,000+</span>
                        <span class="text-slate-400">Downloads</span>
                    </div>
                    <div class="flex items-center gap-1.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 px-3 py-1.5 rounded-lg shadow-sm">
                        <i class="ph-bold ph-seal-check text-emerald-500 text-base"></i>
                        <span class="text-slate-900 dark:text-white font-bold">100%</span>
                        <span class="text-slate-400">Direct Owners</span>
                    </div>
                </div>

                {{-- Store Download Options --}}
                <div id="store-download-options" class="flex flex-col sm:flex-row flex-wrap items-center justify-center lg:justify-start gap-3 sm:gap-4 mb-6">
                    
                    {{-- Google Play Store Button --}}
                    @php
                        $googlePlayUrl = (!empty($site_settings['app_google_play_url']) && $site_settings['app_google_play_url'] !== '#')
                            ? $site_settings['app_google_play_url']
                            : route('app.download.apk');
                    @endphp
                    <a href="{{ $googlePlayUrl }}" target="_blank" rel="noopener noreferrer" class="w-full sm:w-auto inline-flex items-center justify-center gap-3.5 px-6 py-3.5 bg-slate-900 hover:bg-slate-800 dark:bg-slate-800 dark:hover:bg-slate-700 text-white rounded-2xl border border-slate-700/60 shadow-lg hover:shadow-xl transition-all duration-200 group transform hover:-translate-y-0.5" id="store-btn-google" title="Get it on Google Play">
                        <div class="w-7 h-7 flex-shrink-0 flex items-center justify-center">
                            <svg class="w-full h-full" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                                <path d="M32.5 17.5C29.6 20.3 28 24.4 28 29.5v453c0 5.1 1.6 9.2 4.5 12l1.5 1.5L257 273v-6l-223-251-1.5 1.5z" fill="#00a3ff"/>
                                <path d="M353.5 173.5l-96.5 96.5v6l96.5 96.5 1.5-1 113.5-64.5c32.5-18.5 32.5-48.5 0-67L355 174.5l-1.5-1z" fill="#ffc107"/>
                                <path d="M257 276.5l-223 223c4.5 4.5 11.5 5 20 0.5l301-171-98-52.5z" fill="#ff3d00"/>
                                <path d="M257 269.5l98-52.5-301-171-8.5-4.5-15.5-4-20 0.5l223 223z" fill="#4caf50"/>
                            </svg>
                        </div>
                        <div class="text-left">
                            <span class="block text-[10px] font-bold tracking-wider text-slate-400 uppercase">GET IT ON</span>
                            <span class="block text-base font-extrabold text-white leading-tight font-display">Google Play</span>
                        </div>
                    </a>

                    {{-- Apple App Store Button --}}
                    @php
                        $appStoreUrl = (!empty($site_settings['app_store_url']) && $site_settings['app_store_url'] !== '#')
                            ? $site_settings['app_store_url']
                            : route('app.download');
                    @endphp
                    <a href="{{ $appStoreUrl }}" target="_blank" rel="noopener noreferrer" class="w-full sm:w-auto inline-flex items-center justify-center gap-3.5 px-6 py-3.5 bg-slate-900 hover:bg-slate-800 dark:bg-slate-800 dark:hover:bg-slate-700 text-white rounded-2xl border border-slate-700/60 shadow-lg hover:shadow-xl transition-all duration-200 group transform hover:-translate-y-0.5" id="store-btn-apple" title="Download on the App Store">
                        <div class="w-7 h-7 flex-shrink-0 flex items-center justify-center text-white">
                            <svg class="w-full h-full fill-current" viewBox="0 0 384 512" xmlns="http://www.w3.org/2000/svg">
                                <path d="M318.7 268.7c-.2-36.7 16.4-64.4 50-84.8-18.8-26.9-47.2-41.7-84.7-44.6-35.5-2.8-74.3 20.7-88.5 20.7-15 0-49.4-19.7-76.4-19.7C63.3 141.2 4 184.8 4 273.5q0 39.3 14.4 81.2c12.8 36.7 59 126.7 107.2 125.2 25.2-.6 43-17.9 75.8-17.9 31.8 0 48.3 17.9 76.4 17.9 48.6-.7 90.4-82.5 102.6-119.3-65.2-31.4-57.3-114.3-1.7-114.3-1.4 0-1.4 0 0 0zm-7.9-167.2c31.7-36.7 22.1-85 21.6-86.3-4.2.3-51.4 14.4-83.6 51.8-29.8 35.8-22.6 78.4-20.1 82.6 4.3.4 50.4-11.4 82.1-48.1z"/>
                            </svg>
                        </div>
                        <div class="text-left">
                            <span class="block text-[10px] font-bold tracking-wider text-slate-400 uppercase">Download on the</span>
                            <span class="block text-base font-extrabold text-white leading-tight font-display">App Store</span>
                        </div>
                    </a>

                    {{-- Indus Appstore Button --}}
                    @php
                        $indusUrl = (!empty($site_settings['app_indus_appstore_url']) && $site_settings['app_indus_appstore_url'] !== '#')
                            ? $site_settings['app_indus_appstore_url']
                            : 'https://www.indusappstore.com/app/com.unlockrentals.app';
                    @endphp
                    <a href="{{ $indusUrl }}" target="_blank" rel="noopener noreferrer" class="w-full sm:w-auto inline-flex items-center justify-center gap-3.5 px-6 py-3.5 bg-slate-900 hover:bg-slate-800 dark:bg-slate-800 dark:hover:bg-slate-700 text-white rounded-2xl border border-slate-700/60 shadow-lg hover:shadow-xl transition-all duration-200 group transform hover:-translate-y-0.5" id="store-btn-indus" title="Available on Indus Appstore" onclick="return window.__launchIndusApp ? window.__launchIndusApp(event, '{{ $indusUrl }}') : true;">
                        <div class="w-7 h-7 flex-shrink-0 flex items-center justify-center">
                            <svg class="w-full h-full" viewBox="0 0 512 512" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <linearGradient id="indusOrbGradHero" x1="50%" y1="0%" x2="50%" y2="100%">
                                        <stop offset="0%" stop-color="#FFB300" />
                                        <stop offset="100%" stop-color="#FF5400" />
                                    </linearGradient>
                                    <linearGradient id="indusMidChevHero" x1="50%" y1="0%" x2="50%" y2="100%">
                                        <stop offset="0%" stop-color="#FF5400" />
                                        <stop offset="100%" stop-color="#E8165B" />
                                    </linearGradient>
                                    <linearGradient id="indusBotChevHero" x1="50%" y1="0%" x2="50%" y2="100%">
                                        <stop offset="0%" stop-color="#E8165B" />
                                        <stop offset="100%" stop-color="#D0006F" />
                                    </linearGradient>
                                </defs>
                                <rect width="512" height="512" rx="128" fill="#051030" />
                                <circle cx="256" cy="152" r="56" fill="url(#indusOrbGradHero)" />
                                <path d="M186 210 L256 256 L326 210 L326 268 L256 314 L186 268 Z" fill="url(#indusMidChevHero)" />
                                <path d="M186 288 L256 334 L326 288 L326 346 L256 392 L186 346 Z" fill="url(#indusBotChevHero)" />
                            </svg>
                        </div>
                        <div class="text-left">
                            <span class="block text-[10px] font-bold tracking-wider text-slate-400 uppercase">GET IT ON</span>
                            <span class="block text-base font-extrabold text-white leading-tight font-display">Indus Appstore</span>
                        </div>
                    </a>
                </div>

                {{-- Direct Android APK Download Card --}}
                @php
                    $apkDownloadUrl = (!empty($site_settings['app_apk_download_url']) && $site_settings['app_apk_download_url'] !== '#')
                        ? $site_settings['app_apk_download_url']
                        : route('app.download.apk');
                @endphp
                <div class="pt-2">
                    <a href="{{ $apkDownloadUrl }}" class="inline-flex items-center gap-3 px-5 py-3 rounded-xl bg-blue-50/80 hover:bg-blue-100/80 dark:bg-blue-950/40 dark:hover:bg-blue-900/50 border border-blue-200 dark:border-blue-800/80 text-blue-700 dark:text-blue-300 transition-all duration-200 shadow-sm group" title="Download Android APK directly">
                        <span class="w-8 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                            <i class="ph-bold ph-android-logo text-lg"></i>
                        </span>
                        <div class="text-left">
                            <div class="flex items-center gap-2">
                                <span class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white">Download Android APK Directly</span>
                                <span class="inline-block px-2 py-0.5 text-[10px] font-extrabold uppercase rounded bg-emerald-100 text-emerald-800 dark:bg-emerald-900/60 dark:text-emerald-300">Fast Download</span>
                            </div>
                            <span class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">v2.4.0 • 18 MB • Android 8.0+ • Virus-Free</span>
                        </div>
                        <i class="ph-bold ph-download-simple text-base ml-auto text-blue-600 dark:text-blue-400"></i>
                    </a>
                </div>

            </div>

            {{-- Right Visual Column: Mobile Phone Mockup --}}
            <div class="lg:col-span-5 flex justify-center relative">
                <div class="relative w-full max-w-sm sm:max-w-md">
                    
                    {{-- Ambient Glow behind Phone --}}
                    <div class="absolute inset-0 bg-gradient-to-tr from-blue-600/20 to-indigo-600/20 rounded-full blur-3xl transform -translate-y-4"></div>
                    
                    {{-- Premium App Mockup Image --}}
                    <div class="relative z-10 transition-transform duration-500 hover:scale-[1.02]">
                        <picture>
                            <source srcset="{{ asset('unlockrental_premium_mockup_1778934329998.webp') }}" type="image/webp">
                            <img src="{{ asset('unlockrental_premium_mockup_1778934329998.png') }}" 
                                 alt="UnlockRentals Mobile App Interface" 
                                 title="UnlockRentals Mobile App Interface" 
                                 class="w-full h-auto drop-shadow-[0_35px_35px_rgba(0,0,0,0.22)] filter">
                        </picture>
                    </div>

                    {{-- Floating Glass Card 1: Verified Owner --}}
                    <div class="absolute -top-3 -left-4 sm:-left-8 z-20 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md border border-white/60 dark:border-slate-700/60 rounded-2xl p-3.5 shadow-xl flex items-center gap-3 animate-pulse duration-[4000ms]">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center flex-shrink-0 shadow-md shadow-emerald-500/30">
                            <i class="ph-fill ph-seal-check text-xl"></i>
                        </div>
                        <div>
                            <span class="block text-[11px] font-extrabold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Verified Owner</span>
                            <span class="block text-xs font-bold text-slate-900 dark:text-white">Save ₹15,000 Brokerage</span>
                        </div>
                    </div>

                    {{-- Floating Glass Card 2: Instant WhatsApp --}}
                    <div class="absolute -bottom-4 -right-4 sm:-right-6 z-20 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md border border-white/60 dark:border-slate-700/60 rounded-2xl p-3.5 shadow-xl flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-green-500 text-white flex items-center justify-center flex-shrink-0 shadow-md shadow-green-500/30">
                            <i class="ph-bold ph-whatsapp-logo text-xl"></i>
                        </div>
                        <div>
                            <span class="block text-[11px] font-extrabold uppercase tracking-wider text-slate-400">1-Tap Connect</span>
                            <span class="block text-xs font-bold text-slate-900 dark:text-white">Instant WhatsApp Chat</span>
                        </div>
                    </div>

                    {{-- Floating Glass Card 3: Rating --}}
                    <div class="hidden sm:flex absolute top-1/2 -right-8 z-20 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border border-white/60 dark:border-slate-700/60 rounded-2xl p-3 shadow-xl items-center gap-2.5">
                        <span class="text-amber-400 text-lg">★</span>
                        <div class="text-left">
                            <span class="block text-xs font-extrabold text-slate-900 dark:text-white">4.8 / 5.0 Rating</span>
                            <span class="block text-[10px] text-slate-400 font-medium">12,000+ Reviews</span>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        {{-- Key Trust Metrics Strip --}}
        <div class="mt-8 mb-20">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
                <div class="bg-white dark:bg-slate-900/80 border border-slate-200/80 dark:border-slate-800 p-5 rounded-2xl text-center shadow-sm">
                    <span class="block text-2xl sm:text-3xl font-black text-blue-600 dark:text-blue-400 font-display">10,000+</span>
                    <span class="block text-xs sm:text-sm font-semibold text-slate-600 dark:text-slate-400 mt-1">Verified Properties</span>
                </div>
                <div class="bg-white dark:bg-slate-900/80 border border-slate-200/80 dark:border-slate-800 p-5 rounded-2xl text-center shadow-sm">
                    <span class="block text-2xl sm:text-3xl font-black text-emerald-600 dark:text-emerald-400 font-display">₹0</span>
                    <span class="block text-xs sm:text-sm font-semibold text-slate-600 dark:text-slate-400 mt-1">Brokerage Fee</span>
                </div>
                <div class="bg-white dark:bg-slate-900/80 border border-slate-200/80 dark:border-slate-800 p-5 rounded-2xl text-center shadow-sm">
                    <span class="block text-2xl sm:text-3xl font-black text-indigo-600 dark:text-indigo-400 font-display">50,000+</span>
                    <span class="block text-xs sm:text-sm font-semibold text-slate-600 dark:text-slate-400 mt-1">Tenants Assisted</span>
                </div>
                <div class="bg-white dark:bg-slate-900/80 border border-slate-200/80 dark:border-slate-800 p-5 rounded-2xl text-center shadow-sm">
                    <span class="block text-2xl sm:text-3xl font-black text-amber-500 font-display">28+ Cities</span>
                    <span class="block text-xs sm:text-sm font-semibold text-slate-600 dark:text-slate-400 mt-1">Pan-India Reach</span>
                </div>
            </div>
        </div>

        {{-- Desktop QR Code Card --}}
        <div class="hidden md:block mb-24">
            <div class="bg-gradient-to-r from-blue-900 via-indigo-950 to-slate-900 text-white rounded-3xl p-8 sm:p-10 shadow-2xl relative overflow-hidden">
                <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
                    <div class="max-w-xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/20 text-blue-200 text-xs font-bold uppercase tracking-wider mb-4">
                            <i class="ph-bold ph-qr-code text-sm"></i>
                            Instant Mobile Setup
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-black font-display tracking-tight text-white mb-3">
                            Browsing from your laptop or desktop?
                        </h2>
                        <p class="text-sm sm:text-base text-slate-300 leading-relaxed font-normal">
                            Scan this QR code using your smartphone's camera to instantly open and install UnlockRentals on your mobile phone without typing any links.
                        </p>
                    </div>
                    <div class="flex-shrink-0 bg-white p-4 rounded-2xl shadow-xl flex flex-col items-center">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode(route('app.download')) }}&margin=4" 
                             alt="Scan QR Code to Download UnlockRentals" 
                             title="Scan to download app"
                             width="180" 
                             height="180" 
                             class="rounded-lg w-40 h-40 object-contain">
                        <span class="text-[11px] font-bold text-slate-600 mt-2 flex items-center gap-1">
                            <i class="ph-bold ph-camera"></i> Point camera to scan
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- App Feature Highlights (Clean & Standard Displayed Text) --}}
        <div class="mb-24">
            <div class="text-center max-w-3xl mx-auto mb-14">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-xs font-bold uppercase tracking-wider mb-3">
                    <i class="ph-bold ph-lightning text-sm"></i>
                    Everything You Need
                </div>
                <h2 class="text-3xl sm:text-4xl font-black font-display tracking-tight text-slate-900 dark:text-white">
                    Designed for the Modern Rental Experience
                </h2>
                <p class="text-base text-slate-600 dark:text-slate-400 mt-3 font-normal">
                    Everything you need to find, inspect, and finalize your next rental home without the headache of brokers.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                
                {{-- Feature 1 --}}
                <div class="bg-white dark:bg-slate-900 p-7 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-2xl mb-5 shadow-sm">
                        <i class="ph-bold ph-currency-inr"></i>
                    </div>
                    <h3 class="text-lg font-bold font-display text-slate-900 dark:text-white mb-2">Zero Brokerage Guarantee</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed font-normal">
                        Every property on UnlockRentals is posted directly by homeowners and flatmates. Never pay 15 to 30 days of rent to a middleman again.
                    </p>
                </div>

                {{-- Feature 2 --}}
                <div class="bg-white dark:bg-slate-900 p-7 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-2xl mb-5 shadow-sm">
                        <i class="ph-bold ph-navigation-arrow"></i>
                    </div>
                    <h3 class="text-lg font-bold font-display text-slate-900 dark:text-white mb-2">Near Me GPS Search</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed font-normal">
                        Use one-tap geolocation to explore available 1RKs, flats, and PGs within walking distance of your current workplace, university, or metro station.
                    </p>
                </div>

                {{-- Feature 3 --}}
                <div class="bg-white dark:bg-slate-900 p-7 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 rounded-xl bg-green-50 dark:bg-green-900/40 text-green-600 dark:text-green-400 flex items-center justify-center text-2xl mb-5 shadow-sm">
                        <i class="ph-bold ph-whatsapp-logo"></i>
                    </div>
                    <h3 class="text-lg font-bold font-display text-slate-900 dark:text-white mb-2">Instant WhatsApp Chat</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed font-normal">
                        Connect with verified landlords instantly on WhatsApp or phone call. Confirm amenities, ask questions, and book private site visits immediately.
                    </p>
                </div>

                {{-- Feature 4 --}}
                <div class="bg-white dark:bg-slate-900 p-7 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-2xl mb-5 shadow-sm">
                        <i class="ph-bold ph-images"></i>
                    </div>
                    <h3 class="text-lg font-bold font-display text-slate-900 dark:text-white mb-2">100% Verified Photos</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed font-normal">
                        Say goodbye to bait-and-switch listings. Our moderation team verifies photos, pricing, and amenities to ensure what you see online matches reality.
                    </p>
                </div>

                {{-- Feature 5 --}}
                <div class="bg-white dark:bg-slate-900 p-7 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 flex items-center justify-center text-2xl mb-5 shadow-sm">
                        <i class="ph-bold ph-bell-ringing"></i>
                    </div>
                    <h3 class="text-lg font-bold font-display text-slate-900 dark:text-white mb-2">Real-Time Listing Alerts</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed font-normal">
                        Save your preferred locality, budget, and flat configuration. Receive immediate mobile push notifications the second a new matching home gets listed.
                    </p>
                </div>

                {{-- Feature 6 --}}
                <div class="bg-white dark:bg-slate-900 p-7 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 rounded-xl bg-rose-50 dark:bg-rose-900/40 text-rose-600 dark:text-rose-400 flex items-center justify-center text-2xl mb-5 shadow-sm">
                        <i class="ph-bold ph-file-text"></i>
                    </div>
                    <h3 class="text-lg font-bold font-display text-slate-900 dark:text-white mb-2">Digital Rent Agreements</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed font-normal">
                        Draft, customize, and sign legally valid e-stamped rental agreements from the comfort of your couch, without visiting a notary office.
                    </p>
                </div>

            </div>
        </div>

        {{-- 3-Step APK Install Guide (Clean Instruction Steps) --}}
        <div class="mb-24">
            <div class="bg-slate-100 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800/80 rounded-3xl p-8 sm:p-12">
                <div class="max-w-2xl mx-auto text-center mb-10">
                    <span class="inline-block px-3 py-1 rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300 text-xs font-bold uppercase tracking-wider mb-3">
                        Android Quick Setup
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-black font-display text-slate-900 dark:text-white tracking-tight">
                        How to Install the APK on Your Android Device
                    </h2>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-2 font-normal">
                        If you downloaded our direct APK file, follow these 3 simple steps to get started in under 30 seconds:
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm text-center">
                        <span class="w-10 h-10 rounded-full bg-blue-600 text-white font-extrabold text-base inline-flex items-center justify-center mb-4 shadow-md shadow-blue-500/20">
                            1
                        </span>
                        <h4 class="text-base font-bold text-slate-900 dark:text-white mb-2">Download File</h4>
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 leading-relaxed font-normal">
                            Tap the <strong>Download APK</strong> button. The file will save directly into your smartphone's Downloads folder.
                        </p>
                    </div>

                    <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm text-center">
                        <span class="w-10 h-10 rounded-full bg-blue-600 text-white font-extrabold text-base inline-flex items-center justify-center mb-4 shadow-md shadow-blue-500/20">
                            2
                        </span>
                        <h4 class="text-base font-bold text-slate-900 dark:text-white mb-2">Allow Unknown Apps</h4>
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 leading-relaxed font-normal">
                            Tap the downloaded file. When Android prompts for security permissions, tap <strong>Settings</strong> and turn on <em>"Allow from this source"</em>.
                        </p>
                    </div>

                    <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm text-center">
                        <span class="w-10 h-10 rounded-full bg-emerald-600 text-white font-extrabold text-base inline-flex items-center justify-center mb-4 shadow-md shadow-emerald-500/20">
                            3
                        </span>
                        <h4 class="text-base font-bold text-slate-900 dark:text-white mb-2">Install & Explore</h4>
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 leading-relaxed font-normal">
                            Tap <strong>Install</strong> to finish. Open the app to immediately search verified rental properties near your location!
                        </p>
                    </div>
                </div>

                <div class="mt-8 text-center">
                    <a href="{{ $apkDownloadUrl }}" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-md transition-all">
                        <i class="ph-bold ph-download-simple text-base"></i>
                        <span>Start APK Download (18 MB)</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Frequently Asked Questions (Clean Accordion) --}}
        <div class="mb-20">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <span class="inline-block px-3 py-1 rounded-full bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-xs font-bold uppercase tracking-wider mb-2">
                    Help & Clarifications
                </span>
                <h2 class="text-3xl font-black font-display text-slate-900 dark:text-white tracking-tight">
                    Frequently Asked Questions
                </h2>
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-2 font-normal">
                    Answers to common questions about the UnlockRentals mobile application.
                </p>
            </div>

            <div class="max-w-3xl mx-auto space-y-4">
                
                <details class="group bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-5 open:shadow-md transition-all">
                    <summary class="flex items-center justify-between cursor-pointer font-bold text-slate-900 dark:text-white list-none select-none text-base">
                        <span>Is the UnlockRentals mobile app free to download and use?</span>
                        <i class="ph-bold ph-caret-down text-slate-400 group-open:rotate-180 transition-transform"></i>
                    </summary>
                    <p class="mt-3 text-sm text-slate-600 dark:text-slate-400 leading-relaxed font-normal border-t border-slate-100 dark:border-slate-800 pt-3">
                        Yes, 100%! Searching properties, browsing verified photos, viewing location maps, and filtering listings are completely free. You can directly browse properties anytime without mandatory upfront fees.
                    </p>
                </details>

                <details class="group bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-5 open:shadow-md transition-all">
                    <summary class="flex items-center justify-between cursor-pointer font-bold text-slate-900 dark:text-white list-none select-none text-base">
                        <span>How does the Zero Brokerage guarantee work?</span>
                        <summary-marker class="hidden"></summary-marker>
                        <i class="ph-bold ph-caret-down text-slate-400 group-open:rotate-180 transition-transform"></i>
                    </summary>
                    <p class="mt-3 text-sm text-slate-600 dark:text-slate-400 leading-relaxed font-normal border-t border-slate-100 dark:border-slate-800 pt-3">
                        Unlike traditional broker networks that charge 15 to 30 days of rent as commission, UnlockRentals connects tenants directly with verified homeowners and primary flatmates. There are no middleman commissions.
                    </p>
                </details>

                <details class="group bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-5 open:shadow-md transition-all">
                    <summary class="flex items-center justify-between cursor-pointer font-bold text-slate-900 dark:text-white list-none select-none text-base">
                        <span>Can I list my own property as an owner on the app?</span>
                        <i class="ph-bold ph-caret-down text-slate-400 group-open:rotate-180 transition-transform"></i>
                    </summary>
                    <p class="mt-3 text-sm text-slate-600 dark:text-slate-400 leading-relaxed font-normal border-t border-slate-100 dark:border-slate-800 pt-3">
                        Absolutely. Property owners can list their apartments, independent houses, single rooms, or commercial spaces in under 2 minutes by uploading photos, setting rental terms, and receiving verified tenant inquiries directly.
                    </p>
                </details>

                <details class="group bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-5 open:shadow-md transition-all">
                    <summary class="flex items-center justify-between cursor-pointer font-bold text-slate-900 dark:text-white list-none select-none text-base">
                        <span>Is it safe to install the APK file directly on Android?</span>
                        <i class="ph-bold ph-caret-down text-slate-400 group-open:rotate-180 transition-transform"></i>
                    </summary>
                    <p class="mt-3 text-sm text-slate-600 dark:text-slate-400 leading-relaxed font-normal border-t border-slate-100 dark:border-slate-800 pt-3">
                        Yes. Our APK is digitally signed and tested for clean security with zero bloatware or adware. Android merely alerts users whenever installing packages outside the Play Store as a standard safeguard.
                    </p>
                </details>

                <details class="group bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-5 open:shadow-md transition-all">
                    <summary class="flex items-center justify-between cursor-pointer font-bold text-slate-900 dark:text-white list-none select-none text-base">
                        <span>Which Android and iOS versions are supported?</span>
                        <i class="ph-bold ph-caret-down text-slate-400 group-open:rotate-180 transition-transform"></i>
                    </summary>
                    <p class="mt-3 text-sm text-slate-600 dark:text-slate-400 leading-relaxed font-normal border-t border-slate-100 dark:border-slate-800 pt-3">
                        The UnlockRentals application supports Android versions 8.0 (Oreo) and above, and Apple iOS 13.0 and above.
                    </p>
                </details>

            </div>
        </div>

        {{-- Bottom Final CTA Strip --}}
        <div class="rounded-3xl bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-700 p-8 sm:p-12 text-white text-center shadow-xl relative overflow-hidden">
            <div class="relative z-10 max-w-2xl mx-auto">
                <h2 class="text-2xl sm:text-4xl font-black font-display tracking-tight text-white mb-4">
                    Ready to Find Your Dream Home?
                </h2>
                <p class="text-sm sm:text-base text-blue-100 font-normal leading-relaxed mb-8">
                    Join thousands of smart tenants and homeowners across India who have eliminated brokerage commissions with UnlockRentals.
                </p>
                <div class="flex flex-wrap items-center justify-center gap-4">
                    <a href="{{ $googlePlayUrl }}" class="inline-flex items-center gap-2 px-6 py-3.5 bg-white text-slate-900 hover:bg-slate-100 rounded-xl font-bold text-sm shadow-md transition-all">
                        <i class="ph-bold ph-google-play-logo text-lg text-blue-600"></i>
                        <span>Google Play</span>
                    </a>
                    <a href="{{ $appStoreUrl }}" class="inline-flex items-center gap-2 px-6 py-3.5 bg-slate-900 text-white hover:bg-slate-800 rounded-xl font-bold text-sm shadow-md transition-all border border-slate-700">
                        <i class="ph-bold ph-apple-logo text-lg text-white"></i>
                        <span>App Store</span>
                    </a>
                    <a href="{{ $apkDownloadUrl }}" class="inline-flex items-center gap-2 px-6 py-3.5 bg-blue-800/80 hover:bg-blue-800 text-white rounded-xl font-bold text-sm shadow-md transition-all border border-blue-400/40">
                        <i class="ph-bold ph-android-logo text-lg text-emerald-400"></i>
                        <span>Direct APK</span>
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Indus Appstore Intent Script --}}
<script>
(function() {
    window.__launchIndusApp = function(event, fallbackUrl) {
        var ua = navigator.userAgent || '';
        if (!/android/i.test(ua)) {
            return true;
        }
        event.preventDefault();
        var intentUrl = 'intent://launch#Intent;'
            + 'scheme=unlockrentals;'
            + 'package=com.unlockrentals.app;'
            + 'S.browser_fallback_url=' + encodeURIComponent(fallbackUrl) + ';'
            + 'end';
        window.location.href = intentUrl;
        return false;
    };
})();
</script>

{{-- Smart Device Platform Detection --}}
<script>
(function() {
    var ua = navigator.userAgent || navigator.vendor || window.opera || '';
    var notice = document.getElementById('platform-notice');
    var msg = document.getElementById('platform-msg');
    var icon = document.getElementById('platform-icon');
    var quickBtn = document.getElementById('platform-quick-btn');

    if (/android/i.test(ua)) {
        if (notice && msg && icon && quickBtn) {
            icon.className = 'ph-bold ph-android-logo';
            msg.innerHTML = '📱 We detected you are on <strong>Android</strong>. Get the official app for instant room search:';
            var gpBtn = document.getElementById('store-btn-google');
            if (gpBtn && gpBtn.href) {
                quickBtn.href = gpBtn.href;
                quickBtn.setAttribute('target', '_blank');
            }
            notice.classList.remove('hidden');
        }
        var googleStoreBtn = document.getElementById('store-btn-google');
        if (googleStoreBtn) {
            googleStoreBtn.classList.add('ring-2', 'ring-blue-500', 'ring-offset-2', 'dark:ring-offset-slate-900');
        }
    } else if (/iPad|iPhone|iPod/.test(ua) && !window.MSStream) {
        if (notice && msg && icon && quickBtn) {
            icon.className = 'ph-bold ph-apple-logo';
            msg.innerHTML = '📱 We detected you are on <strong>Apple iOS</strong>. Download UnlockRentals for iPhone:';
            var apBtn = document.getElementById('store-btn-apple');
            if (apBtn && apBtn.href) {
                quickBtn.href = apBtn.href;
                quickBtn.setAttribute('target', '_blank');
            }
            notice.classList.remove('hidden');
        }
        var appleStoreBtn = document.getElementById('store-btn-apple');
        if (appleStoreBtn) {
            appleStoreBtn.classList.add('ring-2', 'ring-blue-500', 'ring-offset-2', 'dark:ring-offset-slate-900');
        }
    }
})();
</script>
@endsection
