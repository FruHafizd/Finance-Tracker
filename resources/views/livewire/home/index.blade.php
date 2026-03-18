<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Beranda') }}
        </h2>
    </x-slot>

    <div class="max-w-6xl mx-auto py-6 px-4">

        {{-- FILTER --}}
        <div class="flex items-center gap-3 mb-5 flex-wrap">
            <span class="text-sm text-gray-500">Periode</span>

            <input type="date" wire:model.live="startDate"
                class="h-9 border border-gray-200 rounded-lg px-3 text-sm">

            <div class="w-4 h-px bg-gray-300"></div>

            <input type="date" wire:model.live="endDate"
                class="h-9 border border-gray-200 rounded-lg px-3 text-sm">
        </div>

        {{-- GRID --}}
        <div class="grid grid-cols-1 lg:grid-cols-[1fr_300px] gap-4">

            {{-- CHART --}}
            <div class="bg-white border rounded-xl p-5">
                <p class="text-xs text-gray-400 mb-4 uppercase">
                    Pengeluaran per Kategori
                </p>

                <div class="h-64">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>

            {{-- STATISTIK --}}
            <div class="bg-white border rounded-xl p-5">

                <p class="text-xs text-gray-400 mb-4 uppercase">
                    Ringkasan
                </p>

                @if (empty($chartData['labels']))
                    <p class="text-sm text-gray-400">Tidak ada data</p>
                @else
                    @php
                        $total = array_sum($chartData['values'] ?? []);
                    @endphp

                    <div class="space-y-3">
                        @foreach ($chartData['labels'] as $i => $label)
                            @php
                                $val = $chartData['values'][$i] ?? 0;
                                $color = $chartData['colors'][$i] ?? '#6366f1';
                                $pct = $total > 0 ? round(($val / $total) * 100, 1) : 0;
                            @endphp

                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded"
                                      style="background: {{ $color }}"></span>

                                <div class="flex-1 text-sm truncate">
                                    {{ $label }}
                                </div>

                                <div class="text-xs text-gray-500">
                                    Rp {{ number_format($val, 0, ',', '.') }}
                                    ({{ $pct }}%)
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t mt-3 pt-3 text-sm font-semibold">
                        Total: Rp {{ number_format($total, 0, ',', '.') }}
                    </div>
                @endif

            </div>
        </div>
    </div>

<script>
    let chart;

    function initChart() {
        const ctx = document.getElementById('categoryChart');
        if (!ctx) return;

        chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    data: [],
                    backgroundColor: [],
                    borderRadius: 6,
                    barPercentage: 0.6
                }]
            },
            options: {
                indexAxis: 'y', // 🔥 bikin horizontal
                responsive: true,
                maintainAspectRatio: false,

                layout: {
                    padding: 10
                },

                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => {
                                return 'Rp ' + ctx.raw.toLocaleString('id-ID');
                            }
                        }
                    }
                },

                scales: {
                    x: {
                        grid: {
                            color: '#F3F4F6'
                        },
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000) return 'Rp ' + (value / 1000000) + 'jt';
                                if (value >= 1000) return 'Rp ' + (value / 1000) + 'rb';
                                return value;
                            }
                        }
                    },
                    y: {
                        grid: { display: false },
                        ticks: {
                            callback: function(value) {
                                let label = this.getLabelForValue(value);
                                return label.length > 15
                                    ? label.substring(0, 15) + '...'
                                    : label;
                            }
                        }
                    }
                }
            }
        });
    }

    function updateChart(labels, values, colors) {
        if (!chart) return;

        // 🔥 potong label panjang di sini juga (double safety)
        const cleanLabels = labels.map(l =>
            l.length > 20 ? l.substring(0, 20) + '...' : l
        );

        chart.data.labels = cleanLabels;
        chart.data.datasets[0].data = values;
        chart.data.datasets[0].backgroundColor = colors;

        chart.update();
    }

    document.addEventListener('livewire:init', () => {
        initChart();

        Livewire.on('update-chart', (data) => {
            updateChart(data.labels, data.values, data.colors);
        });
    });
</script>
</div>
