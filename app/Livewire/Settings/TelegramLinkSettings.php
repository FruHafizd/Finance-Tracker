<?php

namespace App\Livewire\Settings;

use App\Models\TelegramAccount;
use App\Services\TelegramBotService;
use Livewire\Attributes\Layout;
use Livewire\Component;

class TelegramLinkSettings extends Component
{
    public bool $isLinked = false;

    public ?string $linkCode = null;

    public ?string $expiresAt = null;

    public ?string $linkedAt = null;

    public bool $showUnlinkConfirm = false;

    public function mount(): void
    {
        $this->refreshStatus();
    }

    /**
     * Cek ulang status koneksi Telegram user saat ini.
     */
    public function refreshStatus(): void
    {
        $account = TelegramAccount::where('user_id', auth()->id())
            ->whereNotNull('telegram_chat_id')
            ->first();

        $this->isLinked = (bool) $account;
        $this->linkedAt = $account?->updated_at?->translatedFormat('d M Y, H:i');

        // Kalau sudah linked, kode yang sedang aktif (kalau ada) jadi tidak relevan lagi
        if ($this->isLinked) {
            $this->linkCode = null;
            $this->expiresAt = null;
        }
    }

    /**
     * Generate kode link baru untuk user yang sedang login.
     *
     * NOTE: sesuaikan nama method ini dengan yang sebenarnya ada
     * di TelegramBotService kamu kalau berbeda.
     */
    public function generateCode(): void
    {
        /** @var TelegramBotService $service */
        $service = app(TelegramBotService::class);

        $this->linkCode = $service->generateLinkCode(auth()->user());
        $this->expiresAt = now()->addMinutes(5)->translatedFormat('H:i');

        $this->dispatch('link-code-generated');
    }

    /**
     * Putuskan koneksi akun Telegram.
     */
    public function unlinkAccount(): void
    {
        /** @var TelegramBotService $service */
        $service = app(TelegramBotService::class);

        // Kalau method ini belum ada di TelegramBotService, tambahkan:
        // public function unlinkAccount(User $user): void { ... }
        $service->unlinkAccount(auth()->user());

        $this->showUnlinkConfirm = false;
        $this->refreshStatus();

        $this->dispatch('notify', type: 'success', message: 'Akun Telegram berhasil diputuskan.');
    }

    public function confirmUnlink(): void
    {
        $this->showUnlinkConfirm = true;
    }

    public function cancelUnlink(): void
    {
        $this->showUnlinkConfirm = false;
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.settings.telegram-link-settings');
    }
}