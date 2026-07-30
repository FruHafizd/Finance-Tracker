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
        if (!$user) {
            return;
        }

        $categories = Category::where('user_id', $user->id)->pluck('id', 'name');
        $accounts = Account::where('user_id', $user->id)->pluck('id', 'name');

        $now = Carbon::now();

        // 30 data transaksi yang sangat realistis (distribusi waktu menyebar 2 bulan terakhir)
        $transactions = [
            // Hari ini / Kemarin
            ["name" => "Makan Siang Sederhana", "type" => "expense", "amount" => 35000, "date" => $now->copy(), "category" => "Makanan", "account" => "Tunai"],
            ["name" => "Kopi Susu Senja", "type" => "expense", "amount" => 22000, "date" => $now->copy()->subHours(5), "category" => "Makanan", "account" => "GoPay"],
            ["name" => "Bensin Motor Bulanan", "type" => "expense", "amount" => 50000, "date" => $now->copy()->subDay(), "category" => "Transportasi", "account" => "Tunai"],
            
            // Minggu ini
            ["name" => "Makan Malam bersama Teman", "type" => "expense", "amount" => 125000, "date" => $now->copy()->subDays(3), "category" => "Makanan", "account" => "GoPay"],
            ["name" => "Ojek Online ke Kantor", "type" => "expense", "amount" => 18000, "date" => $now->copy()->subDays(4), "category" => "Transportasi", "account" => "OVO"],
            ["name" => "Belanja Bulanan Supermarket", "type" => "expense", "amount" => 450000, "date" => $now->copy()->subDays(5), "category" => "Belanja", "account" => "BCA Utama"],
            
            // Minggu lalu
            ["name" => "Nonton Bioskop + Popcorn", "type" => "expense", "amount" => 75000, "date" => $now->copy()->subDays(8), "category" => "Hiburan", "account" => "OVO"],
            ["name" => "Topup Netflix Bulanan", "type" => "expense", "amount" => 186000, "date" => $now->copy()->subDays(10), "category" => "Hiburan", "account" => "Mandiri"],
            ["name" => "Gaji Bulanan Utama", "type" => "income", "amount" => 8500000, "date" => $now->copy()->subDays(12), "category" => "Gaji", "account" => "BCA Utama"],
            ["name" => "Makan Steak Mewah", "type" => "expense", "amount" => 250000, "date" => $now->copy()->subDays(14), "category" => "Makanan", "account" => "BCA Utama"],
            
            // Bulan ini (lebih lama)
            ["name" => "Pekerjaan Sampingan (Freelance)", "type" => "income", "amount" => 1500000, "date" => $now->copy()->subDays(18), "category" => "Gaji", "account" => "Mandiri"],
            ["name" => "Beli Kaos Baru", "type" => "expense", "amount" => 120000, "date" => $now->copy()->subDays(20), "category" => "Belanja", "account" => "GoPay"],
            ["name" => "Ojek Online Hujan", "type" => "expense", "amount" => 25000, "date" => $now->copy()->subDays(22), "category" => "Transportasi", "account" => "OVO"],
            
            // Bulan lalu (subMonth)
            ["name" => "Gaji Bulanan Utama", "type" => "income", "amount" => 8500000, "date" => $now->copy()->subMonth()->setDay(25), "category" => "Gaji", "account" => "BCA Utama"],
            ["name" => "Bayar Tagihan Listrik", "type" => "expense", "amount" => 320000, "date" => $now->copy()->subMonth()->setDay(5), "category" => "Belanja", "account" => "Mandiri"],
            ["name" => "Makan Siang Padang", "type" => "expense", "amount" => 28000, "date" => $now->copy()->subMonth()->setDay(8), "category" => "Makanan", "account" => "Tunai"],
            ["name" => "Ojek Online Meeting", "type" => "expense", "amount" => 35000, "date" => $now->copy()->subMonth()->setDay(12), "category" => "Transportasi", "account" => "OVO"],
            ["name" => "Beli Buku Bacaan", "type" => "expense", "amount" => 95000, "date" => $now->copy()->subMonth()->setDay(15), "category" => "Belanja", "account" => "BCA Utama"],
            ["name" => "Subsidi Parkir Kantor", "type" => "income", "amount" => 100000, "date" => $now->copy()->subMonth()->setDay(20), "category" => "Gaji", "account" => "Tunai"],
            ["name" => "Ngopi Starbucks", "type" => "expense", "amount" => 60000, "date" => $now->copy()->subMonth()->setDay(22), "category" => "Makanan", "account" => "GoPay"],
            
            // 2 Bulan lalu
            ["name" => "Gaji Bulanan Utama", "type" => "income", "amount" => 8500000, "date" => $now->copy()->subMonths(2)->setDay(25), "category" => "Gaji", "account" => "BCA Utama"],
            ["name" => "Beli Sepatu Olahraga", "type" => "expense", "amount" => 550000, "date" => $now->copy()->subMonths(2)->setDay(10), "category" => "Belanja", "account" => "BCA Utama"],
            ["name" => "Ganti Oli Motor", "type" => "expense", "amount" => 85000, "date" => $now->copy()->subMonths(2)->setDay(12), "category" => "Transportasi", "account" => "Tunai"],
            ["name" => "Makan Bakso Lapangan", "type" => "expense", "amount" => 20000, "date" => $now->copy()->subMonths(2)->setDay(15), "category" => "Makanan", "account" => "Tunai"],
            ["name" => "Beli Pulsa & Kuota Data", "type" => "expense", "amount" => 150000, "date" => $now->copy()->subMonths(2)->setDay(18), "category" => "Belanja", "account" => "OVO"],
            ["name" => "Jasa Desain Logo", "type" => "income", "amount" => 800000, "date" => $now->copy()->subMonths(2)->setDay(22), "category" => "Gaji", "account" => "Mandiri"],
        ];

        foreach ($transactions as $trx) {
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