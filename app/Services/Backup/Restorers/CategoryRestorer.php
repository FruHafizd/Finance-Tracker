<?php

namespace App\Services\Backup\Restorers;

use App\Models\Category;

class CategoryRestorer implements RestorerInterface
{
    public function entityName(): string
    {
        return 'categories';
    }

    public function restore(array $items, int $userId, array $idMaps): array
    {
        $myMap = [];
        
        foreach ($items as $item) {
            $clean = $this->sanitizeIncoming($item);
            $oldId = $clean['_old_id'];
            unset($clean['_old_id']);
            
            $clean['user_id'] = $userId;
            
            $new = Category::withoutGlobalScopes()->create($clean);
            $myMap[$oldId] = $new->id;
        }
        
        $idMaps['categories'] = $myMap;
        return $idMaps;
    }

    public function clearUserData(int $userId): void
    {
        Category::withoutGlobalScopes()
            ->where('user_id', $userId)
            ->delete();
    }

    public function sanitizeIncoming(array $data): array
    {
        return [
            '_old_id' => (int) ($data['id'] ?? 0),
            'name'    => strip_tags($data['name'] ?? 'Category'),
            'type'    => strip_tags($data['type'] ?? 'expense'),
            'color'   => isset($data['color']) ? strip_tags($data['color']) : null,
        ];
    }
}
