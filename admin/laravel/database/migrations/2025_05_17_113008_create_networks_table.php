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


        Schema::create('networks', function (Blueprint $table) {
            $table->id();
            $table->string('symbol');
            $table->string('icon')->nullable();
            $table->integer('priority')->default(1);
            $table->boolean('is_withdraw_able')->default(false);
            $table->boolean('is_deposit_able')->default(false);
            $table->boolean('has_tag')->default(false);
            $table->tinyInteger('status')->default(1)->comment('1:Active, 2:Inactive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('networks');
    }
};
