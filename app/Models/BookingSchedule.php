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

    /**
     * Scope a query to only include schedules between two dates.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $startDate
     * @param  string  $endDate
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('waktu_mulai', [$startDate, $endDate]);
    }
}
