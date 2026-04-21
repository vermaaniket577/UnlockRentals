@extends('layouts.app')

@section('title', 'Admin Dashboard - UnlockRentals')

@section('content')

<section class="py-8 lg:py-12" id="admin-dashboard">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-8">
            <h1 class="text-3xl font-medium text-zinc-900 mb-1">Admin Dashboard</h1>
            <p class="text-zinc-500">Overview of the UnlockRentals platform</p>
        </div>

        {{-- Stats Grid --}}
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
            <a href="{{ route('admin.users') }}" class="group bg-stone-50 border border-stone-200/50 rounded-sm p-5 hover:border-[#2563EB]/30 transition-all">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-[#2563EB]/10 rounded-sm flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="ph ph-users text-xl text-[#2563EB]"></i>
                    </div>
                    <span class="text-xs text-zinc-500 uppercase tracking-wider font-medium">Users</span>
                </div>
                <p class="text-2xl font-medium text-zinc-900">{{ $stats['total_users'] }}</p>
                <p class="text-xs text-zinc-500 mt-1">{{ $stats['total_owners'] }} owners · {{ $stats['total_tenants'] }} tenants</p>
            </a>
            <a href="{{ route('admin.properties') }}" class="group bg-stone-50 border border-stone-200/50 rounded-sm p-5 hover:border-indigo-600/30 transition-all">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-indigo-600/10 rounded-sm flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="ph ph-buildings text-xl text-indigo-500"></i>
                    </div>
                    <span class="text-xs text-zinc-500 uppercase tracking-wider font-medium">Properties</span>
                </div>
                <p class="text-2xl font-medium text-zinc-900">{{ $stats['total_properties'] }}</p>
                <p class="text-xs text-zinc-500 mt-1">{{ $stats['approved_properties'] }} approved</p>
            </a>
            <a href="{{ route('admin.properties', ['status' => 'pending']) }}" class="group bg-stone-50 border border-stone-200/50 rounded-sm p-5 hover:border-amber-500/30 transition-all">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-amber-500/10 rounded-sm flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="ph ph-clock-countdown text-xl text-amber-400"></i>
                    </div>
                    <span class="text-xs text-zinc-500 uppercase tracking-wider font-medium">Pending</span>
                </div>
                <p class="text-2xl font-medium text-zinc-900">{{ $stats['pending_properties'] }}</p>
                <p class="text-xs text-zinc-500 mt-1">Awaiting review</p>
            </a>
            <a href="{{ route('admin.feedback') }}" class="group bg-stone-50 border border-stone-200/50 rounded-sm p-5 hover:border-emerald-500/30 transition-all">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-emerald-500/10 rounded-sm flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="ph ph-chat-centered-text text-xl text-emerald-400"></i>
                    </div>
                    <span class="text-xs text-zinc-500 uppercase tracking-wider font-medium">Feedback</span>
                </div>
                <p class="text-2xl font-medium text-zinc-900">{{ $stats['total_feedback'] }}</p>
                <p class="text-xs text-zinc-500 mt-1">{{ $stats['new_feedback'] }} new submissions</p>
            </a>
            <a href="{{ route('admin.subscriptions') }}" class="group bg-stone-50 border border-stone-200/50 rounded-sm p-5 hover:border-[#c9a050]/50 transition-all">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-[#c9a050]/10 rounded-sm flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="ph ph-crown text-xl text-[#c9a050]"></i>
                    </div>
                    <span class="text-xs text-zinc-500 uppercase tracking-wider font-medium">Subscriptions</span>
                </div>
                <p class="text-2xl font-medium text-zinc-900">{{ $stats['active_subscriptions'] }}</p>
                <p class="text-xs text-zinc-500 mt-1">{{ $stats['pending_subscriptions'] }} pending approvals</p>
            </a>
        </div>
        </div>

        {{-- Quick Actions --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <a href="{{ route('admin.properties') }}" class="group p-5 bg-stone-50 border border-stone-200/50 rounded-sm hover:border-[#2563EB]/50 transition-all flex items-center gap-4">
                <div class="w-10 h-10 bg-[#2563EB]/10 rounded-sm flex items-center justify-center group-hover:bg-[#2563EB]/10 transition-all">
                    <i class="ph ph-list-checks text-xl text-[#2563EB]"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-zinc-900">Manage Properties</p>
                    <p class="text-xs text-zinc-500">Review & approve listings</p>
                </div>
            </a>
            <a href="{{ route('admin.users') }}" class="group p-5 bg-stone-50 border border-stone-200/50 rounded-sm hover:border-indigo-600/30 transition-all flex items-center gap-4">
                <div class="w-10 h-10 bg-indigo-600/10 rounded-sm flex items-center justify-center group-hover:bg-indigo-600/20 transition-all">
                    <i class="ph ph-users-three text-xl text-indigo-500"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-zinc-900">Manage Users</p>
                    <p class="text-xs text-zinc-500">View all platform users</p>
                </div>
            </a>
            <a href="{{ route('admin.plans') }}" class="group p-5 bg-stone-50 border border-stone-200/50 rounded-sm hover:border-[#c9a050]/50 transition-all flex items-center gap-4">
                <div class="w-10 h-10 bg-[#c9a050]/10 rounded-sm flex items-center justify-center group-hover:bg-[#c9a050]/20 transition-all">
                    <i class="ph ph-crown text-xl text-[#c9a050]"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-zinc-900">Manage Plans</p>
                    <p class="text-xs text-zinc-500">Create & edit pricing plans</p>
                </div>
            </a>
            <a href="{{ route('admin.subscriptions') }}" class="group p-5 bg-stone-50 border border-stone-200/50 rounded-sm hover:border-emerald-500/30 transition-all flex items-center gap-4">
                <div class="w-10 h-10 bg-emerald-500/10 rounded-sm flex items-center justify-center group-hover:bg-emerald-500/20 transition-all">
                    <i class="ph ph-receipt text-xl text-emerald-500"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-zinc-900">User Subscriptions</p>
                    <p class="text-xs text-zinc-500">{{ $stats['pending_subscriptions'] }} pending payments</p>
                </div>
            </a>
        </div>

        {{-- Pending Properties --}}
        @if($pendingProperties->count() > 0)
        <div class="bg-stone-50 border border-stone-200/50 rounded-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-stone-200/50">
                <h2 class="text-lg font-semibold text-zinc-900 flex items-center gap-2">
                    <span class="w-2 h-2 bg-amber-400 rounded-full animate-pulse"></span>
                    Pending Approvals
                </h2>
            </div>

            <div class="divide-y divide-white/5">
                @foreach($pendingProperties as $property)
                <div class="p-6 hover:bg-stone-50/[0.02] transition-colors">
                    <div class="flex flex-col sm:flex-row gap-4">
                        @if($property->primaryImage)
                            <img src="{{ $property->primaryImage->imageUrl() }}" class="w-full sm:w-24 h-32 sm:h-24 rounded-sm object-cover border border-stone-200/50 flex-shrink-0" alt="">
                        @else
                            <div class="w-full sm:w-24 h-32 sm:h-24 rounded-sm bg-stone-50 flex items-center justify-center flex-shrink-0">
                                <i class="ph ph-image text-2xl text-zinc-500"></i>
                        @endif

                        <div class="flex-1">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <h3 class="text-sm font-semibold text-zinc-900">{{ $property->title }}</h3>
                                    <p class="text-xs text-zinc-500 mt-0.5">
                                        {{ ucfirst($property->type) }} · {{ $property->location }} · {{ $property->formatted_price }}
                                    </p>
                                    <p class="text-xs text-zinc-500 mt-1">By: {{ $property->owner->name ?? 'Unknown' }} · {{ $property->created_at->diffForHumans() }}</p>
                                </div>
                            </div>

                            <div class="flex gap-2 mt-3">
                                <form method="POST" action="{{ route('admin.properties.approve', $property) }}">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1 px-4 py-2 bg-emerald-500/10 text-emerald-400 text-xs font-semibold rounded-sm hover:bg-emerald-500/20 transition-all border border-emerald-500/20">
                                        <i class="ph ph-check-circle"></i> Approve
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.properties.reject', $property) }}">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1 px-4 py-2 bg-red-500/10 text-red-400 text-xs font-semibold rounded-sm hover:bg-red-500/20 transition-all border border-red-500/20">
                                        <i class="ph ph-x-circle"></i> Reject
                                    </button>
                                </form>
                                <a href="{{ route('properties.show', $property) }}" class="inline-flex items-center gap-1 px-4 py-2 bg-stone-50 text-zinc-500 text-xs font-semibold rounded-sm hover:bg-stone-100 transition-all border border-stone-200/50">
                                    <i class="ph ph-eye"></i> View
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Pending Subscriptions --}}
        @if($pendingSubscriptions->count() > 0)
        <div class="bg-stone-50 border border-stone-200/50 rounded-sm overflow-hidden mt-8">
            <div class="px-6 py-5 border-b border-stone-200/50">
                <h2 class="text-lg font-semibold text-zinc-900 flex items-center gap-2">
                    <span class="w-2 h-2 bg-[#c9a050] rounded-full animate-pulse"></span>
                    Pending Subscriptions
                </h2>
            </div>

            <div class="divide-y border-t border-stone-200/50">
                @foreach($pendingSubscriptions as $subscription)
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div>
                            <h3 class="text-sm font-semibold text-zinc-900">{{ $subscription->user->name ?? 'Unknown User' }} requested <span class="text-[#c9a050]">{{ $subscription->plan->name }}</span></h3>
                            <p class="text-xs text-zinc-500 mt-1">
                                Reference: <span class="font-mono text-zinc-700 bg-stone-100 px-1 py-0.5 rounded-sm">{{ $subscription->payment_reference ?? 'None' }}</span>
                                · Amount: {{ $subscription->plan->formatted_price }}
                            </p>
                            <p class="text-xs text-zinc-400 mt-1">{{ $subscription->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="flex gap-2">
                            <form method="POST" action="{{ route('admin.subscriptions.approve', $subscription) }}">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-emerald-500/10 text-emerald-400 text-xs font-semibold rounded-sm hover:bg-emerald-500/20 border border-emerald-500/20 transition-all">
                                    Approve Payment
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.subscriptions.reject', $subscription) }}">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-red-500/10 text-red-500 text-xs font-semibold rounded-sm hover:bg-red-500/20 border border-red-500/20 transition-all">
                                    Reject
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>

@endsection
