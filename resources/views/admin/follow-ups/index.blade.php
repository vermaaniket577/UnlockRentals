@extends('layouts.admin')

@section('title', 'Follow-ups Hub - UnlockRentals CRM')
@section('topbar_title', 'Follow-ups Hub')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    {{-- Top Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Follow-ups Hub</h1>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                    Task Pipeline
                </span>
            </div>
            <p class="text-sm text-slate-500 mt-1">Never lose a hot tenant or buyer. Track scheduled calls, site visits, and WhatsApp engagements.</p>
        </div>
    </div>

    {{-- Navigation Tabs --}}
    <div class="flex items-center gap-2 border-b border-slate-200 pb-1 overflow-x-auto">
        <a href="{{ route('admin.follow-ups.index', ['tab' => 'today']) }}" class="flex items-center gap-2 px-4 py-2.5 rounded-2xl text-xs font-bold transition-all {{ $tab === 'today' ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
            <i class="ph-bold ph-sun text-sm"></i>
            <span>Due Today</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $tab === 'today' ? 'bg-blue-700 text-white' : 'bg-slate-200 text-slate-700' }}">{{ $countToday }}</span>
        </a>

        <a href="{{ route('admin.follow-ups.index', ['tab' => 'overdue']) }}" class="flex items-center gap-2 px-4 py-2.5 rounded-2xl text-xs font-bold transition-all {{ $tab === 'overdue' ? 'bg-rose-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
            <i class="ph-bold ph-warning-circle text-sm"></i>
            <span>Overdue</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $tab === 'overdue' ? 'bg-rose-700 text-white' : ($countOverdue > 0 ? 'bg-rose-100 text-rose-700' : 'bg-slate-200 text-slate-700') }}">{{ $countOverdue }}</span>
        </a>

        <a href="{{ route('admin.follow-ups.index', ['tab' => 'upcoming']) }}" class="flex items-center gap-2 px-4 py-2.5 rounded-2xl text-xs font-bold transition-all {{ $tab === 'upcoming' ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
            <i class="ph-bold ph-calendar-check text-sm"></i>
            <span>Upcoming</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $tab === 'upcoming' ? 'bg-indigo-700 text-white' : 'bg-slate-200 text-slate-700' }}">{{ $countUpcoming }}</span>
        </a>

        <a href="{{ route('admin.follow-ups.index', ['tab' => 'completed']) }}" class="flex items-center gap-2 px-4 py-2.5 rounded-2xl text-xs font-bold transition-all {{ $tab === 'completed' ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
            <i class="ph-bold ph-check-circle text-sm"></i>
            <span>Completed</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $tab === 'completed' ? 'bg-emerald-700 text-white' : 'bg-slate-200 text-slate-700' }}">{{ $countCompleted }}</span>
        </a>
    </div>

    {{-- Follow-ups List --}}
    <div class="space-y-4">
        @forelse($followUps as $fu)
            <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row md:items-center md:justify-between gap-4 hover:border-blue-300 transition-all">
                {{-- Lead & Type Info --}}
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-xl flex-shrink-0 {{ $fu->status === 'completed' ? 'bg-emerald-50 text-emerald-600' : ($fu->scheduled_at < now() ? 'bg-rose-50 text-rose-600' : 'bg-blue-50 text-blue-600') }}">
                        @if($fu->follow_up_type === 'call')
                            <i class="ph-bold ph-phone-call"></i>
                        @elseif($fu->follow_up_type === 'whatsapp')
                            <i class="ph-bold ph-whatsapp-logo"></i>
                        @elseif($fu->follow_up_type === 'site_visit')
                            <i class="ph-bold ph-buildings"></i>
                        @else
                            <i class="ph-bold ph-calendar-check"></i>
                        @endif
                    </div>

                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <a href="{{ route('admin.leads.show', $fu->lead->id) }}" class="text-sm font-black text-slate-900 hover:text-blue-600 transition-colors">
                                {{ $fu->lead->name }}
                            </a>
                            <span class="text-xs font-mono font-bold text-slate-500">{{ $fu->lead->phone }}</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-slate-100 text-slate-700">
                                {{ $fu->follow_up_type }}
                            </span>
                        </div>

                        <div class="text-xs text-slate-500 mt-1 flex items-center gap-3">
                            <span>Scheduled: <strong class="text-slate-800">{{ $fu->scheduled_at->format('d M Y, h:i A') }}</strong></span>
                            <span>({{ $fu->scheduled_at->diffForHumans() }})</span>
                            @if($fu->assignedTo)
                                <span>• Agent: <strong>{{ $fu->assignedTo->name }}</strong></span>
                            @endif
                        </div>

                        @if($fu->notes)
                            <p class="text-xs text-slate-600 mt-2 bg-slate-50 p-2.5 rounded-xl border border-slate-100 whitespace-pre-line">
                                {{ $fu->notes }}
                            </p>
                        @endif
                    </div>
                </div>

                {{-- Action / Complete Section --}}
                <div class="flex items-center gap-3 self-end md:self-auto flex-shrink-0">
                    @if($fu->status === 'pending')
                        <form action="{{ route('admin.follow-ups.complete', $fu->id) }}" method="POST" class="flex items-center gap-2">
                            @csrf
                            <input type="text" name="outcome" placeholder="Outcome (e.g. called, agreed to visit)..." required class="text-xs px-3 py-2 rounded-xl border border-slate-200 w-48 sm:w-64 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <button type="submit" class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-xs transition-all flex items-center gap-1.5">
                                <i class="ph-bold ph-check"></i> Complete
                            </button>
                        </form>
                    @else
                        <span class="px-3 py-1.5 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            Completed {{ $fu->completed_at ? $fu->completed_at->diffForHumans() : '' }}
                        </span>
                    @endif

                    <a href="{{ route('admin.leads.show', $fu->lead->id) }}" class="p-2 text-slate-400 hover:text-slate-900 rounded-xl hover:bg-slate-100 transition-colors" title="View Lead Dossier">
                        <i class="ph-bold ph-arrow-right text-lg"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="bg-white p-12 rounded-3xl border border-slate-200/80 shadow-xs text-center text-slate-400">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                    <i class="ph-bold ph-calendar-check text-2xl"></i>
                </div>
                <p class="text-sm font-semibold text-slate-600">No follow-ups found in this tab.</p>
                <p class="text-xs text-slate-400 mt-1">Schedule follow-ups from any lead's dossier to populate your pipeline.</p>
            </div>
        @endforelse

        @if($followUps->hasPages())
        <div class="p-4 bg-white rounded-2xl border border-slate-200/80">
            {{ $followUps->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
