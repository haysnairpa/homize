<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NewOrderNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pesanan Baru: #' . $this->booking->id,
        );
    }

    public function content(): Content
    {
        // Tambahkan pengecekan data merchant
        if (!$this->booking->merchant || !$this->booking->merchant->nama_usaha) {
            Log::warning('Data merchant tidak lengkap untuk email notifikasi', [
                'booking_id' => $this->booking->id,
                'merchant_id' => $this->booking->merchant->id ?? 'tidak ada',
                'merchant_nama' => $this->booking->merchant->nama_usaha ?? 'tidak ada'
            ]);
        }
        
        return new Content(
            markdown: 'emails.orders.new-order',
        );
    }
}