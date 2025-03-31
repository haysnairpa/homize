<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LayananView extends Model
{
    use HasFactory;

    protected $table = 'layanan_views';
    
    protected $fillable = [
        'id_layanan',
        'ip_address',
        'user_agent',
        'id_user'
    ];

    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'id_layanan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}