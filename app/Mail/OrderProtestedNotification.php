<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderProtestedNotification extends Mailable
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
            ? 'PERHATIAN: Pesanan #' . $this->booking->id . ' mendapatkan protes dari pelanggan'
            : 'PERHATIAN: Pesanan #' . $this->booking->id . ' mendapatkan protes dari pelanggan - Tindak lanjut diperlukan';

        return $this->subject($subject)
            ->view('emails.order-protested')
            ->with([
                'booking' => $this->booking,
                'isForAdmin' => $this->isForAdmin,
            ]);
    }
}
