<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class LandingController extends Controller
{
    public function index(): View
    {
        $steps = [
            ['tag' => 'ITEM 01', 'title' => 'Bikin akun', 'desc' => 'Daftar gratis pakai email. Nggak perlu kartu kredit, nggak pakai ribet.'],
            ['tag' => 'ITEM 02', 'title' => 'Buat rekening pertama', 'desc' => 'Tambahkan minimal satu rekening (misal Dompet Tunai atau BCA) — ini jadi "wadah" tempat transaksimu tercatat.'],
            ['tag' => 'ITEM 03', 'title' => 'Susun kategori', 'desc' => 'Pakai kategori bawaan atau bikin sendiri, biar tiap transaksi kepisah rapi sejak awal.'],
            ['tag' => 'ITEM 04', 'title' => 'Catat & pantau', 'desc' => 'Setelah rekening dan kategori siap, tinggal catat transaksi dan pantau lewat dashboard.'],
        ];

        $features = [
            [
                'badge' => 'Anti Ribet',
                'title' => 'Catat sebelum keburu lupa',
                'desc' => 'Sering niat nyatet tapi keburu sibuk terus lupa. Simpan transaksi rutin sebagai template — kopi, transport, langganan bulanan — tinggal satu tap.',
                'points' => ['Template sekali tap untuk transaksi berulang', 'Kategori otomatis langsung terisi'],
                'reverse' => false,
                'visual' => 'transactions',
            ],
            [
                'badge' => 'Rencana Ke Depan',
                'title' => 'Semua jadwal keuangan, satu tampilan',
                'desc' => 'Tagihan, cicilan, gajian biasanya tersebar di kepala. Financial Calendar naruh semuanya dalam satu kalender visual.',
                'points' => ['Visualisasi jadwal gajian dan tagihan', 'Pengingat sebelum jatuh tempo'],
                'reverse' => true,
                'visual' => 'calendar',
                'calendar_label' => 'April 2026',
                'events' => [
                    7  => ['type' => 'in',  'label' => 'Gajian',  'amount' => '+Rp 6.900.000'],
                    15 => ['type' => 'out', 'label' => 'Cicilan', 'amount' => '-Rp 730.000'],
                    20 => ['type' => 'out', 'label' => 'Listrik', 'amount' => '-Rp 250.000'],
                ],
            ],
            [
                'badge' => 'Kendali Pengeluaran',
                'title' => 'Atur budget, jangan sampai kebablasan',
                'desc' => 'Gampang lupa udah berapa banyak yang kepake buat "Jajan" atau "Transport" bulan ini. Set budget per kategori, Finansiku ngasih tahu begitu mendekati atau lewat batas.',
                'points' => ['Budget bulanan per kategori', 'Progress bar & peringatan saat mendekati limit'],
                'reverse' => false,
                'visual' => 'budget',
            ],
            [
                'badge' => 'Notifikasi',
                'title' => 'Catat duit langsung lewat chat',
                'desc' => 'Males buka aplikasi cuma buat nulis pengeluaran kecil? Kirim ke bot Telegram @FinansikuBot, transaksi otomatis tercatat saat itu juga.',
                'points' => ['Catat instan lewat chat Telegram', 'Sinkron otomatis ke dashboard utama'],
                'reverse' => true,
                'visual' => 'telegram',
            ],
            [
                'badge' => 'Data Milikmu',
                'title' => 'Export ke Excel kapan aja',
                'desc' => 'Unduh seluruh riwayat transaksi ke file Excel dengan satu klik, lengkap dengan filter tanggal dan rekening.',
                'points' => ['Export transaksi ke .xlsx satu klik', 'Bisa difilter per rentang tanggal & rekening'],
                'reverse' => false,
                'visual' => 'export',
            ],
            [
                'badge' => 'Keamanan Data',
                'title' => 'Backup & restore satu klik',
                'desc' => 'Khawatir riwayat keuangan hilang saat ganti HP? Cadangkan data kapan saja, pulihkan dengan satu klik.',
                'points' => ['Cadangkan data kapan saja', 'Pulihkan instan saat butuh'],
                'reverse' => true,
                'visual' => 'backup',
            ],
        ];

        $faqs = [
            ['q' => 'Apakah aplikasi ini gratis?', 'a' => 'Ya, semua fitur yang tersedia bisa dipakai 100% gratis tanpa biaya langganan.', 'open' => true],
            ['q' => 'Kenapa harus bikin rekening dulu sebelum bisa nyatet transaksi?', 'a' => 'Setiap transaksi butuh "wadah" biar saldo per sumber dana jelas (Dompet Tunai, BCA, dll). Prosesnya cuma butuh waktu kurang dari semenit.', 'open' => false],
            ['q' => 'Apakah bisa dipakai di HP?', 'a' => 'Bisa. Tampilannya responsif penuh, nyaman dipakai lewat browser di HP tanpa perlu install apa-apa.', 'open' => false],
            ['q' => 'Bagaimana keamanan data saya?', 'a' => 'Data disimpan dengan protokol enkripsi standar dan dirancang biar gampang dipakai secara aman oleh siapa saja.', 'open' => false],
        ];

        return view('landing', compact('steps', 'features', 'faqs'));
    }
}