<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    public $table = "pembayaran";

    protected $fillable = [
        "id_booking",
        "amount",
        "method",
        "id_status",
        "payment_date",
    ];

    // one to one from pembayaran to booking
    public function booking()
    {
        return $this->belongsTo(Booking::class, "id_booking", "id");
    }

    // one to one from pembayaran to status
    public function status()
    {
        return $this->belongsTo(Status::class, "id_status", "id");
    }
}
