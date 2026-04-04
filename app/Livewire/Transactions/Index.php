<?php

namespace App\Livewire\Transactions;

use App\Models\FavoriteTransaction;
use App\Models\Transaction;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination, \App\Traits\WithNotifications;

    public ?int $deleteId = null;

    protected $paginationTheme = 'tailwind';

    public $filterYear    = '';
    public $filterMonth   = '';
    public $filterType    = '';
    public $filterCategory = '';
    public $startDate     = '';
    public $endDate       = '';

    protected $listeners = [
        'transaction-created' => '$refresh',
        'transaction-deleted' => '$refresh',
        'transaction-updated' => '$refresh',
        'favorite-created' => '$refresh',
    ];

    public function mount(): void
    {
        $this->filterYear  = date('Y');
        $this->filterMonth = date('n');
    }

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

    private function baseQuery()
    {
        $query = Transaction::where('user_id', auth()->id())
            ->with('category');

        // Kalau pakai range tanggal → abaikan filterYear & filterMonth
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('date', [$this->startDate, $this->endDate]);
        } elseif ($this->startDate) {
            $query->whereDate('date', '>=', $this->startDate);
        } elseif ($this->endDate) {
            $query->whereDate('date', '<=', $this->endDate);
        } else {
            // Pakai filter tahun/bulan kalau tidak ada range
            if ($this->filterYear)  $query->whereYear('date', $this->filterYear);
            if ($this->filterMonth) $query->whereMonth('date', $this->filterMonth);
        }

        if ($this->filterType)     $query->where('type', $this->filterType);
        if ($this->filterCategory) $query->where('category_id', $this->filterCategory);

        return $query;
    }

    public function getSummaryProperty(): array
    {
        $data = $this->baseQuery()
            ->selectRaw("
                SUM(CASE WHEN type = 'income'  THEN amount ELSE 0 END) as income,
                SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expense
            ")
            ->first();

        $income  = (float) ($data->income  ?? 0);
        $expense = (float) ($data->expense ?? 0);

        return [
            'income'     => $income,
            'expense'    => $expense,
            'difference' => $income - $expense,
        ];
    }

    public function getCategoriesProperty()
    {
        return Category::where('user_id', auth()->id())
            ->orderBy('name')
            ->get();
    }

    public function addToFavorite(int $transactionId)
    {
        $trx = Transaction::findOrFail($transactionId);

        $fav = FavoriteTransaction::firstOrCreate(
            [
                'user_id' => auth()->id(),
                'name'    => $trx->name,
                'amount'  => $trx->amount,
                'type'    => $trx->type,
            ],
            ['category_id' => $trx->category_id]
        );
        
        if ($fav->wasRecentlyCreated) {
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

    public function delete(): void
    {
        $transaction = Transaction::findOrFail($this->deleteId);
        $transaction->delete();

        $this->deleteId = null;
        $this->dispatch('close-modal', 'modal-delete-transaksi');
        $this->notify('Berhasil!', 'Transaksi berhasil dihapus.', 'success');
        $this->dispatch('transaction-deleted');
    }

    public function render()
    {
        $transactions = $this->baseQuery()
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $grouped = $transactions->getCollection()
            ->groupBy(fn($item) => $item->date->format('Y-m-d'));

        return view('livewire.transactions.index', [
            'transactions' => $transactions,
            'grouped'      => $grouped,
            'summary'      => $this->summary,
            'categories'   => $this->categories,
        ])->layout('layouts.app', ['title' => 'Riwayat Transaksi']);
    }
}