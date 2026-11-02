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
        Schema::table('swaps', function (Blueprint $table) {
            //
            $table->string('swap_metod')->nullable();
            $table->double('usdt_irt_sell_price')->nullable();
            $table->double('usdt_irt_buy_price')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('swaps', function (Blueprint $table) {
            //
            $table->dropColumn([
                'swap_metod',
                'usdt_irt_sell_price',
                'usdt_irt_buy_price'
            ]);
        });
    }
};
