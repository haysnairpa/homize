<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Revisi extends Model
{
    public $table = "revisi";

    protected $fillable = [
        "harga",
        "durasi",
        "tipe_durasi",
    ];

    // one to one from revisi to tarif_layanan
    public function tarif_layanan()
    {
        return $this->belongsTo(TarifLayanan::class, "id", "id_revisi");
    }
}
