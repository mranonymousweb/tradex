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
        Schema::create('supplier_networks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->unsignedBigInteger('network_id')->nullable();
            $table->string('symbol');
            $table->boolean('is_withdraw_able')->default(false);
            $table->boolean('is_deposit_able')->default(false);
            $table->boolean('has_tag')->default(false);
            $table->decimal('fee',15,5)->default(0);
            $table->decimal('min_deposit', 30, 15)->nullable();
            $table->decimal('max_withdraw', 30, 15)->nullable();
            $table->decimal('min_withdraw', 30, 15)->nullable();
            $table->tinyInteger('status')->default(1)->comment('1:Active, 2:Inactive');
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->unique([
                'supplier_id',
                'symbol'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_networks');
    }
};
