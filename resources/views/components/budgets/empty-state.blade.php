<div class="flex flex-col items-center justify-center py-20 text-center">
    <div class="w-16 h-16 bg-primary-light rounded-2xl flex items-center justify-center mb-4">
        <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
    </div>
    <p class="text-text font-medium">Belum ada budget bulan ini</p>
    <p class="text-sm text-text-muted mt-1">Mulai tetapkan batas pengeluaran per kategori</p>
    <button wire:click="$dispatch('open-create-budget')" class="mt-5 px-4 py-2 bg-primary text-white text-sm font-medium rounded-xl hover:bg-primary-hover transition shadow-sm">+ Tambah Budget Pertama</button>
</div>
