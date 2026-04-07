<?php

namespace App\Livewire\Transactions;

use App\Models\Budget;
use App\Models\Category;
use App\Models\Transaction;
use App\Traits\WithNotifications;
use Livewire\Component;

class TransactionForm extends Component
{   
    use WithNotifications;
    public ?int $transactionId = null;
    public $amount;
    public $type;
    public $date;
    public $name;
    public $categories = [];
    public $category_id;
    public $account_id;
    public $to_account_id;
    public $accounts = [];

    protected $listeners = [
        'open-create-transaction' => 'openCreate',
        'open-transfer'           => 'openTransfer',
        'edit-transaction'        => 'openEdit',
        'category-created'        => 'loadCategories',
        'prefill-transaction'     => 'prefillForm',
        'account-saved'           => 'loadAccounts',
        'account-deleted'         => 'loadAccounts',
    ];
     
    protected $rules = [
        'name'          => 'required|string|min:3',
        'category_id'   => 'required',
        'account_id'    => 'required',
        'to_account_id' => 'required_if:type,transfer',
        'amount'        => 'required|numeric|min:1',
        'type'          => 'required|in:income,expense,transfer',
        'date'          => 'required|date',
    ];

    protected $messages = [
        'amount.required'      => 'Jumlah tidak boleh kosong',
        'amount.numeric'       => 'Jumlah harus berupa angka',
        'type.required'        => 'Type tidak boleh kosong',
        'date.required'        => 'Tanggal tidak boleh kosong',
        'name.required'        => 'Nama tidak boleh kosong',
        'type.in'              => 'Type tidak valid',
        'date.date'            => 'Format tanggal tidak valid',
        'name.min'             => 'Nama minimal 3 karakter',
        'category_id.required' => 'Kategori tidak boleh kosong',
        'account_id.required'  => 'Rekening tidak boleh kosong',
        'to_account_id.required_if' => 'Rekening tujuan wajib diisi untuk transfer',
    ];

    public function mount(): void
    {
        $this->loadCategories();
        $this->loadAccounts();
    }

    public function loadCategories(): void
    {
        $this->categories = Category::where('user_id', auth()->id())->get();
    }

    public function loadAccounts(): void
    {
        $this->accounts = \App\Models\Account::where('user_id', auth()->id())->get();
    }

    public function isEditing(): bool
    {
        return $this->transactionId !== null;
    }

    public function openCreate(): void 
    {
        $this->resetForm();
        $this->dispatch('open-modal', 'modal-transaction');    
    }

    public function openTransfer(int $fromAccountId): void
    {
        $this->resetForm();
        $this->type = 'transfer';
        $this->account_id = $fromAccountId;
        $this->date = now()->format('Y-m-d');
        $this->dispatch('open-modal', 'modal-transaction');
    }

    public function openEdit($id): void 
    {
        $this->resetForm();
        
        $transaction = Transaction::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $this->transactionId = $transaction->id;
        $this->amount        = $transaction->amount;
        $this->type          = $transaction->type;
        $this->date          = $transaction->date->format('Y-m-d');
        $this->name          = $transaction->name;
        $this->category_id   = $transaction->category_id;
        $this->account_id    = $transaction->account_id;
        $this->to_account_id = $transaction->to_account_id;

        $this->dispatch('open-modal', 'modal-transaction');
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'category_id'   => $this->category_id,
            'account_id'    => $this->account_id,
            'to_account_id' => $this->type === 'transfer' ? $this->to_account_id : null,
            'amount'        => $this->amount,
            'type'          => $this->type,
            'date'          => $this->date,
            'name'          => $this->name,
        ];

        if ($this->isEditing()) {
            $transaction = Transaction::where('id', $this->transactionId)
                ->where('user_id', auth()->id())
                ->firstOrFail();
            
            $transaction->update($data);

            $this->notify('Berhasil!', 'Data transaksi berhasil diperbarui.', 'success');
            $this->dispatch('transaction-updated');
        } else {
            Transaction::create(array_merge($data, ['user_id' => auth()->id()]));
            $this->notify('Berhasil!', 'Data transaksi berhasil ditambahkan.', 'success');
            $this->dispatch('transaction-created');
        }

        if ($this->type === 'expense') {
            $this->checkBudget();
        }

        $this->resetForm();
        $this->dispatch('close-modal', 'modal-transaction');
    }

    #[\Livewire\Attributes\On('prefill-transaction')]
    public function prefillForm(array $data = []): void
    {
        $this->name        = $data['name']        ?? null;
        $this->amount      = $data['amount']      ?? null;
        $this->type        = $data['type']        ?? null;
        $this->category_id = $data['category_id'] ?? null;
        $this->account_id  = $data['account_id']  ?? null;
        $this->date        = $data['date']        ?? null;
    }

    #[\Livewire\Attributes\On('reset-form')]
    public function resetForm(): void
    {
        $this->reset(['transactionId', 'amount', 'type', 'date', 'name', 'category_id', 'account_id', 'to_account_id']);
    }

    private function checkBudget(): void
    {
        $budget = Budget::with('category')
            ->where('user_id', auth()->id())
            ->where('category_id', $this->category_id)
            ->where('month', (int) now()->format('n'))
            ->where('year', (int) now()->format('Y'))
            ->first();

        if (! $budget) return;

        $spent      = $budget->spentAmount();
        $percentage = $budget->limit_amount > 0
            ? ($spent / $budget->limit_amount) * 100
            : 0;

        $categoryName = $budget->category->name;
        $sisa         = max($budget->limit_amount - $spent, 0);
        $sisaFormat   = 'Rp ' . number_format($sisa, 0, ',', '.');

        if ($percentage >= 100) {
            $detail = json_encode([
                'type'    => 'danger',
                'title'   => 'Aduh, kebablasan! 🚨',
                'message' => "Pengeluaran {$categoryName} kamu sudah melebihi batas bulan ini!",
            ]);
        } elseif ($percentage >= 80) {
            $detail = json_encode([
                'type'    => 'warning',
                'title'   => 'Hampir habis! ⚠️',
                'message' => "Uang {$categoryName} kamu sudah " . round($percentage) . "% terpakai. Sisa {$sisaFormat}.",
            ]);
        } else {
            return;
        }

        $this->js("window.dispatchEvent(new CustomEvent('budget-alert', { detail: {$detail} }));");
    }


    public function render()
    {
        return view('livewire.transactions.transaction-form');
    }
}
