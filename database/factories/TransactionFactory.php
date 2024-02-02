<?php

namespace Database\Factories;

use App\Models\Card;
use App\Models\Transfer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Throwable;

/**
 * @extends Factory<User>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     * @throws Throwable
     */
    public function definition(): array
    {
        return [
            'transfer_id' => Transfer::factory(),
            'card_id' => Card::factory(),
            'amount' => $this->faker->biasedNumberBetween(config('card.minimum_amount'), config('card.maximum_amount')),
            'balance' => 0,
        ];
    }
}
