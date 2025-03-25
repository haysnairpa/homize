<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingSchedule extends Model
{
    public $table = "booking_schedule";

    protected $fillable = [
        "waktu_mulai",
        "waktu_selesai",
    ];

    // belongs to booking
    public function booking()
    {
        return $this->belongsTo(Booking::class, "id", "id_booking_schedule");
    }
}
