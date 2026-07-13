<?php

namespace App\Services;

use App\Models\FavoriteTransaction;
use App\Repositories\FavoriteTransactionRepository;
use Illuminate\Support\Collection;

class FavoriteTransactionService
{
    public function __construct(
        protected FavoriteTransactionRepository $repository
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
}
