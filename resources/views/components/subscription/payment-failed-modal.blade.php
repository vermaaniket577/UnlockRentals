@php
    $paymentFailedReason = $paymentFailedReason
        ?? session('payment_failed_reason')
        ?? (request()->boolean('payment_failed') ? request('reason', 'Payment failed. Please try again or choose another payment method.') : null);
    $retryCheckoutUrl = session('payment_retry_checkout', route('plans.index'));
@endphp
@if($paymentFailedReason)
<div id="payment-failed-modal" class="fixed inset-0 z-[10000] flex items-center justify-center bg-slate-950/70 p-4 backdrop-blur-xl" role="dialog" aria-modal="true" aria-labelledby="payment-failed-title">
    <div class="w-full max-w-md rounded-[2rem] border border-red-100 bg-white p-7 text-center shadow-2xl dark:border-red-500/20 dark:bg-slate-900" style="animation: successScaleIn .36s ease both;">
        <div class="mx-auto grid h-20 w-20 place-items-center rounded-full bg-red-50 text-red-600 dark:bg-red-500/10">
            <i class="ph-bold ph-x-circle text-4xl"></i>
        </div>
        <p class="mt-6 text-xs font-black uppercase tracking-[0.22em] text-red-600">Payment unsuccessful</p>
        <h2 id="payment-failed-title" class="mt-2 text-2xl font-black tracking-tight text-slate-950 dark:text-white">Payment Failed</h2>
        <p class="mt-3 text-sm font-semibold leading-6 text-slate-600 dark:text-slate-300">{{ $paymentFailedReason }}</p>
        <div class="mt-7 grid grid-cols-1 gap-3">
            <a href="{{ $retryCheckoutUrl }}" class="flex items-center justify-center gap-2 rounded-2xl bg-slate-950 px-5 py-3 text-sm font-black text-white shadow-xl transition hover:-translate-y-0.5 hover:bg-blue-700 dark:bg-blue-600">
                <i class="ph-bold ph-arrows-clockwise"></i>
                Try Again
            </a>
            <a href="{{ $retryCheckoutUrl }}#payment-methods" class="flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-black text-slate-700 transition hover:border-blue-200 hover:bg-blue-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:border-blue-500/40">
                <i class="ph-bold ph-credit-card"></i>
                Choose Another Payment Method
            </a>
            <button type="button" class="rounded-2xl px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-slate-400 transition hover:text-slate-600" data-close-payment-failed>
                Back to Plans
            </button>
        </div>
    </div>
</div>
<script>
(() => {
    const modal = document.getElementById('payment-failed-modal');
    document.querySelectorAll('[data-close-payment-failed]').forEach(btn => {
        btn.addEventListener('click', () => {
            modal?.remove();
            window.location.href = @json(route('plans.index'));
        });
    });
})();
</script>
@endif
