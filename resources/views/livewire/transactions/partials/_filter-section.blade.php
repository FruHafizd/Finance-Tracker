{{-- ============================================================
    Filter Section — Header Aksi + Filter Controls
    ============================================================ --}}

<div class="bg-bg-sidebar px-5 sm:px-7 py-6" x-data="{ showAdvanced: false }">
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

        {{-- Baris Utama Filter --}}
        <div class="flex flex-col lg:flex-row gap-4 items-center justify-between">
            {{-- Search Input (Paling lebar) --}}
            <div class="relative w-full lg:flex-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                    <i class="ti ti-search text-text-muted text-lg"></i>
                </span>
                <input type="text" 
                    wire:model.live.debounce.500ms="search"
                    placeholder="Cari transaksi, catatan, atau nominal..."
                    class="w-full rounded-xl border border-border bg-white pl-10 pr-4 py-2.5 text-[13px] font-medium text-text placeholder-text-muted focus:border-primary focus:ring-1 focus:ring-primary transition-shadow duration-150 outline-none" />
            </div>

            {{-- Controls Group --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 w-full lg:w-auto flex-shrink-0">
                {{-- Dropdown Quick-Date --}}
                <select wire:model.live="quickDate"
                    class="rounded-xl border border-border bg-white px-3.5 py-2.5 text-[13px] font-semibold text-text focus:border-primary focus:ring-1 focus:ring-primary transition-shadow outline-none cursor-pointer">
                    <option value="this_month">Bulan ini</option>
                    <option value="last_month">Bulan lalu</option>
                    <option value="last_3_months">3 bulan terakhir</option>
                    <option value="this_year">Tahun ini</option>
                    <option value="custom">Rentang custom</option>
                </select>

                {{-- Dropdown Tipe --}}
                <select wire:model.live="filterType"
                    class="rounded-xl border border-border bg-white px-3.5 py-2.5 text-[13px] font-semibold text-text focus:border-primary focus:ring-1 focus:ring-primary transition-shadow outline-none cursor-pointer">
                    <option value="">Semua Tipe</option>
                    <option value="income">Pemasukan</option>
                    <option value="expense">Pengeluaran</option>
                    <option value="transfer">Transfer</option>
                </select>

                {{-- Dropdown Kategori --}}
                <select wire:model.live="filterCategory"
                    class="rounded-xl border border-border bg-white px-3.5 py-2.5 text-[13px] font-semibold text-text focus:border-primary focus:ring-1 focus:ring-primary transition-shadow outline-none cursor-pointer truncate max-w-[150px]">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>

                {{-- Tombol Filter Lanjutan --}}
                <button type="button"
                    @click="showAdvanced = !showAdvanced"
                    :class="showAdvanced || $wire.quickDate === 'custom' ? 'bg-primary text-white border-primary' : 'bg-white text-text-muted border-border hover:text-text'"
                    class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2.5 border rounded-xl text-[13px] font-bold transition-all duration-150 shadow-sm cursor-pointer">
                    <i class="ti ti-adjustments-horizontal text-base"></i>
                    <span>Filter Lanjutan</span>
                </button>
            </div>
        </div>

        {{-- Baris Chip Filter Aktif --}}
        @if(count($this->activeFilters) > 0)
            <div class="flex flex-wrap items-center gap-2 mt-2">
                <span class="text-[11px] font-bold text-text-muted uppercase tracking-wider mr-1">Filter Aktif:</span>
                @foreach($this->activeFilters as $chip)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-primary/10 text-primary text-xs font-bold rounded-lg ring-1 ring-inset ring-primary/20 transition-all duration-150">
                        {{ $chip['label'] }}
                        <button type="button" wire:click="removeFilter('{{ $chip['key'] }}')" class="hover:text-danger transition-colors focus:outline-none cursor-pointer">
                            <i class="ti ti-x font-bold"></i>
                        </button>
                    </span>
                @endforeach
                <button type="button" wire:click="resetFilters" class="text-xs font-bold text-danger hover:text-danger/80 transition-colors ml-2 cursor-pointer">
                    Reset Semua
                </button>
            </div>
        @endif

        {{-- Panel Filter Lanjutan (Collapsible) --}}
        <div x-show="showAdvanced || $wire.quickDate === 'custom'" 
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="grid grid-cols-1 sm:grid-cols-3 gap-4 bg-white border border-border p-5 rounded-2xl shadow-sm mt-3"
            x-cloak>
            
            {{-- Dari Tanggal --}}
            <div>
                <label class="block text-[11px] font-bold text-text-muted mb-1.5 uppercase tracking-wide">Dari Tanggal</label>
                <input type="date" wire:model.live="startDate"
                    class="w-full rounded-xl border border-border bg-white px-3.5 py-2.5 text-[13px] font-medium text-text focus:border-primary focus:ring-1 focus:ring-primary transition-shadow outline-none" />
            </div>

            {{-- Sampai Tanggal --}}
            <div>
                <label class="block text-[11px] font-bold text-text-muted mb-1.5 uppercase tracking-wide">Sampai Tanggal</label>
                <input type="date" wire:model.live="endDate"
                    class="w-full rounded-xl border border-border bg-white px-3.5 py-2.5 text-[13px] font-medium text-text focus:border-primary focus:ring-1 focus:ring-primary transition-shadow outline-none" />
            </div>

            {{-- Urutkan --}}
            <div>
                <label class="block text-[11px] font-bold text-text-muted mb-1.5 uppercase tracking-wide">Urutkan</label>
                <select wire:model.live="sortBy"
                    class="w-full rounded-xl border border-border bg-white px-3.5 py-2.5 text-[13px] font-medium text-text focus:border-primary focus:ring-1 focus:ring-primary transition-shadow outline-none cursor-pointer">
                    <option value="latest">Terbaru</option>
                    <option value="oldest">Terlama</option>
                    <option value="amount_desc">Nominal Tertinggi</option>
                    <option value="amount_asc">Nominal Terendah</option>
                </select>
            </div>
        </div>

    </div>
</div>
