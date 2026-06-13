<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $userPlan->invoice_id ?? 'INV-' . $userPlan->id }} - UnlockRentals</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@700;800&display=swap" rel="stylesheet">
    <!-- Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            line-height: 1.5;
            padding: 40px 20px;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 24px;
            border: 1px solid rgba(15, 23, 42, 0.08);
            box-shadow: 0 20px 50px rgba(15, 23, 42, 0.04);
            padding: 48px;
            position: relative;
            overflow: hidden;
        }
        /* Paid Watermark Badge */
        .paid-watermark {
            position: absolute;
            top: 40px;
            right: 48px;
            border: 4px solid #10b981;
            color: #10b981;
            font-family: 'Poppins', sans-serif;
            font-size: 18px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            padding: 8px 16px;
            border-radius: 12px;
            transform: rotate(-12deg);
            opacity: 0.85;
            background-color: rgba(16, 185, 129, 0.04);
            user-select: none;
            pointer-events: none;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 32px;
            margin-bottom: 32px;
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }
        .brand-logo {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #2563eb, #7c3aed);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 20px;
        }
        .brand-name {
            font-family: 'Poppins', sans-serif;
            font-size: 22px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.02em;
        }
        .invoice-title {
            text-align: right;
        }
        .invoice-title h1 {
            font-family: 'Poppins', sans-serif;
            font-size: 28px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.1;
        }
        .invoice-meta-info {
            font-size: 13px;
            color: #64748b;
            margin-top: 4px;
            font-weight: 500;
        }
        .billing-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }
        .billing-col h3 {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: #94a3b8;
            margin-bottom: 12px;
        }
        .billing-col p {
            font-size: 14px;
            color: #334155;
            margin-bottom: 4px;
        }
        .billing-col p.name {
            font-weight: 700;
            color: #0f172a;
            font-size: 15px;
        }
        .item-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 32px;
        }
        .item-table th {
            padding: 12px 16px;
            background-color: #f8fafc;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #64748b;
            text-align: left;
            border-bottom: 2px solid #e2e8f0;
        }
        .item-table th.right, .item-table td.right {
            text-align: right;
        }
        .item-table td {
            padding: 16px;
            font-size: 14px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
        }
        .item-title {
            font-weight: 700;
            color: #0f172a;
        }
        .item-desc {
            font-size: 12px;
            color: #64748b;
            margin-top: 2px;
        }
        .totals-section {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 40px;
        }
        .totals-box {
            width: 320px;
            background-color: #f8fafc;
            border-radius: 16px;
            padding: 20px;
            border: 1px solid #f1f5f9;
        }
        .totals-row {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            color: #64748b;
            margin-bottom: 8px;
            font-weight: 500;
        }
        .totals-row.final {
            margin-top: 12px;
            border-top: 2px dashed #e2e8f0;
            padding-top: 12px;
            font-size: 18px;
            color: #0f172a;
            font-weight: 800;
            margin-bottom: 0;
        }
        .totals-row.discount {
            color: #10b981;
        }
        .footer-note {
            text-align: center;
            border-top: 2px solid #f1f5f9;
            padding-top: 24px;
            font-size: 12px;
            color: #94a3b8;
            font-weight: 500;
        }
        .actions-bar {
            max-width: 800px;
            margin: 24px auto 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: 16px;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
        }
        .btn-secondary {
            background-color: #ffffff;
            border: 1px solid #cbd5e1;
            color: #334155;
        }
        .btn-secondary:hover {
            background-color: #f8fafc;
        }
        .btn-primary {
            background-color: #0f172a;
            color: #ffffff;
        }
        .btn-primary:hover {
            background-color: #2563eb;
        }

        /* Print Media Queries */
        @media print {
            body {
                background-color: #ffffff;
                padding: 0;
            }
            .invoice-container {
                border: none;
                box-shadow: none;
                padding: 0;
                width: 100%;
                max-width: 100%;
            }
            .actions-bar {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="invoice-container">
        <!-- Watermark -->
        <div class="paid-watermark">PAID</div>

        <!-- Header -->
        <div class="header">
            <div class="brand">
                <div class="brand-logo">
                    <i class="ph-bold ph-lightning"></i>
                </div>
                <span class="brand-name">UnlockRentals</span>
            </div>
            <div class="invoice-title">
                <h1>INVOICE</h1>
                <div class="invoice-meta-info">
                    Invoice ID: <span style="font-family: monospace; font-weight: 700;">{{ $userPlan->invoice_id ?? 'INV-MOCK-' . $userPlan->id }}</span>
                </div>
            </div>
        </div>

        <!-- Billing Info -->
        <div class="billing-grid">
            <div class="billing-col">
                <h3>Billed By</h3>
                <p class="name">UnlockRentals Services Private Limited</p>
                <p>12th Floor, Nexus Tower</p>
                <p>Connaught Place, New Delhi - 110001</p>
                <p>GSTIN: 07AAFCD0182K1Z9</p>
                <p>support@unlockrentals.com</p>
            </div>
            <div class="billing-col" style="text-align: right;">
                <h3>Billed To</h3>
                <p class="name">{{ $userPlan->user->name }}</p>
                <p>{{ $userPlan->user->email }}</p>
                <p>Phone: {{ $userPlan->user->phone ?? '—' }}</p>
                <p>Billing Date: {{ $userPlan->created_at->format('M d, Y') }}</p>
                <p>Payment Method: {{ strtoupper($userPlan->payment_method ?? 'UPI') }}</p>
            </div>
        </div>

        <!-- Table -->
        <table class="item-table">
            <thead>
                <tr>
                    <th>Item Details</th>
                    <th class="right">Duration</th>
                    <th class="right">Limit</th>
                    <th class="right">Rate</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="item-title">UnlockRentals Premium Subscription: {{ $userPlan->plan?->name ?? 'Premium Plan' }}</div>
                        <div class="item-desc">Premium owner contact unlock package. Access is validated across system properties.</div>
                    </td>
                    <td class="right" style="font-weight: 500;">
                        {{ $userPlan->billing_period === 'yearly' ? '365 days' : '30 days' }}
                    </td>
                    <td class="right" style="font-weight: 500;">
                        {{ $userPlan->plan?->contact_limit ?? 'N/A' }} Unlocks
                    </td>
                    <td class="right" style="font-weight: 600;">
                        Rs. {{ number_format($userPlan->subtotal_amount, 2) }}
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Totals Box -->
        <div class="totals-section">
            <div class="totals-box">
                <div class="totals-row">
                    <span>Subtotal</span>
                    <span>Rs. {{ number_format($userPlan->subtotal_amount, 2) }}</span>
                </div>
                @if($userPlan->discount_amount > 0)
                <div class="totals-row discount">
                    <span>Discount</span>
                    <span>- Rs. {{ number_format($userPlan->discount_amount, 2) }}</span>
                </div>
                @endif
                <div class="totals-row">
                    <span>Tax (GST 18%)</span>
                    <span>Rs. {{ number_format($userPlan->gst_amount, 2) }}</span>
                </div>
                <div class="totals-row final">
                    <span>Total Paid</span>
                    <span>Rs. {{ number_format($userPlan->final_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer-note">
            <p>Thank you for choosing UnlockRentals Premium. This is a computer-generated invoice and does not require signature.</p>
        </div>
    </div>

    <!-- Actions Bar -->
    <div class="actions-bar">
        <a href="{{ route('billing.history') }}" class="btn btn-secondary">
            <i class="ph-bold ph-arrow-left"></i>
            Back to Billing History
        </a>
        <button onclick="window.print()" class="btn btn-primary">
            <i class="ph-bold ph-printer"></i>
            Print Invoice
        </button>
    </div>

</body>
</html>
