<?php

namespace Tests\Feature\Livewire\Transactions;

use App\Livewire\Transactions\Index;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TransactionFilterTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Category $catMakan;
    private Category $catTransport;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user         = User::factory()->create();
        $this->catMakan     = Category::factory()->create(['user_id' => $this->user->id, 'name' => 'Makanan',      'color' => '#f97316']);
        $this->catTransport = Category::factory()->create(['user_id' => $this->user->id, 'name' => 'Transportasi', 'color' => '#3b82f6']);
    }

    private function income(int $amount, string $date, ?Category $cat = null): Transaction
    {
        return Transaction::factory()->create([
            'user_id'     => $this->user->id,
            'type'        => 'income',
            'amount'      => $amount,
            'date'        => $date,
            'category_id' => ($cat ?? $this->catMakan)->id,
        ]);
    }

    private function expense(int $amount, string $date, ?Category $cat = null): Transaction
    {
        return Transaction::factory()->create([
            'user_id'     => $this->user->id,
            'type'        => 'expense',
            'amount'      => $amount,
            'date'        => $date,
            'category_id' => ($cat ?? $this->catMakan)->id,
        ]);
    }

    // =========================================================================
    // 1. Load awal
    // =========================================================================

    /** @test */
    public function load_awal_filter_default_bulan_ini(): void
    {
        Livewire::actingAs($this->user)
            ->test(Index::class)
            ->assertSet('filterYear',  (string) date('Y'))
            ->assertSet('filterMonth', (string) date('n'))
            ->assertSet('startDate',   '')
            ->assertSet('endDate',     '');
    }

    /** @test */
    public function load_awal_hanya_tampilkan_transaksi_bulan_ini(): void
    {
        $this->income(1_000_000, now()->format('Y-m-d'));
        $this->income(999_000,   now()->subMonth()->format('Y-m-d')); // bulan lalu

        Livewire::actingAs($this->user)
            ->test(Index::class)
            ->assertSee('1.000.000')
            ->assertDontSee('999.000');
    }

    /** @test */
    public function tidak_tampil_transaksi_user_lain(): void
    {
        $userLain = User::factory()->create();
        $catLain  = Category::factory()->create(['user_id' => $userLain->id]);

        Transaction::factory()->create([
            'user_id'     => $userLain->id,
            'type'        => 'income',
            'amount'      => 99_999_999,
            'date'        => now()->format('Y-m-d'),
            'category_id' => $catLain->id,
        ]);

        $this->income(1_000_000, now()->format('Y-m-d'));

        Livewire::actingAs($this->user)
            ->test(Index::class)
            ->assertSee('1.000.000')
            ->assertDontSee('99.999.999');
    }

    // =========================================================================
    // 2. Filter Tahun & Bulan
    // =========================================================================

    /** @test */
    public function filter_tahun_menampilkan_data_tahun_itu(): void
    {
        $this->income(1_000_000, '2025-06-10');
        $this->income(2_000_000, '2024-06-10');

        Livewire::actingAs($this->user)
            ->test(Index::class)
            ->set('filterYear',  '2025')
            ->set('filterMonth', '')
            ->assertSee('1.000.000')
            ->assertDontSee('2.000.000');
    }

    /** @test */
    public function filter_bulan_menampilkan_data_bulan_itu(): void
    {
        $tahun = date('Y');
        $this->income(1_000_000, "{$tahun}-03-10");
        $this->income(2_000_000, "{$tahun}-07-10");

        Livewire::actingAs($this->user)
            ->test(Index::class)
            ->set('filterYear',  $tahun)
            ->set('filterMonth', '3')
            ->assertSee('1.000.000')
            ->assertDontSee('2.000.000');
    }

    /** @test */
    public function ubah_filter_tahun_mengosongkan_range_tanggal(): void
    {
        Livewire::actingAs($this->user)
            ->test(Index::class)
            ->set('startDate', '2025-01-01')
            ->set('endDate',   '2025-12-31')
            ->set('filterYear', '2024')
            ->assertSet('startDate', '')
            ->assertSet('endDate',   '');
    }

    /** @test */
    public function ubah_filter_bulan_mengosongkan_range_tanggal(): void
    {
        Livewire::actingAs($this->user)
            ->test(Index::class)
            ->set('startDate', '2025-01-01')
            ->set('endDate',   '2025-01-31')
            ->set('filterMonth', '6')
            ->assertSet('startDate', '')
            ->assertSet('endDate',   '');
    }

    // =========================================================================
    // 3. Filter Range Tanggal
    // =========================================================================

    /** @test */
    public function filter_range_tampilkan_data_dalam_range(): void
    {
        $this->income(1_000_000, '2025-03-10');
        $this->income(2_000_000, '2025-05-20');
        $this->income(3_000_000, '2025-07-01'); // di luar range

        Livewire::actingAs($this->user)
            ->test(Index::class)
            ->set('startDate', '2025-03-01')
            ->set('endDate',   '2025-05-31')
            ->assertSee('1.000.000')
            ->assertSee('2.000.000')
            ->assertDontSee('3.000.000');
    }

    /** @test */
    public function ubah_start_date_mengosongkan_filter_tahun_bulan(): void
    {
        Livewire::actingAs($this->user)
            ->test(Index::class)
            ->set('filterYear',  '2025')
            ->set('filterMonth', '6')
            ->set('startDate',   '2025-01-01')
            ->assertSet('filterYear',  '')
            ->assertSet('filterMonth', '');
    }

    /** @test */
    public function ubah_end_date_mengosongkan_filter_tahun_bulan(): void
    {
        Livewire::actingAs($this->user)
            ->test(Index::class)
            ->set('filterYear',  '2025')
            ->set('filterMonth', '6')
            ->set('endDate',     '2025-06-30')
            ->assertSet('filterYear',  '')
            ->assertSet('filterMonth', '');
    }

    /** @test */
    public function start_date_saja_tanpa_end_date_bekerja(): void
    {
        $this->income(1_000_000, '2025-06-15');
        $this->income(2_000_000, '2025-01-01');

        Livewire::actingAs($this->user)
            ->test(Index::class)
            ->set('startDate', '2025-06-01')
            ->assertSee('1.000.000')
            ->assertDontSee('2.000.000');
    }

    // =========================================================================
    // 4. Summary mengikuti filter
    // =========================================================================

    /** @test */
    public function summary_sesuai_filter_bulan_ini(): void
    {
        $this->income(5_000_000,  now()->format('Y-m-d'));
        $this->expense(1_000_000, now()->format('Y-m-d'));
        $this->income(9_000_000,  now()->subMonth()->format('Y-m-d')); // bulan lalu

        $component = Livewire::actingAs($this->user)->test(Index::class);
        $summary   = $component->get('summary');

        $this->assertEquals(5_000_000, $summary['income']);
        $this->assertEquals(1_000_000, $summary['expense']);
        $this->assertEquals(4_000_000, $summary['difference']);
    }

    /** @test */
    public function summary_ikut_filter_range_tanggal(): void
    {
        $this->income(5_000_000,  '2025-02-10');
        $this->expense(1_000_000, '2025-02-15');
        $this->income(9_000_000,  '2025-06-01'); // di luar range

        $component = Livewire::actingAs($this->user)
            ->test(Index::class)
            ->set('startDate', '2025-02-01')
            ->set('endDate',   '2025-02-28');

        $summary = $component->get('summary');
        $this->assertEquals(5_000_000, $summary['income']);
        $this->assertEquals(1_000_000, $summary['expense']);
        $this->assertEquals(4_000_000, $summary['difference']);
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
            ->test(Index::class)
            ->get('summary');

        $this->assertEquals(1_000_000, $summary['income']);
    }

    /** @test */
    public function summary_kosong_jika_tidak_ada_transaksi(): void
    {
        $summary = Livewire::actingAs($this->user)
            ->test(Index::class)
            ->get('summary');

        $this->assertEquals(0, $summary['income']);
        $this->assertEquals(0, $summary['expense']);
        $this->assertEquals(0, $summary['difference']);
    }

    // =========================================================================
    // 5. Filter Tipe
    // =========================================================================

    /** @test */
    public function filter_tipe_income_hanya_tampilkan_pemasukan(): void
    {
        $this->income(1_000_000,  now()->format('Y-m-d'));
        $this->expense(500_000,   now()->format('Y-m-d'));

        Livewire::actingAs($this->user)
            ->test(Index::class)
            ->set('filterType', 'income')
            ->assertSee('1.000.000')
            ->assertDontSee('500.000');
    }

    /** @test */
    public function filter_tipe_expense_hanya_tampilkan_pengeluaran(): void
    {
        $this->income(1_000_000, now()->format('Y-m-d'));
        $this->expense(500_000,  now()->format('Y-m-d'));

        Livewire::actingAs($this->user)
            ->test(Index::class)
            ->set('filterType', 'expense')
            ->assertDontSee('1.000.000')
            ->assertSee('500.000');
    }

    // =========================================================================
    // 6. Filter Kategori
    // =========================================================================

    /** @test */
    public function filter_kategori_hanya_tampilkan_kategori_tersebut(): void
    {
        $this->expense(300_000, now()->format('Y-m-d'), $this->catMakan);
        $this->expense(150_000, now()->format('Y-m-d'), $this->catTransport);

        Livewire::actingAs($this->user)
            ->test(Index::class)
            ->set('filterCategory', (string) $this->catMakan->id)
            ->assertSee('300.000')
            ->assertDontSee('150.000');
    }

    // =========================================================================
    // 7. Kombinasi filter
    // =========================================================================

    /** @test */
    public function kombinasi_range_tipe_kategori_bekerja_bersamaan(): void
    {
        $this->expense(200_000, '2025-03-10', $this->catMakan);     // lolos semua filter
        $this->expense(300_000, '2025-03-10', $this->catTransport); // gagal kategori
        $this->income(400_000,  '2025-03-10', $this->catMakan);     // gagal tipe
        $this->expense(500_000, '2025-07-10', $this->catMakan);     // gagal range

        Livewire::actingAs($this->user)
            ->test(Index::class)
            ->set('startDate',      '2025-03-01')
            ->set('endDate',        '2025-03-31')
            ->set('filterType',     'expense')
            ->set('filterCategory', (string) $this->catMakan->id)
            ->assertSee('200.000')
            ->assertDontSee('300.000')
            ->assertDontSee('400.000')
            ->assertDontSee('500.000');
    }

    // =========================================================================
    // 8. Reset Filter
    // =========================================================================

    /** @test */
    public function reset_filter_kembali_ke_bulan_ini(): void
    {
        Livewire::actingAs($this->user)
            ->test(Index::class)
            ->set('startDate',      '2024-01-01')
            ->set('endDate',        '2024-12-31')
            ->set('filterType',     'expense')
            ->set('filterCategory', (string) $this->catMakan->id)
            ->call('resetFilters')
            ->assertSet('startDate',      '')
            ->assertSet('endDate',        '')
            ->assertSet('filterType',     '')
            ->assertSet('filterCategory', '')
            ->assertSet('filterYear',     (string) date('Y'))
            ->assertSet('filterMonth',    (string) date('n'));
    }

    // =========================================================================
    // 9. Paginasi
    // =========================================================================

    /** @test */
    public function paginasi_halaman_2_tetap_pakai_filter_aktif(): void
    {
        // 15 transaksi bulan ini
        for ($i = 1; $i <= 15; $i++) {
            $this->income($i * 10_000, now()->format('Y-m-d'));
        }
        // 5 transaksi bulan lalu — tidak boleh ikut terhitung
        for ($i = 1; $i <= 5; $i++) {
            $this->income($i * 100_000, now()->subMonth()->format('Y-m-d'));
        }

        $component = Livewire::actingAs($this->user)->test(Index::class);

        $this->assertCount(10, $component->get('transactions')->items());

        $component->call('gotoPage', 2);
        $this->assertCount(5, $component->get('transactions')->items());
    }

    // =========================================================================
    // 10. Auth
    // =========================================================================

    /** @test */
    public function guest_diredirect_ke_login(): void
    {
        $this->get(route('transaction.index'))
            ->assertRedirect(route('login'));
    }
}