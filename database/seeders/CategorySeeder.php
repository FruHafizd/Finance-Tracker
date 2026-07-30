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
        $users = User::all();
        $categoryService = app(\App\Services\CategoryService::class);

        foreach ($users as $user) {
            $categoryService->seedDefaultCategories($user->id);
        }
    }
}
