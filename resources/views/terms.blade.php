@extends('layouts.app')

@section('title', 'Terms & Conditions - UnlockRentals')
@section('meta_description', 'Terms and Conditions for UnlockRentals rental platform. Read our terms of service, listing guidelines, and user agreements.')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-950 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto bg-white dark:bg-slate-900 rounded-3xl p-8 sm:p-12 border border-slate-200 dark:border-slate-800 shadow-xl">
        <div class="border-b border-slate-200 dark:border-slate-800 pb-6 mb-8">
            <span class="text-xs font-black uppercase tracking-wider text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/60 px-3 py-1 rounded-full">Legal & Agreements</span>
            <h1 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white mt-3">Terms & Conditions</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">Last Updated: August 2026 · UnlockRentals Platform</p>
        </div>

        <div class="space-y-6 text-slate-700 dark:text-slate-300 text-sm sm:text-base leading-relaxed">
            <section>
                <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-2">1. Agreement to Terms</h2>
                <p>By accessing or using the UnlockRentals website (https://unlockrentals.com) and mobile applications, you agree to be bound by these Terms and Conditions. If you do not agree with any part of these terms, please do not use our services.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-2">2. Zero Brokerage & Property Listings</h2>
                <p class="mb-2">UnlockRentals connects prospective tenants directly with verified property owners without middleman brokerage fees.</p>
                <ul class="list-disc pl-5 space-y-1 text-slate-600 dark:text-slate-400">
                    <li><strong>Listing Authenticity:</strong> Owners and listing creators represent and warrant that all property details, photos, rents, and location coordinates are accurate and up-to-date.</li>
                    <li><strong>Approval & Moderation:</strong> UnlockRentals reserves the right to review, edit, or reject listings that violate our quality guidelines or appear fraudulent.</li>
                    <li><strong>Direct Deals:</strong> Final lease agreements, security deposits, and rent transactions are conducted directly between owner and tenant.</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-2">3. Subscription Plans & Contact Unlocking</h2>
                <p class="mb-2">Certain premium features, such as unlocking verified landlord contacts or priority property placement, require an active subscription plan.</p>
                <ul class="list-disc pl-5 space-y-1 text-slate-600 dark:text-slate-400">
                    <li>Plans are activated immediately upon successful transaction confirmation via our secure payment gateway.</li>
                    <li>Subscription fees and contact unlock credits are subject to the specific terms displayed during checkout.</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-2">4. User Conduct & Security</h2>
                <p>Users agree not to harvest data, submit fake inquiries, post defamatory content, or attempt unauthorized access to our infrastructure. Any breach will result in immediate account suspension.</p>
            </section>

            <section>
                <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-2">5. Contact Information</h2>
                <p>For questions or clarifications regarding our Terms & Conditions, please contact us:</p>
                <p class="mt-2 font-semibold text-blue-600 dark:text-blue-400">Email: support@unlockrentals.com<br>Website: https://unlockrentals.com</p>
            </section>
        </div>
    </div>
</div>
@endsection
