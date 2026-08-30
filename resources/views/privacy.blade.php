@extends('layouts.app')

@section('title', 'Privacy Policy - UnlockRentals')
@section('meta_description', 'Privacy Policy for UnlockRentals property rental platform. Learn how we protect and handle your personal data.')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-950 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto bg-white dark:bg-slate-900 rounded-3xl p-8 sm:p-12 border border-slate-200 dark:border-slate-800 shadow-xl">
        <div class="border-b border-slate-200 dark:border-slate-800 pb-6 mb-8">
            <span class="text-xs font-black uppercase tracking-wider text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/60 px-3 py-1 rounded-full">Legal & Transparency</span>
            <h1 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white mt-3">Privacy Policy</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">Last Updated: August 2026 · UnlockRentals Platform</p>
        </div>

        <div class="space-y-6 text-slate-700 dark:text-slate-300 text-sm sm:text-base leading-relaxed">
            <section>
                <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-2">1. Overview</h2>
                <p>UnlockRentals ("we", "our", or "us") is dedicated to protecting your privacy. This Privacy Policy explains how our website (https://unlockrentals.com) and mobile applications collect, use, disclose, and safeguard your personal information when you use our zero-brokerage rental platform.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-2">2. Information We Collect</h2>
                <p class="mb-2">We collect information that you provide directly to us when creating an account, posting property listings, or unlocking owner contacts:</p>
                <ul class="list-disc pl-5 space-y-1 text-slate-600 dark:text-slate-400">
                    <li><strong>Account Data:</strong> Name, email address, phone number, and password.</li>
                    <li><strong>Property Listings:</strong> Property descriptions, photos, pricing, locality, and owner contact details.</li>
                    <li><strong>Payment & Subscription Data:</strong> Transaction references, subscription plan IDs processed securely via RBI-authorized payment gateways (such as Razorpay). We never store your full card details on our servers.</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-2">3. How We Use Your Information</h2>
                <ul class="list-disc pl-5 space-y-1 text-slate-600 dark:text-slate-400">
                    <li>To provide, operate, and maintain the UnlockRentals rental platform.</li>
                    <li>To enable direct communication between verified tenants and property owners.</li>
                    <li>To verify listings and prevent duplicate or fraudulent properties.</li>
                    <li>To send booking confirmations, transaction receipts, and customer support responses.</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-2">4. Data Security</h2>
                <p>We implement 256-bit SSL encryption, secure tokens, and strict database access controls to safeguard your personal information against unauthorized access, alteration, or disclosure.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-2">5. Contact Us</h2>
                <p>If you have any questions regarding this Privacy Policy or your personal data, please contact our support team:</p>
                <p class="mt-2 font-semibold text-blue-600 dark:text-blue-400">Email: support@unlockrentals.com<br>Website: https://unlockrentals.com</p>
            </section>
        </div>
    </div>
</div>
@endsection
