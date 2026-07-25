<?php

namespace App\Services\Backup\Restorers;

use App\Models\Budget;

class BudgetRestorer implements RestorerInterface
{
    public function entityName(): string
    {
        return 'budgets';
    }

    public function restore(array $items, int $userId, array $idMaps): array
    {
        $myMap = [];
        
        foreach ($items as $item) {
            $clean = $this->sanitizeIncoming($item, $idMaps);
            // Budget requires category_id
            if (!isset($clean['category_id'])) continue; 

            $oldId = $clean['_old_id'];
            unset($clean['_old_id']);
            
            $clean['user_id'] = $userId;
            
            $new = Budget::withoutGlobalScopes()->create($clean);
            $myMap[$oldId] = $new->id;
        }
        
        $idMaps['budgets'] = $myMap;
        return $idMaps;
    }

    public function clearUserData(int $userId): void
    {
        Budget::withoutGlobalScopes()
            ->where('user_id', $userId)
            ->delete();
    }

    public function sanitizeIncoming(array $data, array $idMaps = []): array
    {
        $oldCatId = (int) ($data['category_id'] ?? 0);
        $newCatId = $idMaps['categories'][$oldCatId] ?? null;

        return [
            '_old_id'      => (int) ($data['id'] ?? 0),
            'category_id'  => $newCatId,
            'limit_amount' => (float) ($data['limit_amount'] ?? 0),
            'month'        => (int) ($data['month'] ?? 1),
            'year'         => (int) ($data['year'] ?? now()->year),
        ];
    }
}
