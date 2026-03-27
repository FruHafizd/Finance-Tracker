<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Budget
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Flash Message --}}
            @if ($flashMessage)
                <div
                    x-data="{ show: true }"
                    x-show="show"
                    x-init="setTimeout(() => show = false, 3000)"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-2"
                    wire:key="flash-{{ now()->timestamp }}"
                    class="flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm shadow-sm">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ $flashMessage }}
                </div>
            @endif

            {{-- Header --}}
            <div class="flex items-center justify-between gap-4 flex-wrap">

                {{-- Filter Bulan & Tahun --}}
                <div class="flex items-center gap-2">
                    <div class="flex items-center gap-1 bg-white border border-gray-200 rounded-xl px-1 py-1 shadow-sm">
                        <svg class="w-4 h-4 text-gray-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <select wire:model.live="month"
                            class="border-0 bg-transparent text-sm text-gray-700 font-medium focus:outline-none focus:ring-0 pr-2 py-1 cursor-pointer">
                            @foreach (range(1, 12) as $m)
                                <option value="{{ $m }}">
                                    {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center gap-1 bg-white border border-gray-200 rounded-xl px-1 py-1 shadow-sm">
                        <select wire:model.live="year"
                            class="border-0 bg-transparent text-sm text-gray-700 font-medium focus:outline-none focus:ring-0 px-2 py-1 cursor-pointer">
                            @foreach (range((int) now()->format('Y') - 1, (int) now()->format('Y') + 1) as $y)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Tombol Tambah --}}
                <button
                    x-data
                    x-on:click="$dispatch('open-modal', 'modal-budget-create')"
                    class="flex items-center gap-2 px-4 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 active:scale-95 transition-all duration-150 shadow-md shadow-indigo-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Budget
                </button>
            </div>

            {{-- Ringkasan total (hanya tampil kalau ada budget) --}}
            @if ($budgets->isNotEmpty())
                @php
                    $totalLimit = $budgets->sum('limit_amount');
                    $totalSpent = $budgets->sum(fn($b) => $b->spentAmount());
                    $totalPct   = $totalLimit > 0 ? round(($totalSpent / $totalLimit) * 100, 1) : 0;
                @endphp
                <div class="grid grid-cols-3 gap-3">
                    <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm text-center">
                        <p class="text-xs text-gray-400 mb-1">Total Budget</p>
                        <p class="text-base font-bold text-gray-800">
                            Rp {{ number_format($totalLimit, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm text-center">
                        <p class="text-xs text-gray-400 mb-1">Terpakai</p>
                        <p class="text-base font-bold text-indigo-600">
                            Rp {{ number_format($totalSpent, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm text-center">
                        <p class="text-xs text-gray-400 mb-1">Sisa</p>
                        <p class="text-base font-bold {{ ($totalLimit - $totalSpent) < 0 ? 'text-red-500' : 'text-emerald-600' }}">
                            Rp {{ number_format(max($totalLimit - $totalSpent, 0), 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            @endif

            {{-- Daftar Budget --}}
            @if ($budgets->isEmpty())
                <div class="flex flex-col items-center justify-center py-20 text-center">
                    <div class="w-16 h-16 bg-indigo-50 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <p class="text-gray-700 font-medium">Belum ada budget bulan ini</p>
                    <p class="text-sm text-gray-400 mt-1">Mulai tetapkan batas pengeluaran per kategori</p>
                    <button
                        x-data
                        x-on:click="$dispatch('open-modal', 'modal-budget-create')"
                        class="mt-5 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition shadow-md shadow-indigo-200">
                        + Tambah Budget Pertama
                    </button>
                </div>
            @else
                <div class="grid gap-3">
                    @foreach ($budgets as $budget)
                        @php
                            $spent      = $budget->spentAmount();
                            $percentage = $budget->spentPercentage();
                            $isWarning  = $percentage >= 80 && $percentage < 100;
                            $isDanger   = $percentage >= 100;
                            $barColor   = $isDanger
                                            ? 'bg-red-500'
                                            : ($isWarning ? 'bg-amber-400' : 'bg-indigo-500');
                            $bgCard     = $isDanger
                                            ? 'border-red-100 bg-red-50/30'
                                            : ($isWarning ? 'border-amber-100 bg-amber-50/30' : 'border-gray-100 bg-white');
                        @endphp

                        <div class="rounded-xl p-5 border {{ $bgCard }} shadow-sm transition-all duration-200 hover:shadow-md">

                            {{-- Baris atas --}}
                            <div class="flex items-start justify-between mb-4">

                                {{-- Kategori & badge --}}
                                <div class="flex items-center gap-2.5 flex-wrap">
                                    <span class="w-3.5 h-3.5 rounded-full flex-shrink-0 ring-2 ring-white shadow-sm"
                                        style="background-color: {{ $budget->category->color ?? '#6366f1' }}">
                                    </span>
                                    <span class="font-semibold text-gray-800 text-sm">
                                        {{ $budget->category->name }}
                                    </span>

                                    @if ($isWarning)
                                        <span class="inline-flex items-center gap-1 text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full font-medium">
                                            ⚠️ Hampir habis
                                        </span>
                                    @elseif ($isDanger)
                                        <span class="inline-flex items-center gap-1 text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded-full font-medium">
                                            🚨 Melebihi budget
                                        </span>
                                    @endif
                                </div>

                                {{-- Tombol Edit & Hapus --}}
                                <div class="flex items-center gap-1 flex-shrink-0">
                                    <button
                                        x-data
                                        x-on:click="
                                            $dispatch('open-modal', 'modal-budget-edit');
                                            $dispatch('edit-budget', { id: {{ $budget->id }} });
                                        "
                                        class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors duration-150"
                                        title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button
                                        x-data
                                        x-on:click="
                                            $dispatch('open-modal', 'modal-budget-delete');
                                            $dispatch('delete-budget', { id: {{ $budget->id }} });
                                        "
                                        class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-150"
                                        title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            {{-- Nominal --}}
                            <div class="flex justify-between items-baseline mb-2">
                                <span class="text-xl font-bold text-gray-800">
                                    Rp {{ number_format($spent, 0, ',', '.') }}
                                </span>
                                <span class="text-xs text-gray-400">
                                    dari Rp {{ number_format($budget->limit_amount, 0, ',', '.') }}
                                </span>
                            </div>

                            {{-- Progress Bar --}}
                            <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                                <div class="{{ $barColor }} h-2 rounded-full transition-all duration-500"
                                    style="width: {{ min($percentage, 100) }}%">
                                </div>
                            </div>

                            {{-- Persentase --}}
                            <div class="flex justify-between items-center mt-1.5">
                                <span class="text-xs {{ $isDanger ? 'text-red-500 font-medium' : ($isWarning ? 'text-amber-600 font-medium' : 'text-gray-400') }}">
                                    {{ $percentage }}% terpakai
                                </span>
                                <span class="text-xs text-gray-400">
                                    Sisa Rp {{ number_format(max($budget->limit_amount - $spent, 0), 0, ',', '.') }}
                                </span>
                            </div>

                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>

    <livewire:budgets.create />
    <livewire:budgets.edit />
    <livewire:budgets.delete />
</div>