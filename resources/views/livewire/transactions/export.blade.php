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
            x-data="{ hasError: false, errorMsg: '' }"
            class="relative px-5 py-5 sm:px-7 sm:py-6 space-y-5">

            <!-- LOADING OVERLAY -->
            <div
                wire:loading
                wire:target="exportExcel"
                class="absolute inset-0 z-20 bg-white/80 backdrop-blur-sm rounded-b-2xl flex flex-col items-center justify-center gap-3">
                <div class="relative w-14 h-14">
                    <svg class="animate-spin w-14 h-14 text-green-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
                    </svg>
                    <svg class="animate-spin absolute inset-0 w-14 h-14 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                    </div>
                </div>
                <div class="text-center">
                    <p class="text-sm font-semibold text-gray-700">Menyiapkan file...</p>
                    <p class="text-xs text-gray-400 mt-0.5">Harap tunggu, sedang memproses data</p>
                </div>
            </div>

            <!-- Rentang Tanggal -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

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
            <div class="flex items-start gap-2.5 px-3.5 py-3 bg-blue-50 border border-blue-100 rounded-xl text-sm text-blue-600">
                <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Data akan diunduh dalam format <strong>.xlsx</strong> sesuai rentang tanggal yang dipilih.</span>
            </div>

            <!-- ERROR MESSAGE (Alpine) -->
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
                    class="w-full sm:w-auto px-5 py-2.5 rounded-xl border border-gray-200 text-sm font-medium text-gray-600 hover:bg-gray-50 active:bg-gray-100 transition-colors duration-150">
                    Batal
                </button>

                <button
                    wire:loading.attr="disabled"
                    wire:target="exportExcel"
                    @click="
                        hasError = false;
                        errorMsg = '';
                        const start = $wire.startDate;
                        const end = $wire.endDate;
                        if (!start || !end) {
                            hasError = true;
                            errorMsg = 'Tanggal awal dan tanggal akhir wajib diisi.';
                            return;
                        }
                        if (start > end) {
                            hasError = true;
                            errorMsg = 'Tanggal awal tidak boleh lebih dari tanggal akhir.';
                            return;
                        }
                        $wire.exportExcel();
                    "
                    class="w-full sm:w-auto bg-green-600 hover:bg-green-700 active:bg-green-800 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-md disabled:opacity-60 disabled:cursor-not-allowed inline-flex items-center justify-center gap-2">
                    <span wire:loading.remove wire:target="exportExcel" class="inline-flex items-center gap-1.5">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download Excel
                    </span>
                    <span wire:loading wire:target="exportExcel" class="inline-flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Memproses...
                    </span>
                </button>
            </div>

        </div>
    </div>
</x-modal>
