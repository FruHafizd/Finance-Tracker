<?php

namespace App\Services\Backup\Collectors;

use App\Models\FinancialReminder;

class FinancialReminderCollector implements BackupCollectorInterface
{
    public function entityName(): string
    {
        return 'financial_reminders';
    }

    public function collect(int $userId): array
    {
        return FinancialReminder::withoutGlobalScopes()
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
            'day' => (int) $item['day'],
            'month' => (int) $item['month'],
            'year' => (int) $item['year'],
            'category' => (string) $item['category'],
            'description' => (string) ($item['description'] ?? ''),
            'amount' => (float) $item['amount'],
            'remind_before' => (int) $item['remind_before'],
        ];
    }
}
