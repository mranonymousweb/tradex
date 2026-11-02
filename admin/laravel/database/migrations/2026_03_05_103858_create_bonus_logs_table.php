<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bonus_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bonus_id')->constrained()->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('currency_id')->constrained()->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('user_id')->constrained()->nullOnDelete()->cascadeOnUpdate();
            $table->decimal('amount', 20, 10)->nullable();
            $table->string('symbol')->nullable();
            $table->string('section')->nullable();
            $table->string('status')->nullable();
            $table->dateTime('finish_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bonus_logs');
    }
};
