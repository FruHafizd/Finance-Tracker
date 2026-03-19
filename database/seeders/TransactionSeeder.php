<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\Category;
use App\Models\User;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        // ambil category berdasarkan nama
        $categories = Category::where('user_id', $user->id)
            ->pluck('id', 'name');

        $transactions = [
            ["name" => "Gaji Bulanan", "type" => "income", "amount" => 5000000, "date" => "2026-03-01", "category" => "Gaji"],
            ["name" => "Bonus Freelance", "type" => "income", "amount" => 1500000, "date" => "2026-03-10", "category" => "Gaji"],

            ["name" => "Makan Siang", "type" => "expense", "amount" => 25000, "date" => "2026-03-02", "category" => "Makanan"],
            ["name" => "Ngopi", "type" => "expense", "amount" => 20000, "date" => "2026-03-03", "category" => "Makanan"],

            ["name" => "Ojek Online", "type" => "expense", "amount" => 15000, "date" => "2026-03-04", "category" => "Transportasi"],
            ["name" => "Uang Transport", "type" => "income", "amount" => 100000, "date" => "2026-03-05", "category" => "Transportasi"],

            ["name" => "Nonton Bioskop", "type" => "expense", "amount" => 50000, "date" => "2026-03-06", "category" => "Hiburan"],
            ["name" => "Hadiah Event", "type" => "income", "amount" => 200000, "date" => "2026-03-07", "category" => "Hiburan"],

            ["name" => "Beli Baju", "type" => "expense", "amount" => 150000, "date" => "2026-03-08", "category" => "Belanja"],
            ["name" => "Refund Barang", "type" => "income", "amount" => 100000, "date" => "2026-03-09", "category" => "Belanja"],
        ];

        foreach ($transactions as $trx) {
            Transaction::create([
                "user_id" => $user->id,
                "name" => $trx["name"],
                "type" => $trx["type"],
                "amount" => $trx["amount"],
                "date" => $trx["date"],
                "category_id" => $categories[$trx["category"]] ?? null,
            ]);
        }
    }
}