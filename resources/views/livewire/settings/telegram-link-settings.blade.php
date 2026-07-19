<div>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-text leading-tight">
            Integrasi Telegram
        </h2>
    </x-slot>

    {{-- CARD: HUBUNGKAN AKUN --}}
    <div class="bg-bg-sidebar border border-border rounded-xl p-6" wire:poll.5s="refreshStatus">

        <div class="flex items-center justify-between mb-1">
            <h3 class="text-text font-semibold text-lg">Hubungkan Akun Telegram</h3>

            @if ($isLinked)
                <span class="inline-flex items-center gap-1.5 text-xs font-medium text-primary bg-primary-light px-2.5 py-1 rounded-full">
                    <span class="w-1.5 h-1.5 rounded-full bg-primary"></span> Terhubung
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 text-xs font-medium text-danger bg-danger/10 px-2.5 py-1 rounded-full">
                    <span class="w-1.5 h-1.5 rounded-full bg-danger"></span> Belum terhubung
                </span>
            @endif
        </div>
        <p class="text-text-muted text-sm mb-5">
            Catat transaksi dan cek saldo langsung dari chat Telegram tanpa perlu buka aplikasi.
        </p>

        @if (! $isLinked)
            <div class="space-y-4">
                @if (! $linkCode)
                    <button
                        wire:click="generateCode"
                        class="inline-flex items-center gap-2 bg-primary hover:bg-primary-hover text-white text-sm font-medium px-4 py-2.5 rounded-lg transition-colors"
                    >
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9.78 18.65l.28-4.23 7.68-6.92c.34-.31-.07-.46-.52-.19L7.74 13.3 3.64 12c-.88-.25-.89-.86.2-1.3l15.97-6.16c.73-.33 1.43.18 1.15 1.3l-2.72 12.81c-.19.91-.74 1.13-1.5.71l-4.14-3.05-2 1.94c-.23.23-.42.42-.83.42z"/>
                        </svg>
                        Hubungkan Akun Telegram
                    </button>
                @else
                    <div class="bg-primary-light border border-primary/20 rounded-lg p-4">
                        <p class="text-text-muted text-xs mb-2">Kode kamu (berlaku sampai {{ $expiresAt }}):</p>

                        <div class="flex items-center gap-3">
                            <div
                                x-data="{ code: @js($linkCode), copied: false }"
                                class="flex items-center gap-2"
                            >
                                <span class="font-mono text-2xl font-bold text-text tracking-widest bg-white border border-border rounded-md px-4 py-2">
                                    {{ $linkCode }}
                                </span>
                                <button
                                    type="button"
                                    x-on:click="
                                        navigator.clipboard.writeText(code);
                                        copied = true;
                                        setTimeout(() => copied = false, 1500);
                                    "
                                    class="text-text-muted hover:text-text-hover p-2 rounded-md hover:bg-white/60 transition-colors"
                                    title="Salin kode"
                                >
                                    <svg x-show="!copied" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                    <svg x-show="copied" x-cloak class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="mt-4 pt-4 border-t border-primary/20">
                            <p class="text-text text-sm mb-2">Langkah selanjutnya:</p>
                            <ol class="text-text-muted text-sm space-y-1 list-decimal list-inside">
                                <li>
                                    Buka
                                    <a href="https://t.me/FinansikuBot" target="_blank" class="text-primary hover:text-primary-hover font-medium underline">
                                        @FinansikuBot
                                    </a>
                                    di Telegram
                                </li>
                                <li>Kirim pesan: <code class="bg-white border border-border rounded px-1.5 py-0.5 text-xs font-mono">/link {{ $linkCode }}</code></li>
                                <li>Halaman ini otomatis update begitu berhasil terhubung</li>
                            </ol>
                        </div>
                    </div>

                    <button
                        wire:click="generateCode"
                        class="text-text-muted hover:text-text-hover text-xs underline"
                    >
                        Generate kode baru
                    </button>
                @endif
            </div>
        @else
            <div class="flex items-center justify-between bg-primary-light border border-primary/20 rounded-lg p-4">
                <div class="flex items-center gap-3">
                    <span class="w-9 h-9 rounded-full bg-primary/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </span>
                    <div>
                        <p class="text-text font-medium text-sm">Terhubung dengan Telegram</p>
                        @if ($linkedAt)
                            <p class="text-text-muted text-xs">Sejak {{ $linkedAt }}</p>
                        @endif
                    </div>
                </div>

                <button
                    wire:click="confirmUnlink"
                    class="text-danger hover:underline text-sm font-medium"
                >
                    Putuskan Koneksi
                </button>
            </div>
        @endif
    </div>

    {{-- CARD: CARA MENGHUBUNGKAN --}}
    <div class="bg-bg-sidebar border border-border rounded-xl p-6 mt-5">
        <h3 class="text-text font-semibold text-base mb-1">Cara menghubungkan</h3>
        <p class="text-text-muted text-sm mb-4">Tiga langkah singkat, tidak perlu instalasi tambahan.</p>

        <ol class="space-y-3">
            <li class="flex gap-3">
                <span class="w-6 h-6 shrink-0 rounded-full bg-primary-light text-primary text-xs font-semibold flex items-center justify-center">1</span>
                <p class="text-text-muted text-sm">Klik <span class="text-text font-medium">Hubungkan Akun Telegram</span>, sebuah kode 6-digit akan muncul (berlaku 5 menit).</p>
            </li>
            <li class="flex gap-3">
                <span class="w-6 h-6 shrink-0 rounded-full bg-primary-light text-primary text-xs font-semibold flex items-center justify-center">2</span>
                <p class="text-text-muted text-sm">Buka <a href="https://t.me/FinansikuBot" target="_blank" class="text-primary underline">@FinansikuBot</a> di Telegram, lalu kirim <code class="bg-white border border-border rounded px-1.5 py-0.5 text-xs font-mono">/link kode_kamu</code>.</p>
            </li>
            <li class="flex gap-3">
                <span class="w-6 h-6 shrink-0 rounded-full bg-primary-light text-primary text-xs font-semibold flex items-center justify-center">3</span>
                <p class="text-text-muted text-sm">Halaman ini otomatis memperbarui statusnya jadi <span class="text-primary font-medium">Terhubung</span>.</p>
            </li>
        </ol>
    </div>

    {{-- CARD: DAFTAR PERINTAH BOT --}}
    <div class="bg-bg-sidebar border border-border rounded-xl p-6 mt-5">
        <h3 class="text-text font-semibold text-base mb-1">Perintah yang tersedia</h3>
        <p class="text-text-muted text-sm mb-4">Kirim salah satu ini ke bot setelah akun terhubung.</p>

        <div class="divide-y divide-border">
            <div class="flex items-start gap-4 py-2.5">
                <code class="bg-white border border-border rounded-md px-2.5 py-1 text-xs font-mono text-text shrink-0 min-w-[90px] text-center">/saldo</code>
                <p class="text-text-muted text-sm">Cek saldo semua rekening/dompet kamu.</p>
            </div>
            <div class="flex items-start gap-4 py-2.5">
                <code class="bg-white border border-border rounded-md px-2.5 py-1 text-xs font-mono text-text shrink-0 min-w-[90px] text-center">/laporan</code>
                <p class="text-text-muted text-sm">Ringkasan pemasukan dan pengeluaran bulan berjalan.</p>
            </div>
            <div class="flex items-start gap-4 py-2.5">
                <code class="bg-white border border-border rounded-md px-2.5 py-1 text-xs font-mono text-text shrink-0 min-w-[90px] text-center">/? atau /help</code>
                <p class="text-text-muted text-sm">Tampilkan panduan lengkap format pencatatan cepat.</p>
            </div>
            <div class="flex items-start gap-4 py-2.5">
                <code class="bg-white border border-border rounded-md px-2.5 py-1 text-xs font-mono text-text shrink-0 min-w-[90px] text-center">keluar</code>
                <p class="text-text-muted text-sm">
                    Catat pengeluaran cepat. Contoh:
                    <code class="bg-white border border-border rounded px-1.5 py-0.5 text-xs font-mono">keluar 15000 kopi susu</code>
                </p>
            </div>
            <div class="flex items-start gap-4 py-2.5">
                <code class="bg-white border border-border rounded-md px-2.5 py-1 text-xs font-mono text-text shrink-0 min-w-[90px] text-center">masuk</code>
                <p class="text-text-muted text-sm">
                    Catat pemasukan cepat. Contoh:
                    <code class="bg-white border border-border rounded px-1.5 py-0.5 text-xs font-mono">masuk 5000000 bonus</code>
                </p>
            </div>
        </div>

        <div class="mt-4 pt-4 border-t border-border">
            <p class="text-text text-sm font-medium mb-1.5">Kategori & rekening spesifik</p>
            <p class="text-text-muted text-sm">
                Tambahkan
                <code class="bg-white border border-border rounded px-1.5 py-0.5 text-xs font-mono">#kategori</code>
                dan
                <code class="bg-white border border-border rounded px-1.5 py-0.5 text-xs font-mono">@rekening</code>
                di akhir pesan. Contoh:
            </p>
            <code class="block mt-2 bg-white border border-border rounded-md px-3 py-2 text-xs font-mono text-text">
                keluar 15000 Bakso #Makanan @Dompet
            </code>
        </div>
    </div>

    {{-- CARD: KEAMANAN --}}
    @if ($isLinked)
        <div class="bg-bg-sidebar border border-danger/20 rounded-xl p-6 mt-5">
            <h3 class="text-danger font-semibold text-base mb-1">Keamanan</h3>
            <p class="text-text-muted text-sm mb-4">
                Putuskan koneksi jika akun Telegram ini tidak lagi kamu gunakan, atau ingin menghubungkan akun lain. Bot hanya merespons perintah dari akun Telegram yang sudah terverifikasi.
            </p>
            <button
                wire:click="confirmUnlink"
                class="inline-flex items-center gap-2 border border-danger/30 text-danger text-sm font-medium px-4 py-2 rounded-lg hover:bg-danger/5 transition-colors"
            >
                Putuskan Koneksi
            </button>
        </div>
    @endif

    {{-- MODAL KONFIRMASI UNLINK --}}
    @if ($showUnlinkConfirm)
        <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50" wire:click.self="cancelUnlink">
            <div class="bg-bg-sidebar rounded-xl p-6 max-w-sm w-full mx-4 border border-border">
                <h4 class="text-text font-semibold mb-2">Putuskan koneksi Telegram?</h4>
                <p class="text-text-muted text-sm mb-5">
                    Bot tidak akan bisa lagi mengakses data transaksi kamu sampai kamu menghubungkannya kembali.
                </p>
                <div class="flex justify-end gap-2">
                    <button
                        wire:click="cancelUnlink"
                        class="px-4 py-2 text-sm text-text-muted hover:text-text-hover rounded-lg"
                    >
                        Batal
                    </button>
                    <button
                        wire:click="unlinkAccount"
                        class="px-4 py-2 text-sm bg-danger text-white rounded-lg hover:opacity-90"
                    >
                        Ya, Putuskan
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>