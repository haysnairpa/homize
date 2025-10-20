<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;

class Conversation extends Model
{
    protected $fillable = [
        'id_booking',
        'id_user',
        'id_merchant',
        'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class, 'id_merchant');
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'id_booking');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'id_conversation');
    }

    public function latestMessage(): HasOne
    {
        return $this->hasOne(Message::class, 'id_conversation')->latestOfMany();
    }

    // Scopes
    public function scopeForUser($query, $userId)
    {
        return $query->where('id_user', $userId);
    }

    public function scopeForMerchant($query, $merchantId)
    {
        return $query->where('id_merchant', $merchantId);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('last_message_at', 'desc');
    }

    // Helper methods
    public function getUnreadCountForUser(): int
    {
        return $this->messages()
            ->where('sender_type', 'merchant')
            ->where('is_read', false)
            ->count();
    }

    public function getUnreadCountForMerchant(): int
    {
        return $this->messages()
            ->where('sender_type', 'user')
            ->where('is_read', false)
            ->count();
    }

    public function markAllAsReadForUser(): void
    {
        $this->messages()
            ->where('sender_type', 'merchant')
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }

    public function markAllAsReadForMerchant(): void
    {
        $this->messages()
            ->where('sender_type', 'user')
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }

    public function getOtherPartyAttribute()
    {
        $authUser = Auth::user();
        
        if (!$authUser) {
            return null;
        }
        
        // If current user is the customer
        if ($this->id_user === $authUser->id) {
            return [
                'type' => 'merchant',
                'data' => $this->merchant,
                'name' => $this->merchant->nama_usaha ?? 'Unknown',
                'photo' => $this->merchant->profile_url ?? null,
            ];
        }
        
        // If current user is the merchant
        if ($authUser->merchant && $this->id_merchant === $authUser->merchant->id) {
            return [
                'type' => 'user',
                'data' => $this->user,
                'name' => $this->user->nama ?? 'Unknown',
                'photo' => $this->user->profile_photo_url ?? null,
            ];
        }
        
        return null;
    }
}
