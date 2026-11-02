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
        Schema::create('markets', function (Blueprint $table) {
            $table->id()->startingValue(1230);
            // --- Foreign Keys ---
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('main_currency_id')->nullable()->constrained('currencies')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('base_currency_id')->nullable()->constrained('currencies')->nullOnDelete()->cascadeOnUpdate();
            // --- Currency symbols ---
            $table->string('main_currency_symbol', 50)->nullable();
            $table->string('base_currency_symbol', 50)->nullable();
            // --- Trade details ---
            $table->string('pairs', 100)->comment('e.g. BTC_USDT');
            $table->unsignedTinyInteger('side')->comment('1:Buy ; 2:Sell');
            $table->unsignedTinyInteger('type')->comment(' 1:Market;2:Limit  ; 3:StopLimit ; 4:LimitMaker ; 5:StopMarket');
            $table->unsignedTinyInteger('leverage')->default(1);
            // --- Financial values ---
            $table->decimal('amount', 32, 16)->comment('main currency amount');
            $table->decimal('price', 32, 16)->default(0);
            $table->decimal('avg_price', 32, 16)->default(0);
            $table->decimal('total', 32, 16)->default(0);
            $table->decimal('stop_price', 32, 16)->default(0);
            $table->decimal('market_price', 32, 16)->default(0);
            $table->decimal('filled_amount', 32, 16)->default(0);
            $table->decimal('filled_receive_amount', 32, 16)->default(0);
            $table->decimal('filled_total', 32, 16)->default(0);
            $table->decimal('fee', 32, 16);
            $table->decimal('fee_percent', 20, 8)->default(0);
            $table->unsignedTinyInteger('status')->default(1);
            $table->jsonb('meta')->nullable();
            // --- Timestamps ---
            $table->timestamp('open_at')->nullable();
            $table->timestamp('done_at')->nullable();
            $table->timestamp('canceled_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            // --- Essential Indexes for Performance ---
            $table->index(['user_id', 'pairs'], 'idx_user_pairs');
            $table->index(['user_id', 'status'], 'idx_user_status');
            $table->index(['user_id', 'side'], 'idx_user_side');
            $table->index(['user_id', 'type'], 'idx_user_type');
            $table->index(['user_id', 'filled_amount'], 'idx_user_filled_amount');
            $table->index(['user_id', 'filled_total'], 'idx_user_filled_total');
            $table->index(['user_id', 'filled_receive_amount'], 'idx_user_filled_receive');

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('markets');
    }
};
