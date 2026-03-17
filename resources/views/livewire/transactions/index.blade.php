<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Riwayat Transaksi
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">

        <!-- Summary Cards -->
        <div class="py-4 sm:py-8 flex justify-center px-4">
            <div class="max-w-6xl w-full">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">

                    <!-- TOTAL PEMASUKAN -->
                    <div
                        class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-xl p-4 sm:p-5">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <p class="text-xs sm:text-sm text-green-700 font-medium mb-1">Total Pemasukan</p>
                                <p class="text-lg sm:text-2xl font-bold text-green-800 truncate">
                                    Rp {{ number_format($summary['income'] ?? 0, 0, ',', '.') }}
                                </p>
                            </div>
                            <div
                                class="w-10 h-10 sm:w-12 sm:h-12 bg-green-200 rounded-full flex items-center justify-center flex-shrink-0 ml-2">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-700" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- TOTAL PENGELUARAN -->
                    <div class="bg-gradient-to-br from-red-50 to-red-100 border border-red-200 rounded-xl p-4 sm:p-5">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <p class="text-xs sm:text-sm text-red-700 font-medium mb-1">Total Pengeluaran</p>
                                <p class="text-lg sm:text-2xl font-bold text-red-800 truncate">
                                    Rp {{ number_format($summary['expense'] ?? 0, 0, ',', '.') }}
                                </p>
                            </div>
                            <div
                                class="w-10 h-10 sm:w-12 sm:h-12 bg-red-200 rounded-full flex items-center justify-center flex-shrink-0 ml-2">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-red-700" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- SALDO BERSIH -->
                    <div
                        class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-4 sm:p-5 sm:col-span-2 lg:col-span-1">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <p class="text-xs sm:text-sm text-blue-700 font-medium mb-1">Saldo Bersih</p>
                                <p class="text-lg sm:text-2xl font-bold text-blue-800 truncate">
                                    Rp {{ number_format($summary['difference'] ?? 0, 0, ',', '.') }}
                                </p>
                            </div>
                            <div
                                class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-200 rounded-full flex items-center justify-center flex-shrink-0 ml-2">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-700" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <!-- FILTER SECTION -->
                <div
                    class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200 px-4 sm:px-6 py-4 sm:py-5">
                    <div class="flex flex-col space-y-3">
                        <!-- Header Filter -->
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm sm:text-base font-semibold text-gray-700 flex items-center">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 text-gray-600" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                                Filter Transaksi
                            </h3>
                            <div class="flex items-end gap-2">
                                <button x-data x-on:click.prevent="$dispatch('open-modal', 'modal-create');"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                                    + Tambah Catatan
                                </button>

                                <button x-data x-on:click.prevent="$dispatch('open-modal', 'modal-category')"
                                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm">
                                    + Kategori
                                </button>
                            </div>
                        </div>

                        <!-- Filter Controls -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                            <!-- Filter Tahun -->
                            <div>
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1.5">Tahun</label>
                                <select wire:model.live="filterYear"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                    <option value="">Semua Tahun</option>
                                    @for ($year = date('Y'); $year >= 2020; $year--)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>

                            <!-- Filter Bulan -->
                            <div>
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1.5">Bulan</label>
                                <select wire:model.live="filterMonth"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                    <option value="">Semua Bulan</option>
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

                            <!-- Filter Tipe -->
                            <div>
                                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1.5">Tipe
                                    Transaksi</label>
                                <select wire:model.live="filterType"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                    <option value="">Semua Tipe</option>
                                    <option value="income">Pemasukan</option>
                                    <option value="expense">Pengeluaran</option>
                                </select>
                            </div>

                            <!-- Tombol Reset -->
                            <div class="flex items-end">
                                <button wire:click="resetFilters"
                                    class="w-full px-4 py-2 bg-gradient-to-r from-gray-600 to-gray-700 text-white rounded-lg hover:from-gray-700 hover:to-gray-800 transition-all duration-150 text-xs sm:text-sm font-medium shadow-sm flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
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
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tipe
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kategori
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kategori
                                </th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Jumlah
                                </th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($transactions as $item)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $item->date->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $item['name'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item['type'] === 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $item['type'] === 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                            style="background-color: {{ $item->category->color }}20; color: {{ $item->category->color }}">
                                                {{ $item->category->name }}
                                        </span>
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium {{ $item['type'] === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $item['type'] === 'income' ? '+' : '-' }}
                                        Rp {{ number_format($item['amount'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <div class="flex items-center justify-center gap-2">
                                            <button x-data
                                                x-on:click.prevent="
                                                            $dispatch('edit-transaction', { id: {{ $item->id }} });
                                                            $dispatch('open-modal', 'modal-edit');
                                                        "
                                                class="inline-flex items-center px-3 py-1.5 text-blue-600 hover:text-blue-800 hover:bg-blue-50 text-xs font-medium rounded-md transition-colors duration-150">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Edit
                                            </button>
                                            <button x-data
                                                x-on:click.prevent="
                                                            $dispatch('confirm-delete', { id: {{ $item->id }} });
                                                            $dispatch('open-modal', 'modal-delete');
                                                        "
                                                class="inline-flex items-center px-3 py-1.5 text-red-600 hover:text-red-800 hover:bg-red-50 text-xs font-medium rounded-md transition-colors duration-150">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-16 h-16 text-gray-300 mb-3" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <p class="text-base font-medium text-gray-700">Tidak ada transaksi
                                                ditemukan</p>
                                            <p class="text-sm text-gray-400 mt-1">Coba ubah filter atau tambahkan
                                                transaksi baru</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="md:hidden divide-y divide-gray-200">
                    @forelse ($transactions as $item)
                        <div class="p-4 hover:bg-gray-50 transition-colors duration-150">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h4 class="text-sm font-semibold text-gray-900 mb-1">{{ $item['description'] }}
                                    </h4>
                                    <p class="text-xs text-gray-500"> {{ $item->date->format('d M Y') }}</p>
                                </div>
                                <span
                                    class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item['type'] === 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $item['type'] === 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between">
                                <div
                                    class="text-base font-bold {{ $item['type'] === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $item['type'] === 'income' ? '+' : '-' }}
                                    Rp {{ number_format($item['amount'], 0, ',', '.') }}
                                </div>
                                <div class="flex gap-2">
                                    <button x-data
                                        x-on:click.prevent="
                                                    $dispatch('edit-transaction', { id: {{ $item->id }} });
                                                    $dispatch('open-modal', 'modal-edit');
                                                "
                                        class="p-2 text-blue-600 hover:bg-blue-50 rounded-md transition-colors duration-150">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button x-data
                                        x-on:click.prevent="
                                                    $dispatch('confirm-delete', { id: {{ $item->id }} });
                                                    $dispatch('open-modal', 'modal-delete');
                                                "
                                        class="p-2 text-red-600 hover:bg-red-50 rounded-md transition-colors duration-150">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <svg class="w-16 h-16 text-gray-300 mb-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="text-base font-medium text-gray-700">Tidak ada transaksi ditemukan</p>
                                <p class="text-sm text-gray-400 mt-1">Coba ubah filter atau tambahkan transaksi baru
                                </p>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if ($transactions->hasPages())
                    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                            <!-- Showing Info -->
                            <div class="text-xs sm:text-sm text-gray-700 text-center sm:text-left">
                                Menampilkan
                                <span class="font-medium">{{ $transactions->firstItem() }}</span> sampai
                                <span class="font-medium">{{ $transactions->lastItem() }}</span> dari
                                <span class="font-medium">{{ $transactions->total() }}</span> transaksi
                            </div>

                            <!-- Pagination Buttons -->
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                                aria-label="Pagination">
                                <!-- Previous Button -->
                                <button wire:click='previousPage' wire:loading.attr='disabled'
                                    @if ($transactions->onFirstPage()) disabled @endif
                                    class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 text-sm font-medium
                            {{ $transactions->onFirstPage()
                                ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
                                : 'bg-white text-gray-500 hover:bg-gray-50' }}">
                                    <span class="sr-only">Previous</span>
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>

                                <!-- Page Numbers - Responsive -->
                                <div class="hidden sm:flex">
                                    @foreach ($transactions->getUrlRange(1, $transactions->lastPage()) as $page => $url)
                                        <button wire:click='gotoPage({{ $page }})'
                                            class="relative inline-flex items-center px-4 py-2 border text-sm font-medium
                                    {{ $page === $transactions->currentPage()
                                        ? 'bg-blue-50 border-blue-500 text-blue-600 z-10'
                                        : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50' }}">
                                            {{ $page }}
                                        </button>
                                    @endforeach
                                </div>

                                <!-- Current Page Indicator for Mobile -->
                                <div
                                    class="sm:hidden relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                                    {{ $transactions->currentPage() }} / {{ $transactions->lastPage() }}
                                </div>

                                <!-- Next Button -->
                                <button wire:click='nextPage' wire:loading.attr='disabled'
                                    @if (!$transactions->hasMorePages()) disabled @endif
                                    class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 text-sm font-medium
                            {{ !$transactions->hasMorePages()
                                ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
                                : 'bg-white text-gray-500 hover:bg-gray-50' }}">
                                    <span class="sr-only">Next</span>
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                            clip-rule="evenodd" />
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
