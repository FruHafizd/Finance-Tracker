<x-modal name="modal-account" focusable>
    <div class="max-h-[92vh] overflow-y-auto">

        {{-- HEADER --}}
        <div class="sticky top-0 z-10 bg-white/80 backdrop-blur-md border-b border-gray-100 px-6 py-5 sm:px-8">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 tracking-tight">
                        {{ $accountId ? 'Edit Rekening' : 'Tambah Rekening' }}
                    </h2>
                    <p class="text-sm text-gray-500 mt-1 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                        {{ $accountId ? 'Perbarui informasi rekening' : 'Tambah rekening baru' }}
                    </p>
                </div>
                <button
                    x-on:click="$dispatch('close-modal', 'modal-account')"
                    type="button"
                    class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-full transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- FORM BODY --}}
        <form wire:submit.prevent="save" class="px-6 py-6 sm:px-8 space-y-6">

            {{-- Tipe Rekening --}}
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Tipe Rekening <span class="text-red-500">*</span></label>
                <div class="relative flex p-1 bg-gray-100/80 rounded-2xl">
                    @foreach(['tabungan' => 'Tabungan', 'ewallet' => 'E-Wallet', 'tunai' => 'Tunai'] as $key => $label)
                        <button
                            type="button"
                            wire:click="$set('type', '{{ $key }}')"
                            class="relative w-1/3 flex items-center justify-center gap-1.5 py-2.5 text-sm font-semibold rounded-xl transition-all duration-300
                                {{ $type === $key
                                    ? 'text-indigo-700 bg-white shadow-sm ring-1 ring-black/5'
                                    : 'text-gray-500 hover:text-gray-700' }}">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Provider --}}
            @if($type !== 'tunai')
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">
                        {{ $type === 'tabungan' ? 'Pilih Bank' : 'Pilih Aplikasi' }}
                        <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-3 sm:grid-cols-4 gap-2">
                        @foreach($this->providers as $p)
                            <button
                                type="button"
                                wire:click="$set('provider', '{{ $p->value }}')"
                                class="py-2 px-2 rounded-xl text-xs font-medium border transition-all duration-200
                                    {{ $provider === $p->value
                                        ? 'bg-indigo-50 text-indigo-600 border-indigo-400 ring-1 ring-indigo-300'
                                        : 'bg-white text-gray-600 border-gray-200 hover:border-indigo-300 hover:text-indigo-500' }}">
                                {{ $p->value }}
                            </button>
                        @endforeach
                    </div>
                    @error('provider')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            @endif

            {{-- Nama Rekening --}}
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Nama Rekening <span class="text-red-500">*</span></label>
                <input
                    type="text"
                    wire:model="name"
                    placeholder="Contoh: BCA Utama, GoPay Harian"
                    class="block w-full rounded-xl border-0 py-3 px-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm bg-gray-50/50 hover:bg-white transition-colors" />
                @error('name')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Saldo --}}
            <div
                x-data="{
                    raw: @entangle('balance'),
                    get display() {
                        if (!this.raw) return '';
                        return this.raw.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                    },
                    set display(value) {
                        this.raw = value.replace(/\D/g, '');
                    }
                }"
                class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">
                    {{ $accountId ? 'Saldo Saat Ini' : 'Saldo Awal' }}
                    <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                        <span class="text-gray-500 sm:text-sm font-medium">Rp</span>
                    </div>
                    <input
                        type="text"
                        x-model="display"
                        class="block w-full rounded-xl border-0 py-3 pl-11 pr-4 text-gray-900 font-semibold shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-base bg-gray-50/50 hover:bg-white transition-colors"
                        placeholder="0" />
                </div>
                @error('balance')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Warna --}}
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Warna Rekening</label>
                <div class="flex items-center gap-3">
                    <input
                        type="color"
                        wire:model="color"
                        class="w-10 h-10 rounded-xl border border-gray-200 cursor-pointer p-1" />
                    <span class="text-sm text-gray-400">{{ $color }}</span>
                </div>
            </div>

            {{-- FOOTER --}}
            <div class="pt-6 mt-2 border-t border-gray-100 flex flex-col-reverse sm:flex-row justify-end gap-3">
                <button
                    type="button"
                    x-on:click="$dispatch('close-modal', 'modal-account')"
                    class="w-full sm:w-auto px-6 py-3 rounded-xl bg-white text-gray-700 text-sm font-semibold shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-all duration-200">
                    Batal
                </button>
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="w-full sm:w-auto px-6 py-3 rounded-xl bg-indigo-600 text-white text-sm font-semibold shadow-sm hover:bg-indigo-500 transition-all duration-200 disabled:opacity-70 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                    <span wire:loading.remove wire:target="save">
                        {{ $accountId ? 'Simpan Perubahan' : 'Tambah Rekening' }}
                    </span>
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