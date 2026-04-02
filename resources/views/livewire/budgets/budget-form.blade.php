<x-modal name="modal-budget" focusable>
    <div class="p-4 sm:p-6 max-h-[90vh] overflow-y-auto">

        <!-- HEADER -->
        <div class="flex justify-between items-start mb-6 sticky top-0 bg-white pb-4 border-b">
            <div>
                <h2 class="text-lg sm:text-xl font-semibold text-gray-900">
                  {{ $this->isEditing() ? 'Edit Budget' : 'Tambah Budget' }}
                </h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">
                    
                    {{ $this->isEditing() ? 'Ubah batas pengeluaran kategori ini' : 'Tetapkan batas pengeluaran per kategori' }}
                </p>
            </div>

            <button
                x-on:click="$dispatch('close-modal', 'modal-budget')"
                type="button"
                class="flex-shrink-0 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg p-1.5 transition-colors duration-200 ml-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- FORM -->
        <form wire:submit="save" class="space-y-5">
            <!-- Kategori -->
            @if ($this->isEditing())
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700 flex items-center gap-1">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5l5 5v11a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z" />
                        </svg>
                        Kategori
                    </label>
                    <div class="w-full border border-gray-200 bg-gray-50 rounded-lg p-2.5 text-sm text-gray-500 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        {{ $categoryName }}
                    </div>
                </div>
            @else
                <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700 flex items-center gap-1">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h.01M7 3h5l5 5v11a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z" />
                    </svg>
                    Kategori
                    <span class="text-red-500">*</span>
                </label>
                <select wire:model="category_id"
                    class="w-full border border-gray-300 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white">
                    <option value="0">-- Pilih Kategori --</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <span class="text-xs text-red-600 flex items-center gap-1 mt-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        {{ $message }}
                    </span>
                @enderror
            </div>
            @endif

            <!-- Batas Pengeluaran -->
            <div
                x-data="{
                    raw: @entangle('limit_amount'),
                    get display() {
                        if (!this.raw) return '';
                        return this.raw.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                    },
                    set display(value) {
                        this.raw = value.replace(/\D/g, '');
                    }
                }"
                class="space-y-2">
                <label class="text-sm font-medium text-gray-700 flex items-center gap-1">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Batas Pengeluaran
                    <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-medium">Rp</span>
                    <input
                        type="text"
                        x-model="display"
                        inputmode="numeric"
                        placeholder="0"
                        class="w-full border border-gray-300 rounded-lg p-2.5 pl-10 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" />
                </div>
                @error('limit_amount')
                    <span class="text-xs text-red-600 flex items-center gap-1 mt-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <!-- FOOTER -->
            <div class="flex flex-col-reverse sm:flex-row justify-end gap-2 sm:gap-3 pt-4 border-t mt-2">
                <button
                    x-on:click="$dispatch('close-modal', 'modal-budget')"
                    type="button"
                    class="w-full sm:w-auto border-2 border-gray-300 px-4 py-2.5 sm:py-2 rounded-lg hover:bg-gray-50 active:bg-gray-100 transition-colors duration-200 font-medium text-sm text-gray-700">
                    Batal
                </button>

                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="w-full sm:w-auto bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 py-2.5 sm:py-2 rounded-lg hover:from-blue-700 hover:to-blue-800 active:scale-95 transition-all duration-200 font-medium text-sm shadow-lg shadow-blue-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                    <span wire:loading.remove wire:target="save" class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Budget
                    </span>
                    <span wire:loading wire:target="save" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Menyimpan...
                    </span>
                </button>
            </div>

        </form>

    </div>
</x-modal>