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
        Schema::create('feature_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->constrained('feature_orders')->cascadeOnUpdate()->cascadeOnDelete();
            $table->decimal('fee', 50, 30);
            $table->tinyInteger('type')->default(1)->comment('1: main, 2: period, 3: other');
            $table->jsonb('meta')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feature_histories');
    }
};
