<?php

namespace App\Livewire\Transactions;
use App\Models\Category as Categories;
use App\Traits\WithNotifications;
use Livewire\Component;

class Category extends Component
{
    use WithNotifications;
    public $categories;
    public $name;
    public $color = '#6366f1';
    public string $errorMessage = '';

    public $editId = null;

    protected $rules = [
        'name' => 'required|min:2'
    ];

    protected $messages = [
        'name.required' => 'Nama kategori wajib diisi.',
        'name.min' => 'Nama kategori minimal berisi 2 karakter.',
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
        $this->notify('Berhasil!', 'Kategori berhasil ditambahkan.', 'success');
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
        $this->notify('Berhasil!', 'Kategori berhasil diperbarui.', 'success');
        $this->dispatch('category-created');
        $this->loadCategories();
    }

    public function delete($id)
    {
        $this->errorMessage = '';

        // Check for existing transactions or favorite transactions
        $hasTransactions = \App\Models\Transaction::where('category_id', $id)->exists();
        $hasFavorites    = \App\Models\FavoriteTransaction::where('category_id', $id)->exists();

        if ($hasTransactions || $hasFavorites) {
            $this->notify('Gagal!', 'Kategori ini masih digunakan oleh transaksi.', 'danger');
            $this->errorMessage = 'Kategori ini tidak dapat dihapus karena masih digunakan oleh transaksi.';
            return;
        }

        try {
            Categories::findOrFail($id)->delete();
            $this->notify('Dihapus!', 'Kategori berhasil dihapus.', 'success');
            $this->loadCategories();
        } catch (\Exception $e) {
            $this->notify('Gagal!', 'Terjadi kesalahan sistem.', 'danger');
            $this->errorMessage = 'Terjadi kesalahan, kategori gagal dihapus.';
        }
    }

    public function render()
    {
        return view('livewire.transactions.category');
    }
}
