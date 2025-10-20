<x-app-layout>
    <div class="py-8 bg-homize-gray">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden" style="height: calc(100vh - 12rem);">
                <div class="flex h-full">
                <!-- Conversation List Sidebar -->
                <div class="w-full md:w-1/3 border-r border-gray-200 flex flex-col">
                    <!-- Header -->
                    <div class="p-4 border-b border-gray-200 bg-white">
                        <h2 class="text-xl font-semibold text-gray-800">Pesan</h2>
                    </div>

                    <!-- Conversation List -->
                    <div class="flex-1 overflow-y-auto bg-white" x-data="{ selected: {{ $conversations->count() > 0 ? 'null' : 'null' }} }">
                        @if($conversations->count() > 0)
                            @foreach($conversations as $conversation)
                                @include('chat.partials.conversation-item', ['conversation' => $conversation])
                            @endforeach
                        @else
                            <div class="flex flex-col items-center justify-center h-full text-center p-6">
                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                </svg>
                                <h3 class="mt-4 text-lg font-medium text-gray-900">Belum ada percakapan</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    Mulai percakapan dengan merchant untuk mendapatkan informasi layanan.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Message Area -->
                <div class="hidden md:flex md:w-2/3 flex-col bg-gray-50">
                    <div class="flex flex-col items-center justify-center h-full text-center p-6">
                        <svg class="w-20 h-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <h3 class="mt-4 text-xl font-medium text-gray-900">Pilih percakapan</h3>
                        <p class="mt-1 text-gray-500">
                            Pilih percakapan dari daftar untuk mulai berkomunikasi
                        </p>
                    </div>
                </div>
                            </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('chatList', () => ({
                init() {
                    this.checkUnreadMessages();
                    
                    // Check for new messages every 5 seconds
                    setInterval(() => {
                        this.checkUnreadMessages();
                    }, 5000);
                },
                
                async checkUnreadMessages() {
                    try {
                        const response = await fetch('/chat/unread-count');
                        const data = await response.json();
                        
                        if (data.success) {
                            // Update global unread counter in navigation if needed
                            const unreadCountElem = document.getElementById('chat-unread-count');
                            if (unreadCountElem) {
                                unreadCountElem.textContent = data.count;
                                unreadCountElem.classList.toggle('hidden', data.count === 0);
                            }
                        }
                    } catch (error) {
                        console.error('Error checking unread messages:', error);
                    }
                }
            }));
        });
    </script>
    @endpush
</x-app-layout>
