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
        $transactions = DB::select("SELECT b.id, l.nama_layanan, m.nama_usaha, s.nama_status, b.tanggal_booking, p.amount
                                FROM booking b
                                JOIN layanan l ON l.id = b.id_layanan
                                JOIN merchant m ON m.id = b.id_merchant
                                JOIN status s ON b.id_status = s.id
                                JOIN pembayaran p ON p.id_booking = b.id
                                WHERE b.id_user = ?
                                ORDER BY b.created_at DESC", [$userId]);

        return view('user.transactions', compact('transactions'));
    }

    public function transactionDetail($id)
    {
        $userId = Auth::id();
        $transaction = DB::selectOne("SELECT b.id, l.nama_layanan, l.deskripsi_layanan, m.nama_usaha, m.alamat as alamat_merchant, 
                                    s.nama_status, b.tanggal_booking, p.amount, bs.waktu_mulai, bs.waktu_selesai, 
                                    b.alamat_pembeli, b.catatan, b.latitude, b.longitude
                                FROM booking b
                                JOIN layanan l ON l.id = b.id_layanan
                                JOIN merchant m ON m.id = b.id_merchant
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
