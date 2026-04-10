<?php

namespace App\Livewire\Settings;

use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use App\Traits\WithNotifications;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;

class DataManagement extends Component
{
    use WithFileUploads, WithNotifications;

    public $jsonFile;
    public $transactionCount;
    public $categoryCount;
    public $accountCount;

    public function mount()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        // Global scopes handle user_id filtering automatically
        $this->transactionCount = Transaction::count();
        $this->categoryCount = Category::count();
        $this->accountCount = Account::count();
    }

    /**
     * Backup data to JSON with security scoping.
     */
    public function backup()
    {
        $userId = auth()->id();
        
        $data = [
            'accounts' => Account::all()->map(fn($item) => $this->sanitizeForExport($item)),
            'categories' => Category::all()->map(fn($item) => $this->sanitizeForExport($item)),
            'transactions' => Transaction::all()->map(fn($item) => $this->sanitizeForExport($item)),
            'metadata' => [
                'exported_at' => now()->toDateTimeString(),
                'version' => '1.0.0',
                'user_id' => $userId, // Included for verification, but ignored on restore
            ],
        ];

        $fileName = 'finansiku-backup-' . now()->format('Ymd-His') . '.json';
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        return response()->streamDownload(function () use ($json) {
            echo $json;
        }, $fileName);
    }

    /**
     * Restore data from JSON with heavy security sanitization and mapping.
     */
    public function restore()
    {
        $this->validate([
            'jsonFile' => 'required|file|mimes:json|max:5120',
        ]);

        $content = file_get_contents($this->jsonFile->getRealPath());
        $data = json_decode($content, true);

        if (!$this->isValidBackupFormat($data)) {
            session()->flash('error', 'Berkas JSON tidak valid atau korup.');
            return;
        }

        try {
            DB::beginTransaction();

            $userId = auth()->id();
            $accountMap = [];
            $categoryMap = [];

            // 1. Restore Accounts (Minimalist Slate Context)
            foreach ($data['accounts'] as $item) {
                $clean = $this->sanitizeIncomingAccount($item);
                $oldId = $clean['old_id'];
                unset($clean['old_id']);
                
                $clean['user_id'] = $userId; // RIGID SECURITY: Enforce current user
                $new = Account::create($clean);
                $accountMap[$oldId] = $new->id;
            }

            // 2. Restore Categories
            foreach ($data['categories'] as $item) {
                $clean = $this->sanitizeIncomingCategory($item);
                $oldId = $clean['old_id'];
                unset($clean['old_id']);
                
                $clean['user_id'] = $userId;
                $new = Category::create($clean);
                $categoryMap[$oldId] = $new->id;
            }

            // 3. Restore Transactions (Relational Mapping)
            foreach ($data['transactions'] as $item) {
                $clean = $this->sanitizeIncomingTransaction($item);
                $clean['user_id'] = $userId;
                
                // Relational Mapping Logic
                $clean['account_id'] = $accountMap[$clean['account_id']] ?? null;
                $clean['category_id'] = $categoryMap[$clean['category_id']] ?? null;
                
                if (isset($clean['to_account_id'])) {
                    $clean['to_account_id'] = $accountMap[$clean['to_account_id']] ?? null;
                }

                Transaction::create($clean);
            }

            DB::commit();
            $this->loadStats();
            $this->jsonFile = null;
            $this->notify('Berhasil!', 'Data berhasil dipulihkan secara aman.', 'success');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Security-Hardened Restore Failed: ' . $e->getMessage());
            $this->notify('Gagal!', 'Gagal memproses data: Pastikan berkas benar.', 'error');
        }
    }

    private function isValidBackupFormat($data)
    {
        return $data && isset($data['accounts'], $data['categories'], $data['transactions']);
    }

    private function sanitizeForExport($item)
    {
        return $item->makeHidden(['deleted_at', 'created_at', 'updated_at'])->toArray();
    }

    private function sanitizeIncomingAccount($data)
    {
        return [
            'old_id' => $data['id'] ?? 0,
            'name' => strip_tags($data['name'] ?? 'Account'),
            'type' => strip_tags($data['type'] ?? 'Cash'),
            'provider' => strip_tags($data['provider'] ?? ''),
            'balance' => (float)($data['balance'] ?? 0),
            'color' => strip_tags($data['color'] ?? '#000000'),
            'sort_order' => (int)($data['sort_order'] ?? 0),
        ];
    }

    private function sanitizeIncomingCategory($data)
    {
        return [
            'old_id' => $data['id'] ?? 0,
            'name' => strip_tags($data['name'] ?? 'Category'),
            'color' => strip_tags($data['color'] ?? '#000000'),
        ];
    }

    private function sanitizeIncomingTransaction($data)
    {
        return [
            'name' => strip_tags($data['name'] ?? 'Transaction'),
            'amount' => (float)($data['amount'] ?? 0),
            'type' => strip_tags($data['type'] ?? 'expense'),
            'date' => strip_tags($data['date'] ?? now()->toDateTimeString()),
            'category_id' => $data['category_id'] ?? null,
            'account_id' => $data['account_id'] ?? null,
            'to_account_id' => $data['to_account_id'] ?? null,
        ];
    }

    public function render()
    {
        return view('livewire.settings.data-management')
            ->layout('layouts.app');
    }
}
