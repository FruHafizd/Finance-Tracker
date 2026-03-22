<?php

use \App\Livewire\Home\Index as Home;
use App\Livewire\Transactions\Recurring\Index as RecurringIndex;
use App\Livewire\Transactions\Index as Transaction;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/home', Home::class)->name('home');
    Route::get('/transaction', Transaction::class)->name('transaction.index');
    // Route::get('/recurring-transactions', RecurringIndex::class)->name('recurring-transactions'); //disable 


    Route::get('/export-excel', function (\Illuminate\Http\Request $request) {

        // Validasi input
        $request->validate([
            'start' => ['required', 'date'],
            'end'   => ['required', 'date', 'after_or_equal:start'],
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
});


Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';
