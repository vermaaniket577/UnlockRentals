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

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.leads.export.csv', request()->all()) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all">
                <i class="ph-bold ph-download-simple text-base"></i> Export CSV
            </a>
            <button onclick="document.getElementById('createLeadModal').classList.remove('hidden')" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-all shadow-sm shadow-blue-600/20">
                <i class="ph-bold ph-plus text-base"></i> Add Lead
            </button>
        </div>
    </div>

    {{-- Summary Counters --}}
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
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
            <span class="text-[11px] font-bold text-slate-400 uppercase">Overdue Follow-ups</span>
            <p class="text-2xl font-black {{ $overdueFollowUpsCount > 0 ? 'text-rose-600' : 'text-slate-900' }} mt-1">{{ $overdueFollowUpsCount }}</p>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
            <span class="text-[11px] font-bold text-slate-400 uppercase">Win Rate</span>
            <p class="text-2xl font-black text-purple-600 mt-1">{{ $totalLeadsCount > 0 ? round(($convertedCount / $totalLeadsCount) * 100, 1) : 0 }}%</p>
        </div>
    </div>

    {{-- Filter Toolbar --}}
    <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-xs">
        <form method="GET" action="{{ route('admin.leads.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="relative flex-1 min-w-[200px]">
                <i class="ph-bold ph-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search lead name, phone, city..." class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
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

            <select name="intent" onchange="this.form.submit()" class="text-xs py-2 px-3 rounded-xl border border-slate-200 bg-white text-slate-700">
                <option value="all">All Intents</option>
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

            @if(request()->hasAny(['search', 'status', 'intent', 'assigned_to']))
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
                                <a href="{{ route('admin.leads.show', $lead->id) }}" class="font-bold text-slate-900 hover:text-blue-600 transition-colors text-sm block">
                                    {{ $lead->name }}
                                </a>
                                <div class="text-[11px] text-slate-500 mt-0.5 flex items-center gap-2">
                                    <span class="font-mono">{{ $lead->phone }}</span>
                                    @if($lead->whatsapp_opt_in)
                                        <span class="text-emerald-600 font-bold" title="WhatsApp Opted In">
                                            <i class="ph-bold ph-whatsapp-logo"></i>
                                        </span>
                                    @endif
                                </div>
                                <div class="text-[10px] text-slate-400 mt-1">Source: {{ ucfirst(str_replace('_', ' ', $lead->source)) }}</div>
                            </td>

                            {{-- Requirement --}}
                            <td class="px-6 py-4">
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
                            <option value="rent">Looking to Rent</option>
                            <option value="buy">Looking to Buy</option>
                            <option value="sell">Looking to Sell / List</option>
                            <option value="inquire">General Inquiry</option>
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
                            <option value="">Any BHK</option>
                            <option value="1 BHK">1 BHK</option>
                            <option value="2 BHK">2 BHK</option>
                            <option value="3 BHK">3 BHK</option>
                            <option value="4+ BHK">4+ BHK</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Preferred City</label>
                        <input type="text" name="preferred_city" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="e.g. Pune">
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

</div>
@endsection
