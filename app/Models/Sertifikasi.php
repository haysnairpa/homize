<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sertifikasi extends Model
{
    public $table = "sertifikasi";

    protected $fillable = [
        "id_layanan",
        "nama_sertifikasi",
        "media_url",
    ];

    // many to one from sertifikasi to layanan
    public function layanan()
    {
        return $this->belongsTo(Layanan::class, "id_layanan", "id");
    }
}
