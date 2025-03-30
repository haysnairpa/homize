<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JamOperasional extends Model
{
    protected $table = 'jam_operasional';
    protected $fillable = ['jam_buka', 'jam_tutup'];

    public function layanan()
    {
        return $this->hasOne(Layanan::class, 'id_jam_operasional');
    }

    public function hari()
    {
        return $this->belongsToMany(Hari::class, 'jam_operasional_hari', 'id_jam_operasional', 'id_hari');
    }
}
