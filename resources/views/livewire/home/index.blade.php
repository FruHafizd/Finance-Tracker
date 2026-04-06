<div>
    {{-- ===== HERO BANNER BARU ===== --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6 sm:mt-10 transition-all duration-500">
        <div class="relative overflow-hidden rounded-3xl bg-white border border-gray-200 p-8 sm:p-10 shadow-sm group">
            {{-- Subtle Decorative elements --}}
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-gray-50 rounded-full blur-3xl pointer-events-none group-hover:bg-gray-100 transition-colors duration-700"></div>
            <div class="absolute -bottom-12 -left-12 w-48 h-48 bg-slate-50 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-8">
                <div class="space-y-4">
                    <div class="inline-flex items-center gap-2 bg-slate-100 border border-slate-200 px-3 py-1 rounded-full text-slate-600 text-xs font-semibold uppercase tracking-wider">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-slate-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-slate-500"></span>
                        </span>
                        Dashboard Personal
                    </div>
                    <div>
                        <p class="text-slate-500 text-lg font-medium">Selamat datang kembali 👋</p>
                        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight mt-1">
                            {{ auth()->user()->name }}
                        </h1>
                        <p class="text-slate-600 text-sm sm:text-base max-w-md mt-4 leading-relaxed">
                            Kelola arus kas Anda dengan presisi. Ringkasan aktivitas keuangan Anda bulan ini sudah siap ditinjau.
                        </p>
                    </div>
                </div>

                {{-- Date Badge --}}
                <div class="flex flex-col items-end gap-3">
                    <div class="bg-gray-50 border border-gray-100 p-4 rounded-2xl flex items-center gap-4 shadow-inner hover:bg-gray-100 transition-all duration-300">
                        <div class="w-12 h-12 bg-slate-900 rounded-xl flex flex-col items-center justify-center text-white shadow-lg">
                            <span class="text-[10px] font-bold uppercase leading-none">{{ now()->translatedFormat('M') }}</span>
                            <span class="text-xl font-black leading-none">{{ now()->format('d') }}</span>
                        </div>
                        <div>
                            <p class="text-slate-900 font-bold text-lg">{{ now()->translatedFormat('F Y') }}</p>
                            <p class="text-slate-500 text-xs font-medium">Periode aktif berjalan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- ===== END HERO BANNER ===== --}}

    <div class="py-6 sm:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">

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

    {{-- Month in Review Story --}}
    @if($showReview)
        <x-story-modal :reviewData="$reviewData" />
    @endif
</div>
