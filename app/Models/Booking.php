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
        "kode_promo_id",
        "original_amount",
        "diskon_amount",
        "diskon_percentage",
        "final_amount",
        "status_proses",
        "id_booking_schedule",
        "contact_email",
        "contact_phone",
        "first_name",
        "last_name",
        "country",
        "city",
        "province",
        "postal_code",
        "tanggal_booking",
        "alamat_pembeli",
        "catatan",
        "longitude",
        "latitude",
        "customer_approval_status",
        "customer_approval_date",
        "protest_reason",
        "protest_date",
        "merchant_balance_added",
    ];

    protected $dates = [
        "created_at",
        "updated_at",
        "customer_approval_date",
        "protest_date",
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

    // many to one from booking to kode_promo
    public function kodePromo()
    {
        return $this->belongsTo(KodePromo::class, "kode_promo_id", "id");
    }

    // one to one from booking to penggunaan_kode_promo
    public function penggunaanKodePromo()
    {
        return $this->hasOne(PenggunaanKodePromo::class, "booking_id", "id");
    }


    // many to one from booking to booking_schedule
    public function booking_schedule()
    {
        return $this->belongsTo(BookingSchedule::class, "id_booking_schedule", "id");
    }

    /**
     * Cek apakah status bisa diubah ke status tertentu
     */
    public function canTransitionTo($targetStatusName)
    {
        $currentStatus = $this->status_proses;
        // valid transitions dalam Bahasa Indonesia
        $validTransitions = [
            'Pending' => ['Dikonfirmasi', 'Dibatalkan'],
            'Dikonfirmasi' => ['Sedang diproses', 'Dibatalkan'],
            'Sedang diproses' => ['Selesai', 'Dibatalkan'],
            'Selesai' => [],
            'Dibatalkan' => []
        ];
        if (isset($validTransitions[$currentStatus]) && in_array($targetStatusName, $validTransitions[$currentStatus])) {
            return true;
        }
        return false;
    }
    
    /**
     * Check if the order is awaiting customer approval
     * 
     * @return bool
     */
    public function needsCustomerApproval()
    {
        return $this->status_proses === 'Selesai' && $this->customer_approval_status === null;
    }

    /**
     * Check if the order has been approved by the customer
     * 
     * @return bool
     */
    public function isApprovedByCustomer()
    {
        return $this->customer_approval_status === 'approved';
    }

    /**
     * Check if the order has been protested by the customer
     * 
     * @return bool
     */
    public function isProtestedByCustomer()
    {
        return $this->customer_approval_status === 'protested';
    }

    /**
     * Check if the merchant balance has been added for this order
     * 
     * @return bool
     */
    public function isMerchantBalanceAdded()
    {
        return $this->merchant_balance_added;
    }
}
