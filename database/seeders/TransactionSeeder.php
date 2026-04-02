<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\Category;
use App\Models\Account;
use App\Models\User;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        $categories = Category::where('user_id', $user->id)
            ->pluck('id', 'name');

        // ambil account berdasarkan nama
        $accounts = Account::where('user_id', $user->id)
            ->pluck('id', 'name');

        $now = Carbon::now();

        $transactions = [
            ["name" => "Gaji Bulanan",    "type" => "income",  "amount" => 5000000, "date" => $now->copy()->startOfMonth(),             "category" => "Gaji",          "account" => "BCA Utama"],
            ["name" => "Bonus Freelance", "type" => "income",  "amount" => 1500000, "date" => $now->copy()->startOfMonth()->addDays(9),  "category" => "Gaji",          "account" => "BCA Utama"],

            ["name" => "Makan Siang",     "type" => "expense", "amount" => 25000,   "date" => $now->copy()->startOfMonth()->addDays(1),  "category" => "Makanan",       "account" => "GoPay"],
            ["name" => "Ngopi",           "type" => "expense", "amount" => 20000,   "date" => $now->copy()->startOfMonth()->addDays(2),  "category" => "Makanan",       "account" => "GoPay"],

            ["name" => "Ojek Online",     "type" => "expense", "amount" => 15000,   "date" => $now->copy()->startOfMonth()->addDays(3),  "category" => "Transportasi",  "account" => "OVO"],
            ["name" => "Uang Transport",  "type" => "income",  "amount" => 100000,  "date" => $now->copy()->startOfMonth()->addDays(4),  "category" => "Transportasi",  "account" => "Tunai"],

            ["name" => "Nonton Bioskop",  "type" => "expense", "amount" => 50000,   "date" => $now->copy()->startOfMonth()->addDays(5),  "category" => "Hiburan",       "account" => "OVO"],
            ["name" => "Hadiah Event",    "type" => "income",  "amount" => 200000,  "date" => $now->copy()->startOfMonth()->addDays(6),  "category" => "Hiburan",       "account" => "Tunai"],

            ["name" => "Beli Baju",       "type" => "expense", "amount" => 150000,  "date" => $now->copy()->startOfMonth()->addDays(7),  "category" => "Belanja",       "account" => "Mandiri"],
            ["name" => "Refund Barang",   "type" => "income",  "amount" => 100000,  "date" => $now->copy()->startOfMonth()->addDays(8),  "category" => "Belanja",       "account" => "Mandiri"],
        ];

        $previousMonth = Carbon::now()->subMonth();

        $previousMonthTransactions = [
            ["name" => "Gaji Bulanan",    "type" => "income",  "amount" => 4800000, "date" => $previousMonth->copy()->startOfMonth(),             "category" => "Gaji",         "account" => "BCA Utama"],
            ["name" => "Bonus Freelance", "type" => "income",  "amount" => 1000000, "date" => $previousMonth->copy()->startOfMonth()->addDays(11), "category" => "Gaji",         "account" => "BCA Utama"],

            ["name" => "Makan Siang",     "type" => "expense", "amount" => 20000,   "date" => $previousMonth->copy()->startOfMonth()->addDays(1),  "category" => "Makanan",      "account" => "GoPay"],
            ["name" => "Ngopi",           "type" => "expense", "amount" => 15000,   "date" => $previousMonth->copy()->startOfMonth()->addDays(2),  "category" => "Makanan",      "account" => "GoPay"],

            ["name" => "Ojek Online",     "type" => "expense", "amount" => 12000,   "date" => $previousMonth->copy()->startOfMonth()->addDays(3),  "category" => "Transportasi", "account" => "OVO"],
            ["name" => "Uang Transport",  "type" => "income",  "amount" => 80000,   "date" => $previousMonth->copy()->startOfMonth()->addDays(4),  "category" => "Transportasi", "account" => "Tunai"],

            ["name" => "Nonton Bioskop",  "type" => "expense", "amount" => 45000,   "date" => $previousMonth->copy()->startOfMonth()->addDays(5),  "category" => "Hiburan",      "account" => "OVO"],
            ["name" => "Hadiah Event",    "type" => "income",  "amount" => 150000,  "date" => $previousMonth->copy()->startOfMonth()->addDays(6),  "category" => "Hiburan",      "account" => "Tunai"],

            ["name" => "Beli Baju",       "type" => "expense", "amount" => 120000,  "date" => $previousMonth->copy()->startOfMonth()->addDays(7),  "category" => "Belanja",      "account" => "Mandiri"],
            ["name" => "Refund Barang",   "type" => "income",  "amount" => 80000,   "date" => $previousMonth->copy()->startOfMonth()->addDays(8),  "category" => "Belanja",      "account" => "Mandiri"],
        ];

        $allTransactions = array_merge($transactions, $previousMonthTransactions);

        foreach ($allTransactions as $trx) {
            Transaction::create([
                'user_id'     => $user->id,
                'name'        => $trx['name'],
                'type'        => $trx['type'],
                'amount'      => $trx['amount'],
                'date'        => $trx['date'],
                'category_id' => $categories[$trx['category']] ?? null,
                'account_id'  => $accounts[$trx['account']] ?? null,
            ]);
        }
    }
}