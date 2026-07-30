<?php

namespace App\Services;

use App\Models\Budget;
use App\Models\Category;
use App\Repositories\BudgetRepository;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class BudgetService
{
    public function __construct(
        protected BudgetRepository $repository
    ) {}

    /**
     * Ambil budget bulan berjalan untuk user.
     */
    public function getCurrentMonthBudgets(int $userId): Collection
    {
        return $this->repository->getAllForUser(
            $userId,
            (int) now()->format('n'),
            (int) now()->format('Y')
        );
    }

    /**
     * Ambil budget milik user untuk bulan & tahun tertentu.
     */
    public function getBudgetsForUser(int $userId, int $month, int $year): Collection
    {
        return $this->repository->getAllForUser($userId, $month, $year);
    }

    /**
     * Ambil budget untuk form edit.
     */
    public function getBudgetForEdit(int $id, int $userId): Budget
    {
        return $this->repository->findOrFailForUser($id, $userId);
    }

    /**
     * Buat budget baru dengan validasi duplikat kategori+bulan+tahun.
     */
    public function createBudget(array $data, int $userId): Budget
    {
        $month = $data['month'] ?? (int) now()->format('n');
        $year  = $data['year']  ?? (int) now()->format('Y');

        if ($this->repository->existsForCategoryMonth($userId, $data['category_id'], $month, $year)) {
            throw ValidationException::withMessages([
                'category_id' => 'Kategori ini sudah memiliki budget di bulan ini.',
            ]);
        }

        return $this->repository->create(array_merge($data, [
            'user_id' => $userId,
            'month'   => $month,
            'year'    => $year,
        ]));
    }

    /**
     * Update budget yang sudah ada.
     */
    public function updateBudget(int $id, array $data, int $userId): bool
    {
        $budget = $this->repository->findOrFailForUser($id, $userId);

        return $this->repository->update($budget, $data);
    }

    /**
     * Hapus budget milik user.
     */
    public function deleteBudget(int $id, int $userId): void
    {
        $budget = $this->repository->findOrFailForUser($id, $userId);
        $this->repository->delete($budget);
    }

    /**
     * Ambil kategori yang belum punya budget di bulan berjalan.
     */
    public function getAvailableCategories(int $userId): Collection
    {
        $month = (int) now()->format('n');
        $year  = (int) now()->format('Y');

        $usedCategoryIds = $this->repository->getUsedCategoryIds($userId, $month, $year);

        return Category::where('user_id', $userId)
            ->whereNotIn('id', $usedCategoryIds)
            ->orderBy('name')
            ->get();
    }

    /**
     * Ambil budget yang sudah melebihi batas bulan berjalan.
     */
    public function getExceededBudgets(int $userId): Collection
    {
        return $this->repository->getExceededBudgets($userId);
    }

    /**
     * Hitung jumlah budget yang melebihi batas bulan berjalan.
     */
    public function getExceededBudgetsCount(int $userId): int
    {
        return $this->repository->getExceededBudgets($userId)->count();
    }

    /**
     * Cari budget bulan berjalan untuk kategori tertentu.
     */
    public function findCurrentMonthBudget(int $userId, int $categoryId): ?Budget
    {
        return $this->repository->findForCategoryInMonth(
            $userId,
            $categoryId,
            (int) now()->format('n'),
            (int) now()->format('Y')
        );
    }

    /**
     * Hitung total limit budget user untuk bulan berjalan.
     */
    public function getTotalLimitForCurrentMonth(int $userId): float
    {
        return $this->repository->getTotalLimitForMonth(
            $userId,
            (int) now()->format('n'),
            (int) now()->format('Y')
        );
    }
}
