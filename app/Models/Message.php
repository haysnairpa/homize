<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Message extends Model
{
    protected $fillable = [
        'id_conversation',
        'sender_type',
        'id_sender',
        'content',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    protected $appends = [
        'sender_name',
        'sender_photo',
        'formatted_time',
    ];

    // Relationships
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class, 'id_conversation');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(MessageAttachment::class, 'id_message');
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeForConversation($query, $conversationId)
    {
        return $query->where('id_conversation', $conversationId);
    }

    // Accessors
    public function getSenderNameAttribute(): string
    {
        if ($this->sender_type === 'user') {
            $user = User::find($this->id_sender);
            return $user ? $user->nama : 'Unknown User';
        } else {
            $merchant = Merchant::find($this->id_sender);
            return $merchant ? $merchant->nama_usaha : 'Unknown Merchant';
        }
    }

    public function getSenderPhotoAttribute(): ?string
    {
        if ($this->sender_type === 'user') {
            $user = User::find($this->id_sender);
            return $user ? $user->profile_photo_url : null;
        } else {
            $merchant = Merchant::find($this->id_sender);
            return $merchant ? $merchant->profile_url : null;
        }
    }

    public function getFormattedTimeAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    // Helper methods
    public function markAsRead(): void
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }

    public function isFromUser(): bool
    {
        return $this->sender_type === 'user';
    }

    public function isFromMerchant(): bool
    {
        return $this->sender_type === 'merchant';
    }
}
