<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Transaksi
        </h2>
    </x-slot>

    <div class="py-6 sm:py-10">

        <!-- Summary Cards (Fintech Style) -->
        <div class="px-4 sm:px-6 lg:px-8 mb-8">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 sm:gap-6">

                    <!-- TOTAL PEMASUKAN -->
                    <div class="bg-white rounded-xl p-4 ring-1 ring-inset ring-gray-100 shadow-sm flex items-center gap-4">
                        <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center flex-shrink-0 ring-1 ring-inset ring-emerald-600/10">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500">Pemasukan</p>
                            <p class="text-lg font-bold text-gray-900 mt-0.5">
                                Rp {{ number_format($summary['income'] ?? 0, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    <!-- TOTAL PENGELUARAN -->
                    <div class="bg-white rounded-xl p-4 ring-1 ring-inset ring-gray-100 shadow-sm flex items-center gap-4">
                        <div class="w-10 h-10 rounded-lg bg-rose-50 flex items-center justify-center flex-shrink-0 ring-1 ring-inset ring-rose-600/10">
                            <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500">Pengeluaran</p>
                            <p class="text-lg font-bold text-gray-900 mt-0.5">
                                Rp {{ number_format($summary['expense'] ?? 0, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    <!-- SALDO BERSIH -->
                    <div class="bg-white rounded-xl p-4 ring-1 ring-inset ring-gray-100 shadow-sm flex items-center gap-4">
                        <div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center flex-shrink-0 ring-1 ring-inset ring-indigo-600/10">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500">Saldo Bersih</p>
                            <p class="text-lg font-bold text-gray-900 mt-0.5">
                                {{ ($summary['difference'] ?? 0) < 0 ? '-' : '' }}Rp {{ number_format(abs($summary['difference'] ?? 0), 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl ring-1 ring-inset ring-gray-100">

                <!-- FILTER SECTION -->
                <div class="bg-white px-5 sm:px-7 py-6">
                    <div class="flex flex-col space-y-6">

                        <!-- Header Filter -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <h3 class="text-base font-bold text-gray-900 tracking-tight">Daftar Transaksi</h3>
                                <p class="text-[13px] font-medium text-gray-400 mt-1">Kelola dan pantau seluruh aktivitas keuangan Anda.</p>
                            </div>

                            <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap">
                                <button x-data x-on:click.prevent="$dispatch('open-modal', 'modal-export')"
                                    class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-white ring-1 ring-inset ring-gray-200 hover:ring-gray-300 hover:bg-gray-50 text-gray-700 text-[13px] font-bold rounded-xl transition-all duration-150 shadow-sm w-full sm:w-auto">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Export Data
                                </button>
                                <button x-data x-on:click.prevent="$dispatch('open-modal', 'modal-category')"
                                    class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-white ring-1 ring-inset ring-gray-200 hover:ring-gray-300 hover:bg-gray-50 text-gray-700 text-[13px] font-bold rounded-xl transition-all duration-150 shadow-sm w-full sm:w-auto">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                    Kategori
                                </button>
                                <button x-data x-on:click.prevent="$dispatch('open-modal', 'modal-create')"
                                    class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-[13px] font-bold rounded-xl transition-colors duration-150 shadow-sm ring-1 ring-inset ring-indigo-500 w-full sm:w-auto">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Tambah Baru
                                </button>
                            </div>
                        </div>

                        <!-- Divider -->
                        <div class="border-t border-gray-100"></div>

                        <!-- Filter Controls -->
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-7 gap-4 items-end bg-gray-50/50 p-4 rounded-xl ring-1 ring-inset ring-gray-100/50">

                            <!-- Filter Tahun -->
                            <div>
                                <label class="block text-[11px] font-bold text-gray-500 mb-1.5 uppercase tracking-wide">Tahun</label>
                                <select wire:model.live="filterYear"
                                    class="w-full rounded-xl border-0 ring-1 ring-inset ring-gray-200 bg-white px-3.5 py-2.5 text-[13px] font-medium text-gray-900 focus:ring-2 focus:ring-inset focus:ring-indigo-600 transition-shadow">
                                    <option value="">Semua</option>
                                    @for ($year = date('Y'); $year >= 2020; $year--)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>

                            <!-- Filter Bulan -->
                            <div>
                                <label class="block text-[11px] font-bold text-gray-500 mb-1.5 uppercase tracking-wide">Bulan</label>
                                <select wire:model.live="filterMonth"
                                    class="w-full rounded-xl border-0 ring-1 ring-inset ring-gray-200 bg-white px-3.5 py-2.5 text-[13px] font-medium text-gray-900 focus:ring-2 focus:ring-inset focus:ring-indigo-600 transition-shadow">
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

                            <!-- Filter Dari Tanggal -->
                            <div>
                                <label class="block text-[11px] font-bold text-gray-500 mb-1.5 uppercase tracking-wide">Dari</label>
                                <input type="date" wire:model.live="startDate"
                                    class="w-full rounded-xl border-0 ring-1 ring-inset ring-gray-200 bg-white px-3.5 py-2.5 text-[13px] font-medium text-gray-900 focus:ring-2 focus:ring-inset focus:ring-indigo-600 transition-shadow" />
                            </div>

                            <!-- Filter Sampai Tanggal -->
                            <div>
                                <label class="block text-[11px] font-bold text-gray-500 mb-1.5 uppercase tracking-wide">Sampai</label>
                                <input type="date" wire:model.live="endDate"
                                    class="w-full rounded-xl border-0 ring-1 ring-inset ring-gray-200 bg-white px-3.5 py-2.5 text-[13px] font-medium text-gray-900 focus:ring-2 focus:ring-inset focus:ring-indigo-600 transition-shadow" />
                            </div>

                            <!-- Filter Tipe -->
                            <div>
                                <label class="block text-[11px] font-bold text-gray-500 mb-1.5 uppercase tracking-wide">Tipe</label>
                                <select wire:model.live="filterType"
                                    class="w-full rounded-xl border-0 ring-1 ring-inset ring-gray-200 bg-white px-3.5 py-2.5 text-[13px] font-medium text-gray-900 focus:ring-2 focus:ring-inset focus:ring-indigo-600 transition-shadow">
                                    <option value="">Semua</option>
                                    <option value="income">Pemasukan</option>
                                    <option value="expense">Pengeluaran</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-gray-500 mb-1.5 uppercase tracking-wide">Kategori</label>
                                <select wire:model.live="filterCategory"
                                    class="w-full rounded-xl border-0 ring-1 ring-inset ring-gray-200 bg-white px-3.5 py-2.5 text-[13px] font-medium text-gray-900 focus:ring-2 focus:ring-inset focus:ring-indigo-600 transition-shadow">
                                    <option value="">Semua</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Tombol Reset -->
                            <div>
                                <label class="block text-[11px] font-bold text-transparent mb-1.5 uppercase tracking-wide select-none">-</label>
                                <button wire:click="resetFilters"
                                    class="w-full inline-flex items-center justify-center gap-1.5 px-3.5 py-2.5 bg-gray-200/50 hover:bg-gray-200 border-0 text-gray-600 hover:text-gray-900 rounded-xl text-[13px] font-bold transition-colors duration-150 ring-1 ring-inset ring-gray-500/10">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Reset
                                </button>
                            </div>

                        </div>
                        @if($startDate || $endDate)
                            <div class="flex items-center gap-2 mt-2 px-1">
                                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-700 bg-indigo-50 ring-1 ring-inset ring-indigo-600/20 px-3 py-1 rounded-lg">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d M Y') : '...' }}
                                    –
                                    {{ $endDate ? \Carbon\Carbon::parse($endDate)->format('d M Y') : '...' }}
                                </span>
                                <span class="text-[11px] font-semibold text-gray-400">Filter cepat dinonaktifkan sementara</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Table Desktop --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50/80">
                            <tr>
                                <th scope="col" class="px-5 lg:px-7 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">Nama Transaksi</th>
                                <th scope="col" class="px-5 lg:px-7 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-widest w-32">Tipe</th>
                                <th scope="col" class="px-5 lg:px-7 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-widest w-40">Kategori</th>
                                <th scope="col" class="px-5 lg:px-7 py-3.5 text-right text-xs font-bold text-gray-500 uppercase tracking-widest w-44">Jumlah</th>
                                <th scope="col" class="relative px-5 lg:px-7 py-3.5 w-28"><span class="sr-only">Aksi</span></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
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
                                <tr class="bg-gray-50/50 border-t border-gray-100">
                                    <td colspan="5" class="px-5 lg:px-7 py-2.5">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-2.5">
                                                <div class="w-1.5 h-1.5 rounded-full bg-indigo-400"></div>
                                                <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">{{ $label }}</span>
                                            </div>
                                            <span class="text-xs font-medium text-gray-400">{{ $items->count() }} transaksi</span>
                                        </div>
                                    </td>
                                </tr>

                                {{-- Transaction Rows --}}
                                @foreach ($items as $item)
                                    <tr class="hover:bg-gray-50/80 transition-colors duration-150 group">
                                        
                                        <td class="px-5 lg:px-7 py-4 text-sm text-gray-900 max-w-sm">
                                            <span class="line-clamp-2 font-medium">{{ $item->name }}</span>
                                        </td>
                                        <td class="px-5 lg:px-7 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 inline-flex text-xs font-bold rounded-lg tracking-wide
                                                {{ $item->type === 'income' ? 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20' : 'bg-rose-50 text-rose-700 ring-1 ring-inset ring-rose-600/20' }}">
                                                {{ $item->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                                            </span>
                                        </td>
                                        <td class="px-5 lg:px-7 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 inline-flex text-xs font-bold rounded-lg tracking-wide shadow-sm"
                                                style="background-color: {{ $item->category->color }}15; color: {{ $item->category->color }}; ring: 1px inset {{ $item->category->color }}30;">
                                                <span class="w-1.5 h-1.5 rounded-full mr-1.5 mt-1" style="background-color: {{ $item->category->color }}"></span>
                                                {{ $item->category->name }}
                                            </span>
                                        </td>
                                        <td class="px-5 lg:px-7 py-4 whitespace-nowrap text-right text-sm font-bold tracking-tight
                                            {{ $item->type === 'income' ? 'text-emerald-600' : 'text-red-500' }}">
                                            {{ $item->type === 'income' ? '+' : '-' }}Rp {{ number_format($item->amount, 0, ',', '.') }}
                                        </td>
                                        <td class="px-5 lg:px-7 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end gap-1 opacity-100 lg:opacity-0 lg:group-hover:opacity-100 transition-opacity duration-200">
                                                <button x-data
                                                    x-on:click.prevent="
                                                        $dispatch('edit-transaction', { id: {{ $item->id }} });
                                                        $dispatch('open-modal', 'modal-edit');
                                                    "
                                                    title="Edit"
                                                    class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors duration-150">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </button>
                                                <button x-data
                                                    x-on:click.prevent="
                                                        $dispatch('confirm-delete', { id: {{ $item->id }} });
                                                        $dispatch('open-modal', 'modal-delete');
                                                    "
                                                    title="Hapus"
                                                    class="p-1.5 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors duration-150">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-20 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mb-4 ring-1 ring-inset ring-gray-100">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            <p class="text-base font-semibold text-gray-900 tracking-tight">Tidak ada transaksi</p>
                                            <p class="text-sm text-gray-500 mt-1 max-w-sm">Coba sesuaikan filter Anda atau mulai tambahkan aktivitas keuangan baru.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Card View --}}
                <div class="md:hidden divide-y divide-gray-100">
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
                        <div class="sticky top-0 z-10 flex items-center justify-between px-5 py-2.5 bg-gray-50/95 backdrop-blur-sm border-t border-b border-gray-100 shadow-sm">
                            <div class="flex items-center gap-2">
                                <div class="w-1.5 h-1.5 rounded-full bg-indigo-400"></div>
                                <span class="text-xs font-bold text-gray-700 uppercase tracking-wider">{{ $label }}</span>
                            </div>
                            <span class="text-[11px] font-bold text-gray-400 bg-white px-2 py-0.5 rounded-md ring-1 ring-inset ring-gray-200">{{ $items->count() }} item</span>
                        </div>

                        {{-- Mobile Cards --}}
                        <div class="divide-y divide-gray-50 bg-white">
                        @foreach ($items as $item)
                            <div class="p-5 hover:bg-gray-50/50 transition-colors duration-150 active:bg-gray-50">
                                <div class="flex justify-between items-start gap-4 mb-3">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-gray-900 leading-tight mb-1">{{ $item->name }}</p>
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="text-[11px] font-medium text-gray-400 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                {{ $item->date->format('H:i') }}
                                            </span>
                                            <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                                            <span class="px-2 py-0.5 text-[10px] font-bold rounded-md ring-1 ring-inset shadow-sm"
                                                style="background-color: {{ $item->category->color }}15; color: {{ $item->category->color }}; ring-color: {{ $item->category->color }}30;">
                                                {{ $item->category->name }}
                                            </span>
                                        </div>
                                    </div>
                                    <span class="px-2.5 py-1 flex-shrink-0 text-[10px] font-bold rounded-md tracking-wider uppercase ring-1 ring-inset
                                        {{ $item->type === 'income' ? 'bg-emerald-50 text-emerald-700 ring-emerald-600/20' : 'bg-rose-50 text-rose-700 ring-rose-600/20' }}">
                                        {{ $item->type === 'income' ? 'IN' : 'OUT' }}
                                    </span>
                                </div>
                                <div class="flex items-end justify-between mt-1">
                                    <p class="text-[17px] font-black tracking-tight {{ $item->type === 'income' ? 'text-emerald-600' : 'text-gray-900' }}">
                                        {{ $item->type === 'income' ? '+' : '-' }}Rp {{ number_format($item->amount, 0, ',', '.') }}
                                    </p>
                                    <div class="flex items-center gap-1.5">
                                        <button x-data
                                            x-on:click.prevent="
                                                $dispatch('edit-transaction', { id: {{ $item->id }} });
                                                $dispatch('open-modal', 'modal-edit');
                                            "
                                            class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl ring-1 ring-inset ring-gray-100 transition-all duration-150 active:scale-95 bg-white">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button x-data
                                            x-on:click.prevent="
                                                $dispatch('confirm-delete', { id: {{ $item->id }} });
                                                $dispatch('open-modal', 'modal-delete');
                                            "
                                            class="p-2 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl ring-1 ring-inset ring-gray-100 transition-all duration-150 active:scale-95 bg-white">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        </div>

                    @empty
                        <div class="py-20 text-center px-4">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mb-4 ring-1 ring-inset ring-gray-100 shadow-sm">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <p class="text-base font-bold text-gray-900 tracking-tight">Belum ada aktivitas</p>
                                <p class="text-[13px] text-gray-500 mt-1 max-w-[250px]">Catat pemasukan atau pengeluaran pertama Anda hari ini.</p>
                                <button x-data x-on:click.prevent="$dispatch('open-modal', 'modal-create')"
                                        class="mt-6 inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-indigo-600 text-white text-[13px] font-bold rounded-xl shadow-sm ring-1 ring-inset ring-indigo-500 hover:bg-indigo-700 active:bg-indigo-800 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                    Tambah Transaksi
                                </button>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if ($transactions->hasPages())
                    <div class="bg-gray-50/50 px-5 py-4 border-t border-gray-100 sm:px-7 rounded-b-2xl">
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">

                            <p class="text-[13px] font-medium text-gray-500 order-2 sm:order-1">
                                Menampilkan
                                <span class="font-bold text-gray-900">{{ $transactions->firstItem() }}</span> hingga <span class="font-bold text-gray-900">{{ $transactions->lastItem() }}</span>
                                dari <span class="font-bold text-gray-900">{{ $transactions->total() }}</span> entri
                            </p>

                            <nav class="inline-flex rounded-xl shadow-sm ring-1 ring-inset ring-gray-200 overflow-hidden order-1 sm:order-2 bg-white" aria-label="Pagination">
                                <!-- Previous -->
                                <button wire:click='previousPage' wire:loading.attr='disabled'
                                    @if ($transactions->onFirstPage()) disabled @endif
                                    class="relative inline-flex items-center px-3 py-2 text-sm border-r border-gray-200 transition-colors duration-150
                                        {{ $transactions->onFirstPage() ? 'bg-gray-50 text-gray-300 cursor-not-allowed' : 'bg-white text-gray-500 hover:bg-gray-50 hover:text-gray-700 active:bg-gray-100' }}">
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>

                                <div class="hidden sm:flex">
                                    @php
                                        $currentPage = $transactions->currentPage();
                                        $lastPage    = $transactions->lastPage();
                                        $onEachSide  = 2;

                                        $start = max(1, $currentPage - $onEachSide);
                                        $end   = min($lastPage, $currentPage + $onEachSide);

                                        $showStartDots = $start > 2;
                                        $showEndDots   = $end < $lastPage - 1;
                                    @endphp

                                    {{-- Halaman pertama --}}
                                    @if ($start > 1)
                                        <button wire:click='gotoPage(1)'
                                            class="relative inline-flex items-center px-4 py-2 text-[13px] font-bold border-r border-gray-200 transition-colors duration-150
                                                {{ 1 === $currentPage ? 'bg-indigo-50 text-indigo-600' : 'bg-white text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                            1
                                        </button>
                                        @if ($showStartDots)
                                            <span class="relative inline-flex items-center px-3 py-2 text-[13px] font-bold text-gray-400 border-r border-gray-200 bg-gray-50/50">
                                                ...
                                            </span>
                                        @endif
                                    @endif

                                    {{-- Window tengah --}}
                                    @for ($page = $start; $page <= $end; $page++)
                                        <button wire:click='gotoPage({{ $page }})'
                                            class="relative inline-flex items-center px-4 py-2 text-[13px] font-bold border-r border-gray-200 transition-colors duration-150
                                                {{ $page === $currentPage ? 'bg-indigo-50 text-indigo-600 shadow-[inset_0_-2px_0_0_rgba(79,70,229,1)]' : 'bg-white text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                            {{ $page }}
                                        </button>
                                    @endfor

                                    {{-- Halaman terakhir --}}
                                    @if ($end < $lastPage)
                                        @if ($showEndDots)
                                            <span class="relative inline-flex items-center px-3 py-2 text-[13px] font-bold text-gray-400 border-r border-gray-200 bg-gray-50/50">
                                                ...
                                            </span>
                                        @endif
                                        <button wire:click='gotoPage({{ $lastPage }})'
                                            class="relative inline-flex items-center px-4 py-2 text-[13px] font-bold border-r border-gray-200 transition-colors duration-150
                                                {{ $lastPage === $currentPage ? 'bg-indigo-50 text-indigo-600 shadow-[inset_0_-2px_0_0_rgba(79,70,229,1)]' : 'bg-white text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                            {{ $lastPage }}
                                        </button>
                                    @endif
                                </div>

                                <!-- Current Page (Mobile) -->
                                <div class="sm:hidden inline-flex items-center px-4 py-2 bg-gray-50 text-[13px] font-bold text-gray-600 border-r border-gray-200">
                                    {{ $transactions->currentPage() }} / {{ $transactions->lastPage() }}
                                </div>

                                <!-- Next -->
                                <button wire:click='nextPage' wire:loading.attr='disabled'
                                    @if (!$transactions->hasMorePages()) disabled @endif
                                    class="relative inline-flex items-center px-3 py-2 text-sm transition-colors duration-150
                                        {{ (!$transactions->hasMorePages()) ? 'bg-gray-50 text-gray-300 cursor-not-allowed' : 'bg-white text-gray-500 hover:bg-gray-50 hover:text-gray-700 active:bg-gray-100' }}">
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </nav>

                        </div>
                    </div>
                @endif

            </div>
        </div>

        <livewire:transactions.create />
        <livewire:transactions.edit />
        <livewire:transactions.delete />
        <livewire:transactions.category />
        <livewire:transactions.export />

    </div>
</div>
