<?php

namespace Database\Factories;

use App\Enums\TransferStatusEnum;
use App\Models\Card;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Throwable;

/**
 * @extends Factory<User>
 */
class TransferFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     * @throws Throwable
     */
    public function definition(): array
    {
        $failed = $this->faker->boolean(90);

        return [
            'track_number' => Str::ulid(),
            'source' => Card::factory(),
            'destination' => Card::factory(),
            'amount' => $this->faker->biasedNumberBetween(config('card.minimum_amount'), config('card.maximum_amount')),
            'status' => $failed ? TransferStatusEnum::Failed : TransferStatusEnum::Success
        ];
    }
}
