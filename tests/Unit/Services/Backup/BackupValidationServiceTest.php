<?php

namespace Tests\Unit\Services\Backup;

use App\Services\Backup\BackupEncryptionService;
use App\Services\Backup\BackupValidationService;
use RuntimeException;
use Tests\TestCase;

class BackupValidationServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Setup config for testing
        config(['backup.min_compatible_version' => '2.0.0']);
        config(['backup.entities' => [
            'accounts',
            'categories',
            'budgets',
            'favorite_transactions',
            'transactions',
            'financial_reminders',
            'telegram_accounts'
        ]]);
    }

    private function getValidDataStructure(): array
    {
        return [
            'metadata' => [
                'version' => '2.0.0',
                'user_id' => 1,
                'checksum' => 'hash',
            ],
            'accounts' => [],
            'categories' => [],
            'budgets' => [],
            'favorite_transactions' => [],
            'transactions' => [],
            'financial_reminders' => [],
            'telegram_accounts' => []
        ];
    }

    public function test_validate_structure_valid()
    {
        $service = new BackupValidationService();
        $data = $this->getValidDataStructure();
        
        // Seharusnya tidak throw exception
        $service->validateStructure($data);
        $this->assertTrue(true);
    }

    public function test_validate_structure_missing_core_key()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Struktur file backup tidak valid atau tidak lengkap di bagian metadata.');

        $service = new BackupValidationService();
        $data = $this->getValidDataStructure();
        unset($data['metadata']['user_id']); // missing core key
        
        $service->validateStructure($data);
    }

    public function test_validate_structure_missing_entity_key()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("Struktur file backup tidak valid: entity 'transactions' hilang atau format tidak valid.");

        $service = new BackupValidationService();
        $data = $this->getValidDataStructure();
        unset($data['transactions']); // missing one entity at top level
        
        $service->validateStructure($data);
    }

    public function test_validate_ownership_match()
    {
        $service = new BackupValidationService();
        $data = ['metadata' => ['user_id' => 5]];
        
        // Seharusnya tidak throw exception
        $service->validateOwnership($data, 5);
        $this->assertTrue(true);
    }

    public function test_validate_ownership_mismatch()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File backup ini bukan milik Anda.');

        $service = new BackupValidationService();
        $data = ['metadata' => ['user_id' => 5]];
        
        $service->validateOwnership($data, 10);
    }

    public function test_validate_version_compatible_exact()
    {
        $service = new BackupValidationService();
        $data = ['metadata' => ['version' => '2.0.0']];
        
        // Seharusnya tidak throw exception
        $service->validateVersion($data);
        $this->assertTrue(true);
    }

    public function test_validate_version_compatible_greater()
    {
        $service = new BackupValidationService();
        $data = ['metadata' => ['version' => '2.1.0']];
        
        // Seharusnya tidak throw exception
        $service->validateVersion($data);
        $this->assertTrue(true);
    }

    public function test_validate_version_incompatible()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Versi file backup tidak didukung oleh sistem saat ini.');

        $service = new BackupValidationService();
        $data = ['metadata' => ['version' => '1.9.9']];
        
        $service->validateVersion($data);
    }

    public function test_validate_checksum_valid()
    {
        $encryptionService = new BackupEncryptionService();
        $validationService = new BackupValidationService();

        $data = $this->getValidDataStructure();
        $dataForChecksum = $data;
        unset($dataForChecksum['metadata']); // metadata di-unset untuk generate hash

        // Generate valid checksum
        $hash = $encryptionService->generateChecksum($dataForChecksum);
        $data['metadata']['checksum'] = $hash;

        // Seharusnya tidak throw exception
        $validationService->validateChecksum($data, $encryptionService);
        $this->assertTrue(true);
    }

    public function test_validate_checksum_tampered()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Checksum backup tidak valid. File mungkin telah dimodifikasi.');

        $encryptionService = new BackupEncryptionService();
        $validationService = new BackupValidationService();

        $data = $this->getValidDataStructure();
        
        $dataForChecksum = $data;
        unset($dataForChecksum['metadata']);

        // Generate valid checksum
        $hash = $encryptionService->generateChecksum($dataForChecksum);
        
        // Tamper data outside metadata
        $data['accounts'] = [['id' => 1]]; 
        $data['metadata']['checksum'] = $hash; // Old hash

        $validationService->validateChecksum($data, $encryptionService);
    }
}
