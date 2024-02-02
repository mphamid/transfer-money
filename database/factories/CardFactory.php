<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Throwable;

/**
 * @extends Factory<User>
 */
class CardFactory extends Factory
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
            'account_id' => Account::factory(),
            'number' => $this->faker->unique()->creditCardNumber('Visa'),
        ];
    }
}
