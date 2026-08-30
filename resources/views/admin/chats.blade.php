@extends('layouts.admin')

@section('title', 'Chatbot Inquiries & History - Admin CRM')
@section('topbar_title', 'Chat Inquiries')

@section('content')
<div class="max-w-7xl mx-auto space-y-4" id="admin-chats">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-5 rounded-3xl border border-slate-200/80 shadow-xs">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Chat Inquiries & Live Support</h1>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                    <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span> {{ $chats->count() }} Active Sessions
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-0.5">Review user dialogues, chatbot logs, and reply directly to customer sessions in real-time.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all">
                <i class="ph-bold ph-arrow-left text-sm"></i>
                <span>Dashboard</span>
            </a>
        </div>
    </div>

    @if($chats->count() > 0)
    {{-- Main 2-Column Split CRM Chat Box --}}
    <div class="bg-white border border-slate-200/90 rounded-3xl shadow-xs overflow-hidden grid grid-cols-1 lg:grid-cols-12 h-[calc(100vh-190px)] min-h-[600px]">
        
        {{-- Left: Sessions List Sidebar (4 cols) --}}
        <div class="lg:col-span-5 xl:col-span-4 border-r border-slate-200/90 flex flex-col h-full bg-slate-50/50 min-h-0 overflow-hidden">
            
            {{-- Search & Header --}}
            <div class="p-3.5 bg-white border-b border-slate-200/90 space-y-2 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-extrabold text-slate-400 uppercase tracking-widest">Conversations</span>
                    <span class="px-2 py-0.5 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-md">{{ $chats->count() }} total</span>
                </div>

                {{-- Live Search Filter --}}
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <i class="ph-bold ph-magnifying-glass text-xs"></i>
                    </span>
                    <input type="text" id="chat-search-input" placeholder="Search by name, ID or message..."
                           class="w-full pl-8 pr-3 py-1.5 bg-slate-50 hover:bg-slate-100/80 focus:bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/10 transition-all">
                </div>
            </div>

            {{-- Scrollable Session Items List (Fixed scrolling with independent overflow) --}}
            <div class="flex-1 overflow-y-auto divide-y divide-slate-100 custom-scrollbar min-h-0" id="sessions-container" style="overflow-y: auto; -webkit-overflow-scrolling: touch;">
                @foreach($chats as $sessionId => $messages)
                    @php
                        $firstMsg = $messages->first();
                        $lastMsg = $messages->last();
                        $chatUser = $firstMsg->user;
                        $sessionSnippet = $lastMsg ? $lastMsg->message : '';
                    @endphp
                    <div class="session-item p-3.5 cursor-pointer flex gap-3 transition-all duration-150 border-l-4 border-l-transparent hover:bg-white bg-transparent select-none"
                         data-session="{{ $sessionId }}"
                         data-search="{{ strtolower(($chatUser ? $chatUser->name . ' ' . $chatUser->email : 'guest user') . ' ' . $sessionId . ' ' . $sessionSnippet) }}">
                        
                        {{-- Avatar --}}
                        <div class="w-10 h-10 rounded-2xl flex items-center justify-center font-extrabold text-xs flex-shrink-0 shadow-xs
                            {{ $chatUser ? 'bg-gradient-to-tr from-blue-600 to-indigo-600 text-white' : 'bg-slate-200 text-slate-600' }}">
                            {{ strtoupper(substr($chatUser ? $chatUser->name : 'G', 0, 1)) }}
                        </div>
                        
                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start gap-1">
                                <h4 class="text-xs font-bold text-slate-900 truncate">
                                    {{ $chatUser ? $chatUser->name : 'Guest Visitor' }}
                                </h4>
                                <span class="text-[10px] text-slate-400 font-semibold whitespace-nowrap">
                                    {{ $lastMsg ? $lastMsg->created_at->format('h:i A') : '' }}
                                </span>
                            </div>
                            
                            <p class="text-xs text-slate-500 truncate mt-0.5 leading-normal">
                                {{ $sessionSnippet }}
                            </p>
                            
                            <div class="flex justify-between items-center mt-1.5">
                                <span class="text-[9px] text-slate-400 font-mono tracking-tight truncate max-w-[100px] bg-slate-100 px-1.5 py-0.5 rounded">
                                    {{ substr($sessionId, 0, 10) }}...
                                </span>
                                <span class="px-1.5 py-0.5 bg-blue-50 text-blue-700 border border-blue-100 rounded text-[9px] font-extrabold">
                                    {{ $messages->count() }} msg{{ $messages->count() > 1 ? 's' : '' }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Right: Active Chat Conversation Pane (8 cols) --}}
        <div class="lg:col-span-7 xl:col-span-8 flex flex-col h-full bg-white relative min-h-0 overflow-hidden">
            
            {{-- Default Empty State Placeholder --}}
            <div id="chat-default-placeholder" class="absolute inset-0 flex flex-col items-center justify-center p-8 text-center bg-white z-10">
                <div class="w-14 h-14 rounded-3xl bg-blue-50 text-blue-600 flex items-center justify-center mb-3 shadow-xs">
                    <i class="ph-bold ph-chat-circle-dots text-2xl"></i>
                </div>
                <h3 class="text-sm font-extrabold text-slate-900">Select a Conversation</h3>
                <p class="text-xs text-slate-500 max-w-sm mt-1 leading-relaxed">
                    Click on any chat session from the left list to view message history and send direct replies.
                </p>
            </div>

            {{-- Conversations Stack --}}
            <div class="flex-1 flex flex-col h-full relative min-h-0 overflow-hidden">
                @foreach($chats as $sessionId => $messages)
                    @php
                        $firstMsg = $messages->first();
                        $chatUser = $firstMsg->user;
                    @endphp
                    <div id="chat-window-{{ $sessionId }}" class="chat-conversation-pane hidden flex-col h-full w-full min-h-0 overflow-hidden" data-session="{{ $sessionId }}">
                        
                        {{-- Chat Top Header --}}
                        <div class="px-5 py-3 bg-white border-b border-slate-200/80 flex justify-between items-center flex-shrink-0">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl flex items-center justify-center font-extrabold text-xs shadow-xs
                                    {{ $chatUser ? 'bg-gradient-to-tr from-blue-600 to-indigo-600 text-white' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                                    {{ strtoupper(substr($chatUser ? $chatUser->name : 'G', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-xs sm:text-sm font-bold text-slate-900">
                                            {{ $chatUser ? $chatUser->name : 'Guest Visitor' }}
                                        </h3>
                                        @if($chatUser)
                                            <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded text-[9px] font-bold">Registered User</span>
                                        @else
                                            <span class="px-2 py-0.5 bg-slate-100 text-slate-600 border border-slate-200 rounded text-[9px] font-bold">Guest Mode</span>
                                        @endif
                                    </div>
                                    <p class="text-[11px] text-slate-400 mt-0.5 font-medium">
                                        {{ $chatUser ? $chatUser->email : 'Unauthenticated Visitor Session' }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="flex flex-col items-end text-right">
                                <span class="text-[10px] text-slate-400 font-mono bg-slate-50 px-2 py-0.5 rounded border border-slate-200">
                                    ID: {{ substr($sessionId, 0, 14) }}...
                                </span>
                                <span class="text-[9px] text-emerald-600 font-bold flex items-center gap-1 mt-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Active Session
                                </span>
                            </div>
                        </div>

                        {{-- Scrollable Messages Log --}}
                        <div class="msg-box flex-1 overflow-y-auto p-5 space-y-3.5 bg-slate-50/60 custom-scrollbar min-h-0" id="msg-box-{{ $sessionId }}" style="overflow-y: auto;">
                            @foreach($messages->reverse() as $msg)
                                <div class="flex {{ $msg->sender == 'user' ? 'justify-end' : 'justify-start' }} items-end gap-2">
                                    
                                    {{-- Bot / Support Avatar --}}
                                    @if($msg->sender == 'bot')
                                        <div class="w-6 h-6 rounded-lg bg-slate-900 text-white flex items-center justify-center text-[9px] font-bold shadow-xs flex-shrink-0" title="UnlockRentals Assistant">
                                            <i class="ph-bold ph-robot"></i>
                                        </div>
                                    @endif

                                    <div class="max-w-[75%] p-3 rounded-2xl text-xs shadow-xs transition-all
                                        {{ $msg->sender == 'user' 
                                            ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-br-xs font-medium' 
                                            : 'bg-white border border-slate-200/90 text-slate-800 rounded-bl-xs font-medium' }}">
                                        <p class="leading-relaxed whitespace-pre-wrap">{{ $msg->message }}</p>
                                        <span class="text-[9px] mt-1 block opacity-75 text-right font-medium {{ $msg->sender == 'user' ? 'text-blue-100' : 'text-slate-400' }}">
                                            {{ $msg->created_at->format('h:i A') }}
                                        </span>
                                    </div>

                                    {{-- User Avatar --}}
                                    @if($msg->sender == 'user')
                                        <div class="w-6 h-6 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center text-[9px] font-bold shadow-xs flex-shrink-0">
                                            {{ strtoupper(substr($chatUser ? $chatUser->name : 'U', 0, 1)) }}
                                        </div>
                                    @endif

                                </div>
                            @endforeach
                        </div>

                        {{-- Interactive Reply Bar --}}
                        <div class="p-3.5 bg-white border-t border-slate-200/90 flex-shrink-0">
                            
                            {{-- Quick Response Chips --}}
                            <div class="flex items-center gap-1.5 mb-2 overflow-x-auto pb-1 text-[11px] custom-scrollbar">
                                <span class="text-slate-400 font-bold text-[10px] uppercase tracking-wider shrink-0 mr-1">Quick:</span>
                                <button type="button" onclick="insertQuickReply('{{ $sessionId }}', 'Hello! How can we assist you with rental properties today?')"
                                        class="px-2.5 py-1 bg-slate-100 hover:bg-blue-50 hover:text-blue-600 text-slate-600 rounded-lg font-medium transition-all shrink-0">
                                    👋 Greeting
                                </button>
                                <button type="button" onclick="insertQuickReply('{{ $sessionId }}', 'Our executive will contact you shortly on your registered number.')"
                                        class="px-2.5 py-1 bg-slate-100 hover:bg-blue-50 hover:text-blue-600 text-slate-600 rounded-lg font-medium transition-all shrink-0">
                                    📞 Call Follow-up
                                </button>
                                <button type="button" onclick="insertQuickReply('{{ $sessionId }}', 'All properties on UnlockRentals are 100% verified with zero brokerage.')"
                                        class="px-2.5 py-1 bg-slate-100 hover:bg-blue-50 hover:text-blue-600 text-slate-600 rounded-lg font-medium transition-all shrink-0">
                                    🛡️ Zero Brokerage
                                </button>
                            </div>

                            <form onsubmit="handleSendReply(event, '{{ $sessionId }}')" class="flex items-center gap-2">
                                @csrf
                                <input type="hidden" name="session_id" value="{{ $sessionId }}">
                                
                                <div class="relative flex-1">
                                    <input type="text"
                                           name="message"
                                           id="reply-input-{{ $sessionId }}"
                                           required
                                           placeholder="Type your response to this user as Support..."
                                           autocomplete="off"
                                           class="w-full pl-4 pr-10 py-2.5 bg-slate-50 focus:bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-900 placeholder-slate-400 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/10 transition-all">
                                </div>

                                <button type="submit" id="reply-btn-{{ $sessionId }}"
                                        class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white rounded-xl text-xs font-bold transition-all shadow-sm shadow-blue-600/20 flex items-center gap-1.5 shrink-0">
                                    <i class="ph-bold ph-paper-plane-right text-sm"></i>
                                    <span>Send</span>
                                </button>
                            </form>
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

                // Focus input box
                const input = document.getElementById('reply-input-' + sessionId);
                if (input) {
                    setTimeout(() => input.focus(), 50);
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

    // Quick Reply Helper
    window.insertQuickReply = function(sessionId, text) {
        const input = document.getElementById('reply-input-' + sessionId);
        if (input) {
            input.value = text;
            input.focus();
        }
    };

    // AJAX Send Reply
    window.handleSendReply = function(e, sessionId) {
        e.preventDefault();
        const input = document.getElementById('reply-input-' + sessionId);
        const btn = document.getElementById('reply-btn-' + sessionId);
        const msgBox = document.getElementById('msg-box-' + sessionId);
        const messageText = input ? input.value.trim() : '';

        if (!messageText) return;

        // Disable button during send
        btn.disabled = true;
        btn.classList.add('opacity-75');

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        fetch("{{ route('admin.chats.reply') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
                "Accept": "application/json"
            },
            body: JSON.stringify({
                session_id: sessionId,
                message: messageText
            })
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.classList.remove('opacity-75');
            input.value = '';

            // Dynamically append new bot message bubble
            const timeStr = data.time || new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
            const bubbleHtml = `
                <div class="flex justify-start items-end gap-2">
                    <div class="w-6 h-6 rounded-lg bg-slate-900 text-white flex items-center justify-center text-[9px] font-bold shadow-xs flex-shrink-0" title="UnlockRentals Assistant">
                        <i class="ph-bold ph-robot"></i>
                    </div>
                    <div class="max-w-[75%] p-3 rounded-2xl text-xs shadow-xs transition-all bg-white border border-slate-200/90 text-slate-800 rounded-bl-xs font-medium">
                        <p class="leading-relaxed whitespace-pre-wrap">${escapeHtml(data.message || messageText)}</p>
                        <span class="text-[9px] mt-1 block opacity-75 text-right font-medium text-slate-400">
                            ${timeStr}
                        </span>
                    </div>
                </div>
            `;

            if (msgBox) {
                msgBox.insertAdjacentHTML('beforeend', bubbleHtml);
                msgBox.scrollTop = msgBox.scrollHeight;
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.classList.remove('opacity-75');
            alert('Failed to send reply. Please try again.');
        });
    };

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }
});
</script>
@endpush
