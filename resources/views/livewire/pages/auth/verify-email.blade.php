<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest-receipt')] class extends Component
{
    /**
     * Send an email verification notification to the user.
     */
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('home', absolute: false), navigate: true);

            return;
        }

        Auth::user()->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div class="w-full max-w-md mx-auto" x-data="{ sending: false }">
    {{-- Sobekan atas --}}
    <div
        class="h-3 w-full shrink-0"
        style="background-image:linear-gradient(-45deg, #ffffff 8px, transparent 0), linear-gradient(45deg, #ffffff 8px, transparent 0); background-size:16px 16px; background-repeat:repeat-x;"
        aria-hidden="true"
    ></div>

    {{-- Badan struk --}}
    <div class="relative overflow-hidden bg-white px-5 py-6 sm:px-8 sm:py-7 shadow-[0_18px_34px_-12px_rgba(15,23,42,0.16)]">

        {{-- Stempel watermark --}}
        <div
            class="hidden lg:block absolute top-10 -right-9 rotate-[18deg] font-mono text-[11px] font-bold uppercase tracking-[0.14em] text-primary border-2 border-primary rounded-md px-11 py-1 opacity-[0.14] pointer-events-none select-none"
            aria-hidden="true"
        >
            Finansiku
        </div>

        {{-- Header brand --}}
        <div class="text-center mb-4">
            <div class="inline-flex items-center gap-2">
                <img src="{{ asset('images/logo.png') }}" alt="Finansiku" class="h-7 w-7 object-contain shrink-0">
                <span class="font-bold text-lg text-text tracking-tight">Finansiku</span>
            </div>
            <p class="font-mono text-[10px] tracking-[0.14em] uppercase text-text-muted mt-1.5">
                Struk Verifikasi &middot; VERIF-{{ str_pad((string) random_int(1, 999), 5, '0', STR_PAD_LEFT) }}
            </p>
        </div>

        <hr class="border-t border-dashed border-border mb-5">

        {{-- Ikon & pesan --}}
        <div class="text-center space-y-2.5 mb-5">
            <div class="inline-flex items-center justify-center w-14 h-14 bg-primary/10 text-primary rounded-full">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
            <h2 class="text-lg font-bold text-text">Verifikasi email kamu</h2>
            <p class="text-sm text-text-muted leading-relaxed px-2">
                {{ __('Terima kasih telah bergabung! Klik tautan verifikasi yang sudah kami kirim ke email kamu untuk mengaktifkan akun.') }}
            </p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="p-3.5 bg-mint/10 border border-dashed border-mint rounded-card flex items-start gap-2.5 mb-5">
                <svg class="w-5 h-5 text-mint mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-medium text-text">
                    {{ __('Tautan verifikasi baru sudah dikirim ke alamat email kamu.') }}
                </span>
            </div>
        @endif

        <div class="space-y-3.5">
            <button wire:click="sendVerification"
                    @click="sending = true"
                    :disabled="sending"
                    class="w-full flex items-center justify-center py-3 bg-primary text-white rounded-card font-bold text-sm sm:text-base hover:bg-primary-hover transition-all duration-200 active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed">
                <span x-show="!sending">{{ __('Kirim Ulang Email Verifikasi') }}</span>
                <span x-show="sending" x-cloak class="flex items-center gap-2">
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Mengirim...
                </span>
            </button>

            <div class="text-center pt-1">
                <button wire:click="logout" type="button" class="text-sm font-semibold text-text-muted hover:text-text transition-colors underline underline-offset-4">
                    {{ __('Keluar dari Akun') }}
                </button>
            </div>
        </div>
    </div>

    {{-- Sobekan bawah --}}
    <div
        class="h-3 w-full rotate-180 shrink-0"
        style="background-image:linear-gradient(-45deg, #ffffff 8px, transparent 0), linear-gradient(45deg, #ffffff 8px, transparent 0); background-size:16px 16px; background-repeat:repeat-x;"
        aria-hidden="true"
    ></div>
</div>