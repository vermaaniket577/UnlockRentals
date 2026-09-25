@extends('layouts.admin')

@section('title', 'Manage Services | UnlockRentals CRM')

@section('content')
<div class="p-6 space-y-6">

    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white flex items-center gap-2">
                <i class="ph-bold ph-wrench text-blue-600"></i>
                Sub-Services Management
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">Manage individual service items within professional categories</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.professionals.categories') }}" class="px-3.5 py-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-700 dark:text-slate-200">
                Categories List
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

    {{-- Filter by Category Select --}}
    <div class="flex items-center gap-2 overflow-x-auto pb-2 text-xs font-bold">
        @foreach($categories as $c)
            <a href="{{ route('admin.professionals.services', ['category_id' => $c->id]) }}" class="px-3 py-1.5 rounded-xl transition-all whitespace-nowrap {{ $selectedCatId == $c->id ? 'bg-blue-600 text-white' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:bg-slate-50' }}">
                {{ $c->name }}
            </a>
        @endforeach
    </div>

    {{-- Add Service Form --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200/80 dark:border-slate-700 shadow-xs">
        <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
            <i class="ph-bold ph-plus-circle text-blue-600"></i>
            Add Sub-Service
        </h3>

        <form action="{{ route('admin.professionals.services.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Target Category *</label>
                <select name="category_id" required class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $selectedCatId == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Service Name *</label>
                <input type="text" name="name" required placeholder="e.g. Switchboard Installation" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Description (Optional)</label>
                <input type="text" name="description" placeholder="Brief details about what this service covers..." class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white">
            </div>
            <div class="flex items-center gap-2">
                <input type="hidden" name="status" value="active">
                <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs whitespace-nowrap">
                    Add Service
                </button>
            </div>
        </form>
    </div>

    {{-- Services List Table --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700 shadow-xs overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead>
                <tr class="bg-slate-50/50 dark:bg-slate-700/30 text-slate-500 border-b border-slate-100 dark:border-slate-700">
                    <th class="p-3.5">Service Name</th>
                    <th class="p-3.5">Slug</th>
                    <th class="p-3.5">Description</th>
                    <th class="p-3.5">Offering Professionals</th>
                    <th class="p-3.5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                @foreach($services as $serv)
                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-700/40">
                        <td class="p-3.5 font-bold text-slate-900 dark:text-white">
                            {{ $serv->name }}
                        </td>
                        <td class="p-3.5 text-slate-400 font-mono text-[11px]">
                            {{ $serv->slug }}
                        </td>
                        <td class="p-3.5 text-slate-500">
                            {{ $serv->description ?: '—' }}
                        </td>
                        <td class="p-3.5 font-semibold text-blue-600">
                            {{ $serv->professionals_count }} providers
                        </td>
                        <td class="p-3.5 text-right">
                            <form action="{{ route('admin.professionals.services.destroy', $serv->id) }}" method="POST" onsubmit="return confirm('Delete this service?')" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1 rounded-lg text-rose-500 hover:bg-rose-50">
                                    <i class="ph-bold ph-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection
