@extends('layouts.app')

@section('title', 'Dashboard - UnlockRentals')

@section('content')

<section class="py-8 lg:py-12" id="tenant-dashboard">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-medium text-zinc-900 mb-1">Welcome, {{ auth()->user()->name }}!</h1>
            <p class="text-zinc-500">Here are your recent inquiries and activity.</p>
        </div>

        {{-- Quick Actions --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
            <a href="{{ route('properties.index') }}" class="group p-6 bg-stone-50 border border-stone-200/50 rounded-sm hover:border-[#2563EB]/50 transition-all" id="dash-browse">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-[#2563EB]/10 rounded-sm flex items-center justify-center group-hover:bg-[#2563EB]/10 transition-all">
                        <i class="ph ph-magnifying-glass text-2xl text-[#2563EB]"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-zinc-900 group-hover:text-[#2563EB] transition-colors">Browse Properties</p>
                        <p class="text-xs text-zinc-500">Find your next rental</p>
                    </div>
                </div>
            </a>
            <a href="{{ route('properties.index', ['type' => 'house']) }}" class="group p-6 bg-stone-50 border border-stone-200/50 rounded-sm hover:border-indigo-600/30 transition-all" id="dash-houses">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-indigo-600/10 rounded-sm flex items-center justify-center group-hover:bg-indigo-600/20 transition-all">
                        <i class="ph ph-house text-2xl text-indigo-500"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-zinc-900 group-hover:text-indigo-500 transition-colors">Houses for Rent</p>
                        <p class="text-xs text-zinc-500">View available houses</p>
                    </div>
                </div>
            </a>
        </div>

        {{-- Recent Inquiries --}}
        <div class="bg-stone-50 border border-stone-200/50 rounded-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-stone-200/50">
                <h2 class="text-lg font-semibold text-zinc-900">My Recent Inquiries</h2>
            </div>

            @if($inquiries->count() > 0)
            <div class="divide-y divide-white/5">
                @foreach($inquiries as $inquiry)
                <div class="p-6 hover:bg-stone-50/[0.02] transition-colors">
                    <div class="flex gap-4">
                        @if($inquiry->property && $inquiry->property->primaryImage)
                            <img src="{{ $inquiry->property->primaryImage->imageUrl() }}" class="w-16 h-16 rounded-sm object-cover border border-stone-200/50 flex-shrink-0" alt="">
                        @else
                            <div class="w-16 h-16 rounded-sm bg-stone-50 flex items-center justify-center flex-shrink-0">
                                <i class="ph ph-image text-zinc-500"></i>
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            @if($inquiry->property)
                            <a href="{{ route('properties.show', $inquiry->property) }}" class="text-sm font-medium text-zinc-900 hover:text-[#2563EB] transition-colors">
                                {{ $inquiry->property->title }}
                            </a>
                            @endif
                            <p class="text-xs text-zinc-500 mt-1">{{ Str::limit($inquiry->message, 100) }}</p>
                            <div class="flex items-center gap-3 mt-2">
                                <span class="text-xs text-zinc-500">{{ $inquiry->created_at->diffForHumans() }}</span>
                                @php
                                    $statusColors = ['unread' => 'amber', 'read' => 'blue', 'replied' => 'emerald'];
                                    $color = $statusColors[$inquiry->status] ?? 'gray';
                                @endphp
                                <span class="px-2 py-0.5 bg-{{ $color }}-500/10 text-{{ $color }}-400 text-xs rounded-md">
                                    {{ ucfirst($inquiry->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-16">
                <i class="ph ph-chat-dots text-5xl text-gray-700 mb-4"></i>
                <h3 class="text-xl font-semibold text-zinc-500 mb-2">No inquiries yet</h3>
                <p class="text-zinc-500 mb-6">Browse properties and send your first inquiry!</p>
                <a href="{{ route('properties.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-[#2563EB] text-white text-sm font-semibold rounded-sm transition-all">
                    <i class="ph ph-magnifying-glass"></i> Browse Properties
                </a>
            </div>
            @endif
        </div>
    </div>
</section>

@endsection
