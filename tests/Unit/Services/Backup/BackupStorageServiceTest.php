<?php

namespace Tests\Unit\Services\Backup;

use App\Services\Backup\BackupStorageService;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;
use Illuminate\Support\Str;

class BackupStorageServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        config(['backup.storage.disk' => 'local']);
        config(['backup.storage.directory' => 'backups/private_test']);
        config(['backup.storage.max_files_per_user' => 3]);
        
        Storage::fake('local');
    }

    public function test_store_and_read()
    {
        $service = new BackupStorageService();
        $userId = 1;
        $data = 'encrypted-data';

        $path = $service->store($userId, $data);

        $this->assertTrue(Storage::disk('local')->exists($path));
        $this->assertTrue(Str::endsWith($path, '.backup'));
        
        $retrieved = $service->read($path, $userId);
        $this->assertEquals($data, $retrieved);
    }

    public function test_list_backups_returns_correct_data_sorted()
    {
        $service = new BackupStorageService();
        $userId = 1;
        
        $path1 = $service->store($userId, 'data1');
        sleep(1); // Ensure different timestamps
        $path2 = $service->store($userId, 'data2');

        $backups = $service->listBackups($userId);

        $this->assertCount(2, $backups);
        
        // Should be sorted from newest to oldest
        $this->assertEquals($path2, $backups[0]['path']);
        $this->assertEquals($path1, $backups[1]['path']);
    }

    public function test_delete_backup()
    {
        $service = new BackupStorageService();
        $userId = 1;
        
        $path = $service->store($userId, 'data');
        $this->assertTrue(Storage::disk('local')->exists($path));

        $result = $service->delete($path, $userId);
        
        $this->assertTrue($result);
        $this->assertFalse(Storage::disk('local')->exists($path));
    }

    public function test_assert_path_belongs_to_user_throws_403_for_other_user()
    {
        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('Akses ditolak: Anda tidak memiliki akses ke file backup ini.');
        
        // Expect a 403 status code
        $this->expectExceptionObject(new HttpException(403, 'Akses ditolak: Anda tidak memiliki akses ke file backup ini.'));

        $service = new BackupStorageService();
        
        // user 1 stores a backup
        $pathUser1 = $service->store(1, 'data');
        
        // user 2 tries to read user 1's backup
        $service->read($pathUser1, 2);
    }

    public function test_cleanup_old_backups()
    {
        $service = new BackupStorageService();
        $userId = 1;
        
        // Max files is set to 3 in setUp. Let's create 5 files.
        $service->store($userId, 'data1');
        sleep(1);
        $service->store($userId, 'data2');
        sleep(1);
        $path3 = $service->store($userId, 'data3');
        sleep(1);
        $path4 = $service->store($userId, 'data4');
        sleep(1);
        $path5 = $service->store($userId, 'data5');

        $service->cleanupOldBackups($userId);

        $backups = $service->listBackups($userId);
        $this->assertCount(3, $backups);
        
        // The newest 3 should remain
        $paths = array_column($backups, 'path');
        $this->assertContains($path5, $paths);
        $this->assertContains($path4, $paths);
        $this->assertContains($path3, $paths);
    }
}
