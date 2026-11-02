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
        Schema::create('user_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wallet_id')->nullable();
            $table->unsignedBigInteger('asset_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('currency_id');
            $table->unsignedBigInteger('network_id')->nullable();
            $table->unsignedBigInteger('bank_id')->nullable();
            $table->foreignId('card_id')->nullable()->constrained('user_cards')->nullOnDelete()->nullOnUpdate();
            $table->nullableMorphs('transactionable');
            $table->decimal('amount', 32, 12)->nullable();
            $table->text('txt_id')->nullable();
            $table->tinyInteger('section')->comment('1: Gate Way, 2: Wallet');
            $table->tinyInteger('type')->comment('1: Deposit, 2: Withdraw');
            $table->tinyInteger('status')->default(1)->comment('1: Waiting, 2: Confirmed, 3: Rejected');
            $table->text('description')->nullable();
            $table->string('gateway')->nullable()->comment('only use in irt section gateway => name of used gateway');
            $table->string('sheba')->nullable();
            $table->string('address')->nullable()->comment('wallet address destiny in currency withdraw');
            $table->string('tag')->nullable()->comment('wallet tag destiny in currency withdraw');
            $table->decimal('fee', 30, 10)->nullable();
            $table->decimal('final_amount', 32, 12)->nullable();
            $table->timestamps();

            // Foreign Key Constraints
            $table->foreign('wallet_id')->references('id')->on('user_wallets')->onDelete('cascade');
            $table->foreign('asset_id')->references('id')->on('user_assets')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade');
            $table->foreign('network_id')->references('id')->on('networks')->onDelete('cascade');
            $table->foreign('bank_id')->references('id')->on('banks')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_transactions');
    }
};
