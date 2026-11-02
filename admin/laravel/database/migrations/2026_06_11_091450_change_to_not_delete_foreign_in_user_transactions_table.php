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
        Schema::table('user_transactions', function (Blueprint $table) {
            Schema::table('user_transactions', function (Blueprint $table) {


                // nullable کردن FK هایی که nullable نیستند
                $table->unsignedBigInteger('user_id')->nullable()->change();
                $table->unsignedBigInteger('currency_id')->nullable()->change();

                // حذف FK های قبلی
                $table->dropForeign(['wallet_id']);
                $table->dropForeign(['asset_id']);
                $table->dropForeign(['user_id']);
                $table->dropForeign(['currency_id']);
                $table->dropForeign(['network_id']);

                // FK های جدید
                $table->foreign('wallet_id')
                    ->references('id')
                    ->on('user_wallets')
                    ->nullOnDelete();

                $table->foreign('asset_id')
                    ->references('id')
                    ->on('user_assets')
                    ->nullOnDelete();

                $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->nullOnDelete();

                $table->foreign('currency_id')
                    ->references('id')
                    ->on('currencies')
                    ->nullOnDelete();

                $table->foreign('network_id')
                    ->references('id')
                    ->on('networks')
                    ->nullOnDelete();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_transactions', function (Blueprint $table) {
            $table->dropColumn('supplier');

            $table->dropForeign(['wallet_id']);
            $table->dropForeign(['asset_id']);
            $table->dropForeign(['user_id']);
            $table->dropForeign(['currency_id']);
            $table->dropForeign(['network_id']);

            $table->foreign('wallet_id')
                ->references('id')
                ->on('user_wallets')
                ->cascadeOnDelete();

            $table->foreign('asset_id')
                ->references('id')
                ->on('user_assets')
                ->cascadeOnDelete();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();

            $table->foreign('currency_id')
                ->references('id')
                ->on('currencies')
                ->cascadeOnDelete();

            $table->foreign('network_id')
                ->references('id')
                ->on('networks')
                ->cascadeOnDelete();

            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            $table->unsignedBigInteger('currency_id')->nullable(false)->change();
        });
    }
};
