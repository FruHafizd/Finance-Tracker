<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
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
}
