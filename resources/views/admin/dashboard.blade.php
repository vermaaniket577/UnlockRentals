@extends('layouts.admin')

@section('title', 'Admin CRM Dashboard - UnlockRentals')
@section('topbar_title', 'Overview')

@section('content')
<div class="max-w-7xl mx-auto space-y-8" id="admin-dashboard">

    {{-- Welcome Hero & Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Admin Overview</h1>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Live System
                </span>
            </div>
            <p class="text-sm text-slate-500 mt-1">Real-time platform analytics, property approvals, and monetization control.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.properties', ['status' => 'pending']) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold transition-all shadow-sm shadow-amber-500/20 active:scale-95">
                <i class="ph-bold ph-clock-countdown text-base"></i>
                <span>Review Pending ({{ $stats['pending_properties'] ?? 0 }})</span>
            </a>
            <a href="{{ route('properties.create') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-all shadow-sm shadow-blue-600/20 active:scale-95">
                <i class="ph-bold ph-plus-circle text-base"></i>
                <span>Add Property</span>
            </a>
        </div>
    </div>

    {{-- Urgent Notification Banners --}}
    @if(isset($adminNotifications) && $adminNotifications['total_unread'] > 0)
    <div class="space-y-3">
        @if($adminNotifications['new_callbacks'] > 0)
        <div class="bg-rose-50/80 border border-rose-200/90 rounded-2xl p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-xs">
            <div class="flex items-center gap-3 text-rose-900">
                <div class="w-10 h-10 rounded-xl bg-rose-500 text-white flex items-center justify-center shadow-xs flex-shrink-0">
                    <i class="ph-bold ph-phone-call text-xl"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-rose-900">New Callback Requests</h4>
                    <p class="text-xs text-rose-700">You have <span class="font-bold">{{ $adminNotifications['new_callbacks'] }}</span> new callback leads awaiting contact.</p>
                </div>
            </div>
            <a href="{{ route('admin.callbacks') }}" class="text-xs font-bold bg-rose-600 hover:bg-rose-700 text-white px-4 py-2 rounded-xl transition-all shadow-xs flex-shrink-0">
                View Callbacks →
            </a>
        </div>
        @endif

        @if($adminNotifications['unread_chats'] > 0)
        <div class="bg-amber-50/80 border border-amber-200/90 rounded-2xl p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-xs">
            <div class="flex items-center gap-3 text-amber-900">
                <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center shadow-xs flex-shrink-0">
                    <i class="ph-bold ph-chat-circle-dots text-xl"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-amber-900">Unread User Inquiries</h4>
                    <p class="text-xs text-amber-700">You have <span class="font-bold">{{ $adminNotifications['unread_chats'] }}</span> unread chat messages.</p>
                </div>
            </div>
            <a href="{{ route('admin.chats') }}" class="text-xs font-bold bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-xl transition-all shadow-xs flex-shrink-0">
                Open Chat Inbox →
            </a>
        </div>
        @endif

        @if($adminNotifications['new_feedbacks'] > 0)
        <div class="bg-blue-50/80 border border-blue-200/90 rounded-2xl p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-xs">
            <div class="flex items-center gap-3 text-blue-900">
                <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shadow-xs flex-shrink-0">
                    <i class="ph-bold ph-chat-centered-text text-xl"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-blue-900">Customer Feedback</h4>
                    <p class="text-xs text-blue-700">You have <span class="font-bold">{{ $adminNotifications['new_feedbacks'] }}</span> new feedback reviews.</p>
                </div>
            </div>
            <a href="{{ route('admin.feedback') }}" class="text-xs font-bold bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl transition-all shadow-xs flex-shrink-0">
                View Feedback →
            </a>
        </div>
        @endif
    </div>
    @endif

    {{-- 5 High-Impact Metric Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        
        {{-- Card 1: Users --}}
        <a href="{{ route('admin.users') }}" class="group bg-white p-5 rounded-2xl border border-slate-200/80 hover:border-blue-500/50 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Users</span>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white flex items-center justify-center transition-colors">
                    <i class="ph-bold ph-users text-lg"></i>
                </div>
            </div>
            <div class="text-2xl font-extrabold text-slate-900 group-hover:text-blue-600 transition-colors">
                {{ $stats['total_users'] }}
            </div>
            <div class="flex items-center gap-2 mt-2 text-[11px] font-semibold text-slate-500">
                <span class="text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded">{{ $stats['total_owners'] }} Owners</span>
                <span class="text-indigo-600 bg-indigo-50 px-1.5 py-0.5 rounded">{{ $stats['total_tenants'] }} Tenants</span>
            </div>
        </a>

        {{-- Card 2: Properties --}}
        <a href="{{ route('admin.properties') }}" class="group bg-white p-5 rounded-2xl border border-slate-200/80 hover:border-indigo-500/50 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Properties</span>
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white flex items-center justify-center transition-colors">
                    <i class="ph-bold ph-buildings text-lg"></i>
                </div>
            </div>
            <div class="text-2xl font-extrabold text-slate-900 group-hover:text-indigo-600 transition-colors">
                {{ $stats['total_properties'] }}
            </div>
            <div class="flex items-center gap-1.5 mt-2 text-[11px] font-semibold text-emerald-600">
                <i class="ph-bold ph-check-circle"></i>
                <span>{{ $stats['approved_properties'] }} Approved Listings</span>
            </div>
        </a>

        {{-- Card 3: Pending Approvals --}}
        <a href="{{ route('admin.properties', ['status' => 'pending']) }}" class="group bg-white p-5 rounded-2xl border border-slate-200/80 hover:border-amber-500/50 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Pending Review</span>
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 group-hover:bg-amber-600 group-hover:text-white flex items-center justify-center transition-colors">
                    <i class="ph-bold ph-clock-countdown text-lg"></i>
                </div>
            </div>
            <div class="text-2xl font-extrabold text-slate-900 group-hover:text-amber-600 transition-colors">
                {{ $stats['pending_properties'] }}
            </div>
            <div class="flex items-center gap-1.5 mt-2 text-[11px] font-semibold {{ $stats['pending_properties'] > 0 ? 'text-amber-600' : 'text-slate-400' }}">
                @if($stats['pending_properties'] > 0)
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                    <span>Needs Attention</span>
                @else
                    <span>All Caught Up</span>
                @endif
            </div>
        </a>

        {{-- Card 4: Feedback --}}
        <a href="{{ route('admin.feedback') }}" class="group bg-white p-5 rounded-2xl border border-slate-200/80 hover:border-emerald-500/50 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">User Feedback</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white flex items-center justify-center transition-colors">
                    <i class="ph-bold ph-chat-centered-text text-lg"></i>
                </div>
            </div>
            <div class="text-2xl font-extrabold text-slate-900 group-hover:text-emerald-600 transition-colors">
                {{ $stats['total_feedback'] }}
            </div>
            <div class="flex items-center gap-1.5 mt-2 text-[11px] font-semibold text-slate-500">
                <span class="text-emerald-600 font-bold">{{ $stats['new_feedback'] }}</span>
                <span>New Submissions</span>
            </div>
        </a>

        {{-- Card 5: Subscriptions --}}
        <a href="{{ route('admin.subscriptions') }}" class="group bg-white p-5 rounded-2xl border border-slate-200/80 hover:border-purple-500/50 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Subscriptions</span>
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 group-hover:bg-purple-600 group-hover:text-white flex items-center justify-center transition-colors">
                    <i class="ph-bold ph-crown text-lg"></i>
                </div>
            </div>
            <div class="text-2xl font-extrabold text-slate-900 group-hover:text-purple-600 transition-colors">
                {{ $stats['active_subscriptions'] }}
            </div>
            <div class="flex items-center gap-1.5 mt-2 text-[11px] font-semibold text-purple-600">
                <span>{{ $stats['pending_subscriptions'] }} Pending Approvals</span>
            </div>
        </a>

    </div>

    {{-- Quick Management Shortcuts Grid --}}
    <div>
        <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-widest mb-3">Quick Navigation</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <a href="{{ route('admin.properties') }}" class="group bg-white p-5 rounded-2xl border border-slate-200/80 hover:border-blue-500/40 hover:shadow-md transition-all flex items-center justify-between">
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <i class="ph-bold ph-list-checks"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 group-hover:text-blue-600 transition-colors">Properties</h4>
                        <p class="text-xs text-slate-400">Review & approve listings</p>
                    </div>
                </div>
                <i class="ph-bold ph-arrow-right text-slate-300 group-hover:text-blue-600 group-hover:translate-x-1 transition-all"></i>
            </a>

            <a href="{{ route('admin.users') }}" class="group bg-white p-5 rounded-2xl border border-slate-200/80 hover:border-indigo-500/40 hover:shadow-md transition-all flex items-center justify-between">
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                        <i class="ph-bold ph-users-three"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">Users Directory</h4>
                        <p class="text-xs text-slate-400">View & manage all users</p>
                    </div>
                </div>
                <i class="ph-bold ph-arrow-right text-slate-300 group-hover:text-indigo-600 group-hover:translate-x-1 transition-all"></i>
            </a>

            <a href="{{ route('admin.plans') }}" class="group bg-white p-5 rounded-2xl border border-slate-200/80 hover:border-amber-500/40 hover:shadow-md transition-all flex items-center justify-between">
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl group-hover:bg-amber-600 group-hover:text-white transition-colors">
                        <i class="ph-bold ph-crown"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 group-hover:text-amber-600 transition-colors">Pricing Plans</h4>
                        <p class="text-xs text-slate-400">Create & edit packages</p>
                    </div>
                </div>
                <i class="ph-bold ph-arrow-right text-slate-300 group-hover:text-amber-600 group-hover:translate-x-1 transition-all"></i>
            </a>

            <a href="{{ route('admin.blogs.index') }}" class="group bg-white p-5 rounded-2xl border border-slate-200/80 hover:border-emerald-500/40 hover:shadow-md transition-all flex items-center justify-between">
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                        <i class="ph-bold ph-newspaper"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 group-hover:text-emerald-600 transition-colors">Blog Articles</h4>
                        <p class="text-xs text-slate-400">{{ $stats['total_blogs'] ?? 0 }} articles ({{ $stats['published_blogs'] ?? 0 }} live)</p>
                    </div>
                </div>
                <i class="ph-bold ph-arrow-right text-slate-300 group-hover:text-emerald-600 group-hover:translate-x-1 transition-all"></i>
            </a>

        </div>
    </div>

    {{-- Pending Properties Approval Section --}}
    @if($pendingProperties->count() > 0)
    <div class="bg-white rounded-3xl border border-slate-200/80 overflow-hidden shadow-xs">
        <div class="px-6 py-4.5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center gap-2.5">
                <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span>
                <h3 class="text-base font-extrabold text-slate-900">Pending Property Approvals</h3>
                <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800">{{ $pendingProperties->count() }}</span>
            </div>
            <a href="{{ route('admin.properties', ['status' => 'pending']) }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1">
                View All Pending →
            </a>
        </div>

        <div class="divide-y divide-slate-100">
            @foreach($pendingProperties as $property)
            <div class="p-5 sm:p-6 hover:bg-slate-50/60 transition-colors">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5">
                    
                    {{-- Property Info Left --}}
                    <div class="flex items-center gap-4">
                        @if($property->primaryImage)
                            <img src="{{ $property->primaryImage->imageUrl() }}" class="w-16 h-16 rounded-2xl object-cover border border-slate-200 shadow-xs flex-shrink-0" alt="">
                        @else
                            <div class="w-16 h-16 rounded-2xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400 flex-shrink-0">
                                <i class="ph-bold ph-image text-2xl"></i>
                            </div>
                        @endif

                        <div>
                            <div class="flex items-center gap-2">
                                <h4 class="text-sm font-bold text-slate-900 hover:text-blue-600 transition-colors">
                                    <a href="{{ route('properties.show', $property) }}" target="_blank">{{ $property->title }}</a>
                                </h4>
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200 uppercase">{{ $property->type }}</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-1 flex items-center gap-2">
                                <span class="font-bold text-slate-800">{{ $property->formatted_price }}</span>
                                <span>·</span>
                                <span><i class="ph ph-map-pin"></i> {{ $property->location }}</span>
                            </p>
                            <p class="text-[11px] text-slate-400 mt-0.5">
                                Posted by: <span class="font-semibold text-slate-600">{{ $property->owner->name ?? 'User' }}</span> · {{ $property->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>

                    {{-- Action Buttons Right --}}
                    <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                        <a href="{{ route('properties.show', $property) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-all">
                            <i class="ph-bold ph-eye text-sm"></i>
                            <span>Preview</span>
                        </a>

                        <form method="POST" action="{{ route('admin.properties.approve', $property) }}" class="inline-block">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition-all shadow-xs active:scale-95">
                                <i class="ph-bold ph-check text-sm"></i>
                                <span>Approve</span>
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.properties.reject', $property) }}" class="inline-block" onsubmit="return confirm('Are you sure you want to reject this property?');">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white border border-rose-200 hover:border-rose-600 text-xs font-bold rounded-xl transition-all">
                                <i class="ph-bold ph-x text-sm"></i>
                                <span>Reject</span>
                            </button>
                        </form>
                    </div>

                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Pending Subscriptions Approval Section --}}
    @if($pendingSubscriptions->count() > 0)
    <div class="bg-white rounded-3xl border border-slate-200/80 overflow-hidden shadow-xs">
        <div class="px-6 py-4.5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center gap-2.5">
                <span class="w-2.5 h-2.5 rounded-full bg-purple-500 animate-pulse"></span>
                <h3 class="text-base font-extrabold text-slate-900">Pending Subscription Payments</h3>
                <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-purple-100 text-purple-800">{{ $pendingSubscriptions->count() }}</span>
            </div>
            <a href="{{ route('admin.subscriptions') }}" class="text-xs font-bold text-purple-600 hover:text-purple-700 flex items-center gap-1">
                View All Subscriptions →
            </a>
        </div>

        <div class="divide-y divide-slate-100">
            @foreach($pendingSubscriptions as $subscription)
            <div class="p-5 sm:p-6 hover:bg-slate-50/60 transition-colors">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <h4 class="text-sm font-bold text-slate-900">{{ $subscription->user->name ?? 'User' }}</h4>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-purple-50 text-purple-700 border border-purple-200">
                                {{ $subscription->plan->name ?? 'Plan' }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">
                            Reference: <span class="font-mono font-bold text-slate-700 bg-slate-100 px-1.5 py-0.5 rounded">{{ $subscription->payment_reference ?? 'Direct Order' }}</span>
                            · Amount: <span class="font-bold text-slate-900">{{ $subscription->plan->formatted_price ?? '₹0' }}</span>
                        </p>
                        <p class="text-[11px] text-slate-400 mt-0.5">{{ $subscription->created_at->diffForHumans() }}</p>
                    </div>

                    <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                        <form method="POST" action="{{ route('admin.subscriptions.approve', $subscription) }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition-all shadow-xs active:scale-95">
                                <i class="ph-bold ph-check text-sm"></i>
                                <span>Approve Payment</span>
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.subscriptions.reject', $subscription) }}" onsubmit="return confirm('Reject this subscription payment?');">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white border border-rose-200 hover:border-rose-600 text-xs font-bold rounded-xl transition-all">
                                <i class="ph-bold ph-x text-sm"></i>
                                <span>Reject</span>
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
@endsection
