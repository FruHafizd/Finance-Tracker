<x-modal name="modal-budget" focusable>
    <div class="p-4 sm:p-6 max-h-[90vh] overflow-y-auto bg-bg-sidebar border border-border rounded-xl">
        <div class="flex justify-between items-start mb-6 sticky top-0 bg-bg-sidebar pb-4 border-b border-border">
            <div>
                <h2 class="text-lg sm:text-xl font-semibold text-text">{{ $this->isEditing() ? 'Edit Budget' : 'Tambah Budget' }}</h2>
                <p class="text-xs sm:text-sm text-text-muted mt-1">{{ $this->isEditing() ? 'Ubah batas pengeluaran kategori ini' : 'Tetapkan batas pengeluaran per kategori' }}</p>
            </div>
            <button x-on:click="$dispatch('close-modal', 'modal-budget')" type="button" class="flex-shrink-0 text-text-muted hover:text-text-hover hover:bg-bg rounded-lg p-1.5 transition-colors duration-200 ml-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <form wire:submit="save" class="space-y-5">
            @if ($this->isEditing())
                <div class="space-y-2">
                    <label class="block text-xs font-semibold text-text-muted tracking-wider uppercase flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5l5 5v11a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z" /></svg>
                        Kategori
                    </label>
                    <div class="w-full border border-border bg-bg rounded-lg p-2.5 text-sm text-text-muted flex items-center gap-2 cursor-not-allowed">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        {{ $categoryName }}
                    </div>
                </div>
            @else
                <div class="space-y-2">
                    <label class="block text-xs font-semibold text-text-muted tracking-wider uppercase flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5l5 5v11a2 2 0 01-2 2H7a2 2 0 012-2z" /></svg>
                        Kategori <span class="text-danger">*</span>
                    </label>
                    <select wire:model="category_id" class="w-full border border-border rounded-lg p-2.5 text-sm text-text focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200 bg-bg-sidebar">
                        <option value="0">-- Pilih Kategori --</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <span class="text-xs text-danger flex items-center gap-1 mt-1">{{ $message }}</span>
                    @enderror
                </div>
            @endif

            <div x-data="{ raw: @entangle('limit_amount'), get display() { if (!this.raw) return ''; return this.raw.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'); }, set display(value) { this.raw = value.replace(/\D/g, ''); } }" class="space-y-2">
                <label class="block text-xs font-semibold text-text-muted tracking-wider uppercase flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Batas Pengeluaran <span class="text-danger">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-text-muted text-sm font-medium">Rp</span>
                    <input type="text" x-model="display" inputmode="numeric" placeholder="0" class="w-full border border-border rounded-lg p-2.5 pl-10 text-sm text-text bg-bg-sidebar focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200" />
                </div>
                @error('limit_amount')
                    <span class="text-xs text-danger flex items-center gap-1 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex flex-col-reverse sm:flex-row justify-end gap-2 sm:gap-3 pt-4 border-t border-border mt-2">
                <button x-on:click="$dispatch('close-modal', 'modal-budget')" type="button" class="w-full sm:w-auto border border-border px-4 py-2.5 sm:py-2 rounded-lg hover:bg-bg transition-colors duration-200 font-medium text-sm text-text-muted">Batal</button>
                <button type="submit" wire:loading.attr="disabled" class="w-full sm:w-auto bg-primary text-white px-4 py-2.5 sm:py-2 rounded-lg hover:bg-primary-hover active:scale-95 transition-all duration-200 font-medium text-sm shadow-sm disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                    <span wire:loading.remove wire:target="save" class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        Simpan Budget
                    </span>
                    <span wire:loading wire:target="save" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Menyimpan...
                    </span>
                </button>
            </div>
        </form>
    </div>
</x-modal>
