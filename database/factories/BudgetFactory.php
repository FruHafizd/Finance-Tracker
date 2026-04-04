<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BudgetFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'limit_amount' => fake()->numberBetween(500000, 5000000),
            'month' => (int) now()->format('n'),
            'year' => (int) now()->format('Y'),
        ];
    }
}
