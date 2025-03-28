<?php

namespace App\Jobs;

use App\Models\Pembayaran;
use App\Models\Status;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CheckExpiredPayments implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        // Ambil semua pembayaran dengan status Payment Pending yang sudah lebih dari 24 jam
        $statusPaymentPending = Status::where('nama_status', 'Payment Pending')->first();
        $statusPaymentFailed = Status::where('nama_status', 'Payment Failed')->first();
        
        if (!$statusPaymentPending || !$statusPaymentFailed) {
            Log::error('Status Payment Pending atau Payment Failed tidak ditemukan');
            return;
        }
        
        $expiredPayments = Pembayaran::where('id_status', $statusPaymentPending->id)
            ->where('created_at', '<', Carbon::now()->subHours(24))
            ->get();
            
        foreach ($expiredPayments as $payment) {
            // Update status pembayaran menjadi Payment Failed
            $payment->update([
                'id_status' => $statusPaymentFailed->id
            ]);
            
            // Update status booking juga
            $payment->booking->update([
                'id_status' => $statusPaymentFailed->id
            ]);
            
            Log::info("Payment ID: {$payment->id} expired and marked as failed");
        }
        
        Log::info("Checked " . count($expiredPayments) . " expired payments");
    }
}