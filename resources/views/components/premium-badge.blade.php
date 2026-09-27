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
    {{-- LinkedIn Style Iconic Gold/Amber Premium Pill Badge --}}
    <span {{ $attributes->merge(['class' => 'ur-premium-badge inline-flex items-center gap-1 font-black uppercase tracking-wider rounded-full shadow-xs whitespace-nowrap']) }}
          style="background: linear-gradient(135deg, #b45309 0%, #d97706 35%, #f59e0b 70%, #fbbf24 100%); color: #ffffff; text-shadow: 0 1px 2px rgba(0,0,0,0.3); border: 1px solid rgba(251, 191, 36, 0.4); {{ $size === 'xs' ? 'padding: 1px 6px; font-size: 8.5px;' : ($size === 'md' ? 'padding: 3px 10px; font-size: 11px;' : 'padding: 2px 8px; font-size: 9.5px;') }}">
        <i class="ph-fill ph-crown text-amber-200" style="{{ $size === 'xs' ? 'font-size: 9px;' : ($size === 'md' ? 'font-size: 13px;' : 'font-size: 11px;') }}"></i>
        <span>{{ $showPlan ? ($planName . ' Member') : 'Premium' }}</span>
    </span>

@elseif($type === 'pill')
    {{-- Refined Minimalist Gold Pill (similar to LinkedIn Pro) --}}
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
    {{-- Full LinkedIn-style Premium Status Card --}}
    @if($isPaid)
        <div {{ $attributes->merge(['class' => 'rounded-2xl p-4 relative overflow-hidden border border-amber-300/80 dark:border-amber-700/70 shadow-lg']) }}
             style="background: linear-gradient(135deg, rgba(254, 243, 199, 0.85) 0%, rgba(253, 230, 138, 0.5) 50%, rgba(245, 158, 11, 0.15) 100%);">
            {{-- Background decorative watermark --}}
            <i class="ph-fill ph-crown absolute -right-3 -bottom-3 text-amber-500/10 pointer-events-none" style="font-size: 80px;"></i>
            
            <div class="flex items-center justify-between mb-2 relative z-10">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full font-black text-[10px] uppercase tracking-wider text-white shadow-xs"
                      style="background: linear-gradient(135deg, #b45309 0%, #d97706 40%, #f59e0b 100%);">
                    <i class="ph-fill ph-crown text-amber-200"></i>
                    <span>Premium Member</span>
                </span>
                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/60 px-2 py-0.5 rounded-full border border-emerald-200 dark:border-emerald-800">
                    <i class="ph-bold ph-check-circle"></i> Active
                </span>
            </div>

            <div class="relative z-10">
                <h4 class="text-sm font-black text-slate-900 dark:text-white flex items-center gap-1.5">
                    {{ $planName }}
                </h4>
                <p class="text-xs text-slate-600 dark:text-slate-300 mt-0.5 font-medium">
                    <strong class="text-amber-800 dark:text-amber-300 font-bold">{{ $remaining }}</strong> contact views remaining
                    @if($expiresAt)
                        · Valid till {{ $expiresAt }}
                    @endif
                </p>
            </div>
        </div>
    @else
        <div {{ $attributes->merge(['class' => 'rounded-2xl p-4 border border-dashed border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/60']) }}>
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Account Status</span>
                    <p class="text-xs font-bold text-slate-700 dark:text-slate-300">Free Member</p>
                </div>
                <a href="{{ route('plans.index') }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-gradient-to-r from-amber-500 to-yellow-500 text-slate-950 font-black text-xs shadow-xs hover:brightness-105 active:scale-95 transition-all">
                    <i class="ph-fill ph-crown text-xs"></i> Upgrade to Pro
                </a>
            </div>
        </div>
    @endif
@endif
