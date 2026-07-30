<?php

namespace Tests\Feature\Services\Backup;

use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use App\Services\Backup\BackupService;
use App\Services\Backup\RestoreService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RestoreServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
    }

    public function test_full_cycle_restore_with_correct_foreign_keys()
    {
        $user = User::factory()->create();

        $account = Account::create(['user_id' => $user->id, 'name' => 'Account 1', 'type' => 'tabungan', 'balance' => 1000]);
        $category = Category::create(['user_id' => $user->id, 'name' => 'Category 1', 'type' => 'expense']);
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'account_id' => $account->id,
            'category_id' => $category->id,
            'type' => 'expense',
            'amount' => 100,
            'date' => now(),
            'name' => 'Test Transaction'
        ]);

        $backupService = app(BackupService::class);
        $result = $backupService->generateBackup($user->id);
        
        $encryptedContent = Storage::disk('local')->get($result['path']);

        // Delete all data to simulate fresh state or let restore 'replace_all' handle it
        // We'll let restore handle replacing. 
        // Wait, replace_all will delete old data anyway, let's verify IDs change.
        $oldAccountId = $account->id;
        $oldTransactionId = $transaction->id;

        $restoreService = app(RestoreService::class);
        $restoreResult = $restoreService->restore($encryptedContent, $user->id);

        $this->assertTrue($restoreResult['success']);
        $this->assertEquals(1, $restoreResult['stats']['accounts']);
        $this->assertEquals(1, $restoreResult['stats']['transactions']);

        // Verify new IDs and FKs
        $newAccount = Account::where('user_id', $user->id)->first();
        $newTransaction = Transaction::where('user_id', $user->id)->first();

        $this->assertNotEquals($oldAccountId, $newAccount->id);
        $this->assertNotEquals($oldTransactionId, $newTransaction->id);
        $this->assertEquals($newAccount->id, $newTransaction->account_id);
    }

    public function test_restore_rejected_if_ownership_mismatch()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        Account::create(['user_id' => $user1->id, 'name' => 'Account 1', 'type' => 'tabungan', 'balance' => 1000]);

        $backupService = app(BackupService::class);
        $result = $backupService->generateBackup($user1->id);
        $encryptedContent = Storage::disk('local')->get($result['path']);

        // Try restoring user1's backup to user2
        $restoreService = app(RestoreService::class);
        $restoreResult = $restoreService->restore($encryptedContent, $user2->id);

        $this->assertFalse($restoreResult['success']);
        $this->assertStringContainsString('bukan milik', strtolower($restoreResult['message']));
    }

    public function test_restore_rejected_if_checksum_tampered()
    {
        $user = User::factory()->create();
        Account::create(['user_id' => $user->id, 'name' => 'Account 1', 'type' => 'tabungan', 'balance' => 1000]);

        $backupService = app(BackupService::class);
        $result = $backupService->generateBackup($user->id);
        $encryptedContent = Storage::disk('local')->get($result['path']);

        // Decrypt, tamper, re-encrypt
        $encryptionService = app(\App\Services\Backup\BackupEncryptionService::class);
        $json = $encryptionService->decrypt($encryptedContent);
        $data = json_decode($json, true);
        
        // Tamper data
        $data['accounts'][0]['balance'] = 999999;
        
        $tamperedEncrypted = $encryptionService->encrypt(json_encode($data));

        $restoreService = app(RestoreService::class);
        $restoreResult = $restoreService->restore($tamperedEncrypted, $user->id);

        $this->assertFalse($restoreResult['success']);
        $this->assertStringContainsString('checksum', strtolower($restoreResult['message']));
    }

    public function test_restore_rejected_if_version_incompatible()
    {
        $user = User::factory()->create();
        Account::create(['user_id' => $user->id, 'name' => 'Account 1', 'type' => 'tabungan', 'balance' => 1000]);

        $backupService = app(BackupService::class);
        $result = $backupService->generateBackup($user->id);
        $encryptedContent = Storage::disk('local')->get($result['path']);

        // Decrypt, tamper version, re-encrypt
        $encryptionService = app(\App\Services\Backup\BackupEncryptionService::class);
        $json = $encryptionService->decrypt($encryptedContent);
        $data = json_decode($json, true);
        
        $data['metadata']['version'] = '1.0.0'; // Below min compatible
        // Note: we must also update checksum so it doesn't fail checksum validation first
        $dataForChecksum = $data;
        unset($dataForChecksum['metadata']);
        $data['metadata']['checksum'] = $encryptionService->generateChecksum($dataForChecksum);
        
        $tamperedEncrypted = $encryptionService->encrypt(json_encode($data));

        $restoreService = app(RestoreService::class);
        $restoreResult = $restoreService->restore($tamperedEncrypted, $user->id);

        $this->assertFalse($restoreResult['success']);
        $this->assertStringContainsString('versi file backup tidak didukung', strtolower($restoreResult['message']));
    }

    public function test_db_rollback_works_on_error_during_restore()
    {
        $user = User::factory()->create();
        $account = Account::create(['user_id' => $user->id, 'name' => 'Original Account', 'type' => 'tabungan', 'balance' => 1000]);

        $backupService = app(BackupService::class);
        $result = $backupService->generateBackup($user->id);
        $encryptedContent = Storage::disk('local')->get($result['path']);

        // Decrypt, introduce a flaw that causes DB exception
        $encryptionService = app(\App\Services\Backup\BackupEncryptionService::class);
        $json = $encryptionService->decrypt($encryptedContent);
        $data = json_decode($json, true);
        
        // Add a category that has invalid type to trigger DB constraint error
        $data['categories'][] = [
            'id' => 999,
            'user_id' => $user->id,
            'name' => 'Bad Category',
            'type' => 'invalid_enum_value', // This should fail SQL insert
            'color' => '#000000'
        ];
        
        $dataForChecksum = $data;
        unset($dataForChecksum['metadata']);
        $data['metadata']['checksum'] = $encryptionService->generateChecksum($dataForChecksum);
        
        $tamperedEncrypted = $encryptionService->encrypt(json_encode($data));

        $restoreService = app(RestoreService::class);
        $restoreResult = $restoreService->restore($tamperedEncrypted, $user->id);

        $this->assertFalse($restoreResult['success']);
        $this->assertStringContainsString('gagal memproses data', strtolower($restoreResult['message']));

        // Verify DB rollback happened: Original account should still exist
        $this->assertDatabaseHas('accounts', [
            'id' => $account->id,
            'name' => 'Original Account'
        ]);
    }

    public function test_restore_rejects_legacy_plain_json_format()
    {
        $user = User::factory()->create();
        
        // Buat string JSON mentah yang mirip dengan format backup lama (sebelum ada enkripsi dan metadata check)
        $legacyJson = json_encode([
            'accounts' => [
                ['id' => 1, 'name' => 'Legacy Account', 'balance' => 1000]
            ],
            'transactions' => []
        ]);

        $restoreService = app(RestoreService::class);
        $restoreResult = $restoreService->restore($legacyJson, $user->id);

        $this->assertFalse($restoreResult['success']);
        $this->assertEquals('Format backup ini sudah tidak didukung. Silakan buat backup baru.', $restoreResult['message']);
    }

    public function test_restore_rejects_corrupt_encrypted_file()
    {
        $user = User::factory()->create();
        
        // String acak yang bukan valid JSON dan bukan valid encrypted payload
        $corruptString = "This is just some random text string that is clearly corrupt";

        $restoreService = app(RestoreService::class);
        $restoreResult = $restoreService->restore($corruptString, $user->id);

        $this->assertFalse($restoreResult['success']);
        // Pesan error dari DecryptException / dekripsi gagal, BUKAN pesan format lama
        $this->assertStringContainsString('File backup tidak dapat didekripsi', $restoreResult['message']);
        $this->assertNotEquals('Format backup ini sudah tidak didukung. Silakan buat backup baru.', $restoreResult['message']);
    }
}
