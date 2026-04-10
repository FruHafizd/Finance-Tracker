<div class="bg-white rounded-2xl p-6 shadow-sm ring-1 ring-inset ring-gray-100 flex flex-col h-full">
    <div class="flex items-start justify-between mb-6 gap-3">
        <div>
            <h3 class="text-base font-bold text-gray-900 tracking-tight mb-1">Skor Keuangan</h3>
            <div class="flex items-center gap-1.5 group cursor-help">
                <p class="text-[13px] font-medium text-gray-400">Analisis aktivitas bulan ini</p>
                <div class="relative">
                    <svg class="w-3.5 h-3.5 text-gray-300 group-hover:text-gray-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <!-- Tooltip -->
                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-48 p-2 bg-gray-900 text-white text-[10px] rounded-lg opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 text-center shadow-xl">
                        Skor ini dihitung berdasarkan kebiasaan menabung, ketaatan anggaran, dan aktivitas pencatatan Anda selama bulan berjalan.
                        <div class="absolute top-full left-1/2 -translate-x-1/2 border-8 border-transparent border-t-gray-900"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 p-2 rounded-xl ring-1 ring-inset ring-gray-100">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
    </div>

    <div class="flex-1 flex flex-col items-center justify-center py-4">
        <!-- Circular Progress Gauge -->
        <div class="relative flex items-center justify-center">
            <svg class="w-40 h-40 transform -rotate-90">
                <!-- Background Circle -->
                <circle
                    cx="80" cy="80" r="70"
                    stroke="currentColor"
                    stroke-width="12"
                    fill="transparent"
                    class="text-gray-100"
                />
                <!-- Progress Circle -->
                <circle
                    cx="80" cy="80" r="70"
                    stroke="{{ $scoreData['color'] }}"
                    stroke-width="12"
                    fill="transparent"
                    stroke-dasharray="440"
                    stroke-dashoffset="{{ 440 - (440 * $scoreData['total']) / 100 }}"
                    stroke-linecap="round"
                    class="transition-all duration-1000 ease-out"
                />
            </svg>
            <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                <span class="text-4xl font-black tracking-tighter text-gray-900">{{ $scoreData['total'] }}</span>
                <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Poin</span>
            </div>
        </div>

        <div class="mt-6 text-center">
            <p class="text-[15px] font-bold text-gray-900 tracking-tight">{{ $scoreData['status'] }}</p>
            <div class="mt-2 flex items-center justify-center gap-1.5">
                <span class="w-2 h-2 rounded-full" style="background-color: {{ $scoreData['color'] }}"></span>
                <span class="text-[13px] font-medium text-gray-500">Status: {{ $scoreData['total'] > 75 ? 'Optimal' : ($scoreData['total'] > 50 ? 'Waspada' : 'Bahaya') }}</span>
            </div>
        </div>
    </div>

    <!-- Breakdown Stats -->
    <div class="mt-6 pt-6 border-t border-gray-100">
        @if($scoreData['has_transactions'])
            <div class="grid grid-cols-3 gap-4">
                <div class="text-center">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Simpanan</p>
                    <p class="text-sm font-bold text-gray-700">{{ $scoreData['breakdown']['savings'] }}</p>
                </div>
                <div class="text-center border-x border-gray-100 px-2">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Anggaran</p>
                    <p class="text-sm font-bold text-gray-700">{{ $scoreData['breakdown']['budget'] }}</p>
                </div>
                <div class="text-center">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Aktivitas</p>
                    <p class="text-sm font-bold text-gray-700">{{ $scoreData['breakdown']['consistency'] }}</p>
                </div>
            </div>
        @else
            <div class="flex flex-col items-center justify-center text-center px-4 py-2">
                <p class="text-[11px] font-semibold text-gray-400 leading-relaxed italic">
                    Catat pemasukan dan pengeluaran pertamamu di bulan ini untuk melihat analisis skor finansialmu.
                </p>
            </div>
        @endif
    </div>
</div>
