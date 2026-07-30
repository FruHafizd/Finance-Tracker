<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('layouts.guest-receipt')] class extends Component
{
    #[Locked]
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Mount the component.
     */
    public function mount(string $token): void
    {
        $this->token = $token;

        $this->email = request()->string('email');
    }

    /**
     * Reset the password for the given user.
     */
    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        if ($status != Password::PASSWORD_RESET) {
            $this->addError('email', __($status));

            return;
        }

        Session::flash('status', __($status));

        $this->redirectRoute('login', navigate: true);
    }
}; ?>

<div
    class="w-full max-w-md mx-auto"
    x-data="{
        showPassword: false,
        showConfirmPassword: false,
        emailFocused: false,
        passwordFocused: false,
        confirmFocused: false,
        emailValue: '{{ $email }}',
        passwordValue: '',
        confirmValue: ''
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
                Struk Atur Ulang Sandi &middot; RESET-{{ str_pad((string) random_int(1, 999), 5, '0', STR_PAD_LEFT) }}
            </p>
        </div>

        <hr class="border-t border-dashed border-border mb-5">

        <form wire:submit="resetPassword" class="space-y-3.5">
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
                           autocomplete="username"
                           x-model="emailValue"
                           @focus="emailFocused = true"
                           @blur="emailFocused = false"
                           placeholder="nama@email.com"
                           class="block w-full pl-6 pr-2 py-2 bg-transparent border-0 border-b-2 border-dashed border-border font-mono text-sm text-text placeholder:font-sans placeholder:text-text-muted/60 focus:border-solid focus:border-primary focus:ring-0 outline-none transition-colors duration-200">
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-0.5 text-xs font-medium text-danger" />
            </div>

            {{-- Kata Sandi Baru --}}
            <div class="space-y-1">
                <x-input-label for="password" :value="__('Kata Sandi Baru')" class="font-mono text-[10px] uppercase tracking-[0.12em] text-text-muted" />
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pointer-events-none transition-colors duration-200"
                         :class="passwordFocused || passwordValue ? 'text-text' : 'text-text-muted'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <input wire:model="password"
                           id="password"
                           :type="showPassword ? 'text' : 'password'"
                           name="password"
                           required
                           autocomplete="new-password"
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
                <x-input-error :messages="$errors->get('password')" class="mt-0.5 text-xs font-medium text-danger" />
            </div>

            {{-- Konfirmasi Kata Sandi --}}
            <div class="space-y-1">
                <x-input-label for="password_confirmation" :value="__('Konfirmasi Kata Sandi')" class="font-mono text-[10px] uppercase tracking-[0.12em] text-text-muted" />
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pointer-events-none transition-colors duration-200"
                         :class="confirmFocused || confirmValue ? 'text-text' : 'text-text-muted'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <input wire:model="password_confirmation"
                           id="password_confirmation"
                           :type="showConfirmPassword ? 'text' : 'password'"
                           name="password_confirmation"
                           required
                           autocomplete="new-password"
                           x-model="confirmValue"
                           @focus="confirmFocused = true"
                           @blur="confirmFocused = false"
                           placeholder="••••••••"
                           class="block w-full pl-6 pr-8 py-2 bg-transparent border-0 border-b-2 border-dashed border-border font-mono text-sm text-text placeholder:font-sans placeholder:text-text-muted/60 focus:border-solid focus:border-primary focus:ring-0 outline-none transition-colors duration-200">

                    <button type="button"
                            @click="showConfirmPassword = !showConfirmPassword"
                            class="absolute inset-y-0 right-0 flex items-center text-text-muted hover:text-text transition-colors"
                            aria-label="Tampilkan atau sembunyikan konfirmasi kata sandi">
                        <svg x-show="!showConfirmPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg x-show="showConfirmPassword" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7 1.274-4.057 5.064-7 9.542-7 1.053 0 2.062.18 3 .512M7.525 5.525A10.05 10.05 0 0112 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-1.053 0-2.062-.18-3-.512" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-0.5 text-xs font-medium text-danger" />
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit"
                        class="w-full sm:w-auto px-6 py-3 bg-primary text-white rounded-card font-bold text-sm sm:text-base hover:bg-primary-hover transition-all duration-200 active:scale-[0.98]">
                    {{ __('Atur Ulang Sandi') }}
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