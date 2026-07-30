<?php

namespace App\Livewire\Transactions;

use App\Models\Account;
use App\Services\FavoriteTransactionService;
use App\Services\TransactionService;
use App\Traits\WithNotifications;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;

class QuickTransaction extends Component
{
    use WithNotifications;
    
    public $favorites = [];
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

    protected $listeners = [
        'favorite-created' => 'loadFavorites',
        'category-created' => '$refresh',
        'account-saved'    => '$refresh',
        'account-deleted'  => '$refresh',
    ];

    public function mount()
    {
        $this->loadFavorites();
    }

    #[Computed]
    public function accounts()
    {
        return Account::where('user_id', auth()->id())->get();
    }

    /**
     * Kategori yang difilter berdasarkan jenis transaksi yang dipilih.
     * Jika type belum dipilih, tampilkan semua kategori.
     */
    #[Computed]
    public function filteredCategories()
    {
        $service = app(FavoriteTransactionService::class);

        if (! $this->editType) {
            return $service->getAllCategories(auth()->id());
        }

        return $service->getFilteredCategories(auth()->id(), $this->editType);
    }

    public function loadFavorites()
    {
        $this->favorites = app(FavoriteTransactionService::class)->getFavoritesForUser(auth()->id());
    }

    /**
     * Saat user mengganti jenis transaksi, reset kategori yang dipilih.
     */
    public function updatedEditType($value): void
    {
        $this->editCategoryId = null;
    }

    // 1-click langsung save, date = hari ini
    public function saveNow(int $favoriteId)
    {
        $fav = app(FavoriteTransactionService::class)->getFavoriteForUser($favoriteId, auth()->id());

        app(TransactionService::class)->createTransaction([
            'category_id' => $fav->category_id,
            'account_id'  => $fav->account_id,
            'name'        => $fav->name,
            'amount'      => $fav->amount,
            'type'        => $fav->type,
            'date'        => now()->toDateString(),
        ], auth()->user());

        $this->dispatch('transaction-created');
        $this->notify('Transaksi Sukses!', '1 Transaksi cepat berhasil ditambahkan ke catatan.', 'success');
    }

    // Kirim data ke modal Create supaya pre-filled
    public function prefill(int $favoriteId)
    {
        $fav = app(FavoriteTransactionService::class)->getFavoriteForUser($favoriteId, auth()->id());

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
        $fav = app(FavoriteTransactionService::class)->getFavoriteForUser($favoriteId, auth()->id());
        
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
        
        app(FavoriteTransactionService::class)->updateFavorite($this->editId, [
            'name' => $this->editName,
            'amount' => $this->editAmount,
            'type' => $this->editType,
            'category_id' => $this->editCategoryId,
            'account_id' => $this->editAccountId,
        ], auth()->id());
        
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
        app(FavoriteTransactionService::class)->deleteFavorite($this->deleteId, auth()->id());
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
