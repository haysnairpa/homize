<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Merchant;
use App\Models\RiwayatSaldoMerchant;
use App\Mail\OrderApprovedNotification;
use App\Mail\OrderProtestedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class CustomerApprovalController extends Controller
{
    /**
     * Show the customer approval page
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Find booking
        $booking = Booking::with(['user', 'merchant', 'layanan', 'pembayaran'])
            ->findOrFail($id);

        // Check if booking belongs to logged in user
        if ($booking->id_user != Auth::id()) {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Check if booking is completed but not yet approved
        if ($booking->status_proses !== 'Selesai') {
            return redirect()->route('dashboard')
                ->with('error', 'Pesanan belum selesai, tidak dapat disetujui');
        }

        // Check if already approved or protested
        if ($booking->customer_approval_status !== null) {
            return redirect()->route('dashboard')
                ->with('info', 'Anda sudah memberikan persetujuan atau protes untuk pesanan ini');
        }

        return view('customer.approval', compact('booking'));
    }

    /**
     * Approve the order
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve($id)
    {
        // Find booking
        $booking = Booking::with(['user', 'merchant', 'layanan', 'pembayaran'])
            ->findOrFail($id);

        // Check if booking belongs to logged in user
        if ($booking->id_user != Auth::id()) {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Check if booking is completed but not yet approved
        if ($booking->status_proses !== 'Selesai') {
            return redirect()->route('dashboard')
                ->with('error', 'Pesanan belum selesai, tidak dapat disetujui');
        }

        // Check if already approved or protested
        if ($booking->customer_approval_status !== null) {
            return redirect()->route('dashboard')
                ->with('info', 'Anda sudah memberikan persetujuan atau protes untuk pesanan ini');
        }

        try {
            // Start database transaction
            DB::beginTransaction();

            // Update booking with approval status
            $booking->update([
                'customer_approval_status' => 'approved',
                'customer_approval_date' => Carbon::now(),
                'merchant_balance_added' => true // Mark balance as added
            ]);

            // Ambil jumlah pembayaran (sudah tidak termasuk kode unik)
            $jumlahPembayaran = $booking->pembayaran->amount;

            // Tambah saldo merchant
            $merchant = $booking->merchant;
            $saldoSebelum = $merchant->saldo;
            $merchant->increment('saldo', $jumlahPembayaran);

            // Buat riwayat saldo merchant
            RiwayatSaldoMerchant::create([
                'id_merchant' => $merchant->id,
                'jumlah' => $jumlahPembayaran,
                'saldo_sebelum' => $saldoSebelum,
                'saldo_sesudah' => $saldoSebelum + $jumlahPembayaran,
                'tipe' => 'masuk',
                'keterangan' => sprintf(
                    'Penambahan saldo dari layanan %s (ID: %d) - Pelanggan: %s %s (%s) - ID Booking: %d - Tanggal Booking: %s - Alamat: %s',
                    $booking->layanan->nama_layanan,
                    $booking->layanan->id,
                    $booking->first_name,
                    $booking->last_name,
                    $booking->contact_email,
                    $booking->id,
                    $booking->tanggal_booking,
                    $booking->alamat_pembeli
                )
            ]);

            // Commit transaction
            DB::commit();

            // Send email notification to admin and merchant
            try {
                // Send to merchant
                if ($booking->merchant && $booking->merchant->user && $booking->merchant->user->email) {
                    Mail::to($booking->merchant->user->email)
                        ->send(new OrderApprovedNotification($booking));
                    
                    Log::info('Order approval notification sent to merchant', [
                        'booking_id' => $booking->id,
                        'merchant_email' => $booking->merchant->user->email,
                    ]);
                }
                
                // Send to admin
                $adminEmail = config('app.admin_email', 'admin@homize.com');
                Mail::to($adminEmail)
                    ->send(new OrderApprovedNotification($booking, true));
                
                Log::info('Order approval notification sent to admin', [
                    'booking_id' => $booking->id,
                    'admin_email' => $adminEmail,
                    'added_balance' => $jumlahPembayaran
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send order approval notification', [
                    'booking_id' => $booking->id,
                    'error' => $e->getMessage()
                ]);
            }

            return redirect()->route('dashboard')
                ->with('success', 'Terima kasih! Pesanan Anda telah disetujui dan saldo merchant telah ditambahkan.');

        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();
            Log::error('Failed to process order approval', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('dashboard')
                ->with('error', 'Terjadi kesalahan saat memproses persetujuan. Silakan coba lagi.');
        }
    }

    /**
     * Submit a protest for the order
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function protest(Request $request, $id)
    {
        // Validate request
        $request->validate([
            'protest_reason' => 'required|string|min:10|max:1000',
        ]);

        // Find booking
        $booking = Booking::with(['user', 'merchant', 'layanan', 'pembayaran'])
            ->findOrFail($id);

        // Check if booking belongs to logged in user
        if ($booking->id_user != Auth::id()) {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Check if booking is completed but not yet approved
        if ($booking->status_proses !== 'Selesai') {
            return redirect()->route('dashboard')
                ->with('error', 'Pesanan belum selesai, tidak dapat diprotes');
        }

        // Check if already approved or protested
        if ($booking->customer_approval_status !== null) {
            return redirect()->route('dashboard')
                ->with('info', 'Anda sudah memberikan persetujuan atau protes untuk pesanan ini');
        }

        // Update booking with protest status
        $booking->update([
            'customer_approval_status' => 'protested',
            'protest_date' => Carbon::now(),
            'protest_reason' => $request->protest_reason,
        ]);

        // Send email notification to admin and merchant
        try {
            // Send to merchant
            if ($booking->merchant && $booking->merchant->user && $booking->merchant->user->email) {
                Mail::to($booking->merchant->user->email)
                    ->send(new OrderProtestedNotification($booking));
                
                Log::info('Order protest notification sent to merchant', [
                    'booking_id' => $booking->id,
                    'merchant_email' => $booking->merchant->user->email
                ]);
            }
            
            // Send to admin
            $adminEmail = config('app.admin_email', 'admin@homize.com');
            Mail::to($adminEmail)
                ->send(new OrderProtestedNotification($booking, true));
            
            Log::info('Order protest notification sent to admin', [
                'booking_id' => $booking->id,
                'admin_email' => $adminEmail
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send order protest notification', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage()
            ]);
        }

        return redirect()->route('dashboard')
            ->with('success', 'Protes Anda telah dikirim. Admin akan meninjau masalah ini segera.');
    }

    /**
     * Admin method to add balance to merchant after customer approval
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addMerchantBalance($id)
    {
        // Find booking
        $booking = Booking::with(['user', 'merchant', 'layanan', 'pembayaran'])
            ->findOrFail($id);

        // Check if booking is approved by customer
        if (!$booking->isApprovedByCustomer()) {
            return redirect()->back()
                ->with('error', 'Pesanan belum disetujui oleh pelanggan');
        }

        // Check if merchant balance already added
        if ($booking->isMerchantBalanceAdded()) {
            return redirect()->back()
                ->with('info', 'Saldo sudah ditambahkan ke merchant untuk pesanan ini');
        }

        // Get merchant and payment amount
        $merchant = $booking->merchant;
        $amount = $booking->pembayaran->amount;

        // Add balance to merchant
        $merchant->update([
            'saldo' => $merchant->saldo + $amount
        ]);

        // Update booking to mark balance as added
        $booking->update([
            'merchant_balance_added' => true
        ]);

        // Log the transaction
        Log::info('Merchant balance added for approved order', [
            'booking_id' => $booking->id,
            'merchant_id' => $merchant->id,
            'amount' => $amount,
            'new_balance' => $merchant->saldo
        ]);

        return redirect()->back()
            ->with('success', 'Saldo sebesar Rp ' . number_format($amount, 0, ',', '.') . ' berhasil ditambahkan ke merchant');
    }
}
