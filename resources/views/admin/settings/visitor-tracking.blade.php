@extends('layouts.admin')

@section('title', 'CRM & WhatsApp Settings - UnlockRentals')
@section('topbar_title', 'CRM & WhatsApp Gateway Settings')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">

    {{-- Header --}}
    <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs">
        <div class="flex items-center gap-2.5">
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">CRM & WhatsApp Gateway Configuration</h1>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                Integration
            </span>
        </div>
        <p class="text-sm text-slate-500 mt-1">Configure Meta Cloud API or Twilio credentials for automatic lead alerts and manage data retention policies.</p>
    </div>

    <form action="{{ route('admin.crm-settings.update') }}" method="POST" class="space-y-8">
        @csrf

        {{-- Section 1: WhatsApp Gateway Settings --}}
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-6">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                        <i class="ph-bold ph-whatsapp-logo"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">WhatsApp Gateway Provider</h3>
                        <p class="text-xs text-slate-500">Choose between Meta Cloud API (Official), Twilio, or offline local logging</p>
                    </div>
                </div>
            </div>

            {{-- Provider Selection --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2">Active WhatsApp Driver</label>
                @php $activeProvider = $settings['whatsapp_provider'] ?? 'log'; @endphp
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <label class="p-4 rounded-2xl border-2 cursor-pointer flex flex-col justify-between transition-all {{ $activeProvider === 'meta' ? 'border-emerald-600 bg-emerald-50/20' : 'border-slate-200 hover:border-slate-300' }}">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-900">Meta Cloud API</span>
                            <input type="radio" name="whatsapp_provider" value="meta" {{ $activeProvider === 'meta' ? 'checked' : '' }} class="text-emerald-600 focus:ring-emerald-500">
                        </div>
                        <p class="text-[11px] text-slate-500 mt-2">Official Meta Graph API v18.0. Lowest cost per conversation.</p>
                    </label>

                    <label class="p-4 rounded-2xl border-2 cursor-pointer flex flex-col justify-between transition-all {{ $activeProvider === 'twilio' ? 'border-blue-600 bg-blue-50/20' : 'border-slate-200 hover:border-slate-300' }}">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-900">Twilio Messaging</span>
                            <input type="radio" name="whatsapp_provider" value="twilio" {{ $activeProvider === 'twilio' ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500">
                        </div>
                        <p class="text-[11px] text-slate-500 mt-2">Twilio WhatsApp Sandbox & Production API gateway.</p>
                    </label>

                    <label class="p-4 rounded-2xl border-2 cursor-pointer flex flex-col justify-between transition-all {{ $activeProvider === 'log' ? 'border-slate-600 bg-slate-50' : 'border-slate-200 hover:border-slate-300' }}">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-900">Local Log (Dev)</span>
                            <input type="radio" name="whatsapp_provider" value="log" {{ $activeProvider === 'log' ? 'checked' : '' }} class="text-slate-600 focus:ring-slate-500">
                        </div>
                        <p class="text-[11px] text-slate-500 mt-2">Records messages safely in database & log files without sending.</p>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">WhatsApp Business Phone Number</label>
                <input type="text" name="whatsapp_business_phone" value="{{ $settings['whatsapp_business_phone'] ?? '919876543210' }}" class="w-full sm:w-80 px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 focus:outline-none font-mono" placeholder="919876543210">
                <p class="text-[11px] text-slate-400 mt-1">Include country code (e.g. 91 for India) without plus or spaces.</p>
            </div>

            {{-- Meta Cloud API Credentials --}}
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-4">
                <h4 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Meta Cloud API Credentials</h4>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Phone Number ID</label>
                        <input type="text" name="whatsapp_meta_phone_id" value="{{ $settings['whatsapp_meta_phone_id'] ?? '' }}" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:outline-none font-mono" placeholder="e.g. 104829104928104">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Business Account ID</label>
                        <input type="text" name="whatsapp_meta_business_account_id" value="{{ $settings['whatsapp_meta_business_account_id'] ?? '' }}" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:outline-none font-mono" placeholder="e.g. 293849104829104">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Permanent System User Access Token</label>
                    <input type="password" name="whatsapp_meta_access_token" value="{{ $settings['whatsapp_meta_access_token'] ?? '' }}" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:outline-none font-mono" placeholder="EAABw...">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Webhook Verify Token</label>
                    <input type="text" name="whatsapp_webhook_verify_token" value="{{ $settings['whatsapp_webhook_verify_token'] ?? 'unlockrentals_whatsapp_verify_2026' }}" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:outline-none font-mono">
                    <p class="text-[11px] text-slate-400 mt-1">Callback URL for Meta App Dashboard: <code class="bg-white px-1.5 py-0.5 rounded border border-slate-200 font-bold text-slate-700">{{ url('/webhook/whatsapp') }}</code></p>
                </div>
            </div>

            {{-- Twilio Credentials --}}
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-4">
                <h4 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Twilio Credentials (Alternative)</h4>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Account SID</label>
                        <input type="text" name="whatsapp_twilio_sid" value="{{ $settings['whatsapp_twilio_sid'] ?? '' }}" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:outline-none font-mono" placeholder="AC...">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Auth Token</label>
                        <input type="password" name="whatsapp_twilio_token" value="{{ $settings['whatsapp_twilio_token'] ?? '' }}" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:outline-none font-mono">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Twilio Sender Number</label>
                    <input type="text" name="whatsapp_twilio_from" value="{{ $settings['whatsapp_twilio_from'] ?? 'whatsapp:+14155238886' }}" class="w-full sm:w-80 px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:outline-none font-mono" placeholder="whatsapp:+14155238886">
                </div>
            </div>

            {{-- Test WhatsApp Dispatcher --}}
            <div class="p-4 rounded-2xl bg-emerald-50/60 border border-emerald-200">
                <h4 class="text-xs font-extrabold text-emerald-900 uppercase tracking-wider mb-2">Test Live WhatsApp Dispatch</h4>
                <p class="text-xs text-emerald-700 mb-3">Enter an Indian 10-digit mobile number to send a live test message and verify your gateway setup.</p>
                <div class="flex items-center gap-3">
                    <input type="tel" name="test_phone" placeholder="Recipient 10-digit Phone" class="px-3.5 py-2 text-xs rounded-xl border border-emerald-300 bg-white w-56 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <span class="text-xs text-emerald-600 font-medium">(Leave empty if not testing)</span>
                </div>
            </div>
        </div>

        {{-- Section 2: Privacy & Visitor Data Retention --}}
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-6">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl">
                        <i class="ph-bold ph-shield-check"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Privacy & Data Retention Policies</h3>
                        <p class="text-xs text-slate-500">Compliance with India's Digital Personal Data Protection (DPDP) Act and GDPR</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Anonymous Visitor Retention (Days)</label>
                    <input type="number" name="tracking_retention_anonymous_days" value="{{ $settings['tracking_retention_anonymous_days'] ?? '30' }}" min="7" max="180" class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <p class="text-[11px] text-slate-400 mt-1">Sessions & events older than this for unconverted visitors are safely purged automatically.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Identified Lead Retention (Days)</label>
                    <input type="number" name="tracking_retention_leads_days" value="{{ $settings['tracking_retention_leads_days'] ?? '365' }}" min="30" max="1825" class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <p class="text-[11px] text-slate-400 mt-1">Retain consented lead records and communication history for CRM follow-ups.</p>
                </div>
            </div>

            <div class="space-y-3 pt-2">
                <div class="flex items-start gap-3 p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                    <input type="checkbox" name="tracking_anonymize_ip" id="anon_ip" value="1" {{ ($settings['tracking_anonymize_ip'] ?? '1') == '1' ? 'checked' : '' }} class="mt-0.5 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    <label for="anon_ip">
                        <strong class="text-xs font-bold text-slate-800 block">IP Address Masking (Recommended)</strong>
                        <span class="text-[11px] text-slate-500">Masks the last octet of visitor IP addresses (e.g. 49.36.12.***) before storing in database.</span>
                    </label>
                </div>

                <div class="flex items-start gap-3 p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                    <input type="checkbox" name="tracking_filter_bots" id="filter_bots" value="1" {{ ($settings['tracking_filter_bots'] ?? '1') == '1' ? 'checked' : '' }} class="mt-0.5 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    <label for="filter_bots">
                        <strong class="text-xs font-bold text-slate-800 block">Bot & Web Crawler Filtering</strong>
                        <span class="text-[11px] text-slate-500">Automatically excludes Googlebot, Bingbot, and headless scrapers from skewing analytics.</span>
                    </label>
                </div>
            </div>
        </div>

        {{-- Save Button --}}
        <div class="flex items-center justify-end gap-4">
            <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-xs font-bold transition-all shadow-md shadow-blue-600/20 active:scale-95 flex items-center gap-2">
                <i class="ph-bold ph-floppy-disk text-base"></i> Save CRM & Tracking Settings
            </button>
        </div>
    </form>

</div>
@endsection
