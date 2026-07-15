<?php

namespace App\Livewire\Budgets;

use App\Services\BudgetService;
use App\Traits\WithNotifications;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class BudgetForm extends Component
{
    use WithNotifications;
    public ?int $budgetId = null;
    public int $category_id = 0;
    public string $limit_amount = '';
    public string $categoryName = '';

    protected $listeners = [
        'open-create-budget' => 'openCreate',
        'edit-budget' => 'openEdit', 
        'budget-created' => '$refresh',
        'budget-updated' => '$refresh',
        'budget-deleted' => '$refresh',
    ];

    protected $rules = [
        'category_id'  => 'required|integer|min:1|exists:categories,id',
        'limit_amount' => 'required|numeric|min:1',
    ];

    protected $messages =  [
        'category_id.required' => 'Kategori wajib dipilih.',
        'category_id.min'      => 'Kategori wajib dipilih.',
        'category_id.exists'   => 'Kategori tidak valid.',
        'limit_amount.required'=> 'Batas pengeluaran wajib diisi.',
        'limit_amount.numeric' => 'Batas pengeluaran harus berupa angka.',
        'limit_amount.min'     => 'Batas pengeluaran minimal Rp 1.',
    ];

    public function isEditing(): bool 
    {
        return $this->budgetId !== null;    
    }

    public function openCreate(): void 
    {
        $this->resetForm();
        $this->dispatch('open-modal', 'modal-budget');    
    }

    public function openEdit($id, BudgetService $service): void 
    {
        $this->resetForm();
        
        $budget = $service->getBudgetForEdit($id, Auth::id());

        $this->budgetId     = $budget->id;
        $this->category_id  = $budget->category_id;
        $this->limit_amount = (string) $budget->limit_amount;
        $this->categoryName = $budget->category->name;

        $this->resetErrorBag();
        $this->dispatch('open-modal', 'modal-budget');
    }
    
    public function save(BudgetService $service): void 
    {
        $this->validate();
        
        $month = (int) now()->format('n');
        $year  = (int) now()->format('Y');

        $data = [
            'category_id'  => $this->category_id,
            'limit_amount' => (int) $this->limit_amount,
            'month'        => $month,
            'year'         => $year,
        ];

        if ($this->isEditing()) {
            $service->updateBudget($this->budgetId, [
                'limit_amount' => (int) $this->limit_amount,
            ], Auth::id());

            $this->notify('Berhasil!', 'Budget berhasil diperbarui!', 'success');
            $this->dispatch('close-modal', 'budget-updated');
            $this->dispatch('budget-updated');  
        } else {
            $service->createBudget($data, Auth::id());
            $this->notify('Berhasil!', 'Budget berhasil dibuat', 'success');
            $this->dispatch('budget-created');
        }

        $this->resetForm();
        $this->dispatch('close-modal', 'modal-budget');
    }

    #[\Livewire\Attributes\On('reset-form')]
    public function resetForm(): void
    {
        $this->reset(['budgetId', 'limit_amount', 'category_id', 'categoryName']);
    }

    public function render(BudgetService $service)
    {   
        $categories = $service->getAvailableCategories(Auth::id());

        return view('livewire.budgets.budget-form', compact('categories'));
    }
}
