<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Booking;
use App\Http\Controllers\PembayaranController;

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
        $transactions = DB::select("SELECT 
                                    b.id, 
                                    sk.seri_sub_kategori, 
                                    sk.nama as nama_sub_kategori, 
                                    l.nama_layanan, 
                                    l.deskripsi_layanan, 
                                    m.nama_usaha, 
                                    m.profile_url, 
                                    m.alamat as alamat_merchant, 
                                    s.nama_status, 
                                    b.tanggal_booking, 
                                    b.updated_at, 
                                    p.amount, 
                                    bs.waktu_mulai, 
                                    bs.waktu_selesai, 
                                    b.alamat_pembeli, 
                                    b.catatan, 
                                    b.latitude, 
                                    b.longitude
                                FROM booking b
                                JOIN layanan l ON l.id = b.id_layanan
                                JOIN sub_kategori sk ON sk.id = l.id_sub_kategori
                                JOIN merchant m ON m.id = l.id_merchant
                                JOIN status s ON b.id_status = s.id
                                JOIN booking_schedule bs ON bs.id = b.id_booking_schedule
                                JOIN pembayaran p ON p.id_booking = b.id
                                WHERE b.id_user = ?
                                ORDER BY b.created_at DESC", [$userId]);

        return view('user.transactions', compact('transactions'));
    }

    public function filterTransactions(Request $request)
    {
        $userId = Auth::id();
        $status = $request->query('status', 'all');

        $query = "SELECT 
                    b.id, 
                    sk.seri_sub_kategori, 
                    sk.nama as nama_sub_kategori, 
                    l.nama_layanan, 
                    l.deskripsi_layanan, 
                    m.nama_usaha, 
                    m.profile_url, 
                    m.alamat as alamat_merchant, 
                    s.nama_status, 
                    b.tanggal_booking, 
                    b.updated_at, 
                    p.amount, 
                    bs.waktu_mulai, 
                    bs.waktu_selesai, 
                    b.alamat_pembeli, 
                    b.catatan, 
                    b.latitude, 
                    b.longitude
                FROM booking b
                JOIN layanan l ON l.id = b.id_layanan
                JOIN sub_kategori sk ON sk.id = l.id_sub_kategori
                JOIN merchant m ON m.id = l.id_merchant
                JOIN status s ON b.id_status = s.id
                JOIN booking_schedule bs ON bs.id = b.id_booking_schedule
                JOIN pembayaran p ON p.id_booking = b.id
                WHERE b.id_user = ?";

        $params = [$userId];

        if ($status !== 'all') {
            $query .= " AND b.id_status = ?";
            $params[] = $status;
        }

        $query .= " ORDER BY b.created_at DESC";

        $transactions = DB::select($query, $params);

        if ($request->ajax()) {
            $html = view('partials.transaction-list-dashboard', compact('transactions'))->render();
            return response()->json(['success' => true, 'html' => $html]);
        }

        return view('user.transactions', compact('transactions'));
    }

    public function filterTransactionsByDate(Request $request)
    {
        $userId = Auth::id();
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $query = "SELECT 
                    b.id, 
                    sk.seri_sub_kategori, 
                    sk.nama as nama_sub_kategori, 
                    l.nama_layanan, 
                    l.deskripsi_layanan, 
                    m.nama_usaha, 
                    m.profile_url, 
                    m.alamat as alamat_merchant, 
                    s.nama_status, 
                    b.tanggal_booking, 
                    b.updated_at, 
                    p.amount, 
                    bs.waktu_mulai, 
                    bs.waktu_selesai, 
                    b.alamat_pembeli, 
                    b.catatan, 
                    b.latitude, 
                    b.longitude
                FROM booking b
                JOIN layanan l ON l.id = b.id_layanan
                JOIN sub_kategori sk ON sk.id = l.id_sub_kategori
                JOIN merchant m ON m.id = l.id_merchant
                JOIN status s ON b.id_status = s.id
                JOIN booking_schedule bs ON bs.id = b.id_booking_schedule
                JOIN pembayaran p ON p.id_booking = b.id
                WHERE b.id_user = ? AND b.tanggal_booking BETWEEN ? AND ?
                ORDER BY b.created_at DESC";

        $transactions = DB::select($query, [$userId, $startDate, $endDate]);

        if ($request->ajax()) {
            $html = view('partials.transaction-list-dashboard', compact('transactions'))->render();
            return response()->json(['success' => true, 'html' => $html]);
        }

        return view('user.transactions', compact('transactions'));
    }

    public function transactionDetail($id)
    {
        $userId = Auth::id();
        $transaction = DB::selectOne("SELECT 
                                        b.id, 
                                        sk.seri_sub_kategori, 
                                        sk.nama as nama_sub_kategori, 
                                        l.nama_layanan, 
                                        l.deskripsi_layanan, 
                                        m.nama_usaha, 
                                        m.profile_url, 
                                        m.alamat as alamat_merchant, 
                                        s.nama_status, 
                                        b.tanggal_booking, 
                                        p.amount, 
                                        bs.waktu_mulai, 
                                        bs.waktu_selesai, 
                                        b.alamat_pembeli, 
                                        b.catatan, 
                                        b.latitude, 
                                        b.longitude
                                    FROM booking b
                                    JOIN layanan l ON l.id = b.id_layanan
                                    JOIN sub_kategori sk ON sk.id = l.id_sub_kategori
                                    JOIN merchant m ON m.id = l.id_merchant
                                    JOIN status s ON b.id_status = s.id
                                    JOIN booking_schedule bs ON bs.id = b.id_booking_schedule
                                    JOIN pembayaran p ON p.id_booking = b.id
                                    WHERE b.id = ? AND b.id_user = ?", [$id, $userId]);

        if (!$transaction) {
            return redirect()->route('transactions')->with('error', 'Transaksi tidak ditemukan');
        }

        return view('user.transaction-detail', compact('transaction'));
    }
}
