<?php

namespace App\Livewire\Budgets;

use App\Models\Budget;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;

class Edit extends Component
{
    public ?int $budgetId = null;
    public int $category_id = 0;
    public string $limit_amount = '';
    public string $categoryName = '';

    protected $rules = [
        'limit_amount' => 'required|numeric|min:1',
    ];

    protected $messages = [
        'limit_amount.required' => 'Batas pengeluaran wajib diisi.',
        'limit_amount.numeric'  => 'Batas pengeluaran harus berupa angka.',
        'limit_amount.min'      => 'Batas pengeluaran minimal Rp 1.',
    ];
    
    #[On('edit-budget')]
    public function loadBudget(int $id): void
    {
        $budget = Budget::with('category')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        $this->budgetId     = $budget->id;
        $this->category_id  = $budget->category_id;
        $this->limit_amount = (string) $budget->limit_amount;
        $this->categoryName = $budget->category->name;

        $this->resetErrorBag();
    }

    public function update(): void
    {
        $this->validate();

        Budget::where('user_id', Auth::id())
            ->findOrFail($this->budgetId)
            ->update([
                'limit_amount' => (int) $this->limit_amount,
            ]);

        $this->reset(['budgetId', 'limit_amount', 'categoryName']);

        $this->dispatch('close-modal', 'modal-budget-edit');
        $this->dispatch('budget-updated');

        session()->flash('success', 'Budget berhasil diperbarui!');
    }

    public function render()
    {
        return view('livewire.budgets.edit');
    }
}