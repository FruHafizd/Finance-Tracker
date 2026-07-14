<?php

namespace App\Services;

use App\Models\Account;
use App\Models\User;
use Illuminate\Support\Collection;

class AccountService
{
    /**
     * Dapatkan semua rekening milik user.
     */
    public function getUserAccounts(): Collection
    {
        // Global scope 'user' pada Account otomatis memfilter berdasarkan Auth::id()
        return Account::orderBy('sort_order', 'asc')
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     * Dapatkan semua rekening milik user tertentu.
     *
     * Menggunakan withoutGlobalScope agar bisa dipakai di konteks
     * non-Auth (misalnya console command untuk Telegram Bot).
     */
    public function getAccountsForUser(User $user): Collection
    {
        return Account::withoutGlobalScope('user')
            ->where('user_id', $user->id)
            ->orderBy('sort_order', 'asc')
            ->orderBy('name', 'asc')
            ->get();
    }
}
