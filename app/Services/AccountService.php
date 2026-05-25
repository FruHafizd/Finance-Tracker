<?php

namespace App\Services;

use App\Models\Account;
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
}
