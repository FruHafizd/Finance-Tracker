<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finansiku - Kelola Keuangan Pribadi Lebih Cerdas & Simpel</title>
    <meta name="description" content="Aplikasi Finansiku modern untuk mengelola pengeluaran, pemasukan, dan anggaran dengan desain minimalis. 100% Gratis dan Responsif.">
    <meta name="keywords" content="finansiku, kelola uang, aplikasi keuangan, budget tracker, pengeluaran harian">
    <meta name="google-site-verification" content="XDUltp0hE8n1iQSBjhJ339PM7d_XqldKzRBP33wC-m4" />
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui'],
                    },
                }
            }
        }
    </script>
    
    <style>
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 font-sans antialiased">

    <!-- Navbar -->
    <nav class="fixed w-full z-50 transition-all duration-300 glass border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-2">
                    <div class="bg-slate-900 p-1.5 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="text-xl font-bold tracking-tight text-slate-900">Finansiku</span>
                </div>
                <div class="hidden md:flex items-center space-x-8 text-sm font-medium">
                    <a href="#fitur" class="hover:text-slate-600 transition-colors">Fitur</a>
                    <a href="#cara-kerja" class="hover:text-slate-600 transition-colors">Cara Kerja</a>
                    <a href="#faq" class="hover:text-slate-600 transition-colors">FAQ</a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="text-sm font-medium hover:text-slate-600 transition-colors">Masuk</a>
                    <a href="{{ route('register') }}" class="bg-slate-900 text-white px-5 py-2 rounded-full text-sm font-medium hover:bg-slate-800 transition-all shadow-sm">Mulai Gratis</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="pt-32 pb-16 md:pt-48 md:pb-32 px-4 relative overflow-hidden">
        <div class="max-w-7xl mx-auto text-center relative z-10">
            <div class="inline-flex items-center space-x-2 bg-slate-100 px-3 py-1 rounded-full mb-6 border border-slate-200">
                <span class="flex h-2 w-2 rounded-full bg-slate-400"></span>
                <span class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Tersedia Sekarang Untuk Umum</span>
            </div>
            <h1 class="text-4xl md:text-6xl lg:text-7xl font-extrabold text-slate-900 tracking-tight mb-6 leading-tight">
                Kelola Keuangan Pribadi<br class="hidden md:block"> 
                <span class="text-slate-400">Lebih Cerdas, Simpel, dan Elegan.</span>
            </h1>
            <p class="text-lg md:text-xl text-slate-500 max-w-2xl mx-auto mb-10 leading-relaxed">
                Platform pencatatan keuangan modern yang dirancang untuk membantu Anda memantau setiap rupiah dengan presisi tinggi dan desain yang menenangkan.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('register') }}" class="w-full sm:w-auto bg-slate-900 text-white px-8 py-4 rounded-full text-lg font-semibold hover:bg-slate-800 transition-all shadow-lg hover:shadow-xl hover:-translate-y-1">
                    Coba Gratis Sekarang
                </a>
                <a href="#fitur" class="w-full sm:w-auto px-8 py-4 rounded-full text-lg font-semibold text-slate-600 hover:bg-slate-100 transition-all">
                    Lihat Fitur
                </a>
            </div>
            
            <!-- Hero Dashboard Image -->
            <div class="mt-20 max-w-5xl mx-auto relative group">
                <div class="absolute -inset-1 bg-gradient-to-b from-slate-200 to-transparent rounded-[2rem] blur opacity-25 group-hover:opacity-40 transition duration-1000"></div>
                <div class="relative bg-white p-2 rounded-[2rem] shadow-2xl border border-slate-200">
                    <img src="{{ asset('images/features/dashboard.png') }}" alt="Dashboard Finansiku" class="rounded-[1.5rem] w-full shadow-inner">
                </div>
            </div>
        </div>
        
        <!-- Decorative elements -->
        <div class="absolute top-0 right-0 -mr-20 mt-40 opacity-20 pointer-events-none">
            <div class="w-96 h-96 bg-slate-300 rounded-full blur-3xl"></div>
        </div>
        <div class="absolute bottom-0 left-0 -ml-20 mb-20 opacity-20 pointer-events-none">
            <div class="w-96 h-96 bg-slate-300 rounded-full blur-3xl"></div>
        </div>
    </header>

    <!-- How it Works -->
    <section id="cara-kerja" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold mb-4 tracking-tight">Mulai Dalam Hitungan Detik</h2>
                <p class="text-slate-500">Tiga langkah mudah untuk mengontrol masa depan finansial Anda.</p>
            </div>
            <div class="grid md:grid-cols-3 gap-12">
                <div class="text-center group">
                    <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-slate-100 group-hover:bg-slate-900 group-hover:text-white transition-all duration-300">
                        <span class="text-xl font-bold">1</span>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Buat Akun</h3>
                    <p class="text-slate-500 leading-relaxed">Daftar secara gratis hanya dengan email. Tidak perlu kartu kredit, tidak ada hambatan.</p>
                </div>
                <div class="text-center group">
                    <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-slate-100 group-hover:bg-slate-900 group-hover:text-white transition-all duration-300">
                        <span class="text-xl font-bold">2</span>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Catat Transaksi</h3>
                    <p class="text-slate-500 leading-relaxed">Masukkan pemasukan dan pengeluaran harian Anda dengan antarmuka yang cepat dan intuitif.</p>
                </div>
                <div class="text-center group">
                    <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-slate-100 group-hover:bg-slate-900 group-hover:text-white transition-all duration-300">
                        <span class="text-xl font-bold">3</span>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Pantau Progres</h3>
                    <p class="text-slate-500 leading-relaxed">Lihat perkembangan saldo dan analisis pengeluaran Anda melalui dasbor visual yang informatif.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Showcase -->
    <section id="fitur" class="py-24 bg-slate-50 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-20">
                <h2 class="text-3xl md:text-4xl font-bold mb-4 tracking-tight">Fitur Utama Yang Memberdayakan Anda</h2>
                <p class="text-slate-500">Segala yang Anda butuhkan untuk manajemen keuangan profesional.</p>
            </div>

            <!-- Feature 1: Transactions -->
            <div class="flex flex-col lg:flex-row items-center gap-16 mb-32">
                <div class="lg:w-1/2">
                    <div class="bg-white p-3 rounded-[2rem] shadow-xl border border-slate-200">
                        <img src="{{ asset('images/features/transactions.png') }}" alt="Fitur Transaksi" class="rounded-[1.5rem] w-full">
                    </div>
                </div>
                <div class="lg:w-1/2">
                    <div class="inline-flex items-center bg-slate-100 text-slate-600 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-widest mb-4">Efisiensi</div>
                    <h3 class="text-3xl font-bold mb-6 tracking-tight">Pencatatan Transaksi Kilat</h3>
                    <p class="text-lg text-slate-500 leading-relaxed mb-8">
                        Lupakan proses pencatatan yang rumit. Dengan antarmuka minimalis, Anda dapat mencatat pengeluaran dalam hitungan detik sebelum Anda lupa. Dilengkapi dengan kategori dan tag untuk pengorganisasian yang lebih baik.
                    </p>
                    <ul class="space-y-4">
                        <li class="flex items-center gap-3 text-slate-700">
                            <svg class="w-5 h-5 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Kategori yang dapat disesuaikan
                        </li>
                        <li class="flex items-center gap-3 text-slate-700">
                            <svg class="w-5 h-5 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Input nominal yang cepat & akurat
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Feature 2: Budgeting -->
            <div class="flex flex-col lg:flex-row-reverse items-center gap-16 mb-32">
                <div class="lg:w-1/2">
                    <div class="bg-white p-3 rounded-[2rem] shadow-xl border border-slate-200">
                        <img src="{{ asset('images/features/budgeting.png') }}" alt="Fitur Anggaran" class="rounded-[1.5rem] w-full">
                    </div>
                </div>
                <div class="lg:w-1/2">
                    <div class="inline-flex items-center bg-slate-100 text-slate-600 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-widest mb-4">Kontrol</div>
                    <h3 class="text-3xl font-bold mb-6 tracking-tight">Anggaran Yang Terukur</h3>
                    <p class="text-lg text-slate-500 leading-relaxed mb-8">
                        Tetapkan batas pengeluaran untuk setiap kategori dan biarkan sistem memantau untuk Anda. Dapatkan peringatan dini sebelum Anda melampaui batas anggaran bulanan Anda.
                    </p>
                    <ul class="space-y-4">
                        <li class="flex items-center gap-3 text-slate-700">
                            <svg class="w-5 h-5 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Alokasi dana per kategori
                        </li>
                        <li class="flex items-center gap-3 text-slate-700">
                            <svg class="w-5 h-5 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Visualisasi sisa anggaran real-time
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Feature 3: Multi-Account -->
            <div class="flex flex-col lg:flex-row items-center gap-16">
                <div class="lg:w-1/2">
                    <div class="bg-white p-3 rounded-[2rem] shadow-xl border border-slate-200">
                        <img src="{{ asset('images/features/accounts.png') }}" alt="Fitur Banyak Rekening" class="rounded-[1.5rem] w-full">
                    </div>
                </div>
                <div class="lg:w-1/2">
                    <div class="inline-flex items-center bg-slate-100 text-slate-600 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-widest mb-4">Integrasi</div>
                    <h3 class="text-3xl font-bold mb-6 tracking-tight">Manajemen Banyak Rekening</h3>
                    <p class="text-lg text-slate-500 leading-relaxed mb-8">
                        Pantau saldo di berbagai tempat—mulai dari bank utama, e-wallet (GoPay, OVO), hingga uang tunai. Lihat total kekayaan bersih Anda secara agregat dalam satu tampilan.
                    </p>
                    <ul class="space-y-4">
                        <li class="flex items-center gap-3 text-slate-700">
                            <svg class="w-5 h-5 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Mendukung berbagai jenis bank & dompet digital
                        </li>
                        <li class="flex items-center gap-3 text-slate-700">
                            <svg class="w-5 h-5 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Rekonsiliasi saldo yang mudah
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-24 bg-white">
        <div class="max-w-4xl mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold mb-4 tracking-tight">Pertanyaan Umum</h2>
                <p class="text-slate-500">Semua yang perlu Anda ketahui tentang Finansiku.</p>
            </div>
            <div class="space-y-6">
                <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100">
                    <h4 class="text-lg font-bold mb-2">Apakah aplikasi ini gratis?</h4>
                    <p class="text-slate-500 leading-relaxed">Ya, saat ini semua fitur yang tersedia dapat digunakan secara 100% gratis tanpa biaya langganan.</p>
                </div>
                <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100">
                    <h4 class="text-lg font-bold mb-2">Apakah bisa digunakan di handphone?</h4>
                    <p class="text-slate-500 leading-relaxed">Tentu saja. Aplikasi kami dirancang dengan pendekatan 'mobile-first' dan sangat responsif, sehingga nyaman digunakan melalui browser di smartphone Anda.</p>
                </div>
                <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100">
                    <h4 class="text-lg font-bold mb-2">Bagaimana dengan keamanan data saya?</h4>
                    <p class="text-slate-500 leading-relaxed">Kami memprioritaskan privasi Anda. Data Anda dienkripsi dan disimpan dengan protokol keamanan standar industri.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Banner -->
    <section class="py-20 bg-slate-900 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 text-center relative z-10">
            <h2 class="text-3xl md:text-5xl font-bold text-white mb-6 tracking-tight">Siap Untuk Mengontrol Keuangan Anda?</h2>
            <p class="text-slate-400 text-lg mb-10 max-w-2xl mx-auto">Bergabunglah dengan ribuan pengguna lainnya yang telah berhasil memperbaiki kondisi finansial mereka.</p>
            <a href="{{ route('register') }}" class="inline-block bg-white text-slate-900 px-10 py-4 rounded-full text-lg font-bold hover:bg-slate-100 transition-all shadow-xl">
                Mulai Sekarang — Gratis
            </a>
        </div>
        <div class="absolute top-0 left-0 w-full h-full opacity-10">
            <svg class="h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <path d="M0 100 C 20 0 50 0 100 100 Z" fill="white"></path>
            </svg>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-12 bg-white border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center gap-8">
                <div class="flex items-center gap-2">
                    <div class="bg-slate-900 p-1 rounded-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="text-lg font-bold tracking-tight">Finansiku</span>
                </div>
                <div class="text-slate-400 text-sm">
                    &copy; 2026 Finansiku. Dibuat untuk manajemen masa depan yang lebih baik.
                </div>
                <div class="flex space-x-6 text-slate-400 text-sm">
                    <a href="#" class="hover:text-slate-900 transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-slate-900 transition-colors">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Simple navbar interaction
        window.addEventListener('scroll', function() {
            var nav = document.querySelector('nav');
            if (window.scrollY > 50) {
                nav.classList.add('py-2');
                nav.classList.remove('py-4');
            } else {
                nav.classList.add('py-4');
                nav.classList.remove('py-2');
            }
        });
    </script>

</body>
</html>
