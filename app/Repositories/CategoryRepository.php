<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Support\Collection;

class CategoryRepository
{
    /**
     * Ambil semua kategori milik user tertentu.
     */
    public function getAllByUser(int $userId): Collection
    {
        return Category::where('user_id', $userId)->get();
    }

    /**
     * Cari kategori berdasarkan ID.
     */
    public function findById(int $id): ?Category
    {
        return Category::find($id);
    }

    /**
     * Cari kategori berdasarkan ID, atau throw exception jika tidak ditemukan.
     */
    public function findOrFail(int $id): Category
    {
        return Category::findOrFail($id);
    }

    /**
     * Buat kategori baru.
     */
    public function create(array $data): Category
    {
        return Category::create($data);
    }

    /**
     * Update kategori berdasarkan ID.
     */
    public function update(int $id, array $data): bool
    {
        return Category::where('id', $id)->update($data) > 0;
    }

    /**
     * Hapus kategori.
     */
    public function delete(Category $category): bool
    {
        return $category->delete();
    }

    /**
     * Hitung jumlah transaksi yang terkait dengan kategori untuk user tertentu.
     */
    public function countRelatedTransactions(int $categoryId, int $userId): int
    {
        return \App\Models\Transaction::where('category_id', $categoryId)
            ->where('user_id', $userId)
            ->count();
    }

    /**
     * Cek apakah kategori memiliki budget terkait untuk user tertentu.
     */
    public function hasRelatedBudgets(int $categoryId, int $userId): bool
    {
        return \App\Models\Budget::where('category_id', $categoryId)
            ->where('user_id', $userId)
            ->exists();
    }

    /**
     * Cek apakah kategori memiliki transaksi favorit terkait untuk user tertentu.
     */
    public function hasRelatedFavorites(int $categoryId, int $userId): bool
    {
        return \App\Models\FavoriteTransaction::where('category_id', $categoryId)
            ->where('user_id', $userId)
            ->exists();
    }
}
