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
        // 1. Ubah foreign key category_id pada tabel transactions
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories')
                  ->cascadeOnDelete();
        });

        // 2. Ubah foreign key category_id pada tabel favorite_transactions
        Schema::table('favorite_transactions', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories')
                  ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke restrictOnDelete
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories')
                  ->restrictOnDelete();
        });

        Schema::table('favorite_transactions', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories')
                  ->restrictOnDelete();
        });
    }
};
