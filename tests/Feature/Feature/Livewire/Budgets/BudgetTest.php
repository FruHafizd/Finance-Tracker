<?php

namespace Tests\Feature\Livewire\Budgets;

use App\Livewire\Budgets\BudgetForm;
use App\Livewire\Budgets\BudgetIndex;
use App\Models\Budget;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class BudgetTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Category $cat;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->cat = Category::factory()->create(['user_id' => $this->user->id]);
    }

    #[Test]
    public function halaman_budget_bisa_diakses(): void
    {
        $this->actingAs($this->user)
            ->get(route('budget.index'))
            ->assertStatus(200);
    }

    #[Test]
    public function bisa_membuat_budget_baru(): void
    {
        Livewire::actingAs($this->user)
            ->test(BudgetForm::class)
            ->set('category_id', $this->cat->id)
            ->set('limit_amount', 1000000)
            ->call('save')
            ->assertDispatched('budget-created');

        $this->assertDatabaseHas('budgets', [
            'user_id' => $this->user->id,
            'category_id' => $this->cat->id,
            'limit_amount' => 1000000,
        ]);
    }

    #[Test]
    public function validasi_budget_wajib_diisi(): void
    {
        Livewire::actingAs($this->user)
            ->test(BudgetForm::class)
            ->set('category_id', 0)
            ->set('limit_amount', '')
            ->call('save')
            ->assertHasErrors([
                'category_id' => 'required',
                'limit_amount' => 'required',
            ]);
    }

    #[Test]
    public function budget_otomatis_terhapus_jika_id_ditemukan(): void
    {
        $budget = Budget::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->cat->id,
        ]);

        Livewire::actingAs($this->user)
            ->test(\App\Livewire\Budgets\Delete::class)
            ->call('setBudget', $budget->id)
            ->call('delete')
            ->assertDispatched('budget-deleted');

        $this->assertDatabaseMissing('budgets', ['id' => $budget->id]);
    }
}
