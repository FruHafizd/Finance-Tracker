<div>
    <div class="relative inline-flex align-middle w-full sm:w-auto" x-data="{ open: false }">
        <!-- Tombol Utama: Tambah Transaksi -->
        <button wire:click="$dispatch('open-create-transaction')"
            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-primary hover:bg-primary/90 text-white text-[13px] font-bold rounded-l-xl transition-colors duration-150 shadow-sm ring-1 ring-inset ring-primary/20 w-full sm:w-auto border-r border-white/10">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Transaksi
        </button>
        
        <!-- Tombol Samping (Chevron) -->
        <button @click="open = !open"
            class="inline-flex items-center justify-center px-3 py-2 bg-primary hover:bg-primary/90 text-white rounded-r-xl transition-colors duration-150 shadow-sm ring-1 ring-inset ring-primary/20">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <!-- Dropdown Menu -->
        <div x-show="open" 
             @click.outside="open = false"
             x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="transform opacity-0 scale-95"
             x-transition:enter-end="transform opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="transform opacity-100 scale-100"
             x-transition:leave-end="transform opacity-0 scale-95"
             class="absolute right-0 mt-10 w-72 bg-white rounded-xl shadow-xl ring-1 ring-black/5 z-50 overflow-hidden py-1"
             style="display: none;">
             
             <div class="px-3 py-2 text-[11px] font-bold text-text-muted uppercase tracking-wider border-b border-border">
                 Transaksi Cepat
             </div>
             
             <div class="max-h-60 overflow-y-auto">
                 @forelse($favorites as $fav)
                     <div class="flex items-center justify-between px-3 py-2 hover:bg-bg transition-colors duration-150 group">
                         <!-- Clickable area to prefill -->
                         <button @click="open = false" wire:click="prefill({{ $fav->id }})" class="flex items-center gap-2 min-w-0 flex-1 text-left">
                             <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background-color: {{ $fav->category->color ?? '#94a3b8' }}"></span>
                             <div class="min-w-0">
                                 <p class="text-[13px] font-bold text-text truncate" title="{{ $fav->name }}">{{ $fav->name }}</p>
                                 <p class="text-[11px] font-semibold {{ $fav->type === 'income' ? 'text-primary' : 'text-danger' }}">
                                     Rp {{ number_format($fav->amount, 0, ',', '.') }}
                                     @if(!$fav->account)
                                         <span class="text-[9px] font-medium text-text-muted ml-1">(Tanpa Rekening)</span>
                                     @else
                                         <span class="text-[9px] font-medium text-text-muted ml-1">({{ $fav->account->name }})</span>
                                     @endif
                                 </p>
                             </div>
                         </button>
                         
                         <!-- Edit & Delete Buttons -->
                         <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity duration-150">
                             <!-- Edit button -->
                             <button wire:click="editFavorite({{ $fav->id }})" @click="open = false" title="Edit Template" class="p-1 text-text-muted hover:text-text hover:bg-bg rounded-lg transition-colors">
                                 <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                 </svg>
                             </button>
                             <!-- Delete button -->
                             <button wire:click="confirmDelete({{ $fav->id }})" @click="open = false" title="Hapus Template" class="p-1 text-danger/70 hover:text-danger hover:bg-danger/10 rounded-lg transition-colors">
                                 <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                 </svg>
                             </button>
                         </div>
                     </div>
                 @empty
                     <div class="px-3 py-4 text-xs text-text-muted text-center italic">
                         Belum ada transaksi cepat.
                     </div>
                 @endforelse
             </div>
        </div>
    </div>


    <!-- MODAL EDIT FAVORITE -->
    <x-modal name="modal-edit-favorite" focusable>
        <div class="max-h-[92vh] overflow-y-auto">
            <!-- HEADER -->
            <div class="sticky top-0 z-10 bg-white/80 backdrop-blur-md border-b border-border px-6 py-5 sm:px-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-text tracking-tight">Edit Transaksi Cepat</h2>
                        <p class="text-sm text-text-muted mt-1 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span>
                            Sesuaikan data template ini
                        </p>
                    </div>
                    <button x-on:click="$dispatch('close-modal', 'modal-edit-favorite')" type="button" class="w-10 h-10 flex items-center justify-center text-text-muted hover:text-text hover:bg-bg rounded-full transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-border">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- FORM BODY -->
            <form wire:submit.prevent="updateFavorite" class="px-6 py-6 sm:px-8 space-y-7">
                <!-- Jenis Transaksi -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-text">Jenis Transaksi <span class="text-danger">*</span></label>
                    <div class="relative flex p-1 bg-bg rounded-2xl">
                        <button type="button" wire:click="$set('editType', 'income')" class="relative w-1/2 flex items-center justify-center gap-2 py-2.5 text-sm font-semibold rounded-xl transition-all duration-300 {{ $editType === 'income' ? 'text-primary bg-white shadow-sm ring-1 ring-primary/10' : 'text-text-muted hover:text-text' }}">
                            Pemasukan
                        </button>
                        <button type="button" wire:click="$set('editType', 'expense')" class="relative w-1/2 flex items-center justify-center gap-2 py-2.5 text-sm font-semibold rounded-xl transition-all duration-300 {{ $editType === 'expense' ? 'text-danger bg-white shadow-sm ring-1 ring-danger/10' : 'text-text-muted hover:text-text' }}">
                            Pengeluaran
                        </button>
                    </div>
                    @error('editType') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Nama -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-text">Nama Transaksi Cepat <span class="text-danger">*</span></label>
                    <input wire:model="editName" type="text" class="block w-full rounded-xl border-0 py-3 px-4 text-text shadow-sm ring-1 ring-inset ring-border placeholder:text-text-muted focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6 bg-white transition-colors" placeholder="Contoh: Beli Kopi">
                    @error('editName') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Nominal & Kategori -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 sm:gap-6">
                    <!-- Nominal -->
                    <div x-data="{ 
                            display: '', 
                            raw: @entangle('editAmount'), 
                            format(val) { 
                                if (!val) return ''; 
                                return val.toString().replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.'); 
                            } 
                        }" 
                        x-init="
                            $watch('raw', value => { 
                                display = format(value); 
                            });
                            display = format(raw);
                        " class="space-y-2">
                        <label class="block text-sm font-semibold text-text tracking-tight">Nominal <span class="text-danger">*</span></label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                <span class="text-text-muted sm:text-sm font-bold">Rp</span>
                            </div>
                            <input type="text" x-model="display" @input="raw = display.replace(/\D/g, ''); display = format(raw);" class="block w-full rounded-2xl border-0 py-4 pl-12 pr-4 text-text font-extrabold shadow-sm ring-1 ring-inset ring-border placeholder:text-text-muted focus:ring-2 focus:ring-inset focus:ring-primary text-xl sm:leading-6 bg-white transition-all" placeholder="0">
                        </div>
                        @error('editAmount') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Kategori (Pill Selection) -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="block text-sm font-semibold text-text tracking-tight">Kategori <span class="text-danger">*</span></label>
                            <button
                                x-data
                                x-on:click.prevent="$dispatch('open-modal', 'modal-category')"
                                type="button"
                                class="text-[11px] font-bold text-primary hover:text-primary/80 transition-colors">
                                + Tambah
                            </button>
                        </div>
                        <div class="flex flex-wrap gap-2 py-1">
                            @forelse ($this->filteredCategories as $category)
                                <button
                                    type="button"
                                    wire:key="edit-category-{{ $category->id }}"
                                    wire:click="$set('editCategoryId', {{ $category->id }})"
                                    class="px-3 py-2 rounded-xl text-xs font-bold border transition-all duration-200 {{ $editCategoryId == $category->id ? 'bg-primary border-primary text-white shadow-md' : 'bg-white border-border text-text-muted hover:border-text-muted' }}">
                                    {{ $category->name }}
                                </button>
                            @empty
                                <p class="text-xs text-text-muted italic">
                                    {{ $editType ? 'Belum ada kategori untuk jenis ini' : 'Pilih jenis transaksi dulu' }}
                                </p>
                            @endforelse
                        </div>
                        @error('editCategoryId') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Pemilihan Rekening (Matching TransactionForm Style) -->
                <div class="space-y-4">
                    <div class="space-y-3">
                        <label class="block text-sm font-semibold text-text tracking-tight">Rekening <span class="text-danger">*</span></label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @foreach($this->accounts as $account)
                                <button
                                    type="button"
                                    wire:key="edit-account-{{ $account->id }}"
                                    wire:click="$set('editAccountId', {{ $account->id }})"
                                    class="group relative flex flex-col items-center justify-center p-3 rounded-2xl border-2 transition-all duration-200 {{ $editAccountId == $account->id ? 'border-primary bg-primary/5 shadow-sm' : 'border-border bg-white hover:border-text-muted hover:bg-bg' }}">
                                    <div class="w-10 h-10 flex items-center justify-center rounded-xl mb-2 {{ $editAccountId == $account->id ? 'bg-primary text-white' : 'bg-bg text-text-muted group-hover:bg-white' }}">
                                        @if($account->type === 'bank')
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                            </svg>
                                        @else
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="text-left">
                                        <p class="text-xs font-bold {{ $editAccountId == $account->id ? 'text-primary' : 'text-text' }}">{{ $account->name }}</p>
                                        <p class="text-[10px] text-text-muted">Rp {{ number_format($account->balance, 0, ',', '.') }}</p>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                        @error('editAccountId') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- FOOTER ACTION -->
                <div class="pt-6 mt-4 border-t border-border flex flex-col-reverse sm:flex-row justify-end gap-3">
                    <button type="button" x-on:click="$dispatch('close-modal', 'modal-edit-favorite')" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-white text-text text-sm font-semibold shadow-sm ring-1 ring-inset ring-border hover:bg-bg transition-all duration-200">
                        Batal
                    </button>
                    <button type="submit" wire:loading.attr="disabled" wire:target="updateFavorite" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-primary text-white text-sm font-semibold shadow-sm hover:bg-primary/90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary transition-all duration-200 disabled:opacity-70 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="updateFavorite">Simpan Perubahan</span>
                        <span wire:loading wire:target="updateFavorite">Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>
    </x-modal>

    <x-modal-delete
        name="modal-delete-favorit"
        title="Hapus Transaksi Cepat"
        description="Apakah Anda yakin ingin menghapus template transaksi cepat ini? Data akan dihapus permanen."
        action="delete"
    />
</div>