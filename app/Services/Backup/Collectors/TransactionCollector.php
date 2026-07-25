<?php

namespace App\Services\Backup\Collectors;

use App\Models\Transaction;

class TransactionCollector implements BackupCollectorInterface
{
    public function entityName(): string
    {
        return 'transactions';
    }

    public function collect(int $userId): array
    {
        return Transaction::withoutGlobalScopes()
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
            'amount' => (float) $item['amount'],
            'type' => (string) $item['type'],
            'date' => (string) $item['date'],
            'category_id' => isset($item['category_id']) ? (int) $item['category_id'] : null,
            'account_id' => (int) $item['account_id'],
            'to_account_id' => isset($item['to_account_id']) ? (int) $item['to_account_id'] : null,
        ];
    }
}
