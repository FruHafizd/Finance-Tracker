<?php

namespace App\Services;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MonthInReviewService
{
    /**
     * Get review data for the previous month.
     *
     * @param int $userId
     * @return array
     */
    public function getReviewData($userId)
    {
        $lastMonth = Carbon::now()->subMonth();
        $startOfLastMonth = $lastMonth->copy()->startOfMonth();
        $endOfLastMonth = $lastMonth->copy()->endOfMonth();

        // Base query for last month transactions
        $query = Transaction::withoutGlobalScopes()
            ->where('user_id', $userId)
            ->whereBetween('date', [$startOfLastMonth, $endOfLastMonth]);

        $income = (clone $query)->where('type', 'income')->sum('amount');
        $expense = (clone $query)->where('type', 'expense')->sum('amount');
        
        $topCategory = (clone $query)
            ->where('type', 'expense')
            ->select('category_id', DB::raw('SUM(amount) as total_amount'))
            ->groupBy('category_id')
            ->orderByDesc('total_amount')
            ->with('category')
            ->first();

        $netSaving = $income - $expense;

        return [
            'month_name' => $lastMonth->translatedFormat('F'),
            'month_year' => $lastMonth->format('Y-m'),
            'total_income' => $income,
            'total_expense' => $expense,
            'net_saving' => $netSaving,
            'top_category_name' => $topCategory?->category?->name ?? 'Lain-lain',
            'analogy' => $this->getAnalogy($netSaving),
        ];
    }

    /**
     * Provide a fun analogy for the net savings.
     *
     * @param float $netSaving
     * @return string
     */
    private function getAnalogy($netSaving)
    {
        if ($netSaving < 0) {
            return "Waduh, pengeluaranmu lebih besar dari pemasukan. Kurangi jajan boba dulu ya!";
        }

        if ($netSaving <= 50000) {
            return "Lumayan, bisa buat beli Nasi Padang pakai telur 2 bungkus!";
        }

        if ($netSaving <= 150000) {
            return "Asyik, uang segini cukup buat makan All You Can Eat promo berdua!";
        }

        if ($netSaving <= 300000) {
            return "Mantap! Kamu berhasil menghemat setara 1 karung beras 10kg!";
        }

        if ($netSaving <= 1000000) {
            return "Luar biasa! Tabunganmu setara dengan cicilan HP baru kelas menengah!";
        }

        return "Sultan mah bebas! Tabunganmu sangat sehat bulan ini, teruskan!";
    }
}
