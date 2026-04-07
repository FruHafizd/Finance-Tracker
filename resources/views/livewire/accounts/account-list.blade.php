<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-5">

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Rekening
        </h2>
    </x-slot>


    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Rekening</h1>
            <p class="text-xs text-slate-500 mt-1 font-medium">Kelola saldo dan transaksi antar rekening</p>
        </div>
        <button
            x-data
            x-on:click="$dispatch('open-account-form')"
            class="flex items-center justify-center gap-2 bg-slate-800 hover:bg-slate-700 text-white text-sm font-bold px-5 py-3 rounded-2xl shadow-lg shadow-slate-200 transition-all duration-200 active:scale-95">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Rekening
        </button>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">

        {{-- Total Saldo --}}
        <div class="bg-slate-800 rounded-2xl p-4 text-white relative overflow-hidden shadow-lg shadow-slate-200">
            <div class="absolute top-0 right-0 w-20 h-20 bg-white/5 rounded-full -mr-6 -mt-6"></div>
            <p class="text-xs font-medium text-slate-300">Total Saldo</p>
            <p class="text-xl font-bold mt-1 tracking-tight">
                Rp {{ number_format($this->summary['total'], 0, ',', '.') }}
            </p>
            @if($this->summary['netChange'] != 0)
                <div class="flex items-center gap-1 mt-2">
                    @if($this->summary['netChange'] > 0)
                        <svg class="w-3.5 h-3.5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L10 6.414l-3.293 3.293a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-xs text-emerald-400 font-medium">
                            +Rp {{ number_format(abs($this->summary['netChange']), 0, ',', '.') }}
                        </span>
                    @else
                        <svg class="w-3.5 h-3.5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L10 13.586l3.293-3.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-xs text-red-400 font-medium">
                            -Rp {{ number_format(abs($this->summary['netChange']), 0, ',', '.') }}
                        </span>
                    @endif
                    <span class="text-xs text-slate-400 underline underline-offset-2 decoration-slate-600/50">bulan ini</span>
                </div>
            @endif
        </div>

        {{-- Card per Tipe --}}
        @foreach(['tabungan' => 'Tabungan', 'ewallet' => 'E-Wallet', 'tunai' => 'Tunai'] as $typeKey => $typeLabel)
            <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm hover:shadow-md transition-shadow duration-200">
                <p class="text-xs text-slate-500 font-medium">{{ $typeLabel }}</p>
                <p class="text-base font-bold text-slate-800 mt-1">
                    Rp {{ number_format($this->summary[$typeKey], 0, ',', '.') }}
                </p>
                @php $change = $this->summary[$typeKey . '_change'] ?? 0; @endphp
                @if($change != 0)
                    <div class="flex items-center gap-1 mt-1.5">
                        @if($change > 0)
                            <svg class="w-3 h-3 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L10 6.414l-3.293 3.293a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-xs text-emerald-600 font-medium">
                                +{{ number_format(abs($change), 0, ',', '.') }}
                            </span>
                        @else
                            <svg class="w-3 h-3 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L10 13.586l3.293-3.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-xs text-red-500 font-medium">
                                -{{ number_format(abs($change), 0, ',', '.') }}
                            </span>
                        @endif
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-5 shadow-sm">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="relative flex-1">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Cari nama rekening..."
                    class="w-full pl-12 pr-4 py-3 rounded-xl border-slate-200 bg-slate-50 text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-800 focus:border-slate-800 transition-all duration-200" />
            </div>

            <div class="flex items-center gap-2 overflow-x-auto pb-1 md:pb-0 scrollbar-hide">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest mr-2 whitespace-nowrap">Urut:</span>
                <button wire:click="setSort('name')"
                    class="flex items-center gap-2 px-4 py-2.5 rounded-xl border text-xs font-bold transition-all duration-200 whitespace-nowrap
                        {{ $sortBy === 'name' ? 'border-slate-800 bg-slate-800 text-white shadow-md' : 'border-slate-200 bg-white text-slate-600 hover:border-slate-300' }}">
                    Nama
                    @if($sortBy === 'name') <span>{{ $sortDir === 'asc' ? '↑' : '↓' }}</span> @endif
                </button>
                <button wire:click="setSort('balance')"
                    class="flex items-center gap-2 px-4 py-2.5 rounded-xl border text-xs font-bold transition-all duration-200 whitespace-nowrap
                        {{ $sortBy === 'balance' ? 'border-slate-800 bg-slate-800 text-white shadow-md' : 'border-slate-200 bg-white text-slate-600 hover:border-slate-300' }}">
                    Saldo
                    @if($sortBy === 'balance') <span>{{ $sortDir === 'asc' ? '↑' : '↓' }}</span> @endif
                </button>
            </div>
        </div>
    </div>

    {{-- Filter Tab --}}
    <div class="flex gap-2 overflow-x-auto pb-1 scrollbar-hide">
        @foreach(['semua' => 'Semua', 'tabungan' => 'Tabungan', 'ewallet' => 'E-Wallet', 'tunai' => 'Tunai'] as $key => $label)
            <button
                wire:click="$set('activeTab', '{{ $key }}')"
                class="px-5 py-2 rounded-full text-sm font-medium transition-all duration-200 whitespace-nowrap
                    {{ $activeTab === $key
                        ? 'bg-slate-800 text-white shadow-sm ring-1 ring-slate-800'
                        : 'bg-white text-slate-500 border border-slate-200 hover:border-slate-300 hover:text-slate-700' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Account List --}}
    <div class="space-y-4">
        @forelse($this->accounts as $account)
            @php
                $percentage = $this->accountPercentages[$account->id] ?? 0;
                $isLowBalance = $account->balance <= \App\Livewire\Accounts\AccountList::LOW_BALANCE_THRESHOLD;
            @endphp

            <div class="bg-white rounded-2xl border {{ $isLowBalance ? 'border-red-200 ring-1 ring-red-100' : 'border-slate-200' }} shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
                <div class="p-4 sm:p-5">
                    <div class="flex items-center gap-4">
                        {{-- Icon --}}
                        <div
                            class="w-12 h-12 rounded-2xl flex items-center justify-center text-sm font-bold flex-shrink-0"
                            style="background-color: {{ $account->color ?? '#475569' }}15; color: {{ $account->color ?? '#475569' }}">
                            {{ strtoupper(substr($account->provider ?? $account->name, 0, 2)) }}
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <p class="font-bold text-slate-800 text-base leading-tight truncate">{{ $account->name }}</p>
                                <span class="text-[10px] uppercase tracking-wider px-2 py-0.5 rounded-md font-bold bg-slate-100 text-slate-500">
                                    {{ $account->type }}
                                </span>
                                @if($isLowBalance)
                                    <span class="text-[10px] uppercase tracking-wider px-2 py-0.5 rounded-md font-bold bg-red-50 text-red-600 animate-pulse flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        Saldo menipis
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-400 mt-1 font-medium">{{ $account->provider ?? 'Tunai' }}</p>
                        </div>

                        {{-- Saldo --}}
                        <div class="text-right flex-shrink-0">
                            <p class="font-bold {{ $isLowBalance ? 'text-red-600' : 'text-slate-800' }} text-lg leading-tight">
                                Rp {{ number_format($account->balance, 0, ',', '.') }}
                            </p>
                            <p class="text-[11px] text-slate-400 mt-1 font-medium">{{ $percentage }}% dari total saldo</p>
                        </div>

                        {{-- Actions Desktop --}}
                        <div class="hidden sm:flex items-center gap-1 ml-4" x-data>
                            <button
                                x-on:click="$dispatch('open-transfer', { fromAccountId: {{ $account->id }} })"
                                class="p-2.5 text-slate-400 hover:text-slate-800 hover:bg-slate-50 rounded-xl transition"
                                title="Transfer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                </svg>
                            </button>
                            <button
                                x-on:click="$dispatch('open-account-form', { id: {{ $account->id }} })"
                                class="p-2.5 text-slate-400 hover:text-slate-800 hover:bg-slate-50 rounded-xl transition"
                                title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            <button
                                wire:click="confirmDelete({{ $account->id }})"
                                class="p-2.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition"
                                title="Hapus">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Progress Bar --}}
                    @if($percentage > 0)
                        <div class="mt-4">
                            <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                <div
                                    class="h-full rounded-full transition-all duration-1000 ease-out {{ $isLowBalance ? 'bg-red-400' : 'bg-slate-400' }}"
                                    style="width: {{ min($percentage, 100) }}%">
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Mobile Actions --}}
                    <div class="flex sm:hidden items-center gap-2 mt-5 pt-4 border-t border-slate-50" x-data>
                        <button
                            x-on:click="$dispatch('open-transfer', { fromAccountId: {{ $account->id }} })"
                            class="flex-1 flex items-center justify-center gap-2 py-3 rounded-2xl bg-slate-800 text-white text-[11px] font-bold hover:bg-slate-700 transition shadow-md active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                            Transfer
                        </button>
                        <button
                            x-on:click="$dispatch('open-account-form', { id: {{ $account->id }} })"
                            class="flex-1 flex items-center justify-center gap-2 py-3 rounded-2xl bg-white text-slate-600 text-[11px] font-bold border border-slate-200 hover:bg-slate-50 transition active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </button>
                        <button
                            wire:click="confirmDelete({{ $account->id }})"
                            class="w-12 flex items-center justify-center py-3 rounded-2xl bg-red-50 text-red-500 border border-red-100 hover:bg-red-100 transition active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-3xl border border-dashed border-slate-300 p-16 text-center shadow-inner bg-slate-50/30">
                <div class="w-20 h-20 bg-white rounded-2xl shadow-sm border border-slate-100 flex items-center justify-center mx-auto mb-5 rotate-3 transition hover:rotate-0">
                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
                <h3 class="text-slate-800 font-bold text-lg">Belum ada rekening</h3>
                <p class="text-slate-400 text-sm mt-1 max-w-xs mx-auto">Mulai kelola keuanganmu dengan menambahkan rekening pertama hari ini.</p>
                <button
                    x-data x-on:click="$dispatch('open-account-form')"
                    class="mt-6 px-6 py-2.5 bg-slate-800 text-white rounded-xl font-bold text-sm hover:bg-slate-700 transition">
                    Buat Rekening Sekarang
                </button>
            </div>
        @endforelse
    </div>

    {{-- Modal Form --}}
    <livewire:accounts.account-form />

    <x-modal-delete
        name="modal-delete-rekening"
        title="Hapus Rekening"
        description="Apakah Anda yakin ingin menghapus rekening ini? Rekening hanya dapat dihapus jika tidak memiliki riwayat transaksi apa pun."
        action="delete"
    />

    {{-- Form Transaksi (untuk Quick Transfer) --}}
    <livewire:transactions.transaction-form />

</div>