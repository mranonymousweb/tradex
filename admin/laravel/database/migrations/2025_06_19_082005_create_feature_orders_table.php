<?php

use App\Models\FeatureOrder;
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
        Schema::create('feature_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->unsignedBigInteger('main_currency_id')->nullable()->index();
            $table->foreign('main_currency_id')->references('id')->on('currencies')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->string('main_currency_symbol', 50)->nullable()->index();
            $table->unsignedBigInteger('base_currency_id')->nullable()->index();
            $table->foreign('base_currency_id')->references('id')->on('currencies')->onDelete('SET NULL')->onUpdate('CASCADE');
            $table->string('base_currency_symbol', 50)->nullable()->index();
            $table->string('pairs');
            $table->boolean('is_robot')->default(false);
            $table->boolean('has_tpsl')->default(false);
            $table->decimal('take_profit', 50, 30)->nullable();
            $table->decimal('stop_loss', 50, 30)->nullable();
            $table->decimal('enter_balance', 50, 30);
            $table->decimal('quantity', 50, 30);
            $table->decimal('price', 50, 30);
            $table->decimal('enter_price', 50, 30);
            $table->decimal('stop_price', 50, 30)->nullable();
            $table->decimal('exit_price', 50, 30)->nullable();
            $table->string('exit_type')->nullable();
            $table->decimal('liq_price', 50, 30);
            $table->decimal('cost', 50, 30);
            $table->decimal('fee', 50, 30);
            $table->decimal('pnl', 50, 30)->nullable();
            $table->tinyInteger('side')->default(FeatureOrder::SIDE_LONG);
            $table->tinyInteger('type')->default(FeatureOrder::TYPE_MARKET);
            $table->tinyInteger('leverage')->default(1);
            $table->tinyInteger('margin_mode')->default(FeatureOrder::MARGIN_MODE_CROSS);
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamp('liquidation_at')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1:pinging ,2:created,3:closed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feature_orders');
    }
};
