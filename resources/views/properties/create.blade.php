@extends('layouts.app')

@section('title', 'List Your Property - Zero Brokerage | UnlockRentals')
@section('meta_description', 'List your rental house, flat, shop, PG or commercial property on UnlockRentals. Connect with thousands of verified direct tenants with zero brokerage.')

@section('content')

<section class="min-h-screen pt-20 sm:pt-28 pb-32 sm:pb-24 bg-[#f8fafc] dark:bg-slate-950 relative overflow-hidden" id="create-property">
    <style>
        .step-pane {
            animation: fadeInStep 0.22s ease-out;
        }
        @keyframes fadeInStep {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            .chatbot-trigger, .feedback-modal-trigger {
                display: none !important;
            }
        }
        /* Remove default number input up and down spin arrows */
        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none !important;
            margin: 0 !important;
        }
        input[type="number"] {
            -moz-appearance: textfield !important;
            appearance: textfield !important;
        }

        /* Luxury Cover Photo Selection Styles */
        .cover-photo-guidance-card {
            background: linear-gradient(135deg, rgba(239, 246, 255, 0.95) 0%, rgba(240, 249, 255, 0.95) 100%);
            border: 1px solid rgba(191, 219, 254, 0.9);
        }
        .dark .cover-photo-guidance-card {
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.95) 0%, rgba(23, 37, 84, 0.6) 100%);
            border: 1px solid rgba(59, 130, 246, 0.3);
        }
        .preview-card {
            position: relative;
            border-radius: 1rem;
            overflow: hidden;
            cursor: pointer;
            user-select: none;
            transition: transform 0.22s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.22s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.2s ease;
            background-color: #0f172a;
        }
        .preview-card.is-primary-card {
            border: 3px solid #2563eb !important;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.35), 0 12px 24px -4px rgba(37, 99, 235, 0.3) !important;
            transform: translateY(-2px);
            z-index: 5;
        }
        .preview-card:not(.is-primary-card) {
            border: 2px solid rgba(226, 232, 240, 0.95);
        }
        .dark .preview-card:not(.is-primary-card) {
            border: 1.5px solid rgba(51, 65, 85, 0.85);
        }
        .preview-card:not(.is-primary-card):hover {
            border-color: #3b82f6 !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px -2px rgba(0, 0, 0, 0.25);
        }
        /* 1. COVER BADGE PILL - Top Left, strictly visible ONLY on is-primary-card */
        .cover-badge-pill {
            display: none !important;
            position: absolute;
            top: 6px;
            left: 6px;
            background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
            color: #ffffff !important;
            font-size: 9.5px;
            font-weight: 800;
            letter-spacing: 0.02em;
            padding: 2.5px 7px;
            border-radius: 9999px;
            box-shadow: 0 2px 8px rgba(29, 78, 216, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.9);
            align-items: center;
            gap: 3.5px;
            white-space: nowrap;
            z-index: 10;
            pointer-events: none;
        }
        .preview-card.is-primary-card .cover-badge-pill {
            display: inline-flex !important;
        }

        /* 2. SET COVER PILL - Centered, visible ONLY on hover of non-primary card */
        .set-cover-pill {
            display: none !important;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            color: #ffffff !important;
            font-size: 10px;
            font-weight: 700;
            padding: 4px 9px;
            border-radius: 9999px;
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
            align-items: center;
            gap: 4px;
            white-space: nowrap;
            z-index: 10;
            pointer-events: none;
            transition: all 0.18s ease;
        }
        .preview-card:not(.is-primary-card):hover .set-cover-pill {
            display: inline-flex !important;
        }

        /* 3. DELETE BUTTON - Top Right */
        .photo-delete-btn {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 22px;
            height: 22px;
            border-radius: 6px;
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            color: #ffffff !important;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
            transition: all 0.18s ease;
            cursor: pointer;
            z-index: 20;
        }
        .photo-delete-btn:hover {
            background: #dc2626 !important;
            border-color: #ef4444 !important;
            transform: scale(1.1);
            box-shadow: 0 3px 8px rgba(220, 38, 38, 0.45);
        }

        /* Deprecate / Hide any legacy clutter */
        .cover-check-indicator, .cover-photo-footer {
            display: none !important;
        }
    </style>
    {{-- Ambient Background Gradients --}}
    <div class="absolute top-0 left-0 w-full h-[500px] bg-gradient-to-b from-blue-500/[0.04] via-indigo-500/[0.02] to-transparent pointer-events-none"></div>
    <div class="absolute -top-32 -left-32 w-96 h-96 bg-blue-500/10 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute top-1/3 right-0 w-80 h-80 bg-indigo-500/10 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="max-w-4xl mx-auto px-3 sm:px-6 lg:px-8 relative z-10">

        {{-- Page Header --}}
        <div class="mb-4 sm:mb-8 text-center">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 text-[10px] sm:text-xs font-extrabold uppercase tracking-widest rounded-full mb-2 border border-blue-100 dark:border-blue-900/50">
                <i class="ph-bold ph-sparkle text-xs"></i> 100% Free Owner Listing
            </span>
            <h1 class="text-2xl sm:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-tight">
                List Your Property
            </h1>
            <p class="text-slate-500 dark:text-slate-400 text-xs sm:text-sm font-normal max-w-md mx-auto mt-1 leading-snug">
                Reach thousands of verified tenants with zero brokerage.
            </p>
        </div>

        {{-- Interactive 4-Step Navigation Indicator --}}
        <div class="mb-5 sm:mb-8 p-3.5 sm:p-5 bg-white dark:bg-slate-900 rounded-xl sm:rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xs" id="stepper-container">
            <div class="flex items-center justify-between mb-3 px-1 sm:px-2">
                <span class="text-[11px] sm:text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider" id="step-badge-text">
                    Step <span id="current-step-num" class="text-blue-600 dark:text-blue-400 font-extrabold">1</span> of 4: <span id="current-step-name" class="text-slate-900 dark:text-white font-extrabold">Basic Info</span>
                </span>
                <span class="text-[11px] sm:text-xs font-extrabold text-blue-600 dark:text-blue-400" id="step-pct-text">25% Completed</span>
            </div>

            {{-- Visual Progress Track --}}
            <div class="relative mb-3.5 sm:mb-4 px-1">
                <div class="w-full bg-slate-100 dark:bg-slate-800 h-1.5 sm:h-2 rounded-full overflow-hidden">
                    <div id="step-progress-bar" class="bg-gradient-to-r from-blue-600 to-indigo-600 h-full w-1/4 rounded-full transition-all duration-300"></div>
                </div>
            </div>

            <div class="grid grid-cols-4 gap-1.5 sm:gap-4 text-center">
                {{-- Step 1 Indicator --}}
                <button type="button" onclick="goToStep(1)" id="step-btn-1" class="flex flex-col items-center gap-1 group transition-all cursor-pointer">
                    <div id="step-circle-1" class="w-7 h-7 sm:w-9 sm:h-9 rounded-lg sm:rounded-xl bg-blue-600 text-white font-extrabold text-xs sm:text-sm flex items-center justify-center shadow-md ring-2 sm:ring-4 ring-blue-600/20 transition-all">
                        1
                    </div>
                    <span id="step-label-1" class="text-[10px] sm:text-xs font-bold text-blue-600 dark:text-blue-400 transition-colors">Basic Info</span>
                </button>

                {{-- Step 2 Indicator --}}
                <button type="button" onclick="goToStep(2)" id="step-btn-2" class="flex flex-col items-center gap-1 group transition-all cursor-pointer">
                    <div id="step-circle-2" class="w-7 h-7 sm:w-9 sm:h-9 rounded-lg sm:rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 font-extrabold text-xs sm:text-sm flex items-center justify-center transition-all">
                        2
                    </div>
                    <span id="step-label-2" class="text-[10px] sm:text-xs font-semibold text-slate-500 dark:text-slate-400 transition-colors">Pricing</span>
                </button>

                {{-- Step 3 Indicator --}}
                <button type="button" onclick="goToStep(3)" id="step-btn-3" class="flex flex-col items-center gap-1 group transition-all cursor-pointer">
                    <div id="step-circle-3" class="w-7 h-7 sm:w-9 sm:h-9 rounded-lg sm:rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 font-extrabold text-xs sm:text-sm flex items-center justify-center transition-all">
                        3
                    </div>
                    <span id="step-label-3" class="text-[10px] sm:text-xs font-semibold text-slate-500 dark:text-slate-400 transition-colors">Location</span>
                </button>

                {{-- Step 4 Indicator --}}
                <button type="button" onclick="goToStep(4)" id="step-btn-4" class="flex flex-col items-center gap-1 group transition-all cursor-pointer">
                    <div id="step-circle-4" class="w-7 h-7 sm:w-9 sm:h-9 rounded-lg sm:rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 font-extrabold text-xs sm:text-sm flex items-center justify-center transition-all">
                        4
                    </div>
                    <span id="step-label-4" class="text-[10px] sm:text-xs font-semibold text-slate-500 dark:text-slate-400 transition-colors">Photos</span>
                </button>
            </div>
        </div>

        {{-- Step Validation Alert Toast / Banner --}}
        <div id="step-validation-alert" class="hidden mb-4 sm:mb-6 p-3.5 sm:p-4 bg-red-50 dark:bg-red-950/50 border border-red-200 dark:border-red-800 rounded-xl sm:rounded-2xl text-xs sm:text-sm font-bold text-red-700 dark:text-red-300 flex items-center justify-between gap-3 shadow-sm">
            <div class="flex items-center gap-2.5">
                <i class="ph-bold ph-warning-circle text-red-600 text-lg sm:text-xl shrink-0"></i>
                <span id="step-validation-alert-msg">Please complete all required fields in this step.</span>
            </div>
            <button type="button" onclick="document.getElementById('step-validation-alert').classList.add('hidden')" class="text-red-400 hover:text-red-700 dark:hover:text-red-200 text-base">
                <i class="ph-bold ph-x"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('properties.store') }}" enctype="multipart/form-data" class="space-y-4 sm:space-y-6" id="create-property-form" data-ur-loader-skip="true" data-no-smooth="true" novalidate>
            @csrf
            {{-- ======================================================== --}}
            {{-- STEP 1: Basic Information & Key Specifications            --}}
            {{-- ======================================================== --}}
            <div id="step-pane-1" class="step-pane space-y-4 sm:space-y-6">

            @if(auth()->check() && auth()->user()->isAdmin())
                @php
                    $initBehalfMode = old('behalf_mode', request('behalf_mode', (request('behalf_name') || request('behalf_phone') ? 'new' : (request('behalf_user_id') ? 'existing' : 'new'))));
                    $initBehalfName = old('behalf_name', request('behalf_name', ''));
                    $initBehalfPhone = old('behalf_phone', request('behalf_phone', ''));
                    $initBehalfEmail = old('behalf_email', request('behalf_email', ''));
                    $initBehalfUserId = old('behalf_user_id', request('behalf_user_id', ''));
                @endphp
                {{-- Admin Mode: Listing on Behalf of Client / Owner Banner & Controls --}}
                <div class="bg-gradient-to-br from-orange-50 via-amber-50/50 to-orange-50/30 dark:from-slate-900 dark:via-slate-900/90 dark:to-slate-900 border-2 border-orange-300 dark:border-orange-500/50 rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-sm relative overflow-hidden" id="admin-behalf-card">
                    <div class="absolute -right-6 -bottom-6 w-32 h-32 rounded-full bg-orange-500/10 dark:bg-orange-500/5 blur-2xl pointer-events-none"></div>

                    <div class="flex items-center justify-between flex-wrap gap-3 pb-3 mb-4 border-b border-orange-200/80 dark:border-slate-800">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-orange-600 text-white flex items-center justify-center text-lg shadow-sm shadow-orange-600/30 shrink-0">
                                <i class="ph-bold ph-megaphone"></i>
                            </div>
                            <div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h2 class="text-sm sm:text-base font-extrabold text-slate-900 dark:text-white">Admin Listing Mode: Post on Behalf of Client</h2>
                                    <span class="px-2 py-0.5 rounded-full bg-orange-100 dark:bg-orange-950/80 text-orange-700 dark:text-orange-400 font-extrabold text-[10px] uppercase tracking-wider">
                                        Auto-Approved
                                    </span>
                                </div>
                                <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400">List this property for a client. If they don't have an account, their owner profile will be automatically created!</p>
                            </div>
                        </div>

                        {{-- Mode Pill Selector --}}
                        <div class="inline-flex p-1 bg-white/90 dark:bg-slate-800 border border-orange-200 dark:border-slate-700 rounded-xl shadow-2xs">
                            <label class="cursor-pointer">
                                <input type="radio" name="behalf_mode" value="new" class="sr-only" {{ $initBehalfMode === 'new' ? 'checked' : '' }} onchange="toggleAdminBehalfMode('new')">
                                <span class="behalf-tab px-3 py-1.5 rounded-lg text-xs font-bold transition-all block {{ $initBehalfMode === 'new' ? 'bg-orange-600 text-white shadow-2xs' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900' }}" id="tab-btn-new">
                                    ✨ New Client (Auto-Create)
                                </span>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="behalf_mode" value="existing" class="sr-only" {{ $initBehalfMode === 'existing' ? 'checked' : '' }} onchange="toggleAdminBehalfMode('existing')">
                                <span class="behalf-tab px-3 py-1.5 rounded-lg text-xs font-bold transition-all block {{ $initBehalfMode === 'existing' ? 'bg-orange-600 text-white shadow-2xs' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900' }}" id="tab-btn-existing">
                                    👤 Existing User
                                </span>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="behalf_mode" value="self" class="sr-only" {{ $initBehalfMode === 'self' ? 'checked' : '' }} onchange="toggleAdminBehalfMode('self')">
                                <span class="behalf-tab px-3 py-1.5 rounded-lg text-xs font-bold transition-all block {{ $initBehalfMode === 'self' ? 'bg-orange-600 text-white shadow-2xs' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900' }}" id="tab-btn-self">
                                    🛡️ Post for Myself
                                </span>
                            </label>
                        </div>
                    </div>

                    {{-- Form Panel: New Client Profile (Auto Create) --}}
                    <div id="panel-behalf-new" class="{{ $initBehalfMode === 'new' ? '' : 'hidden' }} space-y-3">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div>
                                <label for="create-behalf-name" class="block text-[11px] sm:text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                                    Client / Owner Name <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <i class="ph-bold ph-user absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                                    <input type="text" name="behalf_name" id="create-behalf-name" value="{{ $initBehalfName }}" placeholder="e.g. Ramesh Sharma"
                                           class="w-full pl-8 pr-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20">
                                </div>
                            </div>

                            <div>
                                <label for="create-behalf-phone" class="block text-[11px] sm:text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                                    Client Mobile Phone <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <i class="ph-bold ph-phone absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                                    <input type="tel" name="behalf_phone" id="create-behalf-phone" value="{{ $initBehalfPhone }}" placeholder="e.g. 9876543210"
                                           oninput="syncBehalfPhoneToProperty(this.value)"
                                           class="w-full pl-8 pr-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20">
                                </div>
                            </div>

                            <div>
                                <label for="create-behalf-email" class="block text-[11px] sm:text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                                    Email Address <span class="text-slate-400 font-normal lowercase text-[10px]">(Optional)</span>
                                </label>
                                <div class="relative">
                                    <i class="ph-bold ph-envelope absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                                    <input type="email" name="behalf_email" id="create-behalf-email" value="{{ $initBehalfEmail }}" placeholder="e.g. ramesh@example.com"
                                           class="w-full pl-8 pr-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20">
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 text-[11px] text-amber-800 dark:text-amber-300 font-medium bg-amber-100/60 dark:bg-amber-950/40 p-2.5 rounded-xl border border-amber-200/70 dark:border-amber-800/40">
                            <i class="ph-bold ph-sparkle text-amber-600 text-sm shrink-0"></i>
                            <span>A new <strong>Owner Profile</strong> will be automatically created in the User Directory with their phone verified upon submitting this property.</span>
                        </div>
                    </div>

                    {{-- Form Panel: Existing Registered User --}}
                    <div id="panel-behalf-existing" class="{{ $initBehalfMode === 'existing' ? '' : 'hidden' }} space-y-2">
                        <label for="create-behalf-user-id" class="block text-[11px] sm:text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                            Select Existing User from Directory <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <select name="behalf_user_id" id="create-behalf-user-id" onchange="onSelectExistingUserChange(this)"
                                    class="w-full pl-3 pr-8 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 cursor-pointer">
                                <option value="">-- Choose User --</option>
                                @if(isset($adminUsers))
                                    @foreach($adminUsers as $u)
                                        <option value="{{ $u->id }}" data-phone="{{ $u->phone }}" {{ (string)$initBehalfUserId === (string)$u->id ? 'selected' : '' }}>
                                            {{ $u->name }} ({{ $u->phone ?: $u->email }}) — {{ ucfirst($u->role) }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    {{-- Form Panel: Post for Myself --}}
                    <div id="panel-behalf-self" class="{{ $initBehalfMode === 'self' ? '' : 'hidden' }} text-xs text-slate-600 dark:text-slate-300">
                        <p>This property will be listed directly under your administrator account (<strong>{{ auth()->user()->name }}</strong>).</p>
                    </div>
                </div>
            @endif

                {{-- 1. Basic Info Section --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 lg:p-8 shadow-xs" id="create-basic-info">
                <div class="flex items-center gap-2.5 sm:gap-3 mb-4 sm:mb-6 pb-3 sm:pb-4 border-b border-slate-100 dark:border-slate-800">
                    <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg sm:rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 flex items-center justify-center text-base sm:text-lg">
                        <i class="ph-bold ph-info"></i>
                    </div>
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">Basic Information</h2>
                        <p class="text-[11px] sm:text-xs text-slate-400">General property headline and listing classification</p>
                    </div>
                </div>

                <div class="space-y-4 sm:space-y-5">
                    {{-- Title --}}
                    <div>
                        <label for="create-title" class="block text-[11px] sm:text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Property Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="create-title" value="{{ old('title') }}" required enterkeyhint="next"
                               class="w-full px-3.5 py-2.5 sm:px-4 sm:py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white placeholder:text-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/15 transition-all"
                               placeholder="e.g. Spacious 3 BHK Semi-Furnished Flat in Sector 57">
                        @error('title') <p class="text-red-500 text-xs mt-1.5 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="create-description" class="block text-[11px] sm:text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Description <span class="text-red-500">*</span></label>
                        <textarea name="description" id="create-description" rows="3" required
                                  class="w-full px-3.5 py-2.5 sm:px-4 sm:py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white placeholder:text-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/15 transition-all resize-none"
                                  placeholder="Describe key features, sunlight, balcony view, and tenant preferences...">{{ old('description') }}</textarea>
                        @error('description') <p class="text-red-500 text-xs mt-1.5 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    {{-- Listing Purpose (Rent vs Sell) --}}
                    <div>
                        <label class="block text-[11px] sm:text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Listing Purpose (Intent) <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-2 gap-2 sm:gap-3 max-w-xl">
                            @php
                                $curPurpose = old('purpose', 'rent');
                            @endphp
                            <label class="relative flex items-center gap-2.5 p-2.5 sm:p-3.5 rounded-xl border cursor-pointer transition-all {{ ($curPurpose === 'rent') ? 'border-blue-600 bg-blue-50/70 dark:bg-blue-950/40 text-blue-700 dark:text-blue-400 font-bold ring-1 ring-blue-600 shadow-sm' : 'border-slate-200 dark:border-slate-700/80 bg-slate-50/50 dark:bg-slate-800/40 hover:border-slate-300 text-slate-600 dark:text-slate-400 font-medium' }}" for="create-purpose-rent">
                                <input type="radio" name="purpose" value="rent" id="create-purpose-rent" {{ $curPurpose === 'rent' ? 'checked' : '' }} class="sr-only" onchange="this.closest('.grid').querySelectorAll('label').forEach(l => l.classList.remove('border-blue-600','bg-blue-50/70','dark:bg-blue-950/40','text-blue-700','dark:text-blue-400','font-bold','ring-1','ring-blue-600','shadow-sm')); this.closest('label').classList.add('border-blue-600','bg-blue-50/70','dark:bg-blue-950/40','text-blue-700','dark:text-blue-400','font-bold','ring-1','ring-blue-600','shadow-sm'); if(window.onPurposeChange) window.onPurposeChange('rent');">
                                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center shrink-0">
                                    <i class="ph-bold ph-key text-sm sm:text-base"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="text-xs sm:text-sm font-bold leading-tight truncate">For Rent</div>
                                    <div class="text-[10px] sm:text-[11px] text-slate-500 dark:text-slate-400 font-normal truncate">Monthly Lease</div>
                                </div>
                            </label>

                            <label class="relative flex items-center gap-2.5 p-2.5 sm:p-3.5 rounded-xl border cursor-pointer transition-all {{ in_array($curPurpose, ['buy', 'sell']) ? 'border-blue-600 bg-blue-50/70 dark:bg-blue-950/40 text-blue-700 dark:text-blue-400 font-bold ring-1 ring-blue-600 shadow-sm' : 'border-slate-200 dark:border-slate-700/80 bg-slate-50/50 dark:bg-slate-800/40 hover:border-slate-300 text-slate-600 dark:text-slate-400 font-medium' }}" for="create-purpose-buy">
                                <input type="radio" name="purpose" value="buy" id="create-purpose-buy" {{ in_array($curPurpose, ['buy', 'sell']) ? 'checked' : '' }} class="sr-only" onchange="this.closest('.grid').querySelectorAll('label').forEach(l => l.classList.remove('border-blue-600','bg-blue-50/70','dark:bg-blue-950/40','text-blue-700','dark:text-blue-400','font-bold','ring-1','ring-blue-600','shadow-sm')); this.closest('label').classList.add('border-blue-600','bg-blue-50/70','dark:bg-blue-950/40','text-blue-700','dark:text-blue-400','font-bold','ring-1','ring-blue-600','shadow-sm'); if(window.onPurposeChange) window.onPurposeChange('buy');">
                                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center shrink-0">
                                    <i class="ph-bold ph-tag text-sm sm:text-base"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="text-xs sm:text-sm font-bold leading-tight truncate">For Sale</div>
                                    <div class="text-[10px] sm:text-[11px] text-slate-500 dark:text-slate-400 font-normal truncate">Direct Outright Sale</div>
                                </div>
                            </label>
                        </div>
                        @error('purpose') <p class="text-red-500 text-xs mt-1.5 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    {{-- Property Type Selector --}}
                    <div>
                        <label class="block text-[11px] sm:text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Property Type <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2 sm:gap-2.5">
                            @php
                                $formTypes = [
                                    'house' => ['label' => 'House / Flat', 'icon' => 'ph-bold ph-house-line'],
                                    'shop' => ['label' => 'Commercial Shop', 'icon' => 'ph-bold ph-storefront'],
                                    'pg-hostel' => ['label' => 'PG / Hostel', 'icon' => 'ph-bold ph-buildings'],
                                    'plot' => ['label' => 'Plot / Land', 'icon' => 'ph-bold ph-map-trifold'],
                                    'hotel' => ['label' => 'Hotel Room', 'icon' => 'ph-bold ph-bed']
                                ];
                            @endphp
                            @foreach($formTypes as $val => $info)
                            <label class="relative flex flex-col items-center justify-center p-2.5 sm:p-3 rounded-xl border cursor-pointer transition-all {{ (old('type', 'house') === $val) ? 'border-blue-600 bg-blue-50/70 dark:bg-blue-950/40 text-blue-700 dark:text-blue-400 font-bold ring-1 ring-blue-600' : 'border-slate-200 dark:border-slate-700/80 bg-slate-50/50 dark:bg-slate-800/40 hover:border-slate-300 text-slate-600 dark:text-slate-400 font-medium' }}" for="create-type-{{ $val }}">
                                <input type="radio" name="type" value="{{ $val }}" id="create-type-{{ $val }}" {{ old('type', 'house') === $val ? 'checked' : '' }} class="sr-only" onchange="this.closest('.grid').querySelectorAll('label').forEach(l => l.classList.remove('border-blue-600','bg-blue-50/70','dark:bg-blue-950/40','text-blue-700','dark:text-blue-400','font-bold','ring-1','ring-blue-600')); this.closest('label').classList.add('border-blue-600','bg-blue-50/70','dark:bg-blue-950/40','text-blue-700','dark:text-blue-400','font-bold','ring-1','ring-blue-600'); if(window.onTypeChange) window.onTypeChange('{{ $val }}');">
                                <i class="{{ $info['icon'] }} text-lg sm:text-xl mb-1 text-blue-600"></i>
                                <span class="text-[11px] sm:text-xs text-center leading-tight">{{ $info['label'] }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Category & Phone Row --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-5">
                        <div>
                            <label for="create-category" class="block text-[11px] sm:text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Category <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select name="category_id" id="create-category" required
                                        class="w-full pl-3.5 pr-8 py-2.5 sm:pl-4 sm:pr-9 sm:py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/15 transition-all appearance-none cursor-pointer">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" data-type="{{ $category->getPropertyType() }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <i class="ph-bold ph-caret-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="create-phone" class="block text-[11px] sm:text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Contact Phone Number <span class="text-red-500">*</span></label>
                                <button type="button" id="btn-add-alternate-phone" class="inline-flex items-center gap-1 text-[11px] sm:text-xs font-bold text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition-colors cursor-pointer" title="Add another mobile number">
                                    <i class="ph-bold ph-plus-circle text-xs sm:text-sm"></i>
                                    <span>Add More Mobile No.</span>
                                </button>
                            </div>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"><i class="ph-bold ph-phone"></i></span>
                                <input type="tel" name="contact_phone" id="create-phone" value="{{ old('contact_phone', request('behalf_phone', auth()->user()->phone ?? '')) }}" required inputmode="tel" autocomplete="tel"
                                       class="w-full pl-9 pr-3.5 py-2.5 sm:pl-10 sm:pr-4 sm:py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white placeholder:text-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/15 transition-all"
                                       placeholder="+91 94254 55499">
                            </div>
                            @error('contact_phone') <p class="text-red-500 text-xs mt-1.5 font-semibold">{{ $message }}</p> @enderror

                            {{-- Dynamic Alternate Phone Numbers Container --}}
                            <div id="alternate-phones-container" class="space-y-2 mt-2.5">
                                @php
                                    $oldAltPhones = old('alternate_phones', []);
                                    if (empty($oldAltPhones) && old('alternate_phone')) {
                                        $oldAltPhones = explode(',', old('alternate_phone'));
                                    }
                                    $oldAltPhones = array_filter(array_map('trim', (array)$oldAltPhones));
                                @endphp
                                @foreach($oldAltPhones as $altPhone)
                                    <div class="alternate-phone-row flex items-center gap-2">
                                        <div class="relative flex-1">
                                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-indigo-500 text-sm"><i class="ph-bold ph-phone-call"></i></span>
                                            <input type="tel" name="alternate_phones[]" value="{{ $altPhone }}" inputmode="tel" autocomplete="tel"
                                                   class="w-full pl-9 pr-14 py-2 sm:pl-10 sm:py-2.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white placeholder:text-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/15 transition-all"
                                                   placeholder="Alternate Mobile (e.g. +91 98765 43210)">
                                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/60 px-1.5 py-0.5 rounded-md pointer-events-none">Alt</span>
                                        </div>
                                        <button type="button" class="btn-remove-alt-phone p-2 sm:p-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-400 hover:text-red-600 hover:border-red-200 dark:hover:border-red-800 hover:bg-red-50 dark:hover:bg-red-950/30 transition-all shrink-0 cursor-pointer" title="Remove this number">
                                            <i class="ph-bold ph-trash text-sm"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Bottom Add Button --}}
                            <button type="button" id="btn-add-alternate-phone-bottom" class="mt-2.5 w-full py-2 px-3 border border-dashed border-blue-300 dark:border-blue-700/60 rounded-xl text-xs font-bold text-blue-600 dark:text-blue-400 bg-blue-50/50 dark:bg-blue-950/30 hover:bg-blue-50 dark:hover:bg-blue-950/60 transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                                <i class="ph-bold ph-plus-circle text-sm"></i>
                                <span>Add Another Mobile Number</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

                {{-- 4. Key Specifications --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 lg:p-8 shadow-xs" id="create-details">
                <div class="flex items-center gap-2.5 sm:gap-3 mb-4 sm:mb-6 pb-3 sm:pb-4 border-b border-slate-100 dark:border-slate-800">
                    <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg sm:rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 flex items-center justify-center text-base sm:text-lg">
                        <i class="ph-bold ph-house-line" id="create-details-icon"></i>
                    </div>
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white" id="create-details-title">Key Specifications</h2>
                        <p class="text-[11px] sm:text-xs text-slate-400" id="create-details-desc">Bedrooms, bathrooms, area, and furnishing level</p>
                    </div>
                </div>

                {{-- Residential / Commercial Standard Specs Grid --}}
                <div class="grid grid-cols-3 gap-2 sm:gap-5" id="create-specs-grid">
                    <div id="create-bedrooms-col">
                        <label for="create-bedrooms" class="block text-[10px] sm:text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1 truncate">Bedrooms (BHK)</label>
                        <div class="relative">
                            <span class="absolute left-2.5 sm:left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs sm:text-sm"><i class="ph-bold ph-bed"></i></span>
                            <input type="number" name="bedrooms" id="create-bedrooms" value="{{ old('bedrooms') }}" min="0" max="20" inputmode="numeric"
                                   class="w-full pl-7 sm:pl-10 pr-2 sm:pr-4 py-2.5 sm:py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 dark:text-white placeholder:text-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/15 transition-all"
                                   placeholder="3">
                        </div>
                    </div>
                    <div id="create-bathrooms-col">
                        <label for="create-bathrooms" class="block text-[10px] sm:text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1 truncate">Bathrooms</label>
                        <div class="relative">
                            <span class="absolute left-2.5 sm:left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs sm:text-sm"><i class="ph-bold ph-drop"></i></span>
                            <input type="number" name="bathrooms" id="create-bathrooms" value="{{ old('bathrooms') }}" min="0" max="20" inputmode="numeric"
                                   class="w-full pl-7 sm:pl-10 pr-2 sm:pr-4 py-2.5 sm:py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 dark:text-white placeholder:text-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/15 transition-all"
                                   placeholder="2">
                        </div>
                    </div>
                    <div id="create-area-col">
                        <label for="create-area" class="block text-[10px] sm:text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1 truncate" id="create-area-label">Area (sq.ft)</label>
                        <div class="relative">
                            <span class="absolute left-2.5 sm:left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs sm:text-sm"><i class="ph-bold ph-square-half"></i></span>
                            <input type="number" name="area_sqft" id="create-area" value="{{ old('area_sqft') }}" min="0" inputmode="numeric"
                                   class="w-full pl-7 sm:pl-10 pr-2 sm:pr-4 py-2.5 sm:py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 dark:text-white placeholder:text-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/15 transition-all"
                                   placeholder="1250">
                        </div>
                    </div>
                </div>

                {{-- Plot Land Informational Helper --}}
                <div id="create-plot-helper" class="hidden mt-3.5 sm:mt-5 p-3 sm:p-4 bg-amber-50/80 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/60 rounded-xl text-xs text-amber-800 dark:text-amber-300 flex items-start gap-2.5 shadow-2xs">
                    <i class="ph-bold ph-info text-amber-600 text-base shrink-0 mt-0.5"></i>
                    <div>
                        <strong class="font-bold">Plot / Land Mode:</strong>
                        <p class="mt-0.5 text-amber-700 dark:text-amber-400 font-normal">Living space specifications (BHK, Bathrooms, Furnishing) are hidden for vacant land. Enter the total plot area above and specify road frontage, zoning (Residential / Commercial / Industrial), and clear registry status in the description.</p>
                    </div>
                </div>

                {{-- PG & Hostel Dedicated Specifications Form --}}
                <div id="create-pg-container" class="hidden space-y-4 sm:space-y-5">
                    {{-- PG Banner --}}
                    <div class="p-3 sm:p-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-950/40 dark:to-indigo-950/40 border border-blue-200/90 dark:border-blue-800/60 rounded-xl text-xs text-blue-900 dark:text-blue-200 flex items-start gap-2.5 shadow-2xs">
                        <i class="ph-bold ph-buildings text-blue-600 text-lg shrink-0 mt-0.5"></i>
                        <div>
                            <strong class="font-bold text-blue-950 dark:text-blue-100">PG / Hostel Listing Mode:</strong>
                            <p class="mt-0.5 text-blue-700 dark:text-blue-300 font-normal">Fill in bed sharing options, tenant rules, meal facilities, and washroom details below. Verified students & working professionals filter specifically by these details.</p>
                        </div>
                    </div>

                    {{-- 1. Room Occupancy / Sharing Type Available --}}
                    <div>
                        <label class="block text-[11px] sm:text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                            Room Sharing / Occupancy Available <span class="text-red-500">*</span>
                            <span class="text-[10px] text-slate-400 font-normal lowercase">(select all that apply)</span>
                        </label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-2.5">
                            @php
                                $pgOccupancies = [
                                    'Single Room' => ['label' => 'Single Room', 'sub' => 'Private Room', 'icon' => 'ph-user'],
                                    'Double Sharing' => ['label' => 'Double Sharing', 'sub' => '2 Persons in Room', 'icon' => 'ph-users'],
                                    'Triple Sharing' => ['label' => 'Triple Sharing', 'sub' => '3 Persons in Room', 'icon' => 'ph-users-three'],
                                    '4+ Sharing / Dorm' => ['label' => '4+ Sharing', 'sub' => 'Dormitory / 4+ Beds', 'icon' => 'ph-users-four']
                                ];
                            @endphp
                            @foreach($pgOccupancies as $val => $occ)
                            <label class="relative flex flex-col p-2.5 sm:p-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/60 dark:bg-slate-800/40 hover:border-blue-400 cursor-pointer transition-all has-checked:border-blue-600 has-checked:bg-blue-50/70 dark:has-checked:bg-blue-950/40 has-checked:ring-1 has-checked:ring-blue-600">
                                <div class="flex items-center justify-between mb-1">
                                    <i class="ph-bold {{ $occ['icon'] }} text-blue-600 text-base"></i>
                                    <input type="checkbox" name="pg_occupancy[]" value="{{ $val }}" {{ (is_array(old('pg_occupancy')) && in_array($val, old('pg_occupancy'))) ? 'checked' : '' }} class="w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                                </div>
                                <span class="text-xs font-bold text-slate-900 dark:text-white leading-tight">{{ $occ['label'] }}</span>
                                <span class="text-[10px] text-slate-400 leading-tight mt-0.5">{{ $occ['sub'] }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- 2. Gender / Preferred Tenant & Washroom Type (2-col) --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-5">
                        <div>
                            <label for="create-pg-gender" class="block text-[11px] sm:text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                                Preferred Tenants / Gender <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select name="pg_gender" id="create-pg-gender"
                                        class="w-full pl-3.5 pr-8 py-2.5 sm:pl-4 sm:pr-9 sm:py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/15 transition-all appearance-none cursor-pointer">
                                    <option value="Boys Only" {{ old('pg_gender') === 'Boys Only' ? 'selected' : '' }}>Boys Only (Male)</option>
                                    <option value="Girls Only" {{ old('pg_gender') === 'Girls Only' ? 'selected' : '' }}>Girls Only (Female)</option>
                                    <option value="Co-ed / Anyone" {{ old('pg_gender') === 'Co-ed / Anyone' ? 'selected' : '' }}>Co-ed / Anyone (Working Men & Women)</option>
                                </select>
                                <i class="ph-bold ph-caret-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                            </div>
                        </div>

                        <div>
                            <label for="create-pg-bathroom" class="block text-[11px] sm:text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                                Bathroom / Washroom Type <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select name="pg_bathroom" id="create-pg-bathroom"
                                        class="w-full pl-3.5 pr-8 py-2.5 sm:pl-4 sm:pr-9 sm:py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/15 transition-all appearance-none cursor-pointer">
                                    <option value="attached" {{ old('pg_bathroom', 'attached') === 'attached' ? 'selected' : '' }}>Attached Washroom (Inside Room)</option>
                                    <option value="shared" {{ old('pg_bathroom') === 'shared' ? 'selected' : '' }}>Shared / Common Washroom</option>
                                </select>
                                <i class="ph-bold ph-caret-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                            </div>
                        </div>
                    </div>

                    {{-- 3. Food / Meal Facility & Gate Timing / Curfew (2-col) --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-5">
                        <div>
                            <label for="create-pg-food" class="block text-[11px] sm:text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                                Food & Meals Facility <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select name="pg_food" id="create-pg-food"
                                        class="w-full pl-3.5 pr-8 py-2.5 sm:pl-4 sm:pr-9 sm:py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/15 transition-all appearance-none cursor-pointer">
                                    <option value="Food Included (3 Meals - Breakfast, Lunch, Dinner)" {{ old('pg_food') === 'Food Included (3 Meals - Breakfast, Lunch, Dinner)' ? 'selected' : '' }}>Food Included (3 Meals: Breakfast, Lunch, Dinner)</option>
                                    <option value="Breakfast & Dinner Included" {{ old('pg_food') === 'Breakfast & Dinner Included' ? 'selected' : '' }}>Breakfast & Dinner Included</option>
                                    <option value="Self-Cooking Kitchen Available" {{ old('pg_food') === 'Self-Cooking Kitchen Available' ? 'selected' : '' }}>Self-Cooking Kitchen Available</option>
                                    <option value="Food Optional (Extra Charges)" {{ old('pg_food') === 'Food Optional (Extra Charges)' ? 'selected' : '' }}>Food Optional (Available on Extra Charges)</option>
                                    <option value="No Food Service" {{ old('pg_food') === 'No Food Service' ? 'selected' : '' }}>No Food Provided</option>
                                </select>
                                <i class="ph-bold ph-caret-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                            </div>
                        </div>

                        <div>
                            <label for="create-pg-curfew" class="block text-[11px] sm:text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                                Gate Closing Timing / Curfew <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select name="pg_curfew" id="create-pg-curfew"
                                        class="w-full pl-3.5 pr-8 py-2.5 sm:pl-4 sm:pr-9 sm:py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/15 transition-all appearance-none cursor-pointer">
                                    <option value="No Curfew (24x7 Entry / Biometric)" {{ old('pg_curfew') === 'No Curfew (24x7 Entry / Biometric)' ? 'selected' : '' }}>No Curfew (24x7 Entry / Biometric)</option>
                                    <option value="Gate Closes at 10:30 PM" {{ old('pg_curfew') === 'Gate Closes at 10:30 PM' ? 'selected' : '' }}>Gate Closes at 10:30 PM</option>
                                    <option value="Gate Closes at 11:00 PM" {{ old('pg_curfew') === 'Gate Closes at 11:00 PM' ? 'selected' : '' }}>Gate Closes at 11:00 PM</option>
                                    <option value="Gate Closes at 10:00 PM" {{ old('pg_curfew') === 'Gate Closes at 10:00 PM' ? 'selected' : '' }}>Gate Closes at 10:00 PM</option>
                                    <option value="Flexible (Night Shifts Allowed)" {{ old('pg_curfew') === 'Flexible (Night Shifts Allowed)' ? 'selected' : '' }}>Flexible (Night Shifts Allowed)</option>
                                </select>
                                <i class="ph-bold ph-caret-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                            </div>
                        </div>
                    </div>

                    {{-- 4. Security Deposit, Notice Period & Total Beds (3-col) --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
                        <div>
                            <label for="create-pg-deposit" class="block text-[11px] sm:text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                                Security Deposit
                            </label>
                            <div class="relative">
                                <select name="pg_deposit" id="create-pg-deposit"
                                        class="w-full pl-3.5 pr-8 py-2.5 sm:pl-4 sm:pr-8 sm:py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/15 transition-all appearance-none cursor-pointer">
                                    <option value="1 Month Rent" {{ old('pg_deposit', '1 Month Rent') === '1 Month Rent' ? 'selected' : '' }}>1 Month Rent</option>
                                    <option value="2 Months Rent" {{ old('pg_deposit') === '2 Months Rent' ? 'selected' : '' }}>2 Months Rent</option>
                                    <option value="Zero Deposit" {{ old('pg_deposit') === 'Zero Deposit' ? 'selected' : '' }}>Zero Deposit</option>
                                    <option value="₹5,000 Fixed" {{ old('pg_deposit') === '₹5,000 Fixed' ? 'selected' : '' }}>₹5,000 Fixed</option>
                                    <option value="₹10,000 Fixed" {{ old('pg_deposit') === '₹10,000 Fixed' ? 'selected' : '' }}>₹10,000 Fixed</option>
                                    <option value="Negotiable" {{ old('pg_deposit') === 'Negotiable' ? 'selected' : '' }}>Negotiable</option>
                                </select>
                                <i class="ph-bold ph-caret-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                            </div>
                        </div>

                        <div>
                            <label for="create-pg-notice" class="block text-[11px] sm:text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                                Notice Period
                            </label>
                            <div class="relative">
                                <select name="pg_notice" id="create-pg-notice"
                                        class="w-full pl-3.5 pr-8 py-2.5 sm:pl-4 sm:pr-8 sm:py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/15 transition-all appearance-none cursor-pointer">
                                    <option value="1 Month" {{ old('pg_notice', '1 Month') === '1 Month' ? 'selected' : '' }}>1 Month Notice</option>
                                    <option value="15 Days" {{ old('pg_notice') === '15 Days' ? 'selected' : '' }}>15 Days Notice</option>
                                    <option value="2 Months" {{ old('pg_notice') === '2 Months' ? 'selected' : '' }}>2 Months Notice</option>
                                    <option value="No Notice Period" {{ old('pg_notice') === 'No Notice Period' ? 'selected' : '' }}>No Notice Period</option>
                                </select>
                                <i class="ph-bold ph-caret-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                            </div>
                        </div>

                        <div>
                            <label for="create-pg-total-beds" class="block text-[11px] sm:text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                                Total Beds / Capacity
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"><i class="ph-bold ph-bed"></i></span>
                                <input type="number" name="pg_total_beds" id="create-pg-total-beds" value="{{ old('pg_total_beds', old('bedrooms')) }}" min="1" max="500" inputmode="numeric"
                                       class="w-full pl-9 pr-3.5 py-2.5 sm:pl-9 sm:pr-3.5 sm:py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 dark:text-white placeholder:text-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/15 transition-all"
                                       placeholder="e.g. 15 Beds">
                            </div>
                        </div>
                    </div>

                    {{-- 5. PG Amenities & Facilities --}}
                    <div>
                        <label class="block text-[11px] sm:text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                            Included Amenities & Facilities
                            <span class="text-[10px] text-slate-400 font-normal lowercase">(select all that apply)</span>
                        </label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2 sm:gap-2.5">
                            @php
                                $pgAmenitiesList = [
                                    'High-Speed Wi-Fi' => ['icon' => 'ph-wifi-high', 'default' => true],
                                    'Air Conditioner (AC)' => ['icon' => 'ph-snowflake', 'default' => false],
                                    'Geyser / Hot Water' => ['icon' => 'ph-drop', 'default' => true],
                                    'RO Drinking Water' => ['icon' => 'ph-drop-half-bottom', 'default' => true],
                                    'Washing Machine' => ['icon' => 'ph-washing-machine', 'default' => true],
                                    'Daily Housekeeping' => ['icon' => 'ph-broom', 'default' => true],
                                    '24x7 Power Backup' => ['icon' => 'ph-lightning', 'default' => true],
                                    'CCTV & Security' => ['icon' => 'ph-shield-check', 'default' => true],
                                    'Refrigerator' => ['icon' => 'ph-cube', 'default' => false],
                                    'Bed & Wardrobe' => ['icon' => 'ph-dresser', 'default' => true],
                                    'Study Table & Chair' => ['icon' => 'ph-desk', 'default' => false],
                                    'Two-Wheeler Parking' => ['icon' => 'ph-motorcycle', 'default' => true],
                                    'Gym / Fitness Area' => ['icon' => 'ph-barbell', 'default' => false],
                                    'Balcony / Terrace' => ['icon' => 'ph-sun-dim', 'default' => false],
                                ];
                            @endphp
                            @foreach($pgAmenitiesList as $amenityName => $aInfo)
                                @php
                                    $isOldChecked = is_array(old('pg_amenities')) ? in_array($amenityName, old('pg_amenities')) : (old('type') === 'pg-hostel' ? false : $aInfo['default']);
                                @endphp
                                <label class="relative flex items-center gap-2 p-2 sm:p-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/60 dark:bg-slate-800/40 hover:border-blue-400 cursor-pointer transition-all has-checked:border-blue-600 has-checked:bg-blue-50/70 dark:has-checked:bg-blue-950/40 has-checked:ring-1 has-checked:ring-blue-600">
                                    <input type="checkbox" name="pg_amenities[]" value="{{ $amenityName }}" {{ $isOldChecked ? 'checked' : '' }} class="w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500 shrink-0">
                                    <i class="ph-bold {{ $aInfo['icon'] }} text-blue-600 text-sm shrink-0"></i>
                                    <span class="text-xs font-semibold text-slate-800 dark:text-slate-200 leading-tight truncate">{{ $amenityName }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="mt-3.5 sm:mt-5" id="create-furnishing-container">
                    <label for="create-furnishing" class="block text-[11px] sm:text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Furnishing Status <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="furnishing" id="create-furnishing"
                                class="w-full pl-3.5 pr-8 py-2.5 sm:pl-4 sm:pr-9 sm:py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/15 transition-all appearance-none cursor-pointer">
                            <option value="unfurnished" {{ old('furnishing') === 'unfurnished' ? 'selected' : '' }}>Unfurnished</option>
                            <option value="semi-furnished" {{ old('furnishing') === 'semi-furnished' ? 'selected' : '' }}>Semi-Furnished</option>
                            <option value="fully-furnished" {{ old('furnishing', 'fully-furnished') === 'fully-furnished' ? 'selected' : '' }}>Fully Furnished</option>
                        </select>
                        <i class="ph-bold ph-caret-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                    </div>
                </div>
            </div>

                {{-- Step 1 Footer Navigation --}}
                <div class="flex items-center justify-between pt-4 sm:pt-6 border-t border-slate-200/70 dark:border-slate-800">
                    <a href="{{ route('dashboard') }}" class="px-4 sm:px-5 py-2.5 sm:py-3 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs sm:text-sm rounded-xl transition-all">
                        Cancel
                    </a>
                    <button type="button" onclick="goToStep(2)" class="px-5 sm:px-6 py-2.5 sm:py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs sm:text-sm rounded-xl shadow-md shadow-blue-500/25 transition-all flex items-center gap-2 cursor-pointer active:scale-95">
                        <span>Continue to Pricing</span>
                        <i class="ph-bold ph-arrow-right"></i>
                    </button>
                </div>
            </div>
            {{-- ======================================================== --}}
            {{-- STEP 2: Pricing & Billing                                 --}}
            {{-- ======================================================== --}}
            <div id="step-pane-2" class="step-pane space-y-4 sm:space-y-6 hidden">
                {{-- 2. Pricing Section --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 lg:p-8 shadow-xs" id="create-pricing">
                <div class="flex items-center gap-2.5 sm:gap-3 mb-4 sm:mb-6 pb-3 sm:pb-4 border-b border-slate-100 dark:border-slate-800">
                    <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg sm:rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 flex items-center justify-center text-base sm:text-lg">
                        <i class="ph-bold ph-currency-inr"></i>
                    </div>
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white" id="create-pricing-title">Pricing & Billing</h2>
                        <p class="text-[11px] sm:text-xs text-slate-400" id="create-pricing-desc">Set your expected rent or sale price</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-5" id="pricing-grid">
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="create-price-input" class="block text-[11px] sm:text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider" id="create-price-label">
                                Expected Rent (₹) <span class="text-red-500">*</span>
                            </label>
                            <span id="create-price-unit-hint" class="text-[10px] sm:text-[11px] font-bold text-emerald-600 dark:text-emerald-400 hidden">
                                <i class="ph-bold ph-tag"></i> Select Unit (Lakh / Crore)
                            </span>
                        </div>

                        {{-- Hidden raw price in INR submitted to backend --}}
                        <input type="hidden" name="price" id="create-price" value="{{ old('price') }}">

                        {{-- Combined Input Group with Dropdown --}}
                        <div class="flex items-stretch rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 focus-within:bg-white dark:focus-within:bg-slate-800 focus-within:border-blue-600 focus-within:ring-2 focus-within:ring-blue-600/15 transition-all overflow-hidden shadow-2xs">
                            <div class="flex items-center justify-center pl-3.5 sm:pl-4 text-slate-400 font-extrabold text-sm sm:text-base select-none">
                                ₹
                            </div>
                            <input type="number" id="create-price-input" value="{{ old('price') }}" required min="0" step="any" inputmode="decimal"
                                   class="w-full pl-2 pr-2 py-2.5 sm:py-3 bg-transparent text-sm sm:text-base font-bold text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none border-0"
                                   placeholder="25,000">

                            {{-- Unit Dropdown (Lakh, Crore, etc.) --}}
                            <div class="relative flex items-center border-l border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shrink-0">
                                <select name="price_unit" id="create-price-unit"
                                        class="h-full pl-2.5 pr-7 sm:pl-3.5 sm:pr-8 py-2 text-xs sm:text-sm font-bold text-slate-800 dark:text-slate-200 bg-transparent focus:outline-none cursor-pointer appearance-none">
                                    <option value="rupees" {{ old('price_unit') === 'rupees' ? 'selected' : '' }}>₹ Full</option>
                                    <option value="thousand" {{ old('price_unit') === 'thousand' ? 'selected' : '' }}>Thousand (K)</option>
                                    <option value="lakh" {{ old('price_unit') === 'lakh' ? 'selected' : '' }}>Lakh (Lac)</option>
                                    <option value="crore" {{ old('price_unit') === 'crore' ? 'selected' : '' }}>Crore (Cr)</option>
                                </select>
                                <i class="ph-bold ph-caret-down absolute right-2 sm:right-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                            </div>
                        </div>

                        {{-- Real-time Human Friendly Price Preview in Indian Currency --}}
                        <div id="create-price-in-words" class="mt-1.5 flex items-center gap-1.5 text-[11px] sm:text-xs font-semibold text-slate-600 dark:text-slate-300 min-h-[18px]">
                            <span class="text-slate-400 font-normal">Amount:</span>
                            <span id="create-price-preview-text" class="text-emerald-600 dark:text-emerald-400 font-bold">₹0</span>
                        </div>

                        @error('price') <p class="text-red-500 text-xs mt-1.5 font-semibold">{{ $message }}</p> @enderror
                    </div>

                    {{-- Rent Billing Cycle (Per Month / Per Year) --}}
                    <div id="period-input-col">
                        <label for="create-period" class="block text-[11px] sm:text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5" id="create-period-label">Billing Cycle <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select name="price_period" id="create-period" required
                                    class="w-full pl-3.5 pr-8 py-2.5 sm:pl-4 sm:pr-9 sm:py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/15 transition-all appearance-none cursor-pointer">
                                <option value="month" {{ old('price_period') === 'month' ? 'selected' : '' }}>Per Month</option>
                                <option value="year" {{ old('price_period') === 'year' ? 'selected' : '' }}>Per Year</option>
                            </select>
                            <i class="ph-bold ph-caret-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                        </div>
                    </div>

                    {{-- Sale Pricing Mode Badge (Shown when Selling) --}}
                    <div id="sale-badge-col" class="hidden">
                        <label class="block text-[11px] sm:text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Payment Mode</label>
                        <div class="h-[42px] sm:h-[46px] px-3.5 sm:px-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/50 rounded-xl text-xs font-bold text-emerald-800 dark:text-emerald-300 flex items-center gap-2 shadow-2xs">
                            <i class="ph-bold ph-tag text-emerald-600 text-base shrink-0"></i>
                            <span>One-Time Total Purchase Price (No recurring rent)</span>
                        </div>
                    </div>
                </div>
            </div>

                {{-- Step 2 Footer Navigation --}}
                <div class="flex items-center justify-between pt-4 sm:pt-6 border-t border-slate-200/70 dark:border-slate-800">
                    <button type="button" onclick="goToStep(1)" class="px-4 sm:px-5 py-2.5 sm:py-3 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs sm:text-sm rounded-xl transition-all flex items-center gap-2 cursor-pointer">
                        <i class="ph-bold ph-arrow-left"></i>
                        <span>Back to Basic Info</span>
                    </button>
                    <button type="button" onclick="goToStep(3)" class="px-5 sm:px-6 py-2.5 sm:py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs sm:text-sm rounded-xl shadow-md shadow-blue-500/25 transition-all flex items-center gap-2 cursor-pointer active:scale-95">
                        <span>Continue to Location</span>
                        <i class="ph-bold ph-arrow-right"></i>
                    </button>
                </div>
            </div>
            {{-- ======================================================== --}}
            {{-- STEP 3: Location Details                                  --}}
            {{-- ======================================================== --}}
            <div id="step-pane-3" class="step-pane space-y-4 sm:space-y-6 hidden">
                {{-- 3. Location Details --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 lg:p-8 shadow-xs" id="create-location">
                <div class="flex items-center gap-2.5 sm:gap-3 mb-4 sm:mb-6 pb-3 sm:pb-4 border-b border-slate-100 dark:border-slate-800">
                    <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg sm:rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 flex items-center justify-center text-base sm:text-lg">
                        <i class="ph-bold ph-map-pin"></i>
                    </div>
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">Location Details</h2>
                        <p class="text-[11px] sm:text-xs text-slate-400">Accurate location helps verified tenants discover your listing</p>
                    </div>
                </div>

                <div class="space-y-3.5 sm:space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-5">
                        <div>
                            <label for="create-state" class="block text-[11px] sm:text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">State <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select name="state" id="create-state" required onchange="if(window.handleLocationStateChange) window.handleLocationStateChange(this, 'create-city', 'create-locality-select');"
                                        class="w-full pl-3.5 pr-8 py-2.5 sm:pl-4 sm:pr-9 sm:py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/15 transition-all appearance-none cursor-pointer">
                                    <option value="">Select State</option>
                                    @php $statesList = $globalAllStates ?? $allStates ?? []; @endphp
                                    @if(!empty($statesList))
                                        @foreach($statesList as $code => $name)
                                             <option value="{{ $code }}" {{ old('state') == $code ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <i class="ph-bold ph-caret-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                            </div>
                        </div>

                        <div>
                            <label for="create-city" class="block text-[11px] sm:text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">City / District <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select name="location" id="create-city" required onchange="if(window.handleLocationCityChange) window.handleLocationCityChange(this, 'create-locality-select', 'create-state');"
                                        class="w-full pl-3.5 pr-8 py-2.5 sm:pl-4 sm:pr-9 sm:py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/15 transition-all appearance-none cursor-pointer">
                                    <option value="">Select District</option>
                                    @php $districtsList = $globalAllDistricts ?? $allDistricts ?? []; @endphp
                                    @if(!empty($districtsList))
                                        @foreach($districtsList as $d)
                                            @php
                                                $dSlug = $d['slug'] ?? strtolower(str_replace(' ', '-', $d['name']));
                                                $isSelected = (old('location') === $dSlug || old('location') === $d['name']);
                                            @endphp
                                            <option value="{{ $d['name'] }}" {{ $isSelected ? 'selected' : '' }}>
                                                {{ $d['name'] }}{{ !empty($d['state_code']) ? ' (' . $d['state_code'] . ')' : '' }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <i class="ph-bold ph-caret-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label for="create-locality-select" class="block text-[11px] sm:text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Locality / Sector / Area <span class="text-red-500">*</span></label>
                        <div id="locality-select-wrap">
                            <div class="relative">
                                <select name="locality" id="create-locality-select"
                                        class="w-full pl-3.5 pr-8 py-2.5 sm:pl-4 sm:pr-9 sm:py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/15 transition-all appearance-none cursor-pointer">
                                    <option value="">Select City First</option>
                                </select>
                                <i class="ph-bold ph-caret-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                            </div>
                        </div>
                        <div id="locality-text-wrap" style="display: none;">
                            <div class="relative">
                                <i class="ph-bold ph-map-pin absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                                <input type="text" name="locality" id="create-locality-text" value="{{ old('locality') }}" disabled 
                                       class="w-full pl-9 pr-3.5 py-2.5 sm:pl-10 sm:pr-4 sm:py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white placeholder:text-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/15 transition-all"
                                       placeholder="e.g. Sector 57, Sushant Lok">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="create-address" class="block text-[11px] sm:text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Complete Address <span class="text-red-500">*</span></label>
                        <input type="text" name="address" id="create-address" value="{{ old('address') }}" required enterkeyhint="next"
                               class="w-full px-3.5 py-2.5 sm:px-4 sm:py-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white placeholder:text-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/15 transition-all"
                               placeholder="Flat/House No, Building Name, Street / Landmark">
                    </div>
                </div>

                <script>
                    (function() {
                        function checkAndInjectSector13() {
                            var cityEl = document.getElementById('create-city');
                            var locEl = document.getElementById('create-locality-select');
                            if (!cityEl || !locEl) return;
                            var cVal = (cityEl.value || '').trim().toLowerCase();
                            if (!cVal.includes('gurugram') && !cVal.includes('gurgaon')) return;
                            if (locEl.options.length <= 1) return;

                            var hasS13 = Array.from(locEl.options).some(function(o) {
                                return o.value.trim().toLowerCase() === 'sector 13';
                            });
                            if (!hasS13) {
                                var curVal = locEl.value || "{{ old('locality') }}";
                                var newOpt = new Option('\u00A0\u00A0Sector 13', 'Sector 13');
                                var inserted = false;
                                for (var i = 1; i < locEl.options.length; i++) {
                                    var txt = locEl.options[i].text.trim();
                                    if (txt.localeCompare('Sector 13', undefined, { numeric: true, sensitivity: 'base' }) > 0) {
                                        locEl.insertBefore(newOpt, locEl.options[i]);
                                        inserted = true;
                                        break;
                                    }
                                }
                                if (!inserted) locEl.add(newOpt);
                                if (curVal) locEl.value = curVal;
                            }
                        }

                        document.addEventListener('DOMContentLoaded', function() {
                            if (typeof window.initLocationCascading === 'function') {
                                window.initLocationCascading({
                                    stateId: 'create-state',
                                    cityId: 'create-city',
                                    localityId: 'create-locality-select',
                                    localityTextWrapId: 'locality-text-wrap',
                                    localitySelectWrapId: 'locality-select-wrap',
                                    selectedState: "{{ old('state') }}",
                                    selectedCity: "{{ old('location') }}",
                                    selectedLocality: "{{ old('locality') }}"
                                });
                            }

                            checkAndInjectSector13();
                            setTimeout(checkAndInjectSector13, 80);
                            setTimeout(checkAndInjectSector13, 250);
                            setTimeout(checkAndInjectSector13, 600);
                            setTimeout(checkAndInjectSector13, 1200);

                            var locEl = document.getElementById('create-locality-select');
                            var cityEl = document.getElementById('create-city');
                            if (locEl) {
                                locEl.addEventListener('focus', checkAndInjectSector13);
                                locEl.addEventListener('mousedown', checkAndInjectSector13);
                                locEl.addEventListener('click', checkAndInjectSector13);

                                if (window.MutationObserver) {
                                    var observer = new MutationObserver(function() {
                                        observer.disconnect();
                                        checkAndInjectSector13();
                                        observer.observe(locEl, { childList: true });
                                    });
                                    observer.observe(locEl, { childList: true });
                                }
                            }
                            if (cityEl) {
                                cityEl.addEventListener('change', function() {
                                    setTimeout(checkAndInjectSector13, 50);
                                    setTimeout(checkAndInjectSector13, 200);
                                    setTimeout(checkAndInjectSector13, 500);
                                });
                            }
                        });
                    })();
                </script>
            </div>

                {{-- Step 3 Footer Navigation --}}
                <div class="flex items-center justify-between pt-4 sm:pt-6 border-t border-slate-200/70 dark:border-slate-800">
                    <button type="button" onclick="goToStep(2)" class="px-4 sm:px-5 py-2.5 sm:py-3 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs sm:text-sm rounded-xl transition-all flex items-center gap-2 cursor-pointer">
                        <i class="ph-bold ph-arrow-left"></i>
                        <span>Back to Pricing</span>
                    </button>
                    <button type="button" onclick="goToStep(4)" class="px-5 sm:px-6 py-2.5 sm:py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs sm:text-sm rounded-xl shadow-md shadow-blue-500/25 transition-all flex items-center gap-2 cursor-pointer active:scale-95">
                        <span>Continue to Photos</span>
                        <i class="ph-bold ph-arrow-right"></i>
                    </button>
                </div>
            </div>
            {{-- ======================================================== --}}
            {{-- STEP 4: Property Photos & Video Tours                     --}}
            {{-- ======================================================== --}}
            <div id="step-pane-4" class="step-pane space-y-4 sm:space-y-6 hidden">
                {{-- 5. Media & Photo Gallery --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl sm:rounded-2xl p-4 sm:p-6 lg:p-8 shadow-xs" id="create-images">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 sm:gap-3 mb-4 sm:mb-6 pb-3 sm:pb-4 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-2.5 sm:gap-3">
                        <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg sm:rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 flex items-center justify-center text-base sm:text-lg">
                            <i class="ph-bold ph-images-square"></i>
                        </div>
                        <div>
                            <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">Property Photos</h2>
                            <p class="text-[11px] sm:text-xs text-slate-400">High-quality photos increase inquiries by up to 5x</p>
                        </div>
                    </div>
                    <span class="text-[11px] sm:text-xs font-bold text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-950/60 px-2.5 py-1 rounded-lg self-start sm:self-auto border border-blue-200/60 dark:border-blue-800">Min 1 Photo or Video Tour *</span>
                </div>

                {{-- Upload Dropzone --}}
                <div class="border-2 border-dashed border-slate-200 dark:border-slate-700 hover:border-blue-500 rounded-xl sm:rounded-2xl p-5 sm:p-8 text-center transition-all group relative bg-slate-50/50 dark:bg-slate-800/30">
                    <div class="w-11 h-11 sm:w-14 sm:h-14 rounded-xl sm:rounded-2xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 flex items-center justify-center mx-auto mb-2.5 group-hover:scale-110 transition-transform">
                        <i class="ph-bold ph-cloud-arrow-up text-xl sm:text-2xl"></i>
                    </div>
                    <p class="text-xs sm:text-sm font-bold text-slate-800 dark:text-white mb-0.5">Click to upload or drag & drop photos here</p>
                    <p class="text-[11px] sm:text-xs text-slate-400">Supports JPG, PNG, WebP · <span class="text-emerald-600 font-semibold">⚡ Auto-compressed for ultra-fast upload</span></p>
                    
                    <input type="file" name="images[]" multiple accept="image/*"
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                           id="create-images-input" onchange="handleImageSelection(event)">
                </div>

                {{-- Image Compression Status Indicator --}}
                <div id="compression-status" class="mt-3 p-3 bg-blue-50 dark:bg-blue-950/50 border border-blue-200 dark:border-blue-800 rounded-xl text-xs font-semibold text-blue-700 dark:text-blue-300 flex items-center justify-between hidden">
                    <div class="flex items-center gap-2">
                        <i class="ph-bold ph-lightning text-amber-500 text-base"></i>
                        <span id="compression-status-text">Optimizing photos for instant upload...</span>
                    </div>
                    <span id="compression-saved-text" class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-950/60 px-2 py-0.5 rounded-md"></span>
                </div>
                
                {{-- Hidden input for primary image --}}
                <input type="hidden" name="primary_image" id="primary-image-input" value="0">

                {{-- Cover Photo Helper Guidance --}}
                <div id="cover-photo-guidance" class="cover-photo-guidance-card hidden mt-3.5 p-3 sm:p-3.5 rounded-xl sm:rounded-2xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2.5 shadow-2xs">
                    <div class="flex items-center gap-2.5 sm:gap-3 min-w-0">
                        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-gradient-to-tr from-amber-500 via-amber-400 to-yellow-300 text-slate-950 flex items-center justify-center shrink-0 shadow-md shadow-amber-500/20 ring-2 ring-white/80 dark:ring-slate-800">
                            <i class="ph-fill ph-star text-base sm:text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h4 class="text-xs sm:text-sm font-extrabold text-slate-900 dark:text-white tracking-tight">Select Cover Photo</h4>
                                <span class="px-2 py-0.5 rounded-full bg-blue-100/80 dark:bg-blue-900/60 text-blue-700 dark:text-blue-300 text-[10px] font-extrabold uppercase tracking-wider border border-blue-200/80 dark:border-blue-800/80">Main Listing Display</span>
                            </div>
                            <p class="text-[11px] sm:text-xs text-slate-600 dark:text-slate-300 mt-0.5">
                                <strong>Tap or click any photo</strong> to set it as the primary cover photo for search results and map cards.
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 shrink-0 self-end sm:self-center">
                        <span class="inline-flex items-center gap-1.5 text-[10px] sm:text-[11px] font-bold text-emerald-700 dark:text-emerald-300 bg-white/90 dark:bg-slate-900/90 px-2.5 py-1 rounded-full border border-emerald-200/80 dark:border-emerald-800/80 shadow-2xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span>1 Cover Photo Allowed</span>
                        </span>
                    </div>
                </div>

                {{-- Preview Gallery Container --}}
                <div id="image-preview-container" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2.5 sm:gap-3 mt-3 sm:mt-4 hidden">
                    {{-- Previews dynamically rendered here --}}
                </div>
                
                @error('images') <p class="text-red-500 text-xs mt-3 font-semibold">{{ $message }}</p> @enderror
                @error('images.*') <p class="text-red-500 text-xs mt-3 font-semibold">{{ $message }}</p> @enderror

                {{-- Video Tour Section --}}
                <div class="mt-6 sm:mt-8 pt-4 sm:pt-6 border-t border-slate-100 dark:border-slate-800">
                    <div class="flex items-center justify-between gap-2.5 sm:gap-3 mb-3.5 sm:mb-4">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg sm:rounded-xl bg-purple-50 dark:bg-purple-950/60 text-purple-600 flex items-center justify-center">
                                <i class="ph-bold ph-video-camera text-base"></i>
                            </div>
                            <div>
                                <h3 class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white flex items-center gap-1.5 sm:gap-2">
                                    Video Tours & Clips
                                    <span class="px-1.5 sm:px-2 py-0.5 bg-purple-100 dark:bg-purple-900/60 text-purple-700 dark:text-purple-300 text-[9px] sm:text-[10px] font-extrabold uppercase rounded-full">Multi-Clip & Trimmer</span>
                                </h3>
                                <p class="text-[11px] sm:text-xs text-slate-400">Upload multiple clips, trim large videos, or add YouTube/Vimeo links</p>
                            </div>
                        </div>
                        <span class="text-[10px] sm:text-[11px] font-bold text-purple-700 dark:text-purple-300 bg-purple-50 dark:bg-purple-950/60 px-2 py-0.5 rounded-md border border-purple-200/60 dark:border-purple-800 shrink-0">3x Inquiries</span>
                    </div>

                    {{-- Multi-Video Upload Box --}}
                    <div id="video-upload-box" class="border-2 border-dashed border-slate-200 dark:border-slate-700 hover:border-purple-500 rounded-xl sm:rounded-2xl p-4 sm:p-6 text-center transition-all group relative bg-slate-50/40 dark:bg-slate-800/20 cursor-pointer" onclick="document.getElementById('create-video-input').click()">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-purple-50 dark:bg-purple-950/50 text-purple-600 flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                            <i class="ph-bold ph-film-slate text-lg sm:text-xl"></i>
                        </div>
                        <p class="text-xs sm:text-sm font-bold text-slate-800 dark:text-white mb-0.5">Click to upload property videos / clips (Up to 10)</p>
                        <p class="text-[11px] sm:text-xs text-slate-400">MP4, WebM, MOV · Auto-optimized · Max 10 clips</p>
                        
                        <input type="file" id="create-video-input" multiple accept="video/mp4,video/webm,video/ogg,video/quicktime,video/x-m4v" class="hidden" onchange="handleMultipleVideoSelection(event)">
                    </div>

                    {{-- External Video Tour Link Section --}}
                    <div class="mt-3.5 sm:mt-4 p-3 sm:p-4 rounded-xl bg-slate-50/60 dark:bg-slate-800/40 border border-slate-200/80 dark:border-slate-700/80">
                        <div class="flex items-center justify-between gap-2 mb-1.5">
                            <label class="text-[11px] sm:text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider flex items-center gap-1.5">
                                <i class="ph-bold ph-link text-purple-600"></i> Or Add Video Tour Link (YouTube / Vimeo / Cloud)
                            </label>
                        </div>
                        <div class="flex gap-2">
                            <input type="url" id="video-url-input" placeholder="https://www.youtube.com/watch?v=... or cloud link"
                                   class="flex-1 px-3 py-2 sm:px-3.5 sm:py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-purple-600">
                            <button type="button" onclick="addVideoUrlLink()" class="px-3.5 sm:px-4 py-2 sm:py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-xl shadow-sm transition-all flex items-center gap-1.5 cursor-pointer shrink-0">
                                <i class="ph-bold ph-plus"></i> Add Link
                            </button>
                        </div>
                    </div>

                    {{-- Compression / Trimming Global Status --}}
                    <div id="video-compress-status" class="mt-3.5 sm:mt-4 p-3 sm:p-3.5 bg-purple-50 dark:bg-purple-950/40 border border-purple-200 dark:border-purple-800/60 rounded-xl hidden">
                        <div class="flex items-center justify-between text-xs font-bold text-purple-900 dark:text-purple-300 mb-1.5">
                            <span class="flex items-center gap-1.5"><i class="ph-bold ph-lightning text-amber-500 animate-pulse text-sm"></i> <span id="compress-status-text">Optimizing video for fast posting...</span></span>
                            <span id="compress-status-pct">0%</span>
                        </div>
                        <div class="w-full bg-purple-200/60 dark:bg-purple-900/60 h-2 rounded-full overflow-hidden">
                            <div id="compress-progress-bar" class="bg-gradient-to-r from-purple-600 to-blue-600 h-full w-0 transition-all duration-150"></div>
                        </div>
                    </div>

                    {{-- Multi-Video Preview List Container --}}
                    <div id="video-previews-container" class="mt-3 sm:mt-4 space-y-2.5 sm:space-y-3"></div>

                    @error('video') <p class="text-red-500 text-xs mt-2 font-semibold">{{ $message }}</p> @enderror
                    @error('videos') <p class="text-red-500 text-xs mt-2 font-semibold">{{ $message }}</p> @enderror
                    @error('videos.*') <p class="text-red-500 text-xs mt-2 font-semibold">{{ $message }}</p> @enderror
                </div>

                {{-- Interactive Video Trimmer Modal --}}
                <div id="video-trimmer-modal" class="fixed inset-0 z-[99999] flex items-center justify-center p-3 sm:p-4 bg-black/85 backdrop-blur-md hidden" style="z-index: 99999;">
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl max-w-2xl w-full max-h-[92vh] overflow-y-auto shadow-2xl p-4 sm:p-6 transition-all relative z-10" onclick="event.stopPropagation()">
                        
                        {{-- Modal Header --}}
                        <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100 dark:border-slate-800">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center text-base sm:text-lg">
                                    <i class="ph-bold ph-scissors"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                        Trim Video Clip
                                        <span class="px-2 py-0.5 bg-purple-100 dark:bg-purple-900/60 text-purple-700 dark:text-purple-300 text-[10px] font-extrabold uppercase rounded-full">Fast Web Clip</span>
                                    </h3>
                                    <p class="text-[11px] sm:text-xs text-slate-400">Cut a short highlight clip (15s–60s) for instant uploading & fast playback</p>
                                </div>
                            </div>
                            <button type="button" onclick="closeTrimmerModal()" class="w-8 h-8 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400 hover:text-slate-700 dark:hover:text-white flex items-center justify-center transition-all cursor-pointer">
                                <i class="ph-bold ph-x text-base"></i>
                            </button>
                        </div>

                        {{-- Video Player Container --}}
                        <div class="relative bg-black rounded-xl overflow-hidden aspect-video max-h-[220px] sm:max-h-[320px] mb-3.5 sm:mb-4 flex items-center justify-center shadow-inner">
                            <video id="trimmer-preview-player" playsinline preload="metadata" class="w-full h-full object-contain"></video>
                            
                            {{-- Time Overlay --}}
                            <div class="absolute bottom-2 left-2 px-2.5 py-1 bg-black/75 backdrop-blur-xs text-white text-[11px] font-mono font-bold rounded-lg pointer-events-none">
                                <span id="trimmer-current-time">00:00</span> / <span id="trimmer-total-duration">00:00</span>
                            </div>
                        </div>

                        {{-- Clip Label Input --}}
                        <div class="mb-3.5 sm:mb-4">
                            <label class="block text-[11px] sm:text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">
                                Clip Name / Room (Optional)
                            </label>
                            <input type="text" id="trimmer-clip-title" placeholder="e.g. Living Room Walkthrough, Master Bedroom, Balcony View"
                                   class="w-full px-3 py-2 sm:px-3.5 sm:py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white focus:outline-none focus:border-purple-600">
                        </div>

                        {{-- Trim Range Controls --}}
                        <div class="p-3.5 sm:p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-200/80 dark:border-slate-700/80 mb-3.5 sm:mb-4 space-y-3">
                            
                            {{-- Sliders & Time Inputs --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                {{-- Start Time --}}
                                <div class="space-y-1">
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="font-bold text-slate-700 dark:text-slate-300">Start Time:</span>
                                        <div class="flex items-center gap-1.5">
                                            <span id="trimmer-start-display" class="font-mono font-bold text-purple-600 dark:text-purple-400">0.0s</span>
                                            <button type="button" onclick="setTrimStartToCurrent()" class="px-2 py-0.5 bg-purple-100 dark:bg-purple-950/60 hover:bg-purple-200 text-purple-700 dark:text-purple-300 text-[10px] font-bold rounded cursor-pointer">
                                                Set Current
                                            </button>
                                        </div>
                                    </div>
                                    <input type="range" id="trimmer-start-slider" min="0" max="100" step="0.1" value="0"
                                           class="w-full accent-purple-600 cursor-pointer" oninput="onTrimStartSliderChange(this.value)">
                                </div>

                                {{-- End Time --}}
                                <div class="space-y-1">
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="font-bold text-slate-700 dark:text-slate-300">End Time:</span>
                                        <div class="flex items-center gap-1.5">
                                            <span id="trimmer-end-display" class="font-mono font-bold text-purple-600 dark:text-purple-400">30.0s</span>
                                            <button type="button" onclick="setTrimEndToCurrent()" class="px-2 py-0.5 bg-purple-100 dark:bg-purple-950/60 hover:bg-purple-200 text-purple-700 dark:text-purple-300 text-[10px] font-bold rounded cursor-pointer">
                                                Set Current
                                            </button>
                                        </div>
                                    </div>
                                    <input type="range" id="trimmer-end-slider" min="0" max="100" step="0.1" value="30"
                                           class="w-full accent-purple-600 cursor-pointer" oninput="onTrimEndSliderChange(this.value)">
                                </div>
                            </div>

                            {{-- Quick Presets --}}
                            <div class="flex flex-wrap items-center gap-1.5 pt-2 border-t border-slate-200/60 dark:border-slate-700/60">
                                <span class="text-[10px] sm:text-[11px] font-bold text-slate-400 mr-1">Fast Presets:</span>
                                <button type="button" onclick="applyTrimPreset(15)" id="trim-preset-15" class="trim-preset-btn px-2.5 py-1 bg-white dark:bg-slate-700 hover:bg-purple-50 text-slate-700 dark:text-slate-200 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-600 cursor-pointer transition-all">15s Clip</button>
                                <button type="button" onclick="applyTrimPreset(30)" id="trim-preset-30" class="trim-preset-btn px-2.5 py-1 bg-white dark:bg-slate-700 hover:bg-purple-50 text-slate-700 dark:text-slate-200 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-600 cursor-pointer transition-all">30s Clip</button>
                                <button type="button" onclick="applyTrimPreset(45)" id="trim-preset-45" class="trim-preset-btn px-2.5 py-1 bg-white dark:bg-slate-700 hover:bg-purple-50 text-slate-700 dark:text-slate-200 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-600 cursor-pointer transition-all">45s Clip</button>
                                <button type="button" onclick="applyTrimPreset(60)" id="trim-preset-60" class="trim-preset-btn px-2.5 py-1 bg-white dark:bg-slate-700 hover:bg-purple-50 text-slate-700 dark:text-slate-200 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-600 cursor-pointer transition-all">60s Clip</button>
                                <button type="button" onclick="previewTrimRange()" class="ml-auto px-3 py-1 bg-slate-900 hover:bg-black text-white text-xs font-bold rounded-lg flex items-center gap-1 shadow-xs cursor-pointer">
                                    <i class="ph-bold ph-play"></i> Preview Clip
                                </button>
                            </div>

                            {{-- Summary Badge --}}
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1.5 text-xs font-semibold text-slate-600 dark:text-slate-300 pt-1">
                                <span>Clip Duration: <strong id="trimmer-duration-badge" class="text-purple-600 dark:text-purple-400">30.0s</strong> <span class="text-[10px] text-slate-400 font-normal">(Max 60s per clip)</span></span>
                                <div id="trimmer-speed-badge">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 text-[11px] font-bold rounded-full border border-emerald-200/50">⚡ ~4.8 MB · Uploads in ~2 sec</span>
                                </div>
                            </div>
                        </div>

                        {{-- Trimming Progress Bar (during processing) --}}
                        <div id="trimmer-progress-wrapper" class="mb-3.5 sm:mb-4 p-3 bg-purple-50 dark:bg-purple-950/40 rounded-xl border border-purple-200 dark:border-purple-800/60 hidden">
                            <div class="flex items-center justify-between text-xs font-bold text-purple-900 dark:text-purple-300 mb-1.5">
                                <span class="flex items-center gap-1.5"><i class="ph-bold ph-lightning text-amber-500 animate-pulse"></i> Extracting & optimizing clip...</span>
                                <span id="trimmer-progress-pct">0%</span>
                            </div>
                            <div class="w-full bg-purple-200 dark:bg-purple-900 h-2 rounded-full overflow-hidden">
                                <div id="trimmer-progress-bar" class="bg-gradient-to-r from-purple-600 to-indigo-600 h-full w-0 transition-all duration-100"></div>
                            </div>
                        </div>

                        {{-- Mode Selector: Add as new clip vs replace --}}
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2.5 sm:gap-4 mb-4 sm:mb-5 text-xs">
                            <label class="inline-flex items-center gap-2 cursor-pointer text-slate-700 dark:text-slate-300 font-semibold">
                                <input type="radio" name="trimmer_mode" value="add" id="trimmer-mode-add" checked class="text-purple-600 focus:ring-purple-500">
                                <span>Add as New Clip <span class="text-[10px] text-slate-400">(Cut multiple clips for different rooms)</span></span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer text-slate-700 dark:text-slate-300 font-semibold">
                                <input type="radio" name="trimmer_mode" value="replace" id="trimmer-mode-replace" class="text-purple-600 focus:ring-purple-500">
                                <span>Replace Selected Video</span>
                            </label>
                        </div>

                        {{-- Bottom Modal Buttons --}}
                        <div class="flex items-center justify-end gap-2.5 sm:gap-3 pt-3 border-t border-slate-100 dark:border-slate-800">
                            <button type="button" onclick="closeTrimmerModal()" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all cursor-pointer">
                                Cancel
                            </button>
                            <button type="button" id="trimmer-submit-btn" onclick="executeVideoTrimming()" class="px-4 sm:px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white text-xs font-bold rounded-xl shadow-md transition-all flex items-center gap-1.5 cursor-pointer">
                                <i class="ph-bold ph-scissors text-sm"></i>
                                <span id="trimmer-submit-text">✂️ Cut & Add Clip</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

                {{-- Step 4 Quick Review Summary Card --}}
                <div class="bg-blue-50/60 dark:bg-blue-950/30 border border-blue-200/80 dark:border-blue-900/50 rounded-xl sm:rounded-2xl p-4 sm:p-5 text-xs text-slate-700 dark:text-slate-300 shadow-xs">
                    <div class="flex items-center gap-2.5 font-bold text-slate-900 dark:text-white text-sm mb-1.5">
                        <i class="ph-fill ph-check-circle text-blue-600 text-lg"></i>
                        <span>Almost Done! Review & Publish</span>
                    </div>
                    <p class="text-slate-500 dark:text-slate-400 mb-2">Double check your listing details before publishing to verified tenants.</p>
                    <div id="step4-summary-details" class="border-t border-blue-100 dark:border-blue-900/50">
                        {{-- Populated dynamically via JS updateStep4Summary() --}}
                    </div>
                </div>

                {{-- Step 4 Footer Navigation --}}
                <div class="flex items-center justify-between pt-4 sm:pt-6 border-t border-slate-200/70 dark:border-slate-800">
                    <button type="button" onclick="goToStep(3)" class="px-4 sm:px-5 py-2.5 sm:py-3 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs sm:text-sm rounded-xl transition-all flex items-center gap-2 cursor-pointer">
                        <i class="ph-bold ph-arrow-left"></i>
                        <span>Back to Location</span>
                    </button>
                    <button type="submit" class="px-6 sm:px-8 py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold text-sm sm:text-base rounded-xl shadow-md shadow-blue-500/25 transition-all active:scale-[0.98] flex items-center justify-center gap-2 cursor-pointer disabled:opacity-75 disabled:cursor-not-allowed" id="create-submit">
                        <i class="ph-bold ph-paper-plane-tilt text-base" id="submit-icon"></i> 
                        <span id="submit-label">Publish Property Listing</span>
                    </button>
                </div>
            </div>
        </form>

    </div>
</section>

<script>
let selectedFiles = [];
let primaryImageIndex = 0;

// Maximum duration allowed for a single clip (guarantees fast upload and instant processing)
const MAX_CLIP_SECONDS = 60;

// High-speed client-side image compression
async function compressImage(file, maxDimension = 1600, quality = 0.85) {
    if (file.type === 'image/svg+xml' || file.size < 150 * 1024) {
        return { file: file, originalSize: file.size, compressedSize: file.size };
    }

    return new Promise((resolve) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = (e) => {
            const img = new Image();
            img.src = e.target.result;
            img.onload = () => {
                let width = img.width;
                let height = img.height;

                if (width > maxDimension || height > maxDimension) {
                    if (width > height) {
                        height = Math.round((height * maxDimension) / width);
                        width = maxDimension;
                    } else {
                        width = Math.round((width * maxDimension) / height);
                        height = maxDimension;
                    }
                }

                const canvas = document.createElement('canvas');
                canvas.width = width;
                canvas.height = height;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);

                const mimeType = 'image/jpeg';
                canvas.toBlob((blob) => {
                    if (blob && blob.size < file.size) {
                        const newName = file.name.replace(/\.[^/.]+$/, "") + ".jpg";
                        const compressedFile = new File([blob], newName, {
                            type: mimeType,
                            lastModified: Date.now()
                        });
                        resolve({ file: compressedFile, originalSize: file.size, compressedSize: compressedFile.size });
                    } else {
                        resolve({ file: file, originalSize: file.size, compressedSize: file.size });
                    }
                }, mimeType, quality);
            };
            img.onerror = () => resolve({ file: file, originalSize: file.size, compressedSize: file.size });
        };
        reader.onerror = () => resolve({ file: file, originalSize: file.size, compressedSize: file.size });
    });
}

async function handleImageSelection(event) {
    const input = event.target;
    const newRawFiles = Array.from(input.files);
    
    if (selectedFiles.length + newRawFiles.length > 10) {
        alert('You can upload a maximum of 10 photos per property.');
        syncInputFiles();
        return;
    }

    const statusBox = document.getElementById('compression-status');
    const statusText = document.getElementById('compression-status-text');
    const savedText = document.getElementById('compression-saved-text');
    
    if (statusBox) {
        statusBox.classList.remove('hidden');
        statusText.textContent = `Optimizing ${newRawFiles.length} photo(s) for fast upload...`;
        savedText.textContent = 'Processing...';
    }

    let totalOriginal = 0;
    let totalCompressed = 0;

    const compressedResults = await Promise.all(newRawFiles.map(f => compressImage(f)));

    compressedResults.forEach(res => {
        selectedFiles.push(res.file);
        totalOriginal += res.originalSize;
        totalCompressed += res.compressedSize;
    });

    syncInputFiles();
    renderPreviews();

    if (statusBox && totalOriginal > 0) {
        const origMB = (totalOriginal / (1024 * 1024)).toFixed(1);
        const compMB = (totalCompressed / (1024 * 1024)).toFixed(1);
        const savings = Math.max(0, Math.round(((totalOriginal - totalCompressed) / totalOriginal) * 100));

        statusText.textContent = `⚡ Optimized ${selectedFiles.length} photo(s) (${origMB}MB → ${compMB}MB)`;
        savedText.textContent = `${savings}% Faster Upload`;
    }
}

function syncInputFiles() {
    const input = document.getElementById('create-images-input');
    const dataTransfer = new DataTransfer();
    selectedFiles.forEach(file => dataTransfer.items.add(file));
    input.files = dataTransfer.files;

    const primaryInput = document.getElementById('primary-image-input');
    if (primaryInput) {
        primaryInput.value = primaryImageIndex;
    }
}

function setPrimaryImage(index) {
    primaryImageIndex = index;
    const primaryInput = document.getElementById('primary-image-input');
    if (primaryInput) primaryInput.value = index;

    // Instantly update DOM cards
    document.querySelectorAll('#image-preview-container .preview-card').forEach((card, idx) => {
        if (idx === index) {
            card.classList.add('is-primary-card');
        } else {
            card.classList.remove('is-primary-card');
        }
    });
}

function renderPreviews() {
    const container = document.getElementById('image-preview-container');
    const guidance = document.getElementById('cover-photo-guidance');
    if (!container) return;
    container.innerHTML = ''; 
    
    if (selectedFiles.length > 0) {
        container.classList.remove('hidden');
        if (guidance) guidance.classList.remove('hidden');
    } else {
        container.classList.add('hidden');
        if (guidance) guidance.classList.add('hidden');
        const statusBox = document.getElementById('compression-status');
        if (statusBox) statusBox.classList.add('hidden');
        return;
    }

    selectedFiles.forEach((file, index) => {
        const isPrimary = (index === primaryImageIndex);
        const imgBox = document.createElement('div');
        imgBox.className = `preview-card group ${isPrimary ? 'is-primary-card' : ''}`;
        imgBox.style.aspectRatio = '1 / 1';
        imgBox.style.maxHeight = '140px';
        imgBox.id = `preview-card-${index}`;
        imgBox.onclick = () => setPrimaryImage(index);
        imgBox.title = isPrimary ? 'Current Cover Photo' : 'Click to set as Cover Photo';

        const img = document.createElement('img');
        img.className = 'w-full h-full object-cover group-hover:scale-108 transition-transform duration-500 pointer-events-none';
        img.src = URL.createObjectURL(file);
        imgBox.appendChild(img);

        const actionsDiv = document.createElement('div');
        actionsDiv.innerHTML = `
            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/25 transition-colors pointer-events-none z-1"></div>

            <div class="cover-badge-pill">
                <i class="ph-fill ph-star text-amber-300 text-xs"></i>
                <span>Cover Photo</span>
            </div>

            <div class="set-cover-pill">
                <i class="ph-bold ph-star text-amber-300 text-xs"></i>
                <span>Set as Cover</span>
            </div>

            <button type="button" onclick="event.stopPropagation(); removeImage(${index})" class="photo-delete-btn" title="Remove photo">
                <i class="ph-bold ph-trash text-xs"></i>
            </button>
        `;
        imgBox.appendChild(actionsDiv);
        container.appendChild(imgBox);
    });
}

function removeImage(index) {
    selectedFiles.splice(index, 1);
    if (primaryImageIndex >= selectedFiles.length) {
        primaryImageIndex = Math.max(0, selectedFiles.length - 1);
    }
    syncInputFiles();
    renderPreviews();
}

// --- MULTI-VIDEO & VIDEO TRIMMER / COMPRESSION ENGINE ---
let uploadedVideoList = []; // Array of { id, file, name, size, originalSize, isCompressed, isTrimmed, previewUrl, isUrl, url }
let activeTrimmerItem = null;
let trimmerPlayer = null;

function handleMultipleVideoSelection(event) {
    const files = Array.from(event.target.files || []);
    if (files.length === 0) return;

    const maxVideos = 10;
    const remainingSlots = maxVideos - uploadedVideoList.length;

    if (remainingSlots <= 0) {
        alert('You have reached the maximum limit of 10 videos/clips per property. Please remove a video before uploading more.');
        event.target.value = '';
        return;
    }

    let filesToProcess = files;
    if (files.length > remainingSlots) {
        alert(`You can only add ${remainingSlots} more video(s). Maximum 10 videos allowed. Only the first ${remainingSlots} will be attached.`);
        filesToProcess = files.slice(0, remainingSlots);
    }

    let firstLargeItemToTrim = null;

    filesToProcess.forEach(file => {
        // Allow up to 2GB local file selection so users can trim 4K/HD phone videos!
        if (file.size > 2048 * 1024 * 1024) {
            alert(`"${file.name}" is unusually large (> 2GB). Please select a video file under 2GB.`);
            return;
        }

        const videoId = 'vid_' + Date.now() + '_' + Math.random().toString(36).substr(2, 6);
        const item = {
            id: videoId,
            file: file,
            name: file.name,
            originalSize: file.size,
            size: file.size,
            isCompressed: false,
            isTrimmed: false,
            previewUrl: URL.createObjectURL(file),
            isUrl: false
        };
        uploadedVideoList.push(item);

        // Track large files for info banner
        if (file.size > 50 * 1024 * 1024 && !firstLargeItemToTrim) {
            firstLargeItemToTrim = item;
        }
    });

    event.target.value = '';
    renderVideoPreviews();
    syncVideoFormInputs();

    // Show a non-intrusive info banner for large files (no popup)
    if (firstLargeItemToTrim) {
        const sizeMB = (firstLargeItemToTrim.size / (1024 * 1024)).toFixed(0);
        const statusWrapper = document.getElementById('video-compress-status');
        const statusText = document.getElementById('compress-status-text');
        if (statusWrapper && statusText) {
            statusText.innerHTML = `📹 <strong>"${firstLargeItemToTrim.name}"</strong> (${sizeMB} MB) added. It will be <strong>auto-compressed on submit</strong> for fast upload. You can also use "Trim Clip" to select a highlight.`;
            statusWrapper.classList.remove('hidden');
            setTimeout(() => statusWrapper.classList.add('hidden'), 6000);
        }
    }
}

function addVideoUrlLink() {
    if (uploadedVideoList.length >= 10) {
        alert('You have reached the maximum limit of 10 videos/clips per property. Please remove a video before adding a new link.');
        return;
    }

    const input = document.getElementById('video-url-input');
    const url = (input.value || '').trim();
    if (!url) return;

    if (!url.startsWith('http://') && !url.startsWith('https://')) {
        alert('Please enter a valid video link starting with http:// or https://');
        return;
    }

    const videoId = 'url_' + Date.now() + '_' + Math.random().toString(36).substr(2, 6);
    uploadedVideoList.push({
        id: videoId,
        file: null,
        name: 'Video Link: ' + url.replace(/^https?:\/\/(www\.)?/, '').substring(0, 30) + '...',
        url: url,
        isUrl: true,
        isTrimmed: false,
        previewUrl: url
    });

    input.value = '';
    renderVideoPreviews();
    syncVideoFormInputs();
}

function removeVideoItem(id) {
    const idx = uploadedVideoList.findIndex(v => v.id === id);
    if (idx !== -1) {
        if (uploadedVideoList[idx].previewUrl && !uploadedVideoList[idx].isUrl) {
            URL.revokeObjectURL(uploadedVideoList[idx].previewUrl);
        }
        uploadedVideoList.splice(idx, 1);
        renderVideoPreviews();
        syncVideoFormInputs();
    }
}

// Format seconds into MM:SS
function formatTime(seconds) {
    const mins = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);
    return `${mins < 10 ? '0' : ''}${mins}:${secs < 10 ? '0' : ''}${secs}`;
}

// --- VIDEO TRIMMER MODAL LOGIC ---
function openTrimmerModal(videoId) {
    const item = uploadedVideoList.find(v => v.id === videoId);
    if (!item || item.isUrl || !item.file) return;

    activeTrimmerItem = item;
    const modal = document.getElementById('video-trimmer-modal');
    trimmerPlayer = document.getElementById('trimmer-preview-player');
    const clipTitleInput = document.getElementById('trimmer-clip-title');

    if (!modal || !trimmerPlayer) return;

    // Teleport modal to body so it floats on top of everything without footer bleed
    if (modal.parentElement !== document.body) {
        document.body.appendChild(modal);
    }

    if (clipTitleInput) clipTitleInput.value = '';
    trimmerPlayer.src = item.previewUrl;
    modal.classList.remove('hidden');

    trimmerPlayer.onloadedmetadata = () => {
        const duration = trimmerPlayer.duration || 30;
        const totalDurationEl = document.getElementById('trimmer-total-duration');
        if (totalDurationEl) totalDurationEl.textContent = formatTime(duration);

        const startSlider = document.getElementById('trimmer-start-slider');
        const endSlider = document.getElementById('trimmer-end-slider');

        if (startSlider && endSlider) {
            startSlider.min = 0;
            startSlider.max = duration.toFixed(1);
            startSlider.value = 0;

            endSlider.min = 0;
            endSlider.max = duration.toFixed(1);
            // Default to 30s clip for instant processing
            endSlider.value = Math.min(30, duration).toFixed(1);
        }

        updateTrimDisplay();
    };

    trimmerPlayer.ontimeupdate = () => {
        const currentEl = document.getElementById('trimmer-current-time');
        if (currentEl && trimmerPlayer) {
            currentEl.textContent = formatTime(trimmerPlayer.currentTime);
        }
    };
}

function closeTrimmerModal() {
    const modal = document.getElementById('video-trimmer-modal');
    if (trimmerPlayer) {
        trimmerPlayer.pause();
        trimmerPlayer.removeAttribute('src');
        trimmerPlayer.load();
    }
    const progressWrapper = document.getElementById('trimmer-progress-wrapper');
    if (progressWrapper) progressWrapper.classList.add('hidden');
    if (modal) modal.classList.add('hidden');
    activeTrimmerItem = null;
}

let selectedTrimWindow = 30; // default 30s preset duration

function highlightActivePresetBtn(sec) {
    document.querySelectorAll('.trim-preset-btn').forEach(btn => {
        btn.classList.remove('border-purple-600', 'bg-purple-50', 'dark:bg-purple-950/60', 'text-purple-700', 'dark:text-purple-300', 'font-bold', 'ring-2', 'ring-purple-600/30');
    });
    const activeBtn = document.getElementById(`trim-preset-${sec}`);
    if (activeBtn) {
        activeBtn.classList.add('border-purple-600', 'bg-purple-50', 'dark:bg-purple-950/60', 'text-purple-700', 'dark:text-purple-300', 'font-bold', 'ring-2', 'ring-purple-600/30');
    }
}

function onTrimStartSliderChange(val) {
    let startVal = parseFloat(val);
    const endSlider = document.getElementById('trimmer-end-slider');
    const maxDuration = trimmerPlayer?.duration || parseFloat(endSlider?.max || 60);

    // Automatically shift End Time with Start Time to keep selected clip duration (e.g. 60s)
    let endVal = Math.min(maxDuration, startVal + selectedTrimWindow);
    if (endSlider) {
        endSlider.value = endVal.toFixed(1);
    }

    if (trimmerPlayer) trimmerPlayer.currentTime = startVal;
    updateTrimDisplay();
}

function onTrimEndSliderChange(val) {
    let endVal = parseFloat(val);
    const startSlider = document.getElementById('trimmer-start-slider');
    const startVal = parseFloat(startSlider?.value || 0);

    if (startSlider && endVal <= startVal) {
        endVal = Math.min(parseFloat(document.getElementById('trimmer-end-slider').max), startVal + 1);
        document.getElementById('trimmer-end-slider').value = endVal.toFixed(1);
    }

    // Auto-clamp to 60s max per clip
    if (endVal - startVal > MAX_CLIP_SECONDS) {
        endVal = startVal + MAX_CLIP_SECONDS;
        document.getElementById('trimmer-end-slider').value = endVal.toFixed(1);
    }

    // Remember user's custom clip duration
    selectedTrimWindow = Math.max(1, endVal - startVal);
    highlightActivePresetBtn(Math.round(selectedTrimWindow));

    if (trimmerPlayer) trimmerPlayer.currentTime = endVal;
    updateTrimDisplay();
}

function setTrimStartToCurrent() {
    if (!trimmerPlayer) return;
    const current = trimmerPlayer.currentTime;
    const startSlider = document.getElementById('trimmer-start-slider');
    if (startSlider) {
        startSlider.value = current.toFixed(1);
        onTrimStartSliderChange(current);
    }
}

function setTrimEndToCurrent() {
    if (!trimmerPlayer) return;
    const current = trimmerPlayer.currentTime;
    const endSlider = document.getElementById('trimmer-end-slider');
    if (endSlider) {
        endSlider.value = current.toFixed(1);
        onTrimEndSliderChange(current);
    }
}

function applyTrimPreset(seconds) {
    if (!trimmerPlayer) return;
    const duration = trimmerPlayer.duration || 30;
    selectedTrimWindow = seconds;
    highlightActivePresetBtn(seconds);

    const startSlider = document.getElementById('trimmer-start-slider');
    const endSlider = document.getElementById('trimmer-end-slider');

    if (startSlider && endSlider) {
        const curStart = parseFloat(startSlider.value) || 0;
        let startVal = curStart;
        if (startVal + selectedTrimWindow > duration) {
            startVal = Math.max(0, duration - selectedTrimWindow);
            startSlider.value = startVal.toFixed(1);
        }
        const endVal = Math.min(duration, startVal + selectedTrimWindow);
        endSlider.value = endVal.toFixed(1);
        updateTrimDisplay();
        if (trimmerPlayer) trimmerPlayer.currentTime = startVal;
    }
}

function updateTrimDisplay() {
    const startSlider = document.getElementById('trimmer-start-slider');
    const endSlider = document.getElementById('trimmer-end-slider');
    const startDisplay = document.getElementById('trimmer-start-display');
    const endDisplay = document.getElementById('trimmer-end-display');
    const durationBadge = document.getElementById('trimmer-duration-badge');
    const speedBadge = document.getElementById('trimmer-speed-badge');

    if (!startSlider || !endSlider) return;

    const start = parseFloat(startSlider.value) || 0;
    const end = parseFloat(endSlider.value) || 0;
    const diff = Math.max(0, end - start);

    if (startDisplay) startDisplay.textContent = `${start.toFixed(1)}s (${formatTime(start)})`;
    if (endDisplay) endDisplay.textContent = `${end.toFixed(1)}s (${formatTime(end)})`;
    if (durationBadge) {
        durationBadge.textContent = `${diff.toFixed(1)}s`;
    }

    const estMb = Math.max(0.4, (diff * 0.16)).toFixed(1);
    if (speedBadge) {
        if (diff <= 15) {
            speedBadge.innerHTML = `<span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 text-[11px] font-bold rounded-full border border-emerald-200/50">⚡ ~${estMb} MB · Instant Upload (~1s)</span>`;
        } else if (diff <= 30) {
            speedBadge.innerHTML = `<span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 text-[11px] font-bold rounded-full border border-emerald-200/50">⚡ ~${estMb} MB · Fast Upload (~2s)</span>`;
        } else if (diff <= 60) {
            speedBadge.innerHTML = `<span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-indigo-100 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 text-[11px] font-bold rounded-full border border-indigo-200/50">🚀 ~${estMb} MB · Tour Clip (~4s)</span>`;
        } else {
            speedBadge.innerHTML = `<span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 text-[11px] font-bold rounded-full border border-amber-200/50">⚠️ ~${estMb} MB · Max 60s per clip</span>`;
        }
    }
}

function previewTrimRange() {
    if (!trimmerPlayer) return;
    const startSlider = document.getElementById('trimmer-start-slider');
    const endSlider = document.getElementById('trimmer-end-slider');

    const start = parseFloat(startSlider?.value || 0);
    const end = parseFloat(endSlider?.value || 30);

    trimmerPlayer.currentTime = start;
    trimmerPlayer.play();

    const checkTime = () => {
        if (trimmerPlayer.currentTime >= end || trimmerPlayer.paused) {
            trimmerPlayer.pause();
            trimmerPlayer.removeEventListener('timeupdate', checkTime);
        }
    };
    trimmerPlayer.addEventListener('timeupdate', checkTime);
}

// Background high-speed server uploader (takes ~0.5s for 1.5MB clip)
async function uploadCreateVideoToServer(file, onProgress) {
    return new Promise((resolve, reject) => {
        const formData = new FormData();
        formData.append('video', file);
        formData.append('_token', '{{ csrf_token() }}');

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '{{ route("property.video.upload") }}', true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.setRequestHeader('Accept', 'application/json');

        xhr.upload.onprogress = (e) => {
            if (e.lengthComputable && onProgress) {
                const pct = Math.min(99, Math.round((e.loaded / e.total) * 100));
                onProgress(pct);
            }
        };

        xhr.onload = () => {
            if (xhr.status >= 200 && xhr.status < 400) {
                try {
                    const res = JSON.parse(xhr.responseText);
                    if (res.success && res.path) {
                        resolve(res.path);
                    } else {
                        resolve(null);
                    }
                } catch(err) {
                    resolve(null);
                }
            } else {
                resolve(null);
            }
        };

        xhr.onerror = () => resolve(null);
        xhr.send(formData);
    });
}

// --- EXECUTE TRIM & EXTRACT VIDEO CLIP ---
async function executeVideoTrimming() {
    if (!activeTrimmerItem || !activeTrimmerItem.file) return;

    const startSlider = document.getElementById('trimmer-start-slider');
    const endSlider = document.getElementById('trimmer-end-slider');
    const clipTitleInput = document.getElementById('trimmer-clip-title');
    const progressWrapper = document.getElementById('trimmer-progress-wrapper');
    const progressPct = document.getElementById('trimmer-progress-pct');
    const progressBar = document.getElementById('trimmer-progress-bar');
    const submitBtn = document.getElementById('trimmer-submit-btn');
    const submitText = document.getElementById('trimmer-submit-text');
    const isReplace = document.getElementById('trimmer-mode-replace')?.checked;

    const startTime = parseFloat(startSlider?.value || 0);
    const endTime = parseFloat(endSlider?.value || 30);
    const customTitle = (clipTitleInput?.value || '').trim();

    if (progressWrapper) progressWrapper.classList.remove('hidden');
    if (submitBtn) submitBtn.disabled = true;
    if (submitText) submitText.textContent = '⚡ Extracting Clip...';

    try {
        // Step 1: Slice video at 8x speed (~4-6s)
        const trimmedBlob = await runVideoSliceAndCompress(
            activeTrimmerItem.file,
            startTime,
            endTime,
            (pct) => {
                if (progressPct) progressPct.textContent = `Processing: ${Math.round(pct)}%`;
                if (progressBar) progressBar.style.width = `${pct * 0.7}%`;
            }
        );

        const safeTitle = customTitle || activeTrimmerItem.name.replace(/\.[^/.]+$/, "") + `_clip_${Math.round(startTime)}-${Math.round(endTime)}s`;
        const clipFileName = `${safeTitle.replace(/[^a-zA-Z0-9_-]/g, '_')}.webm`;
        const clipFile = new File([trimmedBlob], clipFileName, { type: 'video/webm' });

        // Step 2: Instant Background Upload to server (~0.5s for 1.5MB)
        if (progressPct) progressPct.textContent = 'Uploading to server...';
        if (progressBar) progressBar.style.width = '85%';

        let serverPath = null;
        try {
            serverPath = await uploadCreateVideoToServer(clipFile, (upPct) => {
                if (progressPct) progressPct.textContent = `Uploading: ${upPct}%`;
                if (progressBar) progressBar.style.width = `${70 + (upPct * 0.3)}%`;
            });
        } catch(upErr) {
            console.warn('Direct upload fallback:', upErr);
        }

        // If user chose replace, replace the active item
        if (isReplace) {
            if (activeTrimmerItem.previewUrl) URL.revokeObjectURL(activeTrimmerItem.previewUrl);
            activeTrimmerItem.file = clipFile;
            activeTrimmerItem.name = customTitle ? `${customTitle} (Trimmed Clip)` : clipFileName;
            activeTrimmerItem.size = trimmedBlob.size;
            activeTrimmerItem.originalSize = trimmedBlob.size;
            activeTrimmerItem.isTrimmed = true;
            activeTrimmerItem.isCompressed = true;
            activeTrimmerItem.serverPath = serverPath;
            activeTrimmerItem.previewUrl = URL.createObjectURL(trimmedBlob);
        } else {
            if (uploadedVideoList.length >= 10) {
                alert('You have already reached the maximum limit of 10 videos/clips.');
                if (progressWrapper) progressWrapper.classList.add('hidden');
                if (submitBtn) submitBtn.disabled = false;
                if (submitText) submitText.textContent = '✂️ Cut & Add Clip to Uploads';
                return;
            }

            const newId = 'vid_clip_' + Date.now() + '_' + Math.random().toString(36).substr(2, 6);
            uploadedVideoList.push({
                id: newId,
                file: clipFile,
                name: customTitle ? `${customTitle} (Trimmed Clip)` : `Clip: ${activeTrimmerItem.name} [${startTime.toFixed(0)}s-${endTime.toFixed(0)}s]`,
                originalSize: trimmedBlob.size,
                size: trimmedBlob.size,
                isCompressed: true,
                isTrimmed: true,
                serverPath: serverPath,
                previewUrl: URL.createObjectURL(trimmedBlob),
                isUrl: false
            });

            // If the original master file was oversized (>60MB) and not yet trimmed, remove the raw master so it doesn't block submission
            const rawIdx = uploadedVideoList.findIndex(v => v.id === activeTrimmerItem.id && !v.isTrimmed && v.originalSize > 60 * 1024 * 1024);
            if (rawIdx !== -1) {
                uploadedVideoList.splice(rawIdx, 1);
            }
        }

        renderVideoPreviews();
        syncVideoFormInputs();
        closeTrimmerModal();

        // Flash message in status
        const statusWrapper = document.getElementById('video-compress-status');
        const statusText = document.getElementById('compress-status-text');
        if (statusWrapper && statusText) {
            statusText.innerHTML = `🎉 Clip successfully trimmed & added! (${uploadedVideoList.length}/10 Clips Attached · ${(trimmedBlob.size/(1024*1024)).toFixed(1)} MB).`;
            statusWrapper.classList.remove('hidden');
            setTimeout(() => statusWrapper.classList.add('hidden'), 4000);
        }

    } catch (err) {
        console.error('Trimming error:', err);
        alert('Could not trim this video format. Using full file with compression.');
    } finally {
        if (progressWrapper) progressWrapper.classList.add('hidden');
        if (submitBtn) submitBtn.disabled = false;
        if (submitText) submitText.textContent = '✂️ Cut & Add Clip to Uploads';
    }
}

// Slice video range [startTime -> endTime] and compress using Canvas & MediaRecorder
function runVideoSliceAndCompress(file, startTime, endTime, onProgress) {
    return new Promise((resolve, reject) => {
        const video = document.createElement('video');
        video.src = URL.createObjectURL(file);
        video.muted = true;
        video.playsInline = true;
        video.crossOrigin = 'anonymous';

        video.onloadedmetadata = () => {
            const duration = Math.max(0.5, Math.min(endTime, video.duration) - Math.max(0, startTime));
            let width = video.videoWidth || 960;
            let height = video.videoHeight || 540;

            const maxDimension = 960;
            if (width > maxDimension || height > maxDimension) {
                if (width > height) {
                    height = Math.round((height * maxDimension) / width);
                    width = maxDimension;
                } else {
                    width = Math.round((width * maxDimension) / height);
                    height = maxDimension;
                }
            }

            const canvas = document.createElement('canvas');
            canvas.width = (width % 2 === 0) ? width : width - 1;
            canvas.height = (height % 2 === 0) ? height : height - 1;
            const ctx = canvas.getContext('2d', { alpha: false });

            if (!canvas.captureStream || typeof MediaRecorder === 'undefined') {
                return resolve(file);
            }

            const stream = canvas.captureStream(25);

            let mimeType = 'video/webm;codecs=vp8';
            if (!MediaRecorder.isTypeSupported(mimeType)) {
                mimeType = MediaRecorder.isTypeSupported('video/webm') ? 'video/webm' : 'video/mp4';
            }

            let mediaRecorder;
            try {
                mediaRecorder = new MediaRecorder(stream, {
                    mimeType: MediaRecorder.isTypeSupported(mimeType) ? mimeType : undefined,
                    videoBitsPerSecond: 900000 // 900 kbps ultra-fast & lightweight
                });
            } catch(e) {
                mediaRecorder = new MediaRecorder(stream);
            }

            const chunks = [];
            mediaRecorder.ondataavailable = e => { if (e.data && e.data.size > 0) chunks.push(e.data); };

            let isCompleted = false;
            const finishRecording = () => {
                if (isCompleted) return;
                isCompleted = true;
                if (onProgress) onProgress(100);
                try {
                    if (mediaRecorder.state === 'recording') {
                        mediaRecorder.stop();
                    }
                } catch(e) {
                    console.warn(e);
                }
            };

            mediaRecorder.onstop = () => {
                const blob = new Blob(chunks, { type: 'video/webm' });
                URL.revokeObjectURL(video.src);
                resolve(blob);
            };

            // Set normal 1.0x playback rate for full duration & natural smooth motion
            video.playbackRate = 1.0;
            video.currentTime = startTime;
            let isRecording = false;

            video.onseeked = () => {
                if (isRecording) return;
                isRecording = true;
                try {
                    mediaRecorder.start(100);
                } catch(e) {
                    return resolve(file);
                }
                video.play().catch(e => {
                    return resolve(file);
                });

                const drawFrame = () => {
                    if (isCompleted) return;
                    if (!video.paused && !video.ended) {
                        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                        const progress = Math.min(99, Math.max(1, ((video.currentTime - startTime) / (endTime - startTime)) * 100));
                        if (onProgress) onProgress(progress);
                    }
                    if (video.currentTime >= endTime || video.ended) {
                        finishRecording();
                        return;
                    }
                    requestAnimationFrame(drawFrame);
                };
                requestAnimationFrame(drawFrame);

                const timeoutMs = ((endTime - startTime) + 3) * 1000;
                setTimeout(() => finishRecording(), timeoutMs);
            };
        };

        video.onerror = () => resolve(file);
    });
}

// Client-Side Full Video Compressor
async function compressVideoItem(id) {
    const item = uploadedVideoList.find(v => v.id === id);
    if (!item || item.isUrl || !item.file) return;

    const statusWrapper = document.getElementById('video-compress-status');
    const statusText = document.getElementById('compress-status-text');
    const statusPct = document.getElementById('compress-status-pct');
    const progressBar = document.getElementById('compress-progress-bar');

    if (statusWrapper) statusWrapper.classList.remove('hidden');
    if (statusText) statusText.textContent = `Optimizing "${item.name}" for fast posting...`;

    try {
        const compressedBlob = await runVideoCompression(item.file, (pct) => {
            if (statusPct) statusPct.textContent = `${Math.round(pct)}%`;
            if (progressBar) progressBar.style.width = `${pct}%`;
        });

        if (compressedBlob && compressedBlob.size < item.file.size) {
            const compressedFile = new File([compressedBlob], item.name.replace(/\.[^/.]+$/, "") + "_compressed.webm", {
                type: 'video/webm'
            });
            item.file = compressedFile;
            item.size = compressedBlob.size;
            item.isCompressed = true;
            if (item.previewUrl) URL.revokeObjectURL(item.previewUrl);
            item.previewUrl = URL.createObjectURL(compressedBlob);
            
            if (statusText) statusText.textContent = `Done! Reduced from ${(item.originalSize / (1024*1024)).toFixed(1)}MB to ${(item.size / (1024*1024)).toFixed(1)}MB 🎉`;
            setTimeout(() => { if (statusWrapper) statusWrapper.classList.add('hidden'); }, 3500);
        } else {
            if (statusText) statusText.textContent = 'Video is already at optimal size!';
            setTimeout(() => { if (statusWrapper) statusWrapper.classList.add('hidden'); }, 2500);
        }
    } catch (e) {
        console.warn('Compression skipped, using original video file:', e);
        if (statusWrapper) statusWrapper.classList.add('hidden');
    }

    renderVideoPreviews();
    syncVideoFormInputs();
}

function runVideoCompression(file, onProgress) {
    return new Promise((resolve) => {
        const video = document.createElement('video');
        video.src = URL.createObjectURL(file);
        video.muted = true;
        video.playsInline = true;
        
        video.onloadedmetadata = () => {
            let width = video.videoWidth || 1280;
            let height = video.videoHeight || 720;
            
            const maxDimension = 1280;
            if (width > maxDimension || height > maxDimension) {
                if (width > height) {
                    height = Math.round((height * maxDimension) / width);
                    width = maxDimension;
                } else {
                    width = Math.round((width * maxDimension) / height);
                    height = maxDimension;
                }
            }

            const canvas = document.createElement('canvas');
            canvas.width = width;
            canvas.height = height;
            const ctx = canvas.getContext('2d');

            if (!canvas.captureStream || typeof MediaRecorder === 'undefined') {
                return resolve(file);
            }

            const stream = canvas.captureStream(25);
            let mediaRecorder;
            try {
                mediaRecorder = new MediaRecorder(stream, {
                    mimeType: MediaRecorder.isTypeSupported('video/webm;codecs=vp8') ? 'video/webm;codecs=vp8' : 'video/webm',
                    videoBitsPerSecond: 1800000
                });
            } catch(e) {
                return resolve(file);
            }

            const chunks = [];
            mediaRecorder.ondataavailable = e => { if (e.data && e.data.size > 0) chunks.push(e.data); };
            mediaRecorder.onstop = () => {
                const blob = new Blob(chunks, { type: 'video/webm' });
                URL.revokeObjectURL(video.src);
                resolve(blob);
            };

            // 🚀 8x FAST PROCESSING for full-video compression too!
            video.playbackRate = 8;
            mediaRecorder.start(100);
            video.currentTime = 0;
            video.play();

            const drawFrame = () => {
                if (video.paused || video.ended) {
                    if (video.ended && mediaRecorder.state === 'recording') {
                        mediaRecorder.stop();
                    }
                    return;
                }
                ctx.drawImage(video, 0, 0, width, height);
                if (video.duration) {
                    const progress = (video.currentTime / video.duration) * 100;
                    onProgress(Math.min(progress, 99));
                }
                requestAnimationFrame(drawFrame);
            };

            video.onplay = () => requestAnimationFrame(drawFrame);
            video.onended = () => {
                if (mediaRecorder.state === 'recording') mediaRecorder.stop();
            };

            // Safety timeout: even at 8x, cap at 120s max processing
            setTimeout(() => {
                if (mediaRecorder.state === 'recording') {
                    mediaRecorder.stop();
                }
            }, 120000);
        };

        video.onerror = () => resolve(file);
    });
}

function renderVideoPreviews() {
    const container = document.getElementById('video-previews-container');
    if (!container) return;

    if (uploadedVideoList.length === 0) {
        container.innerHTML = '';
        return;
    }

    const isMax = uploadedVideoList.length >= 10;

    let html = `<div class="p-3 bg-purple-50/60 dark:bg-purple-950/30 rounded-xl border border-purple-100 dark:border-purple-900/40 mb-2 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
        <div class="flex items-center gap-2">
            <span class="text-xs font-bold text-purple-900 dark:text-purple-300 flex items-center gap-1.5">
                <i class="ph-bold ph-video text-purple-600"></i> ${uploadedVideoList.length} / 10 Video Clips Attached
            </span>
            ${isMax ? `<span class="px-2 py-0.5 bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 text-[10px] font-extrabold rounded-md">Max 10 Reached</span>` : ''}
        </div>
        ${!isMax ? `
            <button type="button" onclick="document.getElementById('create-video-input').click()" class="text-[11px] font-extrabold text-purple-700 dark:text-purple-300 hover:underline flex items-center gap-1 cursor-pointer">
                <i class="ph-bold ph-plus-circle"></i> Add More Videos / Clips (${10 - uploadedVideoList.length} slots left)
            </button>
        ` : `
            <span class="text-[11px] text-slate-400 font-semibold">Maximum 10 clips limit reached</span>
        `}
    </div>`;

    uploadedVideoList.forEach((v, index) => {
        const sizeMb = v.size ? (v.size / (1024 * 1024)).toFixed(1) + ' MB' : '';

        html += `
        <div class="relative rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800/90 shadow-sm p-3.5 flex flex-col sm:flex-row items-center gap-3">
            ${v.isUrl ? `
                <div class="w-full sm:w-36 h-20 rounded-lg bg-slate-900 flex flex-col items-center justify-center text-purple-400 shrink-0">
                    <i class="ph-bold ph-link text-2xl mb-0.5"></i>
                    <span class="text-[10px] text-slate-400 font-bold uppercase">External Tour</span>
                </div>
            ` : `
                <div class="w-full sm:w-36 h-20 rounded-lg bg-black overflow-hidden shrink-0 relative group">
                    <video src="${v.previewUrl}" class="w-full h-full object-cover" preload="metadata"></video>
                    <span class="absolute bottom-1 right-1 px-1.5 py-0.5 bg-black/80 text-white text-[9px] font-bold rounded">Clip ${index + 1}</span>
                </div>
            `}

            <div class="flex-1 min-w-0 w-full">
                <div class="flex flex-wrap items-center gap-2 mb-1">
                    <span class="text-xs font-bold text-slate-900 dark:text-white truncate max-w-xs">${v.name}</span>
                    ${v.isTrimmed ? `<span class="px-2 py-0.5 bg-purple-100 dark:bg-purple-950/60 text-purple-700 dark:text-purple-300 font-extrabold text-[10px] rounded-md shrink-0 flex items-center gap-1"><i class="ph-bold ph-scissors"></i> Trimmed Clip</span>` : ''}
                    ${v.isCompressed ? `<span class="px-2 py-0.5 bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 font-extrabold text-[10px] rounded-md shrink-0 flex items-center gap-1"><i class="ph-bold ph-lightning"></i> Fast Web Clip</span>` : ''}
                </div>
                
                <div class="flex flex-wrap items-center gap-2.5 text-xs text-slate-500 dark:text-slate-400">
                    ${v.isUrl ? `
                        <a href="${v.url}" target="_blank" class="text-purple-600 dark:text-purple-400 hover:underline truncate max-w-xs text-[11px]">${v.url}</a>
                    ` : `
                        <span>Size: <strong class="text-slate-700 dark:text-slate-200">${sizeMb}</strong></span>
                        <div class="flex flex-wrap items-center gap-1.5">
                            <button type="button" onclick="openTrimmerModal('${v.id}')" class="px-2.5 py-1 bg-purple-600 hover:bg-purple-700 text-white font-bold text-[11px] rounded-lg shadow-xs flex items-center gap-1 cursor-pointer">
                                <i class="ph-bold ph-scissors"></i> Trim Clip
                            </button>
                            ${(!v.isCompressed && v.size > 10 * 1024 * 1024) ? `
                                <button type="button" onclick="compressVideoItem('${v.id}')" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] rounded-lg shadow-xs flex items-center gap-1 cursor-pointer">
                                    <i class="ph-bold ph-lightning"></i> Fast Compress
                                </button>
                            ` : ''}
                        </div>
                    `}
                </div>
            </div>

            <button type="button" onclick="removeVideoItem('${v.id}')" class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-950/40 rounded-lg transition-all shrink-0 cursor-pointer" title="Remove Video Clip">
                <i class="ph-bold ph-trash text-base"></i>
            </button>
        </div>`;
    });

    container.innerHTML = html;
}

function syncVideoFormInputs() {
    const form = document.getElementById('create-property-form');
    if (!form) return;

    // Remove existing hidden video file & url inputs
    form.querySelectorAll('.sync-video-input').forEach(el => el.remove());

    // Sync file uploads using DataTransfer
    const fileItems = uploadedVideoList.filter(v => !v.isUrl && v.file);
    if (fileItems.length > 0) {
        const dt = new DataTransfer();
        fileItems.forEach(item => dt.items.add(item.file));

        const hiddenFileInput = document.createElement('input');
        hiddenFileInput.type = 'file';
        hiddenFileInput.name = 'videos[]';
        hiddenFileInput.multiple = true;
        hiddenFileInput.className = 'hidden sync-video-input';
        hiddenFileInput.files = dt.files;
        form.appendChild(hiddenFileInput);
    }

    // Sync pre-uploaded server paths
    const preUploadedItems = uploadedVideoList.filter(v => v.serverPath);
    preUploadedItems.forEach(item => {
        const hiddenPathInput = document.createElement('input');
        hiddenPathInput.type = 'hidden';
        hiddenPathInput.name = 'uploaded_video_paths[]';
        hiddenPathInput.value = item.serverPath;
        hiddenPathInput.className = 'sync-video-input';
        form.appendChild(hiddenPathInput);
    });

    // Sync URL links
    const urlItems = uploadedVideoList.filter(v => v.isUrl && v.url);
    urlItems.forEach(item => {
        const hiddenUrlInput = document.createElement('input');
        hiddenUrlInput.type = 'hidden';
        hiddenUrlInput.name = 'video_urls[]';
        hiddenUrlInput.value = item.url;
        hiddenUrlInput.className = 'sync-video-input';
        form.appendChild(hiddenUrlInput);
    });
}

// Dynamic Purpose UI Label Updater
window.onPurposeChange = function(purpose) {
    const priceLabel = document.getElementById('create-price-label');
    const priceInput = document.getElementById('create-price-input') || document.getElementById('create-price');
    const priceUnit = document.getElementById('create-price-unit');
    const priceHint = document.getElementById('create-price-unit-hint');
    const periodCol = document.getElementById('period-input-col');
    const saleCol = document.getElementById('sale-badge-col');
    const periodSelect = document.getElementById('create-period');
    const checkedType = document.querySelector('input[name="type"]:checked')?.value;

    if (purpose === 'buy' || purpose === 'sell') {
        if (priceLabel) priceLabel.innerHTML = 'Total Sale Price (₹) <span class="text-red-500">*</span>';
        if (priceHint) priceHint.classList.remove('hidden');
        if (periodCol) periodCol.classList.add('hidden');
        if (saleCol) saleCol.classList.remove('hidden');
        if (periodSelect) periodSelect.value = 'month'; // Default backend safe fallback

        if (priceUnit && (priceUnit.value === 'rupees' || !priceUnit.value)) {
            priceUnit.value = 'crore';
            if (priceInput && (!priceInput.value || priceInput.value === '25000')) {
                priceInput.value = '';
                priceInput.placeholder = 'e.g. 1.6 or 45';
            }
        }
    } else {
        if (priceHint) priceHint.classList.add('hidden');
        if (checkedType === 'pg-hostel') {
            if (priceLabel) priceLabel.innerHTML = 'Monthly Rent per Bed / Room (₹) <span class="text-red-500">*</span>';
            if (priceInput) priceInput.placeholder = '6,500';
        } else {
            if (priceLabel) priceLabel.innerHTML = 'Expected Rent (₹) <span class="text-red-500">*</span>';
            if (priceInput) priceInput.placeholder = '25,000';
        }
        if (periodCol) periodCol.classList.remove('hidden');
        if (saleCol) saleCol.classList.add('hidden');

        if (priceUnit && priceUnit.value === 'crore') {
            priceUnit.value = 'rupees';
            if (priceInput && (priceInput.value === '1.6' || priceInput.value === '1.5')) {
                priceInput.value = '';
            }
        }
    }

    if (window.urRecalculateCreatePrice) {
        window.urRecalculateCreatePrice();
    }
};

// Price & Unit Calculator Logic (Converts Lakh / Crore / Thousand into raw INR)
(function() {
    function initCreatePriceCalculator() {
        const input = document.getElementById('create-price-input');
        const unitSelect = document.getElementById('create-price-unit');
        const rawInput = document.getElementById('create-price');
        const preview = document.getElementById('create-price-preview-text');

        if (!input || !unitSelect || !rawInput) return;

        function getMultiplier(unit) {
            switch (unit) {
                case 'crore': return 10000000;
                case 'lakh': return 100000;
                case 'thousand': return 1000;
                default: return 1;
            }
        }

        function formatCurrencyWithWords(num) {
            if (!num || isNaN(num) || num <= 0) return '₹0';
            num = Math.round(num * 100) / 100;
            const formatted = new Intl.NumberFormat('en-IN', { maximumFractionDigits: 2 }).format(num);

            let desc = '';
            if (num >= 10000000) {
                const cr = num / 10000000;
                desc = (Math.round(cr * 100) / 100) + ' Cr';
            } else if (num >= 100000) {
                const lk = num / 100000;
                desc = (Math.round(lk * 100) / 100) + ' Lac';
            } else if (num >= 1000) {
                const th = num / 1000;
                desc = (Math.round(th * 10) / 10) + ' K';
            }

            return '₹ ' + formatted + (desc ? ' (' + desc + ')' : '');
        }

        function recalculate() {
            const val = parseFloat(input.value);
            const unit = unitSelect.value;
            const mult = getMultiplier(unit);

            if (isNaN(val) || val <= 0) {
                rawInput.value = '';
                if (preview) preview.textContent = '₹0';
                return;
            }

            const rawPrice = Math.round(val * mult * 100) / 100;
            rawInput.value = rawPrice;

            if (preview) {
                preview.textContent = formatCurrencyWithWords(rawPrice);
            }
        }

        window.urRecalculateCreatePrice = recalculate;

        input.addEventListener('input', recalculate);
        unitSelect.addEventListener('change', recalculate);

        // Pre-fill / Auto-detect if raw value is already present
        const initialRaw = parseFloat(rawInput.value);
        if (!isNaN(initialRaw) && initialRaw > 0) {
            const currentPurpose = document.querySelector('input[name="purpose"]:checked')?.value || 'rent';
            if (currentPurpose === 'buy' || currentPurpose === 'sell') {
                if (initialRaw >= 10000000) {
                    unitSelect.value = 'crore';
                    input.value = Math.round((initialRaw / 10000000) * 100) / 100;
                } else if (initialRaw >= 100000) {
                    unitSelect.value = 'lakh';
                    input.value = Math.round((initialRaw / 100000) * 100) / 100;
                } else if (initialRaw >= 1000) {
                    unitSelect.value = 'thousand';
                    input.value = Math.round((initialRaw / 1000) * 10) / 10;
                } else {
                    unitSelect.value = 'rupees';
                    input.value = initialRaw;
                }
            } else {
                if (initialRaw >= 100000 && initialRaw % 100000 === 0) {
                    unitSelect.value = 'lakh';
                    input.value = initialRaw / 100000;
                } else {
                    unitSelect.value = 'rupees';
                    input.value = initialRaw;
                }
            }
        } else if (input.value) {
            recalculate();
        }

        recalculate();

        input.closest('form')?.addEventListener('submit', function() {
            recalculate();
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCreatePriceCalculator);
    } else {
        initCreatePriceCalculator();
    }
})();

// Filter category dropdown options according to selected property type
window.filterPropertyCategories = function(selectId, selectedType, preferredValue = null) {
    const select = document.getElementById(selectId);
    if (!select) return;

    // Cache the master categories list once
    if (!select._masterCategories) {
        select._masterCategories = [];
        Array.from(select.options).forEach(opt => {
            if (opt.value) {
                select._masterCategories.push({
                    value: opt.value,
                    text: opt.text.trim(),
                    type: opt.getAttribute('data-type') || 'house'
                });
            }
        });
    }

    const currentVal = (preferredValue !== null && preferredValue !== undefined && preferredValue !== '') 
        ? String(preferredValue) 
        : String(select.value || '');

    // Filter matching categories for the chosen property type
    const matching = select._masterCategories.filter(cat => cat.type === selectedType);

    select.innerHTML = '';
    const defaultOpt = document.createElement('option');
    defaultOpt.value = '';
    defaultOpt.textContent = 'Select Category';
    select.appendChild(defaultOpt);

    let hasSelected = false;
    matching.forEach(cat => {
        const opt = document.createElement('option');
        opt.value = cat.value;
        opt.textContent = cat.text;
        opt.setAttribute('data-type', cat.type);
        if (String(cat.value) === currentVal) {
            opt.selected = true;
            hasSelected = true;
        }
        select.appendChild(opt);
    });

    // Auto-select primary category if current value is not in matching categories
    if (!hasSelected && matching.length > 0) {
        if (selectedType === 'pg-hostel') {
            const pgOpt = matching.find(c => c.text.toLowerCase().includes('pg')) || matching[0];
            select.value = pgOpt.value;
        } else if (selectedType === 'plot') {
            select.value = matching[0].value;
        } else if (selectedType === 'hotel') {
            select.value = matching[0].value;
        } else if (selectedType === 'shop') {
            const shopOpt = matching.find(c => c.text.toLowerCase().includes('retail') || c.text.toLowerCase().includes('shop')) || matching[0];
            select.value = shopOpt.value;
        } else if (selectedType === 'house') {
            const houseOpt = matching.find(c => c.text.toLowerCase().includes('residential')) || matching[0];
            select.value = houseOpt.value;
        }
    }
};

// Dynamic Property Type UI Handler (Adapts layout for PG/Hostel vs Land/Plot vs Residential/Commercial vs Hotel)
window.onTypeChange = function(type) {
    const isPg = (type === 'pg-hostel');
    const isPlot = (type === 'plot');
    const isHotel = (type === 'hotel');

    const bedCol = document.getElementById('create-bedrooms-col');
    const bathCol = document.getElementById('create-bathrooms-col');
    const areaCol = document.getElementById('create-area-col');
    const areaLabel = document.getElementById('create-area-label');
    const areaInput = document.getElementById('create-area');
    const furnWrap = document.getElementById('create-furnishing-container');
    const plotHelper = document.getElementById('create-plot-helper');
    const pgContainer = document.getElementById('create-pg-container');
    const titleInput = document.getElementById('create-title');
    const titleLabel = document.querySelector('label[for="create-title"]');
    const descInput = document.getElementById('create-description');
    const descLabel = document.querySelector('label[for="create-description"]');
    const priceLabel = document.getElementById('create-price-label');
    const priceInput = document.getElementById('create-price');
    const pricingTitle = document.getElementById('create-pricing-title');
    const pricingDesc = document.getElementById('create-pricing-desc');
    const purposeBuyLabel = document.querySelector('label[for="create-purpose-buy"]');
    const purposeRentRadio = document.getElementById('create-purpose-rent');
    const purposeRentLabel = document.querySelector('label[for="create-purpose-rent"]');
    const periodSelect = document.getElementById('create-period');
    const specsTitle = document.getElementById('create-details-title');
    const specsDesc = document.getElementById('create-details-desc');
    const specsIcon = document.getElementById('create-details-icon');

    // 1. Dynamically filter the Category dropdown to only show categories matching this property type
    window.filterPropertyCategories('create-category', type);

    if (isPg) {
        // 1. Force Purpose to "For Rent" (PGs cannot be sold outright)
        if (purposeRentRadio) {
            purposeRentRadio.checked = true;
            if (window.onPurposeChange) window.onPurposeChange('rent');
            if (purposeRentLabel) {
                purposeRentLabel.closest('.grid')?.querySelectorAll('label').forEach(l => l.classList.remove('border-blue-600','bg-blue-50/70','dark:bg-blue-950/40','text-blue-700','dark:text-blue-400','font-bold','ring-1','ring-blue-600','shadow-sm'));
                purposeRentLabel.classList.add('border-blue-600','bg-blue-50/70','dark:bg-blue-950/40','text-blue-700','dark:text-blue-400','font-bold','ring-1','ring-blue-600','shadow-sm');
            }
        }
        if (purposeBuyLabel) purposeBuyLabel.classList.add('hidden');

        // 2. Hide Flat specs & Plot helper, Show PG container
        if (bedCol) bedCol.classList.add('hidden');
        if (bathCol) bathCol.classList.add('hidden');
        if (areaCol) areaCol.classList.add('hidden');
        if (furnWrap) furnWrap.classList.add('hidden');
        if (plotHelper) plotHelper.classList.add('hidden');
        if (pgContainer) pgContainer.classList.remove('hidden');

        // Update section header
        if (specsTitle) specsTitle.textContent = 'PG & Hostel Specifications';
        if (specsDesc) specsDesc.textContent = 'Room sharing, gender rules, food facilities, and amenities';
        if (specsIcon) specsIcon.className = 'ph-bold ph-buildings';

        // 3. Pricing Section adaptation
        if (priceLabel) priceLabel.innerHTML = 'Monthly Rent per Bed / Room (₹) <span class="text-red-500">*</span>';
        if (priceInput) priceInput.placeholder = '6,500';
        if (pricingTitle) pricingTitle.textContent = 'PG Rent & Billing';
        if (pricingDesc) pricingDesc.textContent = 'Monthly rent per bed or private room';
        if (periodSelect) periodSelect.value = 'month';

        // 4. Title & Description placeholders & labels
        if (titleLabel) titleLabel.innerHTML = 'PG / Hostel Name & Headline <span class="text-red-500">*</span>';
        if (titleInput && (!titleInput.value || titleInput.value.includes('Flat') || titleInput.value.includes('Plot') || titleInput.value.includes('BHK'))) {
            titleInput.placeholder = 'e.g. Luxury Single & Double Sharing PG for Boys in Koramangala with Food, Wi-Fi & AC';
        }
        if (descLabel) descLabel.innerHTML = 'PG Details, Food Menu & Rules <span class="text-red-500">*</span>';
        if (descInput && (!descInput.value || descInput.value.includes('balcony') || descInput.value.includes('frontage'))) {
            descInput.placeholder = 'Describe room sharing options (Single/Double), meal menu (North/South Indian), cleanliness, Wi-Fi speed, gate closing timings, nearby colleges/IT parks, metro distance, and house rules...';
        }

        // Pre-select furnishing as fully furnished
        const furnSelect = document.getElementById('create-furnishing');
        if (furnSelect) furnSelect.value = 'fully-furnished';

    } else if (isPlot) {
        // Unhide buy label
        if (purposeBuyLabel) purposeBuyLabel.classList.remove('hidden');

        // Automatically default intent to "For Sale"
        const buyRadio = document.getElementById('create-purpose-buy');
        if (buyRadio && !buyRadio.checked) {
            buyRadio.checked = true;
            if (window.onPurposeChange) window.onPurposeChange('buy');
            const grid = buyRadio.closest('.grid');
            if (grid) {
                grid.querySelectorAll('label').forEach(l => l.classList.remove('border-blue-600','bg-blue-50/70','dark:bg-blue-950/40','text-blue-700','dark:text-blue-400','font-bold','ring-1','ring-blue-600','shadow-sm'));
                buyRadio.closest('label')?.classList.add('border-blue-600','bg-blue-50/70','dark:bg-blue-950/40','text-blue-700','dark:text-blue-400','font-bold','ring-1','ring-blue-600','shadow-sm');
            }
        }

        // Hide bedrooms & bathrooms & furnishing & pgContainer
        if (bedCol) bedCol.classList.add('hidden');
        if (bathCol) bathCol.classList.add('hidden');
        if (furnWrap) furnWrap.classList.add('hidden');
        if (pgContainer) pgContainer.classList.add('hidden');
        if (plotHelper) plotHelper.classList.remove('hidden');

        // Clear residential values
        const bedInput = document.getElementById('create-bedrooms');
        const bathInput = document.getElementById('create-bathrooms');
        if (bedInput) bedInput.value = '';
        if (bathInput) bathInput.value = '';

        // Expand area column to full width
        if (areaCol) {
            areaCol.className = 'col-span-3';
            areaCol.classList.remove('hidden');
        }
        if (areaLabel) areaLabel.textContent = 'Total Plot / Land Area (sq.ft) *';
        if (areaInput) areaInput.placeholder = 'e.g. 1500 (or 166 sq.yards)';

        if (specsTitle) specsTitle.textContent = 'Key Specifications';
        if (specsDesc) specsDesc.textContent = 'Total land area and plot details';
        if (specsIcon) specsIcon.className = 'ph-bold ph-map-trifold';

        if (priceLabel) priceLabel.innerHTML = 'Total Sale Price (₹) <span class="text-red-500">*</span>';
        if (priceInput) priceInput.placeholder = '50,00,000';
        if (pricingTitle) pricingTitle.textContent = 'Pricing & Financials';
        if (pricingDesc) pricingDesc.textContent = 'Set total outright sale price';

        if (titleLabel) titleLabel.innerHTML = 'Property Title <span class="text-red-500">*</span>';
        if (titleInput && (!titleInput.value || titleInput.value.includes('Flat') || titleInput.value.includes('BHK') || titleInput.value.includes('PG'))) {
            titleInput.placeholder = 'e.g. 1500 sq.ft Residential Corner Plot in Sector 57';
        }
        if (descLabel) descLabel.innerHTML = 'Description <span class="text-red-500">*</span>';
        if (descInput && (!descInput.value || descInput.value.includes('balcony') || descInput.value.includes('sharing'))) {
            descInput.placeholder = 'Describe plot dimensions, road frontage width (e.g. 30ft road), facing direction (North/East), zoning (Residential/Commercial), boundary wall, and clear title status...';
        }
    } else if (isHotel) {
        // Hotel Room adaptation
        if (purposeBuyLabel) purposeBuyLabel.classList.add('hidden');
        if (purposeRentRadio) {
            purposeRentRadio.checked = true;
            if (window.onPurposeChange) window.onPurposeChange('rent');
            if (purposeRentLabel) {
                purposeRentLabel.closest('.grid')?.querySelectorAll('label').forEach(l => l.classList.remove('border-blue-600','bg-blue-50/70','dark:bg-blue-950/40','text-blue-700','dark:text-blue-400','font-bold','ring-1','ring-blue-600','shadow-sm'));
                purposeRentLabel.classList.add('border-blue-600','bg-blue-50/70','dark:bg-blue-950/40','text-blue-700','dark:text-blue-400','font-bold','ring-1','ring-blue-600','shadow-sm');
            }
        }

        if (bedCol) bedCol.classList.remove('hidden');
        if (bathCol) bathCol.classList.remove('hidden');
        if (furnWrap) furnWrap.classList.remove('hidden');
        if (plotHelper) plotHelper.classList.add('hidden');
        if (pgContainer) pgContainer.classList.add('hidden');

        if (areaCol) {
            areaCol.className = 'col-span-1';
            areaCol.classList.remove('hidden');
        }
        if (areaLabel) areaLabel.textContent = 'Room Area (sq.ft)';
        if (areaInput) areaInput.placeholder = '350';

        if (specsTitle) specsTitle.textContent = 'Hotel Room Specifications';
        if (specsDesc) specsDesc.textContent = 'Beds, attached bathrooms, area, and room amenities';
        if (specsIcon) specsIcon.className = 'ph-bold ph-bed';

        if (priceLabel) priceLabel.innerHTML = 'Nightly / Monthly Tariff (₹) <span class="text-red-500">*</span>';
        if (priceInput) priceInput.placeholder = '2,500';
        if (pricingTitle) pricingTitle.textContent = 'Tariff & Pricing';
        if (pricingDesc) pricingDesc.textContent = 'Set room rate per night or month';

        if (titleLabel) titleLabel.innerHTML = 'Hotel / Room Title <span class="text-red-500">*</span>';
        if (titleInput && (!titleInput.value || titleInput.value.includes('Flat') || titleInput.value.includes('Plot') || titleInput.value.includes('PG'))) {
            titleInput.placeholder = 'e.g. Deluxe AC King Room with Complimentary Breakfast & Free Wi-Fi';
        }
        if (descLabel) descLabel.innerHTML = 'Hotel Room Details <span class="text-red-500">*</span>';
        if (descInput && (!descInput.value || descInput.value.includes('balcony') || descInput.value.includes('sharing') || descInput.value.includes('frontage'))) {
            descInput.placeholder = 'Describe room amenities, check-in/out timings, attached bathroom, daily housekeeping, parking, AC, TV, Wi-Fi...';
        }

        const furnSelect = document.getElementById('create-furnishing');
        if (furnSelect) furnSelect.value = 'fully-furnished';

    } else {
        // Standard Residential House / Commercial Shop
        if (purposeBuyLabel) purposeBuyLabel.classList.remove('hidden');

        if (bedCol) bedCol.classList.remove('hidden');
        if (bathCol) bathCol.classList.remove('hidden');
        if (furnWrap) furnWrap.classList.remove('hidden');
        if (plotHelper) plotHelper.classList.add('hidden');
        if (pgContainer) pgContainer.classList.add('hidden');

        if (areaCol) {
            areaCol.className = 'col-span-1';
            areaCol.classList.remove('hidden');
        }
        if (areaLabel) areaLabel.textContent = 'Area (sq.ft)';
        if (areaInput) areaInput.placeholder = '1250';

        if (specsTitle) specsTitle.textContent = 'Key Specifications';
        if (specsDesc) specsDesc.textContent = 'Bedrooms, bathrooms, area, and furnishing level';
        if (specsIcon) specsIcon.className = 'ph-bold ph-house-line';

        if (pricingTitle) pricingTitle.textContent = 'Pricing & Billing';
        if (pricingDesc) pricingDesc.textContent = 'Set your expected rent or sale price';

        const curPurpose = document.querySelector('input[name="purpose"]:checked')?.value || 'rent';
        if (window.onPurposeChange) window.onPurposeChange(curPurpose);

        if (titleLabel) titleLabel.innerHTML = 'Property Title <span class="text-red-500">*</span>';
        if (titleInput && (titleInput.value === '' || titleInput.placeholder.includes('Plot') || titleInput.placeholder.includes('PG') || titleInput.placeholder.includes('Hotel'))) {
            titleInput.placeholder = 'e.g. Spacious 3 BHK Semi-Furnished Flat in Sector 57';
        }
        if (descLabel) descLabel.innerHTML = 'Description <span class="text-red-500">*</span>';
        if (descInput && (descInput.value === '' || descInput.placeholder.includes('frontage') || descInput.placeholder.includes('sharing') || descInput.placeholder.includes('housekeeping'))) {
            descInput.placeholder = 'Describe key features, sunlight, balcony view, and tenant preferences...';
        }
    }
};

// Initialize on load
document.addEventListener('DOMContentLoaded', () => {
    const checkedPurpose = document.querySelector('input[name="purpose"]:checked');
    if (checkedPurpose) {
        window.onPurposeChange(checkedPurpose.value);
    }
    const checkedType = document.querySelector('input[name="type"]:checked');
    if (checkedType) {
        window.filterPropertyCategories('create-category', checkedType.value, '{{ old("category_id") }}');
        window.onTypeChange(checkedType.value);
    }
});

// Quick auto-optimize medium videos (25–60MB) by extracting first 30s clip at 8x speed (~4 seconds!)
async function autoCompressOversizedVideos() {
    const MAX_SAFE = 60 * 1024 * 1024; // 60MB
    const MIN_COMPRESS = 25 * 1024 * 1024; // 25MB
    const toCompress = uploadedVideoList.filter(v => !v.isUrl && v.file && !v.isCompressed && v.file.size > MIN_COMPRESS && v.file.size <= MAX_SAFE);
    if (toCompress.length === 0) return true;

    const statusWrapper = document.getElementById('video-compress-status');
    const statusText = document.getElementById('compress-status-text');
    const statusPct = document.getElementById('compress-status-pct');
    const progressBar = document.getElementById('compress-progress-bar');

    for (let i = 0; i < toCompress.length; i++) {
        const item = toCompress[i];
        const label = `(${i+1}/${toCompress.length}) "${item.name}"`;

        if (statusWrapper) statusWrapper.classList.remove('hidden');
        if (statusText) statusText.innerHTML = `⚡ Quick-optimizing ${label}...`;
        if (statusPct) statusPct.textContent = '0%';
        if (progressBar) progressBar.style.width = '0%';

        try {
            // Use fast slice (first 30s) instead of processing entire video
            const compressedBlob = await runVideoSliceAndCompress(item.file, 0, 30, (pct) => {
                if (statusPct) statusPct.textContent = `${Math.round(pct)}%`;
                if (progressBar) progressBar.style.width = `${pct}%`;
            });

            if (compressedBlob && compressedBlob.size < item.file.size) {
                const compressedFile = new File([compressedBlob], item.name.replace(/\.[^/.]+$/, '') + '_optimized.webm', {
                    type: 'video/webm'
                });
                if (item.previewUrl) URL.revokeObjectURL(item.previewUrl);
                item.file = compressedFile;
                item.size = compressedBlob.size;
                item.isCompressed = true;
                item.isTrimmed = true;
                item.previewUrl = URL.createObjectURL(compressedBlob);
            }
        } catch (err) {
            console.warn('Auto-compress skipped for', item.name, err);
        }
    }

    syncVideoFormInputs();
    if (statusWrapper) statusWrapper.classList.add('hidden');
    return true;
}

// Submission validation and instant loader
document.getElementById('create-property-form')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    const form = this;

    // 1. Validate that at least one photo or video is attached
    const hasPhotos = (typeof selectedFiles !== 'undefined' && selectedFiles.length > 0) || 
                      (document.getElementById('create-images-input')?.files?.length > 0);
    const hasVideos = (typeof uploadedVideoList !== 'undefined' && uploadedVideoList.length > 0);

    if (!hasPhotos && !hasVideos) {
        alert('Please upload at least one photo or video tour for your property listing.');
        document.getElementById('create-images')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        return;
    }

    // 2. Automatically clean up any raw uncompressed files > 60MB if trimmed clips exist
    const MAX_SAFE_SIZE = 60 * 1024 * 1024;
    const hasTrimmedClips = uploadedVideoList.some(v => v.isTrimmed || v.isCompressed);
    if (hasTrimmedClips) {
        uploadedVideoList = uploadedVideoList.filter(v => v.isUrl || !v.file || v.file.size <= MAX_SAFE_SIZE || v.isTrimmed || v.isCompressed);
        syncVideoFormInputs();
        renderVideoPreviews();
    }

    // 3. Check if any untrimmed raw file > 60MB still remains
    const tooBigFiles = (typeof uploadedVideoList !== 'undefined') ? uploadedVideoList.filter(v => !v.isUrl && v.file && v.file.size > MAX_SAFE_SIZE && !v.isTrimmed && !v.isCompressed) : [];
    if (tooBigFiles.length > 0) {
        const item = tooBigFiles[0];
        const sizeMb = (item.file.size / (1024 * 1024)).toFixed(0);
        alert(`"${item.name}" is ${sizeMb}MB — too large to upload directly.\n\nPlease use "Trim Clip" to cut a short highlight (15s–30s). It takes seconds and uploads instantly!`);
        openTrimmerModal(item.id);
        return;
    }

    const submitBtn = document.getElementById('create-submit');
    const submitIcon = document.getElementById('submit-icon');
    const submitLabel = document.getElementById('submit-label');

    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.classList.remove('btn-submitting');
        if (submitIcon) submitIcon.className = 'ph-bold ph-spinner animate-spin text-lg';
        if (submitLabel) submitLabel.textContent = '🚀 Publishing...';
    }

    // 4. Sync inputs & construct FormData for high-speed AJAX upload with live progress
    syncVideoFormInputs();
    const formData = new FormData(form);

    const xhr = new XMLHttpRequest();
    xhr.open('POST', form.action, true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.setRequestHeader('Accept', 'application/json');
    xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

    xhr.upload.onprogress = function(evt) {
        if (evt.lengthComputable && submitLabel) {
            const pct = Math.min(99, Math.round((evt.loaded / evt.total) * 100));
            submitLabel.textContent = `🚀 Uploading ${pct}%...`;
        }
    };

    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 400) {
            if (submitLabel) submitLabel.textContent = '✅ Published! Redirecting...';
            try {
                const res = JSON.parse(xhr.responseText);
                window.location.href = res.redirect_url || '{{ route("dashboard") }}';
            } catch (e) {
                window.location.href = '{{ route("dashboard") }}';
            }
        } else {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.classList.remove('btn-submitting');
            }
            if (submitIcon) submitIcon.className = 'ph-bold ph-paper-plane-tilt text-base';
            if (submitLabel) submitLabel.textContent = 'Publish Property Listing';
            try {
                const res = JSON.parse(xhr.responseText);
                const errors = res.errors ? Object.values(res.errors).flat().join('\n') : (res.message || 'An error occurred.');
                alert('Error publishing property:\n' + errors);
            } catch (e) {
                alert('An error occurred. Please try submitting again.');
            }
        }
    };

    xhr.onerror = function() {
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('btn-submitting');
        }
        if (submitIcon) submitIcon.className = 'ph-bold ph-paper-plane-tilt text-base';
        if (submitLabel) submitLabel.textContent = 'Publish Property Listing';
        alert('Network error. Please check your internet connection.');
    };

    xhr.send(formData);
});

// ========================================================
// 4-Step Wizard Navigation & Validation Logic
// ========================================================
window.currentStep = 1;
window.maxVisitedStep = 1;

const STEP_NAMES = {
    1: 'Basic Info',
    2: 'Pricing',
    3: 'Location',
    4: 'Photos'
};

function showStepError(input, message) {
    if (!input) return;
    
    // Highlight input
    input.classList.add('ring-2', 'ring-red-500', 'border-red-500');
    setTimeout(() => {
        input.classList.remove('ring-2', 'ring-red-500', 'border-red-500');
    }, 4000);

    // Scroll to input
    input.scrollIntoView({ behavior: 'smooth', block: 'center' });
    try { input.focus({ preventScroll: true }); } catch (e) {}

    // Show alert banner
    const alertBox = document.getElementById('step-validation-alert');
    const alertMsg = document.getElementById('step-validation-alert-msg');
    if (alertBox && alertMsg) {
        alertMsg.textContent = message;
        alertBox.classList.remove('hidden');
        setTimeout(() => {
            alertBox.classList.add('hidden');
        }, 6000);
    } else {
        alert(message);
    }
}

function validateStep(step) {
    if (step === 1) {
        const title = document.getElementById('create-title');
        if (!title || !title.value.trim()) {
            showStepError(title, 'Please enter a property title (e.g. 2 BHK Flat in Sector 57).');
            return false;
        }
        if (title.value.trim().length < 10) {
            showStepError(title, 'Property title must be at least 10 characters long.');
            return false;
        }

        const desc = document.getElementById('create-description');
        if (!desc || !desc.value.trim()) {
            showStepError(desc, 'Please enter a property description (minimum 20 characters).');
            return false;
        }
        if (desc.value.trim().length < 20) {
            showStepError(desc, 'Property description must be at least 20 characters long.');
            return false;
        }

        const cat = document.getElementById('create-category');
        if (!cat || !cat.value) {
            showStepError(cat, 'Please select a property category from the dropdown.');
            return false;
        }

        const phone = document.getElementById('create-phone');
        if (!phone || !phone.value.trim() || phone.value.trim().length < 8) {
            showStepError(phone, 'Please enter a valid contact phone number.');
            return false;
        }

        return true;
    }

    if (step === 2) {
        const price = document.getElementById('create-price');
        if (!price || !price.value || parseFloat(price.value) <= 0) {
            showStepError(price, 'Please enter a valid price/rent amount greater than 0.');
            return false;
        }
        return true;
    }

    if (step === 3) {
        const state = document.getElementById('create-state');
        if (!state || !state.value) {
            showStepError(state, 'Please select a state.');
            return false;
        }

        const city = document.getElementById('create-city');
        if (!city || !city.value) {
            showStepError(city, 'Please select a city/district.');
            return false;
        }

        const addr = document.getElementById('create-address');
        if (!addr || !addr.value.trim() || addr.value.trim().length < 4) {
            showStepError(addr, 'Please enter the complete property address.');
            return false;
        }
        return true;
    }

    return true;
}

window.goToStep = function(targetStep) {
    if (targetStep < 1 || targetStep > 4) return;

    // If moving forward, validate current step
    if (targetStep > window.currentStep) {
        for (let s = window.currentStep; s < targetStep; s++) {
            if (!validateStep(s)) {
                return;
            }
        }
    }

    window.currentStep = targetStep;
    if (targetStep > window.maxVisitedStep) {
        window.maxVisitedStep = targetStep;
    }

    // Toggle step panes
    for (let i = 1; i <= 4; i++) {
        const pane = document.getElementById(`step-pane-${i}`);
        if (pane) {
            if (i === targetStep) {
                pane.classList.remove('hidden');
            } else {
                pane.classList.add('hidden');
            }
        }
    }

    // Update stepper UI
    updateStepperUI(targetStep);

    // If step 4, refresh summary preview
    if (targetStep === 4) {
        updateStep4Summary();
    }

    // Scroll to top of stepper
    const stepperCard = document.getElementById('stepper-container');
    if (stepperCard) {
        stepperCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
};

function updateStepperUI(activeStep) {
    const numEl = document.getElementById('current-step-num');
    const nameEl = document.getElementById('current-step-name');
    const pctEl = document.getElementById('step-pct-text');
    const barEl = document.getElementById('step-progress-bar');

    if (numEl) numEl.textContent = activeStep;
    if (nameEl) nameEl.textContent = STEP_NAMES[activeStep] || '';
    if (pctEl) pctEl.textContent = `${activeStep * 25}% Completed`;
    if (barEl) barEl.style.width = `${activeStep * 25}%`;

    for (let i = 1; i <= 4; i++) {
        const circle = document.getElementById(`step-circle-${i}`);
        const label = document.getElementById(`step-label-${i}`);
        if (!circle || !label) continue;

        if (i === activeStep) {
            circle.className = 'w-7 h-7 sm:w-9 sm:h-9 rounded-lg sm:rounded-xl bg-blue-600 text-white font-extrabold text-xs sm:text-sm flex items-center justify-center shadow-md ring-2 sm:ring-4 ring-blue-600/25 transition-all';
            circle.innerHTML = `${i}`;
            label.className = 'text-[10px] sm:text-xs font-bold text-blue-600 dark:text-blue-400 transition-colors';
        } else if (i < activeStep) {
            circle.className = 'w-7 h-7 sm:w-9 sm:h-9 rounded-lg sm:rounded-xl bg-emerald-500 text-white font-extrabold text-xs sm:text-sm flex items-center justify-center shadow-xs hover:bg-emerald-600 transition-all';
            circle.innerHTML = '<i class="ph-bold ph-check text-xs sm:text-sm"></i>';
            label.className = 'text-[10px] sm:text-xs font-bold text-slate-700 dark:text-slate-300 transition-colors';
        } else {
            circle.className = 'w-7 h-7 sm:w-9 sm:h-9 rounded-lg sm:rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 font-extrabold text-xs sm:text-sm flex items-center justify-center transition-all';
            circle.innerHTML = `${i}`;
            label.className = 'text-[10px] sm:text-xs font-semibold text-slate-400 dark:text-slate-500 transition-colors';
        }
    }
}

function updateStep4Summary() {
    const title = document.getElementById('create-title')?.value || 'Untitled Property';
    const typeRadio = document.querySelector('input[name="type"]:checked')?.value || 'house';
    const purposeRadio = document.querySelector('input[name="purpose"]:checked')?.value || 'rent';
    const rawPrice = document.getElementById('create-price')?.value;
    const priceText = rawPrice ? ('₹' + Number(rawPrice).toLocaleString('en-IN')) : 'Not specified';
    const city = document.getElementById('create-city')?.value || '';
    const localitySelect = document.getElementById('create-locality-select')?.value;
    const localityText = document.getElementById('create-locality-text')?.value;
    const locality = localitySelect || localityText || '';
    const address = document.getElementById('create-address')?.value || '';

    const summaryEl = document.getElementById('step4-summary-details');
    if (summaryEl) {
        summaryEl.innerHTML = `
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 pt-2.5 text-[11px] sm:text-xs">
                <div class="bg-white/80 dark:bg-slate-900/60 p-2.5 rounded-xl border border-blue-100 dark:border-blue-900/40">
                    <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider mb-0.5">Property Headline</span>
                    <strong class="text-slate-900 dark:text-white font-bold block truncate" title="${title}">${title}</strong>
                    <span class="text-blue-600 dark:text-blue-400 text-[10px] font-semibold capitalize mt-0.5 block">${purposeRadio === 'rent' ? 'For Rent' : 'For Sale'} · ${typeRadio}</span>
                </div>
                <div class="bg-white/80 dark:bg-slate-900/60 p-2.5 rounded-xl border border-blue-100 dark:border-blue-900/40">
                    <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider mb-0.5">Price & Billing</span>
                    <strong class="text-emerald-600 dark:text-emerald-400 font-extrabold text-sm block">${priceText}</strong>
                    <span class="text-slate-400 text-[10px] block">${purposeRadio === 'rent' ? 'Monthly Expected Rent' : 'One-Time Purchase Price'}</span>
                </div>
                <div class="bg-white/80 dark:bg-slate-900/60 p-2.5 rounded-xl border border-blue-100 dark:border-blue-900/40 sm:col-span-1 col-span-1">
                    <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider mb-0.5">Location</span>
                    <strong class="text-slate-900 dark:text-white font-bold block truncate" title="${city}">${city}${locality ? ' (' + locality + ')' : ''}</strong>
                    <span class="text-slate-500 dark:text-slate-400 text-[10px] block truncate" title="${address}">${address || 'Address provided'}</span>
                </div>
            </div>
        `;
    }
}

// Admin Behalf Mode Helpers
function toggleAdminBehalfMode(mode) {
    const panelNew = document.getElementById('panel-behalf-new');
    const panelExisting = document.getElementById('panel-behalf-existing');
    const panelSelf = document.getElementById('panel-behalf-self');
    const tabNew = document.getElementById('tab-btn-new');
    const tabExisting = document.getElementById('tab-btn-existing');
    const tabSelf = document.getElementById('tab-btn-self');

    const tabs = [tabNew, tabExisting, tabSelf];
    tabs.forEach(t => {
        if (t) {
            t.className = 'behalf-tab px-3 py-1.5 rounded-lg text-xs font-bold transition-all block text-slate-600 dark:text-slate-300 hover:text-slate-900';
        }
    });

    if (panelNew) panelNew.classList.add('hidden');
    if (panelExisting) panelExisting.classList.add('hidden');
    if (panelSelf) panelSelf.classList.add('hidden');

    const activeTab = document.getElementById('tab-btn-' + mode);
    if (activeTab) {
        activeTab.className = 'behalf-tab px-3 py-1.5 rounded-lg text-xs font-bold transition-all block bg-orange-600 text-white shadow-2xs';
    }

    const activePanel = document.getElementById('panel-behalf-' + mode);
    if (activePanel) activePanel.classList.remove('hidden');
}

function syncBehalfPhoneToProperty(phoneVal) {
    const contactInput = document.getElementById('create-phone');
    if (contactInput && phoneVal) {
        contactInput.value = phoneVal;
    }
}

function onSelectExistingUserChange(selectElem) {
    const selectedOption = selectElem.options[selectElem.selectedIndex];
    const phone = selectedOption?.getAttribute('data-phone');
    if (phone) {
        syncBehalfPhoneToProperty(phone);
    }
}

// Alternate Phone Numbers Handler
(function() {
    const container = document.getElementById('alternate-phones-container');
    const btnTop = document.getElementById('btn-add-alternate-phone');
    const btnBottom = document.getElementById('btn-add-alternate-phone-bottom');

    function createAltPhoneRow(val = '') {
        const row = document.createElement('div');
        row.className = 'alternate-phone-row flex items-center gap-2 animate-fade-in';
        row.innerHTML = `
            <div class="relative flex-1">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-indigo-500 text-sm"><i class="ph-bold ph-phone-call"></i></span>
                <input type="tel" name="alternate_phones[]" value="${val ? val.replace(/"/g, '&quot;') : ''}" inputmode="tel" autocomplete="tel"
                       class="w-full pl-9 pr-14 py-2 sm:pl-10 sm:py-2.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white placeholder:text-slate-400 focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/15 transition-all"
                       placeholder="Alternate Mobile (e.g. +91 98765 43210)">
                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/60 px-1.5 py-0.5 rounded-md pointer-events-none">Alt</span>
            </div>
            <button type="button" class="btn-remove-alt-phone p-2 sm:p-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-400 hover:text-red-600 hover:border-red-200 dark:hover:border-red-800 hover:bg-red-50 dark:hover:bg-red-950/30 transition-all shrink-0 cursor-pointer" title="Remove this number">
                <i class="ph-bold ph-trash text-sm"></i>
            </button>
        `;
        return row;
    }

    function addAltPhone() {
        if (!container) return;
        const currentRows = container.querySelectorAll('.alternate-phone-row');
        if (currentRows.length >= 5) {
            alert('You can add up to 5 alternate contact numbers.');
            return;
        }
        const row = createAltPhoneRow();
        container.appendChild(row);
        const input = row.querySelector('input');
        if (input) input.focus();
    }

    if (btnTop) btnTop.addEventListener('click', addAltPhone);
    if (btnBottom) btnBottom.addEventListener('click', addAltPhone);

    if (container) {
        container.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-remove-alt-phone');
            if (btn) {
                const row = btn.closest('.alternate-phone-row');
                if (row) {
                    row.remove();
                }
            }
        });
    }
})();

// Initial step handler based on server validation errors
document.addEventListener('DOMContentLoaded', function() {
    @if($errors->hasAny(['images', 'images.*', 'video', 'videos', 'videos.*', 'uploaded_video_paths', 'video_url', 'video_urls']))
        goToStep(4);
    @elseif($errors->hasAny(['state', 'location', 'locality', 'address']))
        goToStep(3);
    @elseif($errors->hasAny(['price', 'price_period']))
        goToStep(2);
    @else
        goToStep(1);
    @endif
});

</script>

@endsection
