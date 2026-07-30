<?php

namespace App\Services\Backup\Restorers;

use App\Models\FavoriteTransaction;

class FavoriteTransactionRestorer implements RestorerInterface
{
    public function entityName(): string
    {
        return 'favorite_transactions';
    }

    public function restore(array $items, int $userId, array $idMaps): array
    {
        $myMap = [];
        
        foreach ($items as $item) {
            $clean = $this->sanitizeIncoming($item, $idMaps);
            
            $oldId = $clean['_old_id'];
            unset($clean['_old_id']);
            
            $clean['user_id'] = $userId;
            
            $new = FavoriteTransaction::withoutGlobalScopes()->create($clean);
            $myMap[$oldId] = $new->id;
        }
        
        $idMaps['favorite_transactions'] = $myMap;
        return $idMaps;
    }

    public function clearUserData(int $userId): void
    {
        FavoriteTransaction::withoutGlobalScopes()
            ->where('user_id', $userId)
            ->delete();
    }

    public function sanitizeIncoming(array $data, array $idMaps = []): array
    {
        $oldCatId = isset($data['category_id']) ? (int) $data['category_id'] : null;
        $newCatId = $oldCatId ? ($idMaps['categories'][$oldCatId] ?? null) : null;

        $oldAccId = isset($data['account_id']) ? (int) $data['account_id'] : null;
        $newAccId = $oldAccId ? ($idMaps['accounts'][$oldAccId] ?? null) : null;

        return [
            '_old_id'     => (int) ($data['id'] ?? 0),
            'category_id' => $newCatId,
            'account_id'  => $newAccId,
            'name'        => strip_tags($data['name'] ?? 'Favorite'),
            'amount'      => (float) ($data['amount'] ?? 0),
            'type'        => strip_tags($data['type'] ?? 'expense'),
        ];
    }
}
