@extends('layouts.admin')

@section('title', 'Local Professionals CRM Dashboard | UnlockRentals')

@section('content')
<div class="p-6 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white flex items-center gap-2">
                <i class="ph-bold ph-toolbox text-blue-600"></i>
                Local Professionals CRM
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">Manage home service marketplace providers, verification requests, leads and reviews</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.professionals.categories') }}" class="px-3.5 py-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold hover:bg-slate-50 shadow-xs">
                Manage Categories
            </a>
            <a href="{{ route('admin.professionals.index') }}" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md shadow-blue-600/20">
                View All Professionals
            </a>
        </div>
    </div>

    {{-- Stats Cards (8 Key Metrics) --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <a href="{{ route('admin.professionals.index') }}" class="p-5 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 shadow-xs hover:border-blue-500 transition-colors">
            <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Listings</span>
            <div class="text-2xl font-black text-slate-900 dark:text-white mt-1">{{ $stats['total_professionals'] }}</div>
            <span class="text-[10px] text-blue-600 font-semibold mt-1 block">{{ $stats['approved_professionals'] }} approved & live</span>
        </a>

        <a href="{{ route('admin.professionals.index', ['status' => 'pending']) }}" class="p-5 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 shadow-xs hover:border-amber-500 transition-colors">
            <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Pending Approvals</span>
            <div class="text-2xl font-black text-amber-600 mt-1">{{ $stats['pending_professionals'] }}</div>
            <span class="text-[10px] text-amber-500 font-semibold mt-1 block">Requires admin action</span>
        </a>

        <a href="{{ route('admin.professionals.index', ['verification' => 'verified']) }}" class="p-5 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 shadow-xs hover:border-emerald-500 transition-colors">
            <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Verified Badges</span>
            <div class="text-2xl font-black text-emerald-600 mt-1">{{ $stats['verified_professionals'] }}</div>
            <span class="text-[10px] text-emerald-600 font-semibold mt-1 block">KYC verified providers</span>
        </a>

        <a href="{{ route('admin.professionals.leads') }}" class="p-5 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 shadow-xs hover:border-indigo-500 transition-colors">
            <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Leads Dispatched</span>
            <div class="text-2xl font-black text-indigo-600 mt-1">{{ $stats['total_leads'] }}</div>
            <span class="text-[10px] text-indigo-500 font-semibold mt-1 block">{{ $stats['completed_jobs'] }} completed jobs</span>
        </a>
    </div>

    {{-- Secondary Metric Bar --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <a href="{{ route('admin.professionals.reviews', ['status' => 'pending']) }}" class="p-4 rounded-xl bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase">Pending Reviews</span>
                <div class="text-lg font-bold text-slate-800 dark:text-slate-200">{{ $stats['pending_reviews'] }}</div>
            </div>
            <i class="ph-bold ph-star text-amber-500 text-xl"></i>
        </a>

        <a href="{{ route('admin.professionals.reports', ['status' => 'pending']) }}" class="p-4 rounded-xl bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase">Fraud Reports</span>
                <div class="text-lg font-bold text-rose-600">{{ $stats['pending_reports'] }}</div>
            </div>
            <i class="ph-bold ph-flag text-rose-500 text-xl"></i>
        </a>

        <div class="p-4 rounded-xl bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase">Service Requests</span>
                <div class="text-lg font-bold text-blue-600">{{ $stats['total_requests'] }}</div>
            </div>
            <i class="ph-bold ph-clipboard-text text-blue-500 text-xl"></i>
        </div>

        <div class="p-4 rounded-xl bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase">Suspended</span>
                <div class="text-lg font-bold text-slate-700 dark:text-slate-300">{{ $stats['suspended_professionals'] }}</div>
            </div>
            <i class="ph-bold ph-prohibit text-slate-400 text-xl"></i>
        </div>
    </div>

    {{-- Pending Approvals Table & Top Categories --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Pending Registrations Table --}}
        <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200/80 dark:border-slate-700 shadow-xs">
            <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100 dark:border-slate-700">
                <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="ph-bold ph-hourglass text-amber-500"></i>
                    Pending Approval Requests
                </h3>
                <a href="{{ route('admin.professionals.index', ['status' => 'pending']) }}" class="text-xs font-bold text-blue-600">
                    View All ({{ $stats['pending_professionals'] }})
                </a>
            </div>

            @if($pendingApprovals->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="text-slate-400 border-b border-slate-100 dark:border-slate-700">
                                <th class="pb-2">Professional</th>
                                <th class="pb-2">Category</th>
                                <th class="pb-2">Location</th>
                                <th class="pb-2">Date</th>
                                <th class="pb-2 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                            @foreach($pendingApprovals as $p)
                                <tr>
                                    <td class="py-3">
                                        <div class="flex items-center gap-2.5">
                                            <img src="{{ $p->profile_photo_url }}" class="w-8 h-8 rounded-lg object-cover">
                                            <div>
                                                <a href="{{ route('admin.professionals.show', $p->id) }}" class="font-bold text-slate-900 dark:text-white hover:text-blue-600">
                                                    {{ $p->business_name }}
                                                </a>
                                                <span class="block text-[11px] text-slate-400">{{ $p->full_name }} • {{ $p->phone }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 font-semibold text-slate-700 dark:text-slate-300">
                                        {{ $p->category->name }}
                                    </td>
                                    <td class="py-3 text-slate-500">
                                        {{ $p->city }}
                                    </td>
                                    <td class="py-3 text-slate-400 text-[11px]">
                                        {{ $p->created_at->diffForHumans() }}
                                    </td>
                                    <td class="py-3 text-right">
                                        <div class="inline-flex items-center gap-1.5">
                                            <form action="{{ route('admin.professionals.approve', $p->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="px-2.5 py-1 rounded-lg bg-emerald-600 text-white font-bold text-[11px] hover:bg-emerald-700">
                                                    Approve
                                                </button>
                                            </form>
                                            <a href="{{ route('admin.professionals.show', $p->id) }}" class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 text-[11px] font-bold">
                                                Inspect
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8 text-slate-400">
                    <i class="ph ph-check-circle text-3xl mb-1 text-emerald-500 block"></i>
                    <p class="text-xs">All pending professional listings have been reviewed!</p>
                </div>
            @endif
        </div>

        {{-- Top Categories by Listings --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200/80 dark:border-slate-700 shadow-xs">
            <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                <i class="ph-bold ph-chart-pie-slice text-blue-600"></i>
                Top Service Categories
            </h3>

            <div class="space-y-3">
                @foreach($topCategories as $tc)
                    <div class="flex items-center justify-between text-xs py-1.5 border-b border-slate-100 dark:border-slate-700">
                        <div class="flex items-center gap-2">
                            <i class="{{ $tc->icon ?: 'ph-bold ph-wrench' }} text-blue-600 text-base"></i>
                            <span class="font-bold text-slate-800 dark:text-slate-200">{{ $tc->name }}</span>
                        </div>
                        <span class="px-2 py-0.5 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-black text-[11px]">
                            {{ $tc->professionals_count }} listings
                        </span>
                    </div>
                @endforeach
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-700">
                <a href="{{ route('admin.professionals.categories') }}" class="block text-center text-xs font-bold text-blue-600 hover:text-blue-700">
                    Manage All Categories & Services →
                </a>
            </div>
        </div>

    </div>

</div>
@endsection
