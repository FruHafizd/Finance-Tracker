<div class="bg-white border border-gray-200 rounded-3xl shadow-sm p-6 hover:shadow-md transition-all duration-300">
    <div class="flex items-center justify-between mb-5">
        <div>
            <h2 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Budget Bulan Ini</h2>
        </div>
        <a href="{{ route('budget.index') }}" class="text-[11px] font-semibold text-sky-500 hover:text-sky-600 transition-colors flex items-center gap-0.5">
            Atur Budget
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
        </a>
    </div>

    @if($budgets->isEmpty())
        <div class="flex flex-col items-center justify-center py-6 text-center">
            <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center mb-3 text-slate-300 border border-slate-100">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
            <p class="text-xs font-bold text-slate-900 tracking-tight">Belum ada budget bulan ini</p>
            <p class="text-[10px] text-slate-400 mt-1 mb-3">Tentukan alokasi budget agar pengeluaran terkendali.</p>
            <a href="{{ route('budget.index') }}" class="inline-flex items-center justify-center px-4 py-2 text-[10px] font-bold text-white bg-sky-500 hover:bg-sky-600 rounded-xl transition-all shadow-sm">
                + Buat Budget Pertama
            </a>
        </div>
    @else
        @php
            $totalBudget = $budgets->sum('limit_amount');
            $totalSpent = $budgets->sum(fn($b) => $b->spentAmount());
            $overallPercent = $totalBudget > 0 ? round(($totalSpent / $totalBudget) * 100, 1) : 0;
            $overBudgets = $budgets->filter(fn($b) => $b->isExceeded())->count();
        @endphp

        {{-- Top Summary Stats to Fill Empty Feel --}}
        <div class="grid grid-cols-3 gap-3 mb-6 p-3 bg-slate-50/50 rounded-2xl border border-slate-100/50">
            <div class="text-center">
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Total Limit</span>
                <span class="text-xs font-extrabold text-slate-800 block mt-0.5">Rp {{ number_format($totalBudget, 0, ',', '.') }}</span>
            </div>
            <div class="text-center border-x border-slate-200/50 px-1">
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Total Terpakai</span>
                <span class="text-xs font-extrabold text-slate-800 block mt-0.5">Rp {{ number_format($totalSpent, 0, ',', '.') }}</span>
            </div>
            <div class="text-center">
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Over Limit</span>
                <span class="text-xs font-extrabold {{ $overBudgets > 0 ? 'text-rose-600' : 'text-emerald-600' }} block mt-0.5">{{ $overBudgets }} Kategori</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($budgets as $budget)
                @php
                    $spent = $budget->spentAmount();
                    $limit = $budget->limit_amount;
                    $percent = $limit > 0 ? round(($spent / $limit) * 100, 1) : 0;
                    $remaining = $limit - $spent;
                    
                    if ($percent >= 100) {
                        $barColor = 'bg-rose-500';
                        $textColor = 'text-rose-600';
                        $bgColor = 'bg-rose-50';
                    } elseif ($percent >= 80) {
                        $barColor = 'bg-amber-500';
                        $textColor = 'text-amber-600';
                        $bgColor = 'bg-amber-50';
                    } else {
                        $barColor = 'bg-emerald-500';
                        $textColor = 'text-emerald-600';
                        $bgColor = 'bg-emerald-50';
                    }
                @endphp
                <div class="p-3 rounded-xl border border-slate-100 bg-slate-50/30 hover:border-slate-200 transition-colors flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <div class="flex items-center gap-1.5 min-w-0">
                                <span class="w-2 h-2 rounded-full flex-shrink-0" style="background-color: {{ $budget->category->color ?? '#64748B' }}"></span>
                                <span class="text-xs font-bold text-slate-800 truncate">{{ $budget->category->name ?? 'Tanpa Kategori' }}</span>
                            </div>
                            <span class="text-[10px] font-bold {{ $textColor }} px-1.5 py-0.5 rounded-full {{ $bgColor }} flex-shrink-0">
                                {{ $percent }}%
                            </span>
                        </div>

                        <div class="flex items-baseline justify-between mb-2">
                            <p class="text-[10px] text-slate-500">
                                Rp {{ number_format($spent, 0, ',', '.') }} / {{ number_format($limit, 0, ',', '.') }}
                            </p>
                            <p class="text-[10px] font-semibold {{ $remaining >= 0 ? 'text-slate-500' : 'text-rose-600' }}">
                                {{ $remaining >= 0 ? 'Sisa Rp ' . number_format($remaining, 0, ',', '.') : 'Over Rp ' . number_format(abs($remaining), 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    <div class="w-full bg-slate-100/50 h-1.5 rounded-full overflow-hidden mt-1">
                        <div class="h-full {{ $barColor }} rounded-full transition-all duration-500" style="width: {{ min($percent, 100) }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
