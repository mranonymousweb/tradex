<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('future_adl_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('base_currency_id')
                ->constrained('currencies')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->unsignedTinyInteger('adl_min_exchange_profit_percent')
                ->default(0)
                ->comment('Minimum exchange profit percent (0-100)');
            $table->unsignedBigInteger('min_trade_amount_usdt')
                ->default(0)
                ->comment('Current balance / min trade amount');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('future_adl_settings');
    }
};
