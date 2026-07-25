<?php

namespace App\Services\Backup;

use Illuminate\Support\Facades\Crypt;

class BackupEncryptionService
{
    /**
     * Mengenkripsi string JSON backup.
     */
    public function encrypt(string $json): string
    {
        return Crypt::encryptString($json);
    }

    /**
     * Mendekripsi string backup terenkripsi.
     */
    public function decrypt(string $encrypted): string
    {
        try {
            return Crypt::decryptString($encrypted);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            throw new \RuntimeException('File backup tidak dapat didekripsi. File mungkin corrupt atau berasal dari aplikasi lain.', 0, $e);
        }
    }

    /**
     * Men-generate checksum SHA-256 dari array data.
     * Menggunakan ksort secara rekursif agar urutan key tidak memengaruhi hasil checksum.
     */
    public function generateChecksum(array $data): string
    {
        $this->recursiveKsort($data);
        $json = json_encode($data);

        return hash('sha256', $json);
    }

    /**
     * Memverifikasi apakah checksum dari data sesuai dengan hash yang diberikan.
     */
    public function verifyChecksum(array $data, string $hash): bool
    {
        return hash_equals($hash, $this->generateChecksum($data));
    }

    /**
     * Melakukan ksort secara rekursif pada array.
     */
    private function recursiveKsort(array &$array): void
    {
        ksort($array);
        foreach ($array as &$value) {
            if (is_array($value)) {
                $this->recursiveKsort($value);
            }
        }
    }
}
