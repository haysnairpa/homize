<?php

namespace App\Mail;

use Illuminate\Queue\SerializesModels;

/**
 * Trait for synchronous mail sending (no queueing)
 * Use this instead of Queueable if you want emails to be sent immediately
 */
trait SyncMailTrait
{
    use SerializesModels;
}
