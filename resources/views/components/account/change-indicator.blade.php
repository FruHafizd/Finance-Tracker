@props(['amount', 'variant'])

@if($variant === 'hero')
    @if(($amount ?? 0) > 0)
        <div class="inline-flex items-center gap-1.5 mt-3 px-2 py-0.5 rounded-lg bg-white/10 relative z-10">
            <svg class="w-3 h-3 text-emerald-300" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L10 6.414l-3.293 3.293a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
            </svg>
            <span class="text-[11px] text-emerald-300 font-bold">
                +Rp {{ number_format(abs($amount), 0, ',', '.') }}
            </span>
            <span class="text-[10px] text-white/70">bulan ini</span>
        </div>
    @elseif(($amount ?? 0) < 0)
        <div class="inline-flex items-center gap-1.5 mt-3 px-2 py-0.5 rounded-lg bg-white/10 relative z-10">
            <svg class="w-3 h-3 text-rose-300" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L10 13.586l3.293-3.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
            </svg>
            <span class="text-[11px] text-rose-300 font-bold">
                -Rp {{ number_format(abs($amount), 0, ',', '.') }}
            </span>
            <span class="text-[10px] text-white/70">bulan ini</span>
        </div>
    @else
        <div class="inline-flex items-center gap-1.5 mt-3 px-2 py-0.5 rounded-lg bg-white/5 relative z-10">
            <span class="text-[10px] text-white/70 font-semibold">
                — Tidak berubah bulan ini
            </span>
        </div>
    @endif
@elseif($variant === 'card')
    @if($amount > 0)
        <div class="flex items-center gap-1 mt-1.5">
            <svg class="w-3 h-3 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L10 6.414l-3.293 3.293a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
            </svg>
            <span class="text-xs text-emerald-600 font-medium">
                +Rp {{ number_format(abs($amount), 0, ',', '.') }}
            </span>
            <span class="text-[10px] text-text-muted">bulan ini</span>
        </div>
    @elseif($amount < 0)
        <div class="flex items-center gap-1 mt-1.5">
            <svg class="w-3 h-3 text-danger" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L10 13.586l3.293-3.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
            </svg>
            <span class="text-xs text-danger font-medium">
                -Rp {{ number_format(abs($amount), 0, ',', '.') }}
            </span>
            <span class="text-[10px] text-text-muted">bulan ini</span>
        </div>
    @else
        <div class="flex items-center gap-1 mt-1.5">
            <span class="text-xs text-text-muted font-medium">
                — Tidak berubah bulan ini
            </span>
        </div>
    @endif
@elseif($variant === 'row')
    @if(($amount ?? 0) > 0)
        <span class="text-[11px] text-emerald-600 font-bold flex items-center justify-end gap-0.5 mt-1">
            <svg class="w-3 h-3 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L10 6.414l-3.293 3.293a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
            </svg>
            +Rp {{ number_format(abs($amount), 0, ',', '.') }}
        </span>
    @elseif(($amount ?? 0) < 0)
        <span class="text-[11px] text-danger font-bold flex items-center justify-end gap-0.5 mt-1">
            <svg class="w-3 h-3 text-danger" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L10 13.586l3.293-3.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
            </svg>
            -Rp {{ number_format(abs($amount), 0, ',', '.') }}
        </span>
    @else
        <span class="text-[11px] text-text-muted font-bold flex items-center justify-end gap-0.5 mt-1">
            — Tidak berubah
        </span>
    @endif
@endif
