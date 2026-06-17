@php($subscriptionSuccess = $subscriptionSuccess ?? session('subscription_success'))
@if($subscriptionSuccess)
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
<div id="subscription-success-modal" class="fixed inset-0 z-[10000] flex items-center justify-center overflow-hidden p-4" style="background: rgba(241, 245, 249, 0.85); backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px);">

    {{-- Floating celebration particles --}}
    @for($i = 0; $i < 18; $i++)
        <span class="absolute rounded-full opacity-40" style="
            width: {{ rand(4, 10) }}px;
            height: {{ rand(4, 10) }}px;
            left: {{ rand(5, 95) }}%;
            top: {{ rand(5, 95) }}%;
            background: {{ ['#10b981', '#3b82f6', '#f59e0b', '#8b5cf6', '#06b6d4'][rand(0, 4)] }};
            animation: successFloat {{ rand(30, 55) / 10 }}s ease-in-out infinite;
            animation-delay: -{{ rand(0, 40) / 10 }}s;
        "></span>
    @endfor

    {{-- Modal Card --}}
    <div class="relative w-full max-w-md overflow-hidden rounded-3xl bg-white p-8 text-center shadow-2xl" style="animation: successScaleIn .4s cubic-bezier(.21,1.02,.73,1) both; box-shadow: 0 25px 80px rgba(15, 23, 42, 0.12), 0 0 0 1px rgba(15, 23, 42, 0.04);">

        {{-- Top accent gradient line --}}
        <div class="absolute inset-x-0 top-0 h-1" style="background: linear-gradient(90deg, #10b981, #3b82f6, #8b5cf6);"></div>

        {{-- Subtle background pattern --}}
        <div class="pointer-events-none absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(circle at 1px 1px, #334155 1px, transparent 0); background-size: 24px 24px;"></div>

        {{-- Success Icon --}}
        <div class="relative mx-auto h-20 w-20">
            <div class="absolute inset-0 rounded-full opacity-20" style="background: radial-gradient(circle, #10b981, transparent 70%); animation: successPulse 2s ease-in-out infinite;"></div>
            <div class="relative grid h-20 w-20 place-items-center rounded-full border-2 border-emerald-200 bg-gradient-to-br from-emerald-50 to-white shadow-lg shadow-emerald-100">
                <svg width="40" height="40" viewBox="0 0 40 40" fill="none" aria-hidden="true">
                    <circle cx="20" cy="20" r="17" stroke="#10b981" stroke-width="2.5" opacity=".2"/>
                    <path class="premium-success-check" d="M12 21L17.5 26.5L29 14.5" stroke="#059669" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round" style="stroke-dasharray: 30; stroke-dashoffset: 30; animation: successDraw .6s ease .3s forwards;"/>
                </svg>
            </div>
        </div>

        {{-- Text Content --}}
        <p class="mt-5 text-[10px] font-extrabold uppercase tracking-[0.25em] text-emerald-600">Plan Activated</p>
        <h2 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-900">Congratulations 🎉</h2>
        <p class="mt-2 text-sm font-semibold text-slate-600">Your plan has been activated successfully</p>
        <p class="mt-1 text-xs leading-5 text-slate-400">Premium services started immediately. Enjoy your benefits!</p>

        {{-- Plan Details Grid --}}
        <div class="mt-6 grid grid-cols-2 gap-3 text-left text-sm">
            <div class="rounded-2xl border border-slate-100 bg-slate-50/80 p-4 transition-colors hover:border-emerald-200 hover:bg-emerald-50/50">
                <p class="text-[9px] font-extrabold uppercase tracking-[0.2em] text-slate-400">Activated Plan</p>
                <p class="mt-1.5 text-base font-extrabold text-slate-900">{{ $subscriptionSuccess['plan'] ?? 'Premium' }}</p>
            </div>
            <div class="rounded-2xl border border-slate-100 bg-slate-50/80 p-4 transition-colors hover:border-blue-200 hover:bg-blue-50/50">
                <p class="text-[9px] font-extrabold uppercase tracking-[0.2em] text-slate-400">Subscription Expiry</p>
                <p class="mt-1.5 text-base font-extrabold text-slate-900">{{ $subscriptionSuccess['expires_at'] ?? '—' }}</p>
            </div>
            <div class="col-span-2 rounded-2xl border border-blue-100 bg-gradient-to-r from-blue-50/80 to-indigo-50/60 p-4">
                <p class="text-[9px] font-extrabold uppercase tracking-[0.2em] text-blue-500">Invoice ID</p>
                <p class="mt-1.5 font-mono text-sm font-bold text-blue-800">{{ $subscriptionSuccess['invoice_id'] ?? 'Generated' }}</p>
            </div>
        </div>

        {{-- CTA Button --}}
        <a href="{{ route('dashboard') }}" class="mt-6 flex w-full items-center justify-center gap-2.5 rounded-2xl px-5 py-3.5 text-sm font-extrabold text-white shadow-lg transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xl" style="background: linear-gradient(135deg, #10b981 0%, #059669 50%, #047857 100%); box-shadow: 0 8px 30px rgba(16, 185, 129, 0.25);">
            <i class="ph-bold ph-squares-four text-base"></i>
            Go to Dashboard
        </a>

        {{-- Security Badge --}}
        <div class="mt-4 flex items-center justify-center gap-2 text-[10px] font-bold text-slate-400">
            <i class="ph ph-shield-check text-emerald-500"></i>
            <span>Secured by UnlockRentals</span>
        </div>
    </div>
</div>

<style>
    @keyframes successScaleIn {
        0% { opacity: 0; transform: scale(.88) translateY(20px); }
        100% { opacity: 1; transform: scale(1) translateY(0); }
    }
    @keyframes successFloat {
        0%, 100% { transform: translateY(0) scale(1); opacity: .2; }
        50% { transform: translateY(-20px) scale(1.3); opacity: .5; }
    }
    @keyframes successPulse {
        0%, 100% { transform: scale(1); opacity: .2; }
        50% { transform: scale(1.5); opacity: .08; }
    }
    @keyframes successDraw {
        to { stroke-dashoffset: 0; }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const duration = 3200;
    const end = Date.now() + duration;
    (function frame() {
        if (typeof confetti !== 'function') return;
        confetti({ particleCount: 3, angle: 60, spread: 55, origin: { x: 0, y: 0.6 }, colors: ['#10B981', '#3B82F6', '#F59E0B', '#8B5CF6'] });
        confetti({ particleCount: 3, angle: 120, spread: 55, origin: { x: 1, y: 0.6 }, colors: ['#10B981', '#3B82F6', '#F59E0B', '#8B5CF6'] });
        if (Date.now() < end) requestAnimationFrame(frame);
    }());
});
</script>
@endif
