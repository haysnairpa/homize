<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hari extends Model
{
    public $table = "hari";

    protected $fillable = [
        "nama_hari",
    ];

    // one to one from hari to jam_operasional
    public function jam_operasional()
    {
        return $this->belongsTo(JamOperasional::class, "id", "id_hari");
    }
}
