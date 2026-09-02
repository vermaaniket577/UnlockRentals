@extends('layouts.admin')

@section('title', 'Platform Settings & Configurations - Admin CRM')
@section('topbar_title', 'Settings & Content')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 pb-24" id="admin-settings">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Platform Settings & Content</h1>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                    <i class="ph-bold ph-sliders"></i> Configuration Hub
                </span>
            </div>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Manage API keys, payment gateways, contact details, AI chatbot behaviors, and frontend landing content.</p>
        </div>

        <div class="flex items-center gap-3">
            <button type="button" onclick="document.getElementById('settings-main-form').submit()"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white rounded-xl text-xs font-extrabold transition-all shadow-md shadow-blue-600/20">
                <i class="ph-bold ph-floppy-disk text-base"></i>
                <span>Save All Changes</span>
            </button>
        </div>
    </div>

    {{-- Quick Section Navigation Pills --}}
    <div class="flex items-center gap-2 overflow-x-auto pb-1 custom-scrollbar text-xs font-bold">
        <a href="#section-contact" class="px-4 py-2 bg-white hover:bg-blue-50 hover:text-blue-600 text-slate-700 rounded-xl border border-slate-200 shadow-2xs transition-all shrink-0 flex items-center gap-1.5">
            <i class="ph-bold ph-phone text-blue-600"></i> Contact & Footer
        </a>
        <a href="#section-social" class="px-4 py-2 bg-white hover:bg-blue-50 hover:text-blue-600 text-slate-700 rounded-xl border border-slate-200 shadow-2xs transition-all shrink-0 flex items-center gap-1.5">
            <i class="ph-bold ph-share-network text-indigo-600"></i> Social Links
        </a>
        <a href="#section-auth" class="px-4 py-2 bg-white hover:bg-blue-50 hover:text-blue-600 text-slate-700 rounded-xl border border-slate-200 shadow-2xs transition-all shrink-0 flex items-center gap-1.5">
            <i class="ph-bold ph-fingerprint text-emerald-600"></i> Social Login (OAuth)
        </a>
        <a href="#section-otp" class="px-4 py-2 bg-white hover:bg-emerald-50 hover:text-emerald-600 text-slate-700 rounded-xl border border-slate-200 shadow-2xs transition-all shrink-0 flex items-center gap-1.5">
            <i class="ph-bold ph-whatsapp-logo text-emerald-600"></i> WhatsApp & OTP
        </a>
        <a href="#section-mail" class="px-4 py-2 bg-white hover:bg-blue-50 hover:text-blue-600 text-slate-700 rounded-xl border border-slate-200 shadow-2xs transition-all shrink-0 flex items-center gap-1.5">
            <i class="ph-bold ph-envelope-simple text-purple-600"></i> SMTP Mail Server
        </a>
        <a href="#section-payments" class="px-4 py-2 bg-white hover:bg-blue-50 hover:text-blue-600 text-slate-700 rounded-xl border border-slate-200 shadow-2xs transition-all shrink-0 flex items-center gap-1.5">
            <i class="ph-bold ph-credit-card text-amber-600"></i> Payment Gateways & Tax
        </a>
        <a href="#section-features" class="px-4 py-2 bg-white hover:bg-blue-50 hover:text-blue-600 text-slate-700 rounded-xl border border-slate-200 shadow-2xs transition-all shrink-0 flex items-center gap-1.5">
            <i class="ph-bold ph-robot text-rose-600"></i> AI Bot & Features
        </a>
        <a href="#section-content" class="px-4 py-2 bg-white hover:bg-blue-50 hover:text-blue-600 text-slate-700 rounded-xl border border-slate-200 shadow-2xs transition-all shrink-0 flex items-center gap-1.5">
            <i class="ph-bold ph-layout text-cyan-600"></i> Landing Content
        </a>
    </div>

    <form id="settings-main-form" action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
        @csrf

        {{-- 1. Site Contact Information --}}
        <div id="section-contact" class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs">
            <div class="flex items-center gap-3 pb-5 mb-6 border-b border-slate-100">
                <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl flex-shrink-0 shadow-xs">
                    <i class="ph-bold ph-address-book"></i>
                </div>
                <div>
                    <h2 class="text-base font-extrabold text-slate-900">Site Contact Info & Footer</h2>
                    <p class="text-xs text-slate-400">Displayed in the website footer, support modals, and transaction emails.</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Support Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-envelope"></i></span>
                        <input type="email" name="site_email" value="{{ $settings['site_email'] ?? 'support@unlockrentals.com' }}"
                               class="w-full pl-10 pr-3.5 py-2.5 bg-slate-50 focus:bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Contact Phone</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-phone"></i></span>
                        <input type="text" name="site_phone" value="{{ $settings['site_phone'] ?? '+91 94254 55499' }}"
                               class="w-full pl-10 pr-3.5 py-2.5 bg-slate-50 focus:bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Support Agent Phone (Call Agent)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-headset"></i></span>
                        <input type="text" name="agent_phone" value="{{ $settings['agent_phone'] ?? '+91 94254 55499' }}"
                               class="w-full pl-10 pr-3.5 py-2.5 bg-slate-50 focus:bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Office Location</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-map-pin"></i></span>
                        <input type="text" name="site_address" value="{{ $settings['site_address'] ?? 'Mumbai, India' }}"
                               class="w-full pl-10 pr-3.5 py-2.5 bg-slate-50 focus:bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. Social Media Links --}}
        <div id="section-social" class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs">
            <div class="flex items-center gap-3 pb-5 mb-6 border-b border-slate-100">
                <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl flex-shrink-0 shadow-xs">
                    <i class="ph-bold ph-share-network"></i>
                </div>
                <div>
                    <h2 class="text-base font-extrabold text-slate-900">Social Media Links</h2>
                    <p class="text-xs text-slate-400">Links to your official business social profiles for footer and navigation icons.</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Facebook Page URL</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-blue-600"><i class="ph-bold ph-facebook-logo"></i></span>
                        <input type="url" name="social_facebook" value="{{ $settings['social_facebook'] ?? '' }}" placeholder="https://facebook.com/unlockrentals"
                               class="w-full pl-10 pr-3.5 py-2.5 bg-slate-50 focus:bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Instagram Profile URL</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-rose-500"><i class="ph-bold ph-instagram-logo"></i></span>
                        <input type="url" name="social_instagram" value="{{ $settings['social_instagram'] ?? '' }}" placeholder="https://instagram.com/unlockrentals"
                               class="w-full pl-10 pr-3.5 py-2.5 bg-slate-50 focus:bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Twitter / X URL</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-700"><i class="ph-bold ph-x-logo"></i></span>
                        <input type="url" name="social_twitter" value="{{ $settings['social_twitter'] ?? '' }}" placeholder="https://twitter.com/unlockrentals"
                               class="w-full pl-10 pr-3.5 py-2.5 bg-slate-50 focus:bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">LinkedIn Profile URL</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-blue-700"><i class="ph-bold ph-linkedin-logo"></i></span>
                        <input type="url" name="social_linkedin" value="{{ $settings['social_linkedin'] ?? '' }}" placeholder="https://linkedin.com/company/unlockrentals"
                               class="w-full pl-10 pr-3.5 py-2.5 bg-slate-50 focus:bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. Social Authentication (Google & Facebook OAuth) --}}
        <div id="section-auth" class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs">
            <div class="flex items-center gap-3 pb-5 mb-6 border-b border-slate-100">
                <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl flex-shrink-0 shadow-xs">
                    <i class="ph-bold ph-fingerprint"></i>
                </div>
                <div>
                    <h2 class="text-base font-extrabold text-slate-900">Social Login Credentials (Google & Facebook OAuth)</h2>
                    <p class="text-xs text-slate-400">Used for 1-click Google and Facebook user registration and sign-in on the website.</p>
                </div>
            </div>
            
            <div class="space-y-6">
                {{-- Google Auth --}}
                <div class="p-5 bg-slate-50/60 rounded-2xl border border-slate-200/80">
                    <div class="flex items-center gap-2 mb-4">
                        <svg width="18" height="18" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                        <h3 class="text-xs sm:text-sm font-bold text-slate-900">Google OAuth API</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Google Client ID</label>
                            <input type="text" name="google_client_id" value="{{ $settings['google_client_id'] ?? '' }}" placeholder="xxxxxx.apps.googleusercontent.com"
                                   class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 font-mono transition-all">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Google Client Secret</label>
                            <div class="relative">
                                <input type="password" name="google_client_secret" id="google_secret" value="{{ $settings['google_client_secret'] ?? '' }}" placeholder="••••••••••••••••"
                                       class="w-full pl-3.5 pr-10 py-2.5 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 font-mono transition-all">
                                <button type="button" onclick="togglePassword('google_secret', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-600 p-1">
                                    <i class="ph-bold ph-eye text-base eye-open"></i>
                                    <i class="ph-bold ph-eye-slash text-base eye-closed" style="display:none"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Facebook Auth --}}
                <div class="p-5 bg-slate-50/60 rounded-2xl border border-slate-200/80">
                    <div class="flex items-center gap-2 mb-4">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="#1877F2"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        <h3 class="text-xs sm:text-sm font-bold text-slate-900">Facebook App OAuth</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Facebook App ID</label>
                            <input type="text" name="facebook_client_id" value="{{ $settings['facebook_client_id'] ?? '' }}" placeholder="App ID Number"
                                   class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 font-mono transition-all">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Facebook App Secret</label>
                            <div class="relative">
                                <input type="password" name="facebook_client_secret" id="facebook_secret" value="{{ $settings['facebook_client_secret'] ?? '' }}" placeholder="••••••••••••••••"
                                       class="w-full pl-3.5 pr-10 py-2.5 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 font-mono transition-all">
                                <button type="button" onclick="togglePassword('facebook_secret', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-600 p-1">
                                    <i class="ph-bold ph-eye text-base eye-open"></i>
                                    <i class="ph-bold ph-eye-slash text-base eye-closed" style="display:none"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. Mobile Number OTP & WhatsApp / SMS Verification --}}
        <div id="section-otp" class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs">
            <div class="flex items-center justify-between pb-5 mb-6 border-b border-slate-100 flex-wrap gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl flex-shrink-0 shadow-xs">
                        <i class="ph-bold ph-whatsapp-logo"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-extrabold text-slate-900">Mobile OTP & WhatsApp Verification</h2>
                        <p class="text-xs text-slate-400">Configure delivery channels for phone verification during registration and phone-based OTP login.</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    @php
                        $currentChannel = $settings['otp_channel'] ?? config('otp.channel', 'log');
                    @endphp
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $currentChannel === 'whatsapp' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($currentChannel === 'sms' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-amber-50 text-amber-700 border border-amber-200') }}">
                        <span class="w-2 h-2 rounded-full {{ $currentChannel === 'whatsapp' ? 'bg-emerald-500' : ($currentChannel === 'sms' ? 'bg-blue-500' : 'bg-amber-500 animate-pulse') }}"></span>
                        Active: {{ strtoupper($currentChannel) }}
                    </span>
                </div>
            </div>

            <div class="space-y-6">
                {{-- Channel Selection & Global OTP Rules --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 p-5 bg-slate-50/60 rounded-2xl border border-slate-200/80">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            OTP Delivery Channel <span class="text-rose-500">*</span>
                        </label>
                        <select name="otp_channel" id="otp_channel_select"
                                class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-bold text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                            <option value="notification" {{ ($settings['otp_channel'] ?? config('otp.channel', 'log')) === 'notification' ? 'selected' : '' }}>
                                🔔 Push Notification (Web & Mobile Push)
                            </option>
                            <option value="whatsapp" {{ ($settings['otp_channel'] ?? config('otp.channel', 'log')) === 'whatsapp' ? 'selected' : '' }}>
                                🟢 WhatsApp Cloud API (Meta)
                            </option>
                            <option value="sms" {{ ($settings['otp_channel'] ?? config('otp.channel', 'log')) === 'sms' ? 'selected' : '' }}>
                                🔵 SMS Gateway (2Factor / MSG91)
                            </option>
                            <option value="log" {{ ($settings['otp_channel'] ?? config('otp.channel', 'log')) === 'log' ? 'selected' : '' }}>
                                🟡 Local Log / Development (Free Test Mode)
                            </option>
                        </select>
                        <p class="text-[11px] text-slate-400 mt-1">Select where OTPs get sent when requested by users.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">OTP Validity (Minutes)</label>
                        <input type="number" name="otp_expiry_minutes" min="1" max="60"
                               value="{{ $settings['otp_expiry_minutes'] ?? config('otp.expiry_minutes', 10) }}"
                               class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                        <p class="text-[11px] text-slate-400 mt-1">Default is 10 minutes.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Resend Cooldown (Seconds)</label>
                        <input type="number" name="otp_resend_seconds" min="15" max="300"
                               value="{{ $settings['otp_resend_seconds'] ?? config('otp.resend_seconds', 60) }}"
                               class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                        <p class="text-[11px] text-slate-400 mt-1">Countdown timer before user can click resend.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Max Failed Attempts</label>
                        <input type="number" name="otp_max_attempts" min="1" max="10"
                               value="{{ $settings['otp_max_attempts'] ?? config('otp.max_attempts', 3) }}"
                               class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                        <p class="text-[11px] text-slate-400 mt-1">Locks OTP after N invalid tries.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Max OTPs / Hour</label>
                        <input type="number" name="otp_max_per_hour" min="5" max="100"
                               value="{{ $settings['otp_max_per_hour'] ?? config('otp.max_per_hour', 15) }}"
                               class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                        <p class="text-[11px] text-slate-400 mt-1">Hourly rate limit per phone number.</p>
                    </div>
                </div>

                {{-- WhatsApp Cloud API Settings Card --}}
                <div class="p-5 bg-emerald-50/40 rounded-2xl border border-emerald-200/80 space-y-4">
                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <div class="flex items-center gap-2">
                            <i class="ph-bold ph-whatsapp-logo text-xl text-emerald-600"></i>
                            <h3 class="text-xs sm:text-sm font-bold text-slate-900">Meta WhatsApp Cloud API Configuration</h3>
                        </div>
                        <a href="https://developers.facebook.com/apps/" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 hover:text-emerald-800 hover:underline">
                            <span>Open Meta Developer Console</span>
                            <i class="ph-bold ph-arrow-square-out"></i>
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="lg:col-span-2">
                            <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">
                                WhatsApp Access Token (Permanent / System User Token)
                            </label>
                            <div class="relative">
                                <input type="password" name="whatsapp_token" id="whatsapp_token_input"
                                       value="{{ $settings['whatsapp_token'] ?? config('otp.whatsapp.token') }}"
                                       placeholder="EAAxxxxxxxxxxxxxxxxxxxx..."
                                       class="w-full pl-3.5 pr-10 py-2.5 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:border-emerald-600 focus:ring-4 focus:ring-emerald-600/10 font-mono transition-all">
                                <button type="button" onclick="togglePassword('whatsapp_token_input', this)"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-emerald-600 p-1">
                                    <i class="ph-bold ph-eye text-base eye-open"></i>
                                    <i class="ph-bold ph-eye-slash text-base eye-closed" style="display:none"></i>
                                </button>
                            </div>
                            <p class="text-[11px] text-slate-400 mt-1">Permanent access token from Meta Business Manager > System Users.</p>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">
                                Phone Number ID
                            </label>
                            <input type="text" name="whatsapp_phone_number_id"
                                   value="{{ $settings['whatsapp_phone_number_id'] ?? config('otp.whatsapp.phone_number_id') }}"
                                   placeholder="e.g. 109823471829384"
                                   class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:border-emerald-600 focus:ring-4 focus:ring-emerald-600/10 font-mono transition-all">
                            <p class="text-[11px] text-slate-400 mt-1">Found under WhatsApp > API Setup in Meta Console.</p>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">
                                Message Template Name
                            </label>
                            <input type="text" name="whatsapp_otp_template_name"
                                   value="{{ $settings['whatsapp_otp_template_name'] ?? config('otp.whatsapp.template_name', 'otp_verification') }}"
                                   placeholder="otp_verification"
                                   class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:border-emerald-600 focus:ring-4 focus:ring-emerald-600/10 font-mono transition-all">
                            <p class="text-[11px] text-slate-400 mt-1">Approved WhatsApp template name containing 1 body parameter (code).</p>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">
                                WhatsApp Business Account ID (WABA ID)
                            </label>
                            <input type="text" name="whatsapp_business_account_id"
                                   value="{{ $settings['whatsapp_business_account_id'] ?? '' }}"
                                   placeholder="e.g. 108273948572019"
                                   class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:border-emerald-600 focus:ring-4 focus:ring-emerald-600/10 font-mono transition-all">
                            <p class="text-[11px] text-slate-400 mt-1">Your WABA Account ID (optional reference).</p>
                        </div>
                    </div>
                </div>

                {{-- SMS Gateway Fallback Settings Card --}}
                <div class="p-5 bg-blue-50/40 rounded-2xl border border-blue-200/80 space-y-4">
                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <div class="flex items-center gap-2">
                            <i class="ph-bold ph-chat-circle-text text-xl text-blue-600"></i>
                            <h3 class="text-xs sm:text-sm font-bold text-slate-900">SMS Gateway Configuration (Alternative / Fallback)</h3>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">SMS Provider</label>
                            <select name="sms_provider"
                                    class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                                <option value="2factor" {{ ($settings['sms_provider'] ?? config('otp.sms.provider', '2factor')) === '2factor' ? 'selected' : '' }}>2Factor.in (India SMS)</option>
                                <option value="msg91" {{ ($settings['sms_provider'] ?? config('otp.sms.provider', '2factor')) === 'msg91' ? 'selected' : '' }}>MSG91 (Global / India)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">SMS API Key</label>
                            <div class="relative">
                                <input type="password" name="sms_api_key" id="sms_api_key_input"
                                       value="{{ $settings['sms_api_key'] ?? config('otp.sms.api_key') }}"
                                       placeholder="Enter your SMS API Key"
                                       class="w-full pl-3.5 pr-10 py-2.5 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 font-mono transition-all">
                                <button type="button" onclick="togglePassword('sms_api_key_input', this)"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-600 p-1">
                                    <i class="ph-bold ph-eye text-base eye-open"></i>
                                    <i class="ph-bold ph-eye-slash text-base eye-closed" style="display:none"></i>
                                </button>
                            </div>
                        </div>
                {{-- Push Notification & FCM Settings Card --}}
                <div class="p-5 bg-purple-50/40 rounded-2xl border border-purple-200/80 space-y-4">
                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <div class="flex items-center gap-2">
                            <i class="ph-bold ph-bell-ringing text-xl text-purple-600"></i>
                            <h3 class="text-xs sm:text-sm font-bold text-slate-900">Push Notification & FCM Configuration (Mobile & Web Push)</h3>
                        </div>
                        <a href="https://console.firebase.google.com/" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-1 text-[11px] font-bold text-purple-700 hover:text-purple-800 hover:underline">
                            <span>Open Firebase Console</span>
                            <i class="ph-bold ph-arrow-square-out"></i>
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Firebase Server Key (FCM Legacy / Cloud Messaging)</label>
                            <div class="relative">
                                <input type="password" name="fcm_server_key" id="fcm_server_key_input"
                                       value="{{ $settings['fcm_server_key'] ?? config('otp.fcm.server_key') }}"
                                       placeholder="AAAAxxxxxxxx:APA91b..."
                                       class="w-full pl-3.5 pr-10 py-2.5 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-600/10 font-mono transition-all">
                                <button type="button" onclick="togglePassword('fcm_server_key_input', this)"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-purple-600 p-1">
                                    <i class="ph-bold ph-eye text-base eye-open"></i>
                                    <i class="ph-bold ph-eye-slash text-base eye-closed" style="display:none"></i>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Firebase Project ID</label>
                            <input type="text" name="fcm_project_id"
                                   value="{{ $settings['fcm_project_id'] ?? config('otp.fcm.project_id') }}"
                                   placeholder="e.g. unlockrentals-app"
                                   class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-600/10 font-mono transition-all">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 5. SMTP Mail Server Configuration --}}
        <div id="section-mail" class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs">
            <div class="flex items-center gap-3 pb-5 mb-6 border-b border-slate-100">
                <div class="w-10 h-10 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl flex-shrink-0 shadow-xs">
                    <i class="ph-bold ph-envelope-simple"></i>
                </div>
                <div>
                    <h2 class="text-base font-extrabold text-slate-900">SMTP Email Server (Dynamic Delivery)</h2>
                    <p class="text-xs text-slate-400">Used for transactional emails, booking notifications, and password reset links.</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">SMTP Host</label>
                    <input type="text" name="mail_host" value="{{ $settings['mail_host'] ?? '' }}" placeholder="smtp.gmail.com"
                           class="w-full px-3.5 py-2.5 bg-slate-50 focus:bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all font-mono">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">SMTP Port</label>
                    <input type="text" name="mail_port" value="{{ $settings['mail_port'] ?? '587' }}" placeholder="587 or 465"
                           class="w-full px-3.5 py-2.5 bg-slate-50 focus:bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all font-mono">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Encryption Protocol</label>
                    <select name="mail_encryption" class="w-full px-3.5 py-2.5 bg-slate-50 focus:bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                        <option value="tls" {{ ($settings['mail_encryption'] ?? '') == 'tls' ? 'selected' : '' }}>TLS (Port 587 - Recommended)</option>
                        <option value="ssl" {{ ($settings['mail_encryption'] ?? '') == 'ssl' ? 'selected' : '' }}>SSL (Port 465)</option>
                        <option value="none" {{ ($settings['mail_encryption'] ?? '') == 'none' ? 'selected' : '' }}>None</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">SMTP Username / Email</label>
                    <input type="text" name="mail_username" value="{{ $settings['mail_username'] ?? '' }}" placeholder="your-email@gmail.com"
                           class="w-full px-3.5 py-2.5 bg-slate-50 focus:bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">SMTP Password / App Password</label>
                    <div class="relative">
                        <input type="password" name="mail_password" id="mail_password" value="{{ $settings['mail_password'] ?? '' }}" placeholder="••••••••••••••••"
                               class="w-full pl-3.5 pr-10 py-2.5 bg-slate-50 focus:bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all font-mono">
                        <button type="button" onclick="togglePassword('mail_password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-600 p-1">
                            <i class="ph-bold ph-eye text-base eye-open"></i>
                            <i class="ph-bold ph-eye-slash text-base eye-closed" style="display:none"></i>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">From Sender Email</label>
                    <input type="email" name="mail_from_address" value="{{ $settings['mail_from_address'] ?? 'noreply@unlockrentals.com' }}" placeholder="noreply@unlockrentals.com"
                           class="w-full px-3.5 py-2.5 bg-slate-50 focus:bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                </div>
            </div>
        </div>

        {{-- 5. Payment Gateways & Tax Settings --}}
        <div id="section-payments" class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs">
            <div class="flex items-center gap-3 pb-5 mb-6 border-b border-slate-100">
                <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl flex-shrink-0 shadow-xs">
                    <i class="ph-bold ph-credit-card"></i>
                </div>
                <div>
                    <h2 class="text-base font-extrabold text-slate-900">Tax & Payment Gateways</h2>
                    <p class="text-xs text-slate-400">Configure dynamic GST taxation rates and automated/manual checkout gateways.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-8">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">GST Rate Percentage (%)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 font-bold">%</span>
                        <input type="number" name="gst_rate" min="0" max="100" step="0.01" value="{{ $settings['gst_rate'] ?? '18' }}"
                               class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 focus:bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1">Calculated automatically on invoice breakdown during plan checkout (Default is 18%).</p>
                </div>
            </div>

            <div class="border-t border-slate-100 pt-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-5">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Active Payment Gateways</h3>
                        <p class="text-xs text-slate-400">Manage Razorpay, UPI QR codes, or bank transfer gateways.</p>
                    </div>
                    <button type="button" onclick="window.addPaymentGateway()" id="btn-add-gateway"
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white rounded-xl text-xs font-bold transition-all shadow-xs self-start sm:self-auto cursor-pointer">
                        <i class="ph-bold ph-plus"></i>
                        <span>Add Gateway</span>
                    </button>
                </div>

                <div id="payment-gateways" class="space-y-4">
                    @foreach($paymentGateways as $index => $gateway)
                        @include('admin.partials.payment-gateway-fields', ['gateway' => $gateway, 'index' => $index, 'activePaymentGatewayId' => $activePaymentGatewayId])
                    @endforeach
                </div>
            </div>
        </div>

        {{-- 6. Features & AI Chatbot --}}
        <div id="section-features" class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs">
            <div class="flex items-center gap-3 pb-5 mb-6 border-b border-slate-100">
                <div class="w-10 h-10 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl flex-shrink-0 shadow-xs">
                    <i class="ph-bold ph-robot"></i>
                </div>
                <div>
                    <h2 class="text-base font-extrabold text-slate-900">Platform Features & AI Chatbot</h2>
                    <p class="text-xs text-slate-400">Control customer feedback, automatic property bypass approvals, and chatbot messaging rules.</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="space-y-4">
                    {{-- Toggle 1 --}}
                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-200/80">
                        <div>
                            <h4 class="text-xs sm:text-sm font-bold text-slate-900">AI Assistant Chatbot</h4>
                            <p class="text-xs text-slate-400">Show floating interactive support bot on website</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="chatbot_enabled" value="1" {{ ($settings['chatbot_enabled'] ?? '1') == '1' ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>

                    {{-- Toggle 2 --}}
                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-200/80">
                        <div>
                            <h4 class="text-xs sm:text-sm font-bold text-slate-900">Customer Feedback Reviews</h4>
                            <p class="text-xs text-slate-400">Enable 5-star rating collection widget on frontend</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="feedback_enabled" value="1" {{ ($settings['feedback_enabled'] ?? '1') == '1' ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>

                    {{-- Toggle 3 --}}
                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-200/80">
                        <div>
                            <h4 class="text-xs sm:text-sm font-bold text-slate-900">Direct Approval Bypass</h4>
                            <p class="text-xs text-slate-400">Auto-approve new listings without manual admin review</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="bypass_property_approval" value="1" {{ ($settings['bypass_property_approval'] ?? '0') == '1' ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Bot Greeting / Welcome Message</label>
                    <textarea name="bot_welcome_message" rows="5" class="w-full p-3.5 bg-slate-50 focus:bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all" placeholder="Hi there! 👋 Welcome to UnlockRentals. How can I assist you with your property search today?">{{ $settings['bot_welcome_message'] ?? 'Hi there! 👋 Welcome to UnlockRentals. How can I assist you with your property search today?' }}</textarea>
                </div>

                <div class="lg:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Bot Intelligent Auto-Responses (One response per line)</label>
                    <textarea name="bot_auto_responses" rows="4" class="w-full p-3.5 bg-slate-50 focus:bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all font-mono leading-relaxed" placeholder="That's a great question! Let me check our verified listings for you.">{{ $settings['bot_auto_responses'] ?? "That's a great question! Let me check our premium listings for you.\nI can certainly help you with that. Would you like to see properties in a specific city?\nOne of our agents will be happy to assist you further. Shall I book a callback for you?\nUnlockRentals offers 100% verified properties with zero brokerage across India." }}</textarea>
                </div>
            </div>
        </div>

        {{-- 7. Frontend Landing Content & Mobile App Links --}}
        <div id="section-content" class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs">
            <div class="flex items-center gap-3 pb-5 mb-6 border-b border-slate-100">
                <div class="w-10 h-10 rounded-2xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-xl flex-shrink-0 shadow-xs">
                    <i class="ph-bold ph-layout"></i>
                </div>
                <div>
                    <h2 class="text-base font-extrabold text-slate-900">Hero Section & App Distribution Links</h2>
                    <p class="text-xs text-slate-400">Headlines, call-to-actions, and official mobile app download links.</p>
                </div>
            </div>

            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Hero Title (Prefix)</label>
                        <input type="text" name="hero_title_1" value="{{ $settings['hero_title_1'] ?? 'Find Your' }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 focus:bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Hero Title (Highlighted Word)</label>
                        <input type="text" name="hero_title_2" value="{{ $settings['hero_title_2'] ?? 'Perfect Rental' }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 focus:bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Hero Sub-Description</label>
                    <textarea name="hero_description" rows="2" class="w-full p-3.5 bg-slate-50 focus:bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">{{ $settings['hero_description'] ?? 'Discover thousands of verified houses, flats, PGs & commercial spaces across India. Connect directly with owners with zero brokerage.' }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 border-t border-slate-100 pt-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Google Play Store URL</label>
                        <input type="url" name="app_google_play_url" value="{{ $settings['app_google_play_url'] ?? '#' }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 focus:bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Apple App Store URL</label>
                        <input type="url" name="app_store_url" value="{{ $settings['app_store_url'] ?? '#' }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 focus:bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Indus Appstore URL</label>
                        <input type="url" name="app_indus_appstore_url" value="{{ $settings['app_indus_appstore_url'] ?? 'https://www.indusappstore.com' }}" placeholder="https://www.indusappstore.com/app/..."
                               class="w-full px-3.5 py-2.5 bg-slate-50 focus:bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Direct APK Download URL</label>
                        <input type="url" name="app_apk_download_url" value="{{ $settings['app_apk_download_url'] ?? '' }}" placeholder="https://unlockrentals.com/downloads/UnlockRentals.apk"
                               class="w-full px-3.5 py-2.5 bg-slate-50 focus:bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                    </div>
                </div>
            </div>
        </div>

        {{-- Sticky Bottom Floating Action Bar --}}
        <div class="fixed bottom-0 left-64 right-0 bg-white/95 backdrop-blur-md border-t border-slate-200/90 py-3.5 px-8 flex items-center justify-between z-30 shadow-lg">
            <div class="text-xs font-semibold text-slate-600 hidden sm:block">
                <span>Save all configuration changes to your database.</span>
            </div>
            <div class="flex items-center gap-3 ml-auto">
                <a href="{{ route('admin.dashboard') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold transition-all">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white rounded-xl text-xs font-extrabold transition-all shadow-md shadow-blue-600/20 flex items-center gap-1.5">
                    <i class="ph-bold ph-floppy-disk text-base"></i>
                    <span>Save Settings</span>
                </button>
            </div>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
window.togglePassword = function(inputId, btn) {
    const input = document.getElementById(inputId);
    if (!input) return;
    const eyeOpen = btn.querySelector('.eye-open');
    const eyeClosed = btn.querySelector('.eye-closed');
    if (input.type === 'password') {
        input.type = 'text';
        if (eyeOpen) eyeOpen.style.display = 'none';
        if (eyeClosed) eyeClosed.style.display = 'block';
    } else {
        input.type = 'password';
        if (eyeOpen) eyeOpen.style.display = 'block';
        if (eyeClosed) eyeClosed.style.display = 'none';
    }
};

window.gatewayField = function(index, label, key, type, placeholder) {
    return `
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">${label}</label>
            <input type="${type}" name="payment_gateways[${index}][${key}]" placeholder="${placeholder}" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
        </div>
    `;
};

window.addPaymentGateway = function() {
    const container = document.getElementById('payment-gateways');
    if (!container) return;
    const index = Date.now();
    const id = `gateway-${index}`;

    container.insertAdjacentHTML('beforeend', `
        <div class="payment-gateway-item bg-slate-50/80 border border-slate-200/90 rounded-2xl p-5 sm:p-6 transition-all hover:border-slate-300 shadow-xs" data-index="${index}">
            <input type="hidden" name="payment_gateways[${index}][id]" value="${id}">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-5 pb-4 border-b border-slate-200/70">
                <div class="flex flex-wrap items-center gap-4">
                    <label class="inline-flex items-center gap-2 text-xs font-bold text-slate-800 cursor-pointer bg-white px-3 py-1.5 rounded-xl border border-slate-200 shadow-2xs hover:border-blue-300 transition-colors">
                        <input type="radio" name="active_payment_gateway_id" value="${id}" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                        <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Set as Primary Gateway</span>
                    </label>
                    <label class="inline-flex items-center gap-2 text-xs font-bold text-slate-700 cursor-pointer bg-white px-3 py-1.5 rounded-xl border border-slate-200 shadow-2xs hover:border-slate-300 transition-colors">
                        <input type="checkbox" name="payment_gateways[${index}][enabled]" value="1" checked class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                        <span>Enabled</span>
                    </label>
                </div>
                <button type="button" onclick="window.removePaymentGateway(this)" class="inline-flex items-center gap-1.5 text-xs font-bold text-rose-600 hover:text-white bg-rose-50 hover:bg-rose-600 border border-rose-200 hover:border-rose-600 px-3 py-1.5 rounded-xl transition-all shadow-xs">
                    <i class="ph-bold ph-trash"></i>
                    <span>Remove</span>
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
                ${window.gatewayField(index, 'Gateway Display Name', 'name', 'text', 'Razorpay / UPI / Bank Transfer')}
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Gateway Processing Type</label>
                    <select name="payment_gateways[${index}][type]" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                        <option value="razorpay">Razorpay Automated</option>
                        <option value="manual" selected>Manual Verification</option>
                        <option value="external">External Payment Link</option>
                    </select>
                </div>
                ${window.gatewayField(index, 'Account / Merchant Name', 'account_name', 'text', 'UnlockRentals Pvt Ltd')}
                ${window.gatewayField(index, 'Gateway Identifier (UPI / Acc No)', 'identifier', 'text', 'unlockrentals@upi')}
                ${window.gatewayField(index, 'Payment Link (Optional)', 'payment_link', 'url', 'https://...')}
                ${window.gatewayField(index, 'QR Image URL (Optional)', 'qr_url', 'url', 'https://example.com/payment-qr.png')}
                ${window.gatewayField(index, 'Reference Field Prompt', 'reference_label', 'text', 'Transaction ID / UTR Number')}
                ${window.gatewayField(index, 'Razorpay Key ID', 'key_id', 'text', 'rzp_test_...')}
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Razorpay Key Secret</label>
                    <input type="password" name="payment_gateways[${index}][key_secret]" placeholder="Only needed for Razorpay" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Checkout Instructions</label>
                    <textarea name="payment_gateways[${index}][instructions]" rows="2" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all" placeholder="Explain to users how to complete the payment and where to find the reference ID."></textarea>
                </div>
            </div>
        </div>
    `);
};

window.removePaymentGateway = function(button) {
    const item = button.closest('.payment-gateway-item');
    if (item) {
        item.remove();
    }
};
</script>
@endpush
