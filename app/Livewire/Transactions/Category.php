<?php

namespace App\Livewire\Transactions;
use App\Models\Category as Categories;
use Livewire\Component;

class Category extends Component
{
    public $categories;
    public $name;
    public $color = '#6366f1';
    public string $errorMessage = '';

    public $editId = null;

    protected $rules = [
        'name' => 'required|min:2'
    ];

    public function mount()
    {
        $this->loadCategories();
    }

    public function loadCategories()
    {
        $this->categories = Categories::where('user_id', auth()->id())->get();
    }

    public function create()
    {
        $this->validate();

        Categories::create([
            'user_id' => auth()->id(),
            'name' => strip_tags($this->name),
            'color' => $this->color,
        ]);

        $this->reset(['name']);
        $this->dispatch('category-created');
        $this->loadCategories();
    }

    public function startEdit($id)
    {
        $category = Categories::find($id);

        $this->editId = $id;
        $this->name = $category->name;
        $this->color = $category->color;
    }

    public function update()
    {
        $this->validate();

        Categories::where('id', $this->editId)->update([
            'name' => $this->name,
            'color' => $this->color,
        ]);

        $this->reset(['name','editId']);
        $this->dispatch('category-created');
        $this->loadCategories();
    }

    public function delete($id)
    {
        $this->errorMessage = '';
        try {
            Categories::find($id)->delete();
            $this->loadCategories();
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '23000') {
                $this->errorMessage = 'Kategori ini tidak dapat dihapus karena masih digunakan oleh transaksi.';
            } else {
                $this->errorMessage = 'Terjadi kesalahan, kategori gagal dihapus.';
            }
        }
    }

    public function render()
    {
        return view('livewire.transactions.category');
    }
}
