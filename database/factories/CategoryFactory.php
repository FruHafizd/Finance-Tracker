<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name'    => fake()->randomElement(['Makanan', 'Transportasi', 'Hiburan', 'Belanja', 'Gaji', 'Kesehatan']),
            'color'   => fake()->hexColor(),
        ];
    }
}