<?php

use App\Livewire\TransactionHistory;
use \App\Livewire\GoalFinancial;
use \App\Livewire\Home\Index as Home;
use App\Livewire\Transactions\Recurring\Index as RecurringIndex;
use App\Livewire\Transactions\Index as Transaction;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/home', Home::class)->name('home');
    Route::get('/transaction', Transaction::class)->name('transaction.index');
    Route::get('/recurring-transactions', RecurringIndex::class)->name('recurring-transactions');
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
