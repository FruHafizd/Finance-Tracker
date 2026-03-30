<x-modal name="modal-create" focusable>
    <div class="max-h-[92vh] overflow-y-auto">

        <!-- HEADER -->
        <div class="sticky top-0 z-10 bg-white/80 backdrop-blur-md border-b border-gray-100 px-6 py-5 sm:px-8">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 tracking-tight">Catat Transaksi</h2>
                    <p class="text-sm text-gray-500 mt-1 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                        Kelola arus kas Anda
                    </p>
                </div>
                <button
                    x-on:click="$dispatch('close-modal', 'modal-create')"
                    type="button"
                    class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-full transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- FORM BODY -->
        <form wire:submit.prevent="save" class="px-6 py-6 sm:px-8 space-y-7">

            <!-- Jenis Transaksi (Segmented Control) -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Jenis Transaksi <span class="text-red-500">*</span></label>
                <div class="relative flex p-1 bg-gray-100/80 rounded-2xl">
                    <!-- Pemasukan -->
                    <button 
                        type="button"
                        wire:click="$set('type', 'income')"
                        class="relative w-1/2 flex items-center justify-center gap-2 py-2.5 text-sm font-semibold rounded-xl transition-all duration-300 {{ $type === 'income' ? 'text-emerald-700 bg-white shadow-sm ring-1 ring-black/5' : 'text-gray-500 hover:text-gray-700' }}">
                        <svg class="w-5 h-5 {{ $type === 'income' ? 'text-emerald-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        Pemasukan
                    </button>
                    <!-- Pengeluaran -->
                    <button 
                        type="button"
                        wire:click="$set('type', 'expense')"
                        class="relative w-1/2 flex items-center justify-center gap-2 py-2.5 text-sm font-semibold rounded-xl transition-all duration-300 {{ $type === 'expense' ? 'text-rose-700 bg-white shadow-sm ring-1 ring-black/5' : 'text-gray-500 hover:text-gray-700' }}">
                        <svg class="w-5 h-5 {{ $type === 'expense' ? 'text-rose-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
                        Pengeluaran
                    </button>
                </div>
                @error('type') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Nama & Tanggal -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 sm:gap-6">
                <!-- Nama -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Nama Transaksi</label>
                    <input
                        wire:model="name"
                        type="text"
                        class="block w-full rounded-xl border-0 py-3 px-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 bg-gray-50/50 hover:bg-white transition-colors"
                        placeholder="Contoh: Gaji, Makan, dll">
                    @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Tanggal -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Tanggal <span class="text-red-500">*</span></label>
                    <input
                        type="date"
                        wire:model="date"
                        class="block w-full rounded-xl border-0 py-3 px-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 bg-gray-50/50 hover:bg-white transition-colors">
                    @error('date') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Nominal & Kategori -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 sm:gap-6">
                <!-- Nominal -->
                <div
                    x-data="{ display: '', raw: '' }"
                    @open-modal.window="
                        if ($event.detail === 'modal-create') {
                            display = '';
                            raw = '';
                            $wire.set('amount', '');
                        }
                    "
                    class="space-y-2"
                >
                    <label class="block text-sm font-medium text-gray-700">Nominal <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                            <span class="text-gray-500 sm:text-sm font-medium">Rp</span>
                        </div>
                        <input
                            type="text"
                            x-model="display"
                            @input="
                                raw = display.replace(/\D/g, '');
                                display = raw.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                                $wire.amount = raw;
                            "
                            class="block w-full rounded-xl border-0 py-3 pl-11 pr-4 text-gray-900 font-semibold shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-base sm:leading-6 bg-gray-50/50 hover:bg-white transition-colors"
                            placeholder="0">
                    </div>
                    @error('amount') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Kategori -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Kategori <span class="text-red-500">*</span></label>
                    <div class="flex items-center gap-3">
                        <div class="relative flex-1">
                            <select
                                wire:model="category_id"
                                class="block w-full rounded-xl border-0 py-3 pl-4 pr-10 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 appearance-none bg-gray-50/50 hover:bg-white transition-colors cursor-pointer">
                                <option value="" class="text-gray-400">Pilih Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button
                            x-data
                            x-on:click.prevent="$dispatch('open-modal', 'modal-category')"
                            title="Tambah kategori"
                            class="flex-shrink-0 w-[48px] h-[48px] flex items-center justify-center rounded-xl border border-gray-200 bg-white text-gray-600 hover:border-indigo-600 hover:text-indigo-600 shadow-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                        </button>
                    </div>
                    @error('category_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- FOOTER ACTION -->
            <div class="pt-6 mt-4 border-t border-gray-100 flex flex-col-reverse sm:flex-row justify-end gap-3">
                <button
                    x-on:click="$dispatch('close-modal', 'modal-create')"
                    type="button"
                    class="w-full sm:w-auto px-6 py-3 rounded-xl bg-white text-gray-700 text-sm font-semibold shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-all duration-200">
                    Batal
                </button>
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="w-full sm:w-auto px-6 py-3 rounded-xl bg-indigo-600 text-white text-sm font-semibold shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-all duration-200 disabled:opacity-70 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                    <span wire:loading.remove wire:target="save">Simpan Transaksi</span>
                    <span wire:loading wire:target="save" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
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
