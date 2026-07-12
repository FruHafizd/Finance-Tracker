<x-modal name="modal-transaction" focusable>
    <div class="max-h-[92vh] overflow-y-auto bg-gray-50/30">

        {{-- HEADER (compact) --}}
        <div class="sticky top-0 z-20 bg-white/80 backdrop-blur-md border-b border-gray-100 px-5 py-4 sm:px-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 tracking-tight">
                        {{ $this->isEditing() ? 'Edit Transaksi' : 'Catat Transaksi' }}
                    </h2>
                    <p class="text-xs text-gray-500 mt-0.5 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-slate-500 animate-pulse"></span>
                        {{ $this->isEditing() ? 'Perbarui informasi Finansiku' : 'Kelola arus kas Anda dengan cerdas' }}
                    </p>
                </div>
                <button
                    x-on:click="$dispatch('close-modal', 'modal-transaction')"
                    type="button"
                    class="w-9 h-9 flex items-center justify-center text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-full transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-200">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        @if($this->accounts->isEmpty())
            {{-- EMPTY STATE ACCOUNTS --}}
            <div class="px-6 py-12 sm:px-8 flex flex-col items-center text-center">
                <div class="w-20 h-20 bg-amber-50 rounded-3xl flex items-center justify-center mb-6 ring-8 ring-amber-50/50">
                    <svg class="w-10 h-10 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Rekening Tidak Ditemukan</h3>
                <p class="text-sm text-gray-500 mt-2 max-w-xs mx-auto">
                    Anda belum memiliki rekening (Tunai/Bank). Silakan buat rekening terlebih dahulu untuk mulai mencatat transaksi.
                </p>
                <div class="mt-8">
                    <a href="{{ route('account.index') }}"
                       class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-slate-900 text-white text-sm font-semibold shadow-lg shadow-slate-200 hover:bg-slate-800 transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Buat Rekening Sekarang
                    </a>
                </div>
            </div>
        @else
            {{-- FORM BODY --}}
            <form wire:submit.prevent="save" class="px-5 py-4 sm:px-6 space-y-4">

                {{-- ============================================================
                     1. JENIS TRANSAKSI
                     ============================================================ --}}
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-gray-700 tracking-tight uppercase">
                        Jenis Transaksi <span class="text-red-500">*</span>
                    </label>
                    <div class="relative flex p-1 bg-gray-100 rounded-xl">
                        <button
                            type="button"
                            wire:click="$set('type', 'income')"
                            class="relative w-1/3 flex items-center justify-center gap-1.5 py-2.5 text-xs font-bold rounded-lg transition-all duration-300 {{ $type === 'income' ? 'text-emerald-700 bg-white shadow-md ring-1 ring-black/5' : 'text-gray-500 hover:text-gray-700' }}">
                            <svg class="w-4 h-4 {{ $type === 'income' ? 'text-emerald-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                            Pemasukan
                        </button>
                        <button
                            type="button"
                            wire:click="$set('type', 'expense')"
                            class="relative w-1/3 flex items-center justify-center gap-1.5 py-2.5 text-xs font-bold rounded-lg transition-all duration-300 {{ $type === 'expense' ? 'text-rose-700 bg-white shadow-md ring-1 ring-black/5' : 'text-gray-500 hover:text-gray-700' }}">
                            <svg class="w-4 h-4 {{ $type === 'expense' ? 'text-rose-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                            </svg>
                            Pengeluaran
                        </button>
                        <button
                            type="button"
                            wire:click="$set('type', 'transfer')"
                            class="relative w-1/3 flex items-center justify-center gap-1.5 py-2.5 text-xs font-bold rounded-lg transition-all duration-300 {{ $type === 'transfer' ? 'text-blue-700 bg-white shadow-md ring-1 ring-black/5' : 'text-gray-500 hover:text-gray-700' }}">
                            <svg class="w-4 h-4 {{ $type === 'transfer' ? 'text-blue-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                            Transfer
                        </button>
                    </div>
                    @error('type')
                        <p class="text-xs text-rose-500 font-medium flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- ============================================================
                     2. REKENING (Compact Horizontal Chips)
                     ============================================================ --}}
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-gray-700 tracking-tight uppercase">
                        {{ $type === 'transfer' ? 'Rekening Sumber' : 'Rekening' }} <span class="text-red-500">*</span>
                    </label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($this->accounts as $account)
                            <button
                                type="button"
                                wire:key="account-src-{{ $account->id }}"
                                wire:click="$set('account_id', {{ $account->id }})"
                                class="group inline-flex items-center gap-2 px-3 py-2 rounded-xl border transition-all duration-200
                                    {{ $account_id == $account->id
                                        ? 'border-slate-800 bg-slate-50 shadow-sm ring-1 ring-slate-800/10'
                                        : 'border-gray-200 bg-white hover:border-gray-400 hover:bg-gray-50' }}">
                                {{-- Icon --}}
                                <div class="w-7 h-7 flex items-center justify-center rounded-lg shrink-0
                                    {{ $account_id == $account->id ? 'bg-slate-900 text-white' : 'bg-gray-100 text-gray-500 group-hover:bg-gray-200' }}">
                                    @if($account->type === 'tabungan')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        </svg>
                                    @elseif($account->type === 'ewallet')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    @endif
                                </div>
                                {{-- Name + Balance --}}
                                <div class="text-left min-w-0">
                                    <div class="text-xs font-bold truncate {{ $account_id == $account->id ? 'text-slate-900' : 'text-gray-700' }}">{{ $account->name }}</div>
                                    <div class="text-[10px] text-gray-500 leading-tight">Rp {{ number_format($account->balance, 0, ',', '.') }}</div>
                                </div>
                            </button>
                        @endforeach
                    </div>
                    @error('account_id') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Rekening Tujuan (khusus Transfer) --}}
                @if($type === 'transfer')
                    <div class="space-y-1.5" x-data x-show="true" x-transition.opacity.duration.300ms>
                        <label class="block text-xs font-semibold text-gray-700 tracking-tight uppercase">Rekening Tujuan <span class="text-red-500">*</span></label>
                        <div class="flex flex-wrap gap-2">
                            @foreach($this->accounts as $account)
                                @if($account->id != $account_id)
                                    <button
                                        type="button"
                                        wire:key="account-dest-{{ $account->id }}"
                                        wire:click="$set('to_account_id', {{ $account->id }})"
                                        class="group inline-flex items-center gap-2 px-3 py-2 rounded-xl border transition-all duration-200
                                            {{ $to_account_id == $account->id
                                                ? 'border-blue-600 bg-blue-50 shadow-sm ring-1 ring-blue-600/10'
                                                : 'border-gray-200 bg-white hover:border-gray-400 hover:bg-gray-50' }}">
                                        <div class="w-7 h-7 flex items-center justify-center rounded-lg shrink-0
                                            {{ $to_account_id == $account->id ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-500 group-hover:bg-gray-200' }}">
                                            @if($account->type === 'tabungan')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                            @endif
                                        </div>
                                        <span class="text-xs font-bold {{ $to_account_id == $account->id ? 'text-blue-900' : 'text-gray-700' }} truncate">{{ $account->name }}</span>
                                    </button>
                                @endif
                            @endforeach
                        </div>
                        @error('to_account_id') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                @endif

                {{-- ============================================================
                     3. NOMINAL + TANGGAL (side by side)
                     ============================================================ --}}
                <div class="grid grid-cols-5 gap-3">
                    {{-- Nominal (ambil 3/5 lebar) --}}
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
                        class="col-span-3 space-y-1.5">
                        <label class="block text-xs font-semibold text-gray-700 tracking-tight uppercase">Nominal <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                                <span class="text-gray-400 text-sm font-bold">Rp</span>
                            </div>
                            <input
                                type="text"
                                x-model="display"
                                class="block w-full rounded-xl border-0 py-3 pl-10 pr-3 text-gray-900 font-extrabold shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-300 focus:ring-2 focus:ring-inset focus:ring-slate-500 text-lg sm:leading-6 bg-white transition-all"
                                placeholder="0">
                        </div>
                        @error('amount') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    {{-- Tanggal (ambil 2/5 lebar) --}}
                    <div class="col-span-2 space-y-1.5">
                        <label class="block text-xs font-semibold text-gray-700 tracking-tight uppercase">Tanggal <span class="text-red-500">*</span></label>
                        <input
                            type="date"
                            wire:model="date"
                            class="block w-full rounded-xl border-0 py-3 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 focus:ring-2 focus:ring-inset focus:ring-slate-500 text-sm sm:leading-6 bg-white transition-all">
                        @error('date') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- ============================================================
                     4. KATEGORI (Pill Selection — filtered by type)
                     ============================================================ --}}
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-semibold text-gray-700 tracking-tight uppercase">Kategori <span class="text-red-500">*</span></label>
                        <button
                            x-data
                            x-on:click.prevent="$dispatch('open-modal', 'modal-category')"
                            type="button"
                            class="text-[11px] font-bold text-slate-600 hover:text-slate-800 transition-colors">
                            + Tambah
                        </button>
                    </div>
                    <div class="flex flex-wrap gap-1.5">
                        @forelse ($this->filteredCategories as $category)
                            <button
                                type="button"
                                wire:key="category-{{ $category->id }}"
                                wire:click="$set('category_id', {{ $category->id }})"
                                class="px-3 py-1.5 rounded-lg text-xs font-bold border transition-all duration-200
                                    {{ $category_id == $category->id
                                        ? 'bg-slate-900 border-slate-900 text-white shadow-md'
                                        : 'bg-white border-gray-200 text-gray-600 hover:border-gray-400' }}">
                                {{ $category->name }}
                            </button>
                        @empty
                            <p class="text-xs text-gray-400 italic py-1">
                                {{ $type ? 'Belum ada kategori untuk jenis ini' : 'Pilih jenis transaksi dulu' }}
                            </p>
                        @endforelse
                    </div>
                    @error('category_id') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- ============================================================
                     5. CATATAN TAMBAHAN (Collapsible)
                     ============================================================ --}}
                <div x-data="{ open: @entangle('showNotes') }">
                    <button
                        type="button"
                        x-show="!open"
                        x-on:click="open = true"
                        class="inline-flex items-center gap-1 text-xs font-semibold text-slate-500 hover:text-slate-700 transition-colors py-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Tambah catatan
                    </button>

                    <div x-show="open" x-collapse x-cloak class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-semibold text-gray-700 tracking-tight uppercase">Nama / Catatan</label>
                            <button
                                type="button"
                                x-on:click="open = false; $wire.set('name', '')"
                                class="text-[11px] font-bold text-gray-400 hover:text-gray-600 transition-colors">
                                Sembunyikan
                            </button>
                        </div>
                        <input
                            wire:model="name"
                            type="text"
                            class="block w-full rounded-xl border-0 py-2.5 px-3.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-slate-500 text-sm bg-white transition-all"
                            placeholder="Contoh: Gaji ke-13, Beli Kopi...">
                        @error('name') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- ============================================================
                     FOOTER ACTION (compact)
                     ============================================================ --}}
                <div class="pt-3 mt-1 border-t border-gray-100 flex flex-col-reverse sm:flex-row justify-end gap-2">
                    <button
                        x-on:click="$dispatch('close-modal', 'modal-transaction')"
                        type="button"
                        class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-white text-gray-700 text-sm font-bold shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-all duration-200">
                        Batal
                    </button>
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        class="w-full sm:w-auto px-8 py-2.5 rounded-xl bg-slate-900 text-white text-sm font-bold shadow-xl shadow-slate-200 hover:bg-slate-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-slate-600 transition-all duration-200 disabled:opacity-70 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="save">
                            {{ $this->isEditing() ? 'Simpan Perubahan' : 'Simpan Transaksi' }}
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
        @endif
    </div>
</x-modal>