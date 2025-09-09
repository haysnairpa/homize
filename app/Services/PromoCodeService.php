<?php

namespace App\Services;

use App\Models\KodePromo;
use App\Models\PenggunaanKodePromo;
use App\Models\Booking;
use App\Models\Layanan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

class PromoCodeService
{
    /**
     * Validate promo code for a specific user and service
     */
    public function validatePromoCode($promoCode, $userId, $layananId, $originalAmount = null)
    {
        try {
            // Find promo code
            $kodePromo = KodePromo::byCode($promoCode)->first();
            
            if (!$kodePromo) {
                return $this->errorResponse('Kode promo tidak ditemukan.');
            }

            // Check if promo is active and valid
            if (!$kodePromo->isValid()) {
                if (!$kodePromo->status_aktif) {
                    return $this->errorResponse('Kode promo tidak aktif.');
                }
                if ($kodePromo->isExpired()) {
                    return $this->errorResponse('Kode promo sudah kadaluarsa.');
                }
                if (Carbon::now() < $kodePromo->tanggal_mulai) {
                    return $this->errorResponse('Kode promo belum berlaku.');
                }
            }

            // Check global usage limit
            if ($kodePromo->isGlobalUsageLimitReached()) {
                return $this->errorResponse('Kode promo sudah mencapai batas penggunaan maksimal.');
            }

            // Check user usage limit
            if ($kodePromo->isUserUsageLimitReached($userId)) {
                return $this->errorResponse('Anda sudah mencapai batas penggunaan kode promo ini.');
            }

            // Check if applicable to the service
            if (!$kodePromo->isApplicableToLayanan($layananId)) {
                return $this->errorResponse('Kode promo tidak berlaku untuk layanan ini.');
            }

            // Check minimum purchase requirement
            if ($originalAmount && $kodePromo->minimum_pembelian && $originalAmount < $kodePromo->minimum_pembelian) {
                $minFormatted = 'Rp ' . number_format($kodePromo->minimum_pembelian, 0, ',', '.');
                return $this->errorResponse("Minimum pembelian untuk kode promo ini adalah {$minFormatted}.");
            }

            // Check for exclusive promo conflicts
            if ($kodePromo->is_exclusive) {
                $hasOtherPromo = $this->userHasActiveExclusivePromo($userId, $kodePromo->id);
                if ($hasOtherPromo) {
                    return $this->errorResponse('Anda sudah menggunakan kode promo eksklusif lain. Kode promo eksklusif tidak dapat digabungkan.');
                }
            }

            // Calculate discount if amount provided
            $discountAmount = 0;
            if ($originalAmount) {
                $discountAmount = $kodePromo->calculateDiscount($originalAmount);
            }

            return $this->successResponse('Kode promo valid!', [
                'kode_promo' => $kodePromo,
                'discount_amount' => $discountAmount,
                'final_amount' => $originalAmount ? ($originalAmount - $discountAmount) : null,
                'discount_percentage' => $originalAmount && $originalAmount > 0 ? round(($discountAmount / $originalAmount) * 100, 2) : 0,
                'remaining_global_usage' => $kodePromo->getRemainingGlobalUsage(),
                'remaining_user_usage' => $kodePromo->getRemainingUserUsage($userId)
            ]);

        } catch (Exception $e) {
            return $this->errorResponse('Terjadi kesalahan saat memvalidasi kode promo: ' . $e->getMessage());
        }
    }

    /**
     * Apply promo code to a booking
     */
    public function applyPromoToBooking($promoCode, $userId, $bookingId, $originalAmount)
    {
        try {
            DB::beginTransaction();

            $booking = Booking::find($bookingId);
            if (!$booking) {
                throw new Exception('Booking tidak ditemukan.');
            }

            // Validate promo code
            $validation = $this->validatePromoCode($promoCode, $userId, $booking->id_layanan, $originalAmount);
            if (!$validation['success']) {
                throw new Exception($validation['message']);
            }

            $kodePromo = $validation['data']['kode_promo'];
            $discountAmount = $validation['data']['discount_amount'];
            $finalAmount = $validation['data']['final_amount'];
            $discountPercentage = $validation['data']['discount_percentage'];

            // Update booking with promo information
            $booking->update([
                'kode_promo_id' => $kodePromo->id,
                'original_amount' => $originalAmount,
                'diskon_amount' => $discountAmount,
                'diskon_percentage' => $discountPercentage,
                'final_amount' => $finalAmount
            ]);

            // Record promo usage
            PenggunaanKodePromo::create([
                'kode_promo_id' => $kodePromo->id,
                'user_id' => $userId,
                'booking_id' => $bookingId,
                'diskon_amount' => $discountAmount,
                'original_amount' => $originalAmount,
                'final_amount' => $finalAmount,
                'tanggal_digunakan' => Carbon::now()
            ]);

            DB::commit();

            return $this->successResponse('Kode promo berhasil diterapkan!', [
                'booking' => $booking->fresh(),
                'discount_amount' => $discountAmount,
                'final_amount' => $finalAmount,
                'savings_percentage' => $discountPercentage
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Gagal menerapkan kode promo: ' . $e->getMessage());
        }
    }

    /**
     * Remove promo code from booking
     */
    public function removePromoFromBooking($bookingId)
    {
        try {
            DB::beginTransaction();

            $booking = Booking::find($bookingId);
            if (!$booking) {
                throw new Exception('Booking tidak ditemukan.');
            }

            // Remove promo usage record
            PenggunaanKodePromo::where('booking_id', $bookingId)->delete();

            // Reset booking promo fields
            $booking->update([
                'kode_promo_id' => null,
                'original_amount' => null,
                'diskon_amount' => 0,
                'diskon_percentage' => 0,
                'final_amount' => null
            ]);

            DB::commit();

            return $this->successResponse('Kode promo berhasil dihapus dari booking.');

        } catch (Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Gagal menghapus kode promo: ' . $e->getMessage());
        }
    }

    /**
     * Get user's promo usage history
     */
    public function getUserPromoHistory($userId, $limit = 10)
    {
        $history = PenggunaanKodePromo::with(['kodePromo', 'booking.layanan'])
            ->byUser($userId)
            ->orderBy('tanggal_digunakan', 'desc')
            ->limit($limit)
            ->get();

        return $this->successResponse('Riwayat penggunaan kode promo berhasil diambil.', $history);
    }

    /**
     * Get available promo codes for user and service
     */
    public function getAvailablePromosForUser($userId, $layananId = null, $amount = null)
    {
        $query = KodePromo::valid();

        // Filter by service if provided
        if ($layananId) {
            $layanan = Layanan::find($layananId);
            if ($layanan && $layanan->subKategori) {
                $query->where(function($q) use ($layananId, $layanan) {
                    $q->where('target_type', 'all')
                      ->orWhere(function($sq) use ($layananId) {
                          $sq->where('target_type', 'service')
                             ->where('target_id', $layananId);
                      })
                      ->orWhere(function($sq) use ($layanan) {
                          $sq->where('target_type', 'category')
                             ->where('target_id', $layanan->subKategori->id_kategori);
                      });
                });
            }
        }

        // Filter by minimum purchase if amount provided
        if ($amount) {
            $query->where(function($q) use ($amount) {
                $q->whereNull('minimum_pembelian')
                  ->orWhere('minimum_pembelian', '<=', $amount);
            });
        }

        $promos = $query->get()->filter(function($promo) use ($userId) {
            return $promo->canBeUsedBy($userId);
        });

        return $this->successResponse('Kode promo tersedia berhasil diambil.', $promos->values());
    }

    /**
     * Check if user has active exclusive promo
     */
    private function userHasActiveExclusivePromo($userId, $excludePromoId = null)
    {
        $query = PenggunaanKodePromo::byUser($userId)
            ->whereHas('kodePromo', function($q) {
                $q->exclusive()->valid();
            })
            ->whereHas('booking', function($q) {
                $q->whereIn('status_proses', ['Pending', 'Dikonfirmasi', 'Sedang diproses']);
            });

        if ($excludePromoId) {
            $query->where('kode_promo_id', '!=', $excludePromoId);
        }

        return $query->exists();
    }

    /**
     * Get promo statistics for admin
     */
    public function getPromoStatistics($promoId = null, $startDate = null, $endDate = null)
    {
        $query = PenggunaanKodePromo::with(['kodePromo', 'user', 'booking']);

        if ($promoId) {
            $query->byPromo($promoId);
        }

        if ($startDate && $endDate) {
            $query->inDateRange($startDate, $endDate);
        }

        $usage = $query->get();

        $stats = [
            'total_usage' => $usage->count(),
            'total_discount_given' => $usage->sum('diskon_amount'),
            'total_original_amount' => $usage->sum('original_amount'),
            'total_final_amount' => $usage->sum('final_amount'),
            'average_discount' => $usage->avg('diskon_amount'),
            'unique_users' => $usage->pluck('user_id')->unique()->count(),
            'usage_by_date' => $usage->groupBy(function($item) {
                return $item->tanggal_digunakan->format('Y-m-d');
            })->map->count()
        ];

        return $this->successResponse('Statistik kode promo berhasil diambil.', $stats);
    }

    /**
     * Success response helper
     */
    private function successResponse($message, $data = null)
    {
        return [
            'success' => true,
            'message' => $message,
            'data' => $data
        ];
    }

    /**
     * Error response helper
     */
    private function errorResponse($message)
    {
        return [
            'success' => false,
            'message' => $message,
            'data' => null
        ];
    }
}