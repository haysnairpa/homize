<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    public $table = 'merchant';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_user',
        'id_sub_kategori',
        'nama_usaha',
        'profile_url',
        'alamat',
        'media_sosial',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

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

    // one to one from merchant to user
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    // one to one from merchant to sub_kategori
    public function sub_kategori()
    {
        return $this->belongsTo(SubKategori::class, 'id_sub_kategori', 'id');
    }
}
