<x-modal name="reminder-form" :show="$showForm" maxWidth="md">
    <!-- Header with Close Icon -->
    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4 flex justify-between items-center border-b border-border bg-bg-sidebar">
        <h3 class="text-lg leading-6 font-bold text-text" id="modal-title">
            Tambah Reminder Baru
        </h3>
        <button type="button" x-on:click="$dispatch('close-modal', 'reminder-form')" class="w-10 h-10 flex items-center justify-center text-text-muted hover:text-text hover:bg-bg rounded-lg transition-colors" title="Tutup">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    <!-- Form Content -->
    <div class="px-4 py-5 sm:p-6 bg-bg-sidebar">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-text-muted mb-1.5">Tanggal</label>
                <select wire:model="formDay" class="w-full border-border bg-bg text-text rounded-xl focus:border-primary focus:ring-primary text-sm">
                    @for($i = 1; $i <= \Carbon\Carbon::createFromDate($currentYear, $currentMonth, 1)->daysInMonth; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
                @error('formDay') <span class="text-xs text-danger mt-1 block">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-xs font-semibold text-text-muted mb-1.5">Kategori</label>
                <select wire:model="formCategory" class="w-full border-border bg-bg text-text rounded-xl focus:border-primary focus:ring-primary text-sm">
                    <option value="Investasi">Investasi</option>
                    <option value="Tabungan">Tabungan</option>
                    <option value="Tagihan">Tagihan</option>
                    <option value="Pemasukan">Pemasukan</option>
                </select>
                @error('formCategory') <span class="text-xs text-danger mt-1 block">{{ $message }}</span> @enderror
            </div>
            <div class="sm:col-span-2">
                <label class="block text-xs font-semibold text-text-muted mb-1.5">Keterangan</label>
                <input type="text" wire:model="formDescription" placeholder="Contoh: Cicilan KPR, Gaji Bulanan..." class="w-full border-border bg-bg text-text rounded-xl focus:border-primary focus:ring-primary text-sm">
                @error('formDescription') <span class="text-xs text-danger mt-1 block">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-xs font-semibold text-text-muted mb-1.5">Nominal (Rp)</label>
                <input type="text"
                       x-data="{
                           format(val) {
                               if (!val) return '';
                               let clean = val.toString().replace(/\D/g, '');
                               return clean ? new Intl.NumberFormat('id-ID').format(clean) : '';
                           }
                       }"
                       x-init="
                           $el.value = format($wire.formAmount || '');
                           $watch('$wire.formAmount', value => {
                               $el.value = format(value || '');
                           });
                       "
                       @input="$wire.formAmount = $event.target.value.replace(/\D/g, '')"
                       placeholder="0"
                       class="w-full border-border bg-bg text-text rounded-xl focus:border-primary focus:ring-primary text-sm">
                @error('formAmount') <span class="text-xs text-danger mt-1 block">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-xs font-semibold text-text-muted mb-1.5">Ingatkan</label>
                <select wire:model="formRemindBefore" class="w-full border-border bg-bg text-text rounded-xl focus:border-primary focus:ring-primary text-sm">
                    <option value="0">Pada Hari H</option>
                    <option value="1">H-1</option>
                    <option value="3">H-3</option>
                    <option value="7">H-7</option>
                </select>
                @error('formRemindBefore') <span class="text-xs text-danger mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>
    
    <!-- Actions -->
    <div class="bg-bg px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2 border-t border-border">
        <button type="button" wire:click="saveReminder" class="w-full inline-flex justify-center items-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:ml-3 sm:w-auto sm:text-sm transition-colors min-h-[40px]">
            Simpan
        </button>
        <button type="button" x-on:click="$dispatch('close-modal', 'reminder-form')" class="mt-3 w-full inline-flex justify-center items-center rounded-xl border border-border shadow-sm px-4 py-2 bg-bg-sidebar text-base font-medium text-text hover:bg-bg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200 sm:mt-0 sm:w-auto sm:text-sm transition-colors min-h-[40px]">
            Batal
        </button>
    </div>
</x-modal>
