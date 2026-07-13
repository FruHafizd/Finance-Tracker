<div>
    <div class="relative inline-flex align-middle w-full sm:w-auto" x-data="{ open: false }">
        <!-- Tombol Utama: Tambah Transaksi -->
        <button wire:click="$dispatch('open-create-transaction')"
            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-primary hover:bg-primary-hover text-white text-[13px] font-bold rounded-l-xl transition-colors duration-150 shadow-sm ring-1 ring-inset ring-primary/20 w-full sm:w-auto border-r border-primary-hover">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Transaksi
        </button>
        
        <!-- Tombol Samping (Chevron) -->
        <button @click="open = !open"
            class="inline-flex items-center justify-center px-3 py-2 bg-primary hover:bg-primary-hover text-white rounded-r-xl transition-colors duration-150 shadow-sm ring-1 ring-inset ring-primary/20">
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
             
             <div class="px-3 py-2 text-[11px] font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                 Transaksi Cepat
             </div>
             
             <div class="max-h-60 overflow-y-auto">
                 @forelse($favorites as $fav)
                     <div class="flex items-center justify-between px-3 py-2 hover:bg-gray-50 transition-colors duration-150 group">
                         <!-- Clickable area to prefill -->
                         <button @click="open = false" wire:click="prefill({{ $fav->id }})" class="flex items-center gap-2 min-w-0 flex-1 text-left">
                             <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background-color: {{ $fav->category->color ?? '#94a3b8' }}"></span>
                             <div class="min-w-0">
                                 <p class="text-[13px] font-bold text-gray-800 truncate" title="{{ $fav->name }}">{{ $fav->name }}</p>
                                 <p class="text-[11px] font-semibold {{ $fav->type === 'income' ? 'text-emerald-600' : 'text-rose-500' }}">
                                     Rp {{ number_format($fav->amount, 0, ',', '.') }}
                                     @if(!$fav->account)
                                         <span class="text-[9px] font-medium text-gray-400 ml-1">(Tanpa Rekening)</span>
                                     @else
                                         <span class="text-[9px] font-medium text-gray-500 ml-1">({{ $fav->account->name }})</span>
                                     @endif
                                 </p>
                             </div>
                         </button>
                         
                         <!-- Edit & Delete Buttons -->
                         <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity duration-150">
                             <!-- Edit button -->
                             <button wire:click="editFavorite({{ $fav->id }})" @click="open = false" title="Edit Template" class="p-1 text-gray-400 hover:text-slate-700 hover:bg-gray-150 rounded-lg transition-colors">
                                 <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                 </svg>
                             </button>
                             <!-- Delete button -->
                             <button wire:click="confirmDelete({{ $fav->id }})" @click="open = false" title="Hapus Template" class="p-1 text-rose-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                                 <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                 </svg>
                             </button>
                         </div>
                     </div>
                 @empty
                     <div class="px-3 py-4 text-xs text-gray-400 text-center italic">
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
            <div class="sticky top-0 z-10 bg-white/80 backdrop-blur-md border-b border-gray-100 px-6 py-5 sm:px-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 tracking-tight">Edit Transaksi Cepat</h2>
                        <p class="text-sm text-gray-500 mt-1 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                            Sesuaikan data template ini
                        </p>
                    </div>
                    <button x-on:click="$dispatch('close-modal', 'modal-edit-favorite')" type="button" class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-full transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-200">
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
                    <label class="block text-sm font-medium text-gray-700">Jenis Transaksi <span class="text-red-500">*</span></label>
                    <div class="relative flex p-1 bg-gray-100/80 rounded-2xl">
                        <button type="button" wire:click="$set('editType', 'income')" class="relative w-1/2 flex items-center justify-center gap-2 py-2.5 text-sm font-semibold rounded-xl transition-all duration-300 {{ $editType === 'income' ? 'text-emerald-700 bg-white shadow-sm ring-1 ring-black/5' : 'text-gray-500 hover:text-gray-700' }}">
                            Pemasukan
                        </button>
                        <button type="button" wire:click="$set('editType', 'expense')" class="relative w-1/2 flex items-center justify-center gap-2 py-2.5 text-sm font-semibold rounded-xl transition-all duration-300 {{ $editType === 'expense' ? 'text-rose-700 bg-white shadow-sm ring-1 ring-black/5' : 'text-gray-500 hover:text-gray-700' }}">
                            Pengeluaran
                        </button>
                    </div>
                    @error('editType') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Nama -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Nama Transaksi Cepat <span class="text-red-500">*</span></label>
                    <input wire:model="editName" type="text" class="block w-full rounded-xl border-0 py-3 px-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-slate-500 sm:text-sm sm:leading-6 bg-gray-50/50 hover:bg-white transition-colors" placeholder="Contoh: Beli Kopi">
                    @error('editName') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
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
                        <label class="block text-sm font-semibold text-gray-700 tracking-tight">Nominal <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                <span class="text-gray-500 sm:text-sm font-bold">Rp</span>
                            </div>
                            <input type="text" x-model="display" @input="raw = display.replace(/\D/g, ''); display = format(raw);" class="block w-full rounded-2xl border-0 py-4 pl-12 pr-4 text-gray-900 font-extrabold shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-300 focus:ring-2 focus:ring-inset focus:ring-slate-500 text-xl sm:leading-6 bg-white transition-all" placeholder="0">
                        </div>
                        @error('editAmount') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Kategori (Pill Selection) -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700 tracking-tight">Kategori <span class="text-red-500">*</span></label>
                        <div class="flex flex-wrap gap-2 py-1">
                            @foreach ($categories as $category)
                                <button
                                    type="button"
                                    wire:key="edit-category-{{ $category->id }}"
                                    wire:click="$set('editCategoryId', {{ $category->id }})"
                                    class="px-3 py-2 rounded-xl text-xs font-bold border transition-all duration-200 {{ $editCategoryId == $category->id ? 'bg-slate-900 border-slate-900 text-white shadow-md' : 'bg-white border-gray-200 text-gray-600 hover:border-gray-400' }}">
                                    {{ $category->name }}
                                </button>
                            @endforeach
                            @if($categories->isEmpty())
                                <p class="text-xs text-gray-400 italic">Belum ada kategori</p>
                            @endif
                        </div>
                        @error('editCategoryId') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Pemilihan Rekening (Matching TransactionForm Style) -->
                <div class="space-y-4">
                    <div class="space-y-3">
                        <label class="block text-sm font-semibold text-gray-700 tracking-tight">Rekening <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @foreach($accounts as $account)
                                <button
                                    type="button"
                                    wire:key="edit-account-{{ $account->id }}"
                                    wire:click="$set('editAccountId', {{ $account->id }})"
                                    class="group relative flex flex-col items-center justify-center p-3 rounded-2xl border-2 transition-all duration-200 {{ $editAccountId == $account->id ? 'border-slate-800 bg-slate-50/50 shadow-sm' : 'border-gray-100 bg-white hover:border-gray-300 hover:bg-gray-50' }}">
                                    <div class="w-10 h-10 flex items-center justify-center rounded-xl mb-2 {{ $editAccountId == $account->id ? 'bg-slate-900 text-white' : 'bg-gray-100 text-gray-500 group-hover:bg-white' }}">
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
                                        <p class="text-xs font-bold {{ $editAccountId == $account->id ? 'text-slate-900' : 'text-gray-700' }}">{{ $account->name }}</p>
                                        <p class="text-[10px] text-slate-500">Rp {{ number_format($account->balance, 0, ',', '.') }}</p>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                        @error('editAccountId') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- FOOTER ACTION -->
                <div class="pt-6 mt-4 border-t border-gray-100 flex flex-col-reverse sm:flex-row justify-end gap-3">
                    <button type="button" x-on:click="$dispatch('close-modal', 'modal-edit-favorite')" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-white text-gray-700 text-sm font-semibold shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-all duration-200">
                        Batal
                    </button>
                    <button type="submit" wire:loading.attr="disabled" wire:target="updateFavorite" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-slate-900 text-white text-sm font-semibold shadow-sm hover:bg-slate-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-slate-600 transition-all duration-200 disabled:opacity-70 disabled:cursor-not-allowed flex items-center justify-center gap-2">
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