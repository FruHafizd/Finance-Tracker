<div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10 space-y-6">

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-text leading-tight">
            Rekening
        </h2>
    </x-slot>


    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-text tracking-tight">Rekening</h1>
            <p class="text-xs text-text-muted mt-1 font-medium">Kelola saldo dan transaksi antar rekening</p>
        </div>
        <button
            x-data
            x-on:click="$dispatch('open-account-form')"
            class="flex items-center justify-center gap-2 bg-primary hover:bg-primary-hover text-white text-sm font-bold px-5 py-3 rounded-2xl shadow-lg shadow-primary/10 ring-1 ring-inset ring-primary/20 transition-all duration-200 active:scale-95">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Rekening
        </button>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">

        {{-- Total Saldo --}}
        <div class="bg-primary rounded-2xl p-4 sm:p-5 text-white relative overflow-hidden shadow-lg shadow-primary/20 ring-1 ring-inset ring-primary/20">
            <div class="absolute top-0 right-0 w-20 h-20 bg-white/5 rounded-full -mr-6 -mt-6"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-xs font-medium text-white/80">Total Saldo</p>
                    <p class="text-xl font-bold mt-1 tracking-tight">
                        Rp {{ number_format($this->summary['total'], 0, ',', '.') }}
                    </p>
                </div>
                <div class="text-white bg-white/15 p-2 rounded-xl flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h14a2 2 0 002-2v-6zM3 10V6a2 2 0 012-2h11a2 2 0 012 2v4" />
                    </svg>
                </div>
            </div>
            @if(($this->summary['netChange'] ?? 0) > 0)
                <div class="inline-flex items-center gap-1.5 mt-3 px-2 py-0.5 rounded-lg bg-white/10 relative z-10">
                    <svg class="w-3 h-3 text-emerald-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L10 6.414l-3.293 3.293a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-[11px] text-emerald-300 font-bold">
                        +Rp {{ number_format(abs($this->summary['netChange']), 0, ',', '.') }}
                    </span>
                    <span class="text-[10px] text-white/70">bulan ini</span>
                </div>
            @elseif(($this->summary['netChange'] ?? 0) < 0)
                <div class="inline-flex items-center gap-1.5 mt-3 px-2 py-0.5 rounded-lg bg-white/10 relative z-10">
                    <svg class="w-3 h-3 text-rose-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L10 13.586l3.293-3.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-[11px] text-rose-300 font-bold">
                        -Rp {{ number_format(abs($this->summary['netChange']), 0, ',', '.') }}
                    </span>
                    <span class="text-[10px] text-white/70">bulan ini</span>
                </div>
            @else
                <div class="inline-flex items-center gap-1.5 mt-3 px-2 py-0.5 rounded-lg bg-white/5 relative z-10">
                    <span class="text-[10px] text-white/70 font-semibold">
                        — Tidak berubah bulan ini
                    </span>
                </div>
            @endif
        </div>

        {{-- Card per Tipe --}}
        @foreach(['tabungan' => 'Tabungan', 'ewallet' => 'E-Wallet', 'tunai' => 'Tunai'] as $typeKey => $typeLabel)
            <div class="bg-bg-sidebar rounded-2xl p-4 sm:p-5 border border-border shadow-sm hover:shadow-md transition-shadow duration-200 flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs text-text-muted font-medium">{{ $typeLabel }}</p>
                            <p class="text-base font-bold text-text mt-1">
                                Rp {{ number_format($this->summary[$typeKey], 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="text-primary bg-primary-light p-1.5 rounded-lg flex-shrink-0">
                            @if($typeKey === 'tabungan')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                                </svg>
                            @elseif($typeKey === 'ewallet')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            @elseif($typeKey === 'tunai')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <rect x="2" y="5" width="20" height="14" rx="2" />
                                    <circle cx="12" cy="12" r="3" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 9h.01M18 15h.01" />
                                </svg>
                            @endif
                        </div>
                    </div>

                    @php $change = $this->summary[$typeKey . '_change'] ?? 0; @endphp
                    @if($change > 0)
                        <div class="flex items-center gap-1 mt-1.5">
                            <svg class="w-3 h-3 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L10 6.414l-3.293 3.293a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-xs text-emerald-600 font-medium">
                                +Rp {{ number_format(abs($change), 0, ',', '.') }}
                            </span>
                            <span class="text-[10px] text-text-muted">bulan ini</span>
                        </div>
                    @elseif($change < 0)
                        <div class="flex items-center gap-1 mt-1.5">
                            <svg class="w-3 h-3 text-danger" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L10 13.586l3.293-3.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-xs text-danger font-medium">
                                -Rp {{ number_format(abs($change), 0, ',', '.') }}
                            </span>
                            <span class="text-[10px] text-text-muted">bulan ini</span>
                        </div>
                    @else
                        <div class="flex items-center gap-1 mt-1.5">
                            <span class="text-xs text-text-muted font-medium">
                                — Tidak berubah bulan ini
                            </span>
                        </div>
                    @endif
                </div>

                {{-- Row Info Jumlah & Nama Rekening --}}
                <div class="mt-3 pt-2 border-t border-border">
                    <p class="text-[10px] text-text-muted font-medium truncate">
                        {{ $this->accountsByType[$typeKey] }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="bg-bg-sidebar rounded-2xl border border-border p-4 sm:p-5 shadow-sm">
        <div class="flex flex-col md:flex-row gap-4">
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

            <div class="flex items-center gap-2 overflow-x-auto pb-1 md:pb-0 scrollbar-hide">
                <span class="text-xs font-bold text-text-muted uppercase tracking-widest mr-2 whitespace-nowrap">Urut:</span>
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
                        ? 'bg-primary text-white shadow-sm ring-1 ring-primary hover:bg-primary-hover'
                        : 'bg-bg-sidebar text-text-muted border border-border hover:border-text-muted/30 hover:text-text hover:bg-bg' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Account List - Accordion Groups --}}
    <div class="space-y-4">
        @php
            $groupedAccounts = $this->accountsGroupedByType;
            $allAccountsEmpty = true;
            foreach ($groupedAccounts as $group) {
                if ($group['count'] > 0) {
                    $allAccountsEmpty = false;
                    break;
                }
            }
        @endphp

        @if($allAccountsEmpty)
            {{-- Empty state when no accounts at all --}}
            <div class="bg-bg-sidebar rounded-3xl border border-dashed border-border p-16 text-center shadow-inner bg-bg/30">
                <div class="w-20 h-20 bg-bg-sidebar rounded-2xl shadow-sm border border-border flex items-center justify-center mx-auto mb-5 rotate-3 transition hover:rotate-0">
                    <svg class="w-10 h-10 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
                <h3 class="text-text font-bold text-lg">Belum ada rekening</h3>
                <p class="text-text-muted text-sm mt-1 max-w-xs mx-auto">Mulai kelola keuanganmu dengan menambahkan rekening pertama hari ini.</p>
                <button
                    x-data x-on:click="$dispatch('open-account-form')"
                    class="mt-6 px-6 py-2.5 bg-primary hover:bg-primary-hover text-white rounded-xl font-bold text-sm shadow-md shadow-primary/10 ring-1 ring-inset ring-primary/20 transition-all">
                    Buat Rekening Sekarang
                </button>
            </div>
        @else
            {{-- Accordion Groups --}}
            @foreach(['tabungan' => 'Tabungan', 'ewallet' => 'E-Wallet', 'tunai' => 'Tunai'] as $typeKey => $typeLabel)
                @php
                    $group = $groupedAccounts[$typeKey];
                    $isExpanded = $this->expandedGroups[$typeKey] ?? false;
                    $showGroup = $this->activeTab === 'semua' || $this->activeTab === $typeKey;
                @endphp

                @if($showGroup)
                    <div class="bg-bg-sidebar rounded-2xl border border-border shadow-sm overflow-hidden">
                        {{-- Accordion Header --}}
                        <button
                            wire:click="toggleGroup('{{ $typeKey }}')"
                            class="w-full p-4 sm:p-5 flex items-center gap-3 sm:gap-4 min-h-[44px] hover:bg-bg/50 transition-colors duration-200 text-left">
                            {{-- Chevron Icon --}}
                            <div class="flex-shrink-0 text-text-muted transition-transform duration-200 {{ $isExpanded ? 'rotate-90' : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>

                            {{-- Category Icon --}}
                            <div class="text-primary bg-primary-light p-1.5 rounded-lg flex-shrink-0">
                                @if($typeKey === 'tabungan')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                                    </svg>
                                @elseif($typeKey === 'ewallet')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                @elseif($typeKey === 'tunai')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <rect x="2" y="5" width="20" height="14" rx="2" />
                                        <circle cx="12" cy="12" r="3" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 9h.01M18 15h.01" />
                                    </svg>
                                @endif
                            </div>

                            {{-- Category Name & Count --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <p class="font-bold text-text text-sm sm:text-base truncate">{{ $typeLabel }}</p>
                                    <span class="text-[11px] text-text-muted font-medium">({{ $group['count'] }} rekening)</span>
                                </div>
                            </div>

                            {{-- Total Balance --}}
                            <div class="text-right flex-shrink-0">
                                <p class="font-bold text-text text-sm sm:text-base">
                                    Rp {{ number_format($group['total_balance'], 0, ',', '.') }}
                                </p>
                            </div>
                        </button>

                        {{-- Accordion Content --}}
                        <div class="{{ $isExpanded ? '' : 'hidden' }}">
                            @if($group['count'] > 0)
                                <div class="px-4 sm:px-5 pb-4 sm:pb-5 space-y-3">
                                    @foreach($group['accounts'] as $account)
                                        @php
                                            $percentage = $this->accountPercentages[$account->id] ?? 0;
                                            $isLowBalance = $account->balance <= \App\Livewire\Accounts\AccountList::LOW_BALANCE_THRESHOLD;
                                        @endphp

                                        <div class="bg-bg rounded-2xl border {{ $isLowBalance ? 'border-danger ring-1 ring-danger/10' : 'border-border' }} shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
                                            <div class="p-4 sm:p-5 flex flex-col">
                                                {{-- Baris Label Kecil di Atas --}}
                                                <div class="text-[10px] font-bold text-text-muted tracking-wider uppercase mb-3 pb-2 border-b border-border/70 flex justify-between items-center">
                                                    <span>
                                                        {{ $account->transactions_count_this_month }} Transaksi Bulan Ini · Terakhir {{ $account->last_transaction_time ? $account->last_transaction_time->diffForHumans() : 'Belum Ada Transaksi' }}
                                                    </span>
                                                    {{-- Kalo Saldo Menipis taruh badge juga --}}
                                                    @if($isLowBalance)
                                                        <span class="text-[9px] uppercase tracking-wider px-2 py-0.5 rounded-md font-bold bg-danger/10 text-danger border border-danger/20 animate-pulse flex items-center gap-1">
                                                            <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                            </svg>
                                                            Saldo Menipis
                                                        </span>
                                                    @endif
                                                </div>

                                                {{-- Main Info Row --}}
                                                <div class="flex items-center justify-between gap-4">
                                                    <div class="flex items-center gap-3 sm:gap-4 min-w-0">
                                                        {{-- Icon Avatar --}}
                                                        <div
                                                            class="w-12 h-12 rounded-2xl flex items-center justify-center text-sm font-bold flex-shrink-0"
                                                            style="background-color: {{ $account->color ?? '#475569' }}15; color: {{ $account->color ?? '#475569' }}">
                                                            {{ strtoupper(substr($account->provider ?? $account->name, 0, 2)) }}
                                                        </div>

                                                        {{-- Info Text --}}
                                                        <div class="min-w-0">
                                                            <div class="flex items-center gap-2 flex-wrap">
                                                                <p class="font-bold text-text text-base leading-tight truncate">{{ $account->name }}</p>
                                                                
                                                                {{-- Type Badge with Icon --}}
                                                                <span class="text-[9px] uppercase tracking-wider px-2 py-0.5 rounded-md font-bold bg-bg text-text-muted border border-border flex items-center gap-1">
                                                                    @if($account->type === 'tabungan')
                                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                                                                        </svg>
                                                                    @elseif($account->type === 'ewallet')
                                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                                        </svg>
                                                                    @elseif($account->type === 'tunai')
                                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                                            <rect x="2" y="5" width="20" height="14" rx="2" />
                                                                            <circle cx="12" cy="12" r="3" />
                                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 9h.01M18 15h.01" />
                                                                        </svg>
                                                                    @endif
                                                                    {{ $account->type }}
                                                                </span>
                                                            </div>
                                                            <p class="text-xs text-text-muted mt-1 font-medium">{{ $account->provider ?? 'Tunai' }}</p>
                                                        </div>
                                                    </div>

                                                    {{-- Saldo & Delta & Actions --}}
                                                    <div class="flex items-center gap-4">
                                                        <div class="text-right flex-shrink-0">
                                                            <p class="font-bold {{ $isLowBalance ? 'text-danger' : 'text-text' }} text-lg leading-tight">
                                                                Rp {{ number_format($account->balance, 0, ',', '.') }}
                                                            </p>
                                                            
                                                            {{-- Delta change --}}
                                                            @if(($account->monthly_change ?? 0) > 0)
                                                                <span class="text-[11px] text-emerald-600 font-bold flex items-center justify-end gap-0.5 mt-1">
                                                                    <svg class="w-3 h-3 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L10 6.414l-3.293 3.293a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                                                    </svg>
                                                                    +Rp {{ number_format(abs($account->monthly_change), 0, ',', '.') }}
                                                                </span>
                                                            @elseif(($account->monthly_change ?? 0) < 0)
                                                                <span class="text-[11px] text-danger font-bold flex items-center justify-end gap-0.5 mt-1">
                                                                    <svg class="w-3 h-3 text-danger" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L10 13.586l3.293-3.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                                    </svg>
                                                                    -Rp {{ number_format(abs($account->monthly_change), 0, ',', '.') }}
                                                                </span>
                                                            @else
                                                                <span class="text-[11px] text-text-muted font-bold flex items-center justify-end gap-0.5 mt-1">
                                                                    — Tidak berubah
                                                                </span>
                                                            @endif
                                                        </div>

                                                        {{-- Actions Desktop --}}
                                                        <div class="hidden sm:flex items-center gap-1 ml-4" x-data>
                                                            <button
                                                                x-on:click="$dispatch('open-transfer', { fromAccountId: {{ $account->id }} })"
                                                                class="p-2.5 text-text-muted hover:text-text hover:bg-bg rounded-xl transition"
                                                                title="Transfer">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                                                </svg>
                                                            </button>
                                                            <button
                                                                x-on:click="$dispatch('open-account-form', { id: {{ $account->id }} })"
                                                                class="p-2.5 text-text-muted hover:text-text hover:bg-bg rounded-xl transition"
                                                                title="Edit">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                                </svg>
                                                            </button>
                                                            <button
                                                                wire:click="confirmDelete({{ $account->id }})"
                                                                class="p-2.5 text-text-muted hover:text-danger hover:bg-danger/10 rounded-xl transition"
                                                                title="Hapus">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Progress Bar --}}
                                                @if($percentage > 0)
                                                    <div class="mt-4 flex items-center justify-between gap-3">
                                                        <div class="flex-1 bg-bg rounded-full h-2 overflow-hidden">
                                                            <div
                                                                class="h-full rounded-full transition-all duration-1000 ease-out"
                                                                style="width: {{ min($percentage, 100) }}%; background-color: {{ $account->color ?? '#475569' }}">
                                                            </div>
                                                        </div>
                                                        <span class="text-[11px] font-bold text-text-muted whitespace-nowrap">
                                                            {{ $percentage }}%
                                                        </span>
                                                    </div>
                                                @endif

                                                {{-- Mobile Actions --}}
                                                <div class="flex sm:hidden items-center gap-2 mt-4 pt-4 border-t border-border/50" x-data>
                                                    <button
                                                        x-on:click="$dispatch('open-transfer', { fromAccountId: {{ $account->id }} })"
                                                        class="flex-1 flex items-center justify-center gap-2 py-3 rounded-2xl bg-primary hover:bg-primary-hover text-white text-[11px] font-bold shadow-md ring-1 ring-inset ring-primary/20 active:scale-95 transition-all">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                                        </svg>
                                                        Transfer
                                                    </button>
                                                    <button
                                                        x-on:click="$dispatch('open-account-form', { id: {{ $account->id }} })"
                                                        class="flex-1 flex items-center justify-center gap-2 py-3 rounded-2xl bg-bg-sidebar text-text text-[11px] font-bold border border-border hover:bg-bg active:scale-95 transition-all">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                        Edit
                                                    </button>
                                                    <button
                                                        wire:click="confirmDelete({{ $account->id }})"
                                                        class="w-12 flex items-center justify-center py-3 rounded-2xl bg-danger/10 text-danger border border-danger/20 hover:bg-danger/20 active:scale-95 transition-all">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                {{-- Empty state for this category --}}
                                <div class="px-4 sm:px-5 pb-4 sm:pb-5">
                                    <div class="bg-bg rounded-2xl border border-dashed border-border p-8 text-center">
                                        <p class="text-text-muted text-sm">Belum ada rekening di kategori ini</p>
                                        <button
                                            x-data x-on:click="$dispatch('open-account-form')"
                                            class="mt-3 px-4 py-2 bg-primary hover:bg-primary-hover text-white rounded-xl font-bold text-xs shadow-md shadow-primary/10 ring-1 ring-inset ring-primary/20 transition-all">
                                            Tambah Rekening {{ $typeLabel }}
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            @endforeach
        @endif
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
    <livewire:transactions.category />

</div>