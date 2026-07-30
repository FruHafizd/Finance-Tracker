<?php

namespace App\Services;

use App\Models\Budget;
use App\Models\FinancialReminder;
use App\Models\TelegramAccount;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TelegramAlertService
{
    /**
     * Timezone yang digunakan untuk semua perhitungan tanggal.
     */
    private const TIMEZONE = 'Asia/Jakarta';

    public function __construct(
        protected TelegramBotService $telegramBotService
    ) {}

    /**
     * Periksa semua budget aktif dan kirim alert jika melewati threshold.
     */
    public function checkAndSendBudgetAlerts(): void
    {
        $thresholds = config('telegram.alerts.budget_thresholds', [80, 100]);

        // Ambil bulan & tahun saat ini berdasarkan WIB
        $now   = Carbon::now(self::TIMEZONE);
        $month = (int) $now->format('n');
        $year  = (int) $now->format('Y');

        // Ambil semua budget bulan ini yang user-nya punya telegram_chat_id
        // PENTING: bypass global scope 'user' karena tidak ada Auth user di context cron
        $budgets = Budget::withoutGlobalScope('user')
            ->with(['category' => function ($query) {
                // Category juga punya global scope 'user', harus di-bypass juga
                $query->withoutGlobalScope('user');
            }, 'user.telegramAccount'])
            ->where('month', $month)
            ->where('year', $year)
            ->whereHas('user.telegramAccount', function ($query) {
                $query->whereNotNull('telegram_chat_id');
            })
            ->get();

        foreach ($budgets as $budget) {
            $this->processBudgetAlert($budget, $thresholds);
        }
    }

    /**
     * Proses satu budget: cek apakah perlu kirim alert.
     */
    private function processBudgetAlert(Budget $budget, array $thresholds): void
    {
        // Hitung spent amount TANPA global scope (context cron tanpa auth)
        $spent      = $this->getSpentAmount($budget);
        $limit      = $budget->limit_amount;
        $percentage = $limit > 0 ? ($spent / $limit) * 100 : 0;

        // Urutkan threshold dari besar ke kecil agar kita ambil yang tertinggi yang terlewati
        rsort($thresholds);

        foreach ($thresholds as $threshold) {
            if ($percentage >= $threshold) {
                // Cek apakah threshold ini sudah pernah di-alert
                $lastAlerted = $budget->last_alert_threshold;
                if ($lastAlerted !== null && $lastAlerted >= $threshold) {
                    // Sudah pernah alert untuk threshold ini atau lebih tinggi, skip
                    return;
                }

                // Siapkan pesan (plain text, tanpa Markdown formatting)
                $chatId       = $budget->user->telegramAccount->telegram_chat_id;
                $categoryName = $budget->category->name ?? 'Tanpa Kategori';
                $spentFormat  = 'Rp ' . number_format($spent, 0, ',', '.');
                $limitFormat  = 'Rp ' . number_format($limit, 0, ',', '.');

                if ($threshold >= 100) {
                    $message = "🚨 Budget {$categoryName} sudah melebihi limit! ({$spentFormat} dari {$limitFormat})";
                } else {
                    $message = "⚠️ Budget {$categoryName} sudah mencapai {$threshold}% ({$spentFormat} dari {$limitFormat})";
                }

                $sent = $this->trySendMessage($chatId, $message, $budget->user);
                if ($sent) {
                    $budget->update(['last_alert_threshold' => $threshold]);
                }

                // Sudah kirim untuk threshold tertinggi yang terlewati, stop
                return;
            }
        }
    }

    /**
     * Hitung spent amount tanpa global scope (untuk context cron tanpa auth).
     */
    private function getSpentAmount(Budget $budget): float
    {
        return \App\Models\Transaction::withoutGlobalScopes()
            ->where('user_id', $budget->user_id)
            ->where('category_id', $budget->category_id)
            ->where('type', 'expense')
            ->whereMonth('date', $budget->month)
            ->whereYear('date', $budget->year)
            ->sum('amount');
    }

    /**
     * Periksa semua financial reminders dan kirim reminder jika mendekati jatuh tempo.
     */
    public function checkAndSendDueDateReminders(): void
    {
        $reminderDays = config('telegram.alerts.due_date_reminder_days', [3, 1]);

        $now   = Carbon::now(self::TIMEZONE);
        $month = (int) $now->format('n');
        $year  = (int) $now->format('Y');

        // Ambil semua reminder bulan ini yang user-nya punya telegram_chat_id
        $reminders = FinancialReminder::with(['user.telegramAccount'])
            ->where('month', $month)
            ->where('year', $year)
            ->whereHas('user.telegramAccount', function ($query) {
                $query->whereNotNull('telegram_chat_id');
            })
            ->get();

        foreach ($reminders as $reminder) {
            $this->processDueDateReminder($reminder, $reminderDays, $now);
        }
    }

    /**
     * Proses satu reminder: cek apakah perlu kirim notifikasi.
     */
    private function processDueDateReminder(FinancialReminder $reminder, array $reminderDays, Carbon $now): void
    {
        // Bangun tanggal jatuh tempo dari kolom day, month, year
        try {
            $dueDate = Carbon::createFromDate($reminder->year, $reminder->month, $reminder->day, self::TIMEZONE);
        } catch (\Exception $e) {
            // Tanggal tidak valid (misal 31 Februari), skip
            Log::warning("Invalid due date for reminder #{$reminder->id}: {$reminder->year}-{$reminder->month}-{$reminder->day}");
            return;
        }

        // Hitung selisih hari dari sekarang ke jatuh tempo
        // PENTING: gunakan copy() agar tidak mengubah object $now dan $dueDate asli
        // Parameter false pada diffInDays = bisa menghasilkan angka negatif jika due date sudah lewat
        $daysUntilDue = (int) $now->copy()->startOfDay()->diffInDays($dueDate->copy()->startOfDay(), false);

        // Cek apakah hari ini cocok dengan salah satu reminder day
        foreach ($reminderDays as $daysBefore) {
            if ($daysUntilDue === $daysBefore) {
                // Cek apakah reminder ini sudah dikirim hari ini
                if ($reminder->last_reminder_sent_at !== null) {
                    $lastSent = Carbon::parse($reminder->last_reminder_sent_at)->setTimezone(self::TIMEZONE);
                    if ($lastSent->isSameDay($now)) {
                        // Sudah dikirim hari ini, skip
                        return;
                    }
                }

                // Siapkan pesan (plain text, tanpa Markdown formatting)
                $chatId       = $reminder->user->telegramAccount->telegram_chat_id;
                $description  = $reminder->description;
                $amountFormat = 'Rp ' . number_format($reminder->amount, 0, ',', '.');

                if ($daysBefore === 0) {
                    $message = "🔔 {$description} jatuh tempo hari ini ({$amountFormat})";
                } elseif ($daysBefore === 1) {
                    $message = "🔔 {$description} jatuh tempo besok ({$amountFormat})";
                } else {
                    $message = "🔔 {$description} jatuh tempo dalam {$daysBefore} hari ({$amountFormat})";
                }

                $sent = $this->trySendMessage($chatId, $message, $reminder->user);
                if ($sent) {
                    $reminder->update(['last_reminder_sent_at' => $now]);
                }

                // Sudah kirim untuk hari ini, stop
                return;
            }
        }
    }

    /**
     * Coba kirim pesan Telegram via TelegramBotService.
     * Return true jika berhasil, false jika gagal.
     * Jika error "chat not found" atau "bot was blocked", auto-unlink chat_id user.
     */
    private function trySendMessage(int $chatId, string $message, $user): bool
    {
        try {
            $this->telegramBotService->sendMessage($chatId, $message);

            Log::info("Telegram alert sent to chat_id {$chatId}");
            return true;

        } catch (\Telegram\Bot\Exceptions\TelegramResponseException $e) {
            $errorMessage = strtolower($e->getMessage());

            // Deteksi error yang menandakan chat_id tidak valid lagi
            $unlinkErrors = [
                'chat not found',
                'bot was blocked by the user',
                'user is deactivated',
                'forbidden',
            ];

            $shouldUnlink = false;
            foreach ($unlinkErrors as $errorKeyword) {
                if (str_contains($errorMessage, $errorKeyword)) {
                    $shouldUnlink = true;
                    break;
                }
            }

            if ($shouldUnlink) {
                Log::warning("Auto-unlinking Telegram for user #{$user->id}: {$e->getMessage()}");
                TelegramAccount::where('user_id', $user->id)->update([
                    'telegram_chat_id' => null,
                ]);
            } else {
                Log::error("Failed to send Telegram alert to chat_id {$chatId}: {$e->getMessage()}");
            }

            return false;

        } catch (\Exception $e) {
            Log::error("Unexpected error sending Telegram alert to chat_id {$chatId}: {$e->getMessage()}");
            return false;
        }
    }
}
