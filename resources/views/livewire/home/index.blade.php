@php
    $hour = now()->hour;
    if ($hour >= 5 && $hour < 11) {
        $greeting = 'Selamat Pagi';
    } elseif ($hour >= 11 && $hour < 15) {
        $greeting = 'Selamat Siang';
    } elseif ($hour >= 15 && $hour < 19) {
        $greeting = 'Selamat Sore';
    } else {
        $greeting = 'Selamat Malam';
    }
@endphp
<div>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider">{{ $greeting }}</h3>
                <h2 class="font-extrabold text-2xl text-slate-800 leading-tight mt-0.5">
                    {{ auth()->user()->name }}
                </h2>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-semibold text-slate-400">Periode Berjalan:</span>
                <span class="px-2.5 py-1 bg-sky-50 text-sky-600 border border-sky-100/50 rounded-lg text-xs font-bold shadow-sm">
                    {{ now()->translatedFormat('d F Y') }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="pt-2 pb-6">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- 1. Ringkasan Cepat --}}
            <div>
                <div class="flex items-center justify-between mb-3 ml-1">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Ringkasan Cepat</h3>
                    <button wire:click="$dispatch('open-create-transaction')" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-sky-500 hover:bg-sky-600 active:bg-sky-700 text-white text-[11px] font-bold rounded-lg shadow-sm hover:shadow transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Transaksi
                    </button>
                </div>
                <livewire:home.summary-cards />
            </div>

            {{-- 2. Main Content Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                {{-- Left Side: Recent Transactions & Budget Overview (takes 7 cols out of 12 on large screens) --}}
                <div class="lg:col-span-7 space-y-6">
                    <livewire:home.recent-transactions />
                    
                    {{-- Budget Bulanan --}}
                    <livewire:home.budget-overview />
                </div>

                {{-- Right Side: Financial Score & Custom Category Expense Bars (takes 5 cols out of 12) --}}
                <div class="lg:col-span-5 space-y-6">
                    <div>
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 ml-1">Kesehatan Keuangan</h3>
                        <livewire:home.financial-score />
                    </div>
                    <div>
                        <livewire:home.expense-chart />
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Transaction Form Modal --}}
    <livewire:transactions.transaction-form />

    {{-- Month in Review Story --}}
    @if($showReview)
        <x-story-modal :reviewData="$reviewData" />
    @endif
</div>



