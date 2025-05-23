<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingSchedule;
use App\Models\Layanan;

use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingController extends Controller
{
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
            ]);

            // Buat pembayaran dengan status_pembayaran string enum
            Pembayaran::create([
                'id_booking' => $booking->id,
                'amount' => $tarifLayanan->harga,
                'method' => 'Belum dipilih',
                'status_pembayaran' => 'Pending',
                'payment_date' => now(),
            ]);

            DB::commit();

            // Redirect ke halaman pembayaran
            return redirect()->route('pembayaran.show', $booking->id)->with('success', 'Booking berhasil dibuat! Silahkan lakukan pembayaran.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
