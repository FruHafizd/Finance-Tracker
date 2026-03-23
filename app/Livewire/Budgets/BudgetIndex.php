<?php

namespace App\Livewire\Budgets;

use App\Models\Budget;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class BudgetIndex extends Component
{   
    protected $listeners = [    
        'budget-created' => '$refresh',
        'budget-updated' => '$refresh',
        'budget-deleted' => '$refresh',
    ];

    public int $month;
    public int $year;

    public function mount(): void
    {
        $this->month = (int) now()->format('n');
        $this->year  = (int) now()->format('Y');
    }

    public function render()
    {   
        $budgets = Budget::with('category')
            ->where('user_id', Auth::id())
            ->where('month', $this->month)
            ->where('year', $this->year)
            ->get();

        return view('livewire.budgets.budget-index', compact('budgets'))
            ->layout('layouts.app', ['title' => 'Budget Transaksi']);
    }
}