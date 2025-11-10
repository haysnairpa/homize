@php
    $auth = auth()->user();
    $isUserSide = $auth && ($conversation->id_user === $auth->id);
    $isMerchantSide = $auth && $auth->merchant && ($conversation->id_merchant === $auth->merchant->id);
    $initialUnread = $isUserSide ? $conversation->getUnreadCountForUser() : $conversation->getUnreadCountForMerchant();
@endphp
<div 
    class="border-b border-gray-200 hover:bg-gray-50 cursor-pointer" 
    x-data="{ unread: {{ $initialUnread }}, conversationId: {{ $conversation->id }}, activeId: {{ $activeId ?? 'null' }}, currentUserId: {{ auth()->id() }}, currentMerchantId: {{ auth()->user()->merchant->id ?? 'null' }} }"
    data-conversation-id="{{ $conversation->id }}"
    x-init="
        (() => {
            if (window.Echo) {
                window.Echo.private(`conversation.${conversationId}`).listen('.message.sent', (e) => {
                    const isMine = (e.sender_type === 'user' && e.id_sender === currentUserId) || (e.sender_type === 'merchant' && currentMerchantId && e.id_sender === currentMerchantId);
                    const text = e.content ? (e.content.length > 30 ? e.content.slice(0,30) + '…' : e.content) : '';
                    if ($refs.preview) { $refs.preview.textContent = text; }
                    if ($refs.time) {
                        const d = new Date(e.created_at);
                        $refs.time.textContent = d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                    }
                    if (!isMine && (!activeId || activeId !== conversationId)) {
                        unread = (unread || 0) + 1;
                    } else if (!isMine) {
                        unread = 0;
                    }
                });
            }
            // If this item is the active conversation, clear unread on load
            if (activeId && activeId === conversationId) {
                unread = 0;
            }
            window.addEventListener('chat:self-sent', (ev) => {
                if (!ev.detail) return;
                if (ev.detail.conversationId !== conversationId) return;
                const text = ev.detail.content ? (ev.detail.content.length > 30 ? ev.detail.content.slice(0,30) + '…' : ev.detail.content) : '';
                if ($refs.preview) { $refs.preview.textContent = text; }
                if ($refs.time && ev.detail.created_at) {
                    const d = new Date(ev.detail.created_at);
                    $refs.time.textContent = d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                }
                unread = 0;
            });
        })()
    "
    onclick="window.location.href = '{{ route('chat.show', $conversation->id) }}'"
>
    <div class="p-4 flex items-start space-x-3">
        <div class="flex-shrink-0">
            @php
                $otherParty = $conversation->getOtherPartyAttribute();
                $photo = $otherParty['photo'] ?? null;
                $name = $otherParty['name'] ?? 'Unknown';
            @endphp
            
            @if($photo)
                <img src="{{ $photo }}" alt="{{ $name }}" class="h-10 w-10 rounded-full">
            @else
                <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                    <span class="text-gray-600 font-medium text-sm">{{ substr($name, 0, 1) }}</span>
                </div>
            @endif
        </div>
        
        <div class="flex-1 min-w-0">
            <div class="flex justify-between">
                <p class="text-sm font-medium text-gray-900 truncate">
                    {{ $name }}
                </p>
                @if($conversation->last_message_at)
                <p class="text-xs text-gray-500"><span x-ref="time">{{ $conversation->last_message_at->diffForHumans() }}</span></p>
                @endif
            </div>
            
            @if($conversation->latestMessage)
                <p class="mt-1 text-sm text-gray-500 truncate" :class="{ 'font-semibold text-gray-800': unread > 0 }">
                    @if($conversation->latestMessage->attachments->count() > 0)
                        <svg class="inline-block w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                        </svg>
                    @endif
                    <span x-ref="preview">{{ Str::limit($conversation->latestMessage->content, 30) }}</span>
                </p>
            @else
                <p class="mt-1 text-sm text-gray-500 italic">
                    Belum ada pesan
                </p>
            @endif
            
            @php
                $unreadCount = $isUserSide 
                    ? $conversation->getUnreadCountForUser() 
                    : $conversation->getUnreadCountForMerchant();
            @endphp
            
            @if($unreadCount > 0)
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 mt-1">
                    {{ $unreadCount }} pesan baru
                </span>
            @endif
        </div>
    </div>
</div>
