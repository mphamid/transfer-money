<?php

namespace App\Listeners;

use App\Events\SuccessTransaction;
use App\Notifications\CardToCardIncreaseBalanceNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendSuccessNotificationToDestinationCard
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
        $event->transfer->destination_card->account->user->notify(new CardToCardIncreaseBalanceNotification($event->transfer));
    }
}
