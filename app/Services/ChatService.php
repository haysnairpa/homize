<?php

namespace App\Services;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Models\Merchant;
use Illuminate\Support\Facades\DB;

class ChatService
{
    /**
     * Get or create a conversation between user and merchant
     */
    public function getOrCreateConversation(int $userId, int $merchantId, ?int $bookingId = null): Conversation
    {
        $conversation = Conversation::where('id_user', $userId)
            ->where('id_merchant', $merchantId)
            ->when($bookingId, function ($query) use ($bookingId) {
                return $query->where('id_booking', $bookingId);
            })
            ->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'id_user' => $userId,
                'id_merchant' => $merchantId,
                'id_booking' => $bookingId,
                'last_message_at' => now(),
            ]);
        }

        return $conversation;
    }

    /**
     * Send a message
     */
    public function sendMessage(
        int $conversationId,
        string $senderType,
        int $senderId,
        string $content,
        array $attachments = []
    ): Message {
        return DB::transaction(function () use ($conversationId, $senderType, $senderId, $content, $attachments) {
            // Create message
            $message = Message::create([
                'id_conversation' => $conversationId,
                'sender_type' => $senderType,
                'id_sender' => $senderId,
                'content' => $content,
            ]);

            // Handle attachments
            if (!empty($attachments)) {
                foreach ($attachments as $attachment) {
                    $message->attachments()->create($attachment);
                }
            }

            // Update conversation last_message_at
            Conversation::where('id', $conversationId)
                ->update(['last_message_at' => now()]);

            // Load relationships for broadcasting
            $message->load(['attachments', 'conversation']);

            return $message;
        });
    }

    /**
     * Get conversations for a user or merchant
     */
    public function getConversations(string $userType, int $userId, int $perPage = 20)
    {
        $query = Conversation::with([
            'user',
            'merchant',
            'latestMessage.attachments',
            'booking'
        ]);

        if ($userType === 'user') {
            $query->forUser($userId);
        } else {
            $query->forMerchant($userId);
        }

        return $query->recent()->paginate($perPage);
    }

    /**
     * Get messages for a conversation
     */
    public function getMessages(int $conversationId, int $perPage = 50)
    {
        return Message::forConversation($conversationId)
            ->with(['attachments'])
            ->orderBy('created_at', 'asc')
            ->paginate($perPage);
    }

    /**
     * Mark messages as read
     */
    public function markMessagesAsRead(int $conversationId, string $userType): int
    {
        $conversation = Conversation::findOrFail($conversationId);

        if ($userType === 'user') {
            $conversation->markAllAsReadForUser();
            return $conversation->getUnreadCountForUser();
        } else {
            $conversation->markAllAsReadForMerchant();
            return $conversation->getUnreadCountForMerchant();
        }
    }

    /**
     * Get total unread count for user or merchant
     */
    public function getUnreadCount(string $userType, int $userId): int
    {
        if ($userType === 'user') {
            return Message::whereHas('conversation', function ($query) use ($userId) {
                $query->where('id_user', $userId);
            })
            ->where('sender_type', 'merchant')
            ->unread()
            ->count();
        } else {
            return Message::whereHas('conversation', function ($query) use ($userId) {
                $query->where('id_merchant', $userId);
            })
            ->where('sender_type', 'user')
            ->unread()
            ->count();
        }
    }

    /**
     * Check if user can access conversation
     */
    public function canAccessConversation(Conversation $conversation, User $user): bool
    {
        // User is the customer
        if ($conversation->id_user === $user->id) {
            return true;
        }

        // User is the merchant
        if ($user->merchant && $conversation->id_merchant === $user->merchant->id) {
            return true;
        }

        return false;
    }
}
