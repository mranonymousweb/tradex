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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->decimal('withdrawal_max', 60, 20)->nullable();
            $table->decimal('withdrawal_min_fiat', 40, 15)->nullable();
            $table->decimal('deposit_max_fiat', 40, 15)->nullable();
            $table->decimal('deposit_min_fiat', 40, 15)->nullable();
            $table->decimal('fee_fiat', 40, 15)->nullable();
            $table->boolean('deposit')->nullable();
            $table->boolean('withdrawal')->nullable();
            $table->decimal('internal_transfer_fee')->nullable();
            $table->decimal('currency_maker_fee', 14, 8)->nullable();
            $table->decimal('currency_taker_fee', 14, 8)->nullable();
            $table->decimal('currency_taker_fee_max', 40, 15)->nullable();
            $table->decimal('currency_maker_fee_max', 40, 15)->nullable();
            $table->decimal('fiat_maker_fee', 14, 8)->nullable();
            $table->decimal('fiat_taker_fee', 14, 8)->nullable();
            $table->decimal('fiat_maker_fee_max', 40, 15)->nullable();
            $table->decimal('fiat_taker_fee_max', 40, 15)->nullable();
            $table->string('status')->default(\App\Models\Package::STATUS_INACTIVE);
            $table->decimal('amount_to_grant', 14, 8)->default(0);
            $table->decimal('marketing_profit', 40, 10)->default(0);
            $table->decimal('currency_min_deposit', 40, 15)->nullable();
            $table->decimal('currency_min_withdrawal', 40, 15)->nullable();
            $table->integer('withdraw_time_limit')->nullable();
            $table->integer('withdraw_first_time_limit')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
