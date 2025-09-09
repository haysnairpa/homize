<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    protected $table = 'merchant';
    protected $primaryKey = 'id';
    protected $fillable = [
        'saldo',
        'id_user',
        'nama_usaha',
        'id_kategori',
        'profile_url',
        'alamat',
        'media_sosial',
        'verification_status',
        'rejection_reason',
        'verified_at',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    // one to many from merchant to layanan
    public function layanan()
    {
        return $this->hasMany(Layanan::class, 'id_merchant');
    }

    // one to many from merchant to layanan_merchant
    public function layanan_merchant()
    {
        return $this->hasMany(LayananMerchant::class, 'id_merchant');
    }

    // one to many from merchant to booking
    public function booking()
    {
        return $this->hasMany(Booking::class, 'id_merchant');
    }

    // one to many from merchant to riwayat_saldo_merchant
    public function riwayatSaldo()
    {
        return $this->hasMany(RiwayatSaldoMerchant::class, 'id_merchant');
    }

    // one to one from merchant to user
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // one to one from merchant to kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    // Relasi untuk user yang memfavoritkan
    public function toko_favorit()
    {
        return $this->hasMany(TokoFavorit::class, 'id_merchant');
    }

    public function rekening_merchant()
    {
        return $this->hasMany(RekeningMerchant::class, 'id_merchant');
    }
}
