<?php

namespace App\Services;

use App\Models\Category;
use App\Models\FavoriteTransaction;
use App\Models\Transaction;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class TransactionService
{
    /* ------------------------------------------------------------------
     |  Query Builder
     | ------------------------------------------------------------------ */

    /**
     * Bangun query transaksi berdasarkan filter yang diberikan.
     *
     * @param array{
     *     year?:     string|int,
     *     month?:    string|int,
     *     startDate?: string,
     *     endDate?:   string,
     *     type?:      string,
     *     category?:  string|int,
     * } $filters
     */
    public function buildFilteredQuery(array $filters): Builder
    {
        $query = Transaction::with('category');

        // Range tanggal → abaikan filter year & month
        $startDate = $filters['startDate'] ?? '';
        $endDate   = $filters['endDate']   ?? '';

        if ($startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('date', '>=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('date', '<=', $endDate);
        } else {
            $year  = $filters['year']  ?? '';
            $month = $filters['month'] ?? '';

            if ($year)  $query->whereYear('date', $year);
            if ($month) $query->whereMonth('date', $month);
        }

        $type     = $filters['type']     ?? '';
        $category = $filters['category'] ?? '';

        if ($type)     $query->where('type', $type);
        if ($category) $query->where('category_id', $category);

        return $query;
    }

    /* ------------------------------------------------------------------
     |  Summary
     | ------------------------------------------------------------------ */

    /**
     * Hitung ringkasan pemasukan, pengeluaran, dan selisih.
     *
     * @return array{income: float, expense: float, difference: float}
     */
    public function getSummary(array $filters): array
    {
        $data = $this->buildFilteredQuery($filters)
            ->selectRaw("
                SUM(CASE WHEN type = 'income'  THEN amount ELSE 0 END) as income,
                SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expense
            ")
            ->first();

        $income  = (float) ($data->income  ?? 0);
        $expense = (float) ($data->expense ?? 0);

        return [
            'income'     => $income,
            'expense'    => $expense,
            'difference' => $income - $expense,
        ];
    }

    /* ------------------------------------------------------------------
     |  Categories
     | ------------------------------------------------------------------ */

    /**
     * Ambil daftar kategori milik user yang sedang login.
     */
    public function getCategories(): Collection
    {
        return Category::orderBy('name')->get();
    }

    /* ------------------------------------------------------------------
     |  Paginated + Grouped Transactions
     | ------------------------------------------------------------------ */

    /**
     * Ambil transaksi dengan pagination dan grouping per tanggal.
     *
     * @return array{transactions: LengthAwarePaginator, grouped: Collection}
     */
    public function getPaginatedTransactions(array $filters, int $perPage = 10): array
    {
        $transactions = $this->buildFilteredQuery($filters)
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $grouped = $transactions->getCollection()
            ->groupBy(fn($item) => $item->date->format('Y-m-d'));

        return [
            'transactions' => $transactions,
            'grouped'      => $grouped,
        ];
    }

    /* ------------------------------------------------------------------
     |  Favorite
     | ------------------------------------------------------------------ */

    /**
     * Tambahkan transaksi ke daftar favorit (Transaksi Cepat).
     *
     * @return string 'created' jika baru dibuat, 'exists' jika sudah ada.
     */
    public function addToFavorite(int $transactionId): string
    {
        $trx = Transaction::findOrFail($transactionId);

        $fav = FavoriteTransaction::firstOrCreate(
            [
                'user_id' => auth()->id(),
                'name'    => $trx->name,
                'amount'  => $trx->amount,
                'type'    => $trx->type,
            ],
            [
                'category_id' => $trx->category_id,
                'account_id'  => $trx->account_id,
            ]
        );

        return $fav->wasRecentlyCreated ? 'created' : 'exists';
    }

    /* ------------------------------------------------------------------
     |  Delete
     | ------------------------------------------------------------------ */

    /**
     * Hapus transaksi berdasarkan ID.
     */
    public function deleteTransaction(int $id): void
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();
    }
}
