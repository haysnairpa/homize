<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderApprovedNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The booking instance.
     *
     * @var \App\Models\Booking
     */
    public $booking;

    /**
     * Whether this is for admin or merchant
     *
     * @var bool
     */
    public $isForAdmin;

    /**
     * Create a new message instance.
     *
     * @param  \App\Models\Booking  $booking
     * @param  bool  $isForAdmin
     * @return void
     */
    public function __construct(Booking $booking, $isForAdmin = false)
    {
        $this->booking = $booking;
        $this->isForAdmin = $isForAdmin;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->isForAdmin
            ? 'Pesanan #' . $this->booking->id . ' telah disetujui oleh pelanggan'
            : 'Pesanan #' . $this->booking->id . ' telah disetujui oleh pelanggan - Saldo akan segera ditambahkan';

        return $this->subject($subject)
            ->view('emails.order-approved')
            ->with([
                'booking' => $this->booking,
                'isForAdmin' => $this->isForAdmin,
            ]);
    }
}
