@php
$cards = [
    [
        'key'   => 'income',
        'label' => 'Total Pemasukan',
        'value' => $summary['income']['current'],
        'change'=> $summary['income']['change'],
        'hasPrev'=> $summary['income']['hasPrev'],
        'good_when_up' => true,
        'sign'  => '',
        'bg'    => '#f0fdf7', 'border' => '#d1fae5',
        'icon_bg' => '#d1fae5', 'icon_color' => '#059669',
        'label_color' => '#065f46', 'value_color' => '#064e3b',
        'icon' => '<polyline points="18 15 12 9 6 15"/>',
    ],
    [
        'key'   => 'expense',
        'label' => 'Total Pengeluaran',
        'value' => $summary['expense']['current'],
        'change'=> $summary['expense']['change'],
        'hasPrev'=> $summary['expense']['hasPrev'],
        'good_when_up' => false,
        'sign'  => '-',
        'bg'    => '#fff1f2', 'border' => '#fecdd3',
        'icon_bg' => '#fecdd3', 'icon_color' => '#e11d48',
        'label_color' => '#881337', 'value_color' => '#4c0519',
        'icon' => '<polyline points="6 9 12 15 18 9"/>',
    ],
    [
        'key'   => 'balance',
        'label' => 'Saldo Bersih',
        'value' => $summary['balance']['current'],
        'change'=> null,
        'hasPrev'=> $summary['balance']['hasPrev'],
        'prevAmount' => $summary['balance']['prevAmount'] ?? 0,
        'good_when_up' => true,
        'sign'  => '',
        'bg'    => '#eef2ff', 'border' => '#c7d2fe',
        'icon_bg' => '#c7d2fe', 'icon_color' => '#4f46e5',
        'label_color' => '#312e81', 'value_color' => '#1e1b4b',
        'icon' => '<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>',
    ],
];
@endphp

<style>
.sc-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; }
@media(max-width:640px){ .sc-grid{ grid-template-columns:1fr; } }
.sc-card { border-radius:18px; border:1px solid; padding:20px 22px; display:flex; flex-direction:column; gap:12px; transition:transform .2s,box-shadow .2s; }
.sc-card:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(0,0,0,.08); }
.sc-row { display:flex; align-items:center; justify-content:space-between; }
.sc-icon { width:38px; height:38px; border-radius:11px; display:flex; align-items:center; justify-content:center; }
.sc-label { font-size:11px; font-weight:700; letter-spacing:.1em; text-transform:uppercase; }
.sc-value { font-size:26px; font-weight:800; letter-spacing:-.02em; line-height:1.1; }
.sc-divider { height:1px; background:rgba(0,0,0,.06); }
.sc-footer { display:flex; align-items:center; gap:7px; min-height:22px; }
.sc-badge { display:inline-flex; align-items:center; gap:4px; font-size:12px; font-weight:600; padding:2px 8px; border-radius:20px; }
.sc-badge-up-good   { background:rgba(16,185,129,.12); color:#059669; }
.sc-badge-down-good { background:rgba(244,63,94,.12);  color:#e11d48; }
.sc-badge-up-bad    { background:rgba(244,63,94,.12);  color:#e11d48; }
.sc-badge-down-bad  { background:rgba(16,185,129,.12); color:#059669; }
.sc-meta { font-size:12px; color:#9ca3af; }
.sc-no-prev { font-size:12px; color:#c4cad4; font-style:italic; }
</style>

<div class="sc-grid">
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
    $badgeClass = $isUp ? ($isGood ? 'sc-badge-up-good' : 'sc-badge-up-bad') : ($isGood ? 'sc-badge-down-bad' : 'sc-badge-down-good');

    if ($card['key'] === 'expense') {
        $valueStr = '-Rp ' . number_format($card['value'], 0, ',', '.');
    } elseif ($card['key'] === 'balance') {
        $sign = $card['value'] >= 0 ? '+' : '-';
        $valueStr = $sign . 'Rp ' . number_format(abs($card['value']), 0, ',', '.');
    } else {
        $valueStr = 'Rp ' . number_format($card['value'], 0, ',', '.');
    }
@endphp

<div class="sc-card" style="background:{{ $card['bg'] }};border-color:{{ $card['border'] }};">
    <div class="sc-row">
        <span class="sc-label" style="color:{{ $card['label_color'] }}">{{ $card['label'] }}</span>
        <div class="sc-icon" style="background:{{ $card['icon_bg'] }};color:{{ $card['icon_color'] }}">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                {!! $card['icon'] !!}
            </svg>
        </div>
    </div>

    <div class="sc-divider"></div>
    <div class="sc-value" style="color:{{ $card['value_color'] }}">{{ $valueStr }}</div>

    <div class="sc-footer">
        @if(!$card['hasPrev'])
            <span class="sc-no-prev">— Belum ada data bulan lalu</span>
        @elseif($card['key'] === 'balance')
            <span class="sc-badge {{ $badgeClass }}">
                <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    {!! $isUp ? '<polyline points="18 15 12 9 6 15"/>' : '<polyline points="6 9 12 15 18 9"/>' !!}
                </svg>
                {{ $isUp ? '+' : '-' }}Rp {{ number_format(abs($diff), 0, ',', '.') }}
            </span>
            <span class="sc-meta">dari bulan lalu</span>
        @elseif($card['change'] === null)
            <span class="sc-no-prev">— Belum ada data bulan lalu</span>
        @else
            <span class="sc-badge {{ $badgeClass }}">
                <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    {!! $isUp ? '<polyline points="18 15 12 9 6 15"/>' : '<polyline points="6 9 12 15 18 9"/>' !!}
                </svg>
                {{ $isUp ? '+' : '' }}{{ $card['change'] }}%
            </span>
            <span class="sc-meta">dari bulan lalu</span>
        @endif
    </div>
</div>
@endforeach
</div>