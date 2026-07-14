<?php

namespace App\Http\Controllers;

use App\Services\TelegramBotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TelegramWebhookController extends Controller
{
    public function __construct(
        protected TelegramBotService $botService
    ) {}

    public function handle(Request $request)
    {
        // 1. Ambil payload JSON dari Telegram
        $payload = $request->all();

        // 2. Validasi request ini benar-benar dari Telegram menggunakan secret token
        $secretToken = config('telegram.webhook_secret');
        $headerToken = $request->header('X-Telegram-Bot-Api-Secret-Token');

        if (empty($secretToken) || $headerToken !== $secretToken) {
            Log::warning('Unauthorized access attempt to Telegram Webhook endpoint.');
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // 3. Panggil TelegramBotService::handleUpdate($payload)
        try {
            $this->botService->handleUpdate($payload);
        } catch (\Exception $e) {
            Log::error('Error processing Telegram webhook update: ' . $e->getMessage(), [
                'exception' => $e,
                'payload' => $payload
            ]);
        }

        // 4. Return response 200 OK secepat mungkin
        return response()->json(['status' => 'OK'], 200);
    }
}
