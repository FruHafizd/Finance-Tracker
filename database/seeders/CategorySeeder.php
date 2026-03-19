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
            ["name" => "Gaji", "color" => "#22c55e"],
            ["name" => "Makanan", "color" => "#f97316"],
            ["name" => "Transportasi", "color" => "#3b82f6"],
            ["name" => "Hiburan", "color" => "#a855f7"],
            ["name" => "Belanja", "color" => "#ef4444"],
        ];

        foreach ($categories as $cat) {
            Category::create([
                "user_id" => $user->id,
                "name" => $cat["name"],
                "color" => $cat["color"],
            ]);
        }
    }
}
