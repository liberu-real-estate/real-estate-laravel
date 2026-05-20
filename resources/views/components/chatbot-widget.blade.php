<div x-data="chatbotWidget()" x-init="init()" class="fixed bottom-4 right-4 z-50">
    <!-- Chat Toggle Button -->
    <button 
        @click="toggleChat()" 
        x-show="!isOpen"
        class="bg-blue-600 hover:bg-blue-700 text-white rounded-full p-4 shadow-lg transition-all duration-200 flex items-center justify-center"
        aria-label="Open chat"
    >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
        </svg>
    </button>

    <!-- Chat Window -->
    <div 
        x-show="isOpen" 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform scale-95"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-95"
        class="bg-white rounded-lg shadow-2xl w-96 h-[600px] flex flex-col"
        style="display: none;"
    >
        <!-- Header -->
        <div class="bg-blue-600 text-white p-4 rounded-t-lg flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                <h3 class="font-semibold">Customer Support</h3>
            </div>
            <button @click="toggleChat()" class="text-white hover:text-gray-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Messages Container -->
        <div class="flex-1 overflow-y-auto p-4 space-y-4" x-ref="messagesContainer">
            <template x-for="message in messages" :key="message.id">
                <div :class="message.sender_type === 'user' ? 'flex justify-end' : 'flex justify-start'">
                    <div 
                        :class="message.sender_type === 'user' 
                            ? 'bg-blue-600 text-white' 
                            : message.sender_type === 'agent' 
                                ? 'bg-green-100 text-gray-800' 
                                : 'bg-gray-100 text-gray-800'"
                        class="rounded-lg p-3 max-w-[80%] shadow-sm"
                    >
                        <template x-if="message.sender_type === 'agent'">
                            <div class="text-xs font-semibold mb-1 text-green-700">Agent</div>
                        </template>
                        <p class="text-sm whitespace-pre-wrap" x-text="message.message"></p>
                        <span class="text-xs opacity-70 mt-1 block" x-text="formatTime(message.created_at)"></span>
                    </div>
                </div>
            </template>
            
            <!-- Typing Indicator -->
            <div x-show="isTyping" class="flex justify-start">
                <div class="bg-gray-100 rounded-lg p-3 shadow-sm">
                    <div class="flex space-x-1">
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Escalation Option -->
        <div x-show="showEscalationOption && !isEscalated" class="px-4 py-2 bg-yellow-50 border-t border-yellow-200">
            <button 
                @click="escalateToAgent()"
                class="text-sm text-blue-600 hover:text-blue-800 flex items-center gap-1"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Talk to a live agent
            </button>
        </div>

        <!-- Escalated Notice -->
        <div x-show="isEscalated" class="px-4 py-2 bg-green-50 border-t border-green-200">
            <p class="text-sm text-green-700">Connected to a live agent</p>
        </div>

        <!-- Input Area -->
        <div class="p-4 border-t border-gray-200">
            <form @submit.prevent="sendMessage()" class="flex gap-2">
                <input 
                    type="text" 
                    x-model="currentMessage" 
                    :disabled="isEscalated"
                    placeholder="Type your message..." 
                    class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600 disabled:bg-gray-100"
                />
                <button 
                    type="submit" 
                    :disabled="!currentMessage.trim() || isEscalated"
                    class="bg-blue-600 hover:bg-blue-700 text-white rounded-lg px-4 py-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function chatbotWidget() {
    return {
        isOpen: false,
        isTyping: false,
        isEscalated: false,
        showEscalationOption: false,
        sessionId: null,
        conversationId: null,
        currentMessage: '',
        messages: [],

        init() {
            // Start conversation when component initializes
            this.startConversation();
        },

        toggleChat() {
            this.isOpen = !this.isOpen;
            if (this.isOpen && this.messages.length === 0) {
                this.startConversation();
            }
        },

        async startConversation() {
            try {
                const response = await fetch('/api/chatbot/start', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                });

                const data = await response.json();
                this.sessionId = data.session_id;
                this.conversationId = data.conversation_id;
                
                this.messages.push({
                    id: Date.now(),
                    message: data.message,
                    sender_type: 'bot',
                    created_at: new Date().toISOString(),
                });
                
                this.scrollToBottom();
            } catch (error) {
                console.error('Failed to start conversation:', error);
            }
        },

        async sendMessage() {
            if (!this.currentMessage.trim()) return;

            const userMessage = this.currentMessage;
            this.currentMessage = '';

            // Add user message to chat
            this.messages.push({
                id: Date.now(),
                message: userMessage,
                sender_type: 'user',
                created_at: new Date().toISOString(),
            });

            this.scrollToBottom();
            this.isTyping = true;

            try {
                const response = await fetch('/api/chatbot/message', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        session_id: this.sessionId,
                        message: userMessage,
                    }),
                });

                const data = await response.json();
                
                setTimeout(() => {
                    this.isTyping = false;
                    
                    // Add bot response
                    this.messages.push({
                        id: Date.now() + 1,
                        message: data.message,
                        sender_type: data.escalated ? 'agent' : 'bot',
                        created_at: new Date().toISOString(),
                    });

                    if (data.suggest_escalation) {
                        this.showEscalationOption = true;
                    }

                    if (data.escalated) {
                        this.isEscalated = true;
                    }

                    this.scrollToBottom();
                }, 500);
            } catch (error) {
                this.isTyping = false;
                console.error('Failed to send message:', error);
            }
        },

        async escalateToAgent() {
            try {
                const response = await fetch('/api/chatbot/escalate', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        session_id: this.sessionId,
                        reason: 'User requested live agent assistance',
                    }),
                });

                const data = await response.json();
                
                if (data.success) {
                    this.isEscalated = true;
                    this.showEscalationOption = false;
                    this.messages.push({
                        id: Date.now(),
                        message: data.message,
                        sender_type: 'bot',
                        created_at: new Date().toISOString(),
                    });
                    this.scrollToBottom();
                }
            } catch (error) {
                console.error('Failed to escalate:', error);
            }
        },

        scrollToBottom() {
            this.$nextTick(() => {
                const container = this.$refs.messagesContainer;
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            });
        },

        formatTime(timestamp) {
            const date = new Date(timestamp);
            return date.toLocaleTimeString('en-US', { 
                hour: 'numeric', 
                minute: '2-digit',
                hour12: true 
            });
        }
    }
}
</script>
