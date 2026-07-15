<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Category;
use App\Models\FavoriteTransaction;
use App\Models\Transaction;
use App\Models\User;
use App\Repositories\BudgetRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\FavoriteTransactionRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class TransactionService
{
    public function __construct(
        protected TransactionRepository $repository,
        protected FavoriteTransactionRepository $favoriteRepository,
        protected BudgetRepository $budgetRepository,
    ) {}

    /* ==================================================================
     |  CRUD — reusable dari web form, API, maupun Telegram bot
     | ================================================================== */

    /**
     * Buat transaksi baru.
     *
     * Menerima array data yang sudah tervalidasi (gunakan
     * StoreTransactionRequest::rules() untuk validasi).
     * Saldo rekening akan otomatis terupdate via TransactionObserver.
     */
    public function createTransaction(array $data, User $user): Transaction
    {
        $data['user_id'] = $user->id;
        $data['to_account_id'] = ($data['type'] ?? '') === 'transfer'
            ? ($data['to_account_id'] ?? null)
            : null;

        // Generate default name jika kosong
        if (empty($data['name'])) {
            $data['name'] = $this->generateDefaultName($data['type'] ?? 'Transaksi');
        }

        return Transaction::create($data);
    }

    /**
     * Update transaksi yang sudah ada.
     *
     * TransactionObserver akan otomatis reverse saldo lama
     * dan apply saldo baru.
     */
    public function updateTransaction(Transaction $transaction, array $data): Transaction
    {
        $data['to_account_id'] = ($data['type'] ?? $transaction->type) === 'transfer'
            ? ($data['to_account_id'] ?? null)
            : null;

        if (empty($data['name'])) {
            $data['name'] = $this->generateDefaultName($data['type'] ?? $transaction->type);
        }

        $transaction->update($data);

        return $transaction->fresh();
    }

    /* ==================================================================
     |  Smart Defaults — untuk prefill form / bot
     | ================================================================== */

    /**
     * Ambil kategori yang difilter berdasarkan jenis transaksi.
     *
     * Kategori yang sering dipakai untuk tipe tersebut ditampilkan duluan.
     * Jika belum ada riwayat, tampilkan semua.
     */
    public function getFilteredCategories(int $userId, string $transactionType): Collection
    {
        return $this->repository->getCategoriesForType($userId, $transactionType);
    }

    /**
     * Ambil rekening terakhir yang dipakai user.
     */
    public function getLastUsedAccount(User $user): ?Account
    {
        return $this->repository->getLastUsedAccount($user);
    }

    /**
     * Ambil kategori terakhir yang dipakai untuk jenis transaksi tertentu.
     */
    public function getLastUsedCategory(User $user, string $transactionType): ?Category
    {
        return $this->repository->getLastUsedCategory($user, $transactionType);
    }

    /* ==================================================================
     |  Budget Alert — cek apakah pengeluaran melebihi budget
     | ================================================================== */

    /**
     * Cek apakah transaksi expense melampaui budget.
     *
     * @return array|null  null jika tidak ada alert, atau array
     *                     ['type' => 'danger'|'warning', 'title' => ..., 'message' => ...]
     */
    public function checkBudgetAlert(int $userId, int $categoryId): ?array
    {
        $budget = $this->budgetRepository->findForCategoryInMonth(
            $userId,
            $categoryId,
            (int) now()->format('n'),
            (int) now()->format('Y')
        );

        if (! $budget) {
            return null;
        }

        $spent      = $budget->spentAmount();
        $percentage = $budget->limit_amount > 0
            ? ($spent / $budget->limit_amount) * 100
            : 0;

        $categoryName = $budget->category->name;
        $sisa         = max($budget->limit_amount - $spent, 0);
        $sisaFormat   = 'Rp ' . number_format($sisa, 0, ',', '.');

        if ($percentage >= 100) {
            return [
                'type'    => 'danger',
                'title'   => 'Aduh, kebablasan! 🚨',
                'message' => "Pengeluaran {$categoryName} kamu sudah melebihi batas bulan ini!",
            ];
        }

        if ($percentage >= 80) {
            return [
                'type'    => 'warning',
                'title'   => 'Hampir habis! ⚠️',
                'message' => "Uang {$categoryName} kamu sudah " . round($percentage) . "% terpakai. Sisa {$sisaFormat}.",
            ];
        }

        return null;
    }

    /* ==================================================================
     |  Query Builder (existing methods - preserved)
     | ================================================================== */

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

        if ($trx->type === 'transfer') {
            return 'invalid_type';
        }

        $fav = $this->favoriteRepository->firstOrCreate(
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

    /* ------------------------------------------------------------------
     |  Helpers (private)
     | ------------------------------------------------------------------ */

    /**
     * Generate nama default transaksi jika user tidak mengisi.
     */
    private function generateDefaultName(string $type): string
    {
        $labels = [
            'income'   => 'Pemasukan',
            'expense'  => 'Pengeluaran',
            'transfer' => 'Transfer',
        ];

        return ($labels[$type] ?? 'Transaksi') . ' ' . now()->translatedFormat('d M');
    }
}
