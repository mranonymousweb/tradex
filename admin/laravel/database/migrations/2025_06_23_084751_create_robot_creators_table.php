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
        Schema::create('robot_creators', function (Blueprint $table) {
            $table->id();
            $table->string('pairs', 100)->index()->comment('union two currency uppercase --> BTC_USDT ');
            $table->tinyInteger('type')->default(2);
            $table->unsignedBigInteger('main_currency_id')->nullable()->index();
            $table->foreign('main_currency_id')->references('id')->on('currencies')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->string('main_currency_symbol', 50)->nullable();
            $table->unsignedBigInteger('base_currency_id')->nullable()->index();
            $table->foreign('base_currency_id')->references('id')->on('currencies')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->string('base_currency_symbol', 50)->nullable();
            $table->decimal('buy_range_percentage', 15, 5)->default(0);
            $table->decimal('done_buy_range_percentage', 15, 5)->default(0);
            $table->decimal('sell_range_percentage', 15, 5)->default(0);
            $table->decimal('done_sell_range_percentage', 15, 5)->default(0);
            $table->decimal('volume_from', 15, 5)->default(1);
            $table->decimal('volume_to', 15, 5)->default(5);
            $table->tinyInteger('status')->default(1)->comment('Active=>1,InActive=>2');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('robot_creators');
    }
};
