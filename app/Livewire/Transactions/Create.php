<?php

namespace App\Livewire\Transactions;

use App\Models\Transaction;
use Livewire\Component;
use App\Models\Category as Categories;

class Create extends Component
{
    protected $listeners = ['category-created' => 'loadCategories'];

    public $amount;
    public $type;
    public $date;
    public $name;
    public $categories = [];
    public $category_id;

    protected $rules = [
        'name' => 'required|string|min:3',
        'category_id' => 'required',
        'amount' => 'required|numeric|min:1',
        'type' => 'required|in:income,expense',
        'date' => 'required|date',
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

    public function save()  {

        $this->validate();

        Transaction::create([
            'user_id' => auth()->id(),
            'category_id' => $this->category_id,
            'amount' => $this->amount,
            'type' => $this->type,
            'date' => $this->date,
            'name' => $this->name
        ]);
        $this->reset(['amount', 'type', 'date', 'name', 'category_id']);
        $this->dispatch('close-modal', 'modal-create');
        $this->dispatch('transaction-created');
    }

    public function render()
    {
        return view('livewire.transactions.create');
    }
}
