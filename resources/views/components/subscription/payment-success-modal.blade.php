@php($subscriptionSuccess = $subscriptionSuccess ?? session('subscription_success'))
@if($subscriptionSuccess)
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
<div id="subscription-success-modal" class="fixed inset-0 z-[10000] flex items-center justify-center overflow-hidden bg-slate-950/70 p-4 backdrop-blur-2xl">
    @for($i = 0; $i < 26; $i++)
        <span class="absolute h-2 w-2 rounded-full bg-gradient-to-r from-blue-400 to-teal-300 opacity-70" style="left: {{ rand(4, 96) }}%; top: {{ rand(8, 92) }}%; animation: checkoutFloat {{ rand(28, 52) / 10 }}s ease-in-out infinite; animation-delay: -{{ rand(0, 40) / 10 }}s;"></span>
    @endfor
    <div class="relative w-full max-w-lg rounded-[2rem] border border-white/30 bg-white/92 p-7 text-center shadow-2xl backdrop-blur-xl dark:border-white/10 dark:bg-slate-900/92" style="animation: successScaleIn .42s ease both;">
        <div class="absolute inset-x-8 top-0 h-px bg-gradient-to-r from-transparent via-blue-400 to-transparent"></div>
        <div class="mx-auto grid h-24 w-24 place-items-center rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 p-1 shadow-2xl shadow-emerald-500/25">
            <div class="grid h-full w-full place-items-center rounded-full bg-white dark:bg-slate-950">
                <svg width="58" height="58" viewBox="0 0 58 58" fill="none" aria-hidden="true">
                    <circle cx="29" cy="29" r="25" stroke="#10b981" stroke-width="4" opacity=".18"/>
                    <path class="premium-success-check" d="M17 30.5L25.2 38.5L42 20" stroke="#059669" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>
        <p class="mt-6 text-xs font-black uppercase tracking-[0.22em] text-emerald-600">Plan Activated</p>
        <h2 class="mt-2 text-3xl font-black tracking-tight text-slate-950 dark:text-white">Congratulations 🎉</h2>
        <p class="mt-3 text-base font-bold text-slate-700 dark:text-slate-200">Your plan has been activated successfully</p>
        <p class="mt-2 text-sm leading-6 text-slate-500 dark:text-slate-400">Premium services started immediately. Enjoy your premium benefits!</p>

        <div class="mt-6 grid grid-cols-2 gap-3 text-left text-sm">
            <div class="rounded-2xl bg-slate-50 p-4 dark:bg-slate-800/80">
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Activated Plan</p>
                <p class="mt-1 font-black text-slate-950 dark:text-white">{{ $subscriptionSuccess['plan'] ?? 'Premium' }}</p>
            </div>
            <div class="rounded-2xl bg-slate-50 p-4 dark:bg-slate-800/80">
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Subscription Expiry</p>
                <p class="mt-1 font-black text-slate-950 dark:text-white">{{ $subscriptionSuccess['expires_at'] ?? '—' }}</p>
            </div>
            <div class="col-span-2 rounded-2xl border border-blue-100 bg-blue-50 p-4 dark:border-blue-500/30 dark:bg-blue-500/10">
                <p class="text-[10px] font-black uppercase tracking-widest text-blue-500">Invoice ID</p>
                <p class="mt-1 font-mono text-sm font-black text-blue-900 dark:text-blue-200">{{ $subscriptionSuccess['invoice_id'] ?? 'Generated' }}</p>
            </div>
        </div>

        <a href="{{ route('dashboard') }}" class="mt-7 flex w-full items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-500 px-5 py-3.5 text-sm font-black text-white shadow-xl shadow-emerald-500/15 transition hover:-translate-y-0.5">
            <i class="ph-bold ph-squares-four"></i>
            Go to Dashboard
        </a>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const duration = 3200;
    const end = Date.now() + duration;
    (function frame() {
        if (typeof confetti !== 'function') return;
        confetti({ particleCount: 4, angle: 60, spread: 55, origin: { x: 0, y: 0.65 }, colors: ['#10B981', '#3B82F6', '#F59E0B'] });
        confetti({ particleCount: 4, angle: 120, spread: 55, origin: { x: 1, y: 0.65 }, colors: ['#10B981', '#3B82F6', '#F59E0B'] });
        if (Date.now() < end) requestAnimationFrame(frame);
    }());
});
</script>
@endif
