@extends('layouts.admin')

@section('title', 'Manage Process Steps - Admin')

@section('content')

<section class="py-8 lg:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-medium text-zinc-900 mb-1">Manage Process Steps</h1>
                <p class="text-zinc-500 text-sm">Create, edit, and order process flow steps displayed on the homepage.</p>
            </div>
            <a href="{{ route('admin.process-steps.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#c9a050] hover:bg-[#b08d42] text-white text-sm font-semibold rounded-sm transition-all">
                <i class="ph ph-plus"></i> Add Process Step
            </a>
        </div>

        @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-sm text-sm text-emerald-700">
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-white border border-stone-200 rounded-sm overflow-hidden shadow-sm">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-stone-200 bg-stone-50">
                        <th class="text-left px-6 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wider w-20">Number</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wider w-24">Icon Preview</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Title</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Description</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wider w-24">Sort Order</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wider w-24">Status</th>
                        <th class="text-right px-6 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wider w-36">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($steps as $step)
                    <tr class="hover:bg-stone-50/50 transition-colors">
                        <td class="px-6 py-4 font-semibold text-zinc-950">
                            {{ $step->step_number ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="w-12 h-12 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 overflow-hidden">
                                @if(str_starts_with(trim($step->icon_svg), '<svg'))
                                    <div class="scale-75 flex items-center justify-center">{!! $step->icon_svg !!}</div>
                                @elseif($step->icon_svg)
                                    <i class="{{ $step->icon_svg }} text-2xl"></i>
                                @else
                                    <span class="text-xs text-zinc-400">None</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 font-medium text-zinc-900">
                            {{ $step->title }}
                        </td>
                        <td class="px-6 py-4 text-zinc-600 max-w-xs truncate">
                            {{ $step->description }}
                        </td>
                        <td class="px-6 py-4 text-zinc-700 font-mono">
                            {{ $step->sort_order }}
                        </td>
                        <td class="px-6 py-4">
                            @if($step->is_active)
                                <span class="px-2.5 py-0.5 bg-emerald-50 text-emerald-700 text-[10px] font-bold rounded-full uppercase border border-emerald-200">Active</span>
                            @else
                                <span class="px-2.5 py-0.5 bg-zinc-100 text-zinc-500 text-[10px] font-bold rounded-full uppercase border border-zinc-200">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.process-steps.edit', $step) }}" class="px-3 py-1.5 bg-[#2563EB]/10 text-[#2563EB] text-xs font-semibold rounded-sm hover:bg-[#2563EB]/20 transition-all">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.process-steps.destroy', $step) }}" onsubmit="return confirm('Are you sure you want to delete this step?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 bg-red-500/10 text-red-500 text-xs font-semibold rounded-sm hover:bg-red-500/20 transition-all">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-zinc-400">No process steps found. Click 'Add Process Step' to create one.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            <a href="{{ route('admin.dashboard') }}" class="text-sm text-zinc-500 hover:text-[#2563EB]">← Back to Dashboard</a>
        </div>
    </div>
</section>

@endsection
