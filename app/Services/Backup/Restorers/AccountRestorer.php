<?php

namespace App\Services\Backup\Restorers;

use App\Models\Account;

class AccountRestorer implements RestorerInterface
{
    public function entityName(): string
    {
        return 'accounts';
    }

    public function restore(array $items, int $userId, array $idMaps): array
    {
        $myMap = [];
        
        foreach ($items as $item) {
            $clean = $this->sanitizeIncoming($item);
            $oldId = $clean['_old_id'];
            unset($clean['_old_id']);
            
            // SELALU override user_id dengan user yang sedang login
            $clean['user_id'] = $userId;
            
            $new = Account::withoutGlobalScopes()->create($clean);
            $myMap[$oldId] = $new->id;
        }
        
        $idMaps['accounts'] = $myMap;
        return $idMaps;
    }

    public function clearUserData(int $userId): void
    {
        Account::withoutGlobalScopes()
            ->where('user_id', $userId)
            ->delete();
    }

    public function sanitizeIncoming(array $data): array
    {
        return [
            '_old_id'    => (int) ($data['id'] ?? 0),
            'name'       => strip_tags($data['name'] ?? 'Account'),
            'type'       => strip_tags($data['type'] ?? 'Cash'),
            'provider'   => isset($data['provider']) ? strip_tags($data['provider']) : null,
            'balance'    => (float) ($data['balance'] ?? 0),
            'color'      => isset($data['color']) ? strip_tags($data['color']) : null,
            'sort_order' => (int) ($data['sort_order'] ?? 0),
        ];
    }
}
