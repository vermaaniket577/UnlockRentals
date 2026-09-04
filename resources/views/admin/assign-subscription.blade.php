@extends('layouts.admin')

@section('title', 'Assign Plan or Custom Offer - Admin')

@section('content')
<section class="py-8 lg:py-10 bg-slate-50/50 dark:bg-slate-950 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Breadcrumb Navigation --}}
        <nav class="flex items-center gap-2 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-6">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 transition-colors">Admin</a>
            <i class="ph-bold ph-caret-right text-[10px]"></i>
            <a href="{{ route('admin.subscriptions') }}" class="hover:text-blue-600 transition-colors">Subscriptions</a>
            <i class="ph-bold ph-caret-right text-[10px]"></i>
            <span class="text-slate-800 dark:text-slate-200">Assign Plan</span>
        </nav>

        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 dark:bg-blue-950/60 border border-blue-200 dark:border-blue-800 text-blue-700 dark:text-blue-300 text-xs font-bold uppercase tracking-wider mb-2">
                    <i class="ph-bold ph-ticket text-sm"></i>
                    <span>Manual Plan Assignment & Custom Offers</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                    Assign Plan to User
                </h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">
                    Grant immediate membership access or send exclusive discounted checkout offers.
                </p>
            </div>
            <a href="{{ route('admin.subscriptions') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 text-xs font-bold shadow-sm hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                <i class="ph-bold ph-arrow-left"></i>
                <span>Back to Subscriptions</span>
            </a>
        </div>

        {{-- Alert Messages --}}
        @if(session('error'))
        <div class="mb-6 p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-900 text-sm text-rose-700 dark:text-rose-300 flex items-start gap-3 shadow-sm">
            <i class="ph-fill ph-warning-circle text-lg mt-0.5 shrink-0 text-rose-600"></i>
            <div>{{ session('error') }}</div>
        </div>
        @endif

        @if(session('success'))
        <div class="mb-6 p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-900 text-sm text-emerald-700 dark:text-emerald-300 flex items-start gap-3 shadow-sm">
            <i class="ph-fill ph-check-circle text-lg mt-0.5 shrink-0 text-emerald-600"></i>
            <div>{{ session('success') }}</div>
        </div>
        @endif

        {{-- Main Form Card --}}
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-xl overflow-hidden">
            <form action="{{ route('admin.subscriptions.store-assign') }}" method="POST" id="assign-plan-form" class="p-6 sm:p-10 space-y-8">
                @csrf

                {{-- Section 1: Select User --}}
                <div>
                    <label for="user_id" class="block text-xs font-black text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2.5 flex items-center justify-between">
                        <span>1. Target User Account <span class="text-rose-500">*</span></span>
                        <span class="text-[11px] font-semibold text-slate-400">Total Users: {{ count($users) }}</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <i class="ph-bold ph-user text-base"></i>
                        </div>
                        <select name="user_id" id="user_id" required class="w-full pl-11 pr-10 py-3.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-2xl text-sm font-medium text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition">
                            <option value="">-- Choose User by Name or Email --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" 
                                        data-name="{{ $user->name }}" 
                                        data-email="{{ $user->email }}" 
                                        data-phone="{{ $user->phone ?? 'N/A' }}"
                                        {{ (isset($selectedUserId) && $selectedUserId == $user->id) ? 'selected' : '' }}>
                                    {{ $user->name }} &bull; {{ $user->email }} {{ $user->phone ? '(' . $user->phone . ')' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('user_id')
                        <p class="mt-2 text-xs text-rose-600 font-bold flex items-center gap-1.5">
                            <i class="ph-bold ph-warning"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Section 2: Select Plan --}}
                <div>
                    <label for="plan_id" class="block text-xs font-black text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2.5">
                        2. Subscription Plan Tier <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <i class="ph-bold ph-crown text-base"></i>
                        </div>
                        <select name="plan_id" id="plan_id" required class="w-full pl-11 pr-10 py-3.5 bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-2xl text-sm font-medium text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition" onchange="updatePlanDetails()">
                            <option value="">-- Choose Plan Tier --</option>
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}" 
                                        data-name="{{ $plan->name }}"
                                        data-price="{{ (float)$plan->price }}"
                                        data-duration="{{ $plan->duration_days }}"
                                        data-contacts="{{ $plan->contact_limit }}">
                                    {{ $plan->name }} &bull; ₹{{ number_format($plan->price, 0) }} &bull; {{ $plan->duration_days }} Days &bull; {{ $plan->contact_limit }} Contacts
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('plan_id')
                        <p class="mt-2 text-xs text-rose-600 font-bold flex items-center gap-1.5">
                            <i class="ph-bold ph-warning"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Section 3: Mode Selection (Interactive Cards) --}}
                <div>
                    <label class="block text-xs font-black text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-3">
                        3. Assignment Method <span class="text-rose-500">*</span>
                    </label>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Card A: Custom Offer (User Pays) --}}
                        <label class="relative p-5 rounded-2xl border-2 cursor-pointer transition-all duration-200 flex flex-col justify-between" id="card-custom-offer">
                            <input type="radio" name="assign_type" value="custom_offer" checked class="sr-only" onchange="handleAssignTypeChange(this.value)">
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <span class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xl">
                                        <i class="ph-bold ph-tag"></i>
                                    </span>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 text-[10px] font-black uppercase">
                                        User Pays Online
                                    </span>
                                </div>
                                <h4 class="font-bold text-slate-900 dark:text-white text-base mb-1">Exclusive Custom Offer</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                                    User receives a private notification on their dashboard and checks out with your designated discounted price.
                                </p>
                            </div>
                            <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center gap-2 text-xs font-bold text-blue-600 dark:text-blue-400" id="indicator-custom-offer">
                                <i class="ph-fill ph-check-circle text-base"></i>
                                <span>Selected</span>
                            </div>
                        </label>

                        {{-- Card B: Instant Activation (Free / Manual Override) --}}
                        <label class="relative p-5 rounded-2xl border-2 cursor-pointer transition-all duration-200 flex flex-col justify-between" id="card-instant">
                            <input type="radio" name="assign_type" value="instant" class="sr-only" onchange="handleAssignTypeChange(this.value)">
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <span class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xl">
                                        <i class="ph-bold ph-lightning"></i>
                                    </span>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 text-[10px] font-black uppercase">
                                        Free / Admin Direct
                                    </span>
                                </div>
                                <h4 class="font-bold text-slate-900 dark:text-white text-base mb-1">Instant Activation</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                                    Immediately activates the plan on the user's account for free. Ideal for promotions, cash payments, or support VIPs.
                                </p>
                            </div>
                            <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center gap-2 text-xs font-bold text-slate-400" id="indicator-instant">
                                <i class="ph ph-circle text-base"></i>
                                <span>Click to select</span>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Section 4: Dynamic Custom Offer Settings --}}
                <div id="custom-offer-fields" class="p-6 rounded-2xl bg-blue-50/50 dark:bg-blue-950/20 border border-blue-100 dark:border-blue-900/50 space-y-6 transition-all">
                    <div class="flex items-center gap-2 text-xs font-black uppercase tracking-wider text-blue-700 dark:text-blue-300">
                        <i class="ph-bold ph-sliders"></i>
                        <span>Custom Offer Pricing & Period</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        {{-- Billing Period --}}
                        <div>
                            <label for="billing_period" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                                Billing Period / Purpose
                            </label>
                            <select name="billing_period" id="billing_period" required class="w-full px-4 py-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="monthly">Rent (Monthly Validity)</option>
                                <option value="yearly">Buy / Sale (Yearly Validity)</option>
                            </select>
                        </div>

                        {{-- Discounted Price --}}
                        <div>
                            <label for="discounted_price" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                                Custom Price (₹) <span class="text-slate-400 font-normal">(Optional)</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 font-bold">
                                    ₹
                                </div>
                                <input type="number" name="discounted_price" id="discounted_price" min="0" step="0.01" class="w-full pl-8 pr-4 py-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g. 299">
                            </div>
                            <p class="text-[11px] text-slate-500 mt-1.5">Leave blank to offer at the plan's standard retail price.</p>
                        </div>
                    </div>
                </div>

                {{-- Instant Mode Notice (Hidden by default) --}}
                <div id="instant-mode-notice" class="hidden p-5 rounded-2xl bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-900 text-xs text-emerald-800 dark:text-emerald-300 flex items-start gap-3">
                    <i class="ph-fill ph-check-circle text-lg mt-0.5 text-emerald-600 shrink-0"></i>
                    <div>
                        <strong class="block font-bold text-sm mb-0.5">Instant Activation Summary</strong>
                        The selected plan will be immediately activated upon submission. Any existing active plan for this user will be superseded, and the user will receive a confirmation email.
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="pt-6 border-t border-slate-200/80 dark:border-slate-800 flex flex-col-reverse sm:flex-row items-center justify-between gap-4">
                    <a href="{{ route('admin.subscriptions') }}" class="w-full sm:w-auto px-6 py-3.5 rounded-xl text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white text-sm font-bold text-center transition">
                        Cancel & Return
                    </a>
                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-8 py-3.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-bold shadow-lg shadow-blue-500/25 hover:shadow-xl hover:shadow-blue-500/30 transition-all cursor-pointer">
                        <i class="ph-bold ph-paper-plane-tilt text-base"></i>
                        <span id="submit-btn-text">Send Custom Offer</span>
                    </button>
                </div>

            </form>
        </div>

    </div>
</section>

{{-- Interactive Dynamic Script --}}
<script>
    function handleAssignTypeChange(type) {
        const cardCustom = document.getElementById('card-custom-offer');
        const cardInstant = document.getElementById('card-instant');
        const indCustom = document.getElementById('indicator-custom-offer');
        const indInstant = document.getElementById('indicator-instant');
        const customFields = document.getElementById('custom-offer-fields');
        const instantNotice = document.getElementById('instant-mode-notice');
        const submitText = document.getElementById('submit-btn-text');

        if (type === 'custom_offer') {
            cardCustom.className = 'relative p-5 rounded-2xl border-2 border-blue-600 bg-blue-50/20 dark:bg-blue-950/20 cursor-pointer transition-all duration-200 flex flex-col justify-between';
            cardInstant.className = 'relative p-5 rounded-2xl border-2 border-slate-200 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700 cursor-pointer transition-all duration-200 flex flex-col justify-between';
            indCustom.className = 'mt-4 pt-3 border-t border-blue-100 dark:border-blue-900/40 flex items-center gap-2 text-xs font-bold text-blue-600 dark:text-blue-400';
            indCustom.innerHTML = '<i class="ph-fill ph-check-circle text-base"></i><span>Selected</span>';
            indInstant.className = 'mt-4 pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center gap-2 text-xs font-bold text-slate-400';
            indInstant.innerHTML = '<i class="ph ph-circle text-base"></i><span>Click to select</span>';

            customFields.classList.remove('hidden');
            instantNotice.classList.add('hidden');
            submitText.innerText = 'Send Custom Offer';
        } else {
            cardInstant.className = 'relative p-5 rounded-2xl border-2 border-emerald-600 bg-emerald-50/20 dark:bg-emerald-950/20 cursor-pointer transition-all duration-200 flex flex-col justify-between';
            cardCustom.className = 'relative p-5 rounded-2xl border-2 border-slate-200 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700 cursor-pointer transition-all duration-200 flex flex-col justify-between';
            indInstant.className = 'mt-4 pt-3 border-t border-emerald-100 dark:border-emerald-900/40 flex items-center gap-2 text-xs font-bold text-emerald-600 dark:text-emerald-400';
            indInstant.innerHTML = '<i class="ph-fill ph-check-circle text-base"></i><span>Selected</span>';
            indCustom.className = 'mt-4 pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center gap-2 text-xs font-bold text-slate-400';
            indCustom.innerHTML = '<i class="ph ph-circle text-base"></i><span>Click to select</span>';

            customFields.classList.add('hidden');
            instantNotice.classList.remove('hidden');
            submitText.innerText = 'Activate Plan Instantly';
        }
    }

    function updatePlanDetails() {
        const planSelect = document.getElementById('plan_id');
        const selected = planSelect.options[planSelect.selectedIndex];
        if (selected && selected.value) {
            const price = selected.getAttribute('data-price');
            const discountInput = document.getElementById('discounted_price');
            if (!discountInput.value) {
                discountInput.placeholder = 'Default: ₹' + price;
            }
        }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        const checkedType = document.querySelector('input[name="assign_type"]:checked')?.value || 'custom_offer';
        handleAssignTypeChange(checkedType);
        updatePlanDetails();
    });
</script>
@endsection
