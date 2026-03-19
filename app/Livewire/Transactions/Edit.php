<?php

namespace App\Livewire\Transactions;

use App\Models\Transaction;
use Livewire\Component;
use App\Models\Category as Categories;

class Edit extends Component
{
    public $transactionsId;
    public $amount;
    public $type;
    public $date;
    public $name;
    public $categories = [];
    public $category_id;

    protected $listeners = [
        'edit-transaction' => 'loadTransaction',
        'close-edit-modal' => 'resetForm',
        'category-created' => 'loadCategories'
    ];

    protected $rules = [
        'amount' => 'required|numeric|min:1',
        'type' => 'required|in:income,expense',
        'date' => 'required|date',
        'name' => 'required|string|min:3',
        'category_id' => 'required'
    ];

    protected $messages = [
        'amount.required' => 'Jumlah tidak boleh kosong',
        'amount.numeric' => 'Jumlah harus berupa angka',
        'type.required' => 'Type tidak boleh kosong',
        'date.required' => 'Tanggal tidak boleh kosong',
        'name.required' => 'Nama tidak boleh kosong',
        'type.in' => 'Type tidak valid',
        'date.date' => 'Format tanggal tidak valid',
        'name.min' => 'Nama minimal 3 karakter',
        'category_id'=> 'Kategori tidak boleh kosong'
    ];

    public function loadCategories()
    {
        $this->categories = Categories::where('user_id', auth()->id())->get();
    }

    public function mount()
    {
        $this->categories = Categories::where('user_id', auth()->id())->get();
    }

    public function loadTransaction($id)  {

        $transaction = Transaction::where('id',$id)
                        ->where('user_id', auth()->id())
                        ->firstOrFail();
        $this->transactionsId = $transaction->id;
        $this->amount = $transaction->amount;
        $this->type = $transaction->type;
        $this->date = $transaction->date->format('Y-m-d');
        $this->name = $transaction->name;
        $this->category_id = $transaction->category_id;
    }

    public function resetForm()  {
        $this->reset([
            'transactionsId',
            'amount',
            'type',
            'date',
            'name',
            'category_id'
        ]);
    }

    public function update()  {
        if (!$this->transactionsId) {
            return;
        }

        $this->validate();

        Transaction::where('id', $this->transactionsId)
                    ->where('user_id', auth()->id())
                    ->update([
                        'amount' => $this->amount,
                        'type' => $this->type,
                        'date' => $this->date,
                        'name' => $this->name,
                        'category_id' => $this->category_id,
                    ]);
        $this->resetForm();
        $this->dispatch('transaction-updated');
        $this->dispatch('close-modal','modal-edit');
    }

    public function render()
    {
        return view('livewire.transactions.edit');
    }
}
