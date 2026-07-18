<div class="min-h-screen bg-slate-50">

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            Backup dan Restore
        </h2>
    </x-slot>

    <div class="px-4 sm:px-6 lg:px-8 py-6 sm:py-8">

        {{-- Stats Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">

            {{-- Stat 1 --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-5">
                <div class="flex items-start justify-between mb-3">
                    <span class="text-sm text-slate-500">Transaksi</span>
                    <div class="w-9 h-9 bg-sky-50 rounded-xl flex items-center justify-center text-sky-600 shrink-0">
                        <svg class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M6 2a1 1 0 00-1 1v18a1 1 0 001.496.868L9 20.5l2.504 1.368a1 1 0 00.992 0L15 20.5l2.504 1.368A1 1 0 0019 21V3a1 1 0 00-1-1H6zm2 5a1 1 0 011-1h6a1 1 0 110 2H9a1 1 0 01-1-1zm0 4a1 1 0 011-1h6a1 1 0 110 2H9a1 1 0 01-1-1zm0 4a1 1 0 011-1h3a1 1 0 110 2H9a1 1 0 01-1-1z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-slate-900 tabular-nums">{{ number_format($transactionCount) }}</p>
                <p class="text-xs text-slate-400 mt-1">Total tercatat</p>
            </div>

            {{-- Stat 2 --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-5">
                <div class="flex items-start justify-between mb-3">
                    <span class="text-sm text-slate-500">Kategori</span>
                    <div class="w-9 h-9 bg-sky-50 rounded-xl flex items-center justify-center text-sky-600 shrink-0">
                        <svg class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12.586 2.586A2 2 0 0011.172 2H4a2 2 0 00-2 2v7.172a2 2 0 00.586 1.414l8 8a2 2 0 002.828 0l7.172-7.172a2 2 0 000-2.828l-8-8zM7 8a1.5 1.5 0 110-3 1.5 1.5 0 010 3z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-slate-900 tabular-nums">{{ number_format($categoryCount) }}</p>
                <p class="text-xs text-slate-400 mt-1">Kategori aktif</p>
            </div>

            {{-- Stat 3 --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-5">
                <div class="flex items-start justify-between mb-3">
                    <span class="text-sm text-slate-500">Rekening</span>
                    <div class="w-9 h-9 bg-sky-50 rounded-xl flex items-center justify-center text-sky-600 shrink-0">
                        <svg class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2L2 8h20L12 2zM4 10v8H3a1 1 0 000 2h18a1 1 0 000-2h-1v-8h-2v8h-3v-8h-2v8h-2v-8H9v8H6v-8H4z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-slate-900 tabular-nums">{{ number_format($accountCount) }}</p>
                <p class="text-xs text-slate-400 mt-1">Rekening terdaftar</p>
            </div>

        </div>

        {{-- Action Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-stretch mb-8">

            {{-- Backup Card --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-7 shadow-sm flex flex-col justify-between">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M7 10l5 5 5-5M12 15V3" />
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full">JSON</span>
                    </div>
                    <div>
                        <h4 class="text-xl font-bold text-slate-900 mb-1.5">Backup Data</h4>
                        <p class="text-slate-500 text-sm leading-relaxed">
                            Amankan seluruh data keuangan Anda ke dalam berkas JSON yang bisa Anda simpan di perangkat aman manapun.
                        </p>
                    </div>
                </div>

                <button
                    wire:click="backup"
                    wire:loading.attr="disabled"
                    class="mt-6 w-full bg-blue-600 text-white py-3 rounded-xl font-semibold text-sm hover:bg-blue-700 transition-colors disabled:opacity-60 active:scale-[0.98]"
                >
                    <span wire:loading.remove wire:target="backup">Unduh Berkas Sekarang</span>
                    <span wire:loading wire:target="backup" class="flex items-center justify-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Menyiapkan...
                    </span>
                </button>
            </div>

            {{-- Restore Card --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-7 shadow-sm flex flex-col">

                <div class="flex-1 space-y-4">

                    <div class="flex items-center justify-between">
                        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </div>
                        @if (session('success'))
                            <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-600 px-2.5 py-1 rounded-full text-xs font-semibold">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                Berhasil
                            </span>
                        @endif
                    </div>

                    <div>
                        <h4 class="text-xl font-bold text-slate-900 mb-1.5">Restore Data</h4>
                        <p class="text-slate-500 text-sm leading-relaxed">
                            Unggah berkas cadangan Anda untuk memulihkan seluruh riwayat transaksi secara instan.
                        </p>
                    </div>

                    {{-- Upload Area --}}
                    <div class="relative">
                        <input type="file" wire:model="jsonFile" id="jsonFile" class="hidden" accept=".json">
                        <label
                            for="jsonFile"
                            class="flex flex-col items-center justify-center p-8 border-2 border-dashed border-blue-100 bg-blue-50/40 rounded-2xl cursor-pointer hover:border-blue-300 hover:bg-blue-50 transition-colors"
                        >
                            @if ($jsonFile)
                                <div class="flex flex-col items-center gap-2.5">
                                    <div class="w-10 h-10 bg-blue-600 text-white rounded-xl flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-semibold text-slate-900 text-center break-all px-2">
                                        {{ $jsonFile->getClientOriginalName() }}
                                    </span>
                                </div>
                            @else
                                <svg class="w-8 h-8 text-blue-300 mb-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                <span class="text-sm font-medium text-slate-500 text-center">
                                    Ketuk untuk memilih berkas
                                </span>
                                <span class="text-xs text-slate-400 mt-1">*.json</span>
                            @endif
                        </label>
                    </div>

                </div>

                <button
                    wire:click="restore"
                    wire:loading.attr="disabled"
                    @disabled(!$jsonFile)
                    class="mt-6 w-full bg-blue-600 text-white py-3 rounded-xl font-semibold text-sm hover:bg-blue-700 transition-colors disabled:opacity-30 disabled:cursor-not-allowed active:scale-[0.98]"
                >
                    <span wire:loading.remove wire:target="restore">Mulai Sinkronisasi</span>
                    <span wire:loading wire:target="restore" class="flex items-center justify-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Sinkronisasi berlangsung...
                    </span>
                </button>
            </div>

        </div>

        {{-- Riwayat Backup & Backup Otomatis --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

            {{-- Riwayat Backup --}}
            <div class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl p-6 sm:p-7">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-base font-bold text-slate-900">Riwayat Backup</h4>
                    <span class="text-xs text-slate-400">{{ count($backups) }} berkas</span>
                </div>

                @if (count($backups) === 0)
                    <div class="text-center py-10">
                        <svg class="w-10 h-10 text-slate-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H6a2 2 0 01-2-2z" />
                        </svg>
                        <p class="text-sm text-slate-400">Belum ada riwayat backup. Buat backup pertama Anda di atas.</p>
                    </div>
                @else
                    <div class="space-y-2">
                        @foreach ($backups as $index => $item)
                            <div class="flex items-center gap-3 p-3 border border-slate-100 rounded-xl hover:border-slate-300 transition-colors">
                                <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0 {{ $index === 0 ? 'bg-blue-50 text-blue-600' : 'bg-slate-50 text-slate-400' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if ($index === 0)
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H6a2 2 0 01-2-2z" />
                                        @endif
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-slate-900 truncate">{{ $item['name'] }}</p>
                                    <p class="text-xs text-slate-400">
                                        {{ $item['created_at']->diffForHumans() }} &middot; {{ $item['size'] }}
                                    </p>
                                </div>
                                <button
                                    wire:click="downloadBackup('{{ $item['path'] }}')"
                                    class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors shrink-0"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M7 10l5 5 5-5M12 15V3" />
                                    </svg>
                                </button>
                                <button
                                    wire:click="deleteBackup('{{ $item['path'] }}')"
                                    wire:confirm="Hapus berkas backup ini secara permanen?"
                                    class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors shrink-0"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="flex flex-col gap-4">

                <div class="bg-blue-600 rounded-2xl p-6 text-white">
                    <p class="text-xs text-blue-100 mb-1">Backup terakhir</p>
                    <p class="text-xl font-bold">
                        {{ count($backups) > 0 ? $backups[0]['created_at']->diffForHumans() : 'Belum ada' }}
                    </p>
                    @if (count($backups) > 0)
                        <p class="text-xs text-blue-100 mt-1">{{ $backups[0]['created_at']->format('d M Y, H:i') }}</p>
                    @endif
                </div>

            </div>

        </div>

        {{-- Footer Security Note --}}
        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-5 flex items-start gap-3">
            <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center text-sky-600 shrink-0">
                <svg class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2l8 3v6c0 5-3.4 8.9-8 11-4.6-2.1-8-6-8-11V5l8-3zm0 4.2L7 8v3c0 3.6 2.1 6.5 5 7.9 2.9-1.4 5-4.3 5-7.9V8l-5-1.8z"/>
                </svg>
            </div>
            <p class="text-sm text-slate-600 leading-relaxed">
                <span class="font-semibold text-slate-800">Catatan keamanan.</span>
                Seluruh proses backup dan restore dilakukan secara lokal. Berkas Anda tidak disimpan secara permanen di server kami.
            </p>
        </div>

    </div>
</div>