<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Category;
use App\Models\FavoriteTransaction;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class QuickTransactionAccountTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function favorite_transaction_stores_account_id()
    {
        $user     = User::factory()->create();
        $account  = Account::factory()->create(['user_id' => $user->id]);
        $category = Category::factory()->create(['user_id' => $user->id]);

        $fav = FavoriteTransaction::create([
            'user_id'     => $user->id,
            'name'        => 'Beli Makan',
            'amount'      => 50000,
            'type'        => 'expense',
            'category_id' => $category->id,
            'account_id'  => $account->id,
        ]);

        $this->assertDatabaseHas('favorite_transactions', [
            'id'         => $fav->id,
            'account_id' => $account->id,
        ]);
    }

    /** @test */
    public function save_now_creates_transaction_with_correct_account_id()
    {
        $user     = User::factory()->create();
        $account  = Account::factory()->create(['user_id' => $user->id]);
        $category = Category::factory()->create(['user_id' => $user->id]);

        $fav = FavoriteTransaction::create([
            'user_id'     => $user->id,
            'name'        => 'Beli Makan',
            'amount'      => 50000,
            'type'        => 'expense',
            'category_id' => $category->id,
            'account_id'  => $account->id,
        ]);

        Livewire::actingAs($user)
            ->test(\App\Livewire\Transactions\QuickTransaction::class)
            ->call('saveNow', $fav->id);

        $this->assertDatabaseHas('transactions', [
            'user_id'     => $user->id,
            'account_id'  => $account->id,
            'category_id' => $category->id,
            'name'        => 'Beli Makan',
            'amount'      => 50000,
        ]);
    }

    /** @test */
    public function prefill_dispatches_event_with_account_id()
    {
        $user     = User::factory()->create();
        $account  = Account::factory()->create(['user_id' => $user->id]);
        $category = Category::factory()->create(['user_id' => $user->id]);

        $fav = FavoriteTransaction::create([
            'user_id'     => $user->id,
            'name'        => 'Beli Makan',
            'amount'      => 50000,
            'type'        => 'expense',
            'category_id' => $category->id,
            'account_id'  => $account->id,
        ]);

        Livewire::actingAs($user)
            ->test(\App\Livewire\Transactions\QuickTransaction::class)
            ->call('prefill', $fav->id)
            ->assertDispatched('prefill-transaction', function ($event, $params) use ($account) {
                return $params['data']['account_id'] === $account->id;
            });
    }

    /** @test */
    public function update_favorite_saves_new_account_id()
    {
        $user        = User::factory()->create();
        $accountLama = Account::factory()->create(['user_id' => $user->id]);
        $accountBaru = Account::factory()->create(['user_id' => $user->id]);
        $category    = Category::factory()->create(['user_id' => $user->id]);

        $fav = FavoriteTransaction::create([
            'user_id'     => $user->id,
            'name'        => 'Beli Makan',
            'amount'      => 50000,
            'type'        => 'expense',
            'category_id' => $category->id,
            'account_id'  => $accountLama->id,
        ]);

        Livewire::actingAs($user)
            ->test(\App\Livewire\Transactions\QuickTransaction::class)
            ->call('editFavorite', $fav->id)
            ->set('editAccountId', $accountBaru->id)
            ->call('updateFavorite');

        $this->assertDatabaseHas('favorite_transactions', [
            'id'         => $fav->id,
            'account_id' => $accountBaru->id,
        ]);
    }

    /** @test */
    public function add_to_favorite_includes_account_id()
    {
        $user     = User::factory()->create();
        $account  = Account::factory()->create(['user_id' => $user->id]);
        $category = Category::factory()->create(['user_id' => $user->id]);
        
        $trx = Transaction::create([
            'user_id'     => $user->id,
            'account_id'  => $account->id,
            'category_id' => $category->id,
            'name'        => 'Gaji Bulanan',
            'amount'      => 5000000,
            'type'        => 'income',
            'date'        => now()->toDateString(),
        ]);

        Livewire::actingAs($user)
            ->test(\App\Livewire\Transactions\Index::class)
            ->call('addToFavorite', $trx->id);

        $this->assertDatabaseHas('favorite_transactions', [
            'user_id'    => $user->id,
            'account_id' => $account->id,
            'name'       => 'Gaji Bulanan',
            'amount'     => 5000000,
        ]);
    }
}
