<?php

namespace App\Livewire\Settings;

use App\Services\Backup\BackupService;
use App\Services\Backup\BackupStorageService;
use App\Services\Backup\RestoreService;
use App\Traits\WithNotifications;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;
use Livewire\WithFileUploads;

class DataManagement extends Component
{
    use WithFileUploads, WithNotifications;

    public $jsonFile;
    public $stats = [];
    public $backups = [];
    public $autoBackupEnabled = false;
    
    // UI states
    public $showRestoreConfirm = false;
    public $isProcessing = false;

    public function mount(BackupService $backupService, BackupStorageService $storageService)
    {
        $this->loadData($backupService, $storageService);
        $this->autoBackupEnabled = (bool) (auth()->user()->auto_backup_enabled ?? false);
    }

    public function loadData(BackupService $backupService, BackupStorageService $storageService)
    {
        $userId = auth()->id();
        $this->stats = $backupService->getStats($userId);
        $this->backups = $storageService->listBackups($userId);
    }

    public function backup(BackupService $backupService, BackupStorageService $storageService)
    {
        $userId = auth()->id();
        $maxAttempts = config('backup.rate_limit.max_backups_per_hour', 5);
        
        // Rate Limiter per User, maskimal attempts / jam
        if (RateLimiter::tooManyAttempts('backup:' . $userId, $maxAttempts)) {
            $seconds = RateLimiter::availableIn('backup:' . $userId);
            $this->notify('Gagal!', "Terlalu sering melakukan backup. Coba lagi dalam {$seconds} detik.", 'error');
            return;
        }

        $this->isProcessing = true;

        try {
            $backupInfo = $backupService->generateBackup($userId);
            RateLimiter::hit('backup:' . $userId, 3600); // 1 jam rate limit
            
            $this->loadData($backupService, $storageService);
            $this->notify('Berhasil!', 'Backup berhasil dibuat.', 'success');
            
            return $storageService->download($backupInfo['path'], $userId);
        } catch (\Exception $e) {
            $this->notify('Gagal!', 'Terjadi kesalahan saat memproses backup.', 'error');
        } finally {
            $this->isProcessing = false;
        }
    }

    public function confirmRestore()
    {
        $maxSize = config('backup.upload.max_size_kb', 10240);
        
        $this->validate([
            'jsonFile' => 'required|file|extensions:backup|max:' . $maxSize,
        ]);
        
        // Buka modal konfirmasi UI ketimbang langsung destroy/restore
        $this->showRestoreConfirm = true;
    }

    public function cancelRestore()
    {
        $this->showRestoreConfirm = false;
        $this->jsonFile = null;
    }

    public function restore(RestoreService $restoreService, BackupService $backupService, BackupStorageService $storageService)
    {
        if (!$this->jsonFile) {
            $this->notify('Gagal!', 'Tidak ada file backup yang dipilih.', 'error');
            return;
        }

        $userId = auth()->id();
        $maxAttempts = config('backup.rate_limit.max_restores_per_hour', 3);

        if (RateLimiter::tooManyAttempts('restore:' . $userId, $maxAttempts)) {
            $seconds = RateLimiter::availableIn('restore:' . $userId);
            $this->notify('Gagal!', "Terlalu sering melakukan restore. Coba lagi dalam {$seconds} detik.", 'error');
            $this->showRestoreConfirm = false;
            return;
        }

        $this->isProcessing = true;
        $this->showRestoreConfirm = false;

        try {
            $content = file_get_contents($this->jsonFile->getRealPath());
            
            $result = $restoreService->restore($content, $userId);
            
            RateLimiter::hit('restore:' . $userId, 3600); // 1 jam rate limit

            if ($result['success']) {
                $this->loadData($backupService, $storageService);
                $this->jsonFile = null;
                $this->notify('Berhasil!', $result['message'], 'success');
            } else {
                $this->notify('Gagal!', $result['message'], 'error');
            }
        } catch (\Exception $e) {
            $this->notify('Gagal!', 'Terjadi kesalahan sistem saat restore data.', 'error');
        } finally {
            $this->isProcessing = false;
        }
    }

    public function downloadBackup(string $path, BackupStorageService $storageService)
    {
        return $storageService->download($path, auth()->id());
    }

    public function deleteBackup(string $path, BackupStorageService $storageService, BackupService $backupService)
    {
        if ($storageService->delete($path, auth()->id())) {
            $this->loadData($backupService, $storageService);
            $this->notify('Terhapus', 'Berkas backup telah dihapus.', 'success');
        } else {
            $this->notify('Gagal', 'Berkas backup gagal dihapus atau tidak ditemukan.', 'error');
        }
    }

    public function toggleAutoBackup()
    {
        $this->autoBackupEnabled = !$this->autoBackupEnabled;

        auth()->user()->update([
            'auto_backup_enabled' => $this->autoBackupEnabled,
        ]);
        
        $status = $this->autoBackupEnabled ? 'diaktifkan' : 'dinonaktifkan';
        $this->notify('Berhasil!', "Auto backup telah {$status}.", 'success');
    }

    public function render()
    {
        return view('livewire.settings.data-management')
            ->layout('layouts.app');
    }
}