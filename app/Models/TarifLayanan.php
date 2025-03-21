<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TarifLayanan extends Model
{
    public $table = "tarif_layanan";

    protected $fillable = [
        "id_revisi",
        "id_layanan",
        "harga",
        "satuan",
        "durasi",
        "tipe_durasi",
    ];

    // one to one from tarif_layanan to revisi
    public function revisi()
    {
        return $this->hasOne(Revisi::class, "id_revisi", "id");
    }

    // one to one from tarif_layanan to layanan
    public function layanan()
    {
        return $this->belongsTo(Layanan::class, "id_layanan", "id");
    }
}
