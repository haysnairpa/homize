<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JamOperasional extends Model
{
    public $table = "jam_operasional";

    protected $fillable = [
        "id_hari",
        "jam_buka",
        "jam_tutup",
    ];

    // many to one from jam_operasional to layanan
    public function layanan()
    {
        return $this->belongsTo(Layanan::class, "id", "id_jam_operasional");
    }

    // one to one from jam_operasional to hari
    public function hari()
    {
        return $this->hasOne(Hari::class, "id_hari", "id");
    }
}
