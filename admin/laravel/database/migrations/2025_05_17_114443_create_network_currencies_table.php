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
        Schema::create('network_currencies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('currency_id');
            $table->unsignedBigInteger('network_id');
            $table->decimal('fee', 30, 15)->nullable();
            $table->boolean('is_withdraw_able')->default(false);
            $table->boolean('is_deposit_able')->default(false);
            $table->tinyInteger('type')->comment('1: Web Service(api), 2: Manual');
            $table->decimal('min_deposit', 30, 15)->nullable();
            $table->decimal('max_withdraw', 30, 15)->nullable();
            $table->decimal('min_withdraw', 30, 15)->nullable();
            $table->tinyInteger('status')->default(1)->comment('1: Active, 2: Inactive');
            $table->timestamps();

            // Foreign Key Constraints
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade');
            $table->foreign('network_id')->references('id')->on('networks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('network_currencies');
    }
};
