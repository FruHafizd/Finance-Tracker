<?php

namespace App\Livewire\Transactions;

use App\Models\Transaction;
use Livewire\Component;

class Delete extends Component
{
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
        Transaction::where('id', $this->transactionId)
                            ->where('user_id', auth()->id())
                            ->delete();
        $this->dispatch('transaction-deleted');
        $this->dispatch('close-modal','modal-delete');
    }

    public function render()
    {
        return view('livewire.transactions.delete');
    }
}
