<?php

namespace Database\Seeders;

use App\Enums\TransferStatusEnum;
use App\Models\Account;
use App\Models\Card;
use App\Models\Fee;
use App\Models\Transaction;
use App\Models\Transfer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $cardToCardFee = config('card.fee');

        $destinationCard1 = Card::factory()->create();
        $destinationCard2 = Card::factory()->create();
        $destinationCard3 = Card::factory()->create();
        $destinationCard4 = Card::factory()->create();
        $destinationCard5 = Card::factory()->create();

        $destinationCards = [
            $destinationCard1,
            $destinationCard2,
            $destinationCard3,
            $destinationCard4,
            $destinationCard5,
        ];
        User::factory(5)->create()
            ->each(fn(User $user) => Account::factory(2)
                ->create(['user_id' => $user->id, 'balance' => 1000000])
                ->each(fn(Account $account) => Card::factory(rand(1, 3))
                    ->create(['account_id' => $account->id])
                    ->each(fn(Card $card) => Transfer::factory(10)->create([
                        'track_number' => Str::ulid(),
                        'source' => $card->id,
                        'destination' => $destinationCard = $destinationCards[random_int(0, 4)],
                        'amount' => random_int(5000, 10000),
                        'status' => TransferStatusEnum::Success,
                    ])
                        ->each(function (Transfer $transfer) use ($cardToCardFee, $card) {
                            $amount = ($transfer->amount + $cardToCardFee);
                            $card->account->refresh()->decrement('balance', $amount);
                            Transaction::factory(1)->create([
                                'transfer_id' => $transfer->id,
                                'card_id' => $card->id,
                                'amount' => ($amount * -1),
                                'balance' => $card->account->refresh()->balance
                            ]);
                        })
                        ->each(function (Transfer $transfer) use ($cardToCardFee, $destinationCard) {
                            $destinationCard->account->refresh()->increment('balance', $transfer->amount);
                            Transaction::factory(1)->create([
                                'transfer_id' => $transfer->id,
                                'card_id' => $destinationCard->id,
                                'amount' => $transfer->amount,
                                'balance' => $destinationCard->account->refresh()->balance
                            ]);
                        })
                        ->each(fn(Transfer $transfer) => Fee::factory(1)
                            ->create([
                                'transfer_id' => $transfer->id,
                                'amount' => $cardToCardFee
                            ])
                        )
                    )
                )
            );
    }
}
