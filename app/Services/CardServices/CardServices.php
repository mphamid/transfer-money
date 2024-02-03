<?php

namespace App\Services\CardServices;

use App\Enums\TransferStatusEnum;
use App\Events\FailedTransaction;
use App\Events\SuccessTransaction;
use App\Models\Card;
use App\Models\Fee;
use App\Models\Transaction;
use App\Models\Transfer;
use App\Models\User;
use App\Services\CardServices\Exceptions\AmountExceededLimitationException;
use App\Services\CardServices\Exceptions\DestinationCardNotFoundException;
use App\Services\CardServices\Exceptions\InsufficientBalanceException;
use App\Services\CardServices\Exceptions\SourceAndDestinationIsSameException;
use App\Services\CardServices\Exceptions\SourceCardNotFoundException;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class CardServices implements CardRepository
{

    /**
     * @inheritDoc
     * @throws AmountExceededLimitationException
     * @throws DestinationCardNotFoundException
     * @throws InsufficientBalanceException
     * @throws SourceCardNotFoundException
     * @throws SourceAndDestinationIsSameException
     */
    public function cardToCard(string $sourceCard, string $destinationCard, int $amount): Transfer
    {
        if ($sourceCard == $destinationCard) {
            throw new SourceAndDestinationIsSameException();
        }
        $sourceCardRow = Card::query()->with('account')->where('number', $sourceCard)->first();
        if (is_null($sourceCardRow)) {
            throw new SourceCardNotFoundException();
        }
        if ($amount < config('card.minimum_amount') || $amount > config('card.maximum_amount')) {
            throw new AmountExceededLimitationException();
        }
        $sourceAmount = $amount + config('card.fee');
        if ($sourceCardRow->account->balance < $sourceAmount) {
            throw new InsufficientBalanceException();
        }
        $destinationCardRow = Card::query()->with('account')->where('number', $destinationCard)->first();
        if (is_null($destinationCardRow)) {
            throw new DestinationCardNotFoundException();
        }
        $transfer = Transfer::create([
            'track_number' => Str::ulid(),
            'source' => $sourceCardRow->id,
            'destination' => $destinationCardRow->id,
            'amount' => $amount,
            'status' => TransferStatusEnum::Init
        ]);
        try {
            DB::beginTransaction();
            $sourceCardRow->account->decrement('balance', $sourceAmount);
            $sourceCardRow->transactions()->create([
                'transfer_id' => $transfer->id,
                'amount' => ($sourceAmount * -1),
                'balance' => $sourceCardRow->account->refresh()->balance,
            ]);
            $transfer->fee()->create(['amount' => config('card.fee')]);
            $destinationCardRow->account->increment('balance', $sourceAmount);
            $destinationCardRow->transactions()->create([
                'transfer_id' => $transfer->id,
                'amount' => $amount,
                'balance' => $destinationCardRow->account->refresh()->balance,
            ]);
            $transfer->status = TransferStatusEnum::Success;
            DB::commit();
            SuccessTransaction::dispatch($transfer);
        } catch (Throwable $exception) {
            DB::rollBack();
            $transfer->status = TransferStatusEnum::Failed;
            FailedTransaction::dispatch($transfer);
        }
        $transfer->save();

        return $transfer;
    }

    /**
     * @inheritDoc
     */
    public function MostTransactionUser(int $userNumber = 3, int $transactionNumber = 10)
    {
        return User::query()->select([DB::raw('count(`transactions`.`id`) as `number_of_transaction`'), 'users.id'])
            ->join('accounts', 'accounts.user_id', '=', 'users.id')
            ->join('cards', 'cards.account_id', '=', 'accounts.id')
            ->join('transactions', 'transactions.card_id', '=', 'cards.id')
            ->where('transactions.created_at', '>', Carbon::now()->subHours(10))
            ->groupBy('accounts.user_id')
            ->orderBy('number_of_transaction', 'DESC')
            ->limit($userNumber)
            ->get()->each(function (User $user) use ($transactionNumber) {
                $cards = Card::whereIn('account_id', $user->accounts()->get()->pluck('id'))->get();
                $user->transactions = Transaction::whereIn('card_id', $cards->pluck('id'))
                    ->orderBy('created_at', 'DESC')->limit($transactionNumber)->get();
                return $user;
            });
    }
}
