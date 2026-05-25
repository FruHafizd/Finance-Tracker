<div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 hover:shadow-md transition-all duration-300">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Transaksi Terbaru</h2>
        <a href="{{ route('transaction.index') }}" class="text-xs font-semibold text-sky-500 hover:text-sky-600 transition-colors flex items-center gap-1">
            Lihat Semua
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
        </a>
    </div>

    @if($transactions->isEmpty())
        <div class="flex flex-col items-center justify-center py-8 text-center">
            <div class="p-3 bg-slate-50 rounded-full text-slate-400 mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <p class="text-sm font-medium text-gray-900">Belum ada transaksi</p>
            <p class="text-xs text-gray-500 mt-1">Yuk, mulai catat transaksi pertamamu!</p>
        </div>
    @else
        <div class="divide-y divide-gray-100">
            @foreach($transactions as $transaction)
                <div class="flex items-center justify-between py-3 first:pt-0 last:pb-0">
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-xl text-xs font-semibold" style="background-color: {{ $transaction->category->color ?? '#F1F5F9' }}20; color: {{ $transaction->category->color ?? '#64748B' }}">
                            @if($transaction->type === 'income')
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                </svg>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-950">{{ $transaction->description ?: ($transaction->category->name ?? 'Tanpa Kategori') }}</p>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="text-xs text-gray-500 font-medium">{{ $transaction->account->name ?? 'Tanpa Rekening' }}</span>
                                <span class="text-xs text-gray-300">•</span>
                                <span class="text-xs text-gray-400">{{ $transaction->date->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold {{ $transaction->type === 'income' ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ $transaction->type === 'income' ? '+' : '-' }}Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
