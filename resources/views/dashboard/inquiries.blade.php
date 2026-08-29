@extends('layouts.app')

@section('title', 'Tenant Inquiries - UnlockRentals')

@section('content')

<section class="py-8 lg:py-12 bg-slate-50/50 dark:bg-slate-950 min-h-[calc(100vh-4rem)]" id="inquiries-page">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Header Bar --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 p-6 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-2xl flex-shrink-0">
                    <i class="ph-bold ph-chat-teardrop-dots"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">Received Inquiries</h1>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">Direct inquiries & messages from prospective tenants</p>
                </div>
            </div>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-50 hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs sm:text-sm font-semibold rounded-xl border border-slate-200 dark:border-slate-700 transition-colors w-fit" title="Back to Dashboard">
                <i class="ph-bold ph-arrow-left"></i>
                <span>Back to Dashboard</span>
            </a>
        </div>

        @if($inquiries->count() > 0)
        <div class="space-y-4">
            @foreach($inquiries as $inquiry)
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-5 sm:p-6 shadow-sm hover:shadow-md hover:border-blue-300 dark:hover:border-blue-800 transition-all duration-200 {{ $inquiry->status === 'unread' ? 'ring-2 ring-blue-500/20 border-l-4 border-l-blue-600' : '' }}">
                <div class="flex flex-col sm:flex-row gap-5">
                    @if($inquiry->property && $inquiry->property->primaryImageUrl())
                        <a href="{{ route('properties.show', $inquiry->property) }}" class="flex-shrink-0 group">
                            <img src="{{ $inquiry->property->primaryImageUrl() }}" class="w-full sm:w-24 h-36 sm:h-24 rounded-xl object-cover border border-slate-200/80 dark:border-slate-700 group-hover:scale-105 transition-transform" alt="{{ $inquiry->property->title }}">
                        </a>
                    @elseif($inquiry->property && $inquiry->property->hasVideo())
                        <a href="{{ route('properties.show', $inquiry->property) }}" class="flex-shrink-0 group block">
                            <div class="w-full sm:w-24 h-36 sm:h-24 rounded-xl bg-gradient-to-br from-purple-900 to-indigo-950 border border-purple-700 flex flex-col items-center justify-center text-purple-200 group-hover:scale-105 transition-transform">
                                <i class="ph-bold ph-video-camera text-2xl text-purple-300"></i>
                                <span class="text-[9px] font-extrabold uppercase mt-1">Video Tour</span>
                            </div>
                        </a>
                    @endif
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2.5">
                            <div>
                                <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ $inquiry->name }}</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $inquiry->email }} {{ $inquiry->phone ? '· ' . $inquiry->phone : '' }}</p>
                            </div>
                            <div class="flex items-center gap-2 flex-wrap">
                                @if($inquiry->status === 'unread')
                                    <span class="px-2.5 py-0.5 bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border border-amber-200/80 dark:border-amber-800 text-xs font-bold rounded-lg flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Unread
                                    </span>
                                @elseif($inquiry->status === 'replied')
                                    <span class="px-2.5 py-0.5 bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border border-emerald-200/80 dark:border-emerald-800 text-xs font-bold rounded-lg flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Replied
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 bg-blue-50 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 border border-blue-200/80 dark:border-blue-800 text-xs font-bold rounded-lg flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Read
                                    </span>
                                @endif
                                <span class="text-xs text-slate-400 dark:text-slate-500">{{ $inquiry->created_at->diffForHumans() }}</span>
                            </div>
                        </div>

                        @if($inquiry->property)
                        <div class="mb-3">
                            <a href="{{ route('properties.show', $inquiry->property) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 dark:text-blue-400 hover:underline" title="Regarding Property">
                                <i class="ph-bold ph-house-line"></i>
                                <span>Regarding: {{ $inquiry->property->title }}</span>
                            </a>
                        </div>
                        @endif

                        <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed bg-slate-50 dark:bg-slate-850 p-3.5 rounded-xl border border-slate-100 dark:border-slate-800">{{ $inquiry->message }}</p>

                        <div class="mt-4 flex flex-wrap gap-2.5">
                            <a href="mailto:{{ $inquiry->email }}?subject=Re: Inquiry for {{ optional($inquiry->property)->title }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-xs transition-all" title="Reply via Email">
                                <i class="ph-bold ph-envelope"></i>
                                <span>Reply via Email</span>
                            </a>
                            @if($inquiry->phone)
                            <a href="tel:{{ $inquiry->phone }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-xs transition-all" title="Call">
                                <i class="ph-bold ph-phone"></i>
                                <span>Call Phone</span>
                            </a>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $inquiry->phone) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/60 dark:hover:bg-emerald-900 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 text-xs font-bold rounded-xl transition-all" title="WhatsApp">
                                <i class="ph-bold ph-whatsapp-logo"></i>
                                <span>WhatsApp</span>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $inquiries->links() }}
        </div>
        @else
        <div class="text-center py-20 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-3xl p-8">
            <div class="w-16 h-16 mx-auto rounded-2xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-3xl mb-4">
                <i class="ph-bold ph-chat-teardrop-dots"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-1">No inquiries yet</h3>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 max-w-sm mx-auto">You will receive tenant inquiries here once people browse and show interest in your property listings.</p>
        </div>
        @endif
    </div>
</section>

@endsection
