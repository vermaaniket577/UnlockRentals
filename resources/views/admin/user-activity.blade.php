@extends('layouts.admin')

@section('title', 'Member Activity & Lifecycle - ' . $user->name)

@section('content')
<div class="py-8 lg:py-10 bg-slate-50/60 dark:bg-slate-950 min-h-screen text-slate-800 dark:text-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- 1. BREADCRUMBS & TOP NAV --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 transition-colors">Admin</a>
                <i class="ph-bold ph-caret-right text-[10px]"></i>
                <a href="{{ route('admin.subscriptions') }}" class="hover:text-blue-600 transition-colors">Subscriptions</a>
                <i class="ph-bold ph-caret-right text-[10px]"></i>
                <span class="text-slate-700 dark:text-slate-300 font-bold">Member Activity</span>
            </nav>

            <div class="flex items-center gap-2 self-start sm:self-auto">
                <a href="{{ route('admin.subscriptions') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 shadow-xs transition">
                    <i class="ph-bold ph-arrow-left"></i>
                    <span>Subscriptions List</span>
                </a>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Account Active
                </span>
            </div>
        </div>

        {{-- 2. USER PROFILE HEADER HERO CARD --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-6 sm:p-7 shadow-xs">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                
                {{-- Left: User details & Identity --}}
                <div class="flex items-start sm:items-center gap-4 sm:gap-5">
                    <div class="relative shrink-0">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-gradient-to-tr from-blue-600 via-indigo-600 to-sky-500 text-white flex items-center justify-center text-2xl sm:text-3xl font-black shadow-md shadow-blue-500/20">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <span class="absolute -bottom-1 -right-1 px-1.5 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider bg-slate-900 text-white dark:bg-white dark:text-slate-900 border border-white dark:border-slate-800">
                            ID: #{{ $user->id }}
                        </span>
                    </div>

                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2 mb-1.5">
                            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white truncate">
                                {{ $user->name }}
                            </h1>
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-blue-50 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 border border-blue-200/60 dark:border-blue-800">
                                <i class="ph-fill ph-user-circle text-xs"></i>
                                {{ $user->role ?? 'User' }}
                            </span>
                            @if($user->activePlan())
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                                    <i class="ph-fill ph-crown text-xs"></i>
                                    {{ $user->activePlan()->plan?->name ?? 'Subscribed' }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                                    <i class="ph-bold ph-shield text-xs"></i>
                                    Free Tier
                                </span>
                            @endif
                        </div>

                        {{-- Metadata contact chips --}}
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-xs text-slate-500 dark:text-slate-400">
                            <a href="mailto:{{ $user->email }}" class="inline-flex items-center gap-1.5 hover:text-blue-600 transition-colors">
                                <i class="ph-bold ph-envelope text-slate-400"></i>
                                <span class="font-medium text-slate-700 dark:text-slate-300">{{ $user->email }}</span>
                            </a>
                            <span class="hidden sm:inline text-slate-300 dark:text-slate-700">&bull;</span>
                            <a href="tel:{{ $user->phone ?? '' }}" class="inline-flex items-center gap-1.5 hover:text-blue-600 transition-colors">
                                <i class="ph-bold ph-phone text-slate-400"></i>
                                <span class="font-medium text-slate-700 dark:text-slate-300">{{ $user->formatted_phone ?: ($user->phone ?: 'No phone added') }}</span>
                            </a>
                            <span class="hidden sm:inline text-slate-300 dark:text-slate-700">&bull;</span>
                            <span class="inline-flex items-center gap-1.5">
                                <i class="ph-bold ph-calendar text-slate-400"></i>
                                <span>Joined {{ $user->created_at ? $user->created_at->format('d M, Y') : 'N/A' }}</span>
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Right: Quick Action Controls --}}
                <div class="flex flex-wrap items-center gap-2.5 pt-4 lg:pt-0 border-t lg:border-t-0 border-slate-100 dark:border-slate-800">
                    @if($user->phone)
                        @php
                            $cleanPhone = preg_replace('/[^0-9]/', '', $user->phone);
                            if(strlen($cleanPhone) === 10) { $cleanPhone = '91' . $cleanPhone; }
                        @endphp
                        <a href="https://wa.me/{{ $cleanPhone }}?text=Hello%20{{ urlencode($user->name) }}%2C%20reaching%20out%20from%20UnlockRentals" 
                           target="_blank"
                           class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 dark:bg-emerald-950/40 dark:hover:bg-emerald-900/50 dark:text-emerald-300 border border-emerald-200/80 dark:border-emerald-800 text-xs font-bold transition shadow-xs"
                           title="Chat on WhatsApp">
                            <i class="ph-bold ph-whatsapp-logo text-base text-emerald-600"></i>
                            <span>WhatsApp</span>
                        </a>
                    @endif

                    <a href="{{ route('admin.subscriptions.assign', ['user_id' => $user->id]) }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-sm shadow-blue-500/20 active:scale-[0.98] transition">
                        <i class="ph-bold ph-gift text-sm"></i>
                        <span>Provide Offer / Assign Plan</span>
                    </a>
                </div>

            </div>
        </div>

        {{-- 3. INTERACTIVE JOURNEY PROCESS FLOW --}}
        @php
            $hasSearched = $searchEvents->count() > 0;
            $hasSubscribed = $user->activePlan() !== null || $user->userPlans->count() > 0;
            $hasUnlocked = $contactViews->count() > 0;
            $hasInquired = $user->inquiries->count() > 0;

            // Compute current stage
            $currentStage = 1;
            if ($hasSearched) $currentStage = 2;
            if ($hasSubscribed) $currentStage = 3;
            if ($hasUnlocked || $hasInquired) $currentStage = 4;
        @endphp

        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-5 sm:p-6 shadow-xs">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-xs font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 flex items-center gap-2">
                        <i class="ph-bold ph-git-commit text-blue-600"></i>
                        Member Conversion Flow & Pipeline
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">Live status across member lifecycle steps</p>
                </div>
                <span class="text-xs font-bold px-2.5 py-1 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">
                    Stage {{ $currentStage }} of 4
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 relative">
                
                {{-- Step 1: Registration --}}
                <div class="relative p-3.5 rounded-xl border bg-emerald-50/50 dark:bg-emerald-950/20 border-emerald-200 dark:border-emerald-800/60 transition">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center text-sm font-bold shrink-0">
                            <i class="ph-bold ph-check"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-emerald-900 dark:text-emerald-200">1. Onboarding</p>
                            <p class="text-[11px] text-emerald-700 dark:text-emerald-400 truncate">Account Verified</p>
                        </div>
                    </div>
                </div>

                {{-- Step 2: Search Intent --}}
                <div class="relative p-3.5 rounded-xl border {{ $hasSearched ? 'bg-emerald-50/50 dark:bg-emerald-950/20 border-emerald-200 dark:border-emerald-800/60' : 'bg-slate-50 dark:bg-slate-800/40 border-slate-200 dark:border-slate-800' }} transition">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg {{ $hasSearched ? 'bg-emerald-600 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-500' }} flex items-center justify-center text-sm font-bold shrink-0">
                            <i class="ph-bold {{ $hasSearched ? 'ph-check' : 'ph-magnifying-glass' }}"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold {{ $hasSearched ? 'text-emerald-900 dark:text-emerald-200' : 'text-slate-700 dark:text-slate-300' }}">2. Search Intent</p>
                            <p class="text-[11px] {{ $hasSearched ? 'text-emerald-700 dark:text-emerald-400' : 'text-slate-400' }} truncate">
                                {{ $hasSearched ? $searchEvents->count() . ' recorded searches' : 'No searches yet' }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Step 3: Subscription & Offers --}}
                <div class="relative p-3.5 rounded-xl border {{ $hasSubscribed ? 'bg-emerald-50/50 dark:bg-emerald-950/20 border-emerald-200 dark:border-emerald-800/60' : 'bg-amber-50/40 dark:bg-amber-950/20 border-amber-200/70 dark:border-amber-800/50' }} transition">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg {{ $hasSubscribed ? 'bg-emerald-600 text-white' : 'bg-amber-500 text-white' }} flex items-center justify-center text-sm font-bold shrink-0">
                            <i class="ph-bold {{ $hasSubscribed ? 'ph-check' : 'ph-ticket' }}"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold {{ $hasSubscribed ? 'text-emerald-900 dark:text-emerald-200' : 'text-amber-900 dark:text-amber-200' }}">3. Plan Upgrade</p>
                            <p class="text-[11px] {{ $hasSubscribed ? 'text-emerald-700 dark:text-emerald-400' : 'text-amber-700 dark:text-amber-400' }} truncate">
                                {{ $user->activePlan() ? ($user->activePlan()->plan?->name ?? 'Active Plan') : 'Offer Opportunity' }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Step 4: Contact Unlocks & Deals --}}
                <div class="relative p-3.5 rounded-xl border {{ ($hasUnlocked || $hasInquired) ? 'bg-emerald-50/50 dark:bg-emerald-950/20 border-emerald-200 dark:border-emerald-800/60' : 'bg-slate-50 dark:bg-slate-800/40 border-slate-200 dark:border-slate-800' }} transition">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg {{ ($hasUnlocked || $hasInquired) ? 'bg-emerald-600 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-500' }} flex items-center justify-center text-sm font-bold shrink-0">
                            <i class="ph-bold {{ ($hasUnlocked || $hasInquired) ? 'ph-check' : 'ph-lock-open' }}"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold {{ ($hasUnlocked || $hasInquired) ? 'text-emerald-900 dark:text-emerald-200' : 'text-slate-700 dark:text-slate-300' }}">4. Unlocks & Leads</p>
                            <p class="text-[11px] {{ ($hasUnlocked || $hasInquired) ? 'text-emerald-700 dark:text-emerald-400' : 'text-slate-400' }} truncate">
                                {{ $contactViews->count() }} unlocks &bull; {{ $user->inquiries->count() }} inquiries
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- 4. KPI METRIC SUMMARY CARDS --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            
            {{-- Metric 1: Searches --}}
            <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Search Queries</p>
                    <p class="text-2xl font-black text-slate-900 dark:text-white mt-1">{{ $searchEvents->count() }}</p>
                    <p class="text-[11px] text-slate-500 mt-0.5">Recorded filters</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xl shrink-0">
                    <i class="ph-bold ph-magnifying-glass"></i>
                </div>
            </div>

            {{-- Metric 2: Contact Unlocks --}}
            <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Contact Unlocks</p>
                    <p class="text-2xl font-black text-slate-900 dark:text-white mt-1">{{ $contactViews->count() }}</p>
                    <p class="text-[11px] text-slate-500 mt-0.5">Owner details seen</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xl shrink-0">
                    <i class="ph-bold ph-lock-open"></i>
                </div>
            </div>

            {{-- Metric 3: Inquiries --}}
            <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Direct Inquiries</p>
                    <p class="text-2xl font-black text-slate-900 dark:text-white mt-1">{{ $user->inquiries->count() }}</p>
                    <p class="text-[11px] text-slate-500 mt-0.5">Contact requests</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xl shrink-0">
                    <i class="ph-bold ph-chat-centered-text"></i>
                </div>
            </div>

            {{-- Metric 4: Properties Listed (If owner) or Subscriptions --}}
            <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Properties Posted</p>
                    <p class="text-2xl font-black text-slate-900 dark:text-white mt-1">{{ $user->properties->count() }}</p>
                    <p class="text-[11px] text-slate-500 mt-0.5">Listed listings</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center text-xl shrink-0">
                    <i class="ph-bold ph-buildings"></i>
                </div>
            </div>

        </div>

        {{-- 5. MAIN 2-COLUMN LAYOUT: SIDEBAR (PLAN INFO) & RIGHT (ACTIVITY FEEDS) --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- Left Column: Subscriptions & Account Info --}}
            <div class="lg:col-span-1 space-y-6">
                
                {{-- Current Subscription Card --}}
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-6 shadow-xs">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xs font-black uppercase tracking-wider text-slate-400 dark:text-slate-500 flex items-center gap-1.5">
                            <i class="ph-bold ph-ticket text-blue-600"></i>
                            Current Subscription
                        </h2>
                        <a href="{{ route('admin.subscriptions.assign', ['user_id' => $user->id]) }}" class="text-[11px] font-bold text-blue-600 hover:text-blue-700" title="Manage / Upgrade Plan">
                            Manage &rarr;
                        </a>
                    </div>

                    @php $activePlan = $user->activePlan(); @endphp
                    @if($activePlan)
                        <div class="p-4 rounded-xl bg-gradient-to-br from-blue-50/70 to-indigo-50/50 dark:from-blue-950/40 dark:to-indigo-950/30 border border-blue-200/70 dark:border-blue-900/60 mb-5">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs font-black uppercase tracking-wider text-blue-700 dark:text-blue-300">Active Membership</span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200">
                                    {{ $activePlan->status ?? 'Active' }}
                                </span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ $activePlan->plan?->name ?? 'Standard Plan' }}</h3>
                            <div class="flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400 mt-1">
                                <i class="ph-bold ph-clock text-slate-400"></i>
                                <span>Expires: {{ $activePlan->expires_at ? $activePlan->expires_at->format('d M, Y') : 'Never (Lifetime)' }}</span>
                            </div>
                        </div>

                        {{-- Quota progress --}}
                        @php
                            $limit = $activePlan->plan?->contact_limit ?? 0;
                            $used = (int) $activePlan->contacts_used;
                            $pct = $limit > 0 ? min(100, round(($used / $limit) * 100)) : 0;
                        @endphp
                        <div class="space-y-2 mb-4">
                            <div class="flex justify-between items-center text-xs">
                                <span class="font-semibold text-slate-600 dark:text-slate-400">Contact Unlocks Used</span>
                                <span class="font-bold text-slate-900 dark:text-white">{{ $used }} / {{ $limit > 0 ? $limit : 'Unlimited' }}</span>
                            </div>
                            <div class="w-full bg-slate-100 dark:bg-slate-800 h-2.5 rounded-full overflow-hidden p-0.5 border border-slate-200/60 dark:border-slate-700">
                                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-full rounded-full transition-all" style="width: {{ $pct }}%"></div>
                            </div>
                            <p class="text-[11px] text-slate-400 text-right">{{ $limit > 0 ? max(0, $limit - $used) . ' remaining' : 'No cap' }}</p>
                        </div>
                    @else
                        <div class="p-5 rounded-xl border border-dashed border-slate-200 dark:border-slate-800 text-center bg-slate-50/50 dark:bg-slate-900/40 mb-4">
                            <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 flex items-center justify-center mx-auto mb-2 text-lg">
                                <i class="ph-bold ph-ticket"></i>
                            </div>
                            <p class="text-xs font-semibold text-slate-700 dark:text-slate-300">No Active Subscription</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">This member has not purchased a plan yet.</p>
                            <a href="{{ route('admin.subscriptions.assign', ['user_id' => $user->id]) }}" class="inline-flex items-center gap-1.5 mt-3 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition shadow-xs">
                                <i class="ph-bold ph-plus-circle"></i>
                                <span>Assign or Offer Plan</span>
                            </a>
                        </div>
                    @endif

                    {{-- Plan History if multiple --}}
                    @if($user->userPlans->count() > 0)
                        <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">Subscription History ({{ $user->userPlans->count() }})</p>
                            <div class="space-y-2">
                                @foreach($user->userPlans->take(3) as $histPlan)
                                    <div class="flex items-center justify-between text-xs py-1.5 px-2 rounded-lg bg-slate-50 dark:bg-slate-800/60">
                                        <div class="min-w-0">
                                            <p class="font-semibold text-slate-800 dark:text-slate-200 truncate">{{ $histPlan->plan?->name ?? 'Plan #' . $histPlan->id }}</p>
                                            <p class="text-[10px] text-slate-400">{{ $histPlan->created_at->format('d M Y') }}</p>
                                        </div>
                                        <span class="text-[10px] font-bold uppercase px-1.5 py-0.5 rounded {{ $histPlan->status === 'approved' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-stone-200 text-stone-700 dark:bg-slate-700 dark:text-slate-300' }}">
                                            {{ $histPlan->status }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Member Profile Meta & Device Info --}}
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-6 shadow-xs">
                    <h2 class="text-xs font-black uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-4 flex items-center gap-1.5">
                        <i class="ph-bold ph-identification-card text-blue-600"></i>
                        Profile Metadata
                    </h2>

                    <div class="space-y-3 text-xs">
                        <div class="flex items-center justify-between py-1.5 border-b border-slate-100 dark:border-slate-800">
                            <span class="text-slate-500">Registered</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $user->created_at ? $user->created_at->format('d M Y, h:i A') : 'N/A' }}</span>
                        </div>
                        <div class="flex items-center justify-between py-1.5 border-b border-slate-100 dark:border-slate-800">
                            <span class="text-slate-500">Email Status</span>
                            <span class="font-semibold {{ $user->email_verified_at ? 'text-emerald-600' : 'text-amber-600' }}">
                                {{ $user->email_verified_at ? 'Verified' : 'Unverified' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between py-1.5 border-b border-slate-100 dark:border-slate-800">
                            <span class="text-slate-500">Phone Status</span>
                            <span class="font-semibold {{ $user->isPhoneVerified() ? 'text-emerald-600' : 'text-slate-500' }}">
                                {{ $user->isPhoneVerified() ? 'Verified' : ($user->phone ? 'Added' : 'Not added') }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between py-1.5">
                            <span class="text-slate-500">Linked Visitors</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $user->visitors->count() }} sessions</span>
                        </div>
                    </div>
                </div>

                {{-- Posted Properties (If user is an owner or has listings) --}}
                @if($user->properties && $user->properties->count() > 0)
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-6 shadow-xs">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xs font-black uppercase tracking-wider text-slate-400 dark:text-slate-500 flex items-center gap-1.5">
                                <i class="ph-bold ph-buildings text-blue-600"></i>
                                Listings Posted ({{ $user->properties->count() }})
                            </h2>
                        </div>
                        <div class="space-y-3">
                            @foreach($user->properties->take(4) as $property)
                                <a href="{{ route('properties.show', $property) }}" target="_blank" class="block p-3 rounded-xl bg-slate-50 hover:bg-slate-100 dark:bg-slate-800/60 dark:hover:bg-slate-800 border border-slate-200/60 dark:border-slate-700/60 transition group">
                                    <div class="flex items-center justify-between">
                                        <p class="text-xs font-bold text-slate-900 dark:text-white truncate group-hover:text-blue-600">{{ $property->title }}</p>
                                        <i class="ph-bold ph-arrow-up-right text-slate-400 text-xs"></i>
                                    </div>
                                    <p class="text-[11px] text-slate-500 truncate mt-0.5">{{ $property->location ?? $property->city ?? 'Location not specified' }}</p>
                                    <div class="flex items-center gap-2 mt-1.5">
                                        <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300">
                                            ₹{{ number_format($property->price) }}
                                        </span>
                                        <span class="text-[10px] text-slate-400 capitalize">{{ $property->status ?? 'active' }}</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

            {{-- Right Column: Activity Stream (Searches, Unlocks, Inquiries) --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- TABBED OR STRUCTURED ACTIVITY SECTIONS --}}

                {{-- Section A: Property Searches & Requirement Criteria --}}
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs overflow-hidden">
                    <div class="px-6 py-4.5 border-b border-slate-100 dark:border-slate-800 flex flex-wrap items-center justify-between gap-2 bg-slate-50/50 dark:bg-slate-900/60">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 flex items-center justify-center font-bold">
                                <i class="ph-bold ph-magnifying-glass"></i>
                            </div>
                            <div>
                                <h2 class="text-sm font-bold text-slate-900 dark:text-white tracking-tight">Property Searches & Telemetry</h2>
                                <p class="text-[11px] text-slate-500">Captured search filters, locations, and user requirements</p>
                            </div>
                        </div>
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-blue-50 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 border border-blue-200/60 dark:border-blue-800">
                            {{ $searchEvents->count() }} Events
                        </span>
                    </div>

                    @if($searchEvents->count() > 0)
                        <div class="divide-y divide-slate-100 dark:divide-slate-800/80 max-h-[460px] overflow-y-auto">
                            @foreach($searchEvents as $search)
                                @php
                                    $criteria = method_exists($search, 'getSearchCriteriaList') ? $search->getSearchCriteriaList() : [];
                                    $targetLoc = method_exists($search, 'getTargetLocationDisplay') ? $search->getTargetLocationDisplay() : 'All Locations';
                                    $resultsCount = method_exists($search, 'getResultsCount') ? $search->getResultsCount() : null;
                                @endphp
                                <div class="p-5 hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-2">
                                        <div class="flex items-center gap-2.5">
                                            <span class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
                                                <i class="ph-bold ph-map-pin"></i>
                                            </span>
                                            <div>
                                                <h3 class="font-bold text-slate-900 dark:text-white text-sm">{{ $targetLoc ?: 'Browse All' }}</h3>
                                                <p class="text-[11px] text-slate-400">
                                                    {{ $search->created_at ? $search->created_at->format('d M Y, h:i A') : 'Recorded' }}
                                                    @if($search->created_at)
                                                        &bull; <span class="text-slate-500">{{ $search->created_at->diffForHumans() }}</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>

                                        @if($search->page_url || $search->url)
                                            <a href="{{ $search->page_url ?: $search->url }}" target="_blank"
                                               class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-600 dark:text-blue-400 hover:text-blue-700 bg-blue-50 dark:bg-blue-950/40 hover:bg-blue-100 px-3 py-1.5 rounded-lg self-start sm:self-auto transition-colors" title="View Search Query">
                                                <span>View Search Results</span>
                                                <i class="ph-bold ph-arrow-up-right text-[10px]"></i>
                                            </a>
                                        @endif
                                    </div>

                                    @if(!empty($criteria))
                                        <div class="flex flex-wrap items-center gap-1.5 mt-3 pl-10">
                                            @foreach($criteria as $c)
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[11px] font-semibold border {{ $c['badge'] ?? 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700' }}">
                                                    <span class="opacity-70">{{ $c['label'] }}:</span>
                                                    <strong>{{ $c['value'] }}</strong>
                                                </span>
                                            @endforeach
                                            @if($resultsCount !== null && $resultsCount >= 0)
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[11px] font-bold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                                    <i class="ph-bold ph-check-circle"></i>
                                                    <strong>{{ $resultsCount }}</strong> matches displayed
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-12 text-center">
                            <div class="w-14 h-14 rounded-2xl bg-blue-50 dark:bg-blue-950/40 text-blue-500 flex items-center justify-center mx-auto mb-3 text-2xl">
                                <i class="ph ph-magnifying-glass"></i>
                            </div>
                            <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200">No Search Telemetry Yet</h3>
                            <p class="text-xs text-slate-400 max-w-sm mx-auto mt-1">
                                As soon as the user executes property searches with locality or budget filters, the details will populate here in real time.
                            </p>
                        </div>
                    @endif
                </div>

                {{-- Section B: Property Contact Unlocks --}}
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs overflow-hidden">
                    <div class="px-6 py-4.5 border-b border-slate-100 dark:border-slate-800 flex flex-wrap items-center justify-between gap-2 bg-slate-50/50 dark:bg-slate-900/60">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 flex items-center justify-center font-bold">
                                <i class="ph-bold ph-lock-open"></i>
                            </div>
                            <div>
                                <h2 class="text-sm font-bold text-slate-900 dark:text-white tracking-tight">Unlocked Property Contacts</h2>
                                <p class="text-[11px] text-slate-500">Properties where this user revealed owner / seller phone numbers</p>
                            </div>
                        </div>
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-indigo-50 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-300 border border-indigo-200/60 dark:border-indigo-800">
                            {{ $contactViews->count() }} Unlocks
                        </span>
                    </div>

                    @if($contactViews->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs">
                                <thead>
                                    <tr class="bg-slate-50/80 dark:bg-slate-800/40 text-[11px] font-bold text-slate-400 uppercase border-b border-slate-100 dark:border-slate-800">
                                        <th class="px-6 py-3">Property</th>
                                        <th class="px-6 py-3">Plan Used</th>
                                        <th class="px-6 py-3">Unlocked At</th>
                                        <th class="px-6 py-3 text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                                    @foreach($contactViews as $view)
                                        <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/30 transition">
                                            <td class="px-6 py-3.5">
                                                @if($view->property)
                                                    <a href="{{ route('properties.show', $view->property) }}" target="_blank" class="font-bold text-slate-900 dark:text-white hover:text-blue-600 block line-clamp-1">
                                                        {{ $view->property->title }}
                                                    </a>
                                                    <p class="text-[11px] text-slate-400 mt-0.5 flex items-center gap-1">
                                                        <i class="ph-bold ph-map-pin text-[10px]"></i>
                                                        {{ $view->property->location ?? $view->property->city ?? 'Location N/A' }}
                                                    </p>
                                                @else
                                                    <span class="text-slate-400 italic">Property #{{ $view->property_id }} (Removed)</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-3.5">
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                                    {{ $view->userPlan?->plan?->name ?? 'Standard Pass' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-3.5 text-slate-500 whitespace-nowrap">
                                                {{ $view->created_at ? $view->created_at->format('d M Y, h:i A') : 'N/A' }}
                                            </td>
                                            <td class="px-6 py-3.5 text-right whitespace-nowrap">
                                                @if($view->property)
                                                    <a href="{{ route('properties.show', $view->property) }}" target="_blank" class="inline-flex items-center gap-1 text-xs font-bold text-blue-600 hover:text-blue-700" title="View Property">
                                                        <span>View Listing</span>
                                                        <i class="ph-bold ph-arrow-up-right text-[10px]"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-12 text-center">
                            <div class="w-14 h-14 rounded-2xl bg-indigo-50 dark:bg-indigo-950/40 text-indigo-500 flex items-center justify-center mx-auto mb-3 text-2xl">
                                <i class="ph ph-lock-open"></i>
                            </div>
                            <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200">No Contacts Unlocked Yet</h3>
                            <p class="text-xs text-slate-400 max-w-sm mx-auto mt-1">
                                When this user unlocks property owner contact numbers via their subscription, they will be listed here.
                            </p>
                        </div>
                    @endif
                </div>

                {{-- Section C: Direct Inquiries & Messages --}}
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs overflow-hidden">
                    <div class="px-6 py-4.5 border-b border-slate-100 dark:border-slate-800 flex flex-wrap items-center justify-between gap-2 bg-slate-50/50 dark:bg-slate-900/60">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 flex items-center justify-center font-bold">
                                <i class="ph-bold ph-chat-centered-text"></i>
                            </div>
                            <div>
                                <h2 class="text-sm font-bold text-slate-900 dark:text-white tracking-tight">Recent Inquiries & Requests</h2>
                                <p class="text-[11px] text-slate-500">Contact form messages sent to property owners or support</p>
                            </div>
                        </div>
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800">
                            {{ $user->inquiries->count() }} Inquiries
                        </span>
                    </div>

                    @if($user->inquiries->count() > 0)
                        <div class="divide-y divide-slate-100 dark:divide-slate-800/80 max-h-[400px] overflow-y-auto">
                            @foreach($user->inquiries->sortByDesc('created_at') as $inquiry)
                                <div class="p-5 hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-2">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                            <p class="font-bold text-sm text-slate-900 dark:text-white">
                                                {{ $inquiry->property?->title ?? 'General Inquiry' }}
                                            </p>
                                        </div>
                                        <span class="text-[11px] text-slate-400">
                                            {{ $inquiry->created_at ? $inquiry->created_at->format('d M Y, h:i A') : '' }}
                                            ({{ $inquiry->created_at ? $inquiry->created_at->diffForHumans() : '' }})
                                        </span>
                                    </div>
                                    <div class="pl-4 border-l-2 border-emerald-400/60 dark:border-emerald-500/40 mt-2">
                                        <p class="text-xs text-slate-600 dark:text-slate-300 italic">
                                            "{{ $inquiry->message ?? 'Interested in this property, please share details.' }}"
                                        </p>
                                    </div>
                                    @if($inquiry->property)
                                        <div class="mt-2.5 pl-4 flex items-center justify-between text-[11px]">
                                            <span class="text-slate-400">{{ $inquiry->property->location ?? $inquiry->property->city }}</span>
                                            <a href="{{ route('properties.show', $inquiry->property) }}" target="_blank" class="text-blue-600 hover:underline font-bold inline-flex items-center gap-1" title="View Property Details">
                                                <span>Listing Details</span>
                                                <i class="ph-bold ph-arrow-up-right text-[9px]"></i>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-12 text-center">
                            <div class="w-14 h-14 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-500 flex items-center justify-center mx-auto mb-3 text-2xl">
                                <i class="ph ph-chat-circle"></i>
                            </div>
                            <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200">No Direct Inquiries Yet</h3>
                            <p class="text-xs text-slate-400 max-w-sm mx-auto mt-1">
                                Messages and inquiry forms submitted by this member will be tracked and displayed here.
                            </p>
                        </div>
                    @endif
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
