<?php

namespace App\Livewire\Transactions;

use App\Models\Category;
use App\Models\FavoriteTransaction;
use App\Models\Transaction;
use App\Traits\WithNotifications;
use Livewire\Attributes\Validate;
use Livewire\Component;

class QuickTransaction extends Component
{
    use WithNotifications;
    public $favorites = [];
    public $categories = [];
    public ?int $deleteId = null;
    
    // Properties for editing favorite transaction
    public $editId = null;
    
    #[Validate('required|string|min:3', as: 'nama transaksi')]
    public $editName = '';
    
    #[Validate('required|numeric|min:1', as: 'nominal')]
    public $editAmount = '';
    
    #[Validate('required|in:income,expense', as: 'tipe')]
    public $editType = 'expense';
    
    #[Validate('required', as: 'kategori')]
    public $editCategoryId = '';

    #[Validate('required', as: 'rekening')]
    public $editAccountId = '';

    public $accounts = [];

    protected $listeners = [
        'favorite-created' => 'loadFavorites',
        'category-created' => 'loadCategories'
    ];

    public function mount()
    {
        $this->loadCategories();
        $this->loadAccounts();
        $this->loadFavorites();
    }

    public function loadAccounts()
    {
        $this->accounts = \App\Models\Account::where('user_id', auth()->id())->get();
    }

    public function loadCategories()
    {
        $this->categories = Category::where('user_id', auth()->id())->get();
    }

    public function loadFavorites()
    {
        $this->favorites = FavoriteTransaction::with(['category', 'account'])->get();
    }

    // 1-click langsung save, date = hari ini
    public function saveNow(int $favoriteId)
    {
        $fav = FavoriteTransaction::findOrFail($favoriteId);

        Transaction::create([
            'user_id'     => auth()->id(),
            'category_id' => $fav->category_id,
            'account_id'  => $fav->account_id,
            'name'        => $fav->name,
            'amount'      => $fav->amount,
            'type'        => $fav->type,
            'date'        => now()->toDateString(),
        ]);

        $this->dispatch('transaction-created');
        $this->notify('Transaksi Sukses!', '1 Transaksi cepat berhasil ditambahkan ke catatan.', 'success');
    }

    // Kirim data ke modal Create supaya pre-filled
    public function prefill(int $favoriteId)
    {
        $fav = FavoriteTransaction::findOrFail($favoriteId);

        $this->dispatch('prefill-transaction', data: [
            'name'        => $fav->name,
            'amount'      => $fav->amount,
            'type'        => $fav->type,
            'category_id' => $fav->category_id,
            'account_id'  => $fav->account_id,
            'date'        => now()->toDateString(),
        ]);

        $this->dispatch('open-modal', 'modal-transaction');
    }

    // Load data template favorit untuk diubah
    public function editFavorite(int $favoriteId)
    {
        $fav = FavoriteTransaction::findOrFail($favoriteId);
        
        $this->editId = $fav->id;
        $this->editName = $fav->name;
        $this->editAmount = $fav->amount;
        $this->editType = $fav->type;
        $this->editCategoryId = $fav->category_id;
        $this->editAccountId = $fav->account_id;
        
        $this->dispatch('open-modal', 'modal-edit-favorite');
    }

    // Simpan template favorit yang diubah
    public function updateFavorite()
    {
        $this->validate();
        
        $fav = FavoriteTransaction::findOrFail($this->editId);
        $fav->update([
            'name' => $this->editName,
            'amount' => $this->editAmount,
            'type' => $this->editType,
            'category_id' => $this->editCategoryId,
            'account_id' => $this->editAccountId,
        ]);
        
        $this->loadFavorites();
        $this->dispatch('close-modal', 'modal-edit-favorite');
        $this->notify('Berhasil diubah!', 'Template transaksi cepat berhasil diperbarui.', 'success');
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
        $this->dispatch('open-modal', 'modal-delete-favorit');
    }

    // Hapus dari favorite
    public function delete(): void
    {
        FavoriteTransaction::findOrFail($this->deleteId)->delete();
        $this->deleteId = null;
        $this->loadFavorites();
        $this->dispatch('close-modal', 'modal-delete-favorit');
        $this->notify('Berhasil dihapus!', 'Transaksi telah dihapus dari favorit.', 'success');
    }

    public function render()
    {
        return view('livewire.transactions.quick-transaction');
    }
}
