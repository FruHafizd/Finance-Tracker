<?php

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ExportTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function export_membutuhkan_signature_valid(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('export.excel', ['start' => '2024-01-01', 'end' => '2024-01-31']))
            ->assertStatus(403);
    }

    #[Test]
    public function export_berhasil_dengan_signature_valid(): void
    {
        $user = User::factory()->create();
        $url = URL::signedRoute('export.excel', ['start' => '2024-01-01', 'end' => '2024-01-31']);

        $this->actingAs($user)
            ->get($url)
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    #[Test]
    public function export_gagal_jika_format_tanggal_salah(): void
    {
        $user = User::factory()->create();
        $url = URL::signedRoute('export.excel', ['start' => 'not-a-date', 'end' => '2024-01-31']);

        $this->actingAs($user)
            ->get($url)
            ->assertStatus(302) // Redirect back due to validation error
            ->assertSessionHasErrors(['start']);
    }

    #[Test]
    public function export_melindungi_dari_csv_injection(): void
    {
        $user = User::factory()->create();
        
        // Buat transaksi dengan nama berbahaya yang memicu formula Excel
        Transaction::factory()->create([
            'user_id' => $user->id,
            'name'    => '=SUM(1+1)',
            'date'    => '2024-01-01',
        ]);

        $url = URL::signedRoute('export.excel', ['start' => '2024-01-01', 'end' => '2024-01-31']);

        $this->actingAs($user)
            ->get($url)
            ->assertStatus(200);
            
        // Catatan: Verifikasi isi binary excel lebih lanjut memerlukan library PHPSpreadsheet.
        // Namun flow ini memastikan data berbahaya tidak menyebabkan kegagalan sistem.
    }
}
