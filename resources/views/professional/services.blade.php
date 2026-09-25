@extends('layouts.app')

@section('title', 'Manage Services | Professional Dashboard | UnlockRentals')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-900 pb-20 pt-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <nav class="flex items-center text-xs text-slate-500 mb-6 gap-2">
            <a href="{{ route('professional.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
            <i class="ph ph-caret-right text-[10px]"></i>
            <span class="text-slate-900 dark:text-white font-medium">My Services</span>
        </nav>

        <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-200 dark:border-slate-800">
            <div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white">Manage Services Offered</h1>
                <p class="text-xs text-slate-500 mt-0.5">Select all specific services you can provide under {{ $professional->category->name }}</p>
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

        <form action="{{ route('professional.services.update') }}" method="POST" class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 border border-slate-200/80 dark:border-slate-700/80 shadow-sm space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                @foreach($categoryServices as $serv)
                    <label class="flex items-start gap-2.5 p-3 rounded-2xl bg-slate-50 dark:bg-slate-700/40 border border-slate-200/60 dark:border-slate-600 hover:border-blue-500 cursor-pointer transition-colors">
                        <input type="checkbox" name="services[]" value="{{ $serv->id }}" {{ in_array($serv->id, $selectedServiceIds) ? 'checked' : '' }} class="mt-0.5 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        <div>
                            <span class="block text-xs font-bold text-slate-900 dark:text-white">{{ $serv->name }}</span>
                            @if($serv->description)
                                <span class="block text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 line-clamp-2">{{ $serv->description }}</span>
                            @endif
                        </div>
                    </label>
                @endforeach
            </div>

            <button type="submit" class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-600/25 transition-all">
                Save Services Offered
            </button>
        </form>

    </div>
</div>
@endsection
