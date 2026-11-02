<?php

use App\Models\Staking;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stakings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('currency_id')->nullable()->constrained('currencies')->nullOnDelete()->cascadeOnUpdate();
            $table->decimal('min_amount', 30, 15);
            $table->decimal('max_amount', 30, 15);
            $table->integer('marketing')->default(0);
            $table->unsignedInteger('min_day');
            $table->decimal('daily_profit_percent', 30, 15);
            $table->enum('type', [
                Staking::TYPE_DAILY,
                Staking::TYPE_WEEKLY,
                Staking::TYPE_MONTHLY,
                Staking::TYPE_END
            ])->default(Staking::TYPE_DAILY)
                ->comment('daily: daily payout, weekly: weekly payout, monthly: monthly payout, end: payout at the end of the period');
            $table->boolean('accepted_by_admin')->default(true);
            $table->enum('status', [
                Staking::STATUS_ACTIVE,
                Staking::STATUS_INACTIVE
            ])->default(Staking::STATUS_ACTIVE);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stakings');
    }
};
