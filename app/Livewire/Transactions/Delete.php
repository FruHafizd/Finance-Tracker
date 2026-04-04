<?php

namespace App\Livewire\Transactions;

use App\Models\Transaction;
use App\Traits\WithNotifications;
use Livewire\Component;

class Delete extends Component
{
    use WithNotifications;
    public $transactionId;

    protected $listeners = [
        'confirm-delete' => 'setTransaction',
        'close-delete-modal' => 'resetState',
    ];

    public function setTransaction($id) {
        $this->transactionId = $id;
    }

    public function resetState()
    {
        $this->reset('transactionId');
    }

    public function delete() {
        if (!$this->transactionId) {
            return;
        }

        $transaction = Transaction::where('id', $this->transactionId)
                            ->where('user_id', auth()->id())
                            ->first();

        if ($transaction) {
            $transaction->delete();
            $this->notify('Dihapus!', 'Data transaksi telah berhasil dihapus.', 'success');
            $this->dispatch('transaction-deleted');
        }

        $this->dispatch('close-modal','modal-delete');
    }

    public function render()
    {
        return view('livewire.transactions.delete');
    }
}
