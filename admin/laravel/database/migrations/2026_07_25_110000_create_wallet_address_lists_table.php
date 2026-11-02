<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('wallet_address_lists', function (Blueprint $table) {
            $table->id();
            $table->string('address')->index();
            $table->string('type')->index(); // white | black
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique('address');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_address_lists');
    }
};
