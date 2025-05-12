<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    public $table = "pembayaran";

    protected $fillable = [
        "id_booking",
        "order_id",
        "amount",
        "method",
        "status_pembayaran",
        "snap_token",
        "payment_date",
        "otp_attempts",
    ];

    // one to one from pembayaran to booking
    public function booking()
    {
        return $this->belongsTo(Booking::class, "id_booking", "id");
    }


    // Helper method untuk cek status pembayaran
    public function isPending()
    {
        return $this->status_pembayaran === 'Pending';
    }

    public function isCompleted()
    {
        return $this->status_pembayaran === 'Selesai';
    }

    public function isCancelled()
    {
        return $this->status_pembayaran === 'Dibatalkan';
    }

    // Format amount to rupiah
    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }
    
    // OTP attempts tracking
    public function hasOtpAttempts()
    {
        $maxAttempts = config('xendit.max_otp_attempts', 3);
        return $this->otp_attempts < $maxAttempts;
    }
    
    // OTP remaining attempts
    public function remainingOtpAttempts()
    {
        $maxAttempts = config('xendit.max_otp_attempts', 3);
        return $maxAttempts - $this->otp_attempts;
    }
}
