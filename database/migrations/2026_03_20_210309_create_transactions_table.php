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
        Schema::create('transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();
                $table->foreignId('account_id')->constrained()->restrictOnDelete();

                $table->string("name");
                $table->unsignedInteger("amount");
                $table->enum("type",["income","expense","transfer"]);
                $table->date("date");

                $table->timestamps();
                $table->index(["user_id","date"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};