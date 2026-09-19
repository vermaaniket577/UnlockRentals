@extends('layouts.admin')

@section('title', 'Lead Dossier: ' . $lead->name . ' - UnlockRentals CRM')
@section('topbar_title', 'Lead Dossier')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    {{-- Breadcrumbs & Navigation --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.leads.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-blue-600 transition-colors">
            <i class="ph-bold ph-arrow-left"></i> Back to Leads Pipeline
        </a>
        <div class="flex items-center gap-2">
            <span class="text-xs font-mono text-slate-400">Lead #{{ $lead->id }}</span>
            <span class="text-xs font-bold text-slate-400">•</span>
            <span class="text-xs text-slate-500">Created {{ $lead->created_at->format('d M Y, h:i A') }}</span>
        </div>
    </div>

    {{-- Lead Header Dossier Card --}}
    <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div class="flex items-start gap-4">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center text-2xl font-black shadow-md flex-shrink-0">
                {{ strtoupper(substr($lead->name, 0, 1)) }}
            </div>
            <div>
                <div class="flex items-center gap-3 flex-wrap">
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">{{ $lead->name }}</h1>
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
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black uppercase border {{ $statusClasses[$lead->lead_status] ?? 'bg-slate-50 text-slate-600' }}">
                        {{ str_replace('_', ' ', $lead->lead_status) }}
                    </span>
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                        Score: {{ $lead->lead_score }} pts
                    </span>
                </div>

                <div class="flex items-center gap-4 text-xs text-slate-600 mt-2 flex-wrap font-medium">
                    <a href="tel:{{ $lead->phone }}" class="flex items-center gap-1.5 text-blue-600 hover:underline font-bold font-mono">
                        <i class="ph-bold ph-phone-call"></i> {{ $lead->phone }}
                    </a>
                    @if($lead->email)
                        <a href="mailto:{{ $lead->email }}" class="flex items-center gap-1.5 text-slate-600 hover:underline">
                            <i class="ph-bold ph-envelope"></i> {{ $lead->email }}
                        </a>
                    @endif
                    <span class="flex items-center gap-1.5">
                        <i class="ph-bold ph-whatsapp-logo {{ $lead->whatsapp_opt_in ? 'text-emerald-600' : 'text-slate-400' }}"></i>
                        WhatsApp: {{ $lead->whatsapp_opt_in ? 'Subscribed' : 'Opted-Out' }}
                    </span>
                    <span class="flex items-center gap-1.5">
                        <i class="ph-bold ph-user text-slate-400"></i>
                        Agent: <strong class="text-slate-800">{{ $lead->assignedTo ? $lead->assignedTo->name : 'Unassigned' }}</strong>
                    </span>
                </div>
            </div>
        </div>

        {{-- Quick Outreach Action Buttons --}}
        <div class="flex items-center gap-2.5 flex-wrap self-start md:self-auto">
            {{-- WhatsApp Direct Modal Trigger --}}
            <button onclick="document.getElementById('whatsappModal').classList.remove('hidden')" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-all shadow-sm shadow-emerald-600/20">
                <i class="ph-bold ph-whatsapp-logo text-base"></i> Send WhatsApp
            </button>

            {{-- WhatsApp Web Fallback --}}
            <a href="https://wa.me/91{{ $lead->phone }}?text={{ urlencode('Hello ' . $lead->name . ', reaching out regarding your property search on UnlockRentals.') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded-xl text-xs font-bold transition-all" title="Open in WhatsApp Web">
                <i class="ph-bold ph-arrow-square-out text-base"></i> WA Web
            </a>

            {{-- Schedule Follow-up Modal Trigger --}}
            <button onclick="document.getElementById('followUpModal').classList.remove('hidden')" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-all shadow-sm shadow-blue-600/20">
                <i class="ph-bold ph-calendar-plus text-base"></i> Follow-up
            </button>
        </div>
    </div>

    {{-- Main Grid: 2 Columns (7 cols left, 5 cols right) --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- Left Column: Requirements, Property, Visitor, Recommendations --}}
        <div class="lg:col-span-7 space-y-6">

            {{-- Requirement Specifications Card --}}
            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs">
                <h3 class="text-base font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <i class="ph-bold ph-list-checks text-blue-600"></i> Property Requirement Details
                </h3>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100">
                        <span class="text-[10px] font-bold text-slate-400 uppercase">Intent</span>
                        <p class="text-sm font-bold text-slate-900 capitalize mt-0.5">{{ $lead->intent ?? 'Inquire' }}</p>
                    </div>

                    <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100">
                        <span class="text-[10px] font-bold text-slate-400 uppercase">Budget</span>
                        <p class="text-sm font-bold text-slate-900 mt-0.5">
                            @if($lead->budget_max)
                                Up to ₹{{ number_format($lead->budget_max) }}
                            @else
                                Flexible
                            @endif
                        </p>
                    </div>

                    <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100">
                        <span class="text-[10px] font-bold text-slate-400 uppercase">BHK Preference</span>
                        <p class="text-sm font-bold text-slate-900 mt-0.5">{{ $lead->bhk_preference ?? 'Any BHK' }}</p>
                    </div>

                    <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100">
                        <span class="text-[10px] font-bold text-slate-400 uppercase">Preferred City</span>
                        <p class="text-sm font-bold text-slate-900 mt-0.5">{{ $lead->preferred_city ?? 'Anywhere' }}</p>
                    </div>

                    <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100">
                        <span class="text-[10px] font-bold text-slate-400 uppercase">Locality</span>
                        <p class="text-sm font-bold text-slate-900 mt-0.5">{{ $lead->preferred_locality ?? 'Not specified' }}</p>
                    </div>

                    <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100">
                        <span class="text-[10px] font-bold text-slate-400 uppercase">Capture Source</span>
                        <p class="text-sm font-bold text-slate-900 capitalize mt-0.5">{{ str_replace('_', ' ', $lead->source) }}</p>
                    </div>
                </div>
            </div>

            {{-- Inquired Property Card (if attached to specific property) --}}
            @if($lead->property)
            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs">
                <h3 class="text-base font-bold text-slate-900 mb-3 flex items-center gap-2">
                    <i class="ph-bold ph-buildings text-indigo-600"></i> Inquired Property
                </h3>
                <div class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-100">
                    <div class="w-16 h-16 rounded-xl bg-slate-200 overflow-hidden flex-shrink-0">
                        @if($lead->property->primaryImage)
                            <img src="{{ asset('storage/' . $lead->property->primaryImage->image_path) }}" alt="{{ $lead->property->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-400"><i class="ph-bold ph-image text-xl"></i></div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded bg-blue-100 text-blue-700">{{ $lead->property->purpose }}</span>
                        <h4 class="text-sm font-bold text-slate-900 truncate mt-1">{{ $lead->property->title }}</h4>
                        <p class="text-xs text-slate-500 truncate">{{ $lead->property->location }}</p>
                        <p class="text-xs font-black text-slate-900 mt-1">₹{{ number_format($lead->property->price) }}</p>
                    </div>
                    <a href="{{ route('properties.show', $lead->property->id) }}" target="_blank" class="px-3 py-2 bg-blue-50 text-blue-600 rounded-xl font-bold text-xs hover:bg-blue-600 hover:text-white transition-all flex-shrink-0">
                        View Listing
                    </a>
                </div>
            </div>
            @endif

            {{-- Connected Visitor Footprint Card (Anonymous Journey Stitching) --}}
            @if($lead->visitor)
            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <i class="ph-bold ph-footprints text-purple-600"></i> Connected Visitor Pre-Lead Journey
                    </h3>
                    <a href="{{ route('admin.visitors.show', $lead->visitor->id) }}" class="text-xs font-bold text-purple-600 hover:underline flex items-center gap-1">
                        Full Journey <i class="ph-bold ph-arrow-right"></i>
                    </a>
                </div>
                <p class="text-xs text-slate-500 mb-3">
                    Stitched anonymous history prior to lead capture (DPDP compliant).
                </p>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs mb-4">
                    <div class="p-2.5 rounded-xl bg-purple-50/50 border border-purple-100">
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Total Sessions</span>
                        <strong class="text-purple-700 text-sm">{{ $lead->visitor->sessions()->count() }}</strong>
                    </div>
                    <div class="p-2.5 rounded-xl bg-purple-50/50 border border-purple-100">
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Pages Viewed</span>
                        <strong class="text-purple-700 text-sm">{{ $lead->visitor->page_views_count ?? 0 }}</strong>
                    </div>
                    <div class="p-2.5 rounded-xl bg-purple-50/50 border border-purple-100">
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">First Visit</span>
                        <strong class="text-purple-700 text-sm">{{ $lead->visitor->created_at->format('d M Y') }}</strong>
                    </div>
                    <div class="p-2.5 rounded-xl bg-purple-50/50 border border-purple-100">
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Device Type</span>
                        <strong class="text-purple-700 text-sm capitalize">{{ $lead->visitor->latestSession->device_type ?? 'desktop' }}</strong>
                    </div>
                </div>
            </div>
            @endif

            {{-- Matching Properties Recommendations --}}
            @if($matchingProperties->count() > 0)
            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <i class="ph-bold ph-sparkle text-amber-500"></i> Matching Properties For Lead
                    </h3>
                    <span class="text-xs text-slate-500">{{ $matchingProperties->count() }} matches found</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($matchingProperties as $mp)
                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 flex flex-col justify-between">
                            <div>
                                <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-blue-100 text-blue-700 mb-1">
                                    {{ $mp->purpose }} • {{ $mp->bedrooms }} BHK
                                </span>
                                <h4 class="text-xs font-bold text-slate-900 line-clamp-1">{{ $mp->title }}</h4>
                                <p class="text-[11px] text-slate-500 mt-0.5 line-clamp-1">{{ $mp->location }}</p>
                                <p class="text-xs font-black text-slate-900 mt-1">₹{{ number_format($mp->price) }}</p>
                            </div>
                            <div class="flex items-center justify-between mt-3 pt-2 border-t border-slate-200/60">
                                <a href="{{ route('properties.show', $mp->id) }}" target="_blank" class="text-[11px] font-bold text-blue-600 hover:underline">View</a>
                                <a href="https://wa.me/91{{ $lead->phone }}?text={{ urlencode('Hi ' . $lead->name . ', I found a property matching your requirement: ' . $mp->title . ' (₹' . number_format($mp->price) . ') in ' . $mp->location . '. Details: ' . route('properties.show', $mp->id)) }}" target="_blank" class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600 hover:underline">
                                    <i class="ph-bold ph-whatsapp-logo"></i> Send on WA
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        {{-- Right Column: Status Update, Follow-ups Hub, Notes & Audit, Communication Logs --}}
        <div class="lg:col-span-5 space-y-6">

            {{-- Status & Assignment Update Form --}}
            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs">
                <h3 class="text-base font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <i class="ph-bold ph-gear text-slate-700"></i> Lead Management
                </h3>

                <form action="{{ route('admin.leads.update', $lead->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Pipeline Status</label>
                        <select name="lead_status" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white font-bold text-slate-800">
                            <option value="new" {{ $lead->lead_status === 'new' ? 'selected' : '' }}>New</option>
                            <option value="contacted" {{ $lead->lead_status === 'contacted' ? 'selected' : '' }}>Contacted</option>
                            <option value="interested" {{ $lead->lead_status === 'interested' ? 'selected' : '' }}>Interested</option>
                            <option value="scheduled_visit" {{ $lead->lead_status === 'scheduled_visit' ? 'selected' : '' }}>Visit Scheduled</option>
                            <option value="negotiation" {{ $lead->lead_status === 'negotiation' ? 'selected' : '' }}>Negotiation</option>
                            <option value="converted" {{ $lead->lead_status === 'converted' ? 'selected' : '' }}>Converted / Closed</option>
                            <option value="lost" {{ $lead->lead_status === 'lost' ? 'selected' : '' }}>Lost</option>
                            <option value="spam" {{ $lead->lead_status === 'spam' ? 'selected' : '' }}>Spam</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Intent</label>
                            <select name="intent" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white">
                                <option value="rent" {{ $lead->intent === 'rent' ? 'selected' : '' }}>Rent</option>
                                <option value="buy" {{ $lead->intent === 'buy' ? 'selected' : '' }}>Buy</option>
                                <option value="sell" {{ $lead->intent === 'sell' ? 'selected' : '' }}>Sell</option>
                                <option value="inquire" {{ $lead->intent === 'inquire' ? 'selected' : '' }}>Inquire</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Assign Staff</label>
                            <select name="assigned_to" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white">
                                <option value="">Unassigned</option>
                                @foreach($staffUsers as $su)
                                    <option value="{{ $su->id }}" {{ $lead->assigned_to == $su->id ? 'selected' : '' }}>{{ $su->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Max Budget (₹)</label>
                            <input type="number" name="budget_max" value="{{ $lead->budget_max }}" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Preferred City</label>
                            <input type="text" name="preferred_city" value="{{ $lead->preferred_city }}" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="whatsapp_opt_in" id="opt_in_cb" value="1" {{ $lead->whatsapp_opt_in ? 'checked' : '' }} class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        <label for="opt_in_cb" class="text-xs text-slate-700 font-medium">WhatsApp Opt-in Active</label>
                    </div>

                    <button type="submit" class="w-full py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition-all shadow-xs">
                        Update Lead Details
                    </button>
                </form>
            </div>

            {{-- Scheduled Follow-ups Hub --}}
            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <i class="ph-bold ph-calendar-check text-blue-600"></i> Follow-up Tasks ({{ $lead->followUps->count() }})
                    </h3>
                    <button onclick="document.getElementById('followUpModal').classList.remove('hidden')" class="text-xs font-bold text-blue-600 hover:underline">
                        + Schedule
                    </button>
                </div>

                <div class="space-y-3">
                    @forelse($lead->followUps as $fu)
                        <div class="p-3.5 rounded-2xl {{ $fu->status === 'completed' ? 'bg-slate-50 border border-slate-100 opacity-80' : ($fu->scheduled_at < now() ? 'bg-rose-50/70 border border-rose-200' : 'bg-blue-50/50 border border-blue-100') }}">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-black uppercase tracking-wider {{ $fu->status === 'completed' ? 'text-slate-500' : ($fu->scheduled_at < now() ? 'text-rose-700' : 'text-blue-700') }}">
                                    {{ $fu->follow_up_type }} • {{ $fu->scheduled_at->format('d M Y, h:i A') }}
                                </span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase {{ $fu->status === 'completed' ? 'bg-slate-200 text-slate-700' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $fu->status }}
                                </span>
                            </div>

                            @if($fu->notes)
                                <p class="text-xs text-slate-600 mt-2 whitespace-pre-line">{{ $fu->notes }}</p>
                            @endif

                            @if($fu->status === 'pending')
                                <div class="mt-3 pt-2 border-t border-slate-200/60 flex items-center justify-between">
                                    <span class="text-[11px] text-slate-500">{{ $fu->scheduled_at->diffForHumans() }}</span>
                                    <form action="{{ route('admin.follow-ups.complete', $fu->id) }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        <input type="text" name="outcome" placeholder="Outcome note..." required class="text-xs px-2 py-1 rounded-lg border border-slate-200 w-36">
                                        <button type="submit" class="text-xs font-bold text-emerald-700 bg-emerald-100 hover:bg-emerald-200 px-2.5 py-1 rounded-lg">Done</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 py-4 text-center">No follow-ups scheduled for this lead yet.</p>
                    @endforelse
                </div>
            </div>

            {{-- WhatsApp Communication History --}}
            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs">
                <h3 class="text-base font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <i class="ph-bold ph-whatsapp-logo text-emerald-600"></i> WhatsApp Log ({{ $lead->communicationLogs->count() }})
                </h3>

                <div class="space-y-3 max-h-72 overflow-y-auto pr-1">
                    @forelse($lead->communicationLogs as $clog)
                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 text-xs">
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-bold text-slate-800 capitalize">{{ $clog->direction }} • {{ $clog->provider }}</span>
                                <span class="text-[10px] text-slate-400">{{ $clog->created_at->format('d M, h:i A') }}</span>
                            </div>
                            <p class="text-slate-600 text-xs mt-1 whitespace-pre-line">{{ $clog->message_body }}</p>
                            <div class="mt-1.5 flex items-center justify-between text-[10px] text-slate-400">
                                <span>Status: <strong class="text-slate-700 capitalize">{{ $clog->status }}</strong></span>
                                @if($clog->cost_credits > 0)
                                    <span>{{ $clog->cost_credits }} credits</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 py-4 text-center">No WhatsApp messages dispatched yet.</p>
                    @endforelse
                </div>
            </div>

            {{-- Internal CRM Notes Card --}}
            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs">
                <h3 class="text-base font-bold text-slate-900 mb-3 flex items-center gap-2">
                    <i class="ph-bold ph-note-pencil text-blue-600"></i> Internal Notes
                </h3>

                <form action="{{ route('admin.leads.notes.store', $lead->id) }}" method="POST" class="space-y-2 mb-4">
                    @csrf
                    <textarea name="note" rows="3" required placeholder="Add private note about conversation or preferences..." class="w-full p-3 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-all shadow-xs">
                        Add Note
                    </button>
                </form>

                @if($lead->notes)
                    <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 text-xs text-slate-700 whitespace-pre-line max-h-56 overflow-y-auto">
                        {{ $lead->notes }}
                    </div>
                @endif
            </div>

        </div>

    </div>

    {{-- Modal: Send WhatsApp Message --}}
    <div id="whatsappModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <i class="ph-bold ph-whatsapp-logo text-emerald-600 text-lg"></i> Send WhatsApp to {{ $lead->name }}
                </h3>
                <button onclick="document.getElementById('whatsappModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                    <i class="ph-bold ph-x text-lg"></i>
                </button>
            </div>

            <form action="{{ route('admin.leads.send-whatsapp', $lead->id) }}" method="POST" class="mt-4 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Quick Templates</label>
                    <select onchange="document.getElementById('wa_msg_textarea').value = this.value" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-white">
                        <option value="">Select a template or write custom...</option>
                        <option value="Hello {{ $lead->name }}, thank you for contacting UnlockRentals. We have verified listings matching your budget. When is a convenient time to discuss?">Follow-up Greeting</option>
                        <option value="Hi {{ $lead->name }}, we have scheduled your property visit. Our representative will meet you at the site. Please let us know if you need to reschedule.">Schedule Visit Confirmation</option>
                        <option value="Hi {{ $lead->name }}, several new rental properties in {{ $lead->preferred_city ?? 'your area' }} matching your preferences were just listed on UnlockRentals! Check them here: {{ url('/') }}">New Property Alert</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Message Body *</label>
                    <textarea id="wa_msg_textarea" name="message" rows="5" required class="w-full p-3 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 focus:outline-none" placeholder="Type WhatsApp message here..."></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" onclick="document.getElementById('whatsappModal').classList.add('hidden')" class="px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-xl">Cancel</button>
                    <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs flex items-center gap-2">
                        <i class="ph-bold ph-paper-plane-right"></i> Send Now
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal: Schedule Follow-up --}}
    <div id="followUpModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <i class="ph-bold ph-calendar-plus text-blue-600 text-lg"></i> Schedule Follow-up
                </h3>
                <button onclick="document.getElementById('followUpModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                    <i class="ph-bold ph-x text-lg"></i>
                </button>
            </div>

            <form action="{{ route('admin.leads.follow-ups.store', $lead->id) }}" method="POST" class="mt-4 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Scheduled Date & Time *</label>
                    <input type="datetime-local" name="scheduled_at" required value="{{ now()->addDays(1)->format('Y-m-d\TH:i') }}" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Follow-up Type *</label>
                    <select name="follow_up_type" required class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-white">
                        <option value="call">Phone Call</option>
                        <option value="whatsapp">WhatsApp Message</option>
                        <option value="site_visit">Physical Site Visit</option>
                        <option value="meeting">Office Meeting</option>
                        <option value="email">Email</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Task Notes</label>
                    <textarea name="notes" rows="3" placeholder="Objective of follow-up (e.g. discuss deposit, negotiate price)..." class="w-full p-3 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                    <button type="button" onclick="document.getElementById('followUpModal').classList.add('hidden')" class="px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-xl">Cancel</button>
                    <button type="submit" class="px-5 py-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-xs">Schedule Follow-up</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
