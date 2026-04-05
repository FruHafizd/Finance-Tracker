<?php

namespace Tests\Feature;

use App\Livewire\Transactions\Category;
use App\Models\Budget;
use App\Models\Category as CategoryModel;
use App\Models\FavoriteTransaction;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CategoryDeletionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_cannot_delete_category_with_existing_transactions()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $category = CategoryModel::factory()->create(['user_id' => $user->id]);
        
        // Create transaction linked to this category
        Transaction::factory()->create([
            'user_id'     => $user->id,
            'category_id' => $category->id,
        ]);

        Livewire::test(Category::class)
            ->call('delete', $category->id)
            ->assertSet('errorMessage', 'Kategori ini tidak dapat dihapus karena masih digunakan oleh transaksi atau budget.');

        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    /** @test */
    public function it_cannot_delete_category_with_existing_favorite_transactions()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $category = CategoryModel::factory()->create(['user_id' => $user->id]);
        
        // Create favorite transaction linked to this category using create()
        FavoriteTransaction::create([
            'user_id'     => $user->id,
            'category_id' => $category->id,
            'name'        => 'Nasi Padang',
            'amount'      => 20000,
            'type'        => 'expense',
        ]);

        Livewire::test(Category::class)
            ->call('delete', $category->id)
            ->assertSet('errorMessage', 'Kategori ini tidak dapat dihapus karena masih digunakan oleh transaksi atau budget.');

        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    /** @test */
    public function it_can_delete_category_without_transactions()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $category = CategoryModel::factory()->create(['user_id' => $user->id]);

        Livewire::test(Category::class)
            ->call('delete', $category->id)
            ->assertSet('errorMessage', '');

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    /** @test */
    public function it_cannot_delete_category_with_existing_budget()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $category = CategoryModel::factory()->create(['user_id' => $user->id]);

        // Create budget linked to this category
        Budget::factory()->create([
            'user_id'     => $user->id,
            'category_id' => $category->id,
            'month'       => now()->month,
            'year'        => now()->year,
        ]);

        Livewire::test(Category::class)
            ->call('delete', $category->id)
            ->assertSet('errorMessage', 'Kategori ini tidak dapat dihapus karena masih digunakan oleh transaksi atau budget.');

        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }
}
