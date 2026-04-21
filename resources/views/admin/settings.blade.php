@extends('layouts.app')

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
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Support Email</label>
                        <input type="email" name="site_email" value="{{ $settings['site_email'] ?? 'support@unlockrentals.com' }}" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Contact Phone</label>
                        <input type="text" name="site_phone" value="{{ $settings['site_phone'] ?? '+91 7974164274' }}" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
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

            {{-- Plan Payment Gateway --}}
            <div class="bg-white border border-stone-200 rounded-sm p-6 shadow-sm shadow-stone-100/50">
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
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Bot Welcome Message</label>
                        <textarea name="bot_welcome_message" rows="4" class="w-full px-4 py-2 bg-stone-50 border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]" placeholder="Hi there! 👋 Welcome to Unlock Rental. How can I assist you with your property search today?">{{ $settings['bot_welcome_message'] ?? '' }}</textarea>
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
</script>
@endpush
