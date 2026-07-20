@props(['chartData', 'totalLimit'])

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
