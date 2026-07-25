<?php

namespace App\Services\Backup\Collectors;

use App\Models\TelegramAccount;

class TelegramAccountCollector implements BackupCollectorInterface
{
    public function entityName(): string
    {
        return 'telegram_accounts';
    }

    public function collect(int $userId): array
    {
        return TelegramAccount::withoutGlobalScopes()
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
            'telegram_chat_id' => isset($item['telegram_chat_id']) ? (string) $item['telegram_chat_id'] : null,
        ];
    }
}
