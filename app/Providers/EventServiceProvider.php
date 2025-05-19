<?php

namespace App\Providers;

use App\Events\OrderCompleted;
use App\Events\OrderCreated;
use App\Events\PaymentStatusChanged;
use App\Listeners\SendNewOrderNotification;
use App\Listeners\SendOrderCompletedNotification;
use App\Listeners\SendPaymentStatusNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        OrderCreated::class => [
            SendNewOrderNotification::class,
        ],
        OrderCompleted::class => [
            SendOrderCompletedNotification::class,
        ],
        PaymentStatusChanged::class => [
            SendPaymentStatusNotification::class,
        ],
    ];

    public function boot(): void
    {
        //
    }
}