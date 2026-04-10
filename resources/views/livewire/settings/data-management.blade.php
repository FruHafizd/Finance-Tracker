<div class="relative min-h-screen bg-slate-50 overflow-hidden">

    {{-- Background Decorative Glow --}}
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[600px] pointer-events-none opacity-40">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-slate-200 rounded-full blur-[120px]"></div>
        <div class="absolute top-[20%] right-[-5%] w-[30%] h-[30%] bg-slate-100 rounded-full blur-[100px]"></div>
    </div>

    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 sm:pt-12 pb-16 sm:pb-20">

        {{-- Stats Grid --}}
        <div class="grid grid-cols-1 xs:grid-cols-3 sm:grid-cols-3 gap-3 sm:gap-5 mb-10 sm:mb-14">

            {{-- Stat 1 --}}
            <div class="bg-white px-5 py-4 sm:p-6 rounded-2xl sm:rounded-3xl border border-slate-100 shadow-sm flex items-center gap-4 hover:border-slate-300 transition-colors">
                <div class="w-11 h-11 sm:w-13 sm:h-13 bg-slate-50 rounded-xl sm:rounded-2xl flex items-center justify-center text-slate-900 shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <h3 class="text-xl sm:text-2xl font-black text-slate-900 leading-none tabular-nums">{{ number_format($transactionCount) }}</h3>
                    <p class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-[0.18em] mt-1.5 truncate">Transaksi</p>
                </div>
            </div>

            {{-- Stat 2 --}}
            <div class="bg-white px-5 py-4 sm:p-6 rounded-2xl sm:rounded-3xl border border-slate-100 shadow-sm flex items-center gap-4 hover:border-slate-300 transition-colors">
                <div class="w-11 h-11 sm:w-13 sm:h-13 bg-slate-50 rounded-xl sm:rounded-2xl flex items-center justify-center text-slate-900 shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <h3 class="text-xl sm:text-2xl font-black text-slate-900 leading-none tabular-nums">{{ number_format($categoryCount) }}</h3>
                    <p class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-[0.18em] mt-1.5 truncate">Kategori</p>
                </div>
            </div>

            {{-- Stat 3 --}}
            <div class="bg-white px-5 py-4 sm:p-6 rounded-2xl sm:rounded-3xl border border-slate-100 shadow-sm flex items-center gap-4 hover:border-slate-300 transition-colors">
                <div class="w-11 h-11 sm:w-13 sm:h-13 bg-slate-50 rounded-xl sm:rounded-2xl flex items-center justify-center text-slate-900 shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <h3 class="text-xl sm:text-2xl font-black text-slate-900 leading-none tabular-nums">{{ number_format($accountCount) }}</h3>
                    <p class="text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-[0.18em] mt-1.5 truncate">Rekening</p>
                </div>
            </div>

        </div>

        {{-- Action Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-8 items-stretch">

            {{-- Backup Card --}}
            <div class="bg-white border border-slate-200 p-7 sm:p-10 rounded-3xl sm:rounded-[3rem] shadow-sm flex flex-col justify-between group hover:shadow-xl hover:shadow-slate-100 hover:border-slate-300 transition-all duration-500">
                <div class="space-y-5 sm:space-y-7">
                    <div>
                        <h4 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight mb-3">
                            Backup <span class="text-slate-300">Data</span>
                        </h4>
                        <p class="text-slate-500 leading-relaxed text-sm">
                            Amankan seluruh data keuangan Anda ke dalam format JSON terenkripsi yang dapat Anda simpan di perangkat aman manapun.
                        </p>
                    </div>

                    {{-- Divider --}}
                    <div class="flex items-center gap-3">
                        <div class="flex-1 h-px bg-slate-100"></div>
                        <span class="text-[10px] font-bold text-slate-300 uppercase tracking-widest">JSON</span>
                        <div class="flex-1 h-px bg-slate-100"></div>
                    </div>
                </div>

                <button
                    wire:click="backup"
                    wire:loading.attr="disabled"
                    class="mt-10 sm:mt-12 w-full bg-slate-900 text-white py-5 sm:py-6 rounded-2xl sm:rounded-3xl font-black text-[10px] uppercase tracking-[0.25em] hover:bg-slate-800 transition-all shadow-lg shadow-slate-200/60 active:scale-[0.98]"
                >
                    <span wire:loading.remove wire:target="backup">Unduh Berkas Sekarang</span>
                    <span wire:loading wire:target="backup" class="flex items-center justify-center gap-2">
                        <svg class="animate-spin h-3 w-3 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Menyiapkan...
                    </span>
                </button>
            </div>

            {{-- Restore Card --}}
            <div class="bg-white border border-slate-200 p-7 sm:p-10 rounded-3xl sm:rounded-[3rem] shadow-sm flex flex-col group hover:shadow-xl hover:shadow-slate-100 hover:border-slate-300 transition-all duration-500">

                <div class="flex-1 space-y-5 sm:space-y-7">

                    {{-- Icon + Badge Row --}}
                    <div class="flex items-start justify-between">
                        @if (session('success'))
                            <span class="inline-flex items-center gap-1.5 bg-emerald-500 text-white px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest shadow-md shadow-emerald-100 animate-bounce">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                Sukses!
                            </span>
                        @endif
                    </div>

                    <div>
                        <h4 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight mb-3">
                            Restore <span class="text-slate-300">Data</span>
                        </h4>
                        <p class="text-slate-500 leading-relaxed text-sm">
                            Unggah berkas cadangan anda untuk memulihkan seluruh riwayat transaksi. Seluruh data akan disinkronisasi secara instan.
                        </p>
                    </div>

                    {{-- Upload Area --}}
                    <div class="relative">
                        <input type="file" wire:model="jsonFile" id="jsonFile" class="hidden" accept=".json">
                        <label
                            for="jsonFile"
                            class="flex flex-col items-center justify-center p-8 sm:p-12 border-2 border-dashed border-slate-100 bg-slate-50/50 rounded-2xl sm:rounded-[2.5rem] cursor-pointer hover:border-slate-400 hover:bg-white transition-all duration-300 group/upload"
                        >
                            @if ($jsonFile)
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-11 h-11 bg-slate-900 text-white rounded-2xl flex items-center justify-center shadow-md">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <span class="text-[10px] font-black text-slate-900 uppercase tracking-widest text-center break-all px-2">
                                        {{ $jsonFile->getClientOriginalName() }}
                                    </span>
                                </div>
                            @else
                                <svg class="w-10 h-10 sm:w-12 sm:h-12 text-slate-200 mb-3 group-hover/upload:text-slate-400 group-hover/upload:scale-110 transition-all duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.35em] text-center group-hover/upload:text-slate-700 transition-colors">
                                    Ketuk Untuk Memilih Berkas
                                </span>
                                <span class="text-[10px] text-slate-300 mt-1.5">*.json</span>
                            @endif
                        </label>
                    </div>

                </div>

                <button
                    wire:click="restore"
                    wire:loading.attr="disabled"
                    @disabled(!$jsonFile)
                    class="mt-8 sm:mt-10 w-full bg-slate-900 text-white py-5 sm:py-6 rounded-2xl sm:rounded-3xl font-black text-[10px] uppercase tracking-[0.25em] hover:bg-slate-800 transition-all shadow-lg shadow-slate-200/60 disabled:opacity-10 disabled:cursor-not-allowed active:scale-[0.98]"
                >
                    <span wire:loading.remove wire:target="restore">Mulai Sinkronisasi</span>
                    <span wire:loading wire:target="restore" class="flex items-center justify-center gap-2">
                        <svg class="animate-spin h-3 w-3 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Sinkronisasi Tengah Berlangsung...
                    </span>
                </button>
            </div>

        </div>

        {{-- Footer Security Note --}}
        <div class="mt-16 sm:mt-24 pt-8 sm:pt-10 border-t border-slate-200 text-center max-w-2xl mx-auto">
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.4em] leading-loose">
                <span class="text-slate-700">Nota Keamanan:</span>
                Seluruh proses enkripsi dan sinkronisasi dilakukan secara lokal.
                Berkas Anda tidak akan dipindahkan secara permanen di server kami.
            </p>
        </div>

    </div>
</div>