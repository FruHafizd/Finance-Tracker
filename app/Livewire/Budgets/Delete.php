<?php

namespace App\Livewire\Budgets;

use App\Models\Budget;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;

class Delete extends Component
{
    public ?int $budgetId = null;
    public string $categoryName = '';

    #[On('delete-budget')]
    public function loadBudget(int $id): void
    {
        $budget = Budget::with('category')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        $this->budgetId      = $budget->id;
        $this->categoryName  = $budget->category->name;
    }

    public function delete(): void
    {
        Budget::where('user_id', Auth::id())
            ->findOrFail($this->budgetId)
            ->delete();

        $this->reset(['budgetId', 'categoryName']);

        $this->dispatch('close-modal', 'modal-budget-delete');
        $this->dispatch('budget-deleted',message: 'Budget berhasil dihapus!');
    }

    public function render()
    {
        return view('livewire.budgets.delete');
    }
}