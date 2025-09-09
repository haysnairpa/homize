<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function transactions()
    {
        $userId = Auth::id();
        $transactions = DB::select("SELECT b.id, l.nama_layanan, m.nama_usaha, b.status_proses, bs.waktu_mulai, p.amount, p.status_pembayaran
                                FROM booking b
                                JOIN layanan l ON l.id = b.id_layanan
                                JOIN merchant m ON m.id = b.id_merchant
                                JOIN pembayaran p ON p.id_booking = b.id
                                WHERE b.id_user = ?
                                ORDER BY b.created_at DESC", [$userId]);

        return view('user.transactions', compact('transactions'));
    }

    public function transactionDetail($id)
    {
        $userId = Auth::id();
        $transaction = DB::selectOne("SELECT b.id, l.nama_layanan, l.deskripsi_layanan, m.nama_usaha, m.alamat as alamat_merchant, 
                                    b.status_proses, bs.waktu_mulai, p.amount, p.status_pembayaran, bs.waktu_mulai, bs.waktu_selesai, 
                                    b.alamat_pembeli, b.catatan, b.latitude, b.longitude
                                FROM booking b
                                JOIN layanan l ON l.id = b.id_layanan
                                JOIN merchant m ON m.id = b.id_merchant
                                JOIN booking_schedule bs ON bs.id = b.id_booking_schedule
                                JOIN pembayaran p ON p.id_booking = b.id
                                WHERE b.id = ? AND b.id_user = ?", [$id, $userId]);

        if (!$transaction) {
            return redirect()->route('transactions')->with('error', 'Transaksi tidak ditemukan');
        }

        return view('user.transaction.detail', compact('transaction'));
    }
} 