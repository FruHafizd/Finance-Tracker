<?php

namespace App\Services\Backup\Restorers;

use App\Models\TelegramAccount;

class TelegramAccountRestorer implements RestorerInterface
{
    public function entityName(): string
    {
        return 'telegram_accounts';
    }

    public function restore(array $items, int $userId, array $idMaps): array
    {
        $myMap = [];
        
        foreach ($items as $item) {
            $clean = $this->sanitizeIncoming($item);
            
            $oldId = $clean['_old_id'];
            unset($clean['_old_id']);
            
            $clean['user_id'] = $userId;
            
            $new = TelegramAccount::withoutGlobalScopes()->create($clean);
            $myMap[$oldId] = $new->id;
        }
        
        $idMaps['telegram_accounts'] = $myMap;
        return $idMaps;
    }

    public function clearUserData(int $userId): void
    {
        TelegramAccount::withoutGlobalScopes()
            ->where('user_id', $userId)
            ->delete();
    }

    public function sanitizeIncoming(array $data): array
    {
        return [
            '_old_id'              => (int) ($data['id'] ?? 0),
            'telegram_chat_id'     => strip_tags($data['telegram_chat_id'] ?? ''),
            'link_code'            => null,
            'link_code_expires_at' => null,
        ];
    }
}
