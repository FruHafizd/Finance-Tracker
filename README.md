# 📒 Finansiku
🔗 **Live Demo:** [finansiku.vercel.app](https://finansiku.vercel.app/)

## 📖 Tentang Aplikasi
Finansiku adalah aplikasi pencatatan dan pengelolaan keuangan pribadi berbasis web. Aplikasi ini dirancang dengan antarmuka yang modern dan minimalis untuk membantu pengguna melacak aliran kas (pemasukan, pengeluaran, transfer antar akun), mengelola batasan anggaran bulanan, serta mendapatkan visibilitas yang baik terhadap kondisi finansial mereka melalui ringkasan dashboard interaktif.

---

## ✨ Fitur Tersedia
Berikut adalah fitur-fitur utama yang tersedia dalam aplikasi:
1. **Autentikasi & Manajemen Pengguna**: Login, registrasi, dan pengaturan profil yang didukung menggunakan Laravel Breeze.
2. **Dashboard Interaktif**: Ringkasan saldo total uang, grafik analitik keuangan visual (dengan Chart.js), serta daftar riwayat transaksi terbaru.
3. **Manajemen Akun/Dompet**: Mengelola berbagai pos penyimpanan finansial pengguna seperti Tabungan (Bank), e-wallet, dan uang tunai. Mendukung visualisasi warna untuk identifikasi setiap bank/akun.
4. **Kategori Dinamis**: Mengelompokkan transaksi dengan kategori personal yang bisa dispesifikkan beserta variasi warnanya.
5. **Pencatatan Transaksi**: Pencatatan tiga jenis pembukuan: *Income* (Pemasukan), *Expense* (Pengeluaran), dan *Transfer* (Pemindahan dana antar akun internal).
6. **Manajemen Anggaran (Budgeting)**: Menentukan batasan (limit) pengeluaran bulanan berdasarkan nominal dan kategori tertentu untuk mengontrol kebiasaan menghabiskan uang.
7. **Transaksi Favorit (Quick Transactions)**: Menyimpan *template* transaksi yang frekuensi aktivitasnya sering terjadi agar saat penginputan bisa dilakukan dengan sekali klik.
8. **Export / Laporan**: Menghasilkan (download) laporan transaksi berdasarkan jangka (range) waktu tertentu dalam bentuk file Excel (*.xlsx*).
9. **Backup & Restore Data**: Pencadangan data keuangan pengguna yang aman (enkripsi AES-256-CBC) dengan fitur export/import file.
10. **Integrasi Telegram Bot**: Menghubungkan akun aplikasi ke Telegram untuk cek saldo, transaksi, melihat laporan via chat, serta mendapatkan notifikasi push pengingat dan peringatan anggaran (budget alert).
11. **Kalender Keuangan (Financial Calendar)**: Memantau dan melihat detail transaksi per tanggal dalam bentuk kalender interaktif, serta dilengkapi fitur *quick-add* untuk langsung menambah transaksi dari kalender.
12. **Filter Transaksi Lanjutan**: Menyaring data riwayat transaksi dengan lebih fleksibel menggunakan *search bar*, rentang waktu, filter tipe/kategori, dan indikator *chip* filter yang sedang aktif.

---

## 🗄 Schema Database
Aplikasi ini memanfaatkan arsitektur database relasional dengan tabel-tabel utama berikut:

- **`users`**
  Tabel induk menyimpan data autentikasi, informasi akun user, serta preferensi seperti `auto_backup_enabled` untuk fitur backup otomatis.

- **`accounts`**
  Menyimpan daftar dompet keuangan user.
  * *Relasi:* dimiliki oleh `users`.
  * *Penting:* Memiliki kolom `type` ('tabungan', 'ewallet', 'tunai'), `balance` (saldo terupdate), dan menerapkan fitur *soft-deletes* demi menjaga riwayat transaksi.

- **`categories`**
  Menyimpan klasifikasi pos transaksi.
  * *Relasi:* dimiliki oleh `users`.
  * *Penting:* `name` (nama kategori) dan `color` (untuk rendering UI).

- **`transactions`**
  Tabel paling sentral sebagai buku besar keluar masuknya uang.
  * *Relasi:* Berelasi ke `users`, ke `categories`, serta `accounts` (sumber dana `account_id` dan tujuan dana `to_account_id` bila jenis transaksinya transfer).
  * *Penting:* Data nominal `amount`, tanggal `date`, dan sifat transaksi `type` ('income', 'expense', atau 'transfer').

- **`budgets`**
  Menyimpan batasan alokasi pengeluaran bulanan.
  * *Relasi:* Berdasar pada `users` dan `categories`.
  * *Penting:* Kolom `limit_amount`, `month`, `year`, dan indikator pengingat seperti `last_alert_threshold`. Bersifat unik kombinasi user, kategori, dan periode detiknya (unique key).

- **`favorite_transactions`**
  Template jalan pintas dari pencatatan keuangan.
  * *Relasi:* Mirip transaksi tetapi disingkat (tanpa date), menyimpan `user_id`, `category_id`, `account_id` yang di referensi.

- **`telegram_accounts`**
  Menyimpan data tautan integrasi user dengan akun Telegram (`telegram_chat_id`) untuk interaksi bot dan notifikasi *push*.

- **`financial_reminders`**
  Menyimpan jadwal dan rincian pengingat keuangan yang ditampilkan di kalender atau dikirimkan lewat notifikasi Telegram.

---

## 🚀 Setup Project & Teknologi Utama
Aplikasi ini dikembangkan modern (Stack *TALL*) dengan mengedepankan reaktivitas single-page feel menggunakan framework berikut:
- **Programming / OS Environment:** PHP (^8.2), Node.js.
- **Backend Framework:** [Laravel 12.0](https://laravel.com/)
- **Frontend / UI Component:** [Livewire 3.6](https://livewire.laravel.com/) dipadukan dengan [Volt 1.7](https://livewire.laravel.com/docs/volt) (fokus komponen UI reaktif di PHP).
- **Styling UI:** [TailwindCSS](https://tailwindcss.com/) dengan plugin `@tailwindcss/forms`.
- **Interaction UI:** [Alpine.js](https://alpinejs.dev/) dipasangkan bundle Livewire.
- **Database Asumsi Default:** SQLite (disimpan di file local), siap di migrasi ke MySQL atau Postgres.
- **Bundler:** Vite.

### 📦 Library / Package Yang Dipakai
- **`maatwebsite/excel` (^3.1)**: Eksekusi rendering output PHP ke Spreadsheets (.xlsx).
- **`chart.js` (^4.5.1)**: Untuk *client-side logic* pembuatan diagram data.
- **`laravel/breeze`**: Boilerplate struktur login yang efisien.
- **`axios`**: *Promise-based* default HTTP client apabila dibutuhkan ajax mandiri.

---

## 🛠 Cara Install & Setup (Development)
Ikuti panduan berikut untuk instalasi dan clone dari repositori untuk dikerjakan secara lokal:

1. **Clone repository**
   ```bash
   git clone <repository_url_disini>
   cd catatanKeuangan
   ```

2. **Install Dependensi Backend**
   Gunakan composer untuk menginstall pustaka library vendor Laravel:
   ```bash
   composer install
   ```

3. **Install Dependensi Frontend**
   Download node packages (JS/CSS compiler dsb):
   ```bash
   npm install
   ```

4. **Persiapan Environment (.env)**
   Silakan gandakan file konfigurasi ENV:
   ```bash
   cp .env.example .env
   ```
   (Jika menggunakan Windows CMD, perintah ganti dengan `copy .env.example .env`)

5. **Generate Application Key**
   Syarat wajib agar fitur enkripsi berjalan:
   ```bash
   php artisan key:generate
   ```

6. **Migrasi Database**
   Otomatis membuat tabel beserta file SQLite (jika belum mensetting driver database lain).
   ```bash
   php artisan migrate
   ```

---

## 🏃‍♂️ Cara Menjalankan Aplikasi Lokal
Karena ini proyek bersistem asset builder modern (Vite), diperlukan dua layanan yang standby. Anda bisa menjalankan dengan cara termudah berikut:

**Cara Super Simpel (Satu Perintah Terminal):**
```bash
composer run dev
```
*(Perintah ini akan secara otomatis melakukan concurrent eksekusi `php artisan serve` beserta queue list, artisan log, dan `npm run dev` dalam sekali eksekusi).*

**Atau dengan Cara Konvensional (Buka 2 Terminal):**
* Terminal 1 (Menyalakan Backend Server):
  ```bash
  php artisan serve
  ```
* Terminal 2 (Menyalakan Asset Vite Builder):
  ```bash
  npm run dev
  ```
Anda kini dapat membuka aplikasi melalui browser: **`http://localhost:8000`**

---

## 🧪 Cara Test Aplikasi (Unit / Feature Testing)
Testing membantu memastikan bahwa integrasi dan logika dalam model atau routing tidak mudah rusak saat ada penambahan fitur. Karena kita menggunakan standar test bawaan framework, cukup jalankan ini di terminal:

```bash
php artisan test
```
atau
```bash
composer run test
```
Informasi passed status atau simulasi *Fail* akan dicetak secara spesifik ke *console*. Pastikan semua pengujian lulus sebelum dipacking menjadi fitur akhir!

---

Dibuat oleh [FruHafizd](https://github.com/FruHafizd) — Coba aplikasinya di [finansiku.vercel.app](https://finansiku.vercel.app/)
