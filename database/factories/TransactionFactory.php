<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'     => User::factory(),
            'account_id'  => function (array $attributes) {
                return \App\Models\Account::factory()->create([
                    'user_id' => $attributes['user_id'],
                ])->id;
            },
            'category_id' => function (array $attributes) {
                return \App\Models\Category::factory()->create([
                    'user_id' => $attributes['user_id'],
                ])->id;
            },
            'name'        => fake()->words(3, true),
            'amount'      => fake()->numberBetween(10_000, 5_000_000),
            'type'        => fake()->randomElement(['income', 'expense']),
            'date'        => now()->format('Y-m-d'),
        ];
    }

    public function income(): static
    {
        return $this->state(['type' => 'income']);
    }

    public function expense(): static
    {
        return $this->state(['type' => 'expense']);
    }

    public function forDate(string $date): static
    {
        return $this->state(['date' => $date]);
    }
}