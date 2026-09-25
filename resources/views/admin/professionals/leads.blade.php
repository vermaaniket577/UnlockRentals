@extends('layouts.admin')

@section('title', 'Leads CRM | UnlockRentals CRM')

@section('content')
<div class="p-6 space-y-6">

    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white flex items-center gap-2">
                <i class="ph-bold ph-funnel text-blue-600"></i>
                Service Leads Management
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">Track customer service requests and matched professional leads across the platform</p>
        </div>

        <a href="{{ route('admin.professionals.dashboard') }}" class="px-3.5 py-2 rounded-xl bg-slate-100 dark:bg-slate-700 text-xs font-bold text-slate-700 dark:text-slate-200">
            Back to Dashboard
        </a>
    </div>

    {{-- Filter by Status --}}
    <div class="flex items-center gap-2 overflow-x-auto pb-2 text-xs font-bold">
        @foreach(['all' => 'All Status', 'new' => 'New', 'contacted' => 'Contacted', 'accepted' => 'Accepted', 'completed' => 'Completed', 'rejected' => 'Rejected'] as $st => $label)
            <a href="{{ route('admin.professionals.leads', $st !== 'all' ? ['status' => $st] : []) }}" class="px-3 py-1.5 rounded-xl transition-all whitespace-nowrap {{ (request('status') === $st || (!request('status') && $st === 'all')) ? 'bg-blue-600 text-white' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:bg-slate-50' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700 shadow-xs overflow-hidden">
        @if($leads->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-slate-50/50 dark:bg-slate-700/30 text-slate-500 border-b border-slate-100 dark:border-slate-700">
                            <th class="p-3.5">Customer & Request</th>
                            <th class="p-3.5">Assigned Professional</th>
                            <th class="p-3.5">Location</th>
                            <th class="p-3.5">Lead Status</th>
                            <th class="p-3.5">Source</th>
                            <th class="p-3.5">Timestamp</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @foreach($leads as $lead)
                            @php $req = $lead->serviceRequest; @endphp
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-700/40">
                                <td class="p-3.5">
                                    <div class="font-bold text-slate-900 dark:text-white">
                                        {{ $req?->name ?? 'Customer' }}
                                    </div>
                                    <span class="block text-[11px] text-slate-400">
                                        {{ $req?->phone }} • {{ $req?->service?->name ?? ($req?->category?->name ?? 'Service') }}
                                    </span>
                                    <p class="text-[11px] text-slate-600 dark:text-slate-300 line-clamp-1 mt-0.5">{{ $req?->description }}</p>
                                </td>
                                <td class="p-3.5">
                                    @if($lead->professional)
                                        <a href="{{ route('admin.professionals.show', $lead->professional->id) }}" class="font-bold text-blue-600 hover:underline">
                                            {{ $lead->professional->business_name }}
                                        </a>
                                        <span class="block text-[11px] text-slate-400">{{ $lead->professional->phone }}</span>
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="p-3.5 text-slate-600 dark:text-slate-300">
                                    {{ $req?->locality ? $req->locality . ', ' : '' }}{{ $req?->city }}
                                </td>
                                <td class="p-3.5">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $lead->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : ($lead->status === 'new' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800') }}">
                                        {{ $lead->status }}
                                    </span>
                                </td>
                                <td class="p-3.5 text-slate-500 font-semibold">
                                    {{ $lead->lead_source }}
                                </td>
                                <td class="p-3.5 text-slate-400 text-[11px]">
                                    {{ $lead->created_at->format('M d, Y • h:i A') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100 dark:border-slate-700">
                {{ $leads->links() }}
            </div>
        @else
            <div class="text-center py-12 text-slate-400">
                <p class="text-xs">No service leads recorded in this filter.</p>
            </div>
        @endif
    </div>

</div>
@endsection
