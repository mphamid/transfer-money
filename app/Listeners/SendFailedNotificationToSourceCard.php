<?php

namespace App\Listeners;

use App\Events\FailedTransaction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendFailedNotificationToSourceCard
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(FailedTransaction $event): void
    {
        //
    }
}
