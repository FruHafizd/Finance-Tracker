<?php

namespace App\Services\Backup;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class BackupStorageService
{
    private string $disk;
    private string $baseDirectory;
    private int $maxFiles;

    public function __construct()
    {
        $this->disk = config('backup.storage.disk', 'local');
        $this->baseDirectory = config('backup.storage.directory', 'backups/private');
        $this->maxFiles = config('backup.storage.max_files_per_user', 10);
    }

    /**
     * Mendapatkan direktori backup spesifik untuk user.
     */
    public function userDirectory(int $userId): string
    {
        return rtrim($this->baseDirectory, '/') . '/user_' . $userId;
    }

    /**
     * Menyimpan data backup terenkripsi ke storage.
     */
    public function store(int $userId, string $encryptedData): string
    {
        $directory = $this->userDirectory($userId);
        $filename = 'backup_' . now()->format('Ymd_His') . '_' . Str::random(8) . '.backup';
        $path = $directory . '/' . $filename;

        Storage::disk($this->disk)->put($path, $encryptedData);

        // Otomatis hapus backup lama jika melebihi kuota
        $this->cleanupOldBackups($userId);

        return $path;
    }

    /**
     * Mendapatkan daftar semua file backup milik user.
     */
    public function listBackups(int $userId): array
    {
        $directory = $this->userDirectory($userId);
        
        if (!Storage::disk($this->disk)->exists($directory)) {
            return [];
        }

        $files = Storage::disk($this->disk)->files($directory);
        
        $backups = [];
        foreach ($files as $file) {
            if (Str::endsWith($file, '.backup')) {
                $backups[] = [
                    'path' => $file,
                    'name' => basename($file),
                    'size' => Storage::disk($this->disk)->size($file),
                    'last_modified' => Storage::disk($this->disk)->lastModified($file),
                ];
            }
        }

        // Urutkan dari yang terbaru
        usort($backups, function ($a, $b) {
            return $b['last_modified'] <=> $a['last_modified'];
        });

        return $backups;
    }

    /**
     * Membaca isi file backup (terenkripsi).
     */
    public function read(string $path, int $userId): string
    {
        $this->assertPathBelongsToUser($path, $userId);

        if (!Storage::disk($this->disk)->exists($path)) {
            abort(404, 'File backup tidak ditemukan.');
        }

        return Storage::disk($this->disk)->get($path);
    }

    /**
     * Mengunduh file backup.
     */
    public function download(string $path, int $userId): StreamedResponse
    {
        $this->assertPathBelongsToUser($path, $userId);

        if (!Storage::disk($this->disk)->exists($path)) {
            abort(404, 'File backup tidak ditemukan.');
        }

        return Storage::disk($this->disk)->download($path);
    }

    /**
     * Membuat temporary signed URL untuk download.
     */
    public function generateSignedUrl(string $path, int $userId): string
    {
        $this->assertPathBelongsToUser($path, $userId);
        
        return URL::temporarySignedRoute(
            'backups.download',
            now()->addMinutes(30),
            ['path' => base64_encode($path)]
        );
    }

    /**
     * Menghapus file backup.
     */
    public function delete(string $path, int $userId): bool
    {
        $this->assertPathBelongsToUser($path, $userId);

        if (!Storage::disk($this->disk)->exists($path)) {
            return false;
        }

        return Storage::disk($this->disk)->delete($path);
    }

    /**
     * Membersihkan backup lama jika melebihi batas max files per user.
     */
    public function cleanupOldBackups(int $userId): void
    {
        $backups = $this->listBackups($userId);

        if (count($backups) > $this->maxFiles) {
            $toDelete = array_slice($backups, $this->maxFiles);
            foreach ($toDelete as $backup) {
                Storage::disk($this->disk)->delete($backup['path']);
            }
        }
    }

    /**
     * Memvalidasi bahwa path yang diakses berada di dalam direktori milik user_id.
     */
    public function assertPathBelongsToUser(string $path, int $userId): void
    {
        $expectedPrefix = $this->userDirectory($userId) . '/';
        
        // Normalize slashes to forward slashes for reliable check
        $path = str_replace('\\', '/', $path);
        
        if (!Str::startsWith($path, $expectedPrefix)) {
            abort(403, 'Akses ditolak: Anda tidak memiliki akses ke file backup ini.');
        }
    }
}
