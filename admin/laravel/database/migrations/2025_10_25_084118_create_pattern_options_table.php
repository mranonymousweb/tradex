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
        Schema::create('pattern_options', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->tinyInteger('type')->default(1)->comment('1: sms, 2: email');
            $table->string('local')->default('fa');
            $table->longText('value');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->index(['type', 'local']);
            $table->unique(['key', 'type', 'local'], 'pattern_options_unique_key_type_local');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pattern_options');
    }
};
