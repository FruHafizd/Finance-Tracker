<?php

namespace Tests\Unit;

use App\Exports\TransactionExport;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_transaction_export_escapes_csv_injection_characters()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Karakter berbahaya: =, +, -, @
        $dangerousInputs = [
            '=SUM(1,2)',
            '+1+2',
            '-5',
            '@WNDW',
        ];

        foreach ($dangerousInputs as $input) {
             Transaction::factory()->create([
                'user_id' => $user->id,
                'name'    => $input,
                'date'    => now()->format('Y-m-d'),
            ]);
        }

        $export = new TransactionExport(now()->subDay()->format('Y-m-d'), now()->addDay()->format('Y-m-d'));
        $data = $export->array();

        // Cari baris data transaksi (lewatkan judul dan header)
        // Struktur: [Tanggal, Kategori, Nama, Tipe, Jumlah]
        // Baris 0: Judul
        // Baris 1: Periode
        // Baris 2: Kosong
        // Baris 3: Header
        // Baris 4 ke atas: Data
        
        $transactionRows = array_slice($data, 4);

        foreach ($transactionRows as $row) {
            if (isset($row[2]) && in_array(substr($row[2], 1), $dangerousInputs)) {
                $this->assertStringStartsWith("'", $row[2], "Data '{$row[2]}' harus diawali tanda petik tunggal.");
            }
        }
    }
}
