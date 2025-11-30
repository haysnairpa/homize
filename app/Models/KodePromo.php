<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class KodePromo extends Model
{
    public $table = "kode_promo";

    protected $fillable = [
        "kode",
        "nama",
        "tipe_diskon",
        "nilai_diskon",
        "tanggal_mulai",
        "tanggal_berakhir",
        "batas_penggunaan_global",
        "batas_penggunaan_per_user",
        "is_exclusive",
        "target_type",
        "target_id",
        "status_aktif",
        "deskripsi",
        "minimum_pembelian",
        "maksimum_diskon",
    ];

    protected $dates = [
        "created_at",
        "updated_at",
        "tanggal_mulai",
        "tanggal_berakhir",
    ];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_berakhir' => 'datetime',
        'is_exclusive' => 'boolean',
        'status_aktif' => 'boolean',
        'nilai_diskon' => 'decimal:2',
        'minimum_pembelian' => 'decimal:2',
        'maksimum_diskon' => 'decimal:2',
    ];

    // Relationships
    public function penggunaan()
    {
        return $this->hasMany(PenggunaanKodePromo::class, 'kode_promo_id');
    }

    public function booking()
    {
        return $this->hasMany(Booking::class, 'kode_promo_id');
    }

    // Target relationships (polymorphic-like behavior)
    public function targetKategori()
    {
        return $this->belongsTo(Kategori::class, 'target_id')->where('target_type', 'category');
    }

    public function targetLayanan()
    {
        return $this->belongsTo(Layanan::class, 'target_id')->where('target_type', 'service');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status_aktif', true);
    }

    public function scopeValid($query)
    {
        $now = Carbon::now();
        return $query->where('status_aktif', true)
                    ->where('tanggal_mulai', '<=', $now)
                    ->where('tanggal_berakhir', '>=', $now);
    }

    public function scopeByCode($query, $code)
    {
        return $query->where('kode', $code);
    }

    public function scopeExclusive($query)
    {
        return $query->where('is_exclusive', true);
    }

    // Business Logic Methods
    public function isValid()
    {
        $now = Carbon::now();
        return $this->status_aktif && 
               $this->tanggal_mulai <= $now && 
               $this->tanggal_berakhir >= $now;
    }

    public function isExpired()
    {
        return Carbon::now() > $this->tanggal_berakhir;
    }

    public function hasGlobalUsageLimit()
    {
        return !is_null($this->batas_penggunaan_global);
    }

    public function getGlobalUsageCount()
    {
        return $this->penggunaan()->count();
    }

    public function isGlobalUsageLimitReached()
    {
        if (!$this->hasGlobalUsageLimit()) {
            return false;
        }
        return $this->getGlobalUsageCount() >= $this->batas_penggunaan_global;
    }

    public function getUserUsageCount($userId)
    {
        return $this->penggunaan()->where('user_id', $userId)->count();
    }

    public function isUserUsageLimitReached($userId)
    {
        return $this->getUserUsageCount($userId) >= $this->batas_penggunaan_per_user;
    }

    public function canBeUsedBy($userId)
    {
        return $this->isValid() && 
               !$this->isGlobalUsageLimitReached() && 
               !$this->isUserUsageLimitReached($userId);
    }

    public function isApplicableToLayanan($layananId)
    {
        if ($this->target_type === 'all') {
            return true;
        }
        
        if ($this->target_type === 'service') {
            return $this->target_id == $layananId;
        }
        
        if ($this->target_type === 'category') {
            $layanan = Layanan::find($layananId);
            if ($layanan && $layanan->subKategori) {
                return $layanan->subKategori->id_kategori == $this->target_id;
            }
        }
        
        return false;
    }

    public function calculateDiscount($originalAmount)
    {
        // Check minimum purchase requirement
        if ($this->minimum_pembelian && $originalAmount < $this->minimum_pembelian) {
            return 0;
        }

        $discount = 0;
        
        if ($this->tipe_diskon === 'percentage') {
            $discount = ($originalAmount * $this->nilai_diskon) / 100;
            
            // Apply maximum discount limit if set
            if ($this->maksimum_diskon && $discount > $this->maksimum_diskon) {
                $discount = $this->maksimum_diskon;
            }
        } else {
            $discount = $this->nilai_diskon;
        }
        
        // Ensure discount doesn't exceed original amount
        return min($discount, $originalAmount);
    }

    public function getFormattedDiscount()
    {
        if ($this->tipe_diskon === 'percentage') {
            return $this->nilai_diskon . '%';
        }
        return 'Rp ' . number_format($this->nilai_diskon, 0, ',', '.');
    }

    public function getRemainingGlobalUsage()
    {
        if (!$this->hasGlobalUsageLimit()) {
            return null; // Unlimited
        }
        return max(0, $this->batas_penggunaan_global - $this->getGlobalUsageCount());
    }

    public function getRemainingUserUsage($userId)
    {
        return max(0, $this->batas_penggunaan_per_user - $this->getUserUsageCount($userId));
    }

    public function getTargetName()
    {
        switch ($this->target_type) {
            case 'all':
                return 'Semua Layanan';
            case 'category':
                $kategori = Kategori::find($this->target_id);
                return $kategori ? 'Kategori: ' . $kategori->nama : 'Kategori Tidak Ditemukan';
            case 'service':
                $layanan = Layanan::find($this->target_id);
                return $layanan ? 'Layanan: ' . $layanan->nama_layanan : 'Layanan Tidak Ditemukan';
            default:
                return 'Target Tidak Diketahui';
        }
    }
}