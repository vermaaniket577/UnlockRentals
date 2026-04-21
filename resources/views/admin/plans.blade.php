@extends('layouts.app')

@section('title', 'Manage Plans - Admin')

@section('content')

<section class="py-8 lg:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-medium text-zinc-900 mb-1">Manage Plans</h1>
                <p class="text-zinc-500 text-sm">Create and manage subscription plans for users.</p>
            </div>
            <a href="{{ route('admin.plans.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#c9a050] hover:bg-[#b08d42] text-white text-sm font-semibold rounded-sm transition-all">
                <i class="ph ph-plus"></i> Create Plan
            </a>
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
                        <th class="text-left px-6 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Plan</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Price</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Duration</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Contact Limit</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Status</th>
                        <th class="text-right px-6 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200/50">
                    @forelse($plans as $plan)
                    <tr class="hover:bg-stone-50/50">
                        <td class="px-6 py-4">
                            <p class="font-semibold text-zinc-900">{{ $plan->name }}</p>
                            <p class="text-xs text-zinc-500 mt-0.5">{{ Str::limit($plan->description, 50) }}</p>
                        </td>
                        <td class="px-6 py-4 font-semibold text-zinc-900">{{ $plan->formatted_price }}</td>
                        <td class="px-6 py-4 text-zinc-700">{{ $plan->duration_days }} days</td>
                        <td class="px-6 py-4 text-zinc-700">{{ $plan->contact_limit }} contacts</td>
                        <td class="px-6 py-4">
                            @if($plan->is_active)
                                <span class="px-2 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full">Active</span>
                            @else
                                <span class="px-2 py-1 bg-zinc-100 text-zinc-500 text-xs font-bold rounded-full">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.plans.edit', $plan) }}" class="px-3 py-1.5 bg-[#2563EB]/10 text-[#2563EB] text-xs font-semibold rounded-sm hover:bg-[#2563EB]/20 transition-all">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.plans.destroy', $plan) }}" onsubmit="return confirm('Delete this plan?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 bg-red-500/10 text-red-500 text-xs font-semibold rounded-sm hover:bg-red-500/20 transition-all">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-zinc-400">No plans created yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            <a href="{{ route('admin.dashboard') }}" class="text-sm text-zinc-500 hover:text-[#2563EB]">← Back to Dashboard</a>
        </div>
    </div>
</section>

@endsection
