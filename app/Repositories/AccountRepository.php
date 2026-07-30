<?php

namespace App\Repositories;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Support\Collection;

class AccountRepository
{
    /**
     * Ambil rekening berdasarkan filter, pencarian, dan urutan.
     */
    public function getFiltered(string $tab, string $search, string $sortBy, string $sortDir): Collection
    {
        return Account::query()
            ->when($tab !== 'semua', fn ($query) => $query->where('type', $tab))
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('provider', 'like', '%' . $search . '%');
                });
            })
            ->orderBy($sortBy, $sortDir)
            ->get();
    }

    /**
     * Cari rekening milik user yang sedang aktif.
     */
    public function findOrFail(int $id): Account
    {
        return Account::findOrFail($id);
    }

    /**
     * Simpan rekening baru.
     */
    public function create(array $data): Account
    {
        return Account::create($data);
    }

    /**
     * Perbarui rekening.
     */
    public function update(Account $account, array $data): Account
    {
        $account->update($data);

        return $account->fresh();
    }

    /**
     * Hapus rekening.
     */
    public function delete(Account $account): void
    {
        $account->delete();
    }

    /**
     * Ambil sort order tertinggi milik user.
     */
    public function getMaxSortOrder(int $userId): int
    {
        return (int) Account::where('user_id', $userId)->max('sort_order');
    }

    /**
     * Cek apakah rekening masih dipakai sebagai sumber atau tujuan transaksi.
     */
    public function hasTransactions(Account $account): bool
    {
        return $account->transactions()->exists()
            || Transaction::where('to_account_id', $account->id)->exists();
    }
    /**
     * Ambil total pemasukan dan pengeluaran pada bulan berjalan.
     *
     * @param Collection<int, int>|null $accountIds
     * @return array{income: float, expense: float}
     */
    public function getMonthlyTransactionTotals(?Collection $accountIds = null): array
    {
        $query = Transaction::query()
            ->whereBetween('date', [now()->startOfMonth(), now()->endOfMonth()]);

        if ($accountIds !== null) {
            $query->whereIn('account_id', $accountIds);
        }

        $totals = $query->selectRaw("\n            SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as income,\n            SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expense\n        ")->first();

        return [
            'income' => (float) ($totals->income ?? 0),
            'expense' => (float) ($totals->expense ?? 0),
        ];
    }

    /**
     * Ambil semua rekening milik user aktif dengan urutan default.
     */
    public function getUserAccounts(): Collection
    {
        return Account::orderBy('sort_order', 'asc')
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     * Ambil semua rekening untuk user tertentu di konteks non-auth.
     */
    public function getAccountsForUser(int $userId): Collection
    {
        return Account::withoutGlobalScope('user')
            ->where('user_id', $userId)
            ->orderBy('sort_order', 'asc')
            ->orderBy('name', 'asc')
            ->get();
    }
}
