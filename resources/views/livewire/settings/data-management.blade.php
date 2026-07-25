<div class="min-h-screen bg-slate-50 relative">

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            Backup dan Restore
        </h2>
    </x-slot>

    {{-- Progress Overlay --}}
    @if ($isProcessing)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm">
            <div class="bg-white p-6 rounded-2xl shadow-xl flex flex-col items-center max-w-sm w-full mx-4">
                <svg class="animate-spin h-10 w-10 text-primary mb-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <h3 class="text-lg font-bold text-slate-900">Memproses Data...</h3>
                <p class="text-sm text-slate-500 text-center mt-2">Mohon tunggu sebentar, operasi ini mungkin memakan waktu beberapa saat.</p>
            </div>
        </div>
    @endif

    {{-- Restore Confirmation Modal --}}
    @if ($showRestoreConfirm)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm">
            <div class="bg-white p-6 rounded-2xl shadow-xl max-w-md w-full mx-4 border-l-4 border-amber-500">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 shrink-0">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Konfirmasi Pemulihan</h3>
                </div>
                
                <p class="text-slate-600 text-sm mb-4">
                    Anda akan memulihkan data dari berkas backup: 
                    <span class="font-semibold block text-slate-900 mt-1 truncate">{{ $jsonFile?->getClientOriginalName() }}</span>
                </p>
                <p class="text-amber-700 bg-amber-50 rounded-lg p-3 text-sm font-medium mb-6 border border-amber-200">
                    <span class="font-bold">PERHATIAN:</span> Proses ini akan <span class="underline">menggantikan seluruh data Anda saat ini</span> dengan data dari berkas backup. Data yang ada akan terhapus.
                </p>

                <div class="flex items-center gap-3 justify-end">
                    <button wire:click="cancelRestore" class="px-4 py-2 rounded-lg text-slate-600 font-medium hover:bg-slate-100 transition-colors">
                        Batal
                    </button>
                    <button wire:click="restore" class="px-4 py-2 rounded-lg bg-amber-500 text-white font-medium hover:bg-amber-600 transition-colors flex items-center gap-2">
                        <svg wire:loading wire:target="restore" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="restore">Ya, Pulihkan Sekarang</span>
                        <span wire:loading wire:target="restore">Memulihkan...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <div class="px-4 sm:px-6 lg:px-8 py-6 sm:py-8">

        {{-- Stats Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-8">

            {{-- Stat 1: Rekening --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-5">
                <div class="flex items-start justify-between">
                    <span class="text-sm text-slate-500">Rekening</span>
                    <div class="w-9 h-9 bg-primary-light rounded-xl flex items-center justify-center text-primary shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            <rect x="1" y="4" width="22" height="16" rx="2" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M1 10h22" />
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-slate-900 tabular-nums">{{ number_format($stats['accounts'] ?? 0) }}</p>
            </div>

            {{-- Stat 2: Kategori --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-5">
                <div class="flex items-start justify-between">
                    <span class="text-sm text-slate-500">Kategori</span>
                    <div class="w-9 h-9 bg-primary-light rounded-xl flex items-center justify-center text-primary shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01" />
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-slate-900 tabular-nums">{{ number_format($stats['categories'] ?? 0) }}</p>
            </div>

            {{-- Stat 3: Transaksi --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-5">
                <div class="flex items-start justify-between">
                    <span class="text-sm text-slate-500">Transaksi</span>
                    <div class="w-9 h-9 bg-primary-light rounded-xl flex items-center justify-center text-primary shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 2v6h6M16 13H8M16 17H8" />
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-slate-900 tabular-nums">{{ number_format($stats['transactions'] ?? 0) }}</p>
            </div>
            
            {{-- Stat 4: Anggaran --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-5">
                <div class="flex items-start justify-between">
                    <span class="text-sm text-slate-500">Anggaran</span>
                    <div class="w-9 h-9 bg-primary-light rounded-xl flex items-center justify-center text-primary shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-slate-900 tabular-nums">{{ number_format($stats['budgets'] ?? 0) }}</p>
            </div>

        </div>

        {{-- Action Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-stretch mb-8">

            {{-- Backup Card --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-7 shadow-sm flex flex-col justify-between relative overflow-hidden">
                <div class="space-y-4 relative z-10">
                    <div class="flex items-center justify-between">
                        <div class="w-10 h-10 bg-primary-light rounded-xl flex items-center justify-center text-primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M7 10l5 5 5-5M12 15V3" />
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-full flex items-center gap-1.5 border border-emerald-100">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            Encrypted
                        </span>
                    </div>
                    <div>
                        <h4 class="text-xl font-bold text-slate-900 mb-1.5">Backup Data</h4>
                        <p class="text-slate-500 text-sm leading-relaxed">
                            Amankan seluruh data keuangan Anda ke dalam berkas terenkripsi yang aman dan tidak dapat dibaca sembarangan.
                        </p>
                    </div>
                </div>

                <button
                    wire:click="backup"
                    wire:loading.attr="disabled"
                    class="mt-6 w-full bg-primary text-white py-3 rounded-xl font-semibold text-sm hover:bg-primary-hover transition-colors disabled:opacity-60 active:scale-[0.98] relative z-10"
                >
                    Unduh Berkas Aman
                </button>
            </div>

            {{-- Restore Card --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-7 shadow-sm flex flex-col relative overflow-hidden">

                <div class="flex-1 space-y-4 relative z-10">

                    <div class="flex items-center justify-between">
                        <div class="w-10 h-10 bg-primary-light rounded-xl flex items-center justify-center text-primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-xl font-bold text-slate-900 mb-1.5">Restore Data</h4>
                        <p class="text-slate-500 text-sm leading-relaxed">
                            Pilih berkas *.backup untuk memulihkan seluruh data dan riwayat transaksi.
                        </p>
                    </div>

                    {{-- Upload Area --}}
                    <div class="relative mt-4">
                        <input type="file" wire:model="jsonFile" id="jsonFile" class="hidden" accept=".backup">
                        <label
                            for="jsonFile"
                            class="flex flex-col items-center justify-center p-6 border-2 border-dashed {{ $errors->has('jsonFile') ? 'border-red-300 bg-red-50/50' : 'border-primary/20 bg-primary-light/40' }} rounded-2xl cursor-pointer hover:border-primary/50 hover:bg-primary-light transition-colors group"
                        >
                            @if ($jsonFile)
                                <div class="flex flex-col items-center gap-2.5">
                                    <div class="w-10 h-10 bg-primary text-white rounded-xl flex items-center justify-center group-hover:scale-105 transition-transform">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-semibold text-slate-900 text-center break-all px-2">
                                        {{ $jsonFile->getClientOriginalName() }}
                                    </span>
                                </div>
                            @else
                                <svg class="w-8 h-8 text-primary/40 mb-2.5 group-hover:text-primary/70 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                <span class="text-sm font-medium text-slate-500 text-center group-hover:text-slate-700 transition-colors">
                                    Ketuk untuk memilih berkas
                                </span>
                                <span class="text-xs text-slate-400 mt-1 font-mono bg-slate-100 px-1.5 py-0.5 rounded">*.backup</span>
                            @endif
                        </label>
                        @error('jsonFile') <span class="text-xs text-red-500 mt-2 block font-medium">{{ $message }}</span> @enderror
                    </div>

                </div>

                <button
                    wire:click="confirmRestore"
                    wire:loading.attr="disabled"
                    @disabled(!$jsonFile)
                    class="mt-6 w-full bg-slate-900 text-white py-3 rounded-xl font-semibold text-sm hover:bg-slate-800 transition-colors disabled:opacity-30 disabled:cursor-not-allowed active:scale-[0.98] relative z-10"
                >
                    Konfirmasi Pemulihan
                </button>
            </div>

        </div>

        {{-- Riwayat Backup & Backup Terakhir --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

            {{-- Riwayat Backup --}}
            <div class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl p-6 sm:p-7">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-base font-bold text-slate-900">Riwayat Berkas</h4>
                    <span class="text-xs text-slate-400 font-medium bg-slate-100 px-2.5 py-1 rounded-full">{{ count($backups) }} berkas</span>
                </div>

                @if (count($backups) === 0)
                    <div class="text-center py-10">
                        <svg class="w-10 h-10 text-slate-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H6a2 2 0 01-2-2z" />
                        </svg>
                        <p class="text-sm text-slate-400">Belum ada riwayat backup.</p>
                    </div>
                @else
                    <div class="space-y-2">
                        @foreach ($backups as $index => $item)
                            <div wire:key="backup-{{ $item['path'] }}" class="flex items-center gap-3 p-3 border border-slate-100 rounded-xl hover:border-slate-300 transition-colors bg-white">
                                <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0 {{ $index === 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-50 text-slate-400' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if ($index === 0)
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H6a2 2 0 01-2-2z" />
                                        @endif
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-slate-900 truncate" title="{{ $item['name'] }}">{{ $item['name'] }}</p>
                                    <p class="text-xs text-slate-400 mt-0.5">
                                        {{ \Carbon\Carbon::createFromTimestamp($item['last_modified'])->diffForHumans() }} &middot; {{ round($item['size'] / 1024, 2) }} KB
                                    </p>
                                </div>
                                <div class="flex items-center gap-1">
                                    <button
                                        wire:click="downloadBackup('{{ $item['path'] }}')"
                                        class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-primary hover:bg-primary-light transition-colors shrink-0"
                                        title="Unduh"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M7 10l5 5 5-5M12 15V3" />
                                        </svg>
                                    </button>
                                    <button
                                        wire:click="deleteBackup('{{ $item['path'] }}')"
                                        wire:confirm="Hapus berkas backup ini secara permanen?"
                                        class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors shrink-0"
                                        title="Hapus"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="flex flex-col gap-4">
                <div class="bg-gradient-to-br from-primary to-primary-hover rounded-2xl p-6 text-white shadow-md relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mr-4 -mt-4 text-white/10">
                        <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                    </div>
                    <p class="text-xs text-primary-light mb-1 relative z-10 font-medium">Backup terakhir</p>
                    <p class="text-xl font-bold relative z-10">
                        {{ count($backups) > 0 ? \Carbon\Carbon::createFromTimestamp($backups[0]['last_modified'])->diffForHumans() : 'Belum ada' }}
                    </p>
                    @if (count($backups) > 0)
                        <p class="text-xs text-primary-light mt-1.5 relative z-10 opacity-90">{{ \Carbon\Carbon::createFromTimestamp($backups[0]['last_modified'])->format('d M Y, H:i') }}</p>
                    @endif
                </div>
                
                <div class="bg-white border border-slate-200 rounded-2xl p-5 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-slate-900">Auto Backup</p>
                        <p class="text-xs text-slate-500 mt-0.5">Otomatis mencadangkan data</p>
                    </div>
                    <button wire:click="toggleAutoBackup" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $autoBackupEnabled ? 'bg-primary' : 'bg-slate-200' }}">
                        <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $autoBackupEnabled ? 'translate-x-5' : 'translate-x-0' }}"></span>
                    </button>
                </div>
            </div>

        </div>

        {{-- Footer Security Note --}}
        <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-5 flex items-start gap-3">
            <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center text-emerald-600 shrink-0 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <p class="text-sm text-slate-600 leading-relaxed mt-1">
                <span class="font-semibold text-slate-900">Keamanan Enkripsi Lanjutan.</span>
                Seluruh data backup dienkripsi dengan standar AES-256-CBC. File tidak dapat dibaca di luar sistem tanpa kunci aplikasi yang unik.
            </p>
        </div>

    </div>
</div>