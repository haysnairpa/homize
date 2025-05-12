<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Pembayaran;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransCallbackController extends Controller
{
    public function handle(Request $request)
    {
        // Log semua request untuk debugging
        Log::info('Midtrans callback received', [
            'method' => $request->method(),
            'all_data' => $request->all(),
            'headers' => $request->header(),
            'ip' => $request->ip()
        ]);
        
        if ($request->isMethod('get')) {
            return response('OK', 200);
        }
        
        try {
            $serverKey = config('midtrans.server_key');
            $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

            if ($hashed == $request->signature_key) {
                // Proses callback
                $order_id = $request->order_id;
                $pembayaran = Pembayaran::where('order_id', $order_id)->first();
                
                if (!$pembayaran) {
                    Log::error('Payment not found for order_id: ' . $order_id);
                    return response()->json(['status' => 'error', 'message' => 'Payment not found']);
                }

                // Update status
                if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                    $this->updatePaymentSuccess($pembayaran, $request->payment_type);
                } elseif ($request->transaction_status == 'deny' || $request->transaction_status == 'cancel' || $request->transaction_status == 'expire') {
                    // Cek apakah ini kegagalan 3DS
                    if ($request->status_code == '202' || 
                        (isset($request->fraud_status) && $request->fraud_status == 'challenge') ||
                        (isset($request->status_message) && strpos(strtolower($request->status_message), '3ds') !== false)) {
                        
                        // Ini adalah kegagalan 3DS, jangan langsung ubah ke failed
                        $this->updatePayment3DSFailed($pembayaran, $request->payment_type);
                    } else {
                        // Kegagalan normal, update ke failed
                        $this->updatePaymentFailed($pembayaran, $request->payment_type);
                    }
                }
                
                return response()->json(['status' => 'success']);
            }
            
            return response()->json(['status' => 'error', 'message' => 'Invalid signature']);
        } catch (\Exception $e) {
            Log::error('Error in callback: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
    
    private function updatePaymentSuccess($pembayaran, $paymentType)
    {
        $pembayaran->update([
            'status_pembayaran' => 'Selesai',
            'method' => $paymentType,
            'payment_date' => now(),
            'otp_attempts' => 0, // Reset percobaan OTP
        ]);
        $pembayaran->booking->update([
            'status_proses' => 'Pending',
        ]);
        Log::info('Payment completed for order_id: ' . $pembayaran->order_id);
    }
    
    private function updatePayment3DSFailed($pembayaran, $paymentType)
    {
        // Tambah jumlah percobaan OTP
        $attempts = $pembayaran->otp_attempts + 1;
        $maxAttempts = config('midtrans.max_otp_attempts', 3);
        
        if ($attempts >= $maxAttempts) {
            // Jika sudah melebihi batas percobaan, anggap gagal
            $this->updatePaymentFailed($pembayaran, $paymentType);
            Log::info('Payment failed after ' . $attempts . ' OTP attempts for order_id: ' . $pembayaran->order_id);
        } else {
            // Masih ada kesempatan, tetap di status pending
            $pembayaran->update([
                'status_pembayaran' => 'Pending',
                'method' => $paymentType,
                'otp_attempts' => $attempts,
            ]);
            Log::info('3DS verification failed, attempt ' . $attempts . ' of ' . $maxAttempts . ' for order_id: ' . $pembayaran->order_id);
        }
    }
    
    private function updatePaymentFailed($pembayaran, $paymentType)
    {
        $pembayaran->update([
            'status_pembayaran' => 'Gagal',
            'method' => $paymentType,
            'payment_date' => now(),
            'otp_attempts' => 0, // Reset percobaan OTP
        ]);
        $pembayaran->booking->update([
            'status_proses' => 'Dibatalkan',
        ]);
        Log::info('Payment failed for order_id: ' . $pembayaran->order_id);
    }
    
    public function test()
    {
        Log::info('GET Test callback accessed');
        return response('OK', 200);
    }
} 