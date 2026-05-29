{{-- ============================================================
    Mobile Card View — Grouped by Date
    Responsive: tampil di bawah md breakpoint
    Bahasa: Semua label dalam Bahasa Indonesia
    ============================================================ --}}

<div class="md:hidden divide-y divide-border/50">
    @forelse ($grouped as $date => $items)
        @php
            $parsedDate = \Carbon\Carbon::parse($date);
            \Carbon\Carbon::setLocale('id');
            if ($parsedDate->isToday()) {
                $label = 'Hari Ini';
            } elseif ($parsedDate->isYesterday()) {
                $label = 'Kemarin';
            } else {
                $label = $parsedDate->translatedFormat('l, d F Y');
            }
        @endphp

        {{-- Group Header Mobile --}}
        <div class="sticky top-0 z-10 flex items-center justify-between px-4 py-2 bg-bg/95 backdrop-blur-sm border-t border-b border-border shadow-sm">
            <div class="flex items-center gap-2">
                <div class="w-1.5 h-1.5 rounded-full bg-primary"></div>
                <span class="text-[11px] font-bold text-text-hover uppercase tracking-wider">{{ $label }}</span>
            </div>
            <span class="text-[10px] font-bold text-text-muted bg-bg-sidebar px-1.5 py-0.5 rounded-md ring-1 ring-inset ring-border">{{ $items->count() }} item</span>
        </div>

        {{-- Mobile Cards --}}
        <div class="divide-y divide-border/30 bg-bg-sidebar">
        @foreach ($items as $item)
            <div class="px-4 py-3.5 hover:bg-bg/50 transition-colors duration-150 active:bg-bg">

                {{-- Row 1: Nama + Badge Tipe --}}
                <div class="flex justify-between items-start gap-3 mb-2">
                    <div class="flex-1 min-w-0">
                        <p class="text-[13px] font-bold text-text leading-tight truncate">{{ $item->name }}</p>
                        <div class="flex items-center gap-1.5 mt-1 flex-wrap">
                            <span class="text-[10px] font-medium text-text-muted flex items-center gap-1">
                                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                {{ $item->date->format('H:i') }}
                            </span>
                            <span class="w-0.5 h-0.5 rounded-full bg-border flex-shrink-0"></span>
                            <span class="px-1.5 py-0.5 text-[9px] font-bold rounded ring-1 ring-inset shadow-sm truncate max-w-[120px]"
                                style="background-color: {{ $item->category->color }}15; color: {{ $item->category->color }}; ring-color: {{ $item->category->color }}30;">
                                {{ $item->category->name }}
                            </span>
                        </div>
                    </div>
                    {{-- Badge Tipe — Bahasa Indonesia --}}
                    <span class="px-2 py-0.5 flex-shrink-0 text-[9px] font-bold rounded tracking-wider uppercase ring-1 ring-inset
                        {{ $item->type === 'income'
                            ? 'bg-emerald-50 text-emerald-700 ring-emerald-600/20'
                            : ($item->type === 'transfer'
                                ? 'bg-blue-50 text-blue-700 ring-blue-600/20'
                                : 'bg-rose-50 text-rose-700 ring-rose-600/20') }}">
                        {{ $item->type === 'income' ? 'Masuk' : ($item->type === 'transfer' ? 'Transfer' : 'Keluar') }}
                    </span>
                </div>

                {{-- Row 2: Jumlah + Aksi --}}
                <div class="flex items-center justify-between">
                    <p class="text-[15px] font-black tracking-tight
                        {{ $item->type === 'income'
                            ? 'text-emerald-600'
                            : ($item->type === 'transfer'
                                ? 'text-blue-600'
                                : 'text-text') }}">
                        {{ $item->type === 'income' ? '+' : ($item->type === 'transfer' ? '⇅' : '-') }}Rp {{ number_format($item->amount, 0, ',', '.') }}
                    </p>
                    <div class="flex items-center gap-1">
                        {{-- Edit --}}
                        <button x-data
                            x-on:click.prevent="
                                $dispatch('edit-transaction', { id: {{ $item->id }} });
                                $dispatch('open-modal', 'modal-edit');
                            "
                            class="p-1.5 text-text-muted hover:text-primary hover:bg-primary-light rounded-lg ring-1 ring-inset ring-border transition-all duration-150 active:scale-95 bg-bg-sidebar">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>

                        {{-- Delete --}}
                        <button x-data
                            x-on:click.prevent="$wire.confirmDelete({{ $item->id }})"
                            class="p-1.5 text-text-muted hover:text-danger hover:bg-danger/10 rounded-lg ring-1 ring-inset ring-border transition-all duration-150 active:scale-95 bg-bg-sidebar">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>

                        {{-- Favorite --}}
                        <button wire:click="addToFavorite({{ $item->id }})"
                            title="Jadikan Transaksi Cepat"
                            class="p-1.5 text-text-muted hover:text-amber-500 hover:bg-amber-50 rounded-lg ring-1 ring-inset ring-border transition-all duration-150 active:scale-95 bg-bg-sidebar">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
        </div>

    @empty
        {{-- Empty State Mobile --}}
        <div class="py-16 text-center px-4">
            <div class="flex flex-col items-center justify-center">
                <div class="w-14 h-14 bg-bg rounded-2xl flex items-center justify-center mb-4 ring-1 ring-inset ring-border shadow-sm">
                    <svg class="w-7 h-7 text-text-muted" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <p class="text-sm font-bold text-text tracking-tight">Belum ada aktivitas</p>
                <p class="text-[12px] text-text-muted mt-1 max-w-[220px]">Catat pemasukan atau pengeluaran pertama Anda hari ini.</p>
                <button x-data x-on:click.prevent="$dispatch('open-modal', 'modal-create')"
                        class="mt-5 inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-primary text-white text-[12px] font-bold rounded-xl shadow-sm ring-1 ring-inset ring-primary/20 hover:bg-primary-hover active:bg-primary transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Tambah Transaksi
                </button>
            </div>
        </div>
    @endforelse
</div>
