<?php

namespace Tests\Feature\Livewire\Profile;

use App\Models\Budget;
use App\Models\Category;
use App\Models\Account;
use App\Models\FavoriteTransaction;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DeleteUserTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_bisa_dihapus_meskipun_punya_transaksi(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);
        $account = Account::factory()->create(['user_id' => $user->id]);

        // Buat transaksi dan favorite transaction
        Transaction::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'account_id' => $account->id,
        ]);

        FavoriteTransaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'name' => 'Test Favorite',
            'amount' => 50000,
            'type' => 'expense',
        ]);

        Budget::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);

        // Hapus user — tidak boleh ada exception
        $user->delete();

        // Verifikasi semua data milik user sudah terhapus
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('categories', ['user_id' => $user->id]);
        $this->assertDatabaseMissing('transactions', ['user_id' => $user->id]);
        $this->assertDatabaseMissing('favorite_transactions', ['user_id' => $user->id]);
        $this->assertDatabaseMissing('budgets', ['user_id' => $user->id]);
    }
}
