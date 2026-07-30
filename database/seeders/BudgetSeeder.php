<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Budget;
use App\Models\Category;
use App\Models\User;
use Carbon\Carbon;

class BudgetSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        if (!$user) {
            return;
        }

        $categories = Category::where('user_id', $user->id)
            ->where('type', 'expense')
            ->get();

        $months = [
            Carbon::now(), // Bulan ini
            Carbon::now()->subMonth(), // Bulan lalu
            Carbon::now()->subMonths(2), // 2 bulan lalu
        ];

        // Definisikan limit realistis untuk setiap kategori
        $limits = [
            'Makanan' => 2000000,
            'Transportasi' => 800000,
            'Hiburan' => 1000000,
            'Belanja' => 1500000,
        ];

        foreach ($months as $date) {
            foreach ($categories as $category) {
                $limit = $limits[$category->name] ?? 500000;
                
                Budget::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'category_id' => $category->id,
                        'month' => (int) $date->format('n'),
                        'year' => (int) $date->format('Y'),
                    ],
                    [
                        'limit_amount' => $limit,
                    ]
                );
            }
        }
    }
}
