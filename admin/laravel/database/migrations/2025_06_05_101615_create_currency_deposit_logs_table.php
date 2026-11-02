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
        Schema::create('currency_deposit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->string('tx_id')->unique()->nullable();
            $table->string('from_address')->nullable();
            $table->string('address')->nullable();
            $table->string('symbol')->nullable();
            $table->decimal('amount', 65, 30)->default(0);
            $table->double('is_used')->default(false); // change to boolean
            $table->string('network')->nullable();
            $table->text('tag')->nullable();
            $table->string('wallet_status')->default(\App\Models\CurrencyDepositLog::WALLET_STATUS_WAITING);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currency_deposit_logs');
    }
};
