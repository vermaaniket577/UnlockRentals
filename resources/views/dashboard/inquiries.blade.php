@extends('layouts.app')

@section('title', 'Inquiries - UnlockRentals')

@section('content')

<section class="py-8 lg:py-12" id="inquiries-page">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-medium text-zinc-900 mb-1">Received Inquiries</h1>
                <p class="text-zinc-500">Messages from interested tenants</p>
            </div>
            <a href="{{ route('dashboard') }}" class="text-sm text-[#2563EB] hover:text-[#2563EB] font-medium transition-colors flex items-center gap-1">
                <i class="ph ph-arrow-left"></i> Dashboard
            </a>
        </div>

        @if($inquiries->count() > 0)
        <div class="space-y-4">
            @foreach($inquiries as $inquiry)
            <div class="bg-stone-50 border border-stone-200/50 rounded-sm p-6 hover:border-[#2563EB]/50 transition-all {{ $inquiry->status === 'unread' ? 'border-l-4 border-l-blue-600' : '' }}">
                <div class="flex flex-col sm:flex-row gap-4">
                    @if($inquiry->property && $inquiry->property->primaryImage)
                        <img src="{{ $inquiry->property->primaryImage->imageUrl() }}" class="w-full sm:w-20 h-32 sm:h-20 rounded-sm object-cover border border-stone-200/50 flex-shrink-0" alt="">
                    @endif
                    <div class="flex-1">
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <div>
                                <h3 class="text-sm font-semibold text-zinc-900">{{ $inquiry->name }}</h3>
                                <p class="text-xs text-zinc-500">{{ $inquiry->email }} {{ $inquiry->phone ? '· ' . $inquiry->phone : '' }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                @php
                                    $statusColors = ['unread' => 'amber', 'read' => 'blue', 'replied' => 'emerald'];
                                    $color = $statusColors[$inquiry->status] ?? 'gray';
                                @endphp
                                <span class="px-2.5 py-1 bg-{{ $color }}-500/10 text-{{ $color }}-400 text-xs font-medium rounded-sm">
                                    {{ ucfirst($inquiry->status) }}
                                </span>
                                <span class="text-xs text-zinc-500">{{ $inquiry->created_at->diffForHumans() }}</span>
                            </div>
                        </div>

                        @if($inquiry->property)
                        <a href="{{ route('properties.show', $inquiry->property) }}" class="text-xs text-[#2563EB] hover:text-[#2563EB] font-medium mb-2 inline-block">
                            Re: {{ $inquiry->property->title }}
                        </a>
                        @endif

                        <p class="text-sm text-zinc-500 leading-relaxed">{{ $inquiry->message }}</p>

                        <div class="mt-3 flex gap-2">
                            <a href="mailto:{{ $inquiry->email }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-[#2563EB]/10 text-[#2563EB] text-xs font-medium rounded-sm hover:bg-[#2563EB]/10 transition-all">
                                <i class="ph ph-envelope"></i> Reply via Email
                            </a>
                            @if($inquiry->phone)
                            <a href="tel:{{ $inquiry->phone }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-500/10 text-emerald-400 text-xs font-medium rounded-sm hover:bg-emerald-500/20 transition-all">
                                <i class="ph ph-phone"></i> Call
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $inquiries->links() }}
        </div>
        @else
        <div class="text-center py-20 bg-stone-50/30 border border-stone-200/50 rounded-sm">
            <i class="ph ph-chat-dots text-5xl text-gray-700 mb-4"></i>
            <h3 class="text-xl font-semibold text-zinc-500 mb-2">No inquiries yet</h3>
            <p class="text-zinc-500">You'll receive inquiries when tenants are interested in your properties.</p>
        </div>
        @endif
    </div>
</section>

@endsection
