<?php

namespace App\Services\Backup\Collectors;

interface BackupCollectorInterface
{
    /**
     * Nama entity (key dalam JSON backup).
     * Contoh: 'accounts', 'categories', dll.
     */
    public function entityName(): string;

    /**
     * Collect semua data milik user untuk di-backup.
     * @return array Data yang sudah di-sanitize untuk export
     */
    public function collect(int $userId): array;

    /**
     * Sanitize satu item sebelum export.
     * Hapus field sensitif, timestamps, dll.
     */
    public function sanitizeForExport(array $item): array;
}
