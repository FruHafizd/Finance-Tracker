<?php

namespace App\Services;

use App\Models\Category;
use App\Models\FavoriteTransaction;
use App\Repositories\FavoriteTransactionRepository;
use App\Repositories\TransactionRepository;
use Illuminate\Support\Collection;

class FavoriteTransactionService
{
    public function __construct(
        protected FavoriteTransactionRepository $repository,
        protected TransactionRepository $transactionRepository
    ) {}

    public function getFavoritesForUser(int $userId): Collection
    {
        return $this->repository->getAllForUser($userId);
    }

    public function getFavoriteForUser(int $id, int $userId): FavoriteTransaction
    {
        return $this->repository->findOrFailForUser($id, $userId);
    }

    public function createFavorite(array $data, int $userId): FavoriteTransaction
    {
        $data['user_id'] = $userId;
        return $this->repository->create($data);
    }

    public function updateFavorite(int $id, array $data, int $userId): bool
    {
        $favorite = $this->repository->findOrFailForUser($id, $userId);
        return $this->repository->update($favorite, $data);
    }

    public function deleteFavorite(int $id, int $userId): void
    {
        $favorite = $this->repository->findOrFailForUser($id, $userId);
        $this->repository->delete($favorite);
    }

    /**
     * Ambil kategori yang difilter berdasarkan jenis transaksi.
     * Menggunakan logic yang sama dengan TransactionService.
     */
    public function getFilteredCategories(int $userId, string $transactionType): Collection
    {
        return $this->transactionRepository->getCategoriesForType($userId, $transactionType);
    }

    /**
     * Ambil semua kategori milik user (untuk fallback ketika type belum dipilih).
     */
    public function getAllCategories(int $userId): Collection
    {
        return Category::where('user_id', $userId)->orderBy('name')->get();
    }
}
