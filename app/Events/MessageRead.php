<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageRead implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $conversationId;
    public $readBy;

    public function __construct(int $conversationId, string $readBy)
    {
        $this->conversationId = $conversationId;
        $this->readBy = $readBy; // 'user' or 'merchant'
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('conversation.' . $this->conversationId),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'conversation_id' => $this->conversationId,
            'read_by' => $this->readBy,
            'read_at' => now()->toISOString(),
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.read';
    }
}
