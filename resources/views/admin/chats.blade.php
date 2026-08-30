@extends('layouts.admin')

@section('title', 'Chatbot Inquiries & History - Admin CRM')
@section('topbar_title', 'Chat Inquiries')

@section('content')
<div class="max-w-7xl mx-auto space-y-6" id="admin-chats">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Chat Inquiries & Bot Logs</h1>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                    <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span> {{ $chats->count() }} Sessions
                </span>
            </div>
            <p class="text-sm text-slate-500 mt-1">Review live client inquiries, AI chatbot interactions, and guest visitor sessions.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all">
                <i class="ph-bold ph-arrow-left text-base"></i>
                <span>Back to Dashboard</span>
            </a>
        </div>
    </div>

    @if($chats->count() > 0)
    {{-- Main 2-Column Chat CRM Container --}}
    <div class="bg-white border border-slate-200/90 rounded-3xl shadow-xs overflow-hidden grid grid-cols-1 lg:grid-cols-12 min-h-[640px] h-[720px]">
        
        {{-- Left: Session List (4 cols) --}}
        <div class="lg:col-span-5 xl:col-span-4 border-r border-slate-200/90 flex flex-col h-full bg-slate-50/50">
            
            {{-- Search & Header --}}
            <div class="p-4 bg-white border-b border-slate-200/90 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-extrabold text-slate-400 uppercase tracking-widest">Conversations</span>
                    <span class="px-2 py-0.5 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-md">{{ $chats->count() }} total</span>
                </div>

                {{-- Live Search Filter --}}
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <i class="ph-bold ph-magnifying-glass text-sm"></i>
                    </span>
                    <input type="text" id="chat-search-input" placeholder="Search sessions or messages..."
                           class="w-full pl-9 pr-3.5 py-2 bg-slate-50 hover:bg-slate-100/80 focus:bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/10 transition-all">
                </div>
            </div>

            {{-- Scrollable Session Items List --}}
            <div class="flex-1 overflow-y-auto divide-y divide-slate-100 custom-scrollbar" id="sessions-container">
                @foreach($chats as $sessionId => $messages)
                    @php
                        $firstMsg = $messages->first();
                        $lastMsg = $messages->last();
                        $chatUser = $firstMsg->user;
                        $sessionSnippet = $lastMsg ? $lastMsg->message : '';
                    @endphp
                    <div class="session-item p-4 cursor-pointer flex gap-3.5 transition-all duration-150 border-l-4 border-l-transparent hover:bg-white bg-transparent"
                         data-session="{{ $sessionId }}"
                         data-search="{{ strtolower(($chatUser ? $chatUser->name . ' ' . $chatUser->email : 'guest user') . ' ' . $sessionId . ' ' . $sessionSnippet) }}">
                        
                        {{-- Avatar --}}
                        <div class="w-11 h-11 rounded-2xl flex items-center justify-center font-extrabold text-sm flex-shrink-0 shadow-xs
                            {{ $chatUser ? 'bg-gradient-to-tr from-blue-600 to-indigo-600 text-white' : 'bg-slate-200 text-slate-600' }}">
                            {{ strtoupper(substr($chatUser ? $chatUser->name : 'G', 0, 1)) }}
                        </div>
                        
                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start gap-2">
                                <h4 class="text-xs font-bold text-slate-900 truncate">
                                    {{ $chatUser ? $chatUser->name : 'Guest Visitor' }}
                                </h4>
                                <span class="text-[10px] text-slate-400 font-semibold whitespace-nowrap">
                                    {{ $lastMsg ? $lastMsg->created_at->format('h:i A') : '' }}
                                </span>
                            </div>
                            
                            <p class="text-xs text-slate-500 truncate mt-1 leading-normal">
                                {{ $sessionSnippet }}
                            </p>
                            
                            <div class="flex justify-between items-center mt-2">
                                <span class="text-[10px] text-slate-400 font-mono tracking-tight truncate max-w-[110px] bg-slate-100 px-1.5 py-0.5 rounded">
                                    {{ substr($sessionId, 0, 10) }}...
                                </span>
                                <span class="px-2 py-0.5 bg-blue-50 text-blue-700 border border-blue-100 rounded-md text-[10px] font-extrabold">
                                    {{ $messages->count() }} msg{{ $messages->count() > 1 ? 's' : '' }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Right: Active Chat Conversation Pane (8 cols) --}}
        <div class="lg:col-span-7 xl:col-span-8 flex flex-col h-full bg-white relative">
            
            {{-- Default Empty State Placeholder --}}
            <div id="chat-default-placeholder" class="absolute inset-0 flex flex-col items-center justify-center p-8 text-center bg-white z-10">
                <div class="w-16 h-16 rounded-3xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4 shadow-sm">
                    <i class="ph-bold ph-chat-circle-dots text-3xl"></i>
                </div>
                <h3 class="text-base font-extrabold text-slate-900">Select a Conversation</h3>
                <p class="text-xs text-slate-500 max-w-sm mt-1.5 leading-relaxed">
                    Click on any chat session from the list on the left to view the complete dialogue, timestamps, and customer questions.
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
                        
                        {{-- Chat Top Header --}}
                        <div class="px-6 py-4 bg-white border-b border-slate-200/80 flex justify-between items-center flex-shrink-0">
                            <div class="flex items-center gap-3.5">
                                <div class="w-11 h-11 rounded-2xl flex items-center justify-center font-extrabold text-sm shadow-xs
                                    {{ $chatUser ? 'bg-gradient-to-tr from-blue-600 to-indigo-600 text-white' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                                    {{ strtoupper(substr($chatUser ? $chatUser->name : 'G', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-sm font-bold text-slate-900">
                                            {{ $chatUser ? $chatUser->name : 'Guest Visitor' }}
                                        </h3>
                                        @if($chatUser)
                                            <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-md text-[10px] font-bold">Registered User</span>
                                        @else
                                            <span class="px-2 py-0.5 bg-slate-100 text-slate-600 border border-slate-200 rounded-md text-[10px] font-bold">Guest Mode</span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-slate-400 mt-0.5 font-medium">
                                        {{ $chatUser ? $chatUser->email : 'Unauthenticated Visitor Session' }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="flex flex-col items-end text-right">
                                <span class="text-[10px] text-slate-400 font-mono bg-slate-50 px-2 py-1 rounded-lg border border-slate-200">
                                    ID: {{ substr($sessionId, 0, 16) }}...
                                </span>
                                <span class="text-[10px] text-emerald-600 font-bold flex items-center gap-1 mt-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active Log
                                </span>
                            </div>
                        </div>

                        {{-- Scrollable Messages Log --}}
                        <div class="msg-box flex-1 overflow-y-auto p-6 space-y-4 bg-slate-50/60 custom-scrollbar">
                            @foreach($messages->reverse() as $msg)
                                <div class="flex {{ $msg->sender == 'user' ? 'justify-end' : 'justify-start' }} items-end gap-2.5">
                                    
                                    {{-- Bot Avatar --}}
                                    @if($msg->sender == 'bot')
                                        <div class="w-7 h-7 rounded-xl bg-slate-900 text-white flex items-center justify-center text-[10px] font-bold shadow-xs flex-shrink-0" title="UnlockRentals Assistant">
                                            <i class="ph-bold ph-robot"></i>
                                        </div>
                                    @endif

                                    <div class="max-w-[75%] p-3.5 rounded-2xl text-xs shadow-xs transition-all
                                        {{ $msg->sender == 'user' 
                                            ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-br-xs font-medium' 
                                            : 'bg-white border border-slate-200/90 text-slate-800 rounded-bl-xs' }}">
                                        <p class="leading-relaxed whitespace-pre-wrap">{{ $msg->message }}</p>
                                        <span class="text-[10px] mt-1.5 block opacity-75 text-right font-medium {{ $msg->sender == 'user' ? 'text-blue-100' : 'text-slate-400' }}">
                                            {{ $msg->created_at->format('h:i A') }}
                                        </span>
                                    </div>

                                    {{-- User Avatar --}}
                                    @if($msg->sender == 'user')
                                        <div class="w-7 h-7 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-[10px] font-bold shadow-xs flex-shrink-0">
                                            {{ strtoupper(substr($chatUser ? $chatUser->name : 'U', 0, 1)) }}
                                        </div>
                                    @endif

                                </div>
                            @endforeach
                        </div>

                        {{-- Bottom Info Action Bar --}}
                        <div class="px-6 py-3 bg-white border-t border-slate-200/80 text-[11px] text-slate-500 flex items-center justify-between flex-shrink-0">
                            <span class="font-medium">Started on {{ $messages->first()->created_at->format('M d, Y \a\t h:i A') }}</span>
                            <span class="flex items-center gap-1.5 text-emerald-600 font-bold bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200">
                                <i class="ph-bold ph-shield-check text-sm"></i>
                                <span>Verified Read-Only Archive</span>
                            </span>
                        </div>

                    </div>
                @endforeach
            </div>

        </div>

    </div>
    @else
    {{-- Empty State when no chats --}}
    <div class="bg-white border border-slate-200/90 rounded-3xl p-16 text-center shadow-xs">
        <div class="w-16 h-16 bg-slate-50 text-slate-400 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-slate-200 shadow-xs">
            <i class="ph-bold ph-chat-circle-slash text-3xl"></i>
        </div>
        <h3 class="text-lg font-bold text-slate-900">No Chat Conversations Yet</h3>
        <p class="text-slate-500 max-w-sm mx-auto text-xs mt-1.5 leading-relaxed">
            There are currently no customer chatbot interactions recorded. Live user messages will automatically show up here.
        </p>
    </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const sessionItems = document.querySelectorAll('.session-item');
    const chatPanes = document.querySelectorAll('.chat-conversation-pane');
    const defaultPlaceholder = document.getElementById('chat-default-placeholder');
    const searchInput = document.getElementById('chat-search-input');

    function activateSession(sessionId) {
        // Highlight active session item in left panel
        sessionItems.forEach(item => {
            if (item.dataset.session === sessionId) {
                item.classList.add('bg-white', 'border-l-4', 'border-l-blue-600', 'shadow-xs');
                item.classList.remove('border-l-transparent', 'bg-transparent');
            } else {
                item.classList.remove('bg-white', 'border-l-4', 'border-l-blue-600', 'shadow-xs');
                item.classList.add('border-l-transparent', 'bg-transparent');
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

    // Live search filter in session list
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const query = e.target.value.toLowerCase().trim();
            sessionItems.forEach(item => {
                const searchData = item.dataset.search || '';
                if (!query || searchData.includes(query)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }

    // Auto-select first session if it exists
    if (sessionItems.length > 0) {
        activateSession(sessionItems[0].dataset.session);
    }
});
</script>
@endpush
