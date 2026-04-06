<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->word() . ' Account',
            'type' => fake()->randomElement(['tabungan', 'ewallet', 'tunai']),
            'provider' => fake()->company(),
            'balance' => fake()->numberBetween(100000, 10000000),
            'color' => fake()->hexColor(),
            'sort_order' => 0,
        ];
    }
}
