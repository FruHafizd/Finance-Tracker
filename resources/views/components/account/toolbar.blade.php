@props(['sortBy', 'sortDir'])

<div class="bg-bg-sidebar rounded-2xl border border-border p-4 sm:p-5 shadow-sm">
    <div class="flex flex-col md:flex-row gap-4">
        {{-- Search --}}
        <div class="relative flex-1">
            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Cari nama rekening..."
                class="w-full pl-12 pr-4 py-3 rounded-xl border-border bg-bg/50 text-sm text-text placeholder:text-text-muted focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200" />
        </div>

        {{-- Sort + Tambah --}}
        <div class="flex items-center gap-2 overflow-x-auto pb-1 md:pb-0 scrollbar-hide">
            <span class="text-xs font-bold text-text-muted uppercase tracking-widest mr-1 whitespace-nowrap hidden lg:inline">Urut:</span>

            <button wire:click="setSort('name')"
                class="flex items-center gap-2 px-4 py-2.5 rounded-xl border text-xs font-bold transition-all duration-200 whitespace-nowrap
                    {{ $sortBy === 'name' ? 'border-primary bg-primary text-white shadow-md hover:bg-primary-hover' : 'border-border bg-bg-sidebar text-text hover:border-text-muted/30 hover:bg-bg' }}">
                Nama
                @if($sortBy === 'name') <span>{{ $sortDir === 'asc' ? '↑' : '↓' }}</span> @endif
            </button>

            <button wire:click="setSort('balance')"
                class="flex items-center gap-2 px-4 py-2.5 rounded-xl border text-xs font-bold transition-all duration-200 whitespace-nowrap
                    {{ $sortBy === 'balance' ? 'border-primary bg-primary text-white shadow-md hover:bg-primary-hover' : 'border-border bg-bg-sidebar text-text hover:border-text-muted/30 hover:bg-bg' }}">
                Saldo
                @if($sortBy === 'balance') <span>{{ $sortDir === 'asc' ? '↑' : '↓' }}</span> @endif
            </button>

            <div class="w-px h-6 bg-border mx-1 flex-shrink-0"></div>

            <button
                x-data
                x-on:click="$dispatch('open-account-form')"
                class="flex items-center justify-center gap-2 bg-primary hover:bg-primary-hover text-white text-xs sm:text-sm font-bold px-4 sm:px-5 py-2.5 sm:py-3 rounded-2xl shadow-lg shadow-primary/10 ring-1 ring-inset ring-primary/20 transition-all duration-200 active:scale-95 whitespace-nowrap flex-shrink-0">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Rekening</span>
            </button>
        </div>
    </div>
</div>
