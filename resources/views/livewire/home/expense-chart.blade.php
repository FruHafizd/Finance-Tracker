<div class="bg-white rounded-2xl p-6 shadow-sm ring-1 ring-inset ring-gray-100">
    <div class="flex items-start justify-between mb-5 gap-3">
        <div>
            <h3 class="text-base font-bold text-gray-900 tracking-tight mb-1">Pengeluaran per Kategori</h3>
            <p class="text-[13px] font-medium text-gray-400">{{ now()->translatedFormat('F Y') }}</p>
        </div>
        @if(count($chartData['data']) > 0)
        <div class="text-right">
            <p class="text-[11px] font-bold tracking-widest uppercase text-gray-400">Total Pengeluaran</p>
            <p class="text-2xl font-bold text-gray-900 mt-0.5 tracking-tight">Rp {{ number_format(array_sum($chartData['data']), 0, ',', '.') }}</p>
        </div>
        @endif
    </div>

    <!-- Divider -->
    <div class="border-t border-gray-100 mb-6"></div>

    @if(count($chartData['data']) > 0)
        <div class="relative h-[320px] w-full">
            <canvas id="expenseChart"></canvas>
        </div>
        <!-- Legend Custom -->
        <div class="mt-5 flex flex-wrap gap-x-5 gap-y-2.5">
            @foreach($chartData['labels'] as $i => $label)
                <div class="flex items-center gap-2 text-[13px] font-semibold text-gray-600">
                    <span class="w-2.5 h-2.5 rounded-sm flex-shrink-0 ring-1 ring-inset shadow-sm" style="background:{{ $chartData['colors'][$i] ?? '#6366f1' }}; border-color: rgba(0,0,0,0.05)"></span>
                    {{ $label }}
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="flex flex-col items-center justify-center py-16 px-4">
            <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mb-4 ring-1 ring-inset ring-gray-100">
                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <p class="text-[15px] font-bold text-gray-900 tracking-tight">Belum ada pengeluaran</p>
            <p class="text-[13px] text-gray-500 mt-1 text-center max-w-xs">Data statistik otomatis akan muncul setelah Anda mencatat transaksi pengeluaran bulan ini.</p>
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