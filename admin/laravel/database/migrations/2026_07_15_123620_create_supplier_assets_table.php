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
        Schema::create('supplier_assets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->string('network', 30)->nullable();
            $table->string('symbol', 20)->nullable();
            $table->string('address', 255)->unique();
            $table->decimal('balance', 50, 12)->default(0);
            $table->timestamp('last_checked_at')->nullable();
            $table->timestamps();
            $table->index(['supplier_id', 'symbol']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_assets');
    }
};
