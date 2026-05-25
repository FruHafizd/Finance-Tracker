<?php

namespace App\Livewire\Home;

use App\Livewire\Concerns\RefreshesOnTransactionChange;
use App\Services\TransactionSummaryService;
use Livewire\Component;

class RecentTransactions extends Component
{
    use RefreshesOnTransactionChange;

    protected $listeners = []; // allows merge in trait

    public function render(TransactionSummaryService $service)
    {
        return view('livewire.home.recent-transactions', [
            'transactions' => $service->getRecentTransactions(5),
        ]);
    }
}
