@extends('layouts.admin')

@section('title', ($step ? 'Edit' : 'Create') . ' Process Step - Admin')

@section('content')

<div class="py-8 lg:py-10 max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Breadcrumbs & Header --}}
    <div class="mb-8">
        <div class="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">
            <a href="{{ route('admin.process-steps') }}" class="hover:text-blue-600 transition-colors" title="Process Steps">Process Steps</a>
            <i class="ph-bold ph-caret-right text-[10px]"></i>
            <span class="text-slate-700">{{ $step ? 'Edit Step' : 'New Step' }}</span>
        </div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">{{ $step ? 'Edit Process Step' : 'Create New Process Step' }}</h1>
        <p class="text-sm text-slate-500 mt-1">{{ $step ? 'Update step title, description, icon, or order.' : 'Add a new workflow step to show on your homepage.' }}</p>
    </div>

    {{-- Errors Banner --}}
    @if($errors->any())
    <div class="mb-6 p-4 bg-red-50 border border-red-200/80 rounded-xl text-sm text-red-700 shadow-sm">
        <div class="flex items-center gap-2 font-bold mb-1 text-red-800">
            <i class="ph-bold ph-warning-circle text-base"></i> Please fix the following errors:
        </div>
        <ul class="list-disc list-inside space-y-1 text-xs">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Main Form Card --}}
    <form method="POST" action="{{ $step ? route('admin.process-steps.update', $step) : route('admin.process-steps.store') }}"
          class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-8 space-y-6 shadow-sm">
        @csrf
        @if($step) @method('PUT') @endif

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            {{-- Step Number --}}
            <div class="sm:col-span-1">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Step Number</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 font-mono font-bold text-xs">#</span>
                    <input type="text" name="step_number" value="{{ old('step_number', $step->step_number ?? '') }}"
                           class="w-full pl-8 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all font-mono"
                           placeholder="01">
                </div>
            </div>

            {{-- Title --}}
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Step Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title', $step->title ?? '') }}" required
                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all"
                       placeholder="e.g. Discover, Concierge, Finalize">
            </div>
        </div>

        {{-- Description --}}
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Description</label>
            <textarea name="description" rows="3"
                      class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all resize-none"
                      placeholder="Describe what the customer does during this step...">{{ old('description', $step->description ?? '') }}</textarea>
        </div>

        {{-- Icon --}}
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Icon (SVG Code, Asset Path, or Phosphor Class)</label>
            <textarea name="icon_svg" rows="4"
                      class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono text-slate-800 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all"
                      placeholder="e.g. <svg viewBox='0 0 24 24'>...</svg> or images/icons/discover.svg or ph ph-compass">{{ old('icon_svg', $step->icon_svg ?? '') }}</textarea>
            <p class="text-xs text-slate-400 mt-1.5 flex items-center gap-1">
                <i class="ph-bold ph-info text-blue-500"></i> Accepts raw inline SVG XML, image asset relative path, or Phosphor icon class.
            </p>
        </div>

        {{-- Sort Order & Active Toggle --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-2">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Sort Order</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $step->sort_order ?? 0) }}"
                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 focus:bg-white focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all">
            </div>

            <div class="flex items-center pt-6">
                <label class="flex items-center gap-3 cursor-pointer select-none">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $step->is_active ?? true) ? 'checked' : '' }}
                           class="w-5 h-5 rounded-lg text-blue-600 focus:ring-blue-500 border-slate-300 transition-colors">
                    <span class="text-sm font-bold text-slate-800">Active (Visible on Homepage)</span>
                </label>
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="flex items-center gap-3 pt-6 border-t border-slate-100">
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#2563EB] hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-sm shadow-blue-500/25 hover:shadow-md transition-all active:scale-[0.98]">
                <i class="ph-bold ph-check"></i> {{ $step ? 'Save Changes' : 'Create Step' }}
            </button>
            <a href="{{ route('admin.process-steps') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition-all" title="Cancel">
                Cancel
            </a>
        </div>
    </form>

</div>

@endsection
