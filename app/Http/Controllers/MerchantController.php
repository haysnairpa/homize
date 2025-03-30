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
