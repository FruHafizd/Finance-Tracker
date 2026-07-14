<?php

namespace App\Services;

use App\Models\User;
use App\Models\TelegramAccount;
use Illuminate\Support\Str;

class TelegramBotService
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * Handle a Telegram update (reusable for both polling and webhook).
     */
    public function handleUpdate($update): void
    {
        if (is_array($update)) {
            $update = new \Telegram\Bot\Objects\Update($update);
        }

        $message = $update->getMessage();
        if (!$message) {
            return;
        }

        $chatId = $message->getChat()->getId();
        $text = trim($message->getText() ?? '');

        if ($text === '') {
            return;
        }

        // 1. Handling /start
        if (str_starts_with($text, '/start')) {
            $this->reply($chatId, "Halo! Selamat datang di bot Finansiku. 📊\n\nUntuk mulai mencatat keuangan via bot ini, hubungkan akun Anda dengan mengirimkan command:\n`/link <kode>`\n\nDapatkan kode 6-digit dari halaman profil/pengaturan web aplikasi Finansiku.");
            return;
        }

        // 2. Handling /link <code>
        if (preg_match('/^\/link\s+(\d{6})$/', $text, $matches)) {
            $code = $matches[1];
            $linked = $this->linkAccount($code, $chatId);

            if ($linked) {
                $this->reply($chatId, "Selamat! Akun Telegram Anda telah berhasil terhubung dengan aplikasi Finansiku. 🎉\n\n*Cara mencatat transaksi cepat:*\n- `keluar <jumlah> <keterangan>` (Contoh: `keluar 15000 kopi susu`)\n- `masuk <jumlah> <keterangan>` (Contoh: `masuk 5000000 bonus`)\n\n*Command lainnya:*\n- /saldo : Cek saldo akun/rekening Anda\n- /laporan : Cek ringkasan pemasukan & pengeluaran bulan ini");
            } else {
                $this->reply($chatId, "Gagal menghubungkan akun. Pastikan kode yang Anda masukkan benar dan belum kedaluwarsa (berlaku 5 menit).");
            }
            return;
        }

        // Untuk command lain, wajib linked
        $user = $this->findUserByChatId($chatId);
        if (!$user) {
            $this->reply($chatId, "Akses ditolak. Silakan hubungkan Telegram Anda terlebih dahulu menggunakan perintah:\n`/link <kode>`");
            return;
        }

        // Simulate user authentication for global scopes & TransactionService
        \Illuminate\Support\Facades\Auth::login($user);

        // 3. Handling /saldo
        if ($text === '/saldo') {
            $accounts = $user->accounts;
            if ($accounts->isEmpty()) {
                $this->reply($chatId, "Kamu belum punya rekening tercatat.");
            } else {
                $messageText = "💰 Saldo Kamu:\n";
                foreach ($accounts as $account) {
                    $icon = match($account->type) {
                        'tabungan' => '🏦',
                        'ewallet'  => '📱',
                        'tunai'    => '💵',
                        default    => '💳'
                    };
                    $balanceFormatted = number_format($account->balance, 0, ',', '.');
                    $messageText .= "{$icon} {$account->name}: Rp {$balanceFormatted}\n";
                }
                $this->reply($chatId, trim($messageText));
            }
            return;
        }

        // 4. Handling /laporan
        if ($text === '/laporan') {
            $filters = [
                'year' => (int) now()->format('Y'),
                'month' => (int) now()->format('n'),
            ];
            $summary = $this->transactionService->getSummary($filters);

            $income = number_format($summary['income'], 0, ',', '.');
            $expense = number_format($summary['expense'], 0, ',', '.');
            $difference = number_format($summary['difference'], 0, ',', '.');

            $sign = $summary['difference'] >= 0 ? '📈' : '📉';

            $messageText = "📊 Laporan Bulan Ini (" . now()->translatedFormat('F Y') . "):\n\n"
                . "📥 Pemasukan: Rp {$income}\n"
                . "📤 Pengeluaran: Rp {$expense}\n"
                . "---------------------------\n"
                . "{$sign} Selisih: Rp {$difference}";

            $this->reply($chatId, $messageText);
            return;
        }

        // 4.5. Handling /? or /help
        if ($text === '/?' || $text === '/help') {
            $this->reply($chatId, "*Cara mencatat transaksi cepat:*\n"
                . "- `keluar <jumlah> <keterangan>` (Contoh: `keluar 15000 kopi susu`)\n"
                . "- `masuk <jumlah> <keterangan>` (Contoh: `masuk 5000000 bonus`)\n\n"
                . "*Menentukan Kategori & Rekening secara spesifik:*\n"
                . "- Gunakan `#nama_kategori` untuk memilih Kategori.\n"
                . "- Gunakan `@nama_rekening` untuk memilih Rekening.\n"
                . "💡 Contoh: `keluar 15000 Bakso #Makanan @Dompet` (Mencatat pengeluaran Bakso Rp 15.000 ke kategori Makanan menggunakan rekening Dompet)\n\n"
                . "*Command lainnya:*\n"
                . "- /saldo : Cek saldo akun/rekening Anda\n"
                . "- /laporan : Cek ringkasan pemasukan & pengeluaran bulan ini");
            return;
        }

        // 5. Handling quick transaction message
        $parsed = $this->parseQuickTransactionMessage($text);
        if ($parsed) {
            $amount = $parsed['amount'];
            $type = $parsed['type'];
            $name = $parsed['name'];
            $customCategory = $parsed['category_name'];
            $customAccount = $parsed['account_name'];

            // Validation
            if ($amount <= 0) {
                $this->reply($chatId, "Gagal: Jumlah transaksi harus lebih besar dari 0.");
                return;
            }

            // Resolve Account
            $account = null;
            if ($customAccount) {
                // Cari case-insensitive
                $account = $user->accounts()
                    ->where('name', 'like', '%' . $customAccount . '%')
                    ->first();
                if (!$account) {
                    $this->reply($chatId, "Gagal: Rekening/Dompet \"{$customAccount}\" tidak ditemukan.");
                    return;
                }
            } else {
                $account = $this->transactionService->getLastUsedAccount($user) ?? $user->accounts()->first();
            }

            // Resolve Category
            $category = null;
            if ($customCategory) {
                // Cari case-insensitive & sesuai dengan type transaksi
                $category = $user->categories()
                    ->where('type', $type)
                    ->where('name', 'like', '%' . $customCategory . '%')
                    ->first();
                if (!$category) {
                    $this->reply($chatId, "Gagal: Kategori \"{$customCategory}\" (tipe " . ($type === 'income' ? 'Pemasukan' : 'Pengeluaran') . ") tidak ditemukan.");
                    return;
                }
            } else {
                $category = $this->transactionService->getLastUsedCategory($user, $type) 
                    ?? $user->categories()->where('type', $type)->first();
            }

            if (!$account) {
                $this->reply($chatId, "Gagal: Anda belum membuat rekening/dompet apapun di Finansiku. Silakan buat di web app terlebih dahulu.");
                return;
            }

            if (!$category) {
                $this->reply($chatId, "Gagal: Kategori untuk transaksi tipe ini tidak ditemukan. Silakan buat di web app terlebih dahulu.");
                return;
            }

            try {
                $transaction = $this->transactionService->createTransaction([
                    'type' => $type,
                    'amount' => $amount,
                    'name' => $name,
                    'account_id' => $account->id,
                    'category_id' => $category->id,
                    'date' => now()->format('Y-m-d'),
                ], $user);

                $amountFormatted = number_format($transaction->amount, 0, ',', '.');
                $typeLabel = $type === 'income' ? 'Pemasukan' : 'Pengeluaran';
                $replyMessage = "✅ Transaksi berhasil dicatat!\n\n"
                    . "📝 Nama: {$transaction->name}\n"
                    . "💰 Jumlah: Rp {$amountFormatted}\n"
                    . "🏷️ Jenis: {$typeLabel}\n"
                    . "🏦 Dompet: {$account->name}\n"
                    . "📁 Kategori: {$category->name}";

                // Check budget alert
                $budgetAlert = $this->transactionService->checkBudgetAlert($user->id, $category->id);
                if ($budgetAlert) {
                    $replyMessage .= "\n\n" . $budgetAlert['title'] . "\n" . $budgetAlert['message'];
                }

                $this->reply($chatId, $replyMessage);
            } catch (\Exception $e) {
                $this->reply($chatId, "Terjadi kesalahan saat mencatat transaksi. Silakan coba beberapa saat lagi.");
            }
            return;
        }

        // 6. Unknown command / format
        $this->reply($chatId, "Format pesan tidak dikenali. 🧐\n\n"
            . "Gunakan perintah berikut:\n"
            . "- /saldo : Cek saldo dompet/rekening Anda\n"
            . "- /laporan : Cek laporan bulan berjalan\n"
            . "- `masuk <jumlah> <keterangan>` : Input pemasukan cepat\n"
            . "- `keluar <jumlah> <keterangan>` : Input pengeluaran cepat\n\n"
            . "💡 *Format Lengkap Kategori & Rekening:*\n"
            . "keluar <jumlah> <keterangan> #kategori @rekening\n"
            . "Contoh: keluar 25000 Nasi Goreng #Makanan @Tunai");
    }

    /**
     * Send a reply to the specified chat ID.
     */
    public function reply(int $chatId, string $text): void
    {
        try {
            \Telegram\Bot\Laravel\Facades\Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => 'Markdown',
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to send Telegram message: " . $e->getMessage());
        }
    }

    /**
     * Generate 6-digit random code, save to telegram_accounts with 5-minute expiry.
     */
    public function generateLinkCode(User $user): string
    {
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        TelegramAccount::updateOrCreate(
            ['user_id' => $user->id],
            [
                'link_code' => $code,
                'link_code_expires_at' => now()->addMinutes(5),
            ]
        );

        return $code;
    }

    /**
     * Link Telegram chat ID with a user using code.
     */
    public function linkAccount(string $code, int $chatId): bool
    {
        $telegramAccount = TelegramAccount::where('link_code', $code)
            ->where('link_code_expires_at', '>', now())
            ->first();

        if (!$telegramAccount) {
            return false;
        }

        // Hapus link_code yang sama pada chat_id lama jika ada untuk mencegah tabrakan unique key
        TelegramAccount::where('telegram_chat_id', $chatId)->update([
            'telegram_chat_id' => null
        ]);

        $telegramAccount->update([
            'telegram_chat_id' => $chatId,
            'link_code' => null,
            'link_code_expires_at' => null,
        ]);

        return true;
    }

    /**
     * Find User by telegram_chat_id.
     */
    public function findUserByChatId(int $chatId): ?User
    {
        $telegramAccount = TelegramAccount::where('telegram_chat_id', $chatId)->first();

        return $telegramAccount ? $telegramAccount->user : null;
    }

    /**
     * Parse text like "keluar 15000 makan siang" or "masuk 250000 bonus" into transaction details.
     * Supported keywords: masuk/in for income, keluar/out for expense.
     */
    public function parseQuickTransactionMessage(string $text): ?array
    {
        $text = trim($text);

        // Pattern regex: (keluar|masuk|in|out)\s+(\d+)\s+(.+)
        if (preg_match('/^(keluar|masuk|in|out)\s+(\d+)\s+(.+)$/i', $text, $matches)) {
            $keyword = strtolower($matches[1]);
            $amount = (int) $matches[2];
            $payload = trim($matches[3]);

            $type = in_array($keyword, ['masuk', 'in']) ? 'income' : 'expense';

            // Extract hashtag category (#kategori)
            $categoryName = null;
            if (preg_match('/#(\w+([\s-]\w+)*)/i', $payload, $catMatches)) {
                $categoryName = trim($catMatches[1]);
                $payload = str_replace($catMatches[0], '', $payload);
            }

            // Extract account / wallet (@rekening)
            $accountName = null;
            if (preg_match('/@(\w+([\s-]\w+)*)/i', $payload, $accMatches)) {
                $accountName = trim($accMatches[1]);
                $payload = str_replace($accMatches[0], '', $payload);
            }

            // Bersihkan sisa nama transaksi dari double space
            $name = trim(preg_replace('/\s+/', ' ', $payload));

            return [
                'type' => $type,
                'amount' => $amount,
                'name' => $name ?: ($type === 'income' ? 'Pemasukan' : 'Pengeluaran'),
                'category_name' => $categoryName,
                'account_name' => $accountName,
            ];
        }

        return null;
    }

    /**
     * Unlink Telegram account for the user.
     */
    public function unlinkAccount(User $user): void
    {
        if ($user->telegramAccount) {
            $user->telegramAccount->update([
                'telegram_chat_id' => null,
                'link_code' => null,
                'link_code_expires_at' => null,
            ]);
        }
    }
}
