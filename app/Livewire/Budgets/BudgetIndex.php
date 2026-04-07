<?php

namespace App\Livewire\Budgets;

use App\Models\Budget;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class BudgetIndex extends Component
{   
    use \App\Traits\WithNotifications;

    public int $month;
    public int $year;
    public ?int $deleteId = null;
    // public string $flashMessage = '';

    protected $listeners = [
        'budget-created' => '$refresh',
        'budget-updated' => '$refresh',
        'budget-deleted' => '$refresh',
        'transaction-created' => '$refresh',
        'transaction-deleted' => '$refresh',
        'transaction-updated' => '$refresh',
    ];

    public function mount(): void
    {
        $this->month = (int) now()->format('n');
        $this->year  = (int) now()->format('Y');
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
        $this->dispatch('open-modal', 'modal-delete-budget');
    }

    public function delete(): void
    {
        $budget = Budget::where('user_id', Auth::id())
            ->findOrFail($this->deleteId);
        
        $budget->delete();

        $this->deleteId = null;
        $this->dispatch('close-modal', 'modal-delete-budget');
        $this->notify('Berhasil!', 'Budget berhasil dihapus!', 'success');
        $this->dispatch('budget-deleted');
    }

    public function render()
    {   
        $budgets = Budget::with('category')
            ->where('user_id', Auth::id())
            ->where('month', $this->month)
            ->where('year', $this->year)
            ->get();

        return view('livewire.budgets.budget-index', compact('budgets'))
            ->layout('layouts.app', ['title' => 'Budget Transaksi']);
    }
}