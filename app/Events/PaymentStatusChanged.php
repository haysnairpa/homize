<?php

namespace App\Events;

use App\Models\Pembayaran;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentStatusChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $pembayaran;
    public $status;
    public $reason;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\Pembayaran  $pembayaran
     * @param  string  $status
     * @param  string|null  $reason
     * @return void
     */
    public function __construct(Pembayaran $pembayaran, string $status, ?string $reason = null)
    {
        $this->pembayaran = $pembayaran;
        $this->status = $status; // 'confirmed' atau 'rejected'
        $this->reason = $reason;
    }
}
