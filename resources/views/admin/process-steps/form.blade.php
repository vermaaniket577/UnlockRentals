@extends('layouts.admin')

@section('title', ($step ? 'Edit' : 'Create') . ' Process Step - Admin')

@section('content')

<section class="py-8 lg:py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-8">
            <h1 class="text-2xl font-medium text-zinc-900 mb-1">{{ $step ? 'Edit Process Step' : 'Create New Process Step' }}</h1>
            <p class="text-zinc-500 text-sm">{{ $step ? 'Update process step details below.' : 'Set up a new process step to display on the homepage.' }}</p>
        </div>

        @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-sm">
            @foreach($errors->all() as $error)
                <p class="text-sm text-red-700">{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <form method="POST" action="{{ $step ? route('admin.process-steps.update', $step) : route('admin.process-steps.store') }}"
              class="bg-white border border-stone-200 rounded-sm p-6 space-y-5 shadow-sm">
            @csrf
            @if($step) @method('PUT') @endif

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-1">
                    <label class="block text-sm font-semibold text-zinc-700 mb-1.5">Step Number</label>
                    <input type="text" name="step_number" value="{{ old('step_number', $step->step_number ?? '') }}"
                           class="w-full px-4 py-2.5 border border-stone-200 rounded-sm text-sm text-zinc-900 focus:outline-none focus:border-[#c9a050]"
                           placeholder="e.g. 01, 02, 03">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-zinc-700 mb-1.5">Title *</label>
                    <input type="text" name="title" value="{{ old('title', $step->title ?? '') }}" required
                           class="w-full px-4 py-2.5 border border-stone-200 rounded-sm text-sm text-zinc-900 focus:outline-none focus:border-[#c9a050]"
                           placeholder="e.g. Discover, Concierge, Finalize">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-zinc-700 mb-1.5">Description</label>
                <textarea name="description" rows="3"
                          class="w-full px-4 py-2.5 border border-stone-200 rounded-sm text-sm text-zinc-900 focus:outline-none focus:border-[#c9a050] resize-none"
                          placeholder="What should users do in this step?">{{ old('description', $step->description ?? '') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-zinc-700 mb-1.5">Icon (Raw SVG Code OR Phosphor Icon Class)</label>
                <textarea name="icon_svg" rows="4"
                          class="w-full px-4 py-2.5 border border-stone-200 rounded-sm text-sm font-mono text-zinc-900 focus:outline-none focus:border-[#c9a050]"
                          placeholder="e.g. <svg viewBox='0 0 24 24'>...</svg>&#11;OR: ph ph-magnifying-glass">{{ old('icon_svg', $step->icon_svg ?? '') }}</textarea>
                <p class="text-xs text-zinc-400 mt-1">
                    Input raw SVG code (with width, height, and fill/stroke set appropriately) or a valid Phosphor icon class like <code>ph ph-magnifying-glass</code>.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-zinc-700 mb-1.5">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $step->sort_order ?? 0) }}"
                           class="w-full px-4 py-2.5 border border-stone-200 rounded-sm text-sm text-zinc-900 focus:outline-none focus:border-[#c9a050]">
                </div>
                <div class="flex items-center pt-5">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $step->is_active ?? true) ? 'checked' : '' }}
                               class="w-4 h-4 accent-[#c9a050]">
                        <span class="text-sm font-semibold text-zinc-700">Active (visible on homepage)</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-stone-200">
                <button type="submit" class="px-6 py-2.5 bg-[#c9a050] hover:bg-[#b08d42] text-white text-sm font-semibold rounded-sm transition-all">
                    {{ $step ? 'Update Step' : 'Create Step' }}
                </button>
                <a href="{{ route('admin.process-steps') }}" class="px-6 py-2.5 bg-stone-100 text-zinc-600 text-sm font-semibold rounded-sm hover:bg-stone-200 transition-all">
                    Cancel
                </a>
            </div>
        </form>

    </div>
</section>

@endsection
