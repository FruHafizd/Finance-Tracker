<div class="max-w-6xl mx-auto py-6 px-4">

        {{-- FILTER BAR --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-5 py-4 mb-6 flex items-center gap-3 flex-wrap">
            <div class="flex items-center gap-2 text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="text-sm font-medium text-gray-500">Periode</span>
            </div>

            <input
                type="date"
                wire:model.live="startDate"
                class="h-9 border border-gray-200 rounded-lg px-3 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition"
            />

            <span class="text-gray-300 font-medium">—</span>

            <input
                type="date"
                wire:model.live="endDate"
                class="h-9 border border-gray-200 rounded-lg px-3 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition"
            />

            <span class="ml-auto text-xs text-gray-400">
                Data diperbarui otomatis
            </span>
        </div>

        {{-- CHART CARD --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

            {{-- Header chart --}}
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h3 class="text-base font-semibold text-gray-800">Pengeluaran per Kategori</h3>
                    <p class="text-sm text-gray-400 mt-0.5">
                        {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }}
                        –
                        {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}
                    </p>
                </div>

                {{-- Total --}}
                <div class="text-right">
                    <p class="text-xs text-gray-400 uppercase tracking-wide">Total</p>
                    <p class="text-lg font-bold text-gray-800">
                        Rp {{ number_format(array_sum($chartData['data']), 0, ',', '.') }}
                    </p>
                </div>
            </div>

            {{-- Canvas --}}
            @if (count($chartData['data']) > 0)
                <div class="relative" style="height: 340px;">
                    <canvas id="expenseChart"></canvas>
                </div>

                {{-- Legend manual --}}
                <div class="mt-5 flex flex-wrap gap-3">
                    @foreach ($chartData['labels'] as $i => $label)
                        <div class="flex items-center gap-1.5 text-sm text-gray-600">
                            <span class="inline-block w-3 h-3 rounded-full"
                                style="background-color: {{ $chartData['colors'][$i] ?? '#6366f1' }}">
                            </span>
                            {{ $label }}
                        </div>
                    @endforeach
                </div>
            @else
                {{-- Empty state --}}
                <div class="flex flex-col items-center justify-center py-16 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mb-3 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <p class="text-sm font-medium">Tidak ada data pengeluaran</p>
                    <p class="text-xs mt-1">Coba ubah rentang tanggal</p>
                </div>
            @endif
        </div>
    </div>

    @script
    <script>
        let chartInstance = null;

        function renderChart(labels, data, colors) {
            const canvas = document.getElementById('expenseChart');
            if (!canvas) return;

            const ctx = canvas.getContext('2d');

            if (chartInstance) {
                chartInstance.destroy();
            }

            chartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Pengeluaran (Rp)',
                        data: data,
                        backgroundColor: colors.map(c => c + 'cc'), 
                        borderColor: colors,
                        borderWidth: 2,
                        borderRadius: 8,
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
                            bodyColor: '#d1d5db',
                            padding: 12,
                            cornerRadius: 8,
                            callbacks: {
                                label: (ctx) => {
                                    return '  Rp ' + ctx.parsed.y.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: {
                                color: '#9ca3af',
                                font: { size: 12 }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f3f4f6',
                            },
                            ticks: {
                                color: '#9ca3af',
                                font: { size: 12 },
                                callback: (value) => {
                                    if (value >= 1_000_000) return 'Rp ' + (value / 1_000_000).toFixed(1) + 'jt';
                                    if (value >= 1_000) return 'Rp ' + (value / 1_000).toFixed(0) + 'rb';
                                    return 'Rp ' + value;
                                }
                            }
                        }
                    }
                }
            });
        }

        // Render awal
        renderChart(
            @json($chartData['labels']),
            @json($chartData['data']),
            @json($chartData['colors'])
        );

        // Update saat filter berubah
        Livewire.on('chartUpdated', ({ labels, data, colors }) => {
            renderChart(labels, data, colors);
        });
    </script>
    @endscript