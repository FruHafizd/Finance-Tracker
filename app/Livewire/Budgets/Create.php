<?php

namespace App\Livewire\Budgets;

use App\Models\Budget;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Create extends Component
{   
    protected $listeners = [    
        'budget-created' => '$refresh',
        'budget-updated' => '$refresh',
        'budget-deleted' => '$refresh',
    ];
    
    public int $category_id = 0;
    public string $limit_amount = '';

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

    public function save(): void
    {
        $this->validate();

        Budget::create([
            'user_id'      => Auth::id(),
            'category_id'  => $this->category_id,
            'limit_amount' => (int) $this->limit_amount,
            'month'        => (int) now()->format('n'),
            'year'         => (int) now()->format('Y'),
        ]);

        $this->reset(['category_id', 'limit_amount']);
        $this->resetErrorBag();

        $this->dispatch('close-modal', 'modal-budget-create');
        $this->dispatch('budget-created', message: 'Budget berhasil ditambahkan!');
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

        return view('livewire.budgets.create', compact('categories'));
    }
}