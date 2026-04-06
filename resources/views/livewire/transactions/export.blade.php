<x-modal name="modal-export" focusable>
    <div class="max-h-[92vh] overflow-y-auto">

        <!-- HEADER -->
        <div class="sticky top-0 z-10 bg-white/80 backdrop-blur-md border-b border-gray-100 px-6 py-5 sm:px-8">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 tracking-tight">Export Laporan</h2>
                    <p class="text-sm text-gray-500 mt-1 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>
                        Unduh data transaksi dalam format Excel
                    </p>
                </div>
                <button
                    x-on:click="$dispatch('close-modal', 'modal-export')"
                    type="button"
                    class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-full transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- BODY -->
        <div
            x-data="{
                hasError: false,
                errorMsg: '',
                phase: 'idle',
                /*
                 * phase:
                 *   idle      → belum ada aksi
                 *   preparing → server sedang memproses (Livewire berjalan)
                 *   downloading → file sedang diunduh ke browser (XHR progress)
                 *   done      → selesai
                 */
                dlProgress: 0,
                dlLoaded: 0,
                dlTotal: 0,

                formatBytes(bytes) {
                    if (bytes === 0) return '0 B';
                    const k = 1024;
                    const sizes = ['B', 'KB', 'MB'];
                    const i = Math.floor(Math.log(bytes) / Math.log(k));
                    return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
                },

                async doExport() {
                    this.hasError   = false;
                    this.errorMsg   = '';
                    this.dlProgress = 0;
                    this.dlLoaded   = 0;
                    this.dlTotal    = 0;

                    const start = $wire.startDate;
                    const end   = $wire.endDate;

                    if (!start || !end) {
                        this.hasError = true;
                        this.errorMsg = 'Tanggal awal dan tanggal akhir wajib diisi.';
                        return;
                    }
                    if (start > end) {
                        this.hasError = true;
                        this.errorMsg = 'Tanggal awal tidak boleh lebih dari tanggal akhir.';
                        return;
                    }

                    /* ── FASE 1: server memproses ── */
                    this.phase = 'preparing';

                    let url;
                    try {
                        url = await $wire.getExportUrl();
                    } catch (e) {
                        this.phase    = 'idle';
                        this.hasError = true;
                        this.errorMsg = 'Gagal menyiapkan file. Silakan coba lagi.';
                        return;
                    }

                    /* ── FASE 2: download dengan progress tracking ── */
                    this.phase      = 'downloading';
                    this.dlProgress = 0;

                    // Untuk file kecil: animasi paksa dari 0 → 90% dulu
                    // sisanya 100% di onload
                    const fakeInterval = setInterval(() => {
                        if (this.dlProgress < 90) {
                            this.dlProgress += 10;
                        } else {
                            clearInterval(fakeInterval);
                        }
                    }, 80);

                    await new Promise((resolve, reject) => {
                        const xhr = new XMLHttpRequest();
                        xhr.open('GET', url, true);
                        xhr.responseType = 'arraybuffer';

                        xhr.onprogress = (e) => {
                            // Kalau progress real lebih dari fake, pakai yang real
                            if (e.lengthComputable && e.total > 0) {
                                const real = Math.round((e.loaded / e.total) * 100);
                                if (real > this.dlProgress) {
                                    clearInterval(fakeInterval);
                                    this.dlProgress = real;
                                    this.dlTotal    = e.total;
                                    this.dlLoaded   = e.loaded;
                                }
                            }
                        };

                        xhr.onload = () => {
                            clearInterval(fakeInterval);
                            if (xhr.status === 200) {
                                this.dlProgress = 100;
                                this.dlLoaded   = xhr.response.byteLength;
                                this.dlTotal    = xhr.response.byteLength;

                                const blob    = new Blob([xhr.response], {
                                    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                                });
                                const blobUrl = URL.createObjectURL(blob);
                                const a       = document.createElement('a');
                                a.href        = blobUrl;
                                a.download    = `laporan-${start}-sd-${end}.xlsx`;
                                document.body.appendChild(a);
                                a.click();
                                document.body.removeChild(a);
                                setTimeout(() => URL.revokeObjectURL(blobUrl), 10000);
                                resolve();
                            } else {
                                reject(new Error('Server error: ' + xhr.status));
                            }
                        };

                        xhr.onerror = () => {
                            clearInterval(fakeInterval);
                            reject(new Error('Network error'));
                        };

                        xhr.send();
                    }).then(() => {
                        this.phase = 'done';
                        setTimeout(() => {
                            this.phase = 'idle';
                            $dispatch('close-modal', 'modal-export');
                        }, 1500);
                    }).catch(() => {
                        this.phase    = 'idle';
                        this.hasError = true;
                        this.errorMsg = 'Gagal mengunduh file. Silakan coba lagi.';
                    });
                }
            }"
            class="relative px-6 py-6 sm:px-8 space-y-7">

            <!-- Rentang Tanggal -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 sm:gap-6" :class="phase !== 'idle' ? 'opacity-50 pointer-events-none' : ''">

                <!-- Tanggal Awal -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Tanggal Awal</label>
                    <input
                        type="date"
                        wire:model="startDate"
                        x-on:change="hasError = false"
                        class="block w-full rounded-xl border-0 py-3 px-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 focus:ring-2 focus:ring-inset focus:ring-slate-500 sm:text-sm sm:leading-6 bg-gray-50/50 hover:bg-white transition-colors">
                </div>

                <!-- Tanggal Akhir -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Tanggal Akhir</label>
                    <input
                        type="date"
                        wire:model="endDate"
                        x-on:change="hasError = false"
                        class="block w-full rounded-xl border-0 py-3 px-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 focus:ring-2 focus:ring-inset focus:ring-slate-500 sm:text-sm sm:leading-6 bg-gray-50/50 hover:bg-white transition-colors">
                </div>
            </div>

            <!-- Info -->
            <div class="flex items-start gap-4 p-5 bg-slate-50/50 rounded-2xl text-sm text-slate-700"
                 x-show="phase === 'idle'">
                <div class="w-10 h-10 bg-slate-100/70 text-slate-600 rounded-xl flex items-center justify-center flex-shrink-0 -mt-1 shadow-sm border border-slate-200/50">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div class="flex-1 mt-0.5 leading-relaxed">
                    <strong class="block text-slate-900 mb-0.5 tracking-tight font-semibold">Format Laporan Otomatis</strong>
                    Seluruh arus kas Anda akan direkapitulasi secara akurat dan diunduh berupa *Spreadsheet (.xlsx)* sesuai dengan format pelaporan yang berlaku.
                </div>
            </div>

            <!-- ══ PROGRESS AREA ══ -->

            <!-- Fase: server memproses -->
            <div x-show="phase === 'preparing'" x-cloak
                 class="rounded-2xl ring-1 ring-inset ring-slate-100 bg-slate-50/40 px-6 py-5 space-y-4">
                <div class="flex items-center gap-3">
                    <svg class="animate-spin w-5 h-5 text-slate-600 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-sm font-semibold text-slate-900">Menganalisis & Menyiapkan File...</span>
                </div>
                <!-- Indeterminate bar -->
                <div class="w-full bg-slate-200/50 rounded-full h-2.5 overflow-hidden">
                    <div class="h-2.5 rounded-full bg-slate-500 animate-[indeterminate_1.5s_ease-in-out_infinite]"
                         style="width:40%; animation: indeterminate 1.5s ease-in-out infinite;">
                    </div>
                </div>
                <style>
                    @keyframes indeterminate {
                        0%   { transform: translateX(-100%); width: 40%; }
                        50%  { transform: translateX(160%);  width: 40%; }
                        100% { transform: translateX(160%);  width: 40%; }
                    }
                </style>
                <p class="text-xs text-slate-500 font-medium tracking-wide">Mohon tunggu beberapa detik</p>
            </div>

            <!-- Fase: download -->
            <div x-show="phase === 'downloading'" x-cloak
                 class="rounded-2xl ring-1 ring-inset ring-emerald-100 bg-emerald-50/40 px-6 py-5 space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        <span class="text-sm font-semibold text-emerald-900">Mentransfer Data Laporan...</span>
                    </div>
                    <!-- Persentase (jika Content-Length tersedia) -->
                    <span class="text-sm font-bold text-emerald-700"
                          x-show="dlProgress >= 0"
                          x-text="dlProgress + '%'"></span>
                    <!-- Bytes terunduh (selalu tampil) -->
                    <span class="text-xs font-semibold text-emerald-600 ml-2"
                          x-show="dlLoaded > 0"
                          x-text="'(' + formatBytes(dlLoaded) + (dlTotal > 0 ? ' / ' + formatBytes(dlTotal) : '') + ')'"></span>
                </div>

                <!-- Determinate bar (jika Content-Length ada) -->
                <div class="w-full bg-emerald-200/60 rounded-full h-3" x-show="dlProgress >= 0">
                    <div class="h-3 rounded-full bg-emerald-500 shadow-sm transition-all duration-300"
                         :style="'width: ' + dlProgress + '%'">
                    </div>
                </div>

                <!-- Indeterminate bar (jika Content-Length tidak ada) -->
                <div class="w-full bg-emerald-200/60 rounded-full h-3 overflow-hidden" x-show="dlProgress < 0">
                    <div class="h-3 rounded-full bg-emerald-500 shadow-sm"
                         style="width:40%; animation: indeterminate 1.5s ease-in-out infinite;"></div>
                </div>
            </div>

            <!-- Fase: selesai -->
            <div x-show="phase === 'done'" x-cloak
                 class="rounded-2xl ring-1 ring-inset ring-emerald-200 bg-emerald-50/80 px-6 py-5">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-emerald-100/80 text-emerald-600 flex items-center justify-center flex-shrink-0 shadow-sm border border-emerald-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <span class="text-sm font-bold tracking-tight text-emerald-900 block">Unduhan Berhasil!</span>
                        <span class="text-xs font-medium text-emerald-600 mt-0.5">Silakan periksa folder unduhan (Downloads) Anda.</span>
                    </div>
                </div>
            </div>

            <!-- ERROR MESSAGE -->
            <template x-if="hasError">
                <div class="flex items-start gap-3 p-4 bg-rose-50/80 rounded-2xl text-sm text-rose-600">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <span x-text="errorMsg" class="font-medium mt-0.5"></span>
                </div>
            </template>

            <!-- FOOTER -->
            <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-6 border-t border-gray-100">
                <button
                    x-on:click="$dispatch('close-modal', 'modal-export')"
                    type="button"
                    :disabled="phase === 'downloading'"
                    class="w-full sm:w-auto px-6 py-3 rounded-xl bg-white text-gray-700 text-sm font-semibold shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-all duration-200 disabled:opacity-40 disabled:cursor-not-allowed outline-none focus:ring-2 focus:ring-gray-200">
                    Batal
                </button>

                <button
                    @click="doExport()"
                    :disabled="phase !== 'idle'"
                    class="w-full sm:w-auto px-6 py-3 rounded-xl bg-slate-900 text-white text-sm font-semibold shadow-sm hover:bg-slate-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-slate-600 transition-all duration-200 disabled:opacity-70 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="phase === 'idle'">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    <span x-show="phase === 'idle'">Ekspor Data xlsx</span>
                    <span x-show="phase === 'preparing'" class="flex items-center gap-2">Sedang Disiapkan...</span>
                    <span x-show="phase === 'downloading'" class="flex items-center gap-2">
                        Mengunduh <span x-text="dlProgress >= 0 ? dlProgress + '%' : ''"></span>
                    </span>
                    <span x-show="phase === 'done'">Selesai ✓</span>
                </button>
            </div>

        </div>
    </div>
</x-modal>