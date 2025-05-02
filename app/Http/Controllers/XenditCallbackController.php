<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Pembayaran;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class XenditCallbackController extends Controller
{
    public function handle(Request $request)
    {
        // Log semua request untuk debugging
        Log::info('Xendit callback received', [
            'method' => $request->method(),
            'all_data' => $request->all(),
            'headers' => $request->header(),
            'ip' => $request->ip(),
            'url' => $request->fullUrl()
        ]);
        
        // Respond to GET requests (for testing callback URL)
        if ($request->isMethod('get')) {
            return response('Xendit Callback URL configured', 200);
        }
        
        // Bypass CSRF protection untuk webhook
        // Ini diperlukan karena webhook dari Xendit tidak menyertakan CSRF token
        $request->headers->set('X-CSRF-TOKEN', csrf_token());
        
        try {
            // Verify callback token - make this optional during testing
            $callbackToken = config('xendit.callback_token');
            $xenditCallbackToken = $request->header('X-CALLBACK-TOKEN');
            
            if ($callbackToken && $xenditCallbackToken !== $callbackToken) {
                Log::warning('Callback token mismatch but continuing for testing: ' . $xenditCallbackToken);
                // During development, we'll continue processing even with token mismatch
                // In production, you would uncomment the following line:
                // return response()->json(['status' => 'error', 'message' => 'Invalid callback token'], 401);
            }

            // Process callback - support both webhook and redirect callback formats
            $invoiceId = $request->id ?? $request->input('id');
            $externalId = $request->external_id ?? $request->input('external_id');
            $status = $request->status ?? $request->input('status');
            $paymentMethod = $request->payment_method ?? $request->input('payment_method');
            
            Log::info('Processing Xendit callback', [
                'invoice_id' => $invoiceId,
                'external_id' => $externalId,
                'status' => $status
            ]);
            
            // Try to find payment by invoice ID first, then by external ID
            $pembayaran = null;
            
            if ($invoiceId) {
                $pembayaran = Pembayaran::where('order_id', $invoiceId)->first();
                Log::info('Searching payment by invoice_id', ['invoice_id' => $invoiceId, 'found' => (bool)$pembayaran]);
            }
            
            if (!$pembayaran && $externalId) {
                $pembayaran = Pembayaran::where('order_id', 'like', '%' . $externalId . '%')->first();
                Log::info('Searching payment by external_id', ['external_id' => $externalId, 'found' => (bool)$pembayaran]);
            }
            
            if (!$pembayaran) {
                Log::error('Payment not found for callback', [
                    'invoice_id' => $invoiceId,
                    'external_id' => $externalId
                ]);
                return response()->json(['status' => 'error', 'message' => 'Payment not found']);
            }

            // Log current status before update
            Log::info('Current payment status before update', [
                'payment_id' => $pembayaran->id,
                'status' => $pembayaran->status->nama_status,
                'booking_status' => $pembayaran->booking->status->nama_status
            ]);

            // Update status based on the payment status
            if ($status == 'PAID' || $status == 'COMPLETED') {
                $this->updatePaymentSuccess($pembayaran, $paymentMethod);
                Log::info('Payment marked as completed');
            } elseif (in_array($status, ['EXPIRED', 'FAILED'])) {
                // Check if this is a 3DS failure
                $failureReason = $request->failure_reason ?? $request->input('failure_reason');
                if ($failureReason && strpos(strtolower($failureReason), '3ds') !== false) {
                    $this->updatePayment3DSFailed($pembayaran, $paymentMethod);
                    Log::info('Payment marked as 3DS failed');
                } else {
                    // Normal failure, update to failed
                    $this->updatePaymentFailed($pembayaran, $paymentMethod);
                    Log::info('Payment marked as failed');
                }
            }
            
            return response()->json(['status' => 'success']);
            
        } catch (\Exception $e) {
            Log::error('Error in callback: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
    
    private function updatePaymentSuccess($pembayaran, $paymentMethod)
    {
        // Cari status 'Payment Completed'
        $statusCompleted = Status::where('nama_status', 'Payment Completed')->first();
        if (!$statusCompleted) {
            Log::error('Status "Payment Completed" not found in database');
            return;
        }
        
        // Update pembayaran ke status 'Payment Completed'
        $pembayaran->update([
            'id_status' => $statusCompleted->id,
            'method' => $paymentMethod ?: $pembayaran->method, // Gunakan metode yang ada jika tidak ada yang baru
            'payment_date' => now(),
            'otp_attempts' => 0, // Reset percobaan OTP
        ]);
        
        // Refresh model untuk mendapatkan data terbaru
        $pembayaran->refresh();
        
        // Log status pembayaran setelah update
        Log::info('Payment status updated to "Payment Completed"', [
            'payment_id' => $pembayaran->id,
            'order_id' => $pembayaran->order_id,
            'new_status_id' => $pembayaran->id_status,
            'new_status_name' => $pembayaran->status->nama_status
        ]);
        
        // Cari status 'Pending'
        $statusPending = Status::where('nama_status', 'Pending')->first();
        if (!$statusPending) {
            Log::error('Status "Pending" not found in database');
            return;
        }
        
        // Update booking ke status 'Pending'
        $booking = $pembayaran->booking;
        $booking->update([
            'id_status' => $statusPending->id,
        ]);
        
        // Refresh model untuk mendapatkan data terbaru
        $booking->refresh();
        
        // Log status booking setelah update
        Log::info('Booking status updated to "Pending"', [
            'booking_id' => $booking->id,
            'new_status_id' => $booking->id_status,
            'new_status_name' => $booking->status->nama_status
        ]);
        
        // Trigger event atau notifikasi jika diperlukan
        // event(new PaymentCompletedEvent($pembayaran));
        
        Log::info('Payment completed for order_id: ' . $pembayaran->order_id);
    }
    
    private function updatePayment3DSFailed($pembayaran, $paymentMethod)
    {
        // Tambah jumlah percobaan OTP
        $attempts = $pembayaran->otp_attempts + 1;
        $maxAttempts = config('xendit.max_otp_attempts', 3);
        
        if ($attempts >= $maxAttempts) {
            // Jika sudah melebihi batas percobaan, anggap gagal
            $this->updatePaymentFailed($pembayaran, $paymentMethod);
            Log::info('Payment failed after ' . $attempts . ' OTP attempts for order_id: ' . $pembayaran->order_id);
        } else {
            // Masih ada kesempatan, tetap di status pending
            $statusPending = Status::where('nama_status', 'Payment Pending')->first();
            $pembayaran->update([
                'method' => $paymentMethod,
                'otp_attempts' => $attempts,
            ]);
            
            Log::info('3DS verification failed, attempt ' . $attempts . ' of ' . $maxAttempts . ' for order_id: ' . $pembayaran->order_id);
        }
    }
    
    private function updatePaymentFailed($pembayaran, $paymentMethod)
    {
        $statusFailed = Status::where('nama_status', 'Payment Failed')->first();
        $pembayaran->update([
            'id_status' => $statusFailed->id,
            'method' => $paymentMethod,
            'payment_date' => now(),
            'otp_attempts' => 0, // Reset percobaan OTP
        ]);
        
        $pembayaran->booking->update([
            'id_status' => $statusFailed->id,
        ]);
        
        Log::info('Payment failed for order_id: ' . $pembayaran->order_id);
    }
    
    public function test()
    {
        Log::info('GET Test callback accessed');
        return response('OK', 200);
    }
    
    /**
     * Handle Xendit webhook without web middleware
     * This method is specifically for API routes to avoid session and CSRF issues
     */
    public function handleWebhook(Request $request)
    {
        // Log semua request untuk debugging
        Log::info('Xendit API webhook received', [
            'method' => $request->method(),
            'all_data' => $request->all(),
            'headers' => $request->header(),
            'ip' => $request->ip(),
            'url' => $request->fullUrl()
        ]);
        
        try {
            // Process callback - support both webhook and redirect callback formats
            $invoiceId = $request->id ?? $request->input('id');
            $externalId = $request->external_id ?? $request->input('external_id');
            $status = $request->status ?? $request->input('status');
            $paymentMethod = $request->payment_method ?? $request->input('payment_method');
            
            Log::info('Processing Xendit API webhook', [
                'invoice_id' => $invoiceId,
                'external_id' => $externalId,
                'status' => $status
            ]);
            
            // Try to find payment by invoice ID first, then by external ID
            $pembayaran = null;
            
            if ($invoiceId) {
                $pembayaran = Pembayaran::where('order_id', $invoiceId)->first();
                Log::info('Searching payment by invoice_id', ['invoice_id' => $invoiceId, 'found' => (bool)$pembayaran]);
            }
            
            if (!$pembayaran && $externalId) {
                $pembayaran = Pembayaran::where('order_id', 'like', '%' . $externalId . '%')->first();
                Log::info('Searching payment by external_id', ['external_id' => $externalId, 'found' => (bool)$pembayaran]);
            }
            
            if (!$pembayaran) {
                Log::error('Payment not found for webhook', [
                    'invoice_id' => $invoiceId,
                    'external_id' => $externalId
                ]);
                return response()->json(['status' => 'error', 'message' => 'Payment not found'], 404);
            }

            // Log current status before update
            Log::info('Current payment status before update', [
                'payment_id' => $pembayaran->id,
                'status' => $pembayaran->status->nama_status,
                'booking_status' => $pembayaran->booking->status->nama_status
            ]);

            // Update status based on the payment status
            if ($status == 'PAID' || $status == 'COMPLETED') {
                $this->updatePaymentSuccess($pembayaran, $paymentMethod);
                Log::info('Payment marked as completed via API webhook');
            } elseif (in_array($status, ['EXPIRED', 'FAILED'])) {
                // Check if this is a 3DS failure
                $failureReason = $request->failure_reason ?? $request->input('failure_reason');
                if ($failureReason && strpos(strtolower($failureReason), '3ds') !== false) {
                    $this->updatePayment3DSFailed($pembayaran, $paymentMethod);
                    Log::info('Payment marked as 3DS failed via API webhook');
                } else {
                    // Normal failure, update to failed
                    $this->updatePaymentFailed($pembayaran, $paymentMethod);
                    Log::info('Payment marked as failed via API webhook');
                }
            }
            
            return response()->json(['status' => 'success']);
            
        } catch (\Exception $e) {
            Log::error('Error in API webhook: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
