<x-modal name="modal-account" focusable>
    <div class="max-h-[92vh] overflow-y-auto">

        {{-- HEADER --}}
        <div class="sticky top-0 z-10 bg-white/80 backdrop-blur-md border-b border-slate-100 px-6 py-5 sm:px-8">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight">
                        {{ $accountId ? 'Edit Rekening' : 'Tambah Rekening' }}
                    </h2>
                    <p class="text-xs text-slate-500 mt-1.5 flex items-center gap-2 font-medium">
                        <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                        {{ $accountId ? 'Perbarui informasi rekening kamu' : 'Tambah rekening baru untuk kelola uangmu' }}
                    </p>
                </div>
                <button
                    x-on:click="$dispatch('close-modal', 'modal-account')"
                    type="button"
                    class="w-10 h-10 flex items-center justify-center text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-2xl transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- FORM BODY --}}
        <form wire:submit.prevent="save" class="px-6 py-6 sm:px-8 space-y-7">

            {{-- Tipe Rekening --}}
            <div class="space-y-3">
                <label class="block text-sm font-bold text-slate-700">Tipe Rekening <span class="text-red-500">*</span></label>
                <div class="relative flex p-1.5 bg-slate-100 rounded-2xl">
                    @foreach(['tabungan' => 'Tabungan', 'ewallet' => 'E-Wallet', 'tunai' => 'Tunai'] as $key => $label)
                        <button
                            type="button"
                            wire:click="$set('type', '{{ $key }}')"
                            class="relative w-1/3 flex items-center justify-center gap-2 py-2.5 text-sm font-bold rounded-xl transition-all duration-300
                                {{ $type === $key
                                    ? 'text-slate-800 bg-white shadow-sm ring-1 ring-slate-200'
                                    : 'text-slate-500 hover:text-slate-700' }}">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Provider --}}
            @if($type !== 'tunai')
                <div class="space-y-3">
                    <label class="block text-sm font-bold text-slate-700">
                        {{ $type === 'tabungan' ? 'Pilih Bank' : 'Pilih Aplikasi' }}
                        <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                        @foreach($this->providers as $p)
                            <button
                                type="button"
                                wire:click="$set('provider', '{{ $p->value }}')"
                                class="py-2.5 px-3 rounded-xl text-xs font-bold border transition-all duration-200
                                    {{ $provider === $p->value
                                        ? 'bg-slate-800 text-white border-slate-800 ring-2 ring-slate-200'
                                        : 'bg-white text-slate-600 border-slate-200 hover:border-slate-400 hover:bg-slate-50' }}">
                                {{ $p->value }}
                            </button>
                        @endforeach
                    </div>
                    @error('provider')
                        <p class="text-xs text-red-500 mt-2 font-medium flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            @endif

            {{-- Nama Rekening --}}
            <div class="space-y-3">
                <label class="block text-sm font-bold text-slate-700">Nama Rekening <span class="text-red-500">*</span></label>
                <input
                    type="text"
                    wire:model="name"
                    placeholder="Contoh: BCA Utama, GoPay Harian"
                    class="block w-full rounded-2xl border-slate-200 py-3.5 px-4 text-slate-900 shadow-sm focus:ring-2 focus:ring-slate-400 focus:border-slate-400 sm:text-sm bg-slate-50/50 hover:bg-white transition-all duration-200" />
                @error('name')
                    <p class="text-xs text-red-500 mt-2 font-medium flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </p>
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
                class="space-y-3">
                <label class="block text-sm font-bold text-slate-700">
                    {{ $accountId ? 'Saldo Saat Ini' : 'Saldo Awal' }}
                    <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-5">
                        <span class="text-slate-400 sm:text-sm font-bold group-focus-within:text-slate-800 transition-colors">Rp</span>
                    </div>
                    <input
                        type="text"
                        x-model="display"
                        class="block w-full rounded-2xl border-slate-200 py-4 pl-12 pr-5 text-slate-900 font-bold shadow-sm focus:ring-2 focus:ring-slate-800 focus:border-slate-800 sm:text-lg bg-slate-50/50 hover:bg-white transition-all duration-200"
                        placeholder="0" />
                </div>
                @error('balance')
                    <p class="text-xs text-red-500 mt-2 font-medium flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Warna --}}
            <div class="space-y-3">
                <label class="block text-sm font-bold text-slate-700">Warna Rekening</label>
                <div class="flex items-center gap-4 bg-slate-50/50 p-3 rounded-2xl border border-slate-100">
                    <div class="relative w-12 h-12 rounded-xl overflow-hidden shadow-inner ring-1 ring-slate-200">
                        <input
                            type="color"
                            wire:model="color"
                            class="absolute inset-[-100%] w-[300%] h-[300%] cursor-pointer" />
                    </div>
                    <div>
                        <span class="text-sm font-bold text-slate-800 uppercase leading-none">{{ $color }}</span>
                        <p class="text-[11px] text-slate-400 mt-0.5">Identitas visual rekening</p>
                    </div>
                </div>
            </div>

            {{-- FOOTER --}}
            <div class="pt-8 flex flex-col-reverse sm:flex-row justify-end gap-3">
                <button
                    type="button"
                    x-on:click="$dispatch('close-modal', 'modal-account')"
                    class="w-full sm:w-auto px-7 py-3.5 rounded-2xl bg-white text-slate-600 text-sm font-bold border border-slate-200 hover:bg-slate-50 hover:border-slate-300 transition-all duration-200 active:scale-95">
                    Batal
                </button>
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="w-full sm:w-auto px-8 py-3.5 rounded-2xl bg-slate-800 text-white text-sm font-bold shadow-lg shadow-slate-200 hover:bg-slate-900 transition-all duration-200 disabled:opacity-70 disabled:cursor-not-allowed flex items-center justify-center gap-2 active:scale-[0.98]">
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