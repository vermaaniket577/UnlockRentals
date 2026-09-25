@extends('layouts.admin')

@section('title', 'Manage Categories | UnlockRentals CRM')

@section('content')
<div class="p-6 space-y-6">

    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white flex items-center gap-2">
                <i class="ph-bold ph-squares-four text-blue-600"></i>
                Professional Categories Management
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">Add, edit, deactivate and reorder service categories</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.professionals.services') }}" class="px-3.5 py-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-700 dark:text-slate-200">
                Manage Sub-Services
            </a>
            <a href="{{ route('admin.professionals.dashboard') }}" class="px-3.5 py-2 rounded-xl bg-slate-100 dark:bg-slate-700 text-xs font-bold text-slate-700 dark:text-slate-200">
                Back to Dashboard
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 text-emerald-800 text-xs font-semibold flex items-center gap-2">
            <i class="ph-fill ph-check-circle text-base text-emerald-600"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 rounded-xl bg-rose-50 text-rose-800 text-xs font-semibold flex items-center gap-2">
            <i class="ph-fill ph-warning-circle text-base text-rose-600"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Add Category Form --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200/80 dark:border-slate-700 shadow-xs">
        <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
            <i class="ph-bold ph-plus-circle text-blue-600"></i>
            Add New Professional Category
        </h3>

        <form action="{{ route('admin.professionals.categories.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Category Name *</label>
                <input type="text" name="name" required placeholder="e.g. Appliance Repair" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Short Description</label>
                <input type="text" name="short_description" placeholder="e.g. Fridge, Washing Machine, AC repairs" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Phosphor Icon Class</label>
                <input type="text" name="icon" value="ph-wrench" placeholder="e.g. ph-wrench, ph-lightning" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
            </div>
            <div class="flex items-center gap-2">
                <div class="flex-1">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Status</label>
                    <select name="status" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <button type="submit" class="py-2.5 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs whitespace-nowrap">
                    Add Category
                </button>
            </div>
        </form>
    </div>

    {{-- Categories List Table --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700 shadow-xs overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead>
                <tr class="bg-slate-50/50 dark:bg-slate-700/30 text-slate-500 border-b border-slate-100 dark:border-slate-700">
                    <th class="p-3.5">Icon</th>
                    <th class="p-3.5">Category Name</th>
                    <th class="p-3.5">Slug</th>
                    <th class="p-3.5">Services Count</th>
                    <th class="p-3.5">Professionals Count</th>
                    <th class="p-3.5">Status</th>
                    <th class="p-3.5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                @foreach($categories as $cat)
                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-700/40">
                        <td class="p-3.5">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-slate-700 text-blue-600 flex items-center justify-center text-lg">
                                <i class="{{ $cat->icon ?: 'ph-bold ph-wrench' }}"></i>
                            </div>
                        </td>
                        <td class="p-3.5 font-bold text-slate-900 dark:text-white">
                            {{ $cat->name }}
                        </td>
                        <td class="p-3.5 text-slate-400 font-mono text-[11px]">
                            {{ $cat->slug }}
                        </td>
                        <td class="p-3.5 font-semibold text-slate-700 dark:text-slate-300">
                            {{ $cat->services_count }} services
                        </td>
                        <td class="p-3.5 font-bold text-blue-600">
                            {{ $cat->professionals_count }} listings
                        </td>
                        <td class="p-3.5">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $cat->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-700' }}">
                                {{ $cat->status }}
                            </span>
                        </td>
                        <td class="p-3.5 text-right">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('admin.professionals.services', ['category_id' => $cat->id]) }}" class="px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 text-[11px] font-bold hover:bg-blue-100">
                                    Services
                                </a>
                                @if($cat->professionals_count == 0)
                                    <form action="{{ route('admin.professionals.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Delete this category?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 rounded-lg text-rose-500 hover:bg-rose-50">
                                            <i class="ph-bold ph-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection
