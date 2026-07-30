<?php

namespace App\Services\Backup;

use App\Services\Backup\Collectors\BackupCollectorInterface;

class BackupService
{
    private BackupEncryptionService $encryptionService;
    private BackupStorageService $storageService;
    
    /** @var array<string, BackupCollectorInterface> */
    private array $collectors = [];

    public function __construct(
        BackupEncryptionService $encryptionService,
        BackupStorageService $storageService
    ) {
        $this->encryptionService = $encryptionService;
        $this->storageService = $storageService;
        $this->registerCollectors();
    }

    /**
     * Mendaftarkan seluruh collector secara otomatis.
     */
    private function registerCollectors(): void
    {
        $collectorClasses = [
            \App\Services\Backup\Collectors\AccountCollector::class,
            \App\Services\Backup\Collectors\CategoryCollector::class,
            \App\Services\Backup\Collectors\BudgetCollector::class,
            \App\Services\Backup\Collectors\FavoriteTransactionCollector::class,
            \App\Services\Backup\Collectors\TransactionCollector::class,
            \App\Services\Backup\Collectors\FinancialReminderCollector::class,
            \App\Services\Backup\Collectors\TelegramAccountCollector::class,
        ];

        foreach ($collectorClasses as $class) {
            $collector = app($class);
            $this->collectors[$collector->entityName()] = $collector;
        }
    }

    /**
     * Mendaftarkan kolektor secara manual (untuk testing/dependency injection luar).
     */
    public function registerCollector(BackupCollectorInterface $collector): void
    {
        $this->collectors[$collector->entityName()] = $collector;
    }

    /**
     * Menjalankan proses orkestrasi backup secara menyeluruh.
     */
    public function generateBackup(int $userId): array
    {
        $entities = config('backup.entities', []);
        
        $data = [];
        
        // 1. Eksekusi semua kolektor sesuai urutan konfigurasi entitas
        foreach ($entities as $entityName) {
            if (isset($this->collectors[$entityName])) {
                $data[$entityName] = $this->collectors[$entityName]->collect($userId);
            } else {
                $data[$entityName] = []; // Fallback empty array jika kolektor tidak diregister
            }
        }

        // 2. Kalkulasi checksum (Krusial: Hanya dari data mentah entitas)
        $checksum = $this->encryptionService->generateChecksum($data);
        
        // 3. Rekatkan nested metadata
        $data['metadata'] = [
            'version' => config('backup.version', '2.0.0'),
            'user_id' => $userId,
            'checksum' => $checksum,
            'exported_at' => now()->toIso8601String(),
        ];
        
        // 4. Konversi ke JSON dan enkripsi
        $json = json_encode($data);
        $encrypted = $this->encryptionService->encrypt($json);

        // 5. Lempar ke StorageService, yang hanya menerima parameter 2 (userId dan encryptedData)
        // StorageService akan otomatis mengurusi limit kuota di dalamnya (cleanupOldBackups)
        $path = $this->storageService->store($userId, $encrypted);
        
        return [
            'path' => $path,
            'size' => strlen($encrypted) // Estimasi raw byte size
        ];
    }

    /**
     * Shortcut untuk generate backup dan langsung mendownloadnya.
     */
    public function generateAndDownload(int $userId)
    {
        $backupInfo = $this->generateBackup($userId);
        return $this->storageService->download($backupInfo['path'], $userId);
    }
    
    /**
     * Mengambil ringkasan data backup user dari database (record count per entity).
     */
    public function getStats(int $userId): array
    {
        $stats = [];
        foreach ($this->collectors as $entityName => $collector) {
            $stats[$entityName] = count($collector->collect($userId));
        }
        return $stats;
    }

    /**
     * Mengambil ringkasan file backup user dari storage (total size, count, etc).
     */
    public function getBackupFilesSummary(int $userId): array
    {
        $backups = $this->storageService->listBackups($userId);
        
        $totalSize = 0;
        foreach ($backups as $b) {
            $totalSize += $b['size'];
        }
        
        return [
            'total_files' => count($backups),
            'total_size_bytes' => $totalSize,
            'latest_backup' => count($backups) > 0 ? $backups[0] : null,
        ];
    }
}
