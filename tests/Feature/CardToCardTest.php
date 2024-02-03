<?php

namespace Tests\Feature;

use App\Enums\TransferStatusEnum;
use App\Models\Account;
use App\Models\Card;
use App\Models\Fee;
use App\Models\Transaction;
use App\Models\Transfer;
use Illuminate\Support\Facades\Notification;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CardToCardTest extends TestCase
{
    public function testCardToCardSuccessfully(): void
    {
        $fee = config('card.fee');
        $sourceCard = Card::factory()
            ->for(Account::factory()->state(['balance' => $firstSourceBalance = 100000000])->create())
            ->create();
        $destinationCard = Card::factory()
            ->for(Account::factory()->state(['balance' => $firstDestinationBalance = 100000])->create())
            ->create();
        $response = $this->postJson(route('cards.transfer'), [
            'source' => $sourceCard->number,
            'destination' => $destinationCard->number,
            'amount' => $amount = 10000,
        ]);
        $response->assertSuccessful();
        $response->assertJson(
            fn(AssertableJson $json) => $json
                ->has('status')
                ->has('track_number')
                ->etc()
        );
        $this->assertDatabaseHas(Transfer::class, [
            'source' => $sourceCard->id,
            'destination' => $destinationCard->id,
            'amount' => $amount,
            'status' => TransferStatusEnum::Success->value,
        ]);
        $this->assertDatabaseHas(Transaction::class, [
            'card_id' => $sourceCard->id,
            'amount' => ($amount + $fee) * -1,
        ]);
        $this->assertDatabaseHas(Transaction::class, [
            'card_id' => $destinationCard->id,
            'amount' => $amount,
        ]);
        $this->assertDatabaseCount(Fee::class, 1);
        Notification::assertCount(2);
    }

    public function testCardToCardFailedOnInvalidCardNumber(): void
    {
        $response = $this->postJson(route('cards.transfer'), [
            'source' => '1234123412341234',
            'destination' => '4321432143214321',
            'amount' => 10000,
        ]);

        $response->assertUnprocessable();
        Notification::assertCount(0);
    }

    public function testCardToCardFailedOnSameCardNumber(): void
    {
        $sourceCard = Card::factory()->create();
        $response = $this->postJson(route('cards.transfer'), [
            'source' => $sourceCard->number,
            'destination' => $sourceCard->number,
            'amount' => config('card.minimum_amount'),
        ]);
        $response->assertUnprocessable();
        Notification::assertCount(0);
    }

    public function testCardToCardFailedOnInvalidAmount(): void
    {
        Notification::fake();
        $sourceCard = Card::factory()
            ->for(Account::factory()->state(['balance' => $firstSourceBalance = 100000000])->create())
            ->create();
        $destinationCard = Card::factory()
            ->for(Account::factory()->state(['balance' => $firstDestinationBalance = 100000])->create())
            ->create();
        $response = $this->postJson(route('cards.transfer'), [
            'source' => $sourceCard->number,
            'destination' => $destinationCard->number,
            'amount' => 10,
        ]);
        $response->assertUnprocessable();
        $this->assertDatabaseCount(Transaction::class, 0);
        Notification::assertCount(0);
    }

    public function testCardToCardFailedOnBalanceInsufficient(): void
    {
        Notification::fake();
        $sourceCard = Card::factory()
            ->for(Account::factory()->state(['balance' => $firstSourceBalance = 50000])->create())
            ->create();

        $destinationCard = Card::factory()
            ->for(Account::factory()->state(['balance' => $firstDestinationBalance = 100000])->create())
            ->create();

        $response = $this->postJson(route('cards.transfer'), [
            'source' => $sourceCard->number,
            'destination' => $destinationCard->number,
            'amount' => 5000000,
        ]);
        $response->assertStatus(400);

    }
}
