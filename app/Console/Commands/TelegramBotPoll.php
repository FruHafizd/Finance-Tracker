<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\TelegramBotService;
use App\Services\TransactionService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramBotPoll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:poll';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Poll Telegram Updates and process transaction inputs';

    public function __construct(
        protected TelegramBotService $botService,
        protected TransactionService $transactionService
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Telegram bot polling started...");

        $offset = 0;

        while (true) {
            try {
                $updates = Telegram::getUpdates([
                    'offset' => $offset,
                    'timeout' => 30,
                ]);

                foreach ($updates as $update) {
                    $offset = $update->getUpdateId() + 1;
                    $this->botService->handleUpdate($update);
                }
            } catch (\Exception $e) {
                $this->error("Error during polling: " . $e->getMessage());
                sleep(2);
            }
        }
    }
}
