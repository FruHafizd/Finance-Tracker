<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramSetWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:set-webhook';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set Telegram bot webhook URL';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $url = config('app.url') . '/telegram/webhook';
        $secretToken = config('telegram.webhook_secret');

        $this->info("Setting Telegram Webhook to: {$url}");

        if (empty($secretToken)) {
            $this->warn("Warning: TELEGRAM_WEBHOOK_SECRET is empty in configuration!");
        }

        try {
            $response = Telegram::setWebhook([
                'url' => $url,
                'secret_token' => $secretToken,
            ]);

            if ($response) {
                $this->info("Telegram Webhook set successfully!");
            } else {
                $this->error("Failed to set Telegram Webhook.");
            }
        } catch (\Exception $e) {
            $this->error("Error setting webhook: " . $e->getMessage());
        }
    }
}
