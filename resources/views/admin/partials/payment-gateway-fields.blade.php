<div class="payment-gateway-item border border-stone-200 rounded-sm p-5 bg-stone-50/50" data-index="{{ $index }}">
    <input type="hidden" name="payment_gateways[{{ $index }}][id]" value="{{ $gateway['id'] ?? '' }}">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-5">
        <div class="flex flex-wrap items-center gap-5">
            <label class="inline-flex items-center gap-2 text-xs font-bold text-zinc-700 uppercase tracking-wider">
                <input type="radio" name="active_payment_gateway_id" value="{{ $gateway['id'] ?? '' }}" {{ ($activePaymentGatewayId ?? null) === ($gateway['id'] ?? null) ? 'checked' : '' }}>
                Active
            </label>
            <label class="inline-flex items-center gap-2 text-xs font-bold text-zinc-700 uppercase tracking-wider">
                <input type="checkbox" name="payment_gateways[{{ $index }}][enabled]" value="1" {{ ($gateway['enabled'] ?? '1') === '1' ? 'checked' : '' }}>
                Enabled
            </label>
        </div>
        <button type="button" onclick="removePaymentGateway(this)" class="text-xs font-bold text-red-500 hover:text-red-700 uppercase tracking-wider">Remove</button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Gateway Name</label>
            <input type="text" name="payment_gateways[{{ $index }}][name]" value="{{ $gateway['name'] ?? '' }}" placeholder="Razorpay / UPI / Bank Transfer" class="w-full px-4 py-2 bg-white border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
        </div>
        <div>
            <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Gateway Type</label>
            <select name="payment_gateways[{{ $index }}][type]" class="w-full px-4 py-2 bg-white border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
                <option value="razorpay" {{ ($gateway['type'] ?? '') === 'razorpay' ? 'selected' : '' }}>Razorpay Automated</option>
                <option value="manual" {{ ($gateway['type'] ?? 'manual') === 'manual' ? 'selected' : '' }}>Manual Verification</option>
                <option value="external" {{ ($gateway['type'] ?? '') === 'external' ? 'selected' : '' }}>External Payment Link</option>
            </select>
        </div>
        <div>
            <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Account / Merchant Name</label>
            <input type="text" name="payment_gateways[{{ $index }}][account_name]" value="{{ $gateway['account_name'] ?? '' }}" placeholder="UnlockRentals Pvt Ltd" class="w-full px-4 py-2 bg-white border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
        </div>
        <div>
            <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Gateway Identifier</label>
            <input type="text" name="payment_gateways[{{ $index }}][identifier]" value="{{ $gateway['identifier'] ?? '' }}" placeholder="UPI ID / Account No / Wallet ID" class="w-full px-4 py-2 bg-white border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
        </div>
        <div>
            <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Payment Link</label>
            <input type="url" name="payment_gateways[{{ $index }}][payment_link]" value="{{ $gateway['payment_link'] ?? '' }}" placeholder="https://..." class="w-full px-4 py-2 bg-white border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
        </div>
        <div>
            <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">QR Image URL</label>
            <input type="url" name="payment_gateways[{{ $index }}][qr_url]" value="{{ $gateway['qr_url'] ?? '' }}" placeholder="https://example.com/payment-qr.png" class="w-full px-4 py-2 bg-white border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
        </div>
        <div>
            <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Reference Field Label</label>
            <input type="text" name="payment_gateways[{{ $index }}][reference_label]" value="{{ $gateway['reference_label'] ?? 'Transaction ID / UTR Number' }}" class="w-full px-4 py-2 bg-white border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
        </div>
        <div>
            <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Razorpay Key ID</label>
            <input type="text" name="payment_gateways[{{ $index }}][key_id]" value="{{ $gateway['key_id'] ?? '' }}" placeholder="rzp_test_..." class="w-full px-4 py-2 bg-white border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
        </div>
        <div>
            <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Razorpay Key Secret</label>
            <input type="password" name="payment_gateways[{{ $index }}][key_secret]" value="{{ $gateway['key_secret'] ?? '' }}" placeholder="Only needed for Razorpay" class="w-full px-4 py-2 bg-white border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]">
        </div>
        <div class="md:col-span-2">
            <label class="block text-[11px] font-bold text-zinc-400 uppercase tracking-[0.15em] mb-2">Checkout Instructions</label>
            <textarea name="payment_gateways[{{ $index }}][instructions]" rows="3" class="w-full px-4 py-2 bg-white border border-stone-200 rounded-sm text-zinc-900 text-sm focus:outline-none focus:border-[#2563EB]" placeholder="Tell users how to pay and what reference to submit.">{{ $gateway['instructions'] ?? '' }}</textarea>
        </div>
    </div>
</div>
