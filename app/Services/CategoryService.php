<?php

namespace App\Services;

use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Support\Collection;

class CategoryService
{
    public function __construct(
        protected CategoryRepository $repository
    ) {}

    /* ==================================================================
     |  Query — ambil data kategori
     | ================================================================== */

    /**
     * Ambil semua kategori milik user.
     */
    public function getAllByUser(int $userId): Collection
    {
        return $this->repository->getAllByUser($userId);
    }

    /**
     * Cari kategori berdasarkan ID.
     */
    public function findById(int $id): ?Category
    {
        return $this->repository->findById($id);
    }

    /* ==================================================================
     |  CRUD — create, update, delete
     | ================================================================== */

    /**
     * Buat kategori baru.
     *
     * @param array{name: string, color: string, type: string} $data
     */
    public function createCategory(array $data, int $userId): Category
    {
        return $this->repository->create([
            'user_id' => $userId,
            'name'    => strip_tags($data['name']),
            'color'   => $data['color'],
            'type'    => $data['type'],
        ]);
    }

    /**
     * Update kategori yang sudah ada.
     *
     * @param array{name: string, color: string, type: string} $data
     */
    public function updateCategory(int $categoryId, array $data): bool
    {
        return $this->repository->update($categoryId, [
            'name'  => $data['name'],
            'color' => $data['color'],
            'type'  => $data['type'],
        ]);
    }

    /**
     * Hapus kategori jika tidak memiliki relasi yang aktif.
     *
     * @return array{success: bool, message: string}
     */
    public function deleteCategory(int $categoryId, int $userId): array
    {
        // Cek dependensi sebelum hapus
        $dependencyCheck = $this->checkDependencies($categoryId, $userId);

        if (! $dependencyCheck['canDelete']) {
            return [
                'success' => false,
                'message' => $dependencyCheck['reason'],
            ];
        }

        try {
            $category = $this->repository->findOrFail($categoryId);
            $this->repository->delete($category);

            return [
                'success' => true,
                'message' => 'Kategori berhasil dihapus.',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan, kategori gagal dihapus.',
            ];
        }
    }

    /* ==================================================================
     |  Business Rules — validasi bisnis
     | ================================================================== */

    /**
     * Cek apakah kategori bisa dihapus (tidak ada transaksi, budget, atau favorit terkait).
     *
     * @return array{canDelete: bool, reason: string}
     */
    public function checkDependencies(int $categoryId, int $userId): array
    {
        $transactionCount = $this->repository->countRelatedTransactions($categoryId, $userId);
        $hasBudgets       = $this->repository->hasRelatedBudgets($categoryId, $userId);
        $hasFavorites     = $this->repository->hasRelatedFavorites($categoryId, $userId);

        if ($transactionCount > 0 || $hasBudgets || $hasFavorites) {
            $reasons = [];

            if ($transactionCount > 0) {
                $reasons[] = "{$transactionCount} transaksi (termasuk transaksi di bulan-bulan sebelumnya)";
            }
            if ($hasBudgets) {
                $reasons[] = "budget";
            }
            if ($hasFavorites) {
                $reasons[] = "transaksi favorit";
            }

            return [
                'canDelete' => false,
                'reason'    => 'Kategori ini tidak dapat dihapus karena masih digunakan oleh '
                    . implode(' dan ', $reasons) . '.',
            ];
        }

        return [
            'canDelete' => true,
            'reason'    => '',
        ];
    }
}
