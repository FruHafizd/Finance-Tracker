<div class="bg-white rounded-2xl p-6 shadow-sm ring-1 ring-inset ring-gray-100 flex flex-col h-full">
    <div class="flex items-start justify-between mb-6 gap-3">
        <div>
            <h3 class="text-base font-bold text-gray-900 tracking-tight mb-1">Skor Keuangan</h3>
            <p class="text-[13px] font-medium text-gray-400">Analisis kesehatan finansial bulan ini</p>
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
    <div class="mt-6 pt-6 border-t border-gray-100 grid grid-cols-3 gap-4">
        <div class="text-center">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Tabungan</p>
            <p class="text-sm font-bold text-gray-700">{{ $scoreData['breakdown']['savings'] }}</p>
        </div>
        <div class="text-center border-x border-gray-100 px-2">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Anggaran</p>
            <p class="text-sm font-bold text-gray-700">{{ $scoreData['breakdown']['budget'] }}</p>
        </div>
        <div class="text-center">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Aktif</p>
            <p class="text-sm font-bold text-gray-700">{{ $scoreData['breakdown']['consistency'] }}</p>
        </div>
    </div>
</div>
