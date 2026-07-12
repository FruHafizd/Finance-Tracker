<?php

namespace App\Repositories;

use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Collection;

class TransactionRepository
{
    /**
     * Ambil rekening terakhir yang dipakai user (berdasarkan transaksi terbaru).
     */
    public function getLastUsedAccount(User $user): ?Account
    {
        $lastTransaction = Transaction::withoutGlobalScope('user')
            ->where('user_id', $user->id)
            ->latest('date')
            ->latest('created_at')
            ->first();

        if (! $lastTransaction) {
            return null;
        }

        return Account::withoutGlobalScope('user')
            ->where('id', $lastTransaction->account_id)
            ->first();
    }

    /**
     * Ambil kategori terakhir yang dipakai user untuk jenis transaksi tertentu.
     */
    public function getLastUsedCategory(User $user, string $transactionType): ?Category
    {
        $lastTransaction = Transaction::withoutGlobalScope('user')
            ->where('user_id', $user->id)
            ->where('type', $transactionType)
            ->latest('date')
            ->latest('created_at')
            ->first();

        if (! $lastTransaction) {
            return null;
        }

        return Category::withoutGlobalScope('user')
            ->where('id', $lastTransaction->category_id)
            ->first();
    }

    /**
     * Ambil kategori yang difilter berdasarkan jenis transaksi.
     *
     * Di-filter berdasarkan kolom `type` di tabel categories.
     */
    public function getCategoriesForType(int $userId, string $transactionType): Collection
    {
        // Untuk transfer, tampilkan semua kategori
        if ($transactionType === 'transfer') {
            return Category::where('user_id', $userId)
                ->orderBy('name')
                ->get();
        }

        return Category::where('user_id', $userId)
            ->where('type', $transactionType)
            ->orderBy('name')
            ->get();
    }

    /**
     * Cari transaksi milik user berdasarkan ID.
     */
    public function findByIdForUser(int $transactionId, int $userId): Transaction
    {
        return Transaction::where('id', $transactionId)
            ->where('user_id', $userId)
            ->firstOrFail();
    }
}
