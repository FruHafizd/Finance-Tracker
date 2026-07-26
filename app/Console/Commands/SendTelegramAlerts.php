<?php

namespace App\Console\Commands;

use App\Services\TelegramAlertService;
use Illuminate\Console\Command;

class SendTelegramAlerts extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'telegram:send-alerts';

    /**
     * The console command description.
     */
    protected $description = 'Kirim alert Telegram untuk budget dan reminder jatuh tempo';

    public function __construct(
        protected TelegramAlertService $alertService
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Memulai pengecekan budget alerts...');
        $this->alertService->checkAndSendBudgetAlerts();
        $this->info('Budget alerts selesai.');

        $this->info('Memulai pengecekan due date reminders...');
        $this->alertService->checkAndSendDueDateReminders();
        $this->info('Due date reminders selesai.');

        $this->info('Semua alert Telegram selesai diproses.');
        return self::SUCCESS;
    }
}
