<?php

namespace App\Livewire\Transactions;

use App\Models\Transaction;
use App\Services\TransactionService;
use App\Traits\WithNotifications;
use Livewire\Attributes\Computed;
use Livewire\Component;

class TransactionForm extends Component
{
    use WithNotifications;

    /* ------------------------------------------------------------------
     |  UI State
     | ------------------------------------------------------------------ */

    public ?int $transactionId = null;
    public $amount = '';
    public $type = '';
    public $date = '';
    public $name = '';
    public $category_id = '';
    public $account_id = '';
    public $to_account_id = '';
    public bool $showNotes = false;

    /* ------------------------------------------------------------------
     |  Listeners
     | ------------------------------------------------------------------ */

    protected $listeners = [
        'open-create-transaction' => 'openCreate',
        'open-transfer'           => 'openTransfer',
        'edit-transaction'        => 'openEdit',
        'category-created'        => '$refresh',
        'prefill-transaction'     => 'prefillForm',
        'account-saved'           => '$refresh',
        'account-deleted'         => '$refresh',
    ];

    /* ------------------------------------------------------------------
     |  Validation
     | ------------------------------------------------------------------ */

    protected $rules = [
        'name'          => 'nullable|string|min:3',
        'category_id'   => 'required',
        'account_id'    => 'required',
        'to_account_id' => 'required_if:type,transfer',
        'amount'        => 'required|numeric|min:1',
        'type'          => 'required|in:income,expense,transfer',
        'date'          => 'required|date',
    ];

    protected $messages = [
        'amount.required'           => 'Jumlah tidak boleh kosong',
        'amount.numeric'            => 'Jumlah harus berupa angka',
        'amount.min'                => 'Jumlah minimal Rp 1',
        'type.required'             => 'Silakan pilih jenis transaksi (Pemasukan, Pengeluaran, atau Transfer)',
        'date.required'             => 'Tanggal tidak boleh kosong',
        'name.min'                  => 'Nama minimal 3 karakter',
        'type.in'                   => 'Type tidak valid',
        'date.date'                 => 'Format tanggal tidak valid',
        'category_id.required'      => 'Kategori tidak boleh kosong',
        'account_id.required'       => 'Rekening tidak boleh kosong',
        'to_account_id.required_if' => 'Rekening tujuan wajib diisi untuk transfer',
    ];

    /* ------------------------------------------------------------------
     |  Computed Properties (menggantikan public $categories & $accounts)
     | ------------------------------------------------------------------ */

    #[Computed]
    public function accounts()
    {
        return \App\Models\Account::where('user_id', auth()->id())->get();
    }

    /**
     * Kategori yang difilter berdasarkan jenis transaksi yang dipilih.
     * Jika type belum dipilih, tampilkan semua kategori.
     */
    #[Computed]
    public function filteredCategories()
    {
        $service = app(TransactionService::class);

        if (! $this->type || $this->type === 'transfer') {
            return \App\Models\Category::where('user_id', auth()->id())
                ->orderBy('name')
                ->get();
        }

        return $service->getFilteredCategories(auth()->id(), $this->type);
    }

    /* ------------------------------------------------------------------
     |  Lifecycle
     | ------------------------------------------------------------------ */

    public function mount(): void
    {
        $this->date = now()->format('Y-m-d');

        if (auth()->check()) {
            $service = app(TransactionService::class);
            $lastAccount = $service->getLastUsedAccount(auth()->user());
            if ($lastAccount) {
                $this->account_id = $lastAccount->id;
            } else {
                $firstAccount = \App\Models\Account::where('user_id', auth()->id())->first();
                if ($firstAccount) {
                    $this->account_id = $firstAccount->id;
                }
            }
        }
    }

    public function isEditing(): bool
    {
        return $this->transactionId !== null;
    }

    /**
     * Saat user mengganti jenis transaksi, auto-set default kategori.
     */
    public function updatedType($value): void
    {
        if ($value && $value !== 'transfer') {
            $service = app(TransactionService::class);
            $lastCategory = $service->getLastUsedCategory(auth()->user(), $value);

            if ($lastCategory) {
                $this->category_id = $lastCategory->id;
            } else {
                $this->category_id = null;
            }
        }

        // Reset to_account_id jika bukan transfer
        if ($value !== 'transfer') {
            $this->to_account_id = null;
        }
    }

    /* ------------------------------------------------------------------
     |  Modal Openers
     | ------------------------------------------------------------------ */

    public function openCreate(): void
    {
        $this->resetForm();
        $this->applyDefaults();
        $this->dispatch('open-modal', 'modal-transaction');
    }

    public function openTransfer(int $fromAccountId): void
    {
        $this->resetForm();
        $this->type       = 'transfer';
        $this->account_id = $fromAccountId;
        $this->date       = now()->format('Y-m-d');
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

        // Tampilkan field nama jika sudah terisi
        $this->showNotes = ! empty($this->name);

        $this->dispatch('open-modal', 'modal-transaction');
    }

    /* ------------------------------------------------------------------
     |  Save — delegasi ke TransactionService
     | ------------------------------------------------------------------ */

    public function save(TransactionService $service): void
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

            $service->updateTransaction($transaction, $data);

            $this->notify('Berhasil!', 'Data transaksi berhasil diperbarui.', 'success');
            $this->dispatch('transaction-updated');
        } else {
            $service->createTransaction($data, auth()->user());

            $this->notify('Berhasil!', 'Data transaksi berhasil ditambahkan.', 'success');
            $this->dispatch('transaction-created');
        }

        // Budget alert untuk expense
        if ($this->type === 'expense') {
            $alert = $service->checkBudgetAlert(auth()->id(), $this->category_id);
            if ($alert) {
                $detail = json_encode($alert);
                $this->js("window.dispatchEvent(new CustomEvent('budget-alert', { detail: {$detail} }));");
            }
        }

        $this->resetForm();
        $this->dispatch('close-modal', 'modal-transaction');
    }

    /* ------------------------------------------------------------------
     |  Prefill & Reset
     | ------------------------------------------------------------------ */

    #[\Livewire\Attributes\On('prefill-transaction')]
    public function prefillForm(array $data = []): void
    {
        $this->name        = $data['name']        ?? null;
        $this->amount      = $data['amount']      ?? null;
        $this->type        = $data['type']        ?? null;
        $this->category_id = $data['category_id'] ?? null;
        $this->account_id  = $data['account_id']  ?? null;
        $this->date        = $data['date']        ?? null;

        if (empty($this->account_id)) {
            $service = app(TransactionService::class);
            $user    = auth()->user();
            $lastAccount = $service->getLastUsedAccount($user);
            if ($lastAccount) {
                $this->account_id = $lastAccount->id;
            } else {
                $firstAccount = \App\Models\Account::where('user_id', $user->id)->first();
                if ($firstAccount) {
                    $this->account_id = $firstAccount->id;
                }
            }
        }

        $this->showNotes = ! empty($this->name);
    }

    #[\Livewire\Attributes\On('reset-form')]
    public function resetForm(): void
    {
        $this->reset([
            'transactionId', 'amount', 'type', 'date',
            'name', 'category_id', 'account_id', 'to_account_id',
            'showNotes',
        ]);
    }

    /* ------------------------------------------------------------------
     |  Helpers (private)
     | ------------------------------------------------------------------ */

    /**
     * Set smart defaults: tanggal hari ini, rekening & kategori terakhir.
     */
    private function applyDefaults(): void
    {
        $service = app(TransactionService::class);
        $user    = auth()->user();

        // Default tanggal: hari ini
        $this->date = now()->format('Y-m-d');

        // Default rekening: terakhir dipakai
        $lastAccount = $service->getLastUsedAccount($user);
        if ($lastAccount) {
            $this->account_id = $lastAccount->id;
        }
    }

    /* ------------------------------------------------------------------
     |  Render
     | ------------------------------------------------------------------ */

    public function render()
    {
        return view('livewire.transactions.transaction-form');
    }
}
