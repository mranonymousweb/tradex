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
        Schema::create('favorite_market_currencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreignId('market_currency_id')->constrained('market_currencies')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->timestamps();
            $table->unique(['user_id', 'market_currency_id'], 'user_market_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorite_market_currencies');
    }
};
