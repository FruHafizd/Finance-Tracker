<?php

namespace App\Livewire\Settings;

use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use App\Traits\WithNotifications;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class DataManagement extends Component
{
    use WithFileUploads, WithNotifications;

    public $jsonFile;
    public $transactionCount;
    public $categoryCount;
    public $accountCount;

    public $backups = [];
    public $autoBackupEnabled = false;

    // Store each user's backups in their own folder
    protected function backupDisk()
    {
        return 'local'; // change to 'public' or a dedicated disk if you prefer
    }

    protected function backupDir()
    {
        return 'backups/user-' . auth()->id();
    }

    public function mount()
    {
        $this->loadStats();
        $this->loadBackups();
        $this->autoBackupEnabled = (bool) (auth()->user()->auto_backup_enabled ?? false);
    }

    public function loadStats()
    {
        $this->transactionCount = Transaction::count();
        $this->categoryCount = Category::count();
        $this->accountCount = Account::count();
    }

    public function loadBackups()
    {
        $disk = Storage::disk($this->backupDisk());
        $dir = $this->backupDir();

        if (!$disk->exists($dir)) {
            $this->backups = [];
            return;
        }

        $files = collect($disk->files($dir))
            ->filter(fn ($path) => str_ends_with($path, '.json'))
            ->map(function ($path) use ($disk) {
                return [
                    'path' => $path,
                    'name' => basename($path),
                    'size' => $this->formatBytes($disk->size($path)),
                    'created_at' => \Illuminate\Support\Carbon::createFromTimestamp($disk->lastModified($path)),
                ];
            })
            ->sortByDesc('created_at')
            ->values()
            ->toArray();

        $this->backups = $files;
    }

    private function formatBytes($bytes)
    {
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . ' MB';
        }
        if ($bytes >= 1024) {
            return round($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' B';
    }

    /**
     * Backup data to JSON with security scoping.
     * Also saves a copy to storage so it shows up in "Riwayat Backup".
     */
    public function backup()
    {
        $userId = auth()->id();

        $data = [
            'accounts' => Account::all()->map(fn ($item) => $this->sanitizeForExport($item)),
            'categories' => Category::all()->map(fn ($item) => $this->sanitizeForExport($item)),
            'transactions' => Transaction::all()->map(fn ($item) => $this->sanitizeForExport($item)),
            'metadata' => [
                'exported_at' => now()->toDateTimeString(),
                'version' => '1.0.0',
                'user_id' => $userId, // Included for verification, but ignored on restore
            ],
        ];

        $fileName = 'finansiku-backup-' . now()->format('Ymd-His') . '.json';
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        // Persist to storage so it appears in history
        Storage::disk($this->backupDisk())->put(
            $this->backupDir() . '/' . $fileName,
            $json
        );
        $this->loadBackups();

        return response()->streamDownload(function () use ($json) {
            echo $json;
        }, $fileName);
    }

    public function downloadBackup(string $path)
    {
        // Make sure the path actually belongs to this user's folder
        if (!str_starts_with($path, $this->backupDir() . '/')) {
            abort(403);
        }

        return Storage::disk($this->backupDisk())->download($path);
    }

    public function deleteBackup(string $path)
    {
        if (!str_starts_with($path, $this->backupDir() . '/')) {
            abort(403);
        }

        Storage::disk($this->backupDisk())->delete($path);
        $this->loadBackups();
        $this->notify('Terhapus', 'Berkas backup telah dihapus.', 'success');
    }

    public function toggleAutoBackup()
    {
        $this->autoBackupEnabled = !$this->autoBackupEnabled;

        // Requires an `auto_backup_enabled` boolean column on users table
        auth()->user()->update([
            'auto_backup_enabled' => $this->autoBackupEnabled,
        ]);
    }

    /**
     * Restore data from JSON with heavy security sanitization and mapping.
     */
    public function restore()
    {
        $this->validate([
            'jsonFile' => 'required|file|mimes:json|max:5120',
        ]);

        $content = file_get_contents($this->jsonFile->getRealPath());
        $data = json_decode($content, true);

        if (!$this->isValidBackupFormat($data)) {
            session()->flash('error', 'Berkas JSON tidak valid atau korup.');
            return;
        }

        try {
            DB::beginTransaction();

            $userId = auth()->id();
            $accountMap = [];
            $categoryMap = [];

            foreach ($data['accounts'] as $item) {
                $clean = $this->sanitizeIncomingAccount($item);
                $oldId = $clean['old_id'];
                unset($clean['old_id']);

                $clean['user_id'] = $userId;
                $new = Account::create($clean);
                $accountMap[$oldId] = $new->id;
            }

            foreach ($data['categories'] as $item) {
                $clean = $this->sanitizeIncomingCategory($item);
                $oldId = $clean['old_id'];
                unset($clean['old_id']);

                $clean['user_id'] = $userId;
                $new = Category::create($clean);
                $categoryMap[$oldId] = $new->id;
            }

            foreach ($data['transactions'] as $item) {
                $clean = $this->sanitizeIncomingTransaction($item);
                $clean['user_id'] = $userId;

                $clean['account_id'] = $accountMap[$clean['account_id']] ?? null;
                $clean['category_id'] = $categoryMap[$clean['category_id']] ?? null;

                if (isset($clean['to_account_id'])) {
                    $clean['to_account_id'] = $accountMap[$clean['to_account_id']] ?? null;
                }

                Transaction::create($clean);
            }

            DB::commit();
            $this->loadStats();
            $this->jsonFile = null;
            $this->notify('Berhasil!', 'Data berhasil dipulihkan secara aman.', 'success');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Security-Hardened Restore Failed: ' . $e->getMessage());
            $this->notify('Gagal!', 'Gagal memproses data: Pastikan berkas benar.', 'error');
        }
    }

    private function isValidBackupFormat($data)
    {
        return $data && isset($data['accounts'], $data['categories'], $data['transactions']);
    }

    private function sanitizeForExport($item)
    {
        return $item->makeHidden(['deleted_at', 'created_at', 'updated_at'])->toArray();
    }

    private function sanitizeIncomingAccount($data)
    {
        return [
            'old_id' => $data['id'] ?? 0,
            'name' => strip_tags($data['name'] ?? 'Account'),
            'type' => strip_tags($data['type'] ?? 'Cash'),
            'provider' => strip_tags($data['provider'] ?? ''),
            'balance' => (float) ($data['balance'] ?? 0),
            'color' => strip_tags($data['color'] ?? '#000000'),
            'sort_order' => (int) ($data['sort_order'] ?? 0),
        ];
    }

    private function sanitizeIncomingCategory($data)
    {
        return [
            'old_id' => $data['id'] ?? 0,
            'name' => strip_tags($data['name'] ?? 'Category'),
            'color' => strip_tags($data['color'] ?? '#000000'),
        ];
    }

    private function sanitizeIncomingTransaction($data)
    {
        return [
            'name' => strip_tags($data['name'] ?? 'Transaction'),
            'amount' => (float) ($data['amount'] ?? 0),
            'type' => strip_tags($data['type'] ?? 'expense'),
            'date' => strip_tags($data['date'] ?? now()->toDateTimeString()),
            'category_id' => $data['category_id'] ?? null,
            'account_id' => $data['account_id'] ?? null,
            'to_account_id' => $data['to_account_id'] ?? null,
        ];
    }

    public function render()
    {
        return view('livewire.settings.data-management')
            ->layout('layouts.app');
    }
}