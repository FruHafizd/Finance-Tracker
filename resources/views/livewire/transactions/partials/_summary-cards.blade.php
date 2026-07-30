{{-- ============================================================
    Summary Cards — Pemasukan · Pengeluaran · Saldo Bersih
    Tema: Sky on White — Airy & Light
    Token warna: primary, primary-light, bg-sidebar, text, text-muted, border, danger
    ============================================================ --}}

<div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 mb-8">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">

        {{-- PEMASUKAN --}}
        <div class="bg-bg-sidebar rounded-2xl p-4 sm:p-5 shadow-[0_2px_10px_-3px_rgba(14,165,233,0.08)] ring-1 ring-inset ring-border">
            <div class="flex items-center gap-3 sm:gap-4">
                <div class="w-11 h-11 sm:w-14 sm:h-14 rounded-2xl bg-emerald-50 flex items-center justify-center flex-shrink-0 ring-1 ring-inset ring-emerald-600/10">
                    <svg class="w-5 h-5 sm:w-7 sm:h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <h3 class="text-[11px] sm:text-[13px] font-bold text-text-muted tracking-wider uppercase">Pemasukan</h3>
                    <div class="text-lg sm:text-[22px] font-black text-text tracking-tight mt-0.5 truncate">
                        Rp {{ number_format($summary['income'] ?? 0, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>

        {{-- PENGELUARAN --}}
        <div class="bg-bg-sidebar rounded-2xl p-4 sm:p-5 shadow-[0_2px_10px_-3px_rgba(14,165,233,0.08)] ring-1 ring-inset ring-border">
            <div class="flex items-center gap-3 sm:gap-4">
                <div class="w-11 h-11 sm:w-14 sm:h-14 rounded-2xl bg-red-50 flex items-center justify-center flex-shrink-0 ring-1 ring-inset ring-danger/10">
                    <svg class="w-5 h-5 sm:w-7 sm:h-7 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <h3 class="text-[11px] sm:text-[13px] font-bold text-text-muted tracking-wider uppercase">Pengeluaran</h3>
                    <div class="text-lg sm:text-[22px] font-black text-text tracking-tight mt-0.5 truncate">
                        Rp {{ number_format($summary['expense'] ?? 0, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>

        {{-- SALDO BERSIH — primary sky blue, bukan hitam --}}
        <div class="bg-primary-light rounded-2xl p-4 sm:p-5 shadow-[0_2px_10px_-3px_rgba(14,165,233,0.15)] ring-1 ring-inset ring-primary/15">
            <div class="flex items-center gap-3 sm:gap-4">
                <div class="w-11 h-11 sm:w-14 sm:h-14 rounded-2xl bg-primary/10 flex items-center justify-center flex-shrink-0 ring-1 ring-inset ring-primary/20">
                    <svg class="w-5 h-5 sm:w-7 sm:h-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <h3 class="text-[11px] sm:text-[13px] font-bold text-primary-hover tracking-wider uppercase">Saldo Bersih</h3>
                    <div class="text-lg sm:text-[22px] font-black text-text tracking-tight mt-0.5 truncate">
                        {{ ($summary['difference'] ?? 0) < 0 ? '-' : '' }}Rp {{ number_format(abs($summary['difference'] ?? 0), 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
