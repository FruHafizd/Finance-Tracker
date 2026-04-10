<x-legal-layout title="Syarat & Ketentuan">
    <div class="prose prose-slate max-w-none">
        <h1 class="text-3xl font-bold text-slate-900 mb-8 border-b pb-4">Syarat & Ketentuan</h1>
        
        <p class="text-slate-600 mb-6 leading-relaxed">
            Terakhir diperbarui: {{ date('d F Y') }}
        </p>

        <section class="mb-10 text-slate-700">
            <h2 class="text-xl font-bold text-slate-800 mb-4">1. Penerimaan Syarat</h2>
            <p class="mb-4">
                Dengan mengakses atau menggunakan aplikasi {{ config('app.name') }}, Anda setuju untuk terikat oleh Syarat dan Ketentuan ini. Jika Anda tidak menyetujui bagian mana pun dari ketentuan ini, Anda tidak diperbolehkan menggunakan layanan kami.
            </p>
        </section>

        <section class="mb-10 text-slate-700">
            <h2 class="text-xl font-bold text-slate-800 mb-4">2. Penggunaan Layanan</h2>
            <p class="mb-4">
                {{ config('app.name') }} adalah alat untuk membantu manajemen keuangan pribadi. Anda bertanggung jawab penuh atas keakuratan data yang Anda masukkan dan keputusan finansial yang Anda ambil berdasarkan informasi dari aplikasi kami.
            </p>
        </section>

        <section class="mb-10 text-slate-700">
            <h2 class="text-xl font-bold text-slate-800 mb-4">3. Akun Pengguna</h2>
            <p class="mb-4">
                Anda bertanggung jawab untuk menjaga kerahasiaan informasi akun dan kata sandi Anda. Segala aktivitas yang terjadi di bawah akun Anda adalah tanggung jawab Anda sepenuhnya.
            </p>
        </section>

        <section class="mb-10 text-slate-700">
            <h2 class="text-xl font-bold text-slate-800 mb-4">4. Batasan Tanggung Jawab</h2>
            <p class="mb-4">
                Kami menyediakan layanan ini "apa adanya" tanpa jaminan apa pun. Kami tidak bertanggung jawab atas kerugian finansial, kehilangan data, atau masalah teknis yang mungkin timbul selama penggunaan aplikasi.
            </p>
        </section>

        <section class="mb-10 text-slate-700">
            <h2 class="text-xl font-bold text-slate-800 mb-4">5. Kekayaan Intelektual</h2>
            <p class="mb-4">
                Semua konten, logo, desain, dan kode di dalam aplikasi ini adalah milik intelektual {{ config('app.name') }} dan dilindungi oleh hukum yang berlaku. Anda tidak diperbolehkan menggandakan atau mendistribusikan materi apa pun tanpa izin tertulis dari kami.
            </p>
        </section>

        <section class="mb-10 text-slate-700">
            <h2 class="text-xl font-bold text-slate-800 mb-4">6. Pemutusan Akun</h2>
            <p class="mb-4">
                Kami berhak untuk menangguhkan atau menghentikan akun Anda jika ditemukan adanya pelanggaran terhadap Syarat dan Ketentuan ini atau penyalahgunaan sistem yang merugikan pengguna lain.
            </p>
        </section>

        <section class="mb-10 text-slate-700">
            <h2 class="text-xl font-bold text-slate-800 mb-4">7. Hukum yang Berlaku</h2>
            <p class="mb-4">
                Syarat dan Ketentuan ini diatur oleh hukum yang berlaku di Republik Indonesia. Segala perselisihan yang timbul akan diselesaikan melalui jalur hukum di pengadilan yang berwenang.
            </p>
        </section>

        <div class="mt-12 p-6 bg-slate-50 rounded-2xl border border-slate-100 italic text-sm text-slate-500">
            Dengan menggunakan aplikasi ini, Anda dianggap telah membaca dan menyetujui seluruh isi dari Syarat & Ketentuan ini.
        </div>
    </div>
</x-legal-layout>
