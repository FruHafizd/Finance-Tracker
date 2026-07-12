<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        $categories = [
            ["name" => "Gaji", "color" => "#22c55e", "type" => "income"],
            ["name" => "Makanan", "color" => "#f97316", "type" => "expense"],
            ["name" => "Transportasi", "color" => "#3b82f6", "type" => "expense"],
            ["name" => "Hiburan", "color" => "#a855f7", "type" => "expense"],
            ["name" => "Belanja", "color" => "#ef4444", "type" => "expense"],
        ];

        foreach ($categories as $cat) {
            Category::create([
                "user_id" => $user->id,
                "name" => $cat["name"],
                "color" => $cat["color"],
                "type" => $cat["type"],
            ]);
        }
    }
}
