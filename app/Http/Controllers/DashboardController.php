<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function mainDashboard()
    {
        $userId = Auth::id();
        $bookings = DB::select("SELECT sk.seri_sub_kategori, l.nama_layanan, s.nama_status, b.updated_at, p.amount, bs.waktu_mulai, bs.waktu_selesai, b.id
                                FROM booking b
                                JOIN layanan l ON l.id = b.id_layanan
                                JOIN sub_kategori sk ON sk.id = l.id_sub_kategori
                                JOIN status s ON b.id_status = s.id
                                JOIN booking_schedule bs ON bs.id = b.id_booking_schedule
                                JOIN pembayaran p ON p.id_booking = b.id
                                WHERE b.id_user = ?", [$userId]);

        $totalPesanan = DB::select("SELECT COUNT(*) as total_pesanan FROM booking WHERE id_user = ?", [$userId]);
        $totalPesananAktif = DB::select("SELECT COUNT(*) as total_pesanan_aktif FROM booking WHERE id_user = ? AND id_status = 3", [$userId]);
        $totalPesananSelesai = DB::select("SELECT COUNT(*) as total_pesanan_selesai FROM booking WHERE id_user = ? AND id_status = 4", [$userId]);
        $totalPesananBatal = DB::select("SELECT COUNT(*) as total_pesanan_batal FROM booking WHERE id_user = ? AND id_status = 5", [$userId]);
        return view('dashboard', compact('bookings', 'totalPesanan', 'totalPesananAktif', 'totalPesananSelesai', 'totalPesananBatal'));
    }

    public function transactions()
    {
        $userId = Auth::id();
        $bookings = DB::select("SELECT m.profile_url, sk.nama AS nama_sub_kategori , l.nama_layanan, p.amount, b.updated_at, s.nama_status, s.id as status_id
                                FROM booking b
                                JOIN layanan l ON b.id_layanan = l.id
                                LEFT JOIN layanan_merchant lm ON lm.id_layanan = l.id
                                LEFT JOIN merchant m ON lm.id_merchant = m.id
                                JOIN sub_kategori sk ON l.id_sub_kategori = sk.id
                                LEFT JOIN pembayaran p ON p.id_booking = b.id
                                LEFT JOIN `status` s ON b.id_status = s.id
                                WHERE b.id_user = ?
                                ORDER BY s.nama_status ASC", [$userId]);
        return view('transactions', compact('bookings'));
    }

    public function filterTransactions(Request $request)
    {
        $userId = Auth::id();
        $status = $request->status;

        $query = "SELECT m.profile_url, sk.nama AS nama_sub_kategori, l.nama_layanan, p.amount, b.updated_at, s.nama_status, s.id as status_id
                 FROM booking b
                 JOIN layanan l ON b.id_layanan = l.id
                 LEFT JOIN layanan_merchant lm ON lm.id_layanan = l.id
                 LEFT JOIN merchant m ON lm.id_merchant = m.id
                 JOIN sub_kategori sk ON l.id_sub_kategori = sk.id
                 LEFT JOIN pembayaran p ON p.id_booking = b.id
                 LEFT JOIN `status` s ON b.id_status = s.id
                 WHERE b.id_user = ?";

        if ($status !== 'all') {
            $query .= " AND s.id = ?";
        }

        $query .= " ORDER BY s.nama_status ASC";

        $params = [$userId];
        if ($status !== 'all') {
            $params[] = $status;
        }

        $bookings = DB::select($query, $params);

        return response()->json([
            'success' => true,
            'html' => view('partials.transaction-list', compact('bookings'))->render()
        ]);
    }

    public function filterByDateRange(Request $request)
    {
        $userId = Auth::id();
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $query = "SELECT m.profile_url, sk.nama AS nama_sub_kategori, l.nama_layanan, p.amount, b.updated_at, s.nama_status, s.id as status_id
                 FROM booking b
                 JOIN layanan l ON b.id_layanan = l.id
                 LEFT JOIN layanan_merchant lm ON lm.id_layanan = l.id
                 LEFT JOIN merchant m ON lm.id_merchant = m.id
                 JOIN sub_kategori sk ON l.id_sub_kategori = sk.id
                 LEFT JOIN pembayaran p ON p.id_booking = b.id
                 LEFT JOIN `status` s ON b.id_status = s.id
                 WHERE b.id_user = ? AND DATE(b.updated_at) BETWEEN ? AND ?
                 ORDER BY b.updated_at DESC";

        $bookings = DB::select($query, [$userId, $startDate, $endDate]);

        return response()->json([
            'success' => true,
            'html' => view('partials.transaction-list', compact('bookings'))->render()
        ]);
    }
}
