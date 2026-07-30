{{-- ============================================================
    Desktop Table View — Grouped by Date
    Responsive: tampil di md breakpoint ke atas
    Padding responsive: px-4 md:px-5 lg:px-7
    ============================================================ --}}

<div class="hidden md:block overflow-x-auto">
    <table class="min-w-full divide-y divide-border">
        <thead class="bg-bg/80">
            <tr>
                <th scope="col" class="px-4 md:px-5 lg:px-7 py-3.5 text-left text-xs font-bold text-text-muted uppercase tracking-widest">Nama Transaksi</th>
                <th scope="col" class="px-4 md:px-5 lg:px-7 py-3.5 text-left text-xs font-bold text-text-muted uppercase tracking-widest w-28 lg:w-32">Tipe</th>
                <th scope="col" class="px-4 md:px-5 lg:px-7 py-3.5 text-left text-xs font-bold text-text-muted uppercase tracking-widest w-32 lg:w-40">Kategori</th>
                <th scope="col" class="px-4 md:px-5 lg:px-7 py-3.5 text-right text-xs font-bold text-text-muted uppercase tracking-widest w-36 lg:w-44">Jumlah</th>
                <th scope="col" class="relative px-4 md:px-5 lg:px-7 py-3.5 w-24 lg:w-28"><span class="sr-only">Aksi</span></th>
            </tr>
        </thead>
        <tbody class="bg-bg-sidebar divide-y divide-border/50">
            @forelse ($grouped as $date => $items)
                @php
                    $parsedDate  = \Carbon\Carbon::parse($date);
                    \Carbon\Carbon::setLocale('id');
                    if ($parsedDate->isToday()) {
                        $label = 'Hari Ini';
                    } elseif ($parsedDate->isYesterday()) {
                        $label = 'Kemarin';
                    } else {
                        $label = $parsedDate->translatedFormat('l, d F Y');
                    }
                @endphp

                {{-- Group Header Row --}}
                <tr class="bg-bg/50 border-t border-border">
                    <td colspan="5" class="px-4 md:px-5 lg:px-7 py-2.5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="w-1.5 h-1.5 rounded-full bg-primary"></div>
                                <span class="text-xs font-bold text-text-hover uppercase tracking-wider">{{ $label }}</span>
                            </div>
                            <span class="text-xs font-medium text-text-muted">{{ $items->count() }} transaksi</span>
                        </div>
                    </td>
                </tr>

                {{-- Transaction Rows --}}
                @foreach ($items as $item)
                    <tr class="hover:bg-bg/50 transition-colors duration-150 group">

                        {{-- Nama --}}
                        <td class="px-4 md:px-5 lg:px-7 py-4 text-sm text-text">
                            <span class="line-clamp-2 font-medium max-w-[200px] lg:max-w-sm">{{ $item->name }}</span>
                        </td>

                        {{-- Tipe Badge — Bahasa Indonesia --}}
                        <td class="px-4 md:px-5 lg:px-7 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-1 inline-flex text-[11px] lg:text-xs font-bold rounded-lg tracking-wide
                                {{ $item->type === 'income'
                                    ? 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20'
                                    : ($item->type === 'transfer'
                                        ? 'bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-600/20'
                                        : 'bg-rose-50 text-rose-700 ring-1 ring-inset ring-rose-600/20') }}">
                                {{ $item->type === 'income' ? 'Pemasukan' : ($item->type === 'transfer' ? 'Transfer' : 'Pengeluaran') }}
                            </span>
                        </td>

                        {{-- Kategori Badge --}}
                        <td class="px-4 md:px-5 lg:px-7 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-1 inline-flex text-[11px] lg:text-xs font-bold rounded-lg tracking-wide shadow-sm max-w-[120px] lg:max-w-none truncate"
                                style="background-color: {{ $item->category->color }}15; color: {{ $item->category->color }}; ring: 1px inset {{ $item->category->color }}30;">
                                <span class="w-1.5 h-1.5 rounded-full mr-1.5 mt-1 flex-shrink-0" style="background-color: {{ $item->category->color }}"></span>
                                <span class="truncate">{{ $item->category->name }}</span>
                            </span>
                        </td>

                        {{-- Jumlah --}}
                        <td class="px-4 md:px-5 lg:px-7 py-4 whitespace-nowrap text-right text-sm font-bold tracking-tight
                            {{ $item->type === 'income'
                                ? 'text-emerald-600'
                                : ($item->type === 'transfer'
                                    ? 'text-blue-600'
                                    : 'text-danger') }}">
                            {{ $item->type === 'income' ? '+' : ($item->type === 'transfer' ? '⇅' : '-') }}Rp {{ number_format($item->amount, 0, ',', '.') }}
                        </td>

                        {{-- Aksi --}}
                        <td class="px-4 md:px-5 lg:px-7 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-1 opacity-100 lg:opacity-0 lg:group-hover:opacity-100 transition-opacity duration-200">
                                {{-- Edit --}}
                                <button
                                    x-data
                                    x-on:click.prevent="$dispatch('edit-transaction', { id: {{ $item->id }} });"
                                    title="Edit"
                                    class="p-1.5 text-text-muted hover:text-primary hover:bg-primary-light rounded-lg transition-colors duration-150">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>

                                {{-- Delete --}}
                                <button x-data
                                    x-on:click.prevent="$wire.confirmDelete({{ $item->id }})"
                                    title="Hapus"
                                    class="p-1.5 text-text-muted hover:text-danger hover:bg-danger/10 rounded-lg transition-colors duration-150">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>

                                {{-- Favorite --}}
                                <button wire:click="addToFavorite({{ $item->id }})"
                                    title="Jadikan Transaksi Cepat"
                                    class="p-1.5 text-text-muted hover:text-amber-500 hover:bg-amber-50 rounded-lg transition-colors duration-150 cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach

            @empty
                {{-- Empty State --}}
                <tr>
                    <td colspan="5" class="px-6 py-20 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="w-16 h-16 bg-bg rounded-2xl flex items-center justify-center mb-4 ring-1 ring-inset ring-border">
                                <svg class="w-8 h-8 text-text-muted" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <p class="text-base font-semibold text-text tracking-tight">Tidak ada transaksi</p>
                            <p class="text-sm text-text-muted mt-1 max-w-sm">Coba sesuaikan filter Anda atau mulai tambahkan aktivitas keuangan baru.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
