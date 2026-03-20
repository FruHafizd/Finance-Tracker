<x-modal name="modal-category" focusable>
    <div class="max-h-[92vh] overflow-y-auto">

        <!-- HEADER -->
        <div class="sticky top-0 z-10 bg-white border-b border-gray-100 px-5 py-4 sm:px-7 sm:py-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900 leading-tight">Kelola Kategori</h2>
                        <p class="text-xs text-gray-400 mt-0.5">Tambah, edit, atau hapus kategori transaksi</p>
                    </div>
                </div>
                <button
                    x-on:click="$dispatch('close-modal', 'modal-category')"
                    type="button"
                    class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors duration-150 flex-shrink-0 ml-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- BODY -->
        <div class="px-5 py-5 sm:px-7 sm:py-6 space-y-5">

            <!-- INPUT TAMBAH / EDIT -->
            <div class="space-y-1.5">
                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    {{ $editId ? 'Edit Kategori' : 'Kategori Baru' }}
                </label>
                <div class="flex items-center gap-2">
                    <!-- Color Picker -->
                    <div class="relative flex-shrink-0">
                        <input
                            wire:model="color"
                            type="color"
                            title="Pilih warna"
                            class="w-10 h-10 rounded-xl border border-gray-200 cursor-pointer bg-gray-50 p-1">
                    </div>
                    <!-- Name Input -->
                    <input
                        wire:model="name"
                        type="text"
                        placeholder="Nama kategori..."
                        class="flex-1 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition-all duration-200 bg-gray-50 focus:bg-white">
                    <!-- Action Button -->
                    <button
                        wire:click="{{ $editId ? 'update' : 'create' }}"
                        class="flex-shrink-0 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 shadow-sm flex items-center gap-1.5
                            {{ $editId
                                ? 'bg-amber-500 hover:bg-amber-600 active:bg-amber-700 text-white'
                                : 'bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white' }}">
                        @if ($editId)
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                            Update
                        @else
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah
                        @endif
                    </button>
                </div>
                @error('name')
                    <p class="text-xs text-red-500 flex items-center gap-1 mt-1">
                        <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- DIVIDER -->
            <div class="border-t border-dashed border-gray-200"></div>

            <!-- LIST KATEGORI -->
            <div class="space-y-1.5">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Daftar Kategori</p>

                <!-- ERROR MESSAGE -->
                @if ($errorMessage)
                    <div class="flex items-start gap-2.5 px-3.5 py-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-600 mb-2">
                        <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        {{ $errorMessage }}
                    </div>
                @endif

                @forelse ($categories as $cat)
                    <div class="flex items-center justify-between px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl hover:border-gray-300 transition-all duration-150">
                        <div class="flex items-center gap-3">
                            <div class="w-3.5 h-3.5 rounded-full flex-shrink-0 ring-2 ring-white shadow-sm" style="background: {{ $cat->color }}"></div>
                            <span class="text-sm text-gray-700 font-medium">{{ $cat->name }}</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <button
                                wire:click="startEdit({{ $cat->id }})"
                                title="Edit"
                                class="w-7 h-7 flex items-center justify-center rounded-lg text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition-all duration-150">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <button
                                wire:click="delete({{ $cat->id }})"
                                title="Hapus"
                                class="w-7 h-7 flex items-center justify-center rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all duration-150">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-400">
                        <svg class="w-10 h-10 mx-auto mb-2 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        <p class="text-sm">Belum ada kategori</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-modal>
