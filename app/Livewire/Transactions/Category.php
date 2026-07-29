<?php

namespace App\Livewire\Transactions;
use App\Services\CategoryService;
use App\Traits\WithNotifications;
use Livewire\Component;

class Category extends Component
{
    use WithNotifications;
    public $categories;
    public $name;
    public $color = '#6366f1';
    public $type = 'expense';
    public string $errorMessage = '';

    public $editId = null;

    protected $rules = [
        'name' => 'required|min:2',
        'type' => 'required|in:income,expense',
    ];

    protected $messages = [
        'name.required' => 'Nama kategori wajib diisi.',
        'name.min' => 'Nama kategori minimal berisi 2 karakter.',
        'type.required' => 'Jenis kategori wajib dipilih.',
        'type.in' => 'Jenis kategori tidak valid.',
    ];

    public function mount()
    {
        $this->loadCategories();
    }

    public function loadCategories()
    {
        $this->categories = app(CategoryService::class)->getAllByUser(auth()->id());
    }

    public function create()
    {
        $this->validate();

        app(CategoryService::class)->createCategory([
            'name'  => $this->name,
            'color' => $this->color,
            'type'  => $this->type,
        ], auth()->id());

        $this->reset(['name', 'type']);
        $this->type = 'expense'; // restore default
        $this->notify('Berhasil!', 'Kategori berhasil ditambahkan.', 'success');
        $this->dispatch('category-created');
        $this->loadCategories();
    }

    public function startEdit($id)
    {
        $category = app(CategoryService::class)->findById($id);

        if ($category->isSystem()) {
            $this->errorMessage = 'Kategori sistem tidak dapat diedit.';
            $this->notify('Gagal!', $this->errorMessage, 'danger');
            return;
        }

        $this->editId = $id;
        $this->name = $category->name;
        $this->color = $category->color;
        $this->type = $category->type ?? 'expense';
    }

    public function update()
    {
        $this->validate();

        app(CategoryService::class)->updateCategory($this->editId, [
            'name'  => $this->name,
            'color' => $this->color,
            'type'  => $this->type,
        ]);

        $this->reset(['name', 'type', 'editId']);
        $this->type = 'expense'; // restore default
        $this->notify('Berhasil!', 'Kategori berhasil diperbarui.', 'success');
        $this->dispatch('category-created');
        $this->loadCategories();
    }

    public function delete($id)
    {
        $this->errorMessage = '';

        $result = app(CategoryService::class)->deleteCategory($id, auth()->id());

        if (! $result['success']) {
            $this->errorMessage = $result['message'];
            $this->notify('Gagal!', $this->errorMessage, 'danger');
            return;
        }

        $this->notify('Dihapus!', $result['message'], 'success');
        $this->loadCategories();
    }

    public function render()
    {
        return view('livewire.transactions.category');
    }
}
