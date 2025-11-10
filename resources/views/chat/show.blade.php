<x-app-layout>
    <div class="py-8 bg-homize-gray">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden" style="height: calc(100vh - 12rem);">
                <div class="flex h-full">
                    <!-- Conversation List Sidebar (Hidden on Mobile) -->
                    <div class="hidden md:block md:w-1/3 border-r border-gray-200 flex-col">
                        <!-- Header -->
                        <div class="p-4 border-b border-gray-200 bg-white">
                            <h2 class="text-xl font-semibold text-gray-800">Pesan</h2>
                        </div>

                        <!-- Back to List on Mobile -->
                        <div class="md:hidden p-4 flex items-center space-x-2">
                            <a href="{{ route('chat.index') }}" class="flex items-center text-homize-blue">
                                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                <span>Kembali</span>
                            </a>
                        </div>

                        <!-- Conversation List -->
                        <div class="overflow-y-auto bg-white" style="height: calc(100% - 65px);">
                            @foreach($conversations as $item)
                                @include('chat.partials.conversation-item', ['conversation' => $item, 'activeId' => $conversation->id])
                            @endforeach
                        </div>
                    </div>

                    <!-- Message Area -->
                    <div class="w-full md:w-2/3 flex flex-col bg-gray-50" 
                         x-data="chatMessages({ conversationId: {{ $conversation->id }}, userType: '{{ $userType }}', currentUserId: {{ auth()->id() }}, currentMerchantId: {{ auth()->user()->merchant->id ?? 'null' }} })"
                         x-init="init()">

                        <!-- Chat Header -->
                        <div class="px-4 py-3 bg-white border-b border-gray-200 flex items-center justify-between">
                            <div class="flex items-center">
                                <!-- Back Button (Mobile Only) -->
                                <a href="{{ route('chat.index') }}" class="md:hidden mr-2 text-gray-500 hover:text-gray-700">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                    </svg>
                                </a>

                                @php
                                    $otherParty = $conversation->getOtherPartyAttribute();
                                    $photo = $otherParty['photo'] ?? null;
                                    $name = $otherParty['name'] ?? 'Unknown';
                                @endphp

                                <!-- Profile Picture -->
                                @if($photo)
                                    <img src="{{ $photo }}" alt="{{ $name }}" class="h-10 w-10 rounded-full mr-3">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center mr-3">
                                        <span class="text-gray-600 font-medium text-lg">{{ substr($name, 0, 1) }}</span>
                                    </div>
                                @endif

                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">{{ $name }}</h3>
                                    @if($conversation->booking)
                                        <p class="text-xs text-gray-500">
                                            <span class="inline-block px-2 py-0.5 bg-gray-100 rounded-full text-gray-600">
                                                #{{ $conversation->booking->id }} {{ $conversation->booking->layanan->nama_layanan ?? '' }}
                                            </span>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Messages -->
                        <div class="flex-1 overflow-y-auto p-4 space-y-3 bg-gray-50" x-ref="messagesContainer">
                            <template x-for="(message, i) in messages" :key="message.id ?? ('tmp-' + i)">
                                <div :class="`flex ${message.sender_type === userType ? 'justify-end' : 'justify-start'}`">
                                    <div :class="`max-w-xs lg:max-w-md px-4 py-2 rounded-lg ${message.sender_type === userType ? 'bg-homize-blue text-white' : 'bg-white border border-gray-200'}`"
                                         style="word-break: break-word;">
                                        <div x-text="message.content" class="text-sm"></div>
                                        
                                        <!-- Attachments -->
                                        <div class="mt-2 space-y-2" x-show="message.attachments && message.attachments.length > 0">
                                            <template x-for="attachment in message.attachments" :key="attachment.id">
                                                <div class="text-xs" :class="message.sender_type === userType ? 'text-white' : 'text-gray-600'">
                                                    <!-- Image Preview -->
                                                    <template x-if="isImage(attachment.file_type)">
                                                        <a :href="attachment.file_url" target="_blank" class="block">
                                                            <img :src="attachment.file_url" 
                                                                 :alt="attachment.file_name" 
                                                                 class="max-h-32 rounded shadow-sm mx-auto my-1">
                                                        </a>
                                                    </template>
                                                    
                                                    <!-- Other File Types -->
                                                    <template x-if="!isImage(attachment.file_type)">
                                                        <a :href="attachment.file_url" 
                                                           target="_blank" 
                                                           class="flex items-center p-2 bg-opacity-20 rounded" 
                                                           :class="message.sender_type === userType ? 'bg-white' : 'bg-gray-100'">
                                                            <!-- File Icon based on type -->
                                                            <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                                </path>
                                                            </svg>
                                                            <div class="truncate">
                                                                <div class="font-medium" x-text="attachment.file_name"></div>
                                                                <div x-text="attachment.formatted_size"></div>
                                                            </div>
                                                        </a>
                                                    </template>
                                                </div>
                                            </template>
                                        </div>

                                        <!-- Pending Files Preview (optimistic) -->
                                        <div class="mt-2 space-y-1" x-show="message.pendingFiles && message.pendingFiles.length > 0">
                                            <template x-for="(file, idx) in message.pendingFiles" :key="idx">
                                                <div class="text-xs" :class="message.sender_type === userType ? 'text-white' : 'text-gray-600'">
                                                    <span x-text="file.name"></span>
                                                </div>
                                            </template>
                                        </div>

                                        <!-- Time + Status (sending/sent/failed/read) -->
                                        <div class="text-xs mt-1 flex items-center gap-1" :class="message.sender_type === userType ? 'justify-end text-blue-100' : 'justify-end text-gray-500'">
                                            <span x-text="formatTime(message.created_at)"></span>
                                            <template x-if="isOwn(message)">
                                                <span class="inline-flex items-center">
                                                    <template x-if="message.status === 'sending'">
                                                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v2m0 12v2m8-8h2M2 12H4m13.657-6.343l1.414 1.414M4.929 19.071l1.414-1.414m0-10.314L4.93 6.343M18.364 19.071l-1.414-1.414" />
                                                        </svg>
                                                    </template>
                                                    <template x-if="message.status === 'failed'">
                                                        <button type="button" class="ml-1 text-red-300 hover:text-red-500" @click.stop="retryMessage(message)">
                                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                        </button>
                                                    </template>
                                                    <template x-if="!message.status || message.status === 'sent'">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path x-show="!message.is_read" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                            <g x-show="message.is_read">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l4 4L14 5" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l4 4L21 5" />
                                                            </g>
                                                        </svg>
                                                    </template>
                                                </span>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Message Input -->
                        <div class="border-t border-gray-200 bg-white p-4">
                            <form @submit.prevent="sendMessage" class="flex flex-col space-y-3">
                                <div class="flex items-end space-x-2">
                                    <div class="flex-1">
                                        <textarea 
                                            x-model="messageContent" 
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-homize-blue focus:border-transparent"
                                            placeholder="Ketik pesan..."
                                            rows="1"
                                            @keydown.enter.prevent="$event.shiftKey ? null : sendMessage()"
                                        ></textarea>
                                    </div>
                                    
                                    <!-- File Button -->
                                    <div class="flex-shrink-0">
                                        <label class="cursor-pointer p-2 rounded-full hover:bg-gray-100 inline-flex">
                                            <input type="file" class="hidden" multiple @change="handleFileSelect" accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.zip,.rar">
                                            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                            </svg>
                                        </label>
                                    </div>
                                    
                                    <!-- Send Button -->
                                    <button 
                                        type="submit" 
                                        class="bg-homize-blue text-white rounded-full p-2 focus:outline-none hover:bg-blue-600"
                                        :disabled="isSubmitting || (!messageContent.trim() && !attachments.length)"
                                        :class="{'opacity-50': isSubmitting || (!messageContent.trim() && !attachments.length)}"
                                    >
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                        </svg>
                                    </button>
                                </div>
                                
                                <!-- File Previews -->
                                <div class="flex flex-wrap gap-2" x-show="attachments.length > 0">
                                    <template x-for="(file, index) in attachments" :key="index">
                                        <div class="relative bg-gray-100 rounded-md p-2 flex items-center">
                                            <span class="text-xs truncate max-w-xs" x-text="file.name"></span>
                                            <button type="button" @click="removeFile(index)" class="ml-2 text-gray-500 hover:text-red-500">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('chatMessages', ({ conversationId, userType, currentUserId, currentMerchantId }) => ({
                messages: @json($messages->items()),
                messageContent: '',
                conversationId,
                userType,
                currentUserId,
                currentMerchantId,
                isSubmitting: false,
                attachments: [],
                lastMessageId: {{ $messages->count() > 0 ? $messages->last()->id : 0 }},
                seenIds: {},
                _markTimer: null,
                
                init() {
                    if (Array.isArray(this.messages)) {
                        this.messages.forEach(m => { if (m && m.id != null) { this.seenIds[Number(m.id)] = true; } });
                    } else {
                        this.messages = [];
                    }
                    this.scrollToBottom();
                    this.setupPusher();
                    this.markAsRead();
                    
                    // Poll for messages every 3 seconds as fallback
                    setInterval(() => {
                        this.checkNewMessages();
                    }, 3000);

                    // Mark as read when window/tab regains focus
                    window.addEventListener('focus', () => this.markAsReadDebounced());
                    document.addEventListener('visibilitychange', () => {
                        if (document.visibilityState === 'visible') this.markAsReadDebounced();
                    });

                    // Mark as read when scrolled near bottom
                    this.$nextTick(() => {
                        const c = this.$refs.messagesContainer;
                        if (!c) return;
                        c.addEventListener('scroll', () => {
                            const nearBottom = c.scrollHeight - c.scrollTop - c.clientHeight < 40;
                            if (nearBottom) this.markAsReadDebounced();
                        });
                    });
                },
                
                isOwn(message) {
                    return message && message.sender_type === this.userType;
                },
                generateClientId() {
                    return 'c' + Math.random().toString(36).slice(2) + Date.now().toString(36);
                },
                
                addMessageIfNew(msg) {
                    if (!msg || msg.id == null) return;
                    const idNum = Number(msg.id);
                    if (!Array.isArray(this.messages)) this.messages = [];
                    if (this.seenIds[idNum]) return;
                    this.seenIds[idNum] = true;
                    const exists = this.messages.some(m => m && Number(m.id) === idNum);
                    if (!exists) {
                        this.messages.push({ ...msg, status: 'sent' });
                        if (!this.lastMessageId || idNum > Number(this.lastMessageId)) {
                            this.lastMessageId = idNum;
                        }
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
                
                setupPusher() {
                    if (window.Echo) {
                        window.Echo.private(`conversation.${this.conversationId}`)
                            .listen('.message.sent', (e) => {
                                const isMine = (e.sender_type === 'user' && e.id_sender === this.currentUserId)
                                    || (e.sender_type === 'merchant' && this.currentMerchantId && e.id_sender === this.currentMerchantId);

                                if (!isMine) {
                                    this.addMessageIfNew(e);
                                    this.markAsRead();
                                    this.scrollToBottom();
                                }
                            })
                            .listen('.message.read', (e) => {
                                // Update read status if needed
                                if (e.read_by !== this.userType) {
                                    // Update messages to show read status
                                    this.messages.forEach(message => {
                                        if (message.sender_type === this.userType && !message.is_read) {
                                            message.is_read = true;
                                            message.read_at = e.read_at;
                                        }
                                    });
                                }
                            });
                    }
                },
                
                async sendMessage() {
                    if ((!this.messageContent.trim() && this.attachments.length === 0) || this.isSubmitting) {
                        return;
                    }
                    
                    this.isSubmitting = true;
                    const clientId = this.generateClientId();
                    const temp = {
                        id: null,
                        client_id: clientId,
                        sender_type: this.userType,
                        id_sender: this.userType === 'merchant' ? this.currentMerchantId : this.currentUserId,
                        content: this.messageContent,
                        attachments: [],
                        is_read: false,
                        created_at: new Date().toISOString(),
                        status: 'sending',
                        pendingFiles: [...this.attachments]
                    };
                    this.messages.push(temp);
                    this.scrollToBottom();
                    
                    try {
                        const formData = new FormData();
                        formData.append('conversation_id', this.conversationId);
                        formData.append('content', this.messageContent);
                        
                        // Add files if any
                        this.attachments.forEach(file => {
                            formData.append('attachments[]', file);
                        });
                        
                        const response = await fetch('/chat/send', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: formData
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            const idx = this.messages.findIndex(m => m.client_id === clientId);
                            if (idx !== -1) {
                                this.messages[idx] = { ...data.message, status: 'sent' };
                                this.seenIds[Number(data.message.id)] = true;
                                this.lastMessageId = Number(data.message.id);
                            } else {
                                this.addMessageIfNew(data.message);
                            }

                            this.messageContent = '';
                            this.attachments = [];

                            // Notify sidebar items to update preview immediately
                            window.dispatchEvent(new CustomEvent('chat:self-sent', {
                                detail: {
                                    conversationId: this.conversationId,
                                    content: data.message.content,
                                    created_at: data.message.created_at
                                }
                            }));

                            this.scrollToBottom();
                        } else {
                            const idx = this.messages.findIndex(m => m.client_id === clientId);
                            if (idx !== -1) this.messages[idx].status = 'failed';
                        }
                    } catch (error) {
                        const idx = this.messages.findIndex(m => m.client_id === clientId);
                        if (idx !== -1) this.messages[idx].status = 'failed';
                    } finally {
                        this.isSubmitting = false;
                    }
                },

                async retryMessage(message) {
                    if (!message || message.status !== 'failed') return;
                    message.status = 'sending';
                    try {
                        const formData = new FormData();
                        formData.append('conversation_id', this.conversationId);
                        formData.append('content', message.content || '');
                        if (message.pendingFiles && message.pendingFiles.length) {
                            message.pendingFiles.forEach(file => formData.append('attachments[]', file));
                        }
                        const response = await fetch('/chat/send', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: formData
                        });
                        const data = await response.json();
                        if (data.success) {
                            const idx = this.messages.findIndex(m => m.client_id === message.client_id);
                            if (idx !== -1) {
                                this.messages[idx] = { ...data.message, status: 'sent' };
                                this.seenIds[Number(data.message.id)] = true;
                                this.lastMessageId = Number(data.message.id);
                            } else {
                                this.addMessageIfNew(data.message);
                            }
                            window.dispatchEvent(new CustomEvent('chat:self-sent', {
                                detail: { conversationId: this.conversationId, content: data.message.content, created_at: data.message.created_at }
                            }));
                            this.scrollToBottom();
                        } else {
                            message.status = 'failed';
                        }
                    } catch (e) {
                        message.status = 'failed';
                    }
                },
                
                async checkNewMessages() {
                    if (this.isSubmitting) return;
                    
                    try {
                        const response = await fetch(`/chat/messages/${this.conversationId}?last_message_id=${this.lastMessageId}`);
                        const data = await response.json();
                        
                        if (data.success && data.messages.data.length > 0) {
                            // Add new messages
                            const newMessages = data.messages.data;
                            
                            // Check if we have any new messages
                            if (newMessages.length > 0) {
                                // Add messages to our list without duplicates
                                newMessages.forEach(msg => this.addMessageIfNew(msg));
                                
                                // Mark as read
                                this.markAsRead();
                                
                                // Scroll to bottom
                                this.scrollToBottom();
                            }
                        }
                    } catch (error) {
                        console.error('Error checking for new messages:', error);
                    }
                },
                
                async markAsRead() {
                    try {
                        await fetch('/chat/mark-read', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ conversation_id: this.conversationId })
                        });
                    } catch (error) {
                        console.error('Error marking messages as read:', error);
                    }
                },
                
                formatTime(timestamp) {
                    const date = new Date(timestamp);
                    const now = new Date();
                    
                    // Same day, just show time
                    if (date.toDateString() === now.toDateString()) {
                        return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                    } else {
                        // Different day, show date and time
                        return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' }) + ' ' + 
                               date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                    }
                },
                
                isImage(fileType) {
                    return ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/jpg'].includes(fileType);
                },
                
                handleFileSelect(event) {
                    const files = Array.from(event.target.files);
                    
                    // Check file count
                    if (this.attachments.length + files.length > 5) {
                        alert('Maksimal 5 file dapat diunggah sekaligus.');
                        return;
                    }
                    
                    // Check file size (max 10MB each)
                    const maxSize = 10 * 1024 * 1024; // 10MB
                    
                    for (const file of files) {
                        if (file.size > maxSize) {
                            alert(`File "${file.name}" terlalu besar. Ukuran maksimal adalah 10MB.`);
                            continue;
                        }
                        
                        this.attachments.push(file);
                    }
                    
                    // Reset the input
                    event.target.value = '';
                },
                
                removeFile(index) {
                    this.attachments.splice(index, 1);
                }
            }));
        });
    </script>
    @endpush
</x-app-layout>
