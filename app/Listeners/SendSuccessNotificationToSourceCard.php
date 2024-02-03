<?php

namespace App\Listeners;

use App\Events\SuccessTransaction;
use App\Notifications\CardToCardDecreaseBalanceNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendSuccessNotificationToSourceCard
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
    public function handle(SuccessTransaction $event): void
    {
        $event->transfer->source_card->account->user->notify(new CardToCardDecreaseBalanceNotification($event->transfer));
    }
}
