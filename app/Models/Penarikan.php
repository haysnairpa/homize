<?php
	namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penarikan extends Model
{
    use HasFactory;
    protected $table = 'penarikan';
    protected $fillable = [
        'rekening_merchant_id',
        'jumlah',
        'status',
        'catatan',
    ];

    public function rekening_merchant()
    {
        return $this->belongsTo(RekeningMerchant::class);
    }

    // Untuk akses merchant langsung dari penarikan:
    public function merchant()
    {
        return $this->hasOneThrough(
            Merchant::class,
            RekeningMerchant::class,
            'id', // Foreign key RekeningMerchant di Penarikan
            'id', // Foreign key Merchant di RekeningMerchant
            'rekening_merchant_id', // Local key di Penarikan
            'id_merchant' // Local key di RekeningMerchant
        );
    }
}
