<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use App\Models\Booking;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Events\OrderCompleted;
use App\Mail\OrderAcceptedNotification;

class OrderController extends Controller
{
    // AJAX filterByDate handler removed (now using full page reload with GET params)

    public function orders(Request $request)
    {
        $merchant = Merchant::where('id_user', Auth::id())->firstOrFail();
        $status = $request->query('status', 'all');
        $start = $request->query('start_date');
        $end = $request->query('end_date');
        $search = $request->query('search', '');

        // Store filters in session for persistence
        session([
            'merchant_orders_status' => $status,
            'merchant_orders_start' => $start,
            'merchant_orders_end' => $end,
            'merchant_orders_search' => $search,
        ]);

        // Log the merchant order request for debugging
        Log::info('Merchant viewing orders', [
            'merchant_id' => $merchant->id,
            'merchant_name' => $merchant->nama_usaha,
            'filters' => [
                'status' => $status,
                'start_date' => $start,
                'end_date' => $end,
                'search' => $search
            ]
        ]);

        // Only show orders where payment has been completed (status_pembayaran = 'Selesai')
        // This ensures merchants only see orders that have been approved by admin
        $query = "SELECT b.id, u.nama AS nama_user, l.nama_layanan, b.status_proses, p.status_pembayaran, 
                    b.created_at AS booking_date, b.created_at, bs.updated_at, p.amount, 
                    b.alamat_pembeli, b.catatan, p.id as payment_id
                    FROM booking b
                    JOIN users u ON u.id = b.id_user
                    JOIN layanan l ON l.id = b.id_layanan
                    JOIN booking_schedule bs ON bs.id = b.id_booking_schedule
                    JOIN pembayaran p ON p.id_booking = b.id 
                    WHERE b.id_merchant = ? 
                    AND p.status_pembayaran = 'Selesai'";
        $params = [$merchant->id];
        if ($status && $status !== 'all') {
            $query .= " AND b.status_proses = ?";
            $params[] = $status;
        }
        if ($start && $end) {
            $query .= " AND DATE(bs.waktu_mulai) BETWEEN ? AND ?";
            $params[] = $start;
            $params[] = $end;
        }
        if ($search) {
            $query .= " AND (u.nama LIKE ? OR l.nama_layanan LIKE ?)";
            $params[] = '%' . $search . '%';
            $params[] = '%' . $search . '%';
        }
        $query .= " ORDER BY b.created_at DESC";
        $orders = DB::select($query, $params);
        $statuses = collect([
            ['value' => 'Pending', 'label' => 'Pending'],
            ['value' => 'Dikonfirmasi', 'label' => 'Dikonfirmasi'],
            ['value' => 'Sedang diproses', 'label' => 'Sedang diproses'],
            ['value' => 'Selesai', 'label' => 'Selesai'],
            ['value' => 'Dibatalkan', 'label' => 'Dibatalkan'],
        ]);
        return view('merchant.orders', compact('merchant', 'orders', 'statuses', 'status', 'start', 'end', 'search'));
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
        
        // Load all necessary relations for email notifications
        $booking->load(['user', 'merchant', 'merchant.user', 'layanan', 'pembayaran', 'booking_schedule']);
        
        // Send email notification based on the new status
        if ($requestedStatus == 'Dikonfirmasi') {
            try {
                // Send email to user that their order has been accepted
                if ($booking->user && $booking->user->email) {
                    Mail::to($booking->user->email)
                        ->send(new OrderAcceptedNotification($booking));
                    
                    Log::info('Email notifikasi pesanan dikonfirmasi berhasil dikirim', [
                        'booking_id' => $booking->id,
                        'user_email' => $booking->user->email
                    ]);
                } else {
                    Log::warning('Tidak dapat mengirim email notifikasi: data user tidak lengkap', [
                        'booking_id' => $booking->id
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Gagal mengirim email notifikasi pesanan dikonfirmasi', [
                    'booking_id' => $booking->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        } elseif ($requestedStatus == 'Selesai') {
            // Trigger the OrderCompleted event which will send the completion email with rating button
            try {
                event(new OrderCompleted($booking));
                Log::info('Event OrderCompleted berhasil dipicu', [
                    'booking_id' => $booking->id
                ]);
            } catch (\Exception $e) {
                Log::error('Gagal memicu event OrderCompleted', [
                    'booking_id' => $booking->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
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
            'status_proses' => $order->status_proses,
            'status_pembayaran' => $order->status_pembayaran
        ];
        return response()->json([
            'success' => true,
            'data' => $orderDetail
        ]);
    }
}
