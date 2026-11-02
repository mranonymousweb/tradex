<?php

use App\Models\UserStaking;
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
        Schema::create('user_stakings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staking_id')->nullable()->constrained('stakings')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('currency_id')->nullable()->constrained('currencies')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->decimal('amount', 30, 15);
            $table->unsignedInteger('day');
            $table->decimal('daily_profit_percent', 30, 15);
            $table->enum('type', [
                UserStaking::TYPE_DAILY,
                UserStaking::TYPE_WEEKLY,
                UserStaking::TYPE_MONTHLY,
                UserStaking::TYPE_END
            ])->default(UserStaking::TYPE_DAILY)
                ->comment('daily: daily payout, weekly: weekly payout, monthly: monthly payout, end: payout at the end of the period');
            $table->enum('status', [
                UserStaking::STATUS_ACTIVE,
                UserStaking::STATUS_INACTIVE,
                UserStaking::STATUS_WETTING
            ])->default(UserStaking::STATUS_WETTING);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_stakings');
    }
};
