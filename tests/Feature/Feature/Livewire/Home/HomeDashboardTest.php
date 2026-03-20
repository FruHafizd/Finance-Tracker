<?php

namespace Tests\Feature\Livewire\Home;

use App\Livewire\Home\ExpenseChart;
use App\Livewire\Home\SummaryCards;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class HomeDashboardTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Category $cat;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->cat  = Category::factory()->create([
            'user_id' => $this->user->id,
            'name'    => 'Makanan',
            'color'   => '#f97316',
        ]);
    }

    private function income(int $amount, string $date): Transaction
    {
        return Transaction::factory()->create([
            'user_id'     => $this->user->id,
            'type'        => 'income',
            'amount'      => $amount,
            'date'        => $date,
            'category_id' => $this->cat->id,
        ]);
    }

    private function expense(int $amount, string $date): Transaction
    {
        return Transaction::factory()->create([
            'user_id'     => $this->user->id,
            'type'        => 'expense',
            'amount'      => $amount,
            'date'        => $date,
            'category_id' => $this->cat->id,
        ]);
    }

    // =========================================================================
    // SummaryCards — method: getData()
    // =========================================================================

    /** @test */
    public function summary_tampil_data_bulan_ini(): void
    {
        $this->income(5_000_000,  now()->format('Y-m-d'));
        $this->expense(1_000_000, now()->format('Y-m-d'));

        $summary = Livewire::actingAs($this->user)
            ->test(SummaryCards::class)
            ->viewData('summary');

        $this->assertEquals(5_000_000, $summary['income']['current']);
        $this->assertEquals(1_000_000, $summary['expense']['current']);
        $this->assertEquals(4_000_000, $summary['balance']['current']);
    }

    /** @test */
    public function summary_tidak_tampil_data_bulan_lalu(): void
    {
        $this->income(9_000_000, now()->subMonth()->format('Y-m-d'));
        $this->income(1_000_000, now()->format('Y-m-d'));

        $summary = Livewire::actingAs($this->user)
            ->test(SummaryCards::class)
            ->viewData('summary');

        $this->assertEquals(1_000_000, $summary['income']['current']);
    }

    /** @test */
    public function summary_hasPrev_false_jika_tidak_ada_data_bulan_lalu(): void
    {
        $this->income(1_000_000, now()->format('Y-m-d'));

        $summary = Livewire::actingAs($this->user)
            ->test(SummaryCards::class)
            ->viewData('summary');

        $this->assertFalse($summary['income']['hasPrev']);
        $this->assertFalse($summary['expense']['hasPrev']);
        $this->assertFalse($summary['balance']['hasPrev']);
    }

    /** @test */
    public function summary_hasPrev_true_jika_ada_data_bulan_lalu(): void
    {
        $this->income(5_000_000, now()->format('Y-m-d'));
        $this->income(3_000_000, now()->subMonth()->format('Y-m-d'));

        $summary = Livewire::actingAs($this->user)
            ->test(SummaryCards::class)
            ->viewData('summary');

        $this->assertTrue($summary['income']['hasPrev']);
    }

    /** @test */
    public function summary_persentase_perubahan_dihitung_benar(): void
    {
        // Bulan lalu 4jt, bulan ini 5jt → naik 25%
        $this->income(5_000_000, now()->format('Y-m-d'));
        $this->income(4_000_000, now()->subMonth()->format('Y-m-d'));

        $summary = Livewire::actingAs($this->user)
            ->test(SummaryCards::class)
            ->viewData('summary');

        $this->assertEquals(25.0, $summary['income']['change']);
    }

    /** @test */
    public function summary_change_null_jika_bulan_lalu_nol(): void
    {
        // Ada data bulan lalu tapi income = 0, expense = 100rb
        $this->expense(100_000, now()->subMonth()->format('Y-m-d'));
        $this->income(1_000_000, now()->format('Y-m-d'));

        $summary = Livewire::actingAs($this->user)
            ->test(SummaryCards::class)
            ->viewData('summary');

        // income bulan lalu = 0, pct() return null
        $this->assertNull($summary['income']['change']);
    }

    /** @test */
    public function summary_tidak_tampil_data_user_lain(): void
    {
        $userLain = User::factory()->create();
        $catLain  = Category::factory()->create(['user_id' => $userLain->id]);

        Transaction::factory()->create([
            'user_id'     => $userLain->id,
            'type'        => 'income',
            'amount'      => 99_000_000,
            'date'        => now()->format('Y-m-d'),
            'category_id' => $catLain->id,
        ]);

        $this->income(1_000_000, now()->format('Y-m-d'));

        $summary = Livewire::actingAs($this->user)
            ->test(SummaryCards::class)
            ->viewData('summary');

        $this->assertEquals(1_000_000, $summary['income']['current']);
    }

    /** @test */
    public function summary_semua_nol_jika_tidak_ada_transaksi(): void
    {
        $summary = Livewire::actingAs($this->user)
            ->test(SummaryCards::class)
            ->viewData('summary');

        $this->assertEquals(0, $summary['income']['current']);
        $this->assertEquals(0, $summary['expense']['current']);
        $this->assertEquals(0, $summary['balance']['current']);
    }

    // =========================================================================
    // ExpenseChart — method: getChartData()
    // =========================================================================

    /** @test */
    public function chart_tampil_pengeluaran_bulan_ini(): void
    {
        $this->expense(300_000, now()->format('Y-m-d'));

        $chartData = Livewire::actingAs($this->user)
            ->test(ExpenseChart::class)
            ->viewData('chartData');

        $this->assertContains('Makanan', $chartData['labels']);
        $this->assertContains(300_000.0, $chartData['data']);
        $this->assertContains('#f97316', $chartData['colors']);
    }

    /** @test */
    public function chart_tidak_tampil_transaksi_income(): void
    {
        $this->income(5_000_000, now()->format('Y-m-d'));

        $chartData = Livewire::actingAs($this->user)
            ->test(ExpenseChart::class)
            ->viewData('chartData');

        $this->assertEmpty($chartData['data']);
    }

    /** @test */
    public function chart_tidak_tampil_data_bulan_lalu(): void
    {
        $this->expense(500_000, now()->subMonth()->format('Y-m-d'));

        $chartData = Livewire::actingAs($this->user)
            ->test(ExpenseChart::class)
            ->viewData('chartData');

        $this->assertEmpty($chartData['data']);
    }

    /** @test */
    public function chart_diurutkan_terbesar_ke_terkecil(): void
    {
        $catBelanja = Category::factory()->create([
            'user_id' => $this->user->id,
            'name'    => 'Belanja',
            'color'   => '#ef4444',
        ]);

        // Makanan: 100rb, Belanja: 500rb → Belanja harus di index 0
        Transaction::factory()->create([
            'user_id' => $this->user->id, 'type' => 'expense',
            'amount' => 100_000, 'date' => now()->format('Y-m-d'),
            'category_id' => $this->cat->id,
        ]);
        Transaction::factory()->create([
            'user_id' => $this->user->id, 'type' => 'expense',
            'amount' => 500_000, 'date' => now()->format('Y-m-d'),
            'category_id' => $catBelanja->id,
        ]);

        $chartData = Livewire::actingAs($this->user)
            ->test(ExpenseChart::class)
            ->viewData('chartData');

        $this->assertEquals('Belanja', $chartData['labels'][0]);
        $this->assertEquals(500_000.0, $chartData['data'][0]);
    }

    /** @test */
    public function chart_tidak_tampil_data_user_lain(): void
    {
        $userLain = User::factory()->create();
        $catLain  = Category::factory()->create(['user_id' => $userLain->id]);

        Transaction::factory()->create([
            'user_id'     => $userLain->id,
            'type'        => 'expense',
            'amount'      => 99_000_000,
            'date'        => now()->format('Y-m-d'),
            'category_id' => $catLain->id,
        ]);

        $chartData = Livewire::actingAs($this->user)
            ->test(ExpenseChart::class)
            ->viewData('chartData');

        $this->assertEmpty($chartData['data']);
    }

    /** @test */
    public function chart_fallback_warna_jika_kategori_tidak_punya_warna(): void
    {
        // Category tanpa warna (null)
        $catTanpaWarna = Category::factory()->create([
            'user_id' => $this->user->id,
            'name'    => 'Lainnya',
            'color'   => null,
        ]);

        Transaction::factory()->create([
            'user_id'     => $this->user->id,
            'type'        => 'expense',
            'amount'      => 100_000,
            'date'        => now()->format('Y-m-d'),
            'category_id' => $catTanpaWarna->id,
        ]);

        $chartData = Livewire::actingAs($this->user)
            ->test(ExpenseChart::class)
            ->viewData('chartData');

        // Fallback warna harus #6366f1
        $this->assertEquals('#6366f1', $chartData['colors'][0]);
    }

    // =========================================================================
    // Auth
    // =========================================================================

    /** @test */
    public function guest_diredirect_ke_login_dari_beranda(): void
    {
        $this->get(route('home'))
            ->assertRedirect(route('login'));
    }
}