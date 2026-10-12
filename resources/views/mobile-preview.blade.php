<!DOCTYPE html>
<html class="light" lang="en">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>UnlockRentals - Zero Brokerage Rentals</title>
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;family=Outfit:wght@600;700;800&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
<script>
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            primary: "#2563EB",
            "background-light": "#F8FAFC",
            "background-dark": "#0B1120",
          },
          fontFamily: {
            display: ["'Plus Jakarta Sans'", "sans-serif"],
            outfit: ["'Outfit'", "sans-serif"],
          },
          borderRadius: {
            DEFAULT: "1rem",
          },
        },
      },
    };
  </script>
<style>
    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      -webkit-tap-highlight-color: transparent;
    }
    .hide-scrollbar::-webkit-scrollbar {
      display: none;
    }
    .hide-scrollbar {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }
    .material-symbols-outlined {
      font-variation-settings: 'FILL' 0, 'wght' 450, 'GRAD' 0, 'opsz' 24;
    }
    .material-symbols-filled {
      font-variation-settings: 'FILL' 1, 'wght' 500, 'GRAD' 0, 'opsz' 24;
    }

    /* Keyframe Animations & Micro-interactions */
    @keyframes heroKenBurns {
      0% {
        transform: scale(1) translate(0, 0);
      }
      50% {
        transform: scale(1.08) translate(-1%, -1%);
      }
      100% {
        transform: scale(1) translate(0, 0);
      }
    }
    .animate-ken-burns {
      animation: heroKenBurns 22s ease-in-out infinite alternate;
      will-change: transform;
    }

    @keyframes shimmerSweep {
      0% {
        transform: translateX(-100%);
      }
      50%, 100% {
        transform: translateX(200%);
      }
    }
    .shimmer-sweep::after {
      content: "";
      position: absolute;
      top: 0; right: 0; bottom: 0; left: 0;
      background: linear-gradient(90deg, transparent 0%, rgba(255, 255, 255, 0.35) 50%, transparent 100%);
      transform: translateX(-100%);
      animation: shimmerSweep 3.5s cubic-bezier(0.4, 0, 0.2, 1) infinite;
      pointer-events: none;
    }

    @keyframes badgeGlow {
      0%, 100% {
        box-shadow: 0 0 10px rgba(251, 191, 36, 0.15), inset 0 0 4px rgba(251, 191, 36, 0.2);
        border-color: rgba(252, 211, 77, 0.4);
      }
      50% {
        box-shadow: 0 0 20px rgba(251, 191, 36, 0.4), inset 0 0 10px rgba(251, 191, 36, 0.3);
        border-color: rgba(252, 211, 77, 0.85);
      }
    }
    .animate-badge-glow {
      animation: badgeGlow 3s ease-in-out infinite;
    }

    @keyframes radarPing {
      0% {
        transform: scale(0.95);
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
      }
      70% {
        transform: scale(1);
        box-shadow: 0 0 0 8px rgba(16, 185, 129, 0);
      }
      100% {
        transform: scale(0.95);
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
      }
    }
    .animate-radar-ping {
      animation: radarPing 2s cubic-bezier(0, 0, 0.2, 1) infinite;
    }

    @keyframes pulseRing {
      0% {
        transform: scale(1);
        opacity: 0.8;
      }
      50% {
        transform: scale(1.3);
        opacity: 0;
      }
      100% {
        transform: scale(1.3);
        opacity: 0;
      }
    }
    .animate-pulse-ring {
      animation: pulseRing 2.4s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
    }

    @keyframes floatBadge {
      0%, 100% {
        transform: translateY(0);
      }
      50% {
        transform: translateY(-2.5px);
      }
    }
    .animate-float-badge {
      animation: floatBadge 2.8s ease-in-out infinite;
    }

    @keyframes heartPop {
      0% { transform: scale(1); }
      40% { transform: scale(1.38); }
      70% { transform: scale(0.88); }
      100% { transform: scale(1); }
    }
    .heart-active {
      color: #ef4444 !important;
      font-variation-settings: 'FILL' 1, 'wght' 600, 'GRAD' 0, 'opsz' 24 !important;
      animation: heartPop 0.38s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    /* Staggered entrance animations */
    @keyframes slideUpFade {
      0% {
        opacity: 0;
        transform: translateY(14px);
      }
      100% {
        opacity: 1;
        transform: translateY(0);
      }
    }
    .animate-entry {
      animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
    }
    .entry-delay-1 { animation-delay: 0.05s; }
    .entry-delay-2 { animation-delay: 0.12s; }
    .entry-delay-3 { animation-delay: 0.18s; }
    .entry-delay-4 { animation-delay: 0.24s; }
    .entry-delay-5 { animation-delay: 0.30s; }
    .entry-delay-6 { animation-delay: 0.36s; }

    /* Motion accessibility: reduce animations when requested */
    @media (prefers-reduced-motion: reduce) {
      .animate-ken-burns,
      .shimmer-sweep::after,
      .animate-badge-glow,
      .animate-radar-ping,
      .animate-pulse-ring,
      .animate-float-badge,
      .animate-entry,
      .heart-active {
        animation: none !important;
        transform: none !important;
        transition: none !important;
      }
    }

    body {
      min-height: max(884px, 100dvh);
    }
  </style>
</head>
<body class="bg-slate-100 dark:bg-slate-950 text-slate-800 dark:text-slate-100 min-h-screen flex justify-center py-0 md:py-6 antialiased selection:bg-blue-500 selection:text-white">
<main class="w-full max-w-md bg-background-light dark:bg-background-dark min-h-screen relative shadow-2xl overflow-x-hidden flex flex-col pb-24 md:rounded-[40px] md:border md:border-slate-200 dark:md:border-slate-800">
<header class="sticky top-0 z-40 bg-white/95 dark:bg-background-dark/95 backdrop-blur-md px-4 pt-4 pb-3 border-b border-slate-100 dark:border-slate-800/80 transition-colors animate-entry">
<div class="flex items-center justify-between gap-2 mb-3">
<div class="flex items-center gap-2 cursor-pointer transition-transform active:scale-95 duration-150">
<div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-blue-700 to-indigo-500 flex items-center justify-center text-white shadow-md shadow-blue-500/20 transition-transform hover:rotate-3">
<span class="material-symbols-outlined text-[22px]">apartment</span>
</div>
<div class="flex flex-col">
<div class="flex items-center text-lg font-extrabold tracking-tight text-slate-900 dark:text-white">
<span>Unlock</span><span class="text-primary ml-0.5">Rentals</span>
</div>
</div>
</div>
<div class="flex items-center gap-2">
<button class="flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-primary hover:bg-blue-700 active:scale-95 text-white text-xs font-bold transition-all shadow-sm shadow-blue-500/25">
<span class="material-symbols-outlined text-sm">login</span>
<span>Login</span>
</button>
<button class="w-9 h-9 flex items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 border border-slate-200/70 dark:border-slate-700 hover:bg-slate-200 dark:hover:bg-slate-700 active:scale-90 transition">
<span class="material-symbols-outlined text-xl">menu</span>
</button>
</div>
</div>
<div class="flex items-center justify-between gap-2">
<button class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100/90 dark:bg-slate-800/90 border border-slate-200 dark:border-slate-700 text-xs font-semibold text-slate-800 dark:text-slate-200 hover:bg-slate-200 dark:hover:bg-slate-700 active:scale-95 transition-all group" id="location-picker-btn">
<span class="material-symbols-outlined text-sm text-primary transition-transform group-hover:scale-110">location_on</span>
<span id="location-text">Gurugram / NCR</span>
<span class="material-symbols-outlined text-sm text-slate-400">expand_more</span>
</button>
<div class="flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/60 text-[11px] font-bold text-emerald-600 dark:text-emerald-400 select-none shadow-sm shadow-emerald-500/5">
<span class="relative flex h-2 w-2 items-center justify-center">
<span class="animate-radar-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
<span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-emerald-500"></span>
</span>
<span class="tracking-wide">100% DIRECT OWNER</span>
</div>
</div>
</header>
<div class="flex-1 px-4 pt-3.5 space-y-4">
<div class="text-center px-2 pt-1 animate-entry entry-delay-1">
<h1 class="text-lg font-extrabold text-slate-900 dark:text-white tracking-tight">
<span class="text-primary font-black">Unlock Rent</span> with Zero Brokerage
        </h1>
<p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 max-w-xs mx-auto">
          Rent verified flats, single rooms, PGs &amp; houses directly from genuine owners.
        </p>
</div>
<div class="relative animate-entry entry-delay-2">
<div class="flex items-center bg-white dark:bg-slate-800 rounded-2xl p-1.5 shadow-sm border border-slate-200 dark:border-slate-700 gap-1.5 focus-within:ring-2 focus-within:ring-primary/20 focus-within:border-primary transition-all">
<div class="flex items-center flex-1 pl-2 gap-2 text-slate-400">
<span class="material-symbols-outlined text-slate-400 text-lg transition-colors group-focus-within:text-primary">search</span>
<input class="w-full bg-transparent border-0 p-0 text-xs font-medium text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:ring-0 focus:outline-none" placeholder='Search "Flats in Delhi NCR"' type="text"/>
</div>
<button class="flex items-center gap-1 px-2.5 py-1.5 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-primary text-[11px] font-bold hover:bg-blue-100 active:scale-95 transition whitespace-nowrap">
<span class="material-symbols-outlined text-[13px]">near_me</span>
<span>Near</span>
</button>
<button class="flex items-center gap-1 px-2.5 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-700/60 text-slate-700 dark:text-slate-300 text-[11px] font-bold hover:bg-slate-200 active:scale-95 transition">
<span class="material-symbols-outlined text-[13px]">tune</span>
<span>Filter</span>
<span class="material-symbols-outlined text-[11px]">expand_more</span>
</button>
<button class="w-8 h-8 rounded-xl bg-primary text-white flex items-center justify-center hover:bg-blue-700 active:scale-90 transition shadow-sm shadow-blue-500/25 flex-shrink-0">
<span class="material-symbols-outlined text-sm">arrow_forward</span>
</button>
</div>
</div>
<div class="animate-entry entry-delay-3">
<div class="flex items-start justify-between gap-1 overflow-x-auto hide-scrollbar px-0.5 category-row">
<button class="category-btn flex flex-col items-center gap-1.5 min-w-[54px] group focus:outline-none" data-cat="Rooms">
<div class="w-12 h-12 rounded-2xl bg-blue-600/10 dark:bg-blue-500/20 text-primary group-hover:scale-105 active:scale-95 transition-all flex items-center justify-center border border-blue-200/50 dark:border-blue-700/40 shadow-sm">
<span class="material-symbols-outlined text-2xl group-hover:-translate-y-0.5 transition-transform">meeting_room</span>
</div>
<span class="text-[11px] font-semibold text-slate-700 dark:text-slate-300 group-hover:text-primary transition-colors">Rooms</span>
</button>
<button class="category-btn flex flex-col items-center gap-1.5 min-w-[54px] group focus:outline-none" data-cat="Flats">
<div class="w-12 h-12 rounded-2xl bg-teal-600/10 dark:bg-teal-500/20 text-teal-600 dark:text-teal-400 group-hover:scale-105 active:scale-95 transition-all flex items-center justify-center border border-teal-200/50 dark:border-teal-700/40 shadow-sm">
<span class="material-symbols-outlined text-2xl group-hover:-translate-y-0.5 transition-transform">domain</span>
</div>
<span class="text-[11px] font-semibold text-slate-700 dark:text-slate-300 group-hover:text-teal-600 transition-colors">Flats</span>
</button>
<button class="category-btn flex flex-col items-center gap-1.5 min-w-[54px] group focus:outline-none" data-cat="PG Stays">
<div class="w-12 h-12 rounded-2xl bg-amber-500/15 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400 group-hover:scale-105 active:scale-95 transition-all flex items-center justify-center border border-amber-200/50 dark:border-amber-700/40 shadow-sm">
<span class="material-symbols-outlined text-2xl group-hover:-translate-y-0.5 transition-transform">bed</span>
</div>
<span class="text-[11px] font-semibold text-slate-700 dark:text-slate-300 group-hover:text-amber-600 transition-colors">PG Stays</span>
</button>
<button class="category-btn flex flex-col items-center gap-1.5 min-w-[54px] group focus:outline-none" data-cat="Houses">
<div class="w-12 h-12 rounded-2xl bg-purple-600/10 dark:bg-purple-500/20 text-purple-600 dark:text-purple-400 group-hover:scale-105 active:scale-95 transition-all flex items-center justify-center border border-purple-200/50 dark:border-purple-700/40 shadow-sm">
<span class="material-symbols-outlined text-2xl group-hover:-translate-y-0.5 transition-transform">cottage</span>
</div>
<span class="text-[11px] font-semibold text-slate-700 dark:text-slate-300 group-hover:text-purple-600 transition-colors">Houses</span>
</button>
<button class="category-btn flex flex-col items-center gap-1.5 min-w-[54px] group focus:outline-none" data-cat="Commercial">
<div class="w-12 h-12 rounded-2xl bg-cyan-600/10 dark:bg-cyan-500/20 text-cyan-600 dark:text-cyan-400 group-hover:scale-105 active:scale-95 transition-all flex items-center justify-center border border-cyan-200/50 dark:border-cyan-700/40 shadow-sm">
<span class="material-symbols-outlined text-2xl group-hover:-translate-y-0.5 transition-transform">storefront</span>
</div>
<span class="text-[11px] font-semibold text-slate-700 dark:text-slate-300 group-hover:text-cyan-600 transition-colors">Commercial</span>
</button>
<button class="category-btn flex flex-col items-center gap-1.5 min-w-[54px] group focus:outline-none" data-cat="Plot/Land">
<div class="w-12 h-12 rounded-2xl bg-emerald-600/10 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 group-hover:scale-105 active:scale-95 transition-all flex items-center justify-center border border-emerald-200/50 dark:border-emerald-700/40 shadow-sm">
<span class="material-symbols-outlined text-2xl group-hover:-translate-y-0.5 transition-transform">map</span>
</div>
<span class="text-[11px] font-semibold text-slate-700 dark:text-slate-300 group-hover:text-emerald-600 transition-colors">Plot/Land</span>
</button>
</div>
</div>
<div class="relative animate-entry entry-delay-4"><!-- Interactive Multi-Image Hero Carousel Container -->
<div class="relative group">
<!-- Scrollable Slider Track with Native Smooth Snap -->
<div class="flex overflow-x-auto hide-scrollbar snap-x snap-mandatory rounded-3xl gap-0 scroll-smooth w-full" id="hero-carousel-track">
<!-- Slide 1: Luxury Stays & Designer 1RK / PGs -->
<div class="min-w-full flex-shrink-0 snap-center relative rounded-3xl overflow-hidden min-h-[225px] p-5 flex flex-col justify-between text-white border border-white/20 bg-slate-900 select-none">
<div class="absolute inset-0 overflow-hidden pointer-events-none z-0">
<img alt="Luxury Designer Studio Interior" class="w-full h-full object-cover transform scale-100 animate-ken-burns transition-transform duration-1000" src="https://lh3.googleusercontent.com/aida/AEtjO1WIsrQ3esgXxm-4ezshRtil4niu--oIEtCafHv5RupyQlfXVzrE4CHYow5K_qypHUr6Lw0E9AcdkdTD2jdEKR0acms7bPQcCM3I28KCI9X1mw0gGaaFzbJ2mHA4hUnZS0bi96l1zc7hHkYHrBVouz104XU5AtBgWSOrCZ6qbCiH3jBezX1JiUDSZkxfS5eVMcDhR0oO9Ys52bCnpxTekDILrc0F3bSy_L0y6Ccj6FccSlp7zw4ysg6vaXZr"/>
</div>
<!-- Dark scrim gradient ensuring WCAG AA contrast -->
<div class="absolute inset-0 z-0 bg-gradient-to-t from-black/95 via-[#0b1c3e]/85 to-black/50 pointer-events-none"></div>
<div class="flex items-center justify-between gap-2 relative z-10">
<div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-black/60 backdrop-blur-md border border-amber-300/40 text-[10px] font-bold uppercase tracking-wider text-amber-300 animate-badge-glow select-none">
<span class="material-symbols-outlined text-xs text-amber-300">star</span>
<span>PREMIUM LIVING • 0% BROKERAGE</span>
</div>
<div class="text-[11px] font-semibold text-emerald-300 tracking-tight flex items-center gap-1 bg-black/50 backdrop-blur-sm px-2.5 py-0.5 rounded-full border border-emerald-400/30">
<span class="material-symbols-outlined text-[13px] text-emerald-400">verified</span>
<span>100% Direct Owner</span>
</div>
</div>
<div class="mt-3 relative z-10 transition-transform duration-300 group-hover:translate-x-0.5">
<h2 class="text-xl font-extrabold tracking-tight text-white drop-shadow-md leading-tight font-outfit">Luxury Stays &amp; Designer 1RK / PGs</h2>
<div class="text-2xl font-black text-amber-300 mt-1 tracking-tight flex items-baseline gap-1.5 font-outfit">
<span class="text-xs text-slate-200 font-medium font-sans">Starting at</span>
<span class="text-amber-300 drop-shadow-md font-black text-2xl">₹5,999</span>
<span class="text-xs text-amber-200 font-medium opacity-90 font-sans">/mo</span>
</div>
<p class="text-[11px] text-slate-200 font-medium mt-1.5 leading-relaxed flex items-center gap-1.5">
<span class="material-symbols-outlined text-[13px] text-emerald-400">check_circle</span>
<span>Fully Furnished • High-speed Wi-Fi • Housekeeping</span>
</p>
</div>
<div class="mt-4 pt-1 flex items-center justify-between relative z-10">
<button class="shimmer-sweep relative overflow-hidden flex items-center gap-1.5 px-4 py-2 rounded-full bg-white text-slate-900 font-bold text-xs hover:bg-slate-100 hover:shadow-xl hover:shadow-black/40 active:scale-95 transition-all shadow-lg shadow-black/30 group/btn">
<span class="relative z-10">Explore Stays</span>
<span class="material-symbols-outlined text-sm text-primary font-bold transition-transform duration-200 group-hover/btn:translate-x-1 relative z-10">arrow_forward</span>
</button>
<button class="text-xs font-bold text-amber-300 hover:text-amber-200 underline underline-offset-2 tracking-wide flex items-center gap-1 group/opt active:scale-95 transition-transform">
<span>Zero Deposit Options</span>
<span class="material-symbols-outlined text-xs transition-transform duration-200 group-hover/opt:translate-x-0.5">chevron_right</span>
</button>
</div>
</div>
<!-- Slide 2: Modern 2 & 3 BHK Flats -->
<div class="min-w-full flex-shrink-0 snap-center relative rounded-3xl overflow-hidden min-h-[225px] p-5 flex flex-col justify-between text-white border border-white/20 bg-slate-900 select-none">
<div class="absolute inset-0 overflow-hidden pointer-events-none z-0">
<img alt="Modern 2BHK flat living room interior with city view" class="w-full h-full object-cover transform scale-100 animate-ken-burns transition-transform duration-1000" src="https://lh3.googleusercontent.com/aida/AEtjO1XpwMu026c87OG8bb7y5soJuK3uHi5i2HCRKTixpbsqTxuEe5rx043fm05HHy7Xz1ezX4JdhavoxN9aAetKODWeM6k7X3UNz_Texzp_HOrJDh0jSWQuDkHeE0Ig7rpR810A9sYHtoOXkD6FZv3PrtYesTO2g_-BzROnB8AHc17UghNqR1CLitACMxDv-cyIPBil86k8sew0NafC_mxscc1TW5pDSHfo8XK0Ls8YYjfXUlYsyeQ26ci5aq41"/>
</div>
<!-- Dark scrim gradient ensuring WCAG AA contrast -->
<div class="absolute inset-0 z-0 bg-gradient-to-t from-black/95 via-[#0b1c3e]/85 to-black/50 pointer-events-none"></div>
<div class="flex items-center justify-between gap-2 relative z-10">
<div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-black/60 backdrop-blur-md border border-blue-200/50 text-[10px] font-bold uppercase tracking-wider text-blue-300 select-none">
<span class="material-symbols-outlined text-xs text-blue-400">apartment</span>
<span>GATED SOCIETIES • NO BROKERAGE</span>
</div>
<div class="text-[11px] font-semibold text-emerald-300 tracking-tight flex items-center gap-1 bg-black/50 backdrop-blur-sm px-2.5 py-0.5 rounded-full border border-emerald-400/30">
<span class="material-symbols-outlined text-[13px] text-emerald-400">verified</span>
<span>Verified Landlords</span>
</div>
</div>
<div class="mt-3 relative z-10 transition-transform duration-300 group-hover:translate-x-0.5">
<h2 class="text-xl font-extrabold tracking-tight text-white drop-shadow-md leading-tight font-outfit">Modern 2 &amp; 3 BHK Flats</h2>
<div class="text-2xl font-black text-amber-300 mt-1 tracking-tight flex items-baseline gap-1.5 font-outfit">
<span class="text-xs text-slate-200 font-medium font-sans">Starting at</span>
<span class="text-amber-300 drop-shadow-md font-black text-2xl">₹14,999</span>
<span class="text-xs text-amber-200 font-medium opacity-90 font-sans">/mo</span>
</div>
<p class="text-[11px] text-slate-200 font-medium mt-1.5 leading-relaxed flex items-center gap-1.5">
<span class="material-symbols-outlined text-[13px] text-emerald-400">check_circle</span>
<span>Gym &amp; Pool • Power Backup • Covered Parking</span>
</p>
</div>
<div class="mt-4 pt-1 flex items-center justify-between relative z-10">
<button class="shimmer-sweep relative overflow-hidden flex items-center gap-1.5 px-4 py-2 rounded-full bg-white text-slate-900 font-bold text-xs hover:bg-slate-100 hover:shadow-xl hover:shadow-black/40 active:scale-95 transition-all shadow-lg shadow-black/30 group/btn">
<span class="relative z-10">Browse Flats</span>
<span class="material-symbols-outlined text-sm text-primary font-bold transition-transform duration-200 group-hover/btn:translate-x-1 relative z-10">arrow_forward</span>
</button>
<button class="text-xs font-bold text-blue-300 hover:text-white underline underline-offset-2 tracking-wide flex items-center gap-1 group/opt active:scale-95 transition-transform">
<span>Schedule Visit</span>
<span class="material-symbols-outlined text-xs transition-transform duration-200 group-hover/opt:translate-x-0.5">chevron_right</span>
</button>
</div>
</div>
<!-- Slide 3: Luxury Villas & Independent Houses -->
<div class="min-w-full flex-shrink-0 snap-center relative rounded-3xl overflow-hidden min-h-[225px] p-5 flex flex-col justify-between text-white border border-white/20 bg-slate-900 select-none">
<div class="absolute inset-0 overflow-hidden pointer-events-none z-0">
<img alt="Spacious modern luxury villa with courtyard and pool" class="w-full h-full object-cover transform scale-100 animate-ken-burns transition-transform duration-1000" src="https://lh3.googleusercontent.com/aida/AEtjO1VWM4B0YRvb_LtBUAb2OOgBMYAadrEV-44ohT1XqTQsZFA4iqlX8A4CeXVUdAtam0xBNrzYenmtn4KpZC-4dZw4e8VvXOa4f56W8w1kZ5rSRT3qmwWU2_cfl4DaoYLthj6mYCrfr1R8nL4BtRnSt1UZ_QXNtRc_CpVVQYqolu2wfppQax4HK3uJZkP8kOww1XNG9_xlQGdD3gJsWtRJl97ft8FZqA1R4s7x1UBmXprufRu7njm7NIWN6gO-"/>
</div>
<!-- Dark scrim gradient ensuring WCAG AA contrast -->
<div class="absolute inset-0 z-0 bg-gradient-to-t from-black/95 via-[#0b1c3e]/85 to-black/50 pointer-events-none"></div>
<div class="flex items-center justify-between gap-2 relative z-10">
<div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-black/60 backdrop-blur-md border border-amber-300/40 text-[10px] font-bold uppercase tracking-wider text-amber-300 animate-badge-glow select-none">
<span class="material-symbols-outlined text-xs text-amber-300">villa</span>
<span>LUXURY LIVING • DIRECT SELLER</span>
</div>
<div class="text-[11px] font-semibold text-emerald-300 tracking-tight flex items-center gap-1 bg-black/50 backdrop-blur-sm px-2.5 py-0.5 rounded-full border border-emerald-400/30">
<span class="material-symbols-outlined text-[13px] text-emerald-400">verified</span>
<span>Instant Move-in</span>
</div>
</div>
<div class="mt-3 relative z-10 transition-transform duration-300 group-hover:translate-x-0.5">
<h2 class="text-xl font-extrabold tracking-tight text-white drop-shadow-md leading-tight font-outfit">Luxury Villas &amp; Independent Houses</h2>
<div class="text-2xl font-black text-amber-300 mt-1 tracking-tight flex items-baseline gap-1.5 font-outfit">
<span class="text-xs text-slate-200 font-medium font-sans">Starting at</span>
<span class="text-amber-300 drop-shadow-md font-black text-2xl">₹24,999</span>
<span class="text-xs text-amber-200 font-medium opacity-90 font-sans">/mo</span>
</div>
<p class="text-[11px] text-slate-200 font-medium mt-1.5 leading-relaxed flex items-center gap-1.5">
<span class="material-symbols-outlined text-[13px] text-emerald-400">check_circle</span>
<span>Private Garden • Spacious Courtyard • Prime Location</span>
</p>
</div>
<div class="mt-4 pt-1 flex items-center justify-between relative z-10">
<button class="shimmer-sweep relative overflow-hidden flex items-center gap-1.5 px-4 py-2 rounded-full bg-white text-slate-900 font-bold text-xs hover:bg-slate-100 hover:shadow-xl hover:shadow-black/40 active:scale-95 transition-all shadow-lg shadow-black/30 group/btn">
<span class="relative z-10">View Villas</span>
<span class="material-symbols-outlined text-sm text-primary font-bold transition-transform duration-200 group-hover/btn:translate-x-1 relative z-10">arrow_forward</span>
</button>
<button class="text-xs font-bold text-amber-300 hover:text-amber-200 underline underline-offset-2 tracking-wide flex items-center gap-1 group/opt active:scale-95 transition-transform">
<span>Direct Owner Contact</span>
<span class="material-symbols-outlined text-xs transition-transform duration-200 group-hover/opt:translate-x-0.5">chevron_right</span>
</button>
</div>
</div>
</div>
<!-- Left / Right Navigation Chevrons -->
<button aria-label="Previous slide" class="absolute left-2.5 top-1/2 -translate-y-1/2 z-20 w-8 h-8 rounded-full bg-black/55 backdrop-blur-md text-white border border-white/20 flex items-center justify-center hover:bg-black/80 active:scale-90 transition shadow-md opacity-90 group-hover:opacity-100" id="carousel-prev-btn">
<span class="material-symbols-outlined text-lg">chevron_left</span>
</button>
<button aria-label="Next slide" class="absolute right-2.5 top-1/2 -translate-y-1/2 z-20 w-8 h-8 rounded-full bg-black/55 backdrop-blur-md text-white border border-white/20 flex items-center justify-center hover:bg-black/80 active:scale-90 transition shadow-md opacity-90 group-hover:opacity-100" id="carousel-next-btn">
<span class="material-symbols-outlined text-lg">chevron_right</span>
</button>
</div>
<!-- Interactive Slider Carousel Indicator Dots -->
<div class="flex items-center justify-center gap-1.5 mt-2.5" id="hero-dots-container">
<button aria-label="Slide 1" class="slider-dot w-6 h-2 rounded-full bg-primary transition-all duration-300" data-slide="0"></button>
<button aria-label="Slide 2" class="slider-dot w-2 h-2 rounded-full bg-slate-300 dark:bg-slate-700 transition-all duration-300 hover:bg-slate-400" data-slide="1"></button>
<button aria-label="Slide 3" class="slider-dot w-2 h-2 rounded-full bg-slate-300 dark:bg-slate-700 transition-all duration-300 hover:bg-slate-400" data-slide="2"></button>
</div></div>
<!-- Filter Tabs with Smooth State Toggle -->
<div class="filter-tabs-container flex items-center gap-1.5 overflow-x-auto hide-scrollbar py-0.5 animate-entry entry-delay-5">
<button class="filter-tab px-3.5 py-1.5 rounded-full bg-primary text-white text-xs font-bold shadow-sm shadow-blue-500/20 whitespace-nowrap flex items-center gap-1 transition-all active:scale-95" data-filter="all">
<span class="material-symbols-outlined text-sm">grid_view</span>
<span>All</span>
</button>
<button class="filter-tab px-3.5 py-1.5 rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-xs font-semibold hover:border-slate-300 active:scale-95 whitespace-nowrap flex items-center gap-1 transition-all" data-filter="rent">
<span class="material-symbols-outlined text-sm text-emerald-600">key</span>
<span>For Rent</span>
</button>
<button class="filter-tab px-3.5 py-1.5 rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-xs font-semibold hover:border-slate-300 active:scale-95 whitespace-nowrap flex items-center gap-1 transition-all" data-filter="buy">
<span class="material-symbols-outlined text-sm text-purple-600">home</span>
<span>For Buy</span>
</button>
<button class="filter-tab px-3.5 py-1.5 rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-xs font-semibold hover:border-slate-300 active:scale-95 whitespace-nowrap flex items-center gap-1 transition-all" data-filter="pg">
<span class="material-symbols-outlined text-sm text-amber-500">hotel</span>
<span>PG / Stays</span>
</button>
<button class="filter-tab px-3.5 py-1.5 rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-xs font-semibold hover:border-slate-300 active:scale-95 whitespace-nowrap flex items-center gap-1 transition-all" data-filter="under15k">
<span class="material-symbols-outlined text-sm text-blue-500">currency_rupee</span>
<span>Under ₹15k</span>
</button>
</div>
<div class="grid grid-cols-3 divide-x divide-slate-200 dark:divide-slate-800 bg-white dark:bg-slate-800/80 rounded-2xl py-2.5 px-1 border border-slate-200/80 dark:border-slate-700/60 shadow-sm text-center">
<div class="flex items-center justify-center gap-1 text-[11px] font-bold text-slate-700 dark:text-slate-200 hover:text-emerald-600 transition-colors cursor-pointer">
<span class="material-symbols-outlined text-emerald-600 text-sm">verified</span>
<span>Zero Brokerage</span>
</div>
<div class="flex items-center justify-center gap-1 text-[11px] font-bold text-slate-700 dark:text-slate-200 hover:text-blue-600 transition-colors cursor-pointer">
<span class="material-symbols-outlined text-blue-600 text-sm">shield_person</span>
<span>100% Verified</span>
</div>
<div class="flex items-center justify-center gap-1 text-[11px] font-bold text-slate-700 dark:text-slate-200 hover:text-green-600 transition-colors cursor-pointer">
<span class="material-symbols-outlined text-green-600 text-sm">chat</span>
<span>Direct Contact</span>
</div>
</div>
<!-- Direct Pass Section with Interactive Arrow Controls -->
<div class="rounded-3xl bg-gradient-to-b from-blue-50/80 via-white to-slate-50 dark:from-slate-800/80 dark:via-slate-800 dark:to-slate-900 border border-blue-200/70 dark:border-blue-900/40 p-4 shadow-sm relative overflow-hidden transition-all duration-300 hover:shadow-md animate-entry entry-delay-5">
<div class="flex items-center justify-between mb-3">
<div class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 font-extrabold text-[10px] tracking-wide uppercase border border-emerald-300/60 dark:border-emerald-700/40">
<span class="material-symbols-outlined text-xs">verified_user</span>
<span>ZERO BROKERAGE DIRECT PASS</span>
</div>
<div class="flex items-center gap-1">
<button class="w-6 h-6 rounded-full bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 flex items-center justify-center text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-600 active:scale-85 transition-all" id="pass-prev-btn">
<span class="material-symbols-outlined text-xs">chevron_left</span>
</button>
<button class="w-6 h-6 rounded-full bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 flex items-center justify-center text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-600 active:scale-85 transition-all" id="pass-next-btn">
<span class="material-symbols-outlined text-xs">chevron_right</span>
</button>
</div>
</div>
<div class="flex items-center gap-1.5 mb-3 overflow-x-auto hide-scrollbar">
<button class="px-2.5 py-1 rounded-xl bg-slate-200/70 dark:bg-slate-700 text-slate-700 dark:text-slate-300 text-[11px] font-semibold whitespace-nowrap active:scale-95 transition-all" id="tier-starter-btn">
            Starter <span class="text-[9px] px-1 py-0.2 bg-slate-300 dark:bg-slate-600 rounded text-slate-700 dark:text-slate-200 font-bold">BUDGET</span>
</button>
<button class="px-3 py-1 rounded-xl bg-emerald-600 text-white text-[11px] font-bold whitespace-nowrap shadow-sm shadow-emerald-600/20 flex items-center gap-1 active:scale-95 transition-all ring-2 ring-emerald-500/30" id="tier-buyer-btn">
<span class="material-symbols-outlined text-xs">workspace_premium</span>
<span>Buyer Pass</span>
<span class="bg-emerald-700 text-[10px] px-1.5 py-0.2 rounded-md font-bold">₹399 OFFER</span>
</button>
</div>
<div class="bg-white dark:bg-slate-900 rounded-2xl p-3.5 border border-slate-100 dark:border-slate-700/80 shadow-sm relative transition-all duration-300" id="pass-card-box">
<div class="flex items-start justify-between">
<div class="space-y-1">
<div class="flex items-center gap-1.5 flex-wrap">
<span class="shimmer-sweep relative overflow-hidden px-2 py-0.5 rounded-md bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-300 text-[10px] font-bold border border-teal-200 dark:border-teal-800">
                  SPECIAL OFFER
                </span>
<span class="px-2 py-0.5 rounded-md bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 text-[10px] font-bold">
                  SAVE 99%
                </span>
<span class="animate-float-badge px-2 py-0.5 rounded-md bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 text-[10px] font-bold border border-blue-200/60 dark:border-blue-800/60 shadow-xs">
                  ⚡ UNLOCKED FOR YOU
                </span>
</div>
<h3 class="text-base font-extrabold text-slate-900 dark:text-white pt-1">
                Direct Buyer Pass
              </h3>
<p class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                30 Direct Unlocks · 60 Full Days · Zero Commission
              </p>
</div>
<div class="relative flex-shrink-0 group/avatar">
<img alt="Verified Property Consultant and Owner Liaison" class="w-12 h-12 rounded-full object-cover border-2 border-emerald-500 shadow-sm transition-transform duration-300 group-hover/avatar:scale-105" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBJ7UmyPuevSXjQeUm3xtJWhishk4NlWJia0rXb8r2BPrqL2N9H73RQVCjejLU6nFz3uA4Wc9VqY7SPonoHss_FyRKwVsE_F2suKDUFgG272gia79noCUVWW0D1X7ir6m0JUI_LO_w3q_620rsM36VDc5bCpLTJg7aqk5DCe6DH8RbgtKSTEcXH1p42VQNAXvz75gRbAPbkUiNdKU3uenQnpI0NrqujhIJ2HiAa-9ohOlzXEelpW1Ghvw"/>
<span class="absolute bottom-0 right-0 w-3.5 h-3.5 rounded-full bg-emerald-500 border-2 border-white dark:border-slate-900 flex items-center justify-center">
<span class="material-symbols-outlined text-[8px] text-white">check</span>
</span>
</div>
</div>
<div class="mt-3 pt-2.5 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
<div class="text-[11px] text-slate-500 dark:text-slate-400">
              Direct Verified Owner Contact Access
            </div>
<button class="px-3.5 py-1.5 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold text-xs hover:opacity-90 active:scale-95 transition-all shadow-sm" id="get-pass-btn">
              Get Pass
            </button>
</div>
</div>
</div>
<!-- Trending Properties Section with Horizontal Swipeable Slider Card Carousel Format -->
<div class="space-y-3 pt-1 animate-entry entry-delay-6">
<div class="flex items-center justify-between">
<div class="flex items-center gap-2">
<span class="material-symbols-outlined text-primary text-xl">local_fire_department</span>
<h2 class="text-sm font-extrabold text-slate-900 dark:text-white tracking-tight">Trending Verified Properties</h2>
</div>
<div class="flex items-center gap-1.5">
<button aria-label="Previous property" class="w-6 h-6 rounded-full bg-slate-200/70 dark:bg-slate-800 flex items-center justify-center text-slate-700 dark:text-slate-300 hover:bg-slate-300 active:scale-90 transition" id="prop-prev-btn">
<span class="material-symbols-outlined text-sm">chevron_left</span>
</button>
<button aria-label="Next property" class="w-6 h-6 rounded-full bg-slate-200/70 dark:bg-slate-800 flex items-center justify-center text-slate-700 dark:text-slate-300 hover:bg-slate-300 active:scale-90 transition" id="prop-next-btn">
<span class="material-symbols-outlined text-sm">chevron_right</span>
</button>
<button class="text-[11px] font-bold text-primary hover:underline flex items-center group ml-1">
<span>View All</span>
<span class="material-symbols-outlined text-xs transition-transform duration-200 group-hover:translate-x-0.5">chevron_right</span>
</button>
</div>
</div>
<!-- Horizontal Swipeable Property Carousel with Peek-ahead Cards -->
<div class="flex gap-3 overflow-x-auto hide-scrollbar snap-x snap-mandatory py-1 -mx-1 px-1 scroll-smooth" id="property-carousel-track">
<!-- Property Card 1 (DLF Phase 5 Penthouse) -->
<div class="min-w-[85%] max-w-[85%] flex-shrink-0 snap-center bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 relative group/card flex flex-col justify-between">
<div class="relative h-32 w-full overflow-hidden bg-slate-800">
<img alt="DLF Phase 5 Luxury Penthouse Interior" class="w-full h-full object-cover group-hover/card:scale-105 transition-transform duration-500" src="https://lh3.googleusercontent.com/aida/AEtjO1XpwMu026c87OG8bb7y5soJuK3uHi5i2HCRKTixpbsqTxuEe5rx043fm05HHy7Xz1ezX4JdhavoxN9aAetKODWeM6k7X3UNz_Texzp_HOrJDh0jSWQuDkHeE0Ig7rpR810A9sYHtoOXkD6FZv3PrtYesTO2g_-BzROnB8AHc17UghNqR1CLitACMxDv-cyIPBil86k8sew0NafC_mxscc1TW5pDSHfo8XK0Ls8YYjfXUlYsyeQ26ci5aq41"/>
<div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/30 to-black/30 pointer-events-none"></div>
<div class="absolute top-2.5 left-2.5 flex items-center gap-1 z-10">
<span class="px-2 py-0.5 rounded-full bg-emerald-500/90 backdrop-blur-sm text-white text-[9px] font-bold flex items-center gap-0.5 shadow-sm">
<span class="material-symbols-outlined text-[10px]">verified</span>DIRECT OWNER
</span>
<span class="px-2 py-0.5 rounded-full bg-blue-600/90 backdrop-blur-sm text-white text-[9px] font-bold shadow-sm">READY TO MOVE</span>
</div>
<button aria-label="Save to favorites" class="favorite-toggle absolute top-2 right-2 z-10 w-7 h-7 rounded-full bg-black/40 backdrop-blur-md flex items-center justify-center text-white/90 hover:text-red-500 active:scale-75 transition-all">
<span class="material-symbols-outlined text-base">favorite</span>
</button>
<div class="absolute bottom-2 left-2.5 right-2.5 flex items-baseline justify-between text-white z-10">
<div class="font-outfit font-black text-lg text-amber-300 drop-shadow">₹32,000<span class="text-[10px] font-normal text-slate-200 font-sans">/mo</span></div>
<span class="text-[10px] font-semibold text-emerald-300 bg-black/40 px-2 py-0.5 rounded-md backdrop-blur-sm border border-emerald-400/30">₹0 Brokerage</span>
</div>
</div>
<div class="p-3 flex flex-col justify-between flex-1 space-y-2">
<div>
<h3 class="text-xs font-bold text-slate-900 dark:text-white line-clamp-1 group-hover/card:text-primary transition-colors">Luxury 2 BHK Penthouse</h3>
<p class="text-[11px] text-slate-500 dark:text-slate-400 flex items-center gap-1 mt-0.5">
<span class="material-symbols-outlined text-xs text-primary">location_on</span>DLF Phase 5, Golf Course Rd
</p>
</div>
<div class="flex items-center justify-between text-[10px] text-slate-600 dark:text-slate-300 font-medium py-1.5 border-y border-slate-100 dark:border-slate-800">
<span class="flex items-center gap-0.5"><span class="material-symbols-outlined text-xs text-slate-400">bed</span>2 Beds</span>
<span class="flex items-center gap-0.5"><span class="material-symbols-outlined text-xs text-slate-400">bathtub</span>2 Baths</span>
<span class="flex items-center gap-0.5"><span class="material-symbols-outlined text-xs text-slate-400">square_foot</span>1,250 sq.ft</span>
</div>
<div class="flex items-center justify-between gap-1.5 pt-0.5">
<button class="call-owner-btn flex-1 flex items-center justify-center gap-1 py-1.5 px-2 rounded-xl bg-primary hover:bg-blue-700 active:scale-95 text-white text-[11px] font-bold transition-all shadow-sm shadow-blue-500/20">
<span class="material-symbols-outlined text-xs">call</span><span>Call</span>
</button>
<button class="whatsapp-btn flex-1 flex items-center justify-center gap-1 py-1.5 px-2 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60 text-[11px] font-bold hover:bg-emerald-100 active:scale-95 transition-all">
<span class="material-symbols-outlined text-xs">chat</span><span>WhatsApp</span>
</button>
</div>
</div>
</div>
<!-- Property Card 2 (Sohna Road Studio Suite) -->
<div class="min-w-[85%] max-w-[85%] flex-shrink-0 snap-center bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 relative group/card flex flex-col justify-between">
<div class="relative h-32 w-full overflow-hidden bg-slate-800">
<img alt="Modern 1 RK Studio Suite Interior" class="w-full h-full object-cover group-hover/card:scale-105 transition-transform duration-500" src="https://lh3.googleusercontent.com/aida/AEtjO1WIsrQ3esgXxm-4ezshRtil4niu--oIEtCafHv5RupyQlfXVzrE4CHYow5K_qypHUr6Lw0E9AcdkdTD2jdEKR0acms7bPQcCM3I28KCI9X1mw0gGaaFzbJ2mHA4hUnZS0bi96l1zc7hHkYHrBVouz104XU5AtBgWSOrCZ6qbCiH3jBezX1JiUDSZkxfS5eVMcDhR0oO9Ys52bCnpxTekDILrc0F3bSy_L0y6Ccj6FccSlp7zw4ysg6vaXZr"/>
<div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/30 to-black/30 pointer-events-none"></div>
<div class="absolute top-2.5 left-2.5 flex items-center gap-1 z-10">
<span class="px-2 py-0.5 rounded-full bg-emerald-500/90 backdrop-blur-sm text-white text-[9px] font-bold flex items-center gap-0.5 shadow-sm">
<span class="material-symbols-outlined text-[10px]">verified</span>DIRECT OWNER
</span>
<span class="px-2 py-0.5 rounded-full bg-amber-500/90 backdrop-blur-sm text-white text-[9px] font-bold shadow-sm">ALL-INCLUSIVE</span>
</div>
<button aria-label="Save to favorites" class="favorite-toggle absolute top-2 right-2 z-10 w-7 h-7 rounded-full bg-black/40 backdrop-blur-md flex items-center justify-center text-white/90 hover:text-red-500 active:scale-75 transition-all">
<span class="material-symbols-outlined text-base">favorite</span>
</button>
<div class="absolute bottom-2 left-2.5 right-2.5 flex items-baseline justify-between text-white z-10">
<div class="font-outfit font-black text-lg text-amber-300 drop-shadow">₹12,500<span class="text-[10px] font-normal text-slate-200 font-sans">/mo</span></div>
<span class="text-[10px] font-semibold text-emerald-300 bg-black/40 px-2 py-0.5 rounded-md backdrop-blur-sm border border-emerald-400/30">No Extra Bills</span>
</div>
</div>
<div class="p-3 flex flex-col justify-between flex-1 space-y-2">
<div>
<h3 class="text-xs font-bold text-slate-900 dark:text-white line-clamp-1 group-hover/card:text-primary transition-colors">Modern 1 RK Studio Suite</h3>
<p class="text-[11px] text-slate-500 dark:text-slate-400 flex items-center gap-1 mt-0.5">
<span class="material-symbols-outlined text-xs text-primary">location_on</span>Sohna Road, Sector 49
</p>
</div>
<div class="flex items-center justify-between text-[10px] text-slate-600 dark:text-slate-300 font-medium py-1.5 border-y border-slate-100 dark:border-slate-800">
<span class="flex items-center gap-0.5"><span class="material-symbols-outlined text-xs text-slate-400">bed</span>1 RK</span>
<span class="flex items-center gap-0.5"><span class="material-symbols-outlined text-xs text-slate-400">chair</span>Furnished</span>
<span class="flex items-center gap-0.5"><span class="material-symbols-outlined text-xs text-slate-400">wifi</span>High Wi-Fi</span>
</div>
<div class="flex items-center justify-between gap-1.5 pt-0.5">
<button class="call-owner-btn flex-1 flex items-center justify-center gap-1 py-1.5 px-2 rounded-xl bg-primary hover:bg-blue-700 active:scale-95 text-white text-[11px] font-bold transition-all shadow-sm shadow-blue-500/20">
<span class="material-symbols-outlined text-xs">call</span><span>Call</span>
</button>
<button class="whatsapp-btn flex-1 flex items-center justify-center gap-1 py-1.5 px-2 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60 text-[11px] font-bold hover:bg-emerald-100 active:scale-95 transition-all">
<span class="material-symbols-outlined text-xs">chat</span><span>WhatsApp</span>
</button>
</div>
</div>
</div>
<!-- Property Card 3 (Golf Links Luxury Villa) -->
<div class="min-w-[85%] max-w-[85%] flex-shrink-0 snap-center bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 relative group/card flex flex-col justify-between">
<div class="relative h-32 w-full overflow-hidden bg-slate-800">
<img alt="Golf Course Luxury Independent Villa" class="w-full h-full object-cover group-hover/card:scale-105 transition-transform duration-500" src="https://lh3.googleusercontent.com/aida/AEtjO1VWM4B0YRvb_LtBUAb2OOgBMYAadrEV-44ohT1XqTQsZFA4iqlX8A4CeXVUdAtam0xBNrzYenmtn4KpZC-4dZw4e8VvXOa4f56W8w1kZ5rSRT3qmwWU2_cfl4DaoYLthj6mYCrfr1R8nL4BtRnSt1UZ_QXNtRc_CpVVQYqolu2wfppQax4HK3uJZkP8kOww1XNG9_xlQGdD3gJsWtRJl97ft8FZqA1R4s7x1UBmXprufRu7njm7NIWN6gO-"/>
<div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/30 to-black/30 pointer-events-none"></div>
<div class="absolute top-2.5 left-2.5 flex items-center gap-1 z-10">
<span class="px-2 py-0.5 rounded-full bg-emerald-500/90 backdrop-blur-sm text-white text-[9px] font-bold flex items-center gap-0.5 shadow-sm">
<span class="material-symbols-outlined text-[10px]">verified</span>DIRECT OWNER
</span>
<span class="px-2 py-0.5 rounded-full bg-purple-600/90 backdrop-blur-sm text-white text-[9px] font-bold shadow-sm">EXCLUSIVE VILLA</span>
</div>
<button aria-label="Save to favorites" class="favorite-toggle absolute top-2 right-2 z-10 w-7 h-7 rounded-full bg-black/40 backdrop-blur-md flex items-center justify-center text-white/90 hover:text-red-500 active:scale-75 transition-all">
<span class="material-symbols-outlined text-base">favorite</span>
</button>
<div class="absolute bottom-2 left-2.5 right-2.5 flex items-baseline justify-between text-white z-10">
<div class="font-outfit font-black text-lg text-amber-300 drop-shadow">₹45,000<span class="text-[10px] font-normal text-slate-200 font-sans">/mo</span></div>
<span class="text-[10px] font-semibold text-emerald-300 bg-black/40 px-2 py-0.5 rounded-md backdrop-blur-sm border border-emerald-400/30">Zero Brokerage</span>
</div>
</div>
<div class="p-3 flex flex-col justify-between flex-1 space-y-2">
<div>
<h3 class="text-xs font-bold text-slate-900 dark:text-white line-clamp-1 group-hover/card:text-primary transition-colors">3 BHK Grand Villa &amp; Courtyard</h3>
<p class="text-[11px] text-slate-500 dark:text-slate-400 flex items-center gap-1 mt-0.5">
<span class="material-symbols-outlined text-xs text-primary">location_on</span>Golf Course Ext., Sector 65
</p>
</div>
<div class="flex items-center justify-between text-[10px] text-slate-600 dark:text-slate-300 font-medium py-1.5 border-y border-slate-100 dark:border-slate-800">
<span class="flex items-center gap-0.5"><span class="material-symbols-outlined text-xs text-slate-400">bed</span>3 Beds</span>
<span class="flex items-center gap-0.5"><span class="material-symbols-outlined text-xs text-slate-400">yard</span>Private Lawn</span>
<span class="flex items-center gap-0.5"><span class="material-symbols-outlined text-xs text-slate-400">garage</span>2 Parkings</span>
</div>
<div class="flex items-center justify-between gap-1.5 pt-0.5">
<button class="call-owner-btn flex-1 flex items-center justify-center gap-1 py-1.5 px-2 rounded-xl bg-primary hover:bg-blue-700 active:scale-95 text-white text-[11px] font-bold transition-all shadow-sm shadow-blue-500/20">
<span class="material-symbols-outlined text-xs">call</span><span>Call</span>
</button>
<button class="whatsapp-btn flex-1 flex items-center justify-center gap-1 py-1.5 px-2 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60 text-[11px] font-bold hover:bg-emerald-100 active:scale-95 transition-all">
<span class="material-symbols-outlined text-xs">chat</span><span>WhatsApp</span>
</button>
</div>
</div>
</div>
</div>
</div>
<!-- Why Rent Direct Value Proposition Card -->
<div class="rounded-2xl bg-slate-900 text-white p-4 space-y-3 shadow-lg">
<h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-300 flex items-center gap-1.5">
<span class="material-symbols-outlined text-amber-400 text-sm">verified</span>
<span>Why Rent Direct with UnlockRentals</span>
</h3>
<div class="grid grid-cols-3 gap-2 pt-1">
<div class="flex flex-col items-center text-center p-2 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 transition-colors cursor-default">
<span class="material-symbols-outlined text-emerald-400 text-lg mb-1">money_off</span>
<span class="text-[10px] font-bold">₹0 Brokerage</span>
<span class="text-[8px] text-slate-400">Guaranteed</span>
</div>
<div class="flex flex-col items-center text-center p-2 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 transition-colors cursor-default">
<span class="material-symbols-outlined text-blue-400 text-lg mb-1">gpp_good</span>
<span class="text-[10px] font-bold">100% Verified</span>
<span class="text-[8px] text-slate-400">Real Landlords</span>
</div>
<div class="flex flex-col items-center text-center p-2 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 transition-colors cursor-default">
<span class="material-symbols-outlined text-amber-400 text-lg mb-1">description</span>
<span class="text-[10px] font-bold">Free Draft</span>
<span class="text-[8px] text-slate-400">Legal Agreement</span>
</div>
</div>
</div>
</div>
<!-- Bottom Navigation Bar with Radiant Pulsing Post Ad Action -->
<nav class="fixed md:absolute bottom-0 left-0 right-0 max-w-md mx-auto bg-white/95 dark:bg-slate-900/95 backdrop-blur-lg border-t border-slate-200/80 dark:border-slate-800 px-3 py-2 z-50 flex items-center justify-around shadow-2xl">
<a class="nav-item flex flex-col items-center gap-0.5 text-primary transition-all duration-200 active:scale-90" href="{{ url('/') }}">
<span class="material-symbols-filled text-2xl transition-transform">home</span>
<span class="text-[10px] font-bold">Home</span>
</a>
<a class="nav-item flex flex-col items-center gap-0.5 text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-all duration-200 active:scale-90" href="{{ route('properties.index') }}">
<span class="material-symbols-outlined text-2xl transition-transform">explore</span>
<span class="text-[10px] font-semibold">Explore</span>
</a>
<div class="relative -top-5 flex flex-col items-center group">
<div class="relative flex items-center justify-center">
<!-- Radiant glowing ring aura around + Post Ad button -->
<span class="animate-pulse-ring absolute inset-0 rounded-full bg-primary opacity-60"></span>
<a href="{{ route('properties.create') }}" class="relative w-13 h-13 p-3 rounded-full bg-primary text-white shadow-xl shadow-blue-500/50 hover:bg-blue-700 active:scale-90 transition-all flex items-center justify-center border-4 border-white dark:border-background-dark focus:outline-none">
<span class="material-symbols-outlined text-2xl transition-transform duration-300 group-hover:rotate-90">add</span>
</a>
</div>
<span class="text-[10px] font-bold text-primary mt-1 tracking-tight">Post Ad</span>
</div>
<a class="nav-item flex flex-col items-center gap-0.5 text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-all duration-200 active:scale-90" href="{{ route('dashboard') }}">
<span class="material-symbols-outlined text-2xl transition-transform">account_circle</span>
<span class="text-[10px] font-semibold">Account</span>
</a>
<a class="nav-item flex flex-col items-center gap-0.5 text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-all duration-200 active:scale-90" href="{{ route('services.index') }}">
<span class="material-symbols-outlined text-2xl transition-transform">home_repair_service</span>
<span class="text-[10px] font-semibold">Services</span>
</a>
</nav>
</main>
<!-- Vanilla JavaScript for Micro-interactions -->
<script>
  document.addEventListener('DOMContentLoaded', () => {
    // 1. Hero Slider Pagination & Controls
    const heroTrack = document.getElementById('hero-carousel-track');
    const heroDots = document.querySelectorAll('.slider-dot');
    const heroPrevBtn = document.getElementById('carousel-prev-btn');
    const heroNextBtn = document.getElementById('carousel-next-btn');
    let heroCurrentIndex = 0;
    const heroSlideCount = heroDots.length;

    function updateHeroDots(index) {
      heroDots.forEach((dot, idx) => {
        if (idx === index) {
          dot.classList.remove('w-2', 'bg-slate-300', 'dark:bg-slate-700');
          dot.classList.add('w-6', 'bg-primary');
        } else {
          dot.classList.remove('w-6', 'bg-primary');
          dot.classList.add('w-2', 'bg-slate-300', 'dark:bg-slate-700');
        }
      });
      heroCurrentIndex = index;
    }

    function scrollHeroToIndex(index) {
      if (!heroTrack) return;
      const slideWidth = heroTrack.clientWidth;
      heroTrack.scrollTo({
        left: index * slideWidth,
        behavior: 'smooth'
      });
      updateHeroDots(index);
    }

    heroDots.forEach((dot, idx) => {
      dot.addEventListener('click', () => scrollHeroToIndex(idx));
    });

    if (heroPrevBtn) {
      heroPrevBtn.addEventListener('click', () => {
        const targetIndex = (heroCurrentIndex - 1 + heroSlideCount) % heroSlideCount;
        scrollHeroToIndex(targetIndex);
      });
    }

    if (heroNextBtn) {
      heroNextBtn.addEventListener('click', () => {
        const targetIndex = (heroCurrentIndex + 1) % heroSlideCount;
        scrollHeroToIndex(targetIndex);
      });
    }

    // Update dots on swipe/scroll
    if (heroTrack) {
      let heroScrollTimeout;
      heroTrack.addEventListener('scroll', () => {
        clearTimeout(heroScrollTimeout);
        heroScrollTimeout = setTimeout(() => {
          const slideWidth = heroTrack.clientWidth;
          if (slideWidth > 0) {
            const index = Math.round(heroTrack.scrollLeft / slideWidth);
            updateHeroDots(index);
          }
        }, 80);
      });
    }

    // Auto rotate hero slide every 5 seconds
    let heroAutoPlay = setInterval(() => {
      const nextIndex = (heroCurrentIndex + 1) % heroSlideCount;
      scrollHeroToIndex(nextIndex);
    }, 5000);

    // Pause autoplay on user touch
    if (heroTrack) {
      heroTrack.addEventListener('touchstart', () => clearInterval(heroAutoPlay), { passive: true });
    }

    // 2. Property Card Carousel Horizontal Navigation
    const propTrack = document.getElementById('property-carousel-track');
    const propPrevBtn = document.getElementById('prop-prev-btn');
    const propNextBtn = document.getElementById('prop-next-btn');

    if (propTrack && propPrevBtn && propNextBtn) {
      propPrevBtn.addEventListener('click', () => {
        propTrack.scrollBy({ left: -260, behavior: 'smooth' });
      });
      propNextBtn.addEventListener('click', () => {
        propTrack.scrollBy({ left: 260, behavior: 'smooth' });
      });
    }

    // 3. Favorite Heart Button Toggle Micro-interaction
    const favoriteButtons = document.querySelectorAll('.favorite-toggle');
    favoriteButtons.forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.stopPropagation();
        const icon = btn.querySelector('.material-symbols-outlined');
        if (icon) {
          icon.classList.toggle('heart-active');
        }
      });
    });

    // 4. Filter Category Pills Toggle
    const filterTabs = document.querySelectorAll('.filter-tab');
    filterTabs.forEach(tab => {
      tab.addEventListener('click', () => {
        filterTabs.forEach(t => {
          t.className = 'filter-tab px-3.5 py-1.5 rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-xs font-semibold hover:border-slate-300 active:scale-95 whitespace-nowrap flex items-center gap-1 transition-all';
        });
        tab.className = 'filter-tab px-3.5 py-1.5 rounded-full bg-primary text-white text-xs font-bold shadow-sm shadow-blue-500/20 whitespace-nowrap flex items-center gap-1 transition-all active:scale-95';
      });
    });

    // 5. Pass Arrows & Tier Selector Interaction
    const passCardBox = document.getElementById('pass-card-box');
    const prevBtn = document.getElementById('pass-prev-btn');
    const nextBtn = document.getElementById('pass-next-btn');
    const starterBtn = document.getElementById('tier-starter-btn');
    const buyerBtn = document.getElementById('tier-buyer-btn');

    function animateCardSwitch() {
      if (!passCardBox) return;
      passCardBox.classList.add('scale-95', 'opacity-80');
      setTimeout(() => {
        passCardBox.classList.remove('scale-95', 'opacity-80');
      }, 180);
    }

    if (prevBtn) prevBtn.addEventListener('click', animateCardSwitch);
    if (nextBtn) nextBtn.addEventListener('click', animateCardSwitch);

    if (starterBtn && buyerBtn) {
      starterBtn.addEventListener('click', () => {
        starterBtn.classList.remove('bg-slate-200/70', 'dark:bg-slate-700', 'text-slate-700', 'dark:text-slate-300');
        starterBtn.classList.add('bg-emerald-600', 'text-white', 'font-bold', 'ring-2', 'ring-emerald-500/30');
        buyerBtn.classList.remove('bg-emerald-600', 'text-white', 'ring-2', 'ring-emerald-500/30');
        buyerBtn.classList.add('bg-slate-200/70', 'dark:bg-slate-700', 'text-slate-700', 'dark:text-slate-300');
        animateCardSwitch();
      });

      buyerBtn.addEventListener('click', () => {
        buyerBtn.classList.add('bg-emerald-600', 'text-white', 'font-bold', 'ring-2', 'ring-emerald-500/30');
        buyerBtn.classList.remove('bg-slate-200/70', 'dark:bg-slate-700', 'text-slate-700', 'dark:text-slate-300');
        starterBtn.classList.add('bg-slate-200/70', 'dark:bg-slate-700', 'text-slate-700', 'dark:text-slate-300');
        starterBtn.classList.remove('bg-emerald-600', 'text-white', 'font-bold', 'ring-2', 'ring-emerald-500/30');
        animateCardSwitch();
      });
    }

    // 6. Call & WhatsApp Feedback Trigger
    const callBtns = document.querySelectorAll('.call-owner-btn');
    const waBtns = document.querySelectorAll('.whatsapp-btn');

    callBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        const span = btn.querySelector('span:not(.material-symbols-outlined)');
        if (span) {
          const orig = span.textContent;
          span.textContent = 'Calling...';
          setTimeout(() => { span.textContent = orig; }, 1500);
        }
      });
    });

    waBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        const span = btn.querySelector('span:not(.material-symbols-outlined)');
        if (span) {
          const orig = span.textContent;
          span.textContent = 'Opening...';
          setTimeout(() => { span.textContent = orig; }, 1500);
        }
      });
    });

    // 7. Location dropdown micro-toggle
    const locBtn = document.getElementById('location-picker-btn');
    const locText = document.getElementById('location-text');
    if (locBtn && locText) {
      const locations = ['Gurugram / NCR', 'South Delhi', 'Noida / Gr. Noida', 'Bengaluru Central'];
      let locIdx = 0;
      locBtn.addEventListener('click', () => {
        locIdx = (locIdx + 1) % locations.length;
        locText.textContent = locations[locIdx];
      });
    }

    // 8. Bottom navigation active tab switcher
    const navItems = document.querySelectorAll('.nav-item');
    navItems.forEach(item => {
      item.addEventListener('click', (e) => {
        const href = item.getAttribute('href');
        if (href && href !== '#' && !href.startsWith('javascript:')) {
          return; // Let standard link navigation happen
        }
        e.preventDefault();
        navItems.forEach(n => {
          n.className = 'nav-item flex flex-col items-center gap-0.5 text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-all duration-200 active:scale-90';
          const icon = n.querySelector('.material-symbols-filled, .material-symbols-outlined');
          if (icon) {
            icon.className = 'material-symbols-outlined text-2xl transition-transform';
          }
        });
        item.className = 'nav-item flex flex-col items-center gap-0.5 text-primary transition-all duration-200 active:scale-90';
        const activeIcon = item.querySelector('.material-symbols-outlined, .material-symbols-filled');
        if (activeIcon) {
          activeIcon.className = 'material-symbols-filled text-2xl transition-transform';
        }
      });
    });
  });
</script>
</body>
</html>
