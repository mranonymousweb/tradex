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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('currency_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');

            $table->morphs('transactionable');
            $table->tinyInteger('type');
            $table->decimal('amount', 50, 30);
            $table->tinyInteger('section');
            $table->string('reference')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_site_transaction')->default(false)->index();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
