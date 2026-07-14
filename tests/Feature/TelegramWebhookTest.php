<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\TelegramAccount;
use App\Services\TelegramBotService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Telegram\Bot\Laravel\Facades\Telegram;
use Tests\TestCase;

class TelegramWebhookTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Config::set('telegram.webhook_secret', 'secret_token_123');
    }

    public function test_webhook_denies_request_without_secret_token()
    {
        $response = $this->postJson('/telegram/webhook', [
            'update_id' => 12345,
            'message' => [
                'chat' => ['id' => 99999],
                'text' => '/saldo',
            ],
        ]);

        $response->assertStatus(403);
    }

    public function test_webhook_denies_request_with_invalid_secret_token()
    {
        $response = $this->withHeaders([
            'X-Telegram-Bot-Api-Secret-Token' => 'wrong_token',
        ])->postJson('/telegram/webhook', [
            'update_id' => 12345,
            'message' => [
                'chat' => ['id' => 99999],
                'text' => '/saldo',
            ],
        ]);

        $response->assertStatus(403);
    }

    public function test_webhook_accepts_and_processes_valid_request()
    {
        $mockService = $this->createMock(TelegramBotService::class);
        $mockService->expects($this->once())
            ->method('handleUpdate')
            ->with($this->callback(function ($payload) {
                return isset($payload['update_id']) && $payload['update_id'] === 12345;
            }));

        $this->app->instance(TelegramBotService::class, $mockService);

        $response = $this->withHeaders([
            'X-Telegram-Bot-Api-Secret-Token' => 'secret_token_123',
        ])->postJson('/telegram/webhook', [
            'update_id' => 12345,
            'message' => [
                'chat' => ['id' => 99999],
                'text' => '/saldo',
            ],
        ]);

        $response->assertStatus(200);
        $response->assertJson(['status' => 'OK']);
    }
}
