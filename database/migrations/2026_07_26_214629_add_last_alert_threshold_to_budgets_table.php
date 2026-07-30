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
        Schema::table('budgets', function (Blueprint $table) {
            $table->unsignedTinyInteger('last_alert_threshold')->nullable()->after('year');
            // Menyimpan persentase threshold terakhir yang sudah di-alert (80 atau 100)
            // null = belum pernah ada alert di periode ini
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('budgets', function (Blueprint $table) {
            $table->dropColumn('last_alert_threshold');
        });
    }
};
