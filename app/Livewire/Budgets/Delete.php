<?php

namespace App\Livewire\Budgets;

use App\Models\Budget;
use App\Traits\WithNotifications;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class Delete extends Component
{
    use WithNotifications;
    public ?int $budgetId = null;
    public string $categoryName = '';

    #[On('delete-budget')]
    public function setBudget(int $id): void
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

        $this->notify('Berhasil!', 'Budget berhasil dihapus!', 'success');
        $this->dispatch('close-modal', 'modal-budget-delete');
        $this->dispatch('budget-deleted');
    }

    public function render()
    {
        return view('livewire.budgets.delete');
    }
}