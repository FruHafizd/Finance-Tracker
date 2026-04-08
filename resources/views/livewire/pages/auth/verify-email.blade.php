<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
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

<div class="space-y-6" x-data="{ sending: false }">
    <div class="text-center space-y-3">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-slate-100 text-slate-900 rounded-full mb-2">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-slate-900">Verifikasi Email Anda</h2>
        <p class="text-sm text-slate-500 leading-relaxed px-4">
            {{ __('Terima kasih telah bergabung! Silakan klik tautan verifikasi yang telah kami kirimkan ke email Anda untuk mengaktifkan akun.') }}
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-start gap-3">
            <svg class="w-5 h-5 text-emerald-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-sm font-medium text-emerald-800">
                {{ __('Tautan verifikasi baru telah dikirim ke alamat email Anda.') }}
            </span>
        </div>
    @endif

    <div class="space-y-4 pt-4">
        <button wire:click="sendVerification" 
                @click="sending = true" 
                :disabled="sending"
                class="w-full flex items-center justify-center py-4 bg-slate-900 text-white rounded-2xl font-bold text-base hover:bg-slate-800 transition-all duration-200 active:scale-95 shadow-lg shadow-slate-200 disabled:opacity-50 disabled:cursor-not-allowed">
            <span x-show="!sending">{{ __('Kirim Ulang Email Verifikasi') }}</span>
            <span x-show="sending" x-cloak class="flex items-center gap-2">
                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Mengirim...
            </span>
        </button>

        <div class="text-center">
            <button wire:click="logout" type="submit" class="text-sm font-semibold text-slate-500 hover:text-slate-900 transition-colors underline underline-offset-4 decoration-slate-200">
                {{ __('Keluar dari Akun') }}
            </button>
        </div>
    </div>
</div>
