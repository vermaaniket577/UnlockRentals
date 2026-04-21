@extends('layouts.app')

@section('title', ($plan ? 'Edit' : 'Create') . ' Plan - Admin')

@section('content')

<section class="py-8 lg:py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-8">
            <h1 class="text-2xl font-medium text-zinc-900 mb-1">{{ $plan ? 'Edit Plan' : 'Create New Plan' }}</h1>
            <p class="text-zinc-500 text-sm">{{ $plan ? 'Update plan details below.' : 'Set up a new subscription plan for users.' }}</p>
        </div>

        @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-sm">
            @foreach($errors->all() as $error)
                <p class="text-sm text-red-700">{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <form method="POST" action="{{ $plan ? route('admin.plans.update', $plan) : route('admin.plans.store') }}"
              class="bg-stone-50 border border-stone-200/50 rounded-sm p-6 space-y-5">
            @csrf
            @if($plan) @method('PUT') @endif

            <div>
                <label class="block text-sm font-semibold text-zinc-700 mb-1.5">Plan Name *</label>
                <input type="text" name="name" value="{{ old('name', $plan->name ?? '') }}" required
                       class="w-full px-4 py-2.5 border border-stone-200 rounded-sm text-sm text-zinc-900 focus:outline-none focus:border-[#c9a050]"
                       placeholder="e.g. Basic, Premium, Gold">
            </div>

            <div>
                <label class="block text-sm font-semibold text-zinc-700 mb-1.5">Description</label>
                <textarea name="description" rows="3"
                          class="w-full px-4 py-2.5 border border-stone-200 rounded-sm text-sm text-zinc-900 focus:outline-none focus:border-[#c9a050] resize-none"
                          placeholder="Brief description of what this plan offers">{{ old('description', $plan->description ?? '') }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-zinc-700 mb-1.5">Price (₹) *</label>
                    <input type="number" name="price" value="{{ old('price', $plan->price ?? '') }}" required min="0" step="0.01"
                           class="w-full px-4 py-2.5 border border-stone-200 rounded-sm text-sm text-zinc-900 focus:outline-none focus:border-[#c9a050]"
                           placeholder="499">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-zinc-700 mb-1.5">Duration (days) *</label>
                    <input type="number" name="duration_days" value="{{ old('duration_days', $plan->duration_days ?? 30) }}" required min="1"
                           class="w-full px-4 py-2.5 border border-stone-200 rounded-sm text-sm text-zinc-900 focus:outline-none focus:border-[#c9a050]"
                           placeholder="30">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-zinc-700 mb-1.5">Contact Limit *</label>
                    <input type="number" name="contact_limit" value="{{ old('contact_limit', $plan->contact_limit ?? 10) }}" required min="1"
                           class="w-full px-4 py-2.5 border border-stone-200 rounded-sm text-sm text-zinc-900 focus:outline-none focus:border-[#c9a050]"
                           placeholder="10">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-zinc-700 mb-1.5">Features (one per line)</label>
                <textarea name="features" rows="4"
                          class="w-full px-4 py-2.5 border border-stone-200 rounded-sm text-sm text-zinc-900 focus:outline-none focus:border-[#c9a050] resize-none"
                          placeholder="Priority support&#10;Verified badge&#10;Direct messaging">{{ old('features', $plan && $plan->features ? implode("\n", $plan->features) : '') }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-zinc-700 mb-1.5">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $plan->sort_order ?? 0) }}"
                           class="w-full px-4 py-2.5 border border-stone-200 rounded-sm text-sm text-zinc-900 focus:outline-none focus:border-[#c9a050]">
                </div>
                <div class="flex items-end">
                    <label class="flex items-center gap-3 cursor-pointer pb-2.5">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $plan->is_active ?? true) ? 'checked' : '' }}
                               class="w-4 h-4 accent-[#c9a050]">
                        <span class="text-sm font-semibold text-zinc-700">Active (visible to users)</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-stone-200">
                <button type="submit" class="px-6 py-2.5 bg-[#c9a050] hover:bg-[#b08d42] text-white text-sm font-semibold rounded-sm transition-all">
                    {{ $plan ? 'Update Plan' : 'Create Plan' }}
                </button>
                <a href="{{ route('admin.plans') }}" class="px-6 py-2.5 bg-stone-100 text-zinc-600 text-sm font-semibold rounded-sm hover:bg-stone-200 transition-all">
                    Cancel
                </a>
            </div>
        </form>

    </div>
</section>

@endsection
