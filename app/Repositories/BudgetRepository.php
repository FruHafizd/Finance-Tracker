<?php

namespace App\Repositories;

use App\Models\Budget;
use Illuminate\Support\Collection;

class BudgetRepository
{
    /**
     * Ambil semua budget milik user untuk bulan & tahun tertentu.
     */
    public function getAllForUser(int $userId, int $month, int $year): Collection
    {
        return Budget::with('category')
            ->where('user_id', $userId)
            ->where('month', $month)
            ->where('year', $year)
            ->get();
    }

    /**
     * Cari budget milik user berdasarkan ID, throw model not found jika gagal.
     */
    public function findOrFailForUser(int $id, int $userId): Budget
    {
        return Budget::with('category')
            ->where('user_id', $userId)
            ->findOrFail($id);
    }

    /**
     * Simpan data budget baru.
     */
    public function create(array $data): Budget
    {
        return Budget::create($data);
    }

    /**
     * Update data budget.
     */
    public function update(Budget $budget, array $data): bool
    {
        return $budget->update($data);
    }

    /**
     * Hapus budget.
     */
    public function delete(Budget $budget): bool
    {
        return $budget->delete();
    }

    /**
     * Cek apakah budget sudah ada untuk kategori + bulan + tahun tertentu.
     */
    public function existsForCategoryMonth(int $userId, int $categoryId, int $month, int $year): bool
    {
        return Budget::where('user_id', $userId)
            ->where('category_id', $categoryId)
            ->where('month', $month)
            ->where('year', $year)
            ->exists();
    }

    /**
     * Ambil daftar category_id yang sudah punya budget di bulan & tahun tertentu.
     */
    public function getUsedCategoryIds(int $userId, int $month, int $year): Collection
    {
        return Budget::where('user_id', $userId)
            ->where('month', $month)
            ->where('year', $year)
            ->pluck('category_id');
    }

    /**
     * Cari budget untuk kategori di bulan & tahun tertentu.
     */
    public function findForCategoryInMonth(int $userId, int $categoryId, int $month, int $year): ?Budget
    {
        return Budget::with('category')
            ->where('user_id', $userId)
            ->where('category_id', $categoryId)
            ->where('month', $month)
            ->where('year', $year)
            ->first();
    }

    /**
     * Ambil budget yang sudah melebihi batas bulan berjalan.
     */
    public function getExceededBudgets(int $userId): Collection
    {
        return Budget::getExceededBudgets($userId);
    }

    /**
     * Hitung total limit_amount budget user untuk bulan & tahun tertentu.
     */
    public function getTotalLimitForMonth(int $userId, int $month, int $year): float
    {
        return (float) Budget::where('user_id', $userId)
            ->where('month', $month)
            ->where('year', $year)
            ->sum('limit_amount');
    }
}
