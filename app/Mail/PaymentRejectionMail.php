<?php

namespace App\Mail;

use App\Models\Pembayaran;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentRejectionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pembayaran;
    public $reason;

    /**
     * Create a new message instance.
     *
     * @param  \App\Models\Pembayaran  $pembayaran
     * @param  string|null  $reason
     * @return void
     */
    public function __construct(Pembayaran $pembayaran, ?string $reason = null)
    {
        $this->pembayaran = $pembayaran;
        $this->reason = $reason ?? 'Pembayaran tidak sesuai dengan ketentuan';
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pembayaran Ditolak: #' . $this->pembayaran->booking->id,
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.payments.rejected',
        );
    }
}
