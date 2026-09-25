@extends('layouts.app')

@section('title', 'My Service Requests | UnlockRentals')

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-slate-900 pb-20 pt-6">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Breadcrumbs --}}
        <nav class="flex items-center text-xs text-slate-500 dark:text-slate-400 mb-6 gap-2" aria-label="Breadcrumb">
            <a href="{{ url('/') }}" class="hover:text-blue-600 transition-colors">Home</a>
            <i class="ph ph-caret-right text-[10px]"></i>
            <a href="{{ route('services.index') }}" class="hover:text-blue-600 transition-colors">Local Professionals</a>
            <i class="ph ph-caret-right text-[10px]"></i>
            <span class="text-slate-900 dark:text-white font-medium">My Service Requests</span>
        </nav>

        <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-200 dark:border-slate-800">
            <div>
                <h1 class="text-2xl font-black text-slate-900 dark:text-white">My Service Requests</h1>
                <p class="text-xs text-slate-500 mt-1">Track the status of home service requests you have submitted</p>
            </div>
            <a href="{{ route('services.index') }}" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition-all shadow-sm">
                Find Professionals
            </a>
        </div>

        @if($requests->count() > 0)
            <div class="space-y-4">
                @foreach($requests as $req)
                    <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200/80 dark:border-slate-700/80 shadow-sm">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-4 border-b border-slate-100 dark:border-slate-700">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="px-2.5 py-1 rounded-lg bg-blue-50 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 font-bold text-xs">
                                        {{ $req->category?->name ?? 'General Service' }}
                                    </span>
                                    @if($req->service)
                                        <span class="text-xs font-semibold text-slate-600 dark:text-slate-300">
                                            • {{ $req->service->name }}
                                        </span>
                                    @endif
                                </div>
                                <span class="text-[11px] text-slate-400 mt-1 block">Submitted on {{ $req->created_at->format('M d, Y • h:i A') }}</span>
                            </div>

                            @php
                                $statusStyles = [
                                    'new' => 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-900/30 dark:text-amber-300',
                                    'contacted' => 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-900/30 dark:text-blue-300',
                                    'accepted' => 'bg-indigo-50 text-indigo-700 border-indigo-200 dark:bg-indigo-900/30 dark:text-indigo-300',
                                    'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-300',
                                    'cancelled' => 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-900/30 dark:text-rose-300',
                                ];
                                $sClass = $statusStyles[$req->status] ?? 'bg-slate-50 text-slate-700 border-slate-200';
                            @endphp
                            <span class="px-3 py-1 rounded-full border text-xs font-bold capitalize {{ $sClass }}">
                                {{ $req->status }}
                            </span>
                        </div>

                        <div class="py-4 space-y-2 text-xs">
                            <p class="text-slate-700 dark:text-slate-300 leading-relaxed"><strong class="text-slate-900 dark:text-white">Problem:</strong> {{ $req->description }}</p>
                            <div class="flex flex-wrap gap-4 text-slate-500 pt-1">
                                <span><i class="ph-bold ph-map-pin text-blue-600"></i> {{ $req->locality ? $req->locality . ', ' : '' }}{{ $req->city }}</span>
                                @if($req->preferred_date)
                                    <span><i class="ph-bold ph-calendar text-blue-600"></i> Preferred Date: {{ $req->preferred_date }}</span>
                                @endif
                                @if($req->budget)
                                    <span><i class="ph-bold ph-currency-inr text-emerald-600"></i> Budget: ₹{{ number_format($req->budget) }}</span>
                                @endif
                            </div>
                        </div>

                        {{-- Matched Professionals List --}}
                        @if($req->leads->count() > 0)
                            <div class="mt-2 pt-3 border-t border-slate-100 dark:border-slate-700">
                                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-2">Connected Professionals ({{ $req->leads->count() }})</span>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @foreach($req->leads as $lead)
                                        @if($lead->professional)
                                            <div class="p-3 rounded-2xl bg-slate-50 dark:bg-slate-700/50 border border-slate-200/60 dark:border-slate-700 flex items-center justify-between">
                                                <div class="flex items-center gap-3">
                                                    <img src="{{ $lead->professional->profile_photo_url }}" class="w-10 h-10 rounded-xl object-cover">
                                                    <div>
                                                        <h5 class="text-xs font-bold text-slate-900 dark:text-white">{{ $lead->professional->business_name }}</h5>
                                                        <span class="text-[11px] text-slate-500">Status: <strong class="capitalize text-blue-600">{{ $lead->status }}</strong></span>
                                                    </div>
                                                </div>

                                                <div class="flex items-center gap-2">
                                                    <a href="tel:{{ $lead->professional->phone }}" class="p-2 rounded-lg bg-emerald-600 text-white text-xs font-bold">
                                                        <i class="ph-bold ph-phone"></i>
                                                    </a>
                                                    <a href="{{ route('services.show', [$lead->professional->category->slug, Str::slug($lead->professional->city ?: 'india'), $lead->professional->slug]) }}" class="px-2.5 py-1.5 rounded-lg bg-blue-600 text-white text-xs font-bold">
                                                        Profile
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $requests->links() }}
            </div>
        @else
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-10 text-center border border-slate-200 dark:border-slate-700">
                <div class="w-12 h-12 rounded-full bg-blue-50 dark:bg-slate-700 text-blue-600 flex items-center justify-center mx-auto mb-3">
                    <i class="ph-bold ph-clipboard-text text-xl"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-1">No service requests yet</h3>
                <p class="text-xs text-slate-500 mb-4">When you request service from local professionals, you can track their status here.</p>
                <a href="{{ route('services.index') }}" class="inline-flex px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition-all">
                    Search Local Professionals
                </a>
            </div>
        @endif

    </div>
</div>
@endsection
