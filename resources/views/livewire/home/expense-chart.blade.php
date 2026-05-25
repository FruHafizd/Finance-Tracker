<div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex flex-col h-full hover:shadow-md transition-all duration-300">
    <div class="flex items-start justify-between mb-5 gap-3">
        <div>
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Pengeluaran Kategori</h3>
            <p class="text-xs font-semibold text-slate-900 mt-1">Bulan {{ now()->translatedFormat('F Y') }}</p>
        </div>
        @if(count($chartData['data']) > 0)
        <div class="text-right">
            <p class="text-[9px] font-bold tracking-widest uppercase text-slate-400">Total Pengeluaran</p>
            <p class="text-base font-extrabold text-rose-600 tracking-tight mt-0.5">Rp {{ number_format(array_sum($chartData['data']), 0, ',', '.') }}</p>
        </div>
        @endif
    </div>

    @if(count($chartData['data']) > 0)
        @php
            $maxVal = max($chartData['data']) ?: 1;
            $sumVal = array_sum($chartData['data']) ?: 1;
        @endphp
        <div class="space-y-4 my-auto">
            @foreach($chartData['labels'] as $i => $label)
                @php
                    $val = $chartData['data'][$i];
                    $percentOfMax = round(($val / $maxVal) * 100, 1);
                    $percentOfTotal = round(($val / $sumVal) * 100, 1);
                    $color = $chartData['colors'][$i] ?? '#0EA5E9';
                @endphp
                <div class="space-y-1">
                    <div class="flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2 text-slate-600 min-w-0">
                            <span class="w-2 h-2 rounded-full flex-shrink-0" style="background-color: {{ $color }}"></span>
                            <span class="truncate font-semibold">{{ $label }}</span>
                        </div>
                        <div class="flex items-center gap-2 font-bold flex-shrink-0">
                            <span class="text-slate-900">Rp {{ number_format($val, 0, ',', '.') }}</span>
                            <span class="text-[10px] text-slate-400">({{ $percentOfTotal }}%)</span>
                        </div>
                    </div>
                    <div class="w-full bg-slate-50 h-1.5 rounded-full overflow-hidden">
                        <div class="h-full rounded-full transition-all duration-700 ease-out" style="background-color: {{ $color }}; width: {{ $percentOfMax }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="flex flex-col items-center justify-center py-10 px-4 my-auto">
            <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center mb-3 text-slate-300 border border-slate-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <p class="text-xs font-bold text-slate-900 tracking-tight">Belum ada pengeluaran</p>
            <p class="text-[10px] text-slate-400 mt-1 text-center max-w-xs leading-relaxed">Statistik otomatis muncul setelah Anda mencatat transaksi pengeluaran bulan ini.</p>
        </div>
    @endif
</div>