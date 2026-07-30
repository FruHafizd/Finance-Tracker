<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest-receipt')] class extends Component
{
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>

<div class="w-full max-w-md mx-auto" x-data="{ emailFocused: false, emailValue: '' }">
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
                Struk Lupa Sandi &middot; RESET-{{ str_pad((string) random_int(1, 999), 5, '0', STR_PAD_LEFT) }}
            </p>
        </div>

        <hr class="border-t border-dashed border-border mb-5">

        <p class="text-sm text-text-muted leading-relaxed mb-4">
            {{ __('Lupa kata sandi? Tidak masalah. Masukkan email kamu dan kami akan kirimkan tautan untuk membuat sandi baru.') }}
        </p>

        <x-auth-session-status class="mb-4 font-mono text-xs" :status="session('status')" />

        <form wire:submit="sendPasswordResetLink" class="space-y-4">
            {{-- Email --}}
            <div class="space-y-1">
                <x-input-label for="email" :value="__('Email')" class="font-mono text-[10px] uppercase tracking-[0.12em] text-text-muted" />
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pointer-events-none transition-colors duration-200"
                         :class="emailFocused || emailValue ? 'text-text' : 'text-text-muted'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                        </svg>
                    </div>
                    <input wire:model="email"
                           id="email"
                           type="email"
                           name="email"
                           required
                           autofocus
                           x-model="emailValue"
                           @focus="emailFocused = true"
                           @blur="emailFocused = false"
                           placeholder="nama@email.com"
                           class="block w-full pl-6 pr-2 py-2 bg-transparent border-0 border-b-2 border-dashed border-border font-mono text-sm text-text placeholder:font-sans placeholder:text-text-muted/60 focus:border-solid focus:border-primary focus:ring-0 outline-none transition-colors duration-200">
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-0.5 text-xs font-medium text-danger" />
            </div>

            <div class="flex justify-end pt-1">
                <button type="submit"
                        class="px-6 py-3 bg-primary text-white rounded-card font-bold text-sm sm:text-base hover:bg-primary-hover transition-all duration-200 active:scale-[0.98]">
                    {{ __('Kirim Tautan Reset') }}
                </button>
            </div>
        </form>
    </div>

    {{-- Sobekan bawah --}}
    <div
        class="h-3 w-full rotate-180 shrink-0"
        style="background-image:linear-gradient(-45deg, #ffffff 8px, transparent 0), linear-gradient(45deg, #ffffff 8px, transparent 0); background-size:16px 16px; background-repeat:repeat-x;"
        aria-hidden="true"
    ></div>
</div>