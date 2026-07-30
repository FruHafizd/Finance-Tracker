<div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 hover:shadow-md transition-all duration-300">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Daftar Rekening</h2>
        <a href="{{ route('account.index') }}" class="text-xs font-semibold text-sky-500 hover:text-sky-600 transition-colors flex items-center gap-1">
            Atur Rekening
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
        </a>
    </div>

    @if($accounts->isEmpty())
        <div class="flex flex-col items-center justify-center py-8 text-center">
            <div class="p-3 bg-slate-50 rounded-full text-slate-400 mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
            </div>
            <p class="text-sm font-medium text-gray-900">Belum ada rekening</p>
            <p class="text-xs text-gray-500 mt-1">Buat rekening untuk memisahkan dana tabungan, cash, dan e-wallet.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach($accounts as $account)
                <div class="flex items-center gap-4 p-4 rounded-xl border border-slate-100 bg-slate-50 hover:bg-slate-100/50 transition-colors">
                    <div class="p-3 rounded-xl text-white font-bold" style="background-color: {{ $account->color ?: '#0EA5E9' }}">
                        @if($account->type === 'tabungan')
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        @elseif($account->type === 'ewallet')
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        @endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-semibold text-slate-400 truncate uppercase tracking-wider">{{ $account->provider }}</p>
                        <p class="text-sm font-bold text-slate-900 truncate">{{ $account->name }}</p>
                        <p class="text-base font-extrabold text-sky-600 mt-1">Rp {{ number_format($account->balance, 0, ',', '.') }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
