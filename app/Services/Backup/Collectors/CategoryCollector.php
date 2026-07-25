<?php

namespace App\Services\Backup\Collectors;

use App\Models\Category;

class CategoryCollector implements BackupCollectorInterface
{
    public function entityName(): string
    {
        return 'categories';
    }

    public function collect(int $userId): array
    {
        return Category::withoutGlobalScopes()
            ->where('user_id', $userId)
            ->get()
            ->map(fn ($item) => $this->sanitizeForExport($item->toArray()))
            ->toArray();
    }

    public function sanitizeForExport(array $item): array
    {
        return [
            'id' => (int) $item['id'],
            'user_id' => (int) $item['user_id'],
            'name' => (string) $item['name'],
            'type' => (string) $item['type'],
            'color' => isset($item['color']) ? (string) $item['color'] : null,
        ];
    }
}
