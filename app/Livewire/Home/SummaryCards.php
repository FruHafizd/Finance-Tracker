<?php

namespace App\Livewire\Home;

use Livewire\Component;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SummaryCards extends Component
{
    public function getData(): array
    {
        $userId    = Auth::id();
        $start     = now()->startOfMonth()->format('Y-m-d');
        $end       = now()->endOfMonth()->format('Y-m-d');
        $prevStart = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
        $prevEnd   = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');

        $current = Transaction::where('user_id', $userId)
            ->whereDate('date', '>=', $start)
            ->whereDate('date', '<=', $end)
            ->selectRaw("
                SUM(CASE WHEN type = 'income'  THEN amount ELSE 0 END) as income,
                SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expense
            ")->first();

        $prev = Transaction::where('user_id', $userId)
            ->whereDate('date', '>=', $prevStart)
            ->whereDate('date', '<=', $prevEnd)
            ->selectRaw("
                SUM(CASE WHEN type = 'income'  THEN amount ELSE 0 END) as income,
                SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expense
            ")->first();

        $hasPrev = Transaction::where('user_id', $userId)
            ->whereDate('date', '>=', $prevStart)
            ->whereDate('date', '<=', $prevEnd)
            ->exists();

        $income  = (float) ($current->income  ?? 0);
        $expense = (float) ($current->expense ?? 0);
        $balance = $income - $expense;

        $prevIncome  = (float) ($prev->income  ?? 0);
        $prevExpense = (float) ($prev->expense ?? 0);
        $prevBalance = $prevIncome - $prevExpense;

        return [
            'income'  => [
                'current' => $income,
                'change'  => $hasPrev ? $this->pct($income, $prevIncome) : null,
                'hasPrev' => $hasPrev,
            ],
            'expense' => [
                'current' => $expense,
                'change'  => $hasPrev ? $this->pct($expense, $prevExpense) : null,
                'hasPrev' => $hasPrev,
            ],
            'balance' => [
                'current'    => $balance,
                'prevAmount' => $prevBalance,
                'hasPrev'    => $hasPrev,
            ],
        ];
    }

    private function pct(float $current, float $prev): ?float
    {
        if ($prev == 0) return null;
        return round((($current - $prev) / $prev) * 100, 1);
    }

    public function render()
    {
        return view('livewire.home.summary-cards', [
            'summary' => $this->getData(),
        ]);
    }
}