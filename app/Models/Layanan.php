<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    public $table = "layanan";

    protected $fillable = [
        "id_merchant",
        "id_jam_operasional",
        "id_sub_kategori",
        "nama_layanan",
        "deskripsi_layanan",
        "pengalaman"
    ];

    protected $dates = [
        "created_at",
        "updated_at",
    ];

    // one to one from layanan to rating
    public function rating()
    {
        return $this->hasOne(Rating::class, "id", "id_layanan");
    }

    // one to one from layanan to tarif_layanan
    public function tarif_layanan()
    {
        return $this->hasOne(TarifLayanan::class, "id_layanan", "id");
    }

    // one to one from layanan to layanan_merchant
    public function layanan_merchant()
    {
        return $this->hasOne(LayananMerchant::class, "id_layanan", "id");
    }

    // one to one from layanan to sertifikasi
    public function sertifikasi()
    {
        return $this->hasMany(Sertifikasi::class, "id", "id_layanan");
    }

    // one to one from layanan to aset
    public function aset()
    {
        return $this->hasOne(Aset::class, 'id_layanan', 'id');
    }

    // one to one from layanan to booking
    public function booking()
    {
        return $this->belongsTo(Booking::class, "id", "id_layanan");
    }

    // one to one from layanan to jam_operasional
    public function jam_operasional()
    {
        return $this->belongsTo(JamOperasional::class, "id_jam_operasional", "id");
    }

    // many to one from layanan to sub_kategori
    // Layanan belongs to SubKategori (layanan has id_sub_kategori foreign key)
    public function subKategori()
    {
        return $this->belongsTo(SubKategori::class, "id_sub_kategori", "id");
    }

    // Alias for backward compatibility (snake_case)
    public function sub_kategori()
    {
        return $this->subKategori();
    }

    public function wishlist()
    {
        return $this->hasMany(Wishlist::class, 'id_layanan');
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'id_merchant');
    }
}
