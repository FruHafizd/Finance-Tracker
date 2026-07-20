<?php

namespace App\Livewire\Budgets;

use App\Services\BudgetService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class BudgetIndex extends Component
{   
    use \App\Traits\WithNotifications;

    public int $month;
    public int $year;
    public ?int $deleteId = null;

    protected $listeners = [
        'budget-created' => '$refresh',
        'budget-updated' => '$refresh',
        'budget-deleted' => '$refresh',
        'transaction-created' => '$refresh',
        'transaction-deleted' => '$refresh',
        'transaction-updated' => '$refresh',
    ];

    public function mount(): void
    {
        $this->month = (int) now()->format('n');
        $this->year  = (int) now()->format('Y');
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
        $this->dispatch('open-modal', 'modal-delete-budget');
    }

    public function delete(BudgetService $service): void
    {
        $service->deleteBudget($this->deleteId, Auth::id());

        $this->deleteId = null;
        $this->dispatch('close-modal', 'modal-delete-budget');
        $this->notify('Berhasil!', 'Budget berhasil dihapus!', 'success');
        $this->dispatch('budget-deleted');
    }

    public function render(BudgetService $service)
    {   
        $budgets = $service->getBudgetsForUser(Auth::id(), $this->month, $this->year);
        $exceededBudgets = $service->getExceededBudgets(Auth::id());

        // Menyisipkan kalkulasi "kebablasan" langsung ke tiap object collection
        foreach ($exceededBudgets as $b) {
            $b->over = $b->spentAmount() - $b->limit_amount;
        }

        $totalLimit = $budgets->sum('limit_amount');
        $totalSpent = $budgets->sum(fn($b) => $b->spentAmount());
        $chartRadius = 42;
        $chartCircumference = 2 * pi() * $chartRadius;
        $chartOffset = 0;
        
        // Memindahkan logika SVG diagram Donut dari blade ke Livewire
        $chartData = $budgets->map(function($b) use ($totalLimit, $chartCircumference, &$chartOffset) {
            $allocation = (float) $b->limit_amount;
            $percentage = $totalLimit > 0 ? ($allocation / $totalLimit) * 100 : 0;
            $length = ($percentage / 100) * $chartCircumference;

            $data = [
                'name' => $b->category->name,
                'allocation' => $allocation,
                'color' => $b->category->color ?? '#6366f1',
                'percentage' => $percentage,
                'dasharray' => $length . ' ' . ($chartCircumference - $length),
                'dashoffset' => -$chartOffset,
            ];
            $chartOffset += $length;
            
            return $data;
        })->values();

        // Menyisipkan warna status (warning/danger) agar komponen blade tidak melakukan logical ops.
        foreach ($budgets as $budget) {
            $budget->spent = $budget->spentAmount();
            $budget->percentage = $budget->spentPercentage();
            $budget->isWarning = $budget->percentage >= 80 && $budget->percentage < 100;
            $budget->isDanger = $budget->percentage >= 100;
            $budget->barColor = $budget->isDanger ? 'bg-danger' : ($budget->isWarning ? 'bg-amber-400' : 'bg-emerald-500');
        }

        return view('livewire.budgets.budget-index', compact(
            'budgets', 
            'exceededBudgets', 
            'totalLimit', 
            'totalSpent', 
            'chartData'
        ))->layout('layouts.app', ['title' => 'Budget Transaksi']);
    }
}
