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


        Schema::create('order_taker_bots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('currency_id')->nullable()->constrained('currencies')->nullOnDelete()->cascadeOnUpdate();
            $table->string('symbol', 10)->nullable();
            $table->decimal('sellPercent');
            $table->decimal('buyPercent');
            $table->decimal('fillAmountPercentage')->default(0);
            $table->boolean('sendToContactor')->default(false);
            $table->integer('status')->default(1);
            $table->json('currencies')->nullable();
            $table->integer('secount');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_taker_bots');
    }
};
