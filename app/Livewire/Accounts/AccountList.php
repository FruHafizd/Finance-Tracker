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
    public array $expandedGroups = [];

    protected AccountService $accountService;

    public function boot(AccountService $accountService): void
    {
        $this->accountService = $accountService;
    }

    public function mount(): void
    {
        $this->initializeExpandedGroups();
    }

    public function initializeExpandedGroups(): void
    {
        $accounts = $this->accountService->getUserAccounts();
        $types = ['tabungan', 'ewallet', 'tunai'];
        
        $this->expandedGroups = [];
        $firstGroupWithAccounts = null;
        
        foreach ($types as $type) {
            $hasAccounts = $accounts->where('type', $type)->isNotEmpty();
            $this->expandedGroups[$type] = false;
            
            if ($hasAccounts && $firstGroupWithAccounts === null) {
                $firstGroupWithAccounts = $type;
            }
        }
        
        // Open first group that has accounts
        if ($firstGroupWithAccounts !== null) {
            $this->expandedGroups[$firstGroupWithAccounts] = true;
        }
    }

    public function toggleGroup(string $type): void
    {
        $this->expandedGroups[$type] = !$this->expandedGroups[$type];
    }

    public function updatedSearch(): void
    {
        // Auto-expand groups that contain search results
        if (empty($this->search)) {
            $this->initializeExpandedGroups();
            return;
        }
        
        $accounts = $this->accountService->getFilteredAccounts(
            'semua',
            $this->search,
            $this->sortBy,
            $this->sortDir,
        );
        
        $types = ['tabungan', 'ewallet', 'tunai'];
        
        foreach ($types as $type) {
            $hasResults = $accounts->where('type', $type)->isNotEmpty();
            $this->expandedGroups[$type] = $hasResults;
        }
    }

    public function updatedActiveTab(): void
    {
        // When a specific tab is selected, expand that group only
        // When 'semua' is selected, reset to default accordion state
        if ($this->activeTab === 'semua') {
            $this->initializeExpandedGroups();
        } else {
            $types = ['tabungan', 'ewallet', 'tunai'];
            foreach ($types as $type) {
                $this->expandedGroups[$type] = ($type === $this->activeTab);
            }
        }
    }

    public function getAccountsProperty()
    {
        $accounts = $this->accountService->getFilteredAccounts(
            $this->activeTab,
            $this->search,
            $this->sortBy,
            $this->sortDir,
        );

        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        // Ambil transaksi user bulan berjalan
        $monthlyTransactions = \App\Models\Transaction::whereBetween('date', [$startOfMonth, $endOfMonth])->get();

        return $accounts->map(function ($account) use ($monthlyTransactions) {
            // Hitung jumlah transaksi bulan ini
            $thisMonthTrxs = $monthlyTransactions->filter(function ($t) use ($account) {
                return $t->account_id == $account->id || $t->to_account_id == $account->id;
            });
            $account->transactions_count_this_month = $thisMonthTrxs->count();

            // Hitung delta bulan ini
            $delta = 0;
            foreach ($thisMonthTrxs as $t) {
                if ($t->type === 'income' && $t->account_id == $account->id) {
                    $delta += $t->amount;
                } elseif ($t->type === 'expense' && $t->account_id == $account->id) {
                    $delta -= $t->amount;
                } elseif ($t->type === 'transfer') {
                    if ($t->account_id == $account->id) {
                        $delta -= $t->amount;
                    }
                    if ($t->to_account_id == $account->id) {
                        $delta += $t->amount;
                    }
                }
            }
            $account->monthly_change = $delta;

            // Transaksi terakhir
            $lastTrx = \App\Models\Transaction::where(function ($query) use ($account) {
                $query->where('account_id', $account->id)
                      ->orWhere('to_account_id', $account->id);
            })
            ->latest('date')
            ->latest('created_at')
            ->first();

            $account->last_transaction_time = $lastTrx ? $lastTrx->date : null;

            return $account;
        });
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

    public function getAccountsByTypeProperty(): array
    {
        $accounts = $this->accountService->getUserAccounts();
        
        $types = ['tabungan' => 'Tabungan', 'ewallet' => 'E-Wallet', 'tunai' => 'Tunai'];
        $result = [];
        
        foreach ($types as $typeKey => $typeLabel) {
            $typeAccounts = $accounts->where('type', $typeKey);
            $count = $typeAccounts->count();
            
            if ($count === 0) {
                $result[$typeKey] = 'Belum ada rekening';
            } else {
                $names = $typeAccounts->take(2)->pluck('name')->toArray();
                $namesStr = implode(', ', $names);
                if ($count > 2) {
                    $otherCount = $count - 2;
                    $namesStr .= ", +{$otherCount} lainnya";
                }
                
                $result[$typeKey] = "{$count} rekening · {$namesStr}";
            }
        }
        
        return $result;
    }

    public function getAccountPercentagesProperty(): array
    {
        return $this->accountService->getAccountPercentages();
    }

    public function getAccountsGroupedByTypeProperty(): array
    {
        $accounts = $this->accountService->getFilteredAccounts(
            $this->activeTab,
            $this->search,
            $this->sortBy,
            $this->sortDir,
        );

        // Apply the same monthly calculations as getAccountsProperty
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        $monthlyTransactions = \App\Models\Transaction::whereBetween('date', [$startOfMonth, $endOfMonth])->get();

        $accounts = $accounts->map(function ($account) use ($monthlyTransactions) {
            $thisMonthTrxs = $monthlyTransactions->filter(function ($t) use ($account) {
                return $t->account_id == $account->id || $t->to_account_id == $account->id;
            });
            $account->transactions_count_this_month = $thisMonthTrxs->count();

            $delta = 0;
            foreach ($thisMonthTrxs as $t) {
                if ($t->type === 'income' && $t->account_id == $account->id) {
                    $delta += $t->amount;
                } elseif ($t->type === 'expense' && $t->account_id == $account->id) {
                    $delta -= $t->amount;
                } elseif ($t->type === 'transfer') {
                    if ($t->account_id == $account->id) {
                        $delta -= $t->amount;
                    }
                    if ($t->to_account_id == $account->id) {
                        $delta += $t->amount;
                    }
                }
            }
            $account->monthly_change = $delta;

            $lastTrx = \App\Models\Transaction::where(function ($query) use ($account) {
                $query->where('account_id', $account->id)
                      ->orWhere('to_account_id', $account->id);
            })
            ->latest('date')
            ->latest('created_at')
            ->first();

            $account->last_transaction_time = $lastTrx ? $lastTrx->date : null;

            return $account;
        });

        // Group by type
        $types = ['tabungan', 'ewallet', 'tunai'];
        $result = [];

        foreach ($types as $type) {
            $typeAccounts = $accounts->where('type', $type);
            $result[$type] = [
                'accounts' => $typeAccounts->values(),
                'count' => $typeAccounts->count(),
                'total_balance' => $typeAccounts->sum('balance'),
            ];
        }

        return $result;
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
        $this->dispatch('open-modal', 'modal-delete-rekening');
    }

    public function deleteAccount(?int $id = null): void
    {
        if ($id) {
            $this->deleteId = $id;
        }
        $this->delete();
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