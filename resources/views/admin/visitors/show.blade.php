@extends('layouts.admin')

@section('title', 'Visitor Journey #' . substr($visitor->visitor_uuid, 0, 8) . ' - UnlockRentals CRM')
@section('topbar_title', 'Visitor Journey')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">

    {{-- Breadcrumb & Back --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.visitors.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-blue-600 transition-colors">
            <i class="ph-bold ph-arrow-left"></i> Back to Visitors
        </a>
        <span class="text-xs font-mono text-slate-400">UUID: {{ $visitor->visitor_uuid }}</span>
    </div>

    {{-- Visitor Profile Header Card --}}
    <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div class="flex items-start gap-4">
            <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl flex-shrink-0 shadow-xs">
                <i class="ph-bold ph-user"></i>
            </div>
            <div>
                <div class="flex items-center gap-2.5 flex-wrap">
                    <h1 class="text-xl sm:text-2xl font-black text-slate-900">
                        @if($visitor->lead)
                            {{ $visitor->lead->name }}
                        @else
                            Visitor {{ substr($visitor->visitor_uuid, 0, 8) }}
                        @endif
                    </h1>
                    @if($visitor->lead)
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            Identified Lead
                        </span>
                    @else
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                            Anonymous Visitor
                        </span>
                    @endif
                </div>

                <div class="flex items-center gap-4 text-xs text-slate-500 mt-2 flex-wrap">
                    <span class="flex items-center gap-1.5">
                        <i class="ph-bold ph-map-pin text-rose-500"></i>
                        {{ $visitor->city ?? 'India' }}{{ !empty($visitor->state) ? ', ' . $visitor->state : '' }}
                    </span>
                    <span class="flex items-center gap-1.5">
                        <i class="ph-bold ph-device-mobile text-indigo-500"></i>
                        {{ ucfirst($visitor->device_type ?? ($visitor->latestSession->device_type ?? 'desktop')) }} ({{ $visitor->browser ?? ($visitor->latestSession->browser ?? 'Browser') }})
                    </span>
                    <span class="flex items-center gap-1.5">
                        <i class="ph-bold ph-shield-check text-emerald-600"></i>
                        Score: {{ $visitor->engagement_score ?? 0 }} pts ({{ ucfirst($visitor->engagement_tier ?? 'low') }})
                    </span>
                </div>
            </div>
        </div>

        @if($visitor->lead)
            <a href="{{ route('admin.leads.show', $visitor->lead->id) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition-all self-start md:self-auto">
                <i class="ph-bold ph-identification-card text-base"></i> View CRM Lead File
            </a>
        @endif
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
            <span class="text-xs font-bold text-slate-400 uppercase">Sessions</span>
            <p class="text-2xl font-black text-slate-900 mt-1">{{ $sessionsCount }}</p>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
            <span class="text-xs font-bold text-slate-400 uppercase">Page Views</span>
            <p class="text-2xl font-black text-blue-600 mt-1">{{ $totalPageViews }}</p>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
            <span class="text-xs font-bold text-slate-400 uppercase">Properties Viewed</span>
            <p class="text-2xl font-black text-indigo-600 mt-1">{{ $propertyViewsCount }}</p>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
            <span class="text-xs font-bold text-slate-400 uppercase">WhatsApp Clicks</span>
            <p class="text-2xl font-black text-emerald-600 mt-1">{{ $whatsappClicksCount }}</p>
        </div>
    </div>

    {{-- Viewed Properties Section --}}
    @if($viewedProperties->count() > 0)
    <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs">
        <h3 class="text-base font-bold text-slate-900 mb-4 flex items-center gap-2">
            <i class="ph-bold ph-buildings text-blue-600"></i> Properties Interacted With ({{ $viewedProperties->count() }})
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($viewedProperties as $prop)
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 flex flex-col justify-between">
                    <div>
                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-blue-100 text-blue-700 mb-1.5">
                            {{ $prop->purpose }}
                        </span>
                        <h4 class="text-xs font-bold text-slate-800 line-clamp-2">{{ $prop->title }}</h4>
                        <p class="text-[11px] text-slate-500 mt-1">{{ $prop->location }}</p>
                    </div>
                    <div class="flex items-center justify-between mt-3 pt-2 border-t border-slate-200/60">
                        <span class="text-xs font-black text-slate-900">₹{{ number_format($prop->price) }}</span>
                        <a href="{{ route('properties.show', $prop->id) }}" target="_blank" class="text-[11px] font-bold text-blue-600 hover:underline">
                            View <i class="ph-bold ph-arrow-square-out"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Chronological Journey Timeline --}}
    <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs">
        <h3 class="text-base font-bold text-slate-900 mb-6 flex items-center gap-2">
            <i class="ph-bold ph-clock-counter-clockwise text-indigo-600"></i> Chronological Activity Stream
        </h3>

        <div class="relative border-l-2 border-slate-100 ml-4 space-y-6">
            @forelse($visitor->events as $event)
                <div class="relative pl-6 group">
                    {{-- Timeline Dot --}}
                    @if($event->event_name === 'lead_submit')
                        <span class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-emerald-500 ring-4 ring-emerald-50"></span>
                    @elseif($event->event_name === 'whatsapp_click')
                        <span class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-emerald-600 ring-4 ring-emerald-50"></span>
                    @elseif($event->event_name === 'view_property')
                        <span class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-blue-600 ring-4 ring-blue-50"></span>
                    @else
                        <span class="absolute -left-[7px] top-1.5 w-3 h-3 rounded-full bg-slate-300"></span>
                    @endif

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-slate-900 uppercase tracking-wide">
                                {{ str_replace('_', ' ', $event->event_name) }}
                            </span>
                            @if($event->property)
                                <a href="{{ route('properties.show', $event->property->id) }}" target="_blank" class="text-xs text-blue-600 hover:underline font-semibold">
                                    "{{ $event->property->title }}"
                                </a>
                            @endif
                        </div>
                        <span class="text-[11px] text-slate-400">{{ $event->created_at->format('d M Y, h:i:s A') }}</span>
                    </div>

                    @if($event->page_url)
                        <p class="text-xs text-slate-500 font-mono mt-1 break-all">{{ $event->page_url }}</p>
                    @endif

                    @if(!empty($event->metadata))
                        <div class="mt-2 p-2.5 rounded-xl bg-slate-50 border border-slate-100 text-[11px] font-mono text-slate-600 max-w-xl overflow-x-auto">
                            @foreach($event->metadata as $key => $val)
                                <div><span class="text-slate-400">{{ $key }}:</span> {{ is_array($val) ? json_encode($val) : $val }}</div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @empty
                <p class="text-xs text-slate-400 pl-6">No recorded events for this visitor yet.</p>
            @endforelse
        </div>
    </div>

</div>
@endsection
