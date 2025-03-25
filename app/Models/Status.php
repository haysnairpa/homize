<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    public $table = "status";

    protected $fillable = [
        "nama_status",
    ];

    // one to many from status to pembayaran
    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, "id_status", "id");
    }

    // one to many from status to booking
    public function booking()
    {
        return $this->hasMany(Booking::class, "id_status", "id");
    }
}
