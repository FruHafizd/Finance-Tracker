<?php

namespace Tests\Feature\Livewire\Accounts;

use App\Livewire\Accounts\AccountList;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DeleteAccountTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    #[Test]
    public function rekening_tanpa_transaksi_bisa_dihapus(): void
    {
        $account = Account::factory()->create(['user_id' => $this->user->id]);

        Livewire::actingAs($this->user)
            ->test(AccountList::class)
            ->call('deleteAccount', $account->id)
            ->assertDispatched('notify', function ($name, $params) {
                return $params['type'] === 'success';
            });

        $this->assertDatabaseMissing('accounts', ['id' => $account->id]);
    }

    #[Test]
    public function rekening_dengan_transaksi_as_source_tidak_bisa_dihapus(): void
    {
        $account = Account::factory()->create(['user_id' => $this->user->id]);
        $category = Category::factory()->create(['user_id' => $this->user->id]);
        
        Transaction::factory()->create([
            'user_id' => $this->user->id,
            'account_id' => $account->id,
            'category_id' => $category->id,
        ]);

        Livewire::actingAs($this->user)
            ->test(AccountList::class)
            ->call('deleteAccount', $account->id)
            ->assertDispatched('notify', function ($name, $params) {
                return $params['type'] === 'error' && str_contains($params['message'], 'tidak dapat dihapus');
            });

        $this->assertDatabaseHas('accounts', ['id' => $account->id]);
    }

    #[Test]
    public function rekening_dengan_transaksi_as_destination_tidak_bisa_dihapus(): void
    {
        $sourceAccount = Account::factory()->create(['user_id' => $this->user->id]);
        $destAccount = Account::factory()->create(['user_id' => $this->user->id]);
        $category = Category::factory()->create(['user_id' => $this->user->id]);
        
        Transaction::factory()->create([
            'user_id' => $this->user->id,
            'account_id' => $sourceAccount->id,
            'to_account_id' => $destAccount->id,
            'category_id' => $category->id,
            'type' => 'transfer',
        ]);

        Livewire::actingAs($this->user)
            ->test(AccountList::class)
            ->call('deleteAccount', $destAccount->id)
            ->assertDispatched('notify', function ($name, $params) {
                return $params['type'] === 'error' && str_contains($params['message'], 'tidak dapat dihapus');
            });

        $this->assertDatabaseHas('accounts', ['id' => $destAccount->id]);
    }
}
