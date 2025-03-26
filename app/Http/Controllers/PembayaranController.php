<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Pembayaran;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;

class PembayaranController extends Controller
{
    public function __construct()
    {
        // Set konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function show($id)
    {
        // Ambil data booking
        $booking = Booking::with(['user', 'merchant', 'layanan', 'status', 'pembayaran'])
            ->findOrFail($id);

        // Cek apakah booking milik user yang login
        if ($booking->id_user != Auth::id()) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Ambil data tarif layanan
        $tarifLayanan = DB::table('tarif_layanan')
            ->where('id_layanan', $booking->id_layanan)
            ->first();

        return view('pembayaran.show', compact('booking', 'tarifLayanan'));
    }

    public function process(Request $request, $id)
    {
        // Ambil data booking
        $booking = Booking::with(['user', 'merchant', 'layanan', 'pembayaran'])
            ->findOrFail($id);

        // Cek apakah booking milik user yang login
        if ($booking->id_user != Auth::id()) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Ambil data pembayaran
        $pembayaran = $booking->pembayaran;

        // Cek apakah pembayaran sudah selesai
        if ($pembayaran->status->nama_status == 'Payment Completed') {
            return redirect()->route('dashboard')->with('info', 'Pembayaran sudah selesai');
        }

        // Ambil metode pembayaran dari form
        $paymentMethod = $request->input('payment_method', 'bank_transfer');

        // Update metode pembayaran
        $pembayaran->update([
            'method' => $paymentMethod,
        ]);

        // Buat transaksi di Midtrans
        $transaction_details = [
            'order_id' => 'HOMIZE-' . $booking->id . '-' . time(),
            'gross_amount' => $pembayaran->amount,
        ];

        $customer_details = [
            'first_name' => $booking->user->name,
            'email' => $booking->user->email,
        ];

        $item_details = [
            [
                'id' => $booking->layanan->id,
                'price' => $pembayaran->amount,
                'quantity' => 1,
                'name' => $booking->layanan->nama_layanan,
            ]
        ];

        // Konfigurasi payment berdasarkan metode yang dipilih
        $enabled_payments = [];
        $bank_name = '';
        $va_number = '';

        switch ($paymentMethod) {
            case 'bank_transfer':
                $enabled_payments = ['bca_va', 'bni_va', 'bri_va', 'permata_va'];
                $bank_name = 'BCA/BNI/BRI/Permata';
                break;
            case 'e_wallet':
                $enabled_payments = ['gopay', 'shopeepay'];
                break;
            case 'credit_card':
                $enabled_payments = ['credit_card'];
                break;
            case 'qris':
                $enabled_payments = ['qris'];
                break;
            default:
                $enabled_payments = ['bca_va', 'bni_va', 'bri_va', 'permata_va'];
                $bank_name = 'BCA/BNI/BRI/Permata';
        }

        $transaction = [
            'transaction_details' => $transaction_details,
            'customer_details' => $customer_details,
            'item_details' => $item_details,
            'enabled_payments' => $enabled_payments
        ];

        try {
            // Dapatkan Snap Token
            $snapToken = Snap::getSnapToken($transaction);

            // Update order_id dan snap_token di tabel pembayaran
            $pembayaran->update([
                'order_id' => $transaction_details['order_id'],
                'snap_token' => $snapToken,
            ]);

            // Jika metode pembayaran adalah bank transfer, kita bisa langsung menampilkan halaman instruksi
            if ($paymentMethod == 'bank_transfer') {
                // Untuk demo, kita gunakan nomor VA statis
                $va_number = '80777081386348589';
                return view('pembayaran.process', compact('booking', 'pembayaran', 'snapToken', 'bank_name', 'va_number'));
            } else {
                // Untuk metode lain, kita gunakan Snap popup
                return view('pembayaran.process_popup', compact('booking', 'pembayaran', 'snapToken'));
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed == $request->signature_key) {
            // Extract order ID
            $order_id = $request->order_id;
            
            // Find payment by order_id
            $pembayaran = Pembayaran::where('order_id', $order_id)->first();
            
            if (!$pembayaran) {
                return response()->json(['status' => 'error', 'message' => 'Payment not found']);
            }

            // Update payment status based on transaction status
            if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                // Payment success
                $statusCompleted = Status::where('nama_status', 'Payment Completed')->first();
                $pembayaran->update([
                    'id_status' => $statusCompleted->id,
                    'method' => $request->payment_type,
                    'payment_date' => now(),
                ]);

                // Update booking status to Confirmed
                $statusConfirmed = Status::where('nama_status', 'Confirmed')->first();
                $pembayaran->booking->update([
                    'id_status' => $statusConfirmed->id,
                ]);
            } elseif ($request->transaction_status == 'deny' || $request->transaction_status == 'cancel' || $request->transaction_status == 'expire') {
                // Payment failed
                $statusFailed = Status::where('nama_status', 'Payment Failed')->first();
                $pembayaran->update([
                    'id_status' => $statusFailed->id,
                    'method' => $request->payment_type,
                    'payment_date' => now(),
                ]);
            }

            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Invalid signature']);
        }
    }

    public function getToken($id)
    {
        // Ambil data booking
        $booking = Booking::with(['user', 'merchant', 'layanan', 'pembayaran'])
            ->findOrFail($id);

        // Cek apakah booking milik user yang login
        if ($booking->id_user != Auth::id()) {
            return response()->json(['error' => 'Anda tidak memiliki akses ke halaman ini']);
        }

        // Ambil data pembayaran
        $pembayaran = $booking->pembayaran;

        // Cek apakah pembayaran sudah selesai
        if ($pembayaran->status->nama_status == 'Payment Completed') {
            return response()->json(['error' => 'Pembayaran sudah selesai']);
        }

        // Buat transaksi di Midtrans
        $transaction_details = [
            'order_id' => 'HOMIZE-' . $booking->id . '-' . time(),
            'gross_amount' => $pembayaran->amount,
        ];

        $customer_details = [
            'first_name' => $booking->user->name,
            'email' => $booking->user->email,
        ];

        $item_details = [
            [
                'id' => $booking->layanan->id,
                'price' => $pembayaran->amount,
                'quantity' => 1,
                'name' => $booking->layanan->nama_layanan,
            ]
        ];

        $transaction = [
            'transaction_details' => $transaction_details,
            'customer_details' => $customer_details,
            'item_details' => $item_details,
        ];

        try {
            // Dapatkan Snap Token
            $snapToken = Snap::getSnapToken($transaction);

            // Update order_id di tabel pembayaran
            $pembayaran->update([
                'order_id' => $transaction_details['order_id'],
                'snap_token' => $snapToken,
            ]);

            return response()->json(['token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    // Tambahkan method untuk halaman process_popup
    public function processPopup($id)
    {
        // Ambil data booking
        $booking = Booking::with(['user', 'merchant', 'layanan', 'pembayaran'])
            ->findOrFail($id);

        // Cek apakah booking milik user yang login
        if ($booking->id_user != Auth::id()) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Ambil data pembayaran
        $pembayaran = $booking->pembayaran;

        return view('pembayaran.process_popup', compact('booking', 'pembayaran'));
    }
}