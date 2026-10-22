@extends('layouts.admin')

@section('title', 'User Transactions & Payment Hub - UnlockRentals Admin')
@section('topbar_title', 'Transactions')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 sm:space-y-8" id="admin-transactions-hub">

    {{-- Breadcrumb & Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs">
        <div>
            <div class="flex items-center gap-2 text-xs font-bold text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 transition-colors">Admin</a>
                <i class="ph-bold ph-caret-right text-[10px]"></i>
                <span>Monetization &amp; Finance</span>
                <i class="ph-bold ph-caret-right text-[10px]"></i>
                <span class="text-blue-600">Transactions Ledger</span>
            </div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">User Transactions &amp; Status</h1>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Live Stream
                </span>
            </div>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Real-time audit log of all customer plan purchases, gateway payments, and transaction settlement statuses.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5 sm:gap-3">
            {{-- Export CSV Button --}}
            <a href="{{ route('admin.transactions.export', request()->all()) }}" 
               class="inline-flex items-center gap-2 px-3.5 py-2 sm:px-4 sm:py-2.5 rounded-xl text-xs font-bold transition-all duration-200 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 shadow-2xs hover:shadow-xs active:scale-95"
               title="Export Transactions to CSV">
                <i class="ph-bold ph-download-simple text-sm text-blue-600"></i>
                <span>Export CSV</span>
            </a>

            {{-- Assign Plan Manually --}}
            <a href="{{ route('admin.subscriptions.assign') }}" 
               class="inline-flex items-center gap-2 px-3.5 py-2 sm:px-4 sm:py-2.5 rounded-xl text-xs font-bold transition-all duration-200 bg-gradient-to-r from-blue-600 via-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 border border-blue-400/30 text-white shadow-xs hover:shadow-md shadow-blue-500/25 active:scale-95"
               style="color: #ffffff !important; -webkit-text-fill-color: #ffffff !important;"
               title="Assign Subscription Plan Manually">
                <i class="ph-bold ph-plus-circle text-sm" style="color: #ffffff !important;"></i>
                <span style="color: #ffffff !important;">Manual Transaction</span>
            </a>
        </div>
    </div>

    {{-- Flash Notifications --}}
    @if(session('success'))
    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold flex items-center justify-between shadow-xs">
        <div class="flex items-center gap-2.5">
            <i class="ph-fill ph-check-circle text-emerald-600 text-xl"></i>
            <span>{{ session('success') }}</span>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 p-1">
            <i class="ph-bold ph-x"></i>
        </button>
    </div>
    @endif

    {{-- Financial KPI Cards (4 Metrics) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        
        {{-- Total Revenue Settled --}}
        <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-xs hover:shadow-md hover:border-emerald-300 transition-all group">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-extrabold text-slate-500 uppercase tracking-wider">Total Revenue</span>
                <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white transition-all shadow-2xs">
                    <i class="ph-bold ph-currency-inr"></i>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight group-hover:text-emerald-700 transition-colors">
                ₹{{ number_format($stats['total_revenue'], 2) }}
            </div>
            <div class="flex items-center gap-1.5 mt-2 text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-lg w-fit">
                <i class="ph-bold ph-trend-up"></i>
                <span>Today: ₹{{ number_format($stats['today_revenue'], 2) }}</span>
            </div>
        </div>

        {{-- Completed / Approved Transactions --}}
        <a href="{{ route('admin.transactions', ['status' => 'approved']) }}" class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-xs hover:shadow-md hover:border-blue-300 transition-all group block">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-extrabold text-slate-500 uppercase tracking-wider">Completed / Paid</span>
                <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all shadow-2xs">
                    <i class="ph-bold ph-check-circle"></i>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight group-hover:text-blue-700 transition-colors">
                {{ $stats['approved_count'] }}
            </div>
            <div class="flex items-center gap-1.5 mt-2 text-[11px] font-semibold text-slate-500">
                <span>Total settled customer payments</span>
            </div>
        </a>

        {{-- Pending Review --}}
        <a href="{{ route('admin.transactions', ['status' => 'pending']) }}" class="bg-white p-5 rounded-3xl border {{ $stats['pending_count'] > 0 ? 'border-amber-300 bg-gradient-to-b from-amber-50/30 to-white' : 'border-slate-200/80' }} shadow-xs hover:shadow-md hover:border-amber-400 transition-all group block">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-extrabold text-amber-700 uppercase tracking-wider">Pending Review</span>
                <div class="w-10 h-10 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center text-lg group-hover:scale-110 group-hover:bg-amber-600 group-hover:text-white transition-all shadow-2xs">
                    <i class="ph-bold ph-clock-countdown"></i>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-amber-900 tracking-tight">
                {{ $stats['pending_count'] }}
            </div>
            <div class="flex items-center gap-1.5 mt-2 text-[11px] font-bold text-amber-700">
                @if($stats['pending_count'] > 0)
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                    <span>Action required: verify receipts</span>
                @else
                    <span>All payments settled</span>
                @endif
            </div>
        </a>

        {{-- Failed / Rejected --}}
        <a href="{{ route('admin.transactions', ['status' => 'rejected']) }}" class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-xs hover:shadow-md hover:border-rose-300 transition-all group block">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-extrabold text-slate-500 uppercase tracking-wider">Failed / Rejected</span>
                <div class="w-10 h-10 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg group-hover:scale-110 group-hover:bg-rose-600 group-hover:text-white transition-all shadow-2xs">
                    <i class="ph-bold ph-x-circle"></i>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight group-hover:text-rose-700 transition-colors">
                {{ $stats['rejected_count'] }}
            </div>
            <div class="flex items-center gap-1.5 mt-2 text-[11px] font-semibold text-slate-500">
                <span>Declined or rejected records</span>
            </div>
        </a>

    </div>

    {{-- Main Ledger Container --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        
        {{-- Section 1: Navigation Tabs & Feed Switcher --}}
        <div class="border-b border-slate-200/80 bg-slate-50/60 p-4 sm:p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
            
            {{-- Status Filter Tabs --}}
            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 sm:pb-0 custom-scrollbar text-xs font-bold">
                <a href="{{ route('admin.transactions', array_merge(request()->except(['status', 'page']), ['status' => 'all', 'tab' => 'transactions'])) }}" 
                   class="px-3.5 py-2 rounded-xl transition-all whitespace-nowrap {{ ($status === 'all' && $tab === 'transactions') ? 'bg-blue-600 text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200/70' }}">
                    All Transactions ({{ $stats['total_count'] }})
                </a>
                <a href="{{ route('admin.transactions', array_merge(request()->except(['status', 'page']), ['status' => 'approved', 'tab' => 'transactions'])) }}" 
                   class="px-3.5 py-2 rounded-xl transition-all whitespace-nowrap {{ $status === 'approved' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-white text-emerald-700 hover:bg-emerald-50 border border-slate-200/70' }}">
                    <i class="ph-bold ph-check-circle inline align-middle mr-1"></i> Approved ({{ $stats['approved_count'] }})
                </a>
                <a href="{{ route('admin.transactions', array_merge(request()->except(['status', 'page']), ['status' => 'pending', 'tab' => 'transactions'])) }}" 
                   class="px-3.5 py-2 rounded-xl transition-all whitespace-nowrap {{ $status === 'pending' ? 'bg-amber-500 text-white shadow-xs' : 'bg-white text-amber-700 hover:bg-amber-50 border border-slate-200/70' }}">
                    <i class="ph-bold ph-clock inline align-middle mr-1"></i> Pending ({{ $stats['pending_count'] }})
                </a>
                <a href="{{ route('admin.transactions', array_merge(request()->except(['status', 'page']), ['status' => 'rejected', 'tab' => 'transactions'])) }}" 
                   class="px-3.5 py-2 rounded-xl transition-all whitespace-nowrap {{ $status === 'rejected' ? 'bg-rose-600 text-white shadow-xs' : 'bg-white text-rose-700 hover:bg-rose-50 border border-slate-200/70' }}">
                    <i class="ph-bold ph-x-circle inline align-middle mr-1"></i> Rejected ({{ $stats['rejected_count'] }})
                </a>
                <a href="{{ route('admin.transactions', array_merge(request()->except(['status', 'page']), ['status' => 'expired', 'tab' => 'transactions'])) }}" 
                   class="px-3.5 py-2 rounded-xl transition-all whitespace-nowrap {{ $status === 'expired' ? 'bg-slate-700 text-white shadow-xs' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200/70' }}">
                    <i class="ph-bold ph-hourglass inline align-middle mr-1"></i> Expired ({{ $stats['expired_count'] }})
                </a>
                <a href="{{ route('admin.transactions', array_merge(request()->except(['tab', 'page']), ['tab' => 'logs'])) }}" 
                   class="px-3.5 py-2 rounded-xl transition-all whitespace-nowrap {{ $tab === 'logs' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-white text-indigo-700 hover:bg-indigo-50 border border-slate-200/70' }}">
                    <i class="ph-bold ph-receipt text-indigo-500 inline align-middle mr-1"></i> Gateway Logs ({{ $stats['logs_count'] }})
                </a>
            </div>

            {{-- Filter Summary Tag --}}
            <div class="text-[11px] text-slate-500 font-semibold shrink-0">
                Showing <strong>{{ $tab === 'logs' ? $paymentLogs->total() : $transactions->total() }}</strong> records
            </div>
        </div>

        {{-- Section 2: Search & Filter Toolbar --}}
        <div class="p-4 sm:p-5 border-b border-slate-200/80 bg-white">
            <form method="GET" action="{{ route('admin.transactions') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center">
                <input type="hidden" name="status" value="{{ $status }}">
                <input type="hidden" name="tab" value="{{ $tab }}">

                {{-- Search Input --}}
                <div class="lg:col-span-5 relative">
                    <i class="ph-bold ph-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" 
                           name="search" 
                           value="{{ $search }}" 
                           placeholder="Search by User Name, Email, Phone, Txn ID, or Invoice..." 
                           class="w-full pl-9 pr-4 py-2.5 text-xs rounded-xl border border-slate-200 bg-slate-50/50 text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                </div>

                {{-- Payment Method Filter --}}
                <div class="lg:col-span-3">
                    <select name="method" onchange="this.form.submit()" class="w-full px-3 py-2.5 text-xs rounded-xl border border-slate-200 bg-slate-50/50 text-slate-700 font-medium focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        <option value="all" {{ $method === 'all' ? 'selected' : '' }}>All Payment Methods</option>
                        <option value="razorpay" {{ $method === 'razorpay' ? 'selected' : '' }}>Razorpay (Online Gateway)</option>
                        <option value="upi" {{ $method === 'upi' ? 'selected' : '' }}>UPI / QR / PhonePe / Paytm</option>
                        <option value="bank_transfer" {{ $method === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer (NEFT/IMPS)</option>
                        <option value="card" {{ $method === 'card' ? 'selected' : '' }}>Credit / Debit Card</option>
                        <option value="manual" {{ $method === 'manual' ? 'selected' : '' }}>Cash / Manual Assignment</option>
                    </select>
                </div>

                {{-- Date Range Filter --}}
                <div class="lg:col-span-2">
                    <select name="date_range" onchange="this.form.submit()" class="w-full px-3 py-2.5 text-xs rounded-xl border border-slate-200 bg-slate-50/50 text-slate-700 font-medium focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        <option value="all" {{ $dateRange === 'all' ? 'selected' : '' }}>All Time</option>
                        <option value="today" {{ $dateRange === 'today' ? 'selected' : '' }}>Today</option>
                        <option value="week" {{ $dateRange === 'week' ? 'selected' : '' }}>Past 7 Days</option>
                        <option value="month" {{ $dateRange === 'month' ? 'selected' : '' }}>Past 30 Days</option>
                    </select>
                </div>

                {{-- Submit & Reset Buttons --}}
                <div class="lg:col-span-2 flex items-center gap-2">
                    <button type="submit" class="flex-1 py-2.5 px-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-all shadow-2xs text-center cursor-pointer">
                        Filter
                    </button>
                    @if($search || $status !== 'all' || $method !== 'all' || $dateRange !== 'all')
                    <a href="{{ route('admin.transactions', ['tab' => $tab]) }}" class="p-2.5 rounded-xl border border-slate-200 text-slate-500 hover:text-slate-900 hover:bg-slate-50 transition-colors" title="Reset Filters">
                        <i class="ph-bold ph-arrow-counter-clockwise"></i>
                    </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- TAB A: MAIN TRANSACTIONS TABLE --}}
        @if($tab !== 'logs')
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/90 text-slate-500 font-extrabold uppercase tracking-wider text-[10px] border-b border-slate-200/80">
                        <th class="px-5 py-3.5">Invoice &amp; Transaction ID</th>
                        <th class="px-5 py-3.5">Customer / User</th>
                        <th class="px-5 py-3.5">Plan &amp; Duration</th>
                        <th class="px-5 py-3.5 text-right">Amount Paid</th>
                        <th class="px-5 py-3.5 text-center">Payment Method</th>
                        <th class="px-5 py-3.5 text-center">Status</th>
                        <th class="px-5 py-3.5">Date &amp; Time</th>
                        <th class="px-5 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                    @forelse($transactions as $txn)
                    <tr class="hover:bg-blue-50/30 transition-colors group">
                        
                        {{-- 1. Invoice & Transaction ID --}}
                        <td class="px-5 py-4">
                            <div class="flex flex-col gap-1">
                                <div class="flex items-center gap-1.5">
                                    <span class="font-mono font-bold text-slate-900 bg-slate-100 px-2 py-0.5 rounded-md text-[11px] select-all">
                                        {{ $txn->invoice_id ?: ('INV-' . str_pad($txn->id, 6, '0', STR_PAD_LEFT)) }}
                                    </span>
                                    <button type="button" onclick="copyToClipboard('{{ $txn->invoice_id ?: ('INV-' . str_pad($txn->id, 6, '0', STR_PAD_LEFT)) }}')" class="text-slate-400 hover:text-blue-600 transition-colors" title="Copy Invoice ID">
                                        <i class="ph-bold ph-copy text-xs"></i>
                                    </button>
                                </div>
                                <span class="font-mono text-[10px] text-slate-500 truncate max-w-[170px]" title="{{ $txn->transaction_id ?: $txn->payment_reference ?: 'Direct / System' }}">
                                    Ref: {{ $txn->transaction_id ?: $txn->payment_reference ?: 'Direct' }}
                                </span>
                            </div>
                        </td>

                        {{-- 2. Customer / User --}}
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-500 text-white font-extrabold flex items-center justify-center text-xs shrink-0 shadow-2xs">
                                    {{ strtoupper(substr($txn->user->name ?? 'U', 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <a href="{{ $txn->user ? route('admin.users.activity', $txn->user) : '#' }}" class="font-bold text-slate-900 hover:text-blue-600 truncate block">
                                        {{ $txn->user->name ?? 'Deleted User' }}
                                    </a>
                                    <div class="flex items-center gap-2 text-[10px] text-slate-500 truncate mt-0.5">
                                        <span>{{ $txn->user->email ?? 'No email' }}</span>
                                        @if(!empty($txn->user->phone))
                                            <span>•</span>
                                            <a href="tel:{{ $txn->user->phone }}" class="text-blue-600 font-semibold hover:underline">{{ $txn->user->phone }}</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- 3. Plan & Duration --}}
                        <td class="px-5 py-4">
                            <div class="flex flex-col gap-0.5">
                                <span class="font-bold text-slate-900 flex items-center gap-1.5">
                                    <i class="ph-bold ph-crown text-amber-500 text-xs"></i>
                                    {{ $txn->plan->name ?? 'Custom Direct Pass' }}
                                </span>
                                <div class="flex items-center gap-1.5 text-[10px] text-slate-500">
                                    <span class="capitalize px-1.5 py-0.2 rounded bg-slate-100 font-semibold text-slate-600">
                                        {{ $txn->billing_period ?: 'monthly' }}
                                    </span>
                                    <span>•</span>
                                    <span>{{ $txn->contacts_used ?? 0 }} / {{ $txn->plan->contact_limit ?? '10' }} contacts</span>
                                </div>
                            </div>
                        </td>

                        {{-- 4. Amount Paid --}}
                        <td class="px-5 py-4 text-right">
                            <div class="flex flex-col items-end">
                                <span class="font-black text-sm text-slate-900">
                                    ₹{{ number_format((float) ($txn->final_amount ?: ($txn->amount_paid ?: 0)), 2) }}
                                </span>
                                @if(!empty($txn->gst_amount) && $txn->gst_amount > 0)
                                    <span class="text-[10px] text-slate-400">incl. ₹{{ number_format($txn->gst_amount, 2) }} GST</span>
                                @else
                                    <span class="text-[10px] text-emerald-600 font-semibold">Net Total</span>
                                @endif
                            </div>
                        </td>

                        {{-- 5. Payment Method Badge --}}
                        <td class="px-5 py-4 text-center">
                            @php
                                $method = strtolower($txn->payment_method ?: 'online');
                            @endphp
                            @if(str_contains($method, 'razorpay') || $method === 'card')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                    <i class="ph-bold ph-credit-card"></i> Razorpay
                                </span>
                            @elseif(str_contains($method, 'upi') || str_contains($method, 'phonepe') || str_contains($method, 'paytm') || str_contains($method, 'gpay'))
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                    <i class="ph-bold ph-qr-code"></i> UPI / QR
                                </span>
                            @elseif(str_contains($method, 'bank') || str_contains($method, 'neft') || str_contains($method, 'imps'))
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <i class="ph-bold ph-bank"></i> Bank Transfer
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                    <i class="ph-bold ph-hand-coins"></i> {{ ucfirst($method) }}
                                </span>
                            @endif
                        </td>

                        {{-- 6. Status Badge --}}
                        <td class="px-5 py-4 text-center">
                            @if($txn->status === 'approved')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-black bg-emerald-100 text-emerald-800 border border-emerald-300">
                                    <i class="ph-fill ph-check-circle text-emerald-600"></i> Approved &amp; Paid
                                </span>
                            @elseif($txn->status === 'pending')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-black bg-amber-100 text-amber-800 border border-amber-300">
                                    <i class="ph-fill ph-clock text-amber-600"></i> Pending Review
                                </span>
                            @elseif($txn->status === 'rejected' || $txn->status === 'failed')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-black bg-rose-100 text-rose-800 border border-rose-300">
                                    <i class="ph-fill ph-x-circle text-rose-600"></i> Rejected
                                </span>
                            @elseif($txn->status === 'expired')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-300">
                                    <i class="ph-bold ph-hourglass text-slate-500"></i> Expired
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600">
                                    {{ ucfirst($txn->status) }}
                                </span>
                            @endif
                        </td>

                        {{-- 7. Date & Time --}}
                        <td class="px-5 py-4">
                            <div class="flex flex-col">
                                <span class="text-slate-900 font-semibold">{{ $txn->created_at ? $txn->created_at->format('M d, Y') : '—' }}</span>
                                <span class="text-[10px] text-slate-400">{{ $txn->created_at ? $txn->created_at->format('h:i A') : '' }} ({{ $txn->created_at ? $txn->created_at->diffForHumans() : '' }})</span>
                            </div>
                        </td>

                        {{-- 8. Actions (View / Quick Approve / Reject) --}}
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                {{-- View Details Trigger --}}
                                <button type="button" 
                                        onclick="openTransactionModal({{ json_encode([
                                            'id' => $txn->id,
                                            'invoice_id' => $txn->invoice_id ?: ('INV-' . str_pad($txn->id, 6, '0', STR_PAD_LEFT)),
                                            'transaction_id' => $txn->transaction_id ?: $txn->payment_reference ?: 'N/A',
                                            'user_name' => $txn->user->name ?? 'User',
                                            'user_email' => $txn->user->email ?? 'N/A',
                                            'user_phone' => $txn->user->phone ?? 'N/A',
                                            'plan_name' => $txn->plan->name ?? 'Direct Pass',
                                            'billing_period' => ucfirst($txn->billing_period ?: 'monthly'),
                                            'subtotal' => number_format((float) ($txn->subtotal_amount ?: ($txn->amount_paid ?: 0)), 2),
                                            'discount' => number_format((float) ($txn->discount_amount ?: 0), 2),
                                            'gst' => number_format((float) ($txn->gst_amount ?: 0), 2),
                                            'final' => number_format((float) ($txn->final_amount ?: ($txn->amount_paid ?: 0)), 2),
                                            'method' => strtoupper($txn->payment_method ?: 'ONLINE'),
                                            'status' => strtoupper($txn->status ?: 'PENDING'),
                                            'created_at' => $txn->created_at ? $txn->created_at->format('d M Y, h:i A') : '—',
                                            'approved_at' => $txn->approved_at ? $txn->approved_at->format('d M Y, h:i A') : '—',
                                            'expires_at' => $txn->expires_at ? $txn->expires_at->format('d M Y, h:i A') : '—',
                                            'admin_note' => $txn->admin_note,
                                            'payment_proof' => $txn->payment_proof ? asset('storage/' . $txn->payment_proof) : null
                                        ]) }})"
                                        class="p-2 rounded-xl bg-slate-100 hover:bg-blue-600 hover:text-white text-slate-700 transition-all shadow-2xs" 
                                        title="View Full Transaction Details">
                                    <i class="ph-bold ph-eye text-sm"></i>
                                </button>

                                {{-- Quick Approve / Reject for Pending --}}
                                @if($txn->status === 'pending')
                                    <form method="POST" action="{{ route('admin.transactions.approve', $txn) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="p-2 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white transition-all shadow-2xs" title="Approve Transaction">
                                            <i class="ph-bold ph-check text-sm"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.transactions.reject', $txn) }}" onsubmit="return confirm('Reject this transaction?');" class="inline">
                                        @csrf
                                        <button type="submit" class="p-2 rounded-xl bg-rose-100 hover:bg-rose-600 hover:text-white text-rose-700 transition-all shadow-2xs" title="Reject Transaction">
                                            <i class="ph-bold ph-x text-sm"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-slate-500">
                            <div class="max-w-sm mx-auto flex flex-col items-center">
                                <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center text-2xl mb-3">
                                    <i class="ph-bold ph-receipt-x"></i>
                                </div>
                                <h4 class="text-sm font-extrabold text-slate-900">No Transactions Found</h4>
                                <p class="text-xs text-slate-500 mt-1">There are no user transactions matching the selected criteria.</p>
                                @if($search || $status !== 'all' || $method !== 'all' || $dateRange !== 'all')
                                    <a href="{{ route('admin.transactions') }}" class="mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-all shadow-xs">
                                        Clear All Filters
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($transactions->hasPages())
        <div class="px-6 py-4 border-t border-slate-200/80 bg-slate-50/60">
            {{ $transactions->links() }}
        </div>
        @endif

        {{-- TAB B: RAW PAYMENT GATEWAY LOGS --}}
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-indigo-50/80 text-indigo-900 font-extrabold uppercase tracking-wider text-[10px] border-b border-indigo-200/80">
                        <th class="px-5 py-3.5">Log ID</th>
                        <th class="px-5 py-3.5">User</th>
                        <th class="px-5 py-3.5">Gateway / Method</th>
                        <th class="px-5 py-3.5">Reference / Txn ID</th>
                        <th class="px-5 py-3.5 text-right">Amount (₹)</th>
                        <th class="px-5 py-3.5 text-center">Status</th>
                        <th class="px-5 py-3.5">Details</th>
                        <th class="px-5 py-3.5">Timestamp</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700 font-medium">
                    @forelse($paymentLogs as $log)
                    <tr class="hover:bg-indigo-50/20 transition-colors">
                        <td class="px-5 py-3.5 font-mono text-[11px] text-slate-600">
                            #LOG-{{ $log->id }}
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="font-bold text-slate-900">{{ $log->user->name ?? 'Guest / User #' . $log->user_id }}</span>
                        </td>
                        <td class="px-5 py-3.5 uppercase font-bold text-slate-700 text-[11px]">
                            {{ $log->method }}
                        </td>
                        <td class="px-5 py-3.5 font-mono text-[11px] text-slate-900 select-all">
                            {{ $log->transaction_id ?: '—' }}
                        </td>
                        <td class="px-5 py-3.5 text-right font-black text-slate-900">
                            ₹{{ number_format((float)$log->amount, 2) }}
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            @if($log->status === 'success' || $log->status === 'approved')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Success</span>
                            @elseif($log->status === 'pending')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">Pending</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">{{ ucfirst($log->status) }}</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-[11px] font-mono text-slate-600 max-w-xs truncate" title="{{ json_encode($log->details) }}">
                            {{ json_encode($log->details) }}
                        </td>
                        <td class="px-5 py-3.5 text-[11px] text-slate-500 whitespace-nowrap">
                            {{ $log->created_at ? $log->created_at->format('M d, Y h:i A') : '—' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-slate-500">
                            No gateway payment logs recorded yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($paymentLogs->hasPages())
        <div class="px-6 py-4 border-t border-slate-200/80 bg-slate-50/60">
            {{ $paymentLogs->links() }}
        </div>
        @endif
        @endif

    </div>

</div>

{{-- ========================================================================= --}}
{{-- MODAL: FULL TRANSACTION DETAILS & INVOICE RECEIPT                         --}}
{{-- ========================================================================= --}}
<div id="transactionDetailModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden opacity-0 transition-opacity duration-200">
    <div id="transactionModalCard" class="bg-white rounded-3xl max-w-xl w-full max-h-[90vh] overflow-y-auto shadow-2xl border border-slate-200 transform scale-95 transition-transform duration-200">
        
        {{-- Modal Header --}}
        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-slate-50 to-blue-50/40">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-blue-600 text-white flex items-center justify-center text-lg shadow-sm">
                    <i class="ph-bold ph-receipt"></i>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-900 leading-tight" id="modalInvoiceId">INV-000000</h3>
                    <p class="text-xs text-slate-500" id="modalTxnId">Ref: TXN-000000</p>
                </div>
            </div>
            <button type="button" onclick="closeTransactionModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition-colors">
                <i class="ph-bold ph-x text-sm"></i>
            </button>
        </div>

        {{-- Modal Body --}}
        <div class="p-6 space-y-6 text-xs text-slate-700">
            
            {{-- Status Banner --}}
            <div id="modalStatusBanner" class="p-3.5 rounded-2xl border flex items-center justify-between">
                <div>
                    <span class="text-[10px] uppercase font-bold text-slate-500 block">Transaction Status</span>
                    <span class="text-sm font-black" id="modalStatusText">APPROVED</span>
                </div>
                <span id="modalStatusPill" class="px-3 py-1 rounded-full text-xs font-black">SETTLED</span>
            </div>

            {{-- Customer Information Grid --}}
            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/70 space-y-2">
                <h4 class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Customer Details</h4>
                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div>
                        <span class="text-slate-400 block text-[11px]">Full Name:</span>
                        <strong class="text-slate-900" id="modalUserName">—</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[11px]">Phone:</span>
                        <strong class="text-slate-900" id="modalUserPhone">—</strong>
                    </div>
                    <div class="col-span-2">
                        <span class="text-slate-400 block text-[11px]">Email Address:</span>
                        <strong class="text-slate-900 font-mono" id="modalUserEmail">—</strong>
                    </div>
                </div>
            </div>

            {{-- Purchased Item & Financial Breakdown --}}
            <div class="space-y-3">
                <h4 class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Billing Breakdown</h4>
                <div class="p-4 rounded-2xl border border-slate-200 bg-white space-y-2.5">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-slate-800" id="modalPlanName">Direct Contact Pass</span>
                        <span class="font-semibold text-slate-500" id="modalBillingPeriod">Monthly</span>
                    </div>
                    <div class="border-t border-slate-100 pt-2 space-y-1.5 text-slate-600">
                        <div class="flex justify-between">
                            <span>Base Price:</span>
                            <span id="modalSubtotal">₹0.00</span>
                        </div>
                        <div class="flex justify-between text-emerald-600 font-semibold">
                            <span>Discount / Promotion:</span>
                            <span id="modalDiscount">-₹0.00</span>
                        </div>
                        <div class="flex justify-between">
                            <span>GST (18%):</span>
                            <span id="modalGst">₹0.00</span>
                        </div>
                    </div>
                    <div class="border-t border-slate-200 pt-2 flex justify-between items-center text-sm">
                        <span class="font-extrabold text-slate-900">Total Paid:</span>
                        <span class="font-black text-emerald-700 text-base" id="modalFinal">₹0.00</span>
                    </div>
                </div>
            </div>

            {{-- Audit Timeline & Payment Method --}}
            <div class="grid grid-cols-2 gap-3 bg-slate-50 p-4 rounded-2xl border border-slate-200/70">
                <div>
                    <span class="text-slate-400 text-[10px] uppercase font-bold block">Payment Gateway</span>
                    <strong class="text-slate-800 uppercase" id="modalMethod">RAZORPAY</strong>
                </div>
                <div>
                    <span class="text-slate-400 text-[10px] uppercase font-bold block">Transaction Date</span>
                    <span class="text-slate-800 font-semibold" id="modalCreatedAt">—</span>
                </div>
                <div>
                    <span class="text-slate-400 text-[10px] uppercase font-bold block">Approval Date</span>
                    <span class="text-slate-800 font-semibold" id="modalApprovedAt">—</span>
                </div>
                <div>
                    <span class="text-slate-400 text-[10px] uppercase font-bold block">Expires At</span>
                    <span class="text-slate-800 font-semibold" id="modalExpiresAt">—</span>
                </div>
            </div>

            {{-- Note / Proof if available --}}
            <div id="modalNoteContainer" class="hidden p-3.5 bg-amber-50 rounded-2xl border border-amber-200 text-amber-900 text-xs">
                <span class="font-bold block text-[10px] uppercase text-amber-700 mb-1">Transaction Note:</span>
                <p id="modalNoteText" class="font-medium"></p>
            </div>

            {{-- Payment Proof Image Preview --}}
            <div id="modalProofContainer" class="hidden space-y-2">
                <span class="font-bold block text-[10px] uppercase text-slate-500">Payment Screenshot / Transfer Proof:</span>
                <a id="modalProofLink" href="#" target="_blank" class="block border border-slate-200 rounded-2xl overflow-hidden hover:opacity-90 transition-opacity">
                    <img id="modalProofImg" src="" alt="Payment Proof" class="w-full max-h-56 object-cover">
                </a>
            </div>

        </div>

        {{-- Modal Footer --}}
        <div class="p-6 border-t border-slate-100 flex items-center justify-between bg-slate-50/60">
            <button type="button" onclick="window.print()" class="px-4 py-2 bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 rounded-xl font-bold text-xs shadow-2xs transition-all flex items-center gap-1.5 cursor-pointer">
                <i class="ph-bold ph-printer"></i>
                <span>Print Receipt</span>
            </button>
            <button type="button" onclick="closeTransactionModal()" class="px-5 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-bold text-xs shadow-xs transition-all cursor-pointer">
                Close
            </button>
        </div>

    </div>
</div>

@push('scripts')
<script>
function openTransactionModal(data) {
    document.getElementById('modalInvoiceId').textContent = data.invoice_id;
    document.getElementById('modalTxnId').textContent = 'Ref: ' + data.transaction_id;
    document.getElementById('modalStatusText').textContent = data.status;
    document.getElementById('modalStatusPill').textContent = data.status;

    // Status styling
    const banner = document.getElementById('modalStatusBanner');
    const pill = document.getElementById('modalStatusPill');
    if (data.status === 'APPROVED' || data.status === 'SUCCESS') {
        banner.className = 'p-3.5 rounded-2xl border border-emerald-300 bg-emerald-50 text-emerald-950 flex items-center justify-between';
        pill.className = 'px-3 py-1 rounded-full text-xs font-black bg-emerald-200 text-emerald-900';
    } else if (data.status === 'PENDING') {
        banner.className = 'p-3.5 rounded-2xl border border-amber-300 bg-amber-50 text-amber-950 flex items-center justify-between';
        pill.className = 'px-3 py-1 rounded-full text-xs font-black bg-amber-200 text-amber-900';
    } else {
        banner.className = 'p-3.5 rounded-2xl border border-rose-300 bg-rose-50 text-rose-950 flex items-center justify-between';
        pill.className = 'px-3 py-1 rounded-full text-xs font-black bg-rose-200 text-rose-900';
    }

    document.getElementById('modalUserName').textContent = data.user_name;
    document.getElementById('modalUserPhone').textContent = data.user_phone;
    document.getElementById('modalUserEmail').textContent = data.user_email;

    document.getElementById('modalPlanName').textContent = data.plan_name;
    document.getElementById('modalBillingPeriod').textContent = data.billing_period;
    document.getElementById('modalSubtotal').textContent = '₹' + data.subtotal;
    document.getElementById('modalDiscount').textContent = '-₹' + data.discount;
    document.getElementById('modalGst').textContent = '₹' + data.gst;
    document.getElementById('modalFinal').textContent = '₹' + data.final;

    document.getElementById('modalMethod').textContent = data.method;
    document.getElementById('modalCreatedAt').textContent = data.created_at;
    document.getElementById('modalApprovedAt').textContent = data.approved_at;
    document.getElementById('modalExpiresAt').textContent = data.expires_at;

    const noteBox = document.getElementById('modalNoteContainer');
    if (data.admin_note) {
        document.getElementById('modalNoteText').textContent = data.admin_note;
        noteBox.classList.remove('hidden');
    } else {
        noteBox.classList.add('hidden');
    }

    const proofBox = document.getElementById('modalProofContainer');
    if (data.payment_proof) {
        document.getElementById('modalProofImg').src = data.payment_proof;
        document.getElementById('modalProofLink').href = data.payment_proof;
        proofBox.classList.remove('hidden');
    } else {
        proofBox.classList.add('hidden');
    }

    const modal = document.getElementById('transactionDetailModal');
    const card = document.getElementById('transactionModalCard');
    modal.classList.remove('hidden');
    requestAnimationFrame(() => {
        modal.classList.remove('opacity-0');
        modal.classList.add('opacity-100');
        card.classList.remove('scale-95');
        card.classList.add('scale-100');
    });
}

function closeTransactionModal() {
    const modal = document.getElementById('transactionDetailModal');
    const card = document.getElementById('transactionModalCard');
    modal.classList.remove('opacity-100');
    modal.classList.add('opacity-0');
    card.classList.remove('scale-100');
    card.classList.add('scale-95');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 200);
}

function copyToClipboard(text) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text);
        if (window.showAdminToast) {
            window.showAdminToast('Copied: ' + text, 'info');
        } else {
            alert('Copied: ' + text);
        }
    }
}

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeTransactionModal();
    }
});

document.addEventListener('click', (e) => {
    const modal = document.getElementById('transactionDetailModal');
    if (modal && !modal.classList.contains('hidden') && e.target === modal) {
        closeTransactionModal();
    }
});
</script>
@endpush

@endsection
