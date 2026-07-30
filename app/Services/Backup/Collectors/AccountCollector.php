<?php

namespace App\Services\Backup\Collectors;

use App\Models\Account;

class AccountCollector implements BackupCollectorInterface
{
    public function entityName(): string
    {
        return 'accounts';
    }

    public function collect(int $userId): array
    {
        return Account::withoutGlobalScopes()
            ->where('user_id', $userId)
            ->get()
            ->map(fn ($item) => $this->sanitizeForExport($item->toArray()))
            ->toArray();
    }

    public function sanitizeForExport(array $item): array
    {
        // Hanya menyertakan field yang diperlukan untuk export sesuai skema Account
        return [
            'id' => (int) $item['id'],
            'user_id' => (int) $item['user_id'],
            'name' => (string) $item['name'],
            'type' => (string) $item['type'],
            'provider' => isset($item['provider']) ? (string) $item['provider'] : null,
            'balance' => (float) $item['balance'],
            'color' => isset($item['color']) ? (string) $item['color'] : null,
            'sort_order' => (int) ($item['sort_order'] ?? 0),
        ];
    }
}
