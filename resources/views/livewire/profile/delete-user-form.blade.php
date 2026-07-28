<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    public string $password = '';

    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<section class="bg-danger/[0.03] border border-danger/25 rounded-card p-6 md:p-8">
    <header class="flex items-start gap-4 mb-5">
        <div class="shrink-0 w-10 h-10 rounded-full bg-danger/10 flex items-center justify-center">
            <svg class="w-5 h-5 text-danger" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 9v4m0 4h.01M10.29 3.86l-8.18 14.18A2 2 0 0 0 4 21h16a2 2 0 0 0 1.89-2.96L13.71 3.86a2 2 0 0 0-3.42 0Z" />
            </svg>
        </div>
        <div>
            <h2 class="text-base font-semibold text-text">
                {{ __('Hapus Akun') }}
            </h2>
            <p class="mt-1 text-sm text-text-muted max-w-lg">
                {{ __('Setelah akun kamu dihapus, semua data dan sumber daya di dalamnya akan dihapus secara permanen. Sebelum menghapus akun, silakan unduh data atau informasi apa pun yang ingin kamu simpan.') }}
            </p>
        </div>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Hapus Akun') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable>
        <form wire:submit="deleteUser" class="p-6">

            <div class="flex items-start gap-3 mb-1">
                <div class="shrink-0 w-9 h-9 rounded-full bg-danger/10 flex items-center justify-center mt-0.5">
                    <svg class="w-4.5 h-4.5 text-danger" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 9v4m0 4h.01M10.29 3.86l-8.18 14.18A2 2 0 0 0 4 21h16a2 2 0 0 0 1.89-2.96L13.71 3.86a2 2 0 0 0-3.42 0Z" />
                    </svg>
                </div>
                <h2 class="text-base font-semibold text-text pt-1">
                    {{ __('Yakin ingin menghapus akun kamu?') }}
                </h2>
            </div>

            <p class="mt-2 text-sm text-text-muted">
                {{ __('Setelah akun kamu dihapus, semua data dan sumber daya di dalamnya akan dihapus secara permanen. Masukkan password untuk mengonfirmasi bahwa kamu ingin menghapus akun ini secara permanen.') }}
            </p>

            <div class="mt-5">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <x-text-input
                    wire:model="password"
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full"
                    placeholder="{{ __('Password') }}"
                />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Batal') }}
                </x-secondary-button>

                <x-danger-button>
                    {{ __('Hapus Akun') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>