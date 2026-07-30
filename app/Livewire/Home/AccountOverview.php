<?php

namespace App\Livewire\Home;

use App\Services\AccountService;
use Livewire\Component;

class AccountOverview extends Component
{
    protected function getListeners(): array
    {
        return [
            'account-saved' => '$refresh',
            'account-deleted' => '$refresh',
            'transaction-created' => '$refresh',
            'transaction-deleted' => '$refresh',
            'transaction-updated' => '$refresh',
        ];
    }

    public function render(AccountService $service)
    {
        return view('livewire.home.account-overview', [
            'accounts' => $service->getUserAccounts(),
        ]);
    }
}
