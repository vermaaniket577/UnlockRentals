@extends('layouts.app')

@section('title', 'Availability Schedule | Professional Dashboard | UnlockRentals')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-900 pb-20 pt-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <nav class="flex items-center text-xs text-slate-500 mb-6 gap-2">
            <a href="{{ route('professional.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
            <i class="ph ph-caret-right text-[10px]"></i>
            <span class="text-slate-900 dark:text-white font-medium">Availability</span>
        </nav>

        <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-200 dark:border-slate-800">
            <div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white">Working Hours & Availability</h1>
                <p class="text-xs text-slate-500 mt-0.5">Set when you are available to accept customer service requests</p>
            </div>
            <a href="{{ route('professional.dashboard') }}" class="px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-600 text-xs font-bold text-slate-700 dark:text-slate-200">
                Back to Dashboard
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 text-emerald-800 text-xs font-semibold flex items-center gap-2">
                <i class="ph-fill ph-check-circle text-base text-emerald-600"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <form action="{{ route('professional.availability.update') }}" method="POST" class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 border border-slate-200/80 dark:border-slate-700/80 shadow-sm space-y-6">
            @csrf

            {{-- Quick Real-time Toggles --}}
            <div class="p-4 rounded-2xl bg-blue-50/50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-900/40 flex flex-wrap gap-6">
                <label class="flex items-center gap-2 text-xs font-bold text-slate-800 dark:text-slate-200 cursor-pointer">
                    <input type="checkbox" name="available_today" value="1" {{ $professional->available_today ? 'checked' : '' }} class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    <span>⚡ Available for Service Today</span>
                </label>

                <label class="flex items-center gap-2 text-xs font-bold text-slate-800 dark:text-slate-200 cursor-pointer">
                    <input type="checkbox" name="emergency_service" value="1" {{ $professional->emergency_service ? 'checked' : '' }} class="rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                    <span>🚨 24x7 Emergency Service Provider</span>
                </label>
            </div>

            {{-- Weekly Day-by-Day Schedule --}}
            <div class="space-y-3">
                <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-2">Weekly Schedule</h3>

                @foreach($days as $day)
                    @php $sched = $schedules[$day] ?? null; @endphp
                    <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-700/40 border border-slate-200/60 dark:border-slate-700 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <label class="flex items-center gap-2.5 text-xs font-bold text-slate-800 dark:text-slate-200 cursor-pointer sm:w-36">
                            <input type="checkbox" name="avail_{{ $day }}" value="1" {{ ($sched ? $sched->is_available : ($day !== 'Sunday')) ? 'checked' : '' }} class="rounded border-slate-300 text-blue-600">
                            <span>{{ $day }}</span>
                        </label>

                        <div class="flex items-center gap-3 text-xs">
                            <div class="flex items-center gap-1.5">
                                <span class="text-[11px] text-slate-400">Open:</span>
                                <input type="time" name="start_{{ $day }}" value="{{ $sched ? substr($sched->start_time, 0, 5) : '09:00' }}" class="text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-800 dark:text-white py-1 px-2">
                            </div>
                            <span class="text-slate-400">to</span>
                            <div class="flex items-center gap-1.5">
                                <span class="text-[11px] text-slate-400">Close:</span>
                                <input type="time" name="end_{{ $day }}" value="{{ $sched ? substr($sched->end_time, 0, 5) : '20:00' }}" class="text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-800 dark:text-white py-1 px-2">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="submit" class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-600/25 transition-all">
                Save Availability Schedule
            </button>
        </form>

    </div>
</div>
@endsection
