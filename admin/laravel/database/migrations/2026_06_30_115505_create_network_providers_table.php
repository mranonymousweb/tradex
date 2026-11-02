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
        Schema::create('network_providers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->unsignedBigInteger('network_id')->nullable();
            $table->string('symbol');
            $table->tinyInteger('type')->default(1)->comment('1:Wallet, 2:Transaction,3:Fee');
            $table->json('meta')->nullable();
            $table->unique([
                'network_id',
                'type'
            ]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('network_providers');
    }
};
