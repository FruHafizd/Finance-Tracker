<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-text leading-tight">
            Budget
        </h2>
    </x-slot>

    <div class="py-6 sm:py-10">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @if ($exceededBudgets->isNotEmpty())
                <div class="rounded-xl border border-danger/20 bg-danger/10 p-4">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-danger/10 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-sm text-danger font-bold">!</span>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-danger">
                                {{ $exceededBudgets->count() }} kategori sudah melebihi batas bulan ini!
                            </p>
                            <ul class="mt-2 space-y-1">
                                @foreach ($exceededBudgets as $b)
                                    @php
                                        $spent = $b->spentAmount();
                                        $over = $spent - $b->limit_amount;
                                    @endphp
                                    <li class="text-xs text-danger flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-danger flex-shrink-0"></span>
                                        <span>
                                            <span class="font-medium">{{ $b->category->name }}</span>
                                            - kebablasan
                                            <span class="font-medium">Rp {{ number_format($over, 0, ',', '.') }}</span>
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                            <p class="text-xs text-danger mt-2">
                                Naikkan batas budget atau kurangi pengeluaran di kategori tersebut.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="flex items-center justify-between gap-4 flex-wrap">
                <div class="flex items-center gap-2">
                    <div class="flex items-center gap-1 bg-bg-sidebar border border-border rounded-xl px-1 py-1 shadow-sm">
                        <svg class="w-4 h-4 text-text-muted ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <select wire:model.live="month" class="border-0 bg-transparent text-sm text-text font-medium focus:outline-none focus:ring-0 pr-2 py-1 cursor-pointer">
                            @foreach (range(1, 12) as $m)
                                <option value="{{ $m }}">{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center gap-1 bg-bg-sidebar border border-border rounded-xl px-1 py-1 shadow-sm">
                        <select wire:model.live="year" class="border-0 bg-bg-sidebar text-sm text-text font-medium focus:outline-none focus:ring-0 px-2 py-1 pr-7 cursor-pointer">
                            @foreach (range((int) now()->format('Y') - 1, (int) now()->format('Y') + 1) as $y)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <button wire:click="$dispatch('open-create-budget')" class="w-full sm:w-auto flex items-center justify-center gap-2 px-4 py-2.5 bg-primary text-white text-sm font-medium rounded-xl hover:bg-primary-hover active:scale-95 transition-all duration-150 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Budget
                </button>
            </div>

            @if ($budgets->isNotEmpty())
                @php
                    $totalLimit = $budgets->sum('limit_amount');
                    $totalSpent = $budgets->sum(fn($b) => $b->spentAmount());
                    $chartRadius = 42;
                    $chartCircumference = 2 * pi() * $chartRadius;
                    $chartOffset = 0;
                    $chartData = $budgets->map(fn($b) => [
                        'name' => $b->category->name,
                        // Donut menunjukkan alokasi limit tiap kategori dari total budget, bukan pengeluaran aktual.
                        'allocation' => (float) $b->limit_amount,
                        'color' => $b->category->color ?? '#6366f1',
                    ])->values()->map(function ($item) use ($totalLimit, $chartCircumference, &$chartOffset) {
                        $percentage = $totalLimit > 0 ? ($item['allocation'] / $totalLimit) * 100 : 0;
                        $length = ($percentage / 100) * $chartCircumference;

                        $item['percentage'] = $percentage;
                        $item['dasharray'] = $length . ' ' . ($chartCircumference - $length);
                        $item['dashoffset'] = -$chartOffset;
                        $chartOffset += $length;

                        return $item;
                    });
                @endphp

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-bg-sidebar rounded-2xl p-4 sm:p-5 shadow-[0_2px_10px_-3px_rgba(14,165,233,0.08)] ring-1 ring-inset ring-border">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-2xl bg-primary-light flex items-center justify-center flex-shrink-0 ring-1 ring-inset ring-primary/20">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[11px] font-bold text-text-muted tracking-wider uppercase">Total Budget</p>
                                <p class="text-lg font-black text-text mt-0.5 truncate">Rp {{ number_format($totalLimit, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-bg-sidebar rounded-2xl p-4 sm:p-5 shadow-[0_2px_10px_-3px_rgba(14,165,233,0.08)] ring-1 ring-inset ring-border">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-2xl bg-danger/10 flex items-center justify-center flex-shrink-0 ring-1 ring-inset ring-danger/10">
                                <svg class="w-5 h-5 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 13l-5 5m0 0l-5-5m5 5V6" /></svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[11px] font-bold text-text-muted tracking-wider uppercase">Terpakai</p>
                                <p class="text-lg font-black text-text mt-0.5 truncate">Rp {{ number_format($totalSpent, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-bg-sidebar rounded-2xl p-4 sm:p-5 shadow-[0_2px_10px_-3px_rgba(14,165,233,0.08)] ring-1 ring-inset ring-border">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-2xl bg-emerald-50 flex items-center justify-center flex-shrink-0 ring-1 ring-inset ring-emerald-600/10">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[11px] font-bold text-text-muted tracking-wider uppercase">Sisa</p>
                                <p class="text-lg font-black {{ ($totalLimit - $totalSpent) < 0 ? 'text-danger' : 'text-emerald-600' }} mt-0.5 truncate">Rp {{ number_format(max($totalLimit - $totalSpent, 0), 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <div class="lg:col-span-2 space-y-3">
                        @foreach ($budgets as $budget)
                            @php
                                $spent = $budget->spentAmount();
                                $percentage = $budget->spentPercentage();
                                $isWarning = $percentage >= 80 && $percentage < 100;
                                $isDanger = $percentage >= 100;
                                $barColor = $isDanger ? 'bg-danger' : ($isWarning ? 'bg-amber-400' : 'bg-emerald-500');
                            @endphp

                            <div class="rounded-xl p-5 border border-border bg-bg-sidebar shadow-sm transition-all duration-200 hover:shadow-md">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex items-center gap-2.5 flex-wrap">
                                        <span class="w-3.5 h-3.5 rounded-full flex-shrink-0 ring-2 ring-bg-sidebar shadow-sm" style="background-color: {{ $budget->category->color ?? '#6366f1' }}"></span>
                                        <span class="font-semibold text-text text-sm">{{ $budget->category->name }}</span>
                                        @if ($isWarning)
                                            <span class="inline-flex items-center gap-1 text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full font-medium">Hampir habis</span>
                                        @elseif ($isDanger)
                                            <span class="inline-flex items-center gap-1 text-xs bg-danger/10 text-danger px-2 py-0.5 rounded-full font-medium">Melebihi budget</span>
                                        @endif
                                    </div>

                                    <div class="flex items-center gap-1 flex-shrink-0">
                                        <button x-data x-on:click="$dispatch('edit-budget', { id: {{ $budget->id }} });" class="p-1.5 text-text-muted hover:text-text-hover hover:bg-bg rounded-lg transition-colors duration-150" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                        </button>
                                        <button x-data x-on:click="$wire.confirmDelete({{ $budget->id }})" class="p-1.5 text-text-muted hover:text-danger hover:bg-danger/10 rounded-lg transition-colors duration-150" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="flex justify-between items-baseline mb-2">
                                    <span class="text-xl font-bold text-text">Rp {{ number_format($spent, 0, ',', '.') }}</span>
                                    <span class="text-xs text-text-muted">dari Rp {{ number_format($budget->limit_amount, 0, ',', '.') }}</span>
                                </div>
                                <div class="w-full bg-bg rounded-full h-2 overflow-hidden">
                                    <div class="{{ $barColor }} h-2 rounded-full transition-all duration-500" style="width: {{ min($percentage, 100) }}%"></div>
                                </div>
                                <div class="flex justify-between items-center mt-1.5">
                                    <span class="text-xs {{ $isDanger ? 'text-danger font-medium' : ($isWarning ? 'text-amber-600 font-medium' : 'text-text-muted') }}">{{ $percentage }}% terpakai</span>
                                    <span class="text-xs text-text-muted">Sisa Rp {{ number_format(max($budget->limit_amount - $spent, 0), 0, ',', '.') }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="bg-bg-sidebar rounded-2xl p-5 shadow-[0_2px_10px_-3px_rgba(14,165,233,0.08)] ring-1 ring-inset ring-border h-fit">
                        <h3 class="text-sm font-bold text-text">Alokasi dari Total Budget</h3>
                        <p class="text-xs text-text-muted mt-1">Porsi limit budget tiap kategori bulan ini</p>
                        <div class="mt-5">
                            <div class="relative mx-auto w-48 h-48">
                                <svg class="w-full h-full" viewBox="0 0 100 100" role="img" aria-label="Diagram alokasi budget per kategori">
                                    <circle cx="50" cy="50" r="42" fill="none" stroke="#E2E8F0" stroke-width="16" />
                                    @foreach ($chartData as $item)
                                        <circle
                                            cx="50"
                                            cy="50"
                                            r="42"
                                            fill="none"
                                            stroke="{{ $item['color'] }}"
                                            stroke-width="16"
                                            stroke-dasharray="{{ $item['dasharray'] }}"
                                            stroke-dashoffset="{{ $item['dashoffset'] }}"
                                            transform="rotate(-90 50 50)"
                                        />
                                    @endforeach
                                </svg>
                                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                                    <span class="text-xs text-text-muted">Total</span>
                                    <span class="text-sm font-bold text-text">Rp {{ number_format($totalLimit, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            <ul class="mt-5 space-y-2">
                                @foreach ($chartData as $item)
                                    <li class="flex items-center justify-between gap-3 text-xs">
                                        <span class="flex items-center gap-2 min-w-0"><span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background-color: {{ $item['color'] }}"></span><span class="text-text truncate">{{ $item['name'] }}</span></span>
                                        <span class="text-right flex-shrink-0"><span class="block text-text font-medium">{{ number_format($item['percentage'], 1) }}%</span><span class="block text-[11px] text-text-muted">Rp {{ number_format($item['allocation'], 0, ',', '.') }}</span></span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-20 text-center">
                    <div class="w-16 h-16 bg-primary-light rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                    </div>
                    <p class="text-text font-medium">Belum ada budget bulan ini</p>
                    <p class="text-sm text-text-muted mt-1">Mulai tetapkan batas pengeluaran per kategori</p>
                    <button wire:click="$dispatch('open-create-budget')" class="mt-5 px-4 py-2 bg-primary text-white text-sm font-medium rounded-xl hover:bg-primary-hover transition shadow-sm">+ Tambah Budget Pertama</button>
                </div>
            @endif
        </div>
    </div>

    <livewire:budgets.budget-form />
    <x-modal-delete name="modal-delete-budget" title="Hapus Budget" description="Apakah Anda yakin ingin menghapus budget ini? Data budget yang dihapus tidak dapat dikembalikan." action="delete" />
</div>
