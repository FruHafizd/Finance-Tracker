<?php

use App\Http\Controllers\LandingController;
use App\Livewire\Accounts\AccountList;
use App\Livewire\Budgets\BudgetIndex;
use \App\Livewire\Home\Index as Home;
use App\Livewire\Transactions\Index as Transaction;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('landing');

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
    Route::get('/data', \App\Livewire\Settings\DataManagement::class)->name('settings.data');
    Route::get('/telegram', \App\Livewire\Settings\TelegramLinkSettings::class)->name('telegram');
    Route::get('/financial-calendar', \App\Livewire\FinancialCalendar\CalendarPage::class)->name('financial-calendar.index');

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

Route::post('/telegram/webhook', [\App\Http\Controllers\TelegramWebhookController::class, 'handle'])
    ->middleware('throttle:30,1');

// Cron endpoint untuk Telegram alerts (dipanggil oleh cron-job.org / external cron service)
Route::get('/api/cron/telegram-alerts', function (\Illuminate\Http\Request $request) {
    // Validasi secret token via query parameter ?secret=xxx
    $secret     = config('telegram.cron_secret');
    $queryToken = $request->input('secret');

    if (empty($secret) || $queryToken !== $secret) {
        \Illuminate\Support\Facades\Log::warning('Unauthorized access attempt to cron/telegram-alerts endpoint.');
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    // Panggil artisan command
    \Illuminate\Support\Facades\Artisan::call('telegram:send-alerts');

    return response()->json([
        'status'  => 'OK',
        'output'  => \Illuminate\Support\Facades\Artisan::output(),
    ]);
})->middleware('throttle:6,1'); // Max 6 request per menit

require __DIR__ . '/auth.php';
