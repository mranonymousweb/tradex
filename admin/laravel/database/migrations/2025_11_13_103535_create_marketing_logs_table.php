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
        Schema::create('marketing_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('SET NULL')->onUpdate('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('users')->onDelete('SET NULL')->onUpdate('cascade');
            $table->foreignId('currency_id')->nullable()->constrained('currencies')->onDelete('SET NULL')->onUpdate('cascade');
            $table->decimal('total_amount', 40, 20)->default(0);
            $table->decimal('user_amount', 40, 20)->default(0);
            $table->decimal('parent_amount', 40, 20)->default(0);
            $table->string('section')->nullable();
            $table->string('symbol')->nullable();
            $table->nullableMorphs('loggable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_logs');
    }
};
