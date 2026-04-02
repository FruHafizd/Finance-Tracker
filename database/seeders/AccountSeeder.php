<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;
use App\Models\User;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        $accounts = [
            [
                'name'       => 'BCA Utama',
                'type'       => 'tabungan',
                'provider'   => 'BCA',
                'balance'    => 8500000,
                'color'      => '#0060AF',
                'sort_order' => 1,
            ],
            [
                'name'       => 'Mandiri',
                'type'       => 'tabungan',
                'provider'   => 'Mandiri',
                'balance'    => 3200000,
                'color'      => '#003D7C',
                'sort_order' => 2,
            ],
            [
                'name'       => 'GoPay',
                'type'       => 'ewallet',
                'provider'   => 'GoPay',
                'balance'    => 450000,
                'color'      => '#00AED6',
                'sort_order' => 3,
            ],
            [
                'name'       => 'OVO',
                'type'       => 'ewallet',
                'provider'   => 'OVO',
                'balance'    => 125000,
                'color'      => '#4C3494',
                'sort_order' => 4,
            ],
            [
                'name'       => 'Tunai',
                'type'       => 'tunai',
                'provider'   => null,
                'balance'    => 750000,
                'color'      => '#2E7D32',
                'sort_order' => 5,
            ],
        ];

        foreach ($accounts as $account) {
            Account::create([
                'user_id' => $user->id,
                ...$account,
            ]);
        }
    }
}