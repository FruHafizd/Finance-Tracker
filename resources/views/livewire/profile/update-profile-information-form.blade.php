<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component
{
    public string $name = '';
    public string $email = '';

    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('home', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section class="bg-bg-sidebar border border-border rounded-card p-6 md:p-8">
    <header class="flex items-start gap-4 mb-6">
        <div class="shrink-0 w-10 h-10 rounded-full bg-primary-light flex items-center justify-center">
            <svg class="w-5 h-5 text-primary-hover" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21a8 8 0 1 0-16 0" /><circle cx="12" cy="7" r="4" />
            </svg>
        </div>
        <div>
            <h2 class="text-base font-semibold text-text">
                {{ __('Informasi Profil') }}
            </h2>
            <p class="mt-1 text-sm text-text-muted">
                {{ __('Perbarui informasi profil dan alamat email akun kamu.') }}
            </p>
        </div>
    </header>

    <form wire:submit="updateProfileInformation" class="space-y-5 max-w-lg">
        <div>
            <x-input-label for="name" :value="__('Nama')" />
            <x-text-input wire:model="name" id="name" name="name" type="text" class="mt-1.5 block w-full" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" name="email" type="email" class="mt-1.5 block w-full" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                <div class="mt-3 rounded-card bg-primary-light/60 border border-primary-light px-4 py-3">
                    <p class="text-sm text-text">
                        {{ __('Alamat email kamu belum diverifikasi.') }}
                        <button wire:click.prevent="sendVerification" class="font-medium underline text-primary-hover hover:text-text focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 rounded">
                            {{ __('Kirim ulang email verifikasi') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm font-medium text-mint">
                            {{ __('Tautan verifikasi baru telah dikirim ke alamat email kamu.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-1">
            <x-primary-button>{{ __('Simpan') }}</x-primary-button>

            <x-action-message class="text-sm" on="profile-updated">
                {{ __('Tersimpan.') }}
            </x-action-message>
        </div>
    </form>
</section>