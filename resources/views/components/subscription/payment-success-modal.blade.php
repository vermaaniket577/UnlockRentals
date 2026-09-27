@php
    $subscriptionSuccess = $subscriptionSuccess ?? session('subscription_success');
    
    // Auto-resolve recent active plan if arriving via payment_success query param
    if (!$subscriptionSuccess && auth()->check() && request('payment_success')) {
        try {
            $recentUserPlan = \App\Models\UserPlan::where('user_id', auth()->id())
                ->where('status', 'approved')
                ->where('created_at', '>=', now()->subMinutes(45))
                ->with('plan')
                ->latest()
                ->first();

            if ($recentUserPlan) {
                $planObj = $recentUserPlan->plan;
                $subscriptionSuccess = [
                    'plan' => $planObj->name ?? 'Direct Contact Pass',
                    'plan_name' => $planObj->name ?? 'Direct Contact Pass',
                    'expires_at' => $recentUserPlan->expires_at ? $recentUserPlan->expires_at->format('d M Y') : 'Active',
                    'invoice_id' => $recentUserPlan->invoice_id ?? ('UR-' . $recentUserPlan->id),
                    'contact_limit' => $recentUserPlan->contact_limit ?? ($planObj->contact_limit ?? 30),
                    'duration_days' => $recentUserPlan->duration_days ?? ($planObj->duration_days ?? 60),
                    'amount' => $recentUserPlan->amount ?? ($planObj->price ?? 1),
                ];
            }
        } catch (\Throwable $e) {}
    }
@endphp

<div id="subscription-success-modal-root">
@if($subscriptionSuccess)
    <div id="subscription-success-modal" class="fixed inset-0 z-[10000] flex items-center justify-center overflow-y-auto p-4 sm:p-6" style="background: rgba(15, 23, 42, 0.78); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);">
        {{-- Floating Celebration Sparkles --}}
        <div class="pointer-events-none absolute inset-0 overflow-hidden" aria-hidden="true">
            @for($i = 0; $i < 16; $i++)
                <span class="absolute rounded-full opacity-40" style="
                    width: {{ rand(4, 9) }}px;
                    height: {{ rand(4, 9) }}px;
                    left: {{ rand(5, 95) }}%;
                    top: {{ rand(5, 95) }}%;
                    background: {{ ['#10b981', '#3b82f6', '#f59e0b', '#8b5cf6', '#06b6d4'][rand(0, 4)] }};
                    animation: urSuccessFloat {{ rand(30, 50) / 10 }}s ease-in-out infinite;
                    animation-delay: -{{ rand(0, 35) / 10 }}s;
                "></span>
            @endfor
        </div>

        {{-- Modal Card --}}
        <div class="relative w-full max-w-md overflow-hidden rounded-3xl bg-white dark:bg-slate-900 p-6 sm:p-8 text-center shadow-2xl border border-slate-200/80 dark:border-slate-800" style="animation: urSuccessScaleIn .45s cubic-bezier(.16,1,.3,1) both; box-shadow: 0 25px 80px rgba(15, 23, 42, 0.25), 0 0 0 1px rgba(255, 255, 255, 0.08);">
            
            {{-- Top Accent Gradient Bar --}}
            <div class="absolute inset-x-0 top-0 h-1.5" style="background: linear-gradient(90deg, #10b981 0%, #06b6d4 50%, #3b82f6 100%);"></div>

            {{-- Close Button --}}
            <button type="button" onclick="closeSubscriptionSuccessModal()" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white flex items-center justify-center transition-all cursor-pointer z-20" aria-label="Close modal">
                <i class="ph-bold ph-x text-sm"></i>
            </button>

            {{-- Animated Success Badge --}}
            <div class="relative mx-auto h-20 w-20 mt-1">
                <div class="absolute inset-0 rounded-full opacity-25" style="background: radial-gradient(circle, #10b981, transparent 70%); animation: urSuccessPulse 2s ease-in-out infinite;"></div>
                <div class="relative grid h-20 w-20 place-items-center rounded-full border-2 border-emerald-400/40 bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-950/60 dark:to-emerald-900/40 shadow-lg shadow-emerald-500/20">
                    <svg width="44" height="44" viewBox="0 0 44 44" fill="none" aria-hidden="true">
                        <circle cx="22" cy="22" r="18" stroke="#10b981" stroke-width="2.5" opacity=".25"/>
                        <path d="M13 23L19.5 29.5L31 16" stroke="#059669" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round" style="stroke-dasharray: 36; stroke-dashoffset: 36; animation: urSuccessDraw .65s ease .25s forwards;"/>
                    </svg>
                </div>
            </div>

            {{-- Eyebrow & Congratulations Header --}}
            <div class="mt-4">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 text-[11px] font-bold uppercase tracking-wider border border-emerald-200/80 dark:border-emerald-800/60">
                    <i class="ph-fill ph-seal-check text-emerald-500"></i> Plan Activated Successfully
                </span>
                <h2 class="mt-2.5 text-2xl sm:text-3xl font-black tracking-tight text-slate-900 dark:text-white">
                    Congratulations! 🎉
                </h2>
                <p class="mt-1.5 text-xs sm:text-sm text-slate-600 dark:text-slate-300 font-medium">
                    Your <strong class="font-bold text-slate-900 dark:text-white">{{ $subscriptionSuccess['plan'] ?? 'Direct Pass' }}</strong> is now active.
                </p>
                <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5">
                    Direct owner contact details and WhatsApp unlocks are enabled immediately.
                </p>
            </div>

            {{-- Plan Highlights Grid --}}
            <div class="mt-5 grid grid-cols-2 gap-2.5 text-left text-xs">
                <div class="rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/80 dark:bg-slate-800/50 p-3.5">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 flex items-center gap-1">
                        <i class="ph-bold ph-phone-call text-emerald-500"></i> Contact Unlocks
                    </p>
                    <p class="mt-1 text-sm sm:text-base font-extrabold text-slate-900 dark:text-white">
                        {{ $subscriptionSuccess['contact_limit'] ?? 30 }} Direct
                    </p>
                </div>
                <div class="rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/80 dark:bg-slate-800/50 p-3.5">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 flex items-center gap-1">
                        <i class="ph-bold ph-calendar-check text-blue-500"></i> Validity Period
                    </p>
                    <p class="mt-1 text-sm sm:text-base font-extrabold text-slate-900 dark:text-white">
                        {{ $subscriptionSuccess['expires_at'] ?? '60 Days' }}
                    </p>
                </div>
                <div class="col-span-2 rounded-2xl border border-emerald-200/60 dark:border-emerald-900/60 bg-gradient-to-r from-emerald-50/60 to-teal-50/50 dark:from-emerald-950/30 dark:to-teal-950/20 p-3 flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-sm font-bold shrink-0">
                            <i class="ph-bold ph-receipt"></i>
                        </span>
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 block">Invoice Reference</span>
                            <span class="font-mono text-xs font-bold text-slate-800 dark:text-slate-200">{{ $subscriptionSuccess['invoice_id'] ?? 'UR-ACTIVE' }}</span>
                        </div>
                    </div>
                    <span class="px-2.5 py-1 rounded-full bg-emerald-600 text-white text-[10px] font-extrabold uppercase tracking-wide">
                        Verified
                    </span>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="mt-6 flex flex-col gap-2.5">
                <a href="{{ route('properties.index', ['purpose' => 'rent']) }}" onclick="closeSubscriptionSuccessModal()" class="flex w-full items-center justify-center gap-2 rounded-2xl px-5 py-3.5 text-sm sm:text-base font-bold text-white shadow-lg transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xl active:scale-95 cursor-pointer" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%); box-shadow: 0 8px 24px rgba(16, 185, 129, 0.35);" title="Explore Verified Rentals & Contact Owners">
                    <i class="ph-fill ph-lightning text-amber-300 text-lg"></i>
                    <span>Start Exploring Rentals & Owners</span>
                </a>
                
                <div class="flex items-center justify-between gap-2">
                    <a href="{{ route('dashboard') }}" class="flex-1 py-2 text-xs font-semibold text-slate-600 hover:text-blue-600 dark:text-slate-400 dark:hover:text-blue-400 transition" title="Go to Dashboard">
                        <i class="ph-bold ph-squares-four"></i> View My Dashboard
                    </a>
                    <span class="text-slate-300 dark:text-slate-700">·</span>
                    <button type="button" onclick="closeSubscriptionSuccessModal()" class="flex-1 py-2 text-xs font-semibold text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white transition cursor-pointer">
                        Stay on Home Screen
                    </button>
                </div>
            </div>

            {{-- Trust Footnote --}}
            <div class="mt-3 pt-3 border-t border-slate-100 dark:border-slate-800/80 flex items-center justify-center gap-2 text-[11px] font-medium text-slate-400 dark:text-slate-500">
                <i class="ph-fill ph-shield-check text-emerald-500"></i>
                <span>100% Zero Brokerage Guarantee · UnlockRentals</span>
            </div>
        </div>
    </div>
@endif
</div>

{{-- Standalone CSS Animations --}}
<style>
    @keyframes urSuccessScaleIn {
        0% { opacity: 0; transform: scale(.85) translateY(24px); }
        100% { opacity: 1; transform: scale(1) translateY(0); }
    }
    @keyframes urSuccessFloat {
        0%, 100% { transform: translateY(0) scale(1); opacity: .2; }
        50% { transform: translateY(-20px) scale(1.3); opacity: .55; }
    }
    @keyframes urSuccessPulse {
        0%, 100% { transform: scale(1); opacity: .2; }
        50% { transform: scale(1.4); opacity: .08; }
    }
    @keyframes urSuccessDraw {
        to { stroke-dashoffset: 0; }
    }
</style>

{{-- Client-Side Confetti & Fallback Mounting Script --}}
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
<script>
function closeSubscriptionSuccessModal() {
    const modal = document.getElementById('subscription-success-modal');
    if (modal) {
        modal.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
        modal.style.opacity = '0';
        setTimeout(() => modal.remove(), 300);
    }
    try {
        localStorage.removeItem('ur_subscription_success');
        sessionStorage.removeItem('ur_subscription_success');
        localStorage.removeItem('ur_pending_order_id');
        sessionStorage.removeItem('ur_pending_order_id');
    } catch (_) {}

    // Clean payment_success param from URL without reloading
    try {
        if (window.history && window.history.replaceState) {
            const currentUrl = new URL(window.location.href);
            if (currentUrl.searchParams.has('payment_success')) {
                currentUrl.searchParams.delete('payment_success');
                window.history.replaceState({}, document.title, currentUrl.pathname + (currentUrl.search ? currentUrl.search : ''));
            }
        }
    } catch (_) {}
}

document.addEventListener('DOMContentLoaded', () => {
    // 1. Confetti Burst
    const fireCelebrationConfetti = () => {
        if (typeof confetti !== 'function') return;
        try {
            confetti({ particleCount: 50, spread: 60, origin: { x: 0.15, y: 0.6 }, colors: ['#10B981', '#3B82F6', '#F59E0B', '#06B6D4'] });
            confetti({ particleCount: 50, spread: 60, origin: { x: 0.85, y: 0.6 }, colors: ['#10B981', '#3B82F6', '#F59E0B', '#06B6D4'] });
            setTimeout(() => {
                confetti({ particleCount: 80, spread: 100, origin: { x: 0.5, y: 0.45 }, colors: ['#10B981', '#F59E0B', '#3B82F6', '#8B5CF6'] });
            }, 300);
        } catch (_) {}
    };

    const hasServerModal = !!document.getElementById('subscription-success-modal');
    if (hasServerModal) {
        fireCelebrationConfetti();
        // Clean up URL query parameter without refresh
        try {
            if (window.history && window.history.replaceState) {
                const url = new URL(window.location.href);
                if (url.searchParams.has('payment_success')) {
                    url.searchParams.delete('payment_success');
                    window.history.replaceState({}, document.title, url.pathname + (url.search ? url.search : ''));
                }
            }
        } catch (_) {}
        return;
    }

    // 2. Client-side fallback: check if payment completed via localStorage / query param
    let storedSuccess = null;
    try {
        const raw = localStorage.getItem('ur_subscription_success') || sessionStorage.getItem('ur_subscription_success');
        if (raw) storedSuccess = JSON.parse(raw);
    } catch (_) {}

    const hasSuccessParam = window.location.search.includes('payment_success=1');
    if (storedSuccess || hasSuccessParam) {
        const root = document.getElementById('subscription-success-modal-root');
        if (!root) return;

        const planName = storedSuccess?.plan || storedSuccess?.plan_name || 'Direct Contact Pass';
        const contactLimit = storedSuccess?.contact_limit || '30 Direct';
        const expiresAt = storedSuccess?.expires_at || '60 Days';
        const invoiceId = storedSuccess?.invoice_id || 'UR-CONFIRMED';

        root.innerHTML = `
            <div id="subscription-success-modal" class="fixed inset-0 z-[10000] flex items-center justify-center overflow-y-auto p-4 sm:p-6" style="background: rgba(15, 23, 42, 0.78); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);">
                <div class="relative w-full max-w-md overflow-hidden rounded-3xl bg-white dark:bg-slate-900 p-6 sm:p-8 text-center shadow-2xl border border-slate-200/80 dark:border-slate-800" style="animation: urSuccessScaleIn .45s cubic-bezier(.16,1,.3,1) both; box-shadow: 0 25px 80px rgba(15, 23, 42, 0.25);">
                    <div class="absolute inset-x-0 top-0 h-1.5" style="background: linear-gradient(90deg, #10b981 0%, #06b6d4 50%, #3b82f6 100%);"></div>
                    <button type="button" onclick="closeSubscriptionSuccessModal()" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white flex items-center justify-center transition cursor-pointer z-20">
                        <i class="ph-bold ph-x text-sm"></i>
                    </button>
                    <div class="relative mx-auto h-20 w-20 mt-1">
                        <div class="relative grid h-20 w-20 place-items-center rounded-full border-2 border-emerald-400/40 bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-950/60 dark:to-emerald-900/40 shadow-lg shadow-emerald-500/20">
                            <i class="ph-bold ph-check text-emerald-600 text-3xl"></i>
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 text-[11px] font-bold uppercase tracking-wider border border-emerald-200/80 dark:border-emerald-800/60">
                            <i class="ph-fill ph-seal-check text-emerald-500"></i> Plan Activated Successfully
                        </span>
                        <h2 class="mt-2.5 text-2xl sm:text-3xl font-black tracking-tight text-slate-900 dark:text-white">Congratulations! 🎉</h2>
                        <p class="mt-1.5 text-xs sm:text-sm text-slate-600 dark:text-slate-300 font-medium">Your <strong>${planName}</strong> is now active.</p>
                        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5">Direct owner contacts and WhatsApp unlocks are enabled immediately.</p>
                    </div>
                    <div class="mt-5 grid grid-cols-2 gap-2.5 text-left text-xs">
                        <div class="rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/80 dark:bg-slate-800/50 p-3.5">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 flex items-center gap-1"><i class="ph-bold ph-phone-call text-emerald-500"></i> Contact Unlocks</p>
                            <p class="mt-1 text-sm sm:text-base font-extrabold text-slate-900 dark:text-white">${contactLimit}</p>
                        </div>
                        <div class="rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/80 dark:bg-slate-800/50 p-3.5">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 flex items-center gap-1"><i class="ph-bold ph-calendar-check text-blue-500"></i> Validity</p>
                            <p class="mt-1 text-sm sm:text-base font-extrabold text-slate-900 dark:text-white">${expiresAt}</p>
                        </div>
                        <div class="col-span-2 rounded-2xl border border-emerald-200/60 dark:border-emerald-900/60 bg-gradient-to-r from-emerald-50/60 to-teal-50/50 dark:from-emerald-950/30 dark:to-teal-950/20 p-3 flex items-center justify-between text-xs">
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 block">Invoice Reference</span>
                                <span class="font-mono text-xs font-bold text-slate-800 dark:text-slate-200">${invoiceId}</span>
                            </div>
                            <span class="px-2.5 py-1 rounded-full bg-emerald-600 text-white text-[10px] font-extrabold uppercase">Verified</span>
                        </div>
                    </div>
                    <div class="mt-6 flex flex-col gap-2.5">
                        <a href="/properties?purpose=rent" onclick="closeSubscriptionSuccessModal()" class="flex w-full items-center justify-center gap-2 rounded-2xl px-5 py-3.5 text-sm sm:text-base font-bold text-white shadow-lg transition hover:-translate-y-0.5 cursor-pointer" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%);">
                            <i class="ph-fill ph-lightning text-amber-300 text-lg"></i>
                            <span>Start Exploring Rentals & Owners</span>
                        </a>
                        <div class="flex items-center justify-between gap-2">
                            <a href="/dashboard" class="flex-1 py-2 text-xs font-semibold text-slate-600 hover:text-blue-600 transition">View Dashboard</a>
                            <span class="text-slate-300">·</span>
                            <button type="button" onclick="closeSubscriptionSuccessModal()" class="flex-1 py-2 text-xs font-semibold text-slate-500 hover:text-slate-800 transition cursor-pointer">Stay on Home</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        fireCelebrationConfetti();
        try {
            if (window.history && window.history.replaceState) {
                const url = new URL(window.location.href);
                url.searchParams.delete('payment_success');
                window.history.replaceState({}, document.title, url.pathname + (url.search ? url.search : ''));
            }
        } catch (_) {}
    }
});
</script>
