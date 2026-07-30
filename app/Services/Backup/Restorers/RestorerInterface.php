<?php

namespace App\Services\Backup\Restorers;

interface RestorerInterface
{
    /**
     * Nama entity (harus sama dengan collector).
     */
    public function entityName(): string;

    /**
     * Restore array of items ke database.
     * @param array $items Data dari backup file
     * @param int $userId User yang sedang login
     * @param array $idMaps Mapping ID lama → ID baru dari entity sebelumnya
     *                      Format: ['accounts' => [oldId => newId], 'categories' => [...]]
     * @return array Updated $idMaps dengan mapping entity ini
     */
    public function restore(array $items, int $userId, array $idMaps): array;

    /**
     * Hapus semua data entity milik user (untuk mode replace_all).
     */
    public function clearUserData(int $userId): void;

    /**
     * Sanitize satu incoming item (strip_tags, cast types, dll).
     */
    public function sanitizeIncoming(array $data): array;
}
