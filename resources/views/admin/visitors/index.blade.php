@extends('layouts.admin')

@section('title', 'Visitor Tracking & Analytics - UnlockRentals CRM')
@section('topbar_title', 'Visitor Tracking & Analytics')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">

    {{-- Top Header & Time Filter --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Visitor Analytics</h1>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                    <span class="w-2 h-2 rounded-full bg-blue-500 animate-ping"></span> Live Tracking
                </span>
            </div>
            <p class="text-sm text-slate-500 mt-1">Privacy-safe anonymous visitor intelligence, property engagement, and conversion telemetry.</p>
        </div>

        {{-- Date Range Selector --}}
        <div class="flex items-center gap-2 bg-slate-100 p-1.5 rounded-2xl border border-slate-200 self-start md:self-auto">
            <a href="{{ route('admin.visitors.index', ['range' => 1]) }}" class="px-3 py-1.5 text-xs font-bold rounded-xl transition-all {{ $days == 1 ? 'bg-white text-blue-600 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">Today</a>
            <a href="{{ route('admin.visitors.index', ['range' => 7]) }}" class="px-3 py-1.5 text-xs font-bold rounded-xl transition-all {{ $days == 7 ? 'bg-white text-blue-600 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">Last 7 Days</a>
            <a href="{{ route('admin.visitors.index', ['range' => 30]) }}" class="px-3 py-1.5 text-xs font-bold rounded-xl transition-all {{ $days == 30 ? 'bg-white text-blue-600 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">Last 30 Days</a>
            <a href="{{ route('admin.visitors.index', ['range' => 90]) }}" class="px-3 py-1.5 text-xs font-bold rounded-xl transition-all {{ $days == 90 ? 'bg-white text-blue-600 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">90 Days</a>
        </div>
    </div>

    {{-- KPI Metric Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        {{-- Card 1: Unique Visitors --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:border-blue-300 transition-all">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center mb-3">
                <i class="ph-bold ph-users-three text-xl"></i>
            </div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Unique Visitors</p>
            <h3 class="text-2xl font-black text-slate-900 mt-1">{{ number_format($totalVisitors) }}</h3>
            <span class="text-[11px] text-slate-500 font-medium">Last {{ $days }} days</span>
        </div>

        {{-- Card 2: Total Sessions --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:border-indigo-300 transition-all">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-3">
                <i class="ph-bold ph-browser text-xl"></i>
            </div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Sessions</p>
            <h3 class="text-2xl font-black text-slate-900 mt-1">{{ number_format($totalSessions) }}</h3>
            <span class="text-[11px] text-indigo-600 font-bold">{{ $totalVisitors > 0 ? round($totalSessions / $totalVisitors, 1) : 0 }} sessions / visitor</span>
        </div>

        {{-- Card 3: Leads Captured --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:border-emerald-300 transition-all">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-3">
                <i class="ph-bold ph-identification-card text-xl"></i>
            </div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Leads Generated</p>
            <h3 class="text-2xl font-black text-emerald-600 mt-1">{{ number_format($totalLeads) }}</h3>
            <span class="text-[11px] text-emerald-600 font-bold">Consented leads</span>
        </div>

        {{-- Card 4: Conversion Rate --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:border-purple-300 transition-all">
            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center mb-3">
                <i class="ph-bold ph-chart-line-up text-xl"></i>
            </div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Conversion Rate</p>
            <h3 class="text-2xl font-black text-slate-900 mt-1">{{ $conversionRate }}%</h3>
            <span class="text-[11px] text-purple-600 font-bold">Visitor → Lead</span>
        </div>

        {{-- Card 5: Avg Duration --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:border-amber-300 transition-all">
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center mb-3">
                <i class="ph-bold ph-clock text-xl"></i>
            </div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Avg Session</p>
            <h3 class="text-2xl font-black text-slate-900 mt-1">
                @if($avgDurationSeconds >= 60)
                    {{ floor($avgDurationSeconds / 60) }}m {{ $avgDurationSeconds % 60 }}s
                @else
                    {{ $avgDurationSeconds }}s
                @endif
            </h3>
            <span class="text-[11px] text-slate-500 font-medium">Time on site</span>
        </div>

        {{-- Card 6: Bounce Rate --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs hover:border-rose-300 transition-all">
            <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center mb-3">
                <i class="ph-bold ph-arrow-u-up-left text-xl"></i>
            </div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Bounce Rate</p>
            <h3 class="text-2xl font-black text-slate-900 mt-1">{{ $bounceRate }}%</h3>
            <span class="text-[11px] text-slate-500 font-medium">Single-page exits</span>
        </div>
    </div>

    {{-- Main Trend Chart --}}
    <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-lg font-bold text-slate-900">Traffic & Lead Growth Trends</h3>
                <p class="text-xs text-slate-500">Daily unique visitors, sessions, and captured CRM leads</p>
            </div>
            <div class="flex items-center gap-4 text-xs font-semibold">
                <span class="inline-flex items-center gap-1.5 text-blue-600">
                    <span class="w-3 h-3 rounded-full bg-blue-600"></span> Unique Visitors
                </span>
                <span class="inline-flex items-center gap-1.5 text-indigo-400">
                    <span class="w-3 h-3 rounded-full bg-indigo-400"></span> Total Sessions
                </span>
                <span class="inline-flex items-center gap-1.5 text-emerald-600">
                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span> Consented Leads
                </span>
            </div>
        </div>
        <div class="h-72 w-full">
            <canvas id="trendChart"></canvas>
        </div>
    </div>

    {{-- 3-Column Breakdown: Sources, Devices, Top Cities --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Traffic Channels --}}
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <i class="ph-bold ph-share-network text-blue-600"></i> Traffic Sources
                </h3>
            </div>
            <div class="space-y-3 flex-1">
                @php $totalSourceVisits = $sourceBreakdown->sum('count'); @endphp
                @forelse($sourceBreakdown as $sb)
                    @php $pct = $totalSourceVisits > 0 ? round(($sb->count / $totalSourceVisits) * 100, 1) : 0; @endphp
                    <div>
                        <div class="flex items-center justify-between text-xs font-semibold text-slate-700 mb-1">
                            <span>{{ $sb->source_channel }}</span>
                            <span class="text-slate-500">{{ number_format($sb->count) }} ({{ $pct }}%)</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 py-6 text-center">No traffic source data available yet.</p>
                @endforelse
            </div>
        </div>

        {{-- Device Distribution --}}
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <i class="ph-bold ph-device-mobile text-indigo-600"></i> Devices & Platforms
                </h3>
            </div>
            <div class="space-y-4 flex-1">
                @php $totalDeviceCount = $deviceBreakdown->sum('count'); @endphp
                @forelse($deviceBreakdown as $dev)
                    @php $pct = $totalDeviceCount > 0 ? round(($dev->count / $totalDeviceCount) * 100, 1) : 0; @endphp
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center flex-shrink-0">
                            @if(strtolower($dev->device) === 'mobile')
                                <i class="ph-bold ph-device-mobile text-xl text-blue-600"></i>
                            @elseif(strtolower($dev->device) === 'tablet')
                                <i class="ph-bold ph-device-tablet text-xl text-purple-600"></i>
                            @else
                                <i class="ph-bold ph-desktop text-xl text-slate-700"></i>
                            @endif
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between text-xs font-bold text-slate-800 mb-1">
                                <span class="capitalize">{{ $dev->device }}</span>
                                <span>{{ $pct }}%</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 py-6 text-center">No device data available yet.</p>
                @endforelse
            </div>
        </div>

        {{-- Top Cities in India --}}
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <i class="ph-bold ph-map-pin text-rose-600"></i> Top Visitor Locations
                </h3>
            </div>
            <div class="space-y-2.5 flex-1">
                @forelse($topCities as $tc)
                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-100 text-xs">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                            <span class="font-bold text-slate-800">{{ $tc->city }}</span>
                            @if($tc->state)
                                <span class="text-slate-400 font-medium">({{ $tc->state }})</span>
                            @endif
                        </div>
                        <span class="font-extrabold text-slate-700">{{ number_format($tc->total_visits) }} visits</span>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 py-6 text-center">Location tracking will populate automatically from visits.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Top Viewed Properties --}}
    @if($topProperties->count() > 0)
    <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs">
        <h3 class="text-base font-bold text-slate-900 mb-4 flex items-center gap-2">
            <i class="ph-bold ph-eye text-emerald-600"></i> Most Viewed Properties
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($topProperties as $tp)
                <a href="{{ route('properties.show', $tp->id) }}" target="_blank" class="group block p-3.5 rounded-2xl bg-slate-50 border border-slate-100 hover:border-blue-300 hover:bg-blue-50/40 transition-all">
                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-blue-100 text-blue-700 mb-2">
                        {{ $tp->purpose }}
                    </span>
                    <h4 class="text-xs font-bold text-slate-800 line-clamp-1 group-hover:text-blue-600 transition-colors">{{ $tp->title }}</h4>
                    <p class="text-[11px] text-slate-500 line-clamp-1 mt-0.5">{{ $tp->location }}</p>
                    <div class="flex items-center justify-between mt-2 pt-2 border-t border-slate-200/60">
                        <span class="text-xs font-black text-slate-900">₹{{ number_format($tp->price) }}</span>
                        <span class="text-[11px] font-bold text-emerald-600 flex items-center gap-1">
                            <i class="ph-bold ph-eye"></i> {{ $tp->views_count }}
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Filterable Recent Visitors Table --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        {{-- Table Toolbar --}}
        <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-slate-900">Recent Visitors Stream</h3>
                <p class="text-xs text-slate-500">Live feed of site sessions, device footprints, and lead conversions</p>
            </div>

            <form method="GET" action="{{ route('admin.visitors.index') }}" class="flex flex-wrap items-center gap-2">
                <input type="hidden" name="range" value="{{ $days }}">

                <div class="relative">
                    <i class="ph-bold ph-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-sm"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search UUID, IP, City..." class="pl-9 pr-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent w-48 sm:w-60">
                </div>

                <select name="filter_converted" onchange="this.form.submit()" class="text-xs py-2 px-3 rounded-xl border border-slate-200 bg-white text-slate-700 focus:outline-none">
                    <option value="">All Visitors</option>
                    <option value="yes" {{ request('filter_converted') === 'yes' ? 'selected' : '' }}>Converted Leads Only</option>
                    <option value="no" {{ request('filter_converted') === 'no' ? 'selected' : '' }}>Anonymous Only</option>
                </select>

                <select name="device" onchange="this.form.submit()" class="text-xs py-2 px-3 rounded-xl border border-slate-200 bg-white text-slate-700 focus:outline-none">
                    <option value="">All Devices</option>
                    <option value="mobile" {{ request('device') === 'mobile' ? 'selected' : '' }}>Mobile</option>
                    <option value="desktop" {{ request('device') === 'desktop' ? 'selected' : '' }}>Desktop</option>
                    <option value="tablet" {{ request('device') === 'tablet' ? 'selected' : '' }}>Tablet</option>
                </select>

                @if(request()->hasAny(['search', 'filter_converted', 'device']))
                    <a href="{{ route('admin.visitors.index', ['range' => $days]) }}" class="px-3 py-2 text-xs font-semibold text-rose-600 bg-rose-50 rounded-xl hover:bg-rose-100">Clear</a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3.5">Visitor Identifier</th>
                        <th class="px-6 py-3.5">Location & Device</th>
                        <th class="px-6 py-3.5">First Seen / Last Active</th>
                        <th class="px-6 py-3.5">Engagement</th>
                        <th class="px-6 py-3.5">Status</th>
                        <th class="px-6 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($visitors as $v)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-mono font-bold text-slate-900">{{ substr($v->visitor_uuid, 0, 13) }}...</div>
                                <div class="text-[11px] text-slate-400 font-mono">{{ $v->ip_address ? substr($v->ip_address, 0, 10) . '***' : 'Masked IP' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800">
                                    {{ $v->city ?? ($v->latestSession->city ?? 'India') }}
                                    @if(!empty($v->state))
                                        <span class="text-slate-400 font-normal">, {{ $v->state }}</span>
                                    @endif
                                </div>
                                <div class="text-[11px] text-slate-500 capitalize flex items-center gap-1 mt-0.5">
                                    <i class="ph-bold ph-device-mobile"></i>
                                    {{ $v->device_type ?? ($v->latestSession->device_type ?? 'desktop') }} ({{ $v->browser ?? 'Browser' }})
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-800">{{ $v->last_seen_at ? $v->last_seen_at->diffForHumans() : '-' }}</div>
                                <div class="text-[11px] text-slate-400">First: {{ $v->created_at->format('d M Y') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 font-bold text-[11px]">
                                        {{ $v->total_page_views ?? 0 }} views
                                    </span>
                                    <span class="px-2 py-0.5 rounded-md bg-purple-50 text-purple-700 font-bold text-[11px]">
                                        Score: {{ $v->engagement_score ?? 0 }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($v->lead)
                                    <a href="{{ route('admin.leads.show', $v->lead->id) }}" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 transition-colors">
                                        <i class="ph-fill ph-check-circle"></i> {{ $v->lead->name }}
                                    </a>
                                @elseif($v->has_converted_lead)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Converted
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-medium bg-slate-100 text-slate-600">
                                        Anonymous
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.visitors.show', $v->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-50 text-blue-700 hover:bg-blue-600 hover:text-white text-xs font-bold transition-all">
                                    <span>Journey</span> <i class="ph-bold ph-arrow-right text-xs"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                    <i class="ph-bold ph-magnifying-glass text-2xl"></i>
                                </div>
                                <p class="text-sm font-semibold text-slate-600">No visitors found for this criteria.</p>
                                <p class="text-xs text-slate-400 mt-1">Visitors will automatically appear here once tracking telemetry is active.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($visitors->hasPages())
        <div class="p-5 border-t border-slate-100">
            {{ $visitors->links() }}
        </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('trendChart');
    if (!ctx) return;

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [
                {
                    label: 'Unique Visitors',
                    data: {!! json_encode($chartVisitors) !!},
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.08)',
                    tension: 0.35,
                    fill: true,
                    borderWidth: 2,
                    pointRadius: 3,
                    pointHoverRadius: 6,
                },
                {
                    label: 'Total Sessions',
                    data: {!! json_encode($chartSessions) !!},
                    borderColor: '#818cf8',
                    backgroundColor: 'transparent',
                    borderDash: [5, 5],
                    tension: 0.35,
                    borderWidth: 2,
                    pointRadius: 2,
                },
                {
                    label: 'Captured Leads',
                    data: {!! json_encode($chartLeads) !!},
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.35,
                    fill: true,
                    borderWidth: 2.5,
                    pointRadius: 4,
                    pointHoverRadius: 7,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    titleColor: '#ffffff',
                    bodyColor: '#cbd5e1',
                    padding: 12,
                    cornerRadius: 10,
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 11 }, color: '#94a3b8' }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' },
                    ticks: { font: { size: 11 }, color: '#94a3b8', precision: 0 }
                }
            }
        }
    });
});
</script>
@endpush
