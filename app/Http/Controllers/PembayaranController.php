<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Pembayaran;
use App\Models\BookingSchedule;
use App\Models\Layanan;
use App\Models\Merchant;
use App\Events\OrderCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewOrderNotification;
use Carbon\Carbon;
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\CreateInvoiceRequest;

class PembayaranController extends Controller
{
    public function __construct()
    {
        // Xendit Config
        Configuration::setXenditKey(config('xendit.api_key'));
        
        Log::info('Xendit Callback URL configured');
    }

    public function show($id)
    {
        try {
        // Find booking or fail with 404
            $booking = Booking::with(['user', 'merchant', 'layanan', 'pembayaran'])
                ->findOrFail($id);

        // Check if booking belongs to logged in user
        if ($booking->id_user != Auth::id()) {
            abort(403, 'You are not authorized to access this page');
        }

        // Cek apakah booking milik user yang login
        if ($booking->pembayaran->status_pembayaran == 'Selesai') {
            return redirect()->route('dashboard')->with('success', 'Pembayaran sudah selesai');
        }

        // Cek status pembayaran dari Xendit jika ada order_id
        if ($booking->pembayaran->order_id) {
            $this->checkPaymentStatus($booking->pembayaran);

            // Refresh data booking setelah pengecekan status
            $booking = $booking->fresh(['pembayaran']);

            // Cek lagi setelah refresh, jika sudah selesai redirect ke dashboard
            if ($booking->pembayaran->status_pembayaran == 'Selesai') {
                return redirect()->route('dashboard')->with('success', 'Pembayaran sudah selesai');
            }
        }

        // Ambil data tarif layanan
        $tarifLayanan = DB::table('tarif_layanan')
            ->where('id_layanan', $booking->id_layanan)
            ->first();

            if (!$tarifLayanan) {
                abort(404, 'Service rate not found');
            }

            return view('pembayaran.show', compact('booking', 'tarifLayanan'));

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Booking not found');
        } catch (\Exception $e) {
            abort(500, 'An error occurred while processing your request');
        }
    }

    public function process(Request $request, $id)
    {
        try {
            // Find booking or fail with 404
            $booking = Booking::with(['user', 'merchant', 'layanan', 'pembayaran'])
                ->findOrFail($id);

        // Check if booking belongs to logged in user
        if ($booking->id_user != Auth::id()) {
            abort(403, 'You are not authorized to access this page');
        }

        // Ambil data pembayaran
        $pembayaran = $booking->pembayaran;

        // Cek apakah pembayaran sudah selesai
        if ($pembayaran->status_pembayaran == 'Selesai') {
            return redirect()->route('dashboard')->with('info', 'Pembayaran sudah selesai');
        }

        // Ambil metode pembayaran dari form
        $paymentMethod = $request->input('payment_method', 'bank_transfer');

        // Update metode pembayaran
        $pembayaran->update([
            'method' => $paymentMethod,
        ]);

        // Buat transaksi di Xendit
        $externalId = 'HOMIZE-' . $booking->id . '-' . time();
        
        // Konfigurasi payment channels berdasarkan metode yang dipilih
        $paymentChannels = [];

        switch ($paymentMethod) {
            case 'bank_transfer':
                $paymentChannels = ['BCA', 'BNI', 'BRI', 'PERMATA'];
                break;
            case 'e_wallet':
                $paymentChannels = ['OVO', 'DANA', 'SHOPEEPAY', 'LINKAJA'];
                break;
            case 'credit_card':
                $paymentChannels = ['CREDIT_CARD'];
                break;
            case 'qris':
                $paymentChannels = ['QRIS'];
                break;
            default:
                $paymentChannels = ['BCA', 'BNI', 'BRI', 'PERMATA'];
        }

        try {
            // Set Xendit API key directly
            $apiKey = config('xendit.api_key');
            
            // Create invoice using Xendit API directly with cURL
            $curl = curl_init();
            
            // Format amount to ensure it's a valid number (no commas, etc.)
            $amount = (float) $pembayaran->amount;
            
            // Basic required parameters
            $params = [
                'external_id' => $externalId,
                'amount' => $amount,
                'description' => 'Pembayaran ' . $booking->layanan->nama_layanan,
                'invoice_duration' => 86400,
                'currency' => 'IDR',
                'success_redirect_url' => route('dashboard') . '?status=success',
                'failure_redirect_url' => route('dashboard') . '?status=failed'
            ];
            
            // Add customer information with default values for required fields
            $customerName = $booking->user && !empty($booking->user->name) ? $booking->user->name : 'Customer';
            $customerEmail = $booking->user && !empty($booking->user->email) ? $booking->user->email : 'customer@example.com';
            
            $params['customer'] = [
                'given_names' => $customerName,
                'email' => $customerEmail
            ];
            
            // Add payment methods if specified - format them correctly for Xendit API
            if (!empty($paymentChannels)) {
                // For Xendit API, we need to use the correct format for payment methods
                // Convert array of payment methods to array of objects with payment_method.type
                $formattedPaymentMethods = [];
                foreach ($paymentChannels as $method) {
                    $formattedPaymentMethods[] = [
                        'payment_method_id' => strtolower($method)
                    ];
                }
                $params['available_payment_methods'] = $formattedPaymentMethods;
            }
            
            // Log the request parameters for debugging
            Log::info('Xendit API request parameters', [
                'params' => $params,
                'booking_id' => $booking->id
            ]);
            
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://api.xendit.co/v2/invoices',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($params),
                CURLOPT_HTTPHEADER => [
                    'Authorization: Basic ' . base64_encode($apiKey . ':'),
                    'Content-Type: application/json'
                ],
            ]);
            
            $response = curl_exec($curl);
            $err = curl_error($curl);
            
            curl_close($curl);
            
            if ($err) {
                throw new \Exception('cURL Error: ' . $err);
            }
            
            $result = json_decode($response, true);
            
            if (isset($result['error_code'])) {
                // Log the full error response for debugging
                Log::error('Xendit API error response', [
                    'error_code' => $result['error_code'],
                    'message' => $result['message'] ?? 'Unknown error',
                    'full_response' => $result,
                    'request_params' => $params
                ]);
                
                throw new \Exception('Xendit Error: ' . ($result['message'] ?? 'Unknown error'));
            }
            
            // Get the invoice URL and ID from the result
            $invoiceUrl = $result['invoice_url'];
            $invoiceId = $result['id'];
            
            // Update order_id dan snap_token di tabel pembayaran
            // Menggunakan invoice_url sebagai snap_token untuk kompatibilitas
            $pembayaran->update([
                'order_id' => $invoiceId, // Use the Xendit invoice ID as order_id
                'snap_token' => $invoiceUrl,
            ]);
            
            // Log successful invoice creation
            Log::info('Xendit invoice created successfully', [
                'invoice_id' => $invoiceId,
                'invoice_url' => $invoiceUrl,
                'booking_id' => $booking->id,
                'response' => $result
            ]);
            
            // Redirect ke halaman invoice Xendit
            return redirect()->to($invoiceUrl);
            } catch (\Exception $e) {
                // Log detailed error information
                Log::error('Xendit error: ' . $e->getMessage(), [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                    'payment_method' => $paymentMethod,
                    'booking_id' => $booking->id
                ]);
                return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Booking not found');
        } catch (\Exception $e) {
            abort(500, 'An error occurred while processing your request');
        }
    }

    public function callback(Request $request)
    {
        // Log semua request untuk debugging
        Log::info('Xendit callback received', $request->all());

        try {
            // Cek apakah ini request test dari Xendit
            if ($request->isMethod('get')) {
                // Kembalikan 200 OK untuk test request
                return response('OK', 200);
            }

            // Kode lainnya tetap sama
            $callbackToken = config('xendit.callback_token');
            $serverKey = config('xendit.api_key');
            $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

            if ($hashed == $request->signature_key) {
                // Extract order ID
                $order_id = $request->order_id;

                // Find payment by order_id
                $pembayaran = Pembayaran::where('order_id', $order_id)->first();

                if (!$pembayaran) {
                    Log::error('Payment not found for order_id: ' . $order_id);
                    return response()->json(['status' => 'error', 'message' => 'Payment not found']);
                }

                // Update payment status based on transaction status
                if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                    // Payment success - OTOMATIS update ke Payment Completed
                    $pembayaran->update([
                        'status_pembayaran' => 'Selesai',
                        'method' => $request->payment_type,
                        'payment_date' => now(),
                        'otp_attempts' => 0, // Reset percobaan OTP
                    ]);

                    // OTOMATIS update booking status to Pending (menunggu konfirmasi seller)
                    $booking = $pembayaran->booking;
                    $booking->update([
                        'status_proses' => 'Pending',
                    ]);

                    // Load relasi yang dibutuhkan
                    $booking->load(['user', 'merchant', 'merchant.user', 'layanan', 'pembayaran', 'booking_schedule']);

                    // Tambahkan pengecekan merchant lebih detail
                    Log::info('Detail merchant untuk booking #' . $booking->id, [
                        'merchant_id' => $booking->merchant->id ?? 'tidak ada',
                        'merchant_nama' => $booking->merchant->nama_usaha ?? 'tidak ada',
                        'merchant_user_id' => $booking->merchant->user->id ?? 'tidak ada',
                        'merchant_email' => $booking->merchant->user->email ?? 'tidak ada'
                    ]);

                    // Kirim email langsung tanpa event
                    // try {
                    //     if ($booking->merchant && $booking->merchant->user && $booking->merchant->user->email) {
                    //         Mail::to($booking->merchant->user->email)
                    //             ->send(new NewOrderNotification($booking));

                    //         Log::info('Email notifikasi pesanan baru berhasil dikirim langsung', [
                    //             'booking_id' => $booking->id,
                    //             'merchant_email' => $booking->merchant->user->email
                    //         ]);
                    //     } else {
                    //         Log::warning('Tidak dapat mengirim email: data merchant atau user tidak lengkap', [
                    //             'booking_id' => $booking->id,
                    //             'merchant' => $booking->merchant ? 'ada' : 'tidak ada',
                    //             'merchant_user' => ($booking->merchant && $booking->merchant->user) ? 'ada' : 'tidak ada',
                    //             'merchant_email' => ($booking->merchant && $booking->merchant->user) ? $booking->merchant->user->email : 'tidak ada'
                    //         ]);
                    //     }
                    // } catch (\Exception $e) {
                    //     Log::error('Gagal mengirim email notifikasi pesanan baru langsung', [
                    //         'booking_id' => $booking->id,
                    //         'error' => $e->getMessage(),
                    //         'trace' => $e->getTraceAsString()
                    //     ]);
                    // }

                    // Setelah itu, coba trigger event
                    try {
                        event(new OrderCreated($booking));
                        Log::info('Event OrderCreated berhasil dipicu', [
                            'booking_id' => $booking->id
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Gagal memicu event OrderCreated', [
                            'booking_id' => $booking->id,
                            'error' => $e->getMessage()
                        ]);
                    }

                    Log::info('Payment completed for order_id: ' . $order_id);
                } elseif ($request->transaction_status == 'deny' || $request->transaction_status == 'cancel' || $request->transaction_status == 'expire') {
                    // Cek apakah ini kegagalan 3DS
                    if (
                        $request->status_code == '202' ||
                        (isset($request->fraud_status) && $request->fraud_status == 'challenge') ||
                        (isset($request->status_message) && strpos(strtolower($request->status_message), '3ds') !== false)
                    ) {

                        // Ini adalah kegagalan 3DS, tambah jumlah percobaan
                        $attempts = $pembayaran->otp_attempts + 1;
                        $maxAttempts = config('xendit.max_otp_attempts', 3);

                        if ($attempts >= $maxAttempts) {
                            // Jika sudah melebihi batas percobaan, anggap gagal
                            $pembayaran->update([
                                'status_pembayaran' => 'Dibatalkan',
                                'method' => $request->payment_type,
                                'payment_date' => now(),
                                'otp_attempts' => $attempts,
                            ]);

                            // Update booking status to Payment Failed juga
                            $pembayaran->booking->update([
                                'status_proses' => 'Dibatalkan',
                            ]);

                            Log::info('Payment failed after ' . $attempts . ' OTP attempts for order_id: ' . $order_id);
                        } else {
                            // Masih ada kesempatan, tetap di status pending
                            $pembayaran->update([
                                'method' => $request->payment_type,
                                'otp_attempts' => $attempts,
                            ]);

                            Log::info('3DS verification failed, attempt ' . $attempts . ' of ' . $maxAttempts . ' for order_id: ' . $order_id);
                        }
                    } else {
                        // Kegagalan normal, update ke failed
                        $pembayaran->update([
                            'status_pembayaran' => 'Dibatalkan',
                            'method' => $request->payment_type,
                            'payment_date' => now(),
                            'otp_attempts' => 0, // Reset percobaan OTP
                        ]);

                        // Update booking status to Payment Failed juga
                        $pembayaran->booking->update([
                            'status_proses' => 'Dibatalkan',
                        ]);

                        Log::info('Payment failed for order_id: ' . $order_id);
                    }
                } else {
                    Log::info('Payment status: ' . $request->transaction_status . ' for order_id: ' . $order_id);
                }

                return response()->json(['status' => 'success']);
            }

            Log::warning('Invalid signature for order_id: ' . $request->order_id);
            return response()->json(['status' => 'error', 'message' => 'Invalid signature']);
        } catch (\Exception $e) {
            Log::error('Error processing Xendit callback: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
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

        // Buat transaksi di Xendit
        $externalId = 'HOMIZE-' . $booking->id . '-' . time();
        
        try {
            // Set Xendit API key directly
            $apiKey = config('xendit.api_key');
            
            // Create invoice using Xendit API directly with cURL
            $curl = curl_init();
            
            // Format amount to ensure it's a valid number (no commas, etc.)
            $amount = (float) $pembayaran->amount;
            
            // Basic required parameters
            $params = [
                'external_id' => $externalId,
                'amount' => $amount,
                'description' => 'Pembayaran ' . $booking->layanan->nama_layanan,
                'invoice_duration' => 86400,
                'currency' => 'IDR',
                'success_redirect_url' => route('dashboard') . '?status=success',
                'failure_redirect_url' => route('dashboard') . '?status=failed'
            ];
            
            // Add customer information with default values for required fields
            $customerName = $booking->user && !empty($booking->user->name) ? $booking->user->name : 'Customer';
            $customerEmail = $booking->user && !empty($booking->user->email) ? $booking->user->email : 'customer@example.com';
            
            $params['customer'] = [
                'given_names' => $customerName,
                'email' => $customerEmail
            ];
            
            // Log the request parameters for debugging
            Log::info('Xendit API request parameters for token', [
                'params' => $params,
                'booking_id' => $booking->id
            ]);
            
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://api.xendit.co/v2/invoices',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($params),
                CURLOPT_HTTPHEADER => [
                    'Authorization: Basic ' . base64_encode($apiKey . ':'),
                    'Content-Type: application/json'
                ],
            ]);
            
            $response = curl_exec($curl);
            $err = curl_error($curl);
            
            curl_close($curl);
            
            if ($err) {
                throw new \Exception('cURL Error: ' . $err);
            }
            
            $result = json_decode($response, true);
            
            if (isset($result['error_code'])) {
                throw new \Exception('Xendit Error: ' . ($result['message'] ?? 'Unknown error'));
            }
            
            // Get the invoice URL and ID from the result
            $invoiceUrl = $result['invoice_url'];
            $invoiceId = $result['id'];
            
            // Update order_id dan snap_token di tabel pembayaran
            // Menggunakan invoice_url sebagai snap_token untuk kompatibilitas
            $pembayaran->update([
                'order_id' => $invoiceId, // Use the Xendit invoice ID as order_id
                'snap_token' => $invoiceUrl,
            ]);

            return response()->json(['token' => $invoiceUrl]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    // Tambahkan method untuk halaman process_popup
    public function processPopup($id)
    {
        try {
            // Find booking or fail with 404
            $booking = Booking::with(['user', 'merchant', 'layanan', 'pembayaran'])
                ->findOrFail($id);

            // Check if booking belongs to logged in user
            if ($booking->id_user != Auth::id()) {
                abort(403, 'You are not authorized to access this page');
            }

            // Ambil data pembayaran
            $pembayaran = $booking->pembayaran;

            if (!$pembayaran) {
                abort(404, 'Payment data not found');
            }

            return view('pembayaran.process_popup', compact('booking', 'pembayaran'));

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Booking not found');
        } catch (\Exception $e) {
            abort(500, 'An error occurred while processing your request');
        }
    }

    // Tambahkan method baru untuk cek status pembayaran dari Xendit
    private function checkPaymentStatus($pembayaran)
    {
        try {
            // Set konfigurasi Xendit
            \Xendit\Configuration::setXenditKey(config('xendit.api_key'));

            // Inisialisasi Xendit Invoice API
            $apiInstance = new \Xendit\Invoice\InvoiceApi();
            
            // Get invoice status dari Xendit
            try {
                $invoice = $apiInstance->getInvoiceById($pembayaran->order_id);
                $status = $invoice->getStatus();
            } catch (\Exception $e) {
                // If there's an error getting the invoice, log it and return null
                Log::error('Error getting Xendit invoice: ' . $e->getMessage(), [
                    'order_id' => $pembayaran->order_id,
                    'payment_id' => $pembayaran->id
                ]);
                return null;
            }

            Log::info('Checking payment status for order_id: ' . $pembayaran->order_id, [
                'xendit_status' => $status ?? 'unknown'
            ]);

            // Update status pembayaran berdasarkan response dari Xendit
            if ($status) {
                Log::info('Status transaksi: ' . $status);

                if ($status == 'PAID') {
                    // Payment success
                    $pembayaran->update([
                        'status_pembayaran' => 'Selesai',
                        'method' => $status->payment_type ?? $pembayaran->method,
                        'payment_date' => now(),
                        'otp_attempts' => 0, // Reset percobaan OTP
                    ]);

                    // Update booking status to Pending (menunggu konfirmasi seller)
                    $booking = $pembayaran->booking;
                    $booking->update([
                        'status_proses' => 'Pending',
                    ]);

                    // Load relasi yang dibutuhkan
                    $booking->load(['user', 'merchant', 'merchant.user', 'layanan', 'pembayaran', 'booking_schedule']);

                    // Tambahkan pengecekan merchant lebih detail
                    Log::info('Detail merchant untuk booking #' . $booking->id, [
                        'merchant_id' => $booking->merchant->id ?? 'tidak ada',
                        'merchant_nama' => $booking->merchant->nama_usaha ?? 'tidak ada',
                        'merchant_user_id' => $booking->merchant->user->id ?? 'tidak ada',
                        'merchant_email' => $booking->merchant->user->email ?? 'tidak ada'
                    ]);

                    try {
                        if ($booking->merchant && $booking->merchant->user && $booking->merchant->user->email) {
                            Mail::to($booking->merchant->user->email)
                                ->send(new NewOrderNotification($booking));

                            Log::info('Email notifikasi pesanan baru berhasil dikirim langsung', [
                                'booking_id' => $booking->id,
                                'merchant_email' => $booking->merchant->user->email
                            ]);
                        } else {
                            Log::warning('Tidak dapat mengirim email: data merchant atau user tidak lengkap', [
                                'booking_id' => $booking->id,
                                'merchant' => $booking->merchant ? 'ada' : 'tidak ada',
                                'merchant_user' => ($booking->merchant && $booking->merchant->user) ? 'ada' : 'tidak ada',
                                'merchant_email' => ($booking->merchant && $booking->merchant->user) ? $booking->merchant->user->email : 'tidak ada'
                            ]);
                        }
                    } catch (\Exception $e) {
                        Log::error('Gagal mengirim email notifikasi pesanan baru langsung', [
                            'booking_id' => $booking->id,
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString()
                        ]);
                    }

                    // Setelah itu, coba trigger event
                    try {
                        event(new OrderCreated($booking));
                        Log::info('Event OrderCreated berhasil dipicu', [
                            'booking_id' => $booking->id
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Gagal memicu event OrderCreated', [
                            'booking_id' => $booking->id,
                            'error' => $e->getMessage()
                        ]);
                    }

                    Log::info('Payment completed for order_id: ' . $pembayaran->order_id);
                    return true;
                } elseif ($status == 'EXPIRED' || $status == 'FAILED') {
                    // Cek apakah ini kegagalan 3DS
                    $paymentMethod = $status->payment_method ?? '';
                    $failureReason = $status->failure_reason ?? '';
                    
                    if (strpos(strtolower($failureReason), '3ds') !== false) {

                        // Ini adalah kegagalan 3DS, tambah jumlah percobaan
                        $attempts = $pembayaran->otp_attempts + 1;
                        $maxAttempts = config('xendit.max_otp_attempts', 3);

                        if ($attempts >= $maxAttempts) {
                            // Jika sudah melebihi batas percobaan, anggap gagal
                            $pembayaran->update([
                                'status_pembayaran' => 'Dibatalkan',
                                'method' => $status->payment_type ?? $pembayaran->method,
                                'payment_date' => now(),
                                'otp_attempts' => $attempts,
                            ]);

                            // Update booking status to Payment Failed juga
                            $pembayaran->booking->update([
                                'status_proses' => 'Dibatalkan',
                            ]);

                            Log::info('Payment failed after ' . $attempts . ' OTP attempts for order_id: ' . $pembayaran->order_id);
                            return false;
                        } else {
                            // Masih ada kesempatan, tetap di status pending
                            $pembayaran->update([
                                'method' => $status->payment_type ?? $pembayaran->method,
                                'otp_attempts' => $attempts,
                            ]);

                            Log::info('3DS verification failed, attempt ' . $attempts . ' of ' . $maxAttempts . ' for order_id: ' . $pembayaran->order_id);
                            return null;
                        }
                    } else {
                        // Payment failed - bukan kegagalan 3DS
                        $pembayaran->update([
                            'status_pembayaran' => 'Dibatalkan',
                            'method' => $status->payment_type ?? $pembayaran->method,
                            'payment_date' => now(),
                            'otp_attempts' => 0, // Reset percobaan OTP
                        ]);

                        // Update booking status to Payment Failed juga
                        $pembayaran->booking->update([
                            'status_proses' => 'Dibatalkan',
                        ]);

                        Log::info('Payment failed for order_id: ' . $pembayaran->order_id);
                        return false;
                    }
                } else {
                    Log::info('Payment status masih pending: ' . $status);
                }
            } else {
                Log::warning('Tidak ada status transaksi dari Midtrans untuk order_id: ' . $pembayaran->order_id);
            }
        } catch (\Exception $e) {
            // Log error tapi jangan throw exception
            Log::error('Error checking payment status: ' . $e->getMessage());
            return false;
        }

        return null;
    }

    public function checkStatus($id)
    {
        // Ambil data booking
        $booking = Booking::with(['pembayaran'])
            ->findOrFail($id);

        // Cek apakah booking milik user yang login
        if ($booking->id_user != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Cek status pembayaran dari Xendit jika ada order_id
        if ($booking->pembayaran->order_id) {
            // PENTING: Tambahkan parameter true untuk force refresh
            $paymentResult = $this->checkPaymentStatus($booking->pembayaran);
            Log::info('Payment status check result: ' . ($paymentResult === true ? 'completed' : ($paymentResult === false ? 'failed' : 'pending')));

            // Refresh data booking setelah pengecekan status
            $booking = $booking->fresh(['pembayaran']);
        }

        // Logging status saat ini untuk debugging
        Log::info('Current payment status: ' . $booking->pembayaran->status_pembayaran);
        Log::info('Current booking status: ' . $booking->status_proses);

        // Return status pembayaran
        if ($booking->pembayaran->status_pembayaran == 'Selesai') {
            return response()->json(['status' => 'completed']);
        } elseif ($booking->pembayaran->status_pembayaran == 'Dibatalkan') {
            return response()->json(['status' => 'failed']);
        } else {
            return response()->json(['status' => 'pending']);
        }
    }

    public function forceCheckStatus($id)
    {
        $booking = Booking::with(['pembayaran'])->findOrFail($id);

        if (!$booking->pembayaran || !$booking->pembayaran->order_id) {
            return redirect()->back()->with('error', 'Tidak ada data pembayaran untuk pesanan ini');
        }

        try {
            // Set Xendit API key directly
            $apiKey = config('xendit.api_key');
            
            // Create curl request to check invoice status
            $curl = curl_init();
            
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://api.xendit.co/v2/invoices/' . $booking->pembayaran->order_id,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => [
                    'Authorization: Basic ' . base64_encode($apiKey . ':'),
                    'Content-Type: application/json'
                ],
            ]);
            
            $response = curl_exec($curl);
            $err = curl_error($curl);
            
            curl_close($curl);
            
            if ($err) {
                throw new \Exception('cURL Error: ' . $err);
            }
            
            $status = json_decode($response);
            
            if (isset($status->error_code)) {
                throw new \Exception('Xendit Error: ' . ($status->message ?? 'Unknown error'));
            }

            Log::info('Manual check status: ', [
                'order_id' => $booking->pembayaran->order_id,
                'status' => $status
            ]);

            // Proses status
            if ($status->status == 'PAID' || $status->status == 'SETTLED') {
                // Update ke Payment Completed
                $booking->pembayaran->update([
                    'status_pembayaran' => 'Selesai',
                    'method' => $booking->pembayaran->method,
                    'payment_date' => now(),
                    'otp_attempts' => 0, // Reset percobaan OTP
                ]);

                // Update booking status to Pending
                $booking->update([
                    'status_proses' => 'Pending',
                ]);

                return redirect()->back()->with('success', 'Status pembayaran berhasil diperbarui. Pembayaran telah selesai.');
            } elseif ($status->status == 'EXPIRED' || $status->status == 'FAILED') {
                // Kegagalan normal
                return redirect()->back()->with('info', 'Status pembayaran: ' . $status->status);
            }

            return redirect()->back()->with('info', 'Status pembayaran: ' . $status->status);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // Tambahkan method ini setelah method callback
    public function callbackTest()
    {
        Log::info('GET Test callback accessed');
        return response('OK', 200);
    }

    // Tambahkan method baru untuk reset percobaan OTP
    public function resetOtpAttempts($id)
    {
        $booking = Booking::with(['pembayaran'])->findOrFail($id);

        // Cek apakah booking milik user yang login
        if ($booking->id_user != Auth::id()) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Reset percobaan OTP
        $booking->pembayaran->update([
            'otp_attempts' => 0
        ]);

        return redirect()->route('pembayaran.process_popup', $booking->id)
            ->with('success', 'Percobaan OTP telah direset. Silakan coba lagi.');
    }

    public function getVaNumber($id)
    {
        // Ambil data booking
        $booking = Booking::with(['pembayaran'])->findOrFail($id);

        // Cek apakah booking milik user yang login
        if ($booking->id_user != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Cek apakah ada order_id
        if (!$booking->pembayaran->order_id) {
            return response()->json(['error' => 'Belum ada transaksi'], 400);
        }

        try {
            // Set Xendit API key directly
            $apiKey = config('xendit.api_key');
            
            // Create curl request to check invoice status
            $curl = curl_init();
            
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://api.xendit.co/v2/invoices/' . $booking->pembayaran->order_id,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => [
                    'Authorization: Basic ' . base64_encode($apiKey . ':'),
                    'Content-Type: application/json'
                ],
            ]);
            
            $response = curl_exec($curl);
            $err = curl_error($curl);
            
            curl_close($curl);
            
            if ($err) {
                throw new \Exception('cURL Error: ' . $err);
            }
            
            $status = json_decode($response);
            
            if (isset($status->error_code)) {
                throw new \Exception('Xendit Error: ' . ($status->message ?? 'Unknown error'));
            }

            // Log untuk debugging
            Log::info('Transaction status for payment details:', [
                'order_id' => $booking->pembayaran->order_id,
                'status' => $status
            ]);

            // Initialize payment details
            $paymentDetails = null;
            $paymentType = null;
            $bank = null;

            // Check if payment method details are available
            if (isset($status->payment_method) && !empty($status->payment_method)) {
                $paymentType = $status->payment_method;
                
                // For QRIS
                if (strtolower($paymentType) === 'qris') {
                    $paymentDetails = 'QRIS Code tersedia di halaman Xendit';
                }
                // For Virtual Account
                elseif (isset($status->payment_details) && isset($status->payment_details->bank_code)) {
                    $bank = $status->payment_details->bank_code;
                    $paymentDetails = $status->payment_details->account_number ?? null;
                }
                // For E-Wallet
                elseif (isset($status->payment_details) && isset($status->payment_details->ewallet_type)) {
                    $paymentDetails = 'E-Wallet: ' . $status->payment_details->ewallet_type;
                }
            }

            return response()->json([
                'va_number' => $paymentDetails,
                'bank' => $bank,
                'payment_type' => $paymentType
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting payment details: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function showQris($id)
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
        if ($pembayaran->status_pembayaran == 'Selesai') {
            return redirect()->route('dashboard')->with('info', 'Pembayaran sudah selesai');
        }

        // Buat transaksi di Xendit khusus untuk QRIS
        $externalId = 'HOMIZE-' . $booking->id . '-' . time();
        
        try {
            // Set Xendit API key directly
            $apiKey = config('xendit.api_key');
            
            // Create invoice using Xendit API directly with cURL
            $curl = curl_init();
            
            // Format amount to ensure it's a valid number (no commas, etc.)
            $amount = (float) $pembayaran->amount;
            
            // Basic required parameters
            $params = [
                'external_id' => $externalId,
                'amount' => $amount,
                'description' => 'Pembayaran ' . $booking->layanan->nama_layanan,
                'invoice_duration' => 86400,
                'currency' => 'IDR',
                'success_redirect_url' => route('dashboard') . '?status=success',
                'failure_redirect_url' => route('dashboard') . '?status=failed'
            ];
            
            // Add customer information with default values for required fields
            $customerName = $booking->user && !empty($booking->user->name) ? $booking->user->name : 'Customer';
            $customerEmail = $booking->user && !empty($booking->user->email) ? $booking->user->email : 'customer@example.com';
            
            $params['customer'] = [
                'given_names' => $customerName,
                'email' => $customerEmail
            ];
            
            // Add QRIS as the only payment method
            $params['available_payment_methods'] = [['payment_method_id' => 'qris']];
            
            // Log the request parameters for debugging
            Log::info('Xendit API request parameters for QRIS', [
                'params' => $params,
                'booking_id' => $booking->id
            ]);
            
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://api.xendit.co/v2/invoices',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($params),
                CURLOPT_HTTPHEADER => [
                    'Authorization: Basic ' . base64_encode($apiKey . ':'),
                    'Content-Type: application/json'
                ],
            ]);
            
            $response = curl_exec($curl);
            $err = curl_error($curl);
            
            curl_close($curl);
            
            if ($err) {
                throw new \Exception('cURL Error: ' . $err);
            }
            
            $result = json_decode($response, true);
            
            if (isset($result['error_code'])) {
                throw new \Exception('Xendit Error: ' . ($result['message'] ?? 'Unknown error'));
            }
            
            // Get the invoice URL and ID from the result
            $invoiceUrl = $result['invoice_url'];
            $invoiceId = $result['id'];
            
            // Update order_id dan snap_token di tabel pembayaran
            // Menggunakan invoice_url sebagai snap_token untuk kompatibilitas
            $pembayaran->update([
                'order_id' => $invoiceId, // Use the Xendit invoice ID as order_id
                'snap_token' => $invoiceUrl,
                'method' => 'qris',
            ]);
            
            // Render view dengan Xendit invoice URL
            return view('pembayaran.qris', compact('booking', 'pembayaran', 'invoiceUrl'));
        } catch (\Exception $e) {
            Log::error('Xendit QRIS error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Show static QRIS payment page with unique code
     */
    public function showStaticQris($id)
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
        if ($pembayaran->status_pembayaran == 'Selesai') {
            return redirect()->route('dashboard')->with('info', 'Pembayaran sudah selesai');
        }
        
        // Generate unique code if not already set
        if (!$pembayaran->unique_code) {
            $uniqueCode = Pembayaran::generateUniqueCode();
            $pembayaran->update([
                'unique_code' => $uniqueCode,
                'method' => 'qris_static'
            ]);
        }
        
        // Refresh data pembayaran
        $pembayaran = $pembayaran->fresh();
        
        // Prepare WhatsApp message
        $whatsappNumber = config('app.admin_whatsapp', '6281234567890');
        $message = "Halo Admin Homize, saya ingin konfirmasi pembayaran:\n\n";
        $message .= "ID Pesanan: #{$booking->id}\n";
        $message .= "Layanan: {$booking->layanan->nama_layanan}\n";
        $message .= "Total Pembayaran: {$pembayaran->formatted_total_amount}\n";
        $message .= "Kode Unik: {$pembayaran->unique_code}\n\n";
        $message .= "Terima kasih.";
        
        // Encode the message for WhatsApp URL
        $encodedMessage = urlencode($message);
        $whatsappUrl = "https://wa.me/{$whatsappNumber}?text={$encodedMessage}";
        
        return view('pembayaran.qris_static', compact('booking', 'pembayaran', 'whatsappUrl'));
    }

    public function saveOrder($id)
    {
        // Ambil data booking
        $booking = Booking::with(['pembayaran'])->findOrFail($id);

        // Cek apakah booking milik user yang login
        if ($booking->id_user != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        // Update payment status to pending if not already
        if ($booking->pembayaran->status_pembayaran !== 'Pending') {
            $booking->pembayaran->update([
                'status_pembayaran' => 'Pending'
            ]);
        }
        
        return response()->json(['success' => true, 'message' => 'Order saved with pending status']);
    }

    public function approvePayment($id)
    {
        try {
            // Get the payment and booking IDs
            $pembayaran = Pembayaran::with('booking')->findOrFail($id);
            $bookingId = $pembayaran->booking->id;
            
            // Use PDO directly for maximum reliability
            $pdo = DB::connection()->getPdo();
            
            // Update payment status
            $stmt = $pdo->prepare("UPDATE pembayaran SET status_pembayaran = 'Selesai', payment_date = NOW() WHERE id = ?");
            $stmt->execute([$id]);
            
            // Update booking status
            $stmt = $pdo->prepare("UPDATE booking SET status_proses = 'Pending' WHERE id = ?");
            $stmt->execute([$bookingId]);
            
            // Trigger order created event
            try {
                event(new OrderCreated($pembayaran->booking));
            } catch (\Exception $e) {
                // Just log the error but continue
                Log::error('Error triggering OrderCreated event: ' . $e->getMessage());
            }
            
            // Redirect with success message
            return redirect()->route('admin.transactions')
                ->with('success', 'Pembayaran #' . $id . ' berhasil disetujui');
            
        } catch (\Exception $e) {
            Log::error('Error approving payment: ' . $e->getMessage());
            return redirect()->route('admin.transactions')
                ->with('error', 'Terjadi kesalahan saat menyetujui pembayaran');
        }
    }

    public function rejectPayment(Request $request, $id)
    {
        // Validate request
        $request->validate([
            'rejection_reason' => 'required|string|max:255'
        ], [
            'rejection_reason.required' => 'Alasan penolakan harus diisi',
            'rejection_reason.string' => 'Alasan penolakan harus berupa string',
            'rejection_reason.max' => 'Alasan penolakan tidak boleh lebih dari 255 karakter',
        ]);
        
        try {
            // Get the payment and booking IDs
            $pembayaran = Pembayaran::with('booking')->findOrFail($id);
            $bookingId = $pembayaran->booking->id;

            // Use PDO directly for maximum reliability
            $pdo = DB::connection()->getPdo();

            // Update payment status
            $stmt = $pdo->prepare("UPDATE pembayaran SET status_pembayaran = 'Dibatalkan', rejection_reason = ?, payment_date = NOW() WHERE id = ?");
            $stmt->execute([$request->rejection_reason, $id]);

            // Update booking status
            $stmt = $pdo->prepare("UPDATE booking SET status_proses = 'Dibatalkan' WHERE id = ?");
            $stmt->execute([$bookingId]);

            // If AJAX/fetch/JSON request, return JSON
            if ($request->expectsJson() || $request->isJson() || $request->wantsJson()) {
                return response()->json(['success' => true]);
            }

            // Otherwise, fallback to redirect
            return redirect()->route('admin.transactions')
                ->with('success', 'Pembayaran #' . $id . ' berhasil ditolak');
        } catch (\Exception $e) {
            Log::error('Error rejecting payment: ' . $e->getMessage());
            if ($request->expectsJson() || $request->isJson() || $request->wantsJson()) {
                // TEMP: Return real error for debugging
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->route('admin.transactions')
                ->with('error', 'Terjadi kesalahan saat menolak pembayaran');
        }
    }
}