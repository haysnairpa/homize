<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use App\Models\SubKategori;
use App\Models\Booking;
use App\Models\Status;
use App\Models\Kategori;
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

        $kategori = Kategori::all();

        // Ambil data dari session jika ada
        $oldData = Session::get('merchant_registration', []);

        return view('merchant.register.step1', compact('kategori', 'oldData'));
    }

    public function storeStep1(Request $request)
    {
        $validated = $request->validate([
            'nama_usaha' => 'required|string|max:255',
            'id_kategori' => 'required|exists:kategori,id',
            'profile_url' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'nama_usaha.required' => 'Nama usaha wajib diisi',
            'id_kategori.required' => 'Kategori usaha wajib dipilih',
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
            'id_kategori' => $validated['id_kategori'],
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
        $merchant->id_kategori = $merchantData['id_kategori'];
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

        return redirect()->route('merchant.verification-status')
            ->with('success', 'Pendaftaran merchant berhasil! Silakan tunggu verifikasi.');
    }

    public function backToStep1()
    {
        // Tidak perlu menghapus data dari session
        return redirect()->route('merchant.register.step1');
    }

    public function verificationStatus()
    {
        $merchant = Auth::user()->merchant;
        
        if (!$merchant) {
            return redirect()->route('merchant.register.step1');
        }

        if ($merchant->verification_status === 'approved') {
            return redirect()->route('merchant.dashboard');
        }

        return view('merchant.verification-status', ['merchant' => $merchant]);
    }

    public function retryVerification()
    {
        $merchant = Auth::user()->merchant;
        
        if (!$merchant || $merchant->verification_status === 'approved') {
            return redirect()->route('merchant.dashboard');
        }

        // Delete existing merchant record
        $merchant->delete();

        return redirect()->route('merchant.register.step1')
            ->with('info', 'Silakan daftar ulang sebagai merchant');
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
            'id_kategori' => 'required|exists:kategori,id',
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
        $merchant->id_kategori = $validated['id_kategori'];
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
        $subKategori = SubKategori::where('id_kategori', $merchant->id_kategori)->get();
        return view('merchant.services', compact('merchant', 'layanan', 'subKategori'));
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

        // Default periode: 7 hari terakhir
        $period = request('period', 'week');

        // Fungsi untuk mengambil data statistik
        function getStat($query, $params = [])
        {
            $result = DB::select($query, $params);
            return $result && isset($result[0]->total) ? $result[0]->total : 0;
        }

        // Tentukan rentang tanggal berdasarkan periode
        $startDate = now();
        $endDate = now();

        switch ($period) {
            case 'week':
                $startDate = now()->subDays(7);
                break;
            case 'month':
                $startDate = now()->startOfMonth();
                break;
            case 'month30':
                $startDate = now()->subDays(30);
                break;
                // Kasus custom akan ditangani di frontend
        }

        // Format tanggal untuk query
        $startDateStr = $startDate->format('Y-m-d');
        $endDateStr = $endDate->format('Y-m-d');

        // Statistik mingguan
        $weeklyStats = [
            'pendapatan' => getStat(
                "SELECT SUM(p.amount) as total FROM booking b JOIN pembayaran p ON p.id_booking = b.id JOIN status s ON p.id_status = s.id WHERE b.id_merchant = ? AND s.nama_status = 'Payment Completed' AND p.payment_date BETWEEN ? AND ?",
                [$merchant->id, $startDateStr, $endDateStr]
            ),
            'pesanan' => getStat(
                "SELECT COUNT(*) as total FROM booking b WHERE b.id_merchant = ? AND b.created_at BETWEEN ? AND ?",
                [$merchant->id, $startDateStr, $endDateStr]
            ),
            'views' => getStat(
                "SELECT COUNT(*) as total FROM layanan_views lv JOIN layanan l ON lv.id_layanan = l.id WHERE l.id_merchant = ? AND lv.created_at BETWEEN ? AND ?",
                [$merchant->id, $startDateStr, $endDateStr]
            ),
            'rating' => getStat(
                "SELECT COALESCE(AVG(r.rate), 0) as total FROM rating r JOIN layanan l ON r.id_layanan = l.id WHERE l.id_merchant = ? AND r.created_at BETWEEN ? AND ?",
                [$merchant->id, $startDateStr, $endDateStr]
            )
        ];

        $monthlyStats = [
            'pendapatan' => getStat(
                "SELECT SUM(p.amount) as total FROM booking b JOIN pembayaran p ON p.id_booking = b.id JOIN status s ON p.id_status = s.id WHERE b.id_merchant = ? AND s.nama_status = 'Payment Completed' AND MONTH(p.payment_date) = MONTH(CURRENT_DATE()) AND YEAR(p.payment_date) = YEAR(CURRENT_DATE())",
                [$merchant->id]
            ),
            'pesanan' => getStat(
                "SELECT COUNT(*) as total FROM booking b WHERE b.id_merchant = ? AND MONTH(b.created_at) = MONTH(CURRENT_DATE()) AND YEAR(b.created_at) = YEAR(CURRENT_DATE())",
                [$merchant->id]
            ),
            'views' => getStat(
                "SELECT COUNT(*) as total FROM layanan_views lv JOIN layanan l ON lv.id_layanan = l.id WHERE l.id_merchant = ? AND MONTH(lv.created_at) = MONTH(CURRENT_DATE()) AND YEAR(lv.created_at) = YEAR(CURRENT_DATE())",
                [$merchant->id]
            ),
            'rating' => getStat(
                "SELECT COALESCE(AVG(r.rate), 0) as total FROM rating r JOIN layanan l ON r.id_layanan = l.id WHERE l.id_merchant = ? AND MONTH(r.created_at) = MONTH(CURRENT_DATE()) AND YEAR(r.created_at) = YEAR(CURRENT_DATE())",
                [$merchant->id]
            )
        ];

        // Statistik periode sebelumnya untuk perbandingan tren
        $prevStartDate = clone $startDate;
        $prevEndDate = clone $startDate;
        $prevStartDate = $prevStartDate->subDays($startDate->diffInDays($endDate));
        $prevStartDateStr = $prevStartDate->format('Y-m-d');
        $prevEndDateStr = $prevEndDate->format('Y-m-d');

        // Tren pendapatan
        $prevPendapatan = getStat(
            "SELECT SUM(p.amount) as total FROM booking b JOIN pembayaran p ON p.id_booking = b.id JOIN status s ON p.id_status = s.id WHERE b.id_merchant = ? AND s.nama_status = 'Payment Completed' AND p.payment_date BETWEEN ? AND ?",
            [$merchant->id, $prevStartDateStr, $prevEndDateStr]
        );
        $pendapatanTrend = $prevPendapatan > 0 ? round((($weeklyStats['pendapatan'] - $prevPendapatan) / $prevPendapatan) * 100) : 0;

        // Tren pesanan
        $prevPesanan = getStat(
            "SELECT COUNT(*) as total FROM booking b WHERE b.id_merchant = ? AND b.created_at BETWEEN ? AND ?",
            [$merchant->id, $prevStartDateStr, $prevEndDateStr]
        );
        $pesananTrend = $prevPesanan > 0 ? round((($weeklyStats['pesanan'] - $prevPesanan) / $prevPesanan) * 100) : 0;

        // Tren views
        $prevViews = getStat(
            "SELECT COUNT(*) as total FROM layanan_views lv JOIN layanan l ON lv.id_layanan = l.id WHERE l.id_merchant = ? AND lv.created_at BETWEEN ? AND ?",
            [$merchant->id, $prevStartDateStr, $prevEndDateStr]
        );
        $viewsTrend = $prevViews > 0 ? round((($weeklyStats['views'] - $prevViews) / $prevViews) * 100) : 0;

        // Tren rating
        $prevRating = getStat(
            "SELECT COALESCE(AVG(r.rate), 0) as total FROM rating r JOIN layanan l ON r.id_layanan = l.id WHERE l.id_merchant = ? AND r.created_at BETWEEN ? AND ?",
            [$merchant->id, $prevStartDateStr, $prevEndDateStr]
        );
        $ratingTrend = $prevRating > 0 ? round(($weeklyStats['rating'] - $prevRating), 1) : 0;

        // Perbaiki query untuk mendapatkan data revenue
        $revenueData = DB::select(
            "SELECT DATE(p.payment_date) as date, SUM(p.amount) as total 
            FROM booking b 
            JOIN pembayaran p ON b.id = p.id_booking 
            WHERE b.id_merchant = ? 
            AND p.payment_date BETWEEN ? AND ? 
            GROUP BY DATE(p.payment_date) 
            ORDER BY date",
            [$merchant->id, $startDateStr, $endDateStr]
        );

        // Jika tidak ada data, buat data dummy untuk 7 hari terakhir
        if (empty($revenueData)) {
            $revenueData = [];
            $currentDate = clone $startDate;
            while ($currentDate <= $endDate) {
                $revenueData[] = (object)[
                    'date' => $currentDate->format('Y-m-d'),
                    'total' => rand(100000, 1000000) // Data dummy untuk visualisasi
                ];
                $currentDate->addDay();
            }
        }

        // Data untuk grafik pesanan
        $orderData = DB::select(
            "SELECT DATE(b.created_at) as date, COUNT(*) as total 
            FROM booking b 
            WHERE b.id_merchant = ? 
            AND b.created_at BETWEEN ? AND ? 
            GROUP BY DATE(b.created_at) 
            ORDER BY date",
            [$merchant->id, $startDateStr, $endDateStr]
        );

        // Jika tidak ada data pesanan, buat data dummy
        if (empty($orderData)) {
            $orderData = [];
            $currentDate = clone $startDate;
            while ($currentDate <= $endDate) {
                $orderData[] = (object)[
                    'date' => $currentDate->format('Y-m-d'),
                    'total' => rand(1, 10) // Data dummy untuk visualisasi
                ];
                $currentDate->addDay();
            }
        }

        // Data untuk grafik views
        $viewsData = DB::select(
            "SELECT DATE(lv.created_at) as date, COUNT(*) as total 
            FROM layanan_views lv 
            JOIN layanan l ON lv.id_layanan = l.id 
            WHERE l.id_merchant = ? 
            AND lv.created_at BETWEEN ? AND ? 
            GROUP BY DATE(lv.created_at) 
            ORDER BY date",
            [$merchant->id, $startDateStr, $endDateStr]
        );

        // Jika tidak ada data views, buat data dummy
        if (empty($viewsData)) {
            $viewsData = [];
            $currentDate = clone $startDate;
            while ($currentDate <= $endDate) {
                $viewsData[] = (object)[
                    'date' => $currentDate->format('Y-m-d'),
                    'total' => rand(5, 50) // Data dummy untuk visualisasi
                ];
                $currentDate->addDay();
            }
        }

        // Data untuk grafik rating
        $ratingData = DB::select(
            "SELECT DATE(r.created_at) as date, AVG(r.rate) as total 
            FROM rating r 
            JOIN layanan l ON r.id_layanan = l.id 
            WHERE l.id_merchant = ? 
            AND r.created_at BETWEEN ? AND ? 
            GROUP BY DATE(r.created_at) 
            ORDER BY date",
            [$merchant->id, $startDateStr, $endDateStr]
        );

        // Jika tidak ada data rating, buat data dummy
        if (empty($ratingData)) {
            $ratingData = [];
            $currentDate = clone $startDate;
            while ($currentDate <= $endDate) {
                $ratingData[] = (object)[
                    'date' => $currentDate->format('Y-m-d'),
                    'total' => round(rand(30, 50) / 10, 1) // Rating antara 3.0-5.0
                ];
                $currentDate->addDay();
            }
        }

        $servicePerformance = DB::select(
            "SELECT l.id, l.nama_layanan, COUNT(b.id) as total_orders, 
            SUM(p.amount) as total_revenue, AVG(r.rate) as avg_rating,
            COUNT(DISTINCT b.id_user) as unique_customers,
            (COUNT(CASE WHEN b.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) THEN 1 END) / 
             COUNT(b.id)) * 100 as growth_rate
            FROM layanan l
            LEFT JOIN booking b ON l.id = b.id_layanan
            LEFT JOIN pembayaran p ON b.id = p.id_booking
            LEFT JOIN rating r ON l.id = r.id_layanan
            WHERE l.id_merchant = ?
            GROUP BY l.id, l.nama_layanan
            ORDER BY total_revenue DESC",
            [$merchant->id]
        );

        // Data rating dan ulasan
        $avgRating = DB::selectOne(
            "SELECT COALESCE(AVG(r.rate), 0) as avg_rating 
            FROM rating r 
            JOIN layanan l ON r.id_layanan = l.id 
            WHERE l.id_merchant = ?",
            [$merchant->id]
        )->avg_rating;

        $ratingCount = DB::selectOne(
            "SELECT COUNT(*) as total 
            FROM rating r 
            JOIN layanan l ON r.id_layanan = l.id 
            WHERE l.id_merchant = ?",
            [$merchant->id]
        )->total;

        $reviewCount = DB::selectOne(
            "SELECT COUNT(*) as total 
            FROM rating r 
            JOIN layanan l ON r.id_layanan = l.id 
            WHERE l.id_merchant = ? AND r.message IS NOT NULL AND r.message != ''",
            [$merchant->id]
        )->total;

        // Distribusi rating
        $ratingStats = [];
        for ($i = 1; $i <= 5; $i++) {
            $ratingStats[$i] = DB::selectOne(
                "SELECT COUNT(*) as total 
                FROM rating r 
                JOIN layanan l ON r.id_layanan = l.id 
                WHERE l.id_merchant = ? AND r.rate = ?",
                [$merchant->id, $i]
            )->total;
        }

        // Ulasan terbaru
        $latestReviews = DB::select(
            "SELECT r.*, u.nama as nama_user 
            FROM rating r 
            JOIN layanan l ON r.id_layanan = l.id 
            JOIN users u ON r.id_user = u.id
            WHERE l.id_merchant = ? AND r.message IS NOT NULL AND r.message != '' 
            ORDER BY r.created_at DESC 
            LIMIT 5",
            [$merchant->id]
        );

        // Hitung tingkat penyelesaian dan pembatalan
        $totalOrders = getStat(
            "SELECT COUNT(*) as total FROM booking b WHERE b.id_merchant = ? AND b.created_at BETWEEN ? AND ?",
            [$merchant->id, $startDateStr, $endDateStr]
        );

        $completedOrders = getStat(
            "SELECT COUNT(*) as total FROM booking b JOIN status s ON b.id_status = s.id WHERE b.id_merchant = ? AND s.
            nama_status = 'Completed' AND b.created_at BETWEEN ? AND ?",
            [$merchant->id, $startDateStr, $endDateStr]
        );

        $cancelledOrders = getStat(
            "SELECT COUNT(*) as total FROM booking b JOIN status s ON b.id_status = s.id WHERE b.id_merchant = ? AND s.
            nama_status = 'Cancelled' AND b.created_at BETWEEN ? AND ?",
            [$merchant->id, $startDateStr, $endDateStr]
        );

        $completionRate = $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100) : 0;
        $cancellationRate = $totalOrders > 0 ? round(($cancelledOrders / $totalOrders) * 100) : 0;

        // Hitung pelanggan berulang dan baru
        $totalCustomers = getStat(
            "SELECT COUNT(DISTINCT b.id_user) as total FROM booking b WHERE b.id_merchant = ? AND b.created_at 
            BETWEEN ? AND ?",
            [$merchant->id, $startDateStr, $endDateStr]
        );

        $returningCustomers = getStat(
            "SELECT COUNT(DISTINCT b.id_user) as total 
            FROM booking b 
            WHERE b.id_merchant = ? 
            AND b.created_at BETWEEN ? AND ?
            AND b.id_user IN (
                SELECT id_user FROM booking 
                WHERE id_merchant = ? 
                AND created_at < ?
                GROUP BY id_user
            )",
            [$merchant->id, $startDateStr, $endDateStr, $merchant->id, $startDateStr]
        );

        $newCustomers = $totalCustomers - $returningCustomers;
        $returningCustomersPercentage = $totalCustomers > 0 ? round(($returningCustomers / $totalCustomers) * 100) : 0;
        $newCustomersPercentage = $totalCustomers > 0 ? round(($newCustomers / $totalCustomers) * 100) : 0;
        // Format tanggal untuk label chart
        $orderLabels = [];
        $orderDataValues = [];
        $viewsLabels = [];
        $viewsDataValues = [];
        $ratingLabels = [];
        $ratingDataValues = [];

        // Pastikan semua data memiliki format yang sama
        foreach ($revenueData as $data) {
            $data->date = \Carbon\Carbon::parse($data->date)->format('d M');
        }

        foreach ($orderData as $data) {
            $orderLabels[] = \Carbon\Carbon::parse($data->date)->format('d M');
            $orderDataValues[] = (int)$data->total;
        }

        foreach ($viewsData as $data) {
            $viewsLabels[] = \Carbon\Carbon::parse($data->date)->format('d M');
            $viewsDataValues[] = (int)$data->total;
        }

        foreach ($ratingData as $data) {
            $ratingLabels[] = \Carbon\Carbon::parse($data->date)->format('d M');
            $ratingDataValues[] = (float)$data->total;
        }

        return view('merchant.analytics', compact(
            'weeklyStats',
            'monthlyStats',
            'revenueData',
            'orderData',
            'viewsData',
            'ratingData',
            'servicePerformance',
            'pendapatanTrend',
            'pesananTrend',
            'viewsTrend',
            'ratingTrend',
            'avgRating',
            'ratingCount',
            'reviewCount',
            'ratingStats',
            'latestReviews',
            'completionRate',
            'cancellationRate',
            'returningCustomersPercentage',
            'newCustomersPercentage',
            'orderLabels',
            'orderDataValues',
            'viewsLabels',
            'viewsDataValues',
            'ratingLabels',
            'ratingDataValues'
        ));
    }

    public function getAnalyticsData(Request $request)
    {
        $merchant = Auth::user()->merchant;
        $period = $request->input('period', 'week');

        // Set tanggal berdasarkan periode
        $endDate = now();
        $startDate = clone $endDate;

        switch ($period) {
            case 'month':
                $startDate->startOfMonth();
                break;
            case 'month30':
                $startDate->subDays(30);
                break;
            case 'week':
            default:
                $startDate->subDays(7);
                break;
        }

        $startDateStr = $startDate->format('Y-m-d');
        $endDateStr = $endDate->format('Y-m-d');

        // Helper function untuk mendapatkan statistik
        $getStat = function ($query, $params) {
            $result = DB::select($query, $params);
            return $result[0]->total ?? 0;
        };

        // Data untuk grafik pendapatan
        $revenueData = DB::select(
            "SELECT DATE(p.payment_date) as date, SUM(p.amount) as total 
            FROM booking b 
            JOIN pembayaran p ON b.id = p.id_booking 
            JOIN status s ON p.id_status = s.id 
            WHERE b.id_merchant = ? 
            AND s.nama_status = 'Payment Completed' 
            AND p.payment_date BETWEEN ? AND ? 
            GROUP BY DATE(p.payment_date) 
            ORDER BY date",
            [$merchant->id, $startDateStr, $endDateStr]
        );

        // Jika tidak ada data, buat data dummy
        if (empty($revenueData)) {
            $revenueData = [];
            $currentDate = clone $startDate;
            while ($currentDate <= $endDate) {
                $revenueData[] = (object)[
                    'date' => $currentDate->format('Y-m-d'),
                    'total' => 0
                ];
                $currentDate->addDay();
            }
        }

        // Data untuk grafik pesanan
        $orderData = DB::select(
            "SELECT DATE(b.created_at) as date, COUNT(*) as total 
            FROM booking b 
            WHERE b.id_merchant = ? 
            AND b.created_at BETWEEN ? AND ? 
            GROUP BY DATE(b.created_at) 
            ORDER BY date",
            [$merchant->id, $startDateStr, $endDateStr]
        );

        // Data untuk grafik views
        $viewsData = DB::select(
            "SELECT DATE(lv.created_at) as date, COUNT(*) as total 
            FROM layanan_views lv 
            JOIN layanan l ON lv.id_layanan = l.id 
            WHERE l.id_merchant = ? 
            AND lv.created_at BETWEEN ? AND ? 
            GROUP BY DATE(lv.created_at) 
            ORDER BY date",
            [$merchant->id, $startDateStr, $endDateStr]
        );

        // Data untuk grafik rating
        $ratingData = DB::select(
            "SELECT DATE(r.created_at) as date, AVG(r.rate) as total 
            FROM rating r 
            JOIN layanan l ON r.id_layanan = l.id 
            WHERE l.id_merchant = ? 
            AND r.created_at BETWEEN ? AND ? 
            GROUP BY DATE(r.created_at) 
            ORDER BY date",
            [$merchant->id, $startDateStr, $endDateStr]
        );

        // Format data untuk response
        $formatData = function ($data) {
            $labels = [];
            $values = [];

            foreach ($data as $item) {
                $labels[] = \Carbon\Carbon::parse($item->date)->format('d M');
                $values[] = $item->total;
            }

            return [
                'labels' => $labels,
                'data' => $values
            ];
        };

        // Hitung statistik
        $pendapatan = $getStat(
            "SELECT SUM(p.amount) as total FROM booking b JOIN pembayaran p ON p.id_booking = b.id JOIN status s ON p.id_status = s.id WHERE b.id_merchant = ? AND s.nama_status = 'Payment Completed' AND p.payment_date BETWEEN ? AND ?",
            [$merchant->id, $startDateStr, $endDateStr]
        );

        $pesanan = $getStat(
            "SELECT COUNT(*) as total FROM booking b WHERE b.id_merchant = ? AND b.created_at BETWEEN ? AND ?",
            [$merchant->id, $startDateStr, $endDateStr]
        );

        $views = $getStat(
            "SELECT COUNT(*) as total FROM layanan_views lv JOIN layanan l ON lv.id_layanan = l.id WHERE l.id_merchant = ? AND lv.created_at BETWEEN ? AND ?",
            [$merchant->id, $startDateStr, $endDateStr]
        );

        $rating = $getStat(
            "SELECT COALESCE(AVG(r.rate), 0) as total FROM rating r JOIN layanan l ON r.id_layanan = l.id WHERE l.id_merchant = ? AND r.created_at BETWEEN ? AND ?",
            [$merchant->id, $startDateStr, $endDateStr]
        );

        return response()->json([
            'revenue' => $formatData($revenueData),
            'orders' => $formatData($orderData),
            'views' => $formatData($viewsData),
            'rating' => $formatData($ratingData),
            'stats' => [
                'pendapatan' => 'Rp ' . number_format($pendapatan, 0, ',', '.'),
                'pesanan' => $pesanan,
                'views' => $views,
                'rating' => number_format($rating, 1)
            ],
            'trends' => [
                'pendapatan' => 0, // Tambahkan logika tren jika diperlukan
                'pesanan' => 0,
                'views' => 0,
                'rating' => 0
            ]
        ]);
    }

    public function storeLayanan(Request $request)
    {
        try {
            DB::beginTransaction();

            // Get the merchant
            $merchant = Auth::user()->merchant;

            // Validate sub-kategori belongs to merchant's kategori
            $subKategori = SubKategori::findOrFail($request->id_sub_kategori);
            if ($subKategori->id_kategori !== $merchant->id_kategori) {
                throw new \Exception('Sub kategori tidak sesuai dengan kategori merchant Anda.');
            }

            // Create jam_operasional record
            $jamOperasional = JamOperasional::create([
                'jam_buka' => $request->jam_operasional['jam_buka'],
                'jam_tutup' => $request->jam_operasional['jam_tutup']
            ]);

            // Attach multiple hari to jam_operasional
            if (isset($request->jam_operasional['hari']) && is_array($request->jam_operasional['hari'])) {
                $jamOperasional->hari()->attach($request->jam_operasional['hari']);
            }

            // Create layanan record with id_merchant
            $layanan = Layanan::create([
                'nama_layanan' => $request->nama_layanan,
                'deskripsi_layanan' => $request->deskripsi_layanan,
                'id_sub_kategori' => $request->id_sub_kategori,
                'id_jam_operasional' => $jamOperasional->id,
                'id_merchant' => $merchant->id,
                'pengalaman' => $request->pengalaman ?? 0
            ]);

            Sertifikasi::create([
                'id_layanan' => $layanan->id,
                'nama_sertifikasi' => $request->nama_sertifikasi,
                'media_url' => $request->file_sertifikasi ? $request->file_sertifikasi->store('sertifikasi', 'public') : null,
            ]);

            // Handle revisi if enabled
            $revisiId = null;
            if ($request->has('enable_revisi') && $request->enable_revisi) {
                $revisi = Revisi::create([
                    'id_layanan' => $layanan->id,
                    'harga' => $request->revisi_harga,
                    'durasi' => $request->revisi_durasi,
                    'tipe_durasi' => $request->revisi_tipe_durasi
                ]);
                $revisiId = $revisi->id;
            }

            // Create tarif_layanan record with id_revisi
            TarifLayanan::create([
                'id_layanan' => $layanan->id,
                'harga' => $request->harga,
                'durasi' => $request->durasi,
                'tipe_durasi' => $request->tipe_durasi,
                'satuan' => $request->satuan,
                'id_revisi' => $revisiId
            ]);

            // Handle aset (images)
            if ($request->hasFile('aset_layanan')) {
                foreach ($request->file('aset_layanan') as $file) {
                    $path = $file->store('layanan-images', 'public');
                    Aset::create([
                        'id_layanan' => $layanan->id,
                        'media_url' => $path,
                        'deskripsi' => $request->nama_layanan  // Add default description
                    ]);
                }
            }

            // Handle sertifikasi
            if ($request->has('sertifikasi')) {
                foreach ($request->sertifikasi as $sertifikasi) {
                    if (!empty($sertifikasi['nama'])) {
                        $filePath = null;
                        if (isset($sertifikasi['file']) && $sertifikasi['file']) {
                            $filePath = $sertifikasi['file']->store('sertifikasi', 'public');
                        }
                        Sertifikasi::create([
                            'id_layanan' => $layanan->id,
                            'nama' => $sertifikasi['nama'],
                            'file_url' => $filePath
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'Layanan berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
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

    public function showAddLayananForm()
    {
        $merchant = Auth::user()->merchant;
        $subKategori = SubKategori::where('id_kategori', $merchant->id_kategori)->get();
        return view('merchant.components.add-layanan', compact('subKategori', 'merchant'));
    }
}
