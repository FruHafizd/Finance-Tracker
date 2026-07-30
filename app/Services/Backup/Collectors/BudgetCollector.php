<?php

namespace App\Services\Backup\Collectors;

use App\Models\Budget;

class BudgetCollector implements BackupCollectorInterface
{
    public function entityName(): string
    {
        return 'budgets';
    }

    public function collect(int $userId): array
    {
        return Budget::withoutGlobalScopes()
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
            'category_id' => (int) $item['category_id'],
            'limit_amount' => (float) $item['limit_amount'],
            'month' => (int) $item['month'],
            'year' => (int) $item['year'],
        ];
    }
}
