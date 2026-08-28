@extends('layouts.app')

@section('title', 'How It Works - Rental & Listing Process | UnlockRentals')
@section('meta_description', 'Discover the complete step-by-step rental process on UnlockRentals. Zero brokerage, 100% verified properties, direct owner connections, and digital lease agreements.')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-950 pt-24 pb-24 font-sans text-slate-800 dark:text-slate-200">
    
    {{-- Hero Section --}}
    <section class="relative overflow-hidden pt-8 pb-16 lg:pb-24">
        {{-- Ambient decorative glows --}}
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-96 bg-gradient-to-b from-blue-500/10 via-indigo-500/5 to-transparent blur-3xl pointer-events-none -z-10"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 border border-blue-200/60 dark:border-blue-800/60 mb-6 shadow-sm">
                <i class="ph-bold ph-git-merge text-sm"></i>
                End-to-End Process Flow
            </div>
            
            <h1 class="text-4xl sm:text-6xl font-black tracking-tight text-slate-900 dark:text-white max-w-4xl mx-auto leading-[1.15] mb-6 font-['Playfair_Display',serif]">
                A Transparent Journey to <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-500">Your Next Space</span>
            </h1>
            
            <p class="text-lg sm:text-xl text-slate-600 dark:text-slate-300 max-w-2xl mx-auto mb-10 leading-relaxed">
                UnlockRentals replaces pushy middlemen with a direct, zero-brokerage platform. Explore our streamlined process for tenants and property owners below.
            </p>

            {{-- Interactive Persona Switcher Tabs --}}
            <div class="inline-flex p-1.5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-lg shadow-slate-200/50 dark:shadow-none" role="tablist">
                <button type="button" onclick="switchProcessTab('tenant')" id="tab-btn-tenant" class="process-tab-btn flex items-center gap-2.5 px-6 py-3 rounded-xl text-sm font-bold transition-all duration-300 bg-blue-600 text-white shadow-md shadow-blue-500/25">
                    <i class="ph-bold ph-house-line text-lg"></i>
                    <span>For Renters & Tenants</span>
                </button>
                <button type="button" onclick="switchProcessTab('owner')" id="tab-btn-owner" class="process-tab-btn flex items-center gap-2.5 px-6 py-3 rounded-xl text-sm font-bold transition-all duration-300 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white">
                    <i class="ph-bold ph-key text-lg"></i>
                    <span>For Property Owners</span>
                </button>
            </div>
        </div>
    </section>

    {{-- Process Steps Section --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-24">

        {{-- ==================== TENANT PROCESS FLOW ==================== --}}
        <div id="process-flow-tenant" class="process-flow-container transition-all duration-500">
            <div class="text-center mb-12">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white">
                    4 Simple Steps to Move In Without Paying Brokerage
                </h2>
                <p class="text-slate-500 dark:text-slate-400 text-sm sm:text-base mt-2">
                    Save thousands of rupees in commission and deal directly with genuine verified owners.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 relative">
                {{-- Step 1 --}}
                <div class="group relative bg-white dark:bg-slate-900 rounded-3xl p-7 border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-6">
                            <div class="w-14 h-14 rounded-2xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center text-2xl group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300 shadow-sm">
                                <i class="ph-bold ph-magnifying-glass"></i>
                            </div>
                            <span class="text-3xl font-black text-slate-200 dark:text-slate-800 group-hover:text-blue-600/30 transition-colors font-mono">01</span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">
                            Search & Discover
                        </h3>
                        <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed mb-4">
                            Browse high-res galleries, filter by locality, budget, furnishings, and property type (flats, villas, commercial shops).
                        </p>
                    </div>
                    <div class="pt-4 border-t border-slate-100 dark:border-slate-800/80 flex flex-wrap gap-1.5">
                        <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">100% Verified</span>
                        <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">Detailed Specs</span>
                    </div>
                </div>

                {{-- Step 2 --}}
                <div class="group relative bg-white dark:bg-slate-900 rounded-3xl p-7 border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-6">
                            <div class="w-14 h-14 rounded-2xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-2xl group-hover:scale-110 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300 shadow-sm">
                                <i class="ph-bold ph-calendar-check"></i>
                            </div>
                            <span class="text-3xl font-black text-slate-200 dark:text-slate-800 group-hover:text-indigo-600/30 transition-colors font-mono">02</span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">
                            Schedule Free Visit
                        </h3>
                        <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed mb-4">
                            Choose your preferred date and time slot for a private property walkthrough. No agent pushing you into sudden decisions.
                        </p>
                    </div>
                    <div class="pt-4 border-t border-slate-100 dark:border-slate-800/80 flex flex-wrap gap-1.5">
                        <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">Free Booking</span>
                        <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">No Pressure</span>
                    </div>
                </div>

                {{-- Step 3 --}}
                <div class="group relative bg-white dark:bg-slate-900 rounded-3xl p-7 border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-6">
                            <div class="w-14 h-14 rounded-2xl bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 flex items-center justify-center text-2xl group-hover:scale-110 group-hover:bg-amber-600 group-hover:text-white transition-all duration-300 shadow-sm">
                                <i class="ph-bold ph-lock-key-open"></i>
                            </div>
                            <span class="text-3xl font-black text-slate-200 dark:text-slate-800 group-hover:text-amber-600/30 transition-colors font-mono">03</span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">
                            Unlock Owner Contact
                        </h3>
                        <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed mb-4">
                            Activate an affordable rental plan to unlock direct verified landlord phone & WhatsApp numbers. Discuss terms directly.
                        </p>
                    </div>
                    <div class="pt-4 border-t border-slate-100 dark:border-slate-800/80 flex flex-wrap gap-1.5">
                        <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">Direct WhatsApp</span>
                        <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">0% Commission</span>
                    </div>
                </div>

                {{-- Step 4 --}}
                <div class="group relative bg-white dark:bg-slate-900 rounded-3xl p-7 border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-6">
                            <div class="w-14 h-14 rounded-2xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-2xl group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-300 shadow-sm">
                                <i class="ph-bold ph-key"></i>
                            </div>
                            <span class="text-3xl font-black text-slate-200 dark:text-slate-800 group-hover:text-emerald-600/30 transition-colors font-mono">04</span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">
                            Finalize & Move In
                        </h3>
                        <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed mb-4">
                            Complete your digital agreement, transfer security deposit safely, collect keys, and settle smoothly into your new space.
                        </p>
                    </div>
                    <div class="pt-4 border-t border-slate-100 dark:border-slate-800/80 flex flex-wrap gap-1.5">
                        <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">Legal Agreement</span>
                        <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">Move-in Ready</span>
                    </div>
                </div>
            </div>

            <div class="mt-12 text-center">
                <a href="{{ route('properties.index') }}" class="inline-flex items-center gap-2 px-8 py-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold shadow-lg shadow-blue-500/25 hover:shadow-blue-500/35 transition-all" title="Start Searching Rental Homes">
                    <i class="ph-bold ph-magnifying-glass text-lg"></i>
                    <span>Start Searching Rental Homes</span>
                </a>
            </div>
        </div>

        {{-- ==================== OWNER PROCESS FLOW ==================== --}}
        <div id="process-flow-owner" class="process-flow-container hidden transition-all duration-500">
            <div class="text-center mb-12">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white">
                    4 Simple Steps to List and Rent Your Property Fast
                </h2>
                <p class="text-slate-500 dark:text-slate-400 text-sm sm:text-base mt-2">
                    Attract high-intent, screened tenants without paying hefty broker commissions or fielding random calls.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 relative">
                {{-- Step 1 --}}
                <div class="group relative bg-white dark:bg-slate-900 rounded-3xl p-7 border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-6">
                            <div class="w-14 h-14 rounded-2xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center text-2xl group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300 shadow-sm">
                                <i class="ph-bold ph-plus-circle"></i>
                            </div>
                            <span class="text-3xl font-black text-slate-200 dark:text-slate-800 group-hover:text-blue-600/30 transition-colors font-mono">01</span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">
                            Post in 2 Minutes
                        </h3>
                        <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed mb-4">
                            Upload high-res photos, set rent expectation, maintenance fees, and preferred tenant types (families, bachelors, commercial).
                        </p>
                    </div>
                    <div class="pt-4 border-t border-slate-100 dark:border-slate-800/80 flex flex-wrap gap-1.5">
                        <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">Quick Upload</span>
                        <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">Free Listing</span>
                    </div>
                </div>

                {{-- Step 2 --}}
                <div class="group relative bg-white dark:bg-slate-900 rounded-3xl p-7 border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-6">
                            <div class="w-14 h-14 rounded-2xl bg-violet-50 dark:bg-violet-900/30 text-violet-600 dark:text-violet-400 flex items-center justify-center text-2xl group-hover:scale-110 group-hover:bg-violet-600 group-hover:text-white transition-all duration-300 shadow-sm">
                                <i class="ph-bold ph-shield-check"></i>
                            </div>
                            <span class="text-3xl font-black text-slate-200 dark:text-slate-800 group-hover:text-violet-600/30 transition-colors font-mono">02</span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">
                            Instant Verification
                        </h3>
                        <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed mb-4">
                            Our review team reviews your property details to award the coveted "Verified Owner" trust badge, boosting listing visibility by 3x.
                        </p>
                    </div>
                    <div class="pt-4 border-t border-slate-100 dark:border-slate-800/80 flex flex-wrap gap-1.5">
                        <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">Trust Badge</span>
                        <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">Priority SEO</span>
                    </div>
                </div>

                {{-- Step 3 --}}
                <div class="group relative bg-white dark:bg-slate-900 rounded-3xl p-7 border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-6">
                            <div class="w-14 h-14 rounded-2xl bg-sky-50 dark:bg-sky-900/30 text-sky-600 dark:text-sky-400 flex items-center justify-center text-2xl group-hover:scale-110 group-hover:bg-sky-600 group-hover:text-white transition-all duration-300 shadow-sm">
                                <i class="ph-bold ph-chats-circle"></i>
                            </div>
                            <span class="text-3xl font-black text-slate-200 dark:text-slate-800 group-hover:text-sky-600/30 transition-colors font-mono">03</span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">
                            Direct Tenant Inquiries
                        </h3>
                        <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed mb-4">
                            Receive direct inquiries from genuine tenants via dashboard, phone, or WhatsApp. You decide who to invite for a viewing.
                        </p>
                    </div>
                    <div class="pt-4 border-t border-slate-100 dark:border-slate-800/80 flex flex-wrap gap-1.5">
                        <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">No Spam Calls</span>
                        <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">Screened Renters</span>
                    </div>
                </div>

                {{-- Step 4 --}}
                <div class="group relative bg-white dark:bg-slate-900 rounded-3xl p-7 border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-6">
                            <div class="w-14 h-14 rounded-2xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-2xl group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-300 shadow-sm">
                                <i class="ph-bold ph-handshake"></i>
                            </div>
                            <span class="text-3xl font-black text-slate-200 dark:text-slate-800 group-hover:text-emerald-600/30 transition-colors font-mono">04</span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">
                            Close & Enjoy Zero Fees
                        </h3>
                        <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed mb-4">
                            Hand over keys and start collecting rent. UnlockRentals charges 0% commission from owners on completed rentals.
                        </p>
                    </div>
                    <div class="pt-4 border-t border-slate-100 dark:border-slate-800/80 flex flex-wrap gap-1.5">
                        <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">Zero Commission</span>
                        <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">Full Yield</span>
                    </div>
                </div>
            </div>

            <div class="mt-12 text-center">
                <a href="{{ route('properties.create') }}" class="inline-flex items-center gap-2 px-8 py-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold shadow-lg shadow-blue-500/25 hover:shadow-blue-500/35 transition-all" title="List Your Property Free">
                    <i class="ph-bold ph-plus-circle text-lg"></i>
                    <span>List Your Property Free</span>
                </a>
            </div>
        </div>
    </section>

    {{-- Comparison Section: Traditional Broker vs UnlockRentals --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-24">
        <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 sm:p-12 border border-slate-200/80 dark:border-slate-800 shadow-xl overflow-hidden relative">
            <div class="text-center max-w-2xl mx-auto mb-10">
                <span class="text-xs font-black uppercase tracking-widest text-blue-600 dark:text-blue-400">Why It Matters</span>
                <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 dark:text-white mt-1 font-['Playfair_Display',serif]">
                    Traditional Rental Agents vs. UnlockRentals
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Traditional --}}
                <div class="rounded-2xl p-6 bg-rose-50/50 dark:bg-rose-950/20 border border-rose-200/60 dark:border-rose-900/40">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="w-10 h-10 rounded-xl bg-rose-100 dark:bg-rose-900/40 text-rose-600 flex items-center justify-center text-xl">
                            <i class="ph-bold ph-x-circle"></i>
                        </span>
                        <h3 class="text-lg font-bold text-rose-900 dark:text-rose-300">Traditional Brokerage</h3>
                    </div>
                    <ul class="space-y-4 text-sm text-slate-700 dark:text-slate-300">
                        <li class="flex items-start gap-3">
                            <i class="ph-bold ph-x text-rose-500 mt-1"></i>
                            <span><strong>1 to 2 Months Rent</strong> charged as non-refundable brokerage fee from both tenant & landlord.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="ph-bold ph-x text-rose-500 mt-1"></i>
                            <span><strong>Inaccurate Photos:</strong> Low-res pictures or bait-and-switch listings that look nothing like reality.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="ph-bold ph-x text-rose-500 mt-1"></i>
                            <span><strong>Persistent Spam Calls:</strong> Agents badgering you at odd hours to push unwanted listings.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="ph-bold ph-x text-rose-500 mt-1"></i>
                            <span><strong>No Legal Assistance:</strong> Rudimentary verbal promises without standard digital verification.</span>
                        </li>
                    </ul>
                </div>

                {{-- UnlockRentals --}}
                <div class="rounded-2xl p-6 bg-emerald-50/50 dark:bg-emerald-950/20 border border-emerald-200/60 dark:border-emerald-900/40 relative">
                    <div class="absolute top-4 right-4 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-600 text-white shadow-sm">
                        Recommended
                    </div>
                    <div class="flex items-center gap-3 mb-6">
                        <span class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 flex items-center justify-center text-xl">
                            <i class="ph-bold ph-check-circle"></i>
                        </span>
                        <h3 class="text-lg font-bold text-emerald-900 dark:text-emerald-300">The UnlockRentals Way</h3>
                    </div>
                    <ul class="space-y-4 text-sm text-slate-700 dark:text-slate-300">
                        <li class="flex items-start gap-3">
                            <i class="ph-bold ph-check text-emerald-600 mt-1"></i>
                            <span><strong>Zero Brokerage Forever:</strong> Pay once for an affordable pass starting at ₹199, zero commission.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="ph-bold ph-check text-emerald-600 mt-1"></i>
                            <span><strong>100% Genuine Listings:</strong> Direct listings screened and tagged with property attributes.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="ph-bold ph-check text-emerald-600 mt-1"></i>
                            <span><strong>Direct Owner Contact:</strong> Talk directly to landlords without middleman bias or pressure.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="ph-bold ph-check text-emerald-600 mt-1"></i>
                            <span><strong>Online Visit Scheduling:</strong> Book appointments with owners seamlessly from your screen.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- FAQs Section --}}
    <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mb-24">
        <div class="text-center mb-12">
            <span class="text-xs font-black uppercase tracking-widest text-blue-600 dark:text-blue-400">Got Questions?</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white mt-1 font-['Playfair_Display',serif]">
                Frequently Asked Questions
            </h2>
        </div>

        <div class="space-y-4" x-data="{ activeFaq: null }">
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-6 shadow-sm">
                <button @click="activeFaq = activeFaq === 1 ? null : 1" class="w-full flex items-center justify-between text-left font-bold text-slate-900 dark:text-white text-base">
                    <span>How does UnlockRentals work without brokers?</span>
                    <i class="ph-bold ph-caret-down text-lg text-blue-600 transition-transform duration-200" :class="activeFaq === 1 ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="activeFaq === 1" class="mt-4 text-sm text-slate-600 dark:text-slate-300 leading-relaxed pt-3 border-t border-slate-100 dark:border-slate-800">
                    UnlockRentals connects tenants and landlords directly. Owners list properties for free, and tenants can browse verified properties without commission. By eliminating brokers, tenants save tens of thousands of rupees on each transaction.
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-6 shadow-sm">
                <button @click="activeFaq = activeFaq === 2 ? null : 2" class="w-full flex items-center justify-between text-left font-bold text-slate-900 dark:text-white text-base">
                    <span>How does the Contact Unlock Pass work?</span>
                    <i class="ph-bold ph-caret-down text-lg text-blue-600 transition-transform duration-200" :class="activeFaq === 2 ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="activeFaq === 2" class="mt-4 text-sm text-slate-600 dark:text-slate-300 leading-relaxed pt-3 border-t border-slate-100 dark:border-slate-800">
                    To maintain privacy and filter spammers, landlord phone and WhatsApp numbers are revealed via an active plan (such as our Silver, Gold, or Platinum pass). Each plan lets you unlock verified owner details directly without paying any brokerage.
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-6 shadow-sm">
                <button @click="activeFaq = activeFaq === 3 ? null : 3" class="w-full flex items-center justify-between text-left font-bold text-slate-900 dark:text-white text-base">
                    <span>Is it free for owners to list property?</span>
                    <i class="ph-bold ph-caret-down text-lg text-blue-600 transition-transform duration-200" :class="activeFaq === 3 ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="activeFaq === 3" class="mt-4 text-sm text-slate-600 dark:text-slate-300 leading-relaxed pt-3 border-t border-slate-100 dark:border-slate-800">
                    Yes! Listing your residential flat, independent villa, or commercial shop space on UnlockRentals is completely free. We do not deduct any brokerage fee when you rent out your property.
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-6 shadow-sm">
                <button @click="activeFaq = activeFaq === 4 ? null : 4" class="w-full flex items-center justify-between text-left font-bold text-slate-900 dark:text-white text-base">
                    <span>Can I schedule property visits before deciding?</span>
                    <i class="ph-bold ph-caret-down text-lg text-blue-600 transition-transform duration-200" :class="activeFaq === 4 ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="activeFaq === 4" class="mt-4 text-sm text-slate-600 dark:text-slate-300 leading-relaxed pt-3 border-t border-slate-100 dark:border-slate-800">
                    Absolutely! You can use our "Book a Visit" feature on any property page to request an inspection slot at a time that suits you and the owner.
                </div>
            </div>
        </div>
    </section>

    {{-- Bottom CTA Strip --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="rounded-3xl bg-gradient-to-r from-blue-600 to-indigo-700 p-8 sm:p-14 text-white text-center shadow-2xl relative overflow-hidden">
            <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
            
            <h2 class="text-3xl sm:text-5xl font-black mb-4 font-['Playfair_Display',serif]">
                Experience Smarter Renting Today
            </h2>
            <p class="text-base sm:text-lg text-blue-100 max-w-xl mx-auto mb-8">
                Join thousands of tenants and property owners finding the perfect match with zero brokerage fees.
            </p>
            <div class="flex flex-wrap items-center justify-center gap-4">
                <a href="{{ route('properties.index') }}" class="px-8 py-4 rounded-xl bg-white text-blue-700 font-bold hover:bg-blue-50 transition shadow-lg" title="Discover Properties">
                    Discover Properties
                </a>
                <a href="{{ route('plans.index') }}" class="px-8 py-4 rounded-xl bg-blue-800/80 hover:bg-blue-800 text-white font-bold border border-white/20 transition" title="View Pricing Plans">
                    View Pricing Plans
                </a>
            </div>
        </div>
    </section>

</div>

<script>
    function switchProcessTab(type) {
        const tenantTab = document.getElementById('process-flow-tenant');
        const ownerTab = document.getElementById('process-flow-owner');
        const tenantBtn = document.getElementById('tab-btn-tenant');
        const ownerBtn = document.getElementById('tab-btn-owner');

        if (type === 'tenant') {
            tenantTab.classList.remove('hidden');
            ownerTab.classList.add('hidden');

            tenantBtn.className = "process-tab-btn flex items-center gap-2.5 px-6 py-3 rounded-xl text-sm font-bold transition-all duration-300 bg-blue-600 text-white shadow-md shadow-blue-500/25";
            ownerBtn.className = "process-tab-btn flex items-center gap-2.5 px-6 py-3 rounded-xl text-sm font-bold transition-all duration-300 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white";
        } else {
            tenantTab.classList.add('hidden');
            ownerTab.classList.remove('hidden');

            ownerBtn.className = "process-tab-btn flex items-center gap-2.5 px-6 py-3 rounded-xl text-sm font-bold transition-all duration-300 bg-blue-600 text-white shadow-md shadow-blue-500/25";
            tenantBtn.className = "process-tab-btn flex items-center gap-2.5 px-6 py-3 rounded-xl text-sm font-bold transition-all duration-300 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white";
        }
    }
</script>
@endsection
