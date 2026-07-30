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
        Schema::table('transactions', function (Blueprint $table) {
            // Drop existing foreign key constraints
            $table->dropForeign(['account_id']);
            $table->dropForeign(['to_account_id']);

            // Re-add with cascadeOnDelete
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('to_account_id')->references('id')->on('accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Drop cascade constraints
            $table->dropForeign(['account_id']);
            $table->dropForeign(['to_account_id']);

            // Re-add with restrictOnDelete
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('restrict');
            $table->foreign('to_account_id')->references('id')->on('accounts')->onDelete('restrict');
        });
    }
};
