<?php

namespace Tests\Feature\Livewire\Transactions;

use App\Livewire\Transactions\Delete;
use App\Livewire\Transactions\TransactionForm;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TransactionCrudTest extends TestCase
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

    // =========================================================================
    // Create — transactionId null → mode create
    // =========================================================================

    #[Test]
    public function bisa_tambah_transaksi_income(): void
    {
        Livewire::actingAs($this->user)
            ->test(TransactionForm::class)
            ->set('name',        'Gaji Bulanan')
            ->set('amount',      5_000_000)
            ->set('type',        'income')
            ->set('category_id', $this->cat->id)
            ->set('date',        now()->format('Y-m-d'))
            ->call('save')
            ->assertDispatched('transaction-created');

        $this->assertDatabaseHas('transactions', [
            'user_id' => $this->user->id,
            'name'    => 'Gaji Bulanan',
            'amount'  => 5_000_000,
            'type'    => 'income',
        ]);
    }

    #[Test]
    public function bisa_tambah_transaksi_expense(): void
    {
        Livewire::actingAs($this->user)
            ->test(TransactionForm::class)
            ->set('name',        'Makan Siang')
            ->set('amount',      50_000)
            ->set('type',        'expense')
            ->set('category_id', $this->cat->id)
            ->set('date',        now()->format('Y-m-d'))
            ->call('save')
            ->assertDispatched('transaction-created');

        $this->assertDatabaseHas('transactions', [
            'user_id' => $this->user->id,
            'name'    => 'Makan Siang',
            'type'    => 'expense',
        ]);
    }

    #[Test]
    public function setelah_save_form_direset(): void
    {
        Livewire::actingAs($this->user)
            ->test(TransactionForm::class)
            ->set('name',        'Gaji')
            ->set('amount',      5_000_000)
            ->set('type',        'income')
            ->set('category_id', $this->cat->id)
            ->set('date',        now()->format('Y-m-d'))
            ->call('save')
            ->assertSet('name',          '')
            ->assertSet('amount',        '')
            ->assertSet('type',          '')
            ->assertSet('category_id',   '')
            ->assertSet('transactionId', null);
    }

    #[Test]
    public function validasi_name_wajib_diisi(): void
    {
        Livewire::actingAs($this->user)
            ->test(TransactionForm::class)
            ->set('name',        '')
            ->set('amount',      50_000)
            ->set('type',        'expense')
            ->set('category_id', $this->cat->id)
            ->set('date',        now()->format('Y-m-d'))
            ->call('save')
            ->assertHasErrors(['name' => 'required']);
    }

    #[Test]
    public function validasi_name_minimal_3_karakter(): void
    {
        Livewire::actingAs($this->user)
            ->test(TransactionForm::class)
            ->set('name',        'AB')
            ->set('amount',      50_000)
            ->set('type',        'expense')
            ->set('category_id', $this->cat->id)
            ->set('date',        now()->format('Y-m-d'))
            ->call('save')
            ->assertHasErrors(['name' => 'min']);
    }

    #[Test]
    public function validasi_amount_wajib_diisi(): void
    {
        Livewire::actingAs($this->user)
            ->test(TransactionForm::class)
            ->set('name',        'Test Transaksi')
            ->set('amount',      '')
            ->set('type',        'expense')
            ->set('category_id', $this->cat->id)
            ->set('date',        now()->format('Y-m-d'))
            ->call('save')
            ->assertHasErrors(['amount' => 'required']);
    }

    #[Test]
    public function validasi_amount_minimal_1(): void
    {
        Livewire::actingAs($this->user)
            ->test(TransactionForm::class)
            ->set('name',        'Test Transaksi')
            ->set('amount',      0)
            ->set('type',        'expense')
            ->set('category_id', $this->cat->id)
            ->set('date',        now()->format('Y-m-d'))
            ->call('save')
            ->assertHasErrors(['amount' => 'min']);
    }

    #[Test]
    public function validasi_kategori_wajib_dipilih(): void
    {
        Livewire::actingAs($this->user)
            ->test(TransactionForm::class)
            ->set('name',        'Test Transaksi')
            ->set('amount',      50_000)
            ->set('type',        'expense')
            ->set('category_id', '')
            ->set('date',        now()->format('Y-m-d'))
            ->call('save')
            ->assertHasErrors(['category_id']);
    }

    #[Test]
    public function validasi_tipe_harus_income_atau_expense(): void
    {
        Livewire::actingAs($this->user)
            ->test(TransactionForm::class)
            ->set('name',        'Test Transaksi')
            ->set('amount',      50_000)
            ->set('type',        'invalid')
            ->set('category_id', $this->cat->id)
            ->set('date',        now()->format('Y-m-d'))
            ->call('save')
            ->assertHasErrors(['type' => 'in']);
    }

    #[Test]
    public function validasi_tanggal_wajib_diisi(): void
    {
        Livewire::actingAs($this->user)
            ->test(TransactionForm::class)
            ->set('name',        'Test Transaksi')
            ->set('amount',      50_000)
            ->set('type',        'expense')
            ->set('category_id', $this->cat->id)
            ->set('date',        '')
            ->call('save')
            ->assertHasErrors(['date' => 'required']);
    }

    // =========================================================================
    // Edit — transactionId ada → mode edit
    // =========================================================================

    #[Test]
    public function bisa_load_transaksi_untuk_diedit(): void
    {
        $trx = Transaction::factory()->create([
            'user_id'     => $this->user->id,
            'name'        => 'Nama Asli',
            'amount'      => 100_000,
            'type'        => 'expense',
            'date'        => now()->format('Y-m-d'),
            'category_id' => $this->cat->id,
        ]);

        Livewire::actingAs($this->user)
            ->test(TransactionForm::class)
            ->call('openEdit', $trx->id)
            ->assertSet('transactionId', $trx->id)
            ->assertSet('name',          'Nama Asli')
            ->assertSet('amount',        100_000)
            ->assertSet('type',          'expense');
    }

    #[Test]
    public function bisa_update_transaksi(): void
    {
        $trx = Transaction::factory()->create([
            'user_id'     => $this->user->id,
            'name'        => 'Nama Lama',
            'amount'      => 100_000,
            'type'        => 'expense',
            'date'        => now()->format('Y-m-d'),
            'category_id' => $this->cat->id,
        ]);

        Livewire::actingAs($this->user)
            ->test(TransactionForm::class)
            ->call('openEdit', $trx->id)
            ->set('name',   'Nama Baru')
            ->set('amount', 200_000)
            ->call('save')                          // bukan update() lagi
            ->assertDispatched('transaction-updated');

        $this->assertDatabaseHas('transactions', [
            'id'     => $trx->id,
            'name'   => 'Nama Baru',
            'amount' => 200_000,
        ]);
    }

    #[Test]
    public function tidak_bisa_update_tanpa_load_transaksi_dulu(): void
    {
        // transactionId masih null → save() akan masuk mode create, bukan update
        // jadi kita pastikan tidak ada dispatch transaction-updated
        Livewire::actingAs($this->user)
            ->test(TransactionForm::class)
            ->set('name',        'Coba Update')
            ->set('amount',      100_000)
            ->set('type',        'expense')
            ->set('category_id', $this->cat->id)
            ->set('date',        now()->format('Y-m-d'))
            ->call('save')
            ->assertNotDispatched('transaction-updated')
            ->assertDispatched('transaction-created');
    }

    #[Test]
    public function tidak_bisa_load_transaksi_user_lain(): void
    {
        $userLain = User::factory()->create();
        $catLain  = Category::factory()->create(['user_id' => $userLain->id]);

        $trx = Transaction::factory()->create([
            'user_id'     => $userLain->id,
            'name'        => 'Milik User Lain',
            'amount'      => 100_000,
            'type'        => 'expense',
            'date'        => now()->format('Y-m-d'),
            'category_id' => $catLain->id,
        ]);

        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        Livewire::actingAs($this->user)
            ->test(TransactionForm::class)
            ->call('openEdit', $trx->id);
    }

    #[Test]
    public function validasi_edit_name_minimal_3_karakter(): void
    {
        $trx = Transaction::factory()->create([
            'user_id'     => $this->user->id,
            'name'        => 'Nama Valid',
            'amount'      => 100_000,
            'type'        => 'expense',
            'date'        => now()->format('Y-m-d'),
            'category_id' => $this->cat->id,
        ]);

        Livewire::actingAs($this->user)
            ->test(TransactionForm::class)
            ->call('openEdit', $trx->id)
            ->set('name', 'AB')
            ->call('save')
            ->assertHasErrors(['name' => 'min']);
    }

    #[Test]
    public function reset_form_mengosongkan_semua_field(): void
    {
        $trx = Transaction::factory()->create([
            'user_id'     => $this->user->id,
            'name'        => 'Test',
            'amount'      => 100_000,
            'type'        => 'expense',
            'date'        => now()->format('Y-m-d'),
            'category_id' => $this->cat->id,
        ]);

        Livewire::actingAs($this->user)
            ->test(TransactionForm::class)
            ->call('openEdit', $trx->id)
            ->call('resetForm')
            ->assertSet('transactionId', null)  // null bukan '' karena typed ?int
            ->assertSet('name',          '')
            ->assertSet('amount',        '')
            ->assertSet('category_id',   '');
    }

    // =========================================================================
    // Delete — tidak berubah
    // =========================================================================

    #[Test]
    public function bisa_hapus_transaksi_sendiri(): void
    {
        $trx = Transaction::factory()->create([
            'user_id'     => $this->user->id,
            'name'        => 'Mau Dihapus',
            'amount'      => 50_000,
            'type'        => 'expense',
            'date'        => now()->format('Y-m-d'),
            'category_id' => $this->cat->id,
        ]);

        Livewire::actingAs($this->user)
            ->test(Delete::class)
            ->call('setTransaction', $trx->id)
            ->call('delete')
            ->assertDispatched('transaction-deleted');

        $this->assertDatabaseMissing('transactions', ['id' => $trx->id]);
    }

    #[Test]
    public function tidak_bisa_hapus_transaksi_user_lain(): void
    {
        $userLain = User::factory()->create();
        $catLain  = Category::factory()->create(['user_id' => $userLain->id]);

        $trx = Transaction::factory()->create([
            'user_id'     => $userLain->id,
            'amount'      => 50_000,
            'type'        => 'expense',
            'date'        => now()->format('Y-m-d'),
            'category_id' => $catLain->id,
        ]);

        Livewire::actingAs($this->user)
            ->test(Delete::class)
            ->call('setTransaction', $trx->id)
            ->call('delete');

        $this->assertDatabaseHas('transactions', ['id' => $trx->id]);
    }

    #[Test]
    public function delete_tanpa_set_transaction_tidak_melakukan_apapun(): void
    {
        Transaction::factory()->create([
            'user_id'     => $this->user->id,
            'amount'      => 50_000,
            'type'        => 'expense',
            'date'        => now()->format('Y-m-d'),
            'category_id' => $this->cat->id,
        ]);

        Livewire::actingAs($this->user)
            ->test(Delete::class)
            ->call('delete');

        $this->assertDatabaseCount('transactions', 1);
    }
}