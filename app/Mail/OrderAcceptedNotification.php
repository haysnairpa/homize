<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use App\Mail\SyncMailTrait;

class OrderAcceptedNotification extends Mailable
{
    use SyncMailTrait;

    public $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pesanan #' . $this->booking->id . ' Telah Dikonfirmasi',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.orders.accepted',
        );
    }
}
