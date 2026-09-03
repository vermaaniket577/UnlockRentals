@extends('layouts.admin')

@section('title', 'Manage Plans - Admin')

@section('content')

<section class="py-8 lg:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 mb-1">Manage Plans</h1>
                <p class="text-zinc-500 text-sm">Create and manage subscription passes for Renters (Tenants) and Buyers (Property Purchasers).</p>
            </div>
            <a href="{{ route('admin.plans.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all" title="Create Plan">
                <i class="ph-bold ph-plus"></i>
                <span>Create New Plan</span>
            </a>
        </div>

        {{-- Purpose Filter Tabs --}}
        <div class="flex items-center gap-2 mb-6 border-b border-stone-200 pb-3">
            <a href="{{ route('admin.plans') }}" class="px-4 py-2 text-xs font-bold rounded-xl transition-all {{ empty(request('purpose')) ? 'bg-blue-600 text-white shadow-xs' : 'bg-stone-100 text-zinc-600 hover:bg-stone-200' }}">
                All Plans ({{ \App\Models\Plan::count() }})
            </a>
            <a href="{{ route('admin.plans', ['purpose' => 'rent']) }}" class="px-4 py-2 text-xs font-bold rounded-xl transition-all {{ request('purpose') === 'rent' ? 'bg-blue-600 text-white shadow-xs' : 'bg-stone-100 text-zinc-600 hover:bg-stone-200' }}">
                🏠 Rental Plans (Tenants)
            </a>
            <a href="{{ route('admin.plans', ['purpose' => 'buy']) }}" class="px-4 py-2 text-xs font-bold rounded-xl transition-all {{ request('purpose') === 'buy' ? 'bg-blue-600 text-white shadow-xs' : 'bg-stone-100 text-zinc-600 hover:bg-stone-200' }}">
                🏢 Buyer Plans (Purchasers & Investors)
            </a>
        </div>

        @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-sm font-semibold text-emerald-700 shadow-xs">
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-white border border-stone-200 rounded-2xl shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-stone-200 bg-stone-50/80">
                        <th class="text-left px-6 py-3.5 text-xs font-bold text-zinc-500 uppercase tracking-wider">Plan Name</th>
                        <th class="text-left px-6 py-3.5 text-xs font-bold text-zinc-500 uppercase tracking-wider">Target / Purpose</th>
                        <th class="text-left px-6 py-3.5 text-xs font-bold text-zinc-500 uppercase tracking-wider">Price</th>
                        <th class="text-left px-6 py-3.5 text-xs font-bold text-zinc-500 uppercase tracking-wider">Duration</th>
                        <th class="text-left px-6 py-3.5 text-xs font-bold text-zinc-500 uppercase tracking-wider">Contact Limit</th>
                        <th class="text-left px-6 py-3.5 text-xs font-bold text-zinc-500 uppercase tracking-wider">Status</th>
                        <th class="text-right px-6 py-3.5 text-xs font-bold text-zinc-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($plans as $plan)
                    <tr class="hover:bg-stone-50/60 transition-colors">
                        <td class="px-6 py-4">
                            <p class="font-bold text-zinc-900">{{ $plan->name }}</p>
                            <p class="text-xs text-zinc-500 mt-0.5">{{ Str::limit($plan->description, 50) }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @if($plan->purpose === 'buy' || $plan->purpose === 'sale')
                                <span class="px-2.5 py-1 bg-amber-100 text-amber-800 text-[10px] font-black rounded-lg uppercase tracking-wider inline-flex items-center gap-1">
                                    <i class="ph-bold ph-buildings"></i> FOR BUYERS
                                </span>
                            @elseif($plan->purpose === 'both')
                                <span class="px-2.5 py-1 bg-purple-100 text-purple-800 text-[10px] font-black rounded-lg uppercase tracking-wider inline-flex items-center gap-1">
                                    <i class="ph-bold ph-arrows-clockwise"></i> ALL PURPOSES
                                </span>
                            @else
                                <span class="px-2.5 py-1 bg-blue-100 text-blue-800 text-[10px] font-black rounded-lg uppercase tracking-wider inline-flex items-center gap-1">
                                    <i class="ph-bold ph-house-line"></i> FOR RENT
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-bold text-zinc-900">{{ $plan->formatted_price }}</td>
                        <td class="px-6 py-4 text-zinc-700 font-medium">{{ $plan->duration_days }} days</td>
                        <td class="px-6 py-4 text-zinc-700 font-medium">{{ $plan->contact_limit }} contacts</td>
                        <td class="px-6 py-4">
                            @if($plan->is_active)
                                <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 text-[10px] font-black rounded-lg uppercase tracking-wider">Active</span>
                            @else
                                <span class="px-2.5 py-1 bg-zinc-100 text-zinc-500 text-[10px] font-black rounded-lg uppercase tracking-wider">Inactive</span>
                            @endif
                            @if($plan->is_private)
                                <span class="ml-1 px-2.5 py-1 bg-indigo-100 text-indigo-800 text-[10px] font-black rounded-lg uppercase tracking-wider inline-block mt-1">Private Offer</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.plans.edit', $plan) }}" class="px-3.5 py-1.5 bg-blue-50 text-blue-600 font-bold text-xs rounded-lg hover:bg-blue-100 transition-all" title="Edit Plan">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.plans.destroy', $plan) }}" onsubmit="return confirm('Delete this plan?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-3.5 py-1.5 bg-red-50 text-red-600 font-bold text-xs rounded-lg hover:bg-red-100 transition-all cursor-pointer">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-zinc-400 font-medium">No plans found for this selection.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold text-zinc-500 hover:text-blue-600" title="← Back to Dashboard">← Back to Dashboard</a>
        </div>
    </div>
</section>

@endsection
