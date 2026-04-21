@extends('layouts.app')

@section('title', 'Chatbot Conversations - Admin Panel')

@section('content')
<section class="py-12" id="admin-chats">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-medium text-zinc-900">Chatbot History</h1>
                <p class="text-zinc-500">Review all user interactions and bot responses</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-[#2563EB] hover:underline flex items-center gap-2">
                <i class="ph ph-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <div class="space-y-6">
            @forelse($chats as $sessionId => $messages)
                <div class="bg-white border border-stone-200/50 rounded-lg overflow-hidden shadow-sm">
                    <div class="px-6 py-4 bg-stone-50 border-b border-stone-200/50 flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-[#2563EB]/10 rounded-full flex items-center justify-center">
                                <i class="ph ph-chat-circle-dots text-xl text-[#2563EB]"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-zinc-900">Session: {{ substr($sessionId, 0, 15) }}...</h3>
                                <p class="text-xs text-zinc-500">
                                    {{ $messages->first()->created_at->format('M d, Y h:i A') }}
                                    @if($messages->first()->user)
                                        · User: {{ $messages->first()->user->name }} ({{ $messages->first()->user->email }})
                                    @else
                                        · Guest User
                                    @endif
                                </p>
                            </div>
                        </div>
                        <span class="px-3 py-1 bg-zinc-100 rounded-full text-[10px] uppercase tracking-wider font-bold text-zinc-600">
                            {{ $messages->count() }} messages
                        </span>
                    </div>
                    
                    <div class="p-6 space-y-4 max-h-[400px] overflow-y-auto bg-zinc-50/50">
                        @foreach($messages->reverse() as $msg)
                            <div class="flex {{ $msg->sender == 'user' ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-[70%] p-3 rounded-2xl text-sm {{ $msg->sender == 'user' ? 'bg-[#2563EB] text-white rounded-tr-none' : 'bg-white border border-stone-200/50 text-zinc-800 rounded-tl-none' }}">
                                    <p>{{ $msg->message }}</p>
                                    <p class="text-[10px] mt-1 opacity-70 text-right">{{ $msg->created_at->format('h:i A') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="bg-stone-50 border border-stone-200/50 rounded-lg p-12 text-center">
                    <i class="ph ph-chat-slash text-5xl text-zinc-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-zinc-900">No conversations found</h3>
                    <p class="text-zinc-500">User interactions will appear here once the chatbot is used.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
