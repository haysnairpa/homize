<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hari extends Model
{
    protected $table = 'hari';
    protected $fillable = ['nama_hari'];

    public function jamOperasional()
    {
        return $this->belongsToMany(JamOperasional::class, 'jam_operasional_hari', 'id_hari', 'id_jam_operasional');
    }
}
