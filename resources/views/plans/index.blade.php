@extends('layouts.app')

@section('title', 'Membership Plans & Pricing - UnlockRentals')
@section('meta_description', 'Choose an UnlockRentals subscription plan for instant direct owner contact unlocks, zero brokerage, verified phone numbers, and instant visit booking.')

@push('head')
<style>
    .pricing-hero {
        background: radial-gradient(circle at 50% 0%, rgba(37, 99, 235, 0.08) 0%, rgba(248, 250, 252, 0) 70%);
    }
    .dark .pricing-hero {
        background: radial-gradient(circle at 50% 0%, rgba(37, 99, 235, 0.15) 0%, rgba(2, 6, 23, 0) 70%);
    }
    
    .billing-toggle-wrapper {
        display: inline-flex;
        align-items: center;
        padding: 4px;
        background: rgba(241, 245, 249, 0.9);
        border: 1px solid rgba(203, 213, 225, 0.8);
        border-radius: 9999px;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.03);
    }
    .dark .billing-toggle-wrapper {
        background: rgba(15, 23, 42, 0.8);
        border-color: rgba(51, 65, 85, 0.8);
    }
    
    .billing-btn {
        position: relative;
        padding: 8px 20px;
        font-size: 13px;
        font-weight: 700;
        color: #64748b;
        border-radius: 9999px;
        transition: all 0.2s ease;
        border: none;
        background: transparent;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .dark .billing-btn {
        color: #94a3b8;
    }
    .billing-btn.active {
        background: #2563EB;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35);
    }
    
    .plan-card {
        position: relative;
        display: flex;
        flex-direction: column;
        background: #ffffff;
        border: 1px solid rgba(226, 232, 240, 0.9);
        border-radius: 20px;
        padding: 28px 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        transition: all 0.25s ease;
    }
    .dark .plan-card {
        background: #0f172a;
        border-color: rgba(51, 65, 85, 0.6);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }
    .plan-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 16px 36px rgba(0, 0, 0, 0.08);
        border-color: rgba(37, 99, 235, 0.4);
    }
    .dark .plan-card:hover {
        box-shadow: 0 16px 36px rgba(0, 0, 0, 0.4);
        border-color: rgba(59, 130, 246, 0.4);
    }
    
    .plan-card.popular {
        border: 2px solid #2563EB;
        box-shadow: 0 12px 32px rgba(37, 99, 235, 0.12);
        background: linear-gradient(180deg, #ffffff 0%, #f8faff 100%);
    }
    .dark .plan-card.popular {
        background: linear-gradient(180deg, #0f172a 0%, #172033 100%);
        border-color: #3b82f6;
    }
    
    .feature-icon-bullet {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(37, 99, 235, 0.1);
        color: #2563EB;
        font-size: 11px;
        flex-shrink: 0;
    }
    .dark .feature-icon-bullet {
        background: rgba(59, 130, 246, 0.15);
        color: #60a5fa;
    }
</style>
@endpush

@section('content')
@php
    $paymentFailedReason = session('payment_failed_reason') ?: (request()->boolean('payment_failed') ? request('reason', 'Payment failed. Please try again or choose another payment method.') : null);
    $displayPlans = $plans->values();
    $hasEnterprise = $displayPlans->contains(fn($item) => str_contains(strtolower($item->name), 'enterprise'));
@endphp

<div class="min-h-screen bg-slate-50/60 dark:bg-slate-950 pb-24">

    {{-- Hero Section --}}
    <section class="pricing-hero pt-28 pb-12 lg:pt-36 lg:pb-16 text-center px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-50 dark:bg-blue-950/60 border border-blue-200/80 dark:border-blue-900/60 text-blue-700 dark:text-blue-300 text-xs font-bold uppercase tracking-wider mb-5 shadow-xs">
                <i class="ph-bold ph-shield-check text-sm"></i>
                <span>Zero Brokerage · Direct Owner Contacts</span>
            </div>
            
            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black text-slate-900 dark:text-white tracking-tight leading-[1.15]">
                Simple, Transparent Plans for <br class="hidden sm:inline">
                <span class="bg-gradient-to-r from-blue-600 via-indigo-600 to-sky-500 bg-clip-text text-transparent">Renters & Buyers</span>
            </h1>
            
            <p class="mt-4 text-base sm:text-lg text-slate-600 dark:text-slate-400 max-w-2xl mx-auto font-normal">
                Unlock direct landlord phone numbers, chat on WhatsApp, and schedule private property walkthroughs with zero middleman fees.
            </p>

            {{-- Interactive Pass Switcher (Rental Pass vs Buyer Pass) --}}
            <div class="mt-8 flex flex-col items-center gap-3">
                <div class="billing-toggle-wrapper" id="billing-toggle" role="group" aria-label="Plan category selector">
                    <button type="button" class="billing-btn active" data-billing-choice="monthly">
                        <i class="ph-bold ph-house-line text-sm"></i>
                        <span>Rental Pass</span>
                    </button>
                    <button type="button" class="billing-btn" data-billing-choice="yearly">
                        <i class="ph-bold ph-buildings text-sm"></i>
                        <span>Buyer Pass</span>
                        <span class="px-1.5 py-0.5 text-[10px] font-black uppercase rounded-full bg-amber-400 text-slate-950 ml-1">Save 20%</span>
                    </button>
                </div>
                <p class="text-xs font-medium text-slate-500 dark:text-slate-400">
                    Need instant 1-month access or a 1-year pass for buying properties? Switch anytime.
                </p>
            </div>
        </div>

        {{-- Flash Alerts --}}
        @if(session('success') || session('error') || $errors->has('payment_reference'))
            <div class="max-w-2xl mx-auto mt-8 p-4 rounded-xl text-sm font-semibold border {{ session('error') || $errors->has('payment_reference') ? 'bg-red-50 dark:bg-red-950/40 border-red-200 text-red-700 dark:text-red-300' : 'bg-emerald-50 dark:bg-emerald-950/40 border-emerald-200 text-emerald-700 dark:text-emerald-300' }}">
                {{ session('error') ?? session('success') ?? $errors->first('payment_reference') }}
            </div>
        @endif

        {{-- Active/Pending Subscriptions banner --}}
        @auth
            @php
                $userActivePlans = collect([$activeRentPlan ?? null, $activeBuyPlan ?? null])->filter()->unique('id');
            @endphp
            @if($userActivePlans->isNotEmpty())
                <div class="max-w-3xl mx-auto mt-8 space-y-3">
                    @foreach($userActivePlans as $curPlan)
                        <div class="p-4 sm:p-5 rounded-2xl bg-white dark:bg-slate-900 border-2 border-emerald-500/50 shadow-lg shadow-emerald-500/10 flex flex-col sm:flex-row items-center justify-between gap-4 text-left">
                            <div class="flex items-center gap-3.5">
                                <div class="w-11 h-11 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 flex items-center justify-center text-2xl flex-shrink-0">
                                    <i class="ph-bold ph-check-circle"></i>
                                </div>
                                <div>
                                    <h2 class="text-sm font-bold text-slate-900 dark:text-white">Active Plan: {{ $curPlan->plan->name ?? 'Premium' }} ({{ $curPlan->plan && $curPlan->plan->isBuyPlan() ? 'Buyer Pass' : 'Rental Plan' }})</h2>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ $curPlan->remaining_contacts }} contact unlocks remaining · Valid until {{ $curPlan->expires_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-emerald-600 text-white text-xs font-bold uppercase tracking-wider">Active</span>
                        </div>
                    @endforeach
                </div>
            @elseif($pendingPlan)
                <div class="max-w-3xl mx-auto mt-8 p-4 sm:p-5 rounded-2xl bg-white dark:bg-slate-900 border-2 border-amber-500/50 shadow-lg shadow-amber-500/10 flex items-center gap-3.5 text-left">
                    <div class="w-11 h-11 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 flex items-center justify-center text-2xl flex-shrink-0">
                        <i class="ph-bold ph-clock"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 dark:text-white">Payment Review Pending: {{ $pendingPlan->plan->name ?? 'Plan' }}</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Admin verification is in progress. Your plan will activate automatically upon confirmation.</p>
                    </div>
                </div>
            @endif
        @endauth
    </section>

    {{-- Plans Grid Section --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        @php
            $rentPlans = $displayPlans->filter(fn($p) => in_array($p->purpose, ['rent', 'both', null]));
            $buyPlans = $displayPlans->filter(fn($p) => in_array($p->purpose, ['buy', 'sale']));
            // Fallback if no specific buy plans
            if ($buyPlans->isEmpty()) {
                $buyPlans = $rentPlans;
            }
        @endphp

        {{-- 1. Rental Plans Grid --}}
        <div id="rental-plans-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 items-stretch">
            @foreach($rentPlans as $plan)
                @php
                    $isGold = str_contains(strtolower($plan->name), 'gold') || str_contains(strtolower($plan->name), 'pro') || str_contains(strtolower($plan->name), 'popular');
                    $monthlyOffer = isset($userOffers) ? $userOffers->where('plan_id', $plan->id)->where('billing_period', 'monthly')->first() : null;
                    $price = ($monthlyOffer && $monthlyOffer->discounted_price !== null) ? (float) $monthlyOffer->discounted_price : (float) $plan->price;
                    $nameLower = strtolower($plan->name);
                    $planUid = 'rent_' . $plan->id;
                @endphp

                <article class="plan-card {{ $isGold ? 'popular' : '' }}">
                    @if($isGold)
                        <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 px-3.5 py-1 rounded-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-[11px] font-black uppercase tracking-wider shadow-md">
                            ★ Most Popular
                        </div>
                    @endif

                    {{-- Standard Plan Icon Badge with High-Definition Vector SVGs --}}
                    <div class="w-14 h-14 rounded-2xl border flex items-center justify-center mb-4 flex-shrink-0 {{ str_contains($nameLower, 'gold') ? 'bg-amber-50/80 dark:bg-amber-950/50 border-amber-200/80 dark:border-amber-800/80 shadow-md shadow-amber-500/10' : (str_contains($nameLower, 'plat') || str_contains($nameLower, 'diamond') ? 'bg-blue-50/80 dark:bg-blue-950/50 border-blue-200/80 dark:border-blue-800/80 shadow-md shadow-blue-500/10' : 'bg-slate-50 dark:bg-slate-800/80 border-slate-200 dark:border-slate-700 shadow-sm') }}">
                        @if($plan->image_path)
                            <img src="{{ asset('storage/' . $plan->image_path) }}" alt="{{ $plan->name }}" class="w-8 h-8 object-contain">
                        @elseif(str_contains($nameLower, 'gold'))
                            {{-- Luxury 3D Imperial Gold Crown --}}
                            <svg class="w-8 h-8 drop-shadow-sm" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <linearGradient id="goldG_{{ $planUid }}" x1="4" y1="8" x2="44" y2="40" gradientUnits="userSpaceOnUse">
                                        <stop offset="0%" stop-color="#FDE047"/>
                                        <stop offset="45%" stop-color="#F59E0B"/>
                                        <stop offset="100%" stop-color="#D97706"/>
                                    </linearGradient>
                                    <linearGradient id="goldB_{{ $planUid }}" x1="8" y1="34" x2="40" y2="38" gradientUnits="userSpaceOnUse">
                                        <stop offset="0%" stop-color="#FFFBEB"/>
                                        <stop offset="100%" stop-color="#FDE68A"/>
                                    </linearGradient>
                                </defs>
                                <path d="M6 34L10 14L19 23L24 8L29 23L38 14L42 34H6Z" fill="url(#goldG_{{ $planUid }})"/>
                                <rect x="6" y="34" width="36" height="6" rx="3" fill="#B45309"/>
                                <rect x="8" y="35" width="32" height="4" rx="2" fill="url(#goldB_{{ $planUid }})"/>
                                <circle cx="24" cy="8" r="3.5" fill="#EF4444" stroke="#FFF" stroke-width="1.5"/>
                                <circle cx="10" cy="14" r="3" fill="#3B82F6" stroke="#FFF" stroke-width="1.5"/>
                                <circle cx="38" cy="14" r="3" fill="#3B82F6" stroke="#FFF" stroke-width="1.5"/>
                                <circle cx="16" cy="37" r="1.5" fill="#EF4444"/>
                                <circle cx="24" cy="37" r="2" fill="#10B981"/>
                                <circle cx="32" cy="37" r="1.5" fill="#EF4444"/>
                            </svg>
                        @elseif(str_contains($nameLower, 'plat') || str_contains($nameLower, 'diamond'))
                            {{-- Brilliant Cut Royal Sapphire Diamond --}}
                            <svg class="w-8 h-8 drop-shadow-sm" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <linearGradient id="platG1_{{ $planUid }}" x1="6" y1="10" x2="42" y2="42" gradientUnits="userSpaceOnUse">
                                        <stop offset="0%" stop-color="#60A5FA"/>
                                        <stop offset="50%" stop-color="#3B82F6"/>
                                        <stop offset="100%" stop-color="#1D4ED8"/>
                                    </linearGradient>
                                    <linearGradient id="platFacet_{{ $planUid }}" x1="14" y1="10" x2="34" y2="20" gradientUnits="userSpaceOnUse">
                                        <stop offset="0%" stop-color="#EFF6FF"/>
                                        <stop offset="100%" stop-color="#BFDBFE"/>
                                    </linearGradient>
                                </defs>
                                <polygon points="14,10 34,10 42,20 6,20" fill="url(#platG1_{{ $planUid }})"/>
                                <polygon points="18,10 30,10 33,20 15,20" fill="url(#platFacet_{{ $planUid }})"/>
                                <polygon points="6,20 42,20 24,42" fill="url(#platG1_{{ $planUid }})"/>
                                <polygon points="15,20 33,20 24,42" fill="#93C5FD" fill-opacity="0.9"/>
                                <path d="M37 7L38.5 11.5L43 13L38.5 14.5L37 19L35.5 14.5L31 13L35.5 11.5L37 7Z" fill="#FFFFFF"/>
                            </svg>
                        @else
                            {{-- High-End Metallic Silver Shield --}}
                            <svg class="w-8 h-8 drop-shadow-sm" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <linearGradient id="silvG_{{ $planUid }}" x1="8" y1="4" x2="40" y2="44" gradientUnits="userSpaceOnUse">
                                        <stop offset="0%" stop-color="#CBD5E1"/>
                                        <stop offset="40%" stop-color="#64748B"/>
                                        <stop offset="100%" stop-color="#334155"/>
                                    </linearGradient>
                                    <linearGradient id="silvShine_{{ $planUid }}" x1="12" y1="8" x2="36" y2="36" gradientUnits="userSpaceOnUse">
                                        <stop offset="0%" stop-color="#F8FAFC" stop-opacity="0.9"/>
                                        <stop offset="100%" stop-color="#94A3B8" stop-opacity="0.3"/>
                                    </linearGradient>
                                </defs>
                                <path d="M24 4L8 10V22C8 32.5 14.8 42.2 24 44C33.2 42.2 40 32.5 40 22V10L24 4Z" fill="url(#silvG_{{ $planUid }})"/>
                                <path d="M24 7L11 12V21.5C11 30.2 16.5 38.3 24 40C31.5 38.3 37 30.2 37 21.5V12L24 7Z" fill="url(#silvShine_{{ $planUid }})"/>
                                <path d="M24 16L26.3 21.2L32 21.8L27.8 25.6L29 31.2L24 28.3L19 31.2L20.2 25.6L16 21.8L21.7 21.2L24 16Z" fill="#FFFFFF"/>
                            </svg>
                        @endif
                    </div>

                    <div class="flex items-start justify-between gap-3 mb-4">
                        <div>
                            <span class="px-2.5 py-0.5 rounded-md bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 text-[10px] font-black uppercase tracking-wider">Rental Pass</span>
                            <h2 class="text-xl font-extrabold text-slate-900 dark:text-white mt-1">{{ $plan->name }}</h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 min-h-[32px]">{{ $plan->description ?? 'Direct owner contact access for verified rental listings.' }}</p>
                        </div>
                    </div>

                    <div class="py-4 border-y border-slate-100 dark:border-slate-800/80 mb-5">
                        <div class="flex items-baseline gap-1">
                            <span class="text-2xl font-black text-slate-900 dark:text-white">₹</span>
                            <span class="text-4xl font-black text-slate-900 dark:text-white tracking-tight">{{ number_format($price, 0) }}</span>
                            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 ml-1">/ rent pass</span>
                        </div>
                        <div class="mt-2 flex items-center gap-2 text-xs font-semibold text-blue-600 dark:text-blue-400">
                            <i class="ph-bold ph-calendar-check"></i>
                            <span>{{ $plan->duration_days }} Days Validity</span>
                        </div>
                    </div>

                    <ul class="space-y-3 mb-8 flex-1">
                        <li class="flex items-start gap-2.5 text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300">
                            <span class="feature-icon-bullet"><i class="ph-bold ph-check"></i></span>
                            <span><strong>{{ $plan->contact_limit }}</strong> Verified Owner Contacts</span>
                        </li>
                        <li class="flex items-start gap-2.5 text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300">
                            <span class="feature-icon-bullet"><i class="ph-bold ph-check"></i></span>
                            <span>Direct Phone & WhatsApp Unlock</span>
                        </li>
                        <li class="flex items-start gap-2.5 text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300">
                            <span class="feature-icon-bullet"><i class="ph-bold ph-check"></i></span>
                            <span>Zero Brokerage Guarantee</span>
                        </li>
                        @if($plan->features && is_array($plan->features))
                            @foreach($plan->features as $feature)
                                @if(!empty(trim($feature)))
                                    <li class="flex items-start gap-2.5 text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300">
                                        <span class="feature-icon-bullet"><i class="ph-bold ph-check"></i></span>
                                        <span>{{ $feature }}</span>
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    </ul>

                    <div class="mt-auto">
                        @if(auth()->check() && $activeRentPlan && $activeRentPlan->remaining_contacts > 0 && $activeRentPlan->plan_id === $plan->id)
                            <button disabled class="w-full py-3.5 px-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-300 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 font-bold text-sm text-center cursor-default">
                                ✓ Current Active Plan
                            </button>
                        @elseif(auth()->check() && $activeRentPlan && $activeRentPlan->remaining_contacts > 0 && $activeRentPlan->plan && (float) $plan->price > (float) $activeRentPlan->plan->price)
                            <a href="{{ route('plans.checkout', ['plan' => $plan, 'billing' => 'monthly', 'direct' => 1]) }}" class="w-full py-3.5 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm flex items-center justify-center gap-2 shadow-md shadow-blue-500/20 active:scale-[0.98] transition-all" title="Upgrade Plan">
                                <i class="ph-bold ph-lightning"></i>
                                <span>Upgrade Plan</span>
                            </a>
                        @elseif(auth()->check() && $activeRentPlan && $activeRentPlan->remaining_contacts > 0)
                            <button disabled class="w-full py-3.5 px-4 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-400 font-bold text-sm text-center cursor-not-allowed">
                                Already Subscribed
                            </button>
                        @elseif(auth()->check() && $pendingPlan && $pendingPlan->plan && $pendingPlan->plan->isRentPlan())
                            <button disabled class="w-full py-3.5 px-4 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 text-amber-700 dark:text-amber-300 font-bold text-sm text-center cursor-default">
                                Verification Pending
                            </button>
                        @else
                            @guest
                                <a href="{{ route('login', ['redirect' => route('plans.checkout', ['plan' => $plan, 'billing' => 'monthly', 'direct' => 1])]) }}"
                                   onclick="event.preventDefault(); event.stopPropagation(); window.openAuthModal('login', '{{ route('plans.checkout', ['plan' => $plan, 'billing' => 'monthly', 'direct' => 1]) }}');"
                                   class="w-full py-3.5 px-4 rounded-xl {{ $isGold ? 'bg-blue-600 hover:bg-blue-700 text-white shadow-md shadow-blue-500/25' : 'bg-slate-900 dark:bg-white text-white dark:text-slate-900 hover:bg-slate-800 dark:hover:bg-slate-100' }} font-bold text-sm flex items-center justify-center gap-2 active:scale-[0.98] transition-all" title="Unlock Contacts">
                                    <i class="ph-bold ph-lightning text-amber-400"></i>
                                    <span>Unlock Contacts</span>
                                </a>
                            @else
                                <a href="{{ route('plans.checkout', ['plan' => $plan, 'billing' => 'monthly', 'direct' => 1]) }}"
                                   class="w-full py-3.5 px-4 rounded-xl {{ $isGold ? 'bg-blue-600 hover:bg-blue-700 text-white shadow-md shadow-blue-500/25' : 'bg-slate-900 dark:bg-white text-white dark:text-slate-900 hover:bg-slate-800 dark:hover:bg-slate-100' }} font-bold text-sm flex items-center justify-center gap-2 active:scale-[0.98] transition-all" title="Unlock Contacts">
                                    <i class="ph-bold ph-lightning text-amber-400"></i>
                                    <span>Unlock Contacts</span>
                                </a>
                            @endguest
                        @endif
                    </div>
                </article>
            @endforeach

            {{-- Enterprise Card --}}
            @unless($hasEnterprise)
                <article class="plan-card bg-slate-900 text-white dark:bg-slate-900 border-slate-800">
                    {{-- Enterprise Skyline Vector SVG Icon --}}
                    <div class="w-14 h-14 rounded-2xl border border-teal-700/80 bg-teal-950/90 flex items-center justify-center mb-4 shadow-md shadow-teal-500/10">
                        <svg class="w-8 h-8 drop-shadow-sm" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <linearGradient id="entT1" x1="14" y1="6" x2="34" y2="42" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#5EEAD4"/>
                                    <stop offset="50%" stop-color="#0D9488"/>
                                    <stop offset="100%" stop-color="#115E59"/>
                                </linearGradient>
                                <linearGradient id="entT2" x1="6" y1="16" x2="22" y2="42" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#2DD4BF"/>
                                    <stop offset="100%" stop-color="#0F766E"/>
                                </linearGradient>
                            </defs>
                            <path d="M6 18L18 12V42H6V18Z" fill="url(#entT2)"/>
                            <path d="M18 8L32 4V42H18V8Z" fill="url(#entT1)"/>
                            <path d="M32 16L42 20V42H32V16Z" fill="url(#entT2)"/>
                            <rect x="21" y="10" width="3" height="4" rx="0.5" fill="#CCFBF1" fill-opacity="0.8"/>
                            <rect x="26" y="10" width="3" height="4" rx="0.5" fill="#CCFBF1" fill-opacity="0.8"/>
                            <rect x="21" y="17" width="3" height="4" rx="0.5" fill="#CCFBF1" fill-opacity="0.8"/>
                            <rect x="26" y="17" width="3" height="4" rx="0.5" fill="#CCFBF1" fill-opacity="0.8"/>
                            <rect x="21" y="24" width="3" height="4" rx="0.5" fill="#CCFBF1" fill-opacity="0.8"/>
                            <rect x="26" y="24" width="3" height="4" rx="0.5" fill="#CCFBF1" fill-opacity="0.8"/>
                            <rect x="9" y="22" width="3" height="3" rx="0.5" fill="#CCFBF1" fill-opacity="0.6"/>
                            <rect x="9" y="28" width="3" height="3" rx="0.5" fill="#CCFBF1" fill-opacity="0.6"/>
                            <rect x="35" y="24" width="3" height="3" rx="0.5" fill="#CCFBF1" fill-opacity="0.6"/>
                            <rect x="35" y="30" width="3" height="3" rx="0.5" fill="#CCFBF1" fill-opacity="0.6"/>
                        </svg>
                    </div>

                    <div class="mb-4">
                        <span class="px-2.5 py-0.5 rounded-md bg-teal-950 text-teal-400 text-[10px] font-black uppercase tracking-wider">Corporate</span>
                        <h2 class="text-xl font-extrabold text-white mt-1">Enterprise Plan</h2>
                        <p class="text-xs text-slate-400 mt-1 min-h-[32px]">For agencies, relocation teams, and portfolio operations.</p>
                    </div>

                    <div class="py-4 border-y border-slate-800 mb-5">
                        <div class="flex items-baseline gap-1">
                            <span class="text-3xl font-black text-white">Custom</span>
                        </div>
                        <div class="mt-2 flex items-center gap-2 text-xs font-semibold text-teal-400">
                            <i class="ph-bold ph-users-three"></i>
                            <span>Multi-User License</span>
                        </div>
                    </div>

                    <ul class="space-y-3 mb-8 flex-1 text-slate-300">
                        <li class="flex items-start gap-2.5 text-xs sm:text-sm font-medium">
                            <span class="feature-icon-bullet bg-teal-500/20 text-teal-400"><i class="ph-bold ph-check"></i></span>
                            <span>Unlimited Owner Unlocks</span>
                        </li>
                        <li class="flex items-start gap-2.5 text-xs sm:text-sm font-medium">
                            <span class="feature-icon-bullet bg-teal-500/20 text-teal-400"><i class="ph-bold ph-check"></i></span>
                            <span>Dedicated Account Manager</span>
                        </li>
                        <li class="flex items-start gap-2.5 text-xs sm:text-sm font-medium">
                            <span class="feature-icon-bullet bg-teal-500/20 text-teal-400"><i class="ph-bold ph-check"></i></span>
                            <span>GST Invoicing & API Integration</span>
                        </li>
                    </ul>

                    <div class="mt-auto">
                        <a href="mailto:support@unlockrentals.com?subject=Enterprise%20Plan%20Inquiry" class="w-full py-3.5 px-4 rounded-xl bg-teal-600 hover:bg-teal-500 text-white font-bold text-sm flex items-center justify-center gap-2 transition-all" title="Contact Sales">
                            <i class="ph-bold ph-envelope"></i>
                            <span>Contact Sales</span>
                        </a>
                    </div>
                </article>
            @endunless
        </div>

        {{-- 2. Buyer Plans Grid (Hidden by default, shown when Buyer Pass selected) --}}
        <div id="buyer-plans-grid" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 items-stretch">
            @foreach($buyPlans as $plan)
                @php
                    $isGold = str_contains(strtolower($plan->name), 'gold') || str_contains(strtolower($plan->name), 'pro') || str_contains(strtolower($plan->name), 'popular');
                    $price = (float) $plan->price;
                    $nameLower = strtolower($plan->name);
                    $planUid = 'buy_' . $plan->id;
                @endphp

                <article class="plan-card {{ $isGold ? 'popular' : '' }}">
                    @if($isGold)
                        <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 px-3.5 py-1 rounded-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-[11px] font-black uppercase tracking-wider shadow-md">
                            ★ VIP Buyer Choice
                        </div>
                    @endif

                    {{-- Standard Plan Icon Badge with High-Definition Vector SVGs --}}
                    <div class="w-14 h-14 rounded-2xl border flex items-center justify-center mb-4 flex-shrink-0 {{ str_contains($nameLower, 'gold') ? 'bg-amber-50/80 dark:bg-amber-950/50 border-amber-200/80 dark:border-amber-800/80 shadow-md shadow-amber-500/10' : (str_contains($nameLower, 'plat') || str_contains($nameLower, 'diamond') ? 'bg-blue-50/80 dark:bg-blue-950/50 border-blue-200/80 dark:border-blue-800/80 shadow-md shadow-blue-500/10' : 'bg-slate-50 dark:bg-slate-800/80 border-slate-200 dark:border-slate-700 shadow-sm') }}">
                        @if($plan->image_path)
                            <img src="{{ asset('storage/' . $plan->image_path) }}" alt="{{ $plan->name }}" class="w-8 h-8 object-contain">
                        @elseif(str_contains($nameLower, 'gold'))
                            {{-- Luxury 3D Imperial Gold Crown --}}
                            <svg class="w-8 h-8 drop-shadow-sm" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <linearGradient id="goldG_{{ $planUid }}" x1="4" y1="8" x2="44" y2="40" gradientUnits="userSpaceOnUse">
                                        <stop offset="0%" stop-color="#FDE047"/>
                                        <stop offset="45%" stop-color="#F59E0B"/>
                                        <stop offset="100%" stop-color="#D97706"/>
                                    </linearGradient>
                                    <linearGradient id="goldB_{{ $planUid }}" x1="8" y1="34" x2="40" y2="38" gradientUnits="userSpaceOnUse">
                                        <stop offset="0%" stop-color="#FFFBEB"/>
                                        <stop offset="100%" stop-color="#FDE68A"/>
                                    </linearGradient>
                                </defs>
                                <path d="M6 34L10 14L19 23L24 8L29 23L38 14L42 34H6Z" fill="url(#goldG_{{ $planUid }})"/>
                                <rect x="6" y="34" width="36" height="6" rx="3" fill="#B45309"/>
                                <rect x="8" y="35" width="32" height="4" rx="2" fill="url(#goldB_{{ $planUid }})"/>
                                <circle cx="24" cy="8" r="3.5" fill="#EF4444" stroke="#FFF" stroke-width="1.5"/>
                                <circle cx="10" cy="14" r="3" fill="#3B82F6" stroke="#FFF" stroke-width="1.5"/>
                                <circle cx="38" cy="14" r="3" fill="#3B82F6" stroke="#FFF" stroke-width="1.5"/>
                                <circle cx="16" cy="37" r="1.5" fill="#EF4444"/>
                                <circle cx="24" cy="37" r="2" fill="#10B981"/>
                                <circle cx="32" cy="37" r="1.5" fill="#EF4444"/>
                            </svg>
                        @elseif(str_contains($nameLower, 'plat') || str_contains($nameLower, 'diamond'))
                            {{-- Brilliant Cut Royal Sapphire Diamond --}}
                            <svg class="w-8 h-8 drop-shadow-sm" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <linearGradient id="platG1_{{ $planUid }}" x1="6" y1="10" x2="42" y2="42" gradientUnits="userSpaceOnUse">
                                        <stop offset="0%" stop-color="#60A5FA"/>
                                        <stop offset="50%" stop-color="#3B82F6"/>
                                        <stop offset="100%" stop-color="#1D4ED8"/>
                                    </linearGradient>
                                    <linearGradient id="platFacet_{{ $planUid }}" x1="14" y1="10" x2="34" y2="20" gradientUnits="userSpaceOnUse">
                                        <stop offset="0%" stop-color="#EFF6FF"/>
                                        <stop offset="100%" stop-color="#BFDBFE"/>
                                    </linearGradient>
                                </defs>
                                <polygon points="14,10 34,10 42,20 6,20" fill="url(#platG1_{{ $planUid }})"/>
                                <polygon points="18,10 30,10 33,20 15,20" fill="url(#platFacet_{{ $planUid }})"/>
                                <polygon points="6,20 42,20 24,42" fill="url(#platG1_{{ $planUid }})"/>
                                <polygon points="15,20 33,20 24,42" fill="#93C5FD" fill-opacity="0.9"/>
                                <path d="M37 7L38.5 11.5L43 13L38.5 14.5L37 19L35.5 14.5L31 13L35.5 11.5L37 7Z" fill="#FFFFFF"/>
                            </svg>
                        @else
                            {{-- High-End Metallic Silver Shield --}}
                            <svg class="w-8 h-8 drop-shadow-sm" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <linearGradient id="silvG_{{ $planUid }}" x1="8" y1="4" x2="40" y2="44" gradientUnits="userSpaceOnUse">
                                        <stop offset="0%" stop-color="#CBD5E1"/>
                                        <stop offset="40%" stop-color="#64748B"/>
                                        <stop offset="100%" stop-color="#334155"/>
                                    </linearGradient>
                                    <linearGradient id="silvShine_{{ $planUid }}" x1="12" y1="8" x2="36" y2="36" gradientUnits="userSpaceOnUse">
                                        <stop offset="0%" stop-color="#F8FAFC" stop-opacity="0.9"/>
                                        <stop offset="100%" stop-color="#94A3B8" stop-opacity="0.3"/>
                                    </linearGradient>
                                </defs>
                                <path d="M24 4L8 10V22C8 32.5 14.8 42.2 24 44C33.2 42.2 40 32.5 40 22V10L24 4Z" fill="url(#silvG_{{ $planUid }})"/>
                                <path d="M24 7L11 12V21.5C11 30.2 16.5 38.3 24 40C31.5 38.3 37 30.2 37 21.5V12L24 7Z" fill="url(#silvShine_{{ $planUid }})"/>
                                <path d="M24 16L26.3 21.2L32 21.8L27.8 25.6L29 31.2L24 28.3L19 31.2L20.2 25.6L16 21.8L21.7 21.2L24 16Z" fill="#FFFFFF"/>
                            </svg>
                        @endif
                    </div>

                    <div class="flex items-start justify-between gap-3 mb-4">
                        <div>
                            <span class="px-2.5 py-0.5 rounded-md bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 text-[10px] font-black uppercase tracking-wider">Buyer Pass (365 Days)</span>
                            <h2 class="text-xl font-extrabold text-slate-900 dark:text-white mt-1">{{ $plan->name }}</h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 min-h-[32px]">{{ $plan->description ?? 'Direct owner contact access for verified property purchase.' }}</p>
                        </div>
                    </div>

                    <div class="py-4 border-y border-slate-100 dark:border-slate-800/80 mb-5">
                        <div class="flex items-baseline gap-1">
                            <span class="text-2xl font-black text-slate-900 dark:text-white">₹</span>
                            <span class="text-4xl font-black text-slate-900 dark:text-white tracking-tight">{{ number_format($price, 0) }}</span>
                            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 ml-1">/ annual pass</span>
                        </div>
                        <div class="mt-2 flex items-center gap-2 text-xs font-semibold text-emerald-600 dark:text-emerald-400">
                            <i class="ph-bold ph-shield-check"></i>
                            <span>{{ $plan->duration_days }} Days Priority Buyer Access</span>
                        </div>
                    </div>

                    <ul class="space-y-3 mb-8 flex-1">
                        <li class="flex items-start gap-2.5 text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300">
                            <span class="feature-icon-bullet"><i class="ph-bold ph-check"></i></span>
                            <span><strong>{{ $plan->contact_limit }}</strong> Verified Seller Contacts</span>
                        </li>
                        <li class="flex items-start gap-2.5 text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300">
                            <span class="feature-icon-bullet"><i class="ph-bold ph-check"></i></span>
                            <span>Direct Phone & WhatsApp Unlock</span>
                        </li>
                        <li class="flex items-start gap-2.5 text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300">
                            <span class="feature-icon-bullet"><i class="ph-bold ph-check"></i></span>
                            <span>Zero Brokerage Guaranteed</span>
                        </li>
                        <li class="flex items-start gap-2.5 text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300">
                            <span class="feature-icon-bullet"><i class="ph-bold ph-check"></i></span>
                            <span>Schedule Property Walkthroughs</span>
                        </li>
                        @if($plan->features && is_array($plan->features))
                            @foreach($plan->features as $feature)
                                @if(!empty(trim($feature)))
                                    <li class="flex items-start gap-2.5 text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300">
                                        <span class="feature-icon-bullet"><i class="ph-bold ph-check"></i></span>
                                        <span>{{ $feature }}</span>
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    </ul>

                    <div class="mt-auto">
                        @if(auth()->check() && $activeBuyPlan && $activeBuyPlan->remaining_contacts > 0 && $activeBuyPlan->plan_id === $plan->id)
                            <button disabled class="w-full py-3.5 px-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-300 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 font-bold text-sm text-center cursor-default">
                                ✓ Current Active Plan
                            </button>
                        @elseif(auth()->check() && $activeBuyPlan && $activeBuyPlan->remaining_contacts > 0 && $activeBuyPlan->plan && (float) $plan->price > (float) $activeBuyPlan->plan->price)
                            <a href="{{ route('plans.checkout', ['plan' => $plan, 'billing' => 'yearly', 'direct' => 1]) }}" class="w-full py-3.5 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm flex items-center justify-center gap-2 shadow-md shadow-blue-500/20 active:scale-[0.98] transition-all" title="Upgrade Plan">
                                <i class="ph-bold ph-lightning"></i>
                                <span>Upgrade Plan</span>
                            </a>
                        @elseif(auth()->check() && $activeBuyPlan && $activeBuyPlan->remaining_contacts > 0)
                            <button disabled class="w-full py-3.5 px-4 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-400 font-bold text-sm text-center cursor-not-allowed">
                                Already Subscribed
                            </button>
                        @elseif(auth()->check() && $pendingPlan && $pendingPlan->plan && $pendingPlan->plan->isBuyPlan())
                            <button disabled class="w-full py-3.5 px-4 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 text-amber-700 dark:text-amber-300 font-bold text-sm text-center cursor-default">
                                Verification Pending
                            </button>
                        @else
                            @guest
                                <a href="{{ route('login', ['redirect' => route('plans.checkout', ['plan' => $plan, 'billing' => 'yearly', 'direct' => 1])]) }}"
                                   onclick="event.preventDefault(); event.stopPropagation(); window.openAuthModal('login', '{{ route('plans.checkout', ['plan' => $plan, 'billing' => 'yearly', 'direct' => 1]) }}');"
                                   class="w-full py-3.5 px-4 rounded-xl {{ $isGold ? 'bg-blue-600 hover:bg-blue-700 text-white shadow-md shadow-blue-500/25' : 'bg-slate-900 dark:bg-white text-white dark:text-slate-900 hover:bg-slate-800 dark:hover:bg-slate-100' }} font-bold text-sm flex items-center justify-center gap-2 active:scale-[0.98] transition-all" title="Unlock Buyer Contacts">
                                    <i class="ph-bold ph-lightning text-amber-400"></i>
                                    <span>Unlock Buyer Contacts</span>
                                </a>
                            @else
                                <a href="{{ route('plans.checkout', ['plan' => $plan, 'billing' => 'yearly', 'direct' => 1]) }}"
                                   class="w-full py-3.5 px-4 rounded-xl {{ $isGold ? 'bg-blue-600 hover:bg-blue-700 text-white shadow-md shadow-blue-500/25' : 'bg-slate-900 dark:bg-white text-white dark:text-slate-900 hover:bg-slate-800 dark:hover:bg-slate-100' }} font-bold text-sm flex items-center justify-center gap-2 active:scale-[0.98] transition-all" title="Unlock Buyer Contacts">
                                    <i class="ph-bold ph-lightning text-amber-400"></i>
                                    <span>Unlock Buyer Contacts</span>
                                </a>
                            @endguest
                        @endif
                    </div>
                </article>
            @endforeach

            {{-- Enterprise Card --}}
            @unless($hasEnterprise)
                <article class="plan-card bg-slate-900 text-white dark:bg-slate-900 border-slate-800">
                    {{-- Enterprise Skyline Vector SVG Icon --}}
                    <div class="w-14 h-14 rounded-2xl border border-teal-700/80 bg-teal-950/90 flex items-center justify-center mb-4 shadow-md shadow-teal-500/10">
                        <svg class="w-8 h-8 drop-shadow-sm" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <linearGradient id="entT1_buy" x1="14" y1="6" x2="34" y2="42" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#5EEAD4"/>
                                    <stop offset="50%" stop-color="#0D9488"/>
                                    <stop offset="100%" stop-color="#115E59"/>
                                </linearGradient>
                                <linearGradient id="entT2_buy" x1="6" y1="16" x2="22" y2="42" gradientUnits="userSpaceOnUse">
                                    <stop offset="0%" stop-color="#2DD4BF"/>
                                    <stop offset="100%" stop-color="#0F766E"/>
                                </linearGradient>
                            </defs>
                            <path d="M6 18L18 12V42H6V18Z" fill="url(#entT2_buy)"/>
                            <path d="M18 8L32 4V42H18V8Z" fill="url(#entT1_buy)"/>
                            <path d="M32 16L42 20V42H32V16Z" fill="url(#entT2_buy)"/>
                            <rect x="21" y="10" width="3" height="4" rx="0.5" fill="#CCFBF1" fill-opacity="0.8"/>
                            <rect x="26" y="10" width="3" height="4" rx="0.5" fill="#CCFBF1" fill-opacity="0.8"/>
                            <rect x="21" y="17" width="3" height="4" rx="0.5" fill="#CCFBF1" fill-opacity="0.8"/>
                            <rect x="26" y="17" width="3" height="4" rx="0.5" fill="#CCFBF1" fill-opacity="0.8"/>
                            <rect x="21" y="24" width="3" height="4" rx="0.5" fill="#CCFBF1" fill-opacity="0.8"/>
                            <rect x="26" y="24" width="3" height="4" rx="0.5" fill="#CCFBF1" fill-opacity="0.8"/>
                            <rect x="9" y="22" width="3" height="3" rx="0.5" fill="#CCFBF1" fill-opacity="0.6"/>
                            <rect x="9" y="28" width="3" height="3" rx="0.5" fill="#CCFBF1" fill-opacity="0.6"/>
                            <rect x="35" y="24" width="3" height="3" rx="0.5" fill="#CCFBF1" fill-opacity="0.6"/>
                            <rect x="35" y="30" width="3" height="3" rx="0.5" fill="#CCFBF1" fill-opacity="0.6"/>
                        </svg>
                    </div>

                    <div class="mb-4">
                        <span class="px-2.5 py-0.5 rounded-md bg-teal-950 text-teal-400 text-[10px] font-black uppercase tracking-wider">Investor Desk</span>
                        <h2 class="text-xl font-extrabold text-white mt-1">Institutional Buyer</h2>
                        <p class="text-xs text-slate-400 mt-1 min-h-[32px]">For property funds, builders, and large commercial investors.</p>
                    </div>

                    <div class="py-4 border-y border-slate-800 mb-5">
                        <div class="flex items-baseline gap-1">
                            <span class="text-3xl font-black text-white">Custom</span>
                        </div>
                        <div class="mt-2 flex items-center gap-2 text-xs font-semibold text-teal-400">
                            <i class="ph-bold ph-users-three"></i>
                            <span>Portfolio Access</span>
                        </div>
                    </div>

                    <ul class="space-y-3 mb-8 flex-1 text-slate-300">
                        <li class="flex items-start gap-2.5 text-xs sm:text-sm font-medium">
                            <span class="feature-icon-bullet bg-teal-500/20 text-teal-400"><i class="ph-bold ph-check"></i></span>
                            <span>Unlimited Seller Direct Unlocks</span>
                        </li>
                        <li class="flex items-start gap-2.5 text-xs sm:text-sm font-medium">
                            <span class="feature-icon-bullet bg-teal-500/20 text-teal-400"><i class="ph-bold ph-check"></i></span>
                            <span>Dedicated Investment Concierge</span>
                        </li>
                        <li class="flex items-start gap-2.5 text-xs sm:text-sm font-medium">
                            <span class="feature-icon-bullet bg-teal-500/20 text-teal-400"><i class="ph-bold ph-check"></i></span>
                            <span>Direct API & Bulk Export</span>
                        </li>
                    </ul>

                    <div class="mt-auto">
                        <a href="mailto:support@unlockrentals.com?subject=Institutional%20Buyer%20Inquiry" class="w-full py-3.5 px-4 rounded-xl bg-teal-600 hover:bg-teal-500 text-white font-bold text-sm flex items-center justify-center gap-2 transition-all" title="Contact Sales">
                            <i class="ph-bold ph-envelope"></i>
                            <span>Contact Sales</span>
                        </a>
                    </div>
                </article>
            @endunless
        </div>

        {{-- Feature Comparison Matrix --}}
        <div class="mt-16 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 lg:p-8 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100 dark:border-slate-800">
                <div>
                    <span class="text-xs font-extrabold uppercase tracking-wider text-blue-600 dark:text-blue-400">Detailed Comparison</span>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white mt-1">What's included in every plan</h2>
                </div>
                <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 dark:text-slate-400">
                    <i class="ph-bold ph-lock-key text-emerald-500"></i>
                    <span>256-Bit SSL Encrypted Instant Activation</span>
                </div>
            </div>

            <div class="overflow-x-auto mt-4">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-slate-800 text-xs font-bold text-slate-400 uppercase tracking-wider">
                            <th class="py-3.5">Features & Benefits</th>
                            <th class="py-3.5 px-4">Basic</th>
                            <th class="py-3.5 px-4">Gold (Popular)</th>
                            <th class="py-3.5 px-4">Platinum</th>
                            <th class="py-3.5 px-4">Enterprise</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-slate-700 dark:text-slate-300">
                        <tr>
                            <td class="py-3.5 font-bold text-slate-900 dark:text-white">Direct Landlord Contact Unlocks</td>
                            <td class="px-4">Starter Pack</td>
                            <td class="px-4 font-bold text-blue-600 dark:text-blue-400">Expanded Pack</td>
                            <td class="px-4">High Volume</td>
                            <td class="px-4">Unlimited</td>
                        </tr>
                        <tr>
                            <td class="py-3.5 font-bold text-slate-900 dark:text-white">Direct WhatsApp & Call Connect</td>
                            <td class="px-4">✓ Included</td>
                            <td class="px-4">✓ Included</td>
                            <td class="px-4">✓ Included</td>
                            <td class="px-4">✓ Included</td>
                        </tr>
                        <tr>
                            <td class="py-3.5 font-bold text-slate-900 dark:text-white">Zero Brokerage Guarantee</td>
                            <td class="px-4 text-emerald-600 font-bold">100% Free</td>
                            <td class="px-4 text-emerald-600 font-bold">100% Free</td>
                            <td class="px-4 text-emerald-600 font-bold">100% Free</td>
                            <td class="px-4 text-emerald-600 font-bold">100% Free</td>
                        </tr>
                        <tr>
                            <td class="py-3.5 font-bold text-slate-900 dark:text-white">Visit Booking Access</td>
                            <td class="px-4">Standard</td>
                            <td class="px-4">Priority</td>
                            <td class="px-4">Instant Slot Lock</td>
                            <td class="px-4">Concierge</td>
                        </tr>
                        <tr>
                            <td class="py-3.5 font-bold text-slate-900 dark:text-white">Customer Support</td>
                            <td class="px-4">Email Support</td>
                            <td class="px-4 font-semibold text-slate-900 dark:text-white">Priority Chat & Phone</td>
                            <td class="px-4">Dedicated Support</td>
                            <td class="px-4">24/7 SLA Manager</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Frequently Asked Questions --}}
        <div class="mt-16 max-w-3xl mx-auto">
            <h2 class="text-2xl font-black text-slate-900 dark:text-white text-center mb-8">Frequently Asked Questions</h2>
            
            <div class="space-y-4">
                <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xs">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center justify-between">
                        <span>How does the direct owner contact unlock work?</span>
                        <i class="ph-bold ph-plus text-blue-600"></i>
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-2 leading-relaxed">
                        Once you choose a plan and complete payment, you can click "Unlock Owner Contact" on any property. You will instantly view the landlord's verified mobile number and can directly call or message them on WhatsApp with zero broker commission.
                    </p>
                </div>

                <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xs">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center justify-between">
                        <span>What is the difference between Rental Pass and Buyer Pass?</span>
                        <i class="ph-bold ph-plus text-blue-600"></i>
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-2 leading-relaxed">
                        The <strong>Rental Pass</strong> is designed for quick 30-day apartment and room hunting. The <strong>Buyer Pass</strong> provides 365-day extended validity with 20% discount, designed for home buyers and real estate investors exploring properties over several months.
                    </p>
                </div>

                <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xs">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center justify-between">
                        <span>Which payment methods are supported?</span>
                        <i class="ph-bold ph-plus text-blue-600"></i>
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-2 leading-relaxed">
                        We support all popular Indian payment modes including UPI (Google Pay, PhonePe, Paytm), Credit/Debit cards (Visa, Mastercard, RuPay), NetBanking, and Wallets through secure encrypted payment gateways.
                    </p>
                </div>
            </div>
        </div>

    </section>
</div>

<x-subscription.payment-failed-modal :payment-failed-reason="$paymentFailedReason" />
@endsection

@push('scripts')
<script>
(() => {
    const buttons = document.querySelectorAll('[data-billing-choice]');
    const rentalGrid = document.getElementById('rental-plans-grid');
    const buyerGrid = document.getElementById('buyer-plans-grid');

    function setBilling(period) {
        buttons.forEach(button => {
            if (button.dataset.billingChoice === period) {
                button.classList.add('active');
            } else {
                button.classList.remove('active');
            }
        });

        if (period === 'yearly') {
            if (rentalGrid) rentalGrid.classList.add('hidden');
            if (buyerGrid) buyerGrid.classList.remove('hidden');
        } else {
            if (rentalGrid) rentalGrid.classList.remove('hidden');
            if (buyerGrid) buyerGrid.classList.add('hidden');
        }
    }

    buttons.forEach(button => {
        button.addEventListener('click', () => setBilling(button.dataset.billingChoice));
    });

    document.querySelectorAll('.plan-checkout-link').forEach(link => {
        link.addEventListener('click', () => {
            if (!link.hasAttribute('onclick')) {
                link.style.pointerEvents = 'none';
                link.style.opacity = '0.75';
                link.innerHTML = '<i class="ph-bold ph-circle-notch animate-spin"></i><span>Opening Checkout...</span>';
            }
        });
    });
})();
</script>
@endpush
