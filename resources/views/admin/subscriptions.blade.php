@extends('layouts.app')

@section('title', 'Manage Subscriptions - Admin')

@section('content')

<section class="py-8 lg:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-medium text-zinc-900 mb-1">User Subscriptions</h1>
                <p class="text-zinc-500 text-sm">Review and manage user plan purchases.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.subscriptions', ['status' => 'pending']) }}" class="px-3 py-1.5 text-xs font-semibold rounded-sm {{ request('status') === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-stone-100 text-zinc-500' }}">
                    Pending
                </a>
                <a href="{{ route('admin.subscriptions', ['status' => 'approved']) }}" class="px-3 py-1.5 text-xs font-semibold rounded-sm {{ request('status') === 'approved' ? 'bg-emerald-100 text-emerald-700' : 'bg-stone-100 text-zinc-500' }}">
                    Approved
                </a>
                <a href="{{ route('admin.subscriptions') }}" class="px-3 py-1.5 text-xs font-semibold rounded-sm {{ !request('status') ? 'bg-[#2563EB]/10 text-[#2563EB]' : 'bg-stone-100 text-zinc-500' }}">
                    All
                </a>
            </div>
        </div>

        @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-sm text-sm text-emerald-700">
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-stone-50 border border-stone-200/50 rounded-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-stone-200/50 bg-stone-100/50">
                        <th class="text-left px-6 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wider">User</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Plan</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Contacts Used</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Payment Ref</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Status</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Requested</th>
                        <th class="text-right px-6 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200/50">
                    @forelse($subscriptions as $sub)
                    <tr class="hover:bg-stone-100/30 transition-colors cursor-pointer" onclick="window.location.href='{{ route('admin.users.activity', $sub->user) }}'">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-stone-200 flex items-center justify-center text-zinc-500 font-bold text-xs">
                                    {{ strtoupper(substr($sub->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-zinc-900">{{ $sub->user->name }}</p>
                                    <p class="text-[11px] text-zinc-500">{{ $sub->user->email }}</p>
                                    <p class="text-[10px] text-[#2563EB] font-bold">{{ $sub->user->phone ?? '+91 7974164274' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-zinc-900">{{ $sub->plan->name }}</p>
                            <p class="text-xs text-zinc-500">{{ $sub->plan->formatted_price }} / {{ $sub->plan->duration_days }} days</p>
                        </td>
                        <td class="px-6 py-4 text-zinc-700">
                            <div class="flex items-center gap-2">
                                <span class="font-bold">{{ $sub->contact_views_count ?? $sub->contacts_used }}</span>
                                <span class="text-xs text-stone-400">/ {{ $sub->plan->contact_limit }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($sub->payment_reference)
                                <p class="text-xs font-mono font-medium text-zinc-900 bg-stone-100 px-2 py-1 rounded-sm inline-block">{{ $sub->payment_reference }}</p>
                            @else
                                <p class="text-xs text-zinc-400">Not submitted</p>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($sub->status === 'approved')
                                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 text-[10px] font-bold uppercase tracking-tight rounded-sm">Approved</span>
                                @if($sub->expires_at && $sub->expires_at->isPast())
                                    <span class="px-2 py-0.5 bg-red-100 text-red-600 text-[10px] font-bold uppercase tracking-tight rounded-sm ml-1">Expired</span>
                                @endif
                            @elseif($sub->status === 'pending')
                                <span class="px-2 py-0.5 bg-amber-100 text-amber-700 text-[10px] font-bold uppercase tracking-tight rounded-sm">Pending</span>
                            @elseif($sub->status === 'rejected')
                                <span class="px-2 py-0.5 bg-red-50 text-red-600 text-[10px] font-bold uppercase tracking-tight rounded-sm">Rejected</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-xs text-zinc-500">
                            <span class="block">{{ $sub->created_at->format('d M, Y') }}</span>
                            <span class="text-[10px] text-stone-400">{{ $sub->created_at->diffForHumans() }}</span>
                        </td>
                        <td class="px-6 py-4 text-right" onclick="event.stopPropagation()">
                            <div class="flex items-center justify-end gap-2">
                                @if($sub->status === 'pending')
                                    <form method="POST" action="{{ route('admin.subscriptions.approve', $sub) }}">
                                        @csrf
                                        <button type="submit" class="p-2 bg-emerald-500/10 text-emerald-600 rounded-sm hover:bg-emerald-500 hover:text-white transition-all border border-emerald-500/20 shadow-sm" title="Approve">
                                            <i class="ph ph-check-circle text-lg"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.subscriptions.reject', $sub) }}">
                                        @csrf
                                        <button type="submit" class="p-2 bg-red-500/10 text-red-500 rounded-sm hover:bg-red-500 hover:text-white transition-all border border-red-500/20 shadow-sm" title="Reject">
                                            <i class="ph ph-x-circle text-lg"></i>
                                        </button>
                                    </form>
                                @elseif($sub->status === 'approved' && $sub->isActive())
                                    {{-- Update Plan Dropdown Simplified --}}
                                    <div class="relative group/menu inline-block">
                                        <button class="p-2 bg-[#2563EB]/10 text-[#2563EB] rounded-sm hover:bg-[#2563EB] hover:text-white transition-all border border-blue-500/20" title="Manage Plan">
                                            <i class="ph ph-arrows-left-right text-lg"></i>
                                        </button>
                                        <div class="hidden group-hover/menu:block absolute right-0 mt-2 w-48 bg-white border border-stone-200 rounded-sm shadow-xl z-50 text-left p-2">
                                            <p class="text-[10px] font-bold text-stone-400 uppercase p-2 border-bottom">Change Plan</p>
                                            @foreach($plans as $plan)
                                                @if($plan->id !== $sub->plan_id)
                                                    <form action="{{ route('admin.subscriptions.update-plan', $sub) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                                                        <button type="submit" class="w-full text-left px-3 py-2 text-xs hover:bg-stone-50 text-zinc-700 rounded-sm">
                                                            To {{ $plan->name }}
                                                        </button>
                                                    </form>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>

                                    <form method="POST" action="{{ route('admin.subscriptions.cancel', $sub) }}" onsubmit="return confirm('Are you sure you want to cancel this active plan?')">
                                        @csrf
                                        <button type="submit" class="p-2 bg-stone-100 text-zinc-400 rounded-sm hover:bg-red-500 hover:text-white transition-all border border-stone-200" title="Cancel Plan">
                                            <i class="ph ph-prohibit text-lg"></i>
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('admin.users.activity', $sub->user) }}" class="p-2 bg-stone-50 text-zinc-600 rounded-sm hover:bg-zinc-800 hover:text-white transition-all border border-stone-200" title="View Activity">
                                    <i class="ph ph-eye text-lg"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-zinc-400">No subscriptions found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $subscriptions->links() }}
        </div>

        <div class="mt-4">
            <a href="{{ route('admin.dashboard') }}" class="text-sm text-zinc-500 hover:text-[#2563EB]">← Back to Dashboard</a>
        </div>
    </div>
</section>

@endsection
