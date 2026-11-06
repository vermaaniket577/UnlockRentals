@if(($site_settings['chatbot_enabled'] ?? '1') == '1')
<!-- Chatbot Backdrop Overlay for Mobile -->
<div class="chat-backdrop" id="chatBackdrop" onclick="window.closeSupportChat(event)"></div>

<!-- Chatbot Overlay Button & Window -->
<div class="chatbot-trigger" id="chatTrigger" onclick="window.toggleSupportChat(event)" style="overflow: hidden; padding: 0; background: linear-gradient(135deg, #1d4ed8, #2563eb); box-shadow: 0 10px 30px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; position: fixed;" title="Open Chat Support" role="button" aria-label="Open Chatbot">
    <video id="chatbotTriggerVideo" data-src="{{ asset('videos/chatbot.mp4') }}" loop muted playsinline preload="none" aria-label="Support chatbot animation" style="width: 100%; height: 100%; object-fit: cover; pointer-events: none; border-radius: 50%; opacity: 0; transition: opacity 0.3s ease;">
        <track kind="captions" src="data:text/vtt,WEBVTT" srclang="en" label="English" default>
    </video>
    <div id="chatbotTriggerPlaceholder" style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; pointer-events: none;">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
    </div>
</div>

<div class="chat-window" id="chatWindow" aria-live="polite" role="dialog" aria-modal="true" aria-label="Unlock Support Chat">
    <div class="chat-header">
        <div class="chat-header-content">
            <div class="chat-avatar" style="display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.18); border-radius: 50%; width: 28px; height: 28px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="11" width="18" height="10" rx="2"></rect><circle cx="12" cy="5" r="2"></circle><path d="M12 7v4"></path><line x1="8" y1="16" x2="8.01" y2="16"></line><line x1="16" y1="16" x2="16.01" y2="16"></line></svg>
            </div>
            <div class="chat-header-info">
                <div style="font-size: 14px; font-weight: 700; color: #ffffff;">Unlock Support</div>
                <p>Always Online</p>
            </div>
        </div>
        <button type="button" class="chat-close" id="chatClose" onclick="window.closeSupportChat(event)" title="Close Chat" aria-label="Close Chat">
            <i class="ph ph-x text-lg"></i>
        </button>
    </div>
    <div class="chat-messages" id="chatMessages">
        <div class="msg bot">
            {{ $site_settings['bot_welcome_message'] ?? 'Hi there! 👋 Welcome to UnlockRentals. How can I assist you with your property search today?' }}
        </div>
    </div>
    <div class="chat-input-area">
        <input type="text" class="chat-input" id="chatInput" placeholder="Write a message..." autocomplete="off">
        <button class="chat-send-btn" id="chatSend" title="Send Message" aria-label="Send Message" type="button">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: #ffffff;"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
        </button>
    </div>
</div>

<script>
    (function() {
        // Global Support Chat Control
        window.openSupportChat = function(e) {
            if (e && e.stopPropagation) e.stopPropagation();
            const chatWindow = document.getElementById('chatWindow');
            const chatBackdrop = document.getElementById('chatBackdrop');
            const chatInput = document.getElementById('chatInput');
            const chatMessages = document.getElementById('chatMessages');

            if (chatWindow) {
                chatWindow.classList.add('active');
                if (chatBackdrop) chatBackdrop.classList.add('active');
                if (chatMessages) {
                    setTimeout(() => {
                        chatMessages.scrollTop = chatMessages.scrollHeight;
                    }, 50);
                }
                if (chatInput) {
                    setTimeout(() => {
                        chatInput.focus();
                    }, 180);
                }
            } else {
                window.location.href = "{{ route('home') }}?open-chat=1";
            }
        };

        window.closeSupportChat = function(e) {
            if (e && e.stopPropagation) e.stopPropagation();
            const chatWindow = document.getElementById('chatWindow');
            const chatBackdrop = document.getElementById('chatBackdrop');
            if (chatWindow) chatWindow.classList.remove('active');
            if (chatBackdrop) chatBackdrop.classList.remove('active');
        };

        window.toggleSupportChat = function(e) {
            if (e && e.stopPropagation) e.stopPropagation();
            const chatWindow = document.getElementById('chatWindow');
            if (chatWindow && chatWindow.classList.contains('active')) {
                window.closeSupportChat(e);
            } else {
                window.openSupportChat(e);
            }
        };

        function initChatbot() {
            const chatTrigger = document.getElementById('chatTrigger');
            const chatWindow = document.getElementById('chatWindow');
            const chatSend = document.getElementById('chatSend');
            const chatInput = document.getElementById('chatInput');
            const chatMessages = document.getElementById('chatMessages');
            
            if (!chatWindow) return;

            // Chat session ID
            let chatSessionId = localStorage.getItem('ur_chat_session');
            if (!chatSessionId) {
                chatSessionId = 'session_' + Math.random().toString(36).substr(2, 9) + '_' + Date.now();
                localStorage.setItem('ur_chat_session', chatSessionId);
            }

            // Close on clicking outside on desktop
            document.addEventListener('click', function(e) {
                if (chatWindow && chatWindow.classList.contains('active')) {
                    if (chatWindow.contains(e.target) || 
                        (chatTrigger && chatTrigger.contains(e.target)) || 
                        (e.target.closest && e.target.closest('#mobile-support-nav-btn, [data-chat-trigger]'))) {
                        return;
                    }
                    window.closeSupportChat();
                }
            });

            // Close on ESC key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && chatWindow && chatWindow.classList.contains('active')) {
                    window.closeSupportChat();
                }
            });

            let historyLoaded = false;

            window.loadChatHistory = function() {
                if (historyLoaded || !chatMessages) return;
                historyLoaded = true;

                fetch(`/chatbot/history/${chatSessionId}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.messages && data.messages.length > 0) {
                            chatMessages.innerHTML = ''; 
                            data.messages.forEach(msg => {
                                const m = document.createElement('div');
                                m.className = `msg ${msg.sender}`;
                                m.textContent = msg.message;
                                chatMessages.appendChild(m);
                            });
                            chatMessages.scrollTop = chatMessages.scrollHeight;
                        }
                    })
                    .catch(err => console.error('Chat history error:', err));
            };

            // Hook loadChatHistory into openSupportChat
            const originalOpen = window.openSupportChat;
            window.openSupportChat = function(e) {
                window.loadChatHistory();
                if (originalOpen) originalOpen(e);
            };

            function addMessage(text, side) {
                if (!chatMessages) return;
                const msg = document.createElement('div');
                msg.className = `msg ${side}`;
                msg.textContent = text;
                chatMessages.appendChild(msg);
                chatMessages.scrollTop = chatMessages.scrollHeight;

                // Save message to database
                fetch("{{ route('chatbot.save') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        message: text,
                        sender: side,
                        session_id: chatSessionId
                    })
                }).catch(err => console.error('Chat save error:', err));
            }

            let botState = 'idle';
            let leadData = { name: '', phone: '' };

            function sendMessage() {
                if (!chatInput) return;
                const text = chatInput.value.trim();
                if (!text) return;
                addMessage(text, 'user');
                chatInput.value = '';
                
                setTimeout(() => {
                    if (botState === 'collecting_name') {
                        leadData.name = text;
                        botState = 'collecting_phone';
                        addMessage("Thank you! And what's your contact number so an agent can call you?", 'bot');
                        return;
                    }

                    if (botState === 'collecting_phone') {
                        leadData.phone = text;
                        botState = 'idle';
                        
                        // Submit lead
                        fetch("{{ route('chatbot.callback') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                name: leadData.name,
                                phone: leadData.phone,
                                session_id: chatSessionId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            addMessage("Perfect! I've shared your details with our elite concierge team. Expect a call shortly from one of our agents. 📞", 'bot');
                        })
                        .catch(err => {
                            addMessage("I'm sorry, I couldn't save your details. Please try again or call us at {{ $site_settings['site_phone'] ?? '+91 7974164274' }}.", 'bot');
                        });
                        return;
                    }

                    // Check for callback keywords
                    const lowerText = text.toLowerCase();
                    if (lowerText.includes('call') || lowerText.includes('callback') || lowerText.includes('agent') || lowerText.includes('contact')) {
                        botState = 'collecting_name';
                        addMessage("I'll be happy to arrange an elite concierge callback for you. Could you please share your full name?", 'bot');
                        return;
                    }

                    const dbResponses = {!! json_encode(array_values(array_filter(array_map('trim', explode("\n", $site_settings['bot_auto_responses'] ?? "That's a great question! Let me check our premium listings for you.\nI can certainly help you with that. Would you like to see properties in a specific city?\nOne of our agents will be happy to assist you further. Shall I book a callback for you?\nUnlockRentals offers the best verified properties in India. You're in good hands!"))))) !!};
                    
                    const fallbackResponses = [
                        "That's a great question! Let me check our premium listings for you.",
                        "I can certainly help you with that. Would you like to see properties in a specific city?",
                        "One of our agents will be happy to assist you further. Shall I book a callback for you?",
                        "UnlockRentals offers the best verified properties in India. You're in good hands!"
                    ];
                    
                    const responses = dbResponses.length > 0 ? dbResponses : fallbackResponses;
                    addMessage(responses[Math.floor(Math.random() * responses.length)], 'bot');
                }, 1000);
            }

            if (chatSend) {
                chatSend.addEventListener('click', sendMessage);
            }

            if (chatInput) {
                chatInput.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') sendMessage();
                });
            }

            // Open chat if ?open-chat=1 is in URL
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('open-chat') === '1') {
                setTimeout(() => {
                    window.openSupportChat();
                }, 400);
            }

            // High-Performance Deferred Video Loader (Saves ~1.3MB initial payload)
            function loadChatbotVideo() {
                var v = document.getElementById('chatbotTriggerVideo');
                if (!v || v.dataset.loaded) return;
                v.dataset.loaded = 'true';
                if (v.dataset.src) {
                    v.src = v.dataset.src;
                    v.oncanplay = function() {
                        v.style.opacity = '1';
                        var ph = document.getElementById('chatbotTriggerPlaceholder');
                        if (ph) ph.style.display = 'none';
                    };
                    v.load();
                    v.play().catch(function() {});
                }
            }
            ['scroll', 'mousemove', 'touchstart', 'click'].forEach(function(evt) {
                window.addEventListener(evt, loadChatbotVideo, { once: true, passive: true });
            });
            if ('requestIdleCallback' in window) {
                requestIdleCallback(function() { setTimeout(loadChatbotVideo, 3500); });
            } else {
                setTimeout(loadChatbotVideo, 4500);
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initChatbot);
        } else {
            initChatbot();
        }
    })();
</script>
@endif
