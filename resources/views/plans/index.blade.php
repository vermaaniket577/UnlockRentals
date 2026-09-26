@extends('layouts.app')

@section('title', 'Membership Plans & Pricing - UnlockRentals')
@section('meta_description', 'Choose an UnlockRentals subscription plan for instant direct owner contact unlocks, zero brokerage, verified phone numbers, and instant visit booking.')

@push('head')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');

    /* ==========================================
       MATRIMONIAL-STYLE PLANS PAGE
       Clean, structured, premium layout
    ========================================== */

    .plans-page {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        background: #f8f9fb;
        min-height: 100vh;
    }
    .dark .plans-page {
        background: #0a0e1a;
    }

    /* ---- Horizontal Tab Navigation ---- */
    .plans-tab-bar {
        display: flex;
        align-items: center;
        background: #ffffff;
        border-bottom: 1px solid #e5e7eb;
        padding: 0 16px;
        overflow-x: auto;
        scrollbar-width: none;
        -ms-overflow-style: none;
        position: sticky;
        top: 64px;
        z-index: 30;
        gap: 0;
    }
    .dark .plans-tab-bar {
        background: #111827;
        border-bottom-color: #1f2937;
    }
    .plans-tab-bar::-webkit-scrollbar { display: none; }

    .plan-tab {
        position: relative;
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 14px 22px;
        font-size: 13px;
        font-weight: 700;
        color: #6b7280;
        white-space: nowrap;
        cursor: pointer;
        border: none;
        background: transparent;
        border-bottom: 3px solid transparent;
        transition: all 0.2s ease;
        letter-spacing: 0.01em;
    }
    .dark .plan-tab {
        color: #9ca3af;
    }
    .plan-tab:hover {
        color: #1e40af;
        background: rgba(37, 99, 235, 0.04);
    }
    .dark .plan-tab:hover {
        color: #60a5fa;
        background: rgba(59, 130, 246, 0.08);
    }
    .plan-tab.active {
        color: #1d4ed8;
        border-bottom-color: #2563eb;
    }
    .dark .plan-tab.active {
        color: #60a5fa;
        border-bottom-color: #3b82f6;
    }
    .plan-tab .tab-badge {
        display: inline-flex;
        align-items: center;
        padding: 2px 8px;
        font-size: 9px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        border-radius: 4px;
        line-height: 1.4;
    }
    .plan-tab .tab-badge.popular {
        background: #f59e0b;
        color: #451a03;
    }
    .plan-tab .tab-badge.vip {
        background: #8b5cf6;
        color: #fff;
    }
    .plan-tab .tab-badge.budget {
        background: #e5e7eb;
        color: #374151;
    }
    .dark .plan-tab .tab-badge.budget {
        background: #374151;
        color: #d1d5db;
    }
    .plan-tab .tab-badge.buyer {
        background: #10b981;
        color: #fff;
    }
    .plan-tab .tab-icon {
        font-size: 16px;
    }

    /* ---- Main Content Area ---- */
    .plan-detail-section {
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px 60px;
    }

    /* ---- Category Toggle (Rental / Buyer) ---- */
    .category-toggle {
        display: flex;
        align-items: center;
        gap: 4px;
        padding: 4px;
        background: #f1f5f9;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        width: fit-content;
        margin: 0 auto 36px;
    }
    .dark .category-toggle {
        background: #1e293b;
        border-color: #334155;
    }
    .category-toggle-btn {
        padding: 10px 28px;
        font-size: 14px;
        font-weight: 700;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        background: transparent;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .dark .category-toggle-btn {
        color: #94a3b8;
    }
    .category-toggle-btn.active {
        background: #ffffff;
        color: #1e293b;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08), 0 1px 2px rgba(0,0,0,0.04);
    }
    .dark .category-toggle-btn.active {
        background: #334155;
        color: #f1f5f9;
        box-shadow: 0 2px 8px rgba(0,0,0,0.3);
    }
    .category-toggle-btn .save-tag {
        padding: 2px 7px;
        font-size: 10px;
        font-weight: 800;
        background: #dcfce7;
        color: #166534;
        border-radius: 6px;
        letter-spacing: 0.03em;
    }
    .dark .category-toggle-btn .save-tag {
        background: #064e3b;
        color: #6ee7b7;
    }

    /* ---- Plan Content Panel (Split Layout) ---- */
    .plan-content-panel {
        display: none;
    }
    .plan-content-panel.active {
        display: block;
    }

    .plan-split-layout {
        display: grid;
        grid-template-columns: 1fr 420px;
        gap: 32px;
        align-items: start;
    }
    @media (max-width: 900px) {
        .plan-split-layout {
            grid-template-columns: 1fr;
            gap: 24px;
        }
    }

    /* Left Side - Plan Info */
    .plan-info-left {
        padding: 0;
    }
    .plan-info-left .plan-badge-row {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 8px;
    }
    .plan-info-left .plan-type-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 14px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }
    .dark .plan-info-left .plan-type-badge {
        background: #064e3b;
        color: #6ee7b7;
        border-color: #047857;
    }
    .plan-info-left .plan-type-badge.buyer {
        background: #fef3c7;
        color: #92400e;
        border-color: #fde68a;
    }
    .dark .plan-info-left .plan-type-badge.buyer {
        background: #451a03;
        color: #fcd34d;
        border-color: #92400e;
    }
    .plan-info-left h1 {
        font-size: 32px;
        font-weight: 900;
        color: #0f172a;
        line-height: 1.2;
        margin: 0 0 6px;
        letter-spacing: -0.02em;
    }
    .dark .plan-info-left h1 {
        color: #f1f5f9;
    }
    .plan-info-left h1 .plan-name-highlight {
        display: block;
        background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-style: italic;
    }
    .plan-info-left .plan-subtitle {
        font-size: 15px;
        color: #64748b;
        line-height: 1.6;
        max-width: 520px;
        margin-bottom: 28px;
    }
    .dark .plan-info-left .plan-subtitle {
        color: #94a3b8;
    }

    /* Feature Grid (2x2) */
    .feature-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 32px;
    }
    @media (max-width: 600px) {
        .feature-grid {
            grid-template-columns: 1fr;
        }
    }
    .feature-box {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 18px 20px;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        transition: all 0.2s ease;
    }
    .dark .feature-box {
        background: #1e293b;
        border-color: #334155;
    }
    .feature-box:hover {
        border-color: #93c5fd;
        box-shadow: 0 4px 16px rgba(37, 99, 235, 0.06);
    }
    .dark .feature-box:hover {
        border-color: #3b82f6;
        box-shadow: 0 4px 16px rgba(59, 130, 246, 0.1);
    }
    .feature-box .feat-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #dbeafe;
    }
    .dark .feature-box .feat-icon {
        background: #1e3a5f;
        color: #60a5fa;
        border-color: #1e40af;
    }
    .feature-box .feat-icon.green {
        background: #ecfdf5;
        color: #059669;
        border-color: #a7f3d0;
    }
    .dark .feature-box .feat-icon.green {
        background: #064e3b;
        color: #34d399;
        border-color: #047857;
    }
    .feature-box .feat-text h4 {
        font-size: 14px;
        font-weight: 800;
        color: #1e293b;
        margin: 0 0 3px;
    }
    .dark .feature-box .feat-text h4 {
        color: #f1f5f9;
    }
    .feature-box .feat-text p {
        font-size: 12px;
        color: #94a3b8;
        margin: 0;
        line-height: 1.4;
    }
    .dark .feature-box .feat-text p {
        color: #64748b;
    }

    /* Right Side - Pricing Card */
    .pricing-card {
        background: #ffffff;
        border: 2px solid #e2e8f0;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(0,0,0,0.06);
        position: sticky;
        top: 140px;
    }
    .dark .pricing-card {
        background: #1e293b;
        border-color: #334155;
        box-shadow: 0 8px 32px rgba(0,0,0,0.3);
    }
    .pricing-card.popular-card {
        border-color: #2563eb;
        box-shadow: 0 8px 40px rgba(37, 99, 235, 0.12);
    }
    .dark .pricing-card.popular-card {
        border-color: #3b82f6;
        box-shadow: 0 8px 40px rgba(59, 130, 246, 0.15);
    }

    .pricing-card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 20px 24px;
        border-bottom: 1px solid #f1f5f9;
    }
    .dark .pricing-card-header {
        border-bottom-color: #334155;
    }
    .pricing-card-header .card-tab {
        padding: 8px 16px;
        font-size: 13px;
        font-weight: 700;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        background: transparent;
        color: #94a3b8;
        transition: all 0.15s ease;
    }
    .pricing-card-header .card-tab.active {
        background: #2563eb;
        color: #ffffff;
        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.25);
    }
    .dark .pricing-card-header .card-tab.active {
        background: #3b82f6;
    }
    .pricing-card-header .card-icon {
        margin-left: auto;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #eff6ff;
        color: #2563eb;
        font-size: 18px;
    }
    .dark .pricing-card-header .card-icon {
        background: #1e3a5f;
        color: #60a5fa;
    }

    .pricing-card-body {
        padding: 28px 24px;
    }

    .pricing-card .price-row {
        margin-bottom: 4px;
    }
    .pricing-card .price-old {
        font-size: 14px;
        color: #94a3b8;
        text-decoration: line-through;
        font-weight: 600;
    }
    .pricing-card .price-save {
        display: inline-block;
        padding: 2px 8px;
        font-size: 11px;
        font-weight: 800;
        background: #dcfce7;
        color: #166534;
        border-radius: 6px;
        margin-left: 10px;
        letter-spacing: 0.03em;
    }
    .dark .pricing-card .price-save {
        background: #064e3b;
        color: #6ee7b7;
    }
    .pricing-card .price-main {
        display: flex;
        align-items: baseline;
        gap: 4px;
        margin-bottom: 2px;
    }
    .pricing-card .price-main .currency {
        font-size: 24px;
        font-weight: 900;
        color: #0f172a;
    }
    .dark .pricing-card .price-main .currency {
        color: #f1f5f9;
    }
    .pricing-card .price-main .amount {
        font-size: 48px;
        font-weight: 900;
        color: #0f172a;
        letter-spacing: -0.03em;
        line-height: 1;
    }
    .dark .pricing-card .price-main .amount {
        color: #f1f5f9;
    }
    .pricing-card .price-main .period {
        font-size: 14px;
        color: #94a3b8;
        font-weight: 600;
        margin-left: 4px;
    }
    .pricing-card .price-subtitle {
        font-size: 12px;
        color: #10b981;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 20px;
    }

    /* Plan Stats Row */
    .plan-stats {
        display: flex;
        flex-direction: column;
        gap: 12px;
        padding: 16px 0;
        border-top: 1px solid #f1f5f9;
        border-bottom: 1px solid #f1f5f9;
        margin-bottom: 20px;
    }
    .dark .plan-stats {
        border-color: #334155;
    }
    .plan-stat-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 13px;
    }
    .plan-stat-row .stat-label {
        color: #64748b;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .dark .plan-stat-row .stat-label {
        color: #94a3b8;
    }
    .plan-stat-row .stat-label i {
        font-size: 15px;
        color: #94a3b8;
    }
    .dark .plan-stat-row .stat-label i {
        color: #64748b;
    }
    .plan-stat-row .stat-value {
        font-weight: 800;
        color: #1e293b;
    }
    .dark .plan-stat-row .stat-value {
        color: #f1f5f9;
    }
    .plan-stat-row .stat-value.highlight {
        color: #2563eb;
    }
    .dark .plan-stat-row .stat-value.highlight {
        color: #60a5fa;
    }
    .plan-stat-row .stat-value.green {
        color: #059669;
    }
    .dark .plan-stat-row .stat-value.green {
        color: #34d399;
    }

    /* CTA Button */
    .plan-cta-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        padding: 16px 24px;
        font-size: 15px;
        font-weight: 800;
        border: none;
        border-radius: 14px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        letter-spacing: 0.01em;
    }
    .plan-cta-btn.primary {
        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
        color: #ffffff;
        box-shadow: 0 4px 16px rgba(16, 185, 129, 0.3);
    }
    .plan-cta-btn.primary:hover {
        box-shadow: 0 6px 24px rgba(16, 185, 129, 0.4);
        transform: translateY(-1px);
    }
    .plan-cta-btn.blue {
        background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
        color: #ffffff;
        box-shadow: 0 4px 16px rgba(37, 99, 235, 0.3);
    }
    .plan-cta-btn.blue:hover {
        box-shadow: 0 6px 24px rgba(37, 99, 235, 0.4);
        transform: translateY(-1px);
    }
    .plan-cta-btn.dark-btn {
        background: #0f172a;
        color: #ffffff;
    }
    .dark .plan-cta-btn.dark-btn {
        background: #f1f5f9;
        color: #0f172a;
    }
    .plan-cta-btn.dark-btn:hover {
        background: #1e293b;
        transform: translateY(-1px);
    }
    .dark .plan-cta-btn.dark-btn:hover {
        background: #e2e8f0;
    }
    .plan-cta-btn.disabled {
        background: #f1f5f9;
        color: #94a3b8;
        cursor: not-allowed;
        box-shadow: none;
    }
    .dark .plan-cta-btn.disabled {
        background: #1e293b;
        color: #475569;
    }
    .plan-cta-btn.active-plan {
        background: #ecfdf5;
        color: #059669;
        border: 2px solid #a7f3d0;
        cursor: default;
    }
    .dark .plan-cta-btn.active-plan {
        background: #064e3b;
        color: #34d399;
        border-color: #047857;
    }
    .plan-cta-btn.pending-plan {
        background: #fffbeb;
        color: #b45309;
        border: 2px solid #fde68a;
        cursor: default;
    }
    .dark .plan-cta-btn.pending-plan {
        background: #451a03;
        color: #fbbf24;
        border-color: #92400e;
    }

    .plan-compare-link {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 12px;
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        text-decoration: none;
        transition: color 0.15s ease;
    }
    .dark .plan-compare-link {
        color: #94a3b8;
    }
    .plan-compare-link:hover {
        color: #2563eb;
    }

    /* ---- Right Panel: Info Card (beside pricing) ---- */
    .plan-info-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        margin-top: 20px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.04);
    }
    .dark .plan-info-card {
        background: #1e293b;
        border-color: #334155;
    }
    .plan-info-card-img {
        width: 100%;
        height: 160px;
        object-fit: cover;
        display: block;
    }
    .plan-info-card-body {
        padding: 20px;
    }
    .plan-info-card-body .card-badges {
        display: flex;
        gap: 8px;
        margin-bottom: 12px;
    }
    .plan-info-card-body .card-badges span {
        padding: 4px 10px;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-radius: 6px;
    }
    .plan-info-card-body .card-badges .badge-green {
        background: #dcfce7;
        color: #166534;
    }
    .dark .plan-info-card-body .card-badges .badge-green {
        background: #064e3b;
        color: #6ee7b7;
    }
    .plan-info-card-body .card-badges .badge-blue {
        background: #dbeafe;
        color: #1e40af;
    }
    .dark .plan-info-card-body .card-badges .badge-blue {
        background: #1e3a5f;
        color: #93c5fd;
    }
    .plan-info-card-body h3 {
        font-size: 18px;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 8px;
    }
    .dark .plan-info-card-body h3 {
        color: #f1f5f9;
    }
    .plan-info-card-body .card-desc {
        font-size: 13px;
        color: #64748b;
        line-height: 1.6;
        margin-bottom: 16px;
    }
    .dark .plan-info-card-body .card-desc {
        color: #94a3b8;
    }
    .plan-info-card-body .card-checks {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .plan-info-card-body .card-checks li {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        font-weight: 600;
        color: #334155;
    }
    .dark .plan-info-card-body .card-checks li {
        color: #cbd5e1;
    }
    .plan-info-card-body .card-checks li i {
        color: #10b981;
        font-size: 14px;
    }

    .plan-info-card-footer {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 14px 20px;
        border-top: 1px solid #f1f5f9;
        font-size: 12px;
        font-weight: 700;
        color: #7c3aed;
        background: #faf5ff;
    }
    .dark .plan-info-card-footer {
        background: #1e1b4b;
        border-top-color: #334155;
        color: #a78bfa;
    }

    /* ---- Active/Pending Plan Banner ---- */
    .active-plan-banner {
        max-width: 800px;
        margin: 0 auto 24px;
        padding: 16px 20px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        gap: 14px;
        background: #ecfdf5;
        border: 1.5px solid #a7f3d0;
    }
    .dark .active-plan-banner {
        background: #064e3b;
        border-color: #047857;
    }
    .active-plan-banner.pending {
        background: #fffbeb;
        border-color: #fde68a;
    }
    .dark .active-plan-banner.pending {
        background: #451a03;
        border-color: #92400e;
    }
    .active-plan-banner .banner-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
        background: #d1fae5;
        color: #059669;
    }
    .dark .active-plan-banner .banner-icon {
        background: #047857;
        color: #6ee7b7;
    }
    .active-plan-banner.pending .banner-icon {
        background: #fef3c7;
        color: #d97706;
    }
    .dark .active-plan-banner.pending .banner-icon {
        background: #92400e;
        color: #fbbf24;
    }
    .active-plan-banner .banner-text h3 {
        font-size: 14px;
        font-weight: 800;
        color: #065f46;
        margin: 0 0 2px;
    }
    .dark .active-plan-banner .banner-text h3 {
        color: #6ee7b7;
    }
    .active-plan-banner.pending .banner-text h3 {
        color: #92400e;
    }
    .dark .active-plan-banner.pending .banner-text h3 {
        color: #fbbf24;
    }
    .active-plan-banner .banner-text p {
        font-size: 12px;
        color: #047857;
        margin: 0;
    }
    .dark .active-plan-banner .banner-text p {
        color: #34d399;
    }
    .active-plan-banner.pending .banner-text p {
        color: #b45309;
    }
    .dark .active-plan-banner.pending .banner-text p {
        color: #fcd34d;
    }
    .active-plan-banner .banner-badge {
        margin-left: auto;
        padding: 5px 14px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        border-radius: 8px;
        background: #059669;
        color: #fff;
        flex-shrink: 0;
    }

    /* ---- Comparison Section ---- */
    .comparison-section {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px 60px;
    }
    .comparison-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
    }
    .dark .comparison-card {
        background: #111827;
        border-color: #1f2937;
    }
    .comparison-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 24px 28px;
        border-bottom: 1px solid #f1f5f9;
    }
    .dark .comparison-card-header {
        border-bottom-color: #1f2937;
    }
    .comparison-card-header .comp-title {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    .comparison-card-header .comp-title span {
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #2563eb;
    }
    .dark .comparison-card-header .comp-title span {
        color: #60a5fa;
    }
    .comparison-card-header .comp-title h2 {
        font-size: 22px;
        font-weight: 900;
        color: #0f172a;
        margin: 0;
    }
    .dark .comparison-card-header .comp-title h2 {
        color: #f1f5f9;
    }
    .comparison-card-header .comp-secure {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
    }
    .dark .comparison-card-header .comp-secure {
        color: #94a3b8;
    }
    .comparison-card-header .comp-secure i {
        color: #10b981;
    }

    .comparison-table {
        width: 100%;
        text-align: left;
        border-collapse: collapse;
    }
    .comparison-table thead th {
        padding: 14px 20px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #94a3b8;
        border-bottom: 1px solid #f1f5f9;
    }
    .dark .comparison-table thead th {
        color: #64748b;
        border-bottom-color: #1f2937;
    }
    .comparison-table tbody td {
        padding: 14px 20px;
        font-size: 13px;
        font-weight: 600;
        color: #475569;
        border-bottom: 1px solid #f8fafc;
    }
    .dark .comparison-table tbody td {
        color: #cbd5e1;
        border-bottom-color: #1f2937;
    }
    .comparison-table tbody tr:last-child td {
        border-bottom: none;
    }
    .comparison-table tbody td:first-child {
        font-weight: 800;
        color: #0f172a;
    }
    .dark .comparison-table tbody td:first-child {
        color: #f1f5f9;
    }
    .comparison-table .check-cell {
        color: #10b981;
        font-weight: 700;
    }
    .comparison-table .highlight-cell {
        color: #2563eb;
        font-weight: 800;
    }
    .dark .comparison-table .highlight-cell {
        color: #60a5fa;
    }

    /* ---- FAQ Section ---- */
    .faq-section {
        max-width: 780px;
        margin: 0 auto;
        padding: 0 20px 80px;
    }
    .faq-section h2 {
        font-size: 26px;
        font-weight: 900;
        color: #0f172a;
        text-align: center;
        margin-bottom: 28px;
        letter-spacing: -0.02em;
    }
    .dark .faq-section h2 {
        color: #f1f5f9;
    }
    .faq-item {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        margin-bottom: 12px;
        overflow: hidden;
        transition: all 0.2s ease;
    }
    .dark .faq-item {
        background: #111827;
        border-color: #1f2937;
    }
    .faq-item:hover {
        border-color: #93c5fd;
    }
    .dark .faq-item:hover {
        border-color: #3b82f6;
    }
    .faq-question {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px 22px;
        cursor: pointer;
        user-select: none;
    }
    .faq-question h3 {
        font-size: 14px;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        flex: 1;
    }
    .dark .faq-question h3 {
        color: #e2e8f0;
    }
    .faq-question .faq-toggle {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f1f5f9;
        color: #64748b;
        font-size: 14px;
        flex-shrink: 0;
        transition: all 0.2s ease;
    }
    .dark .faq-question .faq-toggle {
        background: #1e293b;
        color: #94a3b8;
    }
    .faq-item.open .faq-toggle {
        background: #2563eb;
        color: #ffffff;
        transform: rotate(45deg);
    }
    .faq-answer {
        display: none;
        padding: 0 22px 18px;
    }
    .faq-item.open .faq-answer {
        display: block;
    }
    .faq-answer p {
        font-size: 13px;
        color: #64748b;
        line-height: 1.7;
        margin: 0;
    }
    .dark .faq-answer p {
        color: #94a3b8;
    }

    /* ---- Flash Alert ---- */
    .plans-flash-alert {
        max-width: 700px;
        margin: 0 auto 20px;
        padding: 14px 20px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 700;
        text-align: center;
    }
    .plans-flash-alert.success {
        background: #ecfdf5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }
    .dark .plans-flash-alert.success {
        background: #064e3b;
        color: #6ee7b7;
        border-color: #047857;
    }
    .plans-flash-alert.error {
        background: #fef2f2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }
    .dark .plans-flash-alert.error {
        background: #450a0a;
        color: #fca5a5;
        border-color: #991b1b;
    }

    /* ---- Plan Feature List (within pricing card) ---- */
    .pricing-feature-list {
        list-style: none;
        padding: 0;
        margin: 0 0 20px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .pricing-feature-list li {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        font-weight: 600;
        color: #334155;
    }
    .dark .pricing-feature-list li {
        color: #cbd5e1;
    }
    .pricing-feature-list li i {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #ecfdf5;
        color: #059669;
        font-size: 11px;
        flex-shrink: 0;
    }
    .dark .pricing-feature-list li i {
        background: #064e3b;
        color: #34d399;
    }

    /* Animate in */
    .plan-content-panel.active .plan-split-layout {
        animation: fadeSlideUp 0.35s ease-out;
    }
    @keyframes fadeSlideUp {
        from { opacity: 0; transform: translateY(16px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('content')
@php
    $paymentFailedReason = session('payment_failed_reason') ?: (request()->boolean('payment_failed') ? request('reason', 'Payment failed. Please try again or choose another payment method.') : null);
    $displayPlans = $plans->values();
    $hasEnterprise = $displayPlans->contains(fn($item) => str_contains(strtolower($item->name), 'enterprise'));

    \App\Models\Plan::ensureBuyerPlansExist();
    if ($displayPlans->whereIn('purpose', ['buy', 'sale'])->isEmpty()) {
        $displayPlans = \App\Models\Plan::public()->get();
    }
    $rentPlans = $displayPlans->filter(fn($p) => in_array($p->purpose, ['rent', 'both', null]));
    $buyPlans = $displayPlans->filter(fn($p) => in_array($p->purpose, ['buy', 'sale']));
    if ($buyPlans->isEmpty()) {
        $buyPlans = $rentPlans;
    }

    $requestedBilling = request('billing');
    $requestedPurpose = request('purpose');
    $initialBilling = ($requestedBilling === 'yearly' || in_array($requestedPurpose, ['buy', 'sale'])) ? 'yearly' : 'monthly';
    $isYearly = ($initialBilling === 'yearly');

    // Build combined plan list for tabs
    $allPlansForTabs = $isYearly ? $buyPlans : $rentPlans;
    $firstPlanId = $allPlansForTabs->first()?->id;
@endphp

<div class="plans-page pb-24">

    {{-- ============================================
         HORIZONTAL TAB BAR (Sticky)
    ============================================ --}}
    <nav class="plans-tab-bar" id="plans-tab-bar" aria-label="Membership Plans">
        {{-- Rental Plan Tabs --}}
        <div id="rental-tabs" class="{{ $isYearly ? 'hidden' : '' }}" style="display: {{ $isYearly ? 'none' : 'flex' }}; align-items: center; gap: 0;">
            @foreach($rentPlans as $index => $plan)
                @php
                    $nameLower = strtolower($plan->name);
                    $isGold = str_contains($nameLower, 'gold') || str_contains($nameLower, 'pro') || str_contains($nameLower, 'popular');
                    $isPlatinum = str_contains($nameLower, 'plat') || str_contains($nameLower, 'diamond') || str_contains($nameLower, 'vip');
                    $tabBadge = $isGold ? 'MOST POPULAR' : ($isPlatinum ? 'VIP CHOICE' : '');
                    $tabBadgeClass = $isGold ? 'popular' : ($isPlatinum ? 'vip' : 'budget');
                @endphp
                <button type="button"
                    class="plan-tab {{ $index === 0 ? 'active' : '' }}"
                    data-plan-tab="rent_{{ $plan->id }}"
                    data-category="rental">
                    @if(str_contains($nameLower, 'silver') || str_contains($nameLower, 'start') || str_contains($nameLower, 'basic'))
                        <i class="ph-bold ph-shield-check tab-icon"></i>
                    @elseif($isGold)
                        <i class="ph-bold ph-crown tab-icon"></i>
                    @elseif($isPlatinum)
                        <i class="ph-bold ph-diamond tab-icon"></i>
                    @else
                        <i class="ph-bold ph-star tab-icon"></i>
                    @endif
                    <span>{{ strtoupper($plan->name) }}</span>
                    @if($tabBadge)
                        <span class="tab-badge {{ $tabBadgeClass }}">{{ $tabBadge }}</span>
                    @endif
                </button>
            @endforeach
            @unless($hasEnterprise)
                <button type="button" class="plan-tab" data-plan-tab="rent_enterprise" data-category="rental">
                    <i class="ph-bold ph-buildings tab-icon"></i>
                    <span>ENTERPRISE</span>
                </button>
            @endunless
        </div>

        {{-- Buyer Plan Tabs --}}
        <div id="buyer-tabs" class="{{ $isYearly ? '' : 'hidden' }}" style="display: {{ $isYearly ? 'flex' : 'none' }}; align-items: center; gap: 0;">
            @foreach($buyPlans as $index => $plan)
                @php
                    $nameLower = strtolower($plan->name);
                    $isGold = str_contains($nameLower, 'gold') || str_contains($nameLower, 'pro') || str_contains($nameLower, 'popular');
                    $isPlatinum = str_contains($nameLower, 'plat') || str_contains($nameLower, 'diamond') || str_contains($nameLower, 'vip');
                    $tabBadge = $isGold ? 'MOST POPULAR' : ($isPlatinum ? 'VIP CHOICE' : '');
                    $tabBadgeClass = $isGold ? 'popular' : ($isPlatinum ? 'vip' : 'budget');
                @endphp
                <button type="button"
                    class="plan-tab {{ $index === 0 ? 'active' : '' }}"
                    data-plan-tab="buy_{{ $plan->id }}"
                    data-category="buyer">
                    @if(str_contains($nameLower, 'silver') || str_contains($nameLower, 'start') || str_contains($nameLower, 'basic'))
                        <i class="ph-bold ph-shield-check tab-icon"></i>
                    @elseif($isGold)
                        <i class="ph-bold ph-crown tab-icon"></i>
                    @elseif($isPlatinum)
                        <i class="ph-bold ph-diamond tab-icon"></i>
                    @else
                        <i class="ph-bold ph-star tab-icon"></i>
                    @endif
                    <span>{{ strtoupper($plan->name) }}</span>
                    @if($tabBadge)
                        <span class="tab-badge {{ $tabBadgeClass }}">{{ $tabBadge }}</span>
                    @endif
                </button>
            @endforeach
            @unless($hasEnterprise)
                <button type="button" class="plan-tab" data-plan-tab="buy_enterprise" data-category="buyer">
                    <i class="ph-bold ph-buildings tab-icon"></i>
                    <span>INSTITUTIONAL BUYER</span>
                </button>
            @endunless

            {{-- Buyer Pass badge on right edge --}}
            <div style="margin-left: auto; padding: 0 16px;">
                <span class="tab-badge buyer" style="font-size: 10px; padding: 4px 10px;">HOME BUYERS</span>
            </div>
        </div>
    </nav>

    {{-- ============================================
         MAIN CONTENT AREA
    ============================================ --}}
    <div class="plan-detail-section">

        {{-- Category Toggle --}}
        <div class="category-toggle" id="category-toggle" role="group" aria-label="Plan category selector">
            <button type="button" class="category-toggle-btn {{ $isYearly ? '' : 'active' }}" data-billing-choice="monthly">
                <i class="ph-bold ph-house-line"></i>
                <span>Rental Pass</span>
            </button>
            <button type="button" class="category-toggle-btn {{ $isYearly ? 'active' : '' }}" data-billing-choice="yearly">
                <i class="ph-bold ph-buildings"></i>
                <span>Buyer Pass</span>
                <span class="save-tag">SAVE 20%</span>
            </button>
        </div>

        {{-- Flash Alerts --}}
        @if(session('success'))
            <div class="plans-flash-alert success">{{ session('success') }}</div>
        @endif
        @if(session('error') || $errors->has('payment_reference'))
            <div class="plans-flash-alert error">{{ session('error') ?? $errors->first('payment_reference') }}</div>
        @endif

        {{-- Active/Pending Subscriptions banner --}}
        @auth
            @php
                $userActivePlans = collect([$activeRentPlan ?? null, $activeBuyPlan ?? null])->filter()->unique('id');
            @endphp
            @if($userActivePlans->isNotEmpty())
                @foreach($userActivePlans as $curPlan)
                    <div class="active-plan-banner">
                        <div class="banner-icon">
                            <i class="ph-bold ph-check-circle"></i>
                        </div>
                        <div class="banner-text">
                            <h3>Active Plan: {{ $curPlan->plan->name ?? 'Premium' }} ({{ $curPlan->plan && $curPlan->plan->isBuyPlan() ? 'Buyer Pass' : 'Rental Plan' }})</h3>
                            <p>{{ $curPlan->remaining_contacts }} contact unlocks remaining · Valid until {{ $curPlan->expires_at->format('M d, Y') }}</p>
                        </div>
                        <span class="banner-badge">Active</span>
                    </div>
                @endforeach
            @elseif($pendingPlan)
                <div class="active-plan-banner pending">
                    <div class="banner-icon">
                        <i class="ph-bold ph-clock"></i>
                    </div>
                    <div class="banner-text">
                        <h3>Payment Review Pending: {{ $pendingPlan->plan->name ?? 'Plan' }}</h3>
                        <p>Admin verification is in progress. Your plan will activate automatically upon confirmation.</p>
                    </div>
                </div>
            @endif
        @endauth


        {{-- ========== RENTAL PLAN PANELS ========== --}}
        @foreach($rentPlans as $index => $plan)
            @php
                $nameLower = strtolower($plan->name);
                $isGold = str_contains($nameLower, 'gold') || str_contains($nameLower, 'pro') || str_contains($nameLower, 'popular');
                $isPlatinum = str_contains($nameLower, 'plat') || str_contains($nameLower, 'diamond') || str_contains($nameLower, 'vip');
                $monthlyOffer = isset($userOffers) ? $userOffers->where('plan_id', $plan->id)->where('billing_period', 'monthly')->first() : null;
                $price = ($monthlyOffer && $monthlyOffer->discounted_price !== null) ? (float) $monthlyOffer->discounted_price : (float) $plan->price;
                $originalPrice = ($monthlyOffer && $monthlyOffer->discounted_price !== null) ? (float) $plan->price : null;
                $savePercent = $originalPrice ? round((($originalPrice - $price) / $originalPrice) * 100) : null;
                $perDay = $plan->duration_days > 0 ? round($price / $plan->duration_days, 1) : 0;
            @endphp
            <div class="plan-content-panel rental-panel {{ $index === 0 && !$isYearly ? 'active' : '' }}"
                 id="panel-rent_{{ $plan->id }}" data-category="rental">
                <div class="plan-split-layout">
                    {{-- LEFT SIDE --}}
                    <div class="plan-info-left">
                        <div class="plan-badge-row">
                            <span class="plan-type-badge">
                                <i class="ph-bold ph-shield-check"></i>
                                ZERO BROKERAGE RENTAL PASS · DIRECT OWNER DEALS
                            </span>
                        </div>

                        <h1>
                            Find Your Dream Rental With
                            <span class="plan-name-highlight">{{ $plan->name }} Pass</span>
                        </h1>
                        <p class="plan-subtitle">
                            {{ $plan->description ?? 'Looking for a flat, PG, or room? Skip the 1-month broker commission and negotiate directly with verified landlords.' }}
                        </p>

                        {{-- Feature Grid --}}
                        <div class="feature-grid">
                            <div class="feature-box">
                                <div class="feat-icon">
                                    <i class="ph-bold ph-user-circle-check"></i>
                                </div>
                                <div class="feat-text">
                                    <h4>{{ $plan->contact_limit }} Verified Direct Owners</h4>
                                    <p>Direct owners & builder representatives</p>
                                </div>
                            </div>
                            <div class="feature-box">
                                <div class="feat-icon green">
                                    <i class="ph-bold ph-currency-inr"></i>
                                </div>
                                <div class="feat-text">
                                    <h4>Save ₹1 Lakh to ₹5 Lakhs</h4>
                                    <p>Zero broker commission on rent transactions</p>
                                </div>
                            </div>
                            <div class="feature-box">
                                <div class="feat-icon">
                                    <i class="ph-bold ph-calendar-check"></i>
                                </div>
                                <div class="feat-text">
                                    <h4>{{ $plan->duration_days }} Days Validity</h4>
                                    <p>Extended window to evaluate deals</p>
                                </div>
                            </div>
                            <div class="feature-box">
                                <div class="feat-icon green">
                                    <i class="ph-bold ph-handshake"></i>
                                </div>
                                <div class="feat-text">
                                    <h4>Direct Negotiation</h4>
                                    <p>Direct owner negotiation access</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT SIDE - Pricing Card --}}
                    <div>
                        <div class="pricing-card {{ $isGold ? 'popular-card' : '' }}">
                            <div class="pricing-card-header">
                                <span class="card-tab active">RENTAL PASS</span>
                                <span class="card-tab" style="cursor: default; opacity: 0.4;">DIRECT PASS</span>
                                <div class="card-icon">
                                    <i class="ph-bold ph-house-line"></i>
                                </div>
                            </div>

                            <div class="pricing-card-body">
                                {{-- Price Row --}}
                                @if($originalPrice)
                                    <div class="price-row">
                                        <span class="price-old">₹{{ number_format($originalPrice, 0) }}</span>
                                        <span class="price-save">SAVE {{ $savePercent }}%</span>
                                    </div>
                                @endif
                                <div class="price-main">
                                    <span class="currency">₹</span>
                                    <span class="amount">{{ number_format($price, 0) }}</span>
                                    <span class="period">/ rental pass</span>
                                </div>
                                <div class="price-subtitle">
                                    <i class="ph-bold ph-seal-check"></i>
                                    Only ₹{{ number_format($perDay, 1) }}/day · {{ $plan->duration_days }} Full Days Validity
                                </div>

                                {{-- Stats --}}
                                <div class="plan-stats">
                                    <div class="plan-stat-row">
                                        <span class="stat-label"><i class="ph-bold ph-phone"></i> Direct Owner Contacts</span>
                                        <span class="stat-value highlight">{{ $plan->contact_limit }} Direct Unlocks</span>
                                    </div>
                                    <div class="plan-stat-row">
                                        <span class="stat-label"><i class="ph-bold ph-calendar"></i> Access Duration</span>
                                        <span class="stat-value">{{ $plan->duration_days }} Full Days</span>
                                    </div>
                                    <div class="plan-stat-row">
                                        <span class="stat-label"><i class="ph-bold ph-hand-coins"></i> Brokerage Fee</span>
                                        <span class="stat-value green">₹0 (Zero Commission)</span>
                                    </div>
                                </div>

                                {{-- CTA --}}
                                @if(auth()->check() && $activeRentPlan && $activeRentPlan->remaining_contacts > 0 && $activeRentPlan->plan_id === $plan->id)
                                    <button class="plan-cta-btn active-plan" disabled>
                                        <i class="ph-bold ph-check-circle"></i>
                                        Current Active Plan
                                    </button>
                                @elseif(auth()->check() && $activeRentPlan && $activeRentPlan->remaining_contacts > 0 && $activeRentPlan->plan && (float) $plan->price > (float) $activeRentPlan->plan->price)
                                    <a href="{{ route('plans.checkout', ['plan' => $plan, 'billing' => 'monthly', 'direct' => 1]) }}" class="plan-cta-btn blue">
                                        <i class="ph-bold ph-lightning"></i>
                                        Upgrade Plan
                                    </a>
                                @elseif(auth()->check() && $activeRentPlan && $activeRentPlan->remaining_contacts > 0)
                                    <button class="plan-cta-btn disabled" disabled>
                                        Already Subscribed
                                    </button>
                                @elseif(auth()->check() && $pendingPlan && $pendingPlan->plan && $pendingPlan->plan->isRentPlan())
                                    <button class="plan-cta-btn pending-plan" disabled>
                                        <i class="ph-bold ph-clock"></i>
                                        Verification Pending
                                    </button>
                                @else
                                    @guest
                                        <a href="{{ route('login', ['redirect' => route('plans.checkout', ['plan' => $plan, 'billing' => 'monthly', 'direct' => 1])]) }}"
                                           onclick="event.preventDefault(); event.stopPropagation(); window.openAuthModal('login', '{{ route('plans.checkout', ['plan' => $plan, 'billing' => 'monthly', 'direct' => 1]) }}');"
                                           class="plan-cta-btn primary">
                                            <i class="ph-bold ph-lock-key-open"></i>
                                            Unlock Contacts Now · ₹{{ number_format($price, 0) }}
                                        </a>
                                    @else
                                        <a href="{{ route('plans.checkout', ['plan' => $plan, 'billing' => 'monthly', 'direct' => 1]) }}"
                                           class="plan-cta-btn primary">
                                            <i class="ph-bold ph-lock-key-open"></i>
                                            Unlock Contacts Now · ₹{{ number_format($price, 0) }}
                                        </a>
                                    @endguest
                                @endif

                                <a href="#comparison-section" class="plan-compare-link">
                                    Compare All Plan Details <i class="ph-bold ph-caret-down"></i>
                                </a>
                            </div>
                        </div>

                        {{-- Info Card --}}
                        <div class="plan-info-card">
                            <div class="plan-info-card-body">
                                <div class="card-badges">
                                    <span class="badge-green">DIRECT OWNER DEALS</span>
                                    <span class="badge-blue">100% Genuine</span>
                                </div>
                                <h3>Direct Property Rental</h3>
                                <p class="card-desc">Connect directly with genuine property owners and save lakhs in brokerage fees.</p>
                                <ul class="card-checks">
                                    <li><i class="ph-bold ph-check-circle"></i> Verified Direct Owner Contacts</li>
                                    <li><i class="ph-bold ph-check-circle"></i> Save 1 Month Brokerage Fee</li>
                                    <li><i class="ph-bold ph-check-circle"></i> Rental Agreement Checklist</li>
                                </ul>
                            </div>
                            <div class="plan-info-card-footer">
                                <i class="ph-bold ph-sparkle"></i>
                                Direct Owner Negotiation · ₹0 Middleman
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        {{-- Enterprise Panel (Rental) --}}
        @unless($hasEnterprise)
            <div class="plan-content-panel rental-panel" id="panel-rent_enterprise" data-category="rental">
                <div class="plan-split-layout">
                    <div class="plan-info-left">
                        <div class="plan-badge-row">
                            <span class="plan-type-badge" style="background: #ccfbf1; color: #134e4a; border-color: #99f6e4;">
                                <i class="ph-bold ph-buildings"></i>
                                ENTERPRISE · CORPORATE SOLUTIONS
                            </span>
                        </div>
                        <h1>
                            Scale Your Operations With
                            <span class="plan-name-highlight">Enterprise Plan</span>
                        </h1>
                        <p class="plan-subtitle">For agencies, relocation teams, and portfolio operations. Get unlimited access, dedicated account management, and seamless API integration.</p>

                        <div class="feature-grid">
                            <div class="feature-box">
                                <div class="feat-icon" style="background: #ccfbf1; color: #0d9488; border-color: #99f6e4;">
                                    <i class="ph-bold ph-infinity"></i>
                                </div>
                                <div class="feat-text">
                                    <h4>Unlimited Owner Unlocks</h4>
                                    <p>No cap on contact discoveries</p>
                                </div>
                            </div>
                            <div class="feature-box">
                                <div class="feat-icon" style="background: #ccfbf1; color: #0d9488; border-color: #99f6e4;">
                                    <i class="ph-bold ph-user-circle-gear"></i>
                                </div>
                                <div class="feat-text">
                                    <h4>Dedicated Account Manager</h4>
                                    <p>Personal support for your team</p>
                                </div>
                            </div>
                            <div class="feature-box">
                                <div class="feat-icon" style="background: #ccfbf1; color: #0d9488; border-color: #99f6e4;">
                                    <i class="ph-bold ph-receipt"></i>
                                </div>
                                <div class="feat-text">
                                    <h4>GST Invoicing & API</h4>
                                    <p>Seamless financial integration</p>
                                </div>
                            </div>
                            <div class="feature-box">
                                <div class="feat-icon" style="background: #ccfbf1; color: #0d9488; border-color: #99f6e4;">
                                    <i class="ph-bold ph-users-three"></i>
                                </div>
                                <div class="feat-text">
                                    <h4>Multi-User License</h4>
                                    <p>Team-wide access and controls</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="pricing-card">
                            <div class="pricing-card-header">
                                <span class="card-tab active" style="background: #0d9488; color: #fff; box-shadow: 0 2px 8px rgba(13,148,136,0.25);">ENTERPRISE</span>
                                <div class="card-icon" style="background: #ccfbf1; color: #0d9488;">
                                    <i class="ph-bold ph-buildings"></i>
                                </div>
                            </div>
                            <div class="pricing-card-body">
                                <div class="price-main">
                                    <span class="amount" style="font-size: 36px;">Custom</span>
                                </div>
                                <div class="price-subtitle" style="color: #0d9488;">
                                    <i class="ph-bold ph-users-three"></i>
                                    Multi-User License · Tailored Pricing
                                </div>

                                <div class="plan-stats">
                                    <div class="plan-stat-row">
                                        <span class="stat-label"><i class="ph-bold ph-phone"></i> Owner Contacts</span>
                                        <span class="stat-value highlight">Unlimited</span>
                                    </div>
                                    <div class="plan-stat-row">
                                        <span class="stat-label"><i class="ph-bold ph-calendar"></i> Access Duration</span>
                                        <span class="stat-value">Custom</span>
                                    </div>
                                    <div class="plan-stat-row">
                                        <span class="stat-label"><i class="ph-bold ph-headset"></i> Support Level</span>
                                        <span class="stat-value green">24/7 SLA Manager</span>
                                    </div>
                                </div>

                                <a href="mailto:support@unlockrentals.com?subject=Enterprise%20Plan%20Inquiry"
                                   class="plan-cta-btn" style="background: linear-gradient(135deg, #0d9488 0%, #14b8a6 100%); color: #fff; box-shadow: 0 4px 16px rgba(13,148,136,0.3);">
                                    <i class="ph-bold ph-envelope"></i>
                                    Contact Sales
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endunless


        {{-- ========== BUYER PLAN PANELS ========== --}}
        @foreach($buyPlans as $index => $plan)
            @php
                $nameLower = strtolower($plan->name);
                $isGold = str_contains($nameLower, 'gold') || str_contains($nameLower, 'pro') || str_contains($nameLower, 'popular');
                $isPlatinum = str_contains($nameLower, 'plat') || str_contains($nameLower, 'diamond') || str_contains($nameLower, 'vip');
                $price = (float) $plan->price;
                $perDay = $plan->duration_days > 0 ? round($price / $plan->duration_days, 1) : 0;

                $isExplicitBuyPlan = ($plan->purpose === 'buy' || $plan->purpose === 'sale');
                $buyerDisplayName = $isExplicitBuyPlan ? $plan->name : ($isGold ? 'Gold Buyer Pass' : ($isPlatinum ? 'Platinum Buyer Pass' : 'Silver Buyer Pass'));
                $buyerDisplayDesc = $isExplicitBuyPlan ? ($plan->description ?: 'Direct seller contact access for verified property purchase.') : (
                    $isGold ? 'Most popular annual pass for active home buyers and property investors.' : (
                        $isPlatinum ? 'Ultimate annual pass with maximum verified seller unlocks for serious property buyers.' : 'Essential direct seller contacts and priority access for property buyers.'
                    )
                );
            @endphp
            <div class="plan-content-panel buyer-panel {{ $index === 0 && $isYearly ? 'active' : '' }}"
                 id="panel-buy_{{ $plan->id }}" data-category="buyer">
                <div class="plan-split-layout">
                    {{-- LEFT SIDE --}}
                    <div class="plan-info-left">
                        <div class="plan-badge-row">
                            <span class="plan-type-badge buyer">
                                <i class="ph-bold ph-key"></i>
                                ZERO BROKERAGE BUYER PASS · DIRECT SELLER DEALS
                            </span>
                        </div>

                        <h1>
                            Purchase Your Dream Property With
                            <span class="plan-name-highlight">{{ $buyerDisplayName }}</span>
                        </h1>
                        <p class="plan-subtitle">{{ $buyerDisplayDesc }}</p>

                        <div class="feature-grid">
                            <div class="feature-box">
                                <div class="feat-icon">
                                    <i class="ph-bold ph-user-circle-check"></i>
                                </div>
                                <div class="feat-text">
                                    <h4>{{ $plan->contact_limit }} Verified Direct Sellers</h4>
                                    <p>Direct owners & builder representatives</p>
                                </div>
                            </div>
                            <div class="feature-box">
                                <div class="feat-icon green">
                                    <i class="ph-bold ph-currency-inr"></i>
                                </div>
                                <div class="feat-text">
                                    <h4>Save ₹1 Lakh to ₹5 Lakhs</h4>
                                    <p>Zero broker commission on buy transactions</p>
                                </div>
                            </div>
                            <div class="feature-box">
                                <div class="feat-icon">
                                    <i class="ph-bold ph-calendar-check"></i>
                                </div>
                                <div class="feat-text">
                                    <h4>{{ $plan->duration_days }} Days Buyer Validity</h4>
                                    <p>Extended window to evaluate deals</p>
                                </div>
                            </div>
                            <div class="feature-box">
                                <div class="feat-icon green">
                                    <i class="ph-bold ph-file-text"></i>
                                </div>
                                <div class="feat-text">
                                    <h4>Title & Visit Assistance</h4>
                                    <p>Direct owner negotiation pass</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT SIDE - Pricing Card --}}
                    <div>
                        <div class="pricing-card {{ $isGold ? 'popular-card' : '' }}">
                            <div class="pricing-card-header">
                                <span class="card-tab active" style="background: #d97706; color: #fff; box-shadow: 0 2px 8px rgba(217,119,6,0.25);">BUYER PASS</span>
                                <span class="card-tab" style="cursor: default; opacity: 0.4;">DIRECT PASS</span>
                                <div class="card-icon" style="background: #fef3c7; color: #d97706;">
                                    <i class="ph-bold ph-buildings"></i>
                                </div>
                            </div>

                            <div class="pricing-card-body">
                                <div class="price-main">
                                    <span class="currency">₹</span>
                                    <span class="amount">{{ number_format($price, 0) }}</span>
                                    <span class="period">/ annual pass</span>
                                </div>
                                <div class="price-subtitle">
                                    <i class="ph-bold ph-seal-check"></i>
                                    Only ₹{{ number_format($perDay, 1) }}/day · {{ $plan->duration_days }} Full Days Validity
                                </div>

                                <div class="plan-stats">
                                    <div class="plan-stat-row">
                                        <span class="stat-label"><i class="ph-bold ph-phone"></i> Direct Seller Contacts</span>
                                        <span class="stat-value highlight">{{ $plan->contact_limit }} Direct Unlocks</span>
                                    </div>
                                    <div class="plan-stat-row">
                                        <span class="stat-label"><i class="ph-bold ph-calendar"></i> Access Duration</span>
                                        <span class="stat-value">{{ $plan->duration_days }} Full Days</span>
                                    </div>
                                    <div class="plan-stat-row">
                                        <span class="stat-label"><i class="ph-bold ph-hand-coins"></i> Brokerage Fee</span>
                                        <span class="stat-value green">₹0 (Zero Commission)</span>
                                    </div>
                                </div>

                                {{-- CTA --}}
                                @if(auth()->check() && $activeBuyPlan && $activeBuyPlan->remaining_contacts > 0 && $activeBuyPlan->plan_id === $plan->id)
                                    <button class="plan-cta-btn active-plan" disabled>
                                        <i class="ph-bold ph-check-circle"></i>
                                        Current Active Plan
                                    </button>
                                @elseif(auth()->check() && $activeBuyPlan && $activeBuyPlan->remaining_contacts > 0 && $activeBuyPlan->plan && (float) $plan->price > (float) $activeBuyPlan->plan->price)
                                    <a href="{{ route('plans.checkout', ['plan' => $plan, 'billing' => 'yearly', 'direct' => 1]) }}" class="plan-cta-btn blue">
                                        <i class="ph-bold ph-lightning"></i>
                                        Upgrade Plan
                                    </a>
                                @elseif(auth()->check() && $activeBuyPlan && $activeBuyPlan->remaining_contacts > 0)
                                    <button class="plan-cta-btn disabled" disabled>
                                        Already Subscribed
                                    </button>
                                @elseif(auth()->check() && $pendingPlan && $pendingPlan->plan && $pendingPlan->plan->isBuyPlan())
                                    <button class="plan-cta-btn pending-plan" disabled>
                                        <i class="ph-bold ph-clock"></i>
                                        Verification Pending
                                    </button>
                                @else
                                    @guest
                                        <a href="{{ route('login', ['redirect' => route('plans.checkout', ['plan' => $plan, 'billing' => 'yearly', 'direct' => 1])]) }}"
                                           onclick="event.preventDefault(); event.stopPropagation(); window.openAuthModal('login', '{{ route('plans.checkout', ['plan' => $plan, 'billing' => 'yearly', 'direct' => 1]) }}');"
                                           class="plan-cta-btn primary">
                                            <i class="ph-bold ph-lock-key-open"></i>
                                            Unlock Contacts Now · ₹{{ number_format($price, 0) }}
                                        </a>
                                    @else
                                        <a href="{{ route('plans.checkout', ['plan' => $plan, 'billing' => 'yearly', 'direct' => 1]) }}"
                                           class="plan-cta-btn primary">
                                            <i class="ph-bold ph-lock-key-open"></i>
                                            Unlock Contacts Now · ₹{{ number_format($price, 0) }}
                                        </a>
                                    @endguest
                                @endif

                                <a href="#comparison-section" class="plan-compare-link">
                                    Compare All Plan Details <i class="ph-bold ph-caret-down"></i>
                                </a>
                            </div>
                        </div>

                        {{-- Info Card --}}
                        <div class="plan-info-card">
                            <div class="plan-info-card-body">
                                <div class="card-badges">
                                    <span class="badge-green">DIRECT SELLER DEALS</span>
                                    <span class="badge-blue">100% Genuine</span>
                                </div>
                                <h3>Direct Property Purchase</h3>
                                <p class="card-desc">Connect directly with genuine property sellers and save lakhs in brokerage fees.</p>
                                <ul class="card-checks">
                                    <li><i class="ph-bold ph-check-circle"></i> Verified Direct Seller Contacts</li>
                                    <li><i class="ph-bold ph-check-circle"></i> Save 1% to 2% Brokerage Fee</li>
                                    <li><i class="ph-bold ph-check-circle"></i> Title Document Checklist</li>
                                </ul>
                            </div>
                            <div class="plan-info-card-footer">
                                <i class="ph-bold ph-sparkle"></i>
                                Direct Seller Negotiation · ₹0 Middleman
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        {{-- Enterprise Panel (Buyer) --}}
        @unless($hasEnterprise)
            <div class="plan-content-panel buyer-panel" id="panel-buy_enterprise" data-category="buyer">
                <div class="plan-split-layout">
                    <div class="plan-info-left">
                        <div class="plan-badge-row">
                            <span class="plan-type-badge" style="background: #ccfbf1; color: #134e4a; border-color: #99f6e4;">
                                <i class="ph-bold ph-buildings"></i>
                                INVESTOR DESK · INSTITUTIONAL SOLUTIONS
                            </span>
                        </div>
                        <h1>
                            Scale Your Investments With
                            <span class="plan-name-highlight">Institutional Buyer</span>
                        </h1>
                        <p class="plan-subtitle">For property funds, builders, and large commercial investors. Get unlimited seller access, dedicated investment concierge, and API integration.</p>

                        <div class="feature-grid">
                            <div class="feature-box">
                                <div class="feat-icon" style="background: #ccfbf1; color: #0d9488; border-color: #99f6e4;">
                                    <i class="ph-bold ph-infinity"></i>
                                </div>
                                <div class="feat-text">
                                    <h4>Unlimited Seller Unlocks</h4>
                                    <p>No cap on contact discoveries</p>
                                </div>
                            </div>
                            <div class="feature-box">
                                <div class="feat-icon" style="background: #ccfbf1; color: #0d9488; border-color: #99f6e4;">
                                    <i class="ph-bold ph-user-circle-gear"></i>
                                </div>
                                <div class="feat-text">
                                    <h4>Dedicated Investment Concierge</h4>
                                    <p>Personal support for your portfolio</p>
                                </div>
                            </div>
                            <div class="feature-box">
                                <div class="feat-icon" style="background: #ccfbf1; color: #0d9488; border-color: #99f6e4;">
                                    <i class="ph-bold ph-code"></i>
                                </div>
                                <div class="feat-text">
                                    <h4>Direct API & Bulk Export</h4>
                                    <p>Programmatic access to data</p>
                                </div>
                            </div>
                            <div class="feature-box">
                                <div class="feat-icon" style="background: #ccfbf1; color: #0d9488; border-color: #99f6e4;">
                                    <i class="ph-bold ph-chart-line-up"></i>
                                </div>
                                <div class="feat-text">
                                    <h4>Portfolio Access</h4>
                                    <p>Multi-property deal pipeline</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="pricing-card">
                            <div class="pricing-card-header">
                                <span class="card-tab active" style="background: #0d9488; color: #fff; box-shadow: 0 2px 8px rgba(13,148,136,0.25);">INSTITUTIONAL</span>
                                <div class="card-icon" style="background: #ccfbf1; color: #0d9488;">
                                    <i class="ph-bold ph-buildings"></i>
                                </div>
                            </div>
                            <div class="pricing-card-body">
                                <div class="price-main">
                                    <span class="amount" style="font-size: 36px;">Custom</span>
                                </div>
                                <div class="price-subtitle" style="color: #0d9488;">
                                    <i class="ph-bold ph-users-three"></i>
                                    Portfolio Access · Tailored Pricing
                                </div>

                                <div class="plan-stats">
                                    <div class="plan-stat-row">
                                        <span class="stat-label"><i class="ph-bold ph-phone"></i> Seller Contacts</span>
                                        <span class="stat-value highlight">Unlimited</span>
                                    </div>
                                    <div class="plan-stat-row">
                                        <span class="stat-label"><i class="ph-bold ph-calendar"></i> Access Duration</span>
                                        <span class="stat-value">Custom</span>
                                    </div>
                                    <div class="plan-stat-row">
                                        <span class="stat-label"><i class="ph-bold ph-headset"></i> Support Level</span>
                                        <span class="stat-value green">24/7 SLA Manager</span>
                                    </div>
                                </div>

                                <a href="mailto:support@unlockrentals.com?subject=Institutional%20Buyer%20Inquiry"
                                   class="plan-cta-btn" style="background: linear-gradient(135deg, #0d9488 0%, #14b8a6 100%); color: #fff; box-shadow: 0 4px 16px rgba(13,148,136,0.3);">
                                    <i class="ph-bold ph-envelope"></i>
                                    Contact Sales
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endunless

    </div>{{-- End plan-detail-section --}}


    {{-- ============================================
         FEATURE COMPARISON TABLE
    ============================================ --}}
    <div class="comparison-section" id="comparison-section">
        <div class="comparison-card">
            <div class="comparison-card-header">
                <div class="comp-title">
                    <span>Detailed Comparison</span>
                    <h2>What's included in every plan</h2>
                </div>
                <div class="comp-secure">
                    <i class="ph-bold ph-lock-key"></i>
                    256-Bit SSL Encrypted · Instant Activation
                </div>
            </div>

            <div style="overflow-x: auto;">
                <table class="comparison-table">
                    <thead>
                        <tr>
                            <th>Features & Benefits</th>
                            <th>Basic</th>
                            <th>Gold (Popular)</th>
                            <th>Platinum</th>
                            <th>Enterprise</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Direct Landlord Contact Unlocks</td>
                            <td>Starter Pack</td>
                            <td class="highlight-cell">Expanded Pack</td>
                            <td>High Volume</td>
                            <td>Unlimited</td>
                        </tr>
                        <tr>
                            <td>Direct WhatsApp & Call Connect</td>
                            <td class="check-cell">✓ Included</td>
                            <td class="check-cell">✓ Included</td>
                            <td class="check-cell">✓ Included</td>
                            <td class="check-cell">✓ Included</td>
                        </tr>
                        <tr>
                            <td>Zero Brokerage Guarantee</td>
                            <td class="check-cell" style="font-weight: 800;">100% Free</td>
                            <td class="check-cell" style="font-weight: 800;">100% Free</td>
                            <td class="check-cell" style="font-weight: 800;">100% Free</td>
                            <td class="check-cell" style="font-weight: 800;">100% Free</td>
                        </tr>
                        <tr>
                            <td>Visit Booking Access</td>
                            <td>Standard</td>
                            <td>Priority</td>
                            <td>Instant Slot Lock</td>
                            <td>Concierge</td>
                        </tr>
                        <tr>
                            <td>Customer Support</td>
                            <td>Email Support</td>
                            <td class="highlight-cell">Priority Chat & Phone</td>
                            <td>Dedicated Support</td>
                            <td>24/7 SLA Manager</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    {{-- ============================================
         FAQ SECTION
    ============================================ --}}
    <div class="faq-section">
        <h2>Frequently Asked Questions</h2>

        <div class="faq-item open">
            <div class="faq-question" onclick="toggleFaq(this)">
                <h3>How does the direct owner contact unlock work?</h3>
                <div class="faq-toggle"><i class="ph-bold ph-plus"></i></div>
            </div>
            <div class="faq-answer">
                <p>Once you choose a plan and complete payment, you can click "Unlock Owner Contact" on any property. You will instantly view the landlord's verified mobile number and can directly call or message them on WhatsApp with zero broker commission.</p>
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question" onclick="toggleFaq(this)">
                <h3>What is the difference between Rental Pass and Buyer Pass?</h3>
                <div class="faq-toggle"><i class="ph-bold ph-plus"></i></div>
            </div>
            <div class="faq-answer">
                <p>The <strong>Rental Pass</strong> is designed for quick 30-day apartment and room hunting. The <strong>Buyer Pass</strong> provides 365-day extended validity with 20% discount, designed for home buyers and real estate investors exploring properties over several months.</p>
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question" onclick="toggleFaq(this)">
                <h3>Which payment methods are supported?</h3>
                <div class="faq-toggle"><i class="ph-bold ph-plus"></i></div>
            </div>
            <div class="faq-answer">
                <p>We support all popular Indian payment modes including UPI (Google Pay, PhonePe, Paytm), Credit/Debit cards (Visa, Mastercard, RuPay), NetBanking, and Wallets through secure encrypted payment gateways.</p>
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question" onclick="toggleFaq(this)">
                <h3>Can I upgrade my plan mid-subscription?</h3>
                <div class="faq-toggle"><i class="ph-bold ph-plus"></i></div>
            </div>
            <div class="faq-answer">
                <p>Yes! You can upgrade to a higher plan at any time. Your remaining contacts from the current plan will carry forward, and you'll get the additional benefits of the upgraded plan immediately upon payment confirmation.</p>
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question" onclick="toggleFaq(this)">
                <h3>Is my payment information secure?</h3>
                <div class="faq-toggle"><i class="ph-bold ph-plus"></i></div>
            </div>
            <div class="faq-answer">
                <p>Absolutely. All payments are processed through 256-bit SSL encrypted payment gateways. We never store your card details on our servers. Your transaction is fully secure and PCI DSS compliant.</p>
            </div>
        </div>
    </div>

</div>

<x-subscription.payment-failed-modal :payment-failed-reason="$paymentFailedReason" />
@endsection

@push('scripts')
<script>
(() => {
    // ---- Category Toggle (Rental / Buyer) ----
    const categoryBtns = document.querySelectorAll('[data-billing-choice]');
    const rentalTabs = document.getElementById('rental-tabs');
    const buyerTabs = document.getElementById('buyer-tabs');

    function setCategory(period, updateUrl = false) {
        categoryBtns.forEach(btn => {
            btn.classList.toggle('active', btn.dataset.billingChoice === period);
        });

        if (period === 'yearly') {
            rentalTabs.style.display = 'none';
            buyerTabs.style.display = 'flex';
            // Hide all rental panels, show first buyer panel
            document.querySelectorAll('.rental-panel').forEach(p => p.classList.remove('active'));
            document.querySelectorAll('.buyer-panel').forEach(p => p.classList.remove('active'));
            const firstBuyer = document.querySelector('.buyer-panel');
            if (firstBuyer) firstBuyer.classList.add('active');
            // Activate first buyer tab
            document.querySelectorAll('#buyer-tabs .plan-tab').forEach((t, i) => {
                t.classList.toggle('active', i === 0);
            });
        } else {
            rentalTabs.style.display = 'flex';
            buyerTabs.style.display = 'none';
            // Hide all buyer panels, show first rental panel
            document.querySelectorAll('.buyer-panel').forEach(p => p.classList.remove('active'));
            document.querySelectorAll('.rental-panel').forEach(p => p.classList.remove('active'));
            const firstRental = document.querySelector('.rental-panel');
            if (firstRental) firstRental.classList.add('active');
            // Activate first rental tab
            document.querySelectorAll('#rental-tabs .plan-tab').forEach((t, i) => {
                t.classList.toggle('active', i === 0);
            });
        }

        if (updateUrl && window.history.replaceState) {
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('billing', period);
            currentUrl.searchParams.set('purpose', period === 'yearly' ? 'buy' : 'rent');
            window.history.replaceState({}, '', currentUrl.toString());
        }
    }

    categoryBtns.forEach(btn => {
        btn.addEventListener('click', () => setCategory(btn.dataset.billingChoice, true));
    });

    // ---- Plan Tab Switching ----
    document.querySelectorAll('.plan-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            const planId = tab.dataset.planTab;
            const category = tab.dataset.category;
            if (!planId) return;

            // Deactivate sibling tabs
            const parentContainer = tab.closest('#rental-tabs, #buyer-tabs');
            if (parentContainer) {
                parentContainer.querySelectorAll('.plan-tab').forEach(t => t.classList.remove('active'));
            }
            tab.classList.add('active');

            // Show matching panel
            const panels = category === 'rental'
                ? document.querySelectorAll('.rental-panel')
                : document.querySelectorAll('.buyer-panel');
            panels.forEach(p => p.classList.remove('active'));
            const target = document.getElementById('panel-' + planId);
            if (target) target.classList.add('active');
        });
    });

    // ---- Init from URL ----
    const urlParams = new URLSearchParams(window.location.search);
    const billingParam = urlParams.get('billing');
    const purposeParam = urlParams.get('purpose');
    const hash = window.location.hash.toLowerCase();

    let targetBilling = '{{ $initialBilling }}';
    if (billingParam === 'yearly' || billingParam === 'monthly') {
        targetBilling = billingParam;
    } else if (purposeParam === 'buy' || purposeParam === 'sale' || hash.includes('buyer') || hash.includes('yearly')) {
        targetBilling = 'yearly';
    } else if (purposeParam === 'rent' || hash.includes('rental') || hash.includes('monthly')) {
        targetBilling = 'monthly';
    }
    setCategory(targetBilling, false);

    // Smooth scroll if param requested
    if (billingParam || purposeParam || hash.includes('buyer') || hash.includes('rental') || hash.includes('billing')) {
        setTimeout(() => {
            const scrollTarget = document.getElementById('category-toggle') || document.getElementById('plans-tab-bar');
            if (scrollTarget) {
                scrollTarget.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }, 150);
    }
})();

// FAQ Toggle
function toggleFaq(el) {
    const item = el.closest('.faq-item');
    if (item) {
        item.classList.toggle('open');
    }
}
</script>
@endpush
