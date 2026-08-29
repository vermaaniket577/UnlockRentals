@if(($site_settings['chatbot_enabled'] ?? '1') == '1')
<!-- Chatbot Overlay Button & Window -->
<div class="chatbot-trigger" id="chatTrigger" style="overflow: hidden; padding: 0; background: none; box-shadow: 0 10px 30px rgba(0,0,0,0.3);" title="Open Chat Support" role="button" aria-label="Open Chatbot">
    <video src="{{ asset('videos/chatbot.mp4') }}" autoplay loop muted playsinline style="width: 100%; height: 100%; object-fit: cover; pointer-events: none; border-radius: 50%;"></video>
</div>

<div class="chat-window" id="chatWindow" aria-live="polite">
    <div class="chat-header">
        <div class="chat-header-content">
            <div class="chat-avatar">
                <img src="{{ asset('images/icons/chatbot.png') }}" alt="Bot" title="Unlock Support Bot" style="width: 24px; height: 24px; object-fit: contain; filter: invert(1) grayscale(1) brightness(200%); mix-blend-mode: screen;">
            </div>
            <div class="chat-header-info">
                <div style="font-size: 14px; font-weight: 700; color: #ffffff;">Unlock Support</div>
                <p>Always Online</p>
            </div>
        </div>
        <i class="ph ph-x chat-close" id="chatClose" title="Close Chat" role="button" aria-label="Close Chat"></i>
    </div>
    <div class="chat-messages" id="chatMessages">
        <div class="msg bot">
            {{ $site_settings['bot_welcome_message'] ?? 'Hi there! 👋 Welcome to UnlockRentals. How can I assist you with your property search today?' }}
        </div>
    </div>
    <div class="chat-input-area">
        <input type="text" class="chat-input" id="chatInput" placeholder="Write a message..." autocomplete="off">
        <button class="chat-send-btn" id="chatSend" title="Send Message" aria-label="Send Message">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: #ffffff;"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
        </button>
    </div>
</div>

<script>
    (function() {
        function initChatbot() {
            const chatTrigger = document.getElementById('chatTrigger');
            const chatWindow = document.getElementById('chatWindow');
            const chatClose = document.getElementById('chatClose');
            const chatSend = document.getElementById('chatSend');
            const chatInput = document.getElementById('chatInput');
            const chatMessages = document.getElementById('chatMessages');
            
            if (!chatTrigger || !chatWindow) return;

            // Chat session ID
            let chatSessionId = localStorage.getItem('ur_chat_session');
            if (!chatSessionId) {
                chatSessionId = 'session_' + Math.random().toString(36).substr(2, 9) + '_' + Date.now();
                localStorage.setItem('ur_chat_session', chatSessionId);
            }

            // Toggle chat window open/close
            chatTrigger.addEventListener('click', function(e) {
                e.stopPropagation();
                chatWindow.classList.toggle('active');
                if (chatWindow.classList.contains('active') && chatInput) {
                    setTimeout(() => chatInput.focus(), 150);
                }
            });

            if (chatClose) {
                chatClose.addEventListener('click', function(e) {
                    e.stopPropagation();
                    chatWindow.classList.remove('active');
                });
            }

            // Close on clicking outside on desktop
            document.addEventListener('click', function(e) {
                if (chatWindow.classList.contains('active')) {
                    if (!chatWindow.contains(e.target) && !chatTrigger.contains(e.target)) {
                        chatWindow.classList.remove('active');
                    }
                }
            });

            // Load Chat History
            if (chatMessages) {
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
                        } else {
                            // Save the initial welcome message to the DB for history
                            const welcomeText = "{{ $site_settings['bot_welcome_message'] ?? 'Hi there! 👋 Welcome to UnlockRentals. How can I assist you with your property search today?' }}";
                            fetch("{{ route('chatbot.save') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    message: welcomeText,
                                    sender: 'bot',
                                    session_id: chatSessionId
                                })
                            }).catch(err => console.error('Chat save error:', err));
                        }
                    })
                    .catch(err => console.error('Chat history error:', err));
            }

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
                    chatWindow.classList.add('active');
                }, 400);
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
