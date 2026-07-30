@props(['totalLimit', 'totalSpent'])

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
