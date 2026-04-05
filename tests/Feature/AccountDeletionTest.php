<?php

namespace Tests\Feature;

use App\Livewire\Accounts\AccountList;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AccountDeletionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_cannot_delete_account_with_existing_transactions()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $account = Account::factory()->create(['user_id' => $user->id]);
        
        // Create transaction linked to this account
        Transaction::factory()->create([
            'user_id'    => $user->id,
            'account_id' => $account->id,
        ]);

        Livewire::test(AccountList::class)
            ->call('confirmDelete', $account->id)
            ->assertSet('deleteId', $account->id)
            ->call('delete')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('accounts', ['id' => $account->id]);
    }

    /** @test */
    public function it_cannot_delete_account_with_incoming_transfer_transactions()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $fromAccount = Account::factory()->create(['user_id' => $user->id]);
        $toAccount   = Account::factory()->create(['user_id' => $user->id]);
        
        // Create transfer transaction where this account is the destination (to_account_id)
        Transaction::factory()->create([
            'user_id'       => $user->id,
            'account_id'    => $fromAccount->id,
            'to_account_id' => $toAccount->id,
            'type'          => 'transfer',
        ]);

        Livewire::test(AccountList::class)
            ->call('confirmDelete', $toAccount->id)
            ->call('delete');

        $this->assertDatabaseHas('accounts', ['id' => $toAccount->id]);
    }

    /** @test */
    public function it_can_delete_account_without_transactions()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $account = Account::factory()->create(['user_id' => $user->id]);

        Livewire::test(AccountList::class)
            ->call('confirmDelete', $account->id)
            ->call('delete')
            ->assertSet('deleteId', null);

        $this->assertDatabaseMissing('accounts', ['id' => $account->id]);
    }
}
