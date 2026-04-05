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
        'transaction-deleted' => '$refresh',
        'transaction-updated' => '$refresh',
    ];

    public string $activeTab = 'semua';
    public ?int $deleteId = null;

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
