<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-text leading-tight">
                {{ __('Profil') }}
            </h2>
            <p class="text-sm text-text-muted mt-0.5">Kelola informasi akun dan keamanan Anda</p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

                {{-- Kolom kiri: form-form profil --}}
                <div class="lg:col-span-2 space-y-6">
                    <livewire:profile.update-profile-information-form />
                    <livewire:profile.update-password-form />
                    <livewire:profile.delete-user-form />
                </div>

                {{-- Kolom kanan: ringkasan akun --}}
                <div class="space-y-6 lg:sticky lg:top-6 self-start">

                    {{-- Card profil --}}
                    <div class="bg-bg-sidebar border border-border rounded-card p-6 text-center transition-shadow hover:shadow-sm">
                        <div class="relative w-20 h-20 mx-auto">
                            <div class="w-20 h-20 rounded-full bg-gradient-to-br from-primary to-primary/70 text-white flex items-center justify-center text-2xl font-semibold ring-4 ring-white shadow-sm">
                                {{ Str::of(auth()->user()->name)->substr(0, 1)->upper() }}
                            </div>

                            @if (auth()->user()->hasVerifiedEmail())
                                <span class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full bg-mint text-white flex items-center justify-center ring-2 ring-bg-sidebar" title="Email terverifikasi">
                                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                            @endif
                        </div>

                        <h3 class="mt-4 font-semibold text-text text-lg">{{ auth()->user()->name }}</h3>
                        <p class="text-sm text-text-muted">{{ auth()->user()->email }}</p>

                        <div class="mt-5 pt-5 border-t border-border text-left space-y-3">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-text-muted inline-flex items-center gap-1.5">
                                    <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2"/>
                                        <path stroke-linecap="round" d="M16 2v4M8 2v4M3 10h18"/>
                                    </svg>
                                    Bergabung sejak
                                </span>
                                <span class="font-medium text-text">{{ auth()->user()->created_at->translatedFormat('d M Y') }}</span>
                            </div>

                            <div class="flex items-center justify-between text-sm">
                                <span class="text-text-muted inline-flex items-center gap-1.5">
                                    <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="5" width="18" height="14" rx="2"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7l9 6 9-6"/>
                                    </svg>
                                    Status email
                                </span>
                                @if (auth()->user()->hasVerifiedEmail())
                                    <span class="inline-flex items-center gap-1 font-medium text-mint bg-mint/10 px-2 py-0.5 rounded-full text-xs">
                                        Terverifikasi
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 font-medium text-danger bg-danger/10 px-2 py-0.5 rounded-full text-xs">
                                        Belum verifikasi
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Card tips keamanan --}}
                    <div class="bg-primary-light/60 border border-primary-light rounded-card p-6 relative overflow-hidden">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-primary/15 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 2l7 4v6c0 5-3 8-7 10-4-2-7-5-7-10V6l7-4z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-text text-sm mb-1">Tips Keamanan</h4>
                                <p class="text-sm text-text-muted leading-relaxed">
                                    Gunakan password unik yang tidak dipakai di layanan lain, dan perbarui secara berkala untuk menjaga keamanan data keuanganmu.
                                </p>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</x-app-layout>