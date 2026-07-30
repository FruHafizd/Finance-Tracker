<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('financial_reminders', function (Blueprint $table) {
            $table->timestamp('last_reminder_sent_at')->nullable()->after('remind_before');
            // Menyimpan kapan terakhir kali reminder Telegram dikirim
            // null = belum pernah dikirim untuk jatuh tempo saat ini
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('financial_reminders', function (Blueprint $table) {
            $table->dropColumn('last_reminder_sent_at');
        });
    }
};
