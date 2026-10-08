@extends('layouts.app')

@section('title', 'Cancellation & Refund Policy - UnlockRentals')
@section('meta_description', 'Read UnlockRentals transparent Cancellation & Refund Policy. Understand plan eligibility, non-refundable situations, processing times, and support assistance.')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-950 py-12 px-4 sm:px-6 lg:px-8 font-sans">
    <div class="max-w-4xl mx-auto bg-white dark:bg-slate-900 rounded-3xl p-8 sm:p-12 border border-slate-200 dark:border-slate-800 shadow-xl">
        
        {{-- Header --}}
        <div class="border-b border-slate-200 dark:border-slate-800 pb-6 mb-8">
            <div class="flex items-center gap-2 mb-2">
                <a href="{{ route('plans.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-600 hover:text-blue-700 dark:text-blue-400">
                    <i class="ph-bold ph-arrow-left"></i> Back to Plans & Pricing
                </a>
            </div>
            <span class="text-xs font-black uppercase tracking-wider text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/60 px-3 py-1 rounded-full inline-flex items-center gap-1">
                <i class="ph-bold ph-shield-check"></i> Transparent & Fair Policy
            </span>
            <h1 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white mt-3">Cancellation & Refund Policy</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">
                Effective Date: October 2026 · UnlockRentals (unlockrentals.com)
            </p>
        </div>

        {{-- Highlight Trust Box --}}
        <div class="mb-8 p-5 bg-blue-50/70 dark:bg-blue-950/40 rounded-2xl border border-blue-200 dark:border-blue-900 flex items-start gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0 text-xl">
                <i class="ph-bold ph-handshake"></i>
            </div>
            <div class="text-xs sm:text-sm text-blue-950 dark:text-blue-100">
                <p class="font-bold text-slate-900 dark:text-white text-sm sm:text-base">Our Commitment to Transparency</p>
                <p class="mt-1 leading-relaxed text-slate-700 dark:text-slate-300">
                    At UnlockRentals, we believe in honest, upfront relationships. We do not use hidden charges, automatic recurring surprises, or misleading guarantees. Please read our cancellation and refund guidelines below so you know exactly how your plan works.
                </p>
            </div>
        </div>

        {{-- Policy Sections --}}
        <div class="space-y-7 text-slate-700 dark:text-slate-300 text-sm sm:text-base leading-relaxed">
            
            <section>
                <h2 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white mb-2.5 flex items-center gap-2">
                    <i class="ph-bold ph-info text-blue-600"></i>
                    1. Nature of Our Digital Membership Plans
                </h2>
                <p>
                    UnlockRentals provides digital subscription passes (Rental Passes and Buyer Passes) that grant users access to unlock verified owner direct contact details, direct WhatsApp/phone connect, and priority visit scheduling without paying middleman brokerage commissions.
                </p>
                <p class="mt-2">
                    Upon successful transaction confirmation through our payment gateway, your account is immediately credited with the plan's contact view limits and duration validity.
                </p>
            </section>

            <section>
                <h2 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white mb-2.5 flex items-center gap-2">
                    <i class="ph-bold ph-seal-check text-emerald-600"></i>
                    2. Refund Eligibility & Scenarios
                </h2>
                <p class="mb-3">
                    We evaluate refund requests fairly and factually based on service usage:
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs sm:text-sm">
                    <div class="p-4 rounded-2xl bg-emerald-50/60 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800">
                        <p class="font-bold text-emerald-900 dark:text-emerald-300 flex items-center gap-1.5 mb-2">
                            <i class="ph-bold ph-check-circle text-base"></i> Eligible for Refund Review
                        </p>
                        <ul class="space-y-1.5 list-disc pl-4 text-slate-700 dark:text-slate-300">
                            <li><strong>Duplicate Charges:</strong> If an error caused you to be billed twice for the same plan.</li>
                            <li><strong>Technical Failure:</strong> If a technical issue on our server prevented plan activation and our support team could not resolve it within 24 hours.</li>
                            <li><strong>Unused Plan within 24–48 Hours:</strong> If you purchased a plan by mistake and have <em>not unlocked or viewed any owner contact details</em>, you can request a cancellation within 48 hours.</li>
                        </ul>
                    </div>

                    <div class="p-4 rounded-2xl bg-rose-50/60 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-800">
                        <p class="font-bold text-rose-900 dark:text-rose-300 flex items-center gap-1.5 mb-2">
                            <i class="ph-bold ph-x-circle text-base"></i> Non-Refundable Situations
                        </p>
                        <ul class="space-y-1.5 list-disc pl-4 text-slate-700 dark:text-slate-300">
                            <li><strong>Contact Credits Used:</strong> Once you have clicked "Unlock Contact" and revealed owner details, that portion of the digital service is considered fully consumed.</li>
                            <li><strong>Expired Validity:</strong> Plans that have exceeded their designated validity window (e.g. 30 days or 365 days).</li>
                            <li><strong>Third-Party Negotiation:</strong> We connect you directly with genuine owners; whether a lease agreement is ultimately signed depends on owner-tenant terms and cannot form grounds for a refund.</li>
                        </ul>
                    </div>
                </div>
            </section>

            <section>
                <h2 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white mb-2.5 flex items-center gap-2">
                    <i class="ph-bold ph-clock-counter-clockwise text-indigo-600"></i>
                    3. Refund Processing Timeline
                </h2>
                <p>
                    Once a refund is approved by our billing support team:
                </p>
                <ul class="list-disc pl-5 space-y-1.5 mt-2">
                    <li>The refund is initiated directly via our payment gateway (Razorpay or UPI banking partner).</li>
                    <li>Refunds are credited back to the <strong>original payment method</strong> (original UPI ID, bank account, or debit/credit card).</li>
                    <li>Standard banking processing time is <strong>5 to 7 business days</strong> depending on your issuing bank.</li>
                    <li>You will receive an email confirmation and reference number for tracking.</li>
                </ul>
            </section>

            <section>
                <h2 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white mb-2.5 flex items-center gap-2">
                    <i class="ph-bold ph-prohibit text-amber-600"></i>
                    4. Subscription Cancellation Policy
                </h2>
                <p>
                    UnlockRentals passes do not automatically lock you into recurring contracts without your consent. Your pass remains valid for the full duration specified at checkout (e.g., 30 days or 365 days) and naturally expires. If you wish to deactivate or close your subscription early, you can submit a cancellation request from your dashboard or contact our customer support.
                </p>
            </section>

            <section>
                <h2 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white mb-2.5 flex items-center gap-2">
                    <i class="ph-bold ph-headset text-blue-600"></i>
                    5. How to Request Support or Refund
                </h2>
                <p>
                    To submit a billing or cancellation inquiry, please contact our support desk with your registered phone number, email, and payment transaction reference:
                </p>
                <div class="mt-4 p-4 rounded-2xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <p class="font-bold text-slate-900 dark:text-white text-sm">Customer Support Desk</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Available Monday to Saturday (10 AM - 7 PM IST)</p>
                        <p class="text-xs font-semibold text-blue-600 dark:text-blue-400 mt-1">
                            Email: <a href="mailto:{{ $site_settings['site_email'] ?? 'support@unlockrentals.com' }}">{{ $site_settings['site_email'] ?? 'support@unlockrentals.com' }}</a>
                            · Phone: <a href="tel:{{ $site_settings['site_phone'] ?? '+91 94254 55499' }}">{{ $site_settings['site_phone'] ?? '+91 94254 55499' }}</a>
                        </p>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $site_settings['whatsapp_phone'] ?? ($site_settings['site_phone'] ?? '919425455499')) }}?text=Hello%20UnlockRentals%2C%20I%20have%20a%20billing%20inquiry"
                           target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition shadow-xs">
                            <i class="ph-bold ph-whatsapp-logo text-base"></i> WhatsApp Desk
                        </a>
                        <a href="mailto:{{ $site_settings['site_email'] ?? 'support@unlockrentals.com' }}"
                           class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition shadow-xs">
                            <i class="ph-bold ph-envelope text-base"></i> Email Us
                        </a>
                    </div>
                </div>
            </section>

        </div>
    </div>
</div>
@endsection
