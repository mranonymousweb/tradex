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
        Schema::create('open_bank_transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->string('type')->index();

            $table->bigInteger('amount');
            $table->bigInteger('balance')->nullable();

            $table->string('source_account')->nullable();
            $table->string('destination_account')->nullable();


            $table->string('transaction_trace')->unique();

            $table->string('transaction_date_time');
            $table->dateTime('transaction_date')->nullable();

            $table->bigInteger('debit_amount')->nullable();
            $table->bigInteger('credit_amount')->nullable();

            $table->string('description')->nullable();
            $table->string('transaction_identity')->nullable();


            $table->boolean('is_used')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('open_bank_transactions');
    }
};
