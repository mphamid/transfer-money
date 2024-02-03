<?php

namespace App\Notifications;

use App\Models\Transaction;
use App\Models\Transfer;
use App\Models\User;
use App\Notifications\Channels\SmsChannel;
use App\Notifications\Contracts\SMSNotificationInterface;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class CardToCardIncreaseBalanceNotification extends Notification implements ShouldQueue, SMSNotificationInterface
{
    use Queueable;

    private Transaction $deposit;

    private Transaction $withdraw;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Transfer $transfer)
    {

    }


    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(User $notifiable): array
    {
        return [SmsChannel::class];
    }

    public function toSms(): string
    {
        $destinationTransaction = $this->transfer->destination_transaction()->first();
        return __('notification.transfer.success.destination', [
            'amount' => $destinationTransaction->amount,
            'balance' => $destinationTransaction->balance,
            'card_number' => $this->transfer->destination_card->number
        ]);
    }

    public function viaQueues(): array
    {
        return [
            SmsChannel::class => 'sms-queue'
        ];
    }

    public function failed(Exception $exception): void
    {
        Log::critical('Card To Card Increase Balance Notification Failed', [
            'exception' => get_class($exception),
            'exception_message' => $exception->getMessage(),
            'transfer_id' => $this->transfer->id,
        ]);
    }
}
