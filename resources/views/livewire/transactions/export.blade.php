<x-modal name="modal-export" focusable>
    <div class="max-h-[92vh] overflow-y-auto">

        <!-- HEADER -->
        <div class="sticky top-0 z-10 bg-white border-b border-gray-100 px-5 py-4 sm:px-7 sm:py-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-green-50 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900 leading-tight">Export Laporan</h2>
                        <p class="text-xs text-gray-400 mt-0.5">Unduh data transaksi dalam format Excel</p>
                    </div>
                </div>
                <button
                    x-on:click="$dispatch('close-modal', 'modal-export')"
                    type="button"
                    class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors duration-150 flex-shrink-0 ml-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            class="relative px-5 py-5 sm:px-7 sm:py-6 space-y-5">

            <!-- Rentang Tanggal -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" :class="phase !== 'idle' ? 'opacity-50 pointer-events-none' : ''">

                <!-- Tanggal Awal -->
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Tanggal Awal
                    </label>
                    <input
                        type="date"
                        wire:model="startDate"
                        x-on:change="hasError = false"
                        class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-green-500 focus:border-green-500 focus:outline-none transition-all duration-200 bg-gray-50 focus:bg-white">
                </div>

                <!-- Tanggal Akhir -->
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Tanggal Akhir
                    </label>
                    <input
                        type="date"
                        wire:model="endDate"
                        x-on:change="hasError = false"
                        class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-green-500 focus:border-green-500 focus:outline-none transition-all duration-200 bg-gray-50 focus:bg-white">
                </div>
            </div>

            <!-- Info -->
            <div class="flex items-start gap-2.5 px-3.5 py-3 bg-blue-50 border border-blue-100 rounded-xl text-sm text-blue-600"
                 x-show="phase === 'idle'">
                <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Data akan diunduh dalam format <strong>.xlsx</strong> sesuai rentang tanggal yang dipilih.</span>
            </div>

            <!-- ══ PROGRESS AREA ══ -->

            <!-- Fase: server memproses -->
            <div x-show="phase === 'preparing'" x-cloak
                 class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-4 space-y-3">
                <div class="flex items-center gap-2.5">
                    <svg class="animate-spin w-4 h-4 text-green-600 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-700">Menyiapkan file di server...</span>
                </div>
                <!-- Indeterminate bar -->
                <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                    <div class="h-2 rounded-full bg-green-400 animate-[indeterminate_1.5s_ease-in-out_infinite]"
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
                <p class="text-xs text-gray-400">Sedang membaca dan memformat data transaksi</p>
            </div>

            <!-- Fase: download -->
            <div x-show="phase === 'downloading'" x-cloak
                 class="rounded-xl border border-green-100 bg-green-50 px-4 py-4 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        <span class="text-sm font-medium text-green-800">Mengunduh file...</span>
                    </div>
                    <!-- Persentase (jika Content-Length tersedia) -->
                    <span class="text-sm font-semibold text-green-700"
                          x-show="dlProgress >= 0"
                          x-text="dlProgress + '%'"></span>
                    <!-- Bytes terunduh (selalu tampil) -->
                    <span class="text-xs text-green-600 ml-1"
                          x-show="dlLoaded > 0"
                          x-text="'(' + formatBytes(dlLoaded) + (dlTotal > 0 ? ' / ' + formatBytes(dlTotal) : '') + ')'"></span>
                </div>

                <!-- Determinate bar (jika Content-Length ada) -->
                <div class="w-full bg-green-200 rounded-full h-2.5" x-show="dlProgress >= 0">
                    <div class="h-2.5 rounded-full bg-green-500 transition-all duration-300"
                         :style="'width: ' + dlProgress + '%'">
                    </div>
                </div>

                <!-- Indeterminate bar (jika Content-Length tidak ada) -->
                <div class="w-full bg-green-200 rounded-full h-2.5 overflow-hidden" x-show="dlProgress < 0">
                    <div class="h-2.5 rounded-full bg-green-500"
                         style="width:40%; animation: indeterminate 1.5s ease-in-out infinite;"></div>
                </div>

                <p class="text-xs text-green-600">File sedang dikirim ke browser Anda</p>
            </div>

            <!-- Fase: selesai -->
            <div x-show="phase === 'done'" x-cloak
                 class="rounded-xl border border-green-200 bg-green-50 px-4 py-4">
                <div class="flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span class="text-sm font-semibold text-green-800">File berhasil diunduh!</span>
                </div>
            </div>

            <!-- ERROR MESSAGE -->
            <template x-if="hasError">
                <div class="flex items-start gap-2.5 px-3.5 py-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-600">
                    <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <span x-text="errorMsg"></span>
                </div>
            </template>

            <!-- FOOTER -->
            <div class="flex flex-col-reverse sm:flex-row justify-end gap-2.5 pt-5 border-t border-gray-100">
                <button
                    x-on:click="$dispatch('close-modal', 'modal-export')"
                    type="button"
                    :disabled="phase === 'downloading'"
                    class="w-full sm:w-auto px-5 py-2.5 rounded-xl border border-gray-200 text-sm font-medium text-gray-600 hover:bg-gray-50 active:bg-gray-100 transition-colors duration-150 disabled:opacity-40 disabled:cursor-not-allowed">
                    Batal
                </button>

                <button
                    @click="doExport()"
                    :disabled="phase !== 'idle'"
                    class="w-full sm:w-auto bg-green-600 hover:bg-green-700 active:bg-green-800 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-md disabled:opacity-60 disabled:cursor-not-allowed inline-flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    <span x-show="phase === 'idle'">Download Excel</span>
                    <span x-show="phase === 'preparing'">Menyiapkan...</span>
                    <span x-show="phase === 'downloading'">Mengunduh <span x-text="dlProgress >= 0 ? dlProgress + '%' : ''"></span></span>
                    <span x-show="phase === 'done'">Selesai ✓</span>
                </button>
            </div>

        </div>
    </div>
</x-modal>