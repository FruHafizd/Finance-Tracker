<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Beranda</h2>
    </x-slot>

    <div class="py-6 sm:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Welcome Header --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Selamat datang 👋</h2>
                    <p class="text-sm font-medium text-gray-500 mt-1">Ringkasan aktivitas keuangan Anda bulan ini.</p>
                </div>
                <div class="inline-flex items-center gap-2 bg-white px-4 py-2 rounded-xl ring-1 ring-inset ring-gray-200 shadow-sm text-sm font-semibold text-gray-700">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <rect x="3" y="4" width="18" height="18" rx="2" stroke-width="2"/>
                        <line x1="16" y1="2" x2="16" y2="6" stroke-width="2"/>
                        <line x1="8" y1="2" x2="8" y2="6" stroke-width="2"/>
                        <line x1="3" y1="10" x2="21" y2="10" stroke-width="2"/>
                    </svg>
                    {{ now()->translatedFormat('F Y') }}
                </div>
            </div>

            {{-- Summary Cards --}}
            <div>
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 ml-1">Ringkasan Cepat</h3>
                <livewire:home.summary-cards />
            </div>

            {{-- Charts & Score --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-1 order-2 lg:order-1">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 ml-1">Kesehatan Keuangan</h3>
                    <livewire:home.financial-score />
                </div>
                <div class="lg:col-span-2 order-1 lg:order-2">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 ml-1">Analisis Pengeluaran</h3>
                    <livewire:home.expense-chart />
                </div>
            </div>


        </div>
    </div>
</div>