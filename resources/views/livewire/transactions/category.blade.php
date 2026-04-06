<x-modal name="modal-category" focusable>
    <div class="max-h-[92vh] overflow-y-auto">

        <!-- HEADER -->
        <div class="sticky top-0 z-10 bg-white/80 backdrop-blur-md border-b border-gray-100 px-6 py-5 sm:px-8">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 tracking-tight">Kelola Kategori</h2>
                    <p class="text-sm text-gray-500 mt-1 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>
                        Tambah, ubah, atau hapus kategori
                    </p>
                </div>
                <button
                    x-on:click="$dispatch('close-modal', 'modal-category')"
                    type="button"
                    class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-full transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- BODY -->
        <div class="px-6 py-6 sm:px-8 space-y-7">

            <!-- ERROR MESSAGE -->
            @if ($errorMessage)
                <div class="flex items-start gap-3 p-4 bg-red-50/80 rounded-2xl text-sm text-red-600">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ $errorMessage }}</span>
                </div>
            @endif

            <!-- INPUT TAMBAH / EDIT -->
            <div class="space-y-3">
                <label class="block text-sm font-medium text-gray-700">
                    {{ $editId ? 'Edit Kategori' : 'Kategori Baru' }}
                </label>
                <div class="flex flex-col sm:flex-row items-center gap-3">
                    <!-- Color Picker & Name -->
                    <div class="relative flex flex-1 items-center gap-3 w-full">
                        <div class="relative flex-shrink-0">
                            <input
                                wire:model="color"
                                type="color"
                                title="Pilih warna"
                                class="w-12 h-12 rounded-xl border-0 cursor-pointer bg-gray-50/50 shadow-sm ring-1 ring-inset ring-gray-200 p-1">
                        </div>
                        <input
                            wire:model="name"
                            type="text"
                            placeholder="Nama kategori..."
                            class="block w-full rounded-xl border-0 py-3 px-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-slate-500 sm:text-sm sm:leading-6 bg-gray-50/50 hover:bg-white transition-colors">
                    </div>
                    
                    <!-- Action Button -->
                    <button
                        wire:click="{{ $editId ? 'update' : 'create' }}"
                        class="w-full sm:w-auto flex-shrink-0 px-6 py-3 rounded-xl text-sm font-semibold transition-all duration-200 shadow-sm flex items-center justify-center gap-2
                            {{ $editId
                                ? 'bg-amber-500 hover:bg-amber-600 text-white'
                                : 'bg-slate-900 hover:bg-slate-800 text-white' }}">
                        @if ($editId)
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan Perubahan
                        @else
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Baru
                        @endif
                    </button>
                </div>
                @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- DIVIDER -->
            <div class="border-t border-gray-100"></div>

            <!-- LIST KATEGORI -->
            <div class="space-y-3">
                <p class="text-sm font-medium text-gray-700">Daftar Kategori Anda</p>

                <div class="grid grid-cols-1 gap-2 max-h-[40vh] overflow-y-auto pr-1 pb-4">
                    @forelse ($categories as $cat)
                        <div class="group flex items-center justify-between p-3 bg-white rounded-xl shadow-sm ring-1 ring-inset ring-gray-100 hover:ring-slate-200 hover:bg-slate-50/30 transition-all duration-200">
                            <div class="flex items-center gap-3">
                                <div class="w-4 h-4 rounded-full flex-shrink-0 ring-2 ring-white shadow-sm" style="background: {{ $cat->color }}"></div>
                                <span class="text-sm font-medium text-gray-700">{{ $cat->name }}</span>
                            </div>
                            <div x-data="{ confirming: false }" 
                                 x-bind:class="confirming ? 'opacity-100' : 'opacity-100 sm:opacity-0 sm:group-hover:opacity-100'"
                                 class="flex items-center gap-1 transition-opacity duration-200">
                                
                                <!-- Normal State -->
                                <div x-show="!confirming" class="flex items-center gap-1">
                                    <button
                                        wire:click="startEdit({{ $cat->id }})"
                                        title="Edit"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-slate-700 hover:bg-slate-100 transition-all duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button
                                        x-on:click="confirming = true"
                                        title="Hapus"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-rose-600 hover:bg-rose-100 transition-all duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>

                                <!-- Confirm State -->
                                <div x-show="confirming" style="display: none;" class="flex items-center gap-1.5 bg-rose-50 px-2 py-1.5 rounded-lg ring-1 ring-inset ring-rose-200">
                                    <span class="text-[10px] font-bold text-rose-600 uppercase tracking-wider ml-1 mr-0.5">Hapus?</span>
                                    <button
                                        x-on:click="confirming = false"
                                        class="px-2.5 py-1 rounded-md bg-white text-gray-600 text-xs font-semibold hover:bg-gray-50 shadow-sm ring-1 ring-inset ring-gray-200 transition-colors">
                                        Batal
                                    </button>
                                    <button
                                        wire:click="delete({{ $cat->id }})"
                                        class="px-2.5 py-1 rounded-md bg-rose-600 text-white text-xs font-semibold hover:bg-rose-700 shadow-sm transition-colors flex items-center gap-1">
                                        <span wire:loading.remove wire:target="delete({{ $cat->id }})">Ya</span>
                                        <span wire:loading wire:target="delete({{ $cat->id }})">
                                            <svg class="animate-spin h-3 w-3 text-white" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 px-4 rounded-2xl bg-gray-50/50 border border-dashed border-gray-200">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            <p class="text-sm font-medium text-gray-500">Belum ada kategori yang dibuat</p>
                            <p class="text-xs text-gray-400 mt-1">Gunakan form di atas untuk menambahkan kategori baru.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-modal>
