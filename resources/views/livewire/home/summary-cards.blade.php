@php
$cards = [
    [
        'key'   => 'income',
        'label' => 'Total Pemasukan',
        'value' => $summary['income']['current'],
        'change'=> $summary['income']['change'],
        'hasPrev'=> $summary['income']['hasPrev'],
        'good_when_up' => true,
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>',
    ],
    [
        'key'   => 'expense',
        'label' => 'Total Pengeluaran',
        'value' => $summary['expense']['current'],
        'change'=> $summary['expense']['change'],
        'hasPrev'=> $summary['expense']['hasPrev'],
        'good_when_up' => false,
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>',
    ],
    [
        'key'   => 'balance',
        'label' => 'Saldo Bersih',
        'value' => $summary['balance']['current'],
        'change'=> null,
        'hasPrev'=> $summary['balance']['hasPrev'],
        'prevAmount' => $summary['balance']['prevAmount'] ?? 0,
        'good_when_up' => true,
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
    ],
];
@endphp

<div class="grid grid-cols-1 sm:grid-cols-3 gap-5 sm:gap-6">
@foreach($cards as $card)
@php
    $isUp = false;
    $diff = 0;
    if ($card['key'] === 'balance') {
        $diff = $card['value'] - ($card['prevAmount'] ?? 0);
        $isUp = $diff >= 0;
    } elseif ($card['change'] !== null) {
        $isUp = $card['change'] >= 0;
    }
    $isGood = ($card['good_when_up'] && $isUp) || (!$card['good_when_up'] && !$isUp);
    
    // Warna badge persentase/selisih bulan lalu
    if ($isGood && $isUp) {
        $badgeClass = 'bg-emerald-50 text-emerald-700 ring-emerald-600/20';
    } elseif ($isGood && !$isUp) {
        $badgeClass = 'bg-emerald-50 text-emerald-700 ring-emerald-600/20';
    } elseif (!$isGood && $isUp) {
        $badgeClass = 'bg-rose-50 text-rose-700 ring-rose-600/20';
    } else {
        $badgeClass = 'bg-rose-50 text-rose-700 ring-rose-600/20';
    }

    if ($card['key'] === 'expense') {
        $valueStr = 'Rp ' . number_format($card['value'], 0, ',', '.');
    } elseif ($card['key'] === 'balance') {
        $sign = $card['value'] >= 0 ? '' : '-';
        $valueStr = $sign . 'Rp ' . number_format(abs($card['value']), 0, ',', '.');
    } else {
        $valueStr = 'Rp ' . number_format($card['value'], 0, ',', '.');
    }

    // Penentuan styling card berdasarkan tipe (Income/Expense/Balance)
    if ($card['key'] === 'income') {
        $cardBg = 'bg-white rounded-2xl p-5 shadow-sm ring-1 ring-inset ring-gray-100 hover:shadow-md transition-shadow duration-200 flex flex-col justify-between';
        $labelColor = 'text-gray-500';
        $valueColor = 'text-gray-900';
        $iconBg = 'bg-emerald-50 text-emerald-600 ring-1 ring-inset ring-emerald-100/50';
    } elseif ($card['key'] === 'expense') {
        $cardBg = 'bg-white rounded-2xl p-5 shadow-sm ring-1 ring-inset ring-gray-100 hover:shadow-md transition-shadow duration-200 flex flex-col justify-between';
        $labelColor = 'text-gray-500';
        $valueColor = 'text-gray-900';
        $iconBg = 'bg-rose-50 text-rose-600 ring-1 ring-inset ring-rose-100/50';
    } else {
        // Balance (Saldo Bersih)
        $cardBg = 'bg-slate-900 rounded-2xl p-5 shadow-md hover:shadow-lg transition-shadow duration-200 flex flex-col justify-between';
        $labelColor = 'text-slate-300';
        $valueColor = 'text-white';
        $iconBg = 'bg-slate-700/50 text-white border border-slate-600/30';
    }
@endphp

<div class="{{ $cardBg }}">
    <div>
        <div class="flex items-center justify-between gap-3 mb-4">
            <span class="text-xs font-semibold uppercase tracking-wide {{ $labelColor }}">{{ $card['label'] }}</span>
            <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 {{ $iconBg }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    {!! $card['icon'] !!}
                </svg>
            </div>
        </div>

        <div class="text-[28px] font-bold tracking-tight mb-4 truncate leading-none {{ $valueColor }}">{{ $valueStr }}</div>
    </div>

    <div class="flex items-center gap-2.5 min-h-[28px]">
        @if(!$card['hasPrev'])
            <span class="text-xs font-medium italic {{ $card['key'] === 'balance' ? 'text-slate-400' : 'text-gray-400' }}">— Belum ada data bulan lalu</span>
        @elseif($card['key'] === 'balance')
            <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5 rounded-md ring-1 ring-inset bg-white/20 text-white ring-white/30 backdrop-blur-sm">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    {!! $isUp ? '<polyline points="18 15 12 9 6 15"/>' : '<polyline points="6 9 12 15 18 9"/>' !!}
                </svg>
                {{ $isUp ? '+' : '-' }}Rp {{ number_format(abs($diff), 0, ',', '.') }}
            </span>
            <span class="text-xs font-medium text-slate-400">dari bulan lalu</span>
        @elseif($card['change'] === null)
            <span class="text-xs font-medium italic {{ $card['key'] === 'balance' ? 'text-slate-400' : 'text-gray-400' }}">— Belum ada data bulan lalu</span>
        @else
            <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5 rounded-md ring-1 ring-inset {{ $badgeClass }}">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    {!! $isUp ? '<polyline points="18 15 12 9 6 15"/>' : '<polyline points="6 9 12 15 18 9"/>' !!}
                </svg>
                {{ $isUp ? '+' : '' }}{{ $card['change'] }}%
            </span>
            <span class="text-xs font-medium text-gray-500">dari bulan lalu</span>
        @endif
    </div>
</div>
@endforeach
</div>