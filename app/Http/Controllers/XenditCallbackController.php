<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Pembayaran;

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
                return response()->json(['status' => 'error', 'message' => 'Invalid callback token'], 401);
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
                'status' => $pembayaran->status_pembayaran,
                'booking_status' => $pembayaran->booking->status_proses
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
        $pembayaran->update([
            'status_pembayaran' => 'Berhasil', // Standarisasi status
            'method' => $paymentMethod ?: $pembayaran->method,
            'payment_date' => now(),
            'otp_attempts' => 0,
        ]);

        // Refresh model untuk mendapatkan data terbaru
        $pembayaran->refresh();

        // Update status proses booking jika masih Pending
        $booking = $pembayaran->booking;
        if ($booking && strtolower($booking->status_proses) === 'pending') {
            $booking->status_proses = 'Dikonfirmasi';
            $booking->save();
        }
        
        // Log status pembayaran setelah update
        Log::info('Payment status updated to "Selesai"', [
            'payment_id' => $pembayaran->id,
            'order_id' => $pembayaran->order_id,
            'new_status' => $pembayaran->status_pembayaran
        ]);
        
        // Update booking ke status 'Pending'
        $booking = $pembayaran->booking;
        
        // Refresh model untuk mendapatkan data terbaru
        $booking->refresh();
        
        // Log status booking setelah update
        Log::info('Booking status updated to "Pending"', [
            'booking_id' => $booking->id,
            'new_status' => $booking->status_proses
        ]);
        
        // Trigger event atau notifikasi jika diperlukan
        // event(new PaymentCompletedEvent($pembayaran));
        
        Log::info('Payment completed for order_id: ' . $pembayaran->order_id);
    }
    
    private function updatePayment3DSFailed($pembayaran, $paymentMethod)
    {
        $attempts = $pembayaran->otp_attempts + 1;
        $maxAttempts = config('xendit.max_otp_attempts', 3);
        
        if ($attempts >= $maxAttempts) {
            $this->updatePaymentFailed($pembayaran, $paymentMethod);
            Log::info('Payment failed after ' . $attempts . ' OTP attempts for order_id: ' . $pembayaran->order_id);
        } else {
            $pembayaran->update([
                'method' => $paymentMethod,
                'otp_attempts' => $attempts,
            ]);
            
            Log::info('3DS verification failed, attempt ' . $attempts . ' of ' . $maxAttempts . ' for order_id: ' . $pembayaran->order_id);
        }
    }
    
    private function updatePaymentFailed($pembayaran, $paymentMethod)
    {
        $pembayaran->update([
            'status_pembayaran' => 'Dibatalkan',
            'method' => $paymentMethod,
            'payment_date' => now(),
            'otp_attempts' => 0,
        ]);
        
        Log::info('Payment failed for order_id: ' . $pembayaran->order_id);
    }
    
    public function test()
    {
        Log::info('GET Test callback accessed');
        return response('OK', 200);
    }
    
    public function handleWebhook(Request $request)
{
    // Log all request data for debugging
    Log::info('Xendit API webhook received', [
        'method' => $request->method(),
        'all_data' => $request->all(),
        'headers' => $request->header(),
        'ip' => $request->ip(),
        'url' => $request->fullUrl()
    ]);

    // For development, skip token verification
    // In production, uncomment this section
    /*
    $token = $request->header('x-callback-token');
    $expectedToken = config('xendit.callback_token');
    if ($token !== $expectedToken) {
        Log::warning('Xendit callback token mismatch', ['token' => $token]);
        return response('Unauthorized', 401);
    }
    */
    
    try {
        // Extract data from the webhook payload
        $data = $request->all();
        $invoiceId = $data['id'] ?? null;
        $externalId = $data['external_id'] ?? null;
        $status = $data['status'] ?? null;
        $paymentMethod = $data['payment_method'] ?? null;
        
        Log::info('Processing Xendit API webhook', [
            'invoice_id' => $invoiceId,
            'external_id' => $externalId,
            'status' => $status
        ]);
        
        // Try to find payment by invoice ID first, then by external ID
        $pembayaran = null;
        
        // Dump all payment records for debugging
        $allPayments = Pembayaran::all();
        Log::info('All payments in database', [
            'count' => $allPayments->count(),
            'payment_ids' => $allPayments->pluck('id')->toArray(),
            'order_ids' => $allPayments->pluck('order_id')->toArray(),
            'booking_ids' => $allPayments->pluck('booking_id')->toArray(),
            'all_payments' => $allPayments->toArray() // Log all payment data for debugging
        ]);
        
        // Log the webhook data for debugging
        Log::info('Webhook data received', [
            'invoice_id' => $invoiceId,
            'external_id' => $externalId,
            'status' => $status,
            'payment_method' => $paymentMethod,
            'all_data' => $data
        ]);
        
        // 1. Try exact match on order_id
        if ($invoiceId) {
            $pembayaran = Pembayaran::where('order_id', $invoiceId)->first();
            Log::info('Searching payment by exact invoice_id', ['invoice_id' => $invoiceId, 'found' => (bool)$pembayaran]);
        }
        
        // 2. Try exact match on external_id
        if (!$pembayaran && $externalId) {
            $pembayaran = Pembayaran::where('order_id', $externalId)->first();
            Log::info('Searching payment by exact external_id', ['external_id' => $externalId, 'found' => (bool)$pembayaran]);
        }
        
        // 3. Try partial match on order_id
        if (!$pembayaran && $invoiceId) {
            $pembayaran = Pembayaran::where('order_id', 'like', '%' . $invoiceId . '%')->first();
            Log::info('Searching payment by partial invoice_id', ['invoice_id' => $invoiceId, 'found' => (bool)$pembayaran]);
        }
        
        // 4. Try partial match on external_id
        if (!$pembayaran && $externalId) {
            $pembayaran = Pembayaran::where('order_id', 'like', '%' . $externalId . '%')->first();
            Log::info('Searching payment by partial external_id', ['external_id' => $externalId, 'found' => (bool)$pembayaran]);
        }
        
        // 5. Try to extract booking ID from external_id (format: HOMIZE-{booking_id}-{timestamp})
        if (!$pembayaran && $externalId && preg_match('/HOMIZE-(\d+)-/', $externalId, $matches)) {
            $bookingId = $matches[1];
            $pembayaran = Pembayaran::where('booking_id', $bookingId)->first();
            Log::info('Searching payment by booking_id extracted from external_id', [
                'external_id' => $externalId, 
                'extracted_booking_id' => $bookingId,
                'found' => (bool)$pembayaran
            ]);
        }
        
        // 6. If we still can't find the payment, try to find the most recent pending payment
        if (!$pembayaran) {
            $pembayaran = Pembayaran::where('status_pembayaran', 'Pending')
                ->orderBy('created_at', 'desc')
                ->first();
            Log::info('Trying to find most recent pending payment as fallback', ['found' => (bool)$pembayaran]);
        }
        
        if (!$pembayaran) {
            Log::error('Payment not found for webhook', [
                'invoice_id' => $invoiceId,
                'external_id' => $externalId,
                'all_webhook_data' => $data
            ]);
            return response()->json(['status' => 'error', 'message' => 'Payment not found'], 404);
        }

        // Log current status before update
        Log::info('Current payment status before update', [
            'payment_id' => $pembayaran->id,
            'status' => $pembayaran->status_pembayaran,
            'booking_id' => $pembayaran->booking_id
        ]);

        // Update status based on the payment status
        if ($status == 'PAID' || $status == 'COMPLETED') {
            // Directly update payment status without using updatePaymentSuccess method
            // This avoids any potential session dependencies
            $pembayaran->status_pembayaran = 'Selesai'; // Use 'Selesai' instead of 'Berhasil'
            $pembayaran->method = $paymentMethod ?: $pembayaran->method;
            $pembayaran->payment_date = now();
            $pembayaran->save();
            
            // Update booking status
            $booking = Booking::find($pembayaran->booking_id);
            if ($booking && strtolower($booking->status_proses) === 'pending') {
                $booking->status_proses = 'Dikonfirmasi';
                $booking->save();
                
                Log::info('Booking status updated', [
                    'booking_id' => $booking->id,
                    'new_status' => $booking->status_proses
                ]);
            }
            
            Log::info('Payment marked as completed via API webhook', [
                'payment_id' => $pembayaran->id,
                'new_status' => $pembayaran->status_pembayaran
            ]);
        } elseif (in_array($status, ['EXPIRED', 'FAILED'])) {
            // Update payment status to failed
            $pembayaran->status_pembayaran = 'Dibatalkan'; // Use 'Dibatalkan' instead of 'Gagal'
            $pembayaran->save();
            
            Log::info('Payment marked as failed via API webhook', [
                'payment_id' => $pembayaran->id,
                'new_status' => $pembayaran->status_pembayaran
            ]);
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
