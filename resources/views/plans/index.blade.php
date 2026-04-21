@extends('layouts.app')

@section('title', 'Plans & Pricing - UnlockRentals')
@section('meta_description', 'Choose a plan to unlock property owner contacts on UnlockRentals.')

@section('content')

@php
    $paymentGatewayConfigured = filled($site_settings['payment_gateway_name'] ?? null)
        || filled($site_settings['payment_gateway_identifier'] ?? null)
        || filled($site_settings['payment_gateway_link'] ?? null)
        || filled($site_settings['payment_gateway_instructions'] ?? null)
        || filled($site_settings['payment_gateway_qr_url'] ?? null);
    $referenceLabel = $site_settings['payment_reference_label'] ?? 'Transaction ID / UTR Number';
@endphp

<section class="py-12 lg:py-20" id="plans-page">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="text-center mb-12">
            <h1 class="text-3xl lg:text-4xl font-medium text-zinc-900 mb-3">Choose Your Plan</h1>
            <p class="text-zinc-500 max-w-lg mx-auto">Unlock property owner contacts and connect directly. Choose a plan that suits your needs.</p>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
        <div class="mb-8 p-4 bg-emerald-50 border border-emerald-200 rounded-sm text-sm text-emerald-700">
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="mb-8 p-4 bg-red-50 border border-red-200 rounded-sm text-sm text-red-700">
            {{ session('error') }}
        </div>
        @endif
        @if($errors->has('payment_reference'))
        <div class="mb-8 p-4 bg-red-50 border border-red-200 rounded-sm text-sm text-red-700">
            {{ $errors->first('payment_reference') }}
        </div>
        @endif

        @if($paymentGatewayConfigured)
        <div class="mb-8 p-6 bg-stone-50 border border-stone-200/60 rounded-sm">
            <div class="flex flex-col lg:flex-row gap-6 lg:items-start lg:justify-between">
                <div class="flex-1">
                    <p class="text-[11px] font-bold text-zinc-400 uppercase tracking-[0.18em] mb-2">Payment Gateway</p>
                    <h2 class="text-xl font-semibold text-zinc-900 mb-3">{{ $site_settings['payment_gateway_name'] ?? 'Payment Details' }}</h2>

                    <div class="space-y-2 text-sm text-zinc-600">
                        @if(filled($site_settings['payment_gateway_account_name'] ?? null))
                        <p><span class="font-semibold text-zinc-900">Account Name:</span> {{ $site_settings['payment_gateway_account_name'] }}</p>
                        @endif
                        @if(filled($site_settings['payment_gateway_identifier'] ?? null))
                        <p><span class="font-semibold text-zinc-900">Pay To:</span> {{ $site_settings['payment_gateway_identifier'] }}</p>
                        @endif
                        @if(filled($site_settings['payment_gateway_instructions'] ?? null))
                        <div class="pt-2">
                            <p class="font-semibold text-zinc-900 mb-1">Instructions</p>
                            <div class="leading-6">{!! nl2br(e($site_settings['payment_gateway_instructions'])) !!}</div>
                        </div>
                        @endif
                    </div>

                    @if(filled($site_settings['payment_gateway_link'] ?? null))
                    <div class="mt-4">
                        <a href="{{ $site_settings['payment_gateway_link'] }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-4 py-2 bg-[#2563EB] text-white text-sm font-semibold rounded-sm hover:bg-[#1D4ED8] transition-all">
                            <i class="ph ph-arrow-square-out"></i> Open Payment Link
                        </a>
                    </div>
                    @endif
                </div>

                @if(filled($site_settings['payment_gateway_qr_url'] ?? null))
                <div class="w-full max-w-[180px]">
                    <div class="bg-white border border-stone-200 rounded-sm p-3">
                        <img src="{{ $site_settings['payment_gateway_qr_url'] }}" alt="{{ $site_settings['payment_gateway_name'] ?? 'Payment QR' }}" class="w-full h-auto rounded-sm object-cover">
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif

        {{-- Active Plan Banner --}}
        @auth
            @if($activePlan)
            <div class="mb-8 p-5 bg-emerald-50 border border-emerald-200 rounded-sm">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                    <div>
                        <h3 class="text-sm font-semibold text-emerald-800 flex items-center gap-2">
                            <i class="ph ph-check-circle text-lg"></i> Active Plan: {{ $activePlan->plan->name }}
                        </h3>
                        <p class="text-xs text-emerald-600 mt-1">
                            {{ $activePlan->remaining_contacts }} contact views remaining · Expires {{ $activePlan->expires_at->format('M d, Y') }}
                        </p>
                    </div>
                    <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full">ACTIVE</span>
                </div>
            </div>
            @elseif($pendingPlan)
            <div class="mb-8 p-5 bg-amber-50 border border-amber-200 rounded-sm">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                    <div>
                        <h3 class="text-sm font-semibold text-amber-800 flex items-center gap-2">
                            <i class="ph ph-clock text-lg"></i> Pending: {{ $pendingPlan->plan->name }}
                        </h3>
                        <p class="text-xs text-amber-600 mt-1">Your plan request is being reviewed by admin. You'll be notified once approved.</p>
                    </div>
                    <span class="px-3 py-1 bg-amber-100 text-amber-700 text-xs font-bold rounded-full">PENDING</span>
                </div>
            </div>
            @endif
        @endauth

        {{-- Plans Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($plans as $plan)
            <div class="bg-stone-50 border border-stone-200/50 rounded-sm overflow-hidden hover:border-[#c9a050]/40 transition-all {{ $loop->iteration === 2 ? 'ring-2 ring-[#c9a050]/30 relative' : '' }}">
                @if($loop->iteration === 2)
                <div class="absolute top-0 left-0 right-0 bg-[#c9a050] text-center py-1.5 text-white text-xs font-bold uppercase tracking-wider">
                    Most Popular
                </div>
                @endif

                <div class="p-6 {{ $loop->iteration === 2 ? 'pt-10' : '' }}">
                    <h3 class="text-lg font-semibold text-zinc-900 mb-1">{{ $plan->name }}</h3>
                    <p class="text-xs text-zinc-500 mb-4">{{ $plan->description }}</p>

                    <div class="mb-6">
                        <span class="text-3xl font-bold text-zinc-900">{{ $plan->formatted_price }}</span>
                        <span class="text-sm text-zinc-500">/ {{ $plan->duration_days }} days</span>
                    </div>

                    {{-- Features --}}
                    <ul class="space-y-3 mb-6">
                        <li class="flex items-center gap-2 text-sm text-zinc-700">
                            <i class="ph ph-check-circle text-emerald-500"></i>
                            <strong>{{ $plan->contact_limit }}</strong> owner contacts
                        </li>
                        @if($plan->features)
                            @foreach($plan->features as $feature)
                            <li class="flex items-center gap-2 text-sm text-zinc-700">
                                <i class="ph ph-check-circle text-emerald-500"></i>
                                {{ $feature }}
                            </li>
                            @endforeach
                        @endif
                        <li class="flex items-center gap-2 text-sm text-zinc-700">
                            <i class="ph ph-check-circle text-emerald-500"></i>
                            Valid for {{ $plan->duration_days }} days
                        </li>
                    </ul>

                    {{-- Action --}}
                    @auth
                        @if($activePlan && $activePlan->plan_id === $plan->id)
                            <button disabled class="w-full px-4 py-3 bg-emerald-100 text-emerald-700 text-sm font-semibold rounded-sm cursor-not-allowed">
                                Current Plan
                            </button>
                        @elseif($activePlan || $pendingPlan)
                            <button disabled class="w-full px-4 py-3 bg-stone-100 text-zinc-400 text-sm font-semibold rounded-sm cursor-not-allowed">
                                {{ $activePlan ? 'Already Subscribed' : 'Request Pending' }}
                            </button>
                        @else
                            <form method="POST" action="{{ route('plans.purchase', $plan) }}" onsubmit="this.querySelector('button').disabled=true; this.querySelector('.btn-text').classList.add('hidden'); this.querySelector('.btn-loader').classList.remove('hidden');">
                                @csrf
                                <button type="submit" class="w-full px-4 py-3 bg-[#c9a050] hover:bg-[#b08d42] text-white text-sm font-semibold rounded-sm transition-all flex items-center justify-center gap-3">
                                    <span class="btn-text">Get Started</span>
                                    <span class="btn-loader hidden flex items-center gap-2">
                                        <i class="ph ph-circle-notch animate-spin text-lg"></i> Loading...
                                    </span>
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="block w-full px-4 py-3 bg-[#c9a050] hover:bg-[#b08d42] text-white text-sm font-semibold rounded-sm text-center transition-all">
                            Sign In to Purchase
                        </a>
                    @endauth
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-16">
                <i class="ph ph-crown text-5xl text-zinc-300 mb-4"></i>
                <h3 class="text-lg font-semibold text-zinc-500 mb-2">No plans available</h3>
                <p class="text-zinc-400 text-sm">Plans will be available soon. Check back later!</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

@endsection
