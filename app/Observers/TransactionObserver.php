<?php

namespace App\Observers;

use App\Models\Transaction;
use App\Models\Account;

class TransactionObserver
{
    /**
     * Handle the Transaction "created" event.
     */
    public function created(Transaction $transaction): void
    {
        $this->applyTransaction($transaction);
    }

    /**
     * Handle the Transaction "updated" event.
     */
    public function updated(Transaction $transaction): void
    {
        // Mendapatkan data sebelum perubahan
        $oldAmount = $transaction->getOriginal('amount');
        $oldType = $transaction->getOriginal('type');
        $oldAccountId = $transaction->getOriginal('account_id');
        $oldToAccountId = $transaction->getOriginal('to_account_id');

        // Balikkan (Reverse) transaksi lama
        $this->reverseTransactionValues($oldType, $oldAmount, $oldAccountId, $oldToAccountId);

        // Terapkan (Apply) transaksi baru
        $this->applyTransaction($transaction);
    }

    /**
     * Handle the Transaction "deleted" event.
     */
    public function deleted(Transaction $transaction): void
    {
        $this->reverseTransaction($transaction);
    }

    /**
     * Helper untuk menerapkan perubahan saldo
     */
    private function applyTransaction(Transaction $transaction): void
    {
        $account = Account::find($transaction->account_id);
        
        if (!$account) return;

        if ($transaction->type === 'income') {
            $account->increment('balance', $transaction->amount);
        } elseif ($transaction->type === 'expense') {
            $account->decrement('balance', $transaction->amount);
        } elseif ($transaction->type === 'transfer' && $transaction->to_account_id) {
            $account->decrement('balance', $transaction->amount);
            $toAccount = Account::find($transaction->to_account_id);
            if ($toAccount) {
                $toAccount->increment('balance', $transaction->amount);
            }
        }
    }

    /**
     * Helper untuk membalikkan perubahan saldo (dari objek transaksi)
     */
    private function reverseTransaction(Transaction $transaction): void
    {
        $this->reverseTransactionValues(
            $transaction->type, 
            $transaction->amount, 
            $transaction->account_id, 
            $transaction->to_account_id
        );
    }

    /**
     * Helper utama untuk membalikkan nilai saldo
     */
    private function reverseTransactionValues($type, $amount, $accountId, $toAccountId): void
    {
        $account = Account::find($accountId);
        
        if (!$account) return;

        if ($type === 'income') {
            $account->decrement('balance', $amount);
        } elseif ($type === 'expense') {
            $account->increment('balance', $amount);
        } elseif ($type === 'transfer' && $toAccountId) {
            $account->increment('balance', $amount);
            $toAccount = Account::find($toAccountId);
            if ($toAccount) {
                $toAccount->decrement('balance', $amount);
            }
        }
    }
}
