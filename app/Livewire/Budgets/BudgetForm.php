<?php

namespace App\Livewire\Budgets;

use App\Models\Budget;
use App\Models\Category;
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

    public function openEdit($id): void 
    {
        $this->resetForm();
        
        $budget = Budget::with('category')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        $this->budgetId     = $budget->id;
        $this->category_id  = $budget->category_id;
        $this->limit_amount = (string) $budget->limit_amount;
        $this->categoryName = $budget->category->name;

        $this->resetErrorBag();
        $this->dispatch('open-modal', 'modal-budget');
    }
    
    public function save(): void 
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
            Budget::where('user_id', Auth::id())
            ->findOrFail($this->budgetId)
            ->update([
                'limit_amount' => (int) $this->limit_amount,
            ]);

            $this->notify('Berhasil!', 'Budget berhasil diperbarui!', 'success');
            $this->dispatch('close-modal', 'budget-updated');
            $this->dispatch('budget-updated');  
        }else {
            $exists = Budget::where('user_id', Auth::id())
                ->where('category_id', $this->category_id)
                ->where('month', $month)
                ->where('year', $year)
                ->exists();

            if ($exists) {
                $this->addError('category_id', 'Kategori ini sudah memiliki budget di bulan ini.');
                return;
            }

            Budget::create(array_merge($data, ['user_id' => auth()->id()]));
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

    public function render()
    {   
        // Ambil kategori yang belum punya budget bulan ini
        $usedCategoryIds = Budget::where('user_id', Auth::id())
            ->where('month', (int) now()->format('n'))
            ->where('year', (int) now()->format('Y'))
            ->pluck('category_id');

        $categories = Category::where('user_id', Auth::id())
            ->whereNotIn('id', $usedCategoryIds)
            ->orderBy('name')
            ->get();
        return view('livewire.budgets.budget-form', compact('categories'));
    }
}
