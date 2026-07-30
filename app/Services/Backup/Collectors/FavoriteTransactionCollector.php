<?php

namespace App\Services\Backup\Collectors;

use App\Models\FavoriteTransaction;

class FavoriteTransactionCollector implements BackupCollectorInterface
{
    public function entityName(): string
    {
        return 'favorite_transactions';
    }

    public function collect(int $userId): array
    {
        return FavoriteTransaction::withoutGlobalScopes()
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
            'account_id' => isset($item['account_id']) ? (int) $item['account_id'] : null,
            'name' => (string) $item['name'],
            'amount' => (float) $item['amount'],
            'type' => (string) $item['type'],
        ];
    }
}
