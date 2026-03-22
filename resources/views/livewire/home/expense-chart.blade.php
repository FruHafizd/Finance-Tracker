<style>
.ec-card { background:#fff; border:1px solid #e8eaf0; border-radius:18px; padding:24px; box-shadow:0 1px 4px rgba(0,0,0,.05); font-family:'Plus Jakarta Sans',sans-serif; }
.ec-head { display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:20px; gap:12px; }
.ec-title { font-size:15px; font-weight:700; color:#1f2937; margin:0 0 3px 0; }
.ec-sub { font-size:12px; color:#9ca3af; }
.ec-total-label { font-size:10px; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:#b0b7c3; text-align:right; }
.ec-total-val { font-size:20px; font-weight:800; color:#1f2937; text-align:right; margin-top:2px; }
.ec-divider { height:1px; background:#f3f4f6; margin:0 0 20px; }
.ec-wrap { position:relative; height:320px; }
.ec-legend { margin-top:16px; display:flex; flex-wrap:wrap; gap:8px 16px; }
.ec-legend-item { display:flex; align-items:center; gap:6px; font-size:12px; font-weight:500; color:#6b7280; }
.ec-dot { width:10px; height:10px; border-radius:3px; flex-shrink:0; }
.ec-empty { display:flex; flex-direction:column; align-items:center; justify-content:center; padding:56px 0; gap:8px; }
.ec-empty svg { opacity:.35; }
.ec-empty p { font-size:13px; font-weight:600; color:#9ca3af; margin:0; }
.ec-empty span { font-size:12px; color:#c4cad4; }
</style>

<div class="ec-card">
    <div class="ec-head">
        <div>
            <p class="ec-title">Pengeluaran per Kategori</p>
            <p class="ec-sub">{{ now()->translatedFormat('F Y') }}</p>
        </div>
        @if(count($chartData['data']) > 0)
        <div>
            <p class="ec-total-label">Total</p>
            <p class="ec-total-val">Rp {{ number_format(array_sum($chartData['data']), 0, ',', '.') }}</p>
        </div>
        @endif
    </div>

    <div class="ec-divider"></div>

    @if(count($chartData['data']) > 0)
        <div class="ec-wrap">
            <canvas id="expenseChart"></canvas>
        </div>
        <div class="ec-legend">
            @foreach($chartData['labels'] as $i => $label)
                <div class="ec-legend-item">
                    <span class="ec-dot" style="background:{{ $chartData['colors'][$i] ?? '#6366f1' }}"></span>
                    {{ $label }}
                </div>
            @endforeach
        </div>
    @else
        <div class="ec-empty">
            <svg width="48" height="48" fill="none" stroke="#d1d5db" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <p>Belum ada pengeluaran bulan ini</p>
            <span>Data akan muncul setelah ada transaksi</span>
        </div>
    @endif
</div>

@if(count($chartData['data']) > 0)
<script>
    (function () {
        function initExpenseChart() {
            var canvas = document.getElementById('expenseChart');
            if (!canvas) return;

            // Destroy chart lama jika ada
            if (window._expenseChartInstance) {
                window._expenseChartInstance.destroy();
                window._expenseChartInstance = null;
            }

            window._expenseChartInstance = new Chart(canvas.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: @json($chartData['labels']),
                    datasets: [{
                        data: @json($chartData['data']),
                        backgroundColor: @json(array_map(fn($c) => $c . '30', $chartData['colors'])),
                        borderColor: @json($chartData['colors']),
                        borderWidth: 2,
                        borderRadius: 10,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1f2937',
                            titleColor: '#f9fafb',
                            bodyColor: '#9ca3af',
                            padding: 12,
                            cornerRadius: 10,
                            callbacks: {
                                label: function(ctx) { return '  Rp ' + ctx.parsed.y.toLocaleString('id-ID'); }
                            }
                        }
                    },
                    scales: {
                        x: { grid: { display: false }, border: { display: false }, ticks: { color: '#9ca3af', font: { size: 12 } } },
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f3f4f6' },
                            border: { display: false },
                            ticks: {
                                color: '#9ca3af', font: { size: 11 },
                                callback: function(v) {
                                    if (v >= 1000000) return 'Rp'+(v/1000000).toFixed(1)+'jt';
                                    if (v >= 1000)    return 'Rp'+(v/1000).toFixed(0)+'rb';
                                    return 'Rp'+v;
                                }
                            }
                        }
                    }
                }
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initExpenseChart);
        } else {
            initExpenseChart();
        }

        // Untuk Livewire navigate (bukan re-render biasa)
        document.addEventListener('livewire:navigated', initExpenseChart);
    })();
    </script>
@endif