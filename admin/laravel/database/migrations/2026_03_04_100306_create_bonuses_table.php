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
        Schema::create('bonuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('currency_id')->constrained()->cascadeOnUpdate()->nullOnUpdate();
            $table->string('symbol')->nullable();
            $table->string('section');
            $table->decimal('amount', 20, 10)->default(0);
            $table->integer('days')->nullable();
            $table->decimal('expected_amount', 20, 10)->nullable();
            $table->decimal('inventory', 20, 10)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bonuses');
    }
};
