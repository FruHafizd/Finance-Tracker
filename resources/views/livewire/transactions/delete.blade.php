<x-modal name="modal-delete" focusable>
    <div class="px-6 py-6 sm:px-8">

        <!-- HEADER -->
        <div class="flex items-start gap-4 mb-6">
            <div class="flex-shrink-0 w-10 h-10 bg-red-50 text-red-600 rounded-2xl flex items-center justify-center mt-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <h2 class="text-xl font-bold text-gray-900 tracking-tight">
                    Hapus Transaksi
                </h2>
                <p class="text-sm text-gray-500 mt-1 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                    Tindakan ini permanen
                </p>
            </div>
            <button
                x-on:click="$dispatch('close-modal', 'modal-delete')"
                type="button"
                class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-full transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-200 -mt-1 -mr-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- CONTENT -->
        <div class="bg-red-50/50 rounded-2xl p-5 mb-8">
            <p class="text-sm text-gray-700 leading-relaxed">
                Apakah Anda yakin ingin menghapus catatan transaksi ini?
                <span class="block mt-2 font-semibold text-red-600">
                    Semua data terkait transaksi ini akan dihapus permanen dan tidak bisa dikembalikan.
                </span>
            </p>
        </div>

        <!-- FOOTER -->
        <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-2">
            <button
                x-on:click="
                $dispatch('close-modal', 'modal-delete');
                $dispatch('close-delete-modal');
                "
                type="button"
                class="w-full sm:w-auto px-6 py-3 rounded-xl bg-white text-gray-700 text-sm font-semibold shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-all duration-200 focus:outline-none">
                Batal
            </button>

            <button
                wire:click="delete"
                wire:loading.attr="disabled"
                type="button"
                class="w-full sm:w-auto px-6 py-3 rounded-xl bg-red-600 text-white text-sm font-semibold shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 transition-all duration-200 disabled:opacity-70 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                <span wire:loading.remove wire:target="delete">Hapus Transaksi</span>
                <span wire:loading wire:target="delete" class="flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Menghapus...
                </span>
            </button>
        </div>

    </div>
</x-modal>