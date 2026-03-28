<x-modal name="modal-budget-delete" focusable>
    <div class="p-4 sm:p-6">

        <!-- HEADER -->
        <div class="flex justify-between items-start mb-4 sm:mb-6 sticky top-0 bg-white pb-4 border-b">
            <div>
                <h2 class="text-lg sm:text-xl font-semibold text-gray-900">
                    Hapus Budget
                </h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">
                    Tindakan ini tidak dapat dibatalkan
                </p>
            </div>

            <button
                x-on:click="$dispatch('close-modal', 'modal-budget-delete')"
                type="button"
                class="flex-shrink-0 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg p-1.5 transition-colors duration-200 ml-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- KONFIRMASI -->
        <div class="flex gap-3 p-4 bg-red-50 border border-red-100 rounded-lg mb-6">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
            </svg>
            <div>
                <p class="text-sm font-medium text-red-700">Yakin ingin menghapus budget ini?</p>
                <p class="text-xs text-red-500 mt-1">
                    Budget kategori <span class="font-semibold">{{ $categoryName }}</span> akan dihapus permanen.
                </p>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="flex flex-col-reverse sm:flex-row justify-end gap-2 sm:gap-3">
            <button
                x-on:click="$dispatch('close-modal', 'modal-budget-delete')"
                type="button"
                class="w-full sm:w-auto border-2 border-gray-300 px-4 py-2.5 sm:py-2 rounded-lg hover:bg-gray-50 transition-colors duration-200 font-medium text-sm text-gray-700">
                Batal
            </button>

            <button
                wire:click="delete"
                wire:loading.attr="disabled"
                class="w-full sm:w-auto bg-gradient-to-r from-red-600 to-red-700 text-white px-4 py-2.5 sm:py-2 rounded-lg hover:from-red-700 hover:to-red-800 transition-all duration-200 font-medium text-sm shadow-lg disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                <span wire:loading.remove wire:target="delete">
                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Hapus Budget
                </span>
                <span wire:loading wire:target="delete" class="flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Menghapus...
                </span>
            </button>
        </div>

    </div>
</x-modal>