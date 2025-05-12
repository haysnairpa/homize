<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use App\Models\Booking;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Events\OrderCompleted;

class OrderController extends Controller
{
    public function orders()
    {
        $merchant = Merchant::where('id_user', Auth::id())->firstOrFail();
        $orders = DB::select("SELECT b.id, u.nama as nama_user, l.nama_layanan, b.status_proses, p.status_pembayaran, b.tanggal_booking, p.amount, bs.waktu_mulai, bs.waktu_selesai, b.alamat_pembeli, b.catatan FROM booking b JOIN users u ON u.id = b.id_user JOIN layanan l ON l.id = b.id_layanan JOIN booking_schedule bs ON bs.id = b.id_booking_schedule JOIN pembayaran p ON p.id_booking = b.id WHERE b.id_merchant = ? ORDER BY b.created_at DESC", [$merchant->id]);
        $statuses = collect([
            ['value' => 'Pending', 'label' => 'Pending'],
            ['value' => 'Dikonfirmasi', 'label' => 'Dikonfirmasi'],
            ['value' => 'Sedang diproses', 'label' => 'Sedang diproses'],
            ['value' => 'Selesai', 'label' => 'Selesai'],
            ['value' => 'Dibatalkan', 'label' => 'Dibatalkan'],
        ]);
        return view('merchant.orders', compact('merchant', 'orders', 'statuses'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status_proses' => 'required|in:Pending,Dikonfirmasi,Sedang diproses,Selesai,Dibatalkan',
        ]);
        $booking = Booking::with(['pembayaran'])->findOrFail($id);
        $merchant = Merchant::where('id_user', Auth::id())->firstOrFail();
        if ($booking->id_merchant != $merchant->id) {
            return redirect()->route('merchant.orders')->with('error', 'Anda tidak memiliki akses untuk mengubah status pesanan ini');
        }
        $currentStatus = $booking->status_proses;
        $requestedStatus = $validated['status_proses'];
        $validTransition = false;
        switch ($currentStatus) {
            case 'Pending':
                if ($requestedStatus == 'Dikonfirmasi' || $requestedStatus == 'Dibatalkan') {
                    $validTransition = true;
                }
                break;
            case 'Dikonfirmasi':
                if ($requestedStatus == 'Sedang diproses') {
                    $validTransition = true;
                }
                break;
            case 'Sedang diproses':
                if ($requestedStatus == 'Selesai') {
                    $validTransition = true;
                }
                break;
            default:
                $validTransition = false;
        }
        if (!$validTransition) {
            return redirect()->route('merchant.orders')->with('error', 'Perubahan status tidak valid. Mohon ikuti alur status yang benar.');
        }
        $booking->status_proses = $validated['status_proses'];
        $booking->save();
        if ($requestedStatus == 'Selesai') {
            $booking->load(['user', 'merchant', 'merchant.user', 'layanan', 'pembayaran', 'booking_schedule']);
            event(new OrderCompleted($booking));
        }
        return redirect()->route('merchant.orders')->with('success', 'Status pesanan berhasil diperbarui');
    }

    public function orderDetail($id)
    {
        $merchant = Merchant::where('id_user', Auth::id())->firstOrFail();
        $order = DB::selectOne("SELECT b.id, u.nama as nama_user, u.email, l.nama_layanan, l.deskripsi_layanan, b.status_proses, p.status_pembayaran, b.tanggal_booking, p.amount, bs.waktu_mulai, bs.waktu_selesai, b.alamat_pembeli, b.catatan, b.longitude, b.latitude, tl.harga, tl.durasi, tl.tipe_durasi FROM booking b JOIN users u ON u.id = b.id_user JOIN layanan l ON l.id = b.id_layanan JOIN booking_schedule bs ON bs.id = b.id_booking_schedule JOIN pembayaran p ON p.id_booking = b.id JOIN tarif_layanan tl ON tl.id_layanan = l.id WHERE b.id = ? AND b.id_merchant = ?", [$id, $merchant->id]);
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan'
            ], 404);
        }
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
            'status_proses' => $order->status_proses, 'status_pembayaran' => $order->status_pembayaran
        ];
        return response()->json([
            'success' => true,
            'data' => $orderDetail
        ]);
    }
}

