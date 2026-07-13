{{-- ============================================================
    Filter Section — Header Aksi + Filter Controls
    ============================================================ --}}

<div class="bg-bg-sidebar px-5 sm:px-7 py-6">
    <div class="flex flex-col space-y-6">

        {{-- Header + Action Buttons --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-base font-bold text-text tracking-tight">Daftar Transaksi</h3>
                <p class="text-[13px] font-medium text-text-muted mt-1">Kelola dan pantau seluruh aktivitas keuangan Anda.</p>
            </div>

            <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap">
                {{-- Export --}}
                <button x-data x-on:click.prevent="$dispatch('open-modal', 'modal-export')"
                    class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-bg-sidebar ring-1 ring-inset ring-border hover:ring-text-muted/30 hover:bg-bg text-text-hover text-[13px] font-bold rounded-xl transition-all duration-150 shadow-sm w-full sm:w-auto">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    Export Data
                </button>

                {{-- Kategori --}}
                <button x-data x-on:click.prevent="$dispatch('open-modal', 'modal-category')"
                    class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-bg-sidebar ring-1 ring-inset ring-border hover:ring-text-muted/30 hover:bg-bg text-text-hover text-[13px] font-bold rounded-xl transition-all duration-150 shadow-sm w-full sm:w-auto">
                    <svg class="w-4 h-4 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    Kategori
                </button>

                {{-- Tambah Transaksi --}}
                <livewire:transactions.quick-transaction />
            </div>
        </div>

        {{-- Divider --}}
        <div class="border-t border-border"></div>

        {{-- Filter Controls Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-7 gap-4 items-end bg-bg/50 p-4 rounded-xl ring-1 ring-inset ring-border/50">

            {{-- Filter Tahun --}}
            <div>
                <label class="block text-[11px] font-bold text-text-muted mb-1.5 uppercase tracking-wide">Tahun</label>
                <select wire:model.live="filterYear"
                    class="w-full rounded-xl border-0 ring-1 ring-inset ring-border bg-bg-sidebar px-3.5 py-2.5 text-[13px] font-medium text-text focus:ring-2 focus:ring-inset focus:ring-primary transition-shadow">
                    <option value="">Semua</option>
                    @for ($year = date('Y'); $year >= 2020; $year--)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endfor
                </select>
            </div>

            {{-- Filter Bulan --}}
            <div>
                <label class="block text-[11px] font-bold text-text-muted mb-1.5 uppercase tracking-wide">Bulan</label>
                <select wire:model.live="filterMonth"
                    class="w-full rounded-xl border-0 ring-1 ring-inset ring-border bg-bg-sidebar px-3.5 py-2.5 text-[13px] font-medium text-text focus:ring-2 focus:ring-inset focus:ring-primary transition-shadow">
                    <option value="">Semua</option>
                    <option value="1">Januari</option>
                    <option value="2">Februari</option>
                    <option value="3">Maret</option>
                    <option value="4">April</option>
                    <option value="5">Mei</option>
                    <option value="6">Juni</option>
                    <option value="7">Juli</option>
                    <option value="8">Agustus</option>
                    <option value="9">September</option>
                    <option value="10">Oktober</option>
                    <option value="11">November</option>
                    <option value="12">Desember</option>
                </select>
            </div>

            {{-- Filter Dari Tanggal --}}
            <div>
                <label class="block text-[11px] font-bold text-text-muted mb-1.5 uppercase tracking-wide">Dari</label>
                <input type="date" wire:model.live="startDate"
                    class="w-full rounded-xl border-0 ring-1 ring-inset ring-border bg-bg-sidebar px-3.5 py-2.5 text-[13px] font-medium text-text focus:ring-2 focus:ring-inset focus:ring-primary transition-shadow" />
            </div>

            {{-- Filter Sampai Tanggal --}}
            <div>
                <label class="block text-[11px] font-bold text-text-muted mb-1.5 uppercase tracking-wide">Sampai</label>
                <input type="date" wire:model.live="endDate"
                    class="w-full rounded-xl border-0 ring-1 ring-inset ring-border bg-bg-sidebar px-3.5 py-2.5 text-[13px] font-medium text-text focus:ring-2 focus:ring-inset focus:ring-primary transition-shadow" />
            </div>

            {{-- Filter Tipe --}}
            <div>
                <label class="block text-[11px] font-bold text-text-muted mb-1.5 uppercase tracking-wide">Tipe</label>
                <select wire:model.live="filterType"
                    class="w-full rounded-xl border-0 ring-1 ring-inset ring-border bg-bg-sidebar px-3.5 py-2.5 text-[13px] font-medium text-text focus:ring-2 focus:ring-inset focus:ring-primary transition-shadow">
                    <option value="">Semua</option>
                    <option value="income">Pemasukan</option>
                    <option value="expense">Pengeluaran</option>
                </select>
            </div>

            {{-- Filter Kategori --}}
            <div>
                <label class="block text-[11px] font-bold text-text-muted mb-1.5 uppercase tracking-wide">Kategori</label>
                <select wire:model.live="filterCategory"
                    class="w-full rounded-xl border-0 ring-1 ring-inset ring-border bg-bg-sidebar px-3.5 py-2.5 text-[13px] font-medium text-text focus:ring-2 focus:ring-inset focus:ring-primary transition-shadow">
                    <option value="">Semua</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Tombol Reset --}}
            <div>
                <label class="block text-[11px] font-bold text-transparent mb-1.5 uppercase tracking-wide select-none">-</label>
                <button wire:click="resetFilters"
                    class="w-full inline-flex items-center justify-center gap-1.5 px-3.5 py-2.5 bg-border/50 hover:bg-border border-0 text-text-muted hover:text-text rounded-xl text-[13px] font-bold transition-colors duration-150 ring-1 ring-inset ring-text-muted/10">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Reset
                </button>
            </div>

        </div>

        {{-- Date Range Indicator --}}
        @if($startDate || $endDate)
            <div class="flex items-center gap-2 mt-2 px-1">
                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-text bg-primary-light ring-1 ring-inset ring-primary/20 px-3 py-1 rounded-lg">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d M Y') : '...' }}
                    –
                    {{ $endDate ? \Carbon\Carbon::parse($endDate)->format('d M Y') : '...' }}
                </span>
                <span class="text-[11px] font-semibold text-text-muted">Filter cepat dinonaktifkan sementara</span>
            </div>
        @endif
    </div>
</div>
