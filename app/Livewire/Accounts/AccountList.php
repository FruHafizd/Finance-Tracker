<?php

namespace App\Livewire\Accounts;

use App\Models\Account;
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

    public function getAccountsProperty()
    {
        return Account::query()
            ->when($this->activeTab !== 'semua', function ($query) {
                $query->where('type', $this->activeTab);
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('provider', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->get();
    }

    public function setSort(string $field): void
    {
        if ($this->sortBy === $field) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDir = 'asc';
        }
    }

    public function getSummaryProperty()
    {
        $all = Account::all();
        $totalNow = $all->sum('balance');

        $startOfMonth = now()->startOfMonth();
        $endOfMonth   = now()->endOfMonth();

        $incomeThisMonth = \App\Models\Transaction::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->where('type', 'income')
            ->sum('amount');

        $expenseThisMonth = \App\Models\Transaction::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->where('type', 'expense')
            ->sum('amount');

        $netChangeThisMonth = $incomeThisMonth - $expenseThisMonth;

        $types = ['tabungan', 'ewallet', 'tunai'];
        $summary = [
            'total'     => $totalNow,
            'netChange' => $netChangeThisMonth,
        ];

        foreach ($types as $type) {
            $accountIds = $all->where('type', $type)->pluck('id');
            $currentBalance = $all->where('type', $type)->sum('balance');

            $incomeType = \App\Models\Transaction::whereIn('account_id', $accountIds)
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->where('type', 'income')
                ->sum('amount');

            $expenseType = \App\Models\Transaction::whereIn('account_id', $accountIds)
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->where('type', 'expense')
                ->sum('amount');

            $summary[$type] = $currentBalance;
            $summary[$type . '_change'] = $incomeType - $expenseType;
        }

        return $summary;
    }

    public function getAccountPercentagesProperty(): array
    {
        $all = Account::all();
        $total = $all->sum('balance');

        if ($total <= 0) return [];

        return $all->mapWithKeys(function ($account) use ($total) {
            return [$account->id => round(($account->balance / $total) * 100, 1)];
        })->toArray();
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
        $this->dispatch('open-modal', 'modal-delete-rekening');
    }

    public function delete(): void
    {
        $account = Account::findOrFail($this->deleteId);

        if ($account->transactions()->exists() || \App\Models\Transaction::where('to_account_id', $this->deleteId)->exists()) {
            $this->notify('Gagal menghapus', "Rekening {$account->name} tidak dapat dihapus karena masih memiliki riwayat transaksi.", 'error');
            return;
        }

        $account->delete();

        $this->deleteId = null;
        $this->dispatch('close-modal', 'modal-delete-rekening');
        $this->notify('Rekening dihapus', "Rekening {$account->name} berhasil dihapus.", 'success');
    }

    public function render()
    {
        return view('livewire.accounts.account-list')->layout('layouts.app', ['title' => 'Rekening']);;
    }
}
