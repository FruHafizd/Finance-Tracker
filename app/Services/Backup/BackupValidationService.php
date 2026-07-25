<?php

namespace App\Services\Backup;

use RuntimeException;

class BackupValidationService
{
    /**
     * Memvalidasi struktur dasar array backup.
     */
    public function validateStructure(array $data): void
    {
        if (!isset($data['metadata']) || !is_array($data['metadata'])) {
            throw new RuntimeException('Struktur file backup tidak valid: key metadata hilang atau bukan array.');
        }

        $meta = $data['metadata'];
        if (!isset($meta['version']) || !isset($meta['user_id']) || !isset($meta['checksum'])) {
            throw new RuntimeException('Struktur file backup tidak valid atau tidak lengkap di bagian metadata.');
        }

        $entities = config('backup.entities', []);
        foreach ($entities as $entity) {
            if (!array_key_exists($entity, $data) || !is_array($data[$entity])) {
                throw new RuntimeException("Struktur file backup tidak valid: entity '$entity' hilang atau format tidak valid.");
            }
        }
    }

    /**
     * Memvalidasi kepemilikan file backup.
     */
    public function validateOwnership(array $data, int $userId): void
    {
        if ((int) $data['metadata']['user_id'] !== $userId) {
            throw new RuntimeException('File backup ini bukan milik Anda.');
        }
    }

    /**
     * Memvalidasi versi backup.
     */
    public function validateVersion(array $data): void
    {
        $minVersion = config('backup.min_compatible_version', '2.0.0');
        
        if (version_compare((string) $data['metadata']['version'], $minVersion, '<')) {
            throw new RuntimeException('Versi file backup tidak didukung oleh sistem saat ini.');
        }
    }

    /**
     * Memvalidasi checksum file backup.
     */
    public function validateChecksum(array $data, BackupEncryptionService $encryptionService): void
    {
        $hash = $data['metadata']['checksum'] ?? '';
        
        // Checksum dihitung dari SELURUH data kecuali key 'metadata'
        $dataForChecksum = $data;
        unset($dataForChecksum['metadata']);

        if (!$encryptionService->verifyChecksum($dataForChecksum, $hash)) {
            throw new RuntimeException('Checksum backup tidak valid. File mungkin telah dimodifikasi.');
        }
    }
}
