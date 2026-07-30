<?php

namespace App\Livewire\Transactions;

use App\Livewire\Concerns\RefreshesOnTransactionChange;
use App\Services\TransactionService;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;

class Index extends Component
{
    use WithPagination, \App\Traits\WithNotifications, RefreshesOnTransactionChange;

    public function __get($property)
    {
        if ($property === 'summary') {
            return app(TransactionService::class)->getSummary($this->currentFilters());
        }
        if ($property === 'transactions') {
            return app(TransactionService::class)->getPaginatedTransactions($this->currentFilters())['transactions'];
        }
        return parent::__get($property);
    }

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
    public $search         = '';
    public $quickDate      = 'this_month';
    public $sortBy         = 'latest';

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
        $this->quickDate   = 'this_month';

        // Cek apakah ada data prefill dari kalender reminder
        $prefill = session('prefill_transaction');
        if ($prefill) {
            // Dispatch event ke TransactionForm setelah halaman selesai render
            $this->dispatch('prefill-transaction', data: $prefill);
            $this->dispatch('open-modal', 'modal-transaction');
        }
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
        $this->quickDate   = 'custom';
        $this->resetPage();
    }

    public function updatedEndDate(): void
    {
        $this->filterYear  = '';
        $this->filterMonth = '';
        $this->quickDate   = 'custom';
        $this->resetPage();
    }

    public function updatedFilterType(): void     { $this->resetPage(); }
    public function updatedFilterCategory(): void { $this->resetPage(); }
    public function updatedSearch(): void         { $this->resetPage(); }
    public function updatedSortBy(): void         { $this->resetPage(); }

    public function updatedQuickDate($value): void
    {
        $this->resetPage();

        if ($value === 'this_month') {
            $this->filterYear = date('Y');
            $this->filterMonth = date('n');
            $this->startDate = '';
            $this->endDate = '';
        } elseif ($value === 'last_month') {
            $lastMonth = now()->subMonth();
            $this->filterYear = $lastMonth->format('Y');
            $this->filterMonth = $lastMonth->format('n');
            $this->startDate = '';
            $this->endDate = '';
        } elseif ($value === 'last_3_months') {
            $this->filterYear = '';
            $this->filterMonth = '';
            $this->startDate = now()->subMonths(3)->toDateString();
            $this->endDate = now()->toDateString();
        } elseif ($value === 'this_year') {
            $this->filterYear = date('Y');
            $this->filterMonth = '';
            $this->startDate = '';
            $this->endDate = '';
        } elseif ($value === 'custom') {
            $this->filterYear = '';
            $this->filterMonth = '';
        }
    }

    public function removeFilter($key): void
    {
        if ($key === 'search') {
            $this->search = '';
        } elseif ($key === 'quickDate') {
            $this->quickDate = 'this_month';
            $this->updatedQuickDate('this_month');
        } elseif ($key === 'type') {
            $this->filterType = '';
        } elseif ($key === 'category') {
            $this->filterCategory = '';
        } elseif ($key === 'sortBy') {
            $this->sortBy = 'latest';
        }
        $this->resetPage();
    }

    public function getActiveFiltersProperty(): array
    {
        $chips = [];

        if (!empty($this->search)) {
            $chips[] = [
                'key' => 'search',
                'label' => 'Cari: "' . $this->search . '"',
            ];
        }

        if ($this->quickDate !== 'this_month') {
            $label = '';
            if ($this->quickDate === 'last_month') $label = 'Bulan lalu';
            elseif ($this->quickDate === 'last_3_months') $label = '3 bulan terakhir';
            elseif ($this->quickDate === 'this_year') $label = 'Tahun ini';
            elseif ($this->quickDate === 'custom') {
                if ($this->startDate && $this->endDate) {
                    $label = date('d M Y', strtotime($this->startDate)) . ' - ' . date('d M Y', strtotime($this->endDate));
                } elseif ($this->startDate) {
                    $label = 'Dari: ' . date('d M Y', strtotime($this->startDate));
                } elseif ($this->endDate) {
                    $label = 'Sampai: ' . date('d M Y', strtotime($this->endDate));
                } else {
                    $label = 'Rentang custom';
                }
            }
            if ($label) {
                $chips[] = [
                    'key' => 'quickDate',
                    'label' => $label,
                ];
            }
        }

        if (!empty($this->filterType)) {
            $typeLabel = $this->filterType === 'income' ? 'Pemasukan' : ($this->filterType === 'transfer' ? 'Transfer' : 'Pengeluaran');
            $chips[] = [
                'key' => 'type',
                'label' => 'Tipe: ' . $typeLabel,
            ];
        }

        if (!empty($this->filterCategory)) {
            $cat = \App\Models\Category::find($this->filterCategory);
            if ($cat) {
                $chips[] = [
                    'key' => 'category',
                    'label' => 'Kategori: ' . $cat->name,
                ];
            }
        }

        if ($this->sortBy !== 'latest') {
            $sortLabels = [
                'oldest' => 'Terlama',
                'amount_desc' => 'Nominal tertinggi',
                'amount_asc' => 'Nominal terendah',
            ];
            $chips[] = [
                'key' => 'sortBy',
                'label' => 'Urutkan: ' . ($sortLabels[$this->sortBy] ?? $this->sortBy),
            ];
        }

        return $chips;
    }

    public function resetFilters(): void
    {
        $this->search         = '';
        $this->quickDate      = 'this_month';
        $this->filterYear     = date('Y');
        $this->filterMonth    = date('n');
        $this->filterType     = '';
        $this->filterCategory = '';
        $this->startDate      = '';
        $this->endDate        = '';
        $this->sortBy         = 'latest';
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
            'search'    => $this->search,
            'sortBy'    => $this->sortBy,
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
            $this->notify('Berhasil ditambahkan!', 'Transaksi telah disimpan ke daftar Transaksi Cepat.', 'success');
        } elseif ($status === 'invalid_type') {
            $this->notify('Gagal!', 'Transaksi transfer tidak dapat dijadikan Transaksi Cepat.', 'danger');
        } else {
            $this->notify('Sudah Ada!', 'Transaksi ini sudah ada di daftar Transaksi Cepat Anda.', 'warning');
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
        $transactions = $this->transactions;
        $grouped = $transactions->getCollection()->groupBy(fn($item) => $item->date->format('Y-m-d'));

        return view('livewire.transactions.index', [
            'transactions' => $transactions,
            'grouped'      => $grouped,
            'summary'      => $this->summary,
            'categories'   => $service->getCategories(),
        ])->layout('layouts.app', ['title' => 'Riwayat Transaksi']);
    }
}