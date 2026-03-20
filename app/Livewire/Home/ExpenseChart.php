<?php

namespace App\Livewire\Home;

use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ExpenseChart extends Component
{
    public string $startDate = '';
    public string $endDate = '';

    public function mount(): void
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate   = now()->endOfMonth()->format('Y-m-d');
    }

    public function getChartData(): array
    {
        $query = Transaction::query()
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->select(
                'categories.name as category',
                'categories.color',
                DB::raw('SUM(transactions.amount) as total')
            )
            ->where('transactions.type', 'expense')
            ->groupBy('categories.id', 'categories.name', 'categories.color')
            ->orderByDesc('total');

        if ($this->startDate) {
            $query->whereDate('transactions.date', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('transactions.date', '<=', $this->endDate);
        }

        $results = $query->get();

        return [
            'labels' => $results->pluck('category')->toArray(),
            'colors' => $results->pluck('color')
                                ->map(fn($c) => $c ?? '#6366f1')
                                ->toArray(),
            'data'   => $results->pluck('total')
                                ->map(fn($v) => (float) $v)
                                ->toArray(),
        ];
    }

    public function updated($property): void
    {
        if (in_array($property, ['startDate', 'endDate'])) {
            $this->dispatch('chartUpdated', ...$this->getChartData());
        }
    }

    public function render()
    {
        return view('livewire.home.expense-chart', [
            'chartData' => $this->getChartData(),
        ]);
    }
}
