<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message->load(['attachments']);
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('conversation.' . $this->message->id_conversation),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'id_conversation' => $this->message->id_conversation,
            'sender_type' => $this->message->sender_type,
            'id_sender' => $this->message->id_sender,
            'content' => $this->message->content,
            'is_read' => $this->message->is_read,
            'sender_name' => $this->message->sender_name,
            'sender_photo' => $this->message->sender_photo,
            'formatted_time' => $this->message->formatted_time,
            'attachments' => $this->message->attachments,
            'created_at' => $this->message->created_at->toISOString(),
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }
}
