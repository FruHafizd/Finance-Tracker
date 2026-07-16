<?php

namespace App\Livewire\Accounts;

use App\Exceptions\AccountHasTransactionsException;
use App\Services\AccountService;
use App\Traits\WithNotifications;
use Livewire\Component;

class AccountList extends Component
{
    use WithNotifications;
    protected $listeners = [
        'account-saved' => '$refresh',
        'transaction-created' => '$refresh',
        'transaction-deleted' => '$refresh',
        'transaction-updated' => '$refresh',
    ];

    public const LOW_BALANCE_THRESHOLD = 50000;

    public string $activeTab = 'semua';
    public string $search = '';
    public string $sortBy = 'sort_order';
    public string $sortDir = 'asc';
    public ?int $deleteId = null;

    protected AccountService $accountService;

    public function boot(AccountService $accountService): void
    {
        $this->accountService = $accountService;
    }

    public function getAccountsProperty()
    {
        return $this->accountService->getFilteredAccounts(
            $this->activeTab,
            $this->search,
            $this->sortBy,
            $this->sortDir,
        );
    }

    public function setSort(string $field): void
    {
        $allowedSortColumns = ['name', 'provider', 'balance', 'sort_order'];
        
        if (!in_array($field, $allowedSortColumns)) {
            return;
        }

        if ($this->sortBy === $field) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDir = 'asc';
        }
    }

    public function getSummaryProperty()
    {
        return $this->accountService->getMonthlySummary();
    }

    public function getAccountPercentagesProperty(): array
    {
        return $this->accountService->getAccountPercentages();
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
        $this->dispatch('open-modal', 'modal-delete-rekening');
    }

    public function delete(): void
    {
        $account = $this->accountService->findOrFail($this->deleteId);

        try {
            $this->accountService->deleteAccount($account->id);
        } catch (AccountHasTransactionsException $exception) {
            $this->notify('Gagal menghapus', $exception->getMessage(), 'error');
            return;
        }

        $this->deleteId = null;
        $this->dispatch('close-modal', 'modal-delete-rekening');
        $this->dispatch('account-deleted');
        $this->notify('Rekening dihapus', "Rekening {$account->name} berhasil dihapus.", 'success');
    }

    public function render()
    {
        return view('livewire.accounts.account-list')->layout('layouts.app', ['title' => 'Rekening']);
    }
}