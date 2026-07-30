@props(['total', 'netChange'])

<div class="bg-primary rounded-2xl p-4 sm:p-5 text-white relative overflow-hidden shadow-lg shadow-primary/20 ring-1 ring-inset ring-primary/20">
    <div class="absolute top-0 right-0 w-20 h-20 bg-white/5 rounded-full -mr-6 -mt-6"></div>
    <div class="flex justify-between items-start relative z-10">
        <div>
            <p class="text-xs font-medium text-white/80">Total Saldo</p>
            <p class="text-xl font-bold mt-1 tracking-tight">
                Rp {{ number_format($total, 0, ',', '.') }}
            </p>
        </div>
        <div class="text-white bg-white/15 p-2 rounded-xl flex-shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h14a2 2 0 002-2v-6zM3 10V6a2 2 0 012-2h11a2 2 0 012 2v4" />
            </svg>
        </div>
    </div>
    <x-account.change-indicator :amount="$netChange" variant="hero" />
</div>
