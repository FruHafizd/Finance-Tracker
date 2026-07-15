<?php

namespace App\Livewire\Budgets;

use App\Services\BudgetService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class BudgetIndex extends Component
{   
    use \App\Traits\WithNotifications;

    public int $month;
    public int $year;
    public ?int $deleteId = null;

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

    public function delete(BudgetService $service): void
    {
        $service->deleteBudget($this->deleteId, Auth::id());

        $this->deleteId = null;
        $this->dispatch('close-modal', 'modal-delete-budget');
        $this->notify('Berhasil!', 'Budget berhasil dihapus!', 'success');
        $this->dispatch('budget-deleted');
    }

    public function render(BudgetService $service)
    {   
        $budgets = $service->getBudgetsForUser(Auth::id(), $this->month, $this->year);
        $exceededBudgets = $service->getExceededBudgets(Auth::id());

        return view('livewire.budgets.budget-index', compact('budgets', 'exceededBudgets'))
            ->layout('layouts.app', ['title' => 'Budget Transaksi']);
    }
}
