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
        Schema::create('supplier_logs', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('loggable');
            $table->foreignId('currency_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->string( 'pairs', 22)->nullable();
            $table->string('supplier', 150);
            $table->decimal('amount', 15, 4)->default(0);
            $table->decimal('total', 15, 4)->default(0);
            $table->text('description')->nullable();
            $table->json('meta')->nullable();
            $table->unsignedTinyInteger('status')->default(1)->comment('1: watching, 2: confirmed, 3: rejected');
            $table->timestamps();
            $table->index('pairs');
            $table->index('status');
            $table->index('supplier');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_logs');
    }
};
