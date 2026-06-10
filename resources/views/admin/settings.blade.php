@extends('layouts.admin')

@section('title', 'Manage Settings & Social Content - Admin')

@section('content')
<div class="py-12 bg-stone-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <h1 class="text-3xl font-serif font-light text-zinc-900 mb-2">Platform Settings & Content</h1>
            <p class="text-zinc-500 text-sm">Manage social media links and dynamic content cards across the platform.</p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-sm">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Site Contact Information --}}
            <div class="bg-white border border-stone-200 rounded-sm p-6 shadow-sm shadow-stone-100/50">
                <h2 class="text-xl font-serif font-light text-zinc-900 mb-6 flex items-center gap-2">
                    <i class="ph ph-address-book text-[#2563EB]"></i> Site Contact Info (Footer)
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Support Email</label>
                        <input type="email" name="site_email" value="{{ $settings['site_email'] ?? 'support@unlockrentals.com' }}" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Contact Phone</label>
                        <input type="text" name="site_phone" value="{{ $settings['site_phone'] ?? '+91 7974164274' }}" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Support Agent Phone (Call Agent)</label>
                        <input type="text" name="agent_phone" value="{{ $settings['agent_phone'] ?? '+91 7974164274' }}" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Office Location</label>
                        <input type="text" name="site_address" value="{{ $settings['site_address'] ?? 'Mumbai, India' }}" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
                    </div>
                </div>
            </div>

            {{-- Social Media Cards --}}
            <div class="bg-white border border-stone-200 rounded-sm p-6 shadow-sm shadow-stone-100/50">
                <h2 class="text-xl font-serif font-light text-zinc-900 mb-6 flex items-center gap-2">
                    <i class="ph ph-share-network text-[#2563EB]"></i> Social Media Links
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Facebook URL</label>
                        <input type="url" name="social_facebook" value="{{ $settings['social_facebook'] ?? '' }}" placeholder="https://facebook.com/..." class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB] focus:ring-1 focus:ring-[#2563EB]/30 transition-all">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Instagram URL</label>
                        <input type="url" name="social_instagram" value="{{ $settings['social_instagram'] ?? '' }}" placeholder="https://instagram.com/..." class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB] focus:ring-1 focus:ring-[#2563EB]/30 transition-all">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Twitter / X URL</label>
                        <input type="url" name="social_twitter" value="{{ $settings['social_twitter'] ?? '' }}" placeholder="https://twitter.com/..." class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB] focus:ring-1 focus:ring-[#2563EB]/30 transition-all">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">LinkedIn URL</label>
                        <input type="url" name="social_linkedin" value="{{ $settings['social_linkedin'] ?? '' }}" placeholder="https://linkedin.com/in/..." class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB] focus:ring-1 focus:ring-[#2563EB]/30 transition-all">
                    </div>
                </div>
            </div>

            {{-- Social Login Configuration --}}
            <div class="bg-white border border-stone-200 rounded-sm p-6 shadow-sm shadow-stone-100/50">
                <h2 class="text-xl font-serif font-light text-zinc-900 mb-6 flex items-center gap-2">
                    <i class="ph ph-fingerprint text-[#2563EB]"></i> Social Authentication (Google & Facebook)
                </h2>
                
                <div class="space-y-8">
                    {{-- Google --}}
                    <div>
                        <h3 class="text-sm font-bold text-zinc-900 mb-4 flex items-center gap-2">
                            <svg width="16" height="16" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                            Google Login
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Google Client ID</label>
                                <input type="text" name="google_client_id" value="{{ $settings['google_client_id'] ?? '' }}" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Google Client Secret</label>
                                <div class="relative">
                                    <input type="password" name="google_client_secret" id="google_secret" value="{{ $settings['google_client_secret'] ?? '' }}" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
                                    <button type="button" onclick="togglePassword('google_secret', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#2563EB]">
                                        <svg class="eye-open" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                        <svg class="eye-closed" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Facebook --}}
                    <div>
                        <h3 class="text-sm font-bold text-zinc-900 mb-4 flex items-center gap-2">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="#1877F2"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            Facebook Login
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Facebook Client ID</label>
                                <input type="text" name="facebook_client_id" value="{{ $settings['facebook_client_id'] ?? '' }}" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Facebook Client Secret</label>
                                <div class="relative">
                                    <input type="password" name="facebook_client_secret" id="facebook_secret" value="{{ $settings['facebook_client_secret'] ?? '' }}" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
                                    <button type="button" onclick="togglePassword('facebook_secret', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#2563EB]">
                                        <svg class="eye-open" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                        <svg class="eye-closed" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SMTP Mail Configuration --}}
            <div class="bg-white border border-stone-200 rounded-sm p-6 shadow-sm shadow-stone-100/50">
                <h2 class="text-xl font-serif font-light text-zinc-900 mb-6 flex items-center gap-2">
                    <i class="ph ph-envelope-simple text-[#2563EB]"></i> SMTP Mail Server (Dynamic)
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">SMTP Host</label>
                        <input type="text" name="mail_host" value="{{ $settings['mail_host'] ?? '' }}" placeholder="smtp.gmail.com" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">SMTP Port</label>
                        <input type="text" name="mail_port" value="{{ $settings['mail_port'] ?? '587' }}" placeholder="587 or 465" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">SMTP Username</label>
                        <input type="text" name="mail_username" value="{{ $settings['mail_username'] ?? '' }}" placeholder="your-email@gmail.com" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">SMTP Password (App Password)</label>
                        <div class="relative">
                            <input type="password" name="mail_password" id="mail_password" value="{{ $settings['mail_password'] ?? '' }}" placeholder="••••••••••••••••" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
                            <button type="button" onclick="togglePassword('mail_password', this)"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#2563EB] transition-colors focus:outline-none" aria-label="Toggle password">
                                <svg class="eye-open" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                <svg class="eye-closed" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Encryption</label>
                        <select name="mail_encryption" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
                            <option value="tls" {{ ($settings['mail_encryption'] ?? '') == 'tls' ? 'selected' : '' }}>TLS (Usually Port 587)</option>
                            <option value="ssl" {{ ($settings['mail_encryption'] ?? '') == 'ssl' ? 'selected' : '' }}>SSL (Usually Port 465)</option>
                            <option value="none" {{ ($settings['mail_encryption'] ?? '') == 'none' ? 'selected' : '' }}>None</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">From Email Address</label>
                        <input type="email" name="mail_from_address" value="{{ $settings['mail_from_address'] ?? '' }}" placeholder="noreply@unlockrentals.com" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
                    </div>
                </div>
            </div>

            {{-- Dynamic Payment Gateways --}}
            <div class="bg-white border border-stone-200 rounded-sm p-6 shadow-sm shadow-stone-100/50">
                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-6">
                    <div>
                        <h2 class="text-xl font-serif font-light text-zinc-900 mb-2 flex items-center gap-2">
                            <i class="ph ph-credit-card text-[#2563EB]"></i> Dynamic Payment Gateways
                        </h2>
                        <p class="text-sm text-zinc-500">Add any number of gateways, enable or disable them, and choose which one customers use at checkout.</p>
                    </div>
                    <button type="button" onclick="addPaymentGateway()" class="px-4 py-2 bg-[#2563EB] text-white text-xs font-bold uppercase tracking-wider rounded-sm hover:bg-[#1D4ED8] transition-all">
                        Add Gateway
                    </button>
                </div>

                <div id="payment-gateways" class="space-y-5">
                    @foreach($paymentGateways as $index => $gateway)
                        @include('admin.partials.payment-gateway-fields', ['gateway' => $gateway, 'index' => $index, 'activePaymentGatewayId' => $activePaymentGatewayId])
                    @endforeach
                </div>
            </div>

            {{-- Legacy Plan Payment Gateway --}}
            <div class="hidden bg-white border border-stone-200 rounded-sm p-6 shadow-sm shadow-stone-100/50">
                <h2 class="text-xl font-serif font-light text-zinc-900 mb-2 flex items-center gap-2">
                    <i class="ph ph-credit-card text-[#2563EB]"></i> Plan Payment Gateway
                </h2>
                <p class="text-sm text-zinc-500 mb-6">Update the payment method shown on the plans page without editing code. This works well for UPI, bank transfer, wallet, or an external checkout link.</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Gateway Name</label>
                        <input type="text" name="payment_gateway_name" value="{{ $settings['payment_gateway_name'] ?? 'UPI Payment' }}" placeholder="UPI Payment / Bank Transfer / Razorpay" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Account / Merchant Name</label>
                        <input type="text" name="payment_gateway_account_name" value="{{ $settings['payment_gateway_account_name'] ?? '' }}" placeholder="UnlockRentals Pvt Ltd" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Gateway Identifier</label>
                        <input type="text" name="payment_gateway_identifier" value="{{ $settings['payment_gateway_identifier'] ?? '' }}" placeholder="UPI ID / Account No / Wallet ID" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Payment Link</label>
                        <input type="url" name="payment_gateway_link" value="{{ $settings['payment_gateway_link'] ?? '' }}" placeholder="https://..." class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">QR Image URL</label>
                        <input type="url" name="payment_gateway_qr_url" value="{{ $settings['payment_gateway_qr_url'] ?? '' }}" placeholder="https://example.com/payment-qr.png" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Reference Field Label</label>
                        <input type="text" name="payment_reference_label" value="{{ $settings['payment_reference_label'] ?? 'Transaction ID / UTR Number' }}" placeholder="Transaction ID / UTR Number" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
                    </div>
                </div>

                <div class="mt-8 pt-8 border-t border-stone-100">
                    <h3 class="text-sm font-bold text-zinc-900 mb-4 flex items-center gap-2">
                        <img src="https://razorpay.com/favicon.png" class="w-4 h-4" alt="">
                        Razorpay API Configuration (Automated)
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Razorpay Key ID</label>
                            <input type="text" name="razorpay_key_id" value="{{ $settings['razorpay_key_id'] ?? '' }}" placeholder="rzp_test_..." class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Razorpay Key Secret</label>
                            <div class="relative">
                                <input type="password" name="razorpay_key_secret" id="razorpay_secret" value="{{ $settings['razorpay_key_secret'] ?? '' }}" placeholder="••••••••••••••••" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
                                <button type="button" onclick="togglePassword('razorpay_secret', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#2563EB]">
                                    <svg class="eye-open" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                    <svg class="eye-closed" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <p class="mt-4 text-[11px] text-zinc-500 italic">Get these from your <a href="https://dashboard.razorpay.com/app/dashboard" target="_blank" class="text-[#2563EB] underline">Razorpay Dashboard</a> under Settings > API Keys.</p>
                </div>

                <div class="mt-6">
                    <textarea name="payment_gateway_instructions" rows="4" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]" placeholder="Example:
1. Pay the exact plan amount.
2. Add the transaction ID / UTR while submitting.
3. Admin will verify and approve the plan.">{{ $settings['payment_gateway_instructions'] ?? '' }}</textarea>
                </div>
            </div>

            {{-- Support & Interaction Settings --}}
            <div class="bg-white border border-stone-200 rounded-sm p-6 shadow-sm shadow-stone-100/50">
                <h2 class="text-xl font-serif font-light text-zinc-900 mb-6 flex items-center gap-2">
                    <i class="ph ph-chat-centered-text text-[#2563EB]"></i> Support & Interaction Features
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-stone-50 rounded-sm border border-stone-100">
                            <div>
                                <h4 class="text-sm font-bold text-zinc-900 uppercase tracking-wider">AI Chatbot</h4>
                                <p class="text-xs text-zinc-500">Display the floating support chatbot</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="chatbot_enabled" value="1" {{ ($settings['chatbot_enabled'] ?? '1') == '1' ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-stone-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#2563EB]"></div>
                            </label>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-stone-50 rounded-sm border border-stone-100">
                            <div>
                                <h4 class="text-sm font-bold text-zinc-900 uppercase tracking-wider">Customer Feedback</h4>
                                <p class="text-xs text-zinc-500">Enable the star rating feedback section</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="feedback_enabled" value="1" {{ ($settings['feedback_enabled'] ?? '1') == '1' ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-stone-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#2563EB]"></div>
                            </label>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-stone-50 rounded-sm border border-stone-100">
                            <div>
                                <h4 class="text-sm font-bold text-zinc-900 uppercase tracking-wider">Bypass Approval</h4>
                                <p class="text-xs text-zinc-500">Auto-approve all new property submissions directly</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="bypass_property_approval" value="1" {{ ($settings['bypass_property_approval'] ?? '0') == '1' ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-stone-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#2563EB]"></div>
                            </label>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Bot Welcome Message</label>
                        <textarea name="bot_welcome_message" rows="4" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]" placeholder="Hi there! 👋 Welcome to UnlockRentals. How can I assist you with your property search today?">{{ $settings['bot_welcome_message'] ?? '' }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Bot Auto-Responses (One per line)</label>
                        <p class="text-[11px] text-zinc-500 mb-2">These are the random dynamic responses the bot will use when a user asks a general question.</p>
                        <textarea name="bot_auto_responses" rows="5" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]" placeholder="That's a great question! Let me check our premium listings for you.
I can certainly help you with that. Would you like to see properties in a specific city?
One of our agents will be happy to assist you further. Shall I book a callback for you?
UnlockRentals offers the best verified properties in India. You're in good hands!">{{ $settings['bot_auto_responses'] ?? "That's a great question! Let me check our premium listings for you.\nI can certainly help you with that. Would you like to see properties in a specific city?\nOne of our agents will be happy to assist you further. Shall I book a callback for you?\nUnlockRentals offers the best verified properties in India. You're in good hands!" }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Home Page & Resources Management --}}
            <div class="bg-white border border-stone-200 rounded-sm p-6 shadow-sm shadow-stone-100/50">
                <h2 class="text-xl font-serif font-light text-zinc-900 mb-6 flex items-center gap-2">
                    <i class="ph ph-layout text-[#2563EB]"></i> Home Page & Resources
                </h2>
                
                <div class="space-y-10">
                    {{-- How It Works --}}
                    <div>
                        <h3 class="text-sm font-bold text-zinc-900 mb-4 border-b border-stone-100 pb-2 uppercase tracking-wider">Step-by-Step Journey (How It Works)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="space-y-4">
                                <label class="block text-[10px] font-black text-blue-600 uppercase tracking-widest">Step 01: Discover</label>
                                <input type="text" name="how_it_works_1_title" value="{{ $settings['how_it_works_1_title'] ?? 'Discover' }}" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]" placeholder="Title">
                                <textarea name="how_it_works_1_desc" rows="2" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-xs focus:outline-none focus:border-[#2563EB]" placeholder="Description">{{ $settings['how_it_works_1_desc'] ?? 'Browse our curated registry of premium properties with intelligent filters.' }}</textarea>
                            </div>
                            <div class="space-y-4">
                                <label class="block text-[10px] font-black text-blue-600 uppercase tracking-widest">Step 02: Concierge</label>
                                <input type="text" name="how_it_works_2_title" value="{{ $settings['how_it_works_2_title'] ?? 'Concierge' }}" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]" placeholder="Title">
                                <textarea name="how_it_works_2_desc" rows="2" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-xs focus:outline-none focus:border-[#2563EB]" placeholder="Description">{{ $settings['how_it_works_2_desc'] ?? 'Connect directly with owners or leverage our elite concierge for viewing support.' }}</textarea>
                            </div>
                            <div class="space-y-4">
                                <label class="block text-[10px] font-black text-blue-600 uppercase tracking-widest">Step 03: Finalize</label>
                                <input type="text" name="how_it_works_3_title" value="{{ $settings['how_it_works_3_title'] ?? 'Finalize' }}" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]" placeholder="Title">
                                <textarea name="how_it_works_3_desc" rows="2" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-xs focus:outline-none focus:border-[#2563EB]" placeholder="Description">{{ $settings['how_it_works_3_desc'] ?? 'Complete digital legal paperwork securely and move into your new luxury space.' }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Resource Directory Tags --}}
                    <div>
                        <h3 class="text-sm font-bold text-zinc-900 mb-4 border-b border-stone-100 pb-2 uppercase tracking-wider">Resource Directory Tags</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Buy Services Tags (Comma Separated)</label>
                                <textarea name="directory_buy_tags" rows="6" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-xs focus:outline-none focus:border-[#2563EB]" placeholder="Legal Services, Interiors, ...">{{ $settings['directory_buy_tags'] ?? 'Property Legal Services, Interiors, Sale Agreement, NoBroker For NRIs, New Builder Project, Home Loan EMI Calculator, Home Loan Balance Transfer, Home Loan Eligibility Calculator, Apply Home Loan, Compare Home Loan Interest, Property Buyers Forum, Property Buyers Guide, Property Seller Guide, Home Loan Guide, Home Loan Queries, Home Renovation Guide, Home Renovation Queries, Interior Design Tips, Interior Design Queries, NRI RealEstate Guide, NRI RealEstate Queries, Realestate Vastu Guide, Personal Loan Guide, Personal Loan Queries, Bill Payment Guide, Realestate Legal Guide, Realestate Legal Queries, e-AASTHI BBMP, Due Diligence Service' }}</textarea>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Rent Services Tags (Comma Separated)</label>
                                <textarea name="directory_rent_tags" rows="6" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-xs focus:outline-none focus:border-[#2563EB]" placeholder="Rental Agreement, Packers & Movers, ...">{{ $settings['directory_rent_tags'] ?? 'Rental Agreement, Pay Tuition Fee, Refer and Earn, Packers and Movers, Property Management in India, Home Services Questions, Rent Services Questions, Rent Calculator, Property Rental Guide, Landlord Guide, Tenant Guide, Packers and Movers Guide, Packers and Movers queries, Home Services, Home Services Queries, Painting Services, Home Painting Guide, Home Painting Queries, Cleaning Services, Kitchen Cleaning Services, Sofa Cleaning Services, Bathroom Cleaning Services, Full House Cleaning Services, Home Cleaning Guide, Home Cleaning Queries, AC Services, Carpentry Services, Carpentry Services Queries, Electrician Services, Electrician Services Queries, Plumbing Services, Plumbing Services Queries, Lease Agreement, Notary, Notary Advocate, Notary Affidavit' }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Mobile App Links --}}
                    <div>
                        <h3 class="text-sm font-bold text-zinc-900 mb-4 border-b border-stone-100 pb-2 uppercase tracking-wider">Mobile App Distribution</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Google Play URL</label>
                                <input type="url" name="app_google_play_url" value="{{ $settings['app_google_play_url'] ?? '#' }}" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Apple App Store URL</label>
                                <input type="url" name="app_store_url" value="{{ $settings['app_store_url'] ?? '#' }}" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Testimonials / Success Stories --}}
            <div class="bg-white border border-stone-200 rounded-sm p-6 shadow-sm shadow-stone-100/50">
                <h2 class="text-xl font-serif font-light text-zinc-900 mb-6 flex items-center gap-2">
                    <i class="ph ph-chat-teardrop-text text-[#2563EB]"></i> Success Stories (Testimonials)
                </h2>
                
                <div class="space-y-8">
                    {{-- Testimonial 1 --}}
                    <div class="border-b border-stone-100 pb-6">
                        <h3 class="text-xs font-bold text-zinc-700 uppercase tracking-widest mb-4 text-[#2563EB]">Testimonial 1 (Left Card)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-4">
                            <div>
                                <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Author Name</label>
                                <input type="text" name="testimonial_1_author" value="{{ $settings['testimonial_1_author'] ?? 'Rahul S.' }}" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Role / Company</label>
                                <input type="text" name="testimonial_1_role" value="{{ $settings['testimonial_1_role'] ?? 'CEO, TechFlow India' }}" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Avatar Image URL</label>
                                <input type="text" name="testimonial_1_image" value="{{ $settings['testimonial_1_image'] ?? 'https://randomuser.me/api/portraits/men/43.jpg' }}" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Star Rating</label>
                                <select name="testimonial_1_stars" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
                                    @for($i = 5; $i >= 1; $i--)
                                        <option value="{{ $i }}" {{ ($settings['testimonial_1_stars'] ?? '5') == $i ? 'selected' : '' }}>{{ str_repeat('★', $i) }} ({{ $i }})</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Quote Text</label>
                            <textarea name="testimonial_1_quote" rows="2" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">{{ $settings['testimonial_1_quote'] ?? "UnlockRentals made finding our company's new office space in Cyber City incredibly seamless. The verified listings and sleek UI saved us weeks of searching." }}</textarea>
                        </div>
                    </div>

                    {{-- Testimonial 2 --}}
                    <div class="border-b border-stone-100 pb-6">
                        <h3 class="text-xs font-bold text-zinc-700 uppercase tracking-widest mb-4 text-[#2563EB]">Testimonial 2 (Middle Card)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-4">
                            <div>
                                <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Author Name</label>
                                <input type="text" name="testimonial_2_author" value="{{ $settings['testimonial_2_author'] ?? 'Priya D.' }}" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Role / Company</label>
                                <input type="text" name="testimonial_2_role" value="{{ $settings['testimonial_2_role'] ?? 'Property Owner' }}" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Avatar Image URL</label>
                                <input type="text" name="testimonial_2_image" value="{{ $settings['testimonial_2_image'] ?? 'https://randomuser.me/api/portraits/women/68.jpg' }}" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Star Rating</label>
                                <select name="testimonial_2_stars" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
                                    @for($i = 5; $i >= 1; $i--)
                                        <option value="{{ $i }}" {{ ($settings['testimonial_2_stars'] ?? '5') == $i ? 'selected' : '' }}>{{ str_repeat('★', $i) }} ({{ $i }})</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Quote Text</label>
                            <textarea name="testimonial_2_quote" rows="2" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">{{ $settings['testimonial_2_quote'] ?? "I listed my luxury villa in Assagao and within 48 hours I had a verified, high-quality tenant. The platform's concierge support is world-class." }}</textarea>
                        </div>
                    </div>

                    {{-- Testimonial 3 --}}
                    <div>
                        <h3 class="text-xs font-bold text-zinc-700 uppercase tracking-widest mb-4 text-[#2563EB]">Testimonial 3 (Right Card)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-4">
                            <div>
                                <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Author Name</label>
                                <input type="text" name="testimonial_3_author" value="{{ $settings['testimonial_3_author'] ?? 'Aditya P.' }}" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Role / Company</label>
                                <input type="text" name="testimonial_3_role" value="{{ $settings['testimonial_3_role'] ?? 'Renter' }}" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Avatar Image URL</label>
                                <input type="text" name="testimonial_3_image" value="{{ $settings['testimonial_3_image'] ?? 'https://randomuser.me/api/portraits/men/57.jpg' }}" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Star Rating</label>
                                <select name="testimonial_3_stars" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
                                    @for($i = 5; $i >= 1; $i--)
                                        <option value="{{ $i }}" {{ ($settings['testimonial_3_stars'] ?? '5') == $i ? 'selected' : '' }}>{{ str_repeat('★', $i) }} ({{ $i }})</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Quote Text</label>
                            <textarea name="testimonial_3_quote" rows="2" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">{{ $settings['testimonial_3_quote'] ?? "The filtering is incredibly smart. We found a beautiful apartment that checked off all our boxes in South Mumbai without dealing with broker spam." }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Dynamic Content Cards --}}
            <div class="bg-white border border-stone-200 rounded-sm p-6 shadow-sm shadow-stone-100/50">
                <h2 class="text-xl font-serif font-light text-zinc-900 mb-6 flex items-center gap-2">
                    <i class="ph ph-cards text-[#2563EB]"></i> Frontend Content Cards
                </h2>
                
                <div class="space-y-6">
                     <div>
                        <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Hero Main Title (First Half)</label>
                        <input type="text" name="hero_title_1" value="{{ $settings['hero_title_1'] ?? 'Find Your' }}" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Hero Main Title (Highlighted)</label>
                        <input type="text" name="hero_title_2" value="{{ $settings['hero_title_2'] ?? 'Perfect Nest' }}" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Hero Description</label>
                        <textarea name="hero_description" rows="3" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">{{ $settings['hero_description'] ?? 'Discover thousands of premium houses, cozy apartments, and practical shop spaces. Connect directly with owners and settle in effortlessly.' }}</textarea>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Primary CTA Button Text (Get Started)</label>
                        <input type="text" name="cta_button_text" value="{{ $settings['cta_button_text'] ?? 'Post Free Advertisement to Rent or Sell your Property' }}" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Footer Copyright Text</label>
                        <input type="text" name="footer_text" value="{{ $settings['footer_text'] ?? '© 2026 UnlockRentals. All rights reserved.' }}" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-4 mt-6">
                <a href="{{ route('admin.dashboard') }}" class="px-6 py-3 border border-stone-200 text-zinc-500 font-medium tracking-wide rounded-sm hover:border-zinc-300 transition-all text-sm">Cancel</a>
                <button type="submit" class="px-8 py-3 bg-[#2563EB] text-white font-medium tracking-wide rounded-sm shadow-sm hover:bg-[#1D4ED8] transition-all text-sm">Save Changes</button>
            </div>
        </form>

    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    const eyeOpen = btn.querySelector('.eye-open');
    const eyeClosed = btn.querySelector('.eye-closed');
    if (input.type === 'password') {
        input.type = 'text';
        eyeOpen.style.display = 'none';
        eyeClosed.style.display = 'block';
    } else {
        input.type = 'password';
        eyeOpen.style.display = 'block';
        eyeClosed.style.display = 'none';
    }
}

function addPaymentGateway() {
    const container = document.getElementById('payment-gateways');
    const index = Date.now();
    const id = `gateway-${index}`;

    container.insertAdjacentHTML('beforeend', `
        <div class="payment-gateway-item border border-stone-200 rounded-sm p-5 bg-stone-50/50" data-index="${index}">
            <input type="hidden" name="payment_gateways[${index}][id]" value="${id}">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-5">
                <div class="flex flex-wrap items-center gap-5">
                    <label class="inline-flex items-center gap-2 text-xs font-bold text-zinc-700 uppercase tracking-wider">
                        <input type="radio" name="active_payment_gateway_id" value="${id}"> Active
                    </label>
                    <label class="inline-flex items-center gap-2 text-xs font-bold text-zinc-700 uppercase tracking-wider">
                        <input type="checkbox" name="payment_gateways[${index}][enabled]" value="1" checked> Enabled
                    </label>
                </div>
                <button type="button" onclick="removePaymentGateway(this)" class="text-xs font-bold text-red-500 hover:text-red-700 uppercase tracking-wider">Remove</button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                ${gatewayField(index, 'Gateway Name', 'name', 'text', 'Razorpay / UPI / Bank Transfer')}
                <div>
                    <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Gateway Type</label>
                    <select name="payment_gateways[${index}][type]" class="w-full px-4 py-2 bg-white border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
                        <option value="razorpay">Razorpay Automated</option>
                        <option value="manual" selected>Manual Verification</option>
                        <option value="external">External Payment Link</option>
                    </select>
                </div>
                ${gatewayField(index, 'Account / Merchant Name', 'account_name', 'text', 'UnlockRentals Pvt Ltd')}
                ${gatewayField(index, 'Gateway Identifier', 'identifier', 'text', 'UPI ID / Account No / Wallet ID')}
                ${gatewayField(index, 'Payment Link', 'payment_link', 'url', 'https://...')}
                ${gatewayField(index, 'QR Image URL', 'qr_url', 'url', 'https://example.com/payment-qr.png')}
                ${gatewayField(index, 'Reference Field Label', 'reference_label', 'text', 'Transaction ID / UTR Number')}
                ${gatewayField(index, 'Razorpay Key ID', 'key_id', 'text', 'rzp_test_...')}
                ${gatewayField(index, 'Razorpay Key Secret', 'key_secret', 'password', 'Only needed for Razorpay')}
                <div class="md:col-span-2">
                    <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Checkout Instructions</label>
                    <textarea name="payment_gateways[${index}][instructions]" rows="3" class="w-full px-4 py-2 bg-white border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]" placeholder="Tell users how to pay and what reference to submit."></textarea>
                </div>
            </div>
        </div>
    `);
}

function gatewayField(index, label, key, type, placeholder) {
    return `
        <div>
            <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">${label}</label>
            <input type="${type}" name="payment_gateways[${index}][${key}]" placeholder="${placeholder}" class="w-full px-4 py-2 bg-white border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
        </div>
    `;
}

function removePaymentGateway(button) {
    button.closest('.payment-gateway-item').remove();
}
</script>
@endpush
