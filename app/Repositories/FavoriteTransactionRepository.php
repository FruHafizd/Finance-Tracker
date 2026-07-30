<?php

namespace App\Repositories;

use App\Models\FavoriteTransaction;
use Illuminate\Support\Collection;

class FavoriteTransactionRepository
{
    /**
     * Ambil semua transaksi favorit milik user beserta relasi category & account.
     */
    public function getAllForUser(int $userId): Collection
    {
        return FavoriteTransaction::with(['category', 'account'])->where('user_id', $userId)->get();
    }

    /**
     * Cari transaksi favorit milik user tertentu, throw model not found jika gagal.
     */
    public function findOrFailForUser(int $id, int $userId): FavoriteTransaction
    {
        return FavoriteTransaction::where('user_id', $userId)->findOrFail($id);
    }

    /**
     * Simpan data transaksi favorit baru.
     */
    public function create(array $data): FavoriteTransaction
    {
        return FavoriteTransaction::create($data);
    }

    /**
     * Update data transaksi favorit.
     */
    public function update(FavoriteTransaction $favorite, array $data): bool
    {
        return $favorite->update($data);
    }

    /**
     * Hapus transaksi favorit.
     */
    public function delete(FavoriteTransaction $favorite): bool
    {
        return $favorite->delete();
    }

    /**
     * Cari atau buat transaksi favorit baru.
     */
    public function firstOrCreate(array $attributes, array $values = []): FavoriteTransaction
    {
        return FavoriteTransaction::firstOrCreate($attributes, $values);
    }
}
