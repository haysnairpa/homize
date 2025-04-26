<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paid extends Model
{
    public $table = "paid";


    protected $fillable = [
        "status_pembayaran"
    ];

    // one to one from paid to booking
    public function booking()
    {
        return $this->hasOne(Booking::class, "id_paid", "id");
    }
}
