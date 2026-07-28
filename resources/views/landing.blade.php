<x-layouts.app>
@section('title', 'Finansiku — Kelola Keuangan Pribadi Lebih Cerdas & Simpel')
@section('description', 'Finansiku mencatat setiap pemasukan dan pengeluaranmu otomatis, lengkap dengan budget, kalender tagihan, dan bot Telegram. Gratis, tanpa ribet.')

{{-- ================= NAVBAR ================= --}}
<nav class="sticky top-0 z-50 bg-bg/85 backdrop-blur border-b border-border">
    <input type="checkbox" id="mnav-toggle" class="peer hidden">

    <div class="max-w-[1120px] mx-auto px-6 h-[72px] flex items-center justify-between">
        <a href="{{ route('landing') }}" class="flex items-center gap-2.5 font-extrabold text-lg">
            {{-- Ganti src ini dengan file logo Finansiku kamu, taruh di public/images/logo.png --}}
            <img src="{{ asset('images/logo.png') }}" alt="Logo Finansiku" class="w-8 h-8 object-contain">
            Finansiku
        </a>

        <div class="hidden md:flex gap-8 text-sm font-semibold text-text-muted">
            <a href="#fitur" class="hover:text-text">Fitur</a>
            <a href="#cara-kerja" class="hover:text-text">Cara Kerja</a>
            <a href="#faq" class="hover:text-text">FAQ</a>
        </div>

        <div class="hidden md:flex items-center gap-4">
            <a href="{{ route('login') }}" class="px-5 py-3 text-sm font-semibold text-text-muted hover:text-text">Masuk</a>
            <a href="{{ route('register') }}" class="px-5 py-3 rounded-full text-sm font-bold bg-primary text-white hover:bg-primary-hover transition">Mulai Gratis</a>
        </div>

        {{-- Mobile menu toggle — pakai checkbox hack, tidak butuh Alpine/JS --}}
        <label for="mnav-toggle" class="md:hidden w-11 h-11 flex flex-col justify-center items-center gap-1.5 cursor-pointer" aria-label="Buka menu">
            <span class="w-5.5 h-0.5 bg-text rounded transition-transform peer-checked:rotate-45"></span>
            <span class="w-5.5 h-0.5 bg-text rounded"></span>
            <span class="w-5.5 h-0.5 bg-text rounded"></span>
        </label>
    </div>

    <div class="hidden peer-checked:flex md:!hidden flex-col gap-1 border-t border-border px-6 py-4 bg-bg">
        <a href="#fitur" class="px-2 py-3 font-semibold rounded-lg hover:bg-primary-light hover:text-primary-hover">Fitur</a>
        <a href="#cara-kerja" class="px-2 py-3 font-semibold rounded-lg hover:bg-primary-light hover:text-primary-hover">Cara Kerja</a>
        <a href="#faq" class="px-2 py-3 font-semibold rounded-lg hover:bg-primary-light hover:text-primary-hover">FAQ</a>
        <a href="{{ route('login') }}" class="px-2 py-3 font-semibold">Masuk</a>
        <a href="{{ route('register') }}" class="mt-1 px-5 py-3 rounded-full text-center font-bold bg-primary text-white">Mulai Gratis</a>
    </div>
</nav>

{{-- ================= HERO ================= --}}
<header class="pt-16 pb-10">
    <div class="max-w-[1120px] mx-auto px-6 grid md:grid-cols-2 gap-14 items-center">
        <div>
            <span class="inline-flex items-center gap-2 bg-primary-light text-primary-hover font-bold text-xs uppercase tracking-wide px-3.5 py-1.5 rounded-full mb-5">
                <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                Dipakai buat Ngatur Duit Sehari-hari
            </span>
            <h1 class="text-4xl md:text-5xl font-extrabold leading-tight tracking-tight mb-5">
                Uang bulanan sering ilang <span class="text-text-muted">entah kemana?</span>
            </h1>
            <p class="text-text-muted text-lg leading-relaxed max-w-md mb-8">
                Finansiku nyatet tiap rupiah yang masuk dan keluar, terus ngasih kamu satu tampilan jelas ke mana perginya.
            </p>
            <div class="flex gap-3.5 flex-wrap">
                <a href="{{ route('register') }}" class="px-6 py-3.5 rounded-full font-bold text-white bg-primary hover:bg-primary-hover transition">Coba Gratis, 30 Detik Doang</a>
                <a href="#cara-kerja" class="px-6 py-3.5 rounded-full font-semibold text-text-muted hover:text-text">Lihat Cara Kerjanya</a>
            </div>
        </div>

        {{-- Receipt visual --}}
        <div class="bg-white border border-border rounded p-6 shadow-sm">
            <div class="text-center border-b border-dashed border-border pb-3 mb-3">
                <div class="font-mono text-[11px] text-text-muted tracking-widest">*** CATATAN KEUANGAN ***</div>
                <div class="font-extrabold text-[15px] mt-1">07 APRIL 2026</div>
            </div>
            <div class="space-y-1.5 text-sm">
                <div class="flex justify-between"><span>Kopi pagi</span><span class="font-mono font-semibold text-danger">-Rp 18.000</span></div>
                <div class="flex justify-between"><span>Bensin motor</span><span class="font-mono font-semibold text-danger">-Rp 50.000</span></div>
                <div class="flex justify-between"><span>Gaji bulanan</span><span class="font-mono font-semibold text-mint">+Rp 6.900.000</span></div>
                <div class="flex justify-between"><span>Bayar cicilan</span><span class="font-mono font-semibold text-danger">-Rp 730.000</span></div>
            </div>
            <div class="border-t border-dashed border-border mt-3 pt-3 flex justify-between items-center">
                <span class="text-xs uppercase font-bold text-text-muted">Saldo Bersih</span>
                <span class="font-mono font-bold text-xl">Rp 5.140.000</span>
            </div>
        </div>
    </div>
</header>

{{-- ================= CARA KERJA ================= --}}
<section id="cara-kerja" class="py-20 bg-white">
    <div class="max-w-[1120px] mx-auto px-6">
        <div class="text-center max-w-lg mx-auto mb-14">
            <h2 class="text-3xl font-extrabold tracking-tight mb-2">Mulai dalam hitungan detik</h2>
            <p class="text-text-muted">Empat langkah singkat sebelum transaksi pertamamu tercatat.</p>
        </div>
        <div class="grid md:grid-cols-4 gap-5">
            @foreach ($steps as $step)
                <div class="bg-bg border border-border rounded-card p-6">
                    <span class="inline-block font-mono text-[11px] font-bold text-primary-hover bg-primary-light px-2.5 py-1 rounded mb-4">{{ $step['tag'] }}</span>
                    <h3 class="font-bold text-lg mb-2">{{ $step['title'] }}</h3>
                    <p class="text-sm text-text-muted leading-relaxed">{{ $step['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ================= FITUR ================= --}}
<section id="fitur" class="py-20">
    <div class="max-w-[1120px] mx-auto px-6">
        <div class="text-center max-w-lg mx-auto mb-14">
            <h2 class="text-3xl font-extrabold tracking-tight mb-2">Fitur yang bikin hidup lebih tenang</h2>
            <p class="text-text-muted">Setiap fitur dirancang buat satu masalah spesifik yang sering bikin pusing.</p>
        </div>

        <div class="space-y-20">
            @foreach ($features as $f)
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div class="{{ $f['reverse'] ? 'md:order-2' : '' }}">
                        <span class="inline-block bg-primary-light text-primary-hover text-xs font-extrabold uppercase tracking-wide px-3 py-1.5 rounded-full mb-3.5">{{ $f['badge'] }}</span>
                        <h3 class="text-2xl font-extrabold tracking-tight mb-3">{{ $f['title'] }}</h3>
                        <p class="text-text-muted leading-relaxed mb-5">{{ $f['desc'] }}</p>
                        <ul class="space-y-2.5">
                            @foreach ($f['points'] as $point)
                                <li class="flex items-center gap-2.5 text-sm font-medium">
                                    <span class="w-5 h-5 rounded-full bg-primary-light flex items-center justify-center shrink-0">
                                        <svg class="w-2.5 h-2.5 stroke-primary-hover" viewBox="0 0 24 24" fill="none" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    </span>
                                    {{ $point }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="{{ $f['reverse'] ? 'md:order-1' : '' }}">
                        <div class="bg-white border border-border rounded overflow-hidden">
                            {{-- Mini bar ala jendela app --}}
                            <div class="flex gap-1.5 px-4 py-3.5 border-b border-border">
                                <span class="w-2 h-2 rounded-full bg-border"></span>
                                <span class="w-2 h-2 rounded-full bg-border"></span>
                                <span class="w-2 h-2 rounded-full bg-border"></span>
                            </div>

                            <div class="p-5">
                                @switch($f['visual'])
                                    @case('transactions')
                                        <div class="space-y-2">
                                            <div class="flex justify-between items-center px-3.5 py-3 bg-bg rounded-lg text-sm">
                                                <span class="flex items-center gap-2.5 font-semibold"><span class="w-2 h-2 rounded-full bg-danger"></span>Kopi pagi</span>
                                                <span class="font-mono font-bold text-xs text-danger">-Rp 18.000</span>
                                            </div>
                                            <div class="flex justify-between items-center px-3.5 py-3 bg-bg rounded-lg text-sm">
                                                <span class="flex items-center gap-2.5 font-semibold"><span class="w-2 h-2 rounded-full bg-danger"></span>Bensin motor</span>
                                                <span class="font-mono font-bold text-xs text-danger">-Rp 50.000</span>
                                            </div>
                                            <div class="flex justify-between items-center px-3.5 py-3 bg-bg rounded-lg text-sm">
                                                <span class="flex items-center gap-2.5 font-semibold"><span class="w-2 h-2 rounded-full bg-mint"></span>Refund kacamata</span>
                                                <span class="font-mono font-bold text-xs text-mint">+Rp 100.000</span>
                                            </div>
                                        </div>
                                        @break

                                    @case('calendar')
                                      <div class="flex justify-between items-baseline mb-2.5">
                                          <span class="text-sm font-bold">{{ $f['calendar_label'] }}</span>
                                          <span class="font-mono text-xs text-text-muted">{{ count($f['events']) }} acara</span>
                                      </div>
                                      <div class="grid grid-cols-7 gap-1 mb-1 text-center text-[10px] font-bold text-text-muted">
                                          <span>M</span><span>S</span><span>S</span><span>R</span><span>K</span><span>J</span><span>S</span>
                                      </div>

                                      <div class="grid grid-cols-7 gap-1">
                                          @for ($d = 1; $d <= 21; $d++)
                                              @if (isset($f['events'][$d]))
                                                  @php $ev = $f['events'][$d]; @endphp
                                                  <div tabindex="0"
                                                      class="group relative aspect-square rounded-md bg-bg border border-border flex flex-col items-center justify-center text-[10px] text-text-muted cursor-pointer transition-colors hover:border-primary hover:bg-primary-light hover:z-10 focus:z-10 focus:outline-none">
                                                      {{ $d }}
                                                      <span class="w-1.5 h-1.5 rounded-full absolute bottom-1 {{ $ev['type'] === 'in' ? 'bg-mint' : 'bg-danger' }}"></span>

                                                      <div class="pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-2 whitespace-nowrap rounded-lg bg-text px-2.5 py-1.5 text-[11px] font-medium text-white opacity-0 scale-95 transition-all duration-150 group-hover:opacity-100 group-hover:scale-100 group-focus:opacity-100 group-focus:scale-100 z-20">
                                                          {{ $ev['label'] }}<br>
                                                          <span class="font-mono font-bold {{ $ev['type'] === 'in' ? 'text-emerald-300' : 'text-red-300' }}">{{ $ev['amount'] }}</span>
                                                          <span class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-text"></span>
                                                      </div>
                                                  </div>
                                              @else
                                                  <div class="aspect-square rounded-md bg-bg border border-border flex items-center justify-center text-[10px] text-text-muted">
                                                      {{ $d }}
                                                  </div>
                                              @endif
                                          @endfor
                                      </div>

                                      <div class="flex gap-4 mt-3 text-[10px] text-text-muted">
                                          <span class="flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-mint"></span>Pemasukan</span>
                                          <span class="flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-danger"></span>Pengeluaran</span>
                                      </div>
                                      @break@case('calendar')
                                      <div class="flex justify-between items-baseline mb-2.5">
                                          <span class="text-sm font-bold">{{ $f['calendar_label'] }}</span>
                                          <span class="font-mono text-xs text-text-muted">{{ count($f['events']) }} acara</span>
                                      </div>
                                      <div class="grid grid-cols-7 gap-1 mb-1 text-center text-[10px] font-bold text-text-muted">
                                          <span>M</span><span>S</span><span>S</span><span>R</span><span>K</span><span>J</span><span>S</span>
                                      </div>

                                      <div class="grid grid-cols-7 gap-1">
                                          @for ($d = 1; $d <= 21; $d++)
                                              @if (isset($f['events'][$d]))
                                                  @php $ev = $f['events'][$d]; @endphp
                                                  <div tabindex="0"
                                                      class="group relative aspect-square rounded-md bg-bg border border-border flex flex-col items-center justify-center text-[10px] text-text-muted cursor-pointer transition-colors hover:border-primary hover:bg-primary-light hover:z-10 focus:z-10 focus:outline-none">
                                                      {{ $d }}
                                                      <span class="w-1.5 h-1.5 rounded-full absolute bottom-1 {{ $ev['type'] === 'in' ? 'bg-mint' : 'bg-danger' }}"></span>

                                                      <div class="pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-2 whitespace-nowrap rounded-lg bg-text px-2.5 py-1.5 text-[11px] font-medium text-white opacity-0 scale-95 transition-all duration-150 group-hover:opacity-100 group-hover:scale-100 group-focus:opacity-100 group-focus:scale-100 z-20">
                                                          {{ $ev['label'] }}<br>
                                                          <span class="font-mono font-bold {{ $ev['type'] === 'in' ? 'text-emerald-300' : 'text-red-300' }}">{{ $ev['amount'] }}</span>
                                                          <span class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-text"></span>
                                                      </div>
                                                  </div>
                                              @else
                                                  <div class="aspect-square rounded-md bg-bg border border-border flex items-center justify-center text-[10px] text-text-muted">
                                                      {{ $d }}
                                                  </div>
                                              @endif
                                          @endfor
                                      </div>

                                      <div class="flex gap-4 mt-3 text-[10px] text-text-muted">
                                          <span class="flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-mint"></span>Pemasukan</span>
                                          <span class="flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-danger"></span>Pengeluaran</span>
                                      </div>
                                      @break

                                    @case('budget')
                                        <div class="space-y-4">
                                            <div>
                                                <div class="flex justify-between text-xs font-bold mb-2"><span>Jajan</span><span class="font-mono">Rp 420rb / 500rb</span></div>
                                                <div class="h-1.5 rounded-full bg-border overflow-hidden"><div class="h-full bg-primary" style="width:84%"></div></div>
                                            </div>
                                            <div>
                                                <div class="flex justify-between text-xs font-bold mb-2"><span>Transport</span><span class="font-mono text-danger">Rp 610rb / 500rb</span></div>
                                                <div class="h-1.5 rounded-full bg-border overflow-hidden"><div class="h-full bg-danger" style="width:100%"></div></div>
                                            </div>
                                        </div>
                                        @break

                                    @case('telegram')
                                        <div class="space-y-2">
                                            <div class="flex justify-between items-center px-3.5 py-3 bg-bg rounded-lg text-sm">
                                                <span class="flex items-center gap-2.5 font-semibold"><span class="w-2 h-2 rounded-full bg-primary"></span>@FinansikuBot</span>
                                                <span class="text-xs text-text-muted">terhubung</span>
                                            </div>
                                            <div class="flex justify-between items-center px-3.5 py-3 bg-bg rounded-lg text-sm">
                                                <span class="text-text-muted italic">"jajan 15rb"</span>
                                                <span class="font-mono font-bold text-xs text-danger">-Rp 15.000</span>
                                            </div>
                                        </div>
                                        @break

                                    @case('export')
                                        <div class="space-y-2">
                                            <div class="flex justify-between items-center px-3.5 py-3 bg-bg rounded-lg text-sm">
                                                <span class="flex items-center gap-2.5 font-semibold">
                                                    <span class="w-6 h-6 rounded-md bg-mint text-white flex items-center justify-center font-mono text-[9px] font-extrabold">XLS</span>
                                                    Transaksi_April.xlsx
                                                </span>
                                                <span class="font-mono text-xs text-text-muted">2.1 MB</span>
                                            </div>
                                            <div class="flex justify-between items-center px-3.5 py-3 bg-bg rounded-lg text-sm">
                                                <span class="text-text-muted">Rentang tanggal</span>
                                                <span class="font-mono text-xs text-text-muted">1–30 Apr</span>
                                            </div>
                                        </div>
                                        @break

                                    @case('backup')
                                        <div class="space-y-2">
                                            <div class="flex justify-between items-center px-3.5 py-3 bg-bg rounded-lg text-sm">
                                                <span class="flex items-center gap-2.5 font-semibold"><span class="w-2 h-2 rounded-full bg-mint"></span>Backup terakhir</span>
                                                <span class="font-mono text-xs text-text-muted">2 jam lalu</span>
                                            </div>
                                            <div class="px-3.5 py-3 bg-bg rounded-lg text-sm text-text-muted">1.204 transaksi tersimpan</div>
                                        </div>
                                        @break
                                @endswitch
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ================= FAQ ================= --}}
<section id="faq" class="py-20 bg-white">
    <div class="max-w-[680px] mx-auto px-6">
        <div class="text-center mb-14">
            <h2 class="text-3xl font-extrabold tracking-tight mb-2">Pertanyaan umum</h2>
            <p class="text-text-muted">Yang biasanya ditanyain sebelum mulai pakai Finansiku.</p>
        </div>
        <div class="space-y-3">
            @foreach ($faqs as $faq)
                <details @if($faq['open']) open @endif class="group bg-bg border border-border rounded-card p-5">
                    <summary class="font-bold text-[15.5px] cursor-pointer list-none flex justify-between items-center">
                        {{ $faq['q'] }}
                        <span class="text-text-muted text-xl group-open:rotate-45 transition-transform">+</span>
                    </summary>
                    <p class="text-text-muted text-sm leading-relaxed mt-3">{{ $faq['a'] }}</p>
                </details>
            @endforeach
        </div>
    </div>
</section>

{{-- ================= CTA — didesain ulang, TANPA hitam ================= --}}
<section class="py-20 px-6">
    <div class="relative max-w-[1120px] mx-auto rounded-[28px] overflow-hidden
                bg-gradient-to-br from-primary via-primary-hover to-sky-800
                px-8 py-16 md:py-20 text-center">

        {{-- aksen dekoratif biar nggak flat, tapi tetap rapi & bukan hitam --}}
        <div class="pointer-events-none absolute -top-16 -left-16 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-20 -right-10 w-72 h-72 bg-mint/20 rounded-full blur-3xl"></div>

        <div class="relative">
            <h2 class="text-white text-3xl md:text-4xl font-extrabold tracking-tight mb-3">
                Siap ngatur keuanganmu?
            </h2>
            <p class="text-primary-light/90 text-[15.5px] mb-2 max-w-md mx-auto">
                Ribuan rupiah kecil, kalau nggak dicatat, jadi jutaan yang nggak ketahuan.
            </p>

            <div id="ctaTicker" class="font-mono text-2xl md:text-3xl font-bold text-white mb-8">
                Rp 0
            </div>

            <a href="{{ route('register') }}"
               class="inline-flex items-center justify-center px-8 py-4 rounded-full font-bold text-primary-hover bg-white hover:bg-primary-light transition shadow-lg">
                Mulai Sekarang — Gratis
            </a>
        </div>
    </div>
</section>

@push('scripts')
<script>
    (function () {
        const ticker = document.getElementById('ctaTicker');
        if (!ticker) return;

        let val = 0;
        const target = 5140000;
        const step = Math.ceil(target / 60);

        function tick() {
            val = Math.min(val + step, target);
            ticker.textContent = 'Rp ' + val.toLocaleString('id-ID');
            if (val < target) requestAnimationFrame(() => setTimeout(tick, 16));
        }

        const obs = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    tick();
                    obs.disconnect();
                }
            });
        });
        obs.observe(ticker);
    })();
</script>
@endpush

{{-- ================= FOOTER ================= --}}
<footer class="py-12">
    <div class="max-w-[1120px] mx-auto px-6 flex flex-wrap justify-between items-center gap-4 border-t border-border pt-8">
        <div class="flex items-center gap-2.5 font-extrabold">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Finansiku" class="w-7 h-7 object-contain">
            Finansiku
        </div>
        <div class="flex gap-6 text-sm text-text-muted">
            <a href="{{ route('legal.privacy') }}" class="hover:text-text">Privacy Policy</a>
            <a href="{{ route('legal.terms') }}" class="hover:text-text">Terms of Service</a>
        </div>
        <div class="text-sm text-text-muted">© {{ date('Y') }} Finansiku</div>
    </div>
</footer>

</x-layouts.app>