<?php

namespace Tests\Unit\Services\Backup;

use App\Services\Backup\BackupEncryptionService;
use Illuminate\Support\Facades\Crypt;
use Tests\TestCase;

class BackupEncryptionServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Setup simple app key for testing encryption
        config(['app.key' => 'base64:' . base64_encode(random_bytes(32))]);
    }

    public function test_encrypt_decrypt_roundtrip()
    {
        $service = new BackupEncryptionService();
        $original = json_encode(['foo' => 'bar']);

        $encrypted = $service->encrypt($original);
        $this->assertNotEquals($original, $encrypted);

        $decrypted = $service->decrypt($encrypted);
        $this->assertEquals($original, $decrypted);
    }

    public function test_decrypt_corrupt_string_throws_runtime_exception()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('File backup tidak dapat didekripsi. File mungkin corrupt atau berasal dari aplikasi lain.');

        $service = new BackupEncryptionService();
        $service->decrypt('invalid-encrypted-string');
    }

    public function test_checksum_is_consistent_despite_array_key_order()
    {
        $service = new BackupEncryptionService();

        $data1 = [
            'a' => 1,
            'b' => 2,
            'nested' => [
                'z' => 99,
                'x' => 88
            ]
        ];

        $data2 = [
            'nested' => [
                'x' => 88,
                'z' => 99
            ],
            'b' => 2,
            'a' => 1
        ];

        $checksum1 = $service->generateChecksum($data1);
        $checksum2 = $service->generateChecksum($data2);

        $this->assertEquals($checksum1, $checksum2);
    }

    public function test_verify_checksum()
    {
        $service = new BackupEncryptionService();
        $data = ['foo' => 'bar'];
        
        $hash = $service->generateChecksum($data);

        $this->assertTrue($service->verifyChecksum($data, $hash));

        $modifiedData = ['foo' => 'baz'];
        $this->assertFalse($service->verifyChecksum($modifiedData, $hash));
    }
}
