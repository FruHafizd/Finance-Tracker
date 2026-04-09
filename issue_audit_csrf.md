# Task: Audit dan Remediasi Keamanan CSRF (Cross-Site Request Forgery)

## Latar Belakang
Pemilik website ingin memastikan bahwa aplikasi (berbasis Laravel) terlindungi dengan baik dari serangan CSRF (Cross-Site Request Forgery). Serangan CSRF adalah jenis serangan manipulasi di mana *attacker* memaksa browser pengguna yang terautentikasi untuk mengeksekusi request yang tidak diinginkan ke aplikasi web tujuan. Oleh karena itu, diperlukan proses audit dan testing keamanan menyeluruh, dan jika ditemukan celah pada form atau request AJAX, hal tersebut harus segera diperbaiki.

---

## Instruksi untuk Junior Programmer / AI Agent

Dokumen ini adalah panduan kerja sistematis untuk melakukan pengujian CSRF di dalam aplikasi.

**Catatan Khusus Arsitektur Ini:**
Aplikasi ini dibangun dengan *framework* **Laravel v11/v12** dan sangat bergantung pada komponen **Livewire**. 
- Secara *default*, Laravel mengaktifkan validasi token CSRF secara global melalui middleware bawaannya (untuk semua route berjenis `web`).
- Framework **Livewire** juga secara otomatis menangani pengiriman token CSRF pada setiap request internalnya yang menggunakan mekanisme AJAX, asalkan tag `<meta name="csrf-token">` tersedia secara global di *layout* utama.

### Tahap 1: Static Code Analysis (Pemeriksaan Konfigurasi Dasar)

Tugas pertama Anda adalah memeriksa konfigurasi dasar sistem dan *blade templates*.

**Langkah-Langkah:**
1. **Periksa Konfigurasi Middleware:** Buka file `bootstrap/app.php` (untuk Laravel 11+) atau `app/Http/Middleware/VerifyCsrfToken.php` (untuk versi lama). Pastikan tidak ada fungsi `->validateCsrfTokens(except: [...])` atau array `$except` yang secara tidak sengaja mematikan perlindungan API/Web rute dari CSRF tanpa alasan kuat.
2. **Periksa Global Meta Tag:** Buka main layout file (contoh: `resources/views/layouts/app.blade.php` atau `guest.blade.php`). Pastikan di dalam `<head>` terdapat baris kode berikut:
   ```html
   <meta name="csrf-token" content="{{ csrf_token() }}">
   ```
3. **Pencarian Form Manual:** Lakukan pencarian (Grep) kata kunci `<form` di direktori `resources/views/`. 
   - Untuk setiap form HTML standar yang menggunakan method `POST`, `PUT`, `PATCH`, atau `DELETE`, pastikan terdapat *directive* `@csrf` di dalamnya.
   - ⚠️ *Note: Jika form menggunakan atribut `wire:submit` (Livewire component), Anda tidak perlu menambahkan `@csrf` karena Livewire telah menanganinya di belakang layar secara otomatis!*

### Tahap 2: Dynamic Testing (Pengujian Eksploitasi Eksternal)

Lakukan simulasi pengujian CSRF melalui terminal atau fitur interceptor standar (misalnya mencoba melakukan request POST dari origin luar).

**Langkah-Langkah:**
1. Kunjungi halaman utama aplikasi dan buka Developer Tools di Browser (tekan F12) lalu buka *Network Tab*.
2. Lakukan satu aksi `POST` yang valid (misalnya Submit Budget atau Tambah Rekening) menggunakan aplikasi.
3. Amati isi request tersebut. Pastikan `X-CSRF-TOKEN` atau payload `_token` terkirim bersama body data.
4. Salin request aslinya (misalnya sebagai *cURL* command).
5. Lakukan percobaan modifikasi: **Hapus token CSRF** tersebut atau ubah beberapa karakternya menjadi acak di command cURL, dan jalankan secara manual di terminal bash/powershell/Postman.
6. **Amati hasil:** Jika aplikasi merespons dengan HTTP Status **419 (Page Expired)** atau **Token Mismatch Exception**, maka proteksi CSRF bekerja dengan **SEMPURNA**. Jika merespon 200/201 (Berhasil diproses), maka aplikasi **RENTAN**.

### Tahap 3: Remediasi (Penyelesaian Masalah)

Bila Anda menemukan kerentanan pada sebuah form atau proses POST yang mem-bypass CSRF, patuhi cara perbaikan berikut:

**Solusi Form Standar Blade:**
```html
<form method="POST" action="/transaction">
    @csrf
    <!-- Input Form di Sini -->
</form>
```

**Solusi Javascript Custom Request (Fetch / Axios):**
Jika ada script Javascript kustom (bukan bawaan Livewire) yang melakukan request HTTP POST, pastikan header diset seperti ini:
```javascript
fetch('/api/custom-endpoint', {
   method: 'POST',
   headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
   },
   body: JSON.stringify({ data: 'contoh' })
})
```

---

## 📝 Check-list Eksekusi (Untuk Tim Pengimplementasi)

Gunakan *checklist* di bawah ini sebagai pedoman bahwa Anda telah mengeksekusi semua pemeriksaan:

- [x] Memastikan `bootstrap/app.php` tidak memiliki properti *exception* (pengecualian) CSRF berbahaya pada blok `validateCsrfTokens()`.
- [x] Mengecek meta tag `<meta name="csrf-token" content="{{ csrf_token() }}">` pada semua file Layout (`app.blade.php`, `guest.blade.php`).
- [x] Menemukan seluruh `<form>` standar di sistem dan memverifikasi eksistensi direktif `@csrf`.
- [x] Melakukan tes dinamis dengan mengirimkan HTTP Request (bertipe mengubah data/state application) *tanpa menggunakan Token CSRF valid* dan mengonfirmasi mendapatkan error 419 Page Expired.
- [x] Menyatakan aplikasi **AMAN** dari serangan form-forgery.

---

## Catatan Dokumentasi untuk Analisa Lanjut
*(Bagian pendataan wajib)*
- **Fitur/Path yang Diperiksa:** Seluruh Form Registrasi, Login, Budget, Rekening, Transaksi.
- **Teknologi Backend Dominan Ditemukan:** Livewire (Menangani proteksi otomatis melalui AJAX header token meta).
- **Status Akhir Keseluruhan:** ✅ AMAN DARI CSRF (Diverifikasi melalui audit statis dan simulasi serangan yang menghasilkan HTTP 419 Page Expired).
