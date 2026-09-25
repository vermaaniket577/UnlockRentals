@extends('layouts.admin')

@section('title', 'Fraud & Abuse Reports | UnlockRentals CRM')

@section('content')
<div class="p-6 space-y-6">

    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white flex items-center gap-2">
                <i class="ph-bold ph-shield-warning text-rose-600"></i>
                Fraud & Abuse Reports CRM
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">Investigate suspicious listings, fake profiles, abusive behavior, and contact disputes</p>
        </div>

        <a href="{{ route('admin.professionals.dashboard') }}" class="px-3.5 py-2 rounded-xl bg-slate-100 dark:bg-slate-700 text-xs font-bold text-slate-700 dark:text-slate-200">
            Back to Dashboard
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 text-emerald-800 text-xs font-semibold flex items-center gap-2">
            <i class="ph-fill ph-check-circle text-base text-emerald-600"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Reports List Table --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700 shadow-xs overflow-hidden">
        @if($reports->count() > 0)
            <div class="divide-y divide-slate-100 dark:divide-slate-700">
                @foreach($reports as $report)
                    <div class="p-5 flex flex-col sm:flex-row items-start justify-between gap-4">
                        <div class="space-y-2 flex-1">
                            <div class="flex items-center gap-3">
                                <span class="px-2.5 py-0.5 rounded-full bg-rose-100 text-rose-800 font-bold text-xs">
                                    {{ $report->reason }}
                                </span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $report->status === 'resolved' ? 'bg-emerald-100 text-emerald-800' : ($report->status === 'pending' ? 'bg-amber-100 text-amber-800' : 'bg-slate-200 text-slate-800') }}">
                                    Status: {{ $report->status }}
                                </span>
                                <span class="text-[11px] text-slate-400">{{ $report->created_at->format('M d, Y • h:i A') }}</span>
                            </div>

                            <p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed font-medium">
                                {{ $report->description }}
                            </p>

                            <div class="flex flex-wrap items-center gap-4 text-[11px] text-slate-400">
                                <span>Reported Listing: <a href="{{ route('admin.professionals.show', $report->professional_id) }}" class="text-blue-600 font-bold hover:underline">{{ $report->professional->business_name }}</a></span>
                                @if($report->user)
                                    <span>Reported By: <strong class="text-slate-700 dark:text-slate-300">{{ $report->user->name }}</strong> ({{ $report->user->email }})</span>
                                @else
                                    <span>Reported by Guest</span>
                                @endif
                            </div>
                        </div>

                        {{-- Resolution Form --}}
                        <form action="{{ route('admin.professionals.reports.resolve', $report->id) }}" method="POST" class="w-full sm:w-72 bg-slate-50 dark:bg-slate-700/40 p-3 rounded-xl border border-slate-200 dark:border-slate-600 space-y-2 text-xs">
                            @csrf
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1">Resolution Action</label>
                                <select name="status" class="w-full text-xs rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800">
                                    <option value="reviewed" {{ $report->status === 'reviewed' ? 'selected' : '' }}>Mark Reviewed</option>
                                    <option value="resolved" {{ $report->status === 'resolved' ? 'selected' : '' }}>Mark Resolved</option>
                                    <option value="dismissed" {{ $report->status === 'dismissed' ? 'selected' : '' }}>Dismiss Report</option>
                                </select>
                            </div>

                            <label class="flex items-center gap-2 text-[11px] text-rose-600 font-bold cursor-pointer">
                                <input type="checkbox" name="suspend_professional" value="1" class="rounded border-slate-300 text-rose-600">
                                <span>Suspend Professional Listing</span>
                            </label>

                            <button type="submit" class="w-full py-1.5 rounded-lg bg-blue-600 text-white font-bold text-xs">
                                Save Action
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>

            <div class="p-4 border-t border-slate-100 dark:border-slate-700">
                {{ $reports->links() }}
            </div>
        @else
            <div class="text-center py-12 text-slate-400">
                <i class="ph ph-shield-check text-3xl mb-1 text-emerald-500 block"></i>
                <p class="text-xs">No pending fraud or abuse reports.</p>
            </div>
        @endif
    </div>

</div>
@endsection
