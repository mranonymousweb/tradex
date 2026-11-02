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
        Schema::create('admin_cards', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('admin_id')->nullable();
            $table->string('card_owner')->nullable();
            $table->string('card_number')->nullable();
            $table->foreignId('bank_id')->nullable()->constrained('banks')->onDelete('cascade')->onUpdate('cascade');
            $table->string('iban')->nullable();
            $table->enum('status', [\App\Models\Option::ACTIVE, \App\Models\Option::INACTIVE])->nullable();
            $table->foreignId('currency_id')->constrained('currencies')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_cards');
    }
};
