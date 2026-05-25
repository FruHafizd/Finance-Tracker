<?php

namespace App\Livewire\Home;

use App\Livewire\Concerns\RefreshesOnTransactionChange;
use App\Services\BudgetService;
use Livewire\Component;

class BudgetOverview extends Component
{
    use RefreshesOnTransactionChange;

    protected function getListeners(): array
    {
        return array_merge($this->listeners ?? [], [
            'budget-created' => '$refresh',
            'budget-updated' => '$refresh',
            'budget-deleted' => '$refresh',
            'transaction-created' => '$refresh',
            'transaction-deleted' => '$refresh',
            'transaction-updated' => '$refresh',
        ]);
    }

    public function render(BudgetService $service)
    {
        return view('livewire.home.budget-overview', [
            'budgets' => $service->getCurrentMonthBudgets(),
        ]);
    }
}
