<footer class="bg-white border-t border-gray-100 pt-8 pb-5 mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Footer Top --}}
        <div class="flex flex-col sm:flex-row justify-between gap-8 mb-7">

            {{-- Brand --}}
            <div class="flex items-start gap-3">
                <div>
                    <p class="text-sm font-semibold text-gray-800">{{ config('app.name') }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Kelola keuangan dengan mudah</p>
                </div>
            </div>

            {{-- Links --}}
            <div class="flex gap-10 flex-wrap">

                {{-- Navigasi --}}
                <div>
                    <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3">Navigasi</h4>
                    <a href="{{ route('home') }}" class="block text-sm text-gray-500 hover:text-violet-600 mb-2 transition-colors">Beranda</a>
                    <a href="{{ route('transaction.index') }}" class="block text-sm text-gray-500 hover:text-violet-600 mb-2 transition-colors">Riwayat Transaksi</a>
                    <a href="{{ route('budget.index') }}" class="block text-sm text-gray-500 hover:text-violet-600 transition-colors">Budget</a>
                </div>

                {{-- Bantuan --}}
                <div>
                    <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3">Bantuan</h4>
                    <a href="mailto:muhammadhafizd055@gmail.com"
                       class="flex items-center gap-2 text-sm text-gray-500 hover:text-violet-600 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        muhammadhafizd055@gmail.com
                    </a>
                </div>

                {{-- Social Media --}}
                <div>
                    <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3">Ikuti Kami</h4>
                    <a href="https://instagram.com/fruha82" target="_blank"
                       class="flex items-center gap-2 text-sm text-gray-500 hover:text-violet-600 mb-2 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                        @fruha82
                    </a>
                    <a href="https://tiktok.com/@fruhaaa" target="_blank"
                       class="flex items-center gap-2 text-sm text-gray-500 hover:text-violet-600 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.27 6.27 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.18 8.18 0 004.78 1.52V6.75a4.85 4.85 0 01-1.01-.06z"/>
                        </svg>
                        @fruhaaa
                    </a>
                </div>

            </div>
        </div>

        {{-- Footer Bottom --}}
        <div class="border-t border-gray-50 pt-4 flex flex-col sm:flex-row justify-between items-center gap-2">
            <span class="text-xs text-gray-300">© {{ date('Y') }} {{ config('app.name') }}. Hak cipta dilindungi.</span>
        </div>

    </div>
</footer>