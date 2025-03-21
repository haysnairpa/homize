<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    public $table = "status";

    protected $fillable = [
        "nama_status",
    ];

    // one to one from status to pembayaran
    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class, "id", "id_status");
    }

    // one to one from status to booking
    public function booking()
    {
        return $this->belongsTo(Booking::class, "id", "id_status");
    }
}
