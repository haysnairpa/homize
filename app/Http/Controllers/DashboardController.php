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
        $bookings = DB::select("SELECT sk.seri_sub_kategori, l.nama_layanan, s.nama_status, b.tanggal_booking, p.amount, bs.waktu_mulai, bs.waktu_selesai, b.id
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

    public function index()
    {
        $user = Auth::user();
        $bookings = Booking::with(['status', 'pembayaran', 'pembayaran.status'])
            ->where('id_user', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Cek status pembayaran untuk semua booking dengan status Payment Pending
        foreach ($bookings as $booking) {
            if ($booking->pembayaran && $booking->pembayaran->status->nama_status == 'Payment Pending' && $booking->pembayaran->order_id) {
                // Cek status pembayaran
                app(PembayaranController::class)->checkPaymentStatus($booking->pembayaran);
            }
        }
        
        // Refresh data booking
        $bookings = Booking::with(['status', 'pembayaran', 'pembayaran.status'])
            ->where('id_user', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('dashboard', compact('bookings'));
    }
}
