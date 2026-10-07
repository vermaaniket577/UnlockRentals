@extends('layouts.admin')

@section('title', 'Leads CRM Pipeline - UnlockRentals')
@section('topbar_title', 'Leads CRM Pipeline')

@section('content')
<div class="max-w-7xl mx-auto space-y-6" x-data="{ addModalOpen: false }">

    {{-- Top Header & Actions --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Leads CRM Pipeline</h1>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    {{ $totalLeadsCount }} Total Leads
                </span>
            </div>
            <p class="text-sm text-slate-500 mt-1">Convert anonymous inquiries into closed rental & property purchase transactions.</p>
        </div>

        <div class="flex items-center gap-2.5 flex-wrap">
            <button type="button" onclick="openExternalSyncModal()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-xl text-xs font-bold transition-all shadow-sm shadow-blue-600/25 cursor-pointer">
                <i class="ph-bold ph-paper-plane-tilt text-base"></i> Send All to External API
            </button>
            <a href="{{ route('admin.leads.export.csv', request()->all()) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all">
                <i class="ph-bold ph-download-simple text-base"></i> Export CSV
            </a>
            <button onclick="document.getElementById('createLeadModal').classList.remove('hidden')" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition-all shadow-sm">
                <i class="ph-bold ph-plus text-base"></i> Add Lead
            </button>
        </div>
    </div>

    {{-- Live Incoming Leads Pipeline & API Dispatch Status Banner --}}
    <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white p-4 sm:p-5 rounded-3xl shadow-sm border border-slate-800/90 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-start sm:items-center gap-3.5">
            <div class="w-10 h-10 rounded-2xl bg-blue-500/20 border border-blue-400/30 flex items-center justify-center flex-shrink-0 text-blue-400 shadow-inner">
                <i class="ph-bold ph-broadcast text-xl animate-pulse"></i>
            </div>
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-black bg-emerald-500/20 text-emerald-300 border border-emerald-400/30">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span> Live Leads Pipeline
                    </span>
                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-blue-500/20 text-blue-200 border border-blue-400/30 font-mono">
                        HTTP 204 Fire-and-Forget API
                    </span>
                </div>
                <p class="text-xs text-slate-300 mt-1 leading-relaxed">
                    Website inquiries, direct landlord contact requests, and search leads stream here automatically. Dispatched asynchronously via <code class="text-indigo-300 font-mono">Http::pool()</code> without blocking.
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2 text-xs font-mono bg-black/40 px-3.5 py-2 rounded-xl border border-white/10 self-start md:self-auto shadow-inner">
            <span class="text-slate-400">Endpoint:</span>
            <code class="text-emerald-300 font-bold select-all">/api/leads/send-all</code>
            <button type="button" onclick="navigator.clipboard.writeText(window.location.origin + '/api/leads/send-all'); alert('Copied endpoint URL to clipboard!')" class="text-slate-400 hover:text-white transition-colors cursor-pointer ml-1" title="Copy endpoint">
                <i class="ph-bold ph-copy text-sm"></i>
            </button>
        </div>
    </div>

    {{-- Summary Counters --}}
    <div class="grid grid-cols-2 sm:grid-cols-6 gap-3.5">
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
            <span class="text-[11px] font-bold text-slate-400 uppercase">Total Leads</span>
            <p class="text-2xl font-black text-slate-900 mt-1">{{ $totalLeadsCount }}</p>
        </div>
        <a href="{{ route('admin.leads.index', ['category' => 'admission']) }}" class="bg-gradient-to-br from-purple-50 to-indigo-50/50 hover:from-purple-100 hover:to-indigo-100/70 p-4 rounded-2xl border {{ request('category') === 'admission' ? 'border-purple-400 ring-2 ring-purple-400/20 shadow-md' : 'border-purple-200/70' }} shadow-xs transition-all group block">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-black text-purple-700 uppercase tracking-wider flex items-center gap-1">
                    <i class="ph-bold ph-graduation-cap text-sm"></i> Admissions
                </span>
                <span class="text-[9px] font-black px-1.5 py-0.5 rounded bg-purple-200/70 text-purple-900 uppercase">Education</span>
            </div>
            <p class="text-2xl font-black text-purple-900 mt-1 group-hover:scale-105 transition-transform">{{ $admissionLeadsCount }}</p>
        </a>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
            <span class="text-[11px] font-bold text-slate-400 uppercase">New Today</span>
            <p class="text-2xl font-black text-blue-600 mt-1">{{ $newTodayCount }}</p>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
            <span class="text-[11px] font-bold text-slate-400 uppercase">In Progress</span>
            <p class="text-2xl font-black text-indigo-600 mt-1">{{ $activeInProgressCount }}</p>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
            <span class="text-[11px] font-bold text-slate-400 uppercase">Converted</span>
            <p class="text-2xl font-black text-emerald-600 mt-1">{{ $convertedCount }}</p>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
            <span class="text-[11px] font-bold text-slate-400 uppercase">Overdue Action</span>
            <p class="text-2xl font-black {{ $overdueFollowUpsCount > 0 ? 'text-rose-600' : 'text-slate-900' }} mt-1">{{ $overdueFollowUpsCount }}</p>
        </div>
    </div>

    {{-- Quick Category Filter Chips --}}
    <div class="flex items-center gap-2 flex-wrap">
        <a href="{{ route('admin.leads.index', request()->except(['category', 'page'])) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold transition-all {{ !request('category') || request('category') === 'all' ? 'bg-slate-900 text-white shadow-xs' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50' }}">
            All Leads <span class="text-[11px] opacity-75">({{ $totalLeadsCount }})</span>
        </a>
        <a href="{{ route('admin.leads.index', array_merge(request()->except(['page']), ['category' => 'admission'])) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold transition-all {{ request('category') === 'admission' ? 'bg-purple-600 text-white shadow-md shadow-purple-600/25 ring-2 ring-purple-600/20' : 'bg-purple-50 text-purple-700 border border-purple-200 hover:bg-purple-100' }}">
            <i class="ph-bold ph-graduation-cap text-sm"></i> Admission & Education Leads <span class="px-1.5 py-0.2 rounded-full text-[10px] font-black {{ request('category') === 'admission' ? 'bg-white/20 text-white' : 'bg-purple-200 text-purple-900' }}">{{ $admissionLeadsCount }}</span>
        </a>
        <a href="{{ route('admin.leads.index', array_merge(request()->except(['page']), ['category' => 'property'])) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold transition-all {{ request('category') === 'property' ? 'bg-blue-600 text-white shadow-xs' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50' }}">
            <i class="ph-bold ph-buildings text-sm"></i> Rental & Property Leads <span class="text-[11px] opacity-75">({{ max(0, $totalLeadsCount - $admissionLeadsCount) }})</span>
        </a>
    </div>

    {{-- Filter Toolbar --}}
    <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-xs">
        <form method="GET" action="{{ route('admin.leads.index') }}" class="flex flex-wrap items-center gap-3">
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif

            <div class="relative flex-1 min-w-[200px]">
                <i class="ph-bold ph-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search lead name, phone, course, city..." class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <select name="status" onchange="this.form.submit()" class="text-xs py-2 px-3 rounded-xl border border-slate-200 bg-white text-slate-700">
                <option value="all">All Statuses</option>
                <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>New</option>
                <option value="contacted" {{ request('status') === 'contacted' ? 'selected' : '' }}>Contacted</option>
                <option value="interested" {{ request('status') === 'interested' ? 'selected' : '' }}>Interested</option>
                <option value="scheduled_visit" {{ request('status') === 'scheduled_visit' ? 'selected' : '' }}>Visit Scheduled</option>
                <option value="negotiation" {{ request('status') === 'negotiation' ? 'selected' : '' }}>Negotiation</option>
                <option value="converted" {{ request('status') === 'converted' ? 'selected' : '' }}>Converted / Closed</option>
                <option value="lost" {{ request('status') === 'lost' ? 'selected' : '' }}>Lost</option>
            </select>

            <select name="source" onchange="this.form.submit()" class="text-xs py-2 px-3 rounded-xl border border-slate-200 bg-white text-slate-700">
                <option value="all">All Sources</option>
                <option value="admission" {{ request('source') === 'admission' ? 'selected' : '' }}>Admission Portal</option>
                <option value="anushram" {{ request('source') === 'anushram' ? 'selected' : '' }}>Anushram</option>
                <option value="website_lead_form" {{ request('source') === 'website_lead_form' ? 'selected' : '' }}>Website Form</option>
                <option value="whatsapp" {{ request('source') === 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                <option value="manual" {{ request('source') === 'manual' ? 'selected' : '' }}>Manual Staff Entry</option>
            </select>

            <select name="intent" onchange="this.form.submit()" class="text-xs py-2 px-3 rounded-xl border border-slate-200 bg-white text-slate-700">
                <option value="all">All Intents</option>
                <option value="admission" {{ request('intent') === 'admission' ? 'selected' : '' }}>Admission</option>
                <option value="rent" {{ request('intent') === 'rent' ? 'selected' : '' }}>Rent</option>
                <option value="buy" {{ request('intent') === 'buy' ? 'selected' : '' }}>Buy</option>
                <option value="sell" {{ request('intent') === 'sell' ? 'selected' : '' }}>Sell</option>
            </select>

            <select name="assigned_to" onchange="this.form.submit()" class="text-xs py-2 px-3 rounded-xl border border-slate-200 bg-white text-slate-700">
                <option value="all">All Agents</option>
                <option value="unassigned" {{ request('assigned_to') === 'unassigned' ? 'selected' : '' }}>Unassigned</option>
                @foreach($staffUsers as $su)
                    <option value="{{ $su->id }}" {{ request('assigned_to') == $su->id ? 'selected' : '' }}>{{ $su->name }}</option>
                @endforeach
            </select>

            <button type="submit" class="px-4 py-2 bg-slate-900 text-white text-xs font-bold rounded-xl hover:bg-slate-800">
                Filter
            </button>

            @if(request()->hasAny(['search', 'status', 'intent', 'source', 'assigned_to', 'category']))
                <a href="{{ route('admin.leads.index') }}" class="px-3 py-2 text-xs font-semibold text-rose-600 bg-rose-50 rounded-xl hover:bg-rose-100">Reset</a>
            @endif
        </form>
    </div>

    {{-- Leads Table --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3.5">Lead Details</th>
                        <th class="px-6 py-3.5">Requirement & Budget</th>
                        <th class="px-6 py-3.5">Status & Stage</th>
                        <th class="px-6 py-3.5">Assigned Agent</th>
                        <th class="px-6 py-3.5">Next Follow-up</th>
                        <th class="px-6 py-3.5 text-right">Direct Outreach</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($leads as $lead)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            {{-- Lead Contact --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <a href="{{ route('admin.leads.show', $lead->id) }}" class="font-bold text-slate-900 hover:text-blue-600 transition-colors text-sm">
                                        {{ $lead->name }}
                                    </a>
                                    @if(method_exists($lead, 'isAdmissionLead') && $lead->isAdmissionLead())
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-purple-100 text-purple-700 border border-purple-200" title="Admission / Education Inquiry">
                                            🎓 Admission
                                        </span>
                                    @endif
                                </div>
                                <div class="text-[11px] text-slate-500 mt-0.5 flex items-center gap-2">
                                    <span class="font-mono font-medium text-slate-700">{{ $lead->phone }}</span>
                                    @if($lead->whatsapp_opt_in)
                                        <span class="text-emerald-600 font-bold" title="WhatsApp Opted In">
                                            <i class="ph-bold ph-whatsapp-logo"></i>
                                        </span>
                                    @endif
                                    @if($lead->email)
                                        <span class="text-slate-400 font-mono text-[10px] truncate max-w-[120px]">{{ $lead->email }}</span>
                                    @endif
                                </div>
                                <div class="text-[10px] text-slate-400 mt-1 flex items-center gap-2">
                                    <span>Source: <strong class="text-slate-600 font-semibold">{{ ucfirst(str_replace('_', ' ', $lead->source)) }}</strong></span>
                                    @if($lead->created_at)
                                        <span class="text-slate-300">•</span>
                                        <span>{{ $lead->created_at->diffForHumans() }}</span>
                                    @endif
                                </div>
                            </td>

                            {{-- Requirement / Enquiry --}}
                            <td class="px-6 py-4">
                                @if(method_exists($lead, 'isAdmissionLead') && $lead->isAdmissionLead())
                                    <div class="font-bold text-purple-900 flex items-center gap-1.5">
                                        <span>🎓 Educational Enquiry</span>
                                    </div>
                                    @if($lead->message)
                                        <div class="text-[11px] text-slate-700 mt-1 p-2 rounded-xl bg-purple-50/80 border border-purple-200/60 line-clamp-2 max-w-xs leading-relaxed" title="{{ $lead->message }}">
                                            {{ $lead->message }}
                                        </div>
                                    @endif
                                    @if($lead->preferred_city)
                                        <div class="text-[10px] text-slate-500 mt-1">
                                            City: {{ $lead->preferred_city }}
                                        </div>
                                    @endif
                                @else
                                    <div class="font-bold text-slate-800">
                                        <span class="capitalize">{{ $lead->intent ?? 'Inquire' }}</span>
                                        @if($lead->bhk_preference)
                                            • {{ $lead->bhk_preference }}
                                        @endif
                                    </div>
                                    <div class="text-[11px] text-slate-500 mt-0.5">
                                        @if($lead->budget_max)
                                            Max ₹{{ number_format($lead->budget_max) }}
                                        @else
                                            Flexible Budget
                                        @endif
                                        @if($lead->preferred_city)
                                            in {{ $lead->preferred_city }}
                                        @endif
                                    </div>
                                    @if($lead->message)
                                        <div class="text-[10px] text-slate-600 mt-1 italic line-clamp-1 max-w-xs" title="{{ $lead->message }}">
                                            "{{ \Illuminate\Support\Str::limit($lead->message, 60) }}"
                                        </div>
                                    @endif
                                @endif
                            </td>

                            {{-- Status Badge --}}
                            <td class="px-6 py-4">
                                @php
                                    $statusClasses = [
                                        'new' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'contacted' => 'bg-purple-50 text-purple-700 border-purple-200',
                                        'interested' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'scheduled_visit' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                        'negotiation' => 'bg-teal-50 text-teal-700 border-teal-200',
                                        'converted' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'lost' => 'bg-slate-100 text-slate-600 border-slate-200',
                                        'spam' => 'bg-rose-50 text-rose-700 border-rose-200',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase border {{ $statusClasses[$lead->lead_status] ?? 'bg-slate-50 text-slate-600' }}">
                                    {{ str_replace('_', ' ', $lead->lead_status) }}
                                </span>
                            </td>

                            {{-- Assigned --}}
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-800">
                                    {{ $lead->assignedTo ? $lead->assignedTo->name : 'Unassigned' }}
                                </div>
                            </td>

                            {{-- Next Follow-up --}}
                            <td class="px-6 py-4">
                                @if($lead->latestFollowUp && $lead->latestFollowUp->status === 'pending')
                                    @php $isOverdue = $lead->latestFollowUp->scheduled_at < now(); @endphp
                                    <div class="font-bold {{ $isOverdue ? 'text-rose-600' : 'text-slate-800' }}">
                                        {{ $lead->latestFollowUp->scheduled_at->format('d M, h:i A') }}
                                    </div>
                                    <div class="text-[10px] text-slate-400 capitalize">
                                        {{ $lead->latestFollowUp->follow_up_type }} ({{ $lead->latestFollowUp->scheduled_at->diffForHumans() }})
                                    </div>
                                @else
                                    <span class="text-slate-400 text-[11px]">No follow-up set</span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    {{-- Direct WhatsApp CTA --}}
                                    <a href="https://wa.me/91{{ $lead->phone }}?text={{ urlencode('Hello ' . $lead->name . ', thank you for your interest in UnlockRentals. How can we help you find the ideal property?') }}" target="_blank" class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white flex items-center justify-center transition-all" title="Chat on WhatsApp">
                                        <i class="ph-bold ph-whatsapp-logo text-base"></i>
                                    </a>

                                    {{-- Direct Phone Call --}}
                                    <a href="tel:{{ $lead->phone }}" class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white flex items-center justify-center transition-all" title="Call Lead">
                                        <i class="ph-bold ph-phone-call text-base"></i>
                                    </a>

                                    {{-- Send Single Lead to External API --}}
                                    <button type="button" onclick="sendSingleLeadToExternal({{ $lead->id }}, '{{ addslashes($lead->name) }}')" class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white flex items-center justify-center transition-all cursor-pointer" title="Send Lead to External API (/api/leads/send/{{ $lead->id }})">
                                        <i class="ph-bold ph-paper-plane-tilt text-base"></i>
                                    </button>

                                    {{-- Dossier Link --}}
                                    <a href="{{ route('admin.leads.show', $lead->id) }}" class="w-8 h-8 rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-900 hover:text-white flex items-center justify-center transition-all" title="View Dossier">
                                        <i class="ph-bold ph-arrow-right text-base"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                    <i class="ph-bold ph-funnel text-2xl"></i>
                                </div>
                                <p class="text-sm font-semibold text-slate-600">No leads matched your criteria.</p>
                                <p class="text-xs text-slate-400 mt-1">Leads captured from website modals and WhatsApp clicks will appear here.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($leads->hasPages())
        <div class="p-5 border-t border-slate-100">
            {{ $leads->links() }}
        </div>
        @endif
    </div>

    {{-- Create Manual Lead Modal --}}
    <div id="createLeadModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-100 overflow-y-auto max-h-[90vh]">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <h3 class="text-lg font-bold text-slate-900">Record New Lead</h3>
                <button onclick="document.getElementById('createLeadModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                    <i class="ph-bold ph-x text-lg"></i>
                </button>
            </div>

            <form action="{{ route('admin.leads.store') }}" method="POST" class="mt-4 space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Full Name *</label>
                        <input type="text" name="name" required class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="e.g. Rahul Sharma">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Phone Number (10 Digits) *</label>
                        <input type="tel" name="phone" pattern="[6-9][0-9]{9}" required class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="9876543210">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Email Address</label>
                        <input type="email" name="email" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="client@example.com">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Intent *</label>
                        <select name="intent" required class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white">
                            <option value="admission">🎓 Admission / Educational Enquiry</option>
                            <option value="rent">Looking to Rent</option>
                            <option value="buy">Looking to Buy</option>
                            <option value="sell">Looking to Sell / List</option>
                            <option value="inquire">General Inquiry</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Course / Degree / Subject (if Admission)</label>
                        <input type="text" name="course" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="e.g. B.Tech / MBA / Medical / Coaching">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Lead Source</label>
                        <select name="lead_source" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white">
                            <option value="admission">Admission Portal</option>
                            <option value="anushram">Anushram</option>
                            <option value="manual">Manual Entry</option>
                            <option value="website_lead_form">Website Form</option>
                            <option value="whatsapp">WhatsApp</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Max Budget (₹)</label>
                        <input type="number" name="budget_max" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="e.g. 25000">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">BHK Preference</label>
                        <select name="bhk_preference" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white">
                            <option value="">Any / Not Applicable</option>
                            <option value="1 BHK">1 BHK</option>
                            <option value="2 BHK">2 BHK</option>
                            <option value="3 BHK">3 BHK</option>
                            <option value="4+ BHK">4+ BHK</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Preferred City / Campus</label>
                        <input type="text" name="preferred_city" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="e.g. Pune, Kanpur">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Initial Staff Notes</label>
                    <textarea name="notes" rows="2" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Details about client requirements, moving date..."></textarea>
                </div>

                <div class="flex items-center gap-2 pt-2">
                    <input type="checkbox" name="whatsapp_opt_in" id="modal_wa_opt" value="1" checked class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    <label for="modal_wa_opt" class="text-xs text-slate-700 font-medium">Lead agreed to receive WhatsApp alerts & listings</label>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" onclick="document.getElementById('createLeadModal').classList.add('hidden')" class="px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-xl">Cancel</button>
                    <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-xs">Save Lead Dossier</button>
                </div>
            </form>
        </div>
    </div>

    {{-- External Leads Dispatcher Modal --}}
    <div id="externalSyncModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-xs">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-100 transform transition-all text-slate-900">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                        <i class="ph-bold ph-paper-plane-tilt"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Dispatch Leads to External API</h3>
                        <p class="text-[11px] text-slate-500">HTTP 204 No Content • Fire-and-Forget Dispatcher</p>
                    </div>
                </div>
                <button type="button" onclick="closeExternalSyncModal()" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 hover:text-slate-900 flex items-center justify-center transition-colors cursor-pointer">
                    <i class="ph-bold ph-x text-sm"></i>
                </button>
            </div>

            <div class="mt-4 space-y-4">
                {{-- Destination URL --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">External Target Endpoint URL</label>
                    <input type="url" id="externalSyncTargetUrl" value="{{ env('EXTERNAL_LEAD_API_URL', 'https://api.anushram.com/v1/api/general-enquiry/create') }}" class="w-full px-3 py-2 text-xs font-mono rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <p class="text-[10px] text-slate-400 mt-1">Default: <code class="text-indigo-600 font-mono">https://api.anushram.com/v1/api/general-enquiry/create</code></p>
                </div>

                {{-- Mode Selection --}}
                <div class="grid grid-cols-2 gap-3">
                    <label class="flex items-start gap-2.5 p-3 rounded-xl border border-slate-200 bg-slate-50 cursor-pointer hover:border-blue-500 transition-colors">
                        <input type="radio" name="syncMode" value="individual" checked class="mt-0.5 text-blue-600 focus:ring-blue-500">
                        <div>
                            <span class="block text-xs font-bold text-slate-900">Individual Mode</span>
                            <span class="block text-[10px] text-slate-500 leading-tight mt-0.5">Dispatches parallel asynchronous HTTP calls via Http::pool()</span>
                        </div>
                    </label>
                    <label class="flex items-start gap-2.5 p-3 rounded-xl border border-slate-200 bg-slate-50 cursor-pointer hover:border-blue-500 transition-colors">
                        <input type="radio" name="syncMode" value="bulk" class="mt-0.5 text-blue-600 focus:ring-blue-500">
                        <div>
                            <span class="block text-xs font-bold text-slate-900">Bulk Mode</span>
                            <span class="block text-[10px] text-slate-500 leading-tight mt-0.5">Sends all {{ $totalLeadsCount }} leads in a single bulk JSON payload</span>
                        </div>
                    </label>
                </div>

                {{-- Live Response / Status Area --}}
                <div id="externalSyncStatus" class="hidden p-3 rounded-xl text-xs font-mono border"></div>

                {{-- Action Buttons --}}
                <div class="flex flex-col sm:flex-row items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                    <button type="button" onclick="closeExternalSyncModal()" class="w-full sm:w-auto px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-xl cursor-pointer">
                        Cancel
                    </button>
                    <button type="button" id="btnTestDebugSync" onclick="executeExternalSync(true)" class="w-full sm:w-auto px-4 py-2 text-xs font-bold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-xl transition-all cursor-pointer flex items-center justify-center gap-1.5">
                        <i class="ph-bold ph-magnifying-glass"></i>
                        <span>Debug / Verify Mode</span>
                    </button>
                    <button type="button" id="btnFireForgetSync" onclick="executeExternalSync(false)" class="w-full sm:w-auto px-5 py-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-xs transition-all cursor-pointer flex items-center justify-center gap-1.5">
                        <i class="ph-bold ph-lightning"></i>
                        <span>Send All (204 Fire-and-Forget)</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    function openExternalSyncModal() {
        const modal = document.getElementById('externalSyncModal');
        const statusBox = document.getElementById('externalSyncStatus');
        if (statusBox) statusBox.classList.add('hidden');
        if (modal) modal.classList.remove('hidden');
    }

    function closeExternalSyncModal() {
        const modal = document.getElementById('externalSyncModal');
        if (modal) modal.classList.add('hidden');
    }

    async function executeExternalSync(isDebug) {
        const targetUrl = document.getElementById('externalSyncTargetUrl').value.trim();
        const mode = document.querySelector('input[name="syncMode"]:checked')?.value || 'individual';
        const statusBox = document.getElementById('externalSyncStatus');
        const btnFire = document.getElementById('btnFireForgetSync');
        const btnDebug = document.getElementById('btnTestDebugSync');

        btnFire.disabled = true;
        btnDebug.disabled = true;
        btnFire.classList.add('opacity-50');
        btnDebug.classList.add('opacity-50');

        statusBox.classList.remove('hidden', 'bg-emerald-50', 'text-emerald-800', 'border-emerald-200', 'bg-rose-50', 'text-rose-800', 'border-rose-200');
        statusBox.classList.add('bg-blue-50', 'text-blue-800', 'border-blue-200');
        statusBox.innerHTML = '<span class="animate-pulse">⏳ Dispatching leads to external API endpoint...</span>';

        try {
            const url = new URL('/api/leads/send-all', window.location.origin);
            if (targetUrl) url.searchParams.set('target_url', targetUrl);
            if (mode === 'bulk') url.searchParams.set('mode', 'bulk');
            if (isDebug) url.searchParams.set('debug', '1');

            const response = await fetch(url.toString(), {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            if (response.status === 204) {
                // Fire and Forget success (HTTP 204 No Content)
                statusBox.className = 'p-3 rounded-xl text-xs font-mono border bg-emerald-50 text-emerald-800 border-emerald-200';
                statusBox.innerHTML = '<strong>✅ HTTP 204 No Content</strong><br>Leads dispatched successfully via Fire-and-Forget (0 bytes response body).';
            } else if (response.ok) {
                const data = await response.json();
                statusBox.className = 'p-3 rounded-xl text-xs font-mono border bg-emerald-50 text-emerald-800 border-emerald-200';
                statusBox.innerHTML = `<strong>✅ ${data.message}</strong><br>Total: ${data.total_leads} leads | Mode: ${data.mode}<br>Target: ${data.target_url}`;
            } else {
                statusBox.className = 'p-3 rounded-xl text-xs font-mono border bg-rose-50 text-rose-800 border-rose-200';
                statusBox.innerHTML = `<strong>⚠️ Status: ${response.status}</strong><br>External API request completed with status ${response.status}.`;
            }
        } catch (err) {
            statusBox.className = 'p-3 rounded-xl text-xs font-mono border bg-rose-50 text-rose-800 border-rose-200';
            statusBox.innerHTML = `<strong>⚠️ Notice:</strong> ${err.message}`;
        } finally {
            btnFire.disabled = false;
            btnDebug.disabled = false;
            btnFire.classList.remove('opacity-50');
            btnDebug.classList.remove('opacity-50');
        }
    }

    async function sendSingleLeadToExternal(leadId, leadName) {
        if (!confirm(`Send lead "${leadName}" (#${leadId}) to external endpoint?`)) {
            return;
        }

        try {
            const response = await fetch(`/api/leads/send/${leadId}?debug=1`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            if (response.status === 204 || response.ok) {
                alert(`✅ Lead #${leadId} (${leadName}) was sent to the external API successfully!`);
            } else {
                alert(`Lead sent. Response status: ${response.status}`);
            }
        } catch (e) {
            alert(`Notice: ${e.message}`);
        }
    }
</script>
@endsection
