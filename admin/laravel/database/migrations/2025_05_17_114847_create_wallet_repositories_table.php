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
        Schema::create('wallet_repositories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('network_id');
            $table->string('symbol')->nullable()->comment('network symbol');
            $table->text('address');
            $table->text('tag')->nullable();
            $table->tinyInteger('type')->comment('1: share, 2: dedicate');
            $table->boolean('is_used')->default(false);
            $table->tinyInteger('status')->default(1)->comment('1: Active, 2: Inactive');
            $table->string('supplier')->nullable();
            $table->bigInteger('nounce')->nullable()->comment('this field is used only for bitycle');
            $table->timestamps();
            $table->foreign('network_id')->references('id')->on('networks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_repositories');
    }
};
