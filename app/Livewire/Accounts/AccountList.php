<?php

namespace App\Livewire\Accounts;

use App\Models\Account;
use Livewire\Component;

class AccountList extends Component
{
    protected $listeners = [
        'account-saved' => '$refresh',
        'transaction-deleted' => '$refresh',
        'transaction-updated' => '$refresh',
    ];

    public string $activeTab = 'semua';

    public function getAccountsProperty()
    {
        return Account::query()
            ->when($this->activeTab !== 'semua', function ($query) {
                $query->where('type', $this->activeTab);
            })
            ->orderBy('sort_order')
            ->get();
    }

    public function getSummaryProperty()
    {
        $all = Account::all();

        return [
            'total'    => $all->sum('balance'),
            'tabungan' => $all->where('type', 'tabungan')->sum('balance'),
            'ewallet'  => $all->where('type', 'ewallet')->sum('balance'),
            'tunai'    => $all->where('type', 'tunai')->sum('balance'),
        ];
    }

    public function deleteAccount(int $id): void
    {
        $account = Account::findOrFail($id);
        $account->delete();

        $this->dispatch('notify', [
            'type'    => 'success',
            'title'   => 'Rekening dihapus',
            'message' => "Rekening {$account->name} berhasil dihapus.",
        ]);
    }
    public function render()
    {
        return view('livewire.accounts.account-list')->layout('layouts.app', ['title' => 'Rekening']);;
    }
}
