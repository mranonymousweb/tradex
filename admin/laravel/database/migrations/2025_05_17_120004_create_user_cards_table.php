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
        Schema::create('user_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('bank_id')->nullable()->constrained('banks')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('currency_id')->nullable()->constrained()->onDelete('set null')->onUpdate('cascade');
            $table->string('card_owner')->nullable();
            $table->string('card_number')->nullable();
            $table->string('iban')->nullable();
            $table->tinyText('status')->default('waiting');
            $table->text('description')->nullable();
            $table->string('account_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_cards');
    }
};
