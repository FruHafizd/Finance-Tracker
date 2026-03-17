<x-modal name="modal-edit" focusable>
    <div class="max-h-[92vh] overflow-y-auto">

        <!-- HEADER -->
        <div class="sticky top-0 z-10 bg-white border-b border-gray-100 px-5 py-4 sm:px-7 sm:py-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900 leading-tight">Edit Catatan Keuangan</h2>
                        <p class="text-xs text-gray-400 mt-0.5">Perbarui informasi catatan keuangan</p>
                    </div>
                </div>
                <button
                    x-on:click="$dispatch('close-modal', 'modal-edit')"
                    type="button"
                    class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors duration-150 flex-shrink-0 ml-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- FORM BODY -->
        <form wire:submit.prevent="update" class="px-5 py-5 sm:px-7 sm:py-6 space-y-5">

            <!-- Row 1: Nama + Jenis -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <!-- Nama -->
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                        </svg>
                        Nama
                    </label>
                    <input
                        wire:model="name"
                        type="text"
                        class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-all duration-200 bg-gray-50 focus:bg-white"
                        placeholder="cth. Gaji Bulanan">
                    @error('name')
                        <p class="text-xs text-red-500 flex items-center gap-1 mt-1">
                            <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Jenis Transaksi -->
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                        </svg>
                        Jenis Transaksi
                        <span class="text-red-400 font-bold">*</span>
                    </label>
                    <select
                        wire:model="type"
                        class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-all duration-200 bg-gray-50 focus:bg-white appearance-none cursor-pointer">
                        <option value="" class="text-gray-400">-- Pilih Jenis --</option>
                        <option value="income">💰 Pemasukan</option>
                        <option value="expense">💸 Pengeluaran</option>
                    </select>
                    @error('type')
                        <p class="text-xs text-red-500 flex items-center gap-1 mt-1">
                            <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <!-- Divider -->
            <div class="border-t border-dashed border-gray-200"></div>

            <!-- Row 2: Tanggal + Jumlah -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <!-- Tanggal -->
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Tanggal
                        <span class="text-red-400 font-bold">*</span>
                    </label>
                    <input
                        type="date"
                        wire:model="date"
                        class="w-full border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-all duration-200 bg-gray-50 focus:bg-white">
                    @error('date')
                        <p class="text-xs text-red-500 flex items-center gap-1 mt-1">
                            <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Jumlah Uang -->
                <div
                    x-data="{
                        raw: @entangle('amount'),
                        get display() {
                            if (!this.raw) return '';
                            return this.raw.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                        },
                        set display(value) {
                            this.raw = value.replace(/\D/g, '');
                        }
                    }"
                    class="space-y-1.5"
                >
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Jumlah Uang
                        <span class="text-red-400 font-bold">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-blue-600 text-sm font-semibold select-none">Rp</span>
                        <input
                            type="text"
                            x-model="display"
                            class="w-full border border-gray-200 rounded-xl pl-10 pr-3.5 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-all duration-200 bg-gray-50 focus:bg-white"
                            placeholder="0">
                    </div>
                    @error('amount')
                        <p class="text-xs text-red-500 flex items-center gap-1 mt-1">
                            <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <!-- Row 3: Kategori (full width) -->
            <div class="space-y-1.5">
                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    Kategori
                    <span class="text-red-400 font-bold">*</span>
                </label>
                <div class="flex items-center gap-2">
                    <select
                        wire:model="category_id"
                        class="flex-1 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-all duration-200 bg-gray-50 focus:bg-white appearance-none cursor-pointer">
                        <option value="" class="text-gray-400">-- Pilih Kategori --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <button
                        x-data
                        x-on:click.prevent="$dispatch('open-modal', 'modal-category')"
                        title="Tambah kategori baru"
                        class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-xl border border-gray-200 bg-gray-50 text-gray-500 hover:bg-blue-50 hover:border-blue-300 hover:text-blue-600 transition-all duration-200 text-lg font-light">
                        +
                    </button>
                </div>
                @error('category_id')
                    <p class="text-xs text-red-500 flex items-center gap-1 mt-1">
                        <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- FOOTER -->
            <div class="flex flex-col-reverse sm:flex-row justify-end gap-2.5 pt-5 border-t border-gray-100 mt-2">
                <button
                    x-on:click="$dispatch('close-modal', 'modal-edit')"
                    type="button"
                    class="w-full sm:w-auto px-5 py-2.5 rounded-xl border border-gray-200 text-sm font-medium text-gray-600 hover:bg-gray-50 active:bg-gray-100 transition-colors duration-150">
                    Batal
                </button>

                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="w-full sm:w-auto bg-blue-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-blue-700 active:bg-blue-800 transition-all duration-200 shadow-sm hover:shadow-md disabled:opacity-60 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                    <span wire:loading.remove wire:target="update" class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Perubahan
                    </span>
                    <span wire:loading wire:target="update" class="flex items-center gap-2">
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
