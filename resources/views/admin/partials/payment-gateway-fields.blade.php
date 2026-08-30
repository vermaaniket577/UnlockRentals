<div class="payment-gateway-item bg-slate-50/80 border border-slate-200/90 rounded-2xl p-5 sm:p-6 transition-all hover:border-slate-300 shadow-xs" data-index="{{ $index }}">
    <input type="hidden" name="payment_gateways[{{ $index }}][id]" value="{{ $gateway['id'] ?? '' }}">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-5 pb-4 border-b border-slate-200/70">
        <div class="flex flex-wrap items-center gap-4">
            <label class="inline-flex items-center gap-2 text-xs font-bold text-slate-800 cursor-pointer bg-white px-3 py-1.5 rounded-xl border border-slate-200 shadow-2xs hover:border-blue-300 transition-colors">
                <input type="radio" name="active_payment_gateway_id" value="{{ $gateway['id'] ?? '' }}" {{ ($activePaymentGatewayId ?? null) === ($gateway['id'] ?? null) ? 'checked' : '' }} class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Set as Primary Gateway</span>
            </label>
            <label class="inline-flex items-center gap-2 text-xs font-bold text-slate-700 cursor-pointer bg-white px-3 py-1.5 rounded-xl border border-slate-200 shadow-2xs hover:border-slate-300 transition-colors">
                <input type="checkbox" name="payment_gateways[{{ $index }}][enabled]" value="1" {{ ($gateway['enabled'] ?? '1') === '1' ? 'checked' : '' }} class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                <span>Enabled</span>
            </label>
        </div>
        <button type="button" onclick="removePaymentGateway(this)" class="inline-flex items-center gap-1.5 text-xs font-bold text-rose-600 hover:text-white bg-rose-50 hover:bg-rose-600 border border-rose-200 hover:border-rose-600 px-3 py-1.5 rounded-xl transition-all shadow-xs self-start sm:self-auto">
            <i class="ph-bold ph-trash"></i>
            <span>Remove Gateway</span>
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Gateway Display Name</label>
            <input type="text" name="payment_gateways[{{ $index }}][name]" value="{{ $gateway['name'] ?? '' }}" placeholder="Razorpay / UPI / Bank Transfer"
                   class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Gateway Processing Type</label>
            <select name="payment_gateways[{{ $index }}][type]"
                    class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
                <option value="razorpay" {{ ($gateway['type'] ?? '') === 'razorpay' ? 'selected' : '' }}>Razorpay Automated (Cards, UPI, Netbanking)</option>
                <option value="manual" {{ ($gateway['type'] ?? 'manual') === 'manual' ? 'selected' : '' }}>Manual Verification (Direct UPI / QR / Bank)</option>
                <option value="external" {{ ($gateway['type'] ?? '') === 'external' ? 'selected' : '' }}>External Payment Link</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Account / Merchant Name</label>
            <input type="text" name="payment_gateways[{{ $index }}][account_name]" value="{{ $gateway['account_name'] ?? '' }}" placeholder="UnlockRentals Pvt Ltd"
                   class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Gateway Identifier (UPI ID / Acc No)</label>
            <input type="text" name="payment_gateways[{{ $index }}][identifier]" value="{{ $gateway['identifier'] ?? '' }}" placeholder="unlockrentals@upi / 9876543210@paytm"
                   class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all font-mono">
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Payment Link (Optional)</label>
            <input type="url" name="payment_gateways[{{ $index }}][payment_link]" value="{{ $gateway['payment_link'] ?? '' }}" placeholder="https://rzp.io/l/..."
                   class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">QR Image URL (Optional)</label>
            <input type="url" name="payment_gateways[{{ $index }}][qr_url]" value="{{ $gateway['qr_url'] ?? '' }}" placeholder="https://example.com/payment-qr.png"
                   class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Reference Field Prompt</label>
            <input type="text" name="payment_gateways[{{ $index }}][reference_label]" value="{{ $gateway['reference_label'] ?? 'Transaction ID / UTR Number' }}"
                   class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Razorpay Key ID (For Razorpay Mode)</label>
            <input type="text" name="payment_gateways[{{ $index }}][key_id]" value="{{ $gateway['key_id'] ?? '' }}" placeholder="rzp_live_... or rzp_test_..."
                   class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all font-mono">
        </div>
        <div class="md:col-span-2">
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Razorpay Key Secret (For Razorpay Mode)</label>
            <input type="password" name="payment_gateways[{{ $index }}][key_secret]" value="{{ $gateway['key_secret'] ?? '' }}" placeholder="••••••••••••••••"
                   class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all font-mono">
        </div>
        <div class="md:col-span-2">
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Checkout Instructions</label>
            <textarea name="payment_gateways[{{ $index }}][instructions]" rows="2"
                      class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all"
                      placeholder="Explain to users how to complete the payment and where to find the reference ID.">{{ $gateway['instructions'] ?? '' }}</textarea>
        </div>
    </div>
</div>
