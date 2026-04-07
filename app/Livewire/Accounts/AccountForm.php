<?php

namespace App\Livewire\Accounts;

use App\Enums\AccountProvider;
use App\Models\Account;
use App\Traits\WithNotifications;
use Livewire\Component;

class AccountForm extends Component
{   
    use WithNotifications;

    public ?int $accountId = null;

    public string $name     = '';
    public string $type     = 'tabungan';
    public string $provider = '';
    public float  $balance  = 0;
    public string $color    = '#475569';

    protected function rules(): array
    {
        return [
            'name'     => 'required|string|max:100',
            'type'     => 'required|in:tabungan,ewallet,tunai',
            'provider' => 'nullable|string|max:100',
            'balance'  => 'required|numeric|min:0',
            'color'    => 'nullable|string|max:7',
        ];
    }

    protected $messages = [
        'name.required'    => 'Nama rekening wajib diisi.',
        'balance.required' => 'Saldo awal wajib diisi.',
        'balance.min'      => 'Saldo tidak boleh minus.',
    ];

    // listener dari AccountList saat klik edit
    protected $listeners = [
        'open-account-form' => 'openForm',
    ];

    public function openForm(?int $id = null): void
    {
        $this->resetForm();
        $this->accountId = $id;

        if ($id) {
            $account = Account::findOrFail($id);

            $this->name     = $account->name;
            $this->type     = $account->type;
            $this->provider = $account->provider ?? '';
            $this->balance  = $account->balance;
            $this->color    = $account->color ?? '#475569';
        }

        // buka modal pakai Alpine dispatch
        $this->dispatch('open-modal', 'modal-account');
    }

    public function updatedType(): void
    {
        $this->provider = '';
    }

    public function getProvidersProperty(): array
    {
        return AccountProvider::forType($this->type);
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name'     => $this->name,
            'type'     => $this->type,
            'provider' => $this->type === 'tunai' ? null : $this->provider,
            'balance'  => $this->balance,
            'color'    => $this->color,
        ];

        if ($this->accountId) {
            $account = Account::findOrFail($this->accountId);
            $account->update($data);
            $title   = 'Rekening diperbarui';
            $message = "Rekening {$account->name} berhasil diperbarui.";
        } else {
            $data['user_id']    = auth()->id();
            $data['sort_order'] = Account::where('user_id', auth()->id())->max('sort_order') + 1;
            $account            = Account::create($data);
            $title              = 'Rekening ditambahkan';
            $message            = "Rekening {$account->name} berhasil ditambahkan.";
        }

        $this->dispatch('close-modal', 'modal-account');
        $this->notify($title, $message, 'success');
        $this->dispatch('account-saved');
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->accountId = null;
        $this->name      = '';
        $this->type      = 'tabungan';
        $this->provider  = '';
        $this->balance   = 0;
        $this->color     = '#475569';
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.accounts.account-form');
    }
}
