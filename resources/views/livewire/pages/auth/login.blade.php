<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest-receipt')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('home', absolute: false), navigate: true);
    }
}; ?>

<div
    class="w-full max-w-md mx-auto"
    x-data="{
        showPassword: false,
        emailFocused: false,
        passwordFocused: false,
        emailValue: '',
        passwordValue: ''
    }"
>
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
                Struk Masuk &middot; LOGIN-{{ str_pad((string) random_int(1, 999), 5, '0', STR_PAD_LEFT) }}
            </p>
        </div>

        <hr class="border-t border-dashed border-border mb-4">

        <x-auth-session-status class="mb-4 font-mono text-xs" :status="session('status')" />

        <form wire:submit="login" class="space-y-3.5">
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
                    <input wire:model="form.email"
                           id="email"
                           type="email"
                           name="email"
                           required
                           autofocus
                           autocomplete="username"
                           x-model="emailValue"
                           @focus="emailFocused = true"
                           @blur="emailFocused = false"
                           placeholder="nama@email.com"
                           class="block w-full pl-6 pr-2 py-2 bg-transparent border-0 border-b-2 border-dashed border-border font-mono text-sm text-text placeholder:font-sans placeholder:text-text-muted/60 focus:border-solid focus:border-primary focus:ring-0 outline-none transition-colors duration-200">
                </div>
                <x-input-error :messages="$errors->get('form.email')" class="mt-0.5 text-xs font-medium text-danger" />
            </div>

            {{-- Password --}}
            <div class="space-y-1">
                <div class="flex items-center justify-between">
                    <x-input-label for="password" :value="__('Kata Sandi')" class="font-mono text-[10px] uppercase tracking-[0.12em] text-text-muted" />
                    @if (Route::has('password.request'))
                        <a class="text-[11px] font-semibold text-primary-hover hover:underline underline-offset-4" href="{{ route('password.request') }}" wire:navigate>
                            {{ __('Lupa?') }}
                        </a>
                    @endif
                </div>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pointer-events-none transition-colors duration-200"
                         :class="passwordFocused || passwordValue ? 'text-text' : 'text-text-muted'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <input wire:model="form.password"
                           id="password"
                           :type="showPassword ? 'text' : 'password'"
                           name="password"
                           required
                           autocomplete="current-password"
                           x-model="passwordValue"
                           @focus="passwordFocused = true"
                           @blur="passwordFocused = false"
                           placeholder="••••••••"
                           class="block w-full pl-6 pr-8 py-2 bg-transparent border-0 border-b-2 border-dashed border-border font-mono text-sm text-text placeholder:font-sans placeholder:text-text-muted/60 focus:border-solid focus:border-primary focus:ring-0 outline-none transition-colors duration-200">

                    <button type="button"
                            @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 flex items-center text-text-muted hover:text-text transition-colors"
                            aria-label="Tampilkan atau sembunyikan kata sandi">
                        <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg x-show="showPassword" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7 1.274-4.057 5.064-7 9.542-7 1.053 0 2.062.18 3 .512M7.525 5.525A10.05 10.05 0 0112 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-1.053 0-2.062-.18-3-.512" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                        </svg>
                    </button>
                </div>
                <p class="text-[11px] text-text-muted">Pastikan kombinasi sandi sudah benar &amp; peka huruf.</p>
                <x-input-error :messages="$errors->get('form.password')" class="mt-0.5 text-xs font-medium text-danger" />
            </div>

            {{-- Ingat saya --}}
            <div class="flex items-center gap-2">
                <input wire:model="form.remember" id="remember" type="checkbox" name="remember"
                       class="h-4 w-4 rounded border-border text-primary shadow-sm focus:ring-primary focus:ring-offset-0 transition">
                <label for="remember" class="text-sm text-text-muted cursor-pointer">{{ __('Ingat saya') }}</label>
            </div>

            {{-- Tombol Aksi --}}
            <div class="pt-1">
                <button type="submit"
                        class="w-full flex items-center justify-center py-3 bg-primary text-white rounded-card font-bold text-sm sm:text-base hover:bg-primary-hover transition-all duration-200 active:scale-[0.98]">
                    Masuk
                </button>
            </div>
        </form>

        {{-- Pemisah --}}
        <div class="relative flex items-center gap-3 my-4">
            <div class="flex-1 border-t border-dashed border-border"></div>
            <span class="font-mono text-[10px] uppercase tracking-[0.14em] text-text-muted">Atau</span>
            <div class="flex-1 border-t border-dashed border-border"></div>
        </div>

        {{-- Google --}}
        <a href="{{ route('auth.google.redirect') }}"
           class="w-full flex items-center justify-center gap-3 py-2.5 bg-white border border-border rounded-card font-semibold text-sm sm:text-base text-text hover:bg-bg transition-all duration-200 active:scale-[0.98]">
            <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
            </svg>
            Masuk dengan Google
        </a>

        {{-- Link ke daftar --}}
        @if (Route::has('register'))
            <div class="flex items-center justify-center flex-wrap gap-1 mt-5 pt-4 border-t border-border">
                <span class="text-sm text-text-muted">{{ __('Belum punya akun?') }}</span>
                <a class="text-sm font-bold text-text hover:underline underline-offset-4" href="{{ route('register') }}" wire:navigate>
                    {{ __('Daftar Sekarang') }}
                </a>
            </div>
        @endif
    </div>

    {{-- Sobekan bawah --}}
    <div
        class="h-3 w-full rotate-180 shrink-0"
        style="background-image:linear-gradient(-45deg, #ffffff 8px, transparent 0), linear-gradient(45deg, #ffffff 8px, transparent 0); background-size:16px 16px; background-repeat:repeat-x;"
        aria-hidden="true"
    ></div>
</div>