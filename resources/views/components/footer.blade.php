<footer class="bg-white border-t border-gray-100 pt-16 pb-8 mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Footer Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">

            {{-- Brand Section --}}
            <div class="space-y-6">
                <x-application-logo />
                <p class="text-sm text-gray-500 leading-relaxed max-w-xs">
                    Platform manajemen keuangan cerdas untuk membantu Anda melacak, menganalisis, dan merencanakan masa depan ekonomi yang lebih baik.
                </p>
                <div class="flex items-center gap-4">
                    <a href="https://instagram.com/fruha82" target="_blank" class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400 hover:bg-slate-900 hover:text-white transition-all duration-300">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                    <a href="https://tiktok.com/@fruhaaa" target="_blank" class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400 hover:bg-slate-900 hover:text-white transition-all duration-300">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.27 6.27 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.18 8.18 0 004.78 1.52V6.75a4.85 4.85 0 01-1.01-.06z"/></svg>
                    </a>
                </div>
            </div>

            {{-- Navigasi Cepat --}}
            <div>
                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-widest mb-6">Navigasi</h4>
                <ul class="space-y-4">
                    <li><a href="{{ route('home') }}" class="text-sm text-gray-500 hover:text-slate-900 transition-colors">Dashboard Utama</a></li>
                    <li><a href="{{ route('transaction.index') }}" class="text-sm text-gray-500 hover:text-slate-900 transition-colors">Riwayat Transaksi</a></li>
                    <li><a href="{{ route('budget.index') }}" class="text-sm text-gray-500 hover:text-slate-900 transition-colors">Manajemen Budget</a></li>
                    <li><a href="{{ route('account.index') }}" class="text-sm text-gray-500 hover:text-slate-900 transition-colors">Rekening Saya</a></li>
                </ul>
            </div>

            {{-- Bantuan & Dukungan --}}
            <div>
                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-widest mb-6">Dukungan</h4>
                <ul class="space-y-4">
                    <li>
                        <a href="mailto:muhammadhafizd055@gmail.com" class="group flex items-center gap-3 text-sm text-gray-500 hover:text-slate-900 transition-colors">
                            <span class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-slate-100 group-hover:text-slate-900 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </span>
                            Email Support
                        </a>
                    </li>
                    <li>
                        <a href="#" class="group flex items-center gap-3 text-sm text-gray-500 hover:text-slate-900 transition-colors">
                            <span class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-slate-100 group-hover:text-slate-900 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </span>
                            Pusat Bantuan
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Newsletter / Status --}}
            <div>
                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-widest mb-6">Status Sistem</h4>
                <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100 hover:border-slate-200 transition-all cursor-default">
                    <div class="flex items-center gap-3 text-sm font-semibold text-slate-900 mb-2">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        Semua Sistem Normal
                    </div>
                    <p class="text-[11px] text-gray-400 leading-tight">
                        Terakhir diperbarui: {{ now()->format('H:i') }} WIB. Kami terus memantau performa untuk kenyamanan Anda.
                    </p>
                </div>
            </div>

        </div>

        {{-- Footer Bottom --}}
        <div class="pt-8 border-t border-gray-50 flex flex-col sm:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-6 text-xs text-gray-400">
                <span>© {{ date('Y') }} {{ config('app.name') }}. Hak cipta dilindungi.</span>
                <a href="{{ route('legal.privacy') }}" class="hover:text-slate-900 transition-colors">Kebijakan Privasi</a>
                <a href="{{ route('legal.terms') }}" class="hover:text-slate-900 transition-colors">Syarat & Ketentuan</a>
            </div>
            <div class="flex items-center gap-2 text-xs text-gray-300 group cursor-default">
                Didesain dengan
                <svg class="w-3 h-3 text-red-400 group-hover:scale-125 transition-transform" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/></svg>
                di Indonesia
            </div>
        </div>

    </div>
</footer>