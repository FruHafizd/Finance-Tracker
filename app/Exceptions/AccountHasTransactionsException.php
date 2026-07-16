<?php

namespace App\Exceptions;

use App\Models\Account;
use DomainException;

class AccountHasTransactionsException extends DomainException
{
    public function __construct(public readonly Account $account)
    {
        parent::__construct("Rekening {$account->name} tidak dapat dihapus karena masih memiliki riwayat transaksi.");
    }
}
