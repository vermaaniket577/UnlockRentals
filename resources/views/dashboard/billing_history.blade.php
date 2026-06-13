@extends('layouts.app')

@section('title', 'Billing & Invoice History - UnlockRentals')

@push('head')
<style>
/* Poppins font for headings */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');

:root {
    --dash-primary: #2563eb;
    --dash-accent: #7c3aed;
    --dash-success: #10b981;
    --dash-warning: #f59e0b;
    --dash-danger: #ef4444;
    --dash-bg: #f1f5f9;
    --dash-card: #ffffff;
    --dash-border: rgba(0,0,0,0.07);
    --dash-text: #1e293b;
    --dash-muted: #64748b;
}

.dash-layout {
    display: grid;
    grid-template-columns: 260px 1fr;
    min-height: calc(100vh - 4rem);
    background: var(--dash-bg);
}
@media (max-width: 1024px) {
    .dash-layout { grid-template-columns: 1fr; }
    .dash-sidebar { display: none; }
    .dash-sidebar.open { display: flex; }
}

.dash-sidebar {
    position: sticky;
    top: 4rem;
    height: calc(100vh - 4rem);
    overflow-y: auto;
    background: #ffffff;
    border-right: 1px solid var(--dash-border);
    display: flex;
    flex-direction: column;
    padding: 1.5rem 0;
    z-index: 40;
}
.sidebar-label {
    font-size: 0.65rem;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--dash-muted);
    padding: 0 1.5rem;
    margin: 1.25rem 0 0.5rem;
}
.sidebar-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.65rem 1.5rem;
    margin: 0 0.75rem;
    border-radius: 10px;
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--dash-muted);
    text-decoration: none;
    transition: all 0.2s ease;
    position: relative;
}
.sidebar-link:hover {
    background: #f1f5f9;
    color: var(--dash-text);
}
.sidebar-link.active {
    background: linear-gradient(135deg, rgba(37,99,235,0.12), rgba(124,58,237,0.08));
    color: var(--dash-primary);
    font-weight: 600;
}
.sidebar-link.active::before {
    content: '';
    position: absolute;
    left: -0.75rem;
    top: 50%;
    transform: translateY(-50%);
    width: 3px;
    height: 60%;
    background: linear-gradient(to bottom, var(--dash-primary), var(--dash-accent));
    border-radius: 0 4px 4px 0;
}
.sidebar-link i { font-size: 1.1rem; }

.dash-main {
    padding: 2rem;
}
@media (max-width: 640px) {
    .dash-main { padding: 1.25rem 1rem; }
}

.history-card {
    background: #ffffff;
    border-radius: 20px;
    border: 1px solid var(--dash-border);
    box-shadow: 0 4px 24px rgba(0,0,0,0.04);
    overflow: hidden;
}

.invoice-table {
    width: 100%;
    border-collapse: collapse;
}
.invoice-table th {
    padding: 1rem 1.25rem;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--dash-muted);
    border-bottom: 1px solid var(--dash-border);
    text-align: left;
}
.invoice-table td {
    padding: 1.25rem;
    font-size: 0.875rem;
    color: var(--dash-text);
    border-bottom: 1px solid rgba(0,0,0,0.04);
}
.invoice-table tr:last-child td {
    border-bottom: none;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.25rem 0.65rem;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}
.status-badge.approved { background: rgba(16,185,129,0.1); color: #059669; }
.status-badge.pending { background: rgba(245,158,11,0.1); color: #d97706; }
.status-badge.rejected { background: rgba(239,68,68,0.1); color: #dc2626; }
.status-badge.expired { background: rgba(100,116,139,0.1); color: #475569; }

.sidebar-toggle-btn {
    display: none;
    position: fixed;
    bottom: 1.5rem;
    right: 1.5rem;
    z-index: 50;
    width: 3rem; height: 3rem;
    background: linear-gradient(135deg, var(--dash-primary), var(--dash-accent));
    border-radius: 50%;
    border: none;
    color: #fff;
    font-size: 1.3rem;
    cursor: pointer;
    box-shadow: 0 8px 24px rgba(37,99,235,0.4);
    align-items: center; justify-content: center;
}
@media (max-width: 1024px) {
    .sidebar-toggle-btn { display: flex; }
}

/* Dark Mode Support */
@media (prefers-color-scheme: dark) {
    :root {
        --dash-bg: #0f172a;
        --dash-card: #1e293b;
        --dash-border: rgba(255,255,255,0.06);
        --dash-text: #f1f5f9;
        --dash-muted: #94a3b8;
    }
    .dash-sidebar, .history-card {
        background: #1e293b;
        border-color: rgba(255,255,255,0.06);
    }
    .sidebar-link:hover { background: #263348; }
    .sidebar-link.active { background: rgba(37,99,235,0.2); }
    .invoice-table td { color: #cbd5e1; border-bottom-color: rgba(255,255,255,0.04); }
}

.dash-sidebar.mobile-open {
    position: fixed;
    top: 4rem;
    left: 0;
    width: 280px;
    display: flex;
    box-shadow: 4px 0 40px rgba(0,0,0,0.2);
}
</style>
@endpush

@section('content')
@php
    $user = auth()->user();
    $nameInitial = strtoupper(substr($user->name, 0, 1));
@endphp

{{-- Mobile Sidebar Toggle --}}
<button class="sidebar-toggle-btn" id="sidebar-toggle" onclick="toggleSidebar()" aria-label="Toggle Sidebar">
    <i class="ph-bold ph-list"></i>
</button>

<div class="dash-layout" id="dash-layout">
    {{-- SIDEBAR --}}
    <aside class="dash-sidebar" id="dash-sidebar">
        <div style="padding: 0 1.5rem; margin-bottom: 0.5rem;">
            <div style="display:flex; align-items:center; gap:0.75rem; padding:0.75rem; background:#f8fafc; border-radius:12px;" class="dark:bg-slate-800">
                <div style="width:2.5rem;height:2.5rem;background:linear-gradient(135deg,#2563eb,#7c3aed);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:1rem;font-family:'Poppins',sans-serif;flex-shrink:0;">
                    {{ $nameInitial }}
                </div>
                <div style="min-width:0;">
                    <p style="font-size:0.82rem;font-weight:600;color:#1e293b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" class="dark:text-white">{{ $user->name }}</p>
                    <p style="font-size:0.7rem;color:#64748b;" class="dark:text-slate-400">{{ $user->isOwner() ? 'Owner Account' : 'Tenant Account' }}</p>
                </div>
            </div>
        </div>

        <span class="sidebar-label">Main Menu</span>
        <a href="{{ route('dashboard') }}" class="sidebar-link" id="sidebar-dashboard">
            <i class="ph-bold ph-squares-four"></i> Dashboard
        </a>
        <a href="{{ route('properties.index') }}" class="sidebar-link" id="sidebar-browse">
            <i class="ph-bold ph-binoculars"></i> Browse Properties
        </a>
        @if($user->isTenant())
        <a href="{{ route('properties.index', ['type' => 'house']) }}" class="sidebar-link" id="sidebar-houses">
            <i class="ph-bold ph-house"></i> Houses for Rent
        </a>
        <a href="{{ route('properties.index', ['type' => 'shop']) }}" class="sidebar-link" id="sidebar-shops">
            <i class="ph-bold ph-storefront"></i> Shops for Rent
        </a>
        @else
        <a href="{{ route('properties.create') }}" class="sidebar-link" id="sidebar-list-property">
            <i class="ph-bold ph-plus-circle"></i> List New Property
        </a>
        @endif

        <span class="sidebar-label">Account</span>
        <a href="{{ route('plans.index') }}" class="sidebar-link" id="sidebar-plans">
            <i class="ph-bold ph-crown"></i> Plans & Pricing
        </a>
        <a href="{{ route('billing.history') }}" class="sidebar-link active" id="sidebar-billing">
            <i class="ph-bold ph-receipt"></i> Billing & Invoices
        </a>
        <a href="{{ route('inquiries.index') }}" class="sidebar-link" id="sidebar-inquiries">
            <i class="ph-bold ph-chat-dots"></i> My Inquiries
        </a>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="dash-main">
        <div class="mb-8">
            <h1 class="text-3xl font-black tracking-tight text-slate-950 dark:text-white" style="font-family: 'Poppins', sans-serif;">Billing & Invoices</h1>
            <p class="text-sm text-slate-500 mt-1">Review subscription plan charges, verify payment processing statuses, and view official billing invoices.</p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-semibold rounded-2xl">
                {{ session('success') }}
            </div>
        @endif

        <div class="history-card">
            @if($userPlans->count() > 0)
                <div class="overflow-x-auto">
                    <table class="invoice-table">
                        <thead>
                            <tr>
                                <th>Invoice ID</th>
                                <th>Plan / Period</th>
                                <th>Method</th>
                                <th>Total Paid</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($userPlans as $up)
                                <tr>
                                    <td class="font-mono text-xs font-bold text-blue-600 dark:text-blue-400">
                                        {{ $up->invoice_id ?? 'INV-MOCK-' . $up->id }}
                                    </td>
                                    <td>
                                        <div class="font-semibold text-slate-900 dark:text-white">{{ $up->plan?->name ?? 'Premium Plan' }}</div>
                                        <div class="text-xs text-slate-500">{{ ($up->billing_period ?? 'monthly') === 'yearly' ? 'Buy' : 'Rent' }} plan</div>
                                    </td>
                                    <td class="text-slate-600 dark:text-slate-400 font-medium">
                                        {{ strtoupper($up->payment_method ?? 'UPI') }}
                                    </td>
                                    <td class="font-semibold text-slate-900 dark:text-white">
                                        Rs. {{ number_format($up->final_amount ?? ($up->amount_paid ?? 0.00), 2) }}
                                    </td>
                                    <td class="text-slate-500 dark:text-slate-400">
                                        {{ $up->created_at->format('M d, Y') }}
                                    </td>
                                    <td>
                                        <span class="status-badge {{ $up->status }}">
                                            <i class="ph-bold ph-circle text-[8px]"></i>
                                            {{ $up->status }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($up->status === 'approved')
                                            <a href="{{ route('billing.invoice', $up) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-900 text-white dark:bg-slate-800 dark:hover:bg-slate-700 hover:bg-blue-700 text-xs font-extrabold uppercase tracking-wider rounded-xl transition-all shadow-sm">
                                                <i class="ph-bold ph-printer"></i>
                                                Print
                                            </a>
                                        @else
                                            <span class="text-xs text-slate-400 dark:text-slate-500 font-semibold italic">Verification pending</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($userPlans->hasPages())
                    <div class="p-5 border-t border-slate-100 dark:border-slate-800">
                        {{ $userPlans->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-16 px-4">
                    <div class="mx-auto w-16 h-16 bg-slate-100 dark:bg-slate-800 text-slate-400 rounded-2xl flex items-center justify-center mb-4">
                        <i class="ph-bold ph-receipt text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-black text-slate-900 dark:text-white" style="font-family: 'Poppins', sans-serif;">No invoices found</h3>
                    <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">You have not completed any billing payments yet. Purchase a premium subscription plan to get started.</p>
                    <a href="{{ route('plans.index') }}" class="mt-6 inline-flex items-center gap-2 px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-black rounded-2xl transition-all shadow-lg shadow-blue-500/10">
                        <i class="ph-bold ph-crown"></i>
                        Upgrade Plan
                    </a>
                </div>
            @endif
        </div>
    </main>
</div>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('dash-sidebar');
    sidebar.classList.toggle('mobile-open');
    document.addEventListener('click', function outsideClick(e) {
        if (!sidebar.contains(e.target) && e.target.id !== 'sidebar-toggle' && !document.getElementById('sidebar-toggle').contains(e.target)) {
            sidebar.classList.remove('mobile-open');
            document.removeEventListener('click', outsideClick);
        }
    }, { capture: true });
}
</script>
@endsection
