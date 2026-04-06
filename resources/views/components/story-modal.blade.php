@props(['reviewData'])

<div 
    x-data="{ 
        activeSlide: 1, 
        maxSlide: 5,
        complete() {
            $wire.markReviewAsSeen();
        }
    }" 
    x-show="$wire.showReview"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 pb-12"
    x-transition:enter-end="opacity-100 pb-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 pb-0"
    x-transition:leave-end="opacity-0 pb-12"
    class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/40 backdrop-blur-md p-4 sm:p-6"
    style="display: none;"
>
    <!-- Main Card Container -->
    <div class="relative w-full max-w-md bg-white rounded-[2.5rem] shadow-[0_32px_64px_-12px_rgba(0,0,0,0.14)] overflow-hidden flex flex-col h-[85vh] sm:h-[600px] border border-gray-100">
        
        <!-- Interactive Navigation Overlays (Inside Card) -->
        <div class="absolute inset-x-0 bottom-0 top-16 flex z-10">
            <div @click="activeSlide > 1 ? activeSlide-- : null" class="w-1/2 h-full cursor-w-resize"></div>
            <div @click="activeSlide < maxSlide ? activeSlide++ : complete()" class="w-1/2 h-full cursor-e-resize"></div>
        </div>

        <!-- Progress Indicator -->
        <div class="absolute top-6 inset-x-8 flex gap-1.5 z-20">
            <template x-for="i in maxSlide" :key="i">
                <div class="h-1 flex-1 bg-gray-100 rounded-full overflow-hidden">
                    <div 
                        class="h-full bg-indigo-600 transition-all duration-300 ease-out"
                        :style="activeSlide >= i ? 'width: 100%' : 'width: 0%'"
                    ></div>
                </div>
            </template>
        </div>

        <!-- Close Button -->
        <button @click="complete()" class="absolute top-10 right-8 z-30 text-gray-400 hover:text-gray-600 p-2 bg-gray-50 rounded-full transition-all hover:rotate-90">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Slides Content Container -->
        <div class="flex-1 flex flex-col p-8 pt-20 relative z-0">
            
            <!-- Slide 1: Welcome -->
            <div x-show="activeSlide === 1" x-transition.opacity.duration.500ms class="flex-1 flex flex-col items-center justify-center text-center space-y-6">
                <div class="w-24 h-24 bg-indigo-50 text-indigo-600 rounded-3xl flex items-center justify-center shadow-inner mb-2 transform -rotate-3">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div class="space-y-2">
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight leading-tight">Halo, {{ Auth::user()->name }}!</h2>
                    <p class="text-gray-500 font-medium px-4">Bulan {{ $reviewData['month_name'] }} telah usai. Bagaimana kondisimu?</p>
                </div>
                <div class="pt-8">
                    <span class="inline-flex items-center gap-2 px-6 py-3 bg-gray-900 text-white rounded-2xl text-sm font-bold shadow-lg shadow-gray-200">
                        Lihat Rekapanmu
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="3"/></svg>
                    </span>
                </div>
            </div>

            <!-- Slide 2: Stats Grid -->
            <div x-show="activeSlide === 2" x-transition.opacity.duration.500ms class="flex-1 flex flex-col justify-center space-y-8">
                <h3 class="text-xl font-bold text-gray-900 text-center mb-4">Arus Kasmu</h3>
                
                <div class="space-y-4">
                    <div class="bg-emerald-50 rounded-2xl p-5 border border-emerald-100 flex items-center gap-4">
                        <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-emerald-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 11l5-5m0 0l5 5m-5-5v12" stroke-width="2.5"/></svg>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase font-bold tracking-widest text-emerald-600/70">Pemasukan</p>
                            <p class="text-xl font-black text-emerald-900">Rp {{ number_format($reviewData['total_income'], 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="bg-rose-50 rounded-2xl p-5 border border-rose-100 flex items-center gap-4">
                        <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-rose-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 13l-5 5m0 0l-5-5m5 5V6" stroke-width="2.5"/></svg>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase font-bold tracking-widest text-rose-600/70">Pengeluaran</p>
                            <p class="text-xl font-black text-rose-900">Rp {{ number_format($reviewData['total_expense'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-gray-50 rounded-2xl text-center">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Sisa Saldo</p>
                    <p class="text-2xl font-black text-gray-900 italic">Rp {{ number_format($reviewData['net_saving'], 0, ',', '.') }}</p>
                </div>
            </div>

            <!-- Slide 3: Category -->
            <div x-show="activeSlide === 3" x-transition.opacity.duration.500ms class="flex-1 flex flex-col items-center justify-center text-center">
                <p class="text-gray-400 font-bold text-xs uppercase tracking-[0.2em] mb-4">Top Spending</p>
                <div class="mb-8 relative">
                    <div class="absolute inset-0 bg-indigo-600/10 blur-3xl rounded-full"></div>
                    <div class="relative w-32 h-32 bg-white rounded-full flex items-center justify-center border-8 border-indigo-50 shadow-xl overflow-hidden">
                        <span class="text-6xl group-hover:scale-110 transition-transform">📉</span>
                    </div>
                </div>
                <h3 class="text-4xl font-black text-gray-900 tracking-tight mb-2">
                    {{ $reviewData['top_category_name'] }}
                </h3>
                <p class="text-sm text-gray-500 font-medium max-w-[200px]">Adalah kategori yang paling banyak menguras kantongmu.</p>
            </div>

            <!-- Slide 4: Analogy -->
            <div x-show="activeSlide === 4" x-transition.opacity.duration.500ms class="flex-1 flex flex-col items-center justify-center">
                <div class="w-full bg-slate-900 text-white rounded-3xl p-8 relative overflow-hidden shadow-2xl">
                    <svg class="absolute top-4 left-4 w-12 h-12 text-white/10" fill="currentColor" viewBox="0 0 32 32">
                        <path d="M10 8c-3.3 0-6 2.7-6 6v10h10V14H7c0-1.7 1.3-3 3-3V8zm12 0c-3.3 0-6 2.7-6 6v10h10V14h-7c0-1.7 1.3-3 3-3V8z"/>
                    </svg>
                    <p class="relative z-10 text-xl md:text-2xl font-black leading-tight tracking-tight mb-4">
                        "{{ $reviewData['analogy'] }}"
                    </p>
                    <hr class="border-white/20 mb-4">
                    <p class="text-indigo-400 font-bold text-xs uppercase tracking-widest">Financial Insight</p>
                </div>
            </div>

            <!-- Slide 5: CTA -->
            <div x-show="activeSlide === 5" x-transition.opacity.duration.500ms class="flex-1 flex flex-col items-center justify-center text-center space-y-8">
                <div class="p-8 bg-indigo-600 rounded-[2rem] text-white shadow-2xl shadow-indigo-200">
                    <h2 class="text-3xl font-black tracking-tight mb-3">Siap Untuk Bulan Ini?</h2>
                    <p class="text-indigo-50 text-sm font-medium leading-relaxed opacity-90">Jadikan bulan ini lebih baik. Yuk, mulai catat transaksi pertamamu hari ini!</p>
                </div>
                
                <button @click="complete()" class="w-full py-5 bg-gray-900 text-white rounded-[1.5rem] font-black text-lg hover:bg-gray-800 transition-all shadow-xl active:scale-95 group">
                    SIAP, MULAI SEKARANG
                    <svg class="inline-block ml-2 w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 8l4 4m0 0l-4 4m4-4H3" stroke-width="2.5"/></svg>
                </button>
            </div>

        </div>

        <!-- Footer Hint -->
        <div class="px-8 py-6 bg-gray-50/50 border-t border-gray-100/50 text-center pointer-events-none">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Tap sisi layar untuk navigasi</p>
        </div>

    </div>
</div>
