<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenggunaanKodePromo extends Model
{
    public $table = "penggunaan_kode_promo";

    protected $fillable = [
        "kode_promo_id",
        "user_id",
        "booking_id",
        "diskon_amount",
        "original_amount",
        "final_amount",
        "tanggal_digunakan",
    ];

    protected $dates = [
        "created_at",
        "updated_at",
        "tanggal_digunakan",
    ];

    protected $casts = [
        'tanggal_digunakan' => 'datetime',
        'diskon_amount' => 'decimal:2',
        'original_amount' => 'decimal:2',
        'final_amount' => 'decimal:2',
    ];

    // Relationships
    public function kodePromo()
    {
        return $this->belongsTo(KodePromo::class, 'kode_promo_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    // Scopes
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByPromo($query, $promoId)
    {
        return $query->where('kode_promo_id', $promoId);
    }

    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal_digunakan', [$startDate, $endDate]);
    }

    // Helper Methods
    public function getFormattedDiscount()
    {
        return 'Rp ' . number_format($this->diskon_amount, 0, ',', '.');
    }

    public function getFormattedOriginalAmount()
    {
        return 'Rp ' . number_format($this->original_amount, 0, ',', '.');
    }

    public function getFormattedFinalAmount()
    {
        return 'Rp ' . number_format($this->final_amount, 0, ',', '.');
    }

    public function getSavingsPercentage()
    {
        if ($this->original_amount <= 0) {
            return 0;
        }
        return round(($this->diskon_amount / $this->original_amount) * 100, 2);
    }
}