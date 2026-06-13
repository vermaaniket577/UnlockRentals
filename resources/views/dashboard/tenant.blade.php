@extends('layouts.app')

@section('title', 'My Dashboard - UnlockRentals')
@section('meta_description', 'Manage your rental journey — view inquiries, saved properties, visit bookings and discover new homes on UnlockRentals.')

@push('head')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<style>
/* ============================================================
   PREMIUM TENANT DASHBOARD — Custom Styles
   ============================================================ */

/* Poppins font for headings */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');

/* CSS Custom Properties */
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
    --hero-from: #1e3a8a;
    --hero-to: #4c1d95;
}

/* ── Layout ───────────────────────────────────────────────── */
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

/* ── Sidebar ──────────────────────────────────────────────── */
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
    box-shadow: 2px 0 20px rgba(0,0,0,0.04);
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
.sidebar-badge {
    margin-left: auto;
    background: var(--dash-danger);
    color: #fff;
    font-size: 0.65rem;
    font-weight: 700;
    padding: 0.1rem 0.4rem;
    border-radius: 999px;
    min-width: 1.2rem;
    text-align: center;
}

/* ── Main content area ────────────────────────────────────── */
.dash-main { padding: 0; overflow: hidden; }

/* ── Hero Section ─────────────────────────────────────────── */
.dash-hero {
    background: linear-gradient(135deg, var(--hero-from) 0%, #1e40af 50%, var(--hero-to) 100%);
    padding: 2.5rem 2rem 5rem;
    position: relative;
    overflow: hidden;
}
.dash-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url("{{ asset('images/dashboard-hero.png') }}") center/cover no-repeat;
    opacity: 0.08;
}
.dash-hero-orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(60px);
    opacity: 0.2;
    pointer-events: none;
}
.dash-hero-orb-1 {
    width: 300px; height: 300px;
    background: #60a5fa;
    top: -80px; right: 10%;
    animation: orbFloat 8s ease-in-out infinite;
}
.dash-hero-orb-2 {
    width: 200px; height: 200px;
    background: #a78bfa;
    bottom: -40px; right: 25%;
    animation: orbFloat 10s ease-in-out infinite reverse;
}
.dash-hero-orb-3 {
    width: 150px; height: 150px;
    background: #34d399;
    top: 20px; left: 30%;
    animation: orbFloat 12s ease-in-out infinite;
}
@keyframes orbFloat {
    0%, 100% { transform: translate(0, 0) scale(1); }
    33%       { transform: translate(20px, -15px) scale(1.05); }
    66%       { transform: translate(-10px, 10px) scale(0.95); }
}

.hero-greeting {
    font-family: 'Poppins', sans-serif;
    font-size: 1.85rem;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 0.4rem;
    position: relative;
    z-index: 1;
    animation: fadeSlideUp 0.6s ease both;
}
@media (max-width: 640px) { .hero-greeting { font-size: 1.4rem; } }
.hero-subtitle {
    color: rgba(255,255,255,0.72);
    font-size: 0.9rem;
    position: relative;
    z-index: 1;
    animation: fadeSlideUp 0.7s ease both;
}
.hero-avatar {
    width: 3.5rem; height: 3.5rem;
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(12px);
    border: 2px solid rgba(255,255,255,0.4);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-family: 'Poppins', sans-serif;
    font-size: 1.3rem; font-weight: 700;
    color: #fff;
    flex-shrink: 0;
    box-shadow: 0 8px 32px rgba(0,0,0,0.2);
}
.hero-cta-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.6rem 1.25rem;
    border-radius: 10px;
    font-size: 0.8rem;
    font-weight: 600;
    transition: all 0.25s ease;
    position: relative; z-index: 1;
    text-decoration: none;
}
.hero-cta-primary {
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,0.35);
    color: #fff;
}
.hero-cta-primary:hover {
    background: rgba(255,255,255,0.3);
    transform: translateY(-1px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.2);
}
.hero-cta-secondary {
    background: #ffffff;
    color: var(--dash-primary);
    border: 1px solid rgba(255,255,255,0.8);
}
.hero-cta-secondary:hover {
    transform: translateY(-1px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
}

/* ── Stats Cards ──────────────────────────────────────────── */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    padding: 0 1.75rem;
    margin-top: -2.75rem;
    position: relative;
    z-index: 10;
}
@media (max-width: 1280px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 640px)  { .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 0.75rem; padding: 0 1rem; } }

.stat-card {
    background: #fff;
    border-radius: 16px;
    padding: 1.25rem;
    box-shadow: 0 4px 24px rgba(0,0,0,0.07), 0 1px 4px rgba(0,0,0,0.04);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    cursor: default;
    animation: fadeSlideUp 0.5s ease both;
}
.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.1);
}
.stat-card::after {
    content: '';
    position: absolute;
    top: 0; right: 0;
    width: 80px; height: 80px;
    border-radius: 50%;
    transform: translate(20px, -20px);
    opacity: 0.08;
}
.stat-card.blue::after  { background: #2563eb; }
.stat-card.violet::after { background: #7c3aed; }
.stat-card.green::after  { background: #10b981; }
.stat-card.amber::after  { background: #f59e0b; }

.stat-icon {
    width: 2.6rem; height: 2.6rem;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem;
    margin-bottom: 0.85rem;
}
.stat-icon.blue   { background: rgba(37,99,235,0.1); color: #2563eb; }
.stat-icon.violet { background: rgba(124,58,237,0.1); color: #7c3aed; }
.stat-icon.green  { background: rgba(16,185,129,0.1); color: #10b981; }
.stat-icon.amber  { background: rgba(245,158,11,0.1); color: #d97706; }

.stat-number {
    font-family: 'Poppins', sans-serif;
    font-size: 2rem;
    font-weight: 700;
    color: var(--dash-text);
    line-height: 1;
    display: block;
    margin-bottom: 0.25rem;
}
.stat-label {
    font-size: 0.78rem;
    color: var(--dash-muted);
    font-weight: 500;
}
.stat-trend {
    font-size: 0.72rem;
    margin-top: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.3rem;
}
.stat-trend.up { color: var(--dash-success); }
.stat-trend.neutral { color: var(--dash-muted); }

/* ── Section containers ───────────────────────────────────── */
.dash-section { padding: 1.5rem 1.75rem; }
@media (max-width: 640px) { .dash-section { padding: 1.25rem 1rem; } }

.section-title {
    font-family: 'Poppins', sans-serif;
    font-size: 1rem;
    font-weight: 700;
    color: var(--dash-text);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* ── Quick Actions ────────────────────────────────────────── */
.quick-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0.85rem;
}
@media (max-width: 1100px) { .quick-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 500px)  { .quick-grid { grid-template-columns: repeat(2, 1fr); } }

.quick-card {
    background: #fff;
    border: 1px solid var(--dash-border);
    border-radius: 14px;
    padding: 1.25rem 1rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    gap: 0.65rem;
    text-decoration: none;
    transition: all 0.25s ease;
    cursor: pointer;
}
.quick-card:hover {
    border-color: var(--dash-primary);
    box-shadow: 0 8px 30px rgba(37,99,235,0.12);
    transform: translateY(-2px);
}
.quick-card:hover .quick-icon { transform: scale(1.1); }
.quick-icon {
    width: 3rem; height: 3rem;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem;
    transition: transform 0.25s ease;
}
.quick-label {
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--dash-text);
    line-height: 1.3;
}
.quick-sub {
    font-size: 0.7rem;
    color: var(--dash-muted);
}

/* ── Plan Banner ──────────────────────────────────────────── */
.plan-banner {
    background: linear-gradient(135deg, #1e40af, #7c3aed);
    border-radius: 16px;
    padding: 1.25rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    position: relative;
    overflow: hidden;
    color: #fff;
}
.plan-banner::before {
    content: '';
    position: absolute;
    top: -30px; right: -30px;
    width: 120px; height: 120px;
    background: rgba(255,255,255,0.08);
    border-radius: 50%;
}
.plan-progress-bar {
    height: 5px;
    background: rgba(255,255,255,0.2);
    border-radius: 999px;
    overflow: hidden;
    margin-top: 0.5rem;
}
.plan-progress-fill {
    height: 100%;
    background: #ffffff;
    border-radius: 999px;
    transition: width 1s ease;
}

/* ── Recommended Cards ────────────────────────────────────── */
.rec-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
}
@media (max-width: 1100px) { .rec-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 640px)  { .rec-grid { grid-template-columns: 1fr; } }

.rec-card {
    background: #fff;
    border: 1px solid var(--dash-border);
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.3s ease;
    text-decoration: none;
    display: block;
}
.rec-card:hover {
    box-shadow: 0 12px 40px rgba(0,0,0,0.1);
    transform: translateY(-3px);
}
.rec-img-wrap {
    position: relative;
    height: 160px;
    background: #e2e8f0;
    overflow: hidden;
}
.rec-img-wrap img {
    width: 100%; height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}
.rec-card:hover .rec-img-wrap img { transform: scale(1.06); }
.rec-badge {
    position: absolute;
    top: 0.6rem; left: 0.6rem;
    background: rgba(15,23,42,0.75);
    backdrop-filter: blur(8px);
    color: #fff;
    font-size: 0.65rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 0.2rem 0.55rem;
    border-radius: 999px;
}
.rec-badge.new { background: linear-gradient(90deg, #2563eb, #7c3aed); }
.rec-price-chip {
    position: absolute;
    bottom: 0.6rem; right: 0.6rem;
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(8px);
    color: #1e293b;
    font-size: 0.75rem;
    font-weight: 700;
    padding: 0.25rem 0.6rem;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.12);
}
.rec-body { padding: 0.9rem 1rem 1rem; }
.rec-title {
    font-size: 0.88rem;
    font-weight: 600;
    color: var(--dash-text);
    margin-bottom: 0.3rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.rec-loc {
    font-size: 0.75rem;
    color: var(--dash-muted);
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

/* ── Inquiry Section ──────────────────────────────────────── */
.inquiry-card {
    background: #fff;
    border-radius: 14px;
    border: 1px solid var(--dash-border);
    overflow: hidden;
}
.inquiry-filter-bar {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid var(--dash-border);
    flex-wrap: wrap;
}
.inq-tab {
    padding: 0.35rem 0.85rem;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 600;
    cursor: pointer;
    border: 1.5px solid transparent;
    transition: all 0.2s ease;
    color: var(--dash-muted);
    background: #f8fafc;
}
.inq-tab:hover { background: #e2e8f0; color: var(--dash-text); }
.inq-tab.active {
    background: rgba(37,99,235,0.08);
    border-color: rgba(37,99,235,0.3);
    color: var(--dash-primary);
}
.inq-search {
    margin-left: auto;
    display: flex;
    align-items: center;
    gap: 0.4rem;
    background: #f8fafc;
    border: 1px solid var(--dash-border);
    border-radius: 8px;
    padding: 0.35rem 0.75rem;
    font-size: 0.8rem;
    color: var(--dash-muted);
}
.inq-search input {
    border: none;
    background: transparent;
    outline: none;
    font-size: 0.8rem;
    color: var(--dash-text);
    width: 120px;
}
.inq-row {
    display: flex;
    gap: 1rem;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid rgba(0,0,0,0.04);
    transition: background 0.15s ease;
    align-items: flex-start;
}
.inq-row:last-child { border-bottom: none; }
.inq-row:hover { background: #f8fafc; }
.inq-thumb {
    width: 3.75rem;
    height: 3.75rem;
    border-radius: 10px;
    object-fit: cover;
    flex-shrink: 0;
    background: #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: center;
}
.inq-status-dot {
    width: 7px; height: 7px;
    border-radius: 50%;
    display: inline-block;
    margin-right: 0.3rem;
    flex-shrink: 0;
}
.inq-status-dot.unread  { background: #f59e0b; }
.inq-status-dot.read    { background: #2563eb; }
.inq-status-dot.replied { background: #10b981; }

.inq-status-pill {
    font-size: 0.68rem;
    font-weight: 600;
    padding: 0.15rem 0.55rem;
    border-radius: 999px;
}
.inq-status-pill.unread  { background: rgba(245,158,11,0.1); color: #d97706; }
.inq-status-pill.read    { background: rgba(37,99,235,0.1); color: #2563eb; }
.inq-status-pill.replied { background: rgba(16,185,129,0.1); color: #059669; }

/* ── Empty State ──────────────────────────────────────────── */
.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 3.5rem 2rem;
    text-align: center;
}
.empty-state-icon {
    width: 5rem; height: 5rem;
    background: linear-gradient(135deg, rgba(37,99,235,0.08), rgba(124,58,237,0.08));
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.2rem;
    color: var(--dash-primary);
    margin-bottom: 1.25rem;
    animation: iconPulse 3s ease-in-out infinite;
}
@keyframes iconPulse {
    0%, 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(37,99,235,0.15); }
    50%       { transform: scale(1.04); box-shadow: 0 0 0 12px rgba(37,99,235,0); }
}

/* ── Exclusive Offers ─────────────────────────────────────── */
.excl-offer-card {
    background: #fff;
    border-radius: 14px;
    border: 1px solid rgba(124,58,237,0.2);
    padding: 1rem 1.25rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    transition: all 0.25s ease;
}
.excl-offer-card:hover {
    border-color: rgba(124,58,237,0.5);
    box-shadow: 0 8px 30px rgba(124,58,237,0.1);
}

/* ── Animations ───────────────────────────────────────────── */
@keyframes fadeSlideUp {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
}
.anim-1 { animation-delay: 0.05s; }
.anim-2 { animation-delay: 0.1s; }
.anim-3 { animation-delay: 0.15s; }
.anim-4 { animation-delay: 0.2s; }
.anim-delay-1 { animation: fadeSlideUp 0.6s ease both 0.1s; }
.anim-delay-2 { animation: fadeSlideUp 0.6s ease both 0.2s; }
.anim-delay-3 { animation: fadeSlideUp 0.6s ease both 0.3s; }
.anim-delay-4 { animation: fadeSlideUp 0.6s ease both 0.4s; }

/* ── Chart container ──────────────────────────────────────── */
.chart-card {
    background: #fff;
    border-radius: 14px;
    border: 1px solid var(--dash-border);
    padding: 1.25rem;
}

/* ── Mobile sidebar toggle ────────────────────────────────── */
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
    transition: transform 0.2s ease;
}
.sidebar-toggle-btn:hover { transform: scale(1.08); }
@media (max-width: 1024px) { .sidebar-toggle-btn { display: flex; } }

/* ── Dark mode ────────────────────────────────────────────── */
@media (prefers-color-scheme: dark) {
    :root {
        --dash-bg: #0f172a;
        --dash-card: #1e293b;
        --dash-border: rgba(255,255,255,0.06);
        --dash-text: #f1f5f9;
        --dash-muted: #94a3b8;
    }
    .dash-sidebar, .stat-card, .quick-card, .inquiry-card,
    .rec-card, .chart-card, .excl-offer-card {
        background: #1e293b;
        border-color: rgba(255,255,255,0.06);
    }
    .inq-row:hover { background: #263348; }
    .inq-tab { background: #263348; }
    .inq-search { background: #263348; }
    .sidebar-link:hover { background: #263348; }
    .sidebar-link.active { background: rgba(37,99,235,0.2); }
    .stat-number, .rec-title, .quick-label, .section-title { color: #f1f5f9; }
    .rec-img-wrap, .inq-thumb { background: #334155; }
}

/* ── Responsive padding for mobile sidebar overlay ───────── */
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
    $hour = (int) now()->setTimezone('Asia/Kolkata')->format('G');
    $greeting = $hour < 12 ? 'Good Morning' : ($hour < 17 ? 'Good Afternoon' : 'Good Evening');
    $greetIcon = $hour < 12 ? '☀️' : ($hour < 17 ? '🌤️' : '🌙');
    $nameInitial = strtoupper(substr($user->name, 0, 1));
    $totalInquiries    ??= 0;
    $repliedInquiries  ??= 0;
    $unreadInquiries   ??= 0;
    $savedProperties   ??= 0;
    $recentVisits      ??= 0;
    $activePlan        ??= null;
    $recommendedProperties ??= collect();
@endphp

{{-- Mobile Sidebar Toggle --}}
<button class="sidebar-toggle-btn" id="sidebar-toggle" onclick="toggleSidebar()" aria-label="Toggle Sidebar">
    <i class="ph-bold ph-list"></i>
</button>

<div class="dash-layout" id="dash-layout">

    {{-- ════════════════════════ SIDEBAR ════════════════════════ --}}
    <aside class="dash-sidebar" id="dash-sidebar">
        {{-- User profile summary --}}
        <div style="padding: 0 1.5rem; margin-bottom: 0.5rem;">
            <div style="display:flex; align-items:center; gap:0.75rem; padding:0.75rem; background:#f8fafc; border-radius:12px;">
                <div style="width:2.5rem;height:2.5rem;background:linear-gradient(135deg,#2563eb,#7c3aed);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:1rem;font-family:'Poppins',sans-serif;flex-shrink:0;">
                    {{ $nameInitial }}
                </div>
                <div style="min-width:0;">
                    <p style="font-size:0.82rem;font-weight:600;color:#1e293b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $user->name }}</p>
                    <p style="font-size:0.7rem;color:#64748b;">Tenant Account</p>
                </div>
            </div>
        </div>

        <span class="sidebar-label">Main Menu</span>
        <a href="{{ route('dashboard') }}" class="sidebar-link active" id="sidebar-dashboard">
            <i class="ph-bold ph-squares-four"></i> Dashboard
        </a>
        <a href="{{ route('properties.index') }}" class="sidebar-link" id="sidebar-browse">
            <i class="ph-bold ph-binoculars"></i> Browse Properties
        </a>
        <a href="{{ route('properties.index', ['type' => 'house']) }}" class="sidebar-link" id="sidebar-houses">
            <i class="ph-bold ph-house"></i> Houses for Rent
        </a>
        <a href="{{ route('properties.index', ['type' => 'shop']) }}" class="sidebar-link" id="sidebar-shops">
            <i class="ph-bold ph-storefront"></i> Shops for Rent
        </a>

        <span class="sidebar-label">Account</span>
        <a href="{{ route('plans.index') }}" class="sidebar-link" id="sidebar-plans">
            <i class="ph-bold ph-crown"></i> Plans & Pricing
            @if(!$activePlan)
                <span class="sidebar-badge">!</span>
            @endif
        </a>
        <a href="{{ route('billing.history') }}" class="sidebar-link" id="sidebar-billing">
            <i class="ph-bold ph-receipt"></i> Billing & Invoices
        </a>
        <a href="{{ route('dashboard') }}#inquiries" class="sidebar-link" id="sidebar-inquiries">
            <i class="ph-bold ph-chat-dots"></i> My Inquiries
            @if($unreadInquiries > 0)
                <span class="sidebar-badge">{{ $unreadInquiries }}</span>
            @endif
        </a>

        {{-- Plan status widget --}}
        @if($activePlan)
        <div style="margin: auto 0.75rem 1rem; padding: 1rem; background: linear-gradient(135deg, rgba(37,99,235,0.08), rgba(124,58,237,0.06)); border-radius:12px; border: 1px solid rgba(37,99,235,0.15);">
            <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.5rem;">
                <i class="ph-bold ph-crown" style="color:#7c3aed;font-size:0.95rem;"></i>
                <span style="font-size:0.75rem;font-weight:700;color:#7c3aed;">{{ $activePlan->plan?->name ?? 'Active Plan' }}</span>
            </div>
            @php
                $total    = $activePlan->plan?->contact_limit ?? 0;
                $used     = $activePlan->contacts_used ?? 0;
                $pct      = $total > 0 ? min(100, round($used / $total * 100)) : 0;
                $daysLeft = $activePlan->expires_at ? max(0, now()->diffInDays($activePlan->expires_at, false)) : '∞';
            @endphp
            <div style="font-size:0.7rem;color:#64748b;margin-bottom:0.35rem;">{{ $used }}/{{ $total }} unlocks used</div>
            <div style="height:4px;background:rgba(0,0,0,0.08);border-radius:999px;overflow:hidden;">
                <div style="height:100%;width:{{ $pct }}%;background:linear-gradient(90deg,#2563eb,#7c3aed);border-radius:999px;"></div>
            </div>
            <div style="font-size:0.68rem;color:#94a3b8;margin-top:0.4rem;">{{ $daysLeft }} days remaining</div>
        </div>
        @else
        <div style="margin: auto 0.75rem 1rem; padding: 1rem; background: rgba(245,158,11,0.06); border-radius:12px; border: 1px dashed rgba(245,158,11,0.4);">
            <p style="font-size:0.75rem;font-weight:600;color:#d97706;margin-bottom:0.4rem;">No Active Plan</p>
            <p style="font-size:0.7rem;color:#92400e;margin-bottom:0.6rem;">Get a plan to unlock property contacts.</p>
            <a href="{{ route('plans.index') }}" style="display:inline-flex;align-items:center;gap:0.3rem;font-size:0.72rem;font-weight:700;color:#2563eb;text-decoration:none;">
                View Plans <i class="ph ph-arrow-right" style="font-size:0.8rem;"></i>
            </a>
        </div>
        @endif
    </aside>

    {{-- ══════════════════════ MAIN CONTENT ══════════════════════ --}}
    <main class="dash-main">

        {{-- ── Hero ────────────────────────────────────────────── --}}
        <div class="dash-hero">
            <div class="dash-hero-orb dash-hero-orb-1"></div>
            <div class="dash-hero-orb dash-hero-orb-2"></div>
            <div class="dash-hero-orb dash-hero-orb-3"></div>

            <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1rem;position:relative;z-index:1;">
                <div class="hero-avatar">{{ $nameInitial }}</div>
                <div>
                    <p class="hero-greeting">{{ $greetIcon }} {{ $greeting }}, {{ explode(' ', $user->name)[0] }}!</p>
                    <p class="hero-subtitle">Ready to find your perfect home? Let's explore your dashboard.</p>
                </div>
            </div>

            <div style="display:flex;gap:0.75rem;flex-wrap:wrap;position:relative;z-index:1;">
                <a href="{{ route('properties.index') }}" class="hero-cta-btn hero-cta-secondary" id="hero-browse">
                    <i class="ph-bold ph-magnifying-glass"></i> Browse Properties
                </a>
                <a href="{{ route('plans.index') }}" class="hero-cta-btn hero-cta-primary" id="hero-plans">
                    <i class="ph-bold ph-crown"></i> View Plans
                </a>
                @if($activePlan)
                <a href="{{ route('dashboard') }}#inquiries" class="hero-cta-btn hero-cta-primary" id="hero-inquiries">
                    <i class="ph-bold ph-chat-dots"></i> My Inquiries
                </a>
                @endif
            </div>
        </div>

        {{-- ── Stat Cards ───────────────────────────────────────── --}}
        <div class="stats-grid" id="stat-cards">
            <div class="stat-card blue anim-1 anim-delay-1">
                <div class="stat-icon blue"><i class="ph-bold ph-chat-dots"></i></div>
                <span class="stat-number" data-count="{{ $totalInquiries }}">0</span>
                <span class="stat-label">Total Inquiries</span>
                <div class="stat-trend {{ $totalInquiries > 0 ? 'up' : 'neutral' }}">
                    <i class="ph ph-{{ $totalInquiries > 0 ? 'trend-up' : 'minus' }}"></i>
                    {{ $repliedInquiries }} replied
                </div>
            </div>
            <div class="stat-card violet anim-2 anim-delay-2">
                <div class="stat-icon violet"><i class="ph-bold ph-lock-open"></i></div>
                <span class="stat-number" data-count="{{ $savedProperties }}">0</span>
                <span class="stat-label">Contacts Unlocked</span>
                <div class="stat-trend neutral">
                    <i class="ph ph-info"></i>
                    Total properties explored
                </div>
            </div>
            <div class="stat-card green anim-3 anim-delay-3">
                <div class="stat-icon green"><i class="ph-bold ph-calendar-check"></i></div>
                <span class="stat-number" data-count="{{ $recentVisits }}">0</span>
                <span class="stat-label">Visit Bookings</span>
                <div class="stat-trend neutral">
                    <i class="ph ph-clock"></i>
                    Scheduled property tours
                </div>
            </div>
            <div class="stat-card amber anim-4 anim-delay-4">
                <div class="stat-icon amber"><i class="ph-bold ph-{{ $activePlan ? 'crown' : 'warning' }}"></i></div>
                <span class="stat-number" style="font-size:{{ $activePlan ? '1.1' : '1.1' }}rem;font-size:clamp(0.85rem,2vw,1.1rem);padding-top:0.25rem;">
                    {{ $activePlan ? ($activePlan->plan->name ?? 'Active') : 'No Plan' }}
                </span>
                <span class="stat-label">Current Plan</span>
                <div class="stat-trend neutral">
                    @if($activePlan)
                        <i class="ph ph-check-circle" style="color:#10b981;"></i>
                        Active subscription
                    @else
                        <a href="{{ route('plans.index') }}" style="color:#2563eb;font-weight:600;text-decoration:none;font-size:0.72rem;">Upgrade now →</a>
                    @endif
                </div>
            </div>
        </div>

        {{-- ── Exclusive Offers ─────────────────────────────────── --}}
        @if(isset($privateOffers) && $privateOffers->count() > 0)
        <div class="dash-section anim-delay-1">
            <div style="background:linear-gradient(135deg,rgba(124,58,237,0.05),rgba(37,99,235,0.05));border:1px solid rgba(124,58,237,0.2);border-radius:16px;padding:1.25rem 1.5rem;position:relative;overflow:hidden;">
                <div style="position:absolute;top:0;right:0;background:linear-gradient(135deg,#7c3aed,#2563eb);color:white;font-size:0.6rem;font-weight:800;letter-spacing:0.1em;text-transform:uppercase;padding:0.3rem 0.85rem;border-bottom-left-radius:10px;">
                    ✨ Exclusive
                </div>
                <h3 style="font-family:'Poppins',sans-serif;font-size:0.9rem;font-weight:700;color:#4c1d95;margin-bottom:0.25rem;">
                    <i class="ph-bold ph-gift" style="margin-right:0.35rem;"></i>You Have a Custom Plan Offer!
                </h3>
                <p style="font-size:0.78rem;color:#6d28d9;margin-bottom:1rem;">An admin has assigned a special private plan just for you.</p>
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:0.75rem;">
                    @foreach($privateOffers as $offer)
                    <div class="excl-offer-card">
                        <div style="display:flex;align-items:center;gap:0.85rem;">
                            @if($offer->plan->image_path)
                                <img src="{{ asset('storage/' . $offer->plan->image_path) }}" style="width:2.75rem;height:2.75rem;object-fit:cover;border-radius:8px;border:1px solid rgba(0,0,0,0.08);" alt="">
                            @else
                                <div style="width:2.75rem;height:2.75rem;background:linear-gradient(135deg,rgba(124,58,237,0.15),rgba(37,99,235,0.15));border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                    <i class="ph-bold ph-gift" style="color:#7c3aed;font-size:1.1rem;"></i>
                                </div>
                            @endif
                            <div>
                                <p style="font-size:0.82rem;font-weight:700;color:#1e293b;">{{ $offer->plan->name }}</p>
                                <p style="font-size:0.7rem;color:#64748b;margin-top:0.1rem;">
                                    @if($offer->discounted_price)
                                        <span style="text-decoration:line-through;color:#94a3b8;margin-right:0.3rem;">{{ $offer->plan->formatted_price }}</span>
                                    @endif
                                    <span style="font-weight:700;color:#7c3aed;">{{ $offer->formatted_effective_price }}</span>
                                    • {{ $offer->plan->duration_days }}d • {{ $offer->plan->contact_limit }} unlocks
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('plans.checkout', $offer->plan) }}" style="padding:0.45rem 1rem;background:linear-gradient(135deg,#7c3aed,#2563eb);color:#fff;font-size:0.75rem;font-weight:700;border-radius:8px;text-decoration:none;white-space:nowrap;transition:opacity 0.2s;" onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">
                            Buy Now
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- ── Quick Actions ─────────────────────────────────────── --}}
        <div class="dash-section">
            <h2 class="section-title"><i class="ph-bold ph-lightning" style="color:#2563eb;"></i> Quick Actions</h2>
            <div class="quick-grid">
                <a href="{{ route('properties.index') }}" class="quick-card" id="qc-browse">
                    <div class="quick-icon" style="background:rgba(37,99,235,0.1);">
                        <i class="ph-bold ph-binoculars" style="color:#2563eb;"></i>
                    </div>
                    <span class="quick-label">Browse Properties</span>
                    <span class="quick-sub">Find your next home</span>
                </a>
                <a href="{{ route('properties.index', ['type' => 'house']) }}" class="quick-card" id="qc-houses">
                    <div class="quick-icon" style="background:rgba(124,58,237,0.1);">
                        <i class="ph-bold ph-house" style="color:#7c3aed;"></i>
                    </div>
                    <span class="quick-label">Houses for Rent</span>
                    <span class="quick-sub">View available houses</span>
                </a>
                <a href="{{ route('properties.index', ['type' => 'shop']) }}" class="quick-card" id="qc-shops">
                    <div class="quick-icon" style="background:rgba(16,185,129,0.1);">
                        <i class="ph-bold ph-storefront" style="color:#10b981;"></i>
                    </div>
                    <span class="quick-label">Shops for Rent</span>
                    <span class="quick-sub">Browse commercial spaces</span>
                </a>
                <a href="{{ route('plans.index') }}" class="quick-card" id="qc-plans">
                    <div class="quick-icon" style="background:rgba(245,158,11,0.1);">
                        <i class="ph-bold ph-crown" style="color:#d97706;"></i>
                    </div>
                    <span class="quick-label">View Plans</span>
                    <span class="quick-sub">Unlock more contacts</span>
                </a>
            </div>
        </div>

        {{-- ── Active Plan Banner ────────────────────────────────── --}}
        @if($activePlan)
        @php
            $planTotal = $activePlan->plan->contact_limit ?? 0;
            $planUsed  = $activePlan->contacts_used ?? 0;
            $planPct   = $planTotal > 0 ? min(100, round($planUsed / $planTotal * 100)) : 0;
            $planDays  = $activePlan->expires_at ? max(0, now()->diffInDays($activePlan->expires_at, false)) : '∞';
        @endphp
        <div class="dash-section">
            <div class="plan-banner">
                <div style="flex-shrink:0;width:2.75rem;height:2.75rem;background:rgba(255,255,255,0.15);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.4rem;backdrop-filter:blur(8px);">
                    👑
                </div>
                <div style="flex:1;min-width:0;position:relative;z-index:1;">
                    <p style="font-size:0.75rem;font-weight:700;color:rgba(255,255,255,0.7);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.1rem;">Active Plan</p>
                    <p style="font-size:1rem;font-weight:700;color:#fff;font-family:'Poppins',sans-serif;">{{ $activePlan->plan->name ?? 'Premium Plan' }}</p>
                    <div style="display:flex;align-items:center;gap:1.25rem;margin-top:0.5rem;flex-wrap:wrap;">
                        <span style="font-size:0.75rem;color:rgba(255,255,255,0.75);">
                            <i class="ph ph-lock-open"></i> {{ $planTotal - $planUsed }} unlocks left
                        </span>
                        <span style="font-size:0.75rem;color:rgba(255,255,255,0.75);">
                            <i class="ph ph-calendar"></i> {{ $planDays }} days remaining
                        </span>
                    </div>
                    <div class="plan-progress-bar" style="margin-top:0.75rem;max-width:240px;">
                        <div class="plan-progress-fill" style="width:{{ $planPct }}%;"></div>
                    </div>
                </div>
                <a href="{{ route('plans.index') }}" style="flex-shrink:0;padding:0.55rem 1.1rem;background:rgba(255,255,255,0.15);backdrop-filter:blur(8px);border:1px solid rgba(255,255,255,0.3);border-radius:10px;color:#fff;font-size:0.78rem;font-weight:700;text-decoration:none;transition:all 0.2s;position:relative;z-index:1;" onmouseover="this.style.background='rgba(255,255,255,0.25)'" onmouseout="this.style.background='rgba(255,255,255,0.15)'">
                    Upgrade
                </a>
            </div>
        </div>
        @endif

        {{-- ── Recommended Properties ────────────────────────────── --}}
        @if($recommendedProperties->count() > 0)
        <div class="dash-section">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
                <h2 class="section-title" style="margin-bottom:0;"><i class="ph-bold ph-sparkle" style="color:#7c3aed;"></i> Recommended for You</h2>
                <a href="{{ route('properties.index') }}" style="font-size:0.8rem;font-weight:600;color:#2563eb;text-decoration:none;display:flex;align-items:center;gap:0.3rem;">
                    View all <i class="ph ph-arrow-right"></i>
                </a>
            </div>
            <div class="rec-grid">
                @foreach($recommendedProperties as $i => $property)
                <a href="{{ route('properties.show', $property) }}" class="rec-card">
                    <div class="rec-img-wrap">
                        @if($property->primaryImage)
                            <img src="{{ $property->primaryImage->imageUrl() }}" alt="{{ $property->title }}" loading="lazy">
                        @else
                            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,rgba(37,99,235,0.08),rgba(124,58,237,0.08));">
                                <i class="ph ph-house-line" style="font-size:3rem;color:#94a3b8;"></i>
                            </div>
                        @endif
                        @if($i === 0)
                            <span class="rec-badge new">✨ New</span>
                        @else
                            <span class="rec-badge">{{ ucfirst($property->type ?? 'Property') }}</span>
                        @endif
                        @if($property->price)
                            <span class="rec-price-chip">₹{{ number_format($property->price) }}/mo</span>
                        @endif
                    </div>
                    <div class="rec-body">
                        <p class="rec-title">{{ $property->title }}</p>
                        <p class="rec-loc">
                            <i class="ph ph-map-pin" style="color:#2563eb;font-size:0.85rem;"></i>
                            {{ $property->city ?? ($property->location ?? 'Location not specified') }}
                        </p>
                        <div style="display:flex;gap:0.6rem;margin-top:0.6rem;flex-wrap:wrap;">
                            @if($property->bedrooms)
                                <span style="font-size:0.68rem;color:#64748b;background:#f8fafc;padding:0.15rem 0.45rem;border-radius:6px;border:1px solid rgba(0,0,0,0.06);">
                                    <i class="ph ph-bed"></i> {{ $property->bedrooms }} BHK
                                </span>
                            @endif
                            @if($property->bathrooms)
                                <span style="font-size:0.68rem;color:#64748b;background:#f8fafc;padding:0.15rem 0.45rem;border-radius:6px;border:1px solid rgba(0,0,0,0.06);">
                                    <i class="ph ph-bathtub"></i> {{ $property->bathrooms }} Bath
                                </span>
                            @endif
                            <span style="font-size:0.68rem;font-weight:600;color:#10b981;background:rgba(16,185,129,0.08);padding:0.15rem 0.45rem;border-radius:6px;">
                                Available
                            </span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ── Recent Inquiries ──────────────────────────────────── --}}
        <div class="dash-section" id="inquiries">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
                <h2 class="section-title" style="margin-bottom:0;"><i class="ph-bold ph-chat-dots" style="color:#2563eb;"></i> My Recent Inquiries</h2>
                @if($inquiries->count() > 0)
                    <span style="font-size:0.75rem;color:#64748b;">{{ $totalInquiries }} total</span>
                @endif
            </div>

            <div class="inquiry-card">
                @if($inquiries->count() > 0)
                    {{-- Filter / Search Bar --}}
                    <div class="inquiry-filter-bar">
                        <button class="inq-tab active" onclick="filterInquiries('all', this)" data-filter="all">All</button>
                        <button class="inq-tab" onclick="filterInquiries('unread', this)" data-filter="unread">
                            Unread @if($unreadInquiries > 0)<span style="background:#f59e0b;color:#fff;border-radius:999px;padding:0.05rem 0.4rem;font-size:0.65rem;margin-left:0.25rem;">{{ $unreadInquiries }}</span>@endif
                        </button>
                        <button class="inq-tab" onclick="filterInquiries('read', this)" data-filter="read">Read</button>
                        <button class="inq-tab" onclick="filterInquiries('replied', this)" data-filter="replied">Replied</button>
                        <div class="inq-search" style="margin-left:auto;">
                            <i class="ph ph-magnifying-glass" style="color:#94a3b8;font-size:0.9rem;"></i>
                            <input type="text" placeholder="Search inquiries…" id="inq-search-input" oninput="searchInquiries(this.value)">
                        </div>
                    </div>

                    {{-- Inquiry List --}}
                    <div id="inq-list">
                        @foreach($inquiries as $inquiry)
                        <div class="inq-row" data-status="{{ $inquiry->status }}" data-search="{{ strtolower($inquiry->property?->title ?? '') }} {{ strtolower($inquiry->message) }}">
                            {{-- Thumbnail --}}
                            @if($inquiry->property && $inquiry->property->primaryImage)
                                <img src="{{ $inquiry->property->primaryImage->imageUrl() }}" class="inq-thumb" alt="" loading="lazy" style="object-fit:cover;">
                            @else
                                <div class="inq-thumb" style="background:linear-gradient(135deg,rgba(37,99,235,0.08),rgba(124,58,237,0.08));">
                                    <i class="ph ph-house-line" style="font-size:1.4rem;color:#94a3b8;"></i>
                                </div>
                            @endif

                            {{-- Content --}}
                            <div style="flex:1;min-width:0;">
                                @if($inquiry->property)
                                    <a href="{{ route('properties.show', $inquiry->property) }}" style="font-size:0.875rem;font-weight:600;color:#1e293b;text-decoration:none;display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:100%;" onmouseover="this.style.color='#2563eb'" onmouseout="this.style.color='#1e293b'">
                                        {{ $inquiry->property->title }}
                                    </a>
                                @else
                                    <span style="font-size:0.875rem;font-weight:600;color:#94a3b8;">[Property Removed]</span>
                                @endif
                                <p style="font-size:0.78rem;color:#64748b;margin:0.25rem 0;line-height:1.5;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">
                                    {{ Str::limit($inquiry->message, 100) }}
                                </p>
                                <div style="display:flex;align-items:center;gap:0.6rem;flex-wrap:wrap;">
                                    <span style="font-size:0.7rem;color:#94a3b8;display:flex;align-items:center;gap:0.25rem;">
                                        <i class="ph ph-clock"></i> {{ $inquiry->created_at->diffForHumans() }}
                                    </span>
                                    <span class="inq-status-pill {{ $inquiry->status }}">
                                        <span class="inq-status-dot {{ $inquiry->status }}"></span>
                                        {{ ucfirst($inquiry->status) }}
                                    </span>
                                    @if($inquiry->property)
                                        <span style="font-size:0.7rem;color:#94a3b8;margin-left:auto;">
                                            <i class="ph ph-map-pin"></i>
                                            {{ $inquiry->property->city ?? '' }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                        <div id="inq-no-results" style="display:none;padding:2rem;text-align:center;color:#94a3b8;font-size:0.85rem;">
                            <i class="ph ph-magnifying-glass" style="font-size:1.5rem;display:block;margin-bottom:0.5rem;"></i>
                            No inquiries match your filter.
                        </div>
                    </div>

                @else
                    {{-- Empty State --}}
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="ph-bold ph-chat-dots"></i>
                        </div>
                        <h3 style="font-family:'Poppins',sans-serif;font-size:1.1rem;font-weight:700;color:#1e293b;margin-bottom:0.5rem;">No Inquiries Yet</h3>
                        <p style="font-size:0.875rem;color:#64748b;max-width:340px;line-height:1.6;margin-bottom:1.5rem;">
                            You haven't sent any inquiries yet. Browse properties and reach out to owners to get started!
                        </p>
                        <div style="display:flex;gap:0.75rem;flex-wrap:wrap;justify-content:center;">
                            <a href="{{ route('properties.index') }}" style="display:inline-flex;align-items:center;gap:0.5rem;padding:0.7rem 1.5rem;background:linear-gradient(135deg,#2563eb,#7c3aed);color:#fff;font-size:0.85rem;font-weight:700;border-radius:10px;text-decoration:none;box-shadow:0 4px 20px rgba(37,99,235,0.3);transition:all 0.2s;" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 28px rgba(37,99,235,0.4)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 20px rgba(37,99,235,0.3)'" id="empty-browse-btn">
                                <i class="ph-bold ph-magnifying-glass"></i> Browse Properties
                            </a>
                            <a href="{{ route('properties.index', ['type' => 'house']) }}" style="display:inline-flex;align-items:center;gap:0.5rem;padding:0.7rem 1.5rem;background:#f8fafc;border:1px solid rgba(0,0,0,0.1);color:#1e293b;font-size:0.85rem;font-weight:600;border-radius:10px;text-decoration:none;" id="empty-houses-btn">
                                <i class="ph-bold ph-house"></i> Houses for Rent
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- ── Inquiry Chart ─────────────────────────────────────── --}}
        @if($totalInquiries > 0)
        <div class="dash-section" style="padding-top:0;">
            <div class="chart-card">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
                    <h2 class="section-title" style="margin-bottom:0;"><i class="ph-bold ph-chart-bar" style="color:#7c3aed;"></i> Inquiry Overview</h2>
                    <span style="font-size:0.72rem;color:#94a3b8;">Status breakdown</span>
                </div>
                <div style="max-width:320px;margin:0 auto;">
                    <canvas id="inqChart" height="200"></canvas>
                </div>
            </div>
        </div>
        @endif

        {{-- Spacer --}}
        <div style="height: 3rem;"></div>
    </main>
</div>

@push('scripts')
<script>
// ── Animated Counter ────────────────────────────────────────────────
(function() {
    const nums = document.querySelectorAll('.stat-number[data-count]');
    nums.forEach(el => {
        const target = parseInt(el.getAttribute('data-count'), 10) || 0;
        if (target === 0) { el.textContent = '0'; return; }
        let start = 0;
        const duration = 1000;
        const step = Math.ceil(target / (duration / 16));
        const timer = setInterval(() => {
            start = Math.min(start + step, target);
            el.textContent = start;
            if (start >= target) clearInterval(timer);
        }, 16);
    });
})();

// ── Inquiry Filter ────────────────────────────────────────────────
function filterInquiries(status, btn) {
    document.querySelectorAll('.inq-tab').forEach(t => t.classList.remove('active'));
    btn.classList.add('active');
    const rows = document.querySelectorAll('.inq-row');
    let visible = 0;
    rows.forEach(row => {
        const match = status === 'all' || row.dataset.status === status;
        row.style.display = match ? 'flex' : 'none';
        if (match) visible++;
    });
    const noRes = document.getElementById('inq-no-results');
    if (noRes) noRes.style.display = visible === 0 ? 'block' : 'none';
}

// ── Inquiry Search ────────────────────────────────────────────────
function searchInquiries(query) {
    const q = query.toLowerCase().trim();
    const rows = document.querySelectorAll('.inq-row');
    let visible = 0;
    rows.forEach(row => {
        const haystack = row.dataset.search || '';
        const match = !q || haystack.includes(q);
        row.style.display = match ? 'flex' : 'none';
        if (match) visible++;
    });
    const noRes = document.getElementById('inq-no-results');
    if (noRes) noRes.style.display = visible === 0 ? 'block' : 'none';
}

// ── Sidebar Toggle ────────────────────────────────────────────────
function toggleSidebar() {
    const sidebar = document.getElementById('dash-sidebar');
    sidebar.classList.toggle('mobile-open');
    // Close on outside click
    document.addEventListener('click', function outsideClick(e) {
        if (!sidebar.contains(e.target) && e.target.id !== 'sidebar-toggle') {
            sidebar.classList.remove('mobile-open');
            document.removeEventListener('click', outsideClick);
        }
    }, { capture: true });
}

// ── Chart.js Inquiry Doughnut ─────────────────────────────────────
@if($totalInquiries > 0)
(function() {
    const ctx = document.getElementById('inqChart');
    if (!ctx) return;
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Unread', 'Read', 'Replied'],
            datasets: [{
                data: [
                    {{ $unreadInquiries ?? 0 }},
                    {{ ($totalInquiries ?? 0) - ($unreadInquiries ?? 0) - ($repliedInquiries ?? 0) }},
                    {{ $repliedInquiries ?? 0 }}
                ],
                backgroundColor: [
                    'rgba(245,158,11,0.85)',
                    'rgba(37,99,235,0.85)',
                    'rgba(16,185,129,0.85)'
                ],
                borderColor: '#ffffff',
                borderWidth: 3,
                hoverOffset: 6
            }]
        },
        options: {
            responsive: true,
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 16,
                        font: { size: 12, family: 'Inter' },
                        usePointStyle: true,
                        pointStyleWidth: 8
                    }
                },
                tooltip: {
                    callbacks: {
                        label: (ctx) => ` ${ctx.label}: ${ctx.raw}`
                    }
                }
            }
        }
    });
})();
@endif
</script>
@endpush
@endsection
