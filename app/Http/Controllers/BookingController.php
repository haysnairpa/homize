<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingSchedule;
use App\Models\Layanan;
use App\Models\Pembayaran;
use App\Services\PromoCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingController extends Controller
{
    protected $promoService;

    public function __construct(PromoCodeService $promoService)
    {
        $this->promoService = $promoService;
    }
    public function create($id)
    {
        // Check if layanan exists first
        $layananExists = DB::table('layanan')->where('id', $id)->exists();
        
        if (!$layananExists) {
            abort(404, 'Service not found');
        }

        // Get navigation data
        app(HomeController::class)->navigation_data();

        // Ambil data layanan
        $layanan = DB::table('layanan as l')
            ->select([
                'l.*',
                'm.id as id_merchant',
                'm.nama_usaha',
                'm.profile_url',
                'm.alamat as alamat_merchant',
                'jo.jam_buka',
                'jo.jam_tutup',
                DB::raw('GROUP_CONCAT(DISTINCT h.nama_hari ORDER BY h.id) as hari'),
                'tl.harga',
                'tl.satuan',
                'tl.durasi',
                'tl.tipe_durasi',
                'tl.id_revisi'
            ])
            ->join('merchant as m', 'l.id_merchant', '=', 'm.id')
            ->join('jam_operasional as jo', 'l.id_jam_operasional', '=', 'jo.id')
            ->leftJoin('jam_operasional_hari as joh', 'jo.id', '=', 'joh.id_jam_operasional')
            ->leftJoin('hari as h', 'joh.id_hari', '=', 'h.id')
            ->leftJoin('tarif_layanan as tl', 'l.id', '=', 'tl.id_layanan')
            ->where('l.id', $id)
            ->groupBy([
                'l.id',
                'l.id_merchant',
                'l.id_jam_operasional',
                'l.id_sub_kategori',
                'l.nama_layanan',
                'l.deskripsi_layanan',
                'l.pengalaman',
                'l.created_at',
                'l.updated_at',
                'm.id',
                'm.nama_usaha',
                'm.profile_url',
                'm.alamat',
                'jo.jam_buka',
                'jo.jam_tutup',
                'tl.harga',
                'tl.satuan',
                'tl.durasi',
                'tl.tipe_durasi',
                'tl.id_revisi'
            ])
            ->first();

        if (!$layanan) {
            return redirect()->back()->with('error', 'Layanan tidak ditemukan');
        }

        // Ambil data user
        $user = Auth::user();

        // Hitung tanggal selesai berdasarkan durasi
        $tanggalMulai = Carbon::now();
        $tanggalSelesai = Carbon::now();

        if ($layanan->tipe_durasi == 'Jam') {
            $tanggalSelesai = $tanggalSelesai->addHours($layanan->durasi);
        } elseif ($layanan->tipe_durasi == 'Hari') {
            $tanggalSelesai = $tanggalSelesai->addDays($layanan->durasi);
        } elseif ($layanan->tipe_durasi == 'Pertemuan') {
            // Untuk pertemuan, anggap 1 pertemuan = 1 hari
            $tanggalSelesai = $tanggalSelesai->addDays($layanan->durasi);
        }

        return view('booking.create', compact('layanan', 'user', 'tanggalMulai', 'tanggalSelesai'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_layanan' => 'required|exists:layanan,id',
            'id_merchant' => 'required|exists:merchant,id',
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'country' => 'required|string',
            'city' => 'required|string',
            'province' => 'required|string',
            'postal_code' => 'required|string',
            'address' => 'required|string',
            'catatan' => 'nullable|string',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
            'tanggal_booking' => 'required|date',
            'kode_promo' => 'nullable|string|max:50',
        ]);

        // Format phone number to ensure it has +62 prefix
        $phoneNumber = $request->contact_phone;
        if (substr($phoneNumber, 0, 1) === '0') {
            $phoneNumber = '+62' . substr($phoneNumber, 1);
        } elseif (substr($phoneNumber, 0, 1) !== '+') {
            $phoneNumber = '+' . $phoneNumber;
        }

        try {
            DB::beginTransaction();

            // Buat booking schedule
            $waktuMulai = Carbon::parse($request->tanggal_booking);

            // Ambil durasi dari tarif layanan
            $tarifLayanan = DB::table('tarif_layanan')
                ->where('id_layanan', $request->id_layanan)
                ->first();

            if (!$tarifLayanan) {
                throw new \Exception('Tarif layanan tidak ditemukan');
            }

            // Initialize promo variables
            $originalAmount = $tarifLayanan->harga;
            $finalAmount = $originalAmount;
            $diskonAmount = 0;
            $diskonPercentage = 0;
            $kodePromoId = null;
            $promoValidation = null;

            // Validate and apply promo code if provided
            if ($request->filled('kode_promo')) {
                $promoValidation = $this->promoService->validatePromoCode(
                    $request->kode_promo,
                    Auth::id(),
                    $request->id_layanan,
                    $originalAmount
                );

                if (!$promoValidation['valid']) {
                    DB::rollBack();
                    return redirect()->back()
                        ->with('error', 'Kode promo tidak valid: ' . $promoValidation['message'])
                        ->withInput();
                }

                // Apply discount
                $discountResult = $this->promoService->calculateDiscount(
                    $promoValidation['promo'],
                    $originalAmount
                );

                $kodePromoId = $promoValidation['promo']->id;
                $diskonAmount = $discountResult['discount_amount'];
                $diskonPercentage = $discountResult['discount_percentage'];
                $finalAmount = $discountResult['final_amount'];
            }

            $waktuSelesai = clone $waktuMulai;

            if ($tarifLayanan) {
                if ($tarifLayanan->tipe_durasi == 'Jam') {
                    $waktuSelesai->addHours($tarifLayanan->durasi);
                } elseif ($tarifLayanan->tipe_durasi == 'Hari') {
                    $waktuSelesai->addDays($tarifLayanan->durasi);
                } elseif ($tarifLayanan->tipe_durasi == 'Pertemuan') {
                    $waktuSelesai->addDays($tarifLayanan->durasi);
                }
            }

            $bookingSchedule = BookingSchedule::create([
                'waktu_mulai' => $waktuMulai->format('Y-m-d H:i:s'),
                'waktu_selesai' => $waktuSelesai->format('Y-m-d H:i:s'),
            ]);

            // Buat booking
            $booking = Booking::create([
                'id_user' => Auth::id(),
                'id_merchant' => $request->id_merchant,
                'id_layanan' => $request->id_layanan,
                'status_proses' => 'Pending',
                'id_booking_schedule' => $bookingSchedule->id,
                'contact_email' => $request->contact_email,
                'contact_phone' => $phoneNumber,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'country' => $request->country,
                'city' => $request->city,
                'province' => $request->province,
                'alamat_pembeli' => $request->address,
                'postal_code' => $request->postal_code,
                'tanggal_booking' => $waktuMulai->format('H:i:s'),
                'catatan' => $request->catatan ?? '',
                'latitude' => $request->latitude ?: null,
                'longitude' => $request->longitude ?: null,
                'kode_promo_id' => $kodePromoId,
                'original_amount' => $originalAmount,
                'diskon_amount' => $diskonAmount,
                'diskon_percentage' => $diskonPercentage,
                'final_amount' => $finalAmount,
            ]);

            // Buat pembayaran dengan status_pembayaran string enum
            Pembayaran::create([
                'id_booking' => $booking->id,
                'amount' => $finalAmount,
                'original_amount' => $originalAmount,
                'discount_amount' => $diskonAmount,
                'method' => 'Belum dipilih',
                'status_pembayaran' => 'Pending',
                'payment_date' => now(),
            ]);

            // Record promo usage if promo was applied
            if ($kodePromoId && $promoValidation) {
                $this->promoService->recordUsage(
                    $promoValidation['promo'],
                    Auth::id(),
                    $booking->id,
                    $originalAmount,
                    $diskonAmount,
                    $finalAmount
                );
            }

            DB::commit();

            // Redirect ke halaman pembayaran
            return redirect()->route('pembayaran.show', $booking->id)->with('success', 'Booking berhasil dibuat! Silahkan lakukan pembayaran.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Validate promo code via AJAX
     */
    public function validatePromo(Request $request)
    {
        $request->validate([
            'kode_promo' => 'required|string|max:50',
            'id_layanan' => 'required|exists:layanan,id',
            'amount' => 'required|numeric|min:0'
        ]);

        try {
            $result = $this->promoService->validatePromoCode(
                $request->kode_promo,
                Auth::id(),
                $request->id_layanan,
                $request->amount
            );

            if ($result['valid']) {
                // Calculate discount for preview
                $discountResult = $this->promoService->calculateDiscount(
                    $result['promo'],
                    $request->amount
                );

                return response()->json([
                    'valid' => true,
                    'message' => 'Kode promo valid!',
                    'promo' => [
                        'nama' => $result['promo']->nama,
                        'tipe_diskon' => $result['promo']->tipe_diskon,
                        'nilai_diskon' => $result['promo']->nilai_diskon,
                        'is_exclusive' => $result['promo']->is_exclusive
                    ],
                    'discount' => [
                        'original_amount' => $request->amount,
                        'discount_amount' => $discountResult['discount_amount'],
                        'discount_percentage' => $discountResult['discount_percentage'],
                        'final_amount' => $discountResult['final_amount']
                    ]
                ]);
            } else {
                return response()->json([
                    'valid' => false,
                    'message' => $result['message']
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'valid' => false,
                'message' => 'Terjadi kesalahan saat memvalidasi kode promo'
            ], 500);
        }
    }

    /**
     * Remove promo code via AJAX
     */
    public function removePromo(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kode promo dihapus',
            'discount' => [
                'original_amount' => $request->amount,
                'discount_amount' => 0,
                'discount_percentage' => 0,
                'final_amount' => $request->amount
            ]
        ]);
    }
}
