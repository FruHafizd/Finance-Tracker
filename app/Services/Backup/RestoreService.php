<?php

namespace App\Services\Backup;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RestoreService
{
    /** @var array<string, \App\Services\Backup\Restorers\RestorerInterface> */
    protected array $restorers = [];
    
    public function __construct(
        protected BackupEncryptionService $encryption,
        protected BackupValidationService $validation,
        protected BackupStorageService $storage,
        protected BackupService $backupService
    ) {
        $this->registerRestorers();
    }

    protected function registerRestorers(): void
    {
        $restorerMap = [
            'accounts'              => \App\Services\Backup\Restorers\AccountRestorer::class,
            'categories'            => \App\Services\Backup\Restorers\CategoryRestorer::class,
            'budgets'               => \App\Services\Backup\Restorers\BudgetRestorer::class,
            'favorite_transactions' => \App\Services\Backup\Restorers\FavoriteTransactionRestorer::class,
            'transactions'          => \App\Services\Backup\Restorers\TransactionRestorer::class,
            'financial_reminders'   => \App\Services\Backup\Restorers\FinancialReminderRestorer::class,
            'telegram_accounts'     => \App\Services\Backup\Restorers\TelegramAccountRestorer::class,
        ];
        
        foreach (config('backup.entities', []) as $entity) {
            if (isset($restorerMap[$entity])) {
                $this->restorers[$entity] = app($restorerMap[$entity]);
            }
        }
    }

    /**
     * Restore dari uploaded file content.
     * @return array ['success' => bool, 'message' => string, 'stats' => array]
     */
    public function restore(string $fileContent, int $userId): array
    {
        // === STEP 0: Legacy Format Check ===
        // File backup baru harus terenkripsi (bukan JSON plain text).
        // Jika file bisa di-decode sebagai JSON mentah dan memiliki struktur lama (misal key 'accounts'),
        // itu berarti format lama.
        $possibleLegacy = json_decode($fileContent, true);
        if ($possibleLegacy !== null && isset($possibleLegacy['accounts'])) {
            return [
                'success' => false, 
                'message' => 'Format backup ini sudah tidak didukung. Silakan buat backup baru.', 
                'stats' => []
            ];
        }

        // === STEP 1: Decrypt ===
        try {
            $json = $this->encryption->decrypt($fileContent);
        } catch (\RuntimeException $e) {
            return ['success' => false, 'message' => $e->getMessage(), 'stats' => []];
        }
        
        // === STEP 2: Parse JSON ===
        $data = json_decode($json, true);
        if ($data === null) {
            return ['success' => false, 'message' => 'File backup bukan format JSON yang valid.', 'stats' => []];
        }
        
        try {
            // === STEP 3: Validate Structure ===
            $this->validation->validateStructure($data);
            
            // === STEP 4: Validate Version ===
            $this->validation->validateVersion($data);
            
            // === STEP 5: Validate Ownership ===
            $this->validation->validateOwnership($data, $userId);
            
            // === STEP 6: Validate Checksum/Integrity ===
            $this->validation->validateChecksum($data, $this->encryption);
        } catch (\RuntimeException $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'stats' => [],
            ];
        }
        
        // === STEP 7: Auto-snapshot sebelum restore (safety net) ===
        if (config('backup.restore.auto_snapshot_before_restore', true)) {
            try {
                $this->backupService->generateBackup($userId);
                Log::info("Pre-restore snapshot created for user {$userId}");
            } catch (\Exception $e) {
                Log::warning("Failed to create pre-restore snapshot: {$e->getMessage()}");
                // Lanjutkan restore meskipun snapshot gagal
            }
        }
        
        // === STEP 8: Execute Restore dalam DB Transaction ===
        $strategy = config('backup.restore.strategy', 'replace_all');
        $stats = [];
        
        try {
            DB::beginTransaction();
            
            $idMaps = [];
            
            // Jika replace_all, hapus data lama SEMUA entity dulu
            // (urutan terbalik untuk menghormati FK constraints)
            if ($strategy === 'replace_all') {
                $reversed = array_reverse(array_keys($this->restorers));
                foreach ($reversed as $entityName) {
                    $this->restorers[$entityName]->clearUserData($userId);
                }
            }
            
            // Restore sesuai urutan config (FK-safe order)
            foreach ($this->restorers as $entityName => $restorer) {
                $items = $data[$entityName] ?? [];
                $idMaps = $restorer->restore($items, $userId, $idMaps);
                $stats[$entityName] = count($items);
            }
            
            DB::commit();
            
            return [
                'success' => true,
                'message' => 'Data berhasil dipulihkan secara aman.',
                'stats' => $stats,
            ];
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Restore failed for user {$userId}: {$e->getMessage()}", [
                'trace' => $e->getTraceAsString(),
            ]);
            
            return [
                'success' => false,
                'message' => 'Gagal memproses data: ' . $e->getMessage(),
                'stats' => [],
            ];
        }
    }
}
