<?php

namespace App\Services;

use App\Exceptions\AccountHasTransactionsException;
use App\Models\Account;
use App\Models\User;
use App\Repositories\AccountRepository;
use Illuminate\Support\Collection;

class AccountService
{
    public function __construct(
        protected AccountRepository $repository,
    ) {}

    /**
     * Dapatkan semua rekening milik user.
     */
    public function getUserAccounts(): Collection
    {
        return $this->repository->getUserAccounts();
    }

    /**
     * Dapatkan semua rekening milik user tertentu.
     *
     * Menggunakan withoutGlobalScope agar bisa dipakai di konteks
     * non-Auth (misalnya console command untuk Telegram Bot).
     */
    public function getAccountsForUser(User $user): Collection
    {
        return $this->repository->getAccountsForUser($user->id);
    }

    /**
     * Ambil rekening berdasarkan state filter pada halaman rekening.
     */
    public function getFilteredAccounts(string $tab, string $search, string $sortBy, string $sortDir): Collection
    {
        $allowedSortColumns = ['name', 'provider', 'balance', 'sort_order'];
        $sortColumn = in_array($sortBy, $allowedSortColumns) ? $sortBy : 'sort_order';
        $sortDirection = in_array(strtolower($sortDir), ['asc', 'desc']) ? $sortDir : 'asc';

        return $this->repository->getFiltered($tab, $search, $sortColumn, $sortDirection);
    }

    /**
     * Ambil ringkasan saldo dan perubahan saldo bulan berjalan.
     */
    public function getMonthlySummary(): array
    {
        $accounts = $this->repository->getUserAccounts();
        $totals = $this->repository->getMonthlyTransactionTotals();

        $summary = [
            'total' => $accounts->sum('balance'),
            'netChange' => $totals['income'] - $totals['expense'],
        ];

        foreach (['tabungan', 'ewallet', 'tunai'] as $type) {
            $accountIds = $accounts->where('type', $type)->pluck('id');
            $typeTotals = $this->repository->getMonthlyTransactionTotals($accountIds);

            $summary[$type] = $accounts->where('type', $type)->sum('balance');
            $summary[$type . '_change'] = $typeTotals['income'] - $typeTotals['expense'];
        }

        return $summary;
    }

    /**
     * Hitung komposisi saldo setiap rekening terhadap total saldo.
     */
    public function getAccountPercentages(): array
    {
        $accounts = $this->repository->getUserAccounts();
        $total = $accounts->sum('balance');

        if ($total <= 0) {
            return [];
        }

        return $accounts->mapWithKeys(fn (Account $account) => [
            $account->id => round(($account->balance / $total) * 100, 1),
        ])->toArray();
    }

    /**
     * Cari rekening untuk kebutuhan edit form.
     */
    public function findOrFail(int $id): Account
    {
        return $this->repository->findOrFail($id);
    }

    /**
     * Buat rekening baru dengan pemilik dan urutan otomatis.
     */
    public function createAccount(array $data): Account
    {
        $userId = auth()->id();

        return $this->repository->create(array_merge($data, [
            'user_id' => $userId,
            'sort_order' => $this->repository->getMaxSortOrder($userId) + 1,
        ]));
    }

    /**
     * Perbarui rekening berdasarkan ID.
     */
    public function updateAccount(int $id, array $data): Account
    {
        return $this->repository->update($this->repository->findOrFail($id), $data);
    }

    /**
     * Hapus rekening jika belum memiliki riwayat transaksi.
     *
     * @throws AccountHasTransactionsException
     */
    public function deleteAccount(int $id): void
    {
        $account = $this->repository->findOrFail($id);

        if ($this->repository->hasTransactions($account)) {   // <- kirim object, bukan id
            throw new AccountHasTransactionsException($account);
        }

        $this->repository->delete($account);
    }
}
