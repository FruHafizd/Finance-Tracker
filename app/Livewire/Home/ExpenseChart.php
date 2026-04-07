<?php

namespace App\Livewire\Home;

use Livewire\Component;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ExpenseChart extends Component
{
    protected $listeners = [
        'transaction-created' => '$refresh',
        'transaction-deleted' => '$refresh',
        'transaction-updated' => '$refresh',
    ];

    public function getChartData(): array
    {
        $results = Transaction::query()
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->select('categories.name as category', 'categories.color', DB::raw('SUM(transactions.amount) as total'))
            ->where('transactions.user_id', Auth::id())
            ->where('transactions.type', 'expense')
            ->whereDate('transactions.date', '>=', now()->startOfMonth())
            ->whereDate('transactions.date', '<=', now()->endOfMonth())
            ->groupBy('categories.id', 'categories.name', 'categories.color')
            ->orderByDesc('total')
            ->get();

        return [
            'labels' => $results->pluck('category')->toArray(),
            'colors' => $results->pluck('color')->map(fn($c) => $c ?? '#6366f1')->toArray(),
            'data'   => $results->pluck('total')->map(fn($v) => (float) $v)->toArray(),
        ];
    }

    public function render()
    {
        return view('livewire.home.expense-chart', [
            'chartData' => $this->getChartData(),
        ]);
    }
}