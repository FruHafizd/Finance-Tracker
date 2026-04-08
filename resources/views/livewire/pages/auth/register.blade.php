<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('home', absolute: false), navigate: true);
    }
}; ?>

<div x-data="{ 
    showPassword: false,
    showConfirmPassword: false,
    nameFocused: false,
    emailFocused: false,
    passwordFocused: false,
    confirmFocused: false,
    nameValue: '',
    emailValue: '',
    passwordValue: '',
    confirmValue: ''
}">

    <form wire:submit="register" class="space-y-6">
        <!-- Name -->
        <div class="space-y-2">
            <x-input-label for="name" :value="__('Nama Lengkap')" class="text-slate-700 font-semibold ml-1" />
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors duration-200" :class="nameFocused || nameValue ? 'text-slate-800' : 'text-slate-400'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <input wire:model="name" 
                       id="name" 
                       type="text" 
                       name="name" 
                       required 
                       autofocus 
                       autocomplete="name"
                       x-model="nameValue"
                       @focus="nameFocused = true"
                       @blur="nameFocused = false"
                       class="block w-full pl-12 pr-4 py-3.5 rounded-2xl border-slate-200 transition-all duration-300 focus:ring-4 focus:ring-slate-100 focus:border-slate-800 outline-none"
                       :class="nameFocused || nameValue ? 'bg-white shadow-sm' : 'bg-slate-50 border-transparent'">
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-xs font-medium" />
        </div>

        <!-- Email Address -->
        <div class="space-y-2">
            <x-input-label for="email" :value="__('Email')" class="text-slate-700 font-semibold ml-1" />
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors duration-200" :class="emailFocused || emailValue ? 'text-slate-800' : 'text-slate-400'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                    </svg>
                </div>
                <input wire:model="email" 
                       id="email" 
                       type="email" 
                       name="email" 
                       required 
                       autocomplete="username"
                       x-model="emailValue"
                       @focus="emailFocused = true"
                       @blur="emailFocused = false"
                       class="block w-full pl-12 pr-4 py-3.5 rounded-2xl border-slate-200 transition-all duration-300 focus:ring-4 focus:ring-slate-100 focus:border-slate-800 outline-none"
                       :class="emailFocused || emailValue ? 'bg-white shadow-sm' : 'bg-slate-50 border-transparent'">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs font-medium" />
        </div>

        <!-- Password -->
        <div class="space-y-2">
            <x-input-label for="password" :value="__('Kata Sandi')" class="text-slate-700 font-semibold ml-1" />
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors duration-200" :class="passwordFocused || passwordValue ? 'text-slate-800' : 'text-slate-400'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                       class="block w-full pl-12 pr-12 py-3.5 rounded-2xl border-slate-200 transition-all duration-300 focus:ring-4 focus:ring-slate-100 focus:border-slate-800 outline-none"
                       :class="passwordFocused || passwordValue ? 'bg-white shadow-sm' : 'bg-slate-50 border-transparent'">
                
                <button type="button" 
                        @click="showPassword = !showPassword"
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-800 transition-colors">
                    <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="showPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7 1.274-4.057 5.064-7 9.542-7 1.053 0 2.062.18 3 .512M7.525 5.525A10.05 10.05 0 0112 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-1.053 0-2.062-.18-3-.512" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                    </svg>
                </button>
            </div>
            <p class="text-[11px] text-slate-400 mt-1 ml-1">Gunakan kombinasi minimal 8 karakter.</p>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs font-medium" />
        </div>

        <!-- Confirm Password -->
        <div class="space-y-2">
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Kata Sandi')" class="text-slate-700 font-semibold ml-1" />
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors duration-200" :class="confirmFocused || confirmValue ? 'text-slate-800' : 'text-slate-400'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                       class="block w-full pl-12 pr-12 py-3.5 rounded-2xl border-slate-200 transition-all duration-300 focus:ring-4 focus:ring-slate-100 focus:border-slate-800 outline-none"
                       :class="confirmFocused || confirmValue ? 'bg-white shadow-sm' : 'bg-slate-50 border-transparent'">
                
                <button type="button" 
                        @click="showConfirmPassword = !showConfirmPassword"
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-800 transition-colors">
                    <svg x-show="!showConfirmPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="showConfirmPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7 1.274-4.057 5.064-7 9.542-7 1.053 0 2.062.18 3 .512M7.525 5.525A10.05 10.05 0 0112 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-1.053 0-2.062-.18-3-.512" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-xs font-medium" />
        </div>

        <!-- Action Button -->
        <div class="pt-2">
            <button type="submit" class="w-full flex items-center justify-center py-4 bg-slate-900 text-white rounded-2xl font-bold text-base hover:bg-slate-800 transition-all duration-200 active:scale-95 shadow-lg shadow-slate-200">
                Daftar
            </button>
        </div>
    </form>

    <!-- Social Login Section -->
    <div class="mt-8 space-y-6">
        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-slate-100"></div>
            </div>
            <div class="relative flex justify-center text-sm font-bold">
                <span class="px-3 bg-white text-slate-400 uppercase tracking-widest">Atau</span>
            </div>
        </div>

        <a href="{{ route('auth.google.redirect') }}" class="w-full flex items-center justify-center gap-3 py-3.5 bg-white border border-slate-200 rounded-2xl font-bold text-slate-700 hover:bg-slate-50 transition-all duration-200 active:scale-95 shadow-sm">
            <svg class="w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
            </svg>
            Daftar dengan Google
        </a>
    </div>

    <!-- Login Link -->
    <div class="flex items-center justify-center mt-10 pt-6 border-t border-slate-50">
        <span class="text-sm text-slate-500 font-medium">{{ __("Sudah punya akun?") }}</span>
        <a class="ms-1 text-sm font-bold text-slate-800 hover:underline underline-offset-4" href="{{ route('login') }}" wire:navigate>
            {{ __('Masuk Sekarang') }}
        </a>
    </div>
</div>
