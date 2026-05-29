<?php

namespace App\Livewire\Transactions;

use App\Livewire\Concerns\RefreshesOnTransactionChange;
use App\Services\TransactionService;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, \App\Traits\WithNotifications, RefreshesOnTransactionChange;

    /* ------------------------------------------------------------------
     |  State
     | ------------------------------------------------------------------ */

    public ?int $deleteId = null;

    protected $paginationTheme = 'tailwind';

    /** Filter properties */
    public $filterYear     = '';
    public $filterMonth    = '';
    public $filterType     = '';
    public $filterCategory = '';
    public $startDate      = '';
    public $endDate        = '';

    /* ------------------------------------------------------------------
     |  Event Listeners
     |  transaction-created/deleted/updated → via RefreshesOnTransactionChange
     | ------------------------------------------------------------------ */

    protected $listeners = [
        'favorite-created' => '$refresh',
    ];

    /* ------------------------------------------------------------------
     |  Lifecycle
     | ------------------------------------------------------------------ */

    public function mount(): void
    {
        $this->filterYear  = date('Y');
        $this->filterMonth = date('n');
    }

    /* ------------------------------------------------------------------
     |  Filter Updates — reset pagination on change
     | ------------------------------------------------------------------ */

    public function updatedFilterYear(): void
    {
        $this->startDate = '';
        $this->endDate   = '';
        $this->resetPage();
    }

    public function updatedFilterMonth(): void
    {
        $this->startDate = '';
        $this->endDate   = '';
        $this->resetPage();
    }

    public function updatedStartDate(): void
    {
        $this->filterYear  = '';
        $this->filterMonth = '';
        $this->resetPage();
    }

    public function updatedEndDate(): void
    {
        $this->filterYear  = '';
        $this->filterMonth = '';
        $this->resetPage();
    }

    public function updatedFilterType(): void     { $this->resetPage(); }
    public function updatedFilterCategory(): void { $this->resetPage(); }

    public function resetFilters(): void
    {
        $this->filterYear     = date('Y');
        $this->filterMonth    = date('n');
        $this->filterType     = '';
        $this->filterCategory = '';
        $this->startDate      = '';
        $this->endDate        = '';
        $this->resetPage();
    }

    /* ------------------------------------------------------------------
     |  Helpers
     | ------------------------------------------------------------------ */

    /**
     * Kumpulkan semua filter aktif ke dalam array untuk dikirim ke service.
     */
    private function currentFilters(): array
    {
        return [
            'year'      => $this->filterYear,
            'month'     => $this->filterMonth,
            'startDate' => $this->startDate,
            'endDate'   => $this->endDate,
            'type'      => $this->filterType,
            'category'  => $this->filterCategory,
        ];
    }

    /* ------------------------------------------------------------------
     |  Actions
     | ------------------------------------------------------------------ */

    public function addToFavorite(int $transactionId, TransactionService $service): void
    {
        $status = $service->addToFavorite($transactionId);

        if ($status === 'created') {
            $this->dispatch('favorite-created');

            $this->js("
                window.dispatchEvent(new CustomEvent('notify', {
                    detail: {
                        type: 'success',
                        title: 'Berhasil ditambahkan!',
                        message: 'Transaksi telah disimpan ke daftar Transaksi Cepat.'
                    }
                }));
            ");
        } else {
            $this->js("
                window.dispatchEvent(new CustomEvent('notify', {
                    detail: {
                        type: 'warning',
                        title: 'Sudah Ada!',
                        message: 'Transaksi ini sudah ada di daftar Transaksi Cepat Anda.'
                    }
                }));
            ");
        }
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
        $this->dispatch('open-modal', 'modal-delete-transaksi');
    }

    public function delete(TransactionService $service): void
    {
        $service->deleteTransaction($this->deleteId);

        $this->deleteId = null;
        $this->dispatch('close-modal', 'modal-delete-transaksi');
        $this->notify('Berhasil!', 'Transaksi berhasil dihapus.', 'success');
        $this->dispatch('transaction-deleted');
    }

    /* ------------------------------------------------------------------
     |  Render
     | ------------------------------------------------------------------ */

    public function render(TransactionService $service)
    {
        $filters = $this->currentFilters();

        $data = $service->getPaginatedTransactions($filters);

        return view('livewire.transactions.index', [
            'transactions' => $data['transactions'],
            'grouped'      => $data['grouped'],
            'summary'      => $service->getSummary($filters),
            'categories'   => $service->getCategories(),
        ])->layout('layouts.app', ['title' => 'Riwayat Transaksi']);
    }
}