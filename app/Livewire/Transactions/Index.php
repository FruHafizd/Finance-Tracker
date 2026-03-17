<?php

namespace App\Livewire\Transactions;

use App\Models\Transaction;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    protected $listeners = [
        'transaction-created' => '$refresh',
        'transaction-deleted' => '$refresh',
        'transaction-updated' => '$refresh',
    ];

    use WithPagination;
    protected $paginationTheme = 'tailwind';
    public $filterYear;
    public $filterMonth;
    public $filterType = '';

    public function mount() {
        $this->filterYear = date('Y');
        $this->filterMonth = date('n');
    }

    public function updatingFilterYear(){
        $this->resetPage();
    }

    public function updatingFilterMonth(){
        $this->resetPage();
    }

    public function updatingFilterType(){
        $this->resetPage();
    }

    public function resetFilters(){
        $this->filterYear = '';
        $this->filterMonth = '';
        $this->filterType = '';
        $this->resetPage();
    }

    public function getTransactionsProperty(){
        $query = Transaction::where('user_id', auth()->id());

        if ($this->filterYear) {
            $query->whereYear('date', $this->filterYear);
        }

        if ($this->filterMonth) {
            $query->whereMonth('date', $this->filterMonth);
        }

        if ($this->filterType) {
            $query->where('type', $this->filterType);
        }

        return $query->orderBy('date', 'desc')->paginate(10);
    }

    public function getSummaryProperty()
    {
        $query = Transaction::where('user_id', auth()->id());

        if ($this->filterYear) {
            $query->whereYear('date', $this->filterYear);
        }
        if ($this->filterMonth) {
            $query->whereMonth('date', $this->filterMonth);
        }

        $transactions = $query->select('type', 'amount')->get();

        $income = $transactions->where('type', 'income')->sum('amount');
        $expense = $transactions->where('type', 'expense')->sum('amount');

        return [
            'income' => $income,
            'expense' => $expense,
            'difference' => $income - $expense
        ];
    }

    public function render()
    {
        return view('livewire.transactions.index',[
            'transactions' => $this->transactions,
            'summary' => $this->summary
        ])->layout('layouts.app', [
                'title' => 'Riwayat Transaksi'
            ]);
    }
}
