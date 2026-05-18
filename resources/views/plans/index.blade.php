@extends('layouts.app')

@section('title', 'Plans & Pricing - UnlockRentals')
@section('meta_description', 'Choose a premium subscription plan to unlock verified property owner contacts instantly.')

@section('content')

@php
    $paymentGatewayConfigured = filled($site_settings['payment_gateway_name'] ?? null)
        || filled($site_settings['payment_gateway_identifier'] ?? null)
        || filled($site_settings['payment_gateway_link'] ?? null)
        || filled($site_settings['payment_gateway_instructions'] ?? null)
        || filled($site_settings['payment_gateway_qr_url'] ?? null);
    $referenceLabel = $site_settings['payment_reference_label'] ?? 'Transaction ID / UTR Number';
@endphp

<section class="relative overflow-hidden pt-32 pb-24 lg:pt-40 lg:pb-32 bg-stone-50/50" id="plans-page">
    {{-- High-end background glowing circles --}}
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <div class="absolute top-[-20%] left-[-10%] w-[600px] h-[600px] bg-blue-500/5 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[500px] h-[500px] bg-purple-500/5 rounded-full blur-[100px]"></div>
    </div>

    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header Section --}}
        <div class="text-center mb-16 max-w-xl mx-auto">
            <span class="text-[10px] font-bold text-[#2563EB] uppercase tracking-[0.25em] block mb-3 font-sans">Premium Access</span>
            <h1 class="text-4xl lg:text-5xl font-extrabold text-zinc-900 tracking-tight leading-[1.15] mb-4 font-serif">
                Choose Your <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#2563EB] to-indigo-600 font-serif italic font-normal">Membership Plan</span>
            </h1>
            <p class="text-sm text-zinc-500 leading-relaxed font-light">
                Connect directly with property owners and unlock verified listings instantly. Select a flexible, premium plan that fits your search duration.
            </p>
        </div>

        {{-- Dynamic Status & Notification Banners --}}
        @if(session('success'))
        <div class="mb-10 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-sm text-emerald-800 shadow-sm flex items-center gap-3 backdrop-blur-sm">
            <i class="ph-fill ph-check-circle text-xl text-emerald-500"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
        @endif
        @if(session('error'))
        <div class="mb-10 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-800 shadow-sm flex items-center gap-3 backdrop-blur-sm">
            <i class="ph-fill ph-warning-circle text-xl text-red-500"></i>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
        @endif
        @if($errors->has('payment_reference'))
        <div class="mb-10 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-800 shadow-sm flex items-center gap-3 backdrop-blur-sm">
            <i class="ph-fill ph-warning text-xl text-red-500"></i>
            <span class="font-medium">{{ $errors->first('payment_reference') }}</span>
        </div>
        @endif

        {{-- Active Plan Banner --}}
        @auth
            @if($activePlan)
            <div class="mb-12 p-6 bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200 rounded-2xl shadow-sm backdrop-blur-sm">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-emerald-500/10 rounded-xl flex items-center justify-center text-emerald-600 flex-shrink-0">
                            <i class="ph-fill ph-sparkle text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-zinc-900 flex items-center gap-2">
                                Active Membership: <span class="text-emerald-700 font-extrabold">{{ $activePlan->plan->name }}</span>
                            </h3>
                            <p class="text-xs text-zinc-500 mt-1">
                                You have <strong class="text-emerald-600 font-bold">{{ $activePlan->remaining_contacts }} contact views</strong> remaining. Valid until <strong class="text-zinc-700 font-bold">{{ $activePlan->expires_at->format('M d, Y') }}</strong>.
                            </p>
                        </div>
                    </div>
                    <span class="px-3.5 py-1.5 bg-emerald-600 text-white text-[10px] font-extrabold rounded-full tracking-wider shadow-sm uppercase">Currently Active</span>
                </div>
            </div>
            @elseif($pendingPlan)
            <div class="mb-12 p-6 bg-gradient-to-r from-amber-50 to-yellow-50 border border-amber-200 rounded-2xl shadow-sm backdrop-blur-sm">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-amber-500/10 rounded-xl flex items-center justify-center text-amber-600 flex-shrink-0">
                            <i class="ph-fill ph-clock text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-zinc-900">
                                Request Pending: <span class="text-amber-700 font-extrabold">{{ $pendingPlan->plan->name }}</span>
                            </h3>
                            <p class="text-xs text-zinc-500 mt-1">Your payment verification request is being reviewed by our administration. We will activate your account shortly.</p>
                        </div>
                    </div>
                    <span class="px-3.5 py-1.5 bg-amber-500 text-white text-[10px] font-extrabold rounded-full tracking-wider shadow-sm uppercase">Pending Review</span>
                </div>
            </div>
            @endif
        @endauth

        {{-- Payment Gateway Details (Glassmorphic Accent Block) --}}
        @if($paymentGatewayConfigured)
        <div class="mb-14 p-8 bg-white border border-stone-200 rounded-2xl shadow-sm relative overflow-hidden">
            <div class="absolute top-0 left-0 bottom-0 w-[5px] bg-gradient-to-b from-[#2563EB] to-indigo-600"></div>
            
            <div class="flex flex-col lg:flex-row gap-8 lg:items-center lg:justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="px-2.5 py-1 bg-blue-50 text-blue-600 rounded-md text-[9px] font-extrabold uppercase tracking-widest">Gateway Configured</span>
                    </div>
                    
                    <h2 class="text-2xl font-bold text-zinc-900 tracking-tight mb-4 flex items-center gap-2 font-sans">
                        <i class="ph ph-shield-check text-[#2563EB]"></i>
                        {{ $site_settings['payment_gateway_name'] ?? 'UPI / Direct Bank Transfer' }}
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-zinc-600 pt-1">
                        @if(filled($site_settings['payment_gateway_account_name'] ?? null))
                        <div class="bg-stone-50 p-3 rounded-lg border border-stone-200">
                            <p class="text-[10px] text-zinc-400 font-bold uppercase tracking-wider mb-0.5">Account Holder</p>
                            <p class="font-bold text-zinc-900 text-sm">{{ $site_settings['payment_gateway_account_name'] }}</p>
                        </div>
                        @endif
                        @if(filled($site_settings['payment_gateway_identifier'] ?? null))
                        <div class="bg-stone-50 p-3 rounded-lg border border-stone-200">
                            <p class="text-[10px] text-zinc-400 font-bold uppercase tracking-wider mb-0.5">UPI ID / Bank Account</p>
                            <p class="font-bold text-zinc-900 text-sm copyable cursor-pointer flex items-center gap-1.5" onclick="navigator.clipboard.writeText('{{ $site_settings['payment_gateway_identifier'] }}'); alert('Copied: {{ $site_settings['payment_gateway_identifier'] }}')">
                                <span>{{ $site_settings['payment_gateway_identifier'] }}</span>
                                <i class="ph ph-copy text-[#2563EB]"></i>
                            </p>
                        </div>
                        @endif
                    </div>

                    @if(filled($site_settings['payment_gateway_instructions'] ?? null))
                    <div class="mt-5 pt-4 border-t border-stone-200">
                        <p class="text-[10px] text-zinc-400 font-bold uppercase tracking-wider mb-2">Transfer Instructions</p>
                        <div class="text-xs leading-relaxed text-zinc-500 font-mono tracking-tight">{!! nl2br(e($site_settings['payment_gateway_instructions'])) !!}</div>
                    </div>
                    @endif

                    @if(filled($site_settings['payment_gateway_link'] ?? null))
                    <div class="mt-6">
                        <a href="{{ $site_settings['payment_gateway_link'] }}" target="_blank" rel="noopener noreferrer" 
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-[#2563EB] to-indigo-600 text-white text-xs font-bold uppercase tracking-wider rounded-lg shadow-md shadow-[#2563EB]/15 hover:shadow-lg hover:shadow-[#2563EB]/30 transition-all duration-300">
                            <i class="ph ph-arrow-square-out text-sm"></i> 
                            <span>Proceed to Payment</span>
                        </a>
                    </div>
                    @endif
                </div>

                @if(filled($site_settings['payment_gateway_qr_url'] ?? null))
                <div class="w-full max-w-[190px] mx-auto lg:mx-0 flex-shrink-0">
                    <div class="bg-stone-50 border border-stone-200 rounded-2xl p-4 shadow-sm text-center">
                        <img src="{{ $site_settings['payment_gateway_qr_url'] }}" alt="{{ $site_settings['payment_gateway_name'] ?? 'Payment QR' }}" class="w-full h-auto rounded-xl object-cover mb-2 border border-stone-200">
                        <span class="text-[9px] font-bold text-zinc-400 uppercase tracking-widest">Scan QR code to pay</span>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif

        {{-- Pricing Grid Section --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">
            @forelse($plans as $plan)
                @php
                    $isPopular = ($loop->iteration === 2);
                @endphp
                <div class="flex flex-col bg-white border rounded-2xl transition-all duration-300 transform hover:scale-[1.02] hover:shadow-2xl relative overflow-hidden {{ $isPopular ? 'border-amber-400 shadow-xl ring-2 ring-amber-400/30' : 'border-stone-200 shadow-sm hover:border-[#2563EB]/30' }}">
                    
                    {{-- Popular Badge --}}
                    @if($isPopular)
                    <div class="absolute top-0 left-0 right-0 bg-gradient-to-r from-amber-500 to-[#c9a050] text-center py-2 text-white text-[10px] font-extrabold uppercase tracking-[0.2em] shadow-sm">
                        Most Popular Choice
                    </div>
                    @endif

                    <div class="p-8 flex flex-col flex-1 {{ $isPopular ? 'pt-12' : '' }}">
                        {{-- Icon Indicator --}}
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-6 {{ $isPopular ? 'bg-amber-500/10 text-[#c9a050]' : 'bg-blue-50 text-[#2563EB]' }}">
                            <i class="ph-bold {{ $loop->iteration === 1 ? 'ph-sparkle' : ($loop->iteration === 2 ? 'ph-crown' : 'ph-lightning') }} text-2xl"></i>
                        </div>

                        <h3 class="text-xl font-bold text-zinc-900 mb-1 leading-tight font-sans">{{ $plan->name }}</h3>
                        <p class="text-xs text-zinc-400 mb-6 font-medium leading-relaxed font-light">{{ $plan->description }}</p>

                        {{-- Pricing display --}}
                        <div class="mb-8 pb-6 border-b border-stone-100 flex items-baseline gap-1">
                            <span class="text-4xl font-extrabold text-zinc-900 tracking-tight">₹{{ number_format($plan->price, 0) }}</span>
                            <span class="text-xs text-zinc-400 font-mono">/ {{ $plan->duration_days }} Days</span>
                        </div>

                        {{-- Features checklist --}}
                        <ul class="space-y-4 mb-8 flex-1">
                            <li class="flex items-start gap-3 text-sm text-zinc-600">
                                <div class="w-5 h-5 rounded-full bg-emerald-500/10 flex items-center justify-center text-emerald-600 flex-shrink-0 mt-0.5">
                                    <i class="ph-bold ph-check text-xs"></i>
                                </div>
                                <span class="leading-tight font-light"><strong class="text-zinc-900 font-semibold">{{ $plan->contact_limit }}</strong> Owner Contact Views</span>
                            </li>
                            @if($plan->features)
                                @foreach($plan->features as $feature)
                                <li class="flex items-start gap-3 text-sm text-zinc-600">
                                    <div class="w-5 h-5 rounded-full bg-emerald-500/10 flex items-center justify-center text-emerald-600 flex-shrink-0 mt-0.5">
                                        <i class="ph-bold ph-check text-xs"></i>
                                    </div>
                                    <span class="leading-tight font-light">{{ $feature }}</span>
                                </li>
                                @endforeach
                            @endif
                            <li class="flex items-start gap-3 text-sm text-zinc-500">
                                <div class="w-5 h-5 rounded-full bg-stone-100 flex items-center justify-center text-zinc-400 flex-shrink-0 mt-0.5">
                                    <i class="ph-bold ph-check text-xs"></i>
                                </div>
                                <span class="leading-tight font-light">Valid for {{ $plan->duration_days }} days</span>
                            </li>
                        </ul>

                        {{-- Actions Button Area --}}
                        @auth
                            @if($activePlan && $activePlan->plan_id === $plan->id)
                                <button disabled class="w-full py-3.5 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-bold uppercase tracking-wider rounded-lg cursor-not-allowed">
                                    <i class="ph-fill ph-check-circle text-base"></i> Active Plan
                                </button>
                            @elseif($activePlan || $pendingPlan)
                                <button disabled class="w-full py-3.5 bg-stone-50 border border-stone-200 text-zinc-400 text-xs font-bold uppercase tracking-wider rounded-lg cursor-not-allowed">
                                    {{ $activePlan ? 'Already Subscribed' : 'Request Pending' }}
                                </button>
                            @else
                                <form method="POST" action="{{ route('plans.purchase', $plan) }}" class="w-full" onsubmit="this.querySelector('button').disabled=true; this.querySelector('.btn-text').classList.add('hidden'); this.querySelector('.btn-loader').classList.remove('hidden');">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full py-3.5 bg-gradient-to-r {{ $isPopular ? 'from-amber-500 to-[#c9a050] hover:from-amber-600 hover:to-[#b08d42] shadow-amber-500/10' : 'from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 shadow-blue-500/10' }} text-white text-xs font-extrabold uppercase tracking-wider rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center gap-2">
                                        <span class="btn-text">Choose Plan</span>
                                        <span class="btn-loader hidden flex items-center gap-2">
                                            <i class="ph ph-circle-notch animate-spin text-sm"></i> Processing...
                                        </span>
                                    </button>
                                </form>
                            @endif
                        @else
                            <a href="{{ route('login') }}" 
                               class="block w-full py-3.5 bg-gradient-to-r {{ $isPopular ? 'from-amber-500 to-[#c9a050] hover:from-amber-600' : 'from-blue-600 to-indigo-600 hover:from-blue-700' }} text-white text-xs font-extrabold uppercase tracking-wider rounded-lg text-center shadow-lg transition-all duration-300">
                                Sign In to Subscribe
                            </a>
                        @endauth
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-20 bg-white border border-stone-200 rounded-3xl shadow-sm">
                    <div class="w-16 h-16 bg-stone-100 rounded-full flex items-center justify-center mx-auto mb-4 text-zinc-400">
                        <i class="ph ph-crown text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-zinc-900 mb-1">No Membership Plans</h3>
                    <p class="text-sm text-zinc-400 max-w-sm mx-auto">Plans are currently being configured by our admin. Please check back later!</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

@endsection
