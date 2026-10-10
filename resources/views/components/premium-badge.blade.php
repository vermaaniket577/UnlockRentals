@props([
    'user' => null,
    'type' => 'badge', // 'badge', 'pill', 'icon', 'card'
    'plan' => null,
    'size' => 'sm',   // 'xs', 'sm', 'md', 'lg'
    'showPlan' => false,
])

@php
    $targetUser = $user ?? auth()->user();
    if (!$targetUser) return;

    $activePlan = $plan ?? $targetUser->activePlan();
    $isPaid = $activePlan !== null;
    if (!$isPaid && $type !== 'card') return;

    $planName = $activePlan?->plan?->name ?? 'Premium';
    $remaining = $activePlan?->remaining_contacts ?? 0;
    $expiresAt = $activePlan?->expires_at ? $activePlan->expires_at->format('d M, Y') : null;
@endphp

@if($type === 'badge')
    {{-- LinkedIn / SaaS Iconic Gold/Amber Premium Pill Badge --}}
    <span {{ $attributes->merge(['class' => 'ur-premium-badge inline-flex items-center gap-1 font-black uppercase tracking-wider rounded-full shadow-xs whitespace-nowrap']) }}
          style="background: linear-gradient(135deg, #d97706 0%, #f59e0b 60%, #fbbf24 100%); color: #ffffff; text-shadow: 0 1px 2px rgba(0,0,0,0.3); border: 1px solid rgba(251, 191, 36, 0.45); {{ $size === 'xs' ? 'padding: 1.5px 6.5px; font-size: 8.5px;' : ($size === 'md' ? 'padding: 3px 10px; font-size: 11px;' : 'padding: 2px 8px; font-size: 9.5px;') }}">
        <i class="ph-fill ph-crown text-amber-100" style="{{ $size === 'xs' ? 'font-size: 9px;' : ($size === 'md' ? 'font-size: 13px;' : 'font-size: 11px;') }}"></i>
        <span>{{ $showPlan ? ($planName . ' Member') : 'Premium' }}</span>
    </span>

@elseif($type === 'pill')
    {{-- Refined Minimalist Gold Pill --}}
    <span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1 rounded-full font-bold uppercase tracking-wider bg-amber-50 dark:bg-amber-950/50 border border-amber-300/80 dark:border-amber-700/60 text-amber-800 dark:text-amber-300 shadow-2xs whitespace-nowrap']) }}
          style="{{ $size === 'xs' ? 'padding: 1px 6px; font-size: 8.5px;' : 'padding: 2px 8px; font-size: 10px;' }}">
        <i class="ph-fill ph-crown text-amber-500"></i>
        <span>{{ $showPlan ? $planName : 'Paid Member' }}</span>
    </span>

@elseif($type === 'icon')
    {{-- Micro Gold Crown Indicator --}}
    <span {{ $attributes->merge(['class' => 'inline-flex items-center justify-center rounded-full bg-gradient-to-tr from-amber-600 to-yellow-400 text-white shadow-xs']) }}
          style="width: 14px; height: 14px; font-size: 9px;" title="Paid Member">
        <i class="ph-fill ph-crown"></i>
    </span>

@elseif($type === 'card')
    {{-- High-End Prestige Subscription Card (Linear / Stripe Style) --}}
    @if($isPaid)
        <div {{ $attributes->merge(['class' => 'rounded-xl p-3.5 relative overflow-hidden transition-all']) }}
             style="background: linear-gradient(145deg, #0f172a 0%, #1e1b4b 60%, #111827 100%); border: 1px solid rgba(245, 158, 11, 0.35); box-shadow: 0 8px 24px -4px rgba(15, 23, 42, 0.35), inset 0 1px 0 rgba(251, 191, 36, 0.25);">
            {{-- Ambient corner gold highlight (clean subtle glow without icon overlap) --}}
            <div style="position: absolute; right: -25px; top: -25px; width: 110px; height: 110px; background: radial-gradient(circle, rgba(245,158,11,0.22) 0%, transparent 70%); pointer-events: none;"></div>

            <div class="flex items-center justify-between mb-2.5 relative z-10">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full font-black text-[10px] uppercase tracking-wider text-amber-200"
                      style="background: rgba(245, 158, 11, 0.15); border: 1px solid rgba(245, 158, 11, 0.35); box-shadow: 0 1px 2px rgba(0,0,0,0.2);">
                    <i class="ph-fill ph-crown text-amber-400 text-xs"></i>
                    <span>Premium Member</span>
                </span>
                <span class="inline-flex items-center gap-1 text-[10.5px] font-bold text-emerald-300 bg-emerald-500/15 px-2 py-0.5 rounded-full border border-emerald-500/30">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Active</span>
                </span>
            </div>

            <div class="relative z-10">
                <div class="flex items-center justify-between gap-2">
                    <h4 class="text-sm font-extrabold text-white tracking-tight">
                        {{ $planName }}
                    </h4>
                    <span class="text-[11px] font-bold text-amber-300 bg-amber-400/10 px-2 py-0.5 rounded-md border border-amber-400/20">
                        {{ $remaining }} unlocks left
                    </span>
                </div>
                @if($expiresAt)
                    <p class="text-[11px] text-slate-400 mt-1.5 flex items-center gap-1 font-medium">
                        <i class="ph ph-calendar text-slate-400"></i>
                        <span>Valid till {{ $expiresAt }}</span>
                    </p>
                @endif
            </div>
        </div>
    @else
        <div {{ $attributes->merge(['class' => 'rounded-xl p-3.5 border border-dashed border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/60']) }}>
            <div class="flex items-center justify-between gap-2">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Plan Tier</span>
                    <p class="text-xs font-bold text-slate-700 dark:text-slate-300">Free Member</p>
                </div>
                <a href="{{ route('plans.index') }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-gradient-to-r from-amber-500 to-yellow-500 text-slate-950 font-black text-xs shadow-xs hover:brightness-105 active:scale-95 transition-all">
                    <i class="ph-fill ph-crown text-xs"></i> Upgrade to Pro
                </a>
            </div>
        </div>
    @endif
@endif
