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
        Schema::create('market_currencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('base_currency_id')->nullable()->constrained('currencies')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('main_currency_id')->nullable()->constrained('currencies')->nullOnDelete()->cascadeOnUpdate();
            // --- Currency symbols ---
            $table->string('base_currency_symbol', 50)->nullable();
            $table->string('main_currency_symbol', 50)->nullable();
            $table->string('pairs', 50)->comment('Uppercase format: market-currency_base-currency e.g., BTC_USDT');
            $table->unsignedTinyInteger('type')->default(1)->comment('1: Spot, 2: Future');
            $table->unsignedTinyInteger('status')->default(1)->comment('1: Active, 2: Inactive');
            $table->boolean('purchasable')->default(true);
            $table->boolean('sellable')->default(true);
            $table->tinyInteger('leverage')->default(125);
            $table->decimal('min_trade_amount_usdt', 20, 8)->default(0)->comment('Minimum trade amount In USDT');
            $table->decimal('increase_percent', 20, 8)->default(0);
            $table->decimal('increase_value', 20, 8)->default(0);
            $table->decimal('decrease_percent', 20, 8)->default(0);
            $table->decimal('decrease_value', 20, 8)->default(0);
            $table->float('rank')->default(1);
            $table->softDeletes();
            $table->timestamps();
            $table->unique(['pairs', 'type'], 'three_columns_unique');
            $table->index(['pairs', 'status', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('market_currencies');
    }
};
