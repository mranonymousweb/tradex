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
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('symbol');
            $table->string('icon')->nullable();
            $table->string('price_receiver')->nullable()->comment('price supplier LIKE gate io');
            $table->json('exchanges')->nullable()->comment('Exchanges like [Gateio, Binance, Coinex, Bingx]');
            $table->decimal('price', 15, 8)->default(0)->comment('Currency Price In USDT');
            $table->tinyInteger('type')->comment('1:Crypto, 2:Fiat,3:Voucher, 4:Other');
            $table->tinyInteger('status')->default(1)->comment('1:Active, 2:Inactive');
            $table->integer('rank')->nullable()->comment('Rank in supplier');
            $table->integer('unique_id')->nullable()->comment('Currency ID in supplier');
            $table->string('slug')->nullable()->comment('Currency slug in supplier');
            $table->string('supplier')->nullable()->comment('Currency supplier LIKE gate io');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};
