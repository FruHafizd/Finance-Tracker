# Bug Fix & Enhancement: Sinkronisasi Edit Transfer & Tampilan Tabel Transaksi

## Deskripsi Masalah

Saat ini terdapat dua masalah terkait fitur transaksi bernilai transfer yang sudah diimplementasikan sebelumnya:
1.  **Bug pada proses Edit:** Ketika mengedit detail transaksi yang bertipe 'transfer', data di dalam database tabel `transactions` berubah, tetapi saldo pada rekening asal dan tujuan *tidak* diperbarui sesuai dengan perubahan nominal yang baru.
2.  **Kekurangan pada UI:** Pada halaman daftar transaksi (Tabel Index Transaksi), tidak ada penanda visual yang menunjukkan bahwa sebuah transaksi adalah 'transfer'. Saat ini hanya terdapat dua jenis indikator: pengeluaran atau pemasukan.

## Objektif

1.  **Memperbaiki logika sinkronisasi `TransactionObserver`:** Memastikan ketika *update* transaksi tipe transfer dilakukan, sistem melakukan *reverse* saldo lama dengan benar pada kedua sisi (rekening asal dan tujuan), lalu mengaplikasikan saldo baru.
2.  **Memperbarui Tampilan Index Transaksi:** Mengubah komponen tabel transaksi untuk mengakomodasi tipe 'transfer' dengan penanda warna dan teks yang unik (misalnya warna biru).

---

## Rencana Implementasi

Berikut adalah langkah-langkah detail yang harus dilakukan untuk menyelesaikan issue ini:

### Tahap 1: Memperbaiki Sinkronisasi Edit Transaksi (`TransactionObserver`)

**File target:** `app/Observers/TransactionObserver.php`

**Instruksi:**
1.  Fokus pada fungsi `updated(Transaction $transaction)`.
2.  Logika saat ini sudah mencoba untuk me-reverse transaksi lama dan mengapply transaksi baru:
    ```php
    // Saat ini
    $oldAmount = $transaction->getOriginal('amount');
    $oldType = $transaction->getOriginal('type');
    $oldAccountId = $transaction->getOriginal('account_id');
    $oldToAccountId = $transaction->getOriginal('to_account_id');

    $this->reverseTransactionValues($oldType, $oldAmount, $oldAccountId, $oldToAccountId);
    $this->applyTransaction($transaction);
    ```
3.  **Masalah utama yang perlu diperiksa/diperbaiki:** Pastikan fungsi `reverseTransactionValues` menangani pembalikan dari tipe transfer dari *data asli (original)*.
    *   Jika `oldType` = `transfer` dan `oldToAccountId` ada:
        *   `account` (sumber) harus di *increment* (dikembalikan saldonya).
        *   `toAccount` (tujuan) harus di *decrement* (dikurangi saldonya).
4.  Pastikan juga fungsi `applyTransaction` memotong dan menambahkan saldo ke `account_id` dan `to_account_id` yang *baru* melalui `$transaction->amount`.
5.  *Catatan Debugging:* Jika observer bekerja, periksa apakah ada tempat lain yang *bypass* events Eloquent saat melakukan update (misalnya menggunakan fungsi `update()` query builder ketimbang model Eloquent). Pastikan Livewire Component memanggil `save()` pada instance Model atau menggunakan metode yang memicu event `updated`.

---

### Tahap 2: Memperbarui Livewire Component & UI Tabel (`transaction-index.blade.php`)

**File target:** Harus dicek di direktori `resources/views/livewire/transactions/transaction-index.blade.php` atau `transaction-list.blade.php` (silakan gunakan *Global Search*).

**Instruksi:**
1.  Cari bagian kode yang menangani *badge* atau indikator tipe transaksi. Kemungkinan besar saat ini terstruktur seperti:
    ```blade
    @if($transaction->type === 'income')
        <span class="text-green-500">Pemasukan</span>
    @else
        <span class="text-red-500">Pengeluaran</span>
    @endif
    ```
2.  Ubah logika tersebut menjadi dukungan untuk 3 tipe (income, expense, transfer).
    *   **Income (Pemasukan):** Teks hijau atau *badge* hijau.
    *   **Expense (Pengeluaran):** Teks merah/orange merah muda.
    *   **Transfer (Transfer):** Tambahkan warna *Biru/Indigo* (misalnya `text-blue-500` / `bg-blue-100 text-blue-700`).
3.  Pastikan nama rekening (sumber dan tujuan) dapat ditampilkan dengan baik jika memungkinkan. Misalnya: `"Bank BCA -> Tunai"`.
    *   Perhatikan bahwa properti `to_account_id` atau relasi `toAccount` sudah ada di Model `Transaction`.

---

### Tahap 3: Persiapan Database (Migrate & Seed)

Untuk memastikan pengujian berjalan di lingkungan yang bersih dan aman dari data yang mungkin korup dari implementasi sebelumnya, lakukan reset database sebelum memulai implementasi dan pengujian mendalam:
1.  Jalankan perintah berikut pada terminal di root proyek:
    ```bash
    php artisan migrate:fresh --seed
    ```
2.  *Catatan:* Pastikan `DatabaseSeeder.php` sudah memiliki data dummy yang memadai (minimal ada user, beberapa kategori, dan default akun/rekening) agar lebih mudah saat testing. Login menggunakan kredensial dummy tersebut.

---

### Tahap 4: Pengujian (Sangat Penting)

1.  **Pengujian Edit Transfer:**
    *   Buat transaksi Transfer baru (A -> B = Rp100.000). Catat saldo A & B.
    *   Edit nominal transaksi tersebut menjadi Rp200.000.
    *   Cek saldo: Rekening A harus berkurang totalnya Rp200.000, Rekening B harus bertambah totalnya Rp200.000 (disesuaikan selisih dari nilai awal).
2.  **Pengujian Tampilan:**
    *   Buka halaman index transaksi (misal: `/transactions`).
    *   Pastikan tabel membedakan baris transaksi mana yang Pemasukan, Pengeluaran, dan mana yang Transfer.

***Semangat! Gunakan instruksi ini sebagai panduan langkah demi langkah. Jika terjadi masalah, gunakan `dd()` atau `Log::info()` untuk memvalidasi nilai di observer.***
