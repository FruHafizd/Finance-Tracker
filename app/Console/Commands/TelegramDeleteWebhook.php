<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramDeleteWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:delete-webhook';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete Telegram bot webhook';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Deleting Telegram Webhook...");

        try {
            $response = Telegram::deleteWebhook();

            if ($response) {
                $this->info("Telegram Webhook deleted successfully!");
            } else {
                $this->error("Failed to delete Telegram Webhook.");
            }
        } catch (\Exception $e) {
            $this->error("Error deleting webhook: " . $e->getMessage());
        }
    }
}
