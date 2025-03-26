<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    public $table = "booking";

    protected $fillable = [
        "id_user",
        "id_merchant",
        "id_layanan",
        "id_status",
        "id_booking_schedule",
        "tanggal_booking",
        "alamat_pembeli",
        "catatan",
        "longitude",
        "latitude",
    ];

    protected $dates = [
        "created_at",
        "updated_at",
    ];

    // one to one from booking to pembayaran
    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, "id_booking", "id");
    }

    // many to one from booking to user
    public function user()
    {
        return $this->belongsTo(User::class, "id_user", "id");
    }

    // many to one from booking to merchant
    public function merchant()
    {
        return $this->belongsTo(Merchant::class, "id_merchant", "id");
    }

    // many to one from booking to layanan
    public function layanan()
    {
        return $this->belongsTo(Layanan::class, "id_layanan", "id");
    }

    // many to one from booking to status
    public function status()
    {
        return $this->belongsTo(Status::class, "id_status", "id");
    }

    // many to one from booking to booking_schedule
    public function booking_schedule()
    {
        return $this->belongsTo(BookingSchedule::class, "id_booking_schedule", "id");
    }
}
