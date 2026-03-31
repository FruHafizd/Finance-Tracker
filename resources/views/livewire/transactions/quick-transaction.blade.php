<div>
    @if($favorites->count() > 0)
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center ring-1 ring-inset ring-amber-500/20">
                    <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900 tracking-tight">Transaksi Cepat</h3>
                    <p class="text-[13px] font-medium text-gray-400 mt-0.5">Satu klik untuk mencatat transaksi rutin Anda.</p>
                </div>
            </div>
        </div>

        <div x-data="{ isDown: false, startX: 0, scrollLeft: 0 }" 
             x-on:mousedown="isDown = true; $el.classList.remove('scroll-smooth', 'snap-x', 'snap-mandatory'); startX = $event.pageX - $el.offsetLeft; scrollLeft = $el.scrollLeft" 
             x-on:mouseleave="isDown = false; $el.classList.add('scroll-smooth', 'snap-x', 'snap-mandatory')" 
             x-on:mouseup="isDown = false; $el.classList.add('scroll-smooth', 'snap-x', 'snap-mandatory')" 
             x-on:mousemove="if(!isDown) return; $event.preventDefault(); const x = $event.pageX - $el.offsetLeft; const walk = (x - startX) * 2; $el.scrollLeft = scrollLeft - walk"
             class="flex gap-4 overflow-x-auto pb-4 pt-1 px-1 -mx-1 snap-x snap-mandatory scroll-smooth cursor-grab active:cursor-grabbing" 
             style="scrollbar-width: none; -ms-overflow-style: none;">
            <style>
                .overflow-x-auto::-webkit-scrollbar { display: none; }
            </style>
            @foreach($favorites as $fav)
            <div class="snap-start flex-shrink-0 w-64 bg-white rounded-2xl ring-1 ring-inset ring-gray-100 shadow-sm hover:shadow-md transition-all duration-200 group relative overflow-hidden">
                {{-- Decorative top border --}}
                <div class="absolute top-0 inset-x-0 h-1 {{ $fav->type === 'income' ? 'bg-emerald-500' : 'bg-rose-500' }}"></div>
                
                <div class="p-5">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center ring-1 ring-inset shadow-sm"
                                 style="background-color: {{ $fav->category->color }}15; ring-color: {{ $fav->category->color }}30;">
                                <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $fav->category->color }}"></span>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold tracking-wider uppercase {{ $fav->type === 'income' ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $fav->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                                </p>
                                <p class="text-[13px] font-bold text-gray-900 truncate max-w-[120px]" title="{{ $fav->name }}">
                                    {{ $fav->name }}
                                </p>
                            </div>
                        </div>
                        
                        {{-- Dropdown for actions --}}
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" @click.away="open = false" class="p-1.5 text-gray-400 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors focus:outline-none">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                </svg>
                            </button>
                            <div x-show="open" x-transition.opacity class="absolute right-0 mt-1 w-36 bg-white rounded-xl shadow-lg ring-1 ring-gray-100/50 z-20 overflow-hidden" style="display: none;">
                                <div class="py-1">
                                    <button wire:click="editFavorite({{ $fav->id }})" class="flex items-center w-full px-4 py-2.5 text-[13px] text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 font-semibold transition-colors">
                                        <svg class="mr-2.5 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        Edit Template
                                    </button>
                                    <button wire:click="prefill({{ $fav->id }})" class="flex items-center w-full px-4 py-2.5 text-[13px] text-gray-700 hover:bg-amber-50 hover:text-amber-700 font-semibold transition-colors">
                                        <svg class="mr-2.5 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                        Pakai di Form
                                    </button>
                                    <button wire:click="removeFavorite({{ $fav->id }})" wire:confirm="Hapus transaksi cepat ini?" class="flex items-center w-full px-4 py-2.5 text-[13px] text-rose-600 hover:bg-rose-50 font-semibold whitespace-nowrap transition-colors">
                                        <svg class="mr-2.5 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <p class="text-xs font-semibold text-gray-400 mb-0.5">Nominal Transaksi</p>
                        <p class="text-xl font-black tracking-tight text-gray-900">
                            Rp {{ number_format($fav->amount, 0, ',', '.') }}
                        </p>
                    </div>

                    <button
                        wire:click="saveNow({{ $fav->id }})"
                        wire:loading.attr="disabled"
                        wire:target="saveNow({{ $fav->id }})"
                        class="w-full flex items-center justify-center gap-2 py-2.5 bg-gray-50 text-gray-700 hover:bg-indigo-600 hover:text-white text-[13px] font-bold rounded-xl ring-1 ring-inset ring-gray-200/60 hover:ring-indigo-600 transition-all duration-200 group-hover:bg-indigo-50 group-hover:text-indigo-700 group-hover:ring-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600">
                        <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span wire:loading.remove wire:target="saveNow({{ $fav->id }})">Tambah Cepat</span>
                        <span wire:loading wire:target="saveNow({{ $fav->id }})">Menyimpan...</span>
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

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
                    <input wire:model="editName" type="text" class="block w-full rounded-xl border-0 py-3 px-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 bg-gray-50/50 hover:bg-white transition-colors" placeholder="Contoh: Beli Kopi">
                    @error('editName') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Nominal & Kategori -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 sm:gap-6">
                    <!-- Nominal -->
                    <div x-data="{ 
                            display: '', 
                            raw: '', 
                            format(val) { 
                                if (!val) return ''; 
                                return val.toString().replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.'); 
                            } 
                        }" 
                        x-init="
                            $watch('$wire.editAmount', value => { 
                                if(value) { 
                                    raw = value.toString(); 
                                    display = format(raw); 
                                } else { 
                                    display = ''; 
                                    raw = ''; 
                                } 
                            })
                        " class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Nominal <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                <span class="text-gray-500 sm:text-sm font-medium">Rp</span>
                            </div>
                            <input type="text" x-model="display" @input="raw = display.replace(/\D/g, ''); display = format(raw); $wire.editAmount = raw;" class="block w-full rounded-xl border-0 py-3 pl-11 pr-4 text-gray-900 font-semibold shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-base sm:leading-6 bg-gray-50/50 hover:bg-white transition-colors" placeholder="0">
                        </div>
                        @error('editAmount') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Kategori -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Kategori <span class="text-red-500">*</span></label>
                        <select wire:model="editCategoryId" class="block w-full rounded-xl border-0 py-3 pl-4 pr-10 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 appearance-none bg-gray-50/50 hover:bg-white transition-colors cursor-pointer">
                            <option value="" class="text-gray-400">Pilih Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('editCategoryId') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- FOOTER ACTION -->
                <div class="pt-6 mt-4 border-t border-gray-100 flex flex-col-reverse sm:flex-row justify-end gap-3">
                    <button type="button" x-on:click="$dispatch('close-modal', 'modal-edit-favorite')" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-white text-gray-700 text-sm font-semibold shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-all duration-200">
                        Batal
                    </button>
                    <button type="submit" wire:loading.attr="disabled" wire:target="updateFavorite" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-indigo-600 text-white text-sm font-semibold shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-all duration-200 disabled:opacity-70 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="updateFavorite">Simpan Perubahan</span>
                        <span wire:loading wire:target="updateFavorite">Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>
    </x-modal>
</div>