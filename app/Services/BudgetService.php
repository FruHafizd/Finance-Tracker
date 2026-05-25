<?php

namespace App\Services;

use App\Models\Budget;
use Illuminate\Support\Collection;

class BudgetService
{
    /**
     * Dapatkan budget bulan ini untuk user yang sedang login.
     */
    public function getCurrentMonthBudgets(): Collection
    {
        // Global scope 'user' pada Budget otomatis memfilter berdasarkan Auth::id()
        return Budget::with('category')
            ->where('month', now()->month)
            ->where('year', now()->year)
            ->get();
    }
}
