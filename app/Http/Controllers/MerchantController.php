<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use App\Models\SubKategori;
use App\Models\Booking;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Layanan;
use App\Models\TarifLayanan;
use App\Models\Aset;
use App\Models\Sertifikasi;
use App\Models\JamOperasional;
use App\Models\Hari;
use App\Models\LayananMerchant;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CreateLayananRequest;
use App\Models\Revisi;
use Carbon\Carbon;
use App\Events\OrderCompleted;
use Illuminate\Support\Facades\Session;

class MerchantController extends Controller
{
    public function index()
    {
        // Cek apakah user sudah terdaftar sebagai merchant
        $merchant = Merchant::where('id_user', Auth::id())->first();

        if ($merchant) {
            // Jika sudah terdaftar, redirect ke dashboard merchant
            return redirect()->route('merchant.dashboard')
                ->with('info', 'Anda sudah terdaftar sebagai merchant');
        }

        return view('merchant');
    }

    public function step1()
    {
        // Cek apakah user sudah terdaftar sebagai merchant
        $merchant = Merchant::where('id_user', Auth::id())->first();

        if ($merchant) {
            // Jika sudah terdaftar, redirect ke dashboard merchant
            return redirect()->route('merchant.dashboard')
                ->with('info', 'Anda sudah terdaftar sebagai merchant');
        }
        
        $subKategori = SubKategori::all();
        
        // Ambil data dari session jika ada
        $oldData = Session::get('merchant_registration', []);
        
        return view('merchant.register.step1', compact('subKategori', 'oldData'));
    }

    public function storeStep1(Request $request)
    {
        $validated = $request->validate([
            'nama_usaha' => 'required|string|max:255',
            'id_sub_kategori' => 'required|exists:sub_kategori,id',
            'profile_url' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'nama_usaha.required' => 'Nama usaha wajib diisi',
            'id_sub_kategori.required' => 'Kategori usaha wajib dipilih',
            'profile_url.required' => 'Foto profil usaha wajib diunggah',
            'profile_url.image' => 'File harus berupa gambar',
            'profile_url.mimes' => 'Format gambar harus jpeg, png, atau jpg',
            'profile_url.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        // Simpan file ke storage
        $profilePath = $request->file('profile_url')->store('temp-merchant-profiles', 'public');

        // Simpan data ke session
        $merchantData = [
            'nama_usaha' => $validated['nama_usaha'],
            'id_sub_kategori' => $validated['id_sub_kategori'],
            'profile_url' => $profilePath,
        ];
        
        Session::put('merchant_registration', $merchantData);

        return redirect()->route('merchant.register.step2');
    }

    public function step2()
    {
        // Cek apakah ada data di session
        if (!Session::has('merchant_registration')) {
            return redirect()->route('merchant.register.step1')
                ->with('error', 'Silakan isi informasi dasar terlebih dahulu');
        }
        
        $merchantData = Session::get('merchant_registration');
        
        return view('merchant.register.step2', compact('merchantData'));
    }

    public function storeStep2(Request $request)
    {
        // Cek apakah ada data di session
        if (!Session::has('merchant_registration')) {
            return redirect()->route('merchant.register.step1')
                ->with('error', 'Silakan isi informasi dasar terlebih dahulu');
        }

        $validated = $request->validate([
            'alamat' => 'required|string',
            'instagram' => 'nullable|string',
            'facebook' => 'nullable|string',
            'whatsapp' => 'required|string',
        ], [
            'alamat.required' => 'Alamat wajib diisi',
            'whatsapp.required' => 'Nomor WhatsApp wajib diisi',
        ]);

        // Ambil data dari session
        $merchantData = Session::get('merchant_registration');
        
        // Buat merchant baru
        $merchant = new Merchant();
        $merchant->id_user = Auth::id();
        $merchant->nama_usaha = $merchantData['nama_usaha'];
        $merchant->id_sub_kategori = $merchantData['id_sub_kategori'];
        $merchant->profile_url = $merchantData['profile_url'];
        $merchant->alamat = $validated['alamat'];
        $merchant->media_sosial = json_encode([
            'instagram' => $validated['instagram'] ?? '',
            'facebook' => $validated['facebook'] ?? '',
            'whatsapp' => $validated['whatsapp']
        ]);
        $merchant->save();

        // Hapus data dari session
        Session::forget('merchant_registration');

        return redirect()->route('merchant.dashboard')
            ->with('success', 'Pendaftaran merchant berhasil! Selamat datang di dashboard merchant.');
    }

    public function backToStep1()
    {
        // Tidak perlu menghapus data dari session
        return redirect()->route('merchant.register.step1');
    }

    public function dashboard()
    {
        $merchant = Merchant::where('id_user', Auth::id())->firstOrFail();

        // Hitung pesanan aktif (status: Confirmed, In Progress)
        $pesananAktif = DB::select(
            "SELECT COUNT(*) as total
                                FROM booking b
                                JOIN status s ON b.id_status = s.id
                                WHERE b.id_merchant = ?
                                AND s.nama_status IN ('Confirmed', 'In Progress')",
            [$merchant->id]
        );

        // Hitung pendapatan bulan ini
        $pendapatan = DB::select(
            "SELECT SUM(p.amount) as total
                                FROM booking b
                                JOIN pembayaran p ON p.id_booking = b.id
                                JOIN status s ON p.id_status = s.id
                                WHERE b.id_merchant = ? 
                                AND s.nama_status = 'Payment Completed'
                                AND MONTH(p.payment_date) = MONTH(CURRENT_DATE())
                                AND YEAR(p.payment_date) = YEAR(CURRENT_DATE())",
            [$merchant->id]
        );

        // Hitung jumlah pelanggan unik bulan ini
        $pelanggan = DB::select(
            "SELECT COUNT(DISTINCT b.id_user) as total
                                FROM booking b
                                WHERE b.id_merchant = ?
                                AND MONTH(b.created_at) = MONTH(CURRENT_DATE())
                                AND YEAR(b.created_at) = YEAR(CURRENT_DATE())",
            [$merchant->id]
        );

        // Hitung rating rata-rata
        $rating = DB::select(
            "SELECT COALESCE(AVG(r.rate), 0) as avg_rating
                            FROM rating r
                            JOIN layanan l ON r.id_layanan = l.id
                            WHERE l.id_merchant = ?",
            [$merchant->id]
        );

        // Ambil pesanan terbaru
        $recentOrders = DB::select(
            "SELECT b.id, u.nama as nama_user, l.nama_layanan, s.nama_status, 
                                    b.tanggal_booking, p.amount, bs.waktu_mulai, bs.waktu_selesai
                                    FROM booking b
                                    JOIN users u ON u.id = b.id_user
                                    JOIN layanan l ON l.id = b.id_layanan
                                    JOIN status s ON b.id_status = s.id
                                    JOIN booking_schedule bs ON bs.id = b.id_booking_schedule
                                    JOIN pembayaran p ON p.id_booking = b.id
                                    WHERE b.id_merchant = ?
                                    ORDER BY b.created_at DESC
                                    LIMIT 5",
            [$merchant->id]
        );

        $stats = [
            'pesananAktif' => $pesananAktif[0]->total ?? 0,
            'pendapatan' => $pendapatan[0]->total ?? 0,
            'pelanggan' => $pelanggan[0]->total ?? 0,
            'rating' => $rating[0]->avg_rating ?? 0
        ];

        return view('merchant.dashboard', compact('merchant', 'stats', 'recentOrders'));
    }

    public function profile()
    {
        $merchant = Merchant::where('id_user', Auth::id())->firstOrFail();
        $subKategori = SubKategori::all();

        // Decode media sosial dari JSON ke array
        $mediaSosial = json_decode($merchant->media_sosial, true) ?? [];

        return view('merchant.profile', compact('merchant', 'subKategori', 'mediaSosial'));
    }

    public function updateProfile(Request $request)
    {
        $merchant = Merchant::where('id_user', Auth::id())->firstOrFail();

        $validated = $request->validate([
            'nama_usaha' => 'required|string|max:255',
            'id_sub_kategori' => 'required|exists:sub_kategori,id',
            'alamat' => 'required|string',
            'instagram' => 'nullable|string',
            'facebook' => 'nullable|string',
            'whatsapp' => 'required|string',
            'profile_url' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('profile_url')) {
            $profilePath = $request->file('profile_url')->store('merchant-profiles', 'public');
            $merchant->profile_url = $profilePath;
        }

        $merchant->nama_usaha = $validated['nama_usaha'];
        $merchant->id_sub_kategori = $validated['id_sub_kategori'];
        $merchant->alamat = $validated['alamat'];
        $merchant->media_sosial = json_encode([
            'instagram' => $validated['instagram'] ?? '',
            'facebook' => $validated['facebook'] ?? '',
            'whatsapp' => $validated['whatsapp']
        ]);

        $merchant->save();

        return redirect()->route('merchant.profile')->with('success', 'Profil berhasil diperbarui');
    }

    public function services()
    {
        $merchant = Merchant::where('id_user', Auth::id())->firstOrFail();
        $layanan = Layanan::where('id_merchant', $merchant->id)->get();
        return view('merchant.services', compact('merchant', 'layanan'));
    }

    public function orders()
    {
        $merchant = Merchant::where('id_user', Auth::id())->firstOrFail();

        $orders = DB::select("SELECT b.id, u.nama as nama_user, l.nama_layanan, s.nama_status, b.tanggal_booking, 
                            p.amount, bs.waktu_mulai, bs.waktu_selesai, b.alamat_pembeli, b.catatan
                            FROM booking b
                            JOIN users u ON u.id = b.id_user
                            JOIN layanan l ON l.id = b.id_layanan
                            JOIN status s ON b.id_status = s.id
                            JOIN booking_schedule bs ON bs.id = b.id_booking_schedule
                            JOIN pembayaran p ON p.id_booking = b.id
                            WHERE b.id_merchant = ?
                            ORDER BY b.created_at DESC", [$merchant->id]);

        $statuses = Status::whereIn('nama_status', ['Pending', 'Confirmed', 'In Progress', 'Completed', 'Cancelled'])->get();

        return view('merchant.orders', compact('merchant', 'orders', 'statuses'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status_id' => 'required|exists:status,id',
        ]);

        $booking = Booking::with(['status', 'pembayaran', 'pembayaran.status'])->findOrFail($id);
        $merchant = Merchant::where('id_user', Auth::id())->firstOrFail();

        // Pastikan booking ini milik merchant yang sedang login
        if ($booking->id_merchant != $merchant->id) {
            return redirect()->route('merchant.orders')->with('error', 'Anda tidak memiliki akses untuk mengubah status pesanan ini');
        }

        // Ambil status saat ini dan status yang diminta
        $currentStatus = $booking->status->nama_status;
        $requestedStatus = Status::find($validated['status_id'])->nama_status;

        // Cek apakah ini adalah status pembayaran yang tidak boleh diubah manual
        $paymentStatuses = ['Payment Pending', 'Payment Completed', 'Payment Failed'];
        if (in_array($currentStatus, $paymentStatuses) || in_array($requestedStatus, $paymentStatuses)) {
            return redirect()->route('merchant.orders')->with('error', 'Status pembayaran tidak dapat diubah secara manual. Status ini berubah otomatis berdasarkan aktivitas transaksi.');
        }

        // Validasi alur status sesuai flowchart
        $validTransition = false;

        switch ($currentStatus) {
            case 'Pending':
                // Dari Pending hanya bisa ke Confirmed atau Cancelled
                if ($requestedStatus == 'Confirmed' || $requestedStatus == 'Cancelled') {
                    $validTransition = true;
                }
                break;
            case 'Confirmed':
                // Dari Confirmed hanya bisa ke In Progress
                if ($requestedStatus == 'In Progress') {
                    $validTransition = true;
                }
                break;
            case 'In Progress':
                // Dari In Progress hanya bisa ke Completed
                if ($requestedStatus == 'Completed') {
                    $validTransition = true;
                }
                break;
            default:
                // Status lain tidak bisa diubah
                $validTransition = false;
        }

        if (!$validTransition) {
            return redirect()->route('merchant.orders')->with('error', 'Perubahan status tidak valid. Mohon ikuti alur status yang benar.');
        }

        // Update status booking
        $booking->id_status = $validated['status_id'];
        $booking->save();

        // Jika status berubah menjadi Completed, trigger event OrderCompleted
        if ($requestedStatus == 'Completed') {
            // Load relasi yang dibutuhkan
            $booking->load(['user', 'merchant', 'merchant.user', 'layanan', 'pembayaran', 'booking_schedule']);
            
            Log::info('Akan memicu event OrderCompleted', [
                'booking_id' => $booking->id
            ]);
            
            // Trigger event OrderCompleted
            event(new OrderCompleted($booking));
            
            Log::info('Event OrderCompleted berhasil dipicu', [
                'booking_id' => $booking->id
            ]);
            
            // // Alternatif: Kirim email langsung jika event tidak berfungsi
            // try {
            //     if ($booking->user) {
            //         Mail::to($booking->user->email)
            //             ->send(new OrderCompletedNotification($booking));
                    
            //         Log::info('Email notifikasi pesanan selesai berhasil dikirim langsung', [
            //             'booking_id' => $booking->id,
            //             'user_email' => $booking->user->email
            //         ]);
            //     }
            // } catch (\Exception $e) {
            //     Log::error('Gagal mengirim email notifikasi pesanan selesai langsung', [
            //         'booking_id' => $booking->id,
            //         'error' => $e->getMessage()
            //     ]);
            // }
        }

        return redirect()->route('merchant.orders')->with('success', 'Status pesanan berhasil diperbarui');
    }

    public function analytics()
    {
        $merchant = Merchant::where('id_user', Auth::id())->firstOrFail();

        // Data placeholder untuk analytics
        $monthlyStats = [
            'pendapatan' => 0,
            'pesanan' => 0,
            'pelanggan' => 0,
            'rating' => 0
        ];

        // Hitung pendapatan bulan ini
        $pendapatan = DB::select(
            "SELECT SUM(p.amount) as total
                                FROM booking b
                                JOIN pembayaran p ON p.id_booking = b.id
                                JOIN status s ON p.id_status = s.id
                                WHERE b.id_merchant = ? 
                                AND s.nama_status = 'Payment Completed'
                                AND MONTH(p.payment_date) = MONTH(CURRENT_DATE())
                                AND YEAR(p.payment_date) = YEAR(CURRENT_DATE())",
            [$merchant->id]
        );

        if ($pendapatan && isset($pendapatan[0]->total)) {
            $monthlyStats['pendapatan'] = $pendapatan[0]->total;
        }

        // Hitung jumlah pesanan bulan ini
        $pesanan = DB::select(
            "SELECT COUNT(*) as total
                            FROM booking b
                            WHERE b.id_merchant = ?
                            AND MONTH(b.created_at) = MONTH(CURRENT_DATE())
                            AND YEAR(b.created_at) = YEAR(CURRENT_DATE())",
            [$merchant->id]
        );

        if ($pesanan && isset($pesanan[0]->total)) {
            $monthlyStats['pesanan'] = $pesanan[0]->total;
        }

        // Hitung jumlah pelanggan unik bulan ini
        $pelanggan = DB::select(
            "SELECT COUNT(DISTINCT b.id_user) as total
                                FROM booking b
                                WHERE b.id_merchant = ?
                                AND MONTH(b.created_at) = MONTH(CURRENT_DATE())
                                AND YEAR(b.created_at) = YEAR(CURRENT_DATE())",
            [$merchant->id]
        );

        if ($pelanggan && isset($pelanggan[0]->total)) {
            $monthlyStats['pelanggan'] = $pelanggan[0]->total;
        }

        return view('merchant.analytics', compact('merchant', 'monthlyStats'));
    }

    public function storeLayanan(CreateLayananRequest $request)
    {
        try {
            DB::beginTransaction();

            // Validasi merchant
            $merchant = Merchant::where('id_user', Auth::id())->firstOrFail();

            // Validasi data sebelum create
            if (!isset($request->jam_operasional['hari']) || !isset($request->jam_operasional['jam_buka']) || !isset($request->jam_operasional['jam_tutup'])) {
                throw new \Exception('Data jam operasional tidak lengkap');
            }

            // Create jam operasional
            $jamOperasional = JamOperasional::create([
                'id_hari' => $request->jam_operasional['hari'],
                'jam_buka' => $request->jam_operasional['jam_buka'],
                'jam_tutup' => $request->jam_operasional['jam_tutup']
            ]);

            // Create layanan
            $layanan = Layanan::create([
                'id_merchant' => $merchant->id,
                'id_jam_operasional' => $jamOperasional->id,
                'id_sub_kategori' => $merchant->id_sub_kategori,
                'nama_layanan' => $request->nama_layanan,
                'deskripsi_layanan' => $request->deskripsi_layanan,
                'pengalaman' => $request->pengalaman
            ]);

            // Create layanan merchant
            LayananMerchant::create([
                'id_layanan' => $layanan->id,
                'id_merchant' => $merchant->id
            ]);

            // Cek apakah ada revisi
            $revisiId = 1; // Default revision ID
            if ($request->has('revisi_harga') && $request->has('revisi_durasi') && $request->has('revisi_tipe_durasi')) {
                // Create revisi baru
                $revisi = Revisi::create([
                    'harga' => $request->revisi_harga,
                    'durasi' => $request->revisi_durasi,
                    'tipe_durasi' => $request->revisi_tipe_durasi
                ]);
                $revisiId = $revisi->id;
            }

            // Create tarif layanan
            TarifLayanan::create([
                'id_layanan' => $layanan->id,
                'id_revisi' => $revisiId,
                'harga' => $request->harga,
                'satuan' => $request->satuan,
                'durasi' => $request->durasi,
                'tipe_durasi' => $request->tipe_durasi
            ]);

            // Handle aset uploads
            if ($request->hasFile('aset_layanan')) {
                foreach ($request->file('aset_layanan') as $file) {
                    $path = $file->store('layanan-assets', 'public');
                    Aset::create([
                        'id_layanan' => $layanan->id,
                        'deskripsi' => $file->getClientOriginalName(),
                        'media_url' => $path
                    ]);
                }
            }

            // Handle sertifikasi uploads
            if ($request->has('sertifikasi')) {
                foreach ($request->sertifikasi as $sertifikasi) {
                    if (isset($sertifikasi['file'])) {
                        $path = $sertifikasi['file']->store('layanan-certificates', 'public');
                        Sertifikasi::create([
                            'id_layanan' => $layanan->id,
                            'nama_sertifikasi' => $sertifikasi['nama'],
                            'media_url' => $path
                        ]);
                    }
                }
            }

            DB::commit();

            // Log success untuk debugging
            Log::info('Layanan berhasil dibuat', [
                'id_merchant' => $merchant->id,
                'nama_layanan' => $request->nama_layanan
            ]);

            return redirect()->route('merchant.services')
                ->with('success', 'Layanan berhasil ditambahkan! Silakan cek di daftar layanan Anda.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating layanan: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Gagal menambahkan layanan: ' . $e->getMessage());
        }
    }

    public function orderDetail($id)
    {
        $merchant = Merchant::where('id_user', Auth::id())->firstOrFail();

        // Ambil detail pesanan
        $order = DB::selectOne(
            "SELECT b.id, u.nama as nama_user, u.email,
                            l.nama_layanan, l.deskripsi_layanan, s.nama_status, 
                            b.tanggal_booking, p.amount, bs.waktu_mulai, bs.waktu_selesai,
                            b.alamat_pembeli, b.catatan, b.longitude, b.latitude,
                            tl.harga, tl.durasi, tl.tipe_durasi
                            FROM booking b
                            JOIN users u ON u.id = b.id_user
                            JOIN layanan l ON l.id = b.id_layanan
                            JOIN status s ON b.id_status = s.id
                            JOIN booking_schedule bs ON bs.id = b.id_booking_schedule
                            JOIN pembayaran p ON p.id_booking = b.id
                            JOIN tarif_layanan tl ON tl.id_layanan = l.id
                            WHERE b.id = ? AND b.id_merchant = ?",
            [$id, $merchant->id]
        );

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan'
            ], 404);
        }

        // Format data untuk ditampilkan
        $orderDetail = [
            'id' => $order->id,
            'tanggal' => Carbon::parse($order->tanggal_booking)->format('d/m/Y'),
            'pelanggan' => [
                'nama' => $order->nama_user,
                'email' => $order->email,
                'alamat' => $order->alamat_pembeli
            ],
            'layanan' => [
                'nama' => $order->nama_layanan,
                'harga' => $order->harga,
                'durasi' => $order->durasi . ' ' . $order->tipe_durasi
            ],
            'jadwal' => [
                'mulai' => Carbon::parse($order->waktu_mulai)->format('H:i'),
                'selesai' => Carbon::parse($order->waktu_selesai)->format('H:i')
            ],
            'catatan' => $order->catatan,
            'total' => $order->amount,
            'status' => $order->nama_status
        ];

        return response()->json([
            'success' => true,
            'data' => $orderDetail
        ]);
    }

    public function getMerchantDetail($id)
    {
        // Get merchant details with ratings and stats
        $merchant = DB::selectOne(
            "SELECT m.id, m.nama_usaha, m.profile_url, m.alamat, m.media_sosial,
            COALESCE(AVG(r.rate), 0) as rating_avg,
            COUNT(DISTINCT r.id) as rating_count,
            COUNT(DISTINCT b.id) as transaction_count,
            COUNT(DISTINCT tf.id) as follower_count
        FROM merchant m
        LEFT JOIN layanan l ON l.id_merchant = m.id
        LEFT JOIN rating r ON r.id_layanan = l.id
        LEFT JOIN booking b ON b.id_merchant = m.id
        LEFT JOIN toko_favorit tf ON tf.id_merchant = m.id
        WHERE m.id = ?
        GROUP BY m.id, m.nama_usaha, m.profile_url, m.alamat, m.media_sosial",
            [$id]
        );

        if (!$merchant) {
            return redirect()->route('home')->with('error', 'Merchant tidak ditemukan');
        }

        // Get merchant's services with ratings and transaction counts
        $layanan = DB::select(
            "SELECT 
                l.id, 
                l.nama_layanan, 
                l.deskripsi_layanan, 
                l.pengalaman, 
                tl.harga,
                a.media_url AS url_layanan,
                sk.nama AS nama_sub_kategori, COALESCE((
                SELECT AVG(rate)
                FROM rating r
                WHERE r.id_layanan = l.id), 0) AS rating_avg, 
                (
                SELECT COUNT(*)
                FROM rating r
                WHERE r.id_layanan = l.id) AS rating_count, 
                (
                SELECT COUNT(*)
                FROM booking b
                WHERE b.id_layanan = l.id) AS transaction_count
                FROM layanan l
                LEFT JOIN sub_kategori sk ON sk.id = l.id_sub_kategori
                LEFT JOIN tarif_layanan tl ON tl.id_layanan = l.id
                LEFT JOIN aset a ON a.id_layanan = l.id
                WHERE l.id_merchant = ?
                ORDER BY l.created_at DESC;
                ",
            [$id]
        );

        $kategori = DB::select("SELECT nama, id FROM kategori");
        $sub_kategori = DB::select("SELECT s.nama, s.seri_sub_kategori, s.id, s.id_kategori
                                FROM sub_kategori s");
        $ids = DB::select("SELECT `id` FROM `sub_kategori`;");

        return view('merchant.detail_merchant', compact('merchant', 'layanan', 'kategori', 'sub_kategori', 'ids'));
    }

    public function sortLayanan($id, Request $request)
    {
        $sort = $request->query('sort', 'newest');
        $query = "SELECT 
            l.id, 
            l.nama_layanan, 
            l.deskripsi_layanan, 
            l.pengalaman,
            l.created_at,
            tl.harga,
            a.media_url AS url_layanan,
            sk.nama AS nama_sub_kategori,
            COALESCE((
                SELECT AVG(rate)
                FROM rating r
                WHERE r.id_layanan = l.id), 0) AS rating_avg,
            (
                SELECT COUNT(*)
                FROM rating r
                WHERE r.id_layanan = l.id) AS rating_count,
            (
                SELECT COUNT(*)
                FROM booking b
                WHERE b.id_layanan = l.id) AS transaction_count
            FROM layanan l
            LEFT JOIN sub_kategori sk ON sk.id = l.id_sub_kategori
            LEFT JOIN tarif_layanan tl ON tl.id_layanan = l.id
            LEFT JOIN aset a ON a.id_layanan = l.id
            WHERE l.id_merchant = ?";

        // Add sorting based on the sort parameter
        switch ($sort) {
            case 'highest_rating':
                $query .= " ORDER BY rating_avg DESC, rating_count DESC";
                break;
            case 'price_asc':
                $query .= " ORDER BY tl.harga ASC";
                break;
            case 'price_desc':
                $query .= " ORDER BY tl.harga DESC";
                break;
            default: // newest
                $query .= " ORDER BY l.created_at DESC";
        }

        $layanan = DB::select($query, [$id]);

        $html = view('merchant.partials.layanan-grid', compact('layanan'))->render();

        return response()->json(['html' => $html]);
    }
}
