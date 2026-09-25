@extends('layouts.app')

@section('title', 'Manage Leads & Jobs | Professional Dashboard | UnlockRentals')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-900 pb-20 pt-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Breadcrumbs --}}
        <nav class="flex items-center text-xs text-slate-500 dark:text-slate-400 mb-6 gap-2" aria-label="Breadcrumb">
            <a href="{{ url('/') }}" class="hover:text-blue-600 transition-colors">Home</a>
            <i class="ph ph-caret-right text-[10px]"></i>
            <a href="{{ route('professional.dashboard') }}" class="hover:text-blue-600 transition-colors">Dashboard</a>
            <i class="ph ph-caret-right text-[10px]"></i>
            <span class="text-slate-900 dark:text-white font-medium">Leads & Jobs</span>
        </nav>

        {{-- Header & Sub-navigation --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white">Customer Leads & Service Jobs</h1>
                <p class="text-xs text-slate-500 mt-0.5">Contact customers directly, accept jobs, and update completion status</p>
            </div>
            <a href="{{ route('professional.dashboard') }}" class="px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-600 text-xs font-bold text-slate-700 dark:text-slate-200">
                Back to Dashboard
            </a>
        </div>

        {{-- Status Filter Tabs --}}
        <div class="flex items-center gap-2 overflow-x-auto pb-4 mb-6 text-xs font-bold">
            @foreach(['all' => 'All Leads', 'new' => 'New Leads', 'contacted' => 'Contacted', 'accepted' => 'Accepted Jobs', 'completed' => 'Completed', 'rejected' => 'Rejected'] as $k => $label)
                <a href="{{ route('professional.leads', ['status' => $k]) }}" class="px-3.5 py-2 rounded-xl transition-all flex items-center gap-1.5 whitespace-nowrap {{ $status === $k ? 'bg-blue-600 text-white shadow-sm' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:bg-slate-50' }}">
                    <span>{{ $label }}</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $status === $k ? 'bg-white/20 text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-500' }}">
                        {{ $stats[$k] ?? 0 }}
                    </span>
                </a>
            @endforeach
        </div>

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 text-emerald-800 text-xs font-semibold flex items-center gap-2">
                <i class="ph-fill ph-check-circle text-base text-emerald-600"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Leads List --}}
        @if($leads->count() > 0)
            <div class="space-y-4">
                @foreach($leads as $lead)
                    @php $req = $lead->serviceRequest; @endphp
                    <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200/80 dark:border-slate-700/80 shadow-sm flex flex-col lg:flex-row items-start justify-between gap-6">

                        {{-- Left: Customer Requirement --}}
                        <div class="flex-1 space-y-3">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="px-2.5 py-1 rounded-lg bg-blue-50 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 font-bold text-xs">
                                    {{ $req?->service?->name ?? ($req?->category?->name ?? 'General Service') }}
                                </span>

                                @php
                                    $statusPills = [
                                        'new' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300',
                                        'viewed' => 'bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-300',
                                        'contacted' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300',
                                        'accepted' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-300',
                                        'completed' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300',
                                        'rejected' => 'bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300',
                                    ];
                                    $pill = $statusPills[$lead->status] ?? 'bg-slate-100 text-slate-800';
                                @endphp
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase {{ $pill }}">
                                    Lead: {{ $lead->status }}
                                </span>

                                <span class="text-[11px] text-slate-400">
                                    Received {{ $lead->created_at->diffForHumans() }}
                                </span>
                            </div>

                            {{-- Problem description --}}
                            <div class="text-xs text-slate-700 dark:text-slate-200 bg-slate-50 dark:bg-slate-700/40 p-3.5 rounded-2xl border border-slate-100 dark:border-slate-700/60 leading-relaxed">
                                <strong class="block text-slate-900 dark:text-white mb-1">Problem Description:</strong>
                                {{ $req?->description }}
                            </div>

                            {{-- Customer Location & Details --}}
                            <div class="flex flex-wrap gap-4 text-xs text-slate-500 dark:text-slate-400">
                                <span><i class="ph-bold ph-map-pin text-blue-600"></i> {{ $req?->locality ? $req->locality . ', ' : '' }}{{ $req?->city }}</span>
                                @if($req?->preferred_date)
                                    <span><i class="ph-bold ph-calendar text-blue-600"></i> Date: {{ $req->preferred_date }}</span>
                                @endif
                                @if($req?->preferred_time)
                                    <span><i class="ph-bold ph-clock text-blue-600"></i> Time: {{ $req->preferred_time }}</span>
                                @endif
                                @if($req?->budget)
                                    <span><i class="ph-bold ph-currency-inr text-emerald-600"></i> Budget: ₹{{ number_format($req->budget) }}</span>
                                @endif
                            </div>

                            {{-- Customer Attachments --}}
                            @if($req && $req->attachments->count() > 0)
                                <div class="flex items-center gap-2 pt-1">
                                    <span class="text-[11px] font-bold text-slate-400">Photos:</span>
                                    @foreach($req->attachments as $att)
                                        <a href="{{ $att->file_url }}" target="_blank" class="w-12 h-12 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-600">
                                            <img src="{{ $att->file_url }}" class="w-full h-full object-cover">
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        {{-- Right: Direct Customer Contact & Status Controls --}}
                        <div class="w-full lg:w-72 flex-shrink-0 bg-slate-50 dark:bg-slate-700/50 p-4 rounded-2xl border border-slate-200/80 dark:border-slate-700 space-y-3">
                            <div>
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Customer Details</span>
                                <div class="text-sm font-bold text-slate-900 dark:text-white mt-0.5">
                                    {{ $req?->name ?? 'Customer' }}
                                </div>
                            </div>

                            {{-- Direct Actions (Call & WhatsApp) --}}
                            @if($req?->phone)
                                <div class="flex items-center gap-2">
                                    <a href="tel:{{ $req->phone }}" class="flex-1 inline-flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-sm transition-all">
                                        <i class="ph-bold ph-phone-call"></i> Call {{ $req->phone }}
                                    </a>
                                    <a href="https://wa.me/91{{ preg_replace('/[^0-9]/', '', $req->phone) }}?text={{ urlencode('Hello ' . $req->name . ', I received your service request on UnlockRentals for ' . ($req->service?->name ?? 'service') . '. How can I help you?') }}" target="_blank" class="p-2 rounded-xl bg-[#25D366] text-white hover:bg-[#20ba59] text-sm">
                                        <i class="ph-bold ph-whatsapp-logo"></i>
                                    </a>
                                </div>
                            @endif

                            {{-- Update Status Form --}}
                            <form action="{{ route('professional.leads.status', $lead->id) }}" method="POST" class="pt-2 border-t border-slate-200 dark:border-slate-600 space-y-2">
                                @csrf
                                <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300">Update Job Status</label>
                                <select name="status" onchange="this.form.submit()" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-800 dark:text-white font-semibold">
                                    <option value="new" {{ $lead->status == 'new' ? 'selected' : '' }}>New</option>
                                    <option value="contacted" {{ $lead->status == 'contacted' ? 'selected' : '' }}>Customer Contacted</option>
                                    <option value="accepted" {{ $lead->status == 'accepted' ? 'selected' : '' }}>Accept Job</option>
                                    <option value="completed" {{ $lead->status == 'completed' ? 'selected' : '' }}>Job Completed</option>
                                    <option value="rejected" {{ $lead->status == 'rejected' ? 'selected' : '' }}>Reject / Cannot Do</option>
                                </select>
                            </form>
                        </div>

                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $leads->links() }}
            </div>
        @else
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-12 text-center border border-slate-200 dark:border-slate-700">
                <div class="w-12 h-12 rounded-full bg-blue-50 dark:bg-slate-700 text-blue-600 flex items-center justify-center mx-auto mb-3">
                    <i class="ph-bold ph-funnel text-xl"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-1">No leads found in this filter</h3>
                <p class="text-xs text-slate-500">Customer service inquiries matching your category and service locations will appear here.</p>
            </div>
        @endif

    </div>
</div>
@endsection
