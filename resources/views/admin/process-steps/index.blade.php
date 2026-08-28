@extends('layouts.admin')

@section('title', 'Manage Process Steps - Admin')

@section('content')

<div class="py-8 lg:py-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2.5 mb-1.5">
                <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-blue-50 text-blue-600 border border-blue-100">
                    <i class="ph-bold ph-list-numbers text-base"></i>
                </span>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Process Flow Steps</h1>
            </div>
            <p class="text-sm text-slate-500">Manage, customize, and reorder the step-by-step workflow displayed on the homepage.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white hover:bg-slate-50 text-slate-700 text-sm font-semibold rounded-xl border border-slate-200 shadow-sm transition-all" title="Back to Dashboard">
                <i class="ph-bold ph-arrow-left"></i> Dashboard
            </a>
            <a href="{{ route('admin.process-steps.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#2563EB] hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm shadow-blue-500/25 hover:shadow-md transition-all active:scale-[0.98]" title="Add Process Step">
                <i class="ph-bold ph-plus text-base"></i> Add New Step
            </a>
        </div>
    </div>

    {{-- Flash Notifications --}}
    @if(session('success'))
    <div class="mb-6 flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200/80 rounded-xl text-sm text-emerald-800 shadow-sm">
        <i class="ph-fill ph-check-circle text-emerald-600 text-xl flex-shrink-0"></i>
        <span class="font-medium">{{ session('success') }}</span>
        <button onclick="this.parentElement.remove()" class="ml-auto text-emerald-400 hover:text-emerald-700 transition-colors">
            <i class="ph-bold ph-x"></i>
        </button>
    </div>
    @endif

    {{-- Main Table Card --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        
        {{-- Card Header Info Bar --}}
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Steps</span>
                <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-700">{{ $steps->count() }}</span>
            </div>
            <span class="text-xs text-slate-400">Order by display priority</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="px-6 py-3.5 w-20 text-center">Step</th>
                        <th class="px-6 py-3.5 w-28">Icon</th>
                        <th class="px-6 py-3.5">Title</th>
                        <th class="px-6 py-3.5">Description</th>
                        <th class="px-6 py-3.5 w-28 text-center">Order</th>
                        <th class="px-6 py-3.5 w-28 text-center">Status</th>
                        <th class="px-6 py-3.5 w-44 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($steps as $step)
                    <tr class="hover:bg-slate-50/60 transition-colors group">
                        
                        {{-- Step Number --}}
                        <td class="px-6 py-4 text-center">
                            <span class="inline-block px-2.5 py-1 bg-slate-100 text-slate-800 rounded-lg text-xs font-extrabold font-mono border border-slate-200/60 shadow-xs">
                                {{ $step->step_number ?? '-' }}
                            </span>
                        </td>

                        {{-- Icon Preview --}}
                        <td class="px-6 py-4">
                            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-50 to-indigo-50/50 border border-blue-100/80 flex items-center justify-center text-blue-600 shadow-xs overflow-hidden [&>svg]:w-6 [&>svg]:h-6 [&>svg]:max-w-6 [&>svg]:max-h-6 [&>img]:w-6 [&>img]:h-6 [&>img]:object-contain">
                                @if(str_starts_with(trim($step->icon_svg), '<svg'))
                                    {!! $step->icon_svg !!}
                                @elseif(Str::endsWith($step->icon_svg, ['.png', '.jpg', '.jpeg', '.gif', '.svg']) || str_contains($step->icon_svg, '/'))
                                    <img src="{{ asset($step->icon_svg) }}" alt="{{ $step->title }}" title="{{ $step->title }}" onerror="this.parentElement.innerHTML='<i class=\'ph-bold ph-sparkle text-lg\'></i>'">
                                @elseif($step->icon_svg)
                                    <i class="{{ $step->icon_svg }} text-xl"></i>
                                @else
                                    <i class="ph-bold ph-sparkle text-lg text-slate-400"></i>
                                @endif
                            </div>
                        </td>

                        {{-- Title --}}
                        <td class="px-6 py-4 font-bold text-slate-900">
                            {{ $step->title }}
                        </td>

                        {{-- Description --}}
                        <td class="px-6 py-4 text-slate-600 max-w-sm">
                            <p class="line-clamp-2 leading-relaxed text-xs">{{ $step->description }}</p>
                        </td>

                        {{-- Sort Order --}}
                        <td class="px-6 py-4 text-center">
                            <span class="font-mono text-xs font-semibold text-slate-600 bg-slate-100/80 px-2 py-0.5 rounded">
                                {{ $step->sort_order }}
                            </span>
                        </td>

                        {{-- Status --}}
                        <td class="px-6 py-4 text-center">
                            @if($step->is_active)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                    Inactive
                                </span>
                            @endif
                        </td>

                        {{-- Action Buttons --}}
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.process-steps.edit', $step) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-semibold rounded-lg border border-blue-200/60 transition-colors" title="Edit Step">
                                    <i class="ph-bold ph-pencil-simple text-xs"></i> Edit
                                </a>
                                <form method="POST" action="{{ route('admin.process-steps.destroy', $step) }}" onsubmit="return confirm('Are you sure you want to delete step: {{ addslashes($step->title) }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold rounded-lg border border-red-200/60 transition-colors" title="Delete Step">
                                        <i class="ph-bold ph-trash text-xs"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="w-12 h-12 mx-auto rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center mb-3">
                                <i class="ph-bold ph-list-numbers text-2xl"></i>
                            </div>
                            <h3 class="text-sm font-bold text-slate-800 mb-1">No process steps found</h3>
                            <p class="text-xs text-slate-500 mb-4">Add the first step to display your customer onboarding workflow on the homepage.</p>
                            <a href="{{ route('admin.process-steps.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg shadow-sm transition-all" title="Add First Step">
                                <i class="ph-bold ph-plus"></i> Add First Step
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
