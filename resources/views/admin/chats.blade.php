@extends('layouts.admin')

@section('title', 'Chatbot Conversations - Admin Panel')

@section('content')
<section class="py-8 lg:py-12" id="admin-chats">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header --}}
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-medium text-zinc-900 mb-1">Chatbot History</h1>
                <p class="text-zinc-500">Review all live user interactions and bot responses</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-[#2563EB] hover:underline flex items-center gap-2">
                <i class="ph ph-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        @if($chats->count() > 0)
        {{-- CRM Inbox Panel --}}
        <div class="bg-white border border-stone-200/50 rounded-sm shadow-sm overflow-hidden grid grid-cols-1 lg:grid-cols-3 min-h-[600px] h-[600px]">
            
            {{-- Left: Session Lists --}}
            <div class="border-r border-stone-200/50 flex flex-col h-full bg-white">
                <div class="p-4 bg-stone-50 border-b border-stone-200/50 flex items-center justify-between">
                    <span class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Active Conversations</span>
                    <span class="px-2 py-0.5 bg-blue-50 text-blue-600 text-[10px] font-bold rounded-full">{{ $chats->count() }} Sessions</span>
                </div>
                <div class="flex-1 overflow-y-auto divide-y divide-stone-100">
                    @foreach($chats as $sessionId => $messages)
                        @php
                            $firstMsg = $messages->first();
                            $lastMsg = $messages->last();
                            $chatUser = $firstMsg->user;
                        @endphp
                        <div class="session-item py-4 px-5 cursor-pointer flex gap-4 transition-all duration-200 border-l-4 border-l-transparent hover:bg-stone-50" data-session="{{ $sessionId }}">
                            {{-- Profile Avatar --}}
                            <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm flex-shrink-0
                                {{ $chatUser ? 'bg-blue-50 text-[#2563EB] border border-blue-100' : 'bg-stone-100 text-stone-500 border border-stone-200' }}">
                                {{ strtoupper(substr($chatUser ? $chatUser->name : 'G', 0, 1)) }}
                            </div>
                            
                            {{-- Info --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-start gap-2">
                                    <h4 class="text-sm font-semibold text-zinc-950 truncate">
                                        {{ $chatUser ? $chatUser->name : 'Guest User' }}
                                    </h4>
                                    <span class="text-[10px] text-zinc-400 font-medium whitespace-nowrap">
                                        {{ $lastMsg->created_at->format('h:i A') }}
                                    </span>
                                </div>
                                
                                <p class="text-xs text-zinc-500 truncate mt-1">
                                    {{ $lastMsg->message }}
                                </p>
                                
                                <div class="flex justify-between items-center mt-2">
                                    <span class="text-[9px] text-zinc-400 font-mono tracking-tight truncate max-w-[120px]">
                                        {{ substr($sessionId, 0, 12) }}...
                                    </span>
                                    <span class="px-1.5 py-0.5 bg-stone-50 border border-stone-200/50 rounded-sm text-[9px] text-zinc-400 font-bold uppercase tracking-wider">
                                        {{ $messages->count() }} Msgs
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Right: Active Chat Conversation Pane --}}
            <div class="lg:col-span-2 flex flex-col h-full bg-stone-50/30 relative">
                
                {{-- Default Empty State Placeholder --}}
                <div id="chat-default-placeholder" class="absolute inset-0 flex flex-col items-center justify-center p-8 text-center bg-stone-50/50 z-10 transition-opacity duration-300">
                    <i class="ph ph-chat-circle-dots text-5xl text-zinc-300 mb-3 animate-pulse"></i>
                    <h3 class="text-sm font-semibold text-zinc-900">Select a Conversation</h3>
                    <p class="text-xs text-zinc-500 max-w-xs mt-1 leading-relaxed">
                        Click on any active chatbot session in the sidebar to load the complete message history.
                    </p>
                </div>

                {{-- Conversations Stack --}}
                <div class="flex-1 flex flex-col h-full relative">
                    @foreach($chats as $sessionId => $messages)
                        @php
                            $firstMsg = $messages->first();
                            $chatUser = $firstMsg->user;
                        @endphp
                        <div id="chat-window-{{ $sessionId }}" class="chat-conversation-pane hidden flex-col h-full w-full" data-session="{{ $sessionId }}">
                            
                            {{-- Chat Header --}}
                            <div class="px-6 py-4 bg-white border-b border-stone-200/50 flex justify-between items-center flex-shrink-0">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm bg-[#2563EB]/10 text-[#2563EB] border border-blue-100">
                                        {{ strtoupper(substr($chatUser ? $chatUser->name : 'G', 0, 1)) }}
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-semibold text-zinc-950">
                                            {{ $chatUser ? $chatUser->name : 'Guest User' }}
                                        </h3>
                                        <p class="text-[10px] text-zinc-400 font-medium">
                                            @if($chatUser)
                                                Registered Client · {{ $chatUser->email }}
                                            @else
                                                Unauthenticated Visitor · Guest Mode
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="flex flex-col items-end text-right">
                                    <span class="text-[10px] text-zinc-400 font-mono">ID: {{ $sessionId }}</span>
                                    <span class="text-[9px] text-[#2563EB] font-bold mt-0.5">Session Active</span>
                                </div>
                            </div>

                            {{-- Scrollable Messages Log --}}
                            <div class="msg-box flex-1 overflow-y-auto p-6 space-y-4 bg-stone-50/50">
                                @foreach($messages->reverse() as $msg)
                                    <div class="flex {{ $msg->sender == 'user' ? 'justify-end' : 'justify-start' }} items-end gap-2">
                                        
                                        {{-- Bot Avatar (Only for bot side) --}}
                                        @if($msg->sender == 'bot')
                                            <div class="w-6 h-6 rounded-full bg-zinc-900 border border-zinc-800 flex items-center justify-center text-white text-[9px] font-bold flex-shrink-0">
                                                B
                                            </div>
                                        @endif

                                        <div class="max-w-[70%] p-3 rounded-2xl text-xs shadow-sm transition-all duration-150
                                            {{ $msg->sender == 'user' 
                                                ? 'bg-[#2563EB] text-white rounded-tr-none' 
                                                : 'bg-white border border-stone-200/50 text-zinc-800 rounded-tl-none' }}">
                                            <p class="leading-relaxed whitespace-pre-wrap">{{ $msg->message }}</p>
                                            <span class="text-[9px] mt-1.5 block opacity-70 text-right font-medium">
                                                {{ $msg->created_at->format('h:i A') }}
                                            </span>
                                        </div>

                                        {{-- User Avatar (Only for user side) --}}
                                        @if($msg->sender == 'user')
                                            <div class="w-6 h-6 rounded-full bg-blue-100 border border-blue-200 flex items-center justify-center text-blue-600 text-[9px] font-bold flex-shrink-0">
                                                {{ strtoupper(substr($chatUser ? $chatUser->name : 'U', 0, 1)) }}
                                            </div>
                                        @endif

                                    </div>
                                @endforeach
                            </div>

                            {{-- Bottom Info Action Bar --}}
                            <div class="px-6 py-3 bg-white border-t border-stone-200/50 text-[10px] text-zinc-400 flex items-center justify-between flex-shrink-0">
                                <span>Created: {{ $messages->first()->created_at->format('M d, Y \a\t h:i A') }}</span>
                                <span class="flex items-center gap-1"><i class="ph ph-shield-check text-emerald-500"></i> Read Only Conversation Archive</span>
                            </div>

                        </div>
                    @endforeach
                </div>

            </div>

        </div>
        @else
        <div class="bg-stone-50 border border-stone-200/50 rounded-lg p-16 text-center">
            <div class="w-16 h-16 bg-stone-100 rounded-full flex items-center justify-center mx-auto mb-4 border border-stone-200/50">
                <i class="ph ph-chat-circle-slash text-2xl text-zinc-400"></i>
            </div>
            <h3 class="text-lg font-medium text-zinc-900">No Conversations Found</h3>
            <p class="text-zinc-500 max-w-sm mx-auto text-sm mt-1">
                There are no client interactions currently logged in your directory. Active chatbot session histories will populate here.
            </p>
        </div>
        @endif

    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const sessionItems = document.querySelectorAll('.session-item');
    const chatPanes = document.querySelectorAll('.chat-conversation-pane');
    const defaultPlaceholder = document.getElementById('chat-default-placeholder');

    function activateSession(sessionId) {
        // Highlight active session item in left panel
        sessionItems.forEach(item => {
            if (item.dataset.session === sessionId) {
                item.classList.add('bg-blue-50/40', 'border-l-4', 'border-l-[#2563EB]');
                item.classList.remove('hover:bg-stone-50');
            } else {
                item.classList.remove('bg-blue-50/40', 'border-l-4', 'border-l-[#2563EB]');
                item.classList.add('hover:bg-stone-50');
            }
        });

        // Toggle active conversation window pane
        chatPanes.forEach(pane => {
            if (pane.dataset.session === sessionId) {
                pane.classList.remove('hidden');
                pane.classList.add('flex');
                
                // Auto Scroll chat message log list to bottom instantly
                const msgBox = pane.querySelector('.msg-box');
                if (msgBox) {
                    msgBox.scrollTop = msgBox.scrollHeight;
                }
            } else {
                pane.classList.remove('flex');
                pane.classList.add('hidden');
            }
        });

        // Remove placeholder layer
        if (defaultPlaceholder) {
            defaultPlaceholder.classList.add('hidden');
        }
    }

    sessionItems.forEach(item => {
        item.addEventListener('click', () => {
            activateSession(item.dataset.session);
        });
    });

    // Auto-select first session if it exists
    if (sessionItems.length > 0) {
        activateSession(sessionItems[0].dataset.session);
    }
});
</script>
@endpush
