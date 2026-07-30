<?php

namespace App\Services\Backup\Restorers;

use App\Models\FinancialReminder;

class FinancialReminderRestorer implements RestorerInterface
{
    public function entityName(): string
    {
        return 'financial_reminders';
    }

    public function restore(array $items, int $userId, array $idMaps): array
    {
        $myMap = [];
        
        foreach ($items as $item) {
            $clean = $this->sanitizeIncoming($item);
            
            $oldId = $clean['_old_id'];
            unset($clean['_old_id']);
            
            $clean['user_id'] = $userId;
            
            $new = FinancialReminder::withoutGlobalScopes()->create($clean);
            $myMap[$oldId] = $new->id;
        }
        
        $idMaps['financial_reminders'] = $myMap;
        return $idMaps;
    }

    public function clearUserData(int $userId): void
    {
        FinancialReminder::withoutGlobalScopes()
            ->where('user_id', $userId)
            ->delete();
    }

    public function sanitizeIncoming(array $data): array
    {
        return [
            '_old_id'       => (int) ($data['id'] ?? 0),
            'day'           => (int) ($data['day'] ?? 1),
            'month'         => (int) ($data['month'] ?? 1),
            'year'          => (int) ($data['year'] ?? now()->year),
            'category'      => strip_tags($data['category'] ?? 'General'),
            'description'   => strip_tags($data['description'] ?? ''),
            'amount'        => (float) ($data['amount'] ?? 0),
            'remind_before' => (int) ($data['remind_before'] ?? 0),
        ];
    }
}
