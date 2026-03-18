<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Riwayat Transaksi
        </h2>
    </x-slot>

    <div class="py-6 sm:py-10">

        <!-- Summary Cards -->
        <div class="px-4 sm:px-6 lg:px-8 mb-6">
            <div class="max-w-6xl mx-auto">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                    <!-- TOTAL PEMASUKAN -->
                    <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-xl p-4 sm:p-5">
                        <div class="flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-xs font-medium text-green-700 mb-1 uppercase tracking-wide">Total Pemasukan</p>
                                <p class="text-xl sm:text-2xl font-bold text-green-800 truncate">
                                    Rp {{ number_format($summary['income'] ?? 0, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-200 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- TOTAL PENGELUARAN -->
                    <div class="bg-gradient-to-br from-red-50 to-red-100 border border-red-200 rounded-xl p-4 sm:p-5">
                        <div class="flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-xs font-medium text-red-700 mb-1 uppercase tracking-wide">Total Pengeluaran</p>
                                <p class="text-xl sm:text-2xl font-bold text-red-800 truncate">
                                    Rp {{ number_format($summary['expense'] ?? 0, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-red-200 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- SALDO BERSIH -->
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-4 sm:p-5">
                        <div class="flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-xs font-medium text-blue-700 mb-1 uppercase tracking-wide">Saldo Bersih</p>
                                <p class="text-xl sm:text-2xl font-bold text-blue-800 truncate">
                                    Rp {{ number_format($summary['difference'] ?? 0, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-200 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">

                <!-- FILTER SECTION -->
                <div class="bg-white border-b border-gray-100 px-4 sm:px-6 py-5">
                    <div class="flex flex-col space-y-4">

                        <!-- Header Filter -->
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                    </svg>
                                </div>
                                <h3 class="text-sm font-semibold text-gray-800">Filter Transaksi</h3>
                            </div>

                            <div class="flex items-center gap-2 flex-wrap">
                                <button x-data x-on:click.prevent="$dispatch('open-modal', 'modal-create')"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-lg transition-colors duration-150 shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Tambah Catatan
                                </button>
                                <button x-data x-on:click.prevent="$dispatch('open-modal', 'modal-category')"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-medium rounded-lg transition-colors duration-150 shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Kategori
                                </button>
                            </div>
                        </div>

                        <!-- Divider -->
                        <div class="border-t border-gray-100"></div>

                        <!-- Filter Controls -->
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-7 gap-3 items-end">

                            <!-- Filter Tahun -->
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wide">Tahun</label>
                                <select wire:model.live="filterYear"
                                    class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 focus:bg-white transition">
                                    <option value="">Semua</option>
                                    @for ($year = date('Y'); $year >= 2020; $year--)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>

                            <!-- Filter Bulan -->
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wide">Bulan</label>
                                <select wire:model.live="filterMonth"
                                    class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 focus:bg-white transition">
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
                                <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wide">Dari</label>
                                <input type="date" wire:model.live="startDate"
                                    class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 focus:bg-white transition" />
                            </div>

                            <!-- Filter Sampai Tanggal -->
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wide">Sampai</label>
                                <input type="date" wire:model.live="endDate"
                                    class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 focus:bg-white transition" />
                            </div>

                            <!-- Filter Tipe -->
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wide">Tipe</label>
                                <select wire:model.live="filterType"
                                    class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 focus:bg-white transition">
                                    <option value="">Semua</option>
                                    <option value="income">Pemasukan</option>
                                    <option value="expense">Pengeluaran</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wide">Kategori</label>
                                <select wire:model.live="filterCategory"
                                    class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 focus:bg-white transition">
                                    <option value="">Semua</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Tombol Reset -->
                            <div>
                                <label class="block text-xs font-medium text-transparent mb-1.5 uppercase tracking-wide select-none">-</label>
                                <button wire:click="resetFilters"
                                    class="w-full inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 hover:text-gray-800 rounded-lg text-xs font-medium transition-colors duration-150 border border-gray-200">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Reset Filter
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Table Desktop -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-28">
                                    Tanggal
                                </th>
                                <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama
                                </th>
                                <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                                    Tipe
                                </th>
                                <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-36">
                                    Kategori
                                </th>
                                <th class="px-4 lg:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-40">
                                    Jumlah
                                </th>
                                <th class="px-4 lg:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($transactions as $item)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $item->date->format('d M Y') }}
                                    </td>
                                    <td class="px-4 lg:px-6 py-4 text-sm text-gray-900 max-w-xs">
                                        <span class="line-clamp-2">{{ $item['name'] }}</span>
                                    </td>
                                    <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                        <span class="px-2.5 py-1 inline-flex text-xs font-semibold rounded-full
                                            {{ $item['type'] === 'income' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $item['type'] === 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                                        </span>
                                    </td>
                                    <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                        <span class="px-2.5 py-1 inline-flex text-xs font-semibold rounded-full"
                                            style="background-color: {{ $item->category->color }}20; color: {{ $item->category->color }}">
                                            {{ $item->category->name }}
                                        </span>
                                    </td>
                                    <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-right text-sm font-semibold
                                        {{ $item['type'] === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $item['type'] === 'income' ? '+' : '-' }}Rp {{ number_format($item['amount'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center gap-1">
                                            <button x-data
                                                x-on:click.prevent="
                                                    $dispatch('edit-transaction', { id: {{ $item->id }} });
                                                    $dispatch('open-modal', 'modal-edit');
                                                "
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 text-blue-600 hover:text-blue-800 hover:bg-blue-50 text-xs font-medium rounded-md transition-colors duration-150">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Edit
                                            </button>
                                            <button x-data
                                                x-on:click.prevent="
                                                    $dispatch('confirm-delete', { id: {{ $item->id }} });
                                                    $dispatch('open-modal', 'modal-delete');
                                                "
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 text-red-500 hover:text-red-700 hover:bg-red-50 text-xs font-medium rounded-md transition-colors duration-150">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-14 h-14 text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <p class="text-sm font-medium text-gray-600">Tidak ada transaksi ditemukan</p>
                                            <p class="text-xs text-gray-400 mt-1">Coba ubah filter atau tambahkan transaksi baru</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="md:hidden divide-y divide-gray-100">
                    @forelse ($transactions as $item)
                        <div class="p-4 hover:bg-gray-50 transition-colors duration-150">
                            <div class="flex items-start justify-between gap-2 mb-2">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $item['name'] }}</p>
                                    <div class="flex items-center gap-2 mt-1 flex-wrap">
                                        <p class="text-xs text-gray-400">{{ $item->date->format('d M Y') }}</p>
                                        <span class="px-2 py-0.5 text-xs font-medium rounded-full"
                                            style="background-color: {{ $item->category->color }}20; color: {{ $item->category->color }}">
                                            {{ $item->category->name }}
                                        </span>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 flex-shrink-0 text-xs font-semibold rounded-full
                                    {{ $item['type'] === 'income' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $item['type'] === 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between mt-3">
                                <p class="text-base font-bold {{ $item['type'] === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $item['type'] === 'income' ? '+' : '-' }}Rp {{ number_format($item['amount'], 0, ',', '.') }}
                                </p>
                                <div class="flex gap-1">
                                    <button x-data
                                        x-on:click.prevent="
                                            $dispatch('edit-transaction', { id: {{ $item->id }} });
                                            $dispatch('open-modal', 'modal-edit');
                                        "
                                        class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-150">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button x-data
                                        x-on:click.prevent="
                                            $dispatch('confirm-delete', { id: {{ $item->id }} });
                                            $dispatch('open-modal', 'modal-delete');
                                        "
                                        class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors duration-150">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-16 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-14 h-14 text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="text-sm font-medium text-gray-600">Tidak ada transaksi ditemukan</p>
                                <p class="text-xs text-gray-400 mt-1">Coba ubah filter atau tambahkan transaksi baru</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if ($transactions->hasPages())
                    <div class="bg-white px-4 py-4 border-t border-gray-100 sm:px-6">
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">

                            <p class="text-xs text-gray-500 order-2 sm:order-1">
                                Menampilkan
                                <span class="font-medium text-gray-700">{{ $transactions->firstItem() }}</span>–<span class="font-medium text-gray-700">{{ $transactions->lastItem() }}</span>
                                dari <span class="font-medium text-gray-700">{{ $transactions->total() }}</span> transaksi
                            </p>

                            <nav class="inline-flex rounded-lg shadow-sm border border-gray-200 overflow-hidden order-1 sm:order-2" aria-label="Pagination">
                                <!-- Previous -->
                                <button wire:click='previousPage' wire:loading.attr='disabled'
                                    @if ($transactions->onFirstPage()) disabled @endif
                                    class="relative inline-flex items-center px-3 py-2 text-sm border-r border-gray-200
                                        {{ $transactions->onFirstPage() ? 'bg-gray-50 text-gray-300 cursor-not-allowed' : 'bg-white text-gray-500 hover:bg-gray-50' }}">
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>

                                <!-- Page Numbers (Desktop) -->
                                <div class="hidden sm:flex">
                                    @foreach ($transactions->getUrlRange(1, $transactions->lastPage()) as $page => $url)
                                        <button wire:click='gotoPage({{ $page }})'
                                            class="relative inline-flex items-center px-4 py-2 text-sm font-medium border-r border-gray-200
                                                {{ $page === $transactions->currentPage()
                                                    ? 'bg-blue-50 text-blue-600 font-semibold'
                                                    : 'bg-white text-gray-600 hover:bg-gray-50' }}">
                                            {{ $page }}
                                        </button>
                                    @endforeach
                                </div>

                                <!-- Current Page (Mobile) -->
                                <div class="sm:hidden inline-flex items-center px-4 py-2 bg-white text-sm font-medium text-gray-600 border-r border-gray-200">
                                    {{ $transactions->currentPage() }} / {{ $transactions->lastPage() }}
                                </div>

                                <!-- Next -->
                                <button wire:click='nextPage' wire:loading.attr='disabled'
                                    @if (!$transactions->hasMorePages()) disabled @endif
                                    class="relative inline-flex items-center px-3 py-2 text-sm
                                        {{ !$transactions->hasMorePages() ? 'bg-gray-50 text-gray-300 cursor-not-allowed' : 'bg-white text-gray-500 hover:bg-gray-50' }}">
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

    </div>
</div>
