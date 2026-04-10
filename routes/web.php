<?php

use App\Livewire\Accounts\AccountList;
use App\Livewire\Budgets\BudgetIndex;
use \App\Livewire\Home\Index as Home;
use App\Livewire\Transactions\Index as Transaction;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('landing');
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/home', Home::class)->name('home');
    Route::get('/transaction', Transaction::class)->name('transaction.index');
    Route::get('/budget', BudgetIndex::class)->name('budget.index');

    Route::get('/export-excel', function (\Illuminate\Http\Request $request) {

        // Validasi input
        $request->validate([
            'start' => ['required', 'date', 'date_format:Y-m-d'],
            'end'   => ['required', 'date', 'date_format:Y-m-d', 'after_or_equal:start'],
        ]);

        $writer = \Maatwebsite\Excel\Facades\Excel::raw(
            new \App\Exports\TransactionExport($request->start, $request->end),
            \Maatwebsite\Excel\Excel::XLSX
        );

        $fileName = 'laporan-transaksi-' . $request->start . '-sd-' . $request->end . '.xlsx';

        return response($writer, 200, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Content-Length'      => strlen($writer),
            'Cache-Control'       => 'no-cache, no-store',
        ]);

    })->middleware('signed')->name('export.excel');

    Route::get('/accounts', AccountList::class)->name('account.index');
    Route::get('/settings/data', \App\Livewire\Settings\DataManagement::class)->name('settings.data');

});


Route::view('profile', 'profile')
    ->middleware(['auth', 'verified'])
    ->name('profile');

Route::get('auth/google/redirect', [App\Http\Controllers\Auth\GoogleController::class, 'redirect'])
    ->name('auth.google.redirect');

Route::get('/auth/google/callback', [App\Http\Controllers\Auth\GoogleController::class, 'callback'])
    ->name('auth.google.callback');

// Legal Pages
Route::view('/privacy-policy', 'legal.privacy')->name('legal.privacy');
Route::view('/terms-of-service', 'legal.terms')->name('legal.terms');

require __DIR__ . '/auth.php';
