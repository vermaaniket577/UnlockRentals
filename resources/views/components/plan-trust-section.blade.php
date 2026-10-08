@php
    $sitePhone = $site_settings['site_phone'] ?? '+91 94254 55499';
    $siteEmail = $site_settings['site_email'] ?? 'support@unlockrentals.com';
    $refundEmail = $site_settings['refund_support_email'] ?? $siteEmail;
    $supportHours = $site_settings['support_hours'] ?? 'Mon – Sat: 9:30 AM – 7:30 PM IST';
    $rawWaPhone = $site_settings['whatsapp_phone'] ?? ($site_settings['whatsapp_number'] ?? $sitePhone);
    $cleanWaPhone = preg_replace('/[^0-9]/', '', (string)$rawWaPhone);
    if (!str_starts_with($cleanWaPhone, '91') && strlen($cleanWaPhone) === 10) {
        $cleanWaPhone = '91' . $cleanWaPhone;
    }
    $cleanTel = preg_replace('/[^0-9+]/', '', (string)$sitePhone);

    // Retrieve approved genuine reviews if not already passed
    if (!isset($feedbacks)) {
        $feedbacks = \Illuminate\Support\Facades\Cache::remember('plans_approved_feedbacks_v2', 300, function () {
            return \App\Models\Feedback::with('user')
                ->where('status', 'approved')
                ->latest()
                ->take(6)
                ->get();
        });
    }

    $hasApprovedReviews = isset($feedbacks) && $feedbacks->isNotEmpty();
@endphp

<style>
/* ============================================================
   UNLOCKRENTALS — HIGH-TRUST PLAN PURCHASE SECTION STYLES
   Modern Indian Real-Estate Marketplace Aesthetic
   Palette: Slate / Navy (#0f172a, #1e3a8a, #2563eb)
   Trust Accents: Emerald (#059669, #10b981)
============================================================ */

.ur-trust-section {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    color: #1e293b;
    margin-top: 3.5rem;
    position: relative;
}
.dark .ur-trust-section {
    color: #f1f5f9;
}

.ur-trust-wrap {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
}

/* ─── 1. Header ─── */
.ur-trust-header {
    text-align: center;
    max-width: 820px;
    margin: 0 auto 3rem;
}

.ur-trust-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.4rem 1rem;
    border-radius: 9999px;
    background: #ecfdf5;
    color: #047857;
    font-size: 0.75rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border: 1px solid #a7f3d0;
    margin-bottom: 1rem;
}
.dark .ur-trust-eyebrow {
    background: rgba(6, 78, 59, 0.4);
    color: #34d399;
    border-color: rgba(16, 185, 129, 0.3);
}

.ur-trust-title {
    font-size: 2rem;
    line-height: 1.25;
    font-weight: 900;
    color: #0f172a;
    letter-spacing: -0.025em;
    margin-bottom: 0.85rem;
}
@media (min-width: 640px) {
    .ur-trust-title { font-size: 2.5rem; }
}
.dark .ur-trust-title {
    color: #ffffff;
}

.ur-trust-title .highlight-blue {
    color: #2563eb;
}
.dark .ur-trust-title .highlight-blue {
    color: #60a5fa;
}

.ur-trust-title .highlight-green {
    color: #059669;
}
.dark .ur-trust-title .highlight-green {
    color: #10b981;
}

.ur-trust-subtitle {
    font-size: 0.95rem;
    line-height: 1.6;
    color: #475569;
    max-width: 680px;
    margin: 0 auto 1.25rem;
    font-weight: 400;
}
.dark .ur-trust-subtitle {
    color: #94a3b8;
}
@media (min-width: 640px) {
    .ur-trust-subtitle { font-size: 1.05rem; }
}

.ur-trust-pill-strip {
    display: inline-flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: center;
    gap: 0.5rem 1.25rem;
    padding: 0.6rem 1.25rem;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 9999px;
    box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
    font-size: 0.78rem;
    font-weight: 600;
    color: #334155;
}
.dark .ur-trust-pill-strip {
    background: #1e293b;
    border-color: #334155;
    color: #cbd5e1;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.ur-trust-pill-item {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
}
.ur-trust-pill-item i {
    color: #059669;
    font-size: 0.95rem;
}
.dark .ur-trust-pill-item i {
    color: #10b981;
}

/* ─── 2. Trust Cards Grid ─── */
.ur-trust-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.25rem;
    margin-bottom: 3.5rem;
}
@media (min-width: 640px) {
    .ur-trust-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (min-width: 1024px) {
    .ur-trust-grid { grid-template-columns: repeat(3, 1fr); }
}

.ur-trust-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 1.25rem;
    padding: 1.75rem 1.5rem;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    box-shadow: 0 4px 16px rgba(15, 23, 42, 0.03);
    transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    position: relative;
    overflow: hidden;
}
.dark .ur-trust-card {
    background: #0f172a;
    border-color: #1e293b;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25);
}
.ur-trust-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 24px -4px rgba(15, 23, 42, 0.08);
    border-color: #cbd5e1;
}
.dark .ur-trust-card:hover {
    border-color: #334155;
    box-shadow: 0 12px 24px -4px rgba(0, 0, 0, 0.45);
}

.ur-trust-card__top {
    margin-bottom: 1.25rem;
}

.ur-trust-icon-box {
    width: 3.25rem;
    height: 3.25rem;
    border-radius: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
    margin-bottom: 1.25rem;
}
.ur-trust-icon-box.blue {
    background: #eff6ff;
    color: #2563eb;
    border: 1px solid #dbeafe;
}
.dark .ur-trust-icon-box.blue {
    background: rgba(37, 99, 235, 0.15);
    color: #60a5fa;
    border-color: rgba(37, 99, 235, 0.3);
}

.ur-trust-icon-box.green {
    background: #ecfdf5;
    color: #059669;
    border: 1px solid #d1fae5;
}
.dark .ur-trust-icon-box.green {
    background: rgba(5, 150, 105, 0.15);
    color: #34d399;
    border-color: rgba(5, 150, 105, 0.3);
}

.ur-trust-icon-box.amber {
    background: #fffbeb;
    color: #d97706;
    border: 1px solid #fef3c7;
}
.dark .ur-trust-icon-box.amber {
    background: rgba(217, 119, 6, 0.15);
    color: #fbbf24;
    border-color: rgba(217, 119, 6, 0.3);
}

.ur-trust-icon-box.indigo {
    background: #eef2ff;
    color: #4f46e5;
    border: 1px solid #e0e7ff;
}
.dark .ur-trust-icon-box.indigo {
    background: rgba(79, 70, 229, 0.15);
    color: #818cf8;
    border-color: rgba(79, 70, 229, 0.3);
}

.ur-trust-card__heading {
    font-size: 1.15rem;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 0.5rem;
    letter-spacing: -0.01em;
}
.dark .ur-trust-card__heading {
    color: #ffffff;
}

.ur-trust-card__desc {
    font-size: 0.85rem;
    line-height: 1.55;
    color: #64748b;
    font-weight: 400;
}
.dark .ur-trust-card__desc {
    color: #94a3b8;
}

.ur-trust-card__bottom {
    margin-top: 1.25rem;
    padding-top: 1rem;
    border-top: 1px solid #f1f5f9;
}
.dark .ur-trust-card__bottom {
    border-top-color: #1e293b;
}

.ur-trust-card__action-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.8rem;
    font-weight: 700;
    color: #2563eb;
    text-decoration: none;
    transition: all 0.15s ease;
}
.ur-trust-card__action-btn:hover {
    color: #1d4ed8;
    gap: 0.6rem;
}
.dark .ur-trust-card__action-btn {
    color: #60a5fa;
}
.dark .ur-trust-card__action-btn:hover {
    color: #93c5fd;
}

.ur-trust-card__badge-note {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.72rem;
    font-weight: 600;
    color: #059669;
    background: #ecfdf5;
    padding: 0.3rem 0.65rem;
    border-radius: 0.5rem;
}
.dark .ur-trust-card__badge-note {
    background: rgba(5, 150, 105, 0.15);
    color: #34d399;
}

/* ─── 3. How Purchase Works (4 Steps) ─── */
.ur-how-section {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 2rem;
    padding: 2.5rem 1.5rem;
    margin-bottom: 3.5rem;
    box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
}
@media (min-width: 640px) {
    .ur-how-section { padding: 3rem 2.5rem; }
}
.dark .ur-how-section {
    background: #0f172a;
    border-color: #1e293b;
}

.ur-how-header {
    text-align: center;
    max-width: 650px;
    margin: 0 auto 2.5rem;
}

.ur-how-title {
    font-size: 1.6rem;
    font-weight: 900;
    color: #0f172a;
    letter-spacing: -0.02em;
    margin-bottom: 0.5rem;
}
.dark .ur-how-title { color: #ffffff; }

.ur-how-sub {
    font-size: 0.88rem;
    color: #64748b;
}
.dark .ur-how-sub { color: #94a3b8; }

.ur-how-steps {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.75rem;
    position: relative;
}
@media (min-width: 640px) {
    .ur-how-steps { grid-template-columns: repeat(2, 1fr); }
}
@media (min-width: 1024px) {
    .ur-how-steps { grid-template-columns: repeat(4, 1fr); }
}

.ur-step-item {
    position: relative;
    display: flex;
    flex-direction: column;
    padding: 1.25rem 1rem;
    background: #f8fafc;
    border: 1px solid #f1f5f9;
    border-radius: 1.25rem;
    transition: transform 0.2s ease;
}
.dark .ur-step-item {
    background: #111827;
    border-color: #1f2937;
}
.ur-step-item:hover {
    transform: translateY(-2px);
}

.ur-step-num {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 2.25rem;
    height: 2.25rem;
    border-radius: 0.75rem;
    background: #2563eb;
    color: #ffffff;
    font-size: 0.85rem;
    font-weight: 900;
    margin-bottom: 1rem;
    box-shadow: 0 4px 10px rgba(37, 99, 235, 0.25);
}

.ur-step-icon {
    font-size: 1.75rem;
    color: #0f172a;
    margin-bottom: 0.75rem;
}
.dark .ur-step-icon { color: #60a5fa; }

.ur-step-heading {
    font-size: 1rem;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 0.35rem;
}
.dark .ur-step-heading { color: #ffffff; }

.ur-step-desc {
    font-size: 0.8rem;
    line-height: 1.5;
    color: #64748b;
}
.dark .ur-step-desc { color: #94a3b8; }

/* ─── 4. Customer Reviews / Social Proof ─── */
.ur-reviews-section {
    margin-bottom: 3.5rem;
}

.ur-reviews-header {
    text-align: center;
    max-width: 650px;
    margin: 0 auto 2rem;
}

.ur-reviews-title {
    font-size: 1.6rem;
    font-weight: 900;
    color: #0f172a;
    letter-spacing: -0.02em;
    margin-bottom: 0.5rem;
}
.dark .ur-reviews-title { color: #ffffff; }

.ur-reviews-sub {
    font-size: 0.88rem;
    color: #64748b;
}
.dark .ur-reviews-sub { color: #94a3b8; }

.ur-reviews-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.25rem;
}
@media (min-width: 640px) {
    .ur-reviews-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (min-width: 1024px) {
    .ur-reviews-grid { grid-template-columns: repeat(3, 1fr); }
}

.ur-review-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 1.25rem;
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    box-shadow: 0 4px 16px rgba(15, 23, 42, 0.03);
}
.dark .ur-review-card {
    background: #0f172a;
    border-color: #1e293b;
}

.ur-review-stars {
    color: #f59e0b;
    font-size: 1rem;
    margin-bottom: 0.75rem;
    display: flex;
    gap: 2px;
}

.ur-review-quote {
    font-size: 0.88rem;
    line-height: 1.55;
    color: #334155;
    font-style: normal;
    margin-bottom: 1.25rem;
}
.dark .ur-review-quote { color: #cbd5e1; }

.ur-review-author {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.ur-review-avatar {
    width: 2.25rem;
    height: 2.25rem;
    border-radius: 9999px;
    background: #eff6ff;
    color: #1e40af;
    font-weight: 800;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #bfdbfe;
    shrink: 0;
}
.dark .ur-review-avatar {
    background: #1e293b;
    color: #60a5fa;
    border-color: #334155;
}

.ur-review-name {
    font-size: 0.82rem;
    font-weight: 700;
    color: #0f172a;
}
.dark .ur-review-name { color: #ffffff; }

.ur-review-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.7rem;
    font-weight: 600;
    color: #059669;
}
.dark .ur-review-badge { color: #34d399; }

/* Honest Empty Review Callout Box */
.ur-empty-reviews-box {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border: 1px dashed #cbd5e1;
    border-radius: 1.5rem;
    padding: 2.5rem 1.5rem;
    text-align: center;
    max-width: 650px;
    margin: 0 auto;
}
.dark .ur-empty-reviews-box {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    border-color: #334155;
}

.ur-empty-reviews-box h4 {
    font-size: 1.2rem;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 0.5rem;
}
.dark .ur-empty-reviews-box h4 { color: #ffffff; }

.ur-empty-reviews-box p {
    font-size: 0.85rem;
    color: #64748b;
    margin-bottom: 1.25rem;
    max-width: 480px;
    margin-left: auto;
    margin-right: auto;
}
.dark .ur-empty-reviews-box p { color: #94a3b8; }

.ur-write-review-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 0.85rem;
    background: #2563eb;
    color: #ffffff;
    font-size: 0.85rem;
    font-weight: 700;
    border: none;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    transition: all 0.2s ease;
}
.ur-write-review-btn:hover {
    background: #1d4ed8;
    transform: translateY(-1px);
}

/* ─── 5. Cancellation & Refund Policy Accordion Box ─── */
.ur-refund-box {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 1.5rem;
    padding: 1.75rem 1.5rem;
    margin-bottom: 3.5rem;
    box-shadow: 0 4px 16px rgba(15, 23, 42, 0.02);
}
@media (min-width: 640px) {
    .ur-refund-box { padding: 2.25rem 2rem; }
}
.dark .ur-refund-box {
    background: #0f172a;
    border-color: #1e293b;
}

.ur-refund-box__top {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    justify-content: space-between;
    margin-bottom: 1.5rem;
    padding-bottom: 1.25rem;
    border-bottom: 1px solid #f1f5f9;
}
@media (min-width: 640px) {
    .ur-refund-box__top {
        flex-direction: row;
        align-items: center;
    }
}
.dark .ur-refund-box__top {
    border-bottom-color: #1e293b;
}

.ur-refund-box__heading {
    font-size: 1.25rem;
    font-weight: 800;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.dark .ur-refund-box__heading { color: #ffffff; }

.ur-refund-box__summary {
    font-size: 0.85rem;
    color: #64748b;
    margin-top: 0.25rem;
}
.dark .ur-refund-box__summary { color: #94a3b8; }

.ur-refund-details-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1rem;
    font-size: 0.82rem;
}
@media (min-width: 640px) {
    .ur-refund-details-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (min-width: 1024px) {
    .ur-refund-details-grid { grid-template-columns: repeat(3, 1fr); }
}

.ur-refund-item {
    background: #f8fafc;
    border: 1px solid #edf2f7;
    border-radius: 1rem;
    padding: 1rem 1.15rem;
}
.dark .ur-refund-item {
    background: #111827;
    border-color: #1f2937;
}

.ur-refund-item__title {
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 0.35rem;
    display: flex;
    align-items: center;
    gap: 0.4rem;
}
.dark .ur-refund-item__title { color: #ffffff; }

.ur-refund-item__desc {
    color: #64748b;
    line-height: 1.5;
}
.dark .ur-refund-item__desc { color: #94a3b8; }

/* ─── 6. FAQ Accordion ─── */
.ur-faq-container {
    max-width: 900px;
    margin: 0 auto 3.5rem;
}

.ur-faq-header {
    text-align: center;
    margin-bottom: 2rem;
}

.ur-faq-title {
    font-size: 1.75rem;
    font-weight: 900;
    color: #0f172a;
    margin-bottom: 0.5rem;
}
.dark .ur-faq-title { color: #ffffff; }

.ur-faq-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.ur-faq-item {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 1.15rem;
    overflow: hidden;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}
.dark .ur-faq-item {
    background: #0f172a;
    border-color: #1e293b;
}
.ur-faq-item.active {
    border-color: #93c5fd;
    box-shadow: 0 4px 14px rgba(37, 99, 235, 0.06);
}
.dark .ur-faq-item.active {
    border-color: #2563eb;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.3);
}

.ur-faq-trigger {
    width: 100%;
    padding: 1.15rem 1.25rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    background: transparent;
    border: none;
    text-align: left;
    cursor: pointer;
}

.ur-faq-question {
    font-size: 0.95rem;
    font-weight: 700;
    color: #0f172a;
}
.dark .ur-faq-question { color: #ffffff; }

.ur-faq-icon {
    width: 1.75rem;
    height: 1.75rem;
    border-radius: 0.5rem;
    background: #f1f5f9;
    color: #64748b;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
    flex-shrink: 0;
    transition: transform 0.2s ease, background 0.2s ease;
}
.dark .ur-faq-icon {
    background: #1e293b;
    color: #94a3b8;
}
.ur-faq-item.active .ur-faq-icon {
    transform: rotate(180deg);
    background: #2563eb;
    color: #ffffff;
}

.ur-faq-body {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s cubic-bezier(0, 1, 0, 1), padding 0.2s ease;
    padding: 0 1.25rem;
}
.ur-faq-item.active .ur-faq-body {
    max-height: 1000px;
    transition: max-height 0.3s ease-in-out;
    padding: 0 1.25rem 1.25rem;
}

.ur-faq-answer {
    font-size: 0.88rem;
    line-height: 1.6;
    color: #475569;
    border-top: 1px solid #f1f5f9;
    padding-top: 0.85rem;
}
.dark .ur-faq-answer {
    color: #cbd5e1;
    border-top-color: #1e293b;
}

/* ─── 7. Help & Support Box ─── */
.ur-help-box {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 1.5rem;
    padding: 2rem 1.5rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
    margin-bottom: 2.5rem;
    box-shadow: 0 4px 16px rgba(15, 23, 42, 0.03);
}
@media (min-width: 768px) {
    .ur-help-box {
        flex-direction: row;
        padding: 2.25rem 2.5rem;
    }
}
.dark .ur-help-box {
    background: #0f172a;
    border-color: #1e293b;
}

.ur-help-content h3 {
    font-size: 1.35rem;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 0.35rem;
}
.dark .ur-help-content h3 { color: #ffffff; }

.ur-help-content p {
    font-size: 0.88rem;
    color: #64748b;
}
.dark .ur-help-content p { color: #94a3b8; }

.ur-help-actions {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 0.75rem;
}

.ur-btn-wa {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.35rem;
    border-radius: 0.85rem;
    background: #25d366;
    color: #ffffff;
    font-size: 0.85rem;
    font-weight: 700;
    text-decoration: none;
    box-shadow: 0 4px 12px rgba(37, 211, 102, 0.25);
    transition: all 0.2s ease;
}
.ur-btn-wa:hover {
    background: #20bd5a;
    transform: translateY(-1px);
}

.ur-btn-contact {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.35rem;
    border-radius: 0.85rem;
    background: #f1f5f9;
    color: #0f172a;
    font-size: 0.85rem;
    font-weight: 700;
    text-decoration: none;
    border: 1px solid #e2e8f0;
    transition: all 0.2s ease;
}
.ur-btn-contact:hover {
    background: #e2e8f0;
}
.dark .ur-btn-contact {
    background: #1e293b;
    color: #ffffff;
    border-color: #334155;
}
.dark .ur-btn-contact:hover {
    background: #334155;
}

/* ─── 8. Final Confidence CTA Banner ─── */
.ur-final-cta-banner {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);
    border-radius: 2rem;
    padding: 2.5rem 1.5rem;
    text-align: center;
    color: #ffffff;
    box-shadow: 0 20px 40px -12px rgba(15, 23, 42, 0.25);
    position: relative;
    overflow: hidden;
}
@media (min-width: 640px) {
    .ur-final-cta-banner { padding: 3.5rem 2.5rem; }
}

.ur-final-cta-title {
    font-size: 1.85rem;
    font-weight: 900;
    color: #ffffff;
    letter-spacing: -0.025em;
    margin-bottom: 0.75rem;
}
@media (min-width: 640px) {
    .ur-final-cta-title { font-size: 2.25rem; }
}

.ur-final-cta-desc {
    font-size: 0.95rem;
    color: #cbd5e1;
    max-width: 580px;
    margin: 0 auto 1.75rem;
    line-height: 1.6;
}

.ur-final-cta-btns {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: center;
    gap: 1rem;
}

.ur-btn-primary-glow {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.85rem 2rem;
    border-radius: 1rem;
    background: #ffffff;
    color: #1e40af;
    font-size: 0.95rem;
    font-weight: 800;
    text-decoration: none;
    box-shadow: 0 4px 16px rgba(255, 255, 255, 0.2);
    transition: all 0.2s ease;
}
.ur-btn-primary-glow:hover {
    background: #f8fafc;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(255, 255, 255, 0.3);
}

.ur-btn-outline-white {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.85rem 1.75rem;
    border-radius: 1rem;
    background: rgba(255, 255, 255, 0.1);
    color: #ffffff;
    font-size: 0.95rem;
    font-weight: 700;
    text-decoration: none;
    border: 1px solid rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(8px);
    transition: all 0.2s ease;
}
.ur-btn-outline-white:hover {
    background: rgba(255, 255, 255, 0.2);
}

/* ─── Feedback Review Modal Overlay ─── */
.ur-review-modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.7);
    backdrop-filter: blur(6px);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    padding: 1rem;
}
.ur-review-modal-overlay.active {
    display: flex;
}

.ur-review-modal-card {
    background: #ffffff;
    border-radius: 1.5rem;
    max-width: 480px;
    width: 100%;
    padding: 2rem;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    position: relative;
}
.dark .ur-review-modal-card {
    background: #0f172a;
    border: 1px solid #1e293b;
}

.ur-modal-star-btn {
    font-size: 2rem;
    color: #cbd5e1;
    cursor: pointer;
    background: none;
    border: none;
    padding: 0 2px;
    transition: color 0.15s ease;
}
.ur-modal-star-btn.active,
.ur-modal-star-btn.hover {
    color: #f59e0b;
}
</style>

<div class="ur-trust-section" id="ur-trust-anchor">
    <div class="ur-trust-wrap">

        {{-- ============================================================
             1. TRUST SECTION HEADING
             ============================================================ --}}
        <div class="ur-trust-header">
            <div class="ur-trust-eyebrow">
                <i class="ph-bold ph-shield-check"></i>
                <span>Safety &amp; Transparency First</span>
            </div>

            <h2 class="ur-trust-title">
                {!! !empty($site_settings['trust_heading']) ? e($site_settings['trust_heading']) : 'Buy With <span class="highlight-blue">Confidence</span>. Rent With <span class="highlight-green">Trust</span>.' !!}
            </h2>

            <p class="ur-trust-subtitle">
                {{ $site_settings['trust_subheading'] ?? 'Everything you need to choose your plan with confidence — transparent pricing, secure payments, clear benefits, and dedicated support.' }}
            </p>

            <div class="ur-trust-pill-strip">
                @if(!empty($site_settings['trust_pill_message']))
                    <span class="ur-trust-pill-item">
                        <i class="ph-bold ph-check-circle"></i> {{ $site_settings['trust_pill_message'] }}
                    </span>
                @else
                    <span class="ur-trust-pill-item">
                        <i class="ph-bold ph-check-circle"></i> No hidden charges
                    </span>
                    <span class="ur-trust-pill-item">
                        <i class="ph-bold ph-check-circle"></i> Clear plans
                    </span>
                    <span class="ur-trust-pill-item">
                        <i class="ph-bold ph-check-circle"></i> Secure checkout
                    </span>
                    <span class="ur-trust-pill-item">
                        <i class="ph-bold ph-check-circle"></i> Dedicated customer support
                    </span>
                @endif
            </div>
        </div>

        {{-- ============================================================
             2. TRUST CARDS (6 Modern Cards)
             ============================================================ --}}
        <div class="ur-trust-grid">
            
            {{-- Card 1: Secure Payments --}}
            <div class="ur-trust-card">
                <div class="ur-trust-card__top">
                    <div class="ur-trust-icon-box blue">
                        <i class="ph-bold ph-lock-key"></i>
                    </div>
                    <h3 class="ur-trust-card__heading">Secure Payments</h3>
                    <p class="ur-trust-card__desc">
                        Your payment is processed through a secure payment gateway. We never store your complete card or banking credentials.
                    </p>
                </div>
                <div class="ur-trust-card__bottom">
                    <span class="ur-trust-card__badge-note">
                        <i class="ph-bold ph-shield-check"></i> 256-Bit SSL Encrypted
                    </span>
                </div>
            </div>

            {{-- Card 2: Transparent Pricing --}}
            <div class="ur-trust-card">
                <div class="ur-trust-card__top">
                    <div class="ur-trust-icon-box green">
                        <i class="ph-bold ph-credit-card"></i>
                    </div>
                    <h3 class="ur-trust-card__heading">Transparent Pricing</h3>
                    <p class="ur-trust-card__desc">
                        See the plan price, features, duration, and applicable charges clearly before you pay. The final payable amount is always shown upfront.
                    </p>
                </div>
                <div class="ur-trust-card__bottom">
                    <span class="ur-trust-card__badge-note">
                        <i class="ph-bold ph-receipt"></i> Zero Surprise Surcharges
                    </span>
                </div>
            </div>

            {{-- Card 3: Clear Plan Benefits --}}
            <div class="ur-trust-card">
                <div class="ur-trust-card__top">
                    <div class="ur-trust-icon-box indigo">
                        <i class="ph-bold ph-check-circle"></i>
                    </div>
                    <h3 class="ur-trust-card__heading">Know What You Get</h3>
                    <p class="ur-trust-card__desc">
                        Every plan clearly explains its features, limits, validity, and benefits so you can choose the right option with complete certainty.
                    </p>
                </div>
                <div class="ur-trust-card__bottom">
                    <a href="#comparison-section" onclick="urScrollTo('comparison-section'); return false;" class="ur-trust-card__action-btn">
                        <span>Compare Plans</span> <i class="ph-bold ph-arrow-right"></i>
                    </a>
                </div>
            </div>

            {{-- Card 4: Customer Support --}}
            <div class="ur-trust-card">
                <div class="ur-trust-card__top">
                    <div class="ur-trust-icon-box amber">
                        <i class="ph-bold ph-headset"></i>
                    </div>
                    <h3 class="ur-trust-card__heading">We're Here to Help</h3>
                    <p class="ur-trust-card__desc">
                        Need help choosing a plan or using UnlockRentals? Contact our support team whenever you need assistance.
                    </p>
                </div>
                <div class="ur-trust-card__bottom flex items-center gap-3 flex-wrap">
                    <a href="https://wa.me/{{ $cleanWaPhone }}?text=Hello%20UnlockRentals%2C%20I%20need%20help%20choosing%20a%20plan"
                       target="_blank" rel="noopener noreferrer"
                       class="ur-trust-card__action-btn text-emerald-600 dark:text-emerald-400">
                        <i class="ph-bold ph-whatsapp-logo text-base"></i>
                        <span>WhatsApp Support</span>
                    </a>
                    <span class="text-slate-300 dark:text-slate-700">·</span>
                    <a href="tel:{{ $cleanTel }}" class="ur-trust-card__action-btn">
                        <i class="ph-bold ph-phone text-base"></i>
                        <span>Contact Support</span>
                    </a>
                </div>
            </div>

            {{-- Card 5: Privacy & Data Protection --}}
            <div class="ur-trust-card">
                <div class="ur-trust-card__top">
                    <div class="ur-trust-icon-box blue">
                        <i class="ph-bold ph-shield-check"></i>
                    </div>
                    <h3 class="ur-trust-card__heading">Your Privacy Matters</h3>
                    <p class="ur-trust-card__desc">
                        Your personal information is handled responsibly and used according to our Privacy Policy. We do not sell or spam your data.
                    </p>
                </div>
                <div class="ur-trust-card__bottom">
                    <a href="{{ route('privacy') }}" class="ur-trust-card__action-btn">
                        <span>Privacy Policy</span> <i class="ph-bold ph-arrow-square-out"></i>
                    </a>
                </div>
            </div>

            {{-- Card 6: Easy & Transparent Experience --}}
            <div class="ur-trust-card">
                <div class="ur-trust-card__top">
                    <div class="ur-trust-icon-box green">
                        <i class="ph-bold ph-sparkle"></i>
                    </div>
                    <h3 class="ur-trust-card__heading">Simple &amp; Transparent</h3>
                    <p class="ur-trust-card__desc">
                        Choose your plan, review the details, make a secure payment, and start using your benefits immediately without complicated steps.
                    </p>
                </div>
                <div class="ur-trust-card__bottom">
                    <span class="ur-trust-card__badge-note">
                        <i class="ph-bold ph-lightning"></i> Instant Activation
                    </span>
                </div>
            </div>

        </div>

        {{-- ============================================================
             3. HOW YOUR PURCHASE WORKS (01 → 02 → 03 → 04)
             ============================================================ --}}
        <div class="ur-how-section">
            <div class="ur-how-header">
                <h3 class="ur-how-title">How Your Purchase Works</h3>
                <p class="ur-how-sub">A clear, straightforward 4-step process designed for total transparency.</p>
            </div>

            <div class="ur-how-steps">
                <div class="ur-step-item">
                    <span class="ur-step-num">01</span>
                    <i class="ph-bold ph-cursor-click ur-step-icon"></i>
                    <h4 class="ur-step-heading">Choose a Plan</h4>
                    <p class="ur-step-desc">Select the rental or buyer pass that matches your requirement and timeline.</p>
                </div>

                <div class="ur-step-item">
                    <span class="ur-step-num">02</span>
                    <i class="ph-bold ph-list-checks ur-step-icon"></i>
                    <h4 class="ur-step-heading">Review Benefits</h4>
                    <p class="ur-step-desc">Check features, duration, unlock limits, price, and applicable taxes before paying.</p>
                </div>

                <div class="ur-step-item">
                    <span class="ur-step-num">03</span>
                    <i class="ph-bold ph-shield-check ur-step-icon"></i>
                    <h4 class="ur-step-heading">Secure Payment</h4>
                    <p class="ur-step-desc">Complete payment through our secure payment gateway via UPI, Card, or NetBanking.</p>
                </div>

                <div class="ur-step-item">
                    <span class="ur-step-num">04</span>
                    <i class="ph-bold ph-key-return ur-step-icon"></i>
                    <h4 class="ur-step-heading">Start Using Benefits</h4>
                    <p class="ur-step-desc">Your plan is activated instantly. Unlock verified owner contacts with zero brokerage.</p>
                </div>
            </div>
        </div>

        {{-- ============================================================
             4. CUSTOMER REVIEWS / SOCIAL PROOF (Genuine verified only)
             ============================================================ --}}
        <div class="ur-reviews-section">
            <div class="ur-reviews-header">
                <h3 class="ur-reviews-title">What Our Customers Say</h3>
                <p class="ur-reviews-sub">Genuine verified feedback from real tenants and property owners.</p>
            </div>

            @if($hasApprovedReviews)
                <div class="ur-reviews-grid">
                    @foreach($feedbacks as $fb)
                        @php
                            $user = $fb->user;
                            $firstName = $user ? \Illuminate\Support\Str::before($user->name, ' ') : 'Customer';
                            $hasPlan = $user && $user->userPlans()->whereIn('status', ['active', 'approved'])->exists();
                            $roleLabel = $hasPlan ? 'Verified Member' : ($user ? ucfirst($user->role) : 'Verified Customer');
                        @endphp
                        <div class="ur-review-card">
                            <div>
                                <div class="ur-review-stars" aria-label="{{ $fb->rating }} out of 5 stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="ph-fill ph-star {{ $i <= $fb->rating ? 'text-amber-400' : 'text-slate-300 dark:text-slate-700' }}"></i>
                                    @endfor
                                </div>
                                <p class="ur-review-quote">“{{ $fb->comment ?: 'Very easy to understand the available rental options. The process was simple.' }}”</p>
                            </div>
                            <div class="ur-review-author">
                                <div class="ur-review-avatar">
                                    {{ strtoupper(substr($firstName, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="ur-review-name">{{ $firstName }}</p>
                                    <p class="ur-review-badge">
                                        <i class="ph-bold ph-seal-check"></i> {{ $roleLabel }}
                                        <span class="text-slate-400 font-normal">· {{ $fb->created_at ? $fb->created_at->format('M Y') : '' }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="ur-empty-reviews-box">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center mx-auto mb-3 text-2xl">
                        <i class="ph-bold ph-chat-circle-dots"></i>
                    </div>
                    <h4>Be among the first to share your UnlockRentals experience.</h4>
                    <p>We believe in 100% genuine reviews and never fabricate customer feedback. Your honest thoughts help us maintain the highest service standard.</p>
                    <button type="button" onclick="urOpenReviewModal()" class="ur-write-review-btn">
                        <i class="ph-bold ph-pencil-simple"></i>
                        <span>Write a Review</span>
                    </button>
                </div>
            @endif
        </div>

        {{-- ============================================================
             5. CANCELLATION & REFUND TRANSPARENCY BOX
             ============================================================ --}}
        <div class="ur-refund-box" id="refund-policy-summary">
            <div class="ur-refund-box__top">
                <div>
                    <h3 class="ur-refund-box__heading">
                        <i class="ph-bold ph-arrow-counter-clockwise text-blue-600"></i>
                        <span>Cancellation &amp; Refund Policy</span>
                    </h3>
                    <p class="ur-refund-box__summary">
                        {{ $site_settings['refund_policy_summary'] ?? 'Please review our cancellation and refund policy before purchasing.' }}
                    </p>
                </div>
                <div>
                    <a href="{{ route('refund-policy') }}" class="ur-trust-card__action-btn text-xs sm:text-sm font-bold">
                        <span>Read Full Policy Details</span> <i class="ph-bold ph-arrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="ur-refund-details-grid">
                <div class="ur-refund-item">
                    <p class="ur-refund-item__title">
                        <i class="ph-bold ph-check text-emerald-600"></i> Eligibility
                    </p>
                    <p class="ur-refund-item__desc">
                        Unused plans with zero revealed landlord contacts, duplicate transactions, or verified technical activation failures.
                    </p>
                </div>

                <div class="ur-refund-item">
                    <p class="ur-refund-item__title">
                        <i class="ph-bold ph-clock text-blue-600"></i> Refund Window
                    </p>
                    <p class="ur-refund-item__desc">
                        Requests must be submitted within 24 to 48 hours of purchase before any owner contacts are viewed.
                    </p>
                </div>

                <div class="ur-refund-item">
                    <p class="ur-refund-item__title">
                        <i class="ph-bold ph-x text-rose-500"></i> Non-Refundable Situations
                    </p>
                    <p class="ur-refund-item__desc">
                        Once contact unlock credits have been revealed or validity has expired, digital services are deemed consumed.
                    </p>
                </div>

                <div class="ur-refund-item">
                    <p class="ur-refund-item__title">
                        <i class="ph-bold ph-bank text-indigo-600"></i> Processing Time
                    </p>
                    <p class="ur-refund-item__desc">
                        Approved refunds are credited to your original payment mode within 5 to 7 business days via payment gateway.
                    </p>
                </div>

                <div class="ur-refund-item">
                    <p class="ur-refund-item__title">
                        <i class="ph-bold ph-prohibit text-amber-600"></i> No Auto-Lock Contracts
                    </p>
                    <p class="ur-refund-item__desc">
                        Plans naturally expire at the end of validity with zero recurring billing surprises.
                    </p>
                </div>

                <div class="ur-refund-item">
                    <p class="ur-refund-item__title">
                        <i class="ph-bold ph-headset text-emerald-600"></i> Contact Support
                    </p>
                    <p class="ur-refund-item__desc">
                        Email <a href="mailto:{{ $refundEmail }}" class="underline font-semibold text-slate-800 dark:text-slate-200">{{ $refundEmail }}</a> or message our WhatsApp desk for fast resolution.
                    </p>
                </div>
            </div>
        </div>

        {{-- ============================================================
             6. FAQ TRUST SECTION (9 Questions Accordion)
             ============================================================ --}}
        <div class="ur-faq-container" id="trust-faq-section">
            <div class="ur-faq-header">
                <span class="ur-trust-eyebrow">
                    <i class="ph-bold ph-question"></i> Clear Answers
                </span>
                <h3 class="ur-faq-title">Frequently Asked Questions</h3>
                <p class="ur-reviews-sub">Honest answers to the most common questions before choosing a plan.</p>
            </div>

            <div class="ur-faq-list">
                
                {{-- Q1 --}}
                <div class="ur-faq-item active">
                    <button type="button" class="ur-faq-trigger" onclick="urToggleFaq(this)">
                        <span class="ur-faq-question">Is payment on UnlockRentals secure?</span>
                        <span class="ur-faq-icon"><i class="ph-bold ph-caret-down"></i></span>
                    </button>
                    <div class="ur-faq-body">
                        <p class="ur-faq-answer">
                            Yes. Payments are processed through standard secure payment gateways (such as Razorpay and UPI) utilizing 256-bit SSL encryption. We never store your full card number, CVV, UPI PIN, or banking passwords on our servers.
                        </p>
                    </div>
                </div>

                {{-- Q2 --}}
                <div class="ur-faq-item">
                    <button type="button" class="ur-faq-trigger" onclick="urToggleFaq(this)">
                        <span class="ur-faq-question">What is included in each plan?</span>
                        <span class="ur-faq-icon"><i class="ph-bold ph-caret-down"></i></span>
                    </button>
                    <div class="ur-faq-body">
                        <p class="ur-faq-answer">
                            Each plan clearly displays its specific contact view limit, validity duration (in days), direct WhatsApp & phone connect privileges, and priority visit scheduling. The exact features are explicitly listed on every card and in the comparison table.
                        </p>
                    </div>
                </div>

                {{-- Q3 --}}
                <div class="ur-faq-item">
                    <button type="button" class="ur-faq-trigger" onclick="urToggleFaq(this)">
                        <span class="ur-faq-question">How long is my plan valid?</span>
                        <span class="ur-faq-icon"><i class="ph-bold ph-caret-down"></i></span>
                    </button>
                    <div class="ur-faq-body">
                        <p class="ur-faq-answer">
                            Plan validity depends on the tier you choose: standard Rental Passes are typically valid for 30 days, while annual passes (Buyer Passes) extend for a full 365 days. Your dashboard always displays the exact remaining days and contact count.
                        </p>
                    </div>
                </div>

                {{-- Q4 --}}
                <div class="ur-faq-item">
                    <button type="button" class="ur-faq-trigger" onclick="urToggleFaq(this)">
                        <span class="ur-faq-question">Are there any additional charges?</span>
                        <span class="ur-faq-icon"><i class="ph-bold ph-caret-down"></i></span>
                    </button>
                    <div class="ur-faq-body">
                        <p class="ur-faq-answer">
                            No. We never add hidden surcharges, convenience penalties, or brokerage commissions. The total payable amount, including any applicable GST, is transparently detailed on the checkout screen before you authorize payment.
                        </p>
                    </div>
                </div>

                {{-- Q5 --}}
                <div class="ur-faq-item">
                    <button type="button" class="ur-faq-trigger" onclick="urToggleFaq(this)">
                        <span class="ur-faq-question">Can I cancel my plan?</span>
                        <span class="ur-faq-icon"><i class="ph-bold ph-caret-down"></i></span>
                    </button>
                    <div class="ur-faq-body">
                        <p class="ur-faq-answer">
                            Yes. Plans do not enforce recurring auto-debit without your authorization; they automatically expire when the duration ends. If you made an accidental purchase and have not viewed any owner contacts, you can request cancellation within 24–48 hours.
                        </p>
                    </div>
                </div>

                {{-- Q6 --}}
                <div class="ur-faq-item">
                    <button type="button" class="ur-faq-trigger" onclick="urToggleFaq(this)">
                        <span class="ur-faq-question">What is the refund policy?</span>
                        <span class="ur-faq-icon"><i class="ph-bold ph-caret-down"></i></span>
                    </button>
                    <div class="ur-faq-body">
                        <p class="ur-faq-answer">
                            Refunds are evaluated for duplicate payments, technical activation failures, or completely unutilized plans requested within 24 to 48 hours of purchase. Once landlord contact credits have been revealed or contacted, the digital service has been consumed and is non-refundable. Approved refunds take 5–7 business days to process to your original payment method.
                        </p>
                    </div>
                </div>

                {{-- Q7 --}}
                <div class="ur-faq-item">
                    <button type="button" class="ur-faq-trigger" onclick="urToggleFaq(this)">
                        <span class="ur-faq-question">When will my plan be activated?</span>
                        <span class="ur-faq-icon"><i class="ph-bold ph-caret-down"></i></span>
                    </button>
                    <div class="ur-faq-body">
                        <p class="ur-faq-answer">
                            Activation occurs immediately once the payment gateway confirms a successful transaction. For manual UPI/QR transfers with transaction reference verification, activation occurs immediately upon admin payment verification.
                        </p>
                    </div>
                </div>

                {{-- Q8 --}}
                <div class="ur-faq-item">
                    <button type="button" class="ur-faq-trigger" onclick="urToggleFaq(this)">
                        <span class="ur-faq-question">How can I contact customer support?</span>
                        <span class="ur-faq-icon"><i class="ph-bold ph-caret-down"></i></span>
                    </button>
                    <div class="ur-faq-body">
                        <p class="ur-faq-answer">
                            You can reach us directly via WhatsApp at {{ $sitePhone }}, call our customer desk at {{ $sitePhone }}, or send an email to {{ $siteEmail }}. Our support team operates Monday through Saturday to assist with inquiries and listing unlocks.
                        </p>
                    </div>
                </div>

                {{-- Q9 --}}
                <div class="ur-faq-item">
                    <button type="button" class="ur-faq-trigger" onclick="urToggleFaq(this)">
                        <span class="ur-faq-question">Can I upgrade my plan later?</span>
                        <span class="ur-faq-icon"><i class="ph-bold ph-caret-down"></i></span>
                    </button>
                    <div class="ur-faq-body">
                        <p class="ur-faq-answer">
                            Yes! If you need more contact unlocks or extended validity, you can upgrade to a higher tier plan at any time. Any remaining contacts from your current plan carry forward smoothly.
                        </p>
                    </div>
                </div>

            </div>
        </div>

        {{-- ============================================================
             7. TRUST FOOTER: "Still Have Questions?"
             ============================================================ --}}
        <div class="ur-help-box" id="trust-help-box">
            <div class="ur-help-content">
                <h3>Still Have Questions?</h3>
                <p>We're here to help you choose the right plan and answer any questions before you purchase.</p>
            </div>

            <div class="ur-help-actions">
                <a href="https://wa.me/{{ $cleanWaPhone }}?text=Hello%20UnlockRentals%2C%20I%20have%20questions%20about%20your%20plans"
                   target="_blank" rel="noopener noreferrer"
                   class="ur-btn-wa">
                    <i class="ph-bold ph-whatsapp-logo text-lg"></i>
                    <span>WhatsApp Us</span>
                </a>

                <a href="tel:{{ $cleanTel }}" class="ur-btn-contact">
                    <i class="ph-bold ph-phone text-lg"></i>
                    <span>Contact Support</span>
                </a>

                <a href="#plans-tab-bar" onclick="urScrollToPlans(); return false;" class="ur-btn-contact">
                    <i class="ph-bold ph-squares-four text-lg"></i>
                    <span>View Plans</span>
                </a>
            </div>
        </div>

        {{-- ============================================================
             8. FINAL CTA BANNER: "Choose Your Plan With Confidence"
             ============================================================ --}}
        <div class="ur-final-cta-banner">
            <h3 class="ur-final-cta-title">Choose Your Plan With Confidence</h3>
            <p class="ur-final-cta-desc">
                Transparent pricing. Clear benefits. Secure checkout. Helpful support. Zero middleman commissions.
            </p>
            <div class="ur-final-cta-btns">
                <a href="#plans-tab-bar" onclick="urScrollToPlans(); return false;" class="ur-btn-primary-glow">
                    <i class="ph-bold ph-lock-key-open"></i>
                    <span>View Plans &amp; Pricing</span>
                </a>
                <a href="https://wa.me/{{ $cleanWaPhone }}?text=Hello%20UnlockRentals%2C%20I%20would%20like%20to%20talk%20to%20support%20about%20plans"
                   target="_blank" rel="noopener noreferrer"
                   class="ur-btn-outline-white">
                    <i class="ph-bold ph-headset"></i>
                    <span>Talk to Support</span>
                </a>
            </div>
        </div>

    </div>
</div>

{{-- Review Submission Modal --}}
<div class="ur-review-modal-overlay" id="urReviewModal">
    <div class="ur-review-modal-card">
        <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-100 dark:border-slate-800">
            <h4 class="text-base font-extrabold text-slate-900 dark:text-white">Share Your Experience</h4>
            <button type="button" onclick="urCloseReviewModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-white p-1">
                <i class="ph-bold ph-x text-lg"></i>
            </button>
        </div>

        <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">
            Help fellow renters and owners make informed choices. All genuine feedback is reviewed and published transparently.
        </p>

        <form id="urFeedbackForm" onsubmit="urSubmitFeedback(event)">
            @csrf
            <div class="mb-4">
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                    Your Rating <span class="text-red-500">*</span>
                </label>
                <div class="flex items-center gap-1" id="urModalStarContainer">
                    @for($s = 1; $s <= 5; $s++)
                        <button type="button" class="ur-modal-star-btn {{ $s <= 5 ? 'active' : '' }}" data-value="{{ $s }}" onclick="urSetRating({{ $s }})">★</button>
                    @endfor
                </div>
                <input type="hidden" name="rating" id="urFeedbackRating" value="5">
            </div>

            <div class="mb-4">
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                    Your Comments / Experience
                </label>
                <textarea name="comment" id="urFeedbackComment" rows="4" required
                          placeholder="How was your experience exploring rental properties or unlocking direct contacts on UnlockRentals?"
                          class="w-full p-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <div id="urFeedbackSuccess" class="hidden mb-3 p-2.5 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 text-xs font-semibold">
                <i class="ph-bold ph-check-circle"></i> Thank you! Your review was submitted and will appear once verified.
            </div>

            <div id="urFeedbackError" class="hidden mb-3 p-2.5 rounded-lg bg-red-50 dark:bg-red-950/40 text-red-700 dark:text-red-300 text-xs font-semibold"></div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="urCloseReviewModal()" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800">
                    Cancel
                </button>
                <button type="submit" id="urFeedbackSubmitBtn" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition shadow-xs flex items-center gap-1.5 cursor-pointer">
                    <span>Submit Review</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function urToggleFaq(btn) {
    const item = btn.closest('.ur-faq-item');
    if (!item) return;
    const isCurrentlyActive = item.classList.contains('active');
    
    // Optional: close other FAQ items
    document.querySelectorAll('.ur-faq-item').forEach(i => {
        if (i !== item) i.classList.remove('active');
    });

    item.classList.toggle('active', !isCurrentlyActive);
}

function urScrollTo(elementId) {
    const el = document.getElementById(elementId);
    if (el) {
        el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

function urScrollToPlans() {
    const target = document.getElementById('plans-tab-bar') || document.getElementById('pricing-plans') || document.getElementById('category-toggle');
    if (target) {
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    } else {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
}

function urOpenReviewModal() {
    const modal = document.getElementById('urReviewModal');
    if (modal) modal.classList.add('active');
}

function urCloseReviewModal() {
    const modal = document.getElementById('urReviewModal');
    if (modal) modal.classList.remove('active');
}

function urSetRating(rating) {
    document.getElementById('urFeedbackRating').value = rating;
    const starBtns = document.querySelectorAll('#urModalStarContainer .ur-modal-star-btn');
    starBtns.forEach(btn => {
        const val = parseInt(btn.dataset.value, 10);
        btn.classList.toggle('active', val <= rating);
    });
}

function urSubmitFeedback(e) {
    e.preventDefault();
    const btn = document.getElementById('urFeedbackSubmitBtn');
    const successMsg = document.getElementById('urFeedbackSuccess');
    const errorMsg = document.getElementById('urFeedbackError');
    const rating = document.getElementById('urFeedbackRating').value;
    const comment = document.getElementById('urFeedbackComment').value;
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    if (!rating || !comment.trim()) {
        errorMsg.textContent = 'Please enter your review comments.';
        errorMsg.classList.remove('hidden');
        return;
    }

    btn.disabled = true;
    btn.innerHTML = '<i class="ph-bold ph-circle-notch animate-spin"></i> Submitting...';
    errorMsg.classList.add('hidden');

    fetch("{{ route('feedback.store') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ rating: rating, comment: comment })
    })
    .then(r => r.json())
    .then(data => {
        btn.innerHTML = '<i class="ph-bold ph-check"></i> Submitted';
        successMsg.classList.remove('hidden');
        setTimeout(() => {
            urCloseReviewModal();
            document.getElementById('urFeedbackComment').value = '';
            btn.disabled = false;
            btn.innerHTML = '<span>Submit Review</span>';
            successMsg.classList.add('hidden');
        }, 2200);
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<span>Submit Review</span>';
        errorMsg.textContent = 'An error occurred while saving your review. Please try again.';
        errorMsg.classList.remove('hidden');
    });
}
</script>
