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
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-3.5">
            
            <a href="{{ route('admin.properties') }}" class="group bg-white p-4 rounded-2xl border border-slate-200/80 hover:border-blue-500/40 hover:shadow-md transition-all flex items-center justify-between">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg group-hover:bg-blue-600 group-hover:text-white transition-colors flex-shrink-0">
                        <i class="ph-bold ph-list-checks"></i>
                    </div>
                    <div class="min-w-0">
                        <h4 class="text-xs sm:text-sm font-bold text-slate-900 group-hover:text-blue-600 transition-colors truncate">Properties</h4>
                        <p class="text-[11px] text-slate-400 truncate">Review listings</p>
                    </div>
                </div>
                <i class="ph-bold ph-arrow-right text-slate-300 group-hover:text-blue-600 group-hover:translate-x-1 transition-all flex-shrink-0"></i>
            </a>

            <a href="{{ route('admin.users') }}" class="group bg-white p-4 rounded-2xl border border-slate-200/80 hover:border-indigo-500/40 hover:shadow-md transition-all flex items-center justify-between">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg group-hover:bg-indigo-600 group-hover:text-white transition-colors flex-shrink-0">
                        <i class="ph-bold ph-users-three"></i>
                    </div>
                    <div class="min-w-0">
                        <h4 class="text-xs sm:text-sm font-bold text-slate-900 group-hover:text-indigo-600 transition-colors truncate">Users Directory</h4>
                        <p class="text-[11px] text-slate-400 truncate">Manage users</p>
                    </div>
                </div>
                <i class="ph-bold ph-arrow-right text-slate-300 group-hover:text-indigo-600 group-hover:translate-x-1 transition-all flex-shrink-0"></i>
            </a>

            <a href="{{ route('admin.leads.index') }}" class="group bg-white p-4 rounded-2xl border border-slate-200/80 hover:border-cyan-500/40 hover:shadow-md transition-all flex items-center justify-between">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-lg group-hover:bg-cyan-600 group-hover:text-white transition-colors flex-shrink-0">
                        <i class="ph-bold ph-funnel"></i>
                    </div>
                    <div class="min-w-0">
                        <h4 class="text-xs sm:text-sm font-bold text-slate-900 group-hover:text-cyan-600 transition-colors truncate">Leads CRM</h4>
                        <p class="text-[11px] text-slate-400 truncate">{{ $crmStats['total_leads'] ?? 0 }} in pipeline</p>
                    </div>
                </div>
                <i class="ph-bold ph-arrow-right text-slate-300 group-hover:text-cyan-600 group-hover:translate-x-1 transition-all flex-shrink-0"></i>
            </a>

            <a href="{{ route('admin.callbacks') }}" class="group bg-white p-4 rounded-2xl border border-slate-200/80 hover:border-rose-500/40 hover:shadow-md transition-all flex items-center justify-between">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg group-hover:bg-rose-600 group-hover:text-white transition-colors flex-shrink-0">
                        <i class="ph-bold ph-phone-call"></i>
                    </div>
                    <div class="min-w-0">
                        <h4 class="text-xs sm:text-sm font-bold text-slate-900 group-hover:text-rose-600 transition-colors truncate">Callbacks</h4>
                        <p class="text-[11px] {{ ($crmStats['pending_callbacks'] ?? 0) > 0 ? 'text-rose-600 font-bold' : 'text-slate-400' }} truncate">{{ $crmStats['pending_callbacks'] ?? 0 }} pending</p>
                    </div>
                </div>
                <i class="ph-bold ph-arrow-right text-slate-300 group-hover:text-rose-600 group-hover:translate-x-1 transition-all flex-shrink-0"></i>
            </a>

            <a href="{{ route('admin.plans') }}" class="group bg-white p-4 rounded-2xl border border-slate-200/80 hover:border-amber-500/40 hover:shadow-md transition-all flex items-center justify-between">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg group-hover:bg-amber-600 group-hover:text-white transition-colors flex-shrink-0">
                        <i class="ph-bold ph-crown"></i>
                    </div>
                    <div class="min-w-0">
                        <h4 class="text-xs sm:text-sm font-bold text-slate-900 group-hover:text-amber-600 transition-colors truncate">Pricing Plans</h4>
                        <p class="text-[11px] text-slate-400 truncate">Packages & tiers</p>
                    </div>
                </div>
                <i class="ph-bold ph-arrow-right text-slate-300 group-hover:text-amber-600 group-hover:translate-x-1 transition-all flex-shrink-0"></i>
            </a>

            <a href="{{ route('admin.blogs.index') }}" class="group bg-white p-4 rounded-2xl border border-slate-200/80 hover:border-emerald-500/40 hover:shadow-md transition-all flex items-center justify-between">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg group-hover:bg-emerald-600 group-hover:text-white transition-colors flex-shrink-0">
                        <i class="ph-bold ph-newspaper"></i>
                    </div>
                    <div class="min-w-0">
                        <h4 class="text-xs sm:text-sm font-bold text-slate-900 group-hover:text-emerald-600 transition-colors truncate">Blog Articles</h4>
                        <p class="text-[11px] text-slate-400 truncate">{{ $stats['total_blogs'] ?? 0 }} articles</p>
                    </div>
                </div>
                <i class="ph-bold ph-arrow-right text-slate-300 group-hover:text-emerald-600 group-hover:translate-x-1 transition-all flex-shrink-0"></i>
            </a>

        </div>
    </div>

    {{-- =========================================================================
         OVERALL CRM EXECUTIVE SUMMARY & PIPELINE HUB
         ========================================================================= --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-7 space-y-6" id="crm-summary-hub">
        
        {{-- Section Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-5 border-b border-slate-100">
            <div>
                <div class="flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 via-indigo-600 to-purple-600 text-white flex items-center justify-center text-xl shadow-md shadow-blue-500/20">
                        <i class="ph-bold ph-funnel"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight">Overall CRM Executive Summary</h2>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-50 text-blue-700 border border-blue-200/80 uppercase tracking-wider">360° Hub</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">End-to-end customer journey, lead conversion pipeline, active paid memberships, and urgent touchpoints.</p>
                    </div>
                </div>
            </div>

            {{-- Quick CRM Jump Links --}}
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.leads.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-xl text-xs font-bold transition-all border border-blue-200/60 active:scale-95">
                    <i class="ph-bold ph-funnel text-sm"></i>
                    <span>Leads CRM ({{ $crmStats['total_leads'] ?? 0 }})</span>
                </a>
                <a href="{{ route('admin.callbacks') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-xl text-xs font-bold transition-all border border-rose-200/60 active:scale-95">
                    <i class="ph-bold ph-phone-call text-sm"></i>
                    <span>Callbacks ({{ $crmStats['pending_callbacks'] ?? 0 }})</span>
                </a>
                <a href="{{ route('admin.follow-ups.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 rounded-xl text-xs font-bold transition-all border border-amber-200/60 active:scale-95">
                    <i class="ph-bold ph-calendar-check text-sm"></i>
                    <span>Follow-ups</span>
                </a>
                <a href="{{ route('admin.visitors.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all active:scale-95">
                    <i class="ph-bold ph-chart-polar text-sm"></i>
                    <span>Visitors</span>
                </a>
            </div>
        </div>

        {{-- 6 High-Impact CRM Metric Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-3.5">
            
            {{-- Metric 1: Total Leads Pipeline --}}
            <a href="{{ route('admin.leads.index') }}" class="group bg-slate-50/70 hover:bg-white p-4 rounded-2xl border border-slate-200/70 hover:border-blue-500/50 hover:shadow-md transition-all">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Leads Pipeline</span>
                    <div class="w-8 h-8 rounded-lg bg-blue-100/70 text-blue-600 group-hover:bg-blue-600 group-hover:text-white flex items-center justify-center transition-colors text-base">
                        <i class="ph-bold ph-funnel"></i>
                    </div>
                </div>
                <div class="text-2xl font-black text-slate-900 group-hover:text-blue-600 transition-colors">
                    {{ $crmStats['total_leads'] ?? 0 }}
                </div>
                <div class="flex items-center gap-1.5 mt-2 text-[11px] font-semibold text-slate-500">
                    <span class="text-emerald-700 font-bold bg-emerald-100/70 px-1.5 py-0.5 rounded text-[10px]">+{{ $crmStats['today_leads'] ?? 0 }} Today</span>
                    <span>{{ $crmStats['in_progress_leads'] ?? 0 }} Active</span>
                </div>
            </a>

            {{-- Metric 2: Won Deals & Conversion --}}
            <a href="{{ route('admin.leads.index', ['status' => 'converted']) }}" class="group bg-slate-50/70 hover:bg-white p-4 rounded-2xl border border-slate-200/70 hover:border-emerald-500/50 hover:shadow-md transition-all">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Won Deals</span>
                    <div class="w-8 h-8 rounded-lg bg-emerald-100/70 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white flex items-center justify-center transition-colors text-base">
                        <i class="ph-bold ph-trophy"></i>
                    </div>
                </div>
                <div class="text-2xl font-black text-slate-900 group-hover:text-emerald-600 transition-colors">
                    {{ $crmStats['converted_leads'] ?? 0 }}
                </div>
                <div class="flex items-center gap-1.5 mt-2 text-[11px] font-semibold text-slate-500">
                    <span class="text-emerald-700 font-bold bg-emerald-100/70 px-1.5 py-0.5 rounded text-[10px]">{{ $crmStats['conversion_rate'] ?? 0 }}% Win</span>
                    <span>{{ $crmStats['whatsapp_leads'] ?? 0 }} WhatsApp</span>
                </div>
            </a>

            {{-- Metric 3: Urgent Callbacks --}}
            <a href="{{ route('admin.callbacks') }}" class="group bg-slate-50/70 hover:bg-white p-4 rounded-2xl border border-slate-200/70 hover:border-rose-500/50 hover:shadow-md transition-all">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Callbacks</span>
                    <div class="w-8 h-8 rounded-lg bg-rose-100/70 text-rose-600 group-hover:bg-rose-600 group-hover:text-white flex items-center justify-center transition-colors text-base">
                        <i class="ph-bold ph-phone-call"></i>
                    </div>
                </div>
                <div class="text-2xl font-black text-slate-900 group-hover:text-rose-600 transition-colors flex items-center gap-2">
                    {{ $crmStats['pending_callbacks'] ?? 0 }}
                    @if(($crmStats['pending_callbacks'] ?? 0) > 0)
                        <span class="w-2 h-2 rounded-full bg-rose-500 animate-ping"></span>
                    @endif
                </div>
                <div class="flex items-center gap-1.5 mt-2 text-[11px] font-semibold text-slate-500">
                    <span class="text-rose-700 font-bold bg-rose-100/70 px-1.5 py-0.5 rounded text-[10px]">{{ $crmStats['pending_callbacks'] ?? 0 }} Pending</span>
                    <span>{{ $crmStats['handled_callbacks'] ?? 0 }} Handled</span>
                </div>
            </a>

            {{-- Metric 4: Paid Members & Revenue --}}
            <a href="{{ route('admin.subscriptions') }}" class="group bg-slate-50/70 hover:bg-white p-4 rounded-2xl border border-slate-200/70 hover:border-purple-500/50 hover:shadow-md transition-all">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Paid Members</span>
                    <div class="w-8 h-8 rounded-lg bg-purple-100/70 text-purple-600 group-hover:bg-purple-600 group-hover:text-white flex items-center justify-center transition-colors text-base">
                        <i class="ph-bold ph-crown"></i>
                    </div>
                </div>
                <div class="text-2xl font-black text-slate-900 group-hover:text-purple-600 transition-colors">
                    {{ $crmStats['active_paid_members'] ?? 0 }}
                </div>
                <div class="flex items-center gap-1.5 mt-2 text-[11px] font-semibold text-slate-500">
                    <span class="text-purple-700 font-bold bg-purple-100/70 px-1.5 py-0.5 rounded text-[10px]">₹{{ number_format($crmStats['total_revenue'] ?? 0) }}</span>
                    <span>{{ $crmStats['pending_plan_approvals'] ?? 0 }} Pending</span>
                </div>
            </a>

            {{-- Metric 5: Tracked Visitors --}}
            <a href="{{ route('admin.visitors.index') }}" class="group bg-slate-50/70 hover:bg-white p-4 rounded-2xl border border-slate-200/70 hover:border-cyan-500/50 hover:shadow-md transition-all">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Traffic & Visitors</span>
                    <div class="w-8 h-8 rounded-lg bg-cyan-100/70 text-cyan-600 group-hover:bg-cyan-600 group-hover:text-white flex items-center justify-center transition-colors text-base">
                        <i class="ph-bold ph-chart-polar"></i>
                    </div>
                </div>
                <div class="text-2xl font-black text-slate-900 group-hover:text-cyan-600 transition-colors">
                    {{ $crmStats['total_visitors'] ?? 0 }}
                </div>
                <div class="flex items-center gap-1.5 mt-2 text-[11px] font-semibold text-slate-500">
                    <span class="text-cyan-800 font-bold bg-cyan-100/70 px-1.5 py-0.5 rounded text-[10px]">{{ $crmStats['today_visitors'] ?? 0 }} Today</span>
                    <span>{{ $crmStats['visitor_to_lead_rate'] ?? 0 }}% Conv</span>
                </div>
            </a>

            {{-- Metric 6: Professionals & Services CRM --}}
            <a href="{{ route('admin.professionals.dashboard') }}" class="group bg-slate-50/70 hover:bg-white p-4 rounded-2xl border border-slate-200/70 hover:border-amber-500/50 hover:shadow-md transition-all">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Local Pros CRM</span>
                    <div class="w-8 h-8 rounded-lg bg-amber-100/70 text-amber-600 group-hover:bg-amber-600 group-hover:text-white flex items-center justify-center transition-colors text-base">
                        <i class="ph-bold ph-toolbox"></i>
                    </div>
                </div>
                <div class="text-2xl font-black text-slate-900 group-hover:text-amber-600 transition-colors">
                    {{ $crmStats['total_professionals'] ?? 0 }}
                </div>
                <div class="flex items-center gap-1.5 mt-2 text-[11px] font-semibold text-slate-500">
                    <span class="text-amber-800 font-bold bg-amber-100/70 px-1.5 py-0.5 rounded text-[10px]">{{ $crmStats['total_prof_leads'] ?? 0 }} Leads</span>
                    <span>{{ $crmStats['pending_professionals'] ?? 0 }} Pending</span>
                </div>
            </a>

        </div>

        {{-- Lead Stages Conversion Funnel Visual Bar --}}
        <div class="bg-slate-50/70 rounded-2xl p-4 sm:p-5 border border-slate-200/60">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-3">
                <div class="flex items-center gap-2">
                    <i class="ph-bold ph-chart-bar-horizontal text-base text-slate-600"></i>
                    <h3 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">Lead Conversion Funnel Stages</h3>
                </div>
                <span class="text-xs font-semibold text-slate-400">Total Leads Ingested: <span class="font-bold text-slate-700">{{ $crmStats['total_leads'] ?? 0 }}</span></span>
            </div>

            {{-- Visual Funnel Cards Row --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2.5">
                
                {{-- Stage 1: New --}}
                <a href="{{ route('admin.leads.index', ['status' => 'new']) }}" class="p-3 bg-white rounded-xl border border-slate-200/80 hover:border-blue-400 hover:shadow-xs transition-all flex flex-col justify-between">
                    <div class="flex items-center justify-between text-xs mb-1">
                        <span class="font-bold text-blue-700">1. New</span>
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                    </div>
                    <div class="text-lg font-extrabold text-slate-900">{{ $crmStats['lead_funnel']['new'] ?? 0 }}</div>
                    <div class="text-[10px] text-slate-400 mt-1">Pending contact</div>
                </a>

                {{-- Stage 2: Contacted --}}
                <a href="{{ route('admin.leads.index', ['status' => 'contacted']) }}" class="p-3 bg-white rounded-xl border border-slate-200/80 hover:border-cyan-400 hover:shadow-xs transition-all flex flex-col justify-between">
                    <div class="flex items-center justify-between text-xs mb-1">
                        <span class="font-bold text-cyan-700">2. Contacted</span>
                        <span class="w-2 h-2 rounded-full bg-cyan-500"></span>
                    </div>
                    <div class="text-lg font-extrabold text-slate-900">{{ $crmStats['lead_funnel']['contacted'] ?? 0 }}</div>
                    <div class="text-[10px] text-slate-400 mt-1">Initial outreach</div>
                </a>

                {{-- Stage 3: Interested / Visit --}}
                <a href="{{ route('admin.leads.index', ['status' => 'interested']) }}" class="p-3 bg-white rounded-xl border border-slate-200/80 hover:border-indigo-400 hover:shadow-xs transition-all flex flex-col justify-between">
                    <div class="flex items-center justify-between text-xs mb-1">
                        <span class="font-bold text-indigo-700">3. Interested</span>
                        <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                    </div>
                    <div class="text-lg font-extrabold text-slate-900">{{ ($crmStats['lead_funnel']['interested'] ?? 0) + ($crmStats['lead_funnel']['visit_scheduled'] ?? 0) }}</div>
                    <div class="text-[10px] text-slate-400 mt-1">Visits & interest</div>
                </a>

                {{-- Stage 4: Negotiation --}}
                <a href="{{ route('admin.leads.index', ['status' => 'negotiation']) }}" class="p-3 bg-white rounded-xl border border-slate-200/80 hover:border-amber-400 hover:shadow-xs transition-all flex flex-col justify-between">
                    <div class="flex items-center justify-between text-xs mb-1">
                        <span class="font-bold text-amber-700">4. Negotiation</span>
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                    </div>
                    <div class="text-lg font-extrabold text-slate-900">{{ $crmStats['lead_funnel']['negotiation'] ?? 0 }}</div>
                    <div class="text-[10px] text-slate-400 mt-1">Terms discussion</div>
                </a>

                {{-- Stage 5: Converted (Won) --}}
                <a href="{{ route('admin.leads.index', ['status' => 'converted']) }}" class="p-3 bg-emerald-50/60 rounded-xl border border-emerald-200/90 hover:border-emerald-500 hover:shadow-xs transition-all flex flex-col justify-between">
                    <div class="flex items-center justify-between text-xs mb-1">
                        <span class="font-bold text-emerald-800">5. Converted</span>
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    </div>
                    <div class="text-lg font-extrabold text-emerald-900">{{ $crmStats['lead_funnel']['converted'] ?? 0 }}</div>
                    <div class="text-[10px] text-emerald-700 font-semibold mt-1">Closed Deals 🎉</div>
                </a>

                {{-- Stage 6: Lost / Inactive --}}
                <a href="{{ route('admin.leads.index', ['status' => 'lost']) }}" class="p-3 bg-white rounded-xl border border-slate-200/80 hover:border-rose-400 hover:shadow-xs transition-all flex flex-col justify-between">
                    <div class="flex items-center justify-between text-xs mb-1">
                        <span class="font-bold text-rose-700">6. Lost / Dropped</span>
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                    </div>
                    <div class="text-lg font-extrabold text-slate-900">{{ $crmStats['lead_funnel']['lost'] ?? 0 }}</div>
                    <div class="text-[10px] text-slate-400 mt-1">Not interested</div>
                </a>

            </div>
        </div>

        {{-- Side-by-Side Dual Activity Feeds: Recent Leads & Urgent Callbacks --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 pt-1">
            
            {{-- Left Feed: Recent CRM Leads --}}
            <div class="border border-slate-200/80 rounded-2xl overflow-hidden flex flex-col justify-between bg-white shadow-xs">
                <div>
                    <div class="px-5 py-3.5 border-b border-slate-100 flex items-center justify-between bg-slate-50/60">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                            <h4 class="text-sm font-extrabold text-slate-900">Recent Inbound Leads</h4>
                            <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-800">{{ $recentCrmLeads->count() }}</span>
                        </div>
                        <a href="{{ route('admin.leads.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1">
                            View All Leads →
                        </a>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @forelse($recentCrmLeads as $lead)
                        <div class="p-4 hover:bg-slate-50/60 transition-colors">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-blue-500 to-indigo-600 text-white flex items-center justify-center font-bold text-xs flex-shrink-0 shadow-xs">
                                        {{ strtoupper(substr($lead->name ?? 'L', 0, 2)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('admin.leads.show', $lead) }}" class="text-xs sm:text-sm font-bold text-slate-900 hover:text-blue-600 truncate transition-colors">
                                                {{ $lead->name ?? 'Customer' }}
                                            </a>
                                            @php
                                                $badge = $lead->status_badge ?? ['bg' => 'bg-slate-100 text-slate-700', 'label' => ucfirst($lead->lead_status ?? 'New')];
                                            @endphp
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $badge['bg'] }} flex-shrink-0">
                                                {{ $badge['label'] }}
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-2 text-[11px] text-slate-500 mt-0.5 truncate">
                                            <span>{{ $lead->formatted_phone ?? $lead->mobile }}</span>
                                            @if($lead->preferred_city)
                                                <span>·</span>
                                                <span><i class="ph ph-map-pin"></i> {{ $lead->preferred_city }}</span>
                                            @endif
                                            @if($lead->purpose)
                                                <span>·</span>
                                                <span class="capitalize">{{ $lead->purpose }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Quick Actions --}}
                                <div class="flex items-center gap-1.5 flex-shrink-0">
                                    @if($lead->mobile)
                                    <a href="https://wa.me/{{ $lead->whatsapp_phone ?? preg_replace('/[^0-9]/', '', $lead->mobile) }}" target="_blank" class="w-8 h-8 rounded-lg bg-emerald-50 hover:bg-emerald-500 text-emerald-600 hover:text-white flex items-center justify-center text-base transition-colors" title="Chat on WhatsApp">
                                        <i class="ph-bold ph-whatsapp-logo"></i>
                                    </a>
                                    @endif
                                    <a href="{{ route('admin.leads.show', $lead) }}" class="px-2.5 py-1.5 bg-slate-100 hover:bg-blue-600 text-slate-700 hover:text-white rounded-lg text-xs font-bold transition-colors">
                                        Open
                                    </a>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="p-8 text-center">
                            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center text-2xl mx-auto mb-2">
                                <i class="ph-bold ph-funnel"></i>
                            </div>
                            <h5 class="text-xs font-bold text-slate-700">No leads recorded yet</h5>
                            <p class="text-[11px] text-slate-400 mt-0.5">Incoming leads from the website and app will appear here in real time.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <div class="p-3 bg-slate-50/50 border-t border-slate-100 text-center">
                    <a href="{{ route('admin.leads.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 inline-flex items-center gap-1">
                        Go to Leads Pipeline ({{ $crmStats['total_leads'] ?? 0 }} Total) →
                    </a>
                </div>
            </div>

            {{-- Right Feed: Urgent Callbacks & Inquiries --}}
            <div class="border border-slate-200/80 rounded-2xl overflow-hidden flex flex-col justify-between bg-white shadow-xs">
                <div>
                    <div class="px-5 py-3.5 border-b border-slate-100 flex items-center justify-between bg-slate-50/60">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-rose-500 {{ ($crmStats['pending_callbacks'] ?? 0) > 0 ? 'animate-ping' : '' }}"></span>
                            <h4 class="text-sm font-extrabold text-slate-900">Urgent Callbacks & Requests</h4>
                            <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-800">{{ $recentCallbacks->count() }}</span>
                        </div>
                        <a href="{{ route('admin.callbacks') }}" class="text-xs font-bold text-rose-600 hover:text-rose-700 flex items-center gap-1">
                            View All Callbacks →
                        </a>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @forelse($recentCallbacks as $callback)
                        <div class="p-4 hover:bg-slate-50/60 transition-colors">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-rose-500 to-amber-500 text-white flex items-center justify-center font-bold text-xs flex-shrink-0 shadow-xs">
                                        <i class="ph-bold ph-phone-call"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2">
                                            <h5 class="text-xs sm:text-sm font-bold text-slate-900 truncate">
                                                {{ $callback->name ?? 'Customer' }}
                                            </h5>
                                            @php
                                                $cbBadge = $callback->status_badge ?? ['bg' => 'bg-amber-100 text-amber-800', 'label' => ucfirst($callback->status ?? 'New')];
                                            @endphp
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $cbBadge['bg'] }} flex-shrink-0">
                                                {{ $cbBadge['label'] }}
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-2 text-[11px] text-slate-500 mt-0.5 truncate">
                                            <span class="font-semibold text-slate-700">{{ $callback->phone }}</span>
                                            @if($callback->property)
                                                <span>·</span>
                                                <span class="truncate"><i class="ph ph-buildings"></i> {{ $callback->property->title }}</span>
                                            @elseif($callback->message)
                                                <span>·</span>
                                                <span class="truncate">{{ Str::limit($callback->message, 30) }}</span>
                                            @endif
                                            <span>·</span>
                                            <span>{{ $callback->created_at ? $callback->created_at->diffForHumans() : 'Recently' }}</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Quick Actions --}}
                                <div class="flex items-center gap-1.5 flex-shrink-0">
                                    @if($callback->phone)
                                    <a href="tel:{{ $callback->phone }}" class="w-8 h-8 rounded-lg bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white flex items-center justify-center text-base transition-colors" title="Call Now">
                                        <i class="ph-bold ph-phone"></i>
                                    </a>
                                    <a href="https://wa.me/{{ $callback->clean_phone ? '91' . $callback->clean_phone : preg_replace('/[^0-9]/', '', $callback->phone) }}" target="_blank" class="w-8 h-8 rounded-lg bg-emerald-50 hover:bg-emerald-500 text-emerald-600 hover:text-white flex items-center justify-center text-base transition-colors" title="WhatsApp Message">
                                        <i class="ph-bold ph-whatsapp-logo"></i>
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="p-8 text-center">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl mx-auto mb-2">
                                <i class="ph-bold ph-check-circle"></i>
                            </div>
                            <h5 class="text-xs font-bold text-slate-700">All callbacks caught up</h5>
                            <p class="text-[11px] text-slate-400 mt-0.5">No pending callback requests require immediate attention.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <div class="p-3 bg-slate-50/50 border-t border-slate-100 text-center">
                    <a href="{{ route('admin.callbacks') }}" class="text-xs font-bold text-rose-600 hover:text-rose-700 inline-flex items-center gap-1">
                        Go to Callbacks Manager ({{ $crmStats['pending_callbacks'] ?? 0 }} Pending) →
                    </a>
                </div>
            </div>

        </div>

        {{-- Direct CRM Workspace Links Toolbar --}}
        <div class="pt-4 border-t border-slate-100 flex flex-col md:flex-row md:items-center md:justify-between gap-3 text-xs">
            <span class="font-extrabold text-slate-400 uppercase tracking-widest text-[10px]">Direct CRM Workspaces:</span>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.leads.index') }}" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-blue-600 hover:text-white text-slate-700 font-bold transition-all">
                    🎯 Leads Pipeline
                </a>
                <a href="{{ route('admin.follow-ups.index') }}" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-amber-600 hover:text-white text-slate-700 font-bold transition-all">
                    📅 Follow-ups Hub
                </a>
                <a href="{{ route('admin.callbacks') }}" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-rose-600 hover:text-white text-slate-700 font-bold transition-all">
                    📞 Callback Center
                </a>
                <a href="{{ route('admin.visitors.index') }}" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-cyan-600 hover:text-white text-slate-700 font-bold transition-all">
                    🌐 Visitor Analytics
                </a>
                <a href="{{ route('admin.chats') }}" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-indigo-600 hover:text-white text-slate-700 font-bold transition-all">
                    💬 Support Inquiries
                </a>
                <a href="{{ route('admin.professionals.leads') }}" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-amber-600 hover:text-white text-slate-700 font-bold transition-all">
                    🛠️ Service Leads
                </a>
                <a href="{{ route('admin.subscriptions') }}" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-purple-600 hover:text-white text-slate-700 font-bold transition-all">
                    👑 Paid Subscribers
                </a>
            </div>
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
