@extends('layouts.admin')

@section('title', 'Manage Professionals | UnlockRentals CRM')

@section('content')
<div class="p-6 space-y-6">

    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white flex items-center gap-2">
                <i class="ph-bold ph-identification-card text-blue-600"></i>
                All Local Professionals Directory
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">Filter, verify, approve, reject and inspect service provider listings</p>
        </div>

        <a href="{{ route('admin.professionals.dashboard') }}" class="px-3.5 py-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-700 dark:text-slate-200">
            Back to CRM Dashboard
        </a>
    </div>

    {{-- Status Tabs --}}
    <div class="flex items-center gap-2 overflow-x-auto pb-2 text-xs font-bold">
        @foreach(['all' => 'All', 'pending' => 'Pending Approval', 'approved' => 'Approved', 'verified' => 'Verified Badge', 'suspended' => 'Suspended', 'rejected' => 'Rejected'] as $k => $label)
            <a href="{{ route('admin.professionals.index', array_merge(request()->except(['status', 'page']), ($k === 'verified' ? ['verification' => 'verified'] : ($k !== 'all' ? ['status' => $k] : [])))) }}" class="px-3 py-1.5 rounded-xl transition-all whitespace-nowrap {{ (request('verification') === 'verified' && $k === 'verified') || ($status === $k && !request('verification')) ? 'bg-blue-600 text-white' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:bg-slate-50' }}">
                {{ $label }} ({{ $counts[$k] ?? 0 }})
            </a>
        @endforeach
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 text-emerald-800 text-xs font-semibold flex items-center gap-2">
            <i class="ph-fill ph-check-circle text-base text-emerald-600"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Search & Filters Bar --}}
    <form action="{{ route('admin.professionals.index') }}" method="GET" class="bg-white dark:bg-slate-800 p-4 rounded-2xl border border-slate-200/80 dark:border-slate-700 grid grid-cols-1 sm:grid-cols-4 gap-3">
        @if(request('status'))
            <input type="hidden" name="status" value="{{ request('status') }}">
        @endif
        @if(request('verification'))
            <input type="hidden" name="verification" value="{{ request('verification') }}">
        @endif

        <div class="sm:col-span-2">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by Business Name, Provider Name, Phone or Email..." class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
        </div>

        <div>
            <select name="category_id" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex items-center gap-2">
            <input type="text" name="city" value="{{ request('city') }}" placeholder="City..." class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
            <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs whitespace-nowrap">
                Filter
            </button>
        </div>
    </form>

    {{-- Bulk Action & Directory Table --}}
    <form action="{{ route('admin.professionals.bulk-action') }}" method="POST" id="bulk-form" onsubmit="return confirm('Apply bulk action to selected professionals?')">
        @csrf

        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700 shadow-xs overflow-hidden">
            {{-- Bulk Bar --}}
            <div class="p-3.5 bg-slate-50 dark:bg-slate-700/50 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between gap-3 text-xs">
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="select-all" onclick="toggleSelectAll(this)" class="rounded border-slate-300 text-blue-600">
                    <span class="font-bold text-slate-700 dark:text-slate-300">Select All</span>
                </div>

                <div class="flex items-center gap-2">
                    <span class="text-slate-500 text-[11px]">With Selected:</span>
                    <select name="action" required class="text-xs py-1 px-2 rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800">
                        <option value="">Choose Action</option>
                        <option value="approve">Bulk Approve</option>
                        <option value="reject">Bulk Reject</option>
                        <option value="verify">Bulk Verify Badge</option>
                        <option value="suspend">Bulk Suspend</option>
                    </select>
                    <button type="submit" class="px-3 py-1 rounded-lg bg-blue-600 text-white font-bold text-xs">
                        Apply
                    </button>
                </div>
            </div>

            {{-- Table --}}
            @if($professionals->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="bg-slate-50/50 dark:bg-slate-700/30 text-slate-500 border-b border-slate-100 dark:border-slate-700">
                                <th class="p-3 w-8"></th>
                                <th class="p-3">Professional / Business</th>
                                <th class="p-3">Category</th>
                                <th class="p-3">Location</th>
                                <th class="p-3">Status</th>
                                <th class="p-3">Verification</th>
                                <th class="p-3">Stats</th>
                                <th class="p-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                            @foreach($professionals as $p)
                                <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-700/40 transition-colors">
                                    <td class="p-3">
                                        <input type="checkbox" name="ids[]" value="{{ $p->id }}" class="prof-checkbox rounded border-slate-300 text-blue-600">
                                    </td>
                                    <td class="p-3">
                                        <div class="flex items-center gap-2.5">
                                            <img src="{{ $p->profile_photo_url }}" class="w-10 h-10 rounded-xl object-cover border border-slate-200 dark:border-slate-600">
                                            <div>
                                                <a href="{{ route('admin.professionals.show', $p->id) }}" class="font-bold text-slate-900 dark:text-white hover:text-blue-600">
                                                    {{ $p->business_name }}
                                                </a>
                                                <div class="text-[11px] text-slate-400">
                                                    <span>{{ $p->full_name }}</span> • 
                                                    <span>{{ $p->phone }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-3 font-semibold text-slate-700 dark:text-slate-300">
                                        {{ $p->category->name }}
                                    </td>
                                    <td class="p-3 text-slate-600 dark:text-slate-300">
                                        {{ $p->locality ? $p->locality . ', ' : '' }}{{ $p->city }}
                                    </td>
                                    <td class="p-3">
                                        @php
                                            $stPills = [
                                                'approved' => 'bg-emerald-100 text-emerald-800',
                                                'pending' => 'bg-amber-100 text-amber-800',
                                                'suspended' => 'bg-slate-200 text-slate-800',
                                                'rejected' => 'bg-rose-100 text-rose-800',
                                            ];
                                        @endphp
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $stPills[$p->status] ?? 'bg-slate-100 text-slate-800' }}">
                                            {{ $p->status }}
                                        </span>
                                    </td>
                                    <td class="p-3">
                                        @if($p->isVerified())
                                            <span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-800 text-[10px] font-bold flex items-center gap-1 w-max">
                                                <i class="ph-bold ph-check"></i> Verified
                                            </span>
                                        @else
                                            <span class="text-slate-400 text-[11px]">Unverified</span>
                                        @endif
                                    </td>
                                    <td class="p-3 text-slate-500 text-[11px]">
                                        <span>👁 {{ $p->views_count }}</span> • 
                                        <span>⚡ {{ $p->lead_count }} leads</span> • 
                                        <span>⭐ {{ number_format($p->average_rating, 1) }}</span>
                                    </td>
                                    <td class="p-3 text-right">
                                        <div class="inline-flex items-center gap-1.5">
                                            @if($p->status !== 'approved')
                                                <form action="{{ route('admin.professionals.approve', $p->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="px-2.5 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px]" title="Approve Listing">
                                                        Approve
                                                    </button>
                                                </form>
                                            @endif

                                            <a href="{{ route('admin.professionals.show', $p->id) }}" class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-200 text-[11px] font-bold">
                                                Inspect
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-slate-100 dark:border-slate-700">
                    {{ $professionals->links() }}
                </div>
            @else
                <div class="text-center py-12 text-slate-400">
                    <p class="text-xs">No professionals found matching the filters.</p>
                </div>
            @endif
        </div>
    </form>

</div>

<script>
    function toggleSelectAll(master) {
        document.querySelectorAll('.prof-checkbox').forEach(cb => {
            cb.checked = master.checked;
        });
    }
</script>
@endsection
