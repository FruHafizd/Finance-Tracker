<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FinancialReminder;
use App\Models\User;
use Carbon\Carbon;

class FinancialReminderSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        if (!$user) {
            return;
        }

        $now = Carbon::now();

        // 5 data reminder yang realistis menyebar di bulan ini
        $reminders = [
            [
                'day' => 5,
                'month' => $now->month,
                'year' => $now->year,
                'category' => 'Tagihan',
                'description' => 'Bayar Tagihan Listrik & WiFi',
                'amount' => 450000,
                'remind_before' => 3,
            ],
            [
                'day' => 10,
                'month' => $now->month,
                'year' => $now->year,
                'category' => 'Tabungan',
                'description' => 'Tabungan Rencana Bulanan',
                'amount' => 1000000,
                'remind_before' => 1,
            ],
            [
                'day' => 15,
                'month' => $now->month,
                'year' => $now->year,
                'category' => 'Investasi',
                'description' => 'Investasi Reksadana & Saham',
                'amount' => 1500000,
                'remind_before' => 3,
            ],
            [
                'day' => 25,
                'month' => $now->month,
                'year' => $now->year,
                'category' => 'Tagihan',
                'description' => 'Cicilan KPR Rumah',
                'amount' => 2000000,
                'remind_before' => 7,
            ],
            [
                'day' => 28,
                'month' => $now->month,
                'year' => $now->year,
                'category' => 'Pemasukan',
                'description' => 'Prediksi Gaji Bulanan',
                'amount' => 8500000,
                'remind_before' => 0,
            ],
        ];

        foreach ($reminders as $reminder) {
            FinancialReminder::create(array_merge(['user_id' => $user->id], $reminder));
        }
    }
}
