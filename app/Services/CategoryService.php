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
        $cleanName = strip_tags($data['name']);

        // Validasi: User tidak boleh membuat kategori yang namanya sama dengan kategori sistem
        $systemCategoryExists = Category::where('user_id', $userId)
            ->where('is_system', true)
            ->whereRaw('LOWER(name) = ?', [strtolower($cleanName)])
            ->exists();

        if ($systemCategoryExists) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'name' => 'Kategori ini sudah disediakan oleh sistem.'
            ]);
        }

        return $this->repository->create([
            'user_id' => $userId,
            'name'    => $cleanName,
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
        $category = $this->repository->findOrFail($categoryId);

        if ($category->isSystem()) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'name' => 'Kategori sistem tidak dapat diedit.'
            ]);
        }

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
        $category = $this->repository->findOrFail($categoryId);

        if ($category->isSystem()) {
            return [
                'success' => false,
                'message' => 'Kategori sistem tidak dapat dihapus.',
            ];
        }

        // Cek dependensi sebelum hapus
        $dependencyCheck = $this->checkDependencies($categoryId, $userId);

        if (! $dependencyCheck['canDelete']) {
            return [
                'success' => false,
                'message' => $dependencyCheck['reason'],
            ];
        }

        try {

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

    /**
     * Seed kategori default sistem untuk user.
     */
    public function seedDefaultCategories(int $userId): void
    {
        $categories = [
            // Pemasukan
            ["name" => "Pemasukan", "color" => "#22c55e", "type" => "income"],
            ["name" => "Gaji", "color" => "#16a34a", "type" => "income"],
            
            // Pengeluaran
            ["name" => "Makanan", "color" => "#f97316", "type" => "expense"],
            ["name" => "Minuman", "color" => "#fb923c", "type" => "expense"],
            ["name" => "Transportasi", "color" => "#3b82f6", "type" => "expense"],
            ["name" => "Belanja", "color" => "#ef4444", "type" => "expense"],
            ["name" => "Hiburan", "color" => "#a855f7", "type" => "expense"],
            ["name" => "Kesehatan", "color" => "#14b8a6", "type" => "expense"],
            ["name" => "Pendidikan", "color" => "#6366f1", "type" => "expense"],
            ["name" => "Tagihan", "color" => "#f43f5e", "type" => "expense"],
            ["name" => "Investasi", "color" => "#0ea5e9", "type" => "expense"],
            ["name" => "Tabungan", "color" => "#10b981", "type" => "expense"],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(
                [
                    "user_id" => $userId,
                    "name"    => $cat["name"],
                    "type"    => $cat["type"],
                ],
                [
                    "color"     => $cat["color"],
                    "is_system" => true,
                ]
            )->update(["is_system" => true]); // Memastikan yang sudah ada (misal Gaji) diubah jadi system
        }
    }
}
